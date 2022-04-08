<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSluggish報警備註Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sluggish報警備註', function (Blueprint $table) {
            $table->string('料號', 12);
            $table->string('客戶別', 45);
            $table->primary(['料號','客戶別']);
            $table->string('備註', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sluggish報警備註');
    }
}
