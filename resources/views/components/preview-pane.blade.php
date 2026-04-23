@props([
    'contentModel' => null,
    'blocks' => [],
])

@php
use Highblossom\ContentBlocks\Services\BlockRegistry;
$blockRegistry = app(BlockRegistry::class);
@endphp

<div x-data="{
    device: 'desktop',
    devices: [
        { id: 'desktop', name: 'Desktop', icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
        { id: 'tablet', name: 'Tablet', icon: 'M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z' },
        { id: 'mobile', name: 'Mobile', icon: 'M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z' },
    ]
}" class="h-full flex flex-col bg-[#0A0A0F]">
    <!-- Device Selector -->
    <div class="p-4 border-b border-white/5 flex items-center justify-between">
        <div class="flex gap-2">
            @foreach($devices as $d)
                <button 
                    @click="device = '{{ $d['id'] }}'"
                    :class="device === '{{ $d['id'] }}' ? 'bg-[#DC2626] border-[#DC2626]' : 'bg-white/5 border-white/10'"
                    class="p-2 rounded-lg border transition-all duration-200"
                    :title="'{{ $d['name'] }}'"
                >
                    <svg class="w-5 h-5 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $d['icon'] }}" />
                    </svg>
                </button>
            @endforeach
        </div>
        <button 
            wire:click="$parent.togglePreview"
            class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] hover:bg-white/10 transition-all duration-200"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span>Close Preview</span>
        </button>
    </div>

    <!-- Preview Container -->
    <div class="flex-1 flex items-center justify-center p-8 overflow-auto">
        <div 
            :class="{
                'w-full': device === 'desktop',
                'w-[768px]': device === 'tablet',
                'w-[375px]': device === 'mobile'
            }"
            class="bg-white min-h-full rounded-xl shadow-2xl overflow-hidden transition-all duration-300"
        >
            <!-- Preview Content -->
            <div class="p-8">
                @if($contentModel)
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $contentModel->title }}</h1>
                @endif

                @foreach($blocks as $block)
                    @if($block['is_visible'])
                        <div class="mb-6">
                            <div class="p-4 bg-gray-100 rounded-lg">
                                <p class="text-sm text-gray-600">Block type: {{ $block['type'] }}</p>
                                    <pre class="text-xs text-gray-500 mt-2">{{ json_encode($block['content'], JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach

                @if(empty($blocks))
                    <div class="text-center py-12">
                        <p class="text-gray-500">No content blocks added yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
