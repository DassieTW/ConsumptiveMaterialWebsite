<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetBuylistTablePk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE [請購單] ALTER COLUMN [SXB單號] nvarchar(100) NOT NULL;");
        Schema::table('請購單', function (Blueprint $table) {
            $table->primary(['料號', 'SXB單號']);
        });
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE [請購單] ALTER COLUMN [SXB單號] nvarchar(45) NULL;");
        Schema::table('請購單', function (Blueprint $table) {
            $table->dropPrimary(['料號', 'SXB單號']);
        });
    } // down
}
