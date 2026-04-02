## Execution Tracker

এই section আমাদের active working checklist।

- প্রতিটি phase ছোট scope-এ ভাগ করা হয়েছে
- status tag: `Done`, `Partial`, `Pending`
- একটি phase শেষ হলে আমি update দেব
- আপনি বললে তবেই পরের phase শুরু হবে

### Phase 1: Asset Audit and Mapping [`Pending`]

- AdminLTE template assets, plugins, images, icons map করা
- DataTables, charts, select widgets, modal, datepicker usage inventory করা
- Tabler-specific UI dependencies identify করা

### Phase 2: Public Asset Integration [`Done`]

- `public/adminlte/dist` and `public/adminlte/plugins` present
- layout থেকে `asset('adminlte/...')` path use হচ্ছে

### Phase 3: Authenticated Base Layout Shell [`Done`]

- authenticated shell এখন AdminLTE-based
- navbar, sidebar, footer include হচ্ছে
- stack support এবং Livewire hooks আছে

### Phase 4: Theme Variant Configuration [`Done`]

- `classic`, `dark-fixed`, `compact` theme config আছে
- per-theme body/navbar/sidebar classes কাজ করছে

### Phase 5: Theme Switching and Persistence [`Done`]

- `theme.switch` route আছে
- selected theme session-এ persist হচ্ছে

### Phase 6: Core Layout Partials Cleanup [`Done`]

- navbar, sidebar, footer partial আছে
- dedicated `head`, `flash`, `breadcrumbs`, `control-sidebar` partial add করা হয়েছে
- master layout এখন এই partial structure use করছে

### Phase 7: Sidebar Navigation Refactor [`Done`]

- Dashboard, Sales, Purchases, Inventory, Parties, Administration, Account grouping আছে
- role-aware visibility এবং active state logic আছে

### Phase 8: Navbar UX and User Menu [`Done`]

- theme dropdown, fullscreen, profile/logout, user menu implemented

### Phase 9: Dashboard Data Preservation [`Done`]

- existing metrics/theme-based resolution preserve করা হয়েছে

### Phase 10: Dashboard Variant Views [`Done`]

- `classic`, `dark-fixed`, `compact` dashboard variant view আছে

### Phase 11: Shared AdminLTE UI Patterns [`Done`]

- existing Blade components reuse হচ্ছে
- shared `page-header` and `page-body` pattern add করা হয়েছে
- existing `x-card` shared card wrapper হিসেবে retained হয়েছে
- layout-level opt-in flash rendering path add করা হয়েছে
- later module phases-এ এই shared patterns adopt করা হবে

### Phase 12: Dashboard Final Polish [`Done`]

- dashboard header/body wrapper parent view-এ centralize করা হয়েছে
- variant views এখন শুধু theme-specific dashboard content বহন করছে
- shared page pattern-এর সঙ্গে dashboard align করা হয়েছে

### Phase 13: Products Module Migration [`Done`]

- products index/create/edit/show/import pages shared AdminLTE page patterns-এ migrate করা হয়েছে
- legacy page scaffolding reduce করা হয়েছে
- subcategory loader script reusable partial-এ নেওয়া হয়েছে

### Phase 14: Categories, Sub Categories, Units Migration [`Done`]

- categories, sub-categories, and units index/create/edit/show pages migrated to shared AdminLTE page patterns
- empty states, page headers, action buttons, and module detail pages aligned with the products module

### Phase 15: Customers and Suppliers Migration [`Done`]

- customers and suppliers index/create/edit/show pages migrated to shared AdminLTE page patterns
- headers, breadcrumbs, empty states, forms, and detail cards aligned with the products/categories/units modules

### Phase 16: Purchases Migration [`Done`]

- purchase list, create, approval, detail, pending, approved, and report pages migrated to shared AdminLTE page patterns
- headers, alerts, action buttons, report forms, and purchase detail cards aligned with the shared module structure

