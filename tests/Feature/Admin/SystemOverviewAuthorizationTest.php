<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Jobs\WarmSystemOverviewProbe;
use App\Livewire\SystemOverviewSection;
use App\Models\User;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeRegistry;
use App\Support\SystemOverview\ProbeRunner;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover who may see which line of the overview: the stored measurement is the same for
 * every reader, ability is applied when it is read, and the card components refuse
 * readers who were never granted the page.
 */
class SystemOverviewAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed roles and act as a user that may view the overview.
     */
    private function actingAsOverviewAdmin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $this->actingAs($user);

        return $user;
    }

    /**
     * Act as a user who may read the overview but may not see the environment.
     */
    private function actingAsOverviewViewer(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $viewer = Role::findOrCreate('Overview Viewer', 'web');
        $viewer->givePermissionTo(['access admin panel', 'view dashboard', 'view system overview']);

        $user = User::factory()->create();
        $user->assignRole($viewer);

        $this->actingAs($user);

        return $user;
    }

    /**
     * @param list<ProbeItem> $items
     *
     * @return list<string>
     */
    private function labels(array $items): array
    {
        return array_map(static fn (ProbeItem $item): string => $item->label, $items);
    }

    public function test_an_environment_value_is_cached_once_and_revealed_by_ability(): void
    {
        $this->actingAsOverviewAdmin();
        $this->assertContains('Endpoint', $this->labels(app(ProbeRunner::class)->warm('database')->items));

        // One entry serves every reader: the gated line stays in the stored measurement,
        // with the ability it needs recorded alongside it.
        $stored = Cache::get('system_overview.database');
        $endpoint = collect($stored['items'])->firstWhere('label', 'Endpoint');

        $this->assertNotNull($endpoint, 'The cache must hold the full measurement.');
        $this->assertNotEmpty($endpoint['value']);
        $this->assertSame('manage environment', $endpoint['requires']);
        $this->assertTrue($endpoint['sensitive']);

        // The next reader may see the page but not the environment.
        app()->forgetInstance(ProbeRunner::class);
        $this->actingAsOverviewViewer();

        $section = app(ProbeRunner::class)->run('database');

        $this->assertNotContains('Endpoint', $this->labels($section->items));
        $this->assertContains('Connection', $this->labels($section->items));

        $this->get(route('admin.system-overview.index'))
            ->assertOk()
            ->assertDontSee('Endpoint')
            ->assertSee('Database');
    }

    public function test_the_granted_reader_still_sees_the_environment_value(): void
    {
        $this->actingAsOverviewAdmin();
        app(ProbeRunner::class)->warm('database');

        app()->forgetInstance(ProbeRunner::class);

        $this->get(route('admin.system-overview.index'))
            ->assertOk()
            ->assertSee('Endpoint');
    }

    public function test_a_warm_up_with_no_reader_still_stores_the_gated_line(): void
    {
        Cache::forget('system_overview.database');

        // The scheduled job measures with nobody authenticated, which must not quietly
        // drop the line for the readers who are allowed to see it.
        (new WarmSystemOverviewProbe('database'))
            ->handle(app(ProbeRegistry::class), app(ProbeRunner::class));

        $stored = Cache::get('system_overview.database');

        $this->assertContains('Endpoint', collect($stored['items'])->pluck('label')->all());

        app()->forgetInstance(ProbeRunner::class);
        $this->actingAsOverviewAdmin();

        $this->assertContains('Endpoint', $this->labels(app(ProbeRunner::class)->run('database')->items));
    }

    public function test_the_card_refuses_a_reader_without_the_overview_ability(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->actingAs(User::factory()->create());

        Livewire::test(SystemOverviewSection::class, ['probe' => 'packages'])->assertStatus(403);
    }

    public function test_the_card_still_serves_a_reader_with_the_ability(): void
    {
        $this->actingAsOverviewAdmin();

        Livewire::test(SystemOverviewSection::class, ['probe' => 'packages'])
            ->assertOk()
            ->assertSee('Dependencies');
    }
}
