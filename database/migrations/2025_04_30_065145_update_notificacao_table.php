<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::table('notificacao', function (Blueprint $table) {
            // Remove coluna id_agendamento se existir
            if (Schema::hasColumn('notificacao', 'id_agendamento')) {
                $table->dropForeign(['id_agendamento']);
                $table->dropColumn('id_agendamento');
            }
    
            // Adiciona novas colunas apenas se não existirem
            if (!Schema::hasColumn('notificacao', 'status')) {
                $table->enum('status', ['pendente', 'enviada', 'falha'])->default('pendente');
            }
            if (!Schema::hasColumn('notificacao', 'lida')) {
                $table->boolean('lida')->default(false);
            }
            if (!Schema::hasColumn('notificacao', 'email_enviado')) {
                $table->boolean('email_enviado')->default(false);
            }
        });
    }
    
    public function down() {
        Schema::table('notificacao', function (Blueprint $table) {
            // Reverte adições
            $table->dropColumn(['status', 'lida', 'email_enviado']);
            
            // Recria id_agendamento (opcional)
            $table->unsignedBigInteger('id_agendamento')->nullable();
            $table->foreign('id_agendamento')->references('id_agendamento')->on('agendamento');
        });
    }
};