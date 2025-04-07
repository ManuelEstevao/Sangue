<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;     
use App\Models\Doacao; 
use App\Models\Doador;        
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DoacaoController extends Controller
{

    public function index(Request $request)
    {
        $centro = Auth::user()->centro;
        $query = Doacao::with(['agendamento.doador'])
            ->whereHas('agendamento', function($query) use ($centro) {
                $query->where('id_centro', $centro->id_centro);
            });

        // Filtros
        $query->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status', $request->status);
        });

        $query->when($request->filled('search'), function ($query) use ($request) {
            $query->whereHas('agendamento.doador', function ($subQuery) use ($request) {
                $subQuery->where('nome', 'like', '%' . $request->search . '%');
            });
        });

        $doacoes = $query->orderByDesc('data_doacao')
        ->paginate(10)
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
        'peso' => 'required|numeric',
        'nome_profissional' => 'required|string|max:255',
        'status' => 'required|in:Aprovado,Reprovado',
        'observacoes' => 'nullable|string|max:500',
        'tipo_sanguineo' => 'nullable|string|in:A+,A-,B+,B-,O+,O-,AB+,AB-'
    ]);

    try {
        DB::beginTransaction();

        $agendamento = Agendamento::findOrFail($request->agendamento_id);

        // Verificação de segurança
        if ($agendamento->id_centro !== auth()->user()->centro->id_centro) {
            return response()->json([
                'success' => false,
                'message' => 'Ação não autorizada'
            ], 403);
        }

        // Atualizar o tipo sanguíneo do doador, se fornecido
        if ($request->filled('tipo_sanguineo') && $request->tipo_sanguineo !== 'Desconhecido') {
            $doador = Doador::find($agendamento->id_doador);
            $doador->update(['tipo_sanguineo' => $request->tipo_sanguineo]);
        }

        // Atualizar o peso do doador (campo na tabela doadores)
        if ($request->filled('peso')) {
            $doador = Doador::findOrFail($agendamento->id_doador);
            $doador->update(['peso' => $request->peso]);
        }

        Doacao::create([
            'id_agendamento' => $request->agendamento_id,
            'hemoglobina' => $request->hemoglobina,
            'pressao_arterial' => $request->pressao_arterial,
            'volume_coletado' => $request->volume_coletado,
            'nome_profissional' => $request->nome_profissional,
            'status' => $request->status,
            'observacoes' => $request->observacoes,
            'id_doador' => $agendamento->id_doador,
            'id_centro' => $agendamento->id_centro,
            'data_doacao' => now()
        ]);

        $agendamento->update(['status' => 'Concluido']);

        DB::commit();

        return redirect()->back()->with('success', 'Doação registrada com sucesso!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Erro: ' . $e->getMessage());
    }
}

public function edit($id)
    {
        $doacao = Doacao::findOrFail($id);
        return view('centro.doacao.edit', compact('doacao'));
    }

    public function update(Request $request, $id)
    {
        // Validação dos dados enviados
        $validated = $request->validate([
            'hemoglobina'       => 'required|numeric',
            'pressao_arterial'  => 'required|regex:/^\d{2,3}\/\d{2,3}$/',
            'volume_coletado'   => 'required|integer|between:300,500',
            'peso'              => 'required|numeric',
            'nome_profissional' => 'required|string|max:255',
            'status'            => 'required|in:Aprovado,Reprovado',
            'observacoes'       => 'nullable|string|max:500'
        ]);
    
        try {
            DB::beginTransaction();
    
            // Recupera a doação pelo ID
            $doacao = Doacao::findOrFail($id);
    
            // Se o peso estiver na tabela de doadores, atualizamos o doador associado
            $doador = $doacao->agendamento->doador;
            $doador->update(['peso' => $request->peso]);
    
            // Atualiza os demais campos da doação
            $doacao->update([
                'hemoglobina'       => $request->hemoglobina,
                'pressao_arterial'  => $request->pressao_arterial,
                'volume_coletado'   => $request->volume_coletado,
                'nome_profissional' => $request->nome_profissional,
                'status'            => $request->status,
                'observacoes'       => $request->observacoes,
            ]);
    
            DB::commit();
    
            return redirect()->route('centro.doacao')->with('success', 'Doação atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar a doação: ' . $e->getMessage());
        }
    }

    // Exclui a doação
    public function destroy($id)
    {
        try {
            $doacao = Doacao::findOrFail($id);
            $agendamento = Agendamento::findOrFail($doacao->id_agendamento);
            $doacao->delete();

        // Excluir o agendamento associado
          $agendamento->delete();
            return redirect()->route('centro.doacao')->with('success', 'Doação excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir a doação: ' . $e->getMessage());
        }
    }


}
