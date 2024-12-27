<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameRedundantColsOnPickBackListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("EXEC sp_rename @objname = N'dbo.outbound.領料人員工號', @newname = N'領料人員', @objtype = 'COLUMN';");
        \DB::statement("EXEC sp_rename @objname = N'dbo.outbound.發料人員工號', @newname = N'發料人員', @objtype = 'COLUMN';");

        \DB::statement("EXEC sp_rename @objname = N'dbo.出庫退料.收料人員工號', @newname = N'收料人員', @objtype = 'COLUMN';");
        \DB::statement("EXEC sp_rename @objname = N'dbo.出庫退料.退料人員工號', @newname = N'退料人員', @objtype = 'COLUMN';");
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("EXEC sp_rename @objname = N'dbo.outbound.領料人員', @newname = N'領料人員工號', @objtype = 'COLUMN';");
        \DB::statement("EXEC sp_rename @objname = N'dbo.outbound.發料人員', @newname = N'發料人員工號', @objtype = 'COLUMN';");

        \DB::statement("EXEC sp_rename @objname = N'dbo.出庫退料.收料人員', @newname = N'收料人員工號', @objtype = 'COLUMN';");
        \DB::statement("EXEC sp_rename @objname = N'dbo.出庫退料.退料人員', @newname = N'退料人員工號', @objtype = 'COLUMN';");
    } // down
}
