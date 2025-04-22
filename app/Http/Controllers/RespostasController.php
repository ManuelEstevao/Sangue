<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Solicitacao;
use App\Models\RespostaSolicitacao;
use App\Models\Centro;
use App\Models\Doacao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Estoque;
use Carbon\Carbon; 


class RespostasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    // RespostaSolicitacaoController.php
public function store(Request $request)
{
    $validated = $request->validate([
        'id_solicitacao' => 'required|exists:solicitacao,id_sol',
        'quantidade_aceita' => 'required|integer|min:1',
        'observacao' => 'nullable|string|max:255'
    ]);

    $centro = auth()->user()->centro;
    $solicitacao = Solicitacao::findOrFail($validated['id_solicitacao']);

    // Verificar se o centro tem estoque
    $estoque = Estoque::where('id_centro', $centro->id_centro)
        ->where('tipo_sanguineo', $solicitacao->tipo_sanguineo)
        ->first();

    if(!$estoque || $estoque->quantidade < $validated['quantidade_aceita']) {
        return back()->withErrors(['estoque' => 'Estoque insuficiente!']);
    }

    RespostaSolicitacao::create([
        'id_solicitacao' => $validated['id_solicitacao'],
        'id_centro' => $centro->id_centro,
        'quantidade_disponivel' => $estoque->quantidade,
        'quantidade_aceita' => $validated['quantidade_aceita'],
        'observacao' => $validated['observacao'],
        'status' => 'pendente'
    ]);

    return redirect()->back()->with('success', 'Resposta enviada!');
}




public function gerarRelatorio($idResposta)
{
    try {
        $resposta = RespostaSolicitacao::with([
            'solicitacao.centroSolicitante',
            'centroDoador'
        ])->findOrFail($idResposta);
        
        // Verificar permissões
        $usuarioCentroId = Auth::user()->centro->id_centro;
        $permitido = $usuarioCentroId == $resposta->solicitacao->id_centro || // Solicitante
                     $usuarioCentroId == $resposta->id_centro; // Doador
            
        if(!$permitido) {
            abort(403, 'Acesso não autorizado');
        }

        return PDF::loadView('centro.pdf.solicitante', compact('resposta'))
         ->stream("Relatorio-Centro-Solicitante-{$idResposta}.pdf");
         


    } catch (\Exception $e) {
        
        return redirect()->back()
            ->withErrors('Erro ao gerar relatório: ' . $e->getMessage());

    }
}

public function gerarRelatorioDoador($idResposta)
{
    
    $resposta = RespostaSolicitacao::with([
        'solicitacao.centroSolicitante',
        'centroDoador'
    ])->findOrFail($idResposta);

    // Só o próprio doador (ou o solicitante) pode gerar
    $usuarioCentroId = Auth::user()->centro->id_centro;
    if ($usuarioCentroId !== $resposta->id_centro) {
        abort(403, 'Acesso não autorizado');
    }

    // Gera o PDF usando a view pdf.doador
    return Pdf::loadView('centro.pdf.relatorio-doador', compact('resposta'))
              ->download("Relatorio_Centro_Doador_{$idResposta}.pdf");
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
