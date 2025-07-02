<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Explore Folders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Main Folders:</h3>
                @forelse ($folders as $folder)
                    <div class="flex items-center mb-2">
                        <img src="{{ asset('images/folder-icon.png') }}" alt="Folder" class="w-6 h-6 mr-2">
                        <a href="{{ route('folders.show', $folder->slug) }}" class="text-blue-600 hover:underline">
                            {{ $folder->name }} @if(!$folder->status) <span class="text-red-500">(Inactive)</span> @endif
                        </a>
                    </div>
                @empty
                    <p class="text-gray-600">No accessible folders found.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>