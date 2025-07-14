<x-app-layout>
    <x-page-title page="Controle de Visitantes" header="Controle de Visitantes" />
    @section('title', 'Controle de Visitantes | Inusittá')

    {{-- Toasts --}}
    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        <script>
            setTimeout(() => document.getElementById('toast')?.remove(), 3000);
        </script>
    @endif
    @if(session('error'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-danger-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        <script>
            setTimeout(() => document.querySelector('.fixed.top-0.right-0')?.remove(), 8000);
        </script>
    @endif

    <div class="space-y-4">
        <!-- Header igual USERS -->
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
            <!-- Busca -->
            <form method="GET" action="{{ route('visitors.index') }}"
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

            <!-- Botões -->
            <div class="flex w-full items-center justify-between gap-x-4 md:w-auto">
                <div class="flex items-center gap-x-4">
                    <a href="{{ route('visitors.index2') }}" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                        <i class="h-4" data-feather="search"></i>
                        <span class="hidden sm:inline-block">Histórico Visitantes</span>
                    </a>

                    <a class="btn btn-danger hidden" id="edit-visitor-button" href="#">
                        <i data-feather="edit" class="w-4 h-4"></i>
                        <span class="hidden sm:inline-block">Editar</span>
                    </a>
                </div>

                <a class="btn btn-primary" href="{{ route('visitors.create') }}">
                    <i data-feather="plus" height="1rem" width="1rem"></i>
                    <span class="hidden sm:inline-block">Criar</span>
                </a>
            </div>
        </div>

        <!-- Tabela -->
        <div class="table-responsive whitespace-nowrap rounded-primary">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-[5%]"><input class="checkbox select-all" type="checkbox" /></th>
                        <th class="w-[20%] uppercase">Nome</th>
                        <th class="w-[10%] uppercase">Documento</th>
                        <th class="w-[10%] uppercase">Tipo</th>
                        <th class="w-[15%] uppercase">Empresa</th>
                        <th class="w-[15%] uppercase">Motivo</th>
                        <th class="w-[10%] uppercase">Estacionamento</th>
                        <th class="w-[10%] uppercase">Veículo</th>
                        <th class="w-[10%] uppercase">Placa</th>
                        <th class="w-[15%] uppercase">Entrada</th>
                        <th class="w-[5%] uppercase">Saída</th>
                        <th class="w-[5%] uppercase text-right">Registrar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visitors as $visitor)
                        <tr>
                            <td><input class="checkbox" type="checkbox" /></td>
                            <td>{{ $visitor->name }}</td>
                            <td>{{ $visitor->document ?? '-' }}</td>
                            <td>{{ $visitor->typevisitor }}</td>
                            <td>{{ $visitor->company ?? '-' }}</td>
                            <td>{{ $visitor->service ?? '-' }}</td>
                            <td>{{ $visitor->parking ?? '-' }}</td>
                            <td>{{ $visitor->vehicle_model ?? '-' }}</td>
                            <td>{{ $visitor->vehicle_plate ?? '-' }}</td>
                            <td>{{ $visitor->created_at ? $visitor->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $visitor->updated_at ? $visitor->updated_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="text-right">
                                <form action="{{ route('visitors.updatesaidastatus', $visitor->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="badge badge-danger flex items-center gap-1">
                                        
                                        <span>Saída</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-slate-400 py-4">Nenhum visitante encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        @if($visitors->total() > 0)
            <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
                <p class="text-xs text-slate-400">
                    Mostrando {{ $visitors->firstItem() }} a {{ $visitors->lastItem() }} de {{ $visitors->total() }} resultados
                </p>
                {{ $visitors->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                feather.replace();
                const selectAll = document.querySelector('.select-all');
                const checkboxes = document.querySelectorAll('tbody .checkbox');
                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        checkboxes.forEach(cb => cb.checked = selectAll.checked);
                    });
                }
            });
        </script>
    @endsection
</x-app-layout>
