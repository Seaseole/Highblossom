<x-layouts::site title="Request a Quote">
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">Get Started</div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                    Request a Quote
                </h1>
                <p class="text-lg text-[#A1A1AA] leading-relaxed">
                    Tell us about your vehicle and glass needs. We'll respond within 24 hours with detailed pricing.
                </p>
            </div>
        </div>
    </section>

    <!-- Quote Form Section -->
    <section class="py-24 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-12">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="glass-card rounded-2xl p-8 md:p-12">
                        <form action="{{ route('quote') }}" method="POST" class="space-y-10">
                            @csrf

                            <!-- Personal Information -->
                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline">Your Information</h3>
                                        <p class="text-[#71717A] text-sm">How we can contact you</p>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-[#A1A1AA] mb-2">Full Name *</label>
                                        <input type="text" id="name" name="name" required
                                            class="form-input-premium"
                                            placeholder="John Doe">
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-[#A1A1AA] mb-2">Phone Number *</label>
                                        <input type="tel" id="phone" name="phone" required
                                            class="form-input-premium"
                                            placeholder="+267 XX XXX XXX">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="email" class="block text-sm font-medium text-[#A1A1AA] mb-2">Email Address</label>
                                        <input type="email" id="email" name="email"
                                            class="form-input-premium"
                                            placeholder="john@example.com">
                                    </div>
                                </div>
                            </div>

                            <!-- Vehicle Information -->
                            <div class="pt-10 border-t border-white/5">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline">Vehicle Details</h3>
                                        <p class="text-[#71717A] text-sm">Tell us about your vehicle</p>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="vehicle_type" class="block text-sm font-medium text-[#A1A1AA] mb-2">Vehicle Type *</label>
                                        <select id="vehicle_type" name="vehicle_type" required class="form-input-premium">
                                            <option value="">Select type</option>
                                            <option value="sedan">Sedan / Hatchback</option>
                                            <option value="suv">SUV / 4x4</option>
                                            <option value="truck">Truck / Bakkie</option>
                                            <option value="van">Van / Minibus</option>
                                            <option value="heavy">Heavy Machinery</option>
                                            <option value="fleet">Fleet Vehicle</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="make_model" class="block text-sm font-medium text-[#A1A1AA] mb-2">Make & Model</label>
                                        <input type="text" id="make_model" name="make_model"
                                            class="form-input-premium"
                                            placeholder="e.g., Toyota Hilux 2020">
                                    </div>
                                    <div>
                                        <label for="reg_number" class="block text-sm font-medium text-[#A1A1AA] mb-2">Registration Number</label>
                                        <input type="text" id="reg_number" name="reg_number"
                                            class="form-input-premium"
                                            placeholder="e.g., B 123 ABC">
                                    </div>
                                    <div>
                                        <label for="year" class="block text-sm font-medium text-[#A1A1AA] mb-2">Year</label>
                                        <input type="number" id="year" name="year" min="1980" max="2026"
                                            class="form-input-premium"
                                            placeholder="2020">
                                    </div>
                                </div>
                            </div>

                            <!-- Service Required -->
                            <div class="pt-10 border-t border-white/5">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline">Service Required</h3>
                                        <p class="text-[#71717A] text-sm">What glass service do you need?</p>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-[#A1A1AA] mb-3">Glass Type *</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach(['windscreen' => 'Windscreen', 'side_window' => 'Side Window', 'rear_window' => 'Rear Window', 'quarter' => 'Quarter Glass', 'machinery' => 'Machinery', 'other' => 'Other'] as $value => $label)
                                        <label class="relative">
                                            <input type="radio" name="glass_type" value="{{ $value }}" class="peer sr-only">
                                            <div class="glass-card rounded-xl p-4 cursor-pointer hover:bg-white/[0.06] peer-checked:bg-[#DC2626]/10 peer-checked:border-[#DC2626]/30 transition-all">
                                                <div class="text-sm font-medium text-[#FAFAFA]">{{ $label }}</div>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <label for="service_type" class="block text-sm font-medium text-[#A1A1AA] mb-2">Service Type *</label>
                                    <select id="service_type" name="service_type" required class="form-input-premium">
                                        <option value="">Select service</option>
                                        <option value="replacement">Full Replacement</option>
                                        <option value="repair">Chip Repair</option>
                                        <option value="inspection">Inspection</option>
                                        <option value="emergency">Emergency</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="pt-10 border-t border-white/5">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline">Additional Information</h3>
                                        <p class="text-[#71717A] text-sm">Any other details we should know</p>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label for="message" class="block text-sm font-medium text-[#A1A1AA] mb-2">Description</label>
                                    <textarea id="message" name="message" rows="4"
                                        class="form-input-premium resize-none"
                                        placeholder="Describe your glass needs, damage details, or any specific requirements..."></textarea>
                                </div>

                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="mobile_service" class="sr-only peer">
                                    <div class="w-5 h-5 rounded border border-white/20 bg-white/5 flex items-center justify-center peer-checked:bg-[#DC2626] peer-checked:border-[#DC2626] transition-all">
                                        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-[#A1A1AA] text-sm group-hover:text-[#FAFAFA] transition-colors">Request mobile service (we come to you)</span>
                                </label>
                            </div>

                            <!-- Submit -->
                            <div class="pt-10 border-t border-white/5">
                                <button type="submit" class="btn-premium glow-red-subtle w-full md:w-auto text-lg px-12 py-4">
                                    <span>Submit Quote Request</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                                <p class="text-[#71717A] text-sm mt-4">
                                    By submitting, you agree to be contacted regarding your quote request.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-32 space-y-6">
                        <!-- Why Request Quote -->
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-[#FAFAFA] font-headline mb-4">Why Request a Quote?</h3>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-[#DC2626] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-[#A1A1AA] text-sm">Detailed, transparent pricing</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-[#DC2626] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-[#A1A1AA] text-sm">Response within 24 hours</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-[#DC2626] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-[#A1A1AA] text-sm">No obligation quote</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-[#DC2626] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-[#A1A1AA] text-sm">Expert consultation included</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Contact Card -->
                        @if($primaryPhone)
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-[#FAFAFA] font-headline mb-4">Prefer to Call?</h3>
                            <p class="text-[#A1A1AA] text-sm mb-4">Speak directly with our team for immediate assistance.</p>
                            <a href="tel:{{ $primaryPhone->phone_number }}" class="flex items-center gap-3 text-[#DC2626] font-semibold hover:underline">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $primaryPhone->formatted_number }}
                            </a>
                        </div>
                        @endif

                        <!-- Emergency Banner -->
                        <div class="bg-[#DC2626]/10 border border-[#DC2626]/30 rounded-2xl p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="w-2 h-2 bg-[#DC2626] rounded-full animate-pulse"></span>
                                <span class="text-[#DC2626] text-sm font-semibold uppercase">24/7 Emergency</span>
                            </div>
                            <p class="text-[#A1A1AA] text-sm mb-4">
                                Broken glass can't wait. Our emergency team is available around the clock.
                            </p>
                            @if($primaryPhone)
                            <a href="tel:{{ $primaryPhone->phone_number }}" class="btn-premium w-full text-sm py-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>Call Emergency Line</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts::site>
