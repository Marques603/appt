<x-app-layout>
    <x-page-title page="Lista de Empresas" header="Empresas" />

    @section('title', 'Lista de Empresas | Inusittá')

    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        <!-- Header Starts -->
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
            <!-- Search -->
            <form method="GET" action="{{ route('company.index') }}" class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:border-transparent dark:bg-slate-800 dark:focus-within:border-primary-500 md:w-72">
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
                                <i class="w-4" data-feather="filter"></i>
                                <span class="hidden sm:inline-block">Filtros</span>
                                <i class="w-4" data-feather="chevron-down"></i>
                            </button>
                        </div>
                        <div class="dropdown-content w-72 !overflow-visible">
                            <ul class="dropdown-list space-y-4 p-4">
                                <li class="dropdown-list-item">
                                    <h2 class="my-1 text-sm font-medium">Status</h2>
                                    <select class="tom-select w-full" autocomplete="off" name="status" onchange="this.form.submit()">
                                        <option value="">Selecione um status</option>
                                        <option value="1" {{ request()->input('status') == '1' ? 'selected' : '' }}>Ativa</option>
                                        <option value="0" {{ request()->input('status') == '0' ? 'selected' : '' }}>Inativa</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <button class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                        <i class="h-4" data-feather="upload"></i>
                        <span class="hidden sm:inline-block">Exportar</span>
                    </button>
                </div>
                <!-- Botão de Criar -->
                @can('edit', App\Models\User::class)
                <a class="btn btn-primary" href="{{ route('company.create') }}" role="button">
                    <i data-feather="plus" height="1rem" width="1rem"></i>
                    <span class="hidden sm:inline-block">Criar</span>
                @endcan    
                </a>
            </div>
        </div>
        <!-- Header Ends -->

        <!-- Table Starts -->
        <div class="table-responsive whitespace-nowrap rounded-primary">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-[5%]">
                            <input class="checkbox" type="checkbox" data-check-all data-check-all-target=".company-checkbox" />
                        </th>
                        <th class="w-[20%] uppercase">Nome Fantasia</th>
                        <th class="w-[25%] uppercase">Razão Social</th>
                        <th class="w-[20%] uppercase">CNPJ</th>
                        <th class="w-[20%] uppercase">Responsável</th>
                        <th class="w-[5%] uppercase">Status</th>
                        @can('edit', App\Models\User::class)
                        <th class="w-[5%] !text-right uppercase">Ações</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <td><input class="checkbox company-checkbox" type="checkbox" /></td>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->corporate_name }}</td>
                            <td>{{ $company->cnpj }}</td>
                            <td>
                            @if($company->responsibles->isNotEmpty())
                            {{ $company->responsibles->first()->name }}
                            @else
                            <span>não há responsável vinculado</span>
                            @endif
                            </td>
                            <td>
                                @if($company->status)
                                    <div class="badge badge-soft-success">Ativa</div>
                                @else
                                    <div class="badge badge-soft-danger">Inativa</div>
                                @endif
                            </td>
                            @can('edit', App\Models\User::class)
                            <td class="text-right">
                                <div class="flex justify-end">
                                    <div class="dropdown" data-placement="bottom-start">
                                        <div class="dropdown-toggle">
                                            <i class="w-6 text-slate-400" data-feather="more-horizontal"></i>
                                        </div>
                                        <div class="dropdown-content">
                                            <ul class="dropdown-list">
                                                <li class="dropdown-list-item">
                                                    <a href="{{ route('company.edit', $company->id) }}" class="dropdown-link">
                                                        <i class="h-5 text-slate-400" data-feather="edit"></i>
                                                        <span>Editar</span>
                                                    </a>
                                                </li>
                                                <li class="dropdown-list-item">
                                                    <a href="javascript:void(0)" class="dropdown-link" data-toggle="modal" data-target="#deleteModal-{{ $company->id }}">
                                                        <i class="h-5 text-slate-400" data-feather="trash"></i>
                                                        <span>Excluir</span>
                                                    </a>
                                                    @endcan
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de Confirmação -->
                                <div class="modal modal-centered" id="deleteModal-{{ $company->id }}">
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
                                                    Tem certeza que deseja excluir <strong>{{ $company->name }}</strong>?
                                                </p>
                                            </div>
                                            <div class="modal-footer flex justify-center">
                                                <form method="POST" action="{{ route('company.destroy', $company->id) }}">
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
                Mostrando {{ $companies->firstItem() }} a {{ $companies->lastItem() }} de {{ $companies->total() }} resultados
            </p>
            {{ $companies->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</x-app-layout>
