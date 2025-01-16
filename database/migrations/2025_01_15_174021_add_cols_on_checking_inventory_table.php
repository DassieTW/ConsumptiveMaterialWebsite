<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsOnCheckingInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("DELETE FROM [checking_inventory];");
        \DB::statement("
        DECLARE @sql NVARCHAR(MAX) = N'';
        SELECT @sql += N'
        ALTER TABLE ' + QUOTENAME(OBJECT_SCHEMA_NAME(parent_object_id))
        + '.' + QUOTENAME(OBJECT_NAME(parent_object_id)) +
        ' DROP CONSTRAINT ' + QUOTENAME(name) + ';'
        FROM sys.objects
        WHERE type_desc LIKE '%CONSTRAINT'
        AND OBJECT_NAME(PARENT_OBJECT_ID) LIKE 'checking_inventory';
        EXEC sp_executesql @sql;");
        \DB::statement("ALTER TABLE [checking_inventory] DROP COLUMN [id];");


        Schema::table('checking_inventory', function (Blueprint $table) {
            $table->string('單號', 255);
            $table->string('approved_by', 255)->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->primary(['料號', '儲位', '單號']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checking_inventory', function (Blueprint $table) {
            $table->dropPrimary(['料號', '儲位', '單號']);
            $table->dropColumn(['單號', 'approved_by', 'approved_at']);
            $table->bigInteger('id')->autoIncrement();
        });
    }
}
