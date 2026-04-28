# Professional Upgrade Roadmap

এই document-এর উদ্দেশ্য হলো `Reza-Laravel-Inventory` project-টাকে step by step আরও professional, maintainable, এবং production-ready level-এ নেওয়া।

এই roadmap execution-friendly করে বানানো হয়েছে:

- phase-গুলো ছোট ছোট
- প্রতিটি phase-এর clear output আছে
- প্রতিটি phase শেষ হলে visible change বোঝা যাবে
- আমরা একবারে একটাই phase execute করব
- প্রতিটি phase শেষ হলে summary report থাকবে
- আপনার explicit approval ছাড়া next phase শুরু হবে না

---

## Planning Principles

- rewrite না করে existing codebase improve করা
- feature add করার আগে risky area stabilize করা
- duplicate flow কমিয়ে canonical flow তৈরি করা
- business-critical workflow আগে ঠিক করা
- প্রতিটি step-এর পরে testable বা reviewable output রাখা

---

## Current Reality Summary

এই project-এ already good feature coverage আছে:

- products
- purchases
- quotations
- customers
- suppliers
- roles and permissions
- Livewire-based tables and forms

তবে current state-এ কয়েকটা important professional gap আছে:

1. legacy `orders` flow এবং duplicate `orders-v2` flow একসাথে ছিল, but active code is now canonical `orders`
2. cart-based order logic business need অনুযায়ী flexible ছিল না; canonical order actions now cover the current workflow
3. কিছু controller অতিরিক্ত heavy ছিল, and the main order/purchase/product paths are now slimmer
4. production hardening এখনো incomplete
5. test discipline was uneven, but the full suite is now green after the order retirement work
6. docs and repo polish আরও improve করা দরকার, especially older historical `orders-v2` notes

সবচেয়ে important architectural problem ছিল:

`orders-v2` আসলে temporary workaround, final solution না। Active code has now moved to this long-term target:

- একটি single canonical order module
- manual override capable pricing flow
- clear stock update rules
- duplicate route, view, and controller removal

---

## Workspace Setup

### AdminLTE Template Integration

আমাদের workspace-এ একটি AdminLTE template setup করা আছে:

- **Workspace File**: `c:\xampp\htdocs\My Works\Reza-Laravel-Inventory-AdminLTE-Workspace.code-workspace`
- **Template Path**: `C:\xampp\htdocs\Templates\AdminLTE-3.1.0`

সামনের UI কাজগুলো AdminLTE-3.1.0 design system অনুযায়ী করা হবে, যাতে layout এবং styling consistent থাকে।

---

## North Star Goal

Target end-state:

- একটাই professional order system
- clean service-oriented business logic
- safer purchase and order stock handling
- better test coverage on critical workflows
- cleaner docs and deployment discipline
- production-friendly configuration and monitoring

---

## Execution Rules

Execution-এর সময় এই rules follow করা হবে:

1. এক phase-এ এক set of related changes
2. phase complete না হওয়া পর্যন্ত next phase শুরু হবে না
3. phase শেষে summary, changed files, এবং verification report থাকবে
4. আপনার explicit approval পেলে next phase শুরু হবে
5. hidden complexity ধরা পড়লে phase-টাকে mini-step-এ break down করা যাবে

---

## Priority Order

Execution priority order:

1. immediate safety and hygiene
2. order architecture unification
3. purchase and stock consistency
4. testing confidence
5. UX and workflow polish
6. production hardening
7. framework modernization

---

## Phase Plan

### Marker Legend

- `[done]` = completed
- `[in-progress]` = work started but not yet closed
- `[next]` = next phase queued
- `[todo]` = pending

## [done] Phase 1: Roadmap Baseline and Repo Hygiene

### Goal

upgrade work-এর জন্য clean planning baseline তৈরি করা।

### Work

- roadmap document finalize
- repo and docs inconsistency list করা
- existing architectural risks short note আকারে capture করা
- execution tracking convention ঠিক করা

### Expected Output

- `docs/professional-upgrade-roadmap.md`
- phase execution style fixed
- improvement priorities documented

### Visible Change

- docs folder-এ clear upgrade roadmap দেখা যাবে

