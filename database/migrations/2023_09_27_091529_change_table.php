<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('consumptive_material', function (Blueprint $table) {
            $table->dropColumn(['耗材歸屬']);
        });

        Schema::table('在途量', function (Blueprint $table) {
            $table->dropPrimary(['客戶', '料號']);
        });
        
        DB::statement("ALTER TABLE [在途量] ALTER COLUMN [客戶] nvarchar(45) NULL;");

        Schema::table('在途量', function (Blueprint $table) {
            $table->primary('料號');
        });

        Schema::table('inventory', function (Blueprint $table) {
            $table->dropPrimary(['料號', '儲位', '客戶別']);
        });
        Schema::table('inventory', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->primary(['料號', '儲位']);
        });
        Schema::table('不良品', function (Blueprint $table) {
            $table->dropPrimary(['料號', '客戶別']);
        });

        DB::statement("ALTER TABLE [不良品] ALTER COLUMN [客戶別] nvarchar(45) NULL;");

        Schema::table('不良品', function (Blueprint $table) {
            $table->primary('料號');
        });
        Schema::table('不良品inventory', function (Blueprint $table) {
            $table->dropPrimary(['料號', '儲位', '客戶別']);
        });

        DB::statement("ALTER TABLE [不良品inventory] ALTER COLUMN [客戶別] nvarchar(45) NULL;");

        Schema::table('不良品inventory', function (Blueprint $table) {
            $table->primary(['料號', '儲位']);
        });

        Schema::table('inbound', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
        });

        Schema::table('MPS', function (Blueprint $table) {
            $table->dropPrimary(['客戶別', '機種', '製程']);
        });
        Schema::table('MPS', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->string('機種', 45)->nullable()->change();
            $table->string('製程', 45)->nullable()->change();
            $table->string('料號', 12);

            $table->primary(['料號', '料號90']);
        });
        Schema::table('月請購_單耗', function (Blueprint $table) {
            $table->dropPrimary(['料號', '客戶別', '機種', '製程']);
        });

        DB::statement("ALTER TABLE [月請購_單耗] ALTER COLUMN [客戶別] nvarchar(45) NULL;");
        DB::statement("ALTER TABLE [月請購_單耗] ALTER COLUMN [機種] nvarchar(45) NULL;");
        DB::statement("ALTER TABLE [月請購_單耗] ALTER COLUMN [製程] nvarchar(45) NULL;");

        Schema::table('月請購_單耗', function (Blueprint $table) {
            $table->primary(['料號', '料號90']);
        });
        Schema::table('outbound', function (Blueprint $table) {
            $table->string('客戶別', 45)->nullable()->change();
            $table->string('機種', 45)->nullable()->change();
            $table->string('製程', 45)->nullable()->change();
        });

        DB::statement("ALTER TABLE [出庫退料] ALTER COLUMN [客戶別] nvarchar(45) NULL;");
        DB::statement("ALTER TABLE [出庫退料] ALTER COLUMN [機種] nvarchar(45) NULL;");
        DB::statement("ALTER TABLE [出庫退料] ALTER COLUMN [製程] nvarchar(45) NULL;");

        DB::statement("ALTER TABLE [請購單] ALTER COLUMN [客戶] nvarchar(45) NULL;");

        DB::statement("ALTER TABLE [非月請購] ALTER COLUMN [客戶別] nvarchar(45) NULL;");

        Schema::table('非月請購', function (Blueprint $table) {
            $table->primary('料號');
        });

        Schema::table('safestock報警備註', function (Blueprint $table) {
            $table->dropPrimary(['料號', '客戶別']);
        });

        DB::statement("ALTER TABLE [safestock報警備註] ALTER COLUMN [客戶別] nvarchar(45) NULL;");

        Schema::table('safestock報警備註', function (Blueprint $table) {
            $table->primary('料號');
        });
        Schema::table('sluggish報警備註', function (Blueprint $table) {
            $table->dropPrimary(['料號', '客戶別']);
        });

        DB::statement("ALTER TABLE [sluggish報警備註] ALTER COLUMN [客戶別] nvarchar(45) NULL;");

        Schema::table('sluggish報警備註', function (Blueprint $table) {
            $table->primary('料號');
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
    }
}
