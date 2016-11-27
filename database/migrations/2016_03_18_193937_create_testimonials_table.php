<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestimonialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contact_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('rating')->unsigned();
            $table->string('email');
            $table->text('body')->nullable();
            $table->string('invite_token')->comment = "Invitation token to prevent multiple testimonials";

            $table->string('token')->nullable()->comment = "Token received from Ziggeo";
            $table->string('thumbnail')->nullable();
            $table->string('url')->nullable();
            
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('seen_at')->nullable();
            $table->timestamps();
        });

        Schema::table('testimonials', function (Blueprint $table) {
            
            $table->foreign('user_id')->references('id')->on('users');

            $table->unique(['contact_id', 'user_id', 'invite_token']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('testimonials');
    }
}
