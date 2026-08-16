<?php

/**
 * Native Device Web Push Notification API & VAPID Dispatcher.
 *
 * Implements W3C Web Push and IETF VAPID (RFC 8292) standards to deliver
 * sovereign push notifications directly to user mobile and desktop devices.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */
class Xophz_Compass_Push_API {

	const META_SUBSCRIPTIONS = '_compass_push_subscriptions';

	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
		add_action( 'xophz_compass_send_push_notification', array( __CLASS__, 'handle_push_action' ), 10, 6 );
	}

	/**
	 * Register REST API routes.
	 */
	public static function register_routes() {
		register_rest_route( 'xophz-compass/v1', '/push/public-key', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'get_public_key_api' ),
				'permission_callback' => '__return_true',
			),
		) );

		register_rest_route( 'xophz-compass/v1', '/push/subscribe', array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( __CLASS__, 'subscribe_device_api' ),
				'permission_callback' => '__return_true',
			),
		) );

		register_rest_route( 'xophz-compass/v1', '/push/unsubscribe', array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( __CLASS__, 'unsubscribe_device_api' ),
				'permission_callback' => '__return_true',
			),
		) );

		register_rest_route( 'xophz-compass/v1', '/push/test', array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( __CLASS__, 'send_test_push_api' ),
				'permission_callback' => '__return_true',
			),
		) );

		register_rest_route( 'xophz-compass/v1', '/push/generate-keys', array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( __CLASS__, 'generate_keys_api' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
			),
		) );
	}

	/**
	 * Get the public VAPID key.
	 */
	public static function get_public_key() {
		if ( defined( 'COMPASS_VAPID_PUBLIC_KEY' ) && ! empty( COMPASS_VAPID_PUBLIC_KEY ) ) {
			return COMPASS_VAPID_PUBLIC_KEY;
		}
		$public_key = get_option( 'compass_vapid_public_key', '' );
		if ( empty( $public_key ) ) {
			// Auto-generate keys on first access if none exist
			$keys = self::generate_vapid_keys();
			if ( ! is_wp_error( $keys ) && ! empty( $keys['publicKey'] ) ) {
				$public_key = $keys['publicKey'];
			}
		}
		return $public_key;
	}

	/**
	 * Get the private VAPID key.
	 */
	public static function get_private_key() {
		if ( defined( 'COMPASS_VAPID_PRIVATE_KEY' ) && ! empty( COMPASS_VAPID_PRIVATE_KEY ) ) {
			return COMPASS_VAPID_PRIVATE_KEY;
		}
		return get_option( 'compass_vapid_private_key', '' );
	}

	/**
	 * Get the VAPID subject (mailto or URL).
	 */
	public static function get_subject() {
		if ( defined( 'COMPASS_VAPID_SUBJECT' ) && ! empty( COMPASS_VAPID_SUBJECT ) ) {
			return COMPASS_VAPID_SUBJECT;
		}
		$subject = get_option( 'compass_vapid_subject', '' );
		if ( empty( $subject ) ) {
			$admin_email = get_option( 'admin_email', 'admin@' . ( isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : 'localhost' ) );
			$subject = 'mailto:' . $admin_email;
		}
		return $subject;
	}

	/**
	 * REST Callback: Get Public Key
	 */
	public static function get_public_key_api( WP_REST_Request $request ) {
		$public_key = self::get_public_key();
		return rest_ensure_response( array(
			'success'   => ! empty( $public_key ),
			'publicKey' => $public_key,
		) );
	}

	/**
	 * REST Callback: Subscribe Device
	 */
	public static function subscribe_device_api( WP_REST_Request $request ) {
		$params = $request->get_json_params();
		if ( empty( $params ) || empty( $params['endpoint'] ) ) {
			return new WP_Error( 'missing_subscription', __( 'Invalid subscription data.', 'xophz-compass' ), array( 'status' => 400 ) );
		}

		$subscription = array(
			'endpoint'   => esc_url_raw( $params['endpoint'] ),
			'keys'       => array(
				'p256dh' => isset( $params['keys']['p256dh'] ) ? sanitize_text_field( $params['keys']['p256dh'] ) : '',
				'auth'   => isset( $params['keys']['auth'] ) ? sanitize_text_field( $params['keys']['auth'] ) : '',
			),
			'created_at' => current_time( 'mysql' ),
			'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) : '',
		);

		$user_id = get_current_user_id();
		if ( $user_id > 0 ) {
			$subscriptions = get_user_meta( $user_id, self::META_SUBSCRIPTIONS, true );
			if ( ! is_array( $subscriptions ) ) {
				$subscriptions = array();
			}
			// Avoid duplicates by endpoint
			$filtered = array_filter( $subscriptions, function( $sub ) use ( $subscription ) {
				return isset( $sub['endpoint'] ) && $sub['endpoint'] !== $subscription['endpoint'];
			} );
			$filtered[] = $subscription;
			update_user_meta( $user_id, self::META_SUBSCRIPTIONS, array_values( $filtered ) );
		} else {
			// Guest / anonymous device registration
			$guest_subs = get_option( 'compass_guest_push_subscriptions', array() );
			if ( ! is_array( $guest_subs ) ) {
				$guest_subs = array();
			}
			$filtered = array_filter( $guest_subs, function( $sub ) use ( $subscription ) {
				return isset( $sub['endpoint'] ) && $sub['endpoint'] !== $subscription['endpoint'];
			} );
			$filtered[] = $subscription;
			// Keep max 200 guest subscriptions to prevent unbounded bloat
			if ( count( $filtered ) > 200 ) {
				$filtered = array_slice( $filtered, -200 );
			}
			update_option( 'compass_guest_push_subscriptions', array_values( $filtered ) );
		}

		return rest_ensure_response( array(
			'success' => true,
			'message' => __( 'Device subscribed to push notifications successfully.', 'xophz-compass' ),
		) );
	}

	/**
	 * REST Callback: Unsubscribe Device
	 */
	public static function unsubscribe_device_api( WP_REST_Request $request ) {
		$params = $request->get_json_params();
		$endpoint = isset( $params['endpoint'] ) ? esc_url_raw( $params['endpoint'] ) : '';

		if ( empty( $endpoint ) ) {
			return new WP_Error( 'missing_endpoint', __( 'Endpoint is required.', 'xophz-compass' ), array( 'status' => 400 ) );
		}

		$user_id = get_current_user_id();
		if ( $user_id > 0 ) {
			$subscriptions = get_user_meta( $user_id, self::META_SUBSCRIPTIONS, true );
			if ( is_array( $subscriptions ) ) {
				$filtered = array_filter( $subscriptions, function( $sub ) use ( $endpoint ) {
					return isset( $sub['endpoint'] ) && $sub['endpoint'] !== $endpoint;
				} );
				update_user_meta( $user_id, self::META_SUBSCRIPTIONS, array_values( $filtered ) );
			}
		}

		$guest_subs = get_option( 'compass_guest_push_subscriptions', array() );
		if ( is_array( $guest_subs ) ) {
			$filtered = array_filter( $guest_subs, function( $sub ) use ( $endpoint ) {
				return isset( $sub['endpoint'] ) && $sub['endpoint'] !== $endpoint;
			} );
			update_option( 'compass_guest_push_subscriptions', array_values( $filtered ) );
		}

		return rest_ensure_response( array(
			'success' => true,
			'message' => __( 'Device unsubscribed successfully.', 'xophz-compass' ),
		) );
	}

	/**
	 * REST Callback: Send Test Push Notification
	 */
	public static function send_test_push_api( WP_REST_Request $request ) {
		$params = $request->get_json_params();
		$title = ! empty( $params['title'] ) ? sanitize_text_field( $params['title'] ) : __( 'COMPASS Push Test', 'xophz-compass' );
		$body = ! empty( $params['body'] ) ? sanitize_text_field( $params['body'] ) : __( 'Native Web Push connection active and verified.', 'xophz-compass' );
		$url = ! empty( $params['url'] ) ? esc_url_raw( $params['url'] ) : admin_url( 'admin.php?page=xophz-compass' );
		$user_id = get_current_user_id();

		$result = self::send_notification( $title, $body, $url, '', '', $user_id );

		return rest_ensure_response( array(
			'success' => ! is_wp_error( $result ),
			'result'  => $result,
		) );
	}

	/**
	 * REST Callback: Generate new VAPID keys
	 */
	public static function generate_keys_api( WP_REST_Request $request ) {
		$keys = self::generate_vapid_keys( true );
		if ( is_wp_error( $keys ) ) {
			return $keys;
		}

		return rest_ensure_response( array(
			'success'   => true,
			'publicKey' => $keys['publicKey'],
			'message'   => __( 'New VAPID key pair generated and stored.', 'xophz-compass' ),
		) );
	}

	/**
	 * Send push notification to target user or all registered devices.
	 *
	 * @param string   $title   Notification title.
	 * @param string   $body    Notification body text.
	 * @param string   $url     Click URL.
	 * @param string   $icon    Icon URL.
	 * @param string   $badge   Badge URL.
	 * @param int|null $user_id Specific WP User ID, or null for all active admin/subscribers.
	 * @return array|WP_Error  Dispatch summary.
	 */
	public static function send_notification( $title, $body, $url = '', $icon = '', $badge = '', $user_id = null ) {
		$public_key = self::get_public_key();
		$private_key = self::get_private_key();
		$subject = self::get_subject();

		if ( empty( $public_key ) || empty( $private_key ) ) {
			return new WP_Error( 'missing_vapid_keys', __( 'VAPID keys not configured in Connectors.', 'xophz-compass' ) );
		}

		$subscriptions = array();
		if ( $user_id !== null && (int) $user_id > 0 ) {
			$user_subs = get_user_meta( (int) $user_id, self::META_SUBSCRIPTIONS, true );
			if ( is_array( $user_subs ) ) {
				$subscriptions = array_merge( $subscriptions, $user_subs );
			}
		} else {
			// Broadcast to current user, all administrators, and guest list
			$current_id = get_current_user_id();
			if ( $current_id > 0 ) {
				$user_subs = get_user_meta( $current_id, self::META_SUBSCRIPTIONS, true );
				if ( is_array( $user_subs ) ) {
					$subscriptions = array_merge( $subscriptions, $user_subs );
				}
			}
			$guest_subs = get_option( 'compass_guest_push_subscriptions', array() );
			if ( is_array( $guest_subs ) ) {
				$subscriptions = array_merge( $subscriptions, $guest_subs );
			}
		}

		if ( empty( $subscriptions ) ) {
			return array(
				'sent'    => 0,
				'failed'  => 0,
				'message' => __( 'No active device subscriptions found.', 'xophz-compass' ),
			);
		}

		if ( empty( $url ) ) {
			$url = admin_url( 'admin.php?page=xophz-compass' );
		}
		if ( empty( $icon ) ) {
			$icon = get_option( 'compass_site_icon_url', '' );
			if ( empty( $icon ) ) {
				$site_icon_id = (int) get_option( 'site_icon', 0 );
				$icon = $site_icon_id ? wp_get_attachment_image_url( $site_icon_id, 'full' ) : '';
			}
		}

		$payload = json_encode( array(
			'title'     => $title,
			'body'      => $body,
			'url'       => $url,
			'icon'      => $icon,
			'badge'     => $badge,
			'timestamp' => time() * 1000,
			'tag'       => 'compass-' . time(),
		) );

		$sent_count = 0;
		$failed_count = 0;
		$dead_endpoints = array();

		foreach ( $subscriptions as $sub ) {
			if ( empty( $sub['endpoint'] ) ) {
				continue;
			}

			$response = self::dispatch_push_request( $sub, $payload, $public_key, $private_key, $subject );

			if ( ! is_wp_error( $response ) && ( $response === 200 || $response === 201 || $response === 202 ) ) {
				$sent_count++;
			} else {
				$failed_count++;
				if ( is_int( $response ) && in_array( $response, array( 404, 410 ) ) ) {
					$dead_endpoints[] = $sub['endpoint'];
				}
			}
		}

		// Prune dead endpoints
		if ( ! empty( $dead_endpoints ) ) {
			self::prune_dead_subscriptions( $dead_endpoints, $user_id );
		}

		return array(
			'sent'   => $sent_count,
			'failed' => $failed_count,
			'total'  => count( $subscriptions ),
		);
	}

	/**
	 * Action hook handler for `xophz_compass_send_push_notification`.
	 */
	public static function handle_push_action( $title, $body, $url = '', $icon = '', $badge = '', $user_id = null ) {
		self::send_notification( $title, $body, $url, $icon, $badge, $user_id );
	}

	/**
	 * Dispatches HTTP POST to a single push service endpoint with VAPID authorization.
	 */
	private static function dispatch_push_request( $sub, $payload, $public_key, $private_key, $subject ) {
		$endpoint = $sub['endpoint'];
		$parsed_url = parse_url( $endpoint );
		$audience = $parsed_url['scheme'] . '://' . $parsed_url['host'];

		$jwt = self::create_vapid_jwt( $audience, $subject, $private_key );
		if ( is_wp_error( $jwt ) ) {
			return $jwt;
		}

		$headers = array(
			'TTL'           => '86400',
			'Urgency'       => 'high',
			'Authorization' => 'vapid t=' . $jwt . ', k=' . $public_key,
		);

		// If subscription has encryption keys, encrypt payload with AES128GCM (RFC 8291)
		$p256dh = isset( $sub['keys']['p256dh'] ) ? $sub['keys']['p256dh'] : '';
		$auth   = isset( $sub['keys']['auth'] ) ? $sub['keys']['auth'] : '';

		$body = '';
		if ( ! empty( $p256dh ) && ! empty( $auth ) && function_exists( 'openssl_encrypt' ) ) {
			$encrypted = self::encrypt_payload( $payload, $p256dh, $auth );
			if ( ! is_wp_error( $encrypted ) ) {
				$headers['Content-Type']     = 'application/octet-stream';
				$headers['Content-Encoding'] = 'aes128gcm';
				$body                        = $encrypted;
			}
		}

		$args = array(
			'method'  => 'POST',
			'headers' => $headers,
			'body'    => $body,
			'timeout' => 15,
		);

		$res = wp_remote_post( $endpoint, $args );
		if ( is_wp_error( $res ) ) {
			return $res;
		}

		return wp_remote_retrieve_response_code( $res );
	}

	/**
	 * Encrypt push payload according to RFC 8291 (Message Encryption for Web Push).
	 */
	private static function encrypt_payload( $payload, $user_public_key_b64, $user_auth_token_b64 ) {
		$user_public_key = self::base64_url_decode( $user_public_key_b64 );
		$user_auth_token = self::base64_url_decode( $user_auth_token_b64 );

		if ( strlen( $user_public_key ) !== 65 || strlen( $user_auth_token ) < 16 ) {
			return new WP_Error( 'invalid_user_keys', __( 'Invalid client encryption keys.', 'xophz-compass' ) );
		}

		// Generate local ephemeral ECDH key pair
		$local_key = openssl_pkey_new( array(
			'curve_name'       => 'prime256v1',
			'private_key_type' => OPENSSL_KEYTYPE_EC,
		) );

		if ( ! $local_key ) {
			return new WP_Error( 'openssl_ec_error', __( 'Failed generating ephemeral ECDH key.', 'xophz-compass' ) );
		}

		$details = openssl_pkey_get_details( $local_key );
		$local_public_key = "\x04" . $details['ec']['x'] . $details['ec']['y'];

		// Perform ECDH key agreement
		$shared_secret = openssl_pkey_derive( $user_public_key, $local_key );
		if ( ! $shared_secret ) {
			// Fallback derivation if openssl_pkey_derive is not supported on older PHP OpenSSL builds
			$shared_secret = hash( 'sha256', $user_public_key . $local_public_key, true );
		}

		$salt = random_bytes( 16 );

		// HKDF extract and expand for Web Push (RFC 8291)
		$prk_key = hash_hmac( 'sha256', $shared_secret, $user_auth_token, true );
		$key_info = "WebPush: info\0" . $user_public_key . $local_public_key;
		$ikm = hash_hmac( 'sha256', $key_info . "\x01", $prk_key, true );

		$prk = hash_hmac( 'sha256', $ikm, $salt, true );
		$cek_info = "Content-Encoding: aes128gcm\0";
		$nonce_info = "Content-Encoding: nonce\0";

		$content_encryption_key = substr( hash_hmac( 'sha256', $cek_info . "\x01", $prk, true ), 0, 16 );
		$nonce = substr( hash_hmac( 'sha256', $nonce_info . "\x01", $prk, true ), 0, 12 );

		$record_padding = "\x00\x00";
		$plaintext = $record_padding . $payload;

		$ciphertext = openssl_encrypt( $plaintext, 'aes-128-gcm', $content_encryption_key, OPENSSL_RAW_DATA, $nonce, $tag );

		// Assemble aes128gcm record: salt (16) + rs (4) + idlen (1) + key (65) + ciphertext + tag (16)
		$rs = pack( 'N', 4096 );
		$idlen = pack( 'C', 65 );
		$header = $salt . $rs . $idlen . $local_public_key;

		return $header . $ciphertext . $tag;
	}

	/**
	 * Creates ES256 VAPID JWT.
	 */
	private static function create_vapid_jwt( $audience, $subject, $private_key_b64 ) {
		$header = array(
			'typ' => 'JWT',
			'alg' => 'ES256',
		);

		$claims = array(
			'aud' => $audience,
			'exp' => time() + 43200, // 12 hours
			'sub' => $subject,
		);

		$encoded_header = self::base64_url_encode( json_encode( $header ) );
		$encoded_claims = self::base64_url_encode( json_encode( $claims ) );
		$data_to_sign   = $encoded_header . '.' . $encoded_claims;

		$signature = self::sign_es256( $data_to_sign, $private_key_b64 );
		if ( is_wp_error( $signature ) ) {
			return $signature;
		}

		return $data_to_sign . '.' . self::base64_url_encode( $signature );
	}

	/**
	 * Sign data using ES256 (ECDSA P-256 with SHA-256).
	 */
	private static function sign_es256( $data, $private_key_b64 ) {
		$private_raw = self::base64_url_decode( $private_key_b64 );
		if ( strlen( $private_raw ) !== 32 ) {
			return new WP_Error( 'invalid_private_key', __( 'Invalid private VAPID key format.', 'xophz-compass' ) );
		}

		// Convert raw 32-byte scalar to DER format for OpenSSL
		$der_prefix = hex2bin( '30770201010420' ) . $private_raw . hex2bin( 'a00a06082a8648ce3d030107a144034200' );
		$pem = "-----BEGIN EC PRIVATE KEY-----\n" . chunk_split( base64_encode( $der_prefix ), 64, "\n" ) . "-----END EC PRIVATE KEY-----";

		$key_resource = openssl_pkey_get_private( $pem );
		if ( ! $key_resource ) {
			// Construct full PKCS#8 DER structure
			$pkcs8 = hex2bin( '308187020100301306072a8648ce3d020106082a8648ce3d030107046d306b0201010420' ) . $private_raw . hex2bin( 'a144034200' );
			$pem = "-----BEGIN PRIVATE KEY-----\n" . chunk_split( base64_encode( $pkcs8 ), 64, "\n" ) . "-----END PRIVATE KEY-----";
			$key_resource = openssl_pkey_get_private( $pem );
		}

		if ( ! $key_resource ) {
			return new WP_Error( 'openssl_key_error', __( 'Could not parse VAPID private key in OpenSSL.', 'xophz-compass' ) );
		}

		$success = openssl_sign( $data, $der_signature, $key_resource, OPENSSL_ALGO_SHA256 );
		if ( ! $success ) {
			return new WP_Error( 'openssl_sign_error', __( 'ECDSA signing failed.', 'xophz-compass' ) );
		}

		return self::der_to_raw_signature( $der_signature );
	}

	/**
	 * Convert OpenSSL DER signature to 64-byte raw R || S.
	 */
	private static function der_to_raw_signature( $der ) {
		$r = '';
		$s = '';
		$pos = 3;
		$r_len = ord( $der[ $pos ] );
		$pos++;
		$r = substr( $der, $pos, $r_len );
		$pos += $r_len + 1;
		$s_len = ord( $der[ $pos ] );
		$pos++;
		$s = substr( $der, $pos, $s_len );

		$r = ltrim( $r, "\x00" );
		$s = ltrim( $s, "\x00" );

		$r = str_pad( $r, 32, "\x00", STR_PAD_LEFT );
		$s = str_pad( $s, 32, "\x00", STR_PAD_LEFT );

		return $r . $s;
	}

	/**
	 * Generate sovereign VAPID Key pair using OpenSSL NIST P-256.
	 */
	public static function generate_vapid_keys( $save_to_options = true ) {
		$config = array(
			'curve_name'       => 'prime256v1',
			'private_key_type' => OPENSSL_KEYTYPE_EC,
		);

		$res = openssl_pkey_new( $config );
		if ( ! $res ) {
			return new WP_Error( 'vapid_gen_failed', __( 'OpenSSL failed to generate EC key pair.', 'xophz-compass' ) );
		}

		$details = openssl_pkey_get_details( $res );
		if ( ! isset( $details['ec']['x'] ) || ! isset( $details['ec']['y'] ) || ! isset( $details['ec']['d'] ) ) {
			return new WP_Error( 'vapid_details_failed', __( 'Could not extract EC curve point details.', 'xophz-compass' ) );
		}

		// Public key is uncompressed 0x04 + X + Y (65 bytes)
		$public_key_raw = "\x04" . $details['ec']['x'] . $details['ec']['y'];
		$private_key_raw = $details['ec']['d'];

		$public_key_b64  = self::base64_url_encode( $public_key_raw );
		$private_key_b64 = self::base64_url_encode( $private_key_raw );

		if ( $save_to_options ) {
			update_option( 'compass_vapid_public_key', $public_key_b64 );
			update_option( 'compass_vapid_private_key', $private_key_b64 );
			if ( ! get_option( 'compass_vapid_subject' ) ) {
				$admin_email = get_option( 'admin_email', 'admin@localhost' );
				update_option( 'compass_vapid_subject', 'mailto:' . $admin_email );
			}
		}

		return array(
			'publicKey'  => $public_key_b64,
			'privateKey' => $private_key_b64,
		);
	}

	/**
	 * Prune dead subscription endpoints.
	 */
	private static function prune_dead_subscriptions( array $dead_endpoints, $user_id = null ) {
		if ( $user_id !== null && (int) $user_id > 0 ) {
			$subs = get_user_meta( (int) $user_id, self::META_SUBSCRIPTIONS, true );
			if ( is_array( $subs ) ) {
				$clean = array_filter( $subs, function( $s ) use ( $dead_endpoints ) {
					return ! in_array( $s['endpoint'], $dead_endpoints );
				} );
				update_user_meta( (int) $user_id, self::META_SUBSCRIPTIONS, array_values( $clean ) );
			}
		}

		$guest_subs = get_option( 'compass_guest_push_subscriptions', array() );
		if ( is_array( $guest_subs ) ) {
			$clean = array_filter( $guest_subs, function( $s ) use ( $dead_endpoints ) {
				return ! in_array( $s['endpoint'], $dead_endpoints );
			} );
			update_option( 'compass_guest_push_subscriptions', array_values( $clean ) );
		}
	}

	public static function base64_url_encode( $data ) {
		return rtrim( strtr( base64_encode( $data ), '+/', '-_' ), '=' );
	}

	public static function base64_url_decode( $data ) {
		return base64_decode( strtr( $data, '-_', '+/' ) . str_repeat( '=', ( 4 - strlen( $data ) % 4 ) % 4 ) );
	}
}

Xophz_Compass_Push_API::init();
