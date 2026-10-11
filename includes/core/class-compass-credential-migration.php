<?php
/**
 * One-time move of credentials out of option names the core plugin no longer reads, into the
 * connector that now owns each one (the Hookshot_Connectors migration pattern).
 *
 * For each connector, the first non-empty legacy option, in the order the old code read them,
 * fills the connector's own option when that option is empty. A legacy option is then deleted
 * only when it is empty or holds the same value as the connector option, so a credential is
 * never deleted without a copy. A legacy value that differs from a value the connector already
 * held stays where it is for the owner to review; nothing reads it any more.
 *
 * Values are only compared and copied: never echoed, logged or returned.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Credential_Migration {

	/** Option holding the connector ids already migrated (autoloaded: read on every request). */
	const DONE_OPTION = 'xophz_compass_legacy_credentials_migrated';

	/**
	 * Connector id => retired option names, in the precedence the old code read them.
	 *
	 * Gemini: Xophz_Compass_HTTP and the Gemini proxy read four extra option names. Anthropic: the
	 * connectors list fell back to compass_anthropic_api_key. Stripe: the Stripe proxy read
	 * xophz_compass_stripe_secret_key and Event Horizon read stripe_secret_key before #5954.
	 * Discord and Google OAuth: Event Horizon read these option names before #5954.
	 */
	const LEGACY_OPTIONS = array(
		'google_gemini_api_key'      => array( 'ai_google_api_key', 'compass_gemini_api_key', 'xophz_gemini_api_key', 'gemini_api_key' ),
		'anthropic'                  => array( 'compass_anthropic_api_key' ),
		'stripe_secret_key'          => array( 'xophz_compass_stripe_secret_key', 'stripe_secret_key' ),
		'discord_client_id'          => array( 'discord_client_id' ),
		'discord_client_secret'      => array( 'discord_client_secret' ),
		'google_oauth_client_id'     => array( 'google_client_id' ),
		'google_oauth_client_secret' => array( 'google_client_secret' ),
	);

	/**
	 * Runs on `init` after core registers connectors. A connector that is not registered yet is
	 * retried on a later request; every finished connector id is recorded so it never runs twice.
	 */
	public static function run(): void {
		$done    = get_option( self::DONE_OPTION, array() );
		$done    = is_array( $done ) ? $done : array();
		$pending = array_diff_key( self::LEGACY_OPTIONS, array_flip( $done ) );
		if ( empty( $pending ) ) {
			return;
		}

		foreach ( $pending as $connector_id => $legacy_options ) {
			if ( self::migrate( $connector_id, $legacy_options ) ) {
				$done[] = $connector_id;
			}
		}

		update_option( self::DONE_OPTION, array_values( array_unique( $done ) ), true );
	}

	/**
	 * @param string   $connector_id   Connector that owns the credential now.
	 * @param string[] $legacy_options Retired option names, highest precedence first.
	 * @return bool False when the connector is not registered, so the move waits for a later run.
	 */
	private static function migrate( string $connector_id, array $legacy_options ): bool {
		$setting = Xophz_Compass_Connector_Key::setting_name( $connector_id );
		if ( '' === $setting ) {
			return false;
		}

		$legacy_options = array_values( array_diff( $legacy_options, array( $setting ) ) );
		$current        = self::read( $setting );

		if ( '' === $current ) {
			$current = self::adopt_first( $setting, $legacy_options );
		}

		foreach ( $legacy_options as $legacy ) {
			$value         = self::read( $legacy );
			$is_disposable = '' === $value || $value === $current;
			if ( $is_disposable ) {
				delete_option( $legacy );
			}
		}

		return true;
	}

	/**
	 * Copies the first non-empty legacy value into the connector option and returns it, or ''.
	 */
	private static function adopt_first( string $setting, array $legacy_options ): string {
		foreach ( $legacy_options as $legacy ) {
			$value = self::read( $legacy );
			if ( '' !== $value ) {
				update_option( $setting, $value, false );
				return $value;
			}
		}

		return '';
	}

	private static function read( string $option ): string {
		$value = get_option( $option, '' );
		return is_scalar( $value ) ? trim( (string) $value ) : '';
	}
}
