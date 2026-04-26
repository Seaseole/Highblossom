<div x-data="{
    blocks: @js($blocks) || [],
    availableBlockTypes: @js($availableBlockTypes) || {},
    draggingIndex: null,
    editingIndex: null,
    editAttributes: {},
    syncHiddenInputs() {
        this.$nextTick(() => {
            document.querySelectorAll('[data-block-index]').forEach(el => {
                const blockIndex = el.getAttribute('data-block-index');
                if (blockIndex !== null && this.blocks[blockIndex]) {
                    el.dataset.blockId = this.blocks[blockIndex].id || '';
                }
            });
        });
    },
    updateBlock(index, attributes) {
        $wire.updateBlock(index, attributes);
    }
}" x-init="
    $wire.$on('blocks-updated', (event) => {
        const updatedBlocks = event.blocks || (Array.isArray(event) ? event : []);
        console.log('blocks-updated received:', updatedBlocks);
        if (Array.isArray(updatedBlocks)) {
            blocks = updatedBlocks;
            this.$nextTick(() => this.syncHiddenInputs());
        }
    });
    this.$watch('blocks', () => this.syncHiddenInputs());
" class="space-y-4">
    <!-- Blocks List -->
    <div class="space-y-3">
        @if(empty($blocks))
            <div class="text-center py-8 border-2 border-dashed border-admin-border rounded-xl">
                <p class="text-admin-text-muted">No content blocks yet. Add your first block below.</p>
            </div>
        @else
            <template x-for="(block, index) in blocks" :key="block.id || index">
                <div
                    class="bg-admin-surface border border-admin-border rounded-xl p-4 group relative"
                    :class="{ 'ring-2 ring-admin-accent': editingIndex === index }"
                    :data-block-index="index"
                    :data-block-id="block.id"
                    draggable="true"
                    @dragstart="draggingIndex = index"
                    @dragover.prevent
                    @drop="if (draggingIndex !== null && draggingIndex !== index) { $wire.moveBlock(draggingIndex, index); draggingIndex = null; }"
                >
                    <!-- Block Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium px-2 py-1 rounded bg-admin-accent/20 text-admin-accent uppercase tracking-wide">
                                <span x-text="(availableBlockTypes && availableBlockTypes[block.type]) || block.type"></span>
                            </span>
                            <span class="text-xs text-admin-text-muted" x-text="'#' + (index + 1)"></span>
                        </div>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button 
                                type="button"
                                @click="editingIndex = editingIndex === index ? null : index; editAttributes = {...block.attributes}"
                                class="p-1.5 rounded-lg hover:bg-admin-surface-alt text-admin-text-muted hover:text-admin-text transition-colors"
                                title="Edit"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button 
                                type="button"
                                @click="$wire.removeBlock(index)"
                                class="p-1.5 rounded-lg hover:bg-red-500/20 text-admin-text-muted hover:text-red-500 transition-colors"
                                title="Delete"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Block Preview -->
                    <div class="text-admin-text-muted text-sm">
                        <template x-if="block.type === 'paragraph'">
                            <div x-text="block.attributes.content || 'Empty paragraph...'"></div>
                        </template>
                        <template x-if="block.type === 'heading'">
                            <div class="font-semibold" x-text="block.attributes.content || 'Empty heading...'"></div>
                        </template>
                        <template x-if="block.type === 'image'">
                            <div x-text="block.attributes.src ? 'Image: ' + block.attributes.src : 'No image selected'"></div>
                        </template>
                        <template x-if="block.type === 'quote'">
                            <div class="italic" x-text="block.attributes.content || 'Empty quote...'"></div>
                        </template>
                        <template x-if="block.type === 'code'">
                            <div class="font-mono text-xs" x-text="block.attributes.content ? 'Code block' : 'Empty code block...'"></div>
                        </template>
                        <template x-if="block.type === 'list'">
                            <div x-text="block.attributes.items?.length ? 'List with ' + block.attributes.items.length + ' items' : 'Empty list...'"></div>
                        </template>
                        <template x-if="block.type === 'cta'">
                            <div x-text="block.attributes.title ? 'CTA: ' + block.attributes.title : 'Empty CTA...'"></div>
                        </template>
                        <template x-if="block.type === 'video'">
                            <div>
                                <template x-if="!block.attributes.src">
                                    <span class="text-admin-text-muted italic">No video selected</span>
                                </template>
                                <template x-if="block.attributes.src">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted"
                                              x-text="(function(src) {
                                                if (src.includes('youtube.com') || src.includes('youtu.be')) return 'YouTube';
                                                if (src.includes('vimeo.com')) return 'Vimeo';
                                                if (src.includes('dailymotion.com') || src.includes('dai.ly')) return 'Dailymotion';
                                                if (src.includes('facebook.com')) return 'Facebook';
                                                if (src.startsWith('/') || src.startsWith('storage/') || src.startsWith('videos/') || src.startsWith('uploads/') || src.includes('/storage/')) return 'Local';
                                                return 'External';
                                              })(block.attributes.src)">
                                        </span>
                                        <span x-text="block.attributes.src.length > 40 ? block.attributes.src.substring(0, 40) + '...' : block.attributes.src"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="block.type === 'divider'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted" x-text="block.attributes.style"></span>
                                <span x-text="block.attributes.style === 'space' ? 'Space (' + block.attributes.size + ')' : 'Divider'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'alert'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted" x-text="block.attributes.type"></span>
                                <span x-text="block.attributes.title || block.attributes.content ? (block.attributes.title + ': ' + block.attributes.content.substring(0, 30) + '...') : 'Empty alert...'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'html'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted">HTML</span>
                                <span x-text="block.attributes.content ? block.attributes.content.substring(0, 40) + '...' : 'Empty HTML...'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'embed'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted">Embed</span>
                                <span x-text="block.attributes.url ? block.attributes.url.substring(0, 40) + '...' : 'No URL...'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'accordion'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted">Accordion</span>
                                <span x-text="block.attributes.items?.length ? block.attributes.items.length + ' items' : 'Empty accordion...'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'table'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted">Table</span>
                                <span x-text="block.attributes.rows?.length ? block.attributes.rows.length + ' rows, ' + block.attributes.headers?.length + ' cols' : 'Empty table...'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'gallery'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted">Gallery</span>
                                <span x-text="block.attributes.images?.length ? block.attributes.images.length + ' images (' + block.attributes.columns + ' cols)' : 'Empty gallery...'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'form'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted">Form</span>
                                <span x-text="block.attributes.fields?.length ? block.attributes.fields.length + ' fields' : 'Empty form...'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'columns'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted">Columns</span>
                                <span x-text="block.attributes.columns?.length ? block.attributes.columns.length + ' columns' : 'Empty columns...'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'tabs'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted">Tabs</span>
                                <span x-text="block.attributes.tabs?.length ? block.attributes.tabs.length + ' tabs' : 'Empty tabs...'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'carousel'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted">Carousel</span>
                                <span x-text="block.attributes.slides?.length ? block.attributes.slides.length + ' slides' : 'Empty carousel...'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'countdown'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted">Countdown</span>
                                <span x-text="block.attributes.target_date ? new Date(block.attributes.target_date).toLocaleDateString() : 'No date set'"></span>
                            </div>
                        </template>
                        <template x-if="block.type === 'poll'">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded bg-admin-surface-alt border border-admin-border text-admin-text-muted">Poll</span>
                                <span x-text="block.attributes.question ? block.attributes.question : 'No question set'"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Edit Panel -->
                    <div x-show="editingIndex === index" x-transition class="mt-4 pt-4 border-t border-admin-border-subtle">
                        <template x-if="editingIndex === index">
                            <div x-init="editAttributes.itemsRaw = (editAttributes.items || []).join('\n')"></div>
                        </template>
                        <template x-if="block.type === 'paragraph'">
                            <div class="space-y-3" x-data="{
                                editorId: 'ckeditor-' + block.id,
                                editorInstance: null,
                                initEditor() {
                                    this.$nextTick(() => {
                                        // Destroy any existing instance first (Livewire morph safety)
                                        if (CKEDITOR.instances[this.editorId]) {
                                            CKEDITOR.instances[this.editorId].destroy(true);
                                        }

                                        this.editorInstance = CKEDITOR.replace(this.editorId, {
                                            customConfig: '/vendor/ckeditor/config.js',
                                            height: 200,
                                        });

                                        // Push changes to Livewire on every change event
                                        this.editorInstance.on('change', () => {
                                            editAttributes.content = this.editorInstance.getData();
                                            $wire.updateBlock(index, editAttributes);
                                        });

                                        // Also catch paste and key events CKEditor sometimes misses
                                        this.editorInstance.on('key', () => {
                                            editAttributes.content = this.editorInstance.getData();
                                            $wire.updateBlock(index, editAttributes);
                                        });
                                    });
                                },
                                destroyEditor() {
                                    if (this.editorInstance) {
                                        this.editorInstance.destroy(true);
                                        this.editorInstance = null;
                                    }
                                }
                            }"
                            x-init="initEditor()"
                            x-effect="if (editingIndex === index) { initEditor() } else { destroyEditor() }"
                            >
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Content</label>
                                    {{-- CKEditor replaces this textarea; id must be unique per block --}}
                                    <textarea
                                        :id="editorId"
                                        x-text="editAttributes.content"
                                        class="w-full"
                                    ></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">CSS Class (optional)</label>
                                    <input 
                                        type="text" 
                                        x-model="editAttributes.class"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="e.g., text-lg font-bold"
                                    >
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'heading'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Content</label>
                                    <input 
                                        type="text" 
                                        x-model="editAttributes.content"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Level</label>
                                    <select 
                                        x-model="editAttributes.level"
                                        @change="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                        <option value="h1">H1</option>
                                        <option value="h2">H2</option>
                                        <option value="h3">H3</option>
                                        <option value="h4">H4</option>
                                        <option value="h5">H5</option>
                                        <option value="h6">H6</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">CSS Class (optional)</label>
                                    <input 
                                        type="text" 
                                        x-model="editAttributes.class"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'image'">
                            <div class="space-y-3" x-data="{
                                previewUrl: null,
                                imageMode: editAttributes.src && (editAttributes.src.startsWith('temp://') || editAttributes.src.startsWith('/') || editAttributes.src.includes('storage/') || editAttributes.src.includes('uploads/')) ? 'upload' : (editAttributes.src ? 'url' : 'upload'),
                                imageError: null
                            }" x-init="
                                previewUrl = editAttributes.src || null;
                                $watch('block.attributes.src', (value) => {
                                    if (value && !value.startsWith('temp://')) {
                                        editAttributes.src = value;
                                        previewUrl = null;
                                    }
                                });
                                $wire.$on('upload:errored', ({ name }) => {
                                    if (name === 'imageUpload') {
                                        imageError = 'Upload failed. Check file size and type.';
                                        previewUrl = null;
                                    }
                                });
                            "
                            " x-bind="{ get availableBlockTypes() { return $parent.availableBlockTypes; } }">
                                <!-- Image Preview -->
                                <div x-show="editAttributes.src || previewUrl || $wire.imageUpload" class="relative rounded-lg overflow-hidden bg-admin-surface-alt border border-admin-border">
                                    <img
                                        :src="editAttributes.src || previewUrl || ''"
                                        x-show="editAttributes.src || previewUrl"
                                        class="w-full h-48 object-cover"
                                    >
                                    <button 
                                        type="button"
                                        @click="$wire.removeUploadedImage(index)"
                                        class="absolute top-2 right-2 p-2 bg-red-500/80 hover:bg-red-500 rounded-lg text-white transition-colors"
                                        title="Remove Image"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Upload Progress -->
                                <div x-show="$wire.uploadProgress > 0 && $wire.uploadProgress < 100" class="w-full">
                                    <div class="flex items-center justify-between text-sm text-admin-text-muted mb-1">
                                        <span>Uploading...</span>
                                        <span x-text="$wire.uploadProgress + '%'"></span>
                                    </div>
                                    <div class="w-full bg-admin-surface rounded-full h-2">
                                        <div
                                            class="bg-admin-accent h-2 rounded-full transition-all duration-300"
                                            :style="'width: ' + $wire.uploadProgress + '%'"
                                        ></div>
                                    </div>
                                </div>

                                <!-- Source Mode Toggle -->
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Image Source</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="imageMode"
                                                value="upload"
                                                @change="if (imageMode === 'upload') { editAttributes.src = ''; previewUrl = null; imageError = null; $wire.updateBlock(index, editAttributes); }"
                                                class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                            >
                                            <span class="text-sm text-admin-text">Upload Image</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="imageMode"
                                                value="url"
                                                @change="if (imageMode === 'url') { editAttributes.src = ''; previewUrl = null; imageError = null; $wire.updateBlock(index, editAttributes); }"
                                                class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                            >
                                            <span class="text-sm text-admin-text">External URL</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Upload Mode -->
                                <template x-if="imageMode === 'upload'">
                                    <div class="space-y-2">
                                        <label class="block">
                                            <input
                                                type="file"
                                                wire:model="imageUpload"
                                                @change="
                                                    const file = $event.target.files[0];
                                                    if (!file) return;

                                                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
                                                    if (!validTypes.includes(file.type)) {
                                                        imageError = 'Invalid file type. Please select an image (JPG, PNG, WebP, GIF).';
                                                        $event.target.value = '';
                                                        return;
                                                    }

                                                    imageError = null;
                                                    previewUrl = URL.createObjectURL(file);

                                                    $wire.set('activeImageUploadIndex', index)
                                                        .then(() => $wire.upload('imageUpload', file))
                                                        .catch(err => {
                                                            imageError = 'Upload failed: ' + (err.message || 'Unknown error');
                                                            previewUrl = null;
                                                        });
                                                "
                                                accept="image/*"
                                                class="hidden"
                                            >
                                            <div class="mt-2 space-y-1">
                                                <template x-if="imageError">
                                                    <p class="text-red-400 text-xs" x-text="imageError"></p>
                                                </template>
                                                @error('imageUpload')
                                                    <p class="text-red-400 text-xs">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <span class="inline-flex items-center justify-center w-full px-4 py-2 bg-admin-surface-alt border border-admin-border rounded-lg text-admin-text hover:bg-admin-surface transition-colors cursor-pointer">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                                Upload Image
                                            </span>
                                        </label>
                                        <!-- Validation Error -->
                                        <div x-show="imageError" x-transition class="text-red-400 text-sm">
                                            <span x-text="imageError"></span>
                                        </div>
                                    </div>
                                </template>

                                <!-- URL Mode -->
                                <template x-if="imageMode === 'url'">
                                    <div>
                                        <label class="block text-sm font-medium text-admin-text-muted mb-2">Image URL</label>
                                        <input
                                            type="url"
                                            x-model="editAttributes.src"
                                            @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                            class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                            placeholder="https://..."
                                        >
                                    </div>
                                </template>

                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Alt Text</label>
                                    <input
                                        type="text"
                                        x-model="editAttributes.alt"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Caption (optional)</label>
                                    <input
                                        type="text"
                                        x-model="editAttributes.caption"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'quote'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Quote Content</label>
                                    <textarea 
                                        x-model="editAttributes.content"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        rows="3"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    ></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Author (optional)</label>
                                    <input 
                                        type="text" 
                                        x-model="editAttributes.author"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Cite URL (optional)</label>
                                    <input 
                                        type="url" 
                                        x-model="editAttributes.cite"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="https://..."
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">CSS Class (optional)</label>
                                    <input 
                                        type="text" 
                                        x-model="editAttributes.class"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'code'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Code</label>
                                    <textarea 
                                        x-model="editAttributes.content"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        rows="6"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm font-mono focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    ></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">CSS Class (optional)</label>
                                    <input 
                                        type="text" 
                                        x-model="editAttributes.class"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'list'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">List Type</label>
                                    <select 
                                        x-model="editAttributes.type"
                                        @change="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                        <option value="ul">Unordered (bullets)</option>
                                        <option value="ordered">Ordered (numbered)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Items (one per line)</label>
                                    <textarea 
                                        x-model="editAttributes.itemsRaw"
                                        @input.debounce.300ms="editAttributes.items = $event.target.value.split('\\n').filter(i => i.trim()); $wire.updateBlock(index, editAttributes)"
                                        rows="4"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="Item 1&#10;Item 2&#10;Item 3"
                                    ></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">CSS Class (optional)</label>
                                    <input 
                                        type="text" 
                                        x-model="editAttributes.class"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'cta'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Title</label>
                                    <input 
                                        type="text" 
                                        x-model="editAttributes.title"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Description (optional)</label>
                                    <textarea 
                                        x-model="editAttributes.description"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        rows="2"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    ></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Button Text</label>
                                    <input 
                                        type="text" 
                                        x-model="editAttributes.button_text"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Button URL</label>
                                    <input 
                                        type="url" 
                                        x-model="editAttributes.button_url"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="https://..."
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">CSS Class (optional)</label>
                                    <input 
                                        type="text" 
                                        x-model="editAttributes.class"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'video'">
                            <div class="space-y-4" x-data="{
                                videoMode: editAttributes.src && (editAttributes.src.startsWith('/') || editAttributes.src.includes('storage/') || editAttributes.src.includes('videos/')) ? 'upload' : 'url',
                                videoPreviewState: 'empty',
                                videoSourceType: null,
                                get availableBlockTypes() { return $parent.availableBlockTypes; },
                                videoSourceLabel: '',
                                videoEmbedUrl: null,
                                videoFullUrl: null,
                                videoUsesIframe: false,
                                videoError: null,
                                videoDetectTimeout: null,
                                async detectVideoUrl() {
                                    const url = editAttributes.src?.trim();
                                    if (!url) {
                                        this.videoPreviewState = 'empty';
                                        return;
                                    }
                                    this.videoPreviewState = 'loading';
                                    try {
                                        const result = await $wire.detectVideoUrl(url);
                                        if (result.valid) {
                                            this.videoPreviewState = 'valid';
                                            this.videoSourceType = result.source_type;
                                            this.videoSourceLabel = result.source_label;
                                            this.videoEmbedUrl = result.embed_url;
                                            this.videoFullUrl = result.full_url;
                                            this.videoUsesIframe = result.uses_iframe;
                                            this.videoError = null;
                                        } else {
                                            this.videoPreviewState = 'invalid';
                                            this.videoError = result.error;
                                            this.videoSourceType = null;
                                        }
                                    } catch (e) {
                                        this.videoPreviewState = 'invalid';
                                        this.videoError = 'Failed to detect video source';
                                    }
                                }
                            }" x-init="$watch('editAttributes.src', (value) => {
                                clearTimeout(videoDetectTimeout);
                                videoDetectTimeout = setTimeout(() => { detectVideoUrl(); }, 500);
                            }); if (editAttributes.src && videoMode === 'url') detectVideoUrl();
                            $wire.$on('upload:errored', ({ name }) => {
                                if (name === 'videoUpload') {
                                    videoError = 'Upload failed. File may have exceeded size limit allowed is 60MB or use an unsupported format.';
                                    videoPreviewState = 'invalid';
                                }
                            });">
                                <!-- Source Mode Toggle -->
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Video Source</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="videoMode"
                                                value="url"
                                                @change="if (videoMode === 'url') { editAttributes.src = ''; editAttributes.poster = ''; videoPreviewState = 'empty'; $wire.updateBlock(index, editAttributes); }"
                                                class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                            >
                                            <span class="text-sm text-admin-text">External URL</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="videoMode"
                                                value="upload"
                                                @change="if (videoMode === 'upload') { editAttributes.src = ''; editAttributes.poster = ''; videoPreviewState = 'empty'; $wire.updateBlock(index, editAttributes); }"
                                                class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                            >
                                            <span class="text-sm text-admin-text">Upload File</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- External URL Mode -->
                                <template x-if="videoMode === 'url'">
                                    <div class="space-y-3">
                                        <!-- Video Preview Area -->
                                        <div>
                                            <label class="block text-sm font-medium text-admin-text-muted mb-2">Video Preview</label>
                                            <div class="relative rounded-lg overflow-hidden bg-admin-surface-alt border border-admin-border aspect-video">
                                                <!-- Empty State -->
                                                <template x-if="videoPreviewState === 'empty'">
                                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-admin-text-muted">
                                                        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                        </svg>
                                                        <p class="text-sm">Paste a video URL below to see preview</p>
                                                    </div>
                                                </template>

                                                <!-- Loading State -->
                                                <template x-if="videoPreviewState === 'loading'">
                                                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-admin-surface-alt">
                                                        <svg class="w-8 h-8 animate-spin text-admin-accent mb-3" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        <p class="text-sm text-admin-text-muted">Detecting video source...</p>
                                                    </div>
                                                </template>

                                                <!-- Valid Embed State -->
                                                <template x-if="videoPreviewState === 'valid'">
                                                    <div class="relative w-full h-full">
                                                        <!-- Platform Badge -->
                                                        <div class="absolute top-3 left-3 z-10">
                                                            <span class="px-2.5 py-1 text-xs font-medium rounded-md bg-admin-accent/20 text-admin-accent border border-admin-accent/30" x-text="videoSourceLabel"></span>
                                                        </div>
                                                        <!-- Iframe Embed for platforms -->
                                                        <template x-if="videoUsesIframe && videoEmbedUrl">
                                                            <iframe :src="videoEmbedUrl" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                        </template>
                                                        <!-- Native Video for direct URLs -->
                                                        <template x-if="!videoUsesIframe">
                                                            <video :src="videoFullUrl" class="w-full h-full" controls muted></video>
                                                        </template>
                                                    </div>
                                                </template>

                                                <!-- Invalid State -->
                                                <template x-if="videoPreviewState === 'invalid'">
                                                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-red-500/5 border border-red-500/20 m-4 rounded-lg">
                                                        <svg class="w-10 h-10 text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                                        </svg>
                                                        <p class="text-sm text-red-400 text-center px-4" x-text="videoError || 'Invalid video URL'"></p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- URL Input -->
                                        <div>
                                            <label class="block text-sm font-medium text-admin-text-muted mb-2">Video URL</label>
                                            <input
                                                type="url"
                                                x-model="editAttributes.src"
                                                @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                                class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                placeholder="https://youtube.com/... or https://..."
                                            >
                                            <!-- Inline Validation -->
                                            <div class="mt-2 flex items-center gap-2">
                                                <template x-if="videoPreviewState === 'valid'">
                                                    <div class="flex items-center gap-1.5 text-green-400 text-xs">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span x-text="'Detected: ' + videoSourceLabel"></span>
                                                    </div>
                                                </template>
                                                <template x-if="videoPreviewState === 'invalid'">
                                                    <div class="flex items-center gap-1.5 text-red-400 text-xs">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c-.866 1.5.217 3.374 1.948 3.374H14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
                                                        </svg>
                                                        <span>Unrecognized URL format</span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- File Upload Mode -->
                                <template x-if="videoMode === 'upload'">
                                    <div class="space-y-3">
                                        <!-- Video/Thumbnail Preview -->
                                        <div x-show="editAttributes.src || editAttributes.poster || $wire.videoUpload" class="relative rounded-lg overflow-hidden bg-admin-surface-alt border border-admin-border">
                                            <template x-if="editAttributes.poster">
                                                <img :src="editAttributes.poster" class="w-full h-48 object-cover">
                                            </template>
                                            <template x-if="!editAttributes.poster && editAttributes.src">
                                                <video :src="editAttributes.src" class="w-full h-48 object-cover" controls muted></video>
                                            </template>
                                            <template x-if="!editAttributes.poster && !editAttributes.src && $wire.videoUpload">
                                                <video :src="$wire.videoUpload ? URL.createObjectURL($wire.videoUpload) : ''" class="w-full h-48 object-cover" controls muted></video>
                                            </template>
                                            <button
                                                type="button"
                                                @click="$wire.removeUploadedVideo(index)"
                                                class="absolute top-2 right-2 p-2 bg-red-500/80 hover:bg-red-500 rounded-lg text-white transition-colors"
                                                title="Remove Video"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Upload Progress -->
                                        <div x-show="$wire.videoUploadProgress > 0 && $wire.videoUploadProgress < 100" class="w-full">
                                            <div class="flex items-center justify-between text-sm text-admin-text-muted mb-1">
                                                <span>Uploading...</span>
                                                <span x-text="$wire.videoUploadProgress + '%'"></span>
                                            </div>
                                            <div class="w-full bg-admin-surface rounded-full h-2">
                                                <div
                                                    class="bg-admin-accent h-2 rounded-full transition-all duration-300"
                                                    :style="'width: ' + $wire.videoUploadProgress + '%'"
                                                ></div>
                                            </div>
                                        </div>

                                        <template x-if="videoError && videoMode === 'upload'">
                                            <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9.303 3.376c-.866 1.5.217 3.374 1.948 3.374H14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
                                                </svg>
                                                <span x-text="videoError"></span>
                                            </div>
                                        </template>

                                        <!-- Upload Button -->
                                        <div>
                                            <label class="block">
                                                <input
                                                    type="file"
                                                    wire:model="videoUpload"
                                                    wire:upload="uploadVideo"
                                                    wire:upload.progress="videoUploadProgress"
                                                    @change="$wire.startVideoUpload(index)"
                                                    accept="video/mp4,video/webm,video/quicktime,video/x-msvideo"
                                                    class="hidden"
                                                >
                                                <span class="inline-flex items-center justify-center w-full px-4 py-2 bg-admin-surface-alt border border-admin-border rounded-lg text-admin-text hover:bg-admin-surface transition-colors cursor-pointer">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                    Upload Video
                                                </span>
                                            </label>
                                            <p class="text-xs text-admin-text-muted mt-1">MP4, WEBM, MOV up to 60MB</p>
                                        </div>
                                    </div>
                                </template>

                                <!-- Common Options -->
                                <div class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        x-model="editAttributes.controls"
                                        @change="$wire.updateBlock(index, editAttributes)"
                                        id="video-controls"
                                        class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                    >
                                    <label for="video-controls" class="text-sm text-admin-text-muted">Show Controls</label>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">CSS Class (optional)</label>
                                    <input
                                        type="text"
                                        x-model="editAttributes.class"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="e.g., rounded-lg shadow-lg"
                                    >
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'divider'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Style</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="editAttributes.style"
                                                value="line"
                                                @change="$wire.updateBlock(index, editAttributes)"
                                                class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                            >
                                            <span class="text-sm text-admin-text">Line</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="editAttributes.style"
                                                value="dots"
                                                @change="$wire.updateBlock(index, editAttributes)"
                                                class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                            >
                                            <span class="text-sm text-admin-text">Dots</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="editAttributes.style"
                                                value="space"
                                                @change="$wire.updateBlock(index, editAttributes)"
                                                class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                            >
                                            <span class="text-sm text-admin-text">Space</span>
                                        </label>
                                    </div>
                                </div>
                                <div x-show="editAttributes.style === 'space'">
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Size</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="editAttributes.size"
                                                value="sm"
                                                @change="$wire.updateBlock(index, editAttributes)"
                                                class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                            >
                                            <span class="text-sm text-admin-text">Small</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="editAttributes.size"
                                                value="md"
                                                @change="$wire.updateBlock(index, editAttributes)"
                                                class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                            >
                                            <span class="text-sm text-admin-text">Medium</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="editAttributes.size"
                                                value="lg"
                                                @change="$wire.updateBlock(index, editAttributes)"
                                                class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                            >
                                            <span class="text-sm text-admin-text">Large</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'alert'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Type</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="editAttributes.type"
                                                value="info"
                                                @change="$wire.updateBlock(index, editAttributes)"
                                                class="rounded border-admin-border bg-admin-surface-alt text-blue-500 focus:ring-blue-500"
                                            >
                                            <span class="text-sm text-admin-text">Info</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="editAttributes.type"
                                                value="success"
                                                @change="$wire.updateBlock(index, editAttributes)"
                                                class="rounded border-admin-border bg-admin-surface-alt text-green-500 focus:ring-green-500"
                                            >
                                            <span class="text-sm text-admin-text">Success</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="editAttributes.type"
                                                value="warning"
                                                @change="$wire.updateBlock(index, editAttributes)"
                                                class="rounded border-admin-border bg-admin-surface-alt text-yellow-500 focus:ring-yellow-500"
                                            >
                                            <span class="text-sm text-admin-text">Warning</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                x-model="editAttributes.type"
                                                value="danger"
                                                @change="$wire.updateBlock(index, editAttributes)"
                                                class="rounded border-admin-border bg-admin-surface-alt text-red-500 focus:ring-red-500"
                                            >
                                            <span class="text-sm text-admin-text">Danger</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Title (optional)</label>
                                    <input
                                        type="text"
                                        x-model="editAttributes.title"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Content</label>
                                    <textarea
                                        x-model="editAttributes.content"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        rows="3"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    ></textarea>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        x-model="editAttributes.dismissible"
                                        @change="$wire.updateBlock(index, editAttributes)"
                                        id="alert-dismissible"
                                        class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                    >
                                    <label for="alert-dismissible" class="text-sm text-admin-text-muted">Dismissible</label>
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'html'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">HTML Content</label>
                                    <textarea
                                        x-model="editAttributes.content"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        rows="8"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm font-mono focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="<p>Your HTML here...</p>"
                                    ></textarea>
                                    <p class="text-xs text-admin-text-muted mt-1">HTML will be sanitized on save (tags, attributes, and URLs filtered).</p>
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'embed'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Embed URL</label>
                                    <input
                                        type="url"
                                        x-model="editAttributes.url"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="https://youtube.com/watch?v=..."
                                    >
                                    <p class="text-xs text-admin-text-muted mt-1">Supports YouTube, Vimeo, Twitter/X, Instagram, and other oEmbed providers.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Title (optional)</label>
                                    <input
                                        type="text"
                                        x-model="editAttributes.title"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'accordion'">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        x-model="editAttributes.multiple_open"
                                        @change="$wire.updateBlock(index, editAttributes)"
                                        id="accordion-multiple-open"
                                        class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                    >
                                    <label for="accordion-multiple-open" class="text-sm text-admin-text-muted">Allow multiple items open at once</label>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Accordion Items</label>
                                    <div class="space-y-2">
                                        <template x-for="(item, itemIndex) in editAttributes.items" :key="itemIndex">
                                            <div class="bg-admin-surface border border-admin-border rounded-lg p-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-admin-text-muted" x-text="'Item ' + (itemIndex + 1)"></span>
                                                    <button
                                                        type="button"
                                                        @click="editAttributes.items.splice(itemIndex, 1); $wire.updateBlock(index, editAttributes)"
                                                        class="text-red-400 hover:text-red-500 text-xs"
                                                    >
                                                        Remove
                                                    </button>
                                                </div>
                                                <input
                                                    type="text"
                                                    x-model="editAttributes.items[itemIndex].title"
                                                    @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                                    class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                    placeholder="Title"
                                                >
                                                <textarea
                                                    x-model="editAttributes.items[itemIndex].content"
                                                    @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                                    rows="2"
                                                    class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                    placeholder="Content"
                                                ></textarea>
                                            </div>
                                        </template>
                                        <button
                                            type="button"
                                            @click="editAttributes.items.push({title: '', content: ''}); $wire.updateBlock(index, editAttributes)"
                                            class="w-full py-2 border-2 border-dashed border-admin-border rounded-lg text-admin-text-muted text-sm hover:border-admin-accent hover:text-admin-accent transition-colors"
                                        >
                                            + Add Item
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'table'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Table Caption (optional)</label>
                                    <input
                                        type="text"
                                        x-model="editAttributes.caption"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="Table caption..."
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Headers (comma-separated)</label>
                                    <input
                                        type="text"
                                        x-model="editAttributes.headersRaw"
                                        @input.debounce.300ms="editAttributes.headers = editAttributes.headersRaw.split(',').map(h => h.trim()); $wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="Column 1, Column 2, Column 3"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Rows</label>
                                    <div class="space-y-2">
                                        <template x-for="(row, rowIndex) in editAttributes.rows" :key="rowIndex">
                                            <div class="bg-admin-surface border border-admin-border rounded-lg p-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-admin-text-muted" x-text="'Row ' + (rowIndex + 1)"></span>
                                                    <button
                                                        type="button"
                                                        @click="editAttributes.rows.splice(rowIndex, 1); $wire.updateBlock(index, editAttributes)"
                                                        class="text-red-400 hover:text-red-500 text-xs"
                                                    >
                                                        Remove
                                                    </button>
                                                </div>
                                                <input
                                                    type="text"
                                                    x-model="editAttributes.rowsRaw[rowIndex]"
                                                    @input.debounce.300ms="editAttributes.rows[rowIndex] = editAttributes.rowsRaw[rowIndex].split(',').map(c => c.trim()); $wire.updateBlock(index, editAttributes)"
                                                    class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                    placeholder="Cell 1, Cell 2, Cell 3"
                                                >
                                            </div>
                                        </template>
                                        <button
                                            type="button"
                                            @click="editAttributes.rows.push([]); editAttributes.rowsRaw.push(''); $wire.updateBlock(index, editAttributes)"
                                            class="w-full py-2 border-2 border-dashed border-admin-border rounded-lg text-admin-text-muted text-sm hover:border-admin-accent hover:text-admin-accent transition-colors"
                                        >
                                            + Add Row
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'gallery'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Columns (1-6)</label>
                                    <input
                                        type="number"
                                        min="1"
                                        max="6"
                                        x-model="editAttributes.columns"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Images</label>
                                    <div class="space-y-2">
                                        <template x-for="(image, imageIndex) in editAttributes.images" :key="imageIndex">
                                            <div class="bg-admin-surface border border-admin-border rounded-lg p-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-admin-text-muted" x-text="'Image ' + (imageIndex + 1)"></span>
                                                    <button
                                                        type="button"
                                                        @click="editAttributes.images.splice(imageIndex, 1); $wire.updateBlock(index, editAttributes)"
                                                        class="text-red-400 hover:text-red-500 text-xs"
                                                    >
                                                        Remove
                                                    </button>
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-admin-text-muted mb-1">Image URL</label>
                                                    <input
                                                        type="text"
                                                        x-model="editAttributes.images[imageIndex].src"
                                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                                        class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                        placeholder="https://..."
                                                    >
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-admin-text-muted mb-1">Alt Text</label>
                                                    <input
                                                        type="text"
                                                        x-model="editAttributes.images[imageIndex].alt"
                                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                                        class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                        placeholder="Description for screen readers"
                                                    >
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-admin-text-muted mb-1">Caption (optional)</label>
                                                    <input
                                                        type="text"
                                                        x-model="editAttributes.images[imageIndex].caption"
                                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                                        class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                        placeholder="Image caption"
                                                    >
                                                </div>
                                            </div>
                                        </template>
                                        <button
                                            type="button"
                                            @click="editAttributes.images.push({src: '', alt: '', caption: ''}); $wire.updateBlock(index, editAttributes)"
                                            class="w-full py-2 border-2 border-dashed border-admin-border rounded-lg text-admin-text-muted text-sm hover:border-admin-accent hover:text-admin-accent transition-colors"
                                        >
                                            + Add Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'form'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Submit Button Text</label>
                                    <input
                                        type="text"
                                        x-model="editAttributes.submit_text"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="Submit"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Action URL (optional)</label>
                                    <input
                                        type="url"
                                        x-model="editAttributes.action_url"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="https://example.com/submit"
                                    >
                                    <p class="text-xs text-admin-text-muted mt-1">Leave empty to use event-based submission.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Form Fields</label>
                                    <div class="space-y-2">
                                        <template x-for="(field, fieldIndex) in editAttributes.fields" :key="fieldIndex">
                                            <div class="bg-admin-surface border border-admin-border rounded-lg p-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-admin-text-muted" x-text="'Field ' + (fieldIndex + 1)"></span>
                                                    <button
                                                        type="button"
                                                        @click="editAttributes.fields.splice(fieldIndex, 1); $wire.updateBlock(index, editAttributes)"
                                                        class="text-red-400 hover:text-red-500 text-xs"
                                                    >
                                                        Remove
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div>
                                                        <label class="block text-xs text-admin-text-muted mb-1">Field Name</label>
                                                        <input
                                                            type="text"
                                                            x-model="editAttributes.fields[fieldIndex].name"
                                                            @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                                            class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                            placeholder="field_name"
                                                        >
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs text-admin-text-muted mb-1">Field Type</label>
                                                        <select
                                                            x-model="editAttributes.fields[fieldIndex].type"
                                                            @change="$wire.updateBlock(index, editAttributes)"
                                                            class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                        >
                                                            <option value="text">Text</option>
                                                            <option value="email">Email</option>
                                                            <option value="textarea">Textarea</option>
                                                            <option value="select">Select</option>
                                                            <option value="checkbox">Checkbox</option>
                                                            <option value="radio">Radio</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-admin-text-muted mb-1">Label</label>
                                                    <input
                                                        type="text"
                                                        x-model="editAttributes.fields[fieldIndex].label"
                                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                                        class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                        placeholder="Field Label"
                                                    >
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <input
                                                        type="checkbox"
                                                        x-model="editAttributes.fields[fieldIndex].required"
                                                        @change="$wire.updateBlock(index, editAttributes)"
                                                        class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                                    >
                                                    <label class="text-sm text-admin-text-muted">Required</label>
                                                </div>
                                                <template x-if="['select', 'radio'].includes(editAttributes.fields[fieldIndex].type)">
                                                    <div>
                                                        <label class="block text-xs text-admin-text-muted mb-1">Options (comma-separated)</label>
                                                        <input
                                                            type="text"
                                                            x-model="editAttributes.fields[fieldIndex].optionsRaw"
                                                            @input.debounce.300ms="editAttributes.fields[fieldIndex].options = editAttributes.fields[fieldIndex].optionsRaw.split(',').map(o => o.trim()); $wire.updateBlock(index, editAttributes)"
                                                            class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                            placeholder="Option 1, Option 2, Option 3"
                                                        >
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <button
                                            type="button"
                                            @click="editAttributes.fields.push({name: '', label: '', type: 'text', required: false, options: []}); $wire.updateBlock(index, editAttributes)"
                                            class="w-full py-2 border-2 border-dashed border-admin-border rounded-lg text-admin-text-muted text-sm hover:border-admin-accent hover:text-admin-accent transition-colors"
                                        >
                                            + Add Field
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'columns'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Column Layout</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <template x-for="(colWidth, colIndex) in editAttributes.column_widths" :key="colIndex">
                                            <div class="flex items-center gap-2">
                                                <input
                                                    type="number"
                                                    min="1"
                                                    max="12"
                                                    x-model="editAttributes.column_widths[colIndex]"
                                                    @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                                    class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                >
                                                <span class="text-xs text-admin-text-muted">/12</span>
                                            </div>
                                        </template>
                                    </div>
                                    <p class="text-xs text-admin-text-muted mt-1">Column widths must sum to 12.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Columns</label>
                                    <div class="space-y-2">
                                        <template x-for="(column, colIndex) in editAttributes.columns" :key="colIndex">
                                            <div class="bg-admin-surface border border-admin-border rounded-lg p-3">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-xs text-admin-text-muted" x-text="'Column ' + (colIndex + 1) + ' (' + (editAttributes.column_widths[colIndex] || 6) + '/12)'"></span>
                                                    <button
                                                        type="button"
                                                        @click="editAttributes.columns.splice(colIndex, 1); editAttributes.column_widths.splice(colIndex, 1); $wire.updateBlock(index, editAttributes)"
                                                        class="text-red-400 hover:text-red-500 text-xs"
                                                    >
                                                        Remove Column
                                                    </button>
                                                </div>
                                                <div class="text-xs text-admin-text-muted mb-2">
                                                    This column contains <span x-text="column ? column.length : 0"></span> block(s). Edit column content by managing blocks at the parent level.
                                                </div>
                                            </div>
                                        </template>
                                        <button
                                            type="button"
                                            @click="editAttributes.columns.push([]); editAttributes.column_widths.push(6); $wire.updateBlock(index, editAttributes)"
                                            class="w-full py-2 border-2 border-dashed border-admin-border rounded-lg text-admin-text-muted text-sm hover:border-admin-accent hover:text-admin-accent transition-colors"
                                        >
                                            + Add Column
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'tabs'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Tabs</label>
                                    <div class="space-y-2">
                                        <template x-for="(tab, tabIndex) in editAttributes.tabs" :key="tabIndex">
                                            <div class="bg-admin-surface border border-admin-border rounded-lg p-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-admin-text-muted" x-text="'Tab ' + (tabIndex + 1)"></span>
                                                    <button
                                                        type="button"
                                                        @click="editAttributes.tabs.splice(tabIndex, 1); $wire.updateBlock(index, editAttributes)"
                                                        class="text-red-400 hover:text-red-500 text-xs"
                                                    >
                                                        Remove Tab
                                                    </button>
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-admin-text-muted mb-1">Tab Label</label>
                                                    <input
                                                        type="text"
                                                        x-model="editAttributes.tabs[tabIndex].label"
                                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                                        class="w-full bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                        placeholder="Tab Label"
                                                    >
                                                </div>
                                                <div class="text-xs text-admin-text-muted">
                                                    This tab contains <span x-text="tab.content ? tab.content.length : 0"></span> block(s). Edit tab content by managing blocks at the parent level.
                                                </div>
                                            </div>
                                        </template>
                                        <button
                                            type="button"
                                            @click="editAttributes.tabs.push({label: '', content: []}); $wire.updateBlock(index, editAttributes)"
                                            class="w-full py-2 border-2 border-dashed border-admin-border rounded-lg text-admin-text-muted text-sm hover:border-admin-accent hover:text-admin-accent transition-colors"
                                        >
                                            + Add Tab
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'carousel'">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        x-model="editAttributes.autoplay"
                                        @change="$wire.updateBlock(index, editAttributes)"
                                        id="carousel-autoplay"
                                        class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                    >
                                    <label for="carousel-autoplay" class="text-sm text-admin-text-muted">Autoplay</label>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Interval (seconds)</label>
                                    <input
                                        type="number"
                                        min="1"
                                        max="60"
                                        x-model="editAttributes.interval"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Slides</label>
                                    <div class="space-y-2">
                                        <template x-for="(slide, slideIndex) in editAttributes.slides" :key="slideIndex">
                                            <div class="bg-admin-surface border border-admin-border rounded-lg p-3">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-xs text-admin-text-muted" x-text="'Slide ' + (slideIndex + 1)"></span>
                                                    <button
                                                        type="button"
                                                        @click="editAttributes.slides.splice(slideIndex, 1); $wire.updateBlock(index, editAttributes)"
                                                        class="text-red-400 hover:text-red-500 text-xs"
                                                    >
                                                        Remove Slide
                                                    </button>
                                                </div>
                                                <div class="text-xs text-admin-text-muted">
                                                    This slide contains <span x-text="slide ? slide.length : 0"></span> block(s). Edit slide content by managing blocks at the parent level.
                                                </div>
                                            </div>
                                        </template>
                                        <button
                                            type="button"
                                            @click="editAttributes.slides.push([]); $wire.updateBlock(index, editAttributes)"
                                            class="w-full py-2 border-2 border-dashed border-admin-border rounded-lg text-admin-text-muted text-sm hover:border-admin-accent hover:text-admin-accent transition-colors"
                                        >
                                            + Add Slide
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'countdown'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Target Date</label>
                                    <input
                                        type="datetime-local"
                                        x-model="editAttributes.target_date"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Label (optional)</label>
                                    <input
                                        type="text"
                                        x-model="editAttributes.label"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="e.g., Sale Ends In"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Timezone</label>
                                    <input
                                        type="text"
                                        x-model="editAttributes.timezone"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="e.g., UTC"
                                    >
                                    <p class="text-xs text-admin-text-muted mt-1">Leave empty to use default timezone.</p>
                                </div>
                            </div>
                        </template>

                        <template x-if="block.type === 'poll'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Question</label>
                                    <input
                                        type="text"
                                        x-model="editAttributes.question"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                        placeholder="What is your question?"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Options</label>
                                    <div class="space-y-2">
                                        <template x-for="(option, optionIndex) in editAttributes.options" :key="optionIndex">
                                            <div class="flex items-center gap-2">
                                                <input
                                                    type="text"
                                                    x-model="editAttributes.options[optionIndex]"
                                                    @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                                    class="flex-1 bg-admin-surface-alt border border-admin-border rounded px-2 py-1 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                                    placeholder="Option text"
                                                >
                                                <button
                                                    type="button"
                                                    @click="editAttributes.options.splice(optionIndex, 1); $wire.updateBlock(index, editAttributes)"
                                                    class="text-red-400 hover:text-red-500 text-xs"
                                                    x-show="editAttributes.options.length > 2"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        </template>
                                        <button
                                            type="button"
                                            @click="editAttributes.options.push(''); $wire.updateBlock(index, editAttributes)"
                                            class="w-full py-2 border-2 border-dashed border-admin-border rounded-lg text-admin-text-muted text-sm hover:border-admin-accent hover:text-admin-accent transition-colors"
                                        >
                                            + Add Option
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            x-model="editAttributes.allow_multiple"
                                            @change="$wire.updateBlock(index, editAttributes)"
                                            id="poll-allow-multiple"
                                            class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                        >
                                        <label for="poll-allow-multiple" class="text-sm text-admin-text-muted">Allow Multiple Selections</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            x-model="editAttributes.show_results"
                                            @change="$wire.updateBlock(index, editAttributes)"
                                            id="poll-show-results"
                                            class="rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent"
                                        >
                                        <label for="poll-show-results" class="text-sm text-admin-text-muted">Show Results After Voting</label>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        @endif
    </div>

    <!-- Add Block Button -->
    <div class="relative" x-data="{ open: false }">
        <button 
            type="button"
            @click="open = !open"
            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-admin-surface-alt border border-admin-border rounded-xl text-admin-text hover:bg-admin-surface transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Block
        </button>

        <!-- Block Type Dropdown -->
        <div 
            x-show="open" 
            @click.outside="open = false"
            x-transition
            class="absolute z-10 w-full mt-2 bg-admin-surface border border-admin-border rounded-xl shadow-xl overflow-hidden"
        >
            <div class="p-2 max-h-64 overflow-y-auto">
                @foreach($availableBlockTypes as $type => $label)
                    <button 
                        type="button"
                        @click="$wire.addBlock('{{ $type }}'); open = false"
                        class="w-full text-left px-3 py-2 rounded-lg hover:bg-admin-surface-alt text-admin-text text-sm transition-colors"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
