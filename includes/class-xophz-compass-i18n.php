<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://mycompassconsulting.com
 * @since      0.0.0
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 * @author     Your Name <email@example.com>
 */
class Xophz_Compass_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'xophz-compass',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
