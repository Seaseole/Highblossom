<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Grant the permission that gates the system overview dashboard.
 *
 * RolesAndPermissionsSeeder covers fresh installs; this migration is what makes
 * the gate resolvable on databases that were seeded before the feature existed.
 * Super Admin only: the page reports runtime internals, so it stays out of the
 * Admin role.
 */
return new class extends Migration
{
    /**
     * Create the permission and attach it to Super Admin.
     */
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = Permission::findOrCreate('view system overview', 'web');

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
        Permission::where('name', 'view system overview')->get()->each->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
