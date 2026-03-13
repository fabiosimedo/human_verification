<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>{{ config('app.name','Humano') }}</title>

@vite(['resources/css/app.css','resources/js/app.js'])
</head>


<body class="min-h-screen bg-[#08111d] text-white antialiased">
    <div class="relative flex min-h-screen items-center justify-center px-4 py-8">
        <div class="w-full max-w-md rounded-[28px] border border-white/10 bg-[linear-gradient(135deg,#07111d,#0c1730,#125f56)] p-6 shadow-2xl sm:p-8">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
