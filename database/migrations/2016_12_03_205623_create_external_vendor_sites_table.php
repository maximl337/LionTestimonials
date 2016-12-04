<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalVendorSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_vendor_sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_token');
            $table->string('token_type');
            $table->dateTime('expires_at');
            $table->string('provider');
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
        Schema::drop('external_vendor_sites');
    }
}
