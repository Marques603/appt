<x-app-layout>
    <x-page-title page="Lista de Categorias" pageUrl="{{ route('categorie.index') }}" header="Editar Categoria" />


    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Preview fixo à esquerda -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="layers" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">Categoria</h2>
                </div>
            </div>
        </section>

        <!-- Formulários -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">

            <!-- Formulário 1: Detalhes da categorie -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Detalhes da Categoria</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Atualize as informações da Categoria</p>

                    <form method="POST" action="{{ route('categorie.update', $categorie) }}" class="flex flex-col gap-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Nome -->
                            <label class="label">
                                <span class="block mb-1">Nome</span>
                                <input type="text" name="name" class="input @error('name') border-red-500 @enderror"
                                       value="{{ old('name', $categorie->name) }}" />
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>

                            <!-- Descrição -->
                            <label class="label">
                                <span class="block mb-1">Descrição</span>
                                <input type="text" name="description" class="input @error('description') border-red-500 @enderror"
                                       value="{{ old('description', $categorie->description) }}" />
                                @error('description')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('categorie.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Atualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

<!-- Atualizar Status da Categoria -->
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('categorie.update.status', $categorie->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="0">

            <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Ativar Caterogia</h2>
            <p class="text-sm font-normal text-slate-400">Defina se esta categorie estará ativa no sistema.</p>

            <label for="status" class="toggle my-2 flex items-center justify-between">
                <div class="label">
                    <p class="text-sm font-normal text-slate-400">Ativar esta categorie</p>
                </div>
                <div class="relative">
                    <input
                        class="toggle-input peer sr-only"
                        id="status"
                        type="checkbox"
                        name="status"
                        value="1"
                        {{ old('status', $categorie->status) == 1 ? 'checked' : '' }}>
                    <div class="toggle-body"></div>
                </div>
            </label>

            @error('status')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror

            <div class="flex items-center justify-end gap-4 mt-6">
                <a href="{{ route('categorie.index') }}"
                   class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </div>
        </form>
    </div>
</div>


            <!-- Formulário 3: Usuários Responsáveis -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Usuários Responsáveis</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Defina os usuários responsáveis por esta categoria</p>

                    <form method="POST" action="{{ route('categorie.update.responsibles', $categorie) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300">Usuários</span>
                            <select name="responsible_users[]" multiple
                                class="tom-select w-full min-h-[2.5rem] py-2 @error('responsible_users') border-red-500 @enderror"
                                autocomplete="off">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $categorie->responsibleUsers->contains($user->id) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('responsible_users')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-4">
                            <a href="{{ route('categorie.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                               Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>

        </section>
    </div>
</x-app-layout>
