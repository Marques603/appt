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

        $positions = Position::with(['users', 'parent'])
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
        $positions = Position::all();

        return view('position.create', compact('users', 'positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            
        ]);

        $position = Position::create([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $request->input('status', false),
        ]);

        if ($request->has('users')) {
            $position->users()->sync($request->users);
        }

        return redirect()->route('position.index')->with('success', 'Cargo criado com sucesso!');
    }

    public function edit(Position $position)
    {
        $users = User::all();
        $positions = Position::where('id', '!=', $position->id)->get();

        $position->load('users', 'parent');

        return view('position.edit', compact('position', 'users', 'positions'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:position,id|not_in:' . $position->id,
            'status' => 'required|boolean',
            'level' => 'required|integer|min:1|max:100',
            'is_leader' => 'required|boolean',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        $position->update($request->only(['name', 'description', 'parent_id', 'status', 'level', 'is_leader']));

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

        $position->users()->sync($validated['users'] ?? []);

        return redirect()->route('position.edit', $position)->with('success', 'Usuários vinculados com sucesso.');
    }

    public function updateStatus(Request $request, Position $position)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $position->status = $request->input('status');
        $position->save();

        return redirect()->route('position.edit', $position)->with('success', 'Status atualizado com sucesso.');
    }

    public function updateDetails(Request $request, Position $position)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:position,id|not_in:' . $position->id,
            'level' => 'required|integer|min:1|max:100',
            'is_leader' => 'required|boolean',
        ]);

        $position->update($validated);

        return redirect()->route('position.edit', $position)->with('success', 'Detalhes atualizados com sucesso.');
    }
    public function tree()
{
    $positions = Position::with('users')
        ->orderBy('parent_id')
        ->orderBy('name')
        ->get();

    $data = $positions->map(function ($item) {
        return [
            'id' => $item->id,
            'pid' => $item->parent_id ? (int)$item->parent_id : null,
            'name' => $item->name,
            'title' => $item->description ?? '',
            'users' => $item->users->pluck('name')->toArray(), // pega os nomes dos usuários responsáveis
        ];
    });

    return view('position.tree', [
        'nodes' => $data->toJson()
    ]);
}


}