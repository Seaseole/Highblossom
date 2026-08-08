<div
    x-data="{ open: false }"
    x-init="
        setTimeout(() => {
            if (! document.cookie.split('; ').some((c) => c.startsWith('cookieConsent='))) open = true;
        }, 1000)
    "
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-full"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-full"
    class="fixed right-6 bottom-6 left-6 z-[9999] md:right-6 md:left-auto md:w-[400px]"
    style="display: none"
>
    <div class="glass-card rounded-2xl border border-white/10 bg-[#0A0A0F]/80 p-8 shadow-2xl backdrop-blur-2xl">
        <h2 class="font-headline mb-4 text-xl font-bold text-[#FAFAFA]">Privacy Preference</h2>
        <p class="mb-8 text-sm leading-relaxed text-[#A1A1AA]">
            We use cookies to enhance your experience, analyze site traffic, and provide personalized services. By
            clicking "Accept", you agree to our
            <a href="<?php echo e(route('privacy')); ?>" class="font-semibold text-[#DC2626] hover:underline">Privacy Policy</a> and
            <a href="<?php echo e(route('terms')); ?>" class="font-semibold text-[#DC2626] hover:underline">Terms of Service</a>.
        </p>

        <div class="flex flex-col gap-3">
            <button
                @click="
                    open = false;
                    document.cookie = 'cookieConsent=true; max-age=31536000; path=/; SameSite=Lax';
                "
                class="w-full rounded-full bg-[#DC2626] px-6 py-3 text-sm font-bold text-[#FAFAFA] shadow-[0_0_30px_rgba(220,38,38,0.4)] transition-all hover:scale-[1.02] hover:bg-[#B91C1C] active:scale-[0.98]"
            >
                Accept All
            </button>
            <button
                @click="
                    open = false;
                    document.cookie = 'cookieConsent=false; max-age=31536000; path=/; SameSite=Lax';
                "
                class="w-full rounded-full bg-white/5 px-6 py-3 text-sm font-semibold text-[#FAFAFA] transition-all hover:bg-white/10"
            >
                Decline
            </button>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\partials\cookie-consent.blade.php ENDPATH**/ ?>