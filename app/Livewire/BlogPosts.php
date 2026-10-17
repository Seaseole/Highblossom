<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Display and filter paginated blog posts with search, category, and tag filters.
 */
final class BlogPosts extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public ?string $categorySlug = null;

    #[Url]
    public ?string $tagSlug = null;

    public int $perPage = 9;

    /**
     * Initialize the component with optional filter values.
     */
    public function mount(string $search = '', ?string $categorySlug = null, ?string $tagSlug = null): void
    {
        $this->search = $search;
        $this->categorySlug = $categorySlug;
        $this->tagSlug = $tagSlug;
    }

    /**
     * Render the lazy-loading placeholder.
     */
    public function placeholder(): View
    {
        return view('livewire.blog-posts.placeholder');
    }

    /**
     * Whether any filter is narrowing the listing.
     */
    #[Computed]
    public function filtered(): bool
    {
        return $this->search !== '' || $this->categorySlug !== null || $this->tagSlug !== null;
    }

    /**
     * The newest published post, promoted to the hero card on the unfiltered view.
     */
    #[Computed]
    public function featuredPost(): ?Post
    {
        if ($this->filtered() || $this->publishedTotal < 2) {
            return null;
        }

        return Post::published()->with(['categories', 'tags', 'author'])->orderByDesc('published_at')->first();
    }

    /**
     * Every published post count per category, for the filter tabs.
     *
     * @return Collection<int, Category>
     */
    #[Computed]
    public function categoryTabs(): Collection
    {
        return Category::withCount(['posts' => fn ($query) => $query->published()])
            ->orderBy('name')
            ->get();
    }

    /**
     * How many posts are live, so the hero can be skipped without a second query.
     */
    #[Computed]
    public function publishedTotal(): int
    {
        return Post::published()->count();
    }

    /**
     * Get the paginated, filtered list of published posts.
     *
     * @return LengthAwarePaginator
     *
     * @throws ModelNotFoundException
     */
    #[Computed]
    public function posts()
    {
        $query = Post::published()->with('categories', 'tags', 'author');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('excerpt', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->categorySlug) {
            $category = Category::where('slug', $this->categorySlug)->firstOrFail();
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $category->id));
        }

        if ($this->tagSlug) {
            $tag = Tag::where('slug', $this->tagSlug)->firstOrFail();
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id));
        }

        if ($featured = $this->featuredPost) {
            $query->whereKeyNot($featured->getKey());
        }

        // Not ->latest(): Eloquent's concrete latest() shadows Post::scopeLatest,
        // so it would order by created_at and tie for posts saved in the same second.
        return $query->orderByDesc('published_at')->orderByDesc('id')->paginate($this->perPage);
    }

    /**
     * Get all available categories for filtering.
     *
     * @return Collection
     */
    #[Computed]
    public function categories()
    {
        return Category::all();
    }

    /**
     * Get all available tags for filtering.
     *
     * @return Collection
     */
    #[Computed]
    public function tags()
    {
        return Tag::all();
    }

    /**
     * Reset pagination when the search term changes.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when the category filter changes.
     */
    public function updatedCategorySlug(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when the tag filter changes.
     */
    public function updatedTagSlug(): void
    {
        $this->resetPage();
    }

    #[On('search-updated')]
    /**
     * Update the search term from an external event and reset pagination.
     */
    public function updateSearch(string $search): void
    {
        $this->search = $search;
        $this->resetPage();
    }

    #[On('filter-cleared')]
    /**
     * Clear a specific filter or all filters from an external event.
     */
    public function clearFilter(string $type): void
    {
        match ($type) {
            'search' => $this->search = '',
            'category' => $this->categorySlug = null,
            'tag' => $this->tagSlug = null,
            'all' => $this->reset(['search', 'categorySlug', 'tagSlug']),
            default => null,
        };

        $this->resetPage();
    }

    /**
     * Clear the search filter.
     */
    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Clear the category filter.
     */
    public function clearCategory(): void
    {
        $this->categorySlug = null;
        $this->resetPage();
    }

    /**
     * Clear the tag filter.
     */
    public function clearTag(): void
    {
        $this->tagSlug = null;
        $this->resetPage();
    }

    /**
     * Clear all active filters.
     */
    public function clearFilters(): void
    {
        $this->reset(['search', 'categorySlug', 'tagSlug']);
        $this->resetPage();
    }

    /**
     * Render the blog posts listing component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.blog-posts', [
            'featured' => $this->featuredPost,
            'posts' => $this->posts,
            'categoryTabs' => $this->categoryTabs,
            'tags' => $this->tags,
        ]);
    }
}
