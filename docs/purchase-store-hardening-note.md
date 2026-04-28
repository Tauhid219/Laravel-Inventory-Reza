# Purchase Store Hardening Note

This note captures the Phase 11 hardening work for the purchase creation flow.

## Current Canonical Store Shape

- request validation now requires:
  - `supplier_id`
  - `date`
  - `total_amount`
  - at least one `invoiceProducts` row
- each line now requires:
  - `product_id`
  - `quantity`
  - `unitcost`

## Server-Side Boundaries

- `purchase_no` is generated server-side
- `status` is forced to `pending`
- `created_by` is taken from the authenticated user
- line `total` is derived on the server from `quantity * unitcost`

## Persistence Behavior

- purchase creation now runs through `App\Actions\Purchases\CreatePurchase`
- payload normalization now lives in:
  - `App\Data\Purchases\CreatePurchaseData`
  - `App\Data\Purchases\PurchaseLineData`
- purchase header and line items are saved inside one database transaction
- line rows are inserted through `createMany()` instead of per-row inserts from the controller

## Regression Coverage Added

- purchase store test now verifies forged `purchase_no`, `status`, and `created_by` inputs are ignored
- purchase store test now verifies server-derived line totals are persisted
- validation failure test now verifies an empty line payload does not create a partial purchase

## Next Logical Follow-Up

Phase 12 should harden purchase approval with the same style:

- transaction-safe approval
- repeated approval guard
- stock increment idempotency
- focused approval workflow tests
