@props(['label', 'step' => null, 'last' => false])

<div {{ $attributes->merge(['class' => 'flex items-start justify-between gap-6 py-4 '.($last ? '' : 'border-b border-[var(--booking-line)]')]) }}>
    <dt class="shrink-0 text-[11px] font-semibold tracking-[0.12em] text-white/35 uppercase">{{ $label }}</dt>
    <dd class="min-w-0 flex-1 text-right text-sm font-medium text-[#FAFAFA]">
        {{ $slot }}
    </dd>

    @if ($step)
        <button
            type="button"
            @click="goTo({{ $step }})"
            class="shrink-0 rounded-full border border-[var(--booking-line)] px-3 py-1.5 text-xs font-semibold text-[var(--booking-accent)] transition-colors hover:bg-[var(--booking-accent)]/12"
        >
            {{ __('booking.edit') }}
        </button>
    @endif
</div>
