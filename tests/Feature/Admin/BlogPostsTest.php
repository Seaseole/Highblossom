<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover the blog post admin surface: authorship, the publish gate, taxonomy
 * syncing, and the status controls offered on the index.
 */
class BlogPostsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed roles and act as an administrator who can manage posts.
     */
    private function admin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));

        $this->actingAs($user);

        return $user;
    }

    /**
     * Create a post that satisfies every publishing requirement.
     */
    private function readyPost(array $attributes = []): Post
    {
        $category = Category::firstOrCreate(['slug' => 'guides'], ['name' => 'Guides']);

        $post = Post::create(array_merge([
            'title' => 'A finished post',
            'slug' => 'a-finished-post-'.Str::random(6),
            'excerpt' => 'Long enough to read well in a listing.',
            'content' => [['type' => 'paragraph', 'attributes' => ['content' => 'Body copy.']]],
            'status' => 'draft',
            'published_at' => null,
        ], $attributes));

        $post->categories()->sync([$category->id]);

        return $post;
    }

    public function test_the_create_form_offers_taxonomy_chips_counters_and_the_publish_checklist(): void
    {
        $this->admin();

        $category = Category::create(['name' => 'Guides', 'slug' => 'guides']);
        $tag = Tag::create(['name' => 'Detailing', 'slug' => 'detailing']);

        $this->get(route('admin.posts.create'))
            ->assertOk()
            ->assertSee('Categories &amp; Tags', false)
            ->assertSee('Ready to publish')
            ->assertSee('Save as draft instead', false)
            ->assertSee($category->name)
            ->assertSee($tag->name)
            ->assertSee('peer-checked:bg-gray-900', false)
            ->assertSee('name="tags[]"', false)
            ->assertSee('/ '.Post::TITLE_MAX, false);
    }

    public function test_the_edit_form_preselects_the_posts_categories_and_tags(): void
    {
        $this->admin();

        $post = $this->readyPost();
        $tag = Tag::create(['name' => 'Detailing', 'slug' => 'detailing']);
        $other = Category::create(['name' => 'Uncut Glass', 'slug' => 'uncut-glass']);
        $post->tags()->sync([$tag->id]);

        $html = $this->get(route('admin.posts.edit', $post))->assertOk()->getContent();

        $assignedCategory = $post->categories->first();

        $this->assertMatchesRegularExpression(
            '/name="categories\[\]"\s+value="'.$assignedCategory->id.'"\s+checked/',
            $html,
            'the assigned category should render checked'
        );

        $this->assertMatchesRegularExpression(
            '/name="tags\[\]"\s+value="'.$tag->id.'"\s+checked/',
            $html,
            'the assigned tag should render checked'
        );

        $this->assertMatchesRegularExpression(
            '/name="categories\[\]"\s+value="'.$other->id.'"\s+class/',
            $html,
            'an unassigned category should not render checked'
        );
    }

    public function test_the_index_offers_the_status_menu_and_bulk_controls(): void
    {
        $this->admin();

        $this->readyPost();

        $html = $this->get(route('admin.posts.index'))->assertOk()->getContent();

        $this->assertStringContainsString('data-post-row', $html);
        $this->assertStringContainsString('data-status-trigger', $html);
        $this->assertStringContainsString('contextmenu', $html);
        $this->assertStringContainsString('Move to draft', $html);
        $this->assertStringContainsString('Published by', $html);
    }

    public function test_index_shows_who_published_the_post(): void
    {
        $admin = $this->admin();

        $this->readyPost(['user_id' => $admin->id]);

        $this->get(route('admin.posts.index'))
            ->assertOk()
            ->assertSee('Published by')
            ->assertSee($admin->name);
    }

    public function test_store_records_the_creating_admin_as_the_author(): void
    {
        $admin = $this->admin();

        $this->post(route('admin.posts.store'), [
            'title' => 'Authored by me',
            'status' => 'draft',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('posts', [
            'title' => 'Authored by me',
            'user_id' => $admin->id,
        ]);
    }

    public function test_publishing_from_the_form_requires_excerpt_category_and_content(): void
    {
        $this->admin();

        $this->post(route('admin.posts.store'), [
            'title' => 'Not ready',
            'status' => 'published',
        ])
            ->assertSessionHasErrors(['excerpt', 'categories', 'content'])
            ->assertRedirect();

        $this->assertDatabaseMissing('posts', ['title' => 'Not ready']);
    }

    public function test_drafts_save_without_the_publishing_requirements(): void
    {
        $this->admin();

        $this->post(route('admin.posts.store'), [
            'title' => 'Just a sketch',
            'status' => 'draft',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('posts', ['title' => 'Just a sketch', 'status' => 'draft']);
    }

    public function test_a_title_longer_than_the_limit_is_rejected(): void
    {
        $this->admin();

        $this->post(route('admin.posts.store'), [
            'title' => str_repeat('a', Post::TITLE_MAX + 1),
            'status' => 'draft',
        ])->assertSessionHasErrors('title');
    }

    public function test_an_excerpt_longer_than_the_limit_is_rejected(): void
    {
        $this->admin();

        $this->post(route('admin.posts.store'), [
            'title' => 'Too long',
            'excerpt' => str_repeat('b', Post::EXCERPT_MAX + 1),
            'status' => 'draft',
        ])->assertSessionHasErrors('excerpt');
    }

    public function test_a_duplicate_title_receives_a_disambiguated_slug(): void
    {
        $this->admin();

        foreach (['first', 'second'] as $ignored) {
            $this->post(route('admin.posts.store'), [
                'title' => 'Same title',
                'status' => 'draft',
            ])->assertSessionHasNoErrors();
        }

        $this->assertDatabaseHas('posts', ['title' => 'Same title', 'slug' => 'same-title']);
        $this->assertDatabaseHas('posts', ['title' => 'Same title', 'slug' => 'same-title-2']);
    }

    public function test_tags_sync_on_store_and_clear_on_update(): void
    {
        $this->admin();

        $tag = Tag::create(['name' => 'Detailing', 'slug' => 'detailing']);

        $this->post(route('admin.posts.store'), [
            'title' => 'Tagged post',
            'status' => 'draft',
            'tags' => [$tag->id],
        ])->assertSessionHasNoErrors();

        $post = Post::where('slug', 'tagged-post')->firstOrFail();

        $this->assertTrue($post->tags->contains($tag->id));

        $this->put(route('admin.posts.update', $post), [
            'title' => 'Tagged post',
            'status' => 'draft',
        ])->assertSessionHasNoErrors();

        $this->assertCount(0, $post->fresh()->tags);
    }

    public function test_the_status_endpoint_publishes_a_ready_post(): void
    {
        $this->admin();

        $post = $this->readyPost();

        $this->patchJson(route('admin.posts.status', $post), ['status' => 'published'])
            ->assertOk()
            ->assertJsonFragment(['statuses' => [$post->getKey() => 'published']]);

        $post->refresh();

        $this->assertSame('published', $post->status);
        $this->assertNotNull($post->published_at);
    }

    public function test_the_status_endpoint_refuses_an_unfinished_post(): void
    {
        $this->admin();

        $post = $this->readyPost(['excerpt' => null]);

        $this->patchJson(route('admin.posts.status', $post), ['status' => 'published'])
            ->assertStatus(422)
            ->assertJsonStructure(['message']);

        $this->assertSame('draft', $post->fresh()->status);
    }

    public function test_the_status_endpoint_always_allows_unpublishing(): void
    {
        $this->admin();

        $post = $this->readyPost([
            'excerpt' => null,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->patchJson(route('admin.posts.status', $post), ['status' => 'draft'])
            ->assertOk()
            ->assertJsonFragment(['statuses' => [$post->getKey() => 'draft']]);

        $this->assertSame('draft', $post->fresh()->status);
    }

    public function test_bulk_status_updates_every_selected_post(): void
    {
        $this->admin();

        $posts = collect([$this->readyPost(), $this->readyPost()]);

        $this->putJson(route('admin.posts.bulk-status'), [
            'status' => 'published',
            'posts' => $posts->pluck('id')->all(),
        ])
            ->assertOk()
            ->assertJson(['changed' => 2]);

        $this->assertSame(2, Post::where('status', 'published')->count());
    }

    public function test_bulk_status_refuses_to_publish_unfinished_posts(): void
    {
        $this->admin();

        $ready = $this->readyPost();
        $unfinished = $this->readyPost(['excerpt' => null]);

        $this->putJson(route('admin.posts.bulk-status'), [
            'status' => 'published',
            'posts' => [$ready->id, $unfinished->id],
        ])->assertStatus(422);

        $this->assertSame('draft', $ready->fresh()->status);
        $this->assertSame('draft', $unfinished->fresh()->status);
    }

    public function test_bulk_status_requires_a_selection_and_a_known_status(): void
    {
        $this->admin();

        $this->putJson(route('admin.posts.bulk-status'), ['status' => 'published', 'posts' => []])
            ->assertStatus(422);

        $this->putJson(route('admin.posts.bulk-status'), ['status' => 'archived', 'posts' => [1]])
            ->assertStatus(422);
    }

    public function test_status_endpoints_require_the_update_blog_permission(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->actingAs(User::factory()->create());

        $post = $this->readyPost();

        $this->patchJson(route('admin.posts.status', $post), ['status' => 'published'])->assertForbidden();

        $this->putJson(route('admin.posts.bulk-status'), [
            'status' => 'published',
            'posts' => [$post->id],
        ])->assertForbidden();
    }

    public function test_an_unfinished_draft_can_be_archived(): void
    {
        $this->admin();

        $post = $this->readyPost(['excerpt' => null, 'content' => null]);
        $post->categories()->detach();

        $this->patchJson(route('admin.posts.status', $post), ['status' => 'archived'])
            ->assertOk()
            ->assertJsonFragment(['statuses' => [$post->getKey() => 'archived']]);

        $this->assertSame('archived', $post->fresh()->status);
    }

    public function test_archiving_hides_a_post_from_the_public_site(): void
    {
        $this->admin();

        $post = $this->readyPost(['status' => 'published', 'published_at' => now()]);

        $this->get('/blog/'.$post->slug)->assertOk();

        $this->patchJson(route('admin.posts.status', $post), ['status' => 'archived'])->assertOk();

        $this->get('/blog/'.$post->slug)->assertNotFound();
    }

    public function test_unarchiving_an_unfinished_post_to_published_is_still_blocked(): void
    {
        $this->admin();

        $post = $this->readyPost(['status' => 'archived', 'excerpt' => null]);

        $this->patchJson(route('admin.posts.status', $post), ['status' => 'published'])->assertStatus(422);

        $this->assertSame('archived', $post->fresh()->status);
    }

    public function test_unarchiving_a_finished_post_publishes_it(): void
    {
        $this->admin();

        $post = $this->readyPost(['status' => 'archived']);

        $this->patchJson(route('admin.posts.status', $post), ['status' => 'published'])
            ->assertOk()
            ->assertJsonFragment(['statuses' => [$post->getKey() => 'published']]);

        $this->assertSame('published', $post->fresh()->status);
    }

    public function test_archived_can_be_set_from_the_post_form(): void
    {
        $this->admin();

        $post = $this->readyPost();

        $this->put(route('admin.posts.update', $post), [
            'title' => 'A finished post',
            'excerpt' => 'Long enough to read well in a listing.',
            'status' => 'archived',
        ])->assertSessionHasNoErrors();

        $this->assertSame('archived', $post->fresh()->status);
    }

    public function test_an_unknown_status_is_rejected(): void
    {
        $this->admin();

        $post = $this->readyPost();

        $this->patchJson(route('admin.posts.status', $post), ['status' => 'deleted'])->assertStatus(422);
    }

    public function test_bulk_archive_moves_every_selected_post(): void
    {
        $this->admin();

        $posts = collect([$this->readyPost(), $this->readyPost(), $this->readyPost(['status' => 'archived'])]);

        $this->putJson(route('admin.posts.bulk-status'), [
            'status' => 'archived',
            'posts' => $posts->pluck('id')->all(),
        ])->assertOk()->assertJson(['changed' => 2]);

        $this->assertSame(3, Post::where('status', 'archived')->count());
    }

    public function test_the_index_lists_each_posts_categories_and_tags(): void
    {
        $this->admin();

        $post = $this->readyPost();
        $tag = Tag::create(['name' => 'Detailing', 'slug' => 'detailing']);
        $post->tags()->sync([$tag->id]);

        $html = $this->get(route('admin.posts.index'))->assertOk()->getContent();

        $this->assertStringContainsString('Guides', $html);
        $this->assertStringContainsString('#Detailing', $html);
    }

    public function test_the_row_menu_offers_only_statuses_other_than_the_current_one(): void
    {
        $this->admin();

        $this->readyPost();

        $html = $this->get(route('admin.posts.index'))->assertOk()->getContent();

        foreach (['published', 'draft', 'archived'] as $status) {
            $this->assertStringContainsString("offers('".$status."')", $html);
        }

        $this->assertStringContainsString("x-on:click=\"setStatus('archived')\"", $html);
    }
}
