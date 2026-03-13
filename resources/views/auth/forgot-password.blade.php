<x-guest-layout>

<div>

<h1 class="text-2xl font-bold text-slate-900">
Recuperar senha
</h1>

<p class="mt-1 text-sm text-slate-500">
Informe seu email para receber o link de recuperação
</p>

<x-auth-session-status class="mt-4" :status="session('status')" />

<form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">

@csrf

<div>
<x-input-label for="email" value="Email" />

<x-text-input
id="email"
class="mt-1 block w-full"
type="email"
name="email"
:value="old('email')"
required
/>

<x-input-error :messages="$errors->get('email')" />

</div>

<button
type="submit"
class="w-full rounded-xl bg-black py-3 text-white font-semibold">
Enviar link
</button>

<a
href="{{ route('login') }}"
class="block text-center text-sm text-gray-600">
Voltar ao login
</a>

</form>

</div>

</x-guest-layout>
