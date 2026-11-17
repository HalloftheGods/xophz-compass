<?php

/**
 * Per-user launcher order shared by the admin Accelerate page and the phone.
 *
 * Order is a list of plugin text domains in user meta. The phone only shows a
 * subset of plugins, so a write sends the visible ids and the server merges
 * them into the stored full order rather than replacing it.
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Plugin_Order_API {

	const META_KEY = '_compass_plugin_order';

	/**
	 * Register GET and POST /xophz-compass/v1/plugin-order.
	 */
	public function register_routes() {
		register_rest_route( 'xophz-compass/v1', '/plugin-order', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_order' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'save_order' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'order' => array(
						'required'          => true,
						'validate_callback' => array( __CLASS__, 'is_order_payload' ),
					),
				),
			),
		) );
	}

	/**
	 * Any signed-in user may keep their own order. A cookie request with a bad
	 * nonce counts as logged out, so reaching the callback proves the nonce.
	 */
	public function check_permissions() {
		return is_user_logged_in();
	}

	public function get_order() {
		return rest_ensure_response( array( 'order' => self::stored_order( get_current_user_id() ) ) );
	}

	public function save_order( WP_REST_Request $request ) {
		$user_id = get_current_user_id();
		$sent    = self::normalize_order( $request->get_param( 'order' ) );
		$merged  = self::merge_order( self::stored_order( $user_id ), $sent );
		update_user_meta( $user_id, self::META_KEY, $merged );
		return rest_ensure_response( array( 'order' => $merged ) );
	}

	public static function is_order_payload( $value ) {
		return null !== self::normalize_order( $value );
	}

	public static function stored_order( $user_id ) {
		$stored = get_user_meta( $user_id, self::META_KEY, true );
		return is_array( $stored ) ? array_values( $stored ) : array();
	}

	/**
	 * Accept an array or a JSON string; keep unique, non-empty strings.
	 * Returns null when the value is not a list at all.
	 */
	public static function normalize_order( $raw ) {
		if ( is_string( $raw ) ) {
			$raw = json_decode( $raw, true );
		}
		if ( ! is_array( $raw ) ) {
			return null;
		}
		$clean = array();
		foreach ( $raw as $id ) {
			if ( ! is_string( $id ) ) {
				continue;
			}
			$id = trim( $id );
			if ( '' !== $id ) {
				$clean[] = $id;
			}
		}
		return array_values( array_unique( $clean ) );
	}

	/**
	 * Sent ids take the slots they already hold in the stored order, in the
	 * sent order. Stored ids that were not sent stay where they are. Sent ids
	 * that were never stored are appended.
	 */
	public static function merge_order( array $stored, array $sent ) {
		$sent_lookup   = array_flip( $sent );
		$stored_lookup = array_flip( $stored );
		$queue         = array_values( array_filter( $sent, function ( $id ) use ( $stored_lookup ) {
			return isset( $stored_lookup[ $id ] );
		} ) );

		$merged = array();
		foreach ( $stored as $id ) {
			$merged[] = isset( $sent_lookup[ $id ] ) ? array_shift( $queue ) : $id;
		}
		foreach ( $sent as $id ) {
			if ( ! isset( $stored_lookup[ $id ] ) ) {
				$merged[] = $id;
			}
		}
		return $merged;
	}
}
