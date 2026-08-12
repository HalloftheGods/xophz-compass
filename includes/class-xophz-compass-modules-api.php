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
		update_option( 'compass_client_onboardings', array_slice( $existing, 0, 100 ), false );

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
		$price = isset( $params['price'] ) ? floatval( $params['price'] ) : 99.98;
		$license = isset( $params['license'] ) ? sanitize_text_field( $params['license'] ) : 'Sovereign Ecosystem Engine';
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

		$license_lower = strtolower( $license );
		$is_subscription = ( strpos( $license_lower, 'monthly' ) !== false || strpos( $license_lower, 'engine' ) !== false || strpos( $license_lower, 'castle' ) !== false || strpos( $license_lower, 'sovereign' ) !== false || $price >= 90 );
		$is_castle = ( strpos( $license_lower, 'castle' ) !== false || strpos( $license_lower, 'enterprise' ) !== false || $price >= 3000 );
		$mode = $is_subscription ? 'subscription' : 'payment';

		// Clean product title formatting for Stripe Checkout
		$product_name = 'My Compass';
		if ( ! empty( $license ) ) {
			$product_name .= ' (' . $license . ')';
		}

		$price_data = array(
			'currency'     => 'usd',
			'product_data' => array(
				'name'     => $product_name,
				'tax_code' => 'txcd_10103000',
			),
			'unit_amount'  => (int) round( $price * 100 ), // Stripe expects cents
		);

		if ( $is_subscription ) {
			$price_data['recurring'] = array( 'interval' => 'month' );
		}

		$payload = array(
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
		);

		if ( $is_castle ) {
			// Automatically stop recurring billing after 6 months for Enterprise engagements
			$payload['subscription_data[cancel_at]'] = strtotime( '+6 months' );
		}

		// Make request to Stripe API
		$response = wp_remote_post( 'https://api.stripe.com/v1/checkout/sessions', array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $secret_key,
				'Content-Type'  => 'application/x-www-form-urlencoded',
			),
			'body' => http_build_query( $payload ),
			'timeout' => 15,
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
				'description'  => 'User rewards, experience points, and achievement badges',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-xp/archive/refs/heads/main.zip',
				'category'     => 'True North',
			),
			'xophz-compass-bazaar' => array(
				'slug'         => 'xophz-compass-bazaar',
				'name'         => 'Bazaar',
				'description'  => 'E-Commerce and digital storefront hub',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-bazaar/archive/refs/heads/main.zip',
				'category'     => 'Command Deck',
			),
			'xophz-compass-event-horizon' => array(
				'slug'         => 'xophz-compass-event-horizon',
				'name'         => 'Event Horizon',
				'description'  => 'Task automation and event trigger engine',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-event-horizon/archive/refs/heads/main.zip',
				'category'     => 'Command Deck',
			),
			'xophz-compass-bomb-bag' => array(
				'slug'         => 'xophz-compass-bomb-bag',
				'name'         => 'Bomb Bag',
				'description'  => 'High-impact messaging and notification dispatch',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-bomb-bag/archive/refs/heads/main.zip',
				'category'     => 'Trajectory',
			),
			'xophz-compass-enchanted-mirror' => array(
				'slug'         => 'xophz-compass-enchanted-mirror',
				'name'         => 'Enchanted Mirror',
				'description'  => 'User social profiles, avatars, and community reflection',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-enchanted-mirror/archive/refs/heads/main.zip',
				'category'     => 'True North',
			),
			'xophz-compass-bugnet' => array(
				'slug'         => 'xophz-compass-bugnet',
				'name'         => 'Bugnet',
				'description'  => 'Issue capture and error logging system',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-bugnet/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
			),
			'xophz-compass-enchiridion' => array(
				'slug'         => 'xophz-compass-enchiridion',
				'name'         => 'Enchiridion',
				'description'  => 'Comprehensive system manual and developer documentation',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-enchiridion/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
			),
			'xophz-compass-gale-boomerang' => array(
				'slug'         => 'xophz-compass-gale-boomerang',
				'name'         => 'Gale Boomerang',
				'description'  => 'Abandoned cart recovery and customer re-engagement',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-gale-boomerang/archive/refs/heads/main.zip',
				'category'     => 'True North',
			),
			'xophz-compass-golden-keys' => array(
				'slug'         => 'xophz-compass-golden-keys',
				'name'         => 'Golden Keys',
				'description'  => 'Granular RBAC vault, role controls, and access permissions',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-golden-keys/archive/refs/heads/main.zip',
				'category'     => 'True North',
			),
			'xophz-compass-hookshot' => array(
				'slug'         => 'xophz-compass-hookshot',
				'name'         => 'Hookshot',
				'description'  => 'GitHub and webhook deployment trigger listener',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-hookshot/archive/refs/heads/main.zip',
				'category'     => 'True North',
			),
			'xophz-compass-lead-magnet' => array(
				'slug'         => 'xophz-compass-lead-magnet',
				'name'         => 'Lead Magnet',
				'description'  => 'Marketing pop-ups, opt-in incentives, and lead generation engine',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-lead-magnet/archive/refs/heads/main.zip',
				'category'     => 'Trajectory',
			),
			'xophz-compass-lit-lamp' => array(
				'slug'         => 'xophz-compass-lit-lamp',
				'name'         => 'Lit Lamp',
				'description'  => 'Google Analytics integration and telemetry insights',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-lit-lamp/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-magic-cloak' => array(
				'slug'         => 'xophz-compass-magic-cloak',
				'name'         => 'Magic Cloak',
				'description'  => 'Zero-knowledge privacy suite, WAF firewall, and encryption',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-magic-cloak/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
			),
			'xophz-compass-midnight-nerd' => array(
				'slug'         => 'xophz-compass-midnight-nerd',
				'name'         => 'Midnight Nerd',
				'description'  => 'Developer tools console and system inspection',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-midnight-nerd/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
			),
			'xophz-compass-mirror-shield' => array(
				'slug'         => 'xophz-compass-mirror-shield',
				'name'         => 'Mirror Shield',
				'description'  => 'Threat protection, IP firewall, and intrusion defense',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-mirror-shield/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-moving-castle' => array(
				'slug'         => 'xophz-compass-moving-castle',
				'name'         => 'Moving Castle',
				'description'  => 'Multi-client agency management hub and site network',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-moving-castle/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-pegasus-boots' => array(
				'slug'         => 'xophz-compass-pegasus-boots',
				'name'         => 'Pegasus Boots',
				'description'  => 'High-speed caching and extreme scaling engine',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-pegasus-boots/archive/refs/heads/main.zip',
				'category'     => 'True North',
			),
			'xophz-compass-phantom-zone' => array(
				'slug'         => 'xophz-compass-phantom-zone',
				'name'         => 'Phantom Zone',
				'description'  => 'Sandbox testing environment and staging clones',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-phantom-zone/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-pixie-dust' => array(
				'slug'         => 'xophz-compass-pixie-dust',
				'name'         => 'Pixie Dust',
				'description'  => 'UI/UX design suite, CSS overrides, and spark styling',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-pixie-dust/archive/refs/heads/main.zip',
				'category'     => 'Trajectory',
			),
			'xophz-compass-quests' => array(
				'slug'         => 'xophz-compass-quests',
				'name'         => 'Quests',
				'description'  => 'Gamified quest tracking and customer relationship management',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-quests/archive/refs/heads/main.zip',
				'category'     => 'Command Deck',
			),
			'xophz-compass-silver-arrow' => array(
				'slug'         => 'xophz-compass-silver-arrow',
				'name'         => 'Silver Arrow',
				'description'  => 'Direct sales links, friction-free checkout, and high-ticket closing',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-silver-arrow/archive/refs/heads/main.zip',
				'category'     => 'Trajectory',
			),
			'xophz-compass-thors-hammer' => array(
				'slug'         => 'xophz-compass-thors-hammer',
				'name'         => 'Thors Hammer',
				'description'  => 'Automated site maintenance, DB repair, and cron tasks',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-thors-hammer/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-titans-mitt' => array(
				'slug'         => 'xophz-compass-titans-mitt',
				'name'         => 'Titans Mitt',
				'description'  => 'Data migration engine and heavy payload transfer',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-titans-mitt/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
			),
			'xophz-compass-treasure-map' => array(
				'slug'         => 'xophz-compass-treasure-map',
				'name'         => 'Treasure Map',
				'description'  => 'Node-based mind mapping and strategy planner',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-treasure-map/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-treasure-trove' => array(
				'slug'         => 'xophz-compass-treasure-trove',
				'name'         => 'Treasure Trove',
				'description'  => 'Centralized media library and asset vault',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-treasure-trove/archive/refs/heads/main.zip',
				'category'     => 'Castle Walls',
			),
			'xophz-compass-alphabet-soup' => array(
				'slug'         => 'xophz-compass-alphabet-soup',
				'name'         => 'Alphabet Soup',
				'description'  => 'Modern post manager and article publishing studio',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-alphabet-soup/archive/refs/heads/main.zip',
				'category'     => 'Command Deck',
			),
			'xophz-compass-magic-wand' => array(
				'slug'         => 'xophz-compass-magic-wand',
				'name'         => 'Magic Wand',
				'description'  => 'Front-end visual editor and quick page tweak tool',
				'download_url' => 'https://github.com/HalloftheGods/xophz-compass-magic-wand/archive/refs/heads/main.zip',
				'category'     => 'Wizard\'s Tower',
			),
			'xophz-nook-phone' => array(
				'slug'         => 'xophz-nook-phone',
				'name'         => 'Xophz Nook Phone',
				'description'  => 'Island companion widget suite and mini-app launcher',
				'download_url' => 'https://github.com/SuperNerdBros/xophz-nook-phone/archive/refs/heads/main.zip',
				'category'     => 'Command Deck',
			),
			'xophz-compass-phone' => array(
				'slug'         => 'xophz-compass-phone',
				'name'         => 'Phone',
				'description'  => 'Mobile companion app backend and system bridge',
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
			if ( empty( $module['icon'] ) ) {
				$icon_path = WP_PLUGIN_DIR . '/' . $slug . '/icon.svg';
				if ( file_exists( $icon_path ) ) {
					$module['icon'] = wp_make_link_relative( plugins_url( $slug . '/icon.svg' ) );
				} else {
					$module['icon'] = wp_make_link_relative( plugins_url( 'assets/' . $slug . '.png', dirname( __FILE__, 2 ) . '/xophz-compass.php' ) );
				}
			}

			if ( $slug === 'xophz-compass-magic-formula' ) {
				$module['is_installed'] = true;
				$module['is_active']    = true;
				continue;
			}

			$plugin_file = $slug . '/' . $slug . '.php';
			$module['is_installed'] = array_key_exists( $plugin_file, $installed_plugins );
			$module['is_active'] = is_plugin_active( $plugin_file );

			if ( $module['is_installed'] && ! empty( $installed_plugins[ $plugin_file ]['Description'] ) ) {
				$module['description'] = $installed_plugins[ $plugin_file ]['Description'];
			} elseif ( ! $module['is_installed'] ) {
				$cached_desc = get_transient( 'compass_mod_desc_' . $slug );
				if ( false !== $cached_desc && ! empty( $cached_desc ) ) {
					$module['description'] = $cached_desc;
				} else {
					$fetched_desc = self::fetch_github_module_description( $slug );
					if ( ! empty( $fetched_desc ) ) {
						$module['description'] = $fetched_desc;
						set_transient( 'compass_mod_desc_' . $slug, $fetched_desc, DAY_IN_SECONDS );
					}
				}
			}
		}

		return rest_ensure_response( array( 'modules' => $modules ) );
	}

	/**
	 * Fetch module description from raw GitHub main file header or repo description.
	 *
	 * @param string $slug
	 * @return string|false
	 */
	public static function fetch_github_module_description( $slug ) {
		$owner = 'HalloftheGods';
		$repo  = $slug;

		if ( strpos( $slug, '/' ) !== false ) {
			list( $owner, $repo ) = explode( '/', $slug, 2 );
		} elseif ( strpos( $slug, 'super-nerd-bros' ) !== false || strpos( $slug, 'nook-phone' ) !== false ) {
			$owner = 'SuperNerdBros';
		}

		$raw_url  = "https://raw.githubusercontent.com/{$owner}/{$repo}/main/{$repo}.php";
		$response = wp_remote_get( $raw_url, array(
			'timeout'    => 5,
			'user-agent' => 'COMPASS-Module-Fetcher',
		) );

		if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
			$body = wp_remote_retrieve_body( $response );
			if ( preg_match( '/ Description:\s*(.+)/i', $body, $matches ) ) {
				return trim( $matches[1] );
			}
		}

		$api_url  = "https://api.github.com/repos/{$owner}/{$repo}";
		$response = wp_remote_get( $api_url, array(
			'timeout'    => 5,
			'user-agent' => 'COMPASS-Module-Fetcher',
			'headers'    => array(
				'Accept' => 'application/vnd.github+json',
			),
		) );

		if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( ! empty( $data['description'] ) ) {
				return trim( $data['description'] );
			}
		}

		return false;
	}

	/**
	 * Dynamically query GitHub API for the latest release ZIP package asset.
	 *
	 * @param string $slug Repository slug (e.g. 'xophz-compass-phone' or 'xophz-compass')
	 * @param string $github_token Optional GitHub API bearer token
	 * @return array|false Release asset array or false if not found
	 */
	public static function fetch_latest_release_zip( $slug, $github_token = '' ) {
		$owner = 'HalloftheGods';
		$repo  = $slug;

		if ( strpos( $slug, '/' ) !== false ) {
			list( $owner, $repo ) = explode( '/', $slug, 2 );
		}

		$api_url = "https://api.github.com/repos/{$owner}/{$repo}/releases/latest";

		$args = array(
			'timeout'    => 15,
			'user-agent' => 'COMPASS-Plugin-Installer',
			'headers'    => array(
				'Accept' => 'application/vnd.github+json',
			),
		);

		if ( ! empty( $github_token ) ) {
			$args['headers']['Authorization'] = 'token ' . $github_token;
		}

		$response = wp_remote_get( $api_url, $args );

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! empty( $body['assets'] ) && is_array( $body['assets'] ) ) {
			foreach ( $body['assets'] as $asset ) {
				$asset_name = isset( $asset['name'] ) ? $asset['name'] : '';
				if ( substr( $asset_name, -4 ) === '.zip' ) {
					$is_private = ! empty( $body['repository']['private'] );
					$download_url = ( $is_private && ! empty( $github_token ) )
						? ( isset( $asset['url'] ) ? $asset['url'] : $asset['browser_download_url'] )
						: ( isset( $asset['browser_download_url'] ) ? $asset['browser_download_url'] : '' );

					if ( ! empty( $download_url ) ) {
						return array(
							'download_url' => $download_url,
							'is_api_asset' => ( $is_private && ! empty( $github_token ) ),
							'asset_name'   => $asset_name,
							'version'      => isset( $body['tag_name'] ) ? ltrim( $body['tag_name'], 'v' ) : '',
						);
					}
				}
			}
		}

		return false;
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

		$plugin_file = $slug . '/' . $slug . '.php';

		// If plugin is already installed on disk, simply activate it
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$installed_plugins = get_plugins();
		if ( array_key_exists( $plugin_file, $installed_plugins ) || file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
			$activated = activate_plugin( $plugin_file );
			if ( is_wp_error( $activated ) ) {
				return new WP_Error( 'activation_failed', 'Module is present but activation failed: ' . $activated->get_error_message(), array( 'status' => 500 ) );
			}
			return rest_ensure_response( array(
				'success' => true,
				'message' => 'Module activated successfully.',
				'slug'    => $slug
			) );
		}

		// Set up GitHub token auth filter if available
		$github_token = '';
		if ( defined( 'GITHUB_TOKEN' ) ) {
			$github_token = GITHUB_TOKEN;
		} elseif ( defined( 'GITHUB_PA_TOKEN' ) ) {
			$github_token = GITHUB_PA_TOKEN;
		} else {
			$github_token = get_option( 'xophz_compass_bugnet_github_token', '' );
		}

		// Dynamically fetch official GitHub release ZIP package asset
		$release_asset = self::fetch_latest_release_zip( $slug, $github_token );
		if ( $release_asset && ! empty( $release_asset['download_url'] ) ) {
			$download_url = $release_asset['download_url'];
		} elseif ( isset( $registry[ $slug ] ) && ! empty( $registry[ $slug ]['download_url'] ) ) {
			$download_url = $registry[ $slug ]['download_url'];
		} else {
			$download_url = "https://github.com/HalloftheGods/{$slug}/archive/refs/heads/main.zip";
		}

		// Include necessary files for the Upgrader
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

		$auth_filter = null;
		if ( ! empty( $github_token ) ) {
			$auth_filter = function( $args, $url ) use ( $github_token ) {
				$args['timeout'] = 300;
				if ( strpos( $url, 'api.github.com' ) !== false ) {
					$args['headers']['Authorization'] = 'token ' . $github_token;
					$args['headers']['Accept']        = 'application/octet-stream';
				} elseif ( strpos( $url, 'github.com' ) !== false ) {
					$args['headers']['Authorization'] = 'token ' . $github_token;
				}
				return $args;
			};
			add_filter( 'http_request_args', $auth_filter, 10, 2 );
		}

		// Silent upgrader skin so it doesn't print HTML to the REST API request output
		$skin     = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );

		// Hook to rename GitHub archive folders which usually end in '-main' or a version string
		$rename_filter = function( $source, $remote_source, $upgrader_obj, $hook_extra = null ) use ( $slug ) {
			global $wp_filesystem;
			if ( is_wp_error( $source ) ) {
				return $source;
			}
			if ( ! is_object( $wp_filesystem ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
				$wp_filesystem = new WP_Filesystem_Direct( null );
			}
			$expected_dir = $slug;
			$source_dir   = untrailingslashit( $source );
			
			if ( basename( $source_dir ) === $expected_dir ) {
				return $source;
			}
			
			$new_source = trailingslashit( $remote_source ) . $expected_dir;
			if ( $wp_filesystem->is_dir( $new_source ) ) {
				$wp_filesystem->delete( $new_source, true );
			}
			if ( $wp_filesystem->move( $source, $new_source ) ) {
				return trailingslashit( $new_source );
			}
			if ( function_exists( 'copy_dir' ) ) {
				$copy_res = copy_dir( $source, $new_source );
				if ( ! is_wp_error( $copy_res ) && $copy_res !== false ) {
					$wp_filesystem->delete( $source, true );
					return trailingslashit( $new_source );
				}
			}
			return $source;
		};
		
		add_filter( 'upgrader_source_selection', $rename_filter, 10, 4 );

		// Run the installation
		$installed = $upgrader->install( $download_url );
		
		remove_filter( 'upgrader_source_selection', $rename_filter, 10 );
		if ( $auth_filter ) {
			remove_filter( 'http_request_args', $auth_filter, 10 );
		}

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
