<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsToSSZInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('SSZInfo', function (Blueprint $table) {
            $table->string('ClaimedStaff', 45)->nullable(true);
            $table->dateTime('claimed_time')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('SSZInfo', function (Blueprint $table) {
            $table->dropColumn('ClaimedStaff');
            $table->dropColumn('claimed_time');
        });
    }
}
