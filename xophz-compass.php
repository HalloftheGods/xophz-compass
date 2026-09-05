<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://youmeos.com
 * @since             0.0.1
 * @package           Xophz_COMPASS
 *
 * @wordpress-plugin
 * Plugin Name:       My Compass Engine
 * Plugin URI:        https://github.com/HalloftheGods/xophz-compass
 * Description:       It's dangerous to go alone! Explore the depths of your site without getting lost using my handy dandy COMPASS. 
 * Version:           26.9.5-491
 * Author:            Hall of the Gods, Inc.
 * Author URI:        https://www.hallofthegods.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       xophz-compass
 * Domain Path:       /languages
 * Update URI:        https://github.com/HalloftheGods/xophz-compass
 * Category:          Command Deck
 * Group:             OS
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'XOPHZ_COMPASS_VERSION', '26.9.5-491' );
define( 'XOPHZ_COMPASS_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Register Core Helper Suite autoloader for Xophz_Compass_* classes.
 */
require_once XOPHZ_COMPASS_PATH . 'includes/core/class-compass-autoloader.php';
Xophz_Compass_Autoloader::register();

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-xophz-compass-activator.php
 */
function activate_xophz_compass() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-xophz-compass-activator.php';
	Xophz_Compass_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-xophz-compass-deactivator.php
 */
function deactivate_xophz_compass() {
	// Deactivation cleanup handled by Core Helper Suite
}

register_activation_hook( __FILE__, 'activate_xophz_compass' );
register_deactivation_hook( __FILE__, 'deactivate_xophz_compass' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-xophz-compass.php';

/**
 * Register WP 7.0 Custom Connectors.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-xophz-compass-connectors.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_xophz_compass() {
  $plugin = new Xophz_Compass();
  $plugin->run();
}
add_action( 'plugins_loaded', 'run_xophz_compass' );

function xophz_compass_action_links( $links ) {
  foreach ( $links as $link ) {
    if ( stripos( $link, '>Settings<' ) !== false ) {
      return $links;
    }
  }
  $settings_link = '<a href="options-general.php?page=w4-my-compass">' . __( 'Settings', 'xophz-compass' ) . '</a>';
  $new_links = array( 'settings' => $settings_link );
  foreach ( $links as $key => $value ) {
    $new_links[ $key ] = $value;
  }
  return $new_links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'xophz_compass_action_links' );
