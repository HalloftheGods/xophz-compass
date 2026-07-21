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

		register_rest_route( 'xophz/v1', '/stripe/checkout', array(
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'create_stripe_checkout' ),
				'permission_callback' => '__return_true',
			)
		) );

		register_rest_route( 'xophz/v1', '/client/onboard', array(
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'submit_client_onboarding' ),
				'permission_callback' => '__return_true',
			)
		) );
	}

	/**
	 * Handle Client Onboarding Form Submission
	 */
	public function submit_client_onboarding( $request ) {
		$params = $request->get_json_params();

		$name    = isset( $params['name'] ) ? sanitize_text_field( $params['name'] ) : '';
		$email   = isset( $params['email'] ) ? sanitize_email( $params['email'] ) : '';
		$phone   = isset( $params['phone'] ) ? sanitize_text_field( $params['phone'] ) : '';
		$website = isset( $params['website'] ) ? esc_url_raw( $params['website'] ) : '';
		$license = isset( $params['license'] ) ? sanitize_text_field( $params['license'] ) : 'True North Subscription';

		if ( empty( $email ) ) {
			return new WP_Error( 'missing_email', 'Email address is required for provisioning.', array( 'status' => 400 ) );
		}

		$submission = array(
			'id'         => uniqid( 'client_' ),
			'name'       => $name,
			'email'      => $email,
			'phone'      => $phone,
			'website'    => $website,
			'license'    => $license,
			'created_at' => current_time( 'mysql' ),
		);

		// Store in WordPress option array as a log
		$existing = get_option( 'compass_client_onboardings', array() );
		if ( ! is_array( $existing ) ) {
			$existing = array();
		}
		array_unshift( $existing, $submission );
		update_option( 'compass_client_onboardings', array_slice( $existing, 0, 100 ) );

		// 1. Send Admin Email Notification
		$admin_email = get_option( 'admin_email' );
		$subject     = '[Compass] New Client Provisioning Request: ' . ( ! empty( $name ) ? $name : $email );
		$body        = "A new client has completed checkout and submitted their onboarding details for dedicated My Compass instance setup:\n\n" .
		               "• Name: " . $name . "\n" .
		               "• Email: " . $email . "\n" .
		               "• Phone: " . $phone . "\n" .
		               "• Website: " . $website . "\n" .
		               "• License Tier: " . $license . "\n\n" .
		               "Please log in to your WP-MU-DEV portal to provision their dedicated My Compass site instance.";

		wp_mail( $admin_email, $subject, $body );

		// 2. Trigger SMS Alert if Twilio is configured
		if ( class_exists( 'Xophz_Compass_Twilio_API' ) ) {
			$twilio_phone = get_option( 'compass_twilio_phone_number' );
			if ( ! empty( $twilio_phone ) ) {
				$sms_msg = "🚀 [Compass Alert] New Provisioning Request: $name ($email, $website). Ready for instance setup!";
				Xophz_Compass_Twilio_API::send_sms( $twilio_phone, $sms_msg );
			}
		}

		return rest_ensure_response( array(
			'success' => true,
			'message' => 'Onboarding information received successfully.',
		) );
	}

	/**
	 * Create Stripe Checkout Session
	 */
	public function create_stripe_checkout( $request ) {
		$params = $request->get_json_params();
		$price = isset( $params['price'] ) ? intval( $params['price'] ) : 100;
		$license = isset( $params['license'] ) ? sanitize_text_field( $params['license'] ) : 'True North Monthly';
		$success_url = isset( $params['success_url'] ) ? esc_url_raw( $params['success_url'] ) : home_url();
		$cancel_url = isset( $params['cancel_url'] ) ? esc_url_raw( $params['cancel_url'] ) : home_url();

		// Fetch Stripe Secret Key via WordPress Connectors API
		$secret_key = '';

		if ( function_exists( 'wp_get_connectors' ) ) {
			$connectors = wp_get_connectors();
			if ( ! empty( $connectors['stripe_secret_key']['authentication']['setting_name'] ) ) {
				$setting_name = $connectors['stripe_secret_key']['authentication']['setting_name'];
				$secret_key = get_option( $setting_name, '' );
			}
		}

		if ( empty( $secret_key ) ) {
			$secret_key = get_option( 'compass_stripe_secret_key', '' );
		}

		if ( empty( $secret_key ) && defined( 'STRIPE_SECRET_KEY' ) ) {
			$secret_key = STRIPE_SECRET_KEY;
		}

		if ( empty( $secret_key ) && ! empty( $_ENV['STRIPE_SECRET_KEY'] ) ) {
			$secret_key = $_ENV['STRIPE_SECRET_KEY'];
		}

		if ( empty( $secret_key ) ) {
			return new WP_Error( 'no_stripe_key', 'Stripe secret key is not configured in Settings -> Connectors UI or STRIPE_SECRET_KEY.', array( 'status' => 400 ) );
		}

		$is_subscription = strpos( strtolower( $license ), 'monthly' ) !== false || strpos( strtolower( $license ), 'true north' ) !== false;
		$mode = $is_subscription ? 'subscription' : 'payment';

		// Clean product title formatting for Stripe Checkout
		$product_name = 'My Compass';
		if ( ! empty( $license ) ) {
			$product_name .= ' (' . $license . ')';
		}

		$price_data = array(
			'currency'     => 'usd',
			'product_data' => array(
				'name' => $product_name,
			),
			'unit_amount'  => $price * 100, // Stripe expects cents
		);

		if ( $is_subscription ) {
			$price_data['recurring'] = array( 'interval' => 'month' );
		}

		// Make request to Stripe API
		$response = wp_remote_post( 'https://api.stripe.com/v1/checkout/sessions', array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $secret_key,
				'Content-Type'  => 'application/x-www-form-urlencoded',
			),
			'body' => http_build_query( array(
				'payment_method_types'  => array( 'card' ),
				'allow_promotion_codes' => 'true',
				'line_items'            => array(
					array(
						'price_data' => $price_data,
						'quantity'   => 1,
					)
				),
				'mode'                  => $mode,
				'success_url'           => $success_url,
				'cancel_url'            => $cancel_url,
			) ),
		) );

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'stripe_error', $response->get_error_message(), array( 'status' => 500 ) );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! empty( $body['url'] ) ) {
			return rest_ensure_response( array( 'url' => $body['url'] ) );
		}

		return new WP_Error( 'stripe_api_error', isset( $body['error']['message'] ) ? $body['error']['message'] : 'Stripe checkout creation failed.', array( 'status' => 400 ) );
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
				'category'     => 'Command Deck',
			),
			'xophz-compass-event-horizon' => array(
				'slug'         => 'xophz-compass-event-horizon',
				'name'         => 'Event Horizon',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-event-horizon/archive/refs/heads/main.zip',
				'category'     => 'Command Deck',
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
				'category'     => 'True North',
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
				'category'     => 'True North',
			),
			'xophz-compass-golden-keys' => array(
				'slug'         => 'xophz-compass-golden-keys',
				'name'         => 'Golden Keys',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-golden-keys/archive/refs/heads/main.zip',
				'category'     => 'True North',
			),
			'xophz-compass-hookshot' => array(
				'slug'         => 'xophz-compass-hookshot',
				'name'         => 'Hookshot',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-hookshot/archive/refs/heads/main.zip',
				'category'     => 'True North',
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
				'category'     => 'Castle Walls',
			),
			'xophz-compass-magic-cloak' => array(
				'slug'         => 'xophz-compass-magic-cloak',
				'name'         => 'Magic Cloak',
				'description'  => 'Compass Ecosystem Module',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-magic-cloak/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
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
			),
			'super-nerd-bros-dodo-air' => array(
				'slug'         => 'super-nerd-bros-dodo-air',
				'name'         => 'Dodo Air',
				'description'  => 'Standalone WordPress backend and router for the Dodo Air SvelteKit app.',
				'download_url' => 'https://github.com/SuperNerdBros/wp-dodo-air/archive/refs/heads/main.zip',
				'category'     => 'Command Deck',
			)
		);

		// Bundled natively, does not download via GitHub
		$modules['xophz-compass-magic-formula'] = array(
			'slug'         => 'xophz-compass-magic-formula',
			'name'         => 'Magic Formulas',
			'description'  => 'The ultimate form, poll, and quiz builder.',
			'download_url' => '', 
			'category'     => 'Command Deck',
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

		// Dynamically fetch the latest release asset from GitHub if applicable
		if ( strpos( $download_url, 'github.com' ) !== false && class_exists( 'Xophz_Compass_Updater' ) ) {
			$parts = explode( '/', parse_url( $download_url, PHP_URL_PATH ) );
			if ( isset( $parts[1], $parts[2] ) ) {
				$repo = $parts[1] . '/' . $parts[2];
				$release = Xophz_Compass_Updater::fetch_release( $repo );
				if ( $release ) {
					$release_url = Xophz_Compass_Updater::get_download_url( $release );
					if ( $release_url ) {
						$download_url = $release_url;
					}
				}
			}
		}

		// Include necessary files for the Upgrader
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

		// Silent upgrader skin so it doesn't print HTML to the REST API request output
		$skin     = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );

		// Hook to rename GitHub archive folders which usually end in '-main' or a version string
		$rename_filter = function( $source, $remote_source, $upgrader_obj, $hook_extra = null ) use ( $slug ) {
			global $wp_filesystem;
			$expected_dir = $slug;
			$source_dir = untrailingslashit( $source );
			
			if ( basename( $source_dir ) === $expected_dir ) {
				return $source;
			}
			
			$new_source = trailingslashit( $remote_source ) . $expected_dir;
			if ( $wp_filesystem->move( $source, $new_source ) ) {
				return trailingslashit( $new_source );
			}
			return $source;
		};
		
		add_filter( 'upgrader_source_selection', $rename_filter, 10, 4 );

		// Run the installation
		$installed = $upgrader->install( $download_url );
		
		remove_filter( 'upgrader_source_selection', $rename_filter, 10 );

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
