<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjusteEstoque extends Model
{
    use HasFactory;

    protected $table = 'ajuste_estoque';
    protected $primaryKey = 'id_ajuste';
    public $timestamps = true;

    protected $fillable = [
        'id_estoque',
        'tipo_sanguineo',
        'operacao',
        'quantidade',
        'motivo',
        'observacao'
    ];

    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'id_estoque');
    }
}
