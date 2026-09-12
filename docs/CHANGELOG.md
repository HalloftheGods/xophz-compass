# Changelog

All notable changes to this project will be documented in this file.

## [2026-09-12]

### Added
- Card Vault Tier Pricing Matrix: Configured Option C explicit pricing in `Xophz_Compass_Modules_API::get_module_registry()` for `xophz-compass-card-vault` (Personal: $99/yr or $249 Lifetime; Business: $249/yr or $599 Lifetime; Agency: $499/yr or $999 Lifetime).
- Compound Tier Route Resolution: Enhanced `resolve_compass_buy_request()` in `class-xophz-compass.php` to normalize single and team aliases as well as compound slug formats (`single-annual`, `team-annual`, `single-lifetime`, `team-lifetime`).

## [2026-09-10]

### Added
- Tiered Lifetime Discounts: Configured tiered lifetime deal discounts (Personal: 20% off, Business: 26% off, Agency: 30% off) modeled after OnePage Express pricing dynamics.
- Special Offer & Strikethrough Pricing: Added strikethrough original valuation pricing (`original-price`) and dynamic "Special Offer - X% OFF" badges (`price-offer-tag`) on checkout takeover cards.

### Changed
- Active Lifetime Pill Contrast: Updated `.switch-btn.active .lifetime-pill` to use solid black styling (`#05070a`) with cyan text (`#62c9ff`) to ensure high contrast against the active cyan toggle button.

## [2026-09-09]

### Added
- Ecosystem Module Registry Expansion: Registered 9 missing plugins in `Xophz_Compass_Modules_API::get_module_registry()` (`xophz-compass`, `card-vault`, `diego-lawfirm`, `glowitheflow`, `produce`, `yellow-links`, `kitchen-synk`, `thoth-reader-wp`, `bulletin-board`).
- Bedrock Checkout Takeover: Implemented `checkout-takeover-template.php` featuring smoky canvas wave physics, Annual vs Lifetime toggle, and 3-tier license selector when navigating to `/buy/my-compass/{slug}` without tier parameters.
- Enriched Modules REST API: Enhanced `GET /xophz/v1/modules` with live pricing matrix, market equivalent valuations, and repository metadata.
- Magic Hat Theme Registration: Registered Magic Hat in `Xophz_Compass_Modules_API::get_module_registry()` with 1/5/Unlimited and Annual/Lifetime pricing tiers.
- Pricing & Module Helpers: Added `find_module()` and `get_plugin_pricing()` to dynamically resolve module metadata and calculate tier pricing with Bedrock valuation fallbacks.
- License Verification API: Added `GET /xophz/v1/license/verify` to exchange Stripe checkout session IDs for client license keys.
- Return URL Security: Added `is_allowed_redirect_url()` and `get_allowed_redirect_hosts()` to `Xophz_Compass_Security` with ecosystem domain whitelisting.

### Changed
- Dynamic Buy Resolver: Connected `resolve_compass_buy_request()` to the `xophz_resolve_buy_request` filter in `class-xophz-compass.php` supporting `/buy/my-compass/{slug}` and `/buy/{slug}`, with automatic Bedrock takeover interception and test mode propagation.

## [2026-09-04]

### Fixed
- Menu Duplication: Added deduplication checks in `Xophz_Compass::add_submenu()` and `Xophz_Compass_Admin::sort_xophz_submenu_alphabetically()` to prevent child plugins and hooks from registering duplicate entries in the WordPress admin menu.

## [2026-05-01]

### Added
- Forminator Autofill System: Implemented `Xophz_Compass_Forminator_Autofill` to automatically retrieve a user's most recent submission and inject its historical data into a new Forminator form instance.
- Visual Notification: Added a glassmorphic/neon UI notification to inform users when a form has been automatically populated from previous data.

### Changed
- Registered `class-xophz-compass-forminator-autofill.php` within the core plugin loader logic (`class-xophz-compass.php`).
