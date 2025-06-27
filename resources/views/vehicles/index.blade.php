<!-- index.blade.php -->
<x-app-layout>
    <x-page-title page="Veículos" header="Lista de Veículos" />
    @section('title', 'Veículos | Inusittá')

    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <form method="GET" action="{{ route('vehicles.index') }}" class="group flex w-full max-w-sm items-center rounded-primary bg-white shadow-sm dark:bg-slate-800">
                <div class="flex items-center px-2">
                    <i data-feather="search" class="h-4 text-slate-400"></i>
                </div>
                <input name="search" class="h-10 w-full bg-transparent px-2 text-sm focus:outline-none" placeholder="Buscar por placa ou modelo" />
            </form>
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
                <i data-feather="plus" class="w-4 h-4"></i> <span class="hidden sm:inline">Cadastrar Veículo</span>
            </a>
        </div>

        <div class="table-responsive whitespace-nowrap rounded-primary">
            <table class="table">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Status</th>
                        <th>Km Atual</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->plate }}</td>
                            <td>{{ $vehicle->brand }}</td>
                            <td>{{ $vehicle->model }}</td>
                            <td>
                                <span class="badge {{ $vehicle->status === 1 ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                    {{ $vehicle->status_label }}
                                </span>
                            </td>
                            <td>{{ $vehicle->current_km }} km</td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <div class="dropdown-toggle">
                                        <i class="w-6 text-slate-400" data-feather="more-horizontal"></i>
                                    </div>
                                    <div class="dropdown-content">
                                        <ul class="dropdown-list">
                                            <li>
                                                <a href="{{ route('vehicles.edit', $vehicle) }}" class="dropdown-link">
                                                    <i data-feather="edit" class="h-4"></i> Editar
                                                </a>
                                            </li>
                                            @if($vehicle->status === 1)
                                            <li>
                                                <a href="{{ route('vehicles.movement.create', $vehicle) }}" class="dropdown-link">
                                                    <i data-feather="log-out" class="h-4"></i> Registrar Saída
                                                </a>
                                            </li>
                                            @else
                                            <li>
                                                <a href="{{ route('vehicles.movement.edit', $vehicle->movements()->latest()->first()) }}" class="dropdown-link">
                                                    <i data-feather="log-in" class="h-4"></i> Registrar Retorno
                                                </a>
                                            </li>
                                            @endif
                                            <li>
                                                <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Deseja excluir?')">
                                                    @csrf @method('DELETE')
                                                    <button class="dropdown-link text-red-500" type="submit">
                                                        <i data-feather="trash" class="h-4"></i> Excluir
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-500 py-4">Nenhum veículo cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex justify-between items-center mt-4">
            <p class="text-xs text-slate-400">
                Mostrando {{ $vehicles->firstItem() }} a {{ $vehicles->lastItem() }} de {{ $vehicles->total() }} veículos
            </p>
            {{ $vehicles->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</x-app-layout>
