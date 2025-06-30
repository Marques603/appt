<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\User;
Use App\Models\Sector;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Gate;

class FolderController extends Controller
{
   public function index(Request $request)
{
    if (!Gate::allows('view', Menu::find(2))) {
        return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
    }

    $folders = Folder::with('responsibleUsers','sectors')
    ->withCount('archives')
    ->paginate(10);


    return view('folder.index', compact('folders'));
}


    public function create()
    {
        $users = User::all();
        return view('folder.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            //'status' => 'required|boolean',
            //'responsible_users' => 'array|nullable',
            //'responsible_users.*' => 'exists:users,id',
        ]);

        $folder = Folder::create($request->only(['name', 'description', 'status']));

        if ($request->has('responsible_users')) {
            $folder->responsibleUsers()->sync($request->responsible_users);
        }

        return redirect()->route('folder.index')->with('success', 'Macro criada com sucesso.');
    }

    public function edit(Folder $folder)
    {
        $users = User::all();
        $folder->load('responsibleUsers');

        return view('folder.edit', compact('folder', 'users'));
    }

    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            //'status' => 'required|boolean',
            'responsible_users' => 'array|nullable',
            'responsible_users.*' => 'exists:users,id',
        ]);

        $folder->update($request->only(['name', 'description', 'status']));

        if ($request->has('responsible_users')) {
            $folder->responsibleUsers()->sync($request->responsible_users);
        }

        return redirect()->route('folder.index')->with('success', 'Macro atualizada com sucesso.');
    }

    public function destroy(Folder $folder)
    {
        $folder->delete();
        return redirect()->route('folder.index')->with('success', 'Macro removida com sucesso.');
    }

    public function restore($id)
    {
        $folder = Folder::withTrashed()->findOrFail($id);
        $folder->restore();
        return redirect()->route('folder.index')->with('success', 'Macro restaurada.');
    }
    public function updateStatus(Request $request, Folder $folder)
    {
    $request->validate(['status' => 'required|in:0,1']);
    $folder->update(['status' => $request->status]);

    return redirect()->route('folder.edit', $folder)->with('success', 'Status atualizado com sucesso.');
    }

    public function updateResponsibles(Request $request, Folder $folder)
    {
    $validated = $request->validate([
        'responsible_users' => 'nullable|array',
        'responsible_users.*' => 'exists:users,id',
    ]);

    $folder->responsibleUsers()->sync($validated['responsible_users'] ?? []);

    return redirect()->route('folder.edit', $folder)->with('success', 'Responsáveis atualizados com sucesso.');
    }
        public function updateSectors(Request $request, Folder $folder)
{
    $request->validate([
        'sectors' => 'nullable|array',
    ]);

    $folder->sectors()->sync($request->sectors ?? []);

    return redirect()->back()->with('success', 'Setores vinculados atualizados com sucesso.');
}




}
