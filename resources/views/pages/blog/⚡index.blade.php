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
    <!-- Header - Left Aligned Asymmetric (DESIGN_VARIANCE: 8) -->
    <header class="relative pt-20 pb-16 overflow-hidden bg-surface">
        <div class="max-w-7xl mx-auto px-8 grid lg:grid-cols-2 gap-16 items-end">
            <div class="relative z-10">
                <span class="inline-block py-1.5 px-4 mb-6 rounded-full bg-primary-container/10 text-primary font-semibold text-sm tracking-wide">
                    Company Insights
                </span>
                <h1 class="text-5xl md:text-6xl font-headline font-semibold text-on-surface tracking-tight leading-[0.95] mb-6 text-wrap-balance">
                    {{ __('Latest Articles') }}
                </h1>
                <p class="text-on-surface-variant text-lg max-w-lg leading-relaxed">
                    {{ __('Industry insights, maintenance tips, and stories from Gaborone\'s leading automotive glass specialists.') }}
                </p>
            </div>

            <!-- Search - Right Side -->
            <div class="relative z-10 lg:pb-2">
                <div class="relative max-w-md lg:ml-auto">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-variation-settings: 'FILL' 0;">search</span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="{{ __('Search articles...') }}"
                        class="w-full pl-12 pr-4 py-4 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface placeholder:text-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/30 transition-all shadow-sm"
                    >
                </div>
            </div>
        </div>

        <!-- Ambient decoration -->
        <div class="absolute top-0 right-0 -z-10 w-1/2 h-full opacity-20 pointer-events-none">
            <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/3 w-64 h-64 bg-secondary/8 rounded-full blur-3xl"></div>
        </div>
    </header>

    <!-- Category Filter Bar -->
    @if($this->categories->isNotEmpty())
        <section class="bg-surface-container-low py-6 border-y border-outline-variant/10">
            <div class="max-w-7xl mx-auto px-8">
                <div class="flex flex-wrap gap-3 items-center">
                    <span class="text-sm font-medium text-on-surface-variant mr-2">Filter:</span>
                    <button
                        wire:click="$set('category', null)"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ !$category ? 'bg-primary text-on-primary shadow-lg shadow-primary/20' : 'bg-surface text-on-surface-variant hover:text-primary hover:bg-primary/5' }}"
                    >
                        {{ __('All') }}
                    </button>
                    @foreach($this->categories as $cat)
                        <button
                            wire:click="$set('category', '{{ $cat->slug }}')"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ $category === $cat->slug ? 'bg-primary text-on-primary shadow-lg shadow-primary/20' : 'bg-surface text-on-surface-variant hover:text-primary hover:bg-primary/5' }}"
                        >
                            {{ $cat->name }}
                            <span class="ml-1.5 opacity-70 text-xs">({{ $cat->posts_count }})</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Posts Content - Full Width No Sidebar -->
    <section class="py-28 bg-surface">
        <div class="max-w-7xl mx-auto px-8">
            @if($this->posts->isEmpty())
                <div class="glass-card p-16 rounded-2xl text-center border border-white/30 shadow-[0_8px_32px_-12px_rgba(0,0,0,0.15)] max-w-2xl mx-auto">
                    <span class="material-symbols-outlined text-6xl text-surface-container-highest mb-6 block">article</span>
                    <h3 class="text-2xl font-headline font-semibold text-on-surface mb-3">{{ __('No posts found') }}</h3>
                    <p class="text-on-surface-variant text-lg">{{ __('Try adjusting your search or category filter.') }}</p>
                </div>
            @else
                @php($firstPost = $this->posts->first())
                @php($remainingPosts = $this->posts->slice(1))

                @if(!$this->search && !$this->category && !$this->tag && $this->posts->currentPage() === 1)
                    <!-- Asymmetric Bento Layout: Featured Post (2/3) + 2 stacked (1/3) -->
                    <div class="grid md:grid-cols-12 gap-6 mb-8">
                        <!-- Featured Post - Large (spans 8 cols) -->
                        <article class="md:col-span-8 group relative overflow-hidden rounded-2xl bg-surface-container-low border border-outline-variant/10 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">
                            @if($firstPost->featured_image)
                                <a href="{{ route('blog.show', $firstPost) }}" class="block aspect-[16/10] overflow-hidden">
                                    <img src="{{ $firstPost->featured_image }}" alt="{{ $firstPost->title }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
                                </a>
                            @endif
                            <div class="p-8">
                                @if($firstPost->category)
                                    <a href="{{ route('blog.category', $firstPost->category) }}" class="inline-block py-1.5 px-3 rounded-full bg-primary/10 text-primary text-xs font-semibold tracking-wide mb-4 hover:bg-primary/15 transition-colors">
                                        {{ $firstPost->category->name }}
                                    </a>
                                @endif
                                <h2 class="text-2xl md:text-3xl font-headline font-semibold text-on-surface mb-4 leading-tight text-wrap-balance">
                                    <a href="{{ route('blog.show', $firstPost) }}" class="hover:text-primary transition-colors">
                                        {{ $firstPost->title }}
                                    </a>
                                </h2>
                                @if($firstPost->excerpt)
                                    <p class="text-on-surface-variant leading-relaxed mb-6 text-wrap-pretty max-w-xl">
                                        {{ $firstPost->excerpt }}
                                    </p>
                                @endif
                                <div class="flex items-center gap-5 text-sm text-on-surface-variant">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 0;">person</span>
                                        <span>{{ $firstPost->author?->name ?? 'Team' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 0;">calendar_today</span>
                                        <span>{{ $firstPost->published_at?->format('M j, Y') }}</span>
                                    </div>
                                    @if($firstPost->reading_time_minutes)
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 0;">schedule</span>
                                            <span>{{ $firstPost->reading_time_minutes }} min read</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </article>

                        <!-- Side Stack - 2 smaller posts (spans 4 cols, 2 rows) -->
                        <div class="md:col-span-4 grid grid-rows-2 gap-6">
                            @foreach($remainingPosts->take(2) as $post)
                                <article class="group relative overflow-hidden rounded-2xl bg-surface-container-low border border-outline-variant/10 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">
                                    @if($post->featured_image)
                                        <a href="{{ route('blog.show', $post) }}" class="block aspect-[16/9] overflow-hidden">
                                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
                                        </a>
                                    @endif
                                    <div class="p-5">
                                        @if($post->category)
                                            <a href="{{ route('blog.category', $post->category) }}" class="inline-block py-1 px-2.5 rounded-full bg-primary/10 text-primary text-[10px] font-semibold tracking-wide mb-2 hover:bg-primary/15 transition-colors">
                                                {{ $post->category->name }}
                                            </a>
                                        @endif
                                        <h3 class="text-base font-headline font-semibold text-on-surface mb-2 leading-snug line-clamp-2">
                                            <a href="{{ route('blog.show', $post) }}" class="hover:text-primary transition-colors">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                                            <span>{{ $post->published_at?->format('M j, Y') }}</span>
                                            @if($post->reading_time_minutes)
                                                <span class="w-1 h-1 rounded-full bg-on-surface-variant/40"></span>
                                                <span>{{ $post->reading_time_minutes }} min</span>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <!-- Remaining Posts - 2 Column Grid (continues bento asymmetric feel) -->
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($remainingPosts->slice(2) as $index => $post)
                            <article class="group flex flex-col bg-surface-container-low rounded-2xl overflow-hidden border border-outline-variant/10 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">
                                @if($post->featured_image)
                                    <a href="{{ route('blog.show', $post) }}" class="block aspect-[16/10] overflow-hidden">
                                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
                                    </a>
                                @endif
                                <div class="flex-1 p-6">
                                    @if($post->category)
                                        <a href="{{ route('blog.category', $post->category) }}" class="inline-block py-1 px-3 rounded-full bg-primary/10 text-primary text-xs font-semibold tracking-wide mb-3 hover:bg-primary/15 transition-colors">
                                            {{ $post->category->name }}
                                        </a>
                                    @endif
                                    <h3 class="text-lg font-headline font-semibold text-on-surface mb-3 leading-snug text-wrap-pretty">
                                        <a href="{{ route('blog.show', $post) }}" class="hover:text-primary transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    @if($post->excerpt)
                                        <p class="text-on-surface-variant text-sm leading-relaxed mb-4 line-clamp-2 text-wrap-pretty">
                                            {{ $post->excerpt }}
                                        </p>
                                    @endif
                                    <div class="mt-auto flex items-center gap-3 text-xs text-on-surface-variant">
                                        <span>{{ $post->published_at?->format('M j, Y') }}</span>
                                        @if($post->reading_time_minutes)
                                            <span class="w-1 h-1 rounded-full bg-on-surface-variant/40"></span>
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
                            <article class="group flex flex-col bg-surface-container-low rounded-2xl overflow-hidden border border-outline-variant/10 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">
                                @if($post->featured_image)
                                    <a href="{{ route('blog.show', $post) }}" class="block aspect-[16/10] overflow-hidden">
                                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
                                    </a>
                                @endif
                                <div class="flex-1 p-6">
                                    @if($post->category)
                                        <a href="{{ route('blog.category', $post->category) }}" class="inline-block py-1 px-3 rounded-full bg-primary/10 text-primary text-xs font-semibold tracking-wide mb-3 hover:bg-primary/15 transition-colors">
                                            {{ $post->category->name }}
                                        </a>
                                    @endif
                                    <h3 class="text-lg font-headline font-semibold text-on-surface mb-3 leading-snug text-wrap-pretty">
                                        <a href="{{ route('blog.show', $post) }}" class="hover:text-primary transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    @if($post->excerpt)
                                        <p class="text-on-surface-variant text-sm leading-relaxed mb-4 line-clamp-2 text-wrap-pretty">
                                            {{ $post->excerpt }}
                                        </p>
                                    @endif
                                    <div class="mt-auto flex items-center gap-3 text-xs text-on-surface-variant">
                                        <span>{{ $post->published_at?->format('M j, Y') }}</span>
                                        @if($post->reading_time_minutes)
                                            <span class="w-1 h-1 rounded-full bg-on-surface-variant/40"></span>
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
        <section class="py-16 bg-surface-container-low border-t border-outline-variant/10">
            <div class="max-w-7xl mx-auto px-8">
                <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
                    <h3 class="font-headline font-semibold text-on-surface flex items-center gap-2 flex-shrink-0">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">sell</span>
                        {{ __('Browse by Topic') }}
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($this->tags as $tag)
                            <a
                                href="{{ route('blog.tag', $tag) }}"
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 bg-surface text-on-surface-variant hover:text-primary hover:bg-primary/5 border border-outline-variant/10"
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
