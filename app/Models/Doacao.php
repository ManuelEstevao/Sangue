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
        'volume_coletado',
        'hemoglobina', 
        'pressao_arterial',
        'status',
        'nome_profissional',
        'id_agendamento'
    ];

    protected $casts = [
        'data_doacao' => 'datetime:Y-m-d H:i:s', // Conversão explícita
    ];

    protected $appends = ['data_formatada']; // Adiciona ao JSON

    public function getDataFormatadaAttribute()
    {
        return $this->data_doacao 
            ? $this->data_doacao->format('d/m/Y H:i')
            : '--/--/---- --:--';
    }


    // Relacionamentos
    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class, 'id_agendamento', 'id_agendamento');
    }

    // Acesso ao centro através do agendamento
    public function centro()
    {
        return $this->agendamento->centro;
    }

    // Acesso ao doador através do agendamento
    public function doador()
    {
        return $this->agendamento->doador;
    }

}
