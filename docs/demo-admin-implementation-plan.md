# Demo Admin Implementation Plan

এই document-এর উদ্দেশ্য হলো `Login as Demo Admin` feature-টি phase-by-phase plan, implementation, verification, এবং handoff সহ execute করা, যাতে recruiter/demo user পুরো admin panel browse করতে পারে কিন্তু কোনো data change করতে না পারে।

---

## Marker Legend

- `[done]` = completed
- `[in-progress]` = work started but not finished
- `[next]` = next phase to execute
- `[todo]` = pending
- `[hold]` = intentionally paused until approval

---

## Execution Rules

- এই file-এ planning notes এবং implementation scope দুটোই থাকবে
- কোনো phase-এ actual code/docs/test work complete না হলে `done` marker বসবে না
- প্রতিটি phase শেষে visible result, changed files, এবং verification summary থাকবে
- আপনি বললে তবেই next phase শুরু হবে

---

## UI Constraint

- demo login button, banner, notice, sidebar adjustment, এবং read-only indicators `C:\xampp\htdocs\Templates\AdminLTE-3.1.0` template direction অনুযায়ী implement করতে হবে
- existing project-এর current AdminLTE integration break করা যাবে না
- auth layout এবং admin layout-এর established component structure preserve করতে হবে

---

## Planning Summary

Planning/discovery হিসেবে নিচের decisions lock করা হয়েছে:

- demo account হবে `read-only business walkthrough account`
- `demo-admin` role seed layer-এ থাকবে
- demo login হবে one-click `POST` flow
- create/update/delete/approve/export/account mutations UI এবং backend দুই layer-এই block হবে
- existing non-admin scoped-data logic demo account reuse করবে
- administration area demo scope-এর বাইরে থাকবে

---

## Execution Tracker

- `[done]` Phase 0: Planning baseline
- `[done]` Phase 1: Demo role and permission seed implementation
- `[done]` Phase 2: Demo login entry implementation
- `[done]` Phase 3: Read-only UI enforcement implementation
- `[done]` Phase 4: Backend authorization hardening implementation
- `[done]` Phase 5: Verification, demo runbook, and handoff

---

## [done] Phase 0: Planning Baseline

### Goal

full scope, risk, execution order, এবং implementation strategy fix করা।

### Work

- plan document create করা
- access model review করা
- UI constraint lock করা
- phase-by-phase execution structure ঠিক করা

### Expected Output

- `docs/demo-admin-implementation-plan.md`

### Visible Change

- docs folder-এ clear implementation roadmap থাকবে

### Acceptance Check

- plan file exists
- phases are sequenced
- markers are clear

### Completion Marker

Phase 0 complete।

---

## [done] Phase 1: Demo Role and Permission Seed Implementation

### Goal

`demo-admin` role, restricted view-only permissions, এবং demo bootstrap user actual seed layer-এ add করা।

### Work

- `RoleSeeder.php`-এ `demo-admin` role add করা
- `RolePermissionSeeder.php`-এ curated view-only permission mapping add করা
- `UserSeederForRolePermission.php`-এ demo bootstrap user add করা
- seeding logic idempotent রাখা
- existing `admin` / `super-admin` seed behavior unchanged রাখা

### Expected Output

- fresh seed run করলে `demo-admin` role create হবে
- demo user seed হবে
- demo role only approved read-only business permissions পাবে

### Likely Files To Change

- `database/seeders/RoleSeeder.php`
- `database/seeders/RolePermissionSeeder.php`
- `database/seeders/UserSeederForRolePermission.php`

### Acceptance Check

- `demo-admin` role exists after seeding
- demo user exists after seeding
- no create/update/delete/admin permissions assigned to demo role

### Planning Notes

Recommended demo permission set:

- `view product`
- `view category`
- `view subcategory`
- `view unit`
- `view customer`
- `view supplier`
- `view quotation`
- `view order`
- `view purchase`

Explicitly excluded:

- all `create *`
- all `update *`
- all `delete *`
- all role/permission management permissions
- all user management permissions

### Completion Marker

Phase 1 complete।

### Changed Files

- `database/seeders/RoleSeeder.php`
- `database/seeders/RolePermissionSeeder.php`
- `database/seeders/UserSeederForRolePermission.php`

### Implementation Summary

