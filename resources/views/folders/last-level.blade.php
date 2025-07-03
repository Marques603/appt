<x-app-layout>
    <x-page-title page="Setores em {{ $parentFolder->name }}" header="Setores na pasta {{ $parentFolder->name }}" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse ($sectors as $sector)
            <a href="{{ route('folders.sectorFiles', [$parentFolder->id, $sector->id]) }}"
                class="block p-4 bg-white dark:bg-slate-800 rounded-lg shadow hover:bg-blue-50 dark:hover:bg-slate-700">
                <h3 class="font-semibold text-lg">{{ $sector->name }}</h3>
                <p class="text-sm text-gray-600">Ver arquivos do setor {{ $sector->name }}</p>
            </a>
        @empty
            <p class="col-span-3 text-center text-gray-500">Nenhum setor vinculado a esta pasta.</p>
        @endforelse
    </div>
</x-app-layout>
