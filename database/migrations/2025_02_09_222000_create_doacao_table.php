<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('doacao', function (Blueprint $table) {
            $table->id('id_doacao');
            $table->text('observacoes');
            $table->timestamp('data_doacao')->useCurrent();
            
            // Chaves estrangeiras
            $table->unsignedBigInteger('id_agendamento');
            $table->foreign('id_agendamento')
                  ->references('id_agendamento')
                  ->on('agendamento')
                  ->onDelete('cascade');
                  
            $table->unsignedBigInteger('id_doador');
            $table->foreign('id_doador')
                  ->references('id_doador')
                  ->on('doador')
                  ->onDelete('cascade');
                  
            $table->unsignedBigInteger('id_centro');
            $table->foreign('id_centro')
                  ->references('id_centro')
                  ->on('centro')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doacao');
    }
};
