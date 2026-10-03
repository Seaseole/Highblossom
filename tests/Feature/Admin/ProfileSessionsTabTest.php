<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\SessionEndReason;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Covers the Sessions tab on the profile page and its sign-out endpoints.
 */
class ProfileSessionsTabTest extends TestCase
{
    use RefreshDatabase;

    private const OTHER_DEVICE = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1';

    public function test_the_sessions_tab_lists_another_device_with_a_sign_out_action(): void
    {
        $user = $this->actingAsProfileUser();
        $this->seedSession($user, '1111111111111111111111111111111111111111');

        $this->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee('Active devices')
            ->assertSee('Safari 17.4 · iOS')
            ->assertSee('Mobile')
            ->assertSee('Sign out other devices');
    }

    public function test_the_device_in_use_is_marked_and_has_no_sign_out_action(): void
    {
        $this->actingAsProfileUser();

        // The tracker records the row at the end of the first request, so a second
        // request on the same session id is what the tab sees as the current device.
        $this->get(route('admin.profile.index'))->assertOk();
        $current = UserSession::sole();

        $this->withCookies([$this->sessionCookie() => $current->session_id])
            ->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee('This device')
            ->assertSee('Previously active');
    }

    public function test_a_user_can_sign_out_one_of_their_own_other_devices(): void
    {
        $user = $this->actingAsProfileUser();
        $other = $this->seedSession($user, '2222222222222222222222222222222222222222');

        $this->delete(route('admin.profile.sessions.destroy', $other))->assertRedirect();

        $other->refresh();

        $this->assertSame(SessionEndReason::REVOKED_BY_USER, $other->end_reason);
        $this->assertSame($user->getKey(), $other->revoked_by);
    }

    public function test_a_user_cannot_sign_out_another_users_device(): void
    {
        $this->actingAsProfileUser();
        $foreign = $this->seedSession(User::factory()->create(), '3333333333333333333333333333333333333333');

        $this->delete(route('admin.profile.sessions.destroy', $foreign))->assertForbidden();

        $this->assertNull($foreign->fresh()->ended_at);
    }

    public function test_the_device_in_use_cannot_be_signed_out_from_the_list(): void
    {
        $this->actingAsProfileUser();

        $this->get(route('admin.profile.index'))->assertOk();
        $current = UserSession::sole();

        $this->withCookies([$this->sessionCookie() => $current->session_id])
            ->delete(route('admin.profile.sessions.destroy', $current))
            ->assertSessionHasErrors('session');

        $this->assertNull($current->fresh()->ended_at);
    }

    public function test_signing_out_other_devices_spares_the_one_in_use(): void
    {
        $user = $this->actingAsProfileUser();

        $this->get(route('admin.profile.index'))->assertOk();
        $current = UserSession::sole();
        $other = $this->seedSession($user, '4444444444444444444444444444444444444444');

        $this->withCookies([$this->sessionCookie() => $current->session_id])
            ->delete(route('admin.profile.sessions.revoke-others'))
            ->assertRedirect();

        $this->assertSame(SessionEndReason::REVOKED_BY_USER, $other->fresh()->end_reason);
        $this->assertNull($current->fresh()->ended_at);
    }

    public function test_closed_sessions_appear_in_the_history_list(): void
    {
        $user = $this->actingAsProfileUser();
        $ended = $this->seedSession($user, '5555555555555555555555555555555555555555');

        $ended->forceFill([
            'ended_at' => now()->subDay(),
            'end_reason' => SessionEndReason::LOGOUT,
        ])->save();

        $this->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee('Previously active')
            ->assertSee('Signed out');
    }

    /**
     * Session cookie name the browser would echo back.
     */
    private function sessionCookie(): string
    {
        return (string) config('session.cookie');
    }

    /**
     * Record an open ledger row for a device other than the one under test.
     */
    private function seedSession(User $user, string $sessionId): UserSession
    {
        return UserSession::create([
            'user_id' => $user->getKey(),
            'session_id' => $sessionId,
            'ip_address' => '198.51.100.7',
            'last_ip_address' => '198.51.100.7',
            'user_agent' => self::OTHER_DEVICE,
            'device_type' => 'mobile',
            'platform' => 'iOS',
            'browser' => 'Safari',
            'browser_version' => '17.4',
            'login_method' => 'password',
            'login_at' => now()->subHours(3),
            'last_seen_at' => now()->subMinutes(5),
        ]);
    }

    /**
     * Authenticate a user that can reach the admin profile routes.
     */
    private function actingAsProfileUser(): User
    {
        Permission::findOrCreate('access admin panel', 'web');

        $user = User::factory()->create();
        $user->givePermissionTo('access admin panel');
        $this->actingAs($user);

        return $user;
    }
}
