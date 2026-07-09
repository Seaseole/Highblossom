<x-layouts::site>
    <section class="relative pt-32 pb-20 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <div class="mb-8">
                    <div class="w-20 h-20 rounded-full bg-green-500/10 border border-green-500/30 flex items-center justify-center mx-auto">
                        <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-4xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-4">
                    {{ __('confirmation.title') }}
                </h1>
                <p class="text-lg text-[#A1A1AA] leading-relaxed mb-10">
                    {!! __('confirmation.message', ['name' => $booking->client_name, 'vehicle' => $booking->vehicle_details]) !!}
                </p>

                <div class="glass-card rounded-2xl p-8 md:p-10 text-left space-y-5 mb-10">
                    <div class="flex items-center justify-between py-3 border-b border-white/5">
                        <span class="text-[#A1A1AA] text-sm">{{ __('confirmation.reference') }}</span>
                        <span class="text-[#FAFAFA] font-mono font-semibold">#HB-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-white/5">
                        <span class="text-[#A1A1AA] text-sm">{{ __('confirmation.scheduled_date') }}</span>
                        <span class="text-[#FAFAFA]">{{ $booking->scheduled_at ? $booking->scheduled_at->format(($dateFormat ?? 'd/M/Y') . ' ' . ($timeFormat ?? 'H:i')) : __('confirmation.tbc') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-white/5">
                        <span class="text-[#A1A1AA] text-sm">{{ __('confirmation.location') }}</span>
                        <span class="text-[#FAFAFA] text-right">
                            {{ $booking->location === 'mobile' ? __('booking.location_mobile') : __('booking.location_workshop') }}
                            @if($booking->location === 'mobile' && $booking->client_address)
                                <span class="block text-xs text-[#A1A1AA] mt-0.5">{{ $booking->client_address }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-white/5">
                        <span class="text-[#A1A1AA] text-sm">{{ __('confirmation.vehicle') }}</span>
                        <span class="text-[#FAFAFA]">{{ $booking->vehicle_details }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <span class="text-[#A1A1AA] text-sm">{{ __('confirmation.confirmation_email') }}</span>
                        <span class="text-[#FAFAFA]">{{ $booking->client_email }}</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('home') }}" class="btn-glass text-lg px-8 py-4 w-full sm:w-auto">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        {{ __('confirmation.return_home') }}
                    </a>
                    <a href="{{ route('bookings.create') }}" class="btn-premium text-lg px-8 py-4 w-full sm:w-auto">
                        @lang('booking.book_another')
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts::site>
