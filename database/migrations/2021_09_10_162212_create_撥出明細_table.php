<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create撥出明細Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('撥出明細', function (Blueprint $table) {
            $table->string('調撥單號', 45);
            $table->string('客戶別', 45);
            $table->string('撥出廠區', 45);
            $table->string('接收廠區', 45);
            $table->string('料號',12);
            $table->string('品名',100);
            $table->string('規格', 100);
            $table->integer('現有庫存');
            $table->integer('預計撥出數量');
            $table->integer('實際撥出數量');
            $table->string('儲位',50);
            $table->string('調撥人',45);
            $table->dateTime('撥出時間');
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
        Schema::dropIfExists('撥出明細');
    }
}
