@php
    // $b: JS expression referring to the block object in the surrounding Alpine scope.
    // $nesting: render depth-one sub-builders for columns/tabs/carousel editors.
    $b ??= 'block';
    $nesting ??= true;

    $labelCls = 'mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300';
    $inputCls = 'w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white';
    $selectCls = 'w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-gray-100 dark:focus:ring-[var(--color-admin-accent)]';
    $btnCls = 'rounded-xl bg-gray-100 px-3 py-2 text-sm font-medium transition-colors hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10';
    $entryCls = 'space-y-2 rounded-xl border border-gray-200 p-3 dark:border-white/10';
    $addEntryCls = 'text-sm font-medium underline text-gray-700 dark:text-gray-300';
    $removeIconCls = 'p-2 text-red-500 hover:bg-red-50 rounded-lg disabled:opacity-30 dark:hover:bg-red-900/20';
    $noNestingNote = 'This block nests other blocks, and nested editing stops here. Its inner blocks can only be edited at the top level.';
@endphp

<template x-if="{{ $b }}.type === 'paragraph'">
    <div
        x-data="{
            editorId: 'paragraph-editor-' + {{ $b }}.id,
            initEditor() {
                this.$nextTick(() => {
                    if (CKEDITOR.instances[this.editorId]) CKEDITOR.instances[this.editorId].destroy(true);
                    const instance = CKEDITOR.replace(this.editorId, { height: 200 });
                    if ({{ $b }}.attributes.content) {
                        instance.setData({{ $b }}.attributes.content);
                    }
                    const push = () => {
                        {{ $b }}.attributes.content = instance.getData();
                    };
                    instance.on('change', push);
                    instance.on('blur', push);
                });
            },
        }"
        x-init="initEditor()"
    >
        <label class="{{ $labelCls }}">Content</label>
        <textarea :id="editorId" x-model="{{ $b }}.attributes.content" class="{{ $inputCls }}"></textarea>
    </div>
</template>

<template x-if="{{ $b }}.type === 'heading'">
    <div class="space-y-4">
        <div>
            <label class="{{ $labelCls }}">Heading Text</label>
            <input type="text" x-model="{{ $b }}.attributes.content" class="{{ $inputCls }}" />
        </div>
        <div>
            <label class="{{ $labelCls }}">Level</label>
            <select x-model="{{ $b }}.attributes.level" class="{{ $selectCls }}">
                @for ($level = 1; $level <= 6; $level++)
                    <option value="h{{ $level }}">H{{ $level }}</option>
                @endfor
            </select>
        </div>
    </div>
</template>

<template x-if="{{ $b }}.type === 'image'">
    <div class="space-y-4">
        <div>
            <label class="{{ $labelCls }}">Image</label>
            <div class="flex items-center gap-2">
                <input type="text" x-model="{{ $b }}.attributes.src" class="{{ $inputCls }}" placeholder="Image URL" />
                <button type="button" @click="openPicker({{ $b }}, 'src')" class="{{ $btnCls }}">Library</button>
                <label class="{{ $btnCls }} cursor-pointer">
                    Upload
                    <input
                        type="file"
                        class="hidden"
                        accept="image/*"
                        @change="$wire.setActiveImageUpload({{ $b }}.id, 'src').then(() => $wire.upload('imageUpload', $event.target.files[0]))"
                    />
                </label>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="{{ $labelCls }}">Alt Text</label>
                <input type="text" x-model="{{ $b }}.attributes.alt" class="{{ $inputCls }}" />
            </div>
            <div>
                <label class="{{ $labelCls }}">Caption</label>
                <input type="text" x-model="{{ $b }}.attributes.caption" class="{{ $inputCls }}" />
            </div>
        </div>
    </div>
</template>

