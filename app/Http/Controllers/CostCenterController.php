<?php

namespace App\Http\Controllers;

use App\Models\CostCenter;
use App\Models\Sector;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Gate;

class CostCenterController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('view', Menu::find(3))) {
            return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
        }

        $search = $request->input('search');

        $costCenters = CostCenter::with('sectors')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('cost_center.index', compact('costCenters', 'search'));
    }


    public function create()
    {
        $sectors = Sector::all();
        return view('cost_center.create', compact('sectors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            
        ]);

        // força status como 0 (inativo) caso não tenha sido enviado ou seja nulo
    $validated['status'] = $validated['status'] ?? 0;

        $costCenter = CostCenter::create($validated);
        $costCenter->sectors()->sync($request->sectors ?? []);

        return redirect()->route('cost_center.index')->with('success', 'Centro de Custo criado com sucesso.');
    }

    public function edit(CostCenter $costCenter)
    {
        $sectors = Sector::all();
        $costCenter->load('sectors');
        return view('cost_center.edit', compact('costCenter', 'sectors'));
    }

    public function update(Request $request, CostCenter $costCenter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'status' => 'required|boolean',
            'sectors' => 'array|nullable',
        ]);

        $costCenter->update($validated);
        $costCenter->sectors()->sync($request->sectors ?? []);

        return redirect()->route('cost_center.index')->with('success', 'Centro de Custo atualizado com sucesso.');
    }

    public function destroy(CostCenter $costCenter)
    {
        $costCenter->sectors()->detach();
        $costCenter->delete();

        return redirect()->route('cost_center.index')->with('success', 'Centro de Custo excluído com sucesso.');
    }public function updateInfo(Request $request, CostCenter $costCenter)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'nullable|string|max:255',
    ]);

    $costCenter->update($validated);
    return back()->with('success', 'Informações atualizadas com sucesso.');
}

public function updateStatus(Request $request, CostCenter $costCenter)
{
    $validated = $request->validate([
        'status' => 'required|boolean',
    ]);

    $costCenter->update($validated);
    return back()->with('success', 'Status atualizado com sucesso.');
}

public function updateSectors(Request $request, CostCenter $costCenter)
{
    $validated = $request->validate([
        'sectors' => 'array|nullable',
    ]);

    $costCenter->sectors()->sync($request->sectors ?? []);
    return back()->with('success', 'Setores vinculados atualizados com sucesso.');
}

}
