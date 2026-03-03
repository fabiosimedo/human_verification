<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Meus links
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800">Adicionar link</h3>

                <form method="POST" action="{{ route('dashboard.links.store') }}" class="mt-4 flex flex-col sm:flex-row gap-3">
                    @csrf

                    <input name="checkout_url" type="url"
                           value="{{ old('checkout_url') }}"
                           placeholder="https://pay.hest.com.br/{uuid}"
                           class="flex-1 rounded border-gray-300"
                           required />

                    <button class="px-4 py-2 rounded bg-black text-white" type="submit">
                        Criar
                    </button>
                </form>

                @error('checkout_url')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror

                <div class="mt-8">
                    <h3 class="font-semibold text-gray-800">Seus links</h3>

                    @if ($links->isEmpty())
                        <p class="mt-2 text-gray-600">Você ainda não criou links.</p>
                    @else
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-600">
                                        <th class="py-2 pr-4">Slug público</th>
                                        <th class="py-2 pr-4">Token</th>
                                        <th class="py-2 pr-4">Status</th>
                                        <th class="py-2 pr-4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-800">
                                    @foreach ($links as $link)
                                        <tr class="border-t">
                                            <td class="py-2 pr-4 font-mono">{{ $link->public_slug }}</td>
                                            <td class="py-2 pr-4 font-mono">{{ $link->token }}</td>
                                            <td class="py-2 pr-4">{{ $link->status }}</td>
                                            <td class="py-2 pr-4">
                                                <form method="POST" action="{{ route('dashboard.links.destroy', $link) }}"
                                                      onsubmit="return confirm('Remover este link?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-600 hover:underline" type="submit">
                                                        Remover
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded bg-gray-200 inline-block">
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
