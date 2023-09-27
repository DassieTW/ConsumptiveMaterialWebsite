<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TempAlterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('在途量', function (Blueprint $table) {
        //     $table->dropPrimary();
        //     $table->string('客戶', 45)->nullable()->change();
        //     $table->primary('料號');
        // });
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('客戶別', 45)->nullable()->change();
            $table->primary(['料號', '儲位']);
        });
        Schema::table('不良品', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('客戶別', 45)->nullable()->change();
            $table->primary('料號');
        });
        Schema::table('不良品inventory', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('客戶別', 45)->nullable()->change();
            $table->primary(['料號', '儲位']);
        });
        Schema::table('inbound', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
        });
        Schema::table('MPS', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('客戶別', 45)->nullable()->change();
            $table->string('機種', 45)->nullable()->change();
            $table->string('製程', 45)->nullable()->change();
            $table->string('料號', 12);
            $table->primary(['料號', '料號90']);
        });
        Schema::table('月請購_單耗', function (Blueprint $table) {
            $table->dropPrimary();
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
            $table->string('客戶別', 45)->nullable()->change();
            $table->dropPrimary();
            $table->primary('料號');
        });
        Schema::table('sluggish報警備註', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('客戶別', 45)->nullable()->change();
            $table->primary('料號');
        });

        Schema::table('consumptive_material', function (Blueprint $table) {
            $table->dropColumn('耗材歸屬');
        });
    } // up

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
