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
      const toast = document.getElementById('toast');
      if (toast) toast.remove();
    }, 8000);
  </script>

  <div class="space-y-4" x-data="{ openModal: null }">
    {{-- Filtros e botões --}}
    <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
      {{-- Busca --}}
      <form method="GET" action="{{ route('notes.index') }}" class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:border-transparent dark:bg-slate-800 dark:focus-within:border-primary-500 md:w-72">
        <div class="flex h-full items-center px-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search h-4 text-slate-400 group-focus-within:text-primary-500">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </div>
        <input name="search" class="h-full w-full border-transparent bg-transparent px-0 text-sm placeholder-slate-400 placeholder:text-sm focus:border-transparent focus:outline-none focus:ring-0" type="text" value="{{ request('search') }}" placeholder="Buscar por fornecedor, descrição...">
      </form>

      {{-- Ações --}}
      <div class="flex flex-wrap items-center gap-4 justify-end">
        {{-- Dropdown Filtros --}}
        <div class="dropdown" data-placement="bottom-end">
          <div class="dropdown-toggle">
            <button type="button" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
              <i class="w-4 h-4" data-feather="filter"></i>
              <span class="hidden sm:inline-block">Filtros</span>
              <i class="w-4 h-4" data-feather="chevron-down"></i>
            </button>
          </div>
          <div class="dropdown-content w-72 !overflow-visible">
            <form method="GET" action="{{ route('notes.index') }}">
              <ul class="dropdown-list space-y-4 p-4">
                <li class="dropdown-list-item">
                  <h2 class="my-1 text-sm font-medium">Tipo de notas</h2>
                  <select name="sector" class="tom-select w-full" autocomplete="off">
                    <option value="">Selecione um setor</option>
                    @foreach($notes as $note)
                      <option value="{{ $note->id }}">{{ $note->note_number }}</option>
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

        {{-- Botões de ação --}}
        <a href="{{ route('notes.export.csv') }}" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
          <i class="h-4" data-feather="upload"></i>
          <span class="hidden sm:inline-block">Exportar CSV</span>
        </a>
        <a href="{{ route('notes.export.pdf') }}" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
          <i class="h-4" data-feather="upload"></i>
          <span class="hidden sm:inline-block">Exportar PDF</span>
        </a>
        <a href="{{ route('notes.history') }}" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
          <i class="h-4" data-feather="search"></i>
          <span class="hidden sm:inline-block">Historico Notas</span>
        </a>

        {{-- Botão Nova Nota --}}
        <a class="btn btn-primary" href="{{ route('notes.create') }}">
          <i data-feather="file-plus" class="w-4 h-4"></i>
          <span class="hidden sm:inline-block">Nova Nota</span>
        </a>
      </div>
    </div>

    {{-- Tabela --}}
    <div class="table-responsive whitespace-nowrap rounded-primary">
      <table class="table">
        <thead>
          <tr>
            <th class="w-[5%]"><input class="checkbox" type="checkbox" /></th>
            <th class="uppercase">Nº Nota</th>
            <th class="uppercase">Prestador</th>
            <th class="uppercase">Descrição</th>
            <th class="uppercase">Valor</th>
            <th class="uppercase">Vencimento</th>
            <th class="uppercase">Status</th>
            <th class="uppercase">Centro de Custo</th>
            <th class="uppercase">Criado por</th>
            <th class="text-right uppercase">Ação</th>
          </tr>
        </thead>
        <tbody>
          @php
            $positions = auth()->user()->positions->pluck('name')->toArray();
            $diretores = ['DIRETOR INDUSTRIAL', 'DIRETOR COMERCIAL E MKT', 'DIRETOR ADM. FINANCEIRO'];
            $temPermissao = collect($positions)->intersect($diretores)->isNotEmpty();
            $temaPermissao = collect($positions)->contains('Fiscal de notas');
            $permissaoPagador = collect($positions)->contains('ANALISTA FINANCEIRO');
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
              <td>{{ $note->note_number }}</td>
              <td>{{ $note->provider }}</td>
              <td>{{ Str::limit($note->description, 40) }}</td>
              <td>R$ {{ number_format($note->valor, 2, ',', '.') }}</td>
              <td>{{ \Carbon\Carbon::parse($note->payday)->format('d/m/Y') }}</td>
              <td><div class="{{ $statusClasses[$note->status] ?? 'badge' }}">{{ $note->status }}</div></td>
              <td>{{ $note->costCenter->name ?? 'Não informado' }}</td>
              <td>{{ $note->createdBy->name ?? 'Não informado' }}</td>
              <td class="text-right">
                {{-- Dropdown Ações --}}
                <div class="flex justify-end">
                  <div class="dropdown" data-placement="bottom-start">
                    <div class="dropdown-toggle cursor-pointer">
                      <i class="w-6 text-slate-400" data-feather="more-horizontal"></i>
                    </div>
                    <div class="dropdown-content">
                      <ul class="dropdown-list">
                        @if($note->pdf_file)
                          <li class="dropdown-list-item">
                            <a href="{{ asset('storage/' . $note->pdf_file) }}" target="_blank" class="dropdown-link">
                              <i class="h-5 text-slate-400" data-feather="eye"></i>
                              <span>Visualizar</span>
                            </a>
                          </li>
                        @endif
                        @if($permissaoPagador && $note->status === 'Lançada no Financeiro')
                          <li class="dropdown-list-item">
                            <form method="POST" action="{{ route('notes.pagar', $note->id) }}" x-data @submit.prevent="if(confirm('Deseja pagar esta nota?')) $el.submit()">
                              @csrf
                              <button type="submit" class="dropdown-link text-left w-full">
                                <i class="h-5 text-green-500" data-feather="check-circle"></i>
                                <span>Pagar Nota</span>
                              </button>
                            </form>
                          </li>
                        @endif
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

                      </ul>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center text-slate-400 py-4">Nenhuma nota encontrada.</td>
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

    {{-- Modais de exclusão --}}
    @foreach($notes as $note)
      <div 
        x-show="openModal === 'deleteNote-{{ $note->id }}'" 
        x-cloak
        @keydown.escape.window="openModal = null"
        @click.away="openModal = null"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center"
        style="display: none;"
      >
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

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (window.feather) {
        feather.replace();
      }
    });
  </script>
</x-app-layout>
