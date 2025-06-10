<x-app-layout>
    <x-page-title page="Lista de Categoria" header="Lista de Categorias" />

    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
            <form method="GET" action="{{ route('categorie.index') }}" class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:border-transparent dark:bg-slate-800 dark:focus-within:border-primary-500 md:w-72">
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

            <div class="flex w-full items-center justify-between gap-x-4 md:w-auto">
                <div class="flex items-center gap-x-4">
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
                          <h2 class="my-1 text-sm font-medium">Setor</h2>
                          <select class="tom-select w-full" autocomplete="off">
                            <option value="">Selecione um setor</option>
                            <option value="1">Tecnologia</option>
                            <option value="2">Qualidade</option>
                            <option value="3">Processos</option>
                          </select>
                        </li>
                        <li class="dropdown-list-item">
                          <h2 class="my-1 text-sm font-medium">Status</h2>
                          <select class="tom-select w-full" autocomplete="off">
                            <option value="">Selecione um status</option>
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
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
@can('edit', App\Models\User::class)
                <a class="btn btn-primary" href="{{ route('categorie.create') }}" role="button">
                    <i data-feather="plus" height="1rem" width="1rem"></i>
                    <span class="hidden sm:inline-block">Criar</span>
                    @endcan
                </a>
            </div>
        </div>

        <div class="table-responsive whitespace-nowrap rounded-primary">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-[5%]">
                            <input class="checkbox" type="checkbox" data-check-all data-check-all-target=".procedure-checkbox" />
                        </th>
                        <th class="w-[20%] uppercase">Nome</th>
                        <th class="w-[20%] uppercase">Descrição</th>
                        <th class="w-[20%] uppercase">Responsável</th>
                        @can('edit', App\Models\User::class)
                        <th class="w-[20%] uppercase">Documentos</th>
                        @endcan
                        <th class="w-[10%] uppercase">Status</th>
                        <th class="w-[5%] !text-right uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $categorie)
                        <tr>
                            <td>
                                <input class="checkbox procedure-checkbox" type="checkbox" />
                            </td>
                            <td>{{ $categorie->name }}</td>
                            <td>{{ $categorie->description ?? 'Sem descrição' }}</td>
                            <td>{{ $categorie->responsibleUsers->isNotEmpty() ? $categorie->responsibleUsers->pluck('name')->join(', ') : 'não há responsável vinculado' }}</td>
                            @can('edit', App\Models\User::class)<td>
                                {{ $categorie->documents_count ?? 0 }}</td>
                            @endcan
                            <td>
                                @if($categorie->status)
                                    <div class="badge badge-soft-success">Ativo</div>
                                @else
                                    <div class="badge badge-soft-danger">Inativo</div>
                                @endif
                            </td>
                            
                            <td class="text-right">
                                <div class="flex justify-end">
                                    <div class="dropdown" data-placement="bottom-start">
                                        <div class="dropdown-toggle">
                                            <i class="w-6 text-slate-400" data-feather="more-horizontal"></i>
                                        </div>
                                        
                                        <div class="dropdown-content">
                                            <ul class="dropdown-list">
                                                <li class="dropdown-list-item">
                                                    @can('edit', App\Models\User::class)
                                                    <a href="{{ route('categorie.edit', $categorie->id) }}" class="dropdown-link">
                                                        <i class="h-5 text-slate-400" data-feather="edit"></i>
                                                        <span>Editar</span>
                                                    </a>
                                                </li>
                                                <li class="dropdown-list-item">
                                                    <a href="javascript:void(0)" class="dropdown-link" data-toggle="modal" data-target="#deleteModal-{{ $categorie->id }}">
                                                        <i class="h-5 text-slate-400" data-feather="trash"></i>
                                                        <span>Excluir</span>
                                                        @endcan
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="modal modal-centered" id="deleteModal-{{ $categorie->id }}">
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
                                                    Tem certeza que deseja excluir <strong>{{ $categorie->name }}</strong>?
                                                </p>
                                            </div>
                                            <div class="modal-footer flex justify-center">
                                                <form method="POST" action="{{ route('categorie.destroy', $categorie->id) }}">
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

        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
            <p class="text-xs font-normal text-slate-400">
                Mostrando {{ $categories->firstItem() }} a {{ $categories->lastItem() }} de {{ $categories->total() }} resultados
            </p>
            {{ $categories->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</x-app-layout>
