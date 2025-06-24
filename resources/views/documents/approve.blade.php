<x-app-layout>
    <x-page-title page="Todos os Documentos" header="Lista completa de documentos" />

    <div class="card">
        <div class="card-body">
            <table class="table table-auto w-full">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Setores</th>
                        <th>Criado em</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $document)
                        <tr>
                            <td>{{ $document->code }}</td>
                            <td>{{ $document->description }}</td>
                            <td>
                                @if($document->status == 1)
                                    <span class="text-green-600">Ativo</span>
                                @else
                                    <span class="text-red-600">Inativo</span>
                                @endif
                            </td>
                            <td>
                                {{ $document->sectors->pluck('name')->join(', ') }}
                            </td>
                            <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
