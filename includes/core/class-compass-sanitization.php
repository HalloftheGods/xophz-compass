<?php
/**
 * Strict sanitization and request extraction utilities.
 * Replaces vulnerable Xophz_Compass::get_input_json() and deprecated FILTER_SANITIZE_STRING calls.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Sanitization {

	/**
	 * Retrieve current HTTP request method safely without deprecated FILTER_SANITIZE_STRING.
	 *
	 * @return string
	 */
	public static function get_http_method(): string {
		$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
		$method = strtoupper( trim( (string) $method ) );
		$allowed = array( 'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD' );
		return in_array( $method, $allowed, true ) ? $method : 'GET';
	}

	/**
	 * Retrieve and decode JSON payload from request body.
	 * Returns null if payload is invalid or malformed, preventing unhandled object crashes.
	 *
	 * @param bool $as_array When true, returns associative array; otherwise stdClass.
	 * @return mixed|null
	 */
	public static function get_json_input( bool $as_array = false ) {
		$raw = file_get_contents( 'php://input' );
		if ( empty( $raw ) ) {
			return null;
		}

		$decoded = json_decode( $raw, $as_array );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return null;
		}

		return $decoded;
	}

	/**
	 * Safely extract sanitized text string from an array.
	 *
	 * @param array  $source  Source array (e.g. $_POST, $_GET).
	 * @param string $key     Array key.
	 * @param string $default Default value.
	 * @return string
	 */
	public static function get_string( array $source, string $key, string $default = '' ): string {
		if ( ! isset( $source[ $key ] ) || ! is_scalar( $source[ $key ] ) ) {
			return $default;
		}
		return sanitize_text_field( wp_unslash( (string) $source[ $key ] ) );
	}

	/**
	 * Safely extract integer from an array.
	 *
	 * @param array  $source  Source array.
	 * @param string $key     Array key.
	 * @param int    $default Default value.
	 * @return int
	 */
	public static function get_int( array $source, string $key, int $default = 0 ): int {
		if ( ! isset( $source[ $key ] ) || ! is_numeric( $source[ $key ] ) ) {
			return $default;
		}
		return intval( $source[ $key ] );
	}

	/**
	 * Safely extract float from an array.
	 *
	 * @param array  $source  Source array.
	 * @param string $key     Array key.
	 * @param float  $default Default value.
	 * @return float
	 */
	public static function get_float( array $source, string $key, float $default = 0.0 ): float {
		if ( ! isset( $source[ $key ] ) || ! is_numeric( $source[ $key ] ) ) {
			return $default;
		}
		return floatval( $source[ $key ] );
	}

	/**
	 * Safely extract boolean from an array.
	 *
	 * @param array  $source  Source array.
	 * @param string $key     Array key.
	 * @param bool   $default Default value.
	 * @return bool
	 */
	public static function get_bool( array $source, string $key, bool $default = false ): bool {
		if ( ! isset( $source[ $key ] ) ) {
			return $default;
		}
		return filter_var( $source[ $key ], FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Safely extract array of strings with element sanitization.
	 *
	 * @param array  $source  Source array.
	 * @param string $key     Array key.
	 * @param array  $default Default value.
	 * @return array<int, string>
	 */
	public static function get_string_array( array $source, string $key, array $default = array() ): array {
		if ( ! isset( $source[ $key ] ) || ! is_array( $source[ $key ] ) ) {
			return $default;
		}
		return array_values( array_map( 'sanitize_text_field', wp_unslash( $source[ $key ] ) ) );
	}

	/**
	 * Recursively sanitize an array, object, or scalar value.
	 *
	 * @param mixed $data Data to sanitize.
	 * @return mixed
	 */
	public static function sanitize_deep( $data ) {
		if ( is_array( $data ) ) {
			foreach ( $data as $key => $val ) {
				$data[ $key ] = self::sanitize_deep( $val );
			}
			return $data;
		}

		if ( is_object( $data ) ) {
			foreach ( get_object_vars( $data ) as $key => $val ) {
				$data->$key = self::sanitize_deep( $val );
			}
			return $data;
		}

		if ( is_string( $data ) ) {
			return sanitize_text_field( $data );
		}

		return $data;
	}

	/**
	 * Static wrapper for WordPress sanitize_text_field.
	 *
	 * @param string $str Input string.
	 * @return string
	 */
	public static function sanitize_text_field( string $str ): string {
		return sanitize_text_field( $str );
	}
}
