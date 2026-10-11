# Changelog

All notable changes to this project will be documented in this file.

## [2026-10-10]

### Security
- Pinata credentials no longer ship in `admin/dist`. A bare `import.meta.env` read in the shared logger (`src/core/sys-logger.ts`) made Vite inline every `VITE_*` value, including the Pinata API key, secret and JWT, into the admin bundle, and `useWeb3Gateways` read `VITE_PINATA_JWT` to call Pinata from the browser. The bundle is rebuilt without them and the root build now fails on any secret-named env value in its output (`scripts/vite-secret-guard.js`). The old values remain in this repository's git history: rotate them at Pinata.
- The `/xophz/v1/connectors` routes (read and write) and `POST /xophz/v1/site-settings` now require `manage_options`. The connectors list returns `configured` and a masked `hint` (last four characters) instead of stored values, and a blank write keeps the stored key (#5938).
- `POST /xophz/v1/gemini/generate` sends the Gemini key in the `x-goog-api-key` header instead of `?key=` in the URL, so the key no longer lands in provider, proxy or HTTP debug logs. The caller-supplied model name is cut to model-id characters before it becomes part of the URL path (#5978, M1).
- The VAPID private key never leaves the server. `Xophz_Compass_Push_API::generate_vapid_keys()` returns only `publicKey` (it used to return `privateKey` too) and `POST /xophz-compass/v1/push/generate-keys` returns only the public key. The pair is read and written through the `compass_vapid_public_key`, `compass_vapid_private_key` and `compass_vapid_subject` connectors, which now declare `COMPASS_VAPID_PUBLIC_KEY`, `COMPASS_VAPID_PRIVATE_KEY` and `COMPASS_VAPID_SUBJECT` as constant and env names (the constants the class read by hand before). When an env var or constant sets the private key, generate-keys answers `409 vapid_keys_pinned` instead of writing a pair that would never be used, and the public-key route only auto-generates when neither key is set (#5978, M5).
- `test-rest.php` at the plugin root booted WordPress and removed Bomb Bag from `active_plugins` for anyone who requested its URL. It now answers 404 and exits unless it runs from the PHP CLI. It is kept until the owner approves deleting it (#5978, M10).
- `GET /xophz/v1/site-settings` stays public but returns `admin_email`, `users_can_register` and `default_role` only to users with `manage_options`. Anonymous callers and other roles get the site identity, language, timezone and date/time formats only. The admin onboarding flow, the route's only reader, is unaffected (#5978).
- `POST /xophz/v1/connectors` writes only the setting names that registered connectors declare. Any other option name (for example `default_role` or `admin_email`) answers `400 xophz_connector_unknown_setting` with the rejected names, and nothing in that request is written (#5978).

### Fixed
- Fresh Mints is approved for the `google`, `openai` and `anthropic` AI connectors, so the AI plugin no longer refuses its Gemini calls with `wpai_connector_not_approved` (#5950).
- Discord login works again on sites that hold the Discord credentials only in environment variables. Event Horizon reads them only through connectors since #5954, and the `discord_client_id`, `discord_client_secret`, `google_oauth_client_id` and `google_oauth_client_secret` connectors declared no constant or env names. They now declare `DISCORD_CLIENT_ID`, `DISCORD_CLIENT_SECRET`, `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` for both (#5978).
- AI connector approvals now also cover Super Nerd Bros Dodo Air, Card Vault, Yellow Links, Alphabet Soup and the Magic Hat theme (the AI plugin keys a theme by its directory slug, `xophz-magic-hat`). The Thoth Reader entry uses the plugin's real basename, `xophz-thoth-reader-wp/xophz-thoth-reader.php` (#5978).

### Added
- `POST /xophz/v1/passport/pin` (`includes/class-xophz-compass-passport-pin-api.php`): pins a validated `GlobalPlayerProfile` to IPFS through Pinata from the server and returns `{ cid }`. The JWT is read through the `pinata_jwt` connector; with none set the route answers `503 compass_pinata_not_configured`. Logged-in users, 10 pins per hour each.
- `Xophz_Compass_Connector_Key::get( $connector_id )` (`includes/core/class-compass-connector-key.php`, autoloaded): reads a connector credential with WordPress core's precedence (env var, then constant, then option) using the names the connector declares.
- The `pinata_jwt` connector declares `PINATA_JWT` as its constant and environment variable names.

## [2026-09-21]

### Added
- Magic Hat Circadian Rhythm Tokens: Refactored `[compass_catalog]`, `[compass_suite]`, and `[compass_plugins]` shortcode styling to dynamically bind to Magic Hat 24-hour circadian theme tokens (`--mh-color-card`, `--mh-color-border-muted`, `--mh-color-text-main`, `--mh-color-brand-base`, `--mh-radius-*`, `--mh-glass-blur-*`), replacing hardcoded light-mode colors.
- Dynamic Catalog REST Endpoint: Added `GET /wp-json/xophz-compass/v1/catalog` to provide real-time discovery of catalog plugins, module registry metadata, version status, and local asset icon URLs.
- Client-Side Live Sync: Added background hydration to the catalog script to synchronize cards with active plugin states and versions without page refreshes.

### Changed
- Checkout Routing: Updated catalog and modal buy buttons to route fulfillment to sovereign `https://mycompassconsulting.com/buy/my-compass/{key}` Bedrock checkout takeover links, normalizing plugin keys to match module registry keys.
- Local Icon Resolution: Enhanced logo resolution to prioritize local plugin SVG and PNG icons before remote fallbacks.

## [2026-09-18]

### Added
- Showcase & Online Portal Links: Added `showcase_url`, `showcase_label`, and `saas_offer` metadata to `Xophz_Compass_Modules_API::get_module_registry()` for 9 core modules (`xophz-nook-phone`, `xophz-compass-xp`, `xophz-kitchen-synk`, `xophz-compass-glowitheflow`, `xophz-compass-yellow-links`, `xophz-compass-fresh-mints`, `xophz-compass-phone`, `xophz-compass-event-horizon`, and `xophz-compass-midnight-nerd`).
- Plugin URI Fallback: Enhanced `get_modules()` to fallback to installed plugin `PluginURI` header when `showcase_url` is unconfigured.

### Changed
- Plugin Description: Updated plugin description in `xophz-compass.php` and `composer.json` to highlight navigating both site depths and the dungeons and dark corners of the unknown realm of the internet.

## [2026-09-13]

### Added
- Dev Proxy Multi-Base & PWA Asset Serving: Enhanced `Xophz_Compass_Dev_Proxy` to intercept and serve `sw.js` with `Service-Worker-Allowed: /` header, web app manifests (`manifest.webmanifest`, `manifest.json`), and static assets (`icons/`, `favicon.*`) directly from candidate directories or Vite proxy with proper MIME types.
- Homepage Deployment Routing: Added support for `xophz_compass_{slug}_load_mode === 'homepage'` in rewrite rules and template redirect interception, dynamically handling root `/`, `/sw.js`, and `/manifest.webmanifest`.
- Universal Companion Plugins REST API: Implemented `Xophz_Compass_Plugins_Manager_REST` under `compass/v1/plugins` extending `Xophz_Compass_REST_Controller` to discover, inspect, toggle, and configure all companion plugins via REST.
- Universal Plugin Registration: Added `compass_registered_plugins` filter and automatic `rest_api_init` routing to `Xophz_Compass_Plugin_Base` allowing all child plugins to be managed programmatically by external tools and AI agents.
- Central Abilities Catalog: Added `GET /compass/v1/plugins/abilities` exposing the aggregate `compass_abilities_registry` for AI agent self-discovery.

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
