<x-app-layout>
    <x-page-title page="Aprovação de Documento" pageUrl="{{ route('documents.index') }}" header="Aprovar Documento" />

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

        <!-- Formulário de Aprovação -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Aprovação</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Informe o seu parecer sobre este documento.</p>

                    <form method="POST" action="{{ route('documents.approve.store', $document->id) }}" class="flex flex-col gap-5">
                        @csrf

                        <label class="label">
                            <span class="block mb-1">Comentário</span>
                            <textarea name="comments" rows="3" class="input @error('comments') border-red-500 @enderror">{{ old('comments') }}</textarea>
                            @error('comments')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </label>

                        <div class="flex gap-4">
    <button type="submit" name="status" value="1" class="btn btn-success">
        <i class="bi bi-check-circle"></i> Aprovar
    </button>

    <button type="submit" name="status" value="2" class="btn btn-danger">
        <i class="bi bi-x-circle"></i> Reprovar
    </button>

    <button type="submit" name="status" value="0" class="btn btn-warning">
        <i class="bi bi-hourglass-split"></i> Em Análise
    </button>
</div>

                    </form>
                </div>
            </div>

            <!-- Histórico de Aprovações -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Histórico de Aprovações</h2>

                    @forelse ($approvals as $approval)
                        <div class="p-2 border rounded mb-3">
                            <strong>{{ $approval->user->name }}</strong>
                            <span class="text-xs text-slate-500"> • {{ $approval->approved_at->format('d/m/Y H:i') }}</span>
                            <div class="mt-1 text-sm">
                                <span>Status: 
                                    @if ($approval->status == 1)
                                        <span class="text-green-600">Aprovado</span>
                                    @elseif ($approval->status == 2)
                                        <span class="text-red-600">Reprovado</span>
                                    @else
                                        <span class="text-yellow-500">Em Análise</span>
                                    @endif
                                </span>
                                <br>
                                <span>Comentário: {{ $approval->comments ?: 'Sem comentário' }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum histórico de aprovação ainda.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
