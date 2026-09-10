# Changelog

All notable changes to this project will be documented in this file.

## [2026-09-09]

### Added
- Magic Hat Theme Registration: Registered Magic Hat in `Xophz_Compass_Modules_API::get_module_registry()` with 1/5/Unlimited and Annual/Lifetime pricing tiers.
- Pricing & Module Helpers: Added `find_module()` and `get_plugin_pricing()` to dynamically resolve module metadata and calculate tier pricing with Bedrock valuation fallbacks.
- License Verification API: Added `GET /xophz/v1/license/verify` to exchange Stripe checkout session IDs for client license keys.
- Return URL Security: Added `is_allowed_redirect_url()` and `get_allowed_redirect_hosts()` to `Xophz_Compass_Security` with ecosystem domain whitelisting.

### Changed
- Dynamic Buy Resolver: Connected `resolve_compass_buy_request()` to the `xophz_resolve_buy_request` filter in `class-xophz-compass.php` supporting `/buy/my-compass/{slug}` and `/buy/{slug}`.

## [2026-09-04]

### Fixed
- Menu Duplication: Added deduplication checks in `Xophz_Compass::add_submenu()` and `Xophz_Compass_Admin::sort_xophz_submenu_alphabetically()` to prevent child plugins and hooks from registering duplicate entries in the WordPress admin menu.

## [2026-05-01]

### Added
- Forminator Autofill System: Implemented `Xophz_Compass_Forminator_Autofill` to automatically retrieve a user's most recent submission and inject its historical data into a new Forminator form instance.
- Visual Notification: Added a glassmorphic/neon UI notification to inform users when a form has been automatically populated from previous data.

### Changed
- Registered `class-xophz-compass-forminator-autofill.php` within the core plugin loader logic (`class-xophz-compass.php`).
