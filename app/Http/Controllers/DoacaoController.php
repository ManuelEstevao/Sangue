<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;     
use App\Models\Doacao;         
use Illuminate\Support\Facades\DB;

class DoacaoController extends Controller
{

    public function index(Request $request)
    {

        $query = Doacao::with(['doador'])
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status', $request->status);
        })
        ->orderBy('data_doacao', 'desc');

    $doacoes = $query->paginate(10)
        ->appends($request->query());

        return view('centro.doacao', compact('doacoes'));
    }
    public function store(Request $request)
{
    $validated = $request->validate([
        'agendamento_id' => 'required|exists:agendamento,id_agendamento',
        'hemoglobina' => 'required|numeric',
        'pressao_arterial' => 'required|regex:/^\d{2,3}\/\d{2,3}$/',
        'volume_coletado' => 'required|integer|between:300,500',
        'status' => 'required|in:Aprovado,Reprovado',
        'observacoes' => 'nullable|string|max:500'
    ]);

    try {
        DB::beginTransaction();

        $agendamento = Agendamento::findOrFail($request->agendamento_id);
        
        // Verificação de segurança
        if($agendamento->id_centro !== auth()->user()->centro->id_centro) {
            return response()->json([
                'success' => false,
                'message' => 'Ação não autorizada'
            ], 403);
        }

        Doacao::create([
            'hemoglobina' => $request->hemoglobina,
            'pressao_arterial' => $request->pressao_arterial,
            'volume_coletado' => $request->volume_coletado,
            'status' => $request->status,
            'observacoes' => $request->observacoes,
            'id_doador' => $agendamento->id_doador,
            'id_centro' => $agendamento->id_centro,
            'data_doacao' => now()
        ]);

        $agendamento->update(['status' => 'Concluído']);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Doação registrada com sucesso!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Erro: ' . $e->getMessage()
        ], 500);
    }
}

}
