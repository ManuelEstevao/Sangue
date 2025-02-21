<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doador', function (Blueprint $table) {
            $table->id('id_doador'); 
            $table->string('numero_bilhete', 14)->unique();
            $table->string('nome', 255);
            $table->date('data_nascimento');
            $table->enum('genero', ['Masculino', 'Feminino']);
            $table->enum('tipo_sanguineo', ['Não sei', 'A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']); 
            $table->string('telefone', 16);
            $table->string('foto')->nullable();
            $table->timestamp('data_cadastro')->useCurrent(); 
            $table->unsignedBigInteger('id_user'); 
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doador');
    }
};