### Phase 17: Orders and Orders V2 Migration [`Done`]

- orders and ordersV2 index/create/show/pending/completed pages migrated to shared AdminLTE page patterns
- print invoice view refreshed with improved printable metadata, totals, and note presentation while preserving print/download behavior

### Phase 18: Quotations, Due, Users, Roles and Permissions [`Done`]

- quotations index/create/show/edit, due index/show/edit, users index/create/edit/show, and role-permission pages migrated to shared AdminLTE page patterns
- quotation controller now supports in-use `show`, `edit`, and `update` flows with cart rehydration so quotation table actions no longer dead-end

### Phase 19: Profile and Settings Migration [`Done`]

- profile edit and settings pages migrated to shared AdminLTE page patterns
- settings page now exposes real password update and account deletion actions instead of placeholder controls

### Phase 20: Auth Pages Restyle [`Done`]

- login, register, forgot password, reset password, verify email, and confirm password screens restyled into a unified AdminLTE auth experience
- auth layout now uses a branded two-panel shell while preserving existing routes, validation, and guest/auth flow behavior

### Phase 21: Error Pages Restyle [`Done`]
- `401`, `402`, `403`, `404`, `419`, `429`, `500`, and `503` error pages now use a shared branded AdminLTE-style error shell
- legacy minimal error presentation replaced while preserving status semantics and per-page error messages

### Phase 22: JS Behavior and Plugin Wiring [`Done`]
- shared AdminLTE custom JS now re-applies Bootstrap 5 compatibility attributes after Livewire morphs
- shared AdminLTE refresh logic now handles both morphed root nodes and their descendants, so Bootstrap compatibility aliases and Tom Select init re-apply reliably after Livewire updates
- local Tom Select assets now load through project-owned assets, and the authenticated layout now provides the Tom Select runtime needed by shared selects and future PowerGrid filters
- compatibility CSS expanded for `form-select`, `dropdown-menu-end`, and shared gap utility support
- PowerGrid dist assets are now available under `public/vendor/livewire-powergrid` and included from the authenticated layout
- reusable `x-tom-select` preselected value handling is fixed, preserving old input and existing selected customer/supplier values on edit/retry flows
- redundant CDN JS usage reduced on JS-sensitive pages so local AdminLTE/runtime assets stay authoritative
- code-side verification completed for push menu/treeview triggers, due/invoice modal triggers, Tom Select wiring on purchase/order/due pages, and Livewire re-init compatibility
- no currently routed PowerGrid-specific screen was found during this pass, but shared assets/runtime wiring are in place for activation later

### Phase 23: QA and Regression Pass [`Done`]

- automated regression coverage now runs against an in-memory SQLite test database instead of the stale `inventory_management_system` MySQL test reference
- route, auth, profile, order, purchase, product, unit, supplier, and Livewire table tests are now passing after aligning test auth/permissions and current validation payload requirements
- supplier detail view no longer crashes when supplier type is unset; it now renders a safe fallback label
- supplier delete regression was confirmed as a stale permission expectation in the test suite; supplier deletion is now covered for both the allowed super-admin path and the related-purchases protection path
- a focused interactive regression pack now covers dashboard theme switching persistence, sidebar pushmenu/treeview markup, Tom Select-backed order/purchase/due forms, and due/invoice modal trigger rendering
- code-side QA rechecked AdminLTE runtime wiring for sidebar pushmenu/treeview, theme dropdown compatibility, Tom Select-backed order/purchase selects, and due/invoice modal triggers
- `php artisan test` and `php artisan view:cache` are green after the latest fixes, with the full automated suite now at `82` passing tests
- final browser/manual verification was completed after the latest UI fixes, including table-header alignment and dark-theme detail-field readability checks on interactive screens

### Phase 24: Cleanup and Documentation [`Done`]

