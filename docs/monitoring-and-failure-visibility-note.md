# Phase 26: Monitoring and Failure Visibility Note

## Status

Completed as a first monitoring baseline. The project now has a lightweight health endpoint and a concrete operator checklist for production visibility.

## What Changed

- Added a public-safe `GET /health` endpoint.
- The health endpoint checks:
  - application boot
  - database connectivity
  - cache read/write behavior
  - writable runtime storage directories
- Health check failures are reported through Laravel's exception reporting pipeline.
- Added feature coverage for the health endpoint.

## Health Endpoint Contract

Route:

```text
GET /health
```

Healthy response:

```json
{
  "status": "ok",
  "checks": {
    "app": { "ok": true },
    "database": { "ok": true },
    "cache": { "ok": true },
    "storage": { "ok": true }
  }
}
```

If any dependency fails, the endpoint returns HTTP `503` with `status: degraded`. It does not expose exception messages, SQL errors, file paths, secrets, or stack traces.

## Monitoring Setup Plan

Before production launch:

- Add an uptime monitor against `/health`.
- Alert on non-`200` responses.
- Alert if the response body status is not `ok`.
- Send Laravel logs to a place operators actually check.
- Add an error monitoring service such as Sentry, Flare, or Bugsnag.
- Confirm `LOG_CHANNEL=daily` and `LOG_LEVEL=warning` or stricter in production.
- Confirm queue worker failures are reviewed with `php artisan queue:failed`.
- Confirm the scheduler cron is installed and can be observed.
- Confirm database backups and storage backups have a restore test.

## Alert-Worthy Events

- `/health` returns `503`.
- Database check fails.
- Cache check fails.
- Storage permissions check fails.
- Queue failed jobs appear.
- Mail delivery failures occur.
- Order completion or purchase approval throws unexpected exceptions.
- Login/password reset error rates spike.
- Disk usage approaches hosting limits.
- Backups fail or are missing.

## cPanel-Friendly Checks

Manual smoke commands:

```bash
php artisan about
php artisan migrate:status
php artisan queue:failed
php artisan schedule:list
```

HTTP check:

```bash
curl -fsS https://your-domain.example/health
```

Cron entries from Phase 25 should be checked in the hosting panel and verified after deploy.

## Remaining Caveats

- No external monitoring provider is configured in code yet.
- No custom dashboard metrics exist yet.
- Queue worker supervision still depends on the final hosting setup.
- Backup/restore verification remains an operational task outside the Laravel app.
