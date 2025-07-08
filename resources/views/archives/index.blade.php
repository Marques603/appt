<x-app-layout>
    <x-page-title page="Arquivos" header="Lista de Arquivos" />

    @section('title', 'Lista de Arquivos | Inusittá')
    
    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
            <form method="GET" action="{{ route('archives.index') }}" class="group flex h-10 w-full max-w-xs items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:bg-slate-800">
                <div class="flex h-full items-center px-2">
                    <i class="h-4 text-slate-400 group-focus-within:text-primary-500" data-feather="search"></i>
                </div>
                <input
                    name="search"
                    class="h-full w-full border-transparent bg-transparent px-0 text-sm placeholder-slate-400 placeholder:text-sm focus:border-transparent focus:outline-none focus:ring-0"
                    type="text"
                    value="{{ request('search') }}"
                    placeholder="Buscar..."
                />
            </form>

            <div class="flex items-center gap-x-4">
        
        
            </div>
        </div>

        <div class="table-responsive whitespace-nowrap rounded-primary">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-[5%]">
                            <input class="checkbox" type="checkbox" data-check-all data-check-all-target=".document-checkbox" />
                        </th>
                        <th class="w-[10%] uppercase">Código</th>
                        <th class="w-[30%] uppercase">Descrição</th>
                        <th class="w-[5%] uppercase">Revisão</th>
                        <th class="w-[15%] uppercase">Pastas</th>
                        <th class="w-[15%] uppercase">Setores</th>
                        <th class="w-[5%] uppercase">Status</th>
                        <th class="w-[5%] !text-right uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archives as $archive)
                        <tr>
                            <td>
                                <input class="checkbox document-checkbox" type="checkbox" />
                            </td>
                            <td>{{ $archive->code }}</td>
                            <td>{{ $archive->description }}</td>
                            <td>{{ $archive->revision ?? '-' }}</td>
                            <td>
                                @foreach($archive->folders as $folder)
                            <span class="badge badge-soft-primary">{{ $folder->fullPath() }}</span>
                            @endforeach

                            </td>
                            <td>
                                @foreach ($archive->plans as $plan)
                                    <span class="badge badge-soft-secondary">{{ $plan->name }}</span>
                                @endforeach
                            </td>   
                            <td>
                                @if($archive->status)
                                    <div class="badge badge-soft-success">Ativo</div>
                                @else
                                    <div class="badge badge-soft-danger">Inativo</div>
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
                                                    <a href="{{ route('archives.show', $archive->id) }}" target="_blank" class="dropdown-link">
                                                        <i class="h-5 text-slate-400" data-feather="eye"></i>
                                                        <span>Ver</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de exclusão -->
                                <div class="modal modal-centered" id="deleteModal-{{ $archive->id }}">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6>Confirmação</h6>
                                                <button type="button" class="btn btn-plain-secondary" data-dismiss="modal">
                                                    <i data-feather="x" width="1.5rem" height="1.5rem"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-sm text-slate-500 dark:text-slate-300">
                                                    Tem certeza que deseja excluir <strong>{{ $archive->code }}</strong>?
                                                </p>
                                            </div>
                                            <div class="modal-footer flex justify-center">
                                                <form method="POST" action="{{ route('archives.destroy', $archive->id) }}">
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
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Nenhum arquivo encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
            <p class="text-xs font-normal text-slate-400">
                Mostrando {{ $archives->firstItem() }} a {{ $archives->lastItem() }} de {{ $archives->total() }} resultados
            </p>
            {{ $archives->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</x-app-layout>
