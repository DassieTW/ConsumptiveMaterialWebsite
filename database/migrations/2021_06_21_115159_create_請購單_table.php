<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create請購單Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('請購單', function (Blueprint $table) {
            $table->string('SRM單號', 45);
            $table->string('客戶', 45);
            $table->string('料號', 12);
            $table->string('品名', 100);
            $table->string('規格', 100);
            $table->integer('MOQ');
            $table->double('下月需求');
            $table->double('當月需求');
            $table->double('單價');
            $table->string('幣別', 45);
            $table->double('匯率');
            $table->double('在途數量');
            $table->double('現有庫存');
            $table->double('本次請購數量');
            $table->double('請購金額');
            $table->dateTime('請購時間');
            $table->string('SXB單號', 45)->nullable();
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
        Schema::dropIfExists('請購單');
    }
}