<template x-if="{{ $b }}.type === 'quote'">
    <div class="space-y-4">
        <div>
            <label class="{{ $labelCls }}">Quote</label>
            <textarea x-model="{{ $b }}.attributes.content" class="{{ $inputCls }}" rows="3"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="{{ $labelCls }}">Author</label>
                <input type="text" x-model="{{ $b }}.attributes.author" class="{{ $inputCls }}" />
            </div>
            <div>
                <label class="{{ $labelCls }}">Citation</label>
                <input type="text" x-model="{{ $b }}.attributes.cite" class="{{ $inputCls }}" />
            </div>
        </div>
    </div>
</template>

<template x-if="{{ $b }}.type === 'cta'">
    <div class="space-y-4">
        <div>
            <label class="{{ $labelCls }}">Title</label>
            <input type="text" x-model="{{ $b }}.attributes.title" class="{{ $inputCls }}" />
        </div>
        <div>
            <label class="{{ $labelCls }}">Description</label>
            <textarea x-model="{{ $b }}.attributes.description" class="{{ $inputCls }}" rows="2"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="{{ $labelCls }}">Button Text</label>
                <input type="text" x-model="{{ $b }}.attributes.button_text" class="{{ $inputCls }}" />
            </div>
            <div>
                <label class="{{ $labelCls }}">Button URL</label>
                <input type="text" x-model="{{ $b }}.attributes.button_url" class="{{ $inputCls }}" />
            </div>
        </div>
    </div>
</template>

<template x-if="{{ $b }}.type === 'video'">
    <div
        class="space-y-4"
        x-data="{
            videoMode: {{ $b }}.attributes.src
                ? {{ $b }}.attributes.src.startsWith('temp://') || {{ $b }}.attributes.src.startsWith('http')
                    ? 'url'
                    : 'upload'
                : 'url',
            previewData: null,
            async detectVideo() {
                if (! {{ $b }}.attributes.src) {
                    this.previewData = null;

                    return;
                }
                this.previewData = await $wire.detectVideoUrl({{ $b }}.attributes.src);
            },
        }"
        x-init="detectVideo()"
    >
        <div class="mb-2 flex gap-4">
            <label class="flex cursor-pointer items-center gap-2">
                <input type="radio" x-model="videoMode" value="url" class="rounded-full" /><span class="text-sm">External URL</span>
            </label>
            <label class="flex cursor-pointer items-center gap-2">
                <input type="radio" x-model="videoMode" value="upload" class="rounded-full" /><span class="text-sm">Upload File</span>
            </label>
        </div>
        <div x-show="videoMode === 'url'">
            <input
                type="url"
                x-model="{{ $b }}.attributes.src"
                @input.debounce="detectVideo()"
                class="{{ $inputCls }}"
                placeholder="https://youtube.com/..."
            />
        </div>
        <div x-show="videoMode === 'upload'">
            <input
                type="file"
                @change="$wire.startVideoUpload({{ $b }}.id).then(() => $wire.upload('videoUpload', $event.target.files[0]))"
                accept="video/mp4,video/webm"
                class="w-full text-sm"
            />
        </div>

        <div x-show="previewData && previewData.valid" class="mt-4">
            <template x-if="previewData && previewData.uses_iframe">
                <div class="aspect-video w-full">
                    <iframe :src="previewData.embed_url" class="h-full w-full rounded-lg" frameborder="0" allowfullscreen></iframe>
                </div>
            </template>
            <template x-if="previewData && ! previewData.uses_iframe">
                <video :src="{{ $b }}.attributes.src" controls class="w-full rounded-lg"></video>
            </template>
            <p class="mt-2 text-xs text-green-500" x-text="'Source detected: ' + (previewData ? previewData.source_label : '')"></p>
        </div>
        <div
            x-show="previewData && ! previewData.valid"
            class="mt-2 rounded-lg bg-red-50 p-2 text-xs text-red-500 dark:bg-red-900/20"
            x-text="previewData ? previewData.error : ''"
        ></div>
    </div>
</template>

