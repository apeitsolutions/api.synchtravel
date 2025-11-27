<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
          $table->increments('id');


            $table->String('invoice_no')->nullable();
            $table->String('platform')->nullable();
            $table->String('total_passengers')->nullable();
            $table->String('lead_passenger_name')->nullable();
            $table->String('lead_passenger_email')->nullable();
            $table->String('lead_passenger_contact')->nullable();
            $table->String('lead_passenger_nationality')->nullable();
            $table->String('lead_passenger_residence')->nullable();
            $table->String('lead_passenger_gender')->nullable();
            $table->String('lead_passenger_date_of_birth')->nullable();
            $table->String('lead_passenger_passport_no')->nullable();
            $table->String('lead_passenger_passport_expiry')->nullable();
           

            $table->longText('hotel_makkah_checkavailability')->nullable();
            $table->longText('hotel_madinah_checkavailability')->nullable();
            $table->longText('transfer_checkavailability')->nullable();
            $table->longText('ground_service_checkavailability')->nullable();

            $table->String('checkin')->nullable();
            $table->String('checkout')->nullable();
            

            $table->String('booking_currency')->nullable();
            $table->String('hotel_makkah_total_amount')->nullable();
            $table->String('hotel_madina_total_amount')->nullable();
            $table->String('transfer_total_amount')->nullable();
            $table->String('ground_service_total_amount')->nullable();

            $table->String('hotel_makkah_brn')->nullable();
            $table->String('hotel_madina_brn')->nullable();
            $table->String('transfer_brn')->nullable();
            $table->String('ground_service_brn')->nullable();

            $table->String('hotel_makkah_info_token')->nullable();
            $table->String('hotel_madina_info_token')->nullable();
            $table->String('transfer_info_token')->nullable();
            $table->String('ground_service_info_token')->nullable();

            $table->String('hotel_makkah_booking_status')->nullable();
            $table->String('hotel_madina_booking_status')->nullable();
            $table->String('transfer_booking_status')->nullable();
            $table->String('ground_service_booking_status')->nullable();


            
            $table->longText('lead_passenger_details')->nullable();
            $table->longText('other_passenger_details')->nullable();

            $table->String('total_visa_fee')->nullable();
            $table->String('booking_grand_total')->nullable();

          



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
        Schema::dropIfExists('bookings');
    }
}
