<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;


class Campanha extends Model
{
    use HasFactory;

    protected $table = 'campanha';
    protected $primaryKey = 'id_campanha';
    public $timestamps = false;


    protected $fillable = [
        'titulo',
        'descricao',
        'data_inicio',
        'data_fim',
        'hora_inicio',
        'hora_fim',
        'foto',
        'id_centro'
    ];
    protected $dates = [
        'data_inicio',  
        'data_fim',     
        'created_at',
        'updated_at'
    ];
    /**
     * Relacionamento com a centro (muitos para 1).
     */
    public function centro(): BelongsTo
    {
        return $this->belongsTo(centro::class, 'id_centro', 'id_centro');
    }

}
