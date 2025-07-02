<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu; // Importar Menu para o Gate
use Illuminate\Support\Facades\Gate; // Importar Gate

class FolderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Adicionado middleware auth
    }

    /**
     * Display a listing of the Folders accessible to the user.
     */
    public function index()
    {
        // Exemplo de Gate para acesso ao menu (adapte o ID do menu se necessário)
        if (!Gate::allows('view', Menu::find(1))) { // Substitua XXX pelo ID do menu "Pastas" ou similar
            return redirect()->route('dashboard')->with('status', 'This menu is not released for your profile.');
        }

        $user = Auth::user();
        $userSectorIds = $user->sectors->pluck('id');

        // Buscamos apenas os Folders que estão ativos E que contenham subfolders acessíveis
        // (ou seja, subfolders que estão relacionados a pelo menos um dos setores do usuário)
        $folders = Folder::where('status', true) // Apenas folders ativos
                         ->whereHas('subfolders.sectors', function ($query) use ($userSectorIds) {
                             $query->whereIn('sector.id', $userSectorIds); // Filtra por setores do usuário
                         })
                         ->get();

        return view('folders.index', compact('folders'));
    }

    /**
     * Display the specified Folder and its accessible Subfolders.
     */
    public function show(Folder $folder)
    {
        // Se o Folder não estiver ativo e o usuário não for admin, redireciona ou aborta.
        if (!$folder->status && !Auth::user()->isAdmin()) {
            return redirect()->route('folders.index')->with('error', 'This folder is currently inactive and cannot be accessed.');
        }

        $user = Auth::user();
        $userSectorIds = $user->sectors->pluck('id');

        // Verifica se o usuário tem acesso a algum conteúdo dentro deste folder
        // (se o folder não tiver subfolders acessíveis, mesmo que o folder esteja ativo, o usuário não deve vê-lo)
        $hasAccessibleContent = $folder->subfolders()
                                       ->where('status', true)
                                       ->whereHas('sectors', function ($query) use ($userSectorIds) {
                                           $query->whereIn('sector.id', $userSectorIds);
                                       })
                                       ->exists();

        if (!$hasAccessibleContent && !$user->isAdmin()) {
             return redirect()->route('folders.index')->with('error', 'You do not have access to any active content within this folder based on your sectors.');
        }

        // Carrega os Subfolders relacionados a este Folder que estão ativos
        // E que são acessíveis pelos setores do usuário.
        $subfolders = $folder->subfolders()
                             ->where('status', true) // Apenas subfolders ativos
                             ->whereHas('sectors', function ($query) use ($userSectorIds) {
                                 $query->whereIn('sector.id', $userSectorIds);
                             })
                             ->get();

        return view('folders.show', compact('folder', 'subfolders'));
    }
}