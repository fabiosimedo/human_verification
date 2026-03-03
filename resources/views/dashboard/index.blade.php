<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-800">
                    Você está logado ✅
                </p>

                <p class="mt-2 text-gray-600">
                    Comece configurando seu perfil e cadastrando seu primeiro link.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('dashboard.profile.edit') }}"
                       class="px-4 py-2 rounded bg-black text-white">
                        Meu perfil
                    </a>

                    <a href="{{ route('dashboard.links.index') }}"
                       class="px-4 py-2 rounded bg-gray-200">
                        Meus links
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
