<?php

class Xophz_Compass_Matrix_API {

	public function register_taxonomy() {
		register_taxonomy( 'compass_department', 'user', array(
			'label'             => 'Department',
			'public'            => false,
			'show_in_rest'      => false,
			'rewrite'           => false,
			'hierarchical'      => true,
			'show_ui'           => false,
			'show_admin_column' => false,
		));
	}

	public function register_routes() {
		register_rest_route( 'xophz/v1', '/matrix/users', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_users' ),
			'permission_callback' => function() {
				return current_user_can( 'list_users' );
			},
		));

		register_rest_route( 'xophz/v1', '/matrix/users/(?P<id>\d+)', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'update_user' ),
			'permission_callback' => function() {
				return current_user_can( 'edit_users' );
			},
			'args' => array(
				'id' => array(
					'validate_callback' => function( $param ) {
						return is_numeric( $param );
					},
				),
			),
		));

		register_rest_route( 'xophz/v1', '/matrix/users/(?P<id>\d+)/reset-password', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'reset_password' ),
			'permission_callback' => function() {
				return current_user_can( 'edit_users' );
			},
			'args' => array(
				'id' => array(
					'validate_callback' => function( $param ) {
						return is_numeric( $param );
					},
				),
			),
		));

		register_rest_route( 'xophz/v1', '/matrix/departments', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_departments' ),
				'permission_callback' => function() {
					return current_user_can( 'list_users' );
				},
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_department' ),
				'permission_callback' => function() {
					return current_user_can( 'edit_users' );
				},
			),
		));

		register_rest_route( 'xophz/v1', '/matrix/departments/(?P<id>\d+)', array(
			array(
				'methods'             => 'PUT',
				'callback'            => array( $this, 'update_department' ),
				'permission_callback' => function() {
					return current_user_can( 'edit_users' );
				},
			),
			array(
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'delete_department' ),
				'permission_callback' => function() {
					return current_user_can( 'edit_users' );
				},
			),
		));
	}

	public function get_users( $request ) {
		$users = get_users( array( 'number' => 200, 'orderby' => 'display_name' ) );
		$result = array();

		foreach ( $users as $user ) {
			$departments = wp_get_object_terms( $user->ID, 'compass_department' );
			$department  = ! empty( $departments ) && ! is_wp_error( $departments ) ? $departments[0] : null;

			$dept_data = null;
			if ( $department ) {
				$dept_data = array(
					'id'    => $department->term_id,
					'name'  => $department->name,
					'slug'  => $department->slug,
					'icon'  => get_term_meta( $department->term_id, 'compass_dept_icon', true ) ?: 'fad fa-building',
					'color' => get_term_meta( $department->term_id, 'compass_dept_color', true ) ?: '#62c9ff',
				);
			}

			$reports_to = get_user_meta( $user->ID, 'compass_reports_to', true );
			$job_title  = get_user_meta( $user->ID, 'compass_job_title', true );

			// Magic Boomerang "Wind" (UTM/Referer Data)
			$wind_data = array();
			$wind_fields = array( 'source', 'medium', 'campaign', 'term', 'content', 'referer', 'first_visit' );
			foreach ( $wind_fields as $field ) {
				$val = get_user_meta( $user->ID, '_gale_wind_' . $field, true );
				if ( ! empty( $val ) ) {
					$wind_data[ $field ] = $val;
				}
			}

			$result[] = array(
				'id'         => $user->ID,
				'name'       => $user->display_name,
				'slug'       => $user->user_login,
				'email'      => $user->user_email,
				'avatar'     => get_avatar_url( $user->ID, array( 'size' => 96 ) ),
				'roles'      => $user->roles,
				'department' => $dept_data,
				'reports_to' => $reports_to ? absint( $reports_to ) : null,
				'job_title'  => $job_title ?: '',
				'wind'       => empty( $wind_data ) ? null : $wind_data,
			);
		}

		return rest_ensure_response( $result );
	}

	public function update_user( $request ) {
		$user_id = absint( $request->get_param( 'id' ) );
		$user    = get_userdata( $user_id );

		if ( ! $user ) {
			return new WP_Error( 'user_not_found', 'User not found.', array( 'status' => 404 ) );
		}

		$params = $request->get_json_params();

		if ( isset( $params['department_id'] ) ) {
			$dept_id = absint( $params['department_id'] );
			if ( $dept_id === 0 ) {
				wp_delete_object_term_relationships( $user_id, 'compass_department' );
			} else {
				$term = get_term( $dept_id, 'compass_department' );
				if ( $term && ! is_wp_error( $term ) ) {
					wp_set_object_terms( $user_id, array( $dept_id ), 'compass_department', false );
				}
			}
		}

		if ( isset( $params['reports_to'] ) ) {
			$manager_id = absint( $params['reports_to'] );
			if ( $manager_id === 0 || $manager_id === $user_id ) {
				delete_user_meta( $user_id, 'compass_reports_to' );
			} else {
				update_user_meta( $user_id, 'compass_reports_to', $manager_id );
			}
		}

		if ( isset( $params['job_title'] ) ) {
			update_user_meta( $user_id, 'compass_job_title', sanitize_text_field( $params['job_title'] ) );
		}

		return rest_ensure_response( array( 'success' => true, 'user_id' => $user_id ) );
	}

	public function reset_password( $request ) {
		$user_id = absint( $request->get_param( 'id' ) );
		$user    = get_userdata( $user_id );

		if ( ! $user ) {
			return new WP_Error( 'user_not_found', 'User not found.', array( 'status' => 404 ) );
		}

		$key = get_password_reset_key( $user );
		if ( is_wp_error( $key ) ) {
			return new WP_Error( 'reset_failed', 'Could not generate reset key.', array( 'status' => 500 ) );
		}

		$message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
		$message .= sprintf( __( 'Site Name: %s' ), wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s' ), $user->user_login ) . "\r\n\r\n";
		$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
		$message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
		$message .= network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ) . "\r\n";

		$title = sprintf( __( '[%s] Password Reset' ), wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) );

		$sent = wp_mail( $user->user_email, $title, $message );

		if ( ! $sent ) {
			return new WP_Error( 'email_failed', 'Could not send reset email.', array( 'status' => 500 ) );
		}

		return rest_ensure_response( array( 'success' => true ) );
	}

	public function get_departments( $request ) {
		$terms = get_terms( array(
			'taxonomy'   => 'compass_department',
			'hide_empty' => false,
			'orderby'    => 'name',
		));

		if ( is_wp_error( $terms ) ) {
			return rest_ensure_response( array() );
		}

		$result = array();
		foreach ( $terms as $term ) {
			$result[] = array(
				'id'          => $term->term_id,
				'name'        => $term->name,
				'slug'        => $term->slug,
				'description' => $term->description,
				'icon'        => get_term_meta( $term->term_id, 'compass_dept_icon', true ) ?: 'fad fa-building',
				'color'       => get_term_meta( $term->term_id, 'compass_dept_color', true ) ?: '#62c9ff',
				'parent'      => $term->parent,
				'count'       => $term->count,
			);
		}

		return rest_ensure_response( $result );
	}

	public function create_department( $request ) {
		$params = $request->get_json_params();
		$name   = sanitize_text_field( $params['name'] ?? '' );

		if ( empty( $name ) ) {
			return new WP_Error( 'missing_name', 'Department name is required.', array( 'status' => 400 ) );
		}

		$inserted = wp_insert_term( $name, 'compass_department', array(
			'description' => sanitize_text_field( $params['description'] ?? '' ),
			'parent'      => isset( $params['parent'] ) ? absint( $params['parent'] ) : 0,
		));

		if ( is_wp_error( $inserted ) ) {
			return new WP_Error( 'create_failed', $inserted->get_error_message(), array( 'status' => 400 ) );
		}

		$term_id = $inserted['term_id'];

		if ( ! empty( $params['icon'] ) ) {
			update_term_meta( $term_id, 'compass_dept_icon', sanitize_text_field( $params['icon'] ) );
		}

		if ( ! empty( $params['color'] ) ) {
			update_term_meta( $term_id, 'compass_dept_color', sanitize_hex_color( $params['color'] ) );
		}

		$term = get_term( $term_id, 'compass_department' );

		return rest_ensure_response( array(
			'id'          => $term->term_id,
			'name'        => $term->name,
			'slug'        => $term->slug,
			'description' => $term->description,
			'icon'        => get_term_meta( $term_id, 'compass_dept_icon', true ) ?: 'fad fa-building',
			'color'       => get_term_meta( $term_id, 'compass_dept_color', true ) ?: '#62c9ff',
			'parent'      => $term->parent,
			'count'       => 0,
		));
	}

	public function update_department( $request ) {
		$term_id = absint( $request->get_param( 'id' ) );
		$params  = $request->get_json_params();

		$term = get_term( $term_id, 'compass_department' );
		if ( ! $term || is_wp_error( $term ) ) {
			return new WP_Error( 'not_found', 'Department not found.', array( 'status' => 404 ) );
		}

		$update_args = array();
		if ( isset( $params['name'] ) ) {
			$update_args['name'] = sanitize_text_field( $params['name'] );
		}
		if ( isset( $params['description'] ) ) {
			$update_args['description'] = sanitize_text_field( $params['description'] );
		}
		if ( isset( $params['parent'] ) ) {
			$update_args['parent'] = absint( $params['parent'] );
		}

		if ( ! empty( $update_args ) ) {
			$result = wp_update_term( $term_id, 'compass_department', $update_args );
			if ( is_wp_error( $result ) ) {
				return new WP_Error( 'update_failed', $result->get_error_message(), array( 'status' => 400 ) );
			}
		}

		if ( isset( $params['icon'] ) ) {
			update_term_meta( $term_id, 'compass_dept_icon', sanitize_text_field( $params['icon'] ) );
		}
		if ( isset( $params['color'] ) ) {
			update_term_meta( $term_id, 'compass_dept_color', sanitize_hex_color( $params['color'] ) );
		}

		$term = get_term( $term_id, 'compass_department' );

		return rest_ensure_response( array(
			'id'          => $term->term_id,
			'name'        => $term->name,
			'slug'        => $term->slug,
			'description' => $term->description,
			'icon'        => get_term_meta( $term_id, 'compass_dept_icon', true ) ?: 'fad fa-building',
			'color'       => get_term_meta( $term_id, 'compass_dept_color', true ) ?: '#62c9ff',
			'parent'      => $term->parent,
			'count'       => $term->count,
		));
	}

	public function delete_department( $request ) {
		$term_id = absint( $request->get_param( 'id' ) );

		$term = get_term( $term_id, 'compass_department' );
		if ( ! $term || is_wp_error( $term ) ) {
			return new WP_Error( 'not_found', 'Department not found.', array( 'status' => 404 ) );
		}

		$deleted = wp_delete_term( $term_id, 'compass_department' );

		if ( is_wp_error( $deleted ) ) {
			return new WP_Error( 'delete_failed', $deleted->get_error_message(), array( 'status' => 400 ) );
		}

		return rest_ensure_response( array( 'success' => true, 'deleted_id' => $term_id ) );
	}
}
