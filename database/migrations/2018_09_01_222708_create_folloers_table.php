<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFolloersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_follower_id')->unsigned();
                $table->foreign('user_follower_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('user_following_id')->unsigned();
                $table->foreign('user_following_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('acepted')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
