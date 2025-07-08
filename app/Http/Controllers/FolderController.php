<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Plan;
use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FolderController extends Controller
{
    public function index(Request $request)
{
    if (!\Gate::allows('view', \App\Models\Menu::find(4))) {
        return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
    }

    $search = $request->query('search');
    $parentId = $request->query('parent_id');

    if ($search) {
        // Busca por nome em qualquer nível
        $folders = Folder::where('name', 'like', '%' . $search . '%')->paginate(24);
        $parentFolder = null; // não tem contexto pai
    } else {
        // Hierarquia normal
        $folders = Folder::where('parent_id', $parentId)->paginate(24);
        $parentFolder = $parentId ? Folder::with('parent')->findOrFail($parentId) : null;
    }

    // Se não encontrou subpastas mas está num nível final, mostra tela dos planos
    if (!$search && $folders->isEmpty() && $parentFolder) {
        $user = auth()->user();
        $folderPlanIds = $parentFolder->plans->pluck('id')->toArray();
        $plans = $user->plans()->whereIn('plans.id', $folderPlanIds)->where('status', 1)->get();
        return view('folders.last-level', compact('parentFolder', 'plans'));
    }

    return view('folders.index', compact('folders', 'parentFolder', 'search'));
}


    public function create(Request $request)
    {
        \Gate::authorize('create', \App\Models\Archive::class);

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
        \Gate::authorize('edit', \App\Models\Archive::class);

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

        Gate::authorize('edit', \App\Models\Archive::class);
         
        if (!\Gate::allows('view', \App\Models\Menu::find(4))) {
        return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
    }
        $archives = $plan->archives()
            ->whereHas('folders', function ($query) use ($folder) {
                $query->where('folders.id', $folder->id);
            })
            ->paginate(10);

        return view('folders.plan-files', compact('folder', 'plan', 'archives'));
    }
}
