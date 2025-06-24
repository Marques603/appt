<x-app-layout>
    <!-- Título da Página -->
    <x-page-title page="{{ isset($sector) ? 'Editar Setor' : 'Criar Setor' }}" pageUrl="{{ route('sector.index') }}" header="{{ isset($sector) ? 'Editar Setor' : 'Criar Setor' }}" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Preview fixo à esquerda -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="briefcase" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">Setor</h2>
                </div>
            </div>
        </section>

        <!-- Formulário -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Detalhes do Setor</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Preencha as informações do setor</p>

                    <form method="POST" action="{{ isset($sector) ? route('sector.update', $sector) : route('sector.store') }}" class="flex flex-col gap-6">
                        @csrf
                        @if(isset($sector)) @method('PUT') @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Nome do Setor -->
                            <label class="label">
                                <span class="block mb-1">Nome do Setor</span>
                                <input type="text" name="name" class="input @error('name') border-red-500 @enderror"
                                    value="{{ old('name', $sector->name ?? '') }}" />
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>

                            <!-- Descrição do Setor -->
                            <label class="label">
                                <span class="block mb-1">Sigla</span>
                                <input type="text" name="acronym" class="input @error('acronym') border-red-500 @enderror"
                                    value="{{ old('acronym', $sector->acronym ?? '') }}" />
                                @error('acronym')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('sector.index') }}"
                                class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ isset($sector) ? 'Atualizar' : 'Adicionar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
