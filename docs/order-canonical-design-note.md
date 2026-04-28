# Order Canonical Design Note

This note documents the historical order architecture, why `orders-v2` existed, and the canonical direction that was later implemented.

## Phase 30 Status

The temporary `orders-v2` module has now been retired from active app code. The current implementation uses the canonical `orders` module:

- `app/Http/Controllers/Order/OrderController.php`
- `app/Http/Requests/Order/StoreOrderRequest.php`
- `app/Livewire/OrderForm.php`
- `app/Livewire/Tables/OrderTable.php`
- `resources/views/orders/*`
- `resources/views/livewire/order-form.blade.php`
- `resources/views/livewire/tables/order-table.blade.php`

The old `OrderV2`, `ordersV2`, `orders-v2`, and `order-v2` names should be treated as historical references only. The regression suite asserts that the legacy `ordersV2.index` route name is gone.

## Why This Note Exists

Historically, the project had two order creation flows:

- a legacy cart-based `orders` flow
- a newer `orders-v2` flow created to support manual total override behavior

That duplication solved an immediate business need, but it increased maintenance cost, created route and view confusion, and made future fixes riskier.

The goal is not to keep both systems. The goal is to merge the useful parts into one canonical order module.

## Business Requirement Behind the Duplicate Flow

The key business need is:

- item lines should still be calculated normally
- but the final order total should be manually overrideable when needed

That requirement was difficult to implement in the cart-based legacy flow, so a second flow was introduced instead of evolving the original module.

## Historical Flow Summary

## Legacy Flow: `orders`

Historical files:

- `routes/web.php`
- `app/Http/Controllers/Order/OrderController.php`
- `app/Http/Controllers/InvoiceController.php`
- `app/Http/Requests/Order/OrderStoreRequest.php`
- `app/Livewire/OrderForm.php`
- `resources/views/orders/create.blade.php`
- `resources/views/invoices/index.blade.php`

### User journey

1. User opens `orders.create`
2. User selects products inside a cart-backed Livewire UI
3. Form submits to `invoice.create`
4. User sees invoice preview page
5. User enters payment data in modal
6. Final save happens through `orders.store`
7. Order remains pending by default
8. Stock is reduced later when order is completed

### Behavior characteristics

- Uses shopping cart package state as the source of truth during creation
- Total, subtotal, VAT, and due are derived from cart values in `OrderStoreRequest`
- Supports payment type and partial payment entry
- Includes separate invoice preview step before final save
- Uses cart contents to create `order_details`

### Strengths

- already integrated with payment fields
- includes preview-before-save experience
- matches the original package-oriented architecture

### Weaknesses

- hard to extend for custom pricing behavior
- business logic is spread across Livewire, cart state, request preparation, invoice preview, and controller
- line items are not submitted as the primary source of truth
- manual total override is not part of the natural flow
- cart lifecycle increases complexity

## Newer Flow: `orders-v2`

Historical files:

- `routes/web.php`
- `app/Http/Controllers/OrderV2/OrderV2Controller.php`
- `app/Http/Requests/OrderV2/StoreOrderV2Request.php`
- `app/Livewire/OrderV2Form.php`
- `resources/views/ordersV2/create.blade.php`
- `resources/views/livewire/order-v2-form.blade.php`

### User journey

1. User opens `ordersV2.create`
2. User adds item rows directly in a Livewire invoice-style form
3. System calculates per-line totals
4. User can manually type a final total into `total_amount`
5. Form submits directly to `ordersV2.store`
6. Order remains pending by default
7. Stock is reduced later when order is approved/completed

### Behavior characteristics

- Does not use the cart package for persistence
- Uses submitted `invoiceProducts` array as the order detail source
- Supports manual final total override directly in the UI
- Stores `sub_total` and `total` using the same submitted `total_amount`
- Forces `payment_type = Cash`, `pay = total_amount`, `due = 0`
- Removes invoice preview step

### Strengths

- much easier to support manual pricing behavior
- more direct create flow
- item rows are explicit in the submitted request
- easier starting point for a canonical refactor

### Weaknesses

