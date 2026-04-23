<x-layouts::admin title="Create Gallery Item">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Create Gallery Item</h1>
            <a href="{{ route('admin.gallery.index') }}" class="admin-action-btn admin-action-btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.gallery.store') }}" class="max-w-3xl" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="image_path" id="image-path" value="">

            <div class="space-y-8" x-data="{ 
                imagePreview: null,
                isDragging: false,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.imagePreview = URL.createObjectURL(file);
                    }
                }
            }">
                <!-- Basic Information -->
                <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-admin-text mb-4">Basic Information</h2>
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-admin-text-muted mb-2">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full admin-form-input" placeholder="Gallery item title">
                        @error('title')
                            <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-admin-text-muted mb-2">Image Selection</label>
                        <div id="image-progress" class="mb-3"></div>
                        <div 
                            class="relative group transition-all duration-300"
                            :class="isDragging ? 'scale-[0.99]' : ''"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="isDragging = false; $refs.imageInput.files = $event.dataTransfer.files; handleFileSelect({target: $refs.imageInput})"
                        >
                            <div 
                                class="relative border-2 border-dashed rounded-2xl p-12 transition-all duration-300 flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden"
                                :class="isDragging ? 'border-[#DC2626] bg-[#DC2626]/5' : 'border-admin-border hover:border-admin-border-subtle bg-admin-surface-alt'"
                                @click="$refs.imageInput.click()"
                            >
                                <template x-if="!imagePreview">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-10 h-10 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-admin-text mb-2">Visual Assessment</h3>
                                        <p class="text-[#DC2626] font-semibold mb-1">Click to upload or drag and drop</p>
                                        <p class="text-admin-text-muted text-sm">PNG, JPG, WEBP up to 10MB</p>
                                    </div>
                                </template>

                                <template x-if="imagePreview">
                                    <div class="relative w-full aspect-video rounded-xl overflow-hidden border border-admin-border">
                                        <img :src="imagePreview" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm">
                                            <div class="flex flex-col items-center text-white">
                                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                                <span class="font-semibold text-sm">Change Image</span>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <input 
                                    type="file" 
                                    name="image" 
                                    x-ref="imageInput" 
                                    class="hidden" 
                                    accept="image/*"
                                    @change="handleFileSelect"
                                >
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gallery_category_id" class="block text-sm font-medium text-admin-text-muted mb-2">Category</label>
                        <select name="gallery_category_id" id="gallery_category_id" required class="w-full admin-form-input">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('gallery_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('gallery_category_id')
                            <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-admin-text-muted mb-2">Description <span class="text-admin-text-muted">(Optional)</span></label>
                        <textarea name="description" id="description" rows="3" class="w-full admin-form-input resize-none" placeholder="Description of the work done">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location Information -->
                <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-admin-text mb-4">Location Information</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-admin-text-muted mb-2">Latitude <span class="text-admin-text-muted">(Optional)</span></label>
                            <input type="number" name="latitude" id="latitude" step="any" value="{{ old('latitude') }}" class="w-full admin-form-input" placeholder="-24.6532">
                            @error('latitude')
                                <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-medium text-admin-text-muted mb-2">Longitude <span class="text-admin-text-muted">(Optional)</span></label>
                            <input type="number" name="longitude" id="longitude" step="any" value="{{ old('longitude') }}" class="w-full admin-form-input" placeholder="25.9087">
                            @error('longitude')
                                <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="location_address" class="block text-sm font-medium text-admin-text-muted mb-2">Location Address <span class="text-admin-text-muted">(Optional)</span></label>
                        <input type="text" name="location_address" id="location_address" value="{{ old('location_address') }}" class="w-full admin-form-input" placeholder="123 Main St, Gaborone, Botswana">
                        <p class="mt-1 text-sm text-admin-text-muted">Address will be displayed on public gallery with Google Maps link</p>
                        @error('location_address')
                            <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Settings -->
                <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-admin-text mb-4">Settings</h2>
                    
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-admin-text-muted mb-2">Sort Order <span class="text-admin-text-muted">(Optional)</span></label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full admin-form-input" placeholder="Lower numbers appear first">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-admin-surface-alt rounded-xl border border-admin-border">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-5 w-5 bg-admin-input-bg border-admin-input-border rounded focus:ring-2 focus:ring-[#DC2626] cursor-pointer">
                        <label for="is_featured" class="text-sm font-medium text-admin-text cursor-pointer select-none">Featured</label>
                        <span class="text-xs text-admin-text-muted ml-auto">Highlighted on public gallery</span>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-admin-surface-alt rounded-xl border border-admin-border">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-5 w-5 bg-admin-input-bg border-admin-input-border rounded focus:ring-2 focus:ring-[#DC2626] cursor-pointer">
                        <label for="is_active" class="text-sm font-medium text-admin-text cursor-pointer select-none">Active</label>
                        <span class="text-xs text-admin-text-muted ml-auto">Visible on public site</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.gallery.index') }}" class="admin-action-btn admin-action-btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Create Gallery Item
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/image-upload.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof ImageUploader !== 'undefined') {
                new ImageUploader({
                    fileInput: document.querySelector('input[name="image"]'),
                    previewContainer: document.querySelector('[x-data]'),
                    progressContainer: document.getElementById('image-progress'),
                    hiddenInput: document.getElementById('image-path'),
                    uploadUrl: '{{ route("admin.image-upload") }}',
                    csrfToken: '{{ csrf_token() }}',
                    maxSize: 10 * 1024 * 1024, // 10MB
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
</x-layouts::admin>
