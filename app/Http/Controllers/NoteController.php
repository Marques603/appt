<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CostCenter; // Certifique-se de que o modelo CostCenter está importado
use App\Models\Note; // Certifique-se de que o modelo Note está importado
use Illuminate\Support\Facades\Gate;
use App\Models\Menu; // Certifique-se de que o modelo Menu está importado
use App\Policies\NotePolicy; // Certifique-se de que a policy NotePolicy está importada
use Illuminate\Support\Facades\Auth; // Para obter o usuário autenticado
use App\Models\User; // Para o modelo User, se necessário

use function Laravel\Prompts\note;

class NoteController extends Controller
{
public function index(Request $request)
{
    if (!Gate::allows('view', Menu::find(6))) {
        return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
    }

    $search = $request->input('search');
    $user = auth()->user();

    // Pega todas roles do user no módulo notas
    $userRoles = $user->roles()->where('module', 'notas')->pluck('name')->toArray();

    $notes = Note::query()
        // Filtro de busca
        ->when($search, function ($query, $search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('provider', 'like', "%{$search}%")
                         ->orWhere('description', 'like', "%{$search}%");
            });
        })
        // Filtro por visualização conforme roles
        ->when(
            collect($userRoles)->intersect(['admin', 'diretor'])->isNotEmpty(),
            fn($query) => $query, // admin/diretor vê tudo
            function ($query) use ($userRoles) {
                if (in_array('lancamentos', $userRoles)) {
                    $query->where('status', 'Aprovada pelo Diretor');
                } elseif (in_array('pagamentos', $userRoles)) {
                    $query->where('status', 'Lançada no Financeiro');
                } else {
                    $query->whereRaw('0 = 1'); // bloqueia acesso
                }
            }
        )
        ->orderByDesc('payday')
        ->paginate(10);

    return view('notes.index', compact('notes'));
}

public function create()
{   
    $costCenters = CostCenter::all(); // ou CostCenter::orderBy('name')->get();
    return view('notes.create', compact('costCenters'));
}
public function store(Request $request)
{
    $validated = $request->validate([
        'provider' => 'required|string|max:255',
       
        'description' => 'required|string|max:255',
        'valor' => 'required|numeric|min:0',
        'payday' => 'required|date',

        'cost_center_id' => 'required|exists:cost_center,id', // Certifique-se de que o ID do centro de custo é válido
    ]);

   Note::create([
    'provider' => $request->provider,
    'description' => $request->description,
    'valor' => $request->valor,
    'payday' => $request->payday,
    'note_number' => $request->note_number,
    'cost_center_id' => $request->cost_center_id,
    'created_by' => auth()->id(), // obrigatório na migration
]);

    return redirect()->route('notes.index')->with('success', 'Nota cadastrada e enviada para aprovação.');
}
public function show(Note $note)
{
    return view('notes.show', compact('note'));
}
public function edit(Note $note)
{   
     Gate::authorize('edit', Note::class);

    return view('notes.edit', compact('note'));
}

public function update(Request $request, Note $note)
{
    $validated = $request->validate([
        'provider' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'valor' => 'required|numeric|min:0',
        'payday' => 'required|date',
        'cost_center' => 'nullable|string|max:255',
    ]);

    $note->update($validated);

    return redirect()->route('notes.index')->with('success', 'Nota atualizada com sucesso.');
}
public function destroy(Note $note)
{
    $note->delete();

    return redirect()->route('notes.index')->with('success', 'Nota excluída com sucesso.');

}
public function aprovar(Note $note)
{
    if ($note->status !== 'Aguardando Aprovação') return back();
    $note->status = 'Aprovada pelo Diretor';
    $note->save();
    return back()->with('success', 'Nota aprovada!');
}

public function lancar(Note $note)
{
    if ($note->status !== 'Aprovada pelo Diretor') return back();
    $note->status = 'Lançada no Financeiro';
    $note->save();
    return back()->with('success', 'Nota lançada no financeiro!');
}

public function enviarPagamento(Note $note)
{
    if ($note->status !== 'Lançada no Financeiro') return back();
    $note->status = 'Enviada para Pagamento';
    $note->save();
    return back()->with('success', 'Nota enviada para pagamento!');
}

public function pagar(Note $note)
{
    if ($note->status !== 'Enviada para Pagamento') return back();
    $note->status = 'Paga';
    $note->save();
    return back()->with('success', 'Nota marcada como paga!');
}


}
