<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover the post editor's quick-add modal: the JSON taxonomy endpoints it calls,
 * the validation surfaced inside the dialog, and that the normal CRUD pages still
 * redirect rather than returning JSON.
 */
class BlogTaxonomyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed roles and act as an administrator who can create taxonomy terms.
     */
    private function admin(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));

        $this->actingAs($user);
    }

    public function test_the_modal_endpoint_returns_the_created_category(): void
    {
        $this->admin();

        $this->postJson(route('admin.categories.store'), ['name' => 'Ceramic Coating'])
            ->assertCreated()
            ->assertJson(['name' => 'Ceramic Coating', 'slug' => 'ceramic-coating'])
            ->assertJsonStructure(['id', 'name', 'slug']);

        $this->assertDatabaseHas('categories', ['name' => 'Ceramic Coating', 'slug' => 'ceramic-coating']);
    }

    public function test_the_modal_endpoint_returns_the_created_tag(): void
    {
        $this->admin();

        $this->postJson(route('admin.tags.store'), ['name' => 'Paint Prep'])
            ->assertCreated()
            ->assertJson(['name' => 'Paint Prep', 'slug' => 'paint-prep']);

        $this->assertDatabaseHas('tags', ['name' => 'Paint Prep', 'slug' => 'paint-prep']);
    }

    public function test_a_blank_name_is_rejected_inside_the_modal(): void
    {
        $this->admin();

        $this->postJson(route('admin.categories.store'), ['name' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');

        $this->assertSame(0, Category::count());
    }

    public function test_a_duplicate_name_is_rejected_with_a_readable_message(): void
    {
        $this->admin();

        Category::create(['name' => 'Guides', 'slug' => 'guides']);

        $this->postJson(route('admin.categories.store'), ['name' => 'Guides'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name')
            ->assertJsonFragment(['A category with that name already exists.']);

        $this->postJson(route('admin.tags.store'), ['name' => 'Detailing'])->assertCreated();

        $this->postJson(route('admin.tags.store'), ['name' => 'Detailing'])
            ->assertStatus(422)
            ->assertJsonFragment(['A tag with that name already exists.']);
    }

    public function test_two_names_that_slug_the_same_get_distinct_slugs(): void
    {
        $this->admin();

        // Distinct names, identical slugs, so the unique slug index would otherwise blow up.
        $this->postJson(route('admin.categories.store'), ['name' => 'Ceramic Coating'])->assertCreated();

        $second = $this->postJson(route('admin.categories.store'), ['name' => 'Ceramic  Coating'])
            ->assertCreated()
            ->json('slug');

        $this->assertSame('ceramic-coating-2', $second);
    }

    public function test_updating_a_term_keeps_its_own_name_valid(): void
    {
        $this->admin();

        $category = Category::create(['name' => 'Guides', 'slug' => 'guides']);

        $this->put(route('admin.categories.update', $category), [
            'name' => 'Guides',
            'description' => 'Now with a description.',
        ])->assertSessionHasNoErrors();

        $this->assertSame('Now with a description.', $category->fresh()->description);
    }

    public function test_the_full_page_still_redirects_rather_than_returning_json(): void
    {
        $this->admin();

        $this->post(route('admin.categories.store'), ['name' => 'From the form'])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', ['name' => 'From the form']);
    }

    public function test_the_post_form_renders_the_quick_add_dialog_and_targets(): void
    {
        $this->admin();

        $html = $this->get(route('admin.posts.create'))->assertOk()->getContent();

        foreach (['category-chip-list', 'category-chip-template', 'tag-chip-list', 'tag-chip-template'] as $id) {
            $this->assertStringContainsString('id="'.$id.'"', $html);
        }

        $this->assertStringContainsString("open('category')", $html);
        $this->assertStringContainsString("open('tag')", $html);
        $this->assertStringContainsString('aria-modal="true"', $html);
        $this->assertStringNotContainsString('/categories/create', $html, 'the dialog must replace the link that navigated away');
        $this->assertStringNotContainsString('/tags/create', $html);
    }

    public function test_a_quick_added_term_can_be_saved_with_the_post(): void
    {
        $this->admin();

        $category = $this->postJson(route('admin.categories.store'), ['name' => 'Wheel Care'])->json('id');
        $tag = $this->postJson(route('admin.tags.store'), ['name' => 'Alloys'])->json('id');

        $this->post(route('admin.posts.store'), [
            'title' => 'Wheel care notes',
            'status' => 'draft',
            'categories' => [$category],
            'tags' => [$tag],
        ])->assertSessionHasNoErrors();

        $post = Post::where('slug', 'wheel-care-notes')->firstOrFail();

        $this->assertTrue($post->categories->contains($category));
        $this->assertTrue($post->tags->contains($tag));
    }
}
