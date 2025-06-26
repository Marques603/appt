<x-app-layout>
    <x-page-title page="Documentos" header="Lista de Documentos" />

    @section('title', 'Lista de documentos | Inusittá')
    
    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
            <div class="flex w-full flex-col items-stretch gap-2 sm:flex-row sm:items-center sm:justify-start md:w-auto md:flex-1">
                <form method="GET" action="{{ route('documents.index') }}" class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:bg-slate-800 sm:max-w-xs">
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
            </div>

            <div class="flex w-full items-center justify-between gap-x-4 md:w-auto">
                <div class="flex items-center gap-x-4">
    <!-- Botão de Arquivos Padrão -->                    
<div class="dropdown" data-placement="bottom-end">
    <div class="dropdown-toggle">
        <button type="button" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
            <i class="w-4" data-feather="download"></i>
            <span class="hidden sm:inline-block">Modelo Padrão</span>
            <i class="w-4" data-feather="chevron-down"></i>
        </button>
    </div>
    <div class="dropdown-content w-72 !overflow-visible">
        <ul class="dropdown-list space-y-4 p-4">
            <li class="dropdown-list-item">
                <h2 class="my-1 text-sm font-medium">Modelo Padrão</h2>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    <li>
                        <a href="{{ asset('downloads/MODELO PADRÃO DE PROCEDIMENTO.docx') }}" class="text-blue-600 hover:underline" download>
                            Modelo padrão de procedimento
                        </a>
                    </li>
                    <li>
                        <a href="{{ asset('downloads/MODELO PADRÃO DE INSTRUÇÃO DE TRABALHO.xlsx') }}" class="text-blue-600 hover:underline" download>
                            Modelo padrão de instrução de trabalho
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>

                    
                    <!-- Filtros e Exportar -->
                    <div class="dropdown" data-placement="bottom-end">
                    <div class="dropdown-toggle">
                        <button type="button" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                            <i class="w-4" data-feather="filter"></i>
                            <span class="hidden sm:inline-block">Filtros</span>
                            <i class="w-4" data-feather="chevron-down"></i>
                        </button>
                    </div>
                    <div class="dropdown-content w-72 !overflow-visible">
                        <form method="GET" action="{{ route('documents.index') }}">
                            <ul class="dropdown-list space-y-4 p-4">
                                <li class="dropdown-list-item">
                                    <h2 class="my-1 text-sm font-medium">Setor</h2>
                                    <select name="sector" class="tom-select w-full" autocomplete="off">
                                        <option value="">Selecione um setor</option>
                                        @foreach($sectors as $sector)
                                            <option value="{{ $sector->id }}" {{ request('sector') == $sector->id ? 'selected' : '' }}>
                                                {{ $sector->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </li>
                                <li class="dropdown-list-item">
                                    <h2 class="my-1 text-sm font-medium">Status</h2>
                                    <select name="status" class="tom-select w-full" autocomplete="off">
                                        <option value="">Selecione um status</option>
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

                    <button class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                        <i class="h-4" data-feather="upload"></i>
                        <span class="hidden sm:inline-block">Exportar</span>
                    </button>
                </div>
                
                <a href="{{ route('documents.create') }}" class="btn btn-primary flex items-center gap-2">
                    <i data-feather="plus" class="w-4 h-4"></i>
                    <span class="hidden sm:inline-block">Criar</span>
                </a>
             
             
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
                        <th class="w-[30%] uppercase">Descriçao</th>
                        <th class="w-[5%] uppercase">Revisão</th>
                        <th class="w-[5%] uppercase">Macros</th>
                        <th class="w-[5%] uppercase">Setores</th>
                        <th class="w-[5%] uppercase">Status</th>
                        <th class="w-[5%] !text-right uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                        <tr>
                            <td>
                                <input class="checkbox document-checkbox" type="checkbox" />
                            </td>
                            <td>{{ $document->code }}</td>
                            <td>{{ $document->description }}</td>
                            <td>{{ $document->revision ?? '-' }}</td>
                            {{-- Macros --}}
                    <td>
                        @foreach($document->macros as $macro)
                            <span class="badge badge-soft-info">{{ $macro->name }}</span>
                        @endforeach
                    </td>

                        @php
                            $documentSectors = $document->sectors;
                            $totalSectors = \App\Models\Sector::count();
                            $sectorNames = $documentSectors->pluck('name')->toArray();

                            $tooltipContent = implode(', ', $sectorNames);
                        @endphp

                        <td>
                            @if($documentSectors->isEmpty())
                                <span class="badge badge-soft-danger">Nenhum setor</span>
                            @elseif($documentSectors->count() === 1)
                                <span class="badge badge-soft-secondary">
                                    {{ $documentSectors->first()->name }}
                                </span>
                            @else
                                <button
                                    type="button"
                                    class="badge badge-soft-secondary"
                                    data-tooltip="tippy"
                                    data-tippy-content="{{ $tooltipContent }}"
                                >
                                    {{ $documentSectors->count() === $totalSectors ? 'Todos os setores' : $documentSectors->count() . ' setores' }}
                                </button>
                            @endif
                        </td>
                            <td>
                                @if($document->status)
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
                                                    <a href="{{ route('documents.logAndShow', $document->id) }}" target="_blank" class="dropdown-link">
                                                <i class="h-5 text-slate-400" data-feather="eye"></i>
                                                <span>Ver</span>
                                            </a>

                                                </li>
                                                @can('view', App\Models\Document::class)
                                                <li class="dropdown-list-item">
                                                    <a href="{{ route('documents.edit', $document->id) }}" class="dropdown-link">
                                                        <i class="h-5 text-slate-400" data-feather="edit"></i>
                                                        <span>Editar</span>
                                                    </a>
                                                </li>
                                                <li class="dropdown-list-item">
                                                    <a href="javascript:void(0)" class="dropdown-link" data-toggle="modal" data-target="#deleteModal-{{ $document->id }}">
                                                        <i class="h-5 text-slate-400" data-feather="trash"></i>
                                                        <span>Excluir</span>
                                                    </a>
                                                </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal modal-centered" id="deleteModal-{{ $document->id }}">
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
                                                    Tem certeza que deseja excluir <strong>{{ $document->code }}</strong>?
                                                </p>
                                            </div>
                                            <div class="modal-footer flex justify-center">
                                                <form method="POST" action="{{ route('documents.destroy', $document->id) }}">
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
                            <td colspan="8" class="text-center ">Nenhum documento encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
            <p class="text-xs font-normal text-slate-400">
                Mostrando {{ $documents->firstItem() }} a {{ $documents->lastItem() }} de {{ $documents->total() }} resultados
            </p>
            {{ $documents->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</x-app-layout>
