<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('categories')->nullable();
            $table->string('attributes')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('time_duration')->nullable();
            $table->string('tour_min_people')->nullable();
            $table->string('tour_max_people')->nullable();
            $table->string('Itinerary_details')->nullable();
            $table->string('tour_location')->nullable();
            $table->string('tour_pricing')->nullable();
            $table->string('tour_publish')->nullable();
            $table->string('tour_author')->nullable();
            $table->string('tour_feature')->nullable();
            $table->string('defalut_state')->nullable();
            $table->string('tour_featured_image')->nullable();
            $table->string('tour_banner_image')->nullable();
            $table->string('tour_extra_price')->nullable();
            $table->string('tour_faq')->nullable();
           
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
        Schema::dropIfExists('tours');
    }
}
