<x-app-layout>
    <x-page-title page="Editar Arquivo" pageUrl="{{ route('archives.index') }}" header="Editar Arquivo" />

    @if(session('success'))
        <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Preview -->
        <section class="col-span-1 flex h-min w-full flex-col gap-6 lg:sticky lg:top-20">
            <div class="card">
                <div class="card-body flex flex-col items-center">
                    <div class="relative flex items-center justify-center h-24 w-24 rounded-full bg-slate-100 dark:bg-slate-700 p-4">
                        <i data-feather="file-text" class="w-10 h-10 text-slate-600 dark:text-slate-200"></i>
                    </div>
                    <h2 class="mt-4 text-[16px] font-medium text-center text-slate-700 dark:text-slate-200">{{ $archive->code }}</h2>
                    <p class="text-sm text-slate-400 mt-2 text-center">{{ $archive->file_type }}</p>
                    <a href="{{ Storage::url($archive->file_path) }}" target="_blank" class="text-blue-500 text-sm mt-2">Ver arquivo</a>
                </div>
            </div>
        </section>

        <!-- Formulários -->
        <section class="col-span-1 flex w-full flex-1 flex-col gap-6 lg:col-span-3 lg:w-auto">
            <!-- Código -->
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('archives.update.code', $archive->id) }}">
                        @csrf
                        @method('PUT')
                        <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Código do Arquivo</h2>
                        <p class="mb-4 text-sm text-slate-400">Altere o código identificador do arquivo.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <label class="label">
                                <span class="block mb-1">Código</span>
                                <input type="text" name="code" class="input" value="{{ old('code', $archive->code) }}" required />
                            </label>
                            <label class="label">
                                <span class="block mb-1">Descrição</span>
                                <input type="text" name="description" class="input" value="{{ old('description', $archive->description) }}" />    
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('archives.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                               Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Upload -->
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('archives.update.file', $archive->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Upload de Arquivo</h2>
                        <p class="mb-4 text-sm text-slate-400">Envie um novo arquivo para substituir o atual (opcional).</p>

                        <label class="label">
                            <span class="block mb-1">Novo Arquivo</span>
                            <input type="file" name="file" class="input" />
                        </label>

                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('archives.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                               Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Status -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Status do Arquivo</h2>
                    <p class="mb-4 text-sm text-slate-400">Ative ou inative este arquivo</p>

                    <form method="POST" action="{{ route('archives.update.status', $archive->id) }}">
                        @csrf
                        @method('PUT')

                        <label for="status" class="toggle my-2 flex items-center justify-between">
                            <div class="label">
                                <p class="text-sm font-normal text-slate-400">Ativar Arquivo</p>
                            </div>
                            <div class="relative">
                                <input type="hidden" name="status" value="0">
                                <input
                                    class="toggle-input peer sr-only"
                                    id="status"
                                    type="checkbox"
                                    name="status"
                                    value="1"
                                    {{ old('status', $archive->status) == 1 ? 'checked' : '' }}>
                                <div class="toggle-body"></div>
                            </div>
                        </label>

                        @error('status')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror

                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('archives.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                               Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pastas -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Pastas Vinculadas</h2>
                    <p class="mb-4 text-sm text-slate-400">Defina as pastas vinculadas a este arquivo.</p>

                    <form method="POST" action="{{ route('archives.update.folders', $archive->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300">Pastas</span>
                            <select name="folders[]" multiple
                                class="tom-select w-full min-h-[2.5rem] py-2 @error('folders') border-red-500 @enderror"
                                autocomplete="off">
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}" @selected(in_array($folder->id, $archive->folders->pluck('id')->toArray()))>
                                        {{ $folder->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('folders')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-4">
                            <a href="{{ route('archives.index') }}"
                               class="btn border border-slate-300 text-slate-500 dark:border-slate-700 dark:text-slate-300">
                               Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Setores -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-[16px] font-semibold text-slate-700 dark:text-slate-300">Setores Vinculados</h2>
                    <p class="mb-4 text-sm text-slate-400">Defina os setores vinculados a este documento.</p>

                    <form method="POST" action="{{ route('archives.update.sectors', $archive->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300">Setores</span>
                            <select name="sectors[]" multiple
                                class="tom-select w-full min-h-[2.5rem] py-2 @error('sectors') border-red-500 @enderror"
                                autocomplete="off">
                                @foreach($sectors as $sector)
                                    <option value="{{ $sector->id }}" @selected(in_array($sector->id, $archive->sectors->pluck('id')->toArray()))>
                                        {{ $sector->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sectors')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-4">
                            <a href="{{ route('archives.index') }}"
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
