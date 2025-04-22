<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Centro; 
use App\Models\Doacao; 
use App\Models\User;
use App\Models\Doador;
use App\Models\Agendamento;
use App\Models\Campanha;
use App\Models\Solicitacao;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class CentroController extends Controller
{
    /**
     * Exibe o dashboard do centro de coleta.
     */
    public function index()
    {
        $centro = Auth::user()->centro;
    
        $data = [
            'agendamentosHoje' => $centro->agendamentos()
                ->whereDate('data_agendada', today())
                ->count(),
    
            'totalDoacoes' => $centro->doacoes()->count(),
    
            'totalCampanhas' => $centro->campanhas()->count(),
    
            'distribuicaoTipos' => $centro->agendamentos()
                ->with('doador') 
                ->where('status', 'Concluido')
                ->get() 
                ->groupBy('doador.tipo_sanguineo') 
                ->map(function ($group, $tipo) {
                    return [
                        'tipo' => $tipo, 
                        'total' => $group->count() 
                    ];
                })->values(),
    
           'doacoesMensais' => DB::table('doacao')
                ->join('agendamento', 'doacao.id_agendamento', '=', 'agendamento.id_agendamento')
                ->selectRaw("DATE_FORMAT(doacao.data_doacao, '%Y-%m') as mes, COUNT(*) as total")
                ->where('agendamento.id_centro', $centro->id_centro)
                ->where('doacao.data_doacao', '>=', now()->subMonths(6))
                ->groupBy('mes')
                ->orderBy('mes')
                ->get()
                ->map(function($item) {
                    $item->mes_formatado = Carbon::createFromFormat('Y-m', $item->mes)->format('M Y');
                    return $item;
            }),
    
            'statusAgendamentos' => $centro->agendamentos()
                ->selectRaw('status, count(*) as total')
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
    $validatedData = $request->validate([
        'nome' => 'required',
        'email' => 'required',
        'password' => 'required',
        'endereco' => 'required',
        'latitude' => 'required',
        'longitude' => 'required',
        'capacidade_maxima' => 'required',
        'telefone' => 'required',
        'horario_abertura' => 'required',
        'horario_fechamento' => 'required'
    ]);

    // Cria usuário
    $user = User::create([
        'tipo_usuario' => 'centro', 
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password'])
    ]);


    // Cria centro com relacionamento
    $centro = new Centro([
        'nome' => $validatedData['nome'],
        'endereco' => $validatedData['endereco'],
        'latitude' => $validatedData['latitude'],
        'longitude' => $validatedData['longitude'],
        'capacidade_maxima' => $validatedData['capacidade_maxima'],
        'telefone' => $validatedData['telefone'],
        'horario_abertura' => $validatedData['horario_abertura'],
        'horario_fechamento' => $validatedData['horario_fechamento'],
        'id_user' => $user->id_user
    ]);

    $centro->save();

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

private function filtrarDoadores(Request $request)
{
    $centroId = auth()->user()->Centro->id_centro;

    return Doador::whereHas('doacoes', function ($query) use ($centroId) {
            $query->where('id_centro', $centroId);
        })
        ->when($request->search, function ($query) use ($request) {
            $query->where('nome', 'LIKE', "%{$request->search}%");
        })
        ->when($request->tipo_sanguineo, function ($query) use ($request) {
            $query->where('tipo_sanguineo', $request->tipo_sanguineo);
        })
        ->with(['doacoes' => function ($query) use ($centroId) {
            $query->where('id_centro', $centroId)->latest('data_doacao');
        }, 'user']);
}


public function listarDoadores(Request $request)
{
    $doadores = $this->filtrarDoadores($request)
                     ->paginate(8)
                     ->appends($request->query());

    return view('centro.doador', compact('doadores'));
}


public function exportarPdf(Request $request)
{
    $centro = auth()->user()->Centro;
    $doadores = $this->filtrarDoadores($request)->get();

    $pdf = Pdf::loadView('centro.pdf.doador', compact('doadores', 'centro'));
    return $pdf->download('Lista de doadores.pdf');
}



}

