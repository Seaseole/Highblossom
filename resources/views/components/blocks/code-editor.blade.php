<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.content" 
    label="Code" 
    type="textarea"
    rows="6"
    placeholder="Enter code..."
/>

<flux:select 
    wire:model.live="blocks.{{ $index }}.attributes.language" 
    label="Language"
>
    <flux:select.option value="javascript">JavaScript</flux:select.option>
    <flux:select.option value="php">PHP</flux:select.option>
    <flux:select.option value="python">Python</flux:select.option>
    <flux:select.option value="html">HTML</flux:select.option>
    <flux:select.option value="css">CSS</flux:select.option>
    <flux:select.option value="bash">Bash</flux:select.option>
    <flux:select.option value="sql">SQL</flux:select.option>
</flux:select>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.class" 
    label="CSS Classes" 
    placeholder="Additional CSS classes..."
/>
