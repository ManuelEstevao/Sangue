<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('doacao', function (Blueprint $table) {
            $table->integer('quantidade_ml')->default(450)->after('data_doacao'); // Quantidade de sangue
            $table->enum('status', ['pendente', 'concluido', 'cancelado'])->default('pendente')->after('quantidade_ml'); // Status da doação
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('doacao', function (Blueprint $table) {
            $table->dropColumn('quantidade_ml');
            $table->dropColumn('status');
        });
    }
};
