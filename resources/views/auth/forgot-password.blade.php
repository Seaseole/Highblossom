<x-auth-premium
    title="Forgot Password"
    :companyName="config('app.name')"
    brandingSubtitle="Forgot your password? No worries. Enter your email and we'll send you a reset link."
>
    <div class="mb-10">
        <h2 class="font-headline mb-2 text-3xl font-bold tracking-tight text-[#18181B]">Forgot Password</h2>
        <p class="text-[#71717A]">Enter your email to receive a password reset link</p>
    </div>

    <x-ui.alert-banner />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        @php $emailErrors = \App\Support\AuthNotification::fieldMessages('email'); @endphp

        <div class="animate-fade-in-up delay-200">
            <label for="email" class="mb-2 block px-1 text-xs font-bold tracking-widest text-[#71717A] uppercase"
                >Email</label>
            <input
                id="email"
                type="email"
                name="email"
                class="w-full rounded-2xl border bg-white/50 px-5 py-4 text-[#18181B] shadow-sm transition-all duration-300 placeholder-[#A1A1AA] focus:ring-2 focus:border-[#DC2626] focus:ring-[#DC2626]/20 focus:outline-none {{ $errors->has('email') ? 'border-[#DC2626]' : 'border-[#E4E4E7]' }}"
                placeholder="you@example.com"
                required
                autofocus
                value="{{ old('email') }}"
                @if ($emailErrors !== []) aria-describedby="email-error" aria-invalid="true" @endif
            />
            <x-ui.field-errors field="email" />
        </div>

        <div class="animate-fade-in-up pt-2 delay-300">
            <button
                type="submit"
                data-submit-pending-button
                class="group flex w-full items-center justify-center gap-2 rounded-2xl bg-[#DC2626] px-6 py-4 text-lg font-bold text-white shadow-xl shadow-[#DC2626]/20 transition-all duration-300 hover:bg-[#B91C1C] focus:ring-4 focus:ring-[#DC2626]/20 focus:outline-none active:scale-[0.98] disabled:cursor-wait disabled:opacity-60"
            >
                <span data-idle-state>Send Reset Link</span>
                <span data-pending-state class="hidden">
                    <span class="flex items-center gap-2">
                        <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('auth.forgot_password.sending') }}
                    </span>
                </span>
                <svg data-idle-state class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </form>

    <div class="animate-fade-in-up mt-10 text-center delay-400">
        <p class="font-medium text-[#71717A]">
            Remember your password?
            <a
                href="{{ route('login') }}"
                class="font-bold text-[#DC2626] underline decoration-[#DC2626]/20 decoration-2 underline-offset-4 transition-colors hover:text-[#B91C1C] hover:decoration-[#DC2626]"
            >
                Sign in
            </a>
        </p>
    </div>
</x-auth-premium>
