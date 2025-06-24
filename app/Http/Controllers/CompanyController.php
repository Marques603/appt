<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
{
    if (!Gate::allows('view', Menu::find(1))) {
        return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
    }

    $query = Company::with('users');

    if (request('search')) {
        $search = request('search');
        $query->where('name', 'like', "%{$search}%");
    }

    $companies = $query->orderBy('name', 'asc')->paginate(10);

    return view('company.index', compact('companies'));
}


    public function create()
    {
        $users = User::all();
        return view('company.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'corporate_name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:company,cnpj',
            //'status' => 'required|boolean',
            //'users' => 'array|nullable',
        ]);
 
          // Força status como 0 (inativo) sempre que criar um setor
        $validated['status'] = 0;

        $company = Company::create($validated);
        if ($request->has('users')) {
            $company->users()->sync($request->users);
        }

        return redirect()->route('company.index')->with('success', 'Empresa criada com sucesso.');
    }

    public function show(Company $company)
    {
        $company->load('users');
        return view('company.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $users = User::all();
        $company->load('users');
        return view('company.edit', compact('company', 'users'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'corporate_name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:company,cnpj,' . $company->id,
            'status' => 'required|boolean',
            'users' => 'array|nullable',
        ]);

        $company->update($validated);
        $company->users()->sync($request->users ?? []);

        return redirect()->route('company.index')->with('success', 'Empresa atualizada com sucesso.');
    }

    public function destroy(Company $company)
    {
        $company->users()->detach();
        $company->delete();

        return redirect()->route('company.index')->with('success', 'Empresa excluída com sucesso.');
    }
    public function updateDetails(Request $request, Company $company) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',  // como você usa description no form, precisa validar também
        'corporate_name' => 'nullable|string|max:255', // Se quiser incluir
        'cnpj' => 'nullable|string|max:18',            // Se quiser incluir
    ]);

    $company->update($validated);

    return back()->with('success', 'Informações atualizadas com sucesso.');
}



public function updateStatus(Request $request, Company $company) {
    $request->validate(['status' => 'required|in:0,1']);
    $company->update(['status' => $request->status]);
    return back()->with('success', 'Status atualizado com sucesso.');
}

public function updateUsers(Request $request, Company $company) {
    $request->validate(['users' => 'nullable|array']);
    $company->users()->sync($request->users ?? []);
    return back()->with('success', 'Usuários vinculados atualizados.');
}
public function updateResponsibles(Request $request, Company $company)
{
    $validated = $request->validate([
        'users' => 'nullable|array',
        'users.*' => 'exists:users,id',
    ]);

    $company->responsibles()->sync($request->users ?? []);

    return redirect()->route('company.edit', $company)->with('success', 'Responsáveis atualizados com sucesso.');
}


}
