<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://mycompassconsulting.com
 * @since      0.0.0
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/admin
 * @author     Your Name <email@example.com>
 */
class Xophz_Compass_Admin {
  /**
   * The ID of this plugin.
   *
   * @since    0.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $xophz_compass;

  /**
   * The version of this plugin.
   *
   * @since    0.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    0.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
    
  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    0.0.0
   */
  public function enqueue_styles() {
    global $wp_styles;

    wp_enqueue_style( 'button-color', plugins_url( 'css/color-my-icon.css', __FILE__ ) );

    if( false !== strpos($_GET['page'],$this->plugin_name)  ){
      // e.g. these styles will keep the admin bar styled
      $styles_to_keep = array("wp-admin", "admin-bar", "dashicons", "open-sans", "admin-menu",  "query-monitor", "button-color");

      $styles = wp_styles()->registered;
      foreach ($styles as $handle => $value) {
          // if we want to keep it, skip it
          if ( in_array($handle, $styles_to_keep) ) continue;

          // otherwise remove it
          // wp_deregister_style($handle);
          // if($handle != 'admin-menu')
            wp_dequeue_style($handle);
      }

      if ( $this->isDevServer() ) {
        // Vite injects CSS via JavaScript in dev mode, so no separate CSS files needed
      } else {
        // Production: Load bundled CSS
        wp_enqueue_style( $this->plugin_name . '_style', plugin_dir_url( __FILE__ ) . 'dist/css/index.css', [], $this->version, 'all' );
      }

      foreach ($styles_to_keep as $style) {
        wp_enqueue_style( $style );
      }
      // wp_enqueue_style(
      //   $this->plugin_name.'admin-css',
      //   plugins_url( 'css/xophz-compass-admin.css', __FILE__ ),
      //   array(),
      //   $this->version,
      //   'all'
      // );
    }

  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    0.0.0
   */
  public function enqueue_scripts() {
    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Xophz_Compass_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Xophz_Compass_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    if( false !== strpos($_GET['page'],$this->plugin_name)  ){
      if ( $this->isDevServer() ) {
        // Vite dev server uses ES modules - we need to add the script tag manually
        // because wp_enqueue_script doesn't support type="module"
        add_action('admin_head', function() {
          $devServerUrl = "http://localhost:8080";
          echo '<script type="module" src="' . $devServerUrl . '/@vite/client"></script>';
          echo '<script type="module" src="' . $devServerUrl . '/src/app.ts"></script>';
        });
      } else {
        // Production: Load bundled assets
        wp_enqueue_script( $this->plugin_name.'-main-app',
          plugin_dir_url( __FILE__ ) . 'dist/js/index.js', 
          [], 
          $this->version, 
          false 
        );
      }
    }
  }

  /**
   * Filter to add type="module" to our enqueued script
   */
  public function add_module_type($tag, $handle, $src) {
    if ( $handle === $this->plugin_name . '-main-app' ) {
      return '<script type="module" src="' . esc_url($src) . '"></script>';
    }
    return $tag;
  }

  /**
   * Menu item.
   *
   * @since    0.0.0
   */
  public function add_menu(){ 
    global $submenu;

    $capability = 'read';
    $slug       = 'xophz-compass';

    $hook = add_menu_page( 
        __( 'Xophz Compass', 'xophz-compass' ), 
        __( 'Xophz Compass', 'xophz-compass' ), 
        $capability,
        $slug, 
        [ $this, 'admin_area' ],
        'dashicons-editor-customchar',
        0 
    );

    array_unshift($submenu[ $slug ] ,[
        __( 'Compass', 'xophz-compass' ),
        'manage_options',
        'admin.php?page=' . $slug . '#/', 
    ]);
    if(!empty($submenu[$slug])){
    }
  }

  public function activate_plugin(){
    $plugin = $_REQUEST['plugin'];
    $result = activate_plugins( "$plugin/$plugin.php" );
    if ( is_wp_error( $result ) ) {
        // Process Error
    }
    $this->output_json($result);
  }

  public function deactivate_plugin(){
    $plugin = $_REQUEST['plugin'];
    $result = deactivate_plugins( "$plugin/$plugin.php" );
    if ( is_wp_error( $result ) ) {
        // Process Error
    }
    $this->output_json($result);
  }

  public function getPluginsByXoph(){
    $plugins = get_plugins();

    foreach($plugins as $p => $plugin){
      if(false === strpos($plugin['TextDomain'],'xophz-compass')){
        // LETS REMOVE EVERYTHING BUT XOPHZ 
        unset($plugins[$p]);
        continue;
      }
      $plugin_dir = str_replace($_SERVER["DOCUMENT_ROOT"],"", plugins_url($plugin['TextDomain']));

      $plugins[$p]['isActivated'] = is_plugin_active($p);
      $plugins[$p]['isInstalled'] = true;
      $plugins[$p]['Name'] = trim(str_replace('Xophz','', $plugin['Name'])) ;
      $plugins[$p]['icon'] = "{$plugin_dir}/icon.svg";
    }

    $this->output_json($plugins);
  }

  public function output_json($json){
      echo json_encode($json);
      wp_die();
  }

  public function admin_area(){
    // require('dist/index.html');
    require('partials/xophz-compass-admin-display.php');
  }

  public function posts_column_views($defaults){
    $defaults['post_views'] = __('Views');
    return $defaults;
  }

  public function posts_custom_column_views($column_name, $id){
      if($column_name === 'post_views'){
          echo Xophz_Compass_Admin::getPostViews(get_the_ID());
      }
  }

  public function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return 0;
    }
    return $count;
  }

  public function change_admin_footer(){
    // echo '<span id="footer-note">From your friends at <a href="http://www.mycompassconsulting.com/" target="_blank">My Compass Consulting</a>.</span>';
    // echo ucwords(str_replace('-',' ',$this->plugin_name)) ." ". $this->version;
    echo "";
  }
  public function change_footer(){
    // echo '<span id="footer-note">From your friends at <a href="http://www.mycompassconsulting.com/" target="_blank">My Compass Consulting</a>.</span>';
    $plugin = ucwords(str_replace('-',' ',$this->plugin_name));
    $version = $this->version;
    $year = date("Y");
    $copy = "&copy;";
    $footer = "{$plugin} {$version} {$copy} {$year}"; 
    echo $footer;
 
  }

  public function getCurrentUser(){
    $user = wp_get_current_user();

    $currentUser = [
      'avatar' => get_avatar_url($user->ID,250),
      'caps'   => $user->caps,
      'data'   => $user->data,
      'roles'   => $user->roles,
    ];

    $blogInfo = [
      'name' => get_bloginfo('name'),
      'description' => get_bloginfo('description'),
      'url' => get_bloginfo('url'),
      'wpurl' => get_bloginfo('wpurl'),
      'version' => get_bloginfo('version'),
      'logouturl' => htmlspecialchars_decode(wp_logout_url())
    ];

    Xophz_Compass::output_json([
      'current_user' => $currentUser,
      'blog_info' => $blogInfo,
    ]);
  }

  private function isDevServer()
  {
    return in_array(DB_HOST, ['mysql:3306']); 
  }
}
