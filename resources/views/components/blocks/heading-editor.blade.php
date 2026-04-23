<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.content" 
    label="Heading Text" 
    placeholder="Enter heading..."
/>

<flux:select 
    wire:model.live="blocks.{{ $index }}.attributes.level" 
    label="Heading Level"
>
    <flux:select.option value="1">H1</flux:select.option>
    <flux:select.option value="2">H2</flux:select.option>
    <flux:select.option value="3">H3</flux:select.option>
    <flux:select.option value="4">H4</flux:select.option>
    <flux:select.option value="5">H5</flux:select.option>
    <flux:select.option value="6">H6</flux:select.option>
</flux:select>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.class" 
    label="CSS Classes" 
    placeholder="Additional CSS classes..."
/>
