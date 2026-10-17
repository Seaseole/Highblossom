<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\BlogPosts;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Cover the public blog listing: the promoted hero card, the category tabs,
 * authorship, and which posts are allowed to appear at all.
 */
class BlogListingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a published post with a unique slug and a category.
     */
    private function published(string $title, array $attributes = []): Post
    {
        $category = Category::firstOrCreate(['slug' => 'guides'], ['name' => 'Guides']);

        $post = Post::create(array_merge([
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => 'An excerpt for '.$title.'.',
            'content' => [['type' => 'paragraph', 'attributes' => ['content' => 'Body copy.']]],
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $attributes));

        $post->categories()->sync([$category->id]);

        return $post;
    }

    public function test_the_newest_post_is_promoted_once_and_not_repeated_in_the_grid(): void
    {
        $older = $this->published('Older article', ['published_at' => now()->subDays(3)]);
        $newest = $this->published('Newest article', ['published_at' => now()->subMinute()]);

        $component = Livewire::test(BlogPosts::class)->instance();

        $this->assertSame($newest->id, $component->featuredPost?->id);
        $this->assertNotContains($newest->id, $component->posts->pluck('id')->all());
        $this->assertContains($older->id, $component->posts->pluck('id')->all());
    }

    public function test_no_hero_card_appears_when_there_is_only_one_post(): void
    {
        $this->published('Sole article');

        $component = Livewire::test(BlogPosts::class)->instance();

        $this->assertNull($component->featuredPost);
        $this->assertCount(1, $component->posts);
    }

    public function test_the_hero_is_skipped_while_a_filter_is_active(): void
    {
        $this->published('Older article');
        $newest = $this->published('Newest article');

        $component = Livewire::test(BlogPosts::class, ['categorySlug' => 'guides'])->instance();

        $this->assertNull($component->featuredPost);
        $this->assertContains($newest->id, $component->posts->pluck('id')->all());
    }

    public function test_category_tabs_show_the_published_post_count(): void
    {
        $this->published('First article');
        $this->published('Second article');
        $this->published('Hidden draft', ['status' => 'draft']);

        $tabs = Livewire::test(BlogPosts::class)->instance()->categoryTabs;

        $this->assertSame(2, $tabs->firstWhere('slug', 'guides')->posts_count);
    }

    public function test_the_author_and_read_time_render_on_each_card(): void
    {
        $author = User::factory()->create(['name' => 'Rina Kapoor']);

        $this->published('Authored article', ['user_id' => $author->id]);
        $this->published('Another article');

        Livewire::test(BlogPosts::class)
            ->assertSee('Rina Kapoor')
            ->assertSee('1 min read');
    }

    public function test_drafts_and_future_scheduled_posts_stay_hidden(): void
    {
        $this->published('Live article');
        $this->published('Tomorrow article', ['published_at' => now()->addWeek()]);
        $this->published('Hidden draft', ['status' => 'draft']);

        $html = Livewire::test(BlogPosts::class)->html();

        $this->assertStringContainsString('Live article', $html);
        $this->assertStringNotContainsString('Tomorrow article', $html);
        $this->assertStringNotContainsString('Hidden draft', $html);
    }

    public function test_the_listing_carries_no_newsletter_sign_up_block(): void
    {
        $this->published('An article');
        $this->published('Another article');

        $html = Livewire::test(BlogPosts::class)->html();

        $this->assertStringNotContainsString('Stay updated', $html);
        $this->assertStringNotContainsString('Subscribe', $html);
    }

    public function test_read_time_counts_the_words_across_content_blocks(): void
    {
        $long = Post::create([
            'title' => 'Long read',
            'slug' => 'long-read',
            'excerpt' => 'Excerpt',
            'content' => [
                ['type' => 'paragraph', 'attributes' => ['content' => Str::repeat('word ', 1200)]],
                ['type' => 'image', 'attributes' => ['images' => [['src' => 'uploads/blog/x.webp', 'alt' => 'ignored']]]],
            ],
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $this->assertSame(6, $long->readTimeMinutes());
    }
}
