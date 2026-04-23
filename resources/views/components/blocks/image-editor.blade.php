<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.src" 
    label="Image URL" 
    placeholder="https://..."
/>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.alt" 
    label="Alt Text" 
    placeholder="Image description for accessibility..."
/>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.caption" 
    label="Caption" 
    placeholder="Image caption..."
/>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.class" 
    label="CSS Classes" 
    placeholder="Additional CSS classes..."
/>
