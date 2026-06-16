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
            $query->where('title', 'like', '%'.$this->search.'%')
                ->orWhere('excerpt', 'like', '%'.$this->search.'%');
        }

        if ($this->categorySlug) {
            $category = Category::where('slug', $this->categorySlug)->firstOrFail();
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $category->id));
        }

        if ($this->tagSlug) {
            $tag = Tag::where('slug', $this->tagSlug)->firstOrFail();
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id));
        }

        // Debug: Log the SQL query
        // \Log::debug('BlogPosts SQL: '.$query->latest()->toSql());
        // \Log::debug('BlogPosts bindings: '.json_encode($query->latest()->getBindings()));

        $result = $query->latest()->paginate($this->perPage);

        // Debug: Log the count
        // \Log::debug('BlogPosts count: '.$result->total());

        return $result;
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
        unset($this->posts);
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
        return view('livewire.blog-posts');
    }
}
