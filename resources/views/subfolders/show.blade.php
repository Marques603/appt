<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subfolder: ') . $subfolder->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4 text-sm text-gray-500">
                    <a href="{{ route('folders.index') }}" class="hover:underline">Folders</a>
                    <span> / </span>
                    <a href="{{ route('folders.show', $folder->slug) }}" class="hover:underline">{{ $folder->name }}</a>
                    <span> / {{ $subfolder->name }}</span>
                </div>

                <h3 class="text-lg font-medium text-gray-900 mb-4">Accessible Sectors:</h3>
                @forelse ($accessibleSectors as $sector)
                    <div class="flex items-center mb-2">
                        <img src="{{ asset('images/sector-icon.png') }}" alt="Sector" class="w-6 h-6 mr-2">
                        <a href="{{ route('sectors.archives.index', $sector->id) }}" class="text-blue-600 hover:underline">
                            {{ $sector->name }} ({{ $sector->acronym }}) @if(!$sector->status) <span class="text-red-500">(Inactive)</span> @endif
                        </a>
                    </div>
                @empty
                    <p class="text-gray-600">No accessible sectors in this subfolder.</p>
                @endforelse

                <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4">Archives (Directly in Subfolder, if accessible):</h3>
                @forelse ($accessibleArchives as $archive)
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
                    <p class="text-gray-600">No accessible archives directly in this subfolder.</p>
                @endforelse

                {{-- Formulário de Upload --}}
                <div class="mt-8 p-4 border rounded-lg bg-gray-50">
                    <h4 class="text-md font-medium text-gray-800 mb-3">Upload New Archive:</h4>
                    <form action="{{ route('archives.upload', ['folder' => $folder->slug, 'subfolder' => $subfolder->slug]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="block text-sm font-medium text-gray-700">Code:</label>
                            <input type="text" name="code" id="code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="revision" class="block text-sm font-medium text-gray-700">Revision (optional):</label>
                            <input type="text" name="revision" id="revision" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('revision') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="archive_file" class="block text-sm font-medium text-gray-700">File:</label>
                            <input type="file" name="archive_file" id="archive_file" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none">
                            @error('archive_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description (optional):</label>
                            <textarea name="description" id="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="sector_upload_id" class="block text-sm font-medium text-gray-700">Attach to Sector (for upload):</label>
                            <select name="sector_upload_id" id="sector_upload_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @forelse ($accessibleSectors as $sector)
                                    <option value="{{ $sector->id }}">{{ $sector->name }} ({{ $sector->acronym }})</option>
                                @empty
                                    <option value="" disabled>No accessible sectors for upload.</option>
                                @endforelse
                            </select>
                            @error('sector_upload_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150" @if($accessibleSectors->isEmpty()) disabled @endif>
                            Upload Archive
                        </button>
                    </form>
                    @if (session('success'))
                        <div class="mt-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>