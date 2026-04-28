# Reza Laravel Inventory

`Reza Laravel Inventory` is a Laravel-based inventory and sales management application built for day-to-day business operations. It brings product management, purchasing, order handling, quotations, customer and supplier records, and role-based access control into one system.

This repository started from an open-source inventory codebase and has been heavily customized and upgraded for practical company use. The current work also includes an ongoing professionalization pass focused on cleaner architecture, safer stock workflows, production readiness, and long-term maintainability.

## Overview

The system is designed to help teams manage the full inventory lifecycle in one place:

- products are created, categorized, and tracked
- purchases bring stock into inventory
- sales orders move stock out of inventory
- quotations support pre-sale workflows without changing stock
- customers and suppliers stay linked to operational records
- users only see and manage the data relevant to their role

At its core, this project is about making stock movement, sales operations, and access control easier to manage in a real business environment.

## Key Capabilities

- Product management with category, sub-category, unit, pricing, image upload, and barcode generation
- Purchase management with pending and approved flows
- Sales order workflow with stock validation and invoice generation
- Quotation workflow separated from stock mutation
- Customer and supplier management
- Due payment tracking for outstanding customer balances
- Dashboard summaries for operational visibility
- Role and permission management with Spatie Laravel Permission
- Category-level access control for non-admin users
- Product import/export and purchase report export
- Health check endpoint for uptime and environment monitoring

## Standout Business Logic

### 1. Category-Based Access Control

One of the most important customizations in this project is category-aware access filtering.

For non-admin users:

- visible categories are filtered by the user's assigned role
- products are filtered through category ownership
- orders and purchases are scoped to the allowed product category path

This makes the application more practical for teams where employees are responsible for different inventory groups.

### 2. Inventory Lifecycle Rules

The main stock rules are intentionally separated by workflow:

- creating a purchase records intended stock intake
- approving a purchase actually increases stock
- creating an order records a sale transaction
- completing or approving an order decreases stock
- quotations do not change stock

This separation keeps inventory movement tied to business approval steps instead of draft entry screens.

### 3. Safer Operational Flows

Recent upgrades in the codebase focus on:

- transactional purchase creation
- approval-time stock synchronization
- report/export cleanup
- health monitoring and deployment hardening
- broader automated regression coverage

## Main Modules

- Dashboard
- Products
- Categories, sub-categories, and units
- Customers
- Suppliers
- Purchases
- Orders
- Quotations
- Due management
- Roles and permissions
- Profile and authentication

## Tech Stack

### Backend

- PHP `^8.2`
- Laravel `^12.0`
- MySQL
- Laravel Breeze
- Laravel Sanctum
- Livewire 3
- Spatie Laravel Permission

### Frontend

- Blade
- Livewire
- Vite
- Tailwind CSS
- Tabler assets
- Livewire PowerGrid

### Supporting Packages

- `picqer/php-barcode-generator` for barcode rendering
- `phpoffice/phpspreadsheet` for export workflows
- `anayarojo/shoppingcart` for legacy cart-style order handling
- `haruncpi/laravel-id-generator` for generated identifiers

## How the System Works

### Product Flow

Products can be created with pricing, stock quantity, tax settings, category mapping, and optional image upload. Product detail pages also generate a barcode from the product code.

### Purchase Flow

Purchases start as pending records with line items saved in `purchase_details`. Stock is increased only when a purchase is approved.

### Order Flow

Orders capture customer sales information and line items. During completion or approval, the system re-checks stock and then decrements product quantity.

### Quotation Flow

Quotations are managed separately from completed sales and do not mutate stock, which keeps pre-sales activity distinct from inventory movement.

### Due Collection Flow

Orders with outstanding balances can be tracked and updated through the due module, which adjusts paid and remaining due amounts.

## Architecture Notes

The codebase is organized around standard Laravel application layers:

- `app/Models` for domain entities and relationships
- `app/Http/Controllers` for feature controllers
- `app/Http/Requests` for validation
- `app/Actions` for extracted business workflows such as order and purchase operations
- `app/Livewire` for interactive UI forms and data tables
- `resources/views` for Blade templates
- `routes` for web, auth, console, and API routes
- `tests` for feature, unit, and Livewire regression coverage
- `docs` for project analysis, architecture notes, deployment guides, and upgrade plans

