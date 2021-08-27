<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

<<<<<<< HEAD
class CreateO庫outboundTable extends Migration
=======
class CreateO庫OutboundTable extends Migration
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
        Schema::create('O庫outbound', function (Blueprint $table) {
<<<<<<< HEAD
            $table->string('料號',100);
            $table->string('品名',100);
            $table->string('規格',100);
            $table->string('廠別',45);
            $table->string('庫別',50);
            $table->integer('數量');
            $table->string('領料人',100);
            $table->dateTime('時間');
            $table->string('備註',45)->nullable();
=======
            $table->string('客戶別', 30);
            $table->string('機種', 30);
            $table->string('製程', 30);
            $table->string('領用原因', 30);
            $table->string('線別', 30);
            $table->string('料號', 12);
            $table->string('品名', 100);
            $table->string('規格', 100);
            $table->integer('預領數量');
            $table->integer('實際領用數量');
            $table->string('備註', 45);
            $table->string('實領差異原因', 45)->nullable();;
            $table->string('庫別', 45)->nullable();;
            $table->string('領料人員', 45)->nullable();;
            $table->string('領料人員工號', 45)->nullable();;
            $table->string('發料人員', 45)->nullable();;
            $table->string('發料人員工號', 45)->nullable();;
            $table->string('領料單號', 45);
            $table->dateTime('開單時間');
            $table->dateTime('出庫時間')->nullable();;
>>>>>>> 0827tony
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
        Schema::dropIfExists('O庫outbound');
    }
}
