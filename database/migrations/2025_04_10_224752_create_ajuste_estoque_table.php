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
        Schema::create('ajuste_estoque', function (Blueprint $table) {
            $table->id('id_ajuste');
            $table->unsignedBigInteger('id_centro')->nullable();
            $table->foreign('id_centro')->references('id_centro')->on('centro')->onDelete('cascade');
            $table->enum('tipo_sanguineo', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']);
            $table->integer('quantidade');
            $table->enum('motivo', ['expiracao', 'perda', 'doacao', 'ajuste']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuste_estoque');
    }
};
