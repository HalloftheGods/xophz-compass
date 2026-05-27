<?php

/**
 * Provides a centralized API for sending SMS messages via Twilio.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */
class Xophz_Compass_Twilio_API {

	public static function init() {
		add_action( 'xophz_compass_send_sms', array( __CLASS__, 'handle_send_sms_action' ), 10, 2 );
	}

	/**
	 * Send an SMS using the global Twilio connectors.
	 *
	 * @param string $to      The destination phone number.
	 * @param string $message The body of the text message.
	 * @return bool|WP_Error  True on success, WP_Error on failure.
	 */
	public static function send_sms( $to, $message ) {
		$sid      = get_option( 'compass_twilio_account_sid' );
		$token    = get_option( 'compass_twilio_auth_token' );
		$from_num = get_option( 'compass_twilio_phone_number' );

		if ( empty( $sid ) || empty( $token ) || empty( $from_num ) ) {
			return new WP_Error( 'missing_twilio_credentials', __( 'Twilio credentials or phone number are missing from Connectors.', 'xophz-compass' ) );
		}

		// Ensure number is somewhat formatted
		$to = preg_replace( '/[^0-9+]/', '', $to );

		$twilio_url = "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json";
		$args = array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( "$sid:$token" )
			),
			'body'    => array(
				'To'   => $to,
				'From' => $from_num,
				'Body' => $message,
			),
		);

		$response = wp_remote_post( $twilio_url, $args );

		if ( is_wp_error( $response ) ) {
			error_log( 'COMPASS Twilio Error: ' . $response->get_error_message() );
			return $response;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['status'] ) && in_array( $data['status'], array( 'queued', 'sent', 'delivered' ) ) ) {
			return true;
		} else {
			$error_msg = isset( $data['message'] ) ? $data['message'] : 'Unknown Twilio error';
			error_log( 'COMPASS Twilio Error: ' . $error_msg );
			return new WP_Error( 'twilio_api_error', $error_msg );
		}
	}

	/**
	 * Action hook handler for `xophz_compass_send_sms`.
	 *
	 * @param string $to
	 * @param string $message
	 */
	public static function handle_send_sms_action( $to, $message ) {
		self::send_sms( $to, $message );
	}
}

Xophz_Compass_Twilio_API::init();
