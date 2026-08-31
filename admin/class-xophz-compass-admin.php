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

    if( isset($_GET['page']) && $_GET['page'] === $this->plugin_name ){
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

    if( isset($_GET['page']) && $_GET['page'] === $this->plugin_name ){
      wp_enqueue_script( 'wp-api' );
      
      // Dequeue native WordPress Gutenberg command palette to prevent conflicting bottom search widget
      wp_dequeue_script( 'wp-commands' );
      wp_dequeue_script( 'wp-core-commands' );
      wp_dequeue_style( 'wp-commands' );
      
      // Prevent blackbox smoke script from loading in compass admin page since compass handles its own
      if ( class_exists( '\BlackBOX\Core' ) ) {
        remove_action( 'admin_footer', [ \BlackBOX\Core::class, 'inject_canvas_script' ], 9999 );
      }

      // Prepare data for injection
      global $_wp_admin_css_colors, $menu, $submenu;
      $current_user = wp_get_current_user();
      
      $ehVersion = defined('XOPHZ_COMPASS_EVENT_HORIZON_VERSION')
        ? XOPHZ_COMPASS_EVENT_HORIZON_VERSION
        : '0.0.0';

      $settings = [
          'adminColors' => $_wp_admin_css_colors,
          'currentUser' => [
              'ID' => $current_user->ID,
              'user_login' => $current_user->user_login,
              'user_email' => $current_user->user_email,
              'display_name' => $current_user->display_name,
              'user_nicename' => $current_user->user_nicename,
              'admin_color' => get_user_option('admin_color', $current_user->ID),
              'roles' => $current_user->roles,
              'avatar' => get_user_meta($current_user->ID, 'youmeos_avatar_url', true) ?: get_avatar_url($current_user->ID, ['size' => 96]),
          ],
          'adminMenu' => $menu,
          'adminSubmenu' => $submenu,
          'nonce' => wp_create_nonce( 'wp_rest' ),
          'restUrl' => get_rest_url(),
          'siteName' => get_bloginfo('name'),
          'siteDescription' => get_bloginfo('description'),
          'siteUrl' => get_bloginfo('url'),
          'compassVersion' => XOPHZ_COMPASS_VERSION,
          'eventHorizonVersion' => $ehVersion,
          'vapidPublicKey' => class_exists( 'Xophz_Compass_Push_API' ) ? Xophz_Compass_Push_API::get_public_key() : '',
          'branding' => class_exists( 'Xophz_Compass_Branding' ) ? Xophz_Compass_Branding::get_config() : null,
          'pluginUrl' => plugin_dir_url( dirname( __FILE__ ) ),
          'assetsUrl' => plugin_dir_url( dirname( __FILE__ ) ) . 'assets/',
          'texturesUrl' => plugin_dir_url( dirname( __FILE__ ) ) . 'assets/textures/planets/',
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
        add_action('admin_head', [$this, 'output_initial_menu_styles']);
      }
    }
  }

  /**
   * Prevents flash of WordPress menu on initial page load
   */
  public function output_initial_menu_styles() {
    $is_open = isset( $_COOKIE['xophz_wp_menu_open'] ) && $_COOKIE['xophz_wp_menu_open'] === '1';
    if ( ! $is_open ) {
      echo '<style id="xophz-compass-menu-closed-init">body.compass-menu-closed #adminmenuwrap, body.compass-menu-closed #adminmenuback, #wpwrap.compass-menu-closed #adminmenuwrap, #wpwrap.compass-menu-closed #adminmenuback { transform: translateX(-100%) !important; transition: none !important; }</style>';
    }
  }

  /**
   * Adds compass-menu-closed class to admin body classes when menu is closed
   */
  public function add_admin_body_classes( $classes ) {
    if ( isset( $_GET['page'] ) && $_GET['page'] === $this->plugin_name ) {
      $is_open = isset( $_COOKIE['xophz_wp_menu_open'] ) && $_COOKIE['xophz_wp_menu_open'] === '1';
      if ( ! $is_open ) {
        $classes .= ' compass-menu-closed';
      }
    }
    return $classes;
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
    $isDisabled = ! get_option( 'xophz_compass_show_admin_bar', true );
    if ( $isDisabled ) return;

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

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

    $capability = 'manage_options';
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
   * Add My Compass submenu under Settings
   *
   * @since    1.0.0
   */
  public function add_w4_my_compass_menu() {
      add_submenu_page(
          'options-general.php',
          'My Compass Settings',
          'My Compass Settings',
          'manage_options',
          'w4-my-compass',
          array( $this, 'render_my_compass_settings_page' )
      );
  }

  /**
   * Render the My Compass settings page
   *
   * @since    1.0.0
   */
  public function render_my_compass_settings_page() {
      if ( ! current_user_can( 'manage_options' ) ) {
          return;
      }
      ?>
      <div class="wrap">
          <h1>My Compass Settings</h1>
          <form action="options.php" method="post">
              <?php
              settings_fields( 'xophz_compass_settings_group' );
              do_settings_sections( 'w4-my-compass' );
              submit_button( 'Save Settings' );
              ?>
          </form>
      </div>
      <?php
  }

  /**
   * Register settings for My Compass
   *
   * @since    1.0.0
   */
  public function register_my_compass_settings() {
      register_setting( 'xophz_compass_settings_group', 'xophz_compass_show_admin_bar', [
          'type' => 'string',
          'default' => true,
          'sanitize_callback' => 'rest_sanitize_boolean',
      ] );

      register_setting( 'xophz_compass_settings_group', 'xophz_compass_redirect_dashboard', [
          'type' => 'string',
          'default' => true,
          'sanitize_callback' => 'rest_sanitize_boolean',
      ] );

      add_settings_section(
          'xophz_compass_main_section',
          'General Configuration',
          array( $this, 'render_compass_settings_section' ),
          'w4-my-compass'
      );

      add_settings_field(
          'xophz_compass_show_admin_bar_field',
          'Admin Bar Menu',
          array( $this, 'render_compass_admin_bar_field' ),
          'w4-my-compass',
          'xophz_compass_main_section'
      );

      add_settings_field(
          'xophz_compass_redirect_dashboard_field',
          'Dashboard Redirect',
          array( $this, 'render_compass_redirect_dashboard_field' ),
          'w4-my-compass',
          'xophz_compass_main_section'
      );

      register_setting( 'xophz_compass_settings_group', 'xophz_compass_mail_sender_name', [
          'type' => 'string',
          'default' => '',
          'sanitize_callback' => 'sanitize_text_field',
      ] );

      register_setting( 'xophz_compass_settings_group', 'xophz_compass_mail_sender_email', [
          'type' => 'string',
          'default' => '',
          'sanitize_callback' => 'sanitize_email',
      ] );

      add_settings_section(
          'xophz_compass_mail_sender_section',
          'Mail Sender Settings',
          array( $this, 'render_compass_mail_sender_section' ),
          'w4-my-compass'
      );

      add_settings_field(
          'xophz_compass_mail_sender_name_field',
          'Mail Sender Name',
          array( $this, 'render_compass_mail_sender_name_field' ),
          'w4-my-compass',
          'xophz_compass_mail_sender_section'
      );

      add_settings_field(
          'xophz_compass_mail_sender_email_field',
          'Mail Sender Email',
          array( $this, 'render_compass_mail_sender_email_field' ),
          'w4-my-compass',
          'xophz_compass_mail_sender_section'
      );

      // AEO Settings
      register_setting( 'xophz_compass_settings_group', 'xophz_compass_aeo_enabled', [
          'type' => 'string',
          'default' => true,
          'sanitize_callback' => 'rest_sanitize_boolean',
      ] );

      register_setting( 'xophz_compass_settings_group', 'xophz_compass_aeo_org_name', [
          'type' => 'string',
          'default' => '',
          'sanitize_callback' => 'sanitize_text_field',
      ] );

      register_setting( 'xophz_compass_settings_group', 'xophz_compass_aeo_logo_url', [
          'type' => 'string',
          'default' => '',
          'sanitize_callback' => 'sanitize_url',
      ] );

      add_settings_section(
          'xophz_compass_aeo_section',
          'Answer Engine Optimization (AEO)',
          array( $this, 'render_compass_aeo_section' ),
          'w4-my-compass'
      );

      add_settings_field(
          'xophz_compass_aeo_enabled_field',
          'Enable AEO JSON-LD',
          array( $this, 'render_compass_aeo_enabled_field' ),
          'w4-my-compass',
          'xophz_compass_aeo_section'
      );

      add_settings_field(
          'xophz_compass_aeo_org_name_field',
          'Publisher Organization Name',
          array( $this, 'render_compass_aeo_org_name_field' ),
          'w4-my-compass',
          'xophz_compass_aeo_section'
      );

      add_settings_field(
          'xophz_compass_aeo_logo_url_field',
          'Publisher Logo URL',
          array( $this, 'render_compass_aeo_logo_url_field' ),
          'w4-my-compass',
          'xophz_compass_aeo_section'
      );
  }

  public function render_compass_settings_section() {
      echo '<p>Configure general settings for the My Compass platform.</p>';
  }

  public function render_compass_admin_bar_field() {
      $isEnabled = get_option( 'xophz_compass_show_admin_bar', true );
      ?>
      <label>
          <input type="checkbox" name="xophz_compass_show_admin_bar" value="1" <?php checked( $isEnabled, true ); ?>>
          Show the My Compass menu in the WordPress admin bar.
      </label>
      <?php
  }

  public function render_compass_redirect_dashboard_field() {
      $isEnabled = get_option( 'xophz_compass_redirect_dashboard', true );
      ?>
      <label>
          <input type="checkbox" name="xophz_compass_redirect_dashboard" value="1" <?php checked( $isEnabled, true ); ?>>
          Redirect administrators to the Compass dashboard after login and when clicking Dashboard.
      </label>
      <?php
  }

  public function render_compass_mail_sender_section() {
      echo '<p>You may change your WordPress default mail sender name and email. If left blank, it defaults to the site name and noreply@sitedomain.</p>';
  }

  public function render_compass_mail_sender_name_field() {
      $val = get_option('xophz_compass_mail_sender_name');
      printf(
          '<input name="xophz_compass_mail_sender_name" type="text" class="regular-text" value="%s" placeholder="%s"/>',
          esc_attr($val),
          esc_attr(wp_specialchars_decode(get_option('blogname'), ENT_QUOTES))
      );
  }

  public function render_compass_mail_sender_email_field() {
      $val = get_option('xophz_compass_mail_sender_email');
      
      $sitename = wp_parse_url( network_home_url(), PHP_URL_HOST );
      if ( null === $sitename ) {
          $sitename = 'example.com';
      }
      if ( strpos( $sitename, 'www.' ) === 0 ) {
          $sitename = substr( $sitename, 4 );
      }
      $placeholder = 'noreply@' . $sitename;
      
      printf(
          '<input name="xophz_compass_mail_sender_email" type="email" class="regular-text" value="%s" placeholder="%s"/>',
          esc_attr($val),
          esc_attr($placeholder)
      );
  }

  public function render_compass_aeo_section() {
      echo '<p>Configure Answer Engine Optimization (AEO) to inject structured data (JSON-LD) and an llms.txt endpoint for AI crawlers like ChatGPT, Perplexity, and Claude.</p>';
  }

  public function render_compass_aeo_enabled_field() {
      $isEnabled = get_option( 'xophz_compass_aeo_enabled', true );
      ?>
      <label>
          <input type="checkbox" name="xophz_compass_aeo_enabled" value="1" <?php checked( $isEnabled, true ); ?>>
          Automatically inject dynamic AEO JSON-LD and enable /llms.txt virtual file.
      </label>
      <?php
  }

  public function render_compass_aeo_org_name_field() {
      $val = get_option('xophz_compass_aeo_org_name');
      $placeholder = get_bloginfo('name');
      printf(
          '<input name="xophz_compass_aeo_org_name" type="text" class="regular-text" value="%s" placeholder="%s"/>',
          esc_attr($val),
          esc_attr($placeholder)
      );
      echo '<p class="description">If empty, defaults to your WordPress Site Name.</p>';
  }

  public function render_compass_aeo_logo_url_field() {
      $val = get_option('xophz_compass_aeo_logo_url');
      $placeholder = '';
      if ( has_custom_logo() ) {
          $custom_logo_id = get_theme_mod( 'custom_logo' );
          $placeholder = wp_get_attachment_image_url( $custom_logo_id, 'full' );
      }
      printf(
          '<input name="xophz_compass_aeo_logo_url" type="url" class="regular-text" value="%s" placeholder="%s"/>',
          esc_attr($val),
          esc_attr($placeholder)
      );
      echo '<p class="description">If empty, defaults to your WordPress Custom Logo.</p>';
  }

  public function redirect_login_to_compass( $redirect_to, $requested_redirect_to, $user ) {
      $isDisabled = ! get_option( 'xophz_compass_redirect_dashboard', true );
      if ( $isDisabled ) return $redirect_to;

      $isNotUser = is_wp_error( $user ) || ! is_object( $user );
      if ( $isNotUser ) return $redirect_to;

      $isAdmin = in_array( 'administrator', (array) $user->roles, true );
      $isDefaultRedirect = $requested_redirect_to === '' || $requested_redirect_to === admin_url();

      if ( $isAdmin && $isDefaultRedirect ) {
          return admin_url( 'admin.php?page=xophz-compass' );
      }

      return $redirect_to;
  }

  public function redirect_dashboard_index() {
      $isDisabled = ! get_option( 'xophz_compass_redirect_dashboard', true );
      if ( $isDisabled ) return;

      if ( is_network_admin() ) return;
      if ( isset( $_GET['wp_dashboard'] ) || isset( $_GET['wp'] ) || isset( $_GET['native'] ) ) return;

      $isAdmin = current_user_can( 'manage_options' );
      if ( ! $isAdmin ) return;

      wp_safe_redirect( admin_url( 'admin.php?page=xophz-compass' ) );
      exit;
  }




  public function sort_xophz_submenu_alphabetically() {
      global $submenu;
      $parent_slug = 'xophz-compass';

      if ( isset( $submenu[ $parent_slug ] ) ) {
          $first_item = array_shift( $submenu[ $parent_slug ] );

          usort( $submenu[ $parent_slug ], function( $a, $b ) {
              return strcmp( $a[0], $b[0] );
          } );

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
    
    // Inject Magic Formulas since it's now bundled natively in compass but needs to appear as a plugin in UI
    $plugins['xophz-compass-magic-formula/xophz-compass-magic-formula.php'] = [
      'Name' => 'Xophz Magic Formulas',
      'PluginURI' => 'http://www.mycompassconsulting.com/',
      'Version' => '1.0.0',
      'Description' => 'The ultimate form, poll, and quiz builder.',
      'Author' => 'Xoph',
      'TextDomain' => 'xophz-compass-magic-formula',
      'DomainPath' => '/languages',
      'Network' => false,
      'Title' => 'Xophz Magic Formulas',
      'AuthorName' => 'Xoph',
      'Category' => 'Command Deck',
      'Group' => 'CRM'
    ];

    $vendor_prefix = Xophz_Compass_Branding::get_vendor_prefix();

    foreach($plugins as $p => $plugin){
      if(false === strpos($plugin['TextDomain'],'xophz-compass')){
        // LETS REMOVE EVERYTHING BUT XOPHZ 
        unset($plugins[$p]);
        continue;
      }
      $plugin_folder = dirname( $p );
      if ( $plugin_folder === '.' || empty( $plugin_folder ) ) {
        $plugin_folder = $plugin['TextDomain'];
      }
      $plugin_dir = wp_make_link_relative( plugins_url( $plugin_folder ) );

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
      
      $icon_path     = WP_PLUGIN_DIR . '/' . $plugin_folder . '/icon.svg';
      $icon_png_path = WP_PLUGIN_DIR . '/' . $plugin_folder . '/icon.png';
      $bundled_png   = dirname( __FILE__, 2 ) . '/assets/' . $plugin['TextDomain'] . '.png';
      $bundled_svg   = dirname( __FILE__, 2 ) . '/assets/' . $plugin['TextDomain'] . '.svg';

      if (file_exists($icon_path)) {
        $icon_version = filemtime($icon_path);
        $plugins[$p]['icon'] = "{$plugin_dir}/icon.svg?v={$icon_version}";
      } elseif (file_exists($icon_png_path)) {
        $icon_version = filemtime($icon_png_path);
        $plugins[$p]['icon'] = "{$plugin_dir}/icon.png?v={$icon_version}";
      } elseif (file_exists($bundled_png)) {
        $plugins[$p]['icon'] = wp_make_link_relative( plugins_url( 'assets/' . $plugin['TextDomain'] . '.png', dirname( __FILE__, 2 ) . '/xophz-compass.php' ) );
      } elseif (file_exists($bundled_svg)) {
        $plugins[$p]['icon'] = wp_make_link_relative( plugins_url( 'assets/' . $plugin['TextDomain'] . '.svg', dirname( __FILE__, 2 ) . '/xophz-compass.php' ) );
      } else {
        $owner = ( strpos( $plugin['TextDomain'], 'super-nerd-bros' ) !== false || strpos( $plugin['TextDomain'], 'nook-phone' ) !== false ) ? 'SuperNerdBros' : 'HalloftheGods';
        $plugins[$p]['icon'] = "https://raw.githubusercontent.com/{$owner}/{$plugin['TextDomain']}/main/icon.svg";
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
      $headers['Group'] = 'Group';
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
    echo "";
  }

  public function render_sidebar_version() {
    $v = defined('XOPHZ_COMPASS_VERSION') ? XOPHZ_COMPASS_VERSION : $this->version;
    ?>
    <script>
    (function(){
      var w = document.getElementById('adminmenuwrap');
      if (!w) return;
      var el = document.createElement('span');
      el.className = 'compass-sidebar-version';
      // Parse version to get just vXX.Y for collapsed state
      var fullVersion = 'v<?php echo esc_js($v); ?>';
      var shortVersion = fullVersion;
      var match = fullVersion.match(/^(v\d+\.\d+)/);
      if (match) shortVersion = match[1];

      el.innerHTML = '<span class="v-long">' + fullVersion + '</span><span class="v-short">' + shortVersion + '</span>';
      w.appendChild(el);
    })();
    </script>
    <?php
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

    $is_multisite = is_multisite();
    $is_super_admin = $is_multisite && is_super_admin( $user_id );
    $user_sites = array();

    if ( $is_multisite && $user_id ) {
      $blogs = get_blogs_of_user( $user_id );
      foreach ( $blogs as $blog ) {
        $blog_id = (int) $blog->userblog_id;
        $site_name = get_blog_option( $blog_id, 'blogname' ) ?: $blog->blogname;
        $site_url = get_blog_option( $blog_id, 'siteurl' ) ?: get_home_url( $blog_id );
        $admin_url = get_admin_url( $blog_id );
        $compass_url = get_admin_url( $blog_id, 'admin.php?page=xophz-compass' );

        $user_sites[] = array(
          'blog_id'     => $blog_id,
          'domain'      => $blog->domain,
          'path'        => $blog->path,
          'site_name'   => $site_name,
          'site_url'    => $site_url,
          'admin_url'   => $admin_url,
          'compass_url' => $compass_url,
        );
      }
    } else {
      $user_sites[] = array(
        'blog_id'     => (int) get_current_blog_id(),
        'domain'      => isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '',
        'path'        => '/',
        'site_name'   => get_bloginfo( 'name' ),
        'site_url'    => get_bloginfo( 'url' ),
        'admin_url'   => admin_url(),
        'compass_url' => admin_url( 'admin.php?page=xophz-compass' ),
      );
    }

    $blogInfo = [
      'name' => get_bloginfo('name'),
      'description' => get_bloginfo('description'),
      'url' => get_bloginfo('url'),
      'wpurl' => get_bloginfo('wpurl'),
      'version' => get_bloginfo('version'),
      'logouturl' => htmlspecialchars_decode(wp_logout_url()),
      'front_page_title' => get_option('show_on_front') === 'page' ? get_the_title(get_option('page_on_front')) : 'Latest Posts',
      'is_multisite' => $is_multisite,
      'is_super_admin' => $is_super_admin,
      'network_admin_url' => $is_multisite ? network_admin_url() : '',
      'user_sites' => $user_sites,
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
    if ( ! file_exists( $hotFilePath ) ) {
        return false;
    }

    // Safety check 1: Prevent loading insecure HTTP dev assets on a secure HTTPS production site
    if ( is_ssl() ) {
        return false;
    }

    // Safety check 2: Explicitly block known production/staging domains
    $host = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';
    if ( strpos($host, 'tempurl.host') !== false || strpos($host, 'youmeos.com') !== false || strpos($host, 'mycompassconsulting.com') !== false ) {
        return false;
    }

    return true;
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
      'permission_callback' => '__return_true'
    ]);

    register_rest_route('xophz-compass/v1', '/menus', [
      'methods'  => 'GET',
      'callback' => [$this, 'get_wp_menus'],
      'permission_callback' => function() {
        return current_user_can('manage_options');
      }
    ]);

    register_rest_route('xophz-compass/v1', '/admin-menu', [
      'methods'  => 'GET',
      'callback' => [$this, 'get_admin_menu_data'],
      'permission_callback' => function() {
        return current_user_can('manage_options');
      }
    ]);

    register_rest_route('xophz-compass/v1', '/versions', [
      'methods'  => 'GET',
      'callback' => [$this, 'get_plugin_versions'],
      'permission_callback' => function() {
        return current_user_can('manage_options');
      },
      'args' => [
        'slug' => [
          'required' => false,
          'type' => 'string',
          'sanitize_callback' => 'sanitize_text_field'
        ]
      ]
    ]);

    register_rest_route('xophz-compass/v1', '/search', [
      'methods'  => 'GET',
      'callback' => [$this, 'search_global'],
      'permission_callback' => '__return_true',
      'args' => [
        'q' => [
          'required' => true,
          'type' => 'string',
          'sanitize_callback' => 'sanitize_text_field'
        ]
      ]
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
    if ( ! function_exists( 'get_plugins' ) ) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
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
          'alphabet-soup' => 'newsroom'
      ];
      $slug = isset($route_map[$slug]) ? $route_map[$slug] : $slug;

      $default_name = trim(str_replace('Xophz', '', $plugin['Name']));
      $name = Xophz_Compass_Branding::get_plugin_name($slug, $default_name);
      $description = Xophz_Compass_Branding::get_plugin_description($slug, $plugin['Description']);

      $plugin_folder = dirname( $p );
      if ( $plugin_folder === '.' || empty( $plugin_folder ) ) {
        $plugin_folder = $plugin['TextDomain'];
      }
      $plugin_dir = wp_make_link_relative( plugins_url( $plugin_folder ) );
      if ($slug === 'magic-formula') {
        $icon_version = time();
        $icon = wp_make_link_relative( plugins_url('xophz-compass/assets/magic-formula.svg') ) . "?v={$icon_version}";
      } else {
        $icon_path = WP_PLUGIN_DIR . '/' . $plugin_folder . '/icon.svg';
        $icon_version = file_exists($icon_path) ? filemtime($icon_path) : time();
        $icon = "{$plugin_dir}/icon.svg?v={$icon_version}";
      }

      $available[] = [
        'slug' => $slug,
        'name' => $name,
        'defaultName' => $default_name,
        'version' => $plugin['Version'],
        'description' => $description,
        'category' => isset($plugin['Category']) ? $plugin['Category'] : '',
        'group' => isset($plugin['Group']) ? $plugin['Group'] : '',
        'icon' => $icon
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
   * Get the LIVE WordPress Admin Menu and Submenu data.
   *
   * @since    1.0.0
   * @return   WP_REST_Response
   */
  public function get_admin_menu_data() {
    // Set current screen to dashboard to satisfy plugins that check screen in admin_menu hooks
    if (function_exists('set_current_screen')) {
      set_current_screen('dashboard');
    } else {
      require_once ABSPATH . 'wp-admin/includes/screen.php';
      set_current_screen('dashboard');
    }
    
    // Load required admin files
    if (!defined('WP_ADMIN')) {
      define('WP_ADMIN', true);
    }
    require_once ABSPATH . 'wp-admin/includes/admin.php';
    
    // Run the menu building process
    require_once ABSPATH . 'wp-admin/menu.php';
    
    global $menu, $submenu;
    
    return new WP_REST_Response([
      'menu' => $menu ?: [],
      'submenu' => $submenu ?: []
    ], 200);
  }

  /**
   * Get the LIVE WordPress Admin Menu via admin-ajax
   *
   * @since    1.0.0
   */
  public function ajax_get_compass_admin_menu() {
    if (!current_user_can('manage_options')) {
      wp_send_json_error('Unauthorized', 401);
      return;
    }
    
    global $pagenow;
    if (empty($pagenow)) {
        $pagenow = 'index.php';
    }
    
    require_once ABSPATH . 'wp-admin/includes/admin.php';
    
    if (function_exists('set_current_screen')) {
      set_current_screen('dashboard');
    }
    
    require_once ABSPATH . 'wp-admin/menu.php';
    
    global $menu, $submenu;
    
    wp_send_json_success([
      'menu' => $menu ?: [],
      'submenu' => $submenu ?: []
    ]);
  }

  public function get_plugin_versions(WP_REST_Request $request) {
    if (!function_exists('get_plugins')) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $targetSlug = $request->get_param('slug');
    $plugins = get_plugins();
    $versions = [];

    foreach ($plugins as $file => $meta) {
      $isCompassPlugin = strpos($meta['TextDomain'] ?? '', 'xophz-compass') === 0;
      if (!$isCompassPlugin) continue;

      $slug = str_replace('xophz-compass-', '', $meta['TextDomain']);
      if ($slug === 'xophz-compass') $slug = 'compass';

      if ($targetSlug && $slug !== $targetSlug) continue;

      $versions[$slug] = [
        'version' => $meta['Version'] ?? '0.0.0',
        'name' => trim(str_replace('Xophz', '', $meta['Name'])),
        'active' => is_plugin_active($file)
      ];
    }

    if ($targetSlug && isset($versions[$targetSlug])) {
      return new WP_REST_Response($versions[$targetSlug], 200);
    }

    return new WP_REST_Response($versions, 200);
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

  /**
   * Search globally across WordPress posts, pages, CPTs, Bazaar products/coupons,
   * Bomb Bag marketing, Questbook CRM, Bugnet tickets, Yellow Links, and XP goals.
   *
   * @param WP_REST_Request $request
   * @return WP_REST_Response
   */
  public function search_global( WP_REST_Request $request ) {
    global $wpdb;
    $raw_query = trim( (string) $request->get_param('q') );
    if ( mb_strlen( $raw_query ) < 2 ) {
      return new WP_REST_Response( [], 200 );
    }

    $results = [];
    $like = '%' . $wpdb->esc_like( $raw_query ) . '%';

    // 1. Query WordPress Posts, Pages, Products, and Custom Post Types
    $post_types = get_post_types( [ 'public' => true ], 'names' );
    unset( $post_types['attachment'] );

    $wp_query = new WP_Query( [
      'post_type'      => array_values( $post_types ),
      'post_status'    => [ 'publish', 'draft', 'pending', 'private' ],
      's'              => $raw_query,
      'posts_per_page' => 8,
      'no_found_rows'  => true,
    ] );

    if ( $wp_query->have_posts() ) {
      while ( $wp_query->have_posts() ) {
        $wp_query->the_post();
        $p_id = get_the_ID();
        $p_type = get_post_type();
        $type_obj = get_post_type_object( $p_type );
        $type_label = $type_obj ? $type_obj->labels->singular_name : ucfirst( $p_type );

        $icon = 'fad fa-file-alt';
        $icon_color = '#62c9ff';
        $route = '/newsroom?type=' . $p_type . '&post=' . $p_id;

        if ( $p_type === 'page' ) {
          $icon = 'fad fa-file';
          $icon_color = '#00e676';
        } elseif ( $p_type === 'product' ) {
          $icon = 'fad fa-shopping-bag';
          $icon_color = '#ff9100';
          $sku = get_post_meta( $p_id, '_sku', true );
          $price = get_post_meta( $p_id, '_price', true );
          $route = '/bazaar/products';
          $subtitle = sprintf( 'Product • %s%s', $price ? '$' . $price . ' • ' : '', $sku ? 'SKU: ' . $sku : ucfirst( get_post_status() ) );
        } elseif ( $p_type === 'post' ) {
          $icon = 'fad fa-newspaper';
          $icon_color = '#29b6f6';
        } elseif ( $p_type === 'bugnet_bug' ) {
          $icon = 'fad fa-bug';
          $icon_color = '#ff5252';
          $route = '/bugnet/detail/' . $p_id;
        } elseif ( $p_type === 'questbook_quest' ) {
          $icon = 'fad fa-scroll';
          $icon_color = '#ab47bc';
          $route = '/questbook/profile';
        }

        if ( ! isset( $subtitle ) ) {
          $subtitle = sprintf( '%s • Status: %s', $type_label, ucfirst( get_post_status() ) );
        }

        $results[] = [
          'id'            => 'post-' . $p_id,
          'title'         => html_entity_decode( get_the_title(), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ?: 'Untitled',
          'subtitle'      => $subtitle,
          'category'      => 'views',
          'categoryLabel' => $type_label,
          'icon'          => $icon,
          'iconColor'     => $icon_color,
          'route'         => $route,
          'badge'         => ucfirst( get_post_status() ),
        ];
        unset( $subtitle );
      }
      wp_reset_postdata();
    }

    // 2. Query WooCommerce Coupons (Bazaar)
    $coupon_query = new WP_Query( [
      'post_type'      => 'shop_coupon',
      'post_status'    => 'publish',
      's'              => $raw_query,
      'posts_per_page' => 4,
      'no_found_rows'  => true,
    ] );
    if ( $coupon_query->have_posts() ) {
      while ( $coupon_query->have_posts() ) {
        $coupon_query->the_post();
        $c_id = get_the_ID();
        $results[] = [
          'id'            => 'coupon-' . $c_id,
          'title'         => get_the_title(),
          'subtitle'      => 'Discount Coupon Code',
          'category'      => 'views',
          'categoryLabel' => 'Bazaar Coupons',
          'icon'          => 'fad fa-ticket-alt',
          'iconColor'     => '#ff9100',
          'route'         => '/bazaar/coupons',
          'badge'         => 'Coupon',
        ];
      }
      wp_reset_postdata();
    }

    // 3. Query Bomb Bag (Journeys, Campaigns, Subscribers, Lists, Templates)
    $bb_journeys = $wpdb->prefix . 'bomb_bag_journeys';
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$bb_journeys}'" ) === $bb_journeys ) {
      $journeys = $wpdb->get_results( $wpdb->prepare(
        "SELECT id, name, description, status FROM {$bb_journeys} WHERE name LIKE %s OR description LIKE %s LIMIT 4",
        $like, $like
      ) );
      if ( ! empty( $journeys ) ) {
        foreach ( $journeys as $j ) {
          $results[] = [
            'id'            => 'bb-journey-' . $j->id,
            'title'         => $j->name,
            'subtitle'      => $j->description ?: 'Automated email journey',
            'category'      => 'views',
            'categoryLabel' => 'Journeys',
            'icon'          => 'fad fa-map-marked-alt',
            'iconColor'     => '#62c9ff',
            'route'         => '/bomb-bag/journeys',
            'badge'         => ucfirst( $j->status ),
          ];
        }
      }
    }

    $bb_campaigns = $wpdb->prefix . 'bomb_bag_campaigns';
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$bb_campaigns}'" ) === $bb_campaigns ) {
      $campaigns = $wpdb->get_results( $wpdb->prepare(
        "SELECT id, name, subject, status FROM {$bb_campaigns} WHERE name LIKE %s OR subject LIKE %s LIMIT 4",
        $like, $like
      ) );
      if ( ! empty( $campaigns ) ) {
        foreach ( $campaigns as $c ) {
          $results[] = [
            'id'            => 'bb-campaign-' . $c->id,
            'title'         => $c->name,
            'subtitle'      => sprintf( 'Subject: %s', $c->subject ?: 'None' ),
            'category'      => 'views',
            'categoryLabel' => 'Campaigns',
            'icon'          => 'fad fa-bullhorn',
            'iconColor'     => '#62c9ff',
            'route'         => '/bomb-bag/campaigns',
            'badge'         => ucfirst( $c->status ),
          ];
        }
      }
    }

    $bb_subs = $wpdb->prefix . 'bomb_bag_subscribers';
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$bb_subs}'" ) === $bb_subs ) {
      $subscribers = $wpdb->get_results( $wpdb->prepare(
        "SELECT id, email, first_name, last_name, status FROM {$bb_subs} WHERE email LIKE %s OR first_name LIKE %s OR last_name LIKE %s LIMIT 4",
        $like, $like, $like
      ) );
      if ( ! empty( $subscribers ) ) {
        foreach ( $subscribers as $s ) {
          $name = trim( $s->first_name . ' ' . $s->last_name );
          $results[] = [
            'id'            => 'bb-sub-' . $s->id,
            'title'         => $name ? $name . ' (' . $s->email . ')' : $s->email,
            'subtitle'      => sprintf( 'Subscriber • Status: %s', ucfirst( $s->status ) ),
            'category'      => 'views',
            'categoryLabel' => 'Subscribers',
            'icon'          => 'fad fa-user-circle',
            'iconColor'     => '#00e676',
            'route'         => '/bomb-bag/subscribers',
            'badge'         => ucfirst( $s->status ),
          ];
        }
      }
    }

    $bb_lists = $wpdb->prefix . 'bomb_bag_lists';
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$bb_lists}'" ) === $bb_lists ) {
      $lists = $wpdb->get_results( $wpdb->prepare(
        "SELECT id, name, description FROM {$bb_lists} WHERE name LIKE %s OR description LIKE %s LIMIT 3",
        $like, $like
      ) );
      if ( ! empty( $lists ) ) {
        foreach ( $lists as $l ) {
          $results[] = [
            'id'            => 'bb-list-' . $l->id,
            'title'         => $l->name,
            'subtitle'      => $l->description ?: 'Contact Audience List',
            'category'      => 'views',
            'categoryLabel' => 'Audience Lists',
            'icon'          => 'fad fa-th-list',
            'iconColor'     => '#62c9ff',
            'route'         => '/bomb-bag/lists',
            'badge'         => 'List',
          ];
        }
      }
    }

    $bb_templates = $wpdb->prefix . 'bomb_bag_templates';
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$bb_templates}'" ) === $bb_templates ) {
      $templates = $wpdb->get_results( $wpdb->prepare(
        "SELECT id, name, description, category FROM {$bb_templates} WHERE name LIKE %s OR description LIKE %s LIMIT 4",
        $like, $like
      ) );
      if ( ! empty( $templates ) ) {
        foreach ( $templates as $t ) {
          $results[] = [
            'id'            => 'bb-template-' . $t->id,
            'title'         => $t->name,
            'subtitle'      => $t->description ?: 'Email Stationery Template',
            'category'      => 'views',
            'categoryLabel' => 'Templates',
            'icon'          => 'fad fa-palette',
            'iconColor'     => '#ab47bc',
            'route'         => '/bomb-bag/templates',
            'badge'         => ucfirst( $t->category ?: 'Template' ),
          ];
        }
      }
    }

    // 4. Query Questbook CRM (Deals, Contacts, Organizations)
    $qb_deals = $wpdb->prefix . 'questbook_deals';
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$qb_deals}'" ) === $qb_deals ) {
      $deals = $wpdb->get_results( $wpdb->prepare(
        "SELECT id, title, value, stage FROM {$qb_deals} WHERE title LIKE %s LIMIT 4",
        $like
      ) );
      if ( ! empty( $deals ) ) {
        foreach ( $deals as $d ) {
          $results[] = [
            'id'            => 'qb-deal-' . $d->id,
            'title'         => $d->title,
            'subtitle'      => sprintf( 'Deal • Value: $%s • Stage: %s', number_format( (float) $d->value, 2 ), ucfirst( $d->stage ) ),
            'category'      => 'views',
            'categoryLabel' => 'Questbook Deals',
            'icon'          => 'fad fa-handshake',
            'iconColor'     => '#ffd54f',
            'route'         => '/questbook/deals',
            'badge'         => ucfirst( $d->stage ),
          ];
        }
      }
    }

    $qb_contacts = $wpdb->prefix . 'questbook_contacts';
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$qb_contacts}'" ) === $qb_contacts ) {
      $contacts = $wpdb->get_results( $wpdb->prepare(
        "SELECT id, name, email, phone, company FROM {$qb_contacts} WHERE name LIKE %s OR email LIKE %s OR company LIKE %s LIMIT 4",
        $like, $like, $like
      ) );
      if ( ! empty( $contacts ) ) {
        foreach ( $contacts as $c ) {
          $results[] = [
            'id'            => 'qb-contact-' . $c->id,
            'title'         => $c->name,
            'subtitle'      => sprintf( '%s%s', $c->email, $c->company ? ' • ' . $c->company : '' ),
            'category'      => 'views',
            'categoryLabel' => 'CRM Contacts',
            'icon'          => 'fad fa-address-card',
            'iconColor'     => '#00e676',
            'route'         => '/questbook/directory',
            'badge'         => 'Contact',
          ];
        }
      }
    }

    // 5. Query Yellow Links Directory
    $yellow_links_table = $wpdb->prefix . 'yellow_links';
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$yellow_links_table}'" ) === $yellow_links_table ) {
      $links = $wpdb->get_results( $wpdb->prepare(
        "SELECT id, title, url, category FROM {$yellow_links_table} WHERE title LIKE %s OR url LIKE %s OR category LIKE %s LIMIT 4",
        $like, $like, $like
      ) );
      if ( ! empty( $links ) ) {
        foreach ( $links as $l ) {
          $results[] = [
            'id'            => 'link-' . $l->id,
            'title'         => $l->title ?: $l->url,
            'subtitle'      => $l->url,
            'category'      => 'views',
            'categoryLabel' => 'Yellow Links',
            'icon'          => 'fad fa-link',
            'iconColor'     => '#ffd54f',
            'route'         => '/yellow-links',
            'badge'         => $l->category ?: 'Link',
          ];
        }
      }
    }

    // 6. Query Sparks (Event Horizon)
    $sparks_table = $wpdb->prefix . 'xophz_sparks';
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$sparks_table}'" ) === $sparks_table ) {
      $sparks = $wpdb->get_results( $wpdb->prepare(
        "SELECT id, name, description, category FROM {$sparks_table} WHERE name LIKE %s OR description LIKE %s LIMIT 4",
        $like, $like
      ) );
      if ( ! empty( $sparks ) ) {
        foreach ( $sparks as $sp ) {
          $results[] = [
            'id'            => 'spark-' . $sp->id,
            'title'         => $sp->name,
            'subtitle'      => $sp->description ?: 'Event Horizon Spark Trigger',
            'category'      => 'sparks',
            'categoryLabel' => 'Sparks',
            'icon'          => 'fad fa-bolt',
            'iconColor'     => '#00e5ff',
            'route'         => '/event-horizon/settings',
            'badge'         => 'Spark',
          ];
        }
      }
    }

    // 7. Allow external plugins to inject their indexed records via WordPress filter
    $results = apply_filters( 'xophz_compass_global_search_results', $results, $raw_query, $request );

    return new WP_REST_Response( $results, 200 );
  }
}
