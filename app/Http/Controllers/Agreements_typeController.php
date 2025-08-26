<?php

namespace App\Http\Controllers;

use App\Models\Agreements_type;
use Illuminate\Http\Request;

class Agreements_typeController extends Controller
{
    public function index()
    {
        $types = Agreements_type::orderBy('type')->paginate(10);
        return view('agreements_type.index', compact('types'));
    }

    public function create()
    {
        return view('agreements_type.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Agreements_type::create($request->only('type', 'description'));

        return redirect()->route('agreements_type.index')->with('success', 'Tipo de convênio criado com sucesso!');
    }

    public function edit($id)
    {
        $type = Agreements_type::findOrFail($id);
        return view('agreements_type.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $type = Agreements_type::findOrFail($id);

        $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $type->update($request->only('type', 'description'));

        return redirect()->route('agreements_type.index')->with('success', 'Tipo de convênio atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $type = Agreements_type::findOrFail($id);
        $type->delete();

        return redirect()->route('agreements_type.index')->with('success', 'Tipo de convênio excluído com sucesso!');
    }
}
