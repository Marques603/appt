<?php

namespace App\Http\Controllers;

use App\Models\Macro;
use App\Models\Document;
use App\Models\User; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMacros = Macro::count();
        $totalDocumentos = Document::count();
        $totalUsers = User::count(); // Assuming you want to count users as well
        return view('dashboard.index', compact('totalMacros','totalDocumentos' , 'totalUsers'));
    }
}
