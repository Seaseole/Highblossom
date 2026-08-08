<div>
    <label class="text-admin-text-muted mb-2 block text-sm font-medium">Content</label>
    <textarea
        id="paragraph-editor-{{ $index }}"
        name="content"
        wire:model.live="blocks.{{ $index }}.attributes.content"
        rows="6"
        class="bg-admin-surface-alt border-admin-border text-admin-text placeholder-admin-text-muted focus:ring-admin-accent w-full rounded-xl border px-4 py-3 focus:border-transparent focus:ring-2"
        placeholder="Enter paragraph text..."
    ></textarea>
</div>

<flux:input
    wire:model.live="blocks.{{ $index }}.attributes.class"
    label="CSS Classes"
    placeholder="Additional CSS classes..."
/>
