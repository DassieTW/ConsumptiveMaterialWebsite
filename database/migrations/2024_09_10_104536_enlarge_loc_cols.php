<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnlargeLocCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE [inbound] ALTER COLUMN [儲位] nvarchar(255) NOT NULL;");
        \DB::statement("ALTER TABLE [儲位] ALTER COLUMN [儲存位置] nvarchar(255) NOT NULL;");
        \DB::statement("ALTER TABLE [inventory] ALTER COLUMN [儲位] nvarchar(255) NOT NULL;");
        \DB::statement("ALTER TABLE [outbound] ALTER COLUMN [儲位] nvarchar(255) NULL;");
        \DB::statement("ALTER TABLE [出庫退料] ALTER COLUMN [儲位] nvarchar(255) NULL;");
        \DB::statement("ALTER TABLE [不良品inventory] ALTER COLUMN [儲位] nvarchar(255) NOT NULL;");
        \DB::statement("ALTER TABLE [checking_inventory] ALTER COLUMN [儲位] nvarchar(255) NOT NULL;");
        \DB::statement("ALTER TABLE [撥出明細] ALTER COLUMN [儲位] nvarchar(255) NOT NULL;");
        \DB::statement("ALTER TABLE [接收明細] ALTER COLUMN [儲位] nvarchar(255) NOT NULL;");
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE [inbound] ALTER COLUMN [儲位] nvarchar(10) NOT NULL;");
        \DB::statement("ALTER TABLE [儲位] ALTER COLUMN [儲存位置] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [inventory] ALTER COLUMN [儲位] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [outbound] ALTER COLUMN [儲位] nvarchar(45) NULL;");
        \DB::statement("ALTER TABLE [出庫退料] ALTER COLUMN [儲位] nvarchar(45) NULL;");
        \DB::statement("ALTER TABLE [不良品inventory] ALTER COLUMN [儲位] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [checking_inventory] ALTER COLUMN [儲位] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [撥出明細] ALTER COLUMN [儲位] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [接收明細] ALTER COLUMN [儲位] nvarchar(45) NOT NULL;");
    } // down
} // EnlargeLocCols
