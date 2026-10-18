@php
    use App\Enums\BookingDamageType;
    use App\Enums\BookingServiceType;

    $serviceOptions = collect(BookingServiceType::selectable())
        ->mapWithKeys(fn (BookingServiceType $type): array => [$type->value => $type->label()]);

    $damageOptions = collect(BookingDamageType::cases())
        ->mapWithKeys(fn (BookingDamageType $type): array => [$type->value => $type->label()]);

    $locationOptions = [
        'mobile' => __('booking.location_mobile'),
        'workshop' => __('booking.location_workshop'),
    ];
@endphp

<x-layouts::site title="Book an Appointment">

    <div class="booking-flow">
        <!-- Hero -->
        <section class="relative bg-[#0A0A0F] pt-28 pb-12 md:pt-32">
            <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="mb-4 text-xs font-semibold tracking-[0.22em] text-[var(--booking-accent)] uppercase">
                        {{ __('booking.label') }}
                    </p>
                    <h1 class="mb-5 font-headline text-4xl font-bold tracking-tight leading-[1.08] text-[#FAFAFA] md:text-5xl lg:text-[3.5rem]">
                        {{ __('booking.title') }}
                    </h1>
                    <p class="text-base leading-relaxed text-[var(--booking-muted)] md:text-lg">
                        {{ __('booking.description') }}
                    </p>
                    <div class="mt-7 flex justify-center">
                        <x-site.contact-buttons :show-quote="false" size="md" class="justify-center" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Wizard -->
        <section class="bg-[#0A0A0F] pb-24">
            <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
                <div
                    class="mx-auto grid max-w-6xl gap-8 lg:grid-cols-[minmax(0,1fr)_20rem]"
                    x-data="bookingWizard()"
                    data-service-type="{{ old('service_type', $serviceType ?? '') }}"
                    data-damage-type="{{ old('damage_type', '') }}"
                    data-vehicle-make="{{ old('vehicle_make', '') }}"
                    data-vehicle-model="{{ old('vehicle_model', '') }}"
                    data-vehicle-year="{{ old('vehicle_year', '') }}"
                    data-location="{{ old('location', $location ?? '') }}"
                    data-client-address="{{ old('client_address', '') }}"
                    data-client-name="{{ old('client_name', '') }}"
                    data-client-email="{{ old('client_email', '') }}"
                    data-client-phone="{{ old('client_phone', '') }}"
                    data-vehicle-details="{{ old('vehicle_details', '') }}"
                    data-prefers-whatsapp="{{ old('prefers_whatsapp') ? '1' : '0' }}"
                    data-scheduled-at="{{ old('scheduled_at', '') }}"
                    data-initial-step="{{ $initialStep }}"
                    data-baseline-month="{{ now()->format('Y-m') }}"
                    data-total-months="{{ count($calendar) + 1 }}"
                    data-months="{{ json_encode($calendar) }}"
                    data-strip-dates="{{ json_encode(array_column($days, 'date')) }}"
                    data-option-labels="{{ json_encode([
                        'services' => $serviceOptions,
                        'damages' => $damageOptions,
                        'locations' => $locationOptions,
                    ]) }}"
                    data-reason-labels="{{ json_encode([
                        'too_late' => __('booking.slot_reason_too_late'),
                        'taken' => __('booking.slot_reason_taken'),
                        'closed' => __('booking.slot_reason_closed'),
                    ]) }}"
                    data-period-labels="{{ json_encode([
                        'morning' => __('booking.period_morning'),
                        'afternoon' => __('booking.period_afternoon'),
                    ]) }}"
                    data-availability-labels="{{ json_encode([
                        'singular' => __('booking.days_available_one', ['count' => 1]),
                        'plural' => __('booking.days_available_other', ['count' => ':count']),
                        'past' => __('booking.day_reason_past'),
                        'closed' => __('booking.day_reason_closed'),
                        'full' => __('booking.day_reason_full'),
                        'loading' => __('booking.day_reason_loading'),
                        'previous' => __('booking.previous_month'),
                        'next' => __('booking.next_month'),
                    ]) }}"
                    data-slot-count-labels="{{ json_encode([
                        'one' => __('booking.slot_count_one', ['count' => 1]),
                        'other' => __('booking.slot_count_other', ['count' => ':count']),
                    ]) }}"
                    data-step-labels="{{ json_encode([
                        __('booking.step_vehicle'),
                        __('booking.step_date_time'),
                        __('booking.step_details'),
                        __('booking.step_review'),
                    ]) }}"
                    data-step-of-template="{{ __('booking.step_of', ['step' => ':step', 'total' => ':total']) }}"
                >
                    <div class="min-w-0">
                        @if (session('error'))
                            <div
                                role="alert"
                                class="mb-6 flex items-start gap-3 rounded-2xl border border-[var(--booking-accent)]/35 bg-[var(--booking-accent)]/10 p-4 text-sm text-[#FAFAFA]"
                            >
                                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-[var(--booking-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 3h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <!-- Step tabs -->
                        <nav aria-label="{{ __('booking.label') }}" class="mb-7 -mx-1 overflow-x-auto px-1 pb-1 no-scrollbar">
                            <ol class="flex min-w-max items-center gap-2">
                                <template x-for="step in [1, 2, 3, 4]" :key="step">
                                    <li>
                                        <button
                                            type="button"
                                            @click="goTo(step)"
                                            :disabled="step > currentStep && ! isStepValid(currentStep)"
                                            :aria-current="currentStep === step ? 'step' : null"
                                            :class="{
                                                'bg-[var(--booking-accent)] text-[var(--booking-accent-ink)]': currentStep === step,
                                                'bg-white/[0.05] text-[#FAFAFA] hover:bg-white/[0.1]': currentStep !== step && step < currentStep,
                                                'bg-transparent text-[var(--booking-muted)]/60 cursor-not-allowed': currentStep !== step && step > currentStep,
                                            }"
                                            class="flex h-11 items-center gap-2 rounded-full border border-[var(--booking-line)] px-4 text-sm font-semibold transition-colors duration-200"
                                        >
                                            <span
                                                class="grid h-5 w-5 place-items-center rounded-full text-[11px] font-bold tnum"
                                                :class="currentStep === step ? 'bg-black/15' : 'bg-white/10'"
                                                x-text="step"
                                            ></span>
                                            <span x-text="stepLabels[step - 1]"></span>
                                        </button>
                                    </li>
                                </template>
                            </ol>
                        </nav>

                        <form
                            action="{{ route('bookings.store') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            x-ref="form"
                            @submit="isSubmitting = true"
                        >
                            @csrf
                            <input
                                type="hidden"
                                name="_idempotency_token"
                                value="{{ session()->get('booking_token', md5(uniqid())) }}"
                            />
                            @php(session()->put('booking_token', md5(uniqid())))

                            {{-- Fields with no visible input are carried as hidden values. --}}
                            <input type="hidden" name="scheduled_at" :value="scheduledAt" />
                            <input type="hidden" name="service_type" :value="serviceType" />
                            <input type="hidden" name="damage_type" :value="damageType" />
                            <input type="hidden" name="location" :value="location" />
                            <input type="hidden" name="prefers_whatsapp" :value="prefersWhatsapp ? 1 : 0" />

                            <!-- ============================ STEP 1: vehicle & service -->
                            <div
                                x-show="currentStep === 1"
                                x-cloak
                                x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="opacity-0 translate-x-6"
                                x-transition:enter-end="opacity-100 translate-x-0"
                                x-transition:leave="transition duration-200 ease-in"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                            >
                                <x-booking.panel :eyebrow="__('booking.step_vehicle')" :title="__('booking.service_heading')" :description="__('booking.service_description')">
                                    <div class="grid gap-3 sm:grid-cols-2">
                                        @foreach ($serviceOptions as $value => $label)
                                            <button
                                                type="button"
                                                @click="serviceType = '{{ $value }}'; showErrors = false; saveDraft()"
                                                :aria-pressed="serviceType === '{{ $value }}'"
                                                class="flex min-h-[44px] items-start gap-3 rounded-2xl border p-4 text-left transition-all duration-200"
                                                :class="serviceType === '{{ $value }}'
                                                    ? 'border-[var(--booking-accent)] bg-[var(--booking-accent)]/12 text-[#FAFAFA]'
                                                    : 'border-[var(--booking-line)] bg-white/[0.03] text-[var(--booking-muted)] hover:bg-white/[0.06] hover:text-[#FAFAFA]'"
                                            >
                                                <span
                                                    class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full border"
                                                    :class="serviceType === '{{ $value }}' ? 'border-[var(--booking-accent)] bg-[var(--booking-accent)]' : 'border-white/25'"
                                                >
                                                    <svg x-show="serviceType === '{{ $value }}'" class="h-3 w-3 text-[var(--booking-accent-ink)]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                                                    </svg>
                                                </span>
                                                <span class="text-sm font-semibold">{{ $label }}</span>
                                            </button>
                                        @endforeach
                                    </div>

                                    @error('service_type')
                                        <p class="mt-3 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </x-booking.panel>

                                <x-booking.panel :title="__('booking.damage_heading')" class="mt-5">
                                    <div class="flex flex-wrap gap-2" role="group" aria-label="{{ __('booking.damage_heading') }}">
                                        @foreach ($damageOptions as $value => $label)
                                            <button
                                                type="button"
                                                @click="damageType = '{{ $value }}'; showErrors = false; saveDraft()"
                                                :aria-pressed="damageType === '{{ $value }}'"
                                                class="min-h-[44px] rounded-full border px-4 text-sm font-medium transition-all duration-200"
                                                :class="damageType === '{{ $value }}'
                                                    ? 'border-[var(--booking-accent)] bg-[var(--booking-accent)] text-[var(--booking-accent-ink)]'
                                                    : 'border-[var(--booking-line)] bg-white/[0.03] text-[var(--booking-muted)] hover:bg-white/[0.07] hover:text-[#FAFAFA]'"
                                            >
                                                {{ $label }}
                                            </button>
                                        @endforeach
                                    </div>

                                    @error('damage_type')
                                        <p class="mt-3 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </x-booking.panel>

                                <x-booking.panel :title="__('booking.vehicle_heading')" class="mt-5">
                                    <div class="grid gap-4 sm:grid-cols-3">
                                        <div class="sm:col-span-1">
                                            <label for="vehicle_make" class="mb-2 block text-sm font-medium text-[#FAFAFA]/75">{{ __('booking.vehicle_make_label') }}</label>
                                            <input
                                                type="text"
                                                id="vehicle_make"
                                                name="vehicle_make"
                                                x-model="vehicleMake"
                                                @input="saveDraft()"
                                                :data-invalid="showErrors && vehicleMake.trim() === ''"
                                                class="booking-input"
                                                placeholder="{{ __('booking.vehicle_make_placeholder') }}"
                                                autocomplete="off"
                                            />
                                            @error('vehicle_make')
                                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-1">
                                            <label for="vehicle_model" class="mb-2 block text-sm font-medium text-[#FAFAFA]/75">{{ __('booking.vehicle_model_label') }}</label>
                                            <input
                                                type="text"
                                                id="vehicle_model"
                                                name="vehicle_model"
                                                x-model="vehicleModel"
                                                @input="saveDraft()"
                                                :data-invalid="showErrors && vehicleModel.trim() === ''"
                                                class="booking-input"
                                                placeholder="{{ __('booking.vehicle_model_placeholder') }}"
                                                autocomplete="off"
                                            />
                                            @error('vehicle_model')
                                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-1">
                                            <label for="vehicle_year" class="mb-2 block text-sm font-medium text-[#FAFAFA]/75">{{ __('booking.vehicle_year_label') }}</label>
                                            <input
                                                type="text"
                                                id="vehicle_year"
                                                name="vehicle_year"
                                                inputmode="numeric"
                                                x-model="vehicleYear"
                                                @input="vehicleYear = vehicleYear.replace(/[^0-9]/g, '').slice(0, 4); saveDraft()"
                                                :data-invalid="showErrors && vehicleYear.trim() === ''"
                                                class="booking-input tnum"
                                                placeholder="{{ __('booking.vehicle_year_placeholder') }}"
                                                autocomplete="off"
                                            />
                                            @error('vehicle_year')
                                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </x-booking.panel>

                                <x-booking.panel :title="__('booking.location_heading')" class="mt-5">
                                    <div class="grid gap-3 sm:grid-cols-2">
                                        @foreach ($locationOptions as $value => $label)
                                            <button
                                                type="button"
                                                @click="location = '{{ $value }}'; showErrors = false; saveDraft()"
                                                :aria-pressed="location === '{{ $value }}'"
                                                class="min-h-[44px] rounded-2xl border p-4 text-left transition-all duration-200"
                                                :class="location === '{{ $value }}'
                                                    ? 'border-[var(--booking-accent)] bg-[var(--booking-accent)]/12'
                                                    : 'border-[var(--booking-line)] bg-white/[0.03] hover:bg-white/[0.06]'"
                                            >
                                                <span class="block text-sm font-semibold text-[#FAFAFA]">{{ $label }}</span>
                                                <span class="mt-1 block text-xs text-[var(--booking-muted)]">
                                                    {{ $value === 'mobile' ? __('booking.location_mobile_hint') : __('booking.location_workshop_hint') }}
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>

                                    @error('location')
                                        <p class="mt-3 text-xs text-red-400">{{ $message }}</p>
                                    @enderror

                                    <div x-show="location === 'mobile'" x-transition class="mt-5">
                                        <label for="client_address" class="mb-2 block text-sm font-medium text-[#FAFAFA]/75">{{ __('booking.address_label') }}</label>
                                        <input
                                            type="text"
                                            id="client_address"
                                            name="client_address"
                                            x-model="clientAddress"
                                            @input="saveDraft()"
                                            :data-invalid="showErrors && location === 'mobile' && clientAddress.trim() === ''"
                                            class="booking-input"
                                            placeholder="{{ __('booking.address_placeholder') }}"
                                            autocomplete="street-address"
                                        />
                                        @error('client_address')
                                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </x-booking.panel>
                            </div>

                            <!-- ============================ STEP 2: date & time -->
                            <div
                                x-show="currentStep === 2"
                                x-cloak
                                x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="opacity-0 translate-x-6"
                                x-transition:enter-end="opacity-100 translate-x-0"
                                x-transition:leave="transition duration-200 ease-in"
                                x-transition:leave-start="opacity-100 translate-x-0"
                                x-transition:leave-end="opacity-0 -translate-x-6"
                            >
                                <div class="mb-5 flex flex-wrap items-center gap-2">
                                    <span class="mr-1 text-xs font-semibold tracking-[0.16em] text-[var(--booking-muted)] uppercase">{{ __('booking.quick_pick') }}</span>
                                    <button type="button" @click="quickPick(0)" class="min-h-[44px] rounded-full border border-[var(--booking-line)] bg-white/[0.03] px-4 text-sm font-medium text-[#FAFAFA] transition-colors hover:bg-white/[0.08]">{{ __('booking.quick_today') }}</button>
                                    <button type="button" @click="quickPick(1)" class="min-h-[44px] rounded-full border border-[var(--booking-line)] bg-white/[0.03] px-4 text-sm font-medium text-[#FAFAFA] transition-colors hover:bg-white/[0.08]">{{ __('booking.quick_tomorrow') }}</button>
                                    <button type="button" @click="bookThisWeek()" class="min-h-[44px] rounded-full border border-[var(--booking-line)] bg-white/[0.03] px-4 text-sm font-medium text-[#FAFAFA] transition-colors hover:bg-white/[0.08]">{{ __('booking.quick_this_week') }}</button>
                                    <button type="button" @click="jumpToEarliest()" class="min-h-[44px] rounded-full border border-[var(--booking-accent)]/45 bg-[var(--booking-accent)]/12 px-4 text-sm font-semibold text-[var(--booking-accent)] transition-colors hover:bg-[var(--booking-accent)]/20">{{ __('booking.quick_earliest') }}</button>
                                </div>

                                <div class="grid gap-5 lg:grid-cols-2">
                                    {{-- Calendar card --}}
                                    <x-booking.panel :eyebrow="__('booking.select_date')" :title="__('booking.step_date_time')">
                                        <template x-if="availableLabel">
                                            <p class="mb-4 text-xs font-semibold tracking-[0.14em] text-[var(--booking-accent)] uppercase tnum" x-text="availableLabel"></p>
                                        </template>

                                        <div class="mb-4 flex items-center justify-between">
                                            <button
                                                type="button"
                                                @click="prevMonth()"
                                                :disabled="activeMonthIndex === 0"
                                                :aria-label="availabilityLabels.previous"
                                                class="grid h-11 w-11 place-items-center rounded-full border border-[var(--booking-line)] bg-white/[0.03] text-[#FAFAFA] transition-colors hover:bg-white/[0.09] disabled:cursor-not-allowed disabled:opacity-25"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                                            </button>
                                            <p class="font-headline text-lg font-bold tracking-tight text-[#FAFAFA]" x-text="monthLabel"></p>
                                            <button
                                                type="button"
                                                @click="nextMonth()"
                                                :disabled="activeMonthIndex >= maxMonthIndex"
                                                :aria-label="availabilityLabels.next"
                                                class="grid h-11 w-11 place-items-center rounded-full border border-[var(--booking-line)] bg-white/[0.03] text-[#FAFAFA] transition-colors hover:bg-white/[0.09] disabled:cursor-not-allowed disabled:opacity-25"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                            </button>
                                        </div>

                                        <div class="mb-2 grid grid-cols-7 gap-1">
                                            @foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $weekday)
                                                <div class="py-1 text-center text-[10px] font-semibold tracking-[0.12em] text-white/30 uppercase">
                                                    {{ __('booking.weekday_'.$weekday) }}
                                                </div>
                                            @endforeach
                                        </div>

                                        <div
                                            x-ref="calendarGrid"
                                            role="grid"
                                            aria-label="{{ __('booking.select_date') }}"
                                            class="grid grid-cols-7 gap-1"
                                            @keydown="onCalendarKeydown($event)"
                                        >
                                            <template x-for="blank in leadingBlanks" :key="`blank-${blank}`">
                                                <div aria-hidden="true"></div>
                                            </template>

                                            <template x-for="day in activeDays" :key="day.date">
                                                <button
                                                    type="button"
                                                    :data-date="day.date"
                                                    role="gridcell"
                                                    :aria-selected="selectedDate === day.date"
                                                    :aria-disabled="! day.selectable"
                                                    :aria-label="day.date"
                                                    :tabindex="tabIndexFor(day.date)"
                                                    :disabled="! day.selectable"
                                                    @click="selectDate(day.date)"
                                                    @focus="focusDate = day.date"
                                                    :title="day.selectable ? '' : (availabilityLabels[day.reason] || '')"
                                                    :class="{
                                                        'bg-[var(--booking-accent)] text-[var(--booking-accent-ink)] font-bold shadow-[0_0_24px_-6px_rgba(220,38,38,0.6)]': selectedDate === day.date,
                                                        'border border-[var(--booking-accent)]/40 text-[#FAFAFA] hover:bg-white/[0.08]': selectedDate !== day.date && day.selectable,
                                                        'text-white/20 cursor-not-allowed line-through decoration-white/20': ! day.selectable,
                                                    }"
                                                    class="relative grid aspect-square min-h-[44px] place-items-center rounded-full text-sm tnum transition-all duration-200"
                                                >
                                                    <span x-text="day.day"></span>
                                                    <span
                                                        x-show="day.is_today"
                                                        class="absolute bottom-1.5 h-1 w-1 rounded-full"
                                                        :class="selectedDate === day.date ? 'bg-[var(--booking-accent-ink)]' : 'bg-[var(--booking-accent)]'"
                                                    ></span>
                                                </button>
                                            </template>
                                        </div>

                                        <p class="mt-4 flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-[var(--booking-muted)]">
                                            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[var(--booking-accent)]"></span>{{ __('booking.quick_today') }}</span>
                                            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-white/20"></span>{{ __('booking.day_reason_closed') }}</span>
                                            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-white/20"></span>{{ __('booking.day_reason_full') }}</span>
                                        </p>
                                    </x-booking.panel>

                                    {{-- Slot card --}}
                                    <x-booking.panel :eyebrow="__('booking.select_time')" :title="__('booking.step_date_time')">
                                        <div x-show="! selectedDate" class="grid min-h-[16rem] place-items-center text-center">
                                            <div>
                                                <svg class="mx-auto mb-3 h-10 w-10 text-white/15" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <rect x="3" y="4.5" width="18" height="16" rx="3" />
                                                    <path stroke-linecap="round" d="M8 2.5v4M16 2.5v4M3 10h18" />
                                                </svg>
                                                <p class="text-sm text-[var(--booking-muted)]">{{ __('booking.select_date') }}</p>
                                            </div>
                                        </div>

                                        {{-- Skeleton --}}
                                        <div x-show="selectedDate && slotsState === 'loading'" class="grid grid-cols-3 gap-2.5 sm:grid-cols-4">
                                            <template x-for="i in 8" :key="i">
                                                <div class="h-11 animate-pulse rounded-xl bg-white/[0.06]"></div>
                                            </template>
                                        </div>

                                        {{-- Error --}}
                                        <div x-show="selectedDate && slotsState === 'error'" x-cloak class="rounded-2xl border border-red-500/25 bg-red-500/[0.07] p-4">
                                            <p class="text-sm text-[#FAFAFA]/85">{{ __('booking.slots_load_failed') }}</p>
                                            <button type="button" @click="fetchSlots(selectedDate)" class="mt-2 min-h-[44px] text-sm font-semibold text-[var(--booking-accent)] underline-offset-4 hover:underline">{{ __('booking.retry') }}</button>
                                        </div>

                                        {{-- Closed --}}
                                        <div x-show="selectedDate && slotsState === 'closed'" x-cloak class="py-10 text-center text-sm text-[var(--booking-muted)]">
                                            {{ __('booking.day_closed') }}
                                        </div>

                                        {{-- Full --}}
                                        <div x-show="selectedDate && slotsState === 'empty'" x-cloak class="py-10 text-center">
                                            <p class="text-sm text-[var(--booking-muted)]">{{ __('booking.no_slots_available') }}</p>
                                            <button type="button" @click="jumpToEarliest()" class="mt-3 min-h-[44px] rounded-full border border-[var(--booking-accent)]/45 px-4 text-sm font-semibold text-[var(--booking-accent)] hover:bg-[var(--booking-accent)]/12">{{ __('booking.quick_earliest') }}</button>
                                        </div>

                                        {{-- Slots --}}
                                        <div x-show="selectedDate && slotsState === 'ready'" class="space-y-5">
                                            <p aria-live="polite" class="text-xs font-semibold tracking-[0.14em] text-[var(--booking-accent)] uppercase tnum" x-text="slotCountLabel"></p>

                                            <template x-for="group in slotGroups" :key="group.key">
                                                <div>
                                                    <p class="mb-2 text-[10px] font-semibold tracking-[0.18em] text-white/35 uppercase" x-text="periodLabels[group.key]"></p>
                                                    <div class="grid grid-cols-3 gap-2.5 sm:grid-cols-4">
                                                        <template x-for="slot in group.slots" :key="slot.time">
                                                            <button
                                                                type="button"
                                                                @click="selectSlot(slot)"
                                                                :disabled="! slot.available"
                                                                :aria-pressed="selectedTime === slot.time"
                                                                :title="slot.available ? `${slot.label} – ${slot.end_label}` : reasonFor(slot)"
                                                                class="relative h-11 rounded-xl border text-sm font-semibold transition-all duration-200"
                                                                :class="{
                                                                    'border-[var(--booking-accent)] bg-[var(--booking-accent)] text-[var(--booking-accent-ink)] shadow-[0_6px_22px_-10px_rgba(220,38,38,0.8)]': selectedTime === slot.time && slot.available,
                                                                    'border-[var(--booking-line)] bg-white/[0.04] text-[#FAFAFA] hover:border-white/20 hover:bg-white/[0.09]': slot.available && selectedTime !== slot.time,
                                                                    'border-transparent bg-white/[0.02] text-white/25 cursor-not-allowed': ! slot.available,
                                                                }"
                                                            >
                                                                <span class="tnum" x-text="slot.label"></span>
                                                            </button>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>

                                            <p class="text-xs text-[var(--booking-muted)]">
                                                {{ __('booking.duration_note', ['minutes' => $serviceDuration ?? 60]) }}
                                            </p>

                                            <div x-show="scheduledAt" x-cloak class="flex flex-wrap items-center gap-2 rounded-2xl border border-[var(--booking-accent)]/30 bg-[var(--booking-accent)]/[0.08] px-4 py-3">
                                                <svg class="h-4 w-4 flex-shrink-0 text-[var(--booking-accent)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" /></svg>
                                                <span class="text-[10px] font-semibold tracking-[0.16em] text-white/45 uppercase">{{ __('booking.selected') }}</span>
                                                <span class="text-sm font-semibold text-[#FAFAFA]">
                                                    <span x-text="selectedDateLabel"></span> · <span class="tnum" x-text="timeLabel"></span>
                                                    <span class="text-[var(--booking-muted)]">– <span class="tnum" x-text="endsLabel"></span></span>
                                                </span>
                                            </div>
                                        </div>

                                        @error('scheduled_at')
                                            <p class="mt-3 text-xs text-red-400">{{ $message }}</p>
                                        @enderror
                                    </x-booking.panel>
                                </div>
                            </div>

                            <!-- ============================ STEP 3: your details -->
                            <div
                                x-show="currentStep === 3"
                                x-cloak
                                x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="opacity-0 translate-x-6"
                                x-transition:enter-end="opacity-100 translate-x-0"
                                x-transition:leave="transition duration-200 ease-in"
                                x-transition:leave-start="opacity-100 translate-x-0"
                                x-transition:leave-end="opacity-0 -translate-x-6"
                            >
                                <x-booking.panel :eyebrow="__('booking.step_details')" :title="__('booking.your_information')" :description="__('booking.your_info_description')">
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="client_name" class="mb-2 block text-sm font-medium text-[#FAFAFA]/75">{{ __('booking.full_name_label') }}</label>
                                            <input type="text" id="client_name" name="client_name" x-model="clientName" @input="saveDraft()" :data-invalid="showErrors && clientName.trim() === ''" class="booking-input" placeholder="{{ __('booking.full_name_placeholder') }}" autocomplete="name" />
                                            @error('client_name')
                                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="client_phone" class="mb-2 block text-sm font-medium text-[#FAFAFA]/75">{{ __('booking.phone_number_label') }}</label>
                                            <input type="tel" id="client_phone" name="client_phone" x-model="clientPhone" @input="clientPhone = clientPhone.replace(/[^0-9]/g, '').slice(0, 20); saveDraft()" :data-invalid="showErrors && clientPhone.trim() === ''" class="booking-input tnum" placeholder="{{ __('booking.phone_placeholder') }}" autocomplete="tel" />
                                            @error('client_phone')
                                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="client_email" class="mb-2 block text-sm font-medium text-[#FAFAFA]/75">{{ __('booking.email_address_label') }}</label>
                                            <input type="email" id="client_email" name="client_email" x-model="clientEmail" @input="saveDraft()" :data-invalid="showErrors && clientEmail.trim() === ''" class="booking-input" placeholder="{{ __('booking.email_placeholder') }}" autocomplete="email" />
                                            @error('client_email')
                                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <label class="sm:col-span-2 flex min-h-[44px] cursor-pointer items-start gap-3 rounded-2xl border border-[var(--booking-line)] bg-white/[0.03] p-4 transition-colors hover:bg-white/[0.06]" :class="prefersWhatsapp ? 'border-[var(--booking-accent)]/45 bg-[var(--booking-accent)]/[0.08]' : ''">
                                            <input type="checkbox" value="1" x-model="prefersWhatsapp" @change="saveDraft()" class="mt-0.5 h-5 w-5 shrink-0 accent-[var(--booking-accent)]" />
                                            <span>
                                                <span class="block text-sm font-semibold text-[#FAFAFA]">{{ __('booking.prefers_whatsapp') }}</span>
                                                <span class="mt-0.5 block text-xs text-[var(--booking-muted)]">{{ __('booking.prefers_whatsapp_hint') }}</span>
                                            </span>
                                        </label>

                                        <div class="sm:col-span-2">
                                            <label for="vehicle_details" class="mb-2 block text-sm font-medium text-[#FAFAFA]/75">{{ __('booking.notes_label') }}</label>
                                            <textarea id="vehicle_details" name="vehicle_details" x-model="vehicleDetails" @input="saveDraft()" rows="3" class="booking-input" placeholder="{{ __('booking.notes_placeholder') }}"></textarea>
                                            @error('vehicle_details')
                                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="damage_photo" class="mb-2 block text-sm font-medium text-[#FAFAFA]/75">{{ __('booking.photo_label') }}</label>
                                            <input
                                                type="file"
                                                id="damage_photo"
                                                name="damage_photo"
                                                accept="image/jpeg,image/png,image/webp"
                                                class="block w-full text-sm text-[var(--booking-muted)] file:mr-4 file:min-h-[44px] file:rounded-full file:border-0 file:bg-[var(--booking-accent)] file:px-5 file:text-sm file:font-semibold file:text-[var(--booking-accent-ink)] hover:file:bg-[var(--booking-accent-strong)]"
                                            />
                                            <p class="mt-2 text-xs text-[var(--booking-muted)]">{{ __('booking.photo_hint') }}</p>
                                            @error('damage_photo')
                                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </x-booking.panel>
                            </div>

                            <!-- ============================ STEP 4: review -->
                            <div
                                x-show="currentStep === 4"
                                x-cloak
                                x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="opacity-0 translate-x-6"
                                x-transition:enter-end="opacity-100 translate-x-0"
                            >
                                <x-booking.panel :eyebrow="__('booking.step_review')" :title="__('booking.review_heading')" :description="__('booking.review_description')">
                                    <dl>
                                        <x-booking.review-row :label="__('booking.review_vehicle')" :step="1">
                                            <span x-text="serviceLabel"></span>
                                            <span class="text-[var(--booking-muted)]">· <span x-text="damageLabel"></span></span>
                                            <span class="mt-1 block text-sm text-[var(--booking-muted)]" x-text="vehicleLabel"></span>
                                        </x-booking.review-row>

                                        <x-booking.review-row :label="__('booking.review_when')" :step="2">
                                            <span x-text="selectedDateLabel"></span>
                                            <span class="tnum">· <span x-text="timeLabel"></span></span>
                                            <span class="text-[var(--booking-muted)]">– <span class="tnum" x-text="endsLabel"></span></span>
                                        </x-booking.review-row>

                                        <x-booking.review-row :label="__('booking.review_where')" :step="1">
                                            <span x-text="locationLabel"></span>
                                            <span class="mt-1 block text-sm text-[var(--booking-muted)]" x-show="location === 'mobile'" x-text="clientAddress"></span>
                                        </x-booking.review-row>

                                        <x-booking.review-row :label="__('booking.review_contact')" :step="3">
                                            <span x-text="clientName"></span>
                                            <span class="mt-1 block text-sm text-[var(--booking-muted)]">
                                                <span class="tnum" x-text="clientPhone"></span> · <span x-text="clientEmail"></span>
                                                <span x-show="prefersWhatsapp" class="text-[var(--booking-accent)]"> · {{ __('booking.prefers_whatsapp') }}</span>
                                            </span>
                                        </x-booking.review-row>

                                        <x-booking.review-row :label="__('booking.review_notes')" :step="3" :last="true">
                                            <span class="text-[var(--booking-muted)]" x-show="vehicleDetails.trim() === ''">{{ __('booking.review_no_notes') }}</span>
                                            <span x-text="vehicleDetails"></span>
                                        </x-booking.review-row>
                                    </dl>
                                </x-booking.panel>
                            </div>

                            <!-- ============================ Navigation -->
                            <div class="mt-7 flex items-center justify-between gap-4">
                                <button
                                    type="button"
                                    @click="prevStep()"
                                    x-show="currentStep > 1"
                                    x-cloak
                                    class="inline-flex min-h-[44px] items-center gap-2 rounded-full border border-[var(--booking-line)] bg-white/[0.03] px-5 text-sm font-semibold text-[#FAFAFA] transition-colors hover:bg-white/[0.08]"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                                    {{ __('booking.back') }}
                                </button>

                                <p class="text-xs text-[var(--booking-muted)] tnum" aria-live="polite" x-text="stepOfLabel"></p>

                                <button
                                    x-show="currentStep < 4"
                                    type="button"
                                    @click="nextStep()"
                                    class="inline-flex min-h-[44px] items-center gap-2 rounded-full bg-[var(--booking-accent)] px-6 text-sm font-semibold text-[var(--booking-accent-ink)] transition-all duration-200 hover:bg-[var(--booking-accent-strong)]"
                                >
                                    {{ __('booking.next') }}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </button>

                                <button
                                    x-show="currentStep === 4"
                                    x-cloak
                                    type="submit"
                                    :disabled="! canSubmit || isSubmitting"
                                    class="inline-flex min-h-[44px] items-center gap-2 rounded-full bg-[var(--booking-accent)] px-8 text-base font-semibold text-[var(--booking-accent-ink)] shadow-[0_10px_40px_-14px_rgba(220,38,38,0.75)] transition-all duration-200 hover:bg-[var(--booking-accent-strong)] disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    <span x-show="! isSubmitting" x-cloak>{{ __('booking.submit_booking') }}</span>
                                    <span x-show="isSubmitting" x-cloak class="flex items-center gap-2">
                                        <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.37 0 0 5.37 0 12h4z"></path>
                                        </svg>
                                        {{ __('booking.submitting') }}
                                    </span>
                                </button>
                            </div>

                            <p class="mt-4 text-xs text-[var(--booking-muted)]">{{ __('booking.submit_disclaimer') }}</p>
                        </form>
                    </div>

                    {{-- Live summary --}}
                    <aside class="lg:sticky lg:top-24 lg:self-start">
                        <div class="rounded-3xl border border-[var(--booking-line)] bg-[var(--booking-panel)] p-6">
                            <p class="mb-5 text-[10px] font-semibold tracking-[0.2em] text-[var(--booking-accent)] uppercase">{{ __('booking.summary_title') }}</p>

                            <dl class="space-y-4 text-sm">
                                <div>
                                    <dt class="text-[11px] tracking-[0.12em] text-white/35 uppercase">{{ __('booking.service_heading') }}</dt>
                                    <dd class="mt-1 font-medium text-[#FAFAFA]">
                                        <span x-show="serviceType" x-cloak x-text="serviceLabel"></span>
                                        <span x-show="! serviceType" class="text-[var(--booking-muted)]">{{ __('booking.summary_not_set') }}</span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] tracking-[0.12em] text-white/35 uppercase">{{ __('booking.vehicle_heading') }}</dt>
                                    <dd class="mt-1 font-medium text-[#FAFAFA]">
                                        <span x-show="vehicleMake" x-cloak x-text="vehicleLabel"></span>
                                        <span x-show="! vehicleMake" class="text-[var(--booking-muted)]">{{ __('booking.summary_not_set') }}</span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] tracking-[0.12em] text-white/35 uppercase">{{ __('booking.select_date') }}</dt>
                                    <dd class="mt-1 font-medium text-[#FAFAFA]">
                                        <span x-show="scheduledAt" x-cloak><span x-text="selectedDateLabel"></span> · <span class="tnum" x-text="timeLabel"></span></span>
                                        <span x-show="! scheduledAt" class="text-[var(--booking-muted)]">{{ __('booking.summary_not_set') }}</span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] tracking-[0.12em] text-white/35 uppercase">{{ __('booking.location_heading') }}</dt>
                                    <dd class="mt-1 font-medium text-[#FAFAFA]">
                                        <span x-show="location" x-cloak x-text="locationLabel"></span>
                                        <span x-show="! location" class="text-[var(--booking-muted)]">{{ __('booking.summary_not_set') }}</span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
</x-layouts::site>
