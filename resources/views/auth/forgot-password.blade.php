<x-auth-premium
    title="Forgot Password"
    :companyName="config('app.name')"
    brandingSubtitle="Forgot your password? No worries. Enter your email and we'll send you a reset link."
>
    <div class="mb-10">
        <h2 class="font-headline mb-2 text-3xl font-bold tracking-tight text-[#18181B]">Forgot Password</h2>
        <p class="text-[#71717A]">Enter your email to receive a password reset link</p>
    </div>

    @if (session('status'))
        <div class="animate-fade-in-up mb-6 rounded-2xl border border-green-500/20 bg-green-500/10 p-4 delay-200">
            <p class="text-sm font-medium text-green-600">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div class="animate-fade-in-up delay-200">
            <label for="email" class="mb-2 block px-1 text-xs font-bold tracking-widest text-[#71717A] uppercase"
                >Email</label>
            <input
                id="email"
                type="email"
                name="email"
                class="w-full rounded-2xl border border-[#E4E4E7] bg-white/50 px-5 py-4 text-[#18181B] placeholder-[#A1A1AA] shadow-sm transition-all duration-300 focus:border-[#DC2626] focus:ring-2 focus:ring-[#DC2626]/20 focus:outline-none"
                placeholder="you@example.com"
                required
                autofocus
                value="{{ old('email') }}"
            />
            @error('email')
                <div class="mt-2 space-y-1 px-1">
                    @foreach ($errors->get('email') as $error)
                        <p class="flex items-start gap-1.5 text-sm font-medium text-[#DC2626]">
                            <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $error }}</span>
                        </p>
                    @endforeach
                </div>
            @enderror
        </div>

        <div class="animate-fade-in-up pt-2 delay-300">
            <button
                type="submit"
                class="group flex w-full items-center justify-center gap-2 rounded-2xl bg-[#DC2626] px-6 py-4 text-lg font-bold text-white shadow-xl shadow-[#DC2626]/20 transition-all duration-300 hover:bg-[#B91C1C] focus:ring-4 focus:ring-[#DC2626]/20 focus:outline-none active:scale-[0.98]"
            >
                <span>Send Reset Link</span>
                <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
