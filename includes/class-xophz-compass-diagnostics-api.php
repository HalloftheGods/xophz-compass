<?php

/**
 * Pre-flight diagnostics for the setup wizard.
 *
 * Every check runs on the server and reports what it actually found, so the
 * wizard never shows a result that was not measured.
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

class Xophz_Compass_Diagnostics_API {

	/**
	 * Register GET /xophz/v1/system/diagnostics.
	 */
	public function register_routes() {
		register_rest_route( 'xophz/v1', '/system/diagnostics', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_diagnostics' ),
			'permission_callback' => array( $this, 'check_permissions' ),
		) );
	}

	/**
	 * Administrators only. A cookie request with a bad nonce is treated as
	 * logged out, so reaching this callback also proves the nonce is valid.
	 */
	public function check_permissions() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Run every check and return the results with a timestamp.
	 */
	public function get_diagnostics( WP_REST_Request $request ) {
		return rest_ensure_response( array(
			'checked_at' => current_time( 'mysql' ),
			'checks'     => array(
				$this->check_session(),
				$this->check_constellations_table(),
				$this->check_connectors_api(),
				$this->check_golden_keys(),
			),
		) );
	}

	private function check_session() {
		$user = wp_get_current_user();
		return $this->result(
			'WordPress REST Nonce Verification',
			true,
			sprintf( 'Signed in as %s', $user->user_login )
		);
	}

	private function check_constellations_table() {
		global $wpdb;

		$table  = $wpdb->prefix . 'compass_constellations';
		$exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) === $table;

		return $this->result(
			'COMPASS Core Database Tables',
			$exists,
			$exists ? 'compass_constellations table present' : 'compass_constellations table missing. Reactivate COMPASS to create it.'
		);
	}

	private function check_connectors_api() {
		$available = function_exists( 'wp_is_connector_registered' );

		return $this->result(
			'WP Connectors API Bridge',
			$available,
			$available ? 'WordPress Connectors API available' : 'WordPress Connectors API not available on this WordPress version'
		);
	}

	private function check_golden_keys() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$active = is_plugin_active( 'xophz-compass-golden-keys/xophz-compass-golden-keys.php' );

		return $this->result(
			'Golden Keys Vault Status',
			$active,
			$active ? 'Golden Keys plugin active' : 'Golden Keys plugin not active (optional)'
		);
	}

	private function result( $label, $passed, $details ) {
		return array(
			'label'   => $label,
			'passed'  => (bool) $passed,
			'details' => $details,
		);
	}
}
