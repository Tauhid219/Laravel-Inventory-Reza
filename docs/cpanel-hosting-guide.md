# cPanel Hosting Guide for `dev.rezatauhid.cfd`

For the short operational deploy checklist, use `docs/cpanel-deployment-runbook.md`.

This guide keeps the longer setup history, server-path context, and troubleshooting notes.

এই গাইডটি তোমার বর্তমান বাস্তব setup ধরে লেখা:

- সাবডোমেইন: `dev.rezatauhid.cfd`
- Document root fixed: `/public_html/dev.rezatauhid.cfd`
- Laravel project পুরোটা `public_html`-এর বাইরে থাকবে
- লোকাল পিসি থেকে `git push`, cPanel terminal থেকে `git pull`
- দরকার হলে image/storage folder symlink করা হবে

এই guide-টা এমনভাবে লেখা যাতে deployment করার সময় আমরা step by step follow করতে পারি।

---

## ১. Recommended Structure

ধরা যাক cPanel home path:

```text
/home/youruser
```

তাহলে structure ideally হবে:

```text
/home/youruser/rezatauhid-inventory
/home/youruser/public_html/dev.rezatauhid.cfd
```

এখানে:

- `/home/youruser/rezatauhid-inventory` = পুরো Laravel project
- `/home/youruser/public_html/dev.rezatauhid.cfd` = public-facing folder

---

## ২. Important Principle

যেহেতু document root change করা যাচ্ছে না, তাই Laravel-এর `public` folder সরাসরি web root করা যাবে না।

তাই আমাদের practical solution হবে:

1. পুরো project বাইরে রাখা
2. subdomain root-এর ভেতরে Laravel `public` folder-এর content রাখা
3. `index.php`-এর path main project-এর দিকে point করানো
4. `storage` বা image folder symlink দিয়ে connect করা

এটা shared hosting-এ খুব common workaround।

---

## ৩. Deployment Workflow

তোমার workflow হবে:

1. লোকাল পিসিতে কাজ করা
2. লোকালেই `npm run build` চালানো
3. GitHub-এ push করা
4. cPanel terminal-এ project folder-এ গিয়ে `git pull` করা
5. দরকারি artisan/composer command চালানো

এই system-এর নাম:

- `Git-based Deployment`
- আরো specific করে: `Pull-based Deployment`

এটা full CI/CD না, কারণ deploy steps এখনো manual।

---

## ৪. Server Preparation

প্রথম deployment-এর আগে server-এ এগুলো confirm করতে হবে:

1. PHP version `8.1+`
2. Composer available
3. Git available
4. cPanel terminal available
5. MySQL database create করা যাবে
6. Symlink allow করে কি না, তা test করতে হবে

---

## ৫. First-Time Setup Plan

### Step 1: Project clone করা

`public_html`-এর বাইরে project clone করো:

```bash
cd ~
git clone <your-repo-url> rezatauhid-inventory
cd rezatauhid-inventory
```

যদি repository already exist করে, clone করার দরকার নেই।

### Step 2: Composer dependencies install

```bash
composer install --optimize-autoloader --no-dev
```

### Step 3: Environment file setup

```bash
cp .env.example .env
php artisan key:generate
```

`php artisan key:generate` শুধু first-time `.env` তৈরির সময় চালাতে হবে। Existing production `.env`-এ `APP_KEY` already থাকলে এটা আবার চালানো যাবে না, কারণ encrypted data, sessions, signed URLs, and tokens invalid হয়ে যেতে পারে।

তারপর `.env`-এ production values বসাতে হবে।

Minimum important values:

```env
APP_NAME="Reza Inventory"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dev.rezatauhid.cfd
LOG_CHANNEL=daily
LOG_LEVEL=warning
SESSION_SECURE_COOKIE=true
CORS_ALLOWED_ORIGINS=https://dev.rezatauhid.cfd
```

Database values:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

Shared hosting-friendly runtime values:

```env
CACHE_DRIVER=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database-uuids
MAIL_MAILER=smtp
```

Redis থাকলে `CACHE_DRIVER`, `SESSION_DRIVER`, and `QUEUE_CONNECTION`-এ `redis` preferred.

### Step 4: Database migrate

```bash
php artisan migrate --force
```

যদি seed দরকার হয়:

```bash
php artisan db:seed --force
```

অথবা:

```bash
php artisan migrate --seed --force
```

Note:

