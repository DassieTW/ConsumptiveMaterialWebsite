<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateO庫不良品InventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('O庫不良品inventory', function (Blueprint $table) {
            $table->string('料號', 45);
            $table->integer('現有庫存');
            $table->string('客戶別', 45);
            $table->string('庫別', 45);
            $table->dateTime('最後更新時間');
            $table->string('品名', 100);
            $table->string('規格', 100);
            $table->primary(['料號', '客戶別', '庫別']);
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
        Schema::dropIfExists('O庫不良品inventory');
    }
}