<template x-if="{{ $b }}.type === 'code'">
    <div class="space-y-4">
        <div>
            <label class="{{ $labelCls }}">Language</label>
            <input type="text" x-model="{{ $b }}.attributes.language" class="{{ $inputCls }}" placeholder="php, javascript, bash…" />
        </div>
        <div>
            <label class="{{ $labelCls }}">Code</label>
            <textarea
                x-model="{{ $b }}.attributes.content"
                rows="8"
                spellcheck="false"
                class="{{ $inputCls }} font-mono"
            ></textarea>
        </div>
    </div>
</template>

<template x-if="{{ $b }}.type === 'list'">
    <div class="space-y-4">
        <div class="flex gap-4">
            <label class="flex items-center gap-2">
                <input type="radio" x-model="{{ $b }}.attributes.type" value="unordered" /><span class="text-sm">Unordered</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" x-model="{{ $b }}.attributes.type" value="ordered" /><span class="text-sm">Ordered</span>
            </label>
        </div>
        <textarea
            x-model="{{ $b }}.attributes.itemsRaw"
            @input="{{ $b }}.attributes.items = $event.target.value.split('\n')"
            class="{{ $inputCls }}"
            rows="4"
            placeholder="Items (one per line)"
        ></textarea>
    </div>
</template>

<template x-if="{{ $b }}.type === 'alert'">
    <div class="space-y-4">
        <select x-model="{{ $b }}.attributes.type" class="{{ $selectCls }}">
            <option value="info">Info</option>
            <option value="warning">Warning</option>
            <option value="danger">Danger</option>
            <option value="success">Success</option>
        </select>
        <input type="text" x-model="{{ $b }}.attributes.title" class="{{ $inputCls }}" placeholder="Title" />
        <textarea x-model="{{ $b }}.attributes.content" class="{{ $inputCls }}" placeholder="Message"></textarea>
        <x-ui.checkbox x-model="{{ $b }}.attributes.dismissible" label="Dismissible" />
    </div>
</template>

<template x-if="{{ $b }}.type === 'html'">
    <div>
        <textarea x-model="{{ $b }}.attributes.content" class="{{ $inputCls }} font-mono" rows="6"></textarea>
    </div>
</template>

<template x-if="{{ $b }}.type === 'embed'">
    <div class="space-y-4">
        <input type="url" x-model="{{ $b }}.attributes.url" class="{{ $inputCls }}" placeholder="URL" />
        <input type="text" x-model="{{ $b }}.attributes.title" class="{{ $inputCls }}" placeholder="Title" />
    </div>
</template>

<template x-if="{{ $b }}.type === 'divider'">
    <div class="space-y-4">
        <select x-model="{{ $b }}.attributes.style" class="{{ $selectCls }}">
            <option value="line">Line</option>
            <option value="dots">Dots</option>
            <option value="space">Space</option>
        </select>
        <select x-model="{{ $b }}.attributes.size" class="{{ $selectCls }}">
            <option value="sm">Small</option>
            <option value="md">Medium</option>
            <option value="lg">Large</option>
        </select>
    </div>
</template>

<template x-if="{{ $b }}.type === 'countdown'">
    <div class="space-y-4">
        <input type="datetime-local" x-model="{{ $b }}.attributes.target_date" class="{{ $inputCls }}" />
        <input type="text" x-model="{{ $b }}.attributes.label" class="{{ $inputCls }}" placeholder="Label" />
    </div>
</template>

