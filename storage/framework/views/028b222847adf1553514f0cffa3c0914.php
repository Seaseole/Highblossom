<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verify Email - <?php echo e(config('app.name')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet"
    />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>

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
                    <?php echo e($companyName ?? config('app.name')); ?>

                </h1>

                <p class="animate-fade-in-up text-xl leading-relaxed font-medium text-white/80 delay-200">
                    Thanks for signing up! Please verify your email address to get started.
                </p>

                <div class="animate-fade-in-up mt-12 flex justify-center gap-4 delay-300">
                    <div class="h-1 w-12 rounded-full bg-white/30"></div>
                    <div class="h-1 w-4 rounded-full bg-white/30"></div>
                    <div class="h-1 w-4 rounded-full bg-white/30"></div>
                </div>
            </div>
        </div>

        <!-- Right Column - Verify Email Form -->
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
                        <?php echo e($companyName ?? config('app.name')); ?>

                    </h1>
                </div>

                <div class="glass-vivid animate-fade-in-up rounded-[2rem] p-8 delay-100 lg:p-10">
                    <div class="mb-8 text-center">
                        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-[#DC2626]/10 to-[#B91C1C]/10">
                            <svg class="h-8 w-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="font-headline mb-3 text-3xl font-bold tracking-tight text-[#18181B]">
                            Verify Your Email
                        </h2>
                        <p class="text-[#71717A]">
                            We've sent a verification link to your email address. Please click it to activate your
                            account.
                        </p>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('resent')): ?>
                        <div class="animate-fade-in-up mb-6 rounded-2xl border border-green-500/20 bg-green-500/10 p-4 delay-200">
                            <p class="text-sm font-medium text-green-600">
                                A fresh verification link has been sent to your email address.
                            </p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="space-y-4">
                        <form
                            method="POST"
                            action="<?php echo e(route('verification.send')); ?>"
                            class="animate-fade-in-up delay-200"
                        >
                            <?php echo csrf_field(); ?>
                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#DC2626] px-6 py-4 text-lg font-bold text-white shadow-xl shadow-[#DC2626]/20 transition-all duration-300 hover:bg-[#B91C1C] focus:ring-4 focus:ring-[#DC2626]/20 focus:outline-none active:scale-[0.98]"
                            >
                                <span>Resend Email</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </button>
                        </form>

                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="animate-fade-in-up delay-300">
                            <?php echo csrf_field(); ?>
                            <button
                                type="submit"
                                class="w-full rounded-2xl border border-[#E4E4E7] bg-white/50 px-6 py-4 font-bold text-[#71717A] transition-all duration-300 hover:bg-white/80 hover:text-[#18181B] active:scale-[0.98]"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Footer Info -->
                <p class="animate-fade-in-up mt-8 text-center text-xs font-medium tracking-[0.2em] text-[#A1A1AA] uppercase delay-500">
                    &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>