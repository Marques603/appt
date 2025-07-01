<x-app-layout>
    <x-page-title page="Pastas" header="Lista de Pastas" />
    @section('title', 'Pastas | Inusittá')

    {{-- Notificação de sucesso (Toast) --}}
    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        {{-- Barra de Ações: Busca, Filtros e Botão de Criar --}}
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
            
            {{-- Formulário de Busca --}}
            <div class="flex w-full md:w-auto">
                <form method="GET" action="{{ route('folder.index') }}" class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:bg-slate-800 sm:max-w-xs">
                    <div class="flex h-full items-center px-2">
                        <i class="h-4 text-slate-400 group-focus-within:text-primary-500" data-feather="search"></i>
                    </div>
                    <input
                        name="search"
                        class="h-full w-full border-transparent bg-transparent px-0 text-sm placeholder-slate-400 placeholder:text-sm focus:border-transparent focus:outline-none focus:ring-0"
                        type="text"
                        value="{{ request('search') }}"
                        placeholder="Buscar por nome..."
                    />
                </form>
            </div>

            {{-- Botões de Ação --}}
            <div class="flex w-full items-center justify-end gap-x-4 md:w-auto">
                
                {{-- Dropdown de Filtros --}}
                <div class="dropdown" data-placement="bottom-end">
                    <div class="dropdown-toggle">
                        <button type="button" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                            <i class="w-4 h-4" data-feather="filter"></i>
                            <span class="hidden sm:inline-block">Filtros</span>
                            <i class="w-4 h-4" data-feather="chevron-down"></i>
                        </button>
                    </div>
                    <div class="dropdown-content w-72 !overflow-visible">
                        <form method="GET" action="{{ route('folder.index') }}">
                            {{-- Mantém o parâmetro de busca ao aplicar o filtro --}}
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <ul class="dropdown-list space-y-4 p-4">
                                <li class="dropdown-list-item">
                                    <h2 class="my-1 text-sm font-medium">Status</h2>
                                    <select name="status" class="tom-select w-full" autocomplete="off">
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

                {{-- Botão Criar Pasta --}}
                <a href="{{ route('folder.create') }}" class="btn btn-primary flex items-center gap-2">
                    <i data-feather="plus" class="w-4 h-4"></i>
                    <span class="hidden sm:inline-block">Criar Pasta</span>
                </a>
            </div>
        </div>

        {{-- Grid de Pastas --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 mt-6">
            @forelse($folders as $folder)
                <a href="{{ route('folder.show', $folder->id) }}"  class="flex flex-col items-center p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow hover:shadow-lg transition group">

                    <i data-feather="folder" class="w-12 h-12 text-yellow-400 mb-2 group-hover:scale-110 transition"></i>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-200 text-center">
                        {{ $folder->name }}
                    </span>
                    @if (isset($folder->archives_count))
                        <span class="text-xs mt-1 text-slate-400 dark:text-slate-500">
                            {{ $folder->archives_count }} {{ Str::plural('arquivo', $folder->archives_count) }}
                        </span>
                    @endif
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-500 dark:text-slate-400">Nenhuma pasta encontrada.</p>
                </div>
            @endforelse
        </div>

        {{-- Paginação --}}
        @if ($folders->hasPages())
            <div class="mt-8">
                {{ $folders->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</x-app-layout>