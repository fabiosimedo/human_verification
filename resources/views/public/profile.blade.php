<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ $metaTitle }}</title>

<meta property="og:type" content="website">
<meta property="og:url" content="{{ $profileUrl }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $ogImageUrl }}">

@vite(['resources/css/app.css'])

</head>

<body class="min-h-screen bg-[#07111d] text-white flex items-center justify-center">

<div class="max-w-5xl w-full grid md:grid-cols-2 bg-[#0c1730] rounded-3xl overflow-hidden">

<div class="p-6 flex items-center justify-center">

<img
src="{{ $profile->photo_url }}"
class="rounded-2xl max-h-[480px] object-cover"
/>

</div>

<div class="p-10 flex flex-col justify-center">

<p class="text-sm text-white/60">
{{ $profile->public_code }}
</p>

<h1 class="text-4xl font-bold mt-4">
Site de verificação humana
</h1>

<p class="mt-6 text-xl text-white/80">
A imagem acima foi verificada e pertence a um humano real.
</p>

<div class="mt-8 bg-green-400 text-black font-bold px-6 py-3 rounded-xl inline-block">
VERIFICADO
</div>

<div class="mt-6 text-white/70">
{{ $profile->display_name }}
</div>

@if($profile->phone)
<div class="text-white/50">
{{ $profile->phone }}
</div>
@endif

<a
href="{{ route('dashboard') }}"
class="mt-10 inline-block bg-white text-black px-6 py-3 rounded-xl">
Voltar
</a>

</div>

</div>

</body>

</html>
