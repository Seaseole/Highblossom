<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Create the application version registry and seed the 1.2.0 baseline.
     */
    public function up(): void
    {
        if (! Schema::hasTable('application_versions')) {
            Schema::create('application_versions', function (Blueprint $table): void {
                $table->id();
                $table->string('version', 20)->unique();
                $table->unsignedSmallInteger('major');
                $table->unsignedSmallInteger('minor');
                $table->unsignedSmallInteger('patch');
                $table->string('summary')->default('');
                $table->json('notes')->nullable();
                $table->date('released_at');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['major', 'minor', 'patch']);
            });
        }

        if (! DB::table('application_versions')->where('version', '1.2.0')->exists()) {
            DB::table('application_versions')->insert([
                'version' => '1.2.0',
                'major' => 1,
                'minor' => 2,
                'patch' => 0,
                'summary' => 'Baseline release',
                'notes' => json_encode([
                    ['type' => 'added', 'text' => 'Application version registry introduced.'],
                ]),
                'released_at' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = Permission::findOrCreate('manage versions', 'web');

        foreach (['Super Admin', 'Admin'] as $roleName) {
            $role = Role::query()->where('name', $roleName)->first();

            if ($role) {
                $role->givePermissionTo($permission);
            }
        }
    }

    /**
     * Drop the application version registry.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_versions');
    }
};
