# Demo Admin Runbook

## Purpose

এই note demo recruiter access maintain করার quick reference।

## Current Demo Identity

- Role: `demo-admin`
- Seeded email: `demo-admin@reza-inventory.test`
- Seeded username: `demo_admin`

## Access Model

- one-click login from the login page
- session runs in `demo_mode`
- UI is read-only
- backend mutations and exports are blocked

## Safe Usage Rules

- production super-admin account কখনো recruiter/demo walkthrough-এর জন্য share করা যাবে না
- demo user শুধুই sanitized/demo environment-এ ব্যবহার করা উচিত
- যদি production-like data থাকে, sensitive fields/data exposure আলাদা review করা উচিত

## Password Rotation

- যদি demo password rotate করতে চান, `database/seeders/UserSeederForRolePermission.php` update করুন
- তারপর target environment-এ relevant seeder rerun করুন
- login page public credential show করে না, তাই rotation external copy change ছাড়া করা যাবে

## Reseed Flow

- role/permission/user refresh করতে access-control seeders rerun করুন
- relevant files:
  - `database/seeders/RoleSeeder.php`
  - `database/seeders/RolePermissionSeeder.php`
  - `database/seeders/UserSeederForRolePermission.php`

## Manual Verification Checklist

- login page-এ `Login as Demo Admin` button visible
- button click করলে dashboard-এ login হয়
- shared `Demo Mode` banner visible
- orders, purchases, products, customers, suppliers, quotations browse করা যায়
- profile/settings visible না
- create/edit/delete/approve/export actions visible না
- direct mutation endpoints 403 দেয়

## If Demo Login Stops Working

- confirm seeded demo user exists
- confirm demo user still has `demo-admin` role
- confirm session storage is working
- confirm `deny.demo` middleware remains registered in `app/Http/Kernel.php`
