<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doacao;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $doador = $usuario->doador;

        // Buscar informações sobre doações
        $totalDoacoes = Doacao::whereHas('agendamento', function($query) use ($doador) {
            $query->where('id_doador', $doador->id_doador);
        })->count();

        $ultimaDoacao = Doacao::whereHas('agendamento', function($query) use ($doador) {
            $query->where('id_doador', $doador->id_doador);
        })->latest('data_doacao')->first();

        return view('dador.perfil', compact('doador', 'totalDoacoes', 'ultimaDoacao'));
    }

    public function update(Request $request)
{
    $validated = $request->validate([
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'telefone' => 'required|string|max:20',
        'endereco'=> 'required',
    ]);

    $doador = Auth::user()->doador;

    if ($request->hasFile('foto')) {
        // Deletar foto antiga se existir
        if ($doador->foto) {
            Storage::disk('public')->delete('fotos/' . $doador->foto);
        }

        // Armazenar nova foto
        $fileName = time() . '_' . $request->file('foto')->getClientOriginalName();
        $path = $request->file('foto')->storeAs(
            'fotos',
            $fileName,
            'public'
        );
        
        $validated['foto'] = $fileName;
    }

    $doador->update($validated);

    return redirect()->back()->with('success', 'Perfil atualizado com sucesso!');
}
}
