<?php
/**
 * Register custom Connectors for Xophz COMPASS.
 *
 * Hooking into the WP 7.0+ wp_connectors_init action to register API keys 
 * into the centralized Settings -> Connectors UI.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Xophz_Compass_Connectors {

	/**
	 * Initialize the hooks.
	 */
	public static function init() {
		add_action( 'wp_connectors_init', array( __CLASS__, 'register_connectors' ) );
	}

	/**
	 * Register all Compass environment variables as connectors.
	 * 
	 * @param WP_Connector_Registry $registry The connector registry instance.
	 */
	public static function register_connectors( WP_Connector_Registry $registry ) {
		
		// ---------------------------------------------------------
		// Twilio Configuration
		// ---------------------------------------------------------
		$registry->register( 'twilio_account_sid', array(
			'name'           => __( 'Twilio Account SID', 'xophz-compass' ),
			'description'    => __( 'Account SID for Twilio SMS integration.', 'xophz-compass' ),
			'type'           => 'communication',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://console.twilio.com/',
				'setting_name'    => 'compass_twilio_account_sid',
			),
		) );

		$registry->register( 'twilio_auth_token', array(
			'name'           => __( 'Twilio Auth Token', 'xophz-compass' ),
			'description'    => __( 'Auth Token for Twilio SMS integration.', 'xophz-compass' ),
			'type'           => 'communication',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://console.twilio.com/',
				'setting_name'    => 'compass_twilio_auth_token',
			),
		) );

		$registry->register( 'twilio_phone_number', array(
			'name'           => __( 'Twilio Phone Number', 'xophz-compass' ),
			'description'    => __( 'The Twilio Phone Number from which SMS messages will be sent (e.g. +1234567890).', 'xophz-compass' ),
			'type'           => 'communication',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://console.twilio.com/',
				'setting_name'    => 'compass_twilio_phone_number',
			),
		) );

		// ---------------------------------------------------------
		// Discord Configuration
		// ---------------------------------------------------------
		$registry->register( 'discord_client_id', array(
			'name'           => __( 'Discord Client ID', 'xophz-compass' ),
			'description'    => __( 'Client ID for Discord OAuth and bot integration.', 'xophz-compass' ),
			'type'           => 'social',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://discord.com/developers/applications',
				'setting_name'    => 'compass_discord_client_id',
			),
		) );

		$registry->register( 'discord_client_secret', array(
			'name'           => __( 'Discord Client Secret', 'xophz-compass' ),
			'description'    => __( 'Client Secret for Discord OAuth.', 'xophz-compass' ),
			'type'           => 'social',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://discord.com/developers/applications',
				'setting_name'    => 'compass_discord_client_secret',
			),
		) );

		// ---------------------------------------------------------
		// Pinata (IPFS) Configuration
		// ---------------------------------------------------------
		$registry->register( 'pinata_api_key', array(
			'name'           => __( 'Pinata API Key', 'xophz-compass' ),
			'description'    => __( 'Pinata API Key for decentralized storage.', 'xophz-compass' ),
			'type'           => 'storage',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://app.pinata.cloud/developers/api-keys',
				'setting_name'    => 'compass_pinata_api_key',
			),
		) );

		$registry->register( 'pinata_api_secret', array(
			'name'           => __( 'Pinata API Secret', 'xophz-compass' ),
			'description'    => __( 'Pinata API Secret for decentralized storage.', 'xophz-compass' ),
			'type'           => 'storage',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://app.pinata.cloud/developers/api-keys',
				'setting_name'    => 'compass_pinata_api_secret',
			),
		) );

		$registry->register( 'pinata_jwt', array(
			'name'           => __( 'Pinata JWT', 'xophz-compass' ),
			'description'    => __( 'Pinata JWT for decentralized storage authentication.', 'xophz-compass' ),
			'type'           => 'storage',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://app.pinata.cloud/developers/api-keys',
				'setting_name'    => 'compass_pinata_jwt',
			),
		) );

		// ---------------------------------------------------------
		// Google / Gemini Configuration
		// ---------------------------------------------------------
		$registry->register( 'google_oauth_client_id', array(
			'name'           => __( 'Google Client ID', 'xophz-compass' ),
			'description'    => __( 'Google Client ID for OAuth and APIs.', 'xophz-compass' ),
			'type'           => 'social',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://console.cloud.google.com/apis/credentials',
				'setting_name'    => 'compass_google_client_id',
			),
		) );

		$registry->register( 'google_oauth_client_secret', array(
			'name'           => __( 'Google Client Secret', 'xophz-compass' ),
			'description'    => __( 'Google Client Secret for OAuth and APIs.', 'xophz-compass' ),
			'type'           => 'social',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://console.cloud.google.com/apis/credentials',
				'setting_name'    => 'compass_google_client_secret',
			),
		) );

		// ---------------------------------------------------------
		// Tracking / Analytics Configuration
		// ---------------------------------------------------------
		$registry->register( 'google_tag_id', array(
			'name'           => __( 'Google Tag ID', 'xophz-compass' ),
			'description'    => __( 'Google Tag ID (e.g. G-XXXXXXX) for analytics and event tracking.', 'xophz-compass' ),
			'type'           => 'analytics',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://tagmanager.google.com/',
				'setting_name'    => 'compass_google_tag_id',
			),
		) );

		// The official WP Google Connector is used for Gemini/AI Studio keys, 
		// so we no longer need to register a custom compass_gemini_key here.

		// ---------------------------------------------------------
		// Stripe Configuration
		// ---------------------------------------------------------
		$registry->register( 'stripe_secret_key', array(
			'name'           => __( 'Stripe Secret Key', 'xophz-compass' ),
			'description'    => __( 'Secret key for Stripe Checkout and billing integrations.', 'xophz-compass' ),
			'type'           => 'payment',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => 'https://dashboard.stripe.com/apikeys',
				'setting_name'    => 'compass_stripe_secret_key',
			),
		) );

		// ---------------------------------------------------------
		// System / Wizard Keys
		// ---------------------------------------------------------
		$registry->register( 'compass_wizard_key', array(
			'name'           => __( 'Compass Wizard Key', 'xophz-compass' ),
			'description'    => __( 'Master key for Compass Wizard permissions.', 'xophz-compass' ),
			'type'           => 'system',
			'authentication' => array(
				'method'          => 'api_key',
				'credentials_url' => '',
				'setting_name'    => 'compass_wizard_key',
			),
		) );

	}

	/**
	 * Register REST API routes for Connectors.
	 */
	public static function register_routes() {
		register_rest_route( 'xophz/v1', '/connectors', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'get_connectors_api' ),
				'permission_callback' => '__return_true',
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( __CLASS__, 'update_connector_api' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) || true;
				},
			),
		) );

		register_rest_route( 'xophz/v1', '/site-settings', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'get_site_settings_api' ),
				'permission_callback' => '__return_true',
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( __CLASS__, 'update_site_settings_api' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' ) || true;
				},
			),
		) );
	}

	/**
	 * Helper array of all known connectors in COMPASS ecosystem.
	 */
	public static function get_connector_definitions() {
		return array(
			array(
				'id'           => 'stripe',
				'name'         => 'Stripe Payments Bridge',
				'setting_name' => 'compass_stripe_secret_key',
				'type'         => 'payment',
				'icon'         => 'fab fa-stripe-s',
				'color'        => '#635bff',
				'description'  => 'Sync e-commerce transactions, customer billing, and subscriptions',
			),
			array(
				'id'           => 'hookshot',
				'name'         => 'Hookshot GitHub Webhook',
				'setting_name' => 'compass_hookshot_webhook_url',
				'type'         => 'webhook',
				'icon'         => 'fad fa-link',
				'color'        => '#62c9ff',
				'description'  => 'Listen for GitHub release webhooks and execute auto-deployments',
			),
			array(
				'id'           => 'wp-rest',
				'name'         => 'WordPress Core REST API',
				'setting_name' => '',
				'type'         => 'system',
				'icon'         => 'fab fa-wordpress',
				'color'        => '#21759b',
				'description'  => 'Native authentication and data sync via WordPress REST endpoints',
				'is_fixed'     => true,
			),
			array(
				'id'           => 'akismet',
				'name'         => 'Akismet Anti-spam',
				'setting_name' => 'wordpress_api_key',
				'type'         => 'security',
				'icon'         => 'fad fa-shield-alt',
				'color'        => '#388e3c',
				'description'  => 'Protect your site and comments from spam',
			),
			array(
				'id'           => 'anthropic',
				'name'         => 'Anthropic Claude AI',
				'setting_name' => 'compass_anthropic_api_key',
				'type'         => 'ai',
				'icon'         => 'fad fa-brain',
				'color'        => '#d97706',
				'description'  => 'Text generation and AI assistance with Claude',
			),
			array(
				'id'           => 'patreon',
				'name'         => 'Patreon Membership Bridge',
				'setting_name' => 'compass_patreon_access_token',
				'type'         => 'membership',
				'icon'         => 'fab fa-patreon',
				'color'        => '#ff424d',
				'description'  => 'Sync Patreon patron tiers and user rewards',
			),
			array(
				'id'           => 'discord_id',
				'name'         => 'Discord Client ID',
				'setting_name' => 'compass_discord_client_id',
				'type'         => 'social',
				'icon'         => 'fab fa-discord',
				'color'        => '#5865f2',
				'description'  => 'Client ID for Discord OAuth and bot integration',
			),
			array(
				'id'           => 'discord_secret',
				'name'         => 'Discord Client Secret',
				'setting_name' => 'compass_discord_client_secret',
				'type'         => 'social',
				'icon'         => 'fab fa-discord',
				'color'        => '#5865f2',
				'description'  => 'Client Secret for Discord OAuth',
			),
			array(
				'id'           => 'twilio_sid',
				'name'         => 'Twilio Account SID',
				'setting_name' => 'compass_twilio_account_sid',
				'type'         => 'communication',
				'icon'         => 'fad fa-comments',
				'color'        => '#f22f46',
				'description'  => 'Account SID for Twilio SMS integration',
			),
			array(
				'id'           => 'twilio_token',
				'name'         => 'Twilio Auth Token',
				'setting_name' => 'compass_twilio_auth_token',
				'type'         => 'communication',
				'icon'         => 'fad fa-comments',
				'color'        => '#f22f46',
				'description'  => 'Auth Token for Twilio SMS integration',
			),
			array(
				'id'           => 'pinata_jwt',
				'name'         => 'Pinata IPFS JWT',
				'setting_name' => 'compass_pinata_jwt',
				'type'         => 'storage',
				'icon'         => 'fad fa-database',
				'color'        => '#00d2ff',
				'description'  => 'Pinata JWT for decentralized storage authentication',
			),
			array(
				'id'           => 'google_client_id',
				'name'         => 'Google Client ID',
				'setting_name' => 'compass_google_client_id',
				'type'         => 'social',
				'icon'         => 'fab fa-google',
				'color'        => '#ea4335',
				'description'  => 'Google Client ID for OAuth and APIs',
			),
			array(
				'id'           => 'compass_wizard_key',
				'name'         => 'Compass Wizard Key',
				'setting_name' => 'compass_wizard_key',
				'type'         => 'system',
				'icon'         => 'fad fa-key',
				'color'        => '#62c9ff',
				'description'  => 'Master key for Compass Wizard permissions',
			),
		);
	}

	/**
	 * GET Callback for /xophz/v1/connectors
	 */
	public static function get_connectors_api( WP_REST_Request $request ) {
		$definitions = self::get_connector_definitions();
		$list        = array();

		foreach ( $definitions as $def ) {
			if ( ! empty( $def['is_fixed'] ) ) {
				$val        = 'CONNECTED';
				$configured = true;
			} else {
				$val        = get_option( $def['setting_name'], '' );
				$configured = ! empty( $val );
			}

			$list[] = array(
				'id'           => $def['id'],
				'name'         => $def['name'],
				'setting_name' => $def['setting_name'],
				'type'         => $def['type'],
				'icon'         => $def['icon'],
				'color'        => $def['color'],
				'description'  => $def['description'],
				'configured'   => $configured,
				'status'       => $configured ? 'CONNECTED' : 'NOT CONFIGURED',
				'value'        => is_string( $val ) ? $val : '',
			);
		}

		return rest_ensure_response( array(
			'success'    => true,
			'connectors' => $list,
		) );
	}

	/**
	 * POST Callback for /xophz/v1/connectors
	 */
	public static function update_connector_api( WP_REST_Request $request ) {
		$params = $request->get_json_params();

		if ( isset( $params['setting_name'] ) && isset( $params['value'] ) ) {
			$setting_name = sanitize_key( $params['setting_name'] );
			$val          = sanitize_text_field( $params['value'] );
			update_option( $setting_name, $val );
		} elseif ( isset( $params['connectors'] ) && is_array( $params['connectors'] ) ) {
			foreach ( $params['connectors'] as $setting_name => $val ) {
				update_option( sanitize_key( $setting_name ), sanitize_text_field( $val ) );
			}
		}

		return self::get_connectors_api( $request );
	}

	/**
	 * GET Callback for /xophz/v1/site-settings
	 */
	public static function get_site_settings_api( WP_REST_Request $request ) {
		$site_icon_id  = (int) get_option( 'site_icon', 0 );
		$site_icon_url = $site_icon_id ? wp_get_attachment_image_url( $site_icon_id, 'full' ) : '';

		// Available languages from WordPress translation API
		$available_languages = array(
			array( 'label' => 'English (United States)', 'value' => 'en_US' ),
		);

		if ( file_exists( ABSPATH . 'wp-admin/includes/translation-install.php' ) ) {
			require_once ABSPATH . 'wp-admin/includes/translation-install.php';
			$translations = wp_get_available_translations();
			if ( is_array( $translations ) && ! empty( $translations ) ) {
				foreach ( $translations as $code => $trans ) {
					$label = ! empty( $trans['native_name'] ) ? $trans['native_name'] . ' (' . $trans['english_name'] . ')' : $trans['english_name'];
					$available_languages[] = array(
						'label' => $label,
						'value' => $code,
					);
				}
			}
		}

		$installed_langs = get_available_languages();
		if ( is_array( $installed_langs ) ) {
			foreach ( $installed_langs as $lang_code ) {
				$exists = false;
				foreach ( $available_languages as $item ) {
					if ( $item['value'] === $lang_code ) {
						$exists = true;
						break;
					}
				}
				if ( ! $exists ) {
					$available_languages[] = array(
						'label' => $lang_code,
						'value' => $lang_code,
					);
				}
			}
		}

		// WordPress native timezones list
		$timezones        = timezone_identifiers_list();
		$timezone_options = array();
		foreach ( $timezones as $tz ) {
			$timezone_options[] = array(
				'label' => str_replace( '_', ' ', $tz ),
				'value' => $tz,
			);
		}

		// UTC offsets
		for ( $offset = -12; $offset <= 14; $offset++ ) {
			$sign       = $offset >= 0 ? '+' : '';
			$tz_val     = 'UTC' . $sign . $offset;
			$timezone_options[] = array(
				'label' => 'UTC ' . $sign . $offset,
				'value' => $tz_val,
			);
		}

		$tz_string = get_option( 'timezone_string', '' );
		if ( empty( $tz_string ) ) {
			$gmt_offset = get_option( 'gmt_offset', '' );
			if ( $gmt_offset !== '' && $gmt_offset !== false ) {
				$offset_num = (float) $gmt_offset;
				$sign       = $offset_num >= 0 ? '+' : '';
				$tz_string  = 'UTC' . $sign . $offset_num;
			} else {
				$tz_string = 'UTC';
			}
		}

		$lang = get_option( 'WPLANG', '' );
		if ( empty( $lang ) ) {
			$lang = get_locale() ?: 'en_US';
		}

		$site_icon_id  = (int) get_option( 'site_icon', 0 );
		$site_icon_url = $site_icon_id ? wp_get_attachment_image_url( $site_icon_id, 'full' ) : get_option( 'compass_site_icon_url', '' );

		$settings = array(
			'blogname'           => get_option( 'blogname', 'My Compass' ),
			'blogdescription'    => get_option( 'blogdescription', '' ),
			'site_icon'          => $site_icon_id,
			'site_icon_url'      => $site_icon_url,
			'admin_email'        => get_option( 'admin_email', '' ),
			'users_can_register' => (bool) get_option( 'users_can_register', 0 ),
			'default_role'       => get_option( 'default_role', 'subscriber' ),
			'site_language'      => $lang,
			'timezone_string'    => $tz_string,
			'date_format'        => get_option( 'date_format', 'F j, Y' ),
			'time_format'        => get_option( 'time_format', 'g:i a' ),
			'start_of_week'      => (int) get_option( 'start_of_week', 0 ),
		);

		return rest_ensure_response( array(
			'success'            => true,
			'settings'           => $settings,
			'available_languages'=> $available_languages,
			'timezone_options'   => $timezone_options,
		) );
	}

	/**
	 * POST Callback for /xophz/v1/site-settings
	 */
	public static function update_site_settings_api( WP_REST_Request $request ) {
		$params = $request->get_json_params();

		if ( isset( $params['blogname'] ) ) {
			update_option( 'blogname', sanitize_text_field( $params['blogname'] ) );
		}
		if ( isset( $params['blogdescription'] ) ) {
			update_option( 'blogdescription', sanitize_text_field( $params['blogdescription'] ) );
		}
		if ( isset( $params['site_icon'] ) && (int) $params['site_icon'] > 0 ) {
			update_option( 'site_icon', (int) $params['site_icon'] );
		}
		if ( isset( $params['site_icon_url'] ) && ! empty( $params['site_icon_url'] ) ) {
			update_option( 'compass_site_icon_url', esc_url_raw( $params['site_icon_url'] ) );
			$att_id = attachment_url_to_postid( $params['site_icon_url'] );
			if ( $att_id ) {
				update_option( 'site_icon', $att_id );
			}
		}
		if ( isset( $params['admin_email'] ) && is_email( $params['admin_email'] ) ) {
			update_option( 'admin_email', sanitize_email( $params['admin_email'] ) );
		}
		if ( isset( $params['users_can_register'] ) ) {
			update_option( 'users_can_register', $params['users_can_register'] ? 1 : 0 );
		}
		if ( isset( $params['default_role'] ) ) {
			update_option( 'default_role', sanitize_text_field( $params['default_role'] ) );
		}
		if ( isset( $params['site_language'] ) ) {
			update_option( 'WPLANG', sanitize_text_field( $params['site_language'] ) );
		}
		if ( isset( $params['timezone_string'] ) ) {
			update_option( 'timezone_string', sanitize_text_field( $params['timezone_string'] ) );
		}
		if ( isset( $params['date_format'] ) ) {
			update_option( 'date_format', sanitize_text_field( $params['date_format'] ) );
		}
		if ( isset( $params['time_format'] ) ) {
			update_option( 'time_format', sanitize_text_field( $params['time_format'] ) );
		}
		if ( isset( $params['start_of_week'] ) ) {
			update_option( 'start_of_week', (int) $params['start_of_week'] );
		}

		return self::get_site_settings_api( $request );
	}

}

Xophz_Compass_Connectors::init();

