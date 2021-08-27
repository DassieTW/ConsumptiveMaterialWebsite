<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login', function (Blueprint $table) {
            $table->string('username',45)->primary();
            $table->string('password', 200);
            $table->tinyInteger('priority');
            $table->string('姓名',50)->nullable();
            $table->string('部門',50)->nullable();
<<<<<<< HEAD
            $table->timestamps();
=======
            $table->integer('avatarChoice')->nullable();
            $table->timestamps();
            $table->softDeletes();
>>>>>>> 0827tony
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login');
    }
}
