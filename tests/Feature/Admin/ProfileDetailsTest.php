<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Post;
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

    public function test_activity_shows_blog_post_counts_and_recent_posts(): void
    {
        $user = $this->actingAsProfileUser();

        Post::create([
            'title' => 'Mine published post',
            'excerpt' => 'Long enough excerpt for a listing.',
            'content' => [['type' => 'paragraph', 'attributes' => ['content' => 'Body.']]],
            'status' => 'published',
            'published_at' => now()->subDay(),
            'user_id' => $user->id,
        ]);

        Post::create([
            'title' => 'Another authors post',
            'excerpt' => 'Long enough excerpt for a listing.',
            'content' => [['type' => 'paragraph', 'attributes' => ['content' => 'Body.']]],
            'status' => 'draft',
            'published_at' => null,
            'user_id' => User::factory()->create()->id,
        ]);

        $this->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee('Blog posts')
            ->assertSee('Recent blog posts')
            ->assertSee('Mine published post')
            ->assertSee('published')
            ->assertDontSee('Another authors post');
    }

    public function test_activity_shows_empty_state_without_posts(): void
    {
        $user = $this->actingAsProfileUser();

        $this->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee('No blog posts yet.');
    }

    public function test_posts_index_filters_by_author(): void
    {
        $user = $this->actingAsProfileUser();
        Permission::findOrCreate('view blog', 'web');
        $user->givePermissionTo('view blog');

        Post::create([
            'title' => 'Filtered by author post',
            'excerpt' => 'Long enough excerpt for a listing.',
            'content' => [['type' => 'paragraph', 'attributes' => ['content' => 'Body.']]],
            'status' => 'draft',
            'published_at' => null,
            'user_id' => $user->id,
        ]);

        Post::create([
            'title' => 'Someone else post',
            'excerpt' => 'Long enough excerpt for a listing.',
            'content' => [['type' => 'paragraph', 'attributes' => ['content' => 'Body.']]],
            'status' => 'draft',
            'published_at' => null,
            'user_id' => User::factory()->create()->id,
        ]);

        $this->get(route('admin.posts.index', ['user' => $user->id]))
            ->assertOk()
            ->assertSee('Filtered by author post')
            ->assertDontSee('Someone else post');
    }
}
