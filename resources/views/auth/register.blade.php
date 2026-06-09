<x-auth-premium 
    title="Register" 
    :companyName="config('app.name')" 
    brandingSubtitle="Create your account to get started with our platform."
>
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-[#18181B] mb-2 font-headline tracking-tight">Create Account</h2>
        <p class="text-[#71717A]">Fill in your details to get started</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="animate-fade-in-up delay-200">
            <label for="name" class="block text-xs font-bold text-[#71717A] uppercase tracking-widest mb-2 px-1">Full Name</label>
            <input
                id="name"
                type="text"
                name="name"
                class="w-full px-5 py-3.5 bg-white/50 border border-[#E4E4E7] rounded-2xl text-[#18181B] placeholder-[#A1A1AA] focus:outline-none focus:ring-2 focus:ring-[#DC2626]/20 focus:border-[#DC2626] transition-all duration-300 shadow-sm"
                placeholder="John Doe"
                required
                autofocus
                value="{{ old('name') }}"
            >
        </div>

        <div class="animate-fade-in-up delay-300">
            <label for="email" class="block text-xs font-bold text-[#71717A] uppercase tracking-widest mb-2 px-1">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                class="w-full px-5 py-3.5 bg-white/50 border border-[#E4E4E7] rounded-2xl text-[#18181B] placeholder-[#A1A1AA] focus:outline-none focus:ring-2 focus:ring-[#DC2626]/20 focus:border-[#DC2626] transition-all duration-300 shadow-sm"
                placeholder="you@example.com"
                required
                value="{{ old('email') }}"
            >
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="animate-fade-in-up delay-400">
                <div class="flex items-center justify-between mb-2 px-1 h-5">
                    <label for="password" class="block text-xs font-bold text-[#71717A] uppercase tracking-widest">Password</label>
                    <button type="button" data-generate-password="password" data-confirm-target="password_confirmation" class="text-[10px] font-bold text-[#DC2626] uppercase tracking-wider hover:text-[#B91C1C] transition-colors flex items-center gap-1 group">
                        <svg class="w-3 h-3 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>Generate</span>
                    </button>
                </div>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="w-full px-5 py-3.5 bg-white/50 border border-[#E4E4E7] rounded-2xl text-[#18181B] placeholder-[#A1A1AA] focus:outline-none focus:ring-2 focus:ring-[#DC2626]/20 focus:border-[#DC2626] transition-all duration-300 shadow-sm"
                    placeholder="••••••••"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    data-min="12"
                    data-max="64"
                    autocomplete="new-password"
                    required
                >
            </div>
            <div id="pw-hint" class="text-[10px] text-[#A1A1AA] italic hidden"></div>
            <div class="animate-fade-in-up delay-400">
                <div class="flex items-center mb-2 px-1 h-5">
                    <label for="password_confirmation" class="block text-xs font-bold text-[#71717A] uppercase tracking-widest">Confirm</label>
                </div>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="w-full px-5 py-3.5 bg-white/50 border border-[#E4E4E7] rounded-2xl text-[#18181B] placeholder-[#A1A1AA] focus:outline-none focus:ring-2 focus:ring-[#DC2626]/20 focus:border-[#DC2626] transition-all duration-300 shadow-sm"
                    placeholder="••••••••"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    autocomplete="new-password"
                    required
                >
            </div>
        </div>
        <div class="animate-fade-in-up delay-500 px-1">
            <div class="light-checkbox">
                <x-ui.checkbox name="terms" id="terms" required>
                    I agree to the <a href="{{route('terms')}}" class="text-[#DC2626] hover:text-[#B91C1C] font-bold">terms</a>
                </x-ui.checkbox>
            </div>
        </div>
        <div class="animate-fade-in-up delay-500 pt-2">
            <button
                type="submit"
                class="w-full bg-[#DC2626] text-white py-4 px-6 rounded-2xl font-bold text-lg hover:bg-[#B91C1C] focus:outline-none focus:ring-4 focus:ring-[#DC2626]/20 transition-all duration-300 active:scale-[0.98] shadow-xl shadow-[#DC2626]/20 group flex items-center justify-center gap-2"
            >
                <span>Create Account</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </form>

    <div class="mt-8 text-center animate-fade-in-up delay-500">
        <p class="text-[#71717A] font-medium">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[#DC2626] hover:text-[#B91C1C] font-bold transition-colors underline decoration-2 underline-offset-4 decoration-[#DC2626]/20 hover:decoration-[#DC2626]">
                Sign in
            </a>
        </p>
    </div>

<script>
  document.getElementById('password').addEventListener('input', function () {
    const val = this.value;
    const min = parseInt(this.dataset.min);
    const max = parseInt(this.dataset.max);
    const hint = document.getElementById('pw-hint');
    const errors = [];

    if (val.length < min)             errors.push('At least ' + min + ' characters');
    if (val.length > max)             errors.push('At most ' + max + ' characters');
    if (!/[a-z]/.test(val))         errors.push('One lowercase letter');
    if (!/[A-Z]/.test(val))         errors.push('One uppercase letter');
    if (!/[0-9]/.test(val))         errors.push('One number');
    if (!/[^a-zA-Z0-9]/.test(val))  errors.push('One symbol');

    hint.textContent = errors.join(' · ');
    hint.classList.toggle('hidden', errors.length === 0);
});
</script>
</x-auth-premium>
