<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiaBloqueado;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DiaBloqueadoController extends Controller
{

    public function index(Request $request, $centroId)
{
    try {
        $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date'
        ]);

        // Converter para UTC
        $start = $request->start 
        ? Carbon::parse($request->start)->setTimezone(config('app.timezone'))->startOfDay()->utc()
        : now()->subMonth()->startOfDay()->utc();

    $end = $request->end 
        ? Carbon::parse($request->end)->setTimezone(config('app.timezone'))->endOfDay()->utc()
        : now()->addMonth()->endOfDay()->utc();

        $dias = DiaBloqueado::where('id_centro', $centroId)
            ->whereBetween('data', [$start, $end])
            ->get()
            ->map(function ($dia) {
                return [
                    'id' => $dia->id_bloqueio,
                    'title' => $dia->motivo,
                    'start' => $dia->data->format('Y-m-d'),
                    'allDay' => true,
                    'extendedProps' => [
                        'tipo' => 'bloqueio'
                    ]
                ];
            });

        return response()->json($dias);

    } catch (\Exception $e) {
        \Log::error('Erro dias bloqueados: ' . $e->getMessage());
        return response()->json([
            'error' => 'Erro interno',
            'details' => $e->getMessage()
        ], 500);
    }
}
    /**
     * Armazena um novo dia bloqueado
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_centro' => 'required|exists:centro,id_centro',
            'data' => 'required|date|after:today',
            'motivo' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $diaBloqueado = DiaBloqueado::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Dia bloqueado com sucesso!',
                'data' => $diaBloqueado
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao bloquear dia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lista dias bloqueados de um centro
     */
    public function getDiasBloqueados($centroId)
    {
        $dias = DiaBloqueado::where('id_centro', $centroId)
            ->get(['data', 'motivo']);

        return response()->json([
            'success' => true,
            'data' => $dias
        ]);
    }

    public function verificarDataBloqueada(Request $request)
{
    $validator = Validator::make($request->all(), [
        'centro' => 'required|integer|exists:centro,id_centro',
        'data' => 'required|date_format:Y-m-d'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $data = Carbon::createFromFormat('Y-m-d', $request->data)->setTimezone('UTC');

        $bloqueado = DiaBloqueado::where('id_centro', $request->centro)
            ->whereDate('data', $data)
            ->exists();

        return response()->json([
            'success' => true,
            'bloqueado' => $bloqueado,
            'motivo' => $bloqueado ? DiaBloqueado::where('id_centro', $request->centro)
                                    ->whereDate('data', $data)
                                    ->value('motivo') : null
        ]);

    } catch (\Exception $e) {
        \Log::error("Erro verificação data bloqueada: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Formato de data inválido'
        ], 400);
    }
}

    /**
     * Remove um bloqueio
     */
    public function destroy($id)
    {
        try {
            $bloqueio = DiaBloqueado::findOrFail($id);
            
            // Verifica se o centro é o dono do bloqueio
            if ($bloqueio->id_centro != auth()->user()->centro->id_centro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ação não permitida'
                ], 403);
            }
    
            $bloqueio->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Dia desbloqueado com sucesso'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao desbloquear: ' . $e->getMessage()
            ], 500);
        }
    }
}