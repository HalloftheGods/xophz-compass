<?php

/**
 * The Sparks API
 *
 * Handles the REST API endpoints to register and list external sparks
 * for YouMeOS.
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

class Xophz_Compass_Sparks_API {

	/**
	 * Register the REST API routes for the Sparks system, server telemetry, and docs.
	 */
	public function register_routes() {
		register_rest_route( 'xophz/v1', '/sparks', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_sparks' ),
				'permission_callback' => '__return_true', // YouMeOS might not pass auth if not logged in, but let's assume it checks internally or restrict it if needed.
			),
		) );

		register_rest_route( 'xophz/v1', '/sparks/(?P<id>[a-zA-Z0-9-]+)', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_spark_manifest' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'id' => array(
						'validate_callback' => function( $param, $request, $key ) {
							return is_string( $param );
						},
					),
				),
			),
		) );

		register_rest_route( 'xophz/v1', '/system/telemetry', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_system_telemetry' ),
				'permission_callback' => '__return_true',
			),
		) );

		register_rest_route( 'xophz/v1', '/docs/sparks/(?P<id>[a-zA-Z0-9-_]+)', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_spark_doc' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'id' => array(
						'description'       => 'Spark identifier for manual documentation lookup.',
						'type'              => 'string',
						'required'          => true,
						'validate_callback' => function( $param, $request, $key ) {
							return is_string( $param ) && preg_match( '/^[a-zA-Z0-9-_]+$/', $param );
						},
					),
				),
			),
		) );
	}

	/**
	 * Get list of all registered sparks.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_sparks( $request ) {
		// Use a filter so other plugins can push their sparks to the payload
		$sparks = apply_filters( 'xophz_register_sparks', array() );
		$spark_list = is_array( $sparks ) ? array_values( $sparks ) : array();

		return rest_ensure_response( array(
			'success' => true,
			'data'    => $spark_list,
		) );
	}

	/**
	 * Get a specific spark's manifest.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_spark_manifest( $request ) {
		$id = sanitize_text_field( $request->get_param( 'id' ) );
		$manifest = apply_filters( 'xophz_get_spark_manifest', null, $id );

		if ( empty( $manifest ) ) {
			return new WP_Error(
				'rest_spark_not_found',
				'No spark manifest found for this ID.',
				array(
					'status' => 404,
					'data'   => array(
						'success' => false,
						'error'   => array(
							'code'    => 'rest_spark_not_found',
							'message' => 'No spark manifest found for this ID.',
						),
					),
				)
			);
		}

		return rest_ensure_response( array(
			'success' => true,
			'data'    => $manifest,
		) );
	}

	/**
	 * Get live server environment telemetry.
	 *
	 * Returns PHP version, server operating system, database health and version,
	 * and memory limit.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response REST response containing telemetry data.
	 */
	public function get_system_telemetry( $request ) {
		global $wpdb;

		$db_status  = 'healthy';
		$db_version = 'unknown';

		if ( isset( $wpdb ) && is_object( $wpdb ) ) {
			if ( method_exists( $wpdb, 'check_connection' ) ) {
				$connected = $wpdb->check_connection( false );
				$db_status = $connected ? 'healthy' : 'unhealthy';
			} elseif ( ! empty( $wpdb->dbh ) ) {
				$db_status = 'healthy';
			} else {
				$db_status = 'unhealthy';
			}

			if ( method_exists( $wpdb, 'db_version' ) ) {
				$raw_version = $wpdb->db_version();
				if ( ! empty( $raw_version ) ) {
					$db_version = (string) $raw_version;
				}
			}
		} else {
			$db_status = 'unhealthy';
		}

		$memory_limit = ini_get( 'memory_limit' );
		if ( empty( $memory_limit ) && defined( 'WP_MEMORY_LIMIT' ) ) {
			$memory_limit = WP_MEMORY_LIMIT;
		}
		if ( empty( $memory_limit ) ) {
			$memory_limit = 'unknown';
		}

		$data = array(
			'php_version'  => PHP_VERSION,
			'server_os'    => php_uname(),
			'database'     => array(
				'status'  => $db_status,
				'version' => $db_version,
			),
			'memory_limit' => (string) $memory_limit,
		);

		return rest_ensure_response( $data );
	}

	/**
	 * Serve raw spark markdown documentation.
	 *
	 * Reads markdown manual from docs/sparks/{id}.md with path traversal protection.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response REST response containing doc or WP_Error 404.
	 */
	public function get_spark_doc( $request ) {
		$raw_id = $request->get_param( 'id' );

		if ( empty( $raw_id ) || ! is_string( $raw_id ) || ! preg_match( '/^[a-zA-Z0-9-_]+$/', $raw_id ) ) {
			return new WP_Error(
				'spark_doc_not_found',
				'Documentation entry not found',
				array(
					'status' => 404,
					'data'   => array(
						'success' => false,
						'error'   => array(
							'code'    => 'spark_doc_not_found',
							'message' => 'Documentation entry not found',
						),
					),
				)
			);
		}

		// Sanitize ID and strip any directory traversal markers
		$id = basename( sanitize_key( $raw_id ) );

		if ( empty( $id ) || ! preg_match( '/^[a-zA-Z0-9-_]+$/', $id ) ) {
			return new WP_Error(
				'spark_doc_not_found',
				'Documentation entry not found',
				array(
					'status' => 404,
					'data'   => array(
						'success' => false,
						'error'   => array(
							'code'    => 'spark_doc_not_found',
							'message' => 'Documentation entry not found',
						),
					),
				)
			);
		}

		$docs_dir      = $this->get_docs_sparks_dir();
		$real_docs_dir = realpath( $docs_dir );

		if ( ! $real_docs_dir || ! is_dir( $real_docs_dir ) ) {
			return new WP_Error(
				'spark_doc_not_found',
				'Documentation entry not found',
				array(
					'status' => 404,
					'data'   => array(
						'success' => false,
						'error'   => array(
							'code'    => 'spark_doc_not_found',
							'message' => 'Documentation entry not found',
						),
					),
				)
			);
		}

		$target_file = $real_docs_dir . DIRECTORY_SEPARATOR . $id . '.md';

		if ( ! file_exists( $target_file ) || ! is_readable( $target_file ) ) {
			return new WP_Error(
				'spark_doc_not_found',
				'Documentation entry not found',
				array(
					'status' => 404,
					'data'   => array(
						'success' => false,
						'error'   => array(
							'code'    => 'spark_doc_not_found',
							'message' => 'Documentation entry not found',
						),
					),
				)
			);
		}

		$real_target = realpath( $target_file );
		$dir_prefix  = rtrim( $real_docs_dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

		// Path traversal check: verify the target file resides strictly within docs/sparks/
		if ( ! $real_target || strpos( $real_target, $dir_prefix ) !== 0 ) {
			return new WP_Error(
				'spark_doc_not_found',
				'Documentation entry not found',
				array(
					'status' => 404,
					'data'   => array(
						'success' => false,
						'error'   => array(
							'code'    => 'spark_doc_not_found',
							'message' => 'Documentation entry not found',
						),
					),
				)
			);
		}

		$content = file_get_contents( $real_target );
		if ( false === $content ) {
			return new WP_Error(
				'spark_doc_not_found',
				'Documentation entry not found',
				array(
					'status' => 404,
					'data'   => array(
						'success' => false,
						'error'   => array(
							'code'    => 'spark_doc_not_found',
							'message' => 'Documentation entry not found',
						),
					),
				)
			);
		}

		$title = '';
		if ( preg_match( '/^#\s+(.+)$/m', $content, $matches ) ) {
			$title = trim( $matches[1] );
		}
		if ( empty( $title ) ) {
			$title = ucwords( str_replace( array( '-', '_' ), ' ', $id ) );
		}

		return rest_ensure_response( array(
			'id'      => $id,
			'title'   => $title,
			'content' => $content,
		) );
	}

	/**
	 * Resolve the directory path where spark documentation files are located.
	 *
	 * @return string Absolute directory path to docs/sparks.
	 */
	protected function get_docs_sparks_dir() {
		$candidates = array(
			dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/apps/youmeos/docs/sparks',
			dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/docs/sparks',
		);

		if ( defined( 'WP_CONTENT_DIR' ) ) {
			$candidates[] = dirname( WP_CONTENT_DIR ) . '/apps/youmeos/docs/sparks';
			$candidates[] = dirname( WP_CONTENT_DIR ) . '/docs/sparks';
		}

		if ( defined( 'ABSPATH' ) ) {
			$candidates[] = rtrim( ABSPATH, '/\\' ) . '/docs/sparks';
		}

		if ( defined( 'XOPHZ_COMPASS_PATH' ) ) {
			$candidates[] = dirname( dirname( dirname( rtrim( XOPHZ_COMPASS_PATH, '/\\' ) ) ) ) . '/docs/sparks';
		}

		foreach ( $candidates as $candidate ) {
			$real = realpath( $candidate );
			if ( $real && is_dir( $real ) ) {
				if ( function_exists( 'apply_filters' ) ) {
					return apply_filters( 'xophz_docs_sparks_dir', $real );
				}
				return $real;
			}
		}

		$fallback = dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/docs/sparks';
		if ( function_exists( 'apply_filters' ) ) {
			return apply_filters( 'xophz_docs_sparks_dir', $fallback );
		}
		return $fallback;
	}
}
