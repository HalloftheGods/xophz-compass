# Project Compass: PHP Consolidation E2E Test Infrastructure & Quality Assurance Architecture

## 1. Overview & Verification Strategy

Project Compass represents the core WordPress systems architecture and child plugin ecosystem of the Elysium monorepo. The PHP Consolidation and Refactoring initiative standardizes plugin architecture by replacing duplicate, blocking dev proxies with `Xophz_Compass_Dev_Proxy`, purging thousands of lines of redundant WPPB boilerplate (loaders, hollow deactivators, i18n classes) in favor of `Xophz_Compass_Plugin_Base` and `Xophz_Compass_Hookable_Trait`, remedying security vulnerabilities (raw 'administrator' role checks, redundant REST nonces, unauthenticated mutation routes), and enforcing PHP 8.2+ compatibility.

The E2E Test Infrastructure operates under an opaque-box paradigm:
- Tests evaluate public interface contracts, class instantiation, hook registration fidelity, TCP socket probing timeouts, and REST API envelopes.
- Verification does not depend on private internal component implementations, preventing test brittleness during ongoing refactoring.
- Every test assertion traces directly to user requirements in `ORIGINAL_REQUEST.md`, architectural specifications in `PROJECT.md`, and findings in `docs/architecture/compass-php-audit-and-core-helpers.md`.
- Zero-Entropy compliance is strictly enforced: zero em dashes (`\u2014`), zero synthetic mock personal data (no fake `@gmail.com` addresses or `555` phone numbers), and 100% deterministic execution.

---

## 2. Four-Tier Testing Methodology

The verification matrix is organized into four progressive tiers:

```
+-----------------------------------------------------------------------+
|            Tier 4: Real-World Operational Scenarios                   |
|    (Dev-to-Prod Toggle, Standalone Graceful Fallback, REST Pipeline,  |
|     Missing Connector Resilience, Full Ecosystem Syntax & Audit Gate) |
+-----------------------------------------------------------------------+
                                   ^
+-----------------------------------------------------------------------+
|            Tier 3: Cross-Feature Subsystem Integration                |
|    (Autoloader + Proxy; Base Class + Hooks + Sparks; Settings Flush;   |
|     REST Controller + Security Caps; Injected HMR; Connectors Cascade)|
+-----------------------------------------------------------------------+
                                   ^
+-----------------------------------------------------------------------+
|            Tier 2: Boundary, Edge & Corner Cases                      |
|    (150ms Socket Timeout Cap, Unreachable Hosts, Empty Dist Paths,    |
|     Dangerous HTTP Verbs, Malformed JSON, Path Traversal, Rate Limits)|
+-----------------------------------------------------------------------+
                                   ^
+-----------------------------------------------------------------------+
|            Tier 1: Feature Coverage (Features F01 through F20)        |
|    (>=5 Test Cases per Feature: 100 Total Tests Covering Autoloader,   |
|     6 SPA Proxies, Glowitheflow, Capabilities, Nonces, Base Classes)  |
+-----------------------------------------------------------------------+
```

