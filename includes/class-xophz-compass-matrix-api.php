<?php

require_once __DIR__ . '/class-xophz-compass-matrix-vacancies.php';

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
		( new Xophz_Compass_Matrix_Vacancies() )->register_routes();

		register_rest_route( 'xophz/v1', '/matrix/users', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_users' ),
				'permission_callback' => function() {
					return current_user_can( 'list_users' );
				},
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_user' ),
				'permission_callback' => function() {
					return current_user_can( 'create_users' );
				},
			),
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

		register_rest_route( 'xophz/v1', '/matrix/contacts/groups', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_contact_groups' ),
			'permission_callback' => function() {
				return current_user_can( 'manage_options' );
			},
		));

		register_rest_route( 'xophz/v1', '/matrix/contacts', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_group_contacts' ),
			'permission_callback' => function() {
				return current_user_can( 'manage_options' );
			},
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

			// Name preference: first + last name, then display name, then username.
			$first     = get_user_meta( $user->ID, 'first_name', true );
			$last      = get_user_meta( $user->ID, 'last_name', true );
			$full_name = trim( $first . ' ' . $last );
			$name      = '' !== $full_name ? $full_name : ( '' !== trim( $user->display_name ) ? $user->display_name : $user->user_login );

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
				'name'         => $name,
				'first_name'   => $first,
				'last_name'    => $last,
				'nickname'     => get_user_meta( $user->ID, 'nickname', true ),
				'display_name' => $user->display_name,
				'url'          => $user->user_url,
				'description'  => get_user_meta( $user->ID, 'description', true ),
				'slug'       => $user->user_login,
				'email'      => $user->user_email,
				'avatar'     => get_avatar_url( $user->ID, array( 'size' => 96 ) ),
				'roles'      => $user->roles,
				'department' => $dept_data,
				'reports_to' => $reports_to ? absint( $reports_to ) : null,
				'job_title'  => $job_title ?: '',
				'dotted_to'  => array_values( array_map( 'absint', (array) get_user_meta( $user->ID, 'compass_dotted_to', true ) ) ),
				'wind'       => empty( $wind_data ) ? null : $wind_data,
			);
		}

		return rest_ensure_response( $result );
	}

	public function create_user( $request ) {
		$params = $request->get_json_params();

		$email = sanitize_email( $params['email'] ?? '' );
		if ( ! is_email( $email ) ) {
			return new WP_Error( 'invalid_email', 'A valid email address is required.', array( 'status' => 400 ) );
		}
		if ( email_exists( $email ) ) {
			return new WP_Error( 'email_exists', 'A user with that email already exists.', array( 'status' => 409 ) );
		}

		$login = sanitize_user( $params['username'] ?? '', true );
		if ( '' === $login ) {
			$login = sanitize_user( strstr( $email, '@', true ), true );
		}
		if ( '' === $login || username_exists( $login ) ) {
			return new WP_Error( 'invalid_username', 'That username is empty or already taken.', array( 'status' => 409 ) );
		}

		$role  = sanitize_key( $params['role'] ?? get_option( 'default_role' ) );
		$roles = get_editable_roles();
		if ( ! isset( $roles[ $role ] ) ) {
			return new WP_Error( 'invalid_role', 'You cannot assign that role.', array( 'status' => 403 ) );
		}

		$notify   = ! empty( $params['send_notification'] );
		$password = (string) ( $params['password'] ?? '' );
		if ( ! $notify && strlen( $password ) < 8 ) {
			return new WP_Error( 'weak_password', 'Set a password of at least 8 characters, or email them a link instead.', array( 'status' => 400 ) );
		}
		if ( $notify || '' === $password ) {
			$password = wp_generate_password( 24 );
		}

		$first = sanitize_text_field( $params['first_name'] ?? '' );
		$last  = sanitize_text_field( $params['last_name'] ?? '' );
		$name  = trim( $first . ' ' . $last );
		if ( '' === $name ) {
			$name  = sanitize_text_field( $params['name'] ?? '' );
			$parts = explode( ' ', $name, 2 );
			$first = $parts[0];
			$last  = $parts[1] ?? '';
		}

		$user_id = wp_insert_user( array(
			'user_login'   => $login,
			'user_email'   => $email,
			'user_pass'    => $password,
			'display_name' => '' !== $name ? $name : $login,
			'first_name'   => $first,
			'last_name'    => $last,
			'role'         => $role,
		) );

		if ( is_wp_error( $user_id ) ) {
			return new WP_Error( $user_id->get_error_code(), $user_id->get_error_message(), array( 'status' => 400 ) );
		}

		// Reuse the edit path for title, department and manager.
		$request->set_param( 'id', $user_id );
		$this->update_user( $request );

		if ( $notify ) {
			wp_new_user_notification( $user_id, null, 'user' );
		}

		return rest_ensure_response( array( 'success' => true, 'user_id' => $user_id ) );
	}

	public function update_user( $request ) {
		$user_id = absint( $request->get_param( 'id' ) );
		$user    = get_userdata( $user_id );

		if ( ! $user ) {
			return new WP_Error( 'user_not_found', 'User not found.', array( 'status' => 404 ) );
		}

		$params = $request->get_json_params();

		// Core profile fields, the same ones WordPress's own profile screen edits.
		$core = array( 'ID' => $user_id );
		foreach ( array( 'first_name', 'last_name', 'nickname', 'display_name' ) as $field ) {
			if ( isset( $params[ $field ] ) ) {
				$core[ $field ] = sanitize_text_field( $params[ $field ] );
			}
		}
		if ( isset( $params['description'] ) ) {
			$core['description'] = sanitize_textarea_field( $params['description'] );
		}
		if ( isset( $params['url'] ) ) {
			$core['user_url'] = esc_url_raw( $params['url'] );
		}
		if ( isset( $params['email'] ) ) {
			$email = sanitize_email( $params['email'] );
			if ( ! is_email( $email ) ) {
				return new WP_Error( 'invalid_email', 'A valid email address is required.', array( 'status' => 400 ) );
			}
			$owner = email_exists( $email );
			if ( $owner && (int) $owner !== $user_id ) {
				return new WP_Error( 'email_exists', 'Another user already has that email.', array( 'status' => 409 ) );
			}
			$core['user_email'] = $email;
		}
		if ( isset( $params['role'] ) && '' !== $params['role'] ) {
			$role = sanitize_key( $params['role'] );
			if ( ! isset( get_editable_roles()[ $role ] ) ) {
				return new WP_Error( 'invalid_role', 'You cannot assign that role.', array( 'status' => 403 ) );
			}
			if ( $user_id === get_current_user_id() && ! in_array( $role, $user->roles, true ) ) {
				return new WP_Error( 'own_role', 'You cannot change your own role here.', array( 'status' => 403 ) );
			}
			if ( ! in_array( $role, $user->roles, true ) ) {
				$core['role'] = $role;
			}
		}
		if ( count( $core ) > 1 ) {
			$saved = wp_update_user( $core );
			if ( is_wp_error( $saved ) ) {
				return new WP_Error( $saved->get_error_code(), $saved->get_error_message(), array( 'status' => 400 ) );
			}
		}

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
				// Refuse loops: the new manager must not already report (directly or not) to this user.
				$cursor = $manager_id;
				$guard  = 0;
				while ( $cursor && $guard++ < 100 ) {
					if ( $cursor === $user_id ) {
						return new WP_Error( 'reports_loop', 'That would make a reporting loop.', array( 'status' => 400 ) );
					}
					$cursor = absint( get_user_meta( $cursor, 'compass_reports_to', true ) );
				}
				update_user_meta( $user_id, 'compass_reports_to', $manager_id );
			}
		}

		if ( isset( $params['dotted_to'] ) ) {
			$dotted = array_values( array_unique( array_filter( array_map( 'absint', (array) $params['dotted_to'] ) ) ) );
			$dotted = array_values( array_diff( $dotted, array( $user_id ) ) );
			if ( empty( $dotted ) ) {
				delete_user_meta( $user_id, 'compass_dotted_to' );
			} else {
				update_user_meta( $user_id, 'compass_dotted_to', $dotted );
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

	/**
	 * Questbook keeps contacts in its own table. Returns the table name, or null when
	 * Questbook is not installed on this site.
	 */
	private function contacts_table() {
		global $wpdb;
		$table = $wpdb->prefix . 'xophz_qb_contacts';
		$found = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
		return $found === $table ? $table : null;
	}

	private function contact_group_column( $request ) {
		$by      = sanitize_key( $request->get_param( 'by' ) );
		$columns = array(
			'stage'   => 'lead_status',
			'source'  => 'source',
			'company' => 'company',
		);
		return $columns[ $by ] ?? 'lead_status';
	}

	public function get_contact_groups( $request ) {
		global $wpdb;
		$table = $this->contacts_table();
		if ( ! $table ) {
			return rest_ensure_response( array( 'available' => false, 'total' => 0, 'groups' => array() ) );
		}

		$column = $this->contact_group_column( $request );
		$rows   = $wpdb->get_results( "SELECT {$column} AS value, COUNT(id) AS total FROM {$table} GROUP BY {$column} ORDER BY total DESC" );

		$groups = array();
		$total  = 0;
		foreach ( $rows as $row ) {
			$total   += (int) $row->total;
			$groups[] = array(
				'value' => (string) $row->value,
				'label' => '' === (string) $row->value ? 'Not set' : (string) $row->value,
				'count' => (int) $row->total,
			);
		}

		return rest_ensure_response( array( 'available' => true, 'total' => $total, 'groups' => $groups ) );
	}

	public function get_group_contacts( $request ) {
		global $wpdb;
		$table = $this->contacts_table();
		if ( ! $table ) {
			return rest_ensure_response( array() );
		}

		$column = $this->contact_group_column( $request );
		$value  = (string) $request->get_param( 'value' );
		$limit  = min( 100, max( 1, absint( $request->get_param( 'per_page' ) ?: 50 ) ) );

		$rows = $wpdb->get_results( $wpdb->prepare(
			"SELECT id, wp_user_id, first_name, last_name, email, company, source, lead_status FROM {$table} WHERE {$column} = %s ORDER BY updated_at DESC LIMIT %d",
			$value,
			$limit
		) );

		$result = array();
		foreach ( $rows as $row ) {
			$name     = trim( $row->first_name . ' ' . $row->last_name );
			$result[] = array(
				'id'         => (int) $row->id,
				'wp_user_id' => (int) $row->wp_user_id,
				'name'       => '' !== $name ? $name : $row->email,
				'email'      => $row->email,
				'company'    => $row->company,
				'source'     => $row->source,
				'stage'      => $row->lead_status,
			);
		}

		return rest_ensure_response( $result );
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
				'head'        => absint( get_term_meta( $term->term_id, 'compass_dept_head', true ) ),
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
		if ( isset( $params['head'] ) ) {
			update_term_meta( $term_id, 'compass_dept_head', absint( $params['head'] ) );
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
		if ( isset( $params['head'] ) ) {
			update_term_meta( $term_id, 'compass_dept_head', absint( $params['head'] ) );
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
