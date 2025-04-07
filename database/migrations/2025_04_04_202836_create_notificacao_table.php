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
        
        Schema::create('notificacao', function (Blueprint $table) {
                $table->id('id_notificacao');
                $table->text('mensagem');
                $table->enum('tipo', [
                        'agendamento', 
                        'campanha', 
                        'lembrete', 
                        'emergencia', 
                        'nao_compareceu' 
                    ])->default('lembrete');
                $table->timestamp('data_envio')->useCurrent();
                $table->unsignedBigInteger('id_user');
                $table->unsignedBigInteger('id_agendamento')->nullable(); 
                
                // Chaves estrangeiras
                $table->foreign('id_user')
                      ->references('id_user')
                      ->on('users')
                      ->onDelete('cascade');
                      
                $table->foreign('id_agendamento')
                      ->references('id_agendamento')
                      ->on('agendamento')
                      ->onDelete('set null');
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
