<?php
/**
 * Centralized PSR-4 Autoloader & Class Map Engine for Project Compass.
 * Automatically loads Core Helper Suite classes and provides backward-compatibility bridges.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Autoloader {

	/**
	 * Namespace prefix mapping to directories.
	 *
	 * @var array<string, string>
	 */
	protected static array $prefixes = array();

	/**
	 * Class map for Core Helper Suite.
	 *
	 * @var array<string, string>
	 */
	protected static array $class_map = array();

	/**
	 * Register the autoloader with SPL.
	 */
	public static function register(): void {
		self::init_defaults();
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Initialize default mappings for the Core Helper Suite.
	 */
	protected static function init_defaults(): void {
		$core_dir = defined( 'XOPHZ_COMPASS_PATH' ) ? XOPHZ_COMPASS_PATH . 'includes/core/' : __DIR__ . '/';

		self::$prefixes['Xophz\\Compass\\Core\\'] = $core_dir;

		self::$class_map = array(
			'Xophz_Compass_Autoloader'       => $core_dir . 'class-compass-autoloader.php',
			'Xophz_Compass_Plugin_Interface' => $core_dir . 'interface-compass-plugin.php',
			'Xophz_Compass_Hookable_Trait'   => $core_dir . 'trait-compass-hookable.php',
			'Xophz_Compass_Plugin_Base'      => $core_dir . 'class-compass-plugin-base.php',
			'Xophz_Compass_REST_Controller'  => $core_dir . 'class-compass-rest-controller.php',
			'Xophz_Compass_Settings_Base'    => $core_dir . 'class-compass-settings-base.php',
			'Xophz_Compass_Dev_Proxy'        => $core_dir . 'class-compass-dev-proxy.php',
			'Xophz_Compass_Security'         => $core_dir . 'class-compass-security.php',
			'Xophz_Compass_HTTP'             => $core_dir . 'class-compass-http.php',
			'Xophz_Compass_Sanitization'     => $core_dir . 'class-compass-sanitization.php',
		);
	}

	/**
	 * Add a namespace prefix mapping.
	 *
	 * @param string $prefix Namespace prefix with trailing backslash.
	 * @param string $base_dir Directory path with trailing slash.
	 */
	public static function add_namespace( string $prefix, string $base_dir ): void {
		self::$prefixes[ trim( $prefix, '\\' ) . '\\' ] = rtrim( $base_dir, '/' ) . '/';
	}

	/**
	 * Autoload callback.
	 *
	 * @param string $class Fully qualified class name.
	 */
	public static function autoload( string $class ): void {
		// Check explicit class map first
		if ( isset( self::$class_map[ $class ] ) ) {
			if ( file_exists( self::$class_map[ $class ] ) ) {
				require_once self::$class_map[ $class ];
				return;
			}
		}

		// Check PSR-4 prefixes
		foreach ( self::$prefixes as $prefix => $base_dir ) {
			$len = strlen( $prefix );
			if ( strncmp( $prefix, $class, $len ) !== 0 ) {
				continue;
			}

			$relative_class = substr( $class, $len );
			$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

			if ( file_exists( $file ) ) {
				require_once $file;
				return;
			}
		}

		// Dynamic resolution for Xophz_Compass_* classes in core directory
		if ( strpos( $class, 'Xophz_Compass_' ) === 0 ) {
			$core_dir = defined( 'XOPHZ_COMPASS_PATH' ) ? XOPHZ_COMPASS_PATH . 'includes/core/' : __DIR__ . '/';
			$name     = strtolower( str_replace( '_', '-', substr( $class, strlen( 'Xophz_Compass_' ) ) ) );

			$candidates = array(
				$core_dir . 'class-compass-' . $name . '.php',
				$core_dir . 'interface-compass-' . $name . '.php',
				$core_dir . 'trait-compass-' . $name . '.php',
			);

			foreach ( $candidates as $candidate ) {
				if ( file_exists( $candidate ) ) {
					require_once $candidate;
					return;
				}
			}
		}
	}
}
