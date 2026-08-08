<x-layouts::site title="Book an Inspection">
    <!-- Hero Section -->
    <section class="relative bg-[#0A0A0F] pt-32 pb-20">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <div class="mb-4 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    {{ __('booking.label') }}
                </div>
                <h1 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                    {{ __('booking.title') }}
                </h1>
                <p class="text-lg leading-relaxed text-[#A1A1AA]">{{ __('booking.description') }}</p>
            </div>
        </div>
    </section>

    <!-- Wizard Section -->
    <section class="bg-[#0A0A0F] py-24">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div
                class="mx-auto max-w-3xl"
                x-data="bookingWizard()"
                data-scheduled-at="{{ old('scheduled_at', '') }}"
                data-location="{{ old('location', '') }}"
                data-client-name="{{ old('client_name', '') }}"
                data-client-email="{{ old('client_email', '') }}"
                data-client-phone="{{ old('client_phone', '') }}"
                data-vehicle-details="{{ old('vehicle_details', '') }}"
                data-client-address="{{ old('client_address', '') }}"
                data-has-error="{{ session('error') ? 'true' : 'false' }}"
                data-months-count="{{ count($calendar) }}"
                data-months-labels="{{ json_encode(array_column($calendar, 'label')) }}"
            >
                @if (session('error'))
                    <div class="mb-8 flex items-start gap-3 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-400">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Step Indicator -->
                <div class="mb-4 flex items-center justify-center">
                    <template x-for="(step, index) in [1, 2, 3]" :key="step">
                        <div class="flex items-center">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold transition-all duration-300"
                                :class="currentStep >= step ? 'bg-[#DC2626] text-white' : 'bg-white/10 text-[#71717A]'"
                                x-text="step"
                            ></div>
                            <div
                                class="mx-2 h-0.5 w-12 transition-colors duration-300"
                                x-show="index < 2"
                                :class="currentStep > step ? 'bg-[#DC2626]' : 'bg-white/10'"
                            ></div>
                        </div>
                    </template>
                </div>
                <div class="mb-12 flex items-center justify-center gap-16 text-sm">
                    <span
                        class="transition-colors duration-300"
                        :class="currentStep === 1 ? 'text-[#DC2626] font-semibold' : 'text-[#71717A]'"
                    >
                        @lang('booking.step_date_time')
                    </span>
                    <span
                        class="transition-colors duration-300"
                        :class="currentStep === 2 ? 'text-[#DC2626] font-semibold' : 'text-[#71717A]'"
                    >
                        @lang('booking.step_details')
                    </span>
                    <span
                        class="transition-colors duration-300"
                        :class="currentStep === 3 ? 'text-[#DC2626] font-semibold' : 'text-[#71717A]'"
                    >
                        @lang('booking.step_vehicle')
                    </span>
                </div>

                <div class="glass-card rounded-2xl p-8 md:p-12">
                    <form action="{{ route('bookings.store') }}" method="POST" x-ref="form">
                        @csrf
                        <input
                            type="hidden"
                            name="_idempotency_token"
                            value="{{ session()->get('booking_token', md5(uniqid())) }}"
                        />
                        @php(session()->put('booking_token', md5(uniqid())))

                        <input type="hidden" name="scheduled_at" :value="scheduledAt" />
                        <input type="hidden" name="location" :value="location" />
                        <input type="hidden" name="client_name" :value="clientName" />
                        <input type="hidden" name="client_email" :value="clientEmail" />
                        <input type="hidden" name="client_phone" :value="clientPhone" />
                        <input type="hidden" name="vehicle_details" :value="vehicleDetails" />
                        <input type="hidden" name="client_address" :value="clientAddress" />

                        <!-- Step 1: Date & Time -->
                        <div x-show="currentStep === 1" x-transition>
                            <div class="mb-6">
                                <label class="mb-5 ml-1 block text-sm font-semibold text-[#FAFAFA]/70">
                                    @lang('booking.select_date')
                                </label>

                                <!-- Month Navigation -->
                                <div class="mb-5 flex items-center justify-between px-1">
                                    <button
                                        type="button"
                                        @click="prevMonth"
                                        :disabled="activeMonthIndex === 0"
                                        class="flex h-9 w-9 items-center justify-center rounded-full transition-all duration-300"
                                        :class="activeMonthIndex === 0
                                            ? 'text-white/20 cursor-not-allowed'
                                            : 'text-white/60 hover:text-white hover:bg-white/10'"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <div
                                        class="text-sm font-semibold tracking-wide text-white/80"
                                        x-text="monthLabel"
                                    ></div>
                                    <button
                                        type="button"
                                        @click="nextMonth"
                                        :disabled="activeMonthIndex === totalMonths - 1"
                                        class="flex h-9 w-9 items-center justify-center rounded-full transition-all duration-300"
                                        :class="activeMonthIndex === totalMonths - 1
                                            ? 'text-white/20 cursor-not-allowed'
                                            : 'text-white/60 hover:text-white hover:bg-white/10'"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="booking-calendar">
                                    @foreach ($calendar as $month)
                                        <div
                                            x-show="activeMonthIndex === {{ $loop->index }}"
                                            x-transition
                                            class="rounded-[2rem] border border-white/[0.06] bg-white/[0.02] p-1.5"
                                        >
                                            <div class="rounded-[calc(2rem-0.375rem)] bg-[#16161D] p-5 pb-4">
                                                <div class="mb-5 inline-flex rounded-full bg-white/5 px-3 py-1 text-[10px] font-medium tracking-[0.2em] text-white/50 uppercase">
                                                    {{ $month['label'] }}
                                                </div>
                                                <div class="grid grid-cols-7 gap-[3px]">
                                                    @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $weekday)
                                                        <div class="py-1 text-center text-[9px] font-medium tracking-[0.18em] text-white/25 uppercase">
                                                            {{ $weekday }}
                                                        </div>
                                                    @endforeach

                                                    @foreach ($month['weeks'] as $week)
                                                        @foreach ($week as $day)
                                                            @if ($day === null)
                                                                <div></div>
                                                            @elseif ($day['selectable'])
                                                                <button
                                                                    type="button"
                                                                    @click="selectDate('{{ $day['date'] }}')"
                                                                    :class="selectedDate === '{{ $day['date'] }}'
                                                                        ? 'bg-[#DC2626] text-white shadow-[0_0_24px_-6px_rgba(220,38,38,0.5)] shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)] scale-100'
                                                                        : 'bg-white/[0.04] text-[#FAFAFA] hover:bg-white/[0.08] hover:-translate-y-[1px] active:scale-[0.94]'"
                                                                    class="group relative flex aspect-square cursor-pointer items-center justify-center rounded-full border-0 text-sm font-semibold transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] will-change-transform"
                                                                >
                                                                    {{ $day['day'] }}
                                                                </button>
                                                            @else
                                                                <div class="flex aspect-square cursor-not-allowed items-center justify-center rounded-full border-0 text-sm font-semibold text-[#3F3F46] opacity-35">
                                                                    {{ $day['day'] }}
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @error('scheduled_at')
                                    <p class="mt-3 ml-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="selectedDate || @json($errors->has('scheduled_at'))" x-transition>
                                <label class="mb-3 ml-1 block text-sm font-semibold text-[#FAFAFA]/70">
                                    @lang('booking.select_time')
                                </label>

                                <div x-show="loadingSlots" class="grid grid-cols-3 gap-3 sm:grid-cols-4">
                                    <template x-for="i in 8" :key="i">
                                        <div class="h-12 animate-pulse rounded-xl bg-white/5"></div>
                                    </template>
                                </div>

                                <div
                                    x-show="! loadingSlots && slots.length === 0"
                                    x-cloak
                                    class="py-8 text-center text-[#71717A]"
                                >
                                    <svg class="mx-auto mb-3 h-12 w-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p>@lang('booking.no_slots_available')</p>
                                </div>

                                <div
                                    x-show="! loadingSlots && slots.length > 0"
                                    class="grid grid-cols-3 gap-3 sm:grid-cols-4"
                                >
                                    <template x-for="slot in slots" :key="slot.time">
                                        <button
                                            type="button"
                                            @click="selectSlot(slot.time)"
                                            :disabled="! slot.available"
                                            class="h-12 rounded-xl border text-sm font-semibold transition-all duration-200"
                                            :class="{
                                                'bg-[#DC2626] border-[#DC2626] text-white shadow-lg shadow-[#DC2626]/20':
                                                    selectedTime === slot.time && slot.available,
                                                'bg-white/5 border-white/10 text-[#FAFAFA] hover:bg-white/10 hover:border-white/20':
                                                    slot.available && selectedTime !== slot.time,
                                                'bg-white/5 text-[#71717A] line-through cursor-not-allowed opacity-40 border-white/5':
                                                    ! slot.available,
                                            }"
                                            x-text="slot.time"
                                        ></button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Your Details -->
                        <div x-show="currentStep === 2" x-cloak x-transition>
                            <div class="space-y-6">
                                <div>
                                    <label
                                        for="client_name"
                                        class="mb-2 ml-1 block text-sm font-semibold text-[#FAFAFA]/70"
                                        >{{ __('booking.full_name_label') }}
                                        <span class="text-[#DC2626]">*</span></label>
                                    <input
                                        type="text"
                                        id="client_name"
                                        name="client_name"
                                        x-model="clientName"
                                        class="form-input-premium @error('client_name') border-red-500 @enderror"
                                        placeholder="John Doe"
                                    />
                                    @error('client_name')
                                        <p class="mt-1.5 ml-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        for="client_phone"
                                        class="mb-2 ml-1 block text-sm font-semibold text-[#FAFAFA]/70"
                                        >{{ __('booking.phone_number_label') }}
                                        <span class="text-[#DC2626]">*</span></label>
                                    <input
                                        type="tel"
                                        id="client_phone"
                                        name="client_phone"
                                        x-model="clientPhone"
                                        class="form-input-premium @error('client_phone') border-red-500 @enderror"
                                        placeholder="267 XX XXX XXX"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    />
                                    @error('client_phone')
                                        <p class="mt-1.5 ml-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label
                                        for="client_email"
                                        class="mb-2 ml-1 block text-sm font-semibold text-[#FAFAFA]/70"
                                        >{{ __('booking.email_address_label') }}
                                        <span class="text-[#DC2626]">*</span></label>
                                    <input
                                        type="email"
                                        id="client_email"
                                        name="client_email"
                                        x-model="clientEmail"
                                        class="form-input-premium @error('client_email') border-red-500 @enderror"
                                        placeholder="john@example.com"
                                    />
                                    @error('client_email')
                                        <p class="mt-1.5 ml-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Vehicle & Location -->
                        <div x-show="currentStep === 3" x-cloak x-transition>
                            <div class="space-y-6">
                                <div>
                                    <label
                                        for="vehicle_details"
                                        class="mb-2 ml-1 block text-sm font-semibold text-[#FAFAFA]/70"
                                        >{{ __('booking.vehicle_details_label') }}
                                        <span class="text-[#DC2626]">*</span></label>
                                    <textarea
                                        id="vehicle_details"
                                        name="vehicle_details"
                                        x-model="vehicleDetails"
                                        rows="3"
                                        class="form-input-premium @error('vehicle_details') border-red-500 @enderror"
                                        placeholder="E.g. Toyota Hilux 2020, B 123 ABC"
                                    ></textarea>
                                    @error('vehicle_details')
                                        <p class="mt-1.5 ml-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-3 ml-1 block text-sm font-semibold text-[#FAFAFA]/70">{{ __('booking.location_label') }} <span class="text-[#DC2626]">*</span></label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <button
                                            type="button"
                                            @click="location = 'mobile'"
                                            class="rounded-xl border p-4 text-center text-sm font-semibold transition-all duration-200"
                                            :class="location === 'mobile'
                                                ? 'border-[#DC2626] bg-[#DC2626]/10 text-[#FAFAFA]'
                                                : 'border-white/10 bg-white/5 text-[#71717A] hover:bg-white/10'"
                                        >
                                            {{ __('booking.location_mobile') }}
                                        </button>
                                        <button
                                            type="button"
                                            @click="location = 'workshop'"
                                            class="rounded-xl border p-4 text-center text-sm font-semibold transition-all duration-200"
                                            :class="location === 'workshop'
                                                ? 'border-[#DC2626] bg-[#DC2626]/10 text-[#FAFAFA]'
                                                : 'border-white/10 bg-white/5 text-[#71717A] hover:bg-white/10'"
                                        >
                                            {{ __('booking.location_workshop') }}
                                        </button>
                                    </div>
                                    @error('location')
                                        <p class="mt-2 ml-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address field, shown only for Mobile Service -->
                                <div x-show="location === 'mobile'" x-transition>
                                    <label
                                        for="client_address"
                                        class="mb-2 ml-1 block text-sm font-semibold text-[#FAFAFA]/70"
                                    >{{ __('booking.address_label') }} <span class="text-[#DC2626]">*</span></label>
                                    <textarea
                                        id="client_address"
                                        x-model="clientAddress"
                                        rows="2"
                                        class="form-input-premium @error('client_address') border-red-500 @enderror"
                                        placeholder="E.g. Plot 123, Gaborone North, Botswana"
                                    ></textarea>
                                    @error('client_address')
                                        <p class="mt-1.5 ml-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="mt-8 flex items-center justify-between border-t border-white/5 pt-10">
                            <button
                                type="button"
                                @click="prevStep"
                                x-show="currentStep > 1"
                                class="btn-glass px-5 py-2.5 text-sm"
                            >
                                <svg class="mr-1 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                @lang('booking.back')
                            </button>

                            <div x-show="currentStep < 3">
                                <button
                                    type="button"
                                    @click="nextStep"
                                    :disabled="! canProceed"
                                    class="btn-premium px-6 py-2.5 text-sm"
                                    :class="{ 'opacity-50 cursor-not-allowed': ! canProceed }"
                                >
                                    @lang('booking.next')
                                    <svg class="ml-1 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>

                            <div x-show="currentStep === 3">
                                <button
                                    type="button"
                                    @click="submitForm"
                                    :disabled="! canSubmit"
                                    class="btn-premium glow-red-subtle w-full px-12 py-4 text-lg md:w-auto"
                                    :class="{ 'opacity-50 cursor-not-allowed': ! canSubmit }"
                                >
                                    <span x-show="! isSubmitting" x-cloak>@lang('booking.submit_booking')</span>
                                    <span x-show="isSubmitting" x-cloak class="flex items-center gap-2">
                                        <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Submitting...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Tabular numbers for calendar grid --}}
    <style>
        .booking-calendar .grid button,
        .booking-calendar .grid > div {
            font-feature-settings: 'tnum';
        }
    </style>
</x-layouts::site>
