<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Subfolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubfolderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Garante que apenas usuários autenticados acessem
    }

    /**
     * Display the specified Subfolder and its accessible Sectors/Archives.
     */
    public function show(Folder $folder, Subfolder $subfolder)
    {
        // 1. Verificação de Status do Subfolder:
        // Se o Subfolder estiver inativo, ele não pode ser acessado por NINGUÉM.
        if (!$subfolder->status) {
            return redirect()->route('folders.show', $folder->slug)->with('error', 'This subfolder is currently inactive and cannot be accessed.');
        }

        $user = Auth::user();
        $userSectorIds = $user->sectors->pluck('id');

        // 2. Verificação de Pertença à Hierarquia:
        // Verifica se o subfolder realmente pertence ao folder especificado na URL (segurança).
        if (!$folder->subfolders->contains($subfolder)) {
            abort(404, 'Subfolder not found in the specified folder path.');
        }

        // 3. Verificação de Acesso ao Subfolder por Setor:
        // Se o usuário não tiver acesso a este Subfolder através de pelo menos um de seus setores, aborta.
        $canAccessSubfolderBySector = $subfolder->sectors()->whereIn('sector.id', $userSectorIds)->exists();

        if (!$canAccessSubfolderBySector) {
            abort(403, 'You do not have permission to access this subfolder based on your sector access.');
        }

        // Busca Setores e Archives acessíveis dentro deste Subfolder:

        // Accessible Sectors: devem estar ativos E serem acessíveis pelos setores do usuário.
        $accessibleSectors = $subfolder->sectors()
                                       ->where('status', true) // Setor deve estar ativo
                                       ->whereIn('sector.id', $userSectorIds)
                                       ->get();

        // Accessible Archives: devem estar ativos E serem acessíveis pelos setores do usuário.
        $accessibleArchives = $subfolder->archives()
                                        ->where('status', true) // Archive deve estar ativo
                                        ->whereHas('sectors', function ($query) use ($userSectorIds) {
                                            $query->whereIn('sector.id', $userSectorIds);
                                        })
                                        ->get();

        return view('subfolders.show', compact('folder', 'subfolder', 'accessibleSectors', 'accessibleArchives'));
    }
}