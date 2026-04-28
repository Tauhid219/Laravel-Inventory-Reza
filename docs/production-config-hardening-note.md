# Phase 25: Production Config Hardening Note

## Status

Completed as a production configuration baseline. This phase does not make the application fully production-ready by itself, but it removes several risky assumptions and documents the exact configuration decisions needed before launch.

## What Changed

- Added environment knobs for CORS and session same-site behavior.
- Added production reminders to `.env.example`.
- Added database-backed runtime tables for shared-hosting friendly queue, session, and cache fallbacks.
- Updated README production notes with `APP_KEY`, HTTPS cookie, and CORS guidance.
- Updated the cPanel hosting guide so `php artisan key:generate` is clearly first-time-only.

## Production Environment Baseline

Minimum live `.env` values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-production-domain.example
LOG_CHANNEL=daily
LOG_LEVEL=warning
SESSION_SECURE_COOKIE=true
CORS_ALLOWED_ORIGINS=https://your-production-domain.example
```

`APP_KEY` must exist before launch, but it must not be regenerated on an existing production environment without a planned key-rotation process.

## Queue Strategy

Preferred:

```env
QUEUE_CONNECTION=redis
```

Acceptable cPanel/single-server fallback:

```env
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database-uuids
```

When using the database driver, run the new operational migration and keep a worker running:

```bash
php artisan migrate --force
php artisan queue:work --tries=3 --timeout=90
```

On cPanel, use the hosting provider's process manager if available. If long-running workers are unavailable, use cron as a weaker fallback:

```bash
* * * * * cd /home/youruser/reza_inventory && php artisan queue:work --stop-when-empty --tries=3 --timeout=90 >> /dev/null 2>&1
```

## Scheduler Strategy

Configure one cron entry:

```bash
* * * * * cd /home/youruser/reza_inventory && php artisan schedule:run >> /dev/null 2>&1
```

The project currently has no custom scheduled command, but setting this up now prevents future scheduled work from silently never running.

## Cache And Session Strategy

Preferred:

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

Acceptable cPanel/single-server fallback:

```env
CACHE_DRIVER=database
SESSION_DRIVER=database
```

The operational migration creates `cache`, `cache_locks`, and `sessions` tables for this fallback.

## Mail Strategy

Do not use `MAIL_MAILER=log` in production except for a temporary smoke test. Use SMTP or a transactional provider:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-user
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@your-domain.example
MAIL_FROM_NAME="${APP_NAME}"
```

## Remaining Caveats

- The app still needs a dedicated monitoring/error-reporting phase.
- Production backup and restore verification still need to be done outside the codebase.
- Rate limiting and API/auth abuse controls should be reviewed during the later production readiness pass.
