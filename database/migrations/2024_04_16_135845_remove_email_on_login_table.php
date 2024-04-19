<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEmailOnLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('login', function (Blueprint $table) {
            $table->string('email', 200)->nullable(true);
        });
    } // down
}
