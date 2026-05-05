<x-layouts::admin title="About Us Content">
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-admin-text font-headline">About Us</h1>
                <p class="text-admin-text-muted mt-1 text-sm">Manage your About Us page content.</p>
            </div>
        </div>

        <form action="{{ route('admin.about-us.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="aboutUsForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6 space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-admin-text">Title</label>
                            <input type="text" name="title" value="{{ old('title', $content->title) }}" required class="w-full admin-form-input">
                            @error('title')
                                <p class="text-sm text-admin-accent">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-admin-text">Subtitle</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle', $content->subtitle) }}" class="w-full admin-form-input">
                            @error('subtitle')
                                <p class="text-sm text-admin-accent">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2" x-data="{
                            editorId: 'body-editor',
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
                                        document.querySelector('textarea[name=body]').value = this.editorInstance.getData();
                                    });
                                    this.editorInstance.on('key', () => {
                                        document.querySelector('textarea[name=body]').value = this.editorInstance.getData();
                                    });
                                });
                            }
                        }" x-init="initEditor()">
                            <label class="text-sm font-medium text-admin-text">Body</label>
                            <textarea name="body" id="body-editor" rows="8" required>{{ old('body', $content->body) }}</textarea>
                            @error('body')
                                <p class="text-sm text-admin-accent">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2" x-data="{
                            editorId: 'mission-editor',
                            editorInstance: null,
                            initEditor() {
                                this.$nextTick(() => {
                                    if (CKEDITOR.instances[this.editorId]) {
                                        CKEDITOR.instances[this.editorId].destroy(true);
                                    }
                                    this.editorInstance = CKEDITOR.replace(this.editorId, {
                                        customConfig: '/vendor/ckeditor/config.js',
                                        height: 150,
                                    });
                                    this.editorInstance.on('change', () => {
                                        document.querySelector('textarea[name=mission]').value = this.editorInstance.getData();
                                    });
                                    this.editorInstance.on('key', () => {
                                        document.querySelector('textarea[name=mission]').value = this.editorInstance.getData();
                                    });
                                });
                            }
                        }" x-init="initEditor()">
                            <label class="text-sm font-medium text-admin-text">Mission</label>
                            <textarea name="mission" id="mission-editor" rows="3">{{ old('mission', $content->mission) }}</textarea>
                            @error('mission')
                                <p class="text-sm text-admin-accent">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2" x-data="{
                            editorId: 'vision-editor',
                            editorInstance: null,
                            initEditor() {
                                this.$nextTick(() => {
                                    if (CKEDITOR.instances[this.editorId]) {
                                        CKEDITOR.instances[this.editorId].destroy(true);
                                    }
                                    this.editorInstance = CKEDITOR.replace(this.editorId, {
                                        customConfig: '/vendor/ckeditor/config.js',
                                        height: 150,
                                    });
                                    this.editorInstance.on('change', () => {
                                        document.querySelector('textarea[name=vision]').value = this.editorInstance.getData();
                                    });
                                    this.editorInstance.on('key', () => {
                                        document.querySelector('textarea[name=vision]').value = this.editorInstance.getData();
                                    });
                                });
                            }
                        }" x-init="initEditor()">
                            <label class="text-sm font-medium text-admin-text">Vision</label>
                            <textarea name="vision" id="vision-editor" rows="3">{{ old('vision', $content->vision) }}</textarea>
                            @error('vision')
                                <p class="text-sm text-admin-accent">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6 space-y-6">
                        <h3 class="font-bold text-admin-text">Status</h3>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $content->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-admin-border bg-admin-surface-alt text-admin-accent focus:ring-admin-accent">
                            <span class="text-sm text-admin-text-muted">Active (visible on site)</span>
                        </div>

                        <div class="pt-4 border-t border-admin-border-subtle space-y-2">
                            <label class="text-sm font-medium text-admin-text">Hero Image</label>
                            <input type="hidden" name="hero_image_path" id="hero-image-path" value="{{ $content->hero_image ?? '' }}">
                            
                            <div id="hero-image-preview" class="mb-3">
                                @if($content->hero_image)
                                    <div class="relative w-full aspect-video rounded-xl overflow-hidden border border-admin-border-subtle">
                                        <img src="{{ Storage::url($content->hero_image) }}" alt="Hero" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm">
                                            <div class="flex flex-col items-center text-white">
                                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                                <span class="font-semibold text-sm">Change Image</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div id="hero-image-progress"></div>
                            
                            <div class="flex flex-col items-center justify-center p-6 bg-admin-surface-alt border-2 border-dashed border-admin-border-subtle rounded-xl group hover:border-admin-accent/50 transition-all cursor-pointer relative overflow-hidden">
                                <svg class="w-8 h-8 text-admin-text-muted mb-2 group-hover:text-admin-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-xs text-admin-text-muted">Click to upload</p>
                                <input type="file" name="hero_image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                            <p class="text-[10px] text-admin-text-muted">Recommended: 1920x600, max 2MB</p>
                        </div>

                        <div class="pt-4 border-t border-admin-border-subtle">
                            <button type="submit" class="w-full py-4 bg-admin-accent hover:bg-admin-accent/90 text-white font-bold rounded-xl shadow-lg shadow-admin-accent/20 transition-all active:scale-[0.98]">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/image-upload.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Image Uploader
            if (typeof ImageUploader !== 'undefined') {
                new ImageUploader({
                    fileInput: document.querySelector('input[name="hero_image"]'),
                    previewContainer: document.getElementById('hero-image-preview'),
                    progressContainer: document.getElementById('hero-image-progress'),
                    hiddenInput: document.getElementById('hero-image-path'),
                    uploadUrl: '{{ route("admin.image-upload") }}',
                    csrfToken: '{{ csrf_token() }}',
                    maxSize: 2 * 1024 * 1024, // 2MB
                    acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
                    onUploadComplete: function(response) {
                        console.log('Image uploaded successfully:', response);
                    },
                    onUploadError: function(message) {
                        console.error('Upload error:', message);
                    }
                });
            }
        });
    </script>
    <style>
        .cke_chrome {
            border-color: var(--color-admin-border-subtle) !important;
            border-radius: 12px !important;
        }
        .cke_top {
            background-color: var(--color-admin-surface) !important;
            border-bottom-color: var(--color-admin-border-subtle) !important;
            border-radius: 12px 12px 0 0 !important;
        }
        .cke_bottom {
            background-color: var(--color-admin-surface) !important;
            border-top-color: var(--color-admin-border-subtle) !important;
            border-radius: 0 0 12px 12px !important;
        }
        .cke_toolgroup {
            background-color: var(--color-admin-bg) !important;
            border-color: var(--color-admin-border-subtle) !important;
            border-radius: 6px !important;
        }
        .cke_button {
            color: var(--color-admin-text-muted) !important;
        }
        .cke_button:hover {
            color: var(--color-admin-text) !important;
        }
        .cke_button_on {
            background-color: var(--color-admin-accent) !important;
            color: var(--color-admin-text) !important;
        }
        .cke_combo {
            background-color: var(--color-admin-bg) !important;
            border-color: var(--color-admin-border-subtle) !important;
        }
        .cke_combo_text {
            color: var(--color-admin-text-muted) !important;
        }
        .cke_combo_arrow {
            color: var(--color-admin-text-muted) !important;
        }
        .cke_contents {
            background-color: var(--color-admin-bg) !important;
            border-radius: 0 0 12px 12px !important;
        }
        .cke_editable {
            color: var(--color-admin-text) !important;
            padding: 15px !important;
            padding-right: 25px !important;
        }
        .cke_editable a {
            color: var(--color-admin-accent) !important;
        }
        .cke_path_item {
            color: var(--color-admin-text-muted) !important;
        }
        .cke_path_item:hover {
            color: var(--color-admin-text) !important;
        }
        .cke_wysiwyg_frame {
            background-color: var(--color-admin-bg) !important;
        }
        /* Custom scrollbar styling */
        .cke_wysiwyg_frame::-webkit-scrollbar {
            width: 8px;
        }
        .cke_wysiwyg_frame::-webkit-scrollbar-track {
            background: var(--color-admin-bg);
        }
        .cke_wysiwyg_frame::-webkit-scrollbar-thumb {
            background: var(--color-admin-border);
            border-radius: 4px;
        }
        .cke_wysiwyg_frame::-webkit-scrollbar-thumb:hover {
            background: var(--color-admin-text-muted);
        }
    </style>
</x-layouts::admin>
