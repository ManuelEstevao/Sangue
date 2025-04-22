<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agendamento;
use App\Models\Notificacao;
use Carbon\Carbon;

class EnviarLembreteAgendamento extends Command
{
    protected $signature = 'lembrete:agendamentos';
    protected $description = 'Envia lembrete 1h antes do agendamento marcado para hoje';

    public function handle()
    {
        $now   = Carbon::now();
        $time1hLater = $now->copy()->addHour(2)->format('H:i:s');
    
        $agendamentos = Agendamento::whereDate('data_agendada', $now->toDateString())
            ->whereTime('horario', $timePlus2)
            ->get();
    
        foreach ($agendamentos as $agendamento) {
            // Evita notificações duplicadas para o mesmo agendamento+dia
            $jaNotificado = Notificacao::where('id_agendamento', $agendamento->id_agendamento)
                ->whereDate('data_envio', $now->toDateString())
                ->exists();
    
            if (! $jaNotificado) {
                Notificacao::create([
                    'id_agendamento' => $agendamento->id_agendamento,
                    'mensagem'       => "Seu agendamento de doação é às {$agendamento->horario} – lembrete 1h antes.",
                    'data_envio'     => $now,
                ]);
                $this->info("Lembrete enviado para agendamento ID {$agendamento->id_agendamento}.");
            }
        }
    }
}   