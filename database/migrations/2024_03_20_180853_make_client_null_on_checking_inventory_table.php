<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeClientNullOnCheckingInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE [checking_inventory] ALTER COLUMN [客戶別] nvarchar(255) NULL;");
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE [checking_inventory] ALTER COLUMN [客戶別] nvarchar(255) NOT NULL;");
    } // down
}