### Tier 1: Feature Coverage (Features F01 - F20)
Covers all 20 discrete architectural features defined in `PROJECT.md` with at least 5 isolated test cases per feature (100 total tests):
- **F01: Core Helper Autoloader Integration**: SPL registration, classmap resolution of all 10 core classes, dynamic fallback for `Xophz_Compass_*`, PSR-4 prefix mapping `Xophz\\Compass\\Core\\`, non-matching class pass-through.
- **F02: Non-Blocking Dev Proxy: Yellow Links (Port 8088)**: Configuration, <= 150ms probe timeout, production dist fallback without hanging, dev asset URL rewriting, `window.wpApiSettings` script injection.
- **F03: Non-Blocking Dev Proxy: Card Vault (Port 8092)**: Configuration, non-blocking socket probe, candidate dist paths fallback, asset path rewriting, user session metadata injection.
- **F04: Non-Blocking Dev Proxy: Fresh Mints (Port 8091)**: Configuration, audit for elimination of `|| true` leak, probe timeout <= 150ms, dist fallback, environment context.
- **F05: Non-Blocking Dev Proxy: Diego Lawfirm (Port 8090)**: Configuration, audit for elimination of 7-host sequential loop, fast candidate resolution, dist fallback, settings script.
- **F06: Non-Blocking Dev Proxy: Phone (Port 8082)**: Configuration, non-blocking probe, rewrite rule registration, dist fallback, nonce context.
- **F07: Non-Blocking Dev Proxy: Event Horizon (Port 8081)**: Configuration, socket probe latency, HMR client injection, production dist serving, `/wp-admin` route passthrough.
- **F08: Capability Checks: Card Vault**: Enforce `manage_card_vault` or `manage_options`, audit for elimination of `current_user_can('administrator')`, admin page capability check, consignor dashboard permissions, 403 error envelope.
- **F09: Capability Checks: Yellow Links & Fresh Mints**: Role extraction audit replacing `in_array('administrator')`, Security helper integration, `require_admin()` guard, clean empty states for anonymous users.
- **F10: Redundant REST Nonce Elimination**: Elimination of redundant nonce bypasses in Card Vault, reliance on core WordPress REST cookie authentication, capability-based permission callbacks, authorization required error handling.
- **F11: REST Response Normalization**: Return `WP_REST_Response` instead of premature `wp_send_json_*` exits, `{ success: true, data: T }` envelope standardization, `WP_Error` formatting, pipeline continuation, header preservation.
- **F12: Synthetic / Mock Data Purge**: Elimination of hardcoded `$pageviews = 50000;` in Yellow Links analytics verifier, clean 0 empty state, dynamic pricing verification, zero synthetic emails or phone numbers.
- **F13: Secure Unauthenticated Endpoints**: Rate limiting via `Xophz_Compass_Security`, elimination of `__return_true` on mutating routes in Yellow Links, capability requirements on Fresh Mints mutation endpoints, 401/403 status codes.
- **F14: Base Class Integration: Event Horizon**: Main class extending `Xophz_Compass_Plugin_Base`, Hookable Trait adoption, textdomain loading on `init` priority 5, admin menu integration, YouMeOS Spark registry filter.
- **F15: Boilerplate Purge: Event Horizon**: Purge of redundant loader, purge of redundant i18n, elimination of activator `die()` anti-pattern, purge of hollow deactivator, 100% hook fidelity.
- **F16: Base Class Integration & Deactivator Purge: Card Vault**: Base class inheritance, hollow deactivator elimination, database schema encapsulation in activator, Spark registry integration, Settings shortcut in plugin action links.
- **F17: WPPB Boilerplate Purge Across Child Plugins**: Census of 28 hollow deactivators, census of 29 duplicate loaders, census of 29 duplicate i18n classes, base class inheritance, hook preservation.
- **F18: 100% PHP Syntax Validation**: `php -l` validation across all 10 core helpers, 6 SPA plugins, glowitheflow, core engine, PHP 8.2+ deprecation elimination.
- **F19: Zero Em Dash and Code Quality Audit**: Zero em dashes across core classes, child plugins, documentation, clean empty states, zero hardcoded credentials.
- **F20: Dev Proxy & Base Class: Glow With The Flow (Port 5177)**: Dev proxy port 5177 config, socket probing replacing blocking loop, Nuxt `baseURL` rewrite, `window.wpApiSettings` injection, `index.html` and `200.html` candidate fallbacks.

### Tier 2: Boundary & Corner Cases (15 Tests)
Evaluates edge conditions, boundary values, timeout caps, and security edge cases:
- B01: Socket probe on offline port 65534 completes in <= 150ms without throwing or hanging.
- B02: Dev Proxy with unreachable hostname safely returns null without fatal crash.
- B03: Dev Proxy with empty candidate dist paths returns graceful fallback error HTML without throwing.
- B04: Dev Proxy `handle_template_redirect` ignores `/wp-admin` and `/wp-login.php` URIs.
- B05: Sanitization `get_http_method` defaults to 'GET' for invalid or dangerous HTTP verbs (`TRACE`, `TRACK`, `<script>`).
- B06: Sanitization `get_json_input` returns null on malformed JSON payload without PHP notices.
- B07: Sanitization `get_string`, `get_int`, and `get_float` handle missing keys, non-scalars, and strings cleanly with defaults.
- B08: Sanitization `sanitize_deep` handles deeply nested mixed arrays without memory leaks.
- B09: Security `validate_compass_slug` rejects path traversal attempts (`../`, `..%2F`, special characters).
- B10: Security `check_rate_limit` enforces threshold and blocks when requests exceed max within window.
- B11: Security `require_admin` returns false for unauthenticated context.
- B12: REST Controller `get_param_bool` correctly parses string 'true', 'false', '1', '0', and null.
- B13: REST Controller `error_response` produces valid WP_Error with custom status and error code.
- B14: HTTP Client `resolve_api_key` cascades across WP Connectors, options, constants, and $_ENV before returning null.
- B15: Autoloader handles repeated registrations idempotently without duplicate hook errors.

