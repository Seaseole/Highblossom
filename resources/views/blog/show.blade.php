<x-layouts::site>
    <x-seo.meta />
    <div class="min-h-screen bg-[#0A0A0F] pt-20">
        <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
            <article>
                @if ($post->featured_image_url)
                    <div class="relative mb-8 h-64 overflow-hidden rounded-2xl">
                        <img
                            src="{{ $post->featured_image_url }}"
                            alt="{{ $post->title }}"
                            class="absolute inset-0 h-full w-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-[#0A0A0F]/30 via-[#0A0A0F]/50 to-transparent"></div>
                        <div class="absolute right-0 bottom-0 left-0 p-6">
                            <h1 class="mb-4 text-4xl font-bold text-[#FAFAFA] drop-shadow-lg">{{ $post->title }}</h1>

                            <div class="mb-4 flex items-center gap-4 text-sm text-[#A1A1AA]">
                                <span class="drop-shadow-md">{{ $post->published_at?->format('M d, Y') }}</span>
                                @if ($post->categories->count() > 0)
                                    <span>•</span>
                                    @foreach ($post->categories as $category)
                                        <a
                                            href="{{ route('blog', ['category' => $category->slug]) }}"
                                            class="drop-shadow-md hover:text-[#DC2626]"
                                        >
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>

                            @if ($post->tags->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($post->tags as $tag)
                                        <a
                                            href="{{ route('blog', ['tag' => $tag->slug]) }}"
                                            class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs text-[#A1A1AA] drop-shadow-md transition-colors hover:border-[#DC2626]/30"
                                        >
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="mb-8">
                        <h1 class="mb-4 text-4xl font-bold text-[#FAFAFA]">{{ $post->title }}</h1>

                        <div class="mb-4 flex items-center gap-4 text-sm text-[#71717A]">
                            <span>{{ $post->published_at?->format('M d, Y') }}</span>
                            @if ($post->categories->count() > 0)
                                <span>•</span>
                                @foreach ($post->categories as $category)
                                    <a
                                        href="{{ route('blog', ['category' => $category->slug]) }}"
                                        class="hover:text-[#DC2626]"
                                    >
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>

                        @if ($post->tags->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach ($post->tags as $tag)
                                    <a
                                        href="{{ route('blog', ['tag' => $tag->slug]) }}"
                                        class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-[#A1A1AA] transition-colors hover:border-[#DC2626]/30"
                                    >
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <div class="prose prose-invert max-w-none">
                    @if ($post->content && is_array($post->content))
                        @foreach ($post->content as $block)
                            @block($block['type'], $block['attributes'] ?? [])
                        @endforeach
                    @endif
                </div>
            </article>

            <div class="mt-8 border-t border-white/10 pt-8">
                <x-blog.social-share :post="$post" />
            </div>

            @if ($relatedPosts->count() > 0)
                <div class="mt-16">
                    <h2 class="mb-6 text-2xl font-bold text-[#FAFAFA]">Related Posts</h2>
                    <div class="grid gap-6 md:grid-cols-3">
                        @foreach ($relatedPosts as $relatedPost)
                            <article class="rounded-xl border border-white/10 bg-white/5 p-4 transition-colors hover:border-white/20">
                                @if ($relatedPost->featured_image_url)
                                    <img
                                        src="{{ $relatedPost->featured_image_url }}"
                                        alt="{{ $relatedPost->title }}"
                                        class="mb-3 h-32 w-full rounded-lg object-cover"
                                    />
                                @endif

                                <h3 class="mb-2 text-lg font-semibold text-[#FAFAFA]">
                                    <a
                                        href="{{ route('blog.show', $relatedPost->slug) }}"
                                        class="transition-colors hover:text-[#DC2626]"
                                    >
                                        {{ $relatedPost->title }}
                                    </a>
                                </h3>

                                <p class="text-sm text-[#71717A]">
                                    {{ $relatedPost->published_at?->format('M d, Y') }}
                                </p>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-8">
                <a
                    href="{{ route('blog') }}"
                    class="inline-flex items-center text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                >
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Blog
                </a>
            </div>
        </div>
    </div>
</x-layouts::site>
