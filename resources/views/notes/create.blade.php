<x-app-layout>
    <x-page-title page="Notas Fiscais" header="Cadastro de Nova Nota" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">

        <!-- Toast de sucesso -->
        @if(session('success'))
            <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Preview fixo à esquerda -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="file-text" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">
                        Cadastro de Nota Fiscal
                    </h2>
                </div>
            </div>
        </section>
        <!-- Formulário -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">
                        Informações da Nota
                    </h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">
                        Preencha as informações abaixo
                    </p>

                    <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">

                        @csrf

                        <section class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800 space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                {{-- Número da Nota --}}
                                <div class="flex flex-col">
                                    <label for="note_number" class="label font-medium">Número da Nota</label>
                                    <input type="text" name="note_number" id="note_number" class="input" placeholder="Número da nota" value="{{ old('note_number') }}">
                                </div>

                                {{-- Fornecedor --}}
                                <div class="flex flex-col">
                                    <label for="provider" class="label label-required font-medium">Prestador</label>
                                    <input type="text" name="provider" id="provider" class="input" required placeholder="Nome do prestador" value="{{ old('provider') }}">
                                </div>

                                {{-- Descrição --}}
                                <div class="flex flex-col">
                                    <label for="description" class="label label-required font-medium">Descrição</label>
                                    <input type="text" name="description" id="description" class="input" required placeholder="Ex: Prestação de serviço de..." value="{{ old('description') }}">
                                </div>

                                {{-- Valor --}}
                                <div class="flex flex-col">
                                    <label for="valor" class="label label-required font-medium">Valor</label>
                                    <input type="text" name="valor" id="valor" class="input" required placeholder="0,00" value="{{ old('valor') }}">
                                </div>
                                
                                {{-- Arquivo PDF --}}
                                <div class="flex flex-col">
                                    <label for="pdf_file" class="label label-required font-medium">Arquivo PDF</label>
                                    <input type="file" name="pdf_file" id="pdf_file" class="input" required accept="application/pdf">
                                </div>

                                {{-- Data de Vencimento --}}
                                <div class="flex flex-col">
                                    <label for="payday" class="label label-required font-medium">Data de Vencimento</label>
                                    <input type="date" name="payday" id="payday" class="input" required value="{{ old('payday') }}">
                                </div>
                                
                                @php
                                $positionsAprovadoras = ['DIRETOR INDUSTRIAL', 'DIRETOR COMERCIAL E MKT', 'DIRETOR ADM. FINANCEIRO', 'DIRETOR PRESIDENTE'];

                                $positions = \App\Models\Position::whereIn('name', $positionsAprovadoras)
                                            ->where('status', true)
                                            ->orderBy('name')
                                            ->get();
                                @endphp

                                <div class="flex flex-col">
                                <label for="approval_position_id" class="label font-medium">
                                    Diretoria responsável
                                </label>
                                <select name="approval_position_id" id="approval_position_id" class="input">
                                    <option value="">Selecione uma diretoria</option>
                                    @foreach($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                    @endforeach
                                </select>
                                </div>

                                {{-- Centro de Custo --}}
                                <div class="flex flex-col">
                                    <label for="cost_center_id" class="label font-medium">Centro de Custo</label>
                                    <select name="cost_center_id" id="cost_center_id" class="input">
                                        <option value="">Selecione um Centro de Custo</option>
                                        @foreach($costCenters as $costCenter)
                                            <option value="{{ $costCenter->id }}" {{ old('cost_center_id') == $costCenter->id ? 'selected' : '' }}>
                                                {{ $costCenter->name }}
                                            </option>
                                        @endforeach
                                    </select>
                            </div>
                        </section>
                        {{-- Botões --}}
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('notes.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Enviar para Aprovação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>