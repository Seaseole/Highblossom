<x-layouts::admin title="Create Service">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Create Service</h1>
            <a href="{{ route('admin-services.index') }}" class="admin-action-btn admin-action-btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin-services.store') }}" class="max-w-2xl" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="image_path" id="image-path" value="">

            <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-admin-text-muted mb-2">Title</label>
                    <input type="text" name="title" id="title" required class="w-full admin-form-input" placeholder="Service title">
                    @error('title')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="short_description" class="block text-sm font-medium text-admin-text-muted mb-2">Short Description</label>
                    <textarea name="short_description" id="short_description" rows="3" required class="w-full admin-form-input resize-none" placeholder="Brief description for listings">{{ old('short_description') }}</textarea>
                    @error('short_description')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="full_description" class="block text-sm font-medium text-admin-text-muted mb-2">Full Description <span class="text-admin-text-muted">(Optional)</span></label>
                    <textarea name="full_description" id="full_description" rows="5" class="w-full admin-form-input resize-none" placeholder="Detailed description for service page">{{ old('full_description') }}</textarea>
                    @error('full_description')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="features" class="block text-sm font-medium text-admin-text-muted mb-2">Features <span class="text-admin-text-muted">(Optional)</span></label>
                    <textarea name="features" id="features" rows="4" class="w-full admin-form-input resize-none" placeholder="Enter each feature on a new line">{{ old('features') }}</textarea>
                    <p class="mt-1 text-sm text-admin-text-muted">Enter each feature on a new line</p>
                    @error('features')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-admin-text-muted mb-2">Image Upload <span class="text-admin-text-muted">(Optional)</span></label>
                    <div id="image-progress" class="mb-3"></div>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full admin-form-input">
                    <p class="mt-1 text-sm text-admin-text-muted">Upload an image (JPEG, PNG, JPG, WebP - Max 2MB)</p>
                    @error('image')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image_url" class="block text-sm font-medium text-admin-text-muted mb-2">Or Image URL <span class="text-admin-text-muted">(Optional)</span></label>
                    <input type="url" name="image_url" id="image_url" class="w-full admin-form-input" placeholder="https://example.com/image.jpg">
                    <p class="mt-1 text-sm text-admin-text-muted">Use an external URL instead of uploading</p>
                    @error('image_url')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium text-admin-text-muted mb-2">Sort Order <span class="text-admin-text-muted">(Optional)</span></label>
                    <input type="number" name="sort_order" id="sort_order" class="w-full admin-form-input" placeholder="Lower numbers appear first">
                    @error('sort_order')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 p-4 bg-admin-surface-alt rounded-xl border border-admin-border">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="h-5 w-5 bg-admin-input-bg border-admin-input-border rounded focus:ring-2 focus:ring-[#DC2626] cursor-pointer">
                    <label for="is_active" class="text-sm font-medium text-admin-text cursor-pointer select-none">Active</label>
                    <span class="text-xs text-admin-text-muted ml-auto">Visible on public site</span>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin-services.index') }}" class="admin-action-btn admin-action-btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Create Service
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
                    previewContainer: document.querySelector('.space-y-6'),
                    progressContainer: document.getElementById('image-progress'),
                    hiddenInput: document.getElementById('image-path'),
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
</x-layouts::admin>
