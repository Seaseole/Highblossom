<x-auth-premium
    title="Accept Terms & Privacy"
    :companyName="config('app.name')"
    brandingSubtitle="One quick step before you can access your account."
>
    <div class="mb-10">
        <h2 class="font-headline mb-2 text-3xl font-bold tracking-tight text-[#18181B]">Terms & Privacy</h2>
        <p class="text-[#71717A]">We haven't recorded your consent yet. Please review and accept to continue.</p>
    </div>

    <form method="POST" action="{{ route('consent.store') }}" class="space-y-5">
        @csrf

        <div class="animate-fade-in-up px-1">
            <div class="light-checkbox space-y-2">
                <x-ui.checkbox name="terms" id="terms" required>
                    I agree to the
                    <a href="{{ route('terms') }}" class="font-bold text-[#DC2626] hover:text-[#B91C1C]" target="_blank">terms</a>
                </x-ui.checkbox>
                @error('terms')
                    <p class="text-xs font-semibold text-[#DC2626]">{{ $message }}</p>
                @enderror
                <x-ui.checkbox name="privacy" id="privacy" required>
                    I agree to the
                    <a href="{{ route('privacy') }}" class="font-bold text-[#DC2626] hover:text-[#B91C1C]" target="_blank">privacy policy</a>
                </x-ui.checkbox>
                @error('privacy')
                    <p class="text-xs font-semibold text-[#DC2626]">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="animate-fade-in-up pt-2">
            <button
                type="submit"
                class="group flex w-full items-center justify-center gap-2 rounded-2xl bg-[#DC2626] px-6 py-4 text-lg font-bold text-white shadow-xl shadow-[#DC2626]/20 transition-all duration-300 hover:bg-[#B91C1C] focus:ring-4 focus:ring-[#DC2626]/20 focus:outline-none active:scale-[0.98]"
            >
                <span>Accept & Continue</span>
                <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </form>

    <div class="animate-fade-in-up mt-8 text-center">
        <p class="font-medium text-[#71717A]">
            Don't want to accept?
            <form method="POST" action="{{ route('logout') }}" class="mt-3 inline">
                @csrf
                <button
                    type="submit"
                    class="font-bold text-[#18181B] underline decoration-[#A1A1AA]/40 decoration-2 underline-offset-4 transition-colors hover:decoration-[#18181B]"
                >
                    Log out
                </button>
            </form>
        </p>
    </div>
</x-auth-premium>
