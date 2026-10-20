<x-layouts::site>
    <section class="min-h-screen bg-site pt-20">
        <div class="mx-auto max-w-[1400px] px-6 py-16 lg:px-8 lg:py-24">
            <span class="inline-flex items-center rounded-full border border-site-accent/20 bg-site-accent-soft px-3 py-1 text-xs font-medium tracking-wider text-site-link uppercase">
                Read our blog
            </span>

            <h1 class="font-headline mt-5 text-4xl leading-[1.08] font-bold tracking-tight text-site-text md:text-5xl lg:text-6xl">
                Our Blog
            </h1>

            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-site-muted">
                Insights and resources from the work behind
                <span class="font-semibold text-site-text">{{ config('app.name') }}</span>.
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

    <x-blog.search-palette />
</x-layouts::site>
