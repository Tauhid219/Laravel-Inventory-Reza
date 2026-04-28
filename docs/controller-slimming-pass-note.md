# Controller Slimming Pass Note

This note captures the Phase 15 controller slimming pass.

## What Changed

- `ProductController` now delegates product create and update persistence to `App\Actions\Product\UpsertProduct`
- `PurchaseController` now delegates export generation to `App\Actions\Purchases\ExportPurchaseReport`
- `OrderV2Controller` now delegates order line stock precheck to `App\Actions\Orders\ValidateRequestedOrderProducts`
- invoice download now runs through the canonical `OrderV2Controller`
- legacy runtime dependency on `App\Http\Controllers\Order\OrderController` has been removed

## Controller Status

### Product

- image handling remains in `HandleProductImage`
- product persistence, slug generation, fallback code generation, and image sync are centralized in `UpsertProduct`

### Purchase

- purchase create and approve flows were already action-based
- export/report file generation is now extracted from the controller

### Orders

- canonical order create, complete, and pre-store availability checks are action-based
- invoice rendering now stays inside the canonical order controller

## Files Changed

- `app/Actions/Product/UpsertProduct.php` (new)
- `app/Actions/Purchases/ExportPurchaseReport.php` (new)
- `app/Actions/Orders/ValidateRequestedOrderProducts.php` (new)
- `app/Http/Controllers/Product/ProductController.php`
- `app/Http/Controllers/Purchase/PurchaseController.php`
- `app/Http/Controllers/OrderV2/OrderV2Controller.php`
- `app/Http/Controllers/Order/OrderController.php` (removed)
- `routes/web.php`
- `tests/Feature/OrderControllerTest.php`
- `docs/professional-upgrade-roadmap.md`
- `docs/controller-slimming-pass-note.md`

## Verification

```text
php artisan test tests/Feature/ProductControllerTest.php
php artisan test tests/Feature/PurchaseControllerTest.php tests/Feature/OrderControllerTest.php
```

## Outcome

- the targeted controllers are now closer to HTTP orchestration only
- repeated business logic is centralized in actions
- the active order flow no longer depends on the legacy order controller
