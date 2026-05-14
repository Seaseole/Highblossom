<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Two-Factor Authentication - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
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
        @keyframes caret-blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.8s var(--ease-out-expo) forwards;
            opacity: 0;
        }
        .caret-blink {
            animation: caret-blink 1s step-end infinite;
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
    </style>
</head>
<body class="min-h-[100dvh] bg-[#F9FAFB] text-[#18181B] font-body selection:bg-[#DC2626] selection:text-white antialiased">
    <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[100dvh] overflow-hidden">
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
                    Two-factor authentication adds an extra layer of security to your account.
                </p>
                
                <div class="mt-12 flex gap-4 justify-center animate-fade-in-up delay-300">
                    <div class="h-1 w-12 bg-white/30 rounded-full"></div>
                    <div class="h-1 w-4 bg-white/30 rounded-full"></div>
                    <div class="h-1 w-4 bg-white/30 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Right Column - Two-Factor Form -->
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
                    <div class="mb-10 text-center">
                        <div class="w-16 h-16 bg-[#DC2626]/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-[#18181B] mb-2 font-headline tracking-tight">Two-Factor Auth</h2>
                        <p class="text-[#71717A]">Enter the 6-digit code from your app</p>
                    </div>

                    <form method="POST" action="{{ route('two-factor.login') }}" id="two-factor-form" class="space-y-8" 
                        x-data="{ code: '', submitting: false }" 
                        x-init="$nextTick(() => { $refs.codeInput.focus() }); $watch('code', value => { if (value.length === 6 && !submitting) { submitting = true; $nextTick(() => { $el.submit() }) } })" 
                        @submit="submitting = true"
                        x-cloak>
                        @csrf

                        <div class="relative animate-fade-in-up delay-200">
                            <label for="code" class="block text-xs font-bold text-[#71717A] uppercase tracking-widest mb-6 text-center px-1">Authentication Code</label>
                            
                            <!-- Real Hidden Input -->
                            <input
                                id="code"
                                type="text"
                                name="code"
                                x-model="code"
                                x-ref="codeInput"
                                maxlength="6"
                                required
                                autofocus
                                inputmode="numeric"
                                pattern="[0-9]*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-text z-10"
                                autocomplete="one-time-code"
                                @input="code = code.replace(/\D/g, '').slice(0, 6)"
                                :readonly="submitting"
                            >

                            <!-- Visual Representation -->
                            <div class="flex justify-between gap-2 sm:gap-3 relative z-0" :class="{ 'opacity-50 pointer-events-none transition-opacity duration-300': submitting }">
                                <template x-for="i in [0,1,2,3,4,5]" :key="i">
                                    <div 
                                        class="w-12 h-16 flex items-center justify-center text-3xl font-bold bg-white/50 border transition-all duration-300 rounded-2xl shadow-sm"
                                        :class="{
                                            'border-[#DC2626] ring-4 ring-[#DC2626]/10': code.length === i && !submitting,
                                            'border-[#E4E4E7]': code.length !== i && !@json($errors->has('code')) || submitting,
                                            'border-red-500 ring-4 ring-red-500/10': @json($errors->has('code')) && !submitting,
                                            'text-[#18181B]': code[i],
                                            'text-transparent': !code[i]
                                        }"
                                    >
                                        <span x-text="code[i] || ''" class="relative z-10"></span>
                                        
                                        <!-- Blinking Caret -->
                                        <div x-show="code.length === i && !submitting" 
                                             class="absolute w-[2px] h-8 bg-[#DC2626] caret-blink">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Loading Indicator -->
                            <div x-show="submitting" x-transition class="mt-8 flex flex-col items-center justify-center gap-3 animate-fade-in-up">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 animate-spin text-[#DC2626]" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-sm font-bold text-[#DC2626] uppercase tracking-[0.2em]">Verifying Code...</span>
                                </div>
                            </div>
                            
                            @error('code')
                                <div x-show="!submitting" class="mt-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 animate-fade-in-up">
                                    <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-red-700 font-medium leading-tight">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </form>

                    @if (session('link'))
                        <div class="mt-6 text-center animate-fade-in-up delay-400">
                            {{ session('link') }}
                        </div>
                    @endif
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
