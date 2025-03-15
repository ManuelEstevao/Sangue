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


    protected $fillable = [
        'id_doador',
        'id_centro',
        'horario',
        'data_agendada',
        'status',
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
    public function centro(): BelongsTo
    {
        return $this->belongsTo(centro::class, 'id_centro', 'id_centro');
    }

}
