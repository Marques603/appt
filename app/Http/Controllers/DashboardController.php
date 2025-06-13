<?php

namespace App\Http\Controllers;

use App\Models\Macro;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // Datas para comparação documentos
        $today = Carbon::today();
        $oneWeekAgo = $today->copy()->subDays(7);
        $twoWeeksAgo = $today->copy()->subDays(14);

        // Documentos da última semana e da semana anterior
        $docsCurrent = Document::where('created_at', '>=', $oneWeekAgo)->count();
        $docsPrevious = Document::whereBetween('created_at', [$twoWeeksAgo, $oneWeekAgo])->count();

        // Calcular crescimento percentual documentos
        if ($docsPrevious == 0) {
            $growthPercent = ($docsCurrent == 0) ? null : 100;
        } else {
            $growthPercent = round((($docsCurrent - $docsPrevious) / $docsPrevious) * 100, 1);
        }

        // Últimos documentos
        $ultimosDocumentos = Document::with(['user', 'macro', 'sectors'])
            ->latest()
            ->take(10);

        // Gráfico: documentos por dia nos últimos 7 dias
        $ultimos7Dias = collect(range(6, 0))->map(function ($i) {
            return Carbon::today()->subDays($i)->format('Y-m-d');
        });

        $documentosPorDia = Document::where('created_at', '>=', Carbon::today()->subDays(6))
            ->selectRaw('DATE(created_at) as data, COUNT(*) as total')
            ->groupBy('data')
            ->pluck('total', 'data');

        $dadosGrafico = $ultimos7Dias->mapWithKeys(function ($dia) use ($documentosPorDia) {
            return [$dia => $documentosPorDia[$dia] ?? 0];
        });

        // Usuários ativos e inativos últimos 7 dias
        $sevenDaysAgo = Carbon::today()->subDays(7);

        // Certifique-se que seu model User tem o campo 'last_active_at' atualizado corretamente
        $activeUsersCount = User::where('last_login_at', '>=', $sevenDaysAgo)->count();
        $inactiveUsersCount = $totalUsers - $activeUsersCount;

        if ($totalUsers > 0) {
            $percentActive = round(($activeUsersCount / $totalUsers) * 100, 1);
            $percentInactive = round(($inactiveUsersCount / $totalUsers) * 100, 1);
        } else {
            $percentActive = 0;
            $percentInactive = 0;
        }

        return view('dashboard.index', compact(
            'totalMacros',
            'totalDocumentos',
            'totalUsers',
            'ultimosDocumentos',
            'growthPercent',
            'docsCurrent',
            'docsPrevious',
            'dadosGrafico',
            'activeUsersCount',
            'inactiveUsersCount',
            'percentActive',
            'percentInactive'
        ));
    }
}
