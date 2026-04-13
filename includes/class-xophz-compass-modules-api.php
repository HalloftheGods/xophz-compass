<?php

/**
 * The Modules API
 *
 * Handles the REST API endpoints to serve the Ghost List registry
 * and install/activate modules natively via WordPress Core UI.
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

class Xophz_Compass_Modules_API {

	/**
	 * Register the REST API routes for the Modules system.
	 */
	public function register_routes() {
		register_rest_route( 'xophz/v1', '/modules', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_modules' ),
				'permission_callback' => '__return_true', // Anyone who can access the interface can view the list
			)
		) );

		register_rest_route( 'xophz/v1', '/modules/install', array(
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'install_module' ),
				'permission_callback' => function() {
					return current_user_can( 'install_plugins' );
				},
				'args'     => array(
					'slug' => array(
						'required' => true,
						'type' => 'string',
						'validate_callback' => function($param, $request, $key) {
							return preg_match('/^[a-z0-9-]+$/i', $param) === 1;
						}
					),
				),
			)
		) );
	}

	/**
	 * Get the Ghost List of available modules.
	 *
	 * @return array The list of registered modules.
	 */
	public static function get_module_registry() {
		$modules = array();
		
		// 1. Dynamic generation by reading `.gitmodules` natively in PHP
		// Use dirname(ABSPATH) for Bedrock compat, falling back to ABSPATH
		$gitmodules_path = ABSPATH . '.gitmodules';
		if ( ! file_exists( $gitmodules_path ) && file_exists( dirname( ABSPATH ) . '/.gitmodules' ) ) {
			$gitmodules_path = dirname( ABSPATH ) . '/.gitmodules';
		}
		
		if ( file_exists( $gitmodules_path ) ) {
			$content = file_get_contents( $gitmodules_path );
			if ( preg_match_all('/\[submodule "(.*?)"\].*?url = (.*?)\s/sm', $content, $matches, PREG_SET_ORDER) ) {
				foreach ($matches as $match) {
					$path = $match[1];
					$git_url = $match[2];
					
					if ( strpos($path, 'wp-content/plugins/xophz-compass-') === 0 ) {
						$slug = str_replace('wp-content/plugins/', '', $path);
						
						// Create friendly name
						$name_parts = explode('-', str_replace('xophz-compass-', '', $slug));
						$name_parts = array_map('ucfirst', $name_parts);
						$name = implode(' ', $name_parts);

						// Convert git url to github archive zip
						$zip_url = str_replace('git@github.com:', 'https://github.com/', $git_url);
						$zip_url = str_replace('.git', '', $zip_url);
						$zip_url .= '/archive/refs/heads/main.zip';

						$modules[$slug] = array(
							'slug'         => $slug,
							'name'         => $name,
							'description'  => 'Compass Ecosystem Module',
							'download_url' => $zip_url,
						);
					}
				}
			}
		}
		
		// Fallback for production where .gitmodules might not exist
		if ( empty( $modules ) ) {
			$modules = array(
				'xophz-compass-xp' => array(
					'slug'         => 'xophz-compass-xp',
					'name'         => 'XP',
					'description'  => 'Compass Ecosystem Module',
					'download_url' => 'https://github.com/HalloftheGods/xophz-compass-xp/archive/refs/heads/main.zip',
				),
				// For brevity in fallback, just ensuring the main ones exist 
				// We can rely on the frontend passing the URL directly!
			);
		}

		// Bundled natively, does not download via GitHub
		$modules['xophz-compass-magic-formula'] = array(
			'slug'         => 'xophz-compass-magic-formula',
			'name'         => 'Magic Formula',
			'description'  => 'The ultimate form, poll, and quiz builder.',
			'download_url' => '', 
		);

		return apply_filters( 'xophz_compass_modules_registry', $modules );
	}

	/**
	 * Return the registry as JSON.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_modules( $request ) {
		$modules = self::get_module_registry();

		// Check which ones are already installed
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		
		$installed_plugins = get_plugins();

		foreach ( $modules as $slug => &$module ) {
			if ( $slug === 'xophz-compass-magic-formula' ) {
				$module['is_installed'] = true;
				$module['is_active']    = true;
				continue;
			}

			$plugin_file = $slug . '/' . $slug . '.php';
			$module['is_installed'] = array_key_exists( $plugin_file, $installed_plugins );
			$module['is_active'] = is_plugin_active( $plugin_file );
		}

		return rest_ensure_response( array( 'modules' => $modules ) );
	}

	/**
	 * Install a module given its slug.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function install_module( $request ) {
		$slug = $request->get_param( 'slug' );
		$registry = self::get_module_registry();

		if ( ! isset( $registry[ $slug ] ) ) {
			return new WP_Error( 'invalid_module_slug', 'The requested module slug does not exist in the COMPASS ecosystem registry.', array( 'status' => 404 ) );
		}

		$module_data  = $registry[ $slug ];
		$download_url = $module_data['download_url'];
		$plugin_file  = $slug . '/' . $slug . '.php';

		// Include necessary files for the Upgrader
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

		// Silent upgrader skin so it doesn't print HTML to the REST API request output
		$skin     = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );

		// Run the installation
		$installed = $upgrader->install( $download_url );

		if ( is_wp_error( $installed ) ) {
			return new WP_Error( 'install_failed', 'Installation failed: ' . $installed->get_error_message(), array( 'status' => 500 ) );
		} elseif ( ! $installed ) {
			return new WP_Error( 'install_failed', 'Installation failed for an unknown reason.', array( 'status' => 500 ) );
		}

		// Activate it
		$activated = activate_plugin( $plugin_file );

		if ( is_wp_error( $activated ) ) {
			return new WP_Error( 'activation_failed', 'Module installed but activation failed: ' . $activated->get_error_message(), array( 'status' => 500 ) );
		}

		return rest_ensure_response( array(
			'success' => true,
			'message' => 'Module installed and activated successfully.',
			'slug'    => $slug
		) );
	}
}
