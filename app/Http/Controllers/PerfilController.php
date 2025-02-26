<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doacao;

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
}
