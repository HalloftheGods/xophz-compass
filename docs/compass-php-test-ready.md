# Project Compass: PHP Consolidation E2E Test Suite Readiness Manifest

## 1. Executive Summary

- **Status**: COMPLETE, VERIFIED & READY FOR MILESTONE BURN-DOWN
- **Test Runner Command**: `node tests/e2e/compass-php/run-tests.mjs`
- **Baseline Runner Command**: `node tests/e2e/compass-php/run-tests.mjs --baseline`
- **Total Test Suites**: 23
- **Total Test Assertions**: 129
- **Baseline Passing Assertions**: 111 (86.0% Pass Rate)
- **Baseline Unmigrated Assertions**: 18 (14.0% - accurately targeting planned M1-M4 refactorings)
- **Average Execution Duration**: ~53 seconds (full 129 assertions with live container execution)
- **Zero-Entropy Policy**: 100% Compliant (Zero em dashes, zero synthetic mock personal data)

---

## 2. Verification Matrix Breakdown by Tier

| Tier | Focus Area | Suites | Tests | Passed | Unmigrated (Baseline) | Pass Rate |
|------|------------|--------|-------|--------|------------------------|-----------|
| **Tier 1** | Feature Coverage (Features F01 - F20) | 20 | 100 | 82 | 18 | 82.0% |
| **Tier 2** | Boundary & Corner Cases | 1 | 15 | 15 | 0 | 100% |
| **Tier 3** | Cross-Feature Subsystem Integration | 1 | 8 | 8 | 0 | 100% |
| **Tier 4** | Real-World Operational Scenarios | 1 | 6 | 6 | 0 | 100% |
| **Total** | **All Tiers Combined** | **23** | **129** | **111** | **18** | **86.0%** |

---

## 3. Tier 1 Feature Coverage Map (Features F01 - F20)

Every discrete architectural feature in `PROJECT.md` is covered by 5 isolated test cases:

| Feature ID | Feature Description | Target Test File | Tests | Passed | Unmigrated | Status |
|------------|---------------------|------------------|-------|--------|------------|--------|
| **F01** | Core Helper Autoloader Integration | `f01-f04-autoloader-proxy.test.mjs` | 5 | 5 | 0 | PASS |
| **F02** | Non-Blocking Dev Proxy: Yellow Links | `f01-f04-autoloader-proxy.test.mjs` | 5 | 5 | 0 | PASS |
| **F03** | Non-Blocking Dev Proxy: Card Vault | `f01-f04-autoloader-proxy.test.mjs` | 5 | 5 | 0 | PASS |
| **F04** | Non-Blocking Dev Proxy: Fresh Mints | `f01-f04-autoloader-proxy.test.mjs` | 5 | 5 | 0 | PASS |
| **F05** | Non-Blocking Dev Proxy: Diego Lawfirm | `f05-f08-proxy-capabilities.test.mjs` | 5 | 5 | 0 | PASS |
| **F06** | Non-Blocking Dev Proxy: Phone | `f05-f08-proxy-capabilities.test.mjs` | 5 | 5 | 0 | PASS |
| **F07** | Non-Blocking Dev Proxy: Event Horizon | `f05-f08-proxy-capabilities.test.mjs` | 5 | 5 | 0 | PASS |
| **F08** | Capability Checks: Card Vault | `f05-f08-proxy-capabilities.test.mjs` | 5 | 3 | 2 | M2 TARGET |
| **F09** | Capability Checks: Yellow Links & Fresh Mints | `f09-f12-caps-nonce-mock.test.mjs` | 5 | 4 | 1 | M2 TARGET |
| **F10** | Redundant REST Nonce Elimination | `f09-f12-caps-nonce-mock.test.mjs` | 5 | 4 | 1 | M2 TARGET |
| **F11** | REST Response Normalization | `f09-f12-caps-nonce-mock.test.mjs` | 5 | 5 | 0 | PASS |
| **F12** | Synthetic / Mock Data Purge | `f09-f12-caps-nonce-mock.test.mjs` | 5 | 4 | 1 | M2 TARGET |
| **F13** | Secure Unauthenticated Endpoints | `f13-f16-endpoints-baseclass.test.mjs` | 5 | 3 | 2 | M2 TARGET |
| **F14** | Base Class Integration: Event Horizon | `f13-f16-endpoints-baseclass.test.mjs` | 5 | 3 | 2 | M3 TARGET |
| **F15** | Boilerplate Purge: Event Horizon | `f13-f16-endpoints-baseclass.test.mjs` | 5 | 1 | 4 | M3 TARGET |
| **F16** | Base Class Integration & Deactivator Purge: Card Vault | `f13-f16-endpoints-baseclass.test.mjs` | 5 | 3 | 2 | M3 TARGET |
| **F17** | WPPB Boilerplate Purge Across Child Plugins | `f17-f20-boilerplate-syntax-glow.test.mjs` | 5 | 2 | 3 | M3 TARGET |
| **F18** | 100% PHP Syntax Validation | `f17-f20-boilerplate-syntax-glow.test.mjs` | 5 | 5 | 0 | PASS |
| **F19** | Zero Em Dash and Code Quality Audit | `f17-f20-boilerplate-syntax-glow.test.mjs` | 5 | 5 | 0 | PASS |
| **F20** | Dev Proxy & Base Class: Glow With The Flow | `f17-f20-boilerplate-syntax-glow.test.mjs` | 5 | 5 | 0 | PASS |

