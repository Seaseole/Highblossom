<div class="space-y-12">
    {{-- Featured post --}}
    @if ($featured)
        <article class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5">
            <div class="relative h-[26rem] w-full sm:h-[30rem]">
                @if ($featured->featured_image_url)
                    <img
                        src="{{ $featured->featured_image_url }}"
                        alt="{{ $featured->title }}"
                        class="h-full w-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.03]"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-[#0A0A0F]/55 to-transparent"></div>
                @endif

                <div class="absolute inset-x-0 bottom-0 flex flex-wrap items-end justify-between gap-6 p-6 sm:p-10">
                    <div class="max-w-2xl">
                        <h2 class="font-headline text-2xl leading-tight font-bold text-[#FAFAFA] md:text-4xl">
                            <a
                                href="{{ route('blog.show', $featured->slug) }}"
                                wire:navigate
                                class="relative z-10 transition-colors hover:text-[#DC2626]"
                            >
                                {{ $featured->title }}
                            </a>
                        </h2>

                        @if ($featured->excerpt)
                            <p class="mt-3 line-clamp-2 leading-relaxed text-[#A1A1AA]">{{ $featured->excerpt }}</p>
                        @endif

                        <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-[#A1A1AA]">
                            @if ($featured->author)
                                <span class="flex items-center gap-2">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#DC2626]/20 text-xs font-semibold text-[#DC2626]">
                                        {{ $featured->author->initials() }}
                                    </span>
                                    <span class="font-medium text-[#FAFAFA]">{{ $featured->author->name }}</span>
                                </span>
                            @endif

                            <span>{{ $featured->published_at?->format('M j, Y') }}</span>
                            <span aria-hidden="true">·</span>
                            <span>{{ $featured->readTimeMinutes() }} min read</span>
                        </div>
                    </div>

                    @if ($featured->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($featured->tags->take(3) as $tag)
                                <a
                                    href="{{ route('blog', ['tag' => $tag->slug]) }}"
                                    wire:navigate
                                    class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-medium text-[#FAFAFA] backdrop-blur-sm transition-colors hover:border-[#DC2626]/40 hover:text-[#DC2626]"
                                >
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </article>
    @endif

    {{-- Filter tabs + search --}}
    <div>
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-white/10">
            <nav class="-mb-px flex flex-wrap items-center gap-1" aria-label="Blog categories">
                <a
                    href="{{ route('blog') }}"
                    wire:navigate
                    class="border-b-2 px-4 py-3 text-sm font-medium transition-colors {{ ! $categorySlug && ! $tagSlug ? 'border-[#DC2626] text-[#FAFAFA]' : 'border-transparent text-[#A1A1AA] hover:border-white/30 hover:text-[#FAFAFA]' }}"
                >
                    All
                </a>

                @foreach ($categoryTabs as $tab)
                    <a
                        href="{{ route('blog', ['category' => $tab->slug]) }}"
                        wire:navigate
                        class="border-b-2 px-4 py-3 text-sm font-medium transition-colors {{ $categorySlug === $tab->slug ? 'border-[#DC2626] text-[#FAFAFA]' : 'border-transparent text-[#A1A1AA] hover:border-white/30 hover:text-[#FAFAFA]' }}"
                    >
                        {{ $tab->name }}
                        <span class="ml-1.5 text-xs {{ $categorySlug === $tab->slug ? 'text-[#DC2626]' : 'text-[#71717A]' }}">{{ $tab->posts_count }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="w-full pb-3 sm:w-72">
                <livewire:blog-posts.search-form :search="$search" />
            </div>
        </div>

        @if ($tags->isNotEmpty())
            <div class="mt-5 flex flex-wrap items-center gap-2">
                @foreach ($tags as $tag)
                    <a
                        href="{{ route('blog', ['tag' => $tag->slug]) }}"
                        wire:navigate
                        wire:key="tag-{{ $tag->id }}"
                        class="rounded-full border px-3 py-1.5 text-xs transition-all duration-200 {{ $tagSlug === $tag->slug ? 'border-[#DC2626]/40 bg-[#DC2626]/10 text-[#DC2626]' : 'border-white/10 bg-white/5 text-[#A1A1AA] hover:border-[#DC2626]/30 hover:text-[#DC2626]' }}"
                    >
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if ($search || $categorySlug || $tagSlug)
            <div class="mt-5">
                <livewire:blog-posts.active-filters
                    :search="$search"
                    :category-slug="$categorySlug"
                    :tag-slug="$tagSlug"
                />
            </div>
        @endif
    </div>

    {{-- Post grid --}}
    @if ($posts->count() > 0)
        <div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $index => $post)
                    <article
                        class="group relative flex flex-col overflow-hidden rounded-3xl border border-white/10 bg-white/5 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1 hover:border-[#DC2626]/30 hover:shadow-2xl hover:shadow-[#DC2626]/5"
                        style="animation: fadeUp 0.6s ease-out {{ $index * 0.06 }}s both;"
                        wire:key="post-{{ $post->id }}"
                    >
                        <div class="aspect-[16/10] overflow-hidden bg-white/5">
                            @if ($post->featured_image_url)
                                <img
                                    src="{{ $post->featured_image_url }}"
                                    alt="{{ $post->title }}"
                                    class="h-full w-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-105"
                                    loading="lazy"
                                />
                            @else
                                <div class="flex h-full items-center justify-center">
                                    <span class="font-headline text-2xl font-bold text-white/10">{{ $post->author?->initials() ?? '—' }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col p-6">
                            @if ($post->categories->isNotEmpty())
                                <p class="text-xs font-semibold tracking-wider text-[#DC2626] uppercase">
                                    {{ $post->categories->first()->name }}
                                </p>
                            @endif

                            <h3 class="font-headline mt-3 line-clamp-2 text-xl leading-snug font-bold text-[#FAFAFA]">
                                <a
                                    href="{{ route('blog.show', $post->slug) }}"
                                    wire:navigate
                                    class="relative z-10 transition-colors duration-300 group-hover:text-[#DC2626]"
                                >
                                    {{ $post->title }}
                                </a>
                            </h3>

                            @if ($post->excerpt)
                                <p class="mt-3 line-clamp-3 leading-relaxed text-[#A1A1AA]">{{ $post->excerpt }}</p>
                            @endif

                            <div class="mt-6 flex items-center gap-3 border-t border-white/5 pt-4 text-sm text-[#71717A]">
                                @if ($post->author)
                                    <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-[#DC2626]/20 text-xs font-semibold text-[#DC2626]">
                                        {{ $post->author->initials() }}
                                    </span>
                                    <span class="truncate font-medium text-[#FAFAFA]">{{ $post->author->name }}</span>
                                    <span aria-hidden="true">·</span>
                                @endif

                                <span>{{ $post->readTimeMinutes() }} min read</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <p class="mt-8 text-sm text-[#71717A]">
                @php
                    // The hero is promoted out of the paginated set, so add it back to the total.
                    $shownTotal = $posts->total() + ($featured ? 1 : 0);
                @endphp
                Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }} of {{ number_format($shownTotal) }} posts
            </p>

            <div class="mt-6">{{ $posts->links('vendor.pagination.site') }}</div>
        </div>
    @else
        <div class="py-20 text-center lg:py-28">
            <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-2xl border border-white/10 bg-white/5">
                <svg class="h-10 w-10 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h3 class="font-headline mb-2 text-xl font-semibold text-[#FAFAFA]">No posts found</h3>
            <p class="mx-auto max-w-md text-[#A1A1AA]">
                We couldn't find any articles matching your criteria. Try adjusting your search or filters.
            </p>
        </div>
    @endif
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
</style>
