<x-app-layout>
    <x-page-title page="Cadastro de Visitante" header="Cadastro de Visitante" />

     @section('title', 'Cadastro de Visitante | Inusittá')

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



    <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">

        <!-- Preview fixo à esquerda -->
        <section class="col-span-1 flex h-min w-full flex-col gap-8 lg:sticky lg:top-20">
            <div class="card p-6 shadow-lg">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-28 w-28 rounded-full bg-slate-100 dark:bg-slate-700 p-4 shadow-md">
                        <i data-feather="user-check" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">
                        Cadastro de Visitante
                    </h2>
                </div>
            </div>
        </section>

        <!-- Coluna direita -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-8 lg:col-span-3 lg:w-auto">

            <!-- Card de busca -->
            <div class="card p-6 shadow-lg">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300 mb-2">Buscar Visitante</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">
                        Digite o nome ou CPF/CNPJ para buscar dados existentes e agilizar o cadastro
                    </p>

                    <form method="GET" action="{{ route('visitors.create') }}" class="space-y-6">
                        <input type="text" name="search" id="search"
                               placeholder="Nome ou CPF/CNPJ"
                               value="{{ request('search') }}"
                               class="input w-full" />

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('visitors.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">Buscar</button>               
                        </div>
                    </form>
                </div>
            </div>

            <!-- Formulário -->
            <div class="card p-6 shadow-lg">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300 mb-2">Informações do Visitante</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Preencha os dados abaixo</p>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('visitors.store') }}" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="label font-medium mb-1">Nome</label>
                                <input type="text" name="name" id="name" class="input w-full"
                                       value="{{ old('name', $visitorData->name ?? '') }}" required>
                            </div>

                            <div>
                                <label for="document" class="label font-medium mb-1">Documento (CPF/CNPJ)</label>
                                <input type="text" name="document" id="document" class="input w-full"
                                       value="{{ old('document', $visitorData->document ?? '') }}" required>
                            </div>

                            <div>
                                <label for="typevisitor" class="label font-medium mb-1">Tipo de Visitante</label>
                                <select name="typevisitor" id="typevisitor" class="select w-full" required>
                                    <option value="">Selecione...</option>
                                    @foreach([
                                        'CANDIDATO', 'CLIENTE', 'COLETA DE RESÍDUOS',
                                        'COLETA/RETIRA DE MATERIAIS', 'FORNECEDOR',
                                        'LOJISTA', 'OUTROS', 'PRESTADOR DE SERVIÇOS', 'REPRESENTANTE'
                                    ] as $type)
                                        <option value="{{ $type }}" @selected(old('typevisitor') == $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="parking" class="label font-medium mb-1">Estacionamento</label>
                                <select name="parking" id="parking" class="select w-full">
                                    <option value="">Selecione...</option>
                                    <option value="Sim" @selected(old('parking') == 'Sim')>Sim</option>
                                    <option value="Não" @selected(old('parking') == 'Não')>Não</option>
                                </select>
                            </div>

                            <div>
                                <label for="service" class="label font-medium mb-1">Motivo</label>
                                <input type="text" name="service" id="service" class="input w-full"
                                       value="{{ old('service') }}">
                            </div>

                            <div id="empresa-wrapper" class="hidden">
                                <label for="company" class="label font-medium mb-1">Empresa</label>
                                <input type="text" name="company" id="company" class="input w-full"
                                       value="{{ old('company') }}">
                            </div>

                            <div id="vehicle-wrapper" class="grid grid-cols-1 gap-6 sm:grid-cols-2 hidden">
                                <div>
                                    <label for="vehicle_model" class="label font-medium mb-1">Modelo do Veículo</label>
                                    <input type="text" name="vehicle_model" id="vehicle_model" class="input w-full"
                                           value="{{ old('vehicle_model') }}">
                                </div>
                                <div>
                                    <label for="vehicle_plate" class="label font-medium mb-1">Placa do Veículo</label>
                                    <input type="text" name="vehicle_plate" id="vehicle_plate" class="input w-full"
                                           value="{{ old('vehicle_plate') }}">
                                </div>
                            </div>

                            <div>
                                <label for="user_id" class="label font-medium mb-1">Colaborador Responsável</label>
                                <select name="responsible_collaborator" id="responsible_collaborator" class="tom-select w-full dark:bg-slate-800" required>
    <option value="">Selecione um colaborador</option>
    @foreach($users as $user)
        <option value="{{ $user->id }}" @selected(old('responsible_collaborator') == $user->id)>
            {{ $user->name }}
        </option>
    @endforeach
</select>

                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('visitors.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">Adicionar</button>               
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('typevisitor');
            const empresaWrapper = document.getElementById('empresa-wrapper');
            const companyInput = document.getElementById('company');

            const parkingSelect = document.getElementById('parking');
            const vehicleWrapper = document.getElementById('vehicle-wrapper');
            const vehicleModelInput = document.getElementById('vehicle_model');
            const vehiclePlateInput = document.getElementById('vehicle_plate');

            const tiposComEmpresa = [
                'COLETA DE RESÍDUOS', 'COLETA/RETIRA DE MATERIAIS',
                'FORNECEDOR', 'LOJISTA', 'OUTROS',
                'PRESTADOR DE SERVIÇOS', 'REPRESENTANTE'
            ];

            function toggleEmpresaField() {
                empresaWrapper.classList.toggle('hidden', !tiposComEmpresa.includes(typeSelect.value));
                if (!tiposComEmpresa.includes(typeSelect.value)) {
                    companyInput.value = '';
                }
            }

            function toggleVehicleFields() {
                vehicleWrapper.classList.toggle('hidden', parkingSelect.value !== 'Sim');
                if (parkingSelect.value !== 'Sim') {
                    vehicleModelInput.value = '';
                    vehiclePlateInput.value = '';
                }
            }

            toggleEmpresaField();
            toggleVehicleFields();

            typeSelect.addEventListener('change', toggleEmpresaField);
            parkingSelect.addEventListener('change', toggleVehicleFields);

            // Inicializa Tom Select no select do colaborador
            new TomSelect('#user_id', {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "Selecione um colaborador",
                allowEmptyOption: true
            });
        });
    </script>
</x-app-layout>
