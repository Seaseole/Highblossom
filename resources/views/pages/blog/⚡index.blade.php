<?php

declare(strict_types=1);

use App\Domains\Content\Models\Category;
use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\Tag;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Blog')] class extends Component
{
    use WithPagination;

    public ?string $category = null;
    public ?string $tag = null;
    public string $search = '';

    public function mount(?string $category = null, ?string $tag = null): void
    {
        $this->category = $category;
        $this->tag = $tag;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function getPostsProperty()
    {
        return Post::query()
            ->published()
            ->with(['author', 'category'])
            ->when($this->category, function ($query) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $this->category));
            })
            ->when($this->tag, function ($query) {
                $query->whereHas('tags', fn ($q) => $q->where('slug', $this->tag));
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('excerpt', 'like', '%' . $this->search . '%');
                });
            })
            ->latest('published_at')
            ->paginate(12);
    }

    public function getCategoriesProperty()
    {
        return Category::forPosts()->withCount(['posts' => fn ($q) => $q->published()])->get();
    }

    public function getTagsProperty()
    {
        return Tag::withCount(['posts' => fn ($q) => $q->published()])->get();
    }

    public function getFeaturedPostsProperty()
    {
        return Post::published()
            ->featured()
            ->with('author')
            ->latest('published_at')
            ->limit(3)
            ->get();
    }
}; ?>

