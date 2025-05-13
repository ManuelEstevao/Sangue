<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DiaBloqueado extends Model
{
    use HasFactory;

    protected $table = 'dias_bloqueados';
    protected $primaryKey = 'id_bloqueio';
    protected $casts = [
        'data' => 'datetime:Y-m-d\TH:i:sP', 
    ];
    
    protected $fillable = [
        'id_centro',
        'data',
        'motivo'
    ];

    protected $dates = ['data'];

    // Relação com o centro
    public function centro()
    {
        return $this->belongsTo(Centro::class, 'id_centro');
    }
}