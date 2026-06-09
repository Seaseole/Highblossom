<x-auth-premium 
    title="Forgot Password" 
    :companyName="config('app.name')" 
    brandingSubtitle="Forgot your password? No worries. Enter your email and we'll send you a reset link."
>
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-[#18181B] mb-2 font-headline tracking-tight">Forgot Password</h2>
        <p class="text-[#71717A]">Enter your email to receive a password reset link</p>
    </div>

    @if (session('status'))
        <div class="mb-6 bg-green-500/10 border border-green-500/20 rounded-2xl p-4 animate-fade-in-up delay-200">
            <p class="text-sm text-green-600 font-medium">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div class="animate-fade-in-up delay-200">
            <label for="email" class="block text-xs font-bold text-[#71717A] uppercase tracking-widest mb-2 px-1">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                class="w-full px-5 py-4 bg-white/50 border border-[#E4E4E7] rounded-2xl text-[#18181B] placeholder-[#A1A1AA] focus:outline-none focus:ring-2 focus:ring-[#DC2626]/20 focus:border-[#DC2626] transition-all duration-300 shadow-sm"
                placeholder="you@example.com"
                required
                autofocus
                value="{{ old('email') }}"
            >
            @error('email')
                <div class="mt-2 space-y-1 px-1">
                    @foreach($errors->get('email') as $error)
                        <p class="text-sm text-[#DC2626] font-medium flex items-start gap-1.5">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $error }}</span>
                        </p>
                    @endforeach
                </div>
            @enderror
        </div>

        <div class="animate-fade-in-up delay-300 pt-2">
            <button
                type="submit"
                class="w-full bg-[#DC2626] text-white py-4 px-6 rounded-2xl font-bold text-lg hover:bg-[#B91C1C] focus:outline-none focus:ring-4 focus:ring-[#DC2626]/20 transition-all duration-300 active:scale-[0.98] shadow-xl shadow-[#DC2626]/20 group flex items-center justify-center gap-2"
            >
                <span>Send Reset Link</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </form>

    <div class="mt-10 text-center animate-fade-in-up delay-400">
        <p class="text-[#71717A] font-medium">
            Remember your password?
            <a href="{{ route('login') }}" class="text-[#DC2626] hover:text-[#B91C1C] font-bold transition-colors underline decoration-2 underline-offset-4 decoration-[#DC2626]/20 hover:decoration-[#DC2626]">
                Sign in
            </a>
        </p>
    </div>
</x-auth-premium>
