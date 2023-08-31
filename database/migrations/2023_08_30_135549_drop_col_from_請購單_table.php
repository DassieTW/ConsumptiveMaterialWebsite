<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColFrom請購單Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('請購單', function (Blueprint $table) {
            $table->string('規格', 100);
            //
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
            //
            $table->dropColumn('實際需求');
            $table->dropColumn('請購占比');
            $table->dropColumn('需求金額');
            $table->dropColumn('需求占比');
            $table->dropColumn('安全庫存');
        });
    }
}
