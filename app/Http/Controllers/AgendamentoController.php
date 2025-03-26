<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Centro;
use App\Models\Campanha;
use App\Models\Doador;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon; 



class AgendamentoController extends Controller
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
        $centros = Centro::all();
        $doador = Auth::user()->doador;

        // Buscar campanhas ativas
        $campanhas = Campanha::where('data_inicio', '<=', now())
                             ->where('data_fim', '>=', now())
                             ->get();

        // Buscar a última doação concluída
        $ultimaDoacao = Agendamento::where('id_doador', $doador->id_doador)
            ->where('status', 'Concluído')
            ->orderBy('data_agendada', 'desc')
            ->first();

        // Definir intervalo mínimo baseado no gênero
        $intervaloMinimo = ($doador->genero == 'Masculino') ? 90 : 120;

        // Calcular a próxima data permitida de doação com base na última doação
        $proximaDataPermitida = $ultimaDoacao ? Carbon::parse($ultimaDoacao->data_agendada)->addDays($intervaloMinimo) : null;
    
        // Verificar se o doador pode doar novamente
        $podeDoarNovamente = true;
        $dataDisponivel = now();
    
        if ($proximaDataPermitida && now()->lessThan($proximaDataPermitida)) {
            $podeDoarNovamente = false;
            $dataDisponivel = $proximaDataPermitida;
        }
    
        // Se o doador não pode agendar, redireciona para a Dashboard com a mensagem de erro
        if (!$podeDoarNovamente) {
            return redirect()->route('doador.Dashbord')->with([
                'error' => 'Você não pode doar novamente antes de ' . $dataDisponivel->format('d/m/Y') . '.',
                'dataDisponivel' => $dataDisponivel
            ]);
        }
        return view('dador.agendamento', compact('centros', 'doador', 'campanhas', 'podeDoarNovamente', 'dataDisponivel'));
    }


    /**
     * Processa o formulário de agendamento e cria um novo registro.
     */
    public function store(Request $request)
{
    $request->validate([
        'data_agendada' => 'required',
        'horario' => 'required',
        'id_centro' => 'required'
    ]);

    $doador = Auth::user()->doador;
    $centro = Centro::findOrFail($request->id_centro);
    $capacidadeMaxima = $centro->capacidade_maxima;

    // Impedir escolha de horários passados no dia atual
    if ($request->data_agendada == now()->toDateString() && strtotime($request->horario) < strtotime(now()->format('H:i'))) {
        return redirect()->back()->withErrors(['horario' => 'Você não pode selecionar um horário que já passou.']);
    }
    // Verificar capacidade máxima no horário
    $agendamentosNoHorario = Agendamento::where('id_centro', $request->id_centro)
        ->where('data_agendada', $request->data_agendada)
        ->where('horario', $request->horario)
        ->count();

    // Se a capacidade foi atingida, bloquear o agendamento
    if ($agendamentosNoHorario >= $capacidadeMaxima) {
        return redirect()->back()->withErrors([
            'horario' => 'Este horário já atingiu a capacidade máxima de doadores.',
        ]);
    }

    // Verificar se o doador já tem um agendamento pendente
    $agendamentoPendente = Agendamento::where('id_doador', $doador->id_doador)
        ->where('status', 'agendado')
        ->exists();

    if ($agendamentoPendente) {
        return redirect()->back()->with('error', 'Você já tem um agendamento ativo. Conclua ou cancele antes de agendar outro.');
    }

    // 🔹 Recuperar o último agendamento do doador (se houver)
    $ultimoAgendamento = Agendamento::where('id_doador', $doador->id_doador)
        ->where('status', 'Concluido') // Só consideramos doações já concluídas
        ->latest('data_agendada')
        ->first();

    /*🔹 Definir intervalo mínimo baseado no gênero
    $intervaloMinimo = ($doador->genero == 'Masculino') ? 90 : 120;

    if ($ultimoAgendamento) {
        $dataPermitida = \Carbon\Carbon::parse($ultimoAgendamento->data_agendada)->addDays($intervaloMinimo);
        
        if (now()->lt($dataPermitida)) {
            return redirect()->back()->withErrors([
                'agendamento' => "Você só pode doar novamente após {$dataPermitida->format('d/m/Y')}."
            ]);
        }
    }*/

    
    // Criar o agendamento se tudo estiver OK
    Agendamento::create([
        'id_doador' => $doador->id_doador,
        'id_centro' => $request->id_centro,
        'data_agendada' => $request->data_agendada,
        'horario' => $request->horario,
        'status' => 'Agendado'
    ]);

    return redirect()->route('doador.Dashbord')->with('success', 'Agendamento realizado com sucesso!');
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
    public function edit($id)
{
    
    $agendamento = Agendamento::findOrFail($id);
    return view('doador.Dashbord', compact('agendamento'));
}

public function update(Request $request, $id)
{
    // Valida os dados recebidos
    $validatedData = $request->validate([
        'data_agendada' => 'required|date|after_or_equal:today',
    ]);

    // Recupera o agendamento
    $agendamento = Agendamento::findOrFail($id);

    // Separa a data e a hora
    $dataHora = \Carbon\Carbon::parse($request->data_agendada);
    $agendamento->data_agendada = $dataHora->toDateString(); 
    $agendamento->horario = $dataHora->toTimeString(); 

    // Atualiza o banco de dados
    $agendamento->save();

    return redirect()->route('doador.Dashbord')
                     ->with('success', 'Agendamento atualizado com sucesso!');
}

public function cancelar($id)
{
    // Recupera o agendamento pelo ID
    $agendamento = Agendamento::findOrFail($id);
    // Define o status como 'Cancelado'
    $agendamento->status = 'Cancelado';
    $agendamento->save();

    return redirect()->route('doador.Dashbord')
                     ->with('success', 'Agendamento cancelado com sucesso!');
}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
