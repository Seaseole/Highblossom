<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

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

            // Access Control
            'manage users',
            'manage roles',
            'access admin panel',
        ];

        // Create permissions idempotently
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Super Admin role idempotently
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->syncPermissions(Permission::all());

        // Create Admin role idempotently
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions([
            'view blog', 'create blog', 'update blog', 'delete blog', 'publish blog',
            'view bookings', 'create bookings', 'update bookings', 'delete bookings',
            'view inspections', 'create inspections', 'update inspections', 'delete inspections',
            'view gallery', 'manage gallery',
            'view services', 'manage services',
            'view settings',
            'manage pages', 'manage categories', 'manage tags', 'manage testimonials',
            'view contact messages', 'manage contact numbers',
            'manage absences',
            'access admin panel',
        ]);

        // Create Staff role idempotently
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);
        $staffRole->syncPermissions([
            'view blog', 'create blog', 'update blog',
            'view bookings', 'create bookings', 'update bookings',
            'view inspections', 'create inspections',
            'view gallery',
            'view services',
            'access admin panel',
        ]);

        // Create User role idempotently
        Role::firstOrCreate(['name' => 'User']);

        // Create Eugene Seaseole admin user idempotently
        $user = User::firstOrCreate(
            ['email' => 'eugeneseasole@gmail.com'],
            [
                'name' => 'Eugene Seaseole',
                'password' => bcrypt('password'),
            ]
        );

        // Assign Super Admin role to Eugene user
        $user->assignRole($superAdminRole);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Remove Super Admin role from Eugene user
        $user = User::where('email', 'eugeneseasole@gmail.com')->first();
        if ($user) {
            $user->removeRole('Super Admin');
            $user->delete();
        }

        // Delete roles
        Role::where('name', 'Super Admin')->delete();
        Role::where('name', 'Admin')->delete();
        Role::where('name', 'Staff')->delete();
        Role::where('name', 'User')->delete();

        // Delete all permissions
        Permission::query()->delete();

        // Reset cached roles and permissions again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
