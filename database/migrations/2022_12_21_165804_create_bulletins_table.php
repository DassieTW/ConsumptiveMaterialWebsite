<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulletinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            
            $table->string('category', 25);
            $table->string('title', 25);
            $table->string('content', 255);
            $table->string('updated_by', 255);
            $table->string('site', 255);
            // level should be one of the list : "urgent", "important", "normal"
            $table->string('level', 255);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bulletins');
    } // down
}
