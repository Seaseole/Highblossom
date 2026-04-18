<?php

declare(strict_types=1);

use App\Domains\Content\Actions\Posts\CalculateReadingTime;
use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\PostView;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Blog Post')] class extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        // Track view (throttled by IP)
        PostView::record($post);

        // Calculate reading time if not set
        if ($post->reading_time_minutes === null) {
            $minutes = app(CalculateReadingTime::class)->execute($post);
            $post->update(['reading_time_minutes' => $minutes]);
            $post->refresh();
        }

        $this->post = $post->load([
            'author',
            'category',
            'tags',
            'contentBlocks' => fn ($q) => $q->where('is_visible', true)->orderBy('sort_order'),
        ]);
    }

    public function getRelatedPostsProperty()
    {
        return Post::published()
            ->where('id', '!=', $this->post->id)
            ->where(function ($query) {
                if ($this->post->category_id) {
                    $query->where('category_id', $this->post->category_id);
                }
                if ($this->post->tags->isNotEmpty()) {
                    $query->orWhereHas('tags', fn ($q) => $q->whereIn('tags.id', $this->post->tags->pluck('id')));
                }
            })
            ->latest('published_at')
            ->limit(3)
            ->get();
    }
}; ?>

<flux:main :title="$post->title">
    <!-- Header -->
    <header class="relative pt-20 pb-16 overflow-hidden bg-surface">
        <div class="max-w-4xl mx-auto px-8 relative z-10">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors mb-6 text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                {{ __('Back to Blog') }}
            </a>

            @if($post->category)
                <a href="{{ route('blog.category', $post->category) }}" class="inline-block py-1.5 px-4 rounded-full bg-primary/10 text-primary text-sm font-semibold tracking-wide mb-6 hover:bg-primary/15 transition-colors">
                    {{ $post->category->name }}
                </a>
            @endif

            <h1 class="text-4xl md:text-5xl font-headline font-semibold text-on-surface tracking-tight leading-[0.95] mb-6 text-wrap-balance">
                {{ $post->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-5 text-sm text-on-surface-variant">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 0;">person</span>
                    <span>{{ $post->author?->name ?? 'Team' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 0;">calendar_today</span>
                    <span>{{ $post->published_at?->format('F j, Y') }}</span>
                </div>
                @if($post->reading_time_minutes)
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 0;">schedule</span>
                        <span>{{ $post->reading_time_minutes }} min read</span>
                    </div>
                @endif
            </div>

            @if($post->tags->isNotEmpty())
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Ambient decoration -->
        <div class="absolute top-0 left-0 -z-10 w-1/3 h-full opacity-20 pointer-events-none">
            <div class="absolute top-1/3 left-1/4 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
        </div>
    </header>

    <!-- Featured Image -->
    @if($post->featured_image)
        <section class="bg-surface pb-12">
            <div class="max-w-5xl mx-auto px-8">
                <div class="rounded-2xl overflow-hidden shadow-xl shadow-primary/5">
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-auto object-cover">
                </div>
            </div>
        </section>
    @endif

    <!-- Content -->
    <article class="py-12 bg-surface">
        <div class="max-w-4xl mx-auto px-8">
            <!-- Content Blocks -->
            <div class="space-y-8">
                @foreach($post->contentBlocks as $block)
                    @php
                        $blockRegistry = app(\App\Domains\Content\Services\BlockRegistry::class);
                        $blockType = $blockRegistry->find($block->type);
                    @endphp

                    @if($blockType)
                        <x-dynamic-component
                            :component="$blockType::component()"
                            :attributes="new \Illuminate\View\ComponentAttributeBag($block->content)"
                        />
                    @endif
                @endforeach
            </div>

            <!-- Tags -->
            @if($post->tags->isNotEmpty())
                <div class="mt-16 pt-8 border-t border-outline-variant/20">
                    <h3 class="font-headline font-semibold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">sell</span>
                        {{ __('Tags') }}
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('blog.tag', $tag) }}" class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 bg-surface-container-low text-on-surface-variant hover:text-primary hover:bg-primary/5 border border-outline-variant/10">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Author Bio -->
            @if($post->author)
                <div class="mt-12 glass-card p-8 rounded-2xl border border-white/30 shadow-[0_8px_32px_-12px_rgba(0,0,0,0.15)]">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-primary flex items-center justify-center text-on-primary font-headline font-bold text-xl">
                            {{ strtoupper(substr($post->author->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-headline font-semibold text-on-surface text-lg">{{ $post->author->name }}</h3>
                            <p class="text-sm text-on-surface-variant">{{ __('Author') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Social Share -->
            <div class="mt-8 pt-8 border-t border-outline-variant/10">
                <x-blog::social-share :post="$post" />
            </div>
        </div>
    </article>

    <!-- Related Posts -->
    @if($this->relatedPosts->isNotEmpty())
        <section class="py-20 bg-surface-container-low border-t border-outline-variant/10">
            <div class="max-w-7xl mx-auto px-8">
                <h3 class="text-2xl font-headline font-semibold text-on-surface mb-10 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">auto_stories</span>
                    {{ __('Related Posts') }}
                </h3>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($this->relatedPosts as $related)
                        <article class="group flex flex-col bg-surface rounded-2xl overflow-hidden border border-outline-variant/10 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">
                            @if($related->featured_image)
                                <a href="{{ route('blog.show', $related) }}" class="block aspect-[16/10] overflow-hidden">
                                    <img src="{{ $related->featured_image }}" alt="" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
                                </a>
                            @endif
                            <div class="flex-1 p-6">
                                <h4 class="font-headline font-semibold text-on-surface mb-2 leading-snug group-hover:text-primary transition-colors line-clamp-2">
                                    <a href="{{ route('blog.show', $related) }}">
                                        {{ $related->title }}
                                    </a>
                                </h4>
                                <p class="text-xs text-on-surface-variant">{{ $related->published_at?->format('M j, Y') }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</flux:main>
