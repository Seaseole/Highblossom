<?php

namespace App\Livewire;


use App\Domains\Content\Models\Category;
use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\Tag;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

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
    
    public function mount(string $search = '', ?string $categorySlug = null, ?string $tagSlug = null): void
    {
        $this->search = $search;
        $this->categorySlug = $categorySlug;
        $this->tagSlug = $tagSlug;
    }

    public function placeholder(): View
    {
        return view('livewire.blog-posts.placeholder');
    }

    #[Computed]
    public function posts()
    {
        $query = Post::published()->with('categories', 'tags', 'author');

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('excerpt', 'like', '%' . $this->search . '%');
        }

        if ($this->categorySlug) {
            $category = Category::where('slug', $this->categorySlug)->firstOrFail();
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $category->id));
        }

        if ($this->tagSlug) {
            $tag = Tag::where('slug', $this->tagSlug)->firstOrFail();
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id));
        }

        return $query->latest()->paginate($this->perPage);
    }

    #[Computed]
    public function categories()
    {
        return Category::all();
    }

    #[Computed]
    public function tags()
    {
        return Tag::all();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategorySlug(): void
    {
        $this->resetPage();
    }

    public function updatedTagSlug(): void
    {
        $this->resetPage();
    }

    #[On('search-updated')]
    public function updateSearch(string $search): void
    {
        $this->search = $search;
        unset($this->posts);
        $this->resetPage();
    }

    #[On('filter-cleared')]
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

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function clearCategory(): void
    {
        $this->categorySlug = null;
        $this->resetPage();
    }

    public function clearTag(): void
    {
        $this->tagSlug = null;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'categorySlug', 'tagSlug']);
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.blog-posts');
    }
}
