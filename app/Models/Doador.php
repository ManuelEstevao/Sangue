<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doador extends Model
{
    protected $table = 'doador';
    protected $primaryKey = 'id_doador';

    protected $fillable = [
        'numero_bilhete',
        'nome',
        'data_nascimento',
        'tipo_sanguineo',
        'telefone',
        'foto',
        'data_cadastro',
        'id_user',
    ];
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

}
