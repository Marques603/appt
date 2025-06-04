<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Macro;
use App\Models\Sector;
use App\Models\ArchiveApproval;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArchiveController extends Controller
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
        return in_array($id, [1, 16]);
    });

    $archives = Archive::query()
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
        ->paginate(10)
        ->withQueryString(); // mantém os filtros na paginação

        $sectors = \App\Models\Sector::all();

    return view('archives.index', compact('archives','sectors'));
}



    public function create()
    {
        $macros = Macro::all();
        $sectors = Sector::all();
        return view('archives.create', compact('macros', 'sectors'));
    }

    public function store(Request $request)
{
    $request->validate([
        'code' => 'required|string',
        'file' => 'required|file',
        'macros' => 'nullable|array',   // Agora pode ser nulo ou vazio
        'sectors' => 'nullable|array',  // Agora pode ser nulo ou vazio
    ]);

    $userId = auth()->id();

    $filePath = $request->file('file')->store('archives', 'public');

    $archive = Archive::create([
        'code' => $request->code,
        'description' => $request->description,
        'user_upload' => $userId,
        'revision' => $request->revision ?? 1,
        'file_path' => $filePath,
        'file_type' => $request->file('file')->getClientOriginalExtension(),
        'status' => 0,
    ]);

    // Se vier vazio ou não vier, sincroniza com array vazio = desvincula tudo
    $archive->macros()->sync($request->macros ?? []);
    $archive->sectors()->sync($request->sectors ?? []);

    return redirect()->route('archives.index')->with('success', 'Documento criado com sucesso.');
}

    public function edit(Archive $archive)
    {
        $macros = Macro::all();
        $sectors = Sector::all();

        return view('archives.edit', compact('archive', 'macros', 'sectors'));
    }

    // Atualiza apenas o código, descrição e revisão
    public function updateCode(Request $request, Archive $archive)
    {
        $request->validate([
            'code' => 'required|string',
            'description' => 'nullable|string',
            'revision' => 'nullable|string',
        ]);

        $archive->code = $request->code;
        $archive->description = $request->description;
        $archive->revision = $request->revision;
        $archive->save();

        return redirect()->back()->with('success', 'Código do documento atualizado com sucesso.');
    }

    // Atualiza apenas o arquivo
public function updateFile(Request $request, Archive $archive)
{
    $request->validate([
        'file' => 'required|file',
    ]);

    // Soft delete do documento atual
    $archive->delete();

    // Armazena novo arquivo
    $filePath = $request->file('file')->store('archives', 'public');

    // Cria novo documento com base no anterior
    $newArchive = Archive::create([
        'code' => $archive->code,
        'description' => $archive->description,
        'user_upload' => auth()->id(),
        'revision' => $archive->revision + 1,
        'file_path' => $filePath,
        'file_type' => $request->file('file')->getClientOriginalExtension(),
        'status' => 0, // Pode começar como inativo novamente
    ]);

    // Mantém os vínculos anteriores
    $newArchive->macros()->sync($archive->macros->pluck('id'));
    $newArchive->sectors()->sync($archive->sectors->pluck('id'));

    return redirect()->route('archives.edit', $newArchive->id)
                     ->with('success', 'Arquivo atualizado. Documento anterior arquivado e nova revisão criada.');
}



    // Atualiza as macros vinculadas
    public function updateMacros(Request $request, Archive $archive)
{
    $request->validate([
        'macros' => 'nullable|array',
    ]);

    $archive->macros()->sync($request->macros ?? []);

    return redirect()->back()->with('success', 'Macros vinculadas atualizadas com sucesso.');
}

    // Atualiza os setores vinculados
    public function updateSectors(Request $request, Archive $archive)
{
    $request->validate([
        'sectors' => 'nullable|array',
    ]);

    $archive->sectors()->sync($request->sectors ?? []);

    return redirect()->back()->with('success', 'Setores vinculados atualizados com sucesso.');
}

    // Métodos de aprovação (showApproveForm, approve, updateApprovalStatus) mantém iguais
    public function showApproveForm($archiveId)
    {
        $archive = Archive::with('approvals.user')->findOrFail($archiveId);
        return view('archives.approve', compact('archive'));
    }

    public function approve($archiveId)
    {
        $archive = Archive::findOrFail($archiveId);

        if ($archive->approvals()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('archives.index')->with('info', 'Você já aprovou este documento.');
        }

        

        ArchiveApproval::create([
            'archive_id' => $archive->id,
            'user_id' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('archives.index')->with('success', 'Documento aprovado com sucesso.');
    }

    public function updateApprovalStatus(Request $request, $archiveId)
    {
        $archive = Archive::findOrFail($archiveId);

        $approval = ArchiveApproval::where('archive_id', $archiveId)
                                    ->where('user_id', auth()->id())
                                    ->first();

        if (!$approval) {
            $approval = new ArchiveApproval();
            $approval->archive_id = $archiveId;
            $approval->user_id = auth()->id();
        }

        $approval->status = $request->status ?? 0;
        $approval->approved_at = now();
        $approval->comments = $request->comments ?? null;
        $approval->save();

        return redirect()->route('archives.index')->with('success', 'Status de aprovação atualizado com sucesso.');
    }
    public function updateStatus(Request $request, Archive $archive)
    {
    $archive->status = $request->input('status', 0);
    $archive->save();

    return redirect()->back()->with('success', 'Status do documento atualizado com sucesso.');
}public function destroy(Archive $archive)
{
    $archive->delete(); // ou $document->forceDelete() se quiser deletar permanentemente

    return redirect()->route('archives.index')->with('success', 'Documento deletado com sucesso.');
}


}