### Acceptance Check

- roadmap file committed-ready অবস্থায় থাকবে
- next phases follow করার জন্য enough detail থাকবে

---

## [done] Phase 2: Public Debug and Unsafe Dev Exposure Cleanup

### Goal

production-facing risky বা embarrassing dev leftovers remove করা।

### Work

- public `phpinfo()` route remove বা protect করা
- temporary `test/` route review করা
- obvious debug leftovers remove করা
- `dd()` এবং similar hard-stop debug code remove বা ticketize করা

### Expected Output

- public routes cleaner হবে
- accidental server-info leak বন্ধ হবে
- report/export flow-এ debug blockers কমবে

### Visible Change

- `routes/web.php` safer হবে
- production-facing debug traps কমে যাবে

### Acceptance Check

- code search-এ production-facing debug leftovers significantly কমে যাবে

---

## [done] Phase 3: README and Documentation Professional Polish

### Goal

repo impression এবং onboarding quality improve করা।

### Work

- `README.md` clean rewrite
- broken encoding fix
- setup steps correct করা
- local, staging, production notes separate করা
- default credentials wording safer করা

### Expected Output

- clean onboarding document
- new developer বা client repo দেখে more trust পাবে

### Visible Change

- `README.md` readable, modern, and project-specific হবে

### Acceptance Check

- a new developer README পড়ে project run করতে পারবে

---

## [done] Phase 4: Order Domain Discovery and Canonical Design Note

### Goal

legacy order flow vs `orders-v2` flow-এর exact difference document করা, যাতে implementation guesswork-based না হয়।

### Work

- old cart-based order flow map করা
- `orders-v2` flow map করা
- manual price override requirement formalize করা
- stock deduction timing clarify করা
- final canonical order behavior নিয়ে design note তৈরি করা

### Expected Output

- order module design decision note
- duplicate flow merge করার safe plan

### Visible Change

- docs-এ clear order architecture decision থাকবে

### Acceptance Check

- এই phase শেষে আমরা জানব কোন flow keep করব, কোনটা retire করব

---

## [done] Phase 5: Manual Price Override Rules Finalization

### Goal

core business pricing requirement formalize করা।

### Work

- order form calculation rule define
- manual total override allowed scenarios define
- audit and display behavior define
- validation rules define
- due, pay, and discount interaction define

### Expected Output

- pricing rules note
- implementation checklist for order refactor

### Visible Change

- order pricing logic আর implicit থাকবে না; documented হবে

### Acceptance Check

- future implementation ambiguity থাকবে না

### Notes

এই phase গুরুত্বপূর্ণ, কারণ `orders-v2` তৈরির root cause এখানেই ছিল।

---

## [done] Phase 6: Order Module Refactor Skeleton

### Goal

single canonical order module-এর foundation তৈরি করা।

### Work

- order-related controllers inventory করা
- shared logic extract points identify করা
- service বা action classes introduce করার structure তৈরি করা
- route naming cleanup plan করা

### Expected Output

- new order service/action skeleton
- refactor-ready folder structure

### Visible Change

- codebase-এ order logic centralization-এর শুরু দেখা যাবে

### Acceptance Check

- নতুন logic রাখার clean place থাকবে

---

## [done] Phase 7: Shared Order Calculation Service

### Goal

calculation logic UI এবং controller থেকে বের করা।

### Work

- subtotal calculation service
- manual total override support
- line item normalization
- total product count calculation
- validation-friendly calculation result object

### Expected Output

- reusable calculation classes

### Visible Change

- controller code ছোট হবে
- calculation logic centralized হবে

### Acceptance Check

- একই calculation logic old এবং new flow দুটোতেই প্রয়োগ technically possible হবে

---

## [done] Phase 8: Canonical Order Store Flow

### Goal

একটি clean order creation path তৈরি করা।

### Work

- create/store action refactor
- transaction-safe order save
- line items save standardize
- order note এবং manual total field consistent handling

### Expected Output

- one primary order persistence flow

### Visible Change

- order create code cleaner হবে
- duplicate save logic কমবে

### Acceptance Check

- canonical flow দিয়ে order create successfully হবে

---

