<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notificacao', function (Blueprint $table) {
            // Adicionar coluna meta (JSON)
            $table->json('meta')->nullable()->after('email_enviado');
            
            // Adicionar colunas de relacionamento
            $table->unsignedBigInteger('id_agendamento')->nullable()->after('id_user');
            $table->unsignedBigInteger('id_centro')->nullable()->after('id_agendamento');
            
            // Adicionar chaves estrangeiras
            $table->foreign('id_agendamento')
                ->references('id_agendamento')
                ->on('agendamento')
                ->onDelete('cascade');

            $table->foreign('id_centro')
                ->references('id_centro')
                ->on('centro')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('notificacao', function (Blueprint $table) {
            // Remover FKs primeiro
            $table->dropForeign(['id_agendamento']);
            $table->dropForeign(['id_centro']);
            
            // Remover colunas
            $table->dropColumn(['meta', 'id_agendamento', 'id_centro']);
        });
    }
};