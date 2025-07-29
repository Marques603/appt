<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archives in Sector: ') . $sector->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4 text-sm text-gray-500">
                    <a href="{{ route('folders.index') }}" class="hover:underline">Folders</a>
                    <span> / Sector: {{ $sector->name }}</span>
                </div>

                <h3 class="text-lg font-medium text-gray-900 mb-4">Archives:</h3>
                @forelse ($archives as $archive)
                    <div class="flex items-center mb-2 text-sm text-gray-700">
                        <img src="{{ asset('images/archive-icon.png') }}" alt="Archive" class="w-6 h-6 mr-2">
                        <div class="flex-grow">
                            <span class="font-semibold">{{ $archive->name }}</span> (Code: {{ $archive->code }}) @if($archive->revision) (Rev: {{ $archive->revision }}) @endif
                            <br>
                            <span class="text-xs text-gray-500">Uploaded by: {{ $archive->uploader->name ?? 'N/A' }} on {{ $archive->created_at->format('Y-m-d') }}</span>
                            @if(!$archive->status) <span class="text-red-500 text-xs">(Inactive)</span> @endif
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('archives.view', $archive->id) }}" target="_blank" class="text-green-600 hover:underline ml-2 text-sm">View</a>
                            <a href="{{ route('archives.download', $archive->id) }}" class="text-blue-600 hover:underline ml-2 text-sm">Download</a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">No archives found in this sector.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>