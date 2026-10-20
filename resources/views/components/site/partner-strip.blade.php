@props([
    'partners',
])

@php
    $partners = collect($partners ?? [])->values();
@endphp

{{-- Only renders with at least one real logo; the row centres and wraps from
     one to eight marks without leaving gaps. --}}
@if ($partners->isNotEmpty())
    <section class="bg-site py-14 lg:py-20" aria-label="{{ __('site.home.trusted_by') }}">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <p class="mb-10 text-center text-[10px] font-bold tracking-[0.2em] text-site-muted uppercase">
                {{ __('site.home.trusted_by') }}
            </p>

            <ul class="flex flex-wrap items-center justify-center gap-x-10 gap-y-8 sm:gap-x-14">
                @foreach ($partners as $partner)
                    <li class="group flex h-12 w-32 items-center justify-center sm:w-40">
                        <img
                            src="{{ $partner->logo_url }}"
                            alt="{{ $partner->name }}"
                            class="max-h-10 max-w-full object-contain opacity-60 grayscale transition-all duration-500 group-hover:opacity-100 group-hover:grayscale-0"
                            loading="lazy"
                            decoding="async"
                        />
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif
