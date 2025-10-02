<x-app-layout>
    <x-page-title page="Convênios" header="Controle de Convênios" />
    @section('title', 'Controle de Convênios | Inusittá')

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
                <form method="GET" action="{{ route('agreements.index') }}" 
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
                        placeholder="Buscar por nome ou contato..."
                    />
                </form>
            </div>

            {{-- Botão para criar novo convênio --}}
            <a href="{{ route('agreements.create') }}" class="btn btn-primary">
                <i data-feather="plus" height="1rem" width="1rem"></i>
                <span class="hidden sm:inline-block">Criar</span>
            </a>
        </div>

        {{-- Tabela --}}
        <div class="table-responsive whitespace-nowrap rounded-primary">
            <table class="table">
                <thead>
                    <tr>
                        <th><input class="checkbox" type="checkbox" data-check-all data-check-all-target=".agreement-checkbox"/></th>
                        <th>NOME</th>
                        <th>DESCRIÇÃO</th>
                        <th>CONTATO</th>
                        <th>RUA</th>
                        <th>CIDADE</th>
                        <th>NÚMERO</th>
                        <th class="!text-right">AÇÃO</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agreements as $agreement)
                    <tr>
                        <td><input class="checkbox agreement-checkbox" type="checkbox" value="{{ $agreement->id }}" /></td>
                        <td>{{ $agreement->name }}</td>
                        <td title="{{ $agreement->description }}">{{ \Illuminate\Support\Str::limit($agreement->description, 50) }}</td>
                        <td>{{ $agreement->contact }}</td>
                        <td>{{ $agreement->road_name }}</td>
                        <td>{{ $agreement->city }}</td>
                        <td>{{ $agreement->number }}</td>
                        <td>
                            <div class="flex justify-end">
                                <div class="dropdown" data-placement="bottom-start">
                                    <div class="dropdown-toggle">
                                        <i class="w-6 text-slate-400" data-feather="more-horizontal"></i>
                                    </div>
                                    <div class="dropdown-content">
                                        <ul class="dropdown-list">
                                            <li class="dropdown-list-item">
                                                <a href="{{ route('agreements.edit', $agreement) }}" class="dropdown-link">
                                                    <i class="h-5 text-slate-400" data-feather="edit"></i> <span>Editar</span>
                                                </a>
                                            </li>
                                            <li class="dropdown-list-item">
                                                <a href="javascript:void(0)" class="dropdown-link" data-toggle="modal" data-target="#deleteModal-{{ $agreement->id }}">
                                                    <i class="h-5 text-slate-400" data-feather="trash"></i> <span>Excluir</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de exclusão -->
                            <div class="modal modal-centered" id="deleteModal-{{ $agreement->id }}">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-sm text-slate-500">
                                                Deseja excluir <strong>{{ $agreement->name }}</strong>?
                                            </p>
                                        </div>
                                        <div class="modal-footer flex justify-center">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <form method="POST" action="{{ route('agreements.destroy', $agreement) }}">
                                                @csrf @method('DELETE')
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
                        <td colspan="8" class="text-center text-slate-500">Nenhum convênio encontrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
            <p class="text-xs font-normal text-slate-400">
                Mostrando {{ $agreements->firstItem() ?? 0 }} a {{ $agreements->lastItem() ?? 0 }} de {{ $agreements->total() }} convênios
            </p>
            {{ $agreements->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</x-app-layout>