## [done] Phase 9: Order Approval and Stock Update Hardening

### Goal

order approval-এর সময় stock mutation safe করা।

### Work

- approval transaction wrap
- stock availability re-check
- duplicate completion prevention
- race-condition conscious update logic improve

### Expected Output

- safer order completion behavior

### Visible Change

- approval logic cleaner এবং more explicit হবে

### Acceptance Check

- same order multiple times complete হওয়ার risk কমবে

---

## [done] Phase 10: Legacy Order Flow Retirement

### Goal

duplicate order system remove করা।

### Work

- legacy hidden routes, views, and controllers identify
- keep-or-remove list finalize
- deprecated code remove বা archive
- UI menu এবং routes canonical flow-এ point করা

### Expected Output

- single order module experience

### Visible Change

- duplicate order entry points কমে যাবে
- codebase less confusing হবে

### Acceptance Check

- developer হিসেবে আর `orders` vs `orders-v2` confusion থাকবে না

### Current Status

- active controller, request, Livewire component, views, and routes now use canonical `Order` naming
- active `OrderV2`, `ordersV2`, `orders-v2`, and `order-v2` references are retired from app/resources/routes/database
- one regression test intentionally asserts the legacy `ordersV2.index` route name is gone
- historical docs still mention `orders-v2` and should be synchronized in a later docs pass

---

## [done] Phase 11: Purchase Store Flow Hardening

### Goal

purchase creation flow professional করা।

### Work

- request validation tighten
- transaction wrap
- line item insertion standardize
- mass assignment review

### Expected Output

- safer purchase create flow

### Visible Change

- purchase controller less fragile হবে

### Acceptance Check

- invalid purchase payload partial save করবে না

---

## [done] Phase 12: Purchase Approval and Stock Sync Hardening

### Goal

purchase approval-এ stock increase rules safe করা।

### Work

- approval idempotency review
- transaction-safe stock increment
- repeated approval guard
- approval audit fields clean handling

### Expected Output

- stock sync on approval more reliable হবে

### Visible Change

- repeated approval bug-এর risk কমবে

### Acceptance Check

- purchase approve flow deterministic হবে

---

## [done] Phase 13: Reporting and Export Cleanup

### Goal

reporting and export features professional quality-তে আনা।

### Work

- export flow-এর `dd()` remove
- query column mismatch fix
- date filter behavior verify
- output naming and formatting review

### Expected Output

- purchase report/export usable হবে

### Visible Change

- report feature actually complete লাগবে

### Acceptance Check

- report page থেকে export successful হবে

---

## [done] Phase 14: Product Image and File Handling Cleanup

### Goal

uploads and file handling reusable এবং safer করা।

### Work

- product image upload logic extract
- file delete/update behavior standardize
- storage path naming clean করা
- validation and fallback behavior review

### Expected Output

- image handling reusable pattern

### Visible Change

- product controller slimmer হবে

### Acceptance Check

- create, update, delete-এ file handling predictable হবে

---

## [done] Phase 15: Controller Slimming Pass

### Goal

heavy controllers reduce করা।

### Work

- `ProductController`
- `PurchaseController`
- `OrderController`
- `OrderV2Controller`

এই controllers-এ business logic extraction করা।

### Expected Output

- cleaner controllers
- more maintainable service/action boundaries

### Visible Change

- controllers visibly ছোট হবে

### Acceptance Check

- controllers mostly HTTP orchestration layer হিসেবে থাকবে

### Current Status

- `ProductController`, `PurchaseController`, and `OrderV2Controller` now delegate more business logic into actions
- purchase export generation and order stock precheck are extracted from controllers
- legacy `OrderController` runtime dependency is removed and invoice download now stays on the canonical order controller

---

## [done] Phase 16: Authorization Consistency Audit

### Goal

authorization behavior consistent করা।

### Work

- middleware coverage audit
- sensitive actions-এর policy candidate identify
- delete, approve, update actions review
- view-level permission assumptions যাচাই

### Expected Output

- permission audit notes
- missing protection fix list

### Visible Change

- sensitive routes and actions better protected হবে

### Acceptance Check

- high-impact operations explicit protection-এর মধ্যে থাকবে

