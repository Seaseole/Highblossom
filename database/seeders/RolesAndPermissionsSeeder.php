<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            // Blog
            'view blog',
            'create blog',
            'update blog',
            'delete blog',
            'publish blog',
            'manage revisions',

            // Bookings
            'view bookings',
            'create bookings',
            'update bookings',
            'delete bookings',

            // Inspections
            'view inspections',
            'create inspections',
            'update inspections',
            'delete inspections',

            // Gallery
            'view gallery',
            'manage gallery',

            // Services
            'view services',
            'manage services',

            // Settings
            'view settings',
            'update settings',

            // SEO
            'manage seo',

            // Content
            'manage pages',
            'manage categories',
            'manage tags',
            'manage testimonials',

            // Communication
            'view contact messages',
            'manage contact numbers',

            // HR
            'manage absences',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create Super Admin role
        $superAdminRole = Role::findOrCreate('Super Admin');

        // Assign Super Admin role to the first user
        $user = User::first();
        if ($user) {
            $user->assignRole($superAdminRole);
        }
    }
}
