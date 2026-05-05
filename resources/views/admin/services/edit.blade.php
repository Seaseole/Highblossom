<x-layouts::admin title="Edit Service">
    <div class="p-8 max-w-5xl mx-auto space-y-10">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3 text-admin-text-muted mb-2">
                    <a href="{{ route('admin.services.index') }}" class="hover:text-admin-accent transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] font-body">Services / Edit</span>
                </div>
                <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline leading-none">
                    Edit Service
                </h1>
                <p class="text-admin-text-muted text-sm max-w-lg">
                    Modify the architectural service details, technical specs, and imagery.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="image_path" id="image-path" value="{{ $service->image ?? '' }}">
            <input type="hidden" name="remove_image" id="remove-image" value="0">

            {{-- Main Form Area --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="admin-glass-card p-8 rounded-3xl shadow-2xl shadow-black/20 space-y-8">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label for="title" class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $service->title) }}" required class="admin-form-input font-headline text-lg font-bold tracking-tight" placeholder="e.g. Structural Glass Balustrades">
                                @error('title')
                                    <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="short_description" class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Summary</label>
                                <textarea name="short_description" id="short_description" rows="2" required class="admin-form-input font-body text-sm leading-relaxed resize-none" placeholder="A high-level summary for service cards...">{{ old('short_description', $service->short_description) }}</textarea>
                                @error('short_description')
                                    <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="full_description" class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Detailed Description</label>
                                <textarea name="full_description" id="full_description" rows="6" class="admin-form-input font-body text-sm leading-relaxed resize-none" placeholder="Full technical details and service scope...">{{ old('full_description', $service->full_description) }}</textarea>
                                @error('full_description')
                                    <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4 border-t border-admin-border-subtle">
                            <div class="space-y-2">
                                <label for="features" class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Technical Features</label>
                                <textarea name="features" id="features" rows="4" class="admin-form-input font-mono text-xs leading-relaxed resize-none bg-black/10" placeholder="• Feature one&#10;• Feature two&#10;• Feature three">{{ old('features', is_array($service->features) ? implode("\n", $service->features) : '') }}</textarea>
                                <p class="text-[10px] text-admin-text-muted font-body tracking-wider mt-2 opacity-60">Enter each feature on a new line for better indexing.</p>
                                @error('features')
                                    <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Area --}}
            <div class="space-y-6">
                <div class="admin-glass-card p-6 rounded-3xl shadow-xl shadow-black/10 space-y-6">
                    <div class="space-y-4">
                        <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Media Assets</label>
                        
                        <div class="space-y-4">
                            <div id="image-preview-container" class="space-y-4">
                                @if ($service->image)
                                    <div class="relative group/current rounded-xl overflow-hidden border border-admin-border-subtle" id="current-service-image-container">
                                        <img src="{{ $service->image }}" alt="{{ $service->title }}" class="w-full h-32 object-cover grayscale group-hover/current:grayscale-0 transition-all duration-500" id="current-service-image">
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/current:opacity-100 transition-opacity">
                                            <span class="text-[10px] font-bold text-white uppercase tracking-widest">Current Image</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($service->image)
                                <button type="button" id="remove-image-btn" class="w-full py-2 px-4 text-xs font-medium text-admin-text-muted hover:text-admin-accent border border-admin-border-subtle hover:border-admin-accent rounded-lg transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Remove Image
                                </button>
                            @endif

                            <div id="image-progress" class="empty:hidden"></div>
                            
                            <div class="relative group/upload">
                                <div class="absolute -inset-0.5 bg-linear-to-r from-admin-accent/20 to-transparent rounded-xl blur opacity-0 group-hover/upload:opacity-100 transition duration-500"></div>
                                <div class="relative">
                                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="admin-form-input flex flex-col items-center justify-center py-8 border-dashed border-2 border-admin-border-subtle group-hover/upload:border-admin-accent/30 transition-colors">
                                        <svg class="w-8 h-8 text-admin-text-muted mb-2 group-hover/upload:text-admin-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-[10px] font-bold text-admin-text uppercase tracking-widest">Replace Image</span>
                                    </div>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                </div>
                                <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $service->image_url) }}" class="admin-form-input pl-10 text-xs font-mono" placeholder="External URL...">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-admin-border-subtle">
                        <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Configuration</label>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-2">
                                <label for="sort_order" class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest font-body ml-1">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $service->sort_order) }}" class="admin-form-input text-sm" placeholder="0">
                            </div>

                            <div class="p-4 bg-admin-surface-alt/30 rounded-xl border border-admin-border-subtle group hover:border-admin-accent/20 transition-colors">
                                <label class="ui-checkbox-wrapper flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="ui-checkbox-input" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                                    <div class="ui-checkbox-check">
                                        <svg viewBox="0 0 18 18">
                                            <path d="M1,9 L1,3.5 C1,2.11928813 2.11928813,1 3.5,1 L14.5,1 C15.8807119,1 17,2.11928813 17,3.5 L17,14.5 C17,15.8807119 15.8807119,17 14.5,17 L3.5,17 C2.11928813,17 1,15.8807119 1,14.5 L1,9 Z"></path>
                                            <polyline points="1 9 7 14 15 4"></polyline>
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-admin-text uppercase tracking-widest font-body">Visible</span>
                                        <span class="text-[10px] text-admin-text-muted font-body">Publicly accessible</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="admin-action-btn admin-action-btn-primary w-full justify-center py-4 shadow-lg shadow-admin-accent/20 focus:ring-admin-accent">
                        <span class="text-xs font-bold uppercase tracking-[0.2em]">Update Service</span>
                    </button>
                    <a href="{{ route('admin.services.index') }}" class="admin-action-btn admin-action-btn-ghost w-full justify-center py-3">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Discard Changes</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/image-upload.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Remove Image Handler
            const removeImageBtn = document.getElementById('remove-image-btn');
            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (!confirm('Are you sure you want to remove the service image?')) {
                        return;
                    }

                    // Set removal flag
                    document.getElementById('remove-image').value = '1';
                    document.getElementById('image-path').value = '';

                    // Clear preview container
                    document.getElementById('image-preview-container').innerHTML = '';

                    // Hide remove button
                    removeImageBtn.style.display = 'none';

                    // Clear progress
                    document.getElementById('image-progress').innerHTML = '';
                });
            }

            if (typeof ImageUploader !== 'undefined') {
                new ImageUploader({
                    fileInput: document.querySelector('input[name="image"]'),
                    previewContainer: document.getElementById('image-preview-container'),
                    progressContainer: document.getElementById('image-progress'),
                    hiddenInput: document.getElementById('image-path'),
                    uploadUrl: '{{ route("admin.image-upload") }}',
                    csrfToken: '{{ csrf_token() }}',
                    folder: 'services',
                    maxSize: 2 * 1024 * 1024, // 2MB
                    acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
                    onUploadComplete: function(response) {
                        // Reset removal flag if new image uploaded
                        document.getElementById('remove-image').value = '0';
                        // Show remove button after upload so user can remove the uploaded image
                        const removeBtn = document.getElementById('remove-image-btn');
                        if (removeBtn) {
                            removeBtn.style.display = 'flex';
                        }
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

