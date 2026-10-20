@props([
    'faqs' => null,
])

@php
    $faqs = collect($faqs ?? [])
        ->filter(fn (array $faq) => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
        ->values();
@endphp

{{-- Questions and answers come from company settings, so the section hides
     entirely when the admin has not filled any in. --}}
@if ($faqs->isNotEmpty())
    <section id="faq" class="bg-site py-20 lg:py-28">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mx-auto mb-12 max-w-2xl text-center">
                <div class="mb-3 text-sm font-semibold tracking-wider text-site-link uppercase">
                    {{ __('site.home.faq_label') }}
                </div>
                <h2 class="font-headline text-3xl font-bold tracking-tight text-site-text md:text-4xl">
                    {{ __('site.home.faq_title') }}
                </h2>
            </div>

            <div
                class="mx-auto max-w-3xl space-y-3"
                x-data="{ open: 0 }"
            >
                @foreach ($faqs as $index => $faq)
                    @php $panelId = 'faq-panel-'.$index; $buttonId = 'faq-button-'.$index; @endphp
                    <div class="glass-card overflow-hidden rounded-2xl">
                        <h3>
                            <button
                                type="button"
                                id="{{ $buttonId }}"
                                aria-expanded="false"
                                :aria-expanded="open === {{ $index }} ? 'true' : 'false'"
                                aria-controls="{{ $panelId }}"
                                @click="open = open === {{ $index }} ? null : {{ $index }}"
                                class="flex min-h-[52px] w-full items-center justify-between gap-4 px-5 py-4 text-left transition-colors hover:bg-site-fill focus:outline-none focus-visible:ring-2 focus-visible:ring-site-accent/60 lg:px-6"
                            >
                                <span class="font-headline text-base font-semibold text-site-text lg:text-lg">
                                    {{ $faq['question'] }}
                                </span>
                                <svg
                                    class="h-5 w-5 flex-shrink-0 text-site-accent transition-transform duration-300"
                                    :class="open === {{ $index }} ? 'rotate-180' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </h3>

                        <div
                            id="{{ $panelId }}"
                            role="region"
                            aria-labelledby="{{ $buttonId }}"
                            x-show="open === {{ $index }}"
                            x-collapse
                            class="px-5 pb-5 lg:px-6"
                        >
                            <p class="text-sm leading-relaxed text-site-muted lg:text-base">
                                {{ $faq['answer'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs
                ->map(fn (array $faq) => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ],
                ])
                ->values()
                ->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}
    </script>
@endif
