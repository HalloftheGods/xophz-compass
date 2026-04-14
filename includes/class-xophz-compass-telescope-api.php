<?php

/**
 * Telescope REST API
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

class Xophz_Compass_Telescope_API {

	public function register_routes() {
		// Get community sites sorted by popularity
		register_rest_route( 'xophz/v1', '/telescope/sites', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_sites' ),
				'permission_callback' => '__return_true', 
			)
		) );

		// Navigate / Record Hit
		register_rest_route( 'xophz/v1', '/telescope/navigate', array(
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'record_navigation' ),
				'permission_callback' => '__return_true',
				'args'     => array(
					'url' => array(
						'required' => true,
						'type'     => 'string',
						'sanitize_callback' => 'esc_url_raw',
					),
				),
			)
		) );

		// Toggle User Favorite
		register_rest_route( 'xophz/v1', '/telescope/favorite', array(
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'toggle_favorite' ),
				'permission_callback' => function () {
					return is_user_logged_in();
				},
				'args'     => array(
					'site_id' => array(
						'required' => true,
						'type'     => 'integer',
					),
				),
			)
		) );

		// Check iframe compatibility
		register_rest_route( 'xophz/v1', '/telescope/check-frame', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'check_frameability' ),
				'permission_callback' => '__return_true',
				'args'     => array(
					'url' => array(
						'required' => true,
						'type'     => 'string',
						'sanitize_callback' => 'esc_url_raw',
					),
				),
			)
		) );
	}

	public function get_sites( $request ) {
		$args = array(
			'post_type'      => 'telescope_site',
			'posts_per_page' => 100,
			'meta_key'       => 'global_clicks',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
		);

		$query = new WP_Query( $args );
		$sites = array();

		$current_user_id = get_current_user_id();
		$user_favorites = $current_user_id ? get_user_meta( $current_user_id, '_telescope_favorites', true ) : array();
		if ( ! is_array( $user_favorites ) ) {
			$user_favorites = array();
		}

		foreach ( $query->posts as $post ) {
			$url = get_post_meta( $post->ID, 'url', true );
			$clicks = (int) get_post_meta( $post->ID, 'global_clicks', true );

			$sites[] = array(
				'id'          => $post->ID,
				'title'       => $post->post_title,
				'url'         => $url,
				'clicks'      => $clicks,
				'is_favorite' => in_array( $post->ID, $user_favorites ),
			);
		}

		return rest_ensure_response( $sites );
	}

	public function record_navigation( $request ) {
		$url = $request->get_param( 'url' );
		if ( empty( $url ) ) {
			return new WP_Error( 'invalid_url', 'Invalid URL provided', array( 'status' => 400 ) );
		}

		// Clean URL to base for title calculation if needed
		$parsed = wp_parse_url( $url );
		$domain = isset( $parsed['host'] ) ? $parsed['host'] : $url;

		// Check if site exists by custom meta
		$args = array(
			'post_type'  => 'telescope_site',
			'meta_query' => array(
				array(
					'key'     => 'url',
					'value'   => $url,
					'compare' => '='
				)
			),
			'posts_per_page' => 1
		);
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			$post_id = $query->posts[0]->ID;
			$clicks = (int) get_post_meta( $post_id, 'global_clicks', true );
			update_post_meta( $post_id, 'global_clicks', $clicks + 1 );
		} else {
			// Create it
			$post_id = wp_insert_post( array(
				'post_type'   => 'telescope_site',
				'post_title'  => sanitize_text_field( $domain ), // Initial title
				'post_status' => 'publish',
			) );

			if ( ! is_wp_error( $post_id ) ) {
				update_post_meta( $post_id, 'url', $url );
				update_post_meta( $post_id, 'global_clicks', 1 );
			}
		}

		return rest_ensure_response( array(
			'success' => true,
			'site_id' => $post_id
		) );
	}

	public function toggle_favorite( $request ) {
		$site_id = (int) $request->get_param( 'site_id' );
		$user_id = get_current_user_id();

		// Ensure it's a valid site
		if ( get_post_type( $site_id ) !== 'telescope_site' ) {
			return new WP_Error( 'invalid_site', 'Invalid site ID', array( 'status' => 400 ) );
		}

		$favorites = get_user_meta( $user_id, '_telescope_favorites', true );
		if ( ! is_array( $favorites ) ) {
			$favorites = array();
		}

		$is_favorite = false;
		$index = array_search( $site_id, $favorites );

		if ( $index !== false ) {
			// Remove it
			unset( $favorites[ $index ] );
		} else {
			// Add it
			$favorites[] = $site_id;
			$is_favorite = true;
		}

		// Reindex array
		$favorites = array_values( $favorites );
		update_user_meta( $user_id, '_telescope_favorites', $favorites );

		return rest_ensure_response( array(
			'success'     => true,
			'is_favorite' => $is_favorite,
			'site_id'     => $site_id
		) );
	}

	public function check_frameability( $request ) {
		$url = $request->get_param( 'url' );
		if ( empty( $url ) ) {
			return new WP_Error( 'invalid_url', 'Invalid URL provided', array( 'status' => 400 ) );
		}

		// Try HEAD first to save bandwidth
		$response = wp_remote_head( $url, array( 'timeout' => 5, 'redirection' => 3 ) );
		
		// If HEAD fails or gives an error (like 405 Method Not Allowed), fallback to GET
		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) >= 400 ) {
			$response = wp_remote_get( $url, array( 'timeout' => 5, 'redirection' => 3 ) );
		}

		if ( is_wp_error( $response ) ) {
			return rest_ensure_response( array(
				'url'          => $url,
				'is_frameable' => false,
				'reason'       => 'Host unreachable: ' . $response->get_error_message()
			) );
		}

		$headers = wp_remote_retrieve_headers( $response );
		
		$is_frameable = true;
		$reason = '';

		// 1. Check X-Frame-Options
		if ( isset( $headers['x-frame-options'] ) ) {
			$xfo = strtolower( is_array( $headers['x-frame-options'] ) ? $headers['x-frame-options'][0] : $headers['x-frame-options'] );
			if ( strpos( $xfo, 'deny' ) !== false || strpos( $xfo, 'sameorigin' ) !== false ) {
				$is_frameable = false;
				$reason = 'X-Frame-Options header blocks embedding';
			}
		}

		// 2. Check Content-Security-Policy frame-ancestors
		if ( $is_frameable && isset( $headers['content-security-policy'] ) ) {
			$csp = strtolower( is_array( $headers['content-security-policy'] ) ? $headers['content-security-policy'][0] : $headers['content-security-policy'] );
			if ( strpos( $csp, 'frame-ancestors' ) !== false && strpos( $csp, 'frame-ancestors *' ) === false ) {
				$is_frameable = false;
				$reason = 'Content-Security-Policy frame-ancestors restricts embedding';
			}
		}

		return rest_ensure_response( array(
			'url'          => $url,
			'is_frameable' => $is_frameable,
			'reason'       => $reason,
		) );
	}
}