### Tier 3: Cross-Feature Subsystem Integration (8 Tests)
Verifies pairwise and multi-feature interaction pipelines across the ecosystem:
- C01: Autoloader + Dev Proxy: Autoloader dynamically instantiates Dev Proxy and registers rewrites.
- C02: Plugin Base + Hookable Trait + Spark Registry: Base class hooks trait and registers spark.
- C03: Plugin Base + Admin Menu Fallback: Standalone options page created when core is absent.
- C04: Settings Base + Rewrite Flush: Slug change triggers rewrite flushing watcher.
- C05: REST Controller + Security Capability + JSON Envelope: Permission check and success envelope.
- C06: Dev Proxy + Auth Session Injection + HMR Script: Proxy injects client and apiSettings.
- C07: HTTP Client + WP Connectors Gemini Key Fallback: Cascade resolves without error.
- C08: Sanitization + REST Input Extraction: JSON payload extracted and validated cleanly.

### Tier 4: Real-World Operational Scenarios (6 Tests)
Simulates complete production and development lifecycle operations:
- S01: SPA Dev Server Offline to Dist Fallback: Instantaneous response (<200ms) without blocking.
- S02: Standalone Plugin Operation with Core Engine Deactivated: Zero fatal errors, clean admin options fallback.
- S03: Authenticated REST Flow with Capability Enforcement: Nonce, capability, sanitization, and envelope flow.
- S04: WP Connectors Key Resolution Under Missing Configuration: Clean empty state without synthetic keys.
- S05: Full Ecosystem PHP Syntax Validation Gate: `php -l` passes across all core and child plugins.
- S06: Monorepo Zero Em Dash and Data Integrity Forensic Gate: Zero em dashes across code, tests, and documentation.

---

## 3. Test Runner & CLI Command Reference

The test suite runs natively on Node.js and interfaces with the live WordPress Docker container (`u-wordpress`) for real PHP execution and linting:

```bash
# Run the complete Project Compass E2E test suite
node tests/e2e/compass-php/run-tests.mjs

# Run with verbose test-by-test breakdown
node tests/e2e/compass-php/run-tests.mjs --verbose

# Run a specific tier only
node tests/e2e/compass-php/run-tests.mjs --tier=1
node tests/e2e/compass-php/run-tests.mjs --tier=2
node tests/e2e/compass-php/run-tests.mjs --tier=3
node tests/e2e/compass-php/run-tests.mjs --tier=4

# Run in baseline mode (records empirical baseline for upcoming milestones)
node tests/e2e/compass-php/run-tests.mjs --baseline
```

### Exit Code Semantics
- **Exit Code 0**: In normal mode, indicates 100% test success (all assertions passed). In `--baseline` mode, indicates baseline capture completed successfully.
- **Exit Code 1**: In normal mode, indicates one or more assertions failed (e.g. unmigrated child plugin boilerplate or syntax error).

---

## 4. Directory Structure

```
tests/e2e/compass-php/
├── run-tests.mjs                                  # Master test runner and CLI entry point
├── harness/
│   ├── test-framework.mjs                         # Zero-dependency runner, assert, em dash checks
│   ├── php-executor.mjs                           # Docker u-wordpress PHP bridge and syntax linter
│   └── code-analyzer.mjs                          # Static AST and source inspection utilities
├── tier1-features/
│   ├── f01-f04-autoloader-proxy.test.mjs          # F01 (Autoloader), F02 (Yellow Links), F03 (Card Vault), F04 (Fresh Mints)
│   ├── f05-f08-proxy-capabilities.test.mjs        # F05 (Diego Lawfirm), F06 (Phone), F07 (Event Horizon), F08 (Card Vault Caps)
│   ├── f09-f12-caps-nonce-mock.test.mjs           # F09 (Caps), F10 (Nonces), F11 (REST Response), F12 (Mock Data)
│   ├── f13-f16-endpoints-baseclass.test.mjs       # F13 (Endpoints), F14 (EH Base), F15 (EH Boilerplate), F16 (CV Base)
│   └── f17-f20-boilerplate-syntax-glow.test.mjs   # F17 (WPPB Boilerplate), F18 (Syntax), F19 (Em Dash), F20 (Glowitheflow)
├── tier2-boundaries/
│   └── boundary-corner-cases.test.mjs             # B01 - B15 Boundary and corner condition assertions
├── tier3-integration/
│   └── cross-feature-flows.test.mjs               # C01 - C08 Subsystem integration pipelines
└── tier4-real-world/
    └── operational-scenarios.test.mjs             # S01 - S06 Complete operational workflows
```

---

## 5. Verification Integrity & Zero-Entropy Policy

- **No Facade or Dummy Tests**: Every test assertion evaluates real PHP execution, active class definitions, socket timeouts, or file contents.
- **Strictly Zero Em Dashes**: Zero em dashes (`\u2014`) exist in any test file, log output, or documentation.
- **Zero Mock / Synthetic Data**: Tests use clean empty states, generic test identifiers, or live WordPress options.
