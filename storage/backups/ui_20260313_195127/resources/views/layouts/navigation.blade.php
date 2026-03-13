<nav x-data="{ open: false }" class="hum-topbar">
    <div class="hum-container">
        <div class="flex h-16 items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="hum-brand">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-lime-400/20 bg-lime-400/10 shadow-[0_0_24px_rgba(158,255,0,0.12)]">
                        <x-application-logo class="h-6 w-6 fill-current text-lime-300" />
                    </div>

                    <div class="leading-tight">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400">
                            HUMANO
                        </div>
                        <div class="text-sm font-semibold text-white">
                            Dashboard
                        </div>
                    </div>
                </a>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                <a href="{{ route('dashboard') }}"
                   class="rounded-xl px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    Início
                </a>

                <a href="{{ route('dashboard.links.index') }}"
                   class="rounded-xl px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard.links.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    Links
                </a>

                <a href="{{ route('dashboard.profile.edit') }}"
                   class="rounded-xl px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard.profile.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    Perfil
                </a>

                <div class="h-8 w-px bg-white/10"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hum-btn-secondary py-2">
                        Sair
                    </button>
                </form>
            </div>

            <button
                @click="open = !open"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-slate-200 md:hidden"
            >
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>

        <div x-show="open" x-transition class="pb-4 md:hidden" style="display:none;">
            <div class="hum-card-soft mt-3 space-y-2 p-3">
                <a href="{{ route('dashboard') }}" class="block rounded-xl px-4 py-3 text-sm text-slate-200 hover:bg-white/5">
                    Início
                </a>
                <a href="{{ route('dashboard.links.index') }}" class="block rounded-xl px-4 py-3 text-sm text-slate-200 hover:bg-white/5">
                    Links
                </a>
                <a href="{{ route('dashboard.profile.edit') }}" class="block rounded-xl px-4 py-3 text-sm text-slate-200 hover:bg-white/5">
                    Perfil
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mt-2 w-full hum-btn-secondary">
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>