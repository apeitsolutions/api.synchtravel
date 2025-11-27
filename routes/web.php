<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\HotelMangers\RoomController;
use App\Http\Controllers\HotelMangers\HotelController;
use App\Http\Controllers\frontend\admin_dashboard\ManageQuotationsController;

use App\Http\Controllers\ActivityController;

use App\Models\city;
use App\Http\Controllers\Travellanda_MetaDataController;
use App\Http\Controllers\Hotelbeds_MetaDataController;
use App\Http\Controllers\TBOHolidays_HotelAPI_Controller;

use App\Http\Controllers\bookingController;
use App\Http\Controllers\frontend\admin_dashboard\AccountDetailsController;
use App\Http\Controllers\frontend\admin_dashboard\TransferVehiclesController;

// Manage Office
use App\Http\Controllers\frontend\admin_dashboard\ManageOfficeController_Admin;
use App\Http\Controllers\frontend\admin_dashboard\CreditLimitController;
// Lead
use App\Http\Controllers\frontend\admin_dashboard\LeadController_Admin;

// Atol
use App\Http\Controllers\frontend\admin_dashboard\AtolController_Admin;
use App\Http\Controllers\frontend\admin_dashboard\FlightController;
use App\Http\Controllers\ActivityController_Admin;
use App\Http\Controllers\Accounts\CashAccControllerApi;
use App\Http\Controllers\HotelMangers\HotelBookingControllerNewApi;
use App\Models\country;

use App\Mail\MyTestEmail;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/mail_Send_SMTP_Test', function() {
    $name = "Usama Ali";
    Mail::to('ua758323@gmail.com')->send(new MyTestEmail($name));
    return 'OK';
});

Route::get("removecache",function (){
\Illuminate\Support\Facades\Artisan::call('route:cache');
\Illuminate\Support\Facades\Artisan::call('route:clear');
\Illuminate\Support\Facades\Artisan::call('config:cache');
\Illuminate\Support\Facades\Artisan::call('config:clear');
\Illuminate\Support\Facades\Artisan::call('optimize');
});



Route::get('super_admin/flight/booking/list',[FlightController::class,'flight_booking']);
Route::get('super_admin/flight/payment/history',[FlightController::class,'flight_payment_history']);
Route::post('super_admin/flight/payment/approved',[FlightController::class,'flight_payment_approved']);
Route::post('super_admin/flight/payment/declined',[FlightController::class,'flight_payment_declined']);


