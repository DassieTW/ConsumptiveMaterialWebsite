<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToSszinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sszinfo', function (Blueprint $table) {
            $table->string('ClaimedBy', 255)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sszinfo', function (Blueprint $table) {
            $table->dropColumn('ClaimedBy');
        });
    }
}