## API and Operational Endpoints

### Health Check

The app exposes a public-safe health endpoint:

```text
GET /health
```

It checks:

- application boot
- database connectivity
- cache read/write behavior
- writable runtime storage directories

### Product API

The app currently exposes a simple product listing endpoint:

```text
GET /api/products
```

It can return all products or filter by `category_id`.

## Local Development Setup

### Requirements

- PHP 8.2+
- Composer
- Node.js and npm
- MySQL

### Installation

1. Clone the repository.
2. Install backend dependencies:

```bash
composer install
```

3. Install frontend dependencies:

```bash
npm install
```

4. Create the environment file:

```bash
cp .env.example .env
```

If you are on Windows PowerShell, you can use:

```powershell
Copy-Item .env.example .env
```

5. Generate the application key:

```bash
php artisan key:generate
```

6. Configure your database credentials in `.env`.
7. Run migrations and seeders:

```bash
php artisan migrate:fresh --seed
```

8. Create the storage symlink:

```bash
php artisan storage:link
```

9. Start the frontend dev server:

```bash
npm run dev
```

10. Start the Laravel server:

```bash
php artisan serve
```

## Default Seed Data

The seeders create development-oriented application data such as roles, permissions, users, categories, units, and sample products. Treat any seeded credentials or records as local-only development data and replace them before using the app in a shared or production-like environment.

## Common Commands

Run the test suite:

```bash
php artisan test
```

Run PHPUnit directly:

```bash
vendor\bin\phpunit
```

Run code style formatting:

```bash
vendor\bin\pint
```

Build frontend assets for production:

```bash
npm run build
```

## Testing Coverage Snapshot

The repository includes tests around:

- authentication flows
- products, categories, customers, suppliers, and units
- order and purchase workflows
- API product responses
- health check behavior
- Livewire table rendering and interaction
- route-level sanity checks

This test coverage is still being expanded as part of the ongoing professionalization roadmap.

## Security and Production Notes

- Do not deploy with development defaults.
- Set `APP_ENV=production` and `APP_DEBUG=false` in live environments.
- Keep `.env` and all secrets out of version control.
- Set `SESSION_SECURE_COOKIE=true` when serving the app over HTTPS.
- Configure a real mail provider for production use.
- Use a proper cache, queue, and session strategy before public launch.
- Restrict `CORS_ALLOWED_ORIGINS` to trusted domains if browser clients access the API cross-origin.
- Do not rotate `APP_KEY` on an existing live environment without a planned key-rotation process.

## Deployment Notes

The current deployment documentation is cPanel-oriented and includes hardening guidance, rollout steps, and production checks.

Useful references:

- [docs/cpanel-hosting-guide.md](docs/cpanel-hosting-guide.md)
- [docs/cpanel-deployment-runbook.md](docs/cpanel-deployment-runbook.md)
- [docs/production-readiness-audit-plan.md](docs/production-readiness-audit-plan.md)
- [docs/production-config-hardening-note.md](docs/production-config-hardening-note.md)
- [docs/monitoring-and-failure-visibility-note.md](docs/monitoring-and-failure-visibility-note.md)
- [docs/laravel-upgrade-readiness-audit.md](docs/laravel-upgrade-readiness-audit.md)
- [docs/laravel-upgrade-execution-note.md](docs/laravel-upgrade-execution-note.md)

## Documentation Map

For deeper project context, see:

- [docs/professional-upgrade-roadmap.md](docs/professional-upgrade-roadmap.md) for the phased upgrade plan
- [docs/project-analysis.md](docs/project-analysis.md) for a broad codebase walkthrough
- [docs/database-schema.md](docs/database-schema.md) for the data model
- [docs/feature-flow-diagram.md](docs/feature-flow-diagram.md) for operational workflows
- [docs/final-professionalization-review-note.md](docs/final-professionalization-review-note.md) for end-state review context

## Current Status

This project is actively maintained and incrementally upgraded. Recent work has focused on:

- controller slimming and action extraction
- safer purchase and order stock handling
- production configuration hardening
- monitoring and health visibility
- report/export cleanup
- expanding regression tests

## License

This project is distributed under the [MIT License](LICENSE).
