<?php

namespace App\Http\Controllers;
use App\Models\Agendamento;
use Illuminate\Http\Request;
use App\Models\Doador;

class AgendamentoCentroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agendamentos = Agendamento::with('doador')->get();

    return view('centro.agendamento', compact('agendamentos'));
    }
    public function historico($id)
{
    // Busca o histórico do doador (ex: agendamentos anteriores)
    $historico = Agendamento::where('doador_id', $id)
        ->where('status', 'concluído')
        ->get(['data_agendamento', 'tipo_doacao', 'status']);

    return response()->json($historico);
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
