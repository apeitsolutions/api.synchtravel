<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SupplierControllerApi;
use App\Http\Controllers\frontend\admin_dashboard\HajPackageController;
use App\Http\Controllers\MutamerControllerApi;
use App\Http\Controllers\HotelMangers\HotelController;
use App\Http\Controllers\HotelMangers\HotelbookingController;
use App\Http\Controllers\HotelMangers\HotelBookingControllerNewApi;

use App\Http\Controllers\HotelMangers\HotelBookingReactController;
use App\Http\Controllers\HotelMangers\HotelBookingReactController_Live;

use App\Http\Controllers\HotelMangers\TestHotelBookingControllerApi;

use App\Http\Controllers\Transfer_3rdPartyBooking_Controller;

// Groups
use App\Http\Controllers\frontend\admin_dashboard\ManageUmrahVisasController;

// Lead
use App\Http\Controllers\frontend\admin_dashboard\LeadController;
// Atol
use App\Http\Controllers\frontend\admin_dashboard\AtolController;
// Season
use App\Http\Controllers\frontend\admin_dashboard\SeasonsController;
// Hotel & Rooms
use App\Http\Controllers\HotelMangersApi\HotelControllerApi;
use App\Http\Controllers\HotelMangersApi\RoomControllerApi;

use App\Http\Controllers\HotelMangersApi\MealTypeController;

use App\Models\city;
use App\Models\country;
// Manage Office
use App\Http\Controllers\frontend\admin_dashboard\ManageOfficeController;
use App\Http\Controllers\frontend\admin_dashboard\ManageOfficeNewController;
use App\Http\Controllers\frontend\admin_dashboard\InvoiceRequestController;
// Quotationssubmit_tours_api
use App\Http\Controllers\frontend\admin_dashboard\ManageQuotationsController;
use App\Http\Controllers\frontend\admin_dashboard\ManageQuotationsControllerApi;
use App\Http\Controllers\frontend\admin_dashboard\UmrahPackageControllerApi;
// Account Details
use App\Http\Controllers\frontend\admin_dashboard\AccountDetailsApiController;
// Vehicle
use App\Http\Controllers\frontend\admin_dashboard\TransferVehiclesApiController;
// Transfer Booking
use App\Http\Controllers\frontend\admin_dashboard\TransferzController;

// Transfer Booking
use App\Http\Controllers\frontend\admin_dashboard\ManageAgentEnquiryController;

use App\Http\Controllers\frontend\admin_dashboard\emailEnquiry\emailEnquiryController;

use App\Http\Controllers\Accounts\CashAccControllerApi;
use App\Http\Controllers\TransfersController;
use App\Http\Controllers\TransfersReactController;
use App\Http\Controllers\TransfersVehicleDestinationsController;

use App\Http\Controllers\TBOHotel_3rdPartyBooking_Controller;

use App\Http\Controllers\Travelenda\Travelenda_Controller;

use App\Http\Controllers\Accounts\ExpenseController;

use App\Http\Controllers\FlightKeysController;
use App\Http\Controllers\frontend\admin_dashboard\CreditLimitController;
use App\Http\Controllers\frontend\admin_dashboard\CreditLimitApiController;

use App\Http\Controllers\frontend\admin_dashboard\AllowSupplierController;

use App\Http\Controllers\PromotionController;

use App\Http\Controllers\frontend\admin_dashboard\AlhijazHotelsRooms\AlhijazHotelsRoomsController;
use App\Http\Controllers\frontend\admin_dashboard\StopSaleHotelsRooms\StopSaleHotelsRoomsController;

use App\Http\Controllers\frontend\admin_dashboard\AllProviderTransfer\AllProviderTransfersController;

use App\Http\Controllers\CheckLoginController;

use App\Http\Controllers\Stuba\Stuba_Controller;

use App\Http\Controllers\SunHotel\SunHotel_Controller;

use App\Http\Controllers\Mail3rdPartyController;

use App\Http\Controllers\frontend\admin_dashboard\BlogPost\BlogPostController;

use App\Http\Controllers\frontend\admin_dashboard\Company\CompanyController;

use App\Http\Controllers\frontend\admin_dashboard\RequestCallback\RequestCallbackController;

use App\Http\Controllers\frontend\admin_dashboard\InvoiceVisaGroupController;
use App\Http\Controllers\frontend\admin_dashboard\QuoteController;

Route::post('addQuote',[QuoteController::class,'addQuote']);

Route::post('notificationShowed','frontend\admin_dashboard\UmrahPackageController@notificationShowed');

Route::post('view_invoices_visa_groups',[InvoiceVisaGroupController::class,'view_invoices_visa_groups']);
Route::post('view_invoices_visa_groups_Ajax',[InvoiceVisaGroupController::class,'view_invoices_visa_groups_Ajax']);

Route::post('addRequestCallback',[RequestCallbackController::class,'addRequestCallback']);

Route::post('createBlogPost',[BlogPostController::class,'createBlogPost']);
Route::post('addBlogPost',[BlogPostController::class,'addBlogPost']);
Route::post('getAllBlogPosts', [BlogPostController::class, 'getAllBlogPosts']);
Route::post('getSingleBlogPosts', [BlogPostController::class, 'getSingleBlogPosts']);
Route::post('updateBlogPost', [BlogPostController::class, 'updateBlogPost']);
Route::post('deleteblogPost', [BlogPostController::class, 'deleteblogPost']);

Route::post('loginCompany', [CompanyController::class, 'loginCompany']);
Route::post('dashboardCompany', [CompanyController::class, 'dashboardCompany']);
Route::post('viewCompany', [CompanyController::class, 'viewCompany']);
Route::post('addCompany', [CompanyController::class, 'addCompany']);

// Seasons
Route::post('create_Season',[SeasonsController::class,'create_Season']);
Route::post('add_Season',[SeasonsController::class,'add_Season']);
Route::post('edit_Season',[SeasonsController::class,'edit_Season']);
Route::post('update_Season',[SeasonsController::class,'update_Season']);
// Seasons

Route::post('check_Login',[CheckLoginController::class,'check_Login']);

Route::post('mail_Check_Alsubaee_Contact_Us',[Mail3rdPartyController::class,'mail_Check_Alsubaee_Contact_Us']);
Route::post('mail_Check_HR_Contact_Us',[Mail3rdPartyController::class,'mail_Check_HR_Contact_Us']);

Route::post('add_SubscriptionEmail',[Mail3rdPartyController::class,'add_SubscriptionEmail']);
Route::post('get_SubscriptionEmail',[Mail3rdPartyController::class,'get_SubscriptionEmail']);

// HH
Route::post('all_Hotels_For_Stop_Sale',[StopSaleHotelsRoomsController::class,'all_Hotels_For_Stop_Sale']);
Route::post('hotel_Stop_All',[StopSaleHotelsRoomsController::class,'hotel_Stop_All']);
Route::post('hotel_Open_All',[StopSaleHotelsRoomsController::class,'hotel_Open_All']);
Route::post('hotel_Rooms',[StopSaleHotelsRoomsController::class,'hotel_Rooms']);
Route::post('room_Stop_Single',[StopSaleHotelsRoomsController::class,'room_Stop_Single']);
Route::post('room_Open_Single',[StopSaleHotelsRoomsController::class,'room_Open_Single']);
Route::post('room_Stop_Date_Wise',[StopSaleHotelsRoomsController::class,'room_Stop_Date_Wise']);
Route::post('room_Stop_Date_Wise_Edit',[StopSaleHotelsRoomsController::class,'room_Stop_Date_Wise_Edit']);
Route::post('room_Open_Date_Wise',[StopSaleHotelsRoomsController::class,'room_Open_Date_Wise']);
// HH

// Alhijaz Rooms
Route::post('view_Alhijaz_Hotels_Rooms',[AlhijazHotelsRoomsController::class,'view_Alhijaz_Hotels_Rooms']);
Route::post('view_Alhijaz_Hotels_Rooms_All',[AlhijazHotelsRoomsController::class,'view_Alhijaz_Hotels_Rooms_All']);
Route::post('get_Hotel_Client_Wise',[AlhijazHotelsRoomsController::class,'get_Hotel_Client_Wise']);
Route::post('add_Alhijaz_Hotels_Rooms',[AlhijazHotelsRoomsController::class,'add_Alhijaz_Hotels_Rooms']);
Route::post('add_Alhijaz_Hotels_Rooms_All',[AlhijazHotelsRoomsController::class,'add_Alhijaz_Hotels_Rooms_All']);
Route::post('show_Allowed_Alhijaz_Hotels_Rooms',[AlhijazHotelsRoomsController::class,'show_Allowed_Alhijaz_Hotels_Rooms']);
Route::post('show_Allowed_Client_Hotels_Rooms',[AlhijazHotelsRoomsController::class,'show_Allowed_Client_Hotels_Rooms']);
Route::post('edit_Alhijaz_Hotels_Rooms',[AlhijazHotelsRoomsController::class,'edit_Alhijaz_Hotels_Rooms']);
Route::post('update_Alhijaz_Hotels_Rooms',[AlhijazHotelsRoomsController::class,'update_Alhijaz_Hotels_Rooms']);
Route::post('hotel_Suppliers_Client_Details',[AlhijazHotelsRoomsController::class,'hotel_Suppliers_Client_Details']);
Route::post('Client_Allowed_Hotel_Rooms',[AlhijazHotelsRoomsController::class,'Client_Allowed_Hotel_Rooms']);
Route::post('allowed_Hotel_Stop',[AlhijazHotelsRoomsController::class,'allowed_Hotel_Stop']);
Route::post('allowed_Hotel_Open',[AlhijazHotelsRoomsController::class,'allowed_Hotel_Open']);
Route::post('update_Allowed_Hotel_Markup',[AlhijazHotelsRoomsController::class,'update_Allowed_Hotel_Markup']);
Route::post('allowed_Room_Stop',[AlhijazHotelsRoomsController::class,'allowed_Room_Stop']);
Route::post('allowed_Room_Open',[AlhijazHotelsRoomsController::class,'allowed_Room_Open']);
Route::post('edit_Allowed_Room_Stop',[AlhijazHotelsRoomsController::class,'edit_Allowed_Room_Stop']);
Route::post('update_Allowed_Room_Stop',[AlhijazHotelsRoomsController::class,'update_Allowed_Room_Stop']);
Route::post('get_Client_Rooms',[AlhijazHotelsRoomsController::class,'get_Client_Rooms']);

// Transfers
Route::post('getAllProvidersTransfers',[AllProviderTransfersController::class,'getAllProvidersTransfers']);
Route::post('checkclientdestinations',[AllProviderTransfersController::class,'checkclientdestinations']);

// Alhijaz Rooms

// Go Global 3rd Party Apis
Route::post('travelenda_Hotel_Policies',[Travelenda_Controller::class,'travelenda_Hotel_Policies']);
// Go Global 3rd Party Apis

// TBO Holidays 3rd Party Apis
Route::post('tboholidays_Search_Hotels',[TBOHotel_3rdPartyBooking_Controller::class,'tboholidays_Search_Hotels']);
Route::post('tboholidays_Country_List',[TBOHotel_3rdPartyBooking_Controller::class,'tboholidays_Country_List']);
Route::post('tboholidays_City_List',[TBOHotel_3rdPartyBooking_Controller::class,'tboholidays_City_List']);
Route::post('tboholidays_Hotel_Codes',[TBOHotel_3rdPartyBooking_Controller::class,'tboholidays_Hotel_Codes']);
Route::post('tboholidays_Hotel_Details',[TBOHotel_3rdPartyBooking_Controller::class,'tboholidays_Hotel_Details']);
Route::post('tboholidays_add_cities',[TBOHotel_3rdPartyBooking_Controller::class,'tboholidays_add_cities']);
Route::post('tboholidays_PreBook',[TBOHotel_3rdPartyBooking_Controller::class,'tboholidays_PreBook']);
Route::post('tboholidays_book',[TBOHotel_3rdPartyBooking_Controller::class,'tboholidays_book']);
Route::post('tboholidays_BookingDetail',[TBOHotel_3rdPartyBooking_Controller::class,'tboholidays_BookingDetail']);
Route::post('tboholidays_Cancel',[TBOHotel_3rdPartyBooking_Controller::class,'tboholidays_Cancel']);
// TBO Holidays 3rd Party Apis

Route::post('create_Promotions',[PromotionController::class,'create_Promotions']);
Route::post('view_Promotions',[PromotionController::class,'view_Promotions']);
Route::post('add_Promotions',[PromotionController::class,'add_Promotions']);
Route::post('edit_Promotion',[PromotionController::class,'edit_Promotion']);
Route::post('update_Promotions',[PromotionController::class,'update_Promotions']);
Route::post('delete_Promotion',[PromotionController::class,'delete_Promotion']);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// index Page Routes
Route::post('view_invoice_location_map','frontend\user_dashboard\UserController@view_invoice_location_map');

Route::get('all_currency_get',[HomeController::class,'all_currency_get']);

Route::post('register_Verify_Mail',[HomeController::class,'register_Verify_Mail']);
Route::post('register_Verify_Otp',[HomeController::class,'register_Verify_Otp']);
Route::post('register/customer/submit',[HomeController::class,'register_customer_submit']);
Route::post('check_login_customer_submit',[HomeController::class,'check_login_customer_submit']);
Route::post('login_customer_submit',[HomeController::class,'login_customer_submit']);
Route::post('update/customer/submit',[HomeController::class,'update_customer_submit']);
Route::post('forgot_Password',[HomeController::class,'forgot_Password']);
Route::post('verify_Otp_B2B_Agent',[HomeController::class,'verify_Otp_B2B_Agent']);
Route::post('reset_Password',[HomeController::class,'reset_Password']);
Route::post('sendVerificationEmail/customer',[HomeController::class,'sendVerificationEmail']);
Route::post('sendVerificationOtpCode/customer',[HomeController::class,'sendVerificationOtpCode']);
Route::post('submitForgetPasswordForm/customer',[HomeController::class,'submitForgetPasswordForm']);
Route::post('submitResetPasswordForm/customer',[HomeController::class,'submitResetPasswordForm']);

// STUBA
Route::post('stuba_Hotel_Codes',[Stuba_Controller::class,'stuba_Hotel_Codes']);
Route::post('test_Cases_Hotel_Codes',[Stuba_Controller::class,'test_Cases_Hotel_Codes']);
Route::post('test_Cases_Search',[Stuba_Controller::class,'test_Cases_Search']);
Route::post('test_Cases_Booking_Prepare',[Stuba_Controller::class,'test_Cases_Booking_Prepare']);
Route::post('test_Cases_Booking',[Stuba_Controller::class,'test_Cases_Booking']);
Route::post('test_Cases_Cancellation',[Stuba_Controller::class,'test_Cases_Cancellation']);
Route::post('test_Cases_Hotel_Details',[Stuba_Controller::class,'test_Cases_Hotel_Details']);
// STUBA

// SUNHOTEL
Route::post('sunHotel_Test_Cases_Search',[SunHotel_Controller::class,'sunHotel_Test_Cases_Search']);
Route::post('sunHotel_Test_Cases_PreBook',[SunHotel_Controller::class,'sunHotel_Test_Cases_PreBook']);
Route::post('sunHotel_Test_Cases_Book',[SunHotel_Controller::class,'sunHotel_Test_Cases_Book']);
Route::post('sunHotel_Test_Cases_Meta',[SunHotel_Controller::class,'sunHotel_Test_Cases_Meta']);
Route::post('sunHotel_Test_Cases_Cancel',[SunHotel_Controller::class,'sunHotel_Test_Cases_Cancel']);
// SUNHOTEL

/*
|--------------------------------------------------------------------------
| Flight  Routes
|--------------------------------------------------------------------------
*/
Route::post('get/markup/flight',[FlightController::class,'get_markup_flight']);
Route::post('flight_booking_list',[FlightController::class,'flight_booking_list']);
Route::post('flight/insert/data',[FlightController::class,'flight_insert_data']);
Route::get('flight/voucher/{invoice_no}',[FlightController::class,'flight_voucher']);
Route::post('flight/trip/detail/update',[FlightController::class,'trip_detail_update']);
Route::post('flight_payment_details',[FlightController::class,'flight_payment_details']);
Route::post('flight/payment/submit',[FlightController::class,'flight_payment_submit']);
Route::post('customer/flight/ledger',[FlightController::class,'customer_flight_ledger']);
Route::post('customer/flight/payment/history',[FlightController::class,'customer_flight_history']);
Route::post('admin/booking/flight/request',[FlightController::class,'booking_request']);
Route::post('flight/booking/list/onrequest',[FlightController::class,'booking_onrequest_list']);

Route::post('flights_arrival_list',[FlightController::class,'flights_arrival_list']);
Route::post('flights_arrival_list_sub_new',[FlightController::class,'flights_arrival_list_sub_new']);

Route::post('onrequest/booking/flight/process',[FlightController::class,'onrequest_booking_process']);
Route::post('onrequest/booking/flight/process/submit',[FlightController::class,'onrequest_booking_process_submit']);
Route::post('onrequest/booking/flight/rejected',[FlightController::class,'onrequest_booking_rejected']);
Route::post('flight/update/status',[FlightController::class,'flight_update_status']);

