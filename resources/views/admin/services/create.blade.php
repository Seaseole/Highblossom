<x-layouts::admin title="Create Service">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Create Service</h1>
        </div>

        <form method="POST" action="{{ route('admin-services.store') }}" class="max-w-2xl" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-[#A1A1AA] mb-2">Title</label>
                    <input type="text" name="title" id="title" required class="admin-form-input @error('title') border-red-500 @enderror" placeholder="Service title">
                    @error('title')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="short_description" class="block text-sm font-medium text-[#A1A1AA] mb-2">Short Description</label>
                    <textarea name="short_description" id="short_description" rows="3" required class="admin-form-input resize-none @error('short_description') border-red-500 @enderror" placeholder="Brief description for listings"></textarea>
                    @error('short_description')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="full_description" class="block text-sm font-medium text-[#A1A1AA] mb-2">Full Description (Optional)</label>
                    <textarea name="full_description" id="full_description" rows="5" class="admin-form-input resize-none @error('full_description') border-red-500 @enderror" placeholder="Detailed description for service page"></textarea>
                    @error('full_description')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="features" class="block text-sm font-medium text-[#A1A1AA] mb-2">Features (Optional)</label>
                    <textarea name="features" id="features" rows="4" class="admin-form-input resize-none @error('features') border-red-500 @enderror" placeholder="Enter each feature on a new line"></textarea>
                    <p class="mt-1 text-sm text-[#71717A]">Enter each feature on a new line</p>
                    @error('features')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-[#A1A1AA] mb-2">Image Upload (Optional)</label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="admin-form-input @error('image') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-[#71717A]">Upload an image (JPEG, PNG, JPG, WebP - Max 2MB)</p>
                    @error('image')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image_url" class="block text-sm font-medium text-[#A1A1AA] mb-2">Or Image URL (Optional)</label>
                    <input type="url" name="image_url" id="image_url" class="admin-form-input @error('image_url') border-red-500 @enderror" placeholder="https://example.com/image.jpg">
                    <p class="mt-1 text-sm text-[#71717A]">Use an external URL instead of uploading</p>
                    @error('image_url')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium text-[#A1A1AA] mb-2">Sort Order (Optional)</label>
                    <input type="number" name="sort_order" id="sort_order" class="admin-form-input @error('sort_order') border-red-500 @enderror" placeholder="Lower numbers appear first">
                    @error('sort_order')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 text-[#DC2626] focus:ring-[#DC2626] border-white/20 rounded bg-white/5">
                    <label for="is_active" class="ml-2 block text-sm text-[#FAFAFA]">Active</label>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin-services.index') }}" class="admin-action-btn admin-action-btn-ghost">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Create Service
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
