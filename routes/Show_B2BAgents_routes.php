<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\B2BAgentController;

Route::post('create_B2B_Agents',[B2BAgentController::class,'create_B2B_Agents']);
Route::post('view_B2B_Agents',[B2BAgentController::class,'view_B2B_Agents']);
Route::post('approve_B2B_Agents',[B2BAgentController::class,'approve_B2B_Agents']);
Route::post('disable_B2B_Agents',[B2BAgentController::class,'disable_B2B_Agents']);
Route::post('reject_B2B_Agents',[B2BAgentController::class,'reject_B2B_Agents']);
Route::post('view_B2B_Agents_Single',[B2BAgentController::class,'view_B2B_Agents_Single']);
Route::post('b2b_Agents_Profile',[B2BAgentController::class,'b2b_Agents_Profile']);
Route::post('b2b_Agents_Booking_Statement',[B2BAgentController::class,'b2b_Agents_Booking_Statement']);
Route::post('b2b_Agent_Payments_List_Ajax',[B2BAgentController::class,'b2b_Agent_Payments_List_Ajax']);
Route::post('b2b_Agent_Profile_Bookings',[B2BAgentController::class,'b2b_Agent_Profile_Bookings']);
Route::post('b2b_Agents_Details_ByType',[B2BAgentController::class,'b2b_Agents_Details_ByType']);
Route::post('b2b_Agent_Prices_Type',[B2BAgentController::class,'b2b_Agent_Prices_Type']);
Route::post('b2b_edit_Agents',[B2BAgentController::class,'b2b_edit_Agents']);
Route::post('b2b_update_Agents',[B2BAgentController::class,'b2b_update_Agents']);
Route::post('b2b_Agent_Payment_Details',[B2BAgentController::class,'b2b_Agent_Payment_Details']);
Route::post('b2b_Agent_Booking_Statement_Ajax',[B2BAgentController::class,'b2b_Agent_Booking_Statement_Ajax']);
Route::post('b2b_Agent_Transfer_Statement',[B2BAgentController::class,'b2b_Agent_Transfer_Statement']);
Route::post('b2b_Agent_Hotel_Supplier_Report',[B2BAgentController::class,'b2b_Agent_Hotel_Supplier_Report']);
Route::post('b2b_Agent_Hotel_Supplier_Report_Sub_User',[B2BAgentController::class,'b2b_Agent_Hotel_Supplier_Report_Sub_User']);
Route::post('b2b_Agent_Markup',[B2BAgentController::class,'b2b_Agent_Markup']);
Route::post('add_b2b_Agent_Markup',[B2BAgentController::class,'add_b2b_Agent_Markup']);
Route::post('view_b2b_Agent_Markup',[B2BAgentController::class,'view_b2b_Agent_Markup']);
Route::post('get_B2B_Agents_Details',[B2BAgentController::class,'get_B2B_Agents_Details']);
Route::post('b2b_Agents_Change_Password',[B2BAgentController::class,'b2b_Agents_Change_Password']);
Route::post('b2b_Agents_Update_Change_Password',[B2BAgentController::class,'b2b_Agents_Update_Change_Password']);
// Credit Limit
Route::post('allowed_B2B_Agents_Credit_Limit',[B2BAgentController::class,'allowed_B2B_Agents_Credit_Limit']);
Route::post('allow_B2B_Agents_Credit_Limit',[B2BAgentController::class,'allow_B2B_Agents_Credit_Limit']);
Route::post('allow_Multiple_B2B_Agents_Credit_Limit',[B2BAgentController::class,'allow_Multiple_B2B_Agents_Credit_Limit']);
Route::post('stop_B2B_Agents_Credit_Limit',[B2BAgentController::class,'stop_B2B_Agents_Credit_Limit']);
Route::post('request_B2B_Agents_Credit_Limit',[B2BAgentController::class,'request_B2B_Agents_Credit_Limit']);
Route::post('b2b_Add_Credit_Limit',[B2BAgentController::class,'b2b_Add_Credit_Limit']);
Route::post('b2b_View_Credit_Limit',[B2BAgentController::class,'b2b_View_Credit_Limit']);
Route::post('edit_B2B_Agents_Credit_Limit',[B2BAgentController::class,'edit_B2B_Agents_Credit_Limit']);
Route::post('b2b_Update_Credit_Limit',[B2BAgentController::class,'b2b_Update_Credit_Limit']);
Route::post('b2b_View_Credit_Limit_Booking',[B2BAgentController::class,'b2b_View_Credit_Limit_Booking']);
Route::post('view_B2B_Agents_Credit_Limit',[B2BAgentController::class,'view_B2B_Agents_Credit_Limit']);
Route::post('view_B2B_Agents_Credit_Limit_Approve',[B2BAgentController::class,'view_B2B_Agents_Credit_Limit_Approve']);
Route::post('b2b_Check_Credit_Limit_Approved',[B2BAgentController::class,'b2b_Check_Credit_Limit_Approved']);
Route::post('b2b_View_Credit_Limit_Ledger',[B2BAgentController::class,'b2b_View_Credit_Limit_Ledger']);
// Credit Limit
Route::post('make_Cancel_Request',[B2BAgentController::class,'make_Cancel_Request']);
Route::post('reject_Pyament_Booking',[B2BAgentController::class,'reject_Pyament_Booking']);