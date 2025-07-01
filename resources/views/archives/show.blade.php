<x-app-layout>
    <x-page-title page="Arquivo {{ $archive->code }}" header="Visualizando {{ $archive->code }} - {{ $archive->description }}" />
    @section('title', 'Arquivo ' . $archive->code . ' | Inusittá')

    <div class="p-4 bg-white dark:bg-slate-800 rounded-lg shadow space-y-4">
        <div>
            <h2 class="font-semibold text-slate-700 dark:text-slate-200">Informações principais</h2>
            <p class="text-sm text-slate-500 dark:text-slate-300 mt-1">
                Código: <strong>{{ $archive->code }}</strong><br>
                Descrição: <strong>{{ $archive->description }}</strong><br>
                Revisão: <strong>{{ $archive->revision ?? '-' }}</strong>
            </p>
        </div>

        <div>
            <h2 class="font-semibold text-slate-700 dark:text-slate-200 mt-4">Pastas e Setores</h2>
            @foreach($archive->folders as $folder)
                <div class="ml-4 text-sm text-slate-600 dark:text-slate-300">
                    <i data-feather="folder" class="inline w-4 h-4 mr-1 text-yellow-400"></i>
                    {{ $folder->name }} 
                    <span class="ml-2 text-xs text-slate-400">
                        ({{ $folder->sectors->pluck('name')->join(', ') }})
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mt-4">
    <a href="{{ asset('storage/archives/' . $archive->file_name) }}" 
       target="_blank" 
       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
        <i data-feather="external-link" class="w-4 h-4 mr-2"></i> Abrir Arquivo
    </a>
</div>

</x-app-layout>
