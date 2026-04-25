import{f as H,c5 as y,c6 as x,d as I,cB as Q,M as K,G as $,e as A,x as J,V as z,c8 as _,c9 as h,m as C,l as c,n as B,F as L,c7 as U,Q as G,Z as q,ca as Z}from"./vendor-JOM5PyOK.js";import{a as j}from"./index-CZKsXyA3.js";import{I as F,r as ee,s as X,D as N,E as R,a as ne}from"./vendor-vuetify-CTVDwVZo.js";import"./vendor-core-C4-i1vzd.js";import"./vendor-three-DbgnMstn.js";import"./vendor-echarts-DHMG2Jr9.js";const te='# Changelog\n\nAll notable changes to the YouMeOS/COMPASS project will be documented in this file.\n\n## [2026-04-24]\n\n### Added\n- **Moving Castle Migration HUD**: Neon percentage display and time remaining counter centered between the source/target castles in the Overseer Migration Terminal.\n  - Large monospace neon percentage (e.g., `01%`) with color-reactive glow matching migration status (cyan, warning, error, success).\n  - Estimated time remaining counter using elapsed-time-to-progress-ratio extrapolation.\n  - Timer starts on import begin, stops on completion or error.\n- **Migration Analytics**: Google Analytics event tracking for cross-import comparison.\n  - `migration_started`: Fires with source URL, scope, dry run flag, and table count.\n  - `migration_completed`: Fires with duration, tables/rows migrated, scope, and dry run flag.\n  - `migration_failed`: Fires with duration, error message, and dry run flag.\n  - Added reusable exports to `useAnalytics.ts` following existing consent-aware pattern.\n\n### Changed\n- **Migration Terminal UX**: Bottom action button now shows green "COMPLETE" with check icon on successful migrations instead of generic "DISCONNECT".\n\n## [2026-04-23] (Late Night)\n\n### Changed\n- **Moving Castle UX Overhaul**: Complete architectural refactor from flat single-page to multi-view sub-app with proper COMPASS navigation drawer integration.\n  - Restructured `moving-castle.routes.ts` with 4 child routes (Subsites, Import, History, Settings) that auto-populate the `x-sub-app-layout` nav drawer.\n  - Extracted monolithic view into 4 dedicated view components: `SubsitesView`, `ImportView`, `HistoryView`, `SettingsView`.\n  - Shell controller now uses `provide/inject` to share migration API and terminal state across child views.\n  - **SubsitesView**: Hero section with floating icon animation, radial glow backdrop, neon door cards with icon rings and hover-lift effects, connection link dialog with copy-to-clipboard.\n  - **ImportView**: Guided 3-step wizard UI with step indicators, connection link input, and "Begin Migration" CTA.\n  - **SettingsView**: Token TTL slider, data scope checkboxes (users/options/uploads), and security toggles (SSL/single-use tokens).\n  - **HistoryView**: Placeholder audit trail with empty-state illustration.\n  - Premium SCSS: hero glow radials, float keyframes, cursor-blink terminal animation, neon card borders with per-color hover shadows, step-number active rings, and gradient text utilities for each section color.\n\n### Added\n- **Moving Castle REST API**: Site-to-site migration endpoints.\n  - `POST /connection`: Generates a 32-char transient token linked to a `blog_id` (1-hour TTL).\n  - `GET /schema?token=`: Returns `SHOW CREATE TABLE` statements for all tables matching the subsite prefix.\n  - `GET /data?token=&table=&page=`: Paginated row export (1000 rows/page) with prefix-based security validation.\n  - `POST /import`: Target-site endpoint that parses a connection URL, handshakes with source via `wp_remote_get`, and returns the discovered table manifest.\n  - `beforeunload` browser guard prevents accidental tab closure during active migrations.\n\n## [2026-04-23] (Evening)\n\n### Changed\n- **Holosweeper Polish**: Major visual upgrade to the Minesweeper spark.\n  - Added animated progress bar showing percentage of safe cells cleared (color-coded: cool → warm → hot).\n  - Cell reveal pop animation for satisfying cascade feedback.\n  - Flag pulse animation on placed flags.\n  - Refined cell hover states with cyan glassmorphic glow.\n  - Improved win/loss status badges with glow effects and best-time tracking.\n  - Simplified win condition to track revealed safe cells instead of requiring all flags placed.\n\n### Added\n- **Snake Rabbit AI**: The rabbit (food) now attempts to flee from the snake.\n  - Detects snake head proximity within 5 Manhattan-distance tiles.\n  - Rabbit turns warning-orange and shakes when scared.\n  - Up to 3 flee attempts per spawn with 60% trigger chance per tick.\n  - Flee direction scored by distance-from-head and center-pull to avoid corners.\n  - Smooth CSS transition on rabbit movement for visible "hop away" effect.\n  - Start screen updated with mechanic hint.\n\n## [2026-04-23]\n\n### Changed\n- **Glassmorphic Design Unification**: Standardized `.glass-panel` across COMPASS admin and YouMeOS runtime.\n  - Set canonical blur to `20px` and border to `rgba(255, 255, 255, 0.08)` in both `src/styles/core/_variables.scss` and `apps/youmeos/styles/_variables.scss`.\n  - Established a single global `.glass-panel` utility class in `src/styles/utilities/_utils.scss`.\n  - Removed 20+ redundant scoped `.glass-panel` definitions from Lead Magnet, Magic Formula, Questbook, Magic Cloak, Midnight Nerd, Gale Boomerang routes and Blackbox, Cookie Jar, Lemon Aid, Chronos, Local Produce, Tourguide sparks.\n  - Deprecated `x-glass-card` in favor of `<x-card class="glass-panel">`.\n\n### Added\n- **Questbook Comm-Link Core Enhancements**:\n  - **Global Shared Inbox**: Implemented `/inbox` REST API endpoint and `QuestbookInbox.vue` UI component to aggregate all recent incoming and outgoing communications across all contacts. Added real-time notification badges for unread inbound messages.\n  - **Template Manager**: Added a "Canned Responses" tab in `QuestbookSettings.vue` with full CRUD operations. Backed by `qb_communication_templates` via the `questbook/v1/settings` REST endpoints.\n  - **Unread State Management**: Injected `_qb_is_read` meta flags for all webhook and form submissions. Added `POST /questbook/v1/contacts/{id}/read` endpoint to clear unread states when an agent opens a profile.\n  - **Forminator Webform Integration**: Enhanced the existing WPMU DEV hook (`Xophz_Compass_Quests_WPMUDEV`) to parse inbound form submissions and inject them directly into the Comm-Link stream as `webform` logs.\n\n- **Solitaire Gamification**: Integrated `useXpStore` and `useSnackbar` into the Solitaire spark. Players are now rewarded with 520 GP and a notification toast when they win a game.\n\n## [2026-04-22]### Added\n- **Jukebox Station Expansion**: Added several new radio stations (Chillhop, Space Station, Synphaera, DEF CON Radio) and implemented a dynamic genre-based grouping system in the track selector menu for easier navigation.\n- **Questbook CRM × Forminator Integration**: End-to-end pipeline connecting form submissions to the CRM.\n  - **Submission Capture Hook**: `Xophz_Compass_Quests_WPMUDEV` hooks into `forminator_custom_form_submit_before_set_fields` to intercept entries with identity resolution (logged-in → link by `_qb_user_id`, logged-out → resolve by email).\n  - **Unverified Protocol**: Logged-out submissions matching existing contacts are quarantined as `_qb_unverified_entry` to prevent black-hat data injection. Verified on login via Claim & Confirm flow.\n  - **Schema Mapper UI** (`v-crm-sync.vue`): New "Questbook Sync" tab in the Magic Formula spark for per-form field mapping. Administrators map Forminator field IDs to CRM core fields (`_qb_raw_email`, `first_name`, `_qb_phone`).\n  - **Mappings REST API**: `GET/POST /wp-json/magic-formula/v1/mappings` for persisting mapping configurations as `questbook_form_mappings` wp_option.\n  - **Dynamic Field Resolution**: The CRM hook reads mappings at runtime, falling back to convention (`email-1`, `name-1`) when no explicit mapping exists.\n  - **Entry Retrieval Endpoints**: `GET /questbook/v1/contacts/{id}/entries` and `GET /questbook/v1/contacts/{id}/unverified` for fetching linked Forminator submissions.\n  - **Claim API**: `POST /questbook/v1/contacts/{id}/claim` with `approve`/`reject` actions to resolve unverified entries.\n- **Questbook CRM Documentation**: Created `docs/compass/plugins/Questbook-CRM-System.md` covering full data model, identity resolution strategy, REST API surface, and Forminator integration patterns.\n\n### Changed\n- **Magic Formula Documentation**: Updated `docs/compass/plugins/magic-formula-system.md` with AI Conjure endpoint, Questbook Sync UI, `/mappings` endpoints, and complete spark file structure.\n- **WP Admin Spark**: Implemented a minimal, clean spark interface for the WordPress Admin dashboard within YouMeOS.\n  - Used `x-iframe` and injected custom CSS (`wp-admin-iframe-patch`) to hide native WP layout components (admin menu, toolbar, etc.) and seamlessly blend the iframe into the YouMeOS ecosystem.\n  - Replicated the WordPress Admin sidebar navigation using `v-window` to allow drilling down through menu items (Dashboard, Posts, Media, Appearance, Settings, etc.).\n  - Enabled native next/back/refresh actions mapping directly to the `x-iframe` content window history.\n  - Registered the spark as "WP Admin" in `launcher.store.ts` (adminOnly: true) and `app/index.ts`.\n\n### Fixed\n- **Jukebox Audio Stream**: Replaced the deprecated/broken `streamafrica.net` Lofi Girl radio stream with a highly reliable `laut.fm` Lofi stream to restore the background radio functionality.\n\n## [2026-04-21]\n\n### Added\n- **Wizards Tower Spark**: New developer-only spark providing a feature flag management UI for controlling spark visibility across access tiers.\n  - **Feature Flag Registry** (`src/config/feature-flags.ts`): Central declaration of every spark\'s status (`stable`, `beta`, `alpha`, `disabled`). Supports localStorage overrides merged at module load.\n  - **Access Tier System** (`src/config/feature-access.ts`): Four-tier access model — Public, Beta Tester, Alpha Tester, Developer. Developer tier auto-enabled on localhost.\n  - **Build-Time Filtering**: Disabled features are excluded from the system registry and launcher store, enabling tree-shaking in production builds.\n  - **Launcher Integration**: `visibleApps` in `launcher.store.ts` now filters through `canAccess()` based on feature status and the resolved access tier.\n  - **System Registry Gating**: `app/index.ts` filters `systemRegistry` through feature flags, preventing disabled spark components from loading.\n  - **Wizards Tower UI**: Two-view spark (Feature Flags + Access Tier) with search, inline status dropdowns, quick toggle, and save-with-reload workflow.\n\n### Fixed\n- **Accessibility Options Consolidation**: Centralized all 5 accessibility CSS overrides into `src/styles/accessibility.scss`. Previously, "Accessible Colors" only worked when the Welcome spark was loaded (CSS was trapped in `:global()` inside `welcome-u-spark.vue`), and "Right Align Rails" only existed in `u-rail.vue`. All options now work globally regardless of which sparks are open.\n  - **Accessible Colors**: Moved gradient-text overrides and semantic color replacements to global stylesheet.\n  - **High Contrast**: Expanded coverage to include `u-rail`, `v-navigation-drawer`, `v-app-bar`, `v-bottom-navigation`, and `.opacity-60`.\n  - **Right Align Rails**: Centralized from component-scoped to global, covering both `.u-rail` and `.v-navigation-drawer--rail`.\n- **Xophz_Compass_Updater Resilience**: Implemented a "Stale-While-Revalidate" fallback mechanism for GitHub plugin updates. The system now stores the last known good release in the `wp_options` table, preventing updates from "disappearing" if the GitHub API hits a rate limit or returns a temporary error.\n- **Update Cache Recovery**: Reduced the error cache TTL from 1 hour to 5 minutes to ensure faster recovery from transient network issues.\n- **Force Refresh Logic**: Improved the reliability of the `?xophz_force_update=1` parameter to ensure a complete bypass of all caches when manually triggered.\n\n## [2026-04-18]\n\n### Added\n- **Omega Source Spark**: New identity spark serving as a public-facing virtual passport / business card. Three-tab layout: Passport (avatar, headline, pulse rating, stat bar, reflections), Growth (expert tags, credentials, skills, resources, references), Links (social URLs, wallet address). Registered in system registry and Launch Pad under the "Gateways" category.\n\n## [2026-04-17]\n\n### Added\n- **Sovereign Utility Documentation**: Added `youmeos_user_guides_swiss_army_knife.md` to directly answer the "What good does it do me?" question, framing YouMeOS as the ultimate "Swiss Army Knife for life" that enables true digital sovereignty.\n\n## [2026-04-16]\n\n### Added\n- **Dirt Digital Mineral Economy**: Documented the core tokenomic philosophy for YouMeOS. The base currency ("Dirt") is pegged to a global fiat/commodity average ("Earth Cent") to ensure stable, borderless micro-transactions. Established the gamified synthesis hierarchy (Dirt -> Quartz -> Gems) and the "System Reserve" protocol to prevent oligarchic wealth hoarding.\n- **Tokenomics Milestone**: Updated the Web3 Vision Roadmap with the new Dirt economy architecture.\n\n### Fixed\n- **Environment Configuration Sync**: Audited and synchronized `.env` and `.youmeos-env-keys.php` to ensure credential consistency.\n- **Pinata RO Key Cleanup**: Removed redundant/duplicate Pinata Read-Only API keys from `.env` that were incorrectly mirroring the master keys.\n- **Discord Key Standardization**: Updated `.youmeos-env-keys.php` to include `VITE_` prefixed Discord keys, maintaining compatibility with frontend environment expectations on production.\n### Changed\n- **Spark Rebranding**: Renamed the "Box Office" spark to "The w⁴ Protocol" across the system registry, launcher store, and window title. Updated the internal ID from `boxes-u` to `tesseract`, renamed the core component files, and styled it with a classic Dr. Who "TARDIS blue" (`#003b6f`) and `fad fa-police-box` icon.\n- **Identity Carousel Evolution**: Integrated the "w4 Protocol" as the bridge card between digital identity and physical sovereignty. Replaced the generic final identity card with a signature green (`rgb(61, 238, 152)`) w4 Protocol upsell that links directly to the Platform Hosting tab.\n- **BlackBOX Branding Refinement**: Standardized the BlackBOX card tier with a legacy-consistent slate gradient, custom bolding (bold "BOX"), and forced black icons/high-contrast ribbons.\n- **Atomic Color Improvements**: Refactored `u-box-card.vue` to dynamically respect the `color` prop for all header elements (icon, price, watermark) while maintaining high-contrast overrides for the black tier.\n- **Welcome Spark Mobile UX**: Refactored secondary tabs (Personalize UX, Gateway Navigation, etc.) to use a "Float-Right" icon layout (no box) with ultra-bold ("black") titles and structured paragraph descriptions for a premium, integrated feel.\n\n## [2026-04-15]\n\n### Added\n- **Plugin Versions REST Endpoint**: New `GET /xophz-compass/v1/versions` endpoint returns version info for all COMPASS suite plugins. Supports optional `?slug=event-horizon` filter for individual lookups. Uses WordPress\'s native `get_plugins()` under the hood.\n- **Version Injection**: Both COMPASS admin and Event Horizon public shell now inject `compassVersion` and `eventHorizonVersion` into `window.xophzCompassSettings`.\n\n### Changed\n- **Legacy Asset Migration**: Extracted the 22 textures, 6 skybox images, 4 lensflare images, and audio tracks the modern engine still needs from the `youmeos_legacy` submodule into a new `public/deprecated/` directory (43MB vs 75MB original). This directory ships with the plugin package, eliminating the submodule dependency on production. Created a slim `manifest.json` with only audio config (no legacy scripts/styles).\n\n### Fixed\n- **YouMeOS System Bar Version**: Corrected the version displayed in the Event Horizon system bar bottom-left. Was showing the monorepo `package.json` version (`26.4.15.1118`); now shows the actual PHP plugin version from `XOPHZ_COMPASS_EVENT_HORIZON_VERSION`.\n- **Terminal Boot Banner**: HoloShell terminal boot message now shows the correct Event Horizon version instead of the monorepo version.\n- **Plugin Updates**: Added `update_plugins_github.com` hook to `Xophz_Compass_Updater` to prevent WordPress 5.8+ from dynamically stripping `xophz-compass` plugins from the updates transient due to the presence of the `Update URI` header.\n\n## [2026-04-14]\n\n### Added\n- **Passport REST API**: New `class-xophz-compass-passport-api.php` with `GET/POST /xophz/v1/passport` (authenticated) and `GET /xophz/v1/passport/public/{user_id}` (public) endpoints. Stores profile CID, wallet address, ENS name, and MMO stats in `wp_usermeta`.\n- **Server-Side CID Persistence**: Published IPFS CIDs are now saved to both `localStorage` (fast) and `wp_usermeta` (permanent) via the Passport API.\n- **Silent Wallet Reconnect**: `reconnectWallet()` uses `eth_accounts` (no popup) to auto-restore MetaMask connection on page load without requiring user interaction.\n- **Inspect Backpack**: Added "Inspect Backpack" button to the IPFS card that opens the Local Produce spark via `portal:fly` to visualize localStorage contents.\n- **SubtleCrypto Guard**: ENS namehash functions now gracefully degrade when `crypto.subtle` is unavailable (HTTP dev or SES lockdown environments).\n\n### Changed\n- **YouMeOS Legacy Relocation**: Moved `youmeos_legacy` engine (75MB) to its own repository (`hallofthegods/youmeos-legacy`) and re-integrated it as a git submodule. This prevents the legacy engine from being included in the primary plugin bundle, reducing the bundle size significantly while still allowing for optional inclusion during development.\n- **Passport UI Reorder**: Cards now display Wallet → IPFS Backpack → ENS Lookup (moved ENS below IPFS).\n- **Profile Data Source**: `handlePublishProfile` now builds the IPFS profile from server-hydrated user data instead of hardcoded mock values.\n- **Nonce Pattern**: Unified REST API nonce usage via `getCompassNonce()` helper reading from `xophzCompassSettings.nonce`.\n\n## [2026-04-12]\n\n### Fixed\n- **Error Template Conversion**: Resolved a PHP Fatal error ("Object of class WP_Error could not be converted to string") in `error-template.php` by correctly parsing `WP_Error` objects using `is_wp_error()` and extracting the nested messages instead of blindly echoing them.\n\n## [2026-04-11]\n\n### Added\n- **Daily Final Versioning**: Implemented a new GitHub Actions workflow (`daily-final-version.yml`) that triggers at 11:59 PM every day. It generates a "final" version tag (e.g., `v26.4.11`) without the `rc` suffix and updates `package.json` accordingly.\n- **Automated Versioning**: Created a GitHub Action workflow to automatically update the version string in `package.json` and generate Git tags upon every push to the `main` branch. The format is `vYY.M.DrcTS`, where `TS` represents the total seconds elapsed in the day (UTC), ensuring unique and time-sorted tags.\n- **Welcome-U Spark Enhancement**: Added a "Personalize UX" section within the Welcome guide to simplify UI customization. Designed with a clear, card-based layout pointing users toward Appearance, Window Behavior, Sound & Audio, and Date & Time.\n\n### Changed\n- **Version Formatting**: Standardized version tags across all automated workflows to use non-leading-zero month/day formats (e.g., `v26.4.11` instead of `v26.04.11`). This aligns with semver best practices and the project\'s new release pattern.\n- **Spark Splash Background**: Removed the forced black background and radial gradient from the `TSparkSplash` blueprint. The component now defaults to transparency, allowing for better integration with 3D canvas backgrounds and glassmorphic designs across all sparks.\n- **Spark Splash Prop Cleanup**: Deprecated and removed the redundant `transparent` prop from `TSparkSplash` and cleaned up its usages in `NoosphereEmit` and `NexusNetworkCommand`.\n\n## [2026-04-09]\n\n### Added\n- **Classic WP Admin Overrides**: Expanded the Compass Admin CSS theme system (`compass-admin.css`) to completely take over classic WordPress UI elements. Added comprehensive targeting for `.wp-list-table`, `.notice`, `.button-primary`, tablenav components, legacy dashboard widgets (`#dashboard_activity`, `#dashboard_primary`), WPMUDEV Beehive widgets (`.beehive-metric-grid`, SUI tabs, `.sui-notification`), and enforced the `.color-gold` palette on all native links to consistently enforce the glassmorphic and dark aesthetic globally.\n- **Plugin Metadata Stacking**: Refactored the `plugin-reveal-circle` to stack version and author metadata vertically, improving layout clarity within the glassmorphic expand state.\n- **Smart Icon Resolver**: Implemented a dynamic icon resolution engine in `x-magic-toast`. It now intelligently maps plain strings (e.g., "bomb-bag", "compass") to their official plugin SVG icons, while maintaining fallback support for FontAwesome classes and direct image paths.\n- **Improved Hint Imagery**: Updated the global "explore-welcome" hint to use the official COMPASS brand icon via the new smart resolver.\n\n- **Software Manager**: Reimagined the "Software Suite" page as a high-fidelity software manager with a master-detail layout.\n- **Dynamic Plugin Data**: Integrated the software manager with `compassStore.pluginList`, displaying version, author, and description directly from active plugins.\n- **Teleport Animation**: Integrated the `x-sequence-icon` primitive into the Software Suite category menu, specifically adding the animated teleport sequence for the "Command Deck" category.\n- **Global Scrolling**: Refactored the COMPASS welcome screen (`compass.vue`) to support full-page scrolling, resolving navigation overlap issues across all tabs (Accelerate, Software Suite).\n\n### Changed\n- **Software Categorization**: Updated `COMPASS_CATEGORIES` in `launcher.store.ts` to map plugins to thematic categories (True North, Command Deck, Trajectory, Wizard\'s Tower, Castle Walls) based on the project\'s design language.\n- **Layout Architecture**: Migrated to a sticky sidebar navigation for the Software Manager to keep categories accessible while browsing long module lists.\n\n### Fixed\n- **Software Manager Routing**: Fixed bug where plugins with mismatched text domains and routes (e.g., quests vs. questbook) were incorrectly filtered out of the suite categories.\n\n## [2026-04-07]\n\n### Added\n- **Trenchess Spark**: Added a new Spark for the game "Trenchess", loading via an iframe to `trenchess.forthexp.com`. Integrated into the system app registry and available in the App Launcher under the Games category.\n- **YouMeOS Admin Bar Button**: Added a branded YouMeOS button to the WordPress admin bar, positioned before the WP logo. Features the galaxy icon with gradient-text label and responsive hiding on mobile.\n- **Logos Embed Mode**: When Compass is launched from the Logos spark, the WordPress admin menu, toolbar, and footer are hidden automatically so Compass fills the full iframe viewport. Triggered by `&embed=1` query param on the Compass URL.\n- **Digital Picnic Category**: Introduced a new thematic category in the App Launcher to bundle the "Colored Popcorn", "Beat Salad", "Alphabet Soup", and "Lemonade" Sparks.\n- **Authenticity & Licensing Infrastructure**: New settings module for managing Youniverse licenses.\n- **Licensing State**: Integrated `license` state into `CompassStore` with persistence via `localStorage`.\n- **Sovereign Tiers**: Defined `Agent`, `Sentinel`, and `Architect` tiers for user access management.\n- **AuthenticityView**: New glassmorphic UI module for license activation and status tracking.\n- **My Compass Rail Integration**: Dedicated "My Compass" entry on the main navigation rail, appearing dynamically based on license accessibility.\n\n### Changed\n- **Rebranding**: Renamed "Steward" tier to **"Agent"** across the entire ecosystem.\n- **Welcome Screen**: Updated `Welcome-U` spark to reflect the new Sovereign Tiers nomenclature.\n- **Navigation Rail Order**: Reprioritized "My Compass" as the primary navigation item, moving it to the top of the main rail above Nucleos.\n- **Bomb Bag Categorization**: Reclassified "Bomb Bag" from "Communication" to "Trajectory" in the App Launcher and Spark Registry.\n- **Trajectory Categorization**: Renamed "Targeting" category to "Trajectory" across the ecosystem plugins (Bazaar, Silver Arrow, Bomb Bag, Lead Magnet, Magic Formulass).\n- **Wizards Categorization**: Renamed "Wizards" category to "Wizard\'s Tower" and replaced the wizard hat icon with a stone tower (rook) icon.\n- **Enchiridion Categorization**: Reclassified "Enchiridion" from "Command Deck" to "Wizard\'s Tower" in the core plugin metadata.\n\n\n### Fixed\n- **Settings Stability**: Resolved runtime errors in `AuthenticityView` by implementing safety checks for store state access.\n- **Slot Implementation**: Optimized `SettingsApp` window navigation to prevent slot-invocation warnings.\n- **Colored Popcorn Performance**: Achieved zero-lag drawing by decoupling the high-frequency stroke logic from the Vue reactivity system. Optimized canvas context state updates for maximum fluidity.\n\n### Added\n- **Rainbow Mode**: Implemented a true hue-cycling "Rainbow" brush for the Rainbow Pi (Paint) app, including a neon-animated tool button.\n\n## [2026-04-06]\n\n### Changed\n\n- **Brand Color Standardization**: Integrated the "Hall of the Gods" brand gold (`#d9be6f`) across the YouMeOS ecosystem.\n  - Added `hog-gold` to the Vuetify theme configuration in `src/engine/vuetify.ts`.\n  - Defined `--color-hog-gold` CSS variable in `src/styles/core/_colors.scss`.\n  - Updated the **Enter Youniverse** icon color in `WelcomeLanding.vue` and `u-account-drawer.vue` to use the new brand gold.\n- **Helios Starburst Calibration**: Updated the Starburst (Sunburst) chart in `o-helios-sunburst.vue` with a new pastel palette.\n  - Derived 7 harmonious pastel shades from the brand logo gradient.\n  - Refined chart glow effects for a softer, integrated visual feel.\n\n## [2026-04-02]\n\n### Added\n\n- **Launch Pad Sidebar**: Extended the Launch Pad with a category sidebar using the `u-window` `#nav-content` slot.\n  - Added `AppCategory` type and `APP_CATEGORIES` constant to `launcher.store.ts`.\n  - Each `LauncherApp` now carries a `category` field (portal, utility, developer, communication).\n  - Store exposes `filteredApps`, `activeCategory`, and `setCategory` for reactive filtering.\n  - Default view shows all apps; selecting a category filters the grid.\n  - Sidebar uses the existing collapsible rail with pin/expand-on-hover behavior.\n\n### Changed\n\n- **Welcome Spark Icon**: Changed the welcome spark\'s icon to `rocket-launch` while keeping the "Welcome" tab internal icon as `hand-spock`.\n  - Updated the main `u-window` icon to `fal fa-rocket-launch`.\n  - Reverted the "Welcome" navigation tab icon to `fad fa-hand-spock`.\n\n## [2026-04-01]\n\n### Changed\n\n- **Desktop Shortcuts Transition**: Transformed the primary dashboard and welcome overlay action panels from oversized glass cards into sleek, OS-native desktop shortcuts (`u-shortcut.vue`).\n  - Added new OS-style shortcut primitive with subtle hover blur, scaling animations, and refined text-shadow typography.\n  - Replaced `u-glass-card` elements in `WelcomeLanding.vue` and `PortalDashboard.vue` with native-feeling application icons.\n  - Simplified the grid layout to a fluid flex row, mimicking a true desktop environment.\n\n### Added\n\n- **Spark Core Navigation**: Added a cinematic camera "fly to" event when clicking the core dashboard tabs inside the specific sparks:\n  - Clicking "My Center" in the Helios spark flies the camera to the sun.\n  - Clicking "My Nexus" in the Nexos spark flies the camera to the Nexos sphere.\n  - Clicking "The Observer" in the Noosphere spark flies the camera to the Noosphere galaxy.\n\n### Fixed\n\n- **YouMeOS Window Borders & Spacing**: Removed the dashed/dotted border and outline from windows when maximized.\n  - Added `border: none !important` and `outline: none !important` to `.u-window-wrapper--maximized`.\n  - Specifically disabled the library\'s (`vue3-draggable-resizable`) default active outline using `::before` and `::after` pseudo-elements.\n  - Ensured internal `.u-window` also sheds its border in maximized state for a seamless full-screen experience.\n  - **Bottom Bar Layout Fix**: Converted the system bar (`u-system-bar`) from a `v-app-bar` to a fixed `v-sheet`. This removes it from Vuetify\'s layout grid, ensuring it no longer reserves space at the bottom and allowing maximized windows to utilize the full viewport height.\n  - Increased system bar `z-index` to `9999` for consistent accessibility over windows.\n\n## [2026-03-31]\n\n### Added\n\n- **YouMeOS Portal Page Configuration**: Added a WordPress Settings page (Settings → YouMeOS) to configure where YouMeOS loads on the front-end.\n  - **Routes Only** (default): YouMeOS only loads on `/youmeos/` and `/os/` as before.\n  - **Homepage**: Replaces the site\'s front page with the full YouMeOS portal.\n  - **Specific Page**: Load YouMeOS on any WordPress page via a dropdown selector.\n  - Settings stored via WP Options API (`youmeos_load_mode`, `youmeos_load_page_id`).\n  - Rewrite rules auto-flush when settings change.\n  - Refactored `class-xophz-compass-event-horizon-public.php` into clean named boolean methods (`is_configured_page`, `resolve_app_base`, `render_youmeos_shell`).\n\n## [2026-03-30]\n\n### Fixed\n\n- **YouMeOS Star Rendering Performance**: Converted all WebGPU particle generators from InstancedMesh billboard quads back to THREE.Points, eliminating ~720k unnecessary vertices per frame.\n  - `milky-way.ts`: 40k quads → 40k points (160k → 40k vertices)\n  - `background-stars.ts`: 119k quads → 119k points (478k → 119k vertices, biggest win)\n  - `starfield.ts`: 5k quads → 5k points (20k → 5k vertices)\n  - `interstellar-dust.ts`: 10k quads → 10k points (40k → 10k vertices)\n  - `interstellar-gas.ts`: 5k quads → 2k points (20k → 2k vertices, matched WebGL gas structure)\n  - Added early `.visible = false` cutoff at opacity ≤ 0.01 across all generators to prevent transparent geometry from consuming GPU cycles.\n  - Restored proper spectral color lookup in Hipparcos stars that was lost during the InstancedMesh migration.\n\n### Added\n\n- **Multi-Tab Audio Deduplication**: Implemented `useTabLeader` composable using BroadcastChannel API + localStorage heartbeat to elect a single "leader" tab across all open YouMeOS windows. Only the leader tab plays audio, preventing duplicate sound when multiple tabs are open.\n  - Leader election with automatic failover when the leader tab closes or becomes stale (5s threshold).\n  - Integrated into `useAudio` — gates `unlockAudio`, `togglePlay`, and auto-next-track behind `isLeader`.\n  - Exposes `isLeader` ref from `useAudio` for downstream consumption.\n\n### Fixed\n\n- **YouMeOS Framerate Drops**: Resolved a severe engine performance issue where `setupSkybox` was inadvertently called twice during `engine.init()`. This spawned double the post-processing geometries, causing massive overdraw and slashing the framerate.\n- **Sun Render Flickering (Texture Ping-Ponging)**: Addressed an insidious strobe-like flashing across the entire solar surface caused by `fract` wrap-around clamping. Switched the U-coordinate color mapping from a rigid `clamp(fract())` logic (which abruptly jumped values at the boundary edge) to a continuous `mix` triangle wave (ping-pong animation). This ensures the sun boils and shifts color completely seamlessly without hard jump cuts.\n- **Sun Render Flickering (Depth Pre-Pass)**: Eliminated overlapping precision Z-fighting artifacts on the Sun by explicitly assigning `depthTest = false` to all additive atmospheric layers (Flares, Halo, Corona). They now reliably render pure bloom over the solar body regardless of the billboard\'s depth collision with the 3D sphere.\n- **Solar Flare Polygons**: Optimized the `TorusGeometry` for the solar flares (reduced from 60x90 segments to 30x45) and reduced the cluster count from 6 to 4 flares per star, massively reducing vertex overhead without sacrificing the visual depth of the additive blend.\n\n\n### Fixed\n\n- **YouMeOS Guided Tour**: Restored the functionality of the "Start Tour" button and the touring mechanics to map accurately to the legacy jQuery engine version.\n  - **Camera Tween Safety**: Fixed `controls.flyTo` auto-unlocking `tourMode` upon completion. The camera is now safely locked during the entire duration of the `restTime` interval pauses.\n  - **Pan Persistence Bug**: Hardcoded `{ panX: 0, panY: 0 }` into the very first "AWAKENING" sequence to ensure the tour doesn\'t start off-center if the user previously panned around the galaxy.\n  - **UI Escape Hatch**: Restored the clickable "Skip Tour" button to the modern theater DOM. This binds directly to `createTheater()` and allows users to safely bypass the tour, killing all `window.TWEEN` tasks and restoring standard interactive controls.\n\n## [2026-03-11]\n\n### Added\n\n- **Bomb Bag News Drip System**: Complete buildout of the email marketing and automated drip sequence engine.\n  - **Drip Sequences**: New DB tables (`bomb_bag_drip_sequences`, `bomb_bag_drip_steps`, `bomb_bag_drip_enrollments`), full REST API with CRUD, step reordering, enrollment management, and per-sequence analytics.\n  - **Email Template Library**: New `bomb_bag_templates` table with REST API for template CRUD. Seeds 3 default templates (Modern Newsletter, Welcome Series, Minimal Text) on activation.\n  - **Campaign Scheduling**: Wired up the existing `scheduled_at` column with a 5-minute WP-Cron job. Campaigns can now be scheduled for future delivery via the REST API.\n  - **Email Provider Implementations**: New `class-bomb-bag-email-providers.php` with actual SendGrid (HTTP API), Mailgun (HTTP API), and Custom SMTP (PHPMailer hook) support. All sending paths now route through this centralized provider class.\n  - **Drip Builder UI**: New Vue timeline-based sequence builder (`bomb-bag-drip-builder.vue`) with step delay configuration, subject/content editing, activate/pause controls.\n  - **Drip List UI**: New Vue view (`bomb-bag-drips.vue`) displaying sequence cards with status, enrollment stats, and trigger type.\n  - **Template Library UI**: New Vue view (`bomb-bag-templates.vue`) with category filtering, scaled HTML preview thumbnails, create/edit/duplicate/delete dialogs, and full-screen preview.\n  - **TypeScript Types**: Comprehensive typed interfaces for all new entities (`DripSequence`, `DripStep`, `DripEnrollment`, `DripAnalytics`, `EmailTemplate`, etc.) with discriminated union types for statuses and triggers.\n  - **Store Expansion**: Pinia store extended with 15+ new actions for drip and template management.\n\n### Fixed\n\n- **WordPress Translation Notice (WP 6.7)**: Resolved "Translation loading for the woocommerce domain was triggered too early" notices and potential "headers already sent" warnings on WordPress 6.7.0.\n  - **Bazaar Reports Optimization**: Delayed WooCommerce report class inclusions (`WC_Admin_Report`, etc.) within `Xophz_Compass_Bazaar_Admin_Reports` until they are actually required by AJAX handlers.\n  - **Submenu Registration Refactor**: Updated `Xophz_Compass::add_submenu` to use `get_plugin_data()` for specific plugin files instead of `get_plugins()`, avoiding the expensive and problematic scanning of all plugin headers during menu registration.\n  - **Delayed Execution**: Ensured all menu registration and plugin header parsing is performed within the `admin_menu` action hook (priority 11), well after the WordPress `init` cycle.\n\n### Changed\n\n- **Route Name Safety**: Prefixed all Bomb Bag route names with "Bomb Bag" (e.g., `Dashboard` → `Bomb Bag Dashboard`) to prevent collision with other plugins. Extracted to typed `BOMB_BAG_ROUTE_NAMES` constant.\n- **Email Sending Centralization**: Replaced all raw `wp_mail()` calls in campaign batch processing and test emails with the new `Email_Providers::send()` static method.\n- **Dashboard Stats**: Added `active_drips` count to the stats response.\n- **Documentation**: Comprehensive rewrite of `Bomb-Bag-System.md` covering all 10 DB tables, REST API surface, PHP class map, and TypeScript type catalog.\n\n## [2026-03-11]\n\n### Added\n\n- **Bug Net Core Engine**: Built the foundational custom post type (`compass_bug`) for the Bug Net plugin to track system errors.\n  - Generates a "Bug Net" interface in the WordPress admin panel with a custom bug icon.\n  - Implements a meta box for "Bug Details" including `bug_status` (New, In Progress, Resolved, Closed), `bug_priority` (Low, Medium, High, Critical), and `bug_environment`.\n  - Exposes the bug data via the WordPress REST API for front-end Vue.js integration.\n\n### Changed\n\n- **Enchiridion UI/UX Reimagination**: Complete visual overhaul of the Enchiridion (Recipe Book) plugin across all three primary views.\n  - **Dashboard**: Migrated to x-atoms (`x-glass-card`, `x-btn`, `x-avatar`, `x-icon`, `x-chip`). Stat cards now feature HUD monospace values with Arcane Violet (`#7C4DFF`) neon glow, oversized ghost icons, and `arcane-glow` hover effects. Category overview cards display split active/dormant chip counts.\n  - **Recipes**: Recipe cards use `x-glass-card` with `pulse-active` neon ring animation when enabled. Category headers feature tonal avatars with gradient divider lines. Switch labels replaced with HUD-styled `x-chip` status badges.\n  - **Custom Code**: Code editor panels now have themed gradient header bars (blue for CSS, amber for JS) with language icons. Save button features neon glow on enabled state. Timestamp uses monospace violet styling with green dot indicator.\n  - **SCSS Foundation**: New `_enchiridion.scss` with `.hud-value`, `.hud-violet`, `.arcane-glow`, `.pulse-active`, `.code-panel-header`, `.save-btn-glow`, `.category-divider`, and `.stat-icon-bg` utility classes.\n\n- **Magic Boomerang UI/UX**: Completely overhauled the Gale Boomerang dashboard to align with the Project Compass high-tech Starship aesthetics.\n  - Upgraded standard Vuetify cards to `x-glass-card` for a deep, premium glassmorphism layout.\n  - Implemented `.hud-value` and `.hud-cyan` SCSS utility classes to format critical data readouts (traffic, server load, visitors) with technical monospace fonts and neon glows.\n\n## [2026-03-09]\n\n### Added\n\n- **Plugin Documentation**: Defined the functional separation between the Magic Mirror and Moving Castle plugins.\n  - Created `docs/plugins/Magic-Mirror-System.md` focusing on SEO/performance competitor benchmarking ("Fairest of them all").\n  - Renamed and updated `docs/plugins/Moving-Castle-System.md` to focus on WordPress multisite management, environments, staging escapes, and on-the-fly theme switching.\n  \n### Fixed\n\n- **Sass Deprecation Migration**: Migrated legacy `@import` rules to the modern `@use` syntax in `u-window.vue` and `u-window-shell.vue` to resolve Dart Sass 3.0.0 deprecation warnings. Verified that all custom SCSS and Vue files in `src/` and `apps/` now use modern Sass module syntax.\n\n## [2026-02-06]\n\n\n### Changed\n\n- **Bootstrap Cleanup**: Comprehensively removed legacy Bootstrap variables, mixins, and utilities from the codebase.\n  - **Variables**: Gutted `src/styles/core/_variables.scss`, retaining only essential color palette, glassmorphism tokens, and layout basics. Removed unused maps (`$theme-colors`, `$font-sizes`) and component-specific variables.\n  - **Mixins**: Cleaned `src/styles/core/_mixins.scss`, removing 10+ legacy helpers (badges, buttons, variants) while preserving critical `glass` and device breakpoints.\n  - **Utilities**: Refactored `src/styles/utilities/_utils.scss` to remove 600+ lines of duplicate utility classes, keeping only `thin-scroll`, `glass` helpers, and standard Font Awesome fixes.\n  - **Colors**: Decoupled `src/styles/core/_colors.scss` from the now-deleted Bootstrap `$theme-colors` map.\n  - **Regression Fix**: Restored critical layout variables (`$toolbar-height`, `$sm-toolbar-height`, `$breadcrumb-height`) that were inadvertently removed during cleanup, ensuring `app-shell` and `routes-navigation-drawer` compile correctly.\n\n## [2026-02-05]\n\n### Changed\n\n- **WordPress Style Isolation**: Implemented Enhanced Scoping strategy to eliminate conflicts between WordPress admin styles and Vuetify components.\n  - **Consolidated Form Resets**: Created unified `src/styles/vendor/_wp-form-reset.scss` to eliminate duplicate code previously scattered across `_reset.scss` and `_wordpress.scss`. All form element resets now live in a single, well-documented source.\n  - **Aggressive PHP Dequeuing**: Reduced WordPress admin stylesheets to the bare minimum (`dashicons`, `admin-bar`, `button-color`). Deregistered problematic core styles (`wp-admin`, `admin-menu`, `colors`, `forms`, `common`) that were applying white backgrounds, borders, and padding to form inputs, preventing Vuetify\'s dark theme from displaying correctly.\n  - **Optimized Load Order**: Reorganized `app.scss` to ensure WordPress vendor styles load first (lowest priority) and COMPASS utilities load last (highest priority), guaranteeing proper style precedence through CSS cascade source order.\n  - **Result**: Text fields, buttons, selects, dialogs, and all Vuetify form components now render with proper glassmorphic dark styling instead of WordPress\'s white-background admin styles bleeding through.\n\n## [2026-02-04]\n\n\n### Changed\n\n- **Post Digger Table**: Refined the "Quick Edit" action in the posts table.\n  - Converted the "Quick Edit" text button to an icon button (`fas fa-bolt`) for a cleaner UI.\n  - Changed the behavior to toggle the quick edit row visibility (expand/collapse) instead of only opening it.\n  - Added visual feedback (active state color) to the quick edit button.\n\n## [2026-02-03]\n\n### Changed\n\n- **Compass Admin Styles**: Hidden standard WordPress notices (e.g., `.notice`, `.notice-warning`, `.update-nag`, `.inline`) specifically on the Compass dashboard page (`.toplevel_page_xophz-compass`) to provide a cleaner, app-like experience.\n\n## [2026-01-30]\n\n### Changed\n\n- **Global Glass Styling**: Updated the `u-window` component to use the system-wide transparent "glass" aesthetic (`42,42,42` base).\n  - Wired `u-window` SCSS to use `--glass-bg`, `--glass-blur`, and `--glass-border` CSS variables.\n  - Ensured the window style aligns with the "Answer to the Universe" design philosophy.\n- **Settings App**: Updated the Settings application (`settings-app.vue`) to use `x-list` and `x-list-item` primitives within its navigation rail, ensuring visual consistency with the glass theme.\n  - Also fixed relative import paths in the system registry `index.ts`.\n- **X-Switch Compatibility**: Resolved a Vue warning and `IndexSizeError` caused by an invalid `size="large"` prop on `x-switch` in `SoundView.vue` and `AppearanceView.vue`. Vuetify\'s `v-switch` does not support the `size` prop, which was being passed down to the underlying HTML `<input>`, causing browser-level validation errors.\n\n### Added\n\n- **Portal Exit Button**: Added a dedicated "Exit Portal" button to the `UPortalAppBar` component.\n  - Linked to a new `exitPortal` function that clears portal navigation state and returns the user to the absolute application root (`/welcome`).\n  - Updated the `goBack` function to also fallback to the app root if history is empty.\n  - Utilizes the `fad fa-portal-exit` icon for clear visual intent.\n  - Refactored `UPortalAppBar` to use the native Vuetify `append` slot, simplifying the template.\n- **Spark Library Improvements**: Refined the Library Explore view.\n  - Renamed "Browse Apps" to "Browse Sparks" to align with project nomenclature.\n  - Enforced `min-height: 100%` on the explore view to resolve layout collapse issues.\n\n## [2026-01-29]\n\n### Fixed\n\n- **System Bar Positioning**: Resolved an issue where the bottom system bar was not positioned at the very bottom of the screen.\n  - Added explicit `order="10"` to `u-system-bar` to ensure it renders outside of the `u-portal-navigation` in the layout stack.\n- **Window Stacking & Z-Index Orchestration**: Resolved an issue where selecting a window from the User Rail would not consistently bring it to the foreground.\n  - Implemented explicit `z-index` binding in `u-window.vue` to override third-party component internal state.\n  - Increased the base `highestZIndex` in `window.store.ts` to 500 to ensure windows always stack above secondary UI elements while remaining beneath primary system bars and drawers.\n  - Synchronized the `focusWindow` logic across the User Rail and Webtop environment.\n- **Enhanced Window Interaction**: Added standard OS behavior to the `u-window-bar` and User Rail.\n  - **Rail Toggle**: Clicking an active window\'s icon in the User Rail now correctly toggles its minimization state (Minimize if active, Restore/Focus if inactive).\n  - Double-clicking the window title bar now toggles between Maximized and Restored states.\n  - **Bug Fix**: Restriced window dragging strictly to the title bar. Interacting with internal window content (sliders, buttons, etc.) no longer triggers a global window drag by implementing event propagation hijacking on the window shell.\n  - **Premium Resizing**: Eliminated the visible "white box" resize marks. The window is now resizable from any edge or corner via large, invisible hit areas, providing a seamless and native desktop interaction feel.\n\n### Changed\n\n- **Nexos Navigation Transmutation**: Refined the navigation items for the Nexos (Social) layer to align with the project\'s alchemical and celestial aesthetic.\n  - "Messages" → **Resonance** (Focus on social sync and pulses).\n  - "Relations" → **Constellations** (Mapping connections between sovereign suns).\n  - "Subspace Comms" → **Aether Channels** (Tracking transmissions across the void).\n  - "Arcade" → **The Forge** (Transmuting reality through simulation).\n- **Noosphere Navigation Transmutation**: Refracted the highest-level view with a balance of familiar and evocative terms.\n  - Introduced **Akashic Archive** to represent the collective intelligence.\n  - Retained **Thought Stream** and **Mind Map** for welcoming intuition.\n  - Standardized on **Galactic Forums** and **Universal Net** to reflect the orbital scale.\n- **Portal Child Routes**: Implemented a nested routing architecture for Nexos and Noosphere portals, mirroring the Helios pattern.\n  - Created placeholder components for all transmuted navigation items.\n  - Updated `NexusView` and `NoosphereView` to use `<router-view>` for seamless nested transitions.\n  - Federated navigation state through controllers and router metadata.\n- **Reactive Billboard System**: Decoupled Portal Identity from the Billboard Context in `UPortalBlueprint`.\n  - The **Portal Identity** in the Drawer now acts as a stable anchor and link back to the portal dashboard.\n  - The **Billboard Header** refracts fully based on the active route, updating its `title`, `description`, `icon`, and `iconColor`.\n  - Icon spinning is now intelligently gated: active for the portal soul (dashboard), stationary for specific tasks (subroutes).\n  - Child routes inherit portal branding unless explicitly overridden via `meta.billboard`.\n- **Helios Placeholder Expansion**: Completed the navigation architecture for the Helios (Personal) layer.\n  - Created placeholder components for **Chronicles**, **Chronos**, **Pictures**, **Videos**, **Music**, and **Solar Profile**.\n  - Migrated and transmuted **Stellar Settings** to **Stellar Profile**, relocating it from Helios to the **Nexos** (Network) layer to reflect network-wide resonance calibration.\n  - Manifested the **Youniversal Profile** in the **Noosphere** layer, completing the trinity of Sovereign identity levels with the `fal fa-sunglasses` icon.\n  - Synchronized `helios.controller.ts`, `nexus.controller.ts`, `noosphere.controller.ts`, and `router.ts` with transmuted names and celestial iconography.\n\n## [2026-01-28]\n\n### Changed\n\n- **Route Transitions**: Simplified the global `fade` transition by removing scale and vertical translation. This resolves the jittery "content picking up" effect during page navigation, replacing it with a clean, cinematic opacity fade.\n\n### Fixed\n\n- **Lit-Lamp Logs**: Resolved a TypeScript error where the log `item` was untyped (`unknown`) in the virtual scroll template.\n  - Defined explicit `LogEntry` and `LogData` interfaces.\n  - Refactored `lit-lamp-logs.vue` to use COMPASS X-Primitive components (`x-btn`, `x-card`, `x-chip`, `x-list-item`, `x-text-field`, `x-select`, `x-icon`).\n  - Improved type safety for the search debounce timer.\n- **Routes Navigation Drawer**: Fixed a Vue template compilation error caused by a redundant `</v-list>` tag.\n\n## [2026-01-28] (Continued)\n\n### Changed\n\n- **Portal Toolbar Centralization**: Refactored individual portal toolbars into a centralized `v-app-bar` within the main desktop layout.\n  - Introduced `UPortalAppBar` construct to render portal-specific titles and actions.\n  - Enhanced `usePortalNavigation` composable to support data passing for app bar components and props.\n  - Standardized layout `order` for drawers and bars: `u-user-rail` (0), `u-portal-app-bar` (1), `u-portal-drawer` (2).\n  - Cleaned up `u-portal-blueprint.vue` by removing local toolbar logic, significantly reducing template complexity.\n\n### Fixed\n\n- **U-Window Geometry Sync**: Resolved "ghosting" artifacts during dragging and resizing by switching to `v-model` binding for real-time geometry updates.\n  - Implemented writable computed properties in `u-window.vue` for seamless store synchronization.\n  - Added `:parent="true"` constraint and `:disable-user-select="true"` for improved interaction feel.\n- **Component Reactivity Warnings**: Resolved Vue performance warnings by wrapping dynamically injected components in `markRaw`.\n  - Updated `usePortalNavigation.ts` to protect `component` and `actions` definitions.\n  - Updated `window.store.ts` to protect window-hosted application components.\n\n## [2026-01-20]\n\n### Added\n\n- **U-Portal-Blueprint**: Introduced a standardized page blueprint for YouMeOS portal views (Helios, Nexus, Noosphere).\n  - Centralizes the glassmorphic "Billboard" header, content grid structure, and transition logic.\n  - Integrates `usePageContentVisibility` for unified HUD toggling behavior.\n  - Refactored `HeliosView`, `NexusView`, and `NoosphereView` to use the new blueprint, reducing code duplication and ensuring visual consistency.\n\n### Changed\n\n- **System Bar Home Branding**: Added a logo button to the start of the system bar that provides quick navigation back to the main YouMeOS dashboard.\n  - Features smooth hover scaling and glassmorphism styling.\n  - Linked to the root `/u` (YouMeOS) route.\n\n### Changed\n\n- **System Bar Refactor**: Refactored the `USystemBar` component to use the `x-btn` primitive instead of raw `v-btn` components, aligning with project design system standards.\n\n### Fixed\n\n- **WebGPU Nexus Material**: Fixed a `TypeError` in the WebGPU star generator caused by a missing `MeshBasicNodeMaterial` constructor. Migrated the implementation to correctly import from `three/webgpu` and implemented proper TSL projection logic for billboarding.\n\n## [2026-01-18]\n\n### Added\n\n- **YouMeOS Visionary Manifesto (README.md)**: Completely rewrote the Event Horizon plugin README as a comprehensive philosophical and technical document:\n  - Defined the core philosophy of YouMeOS: Ancient-Futuristic, Individual Sovereignty, Collective Unity, and Global Balance.\n  - Documented the full Celestial Hierarchy (Youniverse, Star, Helios, Nexos, Noosphere, Oort Cloud, Satellites).\n  - Articulated the vision of the "Operational Internet", a paradigm shift from passive browsing to spatial inhabitation.\n  - Projected YouMeOS\'s potential 50 years (2076) and 100 years (2126) into the future, positioning its creators as "Pioneers of the Operational Internet."\n  - Integrated the dedication to Xopher\'s children and aligned all messaging with the project\'s philosophical core.\n- **Dual-Renderer Architecture**: Implemented foundation for WebGL/WebGPU user toggle:\n  - Created `renderer.factory.ts` — Abstracts renderer creation with WebGL2 (stable) and WebGPU (experimental) modes.\n  - Added `getRendererPreference()` / `setRendererPreference()` for persisting user choice in localStorage.\n  - Added `isWebGPUAvailable()` for runtime capability detection.\n  - Updated `YouMeOSEngine` to use the factory pattern; renderer is now created in `init()`.\n  - Exposed `rendererType` property on engine for UI status display.\n  - Created comprehensive migration plan: `/docs/WebGPU-Migration-Plan.md`.\n  - **Blockers identified**: Custom GLSL shaders and `Lensflare` addon need TSL migration.\n  - Confirmed Three.js version at r182 (latest) with modern GLSL ES 3.00 syntax.\n\n### Added\n\n- **WebGPU Support**: Activated the actual `WebGPURenderer` class from `three/webgpu`.\n- **TSL Shader Migration**: Successfully migrated the following shaders to Three.js Shading Language (TSL):\n  - Galactic Stars (Galaxy & Gas systems)\n  - Data Stars (Hipparcos star field)\n  - Sun Materials (Surface, Flare, Halo, Corona)\n- **Material Factory**: Implemented `material.factory.ts` to abstract material creation, enabling seamless toggling between WebGL2 (GLSL) and WebGPU (TSL).\n\n### Changed\n\n- Refactored `galaxy.ts`, `gas.ts`, `hipparcos.ts`, and `sun.ts` to use the new Material Factory.\n- Updated `renderer.factory.ts` to instantiate and initialize the `WebGPURenderer`.\n- Enhanced `youmeos.engine.ts` to support asynchronous renderer initialization.\n\n### Fixed\n\n- Resolved `Uncaught TypeError` related to `WebGLProgram` when selecting WebGPU mode by ensuring compatible materials are used.\n- Fixed TypeScript lint errors in TSL shaders and engine initialization.\n- Temporarily disabled `Lensflare` in WebGPU mode to prevent renderer-specific addon crashes.\n- **Generator Decoupling**: Implemented the Generator Factory pattern to fully decouple WebGL and WebGPU implementations:\n  - Created `generator.factory.ts` as the central router for scene object creation.\n  - Migrated `Dust`, `Gas`, and `Galaxy` generators to separate `webgl/` (GLSL/Points) and `webgpu/` (TSL/InstancedMesh) directories.\n  - Updated `YouMeOSEngine` to support asynchronous generator initialization.\n  - This architecture eliminates runtime conditionals and ensures clean separation of concerns for the ongoing migration.\n\n### Changed\n\n- **README Consolidation**: Removed the redundant `README.txt` file. All plugin documentation now lives in `README.md` using rich Markdown formatting for better readability and maintenance.\n\n### Fixed\n\n- **Window Management Regression**: Resolved an issue where maximized windows would incorrectly overlap navigation drawers and app bars.\n  - Refactored `u-webtop` to implement a `.u-webtop-surface` relative coordinate system. This surface naturally respects `v-main` layout padding, ensuring all absolute-positioned windows stay within the "safe" content area.\n  - Synchronized `x-window` maximization logic with Vuetify\'s `useLayout` variables, ensuring pixel-perfect alignment with the available content area.\n  - Explicitly enforced `h-100` and flex-grow behavior on the `x-main` (webtop) to ensure the desktop environment utilizes the full vertical space provided by the layout.\n- **Side Zoom Slider Layering**: Lowered `u-zoom-slider` z-index to `5`. This ensures it remains visible above the 3D engine canvas (z-index 0) but sits safely beneath any application windows (starting z-index 100) and even raw primitives (z-index 10), preventing the slider from interfering with interactive window content.\n- **Helios Navigation & Zoom**: Resolved an issue where navigating to Helios would not consistently reset the camera to the correct "Sun Level" zoom.\n  - Updated Helios target zoom from `1.17` to `2.0` as requested (Zoom Level 2).\n  - Consolidated portal navigation fly-to logic to ensure camera transitions trigger even when clicking the active tab\'s root icon (reinforcing "navigate to" intent).\n  - Optimized the route watcher to handle path-specific resets for all major portal dashboards (Helios, Nexos, Noosphere).\n\n### Added\n\n- **Base URL & Routing awareness**: Optimized the application to work correctly within WordPress subdirectories and multiple entry points (`/youmeos/` and `/os/`).\n  - Implemented broader WordPress rewrite rules in `class-xophz-compass-event-horizon-public.php` to prevent 404s on deep-link refreshes.\n  - Dynamically detects the application vessel (`youmeos` vs `os`) and site path to configure a robust `historyBase` for Vue Router.\n  - Refactored `useLegacyLoader.ts` to ensure all 3D engine assets and manifests load relative to the WordPress site path.\n  - Standardized internal navigation links to be base-relative, ensuring consistent behavior across all deployment environments.\n- **Authentication Flow**: Resolved several issues preventing successful login and navigation in YouMeOS.\n  - Added `X-WP-Nonce` header to the login request in `useAuth.ts` to prevent 403 Forbidden errors when legacy session cookies are present.\n  - Updated the PHP login handler in `class-xophz-compass-event-horizon-public.php` to include the `user_id` in the response.\n  - Updated `useAuth.ts` to sync the successful login state with the global `window.xophzCompassSettings.currentUser.ID`, ensuring the router guard allows entry to authenticated routes.\n  - Integrated `loadPlugins` reload into the `LoginView.vue` successful login sequence to ensure the Noosphere and other data-driven components refresh with authenticated data.\n- **API Robustness**: Added defensive checks to `src/engine/api.ts` to prevent application crashes when network or server errors return an undefined response object.\n- **Navigation & Camera Fly-To**: Resolved an issue where clicking Helios, Nexos, or Noosphere links would not trigger the camera transition in the 3D engine.\n  - Corrected hardcoded path matching logic in `u-desktop.ts` and `useNavigation.ts` that was expecting `/os/` prefixes.\n  - Ensured compatibility with the new dynamic `historyBase` routing configuration.\n\n## [2026-01-16]\n\n### Fixed\n\n- **Global Component Registry**: Fixed a Vite resolution error by removing a stale and redundant `PluginGrid` entry from `global-components.ts`. The component was successfully migrated to Primitives as `x-plugin-grid`.\n- **Tour System Transition**: Fixed broken tour transitions in the modern engine.\n  - Connected `WelcomeView` → `enterUniverse()` to the modern tour via `youmeos:tour:start` custom event.\n  - Updated `u-desktop.ts` to listen for the event and trigger `enterApp()`.\n  - Enhanced `useTourGuide.ts` with a dedicated theater DOM overlay featuring:\n    - Glassmorphic message display with Orbitron typography\n    - Message fade-out during camera movement\n    - Message fade-in upon camera arrival\n    - Proper handling of stationary slides (`travelTime: 0`)\n  - Deprecated legacy `window.tour.start()` in favor of modern event-driven architecture.\n- **Interactive Star Navigation**: Restored the ability to click on labeled stars to fly the camera to them. Implemented `onMarkerClick` in `youmeos.engine.ts` with correct coordinate transformations for the rotating universe model.\n\n## [2026-01-15]\n\n### Added\n\n- **Welcome View Refactor**: Redesigned the entry screen to match the Helios/Portal layout.\n  - Implemented a 3-card interactive layout: **Start Tour**, **Enter Youniverse**, and **Weave a Youniverse**.\n  - Integrated the signature sprite-based spinning loader for the "Start Tour" card.\n  - Updated the "Youniverse" title branding with smooth gradient text and a 3D spinning "ni" element.\n- **YouMeOS Taxonomy**: Established official celestial terminology for the system.\n  - **The Noosphere**: The high-level Galaxy perspective representing the "sphere of human thought."\n  - **The Nexos**: The social/hipparcos level representing relationships and connections.\n  - **Helios**: The personal/sun level representing the core dashboard.\n  - **Satellites**: Functional applications (Wapps) mapped to planetary bodies (e.g., Saturn = Blog, Earth = Pictures).\n  - **Oort Cloud**: The user\'s content and consumption cloud.\n- **Randomized Loader Messages**: Expanded the boot sequence with a philosophical "batch" of over 100 randomized messages.\n  - Aligned messaging with the **YouMeOS Movement**: Global balance, individual sovereignty, and collective unity.\n  - Implemented `getRandomLoaderMessage` helper in `loader-messages.ts`.\n- **Documentation**: Created `docs/Taxonomy.md` and updated `docs/Project-Philosophy.md` to reflect the new "Ancient-Futuristic" terminology.\n\n### Changed\n\n- **Navigation UI**: Updated the portal bottom navigation to use the new nomenclature (Noosphere, Nexos, Helios).\n- **Core Logic**: Updated `youmeos.controller.ts` and `u-desktop.ts` to support the new tab values and associated zoom/coordinate targets.\n- **Theme Principle**: Refined the "Compass Metaphor" to "Celestial Navigation" within the project philosophy.\n\n- **Shared Pinia Instance**: Created `src/engine/pinia.ts` as a singleton Pinia store that both COMPASS and YouMeOS share. This ensures that YouMeOS wapps (like Noosphere) have access to the same plugin list, user data, and application state as the main COMPASS application.\n- **Wapps Directory**: Organized YouMeOS sub-applications into a dedicated `apps/youmeos/app/wapps/` folder for cleaner architecture. Moved `noosphere` and `settings` into this new directory.\n\n### Changed\n\n- **Mount Files Refactor**: Updated `mount-app.ts` and `mount-youmeos.ts` to import the shared Pinia instance from `@/engine/pinia` instead of creating separate instances.\n- **Engine Barrel Export**: Added `pinia` to the engine barrel file (`src/engine/index.ts`) for convenient imports.\n\n### Fixed\n\n- **Noosphere "Disabled" Apps**: Resolved an issue where the Noosphere Wapp Store showed all apps as "Not Available". The root cause was YouMeOS creating its own Pinia instance, resulting in an empty `pluginList`. Now that both apps share the same store, the plugin data is available immediately.\n- **Consolidated Window Management**: Removed `UWindowManager` and consolidated its logic into `UWebtop`. Both the COMPASS admin and YouMeOS desktop now share `UWebtop` as the primary window orchestration layer, ensuring architectural consistency across the entire system and resolving previous import path errors.\n\n### Added\n\n- **Window Layout Awareness**: Refactored `u-webtop` and `x-window` to respect Vuetify\'s layout variables. Maximized windows now dynamically account for active app bars and navigation drawers using the `useLayout` composable, ensuring they stay within the "safe" content area.\n- **Window Stacking & Interaction**: Fixed an issue where the `u-webtop` content layer was blocking clicks to the underlying 3D visualization. Restricted `pointer-events` to window components only while maintaining "click-through" for the background.\n- **Window Scrolling**: Resolved an issue where window content was not scrollable. Explicitly enabled `pointer-events: auto` and `overflow: auto` on the `x-window__body` to ensure internal content receives mouse and touch events correctly.\n- **The Noosphere**: Renamed "The Armory" to "The Noosphere" throughout the system registry, component file naming, and portal navigation links to align with the core project narrative.\n- **Zoom Slider Stacking**: Lowered `u-zoom-slider` z-index (1000 -> 50) to ensure application windows (starting z-index 100) always appear on top when overlapping.\n- **Window Drawer Animation**: Refactored the `u-window` internal drawer to use the `x-navigation-drawer` primitive. Added smooth expansion/collapse animations using a premium `cubic-bezier` transition that dynamically adjusts `backdrop-filter` blur intensity (10px to 40px) during interaction.\n\n### Fixed\n\n- **Window Maximization**: Resolved an issue where maximized windows would overflow under app drawers and bars by implementing layout-aware dimension calculations.\n- **Window Draggability**: Restricted `x-window` dragging to the system bar (header) only. Implemented event propagation blocking on the window body to prevent content clicks from triggering the `vue3-draggable-resizable` drag logic, as the library\'s `drag-handle` prop was found to be unsupported in the current version.\n\n## [2026-01-13] - Tour Guide Integration\n\n### Changed\n\n- **Start Tour Button**: Connected the new Vue `useTourGuide` composable to the "Start Tour" button in `u-main-app-bar.vue`.\n- **Legacy Tour Replication**: Migrated the complete 40-stop `GALAXY_TOUR` from the legacy `tour.js` into the modern Vue architecture, preserving all camera positions, rotation angles, timing, and messages.\n- **Composable Enhancement**: Updated `useTourGuide.ts` to handle edge cases:\n  - Stops with `travelTime: 0` now instantly teleport the camera without animation\n  - Stops with only rotation changes (no z-position change) now properly advance to the next stop\n  - Added unified `onArrival` handler for consistent state management\n- **Callback Support**: Legacy engine callbacks (`materializeGalaxy`, `highlightMilkyWay`, `highlightStarHeat`, `displaySunEarthDiagram`) are now properly invoked during the tour.\n- **UI Restoration**: Tour completion now automatically restores the UI visibility after the final "KINNEXUS!" message.\n\n## [2026-01-13] - User Profile Update\n\n### Changed\n\n- **Profile Avatar**: Updated the sidebar user profile to use the custom "Y Logo" asset (`y-logo-v2.png`) and changed the list item variant to `text` for a seamless transparent look.\n\n## [2026-01-13] - Navigation UI Refinement\n\n### Fixed\n\n- **Zoom Slider Height**: Restored the `85vh` height to the vertical zoom slider by ensuring internal Vuetify components ($v-input__control$, $v-slider$) correctly inherit the container\'s height.\n- **X-Slider Vertical Sizing**: Enhanced `x-slider` styles to strictly enforce 100% height and width on internal Vuetify 3 elements (`.v-input__control`, `.v-slider__track`, `.v-slider-track__container`), resolving sizing issues where the slider might collapse or not align properly.\n- **Zoom Slider Force Override**: Applied an explicit `height: 85vh !important` to the `.v-input__control` within `u-zoom-slider.vue` to override deeply nested Vuetify defaults that were preventing the slider from expanding to the full intended height.\n\n### Changed\n\n- **Left Sidebar**: Made the left navigation drawer "permanent" with `rail` and `expand-on-hover` behavior. It is now visible throughout the application whenever the UI is active, rather than being restricted to the Portal view.\n- **Right Sidebar**: Restored the right navigation drawer to its original `temporary` behavior with hover-triggers.\n- **UI Consistency**: Updated the app bar toggle buttons to match the new sidebar visibility rules.\n\n## [2026-01-13] - Portal & Galaxy UI Fixes\n\n### Fixed\n\n- **Portal Centering**: Re-implemented `PortalView.vue` with `fill-height` and flex centering to ensure content is vertically and horizontally centered in the viewport.\n- **Galaxy Link Restoration**: Restored the "Galaxy" card in `PortalView.vue` which was previously replaced by the "Support" button.\n- **Navigation Restoration**: Restored the "Galaxy" link in the primary navigation drawer.\n\n### Changed\n\n- **Support Button Relocation**: Moved the Patreon/Support button to the System Bar (footer) for global access without displacing core navigation elements.\n\n## [2026-01-13] - Documentation & UI Polish\n\n### Added\n\n- **Plugin Documentation**: Created a modern `README.md` for the Event Horizon plugin, featuring a structured overview, feature list, and technical architecture summary.\n\n### Changed\n\n- **Patreon Integration**: Created `SupportView.vue`, a premium landing page explaining the project\'s subscription and key-based entry model.\n- **Universal Access Key**: Generated and integrated a high-fidelity "Access Key" asset for the Bazaar.\n- **Bazaar Navigation**: Added "Bazaar" to the primary navigation links and user drawer.\n- **Support Documentation**: Created `docs/Patreon-and-Bazaar.md` to outline the new payment and contribution model.\n- **Event Horizon README**: Updated the legacy `README.txt` with actual plugin information, replacing the boilerplate template.\n\n## [2026-01-13] - Engine Visual & Interaction Polish\n\n### Changed\n\n- **UI Scalability & Performance**: Capped the number of 3D star markers at 100 to prevent massive FPS drops caused by excessive DOM elements. Optimized the marker update loop with **per-marker proximity checks** (<800 units) to prevent thousands of labels from rendering simultaneously at key zoom thresholds (like `z=1483`). Added a CSS-based fade-in transition for smoother label appearance.\n- **Unified Galactic Rotation**: Introduced a `galaxyContainer` that acts as a single source of truth for the galactic tilt and internal spin. All star systems (Hipparcos, Galaxy, Solar System) now reside in this group, ensuring they move as a cohesive "universe" and eliminating the \'disconnected\' look.\n- **Skybox Logic**: Simplified skybox update logic to strictly follow the universe\'s rotation group, ensuring constant celestial movement during navigation.\n\n## [2026-01-13] - Grid Pulse & Glow Refinement\n\n### Added\n\n- **Procedural GlowSpan Texture**: Replaced the static `glowspan.png` with a programmatically generated `CanvasTexture`. This new texture implements a smooth Gaussian-like gradient that fades to absolute transparency on both ends, ensuring the light "beam" is perfectly centered and avoids hard artifacts during rapid pulses.\n\n### Changed\n\n- **Visual Balancing**: Refined the "light blue" grid pulse to a deeper, more atmospheric cosmic blue (`0x3388cc`).\n- **Pulse Intensity**: Dramatically lowered the pulse opacity range (from 80% peak down to 40% peak) in `galaxy.ts` for a more "ghostly" and sophisticated breathing effect.\n- **Rendering Quality**: Enabled `depthTest` for grid pulse materials to prevent visual clutter and "blown out" overlaps, resulting in a cleaner and more structured geometric appearance.\n\n## [2026-01-13] - Audio & Media Player Robustness\n\n### Changed\n\n- **Interaction Listener**: Moved the global audio interaction listener to the beginning of the application mount. This ensures that early user clicks (even during the boot sequence) can successfully unlock the audio context.\n- **Media Player Logic**: Refactored `togglePlay` and `nextTrack` in `youmeos.controller.ts` to use async/await and handle play promises. Added better error recovery: if a playback fails, it automatically attempts to re-unlock the context.\n- **Auto-Switching**: Improved track switching in `useAudio.ts`. The auto-switch (15s delay) now double-checks that the user hasn\'t manually started another track during the stylistic pause, preventing overlapping audio.\n- **State Synchronization**: Enhanced `isPlaying` and `isUnlocked` reactivity to better reflect the actual state of the HTML5 Audio elements, with comprehensive logging and error catching for better debugging of autoplay policies.\n\n## [2026-01-12] - Grid Legacy Material Reversion\n\n### Changed\n\n- **Material Logic**: Reverted the galactic grid from `ShaderMaterial` back to `MeshBasicMaterial` to restore the legacy aesthetic.\n- **Texture Manipulation**: Re-implemented the diagonal scrolling animation on the `glowspan.png` texture, reproducing the "flowing lattice" effect from the original engine.\n- **Visuals**: Updated the grid color to a bright "Whitish Blue" (`0xddeeff`) and increased opacity to 85% for better visibility against the new dark space background.\n\n## [2026-01-12] - Cosmic Background & Gas Systems\n\n### Added\n\n- **Gas/Nebula System**: Introduced `gas.ts` which spawns colorful, vibrant nebulas using addictive blending. These "gases" use a variety of textures (`p_0`, `p_1`, `p_2`) and colors (pinks, purples, cyans, golds) to add depth to the galactic disk.\n- **Deep Space Background**: Added `distant_galaxies.ts` which places small galaxy sprites at extreme distances (~2M+ units). This ensures that when zooming out far enough to see the galaxy as a point, there is a realistic background of neighboring galaxies in the "Local Group."\n\n## [2026-01-12] - Grid Aesthetic Refinement\n\n### Changed\n\n- **Grid Visibility**: Darkened the galactic grid to a "Midnight Purple/Blue" (`0x1a0633`) and reduced its opacity to 60% for a more subtle, integrated look.\n- **Grid Geometry**: Tightened the grid pattern (Subdivision 5) and refined the "lens" shape. Brought the outer edges to ~66k units (slightly expanded from 60k) and squeezed the center bulge to a 0.15 Y-scale.\n- **Shader Modulation**: Adjusted the `grid.fsh` shader to use deeper blue highlights and reduced the intensity of the additive breathing glow on ripple peaks.\n\n## [2026-01-12] - Visionary Boot Sequence\n\n### Added\n\n- **Dedicated Children\'s Statement**: Added a personal dedication to the beginning of the loader sequence: _"Dedicated to my Children: Krislynn Night, Khronus Infinidee, Gaia RhaeSatori, Ronan Tesla, and Kairos Sol"_\n- **Sequenced Captions**: Updated `loader-messages.ts` with the new `vision` caption.\n\n- **Interactive Controls Restored**: Fixed an issue where the user would lose control of the camera (panning/zooming) after the initial boot or when entering the Portal.\n- **Canvas Interaction**: Ensured `glContainer` is always interactive by setting `pointer-events: none` on blocking UI containers (`PortalView`, `WelcomeView`, and `v-layout`).\n- **Rotation Lock Removal**: Removed the `spinCutoff` logic that previously disabled rotation when zoomed in below 100 units, allowing for smooth navigation at all levels.\n- **Interruptible Zoom**: Made the 15-second initial "Cosmic Zoom" interruptible; manual user interaction now immediately stops the automated transition for responsive control.\n\n### Changed\n\n- **Loader Choreography**: Refined `useLegacyLoader.ts` to prioritize the vision statement.\n- **Reading Time Optimization**: Doubled the display duration for the vision statement (4s) and standard messages (2s) to ensure readability.\n- **Responsive Text Styling**: Improved `x-loader.vue` typography with `max-width`, improved `line-height`, and responsive padding to handle long visionary statements gracefully.\n- **Three.js Quaternion Patch**: Implemented a global patch for `THREE.Quaternion.setFromEuler` to prevent crashes in multi-context (HMR) environments where prototype checks may fail.\n- **Engine Shielding (Anti-Flicker)**: Added an `isEngineRunning` guard to the legacy `start()` function to prevent multiple animation loops and WebGL context conflicts.\n- **Singleton Loader Pattern**: Refactored `useLegacyLoader.ts` to use a singleton `isLoading` state, ensuring the boot sequence only triggers once regardless of route navigation or component remounting.\n- **Legacy Dedication Sync**: Synchronized the dedication text within the legacy `main.js` internal tour sequence.\n\n## [2026-01-12] - Intro Caption Restoration\n\n## [2026-01-12] - Documentation Refinement\n\n### Changed\n\n- **YouMeOS-Engine.md**: Major overhaul of the engine integration documentation.\n  - Documented the modern modular architecture (`youmeos.controller.ts` + `useLegacyLoader.ts`).\n  - Updated synchronization details for the Global Event Bridge and Visionary Boot Sequence.\n  - Implemented a dual roadmap view featuring both a high-level **Summary Table** and detailed **Evolution Tracking**.\n  - Added specific guidance on the "Cosmic Tour" choreography and "Orbitron" typography.\n\n## [2026-01-12] - Galaxy Engine, Navigation & UI Refinement\n\n### Added\n\n- **Independent Rotation Toggles**: Separated internal Galaxy spin from Camera Orbit in the system bar.\n- **Custom Icons**: Added **Galaxy** and **Sun** icons with choreographed slow rotations (15s) in the system bar.\n- **Reversible Spin**: Implemented `.fa-spin-reverse` CSS animation for counter-clockwise icon rotation.\n\n### Changed\n\n- **"Plate on a Stick" Rotation**: Fixed the galaxy/star wobble by implementing true local-axis rotation (`rotateY` and `rotateOnAxis`) in the legacy engine.\n- **Cinematic Speeds**: Optimized internal rotation and camera orbit speeds for a smoother, less frantic experience.\n- **Dramatic Welcome Entry**: Refined the "YouMeOS" title animation to take 3.2 seconds and scale up from a much smaller point (0.3).\n\n### Fixed\n\n- **Scroll Direction**: Inverted scroll-to-zoom direction (UP = Zoom IN) for standard navigation feel.\n- **Restored Zoom Slider**: Re-integrated missing HTML for the legacy zoom slider (`#minimap`) into the layout.\n- **Orbit Direction Override**: Fixed a hard-coded logic in `main.js` that previously overrode the camera\'s rotation direction every frame.\n- **Rotation Global Safety**: Added `enableGalaxySpin` and `toggleGalaxySpin` to `main.js` with robustness checks for `userData`.\n\n## [2026-01-12] - Fix Build Configuration\n\n### Fixed\n\n- **Vite Configuration**:\n  - Corrected router path in `vite.config.js` warmup configuration (`./src/router/router.ts` → `./src/routes/router.ts`).\n  - Added missing `@umeos` alias mapped to `./apps/youmeos` to resolve import errors in `youmeos.controller.ts`.\n  - Removed explicit `vuetify` component imports from `optimizeDeps` to prevent resolution failures during dependency scanning.\n- **Type Definitions**:\n  - Commented out deep internal `echarts` module declarations in `src/types/core/echarts.d.ts` that were causing dependency scan failures due to path mismatch.\n\n## [2026-01-12] - Loader Optimization\n\n### Changed\n\n- **Loader Timing**: Optimized the loader message sequence interval from 2 seconds to 1 second per message in `useLegacyLoader.ts` to speed up the boot experience.\n- **Intro Sequence**: Reduced the initial "Hello World!" message breathe time from 2 seconds to 1 second for a snappier startup.\n\n---\n\n## [2026-01-12] - Fix Build Configuration\n\n### Fixed\n\n- **Vite Alias**: Added `@umeos` alias to `vite.public.config.js` to correctly resolve imports from `apps/youmeos/components`, fixing build errors in `legacy-layout.vue`.\n- **Typography**: Changed main application font to **Orbitron** to align with the sci-fi/OS aesthetic.\n- **Brand Alignment**: Updated loader messages in `loader-messages.ts` to follow the project motto.\n- **Loader Timing**: Implemented a 5-second "message lock" in `useLegacyLoader.ts` to ensure visionary messages (like "Expanding into the infinite...") remain readable even if the engine sends technical status updates immediately after.\n- **Thematic Mapping**: Added mapping for legacy technical loader messages (e.g., "Igniting solar plasma" → "Igniting the Universal Core...") to maintain immersion throughout the boot sequence.\n- **Boot Choreography**: Implemented a mandatory 15-second "Visionary Sequence" in `useLegacyLoader.ts`. Messages are queued and displayed for 3 seconds each to ensure the project motto is fully conveyed.\n- **UI Reveal**: Configured a choreographed entrance: once boot completes, the camera zooms into the star clusters, followed by a staged fade-in of the Vue OS UI elements.\n- **Design System**: Extracted font families into CSS variables (`--font-main`, `--font-body`) in `_variables.scss`.\n\n## [2026-01-11] - YouMeOS Engine Port & Starfield Fix\n\n### Added\n\n- **YouMeOS Layout Initialization**: Initialized the topmost layer with `v-app` for full-screen layout support.\n- **Glass Panel Aesthetic**: Implemented a "glass panel" navigation drawer (`x-navigation-drawer`) on the right side with `backdrop-filter` blur and semi-transparent styling.\n- **Copyright Notice**: Added a dynamic copyright notice to the right navigation drawer footer.\n- **Background Integrity**: Configured CSS absolute positioning and transparency to ensure the WebGL starfield remains the main background while the Vue UI sits on top.\n- **YouMeOS Routing**: Migrated the legacy "Welcome Overlay" to a route-based architecture using `vue-router`.\n  - Added dedicated views for `Welcome`, `Login`, `Register`, and `Reset`.\n  - Implemented a top navigation drawer for central authentication access.\n  - Applied smooth fade transitions between routes.\n- **Hog Branding**: Updated the global sidebar with official "Hall of the Gods, Inc." branding and copyright notice.\n- **YouMeOS Engine Port**: Successfully ported the legacy 100,000 stars engine logic.\n  - Implemented `UniverseEngine` using legacy Three.js r62 with modern TypeScript integration.\n  - Configured high-density starfield rendering (115k+ stars) with custom shaders.\n  - Integrated legacy loader UI with real-time status messages.\n- **Vendor Assets**: Properly enqueued legacy `three-r62.min.js`, `Detector.js`, and `tween-legacy.js` with global scope patches to resolve `ReferenceError` issues.\n- **YouMeOS Relocation**: Moved legacy code from `public/youmeos_legacy` to `wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy` for better plugin organization.\n- **Path Aliasing**: Configured `@plugins` alias in `vite.config.js` to support cross-plugin asset references.\n- **Documentation**: Created `docs/plugins/YouMeOS-Engine.md` documenting the architecture and future scalability improvements (Manifest-driven loading, Composablization, etc.).\n- **Manifest-Driven Loading**: Refactored `PublicApp.vue` to load legacy scripts, styles, and audio configuration from a `manifest.json` file, removing hardcoded lists.\n- **Environment Configuration**: Implemented `.env` support for YouMeOS, moving `VITE_YOUMEOS_PLUGIN_PATH` and `VITE_DEV_SERVER_PORT` out of the source code.\n- **Standalone App Architecture**: Refactored YouMeOS into a fully standalone app structure at `/apps/youmeos/`.\n  - Moved entry point to `apps/youmeos/index.html` and `mount-youmeos.ts`.\n  - Split `PublicApp.vue` into `youmeos-app.vue` (Template), `youmeos.controller.ts` (Logic), and `youmeos.scss` (Styles) for better separation of concerns.\n\n### Fixed\n\n- **THREE CORS Issues**: Explicitly set `THREE.ImageUtils.crossOrigin = "anonymous"` in `PublicApp.vue` to resolve `texImage2D` security errors when loading textures from the dev server.\n- **YouMeOS Entry Point Fix**: Updated the PHP plugin\'s template redirect to point to the correct Vite dev server (port 9000) and the renamed `mount-youmeos.ts` entry point.\n- **Legacy Logic Fixes**: Corrected broken HTML comments in `login.html` that caused script parsing issues.\n- **Engine Initialization**: Resolved "black screen" issues by identifying a depth-clipping bug. Increased `sceneSize` to 100,000 to prevent immediate fading at the default camera distance (57,777).\n- **Asset Paths**: Corrected texture and data fetching paths to be compatible with both Vite dev server and WordPress plugin architecture.\n- **Visuals**: Optimized star point sizes (60.0) for modern high-resolution displays.\n- **Build Error**: Resolved `[ERROR] Unexpected "..."` in `pixie-dust-pixel-form.vue` by replacing ambiguous `...` inside script tag string placeholder with `/* code here */`.\n- **Parsing Error**: Refactored `pixie-dust-pixel-form.vue` to use `<script setup>` syntax, resolving `esbuild` parsing issues with the previous `defineComponent` block.\n- **YouMeOS Audio Playback**: Resolved `NotAllowedError` by gracefully handling autoplay failures and retaining event listeners until successful interaction.\n- **Console Violations**: Added `passive: true` to touch event listeners in `youmeos.controller.ts` to resolve scroll-blocking warnings.\n- **YouMeOS UI**: Refactored the "Welcome/Login" overlay into a native Vue component (`YouMeOSOverlay.vue`), replacing the legacy `login.html` injection. This lays the groundwork for router-based navigation while preserving the original "UMeOS" branding and "Click to Enter" behavior.\n\n### Note\n\n- **Node.js Environment**: Identified that the project\'s Vite 7 configuration require Node.js 20+. Current host environment (v18.19.1) will cause `crypto.hash` errors during build. Users are advised to use the provided Docker containers for all compilation and development tasks.\n\n---\n\n## [2026-01-10] - Documentation Overhaul\n\n### Changed\n\n- **Design-System-Atoms.md**: Completely rewrote documentation to reflect current project structure.\n  - Renamed "Atoms" to "Primitives" per game engine naming convention\n  - Updated directory paths from `components/atoms/` to `components/primitives/`\n  - Documented all 30+ current primitives in categorized tables\n  - Added component hierarchy (Primitives → Prefabs → Constructs → Blueprints)\n  - Updated global registration details to match `src/engine/primitives.ts` plugin\n  - Added guidance for creating new primitives with template patterns\n- **Architecture-and-Migration.md**:\n  - Removed outdated Tailwind CSS references; confirmed Vuetify 3 + SCSS as the primary styling stack.\n  - Updated tech stack to include Axios and TanStack Vue Query.\n  - Corrected engine file paths (`primitives.ts`, `global-components.ts`).\n  - Refined current focus to emphasize WordPress REST API integration.\n- **Migration-Progress.md**:\n  - Removed outdated Tailwind CSS tasks.\n  - Added completed tasks for Barrel Exports, ECharts, and Bomb Bag refactoring.\n- **Component-Refactoring-Guide.md**:\n  - Removed Tailwind-specific typography and utility class mentions.\n  - Standardized on Vuetify utility classes and `.text-mono` for HUD elements.\n- **System Docs**:\n  - Moved specific plugin documentation (`Bomb-Bag-System.md`, `Magic-Cloak-System.md`, etc.) to `docs/plugins/` for better organization.\n  - Updated `Magic-Cloak-System.md` to reflect dynamic hint fetching implementation.\n\n---\n\n## [2026-01-09] - Vuetify Component Catalog\n\n### Added\n\n- Migrated Three.js engine from r62 to r182 using ES modules and TypeScript.\n- New engine directory `apps/youmeos/engine/` housing modernized components.\n- Modernized rendering modules: `galaxy.ts`, `hipparcos.ts`, `sun.ts`, `solarsystem.ts`, `dust.ts`, `skybox.ts`.\n- Implemented `YouMeOSEngine` class for centralized initialization and management.\n- Modernized marker system using `CSS2DRenderer`.\n- Refactored all GLSL shaders to version 300 es (modern `in/out` syntax).\n- Integrated `pnpm` and Node 25 for better development environment stability.\n- Added `env.d.ts` for Vite-specific raw import type support.\n- **Component Catalog**: New reference page in the **Enchiridion** module showcasing Vuetify components with the project\'s custom glassmorphic styling.\n  - Path: `/enchiridion/components`\n  - Categories: Buttons, Form Elements, Cards & Chips, App Layout, Feedback & Progress.\n  - Includes interactive examples of project-specific `@mixin glass` and `@mixin glass-accent` applications.\n- **Mock App Layout**: Added a simulated `v-layout` environment within the catalog to demonstrate `v-app-bar`, `v-navigation-drawer`, and `v-system-bar` coordination.\n- **Route Registration**: Added `EnchiridionComponents` route to `enchiridion.routes.ts` with a pink `puzzle-piece` icon.\n\n---\n\n## [2026-01-09] - WordPress Admin Menu Refinement\n\n### Changed\n\n- **Menu Item Height**: Overrode the height of the first menu item ("Xophz Compass") in the WordPress sidebar to 40px to match the **System Bar** height.\n  - Corrected the selector in `_wordpress.scss` to target `li.toplevel_page_xophz-compass`.\n  - Added `display: flex` and `align-items: center` to the menu link for better vertical centering.\n\n---\n\n## [2026-01-09] - Reactive Color Scheme Synchronization\n\n### Fixed\n\n- **UI Synchronization**: Refactored `useWpTheme.ts` to share reactive state globally.\n  - Changes made via the **System Bar**\'s theme menu now immediately reflect on the **Profile Page** billboard and color scheme picker.\n  - Live hover previews are now synchronized across all components using the `useWpTheme` mechanic.\n  - Resolved an issue where the profile page would show "Midnight" as active even after switching to another theme.\n\n---\n\n## [2026-01-09] - System Bar Layout Refinement\n\n### Changed\n\n- Swapped the positions of the **Compass** and **Theme** buttons on the system bar.\n  - **Compass** (navigation) moved to the left grouping for primary visibility.\n  - **Theme** (utility) moved to the right grouping near the user profile.\n\n---\n\n## [2026-01-09] - Color Palette Picker Refactor\n\n### Added\n\n- **Global Theme Mechanic**: Created `useWpTheme.ts` composable to centralize WordPress administrative color scheme management.\n  - Supports live preview on hover\n  - Handles stylesheet swapping and body class updates\n  - Updates CSS variables (`--wp-theme-*`) for application-wide consistency\n- Created `useWpTheme` mechanic to consolidate WordPress admin color scheme management.\n- Created `theme-menu-btn.vue` prefab for global theme switching in the system bar.\n- Created `x-theme-snackbar.vue` to provide a unified, glass-accented feedback UI for theme changes.\n\n### Changed\n\n- Refactored `profile-color-scheme.vue` to use the new `useWpTheme` mechanic.\n- Integrated `theme-menu-btn` into the default `system-bar.vue`.\n- Updated profile billboard to reactively show theme colors on its chips.\n- Unified snackbar design across the application using `x-theme-snackbar`.\n- Enhanced theme menu aesthetics with custom 3x3 grid and rough-glass styling.\n\n---\n\n## [2026-01-09] - Profile UI & Billboard Enhancements\n\n### Added\n\n- **Profile Billboard Chips**: Enhanced the profile billboard with new informational chips:\n  - **User Email**: Added a chip displaying the user\'s email with a Font Awesome envelope icon.\n  - **Join Date**: Added a "Joined [Month] [Year]" chip using native `Intl.DateTimeFormat`.\n  - **Color Scheme**: Added a chip displaying the active administrative color scheme name with a paintbrush icon.\n- **Data Integration**: Injected `xophzCompassSettings` into `profile.controller.ts` to map scheme keys to human-readable names.\n\n### Changed\n\n- **Color Scheme Picker Refinements**:\n  - **"Active" Badge Repositioning**: Moved the active status indicator from inside the button to the top-left corner of the scheme card using absolute positioning.\n  - **Tonal Styling**: Updated the active badge to use the `tonal` variant for a more subtle, premium look.\n  - **Icon Integration**: Moved the duotone paintbrush icon from the main selection button to the active badge itself.\n  - **Flex Layout**: Added `ga-2` and `flex-wrap` to the billboard chip container to ensure clean wrapping and spacing.\n\n---\n\n## [2026-01-09] - Profile Billboard Fix\n\n### Fixed\n\n- **Billboard Slot System**: Added default slot support to `sub-app-billboard.vue`. When custom content is passed (e.g., profile page with user avatar/name), it now renders that content instead of the plugin-based image/title/description. Plugin billboard pages remain unaffected.\n\n---\n\n## [2026-01-09] - Plugin Grid Order Persistence\n\n### Added\n\n- **User Preference Storage**: Plugin grid order is now saved to the user\'s WordPress profile and persists across page refreshes.\n- **Backend**: Added `save_plugin_order` and `get_plugin_order` AJAX handlers in `class-xophz-compass-admin.php` that store order in `_compass_plugin_order` user meta.\n- **Frontend API**: Added `savePluginOrder()` and `getPluginOrder()` methods to `compass.api.ts`.\n- **Pinia Store**: Updated `useCompassStore` to:\n  - Load saved order on startup (parallel with plugin list fetch)\n  - Sort plugins based on saved order\n  - Save new order with 500ms debounce after drag-and-drop reordering\n\n### Fixed\n\n- **API Wrapper**: Fixed `api.post()`, `api.put()`, and `api.delete()` in `engine/api.ts` to send form-encoded data (`application/x-www-form-urlencoded`) instead of JSON body. WordPress `$_REQUEST` cannot read JSON body, causing all POST/PUT/DELETE API calls to fail silently. This fix affects all plugins using the API wrapper.\n- **Profile Color Scheme**: Registered `admin_color` as a REST API field in `class-xophz-compass.php`. WordPress doesn\'t expose this field by default, causing the color scheme picker to fail silently. Now properly saves via `POST /wp/v2/users/me`.\n\n---\n\n## [2026-01-09] - Profile Schema Form Redesign\n\n### Changed\n\n- **wp-schema-form**: Redesigned the profile schema form to match WordPress admin form-table layout pattern.\n  - Replaced `v-row`/`v-col` grid with semantic `<table class="form-table">` structure\n  - Labels now appear in `<th scope="row">` cells, inputs in `<td>` cells\n  - Boolean checkboxes use inline label pattern (checkbox + description text) like WordPress admin\n  - Added `getFieldLabel` helper to convert field keys (e.g., `first_name`) to readable labels ("First Name")\n  - Moved field descriptions below inputs (for non-boolean fields) instead of using them as labels\n  - Added proper WordPress-style form-table SCSS with row borders, vertical alignment, and theming\n\n---\n\n## [2026-01-09] - REST API & Console Cleanup\n\n### Fixed\n\n- **usePostType URL Construction**: Fixed a bug where REST API URLs were incorrectly formed with two question marks when WordPress uses the `?rest_route=` format (e.g., `/index.php?rest_route=/wp/v2/post_type?query=value` → `/index.php?rest_route=/wp/v2/post_type&query=value`). Now correctly uses `&` separator when the base URL already contains a query string.\n- **404 Error Handling**: `usePostType` now gracefully handles 404 responses for optional/conditional post types (like `compass_cloak_hint` which only exists when Magic Cloak plugin is active) by returning an empty array instead of throwing an error.\n- **Console Noise**: Disabled verbose debug logging in `SubAppBillboard` controller to reduce console clutter. Debug info still available via the `pluginDebug` computed property.\n\n---\n\n## [2026-01-09] - Bomb Bag UI Improvements\n\n### Changed\n\n- **Navigation**: Removed "Bomb Bag" prefix from all sub-routes for a cleaner sidebar appearance.\n- **Icons**: Assigned unique, descriptive Duotone icons to all Bomb Bag sub-routes (Dashboard, Campaigns, Subscribers, Settings, etc.).\n- **Styling**: Added custom Duotone primary/secondary color mappings for all new sub-route icons in `_icon-colors.scss`.\n\n### Fixed\n\n- **Logic**: Updated all internal navigation calls (`router.push`) to match the new route naming convention.\n\n---\n\n## [2026-01-08] - Mount App Refactor\n\n### Changed\n\n- **Modular Extraction**: Refactored `mount-app.ts` from 126 to 60 lines by extracting logic into dedicated modules:\n  - `engine/filters.ts` — Currency and kFormatter filter functions\n  - `engine/echarts.ts` — ECharts module registration and VChart export\n  - `engine/global-components.ts` — Centralized async component registry\n- **Component Registration**: Replaced repetitive manual PascalCase/kebab-case registration with automated loop using `toKebabCase()` helper.\n- **Code Organization**: Improved readability with clear section comments and logical grouping.\n\n---\n\n## [2026-01-08] - Bomb Bag Email Marketing Plugin\n\n### Added\n\n- **New Plugin**: Implemented Bomb Bag email marketing plugin with campaign management, subscriber lists, and newsletter delivery.\n- **Backend**:\n  - Custom database tables: `wp_bomb_bag_campaigns`, `wp_bomb_bag_subscribers`, `wp_bomb_bag_lists`, `wp_bomb_bag_list_subscribers`, `wp_bomb_bag_emails`, `wp_bomb_bag_analytics`\n  - REST API endpoints for campaigns, subscribers, lists, settings, and analytics\n  - Email handler with batch processing via WP-Cron\n  - Open/click tracking via tracking pixel and URL rewriting\n  - One-click unsubscribe handling\n  - Email provider configuration (WordPress, SendGrid, Mailgun, SMTP)\n- **Frontend**:\n  - Dashboard view with stat cards, subscriber growth chart, and quick actions\n  - Campaigns list with status filtering and CRUD operations\n  - Subscriber management with search, filtering, and CSV import\n  - Email composer with HTML editor and live preview (desktop/mobile toggle)\n  - Settings view for email service provider configuration\n  - Pinia store and API composable with full TypeScript types\n- **Theme**: Pink accent (#ED55A9) matching plugin color\n- **Documentation**: Created `Bomb-Bag-System.md` technical documentation.\n\n---\n\n## [2026-01-08] - Mirror Shield Security Plugin\n\n### Added\n\n- **New Plugin**: Implemented Mirror Shield security plugin with honeypot traps, attack logging, and IP blocking.\n- **Backend**:\n  - Custom database tables: `wp_mirror_shield_logs`, `wp_mirror_shield_traps`, `wp_mirror_shield_blocked`\n  - REST API endpoints for logs, stats, traps CRUD, and IP blocking\n  - Honeypot handler for decoy endpoints and hidden form fields\n  - Auto-blocking of repeat offenders (3+ triggers in 1 hour = 24h block)\n- **Frontend**:\n  - Dashboard view with ECharts attack timeline and effectiveness gauge\n  - Traps management view with CRUD operations\n  - Attack logs view with server-side pagination and filters\n  - Pinia store and API composable\n- **Default Traps**: Seeded honeypots for `/wp-admin/backup.php`, `/wp-config.bak`, `/administrator/`, and login form honeypot field.\n- **Documentation**: Created `Mirror-Shield-System.md` technical documentation.\n\n---\n\n## [2026-01-08] - Glass Card & API Nonce Fixes\n\n### Added\n\n- **New Component**: Created `x-glass-card` primitive component to encapsulate the "glassmorphic" aesthetic and resolve Vue warnings.\n\n### Fixed\n\n- **API**: Updated `usePostType` and `api.ts` to correctly send the WordPress Nonce (`X-WP-Nonce`) via `xophzCompassSettings.nonce`, resolving 403 Forbidden and 400 Bad Request errors.\n- **Dependencies**: Fixed missing `x-glass-card` component used in `profile-color-scheme.vue`.\n\n---\n\n## [2026-01-08] - Hover Icon Interaction for Go Button\n\n### Added\n\n- **New Interaction**: Added yellow "slow" traffic light icon to `x-go-btn` when hovering over the associated toggle button in `plugin-grid-circle`.\n- **New Prop**: Added `slow` prop to `x-go-btn` to control the traffic light state independently of the `disabled` state.\n\n---\n\n## [2026-01-08] - Plugin Grid Navigation & Cursor Refinements\n\n### Added\n\n- **Navigation**: Clicking a `plugin-grid-item` now triggers navigation (emits `go`) if the plugin is activated.\n- **Visual Feedback**: The cursor now remains `default` when hovering over a deactivated plugin grid item to signal that it is not clickable.\n\n---\n\n## [2026-01-08] - Vue 3 Transition Reveal Animation\n\n### Changed\n\n- **`plugin-reveal-circle`**: Refactored to use Vue 3 native `<Transition>` component with JS hooks instead of CSS-only class toggles.\n  - Icon now uses `icon-anchor` wrapper for stable positioning during expand/collapse\n  - Content reveal uses `onEnter`/`onLeave` JS hooks for orchestrated stagger animation\n  - Children (meta, title, desc, actions) animate in sequence with 50ms delays\n  - Effects layer wrapped in `<Transition name="effect">` for fade in/out\n- **SCSS**: Added Vue transition classes (`.effect-enter-active`, etc.) and simplified hover state styling\n\n---\n\n## [2026-01-08] - Grid Circle Reveal Animation\n\n### Added\n\n- **`x-pulsing-ring` Primitive**: New reusable component for pulsing ring/ripple effects. Extracted from `compass-circle` for broader reuse.\n  - Configurable ring count (1-5)\n  - Optional outer structural rings\n  - Pulse-on-hover mode\n- **`plugin-reveal-circle` Prefab**: New animation pattern for plugin grid items replacing the flip animation.\n  - Default: Full-size plugin image fills the circle\n  - Hover: Image shrinks to center, outer rings grow in, content fades in around image\n  - Supports scanline, grid, and dots background effects\n\n### Changed\n\n- **`plugin-grid-item`**: Switched from `plugin-grid-circle` (flip) to `plugin-reveal-circle` (reveal) for a smoother, less jarring animation in the grid context.\n- **Animation Philosophy**: Flip animation preserved in `compass-circle` for hero/intro contexts; reveal animation used for dense grid layouts.\n\n---\n\n## [2026-01-08] - Compass Circle Animation Options\n\n### Added\n\n- **Animation Prop**: Added `animation` prop to `compass-circle.vue` with \'flip\' (default) and \'fade\' options.\n- **Fade Animation**: New fadeout-fadein animation that crossfades between front and back faces instead of 3D flip.\n\n### Changed\n\n- **Plugin Grid Items**: Updated `plugin-grid-item.vue` to use \'fade\' animation by default, providing a smoother transition for the plugin grid.\n- **Animation Passthrough**: Added animation prop passthrough in `plugin-grid-circle.vue`.\n\n---\n\n## [2026-01-08] - Plugin Grid Component Redesign\n\n### Added\n\n- **New Components**: Created 3 new components for a cleaner, self-contained plugin grid:\n  - `x-plugin-icon` (Primitive): Simple circular avatar for icons.\n  - `plugin-grid-item` (Construct): Interactive cell wrapping `compass-circle` with flip behavior.\n  - `plugin-grid` (Blueprint): Responsive grid container.\n\n### Changed\n\n- **Migration**: Replaced fragile manual grid in `compass-explore.vue` with `<plugin-grid>` component.\n- **Layout**: Icons start at 80px and expand to 300px on hover with full flip animation.\n- **Cleanup**: Removed conflicting wrapper styles from `_compass-explore.scss`.\n\n---\n\n## [2026-01-08] - Plugin Grid Visibility Improvements\n\n### Changed\n\n- **Plugin Size**: Increased base `size` from 180 to 220 in `compass-explore.vue`.\n- **Scaling Enhancement**: Updated base scale from 0.6 to 0.82 in `_compass-explore.scss`, making plugins significantly more prominent and easier to interact with.\n- **Hover Transitions**: Refined hover scale to 1.1 with a smoother 0.6s cubic-bezier transition.\n- **Grid Layout**: Adjusted `v-col` breakpoints (`cols="6" sm="4" md="3" lg="2"`) to ensure a balanced grid of 2 to 6 items per row rather than a single horizontal line on large screens.\n- **Spacing**: Tightened column padding to reduce gaps and create a more cohesive grid look.\n- **Global Height Optimization**: Standardized `100%` height and `min-height: 100vh` across `html`, `body`, and all main layout blueprints (`x-compass-layout`, `x-sub-app-layout`, `x-sub-route-layout`). This ensures that the application shell and content areas always utilize the full available vertical space, preventing content from being cramped or background gradients from cutting off.\n- **Plugin Grid Centering**: Applied `fill-height` and `align-content="center"` to the `compass-explore` grid for perfect vertical alignment within the expanded viewport.\n\n---\n\n## [2026-01-08] - Plugin List Runtime Fixes\n\n### Fixed\n\n- Resolved `Uncaught TypeError: compassStore.pluginList.some is not a function` in `useMagicCloak.ts`.\n- Ensured `compassStore.pluginList` is always an array by adding defensive logic in `loadPlugins` action to handle object responses from WordPress API.\n- Added safety checks in `useMagicCloak` and `compass-explore` controllers for all native array method calls on `pluginList`.\n\n---\n\n## [2026-01-07] - Explore Redesign & Magic Cloak System\n\n### Added\n\n- **Magic Cloak System**: New global mechanic (`useMagicCloak`) for context-aware hints and tips.\n- **Technical Documentation**: Created `Magic-Cloak-System.md` outlining the architecture for the new messaging system.\n\n### Changed\n\n- **Compass Explore UI**: Redesigned the plugin exploration page with a high-density grid layout.\n- **Interactive Icons**: Plugin icons now scale down to 60% by default and grow smoothly to 100% on hover.\n- **Glass Toast**: Replaced the static `v-alert` with a premium glassmorphic Magic Cloak toast that appears conditionally when the plugin is active.\n- **Responsive Grid**: Improved `v-col` sizing to support many more plugins "above the fold."\n\n---\n\n## [2026-01-07] - Thor\'s Hammer Fix\n\n### Fixed\n\n- Added self-healing logic to `xophz-compass-thors-hammer` to automatically recreate the `wp_xophz_thors_hammer_bans` database table if it goes missing, preventing SQL errors and "headers already sent" warnings.\n\n---\n\n## [2026-01-07] - Plugin Icon SVG Standardization\n\n### Changed\n\n- Standardized all 27 plugin icon SVGs to use uniform `viewBox="-44 -44 600 600"`.\n- All icons now have consistent 44px spillover space on all sides for elements extending beyond the circle.\n- Updated `plugin-circle.vue` icon width from 100% to 117% (600/512 ratio) to align inner circles with compass border.\n\n### Fixed\n\n- Resolved icon alignment issues where some icons appeared smaller or offset within the compass circle.\n- Icons with spillover elements (lantern handle, castle flags, etc.) now display correctly outside the circle.\n\n---\n\n## [2026-01-07] - Engine Refactor\n\n### Changed\n\n- Renamed `src/plugins` to `src/engine` to align with "Game Engine" naming convention.\n- Moved `src/plugins/post-digger.api.ts` to `src/routes/post-digger/post-digger.api.ts`.\n- Renamed `src/utils` to `src/toolkit` to serve as a container for pure utility functions.\n- Introduced `src/engine/patches` directory for environment-specific code.\n- Moved `src/utils/admin_menu_fix.ts` to `src/engine/patches/wordpress-menu.ts`.\n- Updated all import paths and `vite.config.js` to reflect these changes.\n- Fixed type errors in `xp/store/index.ts` related to API response handling.\n\n---\n\n## [2026-01-07] - Asset Reorganization\n\n### Changed\n\n- Moved `src/fonts` folder to `src/assets/fonts` to align with standard asset organization.\n- Updated font paths in `src/styles/core/_variables.scss` and `src/assets/fonts/flaticon/_flaticon.scss` to reflect the new location.\n\n---\n\n## [2026-01-07] - Vue Template SyntaxError Fixes\n\n### Changed\n\n- Refactored `admin_menu_fix.ts` to use modern ES6+ arrow function syntax.\n- Improved robustness with safety checks for `window.jQuery` and existence of `menuRoot` element.\n- Standardized URL/Path parsing for more reliable matching in WordPress Admin.\n- Replaced `self.parents("li")` with `$self.closest("li")` and added check for `wp-first-item`.\n- Improved initial highlighting logic with loop breaking for performance.\n\n### Fixed\n\n- Resolved `SyntaxError: Unexpected identifier \'as\'` project-wide by removing invalid TypeScript `as` casts from Vue templates.\n- Fixed template syntax errors in:\n  - `routes-navigation-drawer.vue`\n  - `enchiridion-dash.vue`\n  - `enchiridion-recipes.vue`\n  - `sparkplug.vue`\n  - `xp-my-achievements-index.vue`\n- Resolved implicit `any` and `unknown` type errors in templates by improving controller prop types and adding helper methods.\n- Verified project-wide with `npm run type-check` (now passing with 0 errors).\n\n### Documentation\n\n- Updated `Component-Refactoring-Guide.md` with new standards for TypeScript usage in templates.\n\n---\n\n## [2026-01-07] - TypeScript Type System Improvements\n\n### Added\n\n- `src/types/index.ts` — Root barrel file for all type exports\n- `src/types/core/index.ts` — Barrel file for core type augmentations\n- `src/types/core/window.d.ts` — Global Window interface augmentation (jQuery)\n- `src/types/core/echarts.d.ts` — Type shims for vue-echarts and echarts modules\n\n### Changed\n\n- Moved global type declarations to centralized `src/types/core/` directory\n- Added barrel exports for cleaner imports: `import "@/types"` instead of full paths\n- Standardized import pattern using ES modules instead of triple-slash references\n- Eliminated `any` types in `admin_menu_fix.ts` with proper `JQueryStatic` typing\n\n### Fixed\n\n- All TypeScript errors resolved — `npm run type-check` now exits with 0 errors\n- Fixed navigation drawer interface extension (changed to type alias)\n- Refactored bazaar `grid` and `stats` controllers to Composition API\n- Fixed sparkplug computed property (added missing setter)\n- Installed `@types/jquery` for proper jQuery type support\n\n---\n\n## [2026-01-07] - Type-Check Cleanup (Phase 1)\n\n### Added\n\n- New type definition files in `src/types/plugins/`:\n  - `xp.d.ts` - XpUser, Achievement, Ability, SoundFxMixin interfaces\n  - `bazaar.d.ts` - BazaarProduct, BazaarOrder, BazaarCategory, BazaarPaging interfaces\n  - `lit-lamp.d.ts` - CronJob, CronData, FileInfo, LogInfo interfaces\n  - `post-digger.d.ts` - Post interface\n\n### Fixed\n\n- Reduced type-check errors from 342 to 284 (~17% reduction)\n- Fixed Vue template typing using `{ item }: { item: TypeName }` pattern in slot bindings\n- Updated `xp-my-abilities.vue` to use Vuetify 3 menu activator pattern (`props` vs `v-on`)\n- Fixed `isMobile.ts` mixin with proper `defineComponent` and typed methods\n- Fixed `post-digger.api.ts` with proper parameter types\n- Added TypeScript `lang="ts"` to `xp-my-abilities-index.vue` and `xp-my-accessories-index.vue`\n- Fixed `soundFx` computed property in XP level-up controllers\n\n---\n\n## [2026-01-07] - SCSS Import Fixes\n\n### Fixed\n\n- Resolved Sass compilation errors in multiple components caused by outdated and broken `@use "@/styles/variables"` and `@use "@/styles/mixins"` paths.\n- Removed redundant SCSS imports from `.vue` and `.scss` files as these are now automatically injected via `vite.config.js`.\n- Fixed broken imports in `lit-lamp`, `pixie-dust`, `enchiridion`, `xp`, and layout components.\n\n---\n\n## [2026-01-07] - Stylesheet Cleanup & Reorganization\n\n### Added\n\n- New folder structure: `core/`, `vendor/`, `layout/`, `utilities/`\n- `core/_typography.scss` - fonts and heading styles\n- `layout/_app-shell.scss` - html, body, v-application containers\n- `layout/_animations.scss` - keyframes (drift, float)\n- `_index.scss` files in each folder using `@forward` for module exports\n\n### Changed\n\n- Reorganized `_variables.scss`, `_mixins.scss`, `_colors.scss`, `_icon-colors.scss` into `core/`\n- Moved `_wordpress.scss` to `vendor/`\n- Moved `_utils.scss` to `utilities/`\n- Rewrote `app.scss` with modern `@use` syntax (no deprecation warnings)\n- Updated `vite.config.js` scss paths to `core/` folder\n\n### Removed\n\n- Deleted `_overrides.scss` (1038 lines of unused Bootstrap overrides)\n- Deleted `_general.scss` (malformed syntax, duplicated content)\n- Deleted `_base.scss` (contents split into new architecture)\n\n---\n\n## [2026-01-07] - Sparkplug Component Update\n\n### Added\n\n- Added `collapsed` prop to `sparkplug` component to allow starting the component in a collapsed state.\n- Enhanced initial state logic to support both `expanded` and `collapsed` attributes.\n\n---\n\n## [2026-01-07] - Prettier Formatting Update\n\n### Changed\n\n- Added `singleAttributePerLine: true` to `.prettierrc` to format HTML/Vue attributes on separate lines.\n\n---\n\n## [2026-01-07] - Charts Library Migration\n\n### Changed\n\n- Migrated from ApexCharts to ECharts for better performance and flexibility.\n- Replaced `vue3-apexcharts` with `vue-echarts` package.\n- Refactored `sparkplug` component to use ECharts `v-chart` component.\n- Updated chart configuration from ApexCharts format to ECharts option format.\n\n---\n\n## [2026-01-07] - Sparkplug Styling\n\n### Changed\n\n- Updated `sparkplug` header typography for a more premium look.\n- Enhanced timeline icons size and spacing.\n\n---\n\n## [2026-01-07] - Compass Layout Refactor\n\n### Fixed\n\n- Refactored `compass.vue` to resolve layout issues caused by incorrect `v-layout` usage in Vuetify 3.\n- Centered the navigation stepper using `v-row` and `v-col` with `justify="center"`.\n- Modernized the stepper buttons by migrating to `x-btn` atom and adding "flare" styling (01, 02 prefix).\n- Improved glassmorphism with `backdrop-blur-md` and updated spacing for better mobile-first responsiveness.\n- Fixed `compass-dashboard.vue` layout by replacing `v-layout` with `v-container`, resolving row/column stacking issues.\n- Updated `sparkplug` component to support an `expanded` prop, defaulting to `true` for immediate visibility of metrics.\n\n---\n\n## [2026-01-06] - Pixie Dust Plugin Implementation\n\n### Added\n\n- Complete Pixie Dust marketing pixel management plugin\n- Pre-built templates for Facebook, GA4, GTM, Google Ads, TikTok, LinkedIn, Pinterest\n- Custom post type `compass_pixie_pixel` for pixel storage\n- Vue frontend with dashboard, pixel list, and add/edit form\n- Automatic pixel injection via `wp_head`, `wp_body_open`, `wp_footer`\n- Conditional loading options (all, home, single, archive, shop)\n\n---\n\n## [2026-01-06] - Moving Castle Planning\n\n### Added\n\n- Created roadmap for Moving Castle WordPress Multisite management system\n- Defined 5-phase implementation plan (Network Dashboard → Site Management → Content Sync → User/Domain → Advanced)\n- Clarified scope: Theme switching handled by Enchanted Mirror, Moving Castle focuses on Multisite only\n- Documented UI concepts (Portal Dial, Portal Cards) and technical requirements\n\n---\n\n## [2026-01-06] - Post Digger Fix\n\n### Fixed\n\n- Resolved `TypeError: Cannot read properties of undefined (reading \'loadPosts\')` in `post-digger-index.controller.ts`.\n- Refactored `post-digger-index.controller.ts` and `createUpdatePost.controller.ts` to use `usePostDiggerStore()` directly instead of `mapStores`.\n- Changed `post-digger` store ID to `postDigger` (camelCase) for better consistency and reliability.\n',ae=`# Cloudflare Email Routing & Vanity Email Architecture

This document describes the strategy and infrastructure for the YouMeOS vanity email system, leveraging Cloudflare Email Routing to provide users with a tiered spatial identity.

## Strategic Tiers

### Tier 1: The Community Layer (Free / Low-Cost)
- **Format:** \`[username]@mail.umeos.com\`
- **Purpose:** A streamlined, minimalist address for early adopters. It gets people into the ecosystem without giving away the premium brand real estate.
- **Routing Implementation:** Catch-all or programmatic routing on the subdomain \`mail.umeos.com\`.

### Tier 2: The Explorer Layer (Mid-Tier Paid)
- **Format:** \`@[concept].youmeos.com\` (e.g. \`@iam.youmeos.com\`)
- **Purpose:** For users willing to pay for a more customized, premium spatial identity. Users claim their specific "node" or "space" within the OS.
- **Routing Implementation:** Requires managing specific subdomain zones in Cloudflare or creating discrete aliases to forward to user-provided destination addresses.

### Tier 3: The Core / Official Members (Reserved)
- **Format:** \`firstname@youmeos.com\`
- **Purpose:** Strictly gated for the core team, partners, and high-tier foundational investors. Holding a root domain address proves the individual is part of the architecture itself.
- **Routing Implementation:** Carefully managed manual aliasing directly on the root \`youmeos.com\` zone in Cloudflare.

## Technical Setup (Cloudflare)

To implement this infrastructure programmatically using Cloudflare Email Routing, follow these guidelines:

### Prerequisites
1. Domain(s) registered and Active in Cloudflare (\`youmeos.com\`, \`umeos.com\`).
2. Cloudflare API Token with \`Email Routing:Edit\` permissions.

### Steps to Implement:
1. **Enable Email Routing:** Navigate to the specific Zone in Cloudflare dashboard -> Email -> Email Routing. Enable and let Cloudflare configure the necessary MX and TXT records.
2. **Create Destination Addresses:** Users must verify their destination inbox first (where emails will be forwarded). This is done via the Cloudflare API or Dashboard.
3. **Set Up Custom Rules:** Use Custom Addresses to link the vanity prefix (\`username@\`) to the verified Destination Address.

### Automation & API Integration
To fully automate Tier 1 and Tier 2 provision via the YouMeOS registration flow:
1. An endpoint in our application interacts with the **Cloudflare API**.
2. When a user requests \`xopher@mail.umeos.com\` to forward to \`xopher@gmail.com\`:
   - Add \`xopher@gmail.com\` as a Destination Address.
   - Wait for user to click the verification email.
   - Add a routing rule for \`xopher@mail.umeos.com\` pointing to that destination ID.

### Security & Sender Reputation
- *Outbound Sending:* Cloudflare Email Routing is purely for **inbound** forwarding. If users wish to *send* as their vanity email, they will need SMTP relay capabilities (e.g. Resend, Mailgun) or they map the address as a "Send Mail As" alias in Gmail using an external SMTP provider.
- *Root Domain Protection:* By strictly isolating Tier 1 (\`mail.umeos.com\`), we protect the main \`youmeos.com\` sender reputation from potential spam implications from open user signups.
`,oe=`# Hello World!

Welcome to the YouMeOS Enchiridion, the central knowledge repository and documentation suite for the entire COMPASS ecosystem.

Whether you're exploring the Youniverse as a user, contributing as a developer, or architecting the **w⁴ Worldwide Webwork** underneath it all, this is your starting point.

## Navigating the Enchiridion

To the left, you'll find the primary navigation drawer outlining the hierarchical structure of the system's documentation:
- **00. Welcome**: You are here! Essential onboarding and introductions.
- **01. YouMeOS**: Documentation, user guides, and blueprints regarding the browser-based environment (OS officially standing for Omega Source, though users may define their own universal acronyms like Operating System).
- **02. COMPASS**: Details on the underlying engine, API structures, and data schemas running quietly under the hood to power the BlackBOX nodes and YouMeOS.
- **03. Platform Docs**: Global references, change logs, philosophies, and overarching notes. Start with the [Ecosystem Architecture Guide](./reference/Ecosystem-Architecture.md) to understand the Trillion-Dollar spatial grid we are building.

Enjoy your journey into the stars.
`,ie=`# The W4 Protocol: Fractal Architecture & Civilizational Infrastructure

> *"From the Sovereign Individual to the Global Noosphere. Building the Operational Internet."*

This document defines the philosophical and architectural trajectory of YouMeOS and the COMPASS ecosystem. It outlines how a collection of WordPress plugins and a Vue-based spatial interface scale into a 4-dimensional engine capable of running global infrastructure.

---

## 1. The Civilizational Scale (The 4D Tesseract)

The traditional internet is built on 2D isolated spaces (websites) and walled 3D silos (SaaS platforms). YouMeOS is a 4-dimensional **Tesseract**—a network of networks where data, identity, and commerce move fluidly across dimensional boundaries without friction.

The architecture scales infinitely based on the "Box" deployed:
1. **Sovereign (The Individual):** The personal digital identity and vault (Omega Source).
2. **Business (The Local Hub):** The economic engine driving commerce and automated drip marketing (The Bazaar & Bomb Bag).
3. **Conglomerate (The Enterprise):** Cross-domain orchestration of multiple brands with a Single Customer View (Questbook).
4. **Metropolis (Municipal Infrastructure):** City-wide or county-wide networks running interconnected public services on a single shared database.
5. **Planetary (The W4 Protocol):** A borderless global operating system unifying disparate multi-networks.

---

## 2. The W4 Protocol (World Wide Web Work)

The **W4 Protocol** is the connective tissue of this global ecosystem. It defines the standards for how Sovereign boxes communicate, share state, and exchange value (Dirt micro-transactions). 

It ensures that while every node (BlackBOX to PalladiumBOX) is sovereign and completely owned by its user, they can natively interoperate. A user can drag a digital asset out of a municipal network and drop it into their personal Sovereign identity box without leaving the YouMeOS interface.

---

## 3. Fractal Architecture: Boxes Within Boxes

The true power of YouMeOS is its **Fractal Architecture**. It is designed to be packed into a massive Box (The Server/Node), but instantly unpacked into micro-boxes for the end user.

### The Macro-Boxes (Infrastructure)
*   **The w⁴ Protocol:** The central orchestrator for provisioning, managing, and navigating the vast network of YouMeOS environments.
*   **BlackBoxWhiteGlove & Buildablox:** The deployment engines that allow agencies and enterprises to stamp out customized, pre-configured Sovereign nodes at scale. 

### The Micro-Boxes (Sparks as PWAs)
YouMeOS does not trap functionality inside a single browser tab. **Every YouMeOS Spark is fundamentally designed as a standalone Progressive Web App (PWA).**

Because the frontend is decoupled from the WordPress core via the COMPASS REST API:
*   A user can "unpack" a specific tool—like the **Questbook CRM**—and install it directly on their iPhone as a solo mobile app.
*   A corporation doesn't just deploy a web portal; they instantly deploy a suite of standalone mobile applications for their employees. 
*   **The Paradigm:** The macro-box hosts the data; the micro-boxes (Sparks) are pre-packed PWAs distributed directly into the user's pocket.

---

## 4. The Backend Engine (COMPASS PHP Plugins)

To achieve this fractal nature without requiring massive proprietary SaaS infrastructure, YouMeOS hijacks the most ubiquitous web framework on earth: **WordPress**.

The **COMPASS PHP Plugins** act as the tectonic plates of the OS:
*   They enforce strict, future-proof database schemas (EAV model).
*   They expose airtight REST API endpoints (\`/questbook/v1\`, \`/magic-formula/v1\`).
*   They leverage \`WP Multi Network\` to achieve the cross-domain identity sharing required by the Tesseract.

By using WordPress as the dark-matter data layer, any of the 800 million WP sites on earth can be upgraded into a YouMeOS node simply by installing the plugins. 

---

## 5. The Path Forward

**How do we get there?**
1. **The Trojan Horse:** Distribute the COMPASS plugins to the existing WP market, converting legacy websites into API-first micro-nodes.
2. **The App Unboxing:** Finalize the PWA service workers so that Sparks can be easily detached and installed to home screens.
3. **The Multi-Network Matrix:** Harden the Moving Castle routing so that Titanium and Palladium boxes can seamlessly spin up and manage thousands of interconnected domains under a Single Customer View.
4. **The Global Tesseract:** Connect the disparate Networks via the W4 Protocol, allowing peer-to-peer data validation and asset transfer.
`,re=`# YouMeOS Federated Identity Architecture

## The Concept

The **YouMeOS Federated Identity** system allows a user to carry an "MMO-style profile" natively built on hybrid Server-to-Server and Web3 (IPFS) mechanics across the entire YouMeOS Multiverse.

Every website running YouMeOS is treated as a separate "Universe". By adhering to this global standard, users can seamlessly transition between domains. They carry their identity, aesthetics, and global XP across their own galaxy (if not their own universe), while strictly submitting to the local permissions of whichever Universe they are visiting. This architecture transforms the YouMeOS ecosystem into a living Universe—or even a Multiverse.

## Authentication (AuthN) vs Authorization (AuthZ)

To avoid breaking privilege chains or creating backdoors between discrete YouMeOS servers, the system actively separates **Identity** from **Permissions**:

- **Identity (AuthN)** - *Who are you?* (Sync Key Handshake + IPFS Backpack)
- **Permissions (AuthZ)** - *What can you do here?* (Local Site Admin Control)

When Site B (the destination) queries Site A (the origin), Site A strictly strips out all session tokens and role keys (\`isAdmin\`). Plural realms only share the \`GlobalPlayerProfile\`. A shadow profile is created locally on Site B to govern permissions independently of where the user originally authenticated from.

## The Dual-Pipeline Architecture

### 1. Server-to-Server Pipeline (The Secure Vault)
The central secure back-channel built to withstand bad actors.
- **Payload:** Cryptographic Handshakes (Sync Keys), Auth tokens, direct message payloads.
- **Mechanism:** Standard encrypted REST API POST calls over HTTPS bypassing browser CORS restrictions. Server B connects to Server A securely validating the user's \`Sync Key\`.

### 2. IPFS Pipeline (The Complete Backpack)
A purely decentralized state-bucket. Stored on IPFS and served blazingly fast through CloudFlare IPFS Gateways (e.g., \`ipfs.youmeos.com\`).
- **Payload (The MMO Slate):** Profile layout preferences, Dark/Light Neon theme settings, unified Avatars, global \`mmo\` state (XP, Level, Badges).
- **Mechanism:** As long as the hash matches the User's verified profile, any YouMeOS UI can instantly read and render this config to make the user feel "At Home."

## The "Sync Key" Handshake Flow

**1. The Request:**
A user logged into Site A opens their settings and requests to sync with Site B (or uses a Universal Link).

**2. The Generation:**
Site A's backend spins up a temporary (\`expires_at\`), cryptographically secure single-use token tied to the user's origin signature. Concurrently, Site A ensures the latest visual layout/XP data is pinned to IPFS, appending the CID to the payload.

**3. The Courier:**
The User (or URL) brings the generic \`Sync Key\` token payload to Site B.

**4. The Verification Check:**
Instead of trusting the token, Site B makes a backend server-to-server request back to Site A: *"Hey Site A, here is a Sync Key someone gave me. Is it valid, and if so, who does it belong to?"*

**5. The Data Grant:**
Site A answers truthfully. Site B links the new CID to a local "Guest/Shadow" account exactly replicating the aesthetic/identity of the imported account without inheriting any local rights.

## Extension

By keeping the Global Profile extensible, custom Sparks on different sites can independently query \`mmo\` badges or even extend the type model to incorporate unique Web3 items, mounts, or titles across the Noosphere.

## Web3 Gateways

| Gateway | URL | Purpose |
|---------|-----|---------|
| IPFS Read | \`https://ipfs.youmeos.com/ipfs/{CID}\` | Fetch pinned profiles globally |
| Ethereum RPC | \`https://aether.youmeos.com\` | ENS resolution via raw JSON-RPC |

Pinning is handled via the **Pinata API** using \`VITE_PINATA_JWT\` from the environment. The composable pins \`GlobalPlayerProfile\` JSON and returns the CID.

## REST API Endpoints

| Method | Endpoint | Auth | Purpose |
|--------|----------|------|---------|
| \`GET\` | \`/xophz/v1/passport\` | Logged-in user | Returns current user's passport (CID, wallet, ENS, MMO) |
| \`POST\` | \`/xophz/v1/passport\` | Logged-in user | Updates passport fields in \`wp_usermeta\` |
| \`GET\` | \`/xophz/v1/passport/public/{user_id}\` | Public | Read-only passport for cross-site identity resolution |

**User meta keys:** \`youmeos_profile_cid\`, \`youmeos_wallet_address\`, \`youmeos_ens_name\`, \`youmeos_mmo\`

## Composable: \`useWeb3Gateways.ts\`

Located at \`src/mechanics/useWeb3Gateways.ts\`. Provides:

- \`connectMetaMask()\` — Prompts MetaMask, saves wallet + ENS to server
- \`reconnectWallet()\` — Silently restores wallet via \`eth_accounts\` (no popup)
- \`resolveENS(name)\` — Forward resolution via raw \`eth_call\` against ENS Registry
- \`syncENS(address)\` — Reverse resolution (wallet → \`.eth\` name)
- \`pinProfileToIPFS(profile)\` — Publishes \`GlobalPlayerProfile\` to IPFS via Pinata
- \`fetchProfileFromIPFS(cid)\` — Reads profile back from \`ipfs.youmeos.com\`
- \`loadPassport()\` — Hydrates state from WordPress REST API
- \`savePassport(payload)\` — Persists passport fields to \`wp_usermeta\`

**ENS Note:** Uses \`SubtleCrypto\` for namehash computation, which requires HTTPS. On HTTP dev servers, ENS resolution gracefully degrades while wallet connection still works. Server-stored ENS names are hydrated via \`loadPassport()\` regardless.

## UI Location

The Passport interface lives inside \`Nucleos > Authenticity > PASSPORT\` tab. It is the default sub-tab when opening the Authenticity settings category.

Files:
- \`apps/youmeos/app/sparks/settings/views/PassportView.vue\`
- \`apps/youmeos/app/sparks/settings/views/AuthenticityView.vue\` (parent)
`,se='# Google Analytics Integration\n\n## Overview\n\nYouMeOS uses Google Analytics 4 (GA4) via `gtag.js` with tracker ID `G-YHY2WZFMDM`.\n\nAll analytics are routed through `apps/youmeos/mechanics/useAnalytics.ts` — a centralized module that:\n\n1. Checks cookie consent from Cookie Jar before firing events\n2. Provides typed helper functions for each event category\n3. Guards against missing `window.gtag` (dev mode, ad blockers)\n\n## Event Catalog\n\n### Auth Events\n| Event | Function | Trigger Location |\n|---|---|---|\n| `login` | `trackLogin(method)` | `useAuth.login()`, `LoginView` OAuth watcher |\n| `sign_up` | `trackSignUp(method)` | `useAuth.register()` |\n| `logout` | `trackLogout()` | `useAuth.logout()` |\n| `password_reset_requested` | `trackPasswordReset()` | `useAuth.requestPasswordReset()` |\n\nMethods: `"credentials"`, `"discord"`, `"google"`\n\n### Navigation Events\n| Event | Function | Trigger Location |\n|---|---|---|\n| `page_view` | `trackPageView(path, title)` | `router.afterEach` guard |\n\n### Welcome Screen Events\n| Event | Function | Trigger Location |\n|---|---|---|\n| `welcome_action_click` | `trackWelcomeAction(action)` | `WelcomeLanding.vue` CTA handlers |\n| `fullscreen_prompt_response` | `trackFullscreenPrompt(accepted)` | `WelcomeLanding.handlePromptResponse` |\n\nActions: `"weave_youniverse"`, `"tour_youniverse"`, `"enter_youniverse"`, `"tesseract_youmeverse"`\n\n### Engagement Events\n| Event | Function | Trigger Location |\n|---|---|---|\n| `tour_started` | `trackTourStarted()` | `useTourGuide.startTour()` |\n| `tour_ended` | `trackTourEnded(completed)` | `useTourGuide.endTour()` |\n\n### Media Events\n| Event | Function | Trigger Location |\n|---|---|---|\n| `audio_play` | `trackAudioPlay(stationId)` | `useAudio.playTrack()` |\n| `audio_station_change` | `trackStationChange(id, title)` | `useAudio.setTrack()` |\n\n### Migration Events\n| Event | Function | Trigger Location |\n|---|---|---|\n| `migration_started` | `trackMigrationStarted(url, scope, isDry, tables)` | `moving-castle.controller.ts` on import start |\n| `migration_completed` | `trackMigrationCompleted(params)` | `moving-castle.controller.ts` on import success |\n| `migration_failed` | `trackMigrationFailed(url, dur, err, isDry)` | `moving-castle.controller.ts` on import error |\n\nParams for `migration_completed`: `sourceUrl`, `durationSeconds`, `tablesMigrated`, `rowsMigrated`, `isDryRun`, `scope`\n\n### Privacy Events\n| Event | Function | Available For |\n|---|---|---|\n| `cookie_consent_update` | `trackConsentUpdate(a, p, m)` | Cookie Jar spark |\n\n## Cookie Consent\n\nAll events check `youmeos_consent` cookie before firing. If the cookie\'s `analytics` field is `false`, no events are sent. If the cookie is absent (pre-consent), events are allowed by default.\n\n## Adding New Events\n\n1. Add a typed export function to `useAnalytics.ts`\n2. Import and call it at the relevant trigger point\n3. Add to this doc\'s Event Catalog\n4. Re-export via `mechanics/index.ts` (already done via barrel)\n\n## Key Files\n\n- `apps/youmeos/mechanics/useAnalytics.ts` — central module\n- `apps/youmeos/routes/router.ts` — SPA page_view guard\n- `src/mechanics/useAuth.ts` — auth event triggers\n- `apps/youmeos/routes/welcome/WelcomeLanding.vue` — welcome CTA triggers\n- `apps/youmeos/mechanics/useTourGuide.ts` — tour lifecycle triggers\n- `apps/youmeos/mechanics/useAudio.ts` — media event triggers\n- `apps/youmeos/index.html` — gtag.js script tag\n',le=`# Authenticity & Licensing System

## Overview
The Authenticity & Licensing system manages user sovereignty tiers and digital credentials within the YouMeOS ecosystem. It provides a visual interface for license activation and tier management.

## Sovereignty Tiers
YouMeOS uses a four-tier "Box" licensing model:

1. **BlackBOX (Entry)**
   - Entry-level sovereignty for individual agents.
   - Standard Sparks access & Community support.
   - Manual node synchronization.
   - Price: $14.99/mo

2. **BronzeBOX (Pioneer)**
   - Enhanced telemetry and stable core hosting.
   - Advanced protection & heuristics.
   - Priority Forge access.
   - Automatic node recovery.
   - Price: $34.99/mo

3. **SilverBOX (Navigator)**
   - High-tier performance for professional digital navigators.
   - Full Galaxy hosting capabilities.
   - Multi-core synchronization.
   - Unlimited Sparks access.
   - Price: $74.99/mo

4. **GoldBOX (Architect)**
   - The ultimate Youniverse experience. Whiteglove infrastructure.
   - Dedicated Node Instance & Custom Domain Mapping.
   - Alpha Feature Access.
   - Price: $129.99/mo

## Implementation Details
- **State Management**: Handled via \`CompassStore\` (\`src/routes/compass/store/index.ts\`).
- **Persistence**: License keys and tiers are persisted to \`localStorage\` to survive session restarts.
- **UI Components**: 
  - \`AuthenticityView.vue\`: Main settings module for licensing.
  - \`AuthenticityView.scss\`: Glassmorphic styles and status indicators.

## License Activation
Licenses are activated by entering a key in the Authenticity settings or synced via the w4.youmeos.com identity bridge.
- Keys starting with \`BLCK-\` grant **BlackBOX** status.
- Keys starting with \`BRNZ-\` grant **BronzeBOX** status.
- Keys starting with \`SLVR-\` grant **SilverBOX** status.
- Keys starting with \`GOLD-\` grant **GoldBOX** status.

## Future Plans
- **Backend Verification**: Integration with Hall of the Gods auth servers for cryptographic key validation.
- **Feature Gating**: Dynamically enabling/disabling Sparks based on the current license tier.
- **Auto-Renewal**: Integration with Patreon API for real-time status updates.
`,de=`# Sovereignty Box Architecture & Value Ladder

## Overview
This document outlines the 8-tier "Box" ecosystem designed to scale YouMeOS into a high-fidelity, customer-funded infrastructure. Every tier is architected to provide increasing levels of **Sovereignty, Performance, and Redundancy**.

---

## 💎 The Value Ladder

### Phase 1: The Personal Layer (Entry & Basic)
*Target: Individual users, hobbyists, and digital citizens.*

#### ⬛ BlackBOX ($14.99/mo) - "The Entry Node"
*   **Interface:** Black Compass
*   **Infrastructure:** Dedicated WP Environment
*   **Specs:** 10GB SSD Storage | 512MB RAM / 1 vCPU
*   **Included Sparks:** Passport, Identity, Neon-Notes, Notepad, Free SSL.
*   **Gating:** Limited to 1 personal instance. Standard "YouMeOS" branding.

#### 🟫 BronzeBOX ($34.99/mo) - "The Pioneer Node"
*   **Interface:** Bronze Compass
*   **Infrastructure:** Dedicated WP Environment
*   **Specs:** 25GB SSD Storage | 1GB RAM / 1 vCPU
*   **Included Sparks:** All BlackBOX + \`Magic-Formula\` (Pro Form Builder), \`Shrink-Ray\` (Image Opt), Analytics.
*   **Gating:** Basic telemetry metrics unlocked.

---

### Phase 2: The Professional Layer (Growth & Productivity)
*Target: Creators, small business owners, and active digital navigators.*

#### ⬜ SilverBOX ($74.99/mo) - "The Navigator Node"
*   **Interface:** Silver Compass
*   **Infrastructure:** Dedicated WP Environment
*   **Specs:** 65GB NVMe Storage
*   **Included Sparks:** All BronzeBOX + \`Brand-Unicorn\` (White-Labeled Admin), \`Lead-Magnet\` (Marketing Pop-ups), \`Pro-Force-Field\` (Security), \`Lens-of-Truth\` (Uptime), Automated Reports.
*   **Gating:** Optimized for high-fidelity content creation.

#### 🟨 GoldBOX ($129.99/mo) - "The Architect Node"
*   **Interface:** Gold Compass
*   **Infrastructure:** Multisite Capable Environment
*   **Specs:** 160GB NVMe Storage | 8GB RAM / 4 vCPUs
*   **Included Sparks:** All SilverBOX + \`Bazaar\` (Commerce w/ Conversion tracking), Health Check (Quarterly Audit), Auto-Healing.
*   **Gating:** Priority Routing (Senior Support). Network Admin control.

---

### Phase 3: The Infrastructure Layer (Power & Scale)
*Target: Developers, agencies, and high-frequency data nodes.*

#### 💠 PlatinumBOX ($299/mo) - "The Sovereign Node"
*   **Interface:** Platinum Compass
*   **Infrastructure:** Multisite Capable Environment
*   **Specs:** 320GB NVMe Storage | 16GB RAM / 6 vCPUs
*   **Included Sparks:** All GoldBOX + Platinum Compass Suite.
*   **Gating:** Unrestricted compute resources for WebGL/WebGPU rendering.

#### ☢️ UraniumBOX ($650/mo) - "The Nuclear Node"
*   **Interface:** Uranium Compass
*   **Infrastructure:** Enterprise Multisite Ready
*   **Specs:** 640GB NVMe Storage | 32GB RAM / 8 vCPUs
*   **Included Sparks:** All PlatinumBOX + Uranium Compass Suite.
*   **Gating:** High-Priority Telemetry pipelines. Raw API access.

---

### Phase 4: The Elite Layer (Redundancy & Sovereignty)
*Target: Enterprise, organizations, and the "Hall of the Gods" elite.*

#### 🔘 TitaniumBOX ($1250/mo) - "The Guardian Node"
*   **Interface:** Titanium Compass
*   **Infrastructure:** Enterprise Multisite Ready
*   **Specs:** 1.2TB NVMe Storage | 64GB RAM / 16 vCPUs
*   **Included Sparks:** All UraniumBOX + Titanium Compass Suite.
*   **Gating:** Multi-Region Redundancy. Sovereign Vault Recovery.

#### 🔷 PalladiumBOX ($2499/mo) - "The Celestial Node"
*   **Interface:** Palladium Compass
*   **Infrastructure:** Unlimited Network Scaling
*   **Specs:** 1.6TB NVMe Storage | 96GB RAM / 24 vCPUs
*   **Included Sparks:** All TitaniumBOX + Palladium Compass Suite.
*   **Gating:** Critical Response (< 1 Hour SLA). Full White-Label Federation.

---

## 🛠️ Technical Execution: Feature Gating

The \`CompassStore\` will enforce these tiers using a **Waterfall Logic**:
1.  **Identity Fetch:** On login, \`getCurrentUser()\` pulls \`license_tier\`.
2.  **Capability Map:** The OS maps the tier to a bitmask of allowed Sparks and Infrastructure flags.
3.  **UI Feedback:**
    *   **Locked Sparks:** Appear with a subtle "Tier Lock" icon and a direct link to \`w4.youmeos.com\` for upgrade.
    *   **Active Tier Glows:** The \`AuthenticityView\` renders the specific glow (e.g., Uranium Green or Gold Amber) to provide immediate status feedback.

## 📈 Infrastructure Scaling

This architecture ensures that as the user's need for **Sovereignty** grows, the system scales to provide the necessary resources, redundancy, and security. By productizing the "Box" as a container for digital life, we provide a clear path for users to invest in their own private, high-fidelity digital ecosystem.
`,ce=`# Dirt: The Distributed Digital Mineral Economy

## Overview
Dirt is the foundation of the YouMeOS gamified economic engine. Moving away from the exclusionary, USD-pegged, hyper-financialized models of traditional crypto, YouMeOS grounds its tokenomics in a "Digital Mineral" hierarchy. 

## 1. The Global Unit Peg: "Dirt Cheap"
The base currency of YouMeOS is literally called **Dirt**. 

### The Earth Cent & Algorithmic Stability
Dirt is mathematically pegged to a "Global Average Unit" (an aggregate index of all granular global fiat currencies). 

**How does it hold its value?**
It operates as an **Algorithmic Stablecoin** powered by decentralized "Oracles" and the Synthesis Engine.
1. **The Global Oracle (The Seers):** A decentralized network of data-fetchers constantly pulls the live foreign exchange (FX) rates of all granular world currencies (USD, EUR, JPY, INR, NGN, etc.). The smart contract calculates the exact average value across all these currencies in real-time. This is the "Earth Cent" target price.
2. **Elastic Supply (The Mint/Burn Cycle):** 
   - **If Dirt is too expensive (High Demand):** The smart contract automatically mints *more* Dirt. It increases the "drop rate" for users performing daily tasks or sells it into liquidity pools. The increased supply brings the price back down to the exact Earth Cent average.
   - **If Dirt is too cheap (Low Demand):** The system incentivizes "Synthesis." It offers highly profitable blueprints for users to compress their Dirt into Quartz or Gems. This acts as a massive "burn" mechanism. As users burn their Dirt, the circulating supply shrinks, driving the price back up to the exact Earth Cent average.

- It is untethered from the US Dollar or any single nation's monetary policy.
- Its value is designed to be around the equivalent of a penny, making it "dirt cheap" and psychologically frictionless to spend.
- It acts as an egalitarian universal translator for human value.

### Utility vs. Hoarding
Because Dirt is tied to a global mathematical average, speculators cannot pump its price to the moon. Its purpose is high-velocity utility: tipping, micro-transactions, unlocking features, and acting as the foundational raw material for synthesis.

## 2. The Two-Tiered Economy & Transmutation
To protect the real-world value of Dirt from botting and exploitation, the YouMeOS economy is split into two layers: the Internal Gamification Layer (Off-Chain) and the Cryptographic Layer (On-Chain).

### The Internal Layer (The Arcade & Social Tipping)
Within the OS, users earn internal, off-chain points. This allows for fast, free, and highly granular gamification without incurring blockchain gas fees. But these are not just hoarded—they are the **social currency of the network**.

**The Granular Value of GP: Emotional Ledgers & Reactions**
At its absolute base level, one primary way to earn Game Points (GP) is through human interaction—specifically, the "click" of an emotional reaction. It is important to note that *GP is not cryptocurrency*; it is the internal, off-chain gamified social currency. 
*   **The Emoji Exchange:** The system acts as an exchange that "buys" an emoji reaction from a user for exactly 1 GP (though conversion scales may become dynamic based on demand). This is a purely gamified transaction to encourage network activity.
*   **The Emotional Ledger (Anti-Bloat Architecture):** To track which emotions a user is "selling" without causing massive database bloat (e.g., writing a new row for every single click in MySQL), YouMeOS utilizes a **Rolling JSON Tally**. Instead of logging every transaction permanently, the server simply updates an aggregated JSON object on the user's metadata (e.g., \`{"❤️": 402, "🔥": 12, "😭": 55}\`). This maintains a lightweight "Emotional Ledger" without the server overhead. (Note: Future iterations may pipe this raw click data through a decentralized p2p network like Gun.js or Nostr relays, keeping local DB footprint at zero while creating a global emotional graph).
*   **Empathy Detection:** By reading this lightweight JSON ledger, the system tracks what emotions a user is expressing the most. If a profile is predominantly selling sad or distressed emojis, the system can algorithmically identify that the user might be depressed and proactively route uplifting content, community support, or "light" to their feed.

**Peer-to-Peer Content Grading:**
The Khepri Ladder acts as a unified tipping mechanism. Instead of generic "likes," users grade content by directly transferring their assets. This masks the complex math behind fun, human interactions:
*   🪙 **1 GP (Reaction):** A basic nod of approval or emotional expression.
*   ⭐ **1 Life (1-UP):** Great content (Transfers 100 GP).
*   ❤️ **1 Heart (Love):** Deep appreciation (Transfers 4 Lives).
*   ✨ **1 Fairy (Bless):** Top-tier contribution (Transfers 5 Hearts).
*   ☄️ **1 Wish (Grant):** Life-changing insight (Transfers 7 Fairies).
*   🐞 **1 Scarab (Forge):** Unparalleled value.

When a creator's post goes viral, they accumulate these assets directly from the community, organically building their swarm of Golden Scarabs to eventually Transmute into real-world wealth.

**The Labor & Service Protocol (P2P Bartering):**
Wealth generation is not limited to algorithmic rewards and social tipping. YouMeOS acts as a borderless digital marketplace where users can directly trade their real-world skills, labor, and services in exchange for the ecosystem's assets. 
*   A developer can code a custom Spark for a business and be contracted for 500 Golden Scarabs.
*   An artist can design a logo or digital asset in exchange for Wishes or Fairies.
*   This creates a robust, closed-loop bartering system. Users can hire global talent without ever needing to convert to fiat currency. This gives immediate, intrinsic, real-world value to the gamified assets simply based on the human labor they can purchase.

### The Cryptographic Layer (Transmutation)
**Dirt** is the actual, on-chain cryptocurrency. You cannot "farm" Dirt directly. Instead, you must **Transmute** your internal gamified assets into Dirt through a strict cryptographic gateway known as **The Alchemist Protocol**.

To successfully sign a transaction that mints Dirt on the exchange, a user must satisfy four non-negotiable vectors. This guarantees that only proven, active human contributors can extract real-world value, acting as an absolute firewall against bot networks:

1.  **Material Cost:** The user must provide the exact aggregate gamified material (e.g., exactly 500 Golden Scarabs to forge 1 Dirt).
2.  **The Reputation Floor:** The user must hold a minimum threshold of lifetime **Reputation Points (RP)** to even access the forge. If your RP is too low, the system considers you an unverified actor or a bot.
3.  **The Reputation Burn:** Transmutation is an alchemist transmutation process. Executing the mint permanently burns a specific amount of RP (\`-X RP\`). You are literally spending your built-up community trust to extract financial value.
4.  **The Velocity Gate (Alchemist Status):** You cannot just hoard RP and go dormant. Transmutation requires current **Ability Point (AP) Velocity**. You must have generated a specific amount of AP within a rolling time period leading up to the mint. 
    *   *The Scaling Rule:* The larger the Dirt payout you are attempting to mint, the higher the required AP velocity, and the longer the sustained time period must be. You must prove you are *currently* active and contributing to the network.

## 3. The Apex Minerals & The Anti-1% Protocol
Once a user holds Dirt, they have entered the true on-chain economy. However, Dirt is merely the foundation. To lock in a base economics for the core system and prevent runaway inflation, users can transmute Dirt under extreme pressure into higher-tier **Apex Minerals** (e.g., Quartz, Rubies, Emeralds, Diamonds). 

**Strict Tier Caps:**
Every tier above Dirt has a mathematically hard-capped global supply. The higher the value of the mineral, the fewer can ever exist.

**The Anti-1% Gating Mechanism:**
To solve the modern global wealth disparity (where the top 1% simply buys up all valuable assets), the highest valued tokens (The Core Crystals) cannot simply be purchased with fiat liquidity. 
*   They are heavily gated behind the Alchemist Protocol. 
*   Even if a billionaire attempts to buy all the Dirt, they cannot transmute it into Core Crystals without an immense, sustained **Reputation (RP)** and actual network **Proof-of-Contribution (AP)**. 
*   They are held in the "System Treasury" (The Mantle/Core) and distributed *only* by the system protocol to users who actively run hosting nodes, develop features, and maintain the network.

By aggressively gating the highest tiers of wealth behind human reputation and contribution, YouMeOS aims to create a singular, balanced global currency structure that permanently prevents a financial oligarchy.
*   These top-tier tokens represent network equity and governance power, rather than just spending power.

## 4. The "Pay Dirt" Experience
When a user hits a milestone or their hosted node processes a batch, they "hit pay dirt." The terminology is universally understood, approachable, and strips away the elitism of typical Web3 jargon. It makes building a network fun, grounded, and rewarding.
## 5. Macro-Economic Safeguards & The Hardware Layer (DePIN)
To ensure this digital economy interacts flawlessly with the real world, YouMeOS relies on physical hardware, localized inflation algorithms, and fiat liquidity bridges.

### The Fiat Bridge: The Blackbox & WWWW
A digital currency only survives if real-world capital backs it. YouMeOS solves the "Cold Start Liquidity" problem through its hardware ecosystem: **The Blackbox** and the **WWWW** (World Wide Web Wallet).
*   **The Hardware Subsidy:** When a user purchases a physical Blackbox hosting node, **10% of that fiat purchase** is injected directly into the YouMeOS Liquidity Pool (LP) to back the value of Dirt.
*   **The Point Rebate:** The user receives **5% of their purchase back as GP**, instantly giving them social ammunition.
*   **The Baseline Peg:** This hardware influx establishes a foundational mathematical floor. At inception, the baseline peg is engineered so that **1 Dirt (42,000,000 GP) = $0.01 USD**, fully backed by actual hardware sales. As the network scales and more capital enters via marketplace gas fees, this floor rises.

### Decentralized Consensus of Value (The Sunburst)
The system does not arbitrarily decide what is "valuable." The users do.
*   Because transferring assets (Hearts, Fairies) costs the user their own hard-earned points, the act of tipping is an un-fakeable cryptographic proof of value.
*   The system tracks this flow of wealth using a **Sunburst Wheel** algorithm, which aggregates global sentiment to determine what the world currently finds valuable, adjusting Reputation (RP) yields accordingly.

### Algorithmic Geo-Balancing (Anti-Inflation)
A core tenet of YouMeOS is that a farmer running a node in rural India provides the exact same cryptographic value to the network as a software engineer in Silicon Valley. 
*   If a user resides in a country suffering from hyperinflation or economic collapse, the YouMeOS global wallet algorithm detects this disparity.
*   It dynamically adjusts and increases the value weight of their payouts. By paying out based on equalized global purchasing power rather than arbitrary USD exchange rates, the system actively fights local poverty and balances the global economic structure.

### DePIN & The DAO Core Crystals
YouMeOS is a **Decentralized Physical Infrastructure Network (DePIN)**. The WordPress/PHP core and Vue frontend literally live on the Blackboxes purchased by users.
*   Every person buying a Blackbox physically creates and expands the system.
*   Because users provide the actual hosting infrastructure, they are rewarded with the highest tier of the economy: **The Core Crystals**.
*   **The 100-Year Governance:** The Core Crystals are not just wealth; they are the ultimate **DAO Tokens**. Holding Core Crystals grants governance voting rights over the core operating system, allowing the proven, decentralized network of hosters to guide the upgrades of digital civilization for the next 100 years.
`,ue=`# The Godhead Progression: Thoth, Qabalah, & The 10-Layer Lineage

## Overview
To capture the true complexity of human nature, the YouMeOS gamification engine uses a 10-layer deep fractal binary tree. This structure is perfectly aligned with the Hermetic Qabalah and Aleister Crowley's *Book of Thoth*. 

It synthesizes the 12 Zodiac archetypes, the 4 Elements, the 7 Classical Planets, and the 10 Sephiroth (Minor Arcana) into a single, scalable mathematical database of human action.

---

## 1. The Math of the Granular Seed
We start with the 12 Zodiac roots. From there, we move 10 layers deep, branching in a binary (dualistic) split at every layer representing the Qabalistic Pillars (Force/Expansion vs. Form/Restriction).

*   **Root:** 12 Zodiacs
*   **Layer 1 (The Aces):** 12 × 2 = 24 nodes
*   **Layer 2 (The Twos):** 48 nodes
*   **Layer 3 (The Threes):** 96 nodes
*   **Layer 4 (The Fours):** 192 nodes
*   **Layer 5 (The Fives):** 384 nodes
*   **Layer 6 (The Sixes):** 768 nodes
*   **Layer 7 (The Sevens):** 1,536 nodes
*   **Layer 8 (The Eights):** 3,072 nodes
*   **Layer 9 (The Nines):** 6,144 nodes
*   **Layer 10 (The Tens):** 12,288 nodes

**Total Granular Leaves: 12,288 seeds.** 
This is the absolute edge of the human profile. An AI can easily map modern human tasks to one of these 12,288 specific esoteric states.

---

## 2. The Planetary Integration (Solving the 7 Planets)
In Hermetic Qabalah, the 7 classical planets do not need to be arbitrarily placed. They are mathematically locked into the Sephiroth themselves. The 10 Layers naturally account for all 7 planets:

1.  **Layer 1 (Kether / Aces):** Primum Mobile (The First Swirlings / Pure Element)
2.  **Layer 2 (Chokhmah / Twos):** The Zodiac (The Fixed Stars)
3.  **Layer 3 (Binah / Threes):** Saturn 🪐
4.  **Layer 4 (Chesed / Fours):** Jupiter ♃
5.  **Layer 5 (Gevurah / Fives):** Mars ♂
6.  **Layer 6 (Tiferet / Sixes):** The Sun ☉
7.  **Layer 7 (Netzach / Sevens):** Venus ♀
8.  **Layer 8 (Hod / Eights):** Mercury ☿
9.  **Layer 9 (Yesod / Nines):** The Moon ☽
10. **Layer 10 (Malkuth / Tens):** The Earth / Physical Elements 🌍

---

## 3. Creating the Attribute Names (The Naming Formula)
When generating the name and meaning for a specific node in this 12,288-seed database, the AI combines the attributes mathematically, following the birth of the element.

**The Naming Formula:** 
\`[Zodiac Archetype] + [Binary Pillar] + [Sephiroth Number & Its Planet]\`

### Example: Journeying down the Aries ♈ (Fire) Lineage

*   **Root:** ♈ The Emperor (Aries / Fire)
*   **Layer 1 (Ace / Kether):** Splits into 2. 
    *   *Path A (Force):* "The Active Spark of the Emperor" 
    *   *Path B (Form):* "The Receptive Spark of the Emperor"
*   **Layer 2 (Two / Chokhmah - Wisdom):** Splits into 4. We are on *Path A (Force)*. 
    *   *New Path A:* "The Active Wisdom of the Emperor" 
*   **...Skipping to Layer 5 (Five / Gevurah - Severity / Mars):** 
    *   At this layer, the attribute is injected with **Mars** (Action, Strife, Discipline). The attribute name becomes a synthesis of *Aries*, *Active Force*, and *Martian Severity*. This maps directly to the **5 of Wands (Strife)** in the Book of Thoth.
    *   *Granular Stat Name:* "Active Martian Discipline (Emperor Lineage)"
*   **...Skipping to Layer 10 (Ten / Malkuth - Earth):**
    *   At the final layer, the energy manifests physically. It represents the **10 of Wands (Oppression/Completion)**. 
    *   *Granular Stat Name:* "Physical Embodiment of the Emperor's Fire"

By mapping this way, every single one of the 12,288 nodes has a mathematically precise esoteric definition based on its Parent Zodiac, its Binary Path (Force vs. Form), and its Layer (Planet/Number).

---

## 4. The DAO Governance Model (Chassis vs. Leaves)
YouMeOS scales infinitely with human progress while maintaining its ancient roots. To achieve this, the 10-layer progression system is divided into two distinct governance domains:

### The Inner Chassis (Layers 1-9): Hardcoded & Governed
The first 9 layers (from Kether to Yesod) represent the unchanging, foundational blueprints of the universe. This forms the "Inner Chassis" of the YouMeOS engine.
*   These rules, planetary attributions, and the **144 core sockets** (12x12) are hardcoded.
*   **Immutability:** Changing the Inner Chassis requires a monumental DAO governance vote using Core Crystals. You do not change the laws of physics without global consensus.

### The 10th Layer (Malkuth): Infinite & Open-Source
Malkuth (The 10th Layer) is the physical manifestation. In Qabalah, this is the chaotic, expansive, and infinitely evolving material world.
*   This layer is **not locked to 144**. It can hold 144,000 or 14 million granular categories.
*   When humanity invents a new skill (e.g., "Quantum Computing"), the community does not need to rewrite the universe. They simply vote on which of the 144 "Sockets" at Layer 9 (e.g., *Aquarius/Uranus/Air*) to plug the new skill into.
*   This grants YouMeOS the stability of an ancient temple and the infinite scalability of a modern wiki.
`,he=`# The W4 Protocol: A 4D Fractal Economic Operating System

## Overview
The W4 Protocol is the unified economic, governance, and logistical operating system of YouMeOS. It is not a feature — it is the protocol itself. Built on the four sovereign pillars (Own Your Infrastructure, Private Compass, Sovereign Data, Distributed Networking), W4 transforms money from a tool of extraction into a gamified, self-stabilizing, fractal system designed to close the global wealth gap permanently.

## 1. The Axiom X Foundation
*"Numbers are the absolute, universal language of existence. Awaken your awareness, locate where the grand symphony is out of tune, and actively bring it back into resonance."*

The W4 economy is built on the Triforce of the Gods axioms:
- **Axiom I (The Sovereign Architecture):** Two opposing forces clash until a third stabilizes them. The economy uses a triangular DAO structure where no single branch can overrule the other two.
- **Axiom III (The Fractal Network):** The identical structures that bind galaxies also bind atoms. The economy is fractal — the same patterns of effort → compression → value repeat at every scale.
- **Axiom VII (The Kinetic Forge):** Sight without movement is a hallucination. Value is only created through action (Proof of Contribution).

## 2. The Two-Layer Economy

### Layer 1: The Fluid Layer (Internal / Off-Chain)
The day-to-day gamified currency of participation. Fast, free, and frictionless.

**The Khepri Ladder (Social Tipping):**
- 🪙 **1 GP (Reaction):** A basic nod of approval or emotional expression.
- ⭐ **1 Life (1-UP):** Great content (= 100 GP).
- ❤️ **1 Heart (Love):** Deep appreciation (= 4 Lives).
- ✨ **1 Fairy (Bless):** Top-tier contribution (= 5 Hearts).
- ☄️ **1 Wish (Grant):** Life-changing insight (= 7 Fairies).
- 🐞 **1 Scarab (Forge):** Unparalleled value. The gateway to on-chain transmutation.

### Layer 2: The Solid Layer (On-Chain / The Mineral Economy)
The permanent, cryptographic layer. Each mineral tier represents a specialized DAO governing a specific branch of the system.

## 3. The Mineral Transmutation Tree & DAO Governance

### The Chain
| Mineral | Requires | DAO Domain | Supply |
|---|---|---|---|
| **Dirt** | 500 Scarabs + Alchemist Protocol | Base on-chain currency | Elastic (Algorithmic Stablecoin) |
| **Quartz** | 100 Dirt | Community & Local DAO | Effort-Bound |
| **Emerald** | 10 Quartz | Ecology & Harmony DAO | Effort-Bound |
| **Ruby** | 5 Emeralds | Energy & Infrastructure DAO | Effort-Bound |
| **Sapphire** | 4 Rubies | Knowledge & Education DAO | Effort-Bound |
| **Diamond** | 3 Sapphires | Code & Ledger Logic DAO | Effort-Bound |
| **Gold** | Special (Fiat Bridge) | The Mantle Credit Protocol | Elastic (Treasury-Backed) |
| **Core Crystal** | 2 Diamonds | Apex Macro-Vision DAO | Effort-Bound |

### Effort-Bound Supply (No Hard Caps)
Unlike Bitcoin's static 21M cap, Apex Minerals have NO hard-coded supply limit. Instead, they are gated by exponentially increasing effort requirements (RP burn, AP velocity, sustained contribution time). The supply naturally forms a pyramid — not because the system locks people out, but because the grind gets steeper. Anyone CAN reach Diamond. Not everyone WILL. Just like a PhD — there is no cap on doctorates, but effort naturally filters the population.

### The Alchemist Protocol (Anti-Bot Firewall)
Every on-chain transmutation requires four non-negotiable vectors:
1. **Material Cost:** The exact aggregate gamified material.
2. **Reputation Floor:** Minimum lifetime RP to access the forge.
3. **Reputation Burn:** Permanently burns RP. You spend trust to extract value.
4. **Velocity Gate:** Recent AP activity within a rolling time window. Proves you are currently active.

## 4. Universal Resonance Voting & The Trinity fX Structure

To prevent the formation of an oligarchy—even one based on labor—governance is not restricted to Apex Mineral holders. **Voting power is universal and distributed throughout the entire system.** Every citizen has a voice, regardless of wealth or mineral status.

### The Sunburst Resonance Multiplier
Because every citizen runs their own YouMeOS node, the system tracks an internal, cryptographically secure alignment metric: **The Sunburst Chart**. 
- Votes are not bought with Gold, nor are they strictly dictated by raw labor output.
- Instead, a citizen's voting weight is calculated using the **Resonance Multiplier**—a metric derived from how balanced their Sunburst chart is. 

**The AI Engine & Anti-Gaming:** 
You cannot "game" this by speed-running positive tasks. When an action is logged, the core AI parses the true context of the action and mathematically distributes the energy across the 12,288-node Qabalistic stat chart (The Godhead Progression). The system measures authentic systemic spread, not repetitive clicking.

**The Twilight Realm (Integrating the Shadow):** 
"Balance" in YouMeOS does not mean toxic positivity. The Sunburst chart requires walking in the realm of twilight—balancing both light and shadow. 
- The system actively rewards and holds space for those who are depressed, angry, or at rock bottom. 
- A citizen authentically expressing grief or struggle in a designated space (e.g., a "Rock Bottom" forum) earns empathy, RP, and connection just as surely as someone building a server does.
- By rewarding the authentic processing of the human shadow, no one is left alone. The network metabolizes suffering instead of repressing it, which consistently raises the true baseline value of the entire ecosystem.

### The Three Branches (Proposal & Curation)
While voting is universal, the Apex Minerals define which branch of the Trinity fX structure a citizen uses to *propose* and *curate* system upgrades. No single branch can overrule the other two:
- **The Labor Branch (Ruby + Emerald Focus):** The doers. They propose physical resource allocation, node deployment, and infrastructure upgrades.
- **The Wisdom Branch (Sapphire + Diamond Focus):** The philosophers and engineers. They propose system architecture, code upgrades, and long-term vision.
- **The Arbitration Branch (Core Crystal Focus):** The stabilizing third point. They curate resolutions for deadlocks between Labor and Wisdom, ensuring proposals align with the core tenets of the Omega Point.

### The Check System
- Labor can veto Wisdom proposals if resources are physically unavailable.
- Wisdom can veto Labor proposals if execution violates long-term systemic health.
- Arbitration curates tie-breakers—but the final execution always requires a **Universal Resonance Vote** by the entire network.

## 5. The Mantle Credit Protocol (Closing the Wealth Gap)

### The Core Philosophy
There IS enough wealth in the world. It is just poorly distributed. The W4 Protocol's job is to fix that by acting as a universal logistical engine — the "Parent" of the global family.

### How It Works

**Scenario:** A citizen needs $10k USD for a lawyer. They don't have it.

**Step 1: Automatic Approval**
The citizen requests $10k from The Mantle (the system treasury). The system automatically approves it. No credit score. No gatekeeping. The system's philosophy: if you are a participating member of the network, the network supports you.

**Step 2: The System Pays the Lawyer**
The lawyer receives $10k USD directly from The Mantle's liquidity pool (funded by Blackbox hardware sales, marketplace gas fees, and network revenue). Real dollars. The lawyer is fully paid. They do not need to understand or interact with the mineral economy.

**Step 3: The Gold Option (Optional Accelerator)**
The system can offer the lawyer a choice:
- Accept $10k USD now, OR
- Accept $20k in YouMeOS Gold (2x premium in ecosystem currency).
If the lawyer takes the Gold, The Mantle preserves its USD liquidity. If they want cash, they get cash. Zero friction.

**Step 4: Organic Debt Settlement**
The citizen now carries a $10k value record with The Mantle. This is NOT a traditional loan:
- No interest. No deadlines. No credit score damage.
- Every day the citizen participates (logging tasks, creating content, running a node, tipping others), they generate GP/XP.
- That activity generates network value (more users, more data, more uptime).
- The system gradually writes down the debt as contribution accumulates.
- Over time, participation IS the repayment.

### The Escrow Protocol (Zero-Leak Transactions)
When Gold is offered to external parties:
- Gold is locked in a smart contract escrow, NOT burned.
- The counterparty receives a time-limited offer.
- If accepted: USD transfers, Gold unlocks. Zero leak.
- If declined: Gold returns to the system. Nothing was burned.
- The system learns the market confidence rate for Gold in that region and algorithmically adjusts future offer ratios.

## 6. The Retention Protocol (The Single Point of Failure)

The ONLY leak in the entire system is user dropout. If someone takes $10k and stops participating, the system absorbs a loss. The W4 Protocol's response to dropout is the OPPOSITE of traditional debt collection — the system invests MORE in the person, not less.

### The Emotional Ledger (Detection Layer)
The system maintains a lightweight Rolling JSON Tally of each user's emotional reactions (e.g., \`{"❤️": 402, "🔥": 12, "😭": 55}\`). This is not surveillance — it is empathy infrastructure.

**Detection Triggers:**
- User activity drops below threshold → system flags
- Emotional Ledger shows spike in distressed reactions → system escalates
- Outstanding Mantle Credit + low activity = **"At Risk" status**

### The Response Protocol (Not Punishment — Support)
1. **Automated Outreach:** The system reaches out through the OS. Not a collections notice — a genuine wellness check.
2. **Debt Counseling Spark:** Routes the user to a financial wellness module. Helps them understand their debt isn't a chain — it's a number that participation naturally erases.
3. **Therapy / Mental Health Routing:** If the Emotional Ledger signals distress, the system connects them to community counselors or professional services (also covered by The Mantle).
4. **Reduced Participation Pathways:** Lighter ways to stay engaged — a 5-minute journal entry, a single reaction, just logging in. Any pulse of activity keeps the relationship alive.

### Why This Works Economically
- Cost of $10k debt write-off from dropout: **-$10k**
- Cost of $500 in counseling to retain the user: **-$500**
- Value of that user staying active for 5+ years: **+$50k+**
- It is ALWAYS cheaper to keep the person than to lose them.
- Empathy becomes an economic imperative.

## 7. The 4D Fractal Money System

The W4 economy operates across four dimensions, each containing the fractal pattern of the one below it:

- **1D: GP/XP (Time)** — Linear human effort over time. The base dimension.
- **2D: Dirt (Space)** — On-chain coordinates. Your labor mapped to a permanent ledger.
- **3D: Apex Minerals (Volume)** — Crystallized, compressed accumulation of effort. Depth of contribution.
- **4D: Gold / The Fiat Bridge (Temporal Bridge)** — The dimension that connects the digital mineral economy to the real-world fiat timeline. Gold exists across both worlds simultaneously.

Each dimension contains the same pattern: effort → compression → value. Zoom into any level and the fractal repeats.

## 8. The W4 Infrastructure Pillars

| W4 Pillar | Economic Function |
|---|---|
| **Own Your Infrastructure** | Cloud hosting nodes (e.g., Enterprise AWS Elastic Cloud) → hosting sales → Liquidity Pool → The Mantle has real USD to lend |
| **Private Compass Included** | YouMeOS itself → the interface where citizens log XP, earn GP, and interact with the economy |
| **Sovereign Data Storage** | Emotional Ledger, XP history, proof-of-contribution — stored on YOUR node, not a corporate server |
| **Distributed Networking** | The mesh of nodes IS the DAO. No central bank. The network is the treasury. |

### Network Synchronization (The W4 Propagation Engine)
For 20 years, WordPress has powered 40% of the internet, but every site has been an isolated island. The W4 Protocol acts as the connective tissue (the "Antenna"), turning these isolated islands into a unified, decentralized supercomputer.

All sub-protocols (Mantle Credit, Retention, Ledger Sync) are wrapped up and executed by the overarching W4 Protocol via:
1. **The Gossip Protocol (Epidemic Routing):** When a node state updates (e.g., forging a Diamond), the node uses background Action Schedulers to ping 5-10 neighbor nodes via WP REST API. Those nodes verify the cryptographic signature and gossip to their neighbors, causing the data to exponentially cascade across the network exactly like DNS propagation.
2. **Directed Federation:** Targeted actions (like sending a GP tip) are queued by WP-Cron and securely POSTed directly to the recipient node's inbox, ensuring immediate settlement without waiting for global gossip.
3. **Index Nodes (Heavy Lifters):** Standard users (Light Nodes) only store their own data and that of direct contacts. Users running upgraded infrastructure (Diamond holders / Ledger Nodes) act as regional hubs, storing massive chunks of the global network state in exchange for higher systemic rewards.

## 9. Macro-Economic Safeguards

### The Earth Cent (Algorithmic Stability)
Dirt is pegged to a "Global Average Unit" — an aggregate index of all world currencies. It is untethered from USD or any single nation's monetary policy.
- **High Demand:** System mints more Dirt, increases drop rates. Supply up, price returns to Earth Cent.
- **Low Demand:** System incentivizes Synthesis (burning Dirt into higher minerals). Supply down, price returns to Earth Cent.

### Algorithmic Geo-Balancing (Anti-Inflation)
A farmer running a node in rural India provides the same cryptographic value as an engineer in Silicon Valley. The system detects local economic disparity and dynamically adjusts payout weights to equalize global purchasing power.

### DePIN (Decentralized Physical Infrastructure Network)
The WordPress/PHP core and Vue frontend literally live on the hosting nodes purchased by users. Currently, these "Blackboxes" are distributed enterprise cloud instances (like AWS Elastic Cloud), establishing the foundational grid before transitioning to physical hardware nodes. Every person buying a hosting plan physically expands the system. The infrastructure IS the economy.

## 10. The Omega Point
The ultimate destination of the W4 Protocol: a post-scarcity civilization where the economy is an invisible API. Humans participate in reality — creating, teaching, building, exploring — and the system handles the math of who needs what and who produced what. Money becomes a game. Life becomes the quest. The interface we all share ensures no one falls through the cracks.

*"The paradise we seek is already built; we merely need to tune our frequencies to receive it."* — Axiom IX
`,pe=`# YouMeOS: The Decentralized Operating System of the Internet

## The Problem: The Fragmented SaaS Paradigm
The current internet economy relies on a rented, fragmented, and increasingly expensive model:
- **Subscription Fatigue:** SaaS platforms like Notion (for documents) or Asana (for tasks) each charge $10-$20+ per user, per month. The cost of running an individual or business digital life compounds rapidly.
- **Friction & Data Silos:** Users must navigate to dozens of different websites, manage separate logins for every portal, and their data remains locked within third-party walled gardens. 
- **Dwindling Synchronicity:** Because data lives in fragmented silos owned by different corporations, true interoperability and synchronicity dwindle. You are renting your digital tools, and you are not in control of your own data.

## The Solution: The YouMeOS Paradigm
What if instead of paying monthly for a fragmented suite of tools, you got them for free? What if instead of logging into separate portals, you could launch unified apps directly from your desktop, from anywhere in the world?

YouMeOS solves the SaaS problem by flipping the architecture upside down:
- **Instant PWAs:** YouMeOS utilizes Progressive Web Apps (PWAs) that can be installed in an instant and work fully offline. 
- **Zero Friction:** A unified desktop interface connects all of your applications (Sparks) without the need to log into dozens of different websites.

## The Core Differentiator: Your Domain, Your Data
The true paradigm shift is not just in the UI—it's in the infrastructure. 

When you use YouMeOS, **you are not logging into our portal.** 
**You are logging into YOURS.**

- **Absolute Data Sovereignty:** The PWAs you load on your devices connect directly to \`www.your-own-domain.com\`. 
- **Decentralized Syncing:** Your data does not go to a central YouMeOS server. It syncs directly to your own domain and stays with you.
- **The Single-Stack Black Box:** Users are no longer just paying for an app—they are paying for the *hosting* that holds their domain, their personal database, and their local install of YouMeOS. This infrastructure acts as their private launchpad to manage their entire ecosystem.
- **Cost Consolidation:** The compounding monthly costs of dozens of disparate SaaS apps are entirely swallowed into this single, highly efficient, all-in-one black-boxed solution.
- **The Internet in Your Pocket:** The entire system operates from your own digital real estate, becoming your personalized, decentralized Operating System for the Internet—fitting right into your back pocket.

## Value Proposition for Stakeholders

### For the User
- **True Ownership:** You own your data, your content, and your domain.
- **Cost Elimination:** Replaces the need for dozens of expensive SaaS subscriptions.
- **Seamless Experience:** Offline-first, lightning-fast PWAs that feel like native applications, all running under a unified OS interface.

### For the Investor
- **Viral Infrastructure Play:** YouMeOS isn't just another app; it's an infrastructure layer. As users adopt it, they claim their own domains, driving massive, decentralized network effects.
- **New Economic Models:** Monetization shifts from predatory subscriptions to value-added ecosystems (e.g., premium Sparks, the W4 Protocol Economy, Digital Mineral/DIRT economy, and managed hosting partnerships). 
- **Moat via Sovereignty:** In an era of privacy concerns and AI data scraping, giving the user absolute control over their data is a powerful, unassailable competitive moat.

### For Developers (The "Sparks" Ecosystem)
- **Unified Distribution:** Developers can build Sparks (apps) that run perfectly within the YouMeOS ecosystem.
- **Direct Connection:** No need to build complex backend user management; the user's domain *is* the backend. 

## Summary
YouMeOS replaces the fragmented, rented Web 2.0 ecosystem with a sovereign, decentralized, and elegant Web 3.0 reality. It transforms the internet from a series of disjointed portals you visit, into a unified Operating System that you own.
`,me=`# YouMeOS Taxonomy & Terminology

This document defines the official naming conventions for the YouMeOS system and its celestial components, utilizing an "Ancient-Futuristic" aesthetic for its primary navigation levels.

## Core System Entities

### YouMeOS

The system as a whole. An Intergalactic Operating System for the Internet, built as a 4D interface for WordPress.

### Youniverse

An individual instance of YouMeOS. It is a 4D interface of a galaxy, capable of seeing distant galaxies in the background. It serves as the site's user-facing portal into the system.

## The Three "God-Views" (Primary Navigation)

### The Noosphere (Galaxy Level)

The highest perspective. A visualization of the entire collective of stars (users) and information. It represents the "sphere of human thought" integrated into the intergalactic web.

### The Nexos (Hipparcos Level)

The spatial level that visualizes your relation to others. It is a time-based grid where users/suns appear on a canvas. This acts as the connecting point between stars. This level is also referred to as **Hipparcos** (after the star catalog used for positional data).
_Note: The time-based grid visualization for user positions is planned for future development._

### Helios (Sun Level)

The personal core. At the very center of a user's system is a large, beautiful Sun. This is the **Default Dashboard** where core management and overview reside. It is named **Helios** to evoke the ancient personification of the Sun.

## Celestial Hierarchy

### Star

Each user on a YouMeOS-enabled site is represented as their own **Star**.

### Main Star

The main WordPress site itself is the **Main Star** (or central sun) of that specific system.

### Oort Cloud

Visible when zooming into your own sun (or someone else's). This contains all the content produced, linked, watched, or consumed by that user. It is the outer layer of a user's digital footprint.

### Sparks

Small, energetic applications that perform specific functions or add features to your system. They are the evolutionary successors to Apps:
**Program** → **Application** → **App** → **Spark**.

In the legacy engine, these were visualized as planets, but now they are treated as ignited modules within your system:

- **Mercury**: Messages
- **Venus**: Relations
- **Earth**: Pictures
- **Mars**: Videos
- **Jupiter**: Music
- **Saturn**: Blog
- **Uranus**: Arcade
- **Neptune**: Calendar

## Installation

YouMeOS is typically installed as a **Plugin** on a WordPress site, transforming the traditional admin experience into a celestial navigation interface.
`,ge='# Xophz-COMPASS Architecture & Migration Documentation\n\n## Overview\n\nXophz-COMPASS is a Vue-based administration portal, originally built with Vue 2, Vuetify 1.5, and Bootstrap. It has been migrated to Vue 3 and Vuetify 3, utilizing a custom "X-Atoms" design system for a premium, game-inspired aesthetic.\n\n> For a deep dive into the system\'s "Apps" and "Plugins", see [Portals & Ecosystem](Portals-and-Ecosystem.md).\n\n## Tech Stack\n\n- **Frontend Framework**: Vue 3 (Composition API)\n- **UI Component Library**: Vuetify 3\n- **Styling**: Vanilla SCSS + Vuetify utility classes (No Tailwind CSS)\n- **State Management**: Pinia\n- **Build Tool**: Vite\n- **Data Fetching**: Axios + TanStack Vue Query\n\n## Project Structure\n\n- `src/`: Core source code\n  - `mount-app.ts`: Entry point\n  - `app.vue`: Root layout component\n  - `assets/`: Static assets (fonts, images)\n  - `routes/`: Module-based routing and components\n    - `compass/`: Core navigation and exploration\n    - `xp/`: Gamification / XP module\n    - `bazaar/`: Product/Sales module\n    - `bomb-bag/`: Email marketing module\n  - `components/`: Global reusable components using game engine-inspired hierarchy:\n    - `primitives/`: Basic building blocks (`x-btn`, `x-card`, etc.)\n    - `prefabs/`: Reusable, interactive components\n    - `constructs/`: Complex, functional UI sections\n    - `blueprints/`: Page layouts and structural wrappers\n  - `styles/`: Global SCSS styles\n  - `engine/`: Core application setup\n    - `primitives.ts`: Global registration of X-Primitives\n    - `global-components.ts`: Registration of complex global components\n    - `api.ts`: Centralized API configuration\n\n## Migration Patterns\n\n### Grid System\n\n- **Legacy**: `v-layout` / `v-flex` (Vuetify 1.x)\n- **Modern**: `v-row` / `v-col` (Vuetify 3)\n\n### Utility Classes\n\n- Use standard Vuetify utility classes (`ma-0`, `pa-0`, `d-flex`).\n- Custom utility classes (e.g., `.glass`, `.rough-glass`) located in `src/styles/core/_utilities.scss`.\n\n### Icons\n\n- Uses **Font Awesome 5 Pro** (SVG/JS or Webfont).\n- Wrap in `<x-icon>` for consistent themed color support.\n\n## Key Components\n\n### Billboard\n\nLocated in `@blueprints/sub-app-billboard/sub-app-billboard.vue`. It serves as a header/hero area that changes based on the active route/plugin.\n\n### Routes Navigation Drawer\n\nLocated in `@constructs/routes-navigation-drawer/routes-navigation-drawer.vue`. Handles the side-level navigation.\n\n### X-Primitives (Atoms)\n\nGeneric UI building blocks (e.g., `<x-btn>`, `<x-card>`) that extend Vuetify components with the unique "Xophz Flare". These are the preferred way to build UI instead of raw `v-` components.\n\n## Current Focus\n\nRefining the premium HUD aesthetic, completing the migration of all legacy modules to the new X-Primitive system, and ensuring robust integration with the WordPress REST API.\n',ye=`# Patreon & Bazaar Integration

## Overview

The **Bazaar** is the community contribution and value-exchange module for the YouMeOS ecosystem. It introduces a "Contribution-as-Access" model where users can support the project via Patreon and receive **Universal Access Keys** to enter the OS and gain beta access to new plugins.

## Architecture

### 1. Support View (\`/support\`)
A dedicated, premium view (\`SupportView.vue\`) explains the project's mission and the benefits of becoming a Patron. It features a generated "Universal Access Key" visual asset and directs users to the project's Patreon page.

### 2. Access Keys
- **Purpose**: Keys are used to gate access to the OS and its advanced features.
- **Acquisition**: Currently, keys are obtained by backing the project on Patreon.
- **Entry**: The **Register** screen (\`RegisterView.vue\`) is the primary entry point for keys. It has been updated to specifically label the password/access field as "Access Key".

### 3. Navigation
- **Bazaar Item**: Added to the \`useNavigation\` composable, providing a central link in the portal navigation.
- **Support Link**: Added to the user interaction drawer (right sidebar) for easy access from anywhere in the app.

## Branding & Aesthetics
The Bazaar uses a **Primary Cyan** color scheme with **Patreon Red** hover accents. The "Universal Access Key" asset follows the project's glassmorphism and holographic HUD aesthetic.

## Future Roadmap
- **Automated Key Delivery**: Integration with Patreon API to automatically issue keys upon contribution.
- **Plugin Marketplace**: The Bazaar will eventually house a "Plugin Store" where Patrons can browse and install beta plugins.
- **Subscription Model**: Introduction of a tiered subscription model for advanced OS features.
`,fe=`# Portals & Ecosystem

> "The Light Web: Weaving the digital universe together, one star at a time."

## 1. The Concept

### Portals (The "Apps")
In **YouMeOS**, a **Portal** is more than just a webpage; it is a destination. When a user navigates into a Portal (e.g., Helios or Nexos), they aren't just changing routes, they are initiating a **Visual Journey**.

- **The Background Canvas**: The defining feature of a Portal is its ability to command the global Background Canvas. As the user moves through the system, the background travels, morphs, and animates to visually represent the current context (e.g., zooming into a Sun, flying through a Nebula, or mapping a Constellation).
- **Spatial Navigation**: The interface is designed to feel "spatial," blending the utility of a traditional OS with the immersion of a video game.

### The Open Ecosystem
Just as Windows has \`.exe\` files and Android has \`.apk\`s, YouMeOS is designed as a platform for **Sparks**.
-   **Evolution**: Program → Application → App → **Spark**.
-   **Concept**: A Spark is a lightweight, living utility that ignites functionality on the canvas.
-   **Build It**: Developers can create their own Portals and Sparks.
-   **Connect It**: These sparks plug into the main YouMeOS/COMPASS core.
-   **Scale It**: The vision is a thriving **You Me Sparks** library that extends the operating system's capabilities.

---

## 2. Core Portals

The default installation comes with three primal Portals, representing the three "God-Views" of reality.

### 🔥 Helios (The Personal Core)
**"The Heart of Your Solar System"**
Helios is the creative suite and media center. It is where the User (the Star) manages their own light-content.

| Nav Item | Purpose |
| :--- | :--- |
| **Chronicles** | Blog posts, journals, and written journey logs. |
| **Pictures** | Visual gallery and image management. |
| **Videos** | Cinematic records and motion captures. |
| **Music** | Audio tracks, playlists, and sonic vibrations. |
| **Vault** | Secure, private storage for sensitive data. |
| **Profile** | The public face of the Star; identity management. |

### ⚛️ Nexos (The System Layer)
**"The Grids That Bind Us"**
Nexos handles the connections, hardware (settings), and the structural relationships between things. It is the "Engineering" deck.

| Nav Item | Purpose |
| :--- | :--- |
| **Resonance** | Social syncing; aligning frequencies with other users. |
| **Constellations** | Organization of friends, groups, and clusters. |
| **Channels** | Communication pipelines (Aether Channels). |
| **Forge** | The Holospace for simulations, games, and tools. |
| **Profile** | Technical profile and system calibration. |

### 🌌 Noosphere (The Collective)
**"The Universal Mind"**
The Noosphere is the "Internet" of YouMeOS, the shared space where all Stars interact, share wisdom, and form the collective consciousness.

| Nav Item | Purpose |
| :--- | :--- |
| **Akashic Archive** | The global library of shared knowledge (Wiki/Docs). |
| **Thought Stream** | The real-time river of global activity and consciousness. |
| **Mind Map** | A visualization of connected ideas and topics. |
| **Forums** | Galactic meeting halls for discussion. |
| **Universal Net** | The visual network graph of the Noosphere. |

---

## 3. Ecosystem Architecture

Understanding how the pieces fit together is crucial for building new extensions.

### COMPASS (The Host)
The "Motherboard". COMPASS is the core WordPress plugin that provides the API, authentication, and database structures.

### YouMeOS (The OS)
The "Interface". This is the Vue.js frontend application. It consumes the COMPASS API and renders the interactive 4D desktop environment.

### Sparks (The "Apps")
Extensions that provide specific "backend" functionality or specialized UI modules. A Spark can expose:
1.  **API Endpoints**: New data sources.
2.  **Vue Components**: New primitive atoms or widgets.
3.  **Portals**: Entire new top-level routes.

#### Standard Sparks
Existing "Cartridges" that plug into the system:

-   **💣 Bomb Bag**: The **Email Marketing** engine. Manages campaigns and subscribers.
-   **🔮 Magic Cloak**: The **Contextual Helper**. Provides "Glass Toast" notifications and guides users based on events.
-   **🛡️ Mirror Shield**: The **Security** layer. Deploys honeypots and blocks malicious entities.
-   **🏰 Moving Castle**: The **Multisite Manager**. Controls the network of sites from a single interface.

---

## 4. Development Guide

### How to Create a Portal
1.  **Define the Route**: Add a new route object in \`router.ts\` under the \`/u\` (Youniverse) path.
2.  **Set Navigation Meta**:
    \`\`\`typescript
    meta: {
      requiresAuth: true,
      navigation: [
        {
           title: "My Portal",
           icon: "fal fa-rocket",
           to: "/u/my-portal/start",
           description: "Launch into the unknown.",
           value: "my-portal-start"
        }
      ]
    }
    \`\`\`
3.  **Create the Component**: Build a Vue component (Leaf) that serves as the layout for your portal.
4.  **Connect the Canvas**: Use the \`useCanvasStore()\` to dispatch a "travel" event when your route is mounted.

### How to Ignite a Spark
1.  **Backend**: Create a WordPress plugin that registers REST API routes under \`xophz-compass/v1/my-spark/\`.
2.  **Frontend**: In YouMeOS, create a \`service\` that fetches data from these endpoints.
3.  **UI**: Create standard \`x-\` components to display the data.
`,ve=`# Software Manager (Compass Suite)

The **Software Manager** is a high-fidelity administrative dashboard within the COMPASS welcome screen designed to provide a comprehensive overview of installed plugins and modules. It replaces the legacy dial-pad interface with a modern master-detail layout.

## Architecture

### Master-Detail Layout
- **Left Sidebar**: A sticky category menu that groups plugins based on the project's thematic design language (e.g., True North, Command Deck, Trajectory).
- **Right Panel**: A dynamic list of plugins for the selected category.

### Data Source
The software manager pulls data directly from the \`CompassStore.pluginList\`, which is populated by the WordPress REST API. It matches plugins using their \`TextDomain\` naming convention (e.g., \`xophz-compass-bomb-bag\`).

## Components

### X-Sequence-Icon Integration
The category menu utilizes the \`x-sequence-icon\` primitive to provide animated feedback for specific navigation items.

- **Command Deck**: Features a "Teleport" animation sequence (\`fal fa-transporter\` range).

### Scrolling Behavior
Scrolling is handled at the global \`compass.vue\` level. The entire route view scrolls independently of the bottom navigation bar, while the software manager's category sidebar remains fixed in place using \`position: sticky\`.

## Configuration

Categories are defined in \`src/stores/launcher.store.ts\` via the \`COMPASS_CATEGORIES\` constant.

\`\`\`typescript
export const COMPASS_CATEGORIES: CompassCategoryDef[] = [
  {
    id: "command deck",
    label: "Command Deck",
    subtext: "Strategy & Operations",
    icon: "fal fa-ship",
    plugins: ["newsroom", "questbook", "event-horizon", "profile"]
  },
  // ...
];
\`\`\`

## Styling
- **Glassmorphism**: Uses the signature COMPASS glass styling for cards and lists.
- **HUD Typography**: Module metadata (version, author) uses subtle caption styling for an "OS dashboard" feel.
`,be='# Bomb Bag News Drip System\n\nThe **Bomb Bag** is an email marketing and automated drip sequence plugin for COMPASS. It provides campaign management, subscriber lists, newsletter delivery, open/click tracking, scheduled campaigns, drip sequences, and reusable email templates.\n\n## Philosophy\n\nBomb Bag transforms email marketing into an intuitive, visual experience. Users can create campaigns, manage subscribers, compose beautiful emails, build automated drip sequences, and track performance, all from a sleek, mobile-first interface with the signature COMPASS "HUD" aesthetic and a pink (#ED55A9) accent theme.\n\n## Architecture\n\n### 1. Database Schema\n\n| Table                          | Purpose                                       |\n| ------------------------------ | --------------------------------------------- |\n| `wp_bomb_bag_campaigns`        | Campaign metadata, status, scheduling, stats  |\n| `wp_bomb_bag_subscribers`      | Email addresses, names, status                |\n| `wp_bomb_bag_lists`            | Subscriber grouping/segmentation              |\n| `wp_bomb_bag_list_subscribers` | Many-to-many list↔subscriber junction         |\n| `wp_bomb_bag_emails`           | Individual sent emails with tracking ID       |\n| `wp_bomb_bag_analytics`        | Open/click/unsubscribe events                 |\n| `wp_bomb_bag_drip_sequences`   | Drip sequence metadata and status             |\n| `wp_bomb_bag_drip_steps`       | Individual steps in a drip sequence            |\n| `wp_bomb_bag_drip_enrollments` | Subscriber enrollment state in drip sequences |\n| `wp_bomb_bag_templates`        | Reusable email templates                      |\n\n### 2. REST API\n\nBase URL: `/wp-json/xophz-compass/v1/bomb-bag/`\n\n#### Core Endpoints\n\n| Endpoint                  | Method         | Description                 |\n| ------------------------- | -------------- | --------------------------- |\n| `/stats`                  | GET            | Dashboard statistics        |\n| `/settings`               | GET/POST       | Plugin settings             |\n| `/settings/test`          | POST           | Test email connection       |\n| `/campaigns`              | GET/POST       | List or create campaigns    |\n| `/campaigns/:id`          | GET/PUT/DELETE | Single campaign CRUD        |\n| `/campaigns/:id/send`     | POST           | Queue campaign for sending  |\n| `/campaigns/:id/schedule` | POST           | Schedule campaign for later |\n| `/campaigns/:id/test`     | POST           | Send test email             |\n| `/subscribers`            | GET/POST       | List or add subscribers     |\n| `/subscribers/:id`        | PUT/DELETE     | Update or remove subscriber |\n| `/subscribers/import`     | POST           | Bulk CSV import             |\n| `/lists`                  | GET/POST       | List management             |\n| `/lists/:id`              | PUT/DELETE     | Update or delete list       |\n| `/analytics/:campaign_id` | GET            | Campaign-specific analytics |\n\n#### Drip Sequence Endpoints\n\n| Endpoint                               | Method     | Description                 |\n| -------------------------------------- | ---------- | --------------------------- |\n| `/drips`                               | GET/POST   | List or create sequences    |\n| `/drips/:id`                           | GET/PUT/DELETE | Single sequence CRUD    |\n| `/drips/:id/steps`                     | POST       | Add a step                  |\n| `/drips/:id/steps/:step_id`            | PUT/DELETE | Update or delete a step     |\n| `/drips/:id/steps/reorder`             | POST       | Reorder steps               |\n| `/drips/:id/enrollments`               | GET        | List enrollments            |\n| `/drips/:id/enroll`                    | POST       | Enroll a subscriber         |\n| `/drips/:id/analytics`                 | GET        | Drip-specific analytics     |\n\n#### Template Endpoints\n\n| Endpoint         | Method         | Description            |\n| ---------------- | -------------- | ---------------------- |\n| `/templates`     | GET/POST       | List or create         |\n| `/templates/:id` | GET/PUT/DELETE | Single template CRUD   |\n\n### 3. Email Delivery\n\n- **Batch Processing**: Emails queued in database, processed via WP-Cron\n- **Configurable batch size** (default: 50) and delay between batches\n- **Providers Supported**:\n  - WordPress Default (`wp_mail`)\n  - SendGrid (HTTP API with Bearer token)\n  - Mailgun (HTTP API with Basic auth)\n  - Custom SMTP (PHPMailer hook)\n- **Provider Implementation**: All providers implemented in `class-bomb-bag-email-providers.php`\n\n### 4. Tracking\n\n- **Open Tracking**: 1x1 transparent GIF pixel\n- **Click Tracking**: URL rewriting through tracking endpoint\n- **Unsubscribe**: One-click unsubscribe via tracking URL\n\nTracking endpoint: `?bomb_bag_track={open|click|unsubscribe}&tid={tracking_id}`\n\n### 5. Campaign Scheduling\n\n- Campaigns can be scheduled for future delivery via the `scheduled_at` field\n- A WP-Cron job runs every 5 minutes to check for campaigns due to send\n- When due, the scheduler calls `queue_campaign()` to start batch processing\n\n### 6. Drip Sequences (News Drip)\n\n- **Sequences** contain ordered **steps**, each with a subject, content, and delay\n- **Triggers**: `subscribe` (auto-enroll on list subscription), `manual`, `tag_added`\n- **Enrollments** track each subscriber\'s progress through the sequence\n- A WP-Cron job runs hourly to process due drip emails\n- Each step can use a template from the template library\n\n### 7. Frontend Views\n\n| Route                     | View          | Description                                         |\n| ------------------------- | ------------- | --------------------------------------------------- |\n| `/bomb-bag`               | Dashboard     | Stats cards, subscriber growth chart, quick actions  |\n| `/bomb-bag/campaigns`     | Campaigns     | Campaign list with status filters                    |\n| `/bomb-bag/campaigns/new` | Campaign Form | Create new campaign                                  |\n| `/bomb-bag/campaigns/:id` | Campaign Form | Edit existing campaign                               |\n| `/bomb-bag/subscribers`   | Subscribers   | Subscriber list, search, import                      |\n| `/bomb-bag/composer/:id`  | Composer      | HTML editor with live preview                        |\n| `/bomb-bag/settings`      | Settings      | Email provider configuration                         |\n| `/bomb-bag/drips`         | Drips         | Drip sequence listing with stats                     |\n| `/bomb-bag/drips/new`     | Drip Builder  | Timeline-based sequence builder                      |\n| `/bomb-bag/drips/:id`     | Drip Builder  | Edit existing sequence                               |\n| `/bomb-bag/templates`     | Templates     | Email template library with preview                  |\n\n### 8. Route Names\n\nAll route names are prefixed with "Bomb Bag" to prevent collision with other plugins. Constants are defined in `bomb-bag.routes.ts`:\n\n```typescript\nconst R = BOMB_BAG_ROUTE_NAMES;\nrouter.push({ name: R.campaigns });\n```\n\n## Email Template Variables\n\nAvailable in campaign and drip content:\n\n| Variable              | Description                |\n| --------------------- | -------------------------- |\n| `{{first_name}}`      | Subscriber\'s first name    |\n| `{{last_name}}`       | Subscriber\'s last name     |\n| `{{email}}`           | Subscriber\'s email address |\n| `{{unsubscribe_url}}` | One-click unsubscribe link |\n\n## Default Behavior\n\nOn plugin activation:\n\n1. Creates 10 database tables\n2. Sets default email provider to WordPress (`wp_mail`)\n3. Seeds "Main Newsletter" subscriber list\n4. Seeds 3 default email templates (Modern Newsletter, Welcome Series, Minimal Text)\n\n## PHP Class Map\n\n| Class | File | Purpose |\n| --- | --- | --- |\n| `Xophz_Compass_Bomb_Bag` | `class-xophz-compass-bomb-bag.php` | Main plugin class, hooks, cron |\n| `Xophz_Compass_Bomb_Bag_Rest` | `class-bomb-bag-rest.php` | Core REST API (campaigns, subscribers, lists, settings) |\n| `Xophz_Compass_Bomb_Bag_Drip_Rest` | `class-bomb-bag-drip-rest.php` | Drip sequence REST API |\n| `Xophz_Compass_Bomb_Bag_Template_Rest` | `class-bomb-bag-template-rest.php` | Template REST API |\n| `Xophz_Compass_Bomb_Bag_Email_Handler` | `class-bomb-bag-email-handler.php` | Email batch processing, tracking, drip processing |\n| `Xophz_Compass_Bomb_Bag_Email_Providers` | `class-bomb-bag-email-providers.php` | Provider-specific email sending |\n| `Xophz_Compass_Bomb_Bag_Activator` | `class-xophz-compass-bomb-bag-activator.php` | DB table creation, seeding |\n\n## TypeScript Type Map\n\nAll types defined in `bomb-bag.api.ts`:\n\n| Type | Purpose |\n| --- | --- |\n| `Campaign`, `CampaignPayload` | Campaign data and creation payloads |\n| `Subscriber`, `SubscriberPayload` | Subscriber data |\n| `SubscriberList` | List metadata |\n| `DripSequence`, `DripSequencePayload` | Drip sequence data |\n| `DripStep`, `DripStepPayload` | Individual drip steps |\n| `DripEnrollment` | Subscriber enrollment state |\n| `EmailTemplate`, `EmailTemplatePayload` | Template data |\n| `CampaignAnalytics`, `DripAnalytics` | Analytics responses |\n| `Stats`, `Settings` | Dashboard and configuration |\n| `CampaignStatus`, `DripStatus`, `DripTrigger`, `EmailProvider` | Union type constants |\n',we="# Bug Net System\n\n## Purpose\nThe Bug Net System is the official bug-catching and tracking engine for the COMPASS platform. It leverages a dedicated WordPress Custom Post Type (`compass_bug`) to log, monitor, and resolve system issues, acting as an integrated issue tracker for the suite.\n\n## Architecture\n\n| Component | Responsibility | Location |\n|---|---|---|\n| **CPT (`compass_bug`)** | Stores bug data (Title, Description, Environment, Status, Priority). | `wp-content/plugins/xophz-compass-bugnet/includes/class-xophz-compass-bugnet-cpt.php` |\n| **Meta Box** | WP Admin interface for reviewing bug state (classic editor, Gutenberg disabled). | Same as above |\n| **REST API** | Exposes `bug_status`, `bug_priority`, `bug_environment` for Vue integration. | `rest_api_init` hooks inside the CPT class |\n| **Error Interceptor** | Catches runtime JS/Vue errors and auto-creates bug posts. | `src/engine/bugnet-interceptor.ts` |\n| **Vue Dashboard** | Card-based bug tracking UI with search, filters, and inline editing. | `src/routes/bugnet/views/` |\n| **API Composable** | Typed wrapper around `usePostType('compass_bug')` with shared constants. | `src/routes/bugnet/bugnet.api.ts` |\n\n## Meta Fields & Status Lifecycle\n\n### Bug Status (`bug_status`)\n- `new`: A newly reported issue waiting for triage.\n- `in-progress`: Actively being worked on by the development team.\n- `resolved`: The fix has been applied and tested but not fully closed out.\n- `closed`: Verified complete.\n\n### Priority (`bug_priority`)\n- `low`: Minor visual inconsistencies or trivial edge cases.\n- `medium`: Expected functionality issues that don't block the core user experience.\n- `high`: Severe operational failure in a primary system component.\n- `critical`: Total system failure, data loss, or significant security vulnerability.\n\n## Automatic Error Interception\n\nThe Bug Net interceptor (`src/engine/bugnet-interceptor.ts`) is installed during app bootstrap in `mount-app.ts`. It hooks into three error surfaces:\n\n| Source | What It Catches |\n|---|---|\n| `window.addEventListener('error')` | Uncaught JS errors (syntax, reference, type errors) |\n| `window.addEventListener('unhandledrejection')` | Failed async/await and unhandled Promise rejections |\n| `app.config.errorHandler` | Vue component lifecycle errors (render, setup, watchers) |\n\n### Safety Mechanisms\n- **Fingerprint Deduplication**: Identical errors within a 10-second window are suppressed.\n- **Batched Queue**: Posts are flushed in batches of 5 every 2 seconds to avoid API floods.\n- **Max Queue Cap**: Only 20 pending reports are queued at any time.\n- **Silent Failure**: The interceptor itself never throws, as a bug reporter creating bugs would be ironic.\n- **Nonce Guard**: If no WP nonce is present (e.g., logged-out user), auto-reporting is disabled entirely.\n\n### Auto-Generated Bug Format\n- **Title**: `[Auto] <error message>` (truncated to 200 chars)\n- **Content**: `<pre>` block containing the full error message + stack trace\n- **Status**: Always `new`\n- **Priority**: `high` for JS errors, `critical` for Vue component errors\n- **Environment**: Auto-populated with `navigator.userAgent` and current route\n\n## Vue Dashboard Views\n\n| Route | Component | Purpose |\n|---|---|---|\n| `/bugnet` | `bugnet-dashboard.vue` | Card grid of all bugs with search, status/priority filters, and \"Report Bug\" dialog |\n| `/bugnet/:id` | `bugnet-detail.vue` | Single bug view with inline status/priority editing and quick Resolve/Close actions |\n\n## Future Development\n- Funnel critical bugs to designated communication channels (Slack, Discord, etc.) via WP hooks.\n- Add bug assignment to team members via the Questbook CRM contact system.\n- Integrate with the XP system to reward users who report bugs.\n",Se=`# Lemon Aid System

## Overview
**Lemon Aid** (also referred to technically as the \`xophz-lemonade-stand\` or Post Digger) is an RSS feed aggregator and user curation spark natively integrated into YouMeOS. It serves a dual purpose:
1. Acting as a personal feed reader for individual users to aggregate their preferred news sources.
2. Crowdsourcing content curation for the broader **Noosphere** collective feed.

## The Sugar vs Lemon Mechanic
Inside the Lemon Aid Spark, every incoming feed item is actionable via two gamified inputs:
- **Sugar 🧊 (Upvote)**: Positive reinforcement signaling high value, interesting, or relevant content.
- **Lemon 🍋 (Downvote)**: Negative feedback indicating low quality, clickbait, or irrelevant noise ("sour" content).

### Noosphere Promotion
As users cast "Sugar" on stories in their personal reading queues, a background algorithm calculates the **Sweetness Ratio**. When content reaches specific Sweetness thresholds, the article is automatically promoted directly into the **Noosphere**—the global OS network overview—as an "Editor's Pick" or "Trending" item. Conversely, articles stacked with Lemons are filtered out from global visibility and deprioritized even in personal queues.

## Architecture
### Backend (Data Fetching & Storage)
Controlled by the \`xophz-compass-post-digger\` WordPress plugin.
- Background cron jobs fetch the latest RSS XML updates.
- Emits sanitized, normalized Feed post types via the REST API.
- Logs Sugar and Lemon interactions to aggregate global post weights.

### Frontend (Vue Spark)
The UI inside YouMeOS is a native Vue application plotting the \`lemon-aid\` framework.
- **View:** \`apps/youmeos/app/sparks/lemon-aid/\`
- **Design System:** Borrows standard \`<x-list>\` and \`<x-card>\` glassmorphic primitives to present stories. Follows the "Digital Picnic" (\`picnic\`) design taxonomy.
`,Te=`# Magic Cloak System

The **Magic Cloak System** is a modular, event-driven contextual messaging and guidance service for COMPASS. It provides a non-intrusive way to deliver hints, tips, and system updates to users through premium "glass toasts."

## Philosophy

Inspired by the "Office Assistant" concept, Magic Cloak aims to be a helpful companion that lives within the COMPASS ecosystem. It is designed to be:

- **Contextual**: Messages appear based on the user's current location or action.
- **Conditional**: The entire system is bound to the activation status of the **Magic Cloak** plugin.
- **Premium**: High-end visual design that emphasizes COMPASS's modern, game-like aesthetic.

## Architecture

### 1. The Source (Magic Cloak Plugin)

The \`xophz-compass-magic-cloak\` plugin acts as the bridge between the backend/configuration and the frontend.

- **Completed Phase**: Hardcoded message registry in the frontend controller/mechanic.
- **Current Phase**: Dynamic fetching of messages from WordPress via the custom post type \`compass_cloak_hint\`.
- **API Endpoint**: \`/wp-json/wp/v2/compass_cloak_hint\` (or custom endpoint configured in \`api.ts\`).

**Registry Schema:**

\`\`\`typescript
interface MagicHint {
  id: string | number;
  trigger: string; // e.g., 'route:enter:compass-explore'
  content: string; // The message body
  icon?: string; // Optional: Plugin shorthand, ID, Name, Path, or FA class
  priority?: number; // Handling overlapping messages
  title?: string; // Optional hint header
  timeout?: number; // Display duration (default 8s)
}
\`\`\`

### Smart Icon Resolution

The Magic Cloak system features an intelligent icon resolution engine. When a hint is triggered, the \`icon\` field is processed with the following priority logic:

1. **Direct Path/URL**: If the string contains \`/\` or \`.\`, it is rendered as an \`<img>\`.
2. **Plugin Shorthand/ID**: It attempts to match the string against \`TextDomain\` (e.g., \`"bomb-bag"\`), full TextDomain (\`"xophz-compass-bomb-bag"\`), or Plugin Name (\`"Bomb Bag"\`). If matched, it uses the official plugin SVG.
3. **FontAwesome Fallback**: If no plugin match is found, it is treated as a FontAwesome icon class (e.g., \`"fad fa-atom"\`).
4. **System Default**: If no icon is provided, it defaults to the **Magic Cloak** plugin icon.

### 2. The Mechanic (\`useMagicCloak\`)

Located in \`src/mechanics/useMagicCloak.ts\`, this hook is responsible for:

- Checking if the Magic Cloak plugin is active via \`CompassStore\`.
- Reacting to global events (routing, component interactions).
- Dispatching messages to the UI layer.

### 3. The UI Layer (\`x-snackbar\`)

A specialized primitive designed for the Magic Cloak's specific aesthetic requirements and reusable across other plugins:

- **Glassmorphism**: 20px blur, semi-transparent backgrounds (\`rgba(13, 17, 23, 0.4)\`).
- **Micro-animations**: Smooth entry/exit inherited from \`v-snackbar\`.
- **Global Availability**: Registered as an atomic component for use in any route.
- **Layout**: Positioned in the bottom-right (\`bottom end\`) corner to minimize obstruction of central content.

## Integration Plan

1. **Pilot Phase**: Replace the static \`v-alert\` on the Explore page with a dynamic toast triggered by the \`useMagicCloak\` mechanic.
2. **Expansion**: Add "Events" to other core plugins (e.g., titan's mitt, enchiridion) that the Magic Cloak can listen for.
3. **Configuration**: Develop a UI within the Magic Cloak plugin to manage or override these messages.
`,Pe=`# Magic Mirror: Competitive Benchmarking & Performance Analytics

> *"Magic Mirror on the wall, who is the fairest one of all?"*

Magic Mirror is an SEO, performance, and accessibility benchmarking tool designed to gamify website optimization. Instead of viewing analytics in a vacuum, Magic Mirror lets users directly compare their site's "reflection" against their fiercest competitors.

---

## Scope

**Magic Mirror focuses exclusively on competitive audits, benchmarking, and actionable insights.**

Theme switching, environments, and staging escapes are handled by **Moving Castle**.

---

## Core Features

### Phase 1: The "Fairest of Them All" Dashboard (MVP)
- [ ] UI to input 1-3 competitor URLs.
- [ ] Integration with PageSpeed Insights API (or similar) for Lighthouse scoring.
- [ ] Side-by-side comparison matrix for Core Web Vitals (LCP, FID, CLS).
- [ ] Overall "Fairest" designation awarded to the winning site.
- [ ] Caching of results to prevent API rate limiting.

### Phase 2: Actionable Insights ("The Mirror's Truth")
- [ ] Highlight specific metrics where competitors are winning.
- [ ] Generate actionable, clear-English recommendations ("Site X is faster because their images are optimized using WebP").
- [ ] Visual UI shifts based on performance (e.g., the glassmorphic mirror UI "cracks" or gets cloudy when losing to a rival, and shines brilliantly when winning).
- [ ] Historical tracking (are we gaining or losing ground over time?).

### Phase 3: Comprehensive Auditing
- [ ] SEO comparison (Meta tags, heading structure, keyword presence).
- [ ] Accessibility comparison (ARIA, contrast ratios).
- [ ] Security headers check.
- [ ] Automated weekly checks and notifications if a competitor overtakes the user.

---

## UI Concept

**The Reflection Board**: A sleek, dark-mode, glassmorphic layout. The user's site is in the center, flanked by competitors. The UI should feel like gazing into a crystal or mirror, utilizing neon cyan (#62c9ff) for positive metrics and warning colors (like red/orange) for metrics where the user is falling behind.

---

## Technical Notes

### Key Integrations
- Google PageSpeed Insights REST API.
- W3C Validator API.

### Database Tables (Proposed)
- \`wp_compass_magic_mirror_rivals\` - Stored competitor URLs and metadata.
- \`wp_compass_magic_mirror_audits\` - Historical audit data for charting over time.

---

## Status

🔴 **Not Started** - Concept defined, awaiting implementation phase.
`,ke="# Mirror Shield System\n\nThe **Mirror Shield** is a security plugin for COMPASS that provides honeypot traps, attack logging, and IP blocking capabilities.\n\n## Philosophy\n\nMirror Shield acts as a defensive layer that catches malicious actors before they can cause harm. By deploying invisible honeypot traps, it identifies and blocks attackers while legitimate users remain unaffected.\n\n## Architecture\n\n### 1. Honeypot Traps\n\nThree types of traps are supported:\n\n| Type | Description |\n|------|-------------|\n| `decoy_endpoint` | Fake URLs that attackers probe (e.g., `/wp-admin/backup.php`) |\n| `honeypot_field` | Hidden form fields bots fill out but humans don't see |\n| `fake_login` | Decoy login pages that capture credentials |\n\n### 2. Database Schema\n\n**`wp_mirror_shield_logs`** - Attack log records\n- `ip_address`, `trap_type`, `user_agent`, `request_uri`, `blocked`, `created_at`\n\n**`wp_mirror_shield_traps`** - Trap configuration\n- `trap_type`, `name`, `target_url`, `config`, `is_active`, `hit_count`\n\n**`wp_mirror_shield_blocked`** - Blocked IPs\n- `ip_address`, `reason`, `blocked_until`, `is_permanent`\n\n### 3. REST API\n\nBase URL: `/wp-json/xophz-compass/v1/mirror-shield/`\n\n| Endpoint | Method | Description |\n|----------|--------|-------------|\n| `/logs` | GET | Paginated attack logs |\n| `/stats` | GET | Dashboard statistics |\n| `/traps` | GET/POST | List or create traps |\n| `/traps/:id` | PUT/DELETE | Update or delete trap |\n| `/block` | GET/POST | List or block IPs |\n| `/block/:ip` | DELETE | Unblock IP |\n\n### 4. Frontend Views\n\n| Route | View | Description |\n|-------|------|-------------|\n| `/mirror-shield` | Dashboard | ECharts attack timeline, stats cards, top attackers |\n| `/mirror-shield/traps` | Traps | CRUD management for honeypot traps |\n| `/mirror-shield/logs` | Logs | Server-side paginated attack log browser |\n\n## Auto-Blocking Behavior\n\nIPs are automatically blocked for 24 hours after **3+ triggering events** within 1 hour. Manual blocking from the UI supports permanent or time-limited blocks.\n\n## Default Traps\n\nThe plugin seeds these default traps on activation:\n\n1. **Fake Backup File** - `/wp-admin/backup.php`\n2. **Fake Config File** - `/wp-config.bak`\n3. **Fake Admin Panel** - `/administrator/`\n4. **Login Form Honeypot** - Hidden `website_url` field\n",Ce=`# Moving Castle: Environments & Theme Shifting

> *"Open your door to many ventures, markets, and brands without moving."*

Inspired by Howl's Moving Castle, where turning a door dial connects to different locations and realities. Moving Castle provides a modern, intuitive UI for managing WordPress Multisite networks, staging environments, and on-the-fly theme swapping without affecting live users.

---

## Scope

**Moving Castle handles environments, multisite management, and theme routing.**

Competitor benchmarking and performance analysis is handled by **Magic Mirror**.
Theme/page building (content creation) is handled by **Pegasus Boots**.

---

## Core Features

### Phase 1: Environment & Theme Routing (The "Reflection" Mode)
- [ ] Allow admins to browse the live website using a different installed theme without affecting the public site (Session-based theme previewing).
- [ ] Support secure "Castle Links" (e.g., \`?castle_theme=new-design\`) to send to clients for previewing new designs on the live database.
- [ ] Intercept WordPress core \`template\` and \`stylesheet\` hooks based on active session cookies.

### Phase 2: Per-Page Theme Enchants
- [ ] Meta box in the WordPress editor to assign a specific theme to a specific page or post.
- [ ] Useful for standalone custom landing pages (e.g., Black Friday sales) that require a layout unachievable with the site's global theme.
- [ ] Central dashboard tab listing all "Enchanted" pages with theme overrides.

### Phase 3: Multisite Dial (Network Management MVP)
- [ ] Detect if WordPress Multisite is enabled.
- [ ] Portal Dial Interface: A visual dial/grid to switch between sites in the network.
- [ ] Quick site health indicators and network-wide statistics.
- [ ] Create, edit, and deactivate sites directly from the Moving Castle UI.

### Phase 4: The Escape Hatch (Staging & Rollbacks)
- [ ] The "Light Realm" vs "Dark Realm": 1-click jump between viewing the current URL in Production vs. Staging.
- [ ] Quick template/layout emergency rollback if an update breaks the site visually.

### Phase 5: Content Synchronization
- [ ] Sync posts/pages/media between sites in a multisite network.
- [ ] Sync history and conflict resolution UI.

### Phase 6: Multi-Network (The Conglomerate OS)
- [ ] Integration with WP Multi Network to allow a "Network of Networks".
- [ ] **Single Customer View (SCV):** Because all networks share the same \`wp_users\` table, Moving Castle integrates with **Questbook CRM** to track user identity and journey across completely separate domains/brands.
- [ ] **Agency / Franchise Mode:** Allow a parent organization to spin up dedicated multisite networks for different clients or franchisees, managing the entire ecosystem from one Moving Castle dashboard.

---

## UI Concept

**Portal Dial & Wardrobe**: A visual interface aligned with COMPASS glassmorphism.
- The "Wardrobe" tab lists all installed themes and allows generating preview links.
- The "Portal Dial" tab represents network sites as glowing orbs or doors to travel through.

---

## Technical Notes

### Database Tables (Proposed)
- \`wp_compass_portals\` - Portal configurations and multisite mappings.
- \`wp_compass_theme_overrides\` - Stores per-page theme assignments (or just rely on post meta: \`_compass_enchanted_theme\`).

### Key WordPress APIs
- Plugin interception of \`stylesheet\` and \`template\` filters.
- \`get_sites()\` and \`switch_to_blog()\` for multisite.

---

## Status

🔴 **Not Started** - Concept expanded and defined, awaiting implementation phase.

---

## Related Plugins

| Plugin | Responsibility |
|--------|---------------|
| Moving Castle | Multisite network, environment staging, theme routing/preview |
| Magic Mirror | Competitor benchmarking, SEO/Performance comparison |
| Pegasus Boots | Theme & page visual builder |
`,xe='# Questbook CRM System\n\nQuestbook is the central CRM and customer journey tracker for the COMPASS platform. It manages contacts, lead pipelines, quest-based engagement tracking, and form submission linking — all built on WordPress\'s native CPT + Post Meta architecture for zero-migration scalability.\n\n## Design Philosophy: Zero-Migration Schema\n\nQuestbook is built to **never require a database migration**. Following the same EAV (Entity-Attribute-Value) pattern that powers WordPress core (`wp_posts` + `wp_postmeta`), all CRM data is stored as post meta on Custom Post Types. New fields can be added at any time without schema changes.\n\n### Why This Works\n\n- **`wp_posts`** stores the entity identity (contact name as `post_title`, timestamps, status)\n- **`wp_postmeta`** stores all CRM-specific data as key-value pairs\n- **`wp_terms`** provides hierarchical categorization via taxonomies\n- **Forminator** stores all raw form submission data in its own tables\n- **Questbook** stores only identity pointers and references, never raw form payloads\n\n## Data Model\n\n### Custom Post Types\n\n| CPT Slug | Purpose | Supports |\n|----------|---------|----------|\n| `questbook_contact` | CRM contact entity | `title` |\n| `questbook_quest` | Journey milestone definitions | `title`, `editor` |\n| `questbook_log` | Historical interaction log entries | `title` |\n\n### Taxonomies\n\n| Taxonomy | Attached To | Purpose |\n|----------|-------------|---------|\n| `qb_contact_type` | `questbook_contact` | Hierarchical categorization (Lead, Customer, Partner, etc.) |\n\n### Contact Meta Schema\n\nThese are the standardized meta keys used on `questbook_contact` posts:\n\n#### Identity & Linking\n\n| Meta Key | Type | Purpose |\n|----------|------|---------|\n| `_qb_user_id` | `int` | Links contact to a `wp_users` ID (if registered) |\n| `_qb_raw_email` | `string` | Primary email for unlinked leads |\n\n#### Core Fields\n\n| Meta Key | Type | Purpose |\n|----------|------|---------|\n| `_qb_phone` | `string` | Contact phone number |\n| `_qb_lead_status` | `string` | Pipeline stage (New Lead, Contacted, Qualified, Won, Lost) |\n| `_qb_source` | `string` | Acquisition source (e.g., "Forminator Form #412", "Hustle Opt-in #7") |\n\n#### Forminator Integration\n\n| Meta Key | Type | Multi-Value | Purpose |\n|----------|------|-------------|---------|\n| `_qb_forminator_entry` | `int` | Yes | Verified Forminator entry IDs linked to this contact |\n| `_qb_unverified_entry` | `int` | Yes | Pending entry IDs awaiting Claim & Confirm |\n\n#### Assignment & Workflow\n\n| Meta Key | Type | Purpose |\n|----------|------|---------|\n| `_qb_assigned_to` | `int` | WP User ID of the assigned agent/team member |\n\n### Identity Resolution Strategy\n\n```\nForm Submitted\n     │\n     ▼\n Is user logged in?\n   ├── YES → Find contact by _qb_user_id\n   │         ├── Found → Link entry as _qb_forminator_entry ✓\n   │         └── Not Found → Create contact, link to wp_user, attach entry ✓\n   │\n   └── NO → Extract email from form\n             ├── Email matches existing contact?\n             │   ├── YES → Store as _qb_unverified_entry ⚠️\n             │   └── NO → Create new "Raw Lead" contact ✓\n             │\n             └── On Login → Magic Cloak triggers Claim & Confirm dialog\n                            ├── Approve → Move to _qb_forminator_entry ✓\n                            └── Reject → Delete _qb_unverified_entry ✗\n```\n\n## PHP Plugin (`wp-content/plugins/xophz-compass-quests/`)\n\n| File | Purpose |\n|------|---------|\n| `xophz-compass-quests.php` | Bootstrap — requires COMPASS core |\n| `includes/class-xophz-compass-quests.php` | Core class — loads dependencies, registers hooks |\n| `includes/class-xophz-compass-quests-cpt.php` | CPT + taxonomy registration |\n| `includes/class-xophz-compass-quests-rest.php` | Primary REST API (full CRUD + entries + claim) |\n| `includes/class-xophz-compass-quests-wpmudev.php` | Forminator/Hustle submission capture hooks |\n| `includes/class-xophz-compass-quests-loader.php` | WordPress hook orchestration |\n| `admin/class-xophz-compass-quests-admin.php` | WP Admin menu + meta box registration |\n\n## REST API Endpoints\n\n### Contacts CRUD\n\n| Method | Route | Auth | Description |\n|--------|-------|------|-------------|\n| `GET` | `/questbook/v1/contacts` | `manage_options` | List all contacts (with WP user resolution) |\n| `POST` | `/questbook/v1/contacts` | `manage_options` | Create a new contact |\n| `GET` | `/questbook/v1/contacts/{id}` | `manage_options` | Get single contact detail |\n| `PUT` | `/questbook/v1/contacts/{id}` | `manage_options` | Update contact fields |\n| `DELETE` | `/questbook/v1/contacts/{id}` | `manage_options` | Delete a contact |\n\n### Relationships\n\n| Method | Route | Auth | Description |\n|--------|-------|------|-------------|\n| `GET` | `/questbook/v1/contacts/{id}/assets` | `manage_options` | All CPT posts authored by or linked to this contact |\n| `GET` | `/questbook/v1/contacts/{id}/entries` | `manage_options` | Verified Forminator entries linked to this contact |\n\n### Unverified / Claim Flow\n\n| Method | Route | Auth | Description |\n|--------|-------|------|-------------|\n| `GET` | `/questbook/v1/contacts/{id}/unverified` | `manage_options` | Pending unverified Forminator entries |\n| `POST` | `/questbook/v1/contacts/{id}/claim` | `manage_options` | Approve or reject an unverified entry |\n\n#### Claim Request Body\n\n```json\n{\n  "entry_id": 1042,\n  "action": "approve"   // or "reject"\n}\n```\n\n## Forminator Integration\n\nThe `Xophz_Compass_Quests_WPMUDEV` class hooks into `forminator_custom_form_submit_before_set_fields` to intercept every form submission. It reads the per-form field mappings from the `questbook_form_mappings` option (managed by the Magic Formula Questbook Sync UI) to dynamically resolve which Forminator fields map to CRM core fields.\n\n### Key Behaviors\n\n1. **No mapping exists** → Falls back to convention (`email-1`, `name-1`, `phone-1`)\n2. **Mapping exists but disabled** → Submission is completely ignored by the CRM\n3. **Mapping exists and enabled** → Uses the configured field keys for extraction\n4. **Unverified Protocol enabled** → Logged-out submissions matching existing contacts are quarantined\n\n## Feature Set (Planned)\n\n1. **Pipeline** — Kanban board for managing lead stages (New, Contacted, Qualified, Won, Lost)\n2. **Directory** — Filterable data table for bulk contact management\n3. **Journey Timeline** — Historical log of interactions and completed quests\n4. **Claim & Confirm** — Magic Cloak notification → `x-dialog` for data reconciliation\n\n## XP Integration\n\nQuestbook integrates directly with the `xophz-compass-xp` system. Completing specific "Quests" in a customer\'s journey can automatically grant `XP`, `AP`, or `GP` to the linked WordPress user, unified through the platform\'s Gamification engine.\n\n## Legacy Notes\n\nThe `class-xophz-compass-quests-rest.php` is the canonical REST implementation and should be used for all new development.\n',Ae=`# Treasure Trove & Treasure Map System

## Overview
The transition of YouMeOS toward a model of **Individual Sovereignty and Collective Unity** requires a concrete mechanism for users to own their data and receive value for their participation. The **Treasure Trove** and **Treasure Map** plugins act as the core architectural pillars for this vision.

- **Treasure Trove (The Sovereign Vault)**: Reimagined from a KPI dashboard into the user's centralized, encrypted personal data locker. It stores preferences, behavioral history, and sovereign identity artifacts.
- **Treasure Map (The Path to Rewards)**: Reimagined from a simple site walkthrough into a gamified discovery and reward engine. It ties the user's sovereign data participation and platform contributions to the YouMeOS XP system.

---

## 1. Treasure Trove: The Sovereign Vault Architecture

### Concept
Treasure Trove acts as the "Swiss Bank Account" for a user's digital soul. Instead of the platform owning the user's analytics and behavioral data, the data is encrypted and held in trust. The user holds the keys and decides *if* and *how* that data interacts with the collective Noosphere.

### Core Mechanisms
1. **Local Encryption (Simulated/Actual)**: 
   - Sensitive user activity, preferences, and form data are encrypted client-side or before database insertion. 
   - A master decryption key (derived from the user's session or a sovereign identity token) is required to parse the true payload.
2. **Data Categories**:
   - **Identity Data**: Personal details, profile configurations.
   - **Chronicle Data**: History of actions, posts (Post Digger), and interactions (Bug Catching Net).
   - **Contribution Data**: How much processing power/insight the user has donated to the Noosphere.
3. **The Data Ledger (UI)**:
   - A glassmorphic dashboard within the Spark that visualizes the "weight" of the user's data vault.
   - Granular privacy toggles: Users can toggle specific data clusters from "Private (Encrypted)" to "Shared (Noosphere)".

### Technical Flow
- **Storage**: Data is stored in customized WordPress tables (\`wp_compass_trove_ledgers\`), but the \`payload\` column contains AES-256 encrypted JSON.
- **Access**: When the user opens the Treasure Trove Spark, the Vue application requests their ledger. The backend serves the encrypted blob, and the Vue PWA (acting as a secure node) decrypts it client-side.

---

## 2. Treasure Map: The Discovery & Reward Engine

### Concept
If Treasure Trove is the vault, Treasure Map is how the user earns what goes into it. It visualizes the user's journey through YouMeOS and rewards them for embracing the "Ancient-Futuristic" protocols (like setting up security, contributing to the Noosphere, or configuring their Bomb Bag).

### Core Mechanisms
1. **Waypoints (Milestones)**:
   - Specific triggers across the COMPASS suite act as waypoints (e.g., "First Login", "Connected to Noosphere", "Encrypted First Payload").
2. **The Reward Matrix**:
   - Tied directly to the \`xophz-compass-xp\` system. 
   - Unlocking a waypoint on the Treasure Map grants XP, digital assets (like special celestial skyboxes), or new abilities within the 3D engine.
3. **The Celestial Map (UI)**:
   - An interactive, 3D/2.5D visual representation of the user's progress using the \`4d-youniverse\` engine. Instead of a flat website walkthrough, users navigate a literal "map" of their digital evolution.

### Integration with Treasure Trove
- **Data Dividends**: If a user toggles their Treasure Trove setting to "Share Anonymous Behavioral Data with Noosphere", the Treasure Map periodically generates "Passive XP Rewards". 
- **Proof of Sovereignty**: Completing the setup of the Treasure Trove is the primary quest on the Treasure Map, acting as the onboarding phase.

---

## 3. Implementation Roadmap

### Phase 1: Foundation (Current)
- [x] Redefine plugin metadata and descriptions to reflect the Sovereignty & Gamification vision.
- [ ] Establish \`wp_compass_trove\` database schema for encrypted payloads.
- [ ] Create the \`TreasureTroveView.vue\` and \`TreasureMapView.vue\` Spark entry points.

### Phase 2: The Vault Mechanics
- [ ] Implement client-side/server-side hybrid encryption utilities in \`youmeos.engine.ts\` or a new \`crypto\` sub-module.
- [ ] Build the UI for users to view and manage their data "clusters" (Private vs. Public).

### Phase 3: The Gamified Journey
- [ ] Connect Treasure Map event listeners to COMPASS global actions (e.g., \`do_action('compass_user_milestone_reached')\`).
- [ ] Render the visual Map using the existing WebGL/Three.js primitives in the YouMeOS engine.
- [ ] Issue XP rewards and trigger cinematic animations when a user claims a treasure.
`,Me=`# Magic Formula System

Magic Formula is the COMPASS proxy layer for [Forminator](https://wpmudev.com/project/forminator-pro/) (WPMU DEV). It provides both a COMPASS admin sub-app and a YouMeOS spark for managing and consuming forms, polls, and quizzes — all connected to the Questbook CRM for full-funnel lead tracking.

## Architecture: The Bridge Pattern

Magic Formula demonstrates the **Bridge Pattern** — where COMPASS (admin) and YouMeOS (public) share the same data layer but expose access-filtered interfaces:

| Layer | COMPASS (Admin) | YouMeOS (Public) |
|-------|-----------------|-------------------|
| **Layout** | \`x-sub-app-layout\` (billboard + side-nav) | \`u-window\` (spark shell) |
| **Navigation** | Full: Dashboard, Builder, Analytics, Config | Filtered: Available forms/polls/quizzes only |
| **API** | \`magic-formula/v1/*\` (all endpoints) | \`magic-formula/v1/forms\` (public published only) |
| **Naming** | Forms, Polls, Quizzes | Magic Formulas, Magic Quizzes, Magic Poll-ulas |

## PHP Plugin (\`wp-content/plugins/xophz-compass-magic-formula/\`)

| File | Purpose |
|------|---------|
| \`xophz-compass-magic-formula.php\` | Bootstrap — requires COMPASS + Forminator |
| \`includes/class-xophz-compass-magic-formula-rest.php\` | REST API proxy (\`magic-formula/v1/*\`) |
| \`includes/class-xophz-compass-magic-formula-wpmudev.php\` | Forminator hook intercepts (stub for future use) |
| \`public/class-xophz-compass-magic-formula-public.php\` | Spark registration + manifest |

## YouMeOS Spark (\`apps/youmeos/app/sparks/magic-formula/\`)

| File | Purpose |
|------|---------|
| \`magic-formula-app.vue\` | Root spark layout with sidebar nav and section routing |
| \`magic-formula-canvas.vue\` | Animated cauldron canvas for the Intro splash |
| \`views/v-formula-dashboard.vue\` | KPI overview + module cards |
| \`views/v-formula-detail.vue\` | Form detail (tabs: overview/submissions/settings) |
| \`views/v-crm-sync.vue\` | **Questbook Sync** — Schema Mapper UI for CRM field linking |
| \`molecules/m-form-card.vue\` | Form listing card component |
| \`molecules/m-ai-generator-dialog.vue\` | "Conjure with AI" dialog for AI-driven form generation |
| \`organisms/o-submissions-feed.vue\` | Submissions data feed organism |

### Navigation Structure

\`\`\`
── overview ─────────────
   Intro               Cauldron splash page
   Dashboard           KPIs, module cards, recent activity

── builder ──────────────
   Forms               List/manage forms → detail view
   Polls               List/manage polls
   Quizzes             List/manage quizzes
   Templates           Pre-built starting points

── analytics ────────────
   Submissions         All submissions across forms (with CRM status)
   Reports             Conversion funnels, trends

── config ───────────────
   Questbook Sync      Map form fields → CRM core fields
   Integrations        Questbook CRM, webhooks, email (Forminator iframe)
   Settings            Global form defaults, captcha (Forminator iframe)
   Add-ons             Payment gateways, conditional logic (Forminator iframe)
\`\`\`

## REST API Endpoints

| Method | Route | Auth | Description |
|--------|-------|------|-------------|
| \`GET\` | \`/wp-json/magic-formula/v1/forms\` | \`manage_options\` | All forms, polls, quizzes (normalized) |
| \`GET\` | \`/wp-json/magic-formula/v1/submissions/{id}\` | \`manage_options\` | Entries for a form with CRM status lookup |
| \`POST\` | \`/wp-json/magic-formula/v1/submit/{id}\` | Public | Submit form data through the proxy |
| \`POST\` | \`/wp-json/magic-formula/v1/conjure\` | \`manage_options\` | AI-generate a Forminator form from field descriptors |
| \`GET\` | \`/wp-json/magic-formula/v1/mappings\` | \`manage_options\` | Fetch all Questbook field mappings |
| \`POST\` | \`/wp-json/magic-formula/v1/mappings\` | \`manage_options\` | Save Questbook field mappings |

## CRM Integration (Questbook Sync)

Form submissions are linked to Questbook CRM contacts via the \`forminator_custom_form_submit_before_set_fields\` hook in \`xophz-compass-quests\`. The integration uses a **Link & Extract** pattern:

### Schema Mapper

Administrators configure per-form field mappings via the **Questbook Sync** tab in the Magic Formula spark. Mappings are stored as a single JSON option (\`questbook_form_mappings\`) in \`wp_options\`:

\`\`\`json
{
  "412": {
    "enabled": true,
    "useUnverified": true,
    "fields": {
      "_qb_raw_email": "email-1",
      "first_name": "name-1",
      "_qb_phone": "phone-1"
    }
  }
}
\`\`\`

### Core CRM Fields (Mapped)

| CRM Meta Key | Purpose |
|---------------|---------|
| \`_qb_raw_email\` | Primary identity resolution key |
| \`first_name\` | Contact display name (stored as \`post_title\`) |
| \`_qb_phone\` | Contact phone number |

### Linked Entry IDs (Not Mapped — Stored by Reference)

| CRM Meta Key | Purpose |
|---------------|---------|
| \`_qb_forminator_entry\` | Verified Forminator entry IDs (multi-value via \`add_post_meta\`) |
| \`_qb_unverified_entry\` | Unverified entry IDs pending Claim & Confirm |

All remaining form data stays in Forminator's native tables and is fetched on-demand via \`Forminator_API::get_entry()\`. This prevents CRM schema bloat regardless of how many custom fields exist on any form.

### Unverified Protocol

When a logged-out user submits a form and the email matches an existing CRM contact, the entry is stored as \`_qb_unverified_entry\` instead of being auto-linked. Upon login, the Magic Cloak notification system triggers a Claim & Confirm dialog where the user can approve or reject the merge.

## AI Conjure

The \`/conjure\` endpoint accepts an array of field descriptors (\`{ type, label }\`) and dynamically creates a Forminator form using \`Forminator_API::add_form()\`. The AI dialog in the spark generates these descriptors from a natural language prompt.
`,_e=`# GitHub Plugin Updater

The COMPASS core plugin includes a built-in GitHub Releases-based update system. It automatically detects new versions of all \`xophz-compass-*\` plugins and surfaces native WordPress update notifications — no third-party libraries needed.

## How It Works

### Auto-Discovery

When COMPASS loads, \`Xophz_Compass_Updater::init()\` scans all installed plugins via \`get_plugins()\`. Any plugin with a \`TextDomain\` starting with \`xophz-compass\` is automatically registered.

Each text domain maps to \`HalloftheGods/{text-domain}\` on GitHub.

### Update Check Flow

\`\`\`
WP cron triggers update check
  → pre_set_site_transient_update_plugins filter fires
  → For each registered plugin:
      → Fetch https://api.github.com/repos/HalloftheGods/{slug}/releases/latest
      → Compare tag_name (stripped of \`v\` prefix) to installed Version header
      → If newer: inject update object into transient
  → WP shows native update badge
\`\`\`

### Caching

GitHub API responses are cached in WordPress transients:
- **Success**: 12-hour TTL (\`xophz_gh_rel_{md5(repo)}\`)
- **Failure**: 1-hour TTL (avoids hammering on errors)

GitHub's public API rate limit is 60 requests/hour per IP. With ~25 plugins and 12-hour caching, this uses ~25 requests per check cycle — well within limits.

### Download Source

The updater looks for a \`.zip\` asset attached to the GitHub Release (created by the \`build-plugin-zip.yml\` workflow). Falls back to the GitHub-generated \`zipball_url\` if no asset exists.

### Icon Resolution

The update modal displays plugin icons fetched from the repo root:
1. \`icon.svg\` (preferred)
2. \`icon.png\` (fallback)

Loaded from \`raw.githubusercontent.com/{repo}/main/icon.*\`.

### Changelog

The GitHub Release body (markdown) is converted to HTML and displayed in the "View Details" modal under the Changelog tab.

## Requirements for New Plugins

To participate in the update system, a new plugin needs:

1. **TextDomain** starting with \`xophz-compass\` in the plugin header
2. **A matching GitHub repo** at \`HalloftheGods/{text-domain}\`
3. **The shared CI workflow** (\`auto-version.yml\`) to create tagged releases with zip assets
4. **\`Update URI\` header** — automatically injected by the CI workflow on first release

That's it. No registration code needed in the child plugin.

## Update URI Header

The \`wp-plugin-update.yml\` workflow automatically injects this header if missing:

\`\`\`
* Update URI:        https://github.com/HalloftheGods/{repo-name}
\`\`\`

This tells WordPress 5.8+ not to check wordpress.org for updates to this plugin, preventing slug collisions.

## File Locations

| File | Purpose |
|------|---------|
| \`xophz-compass/includes/class-xophz-compass-updater.php\` | The updater class |
| \`xophz-compass/includes/class-xophz-compass.php\` | Loads and initializes the updater |
| \`.github/workflows/wp-plugin-update.yml\` | CI: version bump + Update URI injection |
| \`.github/workflows/build-plugin-zip.yml\` | CI: builds and attaches zip to release |

## **Updates not showing?**
- Check that a GitHub Release exists with a tag newer than the installed version.
- Force a refresh by visiting: \`YOUR_SITE_URL/wp-admin/plugins.php?xophz_force_update=1\`
- This clears all cached GitHub responses and the WordPress update transient.

**Updates disappeared after initially showing?**
- This usually happens if the GitHub API rate limit is hit (60 requests/hour for public API).
- The system now includes a "Stale-While-Revalidate" fallback that stores the last known good release in the \`wp_options\` table.
- If the API call fails (due to rate limits or network issues), the system will fall back to the last successfully fetched version.
- Errors are now only cached for 5 minutes (instead of 1 hour) to allow for faster recovery.

**Rate limited?**
- The 60 req/hour public limit can be hit if you have many plugins (40+) and check frequently.
- Avoid rapid clicking of "Check Again" on the Updates page.
- If you need more headroom, a GitHub token can be added to the \`wp_remote_get\` headers (1000 req/hour).
`,Re=`# Magic Door — Multi-Theme Domain Mapping

## Overview

Magic Door is a feature within the **Moving Castle** plugin that enables a single WordPress installation to serve different themes based on the incoming request context. Inspired by the magic door in *Howl's Moving Castle*, each configured "door" maps a trigger condition (domain, subdomain, user role, or URL parameter) to a specific installed WordPress theme.

## Architecture

### PHP Backend

**File:** \`includes/class-xophz-compass-moving-castle-magic-door.php\`

The Magic Door class handles:
- **REST API endpoints** for CRUD operations on door configurations
- **Theme override logic** via WordPress \`template\` and \`stylesheet\` filters
- **Persistence** via \`wp_options\` table under the key \`mc_magic_doors\`

### REST API Endpoints

| Method   | Endpoint                                | Purpose                       |
|----------|-----------------------------------------|-------------------------------|
| \`GET\`    | \`/moving-castle/v1/magic-door/themes\`   | List all installed WP themes  |
| \`GET\`    | \`/moving-castle/v1/magic-door/doors\`    | List all configured doors     |
| \`POST\`   | \`/moving-castle/v1/magic-door/doors\`    | Create a new door             |
| \`PUT\`    | \`/moving-castle/v1/magic-door/doors/:id\`| Update a door                 |
| \`DELETE\` | \`/moving-castle/v1/magic-door/doors/:id\`| Delete a door                 |
| \`POST\`   | \`/moving-castle/v1/magic-door/doors/:id/toggle\` | Toggle active state |

All endpoints require \`manage_options\` capability.

### Theme Override Mechanism

The \`apply_theme_override()\` method hooks into \`setup_theme\` — the earliest WordPress lifecycle hook where theme switching is effective. It:

1. Loads all active doors from \`wp_options\`
2. Evaluates each door's trigger against the current request
3. On first match, applies \`add_filter('template', ...)\` and \`add_filter('stylesheet', ...)\` to override the active theme

### Trigger Types

| Type        | Evaluates Against              | Example                    |
|-------------|-------------------------------|----------------------------|
| \`domain\`    | \`$_SERVER['HTTP_HOST']\`       | \`cool-brand.com\`           |
| \`subdomain\` | \`$_SERVER['HTTP_HOST']\`       | \`beta.yoursite.com\`        |
| \`role\`      | Current user's WordPress role | \`administrator\`            |
| \`param\`     | \`$_GET\` query parameters      | \`?door=red\`                |

### Door Data Schema

\`\`\`json
{
  "id": "unique_string",
  "label": "The Red Door",
  "triggerType": "domain",
  "trigger": "cool-brand.com",
  "themeSlug": "astra",
  "themeName": "Astra",
  "color": "red",
  "active": true
}
\`\`\`

## Frontend

### API Layer
**File:** \`src/routes/moving-castle/moving-castle.api.ts\`

Methods: \`getInstalledThemes()\`, \`getDoors()\`, \`createDoor()\`, \`updateDoor()\`, \`deleteDoor()\`, \`toggleDoor()\`

### View
**File:** \`src/routes/moving-castle/views/MagicDoorView.vue\`

**Route:** \`#/moving-castle/magic-door\`

Features:
- Circular dial UI inspired by Howl's door mechanism
- Door list with real-time toggle switches
- Config dialog with trigger type selection, theme dropdown (fetched from WP), and color picker
- All operations are persisted to the WordPress database via REST API

## Use Cases

1. **White-labeling:** \`client-a.com\` and \`client-b.com\` both point to the same WP install but display completely different themes
2. **Staging new designs:** Map \`beta.yoursite.com\` to a new theme, test it with real production data
3. **Role-based theming:** Admins see a dashboard theme; regular visitors see the public theme
4. **Campaign landing pages:** Visitors arriving via \`?door=promo\` get a special promotional theme

## DNS Requirements

For domain/subdomain triggers, the DNS records must point to the WordPress server. WordPress itself doesn't need multisite — the Magic Door handles the theme routing at the application layer.
`,Ie=`# The Hall of the Gods Ecosystem Architecture

While this repository (**COMPASS**) focuses entirely on the "Systems Interface" and tools for WordPress, it sits within a much larger spatial computing infrastructure. This document outlines the grand vision and the century-scale architecture that developers and contributors must understand when working on the codebase.

We are not building a simple web UI; we are building the operational layers of a $1T spatial deployment network.

## The Trillion-Dollar Stack (TDS)

The ecosystem is divided into five distinct foundational layers, moving from the physical protocol to the overarching cultural genesis.

### 1. The Protocol: w⁴ (Worldwide Webwork)
The bedrock layer. The w⁴ Protocol takes the traditional, flattened "World Wide Web" and adds a fourth dimension: The Work. It is a highly-pressurized, spatial deployment grid leveraging the undeniable dominance of WordPress (45% of the web). It provides a self-healing, hyper-cube architecture where "websites" cease to be 2D brochures and become nodes in a sovereign network.

### 2. The Nodes: BlackBOX
A BlackBOX is the individual, isolated sovereign environment (node) deployed onto the w⁴ grid. It represents a user's absolute digital sovereignty.
*   **Build a BLOX**: The mechanism by which individuals forge their own node.
*   **BlackBOX WhiteGLOVE**: The managed concierge service where our architects steward the node's growth on the high-frequency network.

### 3. The Interface: My Compass Suite
**_(This is where Xophz-COMPASS lives)_**
Raw server power and spatial grids are chaotic and inaccessible to most. The **Compass Suite** is the internal command center installed in every BlackBOX. It translates that raw industrial power into an elegant, gamified user experience. 

It strips away legacy WordPress bloat and replaces it with functional superpowers:
*   *Force Field* (Security)
*   *Time Machine* (Restoration/Redundancy)
*   *Lens of Truth* (Analytics)
*   *Magic Formulass* (Interaction)

### 4. The Digital Cosmos: YouMeOS
The horizon of our infrastructure. When BlackBOXes are woven together, they form a living, interactive network. **YouMeOS** (OS officially representing the **Omega Source**, though it can universally stand for Operating System or whatever fits the user's worldview) operates as the personal spatial environment connecting these decentralized nodes, moving humanity away from rented social media silos and back into sovereign web ownership.

### 5. The Genesis Point: Hall of the Gods
The overarching Nexus. The Hall is the cultural and structural entity that guides the webwork. Founded as a gathering place for creators—the "gods" of this age—to build, debate, and impose their will on the void. The Hall of the Gods is the architect behind the w⁴ Protocol.

---

## Why Understanding The Stack Matters

As you write code in the \`Xophz-COMPASS\` repository, you must remember that you are constructing **Layer 3**. 

Every atom, component, and Vue view you build isn't just updating a WordPress plugin—it is the control interface for a sovereign BlackBOX node. The UI must feel like a starship HUD (Heads-Up Display) because it literally controls the user's trajectory through the w⁴ spatial network. 

> *"People are looking at decades. We are looking at centuries."*

Design with extreme precision, ensure zero-lag execution, and build for scale.
`,Ee=`# Project Compass Philosophy

## Core Mission

**Simplifying the WordPress Admin User Experience (UX).**

The goal of Compass is to transform the traditional, often cluttered WordPress dashboard into a sleek, responsive, and intuitive "Command Center." We aim to empower users to manage their WordPress sites effortlessly, specifically prioritizing a **Mobile-First** approach.

## Design Pillars

### 1. Mobile-First & Responsive

- **Manage from Anywhere**: The interface interprets complex admin tasks into touch-friendly interactions.
- **Scalability**: UI elements scale gracefully from a desktop "cockpit" view down to a phone's "handheld scanner" view without losing functionality.

### 2. Aesthetic Vision: "Sleek, Futuristic, Modern"

- **Visual Style**: High-end Glassmorphism, deep layered backgrounds, and neon/fluorescent accents (Cyan/Blue #62c9ff).
- **The "Nerdy" Twist**: We embrace the "System" aesthetic. HUD (Heads-Up Display) elements, micro-animations, monospace data readouts, and gyroscope-like rings. It should feel like operating a starship or high-tech machinery.
- **Typography**: Modern geometric sans-serifs (Cairo, Rajdhani) paired with technical monospace fonts for data.

## Terminology & Taxonomy

The project uses a celestial metaphor to represent the digital architecture:

- **YouMeOS**: The system as a whole.
- **Youniverse**: An individual instance/galaxy (the user-facing portal).
- **Star**: A user within the system.
- **Main Star**: The primary site.
- **Nexos**: The "Hippocros" level visualizing relationships.
- **Oort Cloud**: A user's content cloud (links, media, consumption).
- **Satellites**: Functional modules and tools (Wapps).
- **The Sun**: The central dashboard of a Star.

See \`docs/Taxonomy.md\` for full definitions.

## UX Principles

- **Surprise & Delight**: Interfaces should not just be functional; they should be satisfying to use. (e.g., The "flip" of the compass intro).
- **Focus**: Reduce cognitive load by hiding unnecessary WP clutter and surfacing the most vital controls.
- **Direct Manipulation**: Interactive elements (toggles, sliders, wheels) over static input fields where possible.
- **Celestial Navigation**: Moving through the site should feel like traversing space, with the "Compass" as your guide between the Galaxy (Global), Nexos (Social), and Sun (Personal) levels.
`,De=`# Versioning System

The Xophz-COMPASS ecosystem uses an automated date-based versioning system to ensure continuous delivery and clear release tracking.

## Version Format

Versions follow the pattern: \`vYY.M.D[rcTS]\`

- **YY**: Last two digits of the year (e.g., \`26\`).
- **M**: Month without leading zeros (e.g., \`4\` for April).
- **D**: Day of the month without leading zeros (e.g., \`11\`).
- **rcTS** (Optional): Release Candidate suffix. \`TS\` is the total seconds elapsed in the day (UTC), used for unique push-based versions.

### Examples
- \`v26.4.11rc46986\`: A release candidate generated during a push on April 11, 2026.
- \`v26.4.11\`: The final daily version for April 11, 2026.

## Workflows

### 1. Auto Version (Push-to-Main)
- **File**: \`.github/workflows/auto-version.yml\`
- **Trigger**: Every push to the \`main\` branch.
- **Action**: Generates an \`rc\` version, updates \`package.json\`, and creates a Git tag.
- **Purpose**: Rapid iteration and testing versions.

### 2. Daily Final Version (Schedule)
- **File**: \`.github/workflows/daily-final-version.yml\`
- **Trigger**: Every day at **11:59:00 PM UTC** (\`59 23 * * *\`).
- **Action**: Generates the final daily version (strips \`rc\`), updates \`package.json\`, and creates/overwrites the daily tag.
- **Purpose**: Stable daily snapshot for production-ready states.

## Manual Trigger
Both workflows can be triggered manually via the GitHub Actions "Run workflow" button for out-of-band releases.
`,Oe='# Wizards Tower — Feature Flag System\n\n## Overview\n\nThe Wizards Tower is a developer-only spark that provides a UI for managing feature flags across YouMeOS. It controls which sparks and menu items are visible based on access tiers.\n\n## Architecture\n\n### Two-Layer System\n\n1. **Build-Time Config** (`src/config/feature-flags.ts`) — Declares default status for every spark\n2. **Runtime Overrides** (`localStorage: youmeos:feature-flag-overrides`) — UI-driven overrides merged at module load\n\n### Access Tiers\n\n| Tier        | Description                                      | Sees Statuses            |\n|-------------|--------------------------------------------------|--------------------------|\n| `public`    | Standard users                                   | `stable`                 |\n| `beta`      | Beta testers                                     | `stable`, `beta`         |\n| `alpha`     | Alpha testers                                    | `stable`, `beta`, `alpha`|\n| `developer` | Auto-enabled on `localhost` / `import.meta.env.DEV` | Everything except `disabled` |\n\n### Feature Statuses\n\n| Status     | Build Included | Menu Visible     | Who Sees It           |\n|------------|----------------|------------------|-----------------------|\n| `stable`   | ✅             | ✅               | Everyone              |\n| `beta`     | ✅             | Tagged with badge| Beta testers + devs   |\n| `alpha`    | ✅             | Hidden by default| Alpha testers + devs  |\n| `disabled` | ❌ (tree-shaken) | ❌             | Nobody                |\n\n## Key Files\n\n| File | Purpose |\n|------|---------|\n| `src/config/feature-flags.ts` | Feature flag declarations + localStorage override merging |\n| `src/config/feature-access.ts` | Access tier resolution, `canAccess()` utility |\n| `src/stores/launcher.store.ts` | Filters `visibleApps` through feature flags |\n| `apps/youmeos/app/index.ts` | Filters `systemRegistry` through feature flags |\n| `apps/youmeos/app/sparks/wizards-tower/` | The Wizards Tower spark UI |\n\n## Usage\n\n### Adding a New Feature Flag\n\n1. Add the entry to `FEATURE_FLAGS` in `src/config/feature-flags.ts`:\n   ```typescript\n   "my-new-spark": { status: "alpha", label: "My New Spark" }\n   ```\n\n2. The spark will automatically be filtered from the launcher and system registry unless the user has alpha/developer access.\n\n### Setting Access Tier\n\n- **Development**: Automatically `developer` tier — sees everything\n- **Production**: Open Wizards Tower → Access Tier → Select tier\n- **localStorage key**: `youmeos:access-tier`\n\n### Overriding Flags via UI\n\n1. Open Wizards Tower spark\n2. Navigate to "Feature Flags" tab\n3. Change status via dropdown or quick toggle\n4. Click "Save & Reload"\n5. Overrides are stored in `localStorage: youmeos:feature-flag-overrides`\n\n## Integration Points\n\nThe feature flag system hooks into two filtering layers:\n\n1. **`launcher.store.ts` → `visibleApps`**: Controls what appears in the app launcher, user rail, and category filters\n2. **`app/index.ts` → `systemRegistry`**: Controls what components are loaded into the webtop\'s active app renderer\n\nBoth layers call `canAccess(status, tier)` from `feature-access.ts`.\n',Le=`# Omega Source (Virtual Passport)

The Omega Source is a YouMeOS spark that serves as the user's **public-facing identity card** — a virtual passport / business card combining personal branding, expertise, and social presence into a single shareable profile.

## Spark Metadata

| Field       | Value                  |
|-------------|------------------------|
| ID          | \`omegafile\`            |
| Icon        | \`fal fa-id-card\`       |
| Brand Color | \`#62c9ff\`              |
| Category    | Gateways (portal)      |
| Location    | \`apps/youmeos/app/sparks/omegafile/\` |

## Architecture

\`\`\`
sparks/omegafile/
├── omegafile-app.vue              # Main u-window spark template
└── omegafile-app.controller.ts    # Composable with all state & logic
\`\`\`

### Data Sources
- **useAuth** — Display name, email, nicename
- **useXpStore** — XP, GP, AP, RP, level, avatar
- **useWeb3Gateways** — Wallet address, server profile (headline, location)

## Tabs

### 1. Passport (Identity Card)
The core virtual passport card with:
- **Avatar** with level-colored ring border (cyan → green → red → purple)
- **Headline** — pulled from server profile or defaults to "Sovereign Digital Citizen"
- **Location** — optional geolocation string
- **Pulse Rating** — 1-5 bar self-assessment indicator
- **Stat Bar** — AP, RP, GP, Level at a glance
- **My Reflection** — Three expandable entries:
  - My Passion
  - My Purpose
  - My Power

### 2. Growth (Room to Grow)
Professional development section with:
- **Expert In** — Taggable skill/job title chips (add/remove)
- **Credentials** — Verified credentials with special glow when confirmed
- **Skills** — Documented skill list
- **Resources** — Available resources
- **References** — Professional references

### 3. Links (Social Presence)
Web presence aggregation:
- **Social Links** — Website, Blog, GitHub, LinkedIn, X/Twitter
- **Wallet** — Connected Ethereum address (from Passport/MetaMask)

## Navigation Sidebar
The left nav panel displays:
- User avatar with level-colored ring
- Display name and headline
- Location
- Tab navigation (Passport / Growth / Links)
- Level chip + RP chip
- XP progress bar

## Wireframe Mapping

From the original wireframe sketch:

| Wireframe Element       | Implementation                     |
|-------------------------|------------------------------------|
| Avatar area             | \`v-avatar\` with \`omegafile-avatar-ring\` |
| Username                | \`displayName\` computed             |
| Frame color by type     | \`profileFrameColor\` by level tier  |
| My Pulse (1-5)          | \`pulseRating\` with dot indicators  |
| My Reflection entries   | \`reflections\` array with expand    |
| Expert In / tags        | \`expertTags\` with v-chip + add/remove |
| Credentials             | \`credentials\` ref (placeholder)    |
| Skills                  | \`skills\` ref (placeholder)         |
| Resources               | \`resources\` ref (placeholder)      |
| References              | \`references\` ref (placeholder)     |
| Social links / websites | \`socialLinks\` array                |
| Prana/XP stats          | AP, RP, GP, Level stat boxes       |
| Color Guard (badges)    | Level-based \`profileFrameColor\`    |

## Future Enhancements
- Persist reflection entries via WP REST API
- Save/load expert tags, credentials, skills, resources, references
- IPFS publication of public profile data
- QR code generation for profile sharing
- Color wheel for 12-category resonance mapping
- "Room to Growth" iteration tracking
- Triple DNA Strand Rating System (Quality, Integrity, Service)
`,We=`# Helios Modules Documentation

## Overview
The Helios spark serves as the central hub for user identity, inner reflection, and personal growth within the YouMeOS network. It is architected around planetary/solar archetypes, divided into distinct domains of consciousness.

## Architecture

Helios is built as a primary OS application ("Spark") and utilizes standard Project Compass glassmorphic components (\`<u-window>\`, \`<u-spark-view>\`, \`<u-glass-card>\`) combined with \`<v-window>\` and \`<v-bottom-navigation>\` elements for interior module routing.

### Core Modules

1. **I Am (Sun) - \`HeliosStellarCommand.vue\`**
   - **Route:** \`my-center\`
   - **Purpose:** Core overview, mission statement, public profile, and spectral frequency. Captures the central user identity.
   - **Features:** A profile configuration area syncing with WordPress User meta data via the Event Horizon layer.

2. **Loving Awareness (Venus) - \`HeliosHeart.vue\`**
   - **Route:** \`helios-heart\`
   - **Purpose:** Interpersonal connection, release of density, self-love, and energetic frequency resonance.
   - **Features:** Incorporates an interactive "Release Void" mechanism, forgiveness affirmations, and emotional resonance tags.

3. **Light & Shadow (Moon) - \`HeliosVault.vue\`**
   - **Route:** \`helios-vault\`
   - **Purpose:** Subconscious reflections, shadow work, trigger logs, and the astral vault.
   - **Features:** Safe storage interface (Memory Crystals) with categorized tags for Dreams, Shadow Work, and Insight logs.

4. **Voice & Vibration (Mercury) - \`HeliosCommunication.vue\`**
   - **Route:** \`helios-communication\`
   - **Purpose:** Message management, inbound/outbound communication filtering, and vocal resonance settings.
   - **Features:** Notification thresholds and focus-state signal filtering.

5. **Drive & Discipline (Mars) - \`HeliosDrive.vue\`**
   - **Route:** \`helios-drive\`
   - **Purpose:** Tracking active endeavors, kinetic output, willpower, and disciplined energy.
   - **Features:** A dynamic drive score and an interactive tracker for morning protocols, flow sessions, and other disciplined endeavors.

6. **Fortune & Expansion (Jupiter) - \`HeliosWallet.vue\`**
   - **Route:** \`helios-wallet\`
   - **Purpose:** Karmic wealth aggregation, resource alignment, and expansion vectors.
   - **Features:** Real-time generation visualization of karmic credits and tracking of professional vs. artistic expansion vectors.

7. **Time & Structure (Saturn) - \`HeliosIdentity.vue\`**
   - **Route:** \`helios-identity\`
   - **Purpose:** Long-term responsibilities, karmic structure, and timeline commitments.
   - **Features:** Comprehensive milestone timeline and time auditing dashboard showing structural boundaries and blockages.

## Component Philosophies

- **Zero-Entropy Logic:** All interior Helios modules focus on single-responsibility interfaces.
- **Starship Aesthetics:** Dark mode default using \`bg-transparent\` overlays over the OS desktop. Sub-surfaces use \`<u-glass-card>\` with varying opacities and subtle neon borders.
- **Atoms First:** Extensive usage of standard Vuetify primitives matched with atomic wrapper styling or OS-specific custom atoms where practical.
`,ze='# Legacy Engine Mechanics & Architecture\n\n> [!NOTE]\n> This document records R&D findings regarding the legacy Three.js engine (r62) located in `wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/main.js`. Use this reference when modifying core universe behavior.\n\n## Core Scene Graph Hierarchy\n\nThe engine uses a specific hierarchy of `THREE.Object3D` containers to manage different types of potential movement.\n\n1.  **`scene`** (Root)\n    *   **`rotating`** (`THREE.Object3D`)\n        *   **Purpose**: Controls the **Camera\'s Orbital Rotation** (View Rotation).\n        *   **Behavior**:\n            *   Rotated by user input (mouse drag) via `rotateX` and `rotateY` global variables.\n            *   "Pinning" works by damping `rotateVX` and `rotateVY` to zero when dragging stops.\n        *   **Children**:\n            *   **`galacticCentering`** (`THREE.Object3D`)\n                *   **Purpose**: An intermediate container, likely for centering offsets.\n                *   **Children**:\n                    *   **`translating`** (`THREE.Object3D`)\n                        *   **Purpose**: Controls **Linear Movement** (Panning/Zooming target).\n                        *   **Behavior**: Interpolates position to `targetPosition`.\n                        *   **Contents**: All actual celestial objects live here.\n\n## Global Objects of Interest\n\nThese global variables are defined in `main.js` and can be manipulated in the `animate()` loop.\n\n| Variable | Type | Description |\n| :--- | :--- | :--- |\n| `pGalacticSystem` | `THREE.ParticleSystem` | The main galaxy swirl (generated by `galaxy.js`). Rotation here spins the galaxy *internally*. |\n| `pSystem` | `THREE.ParticleSystem` | The Hipparcos star data (100,000 stars). |\n| `starModel` | `THREE.Object3D` | The generated procedural star models (when enabled). |\n| `solarSystem` | `THREE.Object3D` | The solar system container. |\n| `camera` | `THREE.PerspectiveCamera` | The main camera. Note: In this engine, the *camera* usually stays at `z` distance, while the *universe* (`rotating`) rotates around it. |\n\n## Rotation Mechanics\n\n### 1. View Rotation ("Orbiting")\nThe user doesn\'t actually rotate the camera around the objects; they rotate the **Universe Container** (`rotating`) in front of the camera.\n\n```javascript\n// Found in animate()\nrotating.rotation.x = rotateX;\nrotating.rotation.y = rotateY;\n```\n\n### 2. Internal Rotation ("Spinning")\nTo make an object spin continuously *regardless* of view rotation (like a galaxy swirling on its axis), you must rotate the object itself within the `translating` container.\n\n```javascript\n// Added to animate() for Galaxy Swirl\nif (pGalacticSystem) {\n    pGalacticSystem.rotation.y += 0.0008; // Continuous internal rotation\n}\n```\n\n## Grid Mechanics\n\nThe engine historically uses multiple grid layers to provide spatial reference.\n\n| Grid Layer | File | Geometry | Material | Texture |\n| :--- | :--- | :--- | :--- | :--- |\n| **Galactic Grid** | `galaxy.ts` | `IcosahedronGeometry` (scaled 1.15, 0.15, 1.15) | `MeshBasicMaterial` (Wireframe) | `glowspan.png` |\n| **Solar Plane** | `plane.js` | `CylinderGeometry` (scaled 1.0, 0.001, 1.0) | `MeshBasicMaterial` (Wireframe) | `glowspan.png` |\n| **Star Lattice** | `sun.js` | `IcosahedronGeometry` (radius * 1.25) | `MeshBasicMaterial` (Wireframe) | `glowspan.png` |\n\n**Texture Manipulation**:\nThe "flowing" effect is achieved by decrementing the texture offset in the `animate` loop or an object-specific `update` function:\n\n```javascript\n// Example from legacy animate loop\nmesh.material.map.offset.y -= 0.001;\n```\n\n## "Pinning" vs. Auto-Rotate\n\n-   **Pinning**: The user clicks and drags. This stops the *View Rotation* (the `initialAutoRotate` flag is often used to re-enable view rotation after inactivity, but the legacy code relies on friction).\n-   **Simultaneous Rotation**: By separating View Rotation (`rotating.rotation`) from Internal Rotation (`pGalacticSystem.rotation`), we can have the galaxy swirl eternally even while the user has "pinned" the view angle.\n\n## File Locations\n\n-   **Engine Entry**: `wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/main.js`\n-   **Manifest**: `.../youmeos_legacy/manifest.json` (Controls script loading order)\n-   **Galaxy Logic**: `.../youmeos_legacy/js/galaxy.js`\n',Be=`# OS Architecture & Design

## Overview

YouMeOS aims to replicate a desktop operating system feel within a web browser. This requires moving beyond standard web routing to a stateful, window-based architecture where multiple "Applications" can coexist, overlap, and interact.

## 1. The Window Manager (\`wm\`)

The Window Manager is responsible for rendering, positioning, and managing the lifecycle of window containers (\`x-window\`).

### Core Responsibilities

- **Z-Index Management**: Ensuring the focused window is always on top.
- **Focus Management**: Tracking the \`activeWindowId\`.
- **Geometry**: Handling dragging, resizing, maximizing, and minimizing.
- **Constraints**: Keeping windows within the viewport (the "Desktop").

### Component: \`<x-window>\`

A new construct that mimics a native OS window.

- **Props**: \`title\`, \`icon\`, \`width\`, \`height\`, \`x\`, \`y\`, \`is-resizable\`, \`is-minimizable\`.
- **Slots**: \`header\`, \`toolbar\`, \`default\` (content), \`footer\`.
- **Events**: \`close\`, \`minimize\`, \`maximize\`, \`focus\`, \`move\`, \`resize\`.

### Store: \`useOsStore\`

A Pinia store to manage the state of the OS.

\`\`\`typescript
state: () => ({
  windows: [
    { id: 'app-settings', appId: 'settings', zIndex: 10, state: 'normal', ... }
  ],
  activeWindowId: 'app-settings',
  nextZIndex: 100
})
\`\`\`

## 2. The Application Model

An "App" in YouMeOS is more than just a route; it is a self-contained unit of functionality with metadata and lifecycle events.

### App Definition

Apps are defined in a registry (likely \`apps.config.ts\`) with a standardized interface:

\`\`\`typescript
interface AppDefinition {
  id: string; // Unique slug (e.g., 'calculator', 'file-explorer')
  name: string; // Display name
  icon: string; // Iconify key or image URL
  component: Component; // Vue component to render inside the window
  defaultSize: { w: number; h: number };
  singleton: boolean; // If true, only one instance allowed
}
\`\`\`

### App Lifecycle

- **Launch**: Triggered via Start Menu or Desktop. Creates a new Window entry in \`useOsStore\`.
- **Suspend/Background**: When minimized or obscured.
- **Terminate**: When the window is closed.

## 3. Process Management & Taskbar

The system must track "Running Processes" to display them in the Taskbar (or Dock).

- **Taskbar Item**: Represents a window (or group of windows for the same app).
- **Context Menu**: Right-click to "Close", "Minimize", "Pin".
- **Visual Feedback**: Active apps have a "glow" or indicator.

## 4. The UI Shell

The Shell is the container for the entire OS experience.

- **Desktop**: The background area where windows float. Supports icons/shortcuts.
- **System Bar / Taskbar**: Global navigation, clock, system tray, start menu anchor.
- **Start Menu / Launch Pad**: A centralized grid or list of all installed App Definitions.

## 5. File System Abstraction (VFS)

(Future Scope)
To support a true OS feel, we need a Virtual File System (VFS) to standardize how apps access data.

- **Drive Mapping**:
  - \`home://\` -> User's local settings/data.
  - \`wp://\` -> WordPress Media Library.
- **File Explorer**: An app to browse these paths.

## 6. Integration with Routes

While the OS uses windows, we still need to support deep linking.

- **URL Mapping**: \`/os/app/settings\` -> Boots the OS and immediately launches the Settings app.
- **Browser History**: Opening an app might push a state to the history stack (optional).
`,Ue=`# Spark Federation Protocol & Security Architecture

## Overview
The "Spark Federation" is the protocol that elevates YouMeOS from a solitary application into an interconnected "OS for the Internet." It allows independent WordPress instances running the Xophz-COMPASS plugin to embed each other natively as "Sparks" (mini-browser windows) within the YouMeOS 3D desktop environment.

To achieve this decentralized network without compromising the sovereignty and security of the user, the protocol must tightly navigate modern web browser restrictions.

## Security Gotchas & Architectural Solutions

### 1. The Third-Party Cookie Apocalypse
**The Threat:** Modern browsers (Safari, Firefox, Chrome) block third-party cookies by default to prevent tracking. If \`client.com\` is rendered inside an iframe on \`xopher.com\`, the browser views \`client.com\` as a third-party and blocks access to its authentication cookies, effectively logging the user out within the Spark.

**The Solution: Token-Based Auth Bridge**
YouMeOS nodes cannot rely on legacy HTTP cookies when embedded.
- Implement a decoupled, token-based authentication bridge (e.g., JWT).
- When Node A (host) embeds Node B (child), auth state must be securely passed via \`window.postMessage\` or persisted in partitioned memory, bypassing the cookie restriction entirely.

### 2. The Clickjacking Reversal
**The Threat:** To permit embedding, a YouMeOS node must disable the standard \`X-Frame-Options\` defense. Doing this globally would allow malicious actors to invisibly embed the node on nefarious sites to hijack user clicks (Clickjacking).

**The Solution: Dynamic Content Security Policies (CSP)**
- Never globally disable \`X-Frame-Options\` or permit \`frame-ancestors *\`.
- Implement a rigid CSP \`frame-ancestors\` directive that is dynamically generated. 
- A node only drops its shields if the requesting URL is explicitly whitelisted or cryptographically verified as an authorized YouMeOS node on the user's connection network.

### 3. Cross-Site Scripting (XSS) & \`postMessage\` Poisoning
**The Threat:** Federation requires the parent OS and the embedded Spark to communicate (e.g., transmitting identity or gamification data) via \`window.postMessage\`. If listeners blindly accept payloads, a malicious node could inject scripts or execute destructive commands.

**The Solution: Zero Trust Messaging**
- Strict \`origin\` verification must be enforced on every single \`message\` event listener.
- Sensitive payloads (Identity transfers, XP events) must be cryptographically signed to ensure data integrity and proof of origin mid-flight.

### 4. Resource & GPU Exhaustion 
**The Threat:** If a YouMeOS instance embeds another full YouMeOS instance, multiple heavy WebGL/Three.js contexts will attempt to initialize simultaneously. This will instantly devour the client's GPU and RAM, crashing the browser.

**The Solution: 2D-Only "Embedded Mode"**
- The parent OS handles *all* 3D space rendering.
- When a YouMeOS instance boots, it must execute a context check: if it is running inside an iframe (\`window.self !== window.top\`), it triggers "Embedded Mode."
- In Embedded Mode, the application completely bypasses the 3D engine initialization and *only* serves the 2D HTML/Vue layers (the dashboards, lists, and glass UI).
- The parent window manager will also actively pause iframe execution for Sparks that are minimized or out of focus.
`,Ge=`---
title: Unified Contacts & Address Book
date: 2026-04-05
status: Draft / Brainstorming
category: Blueprint
---

# Unified Contacts & Address Book Blueprint

This document outlines the proposed architecture and integration path for a centralized Address Book ("Contacts Spark") within YouMeOS. The goal is to allow users to pull in and merge their contacts from external ecosystems (Google, Apple, Microsoft, GitHub) into a single, cohesive interface.

## Core Philosophy

Following modern OS paradigms (macOS, GNOME, Windows), YouMeOS will manage authentication and sync permissions globally via the system **Settings** Spark, rather than embedding complex OAuth flows directly into the Contacts app. The Contacts app itself acts as a lightweight display and organization layer over the synchronized data.

### Outstanding Design Decisions

1. **Data Persistence**
   - *Option A: Browser Local* (\`IndexedDB\`). Maximum privacy and zero backend overhead, but contacts exist only on the current device unless the user goes through the OAuth flow on every device.
   - *Option B: Cloud Hosted* (WordPress Event Horizon API). Synced securely to the user's data vault, persisting across devices automatically.
   - *Current Stance:* Actively debating. Likely starting with Browser Local Storage to reduce initial friction.

2. **OAuth Onboarding**
   - Requires setting up respective apps on Google Cloud Console and Microsoft Entra ID. 
   - *Current Stance:* On hold. When implemented, initial development will use mock adapters to build the UI before wiring real keys.

## Proposed Architecture

### 1. Synchronization Engine (\`useContactSync\`)

A dedicated composable (e.g., \`useContactSync.ts\`) or Pinia store that acts as the pipeline:
- Monitors the authenticated accounts list.
- Interrogates the "Sync Contacts" preference mapped to each account.
- Maps varying API response formats into a standardized \`CompassContact\` TypeScript interface.
- Executes deduplication logic (merging records with identical emails or phone numbers).

### 2. OS Settings Integration

The existing \`OnlineAccountsView.vue\` in the Settings spark will serve as the control center.
- **Location:** \`apps/youmeos/app/sparks/settings/views/OnlineAccountsView.vue\`
- **Additions:** Beneath each account's "Connect" option, a \`<v-switch>\` or equivalent checkbox will be offered specifically for "Sync Contacts".
- Toggling this will trigger the Synchronization Engine to perform a fetch or wipe.

### 3. The Contacts Spark (UI)

A dedicated spark established purely for the address book function.
- **Location:** \`apps/youmeos/app/sparks/contacts/\`
- **Layout Flow:** Split-pane design, with an alphabetical, virtualized list on the left and full contact detail view on the right.
- **Visuals:** Uses the \`x-avatar\` component. Profiles originating from connected accounts will bear a small provider badge (e.g., a tiny Google 'G' icon in the lower corner of the avatar).

## Implementation Steps (When Ready)

1. **Define Schema:** Solidify the \`CompassContact\` structure. Complete the mock generator in \`useContactSync\`.
2. **Build the Spark:** Scaffold the main window frame for the Contacts Spark and implement the virtualized list components.
3. **Upgrade Settings:** Add the necessary checkboxes to the Online Accounts view.
4. **Wire It Up:** Connect the UI arrays to the synchronization engine.
5. **Phase Two:** Implement the actual standard OAuth libraries.
`,Fe=`# YouMeOS: The Asynchronous Cosmos

This document defines how YouMeOS visualizes a decentralized internet using an "Observer-Centric" Noosphere without the need for expensive, real-time multiplayer telemetry (P2P/WebRTC). It outlines the mathematical ruleset for how interaction history generates the 3D cosmos.

## The Problem
Rendering an intergalactic network of 100k+ users crossing between individual decentralized nodes typically requires a real-time multiplayer engine. If a user moves their spaceship, everyone else needs the X/Y/Z telemetry instantly. This crashes databases and makes true decentralization over WordPress impossible.

## The YouMeOS Solution: Asynchronous, Observer-Centric Map
Because YouMeOS is a spatial operating system meant for long-term data navigation, not twitch-gaming, it uses an entirely different paradigm. 

The Noosphere is **deeply subjective.** Every user is the absolute center of their own universe (the Main Star/Sun). The stars around them are placed algorithmically based strictly on *historical interaction data*, not real-time telemetry. If another user moves their ship locally on their own screen, it does not matter to you.

### 1. The Protocol: ActivityPub Polling
Instead of a P2P graph database, YouMeOS Native relies structurally on **ActivityPub**. ActivityPub acts like standard HTTP REST polling natively built for the W3C Open Web (powering software like Mastodon).
*   Every time you follow someone, read their "Sparks" (messages), or reply to them, an ActivityPub event is recorded.
*   Your Vue3 WebGL engine simply digests a stream of these historical events from your local database.

### 2. Algorithmic Astrophysics
With your ActivityPub event stream in hand, the Vue3 engine calculates the physical properties of foreign Stars in your Noosphere using the following rules:

*   **Mass & Gravity (Proximity):** Determined by *interaction frequency*. If you constantly exchange DMs/Sparks with User B, the mathematical weight of that relationship increases their Mass. The heavier their Mass relative to yours, the stronger the gravitational pull. Over weeks, User B's Star will physically orbit closer and closer to your center Sun until they are in your immediate inner solar system.
*   **Brightness (Intensity):** Determined by *recency and volume*. If User C just dumped 50 new Sparks (photos, blogs) into their Oort Cloud, their Brightness spikes. They may be far away (low interaction mass), but they shine brilliantly, catching your eye across the Noosphere. If you interact with them, their Mass increases, pulling them closer.
*   **Cosmological Drift (Decay):** Over time, if relationships cool down, the system applies a time-decay algorithm to the Mass. A formerly close Star will slowly drift outwardly back into the distant Oort Cloud. 

## Result: A Living Social Graph
By rendering the Cosmos asynchronously, YouMeOS eliminates the overhead of multiplayer servers. Every user gets a completely unique, personalized galaxy where their closest friends and most interacted nodes literally form the constellations orbiting their world. ActivityPub provides all necessary data through simple webhooks.
`,Ne=`# YouMeOS: The Sovereign Node Architecture

This document outlines the infrastructure choices that allow YouMeOS to scale infinitely while remaining decentralized, leveraging WordPress as the core operating layer alongside Progressive Web App (PWA) client technologies.

## The Problem
Running a 4D WebGL-based universe is resource-intensive. Centralizing physics parsing, real-time shaders, and vast data stores on a single server kills scalability and runs contrary to decentralization. 

## The YouMeOS Solution
Because over **50% of the internet** is powered by WordPress, it serves as the ultimate "Trojan Horse" to construct an "Intergalactic Federation of Sites." Users do not need to learn a new, esoteric decentralized blockchain system. They simply install the YouMeOS plugin into WordPress.

### 1. WordPress as the "Main Star Processor"
Every YouMeOS instance is driven by a WordPress backend. Whether hosted on a standard $5 shared host or spun up locally, WP serves as the fundamental data-lake and API endpoint.
*   **Data Silo:** It securely handles user content (Sparks), Media (the Oort Cloud), and processes incoming ActivityPub federation updates from other nodes.
*   **The Power of Ecosystem:** Building strictly on WordPress means users can use 60,000+ existing plugins (WooCommerce, memberships, caching) to customize their personal Main Star node natively, without proprietary lockdowns.

### 2. The PWA (Progressive Web App) Front-End
The visual "Operating System" (built in Vue3, Vuetify, and WebGL) executes entirely on the user's local machine via a headless architecture layered over the WP REST API.
*   **Service Workers:** Once users load the YouMeOS dashboard, service workers cache the heavy WebGL engine, the 3D textures, and the core routing mechanics. Future loads happen instantly off the hard drive. 
*   **Instant UI:** Navigating Windows, accessing sparks, and rotating the camera around the Noosphere happen with zero-lag because "rendering" is decoupled from "database polling." 
*   **Installable:** It looks, feels, and installs exactly like a native OS Application on the user's tablet, phone, or desktop.

### 3. The Ultimate Goal: The "1-Click" Desktop Node
For true decentralization, a user shouldn't even need to rent a server. The roadmap builds toward a fully wrapped Desktop Application.
*   Using frameworks like **Tauri** or **Electron** bundled closely with lightweight development environments (like WordPress Studio or WP-Env SQLite), non-technical users will be able to download an \`.exe\` or \`.dmg\` that runs an entirely self-contained WordPress instance on their laptop hard drive.
*   **Sovereign Local-First:** They log into their Desktop App. They write their Sparks and view the WebGL Noosphere entirely offline. When they connect to the internet, their local WP node pushes their ActivityPub packets out to the broader internet.

This strategy ensures total data sovereignty while maintaining modern, high-tier visual fidelity through decoupling the processing engine (PWA local rendering) from the data vault (WP PHP/MySQL backend).
`,Ve=`# YouMeOS: Sovereign Identity Architecture 

This document outlines the identity model for YouMeOS, detailing how users authenticate, maintain sovereignty, and traverse the Intergalactic Federation of Sites without relying on centralized Web 2.0 silos or gas-dependent Web 3.0 blockchains.

## The Problem
Traditionally, a user's identity is "owned" by the database of the platform they are visiting (e.g., a row in \`wp_users\`). If the platform goes down, or the user is banned, their identity and digital footprint vanish. 

## The YouMeOS Solution: Hybrid Sovereignty
YouMeOS utilizes a two-pronged approach, merging the simplicity of the open web (IndieAuth) with the cryptographic security of Web3 (DIDs). 

### 1. The Anchor: WordPress as the Main Star
In YouMeOS, **your WordPress URL is your identity**. This is achieved using **IndieAuth** (OAuth2 for the Open Web).
*   **The Passport URL:** When visiting a foreign YouMeOS node, you do not create an account. You enter your Main Star's URL (e.g., \`https://my-local-youmeos.com\`).
*   **The Handshake:** The foreign node temporarily redirects you to your Main Star, asking for cryptographic permission to log you in. Your Main Star validates the request and sends an authorization token back to the foreign node.
*   **Result:** You are authenticated on a foreign system without passwords, centralized databases, or third-party log-ins.

### 2. The Cryptographic Layer: Decentralized Identifiers (DIDs)
While URLs are excellent for human readability, raw cryptography is needed for immutable "Oort Cloud" asset ownership to ensure true data portability if your web host fails. 

*   **The DID Document:** Your Main Star (WordPress) automatically generates and publishes a \`did:web\` document at \`https://my-local-youmeos.com/.well-known/did.json\`. 
*   **Wallet Fallback:** Because this DID document contains public keys, a user can choose to authenticate using a standard Web3 wallet by signing a payload matching the public key registered in their DID document. **This requires absolutely zero gas or cryptocurrency**, it strictly uses the math behind the wallet.

## Portability of the Oort Cloud
When you log into a foreign node via IndieAuth or DID, you do not arrive empty-handed. Your authentication handshake passes pointers to your **Oort Cloud** (your avatar, bio, and public sparks). The foreign node renders your Star immediately using the data *you* host on your Main Star or via IPFS.

If you ever migrate from \`my-local-youmeos.com\` to a new domain, you simply update your DID registry, and your entire identity traverses with you, unbroken.
`,He=`# YouMeOS Engine Integration

The **YouMeOS Engine** is a high-performance cosmic visualization and portal system, originally built with Three.js (r62) and now masterfully integrated into the **Xophz-COMPASS** ecosystem. It manages the rendering of 100,000+ stars and serves as the primary navigation gateway for the "Youniverse" experience.

---

## 🏗️ Architecture

The integration follows a sophisticated hybrid model, bridging legacy procedural logic with modern reactive Vue 3 patterns.

### 1. Modular Engine Control

The engine is no longer monolithic. It is managed via:

- **\`youmeos-app.vue\`**: The main entry point within the YouMeOS sub-app, responsible for the high-level layout and UI overlay.
- **\`youmeos.controller.ts\`**: Handles the lifecycle and interaction logic, coordinating between the Vue UI and the Three.js canvas.
- **\`useLegacyLoader.ts\`**: A dedicated Vue Composable that orchestrates the dynamic loading of legacy assets and scripts.

### 2. Entry Points & Routing

Standalone application gateways are located in the root \`/apps\` directory (e.g., \`/apps/youmeos/index.html\`) to separate portal logic from the main Compass SPA, allowing for specialized performance optimizations.

### 3. Portal Page Configuration

YouMeOS can be configured to load on different pages via **Settings → YouMeOS** in wp-admin:

| Mode | Behavior |
| :--- | :--- |
| **Routes Only** (default) | Serves YouMeOS on \`/youmeos/\` and \`/os/\` only |
| **Homepage** | Replaces the site's front page with YouMeOS |
| **Specific Page** | Loads YouMeOS on a chosen WordPress page |

The \`/youmeos/\` and \`/os/\` routes remain available regardless of the selected mode. Settings are stored in \`youmeos_load_mode\` and \`youmeos_load_page_id\` WP options.

### 4. Legacy Asset Management

Core logic, shaders, and textures are relocated to the plugin's public directory: \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy\`.

---

## ✨ Key Integration Features

### 🚀 Visionary Boot Sequence

The application features a perfectly choreographed boot sequence defined in \`apps/youmeos/config/loader-messages.ts\`.

- **Message Timing**: 1-second intervals for a rapid, high-tech experience.
- **Visual Stability**: Messages are centered and themed (e.g., "Calibrating Universe...", "Welcome to YouMeOS").
- **Automatic Reveal**: The UI fades in gracefully only after the engine signals readiness (\`window.hideLoader\`).

### 🌉 Global Event Bridge

The \`useLegacyLoader\` establishes a safety bridge between contexts:

- \`window.start()\`: Triggers the legacy engine initialization.
- \`window.setLoadMessage()\`: Allows legacy scripts to report status to the modern Vue loader.
- \`window.hideLoader()\`: Signals that the starfield is fully rendered and the UI can be revealed.

### 🌌 Cosmic Tour Choreography

Finalized tour choreography in \`tour.js\` provides an immersive onboarding experience:

- **Quartic Easing**: Smooth, cinematic camera movements.
- **Synchronized Transitions**: Message fade-out/in is perfectly timed with camera arrival at star clusters.
- **Star Presentation**: Integrated callbacks for highlighting Milky Way and star heat signatures.

### 💎 Design System Alignment

The "SuperDomX" aesthetic has been unified with the "Compass" design system:

- **Shared Primitive Mapping**: Legacy UI classes map to modern **X-Atom** (Primitives) mixins for a consistent glassmorphic look.
- **Typography**: **Orbitron** is the primary font, ensuring a high-tech, futuristic feel across both legacy and modern UI layers.

---

## 📈 Evolution & Roadmap

To ensure the system remains scalable and maintainable as it grows, the following improvements are being tracked:

| Feature                      | Status | Description                                                        |
| :--------------------------- | :----: | :----------------------------------------------------------------- |
| **Manifest-Driven Loading**  |   ✅   | Scripts and styles are loaded dynamically via \`manifest.json\`.     |
| **Modular Composablization** |  [/]   | Audio and Interface complete; Star Gen and Physics pending bridge. |
| **Shared Primitive Mapping** |   ✅   | Consistent glassmorphism across legacy and modern contexts.        |
| **Global Bridge (SDK)**      |   ✅   | Robust communication between Three.js and Vue 3.                   |
| **Cosmic Tour Refinement**   |   ✅   | Finalized choreography, easing, and message centering.             |
| **Advanced Asset Pipeline**  |   ⏳   | Implementation of **Basis** or **KTX2** for 100k+ star textures.   |
| **Physics Engine Bridge**    |   ⏳   | Exposing legacy inertia and physics to Pinia state.                |

### Detailed Progression

- [x] **Manifest-Driven Loading**
      Instead of hardcoding script sequences in \`youmeos-app.vue\`, implement a \`manifest.json\` generated by the PHP plugin. This allows for dynamic module loading and easier updates to engine components without frontend recompilation.

- [/] **Gradual "Composablization"**
  Wrap legacy features (Audio, Star Generation, Physics) into modern **Vue Composables** (e.g., \`useYouMeOSAudio()\`). While Audio and Interface are complete, Star Generation and Physics still need to be bridged to Reactive Vue (Pinia) to enable unified controls across the entire platform.

- [x] **Shared Primitive Mapping**
      Unify the "SuperDomX" aesthetic with the "Compass" design system by mapping legacy UI classes to modern **X-Atom** (Primitives) mixins. This allows for a consistent glassmorphic look without invasive code changes to the legacy engine.

- [x] **Centralized Environment Configuration**
      Move hardcoded paths (e.g., \`pluginPath\`) and server settings (e.g., \`devServerPort\`) into Vite \`.env\` files or a central \`config/\` directory.

- [ ] **Advanced Asset Pipeline**
      Implement a modern asset pipeline for the legacy textures (100k+ stars). Use compressed formats like **Basis** or **KTX2** and Vite's image optimization tools to reduce initial load times and GPU memory footprint.

- [x] **Global Event Bridge (SDK)**
      Establish a \`window.CompassBridge\` (and helpers like \`window.start\`, \`window.hideLoader\`) to facilitate safe communication between the modern Vue context and the legacy Global context, preventing \`ReferenceError\` or \`TypeError\` during complex cross-context interactions.

- [ ] **Physics Engine Bridge**
      Expose legacy inertia and physics parameters to Pinia state, allowing for modern UI sliders to control the starfield movement speed and direction in real-time.

---

> [!TIP]
> To modify the engine's boot sequence messages, visit **\`apps/youmeos/config/loader-messages.ts\`**. To adjust the tour choreography, see **\`public/youmeos_legacy/js/tour.js\`**.
`,je=`# Component Architecture

## Naming Conventions

### X-Components (\`x-*\`)

- **Location**: \`src/components/primitives/\`
- **Purpose**: Base "Compass" primitives.
- **Nature**: Wrappers around Vuetify \`v-*\` components or raw HTML.
- **Usage**: Universal building blocks available to all apps.
- **Examples**: \`x-btn\`, \`x-card\`, \`x-window\`.

### U-Components (\`u-*\`)

- **Location**: \`apps/youmeos/components/\`
- **Purpose**: YouMeOS-specific constructs.
- **Nature**: OS-level UI elements, windows, and widgets.
- **Usage**: Only within the YouMeOS context.
- **Examples**: \`u-window\`, \`u-taskbar\`, \`u-launcher\`.

## Component Types

1. **Primitives**: Dumb, functional atoms (Buttons, Inputs).
2. **Constructs**: Logical compositions (Dialogs, Windows).
3. **Blueprints**: Page-level layouts (Desktops, Dashboards).
`,Ye='# Component Refactoring Guide (Vuetify 3)\n\nThis document outlines the standard patterns to use when refactoring components in Xophz-COMPASS.\n\n> **Important**: Before using raw Vuetify components (`v-*`), check the [Design System: Atoms/Primitives](./Design-System-Atoms.md) to see if a pre-styled primitive (`x-*`) is available.\n\n## Game Engine Naming Convention\n\nWe use a video game-inspired naming convention instead of standard Atomic Design.\n\n| Atomic Term  | Game Engine Term | Description                       | Examples                          |\n| :----------- | :--------------- | :-------------------------------- | :-------------------------------- |\n| **Atom**     | **Primitive**    | Basic building blocks.            | `x-btn`, `x-icon`, `x-input`      |\n| **Molecule** | **Prefab**       | Reusable, interactive components. | `search-box`, `user-card`         |\n| **Organism** | **Construct**    | Complex, functional UI sections.  | `nav-drawer`, `dashboard-grid`    |\n| **Template** | **Blueprint**    | Page layouts and structures.      | `auth-layout`, `dashboard-layout` |\n| **Page**     | **Level**        | Specific views with data.         | `home-level`, `login-level`       |\n| **Hook**     | **Mechanic**     | Reusable logic and behaviors.     | `useNavigationMechanic`           |\n\n## Grid System\n\n### Legacy (Avoid)\n\n```html\n<v-layout\n  row\n  wrap\n>\n  <v-flex\n    xs12\n    sm6\n    >...</v-flex\n  >\n</v-layout>\n```\n\n### Modern (Standard)\n\n```html\n<v-row>\n  <v-col\n    cols="12"\n    sm="6"\n    >...</v-col\n  >\n</v-row>\n```\n\n## Spacing and Utility Classes\n\n### Spacing\n\nUse Vuetify\'s spacing utilities.\n\n| Class Pattern  | Description                              |\n| :------------- | :--------------------------------------- |\n| `ma-*`, `pa-*` | Margin/Padding all sides                 |\n| `mt-*`, `pt-*` | Margin/Padding top                       |\n| `mb-*`, `pb-*` | Margin/Padding bottom                    |\n| `ml-*`, `pl-*` | Margin/Padding left                      |\n| `mr-*`, `pr-*` | Margin/Padding right                     |\n| `mx-*`, `px-*` | Margin/Padding horizontal (left + right) |\n| `my-*`, `py-*` | Margin/Padding vertical (top + bottom)   |\n\n### Flexbox\n\n| Class                   | Description                   |\n| :---------------------- | :---------------------------- |\n| `d-flex`                | Display flex                  |\n| `d-none`                | Display none                  |\n| `align-center`          | Align items center            |\n| `align-start`           | Align items start             |\n| `justify-center`        | Justify content center        |\n| `justify-space-between` | Justify content space-between |\n\n## Vuetify Component Updates\n\n### Buttons\n\n- **Preferred**: Use `<x-btn>` instead of `<v-btn>` to ensure consistent flare.\n- Use `size="small"` instead of `small`.\n- Use `variant="text"`, `variant="outlined"`, `variant="tonal"`.\n- Use `start` or `end` props on `v-icon` inside buttons.\n\n### Chips and Avatars\n\n- Use `size="small"`, `size="x-small"`.\n- Use `v-avatar` with `start` or `end` inside chips.\n\n### List Items\n\nUse slots for prepend/append content:\n\n```html\n<v-list-item\n  title="Title"\n  subtitle="Subtitle"\n>\n  <template v-slot:prepend>\n    <v-icon>...</v-icon>\n  </template>\n</v-list-item>\n```\n\n## Typography\n\nUse Vuetify typography classes:\n\n- `.text-caption` for small notes.\n- `.text-h6`, `.text-h5` for headings.\n- `.text-mono` for technical data or HUD elements (technical monospace).\n- `.font-weight-bold` for bold text.\n\n## TypeScript in Templates\n\n> [!IMPORTANT] > **Do not use TypeScript-specific syntax (like `as any` or `as string`) inside `<template>` blocks.**\n\nVue templates are compiled to standard JavaScript and do not support TypeScript casting at runtime. This will cause a `SyntaxError` in the browser.\n\n### Correct Pattern\n\nIf you need to access properties on an object that TypeScript thinks is `any` or `unknown`, do one of the following:\n\n1. **Proper Prop Typing**: Use `PropType<T>` for props in your controller to ensure the template has the correct types.\n2. **Helper Methods**: Create a small helper method in your controller to perform the cast and return the typed value.\n3. **Reactive State**: Use properly typed `ref` or `computed` properties to expose the data to the template.\n\n#### Example (Prop Typing):\n\n```typescript\nprops: {\n  items: {\n    type: Array as PropType<MyItem[]>,\n    required: true\n  }\n}\n```\n\n#### Example (Helper Method):\n\n```typescript\nmethods: {\n  getMember(obj: any) {\n    return obj.member;\n  }\n}\n```\n\nIn template: `{{ getMember(item) }}`\n',qe=`# Design System: X-Primitives

Xophz-COMPASS uses a set of **Primitive** components that serve as thin wrappers around vanilla Vuetify elements. These components allow us to maintain a consistent "flare" or aesthetic across the application while retaining the full API and flexibility of Vuetify.

> **Note:** We use a **Game Engine-inspired naming convention**. What is traditionally called "Atoms" in Atomic Design, we call **Primitives**. See the [Component Refactoring Guide](./Component-Refactoring-Guide.md) for the full naming hierarchy.

---

## Core Principles

1.  **Prefix**: All primitives use the \`x-\` prefix (e.g., \`<x-btn>\`).
2.  **Transparent API**: Every primitive uses \`v-bind="$attrs"\` and forwards all \`$slots\`, ensuring they are drop-in replacements for their \`v-\` counterparts.
3.  **Flare**: Custom styles are defined in local \`_x-*.scss\` files within each component's folder, utilizing project variables for glassmorphism, backdrop blurs, and hover transitions.
4.  **File Structure**: Each primitive resides in its own folder: \`src/components/primitives/x-<name>/\`
    - \`x-<name>.vue\` — The template file
    - \`x-<name>.controller.ts\` — The script/logic
    - \`_x-<name>.scss\` — The scoped styles

---

> [!TIP] > **Interactive Reference**: You can see all these components and more in action at the **[Enchiridion Component Catalog](/wp-admin/admin.php?page=xophz-compass#/enchiridion/components)** (Route: \`/enchiridion/components\`).

---

## Component Hierarchy

We use a video game-inspired naming convention. See the [Component Refactoring Guide](./Component-Refactoring-Guide.md) for details.

| Level | Term          | Directory                    | Description                               |
| :---: | :------------ | :--------------------------- | :---------------------------------------- |
|   1   | **Primitive** | \`src/components/primitives/\` | Basic building blocks (\`x-btn\`, \`x-card\`) |
|   2   | **Prefab**    | \`src/components/prefabs/\`    | Reusable, interactive components          |
|   3   | **Construct** | \`src/components/constructs/\` | Complex, functional UI sections           |
|   4   | **Blueprint** | \`src/components/blueprints/\` | Page layouts and structural wrappers      |
|   5   | **Level**     | \`src/routes/.../\`            | Specific views with data (routed pages)   |

---

## Available Primitives

All primitives are globally registered and can be used without importing.

### Core Layout

| Component               | Vuetify Base            | Purpose                                |
| :---------------------- | :---------------------- | :------------------------------------- |
| \`<x-main>\`              | \`<v-main>\`              | Main content area                      |
| \`<x-app-bar>\`           | \`<v-app-bar>\`           | Top application bar                    |
| \`<x-system-bar>\`        | \`<v-system-bar>\`        | OS-style system bar with glassmorphism |
| \`<x-footer>\`            | \`<v-footer>\`            | Application footer                     |
| \`<x-navigation-drawer>\` | \`<v-navigation-drawer>\` | Side navigation drawer                 |
| \`<x-bottom-sheet>\`      | \`<v-bottom-sheet>\`      | Bottom sheet overlay                   |

### Buttons & Controls

| Component        | Vuetify Base   | Purpose                             |
| :--------------- | :------------- | :---------------------------------- |
| \`<x-btn>\`        | \`<v-btn>\`      | Button with custom hover lift       |
| \`<x-switch>\`     | \`<v-switch>\`   | Toggle switch with micro-animations |
| \`<x-checkbox>\`   | \`<v-checkbox>\` | Checkbox with subtle glow effects   |
| \`<x-go-btn>\`     | —              | Custom navigation/action button     |
| \`<x-toggle-btn>\` | —              | Custom toggle button                |

### Display & Data

| Component             | Vuetify Base          | Purpose                                 |
| :-------------------- | :-------------------- | :-------------------------------------- |
| \`<x-card>\`            | \`<v-card>\`            | Glass card with glassmorphism aesthetic |
| \`<x-glass-card>\`      | —                     | Premium frosted glass card              |
| \`<x-table>\`           | \`<table>\`             | Styled table with uppercase headers     |
| \`<x-data-table>\`      | \`<v-data-table>\`      | Glassmorphic data table                 |
| \`<x-chip>\`            | \`<v-chip>\`            | Chip with hover scaling                 |
| \`<x-avatar>\`          | \`<v-avatar>\`          | Avatar with duotone support             |
| \`<x-divider>\`         | \`<v-divider>\`         | Divider with optional gradient flare    |
| \`<x-icon>\`            | \`<v-icon>\`            | Icon with themed color support          |
| \`<x-skeleton-loader>\` | \`<v-skeleton-loader>\` | Loading placeholder                     |
| \`<x-powerline>\`       | —                     | Segmented status bar using chevrons (\`>\`) |
| \`<x-iframe>\`          | \`<iframe>\`            | Transparent iframe with \`color-scheme\` fix|

### Forms & Inputs

| Component        | Vuetify Base     | Purpose                             |
| :--------------- | :--------------- | :---------------------------------- |
| \`<x-text-field>\`   | \`<v-text-field>\`   | Text input with consistent styling      |
| \`<x-select>\`       | \`<v-select>\`       | Dropdown select with custom borders     |
| \`<x-autocomplete>\` | \`<v-autocomplete>\` | Autocomplete with project-specific look |
| \`<x-slider>\`       | \`<v-slider>\`       | Slider with full height support         |

### Lists & Navigation

| Component            | Vuetify Base    | Purpose                      |
| :------------------- | :-------------- | :--------------------------- |
| \`<x-list>\`           | \`<v-list>\`      | Transparent list container   |
| \`<x-list-item>\`      | \`<v-list-item>\` | List item with hover states  |
| \`<x-nav-item.vue>\`   | —               | Navigation item for menus    |
| \`<x-nav-avatar.vue>\` | —               | Avatar-based navigation item |

### Overlays & Feedback

| Component      | Vuetify Base   | Purpose                                          |
| :------------- | :------------- | :----------------------------------------------- |
| \`<x-dialog>\`   | \`<v-dialog>\`   | Modal dialog with glass content class            |
| \`<x-window>\`   | —              | Draggable, resizable window with OS-style header |
| \`<x-menu>\`     | \`<v-menu>\`     | Dropdown menu with glass styling                 |
| \`<x-snackbar>\` | \`<v-snackbar>\` | Toast notification with \`rough-glass\`            |

### Plugin-Specific

| Component              | Purpose                                   |
| :--------------------- | :---------------------------------------- |
| \`<x-plugin-icon>\`      | Renders a WordPress plugin's icon         |
| \`<x-plugin-image>\`     | Renders a WordPress plugin's banner image |
| \`<x-pulsing-ring>\`     | Animated ring effect for visual feedback  |
| \`<x-background-smoke>\` | Animated smoke/fog background effect      |

---

## Usage Examples

### Buttons (\`x-btn\`)

Standard button with a custom hover lift and project-specific weight.

\`\`\`html
<x-btn
  color="primary"
  @click="doSomething"
>
  Save Action
</x-btn>
\`\`\`

### Cards (\`x-card\`)

Implements the core glassmorphism aesthetic.

\`\`\`html
<x-card class="pa-4">
  <v-card-title>Header</v-card-title>
  <v-card-text>Content goes here.</v-card-text>
</x-card>
\`\`\`

### Input Fields (\`x-text-field\`, \`x-select\`)

Standard inputs with consistent border-radius and focus rings.

\`\`\`html
<x-text-field
  label="Username"
  v-model="user"
/>
<x-select
  :items="options"
  label="Choose Category"
/>
\`\`\`

### Selection Controls (\`x-checkbox\`, \`x-switch\`)

Interactive controls with subtle micro-animations and glow effects.

\`\`\`html
<x-checkbox label="Draft Mode" />
<x-switch
  label="System Active"
  color="success"
/>
\`\`\`

### Chips & Avatars (\`x-chip\`, \`x-avatar\`)

Standard chips with project-specific hover scaling and duotone support.

\`\`\`html
<x-avatar size="48">
  <v-img :src="profilePic" />
</x-avatar>

<x-chip
  variant="tonal"
  color="info"
>
  Level 99
</x-chip>
\`\`\`

### Lists (\`x-list\`, \`x-list-item\`)

Clean list layouts that preserve transparency and add hover states to items.

\`\`\`html
<x-list>
  <x-list-item
    title="Profile"
    prepend-icon="fa-user"
  />
  <x-list-item
    title="Settings"
    prepend-icon="fa-cog"
  />
</x-list>
\`\`\`

### Menu & Dialog (\`x-menu\`, \`x-dialog\`)

Overlays that automatically apply the COMPASS glassmorphism content classes.

\`\`\`html
<x-menu>
  <template #activator="{ props }">
    <x-btn v-bind="props">Open Menu</x-btn>
  </template>
  <x-list>...</x-list>
</x-menu>

<x-dialog v-model="showModal">
  <x-card>...</x-card>
</x-dialog>
\`\`\`

### Tables (\`x-table\`)

Sleek, transparent tables with bold, uppercase headers and standardized row spacing.

\`\`\`html
<x-table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>001</td>
      <td>Active</td>
    </tr>
  </tbody>
</x-table>
\`\`\`

### Powerline Stats (\`x-powerline\`)

A segmented, chevron-styled stats bar designed to sit at the bottom of cards (\`zsh\` powerline aesthetic). It accepts an array of \`items\` containing \`label\`, \`value\`, \`icon\`, and \`color\`.

\`\`\`html
<x-powerline 
  :items="[
    { label: 'TEMP', value: '2198K', color: 'rgba(0,0,0,0.4)', icon: 'fas fa-fire' },
    { label: 'MASS (M☉)', value: '1.04', color: 'rgba(0,0,0,0.3)' }
  ]" 
  :flush="true" 
/>
\`\`\`

### Iframes (\`x-iframe\`)

A wrapper for standard \`iframe\` elements that ensures transparent backgrounds work across environments by explicitly resetting \`color-scheme: normal\`.

\`\`\`html
<x-iframe
  src="/wp-admin/admin.php?page=plugin-slug"
  name="compass-sub-app"
/>
\`\`\`

### Data Tables (\`x-data-table\`)

A wrapper for \`v-data-table\` that applies glassmorphism transparency and styling to headers and rows.

\`\`\`html
<x-data-table
  :headers="headers"
  :items="items"
/>
\`\`\`

### Dividers (\`x-divider\`)

Standard dividers with added support for gradient flare via the \`inset\` prop.

\`\`\`html
<x-divider class="my-4" /> <x-divider inset />
<!-- Shows a gradient flare divider -->
\`\`\`

---

## Global Registration

Primitives are auto-registered via the \`primitives.ts\` plugin in \`src/engine/primitives.ts\`. This plugin scans all \`.vue\` files within \`src/components/primitives/\` and registers them globally.

**You do NOT need to import primitives manually in your \`.vue\` files.**

Both PascalCase (\`<XBtn>\`) and kebab-case (\`<x-btn>\`) naming conventions are supported.

\`\`\`typescript
// src/engine/primitives.ts
const primitives = import.meta.glob("@primitives/**/*.vue", { eager: true });

export default {
  install(app: App) {
    Object.entries(primitives).forEach(([path, definition]) => {
      const filename = path
        .split("/")
        .pop()
        ?.replace(/\\.\\w+$/, "");
      if (filename) {
        const pascalName = upperFirst(camelCase(filename));
        app.component(pascalName, definition.default);
        if (filename !== pascalName) {
          app.component(filename, definition.default);
        }
      }
    });
  }
};
\`\`\`

---

## Creating New Primitives

When creating a new primitive:

1.  **Create the folder**: \`src/components/primitives/x-<name>/\`
2.  **Add the files**:
    - \`x-<name>.vue\` — Template with \`v-bind="$attrs"\` and slot forwarding
    - \`x-<name>.controller.ts\` — \`defineComponent({ name: 'X<Name>' })\`
    - \`_x-<name>.scss\` — Scoped styles using project variables
3.  **Ensure it's picked up** by the auto-registration in \`src/engine/primitives.ts\`.
4.  **Document it** in this file under the appropriate category

### Template Pattern

\`\`\`vue
<template>
  <v-<base-component> v-bind="$attrs" class="x-<name>">
    <template v-for="(_, slot) in $slots" #[slot]="scope">
      <slot :name="slot" v-bind="scope || {}" />
    </template>
  </v-<base-component>>
</template>

<script lang="ts" src="./x-<name>.controller.ts"><\/script>
<style lang="scss" src="./_x-<name>.scss"></style>
\`\`\`

---

## When to Use Native Vuetify (\`v-*\`)

Use raw Vuetify components only when:

- **Layout components**: \`<v-row>\`, \`<v-col>\`, \`<v-container>\`, \`<v-spacer>\`
- **Complex widgets**: \`<v-tabs>\`, \`<v-window>\`, \`<v-progress-linear>\`, \`<v-stepper>\` (when no \`x-\` wrapper exists)
- **Sub-components**: \`<v-card-title>\`, \`<v-card-text>\`, \`<v-list-item-title>\`, etc.

---

## Related Documentation

- [Component Refactoring Guide](./Component-Refactoring-Guide.md) — Migration patterns and game engine naming
- [Project Philosophy](./Project-Philosophy.md) — Design pillars and UX principles
- [Architecture & Migration](./Architecture-and-Migration.md) — Tech stack and project structure
`,Xe=`# Sub-App Layout & Bazaar Routing Investigation

## Summary

Investigated issues with the Bazaar sub-app layout not rendering correctly. The root cause was determined to be **route configuration and component hierarchy**.

## Key Findings

### 1. Route Structure Issue

The \`/bazaar\` route was configured with nested child routes but the parent \`bazaar.vue\` component wasn't properly set up to render child content via \`<router-view>\`.

**Before:**

\`\`\`
/bazaar → bazaar.vue (no nested router-view)
/bazaar/dashboard → child route (never rendered)
\`\`\`

### 2. x-sub-app-layout Component

Located at: \`src/components/templates/x-sub-app-layout/\`

This template component provides:

- Standard sub-app header/navigation structure
- Sidebar layout support
- Content area with proper scrolling
- Integration with app-level state

**Usage Pattern:**

\`\`\`vue
<template>
  <x-sub-app-layout>
    <template #sidebar>...</template>
    <template #content>...</template>
  </x-sub-app-layout>
</template>
\`\`\`

### 3. Controller Pattern

The codebase uses a controller pattern (\`*.controller.ts\`) to separate business logic from Vue components. Controllers handle:

- State management
- API calls
- Business logic

### 4. Billboard vs Dashboard Routes

- \`/bazaar\` → Main bazaar view (billboard/landing)
- \`/bazaar-dash\` → Dashboard variant (separate top-level route)
- \`/bazaar/dashboard\` → Nested dashboard (requires parent router-view)

## Recommendations

1. **For nested routes**: Ensure parent component includes \`<router-view>\` to render children
2. **For layouts**: Use \`x-sub-app-layout\` template for consistent sub-app structure
3. **For controllers**: Keep Vue components thin, delegating logic to controllers

## Files Examined

- \`src/routes/bazaar/bazaar.vue\`
- \`src/routes/bazaar/bazaar.controller.ts\`
- \`src/routes/bazaar/routes/bazaar-dash/bazaar-dash.vue\`
- \`src/components/blueprints/layouts/x-sub-app-layout/x-sub-app-layout.vue\`
- \`src/components/blueprints/layouts/x-sub-app-layout/x-sub-app-layout.controller.ts\`
- \`src/mount-app.ts\` (route and app initialization)
`,Qe=`# Project Roadmap

## 🚩 Milestone 1: The Alpha Release (Current Focus)

**Theme:** "Systems Online"
**Goal:** Establish a stable, "Wow"-inducing core for early adopters and demonstrate the power of the Compass Ecosystem.

### 🌟 Core Experience (The "OS" Feel)

- [ ] **Holographic Snap Previews**:
    -   *Objective*: Visual "ghost" overlays when dragging windows to screen edges (Aero Snap).
    -   *Status*: **Pending Implementation**.
- [ ] **Magic Cloak (System Intelligence)**:
    -   *Objective*: Contextual help system that gives the OS a "voice".
    -   *Status*: **In Progress**.

### 📦 Compass Ecosystem (Plugins)

We are prioritizing plugins based on completeness and "Hero" potential.

#### 💣 Bomb Bag (The Hero)
**Domain:** Email Marketing & Audience Relationship Management.
*Why?*: It is the most technically mature plugin and demonstrates high-value business logic.

- [ ] **UI Polish**: Verify layouts match the Sub-App Billboard/Drawer pattern.
- [ ] **Functionality**: Ensure Campaigns and Subscribers can be managed via API.

#### 🔮 Magic Cloak (The Support)
**Domain:** User Onboarding & Contextual Help.
*Why?*: It reinforces the "Agentic" nature of the OS.

- [ ] **Hint Fetching**: Connect frontend to the WordPress \`compass_cloak_hint\` CPPT.
- [ ] **Editor**: functional UI for creating new hints.

### 💤 Deprioritized / Future Milestones (Beta)

- **Post Digger (Content)**: Currently a shell. Will be revisited in Beta.
- **Bazaar (Commerce)**: Complex logic; deferred until Alpha foundation is solid.
- **Silver Arrow / Titans Mitt**: Utility plugins deferred.

---

## 🔮 Future Horizons

### Milestone 2: The Beta (Connectivity)
-   **Noosphere**: Public profiles and social graph.
-   **Bazaar**: Full commerce integration.

### Milestone 3: 1.0 Release (Sovereignty)
-   **Moving Castle**: Multisite management.
-   **Production Security Review**.
`,Ke=`# Project Migration Progress

## Completed Tasks

### Store Migration (Vuex -> Pinia)

- [x] **Post Digger Module**: Migrated to Pinia, updated controllers. (Now part of general content management).
- [x] **Bazaar Module**: Migrated to Pinia, updated controllers.
- [x] **Compass Module**: Migrated to Pinia, updated controllers.
- [x] **XP Module**: Migrated to Pinia, updated controllers.
- [x] Removed legacy \`src/app.store.js\`.

### Framework & Styles

- [x] Removed **Bootstrap** dependencies and SCSS imports.
- [x] Updated to **Vuetify 3** and resolved major migration hurdles.
- [x] Established **X-Primitives** (Atoms) system with global registration via \`src/engine/primitives.ts\`.
- [x] Created premium **Glassmorphism** utility system (\`.glass\`, \`.rough-glass\`).
- [x] Implemented **Barrel Export** pattern in \`src/engine/index.ts\`.
- [x] Integrated **ECharts** for high-performance data visualization.

### UI Refactoring (Vuetify 3 + X-Primitives)

- [x] **Index / Root Layout**: Refined header, breadcrumbs, and billboard integration.
- [x] **Compass Module**:
  - [x] \`compass.vue\`: Refined custom stepper layout with glass HUD aesthetic.
  - [x] \`compass-dashboard.vue\`: Refactored to Vuetify 3/X-Primitives.
  - [x] \`compass-explore.vue\`: Refactored to Vuetify 3/X-Primitives.
- [x] **XP Module**:
  - [x] \`xp-categories.vue\`: Modernized list items and grid.
  - [x] \`xp-leaderboard.vue\`: Modernized cards and dialogs.
- [x] **Bomb Bag Module**:
  - [x] Refactored routes and modernised UI with pink (#ED55A9) accent theme.

## In Progress / Next Steps

- [ ] **Mobile Responsive Pass**: Ensure all refactored layouts work well on mobile.
- [x] **Bootstrap Variable Cleanup**: Fully remove unneeded variables from \`_variables.scss\`.
- [ ] **Magic Cloak System**: Complete dynamic hint fetching from \`compass_cloak_hint\` CPPT.
- [ ] **Documentation overhaul**: Update all system docs to match current architecture.
`,$e=`# YouMeOS: The 4D Tesseract Visualization Plan

## Overview

The **Tesseract** is the 4D extension of the YouMeOS canvas. It transforms the 3D celestial navigation into a living history by treating **Time as the Y-Axis (Verticality)**. This allows users to visualize their digital footprint and interactions as spatial objects that can be navigated, scrubbed, and "pulled" into the present.

---

## 1. Core Concept: The Vertical Temporal Strand

Instead of a "Star" or "Spark" being a static point in 3D space, every object in the Tesseract is a **Temporal Strand**, a vertical filament of light extending downwards from the current "Universal Now" (the Base Plane).

### The Spatial Mapping (XYZ + T)
*   **X & Z (Celestial Position):** Represents the *identity* or *context* of the object (e.g., its coordinate in the Hipparcos galaxy or its orbital distance in the Helios solar system).
*   **Y (Temporal Offset):** Represents the *timestamp*. 
    *   \`Y = 0\`: The Present (The Base Plane).
    *   \`Y < 0\`: The Past (Deep History).
    *   \`Y > 0\`: Predicted or Scheduled Events (The Future).

---

## 2. Visualizing "The Pull" (Data Interaction)

The "4D" aspect comes alive through the interaction between the user's **Sun (Sol)** and the surrounding **Stars (Galaxies)**.

### Data Pulses (Sparks)
When a user "pulls" data from a star (e.g., viewing a Facebook post or a YouTube video), the interaction is visualized as a **Data Pulse**:
1.  **Origin:** A glowing spark ignites on the target Star's strand at a specific historical \`Y\` coordinate.
2.  **Traversal:** The spark travels along a relationship thread (the "Hipparcos Line") toward the user's central Sun.
3.  **Impact:** As the spark arrives at the Sun, it "ignites" or updates a local Spark (App/Planet), visualizing the transfer of information from the "Them" to the "You."

---

## 3. The Navigation Levels (Nexos vs. Helios)

The w⁴ Protocol manifests differently depending on the user's perspective:

### The Nexos (Hipparcos) Tesseract
*   **Visualization:** A dense forest of vertical strands representing the interaction history of all connected users/stars.
*   **Use Case:** Seeing "Cluster Clouds" where many strands are connected by horizontal relationship threads at the same \`Y\` level, indicating a shared global event or viral moment.

### The Helios (Solar System) Tesseract
*   **Visualization:** A focused view of the user's own "Oort Cloud" and planetary Sparks.
*   **Use Case:** Looking down through the "Pictures" or "Messages" planets to see a vertical stack of historical snapshots, allowing for "Time-Travel" through your own content.

---

## 4. User Interaction: The "Chronos" Scrubber

To navigate the 4D Tesseract, the **Compass Suite** UI provides a "Temporal Scrubber":
*   **Slicing the Tesseract:** Moving the scrubber slides a horizontal "Clipping Plane" up and down the Y-axis.
*   **State Reconstruction:** As the plane moves, the 3D objects on the canvas (Stars/Planets) update their size, color, and relations to match the historical data at that exact \`Y\` level.
*   **Ghosting:** Objects from the "Future" or "Deeper Past" appear as faint, translucent silhouettes, providing context for the current temporal slice.

---

## 5. Technical Requirements (Simple Start)

1.  **Temporal Buffer:** A centralized store to track \`(id, timestamp, data)\` snapshots.
2.  **Strand Shader:** A custom shader for \`THREE.Line\` or \`THREE.Points\` that renders vertical filaments with a "breathing" neon pulse.
3.  **Global Time Ref:** A reactive variable in the \`useChronos\` store that drives the Y-offset of all canvas objects.

---

## 6. Philosophy: The "Ancient-Future" Logbook

By visualizing time as a physical dimension, we move away from the "infinite scroll" of Web2 and toward a "celestial record." The w⁴ Protocol makes the intangible nature of data history something you can literally fly through, touch, and reorganize.
`,Je=`# Three.js Legacy Migration Guide

This document tracks breaking changes and migration steps for Three.js, from r56 to r183.
**Current Project Version:** \`0.182.0\` (as per \`package.json\`)
**Target Legacy Version:** \`r62\` (approximate legacy code base version)

Use this guide to upgrade legacy components.

## Detected Legacy Usage in Codebase

The following files utilize deprecated Three.js classes and need migration:

### \`THREE.Geometry\` (Removed in r125)

- \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/plane.js\`
- \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/util.js\`
- \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/solarsystem.js\`
- \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/infocallout.js\`
- \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/hipparcos.js\`
- \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/guides.js\`

### \`THREE.ParticleSystem\` / \`THREE.ParticleBasicMaterial\` (Removed in r62/r68)

- \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/solarsystem.js\`
- \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/galaxy.js\`
- \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/guides.js\`
- \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/hipparcos.js\`
- \`wp-content/plugins/xophz-compass-event-horizon/public/youmeos_legacy/js/dust.js\`
- \`wp-content/plugins/xophz-compass-event-horizon/public/js/engine/solarsystem.js\`

**Action Plan:**

1.  Replace \`THREE.Geometry\` with \`THREE.BufferGeometry\`.
2.  Replace \`THREE.ParticleSystem\` with \`THREE.Points\`.
3.  Replace \`THREE.ParticleBasicMaterial\` with \`THREE.PointsMaterial\`.

## Migration Log

### 182 → 183

- **Clock**: Deprecated. Use \`Timer\` instead.
- **WebGPURenderer**: Shadows improved. Remove/decrease shadow bias values used to mitigate acne.
- **RoomEnvironment**: Scene position updated. PMREM lighting will look different. See #32646.
- **Sky/SkyMesh**: Legacy gamma correction removed. Visuals will differ; no parameter restoration available. See #32677.
- **MeshPostProcessingMaterial**: Removed.

### 181 → 182

- **WebGLRenderer**: \`PCFSoftShadowMap\` deprecated. Use \`PCFShadowMap\` (now soft).
- **WebGPURenderer**: \`colorBufferType\` -> \`outputBufferType\`. \`getColorBufferType()\` -> \`getOutputBufferType()\`.
- **VOXLoader**: \`load()\` changed. \`onLoad()\` callback now returns a scene with parsed meshes. #32488.

### 180 → 181

- **PBR Materials**: Indirect specular light computation improved. Rough materials (> 0.5) conserve energy better and appear brighter.
- **PMREM**: Reflections improved.
- **WebGPURenderer**: Async methods (\`renderAsync\`, \`computerAsync\`, \`clearAsync\`, etc.) deprecated. Initialize via \`await renderer.init()\` or \`renderer.setAnimationLoop()\`.
- **WebGPURenderer**: \`waitForGPU()\` removed.
- **KTX2Loader**: \`detectSupportAsync()\` deprecated. Use \`detectSupport()\` after renderer init.
- **TSL**: \`PI2\` -> \`TWO_PI\`.
- **GTAONode**: Internal AO render target optimized to \`r\` channel. Blend formula: \`vec4( scenePassColor.rgb.mul( aoPass.r ), scenePassColor.a )\`.
- **PassNode**: \`setResolution()\` -> \`setResolutionScale()\`, \`getResolution()\` -> \`getResolutionScale()\`.
- **AfterImageNode**: \`damp\` property is now \`Node<float>\`.
- **WaterMesh**: \`resolution\` -> \`resolutionScale\`.
- **Docs**: New API docs based on JSDoc. No i18n support.

### 179 → 180

- **DepthOfFieldNode**: New implementation/API. See \`webgpu_postprocessing_dof\`.
- **RGBELoader**: Renamed to \`HDRLoader\`.
- **RGBMLoader**: Removed. Use \`EXRLoader\`, \`HDRLoader\`, \`HDRCubeTextureLoader\` meant for HDR or \`UltraHDRLoader\`.
- **PostProcessing Nodes**: \`resolution\` -> \`resolutionScale\` (scalar, not Vector2) for \`ReflectorNode\`, \`AnamorphicNode\`, \`GaussianBlurNode\`.
- **WebGLRenderer**:
  - \`USE_REVERSEDEPTHBUF\` -> \`USE_REVERSED_DEPTH_BUFFER\`.
  - \`USE_LOGDEPTHBUF\` -> \`USE_LOGARITHMIC_DEPTH_BUFFER\`.

### 178 → 179

- **Timer**: Moved to core (THREE namespace).
- **USDZLoader**: Deprecated. Use \`USDLoader\`.
- **TRAAPassNode**: Now \`TRAANode\`, requires new setup.
- **WebGLRenderer**: \`reverseDepthBuffer\` parameter -> \`reversedDepthBuffer\`.
- **TSL**: \`label()\` -> \`setName()\`.
- **GaussianBlurNode**: Improved blur. Custom sigma values must be doubled.

### 177 → 178

- **Blending**: \`MultiplyBlending\` and \`SubtractiveBlending\` require \`Material.premultipliedAlpha = true\`.

### 176 → 177

- **ColorManagement**:
  - \`fromWorkingColorSpace()\` -> \`workingToColorSpace()\`.
  - \`toWorkingColorSpace()\` -> \`colorSpaceToWorking()\`.
- **JSON Object Scene**: Version 4.6 -> 4.7.
- **GLTFExporter**: Pushes parent node indices first.
- **PeppersGhostEffect**: Removed.

### 175 → 176

- **CapsuleGeometry**: \`length\` -> \`height\`.
- **GLTFLoader**: WebP/AVIF support detection removed.
- **LottieLoader**: Deprecated. Use \`lottie-web\`.

### 174 → 175

- **AnimationClip**: \`parseAnimation()\` deprecated.
- **ParametricGeometries**: Renamed to \`ParametricFunctions\`. Inner classes removed.
- **LUTImageLoader**: Constructor changed (only loading manager). Flip handled by property.
- **SMAAPass/HalftonePass**: \`width\`/\`height\` removed from constructor.
- **Controls**: \`connect()\` requires DOM element.

### 173 → 174

- **Timer**: No longer uses Page Visibility API automatically. Call \`timer.connect( document )\` for old behavior.
- **RenderTarget**: \`clone()\` performs full structural clone (no texture sharing).

### 172 → 173

- **MeshGouraudMaterial**: Deprecated. Use \`MeshLambertMaterial\`.
- **InstancedPointsNodeMaterial**: Removed. Use \`PointsNodeMaterial\`.
- **TSL**:
  - \`varying()\` -> \`toVarying()\`.
  - \`vertexStage()\` -> \`toVertexStage()\`.

### 171 → 172

- **TextureNode**: \`uv()\` -> \`sample()\`.
- **TSL**:
  - \`rangeFog()\` -> \`fog( color, rangeFogFactor() )\`.
  - \`densityFog()\` -> \`fog( color, densityFogFactor() )\`.
  - \`materialAOMap\` -> \`materialAO\`.
  - \`shadowWorldPosition\` -> \`shadowPositionWorld\`.
- **DotScreenNode**: \`center\` property removed.
- **PostProcessingUtils**: Renamed to \`RendererUtils\`.

### 170 → 171

- **WebGPURenderer Imports**: Use \`three/webgpu\` and \`three/tsl\`.
- **TSL**: Renamed blending functions (\`burn\` -> \`blendBurn\`, etc.). \`storageObject()\` -> \`storage().setPBO( true )\`.

### 169 → 170

- **Material.type**: Now static, cannot be modified.
- **TSL**: Modules moved to addons.
- **Texture**: Mipmaps always generated if \`generateMipmaps\` is true.
- **GLTFExporter**: Non-PBR \`metallicFactor\` 0, \`roughnessFactor\` 1.
- **MMD Modules**: Deprecated.
- **WebGLRenderer**: \`copyTextureToTexture3D()\` deprecated -> \`copyTextureToTexture()\`.
- **WebXRManager**: Honors camera layers.
- **CinematicCamera**: Removed.
- **LDrawLoader**: Conditional line material injection restriction.

### 168 → 169

- **TransformControls**: Derived from \`Controls\`. Use \`scene.add( controls.getHelper() )\`.
- **Exporters**: \`EXRExporter\`, \`KTX2Exporter\`, \`LightProbeGenerator\` methods now async.
- **PackedPhongMaterial**: Removed.
- **SDFGeometryGenerator**: Removed.
- **TiltLoader**: Removed.
- **GPUStatsPanel**: Removed. Use \`stats-gl\`.
- **GeometryCompressionUtils**: Accepts geometries, not meshes.

### 167 → 168

- **TSL**: Chaining removed (e.g., \`fxaa( outputPass )\` instead of \`outputPass.fxaa()\`).
- **TSL**: \`viewportTopLeft\` -> \`viewportUV\`, \`viewportBottomLeft\` removed (use flipY), \`uniforms()\` -> \`uniformArray()\`.
- **DragControls**: \`activate/deactivate\` -> \`connect/disconnect\`. Getters/setters removed for properties.
- **PointerLockControls**: \`getObject()\` -> \`object\`.
- **LogLuvLoader**: Removed.

### 166 → 167

- **WebGPURenderer/TSL**: Imports changed.
- **HDRJPGLoader**: Removed. Use \`UltraHDRLoader\`.
- **LDrawLoader**: recursive pack script renamed to \`.mjs\`.

### 165 → 166

- **BatchedMesh**: Must call \`addInstance()\` after adding geometry.

### 164 → 165

- **WebGLRenderer**: \`copyTextureToTexture\` and \`copyFramebufferToTexture\` signatures changed (added region/level support).

### 163 → 164

- **LWOLoader**: Coordinate system changed (left to right-handed).
- **USDZLoader**: \`parse\` -> \`parseAsync\`.
- **Shaders**: \`lightmap_fragment\` removed.
- **WebGLNodeBuilder**: Removed.

### 162 → 163

- **WebGLRenderer**: WebGL 1 support dropped. Stencil \`false\` by default.
- **TextGeometry**: \`height\` -> \`depth\`.
- **Scene.environment**: Attenuate with \`environmentIntensity\`. Material \`envMapIntensity\` only affects material envMap.

### 161 → 162

- **WebGLMultipleRenderTargets**: Removed. Use \`count\` property.
- **Hand-Tracking**: Manual request required.
- **InteractiveGroup**: API changed (\`listenToXRControllerEvents\`, \`listenToPointerEvents\`).
- **HTMLImageElement**: Uses \`naturalWidth\`/\`naturalHeight\` for dimensions.

### 160 → 161

- **Build Files**: \`build/three.js\` removed. Use ES modules.
- **WebGLRenderer**: Precision qualifiers for samplers.
- **GroundProjectedSkybox**: Replaced with \`GroundedSkybox\`.
- **Equirectangular**: Auto-converts to cube map (higher memory).

### 159 → 160

- **HBAOPass**: Replaced with \`GTAOPass\`.
- **Triangle**: Updates to \`getBarycoord\` / \`getInterpolation\`.

### 158 → 159

- **BatchedMesh**: \`applyGeometry()\` -> \`addGeometry()\`.
- **BufferAttributes**: \`updateRange\` -> \`updateRanges\`.
- **SkinnedMesh**: No WebGL 1 support.

### 157 → 158

- **Quaternions**: Must be normalized.
- **Materials**: \`bumpScale\` invariant to UV scale.

### 156 → 157

- **LightProbe**: Ambient/Hemisphere probes removed.
- **GLSL**: \`GeometricContext\` removed.
- **AnimationUtils**: \`arraySlice()\` removed.

### 155 → 156

- **FilmPass**: \`scanlines\` params removed.
- **SAOPass**: \`useDepthTexture\`/\`useNormals\` removed.
- **SSAOPass**: Requires prior RenderPass.

### 154 → 155

- **WebGLRenderer**: \`useLegacyLights\` false by default (deprecated).
- **ToneMapping**: Inline only works for screen. Use \`OutputPass\` for post-processing.
- **OutputPass**: No constructor params.

### 153 → 154

- **Shaders**: \`encodings_fragment\` -> \`colorspace_fragment\`, \`output_fragment\` -> \`opaque_fragment\`.

### 152 → 153

- **WebGL 1**: Deprecated.
- **EffectComposer**: Default \`HalfFloatType\`.
- **AdaptiveToneMappingPass**: Removed.
- **CubeTextureLoader**: sRGB by default.

### 151 → 152

- **UVs**: \`uv\`, \`uv2\`, \`uv3\`, \`uv4\` -> \`uv\`, \`uv1\`, \`uv2\`, \`uv3\`.
- **ColorManagement**: \`outputEncoding\` -> \`outputColorSpace\`. \`texture.encoding\` -> \`texture.colorSpace\`.
- **SVGLoader**: \`renderOrder\` required.

### 150 → 151

- **Float16BufferAttribute**: Auto-conversion.
- **SkinnedMesh**: \`boneTransform()\` -> \`applyBoneTransform()\`.
- **InstancedMesh**: \`frustumCulled\` true by default.
- **MapControls**: Moved to own module.
- **Tween.js**: Import path changed.
- **Points**: Support texture coordinates.
- **ShaderMaterial**: \`forceSinglePass\` true by default.
- **aoMap/lightMap**: No longer use uv2.

### 149 → 150

- **Build**: \`three.js\` deprecated.
- **BasisTextureLoader**: Removed. Use \`KTX2Loader\`.
- **ColorManagement**: \`legacyMode\` -> \`enabled\`.
- **Lights**: \`physicallyCorrectLights\` -> \`useLegacyLights\`.

### 148 → 149

- **Euler/Object3D**: Default constants renamed (e.g. \`DefaultOrder\` -> \`DEFAULT_ORDER\`).
- **ViewHelper**: \`controls\` removed.
- **Three.TwoPassDoubleSide**: Removed.

### 147 → 148

- **Examples**: \`js\` directory removed (use \`jsm\`).
- **Defaults**: Geometry radial values increased.
- **GLTFLoader**: Node order preserved.

### 146 → 147

- **Lights**: Decay default 2 (physically correct).
- **PixelShader**: Removed -> \`RenderPixelatedPass\`.

### 145 → 146

- **BloomPass/BokehPass**: Constructor params removed.

### 144 → 145

- **Controls**: Fly/FirstPerson use Pointer Events.
- **BufferGeometry**: Alias deprecated (use specific geometries).

### 143 → 144

- **BufferGeometry**: \`merge()\` removed.
- **MeshLambertMaterial**: Per-fragment shading.
- **Scene**: \`autoUpdate\` -> \`Object3D.matrixWorldAutoUpdate\`.

### 142 → 143

- **AnimationUtils**: Named import.
- **PCDLoader**: White default color.

### 141 → 142

- **DataUtils**: Named import.

### 140 → 141

- **GLTFExporter**: \`embedImages\` removed.
- **Geometry**: Removed completely.
- **SelectionHelper**: constructor changed.

### 139 → 140

- **SkinnedMesh**: Floating point vertex textures required.

### 138 → 139

- **CubeUVRefractionMapping**: Removed.

### 137 → 138

- **WebGLMultisampleRenderTarget**: Removed.
- **Node Material**: Replaced with new implementation.
- **VRMLoader**: Removed -> \`three-vrm\`.

### 136 → 137

- **RGBFormat**: Removed -> \`RGBAFormat\`.
- **BasisTextureLoader**: Deprecated.
- **ASTC**: sRGB removed.

### 135 → 136

- **WebGL 1**: HDR requires extensions.
- **ETC1**: WebGL 1 only.
- **ArcballControls**: Event listener changes.

### 134 → 135

- **dat.gui**: Replaced with \`lil-gui\`.
- **Texture**: Properties immutable after use.

### 133 → 134

- **DeviceOrientationControls**: Removed.
- **OrbitControls**: No zooming while rotating.
- **FileLoader**: Uses \`fetch\`.

### 132 → 133

- **Raycaster**: Recursive true by default.
- **Parametric/TextGeometry/Font**: Moved to examples.

### 131 → 132

- **BufferGeometryUtils**: Import change.
- **MeshPhysicalMaterial**: \`sheen\` -> \`sheenTint\`.
- **ComputeFaceNormals**: Removed.

### 130 → 131

- **MorphTargets**: Removed from material.
- **envMap**: Converted to PMREM.

### 129 → 130

- **Controls**: No \`preventDefault\`. All use Pointer Events.

### 128 → 129

- **WebGLRenderer**: \`render()\` target/clear args removed.
- **Skinning**: Removed from materials.

### 127 → 128

- **Classes**: ES6 classes for core components.
- **NPM**: Bare specifier imports.
- **XLoader**: Removed.

### 126 → 127

- **Scene.background**: No \`WebGLCubeRenderTarget\`.
- **AssimpLoader**: Removed.

### 125 → 126

- **Types**: Moved to \`three-types\`.
- **Face3**: Removed (moved to deprecated).
- **CSS3DRenderer**: No IE11.

### 124 → 125

- **Geometry**: Removed from core.
- **Converters**: Removed.

### 123 → 124

- **ColladaLoader**: Animation clips location changed.

### 122 → 123

- **Matrix**: \`getInverse\` deprecated -> \`invert\`.
- **DragControls**: Pointer Events.

### 121 → 122

- **ExplodeModifier/Fire**: Removed.

### 120 → 121

- **PolyhedronGeometry**: Detail param changed.
- **Geometry Generators**: Split files.

### 119 → 120

- **Scene.dispose**: Removed.
- **ShaderMaterial**: \`glslVersion\` required for GLSL 3.0.
- **CannonPhysics**: Removed.
- **Controls**: Pointer Events.

### 118 → 119

- **Transparency**: Renamed to \`transmission\`.

### 117 → 118

- **SphericalReflectionMapping**: Removed.
- **WebGLRenderer**: WebGL 2 auto-created.
- **Water**: LinearEncoding required.

### 116 → 117

- **Texture.mipmaps**: Type \`any[]\`.
- **CubeCamera**: Constructor change.

### 115 → 116

- **TopMapping**: Default \`NoToneMapping\`.
- **TranslucentShader**: Renamed \`SubsurfaceScatteringShader\`.

### 114 → 115

- **Matrix**: \`throwOnDegenerate\` removed.
- **Pragmas**: \`unroll_loop\` deprecated.

### 113 → 114

- **Material.vertexColors**: Boolean.
- **Raycaster**: Honors invisible objects (use layers).
- **GLTFLoader**: Returns \`Group\`.

### 112 → 113

- **Math**: Renamed \`MathUtils\`.
- **WebGLDeferredRenderer/RaytracingRenderer**: Removed.
- **Default Material Color**: White.

### 111 → 112

- **PMREMGenerator**: New implementation. (Core).
- **Gamma**: Removed (\`gammaInput\`, \`gammaOutput\`). Use encoding.
- **WebVR**: Removed -> WebXR.

### 110 → 111

- **Material.needsUpdate**: Integer check.
- **Legacy Helpers**: Removed.

### 109 → 110

- **BufferAttribute**: \`dynamic\` deprecated -> \`usage\`.
- **Methods**: \`addAttribute\` -> \`setAttribute\`.

### 108 → 109

- **Loaders**: \`Babylon\`, \`PlayCanvas\`, \`AWD\`, \`SEA3D\` removed.

### 107 → 108

- **CTMLoader**: Removed.
- **MeshPhysicalMaterial**: \`clearCoat\` -> \`clearcoat\`.

### 106 → 107

- **Constants**: \`MipMap\` -> \`Mipmap\`.
- **Namespace**: \`WEBGL\`, \`WEBVR\` -> \`THREE\`.

### 105 → 106

- **Examples**: ES6 modules.
- **VRMLLoader**: New implementation.

### 104 → 105

- **Debug**: \`checkShaderErrors\` true by default.

### 103 → 104

- **WebGLRenderer**: No error checking default.

### 102 → 103

- **Editor**: PWA.
- **SVGLoader**: Returns data object.

### 101 → 102

- **WebGLRenderer**: \`renderTarget\` args removed from \`render()\`.
- **Viewport**: \`(x,y)\` is lower left.

### 100 → 101

- **FirstPersonControls**: \`lookAt()\`.

### 99 → 100

- **Octree**: Removed.

### 98 → 99

- **SSAO**: New implementation.
- **JSONLoader**: Removed from core.
- **SkinnedMesh**: No \`initBones()\`.

### 97 → 98

- **CanvasRenderer**: Removed.

### 96 → 97

- **BinaryLoader**: Removed.
- **PointerLockControls**: Refactored.

### 95 → 96

- **EquirectangularToCubeGenerator**: Constructor changed.

### 94 → 95

- **OrbitControls**: Mouse buttons renamed.

### 93 → 94

- **Loaders**: \`TDS\`, \`MD2\`, \`X\` produce BufferGeometry.

### 92 → 93

- **ExtrudeBufferGeometry**: \`amount\` -> \`depth\`.

### 91 → 92

- **ExtrudeBufferGeometry**: \`frames\`, \`getArrays\`, etc. removed.

### 90 → 91

- **Geometry**: \`center()\` returns \`this\`.
- **ShaderChunks**: Split lights/normal pars.

### 89 → 90

- **Lensflare**: Moved out of core.
- **JSON Exporters**: Removed.

### 88 → 89

- **ImageUtils**: Removed.

### 87 → 88

- **CombinedCamera**: Removed.
- **Curves**: Renamed methods.

### 86 → 87

- **GLTF2Loader**: Replaced \`GLTFLoader\`.

### 85 → 86

- **Animation**: Removed deprecated classes.

### 84 → 85

- **MultiMaterial**: Removed -> Array.
- **Matrix4**: Methods removed.
- **BoxHelper**: \`update()\` args removed.

### 83 → 84

- **Spline**: Removed -> \`CatmullRomCurve3\`.
- **Vector3**: \`applyProjection\` deprecated.

### 82 → 83

- **Loaders**: \`STL\`, \`PDB\`, \`Assimp\` produce BufferGeometry.
- **BoundingBoxHelper**: Removed.
- **XHRLoader**: Renamed \`FileLoader\`.

### 81 → 82

- **PLYLoader**: BufferGeometry.
- **TubeGeometry**: \`taper\` removed.

### 80 → 81

- **Box2/3/Line3**: \`center()\` -> \`getCenter()\`.

### 76 → 77

- **GridHelper**: Colors in constructor.

### 75 → 76

- **Audio**: \`load\` deprecated -> \`AudioLoader\`.
- **Uniforms**: No \`.type\`.

### 74 → 75

- **Vector3**: \`setFromMatrixColumn\` signature.

### 73 → 74

- **Shadows**: Prop names changed (\`shadowDarkness\` removed).
- **MeshPhongMaterial**: \`metal\` removed.

### 72 → 73

- **Geometry**: \`morphColors\` removed.

### 71 → 72

- **PointCloud**: Renamed \`Points\`.

### 70 → 71

- **Material**: \`ambient\` removed.

### 69 → 70

- **Object3D**: \`renderDepth\` removed.

### 68 → 69

- **ColladaLoader**: Returns Scene.

### 67 → 68

- **Object3D**: Transform properties immutable.

### 66 → 67

- **Face3**: No centroid.

### 65 → 66

- **CubeGeometry**: Renamed \`BoxGeometry\`.

### 64 → 65

- **WebGLRenderer**: \`physicallyBasedShading\` removed.

### 62 → 63

- **WebGLRenderer**: Opaque background default.

### 61 → 62

- **Particle**: Removed -> \`Sprite\`.

### 59 → 60

- **Face4**: Removed.
- **OrbitControls**: Zoom methods renamed.

### 58 → 59

- **Object3D**: \`rotation\` is Euler. \`useQuaternion\` removed.
- **Mesh**: \`setGeometry/setMaterial\` removed.

### 57 → 58

- **Matrix4**: \`translate/rotate\` removed.
- **Object3D**: \`getChildByName\` -> \`getObjectByName\`.

### 56 → 57

- **BufferGeometry**: Attribute naming changed.
`,Ze=`# YouMeOS Web3 Vision & Roadmap

## Overview

This document outlines the strategic vision for transitioning YouMeOS from a centralized web platform into a fully decentralized, community-owned Web3 operating system. It covers tokenomics, decentralized governance, and the technical architecture required to host the software itself on decentralized networks.

### The Real-World Mission: The w⁴ Protocol & Solving the Web2 Crisis
It is critical to understand that the immersive aesthetics and gamification of YouMeOS are simply vehicles to facilitate profound real-world change, not to distract from it. The foundation of this change is governed by the **w⁴ Protocol**: transforming the "World Wide Web" into a "Worldwide Webwork" by adding the critical fourth dimension: *the Work*.
*   **Ending Data Exploitation:** Corporations currently harvest and endlessly profit off user data without compensation. The NFT Data Protocol dismantles this by returning cryptographic ownership, and the resulting passive income, to the true creators of that data.
*   **Establishing a Digital Meritocracy:** By binding network earning power to a soulbound Reputation system, we break the "pay-to-win" dynamics of the flat web. We are engineering an ecosystem where value is generated based on community contribution rather than extracted from the bottom up.
*   **The BlackBOX (Sovereign Digital Reality):** At the infrastructural level, every user operates from a **BlackBOX**: a hardened, autonomous, sovereign server. This transforms the user from a passive consumer into an "owner-operator" of a high-performance node in a global spatial network.
*   **The w⁴ Protocol (The 4D System View):** The w⁴ Protocol is not solely a user interface; it is the entirety of the vast, interconnected system when viewed from above. By weaving time and cryptographic timestamps directly into the YouMeOS canvas, users can visualize and navigate their individual "youniverse" at any given historical moment, literally expanding the 3D spatial network into a living 4D construct.
*   **The Compass Suite (The Navigation Interface):** To traverse this massive Tesseract, the *Compass Suite* UI unpacks the extreme density of decentralized environments, storage, and temporal data routing into a seamless, ancient-future command center.

---

## 1. Tokenomics: Currency vs. Governance

A core decision in Web3 design is structuring the token economy. YouMeOS rejects the standard exclusionary, USD-pegged models in favor of a gamified, egalitarian "Digital Mineral" economy.

### The "Dirt" Economy (The Base Layer)
Instead of a standard utility token, the base currency is called **Dirt**.
*   **The Earth Cent Peg:** Dirt is pegged mathematically to a "Global Average Unit" (an aggregate of world fiat/commodities), untethering it from any single nation's economy.
*   **Micro-Transaction Velocity:** Designed to be worth around a penny ("dirt cheap"), it removes the psychological friction of spending.
*   **Synthesis Hierarchy:** Users don't just hold Dirt; they "synthesize" or refine it under pressure to create higher-tier assets (Quartz -> Rubies -> Emeralds).
*   **System Reserve:** The wealthiest top-tier tokens (Apex Minerals/Core Crystals) are not sold; they are held by the system and granted only through proof-of-contribution (hosting, building). This prevents oligarchic hoarding.

### Option A: The Single-Token Model (e.g., \`$YOS\`)
In this model, a single token is used for *both* buying assets within the OS (like app licenses, storage space, or digital property) and for voting on protocol changes.
*   **Pros:** A simpler mental model for users. Concentrates all network value into one asset.
*   **Cons:** If the token becomes highly valuable due to speculators buying it for governance power, it becomes too expensive for normal users to use for everyday OS transactions, creating a high barrier to entry.

### Option B: The Tri-Token Model (Recommended)
This approach separates the economy, governance, and reputation, building a robust and fair Web3 ecosystem.
1.  **The Utility/Gas Token (e.g., Energy/Credits):** An inflationary, highly liquid token used for daily OS operations. Users earn it by interacting with the platform, providing computing resources, or completing bounties. They spend it on transactions, apps, and decentralized storage.
2.  **The Governance Token (e.g., \`$YOS\`):** A fixed-supply token representing ownership and voting power in the YouMeOS ecosystem.
3.  **The Reputation Token (XP System):** A specialized, non-transferable (soulbound) token or on-chain XP point system that tracks user reputation. Users earn this token through positive community contributions, good behavior, and high-quality interactions. 
    *   **Sybil Attack Prevention:** Because these tokens are soulbound to a specific wallet, they cannot be bought, sold, or transferred. This prevents wealthy entities from simply "buying" a good reputation; they must actually earn it.
    *   **Proof of Good Citizenship:** The system uses a reward mechanism where building a strong reputation directly correlates to earning the platform's primary (Utility or Governance) tokens. This effectively pays users to be helpful and constructive, aligning financial incentives directly with the health of the YouMeOS community.

---

## 2. Data Ownership & The NFT Data Protocol

A fundamental pillar of YouMeOS is the absolute ownership of personal data. Instead of data being harvested and siloed by centralized corporations, YouMeOS envisions a system where user data is explicitly tokenized and controlled by its creator.

### Tokenizing Personal Information & The Privacy Architecture
Blockchains are public ledgers, meaning publishing raw user data directly to the chain would be a severe privacy violation. To solve this, the NFT acts as an **Access Key** rather than the data storage itself.
*   **The Encrypted Payload:** A user’s personal information, preferences, and behavioral data are aggregated, encrypted, and securely stored on decentralized networks (like IPFS or Arweave) or kept locally in secure sovereign storage.
*   **Data as Access NFTs:** The user mints a secure Non-Fungible Token (NFT) that serves as the cryptographic decryption and access key to their underlying payload.
*   **Absolute Ownership:** Because the NFT key is an asset in their Web3 wallet, the user has literal, cryptographic ownership. No corporation or AI can access or read the data unless they are explicitly mathematically granted permission by that specific NFT.

### Data Vaults & Tiered Packaging (The "Identity Broker" Spark)
This tokenized information is not a monolith; it is securely layered so users maintain granular control over exactly what they monetize. This entire process operates physically within the user's sovereign BlackBOX and is managed visually via the UI Tesseract (a dedicated YouMeOS Spark), acting as their personal data command center.
*   **Layer 1 (The Basic Lead):** Standard public or semi-public information typically given in website lead forms (Name, Email, Phone, basic demographics). This is the most frequently requested, baseline data package.
*   **Layer 2 (The Behavioral Graph):** Platform usage statistics, app preferences, content engagement, and contextual interactions within the YouMeOS ecosystem.
*   **Layer 3 (The Deep Persona):** Highly specific, deeply personal metrics, financial interactions, or AI-derived psychological profiles.
*   **Granular Packaging:** The Identity Broker Spark allows users to select specific layers or curate custom "data sets" to mint as targeted access NFTs. This ensures they only sell the exact tiers of information they are comfortable sharing, pricing them accordingly.

### The Resale and Royalty Mechanism
*   **Consensual Data Monetization:** If a company wants to use a person's data for marketing, research, or training AI, they must purchase a license or a copy of that data directly from the user.
*   **Smart Contract Royalties:** The true power of this system lies in secondary markets. If the company that initially bought the data resells it to a third party, the underlying smart contract ensures that a significant percentage of that resale value is automatically routed back to the original owner (the user) as a royalty kickback.
*   **Passive Income:** This creates a paradigm where users generate ongoing passive income from the very data they produce, correcting the exploitative nature of current data harvesting practices.

---

## 3. Decentralized Autonomous Organization (DAO)

By implementing a DAO, YouMeOS transitions the community from just being "users" to being "owner-operators."

### How Voting Works
Holders of the Governance Token participate in the **YouMeOS DAO**.
*   **Proposals:** Any user holding a minimum threshold of tokens can submit a YouMeOS Improvement Proposal (YIP).
*   **Voting:** Token holders vote on these proposals. The weight of their vote is proportional to their token holdings.
*   **Treasury:** A portion of all transaction fees on the platform (or app sales) goes into a community treasury smart contract. The DAO votes on how to allocate these funds.

### What Does the DAO Govern?
*   **Protocol Upgrades:** Voting on changes to core smart contracts.
*   **Treasury Allocation:** Funding developers to build new core apps, covering decentralized server costs, or marketing campaigns.
*   **Economy Tweaks:** Adjusting token inflation rates or platform fee structures.
*   **Universe Lore/Features:** In a gamified environment, voting on opening new sectors, global events, or default App Store curation.

---

## 4. Hosting Software "On the Blockchain"

Is it possible to put the software itself on the blockchain? Yes, but a hybrid approach is required.

Blockchains (like Ethereum, Solana, or L2s like Arbitrum) are essentially highly secure, slow, and expensive global state machines. They are perfect for verifying ownership, but entirely unsuited for storing large files like 3D GLTF models, textures, or large JavaScript application bundles.

### The Hybrid Web3 Architecture
To make YouMeOS censorship-resistant and decentralized, we utilize a combination of decentralized technologies:

#### A. The State Layer (On-Chain)
*   **Smart Contracts:** Deployed on an EVM-compatible chain or Solana. These hold the "truth" of the universe. They track who owns which tokens, who owns which digital items/apps (represented as NFTs), and the state of the DAO treasury.
*   **Identity:** Users authenticate using their Web3 wallets (e.g., MetaMask, Phantom) instead of traditional usernames and passwords.

#### B. The Storage Layer (Decentralized File Systems)
*   **IPFS (InterPlanetary File System) or Arweave:** This is where the actual YouMeOS software lives. The compiled Vue.js/Three.js code, 3D assets, audio, and images are uploaded to these decentralized storage networks.
*   **How it works:** Instead of pulling \`youmeos.js\` from a centralized AWS server, the user's browser pulls it from a peer-to-peer network using the file's unique cryptographic hash. Once uploaded, the frontend cannot be taken down centrally.

#### C. The Access Layer (Decentralized Naming)
*   **ENS (Ethereum Name Service):** Instead of relying on a centralized DNS registrar (like GoDaddy) for \`youmeos.com\`, an ENS domain like \`youmeos.eth\` points directly to the IPFS hash containing the frontend code. Browsers with Web3 capabilities resolve this domain, loading the OS entirely from the decentralized web.

### Fully On-Chain Exceptions
While the Hybrid architecture is the gold standard for visually rich applications, some lightweight logic *can* be fully on-chain. For instance, the core rule engine or critical random number generation (RNG) for OS events can be executed via smart contracts or Zero-Knowledge (ZK) proofs, while the visual rendering remains off-chain.

---

## 5. Phased Rollout Plan

Transitioning to this architecture should be gradual.

*   **Phase 1: Web2.5 (The Foundational Bedrock)**
    *   Establish the BlackBOX infrastructure using WordPress (which powers 45% of the flat web) as the foundational bedrock to ensure massive interoperability.
    *   Implement Web3 wallet login authentication ("Connect Wallet" as an alternative to email) to seamlessly transition Web2 users into the Web3 framework.
    *   Mint basic NFTs representing internal digital assets (e.g., Desktop Themes, Premium Apps) on a low-cost Layer-2 network like Polygon.

*   **Phase 2: Token Generation Event (TGE) & DAO Formation**
    *   Launch the Utility and Governance tokens.
    *   Airdrop tokens to early adopters, contributors, and active users to bootstrap the economy.
    *   Establish off-chain DAO voting (using tools like Snapshot) based on token holdings to guide early development.

*   **Phase 3: Decentralized Storage Integration**
    *   Package the compiled YouMeOS frontend and publish versions to IPFS.
    *   Link the dApp URL to the IPFS gateway, allowing users to load the OS via decentralized storage.

*   **Phase 4: Full Decentralization**
    *   Move governance entirely on-chain.
    *   Hand over control of the master smart contracts (and the treasury) to the DAO.
    *   The OS runs autonomously on peer-to-peer networks, maintained and upgraded by the community.
`,en=`# WebGPU Migration Plan

> **Status**: In Progress  
> **Started**: 2026-01-18  
> **Goal**: Dual-renderer architecture with user toggle

## Overview

YouMeOS uses a custom 3D engine built on Three.js for rendering the Youniverse. This document outlines the migration path from WebGL to WebGPU while maintaining backwards compatibility.

## Current State

| Component       | Version             | Status           |
| :-------------- | :------------------ | :--------------- |
| Three.js        | r182                | ✅ Latest        |
| Renderer        | WebGLRenderer       | ✅ Stable        |
| Shaders         | GLSL ES 3.00        | ✅ Modern syntax |
| Browser Support | All modern browsers | ✅ Universal     |

## Target Architecture

\`\`\`
┌─────────────────────────────────────────────────────────────┐
│                     YouMeOSEngine                           │
├─────────────────────────────────────────────────────────────┤
│                    RendererFactory                          │
│           ┌───────────────┬───────────────┐                 │
│           │   WebGL2      │    WebGPU     │                 │
│           │  (Stable)     │  (Experimental)│                │
│           └───────────────┴───────────────┘                 │
├─────────────────────────────────────────────────────────────┤
│           ShaderFactory (Material Abstraction)              │
│     ┌─────────────────────┬─────────────────────┐           │
│     │   GLSL Materials    │   TSL NodeMaterials │           │
│     │   (ShaderMaterial)  │   (NodeMaterial)    │           │
│     └─────────────────────┴─────────────────────┘           │
└─────────────────────────────────────────────────────────────┘
\`\`\`

## Blockers Identified (2026-01-18)

### 1. Custom GLSL Shaders

WebGPURenderer does not support raw \`ShaderMaterial\` with GLSL. These must be migrated to **TSL (Three.js Shading Language)**.

| Shader                  | Purpose                  | Priority |
| :---------------------- | :----------------------- | :------- |
| \`galacticstars.vsh/fsh\` | Star particles in galaxy | High     |
| \`datastars.vsh/fsh\`     | Data-driven star colors  | High     |
| \`grid.vsh/fsh\`          | Coordinate grid overlay  | Medium   |
| \`starsurface.vsh/fsh\`   | Sun surface animation    | Medium   |
| \`starhalo.vsh/fsh\`      | Sun glow effect          | Medium   |
| \`starflare.vsh/fsh\`     | Solar flare effect       | Medium   |
| \`corona.vsh/fsh\`        | Sun corona effect        | Medium   |
| \`galacticdust.vsh/fsh\`  | Nebula dust clouds       | Low      |
| \`cubemapcustom.vsh/fsh\` | Skybox rendering         | Low      |

### 2. Legacy Three.js Addons

| Addon           | Issue                       | Solution                          |
| :-------------- | :-------------------------- | :-------------------------------- |
| \`Lensflare\`     | Uses \`getCurrentViewport()\` | Disable in WebGPU mode or rewrite |
| \`CSS2DRenderer\` | Works with both             | No changes needed                 |

## Implementation Phases

### Phase 1: Renderer Abstraction (Current)

Create a renderer factory that can create either WebGL or WebGPU renderers:

\`\`\`typescript
// apps/youmeos/engine/core/renderer.factory.ts
export type RendererType = "webgl" | "webgpu";

export async function createRenderer(
  type: RendererType,
  options: RendererOptions
): Promise<THREE.WebGLRenderer | WebGPURenderer>;
\`\`\`

### Phase 2: Material Abstraction

Create a material factory that returns appropriate materials based on renderer:

\`\`\`typescript
// apps/youmeos/engine/core/material.factory.ts
export function createStarMaterial(
  rendererType: RendererType,
  options: StarMaterialOptions
): THREE.Material;
\`\`\`

### Phase 3: Shader Migration to TSL

Migrate each shader pair from GLSL to TSL:

\`\`\`typescript
// OLD: GLSL String
const vertexShader = \`
  uniform float zoomSize;
  in vec3 customColor;
  void main() { ... }
\`;

// NEW: TSL (JavaScript)
import { uniform, attribute, varying, vec3 } from "three/tsl";
const zoomSize = uniform("float", 1.0);
const customColor = attribute("vec3");
// ...node-based logic
\`\`\`

### Phase 4: User Toggle

Add a toggle in the UI settings:

\`\`\`typescript
// Stored in localStorage
const rendererPreference = localStorage.getItem("youmeos_renderer") || "webgl";

// User can switch; requires reload
function setRendererPreference(type: "webgl" | "webgpu") {
  localStorage.setItem("youmeos_renderer", type);
  location.reload(); // Renderer initialized at boot
}
\`\`\`

## File Structure

\`\`\`
apps/youmeos/engine/
├── core/
│   ├── youmeos.engine.ts      # Main engine (uses factory)
│   ├── renderer.factory.ts    # NEW: Creates WebGL or WebGPU renderer
│   └── material.factory.ts    # NEW: Creates GLSL or TSL materials
├── webgl/                     # NEW: WebGL-specific implementations
│   └── materials/             # GLSL ShaderMaterial wrappers
├── webgpu/                    # NEW: WebGPU-specific implementations
│   └── materials/             # TSL NodeMaterial implementations
└── 4d-youniverse/
    └── effects/
        └── shaders/           # Existing GLSL files (unchanged)
\`\`\`

## Testing Strategy

1. **WebGL Mode**: Default, battle-tested, no regressions
2. **WebGPU Mode**: Opt-in via toggle, expect visual differences during migration
3. **Feature Flags**: Disable incompatible features (Lensflare) in WebGPU mode

## Browser Support

| Browser       | WebGL2 | WebGPU       |
| :------------ | :----- | :----------- |
| Chrome 113+   | ✅     | ✅           |
| Edge 113+     | ✅     | ✅           |
| Firefox 131+  | ✅     | ✅ (Nightly) |
| Safari 18+    | ✅     | ✅           |
| Mobile Chrome | ✅     | ⚠️ (Limited) |

## References

- [Three.js WebGPU Guide](https://threejs.org/docs/#manual/en/introduction/How-to-use-WebGPU)
- [TSL Documentation](https://github.com/mrdoob/three.js/wiki/Three.js-Shading-Language)
- [WebGPU Spec](https://www.w3.org/TR/webgpu/)
`,nn=`# WebGPU Shader Migration Plan

This plan outlines the step-by-step process for converting custom GLSL shaders to Three.js Shading Language (TSL) and integrating them into the YouMeOS engine.

## Phase 1: Foundation (Current Status)

- [x] Create \`renderer.factory.ts\` for dual-renderer support.
- [x] Update \`YouMeOSEngine\` to use renderer factory.
- [x] Add UI Toggle for renderer preference.
- [x] Update \`EngineContext\` type definition.
- [ ] **Fix**: Initialize \`rendererType\` in \`YouMeOSEngine\` constructor (fixes lint error).
- [ ] Create \`material.factory.ts\` as the central abstraction layer.

## Phase 2: Material Factory Integration

We will refactor existing generators to use \`create[Name]Material()\` from the factory instead of \`new THREE.ShaderMaterial()\`.

- **Goal**: Isolate shader instantiation so the generator code doesn't care about WebGL vs WebGPU.

### Target Files:

1.  \`apps/youmeos/engine/4d-youniverse/celestial/galaxy.ts\` -> \`createGalacticStarMaterial\`
2.  \`apps/youmeos/engine/4d-youniverse/celestial/hipparcos.ts\` -> \`createDataStarMaterial\`
3.  \`apps/youmeos/engine/4d-youniverse/celestial/sun.ts\` -> \`createSunMaterial\` (Surface, Halo, Corona, Flare)
4.  \`apps/youmeos/engine/4d-youniverse/effects/skybox.ts\` -> \`createSkyboxMaterial\`
5.  \`apps/youmeos/engine/4d-youniverse/effects/dust.ts\` -> \`createDustMaterial\`

## Phase 3: Shader Migration (TSL Implementation)

We will migrate shaders one by one. For each shader:

1.  Analyze GLSL (\`.vsh\`, \`.fsh\`).
2.  Create TSL counterpart in \`apps/youmeos/engine/webgpu/materials/\`.
3.  Register in \`material.factory.ts\`.
4.  Verify in WebGPU mode.

### Priority 1: The Galaxy (Core Visuals)

- [ ] **Galactic Stars**
  - _Status_: TSL Drafted (\`materials/galacticstars.ts\`).
  - _Action_: Integrate into factory and test.
- [ ] **Galactic Dust**
  - _Complexity_: Low. Texture sampling + noise.

### Priority 2: Unverified Stars (Hipparcos)

- [ ] **Data Stars** (\`datastars\`)
  - _Complexity_: Medium. Vertex attributes for size/color.

### Priority 3: The Sun (Complex Effects)

- [ ] **Star Surface** (\`starsurface\`)
  - _Complexity_: High. Time-based noise animation.
- [ ] **Star Halo** (\`starhalo\`)
  - _Complexity_: Medium. Glow gradients.
- [ ] **Star Flare** (\`starflare\`) & **Corona** (\`corona\`)
  - _Complexity_: Medium.

### Priority 4: Environment

- [ ] **Skybox** (\`cubemapcustom\`)
  - _Complexity_: Low. Cubemap sampling.
- [ ] **Grid** (\`grid\`)
  - _Complexity_: Low.

## Phase 4: Feature Parity & Cleanup

- [ ] **Lensflare Replacement**:
  - Identify WebGPU native lens flare solution or implement custom TSL billboard.
- [ ] **WebGPU Renderer Activation**:
  - Once critical shaders are ported, update \`renderer.factory.ts\` to instantiate the **actual** \`WebGPURenderer\` class instead of the WebGL fallback.
`,tn=`# Window Management & Workspace Orchestration Roadmap

This document outlines the evolutionary path for the YouMeOS windowing environment. Our goal is to forge a desktop experience that balances the familiarity of traditional operating systems with the fluid, celestial nature of the YOUniverse.

## 🌌 Core Philosophy: Sovereign Agency

Windows in YouMeOS are not just containers; they are **Resonant Viewports** into specific sparks of functionality. The user (Sovereign Sun) should have absolute control over their spatial organization.

---

## 🛤️ Evolution Horizons

### Phase I: Stability & Persistence (Current Focus)

Ensuring the environment is reliable and remembers the user's intent.

- [x] **Z-Index Stacking**: Selected windows always rise to the peak of the Heat Signature.
- [x] **Double-Click Maximization**: Standard OS interaction patterns on the window bar.
- [x] **Chrono-Persistence (Geometry Storage)**: Save window positions, sizes, and open/closed states to persistent storage.
- [x] **Focus Reclamation**: Automatically focus the most recently used window when the active one is closed.
- [x] **"Show Desktop" Pulse**: A system-level trigger to clear all overlays and reveal the 3D visualization.
- [x] **Global Glass Styling**: Unify window aesthetics with the system-wide \`42,42,42\` glass morphism variables.

### Phase II: Spatial Fluidity

Improving the speed and ease of window organization.

- [x] **Aero-Snap / Magnetic Anchoring**: Drag windows to edges/corners to snap into 50% or 25% layouts.
- [ ] **Holographic Snap Previews**: Show a spectral outline of the snap target before the user releases the window.
- [ ] **Intelligent Tiling**: Single-click command to organize all open windows into a non-overlapping grid.
- [ ] **Active Sparks Overlay**: A high-fidelity overlay for rapid switching between active sparks.
  - **Ctrl+Shift** (hold): Opens the Active Sparks overlay
  - **←/→** while held: Cycles through open windows in stack order
  - Release to focus selected spark
  - _Note: Avoids OS-reserved shortcuts like Alt+Tab_

### Phase III: Deep Context & Multi-Dimensionality

Advanced features for power-users and complex workflows.

- [ ] **Right-Click Resonance (Context Menus)**: Granular window controls (Pin to Top, Move to Workspace, Collapse to Rail).
- [ ] **Multi-Workspaces (Celestial Realms)**: Paginated desktop environments that users can swipe between.
- [ ] **Live Peek / Hover Previews**: Real-time thumbnails appearing when hovering over the User Rail icons.
- [ ] **Cross-Portal Drag & Drop**: Moving content between windows and sparking interactions between different applications.

---

## 🛠️ Technical Strategy

- **State Management**: Utilize the \`window.store.ts\` (Pinia) as the Single Source of Truth for all geometry and visibility.
- **Persistence Layer**: Implement a debounce-save logic to update WordPress User Meta with window states, preventing excessive API calls.
- **Interaction Layer**: Enhance the \`u-window\` wrapper with edge-detection logic for the Snap system.
- **Animation Philosophy**: All transitions must use the project's signature \`cubic-bezier\` durations defined in \`design-system.scss\` to maintain a premium, cinematic feel.

---

## ✅ Progress Log

| Feature                        | Status    | Date Manifested |
| :----------------------------- | :-------- | :-------------- |
| Z-Index Logic                  | Completed | 2026-01-29      |
| Double-Click Maximization      | Completed | 2026-01-29      |
| Chrono-Persistence             | Completed | 2026-01-29      |
| Aero-Snap / Magnetic Anchoring | Completed | 2026-01-29      |
| Snap Logic                     | Completed | 2026-01-29      |
| Rail Toggle Minimization       | Completed | 2026-01-29      |
| Global Glass Styling           | Completed | 2026-01-30      |
`,an=`# YouMeOS Decentralization & Local-First Architecture Roadmap

To realize the vision of an "Intergalactic Federation of Sites" where YouMeOS runs locally, remains decentralized, and operates across many internet nodes, we must evolve the current architecture (WordPress + Vue 3/WebGL) into a federated, local-first mesh network.

## Strategic Pillars

### 1. The Bedrock Strategy: WP Node + PWA Client
Since over 50% of the web runs on WordPress, it serves as the ultimate "Trojan Horse" to decentralize the internet. 

*   **WordPress as the Main Star Processor:** The core server software for a node remains WordPress. Anyone with a standard shared host can install the YouMeOS plugin and instantly become an active star in the Noosphere. 
*   **The Desktop "Local Node":** For true localized running, we will package a "1-click YouMeOS Desktop App" (using technologies like Tauri or Electron bundled with a lightweight PHP/SQLite runtime like WordPress-Studio). This allows a user to run their own sovereign WordPress node locally on their laptop, completely offline.
*   **The PWA (Progressive Web App):** The Vue 3/WebGL frontend will be served as an installable PWA. The user interfaces with the lightning-fast PWA, which syncs seamlessly back to their local (or hosted) WordPress node running in the background.

### 2. Sovereign Identity (IndieAuth + Web3 DIDs)
To move across different deployments and nodes, a user's identity cannot be tied exclusively to a single node's \`wp_users\` table. Rather than choosing between Web3 cryptography and traditional Web2 URLs, we will tie them together.

*   **The Best of Both Worlds:** We adopt **IndieAuth** (OAuth2 for the Open Web) so your WordPress URL *is* your primary identity. However, we tie your WordPress User profile to a **DID (Decentralized Identifier)**. 
*   **How it Works:** Your WordPress site (Main Star) publishes a DID document. This means a user can authenticate to external Noospheres uniquely in two ways:
    1.  Entering their URL (e.g., \`my-local-youmeos.com\`) and verifying via IndieAuth (No crypto needed).
    2.  Signing a cryptographic payload with a Web3 Wallet that matches the public key registered in their Main Star's DID document.
*   **Portable "Oort Clouds":** User content and settings are tied to this dual-layered identity, allowing them to carry their digital footprint safely across the "Noosphere" to different YouMeOS instances.

### 3. Asynchronous Federation (The Galactic Math)
We need a protocol for Stars (WordPress nodes) to communicate and form the Noosphere. Based on the fundamental insight that the Noosphere is a deeply *personal, localized* map, we do not require instant P2P real-time telemetry (like Gun.js or WebRTC multiplayer).

*   **ActivityPub (The Single Source of Truth):** We will use ActivityPub (the W3C standard powering Mastodon) natively inside WordPress HTTP REST. When you interact with another node (send a Spark, reply, follow), it registers as an ActivityPub event.
*   **The "Observer-Centric" Cosmos:** Because every user sees the Youniverse with themselves as the center Sun, there is no "objective" map. If User A interacts heavily with User B, User B's ActivityPub payload gets sent to User A's node. 
*   **Calculating Celestial Physics from Data:** The Vue3 WebGL engine locally calculates the "gravity," "brightness," and "mass" of other Stars based strictly on the asynchronous interaction history:
    *   **Mass & Gravity:** Determined by interaction frequency (how many DMs, replies, or Sparks are shared). High interaction pulls the Star closer into your immediate orbit.
    *   **Brightness:** Determined by the recency and volume of data payloads received.
    *   **Drift:** Because relationships change over days/weeks, the starry constellations drift slowly. Pure ActivityPub event polling gives us everything we need to compute these beautiful, unique cosmological maps without the overhead of real-time multiplayer networking.

### 4. Distributed Storage (The Collective Space)
Building a heavy media-driven 3D cosmos on localized nodes will bottleneck bandwidth if scaled traditionally.

*   **IPFS (InterPlanetary File System):** For hosting rich media, 3D glTF assets, and textures for the WebGL engine. Assets are addressed by their hash instead of a central URL, meaning dense planetary textures can be loaded from the nearest peer instead of the source node.

## Implementation Roadmap

### Phase 1: Local-First Optimization & IndieAuth Core
*   Implement full PWA caching and Service Workers for the core YouMeOS Vue app.
*   Install/Configure IndieAuth within the Compass plugin so a user's WordPress installation becomes their universal passport.
*   Introduce the DID public registry on the WordPress user profile.

### Phase 2: Inter-Node Federation (ActivityPub)
*   Extend the \`xophz-compass\` plugin to support basic ActivityPub endpoints.
*   Map Compass "Sparks" (Mercury messages, Saturn blogs, etc.) into ActivityPub object types so external nodes can broadcast their activity streams.

### Phase 3: The Vue3 Observer-Centric WebGL Engine
*   Update the YouMeOS WebGL \`tour.js\` and Star Generators to consume ActivityPub interaction metadata.
*   Implement algorithms mapping interaction frequency to physical Star Properties (Mass, Gravity, Orbit Proximity, and Brightness) to generate deeply subjective, personalized cosmos renderings.

### Phase 4: Desktop Node Wrapper 
*   Once the PWA and federation protocols are hardened, wrap the entire ecosystem (WordPress + PHP + Vue3) into a single downloadable desktop application (using Tauri or similar) to allow non-technical users to run a 1-click sovereign Main Star node perfectly locally.
`,on=`# Getting Started with YouMeOS

## Welcome to YouMeOS
YouMeOS is your centralized, browser-based operating environment. Designed to feel like a native desktop operating system, it provides a seamless workspace where you can launch tools, manage your profile, and multitask effortlessly. 

## Interface Overview

### The Desktop
The main visual area of YouMeOS is the Desktop. This is your primary workspace where all windowed applications (known as **Sparks**) will open, float, and operate. 

### The System Bar
Located at the bottom of the screen, the System Bar is your navigational anchor:
- **App Launcher**: Typically found on the far left, clicking this will bring up a grid of all available Sparks on your system.
- **Active Processes**: As you open Sparks, they will appear in the center of the System Bar, allowing you to quickly switch between them, minimize them, or bring them to the forefront.
- **System Tray**: On the far right, you'll find system status indicators such as the current time, system settings, or environment details.

### Navigation Rails
- **Left Rail**: When expanded, provides global navigation elements across the broader COMPASS ecosystem.
- **Right Rail**: Reserved for context-specific communications, notifications, or quick actions depending on your active tasks.

## Sparks: Your Applications
In YouMeOS, applications are called **Sparks**. They behave exactly like native windows: you can drag them around the desktop, minimize them to the System Bar, maximize them to take up the full viewport, and close them when finished.

Some essential Sparks to get you started include:
- **Welcome-U**: Your onboarding screen that provides a friendly introduction to the environment. 
- **Enchiridion**: The official documentation system. Use this to search and drill down into guides and technical references (like this very document!).
- **HoloShell Terminal**: Your command-line interface access for direct system interaction.
- **Scratch Paper**: A sleek notepad for jotting down thoughts quickly. Your notes persist automatically between sessions.
- **Helios**: Your profile and identity control center, where you can configure your "I Am" statements and manage your display name.
- **System Monitor**: An interactive visualizer to see the current load and activity of your OS environment.

## Window Management
Multitasking in YouMeOS is intuitive and dynamic:
- **Focus**: Clicking on any part of a Spark's window will bring it to the front and make it the active window.
- **Drag**: Click and hold the header of any Spark to move it anywhere on the desktop.
- **Resize**: Grab the edges or corners of a supported window to adjust its dimensions to your liking. 
- **Animations**: Watch out for smooth transitions when minimizing (sending a window down to the System Bar) and maximizing.

## Getting Help
If you are ever unsure how a specific subsystem operates, launch the **Enchiridion** spark from the App Launcher. From there, you can browse an alphabetically organized, scrollable sidebar that houses all available platform documentation.
`,rn=`# The Swiss Army Knife for Life

## What Good Does This Do Me?

When exploring YouMeOS and the COMPASS platform, the number one question people ask is simple: *"What good does this actually do me?"*

The short answer: **It is the Swiss Army Knife for life that makes you a true sovereign person with the rest of the world.**

We often use the word "Sovereign" to describe our overarching vision, and while it might sound like a heavy, philosophical term, its practical meaning for your daily life is incredibly grounding and empowering.

## What Does "Sovereignty" Actually Mean for You?

In the context of YouMeOS, personal sovereignty isn't about isolation; it's about absolute, irrefutable ownership of your digital existence. It means you are the undisputed owner of your data, your identity, and your creative output. 

Instead of renting space on platforms that monetize your attention and data, you operate from a foundation that you control. You are a sovereign entity interfacing with the world on your own terms.

## The Swiss Army Knife Utility

Just as a physical Swiss Army Knife equips you with the exact right tool for a multitude of unexpected real-world situations, YouMeOS equips you for the digital world. 

Here is how it acts as your ultimate multi-tool:

### 1. Unified Command Center
Everything you need—from managing your decentralized identity (Helios) to jotting down spontaneous ideas (Scratch Paper) and interacting with global economic protocols (Nexos)—happens in one cohesive, desktop-like environment. You don't have to juggle fragmented apps; your tools are unified.

### 2. Absolute Portability
Your workspace, your assets, your documents, and your identity aren't locked to a specific device. Because YouMeOS is a browser-based operating environment backed by robust, decentralized principles, your digital life travels with you. 

### 3. Economic Independence
Through integrated systems like The Crucible and the W4 Protocol, you aren't just a consumer; you are an active participant in a decentralized economy. You can transmute your time, effort, and engagement into tangible, system-recognized value without relying on traditional, predatory financial gateways.

### 4. Uncompromised Privacy
True sovereignty requires true privacy. By centralizing your tools within an environment built on self-ownership, you sever the reliance on third-party data harvesters. Your actions, notes, and metrics belong to you.

## The Bottom Line

When you boot into YouMeOS, you aren't just logging into a website. You are stepping into your own digital territory. You are equipping yourself with a versatile, ever-expanding Swiss Army Knife designed to help you navigate, build, and thrive as a sovereign individual in an interconnected universe.
`,sn=["innerHTML"],ln=H({__name:"x-markdown",props:{content:{}},setup(W){const w=W,p=I(()=>w.content?Q.parse(w.content):"");return(T,s)=>(y(),x("div",{class:"x-markdown-container",innerHTML:p.value},null,8,sn))}}),dn=j(ln,[["__scopeId","data-v-c9651a1d"]]),cn=80,k=150,V=200,un=H({__name:"enchiridion-canvas",setup(W){const w=A(null);let p=0,T=null;function s(o,i){const m=Math.random()>.85;return{x:Math.random()*o,y:Math.random()*i,vx:(Math.random()-.5)*.6,vy:(Math.random()-.5)*.6,radius:m?3+Math.random()*3:1+Math.random()*1.5,baseRadius:m?3.5:1.5,hue:m?45:200,pulsePhase:Math.random()*Math.PI*2,pulseSpeed:.02+Math.random()*.03}}return K(()=>{const o=w.value;if(!o)return;const i=o.getContext("2d");if(!i)return;let m=-1e3,u=-1e3,f=0,S=0;const r=[],P=()=>{const e=o.parentElement;if(e){if(o.width=e.clientWidth,o.height=e.clientHeight,f<20&&o.width>50||S<20&&o.height>50)for(const n of r)n.x=Math.random()*o.width,n.y=Math.random()*o.height;f=o.width,S=o.height}};P();for(let e=0;e<cn;e++)r.push(s(o.width,o.height));const E=e=>{const n=o.getBoundingClientRect();m=e.clientX-n.left,u=e.clientY-n.top},D=()=>{m=-1e3,u=-1e3};o.addEventListener("mousemove",E),o.addEventListener("mouseleave",D);const M=()=>{if(!(!i||!o)){i.clearRect(0,0,o.width,o.height);for(const e of r){e.x+=e.vx,e.y+=e.vy,e.pulsePhase+=e.pulseSpeed,e.radius=e.baseRadius+Math.sin(e.pulsePhase)*(e.baseRadius*.4),e.x<0&&(e.x=o.width),e.x>o.width&&(e.x=0),e.y<0&&(e.y=o.height),e.y>o.height&&(e.y=0);const n=e.x-m,t=e.y-u,a=Math.sqrt(n*n+t*t);if(a<V&&a>.1){const v=(V-a)/V;e.vx+=n/a*v*.03,e.vy+=t/a*v*.03}const l=Math.sqrt(e.vx*e.vx+e.vy*e.vy);l>1.2?(e.vx*=.95,e.vy*=.95):l<.2&&(e.vx*=1.05,e.vy*=1.05)}for(let e=0;e<r.length;e++){for(let a=e+1;a<r.length;a++){const l=r[e].x-r[a].x,v=r[e].y-r[a].y;if(Math.abs(l)>k||Math.abs(v)>k)continue;const d=Math.sqrt(l*l+v*v);if(d<k){const g=1-d/k;i.beginPath(),i.moveTo(r[e].x,r[e].y),i.lineTo(r[a].x,r[a].y),i.strokeStyle=`rgba(98, 201, 255, ${g*.25})`,i.lineWidth=1,i.stroke()}}const n=r[e].x-m,t=r[e].y-u;if(Math.abs(n)<k*1.5&&Math.abs(t)<k*1.5){const a=Math.sqrt(n*n+t*t);if(a<k*1.5){const l=1-a/(k*1.5);i.beginPath(),i.moveTo(r[e].x,r[e].y),i.lineTo(m,u),i.strokeStyle=`rgba(217, 190, 111, ${l*.4})`,i.lineWidth=1.5,i.stroke()}}}for(const e of r){if(e.radius<=0)continue;i.beginPath(),i.arc(e.x,e.y,e.radius,0,Math.PI*2),i.fillStyle=`hsla(${e.hue}, 80%, 70%, 0.8)`,i.fill();const n=e.radius*3.5;i.beginPath(),i.arc(e.x,e.y,n,0,Math.PI*2);const t=i.createRadialGradient(e.x,e.y,e.radius,e.x,e.y,n);t.addColorStop(0,`hsla(${e.hue}, 80%, 60%, 0.4)`),t.addColorStop(1,`hsla(${e.hue}, 80%, 60%, 0)`),i.fillStyle=t,i.fill()}p=requestAnimationFrame(M)}},O=new ResizeObserver(P);o.parentElement&&O.observe(o.parentElement),p=requestAnimationFrame(M),T=()=>{cancelAnimationFrame(p),O.disconnect(),o.removeEventListener("mousemove",E),o.removeEventListener("mouseleave",D)}}),$(()=>{T?.()}),(o,i)=>(y(),x("canvas",{ref_key:"canvasRef",ref:w,class:"enchiridion-canvas"},null,512))}}),hn=j(un,[["__scopeId","data-v-37aa8a33"]]),pn={class:"w-100 enchiridion-nav-ui",style:{height:"100%",display:"flex","flex-direction":"column"}},mn={class:"d-flex justify-center align-center opacity-70 mr-4",style:{width:"24px"}},gn={class:"px-3 pt-2 pb-2 text-caption text-uppercase font-weight-bold header-label"},yn={class:"h-100 w-100",style:{position:"relative"}},fn={class:"reading-pane mx-auto w-100"},vn="../../../../../docs/",bn=H({__name:"enchiridion-app",setup(W){const w=Object.assign({"../../../../../docs/CHANGELOG.md":te,"../../../../../docs/Cloudflare-Email-Routing.md":ae,"../../../../../docs/Hello-World.md":oe,"../../../../../docs/The-W4-Protocol-and-Fractal-OS.md":ie,"../../../../../docs/YouMeOS-Federated-Identity.md":re,"../../../../../docs/analytics_google-analytics-integration.md":se,"../../../../../docs/authenticity_and_licensing.md":le,"../../../../../docs/business/BOX_TIER_ARCHITECTURE.md":de,"../../../../../docs/business/business_dirt_digital_mineral_economy.md":ce,"../../../../../docs/business/business_sephiroth_godhead_progression.md":ue,"../../../../../docs/business/business_w4_protocol_economy.md":he,"../../../../../docs/business/business_youmeos_decentralized_pwa_model.md":pe,"../../../../../docs/compass/blueprints/Core-Taxonomy.md":me,"../../../../../docs/compass/blueprints/System-Overview.md":ge,"../../../../../docs/compass/ecosystem/Patreon-and-Bazaar.md":ye,"../../../../../docs/compass/ecosystem/Portals-and-Ecosystem.md":fe,"../../../../../docs/compass/ecosystem/software_manager.md":ve,"../../../../../docs/compass/plugins/Bomb-Bag-System.md":be,"../../../../../docs/compass/plugins/Bug-Net-System.md":we,"../../../../../docs/compass/plugins/Lemon-Aid-System.md":Se,"../../../../../docs/compass/plugins/Magic-Cloak-System.md":Te,"../../../../../docs/compass/plugins/Magic-Mirror-System.md":Pe,"../../../../../docs/compass/plugins/Mirror-Shield-System.md":ke,"../../../../../docs/compass/plugins/Moving-Castle-System.md":Ce,"../../../../../docs/compass/plugins/Questbook-CRM-System.md":xe,"../../../../../docs/compass/plugins/Treasure-Trove-and-Map-System.md":Ae,"../../../../../docs/compass/plugins/magic-formula-system.md":Me,"../../../../../docs/github-plugin-updater.md":_e,"../../../../../docs/moving-castle_magic-door.md":Re,"../../../../../docs/reference/Ecosystem-Architecture.md":Ie,"../../../../../docs/reference/Project-Philosophy.md":Ee,"../../../../../docs/reference/versioning.md":De,"../../../../../docs/spark_wizards-tower_feature-flags.md":Oe,"../../../../../docs/sparks_omegafile.md":Le,"../../../../../docs/youmeos/blueprints/Helios-Modules.md":We,"../../../../../docs/youmeos/blueprints/Legacy-Engine-Mechanics.md":ze,"../../../../../docs/youmeos/blueprints/OS-Architecture.md":Be,"../../../../../docs/youmeos/blueprints/Spark-Federation-Security.md":Ue,"../../../../../docs/youmeos/blueprints/Unified-Contacts-Blueprint.md":Ge,"../../../../../docs/youmeos/core-engine/Asynchronous-Cosmos.md":Fe,"../../../../../docs/youmeos/core-engine/PWA-Desktop-Node.md":Ne,"../../../../../docs/youmeos/core-engine/Sovereign-Identity.md":Ve,"../../../../../docs/youmeos/core-engine/YouMeOS-Engine.md":He,"../../../../../docs/youmeos/design-system/Component-Architecture.md":je,"../../../../../docs/youmeos/design-system/Component-Refactoring-Guide.md":Ye,"../../../../../docs/youmeos/design-system/Design-System-Atoms.md":qe,"../../../../../docs/youmeos/design-system/Sub-App-Layout.md":Xe,"../../../../../docs/youmeos/roadmaps/General-Roadmap.md":Qe,"../../../../../docs/youmeos/roadmaps/Migration-Progress.md":Ke,"../../../../../docs/youmeos/roadmaps/Tesseract-4D-Visualization.md":$e,"../../../../../docs/youmeos/roadmaps/ThreeJS-Legacy-Migration.md":Je,"../../../../../docs/youmeos/roadmaps/Web3-Vision-Roadmap.md":Ze,"../../../../../docs/youmeos/roadmaps/WebGPU-Migration.md":en,"../../../../../docs/youmeos/roadmaps/WebGPU-Shader-Migration.md":nn,"../../../../../docs/youmeos/roadmaps/Window-Management.md":tn,"../../../../../docs/youmeos/roadmaps/YouMeOS-Decentralization-Roadmap.md":an,"../../../../../docs/youmeos/user-guides/youmeos-user-guides-getting-started.md":on,"../../../../../docs/youmeos/user-guides/youmeos_user_guides_swiss_army_knife.md":rn}),p=I(()=>Object.keys(w).map(n=>{const t=n.replace(vn,""),a=t.split("/"),l=a.pop()||"",v=l.replace(".md","").split("-").map(b=>b.charAt(0).toUpperCase()+b.slice(1)).join(" ");let d="03. Platform Docs",g="General";l.toLowerCase()==="hello-world.md"?(d="00. Welcome",g="Start Here"):a.length>=2&&["compass","youmeos"].includes(a[0].toLowerCase())?(d=a[0].toLowerCase()==="youmeos"?"01. YouMeOS":"02. COMPASS",g=a[1]):a.length>0&&(d="03. Platform Docs",g=a[0]);const Y=g.replace(/[-_]/g," ").split(" ").map(b=>b.charAt(0).toUpperCase()+b.slice(1).toLowerCase()).join(" ");return{path:n,relativePath:t,title:v,category:Y,root:d}}).sort((n,t)=>n.root!==t.root?n.root.localeCompare(t.root):n.category!==t.category?n.category.localeCompare(t.category):n.title.localeCompare(t.title))),T=I(()=>{const n={};p.value.forEach(a=>{n[a.root]||(n[a.root]={}),n[a.root][a.category]||(n[a.root][a.category]=[]),n[a.root][a.category].push(a)});const t={};return Object.keys(n).sort().forEach(a=>{t[a]={},Object.keys(n[a]).sort().forEach(l=>{t[a][l]=n[a][l]})}),t}),s=A(null),o=A(null),i=I(()=>!s.value||!w[s.value]?`# Welcome to the Source

Please select a document from the rail to begin your reading.`:w[s.value]),m=n=>{s.value=n};J(s,()=>{o.value&&(o.value.scrollTop=0)},{flush:"post"});const u=A(null),f=A(null),S=A(0),r=typeof sessionStorage<"u"?sessionStorage.getItem("youmeos:enchiridion:open"):null;let P=null;r&&(P=p.value.find(n=>n.path.toLowerCase().includes(r.toLowerCase())),typeof sessionStorage<"u"&&sessionStorage.removeItem("youmeos:enchiridion:open")),!s.value&&P&&(s.value=P.path,u.value=P.root,f.value=P.category,S.value=1),typeof window<"u"&&window.addEventListener("youmeos:enchiridion:open",n=>{const t=n.detail;if(t){const a=p.value.find(l=>l.path.toLowerCase().includes(t.toLowerCase()));a&&(s.value=a.path,u.value=a.root,f.value=a.category,S.value=1)}});const E=(n,t)=>{u.value=t,f.value=n,S.value=1},D=()=>{S.value=0},M=I(()=>{if(!s.value)return null;const n=p.value.findIndex(t=>t.path===s.value);return n>=0&&n<p.value.length-1?p.value[n+1]:null}),O=()=>{const n=M.value;n&&(m(n.path),(f.value!==n.category||u.value!==n.root)&&(f.value=n.category,u.value=n.root))},e=n=>{const t=n.toLowerCase();return t.includes("start here")?"fal fa-stars":t.includes("blueprints")?"fal fa-drafting-compass":t.includes("user guides")?"fal fa-map-signs":t.includes("core engine")?"fal fa-microchip":t.includes("design system")?"fal fa-swatchbook":t.includes("roadmaps")?"fal fa-road":t.includes("ecosystem")?"fal fa-planet-ringed":t.includes("plugins")?"fal fa-plug":t.includes("reference")?"fal fa-books":t.includes("general")?"fal fa-book-spells":"fal fa-folder"};return(n,t)=>{const a=z("x-spark-splash-action"),l=z("t-spark-splash"),v=z("u-window");return y(),_(v,{id:"enchiridion",title:"Enchiridion",icon:"fal fa-book-spells","default-width":1e3,"default-height":700,"default-nav-pinned":!0},{"nav-content":h(()=>[C("div",pn,[c(ee,{modelValue:S.value,"onUpdate:modelValue":t[1]||(t[1]=d=>S.value=d),transition:"window-fade-transition","reverse-transition":"window-fade-transition",style:{height:"100%",width:"100%"}},{default:h(()=>[c(X,{value:0,style:{height:"100%",width:"100%","overflow-y":"auto","overflow-x":"hidden"},class:"custom-scrollbar"},{default:h(()=>[c(N,{nav:"",density:"compact",class:"pb-8","bg-color":"transparent"},{default:h(()=>[c(R,{title:"The Source",class:B(["mb-1 font-weight-bold category-list-item mt-2",s.value?"opacity-80":"text-primary"]),onClick:t[0]||(t[0]=d=>s.value=null)},{prepend:h(()=>[C("div",mn,[c(ne,{icon:"fal fa-book-spells",class:B(s.value?"":"text-primary")},null,8,["class"])])]),_:1},8,["class"]),(y(!0),x(L,null,U(T.value,(d,g)=>(y(),x(L,{key:g},[C("div",{class:B(["px-3 pt-3 pb-2 text-caption text-uppercase font-weight-bold header-label",g!==Object.keys(T.value)[0]?"mt-4":""])},G(g),3),(y(!0),x(L,null,U(d,(Y,b)=>(y(),_(R,{key:b,title:String(b),"prepend-icon":e(String(b)),"append-icon":"fal fa-chevron-right",color:"#62c9ff",class:"mb-1 opacity-80",onClick:wn=>E(String(b),String(g))},null,8,["title","prepend-icon","onClick"]))),128))],64))),128))]),_:1})]),_:1}),c(X,{value:1,style:{height:"100%",width:"100%",display:"flex","flex-direction":"column"}},{default:h(()=>[c(N,{nav:"",density:"compact",class:"flex-grow-1 custom-scrollbar","bg-color":"transparent",style:{"overflow-y":"auto","overflow-x":"hidden"}},{default:h(()=>[c(R,{"prepend-icon":"fal fa-arrow-left",title:"Back",class:"mb-2 back-btn",onClick:D}),C("div",gn,[q(G(u.value)+" ",1),t[2]||(t[2]=C("span",{class:"opacity-50 mx-1"},"/",-1)),q(" "+G(f.value),1)]),(y(!0),x(L,null,U(u.value&&f.value&&T.value[u.value]?T.value[u.value][f.value]:[],d=>(y(),_(R,{key:d.path,title:d.title,"prepend-icon":"fal fa-file-alt",active:s.value===d.path,class:"mb-1",color:"#62c9ff",onClick:g=>m(d.path)},null,8,["title","active","onClick"]))),128))]),_:1}),M.value?(y(),_(N,{key:0,nav:"",density:"compact",class:"mt-auto px-2","bg-color":"transparent"},{default:h(()=>[c(R,{"prepend-icon":"fal fa-arrow-right",title:"Next",class:"opacity-80",onClick:O})]),_:1})):Z("",!0)]),_:1})]),_:1},8,["modelValue"])])]),default:h(()=>[C("div",yn,[s.value?(y(),x("div",{key:1,ref_key:"contentScrollEl",ref:o,class:"enchiridion-content fill-height custom-scrollbar"},[C("div",fn,[c(dn,{content:i.value},null,8,["content"])])],512)):(y(),_(l,{key:0,icon:"fal fa-book-spells",title:"Enchiridion",subtitle:"The Ultimate Handbook to the","subtitle-highlight":"Youniverse"},{background:h(()=>[c(hn)]),actions:h(()=>[c(F,{cols:"12",sm:"4"},{default:h(()=>[c(a,{title:"Explore",description:"All the knowledge of the Youniverse",icon:"fal fa-compass","icon-color":"#62c9ff"})]),_:1}),c(F,{cols:"12",sm:"4"},{default:h(()=>[c(a,{title:"Learn",description:"Master the tools of the Youniverse",icon:"fal fa-book-open","icon-color":"#D9BE6F"})]),_:1}),c(F,{cols:"12",sm:"4"},{default:h(()=>[c(a,{title:"Dream",description:"Shape the future of your Youniverse",icon:"fal fa-sparkles","icon-color":"#22c55e"})]),_:1})]),_:1}))])]),_:1})}}}),An=j(bn,[["__scopeId","data-v-f646e99c"]]);export{An as default};
