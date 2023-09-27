<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add90PNToMPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('MPS', function (Blueprint $table) {
            $table->string('料號90', 12);
            $table->string('料號', 12);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('MPS', function (Blueprint $table) {
            $table->dropColumn('料號90');
            $table->dropColumn('料號');
        });
    }
}
