<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Agendamento;
use App\Models\Solicitacao;
use App\Models\Doador;
use App\Models\Campanha;
use App\Models\Centro;
use App\Models\User;
use App\Models\Doacao;
use App\Models\Estoque;
use App\Models\Notificacao;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Métricas principais
    $metrics = [
        'total_doadores' => Doador::count(),
        'total_centros' => Centro::count(),
        'total_doacoes' => Doacao::count(),
        'total_agendamentos' => Agendamento::count(),
        'total_campanhas' => Campanha::count(),
        'solicitacoes_atendidas' => Solicitacao::where('status', 'atendida')->count()
    ];

    // Dados para gráficos
    $chartData = [
        'blood_types' => Doador::selectRaw('tipo_sanguineo, COUNT(*) as total')
            ->groupBy('tipo_sanguineo')
            ->get(),
            
        'center_donations' => Centro::withCount(['agendamentos as doacoes_count' => function($query) {
            $query->whereHas('doacao');
        }])->get(),
        
        'donors_per_month' => Doador::selectRaw('
            DATE_FORMAT(data_cadastro, "%b %Y") as label,
            DATE_FORMAT(data_cadastro, "%Y-%m") as month_year,
            COUNT(*) as total')
        ->groupByRaw('month_year, label') 
        ->orderByRaw('month_year')
        ->get()
    ];


    return view('ADM.dashbord', [
        'metrics' => $metrics,
        'chartData' => $chartData
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   

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
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $doador = Doador::with('user')->findOrFail($id);

        // Validação
        $validated = $request->validate([
            'nome'            => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'genero'          => 'required|in:Masculino,Feminino',
            'tipo_sanguineo'  => 'required',
            'telefone'        => 'required|string|max:16',
            'email'           => 'required|email|unique:users,email,'.$doador->user->id_user.',id_user',
            'password'        => 'nullable',
        ]);

        // Atualiza dados do doador
        $doador->update([
            'nome'            => $validated['nome'],
            'data_nascimento' => $validated['data_nascimento'],
            'genero'          => $validated['genero'],
            'tipo_sanguineo'  => $validated['tipo_sanguineo'],
            'telefone'        => $validated['telefone'],
        ]);

        // Atualiza email/senha do usuário
        $user = $doador->user;
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return redirect()
            ->route('listaD')
            ->with('success', 'Doador atualizado com sucesso!');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
