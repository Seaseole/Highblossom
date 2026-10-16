<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Grant the permission that gates the system overview export.
 *
 * RolesAndPermissionsSeeder covers fresh installs; this migration is what makes
 * the gate resolvable on databases that were seeded before the feature existed.
 * Kept separate from "view system overview" on purpose: reading the dashboard and
 * taking a copy of it off the server are different risks, so a support role may be
 * given the first without the second. Super Admin only.
 */
return new class extends Migration
{
    /**
     * Create the permission and attach it to Super Admin.
     */
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = Permission::findOrCreate('export system overview', 'web');

        $role = Role::query()->where('name', 'Super Admin')->first();

        if ($role) {
            $role->givePermissionTo($permission);
        }
    }

    /**
     * Remove the permission again, leaving role grants to be swept by Spatie.
     */
    public function down(): void
    {
        Permission::where('name', 'export system overview')->get()->each->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
