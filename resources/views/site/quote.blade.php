<x-layouts::site title="Request a Quote">
    <!-- Hero Section -->
    <section class="py-16 bg-surface">
        <div class="max-w-7xl mx-auto px-8 text-center">
            <span class="inline-block py-1 px-3 mb-6 rounded-full bg-primary-container/10 text-primary font-bold text-sm tracking-widest uppercase">Get Started</span>
            <h1 class="text-5xl md:text-6xl font-headline font-extrabold text-on-surface tracking-tighter leading-none mb-6">
                Request a <span class="text-primary">Quote</span>
            </h1>
            <p class="text-on-surface-variant text-lg max-w-2xl mx-auto leading-relaxed">
                Tell us about your vehicle and glass needs. We'll get back to you within 24 hours with a detailed quote.
            </p>
        </div>
    </section>

    <!-- Quote Form Section -->
    <section class="py-24 bg-surface-container-low">
        <div class="max-w-4xl mx-auto px-8">
            <div class="glass-card p-8 md:p-12 rounded-3xl border border-outline-variant/10 shadow-xl">
                <form class="space-y-8">
                    <!-- Personal Information -->
                    <div>
                        <h2 class="text-2xl font-headline font-bold text-on-surface mb-6">Your Information</h2>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-on-surface-variant mb-2">Full Name *</label>
                                <input type="text" id="name" name="name" required
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                    placeholder="John Doe">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-on-surface-variant mb-2">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                    placeholder="+267 XX XXX XXX">
                            </div>
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-on-surface-variant mb-2">Email Address</label>
                                <input type="email" id="email" name="email"
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                    placeholder="john@example.com">
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Information -->
                    <div class="pt-8 border-t border-outline-variant/10">
                        <h2 class="text-2xl font-headline font-bold text-on-surface mb-6">Vehicle Information</h2>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="vehicle_type" class="block text-sm font-medium text-on-surface-variant mb-2">Vehicle Type *</label>
                                <select id="vehicle_type" name="vehicle_type" required
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    <option value="">Select vehicle type</option>
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
                                <label for="make_model" class="block text-sm font-medium text-on-surface-variant mb-2">Make & Model</label>
                                <input type="text" id="make_model" name="make_model"
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                    placeholder="e.g., Toyota Hilux 2020">
                            </div>
                            <div>
                                <label for="reg_number" class="block text-sm font-medium text-on-surface-variant mb-2">Registration Number</label>
                                <input type="text" id="reg_number" name="reg_number"
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                    placeholder="e.g., B 123 ABC">
                            </div>
                            <div>
                                <label for="year" class="block text-sm font-medium text-on-surface-variant mb-2">Year</label>
                                <input type="number" id="year" name="year" min="1980" max="2026"
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                    placeholder="2020">
                            </div>
                        </div>
                    </div>

                    <!-- Glass Service Required -->
                    <div class="pt-8 border-t border-outline-variant/10">
                        <h2 class="text-2xl font-headline font-bold text-on-surface mb-6">Glass Service Required</h2>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-on-surface-variant mb-4">Glass Type Needed *</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <label class="flex items-center gap-3 p-4 rounded-lg bg-surface border border-outline-variant/20 cursor-pointer hover:border-primary/50 transition-colors">
                                        <input type="radio" name="glass_type" value="windscreen" class="w-4 h-4 text-primary focus:ring-primary">
                                        <span class="text-on-surface font-medium">Windscreen</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-4 rounded-lg bg-surface border border-outline-variant/20 cursor-pointer hover:border-primary/50 transition-colors">
                                        <input type="radio" name="glass_type" value="side_window" class="w-4 h-4 text-primary focus:ring-primary">
                                        <span class="text-on-surface font-medium">Side Window</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-4 rounded-lg bg-surface border border-outline-variant/20 cursor-pointer hover:border-primary/50 transition-colors">
                                        <input type="radio" name="glass_type" value="rear_window" class="w-4 h-4 text-primary focus:ring-primary">
                                        <span class="text-on-surface font-medium">Rear Window</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-4 rounded-lg bg-surface border border-outline-variant/20 cursor-pointer hover:border-primary/50 transition-colors">
                                        <input type="radio" name="glass_type" value="quarter" class="w-4 h-4 text-primary focus:ring-primary">
                                        <span class="text-on-surface font-medium">Quarter Glass</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-4 rounded-lg bg-surface border border-outline-variant/20 cursor-pointer hover:border-primary/50 transition-colors">
                                        <input type="radio" name="glass_type" value="machinery" class="w-4 h-4 text-primary focus:ring-primary">
                                        <span class="text-on-surface font-medium">Machinery</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-4 rounded-lg bg-surface border border-outline-variant/20 cursor-pointer hover:border-primary/50 transition-colors">
                                        <input type="radio" name="glass_type" value="other" class="w-4 h-4 text-primary focus:ring-primary">
                                        <span class="text-on-surface font-medium">Other</span>
                                    </label>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label for="service_type" class="block text-sm font-medium text-on-surface-variant mb-2">Service Type *</label>
                                <select id="service_type" name="service_type" required
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    <option value="">Select service type</option>
                                    <option value="replacement">Full Replacement</option>
                                    <option value="repair">Chip Repair</option>
                                    <option value="inspection">Inspection/Quote</option>
                                    <option value="emergency">Emergency Repair</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div class="pt-8 border-t border-outline-variant/10">
                        <h2 class="text-2xl font-headline font-bold text-on-surface mb-6">Additional Details</h2>
                        <div class="space-y-6">
                            <div>
                                <label for="message" class="block text-sm font-medium text-on-surface-variant mb-2">Describe Your Needs</label>
                                <textarea id="message" name="message" rows="4"
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none"
                                    placeholder="Please provide any additional details about your glass needs (e.g., damage extent, preferred time, insurance claim, etc.)"></textarea>
                            </div>
                            <div>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="mobile_service" class="w-5 h-5 rounded border-outline-variant/20 text-primary focus:ring-primary">
                                    <span class="text-on-surface-variant">I need mobile service (we come to you)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-8 border-t border-outline-variant/10">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                            <p class="text-sm text-on-surface-variant">
                                By submitting this form, you agree to be contacted regarding your quote request.
                            </p>
                            <button type="submit" class="primary-gradient text-on-primary px-8 py-4 rounded-lg font-headline font-bold text-lg shadow-xl shadow-primary/20 hover:opacity-90 transition-opacity w-full md:w-auto">
                                Submit Quote Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Alternative Contact -->
    <section class="py-24 bg-surface">
        <div class="max-w-4xl mx-auto px-8 text-center">
            <h2 class="text-3xl font-headline font-bold text-on-surface mb-6">Need Immediate Assistance?</h2>
            <p class="text-on-surface-variant mb-8">For urgent glass repairs or if you prefer to speak with us directly:</p>
            <div class="flex flex-wrap justify-center gap-4">
                @if ($primaryPhone)
                <a href="tel:{{ $primaryPhone->phone_number }}" class="glass-card text-on-surface px-8 py-4 rounded-lg font-headline font-bold text-lg border border-outline-variant/20 flex items-center gap-2 hover:bg-white/80 transition-colors">
                    <span class="material-symbols-outlined">phone</span>
                    Call: {{ $primaryPhone->formatted_number }}
                </a>
                @endif
                @php
                    $whatsapp = $whatsappNumber?->phone_number ?? '+26712345678';
                @endphp
                <a href="https://wa.me/{{ str_replace(['+', ' '], '', $whatsapp) }}" target="_blank" class="bg-[#25D366] text-white px-8 py-4 rounded-lg font-headline font-bold hover:opacity-90 transition-opacity inline-flex items-center gap-2">
                    <svg fill="currentColor" height="20" viewBox="0 0 16 16" width="20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"></path>
                    </svg>
                    WhatsApp Us
                </a>
            </div>
        </div>
    </section>
</x-layouts::site>
