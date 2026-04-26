<div>
    @if($search)
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DC2626]/10 border border-[#DC2626]/20 text-sm text-[#DC2626]">
            Search: {{ $search }}
            <button wire:click="clearSearch" class="hover:text-white transition-colors">×</button>
        </span>
    @endif
    @if($category)
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DC2626]/10 border border-[#DC2626]/20 text-sm text-[#DC2626]">
            Category: {{ $category->name }}
            <button wire:click="clearCategory" class="hover:text-white transition-colors">×</button>
        </span>
    @endif
    @if($tag)
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DC2626]/10 border border-[#DC2626]/20 text-sm text-[#DC2626]">
            Tag: #{{ $tag->name }}
            <button wire:click="clearTag" class="hover:text-white transition-colors">×</button>
        </span>
    @endif
    @if($search || $categorySlug || $tagSlug)
        <button wire:click="clearAll" class="text-sm text-[#A1A1AA] hover:text-[#DC2626] transition-colors ml-2">
            Clear all
        </button>
    @endif
</div>
