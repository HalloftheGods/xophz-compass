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
 * Plugin Name:       My Compass 
 * Plugin URI:        https://github.com/HalloftheGods/xophz-compass
 * Description:       It's dangerous to go alone! Explore the depths of your site without getting lost using my handy dandy COMPASS. 
 * Version:           26.5.14.376
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
define( 'XOPHZ_COMPASS_VERSION', '26.5.14.376' );

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
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-xophz-compass-deactivator.php';
	Xophz_Compass_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_xophz_compass' );
register_deactivation_hook( __FILE__, 'deactivate_xophz_compass' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-xophz-compass.php';

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
