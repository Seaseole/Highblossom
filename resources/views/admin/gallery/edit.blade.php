<x-layouts::admin title="Edit Gallery Item">
    <div class="p-8 max-w-6xl mx-auto space-y-10">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3 text-admin-text-muted mb-2">
                    <a href="{{ route('admin.gallery.index') }}" class="hover:text-admin-accent transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] font-body">Gallery / Edit</span>
                </div>
                <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline leading-none">
                    Edit Item
                </h1>
                <p class="text-admin-text-muted text-sm max-w-lg">
                    Modify the masterpiece details in the architectural showcase.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.gallery.update', $item) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8" enctype="multipart/form-data" x-data="{ 
            imagePreview: '{{ $item->image_url }}',
            isDragging: false,
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.imagePreview = URL.createObjectURL(file);
                    // Reset removal flag when new image selected
                    $refs.removeImageInput.value = '0';
                }
            },
            removeImage() {
                if (!confirm('Are you sure you want to remove the gallery image?')) {
                    return;
                }
                this.imagePreview = null;
                $refs.removeImageInput.value = '1';
                $refs.imagePathInput.value = '';
            }
        }">
            @csrf
            @method('PUT')
            <input type="hidden" name="image_path" id="image-path" x-ref="imagePathInput" value="{{ $item->image_path ?? '' }}">
            <input type="hidden" name="remove_image" x-ref="removeImageInput" value="0">

            {{-- Main Form Area --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="admin-glass-card p-8 rounded-3xl shadow-2xl shadow-black/20 space-y-8">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label for="title" class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Project Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $item->title) }}" required class="admin-form-input font-headline text-lg font-bold tracking-tight" placeholder="e.g. Modern Minimalist Residence">
                                @error('title')
                                    <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="gallery_category_id" class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Category</label>
                                <select name="gallery_category_id" id="gallery_category_id" required class="admin-form-input font-body text-sm">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('gallery_category_id', $item->gallery_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('gallery_category_id')
                                    <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="description" class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Project Description</label>
                                <textarea name="description" id="description" rows="4" class="admin-form-input font-body text-sm leading-relaxed resize-none" placeholder="Details about the installation, materials used, or architectural vision...">{{ old('description', $item->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4 border-t border-admin-border-subtle space-y-6">
                            <h3 class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Project Location</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="latitude" class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest font-body ml-1">Latitude</label>
                                    <input type="number" name="latitude" id="latitude" step="any" value="{{ old('latitude', $item->latitude) }}" class="admin-form-input font-mono text-xs" placeholder="-24.6532">
                                    @error('latitude')
                                        <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label for="longitude" class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest font-body ml-1">Longitude</label>
                                    <input type="number" name="longitude" id="longitude" step="any" value="{{ old('longitude', $item->longitude) }}" class="admin-form-input font-mono text-xs" placeholder="25.9087">
                                    @error('longitude')
                                        <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label for="location_address" class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest font-body ml-1">Physical Address</label>
                                <input type="text" name="location_address" id="location_address" value="{{ old('location_address', $item->location_address) }}" class="admin-form-input font-body text-sm" placeholder="123 Main St, Gaborone, Botswana">
                                @error('location_address')
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
                        <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Visual Assessment</label>
                        
                        <div class="space-y-4">
                            <div id="image-progress" class="empty:hidden"></div>
                            
                            <div 
                                class="relative group/upload transition-all duration-300"
                                :class="isDragging ? 'scale-[0.98]' : ''"
                                @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false"
                                @drop.prevent="isDragging = false; $refs.imageInput.files = $event.dataTransfer.files; handleFileSelect({target: $refs.imageInput})"
                            >
                                <div class="absolute -inset-0.5 bg-linear-to-r from-admin-accent/20 to-transparent rounded-2xl blur opacity-0 group-hover/upload:opacity-100 transition duration-500"></div>
                                <div 
                                    class="relative admin-form-input flex flex-col items-center justify-center min-h-[200px] border-dashed border-2 border-admin-border-subtle group-hover/upload:border-admin-accent/30 transition-all overflow-hidden cursor-pointer"
                                    :class="isDragging ? 'border-admin-accent/50 bg-admin-accent/5' : ''"
                                    @click="$refs.imageInput.click()"
                                >
                                    <template x-if="!imagePreview">
                                        <div class="flex flex-col items-center text-center p-6">
                                            <div class="w-12 h-12 rounded-xl bg-admin-accent/10 flex items-center justify-center mb-4 group-hover/upload:scale-110 transition-transform duration-500">
                                                <svg class="w-6 h-6 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <span class="text-[10px] font-bold text-admin-text uppercase tracking-widest mb-1">Click or Drag</span>
                                            <span class="text-[9px] text-admin-text-muted uppercase tracking-tight">PNG, JPG, WEBP (Max 10MB)</span>
                                        </div>
                                    </template>

                                    <template x-if="imagePreview">
                                        <div class="relative w-full h-full group/preview">
                                            <img :src="imagePreview" class="w-full h-full object-cover grayscale group-hover/preview:grayscale-0 transition-all duration-700">
                                            <div class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center gap-3 opacity-0 group-hover/preview:opacity-100 transition-opacity backdrop-blur-sm">
                                                <span class="text-[10px] font-bold text-white uppercase tracking-widest">Replace</span>
                                                <button type="button" @click.prevent="removeImage()" class="px-3 py-1.5 text-[10px] font-bold text-white bg-admin-accent/80 hover:bg-admin-accent rounded transition-colors uppercase tracking-widest flex items-center gap-1.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Remove
                                                </button>
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
                                <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-admin-border-subtle">
                        <label class="text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body ml-1">Configuration</label>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-2">
                                <label for="sort_order" class="text-[10px] font-medium text-admin-text-muted/60 uppercase tracking-widest font-body ml-1">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $item->sort_order) }}" class="admin-form-input text-sm" placeholder="0">
                                @error('sort_order')
                                    <p class="mt-1 text-[10px] font-bold text-admin-accent uppercase tracking-wider">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-4 bg-admin-surface-alt/30 rounded-xl border border-admin-border-subtle group hover:border-admin-accent/20 transition-colors">
                                <label class="ui-checkbox-wrapper flex items-center gap-3 cursor-pointer">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" id="is_featured" value="1" class="ui-checkbox-input" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }}>
                                    <div class="ui-checkbox-check">
                                        <svg viewBox="0 0 18 18">
                                            <path d="M1,9 L1,3.5 C1,2.11928813 2.11928813,1 3.5,1 L14.5,1 C15.8807119,1 17,2.11928813 17,3.5 L17,14.5 C17,15.8807119 15.8807119,17 14.5,17 L3.5,17 C2.11928813,17 1,15.8807119 1,14.5 L1,9 Z"></path>
                                            <polyline points="1 9 7 14 15 4"></polyline>
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-admin-text uppercase tracking-widest font-body">Featured</span>
                                        <span class="text-[10px] text-admin-text-muted font-body">Highlight on showcase</span>
                                    </div>
                                </label>
                            </div>

                            <div class="p-4 bg-admin-surface-alt/30 rounded-xl border border-admin-border-subtle group hover:border-admin-accent/20 transition-colors">
                                <label class="ui-checkbox-wrapper flex items-center gap-3 cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="ui-checkbox-input" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
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
                        <span class="text-xs font-bold uppercase tracking-[0.2em]">Update Item</span>
                    </button>
                    <a href="{{ route('admin.gallery.index') }}" class="admin-action-btn admin-action-btn-ghost w-full justify-center py-3">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Discard Changes</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Gallery Edit uses Alpine.js for image preview and upload handling
        // No ImageUploader needed - Alpine.js handles drag/drop, preview, remove, and form submission
    </script>
</x-layouts::admin>

