# Production Readiness Audit Plan

## Overview

This document captures a production readiness audit for the Reza Laravel Inventory project as of 2026-04-02.

The goal is to answer one practical question:

Can this project safely go live for real users in its current state, and if not, what exact actions should be taken first?

Short answer:

- The project can run in production.
- The project should not be treated as production-ready without a hardening pass.
- The biggest risk is not traffic by itself. The biggest risk is an aging framework baseline, production configuration defaults, and a few operational gaps.

## Current Snapshot

### Application Stack

- Laravel: 10.48.29
- PHP requirement in `composer.json`: `^8.1`
- Current local PHP: 8.2.12
- Authentication: Laravel Breeze
- UI stack: Livewire 3, Blade, AdminLTE
- Permissions: Spatie Laravel Permission
- Data/export utilities: PhpSpreadsheet, barcode generator

### Important Dependency Notes

- `laravel/framework` is pinned to `10.48.29`
- `livewire/livewire` is `^3.1`
- `power-components/livewire-powergrid` is `^5.1`
- `spatie/laravel-permission` is `^6.19`
- `haruncpi/laravel-id-generator` is `^1.1`
- `anayarojo/shoppingcart` is `^4.2`

### Production-Relevant Defaults Seen in the Project

From `.env.example`:

- `APP_ENV=local`
- `APP_DEBUG=true`
- `CACHE_DRIVER=file`
- `QUEUE_CONNECTION=sync`
- `SESSION_DRIVER=file`
- `MAIL_MAILER=log`

From routing and code:

- `routes/web.php` exposes a public `phpinfo()` route at `php/`
- `config/app.php` uses file-based maintenance mode
- `config/session.php` defaults to file sessions
- `config/cache.php` defaults to file cache
- `config/queue.php` defaults to sync queue

### Testing Snapshot

The project has a useful test suite, including auth, CRUD, API, Livewire table tests, and AdminLTE regression coverage.

However:

- `tests/Feature/PurchaseControllerTest.php` has `RefreshDatabase` commented out
- several tests are happy-path response tests and do not yet prove production behavior under concurrency, queueing, or failure conditions
- there is no visible load or performance test setup in the repository

## Risk Summary

## 1. Framework support risk

Laravel 10 is no longer a comfortable long-term production baseline for a public-facing business app. The app may still run fine, but the framework is past the point where it should be treated as the long-term secure target for a growing production system.

Impact:

- future security posture weakens
- upgrade difficulty increases over time
- dependency compatibility window gets tighter

## 2. Production configuration risk

The repository defaults are still clearly development-oriented.

Impact:

- accidental production deploy with debug enabled could leak sensitive error details
- file cache and file session drivers do not scale cleanly across multiple app instances
- sync queue pushes all job work into user requests, which increases response time and failure blast radius
- log mailer can silently hide real email delivery gaps

## 3. Security exposure risk

The public `phpinfo()` route is not acceptable for production.

Impact:

- leaks PHP extensions, server details, configuration values, and environment clues to attackers

## 4. Scale and ops risk

The app currently looks more like a solid local/staging app than a fully hardened production system.

Impact:

- background jobs may block web requests
- cache and session handling may become brittle under higher user counts
- missing operational tooling can slow incident response

## 5. Test confidence risk

The project has meaningful tests, but not enough evidence yet for safe production confidence in high-value flows such as orders, purchases, due management, imports, exports, permissions, and concurrency-sensitive actions.

Impact:

- regressions may reach production
- checkout/order/purchase workflows may fail under real usage patterns that simple tests do not simulate

## Decision

## Recommended production posture

Do not launch this project to a real multi-user production environment exactly as-is.

Launch should happen only after the Phase 0 and Phase 1 actions below are completed.

## Exact Action List

## Phase 0: Blockers to fix before production

These are mandatory.

1. Remove or protect the public `phpinfo()` route in `routes/web.php`.
2. Ensure production `.env` uses:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - real `APP_URL`
   - real `APP_KEY`
3. Replace `MAIL_MAILER=log` with a real SMTP or transactional mail provider.
4. Decide the production session strategy:
   - preferred: `redis`
   - acceptable for small single-server deployments: `database`
5. Decide the production cache strategy:
   - preferred: `redis`
   - acceptable fallback: `database`
6. Stop using `QUEUE_CONNECTION=sync` in production.
   - use `redis` or `database`
   - run dedicated workers
