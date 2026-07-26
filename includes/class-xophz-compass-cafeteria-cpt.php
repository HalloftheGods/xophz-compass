<?php

/**
 * Cafeteria CPT & Board Taxonomy Backend
 *
 * Provides custom post type `cafeteria_topic` and taxonomy `cafeteria_board`
 * to power Noosphere Observer interface and Cafeteria Food forum.
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Cafeteria_CPT {

	const POST_TYPE = 'cafeteria_topic';
	const TAXONOMY  = 'cafeteria_board';

	/**
	 * Initialize hooks
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_type_and_taxonomy' ) );
		add_action( 'init', array( __CLASS__, 'register_term_meta' ) );
		add_action( 'init', array( __CLASS__, 'seed_default_boards' ), 20 );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ) );
	}

	/**
	 * Register Post Type & Taxonomy
	 */
	public static function register_post_type_and_taxonomy() {
		// 1. Register Taxonomy: cafeteria_board
		$tax_labels = array(
			'name'              => _x( 'Cafeteria Boards', 'taxonomy general name', 'xophz-compass' ),
			'singular_name'     => _x( 'Cafeteria Board', 'taxonomy singular name', 'xophz-compass' ),
			'search_items'      => __( 'Search Boards', 'xophz-compass' ),
			'all_items'         => __( 'All Boards', 'xophz-compass' ),
			'parent_item'       => __( 'Parent Board', 'xophz-compass' ),
			'parent_item_colon' => __( 'Parent Board:', 'xophz-compass' ),
			'edit_item'         => __( 'Edit Board', 'xophz-compass' ),
			'update_item'       => __( 'Update Board', 'xophz-compass' ),
			'add_new_item'      => __( 'Add New Board', 'xophz-compass' ),
			'new_item_name'     => __( 'New Board Name', 'xophz-compass' ),
			'menu_name'         => __( 'Cafeteria Boards', 'xophz-compass' ),
		);

		$tax_args = array(
			'hierarchical'      => true,
			'labels'            => $tax_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'rest_base'         => 'cafeteria_board',
			'rewrite'           => array( 'slug' => 'cafeteria-board' ),
		);

		register_taxonomy( self::TAXONOMY, array( self::POST_TYPE ), $tax_args );

		// 2. Register Post Type: cafeteria_topic
		$cpt_labels = array(
			'name'               => _x( 'Cafeteria Topics', 'post type general name', 'xophz-compass' ),
			'singular_name'      => _x( 'Cafeteria Topic', 'post type singular name', 'xophz-compass' ),
			'menu_name'          => _x( 'Cafeteria Topics', 'admin menu', 'xophz-compass' ),
			'name_admin_bar'     => _x( 'Cafeteria Topic', 'add new on admin bar', 'xophz-compass' ),
			'add_new'            => _x( 'Add New Topic', 'topic', 'xophz-compass' ),
			'add_new_item'       => __( 'Add New Topic', 'xophz-compass' ),
			'new_item'           => __( 'New Topic', 'xophz-compass' ),
			'edit_item'          => __( 'Edit Topic', 'xophz-compass' ),
			'view_item'          => __( 'View Topic', 'xophz-compass' ),
			'all_items'          => __( 'All Topics', 'xophz-compass' ),
			'search_items'       => __( 'Search Topics', 'xophz-compass' ),
			'not_found'          => __( 'No topics found.', 'xophz-compass' ),
			'not_found_in_trash' => __( 'No topics found in Trash.', 'xophz-compass' ),
		);

		$cpt_args = array(
			'labels'             => $cpt_labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'cafeteria-topic' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 25,
			'menu_icon'          => 'dashicons-format-chat',
			'show_in_rest'       => true,
			'rest_base'          => 'cafeteria_topic',
			'supports'           => array( 'title', 'editor', 'author', 'comments', 'custom-fields', 'revisions' ),
			'taxonomies'         => array( self::TAXONOMY ),
		);

		register_post_type( self::POST_TYPE, $cpt_args );
	}

	/**
	 * Register term meta for board_icon & board_order
	 */
	public static function register_term_meta() {
		register_term_meta( self::TAXONOMY, 'board_icon', array(
			'type'          => 'string',
			'single'        => true,
			'show_in_rest'  => true,
			'description'   => 'Icon string for FontAwesome',
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			}
		) );

		register_term_meta( self::TAXONOMY, 'board_order', array(
			'type'          => 'string',
			'single'        => true,
			'show_in_rest'  => true,
			'description'   => 'Display ordering number',
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			}
		) );
	}

	/**
	 * Seed Default Parent (noo) & Default Spheres if missing
	 */
	public static function seed_default_boards() {
		if ( ! taxonomy_exists( self::TAXONOMY ) ) {
			return;
		}

		// Ensure top-level parent term: 'noo' (Noosphere)
		$parent_term = get_term_by( 'slug', 'noo', self::TAXONOMY );
		if ( ! $parent_term ) {
			$inserted = wp_insert_term( 'Noosphere', self::TAXONOMY, array(
				'slug'        => 'noo',
				'description' => 'Noosphere Observer Spheres',
			) );

			if ( ! is_wp_error( $inserted ) && isset( $inserted['term_id'] ) ) {
				$parent_id = $inserted['term_id'];
				update_term_meta( $parent_id, 'board_icon', 'fal fa-galaxy' );
				update_term_meta( $parent_id, 'board_order', '0' );
			} else {
				return;
			}
		} else {
			$parent_id = $parent_term->term_id;
		}

		// Default spheres under 'noo'
		$default_spheres = array(
			array(
				'name'        => 'u/noo/COMPASS',
				'slug'        => 'noo-compass',
				'description' => 'Navigational systems and compass updates',
				'icon'        => 'fal fa-compass',
				'order'       => '1',
			),
			array(
				'name'        => 'u/noo/Aesthetics',
				'slug'        => 'noo-aesthetics',
				'description' => 'Design systems, neon, glassmorphism, visual theory',
				'icon'        => 'fal fa-palette',
				'order'       => '2',
			),
			array(
				'name'        => 'u/noo/General',
				'slug'        => 'noo-general',
				'description' => 'General chatter across the federation',
				'icon'        => 'fal fa-comments',
				'order'       => '3',
			),
		);

		foreach ( $default_spheres as $sphere ) {
			$existing = get_term_by( 'slug', $sphere['slug'], self::TAXONOMY );
			if ( ! $existing ) {
				$inst = wp_insert_term( $sphere['name'], self::TAXONOMY, array(
					'slug'        => $sphere['slug'],
					'description' => $sphere['description'],
					'parent'      => $parent_id,
				) );

				if ( ! is_wp_error( $inst ) && isset( $inst['term_id'] ) ) {
					update_term_meta( $inst['term_id'], 'board_icon', $sphere['icon'] );
					update_term_meta( $inst['term_id'], 'board_order', $sphere['order'] );
				}
			}
		}
	}

	/**
	 * Register REST routes for sphere subscriptions
	 */
	public static function register_rest_routes() {
		register_rest_route( 'xophz-compass/v1', '/subscriptions', array(
			array(
				'methods'             => 'GET',
				'callback'            => array( __CLASS__, 'get_user_subscriptions' ),
				'permission_callback' => '__return_true',
			),
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'update_user_subscriptions' ),
				'permission_callback' => function() {
					return is_user_logged_in();
				},
			)
		) );
	}

	/**
	 * GET user subscriptions
	 */
	public static function get_user_subscriptions( WP_REST_Request $request ) {
		$user_id = get_current_user_id();
		if ( ! $user_id ) {
			return rest_ensure_response( array( 'subscribed_boards' => array() ) );
		}

		$subs = get_user_meta( $user_id, '_xophz_subscribed_spheres', true );
		if ( ! is_array( $subs ) ) {
			$subs = array();
		}

		return rest_ensure_response( array( 'subscribed_boards' => array_map( 'intval', $subs ) ) );
	}

	/**
	 * POST update user subscriptions
	 */
	public static function update_user_subscriptions( WP_REST_Request $request ) {
		$user_id = get_current_user_id();
		if ( ! $user_id ) {
			return new WP_Error( 'unauthorized', 'User must be logged in.', array( 'status' => 401 ) );
		}

		$boards = $request->get_param( 'subscribed_boards' );
		if ( ! is_array( $boards ) ) {
			$boards = array();
		}

		$clean_boards = array_map( 'intval', $boards );
		update_user_meta( $user_id, '_xophz_subscribed_spheres', $clean_boards );

		return rest_ensure_response( array(
			'success'           => true,
			'subscribed_boards' => $clean_boards,
		) );
	}
}
