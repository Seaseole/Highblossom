<div class="space-y-4" wire:ignore.self wire:key="block-builder-{{ $name }}" x-data="blockBuilder({
    initialBlocks: {{ Js::from($blocks ?? []) }},
    availableBlockTypes: {{ Js::from($availableBlockTypes ?? []) }}
})">
    <!-- Blocks List -->
    <div class="space-y-3">
        <template x-if="blocks.length === 0">
            <div class="text-center py-8 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl">
                <p class="text-gray-500 dark:text-gray-400">No content blocks yet. Add your first block below.</p>
            </div>
        </template>
        
        <template x-for="(block, index) in blocks" :key="block.id">
            <div class="bg-white dark:bg-[#16161D] border border-gray-200 dark:border-white/10 rounded-2xl p-4 group relative">
                <!-- Block Header -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-gray-100 uppercase tracking-wider" x-text="availableBlockTypes[block.type] || block.type"></span>
                        <span class="text-xs text-gray-400 dark:text-gray-500" x-text="'#' + (index + 1)"></span>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" @click="removeBlock(index)" class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Edit Panel -->
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-white/10">
                    <template x-if="block.type === 'paragraph'">
                        <div x-data="{
                            editorId: 'paragraph-editor-' + block.id,
                            initEditor() {
                                this.$nextTick(() => {
                                    if (CKEDITOR.instances[this.editorId]) CKEDITOR.instances[this.editorId].destroy(true);
                                    const instance = CKEDITOR.replace(this.editorId, { height: 200 });
                                    instance.on('change', () => { document.getElementById(this.editorId).value = instance.getData(); });
                                });
                            }
                        }" x-init="initEditor()">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content</label>
                            <textarea :id="editorId" x-model="block.attributes.content" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white"></textarea>
                        </div>
                    </template>

                    <template x-if="block.type === 'heading'">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Heading Text</label>
                                <input type="text" x-model="block.attributes.content" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Level</label>
                                <select x-model="block.attributes.level" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                    <option value="h1">H1</option><option value="h2">H2</option><option value="h3">H3</option><option value="h4">H4</option>
                                </select>
                            </div>
                        </div>
                    </template>

                    <template x-if="block.type === 'quote'">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quote</label>
                                <textarea x-model="block.attributes.content" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" rows="3"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Author</label><input type="text" x-model="block.attributes.author" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white"></div>
                                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Citation</label><input type="text" x-model="block.attributes.cite" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white"></div>
                            </div>
                        </div>
                    </template>

                    <template x-if="block.type === 'cta'">
                        <div class="space-y-4">
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label><input type="text" x-model="block.attributes.title" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white"></div>
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label><textarea x-model="block.attributes.description" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" rows="2"></textarea></div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button Text</label><input type="text" x-model="block.attributes.button_text" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white"></div>
                                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button URL</label><input type="text" x-model="block.attributes.button_url" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white"></div>
                            </div>
                        </div>
                    </template>

                    <template x-if="block.type === 'video'">
                        <div class="space-y-4" x-data="{ 
                            videoMode: block.attributes.src ? (block.attributes.src.startsWith('temp://') || block.attributes.src.startsWith('http') ? 'url' : 'upload') : 'url',
                            previewData: null,
                            async detectVideo() {
                                if (!block.attributes.src) { this.previewData = null; return; }
                                this.previewData = await $wire.detectVideoUrl(block.attributes.src);
                            }
                        }" x-init="detectVideo()">
                            <div class="flex gap-4 mb-2">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" x-model="videoMode" value="url" class="rounded-full"><span class="text-sm">External URL</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" x-model="videoMode" value="upload" class="rounded-full"><span class="text-sm">Upload File</span></label>
                            </div>
                            <div x-show="videoMode === 'url'">
                                <input type="url" x-model="block.attributes.src" @input.debounce="detectVideo()" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="https://youtube.com/...">
                            </div>
                            <div x-show="videoMode === 'upload'">
                                <input type="file" @change="$wire.startVideoUpload(index).then(() => $wire.upload('videoUpload', $event.target.files[0]))" accept="video/mp4,video/webm" class="w-full text-sm">
                            </div>
                            
                            <!-- Preview -->
                            <div x-show="previewData && previewData.valid" class="mt-4">
                                <template x-if="previewData && previewData.uses_iframe">
                                    <div class="aspect-video w-full">
                                        <iframe :src="previewData.embed_url" class="w-full h-full rounded-lg" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </template>
                                <template x-if="previewData && !previewData.uses_iframe">
                                    <video :src="block.attributes.src" controls class="w-full rounded-lg"></video>
                                </template>
                                <p class="text-xs text-green-500 mt-2" x-text="'Source detected: ' + (previewData ? previewData.source_label : '')"></p>
                            </div>
                            <div x-show="previewData && !previewData.valid" class="mt-2 p-2 bg-red-50 dark:bg-red-900/20 text-red-500 rounded-lg text-xs" x-text="previewData.error"></div>
                        </div>
                    </template>

                    <template x-if="block.type === 'image'">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Image URL / Upload</label>
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="block.attributes.src" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                    <button type="button" @click="$refs.imgInput.click()" class="px-4 py-2 bg-gray-100 dark:bg-white/5 rounded-xl text-sm font-medium">Upload</button>
                                    <input type="file" x-ref="imgInput" class="hidden" accept="image/*" @change="$wire.upload('imageUpload', $event.target.files[0], () => $wire.uploadImageForBlock(index))">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-sm font-medium">Alt Text</label><input type="text" x-model="block.attributes.alt" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm"></div>
                                <div><label class="block text-sm font-medium">Caption</label><input type="text" x-model="block.attributes.caption" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm"></div>
                            </div>
                        </div>
                    </template>

                    <template x-if="block.type === 'gallery'"><div class="space-y-4"><div><label class="block text-sm font-medium">Columns</label><input type="number" x-model="block.attributes.columns" min="1" max="6" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm"></div><p class="text-xs text-gray-500">Gallery images managed in Media Library.</p></div></template>
                    
                    <template x-if="block.type === 'list'">
                        <div class="space-y-4">
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2"><input type="radio" x-model="block.attributes.type" value="ul"><span class="text-sm">Unordered</span></label>
                                <label class="flex items-center gap-2"><input type="radio" x-model="block.attributes.type" value="ol"><span class="text-sm">Ordered</span></label>
                            </div>
                            <textarea x-model="block.attributes.itemsRaw" @input="block.attributes.items = $event.target.value.split('\n')" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" rows="4" placeholder="Items (one per line)"></textarea>
                        </div>
                    </template>
                    
                    <template x-if="block.type === 'alert'">
                        <div class="space-y-4">
                            <select x-model="block.attributes.type" class="w-full bg-gray-50 dark:bg-white/5 border rounded-xl px-4 py-2.5 text-sm"><option value="info">Info</option><option value="warning">Warning</option><option value="danger">Danger</option><option value="success">Success</option></select>
                            <input type="text" x-model="block.attributes.title" class="w-full bg-gray-50 border rounded-xl px-4 py-2.5 text-sm" placeholder="Title">
                            <textarea x-model="block.attributes.content" class="w-full bg-gray-50 border rounded-xl px-4 py-2.5 text-sm" placeholder="Message"></textarea>
                        </div>
                    </template>

                    <template x-if="block.type === 'html'"><div><textarea x-model="block.attributes.content" class="w-full font-mono bg-gray-50 border rounded-xl px-4 py-2.5 text-sm" rows="6"></textarea></div></template>
                    <template x-if="block.type === 'embed'"><div class="space-y-4"><input type="url" x-model="block.attributes.url" class="w-full border rounded-xl px-4 py-2.5" placeholder="URL"><input type="text" x-model="block.attributes.title" class="w-full border rounded-xl px-4 py-2.5" placeholder="Title"></div></template>
                    <template x-if="block.type === 'divider'">
                        <div class="space-y-4">
                            <select x-model="block.attributes.style" class="w-full border rounded-xl px-4 py-2.5 text-sm"><option value="line">Line</option><option value="dashed">Dashed</option><option value="dotted">Dotted</option></select>
                            <select x-model="block.attributes.size" class="w-full border rounded-xl px-4 py-2.5 text-sm"><option value="sm">Small</option><option value="md">Medium</option><option value="lg">Large</option></select>
                        </div>
                    </template>
                    
                    <template x-if="block.type === 'countdown'"><div class="space-y-4"><input type="datetime-local" x-model="block.attributes.target_date" class="w-full border rounded-xl px-4 py-2.5"><input type="text" x-model="block.attributes.label" class="w-full border rounded-xl px-4 py-2.5" placeholder="Label"></div></template>
                    
                    <template x-if="block.type === 'poll'">
                        <div class="space-y-4">
                            <input type="text" x-model="block.attributes.question" class="w-full border rounded-xl px-4 py-2.5 text-sm" placeholder="Question">
                            <div class="space-y-2">
                                <template x-if="Array.isArray(block.attributes.options)">
                                    <template x-for="(opt, optIdx) in block.attributes.options" :key="optIdx">
                                        <div class="flex items-center gap-2">
                                            <input type="text" x-model="block.attributes.options[optIdx]" class="flex-1 border rounded-xl px-4 py-2 text-sm">
                                            <button type="button" @click="block.attributes.options.splice(optIdx, 1)" class="p-2 text-red-500">X</button>
                                        </div>
                                    </template>
                                </template>
                                <button type="button" @click="if(!Array.isArray(block.attributes.options)) block.attributes.options = []; block.attributes.options.push('New Option')" class="text-sm font-medium underline">Add Option</button>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label><input type="checkbox" x-model="block.attributes.allow_multiple"> Allow multiple</label>
                                <label><input type="checkbox" x-model="block.attributes.show_results"> Show results</label>
                            </div>
                        </div>
                    </template>
                    
                    <template x-if="['columns', 'tabs', 'accordion'].includes(block.type)">
                        <p class="text-sm text-gray-500">Complex layouts are managed in visual editor.</p>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <!-- Add Block Button -->
    <div class="relative" x-data="{ open: false }">
        <button type="button" @click="open = !open" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl font-medium transition-colors">
            Add Block
        </button>
        <div x-show="open" @click.outside="open = false" x-cloak class="absolute z-10 w-full mt-2 bg-white dark:bg-[#16161D] border border-gray-200 dark:border-white/10 rounded-2xl shadow-xl overflow-hidden">
            <div class="p-2 max-h-64 overflow-y-auto">
                <template x-for="(label, type) in availableBlockTypes" :key="type">
                    <button type="button" @click="addBlock(type); open = false" class="w-full text-left px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 text-sm transition-colors text-gray-700 dark:text-gray-300" x-text="label"></button>
                </template>
                <div x-show="Object.keys(availableBlockTypes).length === 0" class="p-4 text-center text-xs text-gray-500">
                    No blocks registered in registry.
                </div>
            </div>
        </div>
    </div>
</div>
