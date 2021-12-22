<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create調撥單Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('調撥單', function (Blueprint $table) {
            $table->string('料號', 12);
            $table->string('品名', 100);
            $table->string('規格', 100);
            $table->string('單位', 45);
            $table->integer('庫存');
            $table->integer('調撥數量');
            $table->string('調撥人', 45)->nullable();
            $table->string('接收人', 45)->nullable();
            $table->string('撥出廠區', 45);
            $table->string('接收廠區', 45);
            $table->integer('撥出數量')->nullable();
            $table->integer('接收數量')->nullable();
            $table->string('調撥單號', 45);
            $table->dateTime('開單時間');
            $table->dateTime('出庫時間')->nullable();
            $table->dateTime('入庫時間')->nullable();
            $table->string('狀態', 45);
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
        Schema::dropIfExists('調撥單');
    }
}