<template x-if="{{ $b }}.type === 'poll'">
    <div class="space-y-4">
        <input type="text" x-model="{{ $b }}.attributes.question" class="{{ $inputCls }}" placeholder="Question" />
        <div class="space-y-2">
            <template x-if="Array.isArray({{ $b }}.attributes.options)">
                <template x-for="(opt, optIdx) in {{ $b }}.attributes.options" :key="optIdx">
                    <div class="flex items-center gap-2">
                        <input type="text" x-model="{{ $b }}.attributes.options[optIdx]" class="{{ $inputCls }}" />
                        <button
                            type="button"
                            @click="{{ $b }}.attributes.options.splice(optIdx, 1)"
                            :disabled="{{ $b }}.attributes.options.length <= 2"
                            class="{{ $removeIconCls }}"
                            title="Polls need at least two options"
                        >
                            X
                        </button>
                    </div>
                </template>
            </template>
            <button
                type="button"
                @click="
                    if (! Array.isArray({{ $b }}.attributes.options)) {{ $b }}.attributes.options = [];
                    {{ $b }}.attributes.options.push('New Option');
                "
                class="{{ $addEntryCls }}"
            >
                Add Option
            </button>
        </div>
        <div class="flex flex-col gap-2">
            <x-ui.checkbox x-model="{{ $b }}.attributes.allow_multiple" label="Allow multiple" />
            <x-ui.checkbox x-model="{{ $b }}.attributes.show_results" label="Show results" />
        </div>
    </div>
</template>

<template x-if="{{ $b }}.type === 'gallery'">
    <div class="space-y-4">
        <div>
            <label class="{{ $labelCls }}">Columns</label>
            <select x-model.number="{{ $b }}.attributes.columns" class="{{ $selectCls }}">
                @for ($columns = 1; $columns <= 6; $columns++)
                    <option value="{{ $columns }}">{{ $columns }}</option>
                @endfor
            </select>
        </div>
        <div class="space-y-3">
            <template x-for="(img, gi) in {{ $b }}.attributes.images" :key="gi">
                <div class="{{ $entryCls }}">
                    <div class="flex items-center gap-2">
                        <input type="text" x-model="{{ $b }}.attributes.images[gi].src" class="{{ $inputCls }}" placeholder="Image URL" />
                        <button type="button" @click="openPicker({{ $b }}, 'images.' + gi + '.src')" class="{{ $btnCls }}">Library</button>
                        <label class="{{ $btnCls }} cursor-pointer">
                            Upload
                            <input
                                type="file"
                                class="hidden"
                                accept="image/*"
                                @change="$wire.setActiveImageUpload({{ $b }}.id, 'images.' + gi + '.src').then(() => $wire.upload('imageUpload', $event.target.files[0]))"
                            />
                        </label>
                        <button type="button" @click="removeGalleryImage({{ $b }}, gi)" class="{{ $removeIconCls }}" title="Remove image" aria-label="Remove image">
                            X
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" x-model="{{ $b }}.attributes.images[gi].alt" class="{{ $inputCls }}" placeholder="Alt text" />
                        <input type="text" x-model="{{ $b }}.attributes.images[gi].caption" class="{{ $inputCls }}" placeholder="Caption" />
                    </div>
                </div>
            </template>
            <button type="button" @click="addGalleryImage({{ $b }})" class="{{ $addEntryCls }}">Add Image</button>
        </div>
    </div>
</template>

<template x-if="{{ $b }}.type === 'table'">
    <div class="space-y-4">
        <div>
            <label class="{{ $labelCls }}">Caption</label>
            <input type="text" x-model="{{ $b }}.attributes.caption" class="{{ $inputCls }}" />
        </div>
        <div class="space-y-2">
            <label class="{{ $labelCls }}">Columns</label>
            <template x-for="(header, ci) in {{ $b }}.attributes.headers" :key="ci">
                <div class="flex items-center gap-2">
                    <input type="text" x-model="{{ $b }}.attributes.headers[ci]" class="{{ $inputCls }}" placeholder="Column name" />
                    <button
                        type="button"
                        @click="removeTableColumn({{ $b }}, ci)"
                        :disabled="{{ $b }}.attributes.headers.length <= 1"
                        class="{{ $removeIconCls }}"
                        title="Remove column"
                        aria-label="Remove column"
                    >
                        X
                    </button>
                </div>
            </template>
            <div class="flex gap-4">
                <button type="button" @click="addTableColumn({{ $b }})" class="{{ $addEntryCls }}">Add Column</button>
            </div>
        </div>
        <div class="space-y-2">
            <label class="{{ $labelCls }}">Rows</label>
            <template x-for="(row, ri) in {{ $b }}.attributes.rows" :key="ri">
                <div class="{{ $entryCls }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="grid flex-1 gap-2" :style="'grid-template-columns: repeat(' + {{ $b }}.attributes.headers.length + ', minmax(0, 1fr))'">
                            <template x-for="(cell, ci) in row" :key="ci">
                                <input type="text" x-model="{{ $b }}.attributes.rows[ri][ci]" class="{{ $inputCls }}" placeholder="Cell" />
                            </template>
                        </div>
                        <button type="button" @click="removeTableRow({{ $b }}, ri)" class="{{ $removeIconCls }}" title="Remove row" aria-label="Remove row">
                            X
                        </button>
                    </div>
                </div>
            </template>
            <button type="button" @click="addTableRow({{ $b }})" class="{{ $addEntryCls }}">Add Row</button>
        </div>
    </div>
