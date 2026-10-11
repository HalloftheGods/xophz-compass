<?php
/**
 * Reads a credential VALUE for a connector registered with the WordPress core Connectors API.
 *
 * This is the one credential resolver in the core plugin: every credential read goes through
 * get(). Core exposes no public function that returns a key's value, so get() follows the same
 * precedence as core's _wp_connectors_get_api_key_source(): environment variable, then PHP
 * constant, then the database option. Every name comes from the connector's own
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
		$auth = self::authentication( $connector_id );

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

	/**
	 * Whether the connector resolves to a non-empty credential from any source.
	 */
	public static function is_configured( string $connector_id ): bool {
		return '' !== self::get( $connector_id );
	}

	/**
	 * Where get() finds the credential: 'env', 'constant', 'option', or '' when unset.
	 * Lets callers refuse to overwrite a value an environment variable or constant pins.
	 */
	public static function source( string $connector_id ): string {
		$auth = self::authentication( $connector_id );

		$sources = array(
			'env'      => self::from_env( (string) ( $auth['env_var_name'] ?? '' ) ),
			'constant' => self::from_constant( (string) ( $auth['constant_name'] ?? '' ) ),
			'option'   => self::from_option( (string) ( $auth['setting_name'] ?? '' ) ),
		);

		foreach ( $sources as $source => $value ) {
			if ( '' !== $value ) {
				return $source;
			}
		}

		return '';
	}

	/**
	 * The option a registered connector stores its value in, or '' when it is unregistered or
	 * declares none. For writing a value the server produces itself, such as a generated key.
	 */
	public static function setting_name( string $connector_id ): string {
		return (string) ( self::authentication( $connector_id )['setting_name'] ?? '' );
	}

	/**
	 * The setting names of every registered connector, for allowlisting writes.
	 *
	 * @return string[]
	 */
	public static function registered_setting_names(): array {
		if ( ! function_exists( 'wp_get_connectors' ) ) {
			return array();
		}

		$names = array();
		foreach ( (array) wp_get_connectors() as $connector ) {
			$name = is_array( $connector ) ? (string) ( $connector['authentication']['setting_name'] ?? '' ) : '';
			if ( '' !== $name ) {
				$names[ $name ] = true;
			}
		}

		return array_keys( $names );
	}

	private static function authentication( string $connector_id ): array {
		$is_available = function_exists( 'wp_get_connector' ) && function_exists( 'wp_is_connector_registered' );
		if ( ! $is_available || ! wp_is_connector_registered( $connector_id ) ) {
			return array();
		}

		$connector = wp_get_connector( $connector_id );

		return is_array( $connector ) ? (array) ( $connector['authentication'] ?? array() ) : array();
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
