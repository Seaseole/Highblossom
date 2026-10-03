<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Grant the permission that gates the session and device management screens.
 *
 * RolesAndPermissionsSeeder covers fresh installs; this migration is what makes
 * the gate resolvable on databases that were seeded before the feature existed.
 */
return new class extends Migration
{
    /**
     * Create the permission and attach it to the roles that manage sessions.
     */
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = Permission::findOrCreate('manage user sessions', 'web');

        foreach (['Super Admin', 'Admin'] as $roleName) {
            $role = Role::query()->where('name', $roleName)->first();

            if ($role) {
                $role->givePermissionTo($permission);
            }
        }
    }

    /**
     * Remove the permission again, leaving role grants to be swept by Spatie.
     */
    public function down(): void
    {
        Permission::where('name', 'manage user sessions')->get()->each->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
