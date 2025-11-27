<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadPassengerLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead__passenger_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('lead_passenger_id');
            $table->string('lead_passenger_email');
            $table->string('loc_address')->nullable();
            $table->string('loc_latitude')->nullable();
            $table->string('loc_longitude')->nullable();
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
        Schema::dropIfExists('lead__passenger_locations');
    }
}
