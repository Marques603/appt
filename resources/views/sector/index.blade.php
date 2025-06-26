<x-app-layout>
    <x-page-title page="Lista de Setores" header="Setores" />
    
    @section('title', 'Lista de setor | Inusittá')

    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        <!-- Header Starts -->
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
            <!-- Search -->
            <form method="GET" action="{{ route('sector.index') }}" class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:border-transparent dark:bg-slate-800 dark:focus-within:border-primary-500 md:w-72">
                <div class="flex h-full items-center px-2">
                    <i class="h-4 text-slate-400 group-focus-within:text-primary-500" data-feather="search"></i>
                </div>
                <input
                    name="search"
                    class="h-full w-full border-transparent bg-transparent px-0 text-sm placeholder-slate-400 placeholder:text-sm focus:border-transparent focus:outline-none focus:ring-0"
                    type="text"
                    value="{{ request()->input('search') }}"
                    placeholder="Buscar..."
                />
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
                                    <select class="tom-select w-full" autocomplete="off" name="status" onchange="this.form.submit()">
                                        <option value="">Selecione um status</option>
                                        <option value="1" {{ request()->input('status') == '1' ? 'selected' : '' }}>Ativo</option>
                                        <option value="0" {{ request()->input('status') == '0' ? 'selected' : '' }}>Inativo</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <button class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
              <i class="h-4" data-feather="upload"></i>
              <span class="hidden sm:inline-block">Exportar</span>
            </button>
                @can('edit', App\Models\User::class)
                <a class="btn btn-primary" href="{{ route('sector.create') }}" role="button">
                    <i data-feather="plus" height="1rem" width="1rem"></i>
                    <span class="hidden sm:inline-block">Criar</span>
                </a>
                @endcan
            </div>
        </div>
        <!-- Header Ends -->

        <!-- Table Starts -->
        <div class="table-responsive whitespace-nowrap rounded-primary">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-[5%]">
                            <input class="checkbox" type="checkbox" data-check-all data-check-all-target=".sector-checkbox" />
                        </th>
                        <th class="w-[10%] uppercase">Nome</th>
                        <th class="w-[15%] uppercase">Sigla</th>
                        <th class="w-[20%] uppercase">Funcionários</th>
                        <th class="w-[20%] uppercase">Responsável</th>
                        <th class="w-[15%] uppercase">Centro</th>
                        <th class="w-[5%] uppercase">Status</th>
                        @can('edit', App\Models\User::class)
                        <th class="w-[5%] !text-right uppercase">Ações</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($sectors as $sector)
                        <tr>
                            <td><input class="checkbox sector-checkbox" type="checkbox" /></td>
                            <td>{{ $sector->name }}</td>
                            <td>
                    <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-md">
                        {{ $sector->acronym }}
                    </span>
                </td>
                            @php
                                $users = $sector->users;
                                $count = $users->count();
                                $userNames = $users->pluck('name')->toArray();
                                $tooltipContent = implode(', ', $userNames);
                            @endphp

                            <td>
                                @if($count === 0)
                                    <span class="badge badge-soft-danger">Não há funcionários</span>
                                @elseif($count === 1)
                                    <span class="badge badge-soft-secondary" data-tooltip="tippy" data-tippy-content="{{ $tooltipContent }}">
                                        1 Funcionário
                                    </span>
                                @else
                                    <button
                                        type="button"
                                        class="badge badge-soft-secondary"
                                        data-tooltip="tippy"
                                        data-tippy-content="{{ $tooltipContent }}"
                                    >
                                        {{ $count }} funcionários
                                    </button>
                                @endif
                            </td>
                            <td>
                                
                            @if($sector->responsibleUsers->isNotEmpty())
                            <span class="badge badge-soft-info">{{ $sector->responsibleUsers->pluck ('name')->join(', ') }}</span>
                            @else
                            <span class="badge badge-soft-danger">Não definido</span>
                            @endif
                            </td>
                                @php
                                    $costCenters = $sector->costCenters;
                                    $tooltipCostCenters = $costCenters->map(fn($cc) => $cc->name . ' (' . $cc->code . ')')->toArray();
                                    $tooltipText = implode(', ', $tooltipCostCenters);
                                @endphp

                                <td>
                                    @if ($costCenters->isEmpty())
                                        <span class="badge badge-soft-danger">Não definido</span>
                                    @elseif ($costCenters->count() === 1)
                                        <span class="badge badge-soft-primary">
                                            {{ $costCenters->first()->name }} ({{ $costCenters->first()->code }})
                                        </span>
                                    @else
                                        <button
                                            type="button"
                                            class="badge badge-soft-primary"
                                            data-tooltip="tippy"
                                            data-tippy-content="{{ $tooltipText }}"
                                        >
                                            {{ $costCenters->count() }} centros de custo
                                        </button>
                                    @endif
                                </td>

                            <td>
                                @if($sector->status)
                                    <div class="badge badge-soft-success">Ativo</div>
                                @else
                                    <div class="badge badge-soft-danger">Inativo</div>
                                @endif
                            </td>
                            @can('edit', App\Models\User::class)
                            <td class="text-right">
                                <div class="flex justify-end">
                                    
                                    <div class="dropdown" data-placement="bottom-start">
                                        <div class="dropdown-toggle">
                                            
                                            <i class="w-6 text-slate-400" data-feather="more-horizontal"></i>
                                            @endcan
                                        </div>
                                        <div class="dropdown-content">
                                            <ul class="dropdown-list">
                                                <li class="dropdown-list-item">
                                                    @can('edit', App\Models\User::class)
                                                    <a href="{{ route('sector.edit', $sector->id) }}" class="dropdown-link">
                                                        <i class="h-5 text-slate-400" data-feather="edit"></i>
                                                        <span>Editar</span>
                                                    </a>
                                                    @endcan
                                                </li>
                                                @can('edit', App\Models\User::class)
                                                <li class="dropdown-list-item">
                                                    @can('edit', App\Models\User::class)
                                                    <a href="javascript:void(0)" class="dropdown-link" data-toggle="modal" data-target="#deleteModal-{{ $sector->id }}">
                                                        <i class="h-5 text-slate-400" data-feather="trash"></i>
                                                        <span>Excluir</span>
                                                    </a>
                                                    @endcan
                                                </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal de Confirmação -->
                                <div class="modal modal-centered" id="deleteModal-{{ $sector->id }}">
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
                                                <p class="text-sm text-slate-500 dark:text-slate-300">
                                                    Tem certeza que deseja excluir o setor <strong>{{ $sector->name }}</strong>?
                                                </p>
                                            </div>
                                            <div class="modal-footer flex justify-center">
                                                <form method="POST" action="{{ route('sector.destroy', $sector->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
        <!-- Table Ends -->

        <!-- Pagination -->
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
            <p class="text-xs font-normal text-slate-400">
                Mostrando {{ $sectors->firstItem() }} a {{ $sectors->lastItem() }} de {{ $sectors->total() }} resultados
            </p>
            {{ $sectors->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</x-app-layout>
