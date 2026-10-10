{{--
    Authentication notification banner.

    Rendered server-side because the auth pages do not boot Alpine, so the
    session toaster cannot run here.
--}}
@props(['notification' => null])

@php
    $notification ??= \App\Support\AuthNotification::fromSession();

    $styles = match ($notification['type'] ?? 'error') {
        'success' => 'border-emerald-500/25 bg-emerald-500/10 text-emerald-700',
        'info' => 'border-sky-500/25 bg-sky-500/10 text-sky-700',
        'warning' => 'border-amber-500/25 bg-amber-500/10 text-amber-700',
        default => 'border-[#DC2626]/25 bg-[#DC2626]/5 text-[#B91C1C]',
    };
@endphp

@if ($notification && ! empty($notification['message']))
    <div
        data-auth-alert
        role="alert"
        aria-live="polite"
        {{ $attributes->class('mb-6 flex items-start gap-3 rounded-2xl border px-4 py-3 text-sm font-medium '.$styles) }}
    >
        @if (($notification['type'] ?? 'error') === 'success')
            <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        @else
            <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        @endif

        <p class="min-w-0 flex-1">{{ $notification['message'] }}</p>
    </div>
@endif
