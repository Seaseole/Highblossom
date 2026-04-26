<?php

namespace App\Livewire\BlogPosts;

use App\Domains\Content\Models\Category;
use App\Domains\Content\Models\Tag;
use Livewire\Component;

final class ActiveFilters extends Component
{
    public string $search = '';
    public ?string $categorySlug = null;
    public ?string $tagSlug = null;

    public function mount(string $search = '', ?string $categorySlug = null, ?string $tagSlug = null): void
    {
        $this->search = $search;
        $this->categorySlug = $categorySlug;
        $this->tagSlug = $tagSlug;
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->dispatch('filter-cleared', type: 'search');
    }

    public function clearCategory(): void
    {
        $this->categorySlug = null;
        $this->dispatch('filter-cleared', type: 'category');
    }

    public function clearTag(): void
    {
        $this->tagSlug = null;
        $this->dispatch('filter-cleared', type: 'tag');
    }

    public function clearAll(): void
    {
        $this->search = '';
        $this->categorySlug = null;
        $this->tagSlug = null;
        $this->dispatch('filter-cleared', type: 'all');
    }

    public function render()
    {
        return view('livewire.blog-posts.active-filters', [
            'category' => $this->categorySlug ? Category::where('slug', $this->categorySlug)->first() : null,
            'tag' => $this->tagSlug ? Tag::where('slug', $this->tagSlug)->first() : null,
        ]);
    }
}
