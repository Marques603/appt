<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Folder: ') . $folder->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4 text-sm text-gray-500">
                    <a href="{{ route('folders.index') }}" class="hover:underline">Folders</a>
                    <span> / {{ $folder->name }}</span>
                </div>

                <h3 class="text-lg font-medium text-gray-900 mb-4">Subfolders:</h3>
                @forelse ($subfolders as $subfolder)
                    <div class="flex items-center mb-2">
                        <img src="{{ asset('images/subfolder-icon.png') }}" alt="Subfolder" class="w-6 h-6 mr-2">
                        <a href="{{ route('subfolders.show', ['folder' => $folder->slug, 'subfolder' => $subfolder->slug]) }}" class="text-blue-600 hover:underline">
                            {{ $subfolder->name }} @if(!$subfolder->status) <span class="text-red-500">(Inactive)</span> @endif
                        </a>
                    </div>
                @empty
                    <p class="text-gray-600">No accessible subfolders in this folder.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>