<?php

namespace App\Http\Controllers;

use App\Models\Macro;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Gate;

class MacroController extends Controller
{
 public function index(Request $request)
{
    if (!Gate::allows('view', Menu::find(2))) {
        return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
    }

    $search = $request->input('search');

    $macros = Macro::with('responsibleUsers')
        ->withCount('documents')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        })
        ->paginate(10);

    return view('macro.index', compact('macros', 'search'));
}



    public function create()
    {
        $users = User::all();
        return view('macro.create', compact('users'));
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

        $macro = Macro::create($request->only(['name', 'description', 'status']));

        if ($request->has('responsible_users')) {
            $macro->responsibleUsers()->sync($request->responsible_users);
        }

        return redirect()->route('macro.index')->with('success', 'Macro criada com sucesso.');
    }

    public function edit(Macro $macro)
    {
        $users = User::all();
        $macro->load('responsibleUsers');

        return view('macro.edit', compact('macro', 'users'));
    }

    public function update(Request $request, Macro $macro)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            //'status' => 'required|boolean',
            'responsible_users' => 'array|nullable',
            'responsible_users.*' => 'exists:users,id',
        ]);

        $macro->update($request->only(['name', 'description', 'status']));

        if ($request->has('responsible_users')) {
            $macro->responsibleUsers()->sync($request->responsible_users);
        }

        return redirect()->route('macro.index')->with('success', 'Macro atualizada com sucesso.');
    }

    public function destroy(Macro $macro)
    {
        $macro->delete();
        return redirect()->route('macro.index')->with('success', 'Macro removida com sucesso.');
    }

    public function restore($id)
    {
        $macro = Macro::withTrashed()->findOrFail($id);
        $macro->restore();
        return redirect()->route('macro.index')->with('success', 'Macro restaurada.');
    }
    public function updateStatus(Request $request, Macro $macro)
    {
    $request->validate(['status' => 'required|in:0,1']);
    $macro->update(['status' => $request->status]);

    return redirect()->route('macro.edit', $macro)->with('success', 'Status atualizado com sucesso.');
    }

    public function updateResponsibles(Request $request, Macro $macro)
    {
    $validated = $request->validate([
        'responsible_users' => 'nullable|array',
        'responsible_users.*' => 'exists:users,id',
    ]);

    $macro->responsibleUsers()->sync($validated['responsible_users'] ?? []);

    return redirect()->route('macro.edit', $macro)->with('success', 'Responsáveis atualizados com sucesso.');
    }



}
