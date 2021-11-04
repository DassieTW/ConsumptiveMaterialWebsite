<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumptiveMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumptive_material', function (Blueprint $table) {
            $table->string('料號', 12);
            $table->primary('料號');
            $table->string('品名', 100);
            $table->string('規格', 100);
            $table->double('單價');
            $table->string('幣別', 45);
            $table->string('單位', 45);
            $table->integer('MPQ');
            $table->integer('MOQ');
            $table->double('LT');
            $table->string('月請購', 4);
            $table->string('A級資材', 4);
            $table->string('耗材歸屬', 45);
            $table->string('發料部門', 45);
            $table->integer('安全庫存')->nullable();
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
        Schema::dropIfExists('consumptive_material');
    }
}
