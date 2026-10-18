<form class="w-full" wire:submit.prevent>
    <div class="relative">
        {{-- The live field stays in the DOM for readers whose Alpine never boots;
             once it boots, the palette owns searching and this is hidden. --}}
        <div x-data="{}" x-show="false" class="relative">
            <svg class="absolute top-1/2 left-3.5 h-4 w-4 -translate-y-1/2 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>

            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search posts..."
                aria-label="Search posts"
                class="w-full rounded-full border border-white/10 bg-white/5 py-2.5 pr-10 pl-10 text-sm text-[#FAFAFA] transition-all duration-300 placeholder:text-[#71717A] focus:border-transparent focus:ring-2 focus:ring-[#DC2626]/50 focus:outline-none"
            />

            <div wire:loading wire:target="search" class="absolute top-1/2 right-3.5 -translate-y-1/2">
                <svg class="h-4 w-4 animate-spin text-[#DC2626]" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            @if ($search)
                <button
                    type="button"
                    wire:click="clearSearch"
                    wire:loading.attr="disabled"
                    wire:target="search"
                    aria-label="Clear search"
                    class="absolute top-1/2 right-3.5 -translate-y-1/2 text-[#71717A] transition-colors hover:text-[#FAFAFA] disabled:opacity-50"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>

        <button
            type="button"
            x-data="{ mac: /Mac|iPhone|iPad|iPod/i.test(navigator.platform || navigator.userAgent) }"
            x-cloak
            @click="$dispatch('blog-search:open', { trigger: $el })"
            aria-label="Open blog search"
            class="flex w-full items-center gap-2.5 rounded-full border border-white/10 bg-white/5 py-2.5 pr-3 pl-3.5 text-left text-sm transition-all duration-300 hover:border-[#DC2626]/30 focus:border-transparent focus:ring-2 focus:ring-[#DC2626]/50 focus:outline-none"
        >
            <svg class="h-4 w-4 flex-shrink-0 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>

            <span class="min-w-0 flex-1 truncate {{ $search ? 'text-[#FAFAFA]' : 'text-[#71717A]' }}">
                {{ $search ?: 'Search articles, categories and tags' }}
            </span>

            <span class="hidden flex-shrink-0 items-center gap-1 sm:flex" aria-hidden="true">
                <span class="inline-flex min-w-[1.4rem] items-center justify-center rounded-md border border-white/10 bg-white/5 px-1 py-0.5 text-[10px] font-semibold text-[#A1A1AA]" x-text="mac ? '⌘' : 'Ctrl'">Ctrl</span>
                <span class="inline-flex min-w-[1.4rem] items-center justify-center rounded-md border border-white/10 bg-white/5 px-1 py-0.5 text-[10px] font-semibold text-[#A1A1AA]">K</span>
            </span>
        </button>
    </div>
</form>
