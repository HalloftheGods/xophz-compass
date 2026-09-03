<?php
/**
 * Standardized Settings & Options Base Class for Compass plugins.
 * Encapsulates settings registration, validation, nonce handling, and rewrite flushing.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Xophz_Compass_Settings_Base {

	/**
	 * Plugin identifier slug.
	 */
	protected string $plugin_slug;

	/**
	 * Option group identifier for settings_fields().
	 */
	protected string $option_group;

	/**
	 * Settings page slug for options-general.php?page=...
	 */
	protected string $page_slug;

	/**
	 * Constructor.
	 *
	 * @param string $plugin_slug Plugin identifier slug.
	 */
	public function __construct( string $plugin_slug ) {
		$this->plugin_slug  = $plugin_slug;
		$this->option_group = 'xophz_compass_' . str_replace( '-', '_', $plugin_slug ) . '_group';
		$this->page_slug    = 'xophz-compass-' . $plugin_slug;

		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register plugin settings. Implemented in concrete subclasses.
	 */
	abstract public function register_settings(): void;

	/**
	 * Helper to register a typed setting.
	 *
	 * @param string $option_name Option database key.
	 * @param string $type        Data type ('string', 'boolean', 'integer', 'number', 'array').
	 * @param mixed  $default     Default value.
	 * @param string $sanitize_cb Optional sanitization callback.
	 */
	protected function register_field( string $option_name, string $type = 'string', $default = '', string $sanitize_cb = '' ): void {
		$args = array(
			'type'              => $type,
			'sanitize_callback' => ! empty( $sanitize_cb ) ? $sanitize_cb : $this->get_default_sanitizer( $type ),
			'default'           => $default,
			'show_in_rest'      => false,
		);

		register_setting( $this->option_group, $option_name, $args );
	}

	/**
	 * Map data type to standard WordPress sanitization callback.
	 *
	 * @param string $type Data type.
	 * @return callable
	 */
	protected function get_default_sanitizer( string $type ): callable {
		switch ( $type ) {
			case 'boolean':
				return 'rest_sanitize_boolean';
			case 'integer':
				return 'absint';
			case 'number':
				return 'floatval';
			case 'array':
				return function( $val ) {
					return is_array( $val ) ? array_map( 'sanitize_text_field', $val ) : array();
				};
			case 'string':
			default:
				return 'sanitize_text_field';
		}
	}

	/**
	 * Render standard settings page wrapper with security nonces.
	 *
	 * @param string $title Page title.
	 */
	public function render_settings_wrapper( string $title ): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized access.', 'xophz-compass' ) );
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( $title ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( $this->option_group );
				do_settings_sections( $this->page_slug );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Hook to flush rewrites when custom slug option is updated.
	 *
	 * @param string $option_key Option key.
	 */
	protected function watch_slug_option_for_rewrite_flush( string $option_key ): void {
		add_action( 'update_option_' . $option_key, function( $old_value, $new_value ) {
			if ( $old_value !== $new_value ) {
				flush_rewrite_rules();
			}
		}, 10, 2 );
	}
}
