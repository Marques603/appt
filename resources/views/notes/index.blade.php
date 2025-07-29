<x-app-layout>
  <x-page-title page="Controle de Notas" header="Controle de Notas Fiscais" />
  <script src="//unpkg.com/alpinejs" defer></script>

  {{-- Toasts --}}
  @if(session('success'))
    <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
      <p>{{ session('success') }}</p>
    </div>
  @endif
  @if(session('error'))
    <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-red-500 text-white rounded shadow-lg z-50" role="alert">
      <p>{{ session('error') }}</p>
    </div>
  @endif

  <script>
    setTimeout(() => {
      const toast = document.querySelector('.fixed.top-0.right-0');
      if (toast) toast.remove();
    }, 8000);
  </script>

  <div x-data="{ openModal: null }" class="space-y-4">

    {{-- Filtros e botão --}}
    <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
      <form method="GET" action="{{ route('notes.index') }}" class="group flex h-10 w-full md:w-72 items-center rounded-primary border bg-white shadow-sm focus-within:ring-1 focus-within:ring-primary-500 dark:bg-slate-800">
        <div class="px-2"><i class="h-4 text-slate-400" data-feather="search"></i></div>
        <input name="search" type="text" value="{{ request()->input('search') }}" class="w-full bg-transparent px-0 text-sm placeholder-slate-400 focus:ring-0" placeholder="Buscar por fornecedor, descrição..." />
      </form>

      <a class="btn btn-primary" href="{{ route('notes.create') }}">
        <i data-feather="file-plus" class="w-4 h-4"></i>
        <span class="hidden sm:inline-block">Nova Nota</span>
      </a>
    </div>

    {{-- Tabela --}}
    <div class="table-responsive whitespace-nowrap rounded-primary">
      <table class="table">
        <thead>
          <tr>
            <th class="w-[5%]"><input class="checkbox" type="checkbox" /></th>
            <th class="uppercase">Prestador</th>
            <th class="uppercase">Descrição</th>
            <th class="uppercase">Valor</th>
            <th class="uppercase">Vencimento</th>
            <th class="uppercase">Status</th>
            <th class="uppercase">Centro de Custo</th>
            <th class="text-right uppercase">Ação</th>
          </tr>
        </thead>
        <tbody>
          @php
            $positions = auth()->user()->positions->pluck('name')->toArray();
            $diretores = ['DIRETOR INDUSTRIAL', 'DIRETOR COMERCIAL E MKT', 'DIRETOR ADM. FINANCEIRO'];
            $temPermissao = collect($positions)->intersect($diretores)->isNotEmpty();
            $temaPermissao = collect($positions)->contains('Fiscal de notas');
            $statusClasses = [
              'Aguardando Aprovação' => 'badge badge-warning badge-rounded',
              'Aprovada pelo Diretor' => 'badge badge-info badge-rounded',
              'Reprovada' => 'badge badge-danger badge-rounded',
              'Lançada no Financeiro' => 'badge badge-primary badge-rounded',
              'Paga' => 'badge badge-success badge-rounded',
            ];
          @endphp

          @forelse($notes as $note)
            <tr>
              <td><input class="checkbox" type="checkbox" /></td>
              <td>{{ $note->provider }}</td>
              <td>{{ Str::limit($note->description, 40) }}</td>
              <td>R$ {{ number_format($note->valor, 2, ',', '.') }}</td>
              <td>{{ \Carbon\Carbon::parse($note->payday)->format('d/m/Y') }}</td>
              <td><div class="{{ $statusClasses[$note->status] ?? 'badge' }}">{{ $note->status }}</div></td>
              <td>{{ $note->costCenter->name ?? 'Não informado' }}</td>
              <td class="text-right">
                <div class="flex justify-end">
                  <div class="dropdown" data-placement="bottom-start">
                    <div class="dropdown-toggle cursor-pointer">
                      <i class="w-6 text-slate-400" data-feather="more-horizontal"></i>
                    </div>
                    <div class="dropdown-content">
                      <ul class="dropdown-list">
                        {{-- Visualizar --}}
                        <li class="dropdown-list-item">
                          <a href="#" class="dropdown-link" @click="openModal = 'viewNote-{{ $note->id }}'">
                            <i class="h-5 text-slate-400" data-feather="eye"></i>
                            <span>Visualizar</span>
                          </a>
                        </li>

                        {{-- Lançar Financeiro --}}
                        @if($temaPermissao && $note->status === 'Aprovada pelo Diretor')
                          <li class="dropdown-list-item">
                            <form method="POST" action="{{ route('notes.lancar', $note->id) }}" x-data @submit.prevent="if(confirm('Deseja lançar esta nota no financeiro?')) $el.submit()">
                              @csrf
                              <button type="submit" class="dropdown-link text-left w-full">
                                <i class="h-5 text-blue-500" data-feather="file-plus"></i>
                                <span>Lançar no Financeiro</span>
                              </button>
                            </form>
                          </li>
                        @endif

                        {{-- Aprovar Nota --}}
                        @if($temPermissao && $note->status === 'Aguardando Aprovação')
                          <li class="dropdown-list-item">
                            <form method="POST" action="{{ route('notes.aprovar', $note->id) }}" x-data @submit.prevent="if(confirm('Deseja aprovar esta nota?')) $el.submit()">
                              @csrf
                              <button type="submit" class="dropdown-link text-left w-full">
                                <i class="h-5 text-green-500" data-feather="check-circle"></i>
                                <span>Aprovar Nota</span>
                              </button>
                            </form>
                          </li>
                        @endif

                        {{-- Excluir --}}
                        <li class="dropdown-list-item">
                          <a href="#" class="dropdown-link text-red-600" @click="openModal = 'deleteNote-{{ $note->id }}'">
                            <i class="h-5 text-red-600" data-feather="trash-2"></i>
                            <span>Excluir</span>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-slate-400 py-4">Nenhuma nota encontrada.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Paginação --}}
    @if($notes->total() > 0)
      <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
        <p class="text-xs text-slate-400">
          Mostrando {{ $notes->firstItem() }} a {{ $notes->lastItem() }} de {{ $notes->total() }} resultados
        </p>
        {{ $notes->appends(request()->query())->links('vendor.pagination.custom') }}
      </div>
    @endif

    {{-- Modais --}}
    @foreach($notes as $note)
      {{-- Visualização --}}
      <div x-show="openModal === 'viewNote-{{ $note->id }}'" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
          <h3 class="text-lg font-bold mb-4">Nota #{{ $note->id }}</h3>
          <p><strong>Fornecedor:</strong> {{ $note->provider }}</p>
          <p><strong>Descrição:</strong> {{ $note->description }}</p>
          <p><strong>Valor:</strong> R$ {{ number_format($note->valor, 2, ',', '.') }}</p>
          <p><strong>Status:</strong> {{ $note->status }}</p>
          <p><strong>Centro de Custo:</strong> {{ $note->costCenter->name ?? 'Não informado' }}</p>
          <p><strong>Vencimento:</strong> {{ \Carbon\Carbon::parse($note->payday)->format('d/m/Y') }}</p>
          <div class="mt-4 text-right">
            <button @click="openModal = null" class="btn">Fechar</button>
          </div>
        </div>
      </div>

      {{-- Exclusão --}}
      <div x-show="openModal === 'deleteNote-{{ $note->id }}'" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
          <h3 class="text-lg font-bold mb-4">Confirmar exclusão</h3>
          <p>Deseja realmente excluir a nota de <strong>{{ $note->provider }}</strong>?</p>
          <div class="mt-4 flex justify-end space-x-2">
            <form method="POST" action="{{ route('notes.destroy', $note->id) }}" x-data @submit.prevent="if(confirm('Tem certeza que deseja excluir esta nota?')) $el.submit()">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger">Excluir</button>
            </form>
            <button @click="openModal = null" class="btn">Cancelar</button>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</x-app-layout>
