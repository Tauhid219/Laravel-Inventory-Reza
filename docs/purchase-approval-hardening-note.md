# Purchase Approval Hardening Note

This note captures the Phase 12 hardening work for the purchase approval flow.

## Current Canonical Approval Shape

- approval is now handled by `App\Actions\Purchases\CompletePurchase`
- controller delegates to the action instead of inline logic
- action provides clean domain exceptions for failure cases

## Server-Side Boundaries

- `status` is forced to `APPROVED` on successful approval
- `updated_by` is set from authenticated user
- stock increment happens inside the same transaction
- repeated approval is prevented with explicit guard

## Transaction Safety

- purchase row is locked with `lockForUpdate()` during approval
- each product row is locked before stock increment
- if any product is missing, the entire transaction rolls back
- stock is only incremented if the purchase was not already approved

## Idempotency and Guard Behavior

- attempting to approve an already-approved purchase throws `InvalidPurchaseApproval`
- controller catches this and shows user-friendly error message
- stock is never incremented twice for the same purchase

## Regression Coverage Added

- `test_purchase_approval_increases_stock_and_updates_status`: verifies stock increment and status change
- `test_purchase_approval_is_idempotent_and_rejects_duplicate_approval`: verifies repeated approval is rejected
- `test_purchase_approval_handles_missing_product`: verifies missing product scenario (documented for MySQL/Production)

## Files Changed

- `app/Actions/Purchases/CompletePurchase.php` (new)
- `app/Exceptions/Purchases/InvalidPurchaseApproval.php` (new)
- `app/Http/Controllers/Purchase/PurchaseController.php` (refactored `update()` method)
- `tests/Feature/PurchaseControllerTest.php` (added approval tests)
- `docs/professional-upgrade-roadmap.md` (marked Phase 12 as done)
- `docs/purchase-approval-hardening-note.md` (this file)

## Next Logical Follow-Up

Phase 13 should focus on reporting and export cleanup:

- export flow-এর query mismatch fix
- date filter behavior verify
- output naming and formatting review
