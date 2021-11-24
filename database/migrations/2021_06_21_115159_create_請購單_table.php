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
            $table->string('SRM單號', 50);
            $table->string('客戶', 30);
            $table->string('料號', 12);
            $table->string('品名', 100);
            $table->integer('MOQ');
            $table->double('下月需求');
            $table->double('當月需求');
            $table->double('安全庫存');
            $table->double('單價');
            $table->string('幣別', 12);
            $table->double('匯率');
            $table->double('在途數量');
            $table->double('現有庫存');
            $table->double('本次請購數量');
            $table->double('實際需求');
            $table->double('請購金額');
            $table->string('請購占比', 50);
            $table->double('需求金額');
            $table->string('需求占比', 50);
            $table->dateTime('請購時間');
            $table->string('SXB單號', 50)->nullable();
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
        Schema::dropIfExists('請購單');
    }
}
