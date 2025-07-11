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

        <!-- Formulário -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Informações do Visitante</h2>
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

                    <form method="POST" action="{{ route('visitors.store') }}" class="space-y-6">
                        @csrf

                        <section class="rounded-lg p-6 shadow-sm dark:bg-slate-800 space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                {{-- Nome --}}
                                <div class="flex flex-col">
                                    <label for="name" class="label font-medium">Nome</label>
                                    <input type="text" name="name" id="name" class="input" value="{{ old('name') }}" required>
                                </div>

                                {{-- Documento --}}
                                <div class="flex flex-col">
                                    <label for="document" class="label font-medium">Documento (CPF/CNPJ)</label>
                                    <input type="text" name="document" id="document" class="input" value="{{ old('document') }}" required>
                                </div>

                                {{-- Tipo de Visitante --}}
                                <div class="flex flex-col">
                                    <label for="typevisitor" class="label font-medium">Tipo de Visitante</label>
                                    <select name="typevisitor" id="typevisitor" class="select" required>
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

                                {{-- Estacionamento --}}
                                <div class="flex flex-col">
                                    <label for="parking" class="label font-medium">Estacionamento</label>
                                    <select name="parking" id="parking" class="select">
                                        <option value="">Selecione...</option>
                                        <option value="Sim" @selected(old('parking') == 'Sim')>Sim</option>
                                        <option value="Não" @selected(old('parking') == 'Não')>Não</option>
                                    </select>
                                </div>

                                                                {{-- Serviço --}}
                                <div class="flex flex-col">
                                    <label for="service" class="label font-medium">Motivo</label>
                                    <input type="text" name="service" id="service" class="input" value="{{ old('service') }}">
                                </div>

                                {{-- Empresa (condicional) --}}
                                <div class="flex flex-col" id="empresa-wrapper" style="display:none;">
                                    <label for="company" class="label font-medium">Empresa</label>
                                    <input type="text" name="company" id="company" class="input" value="{{ old('company') }}">
                                </div>

                               {{-- Veículo (condicional) --}}
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2" id="vehicle-wrapper" style="display:none;">
                                    <div class="flex flex-col">
                                        <label for="vehicle_model" class="label font-medium">Modelo do Veículo</label>
                                        <input type="text" name="vehicle_model" id="vehicle_model" class="input" value="{{ old('vehicle_model') }}">
                                    </div>
                                    <div class="flex flex-col">
                                        <label for="vehicle_plate" class="label font-medium">Placa do Veículo</label>
                                        <input type="text" name="vehicle_plate" id="vehicle_plate" class="input" value="{{ old('vehicle_plate') }}">
                                    </div>
                                </div>

                            </div>
                        </section>



                        {{-- Botões --}}
                        <div class="flex justify-end gap-2 pt-4">
                            <a href="{{ route('visitors.index') }}" class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

    </div>

    {{-- Scripts --}}
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
                'COLETA DE RESÍDUOS',
                'COLETA/RETIRA DE MATERIAIS',
                'FORNECEDOR',
                'LOJISTA',
                'OUTROS',
                'PRESTADOR DE SERVIÇOS',
                'REPRESENTANTE'
            ];

            function toggleEmpresaField() {
                if(tiposComEmpresa.includes(typeSelect.value)) {
                    empresaWrapper.style.display = 'flex';
                } else {
                    empresaWrapper.style.display = 'none';
                    companyInput.value = '';
                }
            }

            function toggleVehicleFields() {
                if(parkingSelect.value === 'Sim') {
                    vehicleWrapper.style.display = 'grid';
                } else {
                    vehicleWrapper.style.display = 'none';
                    vehicleModelInput.value = '';
                    vehiclePlateInput.value = '';
                }
            }

            toggleEmpresaField();
            toggleVehicleFields();

            typeSelect.addEventListener('change', toggleEmpresaField);
            parkingSelect.addEventListener('change', toggleVehicleFields);
        });
    </script>
</x-app-layout>
