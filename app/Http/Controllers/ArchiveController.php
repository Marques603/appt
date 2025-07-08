<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Folder;
use App\Models\Plan;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('view', Menu::find(4))) {
            return redirect()->route('dashboard')->with('status', 'Este menu não está liberado para o seu perfil.');
        }

        $query = Archive::query();

        // Filtros
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('sector') && $request->sector !== '') {
            $query->whereHas('sectors', function ($q) use ($request) {
                $q->where('sector_id', $request->sector);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $archives = $query->with(['folders', 'plans'])->paginate(10);
        $plans = Plan::all();

        return view('archives.index', compact('archives', 'plans'));
    }

    public function create(Folder $folder, Plan $plan)
    {
        return view('archives.create', compact('folder', 'plan'));
    }

    public function store(Request $request, Folder $folder, Plan $plan)
    {
        $validated = $request->validate([
            'code' => 'required|unique:archives,code',
            'description' => 'nullable|string',
            'file' => 'required|file',
            'revision' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $path = $request->file('file')->store('archives');

        $archive = Archive::create([
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
            'user_upload' => auth()->id(),
            'revision' => $validated['revision'] ?? '1',
            'file_path' => $path,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
            'status' => $validated['status'] ?? 1,
        ]);

        $archive->folders()->syncWithoutDetaching([$folder->id]);
        $archive->plans()->syncWithoutDetaching([$plan->id]);

        return redirect()->route('folders.planFiles', ['folder' => $folder->id, 'plan' => $plan->id])
                         ->with('success', 'Arquivo criado e vinculado com sucesso!');
    }

    public function show(Archive $archive)
    {
        \DB::table('archive_logs')->insert([
            'archive_id' => $archive->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return view('archives.show', compact('archive'));
    }

    public function logAndShow(Archive $archive)
    {
        \App\Models\ArchiveLog::create([
            'archive_id' => $archive->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return Storage::download($archive->file_path);
    }

    public function download(Archive $archive)
    {
        return Storage::download($archive->file_path);
    }

    public function destroy($id)
    {
        $archive = Archive::findOrFail($id);

        $folder = $archive->folders()->first();
        $sector = $archive->sector;

        $archive->delete();

        return redirect()->route('folders.index')
                         ->with('success', 'Arquivo excluído com sucesso.');
    }
}
