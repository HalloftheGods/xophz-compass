<?php
/**
 * Abstract base class for all Compass child plugins.
 * Eliminates duplicate main plugin classes, activator/deactivator stubs, and loaders.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/interface-compass-plugin.php';
require_once __DIR__ . '/trait-compass-hookable.php';

abstract class Xophz_Compass_Plugin_Base implements Xophz_Compass_Plugin_Interface {
	use Xophz_Compass_Hookable_Trait;

	/**
	 * Unique plugin slug (e.g. 'bomb-bag', 'card-vault').
	 */
	protected string $slug;

	/**
	 * Plugin version (SemVer).
	 */
	protected string $version;

	/**
	 * Main plugin entry file path.
	 */
	protected string $plugin_file;

	/**
	 * Main plugin directory path (with trailing slash).
	 */
	protected string $plugin_path;

	/**
	 * Main plugin directory URL (with trailing slash).
	 */
	protected string $plugin_url;

	/**
	 * Text domain for internationalization.
	 */
	protected string $text_domain;

	/**
	 * Constructor supporting flexible parameter order.
	 *
	 * Supports:
	 * 1. __construct( string $plugin_file, string $version, string $text_domain )
	 * 2. __construct( string $slug, string $version, string $plugin_file )
	 *
	 * @param string $param1  Plugin file path or slug.
	 * @param string $version Plugin SemVer version.
	 * @param string $param3  Text domain or plugin file path.
	 */
	public function __construct( string $param1, string $version = '1.0.0', string $param3 = '' ) {
		$this->version = $version;

		// Detect parameter layout
		if ( strpos( $param1, '/' ) !== false || substr( $param1, -4 ) === '.php' ) {
			// Layout 1: $param1 is $plugin_file
			$this->plugin_file = $param1;
			$this->plugin_path = plugin_dir_path( $param1 );
			$this->plugin_url  = plugin_dir_url( $param1 );

			if ( ! empty( $param3 ) && strpos( $param3, '/' ) === false && substr( $param3, -4 ) !== '.php' ) {
				$this->text_domain = $param3;
				$this->slug        = str_replace( 'xophz-compass-', '', $param3 );
			} else {
				$dir_name          = basename( dirname( $param1 ) );
				$this->text_domain = $dir_name;
				$this->slug        = str_replace( 'xophz-compass-', '', $dir_name );
			}
		} else {
			// Layout 2: $param1 is $slug, $param3 is $plugin_file
			$this->slug        = $param1;
			$this->text_domain = 'xophz-compass-' . $param1;
			$this->plugin_file = ! empty( $param3 ) ? $param3 : '';
			$this->plugin_path = ! empty( $param3 ) ? plugin_dir_path( $param3 ) : '';
			$this->plugin_url  = ! empty( $param3 ) ? plugin_dir_url( $param3 ) : '';
		}

		$this->setup_core_hooks();
	}

	/**
	 * Setup standard lifecycle and integration hooks.
	 */
	protected function setup_core_hooks(): void {
		// WP 6.7+ compliant translation loading on init priority 5
		add_action( 'init', array( $this, 'load_textdomain' ), 5 );

		// Admin menu registration with graceful degradation
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 20 );

		// Settings link on Plugins list page
		if ( ! empty( $this->plugin_file ) ) {
			add_filter( 'plugin_action_links_' . plugin_basename( $this->plugin_file ), array( $this, 'add_action_links' ) );
		}

		// Automatic YouMeOS Spark registration if configured
		add_filter( 'xophz_register_sparks', array( $this, 'filter_spark_registration' ) );
		add_filter( 'xophz_get_spark_manifest', array( $this, 'filter_spark_manifest' ), 10, 2 );

		// Run child initialization
		$this->init();
	}

	/**
	 * Run the plugin hook accumulator.
	 */
	public function run(): void {
		$this->run_hooks();
	}

	/**
	 * Default init callback for child classes.
	 */
	public function init(): void {
		// Child classes override this to register submodules and hooks.
	}

	/**
	 * Load plugin textdomain safely on init.
	 */
	public function load_textdomain(): void {
		if ( ! empty( $this->plugin_file ) ) {
			load_plugin_textdomain(
				$this->text_domain,
				false,
				dirname( plugin_basename( $this->plugin_file ) ) . '/languages/'
			);
		}
	}

	/**
	 * Register admin menu entry.
	 * If core Xophz_Compass is active, integrates into Compass command deck.
	 * If core is inactive, gracefully creates a standalone options page to prevent fatal errors.
	 */
	public function register_admin_menu(): void {
		if ( class_exists( 'Xophz_Compass' ) && method_exists( 'Xophz_Compass', 'add_submenu' ) ) {
			Xophz_Compass::add_submenu( 'xophz-compass-' . $this->slug );
			return;
		}

		// Fallback: standalone menu when core is disabled
		$title = ucwords( str_replace( '-', ' ', $this->slug ) );
		add_options_page(
			$title . ' Settings',
			$title,
			'manage_options',
			'xophz-compass-' . $this->slug,
			array( $this, 'render_fallback_admin_page' )
		);
	}

	/**
	 * Fallback admin page if core xophz-compass is inactive.
	 */
	public function render_fallback_admin_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized access.', 'xophz-compass' ) );
		}
		$title = ucwords( str_replace( '-', ' ', $this->slug ) );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( $title ); ?></h1>
			<p><?php esc_html_e( 'Project Compass Core plugin is currently inactive. Activate My Compass Engine for full Command Deck integration.', 'xophz-compass' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Add Settings shortcut link to WordPress Plugins list page.
	 *
	 * @param array<string, string> $links Current action links.
	 * @return array<string, string> Modified links.
	 */
	public function add_action_links( array $links ): array {
		$settings_url = admin_url( 'options-general.php?page=xophz-compass-' . $this->slug );
		$settings_link = '<a href="' . esc_url( $settings_url ) . '">' . esc_html__( 'Settings', 'xophz-compass' ) . '</a>';
		return array_merge( array( 'settings' => $settings_link ), $links );
	}

	/**
	 * Optional Spark declaration for YouMeOS. Child plugins override this to publish spark definitions.
	 *
	 * @return array<string, mixed>|null
	 */
	public function get_spark_definition(): ?array {
		return null;
	}

	/**
	 * Filter callback for xophz_register_sparks.
	 *
	 * @param array<string, mixed> $sparks Existing registered sparks.
	 * @return array<string, mixed>
	 */
	public function filter_spark_registration( array $sparks ): array {
		$def = $this->get_spark_definition();
		if ( $def && isset( $def['id'] ) ) {
			$sparks[ $def['id'] ] = $def;
		}
		return $sparks;
	}

	/**
	 * Filter callback for xophz_get_spark_manifest.
	 *
	 * @param mixed  $manifest Existing manifest or null.
	 * @param string $spark_id Requested spark ID.
	 * @return mixed
	 */
	public function filter_spark_manifest( $manifest, string $spark_id ) {
		$def = $this->get_spark_definition();
		if ( $def && isset( $def['id'] ) && $def['id'] === $spark_id ) {
			return $def;
		}
		return $manifest;
	}

	/**
	 * Get the plugin unique identifier slug.
	 */
	public function get_slug(): string {
		return $this->slug;
	}

	/**
	 * Get current SemVer version.
	 */
	public function get_version(): string {
		return $this->version;
	}

	/**
	 * Get directory path.
	 */
	public function get_path(): string {
		return $this->plugin_path;
	}

	/**
	 * Get directory URL.
	 */
	public function get_url(): string {
		return $this->plugin_url;
	}

	/**
	 * Default activation hook callback.
	 */
	public static function activate(): void {
		// Override in child class if custom table creation or migration is required.
	}

	/**
	 * Default deactivation hook callback.
	 */
	public static function deactivate(): void {
		// Override in child class if cleanup is required.
	}
}