7. Add queue worker process management.
   - Supervisor, systemd, PM2 equivalent, or hosting-native worker manager
8. Add scheduled task execution.
   - configure cron for `php artisan schedule:run`
9. Verify HTTPS and cookie security.
   - set `SESSION_SECURE_COOKIE=true` behind HTTPS
   - confirm `same_site` and cookie domain behavior for the real domain
10. Confirm backups exist and are tested.
   - database backups
   - uploaded/storage files if applicable
   - restore drill at least once

## Phase 1: Strongly recommended before public launch

These should be treated as launch-critical even if they are not absolute blockers.

1. Upgrade the framework baseline.
   - minimum target: Laravel 11
   - preferred target: Laravel 12 if dependency constraints are resolved cleanly
2. Freeze a production PHP target.
   - near-term stable choice: PHP 8.2 for current stack
   - if moving to newer dependency branches, evaluate PHP 8.4 readiness first
3. Expand test coverage for business-critical flows.
   - purchase approval
   - order placement and completion
   - due payments
   - quotation creation
   - product import/export
   - role/permission enforcement
4. Fix test isolation issues.
   - restore `RefreshDatabase` where intentionally disabled unless there is a documented reason not to
5. Add production error monitoring.
   - Sentry, Flare, Bugsnag, or equivalent
6. Add structured log review and alerting.
7. Run a permission audit.
   - verify all high-impact routes require the right roles and permissions
8. Review rate limiting for auth and sensitive actions.
9. Audit all upload, import, and export paths for validation and abuse resistance.
10. Review public routes and remove any debugging or temporary endpoints.

## Phase 2: Scale and resilience improvements

These make the system safer as usage grows.

1. Move cache, queue, and session to Redis if not already done.
2. Add database performance review.
   - inspect slow queries
   - verify indexes on frequently filtered columns
   - check reporting screens, order history, purchase reports, and dashboard aggregates
3. Add load testing for top workflows.
   - login
   - dashboard load
   - product listing/search
   - order create/store
   - purchase create/approve
4. Add health checks for:
   - database
   - queue
   - cache
   - storage permissions
5. Add deployment rollback procedure.
6. Add zero-downtime deployment steps if possible.
7. Document incident response steps.

## Phase 3: Upgrade roadmap

This is the recommended modernization path.

1. Stabilize production configuration first.
2. Upgrade Laravel 10 to Laravel 11.
3. Run full test suite and manual regression.
4. Resolve package compatibility for Laravel 12.
5. Upgrade Laravel 11 to Laravel 12.
6. Re-run regression, queue tests, auth tests, and module smoke tests.

Notes:

- Laravel 12 itself is not the main problem.
- The main work is dependency readiness and safe rollout.
- `spatie/laravel-permission` and `haruncpi/laravel-id-generator` should be reviewed carefully during the upgrade track.

## Recommended Implementation Order

If the goal is to go live soon without creating new risk, this is the best order:

1. Remove `phpinfo()` route.
2. Prepare production `.env` values.
3. Move mail, queue, cache, and session away from development defaults.
4. Add worker and cron setup.
5. Verify backup and monitoring.
6. Fix test isolation issues and expand coverage on critical workflows.
7. Perform a production smoke test on staging.
8. Launch.
9. Start framework upgrade work immediately after launch, or before launch if timeline allows.

## Suggested Owner Checklist

Use this as a direct execution checklist.

- [ ] Remove public debug or server info routes
- [ ] Create production `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure real mail provider
- [ ] Choose queue backend
- [ ] Choose session backend
- [ ] Choose cache backend
- [ ] Run queue workers
- [ ] Configure scheduler cron
- [ ] Enable HTTPS cookie settings
- [ ] Verify backups and restore
- [ ] Add monitoring and alerts
- [ ] Review permissions and sensitive routes
- [ ] Fix weak or non-isolated tests
- [ ] Run staging regression
- [ ] Decide Laravel 11 vs Laravel 12 upgrade timeline

## Final Recommendation

This project is close enough to production quality to be a real deployable app, but it is not yet in the state where "many users" should be the next step without preparation.

The fastest safe path is:

- harden production configuration first
- remove security exposure points
- move queue/session/cache to production-ready drivers
- improve operational readiness
- then upgrade the framework baseline on a controlled timeline

If these actions are completed, the project can be made suitable for real production use.
