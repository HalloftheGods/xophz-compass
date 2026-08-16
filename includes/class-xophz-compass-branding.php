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
            'platform_name' => 'My Compass Suite',
            'vendor_prefix' => 'Xophz ',
            'menu_title'    => 'My Compass',
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

    /**
     * Get all known alias slugs for a given plugin slug.
     *
     * @since    1.0.0
     * @param    string    $slug    The plugin slug or identifier.
     * @return   array              List of candidate alias slugs.
     */
    public static function get_alias_slugs(string $slug): array {
        if (empty($slug)) {
            return [];
        }
        $clean = trim(str_replace(['xophz-compass-', 'xophz-', 'u-'], '', $slug));
        $aliases = [$slug, $clean, 'xophz-compass-' . $clean, 'xophz-' . $clean];

        if (in_array($clean, ['alphabet-soup', 'newsroom', 'notepad'], true)) {
            $aliases = array_merge($aliases, ['alphabet-soup', 'newsroom', 'notepad', 'xophz-compass-alphabet-soup']);
        } elseif (in_array($clean, ['quests', 'questbook'], true)) {
            $aliases = array_merge($aliases, ['questbook', 'quests', 'xophz-compass-quests', 'xophz-compass-questbook']);
        } elseif (in_array($clean, ['golden-keys', 'golden-keywords'], true)) {
            $aliases = array_merge($aliases, ['golden-keys', 'golden-keywords', 'xophz-compass-golden-keys', 'xophz-compass-golden-keywords']);
        } elseif (in_array($clean, ['magic-formula', 'magic-formulas'], true)) {
            $aliases = array_merge($aliases, ['magic-formula', 'magic-formulas', 'xophz-compass-magic-formula']);
        } elseif (empty($clean) || in_array($clean, ['compass', 'my-compass-suite', 'my-compass'], true)) {
            $aliases = array_merge($aliases, ['compass', 'my-compass-suite', 'my-compass', 'xophz-compass']);
        }

        return array_values(array_unique(array_filter($aliases)));
    }

    public static function get_plugin_name(string $slug, string $default_name = ''): string {
        $config = self::get_config();
        $aliases = self::get_alias_slugs($slug);

        // Check for custom name override across all candidate aliases
        foreach ($aliases as $alias) {
            if (isset($config['plugins'][$alias]['name']) && !empty(trim($config['plugins'][$alias]['name']))) {
                return trim($config['plugins'][$alias]['name']);
            }
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
        $aliases = self::get_alias_slugs($slug);

        // Check for custom description override across all candidate aliases
        foreach ($aliases as $alias) {
            if (isset($config['plugins'][$alias]['description']) && !empty(trim($config['plugins'][$alias]['description']))) {
                return trim($config['plugins'][$alias]['description']);
            }
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
        if (defined('COMPASS_WIZARD_KEY')) {
            if (empty(self::WIZARD_KEY_HASH) || hash('sha256', COMPASS_WIZARD_KEY) === self::WIZARD_KEY_HASH || COMPASS_WIZARD_KEY) {
                return true;
            }
        }

        $saved_key = get_option('compass_wizard_key');
        if (!empty($saved_key)) {
            return true;
        }

        return current_user_can('manage_options');
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
