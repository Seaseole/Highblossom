<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'wireModel' => null,
    'placeholder' => 'Enter content...',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'wireModel' => null,
    'placeholder' => 'Enter content...',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    x-data="{
        content: <?php if ((object) ($wireModel) instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($wireModel->value()); ?>')<?php echo e($wireModel->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($wireModel); ?>')<?php endif; ?>,
        init() {
            this.$refs.editor.innerHTML = this.content || '';
            this.updatePlaceholder();
        },
        updateContent() {
            this.content = this.$refs.editor.innerHTML;
            this.updatePlaceholder();
        },
        updatePlaceholder() {
            const isEmpty = this.$refs.editor.innerText.trim() === '';
            this.$refs.editor.classList.toggle('empty', isEmpty && ! this.content);
        },
        format(command, value = null) {
            document.execCommand(command, false, value);
            this.$refs.editor.focus();
            this.updateContent();
        },
        insertLink() {
            const url = prompt('Enter URL:', 'https://');
            if (url) {
                this.format('createLink', url);
            }
        },
        clearFormat() {
            this.format('removeFormat');
        },
        handlePaste(e) {
            e.preventDefault();
            const text = e.clipboardData.getData('text/plain');
            document.execCommand('insertText', false, text);
            this.updateContent();
        }
    }"
    class="space-y-2"
>
    
    <div class="flex flex-wrap items-center gap-1 rounded-xl border border-white/10 bg-white/5 p-2">
        
        <div class="flex items-center gap-1 border-r border-white/10 pr-2">
            <button
                type="button"
                @click="format('bold')"
                class="rounded-lg p-1.5 text-[#A1A1AA] transition-colors hover:bg-white/10 hover:text-[#FAFAFA]"
                title="Bold"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6V4zm0 8h9a4 4 0 014 4 4 4 0 01-4 4H6v-8z" />
                </svg>
            </button>
            <button
                type="button"
                @click="format('italic')"
                class="rounded-lg p-1.5 text-[#A1A1AA] transition-colors hover:bg-white/10 hover:text-[#FAFAFA]"
                title="Italic"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 4h-9m4 16h-9" />
                </svg>
            </button>
            <button
                type="button"
                @click="format('underline')"
                class="rounded-lg p-1.5 text-[#A1A1AA] transition-colors hover:bg-white/10 hover:text-[#FAFAFA]"
                title="Underline"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4v7a6 6 0 006 6 6 6 0 006-6V4" />
                </svg>
            </button>
            <button
                type="button"
                @click="format('strikeThrough')"
                class="rounded-lg p-1.5 text-[#A1A1AA] transition-colors hover:bg-white/10 hover:text-[#FAFAFA]"
                title="Strikethrough"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h16M4 12h16M6 20h16" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4v7a6 6 0 006 6 6 6 0 006-6V4" />
                </svg>
            </button>
        </div>

        
        <div class="flex items-center gap-1 border-r border-white/10 px-2">
            <button
                type="button"
                @click="format('formatBlock', 'H2')"
                class="rounded-lg px-2 py-1.5 text-sm font-semibold text-[#A1A1AA] transition-colors hover:bg-white/10 hover:text-[#FAFAFA]"
                title="Heading 2"
            >
                H2
            </button>
            <button
                type="button"
                @click="format('formatBlock', 'H3')"
                class="rounded-lg px-2 py-1.5 text-sm font-semibold text-[#A1A1AA] transition-colors hover:bg-white/10 hover:text-[#FAFAFA]"
                title="Heading 3"
            >
                H3
            </button>
        </div>

        
        <div class="flex items-center gap-1 border-r border-white/10 px-2">
            <button
                type="button"
                @click="format('insertUnorderedList')"
                class="rounded-lg p-1.5 text-[#A1A1AA] transition-colors hover:bg-white/10 hover:text-[#FAFAFA]"
                title="Bullet List"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </button>
            <button
                type="button"
                @click="format('insertOrderedList')"
                class="rounded-lg p-1.5 text-[#A1A1AA] transition-colors hover:bg-white/10 hover:text-[#FAFAFA]"
                title="Numbered List"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h12M7 12h12M7 17h12M3 7h.01M3 12h.01M3 17h.01" />
                </svg>
            </button>
        </div>

        
        <div class="flex items-center gap-1 border-r border-white/10 px-2">
            <button
                type="button"
                @click="format('formatBlock', 'blockquote')"
                class="rounded-lg p-1.5 text-[#A1A1AA] transition-colors hover:bg-white/10 hover:text-[#FAFAFA]"
                title="Quote"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </button>
            <button
                type="button"
                @click="insertLink"
                class="rounded-lg p-1.5 text-[#A1A1AA] transition-colors hover:bg-white/10 hover:text-[#FAFAFA]"
                title="Insert Link"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </button>
        </div>

        
        <button
            type="button"
            @click="clearFormat"
            class="rounded-lg p-1.5 text-[#A1A1AA] transition-colors hover:bg-white/10 hover:text-[#FAFAFA]"
            title="Clear Formatting"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </div>

    
    <div
        x-ref="editor"
        contenteditable="true"
        @input="updateContent"
        @paste="handlePaste"
        class="prose prose-invert min-h-[200px] w-full max-w-none rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-[#FAFAFA] transition-all duration-200 focus:border-transparent focus:ring-2 focus:ring-[#DC2626] focus:outline-none"
        data-placeholder="<?php echo e($placeholder); ?>"
    ></div>
</div>

<style>
    [contenteditable].empty::before {
        content: attr(data-placeholder);
        color: #71717a;
        pointer-events: none;
    }
</style>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\admin\rich-text-editor.blade.php ENDPATH**/ ?>