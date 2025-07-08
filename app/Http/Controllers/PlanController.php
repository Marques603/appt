<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Gate;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('view', Menu::find(4))) {
            return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
        }

        $query = Plan::with(['users', 'responsibleUsers']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $plans = $query->orderBy('name', 'asc')->paginate(10);

        return view('plans.index', compact('plans'));
    }

    public function create()
    {
        Gate::authorize('create', \App\Models\Archive::class);

        $users = User::all();
        return view('plans.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $validated['status'] = 0;

        $plan = Plan::create($validated);
        $plan->users()->sync($request->users ?? []);
        $plan->responsibleUsers()->sync($request->responsible_users ?? []);

        return redirect()->route('plans.index')->with('success', 'Plano criado com sucesso.');
    }

    public function edit(Plan $plan)
    {

        Gate::authorize('edit', \App\Models\Archive::class);

        $users = User::all();
        $plan->load(['users', 'responsibleUsers']);
        return view('plans.edit', compact('plan', 'users'));
    }

    // Não usado caso seus formulários sejam separados
    public function update(Request $request, Plan $plan)
    {
        abort(404);
    }

    public function destroy(Plan $plan)
    {
        $plan->users()->detach();
        $plan->responsibleUsers()->detach();
        $plan->delete();

        return redirect()->route('plans.index')->with('success', 'Plano excluído com sucesso.');
    }

    public function updateDetails(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $plan->update($validated);

        return redirect()->back()->with('success', 'Detalhes do plano atualizados!');
    }

    public function updateStatus(Request $request, Plan $plan)
    {
        $plan->status = $request->has('status') ? 1 : 0;
        $plan->save();

        return redirect()->back()->with('success', 'Status atualizado!');
    }

    public function updateUsers(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        $plan->users()->sync($validated['users'] ?? []);

        return redirect()->back()->with('success', 'Usuários vinculados atualizados!');
    }

    public function updateResponsibles(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'responsible_users' => 'nullable|array',
            'responsible_users.*' => 'exists:users,id',
        ]);

        $plan->responsibleUsers()->sync($validated['responsible_users'] ?? []);

        return redirect()->back()->with('success', 'Responsáveis atualizados com sucesso.');
    }

    public function showArchives(Plan $plan)
    {
        if (!$plan->status) {
            return redirect()->route('folders.index')->with('error', 'Este plano está inativo e não pode ser acessado.');
        }

        $user = auth()->user();

        $canAccessPlanByUser = $user->plans->contains($plan);

        if (!$canAccessPlanByUser) {
            abort(403, 'Você não tem permissão para acessar este plano.');
        }

        $archives = $plan->archives()->where('status', true)->get();

        return view('plans.archives', compact('plan', 'archives'));
    }
}
