# Reza Laravel Inventory Project Analysis

## Overview

This project is a Laravel 10 based inventory management system with sales, purchase, quotation, customer, supplier, and role-permission management features.

The codebase combines:

- Inventory management
- POS/order handling
- Purchase approval workflow
- Quotation generation
- Customer and supplier management
- Role-based access control
- Livewire-based interactive forms and tables

## Tech Stack

- Laravel 10
- PHP 8.1+
- MySQL
- Laravel Breeze for authentication
- Livewire 3
- Spatie Laravel Permission
- Shopping cart package
- Livewire PowerGrid
- PhpSpreadsheet for export
- Barcode generator

Key dependencies are defined in `composer.json`.

## Top-Level Project Structure

```text
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
```

### Important Directories

- `app/Models`
  Contains the main business entities and relationships.

- `app/Http/Controllers`
  Contains route handling and business logic. Controllers are split by module such as product, purchase, order, quotation, dashboard, and auth.

- `app/Livewire`
  Contains interactive form components, searchable UI components, and table components.

- `database/migrations`
  Defines the database schema.

- `database/seeders`
  Contains default roles, permissions, users, categories, and sample seed logic.

- `resources/views`
  Blade templates grouped by feature area.

- `routes`
  Route definitions for web, API, auth, channels, and console.

- `tests`
  Feature and unit tests for major modules.

## Core Domain Models

The application revolves around these main models:

- `User`
- `Category`
- `SubCategory`
- `Unit`
- `Product`
- `Customer`
- `Supplier`
- `Order`
- `OrderDetails`
- `Purchase`
- `PurchaseDetails`
- `Quotation`
- `QuotationDetails`

## Main Features

### 1. Dashboard

The dashboard shows summary counts for:

- Total products
- Total orders
- Completed orders
- Total purchases
- Today's purchases
- Categories
- Sub-categories
- Suppliers
- Customers
- Quotations
- Today's quotations

Main logic:

- `app/Http/Controllers/Dashboards/DashboardController.php`

### 2. Product Management

Product management supports:

- Product create, edit, show, delete
- Product image upload
- Product barcode generation
- Category, sub-category, and unit assignment
- Duplicate product code fallback generation

Important files:

- `app/Http/Controllers/Product/ProductController.php`
- `app/Models/Product.php`

How it works:

- Product prices are stored as integer values in the database and converted through model accessors/mutators.
- Product detail pages generate a barcode from the product code.
- Product images are saved to storage and linked through the public disk.

### 3. Category, Sub-category, and Unit Management

These modules define the product classification structure.

Features:

- Category CRUD
- Sub-category CRUD
- Unit CRUD
- Category-level role mapping through `role_name`

Important files:

- `app/Http/Controllers/CategoryController.php`
- `app/Http/Controllers/SubCategoryController.php`
- `app/Http/Controllers/UnitController.php`
- `app/Models/Category.php`

### 4. Customer Management

Customer module supports:

- Customer create, edit, view, delete
- Photo upload
- Customer profile details
- Relation with orders and quotations

Important behavior:

- A customer cannot be deleted if the customer has related orders.

Important file:

- `app/Http/Controllers/CustomerController.php`

### 5. Supplier Management

Supplier module supports:

- Supplier create, edit, view, delete
- Photo upload
- Supplier purchase history
- Supplier type handling

Important behavior:

- A supplier cannot be deleted if the supplier has related purchases.

Important file:

- `app/Http/Controllers/SupplierController.php`

### 6. Orders and POS

There are two order flows in the project.

#### Legacy Order Flow

The older flow uses a shopping cart package.

Main files:

- `app/Http/Controllers/Order/OrderController.php`
- `app/Livewire/OrderForm.php`
- `app/Livewire/ProductCart.php`

How it works:

- Products are added to a cart instance.
- An order is created from cart contents.
- Order details are inserted into `order_details`.
- When the order is completed, product stock is reduced.

#### Order V2 Flow

The newer flow uses a Livewire invoice-style form instead of cart-based persistence.

Main files:

- `app/Http/Controllers/OrderV2/OrderV2Controller.php`
- `app/Livewire/OrderV2Form.php`

How it works:

- Products are selected in dynamic invoice rows.
- The order and related order details are stored inside a database transaction.
- The order can remain pending.
- When approved/completed, stock is reduced from the product table.

#### Related Order Views

- All orders
- Pending orders
- Completed orders
- Single order detail
- Printable invoice

Important schema files:

- `database/migrations/2023_05_04_084431_create_orders_table.php`
- `database/migrations/2023_05_04_084646_create_order_details_table.php`

### 7. Due Payment Management

The due module handles outstanding customer payments.

Features:

- List orders with due amount greater than zero
- View due order details
- Edit and record payment against due

Main file:

- `app/Http/Controllers/Order/DueOrderController.php`

How it works:

- The system updates `pay` and `due` values on the order.

### 8. Purchase Management

Purchase module supports:

- Purchase creation
- Pending purchases
- Approved purchases
- Daily purchase report
- Purchase report export flow

Main files:

- `app/Http/Controllers/Purchase/PurchaseController.php`
- `app/Livewire/PurchaseForm.php`
- `app/Models/Purchase.php`

How it works:

- A purchase is created with one or more purchase detail rows.
- Purchase starts in pending status.
- When approved, product stock is increased based on purchase details.

Important schema files:

- `database/migrations/2023_05_06_142348_create_purchases_table.php`
- `database/migrations/2023_05_06_143104_create_purchase_details_table.php`

### 9. Quotation Management

Quotation module supports:

- Quotation creation
- Quotation listing
- Customer-linked quotation records
- Item-level tax and discount handling

Main files:

- `app/Http/Controllers/Quotation/QuotationController.php`
- `app/Models/Quotation.php`
- `app/Models/QuotationDetails.php`

