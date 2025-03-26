<?php

namespace App\Http\Controllers;
use App\Models\Agendamento;
use App\Models\Doacao; 
use Illuminate\Http\Request;
use App\Models\Doador;

class AgendamentoCentroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $agendamentos = Agendamento::with('doador')->get();
        $query = Agendamento::with('doador');

        // Aplica filtro se existir
        if ($request->filled('tipo_sanguineo')) {
            $query->whereHas('doador', function($q) use ($request) {
                $q->where('tipo_sanguineo', $request->tipo_sanguineo);
            });
        }

    
        $agendamentos = $query->paginate(8)->appends($request->query());
      

    return view('centro.agendamento', compact('agendamentos'));
    }
    public function historico($id)
{
    // Busca o histórico do doador (ex: agendamentos anteriores)
    $historico = Agendamento::where('id_doador', $id)
        ->where('status', 'concluído')
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
        ->update(['status' => $validated['status'] === 'Aprovado' ? 'Concluído' : 'Reprovado']);

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
