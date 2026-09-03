<?php
/**
 * Unified HTTP & Connectors Client for Compass plugins.
 * Replaces duplicate WP Connectors API key resolvers and provides resilient remote calls.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_HTTP {

	/**
	 * Retrieve official Gemini API Key through WP Connectors cascade.
	 * Replaces duplicate 50-line key discovery logic across child plugins.
	 *
	 * @return string|null
	 */
	public static function get_gemini_api_key(): ?string {
		// Tier 1: Check WP Connectors API for 'google_gemini_api_key' and 'google'
		if ( function_exists( 'wp_get_connectors' ) ) {
			$connectors = wp_get_connectors();

			if ( ! empty( $connectors['google_gemini_api_key']['authentication']['setting_name'] ) ) {
				$setting = $connectors['google_gemini_api_key']['authentication']['setting_name'];
				$val = get_option( $setting, '' );
				if ( ! empty( $val ) && is_string( $val ) ) {
					return trim( $val );
				}
			}

			if ( ! empty( $connectors['google']['authentication']['setting_name'] ) ) {
				$setting = $connectors['google']['authentication']['setting_name'];
				$val = get_option( $setting, '' );
				if ( ! empty( $val ) && is_string( $val ) ) {
					return trim( $val );
				}
			}
		}

		// Tier 2: WordPress options fallbacks
		$fallback_options = array(
			'connectors_ai_google_api_key',
			'compass_gemini_api_key',
			'xophz_gemini_api_key',
			'gemini_api_key',
		);

		foreach ( $fallback_options as $opt_key ) {
			$val = get_option( $opt_key, '' );
			if ( ! empty( $val ) && is_string( $val ) ) {
				return trim( $val );
			}
		}

		// Tier 3: PHP Constants
		if ( defined( 'GEMINI_API_KEY' ) ) {
			$const_val = constant( 'GEMINI_API_KEY' );
			if ( ! empty( $const_val ) && is_string( $const_val ) ) {
				return trim( $const_val );
			}
		}

		// Tier 4: $_ENV superglobal
		if ( ! empty( $_ENV['GEMINI_API_KEY'] ) && is_string( $_ENV['GEMINI_API_KEY'] ) ) {
			return trim( $_ENV['GEMINI_API_KEY'] );
		}

		// Tier 5: getenv() lookup
		$env_val = getenv( 'GEMINI_API_KEY' );
		if ( ! empty( $env_val ) && is_string( $env_val ) ) {
			return trim( $env_val );
		}

		return null;
	}

	/**
	 * Generic API key resolver using WP 7.0 Connectors, option fallbacks, and env variables.
	 *
	 * @param string             $connector_id     WP Connector ID.
	 * @param array<int, string> $fallback_options WordPress option keys.
	 * @param string             $env_var_name     Environment variable name.
	 * @return string|null
	 */
	public static function resolve_api_key( string $connector_id, array $fallback_options, string $env_var_name ): ?string {
		// Tier 1: WP 7.0 Connectors API
		if ( function_exists( 'wp_get_connectors' ) ) {
			$connectors = wp_get_connectors();
			if ( ! empty( $connectors[ $connector_id ]['authentication']['setting_name'] ) ) {
				$setting = $connectors[ $connector_id ]['authentication']['setting_name'];
				$val = get_option( $setting, '' );
				if ( ! empty( $val ) && is_string( $val ) ) {
					return trim( $val );
				}
			}
		}

		// Tier 2: WordPress options fallbacks
		foreach ( $fallback_options as $opt_key ) {
			$val = get_option( $opt_key, '' );
			if ( ! empty( $val ) && is_string( $val ) ) {
				return trim( $val );
			}
		}

		// Tier 3: PHP Constants
		if ( defined( $env_var_name ) ) {
			$const_val = constant( $env_var_name );
			if ( ! empty( $const_val ) && is_string( $const_val ) ) {
				return trim( $const_val );
			}
		}

		// Tier 4: $_ENV superglobal
		if ( ! empty( $_ENV[ $env_var_name ] ) && is_string( $_ENV[ $env_var_name ] ) ) {
			return trim( $_ENV[ $env_var_name ] );
		}

		// Tier 5: getenv() lookup
		$env_val = getenv( $env_var_name );
		if ( ! empty( $env_val ) && is_string( $env_val ) ) {
			return trim( $env_val );
		}

		return null;
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
