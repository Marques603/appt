<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Folder;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    public function create(Folder $folder, Plan $plan)
{
    return view('archives.create', compact('folder', 'plan'));
}

public function store(Request $request, Folder $folder, Plan $plan)
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

    // Vincular archive à pasta e plano
    $archive->folders()->syncWithoutDetaching([$folder->id]);
    $archive->plans()->syncWithoutDetaching([$plan->id]);

    return redirect()->route('folders.planFiles', ['folder' => $folder->id, 'plan' => $plan->id])
                     ->with('success', 'Arquivo criado e vinculado com sucesso!');

    }
    public function index(Request $request)
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
}public function show(Archive $archive)
{
    return view('archives.show', compact('archive'));
}

public function download(Archive $archive)
    {
        // log removido pois não usa Spatie
        return Storage::download($archive->file_path);
    }
    public function destroy($id)
    {
        $archive = Archive::findOrFail($id);

        // Captura folder e sector para redirecionar depois
        $folder = $archive->folders()->first();  // supondo relação belongsToMany
        $sector = $archive->sector;              // supondo relação belongsTo

        $archive->delete();

        return redirect()->route('folders.index')
    ->with('success', 'Arquivo excluído com sucesso.');

    }



}
