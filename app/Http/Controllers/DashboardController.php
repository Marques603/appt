<?php

namespace App\Http\Controllers;

use App\Models\Macro;
use App\Models\Document;
use App\Models\User; 
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // Contar documentos da última semana
        $today = Carbon::today();
        $oneWeekAgo = $today->copy()->subDays(7);
        $twoWeeksAgo = $today->copy()->subDays(14);

        $docsCurrent = Document::where('created_at', '>=', $oneWeekAgo)->count();
        $docsPrevious = Document::whereBetween('created_at', [$twoWeeksAgo, $oneWeekAgo])->count();

        // Calcular crescimento percentual
        if ($docsPrevious == 0) {
            if ($docsCurrent == 0) {
                $growthPercent = null; // Sem dados para comparar
            } else {
                $growthPercent = 100; // Crescimento total, primeiros documentos cadastrados
            }
        } else {
            $growthPercent = (($docsCurrent - $docsPrevious) / $docsPrevious) * 100;
            $growthPercent = round($growthPercent, 1);
        }

        $ultimosDocumentos = Document::with(['user', 'macro', 'sectors'])
            ->latest()
            ->take(10);

        return view('dashboard.index', compact(
            'totalMacros', 
            'totalDocumentos', 
            'totalUsers', 
            'ultimosDocumentos',
            'growthPercent',
            'docsCurrent',
            'docsPrevious'
        ));
    }
}
