<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRedundantColsOnPRTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('請購單', function (Blueprint $table) {
            if (Schema::hasColumn('請購單', '實際需求')) {
                $table->dropColumn('實際需求');
            } // if

            if (Schema::hasColumn('請購單', '請購占比')) {
                $table->dropColumn('請購占比');
            } // if

            if (Schema::hasColumn('請購單', '需求金額')) {
                $table->dropColumn('需求金額');
            } // if

            if (Schema::hasColumn('請購單', '需求占比')) {
                $table->dropColumn('需求占比');
            } // if
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('請購單', function (Blueprint $table) {
            $table->double('實際需求');
            $table->string('請購占比', 45);
            $table->double('需求金額');
            $table->string('需求占比', 45);
        });
    }
}
