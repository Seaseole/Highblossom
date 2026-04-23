<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.title" 
    label="Title" 
    placeholder="CTA title..."
/>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.description" 
    label="Description" 
    type="textarea"
    rows="2"
    placeholder="CTA description..."
/>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.button_text" 
    label="Button Text" 
    placeholder="Click here..."
/>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.button_url" 
    label="Button URL" 
    placeholder="https://..."
/>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.class" 
    label="CSS Classes" 
    placeholder="Additional CSS classes..."
/>
