# Database Schema Guide

## Overview

This document explains the main database structure of the Reza Laravel Inventory project.

The system is centered around inventory movement and business operations through:

- products
- purchases
- orders
- quotations
- users and permissions

## Main Tables

### users

Stores application users.

Important fields:

- `id`
- `name`
- `username`
- `email`
- `password`
- `photo`
- `email_verified_at`

Used by:

- authentication
- profile management
- purchase creator/updater tracking
- role and permission mapping

### categories

Stores top-level product categories.

Important fields:

- `id`
- `name`
- `slug`
- `short_code`
- `role_name`

Special note:

- `role_name` is used for role-based filtering of visible data.

### sub_categories

Stores sub-categories under categories.

Important fields:

- `id`
- `name`
- `slug`
- `category_id`

Relationship:

- many sub-categories belong to one category

### units

Stores product measurement/unit data.

Examples:

- piece
- box
- kg

### products

Stores inventory items.

Important fields:

- `id`
- `name`
- `slug`
- `code`
- `quantity`
- `buying_price`
- `selling_price`
- `quantity_alert`
- `tax`
- `tax_type`
- `notes`
- `product_image`
- `category_id`
- `sub_category_id`
- `unit_id`

Relationships:

- belongs to `categories`
- belongs to `sub_categories`
- belongs to `units`

Behavior:

- stock is increased from approved purchases
- stock is decreased from completed orders

### customers

Stores customer information.

Important fields:

- `id`
- `name`
- `email`
- `phone`
- `address`
- `photo`
- `account_holder`
- `account_number`
- `bank_name`

Relationships:

- one customer has many orders
- one customer has many quotations

### suppliers

Stores supplier/vendor information.

Important fields:

- `id`
- `name`
- `email`
- `phone`
- `address`
- `shopname`
- `type`
- `photo`
- `account_holder`
- `account_number`
- `bank_name`

Relationships:

- one supplier has many purchases

### orders

Stores customer sales orders.

Important fields:

- `id`
- `customer_id`
- `order_date`
- `order_status`
- `total_products`
- `sub_total`
- `vat`
- `total`
- `invoice_no`
- `payment_type`
- `pay`
- `due`
- `note`

Relationship:

- belongs to `customers`
- has many `order_details`

Business meaning:

- represents a sale transaction
- may start pending
- becomes complete after stock deduction

### order_details

Stores line items for each order.

Important fields:

- `id`
- `order_id`
- `product_id`
- `quantity`
- `unitcost`
- `total`

Relationships:

- belongs to `orders`
- belongs to `products`

### purchases

Stores supplier purchase headers.

Important fields:

- `id`
- `supplier_id`
- `date`
- `purchase_no`
- `status`
- `total_amount`
- `created_by`
- `updated_by`

Relationships:

- belongs to `suppliers`
- belongs to `users` through `created_by`
- belongs to `users` through `updated_by`
- has many `purchase_details`

Business meaning:

- represents incoming stock
- approved status updates stock

### purchase_details

Stores line items for each purchase.

Important fields:

- `id`
- `purchase_id`
- `product_id`
- `quantity`
- `unitcost`
- `total`

Relationships:

- belongs to `purchases`
- belongs to `products`

### quotations

Stores quotation header information.

Important fields:

- `id`
- `date`
- `reference`
- `customer_id`
- `customer_name`
- `tax_percentage`
- `tax_amount`
- `discount_percentage`
- `discount_amount`
- `shipping_amount`
- `total_amount`
- `status`
- `note`

Relationships:

- belongs to `customers`
- has many `quotation_details`

Business meaning:

- commercial quotation
- does not move stock

### quotation_details

Stores quotation line items.

Important fields:

- `id`
- `quotation_id`
- `product_id`
- `product_name`
- `product_code`
- `quantity`
- `price`
- `unit_price`
- `sub_total`
- `product_discount_amount`
- `product_discount_type`
- `product_tax_amount`

Relationships:

- belongs to `quotations`
- optionally belongs to `products`

## Permission Tables

These tables come from Spatie Laravel Permission.

### permissions

Stores permission names such as:

- `create product`
- `view product`
- `update order`

### roles

Stores roles such as:

- `super-admin`
- `admin`
- category-specific admin roles

### model_has_permissions

Maps permissions directly to models.

### model_has_roles

Maps roles to models, mainly users.

### role_has_permissions

Maps permissions to roles.

## Relationship Summary

### Product Classification

- one category has many products
- one category has many sub-categories
- one sub-category has many products
- one unit has many products

### Sales

- one customer has many orders
- one order has many order details
- one product can appear in many order details

### Purchases

- one supplier has many purchases
- one purchase has many purchase details
- one product can appear in many purchase details

### Quotations

- one customer has many quotations
- one quotation has many quotation details
- one product can appear in many quotation details

### User Tracking

- one user can create many purchases
- one user can approve/update many purchases

## Stock Movement Rules

### Purchase Approval

When a purchase is approved:

- the purchase status changes to approved
- each `purchase_details.quantity` is added to `products.quantity`

### Order Completion

When an order is completed:

- the order status changes to complete
- each `order_details.quantity` is subtracted from `products.quantity`

## Status Fields

### orders.order_status

Enum values:

- `0` = Pending
- `1` = Complete
- `2` = Cancel

### purchases.status

Enum values:

- `0` = Pending
- `1` = Approved

### quotations.status

Enum values:

- `0` = Pending
- `1` = Sent
- `2` = Canceled

## ERD-Style Mental Model

```text
users
  ├─< purchases >─ suppliers
  │      └─< purchase_details >─ products
  │
customers
  ├─< orders
  │     └─< order_details >─ products
  │
  └─< quotations
        └─< quotation_details >─ products

categories
  ├─< sub_categories
  └─< products >─ units
```

## Important Schema Risks and Notes

### Duplicate category migration

There are two migrations that add `role_name` to `categories`. This may cause problems during fresh migration if not handled carefully.

### Mixed old and new naming

Some controller logic still references old field names like:

- `purchase_date`
- `purchase_status`

But the active schema uses:

- `date`
- `status`

This is especially relevant in purchase reporting logic.

## Final Summary

The schema is designed around three business cores:

- catalog and inventory data
- stock-in through purchases
- stock-out through orders

The remaining tables support users, access control, and commercial records such as quotations.
