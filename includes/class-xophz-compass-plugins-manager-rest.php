<?php
/**
 * Central REST API controller for managing all companion plugins in the COMPASS suite.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/core/class-compass-rest-controller.php';

class Xophz_Compass_Plugins_Manager_REST extends Xophz_Compass_REST_Controller {

	public function __construct() {
		parent::__construct( 'plugins', 'v1' );
	}

	/**
	 * Register routes for plugin management.
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->namespace,
			'/',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_plugins' ),
					'permission_callback' => array( $this, 'permissions_public' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/abilities',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_abilities' ),
					'permission_callback' => array( $this, 'permissions_public' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/(?P<slug>[a-zA-Z0-9-_]+)',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_plugin' ),
					'permission_callback' => array( $this, 'permissions_public' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/(?P<slug>[a-zA-Z0-9-_]+)/toggle',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'toggle_plugin' ),
					'permission_callback' => array( $this, 'permissions_admin' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/(?P<slug>[a-zA-Z0-9-_]+)/config',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_plugin_config' ),
					'permission_callback' => array( $this, 'permissions_admin' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_plugin_config' ),
					'permission_callback' => array( $this, 'permissions_admin' ),
				),
			)
		);
	}

	/**
	 * Get list of all companion plugins registered and detected.
	 */
	public function get_plugins( WP_REST_Request $request ): WP_REST_Response {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_wp_plugins = get_plugins();
		$active_plugins = (array) get_option( 'active_plugins', array() );
		$registered     = apply_filters( 'compass_registered_plugins', array() );

		$companion_plugins = array();

		foreach ( $all_wp_plugins as $file => $data ) {
			$dir = dirname( $file );
			$is_compass = strpos( $dir, 'xophz-compass' ) !== false || strpos( $file, 'xophz-compass' ) !== false;
			if ( ! $is_compass ) {
				continue;
			}

			$slug      = str_replace( 'xophz-compass-', '', $dir );
			$is_active = in_array( $file, $active_plugins, true );
			$meta      = $registered[ $slug ] ?? null;

			$companion_plugins[] = array(
				'file'        => $file,
				'slug'        => $slug,
				'name'        => $data['Name'] ?? $slug,
				'version'     => $data['Version'] ?? '1.0.0',
				'description' => $data['Description'] ?? '',
				'is_active'   => $is_active,
				'has_spark'   => ! empty( $meta['spark'] ),
				'metadata'    => $meta,
			);
		}

		return rest_ensure_response(
			array(
				'success' => true,
				'count'   => count( $companion_plugins ),
				'plugins' => $companion_plugins,
			)
		);
	}

	/**
	 * Get single plugin details.
	 */
	public function get_plugin( WP_REST_Request $request ): WP_REST_Response {
		$slug = sanitize_key( (string) $request->get_param( 'slug' ) );
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_wp_plugins = get_plugins();
		$active_plugins = (array) get_option( 'active_plugins', array() );
		$registered     = apply_filters( 'compass_registered_plugins', array() );

		$target_file = null;
		$target_data = null;

		foreach ( $all_wp_plugins as $file => $data ) {
			if ( dirname( $file ) === 'xophz-compass-' . $slug || $file === 'xophz-compass-' . $slug . '.php' ) {
				$target_file = $file;
				$target_data = $data;
				break;
			}
		}

		if ( ! $target_data ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => "Companion plugin '{$slug}' not found.",
				),
				404
			);
		}

		return rest_ensure_response(
			array(
				'success'   => true,
				'slug'      => $slug,
				'file'      => $target_file,
				'name'      => $target_data['Name'] ?? $slug,
				'version'   => $target_data['Version'] ?? '1.0.0',
				'is_active' => in_array( $target_file, $active_plugins, true ),
				'metadata'  => $registered[ $slug ] ?? null,
			)
		);
	}

	/**
	 * Activate or deactivate a plugin.
	 */
	public function toggle_plugin( WP_REST_Request $request ): WP_REST_Response {
		$slug   = sanitize_key( (string) $request->get_param( 'slug' ) );
		$action = sanitize_key( (string) $request->get_param( 'action' ) );

		if ( ! function_exists( 'activate_plugin' ) || ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_wp_plugins = get_plugins();
		$target_file    = null;

		foreach ( array_keys( $all_wp_plugins ) as $file ) {
			if ( dirname( $file ) === 'xophz-compass-' . $slug || $file === 'xophz-compass-' . $slug . '.php' ) {
				$target_file = $file;
				break;
			}
		}

		if ( ! $target_file ) {
			return new WP_REST_Response( array( 'success' => false, 'message' => "Plugin '{$slug}' not found." ), 404 );
		}

		if ( $action === 'activate' ) {
			$result = activate_plugin( $target_file );
			if ( is_wp_error( $result ) ) {
				return new WP_REST_Response( array( 'success' => false, 'message' => $result->get_error_message() ), 500 );
			}
			return rest_ensure_response( array( 'success' => true, 'is_active' => true, 'message' => "Activated {$slug}." ) );
		}

		if ( $action === 'deactivate' ) {
			deactivate_plugins( $target_file );
			return rest_ensure_response( array( 'success' => true, 'is_active' => false, 'message' => "Deactivated {$slug}." ) );
		}

		$is_active = is_plugin_active( $target_file );
		if ( $is_active ) {
			deactivate_plugins( $target_file );
		} else {
			activate_plugin( $target_file );
		}

		return rest_ensure_response( array( 'success' => true, 'is_active' => ! $is_active ) );
	}

	/**
	 * Get plugin configuration options.
	 */
	public function get_plugin_config( WP_REST_Request $request ): WP_REST_Response {
		$slug   = sanitize_key( (string) $request->get_param( 'slug' ) );
		$option = get_option( 'xophz_compass_' . str_replace( '-', '_', $slug ) . '_settings', array() );

		return rest_ensure_response(
			array(
				'success'  => true,
				'slug'     => $slug,
				'settings' => is_array( $option ) ? $option : array(),
			)
		);
	}

	/**
	 * Update plugin configuration options.
	 */
	public function update_plugin_config( WP_REST_Request $request ): WP_REST_Response {
		$slug     = sanitize_key( (string) $request->get_param( 'slug' ) );
		$settings = $request->get_param( 'settings' );

		if ( ! is_array( $settings ) ) {
			return new WP_REST_Response( array( 'success' => false, 'message' => 'Settings must be an object/array.' ), 400 );
		}

		$option_key = 'xophz_compass_' . str_replace( '-', '_', $slug ) . '_settings';
		update_option( $option_key, $settings );

		return rest_ensure_response(
			array(
				'success'  => true,
				'slug'     => $slug,
				'settings' => $settings,
			)
		);
	}

	/**
	 * Get central abilities catalog across all companion plugins.
	 */
	public function get_abilities( WP_REST_Request $request ): WP_REST_Response {
		$abilities = apply_filters( 'compass_abilities_registry', array() );

		return rest_ensure_response(
			array(
				'success'   => true,
				'count'     => count( $abilities ),
				'abilities' => $abilities,
			)
		);
	}
}
