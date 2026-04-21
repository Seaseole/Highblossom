<x-layouts::admin title="Edit Service">
    <div class="p-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-zinc-500 mb-4">
                <a href="{{ route('admin.services.index') }}" class="hover:text-zinc-700 transition-colors">Services</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-zinc-400">Edit</span>
            </nav>
            <h1 class="text-4xl font-bold text-[#39393c] tracking-tight">Edit Service</h1>
            <p class="mt-2 text-zinc-500">Update service details and manage content</p>
        </div>

        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="max-w-3xl" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-zinc-700 mb-2">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $service->title) }}" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Service title">
                        @error('title')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="short_description" class="block text-sm font-semibold text-zinc-700 mb-2">Short Description</label>
                        <textarea name="short_description" id="short_description" rows="3" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200 resize-none" placeholder="Brief description for listings">{{ old('short_description', $service->short_description) }}</textarea>
                        @error('short_description')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="full_description" class="block text-sm font-semibold text-zinc-700 mb-2">Full Description <span class="font-normal text-zinc-400">(Optional)</span></label>
                        <textarea name="full_description" id="full_description" rows="5" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200 resize-none" placeholder="Detailed description for service page">{{ old('full_description', $service->full_description) }}</textarea>
                        @error('full_description')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="features" class="block text-sm font-semibold text-zinc-700 mb-2">Features <span class="font-normal text-zinc-400">(Optional)</span></label>
                        <textarea name="features" id="features" rows="4" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200 resize-none" placeholder="Enter each feature on a new line">{{ old('features', is_array($service->features) ? implode("\n", $service->features) : '') }}</textarea>
                        <p class="mt-2 text-sm text-zinc-500">Enter each feature on a new line</p>
                        @error('features')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Image Section -->
                <div class="space-y-6">
                    @if ($service->image)
                        <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-200">
                            <label class="block text-sm font-semibold text-zinc-700 mb-3">Current Image</label>
                            <div class="flex items-start gap-4">
                                <img src="{{ $service->image }}" alt="{{ $service->title }}" class="h-32 w-32 object-cover rounded-lg border border-zinc-200 shadow-sm">
                                <div class="flex-1">
                                    <p class="text-sm text-zinc-600 mb-2">Image is currently being displayed on the public site.</p>
                                    <p class="text-xs text-zinc-500">Upload a new image to replace it, or use an external URL.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="image" class="block text-sm font-semibold text-zinc-700 mb-2">Upload New Image <span class="font-normal text-zinc-400">(Optional)</span></label>
                        <div class="relative">
                            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full px-4 py-3 rounded-xl border-2 border-dashed border-zinc-300 bg-white text-zinc-900 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200 cursor-pointer hover:border-zinc-400">
                            <p class="mt-2 text-sm text-zinc-500">JPEG, PNG, JPG, or WebP (Max 2MB)</p>
                        </div>
                        @error('image')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-zinc-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-4 bg-zinc-50 text-sm text-zinc-500">or use external URL</span>
                        </div>
                    </div>

                    <div>
                        <label for="image_url" class="block text-sm font-semibold text-zinc-700 mb-2">Image URL <span class="font-normal text-zinc-400">(Optional)</span></label>
                        <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $service->image_url) }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="https://example.com/image.jpg">
                        <p class="mt-2 text-sm text-zinc-500">Use an external URL instead of uploading</p>
                        @error('image_url')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Settings -->
                <div class="space-y-6">
                    <div>
                        <label for="sort_order" class="block text-sm font-semibold text-zinc-700 mb-2">Sort Order <span class="font-normal text-zinc-400">(Optional)</span></label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $service->sort_order) }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Lower numbers appear first">
                        @error('sort_order')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-zinc-50 rounded-xl border border-zinc-200">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }} class="h-5 w-5 text-[#dc2626] focus:ring-[#dc2626] focus:ring-offset-0 border-zinc-300 rounded transition-all duration-200 cursor-pointer">
                        <label for="is_active" class="text-sm font-medium text-zinc-700 cursor-pointer select-none">Active</label>
                        <span class="text-xs text-zinc-500 ml-auto">Visible on public site</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-200">
                    <a href="{{ route('admin.services.index') }}" class="px-6 py-3 rounded-xl border border-zinc-200 text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:border-zinc-300 transition-all duration-200 active:scale-[0.98]">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-[#dc2626] text-white text-sm font-medium shadow-lg shadow-[#dc2626]/20 hover:bg-[#b91c1c] hover:shadow-xl hover:shadow-[#dc2626]/30 transition-all duration-200 active:scale-[0.98]">
                        Update Service
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
