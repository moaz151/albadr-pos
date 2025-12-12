---
name: Cart and Orders API
overview: Implement a complete e-commerce Cart and Orders API system for clients, including product browsing, cart management, checkout flow, order tracking, and customer profile management. Orders automatically convert to Sale invoices when delivered.
todos:
  - id: create-cart-models
    content: Create Cart and CartItem models with migrations and relationships
    status: pending
  - id: create-order-status-enum
    content: Create OrderStatusEnum with confirmed, processing, shipped, delivered statuses
    status: pending
  - id: update-order-model
    content: Update Order model with client relationship, shipping fields, and sale relationship
    status: completed
  - id: enhance-item-controller
    content: Enhance ItemController with category filtering and search functionality
    status: completed
  - id: create-cart-controller
    content: Create CartController with add, update, remove, clear, and getTotal methods
    status: completed
    dependencies:
      - create-cart-models
  - id: create-cart-requests
    content: Create AddCartItemRequest and UpdateCartItemRequest with AdvancedSettings validation
    status: completed
  - id: create-cart-resources
    content: Create CartResource and CartItemResource for API responses
    status: completed
    dependencies:
      - create-cart-models
  - id: add-cart-routes
    content: Add cart API routes with authentication middleware
    status: completed
    dependencies:
      - create-cart-controller
  - id: create-order-controller
    content: Create OrderController with checkout, index, show, and cancel methods
    status: completed
    dependencies:
      - create-cart-controller
      - update-order-model
  - id: create-order-service
    content: Create OrderService to handle order creation, status updates, and Sale conversion
    status: completed
    dependencies:
      - create-order-status-enum
      - update-order-model
  - id: create-checkout-request
    content: Create CheckoutRequest with shipping and payment validation
    status: completed
  - id: create-order-resource
    content: Create OrderResource for API responses
    status: completed
    dependencies:
      - update-order-model
  - id: add-order-routes
    content: Add order API routes with authentication middleware
    status: completed
    dependencies:
      - create-order-controller
  - id: enhance-auth-controller
    content: Enhance AuthController profile methods and ClientResource with order count
    status: completed
  - id: create-admin-order-controller
    content: Create Admin OrderController for order status management
    status: completed
    dependencies:
      - create-order-service
  - id: add-admin-order-routes
    content: Add admin order management routes
    status: completed
    dependencies:
      - create-admin-order-controller
  - id: integrate-advanced-settings
    content: Apply AdvancedSettings (decimal quantities, payment methods) to cart and orders
    status: pending
    dependencies:
      - create-cart-controller
      - create-order-controller
  - id: integrate-stock-management
    content: Integrate stock validation and management in OrderService
    status: pending
    dependencies:
      - create-order-service
  - id: implement-sale-conversion
    content: Implement automatic Sale creation when order status changes to Delivered
    status: pending
    dependencies:
      - create-order-service
---

# Advanced Settings & User Permission Management Implementation Plan

## Overview

This plan implements Advanced POS Settings and integrates Spatie Laravel Permissions for comprehensive user and role management. The implementation follows the same pattern as General Settings.

## Part 1: Advanced POS Settings

### 1.1 Create AdvancedSettings Class

- **File**: `app/Settings/AdvancedSettings.php`
- Extend `Spatie\LaravelSettings\Settings`
- Properties:
- `allow_decimal_quantities` (bool) - Toggle for decimal quantities
- `default_discount_method` (string) - 'percentage' or 'fixed_amount'
- `payment_methods` (array) - Array of enabled payment methods (e.g., ['cash'])
- Set group to 'advanced'

### 1.2 Create Settings Migration

- **File**: `database/settings/YYYY_MM_DD_HHMMSS_create_advanced_settings.php`
- Use `Spatie\LaravelSettings\Migrations\SettingsMigration`
- Add default values:
- `advanced.allow_decimal_quantities` = false
- `advanced.default_discount_method` = 'percentage'
- `advanced.payment_methods` = ['cash']

### 1.3 Create Form Request

- **File**: `app/Http/Requests/Admin/AdvancedSettingsRequest.php`
- Validation rules:
- `allow_decimal_quantities`: boolean
- `default_discount_method`: required|in:percentage,fixed_amount
- `payment_methods`: array
- `payment_methods.*`: in:cash (extendable for future methods)

### 1.4 Update AdvancedSettingsController

- **File**: `app/Http/Controllers/Admin/Settings/AdvancedSettingsController.php`
- Implement `view()` method - return view with AdvancedSettings instance
- Implement `update()` method - handle form submission, file uploads if needed, save settings

### 1.5 Create Blade View

- **File**: `resources/views/admin/settings/advanced.blade.php`
- Form with:
- Toggle switch for "Allow Decimal Quantities in Sales"
- Radio buttons for "Default Discount Application Method" (Percentage/Fixed Amount)
- Checkboxes for "Activate Available Payment/Till Methods" (Cash checkbox)
- Follow same structure as `general.blade.php`
- Use AdminLTE form components

### 1.6 Register Settings & Routes

- **File**: `config/settings.php` - Add `AdvancedSettings::class` to settings array
- **File**: `routes/web.php` - Add routes:
- `GET admin/settings/advanced` → `view()`
- `PUT admin/settings/advanced` → `update()`

### 1.7 Update Sidebar Navigation

- **File**: `resources/views/admin/layouts/partials/_sidebar.blade.php`
- Add "Advanced Settings" link under Settings menu

