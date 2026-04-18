<footer class="w-full bg-surface-container-lowest border-t border-outline-variant/10">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-12 px-8 py-16 max-w-7xl mx-auto">
        <div class="col-span-1 md:col-span-1">
            <div class="text-lg font-bold text-on-surface font-headline mb-6">{{ $logoText }}</div>
            <p class="text-on-surface-variant text-sm font-body leading-relaxed">Precision automotive glass and heavy machinery specialist. Safety first, quality always.</p>
        </div>
        <div>
            <h4 class="font-headline font-bold text-on-surface mb-6">Services</h4>
            <ul class="space-y-4">
                <li><a href="{{ route('services') }}" class="text-on-surface-variant hover:text-primary transition-colors text-sm font-body">Automotive Glass</a></li>
                <li><a href="{{ route('services') }}" class="text-on-surface-variant hover:text-primary transition-colors text-sm font-body">Heavy Machinery</a></li>
                <li><a href="{{ route('services') }}" class="text-on-surface-variant hover:text-primary transition-colors text-sm font-body">Fleet Services</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-headline font-bold text-on-surface mb-6">Company</h4>
            <ul class="space-y-4">
                <li><a href="{{ route('contact') }}" class="text-on-surface-variant hover:text-primary transition-colors text-sm font-body">Contact Us</a></li>
            </ul>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-8 py-8 border-t border-outline-variant/10">
        <p class="text-sm text-on-surface-variant text-center md:text-left">
            © {{ date('Y') }} {{ $companyName }}. Precision Refraction.
        </p>
    </div>
</footer>
