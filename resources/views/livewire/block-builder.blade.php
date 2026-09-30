@php
    $iconBtnCls = 'rounded-lg p-1.5 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900 disabled:opacity-30 disabled:hover:bg-transparent dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white';
@endphp

<div
    class="space-y-4"
    wire:ignore.self
    wire:key="block-builder-{{ $name }}"
    x-data="blockBuilder({
    initialBlocks: {{ Js::from($blocks ?? []) }},
    blockMeta: {{ Js::from($blockMeta ?? []) }},
    blockErrors: {{ Js::from($blockErrors ?? []) }},
    name: @js($name)
})"
>
    <!-- Invalid block summary -->
    <template x-if="invalidBlockLabels().length > 0">
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-200">
            <p class="font-medium">These blocks are incomplete and will block the save:</p>
            <p class="mt-1" x-text="invalidBlockLabels().join(', ')"></p>
        </div>
    </template>

    <!-- Undo remove -->
    <template x-if="lastRemoved">
        <div class="flex items-center justify-between gap-3 rounded-2xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm dark:border-white/10 dark:bg-white/5">
            <span class="text-gray-600 dark:text-gray-300"
                >Removed <strong x-text="label(lastRemoved.block.type)"></strong> block.</span
            >
            <div class="flex items-center gap-2">
                <button type="button" @click="undoRemove()" class="font-medium text-gray-900 underline dark:text-white">
                    Undo
                </button>
                <button type="button" @click="lastRemoved = null" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                    Dismiss
                </button>
            </div>
        </div>
    </template>

    <!-- Blocks List -->
    <div class="space-y-3">
        <template x-if="blocks.length === 0">
            <div class="rounded-2xl border-2 border-dashed border-gray-200 py-8 text-center dark:border-white/10">
                <p class="text-gray-500 dark:text-gray-400">No content blocks yet. Add your first block below.</p>
            </div>
        </template>

        <template x-for="(block, index) in blocks" :key="block.id">
            <div
                class="group relative rounded-2xl border bg-white p-4 transition-shadow dark:bg-[#16161D]"
                :class="[
                    isInvalid(block)
                        ? 'border-red-300 dark:border-red-500/40'
                        : 'border-gray-200 dark:border-white/10',
                    dropIndex === index ? 'ring-2 ring-gray-900 dark:ring-white' : '',
                    dragIndex === index ? 'opacity-40' : '',
                ]"
                @dragover.prevent="hoverDrag(index)"
                @drop.prevent="finishDrag()"
            >
                <!-- Block Header -->
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-2">
                        <button
                            type="button"
                            draggable="true"
                            @dragstart="startDrag(index)"
                            @dragend="finishDrag()"
                            class="{{ $iconBtnCls }} cursor-grab active:cursor-grabbing"
                            title="Drag to reorder"
                            aria-label="Drag to reorder"
                        >
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="9" cy="6" r="1.6" /><circle cx="15" cy="6" r="1.6" /><circle cx="9" cy="12" r="1.6" /><circle cx="15" cy="12" r="1.6" /><circle cx="9" cy="18" r="1.6" /><circle cx="15" cy="18" r="1.6" /></svg>
                        </button>
                        <span
                            class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold tracking-wider text-gray-900 uppercase dark:bg-white/5 dark:text-gray-100"
                            x-text="label(block.type)"
                        ></span>
                        <span class="shrink-0 text-xs text-gray-400 dark:text-gray-500" x-text="'#' + (index + 1)"></span>
                        <template x-if="isInvalid(block)">
                            <span
                                class="truncate rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600 dark:bg-red-900/20 dark:text-red-300"
                                x-text="invalidHint(block)"
                            ></span>
                        </template>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 transition-opacity group-hover:opacity-100 focus-within:opacity-100">
                        <button
                            type="button"
                            @click="moveBlock(index, -1)"
                            :disabled="index === 0"
                            class="{{ $iconBtnCls }}"
                            title="Move up"
                            aria-label="Move block up"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                        </button>
                        <button
                            type="button"
                            @click="moveBlock(index, 1)"
                            :disabled="index === blocks.length - 1"
                            class="{{ $iconBtnCls }}"
                            title="Move down"
                            aria-label="Move block down"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <button
                            type="button"
                            @click="duplicateBlock(index)"
                            class="{{ $iconBtnCls }}"
                            title="Duplicate"
                            aria-label="Duplicate block"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                        <button
                            type="button"
                            @click="toggleCollapse(block)"
                            class="{{ $iconBtnCls }}"
                            :title="isCollapsed(block) ? 'Expand' : 'Collapse'"
                            :aria-label="isCollapsed(block) ? 'Expand block' : 'Collapse block'"
                        >
                            <svg
                                class="h-4 w-4 transition-transform"
                                :class="isCollapsed(block) ? '-rotate-90' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            ><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <button
                            type="button"
                            @click="removeBlock(index)"
                            class="rounded-lg p-1.5 text-gray-500 transition-colors hover:bg-red-50 hover:text-red-500 dark:text-gray-400 dark:hover:bg-red-900/20"
                            title="Delete"
                            aria-label="Delete block"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Server-side validation messages for this block -->
                <template x-if="errorsFor(block).length > 0">
                    <ul class="mb-4 space-y-1 rounded-xl bg-red-50 p-3 text-xs text-red-600 dark:bg-red-900/20 dark:text-red-300">
                        <template x-for="message in errorsFor(block)" :key="message">
                            <li x-text="message"></li>
                        </template>
                    </ul>
                </template>

                <!-- Edit Panel -->
                <div x-show="! isCollapsed(block)" x-collapse class="mt-4 border-t border-gray-100 pt-4 dark:border-white/10">
                    <template x-if="! isEditableType(block.type)">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            This block type has no inline editor yet. New
                            <span x-text="label(block.type)"></span>
                            blocks cannot be added, but the saved values of this one are preserved when you update the post.
                        </p>
                    </template>

                    @include('livewire.block-builder.fields', ['b' => 'block', 'nesting' => true])
                </div>
            </div>
        </template>
    </div>

    <!-- Add Block + Preview -->
    <div class="flex items-center gap-3">
        <div class="flex-1">
            @include('livewire.block-builder.add-menu')
        </div>
        <button
            type="button"
            @click="openPreview($wire)"
            class="rounded-2xl border border-gray-200 bg-white px-4 py-3 font-medium text-gray-900 transition-colors hover:bg-gray-100 dark:border-white/10 dark:bg-white/5 dark:text-gray-100 dark:hover:bg-white/10"
        >
            Preview
        </button>
    </div>

    <!-- Draft preview modal -->
    <div
        x-show="previewOpen"
        x-cloak
        style="display: none"
        class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-[#0A0A0F]/90 p-4 backdrop-blur-sm"
        @click.self="closePreview()"
        @keydown.escape.window="closePreview()"
    >
        <div class="relative my-8 w-full max-w-3xl rounded-2xl border border-white/10 bg-white p-6 shadow-2xl dark:bg-[#16161D]">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Draft preview</h3>
                <button type="button" @click="closePreview()" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5" aria-label="Close preview">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div x-show="previewLoading" class="py-12 text-center text-sm text-gray-500">Rendering preview…</div>
            <div x-show="! previewLoading" x-html="previewHtml" class="prose max-w-none dark:prose-invert"></div>
            <p x-show="! previewLoading && previewHtml === ''" class="py-8 text-center text-sm text-gray-500">Nothing to preview yet.</p>
        </div>
    </div>
</div>
