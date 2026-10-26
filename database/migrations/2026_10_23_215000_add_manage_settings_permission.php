<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Declare `manage settings` — the ability the staff directory and partner routes
 * have always gated on, in the web admin and in `/api/staff/team` and
 * `/api/staff/partners`, but which existed in no permission registry.
 *
 * The consequence was silent: `can:manage settings` denied every role, and only
 * Super Admin got through because `Gate::before` returns true for it. Because the
 * Roles screen builds its checkbox list from `Permission::all()`, nobody could
 * grant the ability at all — Staff directory and Partners were permanently
 * Super-Admin-only, and the staff app's two modules answered 403.
 *
 * Fresh installs declare it in the seed migration; this is the path for databases
 * that already exist. Admin is granted it because Admin already manages the same
 * content everywhere else; Staff deliberately is not.
 */
return new class extends Migration
{
    /**
     * Create the permission and grant it to the Admin role.
     */
    public function up(): void
    {
        Permission::findOrCreate('manage settings', 'web');

        Role::where('name', 'Admin')
            ->get()
            ->each(fn (Role $role) => $role->givePermissionTo('manage settings'));
    }

    /**
     * Revoke the grant and remove the permission.
     */
    public function down(): void
    {
        Role::where('name', 'Admin')
            ->get()
            ->each(fn (Role $role) => $role->revokePermissionTo('manage settings'));

        Permission::where('name', 'manage settings')
            ->get()
            ->each(fn (Permission $permission) => $permission->delete());
    }
};
