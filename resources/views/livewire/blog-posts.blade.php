<div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
    {{-- Main Content --}}
    <div class="flex-1 min-w-0">
        @if($this->posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($this->posts as $index => $post)
                    @php
                    $wordCount = str_word_count(strip_tags($post->content['content'] ?? ''));
                    $readTime = max(1, ceil($wordCount / 200));
                    $author = $post->author ?? auth()->user();
                    @endphp

                    <article
                        class="group bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:border-[#DC2626]/30 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#DC2626]/5"
                        style="animation: fadeUp 0.6s ease-out {{ $index * 0.1 }}s both;"
                        wire:key="post-{{ $post->id }}"
                    >
                        {{-- Featured Image --}}
                        @if($post->featured_image_url)
                            <div class="aspect-[16/10] overflow-hidden">
                                <a href="{{ route('blog.show', $post->slug) }}" class="block h-full relative">
                                    <img
                                        src="{{ $post->featured_image_url }}"
                                        alt="{{ $post->title }}"
                                        class="w-full h-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-105"
                                        loading="lazy"
                                    >
                                </a>
                            </div>
                        @endif

                        {{-- Content --}}
                        <div class="p-6">
                            {{-- Date and Category --}}
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-sm text-[#71717A]">{{ $post->published_at?->format('M d, Y') }}</span>
                                @if($post->categories->count() > 0)
                                    <span class="w-1 h-1 rounded-full bg-white/20"></span>
                                    @foreach($post->categories->take(1) as $category)
                                        <a
                                            href="{{ route('blog', ['category' => $category->slug]) }}"
                                            class="text-sm font-medium text-[#DC2626] hover:text-[#B91C1C] transition-colors"
                                        >
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>

                            {{-- Title --}}
                            <h2 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3 group-hover:text-[#DC2626] transition-colors duration-300 line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="block">
                                    {{ $post->title }}
                                </a>
                            </h2>

                            {{-- Description --}}
                            @if($post->excerpt)
                                <p class="text-[#A1A1AA] leading-relaxed mb-4 line-clamp-3">
                                    {{ $post->excerpt }}
                                </p>
                            @endif

                            {{-- Read More Link --}}
                            <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center text-sm font-medium text-[#DC2626] hover:text-[#B91C1C] transition-colors mb-6">
                                Read More
                                <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>

                            {{-- Author Section --}}
                            @if($author)
                                <div class="flex items-center gap-3 pt-4 border-t border-white/5">
                                    <div class="w-10 h-10 rounded-full bg-[#DC2626]/20 flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-semibold text-[#DC2626]">{{ $author->initials() }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-[#FAFAFA] truncate">{{ $author->name }}</p>
                                        <p class="text-xs text-[#71717A]">Author</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $this->posts->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-20 lg:py-32">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/5 border border-white/10 mb-6">
                    <svg class="w-10 h-10 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-[#FAFAFA] mb-2">No posts found</h3>
                <p class="text-[#A1A1AA] mb-6 max-w-md mx-auto">
                    We couldn't find any articles matching your criteria. Try adjusting your search or filters.
                </p>
                <button wire:click="clearFilters" class="btn-ghost inline-flex">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>View all posts</span>
                </button>
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <aside class="w-full lg:w-80 flex-shrink-0 space-y-6">
        {{-- Categories Panel --}}
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#FAFAFA]">Categories</h3>
            </div>
            <ul class="space-y-1">
                @foreach($this->categories as $category)
                    <li wire:key="category-{{ $category->id }}">
                        <a
                            href="{{ route('blog', ['category' => $category->slug]) }}"
                            class="flex items-center justify-between px-3 py-2 rounded-lg text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/5 transition-all duration-200 {{ $this->categorySlug === $category->slug ? 'bg-[#DC2626]/10 text-[#DC2626] hover:bg-[#DC2626]/15' : '' }}"
                        >
                            <span>{{ $category->name }}</span>
                            @if($this->categorySlug === $category->slug)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        {{-- Tags Panel --}}
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#FAFAFA]">Tags</h3>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($this->tags as $tag)
                    <a
                        href="{{ route('blog', ['tag' => $tag->slug]) }}"
                        class="px-3 py-1.5 rounded-full text-sm border transition-all duration-200 {{ $this->tagSlug === $tag->slug ? 'bg-[#DC2626]/10 border-[#DC2626]/30 text-[#DC2626]' : 'bg-white/5 border-white/10 text-[#A1A1AA] hover:border-[#DC2626]/30 hover:text-[#DC2626]' }}"
                        wire:key="tag-{{ $tag->id }}"
                    >
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Newsletter CTA --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#DC2626]/20 to-[#991B1B]/10 border border-[#DC2626]/20 p-6">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#DC2626]/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-12 h-12 rounded-xl bg-[#DC2626]/20 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#FAFAFA] mb-2">Stay Updated</h3>
                <p class="text-sm text-[#A1A1AA] mb-4">
                    Get the latest automotive tips and news delivered to your inbox.
                </p>
                <a href="{{ route('contact') }}" class="btn-premium w-full justify-center text-sm py-3">
                    <span>Subscribe</span>
                </a>
            </div>
        </div>
    </aside>
</div>

<style>
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
