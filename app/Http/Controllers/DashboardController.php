<?php

namespace App\Http\Controllers;

use App\Models\Macro;
use App\Models\Document;
use App\Models\User; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalMacros = Macro::count();
        $totalDocumentos = Document::count();
        $totalUsers = User::count();

        $ultimosDocumentos = Document::with(['user', 'macro', 'sectors'])
            ->latest()
            ->take(10);
            
        return view('dashboard.index', compact('totalMacros', 'totalDocumentos', 'totalUsers', 'ultimosDocumentos'));
    }
}

