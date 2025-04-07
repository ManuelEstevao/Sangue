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
            ->whereDate('data_agendada', '>=', now()->toDateString()) 
            ->where('status', 'Agendado')
            ->orderBy('data_agendada', 'asc')
            ->first();


           
            

        // Doações através do relacionamento com agendamento
        $doacoesQuery = Doacao::whereHas('agendamento', function($query) use ($doador) {
            $query->where('id_doador', $doador->id_doador);
        });

        // Total de doações aprovadas
        $totalDoacoes = $doacoesQuery->clone()
            ->where('status', 'Aprovado')
            ->count();

        // Obtém a última doação realizada
        $ultimaDoacao = $doacoesQuery->clone()
            ->where('status', 'Aprovado')
            ->orderByDesc('data_doacao')
            ->first();

        // 🔹 Definir intervalo mínimo baseado no gênero
        $intervaloMinimo = ($doador->genero == 'Masculino') ? 90 : 120;

        // Definir a próxima data possível de doação com base na última
        $proximaDataPermitida = $ultimaDoacao ? Carbon::parse($ultimaDoacao->data_doacao)->addDays($intervaloMinimo) : null;

        // Obtém os últimos agendamentos do usuário
        $agendamentos = Agendamento::where('id_doador', $doador->id_doador)
            ->latest('data_agendada') 
            ->paginate(2);
            

        // Retorna a view com os dados
        return view('dador.dashbord', [
            'proximaDoacao' => $proximaDoacao,
            'totalDoacoes' => $totalDoacoes,
            'ultimaDoacao' => $ultimaDoacao ? $ultimaDoacao->data_doacao : null,
            'proximaDataPermitida' => $proximaDataPermitida,
            'agendamentos' => $agendamentos,
            'centros' => $centros,
            //'tempoRestante' => $tempoRestante,
        ]);
    }
}