---

## [done] Phase 17: Route Naming and Module Structure Cleanup

### Goal

route design আরও clean এবং predictable করা।

### Work

- naming consistency improve (e.g., `*.delete` $\rightarrow$ `*.destroy`)
- mixed REST and custom route patterns review
- deprecated paths identify and remove (e.g., `ordersV2.*` aliases)
- module grouping better করা using `Route::controller()`

### Expected Output

- cleaner route file
- standardized route naming convention

### Visible Change

- route definitions more professional লাগবে
- `web.php` file more organized

### Acceptance Check

- new developer routes পড়ে module structure বুঝতে পারবে
- all route names follow Laravel standard

---

## [done] Phase 18: Test Isolation Repair

### Goal

test suite-এ confidence বাড়ানো।

### Work

- `RefreshDatabase` issues fix
- flaky test source identify
- data leakage reduce
- basic test conventions document

### Expected Output

- more reliable test runs

### Visible Change

- tests more deterministic হবে

### Acceptance Check

- critical feature tests repeat run-এ stable থাকবে

### Current Status

- latest full suite verification: `php artisan test`
- result after the order retirement and migration fix: 99 tests passed, 324 assertions

---

## [done] Phase 19: Order Workflow Tests

### Goal

canonical order system protect করা।

### Work

- order create test
- manual total override test
- pending to complete transition test
- stock update test
- insufficient stock test

### Expected Output

- high-value order coverage

### Visible Change

- test suite-এ order module confidence বাড়বে

### Acceptance Check

- future order refactor safer হবে

---

## [done] Phase 20: Purchase Workflow Tests

### Goal

purchase module reliably protect করা।

### Work

- purchase create test
- purchase details persistence test
- purchase approval stock increment test
- duplicate approval prevention test

### Expected Output

- purchase regression protection

### Visible Change

- purchase changes করার সময় confidence বাড়বে

### Acceptance Check

- purchase flow-এর main bugs test-এ ধরা পড়বে

---

## [done] Phase 21: Pricing, Money, and Data Integrity Review

### Goal

money-related logic safer করা।

### Work

- integer money handling consistency review
- tax, discount, and override interaction audit
- due and pay field integrity review
- migration, index, and constraint opportunities note করা

### Expected Output

- money logic integrity checklist

### Visible Change

- code comments, docs, এবং tests-এ clearer financial behavior দেখা যাবে

### Acceptance Check

- money fields-এর behavior ambiguous থাকবে না

### Current Status

- duplicate `orders.order_date` index creation was removed from the performance index migration
- money assertions now follow the current cents-based persistence model
- a deeper model/accessor review can still happen later if the money model changes

---

## [done] Phase 22: Dashboard and Query Efficiency Pass

### Goal

main screens আরও efficient করা।

### Work

- dashboard query review
- common listing eager loading check
- unnecessary `get()` on large pages review
- indexes candidate list

### Expected Output

- performance improvement notes and selected fixes

### Visible Change

- heavy screens smoother হবে

### Acceptance Check

- obvious N+1 বা wasteful query patterns কমবে

### Current Status

- treated as mostly complete for this upgrade pass
- still worth doing browser/manual performance checks on larger datasets before production

---

## [done] Phase 23: UX and Operator Workflow Polish

### Goal

daily-use experience improve করা।

### Work

- order form clarity
- manual override UI messaging
- form validation feedback
- empty states
- success and error messaging consistency

### Expected Output

- cleaner operator experience

### Visible Change

- app ব্যবহার করতে আরও polished লাগবে

### Acceptance Check

- user confusion কমবে, especially order creation flow-এ

### Current Status

- treated as mostly complete for current AdminLTE/order workflow polish
- automated render/regression checks exist, but a manual operator walkthrough is still useful

---

## [done] Phase 24: API Surface Professionalization

### Goal

public and internal API behavior cleaner করা।

### Work

- API route structure review
- API controller response shape review
- resource class candidate identify
- versioning consistency improve

### Expected Output

- cleaner API design baseline

### Visible Change

- API layer more intentional লাগবে

### Acceptance Check

- raw model dumping style response avoid করা যাবে

### Current Status

