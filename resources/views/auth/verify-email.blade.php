<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-[100dvh] bg-[#0A0A0F]">
    <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[100dvh]">
        <!-- Left Column - Branding -->
        <div class="hidden lg:flex flex-col justify-center items-center p-12 bg-gradient-to-br from-[#DC2626] to-[#991B1B] relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <defs>
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#grid)"/>
                </svg>
            </div>
            <div class="relative z-10 text-center">
                <div class="mb-8">
                    <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl font-bold text-white">H</span>
                    </div>
                </div>
                <h1 class="text-5xl font-bold text-white mb-4 tracking-tight font-headline">{{ $companyName ?? config('app.name') }}</h1>
                <p class="text-xl text-white/55 leading-relaxed max-w-md">
                    Thanks for signing up! Please verify your email address to get started.
                </p>
            </div>
        </div>

        <!-- Right Column - Verify Email Form -->
        <div class="flex items-center justify-center p-8 lg:p-12">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="w-16 h-16 bg-[#DC2626] rounded-xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-white">H</span>
                    </div>
                    <h1 class="text-2xl font-bold text-[#FAFAFA] font-headline">{{ $companyName ?? config('app.name') }}</h1>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl shadow-2xl shadow-[#0A0A0F]/50 p-8">
                    <h2 class="text-2xl font-bold text-[#FAFAFA] mb-2 font-headline">Verify Your Email</h2>
                    <p class="text-[#A1A1AA] mb-8">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>

                    @if (session('resent'))
                        <div class="mb-6 bg-green-500/10 border border-green-500/20 rounded-xl p-4">
                            <p class="text-sm text-green-400">A fresh verification link has been sent to your email address.</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                        @csrf

                        <button
                            type="submit"
                            class="w-full bg-[#DC2626] text-white py-3 px-4 rounded-xl hover:bg-[#B91C1C] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:ring-offset-2 transition-all duration-200 active:scale-[0.98] shadow-lg shadow-[#DC2626]/20"
                        >
                            Resend Verification Email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full mt-4 bg-transparent text-[#A1A1AA] border border-white/10 py-3 px-4 rounded-xl hover:bg-white/5 hover:text-[#FAFAFA] focus:outline-none focus:ring-2 focus:ring-white/10 focus:border-transparent transition-all duration-200"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
