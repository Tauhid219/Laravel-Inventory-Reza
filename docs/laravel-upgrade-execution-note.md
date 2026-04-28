# Phase 29: Laravel Upgrade Execution

## Status

Completed on April 22, 2026.

The application has been upgraded from Laravel `10.48.29` to Laravel `12.56.0` with PHP constrained to `^8.2`.

## Dependency Changes

Updated:

- `laravel/framework` to `^12.0`
- `laravel/sanctum` to `^4.0`
- `laravel/breeze` to `^2.0`
- `phpunit/phpunit` to `^11.0`
- `power-components/livewire-powergrid` to `^6.9`
- `phpoffice/phpspreadsheet` to `^3.0`

Removed:

- `beyondcode/laravel-query-detector`
- `kyslik/column-sortable`
- `nunomaduro/collision`

## App Changes

- Updated Sanctum middleware references for Sanctum 4.
- Removed the unused Kyslik Column Sortable provider and config.
- Added a small local `php artisan test` command so the established test workflow still runs PHPUnit after Collision was removed.
- Adjusted one purchase missing-product test setup to work reliably with the upgraded SQLite testing stack.

## Verification

Passing checks:

```bash
php artisan --version
php artisan config:clear
php artisan route:list --path=health
php artisan test --filter=HealthCheckTest
php artisan test
npm.cmd run build
```

Latest full test result:

```text
Tests: 102, Assertions: 368
```

PHPUnit 11 currently reports 4 test runner deprecation notices. They do not fail the suite, but should be reviewed during the final professionalization pass.

The notices come from PHPUnit metadata in doc-comments in:

- `tests/Feature/Livewire/Tables/CustomerTableTest.php`
- `tests/Feature/Livewire/Tables/OrderTableTest.php`
- `tests/Feature/Livewire/Tables/ProductTableTest.php`
- `tests/Feature/Livewire/Tables/QuotationTableTest.php`

## Follow-Up

- Evaluate Laravel 13 after confirming PHP `8.3+` availability and package compatibility.
- Review PHPUnit deprecation notices in Phase 30.
- Smoke-test browser workflows manually before production deployment.
