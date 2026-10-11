<?php
/**
 * POST /xophz/v1/passport/pin: pins the caller's GlobalPlayerProfile JSON to IPFS through Pinata.
 *
 * The Pinata JWT is read on the server through the `pinata_jwt` WP connector and is only ever sent
 * to api.pinata.cloud. The response carries the CID or an error, never a credential. Logged-in
 * users only; WordPress core's cookie authentication enforces the X-WP-Nonce header, the same
 * contract as the sibling /passport routes.
 *
 * Request:  { "profile": GlobalPlayerProfile }
 * Response: 200 { "cid": "<IPFS CID>" }
 * Errors:   400 rest_invalid_profile, 401 rest_forbidden, 429 compass_pin_rate_limited,
 *           502 compass_pinata_unreachable | compass_pinata_failed,
 *           503 compass_pinata_not_configured (no JWT set; never a placeholder CID).
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Passport_Pin_API {

	const CONNECTOR_ID = 'pinata_jwt';
	const PINATA_URL   = 'https://api.pinata.cloud/pinning/pinJSONToIPFS';
	const MAX_BYTES    = 16384;
	const RATE_LIMIT   = 10;
	const RATE_WINDOW  = 3600;

	const STRING_FIELDS   = array( 'youmeos_id', 'alias', 'display_name', 'avatar_cid', 'home_planet', 'config_cid' );
	const REQUIRED_FIELDS = array( 'youmeos_id', 'alias' );
	const OPTIONAL_FIELDS = array( 'portrait_cid' );

	public function register_routes() {
		register_rest_route( 'xophz/v1', '/passport/pin', array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'pin_profile' ),
				'permission_callback' => array( $this, 'can_pin' ),
			),
		) );
	}

	public function can_pin() {
		if ( is_user_logged_in() ) {
			return true;
		}
		return new WP_Error( 'rest_forbidden', 'Log in to publish a passport profile.', array( 'status' => 401 ) );
	}

	public function pin_profile( WP_REST_Request $request ) {
		$jwt = Xophz_Compass_Connector_Key::get( self::CONNECTOR_ID );
		if ( '' === $jwt ) {
			return new WP_Error(
				'compass_pinata_not_configured',
				'IPFS pinning is not configured. Set the Pinata JWT in the WordPress Connectors screen.',
				array( 'status' => 503 )
			);
		}

		$params  = $request->get_json_params();
		$profile = is_array( $params ) ? ( $params['profile'] ?? null ) : null;
		$problem = self::profile_problem( $profile );
		if ( '' !== $problem ) {
			return new WP_Error( 'rest_invalid_profile', $problem, array( 'status' => 400 ) );
		}

		$rate_key   = 'passport_pin_' . get_current_user_id();
		$is_allowed = Xophz_Compass_Security::check_rate_limit( $rate_key, self::RATE_LIMIT, self::RATE_WINDOW );
		if ( ! $is_allowed ) {
			return new WP_Error( 'compass_pin_rate_limited', 'Too many pin requests. Try again in an hour.', array( 'status' => 429 ) );
		}

		return self::pin_to_pinata( $profile, $jwt );
	}

	/**
	 * Runtime guard for a GlobalPlayerProfile. Returns '' when valid, else what is wrong.
	 *
	 * @param mixed $profile Decoded request value.
	 */
	public static function profile_problem( $profile ): string {
		$is_object = is_array( $profile ) && ! empty( $profile ) && ! wp_is_numeric_array( $profile );
		if ( ! $is_object ) {
			return 'Body must be { "profile": { ... } }.';
		}

		$known   = array_merge( self::STRING_FIELDS, self::OPTIONAL_FIELDS, array( 'mmo' ) );
		$unknown = array_diff( array_keys( $profile ), $known );
		if ( ! empty( $unknown ) ) {
			return 'Unknown profile field: ' . sanitize_key( (string) reset( $unknown ) ) . '.';
		}

		foreach ( self::STRING_FIELDS as $field ) {
			if ( ! isset( $profile[ $field ] ) || ! is_string( $profile[ $field ] ) ) {
				return "Profile field {$field} must be a string.";
			}
		}
		foreach ( self::REQUIRED_FIELDS as $field ) {
			if ( '' === trim( $profile[ $field ] ) ) {
				return "Profile field {$field} must not be empty.";
			}
		}
		foreach ( self::OPTIONAL_FIELDS as $field ) {
			if ( isset( $profile[ $field ] ) && ! is_string( $profile[ $field ] ) ) {
				return "Profile field {$field} must be a string.";
			}
		}

		$mmo_problem = self::mmo_problem( $profile['mmo'] ?? null );
		if ( '' !== $mmo_problem ) {
			return $mmo_problem;
		}

		$is_small = strlen( (string) wp_json_encode( $profile ) ) <= self::MAX_BYTES;
		return $is_small ? '' : 'Profile is larger than ' . self::MAX_BYTES . ' bytes.';
	}

	private static function mmo_problem( $mmo ): string {
		if ( ! is_array( $mmo ) ) {
			return 'Profile field mmo must be an object.';
		}

		$xp       = $mmo['xp'] ?? null;
		$is_xp_ok = is_array( $xp )
			&& is_numeric( $xp['total'] ?? null )
			&& is_numeric( $xp['level'] ?? null )
			&& is_string( $xp['title'] ?? null );
		if ( ! $is_xp_ok ) {
			return 'Profile field mmo.xp must be { total: number, level: number, title: string }.';
		}

		$badges       = $mmo['badges'] ?? null;
		$is_badges_ok = is_array( $badges ) && ( empty( $badges ) || wp_is_numeric_array( $badges ) )
			&& count( array_filter( $badges, 'is_string' ) ) === count( $badges );
		if ( ! $is_badges_ok ) {
			return 'Profile field mmo.badges must be a list of strings.';
		}

		$proof       = $mmo['proof'] ?? null;
		$is_proof_ok = null === $proof || ( is_array( $proof )
			&& is_string( $proof['issuer'] ?? null )
			&& is_string( $proof['signature'] ?? null )
			&& is_numeric( $proof['timestamp'] ?? null ) );
		return $is_proof_ok ? '' : 'Profile field mmo.proof must be { issuer: string, signature: string, timestamp: number }.';
	}

	private static function pin_to_pinata( array $profile, string $jwt ) {
		$body = array(
			'pinataOptions'  => array( 'cidVersion' => 1 ),
			'pinataMetadata' => array(
				'name'      => 'YouMeOS Profile - ' . $profile['alias'],
				'keyvalues' => array(
					'app' => 'YouMeOS',
					'id'  => $profile['youmeos_id'],
				),
			),
			'pinataContent'  => $profile,
		);

		$response = wp_remote_post( self::PINATA_URL, array(
			'timeout' => 20,
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $jwt,
			),
			'body'    => wp_json_encode( $body ),
		) );

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'compass_pinata_unreachable', 'Could not reach Pinata.', array( 'status' => 502 ) );
		}

		$status = (int) wp_remote_retrieve_response_code( $response );
		$data   = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		$cid    = is_array( $data ) && is_string( $data['IpfsHash'] ?? null ) ? $data['IpfsHash'] : '';
		$is_ok  = $status >= 200 && $status < 300 && 1 === preg_match( '/^[A-Za-z0-9]{46,128}$/', $cid );
		if ( ! $is_ok ) {
			return new WP_Error( 'compass_pinata_failed', sprintf( 'Pinata did not pin the profile (HTTP %d).', $status ), array( 'status' => 502 ) );
		}

		return rest_ensure_response( array( 'cid' => $cid ) );
	}
}
