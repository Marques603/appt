<x-app-layout>
    <x-page-title page="Veículos" header="Lista de Veículos" />
    @section('title', 'Veículos | Inusittá')

    @if(session('success'))
    <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
      <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="space-y-4">
      <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
        <!-- Search -->
        <form method="GET" action="{{ route('vehicles.index') }}" class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:bg-slate-800 dark:focus-within:border-primary-500 md:w-72">
          <div class="flex h-full items-center px-2">
            <i class="h-4 text-slate-400 group-focus-within:text-primary-500" data-feather="search"></i>
          </div>
          <input name="search" value="{{ request('search') }}" class="h-full w-full border-transparent bg-transparent px-0 text-sm placeholder-slate-400 placeholder:text-sm focus:border-transparent focus:outline-none focus:ring-0" type="text" placeholder="Buscar por placa ou modelo"/>
        </form>

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

            <!-- Editar em massa -->
            <a class="btn btn-danger hidden" id="edit-vehicle-button" href="#" role="button">
                <i data-feather="edit" height="1rem" width="1rem"></i>
                <span class="hidden sm:inline-block">Editar</span>
            </a>
          </div>

          <a class="btn btn-primary" href="{{ route('vehicles.create') }}" role="button">
            <i data-feather="plus" height="1rem" width="1rem"></i>
            <span class="hidden sm:inline-block">Cadastrar</span>
          </a>
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive whitespace-nowrap rounded-primary">
        <table class="table">
          <thead>
            <tr>
              <th class="w-[5%]"><input class="checkbox" type="checkbox" data-check-all data-check-all-target=".vehicle-checkbox"/></th>
              <th class="w-[10%] uppercase">Veículo</th>
              <th class="w-[20%] uppercase">Placa</th>
              <th class="w-[30%] uppercase">Marca</th>
              <th class="w-[30%] uppercase">Cor</th>
              <th class="w-[15%] uppercase">Km Atual</th>
              <th class="w-[15%] uppercase">Status</th>
              <th class="w-[5%] !text-right uppercase">Ação</th>
            </tr>
          </thead>
          <tbody>
            @foreach($vehicles as $vehicle)
            <tr>
              <td><input class="checkbox vehicle-checkbox" type="checkbox" value="{{ $vehicle->id }}" /></td>
              <td>
                <div class="flex items-center gap-3">
                  <div class="avatar avatar-circle">
                    <img class="avatar-img" src="{{ $vehicle->photo ? asset('storage/' . $vehicle->photo) : asset('images/vehicle-placeholder.png') }}" alt="{{ $vehicle->model }}" />
                  </div>
                  <div>
                    <h6 class="whitespace-nowrap text-sm font-medium text-slate-700 dark:text-slate-100">
                      {{ $vehicle->model }}
                    </h6>
                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $vehicle->brand }}</p>
                  </div>
                </div>
              </td>
              <td>{{ $vehicle->plate }}</td>
              <td>{{ $vehicle->brand }}</td>
              <td>{{ $vehicle->brand }}</td>
              <td>{{ $vehicle->current_km }} km</td>
              <td>
                @if($vehicle->status === 1)
                  <div class="badge badge-soft-success">Disponível</div>
                @else
                  <div class="badge badge-soft-warning">Em Trânsito</div>
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
                        @else
                        <li class="dropdown-list-item">
                          <a href="{{ route('vehicles.movement.edit', $vehicle->movements()->latest()->first()) }}" class="dropdown-link">
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

                <!-- Modal -->
                <div class="modal modal-centered" id="deleteModal-{{ $vehicle->id }}">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <div class="flex items-center justify-between">
                          <h6>Confirmação</h6>
                          <button type="button" class="btn btn-plain-secondary" data-dismiss="modal">
                            <i data-feather="x" width="1.5rem" height="1.5rem"></i>
                          </button>
                        </div>
                      </div>
                      <div class="modal-body">
                        <p class="text-sm text-slate-500 dark:text-slate-300">Deseja excluir <strong>{{ $vehicle->model }} - {{ $vehicle->plate }}</strong>?</p>
                      </div>
                      <div class="modal-footer flex justify-center">
                        <div class="flex items-center justify-center gap-4">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                          <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger">Sim, excluir</button>
                          </form>
                        </div>
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
        <p class="text-xs font-normal text-slate-400">Mostrando {{ $vehicles->firstItem() }} a {{ $vehicles->lastItem() }} de {{ $vehicles->total() }} veículos</p>
        {{ $vehicles->appends(request()->query())->links('vendor.pagination.custom') }}
      </div>
    </div>
</x-app-layout>
