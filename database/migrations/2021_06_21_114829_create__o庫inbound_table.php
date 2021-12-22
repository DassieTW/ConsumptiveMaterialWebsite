<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateO庫InboundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('O庫inbound', function (Blueprint $table) {
            $table->string('入庫單號',45);
            $table->string('料號',100);
            $table->string('品名',100);
            $table->string('規格',100);
            $table->string('客戶別',45);
            $table->string('庫別',50);
            $table->integer('數量');
            $table->string('入庫人員',100);
            $table->dateTime('時間');
            $table->string('備註',45)->nullable();
            $table->string('入庫原因',45);
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
        Schema::dropIfExists('O庫inbound');
    }
}
