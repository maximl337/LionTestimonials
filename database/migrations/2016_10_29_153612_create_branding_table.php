<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branding', function (Blueprint $table) {
            $table->increments('id');
            $table->string('primary_color');
            $table->string('background_color');
            $table->string('text_color');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('branding', function (Blueprint $table) {
            
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
        Schema::drop('branding');
    }
}
