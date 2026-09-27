<?php
/**
 * Universal API Gateway and Integration Broker for Xophz COMPASS.
 *
 * Provides a secure, centralized REST gateway and PHP bridge to external APIs (Explee, etc.)
 * with vaulted credentials, strict SSRF guards, financial circuit breakers, multi-key contact caching,
 * rolling telemetry audit logs, and standardized result envelopes.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Gateway {

	/**
	 * Register REST routes for the Gateway.
	 */
	public function register_routes() {
		$namespace = 'xophz/v1';

		// List configured providers
		register_rest_route( $namespace, '/gateway/providers', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_providers_api' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
		) );

		// Universal forwarder (subpath relay with SSRF check)
		register_rest_route( $namespace, '/gateway/(?P<provider>[a-zA-Z0-9_-]+)/forward', array(
			array(
				'methods'             => array( 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' ),
				'callback'            => array( $this, 'handle_forward' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
		) );

		// Curated semantic actions (with pre-flight caching & validation)
		register_rest_route( $namespace, '/gateway/(?P<provider>[a-zA-Z0-9_-]+)/(?P<action>[a-zA-Z0-9_-]+)', array(
			array(
				'methods'             => array( 'GET', 'POST' ),
				'callback'            => array( $this, 'handle_action' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
		) );

		// Telemetry audit log
		register_rest_route( $namespace, '/gateway/telemetry', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_telemetry_api' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
		) );
	}

	/**
	 * Permission check: Admin capability or valid Gateway secret header.
	 */
	public function check_permissions( WP_REST_Request $request ) {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		$gateway_key = $request->get_header( 'X-Compass-Gateway-Key' );
		$stored_key  = get_option( 'compass_gateway_token', '' );
		if ( ! empty( $stored_key ) && ! empty( $gateway_key ) && hash_equals( $stored_key, $gateway_key ) ) {
			return true;
		}

		return new WP_Error( 'gateway_forbidden', 'Administrative privileges or a valid gateway key are required.', array( 'status' => 403 ) );
	}

	/**
	 * Registry of supported providers.
	 */
	public static function get_provider_config( $provider_id ) {
		$configs = array(
			'explee' => array(
				'name'                => 'Explee B2B Outreach & Data',
				'base_url'            => 'https://api.explee.com',
				'auth_strategy'       => 'header',
				'auth_header'         => 'X-API-Key',
				'auth_option'         => 'compass_explee_api_key',
				'allowed_path_regex'  => '#^/public/api/v1/#',
				'timeout'             => 30,
				'minute_limit'        => 30,
				'daily_limit'         => 200,
			),
		);

		$configs = apply_filters( 'compass_gateway_providers', $configs );
		return isset( $configs[ $provider_id ] ) ? $configs[ $provider_id ] : null;
	}

	/**
	 * GET /xophz/v1/gateway/providers
	 */
	public function get_providers_api( WP_REST_Request $request ) {
		$providers = array();
		foreach ( array( 'explee' ) as $pid ) {
			$cfg = self::get_provider_config( $pid );
			if ( ! $cfg ) continue;
			$key = get_option( $cfg['auth_option'], '' );
			$providers[] = array(
				'id'         => $pid,
				'name'       => $cfg['name'],
				'configured' => ! empty( $key ),
				'base_url'   => $cfg['base_url'],
				'actions'    => array( 'balance', 'search-people', 'enrich-email', 'hot-leads', 'campaigns' ),
			);
		}

		return rest_ensure_response( array(
			'success'   => true,
			'providers' => $providers,
		) );
	}

	/**
	 * Universal forwarder endpoint.
	 */
	public function handle_forward( WP_REST_Request $request ) {
		$provider_id = sanitize_key( $request->get_param( 'provider' ) );
		$endpoint    = $request->get_param( 'endpoint' );
		$body        = $request->get_json_params() ?: array();
		$method      = ! empty( $body['method'] ) ? strtoupper( sanitize_text_field( $body['method'] ) ) : strtoupper( $request->get_method() );

		// Remove gateway parameters from body forward
		unset( $body['endpoint'], $body['provider'], $body['method'] );

		return rest_ensure_response( self::execute_request( $provider_id, $endpoint, $method, $body ) );
	}

	/**
	 * Curated actions endpoint.
	 */
	public function handle_action( WP_REST_Request $request ) {
		$provider_id = sanitize_key( $request->get_param( 'provider' ) );
		$action      = sanitize_key( $request->get_param( 'action' ) );
		$params      = $request->get_json_params() ?: array();
		$force       = ! empty( $request->get_param( 'force_refresh' ) ) || ! empty( $params['force_refresh'] );

		if ( 'explee' !== $provider_id ) {
			return rest_ensure_response( self::build_envelope( false, $provider_id, null, array(
				'code'        => 'unsupported_provider',
				'message'     => "Provider '{$provider_id}' is not supported.",
				'http_status' => 400,
			), 400, 0, false ) );
		}

		switch ( $action ) {
			case 'balance':
				return rest_ensure_response( self::execute_request( 'explee', '/public/api/v1/billing/balance', 'GET' ) );

			case 'campaigns':
				return rest_ensure_response( self::execute_request( 'explee', '/public/api/v1/autogtm/campaigns', 'GET' ) );

			case 'hot-leads':
				return rest_ensure_response( self::execute_request( 'explee', '/public/api/v1/autogtm/hot-leads', 'GET' ) );

			case 'search-people':
				return rest_ensure_response( self::execute_request( 'explee', '/public/api/v1/search/people', 'POST', $params ) );

			case 'enrich-email':
				// Pre-flight check: "Never Pay Twice"
				if ( ! $force ) {
					$cached = self::get_cached_contact( 'explee', $params );
					if ( $cached ) {
						return rest_ensure_response( self::build_envelope( true, 'explee', $cached, null, 200, 3, true ) );
					}
				}

				// Cache miss: Execute remote call
				$result = self::execute_request( 'explee', '/public/api/v1/enrich/email', 'POST', $params );
				if ( ! empty( $result['success'] ) && ! empty( $result['data'] ) ) {
					self::store_cached_contact( 'explee', $params, $result['data'] );
				}
				return rest_ensure_response( $result );

			default:
				return rest_ensure_response( self::build_envelope( false, $provider_id, null, array(
					'code'        => 'unknown_action',
					'message'     => "Action '{$action}' is not supported for provider '{$provider_id}'.",
					'http_status' => 404,
				), 404, 0, false ) );
		}
	}

	/**
	 * Core request execution pipeline.
	 */
	public static function execute_request( $provider_id, $endpoint, $method = 'GET', $body = null, $query_params = array() ) {
		$start_time = microtime( true );
		$cfg        = self::get_provider_config( $provider_id );

		if ( ! $cfg ) {
			return self::build_envelope( false, $provider_id, null, array(
				'code'        => 'unknown_provider',
				'message'     => "Unknown provider: '{$provider_id}'.",
				'http_status' => 400,
			), 400, 0, false );
		}

		// 1. Vault Credential Resolution
		$api_key = get_option( $cfg['auth_option'], '' );
		if ( empty( $api_key ) ) {
			return self::build_envelope( false, $provider_id, null, array(
				'code'        => 'missing_credentials',
				'message'     => "API key for '{$provider_id}' is not configured in Connectors.",
				'http_status' => 500,
			), 500, 0, false );
		}

		// 2. SSRF Guard & Path Sanitization
		if ( empty( $endpoint ) || strpos( $endpoint, '://' ) !== false || strpos( $endpoint, '..' ) !== false || strpos( $endpoint, '@' ) !== false ) {
			return self::build_envelope( false, $provider_id, null, array(
				'code'        => 'ssrf_rejection',
				'message'     => 'Invalid or malicious endpoint path.',
				'http_status' => 400,
			), 400, 0, false );
		}

		if ( ! preg_match( $cfg['allowed_path_regex'], $endpoint ) ) {
			return self::build_envelope( false, $provider_id, null, array(
				'code'        => 'path_not_allowed',
				'message'     => "Endpoint '{$endpoint}' is not permitted for provider '{$provider_id}'.",
				'http_status' => 403,
			), 403, 0, false );
		}

		// 3. Financial Circuit Breaker
		$circuit_error = self::check_circuit_breaker( $provider_id, $cfg );
		if ( is_wp_error( $circuit_error ) ) {
			return self::build_envelope( false, $provider_id, null, array(
				'code'        => $circuit_error->get_error_code(),
				'message'     => $circuit_error->get_error_message(),
				'http_status' => 429,
			), 429, 0, false );
		}

		// 4. Construct URL and Headers
		$url = rtrim( $cfg['base_url'], '/' ) . '/' . ltrim( $endpoint, '/' );
		if ( ! empty( $query_params ) ) {
			$url = add_query_arg( $query_params, $url );
		}

		$headers = array(
			'Content-Type' => 'application/json',
			'Accept'       => 'application/json',
			'User-Agent'   => 'Project-Compass-Gateway/1.0',
		);

		if ( 'header' === $cfg['auth_strategy'] ) {
			$headers[ $cfg['auth_header'] ] = $api_key;
		}

		$args = array(
			'method'      => $method,
			'headers'     => $headers,
			'timeout'     => isset( $cfg['timeout'] ) ? $cfg['timeout'] : 30,
			'sslverify'   => true,
		);

		if ( in_array( $method, array( 'POST', 'PUT', 'PATCH' ), true ) && ! empty( $body ) ) {
			$args['body'] = wp_json_encode( $body );
		}

		// 5. Dispatch Remote Request
		$response   = wp_remote_request( $url, $args );
		$latency_ms = round( ( microtime( true ) - $start_time ) * 1000 );

		if ( is_wp_error( $response ) ) {
			self::log_telemetry( $provider_id, $endpoint, $method, 504, $latency_ms, false, $response->get_error_message() );
			return self::build_envelope( false, $provider_id, null, array(
				'code'        => 'upstream_network_error',
				'message'     => $response->get_error_message(),
				'http_status' => 504,
			), 504, $latency_ms, false );
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		$raw_body    = wp_remote_retrieve_body( $response );
		$json        = json_decode( $raw_body, true );
		$payload     = ( null !== $json ) ? $json : array( 'raw' => $raw_body );

		self::log_telemetry( $provider_id, $endpoint, $method, $status_code, $latency_ms, false );

		// 6. Handle Upstream Errors (e.g. 402 Insufficient Balance, 422 Validation)
		if ( $status_code >= 400 ) {
			$error_msg = isset( $payload['detail'] ) ? $payload['detail'] : "Upstream error (HTTP {$status_code})";
			if ( is_array( $error_msg ) ) {
				$error_msg = wp_json_encode( $error_msg );
			}
			return self::build_envelope( false, $provider_id, null, array(
				'code'              => ( 402 === $status_code ) ? 'insufficient_balance' : 'upstream_error',
				'message'           => $error_msg,
				'http_status'       => $status_code,
				'upstream_response' => $payload,
			), $status_code, $latency_ms, false );
		}

		return self::build_envelope( true, $provider_id, $payload, null, $status_code, $latency_ms, false );
	}

	/**
	 * Rate limiting & daily spend circuit breaker.
	 */
	private static function check_circuit_breaker( $provider_id, $cfg ) {
		$user_id     = get_current_user_id() ?: 'anon';
		$minute_key  = "compass_gw_min_{$provider_id}_{$user_id}";
		$daily_key   = "compass_gw_day_{$provider_id}";

		$minute_count = (int) get_transient( $minute_key );
		if ( $minute_count >= $cfg['minute_limit'] ) {
			return new WP_Error( 'rate_limit_exceeded', 'Per-minute gateway rate limit exceeded. Please wait a moment.' );
		}

		$daily_count = (int) get_transient( $daily_key );
		if ( $daily_count >= $cfg['daily_limit'] ) {
			return new WP_Error( 'daily_limit_exceeded', 'Daily gateway request budget cap reached for this provider.' );
		}

		set_transient( $minute_key, $minute_count + 1, MINUTE_IN_SECONDS );
		set_transient( $daily_key, $daily_count + 1, DAY_IN_SECONDS );

		return true;
	}

	/**
	 * Multi-index contact caching lookup ("Never Pay Twice").
	 */
	public static function get_cached_contact( $provider_id, $params ) {
		$hashes = self::generate_contact_hashes( $params );
		foreach ( $hashes as $hash ) {
			$cached = get_option( "compass_gw_c_{$hash}", false );
			if ( ! empty( $cached ) && is_array( $cached ) ) {
				return $cached;
			}
		}
		return null;
	}

	/**
	 * Store enriched contact across all lookup hashes.
	 */
	public static function store_cached_contact( $provider_id, $params, $contact_data ) {
		$hashes = self::generate_contact_hashes( $params );
		if ( ! empty( $contact_data['email'] ) ) {
			$hashes[] = md5( strtolower( trim( $contact_data['email'] ) ) );
		}
		$hashes = array_unique( $hashes );

		foreach ( $hashes as $hash ) {
			update_option( "compass_gw_c_{$hash}", $contact_data, false ); // autoload = false
		}
	}

	/**
	 * Generate multi-index hash keys from params.
	 */
	private static function generate_contact_hashes( $params ) {
		$hashes = array();
		if ( ! empty( $params['email'] ) ) {
			$hashes[] = md5( strtolower( trim( $params['email'] ) ) );
		}
		if ( ! empty( $params['first_name'] ) && ! empty( $params['last_name'] ) && ( ! empty( $params['domain'] ) || ! empty( $params['company_domain'] ) ) ) {
			$dom = ! empty( $params['domain'] ) ? $params['domain'] : $params['company_domain'];
			$hashes[] = md5( strtolower( trim( "{$params['first_name']}_{$params['last_name']}_{$dom}" ) ) );
		}
		if ( ! empty( $params['linkedin_url'] ) ) {
			$hashes[] = md5( strtolower( trim( preg_replace( '#^https?://(www\.)?linkedin\.com/in/#', '', $params['linkedin_url'] ) ) ) );
		}
		return $hashes;
	}

	/**
	 * Telemetry audit logger (stores rolling buffer of 200 events).
	 */
	private static function log_telemetry( $provider_id, $endpoint, $method, $status_code, $latency_ms, $cached, $error = null ) {
		$log = get_option( 'compass_gateway_telemetry', array() );
		if ( ! is_array( $log ) ) {
			$log = array();
		}

		array_unshift( $log, array(
			'timestamp'   => gmdate( 'c' ),
			'provider'    => $provider_id,
			'endpoint'    => $endpoint,
			'method'      => $method,
			'status'      => $status_code,
			'latency_ms'  => $latency_ms,
			'cached'      => (bool) $cached,
			'user_id'     => get_current_user_id() ?: 0,
			'error'       => $error,
		) );

		if ( count( $log ) > 200 ) {
			$log = array_slice( $log, 0, 200 );
		}

		update_option( 'compass_gateway_telemetry', $log, false );
	}

	/**
	 * GET /xophz/v1/gateway/telemetry
	 */
	public function get_telemetry_api( WP_REST_Request $request ) {
		$log = get_option( 'compass_gateway_telemetry', array() );
		return rest_ensure_response( array(
			'success'   => true,
			'telemetry' => is_array( $log ) ? $log : array(),
		) );
	}

	/**
	 * Standardized Result Envelope Formatter.
	 */
	public static function build_envelope( $success, $provider_id, $data, $error, $status_code, $latency_ms, $cached ) {
		return array(
			'success'  => (bool) $success,
			'provider' => $provider_id,
			'data'     => $data,
			'error'    => $error,
			'meta'     => array(
				'status'     => (int) $status_code,
				'latency_ms' => (int) $latency_ms,
				'cached'     => (bool) $cached,
				'timestamp'  => gmdate( 'c' ),
			),
		);
	}

	/**
	 * Public static PHP bridge for direct execution from other plugins (fresh-mints, lead-magnet).
	 */
	public static function call( $provider_id, $endpoint, $method = 'GET', $body = null, $query_params = array() ) {
		return self::execute_request( $provider_id, $endpoint, $method, $body, $query_params );
	}

}