Route::get('super_admin/third_party_hotels_providers_list',[HotelBookingControllerNewApi::class,'third_party_hotels_providers_list']); 
Route::post('update_customer_hotel_providers',[HotelBookingControllerNewApi::class,'update_customer_hotel_providers']); 


 Route::get('super_admin/credit_limit',[CreditLimitController::class,'credit_limit']);
 Route::post('super_admin/credit_limit/submit',[CreditLimitController::class,'credit_limit_submit']);
 Route::post('super_admin/update_credit_limit/submit',[CreditLimitController::class,'update']);
 Route::get('super_admin/costomer_credit_ledger/{id}',[CreditLimitController::class,'costomer_credit_ledger']);
 Route::get('super_admin/costomer_credit_history',[CreditLimitController::class,'costomer_credit_history']);

 Route::post('super_admin/credit/limit/approved',[CreditLimitController::class,'credit_limit_approved']);
 Route::post('super_admin/credit/limit/declined',[CreditLimitController::class,'credit_limit_declined']);

    Route::get('super_admin/view_markup', 'frontend\user_dashboard\UserController@view_markup');
    Route::get('super_admin/markup/add', 'frontend\user_dashboard\UserController@add_markup');
    Route::post('super_admin/admin_markup', 'frontend\user_dashboard\UserController@admin_markup');
    Route::get('super_admin/markup/edit/{id}', 'frontend\user_dashboard\UserController@edit_markup');
    Route::post('super_admin/markup/update/{id}', 'frontend\user_dashboard\UserController@update_markup');
    Route::get('super_admin/markup/delete/{id}', 'frontend\user_dashboard\UserController@delete_markup');
    
    Route::get('super_admin/hotels/payment/history','frontend\admin_dashboard\AdminHotelPaymentController@hotel_payment_history');
    Route::post('super_admin/hotels/payment/approved','frontend\admin_dashboard\AdminHotelPaymentController@hotel_payment_approved');
    Route::post('super_admin/hotels/payment/declined','frontend\admin_dashboard\AdminHotelPaymentController@hotel_payment_declined');
    Route::get('super_admin/hotels/list','frontend\admin_dashboard\AdminHotelPaymentController@hotel_list');
    Route::get('super_admin/client/list','frontend\admin_dashboard\AdminHotelPaymentController@client_list');
    
    Route::get('super_admin/customer/hotels/ledger/{id}','frontend\admin_dashboard\AdminHotelPaymentController@customerHotelLedger');
    Route::get('super_admin/customer/hotels/payment/history/{id}','frontend\admin_dashboard\AdminHotelPaymentController@customerHotelPaymentHistory');
    
    Route::get('super_admin/providers/suppliers','frontend\admin_dashboard\AdminHotelPaymentController@providers_suppliers');
    Route::post('super_admin/admin/provider/payment/submit','frontend\admin_dashboard\AdminHotelPaymentController@providers_payments_submit');
    
    
    Route::get('Error_Authenticate',[ActivityController::class,'Error_Authenticate'])->name('Error_Authenticate');
    
    // metadata routes
    Route::get('travellanda_get_cities',[Travellanda_MetaDataController::class,'get_cities']);
    Route::get('travellanda_get_hotels',[Travellanda_MetaDataController::class,'GetHotels']);
    Route::get('travellanda_get_hotels_details',[Travellanda_MetaDataController::class,'GetHotelDetails']);
    
    
    Route::get('hotelbeds_get_countries',[Hotelbeds_MetaDataController::class,'get_countries']);
    Route::get('hotelbeds_get_hotels',[Hotelbeds_MetaDataController::class,'get_hotel']);
    
    Route::get('tboholidays_get_countries',[TBOHolidays_HotelAPI_Controller::class,'tboholidays_get_countries']);
    Route::get('tboholidays_get_cities',[TBOHolidays_HotelAPI_Controller::class,'tboholidays_get_cities']);

    Route::get('tbo_hotel_details','frontend\TBO_MataDataController@tbo_hotel_details');
    // metadata routes
    
    Route::group(['middleware'=>'admin_middleware'],function(){
        Route::get('/','frontend\user_dashboard\UserController@index');
    });
    
    // Invoice 
    Route::get('create_Invoices',[ManageOfficeController_Admin::class,'create_Invoices']);
    Route::get('view_Invoices',[ManageOfficeController_Admin::class,'view_Invoices'])->name('view_Invoices');
    Route::post('add_Invoices',[ManageOfficeController_Admin::class,'add_Invoices']);
    Route::get('edit_Invoices/{id}',[ManageOfficeController_Admin::class,'edit_Invoices'])->name('edit_Invoices');
    Route::post('update_Invoices/{id}',[ManageOfficeController_Admin::class,'update_Invoices']);
    Route::get('confirm_invoice_Agent/{id}',[ManageOfficeController_Admin::class,'confirm_invoice_Agent'])->name('confirm_invoice_Agent');
    // Invoic
    
    // Agent & Customer
    Route::get('create_Agents_Admin',[ManageOfficeController_Admin::class,'create_Agents_Admin'])->name('create_Agents_Admin');
    Route::get('create_customer_Admin',[ManageOfficeController_Admin::class,'create_customer_Admin'])->name('create_customer_Admin');
    
    // Supplier
    Route::get('view_hotel_suppliers_Admin','frontend\admin_dashboard\HotelSupplierController_Admin@view_hotel_suppliers_Admin');
    Route::get('super_admin/viewsupplier_Admin','SupplierController_Admin@viewsupplier_Admin')->name('viewsupplier_Admin');
    Route::get('view_transfer_suppliers_Admin','frontend\admin_dashboard\TransferSupplierController_Admin@view_transfer_suppliers_Admin');
    Route::get('viewvisasupplier_Admin','frontend\admin_dashboard\visaSupplierController_Admin@viewvisasupplier_Admin');
    
    // Accounts
    Route::get('booking_financial_stats_Admin',[ManageOfficeController_Admin::class,'booking_financial_stats_Admin'])->name('booking_financial_stats_Admin');
    
    // Activity
    Route::get('view_activities_new_Admin',[ActivityController_Admin::class,'view_activities_new_Admin'])->name('view_activities_new_Admin');
    Route::get('view_ternary_bookings_Activity_Admin',[ActivityController_Admin::class,'view_ternary_bookings_Activity_Admin'])->name('view_ternary_bookings_Activity_Admin');
    
    // Lead
    Route::get('view_Lead_Admin',[LeadController_Admin::class,'view_Lead_Admin'])->name('view_Lead_Admin');
    Route::get('view_manage_Lead_Quotation_SingleAll_Admin/{id}',[LeadController_Admin::class,'view_manage_Lead_Quotation_SingleAll_Admin'])->name('view_manage_Lead_Quotation_SingleAll_Admin');
    Route::get('view_manage_Lead_Quotation_Single_Admin/{id}',[LeadController_Admin::class,'view_manage_Lead_Quotation_Single_Admin'])->name('view_manage_Lead_Quotation_Single_Admin');
    Route::get('invoice_Quotations_Admin/{id}',[LeadController_Admin::class,'invoice_Quotations_Admin'])->name('invoice_Quotations_Admin');
    Route::get('view_manage_Lead_Quotation_Admin',[LeadController_Admin::class,'view_manage_Lead_Quotation_Admin'])->name('view_manage_Lead_Quotation_Admin');
    Route::get('view_Lead_Process_Admin',[LeadController_Admin::class,'view_Lead_Process_Admin'])->name('view_Lead_Process_Admin');
    
    Route::get('view_atol_certificate_Admin/{id}', [LeadController_Admin::class,'view_atol_certificate_Admin'])->name('view_atol_certificate_Admin');
    Route::get('view_idemnity_form_Admin/{id}', [LeadController_Admin::class,'view_idemnity_form_Admin'])->name('view_idemnity_form_Admin');
    
    // Atol
        // Report
    Route::get('atol_Report_Admin',[AtolController_Admin::class,'atol_Report_Admin'])->name('atol_Report_Admin');
    Route::get('atol_Report_Weekly_Admin',[AtolController_Admin::class,'atol_Report_Weekly_Admin'])->name('atol_Report_Weekly_Admin');
    Route::get('atol_Report_Monthly_Admin',[AtolController_Admin::class,'atol_Report_Monthly_Admin'])->name('atol_Report_Monthly_Admin');
    Route::get('atol_Report_Quarter_Admin',[AtolController_Admin::class,'atol_Report_Quarter_Admin'])->name('atol_Report_Quarter_Admin');
    Route::get('atol_Report_Half_Yearly_Admin',[AtolController_Admin::class,'atol_Report_Half_Yearly_Admin'])->name('atol_Report_Half_Yearly_Admin');
    Route::get('atol_Report_Yearly_Admin',[AtolController_Admin::class,'atol_Report_Yearly_Admin'])->name('atol_Report_Yearly_Admin');
        // Certificate
    Route::get('atol_Certificate_Admin',[AtolController_Admin::class,'atol_Certificate_Admin'])->name('atol_Certificate_Admin');

    // Hotel Route
    Route::get('/hotel_manger/add_hotel',[HotelController::class,'showAddHotelFrom']);
    Route::post('/hotel_manger/add_hotel_sub',[HotelController::class,'addHotel']);
    Route::post('/country_cites', [CountryController::class,'countryCites']);
    Route::get('/city', function () {
        $city = city::find(1);
        $country = $city->Country;
        dd($country);
        echo "This is Country ";
    });

    // Rooms Route
    Route::get('/hotel_manger/add_room',[RoomController::class,'showAddRoomFrom']);
    Route::post('/hotel_manger/add_room_sub',[RoomController::class,'addRoom']);
    Route::get('/hotel_manger/rooms_list',[RoomController::class,'index']);
    Route::get('/hotel_manger/view_room/{id}',[RoomController::class,'updateShowForm']);
    Route::post('/hotel_manger/update_room/{id}',[RoomController::class,'update_room']);
    Route::get('/hotel_manger/hotel_list',[HotelController::class,'index']);
    Route::get('/hotel_manger/hotel_list_Admin',[HotelController::class,'hotel_list_Admin'])->name('hotel_list_Admin');
    Route::get('/hotel_manger/rooms_list_Admin',[RoomController::class,'rooms_list_Admin'])->name('rooms_list_Admin');
    Route::get('view_rooms_Admin/{id}', [RoomController::class,'view_rooms_Admin'])->name('view_rooms_Admin');

    // Hotel Room Ajax Route
    Route::post('hotel_Room',[HotelController::class,'hotel_Room']);
    
    //Quotations
    Route::get('manage_Quotation',[ManageQuotationsController::class,'manage_Quotation']);
    Route::post('add_Manage_Quotation',[ManageQuotationsController::class,'add_Manage_Quotation']);
    Route::get('view_Quotations',[ManageQuotationsController::class,'view_Quotations'])->name('view_Quotations');
    Route::get('view_QuotationsID/{id}',[ManageQuotationsController::class,'view_QuotationsID']);
    Route::get('edit_Quotations/{id}',[ManageQuotationsController::class,'edit_Quotations'])->name('edit_Quotations');
    Route::post('update_Manage_Quotation/{id}',[ManageQuotationsController::class,'update_Manage_Quotation'])->name('update_Manage_Quotation');
    //Makkah
    Route::post('hotel_Makkah_Room',[ManageQuotationsController::class,'hotel_Makkah_Room']);
    Route::get('makkah_Room/{id}',[ManageQuotationsController::class,'makkah_Room']);
    //Madinah
    Route::post('hotel_Madinah_Room',[ManageQuotationsController::class,'hotel_Madinah_Room']);
    Route::get('madinah_Room/{id}',[ManageQuotationsController::class,'madinah_Room']);

    //Bookings
    Route::get('view_Bookings',[ManageQuotationsController::class,'view_Bookings'])->name('view_Bookings');
    Route::get('add_Bookings/{id}',[ManageQuotationsController::class,'add_Bookings'])->name('add_Bookings');
    
    Route::get('invoice_package/{id}/{booking_id}/{T_ID}', [bookingController::class,'invoice_package']);
    Route::get('/invoice/{id}', [bookingController::class,'invoice']);
    
    Route::get('invoice_package_admin/{id}/{booking_id}/{T_ID}', [bookingController::class,'invoice_package_admin']);
    Route::get('/invoice_admin/{id}', [bookingController::class,'invoice_admin']);
    
    // Accounts Route
    
    Route::get('expenses_IncomeAll',[AccountDetailsController::class,'expenses_IncomeAll'])->name('expenses_IncomeAll');;
    Route::get('expenses_Income_client_wise_data/{id}',[AccountDetailsController::class,'expenses_Income_client_wise_data'])->name('expenses_Income_client_wise_data');
    
    Route::get('income_statement',[AccountDetailsController::class,'income_statement']);
    Route::get('expenses_Income',[AccountDetailsController::class,'expenses_Income'])->name('expenses_Income');
    Route::get('out_Standings',[AccountDetailsController::class,'out_Standings']);
    Route::get('recieved_Payments',[AccountDetailsController::class,'recieved_Payments']);
    Route::get('stats_Tours',[AccountDetailsController::class,'stats_Tours']);
    Route::get('cancelled_Tours',[AccountDetailsController::class,'cancelled_Tours']);
    
    Route::get('view_total_Amount/{id}',[AccountDetailsController::class,'view_total_Amount']);
    Route::get('view_recieve_Amount/{id}',[AccountDetailsController::class,'view_recieve_Amount']);
    Route::get('view_Outstandings/{id}',[AccountDetailsController::class,'view_Outstandings']);
    
    Route::get('view_Details_Accomodation/{id}',[AccountDetailsController::class,'view_Details_Accomodation']);
    
    Route::get('hotel_detail_ID/{id}',[AccountDetailsController::class,'hotel_detail_ID']);
    Route::get('flight_detail_ID/{id}',[AccountDetailsController::class,'flight_detail_ID']);
    Route::get('transportation_detail_ID/{id}',[AccountDetailsController::class,'transportation_detail_ID']);
    Route::get('visa_detail_ID/{id}',[AccountDetailsController::class,'visa_detail_ID']);
    
    // Visa
    Route::get('visa_Pay/{id}','frontend\admin_dashboard\AccountDetailsController@visa_Pay');
    Route::post('sumbit_Visa_Pay','frontend\admin_dashboard\AccountDetailsController@sumbit_Visa_Pay')->name('sumbit_Visa_Pay');
    Route::get('view_visa_total_Amount/{id}',[AccountDetailsController::class,'view_visa_total_Amount']);
    
    // Transportation
    Route::get('transportation_Pay/{id}','frontend\admin_dashboard\AccountDetailsController@transportation_Pay');
    Route::post('sumbit_Transportation_Pay','frontend\admin_dashboard\AccountDetailsController@sumbit_Transportation_Pay')->name('sumbit_Transportation_Pay');
    Route::get('view_transportation_total_Amount/{id}',[AccountDetailsController::class,'view_transportation_total_Amount']);
    
    // Flight
    Route::post('sumbit_Flight_Pay','frontend\admin_dashboard\AccountDetailsController@sumbit_Flight_Pay')->name('sumbit_Flight_Pay');
    
    // Accomodation
    Route::get('acc_Pay/{selected_city}/{t_Id}','frontend\admin_dashboard\AccountDetailsController@acc_Pay');
    Route::post('sumbit_Accomodation_Pay','frontend\admin_dashboard\AccountDetailsController@sumbit_Accomodation_Pay')->name('sumbit_Accomodation_Pay');
    
    // Vehicles
    Route::get('add_Vehicles',[TransferVehiclesController::class,'add_Vehicles']);
    Route::get('view_Vehicles',[TransferVehiclesController::class,'view_Vehicles'])->name('view_Vehicles');
    Route::post('add_new_vehicle',[TransferVehiclesController::class,'add_new_vehicle']);
    Route::get('edit_vehicle_details/{id}',[TransferVehiclesController::class,'edit_vehicle_details']);
    Route::post('update_vehicle/{id}',[TransferVehiclesController::class,'update_vehicle']);
    
    // Destination
    Route::get('view_Destination',[TransferVehiclesController::class,'view_Destination'])->name('view_Destination');
    Route::post('add_new_destination',[TransferVehiclesController::class,'add_new_destination']);
    Route::get('edit_destination_details/{id}',[TransferVehiclesController::class,'edit_destination_details']);
    Route::post('update_destination/{id}',[TransferVehiclesController::class,'update_destination']);
    

