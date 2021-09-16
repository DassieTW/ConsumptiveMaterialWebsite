<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckingInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checking_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('單號', 255);
            $table->string('料號', 12);
            $table->bigInteger('現有庫存');
            $table->string('儲位', 45);
            $table->string('客戶別', 255);
            $table->bigInteger('盤點')->nullable();
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
        Schema::dropIfExists('checking_inventory');
    }
}
