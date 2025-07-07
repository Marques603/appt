<x-app-layout>
    <!-- Título da Página -->
   
     <x-page-title page="Criar plano" pageUrl="{{ route('plans.index') }}" header="Criar plano" />

    @section('title', 'Criar plano | Inusittá')


    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Preview fixo à esquerda -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="briefcase" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">Plano</h2>
                </div>
            </div>
        </section>

        <!-- Formulário -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Detalhes do Plano</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Preencha as informações do plano</p>

                    <form method="POST" action="{{ isset($plan) ? route('plans.update', $plan) : route('plans.store') }}" class="flex flex-col gap-6">
                        @csrf
                        @if(isset($plan)) @method('PUT') @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Nome do Plan -->
                            <label class="label">
                                <span class="block mb-1">Nome do Plano</span>
                                <input type="text" name="name" class="input @error('name') border-red-500 @enderror"
                                    value="{{ old('name', $plan->name ?? '') }}" />
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>

                            <!-- Descrição do Plan -->
                            <label class="label">
                                <span class="block mb-1">Descrição</span>
                                <input type="text" name="description" class="input @error('description') border-red-500 @enderror"
                                    value="{{ old('description', $plan->description ?? '') }}" />
                                @error('description')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('plans.index') }}"
                                class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ isset($plan) ? 'Atualizar' : 'Adicionar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
