<x-layouts::site>
    <section class="min-h-screen bg-[#0A0A0F] pt-20">
        <div class="mx-auto max-w-[1400px] px-6 py-16 lg:px-8 lg:py-24">
            {{-- Header Section --}}
            <div class="mb-12 max-w-2xl lg:mb-16">
                <h1 class="font-headline mb-4 text-4xl leading-[1.1] font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                    Our Blog
                </h1>
                <p class="text-lg leading-relaxed text-[#A1A1AA]">
                    <span
                        >Stay updated with the latest insights in the work behind
                        <span class="font-semibold text-[#DC2626]">{{ strtoupper(config('app.name')) }}</span>.</span>
                </p>
            </div>

            {{-- Search & Filter Bar --}}
            <div class="mb-12">
                <livewire:blog-posts.search-form :search="$search" />

                {{-- Active Filters --}}
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <span class="text-sm text-[#71717A]">Active filters:</span>
                    <livewire:blog-posts.active-filters
                        :search="$search"
                        :category-slug="$categorySlug"
                        :tag-slug="$tagSlug"
                    />
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
