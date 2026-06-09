<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>

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
    <x-ui.toaster />
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
                    {{ $brandingSubtitle ?? 'Welcome back to our platform.' }}
                </p>

                <div class="mt-12 flex gap-4 justify-center animate-fade-in-up delay-300">
                    <div class="h-1 w-12 bg-white/30 rounded-full"></div>
                    <div class="h-1 w-4 bg-white/30 rounded-full"></div>
                    <div class="h-1 w-4 bg-white/30 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Right Column - Content -->
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
                    {{ $slot }}
                </div>

                <!-- Footer Info -->
                <p class="mt-8 text-center text-xs text-[#A1A1AA] font-medium uppercase tracking-[0.2em] animate-fade-in-up delay-500">
                    &copy; {{ date('Y') }} {{ App\Models\CompanySetting::get('company_name', 'Highblossom Pty Ltd') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
