<x-layouts::admin title="Media Library">
    <div x-data="{ open: {{ request()->has('open') ? 'true' : 'false' }}, activeTab: 'browse' }" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                    <div class="space-y-8 p-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Media Library') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Upload or select an existing image') }}</p>
                        </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Media Library') }}</h3>
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
                        @fragment('image-grid')
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="{{ __('Search images...') }}" 
                            hx-get="{{ route('admin.media-library.index') }}" 
                            hx-trigger="keyup changed delay:300ms" 
                            hx-target="#image-grid"
                            hx-indicator="#search-loading"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                        <div id="search-loading" class="hidden">Searching...</div>
                        
                        <div id="image-grid" class="grid grid-cols-2 md:grid-cols-4 gap-6 max-h-[450px] overflow-y-auto p-2">
                            @foreach($images as $image)
                                <button 
                                    type="button"
                                    @click="window.dispatchEvent(new CustomEvent('image-selected', { detail: { url: '{{ $image->image_url }}' }})); open = false"
                                    class="group relative aspect-square rounded-xl overflow-hidden border-2 border-gray-200 dark:border-gray-800 hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/25 transition-all active:scale-95"
                                >
                                    <img src="{{ $image->image_url }}" alt="{{ $image->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="bg-indigo-600 text-white rounded-full p-2 transform scale-0 group-hover:scale-100 transition-transform duration-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 p-2 text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        {{ \Illuminate\Support\Str::limit($image->title, 20) }}
                                    </div>
                                </button>
                            @endforeach
                        </div>
                        
                        <div class="flex justify-center pt-4">
                            {{ $images->links() }}
                        </div>
                        @endfragment
                    </div>

                    <div x-show="activeTab === 'upload'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6 pt-2">
                        <input type="hidden" name="image_path" id="media-image-path" value="">
                        <div id="media-image-progress" class="mb-3"></div>
                        
                        <form 
                            hx-post="{{ route('admin.media-library.upload') }}" 
                            hx-encoding="multipart/form-data"
                            hx-target="#upload-result"
                            hx-indicator="#upload-loading"
                            class="space-y-6"
                        >
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
                                <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Upload') }}
                                </button>
                            </div>
                        </form>
                        <div id="upload-loading" class="hidden">Uploading...</div>
                        <div id="upload-result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/image-upload.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof ImageUploader !== 'undefined') {
            new ImageUploader({
                fileInput: document.getElementById('media-upload-input'),
                previewContainer: document.querySelector('[x-show="activeTab === \'upload\'"]'),
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
