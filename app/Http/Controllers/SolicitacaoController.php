<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitacao;
use App\Models\Centro;
use App\Models\Doacao;
use Illuminate\Support\Facades\Auth;
use App\Models\Estoque;


class SolicitacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $centroLogado = Auth::user()->centro;

        // Solicitações do próprio centro
        $solicitacoesProprias = Solicitacao::with(['centroSolicitante', 'respostas'])
            ->where('id_centro', $centroLogado->id_centro);

        // Solicitações de outros centros com estoque compatível
        $solicitacoesExternas = Solicitacao::with(['centroSolicitante', 'respostas'])
            ->where('id_centro', '!=', $centroLogado->id_centro)
            ->whereIn('status', ['pendente', 'parcial'])
            ->whereHas('estoqueCompativel', function($query) use ($centroLogado) {
                $query->where('id_centro', $centroLogado->id_centro)
                    ->where('quantidade', '>', 0);
            });

        $solicitacoes = $solicitacoesProprias->union($solicitacoesExternas)
            ->orderBy('urgencia', 'desc')
            ->orderBy('prazo', 'asc')
            ->paginate(10);

        return view('centro.solicitacao', compact('solicitacoes'));
    }

    /**
     * Aplica filtros às solicitações
     */
    private function aplicarFiltros($query, Request $request)
    {
        return $query->when($request->filled('tipo_sanguineo'), function ($q) use ($request) {
                $q->where('tipo_sanguineo', $request->tipo_sanguineo);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('urgencia'), function ($q) use ($request) {
                $q->where('urgencia', $request->urgencia);
            })
            ->when($request->filled('prazo'), function ($q) use ($request) {
                $q->where('prazo', '<=', $request->prazo);
            })
            ->when($request->filled(['data_inicio', 'data_fim']), function ($q) use ($request) {
                $q->whereBetween('created_at', [$request->data_inicio, $request->data_fim]);
            });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('centro.createsolicitacao');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    
    $validated = $request->validate([
        'tipo_sanguineo' => 'required',
        'quantidade' => 'required|integer|min:1',
        'urgencia' => 'required',
        'prazo' => 'required|date',
        'motivo' => 'nullable|string',
    ]);

    $user = Auth::user();
    $centro = $user->centro;

    Solicitacao::create([
        'id_centro' => $centro->id_centro,
        'tipo_sanguineo' => $validated['tipo_sanguineo'],
        'quantidade' => $validated['quantidade'],
        'urgencia' => $validated['urgencia'],
        'prazo' => $validated['prazo'],
        'motivo' => $validated['motivo'] ?? null,
        'status' => 'Pendente',
    ]);


    return redirect()->back()->with('success', 'Solicitação criada com sucesso!');
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Retorna os detalhes (view parcial) para carregar na modal de detalhes
        $solicitacao = Solicitacao::with(['respostas', 'centro'])->findOrFail($id);
        return view('centro.solicitacao.partial_show', compact('solicitacao'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $solicitacao = Solicitacao::findOrFail($id);
        // Verifica se o centro autenticado é o solicitante
        if ($solicitacao->id_centro != Auth::user()->centro->id_centro) {
            abort(403, 'Ação não autorizada.');
        }
        return view('centro.solicitacao.edit', compact('solicitacao'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $solicitacao = Solicitacao::findOrFail($id);
        // Apenas o centro solicitante pode atualizar, se a solicitação estiver em status editável (por exemplo, "Pendente")
        if ($solicitacao->id_centro != Auth::user()->centro->id_centro || $solicitacao->status != 'Pendente') {
            abort(403, 'Ação não autorizada.');
        }

        $validated = $request->validate([
            'tipo_sanguineo' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'quantidade'      => 'required|integer|min:1',
            'urgencia'        => 'required|in:Normal,Emergencia',
            'prazo'           => 'required|date_format:Y-m-d\TH:i',
            'motivo'          => 'nullable|string|max:255',
        ]);

        $solicitacao->update($validated);

        return redirect()->route('centro.solicitacao.index')
                         ->with('success', 'Solicitação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
