<?php
/**
 * Provider-Agnostic AI Chat Adapter (WP Connectors API)
 *
 * Discovers any connected AI chat provider from the WordPress Connectors API
 * (`wp_get_connectors`) and Compass connector definitions, respects
 * `compass_ai_model_routing`, and normalizes tool-calling chat turns across
 * Google Gemini, Anthropic Claude, OpenAI, OpenRouter, and Ollama.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_AI_Providers {

	/**
	 * Default model IDs per provider when no explicit model option is configured.
	 */
	private const DEFAULT_MODELS = array(
		'google'     => 'gemini-2.5-flash',
		'openai'     => 'gpt-4o-mini',
		'anthropic'  => 'claude-sonnet-4-20250514',
		'openrouter' => 'openai/gpt-4o-mini',
		'ollama'     => 'llama3.1',
	);

	private const PROVIDER_MODELS = array(
		'google'     => array( 'gemini-2.5-flash', 'gemini-2.5-pro', 'gemini-2.0-flash' ),
		'openai'     => array( 'gpt-4o-mini', 'gpt-4o', 'o3-mini' ),
		'anthropic'  => array( 'claude-sonnet-4-20250514', 'claude-3-5-haiku-latest' ),
		'openrouter' => array( 'openai/gpt-4o-mini', 'anthropic/claude-sonnet-4', 'google/gemini-2.5-flash' ),
		'ollama'     => array( 'llama3.1', 'qwen2.5', 'mistral' ),
	);

	/**
	 * Canonicalize provider alias IDs (e.g. 'gemini' -> 'google').
	 */
	public static function normalize_provider_id( string $raw_id ): string {
		$id = strtolower( trim( $raw_id ) );
		if ( 'gemini' === $id || 'google_gemini_api_key' === $id ) {
			return 'google';
		}
		if ( 'claude' === $id ) {
			return 'anthropic';
		}
		return $id;
	}

	/**
	 * Discover all connected AI chat providers available via WP Connectors API
	 * and Compass connector definitions.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function discover_connected(): array {
		$found   = array();
		$routing = get_option( 'compass_ai_model_routing', array() );
		$default = is_array( $routing ) && ! empty( $routing['general'] )
			? self::normalize_provider_id( (string) $routing['general'] )
			: '';

		// 1. Native WordPress Connectors API (`wp_get_connectors`).
		if ( function_exists( 'wp_get_connectors' ) ) {
			$wp_connectors = wp_get_connectors();
			if ( is_array( $wp_connectors ) ) {
				foreach ( $wp_connectors as $cid => $cdata ) {
					$arr  = is_object( $cdata ) ? (array) $cdata : ( is_array( $cdata ) ? $cdata : array() );
					$type = strtolower( (string) ( $arr['type'] ?? '' ) );
					$norm = self::normalize_provider_id( (string) $cid );

					$is_ai = ( 'ai' === $type || 'ai_provider' === $type )
						|| in_array( $norm, array( 'google', 'openai', 'anthropic', 'openrouter', 'ollama' ), true );
					if ( ! $is_ai ) {
						continue;
					}

					$key = class_exists( 'Xophz_Compass_Connector_Key' )
						? Xophz_Compass_Connector_Key::get( (string) $cid )
						: '';

					if ( '' === $key ) {
						continue;
					}

					$model = self::resolve_model_for_connector( $norm, $arr );
					$name  = (string) ( $arr['name'] ?? $arr['label'] ?? ucfirst( $norm ) );
					$found[ $norm ] = array(
						'id'           => $norm,
						'connector_id' => (string) $cid,
						'name'         => $name,
						'model'        => $model,
						'protocol'     => self::protocol_for_provider( $norm ),
						'base_url'     => (string) ( $arr['base_url'] ?? '' ),
						'api_key'      => $key,
						'is_default'   => false,
					);
				}
			}
		}

		// 2. Fallback / supplement from Compass connector definitions & options.
		$fallbacks = array(
			'google'     => array( 'name' => 'Google Gemini', 'option' => 'connectors_ai_google_api_key', 'legacy' => 'xophz_compass_gemini_api_key' ),
			'openai'     => array( 'name' => 'OpenAI', 'option' => 'connectors_ai_openai_api_key', 'legacy' => '' ),
			'anthropic'  => array( 'name' => 'Anthropic Claude', 'option' => 'connectors_ai_anthropic_api_key', 'legacy' => '' ),
			'openrouter' => array( 'name' => 'OpenRouter', 'option' => 'compass_openrouter_api_key', 'legacy' => '' ),
			'ollama'     => array( 'name' => 'Local Ollama', 'option' => 'compass_ollama_url', 'legacy' => '' ),
		);

		foreach ( $fallbacks as $pid => $meta ) {
			if ( isset( $found[ $pid ] ) ) {
				continue;
			}

			$val = (string) get_option( $meta['option'], '' );
			if ( '' === $val && '' !== $meta['legacy'] ) {
				$val = (string) get_option( $meta['legacy'], '' );
			}
			if ( '' === $val && 'google' === $pid && class_exists( 'Xophz_Compass_HTTP' ) && method_exists( 'Xophz_Compass_HTTP', 'get_gemini_api_key' ) ) {
				$val = (string) Xophz_Compass_HTTP::get_gemini_api_key();
			}

			if ( '' === $val ) {
				continue;
			}

			$found[ $pid ] = array(
				'id'           => $pid,
				'connector_id' => $pid,
				'name'         => $meta['name'],
				'model'        => self::DEFAULT_MODELS[ $pid ],
				'protocol'     => self::protocol_for_provider( $pid ),
				'base_url'     => 'ollama' === $pid ? rtrim( $val, '/' ) : '',
				'api_key'      => 'ollama' === $pid ? 'ollama' : $val,
				'is_default'   => false,
			);
		}

		// Mark default provider using modelRouting['general'] or first available.
		if ( '' !== $default && isset( $found[ $default ] ) ) {
			$found[ $default ]['is_default'] = true;
		} elseif ( ! empty( $found ) ) {
			$first_key                        = array_key_first( $found );
			$found[ $first_key ]['is_default'] = true;
		}

		return array_values( $found );
	}

	/**
	 * Public-safe list of connected providers (strips api_key before sending to frontend).
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function get_public_providers(): array {
		$out = array();
		foreach ( self::discover_connected() as $p ) {
			$pid    = (string) $p['id'];
			$models = self::PROVIDER_MODELS[ $pid ] ?? array( (string) $p['model'] );
			if ( ! in_array( $p['model'], $models, true ) ) {
				array_unshift( $models, (string) $p['model'] );
			}
			$out[] = array(
				'id'         => $pid,
				'name'       => $p['name'],
				'model'      => $p['model'],
				'models'     => $models,
				'is_default' => (bool) $p['is_default'],
			);
		}
		return $out;
	}

	/**
	 * Resolve the active provider config (requested override -> default -> first connected).
	 *
	 * @param string|null $requested_id
	 * @return array<string, mixed>|null
	 */
	public static function resolve_provider( ?string $requested_id = null ): ?array {
		$providers = self::discover_connected();
		if ( empty( $providers ) ) {
			return null;
		}

		if ( null !== $requested_id && '' !== $requested_id ) {
			$target = self::normalize_provider_id( $requested_id );
			foreach ( $providers as $p ) {
				if ( $p['id'] === $target ) {
					return $p;
				}
			}
		}

		foreach ( $providers as $p ) {
			if ( ! empty( $p['is_default'] ) ) {
				return $p;
			}
		}

		return $providers[0];
	}

	/**
	 * Execute a single normalized chat turn with optional tool declarations.
	 *
	 * @param array<string, mixed>              $provider
	 * @param string                            $system_prompt
	 * @param array<int, array<string, mixed>>  $messages
	 * @param array<int, array<string, mixed>>  $tools
	 * @return array{text: string, tool_calls: array<int, array<string, mixed>>, provider: string, model: string}|WP_Error
	 */
	public static function chat_turn( array $provider, string $system_prompt, array $messages, array $tools, string $effort = 'fast' ) {
		$protocol = (string) ( $provider['protocol'] ?? 'openai' );

		if ( 'gemini' === $protocol ) {
			return self::call_gemini( $provider, $system_prompt, $messages, $tools, $effort );
		}
		if ( 'anthropic' === $protocol ) {
			return self::call_anthropic( $provider, $system_prompt, $messages, $tools, $effort );
		}

		return self::call_openai_compatible( $provider, $system_prompt, $messages, $tools, $effort );
	}

	private static function protocol_for_provider( string $provider_id ): string {
		if ( 'google' === $provider_id ) {
			return 'gemini';
		}
		if ( 'anthropic' === $provider_id ) {
			return 'anthropic';
		}
		return 'openai';
	}

	private static function resolve_model_for_connector( string $provider_id, array $connector ): string {
		if ( ! empty( $connector['options']['model']['setting_name'] ) ) {
			$opt = (string) get_option( (string) $connector['options']['model']['setting_name'], '' );
			if ( '' !== $opt ) {
				return $opt;
			}
		}
		return self::DEFAULT_MODELS[ $provider_id ] ?? 'gpt-4o-mini';
	}

	/**
	 * OpenAI / OpenRouter / Ollama chat completions adapter.
	 */
	private static function call_openai_compatible( array $provider, string $system_prompt, array $messages, array $tools, string $effort = 'fast' ) {
		$pid   = (string) $provider['id'];
		$model = (string) $provider['model'];
		$key   = (string) $provider['api_key'];

		if ( 'openrouter' === $pid ) {
			$url = 'https://openrouter.ai/api/v1/chat/completions';
		} elseif ( 'ollama' === $pid ) {
			$base = rtrim( (string) ( $provider['base_url'] ?: 'http://ollama:11434' ), '/' );
			$url  = $base . '/v1/chat/completions';
		} elseif ( ! empty( $provider['base_url'] ) ) {
			$url = rtrim( (string) $provider['base_url'], '/' ) . '/chat/completions';
		} else {
			$url = 'https://api.openai.com/v1/chat/completions';
		}

		$wire_messages = array(
			array(
				'role'    => 'system',
				'content' => $system_prompt,
			),
		);

		foreach ( $messages as $msg ) {
			$role = (string) ( $msg['role'] ?? 'user' );
			if ( 'tool' === $role ) {
				$wire_messages[] = array(
					'role'         => 'tool',
					'tool_call_id' => (string) ( $msg['tool_call_id'] ?? '' ),
					'content'      => wp_json_encode( $msg['result'] ?? '' ),
				);
			} elseif ( 'assistant' === $role && ! empty( $msg['tool_calls'] ) ) {
				$formatted_calls = array();
				foreach ( $msg['tool_calls'] as $tc ) {
					$formatted_calls[] = array(
						'id'       => (string) $tc['id'],
						'type'     => 'function',
						'function' => array(
							'name'      => (string) $tc['name'],
							'arguments' => wp_json_encode( $tc['arguments'] ?? new stdClass() ),
						),
					);
				}
				$wire_messages[] = array(
					'role'       => 'assistant',
					'content'    => (string) ( $msg['content'] ?? '' ),
					'tool_calls' => $formatted_calls,
				);
			} else {
				$wire_messages[] = array(
					'role'    => in_array( $role, array( 'user', 'assistant' ), true ) ? $role : 'user',
					'content' => (string) ( $msg['content'] ?? '' ),
				);
			}
		}

		$body = array(
			'model'    => $model,
			'messages' => $wire_messages,
		);

		if ( ! empty( $tools ) ) {
			$body['tools'] = array_map(
				static function ( array $tool ): array {
					return array(
						'type'     => 'function',
						'function' => array(
							'name'        => $tool['tool_name'],
							'description' => $tool['description'],
							'parameters'  => $tool['input_schema'],
						),
					);
				},
				array_values( $tools )
			);
		}

		$headers = array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $key,
		);
		if ( 'openrouter' === $pid ) {
			$headers['HTTP-Referer'] = home_url( '/' );
			$headers['X-Title']      = get_bloginfo( 'name' ) . ' Compass';
		}

		$response = wp_remote_post(
			$url,
			array(
				'headers' => $headers,
				'body'    => wp_json_encode( $body ),
				'timeout' => 45,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$data = json_decode( (string) wp_remote_retrieve_body( $response ), true );

		if ( $code < 200 || $code >= 300 || ! is_array( $data ) ) {
			$err_msg = isset( $data['error']['message'] ) ? (string) $data['error']['message'] : sprintf( 'AI provider HTTP %d error.', $code );
			return new WP_Error( 'aurora_provider_error', $err_msg, array( 'status' => 502 ) );
		}

		$choice     = $data['choices'][0]['message'] ?? array();
		$text       = (string) ( $choice['content'] ?? '' );
		$tool_calls = array();

		if ( ! empty( $choice['tool_calls'] ) && is_array( $choice['tool_calls'] ) ) {
			foreach ( $choice['tool_calls'] as $tc ) {
				$raw_args = isset( $tc['function']['arguments'] ) ? (string) $tc['function']['arguments'] : '{}';
				$decoded  = json_decode( $raw_args, true );
				$tool_calls[] = array(
					'id'        => (string) ( $tc['id'] ?? wp_generate_uuid4() ),
					'name'      => (string) ( $tc['function']['name'] ?? '' ),
					'arguments' => is_array( $decoded ) ? $decoded : array(),
				);
			}
		}

		$usage_raw = is_array( $data['usage'] ?? null ) ? $data['usage'] : array();
		$prompt_t  = (int) ( $usage_raw['prompt_tokens'] ?? 0 );
		$comp_t    = (int) ( $usage_raw['completion_tokens'] ?? 0 );

		return array(
			'text'       => $text,
			'tool_calls' => $tool_calls,
			'provider'   => $pid,
			'model'      => $model,
			'usage'      => array(
				'prompt_tokens'     => $prompt_t,
				'completion_tokens' => $comp_t,
				'total_tokens'      => (int) ( $usage_raw['total_tokens'] ?? ( $prompt_t + $comp_t ) ),
			),
		);
	}

	/**
	 * Anthropic Claude `/v1/messages` adapter.
	 */
	private static function call_anthropic( array $provider, string $system_prompt, array $messages, array $tools, string $effort = 'fast' ) {
		$model         = (string) $provider['model'];
		$key           = (string) $provider['api_key'];
		$wire_messages = array();

		foreach ( $messages as $msg ) {
			$role = (string) ( $msg['role'] ?? 'user' );
			if ( 'tool' === $role ) {
				$wire_messages[] = array(
					'role'    => 'user',
					'content' => array(
						array(
							'type'        => 'tool_result',
							'tool_use_id' => (string) ( $msg['tool_call_id'] ?? '' ),
							'content'     => wp_json_encode( $msg['result'] ?? '' ),
						),
					),
				);
			} elseif ( 'assistant' === $role && ! empty( $msg['tool_calls'] ) ) {
				$blocks = array();
				if ( ! empty( $msg['content'] ) ) {
					$blocks[] = array( 'type' => 'text', 'text' => (string) $msg['content'] );
				}
				foreach ( $msg['tool_calls'] as $tc ) {
					$blocks[] = array(
						'type'  => 'tool_use',
						'id'    => (string) $tc['id'],
						'name'  => (string) $tc['name'],
						'input' => ! empty( $tc['arguments'] ) ? $tc['arguments'] : new stdClass(),
					);
				}
				$wire_messages[] = array( 'role' => 'assistant', 'content' => $blocks );
			} else {
				$wire_messages[] = array(
					'role'    => 'assistant' === $role ? 'assistant' : 'user',
					'content' => (string) ( $msg['content'] ?? '' ),
				);
			}
		}

		$body = array(
			'model'      => $model,
			'max_tokens' => 2048,
			'system'     => $system_prompt,
			'messages'   => $wire_messages,
		);

		if ( ! empty( $tools ) ) {
			$body['tools'] = array_map(
				static function ( array $tool ): array {
					return array(
						'name'         => $tool['tool_name'],
						'description'  => $tool['description'],
						'input_schema' => $tool['input_schema'],
					);
				},
				array_values( $tools )
			);
		}

		$response = wp_remote_post(
			'https://api.anthropic.com/v1/messages',
			array(
				'headers' => array(
					'Content-Type'      => 'application/json',
					'x-api-key'         => $key,
					'anthropic-version' => '2023-06-01',
				),
				'body'    => wp_json_encode( $body ),
				'timeout' => 45,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$data = json_decode( (string) wp_remote_retrieve_body( $response ), true );

		if ( $code < 200 || $code >= 300 || ! is_array( $data ) ) {
			$err_msg = isset( $data['error']['message'] ) ? (string) $data['error']['message'] : sprintf( 'Anthropic HTTP %d error.', $code );
			return new WP_Error( 'aurora_provider_error', $err_msg, array( 'status' => 502 ) );
		}

		$text_parts = array();
		$tool_calls = array();

		foreach ( (array) ( $data['content'] ?? array() ) as $block ) {
			if ( ! is_array( $block ) ) {
				continue;
			}
			if ( 'text' === ( $block['type'] ?? '' ) ) {
				$text_parts[] = (string) ( $block['text'] ?? '' );
			} elseif ( 'tool_use' === ( $block['type'] ?? '' ) ) {
				$tool_calls[] = array(
					'id'        => (string) ( $block['id'] ?? wp_generate_uuid4() ),
					'name'      => (string) ( $block['name'] ?? '' ),
					'arguments' => isset( $block['input'] ) && is_array( $block['input'] ) ? $block['input'] : array(),
				);
			}
		}

		$usage_raw = is_array( $data['usage'] ?? null ) ? $data['usage'] : array();
		$prompt_t  = (int) ( $usage_raw['input_tokens'] ?? 0 );
		$comp_t    = (int) ( $usage_raw['output_tokens'] ?? 0 );

		return array(
			'text'       => implode( "\n", $text_parts ),
			'tool_calls' => $tool_calls,
			'provider'   => 'anthropic',
			'model'      => $model,
			'usage'      => array(
				'prompt_tokens'     => $prompt_t,
				'completion_tokens' => $comp_t,
				'total_tokens'      => $prompt_t + $comp_t,
			),
		);
	}

	/**
	 * Google Gemini `generateContent` adapter.
	 */
	private static function call_gemini( array $provider, string $system_prompt, array $messages, array $tools, string $effort = 'fast' ) {
		$model = preg_replace( '/[^A-Za-z0-9._-]/', '', (string) $provider['model'] );
		if ( '' === $model ) {
			$model = self::DEFAULT_MODELS['google'];
		}
		$key = (string) $provider['api_key'];
		$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent';

		$contents = array();
		foreach ( $messages as $msg ) {
			$role = (string) ( $msg['role'] ?? 'user' );
			if ( 'tool' === $role ) {
				$fn_part = array(
					'functionResponse' => array(
						'name'     => (string) ( $msg['tool_name'] ?? 'tool' ),
						'response' => array( 'result' => $msg['result'] ?? null ),
					),
				);
				$last_idx = count( $contents ) - 1;
				if ( $last_idx >= 0 && 'user' === ( $contents[ $last_idx ]['role'] ?? '' ) && ! empty( $contents[ $last_idx ]['parts'][0]['functionResponse'] ) ) {
					$contents[ $last_idx ]['parts'][] = $fn_part;
				} else {
					$contents[] = array(
						'role'  => 'user',
						'parts' => array( $fn_part ),
					);
				}
			} elseif ( 'assistant' === $role && ! empty( $msg['tool_calls'] ) ) {
				$parts = array();
				if ( ! empty( $msg['content'] ) ) {
					$parts[] = array( 'text' => (string) $msg['content'] );
				}
				foreach ( $msg['tool_calls'] as $tc ) {
					$fc_part = array(
						'functionCall' => array(
							'name' => (string) $tc['name'],
							'args' => ! empty( $tc['arguments'] ) ? $tc['arguments'] : new stdClass(),
						),
					);
					if ( ! empty( $tc['thought_signature'] ) ) {
						$fc_part['thoughtSignature'] = (string) $tc['thought_signature'];
					}
					$parts[] = $fc_part;
				}
				$contents[] = array( 'role' => 'model', 'parts' => $parts );
			} else {
				$contents[] = array(
					'role'  => 'assistant' === $role ? 'model' : 'user',
					'parts' => array( array( 'text' => (string) ( $msg['content'] ?? '' ) ) ),
				);
			}
		}

		$body = array(
			'systemInstruction' => array(
				'parts' => array( array( 'text' => $system_prompt ) ),
			),
			'contents'          => $contents,
			'generationConfig'  => array(
				'temperature' => 0.4,
			),
		);

		if ( false !== stripos( $model, 'gemini-2.5' ) ) {
			$budget = 'deep' === $effort ? 8192 : ( 'balanced' === $effort ? 2048 : 0 );
			$body['generationConfig']['thinkingConfig'] = array(
				'thinkingBudget' => $budget,
			);
		}

		if ( ! empty( $tools ) ) {
			$declarations = array();
			foreach ( $tools as $tool ) {
				$decl = array(
					'name'        => $tool['tool_name'],
					'description' => (string) ( $tool['description'] ?? $tool['name'] ?? '' ),
				);
				$params = self::sanitize_gemini_parameters( $tool['input_schema'] ?? array() );
				if ( null !== $params ) {
					$decl['parameters'] = $params;
				}
				$declarations[] = $decl;
			}
			$body['tools'] = array(
				array( 'functionDeclarations' => $declarations ),
			);
		}

		$response = wp_remote_post(
			$url,
			array(
				'headers' => array(
					'Content-Type'   => 'application/json',
					'x-goog-api-key' => $key,
				),
				'body'    => wp_json_encode( $body ),
				'timeout' => 45,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$data = json_decode( (string) wp_remote_retrieve_body( $response ), true );

		if ( $code < 200 || $code >= 300 || ! is_array( $data ) ) {
			$err_msg = isset( $data['error']['message'] ) ? (string) $data['error']['message'] : sprintf( 'Gemini HTTP %d error.', $code );
			return new WP_Error( 'aurora_provider_error', $err_msg, array( 'status' => 502 ) );
		}

		$parts      = $data['candidates'][0]['content']['parts'] ?? array();
		$text_parts = array();
		$tool_calls = array();

		foreach ( (array) $parts as $part ) {
			if ( ! is_array( $part ) ) {
				continue;
			}
			if ( ! empty( $part['thought'] ) ) {
				continue;
			}
			if ( isset( $part['text'] ) ) {
				$text_parts[] = (string) $part['text'];
			}
			if ( isset( $part['functionCall'] ) && is_array( $part['functionCall'] ) ) {
				$fc           = $part['functionCall'];
				$call_entry   = array(
					'id'        => wp_generate_uuid4(),
					'name'      => (string) ( $fc['name'] ?? '' ),
					'arguments' => isset( $fc['args'] ) && is_array( $fc['args'] ) ? $fc['args'] : array(),
				);
				if ( ! empty( $part['thoughtSignature'] ) ) {
					$call_entry['thought_signature'] = (string) $part['thoughtSignature'];
				}
				$tool_calls[] = $call_entry;
			}
		}

		$text = implode( "\n", $text_parts );
		if ( '' === trim( $text ) && empty( $tool_calls ) ) {
			$finish = (string) ( $data['candidates'][0]['finishReason'] ?? $data['promptFeedback']['blockReason'] ?? 'EMPTY_RESPONSE' );
			return new WP_Error(
				'aurora_empty_gemini_reply',
				sprintf( 'Gemini returned an empty response (%s).', $finish ),
				array( 'status' => 502 )
			);
		}

		$usage_raw = is_array( $data['usageMetadata'] ?? null ) ? $data['usageMetadata'] : array();
		$prompt_t  = (int) ( $usage_raw['promptTokenCount'] ?? 0 );
		$comp_t    = (int) ( $usage_raw['candidatesTokenCount'] ?? 0 );

		return array(
			'text'       => $text,
			'tool_calls' => $tool_calls,
			'provider'   => 'google',
			'model'      => $model,
			'usage'      => array(
				'prompt_tokens'     => $prompt_t,
				'completion_tokens' => $comp_t,
				'total_tokens'      => (int) ( $usage_raw['totalTokenCount'] ?? ( $prompt_t + $comp_t ) ),
			),
		);
	}

	/**
	 * Convert arbitrary JSON Schema (including native WP Abilities schemas) into
	 * the strict OpenAPI Schema subset accepted by Google Gemini functionDeclarations.
	 *
	 * @param mixed $schema
	 * @return array<string, mixed>|null Null when the tool takes no parameters.
	 */
	private static function sanitize_gemini_parameters( $schema ): ?array {
		if ( is_object( $schema ) ) {
			$schema = (array) $schema;
		}
		if ( ! is_array( $schema ) || empty( $schema ) ) {
			return null;
		}

		$raw_props = $schema['properties'] ?? null;
		if ( is_object( $raw_props ) ) {
			$raw_props = (array) $raw_props;
		}
		if ( ! is_array( $raw_props ) || empty( $raw_props ) ) {
			return null;
		}

		$clean_props = array();
		foreach ( $raw_props as $prop_name => $prop_schema ) {
			if ( ! is_string( $prop_name ) || '' === $prop_name ) {
				continue;
			}
			$clean_props[ $prop_name ] = self::sanitize_gemini_schema_node( $prop_schema );
		}

		if ( empty( $clean_props ) ) {
			return null;
		}

		$out = array(
			'type'       => 'object',
			'properties' => $clean_props,
		);

		if ( ! empty( $schema['required'] ) && is_array( $schema['required'] ) ) {
			$valid_required = array_values(
				array_filter(
					$schema['required'],
					static function ( $field ) use ( $clean_props ): bool {
						return is_string( $field ) && isset( $clean_props[ $field ] );
					}
				)
			);
			if ( ! empty( $valid_required ) ) {
				$out['required'] = $valid_required;
			}
		}

		return $out;
	}

	/**
	 * Recursively strip unsupported JSON Schema keywords for Gemini.
	 *
	 * @param mixed $node
	 * @return array<string, mixed>
	 */
	private static function sanitize_gemini_schema_node( $node ): array {
		if ( is_object( $node ) ) {
			$node = (array) $node;
		}
		if ( ! is_array( $node ) ) {
			return array( 'type' => 'string' );
		}

		$raw_type = $node['type'] ?? 'string';
		$nullable = ! empty( $node['nullable'] );

		if ( is_array( $raw_type ) ) {
			$filtered = array();
			foreach ( $raw_type as $t ) {
				if ( 'null' === $t ) {
					$nullable = true;
				} elseif ( is_string( $t ) ) {
					$filtered[] = $t;
				}
			}
			$raw_type = $filtered[0] ?? 'string';
		}

		$allowed_types = array( 'string', 'number', 'integer', 'boolean', 'array', 'object' );
		$type          = is_string( $raw_type ) && in_array( strtolower( $raw_type ), $allowed_types, true )
			? strtolower( $raw_type )
			: 'string';

		$clean = array( 'type' => $type );

		if ( ! empty( $node['description'] ) && is_string( $node['description'] ) ) {
			$clean['description'] = $node['description'];
		}

		if ( $nullable ) {
			$clean['nullable'] = true;
		}

		if ( 'string' === $type && ! empty( $node['enum'] ) && is_array( $node['enum'] ) ) {
			$enum_vals = array_values(
				array_map(
					'strval',
					array_filter( $node['enum'], 'is_scalar' )
				)
			);
			if ( ! empty( $enum_vals ) ) {
				$clean['enum'] = $enum_vals;
			}
		}

		if ( 'array' === $type ) {
			$clean['items'] = isset( $node['items'] )
				? self::sanitize_gemini_schema_node( $node['items'] )
				: array( 'type' => 'string' );
		}

		if ( 'object' === $type ) {
			$sub_props = ! empty( $node['properties'] )
				? ( is_object( $node['properties'] ) ? (array) $node['properties'] : $node['properties'] )
				: array();
			$clean_sub = array();
			if ( is_array( $sub_props ) ) {
				foreach ( $sub_props as $k => $v ) {
					if ( is_string( $k ) && '' !== $k ) {
						$clean_sub[ $k ] = self::sanitize_gemini_schema_node( $v );
					}
				}
			}
			if ( ! empty( $clean_sub ) ) {
				$clean['properties'] = $clean_sub;
			} else {
				// Gemini rejects empty object schemas in functionDeclarations; fall back to JSON string.
				$clean['type'] = 'string';
			}
		}

		return $clean;
	}
}