How it works:

- Quotation items are stored from a dedicated cart instance.
- Quotation reference IDs are auto-generated using a helper.
- Quotations do not directly change stock.

Important schema files:

- `database/migrations/2023_11_03_140528_create_quotations_table.php`
- `database/migrations/2023_11_03_140529_create_quotation_details_table.php`

### 10. Authentication and Profile

Authentication uses Laravel Breeze.

Features:

- Register
- Login
- Logout
- Forgot password
- Reset password
- Email verification
- Profile update
- Password update

Main route file:

- `routes/auth.php`

### 11. Role and Permission Management

The project uses Spatie Laravel Permission.

Features:

- Role CRUD
- Permission CRUD
- Assign permissions to roles
- Assign roles to users
- Restrict routes by permission and role middleware

Main files:

- `app/Http/Controllers/RoleController.php`
- `app/Http/Controllers/PermissionController.php`
- `app/Http/Controllers/UserRolePermissionController.php`
- `database/migrations/2025_06_03_101259_create_permission_tables.php`

### 12. API

There is a simple product API endpoint.

Features:

- Return all products
- Filter products by `category_id`

Main file:

- `app/Http/Controllers/API/V1/ProductController.php`

## Role-Based Access Logic

One of the custom features in this project is category-based role filtering.

How it works:

- The `categories` table contains a `role_name` column.
- Non-admin users only see categories/products/orders/purchases that belong to their assigned role.
- This filtering appears in controllers and Livewire table/form components.

Important files:

- `app/Livewire/OrderV2Form.php`
- `app/Livewire/PurchaseForm.php`
- `app/Livewire/Tables/ProductTable.php`
- `app/Livewire/Tables/OrderV2Table.php`
- `app/Livewire/Tables/PurchaseTable.php`

## Database Flow Summary

### Stock Increase

Stock increases when:

- A purchase is approved

Flow:

1. Purchase is created in `purchases`
2. Purchase items are stored in `purchase_details`
3. Approval action updates purchase status
4. Product quantity is incremented

### Stock Decrease

Stock decreases when:

- An order is completed or approved

Flow:

1. Order is created in `orders`
2. Order items are stored in `order_details`
3. Completion action checks stock availability
4. Product quantity is decremented

### Quotation Flow

Quotation does not change stock.

Flow:

1. Quotation data is stored in `quotations`
2. Item rows are stored in `quotation_details`
3. Reference number is auto-generated

## Livewire Components

Important interactive components include:

- `app/Livewire/OrderForm.php`
- `app/Livewire/OrderV2Form.php`
- `app/Livewire/PurchaseForm.php`
- `app/Livewire/ProductCart.php`
- `app/Livewire/SearchProduct.php`

Their responsibilities include:

- Dynamic invoice row handling
- Product selection
- Category and sub-category filtering
- Cart updates
- Search and autocomplete
- Live table rendering

## Helpers and Utility Logic

Helper functions are defined in:

- `app/Helpers/helpers.php`

Notable helpers:

- `make_reference_id()` for quotation/reference numbering
- `format_currency()`
- `array_merge_numeric_values()`

## Seed Data

The project contains seeders for:

- Categories
- Units
- Products
- Roles
- Permissions
- Default users

Important files:

- `database/seeders/DatabaseSeeder.php`
- `database/seeders/RoleSeeder.php`
- `database/seeders/PermissionSeeder.php`
- `database/seeders/UserSeederForRolePermission.php`

Default seeded users include:

- `ahmad@gmail.com` with `super-admin`
- `admin@admin.com` with `admin`

## Views Structure

Feature-based Blade view folders include:

- `resources/views/products`
- `resources/views/orders`
- `resources/views/ordersV2`
- `resources/views/purchases`
- `resources/views/quotations`
- `resources/views/customers`
- `resources/views/suppliers`
- `resources/views/categories`
- `resources/views/role-permission`
- `resources/views/profile`

## Test Coverage

There are feature tests and Livewire-related tests for multiple modules.

Examples:

- Product controller tests
- Purchase controller tests
- Order controller tests
- Supplier tests
- Customer tests
- Auth tests
- Livewire table tests

Tests are located in:

- `tests/Feature`
- `tests/Unit`

## Route Surface Summary

The project exposes a broad set of web routes for:

- Authentication
- Dashboard
- Products
- Orders
- Orders V2
- Purchases
- Quotations
- Customers
- Suppliers
- Categories
- Sub-categories
- Units
- Roles
- Permissions
- User management
- Due payments
- Invoice generation

There is also one public API route for products.

## Notable Observations

### 1. Dual Order Implementation

The codebase contains both:

- `orders`
- `orders-v2`

This suggests the project is in a transition phase from the older cart-based order flow to a newer Livewire-based order flow.

### 2. Purchase Report Logic Needs Attention

In the purchase export logic:

- old column names such as `purchase_date` and `purchase_status` still appear
- a `dd()` call is present

This suggests the report export path may be incomplete or outdated.

### 3. Duplicate Migration Risk

There are two migrations adding `role_name` to categories:

- `2025_12_26_142853_add_role_name_to_categories_table.php`
- `2025_12_27_110924_add_role_name_to_categories_table.php`

This may cause migration conflicts in a fresh setup.

## Final Architectural Summary

This project is best understood as a role-aware inventory and sales management platform.

The main architectural pattern is:

- Controllers handle request flow and permission checks
- Models define data relationships and value casting
- Livewire components power interactive forms and listing screens
- Migrations define the business schema
- Seeders bootstrap roles, permissions, and base records

The most important business rule in the system is stock movement:

- purchase approval increases stock
- order completion decreases stock

Everything else supports that inventory lifecycle through classification, user access control, partner management, and sales documentation.
