<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSafestock報警備註Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safestock報警備註', function (Blueprint $table) {
            $table->collation = 'SQL_Latin1_General_CP1256_CI_AS';
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
        Schema::dropIfExists('safestock報警備註');
    }
}
