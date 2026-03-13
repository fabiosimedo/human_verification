<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $metaTitle ?? config('app.name') }}</title>
    <meta name="description" content="{{ $metaDescription ?? '' }}">

    {{-- OG para WhatsApp --}}
    <meta property="og:title" content="{{ $metaTitle ?? '' }}">
    <meta property="og:description" content="{{ $metaDescription ?? '' }}">
    <meta property="og:image" content="{{ $metaImage ?? '' }}">
    <meta property="og:url" content="{{ $metaUrl ?? request()->fullUrl() }}">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @yield('content')
</body>
</html>