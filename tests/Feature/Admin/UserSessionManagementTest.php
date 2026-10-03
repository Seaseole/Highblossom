<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\DeviceType;
use App\Enums\LoginMethod;
use App\Enums\SessionEndReason;
use App\Models\User;
use App\Models\UserSession;
use App\Support\DeviceInformation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Covers the administrator session screens and their sign-out actions.
 */
class UserSessionManagementTest extends TestCase
{
    use RefreshDatabase;

    private const DESKTOP = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36';

    private const MOBILE = 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Mobile Safari/537.36';

    private const IPHONE = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1';

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('session.driver', 'database');
    }

    public function test_the_session_pages_require_the_session_permission(): void
    {
        Permission::findOrCreate('access admin panel', 'web');
        Permission::findOrCreate('manage user sessions', 'web');

        $staff = User::factory()->create();
        $staff->givePermissionTo('access admin panel');

        $this->actingAs($staff)->get(route('admin.sessions.index'))->assertForbidden();
    }

    public function test_an_admin_sees_sessions_from_every_account(): void
    {
        $admin = $this->actingAsSessionsAdmin();
        $this->record(User::factory()->create(['email' => 'painter@example.test']), 'a1111111111111111111111111111111111111111', self::MOBILE);
        $this->record($admin, 'b222222222222222222222222222222222222222', self::DESKTOP);

        $this->get(route('admin.sessions.index'))
            ->assertOk()
            ->assertSee('painter@example.test')
            ->assertSee('Windows')
            ->assertSee('Sessions & devices');
    }

    public function test_the_status_filter_narrows_the_ledger(): void
    {
        $admin = $this->actingAsSessionsAdmin();
        $this->record($admin, 'c333333333333333333333333333333333333333', self::DESKTOP);
        $ended = $this->record($admin, 'd444444444444444444444444444444444444444', self::IPHONE);

        $ended->forceFill(['ended_at' => now()->subHour(), 'end_reason' => SessionEndReason::LOGOUT])->save();

        $this->get(route('admin.sessions.index', ['status' => 'active']))
            ->assertOk()
            ->assertSee('Windows')
            ->assertDontSee('iOS')
            ->assertDontSee('Signed out');

        $this->get(route('admin.sessions.index', ['status' => 'ended']))
            ->assertOk()
            ->assertSee('iOS')
            ->assertSee('Signed out')
            ->assertDontSee('Windows');
    }

    public function test_the_device_filter_only_returns_matching_devices(): void
    {
        $admin = $this->actingAsSessionsAdmin();
        $this->record($admin, 'e555555555555555555555555555555555555555', self::DESKTOP);
        $this->record($admin, 'f666666666666666666666666666666666666666', self::MOBILE);

        $this->get(route('admin.sessions.index', ['device' => 'mobile']))
            ->assertOk()
            ->assertSee('Android')
            ->assertDontSee('Windows');
    }

    public function test_the_search_filter_matches_an_account_email(): void
    {
        $admin = $this->actingAsSessionsAdmin();
        $roofer = User::factory()->create(['name' => 'Roofer Ron', 'email' => 'ron@example.test']);
        $this->record($roofer, 'g7777777777777777777777777777777777777777', self::DESKTOP);
        $this->record($admin, 'h8888888888888888888888888888888888888888', self::DESKTOP);

        $this->get(route('admin.sessions.index', ['search' => 'ron@example.test']))
            ->assertOk()
            ->assertSee('Roofer Ron')
            ->assertDontSee($admin->email);
    }

    public function test_an_invalid_filter_value_is_rejected(): void
    {
        $this->actingAsSessionsAdmin();

        $this->get(route('admin.sessions.index', ['status' => 'nonsense']))
            ->assertSessionHasErrors('status');
    }

    public function test_the_per_user_page_only_lists_that_account(): void
    {
        $admin = $this->actingAsSessionsAdmin();
        $target = User::factory()->create(['name' => 'Glazing Gia', 'email' => 'gia@example.test']);
        $this->record($target, 'i9999999999999999999999999999999999999999', self::DESKTOP);
        $this->record($admin, 'j0000000000000000000000000000000000000000', self::DESKTOP);

        $this->get(route('admin.sessions.user', $target))
            ->assertOk()
            ->assertSee('Glazing Gia')
            ->assertDontSee($admin->email);
    }

    public function test_the_current_device_is_badged_and_offers_no_sign_out(): void
    {
        $admin = $this->actingAsSessionsAdmin();
        $painter = User::factory()->create(['email' => 'painter@example.test']);
        $foreign = $this->record($painter, 'n4444444444444444444444444444444444444444', self::MOBILE);

        $this->get(route('admin.sessions.index'))->assertOk();
        $current = UserSession::where('user_id', $admin->getKey())->sole();

        $response = $this->withCookies([$this->sessionCookie() => $current->session_id])
            ->get(route('admin.sessions.index'))
            ->assertOk();

        // Only the other device is revocable; the row being used to render the page
        // is labelled instead.
        $response
            ->assertSee('This device')
            ->assertSee('/admin/sessions/'.$foreign->getKey().'/revoke')
            ->assertDontSee('/admin/sessions/'.$current->getKey().'/revoke');
    }

    public function test_an_admin_can_sign_out_a_single_session(): void
    {
        $admin = $this->actingAsSessionsAdmin();
        $target = User::factory()->create();
        $session = $this->record($target, 'k1111111111111111111111111111111111111111', self::DESKTOP);
        $this->frameworkSession('k1111111111111111111111111111111111111111', $target);

        $this->post(route('admin.sessions.revoke', $session))->assertRedirect();

        $session->refresh();

        $this->assertSame(SessionEndReason::REVOKED_BY_ADMIN, $session->end_reason);
        $this->assertSame($admin->getKey(), $session->revoked_by);
        $this->assertDatabaseMissing(config('session.table'), ['id' => 'k1111111111111111111111111111111111111111']);
    }

    public function test_an_admin_cannot_sign_out_the_session_in_use(): void
    {
        $admin = $this->actingAsSessionsAdmin();

        $this->get(route('admin.sessions.index'))->assertOk();
        $current = UserSession::where('user_id', $admin->getKey())->sole();

        $this->withCookies([$this->sessionCookie() => $current->session_id])
            ->post(route('admin.sessions.revoke', $current))
            ->assertSessionHasErrors('session');

        $this->assertNull($current->fresh()->ended_at);
    }

    public function test_an_admin_can_sign_out_every_session_of_an_account(): void
    {
        $admin = $this->actingAsSessionsAdmin();
        $target = User::factory()->create();
        $first = $this->record($target, 'l2222222222222222222222222222222222222222', self::DESKTOP);
        $second = $this->record($target, 'm3333333333333333333333333333333333333333', self::MOBILE);

        $this->post(route('admin.sessions.user.revoke', $target))->assertRedirect();

        $this->assertSame(SessionEndReason::REVOKED_BY_ADMIN, $first->fresh()->end_reason);
        $this->assertSame(SessionEndReason::REVOKED_BY_ADMIN, $second->fresh()->end_reason);
    }

    public function test_signing_out_an_account_spares_the_admins_own_session(): void
    {
        $admin = $this->actingAsSessionsAdmin();

        $this->get(route('admin.sessions.index'))->assertOk();
        $current = UserSession::where('user_id', $admin->getKey())->sole();

        $this->withCookies([$this->sessionCookie() => $current->session_id])
            ->post(route('admin.sessions.user.revoke', $admin))
            ->assertRedirect();

        $this->assertNull($current->fresh()->ended_at);
    }

    /**
     * Session cookie name the browser would echo back.
     */
    private function sessionCookie(): string
    {
        return (string) config('session.cookie');
    }

    /**
     * Record one open ledger row for an account, parsed the way the tracker parses it.
     */
    private function record(User $user, string $sessionId, string $userAgent): UserSession
    {
        $device = DeviceInformation::fromUserAgent($userAgent);
        $ip = $device->deviceType === DeviceType::MOBILE ? '198.51.100.30' : '203.0.113.19';

        return UserSession::create([
            'user_id' => $user->getKey(),
            'session_id' => $sessionId,
            'ip_address' => $ip,
            'last_ip_address' => $ip,
            'user_agent' => $userAgent,
            'login_method' => LoginMethod::PASSWORD,
            'login_at' => now()->subHours(2),
            'last_seen_at' => now()->subMinutes(5),
        ] + $device->toColumnArray());
    }

    /**
     * Insert a matching row in the framework session store so revocation has something to delete.
     */
    private function frameworkSession(string $sessionId, User $user): void
    {
        DB::table(config('session.table'))->insert([
            'id' => $sessionId,
            'user_id' => $user->getKey(),
            'payload' => '',
            'last_activity' => time(),
        ]);
    }

    /**
     * Authenticate an administrator allowed to manage sessions.
     */
    private function actingAsSessionsAdmin(): User
    {
        Permission::findOrCreate('access admin panel', 'web');
        Permission::findOrCreate('manage user sessions', 'web');

        $user = User::factory()->create();
        $user->givePermissionTo(['access admin panel', 'manage user sessions']);
        $this->actingAs($user);

        return $user;
    }
}
