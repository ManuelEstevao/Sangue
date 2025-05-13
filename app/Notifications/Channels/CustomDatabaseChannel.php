<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Models\Notificacao;
use App\Models\User;

class CustomDatabaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        \Log::info("CustomDatabaseChannel::send() acionado para user {$notifiable->id_user}");

        $data = method_exists($notification, 'toCustomDatabase')
            ? $notification->toCustomDatabase($notifiable)
            : $notification->toArray($notifiable);

        
        $agendamento = $notification->agendamento;
        $centroUser = $agendamento->centro->user; 

       
        return Notificacao::create([
            'mensagem' => $data['mensagem'],
            'tipo' => $data['tipo'],
            'status' => $data['status'] ?? 'pendente',
            'lida' => $data['lida'] ?? false,
            'email_enviado' => $data['email_enviado'] ?? false,
            'id_user' => $centroUser->id_user, 
            'id_agendamento' => $agendamento->id_agendamento,
            'id_centro' => $agendamento->id_centro,
            'link' => $data['link'] ?? null,
            'meta' => json_encode([
                'doador' => $agendamento->doador->only(['nome', 'tipo_sanguineo']),
                'data_hora' => $agendamento->data_agendada->format('d/m/Y H:i')
            ])
        ]);
    }
}