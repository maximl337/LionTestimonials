<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('title');
            $table->string('token')->comment = "Token received from Ziggeo";
            $table->string('thumbnail');
            $table->string('url');
            $table->string('gif_path')->nullable();
            $table->boolean('profile_video')->default(false);
            $table->boolean('thanks_video')->default(false);
            $table->timestamps();
        });

        Schema::table('videos', function($table) {
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('videos');
    }
}
