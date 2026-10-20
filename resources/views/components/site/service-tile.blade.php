@props([
    'service',
    'featured' => false,
])

@php
    $image = $service->full_image_url ?? asset('placeholder.gif');
    $link = route('services').'#service-'.$service->slug;
    $anchor = $featured ? 'text-2xl lg:text-3xl' : 'text-lg';
@endphp

<article class="glass-card glass-card-hover group relative flex flex-col overflow-hidden rounded-2xl">
    <div class="{{ $featured ? 'aspect-[16/10]' : 'aspect-[4/3]' }} w-full overflow-hidden bg-site-surface">
        <img
            src="{{ $image }}"
            alt="{{ $service->title }}"
            width="800"
            height="600"
            loading="lazy"
            decoding="async"
            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.04]"
        />
    </div>

    <div class="flex flex-1 flex-col p-6">
        <h3 class="font-headline mb-2 {{ $anchor }} font-bold tracking-tight text-site-text">
            {{ $service->title }}
        </h3>

        @if (filled($service->short_description))
            <p class="mb-5 line-clamp-2 text-sm leading-relaxed text-site-muted">
                {{ $service->short_description }}
            </p>
        @endif

        {{-- Every tile uses the same labelled link; the bare chevron variant is gone. --}}
        <x-site.link
            :href="$link"
            prefetch
            class="mt-auto inline-flex min-h-[44px] items-center gap-2 self-start text-sm font-semibold text-site-link transition-all hover:text-site-link-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-site-accent/60"
            aria-label="{{ __('site.learn_more') }} — {{ $service->title }}"
        >
            <span>{{ __('site.learn_more') }}</span>
            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </x-site.link>
    </div>
</article>
