<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create月請購單耗Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('月請購_單耗', function (Blueprint $table) {
            $table->string('料號', 12);
            $table->string('客戶別', 45);
            $table->string('機種', 45);
            $table->string('製程', 45);
            $table->primary(['料號','客戶別','機種','製程']);
            $table->float('單耗');
            $table->string('狀態', 45);
            $table->string('畫押工號', 45);
            $table->string('畫押信箱', 45);
            $table->dateTime('畫押時間')->nullable();
            $table->string('紀錄',255)->nullable();
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
        Schema::dropIfExists('月請購_單耗');
    }
}
