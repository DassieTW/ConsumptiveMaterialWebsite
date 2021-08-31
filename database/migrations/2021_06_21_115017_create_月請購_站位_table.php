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
            $table->float('當月站位人數');
            $table->float('當月開線數');
            $table->float('當月開班數');
            $table->float('當月每人每日需求量');
            $table->float('當月每日更換頻率');
            $table->float('下月站位人數');
            $table->float('下月開線數');
            $table->float('下月開班數');
            $table->float('下月每人每日需求量');
            $table->float('下月每日更換頻率');
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
        Schema::dropIfExists('月請購_站位');
    }
}
