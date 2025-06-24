<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Gate;

class PositionController extends Controller
{
    
    public function index(Request $request)
    {
        if (!Gate::allows('view', Menu::find(3))) {
            return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
        }

        $search = $request->input('search');

        $positions = Position::with('users')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('position.index', compact('positions', 'search'));
    }


    public function create()
    {
        $users = User::all();
        return view('position.create', compact('users'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ]);

    $position = Position::create([
    'name' => $request->input('name'),
    'description' => $request->input('description'),
    'status' => 0, // Sempre inativo ao criar
]);


    // Relacionar usuários (se houver)
    if ($request->has('users')) {
        $position->users()->sync($request->users);
    }

    return redirect()->route('position.index')->with('success', 'Cargo criado com sucesso!');
    }


    public function edit(Position $position)
    {
    $users = User::all();
    $position->load('users');
    return view('position.edit', compact('position', 'users'));
    }



    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required|boolean',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        $position->update($request->only(['name','description','status']));

        // Atualizar relacionamento com usuários
        $position->users()->sync($request->users ?? []);

        return redirect()->route('position.index')->with('success', 'Cargo atualizado com sucesso!');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('position.index')->with('success', 'Cargo excluído com sucesso!');
    }
    public function updateUsers(Request $request, Position $position)
    {
    $validated = $request->validate([
        'users' => ['nullable', 'array'],
        'users.*' => ['exists:users,id'],
    ]);

    // Se nenhum usuário for enviado, desvincula todos
    $position->users()->sync($validated['users'] ?? []);

    return redirect()->route('position.edit', $position)->with('success', 'Usuários vinculados com sucesso.');
    }public function updateStatus(Request $request, Position $position)
    {
    $request->validate([
        'status' => 'required|in:0,1',
    ]);

    $position->status = $request->input('status');
    $position->save();

    return redirect()->route('position.edit', $position)->with('success', 'Status atualizado com sucesso.');
    }
    public function updateDetails(Request $request, Position $position)
    {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ]);

    $position->update($validated);

    return redirect()->route('position.edit', $position)->with('success', 'Detalhes atualizados com sucesso.');
}




}