- `GET api/products/` still uses the stable route path and route name
- product API now returns `ProductResource` paginated collections instead of raw Eloquent models
- `category_id` filtering is preserved and validated
- `per_page` pagination is supported with a safe max of 100
- feature tests cover response shape, filtering, pagination, and invalid category validation

---

## [done] Phase 25: Production Config Hardening

### Goal

deployment posture stronger করা।

### Work

- production env checklist refine
- queue, cache, and session strategy document
- mail strategy note
- scheduler and workers checklist

### Expected Output

- ops-ready config guidance

### Visible Change

- production deployment docs clearer হবে

### Acceptance Check

- staging and production setup less guessy হবে

### Current Status

- `.env.example` now includes production reminders and configurable session/CORS knobs
- database-backed operational tables exist for queue, session, and cache fallbacks on cPanel/single-server hosting
- README and cPanel guide now document first-time-only `APP_KEY` generation, HTTPS session cookies, CORS origin restrictions, queue workers, scheduler cron, and mail strategy
- detailed phase note added at `docs/production-config-hardening-note.md`
- Redis remains the preferred queue/cache/session backend, while database fallback is documented for constrained hosting

---

## [done] Phase 26: Monitoring and Failure Visibility

### Goal

silent failure কমানো।

### Work

- logging review
- error monitoring integration plan
- critical failure points identify
- alert-worthy events list

### Expected Output

- observability checklist

### Visible Change

- production issue detectability improve হবে

### Acceptance Check

- important failures unnoticed থেকে যাওয়ার risk কমবে

### Current Status

- public-safe `GET /health` endpoint added for uptime monitoring
- health check covers application boot, database connectivity, cache read/write, and writable runtime storage directories
- dependency check failures return HTTP `503` without exposing exception details
- monitoring checklist and alert-worthy events documented in `docs/monitoring-and-failure-visibility-note.md`
- README and cPanel guide now reference the health endpoint
- feature test coverage added for the healthy endpoint contract

---

## [done] Phase 27: cPanel Deployment Flow Polish

### Goal

current hosting model-কে safer করা।

### Work

- `docs/cpanel-hosting-guide.md` refine as needed
- deployment steps simplify
- rollback notes add
- asset and build discipline clarify

### Expected Output

- better hosting guide

### Visible Change

- deployment process more repeatable হবে

### Acceptance Check

- deployment-এর সময় fewer surprises হবে

### Current Status

- concise deployment runbook added at `docs/cpanel-deployment-runbook.md`
- cPanel guide now points operators to the short runbook while keeping historical setup notes
- deploy steps clarify local asset build, tracked `public/build`, server pull, composer install, safe migration, optimize, smoke checks, queue/scheduler checks, and rollback limits
- live-server dangerous commands are explicitly listed
- README deployment links include the new runbook

---

## [done] Phase 28: Laravel Upgrade Readiness Audit

### Goal

framework modernization-এর preparation করা।

### Work

- Laravel 10 to 11 blockers list
- package compatibility check
- custom code compatibility review
- upgrade sequence plan

### Expected Output

- upgrade readiness note

### Visible Change

- framework upgrade আর vague থাকবে না

### Acceptance Check

- upgrade work estimate করা সম্ভব হবে

### Current Status

- Laravel upgrade readiness audit added at `docs/laravel-upgrade-readiness-audit.md`
- current baseline confirmed: Laravel `10.48.29`, PHP CLI `8.2.12`, full suite green before the audit
- official support status checked: Laravel 10 is EOL, Laravel 11 security support ended on March 12, 2026, Laravel 12 is still security-supported until February 24, 2027, and Laravel 13 requires PHP `8.3+`
- Laravel 11 blockers identified: PHP constraint, Sanctum 3, Breeze 1, Collision 7, and Query Detector compatibility
- Laravel 12 blockers identified: PHPUnit 10, Livewire/PowerGrid compatibility, Debugbar, Shoppingcart, Column Sortable, and Query Detector package decisions
- recommendation: use Laravel 11 only as an intermediate step, target Laravel 12 as the minimum supported landing version, and evaluate Laravel 13 after PHP/package readiness

---

