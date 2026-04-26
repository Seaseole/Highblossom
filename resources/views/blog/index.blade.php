<x-layouts::site>
    <section class="min-h-screen bg-[#0A0A0F] pt-20">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8 py-16 lg:py-24">
            {{-- Header Section --}}
            <div class="max-w-2xl mb-12 lg:mb-16">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight leading-[1.1] mb-4">
                    Our Blog
                </h1>
                <p class="text-lg text-[#A1A1AA] leading-relaxed">
                    <span>Stay updated with the latest insights in the work behind <span class="font-semibold text-[#DC2626]">{{strtoupper(config('app.name'))  }}</span>.</span>
                </p>
            </div>

            {{-- Search & Filter Bar --}}
            <div class="mb-12">
                <livewire:blog-posts.search-form :search="$search" />

                {{-- Active Filters --}}
                <div class="flex flex-wrap items-center gap-3 mt-4">
                    <span class="text-sm text-[#71717A]">Active filters:</span>
                    <livewire:blog-posts.active-filters :search="$search" :category-slug="$categorySlug" :tag-slug="$tagSlug" />
                </div>
            </div>

            <livewire:blog-posts :search="$search" :category-slug="$categorySlug" :tag-slug="$tagSlug" lazy />
        </div>
    </section>

    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-layouts::site>
