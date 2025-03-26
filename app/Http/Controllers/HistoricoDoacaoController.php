<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doacao;
use App\Models\Doador;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


class HistoricoDoacaoController extends Controller
{
    public function index()
    {
        // Obtém o usuário autenticado
        $user = Auth::user();

        // Recupera o perfil do doador relacionado ao usuário
        $doador = Doador::where('id_user', $user->id_user)->first();

        // Busca as doações deste doador, ordenadas pela data (mais recentes primeiro)
        $doacoes = Doacao::where('id_doador', $doador->id_doador)
                          ->orderBy('data_doacao', 'desc')
                          ->paginate(10);

        return view('dador.historico', compact('doacoes'));
    }

    

    public function show($doadorId): JsonResponse
    {
        try {
            $doador = Doador::with('doacoes')->findOrFail($doadorId);
            
            return response()->json([
                'success' => true,
                'doador' => $doador
            ]);
    
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Doador não encontrado'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }
    
}
