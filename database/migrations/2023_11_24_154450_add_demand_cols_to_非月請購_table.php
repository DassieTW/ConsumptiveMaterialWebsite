<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDemandColsTo非月請購Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('非月請購', function (Blueprint $table) {
            $table->double('當月需求')->nullable(false);
            $table->double('下月需求')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('非月請購', function (Blueprint $table) {
            $table->dropColumn('當月需求');
            $table->dropColumn('下月需求');
        });
    }
}
