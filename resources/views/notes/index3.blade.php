<x-app-layout>
  <x-page-title page="Notas Financeiras" header="Notas sem Aprovação" />
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

    {{-- Filtros e botão --}}
    <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row">
      <form method="GET" action="{{ route('notes3.index') }}" class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:border-transparent dark:bg-slate-800 dark:focus-within:border-primary-500 md:w-72">
        <div class="flex h-full items-center px-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search h-4 text-slate-400 group-focus-within:text-primary-500">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </div>
        <input name="search" class="h-full w-full border-transparent bg-transparent px-0 text-sm placeholder-slate-400 placeholder:text-sm focus:border-transparent focus:outline-none focus:ring-0" type="text" value="{{ request('search') }}" placeholder="Buscar por fornecedor, descrição...">
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
            <th class="uppercase">Nº Nota</th>
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
            $permissaoExcluir = collect($positions)->contains('Fiscal de notas') 
                || collect($positions)->contains('DIRETOR INDUSTRIAL') 
                || collect($positions)->contains('DIRETOR COMERCIAL E MKT') 
                || collect($positions)->contains('DIRETOR ADM. FINANCEIRO');
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
              <td class="text-right">
                <div class="flex justify-end">
                  <div class="dropdown" data-placement="bottom-start">
                    <div class="dropdown-toggle cursor-pointer">
                      <i class="w-6 text-slate-400" data-feather="more-horizontal"></i>
                    </div>
                    <div class="dropdown-content">
                      <ul class="dropdown-list">
                        {{-- Visualizar --}}
                        @if($note->pdf_file)
                          <li class="dropdown-list-item">
                            <a href="{{ asset('storage/' . $note->pdf_file) }}" target="_blank" class="dropdown-link">
                              <i class="h-5 text-slate-400" data-feather="eye"></i>
                              <span>Visualizar</span>
                            </a>
                          </li>
                        @else
                          <li class="dropdown-list-item">
                            <span class="dropdown-link cursor-not-allowed opacity-50" title="Nenhum PDF disponível">
                              <i class="h-5 text-slate-400" data-feather="eye"></i>
                              <span>Visualizar</span>
                            </span>
                          </li>
                        @endif

                        {{-- Excluir --}}
                        @if($permissaoExcluir)
                          <li class="dropdown-list-item">
                            <a href="#" class="dropdown-link text-red-600" @click.prevent="openModal = 'deleteNote-{{ $note->id }}'">
                              <i class="h-5 text-red-600" data-feather="trash-2"></i>
                              <span>Excluir</span>
                            </a>
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
              <td colspan="9" class="text-center text-slate-400 py-4">Nenhuma nota encontrada.</td>
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

    {{-- Modal Exclusão --}}
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
    document.addEventListener('DOMContentLoaded', () => {
      if (window.feather) {
        feather.replace();
      }
    });
  </script>

</x-app-layout>
