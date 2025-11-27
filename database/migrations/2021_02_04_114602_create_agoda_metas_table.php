<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgodaMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agoda_metas', function (Blueprint $table) {


            $table->increments('id');
            $table->String('hotel_id');
            $table->String('hotel_name');
            $table->String('translated_name')->nullable();
            $table->String('star_rating')->nullable();
            $table->String('continent_id')->nullable();


            $table->String('country_id')->nullable();
            $table->String('city_id')->nullable();
            $table->String('area_id')->nullable();
            $table->String('longitude')->nullable();
            $table->String('latitude')->nullable();
            $table->String('hotel_url')->nullable();
            $table->String('popularity_score')->nullable();
            $table->Text('remark')->nullable();


            $table->String('number_of_reviews')->nullable();
            $table->String('rating_average')->nullable();
            $table->Text('child_and_extra_bed_policy')->nullable();
            $table->String('accommodation_type')->nullable();
            $table->String('nationality_restrictions')->nullable();
            $table->Text('address')->nullable();
            $table->Text('hotel_description')->nullable();
            $table->Text('facilities')->nullable();
            $table->Text('pictures')->nullable();
            $table->Text('roomtypes')->nullable();
            $table->Text('facilitiesperroomtypes')->nullable();
           
         
            
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
        Schema::dropIfExists('agoda_metas');
    }
}