/*
|--------------------------------------------------------------------------
| Flight  Routes
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| credit limit Routes Started By JaMsHaiD ChEeNA (23-11-2022)
|--------------------------------------------------------------------------
|
*/
Route::post('customer_get_credit_data',[CreditLimitApiController::class,'customer_get_credit_data']);
Route::post('credit/limit/list',[CreditLimitApiController::class,'credit_limit_list']);
Route::post('submit/credit/limit',[CreditLimitApiController::class,'credit_limit_submit']);
Route::post('credit/limit/history',[CreditLimitApiController::class,'costomer_credit_history']);
Route::post('credit/limit/customer/ledger',[CreditLimitApiController::class,'costomer_credit_ledger']);
Route::post('credit/limit/customer/ledger/flight',[CreditLimitApiController::class,'costomer_credit_ledger_flight']);
 
 
/*
|--------------------------------------------------------------------------
|08-06-2023 route end by jamshaid cheena
|--------------------------------------------------------------------------
*/

Route::post('hotels/payment','frontend\admin_dashboard\HotelPaymentController@payment_view');
Route::post('hotels/payment/submit','frontend\admin_dashboard\HotelPaymentController@submit_payment');
Route::post('customer/hotels/ledger','frontend\admin_dashboard\HotelPaymentController@customer_hotel_ledger');
Route::post('customer/hotels/ledger_new','frontend\admin_dashboard\HotelPaymentController@customer_hotel_ledger_new');
Route::post('customer/hotels/payment/history','frontend\admin_dashboard\HotelPaymentController@customer_hotel_payment_history');
Route::post('all_provider_booking','frontend\admin_dashboard\HotelPaymentController@all_provider_booking');
Route::post('provider_booking_3rdParty','frontend\admin_dashboard\HotelPaymentController@provider_booking_3rdParty');

/*
|--------------------------------------------------------------------------
|08-06-2023 route end by jamshaid cheena
|--------------------------------------------------------------------------
*/
Route::post('mina_packages_selected',[HajPackageController::class,'mina_packages_selected']);
Route::post('arfat_packages_selected',[HajPackageController::class,'arfat_packages_selected']);


Route::post('mina/add',[HajPackageController::class,'mina_add']);
Route::post('mina/submit',[HajPackageController::class,'mina_submit']);
Route::post('mina/list',[HajPackageController::class,'mina_list']);
Route::post('mina/edit/{id}',[HajPackageController::class,'mina_edit']);
Route::post('mina/edit_submit/{id}',[HajPackageController::class,'mina_edit_submit']);
Route::post('mina/delete/{id}',[HajPackageController::class,'mina_delete']);

Route::post('hotel_provider_bookings','frontend\admin_dashboard\HotelProviderBookingController@hotel_provider_bookings');

Route::post('arfat/add',[HajPackageController::class,'arfat_add']);
Route::post('arfat/submit',[HajPackageController::class,'arfat_submit']);
Route::post('arfat/list',[HajPackageController::class,'arfat_list']);
Route::post('arfat/edit/{id}',[HajPackageController::class,'arfat_edit']);
Route::post('arfat/edit_submit/{id}',[HajPackageController::class,'arfat_edit_submit']);
Route::post('arfat/delete/{id}',[HajPackageController::class,'arfat_delete']);



Route::post('allcountriesfetch','CountryController@allcountriesfetch');

Route::post('minapackage/add',[HajPackageController::class,'minapackage_add']);
Route::post('minapackage/submit',[HajPackageController::class,'minapackage_submit']);
Route::post('minapackage/edit/{id}',[HajPackageController::class,'minapackage_edit']);
Route::post('minapackage/edit_submit/{id}',[HajPackageController::class,'minapackage_edit_submit']);
Route::post('minapackage/list',[HajPackageController::class,'minapackage_list']);
Route::post('minapackage/delete/{id}',[HajPackageController::class,'minapackage_delete']);


Route::post('arfatpackage/add',[HajPackageController::class,'arfatpackage_add']);
Route::post('arfatpackage/submit',[HajPackageController::class,'arfatpackage_submit']);
Route::post('arfatpackage/edit/{id}',[HajPackageController::class,'arfatpackage_edit']);
Route::post('arfatpackage/edit_submit/{id}',[HajPackageController::class,'arfatpackage_edit_submit']);
Route::post('arfatpackage/list',[HajPackageController::class,'arfatpackage_list']);
Route::post('arfatpackage/delete/{id}',[HajPackageController::class,'arfatpackage_delete']);

Route::post('muzdalfapackage/add',[HajPackageController::class,'muzdalfapackage_add']);
Route::post('muzdalfapackage/submit',[HajPackageController::class,'muzdalfapackage_submit']);
Route::post('muzdalfapackage/edit/{id}',[HajPackageController::class,'muzdalfapackage_edit']);
Route::post('muzdalfapackage/edit_submit/{id}',[HajPackageController::class,'muzdalfapackage_edit_submit']);
Route::post('muzdalfapackage/list',[HajPackageController::class,'muzdalfapackage_list']);
Route::post('muzdalfapackage/delete/{id}',[HajPackageController::class,'muzdalfapackage_delete']);

/*
|--------------------------------------------------------------------------
| haj-tech Routes ended By JaMsHaiD ChEeNA (23-11-2022)
|--------------------------------------------------------------------------
|
*/

Route::post('transfers_search',[TransfersController::class,'transfers_search']);
Route::post('confirm_booking_transfer',[TransfersController::class,'confirm_booking_transfer']);
Route::post('transfer_voucher_details',[TransfersController::class,'transfer_voucher_details']);
Route::post('all_transfers_bookings',[TransfersController::class,'all_transfers_bookings']);

Route::post('transfers_search_new',[TransfersController::class,'transfers_search_new']);
Route::post('transfers_search_latest',[TransfersController::class,'transfers_search_latest']);
Route::post('transfer_checkout_submit_only',[TransfersController::class,'transfer_checkout_submit_only']);

Route::post('transfers_search_combine',[TransfersController::class,'transfers_search_combine']);

Route::post('search_Transfer_Api_static',[Transfer_3rdPartyBooking_Controller::class,'search_Transfer_Api_static']);
Route::post('book_Transfer_Api',[Transfer_3rdPartyBooking_Controller::class,'book_Transfer_Api']);
Route::post('confbook_Transfer_Api',[Transfer_3rdPartyBooking_Controller::class,'confbook_Transfer_Api']);
Route::post('search_Disclaimer_Transfer_Api',[Transfer_3rdPartyBooking_Controller::class,'search_Disclaimer_Transfer_Api']);

Route::post('transfers_search_react',[TransfersReactController::class,'transfers_search_new']);
Route::post('transfer_checkout_submit_react',[TransfersReactController::class,'transfer_checkout_submit']);
Route::post('transfer_invoice_react',[TransfersReactController::class,'transfer_invoice']);
Route::post('transfers_All_Destinations',[TransfersReactController::class,'transfers_All_Destinations']);

Route::post('vehicle_Destination_Listing',[TransfersVehicleDestinationsController::class,'vehicle_Destination_Listing']);
Route::post('vehicle_Destination_Detailing',[TransfersVehicleDestinationsController::class,'vehicle_Destination_Detailing']);
Route::post('vehicle_Destination_Confirm_Booking',[TransfersVehicleDestinationsController::class,'vehicle_Destination_Confirm_Booking']);
Route::post('vehicle_Destination_Invoice',[TransfersVehicleDestinationsController::class,'vehicle_Destination_Invoice']);

Route::post('admin/hotels_fillters_by_name',[HotelControllerApi::class,'hotels_fillters_by_name']);
Route::post('admin/hotels_fillters',[HotelControllerApi::class,'hotels_fillters']);

Route::post('flight_keys',[FlightKeysController::class,'flight_keys']);
Route::post('currency_keys',[FlightKeysController::class,'currency_keys']);
Route::post('get_customer_id',[FlightKeysController::class,'get_customer_id']);


/*
|--------------------------------------------------------------------------
| hotels route started by jamshaid cheena
|--------------------------------------------------------------------------
*/

Route::post('update_ledgers','frontend\user_dashboard\UserController@update_ledgers');



Route::post('hotels_searching','frontend\admin_dashboard\HotelBookingController@hotels_search');

Route::post('supplier_rooms_booking','frontend\admin_dashboard\HotelSupplierController@supplier_rooms_booking');
Route::post('supplier_rooms_booking_subUser','frontend\admin_dashboard\HotelSupplierController@supplier_rooms_booking_subUser');

Route::post('hotel_supplier_filter','frontend\user_dashboard\UserController@hotel_supplier_filter');
Route::post('hotel_supplier_filter_subUser','frontend\user_dashboard\UserController@hotel_supplier_filter_subUser');

Route::post('agent_booking_filter','frontend\user_dashboard\UserController@agent_booking_filter');
Route::post('agent_booking_filter_subUser','frontend\user_dashboard\UserController@agent_booking_filter_subUser');

Route::post('b2b_Agent_booking_filter','frontend\user_dashboard\B2BUserController@b2b_Agent_booking_filter');
Route::post('b2b_Agent_booking_filter_subUser','frontend\user_dashboard\B2BUserController@b2b_Agent_booking_filter_subUser');

Route::post('supplier_rooms_booking_details','frontend\admin_dashboard\HotelSupplierController@supplier_rooms_booking_details');
Route::post('all_suppliers_booking_details','frontend\admin_dashboard\HotelSupplierController@all_suppliers_booking_details');

Route::post('top_agents_booking','frontend\user_dashboard\UserController@top_agents_booking');
Route::post('top_agents_booking_subUser','frontend\user_dashboard\UserController@top_agents_booking_subUser');

Route::post('b2b_Top_Agents_Booking','frontend\user_dashboard\B2BUserController@b2b_Top_Agents_Booking');
Route::post('b2b_Top_Agents_Booking_subUser','frontend\user_dashboard\B2BUserController@b2b_Top_Agents_Booking_subUser');

Route::post('agents_details_ByType','frontend\user_dashboard\UserController@agents_details_ByType');
Route::post('customer_details_ByType','frontend\user_dashboard\UserController@customer_details_ByType');

Route::post('all_agents_booking','frontend\user_dashboard\UserController@all_agents_booking');

Route::post('top_customer_booking','frontend\user_dashboard\UserController@top_customer_booking');
Route::post('top_customer_booking_subUser','frontend\user_dashboard\UserController@top_customer_booking_subUser');

Route::post('customer_booking_filter','frontend\user_dashboard\UserController@customer_booking_filter');
Route::post('customer_booking_filter_subUser','frontend\user_dashboard\UserController@customer_booking_filter_subUser');

Route::post('all_customers_booking','frontend\user_dashboard\UserController@all_customers_booking');

Route::post('dashboard_revenue_calculate','frontend\user_dashboard\UserController@dashboard_revenue_calculate');
Route::post('dashboard_revenue_calculate_subUser','frontend\user_dashboard\UserController@dashboard_revenue_calculate_subUser');


/*
|--------------------------------------------------------------------------
|hotels route end by jamshaid cheena
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| 20-02-2023 route started by jamshaid cheena
|--------------------------------------------------------------------------
*/

Route::post('get_agent_data', 'frontend\user_dashboard\UserController@get_agent_data');
Route::post('view_markup_hotel', 'frontend\user_dashboard\UserController_Api@view_markup_api');
Route::post('submit_markup_hotel', 'frontend\user_dashboard\UserController_Api@admin_markup_api');
Route::post('markup_hotel/edit/{id}', 'frontend\user_dashboard\UserController_Api@edit_markup_api');
Route::post('markup_hotel/update/{id}', 'frontend\user_dashboard\UserController_Api@update_markup_api');
Route::post('markup_hotel/delete/{id}', 'frontend\user_dashboard\UserController_Api@delete_markup_api');
Route::post('get_markup_hotel_api', 'frontend\user_dashboard\UserController_Api@get_markup_hotel_api');

/*
|--------------------------------------------------------------------------
| 20-02-2023 route started by jamshaid cheena
|--------------------------------------------------------------------------
*/


// Route::post('view_Quotations',[
//   'middleware' => 'token_CheckMiddleware:editor',
//   'uses'       => 'frontend\admin_dashboard\ManageQuotationsControllerApi@view_Quotations',
// ]);

// Route::post('view_Quotations',[ManageQuotationsControllerApi::class,'view_Quotations']);



/*
|--------------------------------------------------------------------------
| 04-02-2023 route started by jamshaid cheena
|--------------------------------------------------------------------------
*/

Route::post('add_rooms',[RoomControllerApi::class,'addRoomsForms']);
Route::post('view_rooms',[RoomControllerApi::class,'viewRooms']);
Route::post('delete_rooms',[RoomControllerApi::class,'deleteRooms']);
Route::post('hotels_amenities',[RoomControllerApi::class,'hotels_amenities']);
Route::post('hotels_galleries',[RoomControllerApi::class,'hotels_galleries']);
Route::post('view_room_facilities',[RoomControllerApi::class,'view_room_facilities']);
Route::post('submit_rooms_facilities',[RoomControllerApi::class,'submit_rooms_facilities']);
Route::post('custom_hotel_facilities',[RoomControllerApi::class,'custom_hotel_facilities']);

// Expense 
Route::post('/add-expense', [ExpenseController::class,'create']);
Route::post('/expense-list', [ExpenseController::class,'index']);
Route::post('/expense-sub', [ExpenseController::class,'store']);
Route::post('/expense-categories', [ExpenseController::class,'expense_categories']);
Route::post('/expense-cat-submit', [ExpenseController::class,'storeCategory']);
Route::post('/expense-sub-categories', [ExpenseController::class,'expense_sub_categories']);
Route::post('/expense-sub-cat-submit', [ExpenseController::class,'expense_sub_cat_submit']);
Route::post('/fetch_sub_category', [ExpenseController::class,'fetch_sub_category']);
Route::post('/expense_print/{id}', [ExpenseController::class,'expense_print']);


/*
|--------------------------------------------------------------------------
|04-02-2023 route end by jamshaid cheena
|--------------------------------------------------------------------------
*/

// Atol
    // Setting
Route::post('atol_Register',[AtolController::class,'atol_Register'])->name('atol_Register');
Route::post('add_Register_Atol',[AtolController::class,'add_Register_Atol'])->name('add_Register_Atol');
Route::post('atol_Flight_Package',[AtolController::class,'atol_Flight_Package'])->name('atol_Flight_Package');
Route::post('add_Register_Flight_Package',[AtolController::class,'add_Register_Flight_Package'])->name('add_Register_Flight_Package');
    // Report
Route::post('atol_Report',[AtolController::class,'atol_Report'])->name('atol_Report');
Route::post('atol_Report_Weekly',[AtolController::class,'atol_Report_Weekly'])->name('atol_Report_Weekly');
Route::post('atol_Report_Monthly',[AtolController::class,'atol_Report_Monthly'])->name('atol_Report_Monthly');
Route::post('atol_Report_Quarter',[AtolController::class,'atol_Report_Quarter'])->name('atol_Report_Quarter');
Route::post('atol_Report_Half_Yearly',[AtolController::class,'atol_Report_Half_Yearly'])->name('atol_Report_Half_Yearly');
Route::post('atol_Report_Yearly',[AtolController::class,'atol_Report_Yearly'])->name('atol_Report_Yearly');
    // Certificate
Route::post('atol_Certificate',[AtolController::class,'atol_Certificate'])->name('atol_Certificate');

// Lead
Route::post('create_Lead',[LeadController::class,'create_Lead'])->name('create_Lead');
Route::post('view_Lead',[LeadController::class,'view_Lead'])->name('view_Lead');
Route::post('view_Lead_Process',[LeadController::class,'view_Lead_Process'])->name('view_Lead_Process');
Route::post('add_Lead',[LeadController::class,'add_Lead'])->name('add_Lead');
Route::post('edit_Lead',[LeadController::class,'edit_Lead'])->name('edit_Lead');
Route::post('update_Lead',[LeadController::class,'update_Lead'])->name('update_Lead');
Route::post('open_close_Lead',[LeadController::class,'open_close_Lead'])->name('open_close_Lead');
Route::post('invoice_Lead',[LeadController::class,'invoice_Lead'])->name('invoice_Lead');
Route::post('voucher_Lead',[LeadController::class,'voucher_Lead'])->name('voucher_Lead');

Route::post('create_lead_quotation',[LeadController::class,'create_lead_quotation'])->name('create_lead_quotation');

Route::post('create_lead_quotation_New',[LeadController::class,'create_lead_quotation_New'])->name('create_lead_quotation_New');

Route::post('view_manage_Lead_Quotation',[LeadController::class,'view_manage_Lead_Quotation'])->name('view_manage_Lead_Quotation');
Route::post('view_manage_Lead_Quotation_SingleAll',[LeadController::class,'view_manage_Lead_Quotation_SingleAll'])->name('view_manage_Lead_Quotation_SingleAll');
Route::post('view_manage_Lead_Quotation_Single',[LeadController::class,'view_manage_Lead_Quotation_Single'])->name('view_manage_Lead_Quotation_Single');
Route::post('add_manage_Lead_Quotation',[LeadController::class,'add_manage_Lead_Quotation'])->name('add_manage_Lead_Quotation');
Route::post('add_manage_Lead_Quotation_New',[LeadController::class,'add_manage_Lead_Quotation_New'])->name('add_manage_Lead_Quotation_New');
Route::post('edit_manage_Lead_Quotation/{id}',[LeadController::class,'edit_manage_Lead_Quotation'])->name('edit_manage_Lead_Quotation');
Route::post('update_manage_Lead_Quotation',[LeadController::class,'update_manage_Lead_Quotation'])->name('update_manage_Lead_Quotation');
Route::post('confirm_manage_Lead_Quotation',[LeadController::class,'confirm_manage_Lead_Quotation'])->name('confirm_manage_Lead_Quotation');
Route::post('confirm_Lead_Quotation',[LeadController::class,'confirm_Lead_Quotation'])->name('confirm_Lead_Quotation');
Route::post('view_atol_Lead',[LeadController::class,'view_atol_Lead'])->name('view_atol_Lead');

