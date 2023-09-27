<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('在途量', function (Blueprint $table) {
            $table->dropPrimary(['客戶', '料號']);
        });
        Schema::table('在途量', function (Blueprint $table) {
            $table->string('客戶')->nullable()->change();
            $table->primary('料號');
        });
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropPrimary(['料號', '儲位', '客戶別']);
        });
        Schema::table('inventory', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->primary(['料號', '儲位']);
        });
        Schema::table('不良品', function (Blueprint $table) {
            $table->dropPrimary(['料號', '客戶別']);
        });
        Schema::table('不良品', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->primary('料號');
        });
        Schema::table('不良品inventory', function (Blueprint $table) {
            $table->dropPrimary(['料號', '儲位', '客戶別']);
        });
        Schema::table('不良品inventory', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->primary(['料號', '儲位']);
        });
        Schema::table('inbound', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
        });
        Schema::table('MPS', function (Blueprint $table) {
            $table->dropPrimary(['客戶別', '機種', '製程']);
        });
        Schema::table('MPS', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->string('機種', 45)->nullable()->change();
            $table->string('製程', 45)->nullable()->change();
            $table->primary(['料號', '料號90']);
        });
        Schema::table('月請購_單耗', function (Blueprint $table) {
            $table->dropPrimary(['料號', '客戶別', '機種', '製程']);
        });
        Schema::table('月請購_單耗', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->string('機種', 45)->nullable()->change();
            $table->string('製程', 45)->nullable()->change();
            $table->primary(['料號', '料號90']);
        });
        Schema::table('outbound', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->string('機種', 45)->nullable()->change();
            $table->string('製程', 45)->nullable()->change();
        });
        Schema::table('出庫退料', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->string('機種', 45)->nullable()->change();
            $table->string('製程', 45)->nullable()->change();
        });
        Schema::table('請購單', function (Blueprint $table) {
            $table->string('客戶', 45)->nullable()->change();
        });
        Schema::table('非月請購', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
        });
        Schema::table('safestock報警備註', function (Blueprint $table) {
            $table->dropPrimary(['料號', '客戶別']);
        });
        Schema::table('safestock報警備註', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->primary('料號');
        });
        Schema::table('sluggish報警備註', function (Blueprint $table) {
            $table->dropPrimary(['料號', '客戶別']);
        });
        Schema::table('sluggish報警備註', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->primary('料號');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
