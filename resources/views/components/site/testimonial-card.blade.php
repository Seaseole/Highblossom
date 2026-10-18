@props([
    'testimonial',
    'featured' => false,
])

@php
    $rating = (int) ($testimonial->rating ?? 0);
@endphp

<div class="glass-card flex flex-col {{ $featured ? 'p-6' : 'p-6 lg:p-8' }} rounded-2xl">
    @if ($featured)
        <div class="mb-4 inline-flex w-fit items-center gap-2 rounded-full border border-[#DC2626]/30 bg-[#DC2626]/20 px-3 py-1">
            <svg class="h-4 w-4 text-[#DC2626]" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            <span class="text-xs font-semibold text-[#DC2626] uppercase">{{ __('site.home.testimonials_featured') }}</span>
        </div>
    @endif

    @if ($rating > 0)
        <div class="mb-4 flex gap-1" role="img" aria-label="{{ $rating }} out of 5 stars">
            @for ($i = 1; $i <= 5; $i++)
                <svg class="h-4 w-4 {{ $i <= $rating ? 'text-[#DC2626]' : 'text-[#3F3F46]' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            @endfor
        </div>
    @endif

    <blockquote class="mb-6 flex-1 {{ $featured ? 'line-clamp-4 text-sm' : 'text-base' }} leading-relaxed text-[#FAFAFA]">
        “{{ $testimonial->content }}”
    </blockquote>

    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#DC2626]/20">
            <span class="text-sm font-bold text-[#DC2626]">{{ Str::upper(Str::substr($testimonial->name, 0, 1)) }}</span>
        </div>
        <div class="min-w-0">
            <div class="text-sm font-semibold text-[#FAFAFA]">{{ $testimonial->name }}</div>
            @if (filled($testimonial->role))
                <div class="text-xs text-[#A1A1AA]">{{ $testimonial->role }}</div>
            @endif
        </div>
    </div>
</div>
