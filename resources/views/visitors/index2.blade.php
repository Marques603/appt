<x-app-layout>
    <x-page-title page="Tabela de Visitantes" header="Tabela de Visitantes" />
    @section('title', 'Tabela de Visitantes | Inusittá')

    <div class="space-y-4">
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
            <!-- Busca -->
            <form method="GET" action="{{ route('visitors.index2') }}"
                  class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:border-transparent dark:bg-slate-800 md:w-72">
                <div class="flex h-full items-center px-2">
                    <i class="h-4 text-slate-400 group-focus-within:text-primary-500" data-feather="search"></i>
                </div>
                <input
                    name="search"
                    class="h-full w-full border-transparent bg-transparent px-0 text-sm placeholder-slate-400 focus:border-transparent focus:outline-none focus:ring-0"
                    type="text"
                    value="{{ request()->input('search') }}"
                    placeholder="Buscar..."
                />
            </form>

            <!-- Filtros e botões -->
            <div class="flex w-full flex-col gap-4 md:w-auto md:flex-row md:items-center md:justify-end">
                <div class="flex items-center gap-x-4">
                    <!-- Dropdown filtro tipo visitante -->
                    <form method="GET" action="{{ route('visitors.index2') }}">
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
                                    <h2 class="my-1 text-sm font-medium">Tipo de Visitante</h2>
                                    <select class="tom-select w-full" name="typevisitor" autocomplete="off">
                                        <option value="">Selecione o tipo</option>
                                        @foreach([
                                            'CANDIDATO', 'CLIENTE', 'COLETA DE RESÍDUOS',
                                            'COLETA/RETIRA DE MATERIAIS', 'FORNECEDOR',
                                            'LOJISTA', 'OUTROS', 'PRESTADOR DE SERVIÇOS', 'REPRESENTANTE'
                                        ] as $type)
                                            <option value="{{ $type }}" @selected(request('typevisitor') == $type)>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </li>

                                    <li class="dropdown-list-item pt-2">
                                        <button type="submit" class="btn btn-primary w-full">
                                            <i data-feather="filter" class="w-4 h-4"></i>
                                            <span>Aplicar Filtro</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>

                    <!-- Export CSV -->
                    <a href="#" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                        <i class="h-4" data-feather="upload"></i>
                        <span class="hidden sm:inline-block">Exportar CSV</span>
                    </a>

                    <!-- Export PDF -->
                    <a href="#" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                        <i class="h-4" data-feather="upload"></i>
                        <span class="hidden sm:inline-block">Exportar PDF</span>
                    </a>
                </div>

                <!-- Botão voltar -->
                <a href="{{ route('visitors.index') }}" class="btn btn-primary" title="Voltar para veículos">
                    <i data-feather="arrow-left" height="1rem" width="1rem"></i>
                    <span class="hidden sm:inline-block">Visitantes</span>
                </a>
            </div>
        </div>

        {{-- Tabela --}}
        <div class="overflow-x-auto whitespace-nowrap rounded-primary">
            <table class="table">
                <thead>
                    <tr>
                        <th><input class="checkbox" type="checkbox" data-check-all data-check-all-target="checkbox"/></th>
                        <th class="w-[30%] uppercase">Nome</th>
                        <th class="w-[20%] uppercase">Documento</th>
                        <th class="w-[15%] uppercase">Tipo</th>
                        <th class="w-[15%] uppercase">Empresa</th>
                        <th class="w-[15%] uppercase">Motivo</th>
                        <th class="w-[15%] uppercase">Estacionamento</th>
                        <th class="w-[15%] uppercase">Veículo</th>
                        <th class="w-[15%] uppercase">Modelo</th>
                        <th class="w-[5%] uppercase">Entrada</th>
                        <th class="w-[5%] uppercase">Saída</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visitors as $visitor)
                        <tr>
                            <td><input class="checkbox" type="checkbox" /></td>
                            <td>{{ $visitor->name }}</td>
                            <td>{{ '***.***.' . substr($visitor->document, 8) }}</td>
                            <td>{{ ucfirst(mb_strtolower($visitor->typevisitor, 'UTF-8')) }}</td>
                            <td>{{ $visitor->company ?? '-' }}</td>
                            <td>{{ $visitor->service ?? '-' }}</td>
                            <td>{{ $visitor->parking ?? '-' }}</td>
                            <td>{{ $visitor->vehicle_plate ?? '-' }}</td>
                            <td>{{ $visitor->vehicle_model ?? '-' }}</td>
                            <td>{{ $visitor->created_at ? $visitor->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $visitor->updated_at ? $visitor->updated_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-slate-400 py-4">Nenhum visitante encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        @if($visitors->total() > 0)
            <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
                <p class="text-xs text-slate-400">
                    Mostrando {{ $visitors->firstItem() }} a {{ $visitors->lastItem() }} de {{ $visitors->total() }} resultados
                </p>
                {{ $visitors->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

    
</x-app-layout>
