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
        Schema::create('respostas_solicitacoes', function (Blueprint $table) {
            $table->id('id_resposta');
            $table->unsignedBigInteger('id_sol')->nullable();
            $table->unsignedBigInteger('id_centro')->nullable();
            $table->foreign('id_sol')->references('id_sol')->on('solicitacao')->onDelete('cascade');  
            $table->foreign('id_centro')->references('id_centro')->on('centro')->onDelete('cascade');
            $table->unsignedInteger('quantidade_aceita');
            $table->enum('status', ['Aceito', 'Concluido'])->default('Aceito');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respostas_solicitacoes');
    }
};
