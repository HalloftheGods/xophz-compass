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

}

Xophz_Compass_Connectors::init();
