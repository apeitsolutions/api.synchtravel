<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizedHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorized_hotels', function (Blueprint $table) {
            $table->increments('id');
            $table->String('code');
            $table->String('name');
            $table->String('rating');
            $table->Text('description');
            $table->String('umrahHotelCode');
            $table->String('category');
            $table->String('checkInTime');
            $table->String('checkOutTime');
            $table->String('locationCode');
            $table->String('languageCode');
            $table->Text('address');
            $table->String('latitude');
            $table->String('longitude');
            $table->String('city');
            $table->String('state');
            $table->String('country');
            $table->String('countryCode');
            $table->String('lastUpdated');
            $table->Text('hotelImages');
            $table->Text('hotelAmenities');
            $table->Text('roomTypes');
           
          
            $table->String('phone');
            $table->String('email');
            $table->String('fax');
         
            $table->String('provider');
            $table->String('vendor');
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
        Schema::dropIfExists('authorized_hotels');
    }
}
