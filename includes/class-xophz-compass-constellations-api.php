<?php

/**
 * The Constellations API
 *
 * Handles the REST API endpoints and global hooks for the multidimensional
 * relationship engine across the COMPASS platform.
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

class Xophz_Compass_Constellations_API {

	/**
	 * Register the REST API routes for the Constellations engine.
	 */
	public function register_routes() {
		register_rest_route( 'compass/v1', '/constellations', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_constellations' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'     => array(
					'origin_id' => array(
						'required'          => false,
						'validate_callback' => 'is_numeric'
					),
					'target_id' => array(
						'required'          => false,
						'validate_callback' => 'is_numeric'
					),
					'relation_type' => array(
						'required'          => false,
						'validate_callback' => 'is_string'
					),
				),
			),
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'create_constellation' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'     => array(
					'origin_id' => array(
						'required'          => true,
						'validate_callback' => 'is_numeric'
					),
					'target_id' => array(
						'required'          => true,
						'validate_callback' => 'is_numeric'
					),
					'relation_type' => array(
						'required'          => true,
						'validate_callback' => 'is_string'
					),
				),
			),
			array(
				'methods'  => WP_REST_Server::DELETABLE,
				'callback' => array( $this, 'delete_constellation' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'     => array(
					'id' => array(
						'required'          => false,
						'validate_callback' => 'is_numeric'
					),
					'origin_id' => array(
						'required'          => false,
						'validate_callback' => 'is_numeric'
					),
					'target_id' => array(
						'required'          => false,
						'validate_callback' => 'is_numeric'
					),
				),
			)
		) );
	}

	/**
	 * Check if a given request has access to manage constellations
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|bool
	 */
	public function check_permissions( $request ) {
		// By default, require edit_posts capability to manage relationships
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Get constellations
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_constellations( $request ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'compass_constellations';

		$origin_id = $request->get_param( 'origin_id' );
		$target_id = $request->get_param( 'target_id' );
		$relation_type = $request->get_param( 'relation_type' );

		$query = "SELECT * FROM {$table_name} WHERE 1=1";
		$prepare_args = array();

		if ( ! empty( $origin_id ) ) {
			$query .= " AND origin_id = %d";
			$prepare_args[] = $origin_id;
		}

		if ( ! empty( $target_id ) ) {
			$query .= " AND target_id = %d";
			$prepare_args[] = $target_id;
		}

		if ( ! empty( $relation_type ) ) {
			$query .= " AND relation_type = %s";
			$prepare_args[] = $relation_type;
		}

		$query .= " ORDER BY created_at DESC LIMIT 100";

		if ( ! empty( $prepare_args ) ) {
			$results = $wpdb->get_results( $wpdb->prepare( $query, $prepare_args ) );
		} else {
			$results = $wpdb->get_results( $query );
		}

		return rest_ensure_response( $results );
	}

	/**
	 * Create a new constellation mapping
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_constellation( $request ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'compass_constellations';

		$origin_id = $request->get_param( 'origin_id' );
		$target_id = $request->get_param( 'target_id' );
		$relation_type = sanitize_key( $request->get_param( 'relation_type' ) );

		// Check if it already exists to prevent duplicates
		$exists = $wpdb->get_var( $wpdb->prepare(
			"SELECT id FROM {$table_name} WHERE origin_id = %d AND target_id = %d AND relation_type = %s",
			$origin_id, $target_id, $relation_type
		) );

		if ( $exists ) {
			return new WP_Error( 'constellation_exists', 'This constellation mapping already exists.', array( 'status' => 409 ) );
		}

		$inserted = $wpdb->insert(
			$table_name,
			array(
				'origin_id' => $origin_id,
				'target_id' => $target_id,
				'relation_type' => $relation_type,
				'created_at' => current_time( 'mysql' )
			),
			array( '%d', '%d', '%s', '%s' )
		);

		if ( ! $inserted ) {
			return new WP_Error( 'db_error', 'Failed to save constellation.', array( 'status' => 500 ) );
		}

		$id = $wpdb->insert_id;

		return rest_ensure_response( array(
			'id' => $id,
			'origin_id' => $origin_id,
			'target_id' => $target_id,
			'relation_type' => $relation_type,
			'message' => 'Constellation created successfully.'
		) );
	}

	/**
	 * Delete a constellation mapping
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function delete_constellation( $request ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'compass_constellations';

		$id = $request->get_param( 'id' );
		$origin_id = $request->get_param( 'origin_id' );
		$target_id = $request->get_param( 'target_id' );

		if ( empty( $id ) && empty( $origin_id ) && empty( $target_id ) ) {
			return new WP_Error( 'missing_params', 'Must provide an ID, or an origin_id / target_id to delete.', array( 'status' => 400 ) );
		}

		$where = array();
		$where_format = array();

		if ( ! empty( $id ) ) {
			$where['id'] = $id;
			$where_format[] = '%d';
		} else {
			if ( ! empty( $origin_id ) ) {
				$where['origin_id'] = $origin_id;
				$where_format[] = '%d';
			}
			if ( ! empty( $target_id ) ) {
				$where['target_id'] = $target_id;
				$where_format[] = '%d';
			}
		}

		$deleted = $wpdb->delete( $table_name, $where, $where_format );

		if ( $deleted === false ) {
			return new WP_Error( 'db_error', 'Failed to delete constellation.', array( 'status' => 500 ) );
		}

		return rest_ensure_response( array(
			'deleted_rows' => $deleted,
			'message' => 'Constellation(s) deleted successfully.'
		) );
	}

	/**
	 * Clean up orphaned connections when a post is deleted
	 *
	 * @param int $post_id
	 */
	public function cleanup_orphaned_post( $post_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'compass_constellations';

		// We assume custom post types use standard post IDs for both origin and target
		// Note: Depending on the specific usage, a user ID might be passed here, so we just
		// clean up anything where origin or target matches this ID.
		$wpdb->delete( $table_name, array( 'origin_id' => $post_id ), array( '%d' ) );
		$wpdb->delete( $table_name, array( 'target_id' => $post_id ), array( '%d' ) );
	}

	/**
	 * Clean up orphaned connections when a user is deleted
	 *
	 * @param int $user_id
	 */
	public function cleanup_orphaned_user( $user_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'compass_constellations';

		// Users might be mapped as origins or targets
		$wpdb->delete( $table_name, array( 'origin_id' => $user_id ), array( '%d' ) );
		$wpdb->delete( $table_name, array( 'target_id' => $user_id ), array( '%d' ) );
	}
}
