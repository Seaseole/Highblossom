<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\ApplicationVersion;
use App\Models\User;
use App\Services\ApplicationVersionService;
use Database\Seeders\ApplicationVersionSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ViewErrorBag;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover the application version registry: permission gating, semver validation,
 * forward-only ordering, and cache invalidation when a release is recorded.
 */
class ApplicationVersionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed permissions and act as a user that can manage versions.
     */
    private function actingAsVersionAdmin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));

        $this->actingAs($user);

        return $user;
    }

    public function test_guests_are_redirected_from_the_versions_page(): void
    {
        $this->get(route('admin.versions.index'))->assertRedirect(route('login'));
    }

    public function test_the_versions_page_renders_with_the_current_release(): void
    {
        $this->actingAsVersionAdmin();
        $this->seed(ApplicationVersionSeeder::class);

        $this->get(route('admin.versions.index'))
            ->assertOk()
            ->assertSee('Application Versions')
            ->assertSee('1.3.1');
    }

    public function test_users_without_the_permission_cannot_manage_versions(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('User', 'web'));
        $this->actingAs($user);

        $this->get(route('admin.versions.index'))->assertForbidden();
        $this->post(route('admin.versions.store'), [
            'version' => '9.9.9',
            'summary' => 'Should not be recorded',
            'released_at' => now()->toDateString(),
        ])->assertForbidden();
    }

    public function test_the_release_history_is_seeded_from_the_baseline_onwards(): void
    {
        $this->actingAsVersionAdmin();

        $this->seed(ApplicationVersionSeeder::class);
        $this->seed(ApplicationVersionSeeder::class);

        $this->assertDatabaseHas('application_versions', [
            'version' => '1.2.0',
            'major' => 1,
            'minor' => 2,
            'patch' => 0,
            'summary' => 'Baseline release',
        ]);

        $this->assertSame(
            ['1.2.0', '1.2.1', '1.3.0', '1.3.1'],
            ApplicationVersion::query()->orderBy('id')->pluck('version')->all()
        );

        $this->assertSame('1.3.1', app(ApplicationVersionService::class)->current()?->version);
    }

    public function test_it_rejects_invalid_semantic_versions(): void
    {
        $this->actingAsVersionAdmin();

        foreach (['1.2', 'v1.2.0', '01.2.0', '1.2.0.0', 'abc', '1.2.x'] as $invalid) {
            $this->post(route('admin.versions.store'), [
                'version' => $invalid,
                'summary' => 'Invalid version test',
                'released_at' => now()->toDateString(),
            ])->assertSessionHas('errors', fn (ViewErrorBag $errors): bool => $errors->getBag('default')->has('version'));

            $this->assertDatabaseMissing('application_versions', ['version' => $invalid]);
        }
    }

    public function test_it_rejects_versions_that_are_not_newer_than_current(): void
    {
        $this->actingAsVersionAdmin();

        foreach (['1.2.0', '1.1.9', '0.9.9'] as $notNewer) {
            $this->post(route('admin.versions.store'), [
                'version' => $notNewer,
                'summary' => 'Non-increasing version test',
                'released_at' => now()->toDateString(),
            ])->assertSessionHas('errors', fn (ViewErrorBag $errors): bool => $errors->getBag('default')->has('version'));
        }
    }

    public function test_it_records_a_newer_release_and_refreshes_the_current_version(): void
    {
        $this->actingAsVersionAdmin();

        $this->post(route('admin.versions.store'), [
            'version' => '1.2.1',
            'summary' => 'Mobile admin responsiveness',
            'released_at' => now()->toDateString(),
            'notes' => [
                ['type' => 'fixed', 'text' => 'Admin index tables now scroll on mobile.'],
                ['type' => 'added', 'text' => 'Application version registry.'],
            ],
        ])->assertRedirect(route('admin.versions.index'));

        $this->assertDatabaseHas('application_versions', [
            'version' => '1.2.1',
            'major' => 1,
            'minor' => 2,
            'patch' => 1,
        ]);

        $current = app(ApplicationVersionService::class)->current();

        $this->assertSame('1.2.1', $current?->version);
    }

    public function test_current_version_is_cached_and_forgotten_on_record(): void
    {
        $this->actingAsVersionAdmin();
        $this->seed(ApplicationVersionSeeder::class);

        $service = app(ApplicationVersionService::class);

        $this->assertSame('1.3.1', $service->current()?->version);
        $this->assertTrue(Cache::has(ApplicationVersionService::CACHE_KEY));

        $service->record([
            'version' => '1.4.0',
            'summary' => 'Feature release',
            'notes' => [],
            'released_at' => now()->toDateString(),
        ], User::factory()->create());

        $this->assertFalse(Cache::has(ApplicationVersionService::CACHE_KEY));
        $this->assertSame('1.4.0', $service->current()?->version);
    }

    public function test_next_bump_suggestions_are_computed_from_the_current_version(): void
    {
        $this->actingAsVersionAdmin();
        $this->seed(ApplicationVersionSeeder::class);

        Cache::forget(ApplicationVersionService::CACHE_KEY);

        $service = app(ApplicationVersionService::class);

        $this->assertSame(['patch' => '1.3.2', 'minor' => '1.4.0', 'major' => '2.0.0'], $service->nextOptions());
    }

    public function test_bump_suggestions_fall_back_to_the_baseline_when_nothing_is_recorded(): void
    {
        $this->actingAsVersionAdmin();

        Cache::forget(ApplicationVersionService::CACHE_KEY);

        $this->assertSame(['patch' => '1.2.1', 'minor' => '1.3.0', 'major' => '2.0.0'], app(ApplicationVersionService::class)->nextOptions());
    }
}
