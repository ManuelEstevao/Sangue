<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Centro;
use App\Models\Campanha;
use App\Models\Doador;
use Illuminate\Support\Facades\Auth;


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
        // Busca todos os centros/centros disponíveis
        $centros = Centro::all();

        // Busca as campanhas ativas (opcional)
        $campanhas = Campanha::where('data_inicio', '<=', now())
                             ->where('data_fim', '>=', now())
                             ->get();

        return view('dador.agendamento', compact('centros', 'campanhas'));
    }

    /**
     * Processa o formulário de agendamento e cria um novo registro.
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'data_agendada' => 'required|date|after:now',
            'id_centro'   => 'required|exists:centro,id_centro',
            'id_campanha'      => 'nullable|exists:campanha,id_campanha',
        ]);

        // Obtém o usuário autenticado e seu perfil de doador
        $user = Auth::user();
        $doador = Doador::where('id_user', $user->id_user)->first();

        // Cria o agendamento utilizando os dados enviados
        Agendamento::create([
            'id_doador'        => $doador->id_doador,
            'id_centro'   => $request->id_centro,
            'id_campanha'      => $request->id_campanha,
            'data_agendada' => $request->data_agendada,
            'status'           => 'Agendado',
        ]);

        return redirect()->route('dador.agendamento')->with('success', 'Agendamento realizado com sucesso!');
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
