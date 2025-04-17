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
        Schema::table('ajuste_estoque', function (Blueprint $table) {
            // Adiciona a coluna id_estoque (usando unsignedBigInteger)
            $table->unsignedBigInteger('id_estoque')->nullable()->after('id_ajuste');

            // Cria a foreign key para a tabela estoque (supondo que a chave primária de estoque seja 'id')
            $table->foreign('id_estoque')->references('id_estoque')->on('estoque')->onDelete('cascade');

            // Se existir a coluna id_centro, vamos removê-la, pois não será mais necessária
            if (Schema::hasColumn('ajuste_estoque', 'id_centro')) {
                $table->dropForeign(['id_centro']);
                $table->dropColumn('id_centro');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ajuste_estoque', function (Blueprint $table) {
            // Recria a coluna id_centro caso seja necessário restaurar o estado original
            $table->unsignedBigInteger('id_centro')->nullable()->after('id_estoque');
            $table->foreign('id_centro')->references('id_centro')->on('centro')->onDelete('cascade');

            // Remove a foreign key e a coluna id_estoque
            $table->dropForeign(['id_estoque']);
            $table->dropColumn('id_estoque');
        });
    }
};
