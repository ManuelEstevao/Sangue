<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DonorDonationStockSeeder extends Seeder
{
    public function run(): void
    {
        $twoMonthsAgo = Carbon::now()->subMonths(2);

        // 1) Cria 8 usuários tipo 'doador'
        $names = [
            'Carlos Pereira',
            'Ana Costa',
            'Miguel Gomes',
            'Beatriz Silva',
            'Rui Marques',
            'Helena Andrade',
            'Pedro Araujo',
            'Sofia Lima',
        ];

        $userIds = [];
        foreach ($names as $name) {
            $email = strtolower(str_replace(' ', '.', $name)) . '@gmail.com';
            $userIds[] = DB::table('users')->insertGetId([
                'tipo_usuario' => 'doador',
                'email'        => $email,
                'password'     => Hash::make(str_repeat('1', 8)), // senha '11111111'
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // 2) Cria registros em 'doador'
        $donorIds = [];
        foreach ($userIds as $idx => $userId) {
            $bilhete = 'BILH' . str_pad($idx + 1, 10, '0', STR_PAD_LEFT);
            $genero  = $idx % 2 === 0 ? 'Masculino' : 'Feminino';
            $tipoS   = ['A+', 'B+', 'O+', 'AB+', 'O-', 'A-', 'B-', 'AB-'][$idx % 8];
            $donorIds[] = DB::table('doador')->insertGetId([
                'numero_bilhete'  => $bilhete,
                'nome'            => $names[$idx],
                'data_nascimento' => $twoMonthsAgo->copy()->subYears(30 - $idx)->format('Y-m-d'),
                'genero'          => $genero,
                'tipo_sanguineo'  => $tipoS,
                'telefone'        => '923' . rand(100000, 999999),
                'peso'            => 65 + $idx,
                'endereco'        => "Av. Exemplo {$idx}, Luanda",
                'foto'            => null,
                'data_cadastro'   => $twoMonthsAgo->copy()->subDays($idx)->toDateTimeString(),
                'id_user'         => $userId,
            ]);
        }

        // 3) Garante que existam estoques para os centros 1 e 4 e inicializa quantidade em zero,
        //    mas já com 'ultima_atualizacao' há 2 meses.
        foreach ([1, 4] as $centroId) {
            foreach (['A+', 'B+', 'O+', 'AB+', 'O-', 'A-', 'B-', 'AB-'] as $tipo) {
                DB::table('estoque')->updateOrInsert(
                    ['id_centro' => $centroId, 'tipo_sanguineo' => $tipo],
                    [
                        'quantidade'         => 0,
                        'ultima_atualizacao' => $twoMonthsAgo->toDateTimeString(),
                    ]
                );
            }
        }

        // 4) Registra agendamento, doação e incrementa estoque; a data de todas as ações é $twoMonthsAgo.
        foreach ($donorIds as $i => $doadorId) {
            $centroId = $i < 3 ? 1 : 4; // 3 doações no centro 1, 5 no centro 4
            $tipoS    = ['A+', 'B+', 'O+', 'AB+', 'O-', 'A-', 'B-', 'AB-'][$i % 8];

            // Agendamento
            $agendamentoId = DB::table('agendamento')->insertGetId([
                'data_agendada' => $twoMonthsAgo->format('Y-m-d'),
                'horario'       => '09:00:00',
                'id_doador'     => $doadorId,
                'id_centro'     => $centroId,
                'status'        => 'Concluido',
                'created_at'    => $twoMonthsAgo,
                'updated_at'    => $twoMonthsAgo,
            ]);

            // Doação
            DB::table('doacao')->insert([
                'hemoglobina'       => 13.0 + ($i * 0.1),
                'pressao_arterial'  => '120/80',
                'volume_coletado'   => 450,
                'observacoes'       => null,
                'nome_profissional' => 'Dr. Exemplo',
                'status'            => 'Aprovado',
                'data_doacao'       => $twoMonthsAgo,
                'id_agendamento'    => $agendamentoId,
            ]);

            // Atualiza o estoque correspondente: incrementa quantidade e define última atualização
            DB::table('estoque')
                ->where('id_centro', $centroId)
                ->where('tipo_sanguineo', $tipoS)
                ->update([
                    'quantidade'         => DB::raw('quantidade + 1'),
                    'ultima_atualizacao' => $twoMonthsAgo->toDateTimeString(),
                ]);
        }
    }
}
