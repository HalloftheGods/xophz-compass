<?php

/**
 * The Stripe Checkout API
 *
 * Handles the REST API endpoints to generate Stripe Checkout Sessions
 * for buying My Compass licenses or the Developer UI Kit.
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

class Xophz_Compass_Stripe_API {

	/**
	 * Register the REST API routes for Stripe.
	 */
	public function register_routes() {
		register_rest_route( 'xophz/v1', '/stripe/checkout', array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_checkout_session' ),
				'permission_callback' => '__return_true', // Allow public checkouts
				'args'                => array(
					'price' => array(
						'required' => true,
						'type'     => 'integer',
					),
					'license' => array(
						'required' => true,
						'type'     => 'string',
					),
					'success_url' => array(
						'required' => false,
						'type'     => 'string',
					),
					'cancel_url' => array(
						'required' => false,
						'type'     => 'string',
					)
				),
			)
		) );
	}

	/**
	 * Retrieve the Stripe secret key.
	 */
	private function get_secret_key() {
		if ( defined( 'STRIPE_SECRET_KEY' ) ) {
			return STRIPE_SECRET_KEY;
		}
		if ( ! empty( $_ENV['STRIPE_SECRET_KEY'] ) ) {
			return $_ENV['STRIPE_SECRET_KEY'];
		}
		$key = get_option( 'compass_stripe_secret_key', '' );
		if ( ! empty( $key ) ) {
			return $key;
		}
		return get_option( 'xophz_compass_stripe_secret_key', '' );
	}

	/**
	 * Create a Stripe Checkout Session or returns a mock redirect.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_checkout_session( $request ) {
		$price = intval( $request->get_param( 'price' ) );
		$license = sanitize_text_field( $request->get_param( 'license' ) );
		$success_url = esc_url_raw( $request->get_param( 'success_url' ) );
		$cancel_url = esc_url_raw( $request->get_param( 'cancel_url' ) );

		if ( empty( $success_url ) ) {
			$success_url = home_url( '/' );
		}
		if ( empty( $cancel_url ) ) {
			$cancel_url = home_url( '/' );
		}

		$secret_key = $this->get_secret_key();

		// Fallback to mock session if Stripe Secret Key is not configured
		if ( empty( $secret_key ) || strpos( $secret_key, 'sk_test_Mock' ) === 0 ) {
			return rest_ensure_response( array(
				'url'      => add_query_arg(
					array(
						'mock_checkout' => '1',
						'price'         => $price,
						'license'       => urlencode( $license ),
						'success_url'   => urlencode( $success_url ),
						'cancel_url'    => urlencode( $cancel_url )
					),
					home_url( '/' )
				),
				'is_mock'  => true,
				'message'  => 'Stripe API key not configured. Redirecting to mock checkout page.'
			) );
		}

		// Perform real Stripe checkout session creation
		$response = wp_remote_post( 'https://api.stripe.com/v1/checkout/sessions', array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $secret_key,
				'Content-Type'  => 'application/x-www-form-urlencoded'
			),
			'body' => http_build_query( array(
				'payment_method_types' => array( 'card' ),
				'line_items' => array(
					array(
						'price_data' => array(
							'currency' => 'usd',
							'product_data' => array(
								'name' => 'My Compass - ' . $license
							),
							'unit_amount' => $price * 100 // convert to cents
						),
						'quantity' => 1
					)
				),
				'mode' => 'payment',
				'success_url' => $success_url,
				'cancel_url' => $cancel_url
			) )
		) );

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'stripe_api_error', $response->get_error_message(), array( 'status' => 500 ) );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $body['error'] ) ) {
			return new WP_Error( 'stripe_checkout_error', $body['error']['message'], array( 'status' => 400 ) );
		}

		return rest_ensure_response( array(
			'id'      => $body['id'],
			'url'     => $body['url'],
			'is_mock' => false
		) );
	}
}
