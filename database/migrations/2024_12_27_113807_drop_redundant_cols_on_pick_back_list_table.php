<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRedundantColsOnPickBackListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outbound', function (Blueprint $table) {
            $table->dropColumn('領料人員');
            $table->dropColumn('發料人員');
        });

        Schema::table('出庫退料', function (Blueprint $table) {
            $table->dropColumn('收料人員');
            $table->dropColumn('退料人員');
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
            $table->string('領料人員', 45)->nullable();
            $table->string('發料人員', 45)->nullable();
        });

        Schema::table('出庫退料', function (Blueprint $table) {
            $table->string('收料人員', 45)->nullable();
            $table->string('退料人員', 45)->nullable();
        });
    } // down
}
