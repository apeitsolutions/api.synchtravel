<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmrahmetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('umrahmetas', function (Blueprint $table) {
             $table->increments('id');
            $table->String('cityname')->nullable();
            $table->String('hotelid')->nullable();
            $table->String('floors')->nullable();
            $table->String('noOfRooms')->nullable();
            $table->String('luxury')->nullable();
            $table->String('provider')->nullable();
            $table->String('fullAddress')->nullable();



            $table->String('description1')->nullable();
            $table->String('hotelName')->nullable();
            $table->String('address')->nullable();
            $table->String('countryName')->nullable();
            $table->String('amenitie')->nullable();
            $table->String('hotelPhone')->nullable();
            $table->String('lat')->nullable();
            $table->String('lng')->nullable();
            $table->String('rooms')->nullable();
           

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
        Schema::dropIfExists('umrahmetas');
    }
}
