<x-app-layout>
    <x-page-title page="Tipos de Convênio" header="Cadastrar Novo Tipo de Convênio" />
    @section('title', 'Cadastro de Tipo de Convênio | Inusittá')

    @if ($errors->any())
        <div class="mb-4 rounded border border-red-300 bg-red-100 p-4 text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        {{-- Preview fixo à esquerda --}}
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="file-text" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">
                        Cadastro de Tipo de Convênio
                    </h2>
                </div>
            </div>
        </section>

        {{-- Formulário --}}
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">
                        Informações do Tipo de Convênio
                    </h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">
                        Preencha as informações abaixo
                    </p>

                    <form action="{{ route('agreements_type.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <section class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800 space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                {{-- Tipo --}}
                                <div class="flex flex-col">
                                    <label for="type" class="label label-required font-medium">Tipo</label>
                                    <input type="text" name="type" id="type" class="input" required placeholder="Tipo do convênio" value="{{ old('type') }}">
                                </div>

                                {{-- Descrição --}}
                                <div class="flex flex-col sm:col-span-2">
                                    <label for="description" class="label label-required font-medium">Descrição</label>
                                    <textarea name="description" id="description" rows="4" class="input" required placeholder="Descreva o tipo do convênio">{{ old('description') }}</textarea>
                                </div>

                            </div>
                        </section>

                        {{-- Botões --}}
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('agreements_type.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Salvar Tipo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

    </div>
</x-app-layout>
