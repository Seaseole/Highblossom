<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Jobs\WarmSystemOverviewProbe;
use App\Livewire\SystemOverviewPackages;
use App\Livewire\SystemOverviewSection;
use App\Models\User;
use App\Support\SystemOverview\PackageCatalog;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeRegistry;
use App\Support\SystemOverview\ProbeRunner;
use App\Support\SystemOverview\ProbeStatus;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover the system overview dashboard: permission gating, section rendering,
 * per-section caching, failure isolation, the re-measure action, and the heavy
 * sections that must never be measured inside a page request.
 */
class SystemOverviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed roles and act as a user that may view the overview.
     */
    private function actingAsOverviewAdmin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));

        $this->actingAs($user);

        return $user;
    }

    public function test_guests_are_redirected_from_the_overview(): void
    {
        $this->get(route('admin.system-overview.index'))->assertRedirect(route('login'));
    }

    public function test_users_without_the_permission_are_forbidden(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Admin', 'web'));

        $this->actingAs($user)->get(route('admin.system-overview.index'))->assertForbidden();
        $this->actingAs($user)->post(route('admin.system-overview.refresh'))->assertForbidden();
    }

    public function test_the_overview_lists_every_light_section(): void
    {
        $this->actingAsOverviewAdmin();

        $this->get(route('admin.system-overview.index'))
            ->assertOk()
            ->assertSee('Application')
            ->assertSee('PHP Runtime')
            ->assertSee('Database')
            ->assertSee('Backing Stores')
            ->assertSee('Queue & Scheduler')
            ->assertSee('Sessions');
    }

    public function test_sections_are_cached_and_remeasured_only_on_demand(): void
    {
        $this->actingAsOverviewAdmin();

        $runner = app(ProbeRunner::class);
        $first = $runner->run('runtime');

        $this->assertNotNull(Cache::get('system_overview.runtime'));

        $second = app(ProbeRunner::class)->run('runtime');

        $this->assertSame($first->warmAt, $second->warmAt);

        $this->post(route('admin.system-overview.refresh'))
            ->assertRedirect()
            ->assertSessionHas('success', __('messages.system_overview_refreshed'));

        $this->assertNull(Cache::get('system_overview.runtime'));
    }

    public function test_a_throwing_probe_becomes_a_failed_section_instead_of_an_exception(): void
    {
        $this->actingAsOverviewAdmin();

        $original = config('database.default');

        // An unresolvable connection makes the database probe throw mid-measure.
        config(['database.default' => 'connection_that_does_not_exist']);

        try {
            $section = app(ProbeRunner::class)->run('database');
        } finally {
            config(['database.default' => $original]);
        }

        $this->assertTrue($section->isFailed());
        $this->assertSame(ProbeStatus::Fail, $section->status);
        $this->assertSame(__('messages.system_overview_probe_failed'), $section->items[0]->value);
    }

    public function test_an_unreadable_cache_entry_is_re_measured(): void
    {
        $this->actingAsOverviewAdmin();

        // Written by an older build of a probe: readable by the store, not by the DTO.
        Cache::put('system_overview.database', 'not-a-section', now()->addMinute());

        $this->get(route('admin.system-overview.index'))
            ->assertOk()
            ->assertSee('Database');
    }

    public function test_the_database_endpoint_is_never_exported(): void
    {
        $this->actingAsOverviewAdmin();

        $section = app(ProbeRunner::class)->run('database');

        $this->assertNotEmpty($section->exportableItems());
        $this->assertNotContains('Endpoint', array_map(
            fn ($item): string => $item->label,
            $section->exportableItems(),
        ));
    }

    public function test_the_database_section_measures_on_sqlite(): void
    {
        $this->actingAsOverviewAdmin();

        // The sqlite branch reads page_size and page_count, and those result rows are keyed
        // by the pragma name rather than by index. A failed section here means that read
        // broke again - the suite runs on sqlite, so nothing else would notice.
        $section = app(ProbeRunner::class)->warm('database');

        $this->assertFalse($section->isFailed());
        $this->assertContains('Footprint', array_map(
            fn ($item): string => $item->label,
            $section->items,
        ));
    }

    public function test_the_overview_does_not_render_configuration_secrets(): void
    {
        $this->actingAsOverviewAdmin();

        config(['database.connections.mysql.password' => 'super-secret-pass']);
        config(['mail.mailers.smtp.password' => 'smtp-secret-pass']);

        $this->get(route('admin.system-overview.index'))
            ->assertOk()
            ->assertDontSee('super-secret-pass')
            ->assertDontSee('smtp-secret-pass');
    }

    public function test_a_page_request_never_measures_a_heavy_section(): void
    {
        $this->actingAsOverviewAdmin();

        Cache::forget('system_overview.footprint');
        Cache::forget('system_overview.packages');

        $this->get(route('admin.system-overview.index'))->assertOk();

        // The file-system and lock-file probes are queued or asked for, never inline.
        $this->assertNull(Cache::get('system_overview.footprint'));
        $this->assertNull(Cache::get('system_overview.packages'));

        // Nor does the package table parse the lock file: it is lazy, so the shell
        // renders a placeholder and the rows are read by a request of their own.
        $this->assertNull(Cache::get('system_overview.packages.rows'));
    }

    public function test_an_unmeasured_heavy_section_offers_both_ways_to_measure(): void
    {
        $this->actingAsOverviewAdmin();

        Cache::forget('system_overview.packages');

        Livewire::test(SystemOverviewSection::class, ['probe' => 'packages'])
            ->assertSee('No measurement taken yet')
            ->assertSee('Measure now')
            ->assertSee('Warm in background')
            // Polls so a warm-up started elsewhere reaches the card without a reload.
            ->assertSee('wire:poll.4s', false);
    }

    public function test_a_measured_heavy_section_stops_polling(): void
    {
        $this->actingAsOverviewAdmin();

        Cache::forget('system_overview.packages');

        Livewire::test(SystemOverviewSection::class, ['probe' => 'packages'])
            ->call('measure')
            ->assertDontSee('wire:poll', false);
    }

    public function test_measuring_in_line_fills_the_section_and_its_cache(): void
    {
        $this->actingAsOverviewAdmin();

        Cache::forget('system_overview.packages');

        Livewire::test(SystemOverviewSection::class, ['probe' => 'packages'])
            ->call('measure')
            ->assertSee('Composer packages')
            ->assertDontSee('No measurement taken yet');

        $this->assertNotNull(Cache::get('system_overview.packages'));
    }

    public function test_warming_in_background_dispatches_a_job_for_that_section_only(): void
    {
        $this->actingAsOverviewAdmin();

        Queue::fake();
        Cache::forget('system_overview.footprint');

        Livewire::test(SystemOverviewSection::class, ['probe' => 'footprint'])
            ->call('queueWarmup')
            ->assertSee('Waiting for a worker');

        Queue::assertPushed(
            WarmSystemOverviewProbe::class,
            fn (WarmSystemOverviewProbe $job): bool => $job->probe === 'footprint',
        );
    }

    public function test_the_warm_job_measures_every_heavy_section(): void
    {
        $this->actingAsOverviewAdmin();

        Cache::forget('system_overview.footprint');
        Cache::forget('system_overview.packages');

        (new WarmSystemOverviewProbe)->handle(
            app(ProbeRegistry::class),
            app(ProbeRunner::class),
        );

        foreach (['footprint', 'packages'] as $key) {
            $this->assertNotNull(Cache::get('system_overview.'.$key), "Section [{$key}] was not warmed.");
        }
    }

    public function test_the_footprint_section_lists_the_size_of_every_cache_group(): void
    {
        $this->actingAsOverviewAdmin();

        $labels = array_map(
            fn (ProbeItem $item): string => $item->label,
            app(ProbeRunner::class)->warm('footprint')->items,
        );

        $this->assertContains('Cache · Company Settings', $labels);
        $this->assertContains('Cache · Application Version', $labels);
        $this->assertContains('Cache · Framework Cache', $labels);
    }

    public function test_the_queue_section_reports_throughput_and_workers(): void
    {
        $this->actingAsOverviewAdmin();

        $labels = array_map(
            fn (ProbeItem $item): string => $item->label,
            app(ProbeRunner::class)->warm('queue')->items,
        );

        $this->assertContains('Throughput (24h)', $labels);
        $this->assertContains('Workers', $labels);
    }

    public function test_a_section_that_is_not_registered_cannot_be_mounted(): void
    {
        $this->actingAsOverviewAdmin();

        Livewire::test(SystemOverviewSection::class, ['probe' => 'configuration'])
            ->assertStatus(404);
    }

    public function test_re_measuring_clears_the_sections_and_queues_the_heavy_ones(): void
    {
        $this->actingAsOverviewAdmin();

        Queue::fake();

        Cache::put('system_overview.runtime', ['stale' => true], now()->addMinute());

        $this->post(route('admin.system-overview.refresh'))->assertRedirect();

        $this->assertNull(Cache::get('system_overview.runtime'));

        Queue::assertPushed(
            WarmSystemOverviewProbe::class,
            fn (WarmSystemOverviewProbe $job): bool => $job->probe === null,
        );
    }

    public function test_the_package_table_appears_only_once_the_section_is_measured(): void
    {
        $this->actingAsOverviewAdmin();

        Cache::forget('system_overview.packages');

        Livewire::test(SystemOverviewSection::class, ['probe' => 'packages'])
            ->assertDontSee('Reading the package list');

        Livewire::test(SystemOverviewSection::class, ['probe' => 'packages'])
            ->call('measure')
            ->assertSee('Reading the package list');
    }

    public function test_the_package_table_searches_the_measured_rows(): void
    {
        $this->actingAsOverviewAdmin();

        Livewire::test(SystemOverviewPackages::class)
            ->assertSee('Package inventory')
            ->set('search', 'FRAMEWORK')
            ->assertSee('laravel/framework')
            ->set('search', 'nothing-named-this-anywhere')
            ->assertSee('No package matches that search.')
            ->call('clearSearch')
            ->assertDontSee('No package matches that search.');
    }

    public function test_the_package_table_filters_by_lock_file(): void
    {
        $this->actingAsOverviewAdmin();

        Livewire::test(SystemOverviewPackages::class)
            ->call('setSource', 'npm')
            ->assertSee('tailwindcss')
            ->assertDontSee('laravel/framework')
            ->call('setSource', 'monero')
            ->assertStatus(404);
    }

    public function test_the_package_table_pages_through_the_cached_rows(): void
    {
        $this->actingAsOverviewAdmin();

        $rows = app(PackageCatalog::class)->rows();
        $firstPageName = $rows[0]['name'];
        $secondPageName = $rows[15]['name'];

        $component = Livewire::test(SystemOverviewPackages::class)
            ->assertSee($firstPageName)
            ->call('gotoPage', 2)
            ->assertSee($secondPageName);

        $this->assertSame(2, $component->instance()->packages()->currentPage());
    }

    public function test_a_user_without_the_permission_cannot_read_the_package_table(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Admin', 'web'));

        $this->actingAs($user);

        Livewire::test(SystemOverviewPackages::class)->assertStatus(403);
    }
}
