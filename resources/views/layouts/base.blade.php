@props([
    'title' => null,
    'alpine' => false,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#f7f5f3" />
    {{-- Contract with resources/js/passkey-signin.js: it reads this meta tag first
         and only falls back to the XSRF-TOKEN cookie. Without it the passkey POST
         loses its token. --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @include('partials.favicons')
    {{-- Pre-paint theme bootstrap: sets .dark on <html> from the stored mode, so
         there is no flash of the wrong theme and no server-side guess needed for
         a guest whose preference lives in localStorage. --}}
    @include('partials.theme-script')

    <title>{{ $title ? $title.' · '.config('app.name') : config('app.name') }}</title>

    {{-- One family, four weights. The headline face this project referenced for
         years (Cabinet Grotesk) is not hosted by Google Fonts — that request
         returned only Geist, so every "display" heading was silently falling back
         to the generic sans stack. Hierarchy is carried by weight instead. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    />

    @vite(
        $alpine
            ? ['resources/css/app.css', 'resources/css/auth.css', 'resources/js/app.js', 'resources/js/auth-alpine.js']
            : ['resources/css/app.css', 'resources/css/auth.css', 'resources/js/app.js'],
    )

    {{ $head ?? '' }}
</head>
<body {{ $attributes->merge(['class' => 'hb-ui min-h-[100dvh] antialiased']) }}>
    {{ $slot }}
</body>
</html>