## [done] Phase 29: Laravel Upgrade Execution

### Goal

framework baseline modernize করা।

### Work

- Laravel 11 first
- tests and smoke verification
- Laravel 12 as the minimum supported landing target if dependency resolution is clean
- Laravel 13 evaluated after PHP `8.3+` and package compatibility are confirmed

### Expected Output

- upgraded framework baseline, preferably Laravel 12 rather than stopping on Laravel 11

### Visible Change

- composer and app bootstrap modernized হবে

### Acceptance Check

- app tests and key flows after upgrade pass করবে

---

### Current Status

- Laravel upgraded from `10.48.29` to `12.56.0`
- Composer PHP constraint raised from `^8.1` to `^8.2`
- Sanctum upgraded to `^4.0`, Breeze to `^2.0`, PHPUnit to `^11.0`, PowerGrid to `^6.9`, and PhpSpreadsheet to `^3.0`
- removed incompatible/unused upgrade blockers: `beyondcode/laravel-query-detector`, `kyslik/column-sortable`, and `nunomaduro/collision`
- Sanctum middleware config updated for Sanctum 4
- unused Column Sortable provider/config removed
- local `php artisan test` command added to preserve the existing test workflow after removing Collision
- verification passed: `php artisan test` returned `102 tests`, `368 assertions`; `npm.cmd run build` completed successfully
- Phase 30 follow-up completed: PHPUnit 11 doc-comment metadata deprecation notices in Livewire table tests were replaced with attributes

---

## [complete] Phase 30: Final Professionalization Review

### Goal

সব major improvements integrated হয়েছে কি না validate করা।

### Work

- final architecture review
- doc sync pass
- remaining rough edges list
- next-quarter ideas backlog
- PHPUnit 11 doc-comment metadata cleanup in Livewire table tests
- final `orders-v2` and removed-package deployment consistency audit

### Expected Output

- closing review note: `docs/final-professionalization-review-note.md`
- post-upgrade backlog

### Visible Change

- PHPUnit 11 deprecation noise removed from the Livewire table tests
- current docs no longer present retired `orders-v2` or `kyslik/column-sortable` production instructions as active guidance
- project clearly more professional feel দেবে

### Acceptance Check

- `OrderV2`/`ordersV2` active references remain retired, with only the intentional regression assertion left in tests
- `php artisan test` and `npm.cmd run build` stay green
- codebase, docs, workflows, and deployment posture aligned থাকবে

---

## Recommended Starting Sequence

সব phase eventually useful হলেও execution-এর জন্য এই sequence recommend করা হচ্ছে:

1. Phase 1
2. Phase 2
3. Phase 3
4. Phase 4
5. Phase 5
6. Phase 6
7. Phase 7
8. Phase 8
9. Phase 9
10. Phase 10
11. Phase 11
12. Phase 12
13. Phase 13
14. Phase 14
15. Phase 15
16. Phase 18
17. Phase 19
18. Phase 20
19. Phase 23
20. Phase 24
21. Phase 25
22. Phase 28
23. Phase 29
24. Phase 30

---

## What Counts as Success

এই roadmap successful বলা যাবে যদি:

- `orders-v2` temporary workaround status থেকে বের হয়
- single canonical order flow তৈরি হয়
- manual total override requirement cleanly supported হয়
- critical stock and approval flows safer হয়
- tests critical workflows protect করে
- production deploy and maintenance less risky হয়

---

## Phase Execution Template

প্রতিটি phase শেষে report এই format-এ দেওয়া হবে:

### Phase Complete

- phase name
- what changed
- files touched
- what is now visibly better
- what was verified
- any caveat

তারপর pause থাকবে।

আপনি যদি বলেন:

- `পরের phase শুরু করো`

তাহলে next phase-এ যাওয়া হবে।

---

## Immediate Next Step

বর্তমান status:

- Phase 1 through Phase 14 complete
- Phase 15 through Phase 30 complete
- roadmap markers and status synchronized with the canonical order module, production-readiness passes, Laravel 12 upgrade, and final professionalization review

Immediate focus:

- Maintain the upgraded Laravel 12 baseline and use the backlog in `docs/final-professionalization-review-note.md` for future polish.
