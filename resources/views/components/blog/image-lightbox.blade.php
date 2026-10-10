{{-- Full-screen viewer for content-block image sets; mount once per page. --}}
@once
    <div
        x-data="cbImageLightbox"
        x-on:click.window="captureTileClick($event)"
        x-on:keydown.escape.window="isOpen && close()"
        x-on:keydown.arrow-right.window="isOpen && next()"
        x-on:keydown.arrow-left.window="isOpen && previous()"
        x-on:keydown.tab.window="trapFocus($event)"
        class="relative"
    >
        <div
            x-show="isOpen"
            x-ref="dialog"
            x-transition:enter="transition duration-200 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            role="dialog"
            aria-modal="true"
            aria-label="Image viewer"
            class="fixed inset-0 z-[100] flex items-center justify-center"
            style="display: none"
        >
            <div class="absolute inset-0 bg-[#0A0A0F]/95 backdrop-blur-xl" @click="close()"></div>

            <button
                type="button"
                @click="close()"
                x-ref="closeButton"
                aria-label="Close image viewer"
                class="absolute top-6 right-6 z-10 flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-[#FAFAFA] transition-colors hover:bg-white/20"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <button
                type="button"
                x-show="slides.length > 1"
                @click="previous()"
                aria-label="Previous image"
                class="absolute top-1/2 left-4 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-[#FAFAFA] transition-colors hover:bg-white/20 sm:left-8"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button
                type="button"
                x-show="slides.length > 1"
                @click="next()"
                aria-label="Next image"
                class="absolute top-1/2 right-4 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-[#FAFAFA] transition-colors hover:bg-white/20 sm:right-8"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <figure class="relative z-10 flex max-h-[86vh] w-full max-w-5xl flex-col items-center gap-4 px-6">
                <img
                    :src="current().src"
                    :alt="current().alt"
                    class="max-h-[74vh] max-w-full rounded-2xl object-contain"
                />
                <figcaption class="flex flex-col items-center gap-2 text-center">
                    <span x-show="current().caption" x-text="current().caption" class="max-w-2xl text-sm text-[#A1A1AA]"></span>
                    <span
                        x-show="slides.length > 1"
                        x-text="String(index + 1) + ' / ' + String(slides.length)"
                        class="text-xs tracking-wider text-[#71717A] uppercase"
                    ></span>
                </figcaption>
            </figure>
        </div>
    </div>
@endonce
