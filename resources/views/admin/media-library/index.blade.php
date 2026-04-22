<x-layouts::admin title="Media Library">
    <div x-data="{ activeTab: 'browse' }" class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Media Library') }}</h1>
            <p class="text-sm text-gray-500">{{ __('Upload or select an existing image') }}</p>
        </div>

        <div class="flex gap-2 p-1.5 bg-gray-100 dark:bg-gray-800 rounded-xl w-fit">
            <button type="button"
                @click="activeTab = 'browse'"
                :class="activeTab === 'browse' ? 'bg-white dark:bg-gray-700 shadow-md text-gray-900 dark:text-white scale-105' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:scale-105'"
                class="px-5 py-2 text-sm font-medium rounded-lg transition-all flex items-center gap-2 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ __('Browse') }}
            </button>
            <button type="button"
                @click="activeTab = 'upload'"
                :class="activeTab === 'upload' ? 'bg-white dark:bg-gray-700 shadow-md text-gray-900 dark:text-white scale-105' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:scale-105'"
                class="px-5 py-2 text-sm font-medium rounded-lg transition-all flex items-center gap-2 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                {{ __('Upload') }}
            </button>
        </div>

        <div x-show="activeTab === 'browse'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6 pt-2">
            @include('admin.media-library.partials.image-grid')
        </div>

        <div x-show="activeTab === 'upload'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6 pt-2">
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
                
                <div class="p-10 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-2xl text-center hover:border-indigo-400 dark:hover:border-indigo-600 transition-colors">
                    <label class="cursor-pointer group">
                        <div class="mb-4">
                            <svg class="w-12 h-12 mx-auto text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500">{{ __('Click to upload or drag and drop') }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ __('PNG, JPG, GIF up to 5MB') }}</p>
                        <input type="file" name="upload" id="media-upload-input" class="hidden" accept="image/*" required>
                    </label>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">{{ __('Title') }}</label>
                        <input type="text" name="title" id="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">{{ __('Category') }}</label>
                        <select name="category" id="category" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="automotive">Automotive</option>
                            <option value="heavy_machinery">Heavy Machinery</option>
                            <option value="fleet">Fleet</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Upload') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::admin>

<script src="{{ asset('js/image-upload.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
