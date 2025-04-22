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
    Schema::table('notificacao', function (Blueprint $table) {
        $table->boolean('lida')->default(false);

        $table->dropForeign(['id_agendamento']);
        $table->dropColumn('id_agendamento');
    });
}

public function down(): void
{
    Schema::table('notificacao', function (Blueprint $table) {
        $table->unsignedBigInteger('id_agendamento')->nullable();

        $table->foreign('id_agendamento')
              ->references('id_agendamento')
              ->on('agendamento')
              ->onDelete('set null');

        $table->dropColumn('lida');
    });
}

};
