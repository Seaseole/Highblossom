<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.8s var(--ease-out-expo) forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }

        .glass-vivid {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
        }

        /* Override checkbox for light theme */
        .light-checkbox .ui-checkbox-label {
            color: #71717A !important;
        }
        .light-checkbox:hover .ui-checkbox-label {
            color: #18181B !important;
        }
    </style>
</head>
<body class="min-h-[100dvh] bg-[#F9FAFB] text-[#18181B] font-body selection:bg-[#DC2626] selection:text-white antialiased">
    <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[100dvh] overflow-hidden">
        <!-- Toast Notifications -->
        <x-ui.toaster />
        <!-- Left Column - Branding -->
        <div class="hidden lg:flex flex-col justify-center items-center p-12 bg-gradient-to-br from-[#DC2626] via-[#E11D48] to-[#F43F5E] relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 opacity-20">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <defs>
                        <pattern id="grid-light" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.2"/>
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#grid-light)"/>
                </svg>
            </div>
            
            <!-- Floating Orbs -->
            <div class="absolute top-[-10%] left-[-10%] w-80 h-80 bg-white/20 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-80 h-80 bg-black/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 text-center max-w-lg">
                <div class="animate-fade-in-up">
                    <div class="w-28 h-28 bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 flex items-center justify-center mx-auto mb-10 shadow-2xl relative group overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <span class="text-5xl font-bold text-white drop-shadow-lg transform group-hover:scale-110 transition-transform duration-500">H</span>
                    </div>
                </div>
                
                <h1 class="text-6xl font-bold text-white mb-6 tracking-tighter font-headline animate-fade-in-up delay-100 leading-none">
                    {{ $companyName ?? config('app.name') }}
                </h1>
                
                <p class="text-xl text-white/80 leading-relaxed animate-fade-in-up delay-200 font-medium">
                    Create your account to get started with our platform.
                </p>
                
                <div class="mt-12 flex gap-4 justify-center animate-fade-in-up delay-300">
                    <div class="h-1 w-12 bg-white/30 rounded-full"></div>
                    <div class="h-1 w-4 bg-white/30 rounded-full"></div>
                    <div class="h-1 w-4 bg-white/30 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Right Column - Register Form -->
        <div class="flex items-center justify-center p-6 lg:p-12 relative">
            <!-- Mobile Background Accent -->
            <div class="lg:hidden absolute top-0 left-0 right-0 h-1/3 bg-gradient-to-b from-[#DC2626]/10 to-transparent"></div>

            <div class="w-full max-w-md relative z-10">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-10 animate-fade-in-up">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#DC2626] to-[#B91C1C] rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                        <span class="text-2xl font-bold text-white">H</span>
                    </div>
                    <h1 class="text-3xl font-bold text-[#18181B] font-headline tracking-tight">{{ $companyName ?? config('app.name') }}</h1>
                </div>

                <div class="glass-vivid rounded-[2rem] p-8 lg:p-10 animate-fade-in-up delay-100">
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
                                 @php
                                    $pwRules = App\Providers\AppServiceProvider::passwordRules()
                                @endphp
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
                                    passwordrules="{{ $pwRules->toPasswordRulesString() }}"
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
                                    passwordrules="{{ $pwRules->toPasswordRulesString() }}"
                                    autocomplete="new-password"
                                    required
                                >
                            </div>
                        </div>
                        <div class="animate-fade-in-up delay-500 px-1">
                            <div class="light-checkbox">
                                <x-ui.checkbox name="terms" id="terms" required>
                                    I agree to the <a href="#" class="text-[#DC2626] hover:text-[#B91C1C] font-bold">terms</a>
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
                </div>
                
                <!-- Footer Info -->
                <p class="mt-8 text-center text-xs text-[#A1A1AA] font-medium uppercase tracking-[0.2em] animate-fade-in-up delay-500">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
<script>
  document.getElementById('password','password_confirmation').addEventListener('input', function () {
    const val = this.value;
    const hint = document.getElementById('pw-hint');
    const errors = [];

    if (val.length < 8)             errors.push('At least 8 characters');
    if (val.length > 12)            errors.push('At most 12 characters');
    if (!/[a-z]/.test(val))         errors.push('One lowercase letter');
    if (!/[A-Z]/.test(val))         errors.push('One uppercase letter');
    if (!/[0-9]/.test(val))         errors.push('One number');
    if (!/[^a-zA-Z0-9]/.test(val))  errors.push('One symbol');

    hint.textContent = errors.join(' · ');
    hint.classList.toggle('hidden', errors.length === 0);
});
</script>
