{{-- Blade: resources/views/archives/index.blade.php --}}
<x-app-layout>
    <x-page-title page="Arquivos" header="Setores com Arquivos" />

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        @foreach($sectors as $sector)
            <a href="{{ route('archives.bySector', $sector->id) }}" class="bg-white dark:bg-slate-800 rounded-primary p-4 shadow hover:shadow-lg transition block">
                <div class="flex items-center gap-3">
                    <i data-feather="folder" class="w-6 h-6 text-yellow-500"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $sector->name }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $sector->archives_count }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</x-app-layout>
