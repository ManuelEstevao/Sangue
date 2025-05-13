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
    protected $casts = [
        'meta' => 'array',
        'email_enviado' => 'boolean',
        'lida' => 'boolean'
    ];
    protected $fillable = [
        'mensagem',
        'tipo',
        'status',
        'lida',
        'email_enviado',
        'id_user'
    ];


    // Relacionamento com usuário
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }

    public function marcarComoLida()
    {
        $this->update(['lida' => true]);
    }

    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class, 'id_agendamento');
    }

    public function centro()
    {
        return $this->belongsTo(Centro::class, 'id_centro');
    }

}
