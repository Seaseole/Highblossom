@props([
    'blockType' => null,
    'content' => [],
    'blockId' => null,
])

@php
use Highblossom\ContentBlocks\Services\BlockRegistry;

$blockRegistry = app(BlockRegistry::class);
$blockClass = $blockRegistry->get($blockType);

// ContentBlocks package doesn't have schema() method
// Using a simple fallback for now
$schema = [];
$debugInfo = "Block type: {$blockType}";
@endphp

<div class="space-y-6">
    @if(empty($schema))
        <div class="text-center py-8">
            <p class="text-sm text-[#A1A1AA]">No configuration options for this block type</p>
            <p class="text-xs text-[#71717A] mt-2">{{ $debugInfo ?? 'Unknown error' }}</p>
        </div>
    @else
        @foreach($schema as $field)
            <div class="space-y-2">
                @if($field['required'] ?? false)
                    <label class="block text-sm font-medium text-[#FAFAFA]">
                        {{ $field['label'] }} <span class="text-[#DC2626]">*</span>
                    </label>
                @else
                    <label class="block text-sm font-medium text-[#A1A1AA]">
                        {{ $field['label'] }}
                    </label>
                @endif

                @if(isset($field['help']))
                    <p class="text-xs text-[#71717A]">{{ $field['help'] }}</p>
                @endif

                @switch($field['type'])
                    @case('text')
                        <input 
                            type="text" 
                            wire:model.live="content.{{ $field['name'] }}"
                            @if($field['required'] ?? false) required @endif
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                        >
                        @break

                    @case('textarea')
                        <textarea 
                            wire:model.live="content.{{ $field['name'] }}"
                            @if($field['required'] ?? false) required @endif
                            rows="4"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200 resize-none"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                        ></textarea>
                        @break

                    @case('select')
                        <select 
                            wire:model.live="content.{{ $field['name'] }}"
                            @if($field['required'] ?? false) required @endif
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200"
                        >
                            @if(!isset($field['default']))
                                <option value="">Select an option</option>
                            @endif
                            @foreach($field['options'] ?? [] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                        @break

                    @case('image')
                        <div class="space-y-3">
                            @if(isset($content[$field['name']]) && $content[$field['name']])
                                <div class="relative">
                                    <img src="{{ $content[$field['name']] }}" alt="Preview" class="w-full h-48 object-cover rounded-xl border border-white/10">
                                    <button 
                                        wire:click="$set('content.{{ $field['name'] }}', null)"
                                        class="absolute top-2 right-2 p-2 rounded-lg bg-[#0A0A0F]/80 backdrop-blur-sm hover:bg-[#DC2626] transition-colors"
                                    >
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                            <input 
                                type="text" 
                                wire:model.live="content.{{ $field['name'] }}"
                                @if($field['required'] ?? false) required @endif
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200"
                                placeholder="Image URL"
                            >
                        </div>
                        @break

                    @case('rich-text')
                        <x-admin.rich-text-editor
                            wireModel="content.{{ $field['name'] }}"
                            :placeholder="$field['placeholder'] ?? 'Enter content...'"
                        />
                        @break

                    @case('repeater')
                        <x-admin.repeater-field
                            wireModel="content.{{ $field['name'] }}"
                            :fields="$field['fields'] ?? []"
                            :label="$field['label'] ?? 'Items'"
                        />
                        @break

                    @case('checkbox')
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                x-model="formData.{{ $field['name'] }}"
                                wire:model.live="formData.{{ $field['name'] }}"
                                class="w-5 h-5 rounded bg-white/5 border-white/20 text-[#DC2626] focus:ring-2 focus:ring-[#DC2626] focus:ring-offset-0 transition-all duration-200"
                            >
                            <span class="text-sm text-[#A1A1AA]">{{ $field['label'] }}</span>
                        </label>
                        @break

                    @default
                        <input 
                            type="text" 
                            wire:model.live="content.{{ $field['name'] }}"
                            @if($field['required'] ?? false) required @endif
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                        >
                @endswitch
            </div>
        @endforeach

        <div class="pt-4 border-t border-white/5">
            <button 
                wire:click="updateBlock({{ $blockId }}, $wire.content)"
                class="w-full px-4 py-3 rounded-xl bg-[#DC2626] border border-[#DC2626] text-white hover:bg-[#B91C1C] transition-all duration-200 shadow-lg shadow-[#DC2626]/20 active:scale-[0.98]"
            >
                Save Changes
            </button>
        </div>
    @endif
</div>
