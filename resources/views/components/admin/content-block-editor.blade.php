@props([
    'block' => null,
    'content' => [],
])

@aware(['contentModel'])

@php
use App\Domains\Content\Services\BlockRegistry;

$blockRegistry = app(BlockRegistry::class);
$blockClass = $blockRegistry->find($block['type'] ?? null);
$schema = $blockClass ? $blockClass::schema() : [];
@endphp

<div class="p-4 rounded-xl bg-white/5 border border-white/10">
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-sm font-medium text-[#FAFAFA]">{{ $block['type'] ?? 'Unknown Block' }}</h4>
        <span class="text-xs text-[#71717A]">ID: {{ $block['id'] ?? 'N/A' }}</span>
    </div>

    @if(empty($schema))
        <p class="text-sm text-[#A1A1AA]">No configuration options for this block type</p>
    @else
        <x-block-configurator 
            :blockType="$block['type']"
            :content="$content"
            :blockId="$block['id']"
        />
    @endif
</div>
