# Order Legacy Retirement Note

This note records the retirement approach used for the legacy order flow.

## What Changed

- canonical route names are now `orders.*`
- the active create/store/show/update/delete behavior now uses `OrderController`
- old cart/invoice-preview order creation is no longer the primary active flow
- `orders-v2` URLs and route aliases have been retired

## Why This Approach

The project needed to retire the old cart-based flow without breaking every existing link in one risky step.

So the retirement strategy is:

1. keep the newer row-based flow as the active implementation
2. move canonical navigation back under the normal `orders` naming
3. remove temporary `orders-v2` aliases after regression coverage confirms the canonical routes
4. keep remaining order behavior under one canonical controller, request, Livewire form, and view set

## Current Active Canonical Path

- index: `orders.index`
- pending: `orders.pending`
- complete: `orders.complete`
- create: `orders.create`
- store: `orders.store`
- show: `orders.show`
- update: `orders.update`
- delete: `orders.delete`

## Compatibility Layer Removed

The temporary `ordersV2.*` route aliases have been removed. The regression suite intentionally asserts that `ordersV2.index` no longer exists.

## Current Active Files

The canonical order module now lives in:

- `app/Http/Controllers/Order/OrderController.php`
- `app/Http/Requests/Order/StoreOrderRequest.php`
- `app/Livewire/OrderForm.php`
- `resources/views/orders/create.blade.php`
- `resources/views/livewire/order-form.blade.php`

## Next Cleanup Direction

The next structural cleanup should eventually:

- keep historical docs synchronized with the canonical `orders` module
- preserve the route regression that proves `ordersV2.*` aliases remain retired
- continue improving order behavior through action classes and focused tests
