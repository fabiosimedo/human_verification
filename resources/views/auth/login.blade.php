<x-guest-layout>
    <div>
        <p class="text-xs uppercase tracking-[0.25em] text-white/50">HUMANO</p>

        <p class="mt-2 text-sm text-white/70">
            Acesse sua conta
        </p>

        <x-auth-session-status class="mt-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
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
                    autofocus
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <div class="flex items-center justify-between gap-3">
                    <x-input-label for="password" value="Senha" class="mb-0" />

                    @if (Route::has('password.request'))
                        <a class="text-sm text-white/70 transition hover:text-white" href="{{ route('password.request') }}">
                            Esqueceu?
                        </a>
                    @endif
                </div>

                <x-text-input
                    id="password"
                    class="mt-1 block w-full"
                    type="password"
                    name="password"
                    required
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center gap-2">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    class="h-4 w-4 rounded border-white/20 bg-white/10 text-emerald-400 focus:ring-emerald-400"
                >

                <label for="remember" class="text-sm text-white/65">
                    Lembrar de mim
                </label>
            </div>

            <button
                type="submit"
                class="w-full rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-white/90">
                Entrar
            </button>

            @if (Route::has('register'))
                <a
                    href="{{ route('register') }}"
                    class="block text-center text-sm text-white/65 transition hover:text-white">
                    Criar conta
                </a>
            @endif
        </form>
    </div>
</x-guest-layout>