Route::group(['prefix'=>'super_admin','middleware'=>'admin_middleware'],function(){
    
    // New Routes
    Route::get('view_tour_Admin','frontend\admin_dashboard\UmrahPackageController_Admin@view_tour_Admin');
    Route::get('view_ternary_bookings_Admin','frontend\admin_dashboard\UmrahPackageController_Admin@view_ternary_bookings_Admin')->name('view_ternary_bookings_Admin');
    Route::get('view_confirmed_bookings_Admin','frontend\admin_dashboard\UmrahPackageController_Admin@view_confirmed_bookings_Admin')->name('view_confirmed_bookings_Admin');
    Route::get('get_customerS_details','frontend\user_dashboard\UserController@get_customerS_details');
    
    Route::get('hotel_markup','frontend\admin_dashboard\UmrahPackageController@hotel_markup');
    
        Route::get('recevied_booking','frontend\admin_dashboard\UmrahPackageController@recevied_booking');
        Route::get('partial_paid_booking/{id}/{provider}','frontend\admin_dashboard\UmrahPackageController@partial_paid_booking');
        Route::get('fully_partial_paid_booking/{id}/{provider}','frontend\admin_dashboard\UmrahPackageController@fully_partial_paid_booking');
         Route::post('payment_detact/{id}','frontend\admin_dashboard\UmrahPackageController@payment_detact');
        
        
        
        Route::get('confirmed_booking','frontend\admin_dashboard\UmrahPackageController@confirmed_booking');
        Route::get('cancel_booking','frontend\admin_dashboard\UmrahPackageController@cancel_booking');
    

    //Room Ajax Route
    Route::get('roomID/{id}',[HotelController::class,'roomID']);

    Route::get('/','frontend\user_dashboard\UserController@index');
    Route::get('create_umrah_packages','frontend\admin_dashboard\UmrahPackageController@create');
    Route::post('submit_umrah_packages','frontend\admin_dashboard\UmrahPackageController@store');
    Route::get('view_umrah_packages','frontend\admin_dashboard\UmrahPackageController@view');

    Route::get('delete_umrah_packages/{id}','frontend\admin_dashboard\UmrahPackageController@delete_umrah_packages');
    Route::get('edit_umrah_packages/{id}','frontend\admin_dashboard\UmrahPackageController@edit_umrah_packages');
    Route::post('submit_edit_umrah_packages/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_umrah_packages');

    Route::get('enable_umrah_packages/{id}','frontend\admin_dashboard\UmrahPackageController@enable_umrah_packages');
    Route::get('disable_umrah_package/{id}','frontend\admin_dashboard\UmrahPackageController@disable_umrah_packages');

    Route::get('create_excursion','frontend\admin_dashboard\UmrahPackageController@create_excursion');
    Route::post('submit_tour','frontend\admin_dashboard\UmrahPackageController@submit_tour');
    Route::get('view_tour','frontend\admin_dashboard\UmrahPackageController@view_tour');
    Route::get('delete_tour/{id}','frontend\admin_dashboard\UmrahPackageController@delete_tour');
    Route::get('edit_tour/{id}','frontend\admin_dashboard\UmrahPackageController@edit_tour');
    Route::post('submit_edit_tour/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_tour');

    Route::get('enable_tour/{id}','frontend\admin_dashboard\UmrahPackageController@enable_tour');
    Route::get('disable_tour/{id}','frontend\admin_dashboard\UmrahPackageController@disable_tour');

    Route::get('add_categories','frontend\admin_dashboard\UmrahPackageController@add_categories');
    Route::post('submit_categories','frontend\admin_dashboard\UmrahPackageController@submit_categories');
    Route::get('view_categories','frontend\admin_dashboard\UmrahPackageController@view_categories');

    Route::get('delete_categories/{id}','frontend\admin_dashboard\UmrahPackageController@delete_categories');
    Route::get('edit_categories/{id}','frontend\admin_dashboard\UmrahPackageController@edit_categories');
    Route::post('submit_edit_categories/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_categories');

    Route::get('add_attributes','frontend\admin_dashboard\UmrahPackageController@add_attributes');
    Route::post('submit_attributes','frontend\admin_dashboard\UmrahPackageController@submit_attributes');
    Route::get('view_attributes','frontend\admin_dashboard\UmrahPackageController@view_attributes');

    Route::get('delete_attributes/{id}','frontend\admin_dashboard\UmrahPackageController@delete_attributes');
    Route::get('edit_attributes/{id}','frontend\admin_dashboard\UmrahPackageController@edit_attributes');
    Route::post('submit_edit_attributes/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_attributes');

    // Usama Routes
    Route::get('customer_subcription','frontend\admin_dashboard\CustomerSubscription@showSubscriptionForm');
    Route::post('submit_subcription','frontend\admin_dashboard\CustomerSubscription@create');
    Route::post('save_meta_tags','frontend\admin_dashboard\CustomerSubscription@save_meta_tags');
    
    Route::get('view_customer_subcription','frontend\admin_dashboard\CustomerSubscription@view_customer_subcription');
    Route::get('subcirbed_customer_ledger/{id}','frontend\admin_dashboard\CustomerSubscription@subcirbed_customer_ledger');
 
    // Tour Booking
    // Route::get('view_ternary_bookings','frontend\admin_dashboard\UmrahPackageController@view_ternary_bookings');
    // Route::get('view_confirmed_bookings','frontend\admin_dashboard\UmrahPackageController@view_confirmed_bookings')->name('view_confirmed_bookings');
    // Route::get('view_booking_payment/{id}','frontend\admin_dashboard\UmrahPackageController@view_booking_payment')->name('view_booking_payment');
    // Route::get('view_booking_detail/{id}','frontend\admin_dashboard\UmrahPackageController@view_booking_detail')->name('view_booking_detail');
    // Route::get('view_booking_customer_details/{id}','frontend\admin_dashboard\UmrahPackageController@view_booking_customer_details')->name('view_booking_customer_details');
    // Route::get('confirmed_tour_booking/{id}','frontend\admin_dashboard\UmrahPackageController@confirmed_tour_booking')->name('confirmed_tour_booking');
    
    // Tour Booking
    Route::get('client_wise_data/{id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@client_wise_data')->name('client_wise_data');
    Route::get('view_ternary_bookingsAll','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_ternary_bookingsAll')->name('view_ternary_bookingsAll');
    
    Route::get('view_ternary_bookings','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_ternary_bookings')->name('view_ternary_bookings');
    Route::get('view_confirmed_bookings','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_confirmed_bookings')->name('view_confirmed_bookings');
    
    Route::get('view_booking_payment/{id}/{P_Id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_booking_payment')->name('view_booking_payment');
    Route::get('view_booking_detail/{id}/{P_Id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_booking_detail')->name('view_booking_detail');
    Route::get('view_booking_customer_details/{id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_booking_customer_details')->name('view_booking_customer_details');
    Route::get('confirmed_tour_booking/{id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@confirmed_tour_booking')->name('confirmed_tour_booking');
    
    Route::post('view_booking_payment_recieve/{tourId}','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_booking_payment_recieve');
    Route::get('view_ternary_bookings_tourId/{P_Id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_ternary_bookings_tourId');
    // End Tour Booking
    
    // Activity Booking
    Route::get('client_wise_data_Activity/{id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@client_wise_data_Activity')->name('client_wise_data_Activity');
    Route::get('view_ternary_bookings_ActivityAll','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_ternary_bookings_ActivityAll')->name('view_ternary_bookings_ActivityAll');
    
    Route::get('view_ternary_bookings_Activity','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_ternary_bookings_Activity')->name('view_ternary_bookings_Activity');
    Route::get('view_confirmed_bookings_Activity','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_confirmed_bookings_Activity')->name('view_confirmed_bookings_Activity');
    
    Route::get('view_booking_payment_Activity/{id}/{P_Id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_booking_payment_Activity')->name('view_booking_payment_Activity');
    Route::get('view_booking_detail_Activity/{id}/{P_Id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_booking_detail_Activity')->name('view_booking_detail_Activity');
    Route::get('view_booking_customer_details_Activity/{id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_booking_customer_details_Activity')->name('view_booking_customer_details_Activity');
    Route::get('confirmed_tour_booking_Activity/{id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@confirmed_tour_booking_Activity')->name('confirmed_tour_booking_Activity');
    
    Route::post('view_booking_payment_recieve_Activity/{tourId}','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_booking_payment_recieve_Activity');
    Route::get('view_ternary_bookings_tourId_Activity/{P_Id}','frontend\admin_dashboard\UmrahPackageControllerAdmin@view_ternary_bookings_tourId_Activity');
    // END Activity Booking






Route::get('ticket_view','frontend\user_dashboard\UserController@view_ticket');
Route::post('ticket_view/submit/{id}','frontend\user_dashboard\UserController@admin_ticket');

Route::get('conversation/{name}/{id}','frontend\user_dashboard\UserController@view_conversation');
Route::post('conversation/submit/{id}','frontend\user_dashboard\UserController@conversation_submit');
Route::post('updated_status_ticket/{id}','frontend\user_dashboard\UserController@updated_status_ticket');
Route::get('view_all_conversation','frontend\user_dashboard\UserController@view_all_conversation');

Route::get('downloadfile/{uuid}','frontend\user_dashboard\UserController@downloadFile');




//employees routes
Route::get('/employees',[App\Http\Controllers\frontend\EmployeeController::class,'employees']);
Route::get('/employees/add',[App\Http\Controllers\frontend\EmployeeController::class,'create']);
Route::post('/employees/submit',[App\Http\Controllers\frontend\EmployeeController::class,'store']);
Route::get('/employees_edit/{id}',[App\Http\Controllers\frontend\EmployeeController::class,'edit']);
Route::post('/employees_update/{id}',[App\Http\Controllers\frontend\EmployeeController::class,'update']);
Route::get('/employees_delete/{id}',[App\Http\Controllers\frontend\EmployeeController::class,'delete']);
Route::get('/view_location/{id}',[App\Http\Controllers\frontend\EmployeeController::class,'view_location']);
Route::get('/view_task_location/{id}',[App\Http\Controllers\frontend\EmployeeController::class,'view_task_location']);
Route::get('/employee_roles',[App\Http\Controllers\frontend\EmployeeController::class,'employee_roles']);
Route::post('/add_roles',[App\Http\Controllers\frontend\EmployeeController::class,'add_roles']);
Route::post('/edit_roles',[App\Http\Controllers\frontend\EmployeeController::class,'edit_roles']);
Route::get('/del_roles/{id}',[App\Http\Controllers\frontend\EmployeeController::class,'del_roles']);
//employees routes


//attendence routes
Route::get('/attendance',[App\Http\Controllers\frontend\AttendenceController::class,'attendence']);
Route::get('/attendance/add',[App\Http\Controllers\frontend\AttendenceController::class,'create']);
Route::post('/attendance/submit',[App\Http\Controllers\frontend\AttendenceController::class,'store']);
Route::get('/attendance_edit/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'edit']);
Route::post('/attendance_update/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'update']);
Route::get('/attendance_delete/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'delete']);
Route::post('/appoint/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'view_task'])->name('task.appoint');
Route::get('/assign_mandob/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'assign_mandob']);

Route::get('employees_task',[App\Http\Controllers\frontend\AttendenceController::class,'employees_task']);
Route::post('submit_task',[App\Http\Controllers\frontend\AttendenceController::class,'submit_task']);
Route::get('employees_task_edit/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'employees_task_edit']);
Route::post('edit_task_submit/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'edit_task_submit']);
Route::get('del_tsk/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'delete_task']);





Route::get('/leave_employees',[App\Http\Controllers\LeaveController::class,'index']);
Route::get('/leave_status/{status}/{id}',[App\Http\Controllers\LeaveController::class,'status']);
Route::post('/leaves/submit',[App\Http\Controllers\LeaveController::class,'store']);



Route::get('/approve/{id}', [App\Http\Controllers\LeaveController::class,'approve'])->name('admin.approve');
Route::get('/decline/{id}', [App\Http\Controllers\LeaveController::class,'decline'])->name('admin.decline');

//settings route 
Route::get('/settings','frontend\user_dashboard\UserController@settings');
Route::post('change-password', 'frontend\LoginController@change_password');

Route::get('send_email_to_agents','frontend\user_dashboard\UserController@send_email_to_agents');
Route::post('/send_mail','frontend\user_dashboard\UserController@send_mail');


Route::get('/manage_user_roles','frontend\user_dashboard\UserController@manage_user_roles');
Route::post('/add_user_permission','frontend\user_dashboard\UserController@add_user_permission');
Route::post('/edit_user_permission','frontend\user_dashboard\UserController@edit_user_permission');
Route::get('mange_user_role_delete/{id}','frontend\user_dashboard\UserController@mange_user_role_delete');
Route::get('activate_user/{id}','frontend\user_dashboard\UserController@super_admin_activate_user');
Route::get('inactivate_user/{id}','frontend\user_dashboard\UserController@super_admin_inactivate_user');



Route::get('create_offers','frontend\user_dashboard\UserController@create_offers');
Route::post('submit_offers','frontend\user_dashboard\UserController@submit_offers');
Route::get('edit_offers/{id}','frontend\user_dashboard\UserController@edit_offers');
Route::post('submit_edit_offers/{id}','frontend\user_dashboard\UserController@submit_edit_offers');
Route::get('delete_offers/{id}','frontend\user_dashboard\UserController@delete_offers');
Route::get('view_offers','frontend\user_dashboard\UserController@view_offers');



Route::get('book_package','frontend\admin_dashboard\BookingPackageController@book_package');
Route::get('get_booking_package/{id}','frontend\admin_dashboard\BookingPackageController@get_booking_package');
Route::get('get_booking_package_tour/{title}','frontend\admin_dashboard\BookingPackageController@get_booking_package_tour');


Route::post('submit_book_package','frontend\admin_dashboard\BookingPackageController@submit_book_package');

//for hotel booking //from hotel.synchronusdigital.com
Route::get('process/{id}/{slug}',[HotelController::class,'process']);
Route::post('confrim_booking_hotel',[HotelController::class,'confrim_booking_hotel']);
Route::get('voucher/{id}/{slug}',[HotelController::class,'voucher']);
///////////
/////////
///////
/////
});
Route::get('get_cites_book/{country_id}', 'frontend\admin_dashboard\BookingPackageController@get_cites_book');
Route::post('/country_cites', [CountryController::class,'countryCites']);


// user dashboard routes ends

//login  and register routes
Route::get('signup1', 'frontend\Register1Controller@signup');
Route::post('register1', 'frontend\Register1Controller@register1');
Route::get('login_admin', 'frontend\LoginController@login');
Route::post('login_submit', 'frontend\LoginController@login_submit');

Route::post('change-password', 'frontend\LoginController@change_password');
Route::get('logout', 'frontend\LoginController@logout');
Auth::routes();

 Route::get('/home', 'HomeController@index')->name('home');
 
     Route::get('/super_admin/custom_hotel_provider',[CashAccControllerApi::class,'custom_hotel_provider']);
     Route::get('/super_admin/custom_hotel_provider_ledger/{id}',[CashAccControllerApi::class,'custom_hotel_provider_ledger']);
     Route::get('/super_admin/provider_payment_request/',[CashAccControllerApi::class,'provider_payment_request']);
     Route::post('/super_admin/provider_payment_request_sub/',[CashAccControllerApi::class,'provider_payment_request_sub']);







