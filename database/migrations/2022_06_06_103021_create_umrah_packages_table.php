<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmrahPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('umrah_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('package_name')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->string('status')->nullable();

            $table->string('makkah_hotel_name')->nullable();
            $table->string('makkah_location')->nullable();
            $table->string('makkah_check_in')->nullable();
            $table->string('makkah_check_out')->nullable();
            $table->string('makkah_no_of_nights')->nullable();
            $table->string('makkah_sharing_1')->nullable();
            $table->string('makkah_sharing_2')->nullable();
            $table->string('makkah_sharing_3')->nullable();
            $table->string('makkah_sharing_4')->nullable();
            $table->string('makkah_price')->nullable();
            $table->string('makkah_board_basis')->nullable();
            $table->string('makkah_room_views')->nullable();
            $table->string('makkah_images')->nullable();

            $table->string('madina_hotel_name')->nullable();
            $table->string('madina_location')->nullable();
            $table->string('madina_check_in')->nullable();
            $table->string('madina_check_out')->nullable();
            $table->string('madina_no_of_nights')->nullable();
            $table->string('madina_sharing_1')->nullable();
            $table->string('madina_sharing_2')->nullable();
            $table->string('madina_sharing_3')->nullable();
            $table->string('madina_sharing_4')->nullable();
            $table->string('madina_price')->nullable();
            $table->string('madina_board_basis')->nullable();
            $table->string('madina_room_views')->nullable();
            $table->string('madina_images')->nullable();

            $table->string('transfer_pickup_location')->nullable();
            $table->string('transfer_drop_location')->nullable();
            $table->string('transfer_pickup_date_time')->nullable();
            $table->string('transfer_vehicle')->nullable();
            $table->string('transfer_images')->nullable();

            $table->string('flights_airline')->nullable();
            $table->string('flights_departure_airport')->nullable();
            $table->string('flights_arrival_airport')->nullable();
            $table->string('flights_departure__return_airport')->nullable();
            $table->string('flights_arrival_return_airport')->nullable();
            $table->string('flights_departure__return_date')->nullable();
            $table->string('flights_arrival_return_date')->nullable();
            $table->string('flights_price')->nullable();

            $table->string('visa_fee')->nullable();
            $table->string('visit_visa_fee')->nullable();
            $table->string('details_visa')->nullable();

            $table->string('administration_charges')->nullable();
            $table->string('administration_details')->nullable();
            
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('umrah_packages');
    }
}