- removed unused legacy `resources/views/layouts/body/*` partials that no longer participate in the authenticated AdminLTE shell
- documented that [resources/views/layouts/tabler.blade.php](c:\xampp\htdocs\Laravel-Practice\Inventory-Experiment\resources\views\layouts\tabler.blade.php) is now a compatibility wrapper around the AdminLTE layout stack, avoiding a noisy mass view rename
- cleaned stale tracker bullets that still contradicted already completed migration phases
- PowerGrid theme naming is now aligned on an `Adminlte` primary class while retaining `App\View\PowerGridThemes\Tabler` as a backward-compatible alias
- added a dedicated final browser-pass checklist in [docs/adminlte-manual-qa-checklist.md](c:\xampp\htdocs\Laravel-Practice\Inventory-Experiment\docs\adminlte-manual-qa-checklist.md)
- broader rename cleanup for `layouts.tabler` references is still intentionally deferred because that identifier remains a compatibility alias across many migrated views
- final post-QA documentation closeout is complete, and the remaining compatibility aliases are now documented as intentional low-risk carryovers

## Working Sequence

এখন থেকে recommended order:

1. Phase 6
2. Phase 11
3. Phase 12
4. Phase 13
5. Phase 14
6. Phase 15
7. Phase 16
8. Phase 17
9. Phase 18
10. Phase 19
11. Phase 20
12. Phase 21
13. Phase 22
14. Phase 23
15. Phase 24

## Pause Rule

1. আমি একটি phase শেষ করব
2. phase summary দেব
3. আপনি বললে তবেই next phase শুরু করব

# AdminLTE Blade Migration Plan

## Objective

`templates/AdminLTE-3.1.0` টেমপ্লেটকে ভিত্তি করে বর্তমান Laravel inventory project-এর UI layer-কে `Blade templating`-এ পুনর্গঠন করা হবে, যাতে:

- পুরো project একটি unified `AdminLTE` design system ব্যবহার করে
- existing Laravel routes, controllers, permissions, Livewire pieces এবং forms অক্ষুণ্ণ থাকে
- sidebar-এর মাধ্যমে `Dashboard v1`, `Dashboard v2`, `Dashboard v3`-ধরনের তিনটি theme/layout variant ব্যবহার করা যায়
- future page development reusable partials/components-এর মাধ্যমে দ্রুত করা যায়

## Current Project Snapshot

বর্তমানে project-এ:

- মূল app layout হচ্ছে [resources/views/layouts/tabler.blade.php](c:\xampp\htdocs\Laravel-Practice\Inventory-Experiment\resources\views\layouts\tabler.blade.php)
- top header, main navigation, footer আলাদা include-এ ভাঙা আছে
- view layer-এ inventory modules already organised:
  - dashboard
  - products
  - purchases
  - orders / ordersV2
  - suppliers
  - customers
  - categories / sub-categories / units
  - users / role-permission
  - profile / auth
- route structure already sizeable, তাই redesign-এর সময় backend logic না বদলে presentation layer refactor করাই safest approach

## Design Targets

নতুন design implementation-এ নিচের UX goals থাকবে:

1. `AdminLTE`-এর sidebar-first app shell
2. reusable navbar + sidebar + content-wrapper + footer structure
3. inventory-friendly navigation grouping
4. responsive mobile collapse behavior
5. role-aware menu visibility
6. page-level title, breadcrumb, action buttons, alerts, tables, forms-এর standardised pattern
7. তিনটি visual/dashboard mode:
   - Variant A: `Dashboard v1` inspired classic light analytics layout
   - Variant B: `Dashboard v2` inspired dark/fixed layout
   - Variant C: `Dashboard v3` inspired simplified compact layout

Note:
এই তিনটি variant original template-এর exact copy হিসেবে না এনে project-friendly naming-এ আনা হবে, তবে capability একই থাকবে: user sidebar থেকে তিন ধরনের dashboard/theme experience বেছে নিতে পারবে।

## Implementation Strategy

