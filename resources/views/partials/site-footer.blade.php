<footer class="w-full bg-[#0A0A0F] border-t border-white/5">
    {{-- Main Footer Content --}}
    <div class="max-w-[1400px] mx-auto px-6 lg:px-8 py-16 lg:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12">
            {{-- Brand Column --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="text-xl font-bold text-[#FAFAFA] font-headline tracking-tight hover:text-[#DC2626] transition-colors">
                    {{ $logoText }}
                </a>
                <p class="mt-4 text-[#A1A1AA] text-sm leading-relaxed max-w-xs">
                    Premium automotive glass repair, restoration, and replacement for Gaborone's vehicle owners and commercial fleets.
                </p>
                <div class="mt-6 flex items-center gap-4">
                    @if($primaryPhone)
                    <a href="tel:{{ $primaryPhone->phone_number }}" class="text-[#A1A1AA] hover:text-[#DC2626] transition-colors" aria-label="Call us">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </a>
                    @endif
                    <a href="{{ route('contact') }}" class="text-[#A1A1AA] hover:text-[#DC2626] transition-colors" aria-label="Email us">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Services Column --}}
            <div>
                <h4 class="font-headline font-semibold text-[#FAFAFA] mb-6 text-sm tracking-wide uppercase">Services</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('services') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                            Windscreen Replacement
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('services') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                            Side & Rear Windows
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('services') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                            Heavy Machinery
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('services') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                            Fleet Services
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('services') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                            Mobile Service
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Company Column --}}
            <div>
                <h4 class="font-headline font-semibold text-[#FAFAFA] mb-6 text-sm tracking-wide uppercase">Company</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gallery') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                            Our Work
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blog.index') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                            Blog
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('quote') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                            Get a Quote
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                            Contact Us
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Contact Column --}}
            <div>
                <h4 class="font-headline font-semibold text-[#FAFAFA] mb-6 text-sm tracking-wide uppercase">Contact</h4>
                <div class="space-y-4">
                    @if($primaryPhone)
                    <div class="glass-card p-4 rounded-xl">
                        <div class="text-[#DC2626] text-xs font-semibold uppercase tracking-wider mb-1">Phone</div>
                        <a href="tel:{{ $primaryPhone->phone_number }}" class="text-[#FAFAFA] hover:text-[#DC2626] transition-colors font-headline font-semibold">
                            {{ $primaryPhone->formatted_number }}
                        </a>
                    </div>
                    @endif

                    <div class="glass-card p-4 rounded-xl">
                        <div class="text-[#DC2626] text-xs font-semibold uppercase tracking-wider mb-1">Location</div>
                        <p class="text-[#FAFAFA] text-sm leading-relaxed">
                            {{ $companyAddress }}
                        </p>
                    </div>

                    <div class="glass-card p-4 rounded-xl">
                        <div class="text-[#DC2626] text-xs font-semibold uppercase tracking-wider mb-1">Hours</div>
                        <p class="text-[#FAFAFA] text-sm">
                            Mon-Fri: 8:00 AM - 5:30 PM<br>
                            Sat: 8:00 AM - 1:00 PM
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-white/5">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-[#71717A]">
                    © {{ date('Y') }} {{ $companyName }}. All rights reserved.
                </p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                        Privacy Policy
                    </a>
                    <a href="{{ route('home') }}" class="text-[#71717A] hover:text-[#FAFAFA] transition-colors text-sm">
                        Terms of Service
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
