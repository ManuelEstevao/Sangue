<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use Illuminate\Support\Facades\Auth;
use App\Models\Doacao;
use App\Models\Doador;

class DashDadorController extends Controller
{

    public function index()
    {
        // Obtém o usuário autenticado
        $user = Auth::user();
        $doador = Doador::where('id_user', $user->id_user)->first();

       // Obtém a próxima doação agendada
       $proximaDoacao = Agendamento::where('id_doador', $doador->id_doador)
       ->where('data_agendada', '>', now())
       ->orderBy('data_agendada', 'asc')
       ->first();

   // Obtém o total de doações realizadas e a data da última doação
   $totalDoacoes = Doacao::where('id_doador', $doador->id_doador)->count();
   $ultimaDoacao = Doacao::where('id_doador', $doador->id_doador)
       ->orderBy('data_doacao', 'desc')
       ->first();

   // Obtém os últimos agendamentos
   $agendamentos = Agendamento::where('id_doador', $doador->id_doador)
       ->orderBy('data_agendamento', 'desc')
       ->paginate(10);

        // Retorna a view com os dados
        return view('dador.dashbord', [
            'proximaDoacao' => $proximaDoacao,
            'totalDoacoes' => $totalDoacoes,
            'ultimaDoacao' => $ultimaDoacao ? $ultimaDoacao->data_agendada : null,
            'agendamentos' => $agendamentos,
        ]);
    }
}
