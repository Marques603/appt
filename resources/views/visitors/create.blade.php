<x-app-layout>
    <x-page-title page="Cadastro de Visitante" header="Cadastro de Visitante" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">

        <!-- Preview fixo à esquerda -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="user-check" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">
                        Cadastro de Visitante
                    </h2>
                </div>
            </div>
        </section>

        <!-- Formulário principal -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Informações do Visitante
                    </h2>
                    <p class="text-sm font-normal text-slate-400 mb-4">Digite o nome ou CPF/CNPJ para buscar dados existentes ou cadastrar novo visitante</p>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('visitors.store') }}" class="space-y-6">
                        @csrf

                        <section class="rounded-lg p-6 shadow-sm dark:bg-slate-800 space-y-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                                {{-- Campo de busca (mesmo padrão dos outros) --}}
                                <div>
                                    <label for="search" class="label font-medium">Buscar por Nome ou CPF/CNPJ</label>
                                    <input type="text" name="search" id="search" class="input"
                                        placeholder="Digite para buscar"
                                        value="{{ request('search') }}">
                                </div>

                                {{-- Nome --}}
                                <div>
                                    <label for="name" class="label font-medium">Nome</label>
                                    <input type="text" name="name" id="name" class="input"
                                        value="{{ old('name', $visitorData->name ?? '') }}" required>
                                </div>

                                {{-- Documento --}}
                                <div>
                                    <label for="document" class="label font-medium">Documento (CPF/CNPJ)</label>
                                    <input type="text" name="document" id="document" class="input"
                                        value="{{ old('document', $visitorData->document ?? request('search')) }}" required>
                                </div>

                                {{-- Tipo de Visitante --}}
                                <div>
                                    <label for="typevisitor" class="label font-medium">Tipo de Visitante</label>
                                    <select name="typevisitor" id="typevisitor" class="select" required>
                                        <option value="">Selecione...</option>
                                        @foreach([
                                            'CANDIDATO', 'CLIENTE', 'COLETA DE RESÍDUOS',
                                            'COLETA/RETIRA DE MATERIAIS', 'FORNECEDOR',
                                            'LOJISTA', 'OUTROS', 'PRESTADOR DE SERVIÇOS', 'REPRESENTANTE'
                                        ] as $type)
                                            <option value="{{ $type }}"
                                                @selected(old('typevisitor') == $type)>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Estacionamento --}}
                                <div>
                                    <label for="parking" class="label font-medium">Estacionamento</label>
                                    <select name="parking" id="parking" class="select">
                                        <option value="">Selecione...</option>
                                        <option value="Sim" @selected(old('parking') == 'Sim')>Sim</option>
                                        <option value="Não" @selected(old('parking') == 'Não')>Não</option>
                                    </select>
                                </div>

                                {{-- Serviço --}}
                                <div>
                                    <label for="service" class="label font-medium">Motivo</label>
                                    <input type="text" name="service" id="service" class="input"
                                        value="{{ old('service') }}">
                                </div>

                                {{-- Empresa --}}
                                <div>
                                    <label for="company" class="label font-medium">Empresa</label>
                                    <input type="text" name="company" id="company" class="input"
                                        value="{{ old('company') }}">
                                </div>

                                {{-- Veículo --}}
                                <div>
                                    <label for="vehicle_model" class="label font-medium">Modelo do Veículo</label>
                                    <input type="text" name="vehicle_model" id="vehicle_model" class="input"
                                        value="{{ old('vehicle_model') }}">
                                </div>
                                <div>
                                    <label for="vehicle_plate" class="label font-medium">Placa do Veículo</label>
                                    <input type="text" name="vehicle_plate" id="vehicle_plate" class="input"
                                        value="{{ old('vehicle_plate') }}">
                                </div>

                            </div>
                        </section>

                        {{-- Botões --}}
                        <div class="flex justify-end gap-2 pt-4">
                            <a href="{{ route('visitors.index') }}" class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

    </div>
</x-app-layout>
