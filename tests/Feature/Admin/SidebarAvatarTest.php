<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover the sidebar avatar badge: it must read the same avatar_url accessor the
 * profile page uses, keep the initials as a fallback, and follow a replacement
 * upload without a reload of anything but the page itself.
 */
class SidebarAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_sidebar_falls_back_to_initials_without_an_avatar(): void
    {
        $this->actingAsAdmin('Naledi Kgosi');

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('NK')
            // The alt text only exists on the image, so its absence proves the badge
            // rendered the fallback rather than a broken picture.
            ->assertDontSee('Avatar of Naledi Kgosi');
    }

    public function test_the_sidebar_shows_the_picture_after_a_profile_upload(): void
    {
        Storage::fake('public');

        $user = $this->actingAsAdmin('Naledi Kgosi');

        $this->put(route('admin.profile.update'), [
            'name' => 'Naledi Kgosi',
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('portrait.png', 300, 300),
        ])->assertSessionHasNoErrors();

        $url = $user->fresh()->avatar_url;

        $this->assertNotNull($url);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Avatar of Naledi Kgosi')
            ->assertSee('src="'.$url.'"', false);
    }

    public function test_the_initials_stay_in_the_markup_behind_the_picture(): void
    {
        Storage::fake('public');

        $user = $this->actingAsAdmin('Naledi Kgosi');

        $this->put(route('admin.profile.update'), [
            'name' => 'Naledi Kgosi',
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('portrait.png', 300, 300),
        ])->assertSessionHasNoErrors();

        // If the image ever fails to load, the badge still has initials to show.
        $this->get(route('dashboard'))->assertOk()->assertSee('NK');
    }

    public function test_a_replacement_upload_removes_the_file_it_supersedes(): void
    {
        Storage::fake('public');

        $user = $this->actingAsAdmin('Naledi Kgosi');

        $this->put(route('admin.profile.update'), [
            'name' => 'Naledi Kgosi',
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('first.png', 300, 300),
        ])->assertSessionHasNoErrors();

        $first = $user->fresh()->avatar_path;

        $this->put(route('admin.profile.update'), [
            'name' => 'Naledi Kgosi',
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('second.png', 300, 300),
        ])->assertSessionHasNoErrors();

        $second = $user->fresh()->avatar_path;

        $this->assertNotSame($first, $second);
        Storage::disk('public')->assertExists($second);
        Storage::disk('public')->assertMissing($first);
    }

    public function test_an_avatar_file_that_fails_to_store_leaves_the_old_one_in_place(): void
    {
        Storage::fake('public');

        $user = $this->actingAsAdmin('Naledi Kgosi');

        $this->put(route('admin.profile.update'), [
            'name' => 'Naledi Kgosi',
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('portrait.png', 300, 300),
        ])->assertSessionHasNoErrors();

        $kept = $user->fresh()->avatar_path;

        // A non-image under the avatar field is rejected before it can displace
        // anything, so the stored picture and its file both survive untouched.
        $this->put(route('admin.profile.update'), [
            'name' => 'Naledi Kgosi',
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->create('notes.txt', 8),
        ])->assertSessionHasErrors('avatar');

        $this->assertSame($kept, $user->fresh()->avatar_path);
        Storage::disk('public')->assertExists($kept);
    }

    /**
     * Seed roles and act as an administrator with the given name.
     */
    private function actingAsAdmin(string $name): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create(['name' => $name]);
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));

        $this->actingAs($user);

        return $user;
    }
}