Route::post('add_manage_Lead_Quotation_Enquiry',[LeadController::class,'add_manage_Lead_Quotation_Enquiry'])->name('add_manage_Lead_Quotation_Enquiry');

Route::post('view_atol_certificate', [LeadController::class,'view_atol_certificate'])->name('view_atol_certificate');
Route::post('view_idemnity_form', [LeadController::class,'view_idemnity_form'])->name('view_idemnity_form');

Route::post('add_Upload_Documents', [LeadController::class,'add_Upload_Documents'])->name('add_Upload_Documents');
Route::post('view_Upload_Documents', [LeadController::class,'view_Upload_Documents'])->name('view_Upload_Documents');
Route::post('edit_Upload_Documents',[LeadController::class,'edit_Upload_Documents'])->name('edit_Upload_Documents');
Route::post('update_Upload_Documents',[LeadController::class,'update_Upload_Documents'])->name('update_Upload_Documents');
Route::post('delete_Upload_Documents',[LeadController::class,'delete_Upload_Documents'])->name('delete_Upload_Documents');

Route::post('get_pax_details', [LeadController::class,'get_pax_details'])->name('get_pax_details');
Route::post('send_quotation_mail_request', [LeadController::class,'send_quotation_mail_request'])->name('send_quotation_mail_request');
Route::post('approve_quotation_mail_request', [LeadController::class,'approve_quotation_mail_request'])->name('approve_quotation_mail_request');
Route::post('get_pax_details_InvoiceOnly', [LeadController::class,'get_pax_details_InvoiceOnly']);
Route::post('send_invoice_mail_request', [LeadController::class,'send_invoice_mail_request'])->name('send_invoice_mail_request');
Route::post('approve_invoice_mail_request', [LeadController::class,'approve_invoice_mail_request'])->name('approve_invoice_mail_request');
// End Lead

Route::post('get_pax_details_PackageOnly', [LeadController::class,'get_pax_details_PackageOnly']);
Route::post('add_more_passenger_Package_only', [LeadController::class,'add_more_passenger_Package_only']);

// Groups
Route::post('create_umrah_Visas',[ManageUmrahVisasController::class,'create_umrah_Visas'])->name('create_umrah_Visas');
Route::post('add_umrah_Visas',[ManageUmrahVisasController::class,'add_umrah_Visas'])->name('add_umrah_Visas');
Route::post('edit_umrah_Visas',[ManageUmrahVisasController::class,'edit_umrah_Visas'])->name('edit_umrah_Visas');
Route::post('update_umrah_Visas',[ManageUmrahVisasController::class,'update_umrah_Visas'])->name('update_umrah_Visas');

Route::post('get_agent_slot_ajax',[ManageUmrahVisasController::class,'get_agent_slot_ajax'])->name('get_agent_slot_ajax');
Route::post('check_group_RN_ajax',[ManageUmrahVisasController::class,'check_group_RN_ajax'])->name('check_group_RN_ajax');

Route::post('get_Agent_Groups',[ManageUmrahVisasController::class,'get_Agent_Groups'])->name('get_Agent_Groups');

// Booking Carts
Route::post('view_Booking_Cards',[ManageUmrahVisasController::class,'view_Booking_Cards'])->name('view_Booking_Cards');

Route::post('get_booking_checkout_admin','frontend\admin_dashboard\HotelController@get_booking_checkout_admin');
Route::post('save_provider_bookings','frontend\admin_dashboard\HotelProviderBookingController@bookings');
Route::post('admin_hotel_voucher','frontend\admin_dashboard\UmrahPackageController@admin_hotel_voucher');
Route::post('super_admin/fetch_all_countries','frontend\admin_dashboard\CustomerSubscription@fetch_all_countries');
Route::post('agents_financial_stats',[ManageOfficeNewController::class,'agents_financial_stats']);

Route::post('booking_financial_stats',[ManageOfficeNewController::class,'booking_financial_stats']);
Route::post('booking_financial_stats_Ajax',[ManageOfficeNewController::class,'booking_financial_stats_Ajax']);
Route::post('booking_financial_stats_month_wise',[ManageOfficeNewController::class,'booking_financial_stats_month_wise']);

Route::post('revenue_stream_agentwise',[ManageOfficeController::class,'revenue_stream_agentwise']);
Route::post('all_cost_stats',[ManageOfficeNewController::class,'all_cost_stats']);

Route::post('arrival_listing',[ManageOfficeNewController::class,'arrival_listing']);
Route::post('arrival_departure_Schedules',[ManageOfficeController::class,'arrival_departure_Schedules']);
Route::post('departure_listing',[ManageOfficeController::class,'departure_listing']);
Route::post('transfer/arrival/list',[ManageOfficeNewController::class,'transfer_arrival_list']);
Route::post('transfer_arrival_filter',[ManageOfficeNewController::class,'transfer_arrival_filter']);

Route::post('single_notification_detail/{id}',[ManageOfficeNewController::class,'single_notification_detail']);
Route::post('all_notification_detail',[ManageOfficeNewController::class,'all_notification_detail']);
Route::post('alhijaz_Notification_function','frontend\admin_dashboard\UmrahPackageController@alhijaz_Notification_function');
Route::post('financial_statement','frontend\admin_dashboard\bookingController@financial_statement');
Route::post('transfer_supplier_payments','frontend\admin_dashboard\TransferSupplierController@transfer_supplier_payments');
Route::post('transfer_supplier_ledger','frontend\admin_dashboard\TransferSupplierController@transfer_supplier_ledger');
Route::post('transfer_supplier_statement','frontend\admin_dashboard\TransferSupplierController@transfer_supplier_statement');

Route::post('hotel_arrival_list',[HotelControllerApi::class,'hotel_arrival_list']);
Route::post('hotel_departure_list',[HotelControllerApi::class,'hotel_departure_list']);

Route::post('view_Rates_Wise_Hotels',[HotelControllerApi::class,'view_Rates_Wise_Hotels']);
Route::post('view_Rates_Wise_Hotels_Makkah',[HotelControllerApi::class,'view_Rates_Wise_Hotels_Makkah']);
Route::post('view_Rates_Wise_Hotels_Madina',[HotelControllerApi::class,'view_Rates_Wise_Hotels_Madina']);
Route::post('view_Rates_Wise_Hotels_Madina_Test',[HotelControllerApi::class,'view_Rates_Wise_Hotels_Madina_Test']);

Route::post('search_api','frontend\admin_dashboard\HotelController@search_api');
//hotel supplier start//

// Hotel Supplier New
Route::post('view_room_bookings','frontend\admin_dashboard\HotelSupplierNewController@view_room_bookings');
Route::post('supplier_hotel_wallet_trans','frontend\admin_dashboard\HotelSupplierNewController@supplier_hotel_wallet_trans');
Route::post('hotel_suppliers_ledger','frontend\admin_dashboard\HotelSupplierNewController@hotel_suppliers_ledger');
Route::post('add_hotel_suppliers','frontend\admin_dashboard\HotelSupplierNewController@add_hotel_suppliers');
Route::post('submit_hotel_suppliers','frontend\admin_dashboard\HotelSupplierNewController@submit_hotel_suppliers');
Route::post('submit_mulitple_suppliers','frontend\admin_dashboard\HotelSupplierNewController@submit_mulitple_suppliers');
Route::post('mulitple_suppliers_list','frontend\admin_dashboard\HotelSupplierNewController@mulitple_suppliers_list');
Route::post('view_hotel_suppliers','frontend\admin_dashboard\HotelSupplierNewController@view_hotel_suppliers');
Route::post('edit_hotel_suppliers','frontend\admin_dashboard\HotelSupplierNewController@edit_hotel_suppliers');
Route::post('submit_edit_hotel_suppliers','frontend\admin_dashboard\HotelSupplierNewController@submit_edit_hotel_suppliers');
Route::post('delete_hotel_suppliers','frontend\admin_dashboard\HotelSupplierNewController@delete_hotel_suppliers');
// Hotel Supplier New

Route::post('hotel_supplier_payments','frontend\admin_dashboard\HotelSupplierController@hotel_supplier_payments');
Route::post('hotel_suppliers_statement','frontend\admin_dashboard\HotelSupplierController@hotel_suppliers_statement');
Route::post('hotel_suppliers_statement_Client','frontend\admin_dashboard\HotelSupplierController@hotel_suppliers_statement_Client');

Route::post('hotel_supplier_reports','frontend\admin_dashboard\HotelSupplierController@hotel_supplier_reports');
Route::post('hotel_supplier_reports_sub','frontend\admin_dashboard\HotelSupplierController@hotel_supplier_reports_sub');


// Allow Suppliers
Route::post('allow_Suppliers','frontend\admin_dashboard\AllowSupplierController@allow_Suppliers');
Route::post('view_Invoices_Suppliers','frontend\admin_dashboard\AllowSupplierController@view_Invoices_Suppliers');
Route::post('add_Hotel_Supplier_To_Customer','frontend\admin_dashboard\AllowSupplierController@add_Hotel_Supplier_To_Customer');
Route::post('add_Flight_Supplier_To_Customer','frontend\admin_dashboard\AllowSupplierController@add_Flight_Supplier_To_Customer');
Route::post('add_Transfer_Supplier_To_Customer','frontend\admin_dashboard\AllowSupplierController@add_Transfer_Supplier_To_Customer');
Route::post('add_Visa_Supplier_To_Customer','frontend\admin_dashboard\AllowSupplierController@add_Visa_Supplier_To_Customer');
// Allow Suppliers

Route::post('supplierCheckin','frontend\admin_dashboard\HotelSupplierController@supplierCheckin');
Route::post('checkSupplierCheckin','frontend\admin_dashboard\HotelSupplierController@checkSupplierCheckin');

Route::post('hotel_supplier_reports_new','frontend\admin_dashboard\HotelSupplierController@hotel_supplier_reports_new');
Route::post('all_Cients_Arrival_list','frontend\admin_dashboard\HotelSupplierController@all_Cients_Arrival_list');
Route::post('all_Cients_Arrival_list_Report','frontend\admin_dashboard\HotelSupplierController@all_Cients_Arrival_list_Report');
Route::post('hotel_supplier_reports_sub_new','frontend\admin_dashboard\HotelSupplierController@hotel_supplier_reports_sub_new');
Route::post('hotel_supplier_reports_sub_new_subUser','frontend\admin_dashboard\HotelSupplierController@hotel_supplier_reports_sub_new_subUser');

Route::post('hotel_supplier_reports_sub_new_HH','frontend\admin_dashboard\HotelSupplierController@hotel_supplier_reports_sub_new_HH');
Route::post('hotel_supplier_reports_sub_new_subUser_HH','frontend\admin_dashboard\HotelSupplierController@hotel_supplier_reports_sub_new_subUser_HH');

Route::post('hotel_supplier_reports_sub_departure_new','frontend\admin_dashboard\HotelSupplierController@hotel_supplier_reports_sub_departure_new');
Route::post('hotel_supplier_reports_sub_departure_new_subUser','frontend\admin_dashboard\HotelSupplierController@hotel_supplier_reports_sub_departure_new_subUser');

Route::post('hotel_supplier_stats','frontend\admin_dashboard\HotelSupplierController@hotel_supplier_stats');

Route::post('get_PDF_AD','frontend\admin_dashboard\HotelSupplierController@get_PDF_AD');
Route::post('add_PDF_AD','frontend\admin_dashboard\HotelSupplierController@add_PDF_AD');
Route::post('send_PDF_AD','frontend\admin_dashboard\HotelSupplierController@send_PDF_AD');
Route::post('change_PDF_AD','frontend\admin_dashboard\HotelSupplierController@change_PDF_AD');
//hotel supplier ended//


// add visa suplier
Route::post('submit_visa_suppliers','frontend\admin_dashboard\visaSupplierController@submit_visa_suppliers');
Route::post('get_visa_suppliers','frontend\admin_dashboard\visaSupplierController@get_visa_suppliers');
Route::post('get_visa_suppliers_for_edit','frontend\admin_dashboard\visaSupplierController@get_visa_suppliers_for_edit');
Route::post('submit_visa_suppliers_for_update','frontend\admin_dashboard\visaSupplierController@submit_visa_suppliers_for_update');
Route::post('get_supplier_visas','frontend\admin_dashboard\visaSupplierController@get_supplier_visas');
Route::post('get_visa_prices','frontend\admin_dashboard\visaSupplierController@get_visa_prices');
Route::post('visa_supplier_new_slot','frontend\admin_dashboard\visaSupplierController@visa_supplier_new_slot');
Route::post('add_visa_supplier_new_slot','frontend\admin_dashboard\visaSupplierController@add_visa_supplier_new_slot');
Route::post('edit_visa_supplier_slot','frontend\admin_dashboard\visaSupplierController@edit_visa_supplier_slot');
Route::post('update_visa_supplier_slot','frontend\admin_dashboard\visaSupplierController@update_visa_supplier_slot');
Route::post('visa_supplier_ledger','frontend\admin_dashboard\visaSupplierController@visa_supplier_ledger');
Route::post('visa_supplier_statement','frontend\admin_dashboard\visaSupplierController@visa_supplier_statement');



//
// submit_visa_type
Route::post('get_visa_type_for_sup','frontend\admin_dashboard\visaSupplierController@get_visa_type_for_sup');
Route::post('submit_visa_avalability_for_sup','frontend\admin_dashboard\visaSupplierController@submit_visa_avalability_for_sup');
Route::post('view_visa_type','frontend\admin_dashboard\visaSupplierController@view_visa_type');


//
Route::post('add_transfer_suppliers','frontend\admin_dashboard\TransferSupplierController@add_transfer_suppliers');
Route::post('submit_transfer_suppliers','frontend\admin_dashboard\TransferSupplierController@submit_transfer_suppliers');
Route::post('view_transfer_suppliers','frontend\admin_dashboard\TransferSupplierController@view_transfer_suppliers');
Route::post('transfer_supplier_stats','frontend\admin_dashboard\TransferSupplierController@transfer_supplier_stats');
Route::post('edit_transfer_suppliers','frontend\admin_dashboard\TransferSupplierController@edit_transfer_suppliers');
Route::post('submit_edit_transfer_suppliers','frontend\admin_dashboard\TransferSupplierController@submit_edit_transfer_suppliers');
Route::post('delete_transfer_suppliers','frontend\admin_dashboard\TransferSupplierController@delete_transfer_suppliers');


Route::post('add_transfer_company','frontend\admin_dashboard\TransferCompanyController@add_transfer_company');
Route::post('submit_transfer_company','frontend\admin_dashboard\TransferCompanyController@submit_transfer_company');
Route::post('view_transfer_company','frontend\admin_dashboard\TransferCompanyController@view_transfer_company');
Route::post('transfer_company_stats','frontend\admin_dashboard\TransferCompanyController@transfer_company_stats');
Route::post('edit_transfer_company','frontend\admin_dashboard\TransferCompanyController@edit_transfer_company');
Route::post('submit_edit_transfer_company','frontend\admin_dashboard\TransferCompanyController@submit_edit_transfer_company');
Route::post('delete_transfer_company','frontend\admin_dashboard\TransferCompanyController@delete_transfer_company');

//supplier start//
Route::post('fetchallhotels',[SupplierControllerApi::class,'fetchallhotels']);
Route::post('all_Hotels_Availability',[SupplierControllerApi::class,'all_Hotels_Availability']);
Route::post('fetchhotelrecord',[SupplierControllerApi::class,'fetchhotelrecord']);
Route::post('chart_Data_All_Hotels',[SupplierControllerApi::class,'chart_Data_All_Hotels']);
Route::post('fetchhotelrecord_test',[SupplierControllerApi::class,'fetchhotelrecord_test']);
Route::post('chart_data_Ajax',[SupplierControllerApi::class,'chart_data_Ajax']);
Route::post('chart_data_Room',[SupplierControllerApi::class,'chart_data_Room']);
Route::post('view_seat_occupancy',[SupplierControllerApi::class,'view_seat_occupancy']);
Route::post('invoice_for_occupancy',[SupplierControllerApi::class,'invoice_for_occupancy']);
Route::post('fetchflightrate',[SupplierControllerApi::class,'fetchflightrate']);
Route::post('pax_details',[SupplierControllerApi::class,'pax_details']);

Route::post('get_flights_occupancy',[SupplierControllerApi::class,'get_flights_occupancy']);

//mutamer
Route::post('getinvoice',[MutamerControllerApi::class,'getinvoice']);
Route::post('addlead',[MutamerControllerApi::class,'addlead']);
Route::post('leadpassengerin',[MutamerControllerApi::class,'leadpassengerin']);
Route::post('save_lead_location',[MutamerControllerApi::class,'save_lead_location']);
Route::post('get_lead_location',[MutamerControllerApi::class,'get_lead_location']);
Route::post('cron_update_lead_location',[MutamerControllerApi::class,'cron_update_lead_location']);
Route::post('lead_logout',[MutamerControllerApi::class,'lead_logout']);
Route::post('get_invoice_data',[MutamerControllerApi::class,'get_invoice_data']);

