<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Links de vendas
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    Você pode criar até {{ $linksLimit }} link(s) no seu plano atual.
                </p>
            </div>

            <button
                type="button"
                onclick="openCreateModal()"
                class="rounded-xl bg-black px-4 py-2 text-sm text-white"
            >
                Criar link
            </button>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->has('general'))
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first('general') }}
            </div>
        @endif

        @if ($errors->has('title') || $errors->has('url'))
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                Verifique os dados do link e tente novamente.
            </div>
        @endif

        <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 text-left text-sm text-gray-700">
                        <tr>
                            <th class="p-3">Nome</th>
                            <th class="p-3">Link</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($links as $link)
                            <tr class="border-t border-gray-200 align-top">
                                <td class="p-3">
                                    <div class="font-medium text-gray-900">
                                        {{ $link->title }}
                                    </div>
                                </td>

                                <td class="p-3 text-sm text-gray-600">
                                    <div class="max-w-[320px] truncate">
                                        {{ $link->url }}
                                    </div>
                                </td>

                                <td class="p-3">
                                    @if($link->is_active)
                                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                            Ativo
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500">
                                            Inativo
                                        </span>
                                    @endif
                                </td>

                                <td class="p-3">
                                    <div class="flex flex-wrap gap-3 text-sm">
                                        <a
                                            href="https://wa.me/?text={{ urlencode($link->url) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-green-600 hover:underline"
                                        >
                                            Compartilhar
                                        </a>

                                        <button
                                            type="button"
                                            onclick='openEditModal(@json([
                                                "id" => $link->id,
                                                "title" => $link->title,
                                                "url" => $link->url,
                                            ]))'
                                            class="text-blue-600 hover:underline"
                                        >
                                            Editar
                                        </button>

                                        <form method="POST" action="{{ route('dashboard.links.toggle', $link) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="text-amber-600 hover:underline">
                                                {{ $link->is_active ? 'Desativar' : 'Ativar' }}
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('dashboard.links.destroy', $link) }}"
                                            onsubmit="return confirm('Tem certeza que deseja remover este link?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-red-600 hover:underline">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-sm text-gray-500">
                                    Nenhum link cadastrado ainda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div
            id="linkModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4"
        >
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
                <div class="flex items-center justify-between">
                    <h2 id="linkModalTitle" class="text-lg font-semibold text-gray-900">
                        Criar link
                    </h2>

                    <button
                        type="button"
                        onclick="closeModal()"
                        class="text-xl leading-none text-gray-400 hover:text-gray-700"
                    >
                        ×
                    </button>
                </div>

                <form id="linkForm" method="POST" action="{{ route('dashboard.links.store') }}" class="mt-6 space-y-5">
                    @csrf

                    <input type="hidden" name="_method" id="linkFormMethod" value="POST">

                    <div>
                        <x-input-label for="linkTitle" value="Nome" />
                        <x-text-input
                            id="linkTitle"
                            name="title"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('title')"
                            required
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="linkUrl" value="URL" />
                        <x-text-input
                            id="linkUrl"
                            name="url"
                            type="url"
                            class="mt-1 block w-full"
                            :value="old('url')"
                            required
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('url')" />
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button
                            type="button"
                            onclick="closeModal()"
                            class="rounded-xl border border-gray-300 px-4 py-2 text-sm text-gray-700"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="rounded-xl bg-black px-4 py-2 text-sm text-white"
                        >
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            const modal = document.getElementById('linkModal');
            const title = document.getElementById('linkModalTitle');
            const form = document.getElementById('linkForm');
            const method = document.getElementById('linkFormMethod');
            const inputTitle = document.getElementById('linkTitle');
            const inputUrl = document.getElementById('linkUrl');

            title.textContent = 'Criar link';
            form.action = @json(route('dashboard.links.store'));
            method.value = 'POST';
            inputTitle.value = '';
            inputUrl.value = '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openEditModal(link) {
            const modal = document.getElementById('linkModal');
            const title = document.getElementById('linkModalTitle');
            const form = document.getElementById('linkForm');
            const method = document.getElementById('linkFormMethod');
            const inputTitle = document.getElementById('linkTitle');
            const inputUrl = document.getElementById('linkUrl');

            title.textContent = 'Editar link';
            form.action = '/dashboard/links/' + link.id;
            method.value = 'PATCH';
            inputTitle.value = link.title ?? '';
            inputUrl.value = link.url ?? '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('linkModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        @if ($errors->has('title') || $errors->has('url'))
            document.addEventListener('DOMContentLoaded', function () {
                const hasOldValues = @json(filled(old('title')) || filled(old('url')));
                if (hasOldValues) {
                    openCreateModal();
                    document.getElementById('linkTitle').value = @json(old('title', ''));
                    document.getElementById('linkUrl').value = @json(old('url', ''));
                }
            });
        @endif
    </script>
</x-app-layout>