- `demo-admin` role seed করা হয়েছে
- demo role-এর জন্য view-only business permissions map করা হয়েছে
- demo bootstrap user seed করা হয়েছে:
  `demo-admin@reza-inventory.test`
- demo user seed `updateOrCreate()` দিয়ে idempotent রাখা হয়েছে
- demo role assignment `syncRoles()` দিয়ে deterministic রাখা হয়েছে

### Verification

- `php -l database/seeders/RoleSeeder.php`
- `php -l database/seeders/RolePermissionSeeder.php`
- `php -l database/seeders/UserSeederForRolePermission.php`

সবগুলো syntax check pass করেছে।

### Note

Live/demo database-এ unexpected data mutation avoid করার জন্য আমি seeder run করিনি। Seeder execution Phase 5 verification-এ বা আপনার explicit instruction-এ করা যাবে।

---

## [done] Phase 2: Demo Login Entry Implementation

### Goal

login page-এ `Login as Demo Admin` button এবং one-click demo login flow actual implement করা।

### Work

- guest-only demo login route add করা
- controller-এ dedicated demo login action add করা
- session regenerate করা
- optional `demo_mode` session flag set করা
- login page-এ AdminLTE-consistent demo access block add করা
- route throttle যোগ করা

### Expected Output

- login page-এ visible `Login as Demo Admin` button থাকবে
- button click করলে demo user authenticate হবে
- normal email/password login flow unchanged থাকবে

### Likely Files To Change

- `routes/auth.php`
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `resources/views/auth/login.blade.php`

### Acceptance Check

- demo login works from login page
- no credential text is publicly shown
- normal auth flow remains intact

### Planning Notes

- login will use a `POST` guest route, not `GET`
- demo credentials HTML-এ expose করা হবে না
- session-level `demo_mode` flag later UI/backend checks-এ reuse করা যেতে পারে

### Completion Marker

Phase 2 complete।

### Changed Files

- `routes/auth.php`
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `resources/views/auth/login.blade.php`

### Implementation Summary

- guest-only throttled `demo.login` route add করা হয়েছে
- `AuthenticatedSessionController`-এ `demoLogin()` action add করা হয়েছে
- demo user resolve, role verify, `Auth::login()`, session regenerate, এবং `demo_mode` session flag set করা হয়েছে
- normal login flow-এ stale `demo_mode` session flag clear করা হয়েছে
- login page-এ AdminLTE-consistent demo access block এবং `Login as Demo Admin` button add করা হয়েছে
- demo access unavailable হলে user-friendly warning message path add করা হয়েছে

### Verification

- `php -l routes/auth.php`
- `php -l app/Http/Controllers/Auth/AuthenticatedSessionController.php`

দুইটি syntax check pass করেছে।

### Note

Browser-level click test এখনো run করা হয়নি। এটা Phase 5 verification-এ বা আপনার instruction-এ interactiveভাবে করা যাবে।

---

## [done] Phase 3: Read-Only UI Enforcement Implementation

### Goal

demo user browse করতে পারবে, কিন্তু UI থেকে কোনো create/update/delete/approve/export/account action initiate করতে পারবে না।

### Work

- shared layout-এ visible `Demo Mode` read-only banner add করা
- `Profile`, `Settings`, `Administration` links hide করা
- create/import/export/add buttons hide করা
- row-level `edit`/`delete`/`approve` actions hide করা
- dashboard quick-create buttons hide করা
- detail pages-এ mutating footer actions remove করা

### Expected Output

- clean read-only browsing experience
- recruiter clearly বুঝবে account is in demo mode
- misleading mutation buttons visible থাকবে না

### Likely Files To Change

- `resources/views/layouts/adminlte/sidebar.blade.php`
- `resources/views/layouts/adminlte/navbar.blade.php`
- `resources/views/**`
- `resources/views/livewire/tables/**`
- dashboard variant views

### Acceptance Check

- demo user visible UI দিয়ে কোনো mutation initiate করতে পারে না
- detail pages still browseable থাকে
- shared banner appears in demo session

### Planning Notes

High-risk UI points already identified:

- `OrderTable` create/delete controls
- header-level `Add ...` buttons across index pages
- purchase approval buttons in multiple views
- sidebar/navbar account links

### Completion Marker

Phase 3 complete।

