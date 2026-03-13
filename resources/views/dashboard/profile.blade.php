<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="mb-8">
            <p class="text-xs font-light uppercase tracking-[0.28em] text-white/45">
                H U M A N O
            </p>

            <p class="mt-4 text-sm leading-7 text-white/75 sm:text-base">
                Preencha os dados abaixo para ativar sua conta
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('dashboard.profile.update') }}"
            enctype="multipart/form-data"
            class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-12"
        >
            @csrf
            @method('PUT')

            <!-- FORM CARD -->
            <div class="w-full rounded-lg border border-white/10 bg-white/10 p-8 backdrop-blur-md">

                <div class="space-y-6">

                    @if (session('status'))
                        <div class="rounded-2xl border border-emerald-300/20 bg-emerald-300/10 px-4 py-3 text-sm text-emerald-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->has('general'))
                        <div class="rounded-2xl border border-red-300/20 bg-red-300/10 px-4 py-3 text-sm text-red-200">
                            {{ $errors->first('general') }}
                        </div>
                    @endif

                    <div>
                        <x-input-label for="name" value="Nome" />

                        <x-text-input
                            id="name"
                            class="mt-2 block w-full"
                            name="name"
                            type="text"
                            :value="old('name', $user->name)"
                            required
                        />

                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="phone" value="Telefone" />

                        <x-text-input
                            id="phone"
                            class="mt-2 block w-full"
                            name="phone"
                            type="text"
                            :value="old('phone', $user->phone)"
                            placeholder="(11) 99999-0000"
                            required
                        />

                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="img" value="Imagem do card" />

                        <input
                            id="img"
                            type="file"
                            name="img"
                            class="mt-2 block w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white file:mr-3 file:rounded-xl file:border-0 file:bg-white file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-900 hover:file:bg-white/90"
                            accept=".jpg,.jpeg,.png,.webp"
                            @if (! ($user->media->firstWhere('is_primary', true) ?? $user->media->first())) required @endif
                        />

                        <p class="mt-2 text-xs text-white/55">
                            Preferencialmente imagens em retrato
                        </p>

                        <x-input-error :messages="$errors->get('img')" class="mt-2" />
                    </div>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-sm font-semibold text-slate-900 shadow-lg transition hover:bg-white/90"
                    >
                        Salvar e criar card
                    </button>

                </div>
            </div>

            <!-- PREVIEW CARD -->
            <div class="flex items-start justify-center">

                <div class="w-full max-w-[460px] rounded-lg border border-white/10 bg-white/10 p-5 shadow-2xl backdrop-blur-md">

                    <div class="overflow-hidden rounded-[22px] border border-white/10 bg-[#0d1728]">

                        <div
                            id="card-preview-image-wrap"
                            class="relative flex h-[420px] w-full items-center justify-center overflow-hidden"
                        >
                            @php
                                $previewImage = $primaryImage?->url() ?? null;
                            @endphp

                            @if ($previewImage)
                                <img
                                    id="card-preview-image"
                                    src="{{ $previewImage }}"
                                    alt="Preview do card"
                                    class="h-full w-full object-cover"
                                >
                            @else
                                <div
                                    id="card-preview-placeholder"
                                    class="flex h-full w-full items-center justify-center text-sm text-white/35"
                                >
                                    Preview da imagem
                                </div>

                                <img
                                    id="card-preview-image"
                                    src=""
                                    alt="Preview do card"
                                    class="hidden h-full w-full object-cover"
                                >
                            @endif
                        </div>

                        <div class="p-6">

                            <div
                                id="card-preview-name"
                                class="break-words text-lg font-semibold text-white"
                            >
                                {{ old('name', $user->name ?: 'Seu nome') }}
                            </div>

                            <div
                                id="card-preview-phone"
                                class="mt-2 break-all text-sm text-white/70"
                            >
                                {{ old('phone', $user->phone ?: '(00) 00000-0000') }}
                            </div>

                            <a
                                href="{{ url('/p/' . $user->slug) }}"
                                target="_blank"
                                class="mt-6 block rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-slate-900 shadow-md transition hover:bg-white/90"
                            >
                                HUMANO VERIFICADO
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>
</x-app-layout>