<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create不良品Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('不良品', function (Blueprint $table) {
            $table->string('料號',12);
            $table->string('客戶別',45);
            $table->primary(['料號','客戶別']);
            $table->string('入庫原因',45);
            $table->float('入庫數量');;
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
        //
        Schema::dropIfExists('不良品');
    }
}