Route::post('createsupplier',[SupplierControllerApi::class,'createsupplier']);
Route::post('flight_supplier_ledger',[SupplierControllerApi::class,'flight_supplier_ledger']);
Route::post('flight_supplier_statement',[SupplierControllerApi::class,'flight_supplier_statement']);

Route::post('addsupplier',[SupplierControllerApi::class,'addsupplier']);
Route::post('get_suppliers_flights_detail',[SupplierControllerApi::class,'get_suppliers_flights_detail']);
Route::post('get_suppliers_flights_rute',[SupplierControllerApi::class,'get_suppliers_flights_rute']);

Route::post('fetchsupplier',[SupplierControllerApi::class,'fetchsupplier']);
Route::post('supplier_wallet_trans',[SupplierControllerApi::class,'supplier_wallet_trans']);
Route::post('deletesupplier',[SupplierControllerApi::class,'deletesupplier']);
Route::post('editsupplier',[SupplierControllerApi::class,'editsupplier']);
Route::post('updatesupplier',[SupplierControllerApi::class,'updatesupplier']);
// fetch supllier name
Route::post('fetchsuppliername',[SupplierControllerApi::class,'fetchsuppliername']);

Route::post('fetchallsupplierforseats',[SupplierControllerApi::class,'fetchallsupplierforseats']);
Route::post('createseat',[SupplierControllerApi::class,'createseat']);
Route::post('createseat1',[SupplierControllerApi::class,'createseat1']);
Route::post('fetchseat',[SupplierControllerApi::class,'fetchseat']);
Route::post('deleteseat',[SupplierControllerApi::class,'deleteseat']);
Route::post('editseat',[SupplierControllerApi::class,'editseat']);
Route::post('updateseat',[SupplierControllerApi::class,'updateseat']);

Route::post('fetchairline',[SupplierControllerApi::class,'fetchairline']);
Route::post('save_flight_payment_recieved_and_remaining',[SupplierControllerApi::class,'save_flight_payment_recieved_and_remaining']);
// chart
Route::post('supplierdetail',[SupplierControllerApi::class,'supplierdetail']);
// Route::post('getbookingofroom',[SupplierControllerApi::class,'getbookingofroom']);
Route::post('getbooking',[SupplierControllerApi::class,'getbooking']);
//supplier end//
// customer subcriptions authentication
Route::post('customer_subcriptions_authentication',[HotelbookingController::class,'customer_subcriptions_authentication']);
Route::post('view_track_id_booking_hotel',[UmrahPackageControllerApi::class,'view_track_id_booking_hotel']);
// Transfer Search
Route::post('transfer_serach','frontend\admin_dashboard\TransferzController@transfer_serach');
Route::post('SaveTransferBooking','frontend\admin_dashboard\TransferzController@SaveTransferBooking');
Route::post('add_lead_passengar_transfer','frontend\admin_dashboard\TransferzController@add_lead_passengar_transfer');
Route::post('add_other_passengar_transfer','frontend\admin_dashboard\TransferzController@add_other_passengar_transfer');
Route::post('confrimTransferbooking','frontend\admin_dashboard\TransferzController@confrimTransferbooking');
Route::post('transfer_voucher','frontend\admin_dashboard\TransferzController@transfer_voucher');
Route::post('transfer_cancellation','frontend\admin_dashboard\TransferzController@transfer_cancellation');
// Transfer Search

Route::post('payment_messages','frontend\admin_dashboard\UmrahPackageController@payment_messages');
Route::post('submit_payment_messages','frontend\admin_dashboard\UmrahPackageController@submit_payment_messages');
Route::post('edit_payment_messages','frontend\admin_dashboard\UmrahPackageController@edit_payment_messages');

Route::post('hotel_voucher/{id}/{slug}','frontend\admin_dashboard\UmrahPackageController@hotel_voucher');
Route::post('save_passenger_detail_hotel','frontend\admin_dashboard\HotelController@save_passenger_detail_hotel');
Route::post('add_lead_passengar','frontend\admin_dashboard\HotelController@add_lead_passengar');
Route::post('add_lead_passengar_provider','frontend\admin_dashboard\HotelProviderBookingController@add_lead_passengar');
Route::post('add_other_passengar','frontend\admin_dashboard\HotelController@add_other_passengar');
Route::post('add_child_passengar','frontend\admin_dashboard\HotelController@add_child_passengar');
Route::post('hotelbed_booking','frontend\admin_dashboard\HotelController@hotelbed_booking');
Route::post('hotel_bed_cancelliation','frontend\admin_dashboard\HotelController@hotel_bed_cancelliation');
Route::post('get_booking_checkout','frontend\admin_dashboard\HotelController@get_booking_checkout');
Route::post('hotel_cart','frontend\admin_dashboard\HotelController@hotel_cart');
Route::post('travellanda_cancel_policy_data','frontend\admin_dashboard\HotelController@travellanda_cancel_policy_data');
Route::post('get_travelanda_hotel_details','frontend\admin_dashboard\HotelController@get_travelanda_hotel_details');

Route::post('get_hotel_cities_code','frontend\admin_dashboard\HotelController@get_hotel_cities_code');
Route::post('get_hotel_details_by_hotelbeds','frontend\admin_dashboard\HotelController@get_hotel_details_by_hotelbeds');
Route::post('travellanda_get_hotel_details','frontend\admin_dashboard\HotelController@travellanda_get_hotel_details');

Route::post('tbo_get_hotel_code_list','frontend\admin_dashboard\HotelController@tbo_get_hotel_code_list');
Route::post('get_booking_with_token','frontend\admin_dashboard\HotelController@get_booking_with_token');
// Route::post('all_provider_booking','frontend\admin_dashboard\HotelController@all_provider_booking');
Route::post('get_booking_with_token_hotelbeds','frontend\admin_dashboard\HotelController@get_booking_with_token_hotelbeds');
Route::post('get_booking_with_token_tbo','frontend\admin_dashboard\HotelController@get_booking_with_token_tbo');
Route::post('get_booking_with_token_ratehawk','frontend\admin_dashboard\HotelController@get_booking_with_token_ratehawk');

Route::post('booking_now_admin','frontend\admin_dashboard\HotelController@booking_now_admin');
Route::post('get_payment_detact_by_admin','frontend\admin_dashboard\HotelController@get_payment_detact_by_admin');
Route::post('get_hotel_payment_details_by_id','frontend\admin_dashboard\HotelController@get_hotel_payment_details_by_id');


Route::post('get_country_hotel_beds','frontend\admin_dashboard\HotelController@get_country_hotel_beds');

    Route::post('submit_other_visa_type','frontend\admin_dashboard\UmrahPackageController@submit_other_visa_type');
    Route::post('get_other_visa_type','frontend\admin_dashboard\UmrahPackageController@get_other_visa_type');
         
    Route::post('submit_other_Hotel_Name','frontend\admin_dashboard\UmrahPackageController@submit_other_Hotel_Name');
    Route::post('get_other_Hotel_Name','frontend\admin_dashboard\UmrahPackageController@get_other_Hotel_Name');
    
    Route::post('submit_pickup_location','frontend\admin_dashboard\UmrahPackageController@submit_pickup_location');
    Route::post('get_pickup_dropof_location','frontend\admin_dashboard\UmrahPackageController@get_pickup_dropof_location');
    
    Route::post('submit_dropof_location','frontend\admin_dashboard\UmrahPackageController@submit_dropof_location');
    
    Route::post('view_Airline_Name','frontend\admin_dashboard\UmrahPackageController@view_Airline_Name');
    Route::post('submitForm_Airline_Name','frontend\admin_dashboard\UmrahPackageController@submitForm_Airline_Name');
    Route::post('get_other_Airline_Name','frontend\admin_dashboard\UmrahPackageController@get_other_Airline_Name');
    Route::post('edit_Airline_Name','frontend\admin_dashboard\UmrahPackageController@edit_Airline_Name');
    Route::post('update_Airline_Name','frontend\admin_dashboard\UmrahPackageController@update_Airline_Name');
    
    Route::post('submit_other_Hotel_Type','frontend\admin_dashboard\UmrahPackageController@submit_other_Hotel_Type');
    Route::post('get_other_Hotel_Type','frontend\admin_dashboard\UmrahPackageController@get_other_Hotel_Type');
    
    Route::post('save_activities','ActivityController@save_activities');
    Route::post('update_activities','ActivityController@update_activities');
    Route::post('index_activities','ActivityController@index_activities');
    
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| api routes written by Usama Ali
|--------------------------------------------------------------------------
|
| 
|
*/

Route::get('get_hotelbeds_token','frontend\admin_dashboard\UmrahPackageControllerApi@get_hotelbeds_token');


/*
|--------------------------------------------------------------------------
| api routes written by Usama Ali 22-06-2022
|--------------------------------------------------------------------------
|
| 
|
*/

Route::post('get_flight_name',[ManageQuotationsControllerApi::class,'get_flight_name']);

// Activity Booking
Route::post('view_ternary_bookings_Activity','frontend\admin_dashboard\UmrahPackageControllerApi@view_ternary_bookings_Activity');
Route::post('view_confirmed_bookings_Activity','frontend\admin_dashboard\UmrahPackageControllerApi@view_confirmed_bookings_Activity');

Route::post('view_booking_payment_Activity/{id}/{tourId}','frontend\admin_dashboard\UmrahPackageControllerApi@view_booking_payment_Activity');
Route::post('view_booking_detail_Activity/{id}/{tourId}','frontend\admin_dashboard\UmrahPackageControllerApi@view_booking_detail_Activity');
Route::post('view_booking_customer_details_Activity/{id}','frontend\admin_dashboard\UmrahPackageControllerApi@view_booking_customer_details_Activity');
Route::post('confirmed_tour_booking_Activity/{id}','frontend\admin_dashboard\UmrahPackageControllerApi@confirmed_tour_booking_Activity');

Route::post('view_booking_payment_recieve_Activity/{tourId}','frontend\admin_dashboard\UmrahPackageControllerApi@view_booking_payment_recieve_Activity');
Route::post('view_ternary_bookings_tourId_Activity/{P_Id}','frontend\admin_dashboard\UmrahPackageControllerApi@view_ternary_bookings_tourId_Activity');
// END Activity Booking

// Tour Booking
Route::post('view_ternary_bookings','frontend\admin_dashboard\UmrahPackageControllerApi@view_ternary_bookings');
Route::post('view_confirmed_bookings','frontend\admin_dashboard\UmrahPackageControllerApi@view_confirmed_bookings');

Route::post('view_booking_payment/{id}/{tourId}/{cartD_ID}','frontend\admin_dashboard\UmrahPackageControllerApi@view_booking_payment');
Route::post('view_booking_detail/{id}/{tourId}','frontend\admin_dashboard\UmrahPackageControllerApi@view_booking_detail');
Route::post('view_booking_customer_details/{id}','frontend\admin_dashboard\UmrahPackageControllerApi@view_booking_customer_details');
Route::post('confirmed_tour_booking/{id}','frontend\admin_dashboard\UmrahPackageControllerApi@confirmed_tour_booking');

Route::post('view_booking_payment_recieve/{tourId}','frontend\admin_dashboard\UmrahPackageControllerApi@view_booking_payment_recieve');
Route::post('view_ternary_bookings_tourId/{P_Id}','frontend\admin_dashboard\UmrahPackageControllerApi@view_ternary_bookings_tourId');

Route::post('occupancy_tour/{id}','frontend\admin_dashboard\UmrahPackageController@occupancy_tour');

Route::post('view_Tour_PopUp/{id}','frontend\admin_dashboard\UmrahPackageController@view_Tour_PopUp');

// View Tour More Details
Route::post('more_Tour_Details/{id}','frontend\admin_dashboard\UmrahPackageController@more_Tour_Details');
Route::post('get_tours_booking','frontend\admin_dashboard\UmrahPackageController@get_tours_booking');
Route::post('view_more_bookings/{id}','frontend\admin_dashboard\UmrahPackageController@view_more_bookings');

Route::post('super_admin_index','frontend\admin_dashboard\UmrahPackageController@super_admin_index');

Route::post('super_admin_index_Latest','frontend\admin_dashboard\UmrahPackageController@super_admin_index_Latest');
Route::post('super_admin_index_subUser','frontend\admin_dashboard\UmrahPackageController@super_admin_index_subUser');
Route::post('super_admin_index_Alhijaz','frontend\admin_dashboard\UmrahPackageController@super_admin_index_Alhijaz');

Route::post('get_Days_details','frontend\admin_dashboard\UmrahPackageController@get_Days_details');
Route::post('get_Type_details','frontend\admin_dashboard\UmrahPackageController@get_Type_details');

//Quotations


/*
|--------------------------------------------------------------------------
| api routes written by Usama Ali 22-06-2022
|--------------------------------------------------------------------------
|
| 
|
*/

// Customer Subcrition 
Route::post('addTransferAmount',[CashAccControllerApi::class,'addTransferAmount']);

Route::post('payment_recv_list',[CashAccControllerApi::class,'payment_recv_list']);
Route::post('payment_recv_list_filter',[CashAccControllerApi::class,'payment_recv_list_filter']);
Route::post('make_payment_list',[CashAccControllerApi::class,'make_payment_list']);
Route::post('make_payment_list_filter',[CashAccControllerApi::class,'make_payment_list_filter']);
Route::post('subcritions_details',[CashAccControllerApi::class,'subcritions_details']);
Route::post('subcirbed_customer_ledger',[CashAccControllerApi::class,'subcirbed_customer_ledger']);
Route::post('subcritions_payments_request',[CashAccControllerApi::class,'subcritions_payments_request']);
Route::post('subcritions_payments_request_sub',[CashAccControllerApi::class,'subcritions_payments_request_sub']);

Route::post('cash_accounts_list',[CashAccControllerApi::class,'cash_accounts_list']);
Route::post('account_statement',[CashAccControllerApi::class,'account_statement']);
Route::post('cash_accounts_ledger',[CashAccControllerApi::class,'cash_accounts_ledger']);
Route::post('cash_accounts_add',[CashAccControllerApi::class,'cash_accounts_add']);
Route::post('add_payment_recv',[CashAccControllerApi::class,'add_payment_recv']);
Route::post('payments_recv_submit',[CashAccControllerApi::class,'payments_recv_submit']);
Route::post('agent_payments_list',[CashAccControllerApi::class,'agent_payments_list']);
Route::post('customer_payments_list',[CashAccControllerApi::class,'customer_payments_list']);
Route::post('agent_payments_list_datewise',[CashAccControllerApi::class,'agent_payments_list_datewise']);
Route::post('customer_payments_list_datewise',[CashAccControllerApi::class,'customer_payments_list_datewise']);
Route::post('supplier_payments_list',[CashAccControllerApi::class,'supplier_payments_list']);
Route::post('get_HS_Payment_List_Season',[CashAccControllerApi::class,'get_HS_Payment_List_Season']);

// Delete Payments
Route::post('delete_supplier_payments',[CashAccControllerApi::class,'delete_supplier_payments']);
Route::post('delete_agent_payments',[CashAccControllerApi::class,'delete_agent_payments']);

// Invoice Agent Receipt
Route::post('invoice_Receipt_Agent',[CashAccControllerApi::class,'invoice_Receipt_Agent']);

// Invoice Customer Receipt
Route::post('invoice_Receipt_Customer',[CashAccControllerApi::class,'invoice_Receipt_Customer']);

Route::post('view_pay_recv',[CashAccControllerApi::class,'view_pay_recv']);
Route::post('payments_add_submit',[CashAccControllerApi::class,'payments_add_submit']);
Route::post('get_person_invoices',[CashAccControllerApi::class,'get_person_invoices']);
Route::post('update_received_payment',[CashAccControllerApi::class,'update_received_payment']);
Route::post('update_make_payment',[CashAccControllerApi::class,'update_make_payment']);

// Hotel Route
Route::post('get_hotel_booking',[HotelControllerApi::class,'get_hotel_booking']);
Route::post('hotel_manger/add_hotel',[HotelControllerApi::class,'showAddHotelFrom']);
Route::post('/hotel_manger/add_hotel_sub',[HotelControllerApi::class,'addHotel']);
Route::post('/addHotelTest',[HotelControllerApi::class,'addHotelTest']);
Route::post('/hotel_manger/add_hotel_sub_ajax',[HotelControllerApi::class,'add_hotel_sub_ajax']);
Route::post('/hotel_manger/hotel_list_All',[HotelControllerApi::class,'hotel_list_All']);
Route::post('/hotel_manger/view_Add_Hotel_Test',[HotelControllerApi::class,'view_Add_Hotel_Test']);
Route::post('/hotel_manger/viewAgentsDebitCreditList',[HotelControllerApi::class,'viewAgentsDebitCreditList']);
Route::post('/hotel_manger/getAgentsDebitCreditList',[HotelControllerApi::class,'getAgentsDebitCreditList']);
Route::post('/hotel_manger/add_Hotel_OD',[HotelControllerApi::class,'add_Hotel_OD']);
Route::post('/hotel_manger/hotel_list',[HotelControllerApi::class,'index']);
Route::post('/country_cites', [CountryController::class,'countryCites']);
Route::post('/country_code', [CountryController::class,'country_code']);

