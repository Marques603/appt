<x-app-layout>
    <x-page-title page="Nova Pasta" pageUrl="{{ route('folders.index') }}" header="Criar nova pasta" />
    @section('title', 'Criar pasta | Inusittá')

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Preview -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-yellow-100 dark:bg-yellow-700 p-4">
                        <i class="ti ti-folder text-4xl text-yellow-500 dark:text-yellow-300"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">Pasta</h2>
                </div>
            </div>
        </section>

        <!-- Formulário -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Detalhes da Pasta</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Preencha as informações da pasta</p>

                    <form action="{{ route('folders.store') }}" method="POST" class="flex flex-col gap-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <label class="label">
                                <span class="block mb-1">Nome</span>
                                <input type="text" name="name"
                                       class="input @error('name') border-red-500 @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>

                            <label class="label">
                                <span class="block mb-1">Status</span>
                                <select name="status"
                                        class="input @error('status') border-red-500 @enderror"
                                        required>
                                    <option value="1" @selected(old('status') == '1')>Ativa</option>
                                    <option value="0" @selected(old('status') == '0')>Inativa</option>
                                </select>
                                @error('status')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>

                        <label class="label">
                            <span class="block mb-1">Descrição</span>
                            <textarea name="description" rows="3"
                                      class="input @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </label>

                        @if(isset($parentId))
                            <input type="hidden" name="parent_id" value="{{ $parentId }}">
                        @endif

                        <label class="label">
                            <span class="block mb-1">Planos com acesso</span>
                            <select name="plans[]" multiple class="tom-select w-full @error('plans') border-red-500 @enderror">
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}" @selected(collect(old('plans'))->contains($plan->id))>
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plans')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </label>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('folders.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-folder-plus"></i> Salvar Pasta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
