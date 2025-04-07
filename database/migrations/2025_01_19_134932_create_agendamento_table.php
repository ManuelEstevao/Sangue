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
        Schema::create('agendamento', function (Blueprint $table) {
            $table->id('id_agendamento');
            $table->date('data_agendada')->nullable();
            $table->time('horario')->nullable();
            $table->unsignedBigInteger('id_doador')->nullable(); 
            $table->foreign('id_doador')->references('id_doador')->on('doador')->onDelete('cascade');
            $table->unsignedBigInteger('id_centro')->nullable(); 
            $table->foreign('id_centro')->references('id_centro')->on('centro')->onDelete('cascade');
            $table->enum('status', ['Agendado', 'Comparecido','Não Compareceu', 'Cancelado', 'Concluido' ])->default('Agendado');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamento');
    }
};
