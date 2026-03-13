<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name','Humano') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen font-sans">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-8">
        <div class="absolute inset-0 hum-grid-bg opacity-20"></div>
        <div class="absolute left-[-60px] top-[8%] h-44 w-44 rounded-full bg-lime-400/10 blur-3xl"></div>
        <div class="absolute right-[-40px] top-[18%] h-56 w-56 rounded-full bg-blue-500/10 blur-3xl"></div>

        <div class="relative z-10 w-full max-w-md hum-card hum-glow hum-fade-up p-6 sm:p-8">
            {{ $slot }}
        </div>
    </div>
</body>
</html>