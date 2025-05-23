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
        Schema::create('solicitacao', function (Blueprint $table) {
            $table->id('id_sol');
            $table->unsignedBigInteger('id_centro')->nullable(); 
            $table->foreign('id_centro')->references('id_centro')->on('centro')->onDelete('cascade');
            $table->enum('tipo_sanguineo', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']);
            $table->unsignedInteger('quantidade');
            $table->enum('status', ['Atendida', 'Pendente', 'Cancelada', 'Parcial', 'Expirada'])->default('Pendente');
            $table->enum('urgencia', ['Normal', 'Emergencia'])->default('Normal');
            $table->date('prazo');
            $table->string('motivo')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacao');
    }
};
