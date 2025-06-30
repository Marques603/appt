<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Folder;
use App\Models\ArchiveApproval;
use App\Models\Sector;
use App\Models\Menu;
use App\Models\ArchiveLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArchiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (!Gate::allows('view', Menu::find(2))) {
            return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
        }

        $user = auth()->user();
        $sectorIds = $user->sectors->pluck('id');
        $isQuality = $sectorIds->contains(function ($id) {
            return in_array($id, [1, 3]);
        });

        $archives = Archive::query()
            ->when(!$isQuality, function ($query) use ($sectorIds) {
                $query->where('status', 1)
                      ->whereHas('folders.sectors', function ($q) use ($sectorIds) {
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
                $query->whereHas('folders.sectors', function ($q) use ($request) {
                    $q->where('sector_id', $request->sector);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $sectors = Sector::all();

        return view('archives.index', compact('archives', 'sectors'));
    }

    public function create()
    {
        $folders = Folder::all();
        $sectors = Sector::all();
        return view('archives.create', compact('folders', 'sectors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'file' => 'required|file',
            'folders' => 'nullable|array',
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

        $archive->folders()->sync($request->folders ?? []);

        return redirect()->route('archives.index')->with('success', 'Arquivo criado com sucesso.');
    }

    public function edit(Archive $archive)
    {
        $folders = Folder::all();
        $sectors = Sector::all();

        return view('archives.edit', compact('archive', 'folders', 'sectors'));
    }

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

        return redirect()->back()->with('success', 'Código atualizado com sucesso.');
    }

    public function updateFile(Request $request, Archive $archive)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $archive->delete();

        $filePath = $request->file('file')->store('archives', 'public');

        $newArchive = Archive::create([
            'code' => $archive->code,
            'description' => $archive->description,
            'user_upload' => auth()->id(),
            'revision' => $archive->revision + 1,
            'file_path' => $filePath,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
            'status' => 0,
        ]);

        $newArchive->folders()->sync($archive->folders->pluck('id'));

        return redirect()->route('archives.edit', $newArchive->id)
                         ->with('success', 'Arquivo atualizado. Versão anterior arquivada.');
    }

    public function updateFolders(Request $request, Archive $archive)
    {
        $request->validate([
            'folders' => 'nullable|array',
        ]);

        $archive->folders()->sync($request->folders ?? []);

        return redirect()->back()->with('success', 'Pastas atualizadas com sucesso.');
    }

    public function showApproveForm($archiveId)
    {
        $archive = Archive::with('approvals.user')->findOrFail($archiveId);
        return view('archives.approve', compact('archive'));
    }

    public function approve($archiveId)
    {
        $archive = Archive::findOrFail($archiveId);

        if ($archive->approvals()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('archives.index')->with('info', 'Você já aprovou este arquivo.');
        }

        ArchiveApproval::create([
            'archive_id' => $archive->id,
            'user_id' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('archives.index')->with('success', 'Arquivo aprovado com sucesso.');
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

        return redirect()->back()->with('success', 'Status atualizado com sucesso.');
    }

    public function destroy(Archive $archive)
    {
        $archive->delete();
        return redirect()->route('archives.index')->with('success', 'Arquivo deletado com sucesso.');
    }

    public function logAndShow(Archive $archive)
    {
        $user = auth()->user();

        ArchiveLog::create([
            'archive_id' => $archive->id,
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->away(asset('storage/' . $archive->file_path));
    }
}