কাজটি একবারে সব view overwrite করে করা হবে না। বরং `layout foundation -> shared partials -> dashboard variants -> module migration -> cleanup` এই phased strategy নেওয়া হবে।

এতে সুবিধা:

- breakage isolate করা সহজ হবে
- existing route names unchanged রাখা যাবে
- partial rollout possible হবে
- future maintenance সহজ হবে

## Phase Breakdown

### Phase 1: Asset and Template Audit

উদ্দেশ্য:
AdminLTE template-এর কোন asset, plugin, CSS, JS, image, icon এবং layout fragment real project-এ লাগবে, তা চিহ্নিত করা।

Tasks:

- `templates/AdminLTE-3.1.0/index.html`
- `templates/AdminLTE-3.1.0/index2.html`
- `templates/AdminLTE-3.1.0/index3.html`
- related plugin dependencies review
- inventory project-এ কোন pages DataTables, charts, select widgets, modal, datepicker ব্যবহার করে তা map করা
- existing Tabler-specific classes/components inventory করা

Deliverables:

- asset copy/use list
- plugin dependency list
- current-to-target layout mapping note

### Phase 2: Public Asset Integration

উদ্দেশ্য:
AdminLTE static assets Laravel app-এ serve করার জন্য prepare করা।

Tasks:

- প্রয়োজনীয় `dist`, `plugins`, `img` assets `public/`-এ project-friendly location-এ place করা
- asset path strategy define করা
  - example: `public/adminlte/...`
- Blade `asset()` helper compatible path standard ঠিক করা
- optional unused assets বাদ দেওয়ার plan রাখা

Decision:

- full template dump public root-এ না রেখে namespaced folder prefer করা হবে
- এতে existing project assets-এর সাথে collision কমবে

### Phase 3: Base Blade Layout Architecture

উদ্দেশ্য:
বর্তমান `Tabler` layout replace করে AdminLTE-compatible master layout তৈরি করা।

Proposed structure:

- `resources/views/layouts/adminlte.blade.php`
- `resources/views/layouts/adminlte/partials/head.blade.php`
- `resources/views/layouts/adminlte/partials/navbar.blade.php`
- `resources/views/layouts/adminlte/partials/sidebar.blade.php`
- `resources/views/layouts/adminlte/partials/footer.blade.php`
- `resources/views/layouts/adminlte/partials/control-sidebar.blade.php`
- `resources/views/layouts/adminlte/partials/flash.blade.php`
- `resources/views/layouts/adminlte/partials/breadcrumbs.blade.php`

Layout responsibilities:

- global CSS/JS load
- body class variant support
- authenticated shell rendering
- page title, subtitle, breadcrumb, actions, content section yield
- stack support:
  - `@stack('styles')`
  - `@stack('scripts')`
  - `@stack('modals')`

### Phase 4: Theme Variant System

উদ্দেশ্য:
AdminLTE-এর `Dashboard v1`, `v2`, `v3` inspired তিনটি visual mode reusableভাবে implement করা।

Approach:

- variant config key define করা
- per-variant `body class`, navbar skin, sidebar skin, footer behavior, fixed layout behavior নিয়ন্ত্রণ করা
- variant selection sidebar menu থেকে accessible করা
- variant session-based persist করা

Suggested internal naming:

- `classic`
- `dark-fixed`
- `compact`

Possible mapping:

- `classic` -> `index.html`
- `dark-fixed` -> `index2.html`
- `compact` -> `index3.html`

Backend handling:

- dedicated route or controller action দিয়ে selected variant session-এ save করা
- layout master session/config থেকে active variant resolve করবে

UI handling:

- sidebar-এ একটি dedicated section থাকবে
- active theme visually highlighted হবে

### Phase 5: Navigation Refactor for Inventory Domain

উদ্দেশ্য:
AdminLTE sidebar structure-এ project modules domain অনুযায়ী regroup করা।

Proposed navigation groups:

- Dashboard
- Sales
  - Orders
  - Pending Orders
  - Completed Orders
  - Due Orders
