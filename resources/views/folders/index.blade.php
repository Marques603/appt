<x-app-layout>
    <x-page-title page="Pastas" header="Lista de Pastas" />

    {{-- Breadcrumb --}}
    <nav class="mb-4 text-sm text-gray-600">
        <a href="{{ route('folders.index') }}" class="underline hover:text-blue-600">Raiz</a>
        @if($parentFolder)
            &raquo; {{ $parentFolder->name }}
        @endif
    </nav>

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    {{-- Botão para nova pasta --}}
    <a href="{{ route('folders.create', ['parent_id' => $parentFolder?->id]) }}" class="btn btn-primary mb-4">Nova Pasta</a>

    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">Nome</th>
                <th class="border border-gray-300 px-4 py-2">Status</th>
                <th class="border border-gray-300 px-4 py-2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($folders as $folder)
            <tr>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="{{ route('folders.index', ['parent_id' => $folder->id]) }}" class="text-blue-600 hover:underline">
                        {{ $folder->name }}
                    </a>
                </td>
                <td class="border border-gray-300 px-4 py-2">{{ $folder->status ? 'Ativa' : 'Inativa' }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="{{ route('folders.edit', $folder) }}" class="text-blue-500 mr-2">Editar</a>
                    <form action="{{ route('folders.destroy', $folder) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                    Nenhuma pasta encontrada.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $folders->withQueryString()->links() }}
    </div>
</x-app-layout>
