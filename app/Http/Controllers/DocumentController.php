<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Macro;
use App\Models\Sector;
use App\Models\DocumentApproval;
use App\Models\Menu;
use App\Models\DocumentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Index e outros métodos (create, store, edit) podem ficar iguais
public function index(Request $request)
{
    if (!Gate::allows('view', Menu::find(2))) {
        return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
    }

    $user = auth()->user();
    $sectorIds = $user->sectors->pluck('id');
    $isQuality = $sectorIds->contains(function ($id) {
        return in_array($id, [4, 16]);
    });

    $documents = Document::query()
        ->when(!$isQuality, function ($query) use ($sectorIds) {
            $query->where('status', 1)
                  ->whereHas('sectors', function ($q) use ($sectorIds) {
                      $q->whereIn('sector_id', $sectorIds);
                  });
        })
        ->when($request->search, function ($query, $search) {
            return $query->where('code', 'like', "%{$search}%");
        })
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status', $request->status);
        })
        ->when($request->filled('sector'), function ($query) use ($request) {
            $query->whereHas('sectors', function ($q) use ($request) {
                $q->where('sector_id', $request->sector);
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(12)
        ->withQueryString(); // mantém os filtros na paginação

        $sectors = \App\Models\Sector::all();

    return view('documents.index', compact('documents','sectors'));
}



    public function create()
{
    $macros = Macro::all();
    $user = auth()->user();
    $userSectors = $user->sectors;
    $sectorIds = $userSectors->pluck('id')->toArray();

    if (array_intersect([1, 2], $sectorIds)) {
    $userSectors = Sector::all();
}

    $showSectorSelect = $userSectors->count() > 1;

    return view('documents.create', compact('macros', 'userSectors', 'showSectorSelect'));
}


    public function store(Request $request)
{
    $request->validate([
        'code' => 'required|string',
        'file' => 'required|file',
        'macros' => 'nullable|array',
        'sector_id' => 'nullable|exists:sector,id', // novo campo opcional
    ]);

    $user = auth()->user();

    $filePath = $request->file('file')->store('documents', 'public');

    $document = Document::create([
        'code' => $request->code,
        'description' => $request->description,
        'user_upload' => $user->id,
        'revision' => $request->revision ?? 1,
        'file_path' => $filePath,
        'file_type' => $request->file('file')->getClientOriginalExtension(),
        'status' => 0,
    ]);

    $document->macros()->sync($request->macros ?? []);

   // Verifica se veio algum setor selecionado
if ($request->filled('sector_ids')) {
    // `sector_ids` é um array, então sincroniza tudo
    $document->sectors()->sync($request->sector_ids);
} else {
    // Se não veio setor selecionado, verifica se usuário tem 1 setor só e vincula
    $userSectorIds = $user->sectors->pluck('id')->toArray();
    if (count($userSectorIds) === 1) {
        $document->sectors()->sync($userSectorIds);
    }
}


    return redirect()->route('documents.index')->with('success', 'Documento criado com sucesso.');
}



    public function edit(Document $document)
    {
        Gate::authorize('edit', $document);

        $macros = Macro::all();
        $sectors = Sector::all();

        return view('documents.edit', compact('document', 'macros', 'sectors'));
    }

    // Atualiza apenas o código, descrição e revisão
    public function updateCode(Request $request, Document $document)
    {
        $request->validate([
            'code' => 'required|string',
            'description' => 'nullable|string',
            'revision' => 'nullable|string',
        ]);

        $document->code = $request->code;
        $document->description = $request->description;
        $document->revision = $request->revision;
        $document->save();

        return redirect()->back()->with('success', 'Código do documento atualizado com sucesso.');
    }

    // Atualiza apenas o arquivo
public function updateFile(Request $request, Document $document)
{
    $request->validate([
        'file' => 'required|file',
    ]);

    // Soft delete do documento atual
    $document->delete();

    // Armazena novo arquivo
    $filePath = $request->file('file')->store('documents', 'public');

    // Cria novo documento com base no anterior
    $newDocument = Document::create([
        'code' => $document->code,
        'description' => $document->description,
        'user_upload' => auth()->id(),
        'revision' => $document->revision + 1,
        'file_path' => $filePath,
        'file_type' => $request->file('file')->getClientOriginalExtension(),
        'status' => 0, // Pode começar como inativo novamente
    ]);

    // Mantém os vínculos anteriores
    $newDocument->macros()->sync($document->macros->pluck('id'));
    $newDocument->sectors()->sync($document->sectors->pluck('id'));

    return redirect()->route('documents.edit', $newDocument->id)
                     ->with('success', 'Arquivo atualizado. Documento anterior arquivado e nova revisão criada.');
}

    // Atualiza as macros vinculadas
    public function updateMacros(Request $request, Document $document)
{
    $request->validate([
        'macros' => 'nullable|array',
    ]);

    $document->macros()->sync($request->macros ?? []);

    return redirect()->back()->with('success', 'Macros vinculadas atualizadas com sucesso.');
}

    // Atualiza os setores vinculados
    public function updateSectors(Request $request, Document $document)
{
    $request->validate([
        'sectors' => 'nullable|array',
    ]);

    $document->sectors()->sync($request->sectors ?? []);

    return redirect()->back()->with('success', 'Setores vinculados atualizados com sucesso.');
}

    // Métodos de aprovação (showApproveForm, approve, updateApprovalStatus) mantém iguais
    public function showApproveForm(Document $document)
{
    $approvals = $document->approvals()->with('user')->orderBy('created_at', 'desc')->get();

    return view('documents.approve', compact('document', 'approvals'));
}

public function storeApproval(Request $request, Document $document)
{
    $request->validate([
        'status' => 'required|in:0,1,2', // Apenas aprovar (1) ou reprovar (2)
        'comments' => 'nullable|string|max:1000',
    ]);

    DocumentApproval::create([
        'document_id' => $document->id,
        'user_id' => auth()->id(),
        'status' => $request->status,
        'comments' => $request->comments,
        'approved_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Aprovação registrada com sucesso.');
}

    public function approve($documentId)
    {
        $document = Document::findOrFail($documentId);

        if ($document->approvals()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('documents.index')->with('info', 'Você já aprovou este documento.');
        }

        DocumentApproval::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('documents.index')->with('success', 'Documento aprovado com sucesso.');
    }

    public function updateApprovalStatus(Request $request, $documentId)
{
    $request->validate([
        'status' => 'required|in:0,1,2',
        'comments' => 'nullable|string|max:1000',
    ]);

    $document = Document::findOrFail($documentId);

    $approval = $document->approvals()->where('user_id', auth()->id())->first();

    if ($approval) {
        // Atualiza histórico existente para o usuário
        $approval->update([
            'status' => $request->status,
            'comments' => $request->comments,
            'approved_at' => now(),
        ]);
    } else {
        // Cria novo histórico de aprovação para o usuário
        $document->approvals()->create([
            'user_id' => auth()->id(),
            'status' => $request->status,
            'comments' => $request->comments,
            'approved_at' => now(),
        ]);
    }

    return redirect()->back()->with('success', 'Status de aprovação atualizado.');
}

    public function updateStatus(Request $request, Document $document)
    {
    $document->status = $request->input('status', 0);
    $document->save();

    return redirect()->back()->with('success', 'Status do documento atualizado com sucesso.');
}public function destroy(Document $document)
{
    $document->delete(); // ou $document->forceDelete() se quiser deletar permanentemente

    return redirect()->route('documents.index')->with('success', 'Documento deletado com sucesso.');
}
public function logAndShow(Document $document)
{
    $user = auth()->user();

    // Salvar log
    \App\Models\DocumentLog::create([
        'document_id' => $document->id,
        'user_id' => $user->id,
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    // Redirecionar para o arquivo (exibir documento)
    return redirect()->away(asset('storage/' . $document->file_path));
}public function documentsapprove(Request $request)
{
    $documents = Document::where('status', 0) // Exemplo: pegando só documentos com status "Em aprovação"
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('documents.approve-index', compact('documents'));
}








}