</template>

<template x-if="{{ $b }}.type === 'accordion'">
    <div class="space-y-4">
        <x-ui.checkbox x-model="{{ $b }}.attributes.multiple_open" label="Allow multiple open items" />
        <div class="space-y-3">
            <template x-for="(item, ai) in {{ $b }}.attributes.items" :key="ai">
                <div class="{{ $entryCls }}">
                    <div class="flex items-center gap-2">
                        <input type="text" x-model="{{ $b }}.attributes.items[ai].title" class="{{ $inputCls }}" placeholder="Question / title" />
                        <button type="button" @click="removeAccordionItem({{ $b }}, ai)" class="{{ $removeIconCls }}" title="Remove item" aria-label="Remove item">
                            X
                        </button>
                    </div>
                    <textarea x-model="{{ $b }}.attributes.items[ai].content" class="{{ $inputCls }}" rows="3" placeholder="Answer / content"></textarea>
                </div>
            </template>
            <button type="button" @click="addAccordionItem({{ $b }})" class="{{ $addEntryCls }}">Add Item</button>
        </div>
    </div>
</template>

<template x-if="{{ $b }}.type === 'form'">
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="{{ $labelCls }}">Submit Button Text</label>
                <input type="text" x-model="{{ $b }}.attributes.submit_text" class="{{ $inputCls }}" />
            </div>
            <div>
                <label class="{{ $labelCls }}">Action URL</label>
                <input type="url" x-model="{{ $b }}.attributes.action_url" class="{{ $inputCls }}" placeholder="https://…" />
            </div>
        </div>
        <div class="space-y-3">
            <label class="{{ $labelCls }}">Fields</label>
            <template x-for="(field, fi) in {{ $b }}.attributes.fields" :key="fi">
                <div class="{{ $entryCls }}">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" x-model="{{ $b }}.attributes.fields[fi].name" class="{{ $inputCls }}" placeholder="Field name" />
                        <input type="text" x-model="{{ $b }}.attributes.fields[fi].label" class="{{ $inputCls }}" placeholder="Label" />
                    </div>
                    <div class="flex items-center gap-2">
                        <select x-model="{{ $b }}.attributes.fields[fi].type" class="{{ $selectCls }}">
                            <option value="text">Text</option>
                            <option value="email">Email</option>
                            <option value="textarea">Textarea</option>
                            <option value="select">Select</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="radio">Radio</option>
                        </select>
                        <x-ui.checkbox x-model="{{ $b }}.attributes.fields[fi].required" label="Required" />
                        <button type="button" @click="removeFormField({{ $b }}, fi)" class="{{ $removeIconCls }}" title="Remove field" aria-label="Remove field">
                            X
                        </button>
                    </div>
                    <input
                        type="text"
                        x-show="[ 'select', 'radio' ].includes({{ $b }}.attributes.fields[fi].type)"
                        x-model="{{ $b }}.attributes.fields[fi].optionsRaw"
                        @blur="syncFieldOptions({{ $b }}, fi)"
                        class="{{ $inputCls }}"
                        placeholder="Options, comma separated"
                    />
                </div>
            </template>
            <button type="button" @click="addFormField({{ $b }})" class="{{ $addEntryCls }}">Add Field</button>
        </div>
    </div>
