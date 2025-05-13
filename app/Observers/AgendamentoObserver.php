<?php

namespace App\Observers;

use App\Models\Agendamento;
use Carbon\Carbon;
use App\Notifications\Doador\Lembrete30MinutosNotification;
class AgendamentoObserver
{
    /**
     * Handle the Agendamento "created" event.
     */
    public function created(Agendamento $agendamento)
    {
        $this->agendarLembrete30Minutos($agendamento);
    }
    
    private function agendarLembrete30Minutos(Agendamento $agendamento)
    {
         $horarioAgendamento = Carbon::parse(
        $agendamento->data_agendada->format('Y-m-d') . ' ' .
        $agendamento->horario->format('H:i:s')
    );

    // subtrai 5 minutos
    $dispatchAt = $horarioAgendamento->subMinutes(20);

    // se já passou, agenda em 5 segundos
    if ($dispatchAt->isPast()) {
        $dispatchAt = now()->addSeconds(5);
    }

    // despacha o Job com o delay correto
    dispatch(new \App\Jobs\CriarNotificacaoLembrete($agendamento))
        ->delay($dispatchAt);
    }

    /**
     * Handle the Agendamento "updated" event.
     */
    public function updated(Agendamento $agendamento): void
    {
        //
    }

    /**
     * Handle the Agendamento "deleted" event.
     */
    public function deleted(Agendamento $agendamento): void
    {
        //
    }

    /**
     * Handle the Agendamento "restored" event.
     */
    public function restored(Agendamento $agendamento): void
    {
        //
    }

    /**
     * Handle the Agendamento "force deleted" event.
     */
    public function forceDeleted(Agendamento $agendamento): void
    {
        //
    }
}
