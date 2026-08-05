<?php

class Xophz_Compass_Updater {

	private static $registry = [];
	private static $theme_registry = [];
	private static $initialized = false;

	const CACHE_TTL = 43200;
	const GITHUB_API = 'https://api.github.com/repos';
	const RAW_CONTENT = 'https://raw.githubusercontent.com';
	const ORG = 'HalloftheGods';

	public static function init() {
		if ( self::$initialized ) return;
		self::$initialized = true;

		self::discover_plugins();
		self::discover_themes();

		if ( isset( $_GET['xophz_force_update'] ) && current_user_can( 'manage_options' ) ) {
			delete_site_transient( 'update_plugins' );
			delete_site_transient( 'update_themes' );
			foreach ( self::$registry as $file => $plugin ) {
				delete_transient( 'xophz_gh_rel_' . md5( $plugin['repo'] ) );
			}
			foreach ( self::$theme_registry as $slug => $theme ) {
				delete_transient( 'xophz_gh_rel_' . md5( $theme['repo'] ) );
			}
			wp_safe_redirect( admin_url( 'plugins.php' ) );
			exit;
		}

		// Hook into both pre_set and the transient read to ensure our updates are always injected
		add_filter( 'pre_set_site_transient_update_plugins', [ __CLASS__, 'check_for_updates' ] );
		add_filter( 'site_transient_update_plugins', [ __CLASS__, 'check_for_updates' ] );
		add_filter( 'update_plugins_github.com', [ __CLASS__, 'github_update_provider' ], 10, 3 );
		add_filter( 'plugins_api', [ __CLASS__, 'plugin_info' ], 20, 3 );
		add_filter( 'plugin_row_meta', [ __CLASS__, 'plugin_row_meta' ], 10, 2 );

		// Theme hooks
		add_filter( 'pre_set_site_transient_update_themes', [ __CLASS__, 'check_for_theme_updates' ] );
		add_filter( 'site_transient_update_themes', [ __CLASS__, 'check_for_theme_updates' ] );
		add_filter( 'themes_api', [ __CLASS__, 'theme_info' ], 20, 3 );

		add_filter( 'upgrader_source_selection', [ __CLASS__, 'upgrader_source_selection' ], 10, 4 );
	}

	private static function discover_plugins() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins = get_plugins();

