<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacao;
use App\Models\Agendamento;
use App\Models\Centro;
use App\Models\User;
use App\Models\Campanha;
use App\Models\Doador;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AgendamentoNotification;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use App\Models\DiaBloqueado;
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
        // Pré-calcular horários para cada centro
        $horariosCentros = [];
        foreach ($centros as $centro) {
            $horariosCentros[$centro->id_centro] = $this->gerarHorariosCentro(
                $centro->horario_abertura,
                $centro->horario_fechamento
            );
        }
        return view('dador.agendamento', compact('centros', 'doador', 'campanhas', 'podeDoarNovamente', 'dataDisponivel','horariosCentros'));
    }
    private function gerarHorariosCentro($abertura, $fechamento)
{
    $horarios = [];
    $inicio = Carbon::parse($abertura);
    $fim = Carbon::parse($fechamento);

    while ($inicio <= $fim) {
        $horarios[] = $inicio->format('H:i');
        $inicio->addMinutes(30);
    }

    return $horarios;
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

    $diaBloqueado = DiaBloqueado::where('id_centro', $request->id_centro)
    ->whereDate('data', $request->data_agendada)
    ->first();

    if ($diaBloqueado) {
        throw ValidationException::withMessages([
            'data_agendada' => $diaBloqueado->motivo ?? 'Esta data está bloqueada para agendamentos'
        ]);
    }
        if ($request->data_agendada == now()->toDateString()
            && strtotime($request->horario) < strtotime(now()->format('H:i'))
        ) {
            throw ValidationException::withMessages([
                'horario' => ['Você não pode selecionar um horário que já passou.']
            ]);
        }

        // 2.2 — Capacidade máxima atingida
        $count = Agendamento::where('id_centro', $centro->id_centro)
            ->where('data_agendada', $request->data_agendada)
            ->where('horario', $request->horario)
            ->count();

        if ($count >= $centro->capacidade_maxima) {
            throw ValidationException::withMessages([
                'horario' => ['Este horário já atingiu a capacidade máxima de doadores.']
            ]);
        }

        // 2.3 — Já tem agendamento ativo
        $existe = Agendamento::where('id_doador', $doador->id_doador)
            ->where('status', 'Agendado')
            ->exists();

        if ($existe) {
            throw ValidationException::withMessages([
                'global' => ['Você já tem um agendamento ativo. Conclua ou cancele antes de agendar outro.']
            ]);
        }
    // 🔹 Recuperar o último agendamento do doador (se houver)
    $ultimoAgendamento = Agendamento::where('id_doador', $doador->id_doador)
        ->where('status', 'Concluido')
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
    $agendamento = Agendamento::create([
        'id_doador' => $doador->id_doador,
        'id_centro' => $request->id_centro,
        'data_agendada' => $request->data_agendada,
        'horario' => $request->horario,
        'status' => 'Agendado'
    ]);

    
        \Log::info("Chamando AgendamentoNotification para centro {$centro->id_user}");

            
        $centroUser = User::find($agendamento->centro->id_user); 
        $centroUser->notify(new AgendamentoNotification($agendamento)); 

    

    return response()->json([
        'success' => true,
        'agendamento_id' => $agendamento->id_agendamento
    ]);
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
