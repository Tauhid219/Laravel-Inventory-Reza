# Phase 28: Laravel Upgrade Readiness Audit

## Status

Completed as a readiness audit. No framework upgrade was executed in this phase.

Phase 29 later executed the upgrade and landed the app on Laravel `12.56.0`. See `docs/laravel-upgrade-execution-note.md` for the execution result.

The current application is on Laravel `10.48.29`. As of April 22, 2026, Laravel 10 is end-of-life, Laravel 11 security fixes ended on March 12, 2026, Laravel 12 receives security fixes until February 24, 2027, and Laravel 13 is the current major release with security fixes until March 17, 2028.

Sources:

- Laravel 11 upgrade guide: https://laravel.com/docs/11.x/upgrade
- Laravel 12 upgrade guide: https://laravel.com/docs/12.x/upgrade
- Laravel 13 release notes / support policy: https://laravel.com/docs/13.x/releases

## Current Baseline

Runtime:

- Local PHP CLI: `8.2.12`
- Composer PHP constraint: `^8.1`
- Laravel framework: `10.48.29`
- Tests: `102 passed`, `368 assertions` before the audit phase

Important direct packages:

| Package | Current | Upgrade note |
| --- | ---: | --- |
| `laravel/framework` | `10.48.29` | Must move to `^11.0`, then `^12.0`; Laravel 13 requires PHP `8.3+`. |
| `laravel/sanctum` | `3.3.3` | Blocks Laravel 11; official Laravel 11 guide requires Sanctum `^4.0`. |
| `laravel/breeze` | `1.29.1` | Locked to Laravel 10 components; official Laravel 11 guide recommends Breeze `^2.0`. |
| `nunomaduro/collision` | `7.10.0` | Explicitly conflicts with Laravel `>=11`; update to `^8.1` for Laravel 11. |
| `livewire/livewire` | `3.5.9` | Current lock supports Laravel 10/11, not 12. Update before Laravel 12. |
| `power-components/livewire-powergrid` | `5.10.7` | Depends on Livewire `^3.4.6`; Laravel 12 compatibility should be checked with package update. |
| `spatie/laravel-permission` | `6.19.0` | Current lock supports Laravel 10/11/12. |
| `laravolt/avatar` | `6.2.0` | Current lock supports Laravel 10/11/12 but requires PHP `>=8.2`. |
| `barryvdh/laravel-debugbar` | `3.14.3` | Current lock supports Laravel 9/10/11, not 12. Update or remove before Laravel 12. |
| `beyondcode/laravel-query-detector` | `1.8.0` | Current lock supports up to Laravel 10. Update/remove before Laravel 11. |
| `anayarojo/shoppingcart` | `4.2.3` | Current lock supports up to Laravel 11, not 12. Needs replacement/update decision before Laravel 12. |
| `kyslik/column-sortable` | `6.6.0` | Current lock supports up to Laravel 11, not 12. Needs replacement/update decision before Laravel 12. |
| `phpunit/phpunit` | `10.5.36` | Laravel 12 guide requires PHPUnit `^11.0`. |

## Local Code Compatibility Scan

Likely low-risk:

- Existing Laravel 10 application structure can remain during Laravel 11 upgrade. The official Laravel 11 guide does not require upgraded apps to migrate to the new slim Laravel 11 structure.
- No custom `BatchRepository`, custom database grammar, `HasUuids`, `HasVersion7Uuids`, `Concurrency::run`, `Schema::getTables`, `Schema::getTableListing`, `mergeIfMissing`, or table-prefix grammar usage was found in active `app`, `config`, `database`, `routes`, or `tests`.
- Existing `config/filesystems.php` explicitly defines the `local` disk, reducing risk from Laravel 12's local disk default-root change.

Needs attention:

- `config/sanctum.php` still references old app middleware classes. Laravel 11 / Sanctum 4 expects updated middleware class references.
- Product/user/customer/supplier image validation uses the `image` rule. Laravel 12 no longer allows SVGs by default for `image`; this is probably acceptable and safer, but should be called out as intentional.
- `app/Providers/RouteServiceProvider.php`, `app/Http/Kernel.php`, and legacy-style `bootstrap/app.php` remain Laravel 10 structure. This is acceptable for Laravel 11, but should be left alone during the first upgrade unless there is a strong reason to modernize.
- `composer.json` still allows PHP `^8.1`; Laravel 11 requires PHP `8.2+`, and Laravel 13 requires PHP `8.3+`.

## Recommended Upgrade Target

Do not stop at Laravel 11.

Recommended path:

1. Upgrade to Laravel 11 as a short intermediate step.
2. Immediately proceed to Laravel 12 once tests are green and package compatibility is resolved.
3. Treat Laravel 13 as the next follow-up audit target, because it requires PHP `8.3+` and some packages may not yet be ready in the current lock state.

Rationale:

- Laravel 10 is already end-of-life.
- Laravel 11 is also past its security-fix date as of March 12, 2026.
- Laravel 12 still has security support until February 24, 2027.
- Laravel 13 is current, but it raises the PHP floor to `8.3`.

## Laravel 11 Execution Checklist

Planned composer target:

```json
{
  "php": "^8.2",
  "laravel/framework": "^11.0",
  "laravel/sanctum": "^4.0",
  "laravel/breeze": "^2.0",
  "livewire/livewire": "^3.4",
  "nunomaduro/collision": "^8.1"
}
```

Also update or remove:

- `beyondcode/laravel-query-detector`
- any other dev package that blocks Illuminate 11 components

Required Laravel 11 / Sanctum tasks:

- Publish Sanctum migrations if needed:

```bash
php artisan vendor:publish --tag=sanctum-migrations
```

- Update `config/sanctum.php` middleware references for Sanctum 4.
- Keep the existing Laravel 10 app structure for the first pass.
- Run `composer update -W`.
- Run `php artisan test`.
- Run `npm.cmd run build`.
- Smoke check `/health`, login, dashboard, product listing, order create, purchase create.

## Laravel 12 Readiness Checklist

Before Laravel 12:

- Update `laravel/framework` to `^12.0`.
- Update `phpunit/phpunit` to `^11.0`.
- Confirm compatible releases or replacements for:
  - `livewire/livewire`
  - `power-components/livewire-powergrid`
  - `barryvdh/laravel-debugbar`
  - `anayarojo/shoppingcart`
  - `kyslik/column-sortable`
  - `beyondcode/laravel-query-detector`
- Decide whether SVG uploads should remain rejected by the `image` rule.
- Re-run the full suite and the cPanel deployment runbook smoke checks.

## Laravel 13 Readiness Note

Laravel 13 is current as of this audit, but it requires PHP `8.3+`. This project should not jump directly from Laravel 10 to Laravel 13 without first stabilizing on Laravel 12 or doing a dedicated package compatibility pass.

Phase 29 should therefore target Laravel 11 first, then Laravel 12 if dependency resolution is clean. Laravel 13 should be evaluated after that as a new modernization step.

## Composer Audit Caveat

`composer prohibits laravel/framework ^11.0` and `^12.0` could not complete in this sandbox because Composer attempted to reach Packagist and the sandboxed connection failed. The package blockers above are based on installed package metadata from `composer.lock` and official Laravel upgrade guides.
