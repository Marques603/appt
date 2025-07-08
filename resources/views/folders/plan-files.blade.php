<x-app-layout>
    <x-page-title 
    page="Arquivos do Plano"
    header="{!!
        'Lista de pastas' .
        ($folder ? ' > ' . collect(explode('/', $folder->fullPath()))
            ->map(fn($s) => ucfirst(trim($s)))
            ->implode(' > ')
        : '') .
        ' > ' . ucfirst($plan->name)
    !!}"
/>

    @section('title', 'Lista de arquivos / ' . $plan->name . ' | Inusittá')

    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
            <div class="flex w-full md:w-auto">
                <form method="GET" action="{{ route('folders.planFiles', [$folder->id, $plan->id]) }}" 
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
                        placeholder="Buscar por código ou descrição..."
                    />
                </form>
            </div>

            <div class="flex w-full items-center justify-end gap-x-4 md:w-auto">
                <div class="dropdown" data-placement="bottom-end">
                    <div class="dropdown-toggle">
                        <button type="button" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                            <i class="w-4 h-4" data-feather="filter"></i>
                            <span class="hidden sm:inline-block">Filtros</span>
                            <i class="w-4 h-4" data-feather="chevron-down"></i>
                        </button>
                    </div>
                    <div class="dropdown-content w-72 !overflow-visible">
                        <form method="GET" action="{{ route('folders.planFiles', [$folder->id, $plan->id]) }}">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <ul class="dropdown-list space-y-4 p-4">
                                <li class="dropdown-list-item">
                                    <h2 class="my-1 text-sm font-medium">Tipo de arquivo</h2>
                                    <select name="type" class="tom-select w-full">
                                        <option value="">Todos</option>
                                        <option value="pdf" {{ request('type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                        <option value="doc" {{ request('type') == 'doc' ? 'selected' : '' }}>Word</option>
                                        <option value="xls" {{ request('type') == 'xls' ? 'selected' : '' }}>Excel</option>
                                    </select>
                                </li>
                                <li class="dropdown-list-item pt-2 border-t">
                                    <button type="submit" class="btn btn-primary w-full">Aplicar</button>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>

                <a href="{{ route('archives.create', ['folder' => $folder->id, 'plan' => $plan->id]) }}"
                    class="btn btn-primary flex items-center gap-2">
                    <i class="ti ti-plus w-4 h-4"></i>
                    <span class="hidden sm:inline-block">Adicionar</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-6">
            @forelse ($archives as $archive)
                <div class="relative group card p-4 hover:shadow-lg transition">
                    <div class="absolute top-4 right-4">
                        <div class="dropdown" data-placement="bottom-end">
                            <div class="dropdown-toggle cursor-pointer">
                                <i class="w-6 text-slate-400" data-feather="more-horizontal"></i>
                            </div>
                            <div class="dropdown-content">
                                <ul class="dropdown-list">
                                    <li class="dropdown-list-item">
                                        <a href="javascript:void(0)" class="dropdown-link" data-toggle="modal" data-target="#deleteModal-{{ $archive->id }}">
                                            <i class="h-5 text-slate-400" data-feather="trash"></i>
                                            <span>Excluir</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('archives.logAndShow', $archive->id) }}" target="_blank" class="block">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full 
                                bg-primary-500 bg-opacity-20 text-primary-500">
                                @switch($archive->type)
                                    @case('pdf')
                                        <i class="ti ti-file-type-pdf text-3xl"></i>
                                        @break
                                    @case('doc')
                                        <i class="ti ti-file-type-doc text-3xl"></i>
                                        @break
                                    @case('xls')
                                        <i class="ti ti-file-type-xls text-3xl"></i>
                                        @break
                                    @default
                                        <i class="ti ti-file-text text-3xl"></i>
                                @endswitch
                            </div>
                            <div class="flex flex-1 flex-col gap-1">
                                <h4>{{ $archive->code }}</h4>
                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                    <p class="text-xs tracking-wide text-slate-500">
                                        {{ Str::limit($archive->description, 40) }}
                                    </p>
                                    <span class="flex items-center text-xs font-medium {{ ($archive->status ?? false) ? 'text-success-500' : 'text-danger-500' }}">
                                        <i class="ti {{ ($archive->status ?? false) ? 'ti-circle-check-filled' : 'ti-alert-triangle' }} mr-1"></i>
                                        {{ ($archive->status ?? false) ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>

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
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-500 dark:text-slate-400">Nenhum arquivo encontrado para este plano.</p>
                </div>
            @endforelse
        </div>

        @if ($archives->hasPages())
            <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row mt-8">
                <p class="text-xs font-normal text-slate-400">
                    Mostrando {{ $archives->firstItem() }} a {{ $archives->lastItem() }} de {{ $archives->total() }} resultados
                </p>
                {{ $archives->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</x-app-layout>
