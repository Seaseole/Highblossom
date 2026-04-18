<?php

declare(strict_types=1);

use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\Tag;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Tag')] class extends Component
{
    use WithPagination;

    public Tag $tag;

    public function mount(Tag $tag): void
    {
        $this->tag = $tag;
    }

    public function getPostsProperty()
    {
        return Post::published()
            ->whereHas('tags', fn ($q) => $q->where('tags.id', $this->tag->id))
            ->with('author')
            ->latest('published_at')
            ->paginate(12);
    }
}; ?>

<flux:main :title="'#' . $tag->name . ' - Blog'">
    <!-- Header -->
    <header class="relative pt-32 pb-20 overflow-hidden bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8 relative z-10">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-[#71717A] hover:text-[#DC2626] transition-colors mb-6 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Blog
            </a>

            <span class="inline-block py-1.5 px-4 rounded-full bg-[#DC2626]/10 text-[#DC2626] font-semibold text-sm tracking-wide mb-6">
                Tag
            </span>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-headline font-bold text-[#FAFAFA] tracking-tight leading-tight mb-6">
                #{{ $tag->name }}
            </h1>

            <p class="text-[#A1A1AA] text-lg leading-relaxed">
                {{ $this->posts->total() }} posts tagged with #{{ $tag->name }}
            </p>
        </div>

        <!-- Ambient decoration -->
        <div class="absolute top-0 right-0 -z-10 w-1/2 h-full opacity-30 pointer-events-none">
            <div class="absolute top-1/4 right-1/4 w-64 h-64 bg-[#DC2626]/10 rounded-full blur-3xl"></div>
        </div>
    </header>

    <!-- Posts -->
    <section class="py-24 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            @if($this->posts->isEmpty())
                <div class="glass-card p-16 rounded-2xl text-center max-w-2xl mx-auto">
                    <svg class="w-16 h-16 text-[#71717A] mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <h3 class="text-2xl font-headline font-bold text-[#FAFAFA] mb-3">No posts with this tag</h3>
                    <p class="text-[#71717A] text-lg">Check back later for new content.</p>
                </div>
            @else
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

                                <h2 class="text-lg font-headline font-bold text-[#FAFAFA] mb-3 leading-snug">
                                    <a href="{{ route('blog.show', $post) }}" class="hover:text-[#DC2626] transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h2>

                                @if($post->excerpt)
                                    <p class="text-[#A1A1AA] text-sm leading-relaxed mb-4 line-clamp-2">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif

                                <div class="mt-auto flex items-center gap-3 text-xs text-[#71717A]">
                                    <span>{{ $post->author?->name ?? 'Team' }}</span>
                                    <span class="w-1 h-1 rounded-full bg-[#71717A]/40"></span>
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

                <div class="mt-16">
                    {{ $this->posts->links() }}
                </div>
            @endif
        </div>
    </section>
</flux:main>
