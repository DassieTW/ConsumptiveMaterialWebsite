<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInitialLocColToSszinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('SSZInfo', function (Blueprint $table) {
            $table->string('initial_loc', 255)->nullable(true);
        });
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('SSZInfo', function (Blueprint $table) {
            $table->dropColumn('initial_loc');
        });
    } // down
}
