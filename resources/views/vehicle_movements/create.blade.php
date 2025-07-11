<x-app-layout>
    <x-page-title page="Veículos" pageUrl="{{ route('vehicles.index') }}" header="Registrar Saída" />
    @section('title', 'Registrar Saída | Inusittá')

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Preview fixo à esquerda -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card shadow-sm rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="card-body flex flex-col items-center p-6">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700">
                        <i data-feather="log-out" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-sm font-medium text-center text-slate-700 dark:text-slate-200 uppercase tracking-wide">
                        Saída do Veículo
                    </h2>
                </div>
            </div>
        </section>

        <!-- Form -->
        <section class="col-span-1 flex flex-col gap-6 lg:col-span-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-200">Saída do Veículo</h2>
                    <form method="POST" action="{{ route('vehicles.movement.store', $vehicle) }}" class="flex flex-col gap-5">
                        @csrf
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <label class="label">
                                <span>Placa</span>
                                <input type="text" class="input bg-gray-100 dark:bg-slate-800" value="{{ $vehicle->plate }}" readonly />
                            </label>
                            <label class="label">
                                <span>KM Atual</span>
                                <input type="number" class="input bg-gray-100 dark:bg-slate-800" value="{{ $vehicle->current_km }}" readonly />
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <label class="label">
                        <span>Usuário Responsável</span>
                        <select name="user_id" class="tom-select w-full dark:bg-slate-800" required>
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
                        </div>

                        <label class="label">
                        <span>Motivo "Observações"</span>
                        <textarea name="observations" rows="4" class="input" required>{{ old('observations') }}</textarea>
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
