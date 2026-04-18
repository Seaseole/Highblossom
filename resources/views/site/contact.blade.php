<x-layouts::site title="Contact Us">
    <!-- Hero Section -->
    <section class="py-16 bg-surface">
        <div class="max-w-7xl mx-auto px-8 text-center">
            <span class="inline-block py-1 px-3 mb-6 rounded-full bg-primary-container/10 text-primary font-bold text-sm tracking-widest uppercase">Get in Touch</span>
            <h1 class="text-5xl md:text-6xl font-headline font-extrabold text-on-surface tracking-tighter leading-none mb-6">
                Contact <span class="text-primary">Highblossom</span>
            </h1>
            <p class="text-on-surface-variant text-lg max-w-2xl mx-auto leading-relaxed">
                Visit our Gaborone workshop or reach out to us. We're here to help with all your automotive glass needs.
            </p>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="py-24 bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-8">
            <div class="grid md:grid-cols-3 gap-8">
                @forelse ($contactNumbers->take(3) as $number)
                <div class="group p-10 bg-surface rounded-2xl border border-outline-variant/10 shadow-lg text-center hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl text-primary">{{ $number->is_whatsapp ? 'chat' : 'phone' }}</span>
                    </div>
                    <h3 class="text-xl font-headline font-bold text-on-surface mb-2">{{ $number->label }}</h3>
                    <p class="text-on-surface-variant mb-4">
                        @if ($number->is_primary)
                            Mon-Fri: 8:00 AM - 5:30 PM
                        @elseif ($number->is_whatsapp)
                            Available on WhatsApp
                        @else
                            {{ ucfirst($number->label) }} Line
                        @endif
                    </p>
                    <a href="tel:{{ $number->phone_number }}" class="text-primary font-bold hover:underline">{{ $number->formatted_number }}</a>
                </div>
                @empty
                <!-- Fallback when no contact numbers exist -->
                <div class="group p-10 bg-surface rounded-2xl border border-outline-variant/10 shadow-lg text-center hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl text-primary">phone</span>
                    </div>
                    <h3 class="text-xl font-headline font-bold text-on-surface mb-2">Phone</h3>
                    <p class="text-on-surface-variant mb-4">Mon-Fri: 8:00 AM - 5:30 PM</p>
                    <a href="tel:{{ $primaryPhone?->phone_number ?? '+26712345678' }}" class="text-primary font-bold hover:underline">{{ $primaryPhone?->formatted_number ?? '+267 123 456 78' }}</a>
                </div>

                <div class="group p-10 bg-surface rounded-2xl border border-outline-variant/10 shadow-lg text-center hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl text-primary">email</span>
                    </div>
                    <h3 class="text-xl font-headline font-bold text-on-surface mb-2">Email</h3>
                    <p class="text-on-surface-variant mb-4">We reply within 24 hours</p>
                    <a href="mailto:{{ $primaryEmail }}" class="text-primary font-bold hover:underline">{{ $primaryEmail }}</a>
                </div>

                <div class="group p-10 bg-surface rounded-2xl border border-outline-variant/10 shadow-lg text-center hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl text-primary">location_on</span>
                    </div>
                    <h3 class="text-xl font-headline font-bold text-on-surface mb-2">Visit Us</h3>
                    <p class="text-on-surface-variant mb-4">{{ $companyAddress }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Map & Contact Form Section -->
    <section class="py-24 bg-surface">
        <div class="max-w-7xl mx-auto px-8">
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Map -->
                <div class="relative">
                    <div class="h-[500px] rounded-2xl overflow-hidden bg-surface-container-highest border border-outline-variant/10">
                        <img alt="Map of Gaborone" class="w-full h-full object-cover opacity-50 grayscale" src="https://images.unsplash.com/photo-1524661135-423995f22d0b?w=1200&q=80" loading="lazy">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-2xl shadow-xl max-w-sm text-center">
                                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">location_on</span>
                                </div>
                                <h3 class="font-headline font-bold text-on-surface mb-2">Highblossom PTY LTD</h3>
                                <p class="text-on-surface-variant text-sm mb-4">Plot 123, Main Road<br>Broadhurst, Gaborone<br>Botswana</p>
                                <a href="https://maps.google.com/?q=8V7P+C6C, Nyamambisi, Gaborone" target="_blank" class="text-primary font-bold text-sm hover:underline inline-flex items-center gap-1">
                                    Get Directions
                                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div>
                    <div class="glass-card p-8 md:p-10 rounded-2xl border border-outline-variant/10 shadow-xl">
                        <h2 class="text-2xl font-headline font-bold text-on-surface mb-6">Send us a Message</h2>

                        @if (session('success'))
                            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact_name" class="block text-sm font-medium text-on-surface-variant mb-2">Your Name *</label>
                                    <input type="text" id="contact_name" name="name" required
                                        class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all @error('name') border-red-500 @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="John Doe">
                                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-on-surface-variant mb-2">Email Address *</label>
                                    <input type="email" id="contact_email" name="email" required
                                        class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all @error('email') border-red-500 @enderror"
                                        value="{{ old('email') }}"
                                        placeholder="john@example.com">
                                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-on-surface-variant mb-2">Phone Number</label>
                                <input type="tel" id="contact_phone" name="phone"
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all @error('phone') border-red-500 @enderror"
                                    value="{{ old('phone') }}"
                                    placeholder="+267 XX XXX XXX">
                                @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="contact_subject" class="block text-sm font-medium text-on-surface-variant mb-2">Subject *</label>
                                <select id="contact_subject" name="subject" required
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all @error('subject') border-red-500 @enderror">
                                    <option value="">Select a subject</option>
                                    <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="quote" {{ old('subject') == 'quote' ? 'selected' : '' }}>Quote Request</option>
                                    <option value="booking" {{ old('subject') == 'booking' ? 'selected' : '' }}>Booking Question</option>
                                    <option value="complaint" {{ old('subject') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                                    <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('subject') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="contact_message" class="block text-sm font-medium text-on-surface-variant mb-2">Message *</label>
                                <textarea id="contact_message" name="message" rows="5" required
                                    class="w-full px-4 py-3 rounded-lg bg-surface border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none @error('message') border-red-500 @enderror"
                                    placeholder="How can we help you?">{{ old('message') }}</textarea>
                                @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" class="w-full primary-gradient text-on-primary px-8 py-4 rounded-lg font-headline font-bold text-lg shadow-xl shadow-primary/20 hover:opacity-90 transition-opacity">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Business Hours -->
    <section class="py-24 bg-surface-container-low">
        <div class="max-w-4xl mx-auto px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-headline font-bold text-on-surface mb-4">Business Hours</h2>
                <p class="text-on-surface-variant">When you can reach us or visit our workshop</p>
            </div>
            <div class="glass-card p-8 rounded-2xl border border-outline-variant/10">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-headline font-bold text-on-surface mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">store</span>
                            Workshop Hours
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex justify-between text-on-surface-variant">
                                <span>Monday - Friday</span>
                                <span class="font-medium text-on-surface">8:00 AM - 5:30 PM</span>
                            </li>
                            <li class="flex justify-between text-on-surface-variant">
                                <span>Saturday</span>
                                <span class="font-medium text-on-surface">8:00 AM - 1:00 PM</span>
                            </li>
                            <li class="flex justify-between text-on-surface-variant">
                                <span>Sunday</span>
                                <span class="font-medium text-error">Closed</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-headline font-bold text-on-surface mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">emergency</span>
                            Emergency Service
                        </h3>
                        <p class="text-on-surface-variant mb-4">
                            Emergency glass repairs available 24/7 for fleet and commercial clients.
                        </p>
                        @if ($primaryPhone)
                        <a href="tel:{{ $primaryPhone->phone_number }}" class="text-primary font-bold hover:underline inline-flex items-center gap-2">
                            <span class="material-symbols-outlined">phone</span>
                            Emergency Hotline: {{ $primaryPhone->formatted_number }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Links -->
    <section class="py-24 bg-surface">
        <div class="max-w-7xl mx-auto px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-headline font-bold text-on-surface mb-4">Quick Actions</h2>
                <p class="text-on-surface-variant">Common tasks and services</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <a href="{{ route('quote') }}" class="group p-8 bg-surface-container-low rounded-2xl border border-outline-variant/10 hover:border-primary/30 hover:shadow-lg transition-all text-center">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-2xl text-primary">request_quote</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2">Get a Quote</h3>
                    <p class="text-on-surface-variant text-sm">Request a detailed quote for your glass needs</p>
                </a>
                <a href="{{ route('services') }}" class="group p-8 bg-surface-container-low rounded-2xl border border-outline-variant/10 hover:border-primary/30 hover:shadow-lg transition-all text-center">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-2xl text-primary">build</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2">Our Services</h3>
                    <p class="text-on-surface-variant text-sm">Explore our full range of glass services</p>
                </a>
                <a href="{{ route('gallery') }}" class="group p-8 bg-surface-container-low rounded-2xl border border-outline-variant/10 hover:border-primary/30 hover:shadow-lg transition-all text-center">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-2xl text-primary">photo_library</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2">View Gallery</h3>
                    <p class="text-on-surface-variant text-sm">See our recent project installations</p>
                </a>
            </div>
        </div>
    </section>
</x-layouts::site>
