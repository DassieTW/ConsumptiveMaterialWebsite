<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSSZNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SSZNumber', function (Blueprint $table) {
            $table->string('id', 45)->primary();
            $table->string('status', 20);
            $table->dateTime('received_time');
        });
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('SSZNumber');
    } // down
} // CreateSSZNumberTable