- payment behavior is oversimplified
- subtotal vs final total distinction is currently blurred
- order financial fields are partially hardcoded
- duplicates many responsibilities already present in legacy flow
- validation and pricing rules are still implicit

## Detailed Comparison

## Creation state model

Legacy flow:

- cart package is the temporary order state

V2 flow:

- Livewire `invoiceProducts` array is the temporary order state

## Final total behavior

Legacy flow:

- final total comes from cart totals
- user does not naturally override the grand total

V2 flow:

- final total can be manually overridden
- subtotal and total are not clearly separated in persistence

## Payment capture

Legacy flow:

- user selects payment type
- user enters pay amount
- due is computed

V2 flow:

- payment type hardcoded to `Cash`
- pay equals total
- due equals zero

## Preview step

Legacy flow:

- yes, invoice preview exists before final save

V2 flow:

- no, direct save

## Stock deduction timing

Both flows:

- stock is not reduced on create
- stock is reduced on approval/completion

## Route and UI duplication

Current duplication exists in:

- route prefixes
- create screens
- listing screens
- show screens
- update endpoints
- deletion endpoints

This makes the codebase harder to reason about and increases regression risk.

## Important Risks Found

1. Two flows write to the same `orders` and `order_details` tables with different assumptions.
2. Legacy flow treats cart totals as authoritative, while V2 treats submitted `total_amount` as authoritative.
3. V2 currently loses some payment flexibility that legacy flow already had.
4. Legacy flow has stronger payment semantics, but weaker custom pricing flexibility.
5. Future fixes may accidentally patch one flow while leaving the other inconsistent.

## Recommended Canonical Direction

Keep the `orders-v2` style item-row builder as the base.

Do not keep the cart package as the core order creation mechanism.

Instead, build one canonical order module with these rules:

1. Order lines are submitted explicitly as structured rows.
2. System-calculated subtotal is always preserved.
3. Final total may be manually overridden when business rules allow it.
4. Payment fields are explicit:
   - payment type
   - paid amount
   - due amount
5. Final saved order should keep both:
   - calculated subtotal
   - user-approved final total
6. Stock is reduced only on completion, not on draft or pending creation.
7. Completion must re-check stock before decrementing.
8. There should be one route group and one main set of views for orders.

## Canonical Target Behavior

The future canonical order flow should look like this:

1. User opens one order create page
2. User adds item rows directly
3. System calculates subtotal from item lines
4. User may optionally override final total
5. User selects payment type and enters paid amount
6. System derives due amount from final total and paid amount
7. Order saves in pending state
8. Order approval later re-checks stock and completes the order

## Migration Strategy Recommendation

Recommended implementation order:

1. Formalize pricing and payment rules
2. Extract shared calculation logic
3. Build canonical create/store logic around row-based order items
4. Re-introduce proper payment behavior into the canonical flow
5. Update show/list/complete actions to use the same module
6. Remove legacy-only routes and views

## Decision

Recommended decision:

- Keep the row-based builder approach
- Drop cart-based order creation as the final architecture
- Reintroduce missing payment semantics from the legacy flow into the row-based flow
- Retire `orders-v2` naming after merge and move back to a single `orders` module

## Files Reviewed

- `app/Http/Controllers/Order/OrderController.php`
- `app/Http/Controllers/OrderV2/OrderV2Controller.php`
- `app/Http/Controllers/InvoiceController.php`
- `app/Http/Requests/Order/OrderStoreRequest.php`
- `app/Http/Requests/OrderV2/StoreOrderV2Request.php`
- `app/Livewire/OrderForm.php`
- `app/Livewire/OrderV2Form.php`
- `app/Livewire/ProductCart.php`
- `app/Models/Order.php`
- `resources/views/orders/create.blade.php`
- `resources/views/ordersV2/create.blade.php`
- `resources/views/livewire/order-form.blade.php`
- `resources/views/livewire/order-v2-form.blade.php`
- `resources/views/invoices/index.blade.php`
- `database/migrations/2023_05_04_084431_create_orders_table.php`
- `database/migrations/2023_05_04_084646_create_order_details_table.php`
