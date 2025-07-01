<x-app-layout>
    <x-page-title page="Pastas" header="Lista de Pastas" />
    @section('title', 'Pastas | Inusittá')

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 mt-6">
        @foreach($folders as $folder)
            <a href="{{ route('folder.show', $folder->id) }}" class="flex flex-col items-center p-4 bg-white dark:bg-slate-800 rounded-xl shadow hover:shadow-lg transition group">
                <i data-feather="folder" class="w-12 h-12 text-yellow-400 mb-2 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-200 text-center">
                    {{ $folder->name }}
                </span>
                @if ($folder->archives_count > 0)
                    <span class="text-xs mt-1 text-slate-400 dark:text-slate-500">{{ $folder->archives_count }} arquivos</span>
                @endif
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $folders->appends(request()->query())->links('vendor.pagination.custom') }}
    </div>
</x-app-layout>
