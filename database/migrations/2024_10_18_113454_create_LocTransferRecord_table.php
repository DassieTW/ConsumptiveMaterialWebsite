<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocTransferRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LocTransferRecord', function (Blueprint $table) {
            $table->string('料號', 12);
            $table->integer('調動數量');
            $table->string('操作人', 45);
            $table->string('調出儲位', 255);
            $table->integer('原調出儲位庫存');
            $table->string('接收儲位', 255);
            $table->integer('原接收儲位庫存');
            $table->dateTime('操作時間');
            $table->primary(['料號', '操作時間']);
        });
    } // end function up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('LocTransferRecord');
    } // end function down
}
