# Order Pricing Rules Note

This document defines the recommended pricing rules for the future canonical order module.

Its purpose is to remove ambiguity before refactoring the order flow.

## Why This Note Exists

The current system has two competing assumptions:

- legacy `orders` treats cart totals as authoritative
- `orders-v2` allows the final total to be typed manually

That second behavior exists for a real business reason, so the canonical module must support it intentionally instead of treating it as a workaround.

## Core Pricing Principle

The order system should always preserve two separate values:

1. calculated subtotal
2. final approved total

These two numbers are related, but they are not the same thing.

## Definitions

## Calculated subtotal

This is the sum of all saved line totals.

Formula:

- line total = quantity x unit cost
- calculated subtotal = sum of all line totals

This value is system-generated and should not be manually typed by the operator.

## Final approved total

This is the customer-facing amount the operator decides to save for the order.

By default:

- final approved total = calculated subtotal

But when business needs require it:

- final approved total may be manually overridden

## Manual Override Rules

## Rule 1: override is optional

If the operator does not enter a manual total, the system should save:

- final approved total = calculated subtotal

## Rule 2: override is explicit

If the operator changes the total manually, the system should treat that as an intentional override, not as a recalculation bug.

## Rule 3: subtotal is never lost

Even when final total is overridden, the original calculated subtotal must still be preserved.

This is important for:

- auditability
- future reporting
- troubleshooting
- discount or negotiation analysis

## Rule 4: overridden total may be lower or higher

Recommended professional rule:

- final approved total may be lower than subtotal
- final approved total may be higher than subtotal

Reasons:

- negotiated deal
- bundled sale
- manual adjustment
- delivery or service charge folded into final number
- operator correction

The system should support this, but it must make the difference visible.

## Rule 5: zero or negative totals are invalid

Recommended validation:

- final approved total must be greater than `0`

## Rule 6: item lines still matter

Manual total override must not remove the need for proper line items.

The system should still require:

- at least one valid item row
- valid quantity
- valid unit cost

## Payment Rules

## Rule 7: payment type stays explicit

The canonical order form should support:

- `cash`
- `cheque`
- `due`

These already exist in the domain and should not be lost during the merge.

## Rule 8: paid amount is entered separately

The operator should explicitly enter the paid amount.

Recommended validation:

- `pay >= 0`
- `pay <= final approved total`

## Rule 9: due amount is derived, not typed

The system should calculate:

- `due = final approved total - pay`

The operator should not manually type `due`.

## Rule 10: due orders are first-class behavior

If `pay < final approved total`, the order becomes a due-bearing order automatically.

That behavior should continue to work with the existing due payment module.

## Rule 11: payment type guidance

Recommended behavior:

- if `payment_type = due`, `pay` may be `0` or any partial amount
- if `payment_type = cash`, partial payment may still be allowed if the business wants due tracking
- if `payment_type = cheque`, partial payment should be policy-driven, but technically supported unless you want stricter rules

For now, the safest professional approach is:

- do not over-restrict payment type behavior in code
- let `final total`, `pay`, and `due` be the authoritative financial fields

## Display Rules

The order create and show pages should clearly display:

- calculated subtotal
- final approved total
- difference amount, when overridden
- paid amount
- due amount

## Override Visibility Rule

If final approved total differs from subtotal, the UI should show:

- that the total was manually adjusted
- the amount of adjustment

Example:

- Calculated subtotal: `12,000`
- Final approved total: `10,500`
- Adjustment: `-1,500`

This makes operator intent visible and reduces confusion.

## Data Model Recommendation

For the canonical implementation, the order model should conceptually preserve:

- calculated subtotal
- final total
- pay
- due

Current table already has:

- `sub_total`
- `total`
- `pay`
- `due`

Recommended mapping:

- `sub_total` = system-calculated subtotal
- `total` = final approved total
- `pay` = actual paid amount
- `due` = derived outstanding amount

## Taxes and Extra Charges

Current state is inconsistent:

- legacy flow still references VAT/cart totals
- `orders-v2` effectively bypasses tax behavior

Recommended near-term rule for canonical merge:

- do not reintroduce complex tax logic during the first merge step
- first stabilize subtotal vs final total behavior
- keep tax behavior simple and explicit

If tax returns later, it should be added as a deliberate second-stage enhancement.

## Validation Rules Summary

Recommended canonical validation:

- `customer_id` is required
- at least one saved line item is required
- each line must have product, quantity, and unit cost
- calculated subtotal must be greater than `0`
- final approved total must be numeric and greater than `0`
- paid amount must be numeric and greater than or equal to `0`
- paid amount must not exceed final approved total
- due amount is derived by the server

## Server-Side Authority Rules

The server should never trust the browser blindly.

Recommended behavior:

1. Recalculate subtotal from submitted line items on the server
2. Accept a submitted final total only after validating it
3. Derive due from final total and paid amount on the server
4. Persist financial values only after that normalization

## Audit Recommendation

The canonical flow should ideally preserve whether an override happened.

Best long-term option:

- add an explicit field like `total_override_amount` or `is_total_overridden`

But for the near-term refactor, even without schema change, the system can infer override when:

- `total != sub_total`

## Assumptions Used In This Note

These are the assumptions I am making for now based on the current codebase and your explanation:

1. manual total override is a valid business requirement, not an exception
2. item rows should remain visible even when final total changes
3. due tracking must continue to work
4. payment type should remain part of the order workflow
5. the business does not currently require strict tax automation during the first merge

If any of these assumptions are wrong, this note should be adjusted before implementation.

## Recommended Decision

Recommended decision for the canonical order module:

- keep line-item subtotal system-calculated
- allow manual final total override
- preserve `sub_total` and `total` as separate meanings
- restore explicit payment type and paid amount fields
- derive `due` on the server
- show override difference clearly in the UI

## Implementation Consequence

Because of these rules, the future canonical order create flow should collect:

- customer
- date
- line items
- optional note
- optional manual final total
- payment type
- paid amount

And the server should persist:

- `sub_total`
- `total`
- `payment_type`
- `pay`
- `due`
- `note`
