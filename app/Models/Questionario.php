<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionario extends Model
{
    use HasFactory;

    public $timestamps = false;    
    protected $table = 'questionario';
    protected $primaryKey = 'id_questionario';

    protected $fillable = [
        'id_agendamento',
        'ja_doou_sangue',
        'problema_doacao_anterior',
        'tem_doenca_cronica',
        'fez_tatuagem_ultimos_12_meses',
        'fez_cirurgia_recente',
        'esta_gravida',
        'recebeu_transfusao_sanguinea',
        'tem_doenca_infecciosa',
        'usa_medicacao_continua',
        'tem_comportamento_de_risco',
        'teve_febre_ultimos_30_dias',
        'consumiu_alcool_ultimas_24_horas',
        'teve_malaria_ultimos_3meses',
        'nasceu_ou_viveu_angola',
        'esteve_internado'
    ];

    // Relação com agendamento
    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class, 'id_agendamento', 'id_agendamento');
    }

    // Acesso ao doador através do agendamento
    public function doador()
    {
        return $this->agendamento->doador;
    }
}