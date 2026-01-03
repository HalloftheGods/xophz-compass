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
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-xophz-compass-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-xophz-compass-public.php';

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
		$this->loader->add_action( 'wp_ajax_get_plugins', $plugin_admin, 'getPluginsByXoph'); 
		$this->loader->add_action( 'wp_ajax_activate_plugin', $plugin_admin, 'activate_plugin'); 
		$this->loader->add_action( 'wp_ajax_deactivate_plugin', $plugin_admin, 'deactivate_plugin'); 
    $this->loader->add_action( 'wp_ajax_get_current_user', $plugin_admin, 'getCurrentUser' );
		$this->loader->add_action( 'wp_ajax_deactivate_plugin', $plugin_admin, 'deactivate_plugin'); 

    $this->loader->add_filter( 'manage_posts_columns', $plugin_admin, 'posts_column_views');
    
    $this->loader->add_action( 'manage_posts_custom_column', $plugin_admin,'posts_custom_column_views',5,2);

    $this->loader->add_filter( 'manage_pages_columns', $plugin_admin, 'posts_column_views');

    $this->loader->add_action( 'manage_pages_custom_column', $plugin_admin,'posts_custom_column_views',5,2);

    $this->loader->add_action( 'admin_footer_text', $plugin_admin,'change_admin_footer',9999);
    $this->loader->add_action( 'update_footer', $plugin_admin,'change_footer',9999);
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

  public static function add_submenu($plugin, $args=[]){
      global $submenu;

      $cap = (isset($args['cap']) && !empty($args['cap'])) 
        ? $args['cap'] : "manage_options";


      $plugin = get_plugins()["{$plugin}/{$plugin}.php"];
      $compass = substr($plugin['TextDomain'], 0, 13);
      $page  = substr($plugin['TextDomain'], 14);

      $submenu[ $compass ][] = [
          __( str_replace("Xophz ","", $plugin['Name']), $compass ),
          $cap,
          "admin.php?page={$compass}#/{$page}"
      ];
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
    return json_decode(file_get_contents('php://input'));
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
    }

    $value = $payload[$key];
    if( $value  ){
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
