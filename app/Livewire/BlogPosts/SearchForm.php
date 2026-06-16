<?php

declare(strict_types=1);

namespace App\Livewire\BlogPosts;

use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Search form that dispatches search-updated events to the blog listing.
 */
final class SearchForm extends Component
{
    #[Url]
    public string $search = '';

    /**
     * Initialize the component with an optional search value.
     */
    public function mount(string $search = ''): void
    {
        $this->search = $search;
    }

    /**
     * Dispatch the search-updated event when the search term changes.
     */
    public function updatedSearch(): void
    {
        $this->dispatch('search-updated', search: $this->search);
    }

    /**
     * Clear the search term and notify the blog listing.
     */
    public function clearSearch(): void
    {
        $this->search = '';
        $this->dispatch('search-updated', search: '');
    }

    /**
     * Render the search form component.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.blog-posts.search-form');
    }
}
