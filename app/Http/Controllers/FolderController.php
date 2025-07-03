<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Sector;
use App\Models\Archive;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        $parentId = $request->query('parent_id');

        $folders = Folder::where('parent_id', $parentId)->paginate(10);
        $parentFolder = $parentId ? Folder::with('parent')->findOrFail($parentId) : null;


        if ($folders->isEmpty() && $parentFolder) {
            // chegamos no último nível: mostrar setores da pasta
            $sectors = $parentFolder->sectors;
            return view('folders.last-level', compact('parentFolder', 'sectors'));
        }

        return view('folders.index', compact('folders', 'parentFolder'));
    }

    public function create(Request $request)
    {
        $parentId = $request->query('parent_id');
        $folders = Folder::all(); // para escolher pasta pai, se quiser
        $sectors = Sector::all(); // popular select setores

        return view('folders.create', compact('folders', 'parentId', 'sectors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:folders,name',
            'description' => 'nullable',
            'status' => 'required|boolean',
            'parent_id' => 'nullable|exists:folders,id',
            'sectors' => 'nullable|array',
            'sectors.*' => 'exists:sector,id',
        ]);

        $folder = Folder::create($validated);

        // sincronizar setores
        $folder->sectors()->sync($request->sectors ?? []);

        return redirect()->route('folders.index')->with('success', 'Pasta criada com sucesso!');
    }

    public function edit(Folder $folder)
    {
        $folders = Folder::where('id', '!=', $folder->id)->get();
        $sectors = Sector::all();

        return view('folders.edit', compact('folder', 'folders', 'sectors'));
    }

    public function update(Request $request, Folder $folder)
    {
        $validated = $request->validate([
            'name' => 'required|unique:folders,name,' . $folder->id,
            'description' => 'nullable',
            'status' => 'required|boolean',
            'parent_id' => 'nullable|exists:folders,id',
            'sectors' => 'nullable|array',
            'sectors.*' => 'exists:sector,id',
        ]);

        $folder->update($validated);

        // atualizar setores
        $folder->sectors()->sync($request->sectors ?? []);

        return redirect()->route('folders.index')->with('success', 'Pasta atualizada com sucesso!');
    }

    public function destroy(Folder $folder)
    {
        $folder->delete();
        return redirect()->route('folders.index')->with('success', 'Pasta removida com sucesso!');
    }

    public function sectorFiles(Folder $folder, Sector $sector)
{
    $archives = $sector->archives()
        ->whereHas('folders', function ($query) use ($folder) {
            $query->where('folders.id', $folder->id);
        })
        ->paginate(10);

    return view('folders.sector-files', compact('folder', 'sector', 'archives'));
    }
    


}
