<x-layouts::site>
    <section class="relative bg-[#0A0A0F] pt-32 pb-20">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <div class="mb-8">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-green-500/30 bg-green-500/10">
                        <svg class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <h1 class="font-headline mb-4 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                    {{ __('confirmation.title') }}
                </h1>
                <p class="mb-10 text-lg leading-relaxed text-[#A1A1AA]">
                    {!! __('confirmation.message', ['name' => $booking->client_name, 'vehicle' => $booking->vehicle_details]) !!}
                </p>

                <div class="glass-card mb-10 space-y-5 rounded-2xl p-8 text-left md:p-10">
                    <div class="flex items-center justify-between border-b border-white/5 py-3">
                        <span class="text-sm text-[#A1A1AA]">{{ __('confirmation.reference') }}</span>
                        <span class="font-mono font-semibold text-[#FAFAFA]">#HB-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-white/5 py-3">
                        <span class="text-sm text-[#A1A1AA]">{{ __('confirmation.scheduled_date') }}</span>
                        <span class="text-[#FAFAFA]">{{ $booking->scheduled_at ? $booking->scheduled_at->format(($dateFormat ?? 'd/M/Y') . ' ' . ($timeFormat ?? 'H:i')) : __('confirmation.tbc') }}</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-white/5 py-3">
                        <span class="text-sm text-[#A1A1AA]">{{ __('confirmation.location') }}</span>
                        <span class="text-right text-[#FAFAFA]">
                            {{ $booking->location === 'mobile' ? __('booking.location_mobile') : __('booking.location_workshop') }}
                            @if ($booking->location === 'mobile' && $booking->client_address)
                                <span class="mt-0.5 block text-xs text-[#A1A1AA]">{{ $booking->client_address }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between border-b border-white/5 py-3">
                        <span class="text-sm text-[#A1A1AA]">{{ __('confirmation.vehicle') }}</span>
                        <span class="text-[#FAFAFA]">{{ $booking->vehicle_details }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-[#A1A1AA]">{{ __('confirmation.confirmation_email') }}</span>
                        <span class="text-[#FAFAFA]">{{ $booking->client_email }}</span>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <a href="{{ route('home') }}" class="btn-glass w-full px-8 py-4 text-lg sm:w-auto">
                        <svg class="mr-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        {{ __('confirmation.return_home') }}
                    </a>
                    <a href="{{ route('bookings.create') }}" class="btn-premium w-full px-8 py-4 text-lg sm:w-auto">
                        @lang('booking.book_another')
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts::site>
