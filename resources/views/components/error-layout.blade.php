@props(['title' => 'Error'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title }} - {{ $settings->get('company_name', 'Highblossom') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet"
    />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes error-entrance {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-error-entrance {
            animation: error-entrance 250ms cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }
    </style>
</head>
<body class="font-body flex min-h-screen flex-col bg-[#0A0A0F] text-[#FAFAFA] antialiased">
    <!-- Logo -->
    <div class="px-6 py-8">
        <a href="/" class="group inline-flex items-center gap-3">
            @php
                $businessLogo = $settings->get('business_logo', '');
                $logoText = $settings->get('logo_text', 'Highblossom');
            @endphp

            @if ($businessLogo)
                <img
                    src="{{ Storage::url($businessLogo) }}"
                    alt="{{ $logoText }}"
                    class="h-10 w-auto rounded-lg object-contain transition-transform duration-200 group-hover:scale-105"
                />
            @else
                <span class="font-headline text-2xl font-bold text-[#FAFAFA] transition-transform duration-200 group-hover:scale-105">
                    {{ $logoText }}
                </span>
            @endif
        </a>
    </div>

    <!-- Content -->
    <main class="flex flex-1 items-center justify-center px-6 pb-8">{{ $slot }}</main>

    <!-- Footer -->
    <footer class="px-6 py-6 text-center">
        <p class="text-sm text-[#A1A1AA]">
            &copy; {{ date('Y') }} {{ $settings->get('company_name', 'Highblossom Pty Ltd') }}. All rights reserved.
        </p>
    </footer>
</body>
</html>
