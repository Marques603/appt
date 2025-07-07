<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Plan;
use App\Models\Archive;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        $parentId = $request->query('parent_id');

        $folders = Folder::where('parent_id', $parentId)->paginate(24);
        $parentFolder = $parentId ? Folder::with('parent')->findOrFail($parentId) : null;

        if ($folders->isEmpty() && $parentFolder) {
            // chegamos no último nível: mostrar planos vinculados
            $plans = $parentFolder->plans;
            return view('folders.last-level', compact('parentFolder', 'plans'));
        }

        return view('folders.index', compact('folders', 'parentFolder'));
    }

    public function create(Request $request)
    {
        $parentId = $request->query('parent_id');
        $folders = Folder::all(); // para escolher pasta pai, se quiser
        $plans = Plan::all(); // popular select planos

        return view('folders.create', compact('folders', 'parentId', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:folders,name',
            'description' => 'nullable',
            'status' => 'required|boolean',
            'parent_id' => 'nullable|exists:folders,id',
            'plans' => 'nullable|array',
            'plans.*' => 'exists:plans,id',
        ]);

        $folder = Folder::create($validated);

        // sincronizar planos
        $folder->plans()->sync($request->plans ?? []);

        return redirect()->route('folders.index')->with('success', 'Pasta criada com sucesso!');
    }

    public function edit(Folder $folder)
    {
        $folders = Folder::where('id', '!=', $folder->id)->get();
        $plans = Plan::all();

        return view('folders.edit', compact('folder', 'folders', 'plans'));
    }

    public function update(Request $request, Folder $folder)
    {
        $validated = $request->validate([
            'name' => 'required|unique:folders,name,' . $folder->id,
            'description' => 'nullable',
            'status' => 'required|boolean',
            'parent_id' => 'nullable|exists:folders,id',
            'plans' => 'nullable|array',
            'plans.*' => 'exists:plans,id',
        ]);

        $folder->update($validated);

        // atualizar planos
        $folder->plans()->sync($request->plans ?? []);

        return redirect()->route('folders.index')->with('success', 'Pasta atualizada com sucesso!');
    }

    public function destroy(Folder $folder)
{
    if ($folder->archives()->count() > 0) {
        return redirect()->route('folders.index')
            ->with('success', 'Não é possível excluir esta pasta, pois ela contém arquivos vinculados.');
    }

    if ($folder->children()->count() > 0) {
        return redirect()->route('folders.index')
            ->with('success', 'Não é possível excluir esta pasta, pois ela possui subpastas vinculadas.');
    }

    $folder->delete();

    return redirect()->route('folders.index')
        ->with('success', 'Pasta excluída com sucesso.');
}

    public function planFiles(Folder $folder, Plan $plan)
    {
        $archives = $plan->archives()
            ->whereHas('folders', function ($query) use ($folder) {
                $query->where('folders.id', $folder->id);
            })
            ->paginate(10);

        return view('folders.plan-files', compact('folder', 'plan', 'archives'));
    }
}
