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
		if ( class_exists( 'Xophz_Compass_Bulletin_Board_CPT' ) ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'register_post_type_and_taxonomy' ) );
		add_action( 'init', array( __CLASS__, 'register_term_meta' ) );
		add_action( 'init', array( __CLASS__, 'seed_default_boards' ), 20 );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_fields' ) );
	}

	/**
	 * Register Post Type & Taxonomy
	 */
	public static function register_post_type_and_taxonomy() {
		// 1. Register Taxonomy: cafeteria_board
		$tax_labels = array(
			'name'              => _x( 'Boards', 'taxonomy general name', 'xophz-compass' ),
			'singular_name'     => _x( 'Board', 'taxonomy singular name', 'xophz-compass' ),
			'search_items'      => __( 'Search Boards', 'xophz-compass' ),
			'all_items'         => __( 'All Boards', 'xophz-compass' ),
			'parent_item'       => __( 'Parent Board', 'xophz-compass' ),
			'parent_item_colon' => __( 'Parent Board:', 'xophz-compass' ),
			'edit_item'         => __( 'Edit Board', 'xophz-compass' ),
			'update_item'       => __( 'Update Board', 'xophz-compass' ),
			'add_new_item'      => __( 'Add New Board', 'xophz-compass' ),
			'new_item_name'     => __( 'New Board Name', 'xophz-compass' ),
			'menu_name'         => __( 'Boards', 'xophz-compass' ),
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
			'name'               => _x( 'Topics', 'post type general name', 'xophz-compass' ),
			'singular_name'      => _x( 'Topic', 'post type singular name', 'xophz-compass' ),
			'menu_name'          => _x( 'Topics', 'admin menu', 'xophz-compass' ),
			'name_admin_bar'     => _x( 'Topic', 'add new on admin bar', 'xophz-compass' ),
			'add_new'            => _x( 'Add New', 'topic', 'xophz-compass' ),
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

		// Ensure Suggestion Box boards
		$sug_slug = 'suggestion-box';
		$sug_term = get_term_by( 'slug', $sug_slug, self::TAXONOMY );
		if ( ! $sug_term ) {
			$inserted_sug = wp_insert_term( 'Suggestion Box', self::TAXONOMY, array(
				'slug'        => $sug_slug,
				'description' => 'A place for ideas, feedback, and feature requests.',
			) );

			if ( ! is_wp_error( $inserted_sug ) && isset( $inserted_sug['term_id'] ) ) {
				$sug_id = $inserted_sug['term_id'];
				update_term_meta( $sug_id, 'board_icon', 'fal fa-box-ballot' );
				update_term_meta( $sug_id, 'board_order', '10' );
			} else {
				return;
			}
		} else {
			$sug_id = $sug_term->term_id;
		}

		$default_suggestions = array(
			array(
				'name'        => 'Ideas, Suggestions, Nuances',
				'slug'        => 'ideas-suggestions',
				'description' => 'Share your ideas and suggestions for the platform.',
				'icon'        => 'fal fa-lightbulb-on',
				'order'       => '1',
			),
			array(
				'name'        => 'Comments, Feedback, Shouts',
				'slug'        => 'comments-feedback',
				'description' => 'General comments and feedback about the experience.',
				'icon'        => 'fal fa-comment-alt-dots',
				'order'       => '2',
			),
			array(
				'name'        => 'Feature Requests',
				'slug'        => 'feature-requests',
				'description' => 'Request new features and capabilities.',
				'icon'        => 'fal fa-flask-potion',
				'order'       => '3',
			),
		);

		foreach ( $default_suggestions as $child ) {
			$existing_child = get_term_by( 'slug', $child['slug'], self::TAXONOMY );
			if ( ! $existing_child ) {
				$inst = wp_insert_term( $child['name'], self::TAXONOMY, array(
					'slug'        => $child['slug'],
					'description' => $child['description'],
					'parent'      => $sug_id,
				) );

				if ( ! is_wp_error( $inst ) && isset( $inst['term_id'] ) ) {
					update_term_meta( $inst['term_id'], 'board_icon', $child['icon'] );
					update_term_meta( $inst['term_id'], 'board_order', $child['order'] );
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
	 * Register custom REST fields for stats
	 */
	public static function register_rest_fields() {
		register_rest_field( self::TAXONOMY, 'stats', array(
			'get_callback' => array( __CLASS__, 'get_board_stats' ),
			'schema'       => null,
		) );

		register_rest_field( self::POST_TYPE, 'stats', array(
			'get_callback' => array( __CLASS__, 'get_topic_stats' ),
			'schema'       => null,
		) );
	}

	/**
	 * Compute board statistics for REST responses
	 */
	public static function get_board_stats( $term, $field_name, $request ) {
		$term_id = is_array( $term ) ? $term['id'] : $term->term_id;
		$term_count = is_array( $term ) ? $term['count'] : $term->count;

		$stats = array(
			'topics'        => $term_count,
			'replies'       => 0,
			'last_activity' => null,
		);

		$topics = get_posts( array(
			'post_type'      => self::POST_TYPE,
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'tax_query'      => array(
				array(
					'taxonomy' => self::TAXONOMY,
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			),
		) );

		$total_replies = 0;
		foreach ( $topics as $topic_id ) {
			$comments_count = wp_count_comments( $topic_id );
			$total_replies += $comments_count->total_comments;
		}
		$stats['replies'] = $total_replies;

		$last_activity = null;
		$latest_ts = 0;

		$latest_topic = get_posts( array(
			'post_type'      => self::POST_TYPE,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'tax_query'      => array(
				array(
					'taxonomy' => self::TAXONOMY,
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			),
		) );

		if ( ! empty( $latest_topic ) ) {
			$t = $latest_topic[0];
			$latest_ts = strtotime( $t->post_date );
			$author_id = $t->post_author;
			$last_activity = array(
				'title'     => $t->post_title,
				'timestamp' => $t->post_date,
				'author'    => get_the_author_meta( 'display_name', $author_id ) ?: 'Unknown',
				'avatar'    => get_avatar_url( $author_id, array( 'size' => 48 ) ),
			);
		}

		if ( ! empty( $topics ) ) {
			$latest_comment = get_comments( array(
				'post_id__in' => $topics,
				'number'      => 1,
				'orderby'     => 'comment_date',
				'order'       => 'DESC',
				'status'      => 'all',
			) );

			if ( ! empty( $latest_comment ) ) {
				$c = $latest_comment[0];
				$c_ts = strtotime( $c->comment_date );
				if ( $c_ts > $latest_ts ) {
					$p = get_post( $c->comment_post_ID );
					$last_activity = array(
						'title'     => 'Re: ' . ( $p ? $p->post_title : '' ),
						'timestamp' => $c->comment_date,
						'author'    => $c->comment_author,
						'avatar'    => get_avatar_url( $c->comment_author_email, array( 'size' => 48 ) ),
					);
				}
			}
		}

		$stats['last_activity'] = $last_activity;

		if ( ! empty( $topics ) && $stats['topics'] == 0 ) {
			$stats['topics'] = count( $topics );
		}

		return $stats;
	}

	/**
	 * Compute topic statistics for REST responses
	 */
	public static function get_topic_stats( $post, $field_name, $request ) {
		$topic_id = is_array( $post ) ? $post['id'] : $post->ID;
		$stats = array(
			'replies'       => 0,
			'views'         => 0,
			'last_activity' => null,
		);

		$comments_count = wp_count_comments( $topic_id );
		$stats['replies'] = $comments_count->total_comments;

		$latest_comment = get_comments( array(
			'post_id' => $topic_id,
			'number'  => 1,
			'orderby' => 'comment_date',
			'order'   => 'DESC',
			'status'  => 'all',
		) );

		if ( ! empty( $latest_comment ) ) {
			$c = $latest_comment[0];
			$stats['last_activity'] = array(
				'timestamp' => $c->comment_date,
				'author'    => $c->comment_author,
				'avatar'    => get_avatar_url( $c->comment_author_email, array( 'size' => 48 ) ),
			);
		}

		return $stats;
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
