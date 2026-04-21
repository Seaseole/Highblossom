<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - {{ config('app.name') }}</title>
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
                    Reset your password to regain access to your account.
                </p>
            </div>
        </div>

        <!-- Right Column - Reset Password Form -->
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
                    <h2 class="text-2xl font-bold text-[#FAFAFA] mb-2 font-headline">Reset Password</h2>
                    <p class="text-[#A1A1AA] mb-8">Enter your new password below</p>

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div>
                            <label for="email" class="block text-sm font-medium text-[#A1A1AA] mb-2">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200"
                                placeholder="you@example.com"
                                required
                                autofocus
                                value="{{ old('email') }}"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-[#A1A1AA] mb-2">Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200"
                                placeholder="••••••••"
                                required
                            >
                            @error('password')
                                <p class="mt-2 text-sm text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-[#A1A1AA] mb-2">Confirm Password</label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200"
                                placeholder="••••••••"
                                required
                            >
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-[#DC2626] text-white py-3 px-4 rounded-xl hover:bg-[#B91C1C] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:ring-offset-2 transition-all duration-200 active:scale-[0.98] shadow-lg shadow-[#DC2626]/20"
                        >
                            Reset Password
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-[#A1A1AA]">
                            <a href="{{ route('login') }}" class="text-[#DC2626] hover:text-[#B91C1C] font-medium transition-colors">
                                Back to login
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
