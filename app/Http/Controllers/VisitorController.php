<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Menu;
use LaravelLang\Lang\Plugins\Fortify\V1;

class VisitorController extends Controller
{
public function index()
{
    $visitors = Visitor::whereNull('updated_at')
        ->latest()
        ->paginate(10);

    return view('visitors.index', compact('visitors'));
}

    public function create()
    {
        return view('visitors.create');
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'document' => 'required|string|max:20',
        'typevisitor' => 'required|string',
        'company' => 'nullable|string|max:255',
        'service' => 'nullable|string|max:255',
        'parking' => 'nullable|in:Sim,Não',
        'vehicle_plate' => 'nullable|string|max:20',
        'vehicle_model' => 'nullable|string|max:50',
    ]);


    
    $visitor = new Visitor($validated);
    $visitor->created_by = auth()->id();
    $visitor->timestamps = false; // <-- Desativa timestamps temporariamente
    $visitor->created_at = now(); // <-- Define manualmente o created_at
    $visitor->save();

    return redirect()->route('visitors.index')->with('success', 'Visitante cadastrado com sucesso.');
}

    public function edit(Visitor $visitor)
    {
        return view('visitors.edit', compact('visitor'));
    }

    public function update(Request $request, Visitor $visitor)
    {
        $validated = $request->validate([
            'update_at' => 'nullable|date_format:Y-m-d H:i:s',
            'status'      => 0,
        ]);

        $visitor->update($validated);

        return redirect()->route('visitors.index')->with('success', 'Visitante atualizado com sucesso.');
    }

    public function destroy(Visitor $visitor)
    {
        $visitor->delete();
        return redirect()->route('visitors.index')->with('success', 'Visitante excluído com sucesso.');
    }public function updatesaidastatus(Request $request, Visitor $visitor)
{
    // Validação opcional, remova se não usar
    // $request->validate([
    //     'update_time' => 'required|date_format:Y-m-d H:i:s',
    // ]);

    $visitor->touch(); // <- atualiza o updated_at
    $validated['status'] = 0;

    $visitor->update($validated);
    return redirect()->route('visitors.index')->with('success', 'Saída registrada com sucesso.');
}
public function index2()
{
    if (!Gate::allows('view', Menu::find(5))) {
        return redirect()->back()->with('success', 'Este menu não está liberado para o seu perfil.');
    }

    $visitors = Visitor::orderByDesc('id')->paginate(10);

    return view('visitors.index2', compact('visitors'));
}




}

