<?php

/**
 * Telescope Bookmarks CPT
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

class Xophz_Compass_Telescope_CPT {

	const POST_TYPE = 'telescope_site';

	/**
	 * Register the Custom Post Type
	 */
	public static function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Telescope Sites', 'Post Type General Name', 'xophz-compass' ),
			'singular_name'         => _x( 'Telescope Site', 'Post Type Singular Name', 'xophz-compass' ),
			'menu_name'             => __( 'Telescope Sites', 'xophz-compass' ),
			'name_admin_bar'        => __( 'Telescope Site', 'xophz-compass' ),
			'archives'              => __( 'Site Archives', 'xophz-compass' ),
			'attributes'            => __( 'Site Attributes', 'xophz-compass' ),
			'parent_item_colon'     => __( 'Parent Site:', 'xophz-compass' ),
			'all_items'             => __( 'All Sites', 'xophz-compass' ),
			'add_new_item'          => __( 'Add New Site', 'xophz-compass' ),
			'add_new'               => __( 'Add New', 'xophz-compass' ),
			'new_item'              => __( 'New Site', 'xophz-compass' ),
			'edit_item'             => __( 'Edit Site', 'xophz-compass' ),
			'update_item'           => __( 'Update Site', 'xophz-compass' ),
			'view_item'             => __( 'View Site', 'xophz-compass' ),
			'view_items'            => __( 'View Sites', 'xophz-compass' ),
			'search_items'          => __( 'Search Site', 'xophz-compass' ),
			'not_found'             => __( 'Not found', 'xophz-compass' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'xophz-compass' ),
		);
		$args = array(
			'label'                 => __( 'Telescope Site', 'xophz-compass' ),
			'description'           => __( 'Telescope saved sites and bookmarks', 'xophz-compass' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'custom-fields' ),
			'hierarchical'          => false,
			'public'                => true, // Let them be queried naturally
			'show_ui'               => true,
			'show_in_menu'          => false, // We'll manage mostly via REST/App
			'menu_position'         => 20,
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'capability_type'       => 'post',
			'show_in_rest'          => true, // Expose to default REST API just in case
		);
		register_post_type( self::POST_TYPE, $args );
	}
}
