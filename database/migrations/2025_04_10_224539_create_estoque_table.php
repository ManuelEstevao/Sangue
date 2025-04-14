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
        Schema::create('estoque', function (Blueprint $table) {
            $table->id('id_estoque');
            $table->unsignedBigInteger('id_centro')->nullable(); 
            $table->foreign('id_centro')->references('id_centro')->on('centro')->onDelete('cascade');
            $table->enum('tipo_sanguineo', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']);
            $table->unsignedInteger('quantidade')->default(0);
            $table->timestamp('ultima_atualizacao')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoque');
    }
};
