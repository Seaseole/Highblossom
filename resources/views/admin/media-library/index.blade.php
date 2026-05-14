<x-layouts::admin title="Media Library">
    <div x-data="mediaLibrary()" class="space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="font-headline text-3xl font-bold text-admin-text tracking-tight">{{ __('Media Library') }}</h1>
            <p class="text-admin-text-muted text-sm mt-2">{{ __('Upload or select an existing image') }}</p>
        </div>

        {{-- Tabs --}}
        <div class="flex gap-2 p-1.5 bg-admin-surface-alt rounded-xl w-fit">
            <button type="button"
                @click="activeTab = 'browse'"
                :class="activeTab === 'browse' ? 'bg-admin-surface shadow-md text-admin-text scale-105' : 'text-admin-text-muted hover:text-admin-text hover:scale-105'"
                class="px-5 py-2 text-sm font-medium rounded-lg transition-all flex items-center gap-2 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ __('Browse') }}
            </button>
            <button type="button"
                @click="activeTab = 'upload'"
                :class="activeTab === 'upload' ? 'bg-admin-surface shadow-md text-admin-text scale-105' : 'text-admin-text-muted hover:text-admin-text hover:scale-105'"
                class="px-5 py-2 text-sm font-medium rounded-lg transition-all flex items-center gap-2 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                {{ __('Upload') }}
            </button>
        </div>

        {{-- Browse Tab --}}
        <div x-show="activeTab === 'browse'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6 pt-2">
            @include('admin.media-library.partials.image-grid')
        </div>

        {{-- Upload Tab --}}
        <div x-show="activeTab === 'upload'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6 pt-2">
            {{-- Form content remains same but wrapped in activeTab check --}}
            <form
                action="{{ route('admin.media-library.upload') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf
                
                <input type="hidden" name="image_path" id="media-image-path">
                
                <div id="media-image-preview" class="mb-4"></div>
                <div id="media-image-progress"></div>
                
                <div class="p-10 border-2 border-dashed border-admin-border rounded-2xl text-center hover:border-admin-accent/50 transition-colors">
                    <label class="cursor-pointer group">
                        <div class="mb-4">
                            <svg class="w-12 h-12 mx-auto text-admin-text-muted group-hover:text-admin-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="text-sm text-admin-text-muted">{{ __('Click to upload or drag and drop') }}</p>
                        <p class="text-xs text-admin-text-muted mt-2">{{ __('PNG, JPG, WebP up to 5MB') }}</p>
                        <input type="file" name="upload" id="media-upload-input" class="hidden" accept="image/*" required>
                    </label>
                </div>

                <div class="admin-glass-card rounded-3xl shadow-black/20 p-6 space-y-4">
                    <div>
                        <label for="title" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">{{ __('Title') }}</label>
                        <input type="text" name="title" id="title" required class="admin-form-input w-full">
                    </div>

                    <div>
                        <label for="category" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">{{ __('Category') }}</label>
                        <select name="category" id="category" required class="admin-form-input w-full">
                            <option value="automotive">Automotive</option>
                            <option value="heavy_machinery">Heavy Machinery</option>
                            <option value="fleet">Fleet</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        {{ __('Upload') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Preview Modal --}}
        <div x-show="showModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             @keydown.escape.window="showModal = false">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     @click="showModal = false"
                     class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     class="relative bg-admin-surface rounded-2xl shadow-2xl max-w-4xl w-full overflow-hidden flex flex-col md:flex-row">
                    
                    {{-- Left side: Image --}}
                    <div class="md:w-3/5 bg-admin-surface-alt flex items-center justify-center p-4">
                        <img :src="selectedImage.url" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-sm">
                    </div>

                    {{-- Right side: Details --}}
                    <div class="md:w-2/5 p-6 flex flex-col border-l border-admin-border">
                        <div class="flex justify-between items-start mb-6">
                            <h2 class="text-xl font-bold text-admin-text" x-text="selectedImage.title"></h2>
                            <button @click="showModal = false" class="text-admin-text-muted hover:text-admin-text transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="flex-1 space-y-4 overflow-y-auto pr-2">
                            <template x-if="selectedImage.metadata">
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-widest">{{ __('File Info') }}</label>
                                        <div class="mt-2 space-y-1 text-sm">
                                            <p class="text-admin-text-muted flex justify-between">
                                                <span>{{ __('Original Name') }}:</span>
                                                <span class="text-admin-text font-medium text-right break-all ml-4" x-text="selectedImage.metadata.original_name"></span>
                                            </p>
                                            <p class="text-admin-text-muted flex justify-between">
                                                <span>{{ __('Size') }}:</span>
                                                <span class="text-admin-text font-medium" x-text="selectedImage.metadata.file_size"></span>
                                            </p>
                                            <p class="text-admin-text-muted flex justify-between">
                                                <span>{{ __('Uploaded') }}:</span>
                                                <span class="text-admin-text font-medium" x-text="selectedImage.metadata.created_at"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-widest">{{ __('Usage') }}</label>
                                        <div class="mt-2">
                                            <p class="text-admin-text-muted text-sm">
                                                {{ __('Used in') }} <span class="text-admin-text font-bold" x-text="selectedImage.metadata.usage_count"></span> {{ __('place(s)') }}.
                                            </p>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <template x-for="usage in selectedImage.metadata.usages">
                                                    <span class="px-2 py-1 bg-admin-surface-alt border border-admin-border rounded text-[10px] font-medium text-admin-text-muted" x-text="`${usage.model} #${usage.id}`"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="pt-6 border-t border-admin-border mt-auto flex gap-3">
                            <button @click="selectImage()" class="flex-1 admin-action-btn admin-action-btn-primary justify-center">
                                {{ __('Select') }}
                            </button>
                            <button @click="deleteImage()" 
                                    class="p-2.5 rounded-xl border border-red-500/30 text-red-500 hover:bg-red-500 hover:text-white transition-all active:scale-95"
                                    :title="'{{ __('Delete from Storage') }}'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::admin>

