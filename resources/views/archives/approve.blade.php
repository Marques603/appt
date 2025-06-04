<x-app-layout>
    <x-page-title page="Aprovar Documento" pageUrl="{{ route('documents.index') }}" header="Aprovar Documento" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Informações do Documento -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="file" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">{{ $document->code }}</h2>
                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                        class="text-sm text-blue-500 mt-2 hover:underline">Visualizar Arquivo</a>
                </div>
            </div>
        </section>

        <!-- Aprovação -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Informações do Documento</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Revise as informações antes de aprovar ou reprovar.</p>

                    <div class="mb-4 space-y-2">
                        <div><strong>Descrição:</strong> {{ $document->description ?? '---' }}</div>
                        <div><strong>Revisão:</strong> {{ $document->revision ?? '---' }}</div>
                        <div><strong>Status:</strong> {{ $document->status ? 'Ativo' : 'Inativo' }}</div>
                    </div>

                    <!-- Status de Aprovação -->
                    @php
                        $userApproval = $document->approvals->where('user_id', auth()->id())->first();
                    @endphp

                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Status de Aprovação:</h3>
                        <div class="flex space-x-4">
                            <form action="{{ route('documents.updateApprovalStatus', $document->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="1">
                                <button type="submit" class="btn btn-success" {{ optional($userApproval)->status === 1 ? 'disabled' : '' }}>
                                    Aprovar
                                </button>
                            </form>

                            <form action="{{ route('documents.updateApprovalStatus', $document->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="2">
                                <button type="submit" class="btn btn-danger" {{ optional($userApproval)->status === 2 ? 'disabled' : '' }}>
                                    Reprovar
                                </button>
                            </form>

                            <form action="{{ route('documents.updateApprovalStatus', $document->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="0">
                                <button type="submit" class="btn btn-warning" {{ optional($userApproval)->status === 0 ? 'disabled' : '' }}>
                                    Em Análise
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Lista de aprovações -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Aprovado por:</h3>
                        <ul class="list-disc pl-5 text-sm text-slate-500">
                            @forelse($document->approvals as $approval)
                                <li>{{ $approval->user->name }} — 
                                    @switch($approval->status)
                                        @case(1) Aprovado @break
                                        @case(2) Reprovado @break
                                        @default Em Análise
                                    @endswitch
                                </li>
                            @empty
                                <li>Nenhuma aprovação registrada ainda.</li>
                            @endforelse
                        </ul>
                    </div>

                </div>
            </div>
        </section>
    </div>
</x-app-layout>