Route::post('hotel_Bookings',[HotelControllerApi::class,'hotel_Bookings']);
Route::post('hotel_Bookings_Selected',[HotelControllerApi::class,'hotel_Bookings_Selected']);
Route::post('custome_Hotel_Bookings',[HotelControllerApi::class,'custome_Hotel_Bookings']);
Route::post('confirm_Alif_Booking',[HotelControllerApi::class,'confirm_Alif_Booking']);
Route::post('add_HCN_Hotel_Booking',[HotelControllerApi::class,'add_HCN_Hotel_Booking']);
Route::post('add_BRN_Hotel_Booking',[HotelControllerApi::class,'add_BRN_Hotel_Booking']);
Route::post('add_CR_Proceed',[HotelControllerApi::class,'add_CR_Proceed']);
Route::post('reject_Cncellation_Request',[HotelControllerApi::class,'reject_Cncellation_Request']);
Route::post('confirm_Booking',[HotelControllerApi::class,'confirm_Booking']);
Route::post('hotel_Bookings_Client',[HotelControllerApi::class,'hotel_Bookings_Client']);

Route::post('add_Transfer_Markup',[TransfersReactController::class,'add_Transfer_Markup']);
Route::post('transfer_Make_Payemnt',[TransfersReactController::class,'transfer_Make_Payemnt']);
Route::post('make_Cancel_Request_Transfer',[TransfersReactController::class,'make_Cancel_Request_Transfer']);
Route::post('add_CR_Proceed_Transfer',[TransfersReactController::class,'add_CR_Proceed_Transfer']);
Route::post('reject_Cncellation_Request_Transfer',[TransfersReactController::class,'reject_Cncellation_Request_Transfer']);
Route::post('reject_Pyament_Booking_Transfer',[TransfersReactController::class,'reject_Pyament_Booking_Transfer']);
Route::post('confirm_Booking_Transfer',[TransfersReactController::class,'confirm_Booking_Transfer']);

Route::post('hotel_manger/edit_hotel',[HotelControllerApi::class,'showeditHotelFrom']);
Route::post('hotel_manger/edit_hotel/submit',[HotelControllerApi::class,'updateHotel']);

Route::post('delete_hotels',[HotelControllerApi::class,'delete_hotels']);

Route::post('/city', function () {
    $city = city::find(1);
    $country = $city->Country;
    dd($country);
    echo "This is Country ";
});
    
// Manage Office Routes
    
    // Agents
    Route::post('agent_ledeger',[ManageOfficeNewController::class,'agent_ledeger']);
    Route::post('agent_booking_statement',[ManageOfficeNewController::class,'agent_booking_statement']);
    Route::post('customer_booking_statement',[ManageOfficeNewController::class,'customer_booking_statement']);
    
    Route::post('agent_account_statement_datewise_Ajax',[ManageOfficeNewController::class,'agent_account_statement_datewise_Ajax']);
    
    Route::post('agent_ledeger1',[ManageOfficeNewController::class,'agent_ledeger1']);
    
    Route::post('create_customer',[ManageOfficeNewController::class,'create_customer']);
    Route::post('submit_customer',[ManageOfficeNewController::class,'submit_customer']);
    Route::post('edit_customer',[ManageOfficeNewController::class,'edit_customer']);
    Route::post('edit_customer_submit',[ManageOfficeNewController::class,'edit_customer_submit']);
    Route::post('customer_profile',[ManageOfficeController::class,'customer_profile']);
    Route::post('agent_profile',[ManageOfficeController::class,'agent_profile']);
    
    Route::post('get_customer_ifExist',[LeadController::class,'get_customer_ifExist'])->name('get_customer_ifExist');
    
    Route::post('customer_ledger',[ManageOfficeNewController::class,'customer_ledger']);
    Route::post('create_Agents',[ManageOfficeController::class,'create_Agents']);
    Route::post('add_Agents',[ManageOfficeController::class,'add_Agents']);
    Route::post('edit_Agents/{id}',[ManageOfficeController::class,'edit_Agents']);
    Route::post('update_Agents/{id}',[ManageOfficeController::class,'update_Agents']);
    Route::post('archive_item','frontend\admin_dashboard\bookingController@archive_item'); 
    Route::post('archived_items_list','frontend\admin_dashboard\bookingController@archived_items_list'); 
    Route::post('delete_agent_parmanently','frontend\admin_dashboard\bookingController@delete_agent_parmanently'); 
    Route::post('delete_customer_parmanently','frontend\admin_dashboard\bookingController@delete_customer_parmanently'); 
    
    // Invoice
    Route::post('create_Invoices',[ManageOfficeController::class,'create_Invoices']);
    Route::post('view_Invoices',[ManageOfficeController::class,'view_Invoices']);
    Route::post('view_Invoices_Season',[ManageOfficeController::class,'view_Invoices_Season']);
    Route::post('add_Invoices',[ManageOfficeController::class,'add_Invoices']);
    Route::post('add_Invoices_CP',[ManageOfficeController::class,'add_Invoices_CP']);
    Route::post('add_Invoices_test',[ManageOfficeController::class,'add_Invoices_test']);
    Route::post('delete_invoice',[ManageOfficeController::class,'delete_invoice']);
    
    Route::post('delete_Quotation',[ManageOfficeController::class,'delete_Quotation']);
    
    Route::post('agents_new_slot',[ManageOfficeController::class,'agents_new_slot']);
    Route::post('add_agents_new_slot',[ManageOfficeController::class,'add_agents_new_slot']);
    Route::post('edit_agents_new_slot',[ManageOfficeController::class,'edit_agents_new_slot']);
    Route::post('update_agents_new_slot',[ManageOfficeController::class,'update_agents_new_slot']);
    
    // Extra Services Invoice
    Route::post('services_submit_Ajax',[ManageOfficeController::class,'services_submit_Ajax']);
    Route::post('add_extra_Services_Invoices',[ManageOfficeController::class,'add_extra_Services_Invoices']);
    Route::post('view_Invoices_Extra',[ManageOfficeController::class,'view_Invoices_Extra']);
    Route::post('view_Invoices_Extra_2024',[ManageOfficeController::class,'view_Invoices_Extra_2024']);
    Route::post('Extra_Services_invoice_Agent',[ManageOfficeController::class,'Extra_Services_invoice_Agent']);
    Route::post('edit_Extra_Services_invoice_Agent',[ManageOfficeController::class,'edit_Extra_Services_invoice_Agent']);
    Route::post('update_extra_Services_Invoices',[ManageOfficeController::class,'update_extra_Services_Invoices']);
    // Extra Services Invoice
    
    // Enquiry
    Route::post('view_Agents_Enquiry',[ManageAgentEnquiryController::class,'view_Agents_Enquiry']);
    Route::post('view_Agents_Enquiry_Quotations',[ManageAgentEnquiryController::class,'view_Agents_Enquiry_Quotations']);
    Route::post('view_Agents_Enquiry_Invoices',[ManageAgentEnquiryController::class,'view_Agents_Enquiry_Invoices']);
    Route::post('create_Agents_Enquiry',[ManageAgentEnquiryController::class,'create_Agents_Enquiry']);
    Route::post('add_Agents_Enquiry',[ManageAgentEnquiryController::class,'add_Agents_Enquiry']);
    Route::post('add_Agents_Enquiry_Next',[ManageAgentEnquiryController::class,'add_Agents_Enquiry_Next']);
    Route::post('proceed_Agents_Enquiry',[ManageAgentEnquiryController::class,'proceed_Agents_Enquiry']);
    Route::post('edit_Agents_Enquiry',[ManageAgentEnquiryController::class,'edit_Agents_Enquiry']);
    Route::post('update_Agents_Enquiry',[ManageAgentEnquiryController::class,'update_Agents_Enquiry']);
    Route::post('update_Agents_Enquiry_Next',[ManageAgentEnquiryController::class,'update_Agents_Enquiry_Next']);
    Route::post('invoice_Agents_Enquiry',[ManageAgentEnquiryController::class,'invoice_Agents_Enquiry']);
    Route::post('add_Reject_Enquiry',[ManageAgentEnquiryController::class,'add_Reject_Enquiry']);
    Route::post('delete_Agents_Enquiry',[ManageAgentEnquiryController::class,'delete_Agents_Enquiry']);
    
    // Email Enquiry
    Route::post('getAllEmailEnquiries',[emailEnquiryController::class,'getAllEmailEnquiries']);
    Route::post('addEmailEnquiries',[emailEnquiryController::class,'addEmailEnquiries']);
    Route::post('addEmailEnquies',[emailEnquiryController::class,'addEmailEnquies']);
    
    // Transfer Supplier Invoice
    Route::post('get_TranferSuppliers',[ManageOfficeNewController::class,'get_TranferSuppliers']);
    Route::post('add_Invoices_TranSupp',[ManageOfficeNewController::class,'add_Invoices_TranSupp']);
    Route::post('update_Invoices_TranSupp/{id}',[ManageOfficeNewController::class,'update_Invoices_TranSupp']);
    
    Route::post('edit_Invoices/{id}',[ManageOfficeController::class,'edit_Invoices']);
    Route::post('update_Invoices/{id}',[ManageOfficeController::class,'update_Invoices']);
    Route::post('update_Invoices_CP/{id}',[ManageOfficeController::class,'update_Invoices_CP']);
    Route::post('update_Invoices_test/{id}',[ManageOfficeNewController::class,'update_Invoices_test']);
    
    Route::post('confirm_invoice_Agent/{id}',[ManageOfficeNewController::class,'confirm_invoice_Agent']);
    Route::post('invoice_Agent/{id}',[ManageOfficeNewController::class,'invoice_Agent'])->name('invoice_Agent');
    Route::post('pay_invoice_Agent/{id}',[ManageOfficeNewController::class,'pay_invoice_Agent'])->name('pay_invoice_Agent');
    Route::post('recieve_invoice_Agent',[ManageOfficeNewController::class,'recieve_invoice_Agent'])->name('recieve_invoice_Agent');
    
    Route::post('invoice_Agent_B2B',[ManageOfficeNewController::class,'invoice_Agent_B2B'])->name('invoice_Agent_B2B');
    
    // Route::post('payable_ledger',[ManageOfficeNewController::class,'payable_ledger'])->name('payable_ledger');
    // Route::post('receivAble_ledger',[ManageOfficeNewController::class,'receivAble_ledger'])->name('receivAble_ledger');
    // Route::post('cash_ledger',[ManageOfficeNewController::class,'cash_ledger'])->name('cash_ledger');
    
    Route::post('cancellation_policy_invoice',[ManageOfficeController::class,'cancellation_policy_invoice'])->name('cancellation_policy_invoice');
    
    Route::post('get_flights_occupied_seats',[ManageOfficeNewController::class,'get_flights_occupied_seats']);
    
    Route::post('get_flights_selected_supplier',[ManageOfficeNewController::class,'get_flights_selected_supplier']);
    Route::post('get_flights_all_routes',[ManageOfficeNewController::class,'get_flights_all_routes']);
    Route::post('get_flights_selected_route',[ManageOfficeNewController::class,'get_flights_selected_route']);
    
    Route::post('get_all_transfer_destinations',[ManageOfficeNewController::class,'get_all_transfer_destinations']);
    
    Route::post('request_Invoices',[ManageOfficeNewController::class,'request_Invoices']);
    Route::post('request_Invoices_submit',[InvoiceRequestController::class,'request_Invoices_submit']);
    Route::post('view_request_Invoices',[InvoiceRequestController::class,'view_request_Invoices']);
    Route::post('get_invoice_data',[InvoiceRequestController::class,'get_invoice_data']);
    Route::post('request_invoice_confirmed',[InvoiceRequestController::class,'request_invoice_confirmed']);
    
    Route::post('request_invoice_confirmed_submit',[InvoiceRequestController::class,'request_invoice_confirmed_submit']);
    
    Route::post('request_invoice_confirmed_view',[InvoiceRequestController::class,'request_invoice_confirmed_view']);
    Route::post('request_invoice_pay_amount',[InvoiceRequestController::class,'request_invoice_pay_amount']);
    Route::post('request_recieve_invoice',[InvoiceRequestController::class,'request_recieve_invoice']);
    
    Route::post('request_invoice_edit',[InvoiceRequestController::class,'request_invoice_edit']);
    Route::post('request_invoice_edit_submit',[InvoiceRequestController::class,'request_invoice_edit_submit']);
    
    Route::post('get_single_Invoice/{id}',[ManageOfficeNewController::class,'get_single_Invoice']);
    Route::post('add_more_passenger_Invoice',[ManageOfficeNewController::class,'add_more_passenger_Invoice']);
    
    Route::post('get_single_Quotation',[ManageOfficeNewController::class,'get_single_Quotation']);
    Route::post('add_more_passenger_Quotation',[ManageOfficeNewController::class,'add_more_passenger_Quotation']);
    // Ajax
    Route::post('get_rooms_list',[ManageOfficeNewController::class,'get_rooms_list']);
    Route::post('get_hotels_list',[ManageOfficeNewController::class,'get_hotels_list']);
    Route::post('get_hotels_list_Single',[ManageOfficeNewController::class,'get_hotels_list_Single']);
    Route::post('get_rooms_view',[ManageOfficeNewController::class,'get_rooms_view']);
    
    Route::post('submit_invoiceRoomSupplier',[ManageOfficeNewController::class,'submit_invoiceRoomSupplier']);
    Route::post('get_invoiceRoomSupplier_detail',[ManageOfficeNewController::class,'get_invoiceRoomSupplier_detail']);
    
    Route::post('get_hotel_Suppliers',[ManageOfficeNewController::class,'get_hotel_Suppliers']);
    
// End Manage Office Routes

// Rooms Route
Route::post('/hotel_manger/add_room',[RoomControllerApi::class,'showAddRoomFrom']);
Route::post('/hotel_manger/fetch_room_types',[RoomControllerApi::class,'fetch_room_types']);
Route::post('/hotel_manger/add_room_type_sub',[RoomControllerApi::class,'add_room_type_sub']);
Route::post('/hotel_manger/add_room_sub',[RoomControllerApi::class,'addRoom']);
Route::post('/hotel_manger/rooms_list',[RoomControllerApi::class,'index']);
Route::post('/hotel_manger/view_room/{id}',[RoomControllerApi::class,'updateShowForm']);
Route::post('/hotel_manger/update_room/{id}',[RoomControllerApi::class,'update_room']);
Route::post('/hotel_manger/updateRoomPriceAjax',[RoomControllerApi::class,'updateRoomPriceAjax']);
Route::post('/hotel_manger/edit_Room_Types',[RoomControllerApi::class,'edit_Room_Types']);
Route::post('/hotel_manger/update_Room_Types',[RoomControllerApi::class,'update_Room_Types']);
Route::post('/hotel_manger/delete_Room_Types',[RoomControllerApi::class,'delete_Room_Types']);

// Meal
Route::post('custom_Meal_Type_Create',[MealTypeController::class,'custom_Meal_Type_Create']);
Route::post('custom_Meal_Type_Add',[MealTypeController::class,'custom_Meal_Type_Add']);
Route::post('custom_Meal_Type_Update',[MealTypeController::class,'custom_Meal_Type_Update']);
Route::post('custom_Meal_Type_Delete',[MealTypeController::class,'custom_Meal_Type_Delete']);

// Parent Category Room Type
Route::post('/hotel_manger/add_Parent_Category_Room_Type',[RoomControllerApi::class,'add_Parent_Category_Room_Type']);
Route::post('/hotel_manger/edit_Parent_Category_Room_Type',[RoomControllerApi::class,'edit_Parent_Category_Room_Type']);
Route::post('/hotel_manger/update_Parent_Category_Room_Type',[RoomControllerApi::class,'update_Parent_Category_Room_Type']);
Route::post('/hotel_manger/delete_Parent_Category_Room_Type',[RoomControllerApi::class,'delete_Parent_Category_Room_Type']);
// Parent Category Room Type

// Meal Route
Route::post('create_Meal_Type',[RoomControllerApi::class,'create_Meal_Type']);
Route::post('add_Meal_Type',[RoomControllerApi::class,'add_Meal_Type']);
Route::post('edit_Meal_Type',[RoomControllerApi::class,'edit_Meal_Type']);
Route::post('update_Meal_Type',[RoomControllerApi::class,'update_Meal_Type']);
Route::post('delete_Meal_Type',[RoomControllerApi::class,'delete_Meal_Type']);

// Hotel Room Ajax Route
Route::post('hotel_Room',[HotelControllerApi::class,'hotel_Room']);

//Quotations
Route::post('manage_Quotation',[ManageQuotationsControllerApi::class,'manage_Quotation']);
Route::post('add_Manage_Quotation',[ManageQuotationsControllerApi::class,'add_Manage_Quotation']);
Route::post('view_Quotations',[ManageQuotationsControllerApi::class,'view_Quotations']);
Route::post('view_QuotationsID/{id}',[ManageQuotationsControllerApi::class,'view_QuotationsID']);
Route::post('edit_Quotations/{id}',[ManageQuotationsControllerApi::class,'edit_Quotations']);
Route::post('update_Manage_Quotation/{id}',[ManageQuotationsControllerApi::class,'update_Manage_Quotation']);
Route::post('invoice_Quotations/{id}',[ManageQuotationsControllerApi::class,'invoice_Quotations'])->name('invoice_Quotations');

