<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FevereiroSeeder extends Seeder
{
    public function run(): void
    {
        // Dados base para 4 doadores
        $doadores = [
            [
                'nome' => 'João Silva',
                'email' => 'joaosilva@example.com',
                'numero_bilhete' => '12345678BO901',
                'data_nascimento' => '1985-02-10',
                'genero' => 'Masculino',
                'tipo_sanguineo' => 'O+',
                'telefone' => '922000000',
                'peso' => 75.50,
                'endereco' => 'Rua das Acácias, Luanda',
            ],
            [
                'nome' => 'Maria Fernandes',
                'email' => 'mariafernandes@example.com',
                'numero_bilhete' => '23456789LA012',
                'data_nascimento' => '1990-02-15',
                'genero' => 'Feminino',
                'tipo_sanguineo' => 'A-',
                'telefone' => '923111111',
                'peso' => 62.00,
                'endereco' => 'Av. República, Luanda',
            ],
            [
                'nome' => 'Carlos Pereira',
                'email' => 'carlospereira@example.com',
                'numero_bilhete' => '34567890LA123',
                'data_nascimento' => '1978-02-20',
                'genero' => 'Masculino',
                'tipo_sanguineo' => 'B+',
                'telefone' => '924222222',
                'peso' => 80.00,
                'endereco' => 'Bairro Central, Luanda',
            ],
            [
                'nome' => 'Ana Gomes',
                'email' => 'anagomes@example.com',
                'numero_bilhete' => '45678901LA234',
                'data_nascimento' => '1995-02-25',
                'genero' => 'Feminino',
                'tipo_sanguineo' => 'AB-',
                'telefone' => '925333333',
                'peso' => 68.20,
                'endereco' => 'Km 30, Luanda',
            ],
        ];

        foreach ($doadores as $index => $dados) {
            // 1. Inserir na tabela users
            $idUser = DB::table('users')->insertGetId([
                'tipo_usuario'      => 'doador',
                'email'             => $dados['email'],
                'password'          => bcrypt('11111111'),
                'created_at'        => Carbon::create(2025, 2, 5 + $index, 9, 0, 0),
                'updated_at'        => Carbon::create(2025, 2, 5 + $index, 9, 0, 0),
            ]);

            // 2. Inserir na tabela doador
            $idDoador = DB::table('doador')->insertGetId([
                'numero_bilhete'    => $dados['numero_bilhete'],
                'nome'              => $dados['nome'],
                'data_nascimento'   => $dados['data_nascimento'],
                'genero'            => $dados['genero'],
                'tipo_sanguineo'    => $dados['tipo_sanguineo'],
                'telefone'          => $dados['telefone'],
                'peso'              => $dados['peso'],
                'endereco'          => $dados['endereco'],
                'data_cadastro'     => Carbon::create(2025, 2, 5 + $index, 9, 15, 0),
                'id_user'           => $idUser,
            ]);

            // 3. Inserir agendamento concluído no centro 2
            $idAgendamento = DB::table('agendamento')->insertGetId([
                'data_agendada'     => Carbon::create(2025, 2, 10 + $index, 10, 0, 0)->toDateString(),
                'horario'           => Carbon::create(2025, 2, 10 + $index, 10, 30, 0)->toTimeString(),
                'id_doador'         => $idDoador,
                'id_centro'         => 2,
                'status'            => 'Concluido',
                'created_at'        => Carbon::create(2025, 2, 10 + $index, 10, 30, 0),
                'updated_at'        => Carbon::create(2025, 2, 10 + $index, 10, 30, 0),
            ]);

            // 4. Inserir doação aprovada
            DB::table('doacao')->insert([
                'hemoglobina'       => 14.2,
                'pressao_arterial'  => '120/80',
                'volume_coletado'   => 450,
                'observacoes'       => null,
                'nome_profissional' => 'Dr. Silva',
                'status'            => 'Aprovado',
                'data_doacao'       => Carbon::create(2025, 2, 10 + $index, 11, 0, 0),
                'id_agendamento'    => $idAgendamento,
            ]);

            // 5. Inserir questionário com Angola = true
            DB::table('questionario')->insert([
                'id_agendamento'          => $idAgendamento,
                'ja_doou_sangue'          => false,
                'problema_doacao_anterior'=> false,
                'tem_doenca_cronica'      => false,
                'fez_tatuagem_ultimos_12_meses'=> false,
                'fez_cirurgia_recente'    => false,
                'esta_gravida'            => false,
                'recebeu_transfusao_sanguinea'=> false,
                'tem_doenca_infecciosa'   => false,
                'usa_medicacao_continua'  => false,
                'tem_comportamento_de_risco'=> false,
                'teve_febre_ultimos_30_dias'=> false,
                'consumiu_alcool_ultimas_24_horas'=> false,
                'teve_malaria_ultimos_3meses'=> false,
                'nasceu_ou_viveu_angola'  => true,
                'esteve_internado'        => false,
                'data_resposta'           => Carbon::create(2025, 2, 10 + $index, 11, 15, 0),
            ]);

            // 6. Incrementar estoque do centro 2 para o tipo sanguíneo
            DB::table('estoque')
                ->where('id_centro', 2)
                ->where('tipo_sanguineo', $dados['tipo_sanguineo'])
                ->increment('quantidade', 1);
        }
    }
}
