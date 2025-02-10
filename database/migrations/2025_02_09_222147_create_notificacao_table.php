<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('notificacao', function (Blueprint $table) {
            $table->id('id_notificacao');
            $table->text('mensagem');
            $table->enum('tipo', ['agendamento', 'campanha', 'lembrete', 'emergencia']);
            $table->timestamp('data_envio')->useCurrent();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacao');
    }
};
