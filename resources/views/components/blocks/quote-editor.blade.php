<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.content" 
    label="Quote Text" 
    type="textarea"
    rows="3"
    placeholder="Enter the quote..."
/>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.author" 
    label="Author" 
    placeholder="Author name..."
/>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.cite" 
    label="Citation URL" 
    placeholder="https://..."
/>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.class" 
    label="CSS Classes" 
    placeholder="Additional CSS classes..."
/>