- Purchases
  - All Purchases
  - Approved
  - Pending
  - Reports
- Inventory
  - Products
  - Categories
  - Sub Categories
  - Units
- Parties
  - Customers
  - Suppliers
- Administration
  - Users
  - Roles & Permissions
- Account
  - Profile
  - Settings
- Theme Modes
  - Classic Dashboard
  - Dark Fixed Dashboard
  - Compact Dashboard

Requirements:

- route-aware active state
- role-aware visibility using existing permission directives
- submenu open/close state based on current route

### Phase 6: Dashboard Rebuild

উদ্দেশ্য:
বর্তমান dashboard-কে AdminLTE card, info-box, small-box, chart area, activity widgets দিয়ে পুনর্গঠন করা।

Plan:

- existing metrics keep করা
  - products
  - categories
  - sub-categories
  - orders
  - completed orders
  - purchases
  - suppliers
  - customers
- three dashboard variants-এর জন্য separate content presentation তৈরি করা
- data source controller-level existing logic যতটা সম্ভব unchanged রাখা

Suggested Blade structure:

- `resources/views/dashboard/index.blade.php`
- `resources/views/dashboard/variants/classic.blade.php`
- `resources/views/dashboard/variants/dark-fixed.blade.php`
- `resources/views/dashboard/variants/compact.blade.php`

Result:

- same business data
- different visual presentation per dashboard mode

### Phase 7: Shared UI Patterns and Blade Partials

উদ্দেশ্য:
সব module page-এ consistent AdminLTE markup আনার জন্য common partial/component set তৈরি করা।

Candidate abstractions:

- page header block
- content card wrapper
- filter toolbar
- table wrapper
- status badge
- action button group
- form field wrapper
- validation error block
- empty state
- modal shell

Important:

বর্তমান custom Blade components পুরোপুরি remove না করে evaluate করা হবে। যেগুলো reuse করা যায় সেগুলো AdminLTE markup-এর সাথে adapt করা হবে।

### Phase 8: Module-by-Module View Migration

উদ্দেশ্য:
প্রত্যেক feature page নতুন layout-এ migrate করা।

Recommended migration order:

1. Dashboard
2. Products
3. Categories / Sub Categories / Units
4. Suppliers / Customers
5. Purchases
6. Orders V2
7. Legacy Orders / Due / Invoice views
8. Users / Role & Permission
9. Profile
10. Auth pages
11. Error pages

Per module checklist:

- extends new AdminLTE layout
- page header + breadcrumb
- alerts / flash messages
- tables become AdminLTE cards/tables
- form layouts updated
- buttons/icons standardised
- mobile spacing verified
- role-gated actions preserved

### Phase 9: Auth and Guest Layouts

উদ্দেশ্য:
login, forgot password, reset password, verify email pages-কে AdminLTE login/register page patterns-এ আনা।

Tasks:

- guest layout তৈরি
- auth pages restyle
- branding align করা
- validation/error messaging redesign

### Phase 10: JS Behavior and Plugin Wiring

উদ্দেশ্য:
AdminLTE JS widgets এবং existing page-specific JS-এর সংঘর্ষ এড়িয়ে functional behavior restore করা।

Tasks:

- sidebar treeview
- push menu
- dropdown compatibility
- DataTables pages
- charts
- date/time pickers
- select widgets
- Livewire re-render safe initialization

Special attention:

- Livewire components reload হলে JS plugin re-init strategy লাগতে পারে
- duplicate jQuery/bootstrap/plugin loading avoid করতে হবে

### Phase 11: QA and Regression Pass

উদ্দেশ্য:
redesign-এর পরে existing workflows যেন না ভাঙে তা verify করা।

Manual verification scope:

- authentication
- dashboard
- product CRUD
- category CRUD
- supplier/customer CRUD
- purchase flow
- order flow
- invoice view/print
- role-based navigation visibility
- profile update
- responsive sidebar and navbar
- theme variant switching persistence

