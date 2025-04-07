<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questionario', function (Blueprint $table) {
            $table->id('id_questionario');     
            $table->unsignedBigInteger('id_agendamento');
            $table->foreign('id_agendamento')->references('id_agendamento')->on('agendamento')->onDelete('cascade');
        

            // Perguntas do questionário
           
            // Perguntas com documentação clara
            $table->boolean('ja_doou_sangue')->default(false)
                  ->comment('Já doou sangue anteriormente?');
                  
            $table->boolean('problema_doacao_anterior')->default(false)
                  ->comment('Teve problemas em doações anteriores?');
                  
            $table->boolean('tem_doenca_cronica')->default(false)
                  ->comment('Possui doença crônica?');
                  
            $table->boolean('fez_tatuagem_ultimos_12_meses')->default(false)
                  ->comment('Fez tatuagem ou piercing nos últimos 12 meses?');
                  
            $table->boolean('fez_cirurgia_recente')->default(false)
                  ->comment('Realizou cirurgia nos últimos 6 meses?');
                  
            $table->boolean('esta_gravida')->default(false)
                  ->comment('Está grávida ou amamentando?');
                  
            $table->boolean('recebeu_transfusao_sanguinea')->default(false)
                  ->comment('Recebeu transfusão sanguínea nos últimos 12 meses?');
                  
            $table->boolean('tem_doenca_infecciosa')->default(false)
                  ->comment('Possui doença infecciosa?');
                  
            $table->boolean('usa_medicacao_continua')->default(false)
                  ->comment('Faz uso contínuo de medicamentos?');
                  
            $table->boolean('tem_comportamento_de_risco')->default(false)
                  ->comment('Comportamento de risco para ISTs?');
                  
            $table->boolean('teve_febre_ultimos_30_dias')->default(false)
                  ->comment('Teve febre nos últimos 30 dias?');
                  
            $table->boolean('consumiu_alcool_ultimas_24_horas')->default(false)
                  ->comment('Consumiu álcool nas últimas 24 horas?');
                  
            $table->boolean('teve_malaria_ultimos_3meses')->default(false)
                  ->comment('Teve malária nos últimos 3 meses?');
                  
            $table->boolean('nasceu_ou_viveu_angola')->default(false)
                  ->comment('Nasceu ou viveu em área endêmica de malária?');
                  
            $table->boolean('esteve_internado')->default(false)
                  ->comment('Esteve internado nos últimos 12 meses?');

            // Controle temporal
            $table->timestamp('data_resposta')->useCurrent()
                  ->comment('Data/hora do preenchimento do questionário');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionario');
    }
};
