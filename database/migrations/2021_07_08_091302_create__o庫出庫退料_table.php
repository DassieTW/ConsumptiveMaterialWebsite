<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateO庫出庫退料Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('O庫出庫退料', function (Blueprint $table) {
            $table->string('客戶別', 45);
            $table->string('機種', 45);
            $table->string('製程', 45);
            $table->string('退回原因', 45);
            $table->string('線別', 45);
            $table->string('料號', 45);
            $table->string('品名', 100);
            $table->string('規格', 100);
            $table->integer('預退數量');
            $table->integer('實際退回數量');
            $table->string('備註', 45);
            $table->string('實退差異原因', 45)->nullable();
            $table->string('庫別', 45)->nullable();
            $table->string('收料人員', 45)->nullable();
            $table->string('收料人員工號', 45)->nullable();
            $table->string('退料人員', 45)->nullable();
            $table->string('退料人員工號', 45)->nullable();
            $table->string('退料單號', 45);
            $table->dateTime('開單時間');
            $table->dateTime('入庫時間')->nullable();
            $table->string('功能狀況', 45)->nullable();
            //$table->timestamps();
            //$table->softDeletes();
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
        Schema::dropIfExists('O庫出庫退料');
    }
}
