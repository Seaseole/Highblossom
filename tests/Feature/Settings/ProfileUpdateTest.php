<?php

declare(strict_types=1);

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Coverage for the account self-service endpoints behind the admin profile page.
 */
class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_profile_page_is_displayed(): void
    {
        $user = $this->actingAsProfileUser();

        $this->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee($user->name);
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = $this->actingAsProfileUser();

        $this->put(route('admin.profile.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
    }

    public function test_email_verification_status_is_unchanged_when_email_address_is_unchanged(): void
    {
        $user = $this->actingAsProfileUser();

        $this->put(route('admin.profile.update'), [
            'name' => 'Test User',
            'email' => $user->email,
        ])->assertSessionHasNoErrors();

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = $this->actingAsProfileUser();

        $this->delete(route('admin.profile.destroy'), [
            'password' => 'password',
        ])->assertRedirect('/');

        $this->assertNull($user->fresh());
        $this->assertGuest();
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = $this->actingAsProfileUser();

        $this->delete(route('admin.profile.destroy'), [
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('password');

        $this->assertNotNull($user->fresh());
    }
}
