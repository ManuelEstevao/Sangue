<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use Illuminate\Support\Facades\Auth;
use App\Models\Doacao;
use App\Models\Centro;
use App\Models\Doador;
use Carbon\Carbon; 

class DashDadorController extends Controller
{

    public function index()
    {
        // Obtém o usuário autenticado
        $user = Auth::user();
        $doador = Doador::where('id_user', $user->id_user)->first();
        
        // Se o usuário ainda não é um doador, evita erro
        if (!$doador) {
            return redirect()->route('home')->with('error', 'Doador não encontrado.');
        }

        $centros = Centro::all();

        // Obtém a próxima doação agendada
        $proximaDoacao = Agendamento::where('id_doador', $doador->id_doador)
        ->where('data_agendada', '>', now())
        ->where('status', 'agendado')
        ->orderBy('data_agendada', 'asc')
        ->first();
        // Calcula o tempo restante para a próxima doação, se houver
        $tempoRestante = $proximaDoacao ? Carbon::parse($proximaDoacao->data_agendada)->diffForHumans(now(), true) : null;

        // Obtém o total de doações realizadas
        $totalDoacoes = Doacao::where('id_doador', $doador->id_doador)
            ->where('status', 'Concluído') // Filtra apenas doações concluídas
            ->count();

        // Obtém a última doação realizada
        $ultimaDoacao = Doacao::where('id_doador', $doador->id_doador)
            ->where('status', 'Concluído')
            ->orderBy('data_doacao', 'desc')
            ->first();

        // Definir a próxima data possível de doação com base na última
        $proximaDataPermitida = null;
        if ($ultimaDoacao) {
            $proximaDataPermitida = Carbon::parse($ultimaDoacao->data_doacao)->addDays(90); // 3 meses depois
        }

        // Obtém os últimos agendamentos do usuário
        $agendamentos = Agendamento::where('id_doador', $doador->id_doador)
            ->orderBy('data_agendada', 'desc')
            ->paginate(2);

        // Retorna a view com os dados
        return view('dador.dashbord', [
            'proximaDoacao' => $proximaDoacao,
            'totalDoacoes' => $totalDoacoes,
            'ultimaDoacao' => $ultimaDoacao ? $ultimaDoacao->data_doacao : null,
            'proximaDataPermitida' => $proximaDataPermitida,
            'agendamentos' => $agendamentos,
            'centros' => $centros,
            'tempoRestante' => $tempoRestante,
        ]);
    }
}