## Part 2: Spatie Laravel Permissions Integration

### 2.1 Install Package

- Run: `composer require spatie/laravel-permission`
- Run: `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
- Run: `php artisan migrate`

### 2.2 Update User Model

- **File**: `app/Models/User.php`
- Add `use Spatie\Permission\Traits\HasRoles;`
- Add `HasRoles` trait to User class

### 2.3 Create Permissions Seeder

- **File**: `database/seeders/PermissionsSeeder.php`
- Define all permissions based on requirements:
- **POS Permissions**: sales.create, sales.view, sales.edit, sales.delete, returns.*, items.*, units.*, categories.*, clients.*, reports.*, inventory.*, users.*
- **Online Store Permissions**: store-settings.*, orders.*, items.*, clients.*, reports.*, users.*
- Create roles: 'pos-admin', 'online-store-admin'
- Assign permissions to respective roles

### 2.4 Create Middleware (Optional)

- **File**: `app/Http/Middleware/CheckPermission.php` (if custom logic needed)
- Or use Spatie's built-in `role:` and `permission:` middleware in routes

## Part 3: Enhanced User Management

### 3.1 Update UserRequest

- **File**: `app/Http/Requests/Admin/UserRequest.php`
- Add validation for `roles` field: `'roles' => 'nullable|array'`
- Add validation for `roles.*`: `'roles.*' => 'exists:roles,name'`

### 3.2 Update UserController

- **File**: `app/Http/Controllers/Admin/UserController.php`
- In `create()`: Load all roles using `Role::all()`
- In `edit()`: Load all roles and user's current roles
- In `store()`: After creating user, sync roles: `$user->syncRoles($request->roles ?? [])`
- In `update()`: Sync roles after updating user
- In `index()`: Eager load roles relationship

### 3.3 Update User Views

- **Files**: 
- `resources/views/admin/users/create.blade.php`
- `resources/views/admin/users/edit.blade.php`
- Add role selection (checkboxes or multi-select)
- Display current roles in edit view
- Show roles in index view table

- **File**: `resources/views/admin/users/index.blade.php`
- Add "Roles" column to display user roles

## Part 4: Roles & Permissions Management Interface

### 4.1 Create RoleController

- **File**: `app/Http/Controllers/Admin/RoleController.php`
- Methods: `index()`, `create()`, `store()`, `edit()`, `update()`, `destroy()`
- Use Spatie's Role model

### 4.2 Create PermissionController

- **File**: `app/Http/Controllers/Admin/PermissionController.php`
- Methods: `index()`, `create()`, `store()`, `edit()`, `update()`, `destroy()`
- Use Spatie's Permission model

### 4.3 Create Form Requests

- **Files**: 
- `app/Http/Requests/Admin/RoleRequest.php`
- `app/Http/Requests/Admin/PermissionRequest.php`
- Validation rules for role/permission creation and updates

### 4.4 Create Views

- **Files**:
- `resources/views/admin/roles/index.blade.php` - List all roles
- `resources/views/admin/roles/create.blade.php` - Create role form
- `resources/views/admin/roles/edit.blade.php` - Edit role form (with permission checkboxes)
- `resources/views/admin/permissions/index.blade.php` - List all permissions
- `resources/views/admin/permissions/create.blade.php` - Create permission form
- `resources/views/admin/permissions/edit.blade.php` - Edit permission form

### 4.5 Add Routes

- **File**: `routes/web.php`
- Add resource routes for roles and permissions
- Add routes under admin middleware group

### 4.6 Update Sidebar

- **File**: `resources/views/admin/layouts/partials/_sidebar.blade.php`
- Add "Roles & Permissions" menu item under Settings or as separate section
- Include sub-items: "Roles" and "Permissions"

## Part 5: Apply Permissions to Routes

### 5.1 Protect Routes with Middleware

- **File**: `routes/web.php`
- Add `middleware(['permission:permission-name'])` to relevant routes
- Or use `middleware(['role:role-name'])` for role-based access
- Protect settings routes, user management, etc.

## Implementation Order

1. Part 1: Advanced Settings (follows existing General Settings pattern)
2. Part 2: Install and configure Spatie Permissions
3. Part 3: Enhance User Management with roles
4. Part 4: Build Roles & Permissions management UI
5. Part 5: Apply permissions to protect routes

## Files to Create/Modify

### New Files:

- `app/Settings/AdvancedSettings.php`
- `database/settings/YYYY_MM_DD_HHMMSS_create_advanced_settings.php`
- `app/Http/Requests/Admin/AdvancedSettingsRequest.php`
- `resources/views/admin/settings/advanced.blade.php`
- `database/seeders/PermissionsSeeder.php`
- `app/Http/Controllers/Admin/RoleController.php`
- `app/Http/Controllers/Admin/PermissionController.php`
- `app/Http/Requests/Admin/RoleRequest.php`
- `app/Http/Requests/Admin/PermissionRequest.php`
- `resources/views/admin/roles/*.blade.php` (4 files)
- `resources/views/admin/permissions/*.blade.php` (3 files)

### Modified Files:

- `app/Http/Controllers/Admin/Settings/AdvancedSettingsController.php`
- `config/settings.php`
- `routes/web.php`
- `resources/views/admin/layouts/partials/_sidebar.blade.php`
- `app/Models/User.php`
- `app/Http/Controllers/Admin/UserController.php`
- `app/Http/Requests/Admin/UserRequest.php`
- `resources/views/admin/users/*.blade.php` (3 files)