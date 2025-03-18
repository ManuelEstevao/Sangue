<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doacao;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PerfilController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $doador = $usuario->doador;

        // Buscar informações sobre doações
        $totalDoacoes = Doacao::where('id_doador', $doador->id_doador)->count();
        $ultimaDoacao = Doacao::where('id_doador', $doador->id_doador)->latest('data_doacao')->first();

        return view('dador.perfil', compact('doador', 'totalDoacoes', 'ultimaDoacao'));
    }

    public function update(Request $request)
{
    $validated = $request->validate([
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'telefone' => 'required|string|max:20',
    ]);

    $doador = Auth::user()->doador;

    if ($request->hasFile('foto')) {
        $path = $request->file('foto')->store('fotos', 'public');
        $validated['foto'] = $path;
        
        // Remove foto antiga se existir
        if ($doador->foto) {
            Storage::disk('public')->delete($doador->foto);
        }
    }

    $doador->update($validated);

    return redirect()->back()->with('success', 'Perfil atualizado com sucesso!');
}
}
