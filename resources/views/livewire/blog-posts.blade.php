<div class="flex flex-col gap-8 lg:flex-row lg:gap-12">
    {{-- Main Content --}}
    <div class="min-w-0 flex-1">
        @if ($this->posts->count() > 0)
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->posts as $index => $post)
                    @php
                        $wordCount = str_word_count(strip_tags($post->content['content'] ?? ''));
                        $readTime = max(1, ceil($wordCount / 200));
                        $author = $post->author ?? auth()->user();
                    @endphp

                    <article
                        class="group overflow-hidden rounded-2xl border border-white/10 bg-white/5 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1 hover:border-[#DC2626]/30 hover:shadow-2xl hover:shadow-[#DC2626]/5"
                        style="animation: fadeUp 0.6s ease-out {{ $index * 0.1 }}s both;"
                        wire:key="post-{{ $post->id }}"
                    >
                        {{-- Featured Image --}}
                        @if ($post->featured_image_url)
                            <div class="aspect-[16/10] overflow-hidden">
                                <a href="{{ route('blog.show', $post->slug) }}" class="relative block h-full">
                                    <img
                                        src="{{ $post->featured_image_url }}"
                                        alt="{{ $post->title }}"
                                        class="h-full w-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-105"
                                        loading="lazy"
                                    />
                                </a>
                            </div>
                        @endif

                        {{-- Content --}}
                        <div class="p-6">
                            {{-- Date and Category --}}
                            <div class="mb-4 flex items-center gap-3">
                                <span class="text-sm text-[#71717A]">{{ $post->published_at?->format('M d, Y') }}</span>
                                @if ($post->categories->count() > 0)
                                    <span class="h-1 w-1 rounded-full bg-white/20"></span>
                                    @foreach ($post->categories->take(1) as $category)
                                        <a
                                            href="{{ route('blog', ['category' => $category->slug]) }}"
                                            class="text-sm font-medium text-[#DC2626] transition-colors hover:text-[#B91C1C]"
                                        >
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>

                            {{-- Title --}}
                            <h2 class="font-headline mb-3 line-clamp-2 text-xl font-bold text-[#FAFAFA] transition-colors duration-300 group-hover:text-[#DC2626]">
                                <a href="{{ route('blog.show', $post->slug) }}" class="block"> {{ $post->title }} </a>
                            </h2>

                            {{-- Description --}}
                            @if ($post->excerpt)
                                <p class="mb-4 line-clamp-3 leading-relaxed text-[#A1A1AA]">{{ $post->excerpt }}</p>
                            @endif

                            {{-- Read More Link --}}
                            <a
                                href="{{ route('blog.show', $post->slug) }}"
                                class="mb-6 inline-flex items-center text-sm font-medium text-[#DC2626] transition-colors hover:text-[#B91C1C]"
                            >
                                Read More
                                <svg class="ml-1 h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>

                            {{-- Author Section --}}
                            @if ($author)
                                <div class="flex items-center gap-3 border-t border-white/5 pt-4">
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#DC2626]/20">
                                        <span class="text-sm font-semibold text-[#DC2626]">{{ $author->initials() }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-[#FAFAFA]">{{ $author->name }}</p>
                                        <p class="text-xs text-[#71717A]">Author</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12">{{ $this->posts->links() }}</div>
        @else
            {{-- Empty State --}}
            <div class="py-20 text-center lg:py-32">
                <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-2xl border border-white/10 bg-white/5">
                    <svg class="h-10 w-10 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-semibold text-[#FAFAFA]">No posts found</h3>
                <p class="mx-auto mb-6 max-w-md text-[#A1A1AA]">
                    We couldn't find any articles matching your criteria. Try adjusting your search or filters.
                </p>
                {{--                <button wire:click="clearFilters" class="btn-ghost inline-flex">--}}
                {{--                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
                {{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>--}}
                {{--                    </svg>--}}
                {{--                    <span>View all posts</span>--}}
                {{--                </button>--}}
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <aside class="w-full flex-shrink-0 space-y-6 lg:w-80">
        {{-- Categories Panel --}}
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/10">
                    <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#FAFAFA]">Categories</h3>
            </div>
            <ul class="space-y-1">
                @foreach ($this->categories as $category)
                    <li wire:key="category-{{ $category->id }}">
                        <a
                            href="{{ route('blog', ['category' => $category->slug]) }}"
                            class="flex items-center justify-between px-3 py-2 rounded-lg text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/5 transition-all duration-200 {{ $this->categorySlug === $category->slug ? 'bg-[#DC2626]/10 text-[#DC2626] hover:bg-[#DC2626]/15' : '' }}"
                        >
                            <span>{{ $category->name }}</span>
                            @if ($this->categorySlug === $category->slug)
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        {{-- Tags Panel --}}
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/10">
                    <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#FAFAFA]">Tags</h3>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach ($this->tags as $tag)
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
        <div class="relative overflow-hidden rounded-2xl border border-[#DC2626]/20 bg-gradient-to-br from-[#DC2626]/20 to-[#991B1B]/10 p-6">
            <div class="absolute top-0 right-0 h-32 w-32 translate-x-1/2 -translate-y-1/2 rounded-full bg-[#DC2626]/10 blur-3xl"></div>
            <div class="relative">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#DC2626]/20">
                    <svg class="h-6 w-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-[#FAFAFA]">Stay Updated</h3>
                <p class="mb-4 text-sm text-[#A1A1AA]">
                    Get the latest automotive tips and news delivered to your inbox.
                </p>
                <a href="{{ route('contact') }}" class="btn-premium w-full justify-center py-3 text-sm">
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
