<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Enums\DeviceType;
use App\Enums\LoginMethod;
use App\Enums\SessionEndReason;
use App\Models\User;
use App\Models\UserSession;
use App\Services\UserSessionService;
use DateTimeInterface;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\SessionGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Guards the `user_sessions` ledger that powers the device lists.
 *
 * Runs on the database session driver rather than the suite default because the
 * driver is what gives a session id a real row to be compared against.
 */
class UserSessionTrackingTest extends TestCase
{
    use RefreshDatabase;

    private const SAFARI_MAC = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Safari/605.1.15';

    private const IP = '203.0.113.19';

    private const SESSION_ID = 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0';

    private const OTHER_SESSION_ID = 'z9y8x7w6v5u4t3s2r1q0p9o8i7n6m5l4k3j2i1h0';

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('session.driver', 'database');
    }

    public function test_login_records_the_session_id_the_user_was_actually_issued(): void
    {
        $user = $this->signIn();

        $ledger = UserSession::sole();
        $nativeIds = DB::table(config('session.table'))->pluck('id')->all();

        // Fortify fires Login and only then regenerates the session id. A row keyed
        // to the pre-login id would point at a session that is never persisted, so
        // the ledger must match the single framework row left behind.
        $this->assertCount(1, $nativeIds);
        $this->assertSame($nativeIds[0], $ledger->session_id);
        $this->assertSame($user->getKey(), $ledger->user_id);
    }

    public function test_login_stores_the_parsed_device_and_method(): void
    {
        $this->signIn();

        $ledger = UserSession::sole();

        $this->assertSame(LoginMethod::PASSWORD, $ledger->login_method);
        $this->assertSame('desktop', $ledger->device_type->value);
        $this->assertSame('macOS', $ledger->platform);
        $this->assertSame('Safari', $ledger->browser);
        $this->assertSame('17.4', $ledger->browser_version);
        $this->assertSame('203.0.113.19', $ledger->ip_address);
        $this->assertFalse($ledger->remembered);
        $this->assertTrue($ledger->isActive());
    }

    public function test_remembered_login_is_flagged(): void
    {
        $user = User::factory()->create();

        $this->withDevice()->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
            'remember' => 'on',
        ]);

        $this->assertTrue(UserSession::sole()->remembered);
    }

    public function test_a_session_recovered_from_the_remember_cookie_is_flagged_as_a_remembered_device(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo($this->adminPanelAccess());
        $user->forceFill(['remember_token' => 'remember-token-value'])->save();

        // Fortify fires no Login event when the guard recalls a user from the
        // remember cookie, so the ledger has to read the method off the guard.
        $recaller = $user->getKey()
            .'|'.$user->remember_token
            .'|'.Auth::guard('web')->hashPasswordForCookie($user->getAuthPassword());

        $this->withDevice()
            ->withCookies(['remember_web_'.sha1(SessionGuard::class) => $recaller])
            ->get(route('admin.profile.index'))
            ->assertOk();

        $ledger = UserSession::sole();

        $this->assertSame($user->getKey(), $ledger->user_id);
        $this->assertSame(LoginMethod::REMEMBER, $ledger->login_method);
        $this->assertTrue($ledger->remembered);
    }

    public function test_a_request_that_reuses_a_recorded_session_does_not_open_a_second_row(): void
    {
        $user = $this->actingAsProfileUser();

        $this->seedSession($user, self::SESSION_ID);

        // The test client does not carry the session cookie between requests, so the
        // follow-up names the id the ledger already holds to stay in the same session.
        $this->withDevice()
            ->withCookies([config('session.cookie') => self::SESSION_ID])
            ->get(route('admin.profile.index'))
            ->assertOk();

        $ledger = UserSession::sole();

        $this->assertSame(self::SESSION_ID, $ledger->session_id);
        $this->assertTrue($ledger->isActive());
        $this->assertNull($ledger->end_reason);
    }

    public function test_activity_is_written_once_the_touch_throttle_window_has_passed(): void
    {
        $user = User::factory()->create();
        $ledger = $this->seedSession($user, self::SESSION_ID, now()->subSeconds(30));

        $this->service()->track($user, $this->ledgerRequest(self::SESSION_ID));

        // Still inside the window: the idle timestamp is left alone.
        $this->assertSame(
            $ledger->last_seen_at->toDateTimeString(),
            $ledger->fresh()->last_seen_at->toDateTimeString()
        );

        $ledger->forceFill(['last_seen_at' => now()->subMinutes(5)])->save();

        $this->service()->track($user, $this->ledgerRequest(self::SESSION_ID));

        $this->assertNotSame($ledger->last_seen_at->toDateTimeString(), $ledger->fresh()->last_seen_at->toDateTimeString());
    }

    public function test_a_new_ip_address_is_recorded_immediately(): void
    {
        $user = User::factory()->create();
        $ledger = $this->seedSession($user, self::SESSION_ID);

        $this->service()->track($user, $this->ledgerRequest(self::SESSION_ID, '198.51.100.24'));

        $ledger->refresh();

        $this->assertSame('198.51.100.24', $ledger->last_ip_address);
        $this->assertSame(self::IP, $ledger->ip_address);
    }

    public function test_a_session_idle_past_the_framework_lifetime_is_no_longer_active(): void
    {
        Config::set('session.lifetime', 120);

        $user = User::factory()->create();
        $ledger = $this->seedSession($user, self::SESSION_ID, now()->subMinutes(121));

        $this->assertFalse($ledger->isActive());
        $this->assertSame(1, UserSession::stale()->count());
        $this->assertSame(0, UserSession::active()->count());
    }

    public function test_the_logout_event_listener_closes_the_matching_ledger_row(): void
    {
        $user = $this->actingAsProfileUser();

        $this->get(route('admin.profile.index'))->assertOk();

        // The tracker recorded this request's session id and the guard dispatches
        // Logout while that id is still the live one, so the listener can resolve
        // the row it belongs to.
        $ledger = UserSession::sole();

        event(new Logout('web', $user));

        $ledger->refresh();

        $this->assertSame(SessionEndReason::LOGOUT, $ledger->end_reason);
        $this->assertNotNull($ledger->ended_at);
        $this->assertFalse($ledger->isActive());
    }

    public function test_a_logout_only_closes_the_signing_out_sessions_row(): void
    {
        $user = $this->actingAsProfileUser();

        $this->get(route('admin.profile.index'))->assertOk();

        $current = UserSession::sole();

        $other = $this->seedSession($user, 'other-device-session');

        event(new Logout('web', $user));

        $this->assertSame(SessionEndReason::LOGOUT, $current->fresh()->end_reason);
        $this->assertNull($other->fresh()->ended_at);
    }

    public function test_closing_one_session_leaves_the_same_users_other_devices_alone(): void
    {
        $user = User::factory()->create();

        $first = $this->seedSession($user, 'session-one');
        $second = $this->seedSession($user, 'session-two');

        $this->service()->closeSession('session-one', SessionEndReason::REVOKED_BY_USER);

        $this->assertNotNull($first->fresh()->ended_at);
        $this->assertNull($second->fresh()->ended_at);
    }

    public function test_revoking_other_sessions_spares_the_exempt_session(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();

        $kept = $this->seedSession($user, 'session-kept');
        $dropped = $this->seedSession($user, 'session-dropped');
        $this->frameworkSession('session-dropped', $user);

        $revoked = $this->service()->revokeOthersFor($user, 'session-kept', SessionEndReason::REVOKED_BY_ADMIN, $admin);

        $this->assertSame(1, $revoked);
        $this->assertNull($kept->fresh()->ended_at);
        $this->assertSame(SessionEndReason::REVOKED_BY_ADMIN, $dropped->fresh()->end_reason);
        $this->assertSame($admin->getKey(), $dropped->fresh()->revoked_by);
        $this->assertDatabaseMissing(config('session.table'), ['id' => 'session-dropped']);
    }

    public function test_changing_a_password_revokes_the_users_other_sessions(): void
    {
        $user = $this->actingAsProfileUser();
        $otherDevice = $this->seedSession($user, 'other-device-session');
        $this->frameworkSession('other-device-session', $user);

        $this->put(route('admin.profile.password.update'), [
            'current_password' => 'password',
            'password' => 'new-password-here',
            'password_confirmation' => 'new-password-here',
        ])->assertSessionHasNoErrors();

        $otherDevice->refresh();

        $this->assertSame(SessionEndReason::PASSWORD_CHANGED, $otherDevice->end_reason);
        $this->assertDatabaseMissing(config('session.table'), ['id' => 'other-device-session']);
    }

    public function test_password_change_revocation_can_be_switched_off(): void
    {
        Config::set('user-sessions.revoke_on_password_change', false);

        $user = $this->actingAsProfileUser();
        $otherDevice = $this->seedSession($user, self::OTHER_SESSION_ID);

        $this->put(route('admin.profile.password.update'), [
            'current_password' => 'password',
            'password' => 'new-password-here',
            'password_confirmation' => 'new-password-here',
        ])->assertSessionHasNoErrors();

        $this->assertNull($otherDevice->fresh()->ended_at);
    }

    public function test_prune_closes_sessions_the_framework_let_lapse(): void
    {
        $user = User::factory()->create();

        $lapsed = $this->seedSession($user, self::SESSION_ID, now()->subMinutes(180));
        $live = $this->seedSession($user, self::OTHER_SESSION_ID);

        $result = $this->service()->prune();

        $this->assertSame(1, $result['closed']);
        $this->assertSame(0, $result['purged']);

        $lapsed->refresh();

        $this->assertSame(SessionEndReason::EXPIRED, $lapsed->end_reason);
        // Closed as of the last moment it was seen, not when the command ran.
        $this->assertSame($lapsed->last_seen_at->toDateTimeString(), $lapsed->ended_at->toDateTimeString());
        $this->assertNull($live->fresh()->ended_at);
    }

    public function test_prune_drops_history_older_than_the_retention_window(): void
    {
        Config::set('user-sessions.history_retention_days', 90);

        $user = User::factory()->create();

        $expired = $this->endedSession($user, self::SESSION_ID, now()->subDays(91));
        $recent = $this->endedSession($user, self::OTHER_SESSION_ID, now()->subDays(30));

        $result = $this->service()->prune();

        $this->assertSame(1, $result['purged']);
        $this->assertDatabaseMissing('user_sessions', ['id' => $expired->getKey()]);
        $this->assertDatabaseHas('user_sessions', ['id' => $recent->getKey()]);
    }

    public function test_the_prune_command_reports_what_it_removed(): void
    {
        $user = User::factory()->create();
        $this->seedSession($user, self::SESSION_ID, now()->subMinutes(180));

        $this->artisan('sessions:prune')
            ->expectsOutputToContain('Closed 1 lapsed session(s).')
            ->assertExitCode(0);

        $this->assertSame(SessionEndReason::EXPIRED, UserSession::sole()->end_reason);
    }

    public function test_the_prune_command_rejects_a_non_positive_retention_window(): void
    {
        $this->artisan('sessions:prune', ['--days' => '0'])->assertExitCode(1);

        $this->assertSame(0, UserSession::count());
    }

    /**
     * Post valid credentials through the login form as the desktop Safari device.
     */
    private function signIn(?User $user = null): User
    {
        $user ??= User::factory()->create();

        $this->withDevice()->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect();

        return $user;
    }

    /**
     * Record an open ledger row for a user on a known session id.
     */
    private function seedSession(User $user, string $sessionId, ?DateTimeInterface $lastSeenAt = null): UserSession
    {
        $lastSeenAt ??= now()->subMinutes(5);

        return UserSession::create([
            'user_id' => $user->getKey(),
            'session_id' => $sessionId,
            'ip_address' => self::IP,
            'last_ip_address' => self::IP,
            'user_agent' => self::SAFARI_MAC,
            'device_type' => DeviceType::DESKTOP->value,
            'platform' => 'macOS',
            'browser' => 'Safari',
            'browser_version' => '17.4',
            'login_method' => LoginMethod::PASSWORD->value,
            'login_at' => $lastSeenAt,
            'last_seen_at' => $lastSeenAt,
        ]);
    }

    /**
     * Record an already-closed ledger row for a user on a known session id.
     */
    private function endedSession(User $user, string $sessionId, DateTimeInterface $endedAt): UserSession
    {
        $session = $this->seedSession($user, $sessionId, $endedAt);

        $session->forceFill([
            'ended_at' => $endedAt,
            'end_reason' => SessionEndReason::LOGOUT,
        ])->save();

        return $session;
    }

    /**
     * Build a request being served under a known session id, device and address.
     */
    private function ledgerRequest(string $sessionId, string $ip = self::IP): Request
    {
        $request = Request::create(route('admin.profile.index'), 'GET', server: [
            'REMOTE_ADDR' => $ip,
            'HTTP_USER_AGENT' => self::SAFARI_MAC,
        ]);

        $store = new Store((string) config('session.cookie'), new ArraySessionHandler(120), 120);
        $store->setId($sessionId);

        $request->setLaravelSession($store);

        return $request;
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
     * Resolve the ledger service from the container.
     */
    private function service(): UserSessionService
    {
        return $this->app->make(UserSessionService::class);
    }

    /**
     * Authenticate a user that can reach the admin profile routes.
     */
    private function actingAsProfileUser(): User
    {
        $user = User::factory()->create();
        $user->givePermissionTo($this->adminPanelAccess());
        $this->actingAs($user);

        return $user;
    }

    /**
     * Ensure the admin panel permission exists and return it.
     */
    private function adminPanelAccess(): Permission
    {
        return Permission::findOrCreate('access admin panel', 'web');
    }

    /**
     * Send the request as a desktop Safari device from a fixed address.
     */
    private function withDevice(): self
    {
        return $this->withServerVariables([
            'HTTP_USER_AGENT' => self::SAFARI_MAC,
            'REMOTE_ADDR' => self::IP,
        ]);
    }
}
