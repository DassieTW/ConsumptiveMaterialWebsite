<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPKOnLocTransferRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('LocTransferRecord', function (Blueprint $table) {
            $table->dropPrimary(['料號', '操作時間']);
            $table->primary(['料號', '調動數量', '操作人', '調出儲位', '接收儲位', '操作時間']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('LocTransferRecord', function (Blueprint $table) {
            $table->dropPrimary(['料號', '調動數量', '操作人', '調出儲位', '接收儲位', '操作時間']);
            $table->primary(['料號', '操作時間']);
        });
    }
}
