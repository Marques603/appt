<x-app-layout>
    <x-page-title page="Tabela de Visitantes" header="Tabela de Visitantes" />

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
            <form method="GET" action="{{ route('visitors.index2') }}" class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:bg-slate-800 md:w-72">
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
            </div>
    
        {{-- Tabela --}}
       <div class="overflow-x-auto whitespace-nowrap rounded-primary">
    <table class="table">
        <thead>
            <th><input class="checkbox" type="checkbox" data-check-all data-check-all-target="checkbox"/></th>
                        
                        <th class="w-[5%] uppercase">ID</th>
                        <th class="w-[30%] uppercase">Nome</th>
                        <th class="w-[20%] uppercase">Status</th>
                        <th class="w-[15%] uppercase">Registrar Saída</th>
                        <th class="w-[15%] uppercase">Tipo</th>
                        <th class="w-[15%] uppercase">Empresa</th>
                        <th class="w-[15%] uppercase">Motivo</th>
                        <th class="w-[15%] uppercase">Estacionamento</th>
                        <th class="w-[15%] uppercase">Veículo</th>
                        <th class="w-[15%] uppercase">Placa</th>
                        
                    </tr>
        </thead>
        <tbody>
            @forelse($visitors as $visitor)
                <tr>
                    <td><input class="checkbox" type="checkbox" /></td>
                    <td>{{ $visitor->id }}</td>
                    <td>{{ $visitor->name }}</td>
                    <td>{{ $visitor->status }}</td>
                    <td>
                        @if($visitor->status === 'active')
                            <a href="{{ route('visitors.exit', $visitor) }}" class="btn btn-primary btn-sm">Registrar Saída</a>
                        @else
                            <span class="text-red-500">Saída Registrada</span>
                        @endif
                    </td>
                    <td>{{ $visitor->typevisitor }}</td>
                    <td>{{ $visitor->company ?? '-' }}</td>
                    <td>{{ $visitor->service ?? '-' }}</td>
                    <td>{{ $visitor->parking ?? '-' }}</td>
                    <td>{{ $visitor->vehicle_model ?? '-' }}</td>
                    <td>
                        <p class="truncate text-xs text-black badge bg-white border border-black">
                            {{ $visitor->vehicle_plate ?? '-' }}
                        </p>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center text-slate-400 py-4">Nenhum visitante encontrado.</td>
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
