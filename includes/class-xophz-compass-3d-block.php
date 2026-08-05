<?php
/**
 * 3D Animated Block Canvas Widget and Shortcode
 *
 * Provides shortcode [compass_3d_block] and WP Widget Xophz_Compass_3D_Block_Widget
 * rendering an interactive 3D block assembly animation powered by Three.js.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_3D_Block {

	/**
	 * Unique instance identifier counter.
	 *
	 * @var int
	 */
	private static $instance_count = 0;

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		add_shortcode( 'compass_3d_block', array( __CLASS__, 'render_shortcode' ) );
		add_action( 'widgets_init', array( __CLASS__, 'register_widget' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
	}

	/**
	 * Register frontend assets.
	 */
	public static function register_assets() {
		$plugin_dir_url = plugin_dir_url( dirname( __FILE__ ) );
		$version        = defined( 'XOPHZ_COMPASS_VERSION' ) ? XOPHZ_COMPASS_VERSION : '1.0.0';

		// Enqueue Three.js from unpkg CDN
		wp_register_script(
			'three-js',
			'https://unpkg.com/three@0.160.0/build/three.min.js',
			array(),
			'0.160.0',
			true
		);

		// Register 3D Block JS & CSS
		wp_register_script(
			'compass-3d-block-js',
			$plugin_dir_url . 'public/js/compass-3d-block.js',
			array( 'three-js' ),
			$version,
			true
		);

		wp_register_style(
			'compass-3d-block-css',
			$plugin_dir_url . 'public/css/compass-3d-block.css',
			array(),
			$version
		);
	}

	/**
	 * Render the [compass_3d_block] shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public static function render_shortcode( $atts ) {
		$defaults = array(
			'height'       => '450px',
			'theme'        => 'dark',
			'grid_size'    => '3',
			'accent_color' => 'rgb(61, 238, 152)',
			'interactive'  => 'true',
		);

		$atts = shortcode_atts( $defaults, $atts, 'compass_3d_block' );

		// Enqueue registered assets
		wp_enqueue_script( 'compass-3d-block-js' );
		wp_enqueue_style( 'compass-3d-block-css' );

		self::$instance_count++;
		$canvas_id = 'compass-3d-block-' . self::$instance_count;

		$height       = esc_attr( $atts['height'] );
		$theme        = esc_attr( $atts['theme'] );
		$grid_size    = esc_attr( $atts['grid_size'] );
		$accent_color = esc_attr( $atts['accent_color'] );
		$interactive  = esc_attr( $atts['interactive'] );

		ob_start();
		?>
		<div id="<?php echo esc_attr( $canvas_id ); ?>" 
			class="compass-3d-block-wrapper" 
			style="height: <?php echo $height; ?>;" 
			data-theme="<?php echo $theme; ?>" 
			data-grid-size="<?php echo $grid_size; ?>" 
			data-accent-color="<?php echo $accent_color; ?>" 
			data-interactive="<?php echo $interactive; ?>">
			<canvas class="compass-3d-block-canvas"></canvas>
			<div class="compass-3d-block-overlay">
				<div class="compass-3d-block-glow"></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Register WordPress Widget.
	 */
	public static function register_widget() {
		register_widget( 'Xophz_Compass_3D_Block_Widget' );
	}
}

/**
 * WordPress Widget Class for 3D Block Canvas.
 */
class Xophz_Compass_3D_Block_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'xophz_compass_3d_block_widget',
			__( 'COMPASS 3D Animated Block', 'xophz-compass' ),
			array( 'description' => __( 'Interactive 3D building block assembly canvas inspired by Resend hero graphics.', 'xophz-compass' ) )
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$shortcode_atts = sprintf(
			'[compass_3d_block height="%s" theme="%s" grid_size="%s" accent_color="%s" interactive="%s"]',
			esc_attr( ! empty( $instance['height'] ) ? $instance['height'] : '400px' ),
			esc_attr( ! empty( $instance['theme'] ) ? $instance['theme'] : 'dark' ),
			esc_attr( ! empty( $instance['grid_size'] ) ? $instance['grid_size'] : '3' ),
			esc_attr( ! empty( $instance['accent_color'] ) ? $instance['accent_color'] : 'rgb(61, 238, 152)' ),
			esc_attr( ! empty( $instance['interactive'] ) ? $instance['interactive'] : 'true' )
		);

		echo do_shortcode( $shortcode_atts );
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title        = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$height       = ! empty( $instance['height'] ) ? $instance['height'] : '400px';
		$theme        = ! empty( $instance['theme'] ) ? $instance['theme'] : 'dark';
		$grid_size    = ! empty( $instance['grid_size'] ) ? $instance['grid_size'] : '3';
		$accent_color = ! empty( $instance['accent_color'] ) ? $instance['accent_color'] : 'rgb(61, 238, 152)';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php _e( 'Height:' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'theme' ) ); ?>"><?php _e( 'Theme:' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'theme' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'theme' ) ); ?>">
				<option value="dark" <?php selected( $theme, 'dark' ); ?>>Dark Metallic</option>
				<option value="neon" <?php selected( $theme, 'neon' ); ?>>Neon Cyan Glow</option>
				<option value="glass" <?php selected( $theme, 'glass' ); ?>>Glassmorphism</option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'accent_color' ) ); ?>"><?php _e( 'Accent Color:' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'accent_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'accent_color' ) ); ?>" type="text" value="<?php echo esc_attr( $accent_color ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance                 = array();
		$instance['title']        = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['height']       = ( ! empty( $new_instance['height'] ) ) ? sanitize_text_field( $new_instance['height'] ) : '400px';
		$instance['theme']        = ( ! empty( $new_instance['theme'] ) ) ? sanitize_text_field( $new_instance['theme'] ) : 'dark';
		$instance['grid_size']    = ( ! empty( $new_instance['grid_size'] ) ) ? sanitize_text_field( $new_instance['grid_size'] ) : '3';
		$instance['accent_color'] = ( ! empty( $new_instance['accent_color'] ) ) ? sanitize_text_field( $new_instance['accent_color'] ) : '#62c9ff';
		return $instance;
	}
}

// Initialize class
Xophz_Compass_3D_Block::init();
