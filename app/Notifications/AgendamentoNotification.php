<?php

namespace App\Notifications;

use App\Models\Agendamento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use Carbon\Carbon;

class AgendamentoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $agendamento;

    public function __construct(Agendamento $agendamento)
    {
        $this->agendamento = $agendamento;
    }

    // Alterar para usar o canal personalizado
    public function via($notifiable)
    {
        return ['custom_database'/*, 'mail'*/];
    }

    // Novo método para o canal personalizado
    public function toCustomDatabase($notifiable)
{
    $data = Carbon::parse($this->agendamento->data_agendada)->format('d/m/Y');
    $hora = $this->agendamento->horario->format('H:i');
    
    return [
        'tipo' => 'agendamento',
        'mensagem' => "Novo agendamento confirmado para {$data} às {$hora} - Doador: {$this->agendamento->doador->nome}", // Adicione o nome do doador
        'status' => 'enviada', 
        'lida' => false,
        'email_enviado' => false,
        'id_user' => $notifiable->id_user, 
        'link' => url("/centro/agendamentos/{$this->agendamento->id_agendamento}"),
        'meta' => [
            'id_agendamento' => $this->agendamento->id_agendamento,
            'doador' => [
                'nome' => $this->agendamento->doador->nome,
                'tipo_sanguineo' => $this->agendamento->doador->tipo_sanguineo
            ],
            'data' => $this->agendamento->data_agendada
        ]
    ];
}

    /* Atualizar método de email
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
        ->subject('Novo Agendamento no Seu Centro')
        ->greeting("Olá, Centro {$this->agendamento->centro->nome}!")
        ->line("Um novo agendamento foi confirmado para {$this->agendamento->data_agendada}.")
        ->action('Ver Detalhes', url("/centro/agendamentos/{$this->agendamento->id_agendamento}"))
        ->line('Doador: ' . $this->agendamento->doador->nome);

        $this->updateNotificationStatus($notifiable, ['email_enviado' => true]);

        return $mail;
    }*/

    private function updateNotificationStatus($notifiable, $data)
    {
        \App\Models\Notificacao::where('id_user', $notifiable->id_user)
            ->where('meta->id_agendamento', $this->agendamento->id_agendamento)
            ->update($data);
    }
}