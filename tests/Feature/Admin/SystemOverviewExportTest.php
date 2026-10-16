<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeRegistry;
use App\Support\SystemOverview\ProbeRunner;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover the overview export: that it needs its own permission, that it withholds what
 * a probe marked sensitive, that it never measures a heavy section, and that every
 * download leaves an audit record behind.
 */
class SystemOverviewExportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed roles and act as a user that may view and export the overview.
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
     * Decode the download body.
     *
     * @return array<string, mixed>
     */
    private function payloadOf(string $content): array
    {
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_reading_the_overview_does_not_allow_an_export(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $viewer = Role::findOrCreate('Overview Viewer', 'web');
        $viewer->givePermissionTo(['access admin panel', 'view dashboard', 'view system overview']);

        $user = User::factory()->create();
        $user->assignRole($viewer);

        $this->actingAs($user)->get(route('admin.system-overview.index'))->assertOk();
        $this->actingAs($user)->post(route('admin.system-overview.export'))->assertForbidden();
    }

    public function test_a_guest_is_redirected_from_the_export(): void
    {
        $this->post(route('admin.system-overview.export'))->assertRedirect(route('login'));
    }

    public function test_the_export_downloads_json_with_every_section(): void
    {
        $this->actingAsOverviewAdmin();

        $response = $this->post(route('admin.system-overview.export'))->assertOk();

        $response->assertHeaderContains('Content-Type', 'application/json');
        $response->assertHeaderContains('Content-Disposition', 'attachment; filename="system-overview-');

        $payload = $this->payloadOf($response->getContent());

        $this->assertSame(['generated_at', 'application', 'environment', 'withheld_lines', 'sections'], array_keys($payload));
        $this->assertCount(count(app(ProbeRegistry::class)->keys()), $payload['sections']);
        $this->assertNotEmpty($payload['sections'][0]['items']);
    }

    public function test_the_export_withholds_every_line_marked_sensitive(): void
    {
        $this->actingAsOverviewAdmin();

        $onScreen = array_map(
            static fn (ProbeItem $item): string => $item->label,
            app(ProbeRunner::class)->run('posture')->items,
        );

        // The proxy line is on the page for this reader, so the file has to be the
        // thing that drops it. Whether the database endpoint appears at all depends on
        // the environment gate, so only its absence from the payload is asserted below.
        $this->assertContains('Trusted proxies', $onScreen);

        $payload = $this->payloadOf($this->post(route('admin.system-overview.export'))->getContent());

        $labels = collect($payload['sections'])->flatMap(
            fn (array $section): array => array_column($section['items'], 'label'),
        );

        $this->assertNotContains('Trusted proxies', $labels->all());
        $this->assertNotContains('Endpoint', $labels->all());
        $this->assertGreaterThanOrEqual(1, $payload['withheld_lines']);
        $this->assertContains('Registration', $labels->all());
    }

    public function test_the_export_carries_no_configured_secrets(): void
    {
        $this->actingAsOverviewAdmin();

        config([
            'database.connections.mysql.password' => 'db-s3cr3t-from-config',
            'mail.mailers.smtp.password' => 'smtp-s3cr3t-from-config',
        ]);

        $body = $this->post(route('admin.system-overview.export'))->assertOk()->getContent();

        $this->assertStringNotContainsString('db-s3cr3t-from-config', $body);
        $this->assertStringNotContainsString('smtp-s3cr3t-from-config', $body);
    }

    public function test_an_export_never_measures_a_heavy_section(): void
    {
        $this->actingAsOverviewAdmin();

        Cache::forget('system_overview.footprint');
        Cache::forget('system_overview.packages');
        Cache::forget('system_overview.packages.rows');

        $payload = $this->payloadOf($this->post(route('admin.system-overview.export'))->assertOk()->getContent());

        $sections = collect($payload['sections'])->keyBy('key');

        $this->assertSame('pending', $sections['footprint']['status']);
        $this->assertSame('pending', $sections['packages']['status']);

        $this->assertNull(Cache::get('system_overview.footprint'));
        $this->assertNull(Cache::get('system_overview.packages'));
        $this->assertNull(Cache::get('system_overview.packages.rows'));
    }

    public function test_the_export_is_recorded_in_the_audit_trail(): void
    {
        $admin = $this->actingAsOverviewAdmin();

        $this->post(route('admin.system-overview.export'))->assertOk();

        $row = DB::table('audit_activity_logs')
            ->where('activity', 'Export System Overview')
            ->latest('id')
            ->first();

        $this->assertNotNull($row, 'An export that leaves the server must leave a record.');
        $this->assertSame($admin->id, $row->user_id);
        $this->assertStringContainsString('system-overview/export', (string) $row->url);

        $properties = json_decode((string) $row->properties, true);

        $this->assertArrayHasKey('sections', $properties);
        $this->assertArrayHasKey('withheld_lines', $properties);
        $this->assertGreaterThanOrEqual(1, $properties['withheld_lines']);
    }

    public function test_the_export_serves_the_same_sections_the_page_shows(): void
    {
        $this->actingAsOverviewAdmin();

        $payload = $this->payloadOf($this->post(route('admin.system-overview.export'))->getContent());

        $keys = array_column($payload['sections'], 'key');

        $this->assertSame(app(ProbeRegistry::class)->keys(), $keys);
    }
}
