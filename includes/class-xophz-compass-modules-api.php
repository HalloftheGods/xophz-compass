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
		$modules = array(
			'xophz-compass-xp' => array(
				'slug'         => 'xophz-compass-xp',
				'name'         => 'XP',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-xp/archive/refs/heads/main.zip',
				'category'     => 'True North',
			),
			'xophz-compass-bazaar' => array(
				'slug'         => 'xophz-compass-bazaar',
				'name'         => 'Bazaar',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-bazaar/archive/refs/heads/main.zip',
				'category'     => 'True North',
			),
			'xophz-compass-event-horizon' => array(
				'slug'         => 'xophz-compass-event-horizon',
				'name'         => 'Event Horizon',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-event-horizon/archive/refs/heads/main.zip',
				'category'     => 'Trajectory',
			),
			'xophz-compass-bomb-bag' => array(
				'slug'         => 'xophz-compass-bomb-bag',
				'name'         => 'Bomb Bag',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-bomb-bag/archive/refs/heads/main.zip',
				'category'     => 'Trajectory',
			),
			'xophz-compass-enchanted-mirror' => array(
				'slug'         => 'xophz-compass-enchanted-mirror',
				'name'         => 'Enchanted Mirror',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-enchanted-mirror/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-bugnet' => array(
				'slug'         => 'xophz-compass-bugnet',
				'name'         => 'Bugnet',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-bugnet/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
			),
			'xophz-compass-enchiridion' => array(
				'slug'         => 'xophz-compass-enchiridion',
				'name'         => 'Enchiridion',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-enchiridion/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
			),
			'xophz-compass-gale-boomerang' => array(
				'slug'         => 'xophz-compass-gale-boomerang',
				'name'         => 'Gale Boomerang',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-gale-boomerang/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-golden-keys' => array(
				'slug'         => 'xophz-compass-golden-keys',
				'name'         => 'Golden Keys',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-golden-keys/archive/refs/heads/main.zip',
				'category'     => 'Command Deck',
			),
			'xophz-compass-lead-magnet' => array(
				'slug'         => 'xophz-compass-lead-magnet',
				'name'         => 'Lead Magnet',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-lead-magnet/archive/refs/heads/main.zip',
				'category'     => 'Trajectory',
			),
			'xophz-compass-lit-lamp' => array(
				'slug'         => 'xophz-compass-lit-lamp',
				'name'         => 'Lit Lamp',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-lit-lamp/archive/refs/heads/main.zip',
				'category'     => 'True North',
			),
			'xophz-compass-magic-cloak' => array(
				'slug'         => 'xophz-compass-magic-cloak',
				'name'         => 'Magic Cloak',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-magic-cloak/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-midnight-nerd' => array(
				'slug'         => 'xophz-compass-midnight-nerd',
				'name'         => 'Midnight Nerd',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-midnight-nerd/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
			),
			'xophz-compass-mirror-shield' => array(
				'slug'         => 'xophz-compass-mirror-shield',
				'name'         => 'Mirror Shield',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-mirror-shield/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-moving-castle' => array(
				'slug'         => 'xophz-compass-moving-castle',
				'name'         => 'Moving Castle',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-moving-castle/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-pegasus-boots' => array(
				'slug'         => 'xophz-compass-pegasus-boots',
				'name'         => 'Pegasus Boots',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-pegasus-boots/archive/refs/heads/main.zip',
				'category'     => 'True North',
			),
			'xophz-compass-phantom-zone' => array(
				'slug'         => 'xophz-compass-phantom-zone',
				'name'         => 'Phantom Zone',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-phantom-zone/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-pixie-dust' => array(
				'slug'         => 'xophz-compass-pixie-dust',
				'name'         => 'Pixie Dust',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-pixie-dust/archive/refs/heads/main.zip',
				'category'     => 'Trajectory',
			),
			'xophz-compass-quests' => array(
				'slug'         => 'xophz-compass-quests',
				'name'         => 'Quests',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-quests/archive/refs/heads/main.zip',
				'category'     => 'Command Deck',
			),
			'xophz-compass-silver-arrow' => array(
				'slug'         => 'xophz-compass-silver-arrow',
				'name'         => 'Silver Arrow',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-silver-arrow/archive/refs/heads/main.zip',
				'category'     => 'Trajectory',
			),
			'xophz-compass-thors-hammer' => array(
				'slug'         => 'xophz-compass-thors-hammer',
				'name'         => 'Thors Hammer',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-thors-hammer/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-titans-mitt' => array(
				'slug'         => 'xophz-compass-titans-mitt',
				'name'         => 'Titans Mitt',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-titans-mitt/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
			),
			'xophz-compass-treasure-map' => array(
				'slug'         => 'xophz-compass-treasure-map',
				'name'         => 'Treasure Map',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-treasure-map/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-treasure-trove' => array(
				'slug'         => 'xophz-compass-treasure-trove',
				'name'         => 'Treasure Trove',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-treasure-trove/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-alphabet-soup' => array(
				'slug'         => 'xophz-compass-alphabet-soup',
				'name'         => 'Alphabet Soup',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-alphabet-soup/archive/refs/heads/main.zip',
				'category'     => 'Command Deck',
			),
			'xophz-compass-magic-wand' => array(
				'slug'         => 'xophz-compass-magic-wand',
				'name'         => 'Magic Wand',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-magic-wand/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
			),
			'xophz-nook-phone' => array(
				'slug'         => 'xophz-nook-phone',
				'name'         => 'Xophz Nook Phone',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/SuperNerdBros/xophz-nook-phone/archive/refs/heads/main.zip',
				'category'     => 'Command Deck',
			),
			'xophz-compass-phone' => array(
				'slug'         => 'xophz-compass-phone',
				'name'         => 'Phone',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-phone/releases/download/v26.7.20.1409/xophz-compass-phone-26.7.20.1409.zip',
				'category'     => 'Command Deck',
			)
		);

		// Bundled natively, does not download via GitHub
		$modules['xophz-compass-magic-formula'] = array(
			'slug'         => 'xophz-compass-magic-formula',
			'name'         => 'Magic Formulas',
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
