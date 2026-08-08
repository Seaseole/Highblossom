<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100">
    <div class="flex min-h-screen">
        <div class="hidden items-center justify-center bg-gradient-to-br from-blue-600 to-blue-800 p-12 lg:flex lg:w-1/2">
            <div class="text-white">
                <h1 class="mb-4 text-4xl font-bold">{{ config('app.name') }}</h1>
                <p class="text-lg opacity-90">Welcome back</p>
            </div>
        </div>
        <div class="flex w-full items-center justify-center p-8 lg:w-1/2">
            <div class="w-full max-w-md">{{ $slot }}</div>
        </div>
    </div>
</body>
</html>
