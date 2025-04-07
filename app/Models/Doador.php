<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Doador extends Model
{
    protected $table = 'doador';
    protected $primaryKey = 'id_doador';
    public $timestamps = false;

    protected $fillable = [
        'numero_bilhete',
        'nome',
        'data_nascimento',
        'genero',  
        'tipo_sanguineo',
        'telefone',
        'peso',
        'endereco',
        'foto',
        'data_cadastro',
        'id_user',
    ];

    
    protected $appends = ['foto_url'];

    public function getFotoUrlAttribute()
    {
        
        if (!$this->foto) {
            return asset('assets/img/profile.png');
        }
    
   
        $caminhoRelativo = "fotos/{$this->foto}";
    
       
        if (Storage::disk('public')->exists($caminhoRelativo)) {
            return asset("storage/{$caminhoRelativo}");
        }
    
        return asset('assets/img/profile.png');
    }
    public static function getEnumValues($column)
    {
        // Obtém a definição da coluna do banco de dados
        $type = \DB::select("SHOW COLUMNS FROM doador WHERE Field = ?", [$column]);
    
        // Valida se o resultado foi encontrado
        if (empty($type)) {
            throw new \Exception("A coluna {$column} não foi encontrada na tabela doador.");
        }
    
        // Captura os valores do ENUM com regex
        preg_match('/^enum\((.*)\)$/', $type[0]->Type, $matches);
    
        // Transforma os valores capturados em um array
        $enum = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));
    
        return $enum;
    }
    
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relacionamento com agendamentos (1 para muitos).
     */
    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class, 'id_doador', 'id_doador');
    }

    /* Todas as doações através dos agendamentos
    *Fazendo um join
    */
    public function doacoes()
    {
        return $this->hasManyThrough(
            Doacao::class,
            Agendamento::class,
            'id_doador', 
            'id_agendamento', 
            'id_doador', 
            'id_agendamento'
        );
    }
  
}
