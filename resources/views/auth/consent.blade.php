<x-auth-premium
    title="Accept Terms & Privacy"
    :companyName="config('app.name')"
    brandingSubtitle="One quick step before you can access your account."
>
    <div class="mb-10">
        <h2 class="font-headline mb-2 text-3xl font-bold tracking-tight text-[#18181B]">Terms & Privacy</h2>
        <p class="text-[#71717A]">We haven't recorded your consent yet. Please review and accept to continue.</p>
    </div>

    <x-ui.alert-banner />

    <form method="POST" action="{{ route('consent.store') }}" class="space-y-5">
        @csrf

        <div class="animate-fade-in-up px-1">
            <div class="light-checkbox space-y-2">
                <x-ui.checkbox name="terms" id="terms" required>
                    I agree to the
                    <a href="{{ route('terms') }}" class="font-bold text-[#DC2626] hover:text-[#B91C1C]" target="_blank">terms</a>
                </x-ui.checkbox>
                <x-ui.checkbox name="privacy" id="privacy" required>
                    I agree to the
                    <a href="{{ route('privacy') }}" class="font-bold text-[#DC2626] hover:text-[#B91C1C]" target="_blank">privacy policy</a>
                </x-ui.checkbox>
            </div>
            <x-ui.field-errors field="terms" />
            <x-ui.field-errors field="privacy" />
        </div>

        <div class="animate-fade-in-up pt-2">
            <button
                type="submit"
                data-submit-pending-button
                class="group flex w-full items-center justify-center gap-2 rounded-2xl bg-[#DC2626] px-6 py-4 text-lg font-bold text-white shadow-xl shadow-[#DC2626]/20 transition-all duration-300 hover:bg-[#B91C1C] focus:ring-4 focus:ring-[#DC2626]/20 focus:outline-none active:scale-[0.98] disabled:cursor-wait disabled:opacity-60"
            >
                <span data-idle-state>Accept & Continue</span>
                <span data-pending-state class="hidden">
                    <span class="flex items-center gap-2">
                        <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('auth.consent.saving') }}
                    </span>
                </span>
                <svg data-idle-state class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
