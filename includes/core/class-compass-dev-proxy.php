<?php
/**
 * Consolidated Dev Server Reverse Proxy & Production Dist Loader.
 * High-performance Vite dev server reverse-proxy using 150ms non-blocking socket probing (fsockopen),
 * eliminating 7-second blocking timeouts.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Dev_Proxy {

	/**
	 * Configuration properties.
	 */
	protected string $slug;
	protected string $default_slug;
	protected int $dev_port;
	protected string $query_var;
	protected string $plugin_path;
	protected string $plugin_url;
	protected string $version;
	protected array $candidate_dist_paths;
	protected array $extra_settings = array();

	/**
	 * Constructor.
	 *
	 * @param array{
	 *   slug: string,
	 *   default_slug?: string,
	 *   dev_port: int,
	 *   query_var: string,
	 *   plugin_path: string,
	 *   plugin_url: string,
	 *   version: string,
	 *   candidate_dist_paths?: array<int, string>,
	 *   extra_settings?: array<string, mixed>
	 * } $config Configuration array.
	 */
	public function __construct( array $config ) {
		$this->slug           = $config['slug'];
		$this->default_slug   = $config['default_slug'] ?? $this->slug;
		$this->dev_port       = $config['dev_port'];
		$this->query_var      = $config['query_var'];
		$this->plugin_path    = rtrim( $config['plugin_path'], '/' ) . '/';
		$this->plugin_url     = rtrim( $config['plugin_url'], '/' ) . '/';
		$this->version        = $config['version'];
		$this->extra_settings = $config['extra_settings'] ?? array();

		$this->candidate_dist_paths = $config['candidate_dist_paths'] ?? array(
			$this->plugin_path . 'public/dist/index.html',
		);

		add_filter( 'query_vars', array( $this, 'register_query_vars' ) );
		add_action( 'init', array( $this, 'register_rewrites' ) );
		add_action( 'template_redirect', array( $this, 'handle_template_redirect' ) );
	}

	/**
	 * Extract and normalize host from HTTP Host header or host configuration string,
	 * safely stripping port and scheme while preserving IPv4, hostname, and bracketed IPv6.
	 *
	 * @param string $raw_host Raw Host header or host string.
	 * @return string Normalized host suitable for URL authority.
	 */
	public static function extract_host( string $raw_host ): string {
		$raw_host = trim( $raw_host );
		if ( empty( $raw_host ) ) {
			return 'localhost';
		}

		// Strip scheme if present (e.g. http:// or https://)
		$raw_host = preg_replace( '#^https?://#i', '', $raw_host );

		// Strip trailing slashes and path components
		$raw_host = explode( '/', $raw_host )[0];
		$raw_host = trim( $raw_host );

		if ( empty( $raw_host ) ) {
			return 'localhost';
		}

		// 1. Bracketed IPv6 with optional port: [::1] or [::1]:8080 -> [::1]
		if ( str_starts_with( $raw_host, '[' ) ) {
			$close_bracket = strpos( $raw_host, ']' );
			if ( false !== $close_bracket ) {
				return substr( $raw_host, 0, $close_bracket + 1 );
			}
		}

		// 2. Raw unbracketed IPv6 without port: ::1 or 2001:db8::1 -> [::1] or [2001:db8::1]
		if ( filter_var( $raw_host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) !== false ) {
			return '[' . $raw_host . ']';
		}

		// 3. Standard IPv4 or hostname with optional port: localhost:8080 -> localhost
		$parsed_host = function_exists( 'wp_parse_url' )
			? wp_parse_url( 'http://' . $raw_host, PHP_URL_HOST )
			: parse_url( 'http://' . $raw_host, PHP_URL_HOST );

		if ( ! empty( $parsed_host ) && is_string( $parsed_host ) ) {
			return $parsed_host;
		}

		// 4. Fallback port strip by splitting on colon
		if ( strpos( $raw_host, ':' ) !== false ) {
			$parts = explode( ':', $raw_host );
			return ! empty( $parts[0] ) ? $parts[0] : 'localhost';
		}

		return $raw_host;
	}

	/**
	 * Probe whether a dev server is actively listening on given port and host.
	 *
	 * @param int    $port    Dev server port.
	 * @param string $host    Host address. Default '127.0.0.1'.
	 * @param float  $timeout Connection timeout in seconds. Default 0.15 (150ms).
	 * @return bool
	 */
	public static function is_dev_active( int $port, string $host = '127.0.0.1', float $timeout = 0.15 ): bool {
		$host       = self::extract_host( $host );
		$connection = @fsockopen( $host, $port, $errno, $errstr, $timeout );
		if ( is_resource( $connection ) ) {
			fclose( $connection );
			return true;
		}
		return false;
	}

	/**
	 * Get prioritized list of candidate dev hosts.
	 * Checks getenv('COMPASS_DEV_HOST') first, falling back to $_ENV, $_SERVER, and constant COMPASS_DEV_HOST.
	 * Trims whitespace, eliminates duplicates, and prioritizes custom host to index 0.
	 *
	 * @return array<int, string>
	 */
	public static function get_candidate_hosts(): array {
		$candidates = array( 'compass', '127.0.0.1', 'localhost', 'host.docker.internal' );

		$raw_host = getenv( 'COMPASS_DEV_HOST' );
		if ( false === $raw_host || '' === trim( (string) $raw_host ) ) {
			if ( defined( 'COMPASS_DEV_HOST' ) && ! empty( COMPASS_DEV_HOST ) && is_string( COMPASS_DEV_HOST ) && '' !== trim( COMPASS_DEV_HOST ) ) {
				$raw_host = COMPASS_DEV_HOST;
			} elseif ( ! empty( $_ENV['COMPASS_DEV_HOST'] ) && is_string( $_ENV['COMPASS_DEV_HOST'] ) && '' !== trim( $_ENV['COMPASS_DEV_HOST'] ) ) {
				$raw_host = $_ENV['COMPASS_DEV_HOST'];
			} elseif ( ! empty( $_SERVER['COMPASS_DEV_HOST'] ) && is_string( $_SERVER['COMPASS_DEV_HOST'] ) && '' !== trim( $_SERVER['COMPASS_DEV_HOST'] ) ) {
				$raw_host = $_SERVER['COMPASS_DEV_HOST'];
			} else {
				$raw_host = null;
			}
		}

		$configured = array();
		if ( null !== $raw_host && '' !== trim( (string) $raw_host ) ) {
			foreach ( explode( ',', (string) $raw_host ) as $part ) {
				$trimmed = trim( $part );
				if ( '' !== $trimmed ) {
					$clean = self::extract_host( $trimmed );
					if ( '' !== $clean ) {
						$configured[] = $clean;
					}
				}
			}
		}

		if ( ! empty( $configured ) ) {
			$candidates = array_values( array_unique( array_merge( $configured, $candidates ) ) );
		}

		return $candidates;
	}

	/**
	 * Probe candidate dev hosts and return the first active host, or null.
	 *
	 * @param int $port Dev server port.
	 * @return string|null
	 */
	public static function resolve_host( int $port ): ?string {
		if ( ! self::is_dev_mode() ) {
			return null;
		}

		$candidates = self::get_candidate_hosts();

		foreach ( $candidates as $host ) {
			if ( self::is_dev_active( $port, $host, 0.15 ) ) {
				return $host;
			}
		}

		return null;
	}

	/**
	 * Enqueue either dev server assets or compiled production dist.
	 *
	 * @param string $handle    Asset script handle.
	 * @param int    $port      Dev server port.
	 * @param string $dist_path Relative or absolute path to production dist.
	 */
	public static function inject_or_enqueue( string $handle, int $port, string $dist_path ): void {
		if ( ! self::is_dev_mode() ) {
			if ( file_exists( $dist_path ) ) {
				$url = function_exists( 'plugins_url' ) ? plugins_url( $dist_path ) : $dist_path;
				if ( function_exists( 'wp_enqueue_script' ) ) {
					wp_enqueue_script( $handle, $url, array(), null, true );
				}
			}
			return;
		}

		$host = self::resolve_host( $port );
		if ( $host ) {
			$vite_client = 'http://' . $host . ':' . $port . '/@vite/client';
			if ( function_exists( 'wp_enqueue_script' ) ) {
				wp_enqueue_script( $handle . '-vite-client', $vite_client, array(), null, false );
			}
		} elseif ( file_exists( $dist_path ) ) {
			$url = function_exists( 'plugins_url' ) ? plugins_url( $dist_path ) : $dist_path;
			if ( function_exists( 'wp_enqueue_script' ) ) {
				wp_enqueue_script( $handle, $url, array(), null, true );
			}
		}
	}

	/**
	 * Register query variable for the front-end SPA route.
	 *
	 * @param array<int, string> $vars Existing query vars.
	 * @return array<int, string>
	 */
	public function register_query_vars( array $vars ): array {
		$vars[] = $this->query_var;
		return $vars;
	}

	/**
	 * Register rewrite rules for the slug and optional homepage mode.
	 */
	public function register_rewrites(): void {
		$opt_prefix = 'xophz_compass_' . str_replace( '-', '_', $this->slug );
		$slug       = get_option( $opt_prefix . '_custom_slug', $this->default_slug );
		$load_mode  = get_option( $opt_prefix . '_load_mode', 'routes_only' );

		if ( ! empty( $slug ) ) {
			$quoted_slug = preg_quote( $slug, '/' );
			add_rewrite_rule( '^' . $quoted_slug . '/?$', 'index.php?' . $this->query_var . '=1', 'top' );
			add_rewrite_rule( '^' . $quoted_slug . '/(.*)?$', 'index.php?' . $this->query_var . '=1', 'top' );
		}

		if ( 'homepage' === $load_mode ) {
			add_rewrite_rule( '^sw\.js$', 'index.php?' . $this->query_var . '=1', 'top' );
			add_rewrite_rule( '^manifest\.webmanifest$', 'index.php?' . $this->query_var . '=1', 'top' );
			add_rewrite_rule( '^manifest\.json$', 'index.php?' . $this->query_var . '=1', 'top' );
		}
	}

	/**
	 * Check if current environment is in development mode.
	 */
	public static function is_dev_mode(): bool {
		// 1. Explicit production overrides and killswitches: always false in production
		if ( isset( $_GET['prod'] ) ) {
			return false;
		}

		if ( defined( 'COMPASS_FORCE_PROD' ) && COMPASS_FORCE_PROD ) {
			return false;
		}

		$env_wp = getenv( 'WP_ENV' );
		if ( false !== $env_wp && in_array( strtolower( trim( (string) $env_wp ) ), array( 'production', 'staging' ), true ) ) {
			return false;
		}

		if ( defined( 'WP_ENV' ) && in_array( strtolower( (string) WP_ENV ), array( 'production', 'staging' ), true ) ) {
			return false;
		}

		if ( ! empty( $_ENV['WP_ENV'] ) && in_array( strtolower( trim( (string) $_ENV['WP_ENV'] ) ), array( 'production', 'staging' ), true ) ) {
			return false;
		}

		if ( ! empty( $_SERVER['WP_ENV'] ) && in_array( strtolower( trim( (string) $_SERVER['WP_ENV'] ) ), array( 'production', 'staging' ), true ) ) {
			return false;
		}

		$env_type = getenv( 'WP_ENVIRONMENT_TYPE' );
		if ( false !== $env_type && in_array( strtolower( trim( (string) $env_type ) ), array( 'production', 'staging' ), true ) ) {
			return false;
		}

		if ( defined( 'WP_ENVIRONMENT_TYPE' ) && in_array( strtolower( (string) WP_ENVIRONMENT_TYPE ), array( 'production', 'staging' ), true ) ) {
			return false;
		}

		if ( function_exists( 'wp_get_environment_type' ) && in_array( wp_get_environment_type(), array( 'production', 'staging' ), true ) ) {
			if ( defined( 'WP_ENVIRONMENT_TYPE' ) || false !== $env_type || ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
				return false;
			}
		}

		// 2. Explicit development indicators
		if ( isset( $_GET['dev'] ) || isset( $_GET['vite'] ) ) {
			return true;
		}

		if ( false !== $env_wp && 'development' === strtolower( trim( (string) $env_wp ) ) ) {
			return true;
		}

		if ( defined( 'WP_ENV' ) && 'development' === strtolower( (string) WP_ENV ) ) {
			return true;
		}

		if ( ! empty( $_ENV['WP_ENV'] ) && 'development' === strtolower( trim( (string) $_ENV['WP_ENV'] ) ) ) {
			return true;
		}

		if ( ! empty( $_SERVER['WP_ENV'] ) && 'development' === strtolower( trim( (string) $_SERVER['WP_ENV'] ) ) ) {
			return true;
		}

		if ( function_exists( 'wp_get_environment_type' ) && 'development' === wp_get_environment_type() ) {
			return true;
		}

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			return true;
		}

		if ( defined( 'COMPASS_DEV_HOST' ) || ( false !== getenv( 'COMPASS_DEV_HOST' ) && '' !== trim( (string) getenv( 'COMPASS_DEV_HOST' ) ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get candidate directories containing static app assets.
	 *
	 * @return array<int, string>
	 */
	protected function get_static_lookup_dirs(): array {
		$dirs = array();

		// 1. Monorepo app public folder (e.g. apps/my-card-vault/public/)
		$monorepo_app_dir = dirname( $this->plugin_path, 3 ) . '/apps/my-' . $this->slug . '/public/';
		if ( is_dir( $monorepo_app_dir ) ) {
			$dirs[] = $monorepo_app_dir;
		}
		$monorepo_app_dir_alt = dirname( $this->plugin_path, 3 ) . '/apps/' . $this->slug . '/public/';
		if ( is_dir( $monorepo_app_dir_alt ) ) {
			$dirs[] = $monorepo_app_dir_alt;
		}

		// 2. Plugin dist folder (e.g. wp-content/plugins/.../public/dist/)
		$plugin_dist_dir = $this->plugin_path . 'public/dist/';
		if ( is_dir( $plugin_dist_dir ) ) {
			$dirs[] = $plugin_dist_dir;
		}

		// 3. ABSPATH fallbacks
		if ( defined( 'ABSPATH' ) ) {
			$abs_app = ABSPATH . 'apps/my-' . $this->slug . '/public/';
			if ( is_dir( $abs_app ) ) {
				$dirs[] = $abs_app;
			}
			$abs_app_alt = ABSPATH . 'apps/' . $this->slug . '/public/';
			if ( is_dir( $abs_app_alt ) ) {
				$dirs[] = $abs_app_alt;
			}
		}

		return array_unique( $dirs );
	}

	/**
	 * Check whether subpath corresponds to a known static asset pattern.
	 *
	 * @param string $subpath Subpath to verify.
	 * @return bool
	 */
	protected function is_static_asset_path( string $subpath ): bool {
		if ( str_contains( $subpath, '..' ) ) {
			return false;
		}

		$is_asset_folder = str_starts_with( $subpath, 'icons/' ) || str_starts_with( $subpath, 'assets/' );
		$is_favicon      = (bool) preg_match( '#^favicon\.(ico|svg|png)$#i', $subpath );
		$has_asset_ext   = (bool) preg_match( '#\.(ico|svg|png|jpe?g|webp|woff2?|ttf|json|css|js|map)$#i', $subpath );

		return $is_asset_folder || $is_favicon || $has_asset_ext;
	}

	/**
	 * Serve the Service Worker with permissive Service-Worker-Allowed header.
	 */
	protected function serve_service_worker(): void {
		status_header( 200 );
		header( 'Content-Type: application/javascript; charset=UTF-8' );
		header( 'Service-Worker-Allowed: /' );
		header( 'Cache-Control: no-cache, no-store, must-revalidate' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		// Check local disk directories first
		foreach ( $this->get_static_lookup_dirs() as $dir ) {
			$file = $dir . 'sw.js';
			if ( file_exists( $file ) ) {
				readfile( $file );
				return;
			}
		}

		// If in dev mode, attempt proxy from active Vite server
		if ( $this->is_dev_mode() ) {
			$active_host = self::resolve_host( $this->dev_port );
			if ( $active_host ) {
				$response = wp_remote_get( "http://{$active_host}:{$this->dev_port}/sw.js", array(
					'headers' => array( 'Host' => 'localhost:' . $this->dev_port ),
					'timeout' => 2,
				) );
				if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
					echo wp_remote_retrieve_body( $response );
					return;
				}
			}
		}

		status_header( 404 );
		echo '// Service worker not found';
	}

	/**
	 * Serve Web App Manifest with correct manifest MIME type and CORS headers.
	 *
	 * @param string $filename Manifest filename.
	 */
	protected function serve_manifest( string $filename ): void {
		status_header( 200 );
		header( 'Content-Type: application/manifest+json; charset=UTF-8' );
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Cache-Control: public, max-age=3600' );

		// Check local disk directories first
		foreach ( $this->get_static_lookup_dirs() as $dir ) {
			$file = $dir . $filename;
			if ( file_exists( $file ) ) {
				readfile( $file );
				return;
			}
		}

		// If in dev mode, attempt proxy from active Vite server
		if ( $this->is_dev_mode() ) {
			$active_host = self::resolve_host( $this->dev_port );
			if ( $active_host ) {
				$response = wp_remote_get( "http://{$active_host}:{$this->dev_port}/{$filename}", array(
					'headers' => array( 'Host' => 'localhost:' . $this->dev_port ),
					'timeout' => 2,
				) );
				if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
					echo wp_remote_retrieve_body( $response );
					return;
				}
			}
		}

		status_header( 404 );
		echo '{"error":"Manifest not found"}';
	}

	/**
	 * Serve static asset from disk or Vite dev server with appropriate Content-Type.
	 *
	 * @param string $subpath Subpath of static asset.
	 * @return bool True if served, false otherwise.
	 */
	protected function serve_static_asset( string $subpath ): bool {
		if ( str_contains( $subpath, '..' ) ) {
			return false;
		}

		$mimes = array(
			'ico'   => 'image/x-icon',
			'svg'   => 'image/svg+xml',
			'png'   => 'image/png',
			'jpg'   => 'image/jpeg',
			'jpeg'  => 'image/jpeg',
			'webp'  => 'image/webp',
			'woff2' => 'font/woff2',
			'woff'  => 'font/woff',
			'ttf'   => 'font/ttf',
			'json'  => 'application/json; charset=UTF-8',
			'css'   => 'text/css; charset=UTF-8',
			'js'    => 'application/javascript; charset=UTF-8',
			'map'   => 'application/json; charset=UTF-8',
		);

		$ext  = strtolower( pathinfo( $subpath, PATHINFO_EXTENSION ) );
		$mime = $mimes[ $ext ] ?? 'application/octet-stream';

		foreach ( $this->get_static_lookup_dirs() as $dir ) {
			$file = $dir . $subpath;
			if ( file_exists( $file ) && ! is_dir( $file ) ) {
				status_header( 200 );
				header( 'Content-Type: ' . $mime );
				header( 'Access-Control-Allow-Origin: *' );
				header( 'Cache-Control: public, max-age=86400' );
				readfile( $file );
				return true;
			}
		}

		// Try Vite dev server in dev mode
		if ( $this->is_dev_mode() ) {
			$active_host = self::resolve_host( $this->dev_port );
			if ( $active_host ) {
				$response = wp_remote_get( "http://{$active_host}:{$this->dev_port}/{$subpath}", array(
					'headers' => array( 'Host' => 'localhost:' . $this->dev_port ),
					'timeout' => 2,
				) );
				if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
					$content_type = wp_remote_retrieve_header( $response, 'content-type' ) ?: $mime;
					// If Vite returned HTML for a non-HTML asset request, treat as fallback 404
					if ( str_contains( $content_type, 'text/html' ) && ! str_contains( $mime, 'text/html' ) ) {
						return false;
					}
					status_header( 200 );
					header( 'Content-Type: ' . $content_type );
					header( 'Access-Control-Allow-Origin: *' );
					echo wp_remote_retrieve_body( $response );
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Intercept front-end requests targeting this SPA.
	 */
	public function handle_template_redirect(): void {
		$request_uri  = $_SERVER['REQUEST_URI'] ?? '';
		$request_path = wp_parse_url( $request_uri, PHP_URL_PATH ) ?? '';

		if ( strpos( $request_path, '/wp-admin' ) === 0 || strpos( $request_path, '/wp-login.php' ) === 0 ) {
			return;
		}

		$opt_prefix = 'xophz_compass_' . str_replace( '-', '_', $this->slug );
		$slug       = get_option( $opt_prefix . '_custom_slug', $this->default_slug );
		$load_mode  = get_option( $opt_prefix . '_load_mode', 'routes_only' );
		$page_id    = (int) get_option( $opt_prefix . '_load_page_id', 0 );

		$slug_prefix = ! empty( $slug ) ? '/' . trim( $slug, '/' ) : '';

		$is_target = (bool) get_query_var( $this->query_var );

		if ( ! $is_target ) {
			if ( 'homepage' === $load_mode && ( is_front_page() || is_home() || '/' === $request_path || in_array( $request_path, array( '/sw.js', '/manifest.webmanifest', '/manifest.json' ), true ) ) ) {
				$is_target = true;
			} elseif ( 'specific_page' === $load_mode && $page_id > 0 && is_page( $page_id ) ) {
				$is_target = true;
			} elseif ( ! empty( $slug_prefix ) && ( $request_path === $slug_prefix || str_starts_with( $request_path, $slug_prefix . '/' ) ) ) {
				$is_target = true;
			}
		}

		if ( ! $is_target ) {
			return;
		}

		// Ensure trailing slash on base directory to avoid broken relative URL resolution
		if ( ! empty( $slug_prefix ) && $request_path === $slug_prefix ) {
			$query_string = isset( $_SERVER['QUERY_STRING'] ) && '' !== $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
			wp_safe_redirect( home_url( $slug_prefix . '/' . $query_string ), 301 );
			exit;
		}

		// Determine requested subpath relative to app mount
		$subpath = '';
		if ( ! empty( $slug_prefix ) && str_starts_with( $request_path, $slug_prefix . '/' ) ) {
			$subpath = substr( $request_path, strlen( $slug_prefix ) + 1 );
		} elseif ( 'homepage' === $load_mode || empty( $slug_prefix ) || '/' === $request_path ) {
			$subpath = ltrim( $request_path, '/' );
		}
		$subpath = ltrim( $subpath, '/' );

		// Serve Service Worker with root-level Service-Worker-Allowed header
		if ( 'sw.js' === $subpath ) {
			$this->serve_service_worker();
			exit;
		}

		// Serve Web App Manifest
		if ( 'manifest.webmanifest' === $subpath || 'manifest.json' === $subpath ) {
			$this->serve_manifest( $subpath );
			exit;
		}

		// Serve static assets (icons, images, favicon)
		if ( ! empty( $subpath ) && $this->is_static_asset_path( $subpath ) ) {
			$served = $this->serve_static_asset( $subpath );
			if ( $served ) {
				exit;
			}
			// If a static asset file was requested but not found, return 404 instead of falling through to SPA HTML
			status_header( 404 );
			header( 'Content-Type: text/plain; charset=UTF-8' );
			echo 'Asset not found: ' . esc_html( $subpath );
			exit;
		}

		status_header( 200 );
		global $wp_query;
		if ( $wp_query ) {
			$wp_query->is_404 = false;
		}

		if ( $this->is_dev_mode() ) {
			$dev_html = $this->fetch_dev_server_html();
			if ( false !== $dev_html ) {
				$dev_html = apply_filters( 'xophz_compass_dev_proxy_html', $dev_html, $this->slug, $this );
				$dev_html = apply_filters( "xophz_compass_dev_proxy_{$this->slug}_html", $dev_html, $this->slug, $this );
				header( 'Content-Type: text/html; charset=UTF-8' );
				echo $dev_html;
				exit;
			}
		}

		// Production Dist Serving
		$prod_html = $this->load_production_dist();
		$prod_html = apply_filters( 'xophz_compass_dev_proxy_html', $prod_html, $this->slug, $this );
		$prod_html = apply_filters( "xophz_compass_dev_proxy_{$this->slug}_html", $prod_html, $this->slug, $this );
		header( 'Content-Type: text/html; charset=UTF-8' );
		echo $prod_html;
		exit;
	}

	/**
	 * Fetch HTML from active Vite dev server using fast socket health checks.
	 * Avoids multi-second blocking timeouts when Vite is offline.
	 *
	 * @return string|false
	 */
	protected function fetch_dev_server_html() {
		$active_host = self::resolve_host( $this->dev_port );
		if ( ! $active_host ) {
			return false;
		}

		$url = "http://{$active_host}:{$this->dev_port}/";
		$response = wp_remote_get( $url, array(
			'timeout'     => 2,
			'redirection' => 2,
		) );

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
			return false;
		}

		$html = wp_remote_retrieve_body( $response );
		if ( empty( $html ) ) {
			return false;
		}

		$raw_host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( $_SERVER['HTTP_HOST'] ) : 'localhost';
		$wp_host  = self::extract_host( $raw_host );
		$vite_url = '//' . $wp_host . ':' . $this->dev_port;

		// Rewrite relative asset imports to Vite dev server
		$html = str_replace( 'src="/', 'src="' . $vite_url . '/', $html );
		$html = str_replace( 'href="/', 'href="' . $vite_url . '/', $html );
		$html = str_replace( 'import("/', 'import("' . $vite_url . '/', $html );
		$html = str_replace( 'from "/', 'from="' . $vite_url . '/', $html );
		$html = str_replace( "from '/", "from '" . $vite_url . '/', $html );

		// Preserve manifest, service worker, and icons on the local WordPress route
		$html = preg_replace(
			'#href="//' . preg_quote( $wp_host . ':' . $this->dev_port, '#' ) . '/(manifest\.(?:webmanifest|json)|sw\.js|favicon\.(?:ico|svg)|icons/[^"]+)"#',
			'href="$1"',
			$html
		);

		// Inject Vite client for HMR if missing
		if ( strpos( $html, '/@vite/client' ) === false ) {
			$client_tag = '<script type="module" src="' . esc_url( $vite_url ) . '/@vite/client"></script>';
			$html = str_replace( '</head>', $client_tag . "\n</head>", $html );
		}

		// Inject window.wpApiSettings
		$settings_script = $this->build_api_settings_script();
		$html = str_replace( '</head>', $settings_script . "\n</head>", $html );

		return $html;
	}

	/**
	 * Load compiled production dist output.
	 *
	 * @return string
	 */
	protected function load_production_dist(): string {
		$target_file = false;
		foreach ( $this->candidate_dist_paths as $path ) {
			if ( file_exists( $path ) ) {
				$target_file = $path;
				break;
			}
		}

		if ( ! $target_file ) {
			return sprintf(
				'<!DOCTYPE html><html><head><title>%s</title></head><body style="background:#0f172a;color:#f8fafc;font-family:system-ui;padding:40px;"><h2>%s build not found.</h2><p>Please run the production build script in the repository root.</p></body></html>',
				esc_html( $this->slug ),
				esc_html( $this->slug )
			);
		}

		$html = file_get_contents( $target_file );
		if ( false === $html ) {
			return '<p>Error reading build file.</p>';
		}

		$relative_plugin_url = function_exists( 'wp_make_link_relative' ) ? wp_make_link_relative( $this->plugin_url ) : preg_replace( '#^https?://[^/]+#i', '', $this->plugin_url );
		$dist_url            = rtrim( $relative_plugin_url, '/' ) . '/public/dist/';

		// Rewrite absolute and relative dist assets
		$html = str_replace( '"/assets/', '"' . $dist_url . 'assets/', $html );
		$html = str_replace( "'/assets/", "'" . $dist_url . "assets/", $html );
		$html = str_replace( '"./assets/', '"' . $dist_url . 'assets/', $html );
		$html = str_replace( "'./assets/", "'" . $dist_url . "assets/", $html );
		$html = str_replace( '"/vite.svg"', '"' . $dist_url . 'vite.svg"', $html );
		$html = str_replace( '"./vite.svg"', '"' . $dist_url . 'vite.svg"', $html );
		$html = str_replace( '"/registerSW.js"', '"' . $dist_url . 'registerSW.js"', $html );
		$html = str_replace( '"./registerSW.js"', '"' . $dist_url . 'registerSW.js"', $html );
		$html = str_replace( '"/sw.js"', '"' . $dist_url . 'sw.js"', $html );
		$html = str_replace( '"/_nuxt/', '"' . $dist_url . '_nuxt/', $html );
		$html = str_replace( "'/_nuxt/", "'" . $dist_url . "_nuxt/", $html );

		// Inject window.wpApiSettings
		$settings_script = $this->build_api_settings_script();
		$html = str_replace( '</head>', $settings_script . "\n</head>", $html );

		return $html;
	}

	/**
	 * Build window.wpApiSettings script tag with authenticated session context.
	 *
	 * @return string
	 */
	protected function build_api_settings_script(): string {
		$user_id   = get_current_user_id();
		$user_data = null;

		if ( $user_id > 0 ) {
			$u = wp_get_current_user();
			$user_data = array(
				'id'           => 'wp-' . $user_id,
				'username'     => $u->user_login,
				'email'        => $u->user_email,
				'fullName'     => ! empty( $u->display_name ) ? $u->display_name : $u->user_login,
				'avatarUrl'    => get_avatar_url( $user_id ) ?: '',
				'role'         => in_array( 'administrator', (array) $u->roles, true ) ? 'admin' : 'user',
				'registeredAt' => strtotime( $u->user_registered ) * 1000,
			);
		}

		$root_url   = function_exists( 'wp_make_link_relative' ) ? wp_make_link_relative( rest_url() ) : '/wp-json/';
		$plugin_url = function_exists( 'wp_make_link_relative' ) ? wp_make_link_relative( $this->plugin_url ) : preg_replace( '#^https?://[^/]+#i', '', $this->plugin_url );

		$raw_title  = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'name' ) : '';
		$site_title = function_exists( 'wp_specialchars_decode' )
			? wp_specialchars_decode( $raw_title, ENT_QUOTES )
			: htmlspecialchars_decode( (string) $raw_title, ENT_QUOTES );

		$payload = array(
			'siteTitle'   => $site_title,
			'root'        => esc_url_raw( $root_url ),
			'nonce'       => wp_create_nonce( 'wp_rest' ),
			'pluginUrl'   => esc_url_raw( $plugin_url ),
			'version'     => esc_js( $this->version ),
			'userId'      => $user_id,
			'currentUser' => $user_data,
		);

		if ( ! empty( $this->extra_settings ) && is_array( $this->extra_settings ) ) {
			$payload = array_merge( $payload, $this->extra_settings );
		}

		$payload = apply_filters( 'xophz_compass_dev_proxy_settings', $payload, $this->slug, $this );
		$payload = apply_filters( "xophz_compass_dev_proxy_{$this->slug}_api_settings", $payload, $this->slug, $this );

		return '<script>window.wpApiSettings = ' . wp_json_encode( $payload ) . ';</script>';
	}
}
