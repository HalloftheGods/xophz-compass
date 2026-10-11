<?php
/**
 * Reads a credential VALUE for a connector registered with the WordPress core Connectors API.
 *
 * Core exposes no public function that returns a key's value, so this resolver follows the
 * same precedence as core's _wp_connectors_get_api_key_source(): environment variable, then
 * PHP constant, then the database option. Every name comes from the connector's own
 * `authentication` definition; this class never hard-codes an option, constant or env name.
 *
 * The value is for server-side use only. Never echo it, log it, return it from a REST route
 * or place it in a URL that is logged.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Connector_Key {

	/**
	 * The credential for a registered connector, or '' when it is unregistered or unset.
	 *
	 * @param string $connector_id Connector id, e.g. 'pinata_jwt' or 'google'.
	 * @return string
	 */
	public static function get( string $connector_id ): string {
		$is_available = function_exists( 'wp_get_connector' ) && function_exists( 'wp_is_connector_registered' );
		if ( ! $is_available || ! wp_is_connector_registered( $connector_id ) ) {
			return '';
		}

		$connector = wp_get_connector( $connector_id );
		$auth      = is_array( $connector ) ? ( $connector['authentication'] ?? array() ) : array();

		$from_env = self::from_env( (string) ( $auth['env_var_name'] ?? '' ) );
		if ( '' !== $from_env ) {
			return $from_env;
		}

		$from_constant = self::from_constant( (string) ( $auth['constant_name'] ?? '' ) );
		if ( '' !== $from_constant ) {
			return $from_constant;
		}

		return self::from_option( (string) ( $auth['setting_name'] ?? '' ) );
	}

	private static function from_env( string $env_var_name ): string {
		return '' === $env_var_name ? '' : self::as_string( getenv( $env_var_name ) );
	}

	private static function from_constant( string $constant_name ): string {
		$is_defined = '' !== $constant_name && defined( $constant_name );
		return $is_defined ? self::as_string( constant( $constant_name ) ) : '';
	}

	private static function from_option( string $setting_name ): string {
		return '' === $setting_name ? '' : self::as_string( get_option( $setting_name, '' ) );
	}

	private static function as_string( $value ): string {
		return is_scalar( $value ) ? trim( (string) $value ) : '';
	}
}
