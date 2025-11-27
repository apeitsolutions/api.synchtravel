<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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



Route::get("removecache",function (){
\Illuminate\Support\Facades\Artisan::call('route:cache');
\Illuminate\Support\Facades\Artisan::call('route:clear');
\Illuminate\Support\Facades\Artisan::call('config:cache');
\Illuminate\Support\Facades\Artisan::call('config:clear');
\Illuminate\Support\Facades\Artisan::call('optimize');
});

Route::get('b2b.voucher/{id}', 'frontend\user_dashboard\BookingController@voucher');
Route::get('b2b.invoice/{id}', 'frontend\user_dashboard\BookingController@invoice');

Route::get('voucher/{id}', 'frontend\user_dashboard\B2CBookingController@voucher');
Route::get('invoice/{id}', 'frontend\user_dashboard\B2CBookingController@invoice');
/*
|--------------------------------------------------------------------------
| written routes by jaMshAid ChEeNa
|--------------------------------------------------------------------------
*/

Route::group(['middleware'=>'admin_middleware'],function(){
    
    Route::get('/','frontend\user_dashboard\UserController@index');
  
});  

Route::group(['prefix'=>'super_admin','middleware'=>'admin_middleware'],function(){


Route::get('/','frontend\user_dashboard\UserController@index');
Route::get('/user_profile','frontend\user_dashboard\UserController@user_profile');
Route::get('/bookings','frontend\user_dashboard\UserController@bookings');
Route::get('/b2cbookings','frontend\user_dashboard\UserController@b2cbookings');
Route::get('/b2bbookings','frontend\user_dashboard\UserController@b2bbookings');
Route::get('/settings','frontend\user_dashboard\UserController@settings');
Route::get('/logout','frontend\user_dashboard\UserController@logout');

Route::get('/b2bagents','frontend\user_dashboard\UserController@b2bagents');

Route::get('/b2cusers','frontend\user_dashboard\UserController@b2cusers');

Route::get('/b2b_hotel_makkah','frontend\user_dashboard\UserController@hotel_makkah');
Route::get('/b2b_hotel_madina','frontend\user_dashboard\UserController@hotel_madina');
Route::get('/b2b_transfer','frontend\user_dashboard\UserController@transfer_b2b');

Route::get('/b2c_hotel_makkah','frontend\user_dashboard\UserController@hotel_makkah_b2c');
Route::get('/b2c_hotel_madina','frontend\user_dashboard\UserController@hotel_madina_b2c');
Route::get('/b2c_transfer','frontend\user_dashboard\UserController@transfer_b2c');
Route::get('/b2c_ground_services','frontend\user_dashboard\UserController@ground_services_b2c');

Route::get('/b2bagents_verified','frontend\user_dashboard\UserController@b2bagents_verified');
Route::get('/b2btop_agents','frontend\user_dashboard\UserController@b2btop_agents');
Route::get('/b2bagents_unverified','frontend\user_dashboard\UserController@b2bagents_unverified');

Route::get('/hudx_makkah','frontend\user_dashboard\UserController@hudx_makkah');
Route::get('/hudx_madina','frontend\user_dashboard\UserController@hudx_madina');

Route::get('/umrah_makkah','frontend\user_dashboard\UserController@umrah_makkah');
Route::get('/umrah_madina','frontend\user_dashboard\UserController@umrah_madina');
Route::get('/agoda_makkah','frontend\user_dashboard\UserController@agoda_makkah');
Route::get('/agoda_madina','frontend\user_dashboard\UserController@agoda_madina');

//accounts routes
Route::get('/ledger','frontend\user_dashboard\UserController@ledger');
Route::get('/income_statement','frontend\user_dashboard\UserController@incomestatement');
Route::get('/notebook','frontend\user_dashboard\UserController@notebook');
Route::get('/trail_balance','frontend\user_dashboard\UserController@trailblance');
Route::get('/balance_sheet','frontend\user_dashboard\UserController@blancesheet');
Route::get('/income','frontend\user_dashboard\UserController@income');
Route::get('/expense','frontend\user_dashboard\UserController@expense');
//visa route
Route::get('/visa_applied','frontend\user_dashboard\UserController@visa_applied');
Route::get('/visa_not_applied','frontend\user_dashboard\UserController@visa_not_applied');
Route::get('/b2b_arrival_details','frontend\user_dashboard\UserController@b2b_arrival_details');
Route::get('/b2b_depature_details','frontend\user_dashboard\UserController@b2b_depature_details');
Route::get('/b2c_arrival_details','frontend\user_dashboard\UserController@b2c_arrival_details');
Route::get('/b2c_depature_details','frontend\user_dashboard\UserController@b2c_depature_details');
//employees routes
Route::get('/employees',[App\Http\Controllers\frontend\EmployeeController::class,'employees']);
Route::get('/employees/add',[App\Http\Controllers\frontend\EmployeeController::class,'create']);
Route::post('/employees/submit',[App\Http\Controllers\frontend\EmployeeController::class,'store']);
Route::get('/employees_edit/{id}',[App\Http\Controllers\frontend\EmployeeController::class,'edit']);
Route::post('/employees_update/{id}',[App\Http\Controllers\frontend\EmployeeController::class,'update']);
Route::get('/employees_delete/{id}',[App\Http\Controllers\frontend\EmployeeController::class,'delete']);
Route::get('/view_location/{id}',[App\Http\Controllers\frontend\EmployeeController::class,'view_location']);
Route::get('/view_task_location/{id}',[App\Http\Controllers\frontend\EmployeeController::class,'view_task_location']);


//attendence routes
Route::get('/attendance',[App\Http\Controllers\frontend\AttendenceController::class,'attendence']);
Route::get('/attendance/add',[App\Http\Controllers\frontend\AttendenceController::class,'create']);
Route::post('/attendance/submit',[App\Http\Controllers\frontend\AttendenceController::class,'store']);
Route::get('/attendance_edit/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'edit']);
Route::post('/attendance_update/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'update']);
Route::get('/attendance_delete/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'delete']);
Route::post('/appoint/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'view_task'])->name('task.appoint');
Route::get('/assign_mandob/{id}',[App\Http\Controllers\frontend\AttendenceController::class,'assign_mandob']);

Route::get('/employees_task',[App\Http\Controllers\frontend\AttendenceController::class,'employees_task']);


Route::post('/booking-detail','frontend\user_dashboard\UserController@bookingDetail');
Route::post('/searching','frontend\user_dashboard\UserController@filter_data');
Route::post('/agent_searching','frontend\user_dashboard\UserController@agent_searching');
Route::post('/searching_b2b','frontend\user_dashboard\UserController@filter_datab2b');




Route::get('/login_emp',  [App\Http\Controllers\frontend\EmployeeController::class,'loginForm'])->name('login_emp');
Route::post('/login_emp',  [App\Http\Controllers\frontend\EmployeeController::class,'login']);
Route::get('/logout_emp',  [App\Http\Controllers\frontend\EmployeeController::class,'logout']);

Route::get('/leave_employees',[App\Http\Controllers\LeaveController::class,'index']);
Route::get('/leave_status/{status}/{id}',[App\Http\Controllers\LeaveController::class,'status']);
Route::post('/leaves/submit',[App\Http\Controllers\LeaveController::class,'store']);



Route::get('/approve/{id}', [App\Http\Controllers\LeaveController::class,'approve'])->name('admin.approve');
Route::get('/decline/{id}', [App\Http\Controllers\LeaveController::class,'decline'])->name('admin.decline');


Route::get('lead_passenger_location', 'frontend\user_dashboard\UserController@lead_passenger_location');


});


//arabic route

Route::group(['prefix'=>'super_admin-ar','middleware'=>'admin_middleware'],function(){


Route::get('/','arabic_frontend\user_dashboard\UserController@index');
Route::get('/user_profile','arabic_frontend\user_dashboard\UserController@user_profile');
Route::get('/bookings','arabic_frontend\user_dashboard\UserController@bookings');
Route::get('/b2cbookings','arabic_frontend\user_dashboard\UserController@b2cbookings');
Route::get('/b2bbookings','arabic_frontend\user_dashboard\UserController@b2bbookings');
Route::get('/settings','arabic_frontend\user_dashboard\UserController@settings');
Route::get('/logout','arabic_frontend\user_dashboard\UserController@logout');

Route::get('/b2bagents','arabic_frontend\user_dashboard\UserController@b2bagents');

Route::get('/b2cusers','arabic_frontend\user_dashboard\UserController@b2cusers');

Route::get('/b2b_hotel_makkah','arabic_frontend\user_dashboard\UserController@hotel_makkah');
Route::get('/b2b_hotel_madina','arabic_frontend\user_dashboard\UserController@hotel_madina');
Route::get('/b2b_transfer','arabic_frontend\user_dashboard\UserController@transfer_b2b');

Route::get('/b2c_hotel_makkah','arabic_frontend\user_dashboard\UserController@hotel_makkah_b2c');
Route::get('/b2c_hotel_madina','arabic_frontend\user_dashboard\UserController@hotel_madina_b2c');
Route::get('/b2c_transfer','arabic_frontend\user_dashboard\UserController@transfer_b2c');
Route::get('/b2c_ground_services','arabic_frontend\user_dashboard\UserController@ground_services_b2c');

Route::get('/b2bagents_verified','arabic_frontend\user_dashboard\UserController@b2bagents_verified');
Route::get('/b2btop_agents','arabic_frontend\user_dashboard\UserController@b2btop_agents');
Route::get('/b2bagents_unverified','arabic_frontend\user_dashboard\UserController@b2bagents_unverified');

Route::get('/hudx_makkah','arabic_frontend\user_dashboard\UserController@hudx_makkah');
Route::get('/hudx_madina','arabic_frontend\user_dashboard\UserController@hudx_madina');

Route::get('/umrah_makkah','arabic_frontend\user_dashboard\UserController@umrah_makkah');
Route::get('/umrah_madina','arabic_frontend\user_dashboard\UserController@umrah_madina');
Route::get('/agoda_makkah','arabic_frontend\user_dashboard\UserController@agoda_makkah');
Route::get('/agoda_madina','arabic_frontend\user_dashboard\UserController@agoda_madina');

//accounts routes
Route::get('/ledger','arabic_frontend\user_dashboard\UserController@ledger');
Route::get('/income_statement','arabic_frontend\user_dashboard\UserController@incomestatement');
Route::get('/notebook','arabic_frontend\user_dashboard\UserController@notebook');
Route::get('/trail_balance','arabic_frontend\user_dashboard\UserController@trailblance');
Route::get('/balance_sheet','arabic_frontend\user_dashboard\UserController@blancesheet');
Route::get('/income','arabic_frontend\user_dashboard\UserController@income');
Route::get('/expense','arabic_frontend\user_dashboard\UserController@expense');
//visa route
Route::get('/visa_applied','arabic_frontend\user_dashboard\UserController@visa_applied');
Route::get('/visa_not_applied','arabic_frontend\user_dashboard\UserController@visa_not_applied');
Route::get('/b2b_arrival_details','arabic_frontend\user_dashboard\UserController@b2b_arrival_details');
Route::get('/b2b_depature_details','arabic_frontend\user_dashboard\UserController@b2b_depature_details');
Route::get('/b2c_arrival_details','arabic_frontend\user_dashboard\UserController@b2c_arrival_details');
Route::get('/b2c_depature_details','arabic_frontend\user_dashboard\UserController@b2c_depature_details');
//employees routes
Route::get('/employees',[App\Http\Controllers\arabic_frontend\EmployeeController::class,'employees']);
Route::get('/employees/add',[App\Http\Controllers\arabic_frontend\EmployeeController::class,'create']);
Route::post('/employees/submit',[App\Http\Controllers\arabic_frontend\EmployeeController::class,'store']);
Route::get('/employees_edit/{id}',[App\Http\Controllers\arabic_frontend\EmployeeController::class,'edit']);
Route::post('/employees_update/{id}',[App\Http\Controllers\arabic_frontend\EmployeeController::class,'update']);
Route::get('/employees_delete/{id}',[App\Http\Controllers\arabic_frontend\EmployeeController::class,'delete']);
Route::get('/view_location/{id}',[App\Http\Controllers\arabic_frontend\EmployeeController::class,'view_location']);
Route::get('/view_task_location/{id}',[App\Http\Controllers\arabic_frontend\EmployeeController::class,'view_task_location']);


//attendence routes
Route::get('/attendance',[App\Http\Controllers\arabic_frontend\AttendenceController::class,'attendence']);
Route::get('/attendance/add',[App\Http\Controllers\arabic_frontend\AttendenceController::class,'create']);
Route::post('/attendance/submit',[App\Http\Controllers\arabic_frontend\AttendenceController::class,'store']);
Route::get('/attendance_edit/{id}',[App\Http\Controllers\arabic_frontend\AttendenceController::class,'edit']);
Route::post('/attendance_update/{id}',[App\Http\Controllers\arabic_frontend\AttendenceController::class,'update']);
Route::get('/attendance_delete/{id}',[App\Http\Controllers\arabic_frontend\AttendenceController::class,'delete']);
Route::post('/appoint/{id}',[App\Http\Controllers\arabic_frontend\AttendenceController::class,'view_task'])->name('task.appoint.ar');
Route::get('/assign_mandob/{id}',[App\Http\Controllers\arabic_frontend\AttendenceController::class,'assign_mandob']);

Route::get('/employees_task',[App\Http\Controllers\arabic_frontend\AttendenceController::class,'employees_task']);


Route::post('/booking-detail','arabic_frontend\user_dashboard\UserController@bookingDetail');
Route::post('/searching','arabic_frontend\user_dashboard\UserController@filter_data');
Route::post('/agent_searching','arabic_frontend\user_dashboard\UserController@agent_searching');
Route::post('/searching_b2b','arabic_frontend\user_dashboard\UserController@filter_datab2b');




Route::get('/login_emp',  [App\Http\Controllers\arabic_frontend\EmployeeController::class,'loginForm'])->name('login_emp.ar');
Route::post('/login_emp',  [App\Http\Controllers\arabic_frontend\EmployeeController::class,'login']);
Route::get('/logout_emp',  [App\Http\Controllers\arabic_frontend\EmployeeController::class,'logout']);

Route::get('/leave_employees',[App\Http\Controllers\LeaveController::class,'index']);
Route::get('/leave_status/{status}/{id}',[App\Http\Controllers\LeaveController::class,'status']);
Route::post('/leaves/submit',[App\Http\Controllers\LeaveController::class,'store']);



Route::get('/approve/{id}', [App\Http\Controllers\LeaveController::class,'approve'])->name('admin.approve.ar');
Route::get('/decline/{id}', [App\Http\Controllers\LeaveController::class,'decline'])->name('admin.decline.ar');


Route::get('lead_passenger_location', 'frontend\user_dashboard\UserController@lead_passenger_location');


});

Route::post('/daterange/fetch_data-ar', 'frontend\user_dashboard\UserController@fetch_data')->name('daterange.fetch_data.ar');
Route::post('/daterange/fetch_data/month-ar', 'frontend\user_dashboard\UserController@fetch_data_month')->name('daterange.fetch.data.month.ar');
Route::post('/daterange/fetch_data/month/revenue-ar', 'frontend\user_dashboard\UserController@fetch_data_month_revenue')->name('month_revenue.ar');
Route::post('/daterange/fetch_data/week-ar', 'frontend\user_dashboard\UserController@fetch_data_week')->name('daterange.fetch.data.week.ar');
Route::post('/daterange/fetch_data/week/revenue-ar', 'frontend\user_dashboard\UserController@fetch_data_week_revenue')->name('daterange.fetch.data.week.revenue.ar');
Route::post('/daterange/fetch_data/today-ar', 'frontend\user_dashboard\UserController@fetch_data_today')->name('daterange.today.ar');
Route::post('/daterange/fetch_data/today/revenue-ar', 'frontend\user_dashboard\UserController@fetch_data_today_revenue')->name('daterange.today_revenue.ar');
Route::get('b2b_agents-ar/{id}','frontend\user_dashboard\UserController@getMessage');
Route::get('b2b_top_agents-ar/{id}','frontend\user_dashboard\UserController@topagentMessage');


Route::get('/findCityName','frontend\user_dashboard\UserController@findCityName');
//Route::get('/findAgenName','frontend\user_dashboard\UserController@findagent');
Route::get('super_admin/findAgentName-ar/{id}','frontend\user_dashboard\UserController@getCategory');


//graph filter route
Route::post('/searching_booking_wise-ar','frontend\user_dashboard\UserController@searching_booking_wise_year_graph')->name('searching_booking_wise_year_graph.ar');
Route::post('/searching_revenue_wise-ar', 'frontend\user_dashboard\UserController@searching_revenue_wise_graph')->name('searching.year.graph.ar');
Route::post('/searching_monthbooking_wise-ar', 'frontend\user_dashboard\UserController@searching_monthbooking_wise_graph')->name('searching.monthbooking.graph.ar');
Route::post('/searching_monthrevenue_wise-ar', 'frontend\user_dashboard\UserController@searching_monthrevenue_wise_graph')->name('searching.monthrevenue.graph.ar');







//arabic route end























Route::post('/daterange/fetch_data', 'frontend\user_dashboard\UserController@fetch_data')->name('daterange.fetch_data');
Route::post('/daterange/fetch_data/month', 'frontend\user_dashboard\UserController@fetch_data_month')->name('daterange.fetch.data.month');
Route::post('/daterange/fetch_data/month/revenue', 'frontend\user_dashboard\UserController@fetch_data_month_revenue')->name('month_revenue');
Route::post('/daterange/fetch_data/week', 'frontend\user_dashboard\UserController@fetch_data_week')->name('daterange.fetch.data.week');
Route::post('/daterange/fetch_data/week/revenue', 'frontend\user_dashboard\UserController@fetch_data_week_revenue')->name('daterange.fetch.data.week.revenue');
Route::post('/daterange/fetch_data/today', 'frontend\user_dashboard\UserController@fetch_data_today')->name('daterange.today');
Route::post('/daterange/fetch_data/today/revenue', 'frontend\user_dashboard\UserController@fetch_data_today_revenue')->name('daterange.today_revenue');
Route::get('b2b_agents/{id}','frontend\user_dashboard\UserController@getMessage');
Route::get('b2b_top_agents/{id}','frontend\user_dashboard\UserController@topagentMessage');


Route::get('/findCityName','frontend\user_dashboard\UserController@findCityName');
//Route::get('/findAgenName','frontend\user_dashboard\UserController@findagent');
Route::get('super_admin/findAgentName/{id}','frontend\user_dashboard\UserController@getCategory');


//graph filter route
Route::post('/searching_booking_wise','frontend\user_dashboard\UserController@searching_booking_wise_year_graph')->name('searching_booking_wise_year_graph');
Route::post('/searching_revenue_wise', 'frontend\user_dashboard\UserController@searching_revenue_wise_graph')->name('searching.year.graph');
Route::post('/searching_monthbooking_wise', 'frontend\user_dashboard\UserController@searching_monthbooking_wise_graph')->name('searching.monthbooking.graph');
Route::post('/searching_monthrevenue_wise', 'frontend\user_dashboard\UserController@searching_monthrevenue_wise_graph')->name('searching.monthrevenue.graph');


// user dashboard routes ends

//login  and register routes
Route::get('signup1', 'frontend\Register1Controller@signup');
Route::post('register1', 'frontend\Register1Controller@register1');
Route::get('login1', 'frontend\LoginController@login');
Route::post('signin', 'frontend\LoginController@signin');
Route::post('change-password', 'frontend\LoginController@change_password');
Route::get('logout', 'frontend\LoginController@logout');
Auth::routes();

 Route::get('/home', 'HomeController@index')->name('home');


Route::get('/viewalllocationsajax',[App\Http\Controllers\frontend\AttendenceController::class,'viewalllocationsajax']);


//mail route
Route::get('/send-mail',  [App\Http\Controllers\frontend\MailSendController::class,'mailsend'])->name('mailsend');
//get current location route
Route::get('show-user-location-data', [App\Http\Controllers\LocationController::class, 'index']);



Route::get('lead_passenger/create_password', [App\Http\Controllers\frontend\AttendenceController::class,'create_password']);

Route::post('lead_passenger/create_password/submit', [App\Http\Controllers\frontend\AttendenceController::class,'create_password_lead'])->name('lead_passenger.password');










