<flux:select 
    wire:model.live="blocks.{{ $index }}.attributes.type" 
    label="List Type"
>
    <flux:select.option value="unordered">Unordered (Bullets)</flux:select.option>
    <flux:select.option value="ordered">Ordered (Numbers)</flux:select.option>
</flux:select>

<div class="space-y-2">
    <label class="text-sm text-admin-text-muted">List Items</label>
    @foreach($block['attributes']['items'] as $itemIndex => $item)
        <div class="flex gap-2">
            <flux:input 
                wire:model.live="blocks.{{ $index }}.attributes.items.{{ $itemIndex }}" 
                placeholder="Item {{ $itemIndex + 1 }}"
            />
            <flux:button 
                size="sm" 
                variant="ghost" 
                wire:click="removeListItem('{{ $block['id'] }}', {{ $itemIndex }})"
            >
                <flux:icon icon="x" variant="outline" class="w-4 h-4" />
            </flux:button>
        </div>
    @endforeach
    <flux:button 
        size="sm" 
        variant="ghost" 
        wire:click="addListItem('{{ $block['id'] }}')"
    >
        <flux:icon icon="plus" variant="outline" class="w-4 h-4 mr-1" />
        Add Item
    </flux:button>
</div>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.class" 
    label="CSS Classes" 
    placeholder="Additional CSS classes..."
/>
