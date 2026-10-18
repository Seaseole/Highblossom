@props([
    'quoteLabel' => null,
    'callLabel' => null,
    'whatsappLabel' => null,
    'showQuote' => true,
    'showWhatsapp' => true,
    'showCall' => true,
    'size' => 'md',
])

@php
    $isLarge = $size === 'lg';
    $base = $isLarge
        ? 'min-h-[52px] px-7 py-3.5 text-base'
        : 'min-h-[44px] px-5 py-3 text-sm sm:text-base';
    $focus = 'focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626] focus-visible:ring-offset-2 focus-visible:ring-offset-[#0A0A0F]';
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-stretch justify-center gap-3 sm:flex-row sm:flex-wrap sm:items-center']) }}>
    @if ($showQuote)
        <a
            href="{{ route('quote') }}"
            class="btn-premium glow-red {{ $base }} {{ $focus }}"
        >
            <span>{{ $quoteLabel ?? __('site.home.cta_band_quote') }}</span>
            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    @endif

    @if ($showWhatsapp && $contact->whatsappHref())
        <a
            href="{{ $contact->whatsappHref() }}"
            target="_blank"
            rel="noopener noreferrer"
            class="btn-glass {{ $base }} border-emerald-400/25 hover:border-emerald-400/50 {{ $focus }}"
        >
            <svg class="h-5 w-5 flex-shrink-0 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.182-.008-.391-.011-.6-.011-.207 0-.544.079-.829.396-.286.319-1.095 1.068-1.095 2.617 0 1.55 1.095 3.05 1.248 3.26.153.21 2.153 3.3 5.223 4.63.73.315 1.3.483 1.743.618.753.225 1.438.193 1.983.117.604-.09 1.84-.752 2.103-1.479.262-.727.262-1.349.183-1.479-.079-.13-.296-.203-.594-.351m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.398 0 .16 5.237.157 11.716c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.504 0 11.79-5.286 11.793-11.793a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            <span>{{ $whatsappLabel ?? __('site.home.cta_band_whatsapp') }}</span>
        </a>
    @endif

    @if ($showCall && $contact->telHref($contact->primaryPhone))
        <a
            href="{{ $contact->telHref($contact->primaryPhone) }}"
            class="btn-glass {{ $base }} {{ $focus }}"
        >
            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
            </svg>
            <span>{{ $callLabel ?? ($contact->primaryPhone !== '' ? $contact->primaryPhone : __('site.home.cta_band_call')) }}</span>
        </a>
    @endif
</div>
