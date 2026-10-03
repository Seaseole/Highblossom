<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Coverage for the profile summary card and the phone/avatar profile fields.
 */
class ProfileDetailsTest extends TestCase
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

    public function test_profile_page_displays_summary_and_activity(): void
    {
        $user = $this->actingAsProfileUser();
        $user->assignRole(Role::findOrCreate('Editor', 'web'));

        $this->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee($user->email)
            ->assertSee('Email verified')
            ->assertSee('Editor')
            ->assertSee('Member since')
            ->assertSee('Activity')
            ->assertSee('Inspection notes');
    }

    public function test_phone_can_be_updated(): void
    {
        $user = $this->actingAsProfileUser();

        $this->put(route('admin.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '+1 555 000 1234',
        ])->assertRedirect();

        $this->assertSame('+1 555 000 1234', $user->fresh()->phone);
    }

    public function test_invalid_phone_is_rejected(): void
    {
        $user = $this->actingAsProfileUser();

        $this->put(route('admin.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => 'not a phone',
        ])->assertSessionHasErrors('phone');
    }

    public function test_avatar_can_be_uploaded(): void
    {
        Storage::fake('public');

        $user = $this->actingAsProfileUser();

        $this->put(route('admin.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('avatar.jpg', 600, 600),
        ])->assertRedirect();

        $user->refresh();

        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_existing_avatar_is_replaced_on_upload(): void
    {
        Storage::fake('public');

        $user = $this->actingAsProfileUser();
        $user->forceFill(['avatar_path' => 'avatars/old.webp'])->save();
        Storage::disk('public')->put('avatars/old.webp', 'old');

        $this->put(route('admin.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('new.png', 300, 300),
        ])->assertRedirect();

        $user->refresh();

        Storage::disk('public')->assertMissing('avatars/old.webp');
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_avatar_is_kept_when_no_file_is_uploaded(): void
    {
        $user = $this->actingAsProfileUser();
        $user->forceFill(['avatar_path' => 'avatars/keep.webp'])->save();

        $this->put(route('admin.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
        ])->assertRedirect();

        $this->assertSame('avatars/keep.webp', $user->fresh()->avatar_path);
    }
}
