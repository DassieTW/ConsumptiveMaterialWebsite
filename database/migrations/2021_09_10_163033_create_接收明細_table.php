<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create接收明細Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('接收明細', function (Blueprint $table) {
            $table->string('調撥單號', 45);
            $table->string('客戶別', 45);
            $table->string('撥出廠區', 45);
            $table->string('接收廠區', 45);
            $table->string('料號',12);
            $table->string('品名',100);
            $table->string('規格', 100);
            $table->integer('實際接收數量');
            $table->integer('實際撥出數量');
            $table->string('儲位',50);
            $table->string('接收人',45);
            $table->dateTime('接收時間');
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
        Schema::dropIfExists('接收明細');
    }
}
