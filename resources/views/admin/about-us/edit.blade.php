<x-layouts::admin title="About Us Content">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">About Us</h1>
                <p class="text-gray-500 dark:text-gray-400">Manage your About Us page content.</p>
            </div>
        </div>

        <form action="{{ route('admin.about-us.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="aboutUsForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                            <input type="text" name="title" value="{{ old('title', $content->title) }}" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                            @error('title')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Subtitle</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle', $content->subtitle) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                            @error('subtitle')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2" x-data="{
                            editorId: 'body-editor',
                            initEditor() {
                                this.$nextTick(() => {
                                    if (CKEDITOR.instances[this.editorId]) CKEDITOR.instances[this.editorId].destroy(true);
                                    const instance = CKEDITOR.replace(this.editorId, { height: 200 });
                                    instance.on('change', () => { document.querySelector('textarea[name=body]').value = instance.getData(); });
                                });
                            }
                        }" x-init="initEditor()">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Body</label>
                            <textarea name="body" id="body-editor" rows="8" required>{{ old('body', $content->body) }}</textarea>
                            @error('body')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2" x-data="{
                            editorId: 'mission-editor',
                            initEditor() {
                                this.$nextTick(() => {
                                    if (CKEDITOR.instances[this.editorId]) CKEDITOR.instances[this.editorId].destroy(true);
                                    const instance = CKEDITOR.replace(this.editorId, { height: 150 });
                                    instance.on('change', () => { document.querySelector('textarea[name=mission]').value = instance.getData(); });
                                });
                            }
                        }" x-init="initEditor()">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mission</label>
                            <textarea name="mission" id="mission-editor" rows="3">{{ old('mission', $content->mission) }}</textarea>
                            @error('mission')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2" x-data="{
                            editorId: 'vision-editor',
                            initEditor() {
                                this.$nextTick(() => {
                                    if (CKEDITOR.instances[this.editorId]) CKEDITOR.instances[this.editorId].destroy(true);
                                    const instance = CKEDITOR.replace(this.editorId, { height: 150 });
                                    instance.on('change', () => { document.querySelector('textarea[name=vision]').value = instance.getData(); });
                                });
                            }
                        }" x-init="initEditor()">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Vision</label>
                            <textarea name="vision" id="vision-editor" rows="3">{{ old('vision', $content->vision) }}</textarea>
                            @error('vision')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Status</h3>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $content->is_active) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Active (visible on site)</span>
                        </label>

                        <div class="pt-4 border-t border-gray-100 dark:border-white/10 space-y-4">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Hero Image</label>
                            <input type="hidden" name="hero_image_path" id="hero-image-path" value="{{ $content->hero_image ?? '' }}">
                            <input type="hidden" name="remove_hero_image" id="remove-hero-image" value="0">
                            
                            <div id="hero-image-preview" class="space-y-4">
                                @if($content->hero_image)
                                    <div class="relative w-full aspect-video rounded-2xl overflow-hidden border border-gray-200 dark:border-white/10">
                                        <img src="{{ Storage::url($content->hero_image) }}" alt="Hero" class="w-full h-full object-cover">
                                    </div>
                                @endif
                            </div>
                            
                            <div id="hero-image-progress"></div>
                            
                            <div class="relative w-full bg-gray-50 dark:bg-white/5 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl flex flex-col items-center justify-center p-6 cursor-pointer hover:border-gray-900 dark:hover:border-white transition-all">
                                <span class="text-xs text-gray-500">Click to upload image</span>
                                <input type="file" name="hero_image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                            
                            @if($content->hero_image)
                                <button type="button" id="remove-hero-image-btn" class="w-full text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">
                                    Remove Image
                                </button>
                            @endif
                            <p class="text-[10px] text-gray-500 dark:text-gray-400">Recommended: 1920x600, max 2MB</p>
                        </div>

                        <div class="pt-4 border-t border-gray-100 dark:border-white/10">
                            <button type="submit" class="w-full bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
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
        (function() {
            const initAboutUs = function() {
                function attachRemoveButtonHandler() {
                    const removeHeroImageBtn = document.getElementById('remove-hero-image-btn');
                    if (removeHeroImageBtn) {
                        removeHeroImageBtn.onclick = function(e) {
                            e.stopPropagation();
                            e.preventDefault();
                            if (!confirm('Are you sure you want to remove the hero image?')) return;
                            const removeInput = document.getElementById('remove-hero-image');
                            const pathInput = document.getElementById('hero-image-path');
                            if (removeInput) removeInput.value = '1';
                            if (pathInput) pathInput.value = '';
                            const preview = document.getElementById('hero-image-preview');
                            if (preview) preview.innerHTML = '';
                            const progress = document.getElementById('hero-image-progress');
                            if (progress) progress.innerHTML = '';
                            removeHeroImageBtn.remove();
                            const form = document.getElementById('aboutUsForm');
                            if (form) form.submit();
                        };
                    }
                }
                attachRemoveButtonHandler();
                if (typeof ImageUploader !== 'undefined') {
                    new ImageUploader({
                        fileInput: document.querySelector('input[name="hero_image"]'),
                        previewContainer: document.getElementById('hero-image-preview'),
                        progressContainer: document.getElementById('hero-image-progress'),
                        hiddenInput: document.getElementById('hero-image-path'),
                        uploadUrl: '{{ route("admin.image-upload") }}',
                        csrfToken: '{{ csrf_token() }}',
                        maxSize: 2 * 1024 * 1024,
                        acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
                        onUploadComplete: function(response) {
                            const removeInput = document.getElementById('remove-hero-image');
                            if (removeInput) removeInput.value = '0';
                            if (!document.getElementById('remove-hero-image-btn')) {
                                const fileInput = document.querySelector('input[name="hero_image"]');
                                if (fileInput) {
                                    const uploadDiv = fileInput.closest('div');
                                    const newBtn = document.createElement('button');
                                    newBtn.type = 'button';
                                    newBtn.id = 'remove-hero-image-btn';
                                    newBtn.className = 'w-full text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors';
                                    newBtn.innerHTML = 'Remove Image';
                                    uploadDiv.insertAdjacentElement('afterend', newBtn);
                                    attachRemoveButtonHandler();
                                }
                            }
                        }
                    });
                }
            };
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initAboutUs);
            } else {
                initAboutUs();
            }
        })();
    </script>
    <style>
        .cke_chrome { border-color: #e5e7eb !important; border-radius: 12px !important; }
        .dark .cke_chrome { border-color: rgba(255, 255, 255, 0.1) !important; }
        .cke_top { background-color: #f9fafb !important; border-bottom-color: #e5e7eb !important; border-radius: 12px 12px 0 0 !important; }
        .dark .cke_top { background-color: rgba(255, 255, 255, 0.05) !important; border-bottom-color: rgba(255, 255, 255, 0.1) !important; }
        .cke_contents { background-color: #ffffff !important; border-radius: 0 0 12px 12px !important; }
        .dark .cke_contents { background-color: #0A0A0F !important; }
        .cke_editable { color: #111827 !important; padding: 15px !important; }
        .dark .cke_editable { color: #f9fafb !important; }
    </style>
</x-layouts::admin>