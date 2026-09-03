<?php
/**
 * Security and authentication utility for Compass plugins.
 * Enforces strict nonce verification, capability guards, and request rate limiting.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Security {

	/**
	 * Verify AJAX request nonce.
	 *
	 * @param string $action    Nonce action name.
	 * @param string $query_arg Request parameter containing nonce.
	 * @return bool
	 */
	public static function verify_ajax_nonce( string $action, string $query_arg = 'nonce' ): bool {
		$nonce = $_REQUEST[ $query_arg ] ?? '';
		if ( empty( $nonce ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( (string) $nonce ) ), $action ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Check capability for current user.
	 *
	 * @param string $capability Capability name.
	 * @return bool
	 */
	public static function check_capability( string $capability ): bool {
		return current_user_can( $capability );
	}

	/**
	 * Enforce AJAX authentication: verifies capability and optional nonce, exiting with 403 on failure.
	 *
	 * @param string $capability Required capability. Default 'manage_options'.
	 * @param string $action     Optional nonce action to verify.
	 */
	public static function enforce_ajax_auth( string $capability = 'manage_options', string $action = '' ): void {
		if ( ! self::check_capability( $capability ) ) {
			wp_send_json_error(
				array(
					'code'    => 'unauthorized',
					'message' => sprintf( esc_html__( 'Capability "%s" is required.', 'xophz-compass' ), esc_html( $capability ) ),
				),
				403
			);
		}

		if ( ! empty( $action ) && ! self::verify_ajax_nonce( $action ) ) {
			wp_send_json_error(
				array(
					'code'    => 'invalid_nonce',
					'message' => esc_html__( 'Security check failed. Please refresh the page.', 'xophz-compass' ),
				),
				403
			);
		}
	}

	/**
	 * Verify AJAX request nonce and exit with 403 JSON if invalid.
	 *
	 * @param string $action    Nonce action name.
	 * @param string $query_arg Request parameter containing nonce.
	 * @return bool
	 */
	public static function verify_ajax( string $action = 'xophz_compass_nonce', string $query_arg = 'nonce' ): bool {
		if ( ! self::verify_ajax_nonce( $action, $query_arg ) ) {
			wp_send_json_error(
				array(
					'code'    => 'invalid_nonce',
					'message' => esc_html__( 'Security check failed. Please refresh the page.', 'xophz-compass' ),
				),
				403
			);
			return false;
		}
		return true;
	}

	/**
	 * Enforce administrator capability check.
	 *
	 * @return bool
	 */
	public static function require_admin(): bool {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'code'    => 'unauthorized',
					'message' => esc_html__( 'Administrator capability is required.', 'xophz-compass' ),
				),
				403
			);
			return false;
		}
		return true;
	}

	/**
	 * Enforce specific capability check.
	 *
	 * @param string $capability Capability name.
	 * @return bool
	 */
	public static function require_cap( string $capability ): bool {
		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error(
				array(
					'code'    => 'unauthorized',
					'message' => sprintf( esc_html__( 'Capability "%s" is required.', 'xophz-compass' ), esc_html( $capability ) ),
				),
				403
			);
			return false;
		}
		return true;
	}

	/**
	 * Check rate limit using WordPress transients.
	 *
	 * @param string $rate_key       Unique identifier (e.g. IP + action).
	 * @param int    $max_requests   Maximum requests allowed in window.
	 * @param int    $window_seconds Window duration in seconds.
	 * @return bool True if within limit, false if rate exceeded.
	 */
	public static function check_rate_limit( string $rate_key, int $max_requests = 60, int $window_seconds = 60 ): bool {
		$transient_name = 'compass_rl_' . md5( $rate_key );
		$current = (int) get_transient( $transient_name );

		if ( false === $current ) {
			set_transient( $transient_name, 1, $window_seconds );
			return true;
		}

		if ( $current >= $max_requests ) {
			return false;
		}

		set_transient( $transient_name, $current + 1, $window_seconds );
		return true;
	}

	/**
	 * Validate and sanitize a plugin slug against an allowlist of Compass plugins.
	 * Prevents directory traversal and arbitrary plugin activation attacks.
	 *
	 * @param string $slug Input slug.
	 * @return string|null Sanitized slug if valid, null if invalid.
	 */
	public static function validate_compass_slug( string $slug ): ?string {
		$clean = sanitize_key( $slug );
		if ( empty( $clean ) ) {
			return null;
		}

		// Verify plugin exists in wp-content/plugins/xophz-compass-*
		$target_file = WP_PLUGIN_DIR . '/xophz-compass-' . $clean . '/xophz-compass-' . $clean . '.php';
		if ( ! file_exists( $target_file ) ) {
			return null;
		}

		return $clean;
	}
}