// Hotel Quotation
Route::post('manage_package_Quotation',[ManageQuotationsControllerApi::class,'manage_package_Quotation']);
Route::post('add_manage_Package_Quotation',[ManageQuotationsControllerApi::class,'add_manage_Package_Quotation']);
Route::post('view_manage_Package_Quotation',[ManageQuotationsControllerApi::class,'view_manage_Package_Quotation']);
Route::post('edit_manage_Package_Quotation/{id}',[ManageQuotationsControllerApi::class,'edit_manage_Package_Quotation']);
Route::post('update_manage_Package_Quotation',[ManageQuotationsControllerApi::class,'update_manage_Package_Quotation']);
Route::post('update_manage_Package_Quotation_New',[ManageQuotationsControllerApi::class,'update_manage_Package_Quotation_New']);
Route::post('confirm_manage_Package_Quotation',[ManageQuotationsControllerApi::class,'confirm_manage_Package_Quotation']);
Route::post('confirm_Quotation/{id}',[ManageQuotationsControllerApi::class,'confirm_Quotation']);
Route::post('view_manage_Package_Quotation_Single',[ManageQuotationsControllerApi::class,'view_manage_Package_Quotation_Single']);

// Flights
Route::post('get_flight_name',[ManageQuotationsControllerApi::class,'get_flight_name']);
//Makkah
Route::post('hotel_Makkah_Room',[ManageQuotationsControllerApi::class,'hotel_Makkah_Room']);
Route::post('makkah_Room/{id}',[ManageQuotationsControllerApi::class,'makkah_Room']);
//Madinah
Route::post('hotel_Madinah_Room',[ManageQuotationsControllerApi::class,'hotel_Madinah_Room']);
Route::post('madinah_Room/{id}',[ManageQuotationsControllerApi::class,'madinah_Room']);

//Bookings
Route::post('view_Bookings',[ManageQuotationsControllerApi::class,'view_Bookings']);
Route::post('add_Bookings/{id}',[ManageQuotationsControllerApi::class,'add_Bookings']);

//Room Ajax Route
Route::get('roomID/{id}',[HotelController::class,'roomID']);

// Vehicles
Route::post('add_Vehicles',[TransferVehiclesApiController::class,'add_Vehicles']);
Route::post('search_Vehicles',[TransferVehiclesApiController::class,'search_Vehicles']);
Route::post('view_Vehicles',[TransferVehiclesApiController::class,'view_Vehicles']);
Route::post('view_Vehicles1',[TransferVehiclesApiController::class,'view_Vehicles1']);
Route::post('add_new_vehicle',[TransferVehiclesApiController::class,'add_new_vehicle']);
Route::post('edit_vehicle_details/{id}',[TransferVehiclesApiController::class,'edit_vehicle_details']);
Route::post('delete_vehicle_details/{id}',[TransferVehiclesApiController::class,'delete_vehicle_details']);
Route::post('update_vehicle/{id}',[TransferVehiclesApiController::class,'update_vehicle']);

// Mazaraat
Route::post('viewMazaraat',[TransferVehiclesApiController::class,'viewMazaraat']);
Route::post('addMazaraat',[TransferVehiclesApiController::class,'addMazaraat']);
Route::post('editMazaraat',[TransferVehiclesApiController::class,'editMazaraat']);
Route::post('updateMazaraat',[TransferVehiclesApiController::class,'updateMazaraat']);

// Destination
Route::post('view_Destination',[TransferVehiclesApiController::class,'view_Destination']);
Route::post('delete_destination',[TransferVehiclesApiController::class,'delete_destination']);

Route::post('add_new_destination',[TransferVehiclesApiController::class,'add_new_destination']);
Route::post('edit_destination_details/{id}',[TransferVehiclesApiController::class,'edit_destination_details']);
Route::post('update_destination/{id}',[TransferVehiclesApiController::class,'update_destination']);
Route::post('get_tbo_hotel_details',[TransferVehiclesApiController::class,'get_tbo_hotel_details']);

Route::post('delete_Destination_Details',[TransferVehiclesApiController::class,'delete_Destination_Details']);

Route::post('getPickupPoints',[TransferVehiclesApiController::class,'getPickupPoints']);
Route::post('viewPickupPoint',[TransferVehiclesApiController::class,'viewPickupPoint']);
Route::post('addPickupPoint',[TransferVehiclesApiController::class,'addPickupPoint']);
Route::post('editPickupPoint',[TransferVehiclesApiController::class,'editPickupPoint']);
Route::post('updatePickupPoint',[TransferVehiclesApiController::class,'updatePickupPoint']);
Route::post('deletePickupPoint',[TransferVehiclesApiController::class,'deletePickupPoint']);

// Vehicle Category
Route::post('view_Vehicle_Category',[TransferVehiclesApiController::class,'view_Vehicle_Category'])->name('view_Vehicle_Category');
Route::post('add_new_vehicle_category',[TransferVehiclesApiController::class,'add_new_vehicle_category']);
Route::post('edit_vehicle_category/{id}',[TransferVehiclesApiController::class,'edit_vehicle_category']);
Route::post('update_vehicle_category/{id}',[TransferVehiclesApiController::class,'update_vehicle_category']);
Route::post('delete_vehicle_category/{id}',[TransferVehiclesApiController::class,'delete_vehicle_category']);
Route::post('add_new_vehicle_category_Ajax',[TransferVehiclesApiController::class,'add_new_vehicle_category_Ajax']);

Route::post('transfer_Bookings',[TransferVehiclesApiController::class,'transfer_Bookings']);

/*
|--------------------------------------------------------------------------
| api routes written by jamshaid cheena
|--------------------------------------------------------------------------
|
| 
|
*/

Route::post('package_departure_list','frontend\admin_dashboard\UmrahPackageController@package_departure_list');
Route::post('package_return_list','frontend\admin_dashboard\UmrahPackageController@package_return_list');
Route::post('activities_departure_list','frontend\admin_dashboard\UmrahPackageController@activities_departure_list');
Route::post('activities_return_list','frontend\admin_dashboard\UmrahPackageController@activities_return_list');
Route::post('package_bookings','frontend\admin_dashboard\UmrahPackageController@package_bookings');
Route::post('activities_bookings','frontend\admin_dashboard\UmrahPackageController@activities_bookings');

Route::post('listing_umrah_packages','frontend\admin_dashboard\UmrahPackageController@listing_umrah_packages');

Route::post('get_Cities_Using_Code', [CountryController::class,'get_Cities_Using_Code']);
Route::post('countryCites_apis', [CountryController::class,'countryCites_api']);
Route::post('country_cites_laln/{id}', [CountryController::class,'country_cites_laln']);
Route::post('country_cites_ID/{id}', [CountryController::class,'country_cites_ID']);

Route::post('hotel_Room',[HotelController::class,'hotel_Room_api']);
Route::post('save_hotel_booking',[HotelbookingController::class,'SaveHotelBooking']);
Route::post('save_hotel_booking_details_tbo',[HotelbookingController::class,'save_hotel_booking_details_tbo']);

//Room Ajax Route
Route::post('super_admin/roomID/{id}',[HotelController::class,'roomID_api']);

Route::get('create_umrah_packages','frontend\admin_dashboard\UmrahPackageController@create');
Route::post('submit_umrah_packages_api','frontend\admin_dashboard\UmrahPackageController@submit_umrah_packages_api');
Route::post('view_umrah_packages_api','frontend\admin_dashboard\UmrahPackageController@view_umrah_packages_api');

Route::post('delete_umrah_packages_api/{id}','frontend\admin_dashboard\UmrahPackageController@delete_umrah_packages_api');
Route::post('edit_umrah_packages_api/{id}','frontend\admin_dashboard\UmrahPackageController@edit_umrah_packages_api');
Route::post('submit_edit_umrah_packages_api/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_umrah_packages_api');

Route::post('enable_umrah_packages_api/{id}','frontend\admin_dashboard\UmrahPackageController@enable_umrah_packages_api');
Route::post('disable_umrah_package_api/{id}','frontend\admin_dashboard\UmrahPackageController@disable_umrah_package_api');

// tour route api started by jamshaid cheena

Route::post('staffDetails','frontend\admin_dashboard\CustomerSubscription@staffDetails');
Route::post('addStaffDetails','frontend\admin_dashboard\CustomerSubscription@addStaffDetails');
Route::post('updateStaffDetails','frontend\admin_dashboard\CustomerSubscription@updateStaffDetails');
Route::post('deleteStaffDetails','frontend\admin_dashboard\CustomerSubscription@deleteStaffDetails');

Route::post('account_details','frontend\admin_dashboard\CustomerSubscription@account_details');
Route::post('edit_account_details/{id}','frontend\admin_dashboard\CustomerSubscription@edit_account_details');
Route::post('contact_details','frontend\admin_dashboard\CustomerSubscription@contact_details');
Route::post('submit_contact_details','frontend\admin_dashboard\CustomerSubscription@submit_contact_details');
Route::post('payment_gateways_list','frontend\admin_dashboard\CustomerSubscription@payment_gateways_list');
Route::post('submit_payment_gateways','frontend\admin_dashboard\CustomerSubscription@submit_payment_gateways');
Route::post('edit_payment_gateways','frontend\admin_dashboard\CustomerSubscription@edit_payment_gateways');

 Route::post('save_meta_tags','frontend\admin_dashboard\CustomerSubscription@save_meta_tags');
 Route::post('get_meta_tags','frontend\admin_dashboard\CustomerSubscription@get_meta_tags');
 Route::post('save_pages_meta_info','frontend\admin_dashboard\CustomerSubscription@save_pages_meta_info');
 Route::post('update_pages_meta_info','frontend\admin_dashboard\CustomerSubscription@update_pages_meta_info');
 Route::post('get_all_pages_meta_info','frontend\admin_dashboard\CustomerSubscription@get_all_pages_meta_info');
 Route::post('get_single_page_data','frontend\admin_dashboard\CustomerSubscription@get_single_page_data');
 


Route::post('payment_mode_list','frontend\admin_dashboard\CustomerSubscription@payment_mode_list');
Route::post('submit_payment_mode','frontend\admin_dashboard\CustomerSubscription@submit_payment_mode');
Route::post('edit_payment_mode','frontend\admin_dashboard\CustomerSubscription@edit_payment_mode');
Route::post('3rd_party_commession','frontend\admin_dashboard\CustomerSubscription@third_party_commession');
Route::post('third_party_packages_selection','frontend\admin_dashboard\CustomerSubscription@third_party_packages_selection');
Route::post('third_party_packages_approve','frontend\admin_dashboard\CustomerSubscription@third_party_packages_approve');
Route::post('third_party_commission_payable','frontend\admin_dashboard\CustomerSubscription@third_party_commission_payable');
Route::post('third_party_commission_receivable','frontend\admin_dashboard\CustomerSubscription@third_party_commission_receivable');
Route::post('third_party_payable_ledger','frontend\admin_dashboard\CustomerSubscription@third_party_payable_ledger'); 
Route::post('third_party_receivable_ledger','frontend\admin_dashboard\CustomerSubscription@third_party_receivable_ledger'); 

Route::post('submit_other_provider','frontend\admin_dashboard\CustomerSubscription@submit_other_provider');
Route::post('submit_all_provider','frontend\admin_dashboard\CustomerSubscription@submit_all_provider');
Route::post('delete_provider/{id}','frontend\admin_dashboard\CustomerSubscription@delete_provider');
Route::post('edit_provider/{id}','frontend\admin_dashboard\CustomerSubscription@edit_provider');
Route::post('submit_edit_provider/{id}','frontend\admin_dashboard\CustomerSubscription@submit_edit_provider');


Route::post('submit_categories','frontend\admin_dashboard\UmrahPackageController@submit_categories_api');
Route::post('view_categories','frontend\admin_dashboard\UmrahPackageController@view_categories_api');
Route::post('delete_categories/{id}','frontend\admin_dashboard\UmrahPackageController@delete_categories_api');
Route::post('edit_categories_api/{id}','frontend\admin_dashboard\UmrahPackageController@edit_categories_api');
Route::post('submit_edit_categories_api/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_categories_api_request');
Route::post('delete_categories_api/{id}','frontend\admin_dashboard\UmrahPackageController@delete_categories_api');

Route::post('submit_attributes','frontend\admin_dashboard\UmrahPackageController@submit_attributes_api');
Route::post('view_attributes','frontend\admin_dashboard\UmrahPackageController@view_attributes_api');
Route::post('delete_attributes_api/{id}','frontend\admin_dashboard\UmrahPackageController@delete_attributes_api');
Route::post('submit_edit_attributes/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_attributes_api');

Route::post('edit_attributes_api/{id}','frontend\admin_dashboard\UmrahPackageController@edit_attributes_api');
 
Route::post('get_tour_list','frontend\admin_dashboard\UmrahPackageControllerApi@get_tour_list_api');
Route::post('create_tour','frontend\admin_dashboard\UmrahPackageController@create_tour_api');
Route::post('add_tour','frontend\admin_dashboard\UmrahPackageController@add_tour_api');


Route::post('mange_currency','frontend\admin_dashboard\UmrahPackageController@mange_currency_api');
Route::post('mange_currency_submit','frontend\admin_dashboard\UmrahPackageController@mange_currency_submit_api');
Route::post('edit_Currency','frontend\admin_dashboard\UmrahPackageController@edit_Currency');
Route::post('manage_Currency_Update','frontend\admin_dashboard\UmrahPackageController@manage_Currency_Update');

// Tour
Route::post('create_excursion2','frontend\admin_dashboard\UmrahPackageController_New@create_excursion2');
Route::post('view_tour','frontend\admin_dashboard\UmrahPackageController_New@view_tour')->name('view_tour');
Route::post('submit_tour','frontend\admin_dashboard\UmrahPackageController_New@submit_tour');
Route::post('submit_tour_test','frontend\admin_dashboard\UmrahPackageController_New@submit_tour_test');

Route::post('edit_tour/{id}','frontend\admin_dashboard\UmrahPackageController_New@edit_tour');
Route::post('submit_edit_tour/{id}','frontend\admin_dashboard\UmrahPackageController_New@submit_edit_tour');
Route::post('submit_edit_tour_test/{id}','frontend\admin_dashboard\UmrahPackageController_New@submit_edit_tour_test');
// End Tour

// Enquire Package
Route::post('view_tour_enquire','frontend\admin_dashboard\UmrahPackageController_New@view_tour_enquire')->name('view_tour_enquire');
Route::post('submit_tour_enquire','frontend\admin_dashboard\UmrahPackageController_New@submit_tour_enquire');
Route::post('edit_tour_enquire/{id}','frontend\admin_dashboard\UmrahPackageController@edit_tour_enquire');
Route::post('submit_edit_tour_enquire/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_tour_enquire');

// End Enquire Package

Route::post('edit_tour_api/{id}','frontend\admin_dashboard\UmrahPackageController@edit_tours_api');
Route::post('submit_tours_api/{id}','frontend\admin_dashboard\UmrahPackageController@submit_tours_api');
Route::post('delete_tour','frontend\admin_dashboard\UmrahPackageController@delete_tour_api');
Route::post('enable_tour/{id}','frontend\admin_dashboard\UmrahPackageController@enable_tour_api');
Route::post('disable_tour/{id}','frontend\admin_dashboard\UmrahPackageController@disable_tour_api');

Route::post('save_Package','frontend\admin_dashboard\UmrahPackageController@save_Package');
Route::post('save_Accomodation/{id}','frontend\admin_dashboard\UmrahPackageController@save_Accomodation');

Route::post('booking_allocations/{id}','frontend\admin_dashboard\UmrahPackageController@booking_allocations');

// Activities 
Route::post('submit_Activities_New','frontend\admin_dashboard\UmrahPackageController@submit_Activities_New');
Route::post('edit_activities/{id}','frontend\admin_dashboard\UmrahPackageController@edit_activities');
Route::post('submit_edit_activities/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_activities');

// activities route api started by jamshaid cheena
Route::post('submit_categories_activites','frontend\admin_dashboard\UmrahPackageController@submit_categories_activites_api');
Route::post('view_categories_activites','frontend\admin_dashboard\UmrahPackageController@view_categories_activites_api');

Route::post('delete_categories_activites/{id}','frontend\admin_dashboard\UmrahPackageController@delete_categories_activites_api');
Route::post('edit_categories_activites_api/{id}','frontend\admin_dashboard\UmrahPackageController@edit_categories_activites_api');
Route::post('submit_edit_categories_activites_api/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_categories_activites_api_request');
Route::post('delete_categories_activites_api/{id}','frontend\admin_dashboard\UmrahPackageController@delete_categories_activites_api');

// Hotel Names
Route::post('view_Hotel_Names','frontend\admin_dashboard\UmrahPackageController@view_Hotel_Names');
Route::post('edit_Hotel_Names/{id}','frontend\admin_dashboard\UmrahPackageController@edit_Hotel_Names');
Route::post('submit_edit_Hotel_Names/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_Hotel_Names');
Route::post('delete_Hotel_Names/{id}','frontend\admin_dashboard\UmrahPackageController@delete_Hotel_Names');
// End Hotel Names

// Accounts Route

// Booking Allocations
Route::post('booking_details_single/{id}',[AccountDetailsApiController::class,'booking_details_single']);
Route::post('hotel_Name_Details_Single',[AccountDetailsApiController::class,'hotel_Name_Details_Single']);
Route::post('reservation_no_add',[AccountDetailsApiController::class,'reservation_no_add']);
Route::post('reservation_no_update',[AccountDetailsApiController::class,'reservation_no_update']);
Route::post('reservation_Detail_Single',[AccountDetailsApiController::class,'reservation_Detail_Single']);
Route::post('add_more_passenger_package_booking',[AccountDetailsApiController::class,'add_more_passenger_package_booking']);
// End

