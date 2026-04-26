<?php

namespace App\Livewire\BlogPosts;

use Livewire\Component;
use Livewire\Attributes\Url;

final class SearchForm extends Component
{
    #[Url]
    public string $search = '';
    
    public function mount(string $search = ''): void
    {
        $this->search = $search;
    }
    
    public function updatedSearch(): void
    {
        $this->dispatch('search-updated', search: $this->search);
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->dispatch('search-updated', search: '');
    }

    public function render()
    {
        return view('livewire.blog-posts.search-form');
    }
}
