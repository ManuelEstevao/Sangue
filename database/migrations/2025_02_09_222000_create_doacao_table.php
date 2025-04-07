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

            $table->decimal('hemoglobina', 4, 1);
            $table->string('pressao_arterial', 7);
            $table->integer('volume_coletado')->default(450);
            $table->text('observacoes')->nullable();
            $table->string('nome_profissional', 255);
            $table->enum('status', ['Reprovado', 'Aprovado']);
            $table->timestamp('data_doacao')->useCurrent();
                  
            $table->unsignedBigInteger('id_agendamento');
            $table->foreign('id_agendamento')
                  ->references('id_agendamento')
                  ->on('agendamento')
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
