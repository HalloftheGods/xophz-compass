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
    wp_enqueue_style( 'button-color', plugins_url( 'css/color-my-icon.css', __FILE__ ) );

    //
    // wp_enqueue_style(
    //   $this->plugin_name.'admin-css',
    //   plugins_url( 'css/xophz-compass-admin.css', __FILE__ ),
    //   array(),
    //   $this->version,
    //   'all'
    // );

    // TODO Move this to Vue Project
    if( false !== strpos($_GET['page'],$this->plugin_name)  ){
      wp_enqueue_style(
        'font-awesome-pro',
        plugins_url( 'fonts/fontawesome-pro-5.10.0-web/css/all.css', __FILE__ ),
        array(),
        $this->version,
        'all'
      );

      wp_enqueue_style(
          'google-fonts',
          '//fonts.googleapis.com/css?family=Roboto:400,500,700,400italic|Material+Icons',
          array(),
          $this->version
      );

      if ( $this->isDevServer() ) {
        wp_enqueue_style( $this->plugin_name . '_style', "http://{$_SERVER['REMOTE_ADDR']}:8080/css/chunk-vendors.css", [], $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name . '_dev', "http://{$_SERVER['REMOTE_ADDR']}:8080/css/index.css", [], $this->version, 'all' );
      } else {
        wp_enqueue_style( $this->plugin_name . '_vendors', plugin_dir_url( __FILE__ ) . 'dist/css/chunk-vendors.css', [], $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name . '_style', plugin_dir_url( __FILE__ ) . 'dist/css/index.css', [], $this->version, 'all' );
      }

    wp_enqueue_style(
      $this->plugin_name.'admin-css',
      plugins_url( 'css/xophz-compass-admin.css', __FILE__ ),
      array(),
      $this->version,
      'all',
     99999 
      
    );

      // wp_enqueue_style(
      //   $this->plugin_name.'admin-vendors-css',
      //   plugin_dir_url( __FILE__ ) . 'dist/css/chunk-vendors.css', 
      //   array(),
      //   $this->version,
      //   'all'
      // );
      //
      // wp_enqueue_style(
      //   $this->plugin_name.'admin-css',
      //   plugin_dir_url( __FILE__ ) . 'dist/css/index.css', 
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
      // MAIN JS FILE
      // wp_enqueue_script( $this->plugin_name.'-vendors',
      //     plugin_dir_url( __FILE__ ) . 'dist/js/chunk-vendors.js', 
      //     array(  ), 
      //     $this->version, 
      //     true 
      // );
      if ( $this->isDevServer() ) {
        wp_enqueue_script( $this->plugin_name.'_dev',
          "http://{$_SERVER['REMOTE_ADDR']}:8080/js/index.js", 
          [], 
          $this->version, 
          false 
        );
      } else {
        wp_enqueue_script( $this->plugin_name.'-chunks',
          plugin_dir_url( __FILE__ ) . 'dist/js/chunk-vendors.js', 
          [], 
          $this->version, 
          false 
        );
        wp_enqueue_script( $this->plugin_name.'-main-app',
          plugin_dir_url( __FILE__ ) . 'dist/js/index.js', 
          [], 
          $this->version, 
          false 
        );
      }

      // wp_enqueue_script( $this->plugin_name.'-admin-js',
      //     plugin_dir_url( __FILE__ ) . 'js/xophz-compass-admin.js', 
      //     array( 'jquery' ), 
      //     $this->version, 
      //     false 
      // );
    }
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

      $plugins[$p]['is_activated'] = is_plugin_active($p);
      $plugins[$p]['is_installed'] = true;
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


    Xophz_Compass::output_json([
      'current_user' => [
        'avatar' => get_avatar_url($user->ID,250),
        'caps'   => $user->caps,
        'data'   => $user->data,
        'roles'   => $user->roles,
      ] 
    ]);
  }

  private function isDevServer()
  {
    $connection = @fsockopen($_SERVER['REMOTE_ADDR'], '8080');

    if ( $connection ) {
        return true;
    }

    return false;
  }
}