---

## 4. Baseline Unmigrated Assertions (Milestone Burn-Down Targets)

The 18 baseline unmigrated assertions represent the exact legacy patterns identified in `docs/architecture/compass-php-audit-and-core-helpers.md`:

### Milestone 2 Targets: Anti-Pattern & Security Remediation (7 Tests)
1. `F08.2`: Elimination of raw `current_user_can("administrator")` in `wp-content/plugins/xophz-compass-card-vault/includes/class-card-vault-api.php`.
2. `F08.3`: Elimination of raw role checks in `wp-content/plugins/xophz-compass-card-vault/admin/class-card-vault-admin.php`.
3. `F09.1`: Yellow Links user role extraction replaces `in_array("administrator")` with capability check.
4. `F10.1`: Card Vault REST route definitions remove manual `check_dealer_or_nonce_permission` bypass.
5. `F12.1`: Yellow Links analytics verifier purges hardcoded `$pageviews = 50000;`.
6. `F13.2`: Yellow Links status route `/links/(?P<id>[a-zA-Z0-9_-]+)/status` requires `manage_options` (replaces `__return_true`).
7. `F13.3`: Yellow Links delete route `DELETABLE /links/(?P<id>[a-zA-Z0-9_-]+)` requires `manage_options` (replaces `__return_true`).

### Milestone 3 Targets: Boilerplate Purge & Base Class Adoption (11 Tests)
1. `F14.1`: Event Horizon main class refactored to extend `Xophz_Compass_Plugin_Base`.
2. `F14.2`: Event Horizon main class adopts `Xophz_Compass_Hookable_Trait`.
3. `F15.1`: `class-xophz-compass-event-horizon-loader.php` purged.
4. `F15.2`: `class-xophz-compass-event-horizon-i18n.php` purged.
5. `F15.3`: `class-xophz-compass-event-horizon-activator.php` purged of `die()` anti-pattern.
6. `F15.4`: `class-xophz-compass-event-horizon-deactivator.php` hollow class purged.
7. `F16.1`: Card Vault main class refactored to extend `Xophz_Compass_Plugin_Base`.
8. `F16.2`: `Card_Vault_Deactivator` hollow class purged.
9. `F17.1`: Census of hollow deactivators purged across all 28 WPPB plugins.
10. `F17.2`: Census of duplicate 129-line WPPB loaders purged across all 29 WPPB plugins.
11. `F17.3`: Census of duplicate single-method i18n classes purged across all 29 WPPB plugins.

---

## 5. Tier 2: Boundary & Corner Cases (15/15 PASS - 100%)

