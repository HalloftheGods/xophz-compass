---
title: Compass Plugin Catalog Shortcode & REST API
description: Specification for the [compass_catalog] shortcode, REST discovery endpoint, Magic Hat circadian tokens, and Bedrock checkout routing.
category: specifications
tags:
  - compass
  - shortcode
  - catalog
  - magic-hat
  - circadian
  - rest-api
status: active
ai_summary: Documents the [compass_catalog] shortcode architecture, the GET /xophz-compass/v1/catalog REST endpoint, circadian rhythm CSS variable integration, and mycompassconsulting.com checkout routing.
---

# Compass Plugin Catalog Shortcode & REST API

## 1. Overview
The Compass Plugin Catalog shortcode (`[compass_catalog]`, `[compass_suite]`, `[compass_plugins]`) provides a modular showcase of WordPress companion plugins and sovereign modules. It features responsive grid layouts, category filtering tabs, real-time client-side search, an inspection modal dialog with turnkey cloud SaaS options, and dynamic checkout routing.

## 2. Shortcode Tags & Attributes
- **Shortcode Tags**: `[compass_catalog]`, `[compass_suite]`, `[compass_plugins]`
- **Attributes**:
  - `category`: Pre-filter to a single category (e.g. `Command Deck`, `True North`, `Trajectory`, `Castle Walls`, `Wizard's Tower`).
  - `columns`: Number of columns in desktop grid (`1`, `2`, `3`, `4`; defaults to `3`).
  - `limit`: Maximum number of plugins to render (`0` for unlimited).
  - `search`: Toggle search toolbar (`true` or `false`).
  - `tabs`: Toggle category pill tabs (`true` or `false`).
  - `checkout_url`: Custom checkout URL pattern. Defaults to `https://mycompassconsulting.com/buy/my-compass/{key}` or dynamic `home_url('/buy/my-compass/{key}')` when hosted directly on `mycompassconsulting.com`.

## 3. Dynamic Catalog REST API
- **Endpoint**: `GET /wp-json/xophz-compass/v1/catalog`
- **Controller Method**: `Xophz_Compass_Catalog_Shortcode::rest_get_catalog()`
- **Permission**: Public (`__return_true`)
- **Data Enrichment**:
  - Checks installed plugins in `WP_PLUGIN_DIR` for active state and installed version.
  - Resolves module registry metadata via `Xophz_Compass_Modules_API::find_module()`.
  - Discovers local SVG and PNG icons via `get_logo_url()` before remote fallbacks.
  - Allows third-party filtering via `apply_filters('compass_catalog_plugins', $catalog)`.

## 4. Magic Hat Circadian Rhythm Token Integration
The shortcode CSS adapts continuously to the 24-hour solar cycle via Magic Hat OKLCH tokens:
- **Card Surfaces**: `background: var(--mh-color-card, rgba(255, 255, 255, 0.05))` with `backdrop-filter: blur(var(--mh-glass-blur-sm, 12px))`
- **Borders & Dividers**: `1px solid var(--mh-color-border-muted, rgba(255, 255, 255, 0.08))`
- **Text & Typography**: `var(--mh-color-text-main)`, `var(--mh-color-text-heading)`, `var(--mh-color-text-muted)`
- **Brand & Accent Elements**: `var(--mh-color-brand-base)`, `var(--mh-color-cta-base)`, `var(--mh-color-brand-hover)`
- **Modal Glass Surface**: `background: var(--mh-color-main, #0f172a)` with ambient glow halos

## 5. Checkout Fulfillment Routing
- **Target Domain**: `mycompassconsulting.com`
- **Route Pattern**: `https://mycompassconsulting.com/buy/my-compass/{key}`
- **Slug Normalization**: Strips `xophz-compass-` and `xophz-` prefixes to resolve cleanly against the Bedrock checkout takeover template.
