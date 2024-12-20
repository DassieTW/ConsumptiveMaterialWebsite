<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryChangeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_change_records', function (Blueprint $table) {
            $table->id();
            $table->string('料號', 12);
            $table->string('儲位', 255);
            $table->integer('原庫存');
            $table->integer('現庫存');
            $table->string('異動原因', 255);
            $table->string('updated_by');
            $table->dateTime('updated_at');
        });

        Schema::table('inventory', function (Blueprint $table) {
            $table->dropColumn('UpdatedBy');
            $table->dropColumn('UpdatedReason');
        });
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_change_records');
        Schema::table('inventory', function (Blueprint $table) {
            $table->string('UpdatedBy', 45)->nullable(true);
            $table->string('UpdatedReason', 255)->nullable(true);
        });
    } // down
}