### Phase 12: Cleanup and Documentation

উদ্দেশ্য:
পুরনো layout remnants সরিয়ে project maintainable রাখা।

Tasks:

- unused Tabler view fragments identify
- obsolete CSS/JS references remove
- final layout usage documentation add
- theme variant extension guide add

## File Impact Forecast

সবচেয়ে বেশি যেসব জায়গায় change আসবে:

- `resources/views/layouts/*`
- `resources/views/dashboard*`
- `resources/views/products/*`
- `resources/views/purchases/*`
- `resources/views/orders*/*`
- `resources/views/customers/*`
- `resources/views/suppliers/*`
- `resources/views/categories/*`
- `resources/views/sub_categories/*`
- `resources/views/units/*`
- `resources/views/users/*`
- `resources/views/profile/*`
- `resources/views/auth/*`
- `public/` এর ভিতরে AdminLTE asset folders
- optionally `routes/web.php` for theme switching route
- optionally small controller/helper changes for theme persistence

## Data and Logic Safety Rules

Implementation-এর সময় নিচের rule follow করা হবে:

- backend business logic change না করা, unless UI requirement force করে
- existing route names keep করা
- permissions directives preserve করা
- forms, validation, old input behavior preserve করা
- Livewire components functional রাখা
- existing session flash behavior preserve করা

## Risks and Mitigations

### Risk 1: Asset Collision

সমস্যা:
বর্তমান assets এবং AdminLTE assets overlap করলে CSS/JS conflict হতে পারে।

Mitigation:

- assets namespaced folder-এ রাখা
- duplicate libraries identify করা
- page-level inclusion control করা

### Risk 2: Livewire/JS Plugin Breakage

সমস্যা:
DOM replace হলে plugin bindings নষ্ট হতে পারে।

Mitigation:

- plugin init wrappers
- Livewire hook-based re-init যেখানে দরকার

### Risk 3: Route Active State Complexity

সমস্যা:
nested menu-তে current section highlight ঠিকমতো কাজ নাও করতে পারে।

Mitigation:

- central helper বা consistent request pattern checks

### Risk 4: Too Much Change at Once

সমস্যা:
এক ধাক্কায় সব page বদলালে regression track করা কঠিন হবে।

Mitigation:

- phased migration
- dashboard-first rollout
- module-by-module validation

## Recommended Execution Order

বাস্তব implementation আমি নিচের order-এ করব:

1. AdminLTE assets prepare
2. base layout + guest layout তৈরি
3. navbar/sidebar/footer partials
4. theme switching mechanism
5. dashboard variants build
6. shared page partials/components
7. high-traffic modules migrate
8. auth/error/profile pages migrate
9. full verification and cleanup

## Acceptance Criteria

কাজ complete ধরা হবে যখন:

- app-এর authenticated area সম্পূর্ণ AdminLTE layout-এ চলবে
- sidebar navigation inventory modules reflect করবে
- তিনটি dashboard/theme mode কাজ করবে
- selected theme persist করবে
- existing CRUD flows usable থাকবে
- auth pages redesign হবে
- responsive behavior acceptable হবে
- obvious UI regression থাকবে না

## Assumptions

- `templates/AdminLTE-3.1.0` local static source হিসেবে ব্যবহার করা যাবে
- project-এর মূল redesign scope server-rendered Blade UI পর্যন্ত সীমিত
- data model, controller flow, route structure broadly unchanged থাকবে
- user prefers keeping original business modules and route names intact

## Proposed Next Step After Approval

আপনি অনুমতি দিলে implementation শুরু হবে এই milestone দিয়ে:

1. AdminLTE assets public namespace-এ integrate করা
2. master Blade layout এবং guest layout তৈরি করা
3. sidebar/navbar/footer partials build করা
4. dashboard theme switching mechanism implement করা
5. dashboard page migrate করে প্রথম visible result দেখানো
