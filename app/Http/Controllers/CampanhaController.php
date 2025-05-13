<?php

namespace App\Http\Controllers;

use App\Models\Campanha;
use App\Models\User;
use App\Models\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CampanhaController extends Controller
{
    public function index()
    {
        $centroId = Auth::user()->centro->id_centro;
        $campanhas = Campanha::where('id_centro', $centroId)
            ->orderBy('data_inicio', 'desc')
            ->paginate(10);

        return view('centro.campanha', compact('campanhas'));
    }

    public function show(Campanha $campanha)
    {
        $campanha->load('centro');
        return view('detalhe', compact('campanha'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date_format:d/m/Y',
            'data_fim' => 'required|date_format:d/m/Y|after_or_equal:data_inicio',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
            'endereco' => 'nullable|string|max:200',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Converter datas para formato MySQL
        $dataInicio = Carbon::createFromFormat('d/m/Y', $request->data_inicio)->format('Y-m-d');
        $dataFim = Carbon::createFromFormat('d/m/Y', $request->data_fim)->format('Y-m-d');

        // Upload da imagem
        $caminhoFoto = null;
        if ($request->hasFile('foto')) {
            $caminhoFoto = $request->file('foto')->store('campanhas', 'public');
        }

        Campanha::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim,
            'endereco' => $request->endereco,
            'foto' => $caminhoFoto,
            'id_centro' => Auth::user()->centro->id_centro
        ]);

        return redirect()->route('campanhas.index')->with('success', 'Campanha criada com sucesso!');
    }

    public function edit($id)
    {
        $campanha = Campanha::findOrFail($id);
        return response()->json($campanha);
    }
    


    public function update(Request $request, Campanha $campanha)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date_format:d/m/Y',
            'data_fim' => 'required|date_format:d/m/Y|after_or_equal:data_inicio',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $updateData = [
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'data_inicio' => Carbon::createFromFormat('d/m/Y', $request->data_inicio),
            'data_fim' => Carbon::createFromFormat('d/m/Y', $request->data_fim),
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim
        ];

        if ($request->hasFile('foto')) {
            if ($campanha->foto) {
                Storage::disk('public')->delete($campanha->foto);
            }
            $updateData['foto'] = $request->file('foto')->store('campanhas', 'public');
        }

        $campanha->update($updateData);

        return redirect()->route('campanhas.index')
            ->with('success', 'Campanha atualizada com sucesso!');
    }

    public function destroy(Campanha $campanha)
    {
        if ($campanha->foto) {
            Storage::disk('public')->delete($campanha->foto);
        }
        $campanha->delete();
        
        return back()->with('success', 'Campanha excluída com sucesso!');
    }

}