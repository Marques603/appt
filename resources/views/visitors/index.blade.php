<x-app-layout>
    <x-page-title page="Controle de Visitantes" header="Controle de Visitantes" />
    @section('title', 'Controle de Visitantes | Inusittá')

    {{-- Toast de sucesso --}}
    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast');
                if (toast) toast.remove();
            }, 3000);
        </script>
    @endif

    {{-- Toast de erro --}}
    @if(session('error'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-danger-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.querySelector('.fixed.top-0.right-0');
                if (toast) toast.remove();
            }, 8000);
        </script>
    @endif

    <div class="space-y-4">
        <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
            <form method="GET" action="{{ route('visitors.index') }}" class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:bg-slate-800 md:w-72">
                <div class="flex items-center px-2">
                    <i class="h-4 text-slate-400" data-feather="search"></i>
                </div>
                <input
                    name="search"
                    type="text"
                    value="{{ request('search') }}"
                    class="w-full border-transparent bg-transparent px-0 text-sm placeholder-slate-400 focus:ring-0"
                    placeholder="Buscar..."
                />
            </form>
             <a href="{{ route('visitors.index2') }}" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                <i class="h-4" data-feather="search"></i>
                <span class="hidden sm:inline-block">Historico Veículos</span>
            </a>

            <a class="btn btn-primary" href="{{ route('visitors.create') }}">
                <i data-feather="user-plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline-block">Entrada de Visitante</span>
            </a>
        </div>

        {{-- Tabela --}}
        <div class="table-responsive whitespace-nowrap rounded-primary">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-[5%]"><input class="checkbox" type="checkbox" /></th>
                        <th class="w-[30%] uppercase">Nome</th>
                        <th class="w-[20%] uppercase">Status</th>
                        <th class="w-[15%] uppercase">Tipo</th>
                        <th class="w-[15%] uppercase">Empresa</th>
                        <th class="w-[15%] uppercase">Motivo</th>
                        <th class="w-[15%] uppercase">Estacionamento</th>
                        <th class="w-[15%] uppercase">Veículo</th>
                        <th class="w-[15%] uppercase">Placa</th>
                        <th class="w-[15%] uppercase">Registrar Saída</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visitors as $visitor)
                        <tr>
                            <td><input class="checkbox" type="checkbox" /></td>
                            
                            <td>{{ $visitor->name }}</td>
                            <td>
                                @if($visitor->status === 1)
                                <span class="badge badge-danger">Saiu {{ $visitor->update_at ? $visitor->updated_at->format('H:i') : '-' }}</div>
                                                                @else
                                <span class="badge badge-success">Entrou {{ $visitor->created_at ? $visitor->created_at->format('H:i') : '-' }}</span>
                                @endif
                            </td>
                            <td>{{ $visitor->typevisitor }}</td>
                            <td>{{ $visitor->company ?? '-' }}</td>
                            <td>{{ $visitor->service ?? '-' }}</td>
                            <td>{{ $visitor->parking ?? '-' }}</td>
                            <td>{{ $visitor->vehicle_model ?? '-' }}</td>
                            <td>{{ $visitor->vehicle_plate ?? '-' }}</td>
                            <td class="text-right">
                           <form action="{{ route('visitors.updatesaidastatus', $visitor->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-warning">
                                <i data-feather="corner-up-left" class="h-4 w-4"></i>
                                <span>Registrar</span>
                            </button>
                        </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-slate-400 py-4">Nenhum visitante encontrado.</td>
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

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                feather.replace();
            });
        </script>
    @endsection
</x-app-layout>
