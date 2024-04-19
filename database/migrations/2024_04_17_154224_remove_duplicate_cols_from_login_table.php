<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDuplicateColsFromLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login', function (Blueprint $table) {
            $table->dropColumn('姓名');
            $table->dropColumn('部門');
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
            $table->string('姓名', 50)->nullable();
            $table->string('部門', 50)->nullable();
        });
    } // down
}
