<div
    x-data="mediaPicker"
    x-on:open-media-picker.window="open($event.detail)"
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
                <div>
                    <h2 class="text-lg font-semibold text-[#FAFAFA]">Media Library</h2>
                    <p class="text-xs text-[#71717A]">
                        <span x-show="multiple" x-text="'Pick up to ' + max + ' images · ' + selected.length + ' selected'"></span>
                        <span x-show="! multiple">Choose one image</span>
                        <span x-show="total > 0" x-text="' · ' + total + ' in library'"></span>
                    </p>
                </div>
                <button @click="isOpen = false" class="rounded-lg p-2 transition-colors hover:bg-white/10">
                    <svg class="h-5 w-5 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Search -->
            <div class="border-b border-white/5 px-6 py-4">
                <input
                    type="search"
                    x-model.debounce.300ms="searchTerm"
                    placeholder="Search the library by name"
                    class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-[#FAFAFA] outline-none placeholder:text-[#71717A] focus:border-[#DC2626]/40"
                />
            </div>

            <!-- Upload Area -->
            <div class="border-b border-white/5 p-6">
                <div class="cursor-pointer rounded-xl border-2 border-dashed border-white/10 p-8 text-center transition-colors hover:border-[#DC2626]/30" @click="$refs.uploadInput.click()">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl border border-white/10 bg-white/5">
                        <svg class="h-6 w-6 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <h3 class="mb-1 text-sm font-medium text-[#FAFAFA]">Upload images</h3>
                    <p class="text-xs text-[#A1A1AA]">Added to the library and selected for you — hold Ctrl to pick several</p>
                    <input type="file" class="hidden" accept="image/*" x-ref="uploadInput" x-bind:multiple="multiple" @change="uploadImage($event)" />
                </div>
            </div>

            <!-- Selection strip -->
            <div x-show="multiple && selected.length > 0" class="flex flex-wrap items-center gap-2 border-b border-white/5 px-6 py-4">
                <template x-for="(item, order) in selected" :key="item.id">
                    <span class="flex items-center gap-2 rounded-full border border-[#DC2626]/30 bg-[#DC2626]/10 py-1 pr-1 pl-3 text-xs text-[#FAFAFA]">
                        <span x-text="order + 1" class="font-semibold text-[#DC2626]"></span>
                        <img :src="item.url" :alt="item.alt || 'Selected image'" class="h-6 w-6 rounded-full object-cover" />
                        <button type="button" @click="toggle(item)" aria-label="Remove from selection" class="flex h-5 w-5 items-center justify-center rounded-full hover:bg-white/10">×</button>
                    </span>
                </template>
                <button type="button" @click="clearSelection()" class="text-xs text-[#A1A1AA] underline hover:text-[#FAFAFA]">Clear all</button>
            </div>

            <!-- Image Grid -->
            <div class="flex-1 overflow-y-auto p-6">
                <div x-show="loading" class="flex items-center justify-center py-12">
                    <div class="h-8 w-8 animate-spin rounded-full border-2 border-[#DC2626] border-t-transparent"></div>
                </div>

                <div x-show="! loading && images.length === 0" class="py-12 text-center">
                    <p class="text-sm text-[#A1A1AA]">No images match this search</p>
                </div>

                <div x-show="! loading && images.length > 0" class="grid grid-cols-4 gap-4">
                    <template x-for="image in images" :key="image.id">
                        <div
                            @click="toggle(image)"
                            :class="(multiple ? isSelected(image) : selectedImage?.id === image.id)
                                ? 'ring-2 ring-[#DC2626]'
                                : 'hover:ring-2 hover:ring-white/20'"
                            class="group relative aspect-square cursor-pointer overflow-hidden rounded-xl transition-all duration-200"
                        >
                            <img :src="image.url" :alt="image.alt || 'Image'" class="h-full w-full object-cover" />
                            <div class="absolute inset-0 flex items-center justify-center bg-black/60 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                                <span x-text="image.name" class="truncate px-2 text-center text-xs text-white"></span>
                            </div>
                            <div
                                x-show="multiple"
                                class="absolute top-1.5 left-1.5 flex h-6 w-6 items-center justify-center rounded-md border text-xs font-bold"
                                :class="isSelected(image) ? 'border-[#DC2626] bg-[#DC2626] text-white' : 'border-white/40 bg-black/50 text-transparent'"
                            >
                                <span x-show="isSelected(image)" x-text="orderOf(image)"></span>
                                <span x-show="! isSelected(image)">+</span>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="! loading && hasMore" class="pt-6 text-center">
                    <button
                        type="button"
                        @click="loadMore()"
                        class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-[#FAFAFA] transition-colors hover:bg-white/10"
                    >
                        Load more images
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div x-show="multiple ? selected.length > 0 : Boolean(selectedImage)" class="flex items-center justify-between border-t border-white/5 p-6">
                <div class="flex items-center gap-3">
                    <img
                        x-show="! multiple"
                        :src="selectedImage?.url"
                        :alt="selectedImage?.alt || 'Selected image'"
                        class="h-16 w-16 rounded-lg object-cover"
                    />
                    <div>
                        <p class="text-sm font-medium text-[#FAFAFA]" x-text="multiple ? selected.length + ' images selected' : selectedImage?.name"></p>
                        <p x-show="! multiple" class="text-xs text-[#A1A1AA]" x-text="selectedImage?.size"></p>
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
                        <span x-text="multiple ? 'Add ' + selected.length + ' Image' + (selected.length === 1 ? '' : 's') : 'Select Image'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
