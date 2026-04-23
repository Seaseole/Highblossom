<div>
    <label class="block text-sm font-medium text-admin-text-muted mb-2">Content</label>
    <textarea
        id="paragraph-editor-{{ $index }}"
        name="content"
        wire:model.live="blocks.{{ $index }}.attributes.content"
        rows="6"
        class="w-full bg-admin-surface-alt border border-admin-border rounded-xl px-4 py-3 text-admin-text placeholder-admin-text-muted focus:ring-2 focus:ring-admin-accent focus:border-transparent"
        placeholder="Enter paragraph text..."
    ></textarea>
</div>

<flux:input 
    wire:model.live="blocks.{{ $index }}.attributes.class" 
    label="CSS Classes" 
    placeholder="Additional CSS classes..."
/>
