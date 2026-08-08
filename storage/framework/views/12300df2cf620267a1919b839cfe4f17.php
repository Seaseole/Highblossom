<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'field' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'field' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    x-data="{
        isOpen: false,
        images: [],
        loading: false,
        selectedImage: null,
    }"
    x-on:open-media-picker.window="
        isOpen = true;
        field = $event.detail.field;
        loadImages();
    "
    class="relative"
>
    <!-- Modal -->
    <div
        x-show="isOpen"
        x-transition:enter="transition-opacity duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center"
        style="display: none"
    >
        <!-- Backdrop -->
        <div
            x-show="isOpen"
            x-transition:enter="transition-opacity duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="isOpen = false"
            class="absolute inset-0 bg-[#0A0A0F]/95 backdrop-blur-3xl"
        ></div>

        <!-- Modal Content -->
        <div class="relative mx-4 flex max-h-[80vh] w-full max-w-5xl flex-col rounded-[1.5rem] border border-white/10 bg-[#16161D] shadow-2xl shadow-[#0A0A0F]/50">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-white/5 p-6">
                <h2 class="text-lg font-semibold text-[#FAFAFA]">Media Library</h2>
                <button @click="isOpen = false" class="rounded-lg p-2 transition-colors hover:bg-white/10">
                    <svg class="h-5 w-5 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Upload Area -->
            <div class="border-b border-white/5 p-6">
                <div class="cursor-pointer rounded-xl border-2 border-dashed border-white/10 p-8 text-center transition-colors hover:border-[#DC2626]/30">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl border border-white/10 bg-white/5">
                        <svg class="h-6 w-6 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <h3 class="mb-1 text-sm font-medium text-[#FAFAFA]">Upload images</h3>
                    <p class="text-xs text-[#A1A1AA]">Drag and drop or click to browse</p>
                    <input type="file" class="hidden" accept="image/*" multiple @change="uploadImages($event)" />
                </div>
            </div>

            <!-- Image Grid -->
            <div class="flex-1 overflow-y-auto p-6">
                <div x-show="loading" class="flex items-center justify-center py-12">
                    <div class="h-8 w-8 animate-spin rounded-full border-2 border-[#DC2626] border-t-transparent"></div>
                </div>

                <div x-show="! loading && images.length === 0" class="py-12 text-center">
                    <p class="text-sm text-[#A1A1AA]">No images uploaded yet</p>
                </div>

                <div x-show="! loading && images.length > 0" class="grid grid-cols-4 gap-4">
                    <template x-for="image in images" :key="image.id">
                        <div
                            @click="selectImage(image)"
                            :class="selectedImage?.id === image.id
                                ? 'ring-2 ring-[#DC2626]'
                                : 'hover:ring-2 hover:ring-white/20'"
                            class="group relative aspect-square cursor-pointer overflow-hidden rounded-xl transition-all duration-200"
                        >
                            <img :src="image.url" :alt="image.alt || 'Image'" class="h-full w-full object-cover" />
                            <div class="absolute inset-0 flex items-center justify-center bg-black/60 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                                <span x-text="image.name" class="truncate px-2 text-center text-xs text-white"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Footer -->
            <div x-show="selectedImage" class="flex items-center justify-between border-t border-white/5 p-6">
                <div class="flex items-center gap-3">
                    <img
                        :src="selectedImage?.url"
                        :alt="selectedImage?.alt || 'Selected image'"
                        class="h-16 w-16 rounded-lg object-cover"
                    />
                    <div>
                        <p class="text-sm font-medium text-[#FAFAFA]" x-text="selectedImage?.name"></p>
                        <p class="text-xs text-[#A1A1AA]" x-text="selectedImage?.size"></p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button
                        @click="isOpen = false"
                        class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-[#FAFAFA] transition-all duration-200 hover:bg-white/10"
                    >
                        Cancel
                    </button>
                    <button
                        @click="confirmSelection"
                        class="rounded-xl border border-[#DC2626] bg-[#DC2626] px-4 py-2 text-white shadow-lg shadow-[#DC2626]/20 transition-all duration-200 hover:bg-[#B91C1C]"
                    >
                        Select Image
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <?php
        $__scriptKey = '3576117348-0';
        ob_start();
    ?>
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
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
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
                            url: this.selectedImage.url,
                        });
                        this.isOpen = false;
                        this.selectedImage = null;
                    }
                },
            }));
        });
    </script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\media-picker.blade.php ENDPATH**/ ?>