<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agreements;
use App\Models\Agreements_type;

class AgreementsController extends Controller
{
public function index(Request $request)
{
    $query = Agreements::query();

    if ($search = $request->input('search')) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('contact', 'like', "%{$search}%");
    }

    $agreements = $query->orderBy('name')->paginate(10);

    return view('agreements.index', compact('agreements'));
}




    public function create()
    {
    $types = \App\Models\Agreements_type::all(); // ou o nome correto do model
    return view('agreements.create', compact('types'));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'contact' => 'required|string|max:255',
            'road_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'agreements_type_id' => 'required|exists:agreements_type,id', // Ensure the agreement type exists
        ]);

        Agreements::create($validated);

        return redirect()->route('agreements.index')->with('success', 'Convênio adicionado com sucesso!'); 
    }

    public function edit($id)
    {
        $agreement = Agreements::findOrFail($id);
        return view('agreements.edit', compact('agreement')); // Show form to edit an existing agreement
    }

public function update(Request $request, $id)
{
    $agreement = Agreements::findOrFail($id);

    // Validação dos dados recebidos
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'contact' => 'required|string|max:255',
        'road_name' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'number' => 'required|string|max:50',
        'agreements_type_id' => 'required|exists:agreements_type,id', // Certifique-se de que o tipo de convênio existe
    ]);

    // Atualiza o registro com os dados validados
    $agreement->update($validatedData);

    // Redireciona para o index com mensagem de sucesso
    return redirect()->route('agreements.index')->with('success', 'Convênio atualizado com sucesso!');
}


    public function destroy($id)
    {
        $agreement = Agreements::findOrFail($id);
        $agreement->delete();

        return redirect()->route('agreements.index')->with('success', 'Convênio excluído com sucesso!');
    }

    public function show($id)
    {
        $agreement = Agreements::findOrFail($id);
        return view('agreements.show', compact('agreement'));
    }
}
