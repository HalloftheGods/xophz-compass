<?php
/**
 * Aurora / North Abilities Registry
 *
 * Collects, normalizes, authorizes, and executes Compass domain abilities
 * registered via the `compass_abilities_registry` filter and the native
 * WordPress Abilities API (`wp_get_abilities`).
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Aurora_Abilities {

	/**
	 * Convert a canonical ability ID into an LLM-safe function name ([a-zA-Z0-9_-]).
	 */
	public static function to_tool_name( string $ability_id ): string {
		$safe = preg_replace( '/[^a-zA-Z0-9_-]/', '_', str_replace( '/', '__', $ability_id ) );
		$safe = trim( (string) $safe, '_' );
		if ( '' === $safe || is_numeric( $safe[0] ) ) {
			$safe = 'ability_' . $safe;
		}
		return substr( $safe, 0, 64 );
	}

	/**
	 * Built-in core Compass abilities so Aurora / North is immediately useful.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function get_core_abilities(): array {
		return array(
			array(
				'id'                  => 'compass/get-site-overview',
				'name'                => 'Get Site Overview',
				'plugin'              => 'Compass Core',
				'category'            => 'System',
				'description'         => 'Returns core WordPress site metadata: site name, tagline, URL, active theme, WP version, and post/user counts.',
				'mutating'            => false,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => new stdClass(),
				),
				'permission_callback' => static function (): bool {
					return is_user_logged_in();
				},
				'execute_callback'    => static function (): array {
					$post_counts = wp_count_posts( 'post' );
					$page_counts = wp_count_posts( 'page' );
					$user_counts = count_users();
					$theme       = wp_get_theme();

					return array(
						'site_name'       => get_bloginfo( 'name' ),
						'tagline'         => get_bloginfo( 'description' ),
						'site_url'        => home_url( '/' ),
						'admin_url'       => admin_url(),
						'wp_version'      => get_bloginfo( 'version' ),
						'active_theme'    => $theme ? $theme->get( 'Name' ) : '',
						'published_posts' => isset( $post_counts->publish ) ? (int) $post_counts->publish : 0,
						'published_pages' => isset( $page_counts->publish ) ? (int) $page_counts->publish : 0,
						'total_users'     => isset( $user_counts['total_users'] ) ? (int) $user_counts['total_users'] : 0,
					);
				},
			),
			array(
				'id'                  => 'compass/list-installed-sparks',
				'name'                => 'List Installed Sparks',
				'plugin'              => 'Compass Core',
				'category'            => 'System',
				'description'         => 'Lists active WordPress plugins and Compass Sparks installed on this site.',
				'mutating'            => false,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'compass_only' => array(
							'type'        => 'boolean',
							'description' => 'When true, return only Xophz Compass suite plugins.',
						),
					),
				),
				'permission_callback' => static function (): bool {
					return current_user_can( 'manage_options' );
				},
				'execute_callback'    => static function ( array $args ): array {
					if ( ! function_exists( 'get_plugins' ) ) {
						require_once ABSPATH . 'wp-admin/includes/plugin.php';
					}
					$all_plugins  = get_plugins();
					$active       = (array) get_option( 'active_plugins', array() );
					$compass_only = ! empty( $args['compass_only'] );
					$results      = array();

					foreach ( $all_plugins as $file => $data ) {
						$is_compass = false !== stripos( $file, 'xophz' ) || false !== stripos( (string) ( $data['Name'] ?? '' ), 'compass' );
						if ( $compass_only && ! $is_compass ) {
							continue;
						}
						$results[] = array(
							'slug'       => dirname( $file ),
							'name'       => (string) ( $data['Name'] ?? $file ),
							'version'    => (string) ( $data['Version'] ?? '' ),
							'active'     => in_array( $file, $active, true ),
							'is_compass' => $is_compass,
						);
					}

					return array(
						'count'   => count( $results ),
						'plugins' => $results,
					);
				},
			),
			array(
				'id'                  => 'compass/search-content',
				'name'                => 'Search Site Content',
				'plugin'              => 'Compass Core',
				'category'            => 'Content',
				'description'         => 'Searches WordPress posts and pages by keyword and returns titles, statuses, dates, and URLs.',
				'mutating'            => false,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'query'     => array(
							'type'        => 'string',
							'description' => 'Search keyword or phrase.',
						),
						'post_type' => array(
							'type'        => 'string',
							'description' => 'Post type to search (post, page, or any). Defaults to any.',
						),
						'limit'     => array(
							'type'        => 'integer',
							'description' => 'Maximum number of results (1-20). Defaults to 8.',
						),
					),
					'required'   => array( 'query' ),
				),
				'permission_callback' => static function (): bool {
					return current_user_can( 'edit_posts' );
				},
				'execute_callback'    => static function ( array $args ): array {
					$query     = sanitize_text_field( (string) ( $args['query'] ?? '' ) );
					$post_type = sanitize_key( (string) ( $args['post_type'] ?? 'any' ) );
					$limit     = max( 1, min( 20, (int) ( $args['limit'] ?? 8 ) ) );

					if ( '' === $query ) {
						return array( 'count' => 0, 'items' => array() );
					}

					$posts = get_posts(
						array(
							's'              => $query,
							'post_type'      => '' !== $post_type ? $post_type : 'any',
							'post_status'    => array( 'publish', 'draft', 'pending' ),
							'posts_per_page' => $limit,
						)
					);

					$items = array();
					foreach ( $posts as $post ) {
						$items[] = array(
							'id'     => (int) $post->ID,
							'title'  => get_the_title( $post ),
							'type'   => $post->post_type,
							'status' => $post->post_status,
							'date'   => $post->post_date,
							'url'    => get_permalink( $post ),
						);
					}

					return array(
						'count' => count( $items ),
						'items' => $items,
					);
				},
			),
			array(
				'id'                  => 'compass/create-draft-post',
				'name'                => 'Create Draft Post',
				'plugin'              => 'Compass Core',
				'category'            => 'Content',
				'description'         => 'Creates a new draft WordPress post with the given title and content. Requires user confirmation.',
				'mutating'            => true,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'title'   => array(
							'type'        => 'string',
							'description' => 'Post title.',
						),
						'content' => array(
							'type'        => 'string',
							'description' => 'Post body content (HTML or plain text).',
						),
						'post_type' => array(
							'type'        => 'string',
							'description' => 'Post type (post or page). Defaults to post.',
						),
					),
					'required'   => array( 'title' ),
				),
				'permission_callback' => static function (): bool {
					return current_user_can( 'edit_posts' );
				},
				'execute_callback'    => static function ( array $args ) {
					$title     = sanitize_text_field( (string) ( $args['title'] ?? '' ) );
					$content   = wp_kses_post( (string) ( $args['content'] ?? '' ) );
					$post_type = sanitize_key( (string) ( $args['post_type'] ?? 'post' ) );

					if ( ! in_array( $post_type, array( 'post', 'page' ), true ) ) {
						$post_type = 'post';
					}

					$post_id = wp_insert_post(
						array(
							'post_title'   => $title,
							'post_content' => $content,
							'post_type'    => $post_type,
							'post_status'  => 'draft',
						),
						true
					);

					if ( is_wp_error( $post_id ) ) {
						return $post_id;
					}

					return array(
						'post_id'  => (int) $post_id,
						'title'    => $title,
						'status'   => 'draft',
						'edit_url' => get_edit_post_link( $post_id, 'raw' ),
					);
				},
			),
			array(
				'id'                  => 'compass/send-email',
				'name'                => 'Send Email',
				'plugin'              => 'Compass Core',
				'category'            => 'Messaging',
				'description'         => 'Sends an email via WordPress mail (wp_mail) to a recipient. Defaults to the current logged-in admin email when "to" is omitted. Always requires user approval before sending.',
				'mutating'            => true,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'to'      => array(
							'type'        => 'string',
							'description' => 'Recipient email address. Defaults to the current user email if omitted.',
						),
						'subject' => array(
							'type'        => 'string',
							'description' => 'Email subject line.',
						),
						'body'    => array(
							'type'        => 'string',
							'description' => 'Email body content (plain text or simple HTML).',
						),
					),
					'required'   => array( 'subject', 'body' ),
				),
				'permission_callback' => static function (): bool {
					return current_user_can( 'manage_options' );
				},
				'execute_callback'    => static function ( array $args ) {
					$current_user = wp_get_current_user();
					$raw_to       = trim( (string) ( $args['to'] ?? '' ) );
					$to           = sanitize_email( '' !== $raw_to ? $raw_to : (string) ( $current_user->user_email ?? '' ) );
					$subject      = sanitize_text_field( (string) ( $args['subject'] ?? '' ) );
					$body         = wp_kses_post( (string) ( $args['body'] ?? '' ) );

					if ( '' === $to || ! is_email( $to ) ) {
						return new WP_Error( 'compass_invalid_email', 'A valid recipient email address is required.' );
					}
					if ( '' === $subject || '' === $body ) {
						return new WP_Error( 'compass_empty_email', 'Email subject and body are required.' );
					}

					$is_html = false !== strpos( $body, '<' ) && false !== strpos( $body, '>' );
					$headers = $is_html ? array( 'Content-Type: text/html; charset=UTF-8' ) : array();
					$sent    = wp_mail( $to, $subject, $body, $headers );

					if ( ! $sent ) {
						return new WP_Error( 'compass_mail_failed', 'WordPress wp_mail() failed to send the email.' );
					}

					return array(
						'sent'    => true,
						'to'      => $to,
						'subject' => $subject,
						'name'    => sprintf( 'Email to %s', $to ),
					);
				},
			),
		);
	}

	/**
	 * Built-in client UI tools executed in the Vue shell rather than on the server.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function get_client_ui_tools(): array {
		return array(
			array(
				'id'           => 'compass_ui/navigate_route',
				'tool_name'    => self::to_tool_name( 'compass_ui/navigate_route' ),
				'name'         => 'Navigate Compass Route',
				'description'  => 'Navigates the user to a Compass route path (for example /bazaar/products or /bomb-bag/campaigns/new). Only use routes present in the provided client navigation manifest.',
				'input_schema' => array(
					'type'       => 'object',
					'properties' => array(
						'route' => array(
							'type'        => 'string',
							'description' => 'Target Compass route path starting with /.',
						),
						'label' => array(
							'type'        => 'string',
							'description' => 'Short human-readable label for the destination.',
						),
					),
					'required'   => array( 'route' ),
				),
				'kind'         => 'client_ui',
				'mutating'     => false,
			),
			array(
				'id'           => 'compass_ui/run_palette_command',
				'tool_name'    => self::to_tool_name( 'compass_ui/run_palette_command' ),
				'name'         => 'Run Command Palette Action',
				'description'  => 'Executes a registered Compass Command Palette item by its exact command ID from the client manifest (for example bazaar-action-add-product).',
				'input_schema' => array(
					'type'       => 'object',
					'properties' => array(
						'command_id' => array(
							'type'        => 'string',
							'description' => 'Exact CommandItem.id from the provided palette commands list.',
						),
					),
					'required'   => array( 'command_id' ),
				),
				'kind'         => 'client_ui',
				'mutating'     => false,
			),
		);
	}

	/**
	 * Collect and normalize all registered abilities (core + plugin filter + WP Abilities API).
	 *
	 * @return array<string, array<string, mixed>> Keyed by canonical ability ID.
	 */
	public static function get_all(): array {
		$raw = self::get_core_abilities();

		if ( class_exists( 'Xophz_Compass_Aurora_REST_Bridge' ) ) {
			$raw = array_merge( $raw, Xophz_Compass_Aurora_REST_Bridge::get_abilities() );
		}

		$filtered = apply_filters( 'compass_abilities_registry', array() );
		if ( is_array( $filtered ) ) {
			$raw = array_merge( $raw, $filtered );
		}

		if ( function_exists( 'wp_get_abilities' ) ) {
			$wp_abilities = wp_get_abilities();
			if ( is_array( $wp_abilities ) ) {
				foreach ( $wp_abilities as $wp_id => $wp_ab ) {
					$ab_array = self::normalize_wp_ability( $wp_id, $wp_ab );
					if ( ! empty( $ab_array ) ) {
						$raw[] = $ab_array;
					}
				}
			}
		}

		$normalized = array();

		foreach ( $raw as $ab ) {
			if ( is_object( $ab ) ) {
				$ab = (array) $ab;
			}
			if ( ! is_array( $ab ) ) {
				continue;
			}

			$name = ! empty( $ab['name'] ) ? (string) $ab['name'] : ( ! empty( $ab['label'] ) ? (string) $ab['label'] : ( ! empty( $ab['id'] ) ? (string) $ab['id'] : '' ) );
			$id   = ! empty( $ab['id'] ) ? (string) $ab['id'] : ( '' !== $name ? sanitize_title( $name ) : '' );

			if ( '' === $name || '' === $id || isset( $normalized[ $id ] ) ) {
				continue;
			}

			$execute_cb   = isset( $ab['execute_callback'] ) && is_callable( $ab['execute_callback'] ) ? $ab['execute_callback'] : null;
			$is_wp_native = ! empty( $ab['_wp_native'] );
			$input_schema = isset( $ab['input_schema'] ) && is_array( $ab['input_schema'] )
				? $ab['input_schema']
				: array(
					'type'       => 'object',
					'properties' => new stdClass(),
				);

			$normalized[ $id ] = array(
				'id'                  => $id,
				'tool_name'           => self::to_tool_name( $id ),
				'name'                => $name,
				'plugin'              => ! empty( $ab['plugin'] ) ? (string) $ab['plugin'] : 'WordPress System',
				'category'            => ! empty( $ab['category'] ) ? ucfirst( (string) $ab['category'] ) : 'General',
				'description'         => ! empty( $ab['description'] ) ? (string) $ab['description'] : 'No description provided.',
				'input_schema'        => $input_schema,
				'mutating'            => ! empty( $ab['mutating'] ),
				'permission_callback' => isset( $ab['permission_callback'] ) ? $ab['permission_callback'] : null,
				'execute_callback'    => $execute_cb,
				'executable'          => ( null !== $execute_cb ) || $is_wp_native,
				'_wp_native'          => $is_wp_native,
			);
		}

		return $normalized;
	}

	/**
	 * Convert a native WP_Ability object or array into a normalized raw ability array.
	 *
	 * @param int|string $wp_id
	 * @param mixed      $wp_ab
	 * @return array<string, mixed>
	 */
	private static function normalize_wp_ability( $wp_id, $wp_ab ): array {
		if ( is_object( $wp_ab ) ) {
			$id   = method_exists( $wp_ab, 'get_name' ) ? (string) $wp_ab->get_name() : ( is_string( $wp_id ) ? $wp_id : '' );
			$meta = method_exists( $wp_ab, 'get_meta' ) ? (array) $wp_ab->get_meta() : array();
			$ann  = isset( $meta['annotations'] ) && is_array( $meta['annotations'] ) ? $meta['annotations'] : array();
			$ro   = array_key_exists( 'readonly', $ann ) ? (bool) $ann['readonly'] : false;

			return array(
				'id'           => $id,
				'name'         => method_exists( $wp_ab, 'get_label' ) ? (string) $wp_ab->get_label() : $id,
				'description'  => method_exists( $wp_ab, 'get_description' ) ? (string) $wp_ab->get_description() : '',
				'category'     => method_exists( $wp_ab, 'get_category' ) ? (string) $wp_ab->get_category() : 'General',
				'plugin'       => 'WordPress Abilities',
				'input_schema' => method_exists( $wp_ab, 'get_input_schema' ) ? (array) $wp_ab->get_input_schema() : array(),
				'mutating'     => ! $ro,
				'_wp_native'   => true,
			);
		}

		if ( is_array( $wp_ab ) ) {
			$wp_ab['_wp_native'] = true;
			if ( empty( $wp_ab['id'] ) && is_string( $wp_id ) ) {
				$wp_ab['id'] = $wp_id;
			}
			return $wp_ab;
		}

		return array();
	}

	/**
	 * Public metadata list for REST `/xophz/v1/abilities` (no closures or internal flags).
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function get_public_manifest(): array {
		$items = array();
		foreach ( self::get_all() as $ab ) {
			$items[] = array(
				'id'           => $ab['id'],
				'name'         => $ab['name'],
				'plugin'       => $ab['plugin'],
				'category'     => $ab['category'],
				'description'  => $ab['description'],
				'mutating'     => $ab['mutating'],
				'executable'   => $ab['executable'],
				'input_schema' => $ab['input_schema'],
			);
		}
		return $items;
	}

	/**
	 * Check whether the current WordPress user is permitted to invoke an ability.
	 */
	public static function check_permission( array $ability ): bool {
		$cb = $ability['permission_callback'] ?? null;

		if ( is_callable( $cb ) ) {
			return (bool) call_user_func( $cb );
		}

		if ( is_string( $cb ) && '' !== $cb ) {
			return current_user_can( $cb );
		}

		if ( ! empty( $ability['mutating'] ) ) {
			return current_user_can( 'manage_options' );
		}

		return is_user_logged_in();
	}

	/**
	 * Return all LLM-callable tools authorized for the current user, indexed by tool_name.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public static function get_authorized_tools(): array {
		$tools = array();

		foreach ( self::get_client_ui_tools() as $ui_tool ) {
			$tools[ $ui_tool['tool_name'] ] = $ui_tool;
		}

		foreach ( self::get_all() as $ab ) {
			if ( empty( $ab['executable'] ) ) {
				continue;
			}
			if ( ! self::check_permission( $ab ) ) {
				continue;
			}
			$ab['kind']                   = 'server_ability';
			$tools[ $ab['tool_name'] ]    = $ab;
		}

		return $tools;
	}

	/**
	 * Execute a registered server ability by canonical ID.
	 *
	 * @param string               $ability_id
	 * @param array<string, mixed> $arguments
	 * @return array{ok: bool, data: mixed, error: string|null}
	 */
	public static function execute( string $ability_id, array $arguments = array() ): array {
		$all = self::get_all();
		if ( ! isset( $all[ $ability_id ] ) ) {
			return array(
				'ok'    => false,
				'data'  => null,
				'error' => sprintf( 'Unknown ability: %s', $ability_id ),
			);
		}

		$ability = $all[ $ability_id ];
		if ( ! self::check_permission( $ability ) ) {
			return array(
				'ok'    => false,
				'data'  => null,
				'error' => 'You do not have permission to execute this ability.',
			);
		}

		if ( ! empty( $ability['execute_callback'] ) && is_callable( $ability['execute_callback'] ) ) {
			try {
				$result = call_user_func( $ability['execute_callback'], $arguments );
				if ( is_wp_error( $result ) ) {
					return array(
						'ok'    => false,
						'data'  => null,
						'error' => $result->get_error_message(),
					);
				}
				return array(
					'ok'    => true,
					'data'  => $result,
					'error' => null,
				);
			} catch ( Throwable $e ) {
				return array(
					'ok'    => false,
					'data'  => null,
					'error' => $e->getMessage(),
				);
			}
		}

		if ( ! empty( $ability['_wp_native'] ) && function_exists( 'wp_get_ability' ) ) {
			$wp_ab = wp_get_ability( $ability_id );
			if ( is_object( $wp_ab ) && method_exists( $wp_ab, 'execute' ) ) {
				$result = $wp_ab->execute( $arguments );
				if ( is_wp_error( $result ) ) {
					return array(
						'ok'    => false,
						'data'  => null,
						'error' => $result->get_error_message(),
					);
				}
				return array(
					'ok'    => true,
					'data'  => $result,
					'error' => null,
				);
			}
		}

		return array(
			'ok'    => false,
			'data'  => null,
			'error' => sprintf( 'Ability "%s" has no execute handler.', $ability_id ),
		);
	}
}
