<x-app-layout>
    <x-page-title page="Movimentos" header="Movimentações dos Veículos" />
    @section('title', 'Movimentações | Inusittá')

    @if(session('success'))
    <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
      <p>{{ session('success') }}</p>
    </div>
    @endif

 
        <div class="space-y-4">
        {{-- Barra de ações --}}
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
            {{-- Busca --}}
            <div class="flex w-full md:w-auto">
                <form method="GET" action="{{ route('vehicle_movements.index') }}" 
                    class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm 
                        focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 
                        dark:bg-slate-800 sm:max-w-xs">
                    <div class="flex h-full items-center px-2">
                        <i class="h-4 text-slate-400 group-focus-within:text-primary-500" data-feather="search"></i>
                    </div>
                    <input
                        name="search"
                        class="h-full w-full border-transparent bg-transparent px-0 text-sm placeholder-slate-400 
                            focus:border-transparent focus:outline-none focus:ring-0"
                        type="text"
                        value="{{ request('search') }}"
                        placeholder="Buscar por nome ou código..."
                    />
                </form>
            </div>



        <!-- Actions -->
        <div class="flex w-full items-center justify-between gap-x-4 md:w-auto">
          <div class="flex items-center gap-x-4">
            <!-- Filtros -->
            <div class="dropdown" data-placement="bottom-end">
              <div class="dropdown-toggle">
                <button type="button" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                  <i class="w-4 h-4" data-feather="filter"></i>
                  <span class="hidden sm:inline-block">Filtros</span>
                  <i class="w-4 h-4" data-feather="chevron-down"></i>
                </button>
              </div>
              <div class="dropdown-content w-72 !overflow-visible">
                <ul class="dropdown-list space-y-4 p-4">
                  <li class="dropdown-list-item">
                    <h2 class="my-1 text-sm font-medium">Status</h2>
                    <select class="tom-select w-full" name="status" onchange="this.form.submit()">
                      <option value="">Todos</option>
                      <option value="1" @selected(request('status') == '1')>Disponível</option>
                      <option value="2" @selected(request('status') == '2')>Em Trânsito</option>
                    </select>
                  </li>
                  <li class="dropdown-list-item">
                    <h2 class="my-1 text-sm font-medium">Marca</h2>
                    <select class="tom-select w-full" name="brand" onchange="this.form.submit()">
                      <option value="">Todas</option>
                      @foreach($brands as $brand)
                        <option value="{{ $brand }}" @selected(request('brand') == $brand)>{{ $brand }}</option>
                      @endforeach
                    </select>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Export -->
            <a href="{{ route('vehicles.export.csv') }}" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                <i class="h-4" data-feather="upload"></i>
                <span class="hidden sm:inline-block">Exportar CSV</span>
            </a>
            <a href="{{ route('vehicles.export.pdf') }}" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                <i class="h-4" data-feather="upload"></i>
                <span class="hidden sm:inline-block">Exportar PDF</span>
            </a>
          </div>

          
            {{-- Botão para criar nova movimentação --}}
            <a href="{{ route('vehicles.index') }}" class="btn btn-primary" title="Voltar para veículos">
                <i data-feather="arrow-left" height="1rem" width="1rem"></i>
                <span class="hidden sm:inline-block">Veículos</span>
            </a>
        </div>
      </div>


        <!-- Table -->
        <div class="table-responsive whitespace-nowrap rounded-primary">
            <table class="table">
                <thead>
                    <tr>
                        <th>VEÍCULO</th>
                        <th>MOTORISTA</th>
                        <th>DESTINO</th>
                        <th>KM SAÍDA</th>
                        <th>HR SAÍDA</th>
                        <th>KM RETORNO</th>
                        <th>HR RETORNO</th>
                        <th>OBSERVAÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $movement)
                    <tr>
                        <td>
                            <div class="whitespace-nowrap">
                                <strong>{{ $movement->vehicle->brand ?? '---' }}</strong> - {{ $movement->vehicle->plate ?? '---' }}<br>
                            </div>
                        </td>
                        <td>{{ $movement->user->name ?? '---' }}</td>
                        <td>{{ $movement->destination }}</td>
                        <td>{{ $movement->departure_km }} km</td>
                        <td>{{ $movement->departure_time ? \Carbon\Carbon::parse($movement->departure_time)->format('d/m/Y H:i') : '---' }}</td>
                        <td>{{ $movement->return_km ?? '---' }}</td>
                        <td>{{ $movement->return_time ? \Carbon\Carbon::parse($movement->return_time)->format('d/m/Y H:i') : '---' }}</td>
                        <td class="truncate max-w-xs" title="{{ $movement->observations ?? 'Sem observações' }}">
                            {{ Str::limit($movement->observations ?? 'Sem observações', 40) }}
                        </td>
                     
                    </tr>
                    @endforeach

                    @if($movements->isEmpty())
                    <tr>
                        <td colspan="10" class="text-center text-slate-400 py-8">Nenhuma movimentação encontrada.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
            <p class="text-xs font-normal text-slate-400">
                Mostrando {{ $movements->firstItem() ?? 0 }} a {{ $movements->lastItem() ?? 0 }} de {{ $movements->total() }} movimentações
            </p>
            {{ $movements->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</x-app-layout>
