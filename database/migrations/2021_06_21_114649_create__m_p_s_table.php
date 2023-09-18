<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('MPS', function (Blueprint $table) {
            $table->string('客戶別', 45);
            $table->string('機種', 45);
            $table->string('製程', 45);
            $table->string('料號', 12);
            $table->double('下月MPS');
            $table->double('下月生產天數');
            $table->double('本月MPS');
            $table->double('本月生產天數');
            $table->dateTime('填寫時間');
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
        Schema::dropIfExists('MPS');
    }
}
