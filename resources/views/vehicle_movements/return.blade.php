<x-app-layout>
    <x-page-title page="Veículos" pageUrl="{{ route('vehicles.index') }}" header="Registrar Retorno" />
    @section('title', 'Registrar retorno | Inusittá')

     @if(session('success'))
    <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
      <p>{{ session('success') }}</p>
    </div>
    @endif

     @if ($errors->any())
    <div class="fixed top-0 right-0 m-4 p-4 bg-rose-600 text-white rounded shadow-lg z-50" role="alert">
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif



    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Preview fixo à esquerda -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card shadow-sm rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="card-body flex flex-col items-center p-6">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700">
                        <i data-feather="log-in" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-sm font-medium text-center text-slate-700 dark:text-slate-200 uppercase tracking-wide">
                        Retorno do Veículo
                    </h2>
                </div>
            </div>
        </section>

        <section class="col-span-1 flex flex-col gap-6 lg:col-span-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-200">Retorno do Veículo</h2>
                    <form method="POST" action="{{ route('vehicles.movement.update', $movement) }}" class="flex flex-col gap-5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <label class="label">
                                <span>Placa</span>
                                <input type="text" class="input bg-gray-100 dark:bg-slate-800" value="{{ $movement->vehicle->plate }}" readonly />
                            </label>
                            <label class="label">
                                <span>KM Saída</span>
                                <input type="number" class="input bg-gray-100 dark:bg-slate-800" value="{{ $movement->departure_km }}" readonly />
                            </label>
                        </div>

                        <label class="label">
                        <span>KM Retorno</span>
                        <input type="number" name="return_km" class="input" value="{{ old('return_km') }}" required />
                      
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
