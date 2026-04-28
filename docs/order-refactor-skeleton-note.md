# Order Refactor Skeleton Note

This note captures the initial skeleton created for the canonical order refactor.

## Goal

Create a clean place for future order logic before moving behavior out of controllers and Livewire components.

## New Structure

### `app/Data/Orders`

Purpose:

- hold normalized order payload data
- separate incoming request shape from controller code

Files:

- `CreateOrderData.php`
- `OrderLineData.php`
- `OrderPaymentData.php`
- `OrderPricingData.php`

### `app/Actions/Orders`

Purpose:

- hold reusable business workflow steps
- move order logic out of controllers over the next phases

Files:

- `CalculateOrderTotals.php`
- `CreateOrder.php`
- `CompleteOrder.php`

## Responsibility Split

### Data objects

These classes describe:

- who the customer is
- which item rows were submitted
- what payment info came in
- what pricing result was derived

They should stay small and explicit.

### Action classes

These classes will become the new business entry points for:

- pricing calculation
- order creation
- order completion

## What Is Already Implemented

- order line normalization from raw arrays
- a shared pricing calculation entry point
- validation-aware subtotal, final total, pay, and due normalization
- a canonical create-order payload object
- a placeholder create action that builds an `Order` model shape
- a placeholder completion action for future stock and status logic

## What Is Not Yet Wired

At this phase, these classes are intentionally not yet used by controllers.

That work belongs to the next phases:

- Phase 7: shared calculation logic
- Phase 8: canonical store flow
- Phase 9: approval and stock completion hardening

## Why This Structure

The current order logic is spread across:

- controllers
- Livewire components
- request classes
- cart state
- invoice preview flow

This skeleton gives us one obvious place to move that logic step by step without doing a risky rewrite in one go.

## Immediate Next Use

Next phase should move subtotal, final total, pay, and due normalization into:

- `App\Actions\Orders\CalculateOrderTotals`

After that, create/store flow should move into:

- `App\Actions\Orders\CreateOrder`
