<div x-data="{ open: false }" 
     x-init="setTimeout(() => { if (!localStorage.getItem('cookieConsent')) open = true }, 1000)"
     x-show="open"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-full"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-full"
     class="fixed bottom-6 left-6 right-6 md:left-auto md:right-6 md:w-[400px] z-[9999]"
     style="display: none;">
    
    <div class="glass-card rounded-2xl p-8 border border-white/10 shadow-2xl backdrop-blur-2xl bg-[#0A0A0F]/80">
        <h2 class="text-xl font-bold font-headline text-[#FAFAFA] mb-4">Privacy Preference</h2>
        <p class="text-[#A1A1AA] text-sm leading-relaxed mb-8">
            We use cookies to enhance your experience, analyze site traffic, and provide personalized services. By clicking "Accept", you agree to our 
            <a href="{{ route('privacy') }}" class="text-[#DC2626] font-semibold hover:underline">Privacy Policy</a> and 
            <a href="{{ route('terms') }}" class="text-[#DC2626] font-semibold hover:underline">Terms of Service</a>.
        </p>
        
        <div class="flex flex-col gap-3">
            <button @click="open = false; localStorage.setItem('cookieConsent', 'true')"
                    class="w-full py-3 px-6 rounded-full bg-[#DC2626] hover:bg-[#B91C1C] text-[#FAFAFA] font-bold text-sm transition-all hover:scale-[1.02] active:scale-[0.98] shadow-[0_0_30px_rgba(220,38,38,0.4)]">
                Accept All
            </button>
            <button @click="open = false"
                    class="w-full py-3 px-6 rounded-full bg-white/5 hover:bg-white/10 text-[#FAFAFA] font-semibold text-sm transition-all">
                Decline
            </button>
        </div>
    </div>
</div>