</template>

<template x-if="{{ $b }}.type === 'columns'">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <p class="text-xs text-gray-400">Widths use a 12-column grid.</p>
            <button type="button" @click="addLayoutColumn({{ $b }})" class="{{ $addEntryCls }}">Add Column</button>
        </div>
        <template x-for="(col, ci) in {{ $b }}.attributes.columns" :key="ci">
            <div class="{{ $entryCls }}">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="'Column ' + (ci + 1)"></span>
                    <select x-model.number="{{ $b }}.attributes.column_widths[ci]" class="{{ $selectCls }} max-w-32">
                        @for ($width = 1; $width <= 12; $width++)
                            <option value="{{ $width }}">{{ $width }}/12</option>
                        @endfor
                    </select>
                    <button
                        type="button"
                        @click="removeLayoutColumn({{ $b }}, ci)"
                        :disabled="{{ $b }}.attributes.columns.length <= 1"
                        class="{{ $removeIconCls }}"
                        title="Remove column"
                        aria-label="Remove column"
                    >
                        X
                    </button>
                </div>
                @if ($nesting)
                    @include('livewire.block-builder.nested-list', ['list' => $b.'.attributes.columns[ci]'])
                @else
                    <p class="text-xs italic text-gray-400">{{ $noNestingNote }}</p>
                @endif
            </div>
        </template>
    </div>
</template>

<template x-if="{{ $b }}.type === 'tabs'">
    <div class="space-y-4">
        <div class="flex justify-end">
            <button type="button" @click="addTab({{ $b }})" class="{{ $addEntryCls }}">Add Tab</button>
        </div>
        <template x-for="(tab, ti) in {{ $b }}.attributes.tabs" :key="ti">
            <div class="{{ $entryCls }}">
                <div class="flex items-center gap-2">
                    <input type="text" x-model="{{ $b }}.attributes.tabs[ti].label" class="{{ $inputCls }}" placeholder="Tab label" />
                    <button
                        type="button"
                        @click="removeTab({{ $b }}, ti)"
                        :disabled="{{ $b }}.attributes.tabs.length <= 1"
                        class="{{ $removeIconCls }}"
                        title="Remove tab"
                        aria-label="Remove tab"
                    >
                        X
                    </button>
                </div>
                @if ($nesting)
                    @include('livewire.block-builder.nested-list', ['list' => $b.'.attributes.tabs[ti].content'])
                @else
                    <p class="text-xs italic text-gray-400">{{ $noNestingNote }}</p>
                @endif
            </div>
        </template>
    </div>
</template>

<template x-if="{{ $b }}.type === 'carousel'">
    <div class="space-y-4">
        <div class="grid grid-cols-2 items-end gap-4">
            <div>
                <label class="{{ $labelCls }}">Interval (seconds)</label>
                <input type="number" min="1" max="60" x-model.number="{{ $b }}.attributes.interval" class="{{ $inputCls }}" />
            </div>
            <x-ui.checkbox x-model="{{ $b }}.attributes.autoplay" label="Autoplay" />
        </div>
        <div class="flex justify-end">
            <button type="button" @click="addSlide({{ $b }})" class="{{ $addEntryCls }}">Add Slide</button>
        </div>
        <template x-for="(slide, si) in {{ $b }}.attributes.slides" :key="si">
            <div class="{{ $entryCls }}">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="'Slide ' + (si + 1)"></span>
                    <button type="button" @click="removeSlide({{ $b }}, si)" class="{{ $removeIconCls }}" title="Remove slide" aria-label="Remove slide">
                        X
                    </button>
                </div>
                @if ($nesting)
                    @include('livewire.block-builder.nested-list', ['list' => $b.'.attributes.slides[si]'])
                @else
                    <p class="text-xs italic text-gray-400">{{ $noNestingNote }}</p>
                @endif
            </div>
        </template>
    </div>
</template>
