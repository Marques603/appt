{{-- Blade: resources/views/archives/by-sector.blade.php --}}
<x-app-layout>
    <x-page-title page="Arquivos - {{ $sector->name }}" header="Arquivos do Setor {{ $sector->name }}" />

    <a href="{{ route('archives.index') }}" class="text-sm text-blue-600 hover:underline mb-4 inline-block">&larr; Voltar para setores</a>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        @forelse($archives as $archive)
            <div class="bg-white dark:bg-slate-800 rounded-primary p-4 shadow hover:shadow-lg transition">
                <h3 class="text-md font-semibold text-slate-900 dark:text-slate-100">
                    <i data-feather="file-text" class="inline w-4 h-4 text-slate-400 mr-1"></i> {{ $archive->code }}
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $archive->description }}</p>
                <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    <p><strong>Revisão:</strong> {{ $archive->revision ?? '-' }}</p>
                    <p><strong>Status:</strong> {{ $archive->status ? 'Ativo' : 'Inativo' }}</p>
                </div>
                <a href="{{ asset('storage/' . $archive->file_path) }}" target="_blank" class="btn btn-sm btn-primary mt-3">Visualizar</a>
            </div>
        @empty
            <p class="col-span-full text-center text-slate-500 dark:text-slate-400">Nenhum arquivo encontrado neste setor.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $archives->links('vendor.pagination.custom') }}
    </div>
</x-app-layout>
