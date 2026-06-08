<x-layouts::admin title="Media Library">
    <div class="max-w-7xl mx-auto space-y-8 py-10" x-data="mediaLibrary()">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Media Library</h1>
            <p class="text-gray-500 dark:text-gray-400">Upload or select existing media assets.</p>
        </div>

        {{-- Tabs --}}
        <div class="flex gap-2 p-1.5 bg-gray-100 dark:bg-white/5 rounded-full w-max">
            <button type="button"
                @click="activeTab = 'browse'"
                :class="activeTab === 'browse' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                class="px-6 py-2 text-sm font-medium rounded-full transition-all">
                Browse
            </button>
            <button type="button"
                @click="activeTab = 'upload'"
                :class="activeTab === 'upload' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                class="px-6 py-2 text-sm font-medium rounded-full transition-all">
                Upload
            </button>
        </div>

        {{-- Browse Tab --}}
        <div x-show="activeTab === 'browse'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6 pt-2">
            @include('admin.media-library.partials.image-grid')
        </div>

        {{-- Upload Tab --}}
        <div x-show="activeTab === 'upload'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6 pt-2" style="display: none;">
            <form action="{{ route('admin.media-library.upload') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                @csrf
                <input type="hidden" name="image_path" id="media-image-path">
                
                <div id="media-image-preview"></div>
                <div id="media-image-progress"></div>
                
                <div class="relative w-full bg-gray-50 dark:bg-white/5 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl flex flex-col items-center justify-center min-h-[200px] cursor-pointer hover:border-gray-900 dark:hover:border-white transition-all">
                    <label class="cursor-pointer text-center p-6 text-gray-500 dark:text-gray-400">
                        <span class="text-xs font-semibold">Click to upload image</span>
                        <input type="file" name="upload" id="media-upload-input" class="hidden" accept="image/*" required>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                        <input type="text" name="title" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                        <select name="category" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                            <option value="automotive">Automotive</option>
                            <option value="heavy_machinery">Heavy Machinery</option>
                            <option value="fleet">Fleet</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                    Upload Media
                </button>
            </form>
        </div>

        {{-- Preview Modal --}}
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
            <div x-show="showModal" class="relative bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 max-w-4xl w-full shadow-2xl flex flex-col md:flex-row gap-8">
                <div class="md:w-3/5 bg-gray-50 dark:bg-white/5 rounded-2xl flex items-center justify-center p-4">
                    <img :src="selectedImage.url" class="max-w-full max-h-[50vh] object-contain rounded-lg">
                </div>
                <div class="md:w-2/5 flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4" x-text="selectedImage.title"></h2>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase">File Info</dt>
                                <dd class="text-sm text-gray-700 dark:text-gray-300" x-text="selectedImage.metadata?.file_size"></dd>
                            </div>
                        </dl>
                    </div>
                    <div class="flex gap-4 pt-6">
                        <button @click="selectImage()" class="flex-1 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-medium py-2 rounded-full text-sm">Select</button>
                        <button @click="deleteImage()" class="px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full text-sm">Delete</button>
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
                } catch (error) { console.error(error); }
            },
            selectImage() {
                window.dispatchEvent(new CustomEvent('image-selected', { detail: { url: this.selectedImage.url } }));
                this.showModal = false;
            },
            async deleteImage() {
                if (!confirm('Are you sure?')) return;
                try {
                    await fetch(`/admin/media-library/${this.selectedImage.id}`, { method: 'DELETE', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'} });
                    window.location.reload();
                } catch (error) { console.error(error); }
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