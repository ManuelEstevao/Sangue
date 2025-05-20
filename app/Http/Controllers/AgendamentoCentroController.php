<?php

namespace App\Http\Controllers;
use App\Models\Agendamento;
use App\Models\Doacao; 
use Illuminate\Http\Request;
use App\Models\Doador;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notificacao;

class AgendamentoCentroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    
    $centro = Auth::user()->centro;
    
    
    $query = Agendamento::with('doador')
             ->where('id_centro', $centro->id_centro); 

    
    $query->when($request->filled('tipo_sanguineo'), function($q) use ($request) {
        $q->whereHas('doador', function($subQuery) use ($request) {
            $subQuery->where('tipo_sanguineo', $request->tipo_sanguineo);
        });
    });

    
     // Ordenação por status e depois por data
    $agendamentos = $query
        ->orderByRaw("
            CASE 
                WHEN status = 'Agendado' THEN 0 
                WHEN status = 'Comparecido' THEN 1 
                ELSE 2 
            END
        ")  
        ->orderBy('data_agendada', 'desc')  
        ->paginate(8)
        ->appends($request->query());

    return view('centro.agendamento', compact('agendamentos',));
}
    public function historico($id)
{
    // Busca o histórico do doador (ex: agendamentos anteriores)
    $historico = Agendamento::where('id_doador', $id)
        ->where('status', 'concluido')
        ->get(['data_agendamento', 'status']);

    return response()->json($historico);
}

    /**
     * Registra uma nova doação
     */
    public function registrarDoacao(Request $request)
    {
        $validated = $request->validate([
            'id_agendamento' => 'required',
            'id_doador' => 'required',
            'tipo_sanguineo' => 'nullable',
            'hemoglobina' => 'required|numeric|min:12.5',
            'pressao_arterial' => 'required|string|max:7',
            'volume' => 'required|numeric|between:300,500',
            'status' => 'required|in:Aprovado,Reprovado',
            'observacoes' => 'required'
        ]);

        try {
            DB::beginTransaction();
            // Atualiza tipo sanguíneo se necessário
        if ($request->filled('tipo_sanguineo')) {
            Doador::where('id_doador', $validated['id_doador'])
                ->update(['tipo_sanguineo' => $validated['tipo_sanguineo']]);
        }

            // Cria a doação
            $doacao = Doacao::create([
                'id_agendamento' => $validated['id_agendamento'], 
                'id_doador' => $validated['id_doador'], 
                'hemoglobina' => $validated['hemoglobina'],
                'pressao_arterial' => $validated['pressao_arterial'],
                'volume_coletado' => $validated['volume'],
                'status' => $validated['status'],
                'observacoes' => $validated['obsservacoes'] 
            ]);

            
        // Atualiza status do agendamento
        Agendamento::where('id_agendamento', $validated['id_agendamento'])
        ->update(['status' => $validated['status'] === 'Aprovado' ? 'Concluido' : 'Reprovado']);

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar doação: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marca comparecimento do doador
     */
    public function marcarComparecido($id)
{
    $agendamento = Agendamento::findOrFail($id);
    
    if($agendamento->status !== 'Agendado') {
        return redirect()->back()
            ->with('error', 'Status inválido para esta operação');
    }

    $agendamento->update(['status' => 'Comparecido']);
    
    return redirect()->route('centro.agendamento')
        ->with('success', 'Comparecimento registrado com sucesso!');
}

public function verificarComparecimentos()
{
    $limite = now()->subMinutes(2);
    
    $agendamentosAtrasados = Agendamento::where('status', 'Agendado')
    ->where('data_agendada', '<=', $limite->toDateString())
    ->whereTime('horario', '<=', $limite->toTimeString())
    ->with(['doador.user', 'centro'])
    ->get();
    

    foreach ($agendamentosAtrasados as $agendamento) {
        try {
            DB::beginTransaction();
            
            
            $agendamento->update(['status' => 'Não Compareceu']);
            
            
            Notificacao::create([
                'mensagem' => sprintf(
                    "Não comparecimento registrado no agendamento de %s às %s no centro %s. Por favor, entre em contato para reagendar.",
                    Carbon::parse($agendamento->data_agendada)->format('d/m/Y'), 
                    Carbon::parse($agendamento->horario)->format('H:i'),
                    $agendamento->centro->nome
                ),
                'tipo' => 'nao_compareceu',
                'id_user' => $agendamento->doador->user->id_user,
                'id_agendamento' => $agendamento->id_agendamento
            ]);

            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao processar agendamento {$agendamento->id_agendamento}: " . $e->getMessage());
        }
    }

    return response()->json([
        'total_atualizados' => $agendamentosAtrasados->count(),
        'ultima_verificacao' => now()->format('Y-m-d H:i:s')
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
        public function confirmar(Agendamento $agendamento)
    {
        try {
            $agendamento->update(['status' => 'confirmado']);
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao confirmar agendamento'
            ], 500);
        }
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
    public function cancelar($id)
    {
        // Recupera o agendamento pelo ID
        $agendamento = Agendamento::findOrFail($id);

        // Define o status como 'Cancelado'
        $agendamento->status = 'Cancelado';
        $agendamento->save();

        return redirect()->route('centro.agendamento')
                         ->with('success', 'Agendamento cancelado com sucesso!');
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
