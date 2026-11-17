<?php

/**
 * Open roles (vacancies) for the Org Matrix. Stored as one option: a list of seats that
 * sit under a manager or a department until someone is hired into them.
 */
class Xophz_Compass_Matrix_Vacancies {

	const OPTION = 'compass_matrix_vacancies';

	public function register_routes() {
		register_rest_route( 'xophz/v1', '/matrix/vacancies', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'list_vacancies' ),
				'permission_callback' => function() {
					return current_user_can( 'list_users' );
				},
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_vacancy' ),
				'permission_callback' => function() {
					return current_user_can( 'edit_users' );
				},
			),
		));

		register_rest_route( 'xophz/v1', '/matrix/vacancies/(?P<id>[A-Za-z0-9]+)', array(
			'methods'             => WP_REST_Server::DELETABLE,
			'callback'            => array( $this, 'delete_vacancy' ),
			'permission_callback' => function() {
				return current_user_can( 'edit_users' );
			},
		));
	}

	private function all() {
		$stored = get_option( self::OPTION, array() );
		return is_array( $stored ) ? array_values( $stored ) : array();
	}

	public function list_vacancies() {
		return rest_ensure_response( $this->all() );
	}

	public function create_vacancy( $request ) {
		$params = $request->get_json_params();
		$title  = sanitize_text_field( $params['title'] ?? '' );
		if ( '' === $title ) {
			return new WP_Error( 'missing_title', 'A title is required.', array( 'status' => 400 ) );
		}

		$vacancy = array(
			'id'            => uniqid( 'v' ),
			'title'         => $title,
			'department_id' => absint( $params['department_id'] ?? 0 ),
			'reports_to'    => absint( $params['reports_to'] ?? 0 ),
		);

		$all   = $this->all();
		$all[] = $vacancy;
		update_option( self::OPTION, $all, false );

		return rest_ensure_response( $vacancy );
	}

	public function delete_vacancy( $request ) {
		$id  = sanitize_key( $request->get_param( 'id' ) );
		$all = array_values( array_filter( $this->all(), function( $v ) use ( $id ) {
			return ( $v['id'] ?? '' ) !== $id;
		} ) );
		update_option( self::OPTION, $all, false );

		return rest_ensure_response( array( 'success' => true ) );
	}
}
