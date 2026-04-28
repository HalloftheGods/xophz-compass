<?php

class Xophz_Compass_Performance_API {

	public function register_routes() {
		register_rest_route( 'xophz/v1', '/perform/widgets', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_widgets' ),
			'permission_callback' => function() {
				return current_user_can( 'read' );
			},
		));

		register_rest_route( 'xophz/v1', '/perform/pinned', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_pinned_widgets' ),
				'permission_callback' => function() { return is_user_logged_in(); },
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_pinned_widgets' ),
				'permission_callback' => function() { return is_user_logged_in(); },
			)
		));
	}

	public function get_widgets() {
		$widgets = apply_filters( 'compass_perform_widgets', array() );
		usort( $widgets, function( $a, $b ) {
			return ( $a['order'] ?? 50 ) - ( $b['order'] ?? 50 );
		});
		return rest_ensure_response( $widgets );
	}

	public function get_pinned_widgets() {
		$user_id = get_current_user_id();
		$pinned = get_user_meta( $user_id, '_xophz_compass_pinned_widgets', true );
		if ( ! is_array( $pinned ) ) {
			$pinned = array();
		}
		return rest_ensure_response( $pinned );
	}

	public function update_pinned_widgets( WP_REST_Request $request ) {
		$user_id = get_current_user_id();
		$pinned = $request->get_param( 'pinned' );
		
		if ( ! is_array( $pinned ) ) {
			return new WP_Error( 'invalid_data', 'Pinned widgets must be an array', array( 'status' => 400 ) );
		}

		// Sanitize to array of strings
		$sanitized = array_map( 'sanitize_text_field', $pinned );
		update_user_meta( $user_id, '_xophz_compass_pinned_widgets', $sanitized );

		return rest_ensure_response( $sanitized );
	}
}
