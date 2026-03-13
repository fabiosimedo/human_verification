<nav x-data="{ open: false }" class="border-b border-white/10 bg-[#08111d]">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <span class="text-[18px] font-light uppercase tracking-[0.28em] text-white/65 sm:text-[19px]">
                    H U M A N O
                </span>
            </a>
        </div>

        <div class="hidden items-center gap-8 md:flex">
            <a
                href="{{ route('dashboard') }}"
                class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-white' : 'text-white/75 hover:text-white' }}"
            >
                Dashboard
            </a>

            <a
                href="{{ route('dashboard.links.index') }}"
                class="text-sm font-medium {{ request()->routeIs('dashboard.links.*') ? 'text-white' : 'text-white/75 hover:text-white' }}"
            >
                Links
            </a>

            <a
                href="{{ route('dashboard.profile.edit') }}"
                class="text-sm font-medium {{ request()->routeIs('dashboard.profile.*') ? 'text-white' : 'text-white/75 hover:text-white' }}"
            >
                Perfil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-medium text-white/75 transition hover:text-white">
                    Sair
                </button>
            </form>
        </div>

        <button
            @click="open = !open"
            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-white md:hidden"
            aria-label="Abrir menu"
        >
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path
                    :class="{ 'hidden': open, 'inline-flex': !open }"
                    class="inline-flex"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 7h16M4 12h16M4 17h16"
                />
                <path
                    :class="{ 'hidden': !open, 'inline-flex': open }"
                    class="hidden"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 6l12 12M18 6L6 18"
                />
            </svg>
        </button>
    </div>

    <div
        x-show="open"
        x-transition
        class="border-t border-white/10 bg-[#08111d] md:hidden"
        style="display: none;"
    >
        <div class="space-y-1 px-4 py-4">
            <a
                href="{{ route('dashboard') }}"
                class="block rounded-xl px-4 py-3 text-sm text-white/80 hover:bg-white/5 hover:text-white"
            >
                Dashboard
            </a>

            <a
                href="{{ route('dashboard.links.index') }}"
                class="block rounded-xl px-4 py-3 text-sm text-white/80 hover:bg-white/5 hover:text-white"
            >
                Links
            </a>

            <a
                href="{{ route('dashboard.profile.edit') }}"
                class="block rounded-xl px-4 py-3 text-sm text-white/80 hover:bg-white/5 hover:text-white"
            >
                Perfil
            </a>

            <!-- <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="block w-full rounded-xl px-4 py-3 text-left text-sm text-white/80 hover:bg-white/5 hover:text-white"
                >
                    Sair
                </button>
            </form> -->
        </div>
    </div>
</nav>