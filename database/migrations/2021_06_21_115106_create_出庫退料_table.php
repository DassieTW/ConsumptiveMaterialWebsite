<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create出庫退料Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('出庫退料', function (Blueprint $table) {
            $table->string('客戶別', 30);
            $table->string('機種', 30);
            $table->string('製程', 30);
            $table->string('退回原因', 30);
            $table->string('線別', 30);
            $table->string('料號', 12);
            $table->string('品名', 100);
            $table->string('規格', 100);
            $table->string('單位', 45);
            $table->integer('預退數量');
            $table->integer('實際退回數量');
            $table->string('備註', 45);
            $table->string('實退差異原因', 45)->nullable();
            $table->string('儲位', 45)->nullable();
            $table->string('收料人員', 45)->nullable();
            $table->string('收料人員工號', 45)->nullable();
            $table->string('退料人員', 45)->nullable();
            $table->string('退料人員工號', 45)->nullable();
            $table->string('退料單號', 45);
            $table->dateTime('開單時間');
            $table->dateTime('入庫時間')->nullable();
            $table->string('功能狀況', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('出庫退料');
    }
}
