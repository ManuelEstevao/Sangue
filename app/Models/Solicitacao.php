<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    use HasFactory;

    protected $table = 'solicitacao';
    protected $primaryKey = 'id_sol';

    protected $fillable = [
        'id_centro',
        'tipo_sanguineo',
        'quantidade',
        'statu',
    ];

    /**
     * Relacionamento com a centro (muitos para 1).
     */
    public function centro(): BelongsTo
    {
        return $this->belongsTo(centro::class, 'id_centro', 'id_centro');
    }

}
