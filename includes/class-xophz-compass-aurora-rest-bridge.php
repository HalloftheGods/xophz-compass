<?php
/**
 * Aurora / North Universal REST Ecosystem Bridge
 *
 * Exposes the live WordPress + Compass REST route registry (`rest_get_server`)
 * to Aurorai / North as 3 bounded, permission-enforced abilities:
 * - `compass/discover-endpoints` (read-only schema discovery)
 * - `compass/call-rest-read`     (read-only GET via `rest_do_request`)
 * - `compass/call-rest-write`    (mutating POST/PUT/PATCH/DELETE, confirmation-gated)
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Aurora_REST_Bridge {

	private const MAX_DISCOVERY_ROUTES = 35;
	private const MAX_RESPONSE_CHARS   = 6000;

	/**
	 * Route prefixes that the AI must never inspect or invoke directly.
	 */
	private const BLOCKED_PREFIXES = array(
		'/xophz/v1/aurora',
		'/xophz/v1/connectors',
		'/xophz/v1/auth',
		'/xophz/v1/passport',
		'/xophz-compass/v1/auth',
	);

	/**
	 * Return the 3 universal REST bridge ability definitions.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function get_abilities(): array {
		return array(
			array(
				'id'                  => 'compass/discover-endpoints',
				'name'                => 'Discover Compass REST Endpoints',
				'plugin'              => 'Compass Ecosystem',
				'category'            => 'Ecosystem',
				'description'         => 'Searches live WordPress, WooCommerce, and Compass plugin REST API routes by keyword or namespace (for example "products", "bazaar", "bomb-bag", "bugnet", "questbook", "orders", "posts") and returns their paths, HTTP methods, and parameter names.',
				'mutating'            => false,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'query'     => array(
							'type'        => 'string',
							'description' => 'Keyword or namespace filter (e.g. "products", "bazaar", "wc/v3", "bomb-bag", "bugnet").',
						),
						'method'    => array(
							'type'        => 'string',
							'description' => 'Optional HTTP method filter: GET, POST, PUT, PATCH, or DELETE.',
						),
					),
					'required'   => array( 'query' ),
				),
				'permission_callback' => static function (): bool {
					return current_user_can( 'edit_posts' );
				},
				'execute_callback'    => array( __CLASS__, 'discover_endpoints' ),
			),
			array(
				'id'                  => 'compass/call-rest-read',
				'name'                => 'Read Compass REST Data',
				'plugin'              => 'Compass Ecosystem',
				'category'            => 'Ecosystem',
				'description'         => 'Executes an authorized read-only GET request against any discovered WordPress, WooCommerce, or Compass plugin REST route (for example /wc/v3/products or /wp/v2/posts).',
				'mutating'            => false,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'route'  => array(
							'type'        => 'string',
							'description' => 'Exact REST route path starting with / (without /wp-json prefix), e.g. /wc/v3/products.',
						),
						'params_json' => array(
							'type'        => 'string',
							'description' => 'Optional JSON object string of query parameters, e.g. "{\"per_page\":10,\"search\":\"book\"}".',
						),
					),
					'required'   => array( 'route' ),
				),
				'permission_callback' => static function (): bool {
					return current_user_can( 'edit_posts' );
				},
				'execute_callback'    => array( __CLASS__, 'call_rest_read' ),
			),
			array(
				'id'                  => 'compass/call-rest-write',
				'name'                => 'Execute Compass REST Action',
				'plugin'              => 'Compass Ecosystem',
				'category'            => 'Ecosystem',
				'description'         => 'Creates, updates, or deletes data via a discovered WordPress, WooCommerce, or Compass plugin REST route (POST, PUT, PATCH, DELETE). Always requires user approval before executing.',
				'mutating'            => true,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'method'     => array(
							'type'        => 'string',
							'description' => 'HTTP write method: POST, PUT, PATCH, or DELETE.',
						),
						'route'      => array(
							'type'        => 'string',
							'description' => 'Exact REST route path starting with / (without /wp-json prefix), e.g. /wc/v3/products.',
						),
						'payload_json' => array(
							'type'        => 'string',
							'description' => 'JSON object string of request body parameters matching the route schema, e.g. "{\"name\":\"Coloring books\",\"regular_price\":\"1.00\",\"manage_stock\":true,\"stock_quantity\":12}".',
						),
						'next_route' => array(
							'type'        => 'string',
							'description' => 'Optional Compass UI route to open after creation/update (e.g. /bazaar/products).',
						),
						'next_label' => array(
							'type'        => 'string',
							'description' => 'Short button label for next_route (e.g. Edit Product in Store).',
						),
					),
					'required'   => array( 'method', 'route' ),
				),
				'permission_callback' => static function (): bool {
					return current_user_can( 'edit_posts' );
				},
				'execute_callback'    => array( __CLASS__, 'call_rest_write' ),
			),
			array(
				'id'                  => 'compass/find-product-images',
				'name'                => 'Find Suggested Product Images',
				'plugin'              => 'Compass Ecosystem',
				'category'            => 'Store',
				'description'         => 'Searches existing store products and the WordPress Media Library for matching product images to suggest when creating a new product. Returns attachment IDs and image URLs.',
				'mutating'            => false,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'query' => array(
							'type'        => 'string',
							'description' => 'Product name or keyword to match against existing products and media (e.g. "coloring book").',
						),
						'limit' => array(
							'type'        => 'integer',
							'description' => 'Maximum number of image suggestions to return (1-8). Defaults to 6.',
						),
					),
					'required'   => array( 'query' ),
				),
				'permission_callback' => static function (): bool {
					return current_user_can( 'edit_posts' );
				},
				'execute_callback'    => array( __CLASS__, 'find_product_images' ),
			),
		);
	}

	/**
	 * Search live REST routes from `rest_get_server()->get_routes()`.
	 *
	 * @param array<string, mixed> $args
	 * @return array<string, mixed>
	 */
	public static function discover_endpoints( array $args ): array {
		$query  = strtolower( trim( sanitize_text_field( (string) ( $args['query'] ?? '' ) ) ) );
		$method = strtoupper( trim( sanitize_text_field( (string) ( $args['method'] ?? '' ) ) ) );
		$server = rest_get_server();
		$routes = $server ? $server->get_routes() : array();
		$hits   = array();

		foreach ( $routes as $route => $handlers ) {
			if ( self::is_blocked_route( $route ) ) {
				continue;
			}
			if ( '' !== $query && false === stripos( $route, $query ) ) {
				continue;
			}

			$methods    = array();
			$param_keys = array();

			foreach ( (array) $handlers as $handler ) {
				if ( ! is_array( $handler ) ) {
					continue;
				}
				$h_methods = isset( $handler['methods'] ) && is_array( $handler['methods'] )
					? array_keys( array_filter( $handler['methods'] ) )
					: array();
				if ( '' !== $method && ! in_array( $method, $h_methods, true ) ) {
					continue;
				}
				$methods = array_merge( $methods, $h_methods );
				if ( ! empty( $handler['args'] ) && is_array( $handler['args'] ) ) {
					foreach ( array_keys( $handler['args'] ) as $arg_name ) {
						if ( is_string( $arg_name ) ) {
							$param_keys[ $arg_name ] = true;
						}
					}
				}
			}

			$methods = array_values( array_unique( $methods ) );
			if ( empty( $methods ) ) {
				continue;
			}

			$hits[] = array(
				'route'   => $route,
				'methods' => $methods,
				'params'  => array_slice( array_keys( $param_keys ), 0, 20 ),
			);

			if ( count( $hits ) >= self::MAX_DISCOVERY_ROUTES ) {
				break;
			}
		}

		return array(
			'count'     => count( $hits ),
			'endpoints' => $hits,
		);
	}

	/**
	 * Execute an internal GET request via `rest_do_request()`.
	 *
	 * @param array<string, mixed> $args
	 * @return array<string, mixed>|WP_Error
	 */
	public static function call_rest_read( array $args ) {
		$route  = self::normalize_route( (string) ( $args['route'] ?? '' ) );
		$params = self::decode_object_arg( $args['params_json'] ?? ( $args['params'] ?? array() ) );

		if ( '' === $route || self::is_blocked_route( $route ) ) {
			return new WP_Error( 'aurora_blocked_route', 'Route is empty or restricted.' );
		}

		$request = new WP_REST_Request( 'GET', $route );
		foreach ( $params as $key => $val ) {
			$request->set_param( (string) $key, $val );
		}

		return self::dispatch_internal_request( $request, $route );
	}

	/**
	 * Execute an internal write request (POST/PUT/PATCH/DELETE) after user confirmation.
	 *
	 * @param array<string, mixed> $args
	 * @return array<string, mixed>|WP_Error
	 */
	public static function call_rest_write( array $args ) {
		$method     = strtoupper( trim( (string) ( $args['method'] ?? 'POST' ) ) );
		$route      = self::normalize_route( (string) ( $args['route'] ?? '' ) );
		$payload    = self::decode_object_arg( $args['payload_json'] ?? ( $args['payload'] ?? array() ) );
		$next_route = sanitize_text_field( (string) ( $args['next_route'] ?? '' ) );
		$next_label = sanitize_text_field( (string) ( $args['next_label'] ?? '' ) );

		if ( ! in_array( $method, array( 'POST', 'PUT', 'PATCH', 'DELETE' ), true ) ) {
			return new WP_Error( 'aurora_invalid_method', 'Unsupported write HTTP method.' );
		}
		if ( '' === $route || self::is_blocked_route( $route ) ) {
			return new WP_Error( 'aurora_blocked_route', 'Route is empty or restricted.' );
		}

		$payload = self::normalize_write_payload( $payload );

		$request = new WP_REST_Request( $method, $route );
		$request->set_header( 'content-type', 'application/json' );
		$request->set_body( wp_json_encode( $payload ) );
		foreach ( $payload as $key => $val ) {
			$request->set_param( (string) $key, $val );
		}

		$result = self::dispatch_internal_request( $request, $route );
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		if ( '' === $next_route ) {
			$inferred   = self::infer_next_route( $route );
			$next_route = $inferred['route'];
			$next_label = '' !== $next_label ? $next_label : $inferred['label'];
		}

		if ( '' !== $next_route ) {
			$result['next_route'] = $next_route;
			$result['next_label'] = '' !== $next_label ? $next_label : 'Open in Compass';
		}

		return $result;
	}

	/**
	 * Search existing WooCommerce products and the WP Media Library for candidate product images.
	 *
	 * @param array<string, mixed> $args
	 * @return array<string, mixed>
	 */
	public static function find_product_images( array $args ): array {
		$query   = sanitize_text_field( (string) ( $args['query'] ?? '' ) );
		$limit   = max( 1, min( 8, (int) ( $args['limit'] ?? 6 ) ) );
		$results = array();
		$seen    = array();

		if ( '' === $query ) {
			return array( 'count' => 0, 'images' => array() );
		}

		// 1. Search existing products with featured images.
		$products = get_posts(
			array(
				's'              => $query,
				'post_type'      => 'product',
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => $limit,
				'meta_key'       => '_thumbnail_id',
			)
		);

		foreach ( $products as $product ) {
			$thumb_id = (int) get_post_thumbnail_id( $product->ID );
			if ( $thumb_id <= 0 || isset( $seen[ $thumb_id ] ) ) {
				continue;
			}
			$url = wp_get_attachment_image_url( $thumb_id, 'medium' );
			if ( ! $url ) {
				continue;
			}
			$seen[ $thumb_id ] = true;
			$results[]         = array(
				'attachment_id' => $thumb_id,
				'src'           => $url,
				'title'         => get_the_title( $product ),
				'source'        => 'product',
			);
			if ( count( $results ) >= $limit ) {
				break;
			}
		}

		// 2. Supplement from WordPress Media Library attachments.
		if ( count( $results ) < $limit ) {
			$attachments = get_posts(
				array(
					's'              => $query,
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'post_status'    => 'inherit',
					'posts_per_page' => $limit - count( $results ),
				)
			);

			foreach ( $attachments as $att ) {
				$att_id = (int) $att->ID;
				if ( isset( $seen[ $att_id ] ) ) {
					continue;
				}
				$url = wp_get_attachment_image_url( $att_id, 'medium' );
				if ( ! $url ) {
					continue;
				}
				$seen[ $att_id ] = true;
				$results[]       = array(
					'attachment_id' => $att_id,
					'src'           => $url,
					'title'         => get_the_title( $att ),
					'source'        => 'media_library',
				);
			}
		}

		// 3. Supplement with external product photos from Wikimedia Commons when local matches are scarce.
		if ( count( $results ) < $limit ) {
			$needed   = $limit - count( $results );
			$wiki_url = add_query_arg(
				array(
					'action'       => 'query',
					'generator'    => 'search',
					'gsrsearch'    => $query . ' filetype:bitmap',
					'gsrnamespace' => 6,
					'gsrlimit'     => $needed,
					'prop'         => 'imageinfo',
					'iiprop'       => 'url',
					'iiurlwidth'   => 600,
					'format'       => 'json',
				),
				'https://commons.wikimedia.org/w/api.php'
			);

			$wiki_res = wp_remote_get(
				$wiki_url,
				array(
					'timeout' => 10,
					'headers' => array( 'User-Agent' => 'XophzCompass/1.0' ),
				)
			);

			if ( ! is_wp_error( $wiki_res ) && 200 === (int) wp_remote_retrieve_response_code( $wiki_res ) ) {
				$wiki_data = json_decode( (string) wp_remote_retrieve_body( $wiki_res ), true );
				$pages     = is_array( $wiki_data['query']['pages'] ?? null ) ? $wiki_data['query']['pages'] : array();
				foreach ( $pages as $page ) {
					$info = $page['imageinfo'][0] ?? null;
					$src  = is_array( $info ) ? (string) ( $info['thumburl'] ?? $info['url'] ?? '' ) : '';
					if ( '' === $src ) {
						continue;
					}
					$results[] = array(
						'attachment_id' => 0,
						'src'           => $src,
						'title'         => preg_replace( '/^File:/i', '', (string) ( $page['title'] ?? $query ) ),
						'source'        => 'wikimedia',
					);
					if ( count( $results ) >= $limit ) {
						break;
					}
				}
			}
		}

		return array(
			'count'  => count( $results ),
			'images' => $results,
		);
	}

	private static function dispatch_internal_request( WP_REST_Request $request, string $route ) {
		$response = rest_do_request( $request );
		$status   = $response->get_status();
		$data     = $response->get_data();

		if ( $response->is_error() || $status >= 400 ) {
			$msg = is_array( $data ) && ! empty( $data['message'] )
				? (string) $data['message']
				: sprintf( 'REST request to %s failed with HTTP %d.', $route, $status );
			return new WP_Error( 'aurora_rest_failed', $msg, array( 'status' => $status ) );
		}

		$encoded = wp_json_encode( $data );
		if ( is_string( $encoded ) && strlen( $encoded ) > self::MAX_RESPONSE_CHARS ) {
			$data = array(
				'truncated' => true,
				'preview'   => substr( $encoded, 0, self::MAX_RESPONSE_CHARS ),
			);
		}

		return array(
			'status' => $status,
			'route'  => $route,
			'data'   => $data,
		);
	}

	private static function decode_object_arg( $raw ): array {
		if ( is_array( $raw ) ) {
			return $raw;
		}
		if ( is_string( $raw ) && '' !== trim( $raw ) ) {
			$decoded = json_decode( $raw, true );
			if ( is_array( $decoded ) ) {
				return $decoded;
			}
		}
		return array();
	}

	/**
	 * Normalize common LLM payload quirks before dispatching a WP/WC REST write:
	 * - Strip leading `$` / commas from price fields (`regular_price`, `sale_price`, `price`).
	 * - Normalize `images` entries from bare URL strings into `array( 'src' => $url )`.
	 *
	 * @param array<string, mixed> $payload
	 * @return array<string, mixed>
	 */
	private static function normalize_write_payload( array $payload ): array {
		foreach ( array( 'regular_price', 'sale_price', 'price' ) as $price_key ) {
			if ( isset( $payload[ $price_key ] ) && is_string( $payload[ $price_key ] ) ) {
				$payload[ $price_key ] = preg_replace( '/[^0-9.\-]/', '', $payload[ $price_key ] );
			}
		}

		if ( isset( $payload['images'] ) && is_array( $payload['images'] ) ) {
			$normalized = array();
			foreach ( $payload['images'] as $img ) {
				if ( is_string( $img ) && '' !== trim( $img ) ) {
					$img = array( 'src' => trim( $img ) );
				}
				if ( ! is_array( $img ) ) {
					continue;
				}
				if ( ! empty( $img['id'] ) ) {
					$normalized[] = $img;
					continue;
				}
				if ( ! empty( $img['src'] ) && is_string( $img['src'] ) ) {
					$sideloaded_id = self::sideload_remote_image( trim( $img['src'] ) );
					if ( $sideloaded_id > 0 ) {
						$normalized[] = array( 'id' => $sideloaded_id );
					} else {
						$normalized[] = $img;
					}
				}
			}
			$payload['images'] = $normalized;
		}

		return $payload;
	}

	/**
	 * Download an external image URL with a descriptive User-Agent (required by Wikimedia Commons)
	 * and import it into the WordPress Media Library, returning the attachment ID.
	 */
	private static function sideload_remote_image( string $url ): int {
		if ( ! preg_match( '#^https?://#i', $url ) ) {
			return 0;
		}
		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		$response = wp_remote_get(
			$url,
			array(
				'timeout'    => 15,
				'user-agent' => 'XophzCompass/1.0 (+https://xophz.com) WordPress',
			)
		);
		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return 0;
		}

		$body = wp_remote_retrieve_body( $response );
		if ( '' === $body ) {
			return 0;
		}

		$path     = (string) wp_parse_url( $url, PHP_URL_PATH );
		$filename = sanitize_file_name( basename( $path ) );
		if ( '' === $filename || false === strpos( $filename, '.' ) ) {
			$filename = 'aurora-product-' . wp_generate_password( 6, false ) . '.jpg';
		}

		$tmp = wp_tempnam( $filename );
		if ( ! $tmp || false === file_put_contents( $tmp, $body ) ) {
			return 0;
		}

		$file_array = array(
			'name'     => $filename,
			'tmp_name' => $tmp,
		);

		$attachment_id = media_handle_sideload( $file_array, 0 );
		if ( is_wp_error( $attachment_id ) ) {
			@unlink( $tmp );
			return 0;
		}

		return (int) $attachment_id;
	}

	private static function normalize_route( string $raw ): string {
		$clean = trim( $raw );
		$clean = preg_replace( '#^/wp-json#i', '', $clean );
		$clean = '/' . ltrim( (string) $clean, '/' );
		return '/' === $clean ? '' : $clean;
	}

	private static function is_blocked_route( string $route ): bool {
		$lower = strtolower( $route );
		foreach ( self::BLOCKED_PREFIXES as $prefix ) {
			if ( 0 === strpos( $lower, $prefix ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Infer a helpful Compass destination route after a successful REST write.
	 *
	 * @return array{route: string, label: string}
	 */
	private static function infer_next_route( string $rest_route ): array {
		$lower = strtolower( $rest_route );
		if ( false !== strpos( $lower, '/products' ) || false !== strpos( $lower, 'bazaar' ) ) {
			return array( 'route' => '/bazaar/products', 'label' => 'Open in Store Inventory' );
		}
		if ( false !== strpos( $lower, 'bomb-bag' ) ) {
			return array( 'route' => '/bomb-bag', 'label' => 'Open Email Newsletters' );
		}
		if ( false !== strpos( $lower, 'bugnet' ) ) {
			return array( 'route' => '/bugnet', 'label' => 'Open Bug Reports' );
		}
		if ( false !== strpos( $lower, '/wp/v2/posts' ) ) {
			return array( 'route' => '/newsroom', 'label' => 'Open Posts' );
		}
		return array( 'route' => '', 'label' => '' );
	}
}
