# Phase 27: cPanel Deployment Runbook

This runbook is the concise deployment path for the current cPanel hosting model.

It complements `docs/cpanel-hosting-guide.md`, which keeps the longer historical setup notes and troubleshooting context.

## Hosting Model

- Project path: `/home/rezatauh/reza_inventory`
- Public web path: `/home/rezatauh/public_html/dev.rezatauhid.cfd`
- Public web path should point to the Laravel `public` directory.
- Build assets locally, commit `public/build`, then deploy by pulling on the server.
- Do not put the full Laravel project inside `public_html`.

## Pre-Deploy Checklist

Before pushing:

```bash
php artisan test
npm run build
```

Confirm `public/build/manifest.json` exists after the build.

Then commit and push:

```bash
git status
git add .
git commit -m "Describe the deployment change"
git push origin main
```

Do not commit `.env`, `.env.production`, `vendor`, `node_modules`, `public/storage`, or local reports.

## Server Deploy

On cPanel terminal:

```bash
cd /home/rezatauh/reza_inventory
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
```

Only run `php artisan storage:link` if file/image serving is broken or the link does not exist.

Do not run `php artisan key:generate` on an existing production `.env`.

## Post-Deploy Smoke Checks

```bash
php artisan about
php artisan migrate:status
php artisan route:list --path=health
curl -fsS https://dev.rezatauhid.cfd/health
```

Browser checks:

- Login page loads.
- Dashboard loads after login.
- Product listing loads.
- Order create page loads.
- Purchase create page loads.
- Uploaded product images still load.

## Queue And Scheduler Checks

Confirm cron entries exist in cPanel:

```bash
* * * * * cd /home/rezatauh/reza_inventory && php artisan schedule:run >> /dev/null 2>&1
* * * * * cd /home/rezatauh/reza_inventory && php artisan queue:work --stop-when-empty --tries=3 --timeout=90 >> /dev/null 2>&1
```

Check failed jobs:

```bash
php artisan queue:failed
```

## Rollback Plan

Use rollback only when the new deploy causes a real incident and a forward fix is not faster.

1. Identify the previous known-good commit:

```bash
git log --oneline -5
```

2. Move the server worktree to that commit:

```bash
git checkout <known-good-commit>
composer install --optimize-autoloader --no-dev
php artisan optimize:clear
php artisan optimize
```

3. Smoke test:

```bash
curl -fsS https://dev.rezatauhid.cfd/health
```

4. After the incident, create a normal forward-fix commit locally and deploy it. Do not leave the server detached long-term.

Important database caveat:

- Roll back code only if migrations are backward-compatible.
- If a migration changed or removed data, do not guess. Stop and make a specific recovery plan from backups.

## Commands To Avoid On Live

```bash
php artisan migrate:fresh
php artisan migrate:fresh --seed
php artisan key:generate
git reset --hard
chmod -R 777 storage bootstrap/cache
```

## Production Values To Recheck

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dev.rezatauhid.cfd
LOG_CHANNEL=daily
LOG_LEVEL=warning
SESSION_SECURE_COOKIE=true
CORS_ALLOWED_ORIGINS=https://dev.rezatauhid.cfd
```
