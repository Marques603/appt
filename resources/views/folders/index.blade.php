<x-app-layout>
      
    @push('title')
        Pastas{{ $parentFolder ? ' | ' . $parentFolder->fullPath() : '' }} | Inusittá
    @endpush

    <x-page-title 
    page="Pastas" 
    header="{!! 'Lista de Pastas' . ($parentFolder 
        ? ' > ' . collect(explode('/', $parentFolder->fullPath()))
            ->map(fn($s) => trim($s))
            ->implode(' > ')
        : ''
    ) !!}"
/>



    @section('title', 'Lista de pastas | Inusittá')

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
                <form method="GET" action="{{ route('folders.index') }}" 
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
                        placeholder="Buscar por nome..."
                    />
                </form>
            </div>

            {{-- Botões --}}
            <div class="flex w-full items-center justify-end gap-x-4 md:w-auto">
                {{-- Filtros --}}
                <div class="dropdown" data-placement="bottom-end">
                    <div class="dropdown-toggle">
                        <button type="button" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                            <i class="w-4 h-4" data-feather="filter"></i>
                            <span class="hidden sm:inline-block">Filtros</span>
                            <i class="w-4 h-4" data-feather="chevron-down"></i>
                        </button>
                    </div>
                    <div class="dropdown-content w-72 !overflow-visible">
                        <form method="GET" action="{{ route('folders.index') }}">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <ul class="dropdown-list space-y-4 p-4">
                                <li class="dropdown-list-item">
                                    <h2 class="my-1 text-sm font-medium">Status</h2>
                                    <select name="status" class="tom-select w-full">
                                        <option value="">Todos</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativo</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativo</option>
                                    </select>
                                </li>
                                <li class="dropdown-list-item pt-2 border-t">
                                    <button type="submit" class="btn btn-primary w-full">Aplicar</button>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>

                {{-- Criar pasta --}}
                <a href="{{ route('folders.create', ['parent_id' => $parentFolder?->id]) }}" 
                    class="btn btn-primary flex items-center gap-2">
                    <i data-feather="plus" class="w-4 h-4"></i>
                    <span class="hidden sm:inline-block">Criar</span>
                </a>
            </div>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-6">
            @forelse($folders as $folder)
                <div class="relative group card p-4 hover:shadow-lg transition">
                    {{-- Dropdown de ações dentro do card --}}
                    <div class="absolute top-4 right-4">
                        <div class="dropdown" data-placement="bottom-end">
                            <div class="dropdown-toggle cursor-pointer">
                                <i class="w-6 text-slate-400" data-feather="more-horizontal"></i>
                            </div>
                            <div class="dropdown-content">
                                <ul class="dropdown-list">
                                    <li class="dropdown-list-item">
                                        <a href="{{ route('folders.edit', $folder->id) }}" class="dropdown-link">
                                            <i class="h-5 text-slate-400" data-feather="edit"></i>
                                            <span>Editar</span>
                                        </a>
                                    </li>
                                    <li class="dropdown-list-item">
                                        <a href="javascript:void(0)" class="dropdown-link" data-toggle="modal" data-target="#deleteModal-{{ $folder->id }}">
                                            <i class="h-5 text-slate-400" data-feather="trash"></i>
                                            <span>Excluir</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Conteúdo do card --}}
                    <a href="{{ route('folders.index', ['parent_id' => $folder->id]) }}" class="block">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full 
                                bg-primary-500 bg-opacity-20 text-primary-500">
                                <i class="ti ti-folder text-3xl"></i>
                            </div>
                            <div class="flex flex-1 flex-col gap-1">
                                <h4>{{ $folder->name }}</h4>

                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                    <p class="text-xs tracking-wide text-slate-500 flex items-center gap-1">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                    Ver arquivos da pasta
                                </p>
                                    <span class="flex items-center text-xs font-medium {{ $folder->status ? 'text-success-500' : 'text-danger-500' }}">
                                        <i class="ti {{ $folder->status ? 'ti-circle-check-filled' : 'ti-alert-triangle' }} mr-1"></i>
                                        {{ $folder->status ? 'Ativa' : 'Inativa' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Modal de exclusão --}}
                    <div class="modal modal-centered" id="deleteModal-{{ $folder->id }}">
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
                                        Tem certeza que deseja excluir <strong>{{ $folder->name }}</strong>?
                                    </p>
                                </div>
                                <div class="modal-footer flex justify-center">
                                    <form method="POST" action="{{ route('folders.destroy', $folder->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Sim, excluir</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">                  
                </div>
            @endforelse
        </div>

        {{-- Paginação --}}
        @if ($folders->hasPages())
    <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row mt-8">
        <p class="text-xs font-normal text-slate-400">
            Mostrando {{ $folders->firstItem() }} a {{ $folders->lastItem() }} de {{ $folders->total() }} resultados
        </p>
        {{ $folders->appends(request()->query())->links('vendor.pagination.custom') }}
    </div>
    @endif

    </div>
</x-app-layout>
