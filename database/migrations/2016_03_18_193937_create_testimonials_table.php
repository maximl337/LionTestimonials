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
            $table->integer('author_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->integer('rating')->unsigned();
            $table->text('body')->nullable();
            $table->string('video')->nullable();
            $table->timestamps();
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('subject_id')->references('id')->on('users');
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
