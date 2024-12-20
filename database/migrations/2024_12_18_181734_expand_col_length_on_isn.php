<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExpandColLengthOnIsn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE [consumptive_material] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [inbound] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [inventory] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [outbound] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [不良品] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [月請購_站位] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [月請購_單耗] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [出庫退料] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [在途量] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [非月請購] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [請購單] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [不良品inventory] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [checking_inventory] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [調撥單] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [撥出明細] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [接收明細] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [safestock報警備註] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [sluggish報警備註] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [MPS] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [LocTransferRecord] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
        \DB::statement("ALTER TABLE [inventory_change_records] ALTER COLUMN [料號] nvarchar(45) NOT NULL;");
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE [consumptive_material] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [inbound] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [inventory] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [outbound] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [不良品] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [月請購_站位] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [月請購_單耗] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [出庫退料] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [在途量] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [非月請購] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [請購單] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [不良品inventory] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [checking_inventory] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [調撥單] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [撥出明細] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [接收明細] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [safestock報警備註] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [sluggish報警備註] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [MPS] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [LocTransferRecord] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
        \DB::statement("ALTER TABLE [inventory_change_records] ALTER COLUMN [料號] nvarchar(12) NOT NULL;");
    } // down
}
