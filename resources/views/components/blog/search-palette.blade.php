{{--
    Blog command palette, rendered by <x-blog.search-palette /> on /blog and
    /blog/{slug}. Opened with Ctrl/Cmd+K, by "/" when focus is not in a field, or
    by any x-data="blogSearchTrigger" button.
--}}

<div
    x-data="blogSearchPalette"
    data-endpoint="{{ route('api.blog.search') }}"
    data-listing="{{ route('blog') }}"
    x-cloak
>
    <p class="sr-only" role="status" aria-live="polite" x-text="statusText()"></p>

    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="blog-search-backdrop fixed inset-0 z-[9990] bg-[#0A0A0F]/80 backdrop-blur-md"
        @click="close()"
        aria-hidden="true"
    ></div>

    {{-- Dialog --}}
    <div
        x-show="open"
        @click.self="close()"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9991] overflow-y-auto p-4 sm:p-6"
    >
        <div class="flex min-h-full items-start justify-center pt-[10vh] sm:pt-[14vh]" @click.self="close()">
            <div
                x-ref="dialog"
                id="blog-search-dialog"
                x-show="open"
                x-transition:enter="transition ease-out-expo duration-300"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                x-on:keydown.tab="trapFocus($event)"
                role="dialog"
                aria-modal="true"
                aria-labelledby="blog-search-title"
                class="blog-search-panel w-full max-w-2xl overflow-hidden rounded-[2rem] border border-white/10 bg-[#16161D] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.6)]"
            >
                <h2 id="blog-search-title" class="sr-only">Search the blog</h2>

                {{-- Query field --}}
                <div class="flex items-center gap-3 border-b border-white/10 px-5 py-4">
                    <svg class="h-5 w-5 flex-shrink-0 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>

                    <input
                        x-ref="input"
                        type="text"
                        x-model="query"
                        @input="onInput()"
                        @keydown="onKey($event)"
                        role="combobox"
                        aria-controls="blog-search-listbox"
                        aria-autocomplete="list"
                        :aria-expanded="open ? 'true' : 'false'"
                        :aria-activedescendant="activeId()"
                        autocomplete="off"
                        spellcheck="false"
                        enterkeyhint="search"
                        maxlength="80"
                        placeholder="Search articles, categories and tags"
                        class="w-full bg-transparent text-base font-medium text-[#FAFAFA] placeholder:text-[#71717A] focus:outline-none"
                    />

                    <span x-show="loading" class="flex-shrink-0" aria-hidden="true">
                        <svg class="h-5 w-5 animate-spin text-[#DC2626]" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>

                    <button
                        type="button"
                        x-show="query"
                        x-cloak
                        @click="query = ''; onInput(); $refs.input.focus()"
                        aria-label="Clear the search field"
                        class="flex-shrink-0 rounded-full p-1 text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Results --}}
                <div
                    x-ref="list"
                    id="blog-search-listbox"
                    role="listbox"
                    aria-label="Blog search results"
                    class="max-h-[52vh] overflow-y-auto px-2 py-2"
                >
                    <template x-for="group in groups()" :key="group.type + '|' + mode">
                        <div role="group" :aria-label="group.label">
                            <p class="px-3 pt-2 pb-1 text-[11px] font-semibold tracking-[0.14em] text-[#71717A] uppercase" x-text="group.label"></p>

                            <template x-for="opt in group.items" :key="opt.id + '|' + clean(query)">
                                <div
                                    :id="'blog-search-option-' + opt.index"
                                    role="option"
                                    :aria-selected="active === opt.index"
                                    @click="choose(opt)"
                                    @mousemove="active = opt.index"
                                    :class="active === opt.index ? 'bg-[#DC2626]/10' : 'hover:bg-white/5'"
                                    class="flex w-full cursor-pointer items-start gap-3 rounded-2xl px-3 py-2.5 transition-colors duration-200"
                                >
                                    <template x-if="opt.type === 'post'">
                                        <div class="flex w-full items-start gap-3">
                                            <template x-if="opt.thumbnail">
                                                <img :src="opt.thumbnail" :alt="opt.label" loading="lazy" class="h-12 w-16 flex-shrink-0 rounded-xl object-cover" />
                                            </template>

                                            <div class="min-w-0 flex-1">
                                                <p
                                                    class="font-headline truncate text-sm font-bold text-[#FAFAFA]"
                                                    x-init="mark($el, opt.label, query)"
                                                ></p>
                                                <p class="mt-1 line-clamp-1 text-xs leading-relaxed text-[#A1A1AA]" x-text="opt.snippet"></p>
                                                <p class="mt-1.5 flex items-center gap-2 text-[11px] text-[#71717A]">
                                                    <span x-show="opt.category" x-text="opt.category"></span>
                                                    <span x-show="opt.category" aria-hidden="true">&middot;</span>
                                                    <span x-text="opt.date"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="opt.type !== 'post'">
                                        <div class="flex w-full items-center gap-3">
                                            <span
                                                class="text-sm font-semibold text-[#FAFAFA]"
                                                :class="opt.type === 'tag' ? 'text-[#DC2626]' : ''"
                                                x-init="mark($el, opt.label, query)"
                                            ></span>

                                            <span class="ml-auto text-xs text-[#71717A]" x-text="opt.meta"></span>
                                        </div>
                                    </template>

                                    {{-- Mouse-only affordance; keyboard users press Delete. --}}
                                    <button
                                        type="button"
                                        x-show="opt.type === 'saved' && query === ''"
                                        x-cloak
                                        tabindex="-1"
                                        aria-hidden="true"
                                        @click.stop="removeHistory(opt.term)"
                                        class="flex-shrink-0 self-center rounded-full p-1 text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Loading --}}
                    <div x-show="loading && !options().length" class="px-3 py-8 text-center text-sm text-[#A1A1AA]">
                        Searching the blog&hellip;
                    </div>

                    {{-- Failure, including a tripped rate limit --}}
                    <div x-show="failed" class="px-3 py-8 text-center">
                        <p class="text-sm text-[#A1A1AA]">Search is unavailable right now. Please try again in a moment.</p>
                        <a href="{{ route('blog') }}" class="mt-2 inline-block text-sm font-semibold text-[#DC2626] hover:underline">Browse all posts</a>
                    </div>

                    {{-- Nothing matched --}}
                    <div x-show="!loading && !failed && !options().length && !isResting()" class="px-3 py-10 text-center">
                        <p class="font-headline text-base font-semibold text-[#FAFAFA]">
                            No results for &ldquo;<span x-text="clean(query)"></span>&rdquo;
                        </p>
                        <a href="{{ route('blog') }}" class="mt-3 inline-block text-sm font-semibold text-[#DC2626] hover:underline">Browse all posts</a>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-white/10 px-5 py-3 text-[11px] text-[#71717A]">
                    <span class="flex items-center gap-1.5">
                        <kbd class="blog-search-kbd">&uarr;</kbd>
                        <kbd class="blog-search-kbd">&darr;</kbd>
                        navigate
                    </span>
                    <span class="flex items-center gap-1.5">
                        <kbd class="blog-search-kbd">Enter</kbd>
                        open
                    </span>
                    <span class="flex items-center gap-1.5">
                        <kbd class="blog-search-kbd">Esc</kbd>
                        close
                    </span>
                    <span x-show="active >= 0 && options()[active] && options()[active].type === 'saved' && query === ''" x-cloak class="flex items-center gap-1.5">
                        <kbd class="blog-search-kbd">Del</kbd>
                        remove
                    </span>

                    <button
                        type="button"
                        x-show="history.length && isResting()"
                        x-cloak
                        @click="clearHistory()"
                        class="ml-auto font-semibold text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                    >
                        Clear history
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .blog-search-kbd {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.5rem;
            padding: 0.15rem 0.4rem;
            border-radius: 0.45rem;
            border: 1px solid rgb(255 255 255 / 0.12);
            background: rgb(255 255 255 / 0.06);
            font-size: 10px;
            font-weight: 600;
            line-height: 1.4;
            color: #a1a1aa;
        }

        #blog-search-dialog mark.blog-search-hit {
            background: rgb(220 38 38 / 0.22);
            color: #fafafa;
            border-radius: 0.2rem;
            padding: 0 0.1rem;
        }

        /* x-transition writes inline styles, so the override needs !important. */
        @media (prefers-reduced-motion: reduce) {
            .blog-search-backdrop,
            .blog-search-panel {
                transition-duration: 1ms !important;
                animation-duration: 1ms !important;
            }
        }
    </style>
</div>
