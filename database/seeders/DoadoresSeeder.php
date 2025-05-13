<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Doador;
use App\Models\Centro;
use App\Models\Agendamento;
use App\Models\Doacao;
use App\Models\Estoque;
use Carbon\Carbon;

class DoadoresSeeder extends Seeder
{
    public function run()
    {
        $doisMesesAtras = Carbon::now()->subMonths(2);
        $nomes = [
            'Maria Fernandes', 'João Santos', 'Ana Pereira', 
            'Carlos Oliveira', 'Sofia Costa', 'Pedro Martins', 
            'Luísa Rodrigues', 'Miguel Sousa'
        ];

        // Cria 8 doadores com usuários
        foreach ($nomes as $index => $nome) {
            // Cria usuário
            $user = User::create([
                'email' => strtolower(str_replace(' ', '.', $nome)).'@gmail.com',
                'password' => Hash::make('11111111'),
                'tipo_usuario' => 'doador',
                'created_at' => $doisMesesAtras,
                'updated_at' => $doisMesesAtras
            ]);

            // Cria doador
            $doador = Doador::create([
                'numero_bilhete' => '123456789LA'.str_pad($index+1, 2, '0', STR_PAD_LEFT),
                'nome' => $nome,
                'data_nascimento' => Carbon::now()->subYears(25 + $index)->format('Y-m-d'),
                'genero' => ($index % 2 == 0) ? 'Feminino' : 'Masculino',
                'tipo_sanguineo' => $this->getTipoSanguineo($index),
                'telefone' => '91234567'.str_pad($index+1, 2, '0', STR_PAD_LEFT),
                'peso' => 65.5 + $index,
                'id_user' => $user->id_user,
                'data_cadastro' => $doisMesesAtras,
            
            ]);

            // Cria agendamento e doação
            $centroId = ($index < 3) ? 1 : 4; // Primeiros 3 no centro 1, restantes no 4

            $agendamento = Agendamento::create([
                'data_agendada' => $doisMesesAtras->format('Y-m-d'),
                'horario' => ($index < 3) ? '09:00' : '14:00',
                'id_doador' => $doador->id_doador,
                'id_centro' => $centroId,
                'status' => 'Concluido',
                'created_at' => $doisMesesAtras,
                'updated_at' => $doisMesesAtras
            ]);

            $doacao = Doacao::create([
                'hemoglobina' => 13.5 + ($index * 0.1),
                'pressao_arterial' => (120 + $index).'/'.(80 + $index),
                'volume_coletado' => 450,
                'nome_profissional' => 'Enf. '.$this->getNomeEnfermeiro($index),
                'status' => 'Aprovado',
                'id_agendamento' => $agendamento->id_agendamento,
                'data_doacao' => $doisMesesAtras,
                
            ]);

            // Atualiza estoque
            Estoque::updateOrCreate(
                [
                    'id_centro' => $centroId,
                    'tipo_sanguineo' => $doador->tipo_sanguineo
                ],
                [
                    'quantidade' => \DB::raw("quantidade + 1"),
                    'ultima_atualizacao' => $doisMesesAtras
                ]
            );
        }
    }

    private function getTipoSanguineo($index)
    {
        $tipos = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        return $tipos[$index];
    }

    private function getNomeEnfermeiro($index)
    {
        $nomes = ['Catarina', 'Manuel', 'Beatriz', 'Rui', 'Inês', 'Hugo', 'Marta', 'Gonçalo'];
        return $nomes[$index];
    }
}