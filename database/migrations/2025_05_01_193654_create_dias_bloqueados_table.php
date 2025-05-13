<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dias_bloqueados', function (Blueprint $table) {
            $table->id('id_bloqueio');
            
            // Chave estrangeira para centro (1:N)
            $table->unsignedBigInteger('id_centro');
            $table->foreign('id_centro')
                  ->references('id_centro')
                  ->on('centro')
                  ->onDelete('cascade'); 

            $table->date('data'); 
            $table->string('motivo', 255)->nullable(); 
            
            // Controle de datas
            $table->timestamps(); // created_at e updated_at

            // Garante que um centro não possa bloquear a mesma data duas vezes
            $table->unique(['id_centro', 'data']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dias_bloqueados');
    }
};