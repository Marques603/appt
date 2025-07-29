<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    // Listar roles
    public function index()
    {

    if (auth()->id() !== 5) {
    return redirect()->route('dashboard')->with('status', 'Acesso restrito. Apenas o administrador pode acessar este menu.');
    }   
        $roles = Role::paginate(10);
        return view('role.index', compact('roles'));
    }

    // Mostrar formulário de criação
    public function create()
    {
        return view('role.create');
    }

    // Salvar novo menu
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'module' => 'required|string|max:255',
        ]);

        Role::create([
            'name' => $request->name,
            'module' => $request->module,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role criado com sucesso!');
    }

    // Mostrar formulário de edição
    public function edit(Role $role)
    {
        return view('role.edit', compact('role'));
    }

    // Atualizar role existente
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'module' => 'required|string|max:255',
        ]);

        $role->update([
            'name' => $request->name,
            'module' => $request->module,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role atualizado com sucesso!');
    }

    // Deletar menu
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role excluído com sucesso!');
    }
}
