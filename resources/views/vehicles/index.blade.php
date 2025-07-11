<x-app-layout>
    <x-page-title page="Frota" header="Controle de Frota" />
    @section('title', 'Controle de Frota | Inusittá')

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
                <form method="GET" action="{{ route('vehicles.index') }}" 
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
            <a href="{{ route('vehicle_movements.index') }}" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                <i class="h-4" data-feather="search"></i>
                <span class="hidden sm:inline-block">Historico Veículos</span>
            </a>
             <a class="btn btn-primary" href="{{ route('vehicles.create') }}" role="button">
            <i data-feather="plus" height="1rem" width="1rem"></i>
            <span class="hidden sm:inline-block">Criar</span>
          </a>
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive whitespace-nowrap rounded-primary">
        <table class="table">
          <thead>
            <tr>
              <th><input class="checkbox" type="checkbox" data-check-all data-check-all-target=".vehicle-checkbox"/></th>
              <th>VEÍCULO</th>
              <th>MOTORISTA</th>
              <th>DESTINO</th>
              <th>HR SAÍDA</th>
              <th>HR RETORNO</th>
              <th>KM ATUAL</th>
              <th>STATUS</th>
              <th class="!text-right">AÇÃO</th>
            </tr>
          </thead>
          <tbody>
            @foreach($vehicles as $vehicle)
              @php
                $lastMovement = $vehicle->movements()->latest()->first();
              @endphp
              <tr>
                <td><input class="checkbox vehicle-checkbox" type="checkbox" value="{{ $vehicle->id }} " /></td>
                <td>
                  <div class="flex items-center gap-3">
                     <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-primary-500 bg-opacity-20">
                      <i class="ti ti-car text-2xl"></i>
                      </div>
                    <div>
                <h6 class="whitespace-nowrap text-sm font-medium">
                  {{ $vehicle->brand }} - {{ $vehicle->model }}
                </h6>             
                <div class="relative w-32 h-12 rounded-md overflow-hidden border border-black">
              <img 
                src="{{ asset('images/placa.png') }}" 
                alt="Placa" 
                class="w-full h-full object-cover">
              <div class="absolute inset-0 flex items-end justify-center pb-2">
                  <span class="text-[16px] font-bold text tracking-wider">
                    {{ $vehicle->plate }}
                  </span>
                </div>
                </div>
              </div>
                </td>
                <td>
                  @if($vehicle->status === 1)
                    <span class="text-xs text-slate-500">---</span>
                  @else
                    <span>{{ $lastMovement?->user?->name ?? '---' }}</span>
                  @endif
                </td>
                <td>
                @if($vehicle->status === 1)
                    <span class="text-xs text-slate-500">---</span>
                @else
                    <span
                        class="inline-block px-2 py-1 text-xs  bg-blue-100 text-blue-800 rounded-md truncate cursor-pointer"
                        data-tooltip="tippy"
                        data-tippy-content="{{ $lastMovement?->observations ?: 'Sem observações' }}"
                    >
                        {{ $lastMovement?->destination ?? '---' }}
                    </span>
                @endif
              </td>
                <td>
                  @if($vehicle->status === 1)
                    <span class="text-xs text-slate-500">---</span>
                  @else
                    <span>{{ $lastMovement?->departure_time ? \Carbon\Carbon::parse($lastMovement->departure_time)->format('d/m/Y H:i') : '---' }}</span>
                  @endif
                </td>
                <td>
                <span>
                  {{ $lastMovement && $lastMovement->return_time 
                      ? \Carbon\Carbon::parse($lastMovement->return_time)->format('d/m/Y H:i') 
                      : '---' }}
                </span>
              </td>
                <td>{{ $vehicle->current_km }} km</td>
                <td>
                  @if($vehicle->status === 1)
                    <div class="badge badge-soft-success">Disponível</div>
                  @else
                    <div class="badge badge-soft-danger">Na Estrada</div>
                  @endif
                </td>
                <td>
                  <div class="flex justify-end">
                    <div class="dropdown" data-placement="bottom-start">
                      <div class="dropdown-toggle">
                        <i class="w-6 text-slate-400" data-feather="more-horizontal"></i>
                      </div>
                      <div class="dropdown-content">
                        <ul class="dropdown-list">
                          <li class="dropdown-list-item">
                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="dropdown-link">
                              <i class="h-5 text-slate-400" data-feather="edit"></i> <span>Editar</span>
                            </a>
                          </li>
                          @if($vehicle->status === 1)
                          <li class="dropdown-list-item">
                            <a href="{{ route('vehicles.movement.create', $vehicle) }}" class="dropdown-link">
                              <i class="h-5 text-slate-400" data-feather="log-out"></i> <span>Registrar Saída</span>
                            </a>
                          </li>
                          @elseif($lastMovement)
                          <li class="dropdown-list-item">
                            <a href="{{ route('vehicles.movement.edit', $lastMovement) }}" class="dropdown-link">
                              <i class="h-5 text-slate-400" data-feather="log-in"></i> <span>Registrar Retorno</span>
                            </a>
                          </li>
                          @endif
                          <li class="dropdown-list-item">
                            <a href="javascript:void(0)" class="dropdown-link" data-toggle="modal" data-target="#deleteModal-{{ $vehicle->id }}">
                              <i class="h-5 text-slate-400" data-feather="trash"></i> <span>Excluir</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>

                  <!-- Modal direto aqui -->
                  <div class="modal modal-centered" id="deleteModal-{{ $vehicle->id }}">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        <div class="modal-body">
                          <p class="text-sm text-slate-500">
                            Deseja excluir <strong>{{ $vehicle->model }} - {{ $vehicle->plate }}</strong>?
                          </p>
                        </div>
                        <div class="modal-footer flex justify-center">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                          <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger">Sim, excluir</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
        <p class="text-xs font-normal text-slate-400">
          Mostrando {{ $vehicles->firstItem() }} a {{ $vehicles->lastItem() }} de {{ $vehicles->total() }} veículos
        </p>
        {{ $vehicles->appends(request()->query())->links('vendor.pagination.custom') }}
      </div>
    </div>
    
</x-app-layout>
