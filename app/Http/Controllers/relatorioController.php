<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doador;
use App\Models\Centro;
use Barryvdh\DomPDF\Facade\Pdf;

class relatorioController extends Controller
{
    public function index($id_centro, Request $request)
    {
        // Captura filtros
        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');
        $pesquisaNome = $request->input('pesquisa_nome');

        // Filtra doadores que doaram para esse centro específico
        $query = Doador::whereHas('doacoes', function ($q) use ($id_centro, $dataInicio, $dataFim) {
            $q->where('id_centro', $id_centro);
            if ($dataInicio && $dataFim) {
                $q->whereBetween('data_doacao', [$dataInicio, $dataFim]);
            }
        });

        if ($pesquisaNome) {
            $query->where('nome', 'like', "%{$pesquisaNome}%");
        }

        $doadores = $query->with(['doacoes' => function ($q) use ($centro_id) {
            $q->where('centro_coleta_id', $centro_id)->latest('data_doacao');
        }])->get();

        $centro = Centro::findOrFail($centro_id);

        return view('relatorios.index', compact('doadores', 'centro'));
    }

    public function exportPdf($centro_id)
    {
        $doadores = Doador::whereHas('doacao', function ($q) use ($centro_id) {
            $q->where('id_centro', $id_centro);
        })->with('doacao')->get();

        $centro = CentroColeta::findOrFail($centro_id);
        $pdf = Pdf::loadView('relatorios.pdf', compact('doadores', 'centro'));

        return $pdf->download("relatorio_doadores_{$centro->nome}.pdf");
    }
}

