<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSszinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SSZInfo', function (Blueprint $table) {
            $table->string('FlowNumber', 45);
            $table->string('Applicant', 255);
            $table->string('MatShort', 100);
            $table->integer('relQty');
            $table->string('MaterialType', 100);
            $table->string('Company', 100);
            $table->string('DeptManager1', 255)->nullable();
            $table->string('CostDept', 255);
            $table->string('Spec', 255);
            $table->string('Keeper', 255);
            $table->string('SSZMemo', 255)->nullable();
        });

        Schema::table('SSZInfo', function (Blueprint $table) {
            $table->primary(['FlowNumber', 'MatShort']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sszinfo');
    }
}
