<x-layouts::admin title="Create Gallery Item">
    <div class="p-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-zinc-500 mb-4">
                <a href="{{ route('admin.gallery.index') }}" class="hover:text-zinc-700 transition-colors">Gallery</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-zinc-400">Create</span>
            </nav>
            <h1 class="text-4xl font-bold text-[#39393c] tracking-tight">Create Gallery Item</h1>
            <p class="mt-2 text-zinc-500">Add a new masterpiece to your portfolio</p>
        </div>

        <form method="POST" action="{{ route('admin.gallery.store') }}" class="max-w-3xl" enctype="multipart/form-data">
            @csrf

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
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-zinc-700 mb-2">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Gallery item title">
                        @error('title')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">Image Selection</label>
                        <div 
                            class="relative group transition-all duration-300"
                            :class="isDragging ? 'scale-[0.99]' : ''"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="isDragging = false; $refs.imageInput.files = $event.dataTransfer.files; handleFileSelect({target: $refs.imageInput})"
                        >
                            <div 
                                class="relative border-2 border-dashed rounded-[2rem] p-12 transition-all duration-300 flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden"
                                :class="isDragging ? 'border-[#dc2626] bg-[#dc2626]/5' : 'border-zinc-200 hover:border-zinc-300 bg-zinc-50/50'"
                                @click="$refs.imageInput.click()"
                            >
                                <template x-if="!imagePreview">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-[#dc2626]/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-10 h-10 text-[#dc2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-zinc-900 mb-2">Visual Assessment</h3>
                                        <p class="text-[#dc2626] font-semibold mb-1">Click to upload or drag and drop</p>
                                        <p class="text-zinc-500 text-sm">PNG, JPG, WEBP up to 10MB</p>
                                    </div>
                                </template>

                                <template x-if="imagePreview">
                                    <div class="relative w-full aspect-video rounded-2xl overflow-hidden border border-zinc-200 shadow-sm">
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
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gallery_category_id" class="block text-sm font-semibold text-zinc-700 mb-2">Category</label>
                        <select name="gallery_category_id" id="gallery_category_id" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('gallery_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('gallery_category_id')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-zinc-700 mb-2">Description <span class="font-normal text-zinc-400">(Optional)</span></label>
                        <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200 resize-none" placeholder="Description of the work done">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location Information -->
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-semibold text-zinc-700 mb-2">Latitude <span class="font-normal text-zinc-400">(Optional)</span></label>
                            <input type="number" name="latitude" id="latitude" step="any" value="{{ old('latitude') }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="-24.6532">
                            @error('latitude')
                                <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-semibold text-zinc-700 mb-2">Longitude <span class="font-normal text-zinc-400">(Optional)</span></label>
                            <input type="number" name="longitude" id="longitude" step="any" value="{{ old('longitude') }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="25.9087">
                            @error('longitude')
                                <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="location_address" class="block text-sm font-semibold text-zinc-700 mb-2">Location Address <span class="font-normal text-zinc-400">(Optional)</span></label>
                        <input type="text" name="location_address" id="location_address" value="{{ old('location_address') }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="123 Main St, Gaborone, Botswana">
                        <p class="mt-2 text-sm text-zinc-500">Address will be displayed on public gallery with Google Maps link</p>
                        @error('location_address')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Settings -->
                <div class="space-y-6">
                    <div>
                        <label for="sort_order" class="block text-sm font-semibold text-zinc-700 mb-2">Sort Order <span class="font-normal text-zinc-400">(Optional)</span></label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Lower numbers appear first">
                        @error('sort_order')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-zinc-50 rounded-xl border border-zinc-200">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-5 w-5 text-[#dc2626] focus:ring-[#dc2626] focus:ring-offset-0 border-zinc-300 rounded transition-all duration-200 cursor-pointer">
                        <label for="is_featured" class="text-sm font-medium text-zinc-700 cursor-pointer select-none">Featured</label>
                        <span class="text-xs text-zinc-500 ml-auto">Highlighted on public gallery</span>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-zinc-50 rounded-xl border border-zinc-200">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-5 w-5 text-[#dc2626] focus:ring-[#dc2626] focus:ring-offset-0 border-zinc-300 rounded transition-all duration-200 cursor-pointer">
                        <label for="is_active" class="text-sm font-medium text-zinc-700 cursor-pointer select-none">Active</label>
                        <span class="text-xs text-zinc-500 ml-auto">Visible on public site</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-200">
                    <a href="{{ route('admin.gallery.index') }}" class="px-6 py-3 rounded-xl border border-zinc-200 text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:border-zinc-300 transition-all duration-200 active:scale-[0.98]">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-[#dc2626] text-white text-sm font-medium shadow-lg shadow-[#dc2626]/20 hover:bg-[#b91c1c] hover:shadow-xl hover:shadow-[#dc2626]/30 transition-all duration-200 active:scale-[0.98]">
                        Create Gallery Item
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