<script src="{{ asset('js/image-upload.js') }}"></script>
<script>
    function mediaLibrary() {
        return {
            activeTab: 'browse',
            showModal: false,
            selectedImage: {},

            init() {
                window.addEventListener('open-image-preview', (e) => {
                    this.fetchImageDetails(e.detail.id);
                });
            },

            async fetchImageDetails(id) {
                try {
                    const response = await fetch(`/admin/media-library/${id}`);
                    this.selectedImage = await response.json();
                    this.showModal = true;
                } catch (error) {
                    console.error('Error fetching image details:', error);
                }
            },

            selectImage() {
                window.dispatchEvent(new CustomEvent('image-selected', { 
                    detail: { url: this.selectedImage.url } 
                }));
                this.showModal = false;
            },

            async deleteImage() {
                if (!confirm('{{ __("Are you sure you want to delete this image? If it is used elsewhere, the file will remain until all references are removed.") }}')) {
                    return;
                }

                try {
                    const response = await fetch(`/admin/media-library/${this.selectedImage.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        this.showModal = false;
                        // Reload the grid
                        window.location.reload();
                    } else {
                        alert(result.message || 'Error deleting image');
                    }
                } catch (error) {
                    console.error('Error deleting image:', error);
                    alert('Error deleting image');
                }
            }
        };
    }

    (function() {
        const initMediaLibrary = function() {
            if (typeof ImageUploader !== 'undefined') {
                new ImageUploader({
                    fileInput: document.querySelector('input[name="upload"]'),
                    previewContainer: document.getElementById('media-image-preview'),
                    progressContainer: document.getElementById('media-image-progress'),
                    hiddenInput: document.getElementById('media-image-path'),
                    uploadUrl: '{{ route("admin.image-upload") }}',
                    csrfToken: '{{ csrf_token() }}',
                    maxSize: 5 * 1024 * 1024, // 5MB
                    acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'],
                    onUploadComplete: function(response) {
                        console.log('Image uploaded successfully:', response);
                        window.location.reload();
                    },
                    onUploadError: function(message) {
                        console.error('Upload error:', message);
                    }
                });
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMediaLibrary);
        } else {
            initMediaLibrary();
        }
    })();
</script>

<script src="{{ asset('js/image-upload.js') }}"></script>
<script>
    (function() {
        const initMediaLibrary = function() {
            if (typeof ImageUploader !== 'undefined') {
                new ImageUploader({
                    fileInput: document.querySelector('input[name="upload"]'),
                    previewContainer: document.getElementById('media-image-preview'),
                    progressContainer: document.getElementById('media-image-progress'),
                    hiddenInput: document.getElementById('media-image-path'),
                    uploadUrl: '{{ route("admin.image-upload") }}',
                    csrfToken: '{{ csrf_token() }}',
                    maxSize: 5 * 1024 * 1024, // 5MB
                    acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'],
                    onUploadComplete: function(response) {
                        console.log('Image uploaded successfully:', response);
                    },
                    onUploadError: function(message) {
                        console.error('Upload error:', message);
                    }
                });
            }
        };

        // Execute initialization
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMediaLibrary);
        } else {
            initMediaLibrary();
        }
    })();
</script>