Route::post('expenses_IncomeAll',[AccountDetailsApiController::class,'expenses_IncomeAll']);
Route::post('expenses_Income_client_wise_data/{id}',[AccountDetailsApiController::class,'expenses_Income_client_wise_data']);

Route::post('income_statement',[AccountDetailsApiController::class,'income_statement']);
Route::post('expenses_Income',[AccountDetailsApiController::class,'expenses_Income']);
Route::post('out_Standings',[AccountDetailsApiController::class,'out_Standings']);
Route::post('get_out_Standings',[AccountDetailsApiController::class,'get_out_Standings']);
Route::post('supplier_out_Standings',[AccountDetailsApiController::class,'supplier_out_Standings']);
Route::post('supplier_out_Standings_ajax',[AccountDetailsApiController::class,'supplier_out_Standings_ajax']);
Route::post('recieved_Payments',[AccountDetailsApiController::class,'recieved_Payments']);
Route::post('recieved_Payments_approve',[AccountDetailsApiController::class,'recieved_Payments_approve']);
Route::post('update_customer_payment',[AccountDetailsApiController::class,'update_customer_payment']);


Route::post('stats_Tours',[AccountDetailsApiController::class,'stats_Tours']);
Route::post('more_Tour_Details1/{id}',[AccountDetailsApiController::class,'more_Tour_Details1']);

Route::post('cancelled_Tours',[AccountDetailsApiController::class,'cancelled_Tours']);

Route::post('view_total_Amount/{id}',[AccountDetailsApiController::class,'view_total_Amount']);
Route::post('view_recieve_Amount/{id}',[AccountDetailsApiController::class,'view_recieve_Amount']);
Route::post('view_Outstandings/{id}',[AccountDetailsApiController::class,'view_Outstandings']);
Route::post('view_Details_Accomodation/{id}',[AccountDetailsApiController::class,'view_Details_Accomodation']);

Route::post('hotel_detail_ID/{id}',[AccountDetailsApiController::class,'hotel_detail_ID']);
Route::post('flight_detail_ID/{id}',[AccountDetailsApiController::class,'flight_detail_ID']);
Route::post('transportation_detail_ID/{id}',[AccountDetailsApiController::class,'transportation_detail_ID']);
Route::post('visa_detail_ID/{id}',[AccountDetailsApiController::class,'visa_detail_ID']);

// Visa
Route::post('visa_Pay/{id}','frontend\admin_dashboard\AccountDetailsApiController@visa_Pay');
Route::post('sumbit_Visa_Pay','frontend\admin_dashboard\AccountDetailsApiController@sumbit_Visa_Pay');
Route::post('view_visa_total_Amount/{id}',[AccountDetailsApiController::class,'view_visa_total_Amount']);

// Transportation
Route::post('transportation_Pay/{id}','frontend\admin_dashboard\AccountDetailsApiController@transportation_Pay');
Route::post('sumbit_Transportation_Pay','frontend\admin_dashboard\AccountDetailsApiController@sumbit_Transportation_Pay');
Route::post('view_transportation_total_Amount/{id}',[AccountDetailsApiController::class,'view_transportation_total_Amount']);

// Flight
Route::post('sumbit_Flight_Pay','frontend\admin_dashboard\AccountDetailsApiController@sumbit_Flight_Pay');

// Accomodation
Route::post('acc_Pay/{selected_city}/{t_Id}','frontend\admin_dashboard\AccountDetailsApiController@acc_Pay');
Route::post('sumbit_Accomodation_Pay','frontend\admin_dashboard\AccountDetailsApiController@sumbit_Accomodation_Pay');


// End Accounts Route

// Locations
    // Pickup Locations
        Route::post('view_pickup_locations','frontend\admin_dashboard\UmrahPackageController@view_pickup_locations');
        Route::post('edit_pickup_locations/{id}','frontend\admin_dashboard\UmrahPackageController@edit_pickup_locations');
        Route::post('submit_edit_pickup_locations/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_pickup_locations');
        Route::post('delete_pickup_locations/{id}','frontend\admin_dashboard\UmrahPackageController@delete_pickup_locations');
    // Dropof Locations
        Route::post('view_dropof_locations','frontend\admin_dashboard\UmrahPackageController@view_dropof_locations');
        Route::post('edit_dropof_locations/{id}','frontend\admin_dashboard\UmrahPackageController@edit_dropof_locations');
        Route::post('submit_edit_dropof_locations/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_dropof_locations');
        Route::post('delete_dropof_locations/{id}','frontend\admin_dashboard\UmrahPackageController@delete_dropof_locations');
// End Locations

Route::post('submit_attributes_activites','frontend\admin_dashboard\UmrahPackageController@submit_attributes_activities_api');
Route::post('view_attributes_activites','frontend\admin_dashboard\UmrahPackageController@view_attributes_activites_api');
Route::post('delete_attributes_activites_api/{id}','frontend\admin_dashboard\UmrahPackageController@delete_attributes_activites_api');
Route::post('submit_edit_attributes_activites/{id}','frontend\admin_dashboard\UmrahPackageController@submit_edit_attributes_activites_api');

Route::post('edit_attributes_activites_api/{id}','frontend\admin_dashboard\UmrahPackageController@edit_attributes_activites_api');
 
Route::post('get_activites_list','frontend\admin_dashboard\UmrahPackageController@get_activities_list_api');
Route::post('get_activites_lists','ActivityController@get_activities_list');
Route::post('submit_activity_cities','ActivityController@submit_activity_cities');

Route::post('get_activites_lists_wi_limit','ActivityController@get_activities_list_wi_limit');
Route::post('get_activites_lists_wi_cities','ActivityController@get_activites_lists_wi_cities');

Route::post('create_activites','frontend\admin_dashboard\UmrahPackageController@create_activities_api');
Route::post('add_activities','frontend\admin_dashboard\UmrahPackageController@add_activities_api');
Route::post('edit_activities_api/{id}','frontend\admin_dashboard\UmrahPackageController@edit_activities_api');
Route::post('submit_activities_api/{id}','frontend\admin_dashboard\UmrahPackageController@submit_activities_api');
Route::post('delete_activities/{id}','frontend\admin_dashboard\UmrahPackageController@delete_activities_api');
Route::post('enable_activities/{id}','frontend\admin_dashboard\UmrahPackageController@enable_activities_api');
Route::post('disable_activities/{id}','frontend\admin_dashboard\UmrahPackageController@disable_activities_api');

// activities route api ended by jamshaid cheena


Route::post('super_admin','frontend\admin_dashboard\CustomerSubscription@access_url');
Route::post('super_admin/login','frontend\admin_dashboard\CustomerSubscription@login');
Route::post('super_admin/login-Support-Synchtravel','frontend\admin_dashboard\CustomerSubscription@login_SS');
Route::get('super_admin/userData','frontend\admin_dashboard\CustomerSubscription@index');

// Book Activity
Route::post('book_activity','ActivityController@book_activity')->name('book_activity');
Route::post('get_tour_for_cart_ActivityAdmin','ActivityController@get_tour_for_cart_ActivityAdmin')->name('get_tour_for_cart_ActivityAdmin');
Route::post('submit_booking_ActivityAdmin','ActivityController@submit_booking_ActivityAdmin')->name('submit_booking_ActivityAdmin');
// Book Activity

// 15/06/2022

Route::post('ticket_generate','frontend\TicketControllerApi@ticket_generate');
Route::post('ticket/generate/submit','frontend\TicketControllerApi@ticket_generate_submit');
Route::post('ticket_view','frontend\user_dashboard\UserController_Api@view_ticket');

Route::post('updated_status_ticket_client','frontend\user_dashboard\UserController_Api@updated_status_ticket_client');


Route::post('conversation/admin','frontend\user_dashboard\UserController_Api@conversation_admin');
Route::post('conversation/admin/submit','frontend\user_dashboard\UserController_Api@conversation_admin_submit');

Route::post('conversation/downloadFile','frontend\user_dashboard\UserController_Api@downloadFile');
Route::post('conversationclient/downloadFile','frontend\user_dashboard\UserController_Api@downloadFileclient');


Route::post('ticket_view/submit/{id}','frontend\user_dashboard\UserController_Api@admin_ticket');

//employees routes
Route::post('/employees',[App\Http\Controllers\frontend\EmployeeController_Api::class,'employees']);
Route::post('/employees/add',[App\Http\Controllers\frontend\EmployeeController_Api::class,'create']);
Route::post('/employees/submit',[App\Http\Controllers\frontend\EmployeeController_Api::class,'store']);
Route::post('/employees_edit/{id}',[App\Http\Controllers\frontend\EmployeeController_Api::class,'edit']);
Route::post('/employees_update/{id}',[App\Http\Controllers\frontend\EmployeeController_Api::class,'update']);
Route::post('/employees_delete/{id}',[App\Http\Controllers\frontend\EmployeeController_Api::class,'delete']);
Route::get('/view_location/{id}',[App\Http\Controllers\frontend\EmployeeController_Api::class,'view_location']);
Route::get('/view_task_location/{id}',[App\Http\Controllers\frontend\EmployeeController_Api::class,'view_task_location']);
Route::post('/employee_roles',[App\Http\Controllers\frontend\EmployeeController_Api::class,'employee_roles']);
Route::post('/add_roles',[App\Http\Controllers\frontend\EmployeeController_Api::class,'add_roles']);
Route::post('/edit_roles',[App\Http\Controllers\frontend\EmployeeController_Api::class,'edit_roles']);
Route::post('/del_roles/{id}',[App\Http\Controllers\frontend\EmployeeController_Api::class,'del_roles']);
//employees routes


//attendence routes
Route::post('/attendance',[App\Http\Controllers\frontend\AttendenceController_Api::class,'attendence']);
Route::post('/attendance/add',[App\Http\Controllers\frontend\AttendenceController_Api::class,'create']);
Route::post('/attendance/submit',[App\Http\Controllers\frontend\AttendenceController_Api::class,'store']);
Route::post('/attendance_edit/{id}',[App\Http\Controllers\frontend\AttendenceController_Api::class,'edit']);
Route::post('/attendance_update/{id}',[App\Http\Controllers\frontend\AttendenceController_Api::class,'update']);
Route::post('/attendance_delete/{id}',[App\Http\Controllers\frontend\AttendenceController_Api::class,'delete']);
Route::post('/appoint/{id}',[App\Http\Controllers\frontend\AttendenceController_Api::class,'view_task'])->name('task.appoint1');
Route::post('/assign_mandob/{id}',[App\Http\Controllers\frontend\AttendenceController_Api::class,'assign_mandob']);

Route::post('employees_task',[App\Http\Controllers\frontend\AttendenceController_Api::class,'employees_task']);
Route::post('submit_task',[App\Http\Controllers\frontend\AttendenceController_Api::class,'submit_task']);
Route::post('employees_task_edit/{id}',[App\Http\Controllers\frontend\AttendenceController_Api::class,'employees_task_edit']);
Route::post('submit_edit_task/{id}',[App\Http\Controllers\frontend\AttendenceController_Api::class,'submit_edit_task']);
Route::post('employees_task_delete/{id}',[App\Http\Controllers\frontend\AttendenceController_Api::class,'employees_task_delete']);





Route::post('/leave_employees',[App\Http\Controllers\LeaveController_Api::class,'index']);
Route::post('/leave_status/{status}/{id}',[App\Http\Controllers\LeaveController_Api::class,'status']);
Route::post('/leaves/submit',[App\Http\Controllers\LeaveController_Api::class,'store']);



Route::post('/approve/{id}', [App\Http\Controllers\LeaveController_Api::class,'approve'])->name('admin.approve1');
Route::post('/decline/{id}', [App\Http\Controllers\LeaveController_Api::class,'decline'])->name('admin.decline1');

//settings route 
Route::post('/settings','frontend\user_dashboard\UserController_Api@settings');
Route::post('change-password', 'frontend\LoginController_Api@change_password');
Route::post('agent_change_password', 'frontend\LoginController_Api@agent_change_password');

Route::post('send_email_to_agents','frontend\user_dashboard\UserController_Api@send_email_to_agents');
Route::post('/send_mail','frontend\user_dashboard\UserController_Api@send_mail');


Route::post('/manage_user_roles','frontend\user_dashboard\UserController_Api@manage_user_roles');
Route::post('/add_user_permission','frontend\user_dashboard\UserController_Api@add_user_permission');
Route::post('/edit_user_permission','frontend\user_dashboard\UserController_Api@edit_user_permission');
Route::post('update_user_password', 'frontend\user_dashboard\UserController_Api@update_user_password');
Route::post('mange_user_role_delete/{id}','frontend\user_dashboard\UserController_Api@mange_user_role_delete');
Route::post('activate_user/{id}','frontend\user_dashboard\UserController_Api@super_admin_activate_user');
Route::post('inactivate_user/{id}','frontend\user_dashboard\UserController_Api@super_admin_inactivate_user');



Route::post('create_offers','frontend\user_dashboard\UserController_Api@create_offers');
Route::post('submit_offers','frontend\user_dashboard\UserController_Api@submit_offers');
Route::post('edit_offers/{id}','frontend\user_dashboard\UserController_Api@edit_offers');
Route::post('submit_edit_offers/{id}','frontend\user_dashboard\UserController_Api@submit_edit_offers');
Route::post('delete_offers/{id}','frontend\user_dashboard\UserController_Api@delete_offers');
Route::post('view_offers','frontend\user_dashboard\UserController_Api@view_offers');

// 15/06/2022


/*
|--------------------------------------------------------------------------
|api routes written by jamshaid cheena
|--------------------------------------------------------------------------
|
| 
|
*/

/*
|--------------------------------------------------------------------------
|api routes written by usama asghar
|--------------------------------------------------------------------------
|
| 
|
*/






Route::post('super_admin/update_web_content','frontend\admin_dashboard\CustomerSubscription@update_web_content');
Route::post('super_admin/view_web_content','frontend\admin_dashboard\CustomerSubscription@view_web_content');
Route::post('super_admin/url_meta_tag_info','frontend\admin_dashboard\CustomerSubscription@get_url_meta_tag_info');
Route::post('super_admin/update_web_cont','frontend\admin_dashboard\CustomerSubscription@update_web_cont');
Route::post('super_admin/slider_Images','frontend\admin_dashboard\CustomerSubscription@slider_Images');
Route::post('super_admin/fetch_top_categories','frontend\admin_dashboard\CustomerSubscription@fetch_top_categories');
Route::post('super_admin/fetch_all_categories','frontend\admin_dashboard\CustomerSubscription@fetch_all_categories');
Route::post('super_admin/fetch_all_categories_wi_packages','frontend\admin_dashboard\CustomerSubscription@fetch_all_categories_wi_packages');

Route::post('super_admin/fetch_all_attributes','frontend\admin_dashboard\CustomerSubscription@fetch_all_attributes');
Route::post('super_admin/filter_tour','frontend\admin_dashboard\CustomerSubscription@filter_tour');
Route::post('filter_tour','frontend\admin_dashboard\CustomerSubscription@filter_tour');
Route::post('super_admin/filter_activities','frontend\admin_dashboard\CustomerSubscription@filter_activities');
Route::post('cleint_users_save','frontend\admin_dashboard\CustomerSubscription@cleint_users_save');
Route::post('customer_login_sub','frontend\admin_dashboard\CustomerSubscription@customer_login_sub');
Route::post('customer_login_sub_otp','frontend\admin_dashboard\CustomerSubscription@customer_login_sub_otp');
Route::post('save_contact_us','frontend\admin_dashboard\CustomerSubscription@insert_contact_us');


Route::post('super_admin/fetch_category_tour_wi_limit','frontend\admin_dashboard\CustomerSubscription@fetch_category_tour_wi_limit');



