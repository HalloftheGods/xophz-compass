=== My Compass ===
Contributors: xopherdeep
Tags: dashboard, admin, ui, youmeos, starship, design system
Requires at least: 6.0
Tested up to: 6.5
Stable tag: 1.0.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A reimagined, future-thinking UI skin for the WordPress Admin.

== Description ==

Welcome to **My Compass**, the foundational layer of the **My Compass Suite**. Our project completely transforms the standard WordPress admin panel into a futuristic, Starship-inspired operating system environment.

Rather than settling for the legacy backend, My Compass acts as a next-generation UI skin and structural foundation. Built with Vue 3, Vuetify, and a custom "glassmorphic" design system, it provides a highly polished, single-page application (SPA) experience right inside WordPress.

While My Compass serves as a standalone UI upgrade, it is also the central hub designed to host the broader **YouMeOS** ecosystem and modular "Sparks" (specialized plugin apps).

### Features

- **Starship UI System**: A rich, dark-mode-first aesthetic with deep glassmorphic blur effects and neon cyan accents.
- **Component Library**: Includes custom `x-atom` web components designed for high-performance administrative interfaces.
- **Dynamic Routing**: Seamless SPA navigation within the WordPress backend.
- **Must-Use (MU) Architecture**: Employs a specialized MU plugin layer to ensure flawless integration between the YouMeOS UI and standard WordPress hooks.

### Open Source Foundation

My Compass is completely free and open-source, licensed under the GNU General Public License v3 (GPLv3). It represents our commitment to pushing the WordPress interface into the future.

_(Note: Advanced cloud features and proprietary intelligence APIs are handled separately by independent plugins and tiers across the My Compass Suite)._

== Installation ==

1. Upload the `xophz-compass` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress. (Upon activation, the core framework will deploy its companion MU plugin to ensure the UI/UX overrides work flawlessly).
3. Once activated, experience the transformed Starship interface when navigating your WP Admin.

== Frequently Asked Questions ==

= Why does it look entirely different from WordPress? =
My Compass is designed to replace the legacy WordPress UI with a modern, application-like experience. Our goal is to make managing your digital domain feel like piloting a starship.

= Does this break my other plugins? =
No. The My Compass UI operates inside its own isolated Vue application layer, heavily supported by our custom MU plugin to prevent conflicts. Standard WordPress pages remain unaffected unless specifically overridden.

== Screenshots ==

1. The main My Compass Dashboard loaded within the WordPress admin.

== Changelog ==

= 1.0.0 =

- Initial release of the My Compass architecture.
- Integrated Vue 3 and Vuetify 3 application mounting.
- Established the Starship aesthetic protocols (Dark Mode, Cyan Accents, Glassmorphism).
- Implemented the MU plugin bridge for flawless YouMeOS integration.
