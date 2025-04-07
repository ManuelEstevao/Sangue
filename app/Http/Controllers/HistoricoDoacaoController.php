<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doacao;
use App\Models\Doador;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; 


class HistoricoDoacaoController extends Controller
{
    public function index()
    {
        // Obtém o usuário autenticado
        $user = Auth::user();

        // Recupera o perfil do doador relacionado ao usuário
        $doador = Doador::where('id_user', $user->id_user)->first();

        // Busca as doações deste doador, ordenadas pela data (mais recentes primeiro)
        $doacoes = Doacao::whereHas('agendamento', function($query) use ($doador) {
            $query->where('id_doador', $doador->id_doador);
        })
        ->orderBy('data_doacao', 'desc')
        ->paginate(10);

        return view('dador.historico', compact('doacoes'));
    }

    

    public function show($doadorId): JsonResponse
{
    try {
        $doador = Doador::with(['doacoes' => function($query) {
            $query->orderBy('data_doacao', 'desc');
        }])->findOrFail($doadorId);

        // Corrigindo URL da foto
        $fotoPath = "fotos/{$doador->foto}"; 
        $doador->foto_url = $doador->foto && Storage::disk('public')->exists($fotoPath)
            ? asset("storage/{$fotoPath}")
            : asset('assets/img/profile.png');

        return response()->json([
            'success' => true,
            'doador' => [
                'id_doador' => $doador->id_doador,
                'nome' => $doador->nome,
                'tipo_sanguineo' => $doador->tipo_sanguineo,
                'foto_url' => $doador->foto_url,
                'doacoes' => $doador->doacoes->map(function($doacao) {
                    // Corrigindo formatação de data
                    return [
                        'volume_coletado' => $doacao->volume_coletado,
                        'status' => $doacao->status,
                        'hemoglobina' => $doacao->hemoglobina,
                        'data_formatada' => $doacao->data_formatada, 
                        'observacoes' => $doacao->observacoes,
                        'nome_profissional' => $doacao->nome_profissional,
                        'pressao_arterial'=> $doacao->pressao_arterial

                    ];
                })
            ]
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