<div class="flex flex-wrap items-center gap-3">
    @if ($search)
        <span class="inline-flex items-center gap-2 rounded-full border border-site-accent/20 bg-site-accent-soft px-3 py-1 text-sm text-site-link">
            Search: {{ $search }}
            <button wire:click="clearSearch" class="transition-colors hover:text-site-text">×</button>
        </span>
    @endif
    @if ($category)
        <span class="inline-flex items-center gap-2 rounded-full border border-site-accent/20 bg-site-accent-soft px-3 py-1 text-sm text-site-link">
            Category: {{ $category->name }}
            <button wire:click="clearCategory" class="transition-colors hover:text-site-text">×</button>
        </span>
    @endif
    @if ($tag)
        <span class="inline-flex items-center gap-2 rounded-full border border-site-accent/20 bg-site-accent-soft px-3 py-1 text-sm text-site-link">
            Tag: #{{ $tag->name }}
            <button wire:click="clearTag" class="transition-colors hover:text-site-text">×</button>
        </span>
    @endif
    @if ($search || $categorySlug || $tagSlug)
        <button wire:click="clearAll" class="ml-2 text-sm text-site-muted transition-colors hover:text-site-link">
            Clear all
        </button>
    @endif
</div>
