<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacao extends Model
{
    use HasFactory;
    protected $table = 'notificacao';
    protected $primaryKey = 'id_notificacao';

    // Tipos de notificação
    const TIPO_AGENDAMENTO = 'agendamento';
    const TIPO_CAMPANHA = 'campanha';
    const TIPO_LEMBRETE = 'lembrete';
    const TIPO_EMERGENCIA = 'emergencia';

    // Campos preenchíveis
    protected $fillable = [
        'mensagem',
        'tipo',
        'data_envio',
        'user_id'
    ];


    // Relacionamento com usuário
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

}
