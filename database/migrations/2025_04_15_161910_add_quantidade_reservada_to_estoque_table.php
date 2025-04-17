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
        Schema::table('estoque', function (Blueprint $table) {
            $table->unsignedInteger('quantidade_reservada')
                  ->default(0)
                  ->after('quantidade');
        });
    }

    public function down()
    {
        Schema::table('estoque', function (Blueprint $table) {
            $table->dropColumn('quantidade_reservada');
        });
    }
};
