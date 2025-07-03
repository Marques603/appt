<x-app-layout>
    <x-page-title page="Arquivos do Setor" header="Arquivos do Setor na Pasta {{ $folder->name }}" />

    <a href="{{ route('archives.create', ['folder' => $folder->id, 'sector' => $sector->id]) }}"
       class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
       + Adicionar arquivo
    </a>

    <div class="p-4 bg-white dark:bg-slate-800 rounded-lg shadow space-y-4">
        <h2 class="font-bold text-xl mb-4">Arquivos do setor: {{ $sector->name }}</h2>

        <ul class="list-disc list-inside">
            @forelse ($archives as $archive)
                <li>
                    <strong>{{ $archive->code }}</strong> - {{ $archive->description }}
                </li>
            @empty
                <li>Nenhum arquivo encontrado para este setor.</li>
            @endforelse
        </ul>

        {{ $archives->links() }}
    </div>
</x-app-layout>
