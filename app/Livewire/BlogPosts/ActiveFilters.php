<?php

declare(strict_types=1);

namespace App\Livewire\BlogPosts;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Display and clear active blog post filters.
 */
final class ActiveFilters extends Component
{
    public string $search = '';

    public ?string $categorySlug = null;

    public ?string $tagSlug = null;

    /**
     * Initialize the component with the active filter values.
     */
    public function mount(string $search = '', ?string $categorySlug = null, ?string $tagSlug = null): void
    {
        $this->search = $search;
        $this->categorySlug = $categorySlug;
        $this->tagSlug = $tagSlug;
    }

    /**
     * Clear the search filter and notify the blog listing.
     */
    public function clearSearch(): void
    {
        $this->search = '';
        $this->dispatch('filter-cleared', type: 'search');
    }

    /**
     * Clear the category filter and notify the blog listing.
     */
    public function clearCategory(): void
    {
        $this->categorySlug = null;
        $this->dispatch('filter-cleared', type: 'category');
    }

    /**
     * Clear the tag filter and notify the blog listing.
     */
    public function clearTag(): void
    {
        $this->tagSlug = null;
        $this->dispatch('filter-cleared', type: 'tag');
    }

    /**
     * Clear all filters and notify the blog listing.
     */
    public function clearAll(): void
    {
        $this->search = '';
        $this->categorySlug = null;
        $this->tagSlug = null;
        $this->dispatch('filter-cleared', type: 'all');
    }

    /**
     * Render the active filters component.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.blog-posts.active-filters', [
            'category' => $this->categorySlug ? Category::where('slug', $this->categorySlug)->first() : null,
            'tag' => $this->tagSlug ? Tag::where('slug', $this->tagSlug)->first() : null,
        ]);
    }
}
