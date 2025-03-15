<?php

use App\Models\Centro;
use App\Models\Agendamento;
use App\Models\Doador;
use Illuminate\Http\Request;

// Obter horários disponíveis para um centro
Route::get('/centros/{centro}/horarios', function (Centro $centro, Request $request) {
    $request->validate(['data' => 'required|date']);
    
    $horarios = [];
    $inicio = now()->parse('08:00');
    $fim = now()->parse('17:00');

    while ($inicio <= $fim) {
        $horario = $inicio->format('H:i');
        
        // Verificar capacidade
        $agendamentos = Agendamento::where([
            'id_centro' => $centro->id_centro,
            'data_agendada' => $request->data,
            'horario' => $horario
        ])->count();

        if ($agendamentos < $centro->capacidade_maxima) {
            $horarios[] = $horario;
        }

        $inicio->addMinutes(30);
    }

    return response()->json($horarios);
});

// Verificar conflito de agendamento por gênero
Route::get('/doadores/{doador}/conflito', function (Doador $doador) {
    $ultimaDoacao = $doador->agendamentos()->latest('data_agendada')->first();
    
    if (!$ultimaDoacao) return response()->json(['conflito' => false]);

    $intervalo = ($doador->genero == 'F') ? 90 : 60;
    $proximaData = $ultimaDoacao->data_agendada->addDays($intervalo);

    return response()->json([
        'conflito' => now()->lt($proximaData),
        'proxima_data' => $proximaData->format('d/m/Y')
    ]);
});