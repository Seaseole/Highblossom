<x-auth-premium 
    :title="__('auth.login.title')" 
    :companyName="config('app.name')" 
    :brandingSubtitle="__('auth.login.welcome_back')"
>
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-[#18181B] mb-2 font-headline tracking-tight">{{ __('auth.login.heading') }}</h2>
        <p class="text-[#71717A]">{{ __('auth.login.subheading') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="animate-fade-in-up delay-200">
            <label for="email" class="block text-xs font-bold text-[#71717A] uppercase tracking-widest mb-2 px-1">{{ __('auth.login.email_label') }}</label>
            <input
                id="email"
                type="email"
                name="email"
                class="w-full px-5 py-4 bg-white/50 border border-[#E4E4E7] rounded-2xl text-[#18181B] placeholder-[#A1A1AA] focus:outline-none focus:ring-2 focus:ring-[#DC2626]/20 focus:border-[#DC2626] transition-all duration-300 shadow-sm"
                placeholder="{{ __('auth.login.email_placeholder') }}"
                required
                autofocus
                autocomplete="username"
                value="{{ old('email') }}"
            >
        </div>
        <div class="animate-fade-in-up delay-300">
            <div class="flex items-center justify-between mb-2 px-1">
                <label for="password" class="block text-xs font-bold text-[#71717A] uppercase tracking-widest">{{ __('auth.login.password_label') }}</label>
                <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#DC2626] hover:text-[#B91C1C] transition-colors uppercase tracking-widest">
                    {{ __('auth.login.forgot_password') }}
                </a>
            </div>
            <input
                id="password"
                type="password"
                name="password"
                class="w-full px-5 py-4 bg-white/50 border border-[#E4E4E7] rounded-2xl text-[#18181B] placeholder-[#A1A1AA] focus:outline-none focus:ring-2 focus:ring-[#DC2626]/20 focus:border-[#DC2626] transition-all duration-300 shadow-sm"
                placeholder="{{ __('auth.login.password_placeholder') }}"
                required
                autocomplete="current-password"
            >
        </div>

        <div class="flex items-center justify-between animate-fade-in-up delay-400 px-1">
            <div class="light-checkbox">
                <x-ui.checkbox name="remember" label="{{ __('auth.login.remember_me') }}" />
            </div>
        </div>

        <div class="animate-fade-in-up delay-500 pt-2 flex flex-col gap-4">
            <button
                type="submit"
                class="w-full bg-[#DC2626] text-white py-4 px-6 rounded-2xl font-bold text-lg hover:bg-[#B91C1C] focus:outline-none focus:ring-4 focus:ring-[#DC2626]/20 transition-all duration-300 active:scale-[0.98] shadow-xl shadow-[#DC2626]/20 group flex items-center justify-center gap-2"
            >
                <span>{{ __('auth.login.sign_in_button') }}</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>

            <div class="relative flex items-center py-2">
                <div class="flex-grow border-t border-[#E4E4E7]"></div>
                <span class="flex-shrink mx-4 text-xs font-bold text-[#A1A1AA] uppercase tracking-widest">Or</span>
                <div class="flex-grow border-t border-[#E4E4E7]"></div>
            </div>

            <button
                type="button"
                onclick="signInWithPasskey()"
                class="w-full bg-white border-2 border-[#E4E4E7] text-[#18181B] py-4 px-6 rounded-2xl font-bold text-lg hover:bg-[#F9FAFB] hover:border-[#DC2626]/30 focus:outline-none focus:ring-4 focus:ring-[#DC2626]/10 transition-all duration-300 active:scale-[0.98] flex items-center justify-center gap-3 group"
            >
                <svg class="w-6 h-6 text-[#71717A] group-hover:text-[#DC2626] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                <span>Sign in with Passkey</span>
            </button>
        </div>
    </form>

    <script>
        async function signInWithPasskey() {
            console.log('Starting passkey login...');
            try {
                const response = await window.Passkeys.verify();
                console.log('Passkey login successful', response);
                
                // Manual redirect if the library doesn't handle it
                if (response && response.redirect) {
                    window.location.href = response.redirect;
                }
            } catch (e) {
                if (e.name === 'NotAllowedError' || e.name === 'AbortError') {
                    console.log('Passkey login cancelled by user.');
                } else {
                    console.error('Passkey login failed:', e);
                    alert('Failed to sign in with passkey. Please try again or use your password.');
                }
            }
        }
    </script>

    <div class="mt-10 text-center animate-fade-in-up delay-500">
        <p class="text-[#71717A] font-medium">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[#DC2626] hover:text-[#B91C1C] font-bold transition-colors underline decoration-2 underline-offset-4 decoration-[#DC2626]/20 hover:decoration-[#DC2626]">
                Register
            </a>
        </p>
    </div>
</x-auth-premium>
