<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Gate;

class CategorieController extends Controller
{
   public function index(Request $request)
{
    if (!Gate::allows('view', Menu::find(2))) {
        return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
    }

    $categories = Categorie::with('responsibleUsers')
    ->paginate(10);
    return view('categorie.index', compact('categories'));
}


    public function create()
    {
        $users = User::all();
        return view('categorie.create', compact('users'));
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

        $categorie = Categorie::create($request->only(['name', 'description', 'status']));

        if ($request->has('responsible_users')) {
            $categorie->responsibleUsers()->sync($request->responsible_users);
        }

        return redirect()->route('categorie.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function edit(Categorie $categorie)
    {
        $users = User::all();
        $categorie->load('responsibleUsers');

        return view('categorie.edit', compact('categorie', 'users'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            //'status' => 'required|boolean',
            'responsible_users' => 'array|nullable',
            'responsible_users.*' => 'exists:users,id',
        ]);

        $categorie->update($request->only(['name', 'description', 'status']));

        if ($request->has('responsible_users')) {
            $categorie->responsibleUsers()->sync($request->responsible_users);
        }

        return redirect()->route('categorie.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();
        return redirect()->route('categorie.index')->with('success', 'Macro removida com sucesso.');
    }

    public function restore($id)
    {
        $categorie = Categorie::withTrashed()->findOrFail($id);
        $categorie->restore();
        return redirect()->route('categorie.index')->with('success', 'Macro restaurada.');
    }
    public function updateStatus(Request $request, Categorie $categorie)
    {
    $request->validate(['status' => 'required|in:0,1']);
    $categorie->update(['status' => $request->status]);

    return redirect()->route('categorie.edit', $categorie)->with('success', 'Status atualizado com sucesso.');
    }

    public function updateResponsibles(Request $request, Categorie $categorie)
    {
    $validated = $request->validate([
        'responsible_users' => 'nullable|array',
        'responsible_users.*' => 'exists:users,id',
    ]);

    $categorie->responsibleUsers()->sync($validated['responsible_users'] ?? []);

    return redirect()->route('categorie.edit', $categorie)->with('success', 'Responsáveis atualizados com sucesso.');
    }



}
