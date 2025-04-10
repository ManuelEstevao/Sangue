<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Centro extends Model
{
    use HasFactory;
    protected $table = 'centro';
    protected $primaryKey = 'id_centro';
    public $timestamps = false;
    public $incrementing = true;

    protected $casts = [
        'horario_funcionamento' => 'array',
        'capacidade_maxima' => 'integer'
    ];

    protected $fillable = [
        'nome',
        'latitude',
        'longitude',
        'telefone',
        'endereco',
        'foto',
        'capacidade_maxima',
        'horario_abertura',
        'horario_fechamento',
        'id_user'
    ];

    /**
     * Relacionamento com o usuário (muitos para 1).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relacionamento com os agendamentos (1 para muitos).
     */
    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class, 'id_centro', 'id_centro');
    }
    /**
     * Fazendo um join
    * Relacionamento indireto com doações via agendamentos:
    */
    public function doacoes()
    {
        return $this->hasManyThrough(
            Doacao::class,
            Agendamento::class,
            'id_centro',
            'id_agendamento', 
            'id_centro', 
            'id_agendamento' 
        );
    }
    // Método para obter horário do dia
    public function getHorarioDia($data)
    {
        $dia = strtolower(\Carbon\Carbon::parse($data)->isoFormat('dddd'));
        return $this->horario_funcionamento[$dia] ?? null;
    }

    /**
     * Relacionamento com solicitações (1 para muitos).
     */
    public function solicitacoes(): HasMany
    {
        return $this->hasMany(Solicitacao::class, 'id_centro', 'id_centro');
    }

    /**
     * Relacionamento com campanha (1 para muitos).
     */
    public function campanhas(): HasMany
    {
        return $this->hasMany(Campanha::class, 'id_centro', 'id_centro');
    }

}
