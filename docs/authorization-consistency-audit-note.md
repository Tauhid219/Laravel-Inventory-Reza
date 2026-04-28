# Authorization Consistency Audit Note

This note captures the Phase 16 authorization consistency audit.

## Findings

- several modules relied only on `auth` or scattered role checks
- customer, supplier, unit, and quotation modules did not have consistent permission middleware
- some sensitive actions were guarded in routes or views by role instead of permission
- invoice download was not explicitly protected by `view order`
- sidebar and table actions assumed visibility without checking permissions

## Fixes Applied

- added missing permission names for customer, supplier, unit, and quotation modules
- added controller middleware for customer, supplier, unit, quotation, product import, and product export flows
- protected invoice download with `view order`
- replaced selected `@role(...)` approval UI checks with `@can(...)`
- made key sidebar and table actions permission-aware

## Policy Candidates Identified

- `OrderPolicy@complete`
- `PurchasePolicy@approve`
- `OrderPolicy@delete`
- `PurchasePolicy@delete`

## Outcome

- sensitive operations are now protected more explicitly
- permission behavior is more consistent across routes, controllers, and views
- future policy-based hardening now has a clearer foundation
