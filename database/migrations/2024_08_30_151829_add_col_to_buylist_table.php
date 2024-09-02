<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToBuylistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('請購單', function (Blueprint $table) {
            $table->string('開單人員', 45)->nullable(true);
        });
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('請購單', function (Blueprint $table) {
            $table->dropColumn('開單人員');
        });
    } // down
}