		foreach ( $all_plugins as $file => $data ) {
			$text_domain = $data['TextDomain'] ?? '';
			$is_compass_plugin = strpos( $text_domain, 'xophz-' ) === 0;

			if ( ! $is_compass_plugin ) continue;

			$repo = self::ORG . '/' . $text_domain;
			self::$registry[ $file ] = [
				'repo'    => $repo,
				'slug'    => $text_domain,
				'version' => $data['Version'] ?? '0.0.0',
				'name'    => $data['Name'] ?? $text_domain,
			];
		}
	}

	private static function discover_themes() {
		$themes = wp_get_themes();

		foreach ( $themes as $slug => $theme ) {
			$text_domain = $theme->get( 'TextDomain' );
			$is_compass_theme = strpos( $text_domain, 'xophz-' ) === 0 || strpos( $text_domain, 'forthexp-' ) === 0;

			if ( ! $is_compass_theme ) continue;

			$repo = self::ORG . '/' . $text_domain;
			self::$theme_registry[ $slug ] = [
				'repo'    => $repo,
				'slug'    => $slug,
				'version' => $theme->get( 'Version' ) ?: '0.0.0',
				'name'    => $theme->get( 'Name' ) ?: $text_domain,
			];
		}
	}

	public static function check_for_updates( $transient ) {
		if ( ! is_object( $transient ) ) return $transient;

		$is_update_check = ! empty( $transient->checked );
		$is_force_check  = isset( $_GET['xophz_force_update'] ) && current_user_can( 'manage_options' );
		
		if ( ! $is_update_check && ! $is_force_check ) return $transient;

		if ( ! isset( $transient->response ) || ! is_array( $transient->response ) ) {
			$transient->response = [];
		}
		if ( ! isset( $transient->no_update ) || ! is_array( $transient->no_update ) ) {
			$transient->no_update = [];
		}

		foreach ( self::$registry as $file => $plugin ) {
			$release = self::fetch_release( $plugin['repo'] );
			if ( ! $release ) continue;

			$remote_version = ltrim( $release->tag_name ?? '', 'v' );
			$has_update = version_compare( $remote_version, $plugin['version'], '>' );

			if ( ! $has_update ) {
				$transient->no_update[ $file ] = (object) [
					'slug'        => $plugin['slug'],
					'plugin'      => $file,
					'new_version' => $remote_version,
					'url'         => "https://github.com/{$plugin['repo']}",
				];
				if ( isset( $transient->response[ $file ] ) ) {
					unset( $transient->response[ $file ] );
				}
				continue;
			}

			$download_url = self::get_download_url( $release );
			if ( ! $download_url ) continue;

			$icons = self::get_icon_urls( $plugin['repo'] );

			$transient->response[ $file ] = (object) [
				'slug'        => $plugin['slug'],
				'plugin'      => $file,
				'new_version' => $remote_version,
				'url'         => "https://github.com/{$plugin['repo']}",
				'package'     => $download_url,
				'icons'       => $icons,
				'tested'      => get_bloginfo( 'version' ),
				'requires'    => '6.0',
			];

			if ( isset( $transient->no_update[ $file ] ) ) {
				unset( $transient->no_update[ $file ] );
			}
		}

		return $transient;
	}

	public static function check_for_theme_updates( $transient ) {
		if ( ! is_object( $transient ) ) return $transient;

		$is_update_check = ! empty( $transient->checked );
		$is_force_check  = isset( $_GET['xophz_force_update'] ) && current_user_can( 'manage_options' );
		
		if ( ! $is_update_check && ! $is_force_check ) return $transient;

		if ( ! isset( $transient->response ) || ! is_array( $transient->response ) ) {
			$transient->response = [];
		}
		if ( ! isset( $transient->no_update ) || ! is_array( $transient->no_update ) ) {
			$transient->no_update = [];
		}

		foreach ( self::$theme_registry as $slug => $theme ) {
			$release = self::fetch_release( $theme['repo'] );
			if ( ! $release ) continue;

			$remote_version = ltrim( $release->tag_name ?? '', 'v' );
			$has_update = version_compare( $remote_version, $theme['version'], '>' );

			if ( ! $has_update ) {
				$transient->no_update[ $slug ] = (array) [
					'theme'       => $slug,
					'new_version' => $remote_version,
					'url'         => "https://github.com/{$theme['repo']}",
				];
				if ( isset( $transient->response[ $slug ] ) ) {
					unset( $transient->response[ $slug ] );
				}
				continue;
			}

			$download_url = self::get_download_url( $release );
			if ( ! $download_url ) continue;

			$transient->response[ $slug ] = (array) [
				'theme'       => $slug,
				'new_version' => $remote_version,
				'url'         => "https://github.com/{$theme['repo']}",
				'package'     => $download_url,
				'requires'    => '6.0',
			];

			if ( isset( $transient->no_update[ $slug ] ) ) {
				unset( $transient->no_update[ $slug ] );
			}
		}

		return $transient;
	}

	public static function github_update_provider( $update, $plugin_data, $plugin_file ) {
		if ( ! isset( self::$registry[ $plugin_file ] ) ) {
			return $update;
		}

		$plugin = self::$registry[ $plugin_file ];
		$release = self::fetch_release( $plugin['repo'] );
		if ( ! $release ) return $update;

		$remote_version = ltrim( $release->tag_name ?? '', 'v' );
		$has_update = version_compare( $remote_version, $plugin['version'], '>' );

		if ( ! $has_update ) return $update;

		$download_url = self::get_download_url( $release );
		if ( ! $download_url ) return $update;

		$icons = self::get_icon_urls( $plugin['repo'] );

		return (object) [
			'slug'        => $plugin['slug'],
			'plugin'      => $plugin_file,
			'new_version' => $remote_version,
			'url'         => "https://github.com/{$plugin['repo']}",
			'package'     => $download_url,
			'icons'       => $icons,
			'tested'      => get_bloginfo( 'version' ),
			'requires'    => '6.0',
		];
	}

	public static function plugin_info( $result, $action, $args ) {
		if ( $action !== 'plugin_information' ) return $result;

		$match = null;
		foreach ( self::$registry as $file => $plugin ) {
			$is_match = ( $args->slug ?? '' ) === $plugin['slug'];
			if ( $is_match ) {
				$match = $plugin;
				break;
			}
		}

		if ( ! $match ) return $result;

		$release = self::fetch_release( $match['repo'] );
		if ( ! $release ) return $result;

		$remote_version = ltrim( $release->tag_name ?? '', 'v' );
		$download_url   = self::get_download_url( $release );
		$changelog_md   = $release->body ?? '';
		$changelog_html = self::markdown_to_html( $changelog_md );
		$icons          = self::get_icon_urls( $match['repo'] );

		$info = new stdClass();
		$info->name          = $match['name'];
		$info->slug          = $match['slug'];
		$info->version       = $remote_version;
		$info->author        = '<a href="https://www.hallofthegods.com/">Hall of the Gods, Inc.</a>';
		$info->homepage      = "https://github.com/{$match['repo']}";
		$info->download_link = $download_url;
		$info->trunk         = $download_url;
		$info->tested        = get_bloginfo( 'version' );
		$info->requires      = '6.0';
		$info->requires_php  = '7.4';
		$info->last_updated  = $release->published_at ?? '';
		$info->icons         = $icons;

		$info->sections = [
			'description' => "<p>GitHub: <a href=\"https://github.com/{$match['repo']}\">{$match['repo']}</a></p>",
			'changelog'   => $changelog_html ?: '<p>See GitHub Releases for full changelog.</p>',
		];

		return $info;
	}

	public static function theme_info( $result, $action, $args ) {
		if ( $action !== 'theme_information' ) return $result;

		$match = null;
		foreach ( self::$theme_registry as $slug => $theme ) {
			$is_match = ( $args->slug ?? '' ) === $slug;
			if ( $is_match ) {
				$match = $theme;
				break;
			}
		}

		if ( ! $match ) return $result;

		$release = self::fetch_release( $match['repo'] );
		if ( ! $release ) return $result;

		$remote_version = ltrim( $release->tag_name ?? '', 'v' );
		$download_url   = self::get_download_url( $release );
		$changelog_md   = $release->body ?? '';
		$changelog_html = self::markdown_to_html( $changelog_md );

		$info = new stdClass();
		$info->name          = $match['name'];
		$info->slug          = $match['slug'];
		$info->version       = $remote_version;
		$info->author        = '<a href="https://www.hallofthegods.com/">Hall of the Gods, Inc.</a>';
		$info->homepage      = "https://github.com/{$match['repo']}";
		$info->download_link = $download_url;
		$info->trunk         = $download_url;
		$info->requires      = '6.0';
		$info->requires_php  = '7.4';
		$info->last_updated  = $release->published_at ?? '';

		$info->sections = [
			'description' => "<p>GitHub: <a href=\"https://github.com/{$match['repo']}\">{$match['repo']}</a></p>",
			'changelog'   => $changelog_html ?: '<p>See GitHub Releases for full changelog.</p>',
		];

		return $info;
	}

	public static function plugin_row_meta( $meta, $file ) {
		$is_registered = isset( self::$registry[ $file ] );
		if ( ! $is_registered ) return $meta;

		$repo = self::$registry[ $file ]['repo'];
		$meta[] = '<a href="https://github.com/' . esc_attr( $repo ) . '/releases" target="_blank">View on GitHub</a>';
		$meta[] = '<a href="' . esc_url( admin_url( 'plugins.php?xophz_force_update=1' ) ) . '" style="color: #d63638; font-weight: bold;">Check for Updates</a>';

		return $meta;
	}

	public static function fetch_release( $repo ) {
		$hash         = md5( $repo );
		$cache_key    = 'xophz_gh_rel_' . $hash;
		$fallback_key = 'xophz_gh_rel_last_' . $hash;
		
		$is_force_check = isset( $_GET['xophz_force_update'] ) || isset( $_GET['force-check'] );
		$force_check    = $is_force_check && current_user_can( 'manage_options' );

		if ( $force_check ) {
			delete_transient( $cache_key );
		}

		$cached = get_transient( $cache_key );
		if ( $cached !== false ) return $cached;

		$url = self::GITHUB_API . "/{$repo}/releases/latest";

		$response = wp_remote_get( $url, [
			'timeout' => 15,
			'headers' => [
				'Accept'     => 'application/vnd.github.v3+json',
				'User-Agent' => 'Xophz-Compass-Updater',
			],
		] );

		$is_error = is_wp_error( $response );
		$status   = wp_remote_retrieve_response_code( $response );
		$is_ok    = $status === 200;

		if ( $is_error || ! $is_ok ) {
			// On error, cache the failure for only 5 minutes (instead of 1 hour)
			set_transient( $cache_key, null, 300 );
			
			// Return fallback if available
			return get_option( $fallback_key, null );
		}

		$body    = wp_remote_retrieve_body( $response );
		$release = json_decode( $body );

		if ( ! $release || ! isset( $release->tag_name ) ) {
			set_transient( $cache_key, null, 300 );
			return get_option( $fallback_key, null );
		}

		// Store in transient for high-performance cache
		set_transient( $cache_key, $release, self::CACHE_TTL );
		
		// Store in options for permanent fallback
		update_option( $fallback_key, $release, false );

		return $release;
	}

	public static function get_download_url( $release ) {
		$assets = $release->assets ?? [];

		if ( ! empty( $assets ) ) {
			foreach ( $assets as $asset ) {
				$name = $asset->name ?? '';
				if ( substr( $name, -4 ) === '.zip' ) {
					return $asset->browser_download_url;
				}
			}
		}

		// Strict security: Never fallback to raw GitHub zipball_url (uncompiled source with wrong folder structure)
		return '';
	}

	private static function get_icon_urls( $repo ) {
		$base = self::RAW_CONTENT . "/{$repo}/main";

		return [
			'svg'     => "{$base}/icon.svg",
			'1x'      => "{$base}/icon.png",
			'2x'      => "{$base}/icon.png",
			'default' => "{$base}/icon.svg",
		];
	}

	private static function markdown_to_html( $md ) {
		if ( empty( $md ) ) return '';

		$html = esc_html( $md );

		$html = preg_replace( '/^### (.+)$/m', '<h4>$1</h4>', $html );
		$html = preg_replace( '/^## (.+)$/m', '<h3>$1</h3>', $html );
		$html = preg_replace( '/^# (.+)$/m', '<h2>$1</h2>', $html );

		$html = preg_replace( '/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html );
		$html = preg_replace( '/\*(.+?)\*/', '<em>$1</em>', $html );
		$html = preg_replace( '/`(.+?)`/', '<code>$1</code>', $html );

		$html = preg_replace( '/^- (.+)$/m', '<li>$1</li>', $html );
		$html = preg_replace( '/(<li>.*<\/li>\n?)+/', '<ul>$0</ul>', $html );

		$html = preg_replace( '/\n{2,}/', '</p><p>', $html );
		$html = '<p>' . $html . '</p>';

		$html = str_replace( '<p></p>', '', $html );
		$html = str_replace( '<p><h', '<h', $html );
		$html = str_replace( '</h2></p>', '</h2>', $html );
		$html = str_replace( '</h3></p>', '</h3>', $html );
		$html = str_replace( '</h4></p>', '</h4>', $html );
		$html = str_replace( '<p><ul>', '<ul>', $html );
		$html = str_replace( '</ul></p>', '</ul>', $html );

		return wp_kses_post( $html );
	}

	public static function upgrader_source_selection( $source, $remote_source, $upgrader, $hook_extra = null ) {
		global $wp_filesystem;

		$expected_dir = null;

		if ( isset( $hook_extra['plugin'] ) && isset( self::$registry[ $hook_extra['plugin'] ] ) ) {
			$expected_dir = self::$registry[ $hook_extra['plugin'] ]['slug'];
		} elseif ( isset( $hook_extra['theme'] ) && isset( self::$theme_registry[ $hook_extra['theme'] ] ) ) {
			$expected_dir = self::$theme_registry[ $hook_extra['theme'] ]['slug'];
		}

		if ( ! $expected_dir ) {
			return $source;
		}

		$source_dir = untrailingslashit( $source );
		if ( basename( $source_dir ) === $expected_dir ) {
			return $source;
		}

		$new_source = trailingslashit( $remote_source ) . $expected_dir;
		if ( $wp_filesystem->move( $source, $new_source ) ) {
			return trailingslashit( $new_source );
		}

		return $source;
	}
}
