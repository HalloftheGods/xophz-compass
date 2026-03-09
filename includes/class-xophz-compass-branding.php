<?php

/**
 * Centralized branding configuration for COMPASS white-label deployments.
 *
 * This class manages all customizable display names, titles, and descriptions
 * across the COMPASS plugin ecosystem.
 *
 * @link       https://mycompassconsulting.com
 * @since      1.0.0
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

/**
 * Branding configuration helper class.
 *
 * Provides static methods for retrieving and updating branding configuration
 * stored in wp_options. Includes Wizard mode detection for admin access.
 *
 * @since      1.0.0
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 * @author     Xopher <x@mycompassconsulting.com>
 */
class Xophz_Compass_Branding {

    /**
     * The option key used to store branding configuration in wp_options.
     *
     * @since    1.0.0
     * @var      string
     */
    const OPTION_KEY = 'compass_branding';

    /**
     * SHA-256 hash of the Wizard key for secure verification.
     * Generate with: echo -n "your-secret-key" | sha256sum
     *
     * @since    1.0.0
     * @var      string
     */
    const WIZARD_KEY_HASH = 'fada515c5b1b5842ea69e871c2a16af7e62d7409af96e051106f7cb6e59a1c14';

    /**
     * Cached branding configuration to avoid repeated database queries.
     *
     * @since    1.0.0
     * @access   private
     * @var      array|null
     */
    private static $cache = null;

    /**
     * Get the default branding configuration.
     *
     * @since    1.0.0
     * @return   array    Default branding values.
     */
    public static function get_defaults(): array {
        return [
            'platform_name' => 'COMPASS',
            'vendor_prefix' => 'Xophz ',
            'menu_title'    => 'Xophz COMPASS',
            'menu_icon'     => 'dashicons-editor-customchar',
            'plugins'       => []
        ];
    }

    /**
     * Get the full branding configuration with defaults merged.
     *
     * @since    1.0.0
     * @return   array    Complete branding configuration.
     */
    public static function get_config(): array {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $saved = get_option(self::OPTION_KEY, []);
        $defaults = self::get_defaults();

        self::$cache = array_merge($defaults, $saved);

        return self::$cache;
    }

    /**
     * Get a specific branding value by key.
     *
     * @since    1.0.0
     * @param    string    $key       The branding key to retrieve.
     * @param    mixed     $default   Optional default value if key doesn't exist.
     * @return   mixed                The branding value.
     */
    public static function get(string $key, $default = null) {
        $config = self::get_config();
        return $config[$key] ?? $default;
    }

    public static function get_plugin_name(string $slug, string $default_name = ''): string {
        $config = self::get_config();
        
        // Check for custom name override
        if (isset($config['plugins'][$slug]['name']) && !empty($config['plugins'][$slug]['name'])) {
            return $config['plugins'][$slug]['name'];
        }

        // Return header name stripped of Xophz
        if (!empty($default_name)) {
            return $default_name;
        }

        // Fallback: humanize the slug
        return ucwords(str_replace('-', ' ', $slug));
    }

    /**
     * Get a plugin's description with branding applied.
     *
     * @since    1.0.0
     * @param    string    $slug           The plugin slug.
     * @param    string    $default_desc   The original plugin description.
     * @return   string                    The branded description.
     */
    public static function get_plugin_description(string $slug, string $default_desc = ''): string {
        $config = self::get_config();
        
        // Check for custom description override
        if (isset($config['plugins'][$slug]['description']) && !empty($config['plugins'][$slug]['description'])) {
            return $config['plugins'][$slug]['description'];
        }

        return $default_desc;
    }

    /**
     * Get the main admin menu title.
     *
     * @since    1.0.0
     * @return   string    The menu title.
     */
    public static function get_menu_title(): string {
        return self::get('menu_title', 'COMPASS');
    }

    /**
     * Get the vendor prefix to strip from plugin names.
     *
     * @since    1.0.0
     * @return   string    The vendor prefix.
     */
    public static function get_vendor_prefix(): string {
        return self::get('vendor_prefix', 'Xophz ');
    }

    /**
     * Get the menu icon.
     *
     * @since    1.0.0
     * @return   string    The dashicons class or base64 encoded icon.
     */
    public static function get_menu_icon(): string {
        return self::get('menu_icon', 'dashicons-editor-customchar');
    }

    /**
     * Update the branding configuration.
     *
     * @since    1.0.0
     * @param    array    $config    The new branding configuration.
     * @return   bool                True on success, false on failure.
     */
    public static function update_config(array $config): bool {
        // Clear cache
        self::$cache = null;

        // Sanitize config
        $sanitized = self::sanitize_config($config);

        return update_option(self::OPTION_KEY, $sanitized);
    }

    /**
     * Delete the branding configuration entirely, reverting to defaults.
     *
     * @since    1.0.0
     * @return   bool    True on success, false on failure.
     */
    public static function delete_config(): bool {
        // Clear cache
        self::$cache = null;

        return delete_option(self::OPTION_KEY);
    }

    /**
     * Sanitize branding configuration values.
     *
     * @since    1.0.0
     * @access   private
     * @param    array    $config    Raw configuration.
     * @return   array               Sanitized configuration.
     */
    private static function sanitize_config(array $config): array {
        $sanitized = [];

        if (isset($config['platform_name'])) {
            $sanitized['platform_name'] = sanitize_text_field($config['platform_name']);
        }

        if (isset($config['vendor_prefix'])) {
            $sanitized['vendor_prefix'] = sanitize_text_field($config['vendor_prefix']);
        }

        if (isset($config['menu_title'])) {
            $sanitized['menu_title'] = sanitize_text_field($config['menu_title']);
        }

        if (isset($config['menu_icon'])) {
            $sanitized['menu_icon'] = sanitize_text_field($config['menu_icon']);
        }

        if (isset($config['plugins']) && is_array($config['plugins'])) {
            $sanitized['plugins'] = [];
            foreach ($config['plugins'] as $slug => $plugin_config) {
                $slug = sanitize_key($slug);
                $sanitized['plugins'][$slug] = [];
                
                if (isset($plugin_config['name'])) {
                    $sanitized['plugins'][$slug]['name'] = sanitize_text_field($plugin_config['name']);
                }
                if (isset($plugin_config['description'])) {
                    $sanitized['plugins'][$slug]['description'] = sanitize_textarea_field($plugin_config['description']);
                }
            }
        }

        return $sanitized;
    }

    /**
     * Check if the current environment has Wizard mode enabled.
     *
     * Wizard mode allows access to the branding configuration UI.
     * It's enabled by placing a file with the correct key in mu-plugins.
     *
     * @since    1.0.0
     * @return   bool    True if Wizard mode is active.
     */
    public static function is_wizard(): bool {
        if (!defined('COMPASS_WIZARD_KEY')) {
            return false;
        }

        return hash('sha256', COMPASS_WIZARD_KEY) === self::WIZARD_KEY_HASH;
    }

    /**
     * Clear the cached configuration.
     * Useful after updates or when testing.
     *
     * @since    1.0.0
     * @return   void
     */
    public static function clear_cache(): void {
        self::$cache = null;
    }
}
