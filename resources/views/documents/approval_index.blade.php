<x-app-layout>
    <x-page-title page="Documentos em Aprovação" pageUrl="{{ route('documents.index') }}" header="Documentos por Fase de Aprovação" />

    <div class="space-y-8">

        {{-- Em Análise --}}
        <section>
            <h2 class="text-lg font-semibold text-yellow-600">📌 Documentos em Análise</h2>
            @forelse ($documentsPending as $document)
                <div class="p-3 border rounded mb-2">
                    <strong>{{ $document->code }}</strong> — {{ $document->description }}
                    <a href="{{ route('documents.show', $document->id) }}" class="text-blue-500 text-sm ml-2">Ver Detalhes</a>
                </div>
            @empty
                <p class="text-sm text-gray-500">Nenhum documento em análise.</p>
            @endforelse
        </section>

        {{-- Aprovados --}}
        <section>
            <h2 class="text-lg font-semibold text-green-600">✅ Documentos Aprovados</h2>
            @forelse ($documentsApproved as $document)
                <div class="p-3 border rounded mb-2">
                    <strong>{{ $document->code }}</strong> — {{ $document->description }}
                    <a href="{{ route('documents.show', $document->id) }}" class="text-blue-500 text-sm ml-2">Ver Detalhes</a>
                </div>
            @empty
                <p class="text-sm text-gray-500">Nenhum documento aprovado.</p>
            @endforelse
        </section>

        {{-- Reprovados --}}
        <section>
            <h2 class="text-lg font-semibold text-red-600">❌ Documentos Reprovados</h2>
            @forelse ($documentsRejected as $document)
                <div class="p-3 border rounded mb-2">
                    <strong>{{ $document->code }}</strong> — {{ $document->description }}
                    <a href="{{ route('documents.show', $document->id) }}" class="text-blue-500 text-sm ml-2">Ver Detalhes</a>
                </div>
            @empty
                <p class="text-sm text-gray-500">Nenhum documento reprovado.</p>
            @endforelse
        </section>

    </div>
</x-app-layout>
