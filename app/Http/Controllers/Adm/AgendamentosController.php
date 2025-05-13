<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use Illuminate\Http\Request;

class AgendamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Agendamento::with(['doador', 'centro']);

        if ($request->filled('data_inicio')) {
            $query->where('data_agendada', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->where('data_agendada', '<=', $request->data_fim);
        }
        if ($request->filled('doador')) {
            $query->whereHas('doador', fn($q) =>
                $q->where('nome', 'like', '%' . $request->doador . '%')
            );
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $agendamentos = $query
            ->orderBy('data_agendada', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('ADM.agendamento', compact('agendamentos'));
    }

    /**
     * Atualiza o status de um agendamento.
     */
    public function updateStatus(Request $request, Agendamento $agendamento)
    {
        $request->validate([
            'status' => 'required|in:Agendado,Comparecido,Não Compareceu,Cancelado,Concluido'
        ]);

        $agendamento->status = $request->status;
        $agendamento->save();

        return back()->with('success', 'Status atualizado com sucesso.');
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