- production server-এ `migrate:fresh` ব্যবহার করা যাবে না
- `migrate:fresh` সব table drop করে

### Step 5: Subdomain root-এ public files বসানো

Laravel `public` folder-এর content subdomain root-এ রাখতে হবে।

Source:

```text
/home/youruser/rezatauhid-inventory/public
```

Target:

```text
/home/youruser/public_html/dev.rezatauhid.cfd
```

যা যা থাকবে:

- `index.php`
- `.htaccess`
- built assets
- অন্যান্য public asset files

Important:

- পুরো Laravel project `public_html`-এ move করবে না
- শুধু `public` layer web root-এ যাবে

### Step 6: `index.php` path fix করা

subdomain root-এর `index.php` edit করে project path ঠিক করতে হবে।

Example:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../../rezatauhid-inventory/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../../rezatauhid-inventory/vendor/autoload.php';

$app = require_once __DIR__.'/../../rezatauhid-inventory/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

বাস্তবে `../../rezatauhid-inventory` path তোমার actual server path অনুযায়ী adjust করতে হতে পারে।

তুমি যখন server path confirm করবে, তখন exact line আমি তোমাকে বলে দেব।

### Step 7: Storage link

Laravel uploaded file/image show করাতে:

```bash
php artisan storage:link
```

এতে সাধারণত এই link তৈরি হয়:

```text
public/storage -> storage/app/public
```

তোমার setup-এ যেহেতু public layer subdomain root-এ expose হচ্ছে, তাই final public path properly check করতে হবে।

### Step 8: Cache and optimization

```bash
php artisan optimize:clear
php artisan optimize
```

যদি optimize-এর পর সমস্যা হয়:

```bash
php artisan optimize:clear
```

---

## ৬. Update / Redeploy Process

প্রতিবার code update-এর সময় সাধারণ flow:

### লোকাল পিসিতে

```bash
npm run build
git add .
git commit -m "Your message"
git push origin main
```

### cPanel terminal-এ

```bash
cd ~/rezatauhid-inventory
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
```

Queue worker and scheduler:

```bash
php artisan queue:work --tries=3 --timeout=90
php artisan schedule:run
```

Production server-এ এগুলো manually একবার চালানোর command না; hosting process manager বা cron দিয়ে নিয়মিত চালু রাখতে হবে। cPanel cron example:

```bash
* * * * * cd /home/rezatauh/reza_inventory && php artisan schedule:run >> /dev/null 2>&1
* * * * * cd /home/rezatauh/reza_inventory && php artisan queue:work --stop-when-empty --tries=3 --timeout=90 >> /dev/null 2>&1
```

যদি storage link একবার ঠিকভাবে তৈরি হয়ে যায়, প্রতিবার সেটা চালানো লাগবে না।

---

## ৭. Frontend Build Note

এই project-এ Vite use হচ্ছে:

```json
"scripts": {
  "dev": "vite",
  "build": "vite build"
}
```

আর `.gitignore`-এ `public/build` এখন tracked রাখার মতো set করা আছে:

```gitignore
# /public/build
```

মানে current workflow অনুযায়ী:

1. লোকালে `npm run build`
2. built files git-এ push
3. server-এ `git pull`

এই project-এর জন্য shared hosting environment-এ এটা acceptable workflow।

---

## ৮. Symlink Strategy

দুই ধরনের symlink দরকার হতে পারে:

### A. Laravel storage symlink

এটা standard:

```bash
php artisan storage:link
```

### B. Custom image folder symlink

যদি তুমি `public_html/dev.rezatauhid.cfd/images`-এর মতো public path থেকে আসলে project-এর ভিতরের কোনো safe folder serve করতে চাও, তখন custom symlink লাগতে পারে।

কিন্তু আগে standard `storage:link` যথেষ্ট কি না, সেটা দেখা উচিত।

---

## ৯. What Not To Do

এগুলো avoid করা ভালো:

1. `777` permission দেওয়া
2. `git reset --hard` কে normal habit বানানো
3. পুরো Laravel project `public_html`-এ রাখা
4. production server-এ `migrate:fresh` চালানো
5. `.env` file git-এ push করা
6. `APP_DEBUG=true` রেখে production চালানো

Recommended permissions:

- `storage`: `775`
- `bootstrap/cache`: `775`

---

## ১০. Safe Troubleshooting

### যদি `.env` read না হয়

```bash
cp .env.example .env
php artisan key:generate
```

