<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Str;

/**
 * Supplies the blog command palette with a small, ranked JSON payload.
 *
 * Deliberately its own query path instead of reusing Livewire\BlogPosts: that
 * component re-renders the hero, filter tabs, tag cloud and a nine-item grid for
 * every keystroke, which is far too much work for a type-ahead.
 *
 * Body text is out of scope. `posts.content` is a JSON block tree, so a LIKE
 * against it matches block keys (`"title"`, `"content"`) rather than prose, and
 * `Post::plainText()` only flattens it in PHP.
 */
final readonly class BlogSearchService
{
    public const MAX_POSTS = 6;

    public const MAX_TERMS = 4;

    /**
     * How many rows a real search may pull before PHP ranks them.
     */
    private const CANDIDATE_LIMIT = 25;

    /**
     * Search for a term, or list recent posts when it is blank.
     *
     * @param string|null $term Raw user query; whitespace-only counts as empty
     *
     * @return array<string, mixed>
     */
    public function search(?string $term): array
    {
        $term = trim(preg_replace('/\s+/u', ' ', $term ?? '') ?? '');

        return $term === '' ? $this->recent() : $this->match($term);
    }

    /**
     * Rank published posts by where the term matched, then by freshness.
     *
     * @param string $term Normalised, non-empty query
     *
     * @return array<string, mixed>
     */
    private function match(string $term): array
    {
        $like = '%'.$this->escapeLike($term).'%';

        $candidates = Post::published()
            ->with('categories:id,name,slug')
            ->select(['id', 'title', 'slug', 'excerpt', 'published_at', 'featured_image_path', 'featured_image_url'])
            ->where(fn ($query) => $query->where('title', 'like', $like)->orWhere('excerpt', 'like', $like))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(self::CANDIDATE_LIMIT)
            ->get();

        // Ranks by match position rather than raw SQL: the candidate set is tiny,
        // and a CASE expression would put driver-specific SQL in the query builder.
        $ranked = $candidates
            ->map(fn (Post $post) => $this->rank($post, $term))
            ->sort()
            ->keys()
            ->take(self::MAX_POSTS)
            ->map(fn (int $index) => $this->postPayload($candidates[$index]));

        return [
            'mode' => 'search',
            'term' => $term,
            'posts' => $ranked->all(),
            'categories' => $this->taxonomy(Category::class, $term, 'category'),
            'tags' => $this->taxonomy(Tag::class, $term, 'tag'),
        ];
    }

    /**
     * Provide the palette's resting state: newest posts and busiest categories.
     *
     * @return array<string, mixed>
     */
    private function recent(): array
    {
        $posts = Post::published()
            ->with('categories:id,name,slug')
            ->select(['id', 'title', 'slug', 'excerpt', 'published_at', 'featured_image_path', 'featured_image_url'])
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(self::MAX_POSTS)
            ->get();

        $categories = Category::withCount(['posts' => fn ($query) => $query->published()])
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->limit(self::MAX_TERMS)
            ->get()
            ->map(fn (Category $category) => [
                'id' => 'category-'.$category->id,
                'type' => 'category',
                'label' => $category->name,
                'meta' => $category->posts_count.' posts',
                'href' => route('blog', ['category' => $category->slug]),
            ]);

        return [
            'mode' => 'recent',
            'term' => '',
            'posts' => $posts->map(fn (Post $post) => $this->postPayload($post))->all(),
            'categories' => $categories->all(),
            'tags' => [],
        ];
    }

    /**
     * Match category or tag names, so the palette can offer filters as results.
     *
     * @param class-string<Category|Tag> $model
     * @param string                     $term  Normalised, non-empty query
     * @param string                     $param Query-string key the listing filters on
     *
     * @return array<int, array<string, mixed>>
     */
    private function taxonomy(string $model, string $term, string $param): array
    {
        $like = '%'.$this->escapeLike($term).'%';

        return $model::query()
            ->withCount(['posts' => fn ($query) => $query->published()])
            ->where('name', 'like', $like)
            ->orderByRaw('CASE WHEN name = ? THEN 0 ELSE 1 END', [$term])
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->limit(self::MAX_TERMS)
            ->get()
            ->map(fn ($item) => [
                'id' => $param.'-'.$item->id,
                'type' => $param,
                'label' => $param === 'tag' ? '#'.$item->name : $item->name,
                'meta' => $item->posts_count.' '.Str::plural('post', $item->posts_count),
                'href' => route('blog', [$param => $item->slug]),
            ])
            ->all();
    }

    /**
     * Weight a candidate: title matches beat excerpt matches.
     *
     * @param Post   $post Candidate row
     * @param string $term Normalised query
     *
     * @return int Lower sorts first.
     */
    private function rank(Post $post, string $term): int
    {
        $needle = Str::lower($term);

        if (str_contains(Str::lower($post->title), $needle)) {
            return 0;
        }

        return 1;
    }

    /**
     * Shape one post for the palette response.
     *
     * @param Post $post Row loaded with the columns this method reads
     *
     * @return array<string, mixed>
     */
    private function postPayload(Post $post): array
    {
        return [
            'id' => 'post-'.$post->id,
            'type' => 'post',
            'label' => $post->title,
            'snippet' => Str::limit((string) $post->excerpt, 140),
            'category' => $post->categories->first()?->name,
            'date' => $post->published_at?->format('M j, Y'),
            'thumbnail' => $post->featured_image_url,
            'href' => route('blog.show', $post->slug),
        ];
    }

    /**
     * Neutralise LIKE wildcards so `100%` or `a_b` search for themselves.
     *
     * @param string $term Normalised query
     */
    private function escapeLike(string $term): string
    {
        return addcslashes($term, '\\%_');
    }
}
