<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;
use App\Models\DocumentLog;
use App\Models\Sector;
use Illuminate\Http\Request;
use App\Models\Macro;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalSetores = Sector::count();
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

        // Setores com mais documentos
        $setoresMaisAtivos = DB::table('document_sector')
            ->select('sector_id', DB::raw('count(*) as total'))
            ->groupBy('sector_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Usuários com mais logins
        $usuariosMaisAtivos = DB::table('users_logs')
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Total de documentos por setor (Top 5)
        $totalPorSetor = DB::table('document_sector')
            ->join('sector', 'document_sector.sector_id', '=', 'sector.id')
            ->select('sector.name', DB::raw('count(document_sector.document_id) as total'))
            ->groupBy('sector.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Total de documentos por macro
        $totalPorMacro = Macro::withCount('documents')
            ->orderByDesc('documents_count')
            ->limit(5)
            ->get();

        // Total por Macro por Setor (o novo)
        $documentsBySectorMacro = DB::table('document')
            ->join('document_macro', 'document_macro.document_id', '=', 'document.id')
            ->join('macro', 'macro.id', '=', 'document_macro.macro_id')
            ->join('document_sector', 'document_sector.document_id', '=', 'document.id')
            ->join('sector', 'sector.id', '=', 'document_sector.sector_id')
            ->select('macro.name as macro', 'sector.name as sector', DB::raw('COUNT(document_sector.document_id) as total'))
            ->groupBy('macro.name', 'sector.name')
            ->orderBy('macro.name')
            ->orderByDesc('total')
            ->get();

        // Formatar para o gráfico JS
        $macroSectorChartData = [];
        foreach ($documentsBySectorMacro as $row) {
            $macro = $row->macro;
            $sector = $row->sector;
            $total = $row->total;

            if (!isset($macroSectorChartData[$macro])) {
                $macroSectorChartData[$macro] = [];
            }

            $macroSectorChartData[$macro][] = [
                'sector' => $sector,
                'total' => $total,
            ];
        }

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
            'percentPendentes',
            'totalPorMacro',
            'totalPorSetor',
            'totalSetores',
            'macroSectorChartData' // <-- Adicionado
        ));
    }
}
