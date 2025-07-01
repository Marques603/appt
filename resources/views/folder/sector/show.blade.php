<x-app-layout>
    <x-page-title page="Arquivos em {{ $sector->name }}" header="Arquivos no setor {{ $sector->name }} da pasta {{ $folder->name }}" />
    @section('title', 'Arquivos em ' . $sector->name . ' | Inusittá')

    <div class="space-y-4 mt-6">
        @forelse($archives as $archive)
            <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-800 rounded-lg shadow hover:shadow-lg transition">
                <div class="flex items-center space-x-3">
                    <i data-feather="file" class="w-5 h-5 text-slate-500"></i>
                    <div class="text-sm text-slate-700 dark:text-slate-300">
                        <span class="font-medium">{{ $archive->code }}</span>
                        <span class="text-xs text-slate-400 ml-2">{{ $archive->description }}</span>
                    </div>
                </div>
                <a href="{{ route('archives.download', $archive->id) }}" class="btn btn-primary btn-sm flex items-center space-x-1">
    <i data-feather="download" class="w-4 h-4"></i>
    <span>Baixar</span>
</a>

            </div>
        @empty
            <div class="text-xs text-slate-400">Nenhum arquivo neste setor.</div>
        @endforelse
    </div>
</x-app-layout>
