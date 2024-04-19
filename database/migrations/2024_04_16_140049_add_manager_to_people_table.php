<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagerToPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('人員信息', function (Blueprint $table) {
            $table->string('email', 200)->nullable(true);
            $table->string('主管工號', 20)->nullable(true);
        });
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('人員信息', function (Blueprint $table) {
            $table->dropColumn('主管工號');
            $table->dropColumn('email');
        });
    } // down
}
