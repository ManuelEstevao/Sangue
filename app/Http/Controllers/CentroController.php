<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Centro; 
use App\Models\Doacao; 
use App\Models\User;
use App\Models\Agendamento;
use App\Models\Campanha;
use App\Models\Solicitacao;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CentroController extends Controller
{
    /**
     * Exibe o dashboard do centro de coleta.
     */
    public function index()
    {
        $centro = Auth::user()->centro;
    
        $data = [
            'agendamentosHoje' => Agendamento::where('id_centro', $centro->id_centro)
                ->whereDate('data_agendada', today())
                ->count(),
    
            'totalDoacoes' => Doacao::where('id_centro', $centro->id_centro)
                ->count(),
    
            'totalCampanhas' => Campanha::where('id_centro', $centro->id_centro)
                ->count(),
    
            'distribuicaoTipos' => Doacao::join('doador', 'doacao.id_doador', '=', 'doador.id_doador')
                ->selectRaw('doador.tipo_sanguineo, count(*) as total')
                ->where('doacao.id_centro', $centro->id_centro)
                ->groupBy('doador.tipo_sanguineo')
                ->get(),
    
                        
            'doacoesMensais' => Doacao::selectRaw("DATE_FORMAT(data_doacao, '%Y-%m') as mes, count(*) as total")
            ->where('id_centro', $centro->id_centro)
            ->where('data_doacao', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function($item) {
                $item->mes_formatado = \Carbon\Carbon::createFromFormat('Y-m', $item->mes)->format('M');
                return $item;
            }),
    
            'statusAgendamentos' => Agendamento::selectRaw('status, count(*) as total')
                ->where('id_centro', $centro->id_centro)
                ->groupBy('status')
                ->get()
        ];
    
        return view('centro.Dashbord', $data);
    }
    public function showRegistrationForm()
    {
        return view('centro.registo');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'endereco' => 'required|string|max:200',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'capacidade'=> 'required|numeric',
            'telefone'=> 'required',
        ]);

        // Cria usuário com tipo correto
        $user = User::create([
            'tipo_usuario' => 'centro', 
            'email' => $request->email,
            'password' => Hash::make($request->password)
    ]);

    // Cria centro com relacionamento
    $centro = new Centro([
        'nome' => $request->nome,
        'endereco' => $request->endereco,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'capacidade' => $request->capacidade_maxima,
        'telefone'=>$request->telefone
    ]);
    
    $user->centro()->save($centro);

    Auth::login($user);
    return redirect()->route('centro.Dashbord')
        ->with('success', 'Centro registrado com sucesso!');
        
}
public function relatorio()
{
    $centro = Auth::user()->centro;
    
    // Dados de doações por tipo sanguíneo
    $doacoesPorSangue = Doacao::join('doador', 'doacao.id_doador', '=', 'doador.id_doador')
    ->selectRaw('doador.tipo_sanguineo, COUNT(*) as total')
    ->where('doacao.id_centro', $centro->id_centro)
    ->groupBy('doador.tipo_sanguineo')
    ->get();


    // Dados de doações nos últimos 7 dias
    $doacoesPorDia = Doacao::selectRaw('DATE(data_doacao) as dia, COUNT(*) as total')
        ->where('id_centro', $centro->id_centro)
        ->where('data_doacao', '>=', Carbon::now()->subDays(7))
        ->groupBy('dia')
        ->orderBy('dia', 'asc')
        ->get();

    return view('centro.relatorio', [
        'doacoesPorSangue' => $doacoesPorSangue,
        'doacoesPorDia' => $doacoesPorDia
    ]);
}
}