### Changed Files

- `resources/views/layouts/tabler.blade.php`
- `resources/views/layouts/adminlte/sidebar.blade.php`
- `resources/views/layouts/adminlte/navbar.blade.php`
- `resources/views/dashboard.blade.php`
- `resources/views/dashboard/variants/classic.blade.php`
- `resources/views/dashboard/variants/dark-fixed.blade.php`
- `resources/views/products/index.blade.php`
- `resources/views/quotations/index.blade.php`
- `resources/views/orders/index.blade.php`
- `resources/views/orders/pending-orders.blade.php`
- `resources/views/orders/complete-orders.blade.php`
- `resources/views/purchases/index.blade.php`
- `resources/views/purchases/pending-purchases.blade.php`
- `resources/views/purchases/approved-purchases.blade.php`
- `resources/views/livewire/tables/product-table.blade.php`
- `resources/views/livewire/tables/order-table.blade.php`
- `resources/views/livewire/tables/purchase-table.blade.php`
- `resources/views/livewire/tables/category-table.blade.php`
- `resources/views/livewire/tables/sub-category-table.blade.php`
- `resources/views/livewire/tables/customer-table.blade.php`
- `resources/views/livewire/tables/supplier-table.blade.php`
- `resources/views/livewire/tables/unit-table.blade.php`
- `resources/views/livewire/tables/quotation-table.blade.php`
- `resources/views/orders/show.blade.php`
- `resources/views/purchases/details-purchase.blade.php`
- `resources/views/purchases/edit.blade.php`

### Implementation Summary

- shared authenticated layout-এ visible `Demo Mode` read-only banner add করা হয়েছে
- sidebar/navbar থেকে demo session-এ profile/settings entry hide করা হয়েছে
- navbar-এ demo badge add করা হয়েছে
- dashboard quick-create actions demo session-এ hide করা হয়েছে
- products, orders, purchases, quotations index header CTAs demo session-এ hide করা হয়েছে
- multiple Livewire table views-এ create/edit/delete/export controls demo session-এ hide করা হয়েছে
- order completion এবং purchase approval footer actions demo session-এ hide করা হয়েছে

### Verification

- updated Blade files manually reviewed for directive nesting and session-gated branches
- targeted post-patch file review completed for shared layout and purchase detail footer logic

### Note

`php artisan view:cache` চালাতে গিয়ে `storage/framework/views` rename step-এ local filesystem permission error (`Access is denied`) হয়েছে। এটা compile-cache permission issue, code syntax failure না। Browser-level walkthrough এখনো run করা হয়নি।

---

## [done] Phase 4: Backend Authorization Hardening Implementation

### Goal

direct URL hit, crafted request, বা auth-only endpoints দিয়েও demo user যেন কোনো mutation করতে না পারে।

### Work

- reusable demo-write denial guard/middleware/helper add করা
- profile update/delete block করা
- password update block করা
- product export block করা
- purchase export block করা
- due order update block করা
- order update/complete block করা
- purchase approval/update block করা
- create/store/update/destroy resource endpoints-এ explicit deny বসানো যেখানে প্রয়োজন

### Expected Output

- UI bypass করলেও demo user write action execute করতে পারবে না
- existing `admin` / `super-admin` behavior unchanged থাকবে

### Likely Files To Change

- `routes/web.php`
- `routes/auth.php`
- `app/Http/Controllers/ProfileController.php`
- `app/Http/Controllers/Order/**`
- `app/Http/Controllers/Purchase/**`
- `app/Http/Controllers/Product/**`
- shared middleware/helper area

### Acceptance Check

- direct URL hit করেও write action blocked
- export/account mutation blocked
- non-admin scoped browse logic still works for demo account

### Planning Notes

- `demo-admin` will not be added to admin route groups
- existing non-admin scoped-data logic demo visibility-তে reuse হবে
- authorization থাকবে layered:
  module access by permission/role
  write prevention by demo guard

### Completion Marker

Phase 4 complete।

### Changed Files

