@props(['title' => 'Error'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ App\Domains\Content\Models\CompanySetting::get('company_name', 'Highblossom') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
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
<body class="bg-[#0A0A0F] text-[#FAFAFA] font-body antialiased min-h-screen flex flex-col">
    <!-- Logo -->
    <div class="py-8 px-6">
        <a href="/" class="inline-flex items-center gap-3 group">
            @php
                $businessLogo = App\Domains\Content\Models\CompanySetting::get('business_logo', '');
                $logoText = App\Domains\Content\Models\CompanySetting::get('logo_text', 'Highblossom');
            @endphp
            
            @if($businessLogo)
                <img 
                    src="{{ Storage::url($businessLogo) }}" 
                    alt="{{ $logoText }}" 
                    class="h-10 w-auto object-contain transition-transform duration-200 group-hover:scale-105"
                >
            @else
                <span class="font-headline text-2xl font-bold text-[#FAFAFA] transition-transform duration-200 group-hover:scale-105">
                    {{ $logoText }}
                </span>
            @endif
        </a>
    </div>

    <!-- Content -->
    <main class="flex-1 flex items-center justify-center px-6 pb-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="py-6 px-6 text-center">
        <p class="text-[#A1A1AA] text-sm">
            &copy; {{ date('Y') }} {{ App\Domains\Content\Models\CompanySetting::get('company_name', 'Highblossom') }}. All rights reserved.
        </p>
    </footer>
</body>
</html>
