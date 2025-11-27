<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Flight\FlightReactController;
use App\Http\Controllers\Flight\FlightReactController_Live;
use App\Http\Controllers\ReactApi\PackageController;
use App\Http\Controllers\ReactApi\CombineBookingController;


Route::post('flight_search',[FlightReactController::class,'flight_search']);
Route::post('flight_revalidation',[FlightReactController::class,'flight_revalidation']);
Route::post('flight_booking_confirm',[FlightReactController::class,'flight_booking_confirm']);
Route::post('flight_credit_limit',[FlightReactController::class,'flight_credit_limit']);
Route::post('get_markup_flight_new',[FlightReactController::class,'get_markup_flight_new']);
Route::post('flight_invoice_new',[FlightReactController::class,'flight_invoice_new']);

Route::post('flight_search_Live',[FlightReactController_Live::class,'flight_search']);
Route::post('flight_revalidation_Live',[FlightReactController_Live::class,'flight_revalidation']);
Route::post('flight_booking_confirm_Live',[FlightReactController_Live::class,'flight_booking_confirm']);
Route::post('flight_credit_limit_Live',[FlightReactController_Live::class,'flight_credit_limit']);
Route::post('get_markup_flight_new_Live',[FlightReactController_Live::class,'get_markup_flight_new']);
Route::post('flight_invoice_new_Live',[FlightReactController_Live::class,'flight_invoice_new']);

Route::post('get_all_tour_list_apis_new',[PackageController::class,'get_all_tour_list_apis_new']);
Route::post('get_all_catigories_list_apis_new',[PackageController::class,'get_all_catigories_list_apis_new']);
Route::post('combine_booking_apis_new',[CombineBookingController::class,'combine_booking_apis_new']);
Route::post('combine_invoice_apis_new',[CombineBookingController::class,'combine_invoice_apis_new']);


