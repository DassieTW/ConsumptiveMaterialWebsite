<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSafeStockColOnPRTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
            IF EXISTS (
                SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = N'請購單'
                AND COLUMN_NAME = N'安全庫存'
                AND TABLE_SCHEMA='DBO'
            )
            BEGIN
                ALTER TABLE [請購單]
                DROP COLUMN [安全庫存]
            END
        ");
    } // end function up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {} // end function down
}
