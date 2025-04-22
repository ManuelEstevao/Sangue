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
    public $timestamps = false;

    // Campos preenchíveis
    protected $fillable = [
        'mensagem',
        'tipo',
        'data_envio',
        'id_user',
        'lida',
    ];


    // Relacionamento com usuário
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function agendamento()
{
    return $this->belongsTo(Agendamento::class, 'id_agendamento');
}


}
