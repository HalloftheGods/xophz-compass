# Changelog

All notable changes to this project will be documented in this file.

## [2026-09-04]

### Fixed
- Menu Duplication: Added deduplication checks in `Xophz_Compass::add_submenu()` and `Xophz_Compass_Admin::sort_xophz_submenu_alphabetically()` to prevent child plugins and hooks from registering duplicate entries in the WordPress admin menu.

## [2026-05-01]

### Added
- Forminator Autofill System: Implemented `Xophz_Compass_Forminator_Autofill` to automatically retrieve a user's most recent submission and inject its historical data into a new Forminator form instance.
- Visual Notification: Added a glassmorphic/neon UI notification to inform users when a form has been automatically populated from previous data.

### Changed
- Registered `class-xophz-compass-forminator-autofill.php` within the core plugin loader logic (`class-xophz-compass.php`).