Route::post('super_admin/update_slider_content','frontend\admin_dashboard\CustomerSubscription@slider_Images_update');



    Route::post('get_all_tores_wi_token','frontend\admin_dashboard\UmrahPackageController@get_all_tour_list_api_with_token');
    Route::post('all_packages_categories','frontend\admin_dashboard\UmrahPackageController@all_packages_categories');
    
    Route::post('get_category_wi_id','frontend\admin_dashboard\UmrahPackageController@get_category_wi_id');
    Route::post('get_facilities_names','frontend\admin_dashboard\UmrahPackageController@get_facilities_names');

    Route::post('get_tour_itenery','frontend\admin_dashboard\bookingController@get_tour_iternery'); 
    Route::post('add_special_discount','frontend\admin_dashboard\bookingController@add_special_discount'); 
    
    
    Route::post('customer_register_submit','frontend\admin_dashboard\bookingController@customer_register_submit'); 
    
    Route::post('get_tour_iteneryI','frontend\admin_dashboard\bookingController@get_tour_iteneryI');
    Route::post('get_tour_iteneryQ','frontend\admin_dashboard\bookingController@get_tour_iteneryQ');
    Route::post('delete_booking','frontend\admin_dashboard\bookingController@delete_booking');
    
    Route::post('delete_booking_Activity','frontend\admin_dashboard\bookingController@delete_booking_Activity');
    
    Route::post('catigories_all','frontend\admin_dashboard\UmrahPackageController@catigories_all');
    
    Route::post('get_tour_wi_token','frontend\admin_dashboard\UmrahPackageController@get_tour_list_api_with_token');
    Route::post('get_tour_for_carsole','frontend\admin_dashboard\UmrahPackageController@get_tour_for_carsole');
    Route::post('get_activity_for_carsole','frontend\admin_dashboard\UmrahPackageController@get_activity_for_carsole');
    Route::post('get_tour_details','frontend\admin_dashboard\UmrahPackageController@get_tour_details');
    Route::post('get_enquire_tour_details','frontend\admin_dashboard\UmrahPackageController@get_enquire_tour_details');
    
    Route::post('fetch_payment_data1','frontend\admin_dashboard\UmrahPackageController@fetch_payment_data1');
    Route::post('get_visa_type','frontend\admin_dashboard\BookingPackageController@get_visa_type');
    Route::post('save_booking','frontend\admin_dashboard\bookingController@save_booking');
    Route::post('save_booking_react','frontend\admin_dashboard\BookingControllerReact@save_booking');
    Route::post('update_booking','frontend\admin_dashboard\bookingController@update_booking');
    Route::post('save_booking_combine','frontend\admin_dashboard\bookingController@save_booking_combine');
    Route::post('save_booking1','frontend\admin_dashboard\bookingController@save_booking1');
    Route::post('agents_stats','frontend\admin_dashboard\bookingController@agents_stats');
    Route::post('agents_stats_details','frontend\admin_dashboard\bookingController@agents_stats_details');
    Route::post('customer_booking_details','frontend\admin_dashboard\bookingController@customer_booking_details');
    Route::post('agent_booking_details','frontend\admin_dashboard\bookingController@agent_booking_details');
    Route::post('customer_profile_bookings','frontend\admin_dashboard\bookingController@customer_profile_bookings');
    
    Route::post('agent_Prices_Type','frontend\admin_dashboard\bookingController@agent_Prices_Type');
    
    Route::post('agents_stats_details_new','frontend\admin_dashboard\bookingController@agents_stats_details_new');
    Route::post('supplier_stats_details','frontend\admin_dashboard\bookingController@supplier_stats_details');
    
    Route::post('customer_Prices_Type','frontend\admin_dashboard\bookingController@customer_Prices_Type');
    
    Route::post('save_booking_payment','frontend\admin_dashboard\bookingController@save_booking_payment');
    Route::post('view_customer_booking','frontend\admin_dashboard\CustomerSubscription@view_customer_booking');
    Route::post('customer_hotel_bookings','frontend\admin_dashboard\CustomerSubscription@customer_hotel_bookings');
    Route::post('customer_transfers_bookings','frontend\admin_dashboard\CustomerSubscription@customer_transfers_bookings');
    
    Route::post('view_customer_hotel_booking','frontend\admin_dashboard\CustomerSubscription@view_customer_hotel_booking');
    Route::post('view_customer_booking_combine','frontend\admin_dashboard\CustomerSubscription@view_customer_booking_combine');
    
  // Usama 6-15-2022
    Route::post('get_tour_for_cart','frontend\admin_dashboard\UmrahPackageController@get_tour_for_cart');
    Route::post('get_tour_payment_mode','frontend\admin_dashboard\UmrahPackageController@get_tour_payment_mode');
    Route::post('get_customer_booking','frontend\admin_dashboard\UmrahPackageController@view_booking_with_token');
    Route::post('get_agent_upaid_all_inv','frontend\admin_dashboard\bookingController@get_agent_upaid_all_inv');
    Route::post('adjust_over_pay','frontend\admin_dashboard\bookingController@adjust_over_pay');
    Route::post('manage_wallent_balance','frontend\admin_dashboard\bookingController@manage_wallent_balance');
    
    Route::post('invoice_data','frontend\admin_dashboard\bookingController@invoice_data');
    Route::post('invoice_data_react','frontend\admin_dashboard\BookingControllerReact@invoice_data');
    Route::post('invoice_dataI','frontend\admin_dashboard\bookingController@invoice_dataI');
    Route::post('invoice_dataQ','frontend\admin_dashboard\bookingController@invoice_dataQ');
    Route::post('tour_revenue','frontend\admin_dashboard\bookingController@tour_revenue');
    Route::post('invoice_combine','frontend\admin_dashboard\bookingController@invoice_combine');
    Route::post('invoice_package_data','frontend\admin_dashboard\bookingController@invoice_package_data');
    Route::post('invoice_package_data_react','frontend\admin_dashboard\BookingControllerReact@invoice_package_data');
    
    Route::post('invoice_package_data_atol','frontend\admin_dashboard\bookingController@invoice_package_data_atol');
    Route::post('get_tour_iternery_invoice_package_data','frontend\admin_dashboard\bookingController@get_tour_iternery_invoice_package_data');
    
    Route::post('/countries', [CountryController::class,'allCountries']);
    Route::post('/countries_flag', [CountryController::class,'countries_flag']);
    
// Route::post('/search_pakages','frontend\admin_dashboard\UmrahPackageController@search_pakages'); 
// Route::post('/map_data','frontend\admin_dashboard\UmrahPackageController@map_data'); 
// Route::post('/search_activities','frontend\admin_dashboard\UmrahPackageController@search_activities'); 
// Route::post('/country_activities','frontend\admin_dashboard\UmrahPackageController@get_country_activities'); 
// Route::post('/all_activities','frontend\admin_dashboard\UmrahPackageController@get_all_activities'); 

Route::post('/getPackagesCountries','frontend\admin_dashboard\UmrahPackageController@getPackagesCountries');
Route::post('/search_pakages','frontend\admin_dashboard\UmrahPackageController@search_pakages'); 
Route::post('/map_data','frontend\admin_dashboard\UmrahPackageController@map_data'); 
Route::post('/search_activities','frontend\admin_dashboard\UmrahPackageController@search_activities'); 
Route::post('/activitySearchajax','frontend\admin_dashboard\AjaxActivityController@activitySearchajax');
Route::post('/get_activity_detail','frontend\admin_dashboard\AjaxActivityController@get_activity_detail');
Route::post('/country_activities','frontend\admin_dashboard\UmrahPackageController@get_country_activities'); 
Route::post('/all_activities','frontend\admin_dashboard\UmrahPackageController@get_all_activities'); 

    
    
Route::post('book_package','frontend\admin_dashboard\BookingPackageController_Api@book_package');
Route::post('get_booking_package/{id}','frontend\admin_dashboard\BookingPackageController_Api@get_booking_package');
Route::post('get_booking_package_tour/{title}','frontend\admin_dashboard\BookingPackageController_Api@get_booking_package_tour');
Route::post('submit_book_package','frontend\admin_dashboard\BookingPackageController_Api@submit_book_package');
Route::post('get_cites_book/{country_id}', 'frontend\admin_dashboard\BookingPackageController_Api@get_cites_book');
/*
|--------------------------------------------------------------------------
|api routes written by usama asghar
|--------------------------------------------------------------------------
|
| 
|
*/



Route::post('/search_hotels','HotelMangers\HotelBookingControllerApi@search_hotels'); 
Route::post('/hotels_details_ajax','HotelMangers\HotelBookingControllerApi@hotels_details_ajax'); 
Route::post('/view_hotel_details','HotelMangers\HotelBookingControllerApi@view_hotel_details');
Route::post('/hotels_checkout','HotelMangers\HotelBookingControllerApi@hotels_checkout'); 
Route::post('/hotels_checkout_submit','HotelMangers\HotelBookingControllerApi@hotels_checkout_submit'); 
Route::post('hotels_checkout_submit_admin','HotelMangers\HotelBookingControllerNewApi@hotels_checkout_submit_admin'); 
Route::post('get_hotels_booking','HotelMangers\HotelBookingControllerApi@get_hotels_booking');
Route::post('booking_refundable_update','HotelMangers\HotelBookingControllerApi@booking_refundable_update');
Route::post('/hotel_invoice','HotelMangers\HotelBookingControllerApi@hotel_invoice'); 
Route::post('payment_cp_update','HotelMangers\HotelBookingControllerApi@payment_cp_update'); 
Route::post('/hotel_booking_cancel','HotelMangers\HotelBookingControllerApi@hotel_booking_cancel'); 
Route::post('/proccess_non_refundable_booking','HotelMangers\HotelBookingControllerApi@proccess_non_refundable_booking'); 
Route::post('/get_hotel_facilities','HotelMangers\HotelBookingControllerApi@get_hotel_facilities'); 

// Admin Panel Routes
Route::post('/custom_hotel_markup','frontend\user_dashboard\UserController_Api@custom_hotel_markup'); 
Route::post('/custom_hotel_markup_submit','frontend\user_dashboard\UserController_Api@custom_hotel_markup_submit'); 
Route::post('/become_provider','frontend\user_dashboard\UserController_Api@become_provider'); 
Route::post('/become_provider_hotel_markup_submit','frontend\user_dashboard\UserController_Api@become_provider_hotel_markup_submit'); 
Route::post('/on_request_booking','frontend\user_dashboard\UserController_Api@on_request_booking'); 
Route::post('custom_hotel_revenue','frontend\user_dashboard\UserController_Api@custom_hotel_revenue'); 
Route::post('/makePayment_on_request','HotelMangers\HotelBookingControllerApi@makePayment_on_request'); 
Route::post('/confrim_on_request','HotelMangers\HotelBookingControllerApi@confrim_on_request'); 
Route::post('confrim_on_request_AR','HotelMangers\HotelBookingControllerApi@confrim_on_request_AR'); 
Route::post('/in_process_on_request','HotelMangers\HotelBookingControllerApi@in_process_on_request');
Route::post('/reject_on_request','HotelMangers\HotelBookingControllerApi@reject_on_request');
Route::post('reject_on_request_AR','HotelMangers\HotelBookingControllerApi@reject_on_request_AR');
Route::post('/on_request_checkout_submit','HotelMangers\HotelBookingControllerApi@on_request_checkout_submit'); 
Route::post('/provider_payments','HotelMangers\HotelBookingControllerApi@provider_payments'); 
Route::post('/provider_payments_requests','HotelMangers\HotelBookingControllerApi@provider_payments_requests'); 


// Test Hotel Api Routes
Route::post('/test_search_hotels','HotelMangers\TestHotelBookingControllerApi@search_hotels'); 
Route::post('/test_hotels_details_ajax','HotelMangers\TestHotelBookingControllerApi@hotels_details_ajax'); 
Route::post('/test_view_hotel_details','HotelMangers\TestHotelBookingControllerApi@view_hotel_details'); 
Route::post('/test_hotels_checkout','HotelMangers\TestHotelBookingControllerApi@hotels_checkout'); 
Route::post('/test_hotels_checkout_submit','HotelMangers\TestHotelBookingControllerApi@hotels_checkout_submit'); 
Route::post('test_hotels_checkout_submit_admin','HotelMangers\TestHotelBookingControllerApi@hotels_checkout_submit_admin'); 
Route::post('test_get_hotels_booking','HotelMangers\TestHotelBookingControllerApi@get_hotels_booking');
Route::post('test_booking_refundable_update','HotelMangers\TestHotelBookingControllerApi@booking_refundable_update');
Route::post('/test_hotel_invoice','HotelMangers\TestHotelBookingControllerApi@hotel_invoice'); 
Route::post('/test_hotel_booking_cancel','HotelMangers\TestHotelBookingControllerApi@hotel_booking_cancel'); 
Route::post('/test_proccess_non_refundable_booking','HotelMangers\TestHotelBookingControllerApi@proccess_non_refundable_booking'); 
Route::post('/test_get_hotel_facilities','HotelMangers\TestHotelBookingControllerApi@get_hotel_facilities');

// Visa Booking Routes
Route::post('search_visa_list','frontend\admin_dashboard\visaSupplierController@search_visa_list');
Route::post('visa_checkout_submit','frontend\admin_dashboard\visaSupplierController@visa_checkout_submit');
Route::post('visa_booking_invoice','frontend\admin_dashboard\visaSupplierController@visa_booking_invoice');
Route::post('visa_confirmed_checkout_submit','frontend\admin_dashboard\visaSupplierController@visa_confirmed_checkout_submit');




Route::post('search_visa_list_combine','frontend\admin_dashboard\visaSupplierController@search_visa_list_combine');
Route::post('visa_booking_list','frontend\admin_dashboard\visaSupplierController@visa_booking_list');
Route::post('confrim_visa_booking','frontend\admin_dashboard\visaSupplierController@confrim_visa_booking');
Route::post('reject_visa_booking','frontend\admin_dashboard\visaSupplierController@reject_visa_booking');


// Hotels New Method Booking
Route::post('/search_hotels_new','HotelMangers\HotelBookingControllerNewApi@search_hotels'); 
Route::post('/hotels_checkout_new','HotelMangers\HotelBookingControllerNewApi@hotels_checkout'); 
Route::post('/hotels_checkout_submit_new','HotelMangers\HotelBookingControllerNewApi@hotels_checkout_submit'); 
Route::post('visa_checkout_save','HotelMangers\HotelBookingControllerNewApi@visa_checkout_save'); 

/*
|--------------------------------------------------------------------------
| React API Routes started
|--------------------------------------------------------------------------
|
*/

Route::post('search/hotels/new','HotelMangers\HotelBookingReactController@search_hotels');
Route::get('countries/fetch','CountryController@allcountriesfetch_new');
Route::post('hotels/mata','HotelMangers\HotelBookingReactController@hotels_mata_apis');
Route::post('hotels/details','HotelMangers\HotelBookingReactController@view_hotel_details');
Route::post('hotels/checkavailability','HotelMangers\HotelBookingReactController@hotels_checkout');
Route::post('hotels/reservation','HotelMangers\HotelBookingReactController@hotels_checkout_submit');
Route::post('hotels/view/reservation','HotelMangers\HotelBookingReactController@view_reservation');

Route::post('latest_packages','HotelMangers\HotelBookingReactController@latest_packages');
Route::post('hotel/reservation/cancell/new','HotelMangers\HotelBookingReactController@hotel_reservation_cancell_new');

Route::post('search/hotels/new_Live','HotelMangers\HotelBookingReactController_Live@search_hotels');
Route::post('search_hotels_Travelenda','HotelMangers\HotelBookingReactController_Live@search_hotels_Travelenda');
Route::post('search_hotels_CUSTOM_HOTELS','HotelMangers\HotelBookingReactController_Live@search_hotels_CUSTOM_HOTELS');
Route::post('search_hotels_SunHotel','HotelMangers\HotelBookingReactController_Live@search_hotels_SunHotel');
Route::post('search_hotels_Stuba','HotelMangers\HotelBookingReactController_Live@search_hotels_Stuba');
Route::post('custom_Search_Hotels','HotelMangers\HotelBookingReactController_Live@custom_Search_Hotels');
Route::get('countries/fetch_Live','CountryController@allcountriesfetch_new_Live');
Route::post('hotels/mata_Live','HotelMangers\HotelBookingReactController_Live@hotels_mata_apis');
Route::post('hotels/details_Live','HotelMangers\HotelBookingReactController_Live@view_hotel_details');
Route::post('hotels/room_Rates_Live','HotelMangers\HotelBookingReactController_Live@room_Rates_Calender');
Route::post('hotels/checkavailability_Live','HotelMangers\HotelBookingReactController_Live@hotels_checkout');
Route::post('all_Hotel_Cancellation_Policy','HotelMangers\HotelBookingReactController_Live@all_Hotel_Cancellation_Policy');
Route::post('hotels/reservation_Live','HotelMangers\HotelBookingReactController_Live@hotels_checkout_submit');
Route::post('hotels/view/reservation_Live','HotelMangers\HotelBookingReactController_Live@view_reservation');
Route::post('get_Meal_Types_Custom_Hotel','HotelMangers\HotelBookingReactController_Live@get_Meal_Types_Custom_Hotel');
Route::post('hotels/payment_Update','HotelMangers\HotelBookingReactController_Live@payment_Update');
Route::get('hotels/MailSend_Static','HotelMangers\HotelBookingReactController_Live@MailSend_Static');
Route::post('hotels/get_Otp_For_Bookings','HotelMangers\HotelBookingReactController_Live@get_Otp_For_Bookings');
Route::post('hotels/check_Otp_For_Bookings','HotelMangers\HotelBookingReactController_Live@check_Otp_For_Bookings');

Route::post('latest_packages_Live','HotelMangers\HotelBookingReactController_Live@latest_packages');
Route::post('hotel/reservation/cancell/new_Live','HotelMangers\HotelBookingReactController_Live@hotel_reservation_cancell_new');

Route::post('hotels_Make_Payemnt','HotelMangers\HotelBookingReactController_Live@hotels_Make_Payemnt');
// Route::post('make_Cancel_Request','HotelMangers\HotelBookingReactController_Live@make_Cancel_Request');
Route::post('B2B_Payment_List','HotelMangers\HotelBookingReactController_Live@B2B_Payment_List');
Route::post('bank_Transfer_Cancel','HotelMangers\HotelBookingReactController_Live@bank_Transfer_Cancel');

/*
|--------------------------------------------------------------------------
| React API Routes endded
|--------------------------------------------------------------------------
|
*/

Route::post('search_activities_react','ActivityReactController@search_activities'); 
Route::post('cites_suggestions','ActivityReactController@cites_suggestions'); 
Route::post('activity-details-react','ActivityReactController@activity_details'); 
Route::post('book_activity','ActivityReactController@book_activity'); 

