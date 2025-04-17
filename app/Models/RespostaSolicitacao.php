<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaSolicitacao extends Model
{
    use HasFactory;
    protected $table = 'respostas_solicitacoes';
    protected $primaryKey = 'id_resposta';
    public $timestamps = true;

    protected $fillable = [
        'id_sol',
        'id_centro',
        'quantidade_aceita',
        'status'
    ];

    public function solicitacao() {
        return $this->belongsTo(Solicitacao::class, 'id_sol', 'id_sol');
    }
    
    public function centroDoador() {
        return $this->belongsTo(Centro::class, 'id_centro', 'id_centro');
    }
}
