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
        Schema::create('campanha', function (Blueprint $table) {
            $table->id('id_campanha');
            $table->string('titulo', 150);
            $table->text('descricao')->nullable();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->time('hora_inicio');       
            $table->time('hora_fim');          
            $table->string('foto')->nullable();
            $table->string('endereco', 200)-> nullable(); 
            $table->unsignedBigInteger('id_centro')->nullable();
            $table->foreign('id_centro')
                  ->references('id_centro')
                  ->on('centro')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campanha');
    }
};