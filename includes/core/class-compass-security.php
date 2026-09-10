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
		$current        = get_transient( $transient_name );

		if ( false === $current ) {
			set_transient( $transient_name, 1, $window_seconds );
			return true;
		}

		$current = (int) $current;
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

	/**
	 * Retrieve all allowed redirect domains for the Compass ecosystem.
	 *
	 * @return array
	 */
	public static function get_allowed_redirect_hosts(): array {
		$default_hosts = array(
			'xophz.com',
			'mycompassconsulting.com',
			'blackboxwhiteglove.com',
			'youmeos.com',
			'forthexp.com',
			'glowitheflow.com',
			'sacredrealm.org',
			'hallofthegods.com',
			'localhost',
			'127.0.0.1',
			'mycompass-localhost',
			'mycompass.localhost',
		);

		// Include current site host
		$current_host = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( ! empty( $current_host ) && ! in_array( $current_host, $default_hosts, true ) ) {
			$default_hosts[] = $current_host;
		}

		// Include admin-configured ecosystem domains
		$custom_domains = get_option( 'compass_trusted_redirect_domains', array() );
		if ( is_string( $custom_domains ) ) {
			$custom_domains = array_filter( array_map( 'trim', explode( "\n", $custom_domains ) ) );
		}
		if ( is_array( $custom_domains ) && ! empty( $custom_domains ) ) {
			foreach ( $custom_domains as $d ) {
				$clean_d = strtolower( trim( (string) $d ) );
				if ( ! empty( $clean_d ) && ! in_array( $clean_d, $default_hosts, true ) ) {
					$default_hosts[] = $clean_d;
				}
			}
		}

		// Include verified Hookshot client domains
		$hookshot_domains = get_option( 'xophz_hookshot_client_domains', array() );
		if ( is_array( $hookshot_domains ) && ! empty( $hookshot_domains ) ) {
			foreach ( $hookshot_domains as $hd ) {
				$clean_hd = strtolower( trim( (string) $hd ) );
				if ( ! empty( $clean_hd ) && ! in_array( $clean_hd, $default_hosts, true ) ) {
					$default_hosts[] = $clean_hd;
				}
			}
		}

		return apply_filters( 'compass_allowed_redirect_hosts', $default_hosts );
	}

	/**
	 * Verify whether a redirect URL belongs to an authorized ecosystem host.
	 *
	 * @param string $url The destination URL.
	 * @return bool True if destination is authorized, false otherwise.
	 */
	public static function is_allowed_redirect_url( string $url ): bool {
		if ( empty( $url ) ) {
			return false;
		}

		$parsed_scheme = wp_parse_url( $url, PHP_URL_SCHEME );
		if ( empty( $parsed_scheme ) || ! in_array( strtolower( $parsed_scheme ), array( 'http', 'https' ), true ) ) {
			return false;
		}

		$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
		if ( empty( $host ) ) {
			return false;
		}

		$allowed_hosts = self::get_allowed_redirect_hosts();

		foreach ( $allowed_hosts as $allowed ) {
			$allowed = strtolower( trim( $allowed ) );
			if ( empty( $allowed ) ) {
				continue;
			}

			// Exact match or local dev match
			if ( $host === $allowed || $host === 'localhost' || $host === '127.0.0.1' ) {
				return true;
			}

			// Subdomain match (e.g. *.xophz.com or xophz.com matches app.xophz.com)
			if ( str_starts_with( $allowed, '*.' ) ) {
				$root_pattern = substr( $allowed, 2 );
				if ( $host === $root_pattern || str_ends_with( $host, '.' . $root_pattern ) ) {
					return true;
				}
			} elseif ( str_ends_with( $host, '.' . $allowed ) ) {
				return true;
			}

			// Localhost development pattern (*.local, *.test, *.localhost)
			if ( in_array( $allowed, array( '*.local', '*.test', '*.localhost' ), true ) ) {
				$suffix = substr( $allowed, 1 );
				if ( str_ends_with( $host, $suffix ) ) {
					return true;
				}
			}
		}

		return false;
	}
}
