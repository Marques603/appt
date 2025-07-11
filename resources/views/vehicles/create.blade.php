<x-app-layout>
    <x-page-title page="Veículos" pageUrl="{{ route('vehicles.index') }}" header="Cadastrar Veículo" />
    @section('title', 'Cadastrar veículo | Inusittá')

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Preview fixo à esquerda -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card shadow-sm rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="card-body flex flex-col items-center p-6">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700">
                        <i data-feather="layers" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-sm font-medium text-center text-slate-700 dark:text-slate-200 uppercase tracking-wide">
                        Veículo
                    </h2>
                </div>
            </div>
        </section>

        <!-- Form principal -->
        <section class="col-span-1 lg:col-span-3">
            <div class="card shadow-sm rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="card-body p-6">
                    <h2 class="text-lg font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide mb-4">
                        Dados do Veículo
                    </h2>
                    <form method="POST" action="{{ route('vehicles.store') }}" class="flex flex-col gap-6">
                        @csrf

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <label class="flex flex-col gap-1 text-sm text-slate-600 dark:text-slate-300">
                                <span>Placa</span>
                                <input type="text" name="plate" class="input" value="{{ old('plate') }}" required />
                            </label>
                            <label class="flex flex-col gap-1 text-sm text-slate-600 dark:text-slate-300">
                                <span>Marca</span>
                                <input type="text" name="brand" class="input" value="{{ old('brand') }}" required />
                            </label>
                            <label class="flex flex-col gap-1 text-sm text-slate-600 dark:text-slate-300">
                                <span>Modelo</span>
                                <input type="text" name="model" class="input" value="{{ old('model') }}" required />
                            </label>
                            <label class="flex flex-col gap-1 text-sm text-slate-600 dark:text-slate-300">
                                <span>KM Atual</span>
                                <input type="number" name="current_km" class="input" value="{{ old('current_km') }}" required />
                            </label>
                        </div>

                        <label class="flex flex-col gap-1 text-sm text-slate-600 dark:text-slate-300">
                            <span>Observações</span>
                            <textarea name="observations" rows="4" class="input">{{ old('observations') }}</textarea>
                        </label>

                        <div class="flex justify-end gap-3 mt-2">
                            <a href="{{ route('vehicles.index') }}" class="btn border border-slate-300 dark:border-slate-600">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
