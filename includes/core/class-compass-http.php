<?php
/**
 * Unified HTTP client for Compass plugins: resilient remote calls, plus the Gemini key lookup.
 * Every credential read delegates to Xophz_Compass_Connector_Key, the one resolver.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_HTTP {

	/**
	 * The Gemini API key: the core `google` AI connector first (GOOGLE_API_KEY, then its option),
	 * then the COMPASS `google_gemini_api_key` connector, which declares GEMINI_API_KEY and shares
	 * the same option. Retired option names are moved into that option by
	 * Xophz_Compass_Credential_Migration and are not read here.
	 *
	 * @return string|null Null when no connector holds a key.
	 */
	public static function get_gemini_api_key(): ?string {
		foreach ( array( 'google', 'google_gemini_api_key' ) as $connector_id ) {
			$key = Xophz_Compass_Connector_Key::get( $connector_id );
			if ( '' !== $key ) {
				return $key;
			}
		}

		return null;
	}

	/**
	 * A connector's credential, resolved through the names the connector declares.
	 *
	 * @param string             $connector_id     WP Connector ID.
	 * @param array<int, string> $fallback_options Ignored. Kept so existing callers do not break;
	 *                                             declare names on the connector instead.
	 * @param string             $env_var_name     Ignored, for the same reason.
	 * @return string|null Null when the connector is unregistered or holds no value.
	 */
	public static function resolve_api_key( string $connector_id, array $fallback_options = array(), string $env_var_name = '' ): ?string {
		$key = Xophz_Compass_Connector_Key::get( $connector_id );

		return '' === $key ? null : $key;
	}

	/**
	 * Safe remote GET request.
	 *
	 * @param string $url  Target remote URL.
	 * @param array  $args Optional wp_remote_get arguments.
	 * @return array|WP_Error
	 */
	public static function safe_remote_get( string $url, array $args = array() ) {
		$default_args = array(
			'timeout'     => 10,
			'redirection' => 3,
			'user-agent'  => 'Project-Compass-HTTP/1.0',
		);
		$request_args = wp_parse_args( $args, $default_args );
		return wp_safe_remote_get( $url, $request_args );
	}

	/**
	 * Safe remote POST request.
	 *
	 * @param string $url  Target remote URL.
	 * @param array  $args Optional wp_remote_post arguments.
	 * @return array|WP_Error
	 */
	public static function safe_remote_post( string $url, array $args = array() ) {
		$default_args = array(
			'timeout'     => 15,
			'redirection' => 2,
			'user-agent'  => 'Project-Compass-HTTP/1.0',
		);
		$request_args = wp_parse_args( $args, $default_args );
		return wp_safe_remote_post( $url, $request_args );
	}

	/**
	 * Perform a cached GET request with automatic transient caching.
	 *
	 * @param string $url       Target remote URL.
	 * @param int    $cache_ttl Cache duration in seconds (default 3600 = 1 hour).
	 * @param array  $args      Optional wp_remote_get arguments.
	 * @return array{status: int, body: string, cached: bool}|WP_Error
	 */
	public static function cached_get( string $url, int $cache_ttl = 3600, array $args = array() ) {
		$cache_key = 'compass_http_' . md5( $url . serialize( $args ) );
		$cached = get_transient( $cache_key );

		if ( false !== $cached && is_array( $cached ) ) {
			$cached['cached'] = true;
			return $cached;
		}

		$response = self::safe_remote_get( $url, $args );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$status = (int) wp_remote_retrieve_response_code( $response );
		$body   = (string) wp_remote_retrieve_body( $response );

		$result = array(
			'status' => $status,
			'body'   => $body,
			'cached' => false,
		);

		if ( $status >= 200 && $status < 300 && $cache_ttl > 0 ) {
			set_transient( $cache_key, $result, $cache_ttl );
		}

		return $result;
	}

	/**
	 * Safe remote POST request with standard error handling.
	 *
	 * @param string $url  Target remote URL.
	 * @param array  $body Request payload.
	 * @param array  $args Optional wp_remote_post arguments.
	 * @return array{status: int, body: string}|WP_Error
	 */
	public static function safe_post( string $url, array $body, array $args = array() ) {
		$args['body'] = $body;
		$response = self::safe_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return array(
			'status' => (int) wp_remote_retrieve_response_code( $response ),
			'body'   => (string) wp_remote_retrieve_body( $response ),
		);
	}
}
