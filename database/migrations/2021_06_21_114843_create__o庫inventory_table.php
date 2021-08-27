<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

<<<<<<< HEAD
class CreateO庫inventoryTable extends Migration
=======
class CreateO庫InventoryTable extends Migration
>>>>>>> 0827tony
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('O庫inventory', function (Blueprint $table) {
            $table->string('料號',12);
            $table->integer('現有庫存');
<<<<<<< HEAD
            $table->string('廠別',45);
            $table->string('庫別',45);
=======
            $table->string('客戶別',45);
            $table->string('庫別',45);
            $table->primary(['料號','客戶別','庫別']);
>>>>>>> 0827tony
            $table->dateTime('最後更新時間');
            $table->string('品名',100);
            $table->string('規格',100);
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
        //
        Schema::dropIfExists('O庫inventory');
    }
}
