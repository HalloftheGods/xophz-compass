# Forminator Autofill System

## Overview
The Forminator Autofill System in Xophz-COMPASS automatically populates form fields with a user's previous submission data when they return to a form. This ensures a seamless experience for users interacting with the "Magic Formula," "Questbook CRM," and other forms across the Bedrock framework.

## Architectural Decisions

### 1. Hooking `forminator_cform_render_fields` vs `forminator_field_{slug}_autofill`
Initially, the goal was to use the `forminator_field_{slug}_autofill` hook. However, investigation revealed that Forminator's native autofill providers (which use this hook) are designed for a different paradigm:
- Native providers (like WP User) simply map a specific attribute (e.g., `first_name`) to a field.
- They do not know the context of the field they are filling (the `element_id`).
- They require the site admin to manually configure *every single field* in the Forminator UI to use the provider.

To achieve true "automatic autopopulation of a previous submission," we instead hook into `forminator_cform_render_fields`. This allows us to intercept the entire form schema right before rendering, loop through all fields, and dynamically inject the values from the previous submission based on their `element_id` directly. This requires zero manual configuration in the Forminator builder UI.

### 2. Direct `$wpdb` Query vs `Forminator_Form_Entry_Model::query_entries()`
Forminator's `query_entries()` method lacks support for standard WordPress `meta_query` arguments. Therefore, to securely and efficiently fetch a user's previous entry, a direct `$wpdb` query is utilized:
1. It queries the `FORM_ENTRY` and `FORM_ENTRY_META` tables.
2. It filters by the active form ID and the logged-in user's ID (`_user_id`).
3. It limits the result to `1` (the most recent) and orders by `date_created DESC`.

This ensures optimal performance without loading unnecessary entry objects into memory.

## User Feedback
When a form is successfully populated with historical data, an HTML notification block is automatically prepended to the form schema. This block aligns with the project's "Neon/Glassmorphic" aesthetic, displaying:
> 🪄 Form autopopulated from your previous submission.

## File Locations
- **Implementation:** `includes/class-xophz-compass-forminator-autofill.php`
- **Registration:** Included in `includes/class-xophz-compass.php` within `load_dependencies()`.
