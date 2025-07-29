<x-app-layout>
    <x-page-title page="Editar Visitante" header="Editar Dados do Visitante" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Card informativo à esquerda -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="user" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">
                        {{ $visitor->name }}
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Documento: {{ $visitor->document }}
                    </p>
                </div>
            </div>
        </section>

        <!-- Formulário -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">
                        Dados do Visitante
                    </h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Atualize as informações do visitante</p>

                    <form action="{{ route('visitors.update', $visitor->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <section class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800 space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <!-- Nome -->
                                <div class="flex flex-col">
                                    <label class="label font-medium" for="name">Nome</label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="input"
                                        value="{{ old('name', $visitor->name) }}"
                                        required>
                                </div>
                                
                                <!-- Documento -->
                                <div class="flex flex-col">
                                    <label class="label font-medium" for="document">Documento</label>
                                    <input
                                        type="text"
                                        name="document"
                                        id="document"
                                        class="input"
                                        value="{{ old('document', $visitor->document) }}"
                                        required>
                                </div>

                                <!-- Tipo de visitante -->
                                <div class="flex flex-col">
                                    <label class="label font-medium" for="typevisitor">Tipo de Visitante</label>
                                    <select name="typevisitor" id="typevisitor" class="input" required>
                                        <option value="">Selecione...</option>
                                        <option value="Fornecedor" {{ old('typevisitor', $visitor->typevisitor) === 'Fornecedor' ? 'selected' : '' }}>Fornecedor</option>
                                        <option value="Cliente" {{ old('typevisitor', $visitor->typevisitor) === 'Cliente' ? 'selected' : '' }}>Cliente</option>
                                        <option value="Prestador" {{ old('typevisitor', $visitor->typevisitor) === 'Prestador' ? 'selected' : '' }}>Prestador de Serviço</option>
                                    </select>
                                </div>

                                <!-- Empresa -->
                                <div class="flex flex-col">
                                    <label class="label font-medium" for="company">Empresa</label>
                                    <input
                                        type="text"
                                        name="company"
                                        id="company"
                                        class="input"
                                        value="{{ old('company', $visitor->company) }}">
                                </div>

                                <!-- Serviço -->
                                <div class="flex flex-col">
                                    <label class="label font-medium" for="service">Serviço</label>
                                    <input
                                        type="text"
                                        name="service"
                                        id="service"
                                        class="input"
                                        value="{{ old('service', $visitor->service) }}">
                                </div>

                                <!-- Estacionamento -->
                                <div class="flex flex-col">
                                    <label class="label font-medium" for="parking">Estacionamento</label>
                                    <select name="parking" id="parking" class="input" required>
                                        <option value="">Selecione...</option>
                                        <option value="Sim" {{ old('parking', $visitor->parking) === 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('parking', $visitor->parking) === 'Não' ? 'selected' : '' }}>Não</option>
                                    </select>
                                </div>

                                <!-- Modelo do veículo -->
                                <div class="flex flex-col">
                                    <label class="label font-medium" for="vehicle_model">Modelo do Veículo</label>
                                    <input
                                        type="text"
                                        name="vehicle_model"
                                        id="vehicle_model"
                                        class="input"
                                        value="{{ old('vehicle_model', $visitor->vehicle_model) }}">
                                </div>

                                <!-- Placa do veículo -->
                                <div class="flex flex-col">
                                    <label class="label font-medium" for="vehicle_plate">Placa do Veículo</label>
                                    <input
                                        type="text"
                                        name="vehicle_plate"
                                        id="vehicle_plate"
                                        class="input"
                                        value="{{ old('vehicle_plate', $visitor->vehicle_plate) }}">
                                </div>
                            </div>
                        </section>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('visitors.index') }}" class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Atualizar Visitante
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                feather.replace();
            });
        </script>
    @endsection
</x-app-layout>
