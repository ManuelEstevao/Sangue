<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObservacaoToAjusteEstoqueTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ajuste_estoque', function (Blueprint $table) {
            $table->text('observacao')->nullable()->after('motivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('ajuste_estoque', function (Blueprint $table) {
            $table->dropColumn('observacao');
        });
    }
}
