@php
    $whatsappMessage = $booking->scheduled_at
        ? __('booking.whatsapp_message', [
            'reference' => $booking->displayReference(),
            'service' => $booking->service_type?->label() ?? __('booking.label'),
            'when' => $booking->scheduled_at->format('D d M Y').', '.$booking->scheduled_at->format('H:i'),
        ])
        : null;
@endphp

<x-layouts::site>
    <div class="booking-flow">
        <section class="relative bg-site pt-28 pb-24">
            <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
                <div class="mx-auto max-w-2xl">
                    <div class="mb-8 flex flex-col items-center text-center">
                        <div class="mb-6 grid h-16 w-16 place-items-center rounded-full border border-[var(--booking-accent)]/35 bg-[var(--booking-accent)]/10">
                            <svg class="h-8 w-8 text-[var(--booking-accent)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                            </svg>
                        </div>

                        <h1 class="mb-3 font-headline text-4xl font-bold tracking-tight leading-[1.1] text-site-text md:text-5xl">
                            {{ __('confirmation.title') }}
                        </h1>
                        <p class="text-base leading-relaxed text-[var(--booking-muted)] md:text-lg">
                            {{ __('confirmation.message', ['name' => $booking->client_name, 'vehicle' => $booking->vehicleSummary()]) }}
                        </p>
                    </div>

                    <div class="mb-6 overflow-hidden rounded-3xl border border-[var(--booking-line)] bg-[var(--booking-panel)]">
                        <div class="flex items-center justify-between border-b border-[var(--booking-line)] bg-[var(--booking-panel-raised)] px-6 py-4">
                            <span class="text-[10px] font-semibold tracking-[0.2em] text-[var(--booking-muted)] uppercase">{{ __('booking.reference_number') }}</span>
                            <span class="font-mono text-sm font-semibold text-[var(--booking-accent)]">{{ $booking->displayReference() }}</span>
                        </div>

                        <dl class="divide-y divide-[var(--booking-line)] text-sm">
                            <div class="flex items-start justify-between gap-6 px-6 py-4">
                                <dt class="shrink-0 text-[var(--booking-muted)]">{{ __('booking.review_vehicle') }}</dt>
                                <dd class="text-right font-medium text-site-text">
                                    {{ $booking->service_type?->label() ?? __('confirmation.tbc') }}
                                    @if ($booking->damage_type)
                                        <span class="block text-xs font-normal text-[var(--booking-muted)]">{{ $booking->damage_type->label() }}</span>
                                    @endif
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-6 px-6 py-4">
                                <dt class="shrink-0 text-[var(--booking-muted)]">{{ __('booking.vehicle_heading') }}</dt>
                                <dd class="text-right font-medium text-site-text">
                                    {{ $booking->vehicleSummary() }}
                                    @if ($booking->vehicle_details)
                                        <span class="block text-xs font-normal text-[var(--booking-muted)]">{{ $booking->vehicle_details }}</span>
                                    @endif
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-6 px-6 py-4">
                                <dt class="shrink-0 text-[var(--booking-muted)]">{{ __('confirmation.scheduled_date') }}</dt>
                                <dd class="text-right font-medium text-site-text tnum">
                                    {{ $booking->scheduled_at ? $booking->scheduled_at->format(($dateFormat ?? 'd/M/Y') . ' ' . ($timeFormat ?? 'H:i')) : __('confirmation.tbc') }}
                                    @if ($booking->ends_at)
                                        <span class="block text-xs font-normal text-[var(--booking-muted)]">
                                            → {{ $booking->ends_at->format('H:i') }}
                                            ({{ $booking->durationMinutes() }} min)
                                        </span>
                                    @endif
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-6 px-6 py-4">
                                <dt class="shrink-0 text-[var(--booking-muted)]">{{ __('confirmation.location') }}</dt>
                                <dd class="text-right font-medium text-site-text">
                                    {{ $booking->location === 'mobile' ? __('booking.location_mobile') : __('booking.location_workshop') }}
                                    @if ($booking->location === 'mobile' && $booking->client_address)
                                        <span class="block text-xs font-normal text-[var(--booking-muted)]">{{ $booking->client_address }}</span>
                                    @endif
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-6 px-6 py-4">
                                <dt class="shrink-0 text-[var(--booking-muted)]">{{ __('confirmation.confirmation_email') }}</dt>
                                <dd class="text-right font-medium text-site-text">
                                    {{ $booking->client_email }}
                                    <span class="block text-xs font-normal text-[var(--booking-muted)] tnum">{{ $booking->client_phone }}</span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mb-10 flex flex-col gap-3 sm:flex-row">
                        <a
                            href="{{ URL::signedRoute('bookings.calendar', $booking) }}"
                            class="inline-flex min-h-[44px] flex-1 items-center justify-center gap-2 rounded-full bg-[var(--booking-accent)] px-6 text-sm font-semibold text-[var(--booking-accent-ink)] transition-colors hover:bg-[var(--booking-accent-strong)]"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="4.5" width="18" height="16" rx="3" />
                                <path stroke-linecap="round" d="M8 2.5v4M16 2.5v4M3 10h18" />
                            </svg>
                            {{ __('booking.add_to_calendar') }}
                        </a>

                        @if ($whatsappMessage && $contact->whatsappHref($whatsappMessage))
                            <a
                                href="{{ $contact->whatsappHref($whatsappMessage) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex min-h-[44px] flex-1 items-center justify-center gap-2 rounded-full border border-[var(--booking-line)] bg-[var(--booking-fill)] px-6 text-sm font-semibold text-site-text transition-colors hover:bg-[var(--booking-fill-hover)]"
                            >
                                <svg class="h-4 w-4 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.182-.008-.391-.011-.6-.011-.207 0-.544.079-.829.396-.286.319-1.095 1.068-1.095 2.617 0 1.55 1.095 3.05 1.248 3.26.153.21 2.153 3.3 5.223 4.63.73.315 1.3.483 1.743.618.753.225 1.438.193 1.983.117.604-.09 1.84-.752 2.103-1.479.262-.727.262-1.349.183-1.479-.079-.13-.296-.203-.594-.351m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.398 0 .16 5.237.157 11.716c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.504 0 11.79-5.286 11.793-11.793a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                                {{ __('booking.confirm_on_whatsapp') }}
                            </a>
                        @endif
                    </div>

                    @if ($booking->events->isNotEmpty())
                        <div class="mb-10 rounded-3xl border border-[var(--booking-line)] bg-[var(--booking-panel)] p-6 md:p-8">
                            <h2 class="mb-1 font-headline text-xl font-bold tracking-tight text-site-text">
                                {{ __('confirmation.progress') }}
                            </h2>
                            <p class="mb-6 text-sm text-[var(--booking-muted)]">{{ __('confirmation.progress_description') }}</p>

                            <ol class="space-y-6">
                                @foreach ($booking->events as $event)
                                    <li class="flex gap-4">
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $loop->last ? 'bg-[var(--booking-accent)]' : 'border border-[var(--booking-accent)]/50 bg-[var(--booking-accent)]/30' }}"
                                            ></span>
                                            @unless ($loop->last)
                                                <span class="mt-1 w-px flex-1 bg-[var(--booking-line)]"></span>
                                            @endunless
                                        </div>
                                        <div class="pb-1">
                                            <p class="text-sm font-semibold text-site-text">
                                                {{ __('confirmation.event_'.$event->type) }}
                                            </p>
                                            <p class="text-xs text-[var(--booking-muted)] tnum">
                                                {{ $event->happened_at->format('d/M/Y') }} ·
                                                {{ $event->happened_at->format('H:i') }}
                                            </p>
                                            @if ($event->summary)
                                                <p class="mt-1.5 text-sm text-[var(--booking-muted)]">{{ $event->summary }}</p>
                                            @endif
                                            @if ($event->type === 'rescheduled' && $event->previous_scheduled_at)
                                                <p class="mt-1.5 text-xs text-[var(--booking-muted)]">
                                                    {{ __('confirmation.moved_from', [
                                                        'slot' => $event->previous_scheduled_at->format('d/M/Y H:i'),
                                                    ]) }}
                                                </p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif

                    <div class="flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <a href="{{ route('home') }}" wire:navigate class="btn-glass w-full px-6 py-3 sm:w-auto">
                            {{ __('confirmation.return_home') }}
                        </a>
                        <a href="{{ route('bookings.create') }}" wire:navigate class="inline-flex min-h-[44px] items-center rounded-full bg-[var(--booking-accent)] px-6 text-sm font-semibold text-[var(--booking-accent-ink)] hover:bg-[var(--booking-accent-strong)]">
                            @lang('booking.book_another')
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            // The draft is only useful until the booking exists; modules are deferred, so
            // this runs before site-booking.js can expose the key.
            try {
                localStorage.removeItem('highblossom-booking-draft');
                localStorage.removeItem('highblossom-booking-draft:step');
            } catch (e) {}
        </script>
    @endpush
</x-layouts::site>
