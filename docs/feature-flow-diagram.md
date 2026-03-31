# Feature Flow Guide

## Overview

This document explains how the main features work step by step across the project.

It focuses on operational flow rather than table structure.

## 1. Login and Access Flow

### Flow

1. User opens the application
2. Default route sends the user to login
3. User logs in through Laravel Breeze auth
4. Auth middleware protects internal routes
5. Permission and role middleware restrict actions

### Key Points

- all main business routes are inside `auth` middleware
- many routes also use `permission:*` or `role:*` restrictions
- super-admin has the highest control

## 2. Product Management Flow

### Create Product

1. User opens product create page
2. Allowed categories are loaded based on user role
3. User fills product data
4. If code already exists, a new unique code is generated
5. Product record is created
6. Optional image is uploaded and saved

### View Product

1. User opens product details
2. Product relations are loaded
3. Barcode is generated from product code
4. Product details are rendered

### Update Product

1. User edits product information
2. Core fields are updated
3. If image is replaced, old image is deleted
4. New image is stored

## 3. Category-Based Access Flow

This is one of the most important custom behaviors in the project.

### Flow

1. User logs in
2. System reads user role names
3. For non-admin users, categories are filtered by `categories.role_name`
4. Products are filtered through their category
5. Orders and purchases are also filtered by product category path

### Result

- users only see the data relevant to their assigned category role
- admins and super-admins can see all data

## 4. Legacy Order Flow

This flow uses the shopping cart package.

### Create Order

1. User opens order create page
2. Cart instance is reset
3. User selects products and adds them to cart
4. User selects customer and fills invoice/order fields
5. System creates an `orders` record
6. System creates `order_details` from cart contents
7. Cart is cleared

### Complete Order

1. User opens pending order
2. User triggers update/complete action
3. System loads order details
4. System checks each product stock
5. System decrements stock
6. System changes order status to complete

## 5. Order V2 Flow

This is the newer invoice-row-based order flow.

### Create Order

1. User opens Order V2 create page
2. Allowed categories and customers are loaded
3. User adds invoice rows dynamically
4. Product, quantity, and price are selected
5. System validates stock before saving
6. System stores order header and detail rows in a transaction

### Approve / Complete Order

1. User views a pending order
2. User triggers status update
3. System loops through order details
4. System confirms enough product stock exists
5. System decrements stock
6. System updates status to complete

### Why V2 Matters

- cleaner invoice row entry
- better Livewire interaction
- role-aware filtering is more structured

## 6. Due Payment Flow

### Flow

1. System lists orders where `due > 0`
2. User opens a due order
3. User enters payment amount
4. System updates:
   `pay = old pay + new payment`
5. System updates:
   `due = old due - new payment`

### Purpose

- track customer outstanding balances
- record partial payment collection

## 7. Purchase Flow

### Create Purchase

1. User opens purchase create page
2. Allowed categories and suppliers are loaded
3. User adds invoice rows dynamically
4. Purchase header is saved in `purchases`
5. Purchase line items are saved in `purchase_details`
6. Purchase starts in pending status

### Approve Purchase

1. Admin opens pending purchase
2. Admin triggers approval
3. System loads all purchase detail rows
4. Each product quantity is incremented
5. Purchase status is changed to approved
6. `updated_by` is saved

### Business Meaning

- purchase create records intended stock intake
- purchase approval actually moves stock into inventory

## 8. Quotation Flow

### Create Quotation

1. User opens quotation create page
2. Quotation cart instance is reset
3. User adds products into quotation cart
4. User selects customer
5. User sets tax, discount, shipping, and note
6. System creates quotation header
7. System creates quotation detail rows
8. Cart is cleared

### Important Difference From Order

- quotation does not reduce stock
- quotation is a commercial document, not a stock movement event

## 9. Invoice Preview Flow

### Flow

1. User prepares an order in cart
2. User requests invoice preview
3. System loads customer data
4. System loads current cart contents
5. Invoice preview page is rendered

### Purpose

- preview or print order invoice before final completion

## 10. Customer and Supplier Flow

### Customer Flow

1. User creates customer
2. Optional photo is uploaded
3. Customer can be linked to orders and quotations
4. Customer delete is blocked if orders exist

### Supplier Flow

1. User creates supplier
2. Optional photo is uploaded
3. Supplier is linked to purchases
4. Supplier delete is blocked if purchases exist

## 11. Role and Permission Flow

### Role Creation

1. Admin creates a role
2. Role is stored in Spatie `roles` table

### Permission Creation

1. Admin creates a permission
2. Permission is stored in Spatie `permissions` table

### Assign Permission to Role

1. Admin opens role permission page
2. Admin selects permissions
3. System syncs permissions to role

### Assign Role to User

1. Admin creates or edits user
2. One or more roles are selected
3. System syncs selected roles to the user

### Result

- route access
- CRUD access
- visibility of scoped data

## 12. Dashboard Flow

### Flow

1. User visits dashboard
2. System counts records from multiple business tables
3. Today's purchases and quotations are calculated
4. Summary cards are displayed

### Purpose

- quick operational overview
- high-level business monitoring

## 13. Product API Flow

### Flow

1. Client requests `api/products`
2. System returns all products
3. If `category_id` is supplied, products are filtered
4. JSON response is returned

### Usage

- lightweight product fetch for external use or AJAX-like integrations

## 14. Seed and Bootstrap Flow

### Flow

1. Seeder creates categories
2. Seeder creates units
3. Seeder creates products
4. Seeder creates roles
5. Seeder creates permissions
6. Seeder creates default users
7. Seeder maps permissions to roles

### Result

- project becomes usable after fresh setup
- super-admin and admin accounts are ready

## 15. End-to-End Inventory Lifecycle

This is the most important business flow in the entire application.

### Stock In

1. Supplier provides products
2. Purchase is recorded
3. Purchase is approved
4. Stock is added to products

### Stock Out

1. Customer order is created
2. Order is approved/completed
3. Stock is subtracted from products

### Non-Stock Commercial Document

1. Customer requests pricing
2. Quotation is generated
3. No stock movement happens

## 16. Practical Mental Model

You can think of the whole project like this:

- `categories/sub-categories/units` organize the catalog
- `products` hold the stockable items
- `purchases` bring stock in
- `orders` send stock out
- `quotations` prepare commercial offers
- `customers` and `suppliers` are business partners
- `users/roles/permissions` control who can see or do what

## 17. Known Flow Issues

### Dual Order System

The presence of both legacy orders and Order V2 means the project currently has overlapping implementations.

### Purchase Report Export

The export flow still appears to use older field names and includes debug output, so that path may not work correctly without cleanup.

### Role Mapping Dependency

Because data visibility depends on `categories.role_name`, category setup must be correct for access filtering to behave correctly.

## Final Summary

If you want to understand the project quickly, focus on these four flows first:

1. product creation
2. purchase approval
3. order completion
4. role-based category filtering

These explain most of the business behavior across the application.
