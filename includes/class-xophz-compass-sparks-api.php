<?php

/**
 * The Sparks API
 *
 * Handles the REST API endpoints to register and list external sparks
 * for YouMeOS.
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

class Xophz_Compass_Sparks_API {

	/**
	 * Register the REST API routes for the Sparks system.
	 */
	public function register_routes() {
		register_rest_route( 'xophz/v1', '/sparks', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_sparks' ),
				'permission_callback' => '__return_true', // YouMeOS might not pass auth if not logged in, but let's assume it checks internally or restrict it if needed.
			)
		) );

		register_rest_route( 'xophz/v1', '/sparks/(?P<id>[a-zA-Z0-9-]+)', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_spark_manifest' ),
				'permission_callback' => '__return_true',
				'args'     => array(
					'id' => array(
						'validate_callback' => function($param, $request, $key) {
							return is_string($param);
						}
					),
				),
			)
		) );
	}

	/**
	 * Get list of all registered sparks.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_sparks( $request ) {
		// Use a filter so other plugins can push their sparks to the payload
		$sparks = apply_filters( 'xophz_register_sparks', array() );
		return rest_ensure_response( $sparks );
	}

	/**
	 * Get a specific spark's manifest.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_spark_manifest( $request ) {
		$id = sanitize_text_field( $request->get_param( 'id' ) );
		$manifest = apply_filters( 'xophz_get_spark_manifest', null, $id );

		if ( empty( $manifest ) ) {
			return new WP_Error( 'rest_spark_not_found', 'No spark manifest found for this ID.', array( 'status' => 404 ) );
		}

		return rest_ensure_response( $manifest );
	}
}
