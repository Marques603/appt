<x-app-layout>
    <x-page-title page="Editar Pasta" header="Editando pasta: {{ $folder->name }}" />

    <form action="{{ route('folders.update', $folder) }}" method="POST" class="space-y-6 max-w-lg mx-auto">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-medium text-gray-700">Nome</label>
            <input type="text" name="name" id="name" value="{{ old('name', $folder->name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block font-medium text-gray-700">Descrição</label>
            <textarea name="description" id="description" rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $folder->description) }}</textarea>
            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="parent_id" class="block font-medium text-gray-700">Pasta Pai (opcional)</label>
            <select name="parent_id" id="parent_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Nenhuma (pasta raiz) --</option>
                @foreach ($folders as $f)
                    @if ($f->id !== $folder->id)
                        <option value="{{ $f->id }}" @selected(old('parent_id', $folder->parent_id) == $f->id)>
                            {{ $f->name }}
                        </option>
                    @endif
                @endforeach
            </select>
            @error('parent_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="sectors" class="block font-medium text-gray-700">Setores com acesso</label>
            <select name="sectors[]" id="sectors" multiple
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach ($sectors as $sector)
                    <option value="{{ $sector->id }}" 
                        @selected(old('sectors', $folder->sectors->pluck('id')->toArray()) 
                            && in_array($sector->id, old('sectors', $folder->sectors->pluck('id')->toArray())))>
                        {{ $sector->name }}
                    </option>
                @endforeach
            </select>
            @error('sectors') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="status" class="block font-medium text-gray-700">Status</label>
            <select name="status" id="status"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="1" @selected(old('status', $folder->status) == '1')>Ativa</option>
                <option value="0" @selected(old('status', $folder->status) == '0')>Inativa</option>
            </select>
            @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('folders.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Atualizar</button>
        </div>
    </form>
</x-app-layout>
