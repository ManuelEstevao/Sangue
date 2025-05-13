<?php

namespace App\Jobs;

use App\Models\Agendamento;
use App\Models\Notificacao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CriarNotificacaoLembrete implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected Agendamento $agendamento;

    /**
     * Recebe o Agendamento para usar no handle().
     */
    public function __construct(Agendamento $agendamento)
    {
        $this->agendamento = $agendamento;
    }

    /**
     * Executa quando o job for processado pela fila.
     */
   public function handle(): void
{    $data = Carbon::parse($this->agendamento->data_agendada)->format('d/m/Y');
    $hora = $this->agendamento->horario->format('H:i');
    // 1) Insere manualmente no seu model Notificacao
    $notificacao = Notificacao::create([
        'mensagem'       => "Sua doação começa em 5 minutos!  {$data} às {$hora}",
        'tipo'           => 'lembrete',
        'status'         => 'enviada',
        'lida'           => false,
        'email_enviado'  => false,
        'id_user'        => $this->agendamento->doador->user->id_user,
        'id_agendamento' => $this->agendamento->id_agendamento,
        'id_centro'      => $this->agendamento->centro->id_centro,
        'meta'           => [
            'id_agendamento' => $this->agendamento->id_agendamento,
            'local'          => $this->agendamento->centro->nome,
            'endereco'       => $this->agendamento->centro->endereco,
            'horario_real'   => $this->agendamento->data_agendada
                                     ->toDateTimeString(),
        ],
    ]);

    // 2) Dispara a Notification oficial (database, e-mail, etc.)
    $user = $this->agendamento->doador->user;
    $user->notify(
        (new \App\Notifications\Doador\Lembrete30MinutosNotification($this->agendamento))
            ->delay(now()) // se quiser, pode agendar outro delay aqui
    );
}
}