Important: existing production `.env` থাকলে `php artisan key:generate` আবার চালাবে না। আগে current `APP_KEY` backup/verify করে তারপর config issue troubleshoot করবে।

### যদি autoload/package issue হয়

```bash
composer install --optimize-autoloader --no-dev
```

### যদি config পুরোনো দেখায়

```bash
php artisan optimize:clear
php artisan optimize
```

### যদি image না দেখা যায়

```bash
php artisan storage:link
```

তারপর URL/path check করতে হবে।

---

## ১১. Exact Working Model for Your Case

তোমার case-এ practical model হবে:

1. project clone থাকবে `public_html`-এর বাইরে
2. subdomain root fixed থাকবে `/public_html/dev.rezatauhid.cfd`
3. Laravel public files ওই root-এ থাকবে
4. `index.php` main project-কে boot করবে
5. image/file serve করার জন্য `storage:link` বা custom symlink use হবে
6. deploy হবে local push + cPanel pull দিয়ে

---

## ১২. Step-by-Step Collaboration Plan

তুমি যখন actual hosting শুরু করবে, আমরা এই order-এ যাব:

1. server home path confirm
2. current subdomain folder status check
3. repo clone / existing repo verify
4. composer install
5. `.env` setup
6. database setup
7. public folder copy/setup
8. `index.php` path fix
9. `storage:link`
10. optimize and test

আমি তোমাকে তখন এক ধাপ করে বলব:

- এখন কোন command run করবে
- run করার পর কী output expect করবে
- output দেখে next step কী হবে

---

## ১৩. Short Summary

তোমার plan valid।

Best practical approach:

- project বাইরে
- subdomain root fixed public folder হিসেবে use
- `index.php` path fix
- local build + git push
- server git pull
- storage/image symlink as needed

এই guide current cPanel constraint অনুযায়ী corrected version।

---

## ১৪. Real Deployment Notes for This Project

এই section-টা generic না, আমাদের actual deployment session-এর verified notes।

### Actual server paths

```text
/home/rezatauh
/home/rezatauh/reza_inventory
/home/rezatauh/public_html/dev.rezatauhid.cfd
```

### Final working symlink

```text
/home/rezatauh/public_html/dev.rezatauhid.cfd -> /home/rezatauh/reza_inventory/public
```

### Actual repository used

```text
https://github.com/Tauhid219/Laravel-Inventory-Reza.git
```

---

## ১৫. What Actually Happened During Deployment

আমাদের deployment-এ শুরুতে project path হিসেবে `/home/rezatauh/inventory_project` ছিল, কিন্তু সেটা broken state-এ ছিল।

Observed state:

- folder-এর মধ্যে শুধু incomplete `.git` ছিল
- `git status` / `git remote -v` কাজ করছিল না
- error আসছিল:

```text
fatal: not a git repository
```

### Safe decision taken

Broken folder repair না করে fresh clone করা হয়েছে।

Old broken folder:

```text
/home/rezatauh/inventory_project
```

Renamed backup:

```text
/home/rezatauh/inventory_project_broken_2026_04_15
```

New final project folder:

```text
/home/rezatauh/reza_inventory
```

---

## ১৬. Exact Commands Used in First Working Setup

### A. Fresh clone

```bash
git clone https://github.com/Tauhid219/Laravel-Inventory-Reza.git /home/rezatauh/reza_inventory
```

### B. Symlink replace

```bash
rm /home/rezatauh/public_html/dev.rezatauhid.cfd
ln -s /home/rezatauh/reza_inventory/public /home/rezatauh/public_html/dev.rezatauhid.cfd
ls -ld /home/rezatauh/public_html/dev.rezatauhid.cfd
```

### C. Base Laravel setup

```bash
cd /home/rezatauh/reza_inventory
composer install --optimize-autoloader --no-dev
cp .env.example .env
php artisan key:generate
php artisan optimize:clear
php artisan storage:link
php artisan optimize
```

### D. Migration status check

```bash
php artisan migrate:status
```

Result:

- সব migration already `Ran` ছিল
- database-এ existing data ছিল
- তাই `migrate:fresh --seed` ব্যবহার করা হয়নি

---

## ১৭. Historical Production Error and Current Status

Deployment-এর সময় একটি real blocker এসেছিল:

```text
Class "Kyslik\ColumnSortable\ColumnSortableServiceProvider" not found
```

### Why it happened historically

