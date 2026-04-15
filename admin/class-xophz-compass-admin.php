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
  private $plugin_name;

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

    $css_file = plugin_dir_path( __FILE__ ) . 'css/compass-admin-global.css';
    $version  = file_exists($css_file) ? filemtime($css_file) : $this->version;
    wp_enqueue_style( 'compass-admin-global', plugins_url( 'css/compass-admin-global.css', __FILE__ ), array(), $version );

    if( isset($_GET['page']) && false !== strpos($_GET['page'], $this->plugin_name) ){
      // TARGETED STYLE DEQUEUING
      // Keep WordPress admin chrome functional, but prevent form styling conflicts
      $styles_to_keep = array(
        "dashicons",     // Required: Icons throughout WordPress admin
        "admin-bar",     // Required: Top WordPress admin bar
        "admin-menu",    // Required: Left sidebar menu styling
        "common",        // Required: WordPress admin base styles
        "colors",        // Required: WordPress color schemes  
        "open-sans",     // WordPress admin font
        "compass-admin-global",  // Custom: COMPASS admin global styles (icon color, scrollbars)
        "query-monitor"  // Dev tool: Query Monitor plugin styles
      );

      $styles = wp_styles()->registered;
      foreach ($styles as $handle => $value) {
          // Keep explicitly allowed styles and color scheme variations
          if ( in_array($handle, $styles_to_keep) || strpos($handle, 'colors') === 0 ) continue;

          // Dequeue everything else
          wp_dequeue_style($handle);
      }

      // Note: We don't deregister WordPress stylesheets anymore
      // Our consolidated _wp-form-reset.scss handles overriding WordPress form styles
      // This prevents dependency warnings and maintains WordPress admin functionality

      if ( $this->isDevServer() ) {
        // Vite injects CSS via JavaScript in dev mode, so no separate CSS files needed
      } else {
        // Production: Load bundled CSS from manifest
        $manifest_path = plugin_dir_path( __FILE__ ) . 'dist/.vite/manifest.json';
        $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : null;
        
        if (isset($manifest['index.html']['css'])) {
          foreach ($manifest['index.html']['css'] as $css_file) {
            wp_enqueue_style( $this->plugin_name . '-' . md5($css_file),
              plugin_dir_url( __FILE__ ) . 'dist/' . $css_file,
              [],
              $this->version,
              'all'
            );
          }
        } else {
          // Fallback if manifest is missing or entry not found
          wp_enqueue_style( $this->plugin_name . '_style', plugin_dir_url( __FILE__ ) . 'dist/css/index.css', [], $this->version, 'all' );
        }
      }

      // Re-enqueue kept styles to ensure they load after deregistration
      foreach ($styles_to_keep as $style) {
        wp_enqueue_style( $style );
      }
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

    if( isset($_GET['page']) && false !== strpos($_GET['page'], $this->plugin_name) ){
      wp_enqueue_script( 'wp-api' );
      
      // Prepare data for injection
      global $_wp_admin_css_colors;
      $current_user = wp_get_current_user();
      
      $settings = [
          'adminColors' => $_wp_admin_css_colors,
          'currentUser' => [
              'ID' => $current_user->ID,
              'admin_color' => get_user_option('admin_color', $current_user->ID),
              'roles' => $current_user->roles,
          ],
          'nonce' => wp_create_nonce( 'wp_rest' ),
          'restUrl' => get_rest_url(),
          'siteName' => get_bloginfo('name')
      ];

      if ( $this->isDevServer() ) {
        // Vite dev server uses ES modules
        add_action('admin_head', function() use ($settings) {
          $host = isset($_SERVER['HTTP_HOST']) ? explode(':', $_SERVER['HTTP_HOST'])[0] : 'localhost';
          $devServerUrl = "http://" . $host . ":8080";
          
          // Inject settings as a global variable
          echo '<script>window.xophzCompassSettings = ' . json_encode($settings) . ';</script>';
          
          echo '<script type="module" src="' . $devServerUrl . '/@vite/client"></script>';
          echo '<script type="module" src="' . $devServerUrl . '/src/mount-app.ts"></script>';
          $this->output_theme_colors();
        });
      } else {
        // Production: Load bundled assets from manifest
        $manifest_path = plugin_dir_path( __FILE__ ) . 'dist/.vite/manifest.json';
        $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : null;
        
        $entry_js = isset($manifest['index.html']) ? 'dist/' . $manifest['index.html']['file'] : 'dist/js/index.js';
        
        wp_enqueue_script( $this->plugin_name.'-main-app',
          plugin_dir_url( __FILE__ ) . $entry_js, 
          [], 
          $this->version, 
          false 
        );
        
        // Inject settings into the main app script
        wp_localize_script( $this->plugin_name.'-main-app', 'xophzCompassSettings', $settings );
        
        add_action('admin_head', [$this, 'output_theme_colors']);
      }
    }
  }

  /**
   * Outputs the current admin theme colors as CSS variables
   */
  public function output_theme_colors() {
    global $_wp_admin_css_colors;
    $color_scheme = get_user_option('admin_color');
    
    // Default palette (Fresh)
    $colors = ['#1d2327', '#2c3338', '#2271b1', '#72aee6']; 
    
    if (isset($_wp_admin_css_colors[$color_scheme])) {
      $colors = $_wp_admin_css_colors[$color_scheme]->colors;
    }

    $c0 = $colors[0] ?? '#1d2327';
    $c1 = $colors[1] ?? '#2c3338';
    $c2 = $colors[2] ?? '#2271b1';
    $c3 = $colors[3] ?? '#72aee6';

    // Handle theme-specific active color mapping
    // Light uses the 2nd color (Index 1) for active menu
    $active = $c2;
    if ($color_scheme === 'light') {
        $active = $c1;
    }
    
    echo "<style>
      :root { 
        --wp-theme-base: {$c0}; 
        --wp-theme-focus: {$c1}; 
        --wp-theme-color: {$c2}; 
        --wp-theme-secondary: {$c3}; 
        --wp-theme-active: {$active};
      }
      body.toplevel_page_xophz-compass {
        --wp-active-scheme: {$color_scheme};
      }
    </style>";
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
   * Add My Compass button to the admin bar with the Gold Omega icon.
   * Uses the same dashicon + color as the sidebar menu item.
   *
   * @param WP_Admin_Bar $wp_admin_bar The admin bar instance.
   */
  public function add_compass_admin_bar_button( $wp_admin_bar ) {
    $omega_html = '<span class="ab-icon dashicons dashicons-editor-customchar compass-ab-omega"></span>';

    $menu_title = class_exists( 'Xophz_Compass_Branding' )
      ? Xophz_Compass_Branding::get_menu_title()
      : 'My Compass';

    $wp_admin_bar->add_node( array(
      'id'    => 'compass-menu',
      'title' => $omega_html . '<span class="compass-ab-label">' . esc_html( $menu_title ) . '</span>',
      'href'  => admin_url( 'admin.php?page=xophz-compass' ),
      'meta'  => array(
        'class' => 'compass-admin-bar-btn',
        'title' => $menu_title,
      ),
    ) );

    // ── Group 1: Dashboard links ──
    $wp_admin_bar->add_node( array(
      'parent' => 'compass-menu',
      'id'     => 'compass-dashboard',
      'title'  => 'Dashboard',
      'href'   => admin_url( 'admin.php?page=xophz-compass#/' ),
    ) );

    $wp_admin_bar->add_node( array(
      'parent' => 'compass-menu',
      'id'     => 'compass-settings',
      'title'  => 'Settings',
      'href'   => admin_url( 'admin.php?page=xophz-compass#/settings' ),
    ) );

    // ── Group 2: External links ──
    $wp_admin_bar->add_group( array(
      'parent' => 'compass-menu',
      'id'     => 'compass-external',
      'meta'   => array( 'class' => 'ab-sub-secondary' ),
    ) );

    $wp_admin_bar->add_node( array(
      'parent' => 'compass-external',
      'id'     => 'compass-website',
      'title'  => 'MyCompassConsulting.com',
      'href'   => 'https://mycompassconsulting.com',
      'meta'   => array( 'target' => '_blank' ),
    ) );

    $wp_admin_bar->add_node( array(
      'parent' => 'compass-external',
      'id'     => 'compass-docs',
      'title'  => 'Documentation',
      'href'   => 'https://mycompassconsulting.com/docs',
      'meta'   => array( 'target' => '_blank' ),
    ) );

    $wp_admin_bar->add_node( array(
      'parent' => 'compass-external',
      'id'     => 'compass-support',
      'title'  => 'Support',
      'href'   => 'https://mycompassconsulting.com/support',
      'meta'   => array( 'target' => '_blank' ),
    ) );
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
    
    // Use branding helper for customizable menu title and icon
    $menu_title = Xophz_Compass_Branding::get_menu_title();
    $menu_icon  = Xophz_Compass_Branding::get_menu_icon();

    $hook = add_menu_page( 
        __( $menu_title, 'xophz-compass' ), 
        __( $menu_title, 'xophz-compass' ), 
        $capability,
        $slug, 
        [ $this, 'admin_area' ],
        $menu_icon,
        0 
    );

    if ( ! isset( $submenu[ $slug ] ) ) {
        $submenu[ $slug ] = array();
    }

    // Use platform name for first menu item
    $platform_name = Xophz_Compass_Branding::get('platform_name', 'Compass');
    array_unshift($submenu[ $slug ] ,[
        __( $platform_name, 'xophz-compass' ),
        'manage_options',
        'admin.php?page=' . $slug . '#/', 
    ]);
  }

  /**
   * Sort the Xophz Compass submenu items alphabetically.
   *
   * @since    1.0.0
   */
  public function sort_xophz_submenu_alphabetically() {
      global $submenu;
      $parent_slug = 'xophz-compass';

      if ( isset( $submenu[ $parent_slug ] ) ) {
          // Preserve the first item (Compass/Dashboard)
          $first_item = array_shift( $submenu[ $parent_slug ] );
          
          usort( $submenu[ $parent_slug ], function( $a, $b ) {
              return strcmp( $a[0], $b[0] );
          } );

          // Add the first item back to the beginning
          array_unshift( $submenu[ $parent_slug ], $first_item );
      }
  }

  public function activate_plugin(){
    if ( ! isset( $_REQUEST['plugin'] ) ) {
        $this->output_json( array( 'error' => 'Missing plugin parameter' ) );
        return;
    }
    $plugin = $_REQUEST['plugin'];
    $result = activate_plugins( "$plugin/$plugin.php" );
    if ( is_wp_error( $result ) ) {
        // Process Error
    }
    $this->output_json($result);
  }

  public function deactivate_plugin(){
    if ( ! isset( $_REQUEST['plugin'] ) ) {
        $this->output_json( array( 'error' => 'Missing plugin parameter' ) );
        return;
    }
    $plugin = $_REQUEST['plugin'];
    $result = deactivate_plugins( "$plugin/$plugin.php" );
    if ( is_wp_error( $result ) ) {
        // Process Error
    }
    $this->output_json($result);
  }

  public function getPluginsByXoph(){
    $plugins = get_plugins();
    
    // Inject Magic Formula since it's now bundled natively in compass but needs to appear as a plugin in UI
    $plugins['xophz-compass-magic-formula/xophz-compass-magic-formula.php'] = [
      'Name' => 'Xophz Magic Formula',
      'PluginURI' => 'http://www.mycompassconsulting.com/',
      'Version' => '1.0.0',
      'Description' => 'The ultimate form, poll, and quiz builder.',
      'Author' => 'Xoph',
      'TextDomain' => 'xophz-compass-magic-formula',
      'DomainPath' => '/languages',
      'Network' => false,
      'Title' => 'Xophz Magic Formula',
      'AuthorName' => 'Xoph',
      'Category' => 'Trajectory'
    ];

    $vendor_prefix = Xophz_Compass_Branding::get_vendor_prefix();

    foreach($plugins as $p => $plugin){
      if(false === strpos($plugin['TextDomain'],'xophz-compass')){
        // LETS REMOVE EVERYTHING BUT XOPHZ 
        unset($plugins[$p]);
        continue;
      }
      $plugin_dir = str_replace($_SERVER["DOCUMENT_ROOT"],"", plugins_url($plugin['TextDomain']));

      // Extract slug from text domain (e.g., 'xophz-compass-bomb-bag' -> 'bomb-bag')
      $slug = str_replace('xophz-compass-', '', $plugin['TextDomain']);
      if ($slug === 'xophz-compass') {
        $slug = 'compass'; // Main plugin
      }

      $active_plugins = (array) get_option( 'active_plugins', array() );
      if ( is_multisite() ) {
        $active_plugins = array_merge( $active_plugins, array_keys( get_site_option( 'active_sitewide_plugins', array() ) ) );
      }
      $plugins[$p]['isActivated'] = ( $slug === 'magic-formula' ) || in_array( $p, $active_plugins );
      $plugins[$p]['isInstalled'] = true;
      
      // Use branding helper for customizable plugin names
      $default_name = trim(str_replace('Xophz', '', $plugin['Name']));
      $plugins[$p]['Name'] = Xophz_Compass_Branding::get_plugin_name($slug, $default_name);
      $plugins[$p]['Description'] = Xophz_Compass_Branding::get_plugin_description($slug, $plugin['Description']);
      
      if ($slug === 'magic-formula') {
        $icon_version = time();
        $plugins[$p]['icon'] = plugins_url('xophz-compass/assets/magic-formula.svg') . "?v={$icon_version}";
      } else {
        $icon_path = WP_PLUGIN_DIR . '/' . $plugin['TextDomain'] . '/icon.svg';
        $icon_version = file_exists($icon_path) ? filemtime($icon_path) : time();
        $plugins[$p]['icon'] = "{$plugin_dir}/icon.svg?v={$icon_version}";
      }
      
      // Fallback approach if WP's native plugins caching hides Category
      if (empty($plugin['Category'])) {
        $plugin_file = WP_PLUGIN_DIR . '/' . $p;
        if (file_exists($plugin_file)) {
          $plugin_data = get_file_data($plugin_file, ['Category' => 'Category']);
          if (!empty($plugin_data['Category'])) {
            $plugin['Category'] = $plugin_data['Category'];
          }
        }
      }
      
      // Ensure category has a fallback
      $plugins[$p]['Category'] = !empty($plugin['Category']) ? trim($plugin['Category']) : 'Uncategorized';
    }

    $this->output_json($plugins);
  }

  public function output_json($json){
      echo json_encode($json);
      wp_die();
  }

  /**
   * Adds the 'Category' header to the list of plugin headers parsed by WordPress.
   */
  public function add_category_header($headers) {
      $headers['Category'] = 'Category';
      return $headers;
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
    $plugin = 'My Compass';
    $version_string = 'v' . $this->version;
    $year = date("Y");
    $copy = "&copy;";
    $footer = "{$plugin} {$version_string} {$year} {$copy} Hall of the Gods, Inc."; 
    echo $footer;
  }

  public function getCurrentUser(){
    $user = wp_get_current_user();
    $user_id = $user->ID;

    // Fetch license info from user meta (likely set by w4.youmeos.com checkout)
    $tier = get_user_meta($user_id, '_youmeos_tier', true) ?: 'unverified';
    $license_key = get_user_meta($user_id, '_youmeos_license_key', true) ?: '';
    $status = get_user_meta($user_id, '_youmeos_license_status', true) ?: (empty($license_key) ? 'none' : 'active');

    $currentUser = [
      'avatar' => get_avatar_url($user_id, 250),
      'caps'   => $user->caps,
      'data'   => $user->data,
      'roles'   => $user->roles,
      'license' => [
        'key' => $license_key,
        'tier' => $tier,
        'status' => $status
      ]
    ];

    $blogInfo = [
      'name' => get_bloginfo('name'),
      'description' => get_bloginfo('description'),
      'url' => get_bloginfo('url'),
      'wpurl' => get_bloginfo('wpurl'),
      'version' => get_bloginfo('version'),
      'logouturl' => htmlspecialchars_decode(wp_logout_url()),
      'front_page_title' => get_option('show_on_front') === 'page' ? get_the_title(get_option('page_on_front')) : 'Latest Posts'
    ];

    Xophz_Compass::output_json([
      'current_user' => $currentUser,
      'blog_info' => $blogInfo,
    ]);
  }

  /**
   * Save the plugin grid order for the current user.
   *
   * @since    1.0.0
   */
  public function save_plugin_order() {
    $user_id = get_current_user_id();
    if (!$user_id) {
      $this->output_json(['error' => 'Not authenticated']);
      return;
    }

    $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : [];
    if (is_string($order)) {
      $order = json_decode(stripslashes($order), true);
    }

    if (!is_array($order)) {
      $this->output_json(['error' => 'Invalid order format']);
      return;
    }

    // Sanitize the order array - only allow valid text domain strings
    $order = array_map('sanitize_text_field', $order);

    update_user_meta($user_id, '_compass_plugin_order', $order);
    $this->output_json(['success' => true]);
  }

  /**
   * Get the saved plugin grid order for the current user.
   *
   * @since    1.0.0
   */
  public function get_plugin_order() {
    $user_id = get_current_user_id();
    if (!$user_id) {
      $this->output_json([]);
      return;
    }

    $order = get_user_meta($user_id, '_compass_plugin_order', true);
    $this->output_json($order ?: []);
  }

  private function isDevServer()
  {
    $hotFilePath = plugin_dir_path( __FILE__ ) . 'hot';
    return file_exists( $hotFilePath );
  }
  /**
   * Register REST API endpoints for branding configuration.
   *
   * @since    1.0.0
   */
  public function register_branding_endpoints() {
    register_rest_route('xophz-compass/v1', '/branding', [
      [
        'methods'  => 'GET',
        'callback' => [$this, 'get_branding'],
        'permission_callback' => function() {
          return current_user_can('manage_options');
        }
      ],
      [
        'methods'  => 'PUT',
        'callback' => [$this, 'update_branding'],
        'permission_callback' => function() {
          // Only Wizards can update branding
          return current_user_can('manage_options') && Xophz_Compass_Branding::is_wizard();
        }
      ],
      [
        'methods'  => 'DELETE',
        'callback' => [$this, 'delete_branding'],
        'permission_callback' => function() {
          // Only Wizards can delete/reset branding
          return current_user_can('manage_options') && Xophz_Compass_Branding::is_wizard();
        }
      ]
    ]);

    register_rest_route('xophz-compass/v1', '/wizard-status', [
      'methods'  => 'GET',
      'callback' => [$this, 'get_wizard_status'],
      'permission_callback' => function() {
        return current_user_can('manage_options');
      }
    ]);

    register_rest_route('xophz-compass/v1', '/plugins', [
      'methods'  => 'GET',
      'callback' => [$this, 'get_available_plugins'],
      'permission_callback' => function() {
        return current_user_can('manage_options');
      }
    ]);

    register_rest_route('xophz-compass/v1', '/menus', [
      'methods'  => 'GET',
      'callback' => [$this, 'get_wp_menus'],
      'permission_callback' => function() {
        return current_user_can('manage_options');
      }
    ]);
  }

  /**
   * Get branding configuration via REST API.
   *
   * @since    1.0.0
   * @return   WP_REST_Response
   */
  public function get_branding() {
    return new WP_REST_Response([
      'config'   => Xophz_Compass_Branding::get_config(),
      'defaults' => Xophz_Compass_Branding::get_defaults(),
      'isWizard' => Xophz_Compass_Branding::is_wizard()
    ], 200);
  }

  /**
   * Update branding configuration via REST API.
   *
   * @since    1.0.0
   * @param    WP_REST_Request    $request    The REST request.
   * @return   WP_REST_Response
   */
  public function update_branding(WP_REST_Request $request) {
    $config = $request->get_json_params();
    
    if (empty($config)) {
      return new WP_REST_Response(['error' => 'No configuration provided'], 400);
    }

    $success = Xophz_Compass_Branding::update_config($config);

    if ($success) {
      return new WP_REST_Response([
        'success' => true,
        'config'  => Xophz_Compass_Branding::get_config()
      ], 200);
    }

    return new WP_REST_Response(['error' => 'Failed to update configuration'], 500);
  }

  /**
   * Delete branding configuration via REST API, reverting to defaults.
   *
   * @since    1.0.0
   * @return   WP_REST_Response
   */
  public function delete_branding() {
    $success = Xophz_Compass_Branding::delete_config();

    if ($success || !get_option(Xophz_Compass_Branding::OPTION_KEY)) {
      return new WP_REST_Response([
        'success' => true,
        'config'  => Xophz_Compass_Branding::get_config() // Returns defaults
      ], 200);
    }

    return new WP_REST_Response(['error' => 'Failed to reset configuration'], 500);
  }

  /**
   * Get Wizard status via REST API.
   *
   * @since    1.0.0
   * @return   WP_REST_Response
   */
  public function get_wizard_status() {
    return new WP_REST_Response([
      'isWizard' => Xophz_Compass_Branding::is_wizard()
    ], 200);
  }

  /**
   * Get list of all available COMPASS plugins for branding.
   *
   * @since    1.0.0
   * @return   WP_REST_Response
   */
  public function get_available_plugins() {
    $plugins = get_plugins();
    $available = [];

    foreach($plugins as $p => $plugin){
      if(false === strpos($plugin['TextDomain'],'xophz-compass')){
        continue;
      }
      
      $slug = str_replace('xophz-compass-', '', $plugin['TextDomain']);
      if ($slug === 'xophz-compass') {
        $slug = 'compass';
      }

      $route_map = [
          'quests' => 'questbook',
          'post-digger' => 'newsroom'
      ];
      $slug = isset($route_map[$slug]) ? $route_map[$slug] : $slug;

      $available[] = [
        'slug' => $slug,
        'defaultName' => trim(str_replace('Xophz', '', $plugin['Name']))
      ];
    }

    return new WP_REST_Response($available, 200);
  }

  /**
   * Get all WordPress menus and their nested structure.
   *
   * @since    1.0.0
   * @return   WP_REST_Response
   */
  public function get_wp_menus() {
    $menus = wp_get_nav_menus();
    $result = [];

    foreach ($menus as $menu) {
      $menu_items = wp_get_nav_menu_items($menu->term_id);
      
      $items = [];
      if ($menu_items) {
        foreach ($menu_items as $item) {
          $items[$item->ID] = [
            'id' => $item->ID,
            'title' => $item->title,
            'url' => $item->url,
            'parent' => $item->menu_item_parent,
            'children' => []
          ];
        }

        // Build tree
        $tree = [];
        foreach ($items as $id => &$node) {
          if ($node['parent'] == 0) {
            $tree[] = &$node;
          } else {
            if (isset($items[$node['parent']])) {
              $items[$node['parent']]['children'][] = &$node;
            }
          }
        }
        $menu_items_tree = $tree;
      } else {
        $menu_items_tree = [];
      }

      $result[] = [
        'id' => $menu->term_id,
        'name' => $menu->name,
        'slug' => $menu->slug,
        'items' => $menu_items_tree
      ];
    }

    return new WP_REST_Response($result, 200);
  }

  /**
   * Retrieve Forminator modules (Forms, Polls, Quizzes) for the Vue frontend.
   */
  public function get_forminator_modules() {
    if ( ! class_exists( 'Forminator_API' ) ) {
      wp_send_json_error( array( 'message' => 'Forminator plugin is not active' ) );
      return;
    }

    $forms = Forminator_API::get_forms(null, 1, 999, 'any');
    $polls = Forminator_API::get_polls(null, 1, 999, 'any');
    $quizzes = Forminator_API::get_quizzes(null, 1, 999, 'any');

    $format_modules = function($modules) {
      $res = array();
      if (is_array($modules) || is_object($modules)) {
        foreach($modules as $m) {
          $name = isset($m->settings['formName']) ? $m->settings['formName'] : (isset($m->settings['pollName']) ? $m->settings['pollName'] : (isset($m->settings['quizName']) ? $m->settings['quizName'] : ''));
          if (empty($name) && isset($m->name)) {
            $name = $m->name;
          }
          
          $res[] = array(
            'id' => isset($m->id) ? $m->id : '',
            'name' => $name,
            'status' => isset($m->status) && $m->status === 'publish' ? 1 : 0,
          );
        }
      }
      return $res;
    };

    $response = array(
      'forms' => $format_modules($forms),
      'polls' => $format_modules($polls),
      'quizzes' => $format_modules($quizzes)
    );

    wp_send_json_success( $response );
  }
}
