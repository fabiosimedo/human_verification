<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ config('app.name', 'Humano') }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#08111d] text-white antialiased">

<main class="flex min-h-screen items-center justify-center px-4 py-10">

<section class="w-full max-w-6xl rounded-[28px] overflow-hidden bg-[linear-gradient(135deg,#07111d,#0c1730,#125f56)] shadow-2xl">

<div class="grid lg:min-h-[100svh] lg:grid-cols-2">

<div class="flex items-center justify-center p-6 lg:p-8">
<div class="w-full max-w-[320px] rounded-3xl bg-white/10 p-4 backdrop-blur">
<img
src="/images/profile-card.png"
alt="Exemplo de card público"
class="w-full rounded-2xl shadow-xl"
/>
</div>
</div>

<div class="flex flex-col justify-center p-8 lg:p-12">

<div>
<p class="text-xs uppercase tracking-[0.25em] text-white/50">HUMANO</p>

<h1 class="mt-4 text-3xl sm:text-4xl font-bold leading-tight">
Verificação humana com preview para WhatsApp
</h1>

<p class="mt-5 text-base sm:text-lg text-white/80">
Crie um card público com sua identidade e compartilhe seu link de vendas com confiança.
</p>
</div>

<div class="mt-8 flex flex-col gap-3 sm:flex-row">

<a href="{{ route('login') }}"
class="flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-slate-900">
Entrar
</a>

@if (Route::has('register'))
<a href="{{ route('register') }}"
class="flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-6 py-3 text-sm font-semibold text-white">
Registrar
</a>
@endif

</div>

</div>

</div>

</section>

</main>

</body>
</html>