File: `tests/e2e/compass-php/tier2-boundaries/boundary-corner-cases.test.mjs`
- **B01**: Socket probe on offline port 65534 completes in <= 150ms without throwing or hanging.
- **B02**: Dev Proxy with unreachable hostname safely returns null without fatal crash.
- **B03**: Dev Proxy with empty candidate dist paths returns graceful fallback error HTML without throwing.
- **B04**: Dev Proxy `handle_template_redirect` ignores `/wp-admin` and `/wp-login.php` URIs.
- **B05**: Sanitization `get_http_method` defaults to GET for invalid or dangerous HTTP verbs (`TRACE`, `TRACK`, `<script>`).
- **B06**: Sanitization `get_json_input` returns null on malformed JSON payload without PHP notices.
- **B07**: Sanitization `get_string`, `get_int`, and `get_float` handle missing keys and non-scalar types with default fallbacks.
- **B08**: Sanitization `sanitize_deep` handles deeply nested mixed arrays without memory exhaustion.
- **B09**: Security `validate_compass_slug` rejects path traversal attempts.
- **B10**: Security `check_rate_limit` enforces threshold and blocks when rate exceeded.
- **B11**: Security `require_admin` returns false for unauthenticated context.
- **B12**: REST Controller `get_param_bool` parses diverse boolean representations (`true`, `false`, `1`, `0`, `null`).
- **B13**: REST Controller `error_response` produces valid WP_Error with custom status and error code.
- **B14**: HTTP Client `resolve_api_key` returns null cleanly when no providers configured.
- **B15**: Autoloader handles repeated registrations idempotently without duplicate hook errors.

---

## 6. Tier 3: Cross-Feature Subsystem Integration (8/8 PASS - 100%)

File: `tests/e2e/compass-php/tier3-integration/cross-feature-flows.test.mjs`
- **C01**: Autoloader + Dev Proxy: Autoloader dynamically instantiates Dev Proxy and registers rewrites.
- **C02**: Plugin Base + Hookable Trait + Spark Registry: Base class hooks trait and registers spark.
- **C03**: Plugin Base + Admin Menu Fallback: Standalone options page created when core is absent.
- **C04**: Settings Base + Rewrite Flush: Slug change triggers rewrite flushing watcher.
- **C05**: REST Controller + Security Capability + JSON Envelope: Permission check and success envelope.
- **C06**: Dev Proxy + Auth Session Injection + HMR Script: Proxy injects client and apiSettings.
- **C07**: HTTP Client + WP Connectors Gemini Key Fallback: Cascade resolves without error.
- **C08**: Sanitization + REST Input Extraction: JSON payload extracted and validated cleanly.

---

## 7. Tier 4: Real-World Operational Scenarios (6/6 PASS - 100%)

File: `tests/e2e/compass-php/tier4-real-world/operational-scenarios.test.mjs`
- **S01**: SPA Dev Server Offline to Dist Fallback: Instantaneous response (<200ms) without blocking.
- **S02**: Standalone Plugin Operation with Core Engine Deactivated: Zero fatal errors.
- **S03**: Authenticated REST Flow with Capability Enforcement: Full request lifecycle.
- **S04**: WP Connectors Key Resolution Under Missing Configuration: Clean empty state.
- **S05**: Full Ecosystem PHP Syntax Validation Gate: `php -l` across core and child plugins.
- **S06**: Monorepo Zero Em Dash and Data Integrity Forensic Gate: Zero em dashes in documentation.

---

## 8. CLI Runner Commands

```bash
# Complete 4-Tier test run
node tests/e2e/compass-php/run-tests.mjs

# Baseline capture (exits 0 with failure catalog)
node tests/e2e/compass-php/run-tests.mjs --baseline

# Verbose tier breakdown
node tests/e2e/compass-php/run-tests.mjs --verbose

# Run specific tier
node tests/e2e/compass-php/run-tests.mjs --tier=1
node tests/e2e/compass-php/run-tests.mjs --tier=2
node tests/e2e/compass-php/run-tests.mjs --tier=3
node tests/e2e/compass-php/run-tests.mjs --tier=4
```
