<x-layouts::site>
    <section class="min-h-screen bg-[#0A0A0F] pt-20">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8 py-16 lg:py-24">
            {{-- Header Section --}}
            <div class="max-w-2xl mb-12 lg:mb-16">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight leading-[1.1] mb-4">
                    Our Blog
                </h1>
                <p class="text-lg text-[#A1A1AA] leading-relaxed">
                    <span>Stay updated with the latest insights in the work behind <span class="font-semibold text-[#DC2626]">{{strtoupper(config('app.name'))  }}</span>.</span>
                </p>
            </div>

            {{-- Search & Filter Bar --}}
            <div class="mb-12">
                <form method="GET" action="{{ route('blog') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search articles..."
                            class="w-full bg-white/5 border border-white/10 rounded-xl pl-12 pr-4 py-4 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626]/50 focus:border-transparent transition-all duration-300"
                        >
                    </div>
                    <button type="submit" class="btn-premium justify-center sm:w-auto">
                        <span>Search</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </button>
                </form>

                {{-- Active Filters --}}
                @if($categorySlug || $tagSlug || $search)
                    <div class="flex flex-wrap items-center gap-3 mt-4">
                        <span class="text-sm text-[#71717A]">Active filters:</span>
                        @if($search)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DC2626]/10 border border-[#DC2626]/20 text-sm text-[#DC2626]">
                                Search: {{ $search }}
                                <a href="{{ route('blog', array_filter(['category' => $categorySlug, 'tag' => $tagSlug])) }}" class="hover:text-white transition-colors">×</a>
                            </span>
                        @endif
                        @if($categorySlug)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DC2626]/10 border border-[#DC2626]/20 text-sm text-[#DC2626]">
                                Category: {{ $categories->firstWhere('slug', $categorySlug)?->name }}
                                <a href="{{ route('blog', array_filter(['search' => $search, 'tag' => $tagSlug])) }}" class="hover:text-white transition-colors">×</a>
                            </span>
                        @endif
                        @if($tagSlug)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DC2626]/10 border border-[#DC2626]/20 text-sm text-[#DC2626]">
                                Tag: #{{ $tags->firstWhere('slug', $tagSlug)?->name }}
                                <a href="{{ route('blog', array_filter(['search' => $search, 'category' => $categorySlug])) }}" class="hover:text-white transition-colors">×</a>
                            </span>
                        @endif
                        <a href="{{ route('blog') }}" class="text-sm text-[#A1A1AA] hover:text-[#DC2626] transition-colors ml-2">
                            Clear all
                        </a>
                    </div>
                @endif
            </div>

            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
                {{-- Main Content --}}
                <div class="flex-1 min-w-0">
                    @if($posts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($posts as $index => $post)
                                @php
                                $wordCount = str_word_count(strip_tags($post->content['content'] ?? ''));
                                $readTime = max(1, ceil($wordCount / 200));
                                $author = $post->author ?? auth()->user();
                                @endphp

                                <article
                                    class="group bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:border-[#DC2626]/30 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#DC2626]/5"
                                    style="animation: fadeUp 0.6s ease-out {{ $index * 0.1 }}s both;"
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
                            {{ $posts->links() }}
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
                            <a href="{{ route('blog') }}" class="btn-ghost inline-flex">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                <span>View all posts</span>
                            </a>
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
                            @foreach($categories as $category)
                                <li>
                                    <a
                                        href="{{ route('blog', ['category' => $category->slug]) }}"
                                        class="flex items-center justify-between px-3 py-2 rounded-lg text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/5 transition-all duration-200 {{ $categorySlug === $category->slug ? 'bg-[#DC2626]/10 text-[#DC2626] hover:bg-[#DC2626]/15' : '' }}"
                                    >
                                        <span>{{ $category->name }}</span>
                                        @if($categorySlug === $category->slug)
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
                            @foreach($tags as $tag)
                                <a
                                    href="{{ route('blog', ['tag' => $tag->slug]) }}"
                                    class="px-3 py-1.5 rounded-full text-sm border transition-all duration-200 {{ $tagSlug === $tag->slug ? 'bg-[#DC2626]/10 border-[#DC2626]/30 text-[#DC2626]' : 'bg-white/5 border-white/10 text-[#A1A1AA] hover:border-[#DC2626]/30 hover:text-[#DC2626]' }}"
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
        </div>
    </section>

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
</x-layouts::site>
