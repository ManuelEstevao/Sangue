<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agendamento extends Model
{
    protected $table = 'agendamento';
    protected $primaryKey = 'id_agendamento';
    public $timestamps = false;
    public $incrementing = true;

    protected $casts = [
        'data_agendada' => 'date:Y-m-d',
        'horario' => 'datetime:H:i:s',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    protected $fillable = [
        'id_doador',
        'id_centro',
        'horario',
        'data_agendada',
        'status',
        'motivo_cancelamento',
    ];

    /**
     * Relacionamento com o doador (muitos para 1).
     */
    public function doador(): BelongsTo
    {
        return $this->belongsTo(Doador::class, 'id_doador', 'id_doador');
    }

    /**
     * Relacionamento com a centro (muitos para 1).
     */

    public function doacao()
    {
         return $this->hasOne(Doacao::class, 'id_agendamento', 'id_agendamento');
    }

    public function centro(): BelongsTo
    {
        return $this->belongsTo(centro::class, 'id_centro', 'id_centro');
    }

    public function questionario()
    {
        return $this->hasOne(Questionario::class, 'id_agendamento', 'id_agendamento');
    }

    public function notificacoes()
{
    return $this->hasMany(Notificacao::class, 'id_agendamento');
}


}
