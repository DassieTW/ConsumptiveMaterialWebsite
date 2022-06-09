<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create月請購站位Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('月請購_站位', function (Blueprint $table) {
            $table->string('料號', 12);
            $table->string('客戶別', 45);
            $table->string('機種', 45);
            $table->string('製程', 45);
            $table->primary(['料號','客戶別','機種','製程']);
            $table->double('當月站位人數');
            $table->double('當月開線數');
            $table->double('當月開班數');
            $table->double('當月每人每日需求量');
            $table->double('當月每日更換頻率');
            $table->double('下月站位人數');
            $table->double('下月開線數');
            $table->double('下月開班數');
            $table->double('下月每人每日需求量');
            $table->double('下月每日更換頻率');
            $table->string('狀態', 45);
            $table->string('畫押信箱', 45);
            $table->dateTime('畫押時間')->nullable();
            $table->dateTime('送單時間');
            $table->string('送單人', 45);
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
        Schema::dropIfExists('月請購_站位');
    }
}
