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
            // Dados da coleta
            $table->decimal('hemoglobina', 4, 1);
            $table->string('pressao_arterial', 7);
            $table->integer('volume_coletado')->default(450) ;
            $table->text('observacoes')->nullable();
            $table->enum('status', ['Reprovado', 'Aprovado']);
            $table->timestamp('data_doacao')->useCurrent();
                  
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
