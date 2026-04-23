<div x-data="{
    blocks: @js($blocks) || [],
    availableBlockTypes: @js($availableBlockTypes) || {},
    draggingIndex: null,
    editingIndex: null,
    editAttributes: {},
    updateBlock(index, attributes) {
        $wire.updateBlock(index, attributes);
    }
}" x-init="
    @this.on('blocks-updated', (event) => {
        if (event.detail && event.detail.blocks) {
            blocks = event.detail.blocks;
        }
    });
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
                    draggable="true"
                    @dragstart="draggingIndex = index"
                    @dragover.prevent
                    @drop="if (draggingIndex !== null && draggingIndex !== index) { $wire.moveBlock(draggingIndex, index); draggingIndex = null; }"
                >
                    <!-- Block Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium px-2 py-1 rounded bg-admin-accent/20 text-admin-accent uppercase tracking-wide">
                                <span x-text="availableBlockTypes[block.type] || block.type"></span>
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
                    </div>

                    <!-- Edit Panel -->
                    <div x-show="editingIndex === index" x-transition class="mt-4 pt-4 border-t border-admin-border-subtle">
                        <template x-if="editingIndex === index">
                            <div x-init="editAttributes.itemsRaw = (editAttributes.items || []).join('\n')"></div>
                        </template>
                        <template x-if="block.type === 'paragraph'">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Content</label>
                                    <textarea 
                                        x-model="editAttributes.content"
                                        @input.debounce.300ms="$wire.updateBlock(index, editAttributes)"
                                        rows="4"
                                        class="w-full bg-admin-surface-alt border border-admin-border rounded-lg px-3 py-2 text-admin-text text-sm focus:ring-2 focus:ring-admin-accent focus:border-transparent"
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
                            <div class="space-y-3">
                                <!-- Image Preview -->
                                <div x-show="editAttributes.src || $wire.imageUpload" class="relative rounded-lg overflow-hidden bg-admin-surface-alt border border-admin-border">
                                    <img 
                                        :src="editAttributes.src || ($wire.imageUpload ? URL.createObjectURL($wire.imageUpload) : '')"
                                        x-show="editAttributes.src || $wire.imageUpload"
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

                                <!-- Upload Button -->
                                <div>
                                    <label class="block">
                                        <input 
                                            type="file" 
                                            wire:model="imageUpload"
                                            wire:upload="uploadImage"
                                            wire:upload.progress="uploadProgress"
                                            @change="$wire.startImageUpload(index)"
                                            accept="image/*"
                                            class="hidden"
                                        >
                                        <span class="inline-flex items-center justify-center w-full px-4 py-2 bg-admin-surface-alt border border-admin-border rounded-lg text-admin-text hover:bg-admin-surface transition-colors cursor-pointer">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                            </svg>
                                            Upload Image
                                        </span>
                                    </label>
                                </div>

                                <div class="text-center text-admin-text-muted text-sm">or</div>

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
                            }); if (editAttributes.src && videoMode === 'url') detectVideoUrl();">
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
                                            <p class="text-xs text-admin-text-muted mt-1">MP4, WEBM, MOV up to 30MB</p>
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
