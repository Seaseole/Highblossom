<x-layouts::site title="Contact Us">
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">Contact</div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                    Get In Touch
                </h1>
                <p class="text-lg text-[#A1A1AA] leading-relaxed">
                    Visit our Gaborone workshop or reach out. We're here to help with all your automotive glass needs.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Methods Grid -->
    <section class="py-24 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse ($contactNumbers->take(3) as $index => $number)
                <div class="glass-card rounded-2xl p-8 text-center group hover:bg-white/[0.06] transition-all">
                    <div class="w-16 h-16 rounded-2xl bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        @if ($number->is_whatsapp)
                            <svg class="w-8 h-8 text-[#DC2626]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.447-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 5.834h-.004a9.311 9.311 0 01-4.51-1.177l-.323-.191-3.35.879.893-3.267-.209-.332a9.309 9.309 0 01-1.38-4.984c0-5.149 4.19-9.338 9.346-9.338a9.307 9.307 0 016.607 2.737 9.32 9.32 0 012.73 6.609c-.002 5.15-4.191 9.338-9.346 9.338m7.642-16.862A11.292 11.292 0 0012.237 0C5.636 0 .17 5.467.17 12.067c0 2.126.556 4.197 1.607 6.017L0 24l6.256-1.64a11.248 11.248 0 005.98 1.608c6.598 0 11.965-5.468 11.965-12.067a11.956 11.956 0 00-3.508-8.47"/>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">
                        {{ $number->is_whatsapp ? 'WhatsApp' : $number->label }}
                    </div>
                    <div class="text-2xl font-bold text-[#FAFAFA] font-headline mb-2">
                        {{ $number->formatted_number }}
                    </div>
                    <p class="text-[#71717A] text-sm mb-6">
                        @if ($number->is_primary)
                            Mon-Fri: 08:00-17:30
                        @elseif ($number->is_whatsapp)
                            Available 24/7
                        @else
                            {{ $number->label }} Line
                        @endif
                    </p>
                    <a href="{{ $number->is_whatsapp ? 'https://wa.me/' . str_replace(['+', ' '], '', $number->phone_number) : 'tel:' . $number->phone_number }}" target="{{ $number->is_whatsapp ? '_blank' : '_self' }}" class="btn-premium">
                        <span>{{ $number->is_whatsapp ? 'Chat on WhatsApp' : 'Call Now' }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
                @empty
                <!-- Fallback Cards -->
                <div class="glass-card rounded-2xl p-8 text-center group hover:bg-white/[0.06] transition-all">
                    <div class="w-16 h-16 rounded-2xl bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-8 h-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">Phone</div>
                    <div class="text-2xl font-bold text-[#FAFAFA] font-headline mb-2">
                        {{ $primaryPhone?->formatted_number ?? '+267 123 456 78' }}
                    </div>
                    <p class="text-[#71717A] text-sm mb-6">Mon-Fri: 08:00-17:30</p>
                    <a href="tel:{{ $primaryPhone?->phone_number ?? '+26712345678' }}" class="btn-premium">
                        <span>Call Now</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>

                <div class="glass-card rounded-2xl p-8 text-center group hover:bg-white/[0.06] transition-all">
                    <div class="w-16 h-16 rounded-2xl bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-8 h-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">Email</div>
                    <div class="text-2xl font-bold text-[#FAFAFA] font-headline mb-2">
                        {{ $primaryEmail ?? 'info@highblossom.co.bw' }}
                    </div>
                    <p class="text-[#71717A] text-sm mb-6">Reply within 24 hours</p>
                    <a href="mailto:{{ $primaryEmail }}" class="btn-premium">
                        <span>Send Email</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>

                <div class="glass-card rounded-2xl p-8 text-center group hover:bg-white/[0.06] transition-all">
                    <div class="w-16 h-16 rounded-2xl bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-8 h-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">Location</div>
                    <div class="text-2xl font-bold text-[#FAFAFA] font-headline mb-2">Gaborone, Botswana</div>
                    <p class="text-[#71717A] text-sm mb-6">Plot 123, Broadhurst</p>
                    <a href="https://maps.google.com" target="_blank" class="btn-premium">
                        <span>Get Directions</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Map & Contact Form Section -->
    <section class="py-24 bg-gradient-to-b from-[#0A0A0F] to-[#121218] border-t border-white/5">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Map -->
                <div class="relative">
                    <div class="h-[600px] rounded-2xl overflow-hidden relative">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114588.25127598265!2d25.87234155!3d-24.6282075!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1ebb5b3b2c6c5c99%3A0x3e3a9f5c5c9c9c9c!2sGaborone%2C%20Botswana!5e0!3m2!1sen!2s!4v1234567890"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="grayscale contrast-125">
                        </iframe>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-transparent to-transparent"></div>
                    </div>
                    <div class="glass-card rounded-2xl p-6 absolute bottom-6 left-6 right-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-[#DC2626]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#FAFAFA] font-headline mb-1">Highblossom Pty Ltd</h3>
                                <p class="text-[#A1A1AA] text-sm">Plot 123, Main Road<br>Broadhurst, Gaborone, Botswana</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div>
                    <div class="glass-card rounded-2xl p-8 md:p-10">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-[#FAFAFA] font-headline">Send a Message</h2>
                                <p class="text-[#71717A] text-sm">We'll get back to you within 24 hours</p>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="mb-6 p-4 rounded-xl bg-[#22C55E]/10 border border-[#22C55E]/30">
                                <span class="text-[#22C55E] text-sm">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-6 p-4 rounded-xl bg-[#DC2626]/10 border border-[#DC2626]/30">
                                <span class="text-[#DC2626] text-sm">{{ session('error') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact_name" class="block text-sm font-medium text-[#A1A1AA] mb-2">Full Name *</label>
                                    <input type="text" id="contact_name" name="name" required
                                        class="form-input-premium @error('name') border-red-500 @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="John Doe">
                                    @error('name') <p class="mt-1 text-[#DC2626] text-xs">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-[#A1A1AA] mb-2">Email Address *</label>
                                    <input type="email" id="contact_email" name="email" required
                                        class="form-input-premium @error('email') border-red-500 @enderror"
                                        value="{{ old('email') }}"
                                        placeholder="john@example.com">
                                    @error('email') <p class="mt-1 text-[#DC2626] text-xs">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-[#A1A1AA] mb-2">Phone Number</label>
                                <input type="tel" id="contact_phone" name="phone"
                                    class="form-input-premium @error('phone') border-red-500 @enderror"
                                    value="{{ old('phone') }}"
                                    placeholder="+267 XX XXX XXX">
                                @error('phone') <p class="mt-1 text-[#DC2626] text-xs">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="contact_subject" class="block text-sm font-medium text-[#A1A1AA] mb-2">Subject *</label>
                                <select id="contact_subject" name="subject" required
                                    class="form-input-premium @error('subject') border-red-500 @enderror">
                                    <option value="">Select a subject</option>
                                    <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="quote" {{ old('subject') == 'quote' ? 'selected' : '' }}>Quote Request</option>
                                    <option value="booking" {{ old('subject') == 'booking' ? 'selected' : '' }}>Booking Question</option>
                                    <option value="complaint" {{ old('subject') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                                    <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('subject') <p class="mt-1 text-[#DC2626] text-xs">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="contact_message" class="block text-sm font-medium text-[#A1A1AA] mb-2">Message *</label>
                                <textarea id="contact_message" name="message" rows="4" required
                                    class="form-input-premium resize-none @error('message') border-red-500 @enderror"
                                    placeholder="How can we help you?">{{ old('message') }}</textarea>
                                @error('message') <p class="mt-1 text-[#DC2626] text-xs">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" class="btn-premium glow-red-subtle w-full text-lg py-4">
                                <span>Send Message</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Business Hours -->
    <section class="py-24 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">Operational Hours</div>
                <h2 class="text-3xl md:text-4xl font-bold text-[#FAFAFA] font-headline">Business Hours</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="glass-card rounded-2xl p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline">Workshop Hours</h3>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex justify-between items-center py-3 border-b border-white/5">
                            <span class="text-[#A1A1AA]">Monday - Friday</span>
                            <span class="text-[#FAFAFA] font-semibold">08:00 - 17:30</span>
                        </li>
                        <li class="flex justify-between items-center py-3 border-b border-white/5">
                            <span class="text-[#A1A1AA]">Saturday</span>
                            <span class="text-[#FAFAFA] font-semibold">08:00 - 13:00</span>
                        </li>
                        <li class="flex justify-between items-center py-3">
                            <span class="text-[#A1A1AA]">Sunday</span>
                            <span class="text-[#DC2626] font-semibold">Closed</span>
                        </li>
                    </ul>
                </div>

                <div class="glass-card rounded-2xl p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#DC2626]/10 rounded-full blur-3xl"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline">Emergency Service</h3>
                    </div>
                    <p class="text-[#A1A1AA] mb-6">
                        Emergency glass repairs available 24/7 for fleet and commercial clients. Don't let broken glass stop your operations.
                    </p>
                    @if ($primaryPhone)
                    <a href="tel:{{ $primaryPhone->phone_number }}" class="btn-premium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>Hotline: {{ $primaryPhone->formatted_number }}</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="py-24 bg-gradient-to-b from-[#0A0A0F] to-[#121218] border-t border-white/5">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">Navigation</div>
                <h2 class="text-3xl md:text-4xl font-bold text-[#FAFAFA] font-headline">Quick Actions</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('quote') }}" class="glass-card rounded-2xl p-8 group hover:bg-white/[0.06] transition-all">
                    <div class="w-12 h-12 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-4 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-6 h-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-2">Get Quote</h3>
                    <p class="text-[#A1A1AA] text-sm mb-4">Request detailed pricing for your glass needs</p>
                    <div class="flex items-center gap-2 text-[#DC2626] font-semibold">
                        <span>Go</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('services') }}" class="glass-card rounded-2xl p-8 group hover:bg-white/[0.06] transition-all">
                    <div class="w-12 h-12 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-4 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-6 h-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-2">Services</h3>
                    <p class="text-[#A1A1AA] text-sm mb-4">Explore our comprehensive glass solutions</p>
                    <div class="flex items-center gap-2 text-[#DC2626] font-semibold">
                        <span>Go</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('gallery') }}" class="glass-card rounded-2xl p-8 group hover:bg-white/[0.06] transition-all">
                    <div class="w-12 h-12 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-4 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-6 h-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-2">Gallery</h3>
                    <p class="text-[#A1A1AA] text-sm mb-4">View our recent glass installation projects</p>
                    <div class="flex items-center gap-2 text-[#DC2626] font-semibold">
                        <span>Go</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </section>
</x-layouts::site>
