<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsTo在途量table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('在途量', function (Blueprint $table) {
            $table->string('說明', 200)->nullable(true);
            $table->string('修改人員', 200)->nullable(true);
            $table->dateTime('最後更新時間')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('在途量', function (Blueprint $table) {
            $table->dropColumn('說明');
            $table->dropColumn('修改人員');
            $table->dropColumn('最後更新時間');
        });
    }
}
