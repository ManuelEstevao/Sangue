<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Solicitacao extends Model
{
    use HasFactory;

    protected $table = 'solicitacao';
    protected $primaryKey = 'id_sol';
    public $timestamps = false;

    protected $fillable = [
        'id_sol',
        'id_centro',
        'tipo_sanguineo',
        'quantidade',
        'urgencia',
        'status',
        'prazo',
        'motivo'
    ];

    /**
     * Relacionamento com a centro (muitos para 1).
     */
    public function centro(): BelongsTo
    {
        return $this->belongsTo(centro::class, 'id_centro', 'id_centro');
    }

    public function respostas()
    {
        return $this->hasMany(RespostaSolicitacao::class, 'id_sol', 'id_sol');
    }
    
    public function centroSolicitante() {
        return $this->belongsTo(Centro::class, 'id_centro');
    }

}
