<x-layouts::admin title="About Us Content">
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#FAFAFA] font-headline">About Us</h1>
                <p class="text-[#A1A1AA] mt-1 text-sm">Manage your About Us page content.</p>
            </div>
        </div>

        <form action="{{ route('admin.about-us.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="aboutUsForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-[#16161d] rounded-2xl border border-white/5 p-6 space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-[#FAFAFA]">Title</label>
                            <input type="text" name="title" value="{{ old('title', $content->title) }}" required class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                            @error('title')
                                <p class="text-sm text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-[#FAFAFA]">Subtitle</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle', $content->subtitle) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                            @error('subtitle')
                                <p class="text-sm text-[#DC2626]">{{ $message }}</p>
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
                            <label class="text-sm font-medium text-[#FAFAFA]">Body</label>
                            <textarea name="body" id="body-editor" rows="8" required>{{ old('body', $content->body) }}</textarea>
                            @error('body')
                                <p class="text-sm text-[#DC2626]">{{ $message }}</p>
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
                            <label class="text-sm font-medium text-[#FAFAFA]">Mission</label>
                            <textarea name="mission" id="mission-editor" rows="3">{{ old('mission', $content->mission) }}</textarea>
                            @error('mission')
                                <p class="text-sm text-[#DC2626]">{{ $message }}</p>
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
                            <label class="text-sm font-medium text-[#FAFAFA]">Vision</label>
                            <textarea name="vision" id="vision-editor" rows="3">{{ old('vision', $content->vision) }}</textarea>
                            @error('vision')
                                <p class="text-sm text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-[#16161d] rounded-2xl border border-white/5 p-6 space-y-6">
                        <h3 class="font-bold text-[#FAFAFA]">Status</h3>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $content->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-white/20 bg-black/30 text-[#DC2626] focus:ring-[#DC2626]">
                            <span class="text-sm text-[#A1A1AA]">Active (visible on site)</span>
                        </div>

                        <div class="pt-4 border-t border-white/5 space-y-2">
                            <label class="text-sm font-medium text-[#FAFAFA]">Hero Image</label>
                            <input type="hidden" name="hero_image_path" id="hero-image-path" value="{{ $content->hero_image ?? '' }}">
                            
                            <div id="hero-image-preview" class="mb-3">
                                @if($content->hero_image)
                                    <div class="relative w-full aspect-video rounded-xl overflow-hidden border border-white/5">
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
                            
                            <div class="flex flex-col items-center justify-center p-6 bg-black/20 border-2 border-dashed border-white/5 rounded-xl group hover:border-[#DC2626]/50 transition-all cursor-pointer relative overflow-hidden">
                                <svg class="w-8 h-8 text-[#71717A] mb-2 group-hover:text-[#DC2626] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-xs text-[#71717A]">Click to upload</p>
                                <input type="file" name="hero_image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                            <p class="text-[10px] text-[#71717A]">Recommended: 1920x600, max 2MB</p>
                        </div>

                        <div class="pt-4 border-t border-white/5">
                            <button type="submit" class="w-full py-4 bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold rounded-xl shadow-lg shadow-[#DC2626]/20 transition-all active:scale-[0.98]">
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
            border-color: rgba(255, 255, 255, 0.05) !important;
            border-radius: 12px !important;
        }
        .cke_top {
            background-color: #16161d !important;
            border-bottom-color: rgba(255, 255, 255, 0.05) !important;
            border-radius: 12px 12px 0 0 !important;
        }
        .cke_bottom {
            background-color: #16161d !important;
            border-top-color: rgba(255, 255, 255, 0.05) !important;
            border-radius: 0 0 12px 12px !important;
        }
        .cke_toolgroup {
            background-color: #0A0A0F !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
            border-radius: 6px !important;
        }
        .cke_button {
            color: #A1A1AA !important;
        }
        .cke_button:hover {
            color: #FAFAFA !important;
        }
        .cke_button_on {
            background-color: #DC2626 !important;
            color: #FAFAFA !important;
        }
        .cke_combo {
            background-color: #0A0A0F !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }
        .cke_combo_text {
            color: #A1A1AA !important;
        }
        .cke_combo_arrow {
            color: #A1A1AA !important;
        }
        .cke_contents {
            background-color: #0A0A0F !important;
            border-radius: 0 0 12px 12px !important;
        }
        .cke_editable {
            color: #FAFAFA !important;
            padding: 15px !important;
            padding-right: 25px !important;
        }
        .cke_editable a {
            color: #DC2626 !important;
        }
        .cke_path_item {
            color: #A1A1AA !important;
        }
        .cke_path_item:hover {
            color: #FAFAFA !important;
        }
        .cke_wysiwyg_frame {
            background-color: #0A0A0F !important;
        }
        /* Custom scrollbar styling */
        .cke_wysiwyg_frame::-webkit-scrollbar {
            width: 8px;
        }
        .cke_wysiwyg_frame::-webkit-scrollbar-track {
            background: #0A0A0F;
        }
        .cke_wysiwyg_frame::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 4px;
        }
        .cke_wysiwyg_frame::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</x-layouts::admin>
