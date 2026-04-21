@props([
    'field' => null,
])

<div x-data="{
    isOpen: false,
    images: [],
    loading: false,
    selectedImage: null
}" 
x-on:open-media-picker.window="isOpen = true; field = $event.detail.field; loadImages()"
class="relative">
    <!-- Modal -->
    <div x-show="isOpen" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
        <!-- Backdrop -->
        <div x-show="isOpen" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="isOpen = false" class="absolute inset-0 bg-[#0A0A0F]/95 backdrop-blur-3xl"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full max-w-5xl mx-4 bg-[#16161D] border border-white/10 rounded-[1.5rem] shadow-2xl shadow-[#0A0A0F]/50 max-h-[80vh] flex flex-col">
            <!-- Header -->
            <div class="p-6 border-b border-white/5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-[#FAFAFA]">Media Library</h2>
                <button @click="isOpen = false" class="p-2 rounded-lg hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Upload Area -->
            <div class="p-6 border-b border-white/5">
                <div class="border-2 border-dashed border-white/10 rounded-xl p-8 text-center hover:border-[#DC2626]/30 transition-colors cursor-pointer">
                    <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-[#FAFAFA] mb-1">Upload images</h3>
                    <p class="text-xs text-[#A1A1AA]">Drag and drop or click to browse</p>
                    <input type="file" class="hidden" accept="image/*" multiple @change="uploadImages($event)">
                </div>
            </div>
            
            <!-- Image Grid -->
            <div class="flex-1 overflow-y-auto p-6">
                <div x-show="loading" class="flex items-center justify-center py-12">
                    <div class="w-8 h-8 border-2 border-[#DC2626] border-t-transparent rounded-full animate-spin"></div>
                </div>
                
                <div x-show="!loading && images.length === 0" class="text-center py-12">
                    <p class="text-sm text-[#A1A1AA]">No images uploaded yet</p>
                </div>
                
                <div x-show="!loading && images.length > 0" class="grid grid-cols-4 gap-4">
                    <template x-for="image in images" :key="image.id">
                        <div 
                            @click="selectImage(image)"
                            :class="selectedImage?.id === image.id ? 'ring-2 ring-[#DC2626]' : 'hover:ring-2 hover:ring-white/20'"
                            class="relative aspect-square rounded-xl overflow-hidden cursor-pointer transition-all duration-200 group"
                        >
                            <img :src="image.url" :alt="image.alt || 'Image'" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                <span x-text="image.name" class="text-xs text-white text-center px-2 truncate"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Footer -->
            <div x-show="selectedImage" class="p-6 border-t border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img :src="selectedImage?.url" :alt="selectedImage?.alt || 'Selected image'" class="w-16 h-16 object-cover rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-[#FAFAFA]" x-text="selectedImage?.name"></p>
                        <p class="text-xs text-[#A1A1AA]" x-text="selectedImage?.size"></p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button @click="isOpen = false" class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] hover:bg-white/10 transition-all duration-200">
                        Cancel
                    </button>
                    <button 
                        @click="confirmSelection"
                        class="px-4 py-2 rounded-xl bg-[#DC2626] border border-[#DC2626] text-white hover:bg-[#B91C1C] transition-all duration-200 shadow-lg shadow-[#DC2626]/20"
                    >
                        Select Image
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('mediaPicker', () => ({
        field: null,
        isOpen: false,
        images: [],
        loading: false,
        selectedImage: null,
        
        async loadImages() {
            this.loading = true;
            try {
                const response = await fetch('/admin/media-library');
                const data = await response.json();
                this.images = data.images || [];
            } catch (error) {
                console.error('Failed to load images:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async uploadImages(event) {
            const files = event.target.files;
            if (!files.length) return;
            
            const formData = new FormData();
            for (let file of files) {
                formData.append('images[]', file);
            }
            
            this.loading = true;
            try {
                const response = await fetch('/admin/media-library/upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                await this.loadImages();
            } catch (error) {
                console.error('Failed to upload images:', error);
            } finally {
                this.loading = false;
            }
        },
        
        selectImage(image) {
            this.selectedImage = image;
        },
        
        confirmSelection() {
            if (this.selectedImage && this.field) {
                this.$dispatch('image-selected', { 
                    field: this.field, 
                    url: this.selectedImage.url 
                });
                this.isOpen = false;
                this.selectedImage = null;
            }
        }
    }));
});
</script>
@endscript
