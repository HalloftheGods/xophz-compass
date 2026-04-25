<?php

/**
 * The Gemini API Wrapper
 *
 * Handles the REST API endpoints to serve as a proxy/wrapper for the
 * Google Gemini backend API. This allows YouMeOS sparks (like Magic Formula)
 * to securely communicate with Gemini without exposing API keys on the frontend.
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

class Xophz_Compass_Gemini_API {

	/**
	 * Register the REST API routes for the Gemini wrapper.
	 */
	public function register_routes() {
		register_rest_route( 'xophz/v1', '/gemini/generate', array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'generate_content' ),
				'permission_callback' => function() {
					return is_user_logged_in(); // Require authentication
				},
				'args'                => array(
					'prompt' => array(
						'required' => true,
						'type'     => 'string',
					),
					'system_instruction' => array(
						'required' => false,
						'type'     => 'string',
					),
					'model' => array(
						'required' => false,
						'type'     => 'string',
						'default'  => 'gemini-2.5-flash',
					)
				),
			)
		) );
	}

	/**
	 * Retrieve the API key for Gemini.
	 * 
	 * @return string API key or empty string if not found.
	 */
	private function get_api_key() {
		if ( defined( 'GEMINI_API_KEY' ) ) {
			return GEMINI_API_KEY;
		}
		if ( ! empty( $_ENV['GEMINI_API_KEY'] ) ) {
			return $_ENV['GEMINI_API_KEY'];
		}
		if ( ! empty( getenv( 'GEMINI_API_KEY' ) ) ) {
			return getenv( 'GEMINI_API_KEY' );
		}
		return get_option( 'xophz_gemini_api_key', '' );
	}

	/**
	 * Handle the generation request.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function generate_content( $request ) {
		$api_key = $this->get_api_key();

		if ( empty( $api_key ) ) {
			return new WP_Error( 'gemini_missing_key', 'Gemini API key is not configured.', array( 'status' => 500 ) );
		}

		$prompt = $request->get_param( 'prompt' );
		$system_instruction = $request->get_param( 'system_instruction' );
		$model = $request->get_param( 'model' );

		// Construct the Gemini API endpoint
		$endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$api_key}";

		// Construct the payload matching Gemini API structure
		$payload = array(
			'contents' => array(
				array(
					'parts' => array(
						array(
							'text' => $prompt
						)
					)
				)
			)
		);

		if ( ! empty( $system_instruction ) ) {
			$payload['system_instruction'] = array(
				'parts' => array(
					array(
						'text' => $system_instruction
					)
				)
			);
		}

		// Make the request
		$response = wp_remote_post( $endpoint, array(
			'headers' => array(
				'Content-Type' => 'application/json',
			),
			'body'    => wp_json_encode( $payload ),
			'timeout' => 60, // Give it time to generate
		) );

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'gemini_api_error', 'Failed to connect to Gemini API: ' . $response->get_error_message(), array( 'status' => 500 ) );
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		$data = json_decode( $response_body, true );

		if ( $response_code >= 400 ) {
			$error_message = isset( $data['error']['message'] ) ? $data['error']['message'] : 'Unknown Gemini API Error';
			return new WP_Error( 'gemini_api_error', $error_message, array( 'status' => $response_code ) );
		}

		// Try to extract the text content directly for easier consumption
		$generated_text = '';
		if ( isset( $data['candidates'][0]['content']['parts'][0]['text'] ) ) {
			$generated_text = $data['candidates'][0]['content']['parts'][0]['text'];
		}

		return rest_ensure_response( array(
			'success' => true,
			'text'    => $generated_text,
			'raw'     => $data, // Include raw response just in case
		) );
	}
}
