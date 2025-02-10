<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campanha extends Model
{
    use HasFactory;

    protected $table = 'campanha';
    protected $primaryKey = 'id_campanha';

    protected $fillable = [
        'titulo',
        'descricao',
        'data_inicio',
        'data_fim',
        'id_cenntro',
    ];

    /**
     * Relacionamento com a cenntro (muitos para 1).
     */
    public function cenntro(): BelongsTo
    {
        return $this->belongsTo(cenntro::class, 'id_cenntro', 'id_cenntro');
    }

}
