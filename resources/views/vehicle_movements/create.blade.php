<x-app-layout>
    <x-page-title page="Veículos" pageUrl="{{ route('vehicles.index') }}" header="Registrar Saída" />
    @section('title', 'Registrar Saída | Inusittá')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <section class="col-span-1"></section>
        <section class="col-span-1 flex flex-col gap-6 lg:col-span-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700">Saída do Veículo</h2>
                    <form method="POST" action="{{ route('vehicles.movement.store', $vehicle) }}" class="flex flex-col gap-5">
                        @csrf
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <label class="label">
                                <span>Placa</span>
                                <input type="text" class="input bg-gray-100" value="{{ $vehicle->plate }}" disabled />
                            </label>
                            <label class="label">
                                <span>KM Atual</span>
                                <input type="number" class="input bg-gray-100" value="{{ $vehicle->current_km }}" disabled />
                            </label>
                        </div>

                        <label class="label">
                            <span>Usuário Responsável</span>
                            <select name="user_id" class="input" required>
                                <option value="">Selecione</option>
                                @foreach(App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </label>

                        <label class="label">
                            <span>Destino</span>
                            <input type="text" name="destination" class="input" value="{{ old('destination') }}" required />
                        </label>

                        <label class="label">
                            <span>Observações</span>
                            <textarea name="observations" rows="4" class="input">{{ old('observations') }}</textarea>
                        </label>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('vehicles.index') }}" class="btn border">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar Saída</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
