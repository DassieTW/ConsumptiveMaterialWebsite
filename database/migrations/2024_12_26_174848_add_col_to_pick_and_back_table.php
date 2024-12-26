<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToPickAndBackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outbound', function (Blueprint $table) {
            $table->string('開單人員', 45)->nullable(true);
        });

        Schema::table('出庫退料', function (Blueprint $table) {
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
        Schema::table('outbound', function (Blueprint $table) {
            $table->dropColumn('開單人員');
        });

        Schema::table('出庫退料', function (Blueprint $table) {
            $table->dropColumn('開單人員');
        });
    } // down
}
