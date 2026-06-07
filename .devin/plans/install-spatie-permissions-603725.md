# Install and Configure Spatie Permissions

This plan outlines the steps to install Spatie Laravel Permission and integrate it into the educational/POS system architecture.

## Proposed Changes

### 1. Installation
- Run `composer require spatie/laravel-permission`.
- Publish the migration and config files.
- Run the migrations.

### 2. User Model Integration
- Add the `HasRoles` trait to the `User` model (`@/app/Models/User.php`).

### 3. Middleware Integration
- Add Spatie's role/permission middleware to `@/bootstrap/app.php`.

### 4. Comprehensive Permission List
Based on the existing admin modules, the following permissions are required:

- **Blog**: `view blog`, `create blog`, `update blog`, `delete blog`, `publish blog`, `manage revisions`
- **Bookings**: `view bookings`, `create bookings`, `update bookings`, `delete bookings`
- **Inspections**: `view inspections`, `create inspections`, `update inspections`, `delete inspections`
- **Gallery**: `view gallery`, `manage gallery`
- **Services**: `view services`, `manage services`
- **Settings**: `view settings`, `update settings`
- **SEO**: `manage seo`
- **Content**: `manage pages`, `manage categories`, `manage tags`, `manage testimonials`
- **Communication**: `view contact messages`, `manage contact numbers`
- **HR**: `manage absences`

### 5. Super Admin Implementation
- Register a `Super Admin` role.
- Use a `Gate::before` rule in `AppServiceProvider` to grant all permissions to users with the `Super Admin` role.
- Create a seeder to automatically create all permissions, the `Super Admin` role, and assign it to the **first user in the database**.

### 6. Policy Refactoring
- Refactor existing policies (e.g., `PostPolicy`, `BookingPolicy`) to use `$user->can()` with the new specific permissions.

## Implementation Steps

#### Step 1: Install Package
- Run `composer require spatie/laravel-permission`.
- Publish and run migrations.

#### Step 2: Model & Middleware
- Update `User.php` with `HasRoles`.
- Update `bootstrap/app.php` to register middleware.

#### Step 3: Seeding & Super Admin Setup
- Create `RolesAndPermissionsSeeder`.
- Assign `Super Admin` to user ID 1.
- Update `AppServiceProvider` with `Gate::before`.

#### Step 4: Policy Updates
- Update `PostPolicy.php` and others to use granular permissions.

## Questions for the User
1. Do you want to keep the email verification check as an additional requirement for all administrative actions?
2. Are there any other hidden modules I might have missed (e.g., POS-specific user management)?
