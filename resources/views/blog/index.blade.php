<x-layouts::site>
    <section class="min-h-screen bg-[#0A0A0F] pt-20">
        <div class="mx-auto max-w-[1400px] px-6 py-16 lg:px-8 lg:py-24">
            <span class="inline-flex items-center rounded-full border border-[#DC2626]/20 bg-[#DC2626]/10 px-3 py-1 text-xs font-medium tracking-wider text-[#DC2626] uppercase">
                Read our blog
            </span>

            <h1 class="font-headline mt-5 text-4xl leading-[1.08] font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                Our Blog
            </h1>

            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-[#A1A1AA]">
                Insights and resources from the work behind
                <span class="font-semibold text-[#FAFAFA]">{{ config('app.name') }}</span>.
            </p>

            <div class="mt-12 lg:mt-16">
                <livewire:blog-posts
                    :search="$search"
                    :category-slug="$categorySlug"
                    :tag-slug="$tagSlug"
                    lazy
                />
            </div>
        </div>
    </section>
</x-layouts::site>
