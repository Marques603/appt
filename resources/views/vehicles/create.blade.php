<!-- create.blade.php -->
<x-app-layout>
    <x-page-title page="Veículos" pageUrl="{{ route('vehicles.index') }}" header="Cadastrar Veículo" />
    @section('title', 'Cadastrar veículo | Inusittá')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <section class="col-span-1"></section>
        <section class="col-span-1 flex flex-col gap-6 lg:col-span-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700">Dados do Veículo</h2>
                    <form method="POST" action="{{ route('vehicles.store') }}" class="flex flex-col gap-5">
                        @csrf
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <label class="label">
                                <span>Placa</span>
                                <input type="text" name="plate" class="input" value="{{ old('plate') }}" required />
                            </label>
                            <label class="label">
                                <span>Marca</span>
                                <input type="text" name="brand" class="input" value="{{ old('brand') }}" required />
                            </label>
                            <label class="label">
                                <span>Modelo</span>
                                <input type="text" name="model" class="input" value="{{ old('model') }}" required />
                            </label>
                            <label class="label">
                                <span>KM Atual</span>
                                <input type="number" name="current_km" class="input" value="{{ old('current_km') }}" required />
                            </label>
                        </div>
                        <label class="label">
                            <span>Observações</span>
                            <textarea name="observations" rows="4" class="input">{{ old('observations') }}</textarea>
                        </label>
                        <div class="flex justify-end gap-4">
                            <a href="{{ route('vehicles.index') }}" class="btn border">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>