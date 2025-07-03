<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Folder;
use App\Models\Sector;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function create(Folder $folder, Sector $sector)
    {
        return view('archives.create', compact('folder', 'sector'));
    }

    public function store(Request $request, Folder $folder, Sector $sector)
    {
        $validated = $request->validate([
            'code' => 'required|unique:archives,code',
            'description' => 'nullable|string',
            'file' => 'required|file',
            'revision' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        // Salvar arquivo no storage
        $path = $request->file('file')->store('archives');

        $archive = Archive::create([
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
            'user_upload' => auth()->id(),
            'revision' => $validated['revision'] ?? null,
            'file_path' => $path,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
            'status' => $validated['status'],
        ]);

        // Vincular archive à pasta e setor
        $archive->folders()->syncWithoutDetaching([$folder->id]);
        $archive->sectors()->syncWithoutDetaching([$sector->id]);

        return redirect()->route('folders.sectorFiles', ['folder' => $folder->id, 'sector' => $sector->id])
                         ->with('success', 'Arquivo criado e vinculado com sucesso!');
    }public function index(Request $request)
{
    $query = Archive::query();

    // Exemplo de filtro (status)
    if ($request->has('status') && $request->status !== '') {
        $query->where('status', $request->status);
    }

    // Exemplo de filtro (sector)
    if ($request->has('sector') && $request->sector !== '') {
        $query->whereHas('sectors', function ($q) use ($request) {
            $q->where('sector_id', $request->sector);
        });
    }

    // Pesquisa simples pelo código ou descrição
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Eager load para evitar N+1
    $archives = $query->with(['folders', 'sectors'])->paginate(10);

    $sectors = Sector::all(); // Para dropdown filtro

    return view('archives.index', compact('archives', 'sectors'));
}

}
