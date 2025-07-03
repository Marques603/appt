<x-app-layout>
    <x-page-title page="Novo Arquivo" header="Adicionar arquivo na pasta {{ $folder->name }} e setor {{ $sector->name }}" />

    <form action="{{ route('archives.store', ['folder' => $folder->id, 'sector' => $sector->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label for="code" class="block font-medium">Código</label>
            <input type="text" name="code" id="code" class="input" required value="{{ old('code') }}">
            @error('code') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block font-medium">Descrição</label>
            <textarea name="description" id="description" class="input">{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="file" class="block font-medium">Arquivo</label>
            <input type="file" name="file" id="file" required>
            @error('file') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="revision" class="block font-medium">Revisão</label>
            <input type="text" name="revision" id="revision" class="input" value="{{ old('revision') }}">
        </div>

        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="status" value="1" checked class="form-checkbox">
                <span class="ml-2">Ativo</span>
            </label>
        </div>

        <button type="submit" class="btn-primary">Salvar Arquivo</button>
    </form>
</x-app-layout>
