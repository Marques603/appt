<x-app-layout>
    <x-page-title page="Enviar Documento" pageUrl="{{ route('documents.index') }}" header="Enviar Documento" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Preview -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="file" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">Documento</h2>
                </div>
            </div>
        </section>

        <!-- Formulário -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Detalhes do Documento</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Preencha as informações do documento</p>

                    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="flex flex-col gap-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <label class="label">
                                <span class="block mb-1">Código</span>
                                <input type="text" name="code" class="input @error('code') border-red-500 @enderror" value="{{ old('code', 'Não Preencher') }}"/>
                                @error('code')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>

                            <label class="label">
                                <span class="block mb-1">Arquivo</span>
                                <input type="file" name="file" class="input @error('file') border-red-500 @enderror" required />
                                @error('file')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>


                        <!-- Campos adicionais opcionais -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <label class="label">
                                <span class="block mb-1">Descrição</span>
                                <input type="text" name="description" class="input" value="{{ old('description','Arquivo para validação') }}" />
                            </label>
                        </div>

                        <!-- Outros campos... -->

                            @if ($showSectorSelect)
                                <label class="label">
                                    <span class="block mb-1">Selecione o setor</span>
                                    <select name="sector_id" id="sector_id" class="input @error('sector_id') border-red-500 @enderror" required>
                                        <option value="">-- Selecione --</option>
                                        @foreach ($userSectors as $sector)
                                            <option value="{{ $sector->id }}" {{ old('sector_id') == $sector->id ? 'selected' : '' }}>
                                                {{ $sector->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </label>
                            @endif

                        <!-- Botões -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('documents.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Enviar Documento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