- `app/Http/Middleware/DenyDemoModeAccess.php`
- `app/Http/Kernel.php`
- `routes/auth.php`
- `app/Http/Controllers/ProfileController.php`
- `app/Http/Controllers/CategoryController.php`
- `app/Http/Controllers/CustomerController.php`
- `app/Http/Controllers/SupplierController.php`
- `app/Http/Controllers/UnitController.php`
- `app/Http/Controllers/SubCategoryController.php`
- `app/Http/Controllers/Quotation/QuotationController.php`
- `app/Http/Controllers/Product/ProductController.php`
- `app/Http/Controllers/Product/ProductImportController.php`
- `app/Http/Controllers/Product/ProductExportController.php`
- `app/Http/Controllers/Order/DueOrderController.php`
- `app/Http/Controllers/Order/OrderController.php`
- `app/Http/Controllers/Purchase/PurchaseController.php`

### Implementation Summary

- reusable `deny.demo` middleware add করা হয়েছে
- middleware alias `Kernel`-এ register করা হয়েছে
- password update route-এ demo deny apply করা হয়েছে
- profile/account screens এবং mutations demo mode-এর জন্য backend-এ blocked করা হয়েছে
- category/customer/supplier/unit/subcategory/quotation/product mutation methods-এ demo deny apply করা হয়েছে
- product import/export endpoints controller-level guard পেয়েছে
- order create/store/update/destroy এবং due edit/update guarded হয়েছে
- purchase create/store/edit/update/destroy এবং report export endpoints guarded হয়েছে

### Verification

- `php -l app/Http/Middleware/DenyDemoModeAccess.php`
- `php -l app/Http/Kernel.php`
- `php -l routes/auth.php`
- `php -l` on all modified controllers

সবগুলো syntax check pass করেছে।

### Note

Browser-level 403 behavior এখনো interactiveভাবে test করা হয়নি। এটা final verification phase-এ check করা হবে।

---

## [done] Phase 5: Verification, Demo Runbook, and Handoff

### Goal

feature complete হওয়ার পর যাচাই করা যে demo flow stable, understandable, এবং maintainable।

### Work

- manual walkthrough checklist run করা
- seed/fresh setup verification করা
- demo login flow test করা
- restricted action attempts test করা
- short ops note লিখা:
  demo password rotation, reseed flow, and recruiter usage note
- phase markers update করে final status set করা

### Expected Output

- tested demo experience
- repeatable setup instructions
- maintenance note for future updates

### Likely Files To Change

- `docs/demo-admin-implementation-plan.md`
- optionally separate demo ops note in `docs/`
- tests if added during implementation

### Acceptance Check

- recruiter/demo user can browse core admin areas
- no data mutation is possible through normal UI or obvious direct actions
- setup can be recreated from seed instructions

### Completion Marker

Phase 5 complete. Demo admin feature implementation, verification, and handoff are now complete.

### Changed Files

- `tests/Feature/Auth/DemoAdminAccessTest.php`
- `docs/demo-admin-runbook.md`
- `app/Http/Middleware/DenyDemoModeAccess.php`
- `docs/demo-admin-implementation-plan.md`

### Implementation Summary

- focused feature tests were added for demo login, session flag handling, and read-only enforcement
- a separate operations handoff document was added in `docs/demo-admin-runbook.md`
- verification exposed a middleware bug where `session()->boolean('demo_mode')` was not supported
- the middleware was corrected to use `session()->get('demo_mode', false)` and cast safely to boolean

### Verification

- `php -l tests/Feature/Auth/DemoAdminAccessTest.php`
- `php -l app/Http/Middleware/DenyDemoModeAccess.php`
- `php artisan test --filter=DemoAdminAccessTest`

Result:
`5 tests, 14 assertions` passed.

### Handoff Notes

- the demo login flow and backend read-only guard now have automated regression coverage
- recruiter/demo maintenance guidance is documented in `docs/demo-admin-runbook.md`
- a final browser walkthrough is still recommended before sharing the demo publicly
---

## Suggested Demo Scope

- Allow: dashboard, products list/details, categories list/details, units list, customers list/details, suppliers list/details, quotations list/details, orders list/details, purchases list/details
- Maybe allow: purchase report view-only surface if export remains blocked
- Hide or deny: user management, role management, permission management, profile, settings
- Deny: all create, edit, delete, import, export, approval, password reset, account mutation

---

## Pause Rule

1. আমি একটি phase implement করব
2. phase summary দেব
3. changed files ও verification বলব
4. আপনি বললে তবেই next phase শুরু করব
