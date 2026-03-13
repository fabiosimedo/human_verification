<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
        <div class="rounded-[28px] bg-[linear-gradient(135deg,#07111d,#0c1730,#125f56)] p-6 shadow-2xl sm:p-8 lg:p-10">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs font-light uppercase tracking-[0.28em] text-white/45">
                        H U M A N O
                    </p>

                    <p class="mt-4 text-sm leading-7 text-white/75 sm:text-base">
                        Gerencie seus links e seu perfil público.
                    </p>
                </div>

                <div class="flex-shrink-0">
                    <a
                        href="{{ route('preview.card') }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-slate-900 shadow-lg transition hover:bg-white/90"
                    >
                        Ver meu card
                    </a>
                </div>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-2 sm:gap-5">
                <a
                    href="{{ route('dashboard.links.index') }}"
                    class="group rounded-[24px] border border-white/10 bg-white/10 p-6 backdrop-blur-md transition duration-200 hover:border-white/20 hover:bg-white/15"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-white">
                                Links de vendas
                            </h2>

                            <p class="mt-3 text-sm leading-6 text-white/70">
                                Gerencie seus links
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-white/60 transition group-hover:bg-white/10 group-hover:text-white/85">
                            Abrir
                        </div>
                    </div>
                </a>

                <a
                    href="{{ route('dashboard.profile.edit') }}"
                    class="group rounded-[24px] border border-white/15 bg-white/[0.03] p-6 backdrop-blur-sm transition duration-200 hover:border-white/25 hover:bg-white/[0.06]"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-white">
                                Editar perfil
                            </h2>

                            <p class="mt-3 text-sm leading-6 text-white/70">
                                Atualize seus dados
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-white/60 transition group-hover:bg-white/10 group-hover:text-white/85">
                            Abrir
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>