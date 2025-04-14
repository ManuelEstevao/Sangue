<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Solicitacao;
use App\Models\Doador;
use App\Models\Campanha;
use App\Models\Centro;
use App\Models\Doacao;
use App\Models\Estoque;
use App\Models\Notificacao;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { // Métricas Principais
        $metrics = [
            'total_doadores' => Doador::count(),
            'total_centros' => Centro::count(),
            'doacoes_hoje' => Doacao::whereDate('data_doacao', today())->count(),
            'solicitacoes_urgentes' => Solicitacao::where('urgencia', 'emergencia')
                ->where('status', 'pendente')
                ->count(),
            'estoque_total' => Estoque::sum('quantidade')
        ];

        // Gráfico: Histórico de Doações (Últimos 6 meses)
        $donationsHistory = Doacao::selectRaw('
                YEAR(data_doacao) as year, 
                MONTH(data_doacao) as month, 
                COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->limit(6)
            ->get()
            ->map(function ($item) {
                return [
                    'label' => Carbon::create()
                        ->year($item->year)
                        ->month($item->month)
                        ->format('M/Y'),
                    'total' => $item->total
                ];
            });

        // Distribuição de Tipos Sanguíneos
        $bloodTypeDistribution = Doacao::join('doador', 'doacao.id_doador', '=', 'doador.id_doador')
        ->selectRaw('doador.tipo_sanguineo, COUNT(*) as total')
        ->groupBy('doador.tipo_sanguineo')
        ->get()
        ->mapWithKeys(fn($item) => [$item->tipo_sanguineo => $item->total]);

        // Estoque Crítico (menos de 20 unidades)
        $criticalStock = Estoque::with('centro')
            ->where('quantidade', '<', 20)
            ->orderBy('quantidade')
            ->get()
            ->map(function ($item) {
                return [
                    'tipo' => $item->tipo_sanguineo,
                    'quantidade' => $item->quantidade,
                    'centro' => $item->centro->nome,
                    'cidade' => $item->centro->endereco // Assumindo que centro tem campo endereco
                ];
            });

        // Últimas 5 Doações
        $recentDonations = Doacao::with(['doador', 'centro'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($doacao) {
                return [
                    'data' => $doacao->data_doacao->format('d/m/Y H:i'),
                    'doador' => $doacao->doador->nome,
                    'tipo_sanguineo' => $doacao->tipo_sanguineo,
                    'centro' => $doacao->centro->nome,
                    'quantidade' => $doacao->quantidade
                ];
            });

        return view('ADM.dashboard', compact(
            'metrics',
            'donationsHistory',
            'bloodTypeDistribution',
            'criticalStock',
            'recentDonations'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
