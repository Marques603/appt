<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleMovementController extends Controller
{
    public function create(Vehicle $vehicle)
    {
        return view('vehicle_movements.create', compact('vehicle'));
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // usuário que levou o veículo
            'destination' => 'required|string|max:255',
            'observations' => 'nullable|string',
        ]);

        // Criar movimentação de saída
        $movement = new VehicleMovement([
            'user_id' => $validated['user_id'],
            'gatekeeper_id' => Auth::id(),
            'departure_km' => $vehicle->current_km,
            'departure_time' => now(),
            'destination' => $validated['destination'],
            'observations' => $validated['observations'] ?? null,
        ]);

        $vehicle->movements()->save($movement);
        $vehicle->update(['status' => Vehicle::STATUS_EM_TRANSITO]);

        return redirect()->route('vehicles.index')->with('success', 'Saída registrada com sucesso.');
    }

    public function edit(VehicleMovement $movement)
    {
        return view('vehicle_movements.return', compact('movement'));
    }

    public function update(Request $request, VehicleMovement $movement)
    {
        $validated = $request->validate([
            'return_km' => 'required|integer|min:' . $movement->departure_km,
            'observations' => 'nullable|string',
        ]);

        $movement->update([
            'return_km' => $validated['return_km'],
            'return_time' => now(),
            'observations' => $validated['observations'] ?? $movement->observations,
        ]);

        // Atualiza o veículo com o novo KM
        $movement->vehicle->update([
            'current_km' => $validated['return_km'],
            'status' => Vehicle::STATUS_DISPONIVEL,
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Retorno registrado com sucesso.');
    }
}
