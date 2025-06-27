<!-- movements/return.blade.php -->
<x-app-layout>
    <x-page-title page="Veículos" pageUrl="{{ route('vehicles.index') }}" header="Registrar Retorno" />
    @section('title', 'Registrar Retorno | Inusittá')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <section class="col-span-1"></section>
        <section class="col-span-1 flex flex-col gap-6 lg:col-span-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700">Retorno do Veículo</h2>
                    <form method="POST" action="{{ route('vehicles.movement.update', $movement) }}" class="flex flex-col gap-5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <label class="label">
                                <span>Placa</span>
                                <input type="text" class="input bg-gray-100" value="{{ $movement->vehicle->plate }}" disabled />
                            </label>
                            <label class="label">
                                <span>KM Saída</span>
                                <input type="number" class="input bg-gray-100" value="{{ $movement->departure_km }}" disabled />
                            </label>
                        </div>

                        <label class="label">
                            <span>KM Retorno</span>
                            <input type="number" name="return_km" class="input" value="{{ old('return_km') }}" required />
                        </label>

                        <label class="label">
                            <span>Observações</span>
                            <textarea name="observations" rows="4" class="input">{{ old('observations', $movement->observations) }}</textarea>
                        </label>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('vehicles.index') }}" class="btn border">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar Retorno</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
