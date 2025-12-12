---
name: Advanced Settings & Permissions
overview: Implement Advanced POS Settings using Spatie Laravel Settings, integrate Spatie Laravel Permissions for role-based access control, and enhance User Management with role assignment capabilities.
todos:
  - id: advanced-settings-class
    content: "Create AdvancedSettings class with properties: allow_decimal_quantities (bool), default_discount_method (string), payment_methods (array)"
    status: completed
  - id: advanced-settings-migration
    content: Create settings migration for advanced settings with default values
    status: completed
    dependencies:
      - advanced-settings-class
  - id: advanced-settings-request
    content: Create AdvancedSettingsRequest with validation rules
    status: completed
  - id: advanced-settings-controller
    content: Implement view() and update() methods in AdvancedSettingsController
    status: completed
    dependencies:
      - advanced-settings-class
      - advanced-settings-request
  - id: advanced-settings-view
    content: Create advanced.blade.php view with toggle, radio buttons, and checkboxes
    status: completed
  - id: register-advanced-settings
    content: Register AdvancedSettings in config/settings.php and add routes in web.php
    status: completed
    dependencies:
      - advanced-settings-class
      - advanced-settings-controller
  - id: update-sidebar-settings
    content: Add Advanced Settings link to sidebar navigation
    status: completed
    dependencies:
      - advanced-settings-view
  - id: install-spatie-permissions
    content: Install spatie/laravel-permission package, publish config, and run migrations
    status: in_progress
  - id: update-user-model
    content: Add HasRoles trait to User model
    status: pending
    dependencies:
      - install-spatie-permissions
  - id: create-permissions-seeder
    content: Create PermissionsSeeder with all POS and Online Store permissions and roles
    status: pending
    dependencies:
      - install-spatie-permissions
  - id: update-user-request
    content: Add roles validation to UserRequest
    status: pending
    dependencies:
      - install-spatie-permissions
  - id: update-user-controller
    content: Update UserController to handle role assignment (syncRoles) in create/update methods
    status: pending
    dependencies:
      - update-user-model
      - update-user-request
  - id: update-user-views
    content: Add role selection to user create/edit forms and display roles in index view
    status: pending
    dependencies:
      - update-user-controller
  - id: create-role-controller
    content: Create RoleController with CRUD operations
    status: pending
    dependencies:
      - install-spatie-permissions
  - id: create-permission-controller
    content: Create PermissionController with CRUD operations
    status: pending
    dependencies:
      - install-spatie-permissions
  - id: create-role-permission-requests
    content: Create RoleRequest and PermissionRequest form validation classes
    status: pending
  - id: create-role-views
    content: Create views for roles management (index, create, edit with permission checkboxes)
    status: pending
    dependencies:
      - create-role-controller
  - id: create-permission-views
    content: Create views for permissions management (index, create, edit)
    status: pending
    dependencies:
      - create-permission-controller
  - id: add-role-permission-routes
    content: Add resource routes for roles and permissions in web.php
    status: pending
    dependencies:
      - create-role-controller
      - create-permission-controller
  - id: update-sidebar-permissions
    content: Add Roles & Permissions menu items to sidebar
    status: pending
    dependencies:
      - add-role-permission-routes
  - id: protect-routes
    content: Apply permission middleware to protect routes based on user roles
    status: pending
    dependencies:
      - create-permissions-seeder
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