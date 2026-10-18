<x-layouts::site>
    <x-seo.meta />

    <div class="min-h-screen bg-[#0A0A0F] pt-20">
        {{-- Hero --}}
        <header class="relative overflow-hidden border-b border-white/10">
            @if ($post->featured_image_url)
                <img
                    src="{{ $post->featured_image_url }}"
                    alt=""
                    aria-hidden="true"
                    class="absolute inset-0 h-full w-full object-cover opacity-25"
                />
                <div class="absolute inset-0 bg-gradient-to-b from-[#0A0A0F]/50 via-[#0A0A0F]/85 to-[#0A0A0F]"></div>
            @endif

            <div class="relative mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:py-24">
                @if ($post->categories->isNotEmpty())
                    <a
                        href="{{ route('blog', ['category' => $post->categories->first()->slug]) }}"
                        wire:navigate
                        class="text-xs font-semibold tracking-wider text-[#DC2626] uppercase transition-colors hover:text-[#B91C1C]"
                    >
                        {{ $post->categories->first()->name }}
                    </a>
                @endif

                <h1 class="font-headline mt-4 text-3xl leading-[1.15] font-bold tracking-tight text-[#FAFAFA] sm:text-4xl lg:text-5xl">
                    {{ $post->title }}
                </h1>

                @if ($post->excerpt)
                    <p class="mt-5 text-lg leading-relaxed text-[#A1A1AA]">{{ $post->excerpt }}</p>
                @endif

                <div class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-3">
                    @if ($post->author)
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#DC2626]/20 text-sm font-semibold text-[#DC2626]">
                                {{ $post->author->initials() }}
                            </span>
                            <span class="text-sm">
                                <span class="block font-medium text-[#FAFAFA]">{{ $post->author->name }}</span>
                                <span class="block text-[#71717A]">Author</span>
                            </span>
                        </div>
                    @endif

                    <span class="text-sm text-[#A1A1AA]">{{ $post->published_at?->format('M j, Y') }}</span>
                    <span class="h-1 w-1 rounded-full bg-white/20" aria-hidden="true"></span>
                    <span class="text-sm text-[#A1A1AA]">{{ $post->readTimeMinutes() }} min read</span>
                </div>

                @if ($post->tags->isNotEmpty())
                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach ($post->tags as $tag)
                            <a
                                href="{{ route('blog', ['tag' => $tag->slug]) }}"
                                wire:navigate
                                class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-[#A1A1AA] transition-colors hover:border-[#DC2626]/30 hover:text-[#DC2626]"
                            >
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </header>

        <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:py-16">
            <article class="prose max-w-none">
                @if ($post->content && is_array($post->content))
                    @foreach ($post->content as $block)
                        <div class="{{ $loop->last ? '' : 'mb-8' }}">
                            @block($block['type'], $block['attributes'] ?? [])
                        </div>
                    @endforeach
                @endif
            </article>

            <div class="mt-12 border-t border-white/10 pt-8">
                <x-blog.social-share :post="$post" />
            </div>

            @if ($relatedPosts->count() > 0)
                <div class="mt-16">
                    <h2 class="font-headline mb-6 text-2xl font-bold text-[#FAFAFA]">Related posts</h2>

                    <div class="grid gap-6 md:grid-cols-3">
                        @foreach ($relatedPosts as $relatedPost)
                            <article class="group overflow-hidden rounded-3xl border border-white/10 bg-white/5 transition-colors hover:border-[#DC2626]/30">
                                @if ($relatedPost->featured_image_url)
                                    <div class="aspect-[16/10] overflow-hidden">
                                        <img
                                            src="{{ $relatedPost->featured_image_url }}"
                                            alt="{{ $relatedPost->title }}"
                                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                            loading="lazy"
                                        />
                                    </div>
                                @endif

                                <div class="p-5">
                                    <h3 class="font-headline line-clamp-2 text-base leading-snug font-semibold text-[#FAFAFA]">
                                        <a
                                            href="{{ route('blog.show', $relatedPost->slug) }}"
                                            wire:navigate
                                            class="relative z-10 transition-colors group-hover:text-[#DC2626]"
                                        >
                                            {{ $relatedPost->title }}
                                        </a>
                                    </h3>

                                    <p class="mt-2 text-sm text-[#71717A]">
                                        {{ $relatedPost->published_at?->format('M j, Y') }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-12">
                <a
                    href="{{ route('blog') }}"
                    wire:navigate
                    class="inline-flex items-center text-sm font-medium text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                >
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to blog
                </a>
            </div>
        </div>

        <x-blog.image-lightbox />
    </div>

    <x-blog.search-palette />
</x-layouts::site>
