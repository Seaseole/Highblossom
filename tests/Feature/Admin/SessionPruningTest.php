<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\DeviceType;
use App\Enums\LoginMethod;
use App\Enums\SessionEndReason;
use App\Models\CompanySetting;
use App\Models\User;
use App\Models\UserSession;
use App\Services\SessionManager;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover session reconciliation: what the overview card counts as live, how the
 * prune treats expired, orphaned and idle sessions, the dry run, and the
 * guarantee that the session doing the pruning is never the one pruned.
 *
 * Runs on the database session driver because that is the driver whose rows the
 * sweep deletes; on any other driver there is no framework table to reconcile.
 */
class SessionPruningTest extends TestCase
{
    use RefreshDatabase;

    private const LIVE_SESSION_ID = 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0';

    private const DEAD_SESSION_ID = 'z9y8x7w6v5u4t3s2r1q0p9o8i7n6m5l4k3j2i1h0';

    private const GUEST_SESSION_ID = 'g1h2i3j4k5l6m7n8o9p0q1r2s3t4u5v6w7x8y9z0';

    private const USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Safari/605.1.15';

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('session.driver', 'database');
        Config::set('session.lifetime', 120);
        Config::set('user-sessions.orphan_grace_minutes', 10);
        Config::set('user-sessions.history_retention_days', 90);
    }

    public function test_counts_separate_valid_expired_guest_and_authenticated_rows(): void
    {
        $user = User::factory()->create();

        $this->frameworkSession(self::LIVE_SESSION_ID, $user, ageMinutes: 5);
        $this->frameworkSession(self::GUEST_SESSION_ID, null, ageMinutes: 5);
        $this->frameworkSession(self::DEAD_SESSION_ID, null, ageMinutes: 180);

        $counts = $this->manager()->counts();

        $this->assertSame(3, $counts['framework_total']);
        $this->assertSame(2, $counts['framework_valid']);
        $this->assertSame(1, $counts['framework_authenticated']);
        $this->assertSame(1, $counts['framework_guest']);
        $this->assertSame(1, $counts['framework_stale']);
        // A guest row is not a user, so it must not inflate the distinct count.
        $this->assertSame(1, $counts['distinct_users']);
    }

    public function test_prune_deletes_expired_framework_rows_and_keeps_live_ones(): void
    {
        $user = User::factory()->create();

        $this->frameworkSession(self::LIVE_SESSION_ID, $user, ageMinutes: 5);
        $this->frameworkSession(self::DEAD_SESSION_ID, $user, ageMinutes: 180);

        $result = $this->manager()->prune();

        $this->assertSame(1, $result['deleted']);
        $this->assertDatabaseHas('sessions', ['id' => self::LIVE_SESSION_ID]);
        $this->assertDatabaseMissing('sessions', ['id' => self::DEAD_SESSION_ID]);
    }

    public function test_prune_closes_a_sign_in_whose_session_row_is_gone(): void
    {
        $user = User::factory()->create();
        $ledger = $this->signIn($user, self::DEAD_SESSION_ID, ageMinutes: 30);

        $result = $this->manager()->prune();

        $this->assertSame(1, $result['orphaned']);
        $this->assertSame(1, $result['closed']);

        $ledger->refresh();

        $this->assertSame(SessionEndReason::ORPHANED, $ledger->end_reason);
        // Closed as of the last moment it was seen, not as of the sweep.
        $this->assertSame($ledger->last_seen_at->toDateTimeString(), $ledger->ended_at?->toDateTimeString());
    }

    public function test_a_sign_in_inside_the_grace_window_is_not_called_orphaned(): void
    {
        $user = User::factory()->create();
        $ledger = $this->signIn($user, self::LIVE_SESSION_ID, ageMinutes: 2);

        $this->manager()->prune();

        $this->assertNull($ledger->fresh()->ended_at);
    }

    public function test_a_sign_in_backed_by_a_live_session_stays_open(): void
    {
        $user = User::factory()->create();

        $this->frameworkSession(self::LIVE_SESSION_ID, $user, ageMinutes: 5);
        $ledger = $this->signIn($user, self::LIVE_SESSION_ID, ageMinutes: 5);

        $counts = $this->manager()->prune();

        $this->assertSame(0, $counts['closed']);
        $this->assertNull($ledger->fresh()->ended_at);
        $this->assertSame(1, $this->manager()->counts()['sign_ins_live']);
        $this->assertSame(0, $this->manager()->counts()['sign_ins_stale']);
    }

    public function test_a_sign_in_whose_session_expired_is_closed_as_expired_not_orphaned(): void
    {
        $user = User::factory()->create();
        $ledger = $this->signIn($user, self::DEAD_SESSION_ID, ageMinutes: 180);

        $result = $this->manager()->prune();

        $this->assertSame(1, $result['expired']);
        $this->assertSame(0, $result['orphaned']);
        $this->assertSame(SessionEndReason::EXPIRED, $ledger->fresh()->end_reason);
    }

    public function test_dry_run_reports_the_sweep_without_changing_anything(): void
    {
        $user = User::factory()->create();

        $this->frameworkSession(self::DEAD_SESSION_ID, $user, ageMinutes: 180);
        $ledger = $this->signIn($user, self::DEAD_SESSION_ID, ageMinutes: 180);

        $result = $this->manager()->prune(dryRun: true);

        $this->assertTrue($result['dry_run']);
        $this->assertSame(1, $result['deleted']);
        $this->assertSame(1, $result['expired']);

        $this->assertDatabaseHas('sessions', ['id' => self::DEAD_SESSION_ID]);
        $this->assertNull($ledger->fresh()->ended_at);
        $this->assertFalse(Cache::has(SessionManager::LAST_RUN_KEY));
    }

    public function test_the_session_being_pruned_from_is_never_touched(): void
    {
        $user = User::factory()->create();

        $this->frameworkSession(self::LIVE_SESSION_ID, $user, ageMinutes: 180);
        $this->frameworkSession(self::DEAD_SESSION_ID, $user, ageMinutes: 180);
        $mine = $this->signIn($user, self::LIVE_SESSION_ID, ageMinutes: 180);
        $theirs = $this->signIn($user, self::DEAD_SESSION_ID, ageMinutes: 180);

        $result = $this->manager()->prune(exceptSessionId: self::LIVE_SESSION_ID);

        $this->assertSame(1, $result['deleted']);
        $this->assertDatabaseHas('sessions', ['id' => self::LIVE_SESSION_ID]);
        $this->assertDatabaseMissing('sessions', ['id' => self::DEAD_SESSION_ID]);
        $this->assertNull($mine->fresh()->ended_at);
        $this->assertSame(SessionEndReason::EXPIRED, $theirs->fresh()->end_reason);
    }

    public function test_idle_timeout_closes_a_session_that_is_still_inside_the_lifetime(): void
    {
        CompanySetting::set('session_idle_timeout_minutes', 30);

        $user = User::factory()->create();

        $this->frameworkSession(self::LIVE_SESSION_ID, $user, ageMinutes: 45);
        $idle = $this->signIn($user, self::LIVE_SESSION_ID, ageMinutes: 45);

        $result = $this->manager()->prune();

        $this->assertSame(1, $result['idle']);
        $this->assertSame(SessionEndReason::IDLE_TIMEOUT, $idle->fresh()->end_reason);
        // An idle timeout is a real sign-out, so the framework row goes with it.
        $this->assertDatabaseMissing('sessions', ['id' => self::LIVE_SESSION_ID]);
    }

    public function test_a_recently_seen_session_survives_the_idle_timeout(): void
    {
        CompanySetting::set('session_idle_timeout_minutes', 30);

        $user = User::factory()->create();

        $this->frameworkSession(self::LIVE_SESSION_ID, $user, ageMinutes: 5);
        $ledger = $this->signIn($user, self::LIVE_SESSION_ID, ageMinutes: 5);

        $this->manager()->prune();

        $this->assertNull($ledger->fresh()->ended_at);
        $this->assertDatabaseHas('sessions', ['id' => self::LIVE_SESSION_ID]);
    }

    public function test_prune_records_the_run_so_the_card_can_show_it(): void
    {
        $user = User::factory()->create();
        $this->frameworkSession(self::DEAD_SESSION_ID, $user, ageMinutes: 180);

        $this->manager()->prune();

        $run = $this->manager()->lastRun();

        $this->assertIsArray($run);
        $this->assertSame(1, $run['deleted']);
        $this->assertArrayHasKey('at', $run);

        $this->actingAsSessionAdmin();

        $this->get(route('admin.system-overview.index'))
            ->assertOk()
            ->assertSee('Last pruned')
            ->assertSee('Deleted 1 expired row(s)');
    }

    public function test_the_card_reports_valid_and_stale_sessions_separately(): void
    {
        $user = User::factory()->create();

        $this->frameworkSession(self::LIVE_SESSION_ID, $user, ageMinutes: 5);
        $this->frameworkSession(self::GUEST_SESSION_ID, null, ageMinutes: 5);
        $this->frameworkSession(self::DEAD_SESSION_ID, null, ageMinutes: 180);

        $this->actingAsSessionAdmin();

        $this->get(route('admin.system-overview.index'))
            ->assertOk()
            ->assertSee('Live (valid)')
            ->assertSee('1 authenticated, 1 guest')
            ->assertSee('Stale')
            ->assertSee('Expired rows the driver has not garbage-collected yet')
            ->assertSee('Prune now');
    }

    public function test_the_card_reads_clean_when_nothing_is_waiting(): void
    {
        $user = User::factory()->create();
        $this->frameworkSession(self::LIVE_SESSION_ID, $user, ageMinutes: 5);

        $this->actingAsSessionAdmin();

        $this->get(route('admin.system-overview.index'))
            ->assertOk()
            ->assertSee('Nothing is waiting past the lifetime.');
    }

    public function test_prune_now_requires_the_manage_user_sessions_permission(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $this->frameworkSession(self::DEAD_SESSION_ID, $user, ageMinutes: 180);

        $this->actingAs($user)->post(route('admin.sessions.prune'))->assertForbidden();
        $this->assertDatabaseHas('sessions', ['id' => self::DEAD_SESSION_ID]);
    }

    public function test_an_admin_can_prune_from_the_card(): void
    {
        $user = $this->actingAsSessionAdmin();
        $this->frameworkSession(self::DEAD_SESSION_ID, $user, ageMinutes: 180);

        $this->post(route('admin.sessions.prune'))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('sessions', ['id' => self::DEAD_SESSION_ID]);
    }

    public function test_pruning_from_the_card_spares_the_admins_own_session(): void
    {
        $admin = $this->actingAsSessionAdmin();

        // A first request gives the pipeline a real session id, a framework row and
        // the ledger row that goes with it.
        $this->get(route('admin.sessions.index'))->assertOk();
        $current = UserSession::where('user_id', $admin->getKey())->sole();

        // Age that same session past the lifetime: the guarantee only means
        // something when the protected row is otherwise prunable.
        $aged = now()->subMinutes(180);
        $current->forceFill(['login_at' => $aged, 'last_seen_at' => $aged])->save();
        DB::table('sessions')->where('id', $current->session_id)
            ->update(['last_activity' => $aged->getTimestamp()]);

        $this->frameworkSession(self::DEAD_SESSION_ID, $admin, ageMinutes: 180);
        $other = $this->signIn($admin, self::DEAD_SESSION_ID, ageMinutes: 180);

        $this->withCookies([$this->sessionCookie() => $current->session_id])
            ->post(route('admin.sessions.prune'))
            ->assertRedirect();

        $this->assertNull($current->fresh()->ended_at);
        $this->assertDatabaseHas('sessions', ['id' => $current->session_id]);
        $this->assertSame(SessionEndReason::EXPIRED, $other->fresh()->end_reason);
        $this->assertDatabaseMissing('sessions', ['id' => self::DEAD_SESSION_ID]);
    }

    public function test_history_past_the_retention_window_is_dropped(): void
    {
        $user = User::factory()->create();

        $old = $this->signIn($user, self::DEAD_SESSION_ID, ageMinutes: 30);
        $old->forceFill(['ended_at' => now()->subDays(91), 'end_reason' => SessionEndReason::LOGOUT])->save();

        $result = $this->manager()->prune();

        $this->assertSame(1, $result['purged']);
        $this->assertDatabaseMissing('user_sessions', ['id' => $old->getKey()]);
    }

    public function test_the_command_reports_the_sweep_and_supports_a_dry_run(): void
    {
        $user = User::factory()->create();
        $this->frameworkSession(self::DEAD_SESSION_ID, $user, ageMinutes: 180);
        $this->signIn($user, self::DEAD_SESSION_ID, ageMinutes: 180);

        $this->artisan('sessions:prune', ['--dry-run' => true])
            ->expectsOutputToContain('Dry run')
            ->expectsOutputToContain('Deleted 1 expired framework session row(s).')
            ->assertExitCode(0);

        $this->assertDatabaseHas('sessions', ['id' => self::DEAD_SESSION_ID]);

        $this->artisan('sessions:prune')
            ->expectsOutputToContain('Closed 1 lapsed session(s).')
            ->expectsOutputToContain('Deleted 1 expired framework session row(s).')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('sessions', ['id' => self::DEAD_SESSION_ID]);
    }

    /**
     * Resolve the reconciliation service.
     */
    private function manager(): SessionManager
    {
        return $this->app->make(SessionManager::class);
    }

    /**
     * The cookie name the session id travels in.
     */
    private function sessionCookie(): string
    {
        return (string) config('session.cookie');
    }

    /**
     * Seed roles and act as an administrator who may manage sessions.
     */
    private function actingAsSessionAdmin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));

        $this->actingAs($user);

        return $user;
    }

    /**
     * Insert a framework session row, aged back from now.
     */
    private function frameworkSession(string $id, ?User $user, int $ageMinutes): void
    {
        DB::table('sessions')->insert([
            'id' => $id,
            'user_id' => $user?->getKey(),
            'payload' => '',
            'last_activity' => now()->subMinutes($ageMinutes)->getTimestamp(),
        ]);
    }

    /**
     * Record an open ledger row last seen the given number of minutes ago.
     */
    private function signIn(User $user, string $sessionId, int $ageMinutes): UserSession
    {
        $lastSeen = now()->subMinutes($ageMinutes);

        return UserSession::create([
            'user_id' => $user->getKey(),
            'session_id' => $sessionId,
            'ip_address' => '203.0.113.19',
            'last_ip_address' => '203.0.113.19',
            'user_agent' => self::USER_AGENT,
            'device_type' => DeviceType::DESKTOP->value,
            'platform' => 'macOS',
            'browser' => 'Safari',
            'browser_version' => '17.4',
            'login_method' => LoginMethod::PASSWORD->value,
            'login_at' => $lastSeen,
            'last_seen_at' => $lastSeen,
        ]);
    }
}
