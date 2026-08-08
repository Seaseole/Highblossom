<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Confirm Password - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet"
    />

    @vite(['resources/css/app.css'])

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
        .delay-100 {
            animation-delay: 100ms;
        }
        .delay-200 {
            animation-delay: 200ms;
        }
        .delay-300 {
            animation-delay: 300ms;
        }
        .delay-400 {
            animation-delay: 400ms;
        }
        .delay-500 {
            animation-delay: 500ms;
        }

        .glass-vivid {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="font-body min-h-[100dvh] bg-[#F9FAFB] text-[#18181B] antialiased selection:bg-[#DC2626] selection:text-white">
    <div class="grid min-h-[100dvh] grid-cols-1 overflow-hidden lg:grid-cols-2">
        <!-- Left Column - Branding -->
        <div class="relative hidden flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-[#DC2626] via-[#E11D48] to-[#F43F5E] p-12 lg:flex">
            <!-- Background Decoration -->
            <div class="absolute inset-0 opacity-20">
                <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <defs>
                        <pattern id="grid-light" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.2" />
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#grid-light)" />
                </svg>
            </div>

            <!-- Floating Orbs -->
            <div class="absolute top-[-10%] left-[-10%] h-80 w-80 animate-pulse rounded-full bg-white/20 blur-[100px]"></div>
            <div class="absolute right-[-10%] bottom-[-10%] h-80 w-80 rounded-full bg-black/10 blur-[100px]"></div>

            <div class="relative z-10 max-w-lg text-center">
                <div class="animate-fade-in-up">
                    <div class="group relative mx-auto mb-10 flex h-28 w-28 items-center justify-center overflow-hidden rounded-3xl border border-white/20 bg-white/10 shadow-2xl backdrop-blur-md">
                        <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"></div>
                        <span class="transform text-5xl font-bold text-white drop-shadow-lg transition-transform duration-500 group-hover:scale-110">H</span>
                    </div>
                </div>

                <h1 class="font-headline animate-fade-in-up mb-6 text-6xl leading-none font-bold tracking-tighter text-white delay-100">
                    {{ $companyName ?? config('app.name') }}
                </h1>

                <p class="animate-fade-in-up text-xl leading-relaxed font-medium text-white/80 delay-200">
                    Please confirm your password to continue with your request.
                </p>

                <div class="animate-fade-in-up mt-12 flex justify-center gap-4 delay-300">
                    <div class="h-1 w-12 rounded-full bg-white/30"></div>
                    <div class="h-1 w-4 rounded-full bg-white/30"></div>
                    <div class="h-1 w-4 rounded-full bg-white/30"></div>
                </div>
            </div>
        </div>

        <!-- Right Column - Confirm Password Form -->
        <div class="relative flex items-center justify-center p-6 lg:p-12">
            <!-- Mobile Background Accent -->
            <div class="absolute top-0 right-0 left-0 h-1/3 bg-gradient-to-b from-[#DC2626]/10 to-transparent lg:hidden"></div>

            <div class="relative z-10 w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="animate-fade-in-up mb-10 text-center lg:hidden">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#DC2626] to-[#B91C1C] shadow-xl">
                        <span class="text-2xl font-bold text-white">H</span>
                    </div>
                    <h1 class="font-headline text-3xl font-bold tracking-tight text-[#18181B]">
                        {{ $companyName ?? config('app.name') }}
                    </h1>
                </div>

                <div class="glass-vivid animate-fade-in-up rounded-[2rem] p-8 delay-100 lg:p-10">
                    <div class="mb-10">
                        <h2 class="font-headline mb-2 text-3xl font-bold tracking-tight text-[#18181B]">
                            Confirm Password
                        </h2>
                        <p class="text-[#71717A]">This is a secure area. Please confirm your password.</p>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                        @csrf

                        <div class="animate-fade-in-up delay-200">
                            <label
                                for="password"
                                class="mb-2 block px-1 text-xs font-bold tracking-widest text-[#71717A] uppercase"
                            >Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="w-full rounded-2xl border border-[#E4E4E7] bg-white/50 px-5 py-4 text-[#18181B] placeholder-[#A1A1AA] shadow-sm transition-all duration-300 focus:border-[#DC2626] focus:ring-2 focus:ring-[#DC2626]/20 focus:outline-none"
                                placeholder="••••••••"
                                required
                                autofocus
                            />
                            @error('password')
                                <p class="mt-2 px-1 text-sm font-medium text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="animate-fade-in-up pt-2 delay-300">
                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#DC2626] px-6 py-4 text-lg font-bold text-white shadow-xl shadow-[#DC2626]/20 transition-all duration-300 hover:bg-[#B91C1C] focus:ring-4 focus:ring-[#DC2626]/20 focus:outline-none active:scale-[0.98]"
                            >
                                <span>Confirm Password</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Info -->
                <p class="animate-fade-in-up mt-8 text-center text-xs font-medium tracking-[0.2em] text-[#A1A1AA] uppercase delay-500">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