- `config/app.php` production-এ `Kyslik\ColumnSortable\ColumnSortableServiceProvider` load করছিল
- কিন্তু `composer.json`-এ `kyslik/column-sortable` package `require-dev`-এ ছিল
- server-এ `composer install --no-dev` দেওয়ায় package install হয়নি

### Current fix after Laravel 12 upgrade



The package has now been removed during the Laravel 12 upgrade. Do not reinstall `kyslik/column-sortable`; keep `config/app.php`, `composer.json`, and `composer.lock` aligned with the upgraded dependency set.


Current server refresh flow:


```bash
cd /home/rezatauh/reza_inventory
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan optimize:clear
php artisan optimize
```

Existing production `.env` থাকলে `APP_KEY` regenerate করা যাবে না। `php artisan key:generate` শুধু first deployment-এ, empty `APP_KEY` পূরণ করার জন্য।

### Deployment lesson

যে package production boot process-এ লাগে, সেটা কখনো `require-dev`-এ রাখা যাবে না।

---

## ১৮. Database Safety Decision

Database credentials `.env`-এ set করার পর migrate নিয়ে confusion হয়েছিল, কারণ database-এ existing data already ছিল।

### What we checked

```bash
php artisan migrate:status
```

### What we found

- সব migration already run হয়েছে
- existing live data ছিল

### Correct decision

এই অবস্থায়:

- `php artisan migrate:fresh` দেওয়া যাবে না
- `php artisan migrate:fresh --seed` দেওয়া যাবে না
- blind `php artisan db:seed`-ও risky

### Safe rules

যদি existing data থাকে:

```bash
php artisan migrate:status
php artisan migrate --force
```

আর seed শুধু তখনই চালাবে যখন নিশ্চিত:

- সেটা duplicate data create করবে না
- existing users/roles/data নষ্ট করবে না

---

## ১৯. Final `.env` Expectations

কমপক্ষে এই values production-এর জন্য ঠিক থাকতে হবে:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dev.rezatauhid.cfd
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_user_password
```

Note:

- `DB_DATABASE` = database name
- `DB_USERNAME` = MySQL user name
- `DB_PASSWORD` = ওই MySQL user-এর password

MySQL user password আর cPanel login password একই হওয়া বাধ্যতামূলক না।

---

## ২০. Final Ongoing Deploy Commands for This Exact Project

এখন থেকে `reza_inventory` project-এর জন্য standard deploy flow হবে:

### লোকাল পিসিতে

```bash
npm run build
git add .
git commit -m "Describe your change"
git push origin main
```

### cPanel server-এ

```bash
cd /home/rezatauh/reza_inventory
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
```

যদি file/image serving নিয়ে সমস্যা হয়:

```bash
php artisan storage:link
```

---

## ২১. Commands To Avoid on Live Server

এই project-এর live server-এ নিচের command avoid করবে:

```bash
php artisan migrate:fresh
php artisan migrate:fresh --seed
git reset --hard
chmod -R 777 storage bootstrap/cache
```

Reason:

- data loss হতে পারে
- unnecessary destructive recovery হতে পারে
- security risk বাড়ে

---

## ২২. Quick Recovery Checklist

যদি future-এ site break করে, এই order-এ check করবে:

1. symlink ঠিক আছে কি না

```bash
ls -ld /home/rezatauh/public_html/dev.rezatauhid.cfd
```

2. project path আছে কি না

```bash
ls -la /home/rezatauh/reza_inventory
```

3. latest code pull হয়েছে কি না

```bash
cd /home/rezatauh/reza_inventory
git pull origin main
```

4. dependencies ঠিক আছে কি না

```bash
composer install --optimize-autoloader --no-dev
```

5. cache clear/rebuild

```bash
php artisan optimize:clear
php artisan optimize
```

6. storage link

```bash
php artisan storage:link
```

7. migration status

```bash
php artisan migrate:status
```

8. health endpoint

```bash
curl -fsS https://dev.rezatauhid.cfd/health
```

Expected healthy status:

```json
{"status":"ok"}
```

Actual response-এ dependency-level `checks` থাকবে। যদি HTTP `503` আসে, database/cache/storage checks আগে দেখবে।

---

## ২৩. Final Recommended Summary

এই project-এর জন্য tested and working approach:

- project path: `/home/rezatauh/reza_inventory`
- subdomain path symlinked to project `public`
- local build + git push
- server git pull
- production composer install
- safe migration only
- no destructive fresh migration on live database

এই section-গুলো future deployment-এর সময় সবচেয়ে useful reference হবে।
