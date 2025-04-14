<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;
    protected $table = 'estoque';
    protected $primaryKey = 'id_estoque';
    public $timestamps = false;

    protected $fillable = [
        'id_centro',
        'tipo_sanguineo',
        'quantidade'
    ];
    protected $casts = [
        'ultima_atualizacao' => 'datetime', 
    ];
    public function centro()
    {
        return $this->belongsTo(Centro::class, 'id_centro', 'id_centro');
    }
   
}
