# AdminLTE Manual QA Checklist

Use this checklist for the final browser pass before marking Phase 23 as `Done`.

## Global Shell

- Log in and confirm the authenticated shell loads without console errors.
- Toggle the sidebar pushmenu on desktop and mobile widths.
- Expand and collapse treeview sections, then refresh on a nested page to confirm active/open state persistence.
- Switch between `Classic Dashboard`, `Dark Fixed Dashboard`, and `Compact Dashboard`, then reload to confirm theme persistence.
- Open the navbar theme dropdown and user menu.

## Core Interactive Screens

- Visit order create and confirm customer Tom Select opens, searches, clears, and preserves the selected value after validation failure.
- Visit purchase create and confirm supplier Tom Select behaves the same way.
- Visit due show and due edit, open the payment modal, submit valid and invalid amounts, and confirm flash messaging plus recalculated due values.
- Visit the invoice preview flow from order create and confirm the payment modal opens, submits, and preserves the hidden note value.
- Open at least one Livewire-heavy table screen and confirm filtering, searching, pagination, and post-update JS wiring still work.

## Module Spot Checks

- Verify supplier show renders correctly when supplier `type` is null and when it is set.
- Delete a supplier with no purchases and confirm success messaging.
- Attempt to delete a supplier with purchases and confirm the guard message appears.
- Open representative CRUD pages for products, categories, customers, purchases, and orders to confirm headers, breadcrumbs, and action buttons render correctly.

## Finish Criteria

- Record any browser-only regressions before updating the phase tracker.
- If this pass is clean, update `docs/adminlte-blade-migration-plan.md` and mark Phase 23 as `Done`.

## Latest Result

- Final browser pass completed and reported clean after the latest AdminLTE fixes, including dark-theme detail-field readability and table-header action alignment.
