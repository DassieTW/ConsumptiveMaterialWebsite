<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInboundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbound', function (Blueprint $table) {
            $table->string('入庫單號', 45);
            $table->string('料號', 45);
            $table->integer('入庫數量');
            $table->string('儲位', 10);
            $table->string('入庫人員', 45);
            $table->string('客戶別', 45);
            $table->string('入庫原因', 45);
            $table->dateTime('入庫時間');
            $table->string('備註', 45)->nullable();
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
        Schema::dropIfExists('inbound');
    }
}
