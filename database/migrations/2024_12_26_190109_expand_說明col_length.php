<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Expand說明ColLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE [在途量] ALTER COLUMN [說明] nvarchar(255) NULL;");
        \DB::statement("ALTER TABLE [非月請購] ALTER COLUMN [說明] nvarchar(255) NULL;");
        \DB::statement("ALTER TABLE [inbound] ALTER COLUMN [備註] nvarchar(255) NULL;");
        \DB::statement("ALTER TABLE [outbound] ALTER COLUMN [備註] nvarchar(255) NOT NULL;");
        \DB::statement("ALTER TABLE [出庫退料] ALTER COLUMN [備註] nvarchar(255) NOT NULL;");
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE [在途量] ALTER COLUMN [說明] nvarchar(200) NULL;");
        \DB::statement("ALTER TABLE [非月請購] ALTER COLUMN [說明] nvarchar(45) NULL;");
        \DB::statement("ALTER TABLE [inbound] ALTER COLUMN [備註] nvarchar(45) NULL;");
        \DB::statement("ALTER TABLE [outbound] ALTER COLUMN [備註] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [出庫退料] ALTER COLUMN [備註] nvarchar(45) NOT NULL;");
    } // down
}
