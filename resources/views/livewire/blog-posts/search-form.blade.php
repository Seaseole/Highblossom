<form class="w-full">
    <div class="relative">
        <svg class="absolute top-1/2 left-4 h-5 w-5 -translate-y-1/2 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search articles..."
            class="w-full rounded-xl border border-white/10 bg-white/5 py-4 pr-14 pl-12 text-[#FAFAFA] placeholder-[#71717A] transition-all duration-300 focus:border-transparent focus:ring-2 focus:ring-[#DC2626]/50 focus:outline-none"
        />

        {{-- Loading Spinner --}}
        <div wire:loading wire:target="search" class="absolute top-1/2 right-10 -translate-y-1/2">
            <svg class="h-5 w-5 animate-spin text-[#DC2626]" fill="none" viewBox="0 0 24 24">
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
                class="absolute top-1/2 right-4 -translate-y-1/2 text-[#71717A] transition-colors hover:text-[#FAFAFA] disabled:opacity-50"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </div>
</form>
