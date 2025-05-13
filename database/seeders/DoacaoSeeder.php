<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoacaoSeeder extends Seeder
{
    public function run(): void
    {
        // Pega o agendamento que criamos
        $agendamentoId = DB::table('agendamento')->orderBy('id_agendamento')->value('id_agendamento');

        // Insere a doação no dia seguinte ao agendamento
        DB::table('doacao')->insert([
            'hemoglobina'       => 14.2,
            'pressao_arterial'  => '12/8',
            'volume_coletado'   => 450,
            'observacoes'       => 'Sem intercorrências',
            'nome_profissional' => 'Dr. Joaquim Lopes',
            'status'            => 'Aprovado',
            'data_doacao'       => now()->subDays(9)->toDateTimeString(),
            'id_agendamento'    => $agendamentoId,
        ]);
    }
}
