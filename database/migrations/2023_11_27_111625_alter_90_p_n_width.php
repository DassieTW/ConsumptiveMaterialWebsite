<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Alter90PNWidth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('月請購_單耗', function (Blueprint $table) {
            $table->dropPrimary(['料號', '料號90']);
        });
        \DB::statement("ALTER TABLE [月請購_單耗] ALTER COLUMN [料號90] nvarchar(50) NOT NULL;");
        Schema::table('月請購_單耗', function (Blueprint $table) {
            $table->primary(['料號', '料號90']);
        });

        Schema::table('MPS', function (Blueprint $table) {
            $table->dropPrimary(['料號', '料號90']);
        });
        \DB::statement("ALTER TABLE [MPS] ALTER COLUMN [料號90] nvarchar(50) NOT NULL;");
        Schema::table('MPS', function (Blueprint $table) {
            $table->primary(['料號', '料號90']);
        });
    } // up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('月請購_單耗', function (Blueprint $table) {
            $table->dropPrimary(['料號', '料號90']);
        });
        \DB::statement("ALTER TABLE [月請購_單耗] ALTER COLUMN [料號90] nvarchar(12) NOT NULL;");
        Schema::table('月請購_單耗', function (Blueprint $table) {
            $table->primary(['料號', '料號90']);
        });

        Schema::table('MPS', function (Blueprint $table) {
            $table->dropPrimary(['料號', '料號90']);
        });
        \DB::statement("ALTER TABLE [MPS] ALTER COLUMN [料號90] nvarchar(12) NOT NULL;");
        Schema::table('MPS', function (Blueprint $table) {
            $table->primary(['料號', '料號90']);
        });
    } // down
}
