<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThirdPartyTestimonialSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_party_testimonial_sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
            $table->string('provider');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('third_party_testimonial_sites', function (Blueprint $table) {
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
        Schema::drop('third_party_testimonial_sites');
    }
}
