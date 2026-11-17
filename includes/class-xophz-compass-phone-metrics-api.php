<?php

/**
 * Live GitHub metrics and contact details for the My Compass Phone app.
 *
 * The public endpoint returns totals only: no private repository names, no account
 * tokens. GitHub responses are cached for an hour so the phone never waits on GitHub.
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

class Xophz_Compass_Phone_Metrics_API {

	const OPTION_GITHUB_USER = 'compass_phone_github_user';
	const OPTION_PHONE       = 'compass_phone_contact_phone';
	const OPTION_EMAIL       = 'compass_phone_contact_email';
	const CACHE_KEY          = 'compass_phone_metrics_v1';
	const CACHE_TTL          = HOUR_IN_SECONDS;
	const MAX_REPOS          = 25;

	/**
	 * Register the metrics and contact routes.
	 */
	public function register_routes() {
		register_rest_route( 'xophz-compass/v1', '/phone/metrics', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_metrics' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'xophz-compass/v1', '/phone/contact', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_contact' ),
				'permission_callback' => '__return_true',
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'save_contact' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			),
		) );
	}

	public function get_contact() {
		return rest_ensure_response( $this->contact_details() );
	}

	/**
	 * Save the contact number, email and GitHub account. Administrators only.
	 */
	public function save_contact( WP_REST_Request $request ) {
		$params = $request->get_json_params();

		if ( isset( $params['phone'] ) ) {
			update_option( self::OPTION_PHONE, sanitize_text_field( $params['phone'] ) );
		}
		if ( isset( $params['email'] ) ) {
			update_option( self::OPTION_EMAIL, sanitize_email( $params['email'] ) );
		}
		if ( isset( $params['github_user'] ) ) {
			update_option( self::OPTION_GITHUB_USER, sanitize_text_field( $params['github_user'] ) );
			delete_transient( self::CACHE_KEY );
		}

		return rest_ensure_response( array(
			'success' => true,
			'contact' => $this->contact_details(),
		) );
	}

	/**
	 * Public GitHub metrics, served from cache when it is fresh.
	 */
	public function get_metrics( WP_REST_Request $request ) {
		$cached = get_transient( self::CACHE_KEY );
		$force  = (bool) $request->get_param( 'refresh' );

		if ( false !== $cached && ! $force ) {
			return rest_ensure_response( $cached );
		}

		$metrics = $this->build_metrics();
		// While GitHub is still computing stats, keep the result only briefly so it refreshes soon.
		$ttl = ! empty( $metrics['pending_repos'] ) ? 5 * MINUTE_IN_SECONDS : self::CACHE_TTL;
		set_transient( self::CACHE_KEY, $metrics, $ttl );

		return rest_ensure_response( $metrics );
	}

	private function contact_details() {
		return array(
			'phone'       => (string) get_option( self::OPTION_PHONE, '' ),
			'email'       => (string) get_option( self::OPTION_EMAIL, '' ),
			'github_user' => (string) get_option( self::OPTION_GITHUB_USER, '' ),
		);
	}

	private function build_metrics() {
		$user  = (string) get_option( self::OPTION_GITHUB_USER, '' );
		$token = class_exists( 'Xophz_Compass_Connector_Key' ) ? Xophz_Compass_Connector_Key::get( 'github_token' ) : '';

		if ( '' === $user ) {
			return array( 'configured' => false, 'error' => 'No GitHub account is set.' );
		}

		$repos = $this->fetch_repos( $user, $token );
		if ( is_wp_error( $repos ) ) {
			return array(
				'configured' => true,
				'github_user' => $user,
				'error'      => $repos->get_error_message(),
			);
		}

		$punch    = array_fill( 0, 7, array_fill( 0, 24, 0 ) ); // [day 0 = Sunday][hour]
		$weekly   = array_fill( 0, 52, 0 );
		$languages = array();
		$analyzed = 0;
		$pending  = 0;
		$public   = 0;
		$private  = 0;
		$last_pushed = '';

		foreach ( $repos as $repo ) {
			if ( ! empty( $repo['private'] ) ) {
				$private++;
			} else {
				$public++;
			}
			if ( ! empty( $repo['language'] ) ) {
				$languages[ $repo['language'] ] = ( $languages[ $repo['language'] ] ?? 0 ) + 1;
			}
			if ( empty( $last_pushed ) || ( ! empty( $repo['pushed_at'] ) && $repo['pushed_at'] > $last_pushed ) ) {
				$last_pushed = (string) ( $repo['pushed_at'] ?? $last_pushed );
			}
		}

		// Only the most recently pushed repos are analysed, to keep GitHub calls bounded.
		foreach ( array_slice( $repos, 0, self::MAX_REPOS ) as $repo ) {
			$full = $repo['full_name'];

			$punch_data = $this->github_get( "repos/{$full}/stats/punch_card", $token );
			$activity   = $this->github_get( "repos/{$full}/stats/commit_activity", $token );

			if ( ! is_array( $punch_data ) || ! is_array( $activity ) ) {
				$pending++;
				continue;
			}

			$analyzed++;
			foreach ( $punch_data as $row ) {
				if ( isset( $row[0], $row[1], $row[2] ) ) {
					$punch[ (int) $row[0] ][ (int) $row[1] ] += (int) $row[2];
				}
			}
			foreach ( array_values( $activity ) as $i => $week ) {
				if ( $i < 52 && isset( $week['total'] ) ) {
					$weekly[ $i ] += (int) $week['total'];
				}
			}
		}

		arsort( $languages );

		return array(
			'configured'        => true,
			'github_user'       => $user,
			'fetched_at'        => current_time( 'c' ),
			'public_repos'      => $public,
			'private_repos'     => $private,
			'analyzed_repos'    => $analyzed,
			'pending_repos'     => $pending,
			'commits_last_year' => array_sum( $weekly ),
			'weekly_commits'    => $weekly,
			'punch_card'        => $punch,
			'languages'         => array_slice( $languages, 0, 6, true ),
			'last_pushed_at'    => $last_pushed,
		);
	}

	/**
	 * Repos owned by the account. A token also reveals private ones; only counts are returned.
	 */
	private function fetch_repos( $user, $token ) {
		$url = $token
			? 'https://api.github.com/user/repos?affiliation=owner&visibility=all&sort=pushed&per_page=100'
			: "https://api.github.com/users/{$user}/repos?type=public&sort=pushed&per_page=100";

		$response = $this->github_request( $url, $token );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$owned = array();
		foreach ( (array) $response as $repo ) {
			if ( isset( $repo['owner']['login'] ) && strtolower( $repo['owner']['login'] ) === strtolower( $user ) ) {
				$owned[] = $repo;
			}
		}

		usort( $owned, function ( $a, $b ) {
			return strcmp( (string) ( $b['pushed_at'] ?? '' ), (string) ( $a['pushed_at'] ?? '' ) );
		} );

		return $owned;
	}

	private function github_get( $path, $token ) {
		$response = $this->github_request( 'https://api.github.com/' . $path, $token );
		return is_wp_error( $response ) ? null : $response;
	}

	/**
	 * GET a GitHub API URL. GitHub answers 202 while it computes statistics; that is treated as no data yet.
	 */
	private function github_request( $url, $token ) {
		$headers = array( 'Accept' => 'application/vnd.github+json' );
		if ( $token ) {
			$headers['Authorization'] = 'Bearer ' . $token;
		}

		$response = wp_remote_get( $url, array(
			'timeout'    => 10,
			'user-agent' => 'COMPASS-Phone-Metrics',
			'headers'    => $headers,
		) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		if ( 202 === $code ) {
			return new WP_Error( 'github_pending', 'GitHub is still computing these statistics.' );
		}
		if ( 200 !== $code ) {
			return new WP_Error( 'github_error', sprintf( 'GitHub returned status %d.', $code ) );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		return is_array( $data ) ? $data : new WP_Error( 'github_error', 'GitHub returned unreadable data.' );
	}
}
