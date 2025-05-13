<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash; 

class AgendamentoSeeder extends Seeder
{
    public function run(): void
    {
        // Pega o primeiro doador
        $doadorId = DB::table('doador')->orderBy('id_doador')->value('id_doador');

        // Insere um agendamento 10 dias atrás, no centro de ID = 1 (você disse que criará os centros)
        DB::table('agendamento')->insert([
            'data_agendada' => now()->subDays(10)->toDateString(),
            'horario'       => '09:30:00',
            'id_doador'     => $doadorId,
            'id_centro'     => 1,
            'status'        => 'Concluido',
            'created_at'    => now()->subDays(11)->toDateTimeString(),
            'updated_at'    => now()->subDays(10)->toDateTimeString(),
        ]);
    }
}
