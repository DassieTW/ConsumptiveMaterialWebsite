<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsToInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->string('UpdatedBy', 45)->nullable(true);
            $table->dropColumn('客戶別');
            $table->string('UpdatedReason', 255)->nullable(true);
        });

        Schema::table('checking_inventory', function (Blueprint $table) {
            $table->dropColumn('客戶別');
            $table->dropColumn('盤點');
            $table->dropColumn('updated_at');
            $table->dropColumn('單號');
        });
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropColumn('UpdatedBy');
            $table->dropColumn('UpdatedReason');
            $table->string('客戶別', 255)->nullable(true);
        });

        Schema::table('checking_inventory', function (Blueprint $table) {
            $table->string('客戶別', 255);
            $table->bigInteger('盤點')->nullable();
            $table->dateTime('updated_at');
            $table->string('單號', 255);
        });
    } // down
}
