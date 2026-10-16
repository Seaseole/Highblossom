<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\CompanySetting;
use App\Models\User;
use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeRegistry;
use App\Support\SystemOverview\ProbeRunner;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Cover the posture section: the configuration verdicts it derives from settings and
 * Fortify, the fact that it is cheap enough to measure inline, and the one line that
 * must never reach an export.
 */
class SystemOverviewPostureTest extends TestCase
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
     * The posture lines, keyed by label, after re-measuring.
     *
     * @return array<string, ProbeItem>
     */
    private function postureItems(): array
    {
        app(ProbeRunner::class)->forgetAll();

        return collect(app(ProbeRunner::class)->run('posture')->items)
            ->keyBy(fn (ProbeItem $item): string => $item->label)
            ->all();
    }

    public function test_the_overview_lists_the_posture_section(): void
    {
        $this->actingAsOverviewAdmin();

        $this->get(route('admin.system-overview.index'))
            ->assertOk()
            ->assertSee('Security Posture')
            ->assertSee('Two-factor coverage');
    }

    public function test_the_registration_and_verification_verdicts_follow_the_settings(): void
    {
        $this->actingAsOverviewAdmin();

        CompanySetting::set('enable_registration', '1', 'boolean');
        CompanySetting::set('require_email_verification', '0', 'boolean');

        $items = $this->postureItems();

        $this->assertSame('Open', $items['Registration']->value);
        $this->assertSame('warn', $items['Registration']->status->value);
        $this->assertSame('Optional', $items['Email verification']->value);
        $this->assertSame('warn', $items['Email verification']->status->value);

        CompanySetting::set('enable_registration', '0', 'boolean');
        CompanySetting::set('require_email_verification', '1', 'boolean');

        $items = $this->postureItems();

        $this->assertSame('Closed', $items['Registration']->value);
        $this->assertSame('ok', $items['Registration']->status->value);
        $this->assertSame('Required', $items['Email verification']->value);
        $this->assertSame('ok', $items['Email verification']->status->value);
    }

    public function test_a_missing_setting_row_reads_as_the_default(): void
    {
        $this->actingAsOverviewAdmin();

        // The toggles are absent from company_settings on a fresh install, and the
        // middleware treats that as off; the card has to agree.
        CompanySetting::query()->delete();

        $items = $this->postureItems();

        $this->assertSame('Closed', $items['Registration']->value);
        $this->assertSame('Optional', $items['Email verification']->value);
    }

    public function test_posture_is_measured_inline_and_never_queued(): void
    {
        $this->actingAsOverviewAdmin();

        $registry = app(ProbeRegistry::class);

        $this->assertContains('posture', $registry->keysByCost(ProbeCost::Light));
        $this->assertNotContains('posture', $registry->keysByCost(ProbeCost::Heavy));

        Cache::forget('system_overview.posture');

        $this->get(route('admin.system-overview.index'))->assertOk();

        $this->assertNotNull(Cache::get('system_overview.posture'), 'A light section is measured while the page renders.');
    }

    public function test_the_forwarded_header_line_is_never_exportable(): void
    {
        $this->actingAsOverviewAdmin();

        $items = $this->postureItems();

        $this->assertArrayHasKey('Trusted proxies', $items);
        $this->assertTrue($items['Trusted proxies']->sensitive, 'The proxy ranges describe the internal network.');
    }
}
