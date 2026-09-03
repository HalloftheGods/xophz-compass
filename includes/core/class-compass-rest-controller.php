<?php
/**
 * Standardized base REST controller for all Compass endpoints.
 * Extends WP_REST_Controller to enforce consistent security, validation, and JSON responses.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_REST_Controller' ) && file_exists( ABSPATH . 'wp-includes/rest-api/endpoints/class-wp-rest-controller.php' ) ) {
	require_once ABSPATH . 'wp-includes/rest-api/endpoints/class-wp-rest-controller.php';
}

abstract class Xophz_Compass_REST_Controller extends WP_REST_Controller {

	/**
	 * Submodule / resource namespace slug.
	 */
	protected string $resource_slug;

	/**
	 * Constructor.
	 *
	 * @param string $resource_slug Submodule namespace slug (e.g. 'card-vault', 'yellow-links').
	 * @param string $api_version   API version slug (default 'v1').
	 */
	public function __construct( string $resource_slug, string $api_version = 'v1' ) {
		$this->resource_slug = $resource_slug;
		$this->namespace     = 'compass/' . $api_version . '/' . trim( $resource_slug, '/' );
	}

	/**
	 * Register routes with WordPress. Overridden in concrete controllers.
	 */
	public function register_routes() {
		// Overridden in concrete controllers.
	}

	/**
	 * Check capability permission for a REST request.
	 *
	 * @param WP_REST_Request $request    REST request object.
	 * @param string          $capability Required capability. Default 'manage_options'.
	 * @return bool|WP_Error
	 */
	public function check_permission( WP_REST_Request $request, string $capability = 'manage_options' ) {
		if ( ! current_user_can( $capability ) ) {
			return new WP_Error(
				'rest_forbidden_cap',
				sprintf( esc_html__( 'The capability "%s" is required for this action.', 'xophz-compass' ), esc_html( $capability ) ),
				array( 'status' => rest_authorization_required_code() )
			);
		}
		return true;
	}

	/**
	 * Verify nonce on a REST request.
	 *
	 * @param WP_REST_Request $request REST request object.
	 * @param string          $action  Expected nonce action. Default 'wp_rest'.
	 * @return bool|WP_Error
	 */
	public function verify_nonce( WP_REST_Request $request, string $action = 'wp_rest' ) {
		$nonce = $request->get_header( 'x_wp_nonce' );
		if ( empty( $nonce ) ) {
			$nonce = $request->get_param( '_wpnonce' );
		}
		if ( empty( $nonce ) || ! wp_verify_nonce( (string) $nonce, $action ) ) {
			return new WP_Error(
				'rest_invalid_nonce',
				esc_html__( 'Security nonce verification failed.', 'xophz-compass' ),
				array( 'status' => 403 )
			);
		}
		return true;
	}

	/**
	 * Standard permission check: Requires administrator capability (manage_options).
	 *
	 * @param WP_REST_Request $request REST request object.
	 * @return bool|WP_Error
	 */
	public function permissions_admin( WP_REST_Request $request ) {
		return $this->check_permission( $request, 'manage_options' );
	}

	/**
	 * Standard permission check: Requires logged-in WordPress user.
	 *
	 * @param WP_REST_Request $request REST request object.
	 * @return bool|WP_Error
	 */
	public function permissions_authenticated( WP_REST_Request $request ) {
		if ( ! is_user_logged_in() ) {
			return new WP_Error(
				'rest_forbidden_auth',
				esc_html__( 'Authentication is required to access this endpoint.', 'xophz-compass' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}
		return true;
	}

	/**
	 * Standard permission check: Public access with explicit declaration.
	 *
	 * @param WP_REST_Request $request REST request object.
	 * @return bool
	 */
	public function permissions_public( WP_REST_Request $request ): bool {
		return true;
	}

	/**
	 * Standard permission check: Specific WordPress user capability.
	 *
	 * @param string          $capability Required WordPress capability.
	 * @param WP_REST_Request $request    REST request object.
	 * @return bool|WP_Error
	 */
	public function permissions_cap( string $capability, WP_REST_Request $request ) {
		return $this->check_permission( $request, $capability );
	}

	/**
	 * Format a unified success response.
	 *
	 * @param mixed                 $data    Payload to return.
	 * @param int                   $status  HTTP status code (default 200).
	 * @param array<string, string> $headers Additional headers.
	 * @return WP_REST_Response
	 */
	protected function success( $data = null, int $status = 200, array $headers = array() ): WP_REST_Response {
		$response_data = array(
			'success' => true,
			'data'    => $data,
		);
		$response = new WP_REST_Response( $response_data, $status );
		foreach ( $headers as $name => $val ) {
			$response->header( $name, $val );
		}
		return $response;
	}

	/**
	 * Unified success response alias.
	 *
	 * @param mixed $data   Payload to return.
	 * @param int   $status HTTP status code. Default 200.
	 * @return WP_REST_Response
	 */
	public function success_response( $data, int $status = 200 ): WP_REST_Response {
		return $this->success( $data, $status );
	}

	/**
	 * Format a unified error response.
	 *
	 * @param string $code    Unique machine-readable error code.
	 * @param string $message Human-readable error message.
	 * @param int    $status  HTTP status code (default 400).
	 * @param array  $data    Optional contextual metadata.
	 * @return WP_Error
	 */
	protected function error( string $code, string $message, int $status = 400, array $data = array() ): WP_Error {
		$error_data = array_merge(
			array(
				'status'  => $status,
				'success' => false,
				'code'    => $code,
			),
			$data
		);
		return new WP_Error( $code, $message, $error_data );
	}

	/**
	 * Unified error response alias.
	 *
	 * @param string $code    Unique error code.
	 * @param string $message Human-readable error message.
	 * @param int    $status  HTTP status code. Default 400.
	 * @return WP_Error
	 */
	public function error_response( string $code, string $message, int $status = 400 ): WP_Error {
		return $this->error( $code, $message, $status );
	}

	/**
	 * Get sanitized string parameter from REST request.
	 *
	 * @param WP_REST_Request $request REST request object.
	 * @param string          $key     Parameter key.
	 * @param string          $default Default fallback.
	 * @return string
	 */
	protected function get_param_string( WP_REST_Request $request, string $key, string $default = '' ): string {
		$val = $request->get_param( $key );
		return is_string( $val ) ? sanitize_text_field( $val ) : $default;
	}

	/**
	 * Get sanitized integer parameter from REST request.
	 *
	 * @param WP_REST_Request $request REST request object.
	 * @param string          $key     Parameter key.
	 * @param int             $default Default fallback.
	 * @return int
	 */
	protected function get_param_int( WP_REST_Request $request, string $key, int $default = 0 ): int {
		$val = $request->get_param( $key );
		return is_numeric( $val ) ? intval( $val ) : $default;
	}

	/**
	 * Get sanitized boolean parameter from REST request.
	 *
	 * @param WP_REST_Request $request REST request object.
	 * @param string          $key     Parameter key.
	 * @param bool            $default Default fallback.
	 * @return bool
	 */
	protected function get_param_bool( WP_REST_Request $request, string $key, bool $default = false ): bool {
		$val = $request->get_param( $key );
		return null !== $val ? filter_var( $val, FILTER_VALIDATE_BOOLEAN ) : $default;
	}
}
