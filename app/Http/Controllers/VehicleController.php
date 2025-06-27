<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
{
    $vehicles = Vehicle::query();

    if ($request->has('search')) {
        $vehicles->where(function ($query) use ($request) {
            $query->where('plate', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->has('status') && $request->status !== '') {
        $vehicles->where('status', $request->status);
    }

    if ($request->has('brand') && $request->brand !== '') {
        $vehicles->where('brand', $request->brand);
    }

    $vehicles = $vehicles->paginate(10)->appends($request->query());

    // Pega lista única de marcas cadastradas
    $brands = Vehicle::select('brand')->distinct()->pluck('brand');

    return view('vehicles.index', compact('vehicles', 'brands'));
}


    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate' => 'required|string|max:10|unique:vehicles,plate',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'current_km' => 'required|integer|min:0',
            'observations' => 'nullable|string',
        ]);

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')->with('success', 'Veículo cadastrado com sucesso.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate' => 'required|string|max:10|unique:vehicles,plate,' . $vehicle->id,
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'current_km' => 'required|integer|min:0',
            'observations' => 'nullable|string',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')->with('success', 'Veículo atualizado com sucesso.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Veículo excluído com sucesso.');
    }
}
