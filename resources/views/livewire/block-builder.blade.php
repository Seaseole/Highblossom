<div x-data="{
    editingIndex: null,
    editAttributes: {},
    blocks: $wire.entangle('blocks'),
}" class="space-y-4">
    @script
    <script>
        $wire.on('blocks-updated', () => {
             const contentInput = document.getElementById('content-input');
             if (contentInput) {
                 contentInput.value = JSON.stringify(Alpine.raw($wire.blocks));
             }
        });
        
        // Initial sync
        const contentInput = document.getElementById('content-input');
        if (contentInput) {
            contentInput.value = JSON.stringify(Alpine.raw($wire.blocks));
        }
    </script>
    @endscript

    <!-- Blocks List -->
    @php
        $pollBlockIds = collect($blocks)
            ->where('type', 'poll')
            ->pluck('attributes.poll_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $preloadedPolls = !empty($pollBlockIds)
            ? \App\Models\Poll::with('votes')->findMany($pollBlockIds)->keyBy('id')
            : collect();
    @endphp
    <div class="space-y-3">
        @if(empty($blocks))
            <div class="text-center py-8 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl">
                <p class="text-gray-500 dark:text-gray-400">No content blocks yet. Add your first block below.</p>
            </div>
        @else
            @foreach($blocks as $index => $block)
                <div
                    wire:key="block-{{ $block['id'] ?? $index }}"
                    class="bg-white dark:bg-[#16161D] border border-gray-200 dark:border-white/10 rounded-2xl p-4 group relative"
                    :class="{ 'ring-2 ring-gray-900 dark:ring-white': editingIndex === {{ $index }} }"
                    data-block-index="{{ $index }}"
                    data-block-id="{{ $block['id'] ?? '' }}"
                >
                    <!-- Block Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-gray-100 uppercase tracking-wider">
                                <span>{{ $availableBlockTypes[$block['type']] ?? $block['type'] }}</span>
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">#{{ $index + 1 }}</span>
                        </div>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button 
                                type="button"
                                @click="editingIndex = editingIndex === {{ $index }} ? null : {{ $index }}; if(editingIndex === {{ $index }}) { editAttributes = @js($block['attributes']) }"
                                class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
                                title="Edit"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button 
                                type="button"
                                wire:click="removeBlock({{ $index }})"
                                wire:confirm="Are you sure you want to delete this block?"
                                class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors"
                                title="Delete"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Edit Panel -->
                    <div x-show="editingIndex === {{ $index }}" x-cloak x-transition class="mt-4 pt-4 border-t border-gray-100 dark:border-white/10">
                        @if($block['type'] === 'paragraph')
                            <div class="space-y-3" x-data="{
                                editorId: 'ckeditor-{{ $block['id'] }}',
                                editorInstance: null,
                                initEditor() {
                                    this.$nextTick(() => {
                                        if (CKEDITOR.instances[this.editorId]) {
                                            CKEDITOR.instances[this.editorId].destroy(true);
                                        }

                                        this.editorInstance = CKEDITOR.replace(this.editorId, {
                                            customConfig: '/vendor/ckeditor/config.js',
                                            height: 200,
                                        });

                                        this.editorInstance.on('change', () => {
                                            editAttributes.content = this.editorInstance.getData();
                                            $wire.updateBlock({{ $index }}, editAttributes);
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
                            x-effect="if (editingIndex === {{ $index }}) { initEditor() } else { destroyEditor() }"
                            >
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content</label>
                                    <textarea
                                        :id="editorId"
                                        x-text="editAttributes.content"
                                        class="w-full"
                                    ></textarea>
                                </div>
                            </div>
                        @elseif($block['type'] === 'heading')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Heading Text</label>
                                    <input type="text" x-model="editAttributes.content" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Level</label>
                                    <select x-model="editAttributes.level" @change="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                        <option value="h1">H1</option>
                                        <option value="h2">H2</option>
                                        <option value="h3">H3</option>
                                        <option value="h4">H4</option>
                                    </select>
                                </div>
                            </div>
                        @elseif($block['type'] === 'quote')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quote</label>
                                    <textarea x-model="editAttributes.content" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" rows="3"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Author</label>
                                        <input type="text" x-model="editAttributes.author" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Citation</label>
                                        <input type="text" x-model="editAttributes.cite" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                    </div>
                                </div>
                            </div>
                        @elseif($block['type'] === 'cta')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                                    <input type="text" x-model="editAttributes.title" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                    <textarea x-model="editAttributes.description" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" rows="2"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button Text</label>
                                        <input type="text" x-model="editAttributes.button_text" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button URL</label>
                                        <input type="text" x-model="editAttributes.button_url" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                    </div>
                                </div>
                            </div>
                        @elseif($block['type'] === 'video')
                            <div class="space-y-4" x-data="{
                                videoMode: editAttributes.src && (editAttributes.src.startsWith('/') || editAttributes.src.includes('storage/') || editAttributes.src.includes('videos/')) ? 'upload' : 'url',
                                videoPreviewState: editAttributes.src ? 'valid' : 'empty',
                                videoSourceType: null,
                                videoSourceLabel: '',
                                videoEmbedUrl: null,
                                videoFullUrl: editAttributes.src || '',
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
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Video Source</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" x-model="videoMode" value="url" class="rounded-full border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white" @change="editAttributes.src = ''; videoPreviewState = 'empty';">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">External URL</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" x-model="videoMode" value="upload" class="rounded-full border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white" @change="editAttributes.src = ''; videoPreviewState = 'empty';">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Upload File</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- External URL Mode -->
                                <div x-show="videoMode === 'url'" class="space-y-3">
                                    <div class="relative rounded-lg overflow-hidden bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 aspect-video flex items-center justify-center">
                                        <div x-show="videoPreviewState === 'empty'"><p class="text-sm text-gray-500">Paste a video URL</p></div>
                                        <div x-show="videoPreviewState === 'loading'"><p class="text-sm text-gray-500">Detecting...</p></div>
                                        <div x-show="videoPreviewState === 'valid'" class="w-full h-full">
                                            <template x-if="videoUsesIframe"><iframe :src="videoEmbedUrl" class="w-full h-full border-0" allowfullscreen></iframe></template>
                                            <template x-if="!videoUsesIframe"><video :src="videoFullUrl" class="w-full h-full" controls></video></template>
                                        </div>
                                    </div>
                                    <input type="url" x-model="editAttributes.src" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="https://youtube.com/...">
                                </div>

                                <!-- File Upload Mode -->
                                <div x-show="videoMode === 'upload'" class="space-y-3">
                                    <div x-show="editAttributes.src || $wire.videoUpload" class="relative rounded-lg overflow-hidden bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 aspect-video">
                                        <video :src="editAttributes.src || ($wire.videoUpload ? URL.createObjectURL($wire.videoUpload) : '')" class="w-full h-full object-cover" controls></video>
                                    </div>
                                    <input type="file" wire:model="videoUpload" @change="$wire.startVideoUpload({{ $index }})" accept="video/mp4,video/webm" class="w-full text-sm">
                                </div>
                            </div>
                        @elseif($block['type'] === 'image')
                            <div class="space-y-4" x-data="{ 
                                isUploading: false,
                                handleUpload(event) {
                                    this.isUploading = true;
                                    $wire.activeImageUploadIndex = {{ $index }};
                                    $wire.imageUpload = event.target.files[0];
                                    $wire.uploadImageForBlock({{ $index }});
                                    this.isUploading = false;
                                }
                            }">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Image URL</label>
                                    <div class="flex items-center gap-2">
                                        <input type="text" x-model="editAttributes.src" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                        <button type="button" @click="$refs.imageInput.click()" class="px-4 py-2 bg-gray-100 dark:bg-white/5 rounded-xl text-sm font-medium hover:bg-gray-200 dark:hover:bg-white/10 transition-all">Upload</button>
                                        <input type="file" x-ref="imageInput" class="hidden" accept="image/*" @change="handleUpload">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alt Text</label>
                                        <input type="text" x-model="editAttributes.alt" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Caption</label>
                                        <input type="text" x-model="editAttributes.caption" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                    </div>
                                </div>
                            </div>
                        @elseif($block['type'] === 'gallery')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Columns</label>
                                    <input type="number" x-model="editAttributes.columns" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" min="1" max="6" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                </div>
                                <div class="p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Gallery images are managed in the main Media Library.</p>
                                </div>
                            </div>
                        @elseif($block['type'] === 'list')
                            <div class="space-y-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" x-model="editAttributes.type" value="ul" @change="$wire.updateBlock({{ $index }}, editAttributes)" class="rounded-full border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Unordered List</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" x-model="editAttributes.type" value="ol" @change="$wire.updateBlock({{ $index }}, editAttributes)" class="rounded-full border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Ordered List</span>
                                </label>
                                <textarea x-model="editAttributes.itemsRaw" @input.debounce.300ms="editAttributes.items = $event.target.value.split('\n'); $wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" rows="4" placeholder="Enter list items, one per line"></textarea>
                            </div>
                        @elseif($block['type'] === 'alert')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alert Type</label>
                                    <select x-model="editAttributes.type" @change="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                        <option value="info">Info</option>
                                        <option value="warning">Warning</option>
                                        <option value="danger">Danger</option>
                                        <option value="success">Success</option>
                                    </select>
                                </div>
                                <input type="text" x-model="editAttributes.title" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="Title">
                                <textarea x-model="editAttributes.content" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="Alert message"></textarea>
                            </div>
                        @elseif($block['type'] === 'accordion')
                            <div class="space-y-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" x-model="editAttributes.multiple_open" @change="$wire.updateBlock({{ $index }}, editAttributes)" class="rounded border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Allow multiple open</span>
                                </label>
                                <div class="text-sm text-gray-500">Accordion items managed per item.</div>
                            </div>
@elseif($block['type'] === 'table')
                            <div class="space-y-4">
                                <input type="text" x-model="editAttributes.caption" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="Table caption">
                                <p class="text-xs text-gray-500">Table data managed via structured interface.</p>
                            </div>
                        @elseif($block['type'] === 'form')
                            <div class="space-y-4">
                                <input type="text" x-model="editAttributes.submit_text" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="Submit Button Text">
                                <input type="text" x-model="editAttributes.action_url" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="Form Action URL">
                            </div>
                        @elseif($block['type'] === 'divider')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Style</label>
                                    <select x-model="editAttributes.style" @change="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                        <option value="line">Line</option>
                                        <option value="dashed">Dashed</option>
                                        <option value="dotted">Dotted</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Size</label>
                                    <select x-model="editAttributes.size" @change="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                        <option value="sm">Small</option>
                                        <option value="md">Medium</option>
                                        <option value="lg">Large</option>
                                    </select>
                                </div>
                            </div>
                        @elseif($block['type'] === 'html')
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">HTML Content</label>
                                <textarea x-model="editAttributes.content" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm font-mono outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" rows="6" placeholder="<div class='custom-html'>...</div>"></textarea>
                            </div>
                        @elseif($block['type'] === 'embed')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Embed URL</label>
                                    <input type="url" x-model="editAttributes.url" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="https://...">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                                    <input type="text" x-model="editAttributes.title" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                </div>
                            </div>
                        @elseif($block['type'] === 'columns')
                            <div class="space-y-2">
                                <p class="text-sm text-gray-500">Column management is handled in the visual layout editor.</p>
                            </div>
                        @elseif($block['type'] === 'tabs')
                            <div class="space-y-2">
                                <p class="text-sm text-gray-500">Tabs management is handled in the visual layout editor.</p>
                            </div>
                        @elseif($block['type'] === 'countdown')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Target Date</label>
                                    <input type="datetime-local" x-model="editAttributes.target_date" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Label</label>
                                    <input type="text" x-model="editAttributes.label" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="Countdown label">
                                </div>
                            </div>
@elseif($block['type'] === 'poll')
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Question</label>
            <input type="text" x-model="editAttributes.question" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="Question text">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Options</label>
            <div class="space-y-2">
                <template x-for="(option, optIndex) in editAttributes.options" :key="optIndex">
                    <div class="flex items-center gap-2">
                        <input type="text" x-model="editAttributes.options[optIndex]" @input.debounce.300ms="$wire.updateBlock({{ $index }}, editAttributes)" class="flex-1 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        <button type="button" @click="editAttributes.options.splice(optIndex, 1); $wire.updateBlock({{ $index }}, editAttributes)" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="if(!editAttributes.options) editAttributes.options = []; editAttributes.options.push('New Option'); $wire.updateBlock({{ $index }}, editAttributes)" class="text-sm text-gray-900 dark:text-white font-medium hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Option
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" x-model="editAttributes.allow_multiple" @change="$wire.updateBlock({{ $index }}, editAttributes)" class="rounded border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Allow multiple answers</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" x-model="editAttributes.show_results" @change="$wire.updateBlock({{ $index }}, editAttributes)" class="rounded border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Show results to voters</span>
            </label>
        </div>

        @php
            $pollId = $block['attributes']['poll_id'] ?? null;
            $poll = $pollId ? $preloadedPolls->get($pollId) : null;
            $results = $poll ? $poll->results : [];
            $voteCounts = $poll ? $poll->vote_counts : [];
        @endphp

        @if(!empty($results))
            <div class="mt-6 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/10">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Current Results</h4>
                <div class="space-y-3">
                    @foreach($block['attributes']['options'] as $optIdx => $optLabel)
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs font-medium">
                                <span class="text-gray-700 dark:text-gray-300">{{ $optLabel }}</span>
                                <span class="text-gray-900 dark:text-white">{{ $results[$optIdx] ?? 0 }}% ({{ $voteCounts[$optIdx] ?? 0 }} votes)</span>
                            </div>
                            <div class="h-1.5 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-gray-900 dark:bg-white" style="width: {{ $results[$optIdx] ?? 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Add Block Button -->
    <div class="relative" x-data="{ open: false }">
        <button 
            type="button"
            @click="open = !open"
            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl text-gray-900 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Block
        </button>

        <div 
            x-show="open" 
            @click.outside="open = false"
            x-cloak
            x-transition
            class="absolute z-10 w-full mt-2 bg-white dark:bg-[#16161D] border border-gray-200 dark:border-white/10 rounded-2xl shadow-xl overflow-hidden"
        >
            <div class="p-2 max-h-64 overflow-y-auto">
                @foreach($availableBlockTypes as $type => $label)
                    <button 
                        type="button"
                        wire:click="addBlock('{{ $type }}')"
                        @click="open = false"
                        class="w-full text-left px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 text-gray-900 dark:text-white text-sm transition-colors"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
