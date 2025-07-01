<x-app-layout>
    <x-page-title page="Pasta: {{ $folder->name }}" header="Pastas de Setores em {{ $folder->name }}" />
    @section('title', 'Pasta ' . $folder->name . ' | Inusittá')

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 mt-6">
        @foreach($folder->sectors as $sector)
            <a href="{{ route('folder.sector.show', ['folder' => $folder->id, 'sector' => $sector->id]) }}" 
               class="flex flex-col items-center p-4 bg-white dark:bg-slate-800 rounded-xl shadow hover:shadow-lg transition group">
                <i data-feather="folder" class="w-12 h-12 text-green-500 mb-2 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-200 text-center">
                    {{ $sector->name }}
                </span>
            </a>
        @endforeach
    </div>
</x-app-layout>
