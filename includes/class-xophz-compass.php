<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://mycompassconsulting.com
 * @since      0.0.0
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 * @author     Xoph <x@mycompassconsulting.com>
 */
class Xophz_Compass {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.0.0
	 * @access   protected
	 * @var      Xophz_Compass_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.0.0
	 */
	public function __construct() {
		if ( defined( 'XOPHZ_COMPASS_VERSION' ) ) {
			$this->version = XOPHZ_COMPASS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'xophz-compass';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Xophz_Compass_Loader. Orchestrates the hooks of the plugin.
	 * - Xophz_Compass_i18n. Defines internationalization functionality.
	 * - Xophz_Compass_Admin. Defines all hooks for the admin area.
	 * - Xophz_Compass_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xophz-compass-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xophz-compass-i18n.php';

		/**
		 * The class responsible for centralized branding configuration.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xophz-compass-branding.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-xophz-compass-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-xophz-compass-public.php';

		/**
		 * The class responsible for handling Constellations relationship mappings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xophz-compass-constellations-api.php';

		/**
		 * The class responsible for handling external Sparks from plugins.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xophz-compass-sparks-api.php';

		$this->loader = new Xophz_Compass_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Xophz_Compass_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Xophz_Compass_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Xophz_Compass_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 999999 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    $this->loader->add_filter( 'script_loader_tag', $plugin_admin, 'add_module_type', 10, 3 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu'); 
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'sort_xophz_submenu_alphabetically', 999 ); 
		$this->loader->add_action( 'wp_ajax_get_plugins', $plugin_admin, 'getPluginsByXoph'); 
		$this->loader->add_filter( 'extra_plugin_headers', $plugin_admin, 'add_category_header' );
		$this->loader->add_action( 'wp_ajax_activate_plugin', $plugin_admin, 'activate_plugin'); 
		$this->loader->add_action( 'wp_ajax_deactivate_plugin', $plugin_admin, 'deactivate_plugin'); 
    $this->loader->add_action( 'wp_ajax_get_current_user', $plugin_admin, 'getCurrentUser' );
		$this->loader->add_action( 'wp_ajax_deactivate_plugin', $plugin_admin, 'deactivate_plugin'); 
    $this->loader->add_action( 'wp_ajax_save_plugin_order', $plugin_admin, 'save_plugin_order' );
    $this->loader->add_action( 'wp_ajax_get_plugin_order', $plugin_admin, 'get_plugin_order' ); 
    $this->loader->add_action( 'wp_ajax_get_forminator_modules', $plugin_admin, 'get_forminator_modules' );

    $this->loader->add_filter( 'manage_posts_columns', $plugin_admin, 'posts_column_views');
    
    $this->loader->add_action( 'manage_posts_custom_column', $plugin_admin,'posts_custom_column_views',5,2);

    $this->loader->add_filter( 'manage_pages_columns', $plugin_admin, 'posts_column_views');

    $this->loader->add_action( 'manage_pages_custom_column', $plugin_admin,'posts_custom_column_views',5,2);

    $this->loader->add_action( 'admin_footer_text', $plugin_admin,'change_admin_footer',9999);
    $this->loader->add_action( 'update_footer', $plugin_admin,'change_footer',9999);

    // Register branding REST API endpoints
    $this->loader->add_action( 'rest_api_init', $plugin_admin, 'register_branding_endpoints' );

    // Register Constellations API & Cleanup Hooks
    $plugin_constellations = new Xophz_Compass_Constellations_API();
    $this->loader->add_action( 'rest_api_init', $plugin_constellations, 'register_routes' );
    $this->loader->add_action( 'before_delete_post', $plugin_constellations, 'cleanup_orphaned_post' );
    $this->loader->add_action( 'deleted_user', $plugin_constellations, 'cleanup_orphaned_user' );

    // Register Sparks API
    $plugin_sparks = new Xophz_Compass_Sparks_API();
    $this->loader->add_action( 'rest_api_init', $plugin_sparks, 'register_routes' );

    // Check DB Schema
    $this->loader->add_action( 'admin_init', $this, 'check_db_schema' );
	}

	/**
	 * Checks if the database schema is up to date, and if not, runs activation.
	 *
	 * @since    1.0.0
	 */
	public function check_db_schema() {
		if ( get_option( 'xophz_compass_db_version' ) !== $this->version ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xophz-compass-activator.php';
			Xophz_Compass_Activator::activate();
			update_option( 'xophz_compass_db_version', $this->version );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Xophz_Compass_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_head', $plugin_public, 'setPostViews' );

		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

    $this->loader->add_filter( 'rest_enabled', $plugin_public, 'enable_rest_api' );
    $this->loader->add_filter( 'rest_jsonp_enabled', $plugin_public, 'enable_rest_api' );

    // Register admin_color as a REST API field for users
    $this->loader->add_action( 'rest_api_init', $this, 'register_admin_color_rest_field' );
    
    // Register views field
    $this->loader->add_action( 'rest_api_init', $this, 'register_post_views_rest_field' );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Xophz_Compass_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register admin_color as a REST API field for users.
	 * This allows the color scheme picker to save via REST API.
	 *
	 * @since     1.0.0
	 */
	public function register_admin_color_rest_field() {
		register_rest_field('user', 'admin_color', [
			'get_callback' => function($user) {
				return get_user_option('admin_color', $user['id']);
			},
			'update_callback' => function($value, $user) {
				// Ensure admin color schemes are registered
				// (they may not be available in REST context by default)
				if (function_exists('wp_admin_css_color')) {
					// Trigger loading of color schemes if not already loaded
					global $_wp_admin_css_colors;
					if (empty($_wp_admin_css_colors)) {
						// Register the default schemes
						require_once(ABSPATH . 'wp-admin/includes/misc.php');
						if (function_exists('register_admin_color_schemes')) {
							register_admin_color_schemes();
						}
					}
				}
				
				// Validate the color scheme - use a simple allowlist if global not available
				global $_wp_admin_css_colors;
				$valid_schemes = !empty($_wp_admin_css_colors) 
					? array_keys($_wp_admin_css_colors)
					: ['fresh', 'light', 'modern', 'blue', 'coffee', 'ectoplasm', 'midnight', 'ocean', 'sunrise'];
				
				if (!in_array($value, $valid_schemes, true)) {
					return new WP_Error(
						'invalid_admin_color',
						'Invalid admin color scheme: ' . $value,
						['status' => 400]
					);
				}
				
				// Update the user option
				update_user_option($user->ID, 'admin_color', sanitize_text_field($value));
				return true;
			},
			'schema' => [
				'description' => 'Admin color scheme for the user.',
				'type' => 'string',
				'context' => ['view', 'edit'],
			]
		]);
	}

	/**
	 * Register post_views_count as a REST API field for posts.
	 *
	 * @since     1.0.0
	 */
	public function register_post_views_rest_field() {
		register_rest_field(['post'], 'post_views_count', [
			'get_callback' => function($post) {
				return (int) get_post_meta($post['id'], 'post_views_count', true);
			},
			'schema' => [
				'description' => 'Number of times the post has been viewed.',
				'type' => 'integer',
				'context' => ['view', 'edit'],
			]
		]);
	}

  public static function add_submenu($plugin, $args=[]){
      global $submenu;

      $register_submenu = function() use ($plugin, $args, &$submenu) {
          if ( ! function_exists( 'get_plugin_data' ) ) {
              require_once ABSPATH . 'wp-admin/includes/plugin.php';
          }
          
          $cap = (isset($args['cap']) && !empty($args['cap'])) 
            ? $args['cap'] : "manage_options";

          $plugin_file = WP_PLUGIN_DIR . "/{$plugin}/{$plugin}.php";
          if ( ! file_exists( $plugin_file ) ) return;

          // Disable markup and translation to prevent early textdomain loading (WP 6.7)
          $plugin_data = get_plugin_data( $plugin_file, false, false );
          
          if (!$plugin_data || empty($plugin_data['TextDomain'])) return;

          $compass = substr($plugin_data['TextDomain'], 0, 13);
          $page  = substr($plugin_data['TextDomain'], 14);

          // Route mapping for renamed plugins
          $route_map = [
              'quests' => 'questbook',
              'post-digger' => 'newsroom'
          ];
          $route_slug = isset($route_map[$page]) ? $route_map[$page] : $page;

          // Use branding helper for customizable plugin name
          $default_name = trim(str_replace('Xophz', '', $plugin_data['Name']));
          $plugin_name = Xophz_Compass_Branding::get_plugin_name($page, $default_name);

          $submenu[ $compass ][] = [
              __( $plugin_name, $compass ),
              $cap,
              "admin.php?page={$compass}#/{$route_slug}"
          ];
      };

      if (did_action('admin_menu')) {
          $register_submenu();
      } else {
          add_action('admin_menu', $register_submenu, 11);
      }
  }

	/**
	 * Output data as json .
	 *
	 * @since     1.0.0
	 */
  public static function output_json($json){
    wp_send_json($json);
  }

	/**
	 * Retrieve the json contents from the input request.
	 *
	 * @since     1.0.0
	 * @return    string    HTTP Method.
	 */
  public static function get_input_json(){
    $input = file_get_contents('php://input');
    $json = json_decode($input);
    if ($json !== null) {
      return $json;
    }

    // Fallback for form-encoded PUT/POST requests
    if (!empty($input)) {
        parse_str($input, $parsed_args);
        if (!empty($parsed_args)) {
            return (object) array_merge(wp_unslash($_REQUEST), wp_unslash($parsed_args));
        }
    }

    return (object) wp_unslash($_REQUEST);
  }

	/**
	 * Retrieve the current HTTP method.
	 *
	 * @since     1.0.0
	 * @return    string    HTTP Method.
	 */
  public static function get_http_method(){
    // Retrieve HTTP method
    return filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
  }

  public static function update_post_meta($id, $key, $payload){
    if(is_array($key)){
      foreach ($key  as $k) {
        Xophz_Compass::update_post_meta(
          $id, $k, $payload
        );
      }
      return;
    }

    if( isset($payload[$key]) ){
      $value = $payload[$key];
      update_post_meta(
        $id,
        $key,
        $value 
      );
    }
  }

  /**
   * undocumented function
   *
   * @return void
   */
  public static function output_error($msg, $status){
    http_response_code($status);
    Xophz_Compass::output_json([
      'error' => [
        'status' => $status,
        'msg'   => $msg
      ] 
    ]);
  }
}
