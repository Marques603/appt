<x-app-layout>
    <!-- Título da Página -->
    <x-page-title page="Lista de Empresas" pageUrl="{{ route('company.index') }}" header="{{ isset($company) ? 'Editar Empresa' : 'Criar Empresa' }}" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Preview fixo à esquerda -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="briefcase" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">Empresa</h2>
                </div>
            </div>
        </section>

        <!-- Formulário -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Detalhes da Empresa</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Preencha as informações da empresa</p>

                    <form method="POST" action="{{ isset($company) ? route('company.update', $company) : route('company.store') }}" class="flex flex-col gap-6">
                        @csrf
                        @if(isset($company)) @method('PUT') @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Nome Fantasia -->
                            <label class="label">
                                <span class="block mb-1">Nome Fantasia</span>
                                <input type="text" name="name" class="input @error('name') border-red-500 @enderror"
                                    value="{{ old('name', $company->name ?? '') }}" />
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>

                            <!-- Razão Social -->
                            <label class="label">
                                <span class="block mb-1">Razão Social</span>
                                <input type="text" name="corporate_name" class="input @error('corporate_name') border-red-500 @enderror"
                                    value="{{ old('corporate_name', $company->corporate_name ?? '') }}" />
                                @error('corporate_name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- CNPJ -->
                            <label class="label">
                                <span class="block mb-1">CNPJ</span>
                                <input type="text" name="cnpj" class="input @error('cnpj') border-red-500 @enderror"
                                    value="{{ old('cnpj', $company->cnpj ?? '') }}" />
                                @error('cnpj')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>


                        <!-- Botões -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('company.index') }}"
                                class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ isset($company) ? 'Atualizar' : 'Adicionar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
