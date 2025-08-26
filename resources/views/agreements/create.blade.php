{{-- resources/views/agreements/create.blade.php --}}
<x-app-layout>
    <x-page-title page="Convênios" header="Cadastro de Novo Convênio" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">

        {{-- Toast de sucesso --}}
        @if(session('success'))
            <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Preview fixo à esquerda --}}
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="file-text" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">
                        Cadastro de Convênio
                    </h2>
                </div>
            </div>
        </section>

        {{-- Formulário --}}
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">
                        Informações do Convênio
                    </h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">
                        Preencha as informações abaixo
                    </p>

                    <form action="{{ route('agreements.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <section class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800 space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                {{-- Estabelecimento --}}
                                <div class="flex flex-col">
                                    <label for="name" class="label label-required font-medium">Estabelecimento</label>
                                    <input type="text" name="name" id="name" class="input" required placeholder="Estabelecimento" value="{{ old('name') }}">
                                </div>

                                {{-- Contato --}}
                                <div class="flex flex-col">
                                    <label for="contact" class="label label-required font-medium">Contato</label>
                                    <input type="text" name="contact" id="contact" class="input" required placeholder="Contato" value="{{ old('contact') }}">
                                </div>

                                {{-- Nome da Rua --}}
                                <div class="flex flex-col">
                                    <label for="road_name" class="label label-required font-medium">Rua</label>
                                    <input type="text" name="road_name" id="road_name" class="input" required placeholder="Rua" value="{{ old('road_name') }}">
                                </div>

                                {{-- Cidade --}}
                                <div class="flex flex-col">
                                    <label for="city" class="label label-required font-medium">Cidade</label>
                                    <input type="text" name="city" id="city" class="input" required placeholder="Nome da cidade" value="{{ old('city') }}">
                                </div>

                                {{-- Número --}}
                                <div class="flex flex-col">
                                    <label for="number" class="label label-required font-medium">Número</label>
                                    <input type="text" name="number" id="number" class="input" required placeholder="Número" value="{{ old('number') }}">
                                </div>

                                <div class="flex flex-col">
                                <label for="agreements_type_id" class="label label-required font-medium">
                                    Tipo de Convênio
                                </label>
                                <select name="agreements_type_id" id="agreements_type_id" class="input">
                                    <option value="">Selecione um tipo de convênio</option>
                                    @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                                </div>
 
                                {{-- Descrição --}}
                                <div class="flex flex-col sm:col-span-2">
                                    <label for="description" class="label label-required font-medium">Descrição</label>
                                    <textarea name="description" id="description" rows="4" class="input" required placeholder="Descreva o convênio">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </section>

                        {{-- Botões --}}
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('agreements.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Salvar Convênio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
