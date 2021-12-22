<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create非月請購Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('非月請購', function (Blueprint $table) {
            $table->string('客戶別',30);
            $table->string('料號',12);
            $table->integer('請購數量');
            $table->dateTime('上傳時間');
            $table->string('說明',50)->nullable();
            $table->string('SXB單號',50)->nullable();
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
        Schema::dropIfExists('非月請購');
    }
}
