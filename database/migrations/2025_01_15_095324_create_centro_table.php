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
        Schema::create('centro', function (Blueprint $table) {
            $table->id('id_centro');
            $table->string('nome', 100);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('telefone', 16);
            $table->time('horario_abertura')->default('08:00:00');
            $table->time('horario_fechamento')->default('17:30:00');
            $table->string('endereco', 200);
            $table->string('foto')->nullable();
            $table->unsignedInteger('capacidade_maxima')->default('10');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->unsignedBigInteger('id_user')->nullable(); 
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centro');
    }
};
