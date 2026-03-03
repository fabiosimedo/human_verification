<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Meu perfil
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('dashboard.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome exibido</label>
                        <input name="display_name" type="text"
                               value="{{ old('display_name', $profile?->display_name) }}"
                               class="mt-1 w-full rounded border-gray-300"
                               required />
                        @error('display_name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto URL (opcional)</label>
                        <input name="photo_url" type="text"
                               value="{{ old('photo_url', $profile?->photo_url) }}"
                               class="mt-1 w-full rounded border-gray-300" />
                        @error('photo_url')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telefone (opcional)</label>
                            <input name="phone" type="text"
                                   value="{{ old('phone', $profile?->phone) }}"
                                   class="mt-1 w-full rounded border-gray-300" />
                            @error('phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">WhatsApp (opcional)</label>
                            <input name="whatsapp" type="text"
                                   value="{{ old('whatsapp', $profile?->whatsapp) }}"
                                   class="mt-1 w-full rounded border-gray-300" />
                            @error('whatsapp')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 rounded bg-black text-white" type="submit">
                            Salvar
                        </button>

                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded bg-gray-200">
                            Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
