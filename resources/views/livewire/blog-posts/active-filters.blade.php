<div>
    @if ($search)
        <span class="inline-flex items-center gap-2 rounded-full border border-[#DC2626]/20 bg-[#DC2626]/10 px-3 py-1 text-sm text-[#DC2626]">
            Search: {{ $search }}
            <button wire:click="clearSearch" class="transition-colors hover:text-white">×</button>
        </span>
    @endif
    @if ($category)
        <span class="inline-flex items-center gap-2 rounded-full border border-[#DC2626]/20 bg-[#DC2626]/10 px-3 py-1 text-sm text-[#DC2626]">
            Category: {{ $category->name }}
            <button wire:click="clearCategory" class="transition-colors hover:text-white">×</button>
        </span>
    @endif
    @if ($tag)
        <span class="inline-flex items-center gap-2 rounded-full border border-[#DC2626]/20 bg-[#DC2626]/10 px-3 py-1 text-sm text-[#DC2626]">
            Tag: #{{ $tag->name }}
            <button wire:click="clearTag" class="transition-colors hover:text-white">×</button>
        </span>
    @endif
    @if ($search || $categorySlug || $tagSlug)
        <button wire:click="clearAll" class="ml-2 text-sm text-[#A1A1AA] transition-colors hover:text-[#DC2626]">
            Clear all
        </button>
    @endif
</div>
