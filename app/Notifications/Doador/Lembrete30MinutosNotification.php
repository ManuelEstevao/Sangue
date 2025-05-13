<?php

namespace App\Notifications\Doador;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Agendamento;


class Lembrete30MinutosNotification extends Notification
{
    use Queueable;

    public function __construct(Agendamento $agendamento)
{
    $this->agendamento = $agendamento;
}


    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
         $data = Carbon::parse($this->agendamento->data_agendada)->format('d/m/Y');
        $hora = $this->agendamento->horario->format('H:i');
        
        return [
            'tipo' => 'lembrete_30min',
            'mensagem' => "Sua doação começa em 30 minutos! {$data} às {$hora}",
            'meta' => [
                'id_agendamento' => $this->agendamento->id_agendamento,
                'local' => $this->agendamento->centro->nome,
                'endereco' => $this->agendamento->centro->endereco,
                'horario_real' => $this->agendamento->data_agendada->toDateTimeString()
            ]
        ];
    }
}
