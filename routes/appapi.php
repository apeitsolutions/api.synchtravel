<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AppApi\WebApiController;
use App\Http\Controllers\AppApi\HotelBookingController;
use App\Http\Controllers\AppApi\LoginController;
use App\Http\Controllers\AppApi\ActivityAppController;


Route::post('hotel/search',[WebApiController::class,'hotel_search']);
Route::post('hotel/checkrates',[WebApiController::class,'hotel_checkrates']);
Route::post('hotel/reservation',[WebApiController::class,'hotel_reservation']);
Route::post('hotel/reservation/cancel',[WebApiController::class,'hotel_reservation_cancel']);
Route::post('hotel/reservation/{invoice_no}',[WebApiController::class,'voucher']);





Route::post('hotel_search_new',[HotelBookingController::class,'search_hotels']);
Route::post('hotels_mata_data_new',[HotelBookingController::class,'hotels_mata_apis']);
Route::post('hotel_details_new',[HotelBookingController::class,'view_hotel_details']);
Route::post('booking_room_new',[HotelBookingController::class,'booking_room_new']);
Route::post('hotel_confirm_booking_new',[HotelBookingController::class,'hotel_confirm_booking_new']);
Route::post('hotel_reservation_view_new',[HotelBookingController::class,'hotel_reservation_view_new']);
Route::post('hotel_reservation_cancell_new',[HotelBookingController::class,'hotel_reservation_cancell_new']);


Route::post('customer_login_new', function () {

        return "Token is wrong";

    }

)->name('customer_login_new');

// Mobiles Api For Website Customer Login

Route::post('customer_login_new',[LoginController::class,'customer_login']);
Route::post('customer_otp_verify_new',[LoginController::class,'customer_otp_verify']);



    

Route::post('customer_hotel_bookings_finds_new',[LoginController::class,'customer_hotel_booking']);
Route::group(['middleware'=>['auth:sanctum']], function (){
    
});


// Activities Api
Route::post('cites_suggestions_app',[ActivityAppController::class,'cites_suggestions']); 
Route::post('search_activities_app',[ActivityAppController::class,'search_activities']); 
Route::post('activity_details_app',[ActivityAppController::class,'activity_details']); 
Route::post('book_activity_app',[ActivityAppController::class,'book_activity']);

