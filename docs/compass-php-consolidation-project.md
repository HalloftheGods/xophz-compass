# Project: Compass PHP Consolidation and Refactoring

## Architecture
- **Core Engine**: `wp-content/plugins/xophz-compass/`
  * Core Helper Suite: `wp-content/plugins/xophz-compass/includes/core/` (Autoloader, Plugin Base, Hookable Trait, Dev Proxy, REST Controller, Security, Sanitization, HTTP, Settings Base).
- **SPA Child Plugins**:
  * `xophz-compass-yellow-links` (Port 8088)
  * `xophz-compass-card-vault` (Port 8092)
  * `xophz-compass-fresh-mints` (Port 8091)
  * `xophz-compass-diego-lawfirm` (Port 8090)
  * `xophz-compass-phone` (Port 8082)
  * `xophz-compass-event-horizon` (Port 8081)
  * `xophz-compass-glowitheflow` (Port 5177)
- **Shared Contracts & Autoloading**:
  * Autoloader registered in `xophz-compass.php` for `Xophz_Compass_*` classes.
  * Child plugins extend `Xophz_Compass_Plugin_Base` and adopt `Xophz_Compass_Hookable_Trait`.
  * Dev proxy instantiated via `new Xophz_Compass_Dev_Proxy(...)` replacing blocking `@file_get_contents` loops.

## Feature Inventory
| # | Feature | Description | Milestone | Source | Status |
|---|---------|-------------|-----------|--------|--------|
| 1 | Core Helper Autoloader Integration | Ensure child plugins seamlessly access Xophz_Compass_* classes | M1 | Survey | DONE |
| 2 | Non-Blocking Dev Proxy: Yellow Links | Replace @file_get_contents with Xophz_Compass_Dev_Proxy (port 8088) | M1 | Survey | DONE |
| 3 | Non-Blocking Dev Proxy: Card Vault | Replace @file_get_contents with Xophz_Compass_Dev_Proxy (port 8092) | M1 | Survey | DONE |
| 4 | Non-Blocking Dev Proxy: Fresh Mints | Replace @file_get_contents with Xophz_Compass_Dev_Proxy (port 8091) and fix \|\| true leak | M1 | Survey | DONE |
| 5 | Non-Blocking Dev Proxy: Diego Lawfirm | Replace 7-host loop with Xophz_Compass_Dev_Proxy (port 8090) | M1 | Survey | DONE |
| 6 | Non-Blocking Dev Proxy: Phone | Replace single-host probe with Xophz_Compass_Dev_Proxy (port 8082) | M1 | Survey | DONE |
| 7 | Non-Blocking Dev Proxy: Event Horizon | Replace dev probe with Xophz_Compass_Dev_Proxy (port 8081) | M1 | Survey | DONE |
| 8 | Capability Checks: Card Vault | Replace current_user_can('administrator') with manage_options / manage_card_vault | M2 | Survey | DONE |
| 9 | Capability Checks: Yellow Links & Fresh Mints | Replace in_array('administrator') with capability checks | M2 | Survey | DONE |
| 10 | Redundant REST Nonce Elimination | Remove manual nonce verification in Card Vault REST endpoints | M2 | Survey | DONE |
| 11 | REST Response Normalization | Replace premature wp_send_json_* exits with WP_REST_Response in REST endpoints | M2 | Survey | DONE |
| 12 | Synthetic / Mock Data Purge | Remove hardcoded mock pageviews in Yellow Links analytics verifier | M2 | Survey | DONE |
| 13 | Secure Unauthenticated Endpoints | Fix open mutation endpoints in Yellow Links and Fresh Mints | M2 | Survey | DONE |
| 14 | Base Class Integration: Event Horizon | Refactor to extend Xophz_Compass_Plugin_Base and adopt Hookable Trait | M3 | Survey | DONE |
| 15 | Boilerplate Purge: Event Horizon | Delete redundant loader, i18n, activator, and hollow deactivator | M3 | Survey | DONE |
| 16 | Base Class Integration & Deactivator Purge: Card Vault | Refactor main class to extend base class and purge hollow deactivator | M3 | Survey | DONE |
| 17 | WPPB Boilerplate Purge Across Child Plugins | Systematically purge redundant loader, i18n, and deactivator files | M3 | Survey | DONE |
| 18 | 100% PHP Syntax Validation | Validate php -l on all modified and existing PHP files with 0 errors | M4 | Survey | DONE |
| 19 | Zero Em Dash and Code Quality Audit | Forensic audit confirming zero em dashes, zero mock data, 100% hook fidelity | M4 | Survey | DONE |
| 20 | Dev Proxy & Base Class: Glowitheflow | Support Nuxt/Vite dev proxy on port 5177 with non-blocking probe | M1 | Survey | DONE |

## Milestones
| # | Name | Scope | Dependencies | Status |
|---|------|-------|-------------|--------|
| 1 | Dev Proxy Consolidation | Replace blocking dev proxies across SPA child plugins with Xophz_Compass_Dev_Proxy | none | DONE |
| 2 | Anti-Pattern & Security Remediation | Fix role checks ('administrator' -> 'manage_options'), purge redundant REST nonces, normalize REST returns, remove mock data | M1 | DONE |
| 3 | Boilerplate Purge & Base Class Adoption | Refactor child plugins to extend Xophz_Compass_Plugin_Base, adopt Hookable Trait, purge redundant WPPB files | M2 | DONE |
| 4 | Final Verification & Audit Gate | 100% php -l syntax check, hook verification, forensic integrity audit | M3 | DONE |

## Interface Contracts
### Child Plugins <-> Core Helper Suite
- `Xophz_Compass_Dev_Proxy`:
  * Constructor: `new Xophz_Compass_Dev_Proxy( int $port = 5173, string $plugin_slug = '', array $options = array() )`
  * Candidate Host Resolution: `Xophz_Compass_Dev_Proxy::get_candidate_hosts()` checks `getenv('COMPASS_DEV_HOST')`, fallback to constant `COMPASS_DEV_HOST`, `$_ENV`, and `$_SERVER`.
  * Host Extraction: `Xophz_Compass_Dev_Proxy::extract_host( $raw )` normalizes hostnames, IPv4, bracketed IPv6, and unbracketed IPv6.
  * Probe: `Xophz_Compass_Dev_Proxy::is_dev_active()` (returns bool, 150ms timeout via `fsockopen`).
  * Enqueue: `$proxy->enqueue_vite_assets( string $entry_relative_path, array $deps = array() )`
  * Handle: `$proxy->handle_template_redirect()`
- `Xophz_Compass_Plugin_Base`:
  * Constructor: supports both Layout 1 `( $plugin_name, $version )` and Layout 2 `( $plugin_file_or_dir, $options )`.
  * Activation / Deactivation: static `activate()`, static `deactivate()`, `register_activation_hook()`, `register_deactivation_hook()`.
  * Hook registration: via `Xophz_Compass_Hookable_Trait` (`add_action`, `add_filter`, `register_hooks`).
- `Xophz_Compass_REST_Controller`:
  * Base controller extending `WP_REST_Controller`.
  * Envelopes: `$this->success_response( $data, $status = 200 )`, `$this->error_response( $message, $code, $status = 400 )`.
  * Permission: capability checks via `current_user_can( 'manage_options' )`.
