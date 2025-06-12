<?php

namespace App\Http\Controllers;

use App\Models\Macro;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMacros = Macro::count();
        return view('dashboard.index', compact('totalMacros'));
    }
}
