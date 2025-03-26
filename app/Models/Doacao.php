<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doacao extends Model
{
    use HasFactory;

    protected $table = 'doacao';
    protected $primaryKey = 'id_doacao';
    public $timestamps = false;

    // Campos preenchíveis
    protected $fillable = [
        'observacoes',
        'data_doacao',
        'id_agendamento',
        'Volume_coletado',
        'hemoglobina', 
        'pressao_arterial',
        'status',
        'id_doador',
        'id_centro'
    ];



    // Relacionamentos
    public function agendamento(): BelongsTo
    {
        return $this->belongsTo(Agendamento::class, 'id_agendamento');
    }

    public function doador(): BelongsTo
    {
        return $this->belongsTo(Doador::class, 'id_doador');
    }

    public function centro(): BelongsTo
    {
        return $this->belongsTo(Centro::class, 'id_centro');
    }

}
