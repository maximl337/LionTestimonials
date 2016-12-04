<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalVendorReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_vendor_reviews', function (Blueprint $table) {
            
            $table->increments('id');
            $table->text('body');
            $table->string('url');
            $table->string('author');
            $table->datetime('review_date');
            $table->integer('rating')->unsigned();
            $table->integer('external_review_site_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

        });

        Schema::table('external_vendor_reviews', function (Blueprint $table) {
            
            $table->foreign('external_review_site_id')
                    ->references('id')
                    ->on('third_party_testimonial_sites')
                    ->onDelete('cascade');
            
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
        Schema::drop('external_vendor_reviews');
    }
}
