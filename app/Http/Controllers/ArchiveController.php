<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Sector;
use App\Models\Subfolder;
use App\Models\Folder; // Importar Folder para o método upload
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Garante que apenas usuários autenticados acessem
    }

    /**
     * Display a specific Archive.
     */
    public function view(Archive $archive)
    {
        $user = Auth::user();

        // 1. Verificação de Status do Archive:
        // Se o Archive estiver inativo, ele não pode ser acessado por NINGUÉM.
        if (!$archive->status) {
            abort(403, 'This archive is inactive or not accessible.');
        }

        // 2. Verificação de Acesso ao Archive por Setor:
        // Se o usuário não tiver acesso ao Archive através de pelo menos um de seus setores, aborta.
        $userSectorIds = $user->sectors->pluck('id');
        $hasAccessBySector = $archive->sectors()->whereIn('sector.id', $userSectorIds)->exists();

        if (!$hasAccessBySector) {
            abort(403, 'You do not have permission to view this archive.');
        }

        // Retorna o arquivo para visualização no navegador.
        return response()->file(Storage::path($archive->path));
    }

    /**
     * Download a specific Archive.
     */
    public function download(Archive $archive)
    {
        $user = Auth::user();

        // 1. Verificação de Status do Archive:
        // Se o Archive estiver inativo, ele não pode ser acessado por NINGUÉM.
        if (!$archive->status) {
            abort(403, 'This archive is inactive or not accessible.');
        }

        // 2. Verificação de Acesso ao Archive por Setor:
        // Se o usuário não tiver acesso ao Archive através de pelo menos um de seus setores, aborta.
        $userSectorIds = $user->sectors->pluck('id');
        $hasAccessBySector = $archive->sectors()->whereIn('sector.id', $userSectorIds)->exists();

        if (!$hasAccessBySector) {
            abort(403, 'You do not have permission to download this archive.');
        }

        // Retorna o arquivo para download.
        return Storage::download($archive->path, $archive->name);
    }

    /**
     * Handle the upload of a new Archive.
     */
    public function upload(Request $request, Folder $folder, Subfolder $subfolder)
    {
        $user = Auth::user();

        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'archive_file' => 'required|file|max:20480', // 20MB max
            'sector_upload_id' => 'required|exists:sector,id', // Valida que o ID do setor existe na tabela 'sector'
            'description' => 'nullable|string|max:255',
            'revision' => 'nullable|string|max:255',
        ]);

        $sector = Sector::find($request->input('sector_upload_id'));

        // 1. Validação de Pertença à Hierarquia da URL (segurança):
        // Verifica se o folder e subfolder são consistentes com a URL.
        if (!$folder->subfolders->contains($subfolder)) {
            abort(404, 'Invalid folder/subfolder path for upload.');
        }

        // 2. Verificação de Status do Sector:
        // Se o Sector selecionado estiver inativo, não permite upload.
        if (!$sector->status) {
            return back()->with('error', 'The selected sector is inactive and cannot be uploaded to.');
        }

        // 3. Verificação de Permissão de Upload por Setor:
        // O usuário pode fazer upload SE:
        //    O usuário tem acesso ao setor selecionado E o subfolder está relacionado a este setor.
        $canUploadBySector = $user->sectors->contains($sector) && $subfolder->sectors->contains($sector);

        if (!$canUploadBySector) {
            abort(403, 'You do not have permission to upload to this location based on your sector access.');
        }

        $file = $request->file('archive_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('archives', $fileName, 'public');

        // Cria o novo registro de Archive no banco de dados
        $archive = Archive::create([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'path' => $path,
            'extension' => $file->getClientOriginalExtension(),
            'revision' => $request->input('revision'),
            'user_upload' => $user->id, // Associa o ID do usuário que fez o upload
            'size' => $file->getSize(),
            'description' => $request->input('description'),
            'status' => true, // Novo archive é ativo por padrão
        ]);

        // Anexa o archive aos relacionamentos corretos
        $archive->subfolders()->attach($subfolder->id);
        $archive->sectors()->attach($sector->id);

        return back()->with('success', 'Archive uploaded successfully to ' . $sector->name . ' in ' . $subfolder->name . '!');
    }
}