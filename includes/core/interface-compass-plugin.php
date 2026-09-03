<?php
/**
 * Interface for all Compass ecosystem plugins.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Xophz_Compass_Plugin_Interface {

	/**
	 * Get the plugin unique identifier slug (e.g. 'bomb-bag', 'card-vault').
	 *
	 * @return string
	 */
	public function get_slug(): string;

	/**
	 * Get current SemVer version of the plugin.
	 *
	 * @return string
	 */
	public function get_version(): string;

	/**
	 * Get the full filesystem path to the main plugin directory.
	 *
	 * @return string
	 */
	public function get_path(): string;

	/**
	 * Get the URL pointing to the main plugin directory.
	 *
	 * @return string
	 */
	public function get_url(): string;

	/**
	 * Initialize plugin components, hooks, and submodules.
	 *
	 * @return void
	 */
	public function init(): void;
}
