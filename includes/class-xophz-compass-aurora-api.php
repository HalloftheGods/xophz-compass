<?php
/**
 * Aurora / North REST API Orchestrator
 *
 * Exposes the unified Compass AI chat endpoints:
 * - GET  /xophz/v1/aurora/status
 * - POST /xophz/v1/aurora/chat
 * - POST /xophz/v1/aurora/execute
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Aurora_API {

	private const MAX_TOOL_ROUNDS   = 5;
	private const RATE_LIMIT_PER_MIN = 30;
	private const CONFIRM_TTL_SECS   = 300;

	/**
	 * Register REST routes on `rest_api_init`.
	 */
	public function register_routes(): void {
		register_rest_route(
			'xophz/v1',
			'/aurora/status',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_status' ),
					'permission_callback' => array( $this, 'require_logged_in' ),
				),
			)
		);

		register_rest_route(
			'xophz/v1',
			'/aurora/chat',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'handle_chat' ),
					'permission_callback' => array( $this, 'require_logged_in' ),
				),
			)
		);

		register_rest_route(
			'xophz/v1',
			'/aurora/execute',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'handle_execute' ),
					'permission_callback' => array( $this, 'require_logged_in' ),
				),
			)
		);
	}

	public function require_logged_in(): bool {
		return is_user_logged_in();
	}

	/**
	 * GET /xophz/v1/aurora/status
	 */
	public function get_status(): WP_REST_Response {
		$providers = Xophz_Compass_AI_Providers::get_public_providers();
		$tools     = Xophz_Compass_Aurora_Abilities::get_authorized_tools();

		return rest_ensure_response(
			array(
				'success'       => true,
				'connected'     => ! empty( $providers ),
				'providers'     => $providers,
				'ability_count' => count( $tools ),
			)
		);
	}

	/**
	 * POST /xophz/v1/aurora/chat
	 */
	public function handle_chat( WP_REST_Request $request ) {
		$rate_error = $this->enforce_rate_limit();
		if ( is_wp_error( $rate_error ) ) {
			return $rate_error;
		}

		$raw_messages   = $request->get_param( 'messages' );
		$provider_id    = $request->get_param( 'provider' );
		$model_override = $request->get_param( 'model' );
		$effort_param   = sanitize_key( (string) ( $request->get_param( 'effort' ) ?? 'fast' ) );
		$effort         = in_array( $effort_param, array( 'fast', 'balanced', 'deep' ), true ) ? $effort_param : 'fast';
		$client_context = $request->get_param( 'client_context' );

		if ( ! is_array( $raw_messages ) || empty( $raw_messages ) ) {
			return new WP_Error( 'aurora_empty_messages', 'Messages array is required.', array( 'status' => 400 ) );
		}

		$provider = Xophz_Compass_AI_Providers::resolve_provider( is_string( $provider_id ) ? $provider_id : null );
		if ( null === $provider ) {
			return new WP_Error(
				'aurora_no_provider',
				'No AI chat connector is configured. Connect Gemini, OpenAI, Anthropic, OpenRouter, or Ollama in Connectors.',
				array( 'status' => 503 )
			);
		}

		if ( is_string( $model_override ) && '' !== trim( $model_override ) ) {
			$provider['model'] = sanitize_text_field( $model_override );
		}

		$messages              = $this->sanitize_messages( $raw_messages );
		$context               = is_array( $client_context ) ? $client_context : array();
		$tools                 = Xophz_Compass_Aurora_Abilities::get_authorized_tools();
		$system_prompt         = $this->build_system_prompt( $context, $tools );
		$client_actions        = array();
		$pending_confirmations = array();
		$executed_tools        = array();
		$final_text            = '';
		$total_usage           = array(
			'prompt_tokens'     => 0,
			'completion_tokens' => 0,
			'total_tokens'      => 0,
		);

		for ( $round = 0; $round < self::MAX_TOOL_ROUNDS; $round++ ) {
			$turn = Xophz_Compass_AI_Providers::chat_turn( $provider, $system_prompt, $messages, $tools, $effort );
			if ( is_wp_error( $turn ) ) {
				return $turn;
			}

			if ( ! empty( $turn['usage'] ) && is_array( $turn['usage'] ) ) {
				$total_usage['prompt_tokens']     += (int) ( $turn['usage']['prompt_tokens'] ?? 0 );
				$total_usage['completion_tokens'] += (int) ( $turn['usage']['completion_tokens'] ?? 0 );
				$total_usage['total_tokens']      += (int) ( $turn['usage']['total_tokens'] ?? 0 );
			}

			$final_text = $turn['text'];
			$calls      = $turn['tool_calls'];

			if ( empty( $calls ) ) {
				break;
			}

			$messages[] = array(
				'role'       => 'assistant',
				'content'    => $final_text,
				'tool_calls' => $calls,
			);

			foreach ( $calls as $tc ) {
				$tool_name = (string) ( $tc['name'] ?? '' );
				$call_id   = (string) ( $tc['id'] ?? wp_generate_uuid4() );
				$args      = isset( $tc['arguments'] ) && is_array( $tc['arguments'] ) ? $tc['arguments'] : array();
				$tool      = $tools[ $tool_name ] ?? ( $tools[ 'compass__' . $tool_name ] ?? ( $tools[ 'compass_ui__' . $tool_name ] ?? null ) );

				if ( null === $tool ) {
					$messages[] = array(
						'role'         => 'tool',
						'tool_call_id' => $call_id,
						'tool_name'    => $tool_name,
						'result'       => array( 'error' => 'Tool not available or unauthorized.' ),
					);
					continue;
				}

				// Client UI action: queue for frontend shell execution.
				if ( 'client_ui' === ( $tool['kind'] ?? '' ) ) {
					$client_actions[] = array(
						'id'        => $tool['id'],
						'arguments' => $args,
					);
					$messages[]       = array(
						'role'         => 'tool',
						'tool_call_id' => $call_id,
						'tool_name'    => $tool_name,
						'result'       => array( 'queued_for_ui' => true, 'arguments' => $args ),
					);
					continue;
				}

				// Mutating server ability: pause and issue a one-time confirmation token.
				if ( ! empty( $tool['mutating'] ) ) {
					$token                   = $this->create_confirmation_token( $tool['id'], $args );
					$pending_confirmations[] = array(
						'token'       => $token,
						'ability_id'  => $tool['id'],
						'name'        => $tool['name'],
						'plugin'      => $tool['plugin'],
						'description' => $tool['description'],
						'arguments'   => $args,
					);
					$messages[]              = array(
						'role'         => 'tool',
						'tool_call_id' => $call_id,
						'tool_name'    => $tool_name,
						'result'       => array(
							'status'  => 'awaiting_user_confirmation',
							'message' => 'This action modifies site state and is waiting for the user to click Approve.',
						),
					);
					continue;
				}

				// Read-only server ability: execute immediately.
				$exec             = Xophz_Compass_Aurora_Abilities::execute( $tool['id'], $args );
				$executed_tools[] = array(
					'ability_id' => $tool['id'],
					'name'       => $tool['name'],
					'ok'         => $exec['ok'],
				);
				$messages[]       = array(
					'role'         => 'tool',
					'tool_call_id' => $call_id,
					'tool_name'    => $tool_name,
					'result'       => $exec['ok'] ? $exec['data'] : array( 'error' => $exec['error'] ),
				);
			}
		}

		return rest_ensure_response(
			array(
				'success'               => true,
				'reply'                 => $final_text,
				'provider'              => $provider['id'],
				'model'                 => $provider['model'],
				'effort'                => $effort,
				'usage'                 => $total_usage,
				'client_actions'        => $client_actions,
				'pending_confirmations' => $pending_confirmations,
				'executed_tools'        => $executed_tools,
			)
		);
	}

	/**
	 * POST /xophz/v1/aurora/execute
	 * Executes a user-approved mutating ability via its one-time signed token.
	 */
	public function handle_execute( WP_REST_Request $request ) {
		$token = sanitize_text_field( (string) $request->get_param( 'token' ) );
		if ( '' === $token ) {
			return new WP_Error( 'aurora_missing_token', 'Confirmation token is required.', array( 'status' => 400 ) );
		}

		$transient_key = 'aurora_confirm_' . $token;
		$payload       = get_transient( $transient_key );

		if ( ! is_array( $payload ) ) {
			return new WP_Error( 'aurora_invalid_token', 'Confirmation token expired or not found.', array( 'status' => 404 ) );
		}

		if ( (int) ( $payload['user_id'] ?? 0 ) !== get_current_user_id() ) {
			return new WP_Error( 'aurora_forbidden_token', 'Token does not belong to the current user.', array( 'status' => 403 ) );
		}

		$ability_id = (string) ( $payload['ability_id'] ?? '' );
		$arguments  = isset( $payload['arguments'] ) && is_array( $payload['arguments'] ) ? $payload['arguments'] : array();
		$override   = $request->get_param( 'arguments_override' );

		if ( is_array( $override ) && ! empty( $override ) ) {
			// Preserve immutable route/method on REST bridge calls while allowing user payload edits.
			if ( 'compass/call-rest-write' === $ability_id ) {
				if ( isset( $override['payload_json'] ) && is_string( $override['payload_json'] ) ) {
					$arguments['payload_json'] = $override['payload_json'];
				}
			} else {
				$arguments = array_merge( $arguments, $override );
			}
		}

		$result = Xophz_Compass_Aurora_Abilities::execute( $ability_id, $arguments );

		if ( ! $result['ok'] ) {
			return new WP_Error( 'aurora_execute_failed', (string) $result['error'], array( 'status' => 400 ) );
		}

		delete_transient( $transient_key );

		return rest_ensure_response(
			array(
				'success'    => true,
				'ability_id' => $ability_id,
				'data'       => $result['data'],
			)
		);
	}

	private function create_confirmation_token( string $ability_id, array $arguments ): string {
		$token = wp_generate_password( 24, false, false );
		set_transient(
			'aurora_confirm_' . $token,
			array(
				'user_id'    => get_current_user_id(),
				'ability_id' => $ability_id,
				'arguments'  => $arguments,
				'created_at' => time(),
			),
			self::CONFIRM_TTL_SECS
		);
		return $token;
	}

	private function enforce_rate_limit() {
		$key   = 'aurora_rate_limit_' . get_current_user_id();
		$usage = (int) get_transient( $key );
		if ( $usage >= self::RATE_LIMIT_PER_MIN ) {
			return new WP_Error( 'aurora_rate_limit', 'Too many chat requests. Please wait a moment.', array( 'status' => 429 ) );
		}
		set_transient( $key, $usage + 1, MINUTE_IN_SECONDS );
		return true;
	}

	/**
	 * @param array<int, mixed> $raw_messages
	 * @return array<int, array<string, mixed>>
	 */
	private function sanitize_messages( array $raw_messages ): array {
		$clean = array();
		$slice = array_slice( $raw_messages, -20 );

		foreach ( $slice as $msg ) {
			if ( ! is_array( $msg ) ) {
				continue;
			}
			$role    = (string) ( $msg['role'] ?? 'user' );
			$content = trim( (string) ( $msg['content'] ?? '' ) );
			if ( '' === $content ) {
				continue;
			}
			$clean[] = array(
				'role'    => 'assistant' === $role ? 'assistant' : 'user',
				'content' => $content,
			);
		}

		return $clean;
	}

	/**
	 * Build a concise, capability-aware system prompt grounded in current Compass context.
	 */
	private function build_system_prompt( array $context, array $tools ): string {
		$display_name  = sanitize_text_field( (string) ( $context['display_name'] ?? 'North' ) );
		$current_route = sanitize_text_field( (string) ( $context['current_route'] ?? '/' ) );
		$active_ctx    = sanitize_text_field( (string) ( $context['active_context'] ?? '' ) );
		$site_name     = get_bloginfo( 'name' );

		$lines = array(
			sprintf( 'You are %s, the built-in intelligence and systems officer for %s (powered by Compass).', $display_name, $site_name ),
			'Be concise, direct, and action-oriented. Never invent fake data, routes, or command IDs.',
			sprintf( 'Current route: %s', $current_route ),
		);

		if ( '' !== $active_ctx ) {
			$lines[] = sprintf( 'Active page context: %s', $active_ctx );
		}

		if ( ! empty( $context['palette_commands'] ) && is_array( $context['palette_commands'] ) ) {
			$cmd_lines = array();
			foreach ( array_slice( $context['palette_commands'], 0, 80 ) as $cmd ) {
				if ( ! is_array( $cmd ) ) {
					continue;
				}
				$id    = sanitize_text_field( (string) ( $cmd['id'] ?? '' ) );
				$title = sanitize_text_field( (string) ( $cmd['title'] ?? '' ) );
				$route = sanitize_text_field( (string) ( $cmd['route'] ?? '' ) );
				$cat   = sanitize_text_field( (string) ( $cmd['categoryLabel'] ?? '' ) );
				if ( '' !== $id && '' !== $title ) {
					$cmd_lines[] = sprintf( '- [%s] %s (%s)%s', $id, $title, $cat, '' !== $route ? ' -> ' . $route : '' );
				}
			}
			if ( ! empty( $cmd_lines ) ) {
				$lines[] = "Available Compass routes and commands:\n" . implode( "\n", $cmd_lines );
			}
		}

		$lines[] = sprintf( 'You have %d authorized tools available, including compass__discover-endpoints, compass__call-rest-read, compass__call-rest-write, and compass__find-product-images to manage the entire WordPress, WooCommerce, and Compass ecosystem. When a user asks to create a product (such as "Nintendo 64" or "12 Coloring books $1 each"), call compass__discover-endpoints to verify the route and call compass__find-product-images to find matching images from the site library or external sources; include {"images":[{"id":<attachment_id>}]} for local images or {"images":[{"src":"<url>"}]} for external images in payload_json. Always propose compass__call-rest-write with next_route and next_label pointing to the Compass edit/list screen so the user can review/edit and approve it.', count( $tools ) );

		$lines[] = 'Field rules: scout before you act (discover-endpoints before any REST call, read before write). Prefer navigate_route or run_palette_command when the manifest already covers the request. Prefer drafts and approval cards over silent changes. Say what you are about to do in one line. Empty results are a valid answer: say "nothing found".';
		$lines[] = 'When you cannot do what the user asked (no ability, endpoint or palette command covers it, a tool lacks a needed field or action, a tool errors, a manifest route or command ID does not resolve, or permissions block a reasonable task), do not just apologize. First give the user the manual UI path as a fallback. Then offer to file a bug report so the software improves: search existing reports with compass/call-rest-read on the compass_bug post type to avoid duplicates, then propose compass/call-rest-write creating a compass_bug with title "[North] <one-line gap>", content (what was asked, what you tried, what happened, what ability would have worked), bug_type (missing-capability, missing-feature, broken-tool, manifest-drift or permission), bug_status "new", bug_priority (low, medium or high), bug_plugin if known, and bug_url set to the current route. Never include secrets or personal data in a report.';

		return implode( "\n", $lines );
	}
}
