<x-app-layout>
    
@section('title', 'Lista de pastas | Inusittá')

<x-page-title 
    page="Pastas" 
    header="{!! 
        'Lista de pastas' 
        . ($parentFolder 
            ? ' > ' . collect(explode('/', $parentFolder->fullPath()))
                ->map(fn($s) => ucfirst(trim($s)))
                ->implode(' > ')
            : ''
        )
    !!}"
/>
    <div class="space-y-4">
        {{-- Barra de ações --}}
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
            {{-- Busca --}}
            <div class="flex w-full md:w-auto">
                <form method="GET" action="" class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:bg-slate-800 sm:max-w-xs">
                    <div class="flex h-full items-center px-2">
                        <i class="h-4 text-slate-400 group-focus-within:text-primary-500" data-feather="search"></i>
                    </div>
                    <input
                        name="search"
                        class="h-full w-full border-transparent bg-transparent px-0 text-sm placeholder-slate-400 focus:border-transparent focus:outline-none focus:ring-0"
                        type="text"
                        value="{{ request('search') }}"
                        placeholder="Buscar plano..."
                    />
                </form>
            </div>

            {{-- Criar pasta --}}
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
                <a href="{{ route('folders.create', ['parent_id' => $parentFolder->id]) }}" class="btn btn-primary flex items-center gap-2">
                    <i data-feather="plus" class="w-4 h-4"></i>
                    <span class="hidden sm:inline-block">Criar</span>
                </a>
            </div>
        </div>

        {{-- Grid dos planos (estilo pasta) --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($plans as $plan)
                <div class="relative group card p-4 hover:shadow-lg transition">
                    <a href="{{ route('folders.planFiles', [$parentFolder->id, $plan->id]) }}" class="block">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full 
                                        bg-primary-500 bg-opacity-20 text-primary-500">
                                <i class="ti ti-folder text-3xl"></i>
                            </div>
                            <div class="flex flex-1 flex-col gap-1">
                                <h4>{{ $plan->name }}</h4>
                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                    <p class="text-xs tracking-wide text-slate-500 flex items-center gap-1">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                    Ver arquivos da pasta
                                </p>
                                    <span class="flex items-center text-xs font-medium text-success-500">
                                        <i class="ti ti-circle-check-filled mr-1"></i> {{ $plan->status ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-500 dark:text-slate-400">Nenhum plano vinculado a esta pasta.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