<flux:main title="{{ $this->category ? ($this->getCategories()->firstWhere('slug', $this->category)?->name . ' - Blog') : ($this->tag ? ($this->getTags()->firstWhere('slug', $this->tag)?->name . ' - Blog') : 'Blog') }}">
    <!-- Header -->
    <header class="relative pt-32 pb-20 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-end">
                <div>
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">Company Blog</div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                        Latest Articles
                    </h1>
                    <p class="text-lg text-[#A1A1AA] max-w-lg">
                        Industry insights, maintenance tips, and stories from Gaborone's leading automotive glass specialists.
                    </p>
                </div>

                <!-- Search -->
                <div class="lg:pb-2">
                    <div class="relative max-w-md lg:ml-auto">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-[#71717A]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search articles..."
                            class="w-full pl-12 pr-4 py-4 rounded-xl glass-card text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:border-[#DC2626]/50 transition-colors"
                        >
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Category Filter Bar -->
    @if($this->categories->isNotEmpty())
        <section class="py-6 bg-[#0A0A0F] border-t border-white/5">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
                <div class="flex flex-wrap gap-3 items-center">
                    <span class="text-[#71717A] text-sm mr-2">Filter:</span>
                    <button
                        wire:click="$set('category', null)"
                        class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ !$category ? 'bg-[#DC2626] text-white' : 'glass-card text-[#A1A1AA] hover:text-[#FAFAFA]' }}"
                    >
                        All
                    </button>
                    @foreach($this->categories as $cat)
                        <button
                            wire:click="$set('category', '{{ $cat->slug }}')"
                            class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ $category === $cat->slug ? 'bg-[#DC2626] text-white' : 'glass-card text-[#A1A1AA] hover:text-[#FAFAFA]' }}"
                        >
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Posts Content -->
    <section class="py-24 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            @if($this->posts->isEmpty())
                <div class="glass-card rounded-2xl p-16 text-center max-w-2xl mx-auto">
                    <svg class="w-16 h-16 text-[#71717A] mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-[#FAFAFA] font-headline mb-3">No Posts Found</h3>
                    <p class="text-[#71717A]">Try adjusting your search or category filter.</p>
                </div>
            @else
                @php($firstPost = $this->posts->first())
                @php($remainingPosts = $this->posts->slice(1))

                @if(!$this->search && !$this->category && !$this->tag && $this->posts->currentPage() === 1)
                    <!-- Featured Layout: Main Post + Side Posts -->
                    <div class="grid md:grid-cols-12 gap-6 mb-8">
                        <!-- Featured Post - Large -->
                        <article class="md:col-span-8 group relative overflow-hidden rounded-2xl glass-card hover:-translate-y-1 transition-all duration-300">
                            @if($firstPost->featured_image)
                                <a href="{{ route('blog.show', $firstPost) }}" class="block aspect-[16/10] overflow-hidden">
                                    <img src="{{ $firstPost->featured_image }}" alt="{{ $firstPost->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </a>
                            @endif
                            <div class="p-8">
                                @if($firstPost->category)
                                    <a href="{{ route('blog.category', $firstPost->category) }}" class="inline-block py-1.5 px-3 rounded-full bg-[#DC2626]/10 text-[#DC2626] text-xs font-semibold tracking-wide mb-4 hover:bg-[#DC2626]/20 transition-colors">
                                        {{ $firstPost->category->name }}
                                    </a>
                                @endif
                                <h2 class="text-2xl md:text-3xl font-headline font-bold text-[#FAFAFA] mb-4 leading-tight">
                                    <a href="{{ route('blog.show', $firstPost) }}" class="hover:text-[#DC2626] transition-colors">
                                        {{ $firstPost->title }}
                                    </a>
                                </h2>
                                @if($firstPost->excerpt)
                                    <p class="text-[#A1A1AA] leading-relaxed mb-6 max-w-xl">
                                        {{ $firstPost->excerpt }}
                                    </p>
                                @endif
                                <div class="flex items-center gap-5 text-sm text-[#71717A]">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>{{ $firstPost->author?->name ?? 'Team' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ $firstPost->published_at?->format('M j, Y') }}</span>
                                    </div>
                                    @if($firstPost->reading_time_minutes)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ $firstPost->reading_time_minutes }} min read</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </article>

                        <!-- Side Stack - 2 smaller posts -->
                        <div class="md:col-span-4 grid grid-rows-2 gap-6">
                            @foreach($remainingPosts->take(2) as $post)
                                <article class="group relative overflow-hidden rounded-2xl glass-card hover:-translate-y-1 transition-all duration-300">
                                    @if($post->featured_image)
                                        <a href="{{ route('blog.show', $post) }}" class="block aspect-[16/9] overflow-hidden">
                                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        </a>
                                    @endif
                                    <div class="p-5">
                                        @if($post->category)
                                            <a href="{{ route('blog.category', $post->category) }}" class="inline-block py-1 px-2.5 rounded-full bg-[#DC2626]/10 text-[#DC2626] text-[10px] font-semibold tracking-wide mb-2 hover:bg-[#DC2626]/20 transition-colors">
                                                {{ $post->category->name }}
                                            </a>
                                        @endif
                                        <h3 class="text-base font-headline font-semibold text-[#FAFAFA] mb-2 leading-snug line-clamp-2">
                                            <a href="{{ route('blog.show', $post) }}" class="hover:text-[#DC2626] transition-colors">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <div class="flex items-center gap-2 text-xs text-[#71717A]">
                                            <span>{{ $post->published_at?->format('M j, Y') }}</span>
                                            @if($post->reading_time_minutes)
                                                <span class="w-1 h-1 rounded-full bg-[#71717A]/40"></span>
                                                <span>{{ $post->reading_time_minutes }} min</span>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <!-- Remaining Posts - 2 Column Grid -->
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($remainingPosts->slice(2) as $index => $post)
                            <article class="group flex flex-col glass-card rounded-2xl overflow-hidden hover:-translate-y-1 transition-all duration-300">
                                @if($post->featured_image)
                                    <a href="{{ route('blog.show', $post) }}" class="block aspect-[16/10] overflow-hidden">
                                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    </a>
                                @endif
                                <div class="flex-1 p-6">
                                    @if($post->category)
                                        <a href="{{ route('blog.category', $post->category) }}" class="inline-block py-1 px-3 rounded-full bg-[#DC2626]/10 text-[#DC2626] text-xs font-semibold tracking-wide mb-3 hover:bg-[#DC2626]/20 transition-colors">
                                            {{ $post->category->name }}
                                        </a>
                                    @endif
                                    <h3 class="text-lg font-headline font-bold text-[#FAFAFA] mb-3 leading-snug">
                                        <a href="{{ route('blog.show', $post) }}" class="hover:text-[#DC2626] transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    @if($post->excerpt)
                                        <p class="text-[#A1A1AA] text-sm leading-relaxed mb-4 line-clamp-2">
                                            {{ $post->excerpt }}
                                        </p>
                                    @endif
                                    <div class="mt-auto flex items-center gap-3 text-xs text-[#71717A]">
                                        <span>{{ $post->published_at?->format('M j, Y') }}</span>
                                        @if($post->reading_time_minutes)
                                            <span class="w-1 h-1 rounded-full bg-[#71717A]/40"></span>
                                            <span>{{ $post->reading_time_minutes }} min</span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <!-- Filtered Results - Clean 2 Column Grid -->
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($this->posts as $post)
                            <article class="group flex flex-col glass-card rounded-2xl overflow-hidden hover:-translate-y-1 transition-all duration-300">
                                @if($post->featured_image)
                                    <a href="{{ route('blog.show', $post) }}" class="block aspect-[16/10] overflow-hidden">
                                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    </a>
                                @endif
                                <div class="flex-1 p-6">
                                    @if($post->category)
                                        <a href="{{ route('blog.category', $post->category) }}" class="inline-block py-1 px-3 rounded-full bg-[#DC2626]/10 text-[#DC2626] text-xs font-semibold tracking-wide mb-3 hover:bg-[#DC2626]/20 transition-colors">
                                            {{ $post->category->name }}
                                        </a>
                                    @endif
                                    <h3 class="text-lg font-headline font-bold text-[#FAFAFA] mb-3 leading-snug">
                                        <a href="{{ route('blog.show', $post) }}" class="hover:text-[#DC2626] transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    @if($post->excerpt)
                                        <p class="text-[#A1A1AA] text-sm leading-relaxed mb-4 line-clamp-2">
                                            {{ $post->excerpt }}
                                        </p>
                                    @endif
                                    <div class="mt-auto flex items-center gap-3 text-xs text-[#71717A]">
                                        <span>{{ $post->published_at?->format('M j, Y') }}</span>
                                        @if($post->reading_time_minutes)
                                            <span class="w-1 h-1 rounded-full bg-[#71717A]/40"></span>
                                            <span>{{ $post->reading_time_minutes }} min</span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                <!-- Pagination -->
                <div class="mt-16">
                    {{ $this->posts->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- Tags Section - Horizontal Bar -->
    @if($this->tags->isNotEmpty() && !$this->search && !$this->category)
        <section class="py-16 bg-[#121218] border-t border-white/5">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
                <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
                    <h3 class="font-headline font-bold text-[#FAFAFA] flex items-center gap-2 flex-shrink-0">
                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Browse by Topic
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($this->tags as $tag)
                            <a
                                href="{{ route('blog.tag', $tag) }}"
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 glass-card text-[#A1A1AA] hover:text-[#DC2626] hover:bg-[#DC2626]/10"
                            >
                                {{ $tag->name }}
                                <span class="opacity-60 ml-1">({{ $tag->posts_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
</flux:main>
