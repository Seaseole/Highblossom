<?php

declare(strict_types=1);

use App\Domains\Content\Models\Category;
use App\Domains\Content\Models\Post;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Category')] class extends Component
{
    use WithPagination;

    public Category $category;

    public function mount(Category $category): void
    {
        abort_if($category->type !== 'post', 404);
        $this->category = $category;
    }

    public function getPostsProperty()
    {
        return Post::published()
            ->where('category_id', $this->category->id)
            ->with('author')
            ->latest('published_at')
            ->paginate(12);
    }
}; ?>

<flux:main :title="$category->name . ' - Blog'">
    <!-- Header -->
    <header class="relative pt-20 pb-16 overflow-hidden bg-surface">
        <div class="max-w-7xl mx-auto px-8 relative z-10">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors mb-6 text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                {{ __('Back to Blog') }}
            </a>

            <span class="inline-block py-1.5 px-4 rounded-full bg-primary-container/10 text-primary font-semibold text-sm tracking-wide mb-6">
                {{ __('Category') }}
            </span>

            <h1 class="text-5xl md:text-6xl font-headline font-semibold text-on-surface tracking-tight leading-[0.95] mb-6 text-wrap-balance">
                {{ $category->name }}
            </h1>

            @if($category->description)
                <p class="text-on-surface-variant text-lg max-w-2xl leading-relaxed">
                    {{ $category->description }}
                </p>
            @endif
        </div>

        <!-- Ambient decoration -->
        <div class="absolute top-0 right-0 -z-10 w-1/2 h-full opacity-20 pointer-events-none">
            <div class="absolute top-1/4 right-1/4 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
        </div>
    </header>

    <!-- Posts -->
    <section class="py-28 bg-surface">
        <div class="max-w-7xl mx-auto px-8">
            @if($this->posts->isEmpty())
                <div class="glass-card p-16 rounded-2xl text-center border border-white/30 shadow-[0_8px_32px_-12px_rgba(0,0,0,0.15)] max-w-2xl mx-auto">
                    <span class="material-symbols-outlined text-6xl text-surface-container-highest mb-6 block">folder_open</span>
                    <h3 class="text-2xl font-headline font-semibold text-on-surface mb-3">{{ __('No posts in this category') }}</h3>
                    <p class="text-on-surface-variant text-lg">{{ __('Check back later for new content.') }}</p>
                </div>
            @else
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($this->posts as $post)
                        <article class="group flex flex-col bg-surface-container-low rounded-2xl overflow-hidden border border-outline-variant/10 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">
                            @if($post->featured_image)
                                <a href="{{ route('blog.show', $post) }}" class="block aspect-[16/10] overflow-hidden">
                                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
                                </a>
                            @endif

                            <div class="flex-1 p-6">
                                <h2 class="text-lg font-headline font-semibold text-on-surface mb-3 leading-snug text-wrap-pretty">
                                    <a href="{{ route('blog.show', $post) }}" class="hover:text-primary transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h2>

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

                <div class="mt-16">
                    {{ $this->posts->links() }}
                </div>
            @endif
        </div>
    </section>
</flux:main>
