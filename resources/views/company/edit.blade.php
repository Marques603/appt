<x-app-layout>
    <!-- Título da Página -->
    <x-page-title page="Lista de Empresas" pageUrl="{{ route('company.index') }}" header="Editar Empresa" />


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
                        <i data-feather="briefcase" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">Empresa</h2>
                </div>
            </div>
        </section>

         <!-- Formulários -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">

            <!-- Formulário 1: Detalhes -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Detalhes da Empresa</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Edite as informações principais da empresa</p>

<form method="POST" action="{{ route('company.update.details', $company) }}" class="flex flex-col gap-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Nome -->
        <label class="label">
            <span class="block mb-1">Nome da Empresa</span>
            <input type="text" name="name" class="input @error('name') border-red-500 @enderror"
                value="{{ old('name', $company->name) }}" />
            @error('name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </label>

        <!-- Descrição -->
        <label class="label">
            <span class="block mb-1">Descrição</span>
            <input type="text" name="corporate_name" class="input @error('corporate_name') border-red-500 @enderror"
                value="{{ old('corporate_name', $company->corporate_name) }}" />
            @error('corporate_name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </label>

                <!-- Descrição -->
        <label class="label">
            <span class="block mb-1">CNPJ</span>
            <input type="text" name="cnpj" class="input @error('cnpj') border-red-500 @enderror"
                value="{{ old('cnpj', $company->cnpj) }}" />
            @error('cnpj')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </label>
    </div>

    <div class="flex items-center justify-end gap-4">
        <a href="{{ route('company.index') }}"
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

            <!-- Formulário 2: Status -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Status da Empresa</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Ative ou inative esta empresa</p>

                    <form method="POST" action="{{ route('company.update.status', $company) }}">
                        @csrf
                        @method('PUT')

                        <label for="status" class="toggle my-2 flex items-center justify-between">
                            <div class="label">
                                <p class="text-sm font-normal text-slate-400">Ativar Empresa</p>
                            </div>
                            <div class="relative">
                                <input type="hidden" name="status" value="0">
                                <input
                                    class="toggle-input peer sr-only"
                                    id="status"
                                    type="checkbox"
                                    name="status"
                                    value="1"
                                       {{ old('status', $company->status) == 1 ? 'checked' : '' }}>
                                <div class="toggle-body"></div>
                            </div>
                        </label>

                        @error('status')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror

                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('company.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                               Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>

                        <!-- Formulário 3: Usuários Responsavel -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Usuários Responsável</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Defina o usuários responsável por esta empresa</p>

                    <form method="POST" action="{{ route('company.update.responsibles', $company) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300">Usuários</span>
                            <select name="users[]" multiple
                                class="tom-select w-full min-h-[2.5rem] py-2 @error('users') border-red-500 @enderror"
                                autocomplete="off">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $company->responsibles->contains($user->id) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('users')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-4">
                            <a href="{{ route('company.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                               Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Formulário 4: Usuários Vinculados -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Usuários Vinculados</h2>
                    <p class="mb-4 text-sm font-normal text-slate-400">Defina os usuários que pertencem a esta empresa</p>

                    <form method="POST" action="{{ route('company.update.users', $company) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300">Usuários</span>
                            <select name="users[]" multiple
                                class="tom-select w-full min-h-[2.5rem] py-2 @error('users') border-red-500 @enderror"
                                autocomplete="off">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $company->users->contains($user->id) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('users')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-4">
                            <a href="{{ route('company.index') }}"
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