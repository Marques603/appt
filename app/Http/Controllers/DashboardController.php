<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;
use App\Models\DocumentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalDocuments = Document::count();
        $totalClicks = DocumentLog::count();

        $sevenDaysAgo = Carbon::now()->subDays(7);
        $activeUsersCount = User::where('last_login_at', '>=', $sevenDaysAgo)->count();
        $inactiveUsersCount = $totalUsers - $activeUsersCount;

        $activeUsersChartData = [
            'series' => [$activeUsersCount, $inactiveUsersCount],
            'labels' => ['Ativos', 'Inativos'],
        ];

        // Logins por dia (últimos 7 dias)
        $loginsPorDia = User::whereNotNull('last_login_at')
            ->where('last_login_at', '>=', now()->subDays(6))
            ->selectRaw('DATE(last_login_at) as data, COUNT(*) as total')
            ->groupBy('data')
            ->pluck('total', 'data');

        $ultimos7Dias = collect(range(6, 0))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        });

        $loginChartData = $ultimos7Dias->mapWithKeys(function ($dia) use ($loginsPorDia) {
            return [$dia => $loginsPorDia[$dia] ?? 0];
        });

        // Documentos criados nos últimos 7 dias
        $documentosSemana = Document::where('created_at', '>=', now()->subDays(7))->count();

        // Top 5 documentos mais acessados
        $documentosMaisAcessados = DocumentLog::select('document_id', DB::raw('count(*) as total'))
            ->groupBy('document_id')
            ->orderByDesc('total')
            ->with('document')
            ->limit(5)
            ->get();

        // Setores com mais documentos (assumindo tabela pivot document_sector)
        $setoresMaisAtivos = DB::table('document_sector')
            ->select('sector_id', DB::raw('count(*) as total'))
            ->groupBy('sector_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Usuários com mais logins (assumindo tabela users_logs)
        $usuariosMaisAtivos = DB::table('users_logs')
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Percentuais por status
        $totalAprovados = Document::where('status', 'aprovado')->count();
        $totalReprovados = Document::where('status', 'reprovado')->count();
        $totalPendentes = Document::where('status', 'pendente')->count();

        $percentAprovados = $totalDocuments ? round(($totalAprovados / $totalDocuments) * 100, 1) : 0;
        $percentReprovados = $totalDocuments ? round(($totalReprovados / $totalDocuments) * 100, 1) : 0;
        $percentPendentes = $totalDocuments ? round(($totalPendentes / $totalDocuments) * 100, 1) : 0;

        return view('dashboard.index', compact(
            'activeUsersChartData',
            'loginChartData',
            'totalDocuments',
            'totalUsers',
            'totalClicks',
            'documentosSemana',
            'documentosMaisAcessados',
            'setoresMaisAtivos',
            'usuariosMaisAtivos',
            'percentAprovados',
            'percentReprovados',
            'percentPendentes'
        ));
    }
}
