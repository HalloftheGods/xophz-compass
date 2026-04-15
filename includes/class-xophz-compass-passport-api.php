<?php

class Xophz_Compass_Passport_API {

	public function register_routes() {
		register_rest_route( 'xophz/v1', '/passport', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_passport' ),
				'permission_callback' => function() {
					return is_user_logged_in();
				}
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_passport' ),
				'permission_callback' => function() {
					return is_user_logged_in();
				}
			),
		));

		register_rest_route( 'xophz/v1', '/passport/public/(?P<user_id>\d+)', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_public_passport' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'user_id' => array(
						'validate_callback' => function( $param ) {
							return is_numeric( $param );
						}
					)
				)
			)
		));
	}

	public function get_passport( $request ) {
		$user_id = get_current_user_id();
		$user    = get_userdata( $user_id );

		$profile_cid = get_user_meta( $user_id, 'youmeos_profile_cid', true );
		$wallet_addr = get_user_meta( $user_id, 'youmeos_wallet_address', true );
		$ens_name    = get_user_meta( $user_id, 'youmeos_ens_name', true );
		$mmo_json    = get_user_meta( $user_id, 'youmeos_mmo', true );

		$mmo = $mmo_json ? json_decode( $mmo_json, true ) : array(
			'xp'     => array( 'total' => 0, 'level' => 1, 'title' => 'Newcomer' ),
			'badges' => array(),
		);

		return rest_ensure_response( array(
			'youmeos_id'     => 'usr_' . $user_id,
			'username'       => '@' . $user->user_login,
			'display_name'   => $user->display_name,
			'avatar_cid'     => '',
			'home_planet'    => home_url(),
			'mmo'            => $mmo,
			'config_cid'     => '',
			'profile_cid'    => $profile_cid ?: '',
			'wallet_address' => $wallet_addr ?: '',
			'ens_name'       => $ens_name ?: '',
		));
	}

	public function update_passport( $request ) {
		$user_id = get_current_user_id();
		$params  = $request->get_json_params();

		if ( isset( $params['profile_cid'] ) ) {
			update_user_meta( $user_id, 'youmeos_profile_cid', sanitize_text_field( $params['profile_cid'] ) );
		}

		if ( isset( $params['wallet_address'] ) ) {
			update_user_meta( $user_id, 'youmeos_wallet_address', sanitize_text_field( $params['wallet_address'] ) );
		}

		if ( isset( $params['ens_name'] ) ) {
			update_user_meta( $user_id, 'youmeos_ens_name', sanitize_text_field( $params['ens_name'] ) );
		}

		if ( isset( $params['mmo'] ) ) {
			update_user_meta( $user_id, 'youmeos_mmo', wp_json_encode( $params['mmo'] ) );
		}

		return rest_ensure_response( array( 'success' => true ) );
	}

	public function get_public_passport( $request ) {
		$user_id = absint( $request->get_param( 'user_id' ) );
		$user    = get_userdata( $user_id );

		if ( ! $user ) {
			return new WP_Error( 'rest_user_not_found', 'User not found.', array( 'status' => 404 ) );
		}

		$profile_cid = get_user_meta( $user_id, 'youmeos_profile_cid', true );
		$mmo_json    = get_user_meta( $user_id, 'youmeos_mmo', true );
		$mmo         = $mmo_json ? json_decode( $mmo_json, true ) : array(
			'xp'     => array( 'total' => 0, 'level' => 1, 'title' => 'Newcomer' ),
			'badges' => array(),
		);

		return rest_ensure_response( array(
			'youmeos_id'   => 'usr_' . $user_id,
			'username'     => '@' . $user->user_login,
			'display_name' => $user->display_name,
			'home_planet'  => home_url(),
			'mmo'          => $mmo,
			'profile_cid'  => $profile_cid ?: '',
		));
	}
}
