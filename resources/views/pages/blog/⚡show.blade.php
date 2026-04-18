<?php

declare(strict_types=1);

use App\Domains\Content\Actions\Posts\CalculateReadingTime;
use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\PostView;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Title('Blog Post'), Layout('layouts.site')] class extends Component
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

<div>
<x-layouts::site :title="$post->title">
    <!-- Header -->
    <header class="relative pt-32 pb-16 overflow-hidden bg-[#0A0A0F]">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 relative z-10">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-[#71717A] hover:text-[#DC2626] transition-colors mb-6 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Blog
            </a>

            @if($post->category)
                <a href="{{ route('blog.category', $post->category) }}" class="inline-block py-1.5 px-4 rounded-full bg-[#DC2626]/10 text-[#DC2626] text-sm font-semibold tracking-wide mb-6 hover:bg-[#DC2626]/20 transition-colors">
                    {{ $post->category->name }}
                </a>
            @endif

            <h1 class="text-4xl md:text-5xl font-headline font-bold text-[#FAFAFA] tracking-tight leading-tight mb-6">
                {{ $post->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-5 text-sm text-[#71717A]">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>{{ $post->author?->name ?? 'Team' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $post->published_at?->format('F j, Y') }}</span>
                </div>
                @if($post->reading_time_minutes)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $post->reading_time_minutes }} min read</span>
                    </div>
                @endif
            </div>

            @if($post->tags->isNotEmpty())
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}" class="text-sm text-[#71717A] hover:text-[#DC2626] transition-colors">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Ambient decoration -->
        <div class="absolute top-0 left-0 -z-10 w-1/3 h-full opacity-30 pointer-events-none">
            <div class="absolute top-1/3 left-1/4 w-64 h-64 bg-[#DC2626]/10 rounded-full blur-3xl"></div>
        </div>
    </header>

    <!-- Featured Image -->
    @if($post->featured_image)
        <section class="bg-[#0A0A0F] pb-12">
            <div class="max-w-5xl mx-auto px-6 lg:px-8">
                <div class="rounded-2xl overflow-hidden">
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-auto object-cover">
                </div>
            </div>
        </section>
    @endif

    <!-- Content -->
    <article class="py-12 bg-[#0A0A0F]">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <!-- Content Blocks -->
            <div class="space-y-8 text-[#A1A1AA] leading-relaxed">
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
                <div class="mt-16 pt-8 border-t border-white/10">
                    <h3 class="font-headline font-bold text-[#FAFAFA] mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Tags
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('blog.tag', $tag) }}" class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 glass-card text-[#A1A1AA] hover:text-[#DC2626] hover:bg-[#DC2626]/10">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Author Bio -->
            @if($post->author)
                <div class="mt-12 glass-card p-8 rounded-2xl">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-[#DC2626] flex items-center justify-center text-white font-headline font-bold text-xl">
                            {{ strtoupper(substr($post->author->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-headline font-bold text-[#FAFAFA] text-lg">{{ $post->author->name }}</h3>
                            <p class="text-sm text-[#71717A]">Author</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Social Share -->
            <div class="mt-8 pt-8 border-t border-white/10">
                <x-blog::social-share :post="$post" />
            </div>
        </div>
    </article>

    <!-- Related Posts -->
    @if($this->relatedPosts->isNotEmpty())
        <section class="py-20 bg-[#121218] border-t border-white/5">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
                <h3 class="text-2xl font-headline font-bold text-[#FAFAFA] mb-10 flex items-center gap-3">
                    <svg class="w-6 h-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Related Posts
                </h3>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($this->relatedPosts as $related)
                        <article class="group flex flex-col glass-card rounded-2xl overflow-hidden hover:-translate-y-1 transition-all duration-300">
                            @if($related->featured_image)
                                <a href="{{ route('blog.show', $related) }}" class="block aspect-[16/10] overflow-hidden">
                                    <img src="{{ $related->featured_image }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </a>
                            @endif
                            <div class="flex-1 p-6">
                                <h4 class="font-headline font-bold text-[#FAFAFA] mb-2 leading-snug group-hover:text-[#DC2626] transition-colors line-clamp-2">
                                    <a href="{{ route('blog.show', $related) }}">
                                        {{ $related->title }}
                                    </a>
                                </h4>
                                <p class="text-xs text-[#71717A]">{{ $related->published_at?->format('M j, Y') }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts::site>
</div>
