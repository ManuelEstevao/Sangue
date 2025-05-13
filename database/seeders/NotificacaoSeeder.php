<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notificacao;
use App\Models\User;
use Carbon\Carbon;

class NotificacaoSeeder extends Seeder
{
    public function run()
    {
        // Tipos e status permitidos
        $tipos = ['agendamento', 'campanha', 'lembrete', 'emergencia', 'nao_compareceu'];
        $statuses = ['pendente', 'enviada', 'falha'];

        // Obter todos os usuários
        $users = User::all();

        // Criar 10 notificações para cada usuário
        foreach ($users as $user) {
            for ($i = 0; $i < 10; $i++) {
                Notificacao::create([
                    'mensagem' => $this->gerarMensagem($tipos[array_rand($tipos)]),
                    'tipo' => $tipos[array_rand($tipos)],
                    'status' => $statuses[array_rand($statuses)],
                    'lida' => rand(0, 1),
                    'email_enviado' => rand(0, 1),
                    'data_envio' => Carbon::now()->subDays(rand(0, 30)),
                    'id_user' => $user->id
                ]);
            }
        }
    }

    private function gerarMensagem($tipo)
    {
        $mensagens = [
            'agendamento' => [
                'Agendamento confirmado para 15/06 às 14h30',
                'Reagendamento necessário - verifique novas datas',
                'Confirmação de doação no Hemocentro Central'
            ],
            'campanha' => [
                'Nova campanha de doação: Doe em Junho e salve vidas!',
                'Campanha "Sangue Solidário" começa na próxima semana'
            ],
            'lembrete' => [
                'Você pode doar novamente a partir de 10/06',
                'Lembrete: intervalo mínimo entre doações é de 60 dias'
            ],
            'emergencia' => [
                'URGENTE: Tipo O- em falta no Hospital Municipal',
                'Emergência: Necessidade crítica de plaquetas'
            ],
            'nao_compareceu' => [
                'Atenção: seu último agendamento não foi comparecido',
                'Por favor, reagende se não puder comparecer'
            ]
        ];

        return $mensagens[$tipo][array_rand($mensagens[$tipo])];
    }
}