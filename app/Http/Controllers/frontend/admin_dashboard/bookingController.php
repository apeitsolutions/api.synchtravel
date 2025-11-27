<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\ToursBooking;
use App\Models\booking_users;
use App\Models\Cart_details;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\view_booking_payment_recieve;
use App\Models\pay_Invoice_Agent;
use App\Models\alhijaz_Notofication;
use App\Models\booking_customers;
use DB;

class bookingController extends Controller
{
/*
|--------------------------------------------------------------------------
| bookingController Function Started
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Customer Register Submit Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Insert The Client Details from database.
*/ 
function customer_register_submit(Request $req){
         $userData           = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
            $request_data   = json_decode($req->request_data);
            $customer_exist = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$request_data->email)->first();
            // dd($customer_exist);
            if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                $customer_detail    = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
                return response()->json(['status'=>'error','message'=>'customer already exist','customer_detail'=>$customer_detail]);
            }else{
               
                
                $password = Hash::make($request_data->password);
                
                $customer_detail                    = new booking_customers();
                $customer_detail->name              = $request_data->name." ".$request_data->lname;
                $customer_detail->opening_balance   = 0;
                $customer_detail->balance           = 0;
                $customer_detail->email             = $request_data->email;
                $customer_detail->password             = $password;
                $customer_detail->phone             = $request_data->phone;
                $customer_detail->gender            = $request_data->gender;
                $customer_detail->country           = $request_data->country;

                $customer_detail->customer_id       = $userData->id;
                $result = $customer_detail->save();

                if($result){
                    return response()->json(['status'=>'success','message'=>'Sign up Successfully','customer_data'=>$customer_detail]);
                }else{
                    return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again','customer_data'=>'']);
                }
            }
        }else{
            return response()->json(['status'=>'error','customer_detail'=>'','message'=>'validation Error']);
        }
    }
/*
|--------------------------------------------------------------------------
| Customer Register Submit Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Archive Item Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the Archive Item Dynamicly by getting table names and .
*/ 
function archive_item(Request $req){
        // dd($req->all());
         $userData           = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
                $result = DB::table($req->table_name)->where('id',$req->id)->update([
                        'status' => $req->status
                    ]);
                if($result){
                    return response()->json(['status'=>'success']);
                }else{
                    return response()->json(['status'=>'error']);
                }
            
        }else{
            return response()->json(['status'=>'error','customer_detail'=>'','message'=>'validation Error']);
        }
    }
    
/*
|--------------------------------------------------------------------------
| Archive Item Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Delete Agent Permanently Started
|--------------------------------------------------------------------------
| In this Function, we coded the Archive Item Dynamicly by getting table names and .
*/ 
function delete_agent_parmanently(Request $req){
        // dd($req->all());
         $userData           = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
                $invoices_count = DB::table('agents_ledgers_new')->where('agent_id',$req->id)->count();
                
                if($invoices_count > 0){
                    return response()->json(['status'=>'error','message'=>'This Agent has Performed Some Transcations.It cannot be deleted ']);
                }
                
                $result = DB::table('Agents_detail')->where('id',$req->id)->delete();
                    
                if($result){
                    return response()->json(['status'=>'success','message'=>'Agent Deleted Successfully ']);
                }else{
                    return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again ']);
                }
            
        }else{
            return response()->json(['status'=>'error','customer_detail'=>'','message'=>'validation Error']);
        }
    }
    
/*
|--------------------------------------------------------------------------
| Delete Agent Permanently Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Delete Customer Permanently Started
|--------------------------------------------------------------------------
| In this Function, we coded the Archive Item Dynamicly by getting table names and .
*/ 
function delete_customer_parmanently(Request $req){
        // dd($req->all());
         $userData           = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
                $invoices_count = DB::table('customer_ledger')->where('booking_customer',$req->id)->count();
                
                if($invoices_count > 0){
                    return response()->json(['status'=>'error','message'=>'This Customer has Performed Some Transcations.It cannot be deleted ']);
                }
                
                $result = DB::table('booking_customers')->where('id',$req->id)->delete();
                    
                if($result){
                    return response()->json(['status'=>'success','message'=>'Customer Deleted Successfully ']);
                }else{
                    return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again ']);
                }
            
        }else{
            return response()->json(['status'=>'error','customer_detail'=>'','message'=>'validation Error']);
        }
    }
    
/*
|--------------------------------------------------------------------------
| Delete Customer Permanently Ended
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| Archive Item List Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the Archive Item Dynamicly by getting table names and .
*/ 
function archived_items_list(Request $req){
        // dd($req->all());
         $userData           = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
                $data = DB::table($req->table_name)->where('status',0)->where('customer_id',$userData->id)->get();
                return response()->json([
                    'status'=>'success',
                    'data' => $data
                    ]);
            
        }else{
            return response()->json(['status'=>'error','customer_detail'=>'','message'=>'validation Error']);
        }
    }
    
    
/*
|--------------------------------------------------------------------------
| Archive Item Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Add Spacial Discount Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Added the Spacial Discount In price For our Client.
*/
function add_special_discount(Request $request){
         $cart_data = DB::table('cart_details')->where('invoice_no',$request->invoice_no)->get();
        //  $cart_data = DB::table('cart_details')->get();
        // print_r($request->all());
         
 
                 $cart_data_all = json_decode($cart_data[0]->cart_total_data); 
        //         // print_r($cart_data);
                // print_r($cart_data_all);
                if(isset($cart_data_all->price_without_disc)){
                          $total_price = $cart_data_all->price - $request->discount_am;
                            $cart_data_all->price = $total_price;
                            if(isset($cart_data_all->special_discount)){
                                $cart_data_all->special_discount = $cart_data_all->special_discount + $request->discount_am;
                            }else{
                                $cart_data_all->special_discount = $request->discount_am;
                            }
                            
                    DB::beginTransaction();
                  
                     try {
                         $result = DB::table('cart_details')->where('id',$cart_data[0]->id)->update([
                                'price'=>$total_price,
                                'tour_total_price'=>$total_price,
                                'cart_total_data'=>json_encode($cart_data_all),
                                ]);
                                
                          DB::table('view_booking_payment_recieve')->where('package_id',$cart_data[0]->booking_id)->update(['total_amount'=>$total_price]);
                          
                           DB::commit();
                                // echo $result;
                        } catch (Throwable $e) {

                            DB::rollback();
                            return response()->json(['message'=>'error','booking_id'=> '']);
                        }
                     
                                
        //                         echo $result;
                }
          

         
     
                    
        if($result){
            return json_encode(['status'=>'success']);
        }else{
            return json_encode(['status'=>'error']);
        }
        // print_r($cart_data_all);
    }
/*
|--------------------------------------------------------------------------
| Add Spacial Discount Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Save Booking Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to insert the data ToursBooking from database.
*/
function save_booking(Request $request){
        
        $currency_slc=$request->currency_slc;
            function dateDiffInDays($date1, $date2){
                $diff = strtotime($date2) - strtotime($date1);
                return abs(round($diff / 86400));
            }
            
            function getBetweenDates($startDate, $endDate){
                $rangArray = [];
                $startDate = strtotime($startDate);
                $endDate = strtotime($endDate);
                     
                     $startDate += (86400); 
                for ($currentDate = $startDate; $currentDate <= $endDate; 
                                                $currentDate += (86400)) {
                                                        
                    $date = date('Y-m-d', $currentDate);
                    $rangArray[] = $date;
                }
          
                return $rangArray;
            }
            
        $request_data = json_decode($request->request_data);
        // dd($request_data);
        $cart_data = json_decode($request->cart_data);
        
        // $request_data = json_decode($request->request_data);
        
        // dd($request_data[0]->name);
        
        $lead_passenger_data    = $request_data;
        $name                   = $request_data[0]->name." ".$request_data[0]->lname;
        $email                  = $request_data[0]->email;
        
          $cart_data_main = json_decode($request->cart_data);
            //   dd($cart_data_main);
            $cart_data = $cart_data_main[0];
         
            if($cart_data_main[1] == 'tour'){
                foreach($cart_data as $cart_res){
                    if(isset($cart_res->provider_id)){
                        $provider_id = $cart_res->provider_id;
                    }else{
                        $provider_id = 'test';
                    }
                }
            }else{
                $provider_id = 'test';
            }
             
            //  dd($cart_res->provider_id);
        
        if(isset($cart_res->provider_id)){
            if($cart_res->provider_id == null){
                $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            }else{
                $userData = CustomerSubcription::where('small_token',$cart_res->provider_id)->select('id','status')->first();
            }
        }else{
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        }
        
        $randomNumber = random_int(1000000, 9999999);
        if($userData->id == '24'){
             $invoiceId =  "365T".$randomNumber;
        }else{
            $invoiceId =  "AHT".$randomNumber;
        }
        
       
        $tourObj = new ToursBooking;

        $tourObj->passenger_name = $name;
        $tourObj->email = $email;
        $tourObj->cart_details = '';
        $tourObj->passenger_detail = $request->request_data;
        $tourObj->adults_detail = $request->adults;
        $tourObj->child_detail = $request->childs;
        $tourObj->infant_details = $request->infants;
        $tourObj->SU_id = $request->SU_id ?? '';
        $tourObj->customer_id = $userData->id;
        $tourObj->parent_token = $request->token;
        $tourObj->invoice_no = $invoiceId;
        $tourObj->booking_person = $request->booking_person;
        $tourObj->exchange_currency = $currency_slc;
        
        
        $tourObj->provider_id = $provider_id;
        
        // print_r($tourObj);die;

        DB::beginTransaction();
        
        try {
            $tourData = $tourObj->save();
            //   dd($tourData);

            $booking_id = $tourObj->id;
            // dd(json_decode($request->cart_data));
             $cart_data_main = json_decode($request->cart_data);
            //   dd($cart_data_main);
             $cart_data = $cart_data_main[0];
             
             if(isset($request->request_form) && $request->request_form == 'web'){
                $customer_id = "";
                $customer_exist = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$email)->first();
                if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                        $customer_id = $customer_exist->id;
                }else{
                   
                    
                    $password = Hash::make('admin123');
                    
                    $customer_detail                    = new booking_customers();
                    $customer_detail->name              = $request_data[0]->name." ".$request_data[0]->lname;
                    $customer_detail->opening_balance   = 0;
                    $customer_detail->balance           = 0;
                    $customer_detail->email             = $request_data[0]->email;
                    $customer_detail->password             = $password;
                    $customer_detail->phone             = $lead_passenger_data[0]->phone;
                    $customer_detail->gender            = $lead_passenger_data[0]->gender;
                    $customer_detail->country           = $lead_passenger_data[0]->country;
    
                    $customer_detail->customer_id       = $userData->id;
                    $result = $customer_detail->save();
                    
                    $customer_id = $customer_detail->id;
    
                    
                }
             
                // print_r($cart_data);
                // die;
                 foreach($cart_data as $index => $cart_res){
                     $cart_data->$index->customer_id = $customer_id;
                 }
                 
             }


             if($cart_data_main[1] == 'tour'){
                 foreach($cart_data as $index => $cart_res){
                // print_r($cart_res);
                // die;
                    $addtional_services = [];
                    if($cart_res->Additional_services_names != ''){
                        $service = [
                            'service'=>$cart_res->Additional_services_names,
                            'service_price'=>$cart_res->services_price,
                            'service_type'=>'',
                            'person'=>$cart_res->services_persons,
                            'dates'=>$cart_res->service_dates,
                            'Service_Days'=>$cart_res->service_day,
                            'total_price'=>$cart_res->service_day * $cart_res->services_price * $cart_res->services_persons,
                            ];
                            
                            array_push($addtional_services,$service);
                    }
                    
                    if($cart_res->Additional_services_names_more !== 'null' AND $cart_res->Additional_services_names_more !== ''){
                        $services_names = json_decode($cart_res->Additional_services_names_more);
                        $services_persons_more = json_decode($cart_res->services_persons_more);
                        $services_price_more = json_decode($cart_res->services_price_more);
                        $services_days_more = json_decode($cart_res->services_days_more);
                        $services_dates_more = json_decode($cart_res->services_dates_more);
                        
                        $z = 0;
                        foreach($services_names as $service_res){
                             $service = [
                            'service'=>$service_res,
                            'service_price'=> $services_price_more[$z],
                            'service_type'=>'',
                            'person'=>$services_persons_more[$z],
                            'dates'=>$services_dates_more[$z],
                            'Service_Days'=>$services_days_more[$z],
                            'total_price'=>$services_days_more[$z] * $services_price_more[$z] * $services_persons_more[$z],
                            ];
                            
                            array_push($addtional_services,$service);
                        }
                    }
                    
                    if(isset($cart_res->Additional_services_Awaab)){
                        $addtional_services = $cart_res->Additional_services_Awaab;
                    }else{
                        $addtional_services = json_encode($addtional_services);
                    }
                    
                    // print_r($addtional_services);
                    // dd($request->cart_visa);
                    if($request->cart_visa != "null"){
                        
                    $cart_visa_data = json_decode($request->cart_visa);
                    $cart_visa_data_save = '';
                    foreach($cart_visa_data as $visa_res){
                        $cart_visa_data_save = $visa_res;
                    }
                    }else{
                        $cart_visa_data_save = "";
                    }
                    
                    // die();
                    //  print_r($cart_res);die;
                    
                    $prvoider_id = 0;
                    if(isset($cart_res->provider_id)){
                        $prvoider_id = $cart_res->provider_id;
                    }
                    
                    
                    
                    
                    
                    // dd($customer_exist);
                 
                    
                    
                    $Cart_details = new Cart_details;
    
                    $Cart_details->tour_id = $cart_res->tourId;
                    $Cart_details->provider_id = $prvoider_id;
                    $Cart_details->cart_total_data = json_encode($cart_res);
                    $Cart_details->visa_change_data = json_encode($cart_visa_data_save);
                    
                    $Cart_details->generate_id = $cart_res->generate_id;
                    $Cart_details->tour_name = $cart_res->name;
                    $Cart_details->adults = $cart_res->adults;
                    $Cart_details->childs = $cart_res->children;
                    $Cart_details->sigle_price = $cart_res->sigle_price;
                    $Cart_details->tour_total_price = $cart_res->tour_total_price;
                    $Cart_details->total_service_price = $cart_res->total_service_price;
                    $Cart_details->price = $cart_res->price;
                    
                    $Cart_details->sharing2 = $cart_res->sharing2;
                    $Cart_details->sharing3 = $cart_res->sharing3;
                    $Cart_details->sharing4 = $cart_res->sharing4;
                    $Cart_details->sharingSelect = $cart_res->sharingSelect;
                    $Cart_details->image = $cart_res->image;
                    $Cart_details->booking_id = $booking_id;
                    $Cart_details->invoice_no = $invoiceId;
                    $Cart_details->currency = $cart_res->currency;
                    $Cart_details->pakage_type = $cart_res->pakage_type;
                    
                    $Cart_details->Additional_services_names = $addtional_services;
                 
                 if(isset($cart_res->child_price)){
                     $child_price = $cart_res->child_price;
                     $cost_price_child = $cart_res->cost_price_child;
                     $adult_total_price = $cart_res->adult_total_price;
                     $child_total_price = $cart_res->child_total_price;
                     $Cart_details->childs = 0;
                 }else{
                     $child_price = 0;
                     $cost_price_child = 0;
                     $adult_total_price = 0;
                     $child_total_price = 0;
                 }
                 $Cart_details->child_price_tour = $child_price;
                 $Cart_details->child_cost_price = $cost_price_child;
                 $Cart_details->adult_total_price = $adult_total_price;
                 $Cart_details->child_total_price = $child_total_price;
                 
                 
                
                 if(isset($cart_res->double_adults)){
                     $Cart_details->double_adults = $cart_res->double_adults;
                 }
                 
                 if(isset($cart_res->triple_adults)){
                     $Cart_details->triple_adults = $cart_res->triple_adults;
                 }
                 
                 if(isset($cart_res->quad_adults)){
                     $Cart_details->quad_adults = $cart_res->quad_adults;
                 }
                 
                  if(isset($cart_res->without_acc_adults)){
                     $Cart_details->without_acc_adults = $cart_res->without_acc_adults;
                 }
                 
                  if(isset($cart_res->double_rooms)){
                     $Cart_details->double_room = $cart_res->double_rooms;
                 }
                 
                  if(isset($cart_res->triple_rooms)){
                     $Cart_details->triple_room = $cart_res->triple_rooms;
                 }
                 
                  if(isset($cart_res->quad_rooms)){
                     $Cart_details->quad_room = $cart_res->quad_rooms;
                 }
                 
                  if(isset($cart_res->without_acc_sale_price)){
                     $Cart_details->without_acc_sale_price = $cart_res->without_acc_sale_price;
                 }
                 
                 
                 if(isset($cart_res->agent_name)){
                     $Cart_details->agent_name = $cart_res->agent_name;
                 }
                    
                    $Cart_details->client_id = $userData->id;
                    // dd($request->flight_id);
                    if(isset($request->flight_id) && $request->flight_id != null && $request->flight_id != ''){
                         $Cart_details->flight_id = $request->flight_id;
                    }else{
                        $Cart_details->flight_id = '';
                    }
                    
                    $Cart_data = $Cart_details->save();
                    
                    if($cart_res->agent_name != '-1'){
                            $agent_data = DB::table('Agents_detail')->where('id',$cart_res->agent_name)->select('id','balance')->first();
               
                                if(isset($agent_data)){
                                    // echo "Enter hre ";
                                    $agent_balance = $agent_data->balance + $cart_res->tour_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('agents_ledgers_new')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'received'=>$cart_res->tour_total_price,
                                        'balance'=>$agent_balance,
                                        'package_invoice_no'=>$invoiceId,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        ]);
                                        
                                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                }
                        }
                        
                        if(isset($cart_res->customer_id))
                        {
                        if($cart_res->customer_id != '-1'){
                            $customer_data = DB::table('booking_customers')->where('id',$cart_res->customer_id)->select('id','balance')->first();
               
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $customer_balance = $customer_data->balance + $cart_res->tour_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$cart_res->tour_total_price,
                                        'balance'=>$customer_balance,
                                        'package_invoice_no'=>$invoiceId,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        ]);
                                        
                                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                }
                        }
                        }
                    // print_r($cart_res);
                    
                    
                    $tours_data = DB::table('tours')->where('id',$cart_res->tourId)->select('accomodation_details','accomodation_details_more')->first();
                    $accomodation = json_decode($tours_data->accomodation_details);
                    $more_accomodation_details = json_decode($tours_data->accomodation_details_more);
                    
                    // print_r($accomodation);
                    // print_r($more_accomodation_details);
                    // die;
                    
                     if(isset($accomodation)){
                         foreach($accomodation as $accomodation_res){
                             if(isset($accomodation_res->hotelRoom_type_idM)){
                                $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                            
                               if($room_data){
                                
                                        $rooms_qty = 0;
                                          if(isset($cart_res->double_rooms)){
                                                if($accomodation_res->acc_type == 'Double'){
                                                     $rooms_qty = $cart_res->double_rooms;
                                                }
                                                 
                                         }
                                         
                                          if(isset($cart_res->triple_rooms)){
                                                if($accomodation_res->acc_type == 'Triple'){
                                                     $rooms_qty = $cart_res->triple_rooms;
                                                }
                                         }
                                         
                                          if(isset($cart_res->quad_rooms)){
                                              if($accomodation_res->acc_type == 'Quad'){
                                                     $rooms_qty = $cart_res->quad_rooms;
                                                }
                                         }
                                         
                                         $total_booked = $room_data->booked + $rooms_qty;
                               
                                
                                         
                                         DB::table('rooms_bookings_details')->insert([
                                             'room_id'=> $accomodation_res->hotelRoom_type_id,
                                             'booking_from'=>'package',
                                             'quantity'=>$rooms_qty,
                                             'booking_id'=>$invoiceId,
                                             'package_id'=>$cart_res->tourId,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$accomodation_res->acc_check_in,
                                             'check_out'=>$accomodation_res->acc_check_out,
                                         ]);
                                         
                               
                                        DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->update(['booked'=>$total_booked]);
                                        
                                        // Update Hotel Supplier Balance
                                
                                         $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
               
                                         if(isset($supplier_data)){
                                                // echo "Enter hre ";
                                                
                                                     $week_days_total = 0;
                                                     $week_end_days_totals = 0;
                                                     $total_price = 0;
                                                     $accomodation_res->acc_check_in = date('Y-m-d',strtotime($accomodation_res->acc_check_in));
                                                     $accomodation_res->acc_check_out = date('Y-m-d',strtotime($accomodation_res->acc_check_out));
                                                    if($room_data->price_week_type == 'for_all_days'){
                                                        $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                        $total_price = $room_data->price_all_days * $avaiable_days;
                                                    }else{
                                                         $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                         
                                                         $all_days = getBetweenDates($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                         
                                                        //  print_r($all_days);
                                                         $week_days = json_decode($room_data->weekdays);
                                                         $week_end_days = json_decode($room_data->weekends);
                                                         
                                                         foreach($all_days as $day_res){
                                                             $day = date('l', strtotime($day_res));
                                                             $day = trim($day);
                                                             $week_day_found = false;
                                                             $week_end_day_found = false;
                                                            
                                                             foreach($week_days as $week_day_res){
                                                                 if($week_day_res == $day){
                                                                     $week_day_found = true;
                                                                 }
                                                             }
                                                      
                                                            // echo "  ".$room_data->weekdays_price;
                                                             if($week_day_found){
                                                                 $week_days_total += $room_data->weekdays_price;
                                                             }else{
                                                                 $week_end_days_totals += $room_data->weekends_price;
                                                             }
                                                             
                                                             
                                                            //  foreach($week_end_days as $week_day_res){
                                                            //      if($week_day_res == $day){
                                                            //          $week_end_day_found = true;
                                                            //      }
                                                            //  }
                                                            //   if($week_end_day_found){
                                                                  
                                                            //  }
                                                         }
                                                         
                                                         
                                                        //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                         
                                                        //  print_r($all_days);
                                                         $total_price = $week_days_total + $week_end_days_totals;
                                                    }
                                                    
                                                    
                                                $all_days_price = $total_price * $rooms_qty;
                                                
                                                // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                                // die;
                                                
                                                // echo "The supplier Balance is ".$supplier_data->balance;
                                                $supplier_balance = $supplier_data->balance;
                                                $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                                
                                                // update Agent Balance
                                                
                                                DB::table('hotel_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$all_days_price,
                                                    'balance'=>$supplier_balance,
                                                    'payable_balance'=>$supplier_payable_balance,
                                                    'room_id'=>$room_data->id,
                                                    'customer_id'=>$userData->id,
                                                    'date'=>date('Y-m-d'),
                                                    'package_invoice_no'=>$Cart_details->invoice_no,
                                                    'available_from'=>$accomodation_res->acc_check_in,
                                                    'available_to'=>$accomodation_res->acc_check_out,
                                                    'room_quantity'=>$rooms_qty,
                                                    ]);
                                                    
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                    'balance'=>$supplier_balance,
                                                    'payable'=>$supplier_payable_balance
                                                    ]);
                                                
                                                
                                                  
                                                                            
                                            }
                            
                               }
                             }
                          
                         }
                        
                    }
                    
                    if(isset($more_accomodation_details)){
                        // print_r($more_accomodation_details);
                        
                         foreach($more_accomodation_details as $accomodation_res){
                             if(isset($accomodation_res->more_hotelRoom_type_idM)){
                                $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                                
                                // print_r($room_data);
                                if($room_data){
                                
                                        // $total_booked = $room_data->booked + $accomodation_res->more_acc_qty;
                                        
                                        
                                        
                                        
                                          $rooms_qty = 0;
                                              if(isset($cart_res->double_rooms)){
                                                        if($accomodation_res->more_acc_type == 'Double'){
                                                             $rooms_qty = $cart_res->double_rooms;
                                                        }
                                                         
                                                 }
                                                 
                                                  if(isset($cart_res->triple_rooms)){
                                                        if($accomodation_res->more_acc_type == 'Triple'){
                                                             $rooms_qty = $cart_res->triple_rooms;
                                                        }
                                                 }
                                                 
                                                  if(isset($cart_res->quad_rooms)){
                                                      if($accomodation_res->more_acc_type == 'Quad'){
                                                             $rooms_qty = $cart_res->quad_rooms;
                                                        }
                                                 }
                                                 
                                                 $total_booked = $room_data->booked + $rooms_qty;
                                       
                                        
                                        
                                                 
                                                 DB::table('rooms_bookings_details')->insert([
                                                     'room_id'=> $accomodation_res->more_hotelRoom_type_id,
                                                     'booking_from'=>'package',
                                                     'quantity'=>$rooms_qty,
                                                     'booking_id'=>$invoiceId,
                                                     'package_id'=>$cart_res->tourId,
                                                     'date'=>date('Y-m-d'),
                                                     'check_in'=>$accomodation_res->more_acc_check_in,
                                                     'check_out'=>$accomodation_res->more_acc_check_out,
                                                 ]);
                                                 
                                      
                                        DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->update(['booked'=>$total_booked]);
                                        
                                        // Update Hotel Supplier Balance
                                
                                         $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
               
                                         if(isset($supplier_data)){
                                                // echo "Enter hre ";
                                                
                                                     $week_days_total = 0;
                                                     $week_end_days_totals = 0;
                                                     $total_price = 0;
                                                     $accomodation_res->more_acc_check_in = date('Y-m-d',strtotime($accomodation_res->more_acc_check_in));
                                                     $accomodation_res->more_acc_check_out = date('Y-m-d',strtotime($accomodation_res->more_acc_check_out));
                                                    if($room_data->price_week_type == 'for_all_days'){
                                                        $avaiable_days = dateDiffInDays($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                        $total_price = $room_data->price_all_days * $avaiable_days;
                                                    }else{
                                                         $avaiable_days = dateDiffInDays($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                         
                                                         $all_days = getBetweenDates($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                         
                                                        //  print_r($all_days);
                                                         $week_days = json_decode($room_data->weekdays);
                                                         $week_end_days = json_decode($room_data->weekends);
                                                         
                                                         foreach($all_days as $day_res){
                                                             $day = date('l', strtotime($day_res));
                                                             $day = trim($day);
                                                             $week_day_found = false;
                                                             $week_end_day_found = false;
                                                            
                                                             foreach($week_days as $week_day_res){
                                                                 if($week_day_res == $day){
                                                                     $week_day_found = true;
                                                                 }
                                                             }
                                                      
                                                            // echo "  ".$room_data->weekdays_price;
                                                             if($week_day_found){
                                                                 $week_days_total += $room_data->weekdays_price;
                                                             }else{
                                                                 $week_end_days_totals += $room_data->weekends_price;
                                                             }
                                                             
                                                             
                                                            //  foreach($week_end_days as $week_day_res){
                                                            //      if($week_day_res == $day){
                                                            //          $week_end_day_found = true;
                                                            //      }
                                                            //  }
                                                            //   if($week_end_day_found){
                                                                  
                                                            //  }
                                                         }
                                                         
                                                         
                                                        //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                         
                                                        //  print_r($all_days);
                                                         $total_price = $week_days_total + $week_end_days_totals;
                                                    }
                                                    
                                                    
                                                $all_days_price = $total_price * $rooms_qty;
                                                
                                                // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                                // die;
                                                
                                                // echo "The supplier Balance is ".$supplier_data->balance;
                                                $supplier_balance = $supplier_data->balance;
                                                $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                                
                                                // update Agent Balance
                                                
                                                DB::table('hotel_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$all_days_price,
                                                    'balance'=>$supplier_balance,
                                                    'payable_balance'=>$supplier_payable_balance,
                                                    'room_id'=>$room_data->id,
                                                    'customer_id'=>$userData->id,
                                                    'date'=>date('Y-m-d'),
                                                    'package_invoice_no'=>$Cart_details->invoice_no,
                                                    'available_from'=>$accomodation_res->more_acc_check_in,
                                                    'available_to'=>$accomodation_res->more_acc_check_out,
                                                    'room_quantity'=>$rooms_qty,
                                                    ]);
                                                    
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                    'balance'=>$supplier_balance,
                                                    'payable'=>$supplier_payable_balance
                                                    ]);
                                                
                                                
                                                  
                                                                            
                                            }
                            
                                }
                             }  
                         }
                    
                    }
                    
                    
                    // dd($Cart_data);
                    // dd($cart_res);
                    
                    $tours_data = DB::table('tours_2')->where('tour_id',$cart_res->tourId)->select('flight_supplier','flight_route_id_occupied','flights_per_person_price')->first();
                    
                    $flight_data = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                 
                    if(isset($flight_data)){
                         
                                // Update Flight Supplier Balance
                                
                                $supplier_data = DB::table('supplier')->where('id',$flight_data->dep_supplier)->first();
                                                  
                                //  Calculate Child Price
                                
                                $price_diff = 0;
                                if($cart_res->total_childs > 0){
                                    $child_price_wi_adult_price = $flight_data->flights_per_person_price * $cart_res->total_childs;
                                    $child_price_wi_child_price = $flight_data->flights_per_child_price * $cart_res->total_childs;
                                    $price_diff = $child_price_wi_adult_price - $child_price_wi_child_price;
                                }
                                
                                $infant_price = 0;
                                if($cart_res->total_Infants > 0){
                                    $infant_price = $flight_data->flights_per_infant_price * $cart_res->total_Infants;
                                }
                                
                                
                                
                                if($price_diff != 0 || $infant_price != 0){
                            
                                    $supplier_balance = $supplier_data->balance - $price_diff;
                                    
                                    $supplier_balance = $supplier_balance + $infant_price;
                                    $total_differ = $infant_price - $price_diff;
                                    
                                    DB::table('flight_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'payment'=>$total_differ,
                                                'balance'=>$supplier_balance,
                                                'route_id'=>$flight_data->id,
                                                'date'=>date('Y-m-d'),
                                                'customer_id'=>$userData->id,
                                                'adult_price'=>$flight_data->flights_per_person_price,
                                                'child_price'=>$flight_data->flights_per_child_price,
                                                'infant_price'=>$flight_data->flights_per_infant_price,
                                                'adult_seats_booked'=>$cart_res->total_adults,
                                                'child_seats_booked'=>$cart_res->total_childs,
                                                'infant_seats_booked'=>$cart_res->total_Infants,
                                                'package_invoice_no'=>$Cart_details->invoice_no,
                                                'remarks'=>'Package Booked',
                                              ]);
                                              
                                    DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                }
                                
                                
                            
                        
                        
                    }
                    
                    // Update 3rd Party package Booking Commission
                    
                    if(isset($cart_res->provider_id)){
                        if($cart_res->provider_id != null){
                        //  Book From Other Website
                        //  $thirdPartyData = CustomerSubcription::where('small_token',$cart_res->provider_id)->select('id','small_token','status')->first();
                        //  if(isset($thirdPartyData)){
                        //      $thirdPartyBalData = DB::table('3rd_party_commissions')
                        //                             ->where('customer_id',$userData->id)
                        //                             ->where('third_party_id',$thirdPartyData->id)
                        //                             ->first();
                        //      // 1 Calculate Commission
                             
                        //      // Check Type of Commission
                        //         if($thirdPartyBalData->commission_type == '%'){
                        //             $commisson_amount    = ($cart_res->tour_total_price * $thirdPartyBalData->commission) / 100;
                        //         }else{
                        //             $commisson_amount = $thirdPartyBalData->commission;
                        //         }
                                
                        //         $third_part_bal = $thirdPartyBalData->balance + $commisson_amount;
                                
                        //         DB::table('3rd_party_package_book_ledger')->insert([
                        //                 'package_id'=>$cart_res->tourId,
                        //                 'package_owner'=>$userData->id,
                        //                 'package_request'=>$thirdPartyData->id,
                        //                 'booking_amount'=>$cart_res->tour_total_price,
                        //                 'commisson_amount'=>$commisson_amount,
                        //                 'commisson_perc'=>$thirdPartyBalData->commission,
                        //                 'invoice_id'=>$invoiceId,
                        //                 'received'=>$commisson_amount,
                        //                 'balance'=>$third_part_bal
                        //                 ]);
                                        
                        //      // 
                             
                        //      DB::table('3rd_party_commissions')
                        //                             ->where('id',$thirdPartyBalData->id)
                        //                             ->update(['balance'=>$third_part_bal]);
                        //  }
                        
                         // Book From Same Website
                          $thirdPartyData = CustomerSubcription::where('small_token',$cart_res->provider_id)->select('id','small_token','status')->first();
                         if(isset($thirdPartyData)){
                             $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                             
                             $thirdPartyBalData = DB::table('3rd_party_commissions')
                                                    ->where('customer_id',$thirdPartyData->id)
                                                    ->where('third_party_id',$userData->id)
                                                    ->first();
                             // 1 Calculate Commission
                             
                             // Check Type of Commission
                                if($thirdPartyBalData->commission_type == '%'){
                                    $commisson_amount    = ($cart_res->tour_total_price * $thirdPartyBalData->commission) / 100;
                                }else{
                                    $commisson_amount = $thirdPartyBalData->commission;
                                }
                                
                                $third_part_bal = $thirdPartyBalData->balance + $commisson_amount;
                                
                                DB::table('3rd_party_package_book_ledger')->insert([
                                        'package_id'=>$cart_res->tourId,
                                        'package_owner'=>$thirdPartyData->id,
                                        'package_request'=>$userData->id,
                                        'booking_amount'=>$cart_res->tour_total_price,
                                        'commisson_amount'=>$commisson_amount,
                                        'commisson_perc'=>$thirdPartyBalData->commission,
                                        'invoice_id'=>$invoiceId,
                                        'received'=>$commisson_amount,
                                        'balance'=>$third_part_bal
                                        ]);
                                        
                             // 
                             
                             DB::table('3rd_party_commissions')
                                                    ->where('id',$thirdPartyBalData->id)
                                                    ->update(['balance'=>$third_part_bal]);
                         }
                         
                         
                    }
                    }
                    
                    $notification_insert                            = new alhijaz_Notofication();
                     $notification_insert->type_of_notification_id   = $booking_id ?? ''; 
                     $notification_insert->customer_id               = $userData->id ?? ''; 
                     $notification_insert->type_of_notification      = 'package_booking' ?? ''; 
                     $notification_insert->generate_id               = $invoiceId ?? '';
                     $notification_insert->notification_creator_name = $agent_name ?? '';
                     $notification_insert->total_price               = $cart_res->tour_total_price ?? ''; 
                     $notification_insert->amount_paid               = 0; 
                     $notification_insert->remaining_price           = $cart_res->tour_total_price ?? ''; 
                     $notification_insert->notification_status       = '1' ?? ''; 
                     $notification_insert->save();
                     
                    //Update Packge Pax
                    $package_data = DB::table('tours')->where('id',$cart_res->tourId)->select('id','available_seats','available_single_seats','available_double_seats','available_triple_seats','available_quad_seats')->first();
               
                    // Double Total Pax
                        $total_doubal_pax = 0;
                        if(isset($cart_res->double_adults)){
                            $total_doubal_pax += (float)$cart_res->double_adults;
                        }
                        
                        if(isset($cart_res->double_childs)){
                            $total_doubal_pax += (float)$cart_res->double_childs;
                        }
                        
                        if(isset($cart_res->double_infant)){
                            $total_doubal_pax += (float)$cart_res->double_infant;
                        }
                        

                    // Triple Total Pax
                        $total_triple_pax = 0;
                        if(isset($cart_res->triple_adults)){
                            $total_triple_pax += $cart_res->triple_adults;
                        }
                        
                        if(isset($cart_res->triple_childs)){
                            $total_triple_pax += (float)$cart_res->triple_childs;
                        }
                        
                        if(isset($cart_res->triple_infant)){
                            $total_triple_pax += (float)$cart_res->triple_infant;
                        }
                        
                    // Quad Total Pax
                        $total_Quad_pax = 0;
                        if(isset($cart_res->quad_adults)){
                            $total_Quad_pax += $cart_res->quad_adults;
                        }
                        
                        if(isset($cart_res->quad_childs)){
                            $total_Quad_pax += (float)$cart_res->quad_childs;
                        }
                        
                        if(isset($cart_res->quad_infant)){
                            $total_Quad_pax += (float)$cart_res->quad_infant;
                        }
                        
                    // Without Pax Total
                        $total_without_pax = 0;
                        if(isset($cart_res->without_acc_adults)){
                            $total_without_pax += $cart_res->without_acc_adults;
                        }
                        
                        if(isset($cart_res->children)){
                            $total_without_pax += (float)$cart_res->children;
                        }
                        
                        if(isset($cart_res->infants)){
                            $total_without_pax += (float)$cart_res->infants;
                        }
                        
                    $total_pax = $total_without_pax + $total_doubal_pax + $total_triple_pax + $total_Quad_pax;
                    
                    
                    // Without Accomodation Pax
                    
                    // echo "package pax ".$package_data->available_double_seats." double pax $total_pax";
               
                    $available_seats = (float)$package_data->available_seats - (float)$total_pax;
                    $available_double_seats = (float)$package_data->available_double_seats - (float)$total_doubal_pax;
                    $available_triple_seats = (float)$package_data->available_triple_seats - (float)$total_triple_pax;
                    $available_quad_seats = (float)$package_data->available_quad_seats - (float)$total_Quad_pax;
                    
                    
                    // echo "aval double pax $available_double_seats ";
                    // print_r($package_data);
                    $package_data = DB::table('tours')->where('id',$package_data->id)->update([
                        'available_seats'=>$available_seats,
                        'available_double_seats'=>$available_double_seats,
                        'available_triple_seats'=>$available_triple_seats,
                        'available_quad_seats'=>$available_quad_seats,
                        ]);
                    // echo $package_data;
                    // die;
    
                }
             }else{
                //  print_r($cart_data);die;
                 foreach($cart_data as $cart_res){
                
                    $addtional_services = [];

                    if($cart_res->Additional_services_names_more !== 'null' AND $cart_res->Additional_services_names_more !== ''){
                        $services_names = json_decode($cart_res->Additional_services_names_more);
                        $services_persons_more = json_decode($cart_res->services_persons_more);
                        $services_price_more = json_decode($cart_res->services_price_more);
                        $services_days_more = json_decode($cart_res->services_days_more);
                        $services_dates_more = json_decode($cart_res->services_dates_more);
                        
                        $z = 0;
                        foreach($services_names as $service_res){
                             $service = [
                            'service'=>$service_res,
                            'service_price'=> $services_price_more[$z],
                            'service_type'=>'',
                            'person'=>$services_persons_more[$z],
                            'dates'=>$services_dates_more[$z],
                            'Service_Days'=>$services_days_more[$z],
                            'total_price'=>$services_days_more[$z] * $services_price_more[$z] * $services_persons_more[$z],
                            ];
                            
                            array_push($addtional_services,$service);
                        }
                    }
                    
                    if(isset($cart_res->Additional_services_Awaab)){
                        // dd($cart_res->Additional_services_Awaab);
                        $addtional_services = $cart_res->Additional_services_Awaab;
                    }else{
                        $addtional_services = json_encode($addtional_services);
                    }
                    
                    // print_r($addtional_services);
                    
                    // die();
                    
                    $start_date = date("Y-m-d", strtotime($cart_res->activity_select_date));
                    // dd($cart_res);
                    $Cart_details = new Cart_details;
    
                    $Cart_details->cart_total_data = json_encode($cart_res);
                    $Cart_details->tour_id = $cart_res->activtyId;
                    $Cart_details->generate_id = $cart_res->generate_id;
                    $Cart_details->tour_name = $cart_res->name;
                    $Cart_details->adults = $cart_res->adults;
                    $Cart_details->childs = $cart_res->children;
                    $Cart_details->adult_price = $cart_res->adult_price;
                    $Cart_details->child_price = $cart_res->child_price;
                    $Cart_details->activity_select_date = $start_date;
                    $Cart_details->tour_total_price = $cart_res->activity_total_price;
                    $Cart_details->total_service_price = $cart_res->total_service_price;
                    $Cart_details->price = $cart_res->price;
                    
                    $Cart_details->image = $cart_res->image;
                    $Cart_details->booking_id = $booking_id;
                    $Cart_details->invoice_no = $invoiceId;
                    $Cart_details->currency = $cart_res->currency;
                    $Cart_details->pakage_type = $cart_res->pakage_type;
                    
                    $Cart_details->Additional_services_names = $addtional_services;
                   
    
                    
                    $Cart_details->client_id = $userData->id;
                   
                    // dd($Cart_details);
                    $Cart_data = $Cart_details->save();
    
                }
             }

        
       
            DB::commit();
            // dd($invoiceId);
            return response()->json(['message'=>'success','booking_id'=>$tourObj->id,'invoice_id'=>$invoiceId]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error','booking_id'=> '']);
            // something went wrong
        }
    }
/*
|--------------------------------------------------------------------------
| Save Booking Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Agent Stats Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display agent stats for our client.
*/
function agents_stats(Request $request){
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $agent_lists = DB::table('Agents_detail')->where('customer_id',$userData->id)->get();
            
            $all_agents_data = [];
            foreach($agent_lists as $agent_res){
                 $agents_tour_booking = DB::table('cart_details')->where('agent_name',$agent_res->id)->get();
                 $agents_invoice_booking = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->id)->get();
                 
                 $agent_data = ['agent_id'=>$agent_res->id,
                                 'agent_name'=>$agent_res->agent_Name,
                                 'agent_company'=>$agent_res->company_name,
                                 'agents_tour_booking'=>sizeof($agents_tour_booking),
                                 'agents_invoice_booking'=>sizeof($agents_invoice_booking),
                                 ];
                    array_push($all_agents_data,$agent_data);             
                                 
            }
           
            // print_r($all_agents_data);
        }
        
        
         return response()->json(['message'=>'success','agent_data'=>$all_agents_data]);
        // print_r($request->all());
    }
/*
|--------------------------------------------------------------------------
| Agent Stats Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Get Agent Invoices Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display All agent Invoices for our client.
*/
function get_agent_upaid_all_inv(Request $request){

        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $package_inv_lists = DB::table('cart_details')
            ->where('agent_name',$request->agent_id)
            ->where('total_paid_amount', '<', DB::raw('tour_total_price'))
            ->get();
            
       
           $inv_lists = DB::table('add_manage_invoices')
            ->where('agent_Id',$request->agent_id)
            ->where('total_paid_amount', '<', DB::raw('total_sale_price_all_Services'))
            ->get();
            
            // print_r($agent_lists);
            return response()->json(['message'=>'success','package_inv_lists'=>$package_inv_lists,'inv_lists'=>$inv_lists]);
        }
        
        
         return response()->json(['message'=>'error','agent_data'=>'','inv_lists'=>'']);
        
        
    }
/*
|--------------------------------------------------------------------------
| Get Agent Invoices Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Adjust Over Pay Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to insert agent ledger and view booking payment recieve data from database for our client.
*/
function adjust_over_pay(Request $request){

        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            
            // DB::beginTransaction();
            //          try 
            //          {
                            $request_Data = json_decode($request->req_data);
                            if($request_Data->paid_type == 'package'){
                                $invoice_data = DB::table('cart_details')->where('invoice_no',$request_Data->invoice_no)->first();
                                
                                    $total_amount = $invoice_data->tour_total_price;
                                    $paid_amount = $invoice_data->total_paid_amount;
                                    $over_paid_amount = $invoice_data->over_paid_amount;
                                    
                                    $total_paid_amount = $paid_amount + $request_Data->recieved_Amount;
                                    $total_over_paid = 0;
                                    $over_paid_am = 0;
                                    if($total_paid_amount > $total_amount){
                                        $over_paid_am = $total_paid_amount - $total_amount;
                                        $total_over_paid = $over_paid_amount + $over_paid_am;
                                        
                                        $total_paid_amount = $total_paid_amount - $over_paid_am;
                                    }
                                    
                                    $result = DB::table('cart_details')->where('id',$invoice_data->id)->update([
                                        'total_paid_amount' => $total_paid_amount,
                                        'over_paid_amount' => $total_over_paid,
                                    ]);
                                    
                
                                            $agent_data = DB::table('Agents_detail')->where('id',$invoice_data->agent_name)->select('id','over_paid_am')->first();
                                            $agent_over_paid = $agent_data->over_paid_am - $request_Data->recieved_Amount;
                                            $agent_over_paid = $agent_over_paid + $over_paid_am;
                                            DB::table('Agents_detail')->where('id',$agent_data->id)->update(['over_paid_am'=>$agent_over_paid]);
                                     
                    
                                  $insert = new view_booking_payment_recieve();
                                  $insert->package_id       = $invoice_data->booking_id;
                                  $insert->tourId           = $invoice_data->tour_id;
                                  $insert->date             = $request_Data->date;
                                  $insert->customer_name    = '';
                                  $insert->package_title    = $invoice_data->tour_name;
                                  $insert->recieved_amount  = $request_Data->recieved_Amount;
                                  $insert->total_amount     = $invoice_data->price;
                                  $insert->remaining_amount = $invoice_data->price - $total_paid_amount;
                                  $insert->amount_paid      = $total_paid_amount;
                                  $insert->save();
                                  
                                   DB::table('agents_ledgers')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'payment_id'=>$insert->id,
                                        "payment"=>$request_Data->recieved_Amount,
                                        "package_invoice_no"=>$invoice_data->invoice_no,
                                        "total_amount"=>$total_amount,
                                        "paid_amount"=>$total_paid_amount,
                                        "remaining_amount"=>$total_amount - $total_paid_amount,
                                        'over_paid'=>$agent_over_paid,
                                        'date'=>$request_Data->date,
                                        'remarks'=>'Wallet',
                                     ]); 
                                                      
                                 return response()->json(['message'=>'success']);
                            }else{
                                $invoice_data          = DB::table('add_manage_invoices')->where('id',$request_Data->invoice_no)->first();
                            
                                    $total_amount = $invoice_data->total_sale_price_all_Services;
                                    $paid_amount = $invoice_data->total_paid_amount;
                                    $over_paid_amount = $invoice_data->over_paid_amount;
                                    
                                    $total_paid_amount = $paid_amount + $request_Data->recieved_Amount;
                                    $total_over_paid = 0;
                                    $over_paid_am = 0;
                                    if($total_paid_amount > $total_amount){
                                        $over_paid_am = $total_paid_amount - $total_amount;
                                        $total_over_paid = $over_paid_amount + $over_paid_am;
                                        
                                        $total_paid_amount = $total_paid_amount - $over_paid_am;
                                    }
                                    
                                    $add_in=DB::table('add_manage_invoices')->where('id',$request_Data->invoice_no)->update([
                                        'total_paid_amount' => $total_paid_amount,
                                        'over_paid_amount' => $total_over_paid,
                                    ]);
                                    
                                    //print_r($add_in);die();
                                    $agent_data = DB::table('Agents_detail')->where('id',$invoice_data->agent_Id)->select('id','over_paid_am')->first();
                                    $agent_over_paid = $agent_data->over_paid_am - $request_Data->recieved_Amount;
                                    $agent_over_paid = $agent_over_paid + $over_paid_am;
                                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['over_paid_am'=>$agent_over_paid]);
                                    
                        
                                    $insert = new pay_Invoice_Agent();
                                    $insert->invoice_Id         = $invoice_data->id;
                                    $insert->generate_id        = $invoice_data->generate_id;
                                    $insert->customer_id        = $invoice_data->customer_id;
                                    $insert->agent_Name         = $invoice_data->agent_Id;
                                    $insert->date               =  $request_Data->date;
                                    $insert->passenger_Name     = $invoice_data->lead_fname;
                                    $insert->total_Amount       = $total_amount;
                                    $insert->recieved_Amount    = $request_Data->recieved_Amount;
                                    $insert->remaining_Amount   = $total_amount - $total_paid_amount;
                                    $insert->amount_Paid        = $total_paid_amount;
                                    $insert->save();
                                    
                                     DB::table('agents_ledgers')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'payment_id'=>$insert->id,
                                        "payment"=>$request_Data->recieved_Amount,
                                        "invoice_no"=>$invoice_data->id,
                                        "total_amount"=>$total_amount,
                                        "paid_amount"=>$total_paid_amount,
                                        "remaining_amount"=>$total_amount - $total_paid_amount,
                                        'over_paid'=>$agent_over_paid,
                                        'date'=>$request_Data->date,
                                        'remarks'=>'Wallet',
                                     ]); 
                                    return response()->json(['message'=>'success']);
                            }
                            
                        DB::commit(); 
                        return response()->json(['message'=>'error','agent_data'=>'','inv_lists'=>'']);
                        // }
                        // catch (Throwable $e) {

                        //     DB::rollback();
                        //     return response()->json(['message'=>'error']);
                        // }
            
        }
        
        
         
        
        
    }
/*
|--------------------------------------------------------------------------
| Adjust Over Pay Function Ended
|--------------------------------------------------------------------------
*/

function manage_wallent_balance(Request $request){

        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $request_Data = json_decode($request->req_data);
            
                  DB::beginTransaction();
                        try {
                            
                                if(isset($request_Data->request_person) && $request_Data->request_person == 'flight_supplier'){
                                      $supplier_data = DB::table('supplier')->where('id',$request_Data->supplier_id)->first();
                                
                                        $supplier_balance = $supplier_data->wallet_amount;
                                        if($request_Data->transtype == 'Refund'){
                                            $supplier_balance -= $request_Data->payment_am;
                                        }else{
                                            $supplier_balance += $request_Data->payment_am;
                                        }
                                        
                                        DB::table('supplier')->where('id',$supplier_data->id)->update(['wallet_amount'=>$supplier_balance]);
                                        
                                        DB::table('flight_supplier_wallet_trans')->insert([
                                            'type'=>$request_Data->transtype,
                                            'amount'=>$request_Data->payment_am,
                                            'balance'=>$supplier_balance,
                                            'pay_method'=>$request_Data->payment_method,
                                            'date'=>$request_Data->payment_date,
                                            'trans_id'=>$request_Data->transcation_id,
                                            'account_no'=>$request_Data->account_no,
                                            'supplier_id'=>$request_Data->supplier_id
                                            ]);
                                        
                                        DB::commit();
                                }else if(isset($request_Data->request_person) && $request_Data->request_person == 'hotel_supplier'){
                                      $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request_Data->supplier_id)->first();
                                
                                        $supplier_balance = $supplier_data->wallat_balance;
                                        if($request_Data->transtype == 'Refund'){
                                            $supplier_balance -= $request_Data->payment_am;
                                        }else{
                                            $supplier_balance += $request_Data->payment_am;
                                        }
                                        
                                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['wallat_balance'=>$supplier_balance]);
                                        
                                        DB::table('hotel_supplier_wallet_trans')->insert([
                                            'type'=>$request_Data->transtype,
                                            'amount'=>$request_Data->payment_am,
                                            'balance'=>$supplier_balance,
                                            'pay_method'=>$request_Data->payment_method,
                                            'date'=>$request_Data->payment_date,
                                            'trans_id'=>$request_Data->transcation_id,
                                            'account_no'=>$request_Data->account_no,
                                            'supplier_id'=>$request_Data->supplier_id
                                            ]);
                                        
                                        DB::commit();
                                }else if(isset($request_Data->request_person) && $request_Data->request_person == 'transfer_supplier'){
                                      $supplier_data = DB::table('transfer_Invoice_Supplier')->where('id',$request_Data->supplier_id)->first();
                                
                                        $supplier_balance = $supplier_data->wallat_balance;
                                        if($request_Data->transtype == 'Refund'){
                                            $supplier_balance -= $request_Data->payment_am;
                                        }else{
                                            $supplier_balance += $request_Data->payment_am;
                                        }
                                        
                                        DB::table('transfer_Invoice_Supplier')->where('id',$supplier_data->id)->update(['wallat_balance'=>$supplier_balance]);
                                        
                                        DB::table('transfer_supplier_wallet_trans')->insert([
                                            'type'=>$request_Data->transtype,
                                            'amount'=>$request_Data->payment_am,
                                            'balance'=>$supplier_balance,
                                            'pay_method'=>$request_Data->payment_method,
                                            'date'=>$request_Data->payment_date,
                                            'trans_id'=>$request_Data->transcation_id,
                                            'account_no'=>$request_Data->account_no,
                                            'supplier_id'=>$request_Data->supplier_id
                                            ]);
                                        
                                        DB::commit();
                                }else{
                                      $agent_lists = DB::table('Agents_detail')->where('id',$request_Data->agent_id)->first();
                                
                                        $agent_balance = $agent_lists->over_paid_am;
                                        if($request_Data->transtype == 'Refund'){
                                            $agent_balance -= $request_Data->payment_am;
                                        }else{
                                            $agent_balance += $request_Data->payment_am;
                                        }
                                        
                                        DB::table('Agents_detail')->where('id',$agent_lists->id)->update(['over_paid_am'=>$agent_balance]);
                                        
                                       
                                            
                                            $trans_id = DB::table('agent_wallet_trans')->insertGetId([
                                            'type'=>$request_Data->transtype,
                                            'amount'=>$request_Data->payment_am,
                                            'balance'=>$agent_balance,
                                            'pay_method'=>$request_Data->payment_method,
                                            'date'=>$request_Data->payment_date,
                                            'trans_id'=>$request_Data->transcation_id,
                                            'account_no'=>$request_Data->account_no
                                            ]);
                                            
                                        if($request_Data->transtype == 'Deposit'){
                                            $index = 'deposit_id';
                                            $index_pay = 'payment';
                                        }else{
                                            $index = 'refund_id';
                                            $index_pay = 'received';
                                        }    
                                        
                                        DB::table('agents_ledgers')->insert([
                                            'agent_id'=>$agent_lists->id,
                                            "$index"=>$trans_id,
                                            "$index_pay"=>$request_Data->payment_am,
                                            'over_paid'=>$agent_balance,
                                            'date'=>$request_Data->payment_date,
                                            ]);
                                        
                                        DB::commit();
                                }
                              
          
                            
                            
                            return response()->json(['status'=>'success','message'=>'Balance is Updated Successfully']);
                        } catch (\Exception $e) {
                            DB::rollback();
                            // echo $e;die;
                            return response()->json(['status'=>'error','message'=>'Something Went Wrong try Again']);
                            // something went wrong
                        }
            
        }
    }
function agents_stats_details(Request $request){
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $agent_lists = DB::table('Agents_detail')->where('id',$request->agent_id)->get();
            
            $all_agents_data = [];
            foreach($agent_lists as $agent_res){
                $agents_tour_booking    = DB::table('cart_details')->where('agent_name',$agent_res->id)->get();
                $agents_invoice_booking = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->id)->select('generate_id','currency_Type_AC','total_sale_price_AC','currency_conversion','total_sale_price_all_Services','total_paid_amount','over_paid_amount','accomodation_details','accomodation_details_more','markup_details','more_markup_details','no_of_pax_days','id','agent_Id','currency_symbol')->get();
                
                $booking_all_data = [];
                foreach($agents_tour_booking as $tour_res){
                    
                    $tours_costing = DB::table('tours_2')->where('tour_id',$tour_res->tour_id)->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')->first();
                    
                    $cart_all_data = json_decode($tour_res->cart_total_data);
                    
                    $grand_profit = 0;
                    $grand_cost = 0;
                    $grand_sale = 0;
                    // Profit From Double Adults
                     
                        if($cart_all_data->double_adults > 0){
                            $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                            $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                            $grand_profit += $double_profit;
                            $grand_cost += $double_adult_total_cost;
                            $grand_sale += $cart_all_data->double_adult_total;
                        }
                     
                     // Profit From Triple Adults
                     
                        if($cart_all_data->triple_adults > 0){
                            $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                            $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                            $grand_profit += $triple_profit;
                            $grand_cost += $triple_adult_total_cost;
                            $grand_sale += $cart_all_data->triple_adult_total;
                        }
                        

                     // Profit From Quad Adults
                     
                        if($cart_all_data->quad_adults > 0){
                            $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                            $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                            $grand_profit += $quad_profit;
                             $grand_cost += $quad_adult_total_cost;
                            $grand_sale += $cart_all_data->quad_adult_total;
                        }
                     
                     // Profit From Without Acc
                     
                        if($cart_all_data->without_acc_adults > 0){
                            $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                            $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                            $grand_profit += $without_acc_profit;
                            $grand_cost += $without_acc_adult_total_cost;
                            $grand_sale += $cart_all_data->without_acc_adult_total;
                        }
                     
                     // Profit From Double Childs
                     
                        if($cart_all_data->double_childs > 0){
                            $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                            $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                            $grand_profit += $double_profit;
                            $grand_cost += $double_child_total_cost;
                            $grand_sale += $cart_all_data->double_childs_total;
                        }
                     
                     // Profit From Triple Childs
                     
                       if($cart_all_data->triple_childs > 0){
                            $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                            $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                            $grand_profit += $triple_profit;
                            $grand_cost += $triple_child_total_cost;
                            $grand_sale += $cart_all_data->triple_childs_total;
                        }
                        
                     // Profit From Quad Childs
                     
                        if($cart_all_data->quad_childs > 0){
                            $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                            $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                            $grand_profit += $quad_profit;
                            $grand_cost += $quad_child_total_cost;
                            $grand_sale += $cart_all_data->quad_child_total;
                        }
                     
                     // Profit From Without Acc Child

                        if($cart_all_data->children > 0){
                            $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                            $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                            $grand_profit += $without_acc_profit;
                            $grand_cost += $without_acc_child_total_cost;
                            $grand_sale += $cart_all_data->without_acc_child_total;
                        }

                    // Profit From Double Infant
                        if($cart_all_data->double_infant > 0){
                            $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                            $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                            $grand_profit += $double_profit;
                             $grand_cost += $double_infant_total_cost;
                            $grand_sale += $cart_all_data->double_infant_total;
                        }
                     
                     // Profit From Triple Infant
                     
                        if($cart_all_data->triple_infant > 0){
                            $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                            $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                            $grand_profit += $triple_profit;
                             $grand_cost += $triple_infant_total_cost;
                            $grand_sale += $cart_all_data->triple_infant_total;
                        }
                     
                     // Profit From Quad Infant
                     
                        if($cart_all_data->quad_infant > 0){
                            $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                            $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                            $grand_profit += $quad_profit;
                             $grand_cost += $quad_infant_total_cost;
                            $grand_sale += $cart_all_data->quad_infant_total;
                        }
                     
                     // Profit From Without Acc Infant  
                     
                      if($cart_all_data->infants > 0){
                            $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                            $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                            $grand_profit += $without_acc_profit;
                            $grand_cost += $without_acc_infant_total_cost;
                            $grand_sale += $cart_all_data->without_acc_infant_total;
                      }
                      
                      $over_all_dis = 0;
                    //   echo "Grand Total Profit is $grand_profit "; 
                      if($cart_all_data->discount_type == 'amount'){
                          $final_profit = $grand_profit - $cart_all_data->discount_enter_am;
                      }else{
                           $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                           $final_profit = $grand_profit - $discunt_am_over_all;
                      }
                     
                      
                     
                    //  echo "Grand Total Profit is $final_profit";
                    //  print_r($cart_all_data);
                    
                    $commission_add = '';
                    if(isset($cart_all_data->agent_commsion_add_total)){
                        $commission_add = $cart_all_data->agent_commsion_add_total;
                    }

                     $booking_data = [
                            'invoice_No'=>$tour_res->invoice_no,
                            'invoice_id'=>$tour_res->invoice_no,
                            'booking_id'=>$tour_res->booking_id,
                            'tour_id'=>$tour_res->tour_id,
                            'price'=>$tour_res->tour_total_price,
                            'paid_amount'=>$tour_res->total_paid_amount,
                            'remaing_amount'=> $tour_res->tour_total_price - $tour_res->total_paid_amount,
                            'over_paid_amount'=> $tour_res->over_paid_amount,
                            'tour_name'=>$cart_all_data->name,
                            'profit'=>$final_profit,
                            'discount_am'=>$cart_all_data->discount_Price,
                            'total_cost'=>$grand_cost,
                            'total_sale'=>$grand_sale,
                            'commission_am'=>$cart_all_data->agent_commsion_am,
                            'agent_commsion_add_total'=>$commission_add,
                            'currency'=>$tour_res->currency,
                         ];
                         
                      array_push($booking_all_data,$booking_data);
                 }
                 
                $invoices_all_data = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    
                    $accomodation = json_decode($agent_inv_res->accomodation_details);
                    $accomodation_more = json_decode($agent_inv_res->accomodation_details_more);
                    $markup_details = json_decode($agent_inv_res->markup_details);
                    $more_markup_details = json_decode($agent_inv_res->more_markup_details);
                     
                    // Caluclate Flight Price 
                    $grand_cost = 0;
                    $grand_sale = 0;
                    $flight_cost = 0;
                    $flight_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                            $flight_cost = $mark_res->without_markup_price; 
                            $flight_sale = $mark_res->markup_price; 
                        }
                    }
                            
                    $flight_total_cost = (float)$flight_cost * (float)$agent_inv_res->no_of_pax_days;
                    $flight_total_sale = (float)$flight_sale * (float)$agent_inv_res->no_of_pax_days;
                    
                    $flight_profit = $flight_total_sale - $flight_total_cost;
                    
                    $grand_cost += $flight_total_cost;
                    $grand_sale += $flight_total_sale;
                    
                    // Caluclate Visa Price 
                    $visa_cost = 0;
                    $visa_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                            $visa_cost = $mark_res->without_markup_price; 
                            $visa_sale = $mark_res->markup_price; 
                        }
                    }
                    $visa_total_cost = (float)$visa_cost * (float)$agent_inv_res->no_of_pax_days;
                    $visa_total_sale = (float)$visa_sale * (float)$agent_inv_res->no_of_pax_days;
                    $visa_profit = $visa_total_sale - $visa_total_cost;
                    $grand_cost += $visa_total_cost;
                    $grand_sale += $visa_total_sale;

                    // Caluclate Transportation Price
                    $trans_cost = 0;
                    $trans_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                            $trans_cost = $mark_res->without_markup_price; 
                            $trans_sale = $mark_res->markup_price; 
                        }
                    }
                    $trans_total_cost = (float)$trans_cost * (float)$agent_inv_res->no_of_pax_days;
                    $trans_total_sale = (float)$trans_sale * (float)$agent_inv_res->no_of_pax_days;
                    $trans_profit = $trans_total_sale - $trans_total_cost;
                    $grand_cost += $trans_total_cost;
                    $grand_sale += $trans_total_sale;

                    // Caluclate Double Room Price
                    $double_total_cost = 0;
                    $double_total_sale = 0;
                    $double_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Double'){
                                (float)$double_cost = $accmod_res->acc_total_amount; 
                                (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                                
                                 $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                                 $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                                 $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                                 $double_total_profit = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Double'){
                                $double_cost = $accmod_res->more_acc_total_amount; 
                                $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                                $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                                $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                                $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                                $double_total_profit = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    $grand_cost += $double_total_cost;
                    $grand_sale += $double_total_sale;
                    
                    // Caluclate Triple Room Price
                    $triple_total_cost = 0;
                    $triple_total_sale = 0;
                    $triple_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->acc_total_amount; 
                                $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                                $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    $grand_cost += $triple_total_cost;
                    $grand_sale += $triple_total_sale;
                     
                    // Caluclate Quad Room Price
                    $quad_total_cost = 0;
                    $quad_total_sale = 0;
                    $quad_total_profit = 0;
                    if(isset($accomodation)){
                                foreach($accomodation as $accmod_res){
                                    if($accmod_res->acc_type == 'Quad'){
                                        $quad_cost = $accmod_res->acc_total_amount; 
                                        $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                        
                                         $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                         $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                         $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                         $quad_total_profit = $quad_total_profit + $quad_profit;
                                    }
                                }
                             }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Quad'){
                                $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                                $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                                $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                                $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                                $quad_total_profit = $quad_total_profit + $quad_profit;
                            }
                        }
                    }
                    $grand_cost += $quad_total_cost;
                    $grand_sale += $quad_total_sale;
                    
                    // Caluclate Final Price
                    $Final_inv_price = $flight_total_sale + $visa_total_sale + $trans_total_sale + $double_total_sale + $triple_total_sale + $quad_total_sale;
                    $Final_inv_profit = $flight_profit + $visa_profit + $trans_profit + $double_total_profit + $triple_total_profit + $quad_total_profit;
                    
                    $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    
                    $select_curreny_data = explode(' ', $agent_inv_res->currency_conversion);
                    
                    $invoice_curreny = "";
                    $agent_curreny_data = explode(' ', $agent_inv_res->currency_Type_AC);
                    if(isset($select_curreny_data[2])){
                        $invoice_curreny = $select_curreny_data[2];
                    }
                    
                    $agent_curreny = $invoice_curreny;
                    $agent_curreny_data = explode(' ', $agent_inv_res->currency_Type_AC);
                    if(isset($agent_curreny_data[2])){
                        $agent_curreny = $agent_curreny_data[2];
                    }
                    
                    
                    $inv_single_data = [
                        'invoice_No'=>$agent_inv_res->generate_id,
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'invoice_curreny'=> $invoice_curreny,
                        'agent_curreny'=>$agent_curreny,
                        'agent_total'=>$agent_inv_res->total_sale_price_AC,
                    ];
                    // dd($inv_single_data);
                    array_push($invoices_all_data,$inv_single_data);
                     
                }
                
                $invoice_Acc_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $accomodation       = json_decode($agent_inv_res->accomodation_details);
                    $accomodation_more  = json_decode($agent_inv_res->accomodation_details_more);
                     
                    // Caluclate Double Room Price
                    $double_total_cost = 0;
                    $double_total_sale = 0;
                    $double_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Double'){
                                (float)$double_cost = $accmod_res->acc_total_amount; 
                                (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                                
                                 $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                                 $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                                 $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                                 $double_total_profit = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Double'){
                                $double_cost = $accmod_res->more_acc_total_amount; 
                                $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                                $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                                $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                                $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                                $double_total_profit = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    $grand_cost += $double_total_cost;
                    $grand_sale += $double_total_sale;
                    
                    // Caluclate Triple Room Price
                    $triple_total_cost = 0;
                    $triple_total_sale = 0;
                    $triple_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->acc_total_amount; 
                                $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                                $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    $grand_cost += $triple_total_cost;
                    $grand_sale += $triple_total_sale;
                     
                    // Caluclate Quad Room Price
                    $quad_total_cost = 0;
                    $quad_total_sale = 0;
                    $quad_total_profit = 0;
                    if(isset($accomodation)){
                                foreach($accomodation as $accmod_res){
                                    if($accmod_res->acc_type == 'Quad'){
                                        $quad_cost = $accmod_res->acc_total_amount; 
                                        $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                        
                                         $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                         $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                         $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                         $quad_total_profit = $quad_total_profit + $quad_profit;
                                    }
                                }
                             }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Quad'){
                                $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                                $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                                $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                                $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                                $quad_total_profit = $quad_total_profit + $quad_profit;
                            }
                        }
                    }
                    $grand_cost += $quad_total_cost;
                    $grand_sale += $quad_total_sale;
                    
                    // Caluclate Final Price
                    $Final_inv_price    = $double_total_sale + $triple_total_sale + $quad_total_sale;
                    $Final_inv_profit   = $double_total_profit + $triple_total_profit + $quad_total_profit;
                    
                    $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'        => $agent_inv_res->id,
                        'price'             => $agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'       => $agent_inv_res->total_paid_amount,
                        'remaing_amount'    => $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'  => $agent_inv_res->over_paid_amount,
                        'profit'            => $Final_inv_profit,
                        'total_cost'        => $grand_cost,
                        'total_sale'        => $grand_sale,
                        'currency'          => $agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Acc_details,$inv_single_data);
                }
                
                $invoice_Flight_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $markup_details         = json_decode($agent_inv_res->markup_details);
                    $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
                     
                    // Caluclate Flight Price 
                    $grand_cost = 0;
                    $grand_sale = 0;
                    $flight_cost = 0;
                    $flight_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                            $flight_cost = $mark_res->without_markup_price; 
                            $flight_sale = $mark_res->markup_price; 
                        }
                    }
                            
                    $flight_total_cost = (float)$flight_cost * (float)$agent_inv_res->no_of_pax_days;
                    $flight_total_sale = (float)$flight_sale * (float)$agent_inv_res->no_of_pax_days;
                    
                    $flight_profit = $flight_total_sale - $flight_total_cost;
                    
                    $grand_cost += $flight_total_cost;
                    $grand_sale += $flight_total_sale;
                    
                    // Caluclate Final Price
                    $Final_inv_price    = $flight_total_sale;
                    $Final_inv_profit   = $flight_profit;
                    
                    $invoice_payments   = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount  = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'currency'=>$agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Flight_details,$inv_single_data);
                     
                }
                
                $invoice_Visa_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $markup_details         = json_decode($agent_inv_res->markup_details);
                    $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
                   // Caluclate Visa Price 
                    $visa_cost = 0;
                    $visa_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                            $visa_cost = $mark_res->without_markup_price; 
                            $visa_sale = $mark_res->markup_price; 
                        }
                    }
                    $visa_total_cost = (float)$visa_cost * (float)$agent_inv_res->no_of_pax_days;
                    $visa_total_sale = (float)$visa_sale * (float)$agent_inv_res->no_of_pax_days;
                    $visa_profit = $visa_total_sale - $visa_total_cost;
                    $grand_cost += $visa_total_cost;
                    $grand_sale += $visa_total_sale;

                    // Caluclate Final Price
                    $Final_inv_price    = $visa_total_sale;
                    $Final_inv_profit   = $visa_profit;
                    
                    $invoice_payments   = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount  = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'currency'=>$agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Visa_details,$inv_single_data);
                     
                }
                
                $invoice_Transportation_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $markup_details         = json_decode($agent_inv_res->markup_details);
                    $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
                   
                    // Caluclate Transportation Price
                    $trans_cost = 0;
                    $trans_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                            $trans_cost = $mark_res->without_markup_price; 
                            $trans_sale = $mark_res->markup_price; 
                        }
                    }
                    $trans_total_cost = (float)$trans_cost * (float)$agent_inv_res->no_of_pax_days;
                    $trans_total_sale = (float)$trans_sale * (float)$agent_inv_res->no_of_pax_days;
                    $trans_profit = $trans_total_sale - $trans_total_cost;
                    $grand_cost += $trans_total_cost;
                    $grand_sale += $trans_total_sale;

                    // Caluclate Final Price
                    $Final_inv_price    = $trans_total_sale;
                    $Final_inv_profit   = $trans_profit;
                    
                    $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'currency'=>$agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Transportation_details,$inv_single_data);
                     
                }
                
                $agent_data = [
                        'agent_id'=>$agent_res->id,
                        'agent_name'=>$agent_res->agent_Name,
                        'agent_over_paid'=>$agent_res->over_paid_am,
                        'agent_company'=>$agent_res->company_name,
                        'agents_tour_booking'=>$booking_all_data,
                        'agents_invoices_booking'=>$invoices_all_data,
                        'invoice_Acc_details'               => $invoice_Acc_details,
                        'invoice_Flight_details'            => $invoice_Flight_details,
                        'invoice_Visa_details'              => $invoice_Visa_details,
                        'invoice_Transportation_details'    => $invoice_Transportation_details,
                    ];
                array_push($all_agents_data,$agent_data);         
            }
        }
        
        $separate_Revenue_accomodation      = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['accomodation_tab'])
                                                ->sum('total_sale_price_all_Services');

        $separate_Revenue_flight            = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['flights_tab'])
                                                ->sum('total_sale_price_all_Services');
        
        $separate_Revenue_visa              = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['visa_tab'])
                                                ->sum('total_sale_price_all_Services');
        
        $separate_Revenue_transportation    = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['transportation_tab'])
                                                ->sum('total_sale_price_all_Services');
                                                
        $separate_package_booking           = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                                ->where('tours_bookings.customer_id',$request->customer_id)
                                                ->where('cart_details.agent_name',$request->agent_id)
                                                ->where('cart_details.pakage_type','tour')
                                                ->get();
        if(count($separate_package_booking) > 0){
            foreach($separate_package_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $separate_package_grand_profit = 0;
                $separate_package_cost_price   = 0;
                $separate_package_Revenue      = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $separate_package_Revenue = 0;
        }
        
        $separate_activity_booking          = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                                ->where('tours_bookings.customer_id',$request->customer_id)
                                                ->where('cart_details.agent_name',$request->agent_id)
                                                ->where('cart_details.pakage_type','activity')
                                                ->get();
        if(count($separate_activity_booking) > 0){
            foreach($separate_activity_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $separate_activity_grand_profit = 0;
                $separate_activity_cost_price   = 0;
                $separate_activity_Revenue      = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $separate_activity_Revenue = 0;
        }
        
        return response()->json(['message'=>'success',
            'agent_data'                        => $all_agents_data,
            'separate_Revenue_accomodation'     => $separate_Revenue_accomodation,
            'separate_Revenue_flight'           => $separate_Revenue_flight,
            'separate_Revenue_visa'             => $separate_Revenue_visa,
            'separate_Revenue_transportation'   => $separate_Revenue_transportation,
            'separate_package_Revenue'          => $separate_package_Revenue,
            'separate_activity_Revenue'         => $separate_activity_Revenue,
        ]);
    }
    
    function createCustomerYearlyGraph($customer_id,$request){
        $series_data                    = [];
        $categories_data                = [];
        if(isset($request->season_Id) && $request->season_Id > 0 && $request->season_Id != 'all_Seasons'){
            $season                     = DB::table('add_Seasons')->where('id', $request->season_Id)->first();
            $startDate                  = Carbon::parse($season->start_Date);
            $endDate                    = Carbon::parse($season->end_Date);
            $monthsData                 = [];
            while ($startDate->lte($endDate)) {
                $startOfMonth           = $startDate->copy()->startOfMonth();
                $endOfMonth             = $startDate->copy()->endOfMonth();
                
                if ($endOfMonth->gt($endDate)) {
                    $endOfMonth         = $endDate;
                }
                
                $categories_data[]      = $startOfMonth->format('F');
                $monthsData[]           = (Object)[
                    'month'             => $startOfMonth->month,
                    'start_date'        => $startOfMonth->format('Y-m-d'),
                    'end_date'          => $endOfMonth->format('Y-m-d'),
                ];
                $startDate->addMonth();
            }
        }else{
            $currentYear                = date('Y');
            $monthsData                 = [];
            for ($month = 1; $month <= 12; $month++) {
                $startOfMonth           = Carbon::create($currentYear, $month, 1)->startOfMonth();
                $endOfMonth             = Carbon::create($currentYear, $month, 1)->endOfMonth();
                $categories_data[]      = $startOfMonth->format('F');
                $startOfMonth           = $startOfMonth->format('Y-m-d');
                $endOfMonth             = $endOfMonth->format('Y-m-d');
                $monthsData[]           = (Object)[
                    'month'             => $month,
                    'start_date'        => $startOfMonth,
                    'end_date'          => $endOfMonth,
                ];
            }
        }
        $customer_booking_data          = [];
        foreach($monthsData as $month_res){
            $customerInvoices           = DB::table('add_manage_invoices')->where('booking_customer_id',$customer_id)
                                            ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                            ->Sum('total_sale_price_all_Services');
            $customerPackageBookings    =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$customer_id")
                                            ->whereDate('cart_details.created_at','>=', $month_res->start_date)->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                            ->Sum('tour_total_price');
            $total_booking_price        = $customerInvoices + $customerPackageBookings;
            $customer_booking_data[]    = floor($total_booking_price * 100) / 100;
        }
        $series_data[] = [
            'data'                      => $customer_booking_data
        ];
        return [
            'series_data'               => $series_data,
            'categories_data'           => $categories_data,
        ];
    }
    
    function createAgentYearlyGraph($agent_id,$request){
        $series_data                = [];
        $categories_data            = [];
        if(isset($request->season_Id) && $request->season_Id > 0 && $request->season_Id != 'all_Seasons'){
            $season                 = DB::table('add_Seasons')->where('id', $request->season_Id)->first();
            $startDate              = Carbon::parse($season->start_Date);
            $endDate                = Carbon::parse($season->end_Date);
            $monthsData             = [];
            while ($startDate->lte($endDate)) {
                $startOfMonth       = $startDate->copy()->startOfMonth();
                $endOfMonth         = $startDate->copy()->endOfMonth();
                
                if ($endOfMonth->gt($endDate)) {
                    $endOfMonth     = $endDate;
                }
                
                $categories_data[]  = $startOfMonth->format('F');
                $monthsData[]       = (Object)[
                    'month'         => $startOfMonth->month,
                    'start_date'    => $startOfMonth->format('Y-m-d'),
                    'end_date'      => $endOfMonth->format('Y-m-d'),
                ];
                $startDate->addMonth();
            }
        }else{
            $currentYear            = date('Y');
            $monthsData             = [];
            for ($month = 1; $month <= 12; $month++) {
                $startOfMonth       = Carbon::create($currentYear, $month, 1)->startOfMonth();
                $endOfMonth         = Carbon::create($currentYear, $month, 1)->endOfMonth();
                $categories_data[]  = $startOfMonth->format('F');
                $startOfMonth       = $startOfMonth->format('Y-m-d');
                $endOfMonth         = $endOfMonth->format('Y-m-d');
                $monthsData[]       = (Object)[
                    'month'         => $month,
                    'start_date'    => $startOfMonth,
                    'end_date'      => $endOfMonth,
                ];
            }
        }
        
        $agent_booking_data         = [];
        foreach($monthsData as $month_res){
            $agentInvoices          =   DB::table('add_manage_invoices')->where('agent_Id',$agent_id)
                                            ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                            ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                            ->Sum('total_sale_price_all_Services');
            $agentPackageBookings   =   DB::table('cart_details')->where('agent_name',"$agent_id")
                                            ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                            ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                            ->Sum('tour_total_price');
            $total_booking_price    = $agentInvoices + $agentPackageBookings;
            $agent_booking_data[]   = floor($total_booking_price * 100) / 100;
        }
        $series_data[] = [
            'data'                  => $agent_booking_data
        ];
        return [
            'series_data'           => $series_data,
            'categories_data'       => $categories_data,
        ];
    }
    
    public static function customer_Booking_Details_Season_Working($all_data,$request){
        $today_Date             = date('Y-m-d');
        // $season_Details         = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            
            $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record['created_at'])) {
                    return false;
                }
                
                if($record['created_at'] != null && $record['created_at'] != ''){
                    $created_at     = Carbon::parse($record['created_at']);
                    $start_Date     = Carbon::parse($start_Date);
                    $end_Date       = Carbon::parse($end_Date);
                    return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                }else{
                    return false;
                }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public static function customer_all_bookings_details($booking_customer_id,$request){
        $customer_lists                 = DB::table('booking_customers')->where('id',$booking_customer_id)->get();
        $all_customers_data             = [];
        
        foreach($customer_lists as $customer_res){
            $customers_tour_booking     = DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$booking_customer_id")->get();
            
            $customers_invoice_booking  = DB::table('add_manage_invoices')->where('booking_customer_id',$booking_customer_id)->get();
            
            $booking_all_data           = [];
            foreach($customers_tour_booking as $tour_res){
                $tours_costing      = DB::table('tours_2')->where('tour_id',$tour_res->tour_id)->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')->first();
                $booking_details    = DB::table('tours_bookings')->where('invoice_no',$tour_res->invoice_no)->first();
                $cart_all_data      = json_decode($tour_res->cart_total_data);
                
                $grand_profit = 0;
                $grand_cost = 0;
                $grand_sale = 0;
                // Profit From Double Adults
                 
                    if($cart_all_data->double_adults > 0){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_adult_total_cost;
                        $grand_sale += $cart_all_data->double_adult_total;
                    }
                 
                 // Profit From Triple Adults
                 
                    if($cart_all_data->triple_adults > 0){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_adult_total_cost;
                        $grand_sale += $cart_all_data->triple_adult_total;
                    }
                    
    
                 // Profit From Quad Adults
                 
                    if($cart_all_data->quad_adults > 0){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_adult_total_cost;
                        $grand_sale += $cart_all_data->quad_adult_total;
                    }
                 
                 // Profit From Without Acc
                 
                    if($cart_all_data->without_acc_adults > 0){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_adult_total_cost;
                        $grand_sale += $cart_all_data->without_acc_adult_total;
                    }
                 
                 // Profit From Double Childs
                 
                    if($cart_all_data->double_childs > 0){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_child_total_cost;
                        $grand_sale += $cart_all_data->double_childs_total;
                    }
                 
                 // Profit From Triple Childs
                 
                   if($cart_all_data->triple_childs > 0){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_child_total_cost;
                        $grand_sale += $cart_all_data->triple_childs_total;
                    }
                    
                 // Profit From Quad Childs
                 
                    if($cart_all_data->quad_childs > 0){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                        $grand_profit += $quad_profit;
                        $grand_cost += $quad_child_total_cost;
                        $grand_sale += $cart_all_data->quad_child_total;
                    }
                 
                 // Profit From Without Acc Child
    
                    if($cart_all_data->children > 0){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_child_total_cost;
                        $grand_sale += $cart_all_data->without_acc_child_total;
                    }
    
                // Profit From Double Infant
                    if($cart_all_data->double_infant > 0){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                        $grand_profit += $double_profit;
                         $grand_cost += $double_infant_total_cost;
                        $grand_sale += $cart_all_data->double_infant_total;
                    }
                 
                 // Profit From Triple Infant
                 
                    if($cart_all_data->triple_infant > 0){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                        $grand_profit += $triple_profit;
                         $grand_cost += $triple_infant_total_cost;
                        $grand_sale += $cart_all_data->triple_infant_total;
                    }
                 
                 // Profit From Quad Infant
                 
                    if($cart_all_data->quad_infant > 0){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_infant_total_cost;
                        $grand_sale += $cart_all_data->quad_infant_total;
                    }
                 
                 // Profit From Without Acc Infant  
                 
                  if($cart_all_data->infants > 0){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_infant_total_cost;
                        $grand_sale += $cart_all_data->without_acc_infant_total;
                  }
                  
                    $over_all_dis = 0;
                //   echo "Grand Total Profit is $grand_profit "; 
                    if($cart_all_data->discount_type == 'amount'){
                      $final_profit = $grand_profit - $cart_all_data->discount_enter_am;
                    }else{
                       $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                       $final_profit = $grand_profit - $discunt_am_over_all;
                    }
                 
                //  echo "Grand Total Profit is $final_profit";
                //  print_r($cart_all_data);
                
                $commission_add = '';
                if(isset($cart_all_data->customer_commsion_add_total)){
                    $commission_add = $cart_all_data->customer_commsion_add_total;
                }
                
                // dd($grand_cost);
                
                $booking_data = [
                        'booking_type' => 'Package',
                        'invoice_id'=>$tour_res->invoice_no,
                        'booking_id'=>$tour_res->booking_id,
                        'tour_id'=>$tour_res->tour_id,
                        'price'=>$tour_res->tour_total_price,
                        'paid_amount'=>$tour_res->total_paid_amount,
                        'remaing_amount'=> $tour_res->tour_total_price - $tour_res->total_paid_amount,
                        'over_paid_amount'=> $tour_res->over_paid_amount,
                        'tour_name'=>$cart_all_data->name,
                        'profit'=>$final_profit,
                        'discount_am'=>$cart_all_data->discount_Price,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'created_at'=>$tour_res->created_at,
                        'commission_am'=>'',
                        'customer_commsion_add_total'=>$commission_add,
                        'currency'=>$tour_res->currency,
                        'confirm'=>$tour_res->confirm,
                        'lead_Name'=>$booking_details->passenger_name,
                        'passenger_detail'=>$booking_details->passenger_detail,
                     ];
                     
                  array_push($booking_all_data,$booking_data);
             }
            
            $invoices_all_data          = [];
            foreach($customers_invoice_booking as $customer_inv_res){
                
                $accomodation = json_decode($customer_inv_res->accomodation_details);
                $accomodation_more = json_decode($customer_inv_res->accomodation_details_more);
                $markup_details = json_decode($customer_inv_res->markup_details);
                $more_markup_details = json_decode($customer_inv_res->more_markup_details);
                 
                // Caluclate Flight Price 
                $grand_cost = 0;
                $grand_sale = 0;
                $flight_cost = 0;
                $flight_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                        $flight_cost = $mark_res->without_markup_price; 
                        $flight_sale = $mark_res->markup_price; 
                    }
                }
                        
                $flight_total_cost = (float)$flight_cost * (float)$customer_inv_res->no_of_pax_days;
                $flight_total_sale = (float)$flight_sale * (float)$customer_inv_res->no_of_pax_days;
                
                $flight_profit = $flight_total_sale - $flight_total_cost;
                
                $grand_cost += $flight_total_cost;
                $grand_sale += $flight_total_sale;
                
                // Caluclate Visa Price 
                $visa_cost = 0;
                $visa_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                        $visa_cost = $mark_res->without_markup_price; 
                        $visa_sale = $mark_res->markup_price; 
                    }
                }
                $visa_total_cost = (float)$visa_cost * (float)$customer_inv_res->no_of_pax_days;
                $visa_total_sale = (float)$visa_sale * (float)$customer_inv_res->no_of_pax_days;
                $visa_profit = $visa_total_sale - $visa_total_cost;
                $grand_cost += $visa_total_cost;
                $grand_sale += $visa_total_sale;
    
                // Caluclate Transportation Price
                $trans_cost = 0;
                $trans_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                        $trans_cost = $mark_res->without_markup_price; 
                        $trans_sale = $mark_res->markup_price; 
                    }
                }
                $trans_total_cost = (float)$trans_cost * (float)$customer_inv_res->no_of_pax_days;
                $trans_total_sale = (float)$trans_sale * (float)$customer_inv_res->no_of_pax_days;
                $trans_profit = $trans_total_sale - $trans_total_cost;
                $grand_cost += $trans_total_cost;
                $grand_sale += $trans_total_sale;
    
                // Caluclate Double Room Price
                $double_total_cost = 0;
                $double_total_sale = 0;
                $double_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Double'){
                            (float)$double_cost = $accmod_res->acc_total_amount; 
                            (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                            
                             $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                             $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                             $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                             $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Double'){
                            $double_cost = $accmod_res->more_acc_total_amount; 
                            $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                            $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                            $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                            $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                            $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                $grand_cost += $double_total_cost;
                $grand_sale += $double_total_sale;
                
                // Caluclate Triple Room Price
                $triple_total_cost = 0;
                $triple_total_sale = 0;
                $triple_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->acc_total_amount; 
                            $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                            $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                $grand_cost += $triple_total_cost;
                $grand_sale += $triple_total_sale;
                 
                // Caluclate Quad Room Price
                $quad_total_cost = 0;
                $quad_total_sale = 0;
                $quad_total_profit = 0;
                if(isset($accomodation)){
                            foreach($accomodation as $accmod_res){
                                if($accmod_res->acc_type == 'Quad'){
                                    $quad_cost = $accmod_res->acc_total_amount; 
                                    $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                    
                                     $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                     $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                     $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                     $quad_total_profit = $quad_total_profit + $quad_profit;
                                }
                            }
                         }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Quad'){
                            $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                            $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                            $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                            $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                            $quad_total_profit = $quad_total_profit + $quad_profit;
                        }
                    }
                }
                $grand_cost += $quad_total_cost;
                $grand_sale += $quad_total_sale;
                
                // Caluclate Final Price
                $Final_inv_price = $flight_total_sale + $visa_total_sale + $trans_total_sale + $double_total_sale + $triple_total_sale + $quad_total_sale;
                $Final_inv_profit = $flight_profit + $visa_profit + $trans_profit + $double_total_profit + $triple_total_profit + $quad_total_profit;
                

                
                $select_curreny_data = explode(' ', $customer_inv_res->currency_conversion);
                
                $invoice_curreny = "";
                $customer_curreny_data = explode(' ', $customer_inv_res->currency_Type_AC);
                if(isset($select_curreny_data[2])){
                    $invoice_curreny = $select_curreny_data[2];
                }
                
                $customer_curreny = $invoice_curreny;
                $customer_curreny_data = explode(' ', $customer_inv_res->currency_Type_AC);
                if(isset($customer_curreny_data[2])){
                    $customer_curreny = $customer_curreny_data[2];
                }
                
                $profit = $customer_inv_res->total_sale_price_all_Services - $customer_inv_res->total_cost_price_all_Services ?? $grand_cost;
                $inv_single_data = [
                    'booking_type' => 'Invoice',
                    'invoice_id'=>$customer_inv_res->id,
                    'price'=>$customer_inv_res->total_sale_price_all_Services,
                    'paid_amount'=>$customer_inv_res->total_paid_amount,
                    'remaing_amount'=> $customer_inv_res->total_sale_price_all_Services - $customer_inv_res->total_paid_amount,
                    'over_paid_amount'=>$customer_inv_res->over_paid_amount,
                    'profit'=>$profit,
                    'total_cost'=>  $customer_inv_res->total_cost_price_all_Services ?? $grand_cost,
                    'total_sale'=>$customer_inv_res->total_sale_price_all_Services,
                    'invoice_curreny'=> $invoice_curreny,
                    'customer_curreny'=>$customer_curreny,
                    'customer_total'=>$customer_inv_res->total_sale_price_AC,
                    'created_at'=>$customer_inv_res->created_at,
                ];
                
                // print_r($inv_single_data);
                $inv_single_data = array_merge($inv_single_data,(array)$customer_inv_res);
                // dd($inv_single_data);
                array_push($invoices_all_data,$inv_single_data);
                 
            }
            
            if($request->customer_id == 4 || $request->customer_id == 54){
                if(empty($booking_all_data)){
                }else{
                    $booking_all_data   = self::customer_Booking_Details_Season_Working($booking_all_data,$request);
                    // dd($booking_all_data);
                }
                
                if(empty($invoices_all_data)){
                }else{
                    $invoices_all_data  = self::customer_Booking_Details_Season_Working($invoices_all_data,$request);
                    // dd($invoices_all_data);
                }
            }
            
            $total_paid_amount          = DB::table('customer_ledger')->where('booking_customer',$booking_customer_id)->where('received_id','!=',NULL)->sum('payment');
            
            $customer_quotation_booking = DB::table('addManageQuotationPackage')->where('booking_customer_id',$booking_customer_id)->where('quotation_status',NULL)->get();
            
            $customer_data              = [
                'customer_id'                   => $customer_res->id,
                'customer_name'                 => $customer_res->name,
                'total_paid_amount'             => $total_paid_amount,
                'customers_tour_booking'        => $booking_all_data,
                'customers_invoices_booking'    => $invoices_all_data,
                'customer_quotation_booking'    => $customer_quotation_booking,
            ];
            array_push($all_customers_data,$customer_data);         
        }
        return $all_customers_data;
    }

    function customer_profile_bookings(Request $request){
       
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $all_customers_data = [];
        if($userData){
            $all_customers_data = $this->customer_all_bookings_details($request->booking_customer_id,$request);
        }
        
        return response()->json(['message'=>'success',
            'customer_data' => $all_customers_data,
        ]);
    }
    
    function customer_booking_details(Request $request){
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $all_customers_data     = [];
        if($userData){
            $all_customers_data = $this->customer_all_bookings_details($request->booking_customer_id,$request);
        }
        $graph_data             = $this->createCustomerYearlyGraph($request->booking_customer_id,$request);
        return response()->json(['message'=>'success',
            'customer_data' => $all_customers_data,
            'graph_data'    => $graph_data
        ]);
    }
    
    public static function agent_Booking_Details_Season_Working($all_data,$request){
        $today_Date             = date('Y-m-d');
        // $season_Details         = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            
            $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record['created_at'])) {
                    return false;
                }
                
                if($record['created_at'] != null && $record['created_at'] != ''){
                    $created_at     = Carbon::parse($record['created_at']);
                    $start_Date     = Carbon::parse($start_Date);
                    $end_Date       = Carbon::parse($end_Date);
                    return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                }else{
                    return false;
                }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public static function agent_all_bookings_details($agent_id,$request){
        $agent_lists = DB::table('Agents_detail')->where('id',$agent_id)->get();
        
        $all_agent_data = [];
        foreach($agent_lists as $agent_res){
            $agent_tour_booking = DB::table('cart_details')->where('agent_name',$agent_id)->get();
            $agent_invoice_booking = DB::table('add_manage_invoices')->where('agent_Id',$agent_id)->get();
            
            //  print_r($agent_tour_booking);die;
            
            $booking_all_data = [];
            foreach($agent_tour_booking as $tour_res){
                $tours_costing      = DB::table('tours_2')->where('tour_id',$tour_res->tour_id)->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')->first();
                $booking_details    = DB::table('tours_bookings')->where('invoice_no',$tour_res->invoice_no)->first();
                $cart_all_data      = json_decode($tour_res->cart_total_data);
                $grand_profit       = 0;
                $grand_cost         = 0;
                $grand_sale         = 0;
                
                if($cart_all_data->double_adults > 0){
                    $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                    $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    $grand_profit += $double_profit;
                    $grand_cost += $double_adult_total_cost;
                    $grand_sale += $cart_all_data->double_adult_total;
                }
                
                // Profit From Triple Adults
                if($cart_all_data->triple_adults > 0){
                    $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                    $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    $grand_profit += $triple_profit;
                    $grand_cost += $triple_adult_total_cost;
                    $grand_sale += $cart_all_data->triple_adult_total;
                }
                
                // Profit From Quad Adults
                if($cart_all_data->quad_adults > 0){
                    $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                    $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    $grand_profit += $quad_profit;
                    $grand_cost += $quad_adult_total_cost;
                    $grand_sale += $cart_all_data->quad_adult_total;
                }
             
                // Profit From Without Acc
                if($cart_all_data->without_acc_adults > 0){
                    $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                    $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    $grand_profit += $without_acc_profit;
                    $grand_cost += $without_acc_adult_total_cost;
                    $grand_sale += $cart_all_data->without_acc_adult_total;
                }
                
                // Profit From Double Childs
                if($cart_all_data->double_childs > 0){
                    $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                    $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    $grand_profit += $double_profit;
                    $grand_cost += $double_child_total_cost;
                    $grand_sale += $cart_all_data->double_childs_total;
                }
             
                // Profit From Triple Childs
                if($cart_all_data->triple_childs > 0){
                    $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                    $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    $grand_profit += $triple_profit;
                    $grand_cost += $triple_child_total_cost;
                    $grand_sale += $cart_all_data->triple_childs_total;
                }
                
                // Profit From Quad Childs
                if($cart_all_data->quad_childs > 0){
                    $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                    $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    $grand_profit += $quad_profit;
                    $grand_cost += $quad_child_total_cost;
                    $grand_sale += $cart_all_data->quad_child_total;
                }
             
                // Profit From Without Acc Child
                if($cart_all_data->children > 0){
                    $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                    $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    $grand_profit += $without_acc_profit;
                    $grand_cost += $without_acc_child_total_cost;
                    $grand_sale += $cart_all_data->without_acc_child_total;
                }

                // Profit From Double Infant
                if($cart_all_data->double_infant > 0){
                    $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                    $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    $grand_profit += $double_profit;
                     $grand_cost += $double_infant_total_cost;
                    $grand_sale += $cart_all_data->double_infant_total;
                }
             
                // Profit From Triple Infant
                if($cart_all_data->triple_infant > 0){
                    $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                    $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    $grand_profit += $triple_profit;
                     $grand_cost += $triple_infant_total_cost;
                    $grand_sale += $cart_all_data->triple_infant_total;
                }
             
                // Profit From Quad Infant
                if($cart_all_data->quad_infant > 0){
                    $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                    $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    $grand_profit += $quad_profit;
                    $grand_cost += $quad_infant_total_cost;
                    $grand_sale += $cart_all_data->quad_infant_total;
                }
             
                // Profit From Without Acc Infant  
                if($cart_all_data->infants > 0){
                    $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                    $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    $grand_profit += $without_acc_profit;
                    $grand_cost += $without_acc_infant_total_cost;
                    $grand_sale += $cart_all_data->without_acc_infant_total;
                }
              
                $over_all_dis = 0;
                //   echo "Grand Total Profit is $grand_profit "; 
                if($cart_all_data->discount_type == 'amount'){
                    $final_profit           = $grand_profit - $cart_all_data->discount_enter_am;
                }else{
                    $discunt_am_over_all    = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                    $final_profit           = $grand_profit - $discunt_am_over_all;
                }
                
                $commission_add = '';
                if(isset($cart_all_data->customer_commsion_add_total)){
                    $commission_add         = $cart_all_data->customer_commsion_add_total;
                }
                
                $booking_data = [
                    'booking_type' => 'Package',
                    'invoice_id'=>$tour_res->invoice_no,
                    'booking_id'=>$tour_res->booking_id,
                    'tour_id'=>$tour_res->tour_id,
                    'price'=>$tour_res->tour_total_price,
                    'paid_amount'=>$tour_res->total_paid_amount,
                    'remaing_amount'=> $tour_res->tour_total_price - $tour_res->total_paid_amount,
                    'over_paid_amount'=> $tour_res->over_paid_amount,
                    'tour_name'=>$cart_all_data->name,
                    'profit'=>$final_profit,
                    'discount_am'=>$cart_all_data->discount_Price,
                    'total_cost'=>$grand_cost,
                    'total_sale'=>$grand_sale,
                    'created_at'=>$tour_res->created_at,
                    'commission_am'=>'',
                    'customer_commsion_add_total'=>$commission_add,
                    'currency'=>$tour_res->currency,
                    'confirm'=>$tour_res->confirm,
                    'lead_Name'=>$booking_details->passenger_name,
                    'passenger_detail'=>$booking_details->passenger_detail,
                ];
                
                array_push($booking_all_data,$booking_data);
            }
            
            $invoices_all_data = [];
            foreach($agent_invoice_booking as $inv_res){
                
                $accomodation = json_decode($inv_res->accomodation_details);
                $accomodation_more = json_decode($inv_res->accomodation_details_more);
                $markup_details = json_decode($inv_res->markup_details);
                $more_markup_details = json_decode($inv_res->more_markup_details);
                
                // Caluclate Flight Price 
                $grand_cost = 0;
                $grand_sale = 0;
                $flight_cost = 0;
                $flight_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                        $flight_cost = $mark_res->without_markup_price; 
                        $flight_sale = $mark_res->markup_price; 
                    }
                }
                
                $flight_total_cost = (float)$flight_cost * (float)$inv_res->no_of_pax_days;
                $flight_total_sale = (float)$flight_sale * (float)$inv_res->no_of_pax_days;
                
                $flight_profit = $flight_total_sale - $flight_total_cost;
                
                $grand_cost += $flight_total_cost;
                $grand_sale += $flight_total_sale;
                
                // Caluclate Visa Price 
                $visa_cost = 0;
                $visa_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                        $visa_cost = $mark_res->without_markup_price; 
                        $visa_sale = $mark_res->markup_price; 
                    }
                }
                $visa_total_cost = (float)$visa_cost * (float)$inv_res->no_of_pax_days;
                $visa_total_sale = (float)$visa_sale * (float)$inv_res->no_of_pax_days;
                $visa_profit = $visa_total_sale - $visa_total_cost;
                $grand_cost += $visa_total_cost;
                $grand_sale += $visa_total_sale;
                
                // Caluclate Transportation Price
                $trans_cost = 0;
                $trans_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                        $trans_cost = $mark_res->without_markup_price; 
                        $trans_sale = $mark_res->markup_price; 
                    }
                }
                $trans_total_cost = (float)$trans_cost * (float)$inv_res->no_of_pax_days;
                $trans_total_sale = (float)$trans_sale * (float)$inv_res->no_of_pax_days;
                $trans_profit = $trans_total_sale - $trans_total_cost;
                $grand_cost += $trans_total_cost;
                $grand_sale += $trans_total_sale;
                
                // Caluclate Double Room Price
                $double_total_cost = 0;
                $double_total_sale = 0;
                $double_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Double'){
                            (float)$double_cost = $accmod_res->acc_total_amount; 
                            (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                            
                             $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                             $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                             $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                             $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Double'){
                            $double_cost = $accmod_res->more_acc_total_amount; 
                            $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                            $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                            $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                            $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                            $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                $grand_cost += $double_total_cost;
                $grand_sale += $double_total_sale;
                
                // Caluclate Triple Room Price
                $triple_total_cost = 0;
                $triple_total_sale = 0;
                $triple_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->acc_total_amount; 
                            $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                            $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                $grand_cost += $triple_total_cost;
                $grand_sale += $triple_total_sale;
                 
                // Caluclate Quad Room Price
                $quad_total_cost = 0;
                $quad_total_sale = 0;
                $quad_total_profit = 0;
                if(isset($accomodation)){
                            foreach($accomodation as $accmod_res){
                                if($accmod_res->acc_type == 'Quad'){
                                    $quad_cost = $accmod_res->acc_total_amount; 
                                    $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                    
                                     $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                     $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                     $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                     $quad_total_profit = $quad_total_profit + $quad_profit;
                                }
                            }
                         }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Quad'){
                            $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                            $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                            $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                            $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                            $quad_total_profit = $quad_total_profit + $quad_profit;
                        }
                    }
                }
                $grand_cost += $quad_total_cost;
                $grand_sale += $quad_total_sale;
                
                // Caluclate Final Price
                $Final_inv_price = $flight_total_sale + $visa_total_sale + $trans_total_sale + $double_total_sale + $triple_total_sale + $quad_total_sale;
                $Final_inv_profit = $flight_profit + $visa_profit + $trans_profit + $double_total_profit + $triple_total_profit + $quad_total_profit;
                
                $select_curreny_data = explode(' ', $inv_res->currency_conversion);
                
                $invoice_curreny = "";
                $customer_curreny_data = explode(' ', $inv_res->currency_Type_AC);
                if(isset($select_curreny_data[2])){
                    $invoice_curreny = $select_curreny_data[2];
                }
                
                $customer_curreny = $invoice_curreny;
                $customer_curreny_data = explode(' ', $inv_res->currency_Type_AC);
                if(isset($customer_curreny_data[2])){
                    $customer_curreny = $customer_curreny_data[2];
                }
                
                if(isset($inv_res->total_sale_price_AC) && $inv_res->total_sale_price_AC > 0){
                    $price          = $inv_res->total_sale_price_AC ?? 0;
                    $total_cost     = $inv_res->total_cost_price_AC ?? $grand_cost ?? 0;
                    $profit         = $price - $total_cost;
                }else if(isset($inv_res->total_sale_price_Company) && $inv_res->total_sale_price_Company > 0){
                    $price          = $inv_res->total_sale_price_Company ?? 0;
                    $total_cost     = $inv_res->total_cost_price_Company ?? $grand_cost ?? 0;
                    $profit         = $price - $total_cost;
                }else {
                    $price          = $inv_res->total_sale_price_all_Services ?? 0;
                    $total_cost     = $inv_res->total_cost_price_all_Services ?? $grand_cost ?? 0;
                    $profit         = $price - $total_cost;
                }
                
                $inv_single_data = [
                    'booking_type'      => 'Invoice',
                    'invoice_id'        => $inv_res->id,
                    'price'             => $price,
                    'paid_amount'       => $inv_res->total_paid_amount,
                    'remaing_amount'    => $price - $inv_res->total_paid_amount,
                    'over_paid_amount'  => $inv_res->over_paid_amount,
                    'profit'            => $profit,
                    'total_cost'        => $total_cost,
                    'total_sale'        => $price,
                    'invoice_curreny'   => $invoice_curreny,
                    'customer_curreny'  => $customer_curreny,
                    'customer_total'    => $price,
                    'created_at'        => $inv_res->created_at,
                ];
                
                // print_r($inv_single_data);
                $inv_single_data = array_merge($inv_single_data,(array)$inv_res);
                // dd($inv_single_data);
                array_push($invoices_all_data,$inv_single_data);
            }
            
            if($request->customer_id == 4 || $request->customer_id == 54){
                if(empty($booking_all_data)){
                }else{
                    $booking_all_data   = self::agent_Booking_Details_Season_Working($booking_all_data,$request);
                    // dd($booking_all_data);
                }
                
                if(empty($invoices_all_data)){
                }else{
                    $invoices_all_data  = self::agent_Booking_Details_Season_Working($invoices_all_data,$request);
                    // dd($invoices_all_data);
                }
            }
            
            $total_paid_amount          = DB::table('agents_ledgers_new')->where('agent_id',$agent_id)->where('received_id','!=',NULL)->sum('payment');
            
            $agent_quotation_booking    = DB::table('addManageQuotationPackage')->where('agent_Id',$agent_id)->where('quotation_status',NULL)->get();
            
            $agent_data                 = [
                'agent_id'                  => $agent_res->id,
                'agent_name'                => $agent_res->agent_Name,
                'total_paid_amount'         => $total_paid_amount,
                'agents_tour_booking'       => $booking_all_data,
                'agents_invoices_booking'   => $invoices_all_data,
                'agent_quotation_booking'   => $agent_quotation_booking,
            ];
            array_push($all_agent_data,$agent_data);         
        }
        
        return $all_agent_data;
    }

    function agent_booking_details(Request $request){
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $all_customers_data     = [];
        if($userData){
            $all_customers_data = $this->agent_all_bookings_details($request->agent_id,$request);
            // dd($all_customers_data);
        }
        $graph_data             = $this->createAgentYearlyGraph($request->agent_id,$request);
        
        return response()->json(['message'=>'success',
            'agent_data'        => $all_customers_data,
            'graph_data'        => $graph_data
        ]);
    }
    
    function agent_Prices_Type(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $all_customers_data = [];
        if($userData){
            $all_customers_data = $this->agent_all_bookings_details_Type($request->agent_id,$request->date_type,$request->start_date,$request->end_date);
        }
        $graph_data = $this->createAgentYearlyGraph($request->agent_id,$request);
        return response()->json(['message'=>'success',
            'agent_data' => $all_customers_data,
            'graph_data' => $graph_data
        ]);
    }
    
    public static function agent_all_bookings_details_Type($agent_id,$date_type,$start_date,$end_date){
        $agent_lists = DB::table('Agents_detail')->where('id',$agent_id)->get();
        
        $all_agent_data = [];
        foreach($agent_lists as $agent_res){
            if($date_type == 'data_today_wise'){
                $today_date             = date('Y-m-d');
                $agent_tour_booking     = DB::table('cart_details')->where('agent_name',$agent_id)->whereDate('cart_details.created_at', $today_date)->get();
                $agent_invoice_booking  = DB::table('add_manage_invoices')->where('agent_Id',$agent_id)->whereDate('add_manage_invoices.created_at', $today_date)->get();
            }
            
            if($date_type == 'data_week_wise'){
                $startOfWeek            = Carbon::now()->startOfWeek();
                $start_date             = $startOfWeek->format('Y-m-d');
                $end_date               = date('Y-m-d');
                $agent_tour_booking     = DB::table('cart_details')->where('agent_name',$agent_id)
                                            ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $agent_invoice_booking  = DB::table('add_manage_invoices')->where('agent_Id',$agent_id)
                                            ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
            }
            
            if($date_type == 'data_month_wise'){
                $startOfMonth   = Carbon::now()->startOfMonth();
                $start_date     = $startOfMonth->format('Y-m-d');
                $end_date       = date('Y-m-d');
                $agent_tour_booking     = DB::table('cart_details')->where('agent_name',$agent_id)
                                            ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $agent_invoice_booking  = DB::table('add_manage_invoices')->where('agent_Id',$agent_id)
                                            ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
            }
            
            if($date_type == 'data_year_wise'){
                $startOfYear    = Carbon::now()->startOfYear();
                $start_date     = $startOfYear->format('Y-m-d');
                $end_date       = date('Y-m-d');
                $agent_tour_booking     = DB::table('cart_details')->where('agent_name',$agent_id)
                                            ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $agent_invoice_booking  = DB::table('add_manage_invoices')->where('agent_Id',$agent_id)
                                            ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
            }
            
            if($date_type == 'data_wise'){
                $start_date             = $start_date;
                $end_date               = $end_date;
                $agent_tour_booking     = DB::table('cart_details')->where('agent_name',$agent_id)
                                            ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $agent_invoice_booking  = DB::table('add_manage_invoices')->where('agent_Id',$agent_id)
                                            ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
            }
            
            // $agent_tour_booking     = DB::table('cart_details')->where('agent_name',$agent_id)->get();
            // $agent_invoice_booking  = DB::table('add_manage_invoices')->where('agent_Id',$agent_id)->get();
            
            //  print_r($agent_tour_booking);die;
            
            $booking_all_data = [];
            foreach($agent_tour_booking as $tour_res){
                 
                $tours_costing = DB::table('tours_2')->where('tour_id',$tour_res->tour_id)->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')->first();
                $booking_details    = DB::table('tours_bookings')->where('invoice_no',$tour_res->invoice_no)->first();
                //  print_r($tours_costing);
                
                 
                 $cart_all_data = json_decode($tour_res->cart_total_data);
                 
                 $grand_profit = 0;
                 $grand_cost = 0;
                 $grand_sale = 0;
                 // Profit From Double Adults
                 
                    if($cart_all_data->double_adults > 0){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_adult_total_cost;
                        $grand_sale += $cart_all_data->double_adult_total;
                    }
                 
                 // Profit From Triple Adults
                 
                    if($cart_all_data->triple_adults > 0){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_adult_total_cost;
                        $grand_sale += $cart_all_data->triple_adult_total;
                    }
                    
    
                 // Profit From Quad Adults
                 
                    if($cart_all_data->quad_adults > 0){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_adult_total_cost;
                        $grand_sale += $cart_all_data->quad_adult_total;
                    }
                 
                 // Profit From Without Acc
                 
                    if($cart_all_data->without_acc_adults > 0){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_adult_total_cost;
                        $grand_sale += $cart_all_data->without_acc_adult_total;
                    }
                 
                 // Profit From Double Childs
                 
                    if($cart_all_data->double_childs > 0){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_child_total_cost;
                        $grand_sale += $cart_all_data->double_childs_total;
                    }
                 
                 // Profit From Triple Childs
                 
                   if($cart_all_data->triple_childs > 0){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_child_total_cost;
                        $grand_sale += $cart_all_data->triple_childs_total;
                    }
                    
                 // Profit From Quad Childs
                 
                    if($cart_all_data->quad_childs > 0){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                        $grand_profit += $quad_profit;
                        $grand_cost += $quad_child_total_cost;
                        $grand_sale += $cart_all_data->quad_child_total;
                    }
                 
                 // Profit From Without Acc Child
    
                    if($cart_all_data->children > 0){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_child_total_cost;
                        $grand_sale += $cart_all_data->without_acc_child_total;
                    }
    
                // Profit From Double Infant
                    if($cart_all_data->double_infant > 0){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                        $grand_profit += $double_profit;
                         $grand_cost += $double_infant_total_cost;
                        $grand_sale += $cart_all_data->double_infant_total;
                    }
                 
                 // Profit From Triple Infant
                 
                    if($cart_all_data->triple_infant > 0){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                        $grand_profit += $triple_profit;
                         $grand_cost += $triple_infant_total_cost;
                        $grand_sale += $cart_all_data->triple_infant_total;
                    }
                 
                 // Profit From Quad Infant
                 
                    if($cart_all_data->quad_infant > 0){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_infant_total_cost;
                        $grand_sale += $cart_all_data->quad_infant_total;
                    }
                 
                 // Profit From Without Acc Infant  
                 
                  if($cart_all_data->infants > 0){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_infant_total_cost;
                        $grand_sale += $cart_all_data->without_acc_infant_total;
                  }
                  
                  $over_all_dis = 0;
                //   echo "Grand Total Profit is $grand_profit "; 
                  if($cart_all_data->discount_type == 'amount'){
                      $final_profit = $grand_profit - $cart_all_data->discount_enter_am;
                  }else{
                       $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                       $final_profit = $grand_profit - $discunt_am_over_all;
                  }
                 
                  
                 
                //  echo "Grand Total Profit is $final_profit";
                //  print_r($cart_all_data);
                
                $commission_add = '';
                if(isset($cart_all_data->customer_commsion_add_total)){
                    $commission_add = $cart_all_data->customer_commsion_add_total;
                }
    
                 $booking_data = [
                        'booking_type' => 'Package',
                        'invoice_id'=>$tour_res->invoice_no,
                        'booking_id'=>$tour_res->booking_id,
                        'tour_id'=>$tour_res->tour_id,
                        'price'=>$tour_res->tour_total_price,
                        'paid_amount'=>$tour_res->total_paid_amount,
                        'remaing_amount'=> $tour_res->tour_total_price - $tour_res->total_paid_amount,
                        'over_paid_amount'=> $tour_res->over_paid_amount,
                        'tour_name'=>$cart_all_data->name,
                        'profit'=>$final_profit,
                        'discount_am'=>$cart_all_data->discount_Price,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'created_at'=>$tour_res->created_at,
                        'commission_am'=>'',
                        'customer_commsion_add_total'=>$commission_add,
                        'currency'=>$tour_res->currency,
                        'confirm'=>$tour_res->confirm,
                        'lead_Name'=>$booking_details->passenger_name,
                        'passenger_detail'=>$booking_details->passenger_detail,
                     ];
                     
                  array_push($booking_all_data,$booking_data);
            }
            
            $invoices_all_data = [];
            foreach($agent_invoice_booking as $inv_res){
                
                $accomodation = json_decode($inv_res->accomodation_details);
                $accomodation_more = json_decode($inv_res->accomodation_details_more);
                $markup_details = json_decode($inv_res->markup_details);
                $more_markup_details = json_decode($inv_res->more_markup_details);
                 
                // Caluclate Flight Price 
                $grand_cost = 0;
                $grand_sale = 0;
                $flight_cost = 0;
                $flight_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                        $flight_cost = $mark_res->without_markup_price; 
                        $flight_sale = $mark_res->markup_price; 
                    }
                }
                
                $flight_total_cost = (float)$flight_cost * (float)$inv_res->no_of_pax_days;
                $flight_total_sale = (float)$flight_sale * (float)$inv_res->no_of_pax_days;
                
                $flight_profit = $flight_total_sale - $flight_total_cost;
                
                $grand_cost += $flight_total_cost;
                $grand_sale += $flight_total_sale;
                
                // Caluclate Visa Price 
                $visa_cost = 0;
                $visa_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                        $visa_cost = $mark_res->without_markup_price; 
                        $visa_sale = $mark_res->markup_price; 
                    }
                }
                $visa_total_cost = (float)$visa_cost * (float)$inv_res->no_of_pax_days;
                $visa_total_sale = (float)$visa_sale * (float)$inv_res->no_of_pax_days;
                $visa_profit = $visa_total_sale - $visa_total_cost;
                $grand_cost += $visa_total_cost;
                $grand_sale += $visa_total_sale;
    
                // Caluclate Transportation Price
                $trans_cost = 0;
                $trans_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                        $trans_cost = $mark_res->without_markup_price; 
                        $trans_sale = $mark_res->markup_price; 
                    }
                }
                $trans_total_cost = (float)$trans_cost * (float)$inv_res->no_of_pax_days;
                $trans_total_sale = (float)$trans_sale * (float)$inv_res->no_of_pax_days;
                $trans_profit = $trans_total_sale - $trans_total_cost;
                $grand_cost += $trans_total_cost;
                $grand_sale += $trans_total_sale;
                
                // Caluclate Double Room Price
                $double_total_cost = 0;
                $double_total_sale = 0;
                $double_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Double'){
                            (float)$double_cost = $accmod_res->acc_total_amount; 
                            (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                            
                             $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                             $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                             $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                             $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Double'){
                            $double_cost = $accmod_res->more_acc_total_amount; 
                            $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                            $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                            $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                            $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                            $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                $grand_cost += $double_total_cost;
                $grand_sale += $double_total_sale;
                
                // Caluclate Triple Room Price
                $triple_total_cost = 0;
                $triple_total_sale = 0;
                $triple_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->acc_total_amount; 
                            $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                            $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                $grand_cost += $triple_total_cost;
                $grand_sale += $triple_total_sale;
                 
                // Caluclate Quad Room Price
                $quad_total_cost = 0;
                $quad_total_sale = 0;
                $quad_total_profit = 0;
                if(isset($accomodation)){
                            foreach($accomodation as $accmod_res){
                                if($accmod_res->acc_type == 'Quad'){
                                    $quad_cost = $accmod_res->acc_total_amount; 
                                    $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                    
                                     $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                     $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                     $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                     $quad_total_profit = $quad_total_profit + $quad_profit;
                                }
                            }
                         }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Quad'){
                            $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                            $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                            $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                            $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                            $quad_total_profit = $quad_total_profit + $quad_profit;
                        }
                    }
                }
                $grand_cost += $quad_total_cost;
                $grand_sale += $quad_total_sale;
                
                // Caluclate Final Price
                $Final_inv_price = $flight_total_sale + $visa_total_sale + $trans_total_sale + $double_total_sale + $triple_total_sale + $quad_total_sale;
                $Final_inv_profit = $flight_profit + $visa_profit + $trans_profit + $double_total_profit + $triple_total_profit + $quad_total_profit;
                
                $select_curreny_data = explode(' ', $inv_res->currency_conversion);
                
                $invoice_curreny = "";
                $customer_curreny_data = explode(' ', $inv_res->currency_Type_AC);
                if(isset($select_curreny_data[2])){
                    $invoice_curreny = $select_curreny_data[2];
                }
                
                $customer_curreny = $invoice_curreny;
                $customer_curreny_data = explode(' ', $inv_res->currency_Type_AC);
                if(isset($customer_curreny_data[2])){
                    $customer_curreny = $customer_curreny_data[2];
                }
                
                $profit = $inv_res->total_sale_price_all_Services - $inv_res->total_cost_price_all_Services ?? $grand_cost;
                $inv_single_data = [
                    'booking_type' => 'Invoice',
                    'invoice_id'=>$inv_res->id,
                    'price'=>$inv_res->total_sale_price_all_Services,
                    'paid_amount'=>$inv_res->total_paid_amount,
                    'remaing_amount'=> $inv_res->total_sale_price_all_Services - $inv_res->total_paid_amount,
                    'over_paid_amount'=>$inv_res->over_paid_amount,
                    'profit'=>$profit,
                    'total_cost'=> $inv_res->total_cost_price_all_Services ?? $grand_cost,
                    'total_sale'=>$inv_res->total_sale_price_all_Services,
                    'invoice_curreny'=> $invoice_curreny,
                    'customer_curreny'=>$customer_curreny,
                    'customer_total'=>$inv_res->total_sale_price_all_Services ?? $inv_res->total_sale_price_AC,
                    'created_at'=>$inv_res->created_at,
                ];
                
                // print_r($inv_single_data);
                $inv_single_data = array_merge($inv_single_data,(array)$inv_res);
                // dd($inv_single_data);
                array_push($invoices_all_data,$inv_single_data);
                 
            }
            
            $total_paid_amount          = DB::table('agents_ledgers_new')->where('agent_id',$agent_id)->where('received_id','!=',NULL)->sum('payment');
            
            $agent_quotation_booking    = DB::table('addManageQuotationPackage')->where('agent_Id',$agent_id)->where('quotation_status',NULL)->get();
            
            $agent_data = [
                    'agent_id'                  => $agent_res->id,
                    'agent_name'                => $agent_res->agent_Name,
                    'total_paid_amount'         => $total_paid_amount,
                    'agents_tour_booking'       => $booking_all_data,
                    'agents_invoices_booking'   => $invoices_all_data,
                    'agent_quotation_booking'   => $agent_quotation_booking,
                ];
            array_push($all_agent_data,$agent_data);         
        }
        
        return $all_agent_data;
    }
    
    function customer_Prices_Type(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $all_customers_data = [];
        if($userData){
            $all_customers_data = $this->customer_all_bookings_details_Type($request->booking_customer_id,$request->date_type,$request->start_date,$request->end_date);
        }
        $graph_data = $this->createCustomerYearlyGraph($request->booking_customer_id,$request);
        return response()->json(['message'=>'success',
            'customer_data' => $all_customers_data,
            'graph_data'    => $graph_data
        ]);
    }
    
    public static function customer_all_bookings_details_Type($booking_customer_id,$date_type,$start_date,$end_date){
        $customer_lists                 = DB::table('booking_customers')->where('id',$booking_customer_id)->get();
        $all_customers_data             = [];
        
        // dd($date_type,$booking_customer_id);
        
        foreach($customer_lists as $customer_res){
            
            if($date_type == 'data_today_wise'){
                $today_date                 = date('Y-m-d');
                $customers_tour_booking     = DB::table('cart_details')->where('cart_total_data->customer_id',"$booking_customer_id")->whereDate('cart_details.created_at', $today_date)->get();
                $customers_invoice_booking  = DB::table('add_manage_invoices')->where('booking_customer_id',$booking_customer_id)->whereDate('add_manage_invoices.created_at', $today_date)->get();
            }
            
            if($date_type == 'data_week_wise'){
                $startOfWeek                = Carbon::now()->startOfWeek();
                $start_date                 = $startOfWeek->format('Y-m-d');
                $end_date                   = date('Y-m-d');
                $customers_tour_booking     = DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$booking_customer_id")
                                                ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $customers_invoice_booking  = DB::table('add_manage_invoices')->where('booking_customer_id',$booking_customer_id)
                                                ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
            }
            
            if($date_type == 'data_month_wise'){
                $startOfMonth               = Carbon::now()->startOfMonth();
                $start_date                 = $startOfMonth->format('Y-m-d');
                $end_date                   = date('Y-m-d');
                $customers_tour_booking     = DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$booking_customer_id")
                                                ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $customers_invoice_booking  = DB::table('add_manage_invoices')->where('booking_customer_id',$booking_customer_id)
                                                ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
            }
            
            if($date_type == 'data_year_wise'){
                $startOfYear    = Carbon::now()->startOfYear();
                $start_date     = $startOfYear->format('Y-m-d');
                $end_date       = date('Y-m-d');
                $customers_tour_booking     = DB::table('cart_details')->whereJsonContains('cart_total_data->cart_details->customer_id',"$booking_customer_id")
                                                ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $customers_invoice_booking  = DB::table('add_manage_invoices')->where('booking_customer_id',$booking_customer_id)
                                                ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
            }
            
            if($date_type == 'data_wise'){
                $start_date                 = $start_date;
                $end_date                   = $end_date;
                $customers_tour_booking     = DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$booking_customer_id")
                                                ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $customers_invoice_booking  = DB::table('add_manage_invoices')->where('add_manage_invoices.booking_customer_id',$booking_customer_id)
                                                ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
            }
            
            // dd($customers_invoice_booking);
            
            // $customers_tour_booking     = DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$booking_customer_id")->get();
            // $customers_invoice_booking  = DB::table('add_manage_invoices')->where('booking_customer_id',$booking_customer_id)->get();
            
            $booking_all_data           = [];
            foreach($customers_tour_booking as $tour_res){
                $tours_costing      = DB::table('tours_2')->where('tour_id',$tour_res->tour_id)->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')->first();
                $booking_details    = DB::table('tours_bookings')->where('invoice_no',$tour_res->invoice_no)->first();
                $cart_all_data      = json_decode($tour_res->cart_total_data);
                
                $grand_profit = 0;
                $grand_cost = 0;
                $grand_sale = 0;
                // Profit From Double Adults
                 
                    if($cart_all_data->double_adults > 0){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_adult_total_cost;
                        $grand_sale += $cart_all_data->double_adult_total;
                    }
                 
                 // Profit From Triple Adults
                 
                    if($cart_all_data->triple_adults > 0){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_adult_total_cost;
                        $grand_sale += $cart_all_data->triple_adult_total;
                    }
                    
    
                 // Profit From Quad Adults
                 
                    if($cart_all_data->quad_adults > 0){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_adult_total_cost;
                        $grand_sale += $cart_all_data->quad_adult_total;
                    }
                 
                 // Profit From Without Acc
                 
                    if($cart_all_data->without_acc_adults > 0){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_adult_total_cost;
                        $grand_sale += $cart_all_data->without_acc_adult_total;
                    }
                 
                 // Profit From Double Childs
                 
                    if($cart_all_data->double_childs > 0){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_child_total_cost;
                        $grand_sale += $cart_all_data->double_childs_total;
                    }
                 
                 // Profit From Triple Childs
                 
                   if($cart_all_data->triple_childs > 0){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_child_total_cost;
                        $grand_sale += $cart_all_data->triple_childs_total;
                    }
                    
                 // Profit From Quad Childs
                 
                    if($cart_all_data->quad_childs > 0){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                        $grand_profit += $quad_profit;
                        $grand_cost += $quad_child_total_cost;
                        $grand_sale += $cart_all_data->quad_child_total;
                    }
                 
                 // Profit From Without Acc Child
    
                    if($cart_all_data->children > 0){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_child_total_cost;
                        $grand_sale += $cart_all_data->without_acc_child_total;
                    }
    
                // Profit From Double Infant
                    if($cart_all_data->double_infant > 0){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                        $grand_profit += $double_profit;
                         $grand_cost += $double_infant_total_cost;
                        $grand_sale += $cart_all_data->double_infant_total;
                    }
                 
                 // Profit From Triple Infant
                 
                    if($cart_all_data->triple_infant > 0){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                        $grand_profit += $triple_profit;
                         $grand_cost += $triple_infant_total_cost;
                        $grand_sale += $cart_all_data->triple_infant_total;
                    }
                 
                 // Profit From Quad Infant
                 
                    if($cart_all_data->quad_infant > 0){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_infant_total_cost;
                        $grand_sale += $cart_all_data->quad_infant_total;
                    }
                 
                 // Profit From Without Acc Infant  
                 
                  if($cart_all_data->infants > 0){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_infant_total_cost;
                        $grand_sale += $cart_all_data->without_acc_infant_total;
                  }
                  
                    $over_all_dis = 0;
                //   echo "Grand Total Profit is $grand_profit "; 
                    if($cart_all_data->discount_type == 'amount'){
                      $final_profit = $grand_profit - $cart_all_data->discount_enter_am;
                    }else{
                       $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                       $final_profit = $grand_profit - $discunt_am_over_all;
                    }
                 
                //  echo "Grand Total Profit is $final_profit";
                //  print_r($cart_all_data);
                
                $commission_add = '';
                if(isset($cart_all_data->customer_commsion_add_total)){
                    $commission_add = $cart_all_data->customer_commsion_add_total;
                }
                
                // dd($grand_cost);
                
                $booking_data = [
                        'booking_type' => 'Package',
                        'invoice_id'=>$tour_res->invoice_no,
                        'booking_id'=>$tour_res->booking_id,
                        'tour_id'=>$tour_res->tour_id,
                        'price'=>$tour_res->tour_total_price,
                        'paid_amount'=>$tour_res->total_paid_amount,
                        'remaing_amount'=> $tour_res->tour_total_price - $tour_res->total_paid_amount,
                        'over_paid_amount'=> $tour_res->over_paid_amount,
                        'tour_name'=>$cart_all_data->name,
                        'profit'=>$final_profit,
                        'discount_am'=>$cart_all_data->discount_Price,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'created_at'=>$tour_res->created_at,
                        'commission_am'=>'',
                        'customer_commsion_add_total'=>$commission_add,
                        'currency'=>$tour_res->currency,
                        'confirm'=>$tour_res->confirm,
                        'lead_Name'=>$booking_details->passenger_name,
                        'passenger_detail'=>$booking_details->passenger_detail,
                     ];
                     
                  array_push($booking_all_data,$booking_data);
             }
            
            $invoices_all_data          = [];
            foreach($customers_invoice_booking as $customer_inv_res){
                
                $accomodation = json_decode($customer_inv_res->accomodation_details);
                $accomodation_more = json_decode($customer_inv_res->accomodation_details_more);
                $markup_details = json_decode($customer_inv_res->markup_details);
                $more_markup_details = json_decode($customer_inv_res->more_markup_details);
                 
                // Caluclate Flight Price 
                $grand_cost = 0;
                $grand_sale = 0;
                $flight_cost = 0;
                $flight_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                        $flight_cost = $mark_res->without_markup_price; 
                        $flight_sale = $mark_res->markup_price; 
                    }
                }
                        
                $flight_total_cost = (float)$flight_cost * (float)$customer_inv_res->no_of_pax_days;
                $flight_total_sale = (float)$flight_sale * (float)$customer_inv_res->no_of_pax_days;
                
                $flight_profit = $flight_total_sale - $flight_total_cost;
                
                $grand_cost += $flight_total_cost;
                $grand_sale += $flight_total_sale;
                
                // Caluclate Visa Price 
                $visa_cost = 0;
                $visa_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                        $visa_cost = $mark_res->without_markup_price; 
                        $visa_sale = $mark_res->markup_price; 
                    }
                }
                $visa_total_cost = (float)$visa_cost * (float)$customer_inv_res->no_of_pax_days;
                $visa_total_sale = (float)$visa_sale * (float)$customer_inv_res->no_of_pax_days;
                $visa_profit = $visa_total_sale - $visa_total_cost;
                $grand_cost += $visa_total_cost;
                $grand_sale += $visa_total_sale;
    
                // Caluclate Transportation Price
                $trans_cost = 0;
                $trans_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                        $trans_cost = $mark_res->without_markup_price; 
                        $trans_sale = $mark_res->markup_price; 
                    }
                }
                $trans_total_cost = (float)$trans_cost * (float)$customer_inv_res->no_of_pax_days;
                $trans_total_sale = (float)$trans_sale * (float)$customer_inv_res->no_of_pax_days;
                $trans_profit = $trans_total_sale - $trans_total_cost;
                $grand_cost += $trans_total_cost;
                $grand_sale += $trans_total_sale;
    
                // Caluclate Double Room Price
                $double_total_cost = 0;
                $double_total_sale = 0;
                $double_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Double'){
                            (float)$double_cost = $accmod_res->acc_total_amount; 
                            (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                            
                             $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                             $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                             $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                             $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Double'){
                            $double_cost = $accmod_res->more_acc_total_amount; 
                            $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                            $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                            $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                            $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                            $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                $grand_cost += $double_total_cost;
                $grand_sale += $double_total_sale;
                
                // Caluclate Triple Room Price
                $triple_total_cost = 0;
                $triple_total_sale = 0;
                $triple_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->acc_total_amount; 
                            $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                            $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                $grand_cost += $triple_total_cost;
                $grand_sale += $triple_total_sale;
                 
                // Caluclate Quad Room Price
                $quad_total_cost = 0;
                $quad_total_sale = 0;
                $quad_total_profit = 0;
                if(isset($accomodation)){
                            foreach($accomodation as $accmod_res){
                                if($accmod_res->acc_type == 'Quad'){
                                    $quad_cost = $accmod_res->acc_total_amount; 
                                    $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                    
                                     $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                     $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                     $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                     $quad_total_profit = $quad_total_profit + $quad_profit;
                                }
                            }
                         }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Quad'){
                            $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                            $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                            $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                            $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                            $quad_total_profit = $quad_total_profit + $quad_profit;
                        }
                    }
                }
                $grand_cost += $quad_total_cost;
                $grand_sale += $quad_total_sale;
                
                // Caluclate Final Price
                $Final_inv_price = $flight_total_sale + $visa_total_sale + $trans_total_sale + $double_total_sale + $triple_total_sale + $quad_total_sale;
                $Final_inv_profit = $flight_profit + $visa_profit + $trans_profit + $double_total_profit + $triple_total_profit + $quad_total_profit;
                

                
                $select_curreny_data = explode(' ', $customer_inv_res->currency_conversion);
                
                $invoice_curreny = "";
                $customer_curreny_data = explode(' ', $customer_inv_res->currency_Type_AC);
                if(isset($select_curreny_data[2])){
                    $invoice_curreny = $select_curreny_data[2];
                }
                
                $customer_curreny = $invoice_curreny;
                $customer_curreny_data = explode(' ', $customer_inv_res->currency_Type_AC);
                if(isset($customer_curreny_data[2])){
                    $customer_curreny = $customer_curreny_data[2];
                }
                
                $profit = $customer_inv_res->total_sale_price_all_Services - $customer_inv_res->total_cost_price_all_Services ?? $grand_cost;
                $inv_single_data = [
                    'booking_type' => 'Invoice',
                    'invoice_id'=>$customer_inv_res->id,
                    'price'=>$customer_inv_res->total_sale_price_all_Services,
                    'paid_amount'=>$customer_inv_res->total_paid_amount,
                    'remaing_amount'=> $customer_inv_res->total_sale_price_all_Services - $customer_inv_res->total_paid_amount,
                    'over_paid_amount'=>$customer_inv_res->over_paid_amount,
                    'profit'=>$profit,
                    'total_cost'=>  $customer_inv_res->total_cost_price_all_Services ?? $grand_cost,
                    'total_sale'=>$customer_inv_res->total_sale_price_all_Services,
                    'invoice_curreny'=> $invoice_curreny,
                    'customer_curreny'=>$customer_curreny,
                    'customer_total'=>$customer_inv_res->total_sale_price_AC,
                    'created_at'=>$customer_inv_res->created_at,
                ];
                
                // print_r($inv_single_data);
                $inv_single_data = array_merge($inv_single_data,(array)$customer_inv_res);
                // dd($inv_single_data);
                array_push($invoices_all_data,$inv_single_data);
                 
            }
            
            $total_paid_amount          = DB::table('customer_ledger')->where('booking_customer',$booking_customer_id)->where('received_id','!=',NULL)->sum('payment');
            
            $customer_quotation_booking = DB::table('addManageQuotationPackage')->where('booking_customer_id',$booking_customer_id)->where('quotation_status',NULL)->get();
            
            $customer_data              = [
                'customer_id'                   => $customer_res->id,
                'customer_name'                 => $customer_res->name,
                'total_paid_amount'             => $total_paid_amount,
                'customers_tour_booking'        => $booking_all_data,
                'customers_invoices_booking'    => $invoices_all_data,
                'customer_quotation_booking'    => $customer_quotation_booking,
            ];
            array_push($all_customers_data,$customer_data);         
        }
        
        return $all_customers_data;
    }
    
    function agents_stats_details_new(Request $request){

        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $agent_lists = DB::table('Agents_detail')->where('id',$request->agent_id)->get();
            
            $all_agents_data = [];
            foreach($agent_lists as $agent_res){
                $agents_tour_booking = DB::table('cart_details')->where('agent_name',$agent_res->id)->get();
                $agents_invoice_booking = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->id)->select('total_sale_price_all_Services','total_paid_amount','over_paid_amount','accomodation_details','accomodation_details_more','markup_details','more_markup_details','no_of_pax_days','id','agent_Id','currency_symbol')->get();
                 
                 
                 $agent_ledgers = DB::table('agents_ledgers_new')->where('agent_id',$request->agent_id)->sum('payment');
                //  print_r($agents_tour_booking);
                 
                //  die;
                 $booking_all_data = [];
                 foreach($agents_tour_booking as $tour_res){
                     
                     $tours_costing = DB::table('tours_2')->where('tour_id',$tour_res->tour_id)->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')->first();

                    //  print_r($tours_costing);
                    
                     
                     $cart_all_data = json_decode($tour_res->cart_total_data);
                     
                     $grand_profit = 0;
                     $grand_cost = 0;
                     $grand_sale = 0;
                     // Profit From Double Adults
                     
                        if($cart_all_data->double_adults > 0){
                            $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                            $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                            $grand_profit += $double_profit;
                            $grand_cost += $double_adult_total_cost;
                            $grand_sale += $cart_all_data->double_adult_total;
                        }
                     
                     // Profit From Triple Adults
                     
                        if($cart_all_data->triple_adults > 0){
                            $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                            $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                            $grand_profit += $triple_profit;
                            $grand_cost += $triple_adult_total_cost;
                            $grand_sale += $cart_all_data->triple_adult_total;
                        }
                        

                     // Profit From Quad Adults
                     
                        if($cart_all_data->quad_adults > 0){
                            $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                            $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                            $grand_profit += $quad_profit;
                             $grand_cost += $quad_adult_total_cost;
                            $grand_sale += $cart_all_data->quad_adult_total;
                        }
                     
                     // Profit From Without Acc
                     
                        if($cart_all_data->without_acc_adults > 0){
                            $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                            $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                            $grand_profit += $without_acc_profit;
                            $grand_cost += $without_acc_adult_total_cost;
                            $grand_sale += $cart_all_data->without_acc_adult_total;
                        }
                     
                     // Profit From Double Childs
                     
                        if($cart_all_data->double_childs > 0){
                            $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                            $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                            $grand_profit += $double_profit;
                            $grand_cost += $double_child_total_cost;
                            $grand_sale += $cart_all_data->double_childs_total;
                        }
                     
                     // Profit From Triple Childs
                     
                      if($cart_all_data->triple_childs > 0){
                            $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                            $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                            $grand_profit += $triple_profit;
                            $grand_cost += $triple_child_total_cost;
                            $grand_sale += $cart_all_data->triple_childs_total;
                        }
                        
                     // Profit From Quad Childs
                     
                        if($cart_all_data->quad_childs > 0){
                            $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                            $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                            $grand_profit += $quad_profit;
                            $grand_cost += $quad_child_total_cost;
                            $grand_sale += $cart_all_data->quad_child_total;
                        }
                     
                     // Profit From Without Acc Child

                        if($cart_all_data->children > 0){
                            $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                            $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                            $grand_profit += $without_acc_profit;
                            $grand_cost += $without_acc_child_total_cost;
                            $grand_sale += $cart_all_data->without_acc_child_total;
                        }

                    // Profit From Double Infant
                        if($cart_all_data->double_infant > 0){
                            $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                            $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                            $grand_profit += $double_profit;
                             $grand_cost += $double_infant_total_cost;
                            $grand_sale += $cart_all_data->double_infant_total;
                        }
                     
                     // Profit From Triple Infant
                     
                        if($cart_all_data->triple_infant > 0){
                            $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                            $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                            $grand_profit += $triple_profit;
                             $grand_cost += $triple_infant_total_cost;
                            $grand_sale += $cart_all_data->triple_infant_total;
                        }
                     
                     // Profit From Quad Infant
                     
                        if($cart_all_data->quad_infant > 0){
                            $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                            $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                            $grand_profit += $quad_profit;
                             $grand_cost += $quad_infant_total_cost;
                            $grand_sale += $cart_all_data->quad_infant_total;
                        }
                     
                     // Profit From Without Acc Infant  
                     
                      if($cart_all_data->infants > 0){
                            $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                            $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                            $grand_profit += $without_acc_profit;
                            $grand_cost += $without_acc_infant_total_cost;
                            $grand_sale += $cart_all_data->without_acc_infant_total;
                      }
                      
                      $over_all_dis = 0;
                    //   echo "Grand Total Profit is $grand_profit "; 
                      if($cart_all_data->discount_type == 'amount'){
                          $final_profit = $grand_profit - $cart_all_data->discount_enter_am;
                      }else{
                          $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                          $final_profit = $grand_profit - $discunt_am_over_all;
                      }
                     
                      
                     
                    //  echo "Grand Total Profit is $final_profit";
                    //  print_r($cart_all_data);
                    
                    $commission_add = '';
                    if(isset($cart_all_data->agent_commsion_add_total)){
                        $commission_add = $cart_all_data->agent_commsion_add_total;
                    }

                     $booking_data = [
                            'invoice_id'=>$tour_res->invoice_no,
                            'booking_id'=>$tour_res->booking_id,
                            'tour_id'=>$tour_res->tour_id,
                            'price'=>$tour_res->tour_total_price,
                            'paid_amount'=>$tour_res->total_paid_amount,
                            'remaing_amount'=> $tour_res->tour_total_price - $tour_res->total_paid_amount,
                            'over_paid_amount'=> $tour_res->over_paid_amount,
                            'tour_name'=>$cart_all_data->name,
                            'profit'=>$final_profit,
                            'discount_am'=>$cart_all_data->discount_Price,
                            'total_cost'=>$grand_cost,
                            'total_sale'=>$grand_sale,
                            'commission_am'=>$cart_all_data->agent_commsion_am,
                            'agent_commsion_add_total'=>$commission_add,
                            'currency'=>$tour_res->currency,
                         ];
                         
                      array_push($booking_all_data,$booking_data);
                 }
                 
                $invoices_all_data = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    
                    $accomodation = json_decode($agent_inv_res->accomodation_details);
                    $accomodation_more = json_decode($agent_inv_res->accomodation_details_more);
                    $markup_details = json_decode($agent_inv_res->markup_details);
                    $more_markup_details = json_decode($agent_inv_res->more_markup_details);
                     
                    // Caluclate Flight Price 
                    $grand_cost = 0;
                    $grand_sale = 0;
                    $flight_cost = 0;
                    $flight_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                            $flight_cost = $mark_res->without_markup_price; 
                            $flight_sale = $mark_res->markup_price; 
                        }
                    }
                            
                    $flight_total_cost = (float)$flight_cost * (float)$agent_inv_res->no_of_pax_days;
                    $flight_total_sale = (float)$flight_sale * (float)$agent_inv_res->no_of_pax_days;
                    
                    $flight_profit = $flight_total_sale - $flight_total_cost;
                    
                    $grand_cost += $flight_total_cost;
                    $grand_sale += $flight_total_sale;
                    
                    // Caluclate Visa Price 
                    $visa_cost = 0;
                    $visa_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                            $visa_cost = $mark_res->without_markup_price; 
                            $visa_sale = $mark_res->markup_price; 
                        }
                    }
                    $visa_total_cost = (float)$visa_cost * (float)$agent_inv_res->no_of_pax_days;
                    $visa_total_sale = (float)$visa_sale * (float)$agent_inv_res->no_of_pax_days;
                    $visa_profit = $visa_total_sale - $visa_total_cost;
                    $grand_cost += $visa_total_cost;
                    $grand_sale += $visa_total_sale;

                    // Caluclate Transportation Price
                    $trans_cost = 0;
                    $trans_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                            $trans_cost = $mark_res->without_markup_price; 
                            $trans_sale = $mark_res->markup_price; 
                        }
                    }
                    $trans_total_cost = (float)$trans_cost * (float)$agent_inv_res->no_of_pax_days;
                    $trans_total_sale = (float)$trans_sale * (float)$agent_inv_res->no_of_pax_days;
                    $trans_profit = $trans_total_sale - $trans_total_cost;
                    $grand_cost += $trans_total_cost;
                    $grand_sale += $trans_total_sale;

                    // Caluclate Double Room Price
                    $double_total_cost = 0;
                    $double_total_sale = 0;
                    $double_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Double'){
                                (float)$double_cost = $accmod_res->acc_total_amount; 
                                (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                                
                                 $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                                 $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                                 $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                                 $double_total_profit = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Double'){
                                $double_cost = $accmod_res->more_acc_total_amount; 
                                $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                                $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                                $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                                $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                                $double_total_profit = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    $grand_cost += $double_total_cost;
                    $grand_sale += $double_total_sale;
                    
                    // Caluclate Triple Room Price
                    $triple_total_cost = 0;
                    $triple_total_sale = 0;
                    $triple_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->acc_total_amount; 
                                $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                                $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    $grand_cost += $triple_total_cost;
                    $grand_sale += $triple_total_sale;
                     
                    // Caluclate Quad Room Price
                    $quad_total_cost = 0;
                    $quad_total_sale = 0;
                    $quad_total_profit = 0;
                    if(isset($accomodation)){
                                foreach($accomodation as $accmod_res){
                                    if($accmod_res->acc_type == 'Quad'){
                                        $quad_cost = $accmod_res->acc_total_amount; 
                                        $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                        
                                         $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                         $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                         $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                         $quad_total_profit = $quad_total_profit + $quad_profit;
                                    }
                                }
                             }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Quad'){
                                $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                                $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                                $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                                $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                                $quad_total_profit = $quad_total_profit + $quad_profit;
                            }
                        }
                    }
                    $grand_cost += $quad_total_cost;
                    $grand_sale += $quad_total_sale;
                    
                    // Caluclate Final Price
                    $Final_inv_price = $flight_total_sale + $visa_total_sale + $trans_total_sale + $double_total_sale + $triple_total_sale + $quad_total_sale;
                    $Final_inv_profit = $flight_profit + $visa_profit + $trans_profit + $double_total_profit + $triple_total_profit + $quad_total_profit;
                    
                    $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'currency'=>$agent_inv_res->currency_symbol,
                    ];
                    array_push($invoices_all_data,$inv_single_data);
                     
                }
                
                $invoice_Acc_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $accomodation       = json_decode($agent_inv_res->accomodation_details);
                    $accomodation_more  = json_decode($agent_inv_res->accomodation_details_more);
                     
                    // Caluclate Double Room Price
                    $double_total_cost = 0;
                    $double_total_sale = 0;
                    $double_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Double'){
                                (float)$double_cost = $accmod_res->acc_total_amount; 
                                (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                                
                                 $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                                 $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                                 $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                                 $double_total_profit = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Double'){
                                $double_cost = $accmod_res->more_acc_total_amount; 
                                $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                                $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                                $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                                $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                                $double_total_profit = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    $grand_cost += $double_total_cost;
                    $grand_sale += $double_total_sale;
                    
                    // Caluclate Triple Room Price
                    $triple_total_cost = 0;
                    $triple_total_sale = 0;
                    $triple_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->acc_total_amount; 
                                $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                                $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    $grand_cost += $triple_total_cost;
                    $grand_sale += $triple_total_sale;
                     
                    // Caluclate Quad Room Price
                    $quad_total_cost = 0;
                    $quad_total_sale = 0;
                    $quad_total_profit = 0;
                    if(isset($accomodation)){
                                foreach($accomodation as $accmod_res){
                                    if($accmod_res->acc_type == 'Quad'){
                                        $quad_cost = $accmod_res->acc_total_amount; 
                                        $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                        
                                         $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                         $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                         $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                         $quad_total_profit = $quad_total_profit + $quad_profit;
                                    }
                                }
                             }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Quad'){
                                $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                                $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                                $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                                $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                                $quad_total_profit = $quad_total_profit + $quad_profit;
                            }
                        }
                    }
                    $grand_cost += $quad_total_cost;
                    $grand_sale += $quad_total_sale;
                    
                    // Caluclate Final Price
                    $Final_inv_price    = $double_total_sale + $triple_total_sale + $quad_total_sale;
                    $Final_inv_profit   = $double_total_profit + $triple_total_profit + $quad_total_profit;
                    
                    $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'        => $agent_inv_res->id,
                        'price'             => $agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'       => $agent_inv_res->total_paid_amount,
                        'remaing_amount'    => $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'  => $agent_inv_res->over_paid_amount,
                        'profit'            => $Final_inv_profit,
                        'total_cost'        => $grand_cost,
                        'total_sale'        => $grand_sale,
                        'currency'          => $agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Acc_details,$inv_single_data);
                }
                
                $invoice_Flight_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $markup_details         = json_decode($agent_inv_res->markup_details);
                    $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
                     
                    // Caluclate Flight Price 
                    $grand_cost = 0;
                    $grand_sale = 0;
                    $flight_cost = 0;
                    $flight_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                            $flight_cost = $mark_res->without_markup_price; 
                            $flight_sale = $mark_res->markup_price; 
                        }
                    }
                            
                    $flight_total_cost = (float)$flight_cost * (float)$agent_inv_res->no_of_pax_days;
                    $flight_total_sale = (float)$flight_sale * (float)$agent_inv_res->no_of_pax_days;
                    
                    $flight_profit = $flight_total_sale - $flight_total_cost;
                    
                    $grand_cost += $flight_total_cost;
                    $grand_sale += $flight_total_sale;
                    
                    // Caluclate Final Price
                    $Final_inv_price    = $flight_total_sale;
                    $Final_inv_profit   = $flight_profit;
                    
                    $invoice_payments   = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount  = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'currency'=>$agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Flight_details,$inv_single_data);
                     
                }
                
                $invoice_Visa_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $markup_details         = json_decode($agent_inv_res->markup_details);
                    $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
                  // Caluclate Visa Price 
                    $visa_cost = 0;
                    $visa_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                            $visa_cost = $mark_res->without_markup_price; 
                            $visa_sale = $mark_res->markup_price; 
                        }
                    }
                    $visa_total_cost = (float)$visa_cost * (float)$agent_inv_res->no_of_pax_days;
                    $visa_total_sale = (float)$visa_sale * (float)$agent_inv_res->no_of_pax_days;
                    $visa_profit = $visa_total_sale - $visa_total_cost;
                    $grand_cost += $visa_total_cost;
                    $grand_sale += $visa_total_sale;

                    // Caluclate Final Price
                    $Final_inv_price    = $visa_total_sale;
                    $Final_inv_profit   = $visa_profit;
                    
                    $invoice_payments   = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount  = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'currency'=>$agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Visa_details,$inv_single_data);
                     
                }
                
                $invoice_Transportation_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $markup_details         = json_decode($agent_inv_res->markup_details);
                    $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
                   
                    // Caluclate Transportation Price
                    $trans_cost = 0;
                    $trans_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                            $trans_cost = $mark_res->without_markup_price; 
                            $trans_sale = $mark_res->markup_price; 
                        }
                    }
                    $trans_total_cost = (float)$trans_cost * (float)$agent_inv_res->no_of_pax_days;
                    $trans_total_sale = (float)$trans_sale * (float)$agent_inv_res->no_of_pax_days;
                    $trans_profit = $trans_total_sale - $trans_total_cost;
                    $grand_cost += $trans_total_cost;
                    $grand_sale += $trans_total_sale;

                    // Caluclate Final Price
                    $Final_inv_price    = $trans_total_sale;
                    $Final_inv_profit   = $trans_profit;
                    
                    $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'currency'=>$agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Transportation_details,$inv_single_data);
                     
                }
                
                
                
                
                $agent_data = [
                        'agent_id'=>$agent_res->id,
                        'agent_name'=>$agent_res->agent_Name,
                        'agent_over_paid'=>$agent_res->over_paid_am,
                        'agent_company'=>$agent_res->company_name,
                        'balance'=>$agent_res->balance,
                        'opening_balance'=>$agent_res->opening_balance,
                        'agent_ledger_data'=>$agent_ledgers,
                        'agents_tour_booking'=>$booking_all_data,
                        'agents_invoices_booking'=>$invoices_all_data,
                        'invoice_Acc_details'               => $invoice_Acc_details,
                        'invoice_Flight_details'            => $invoice_Flight_details,
                        'invoice_Visa_details'              => $invoice_Visa_details,
                        'invoice_Transportation_details'    => $invoice_Transportation_details,
                    ];
                array_push($all_agents_data,$agent_data);         
            }
        }
        
        $separate_Revenue_accomodation      = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['accomodation_tab'])
                                                ->sum('total_sale_price_all_Services');

        $separate_Revenue_flight            = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['flights_tab'])
                                                ->sum('total_sale_price_all_Services');
        
        $separate_Revenue_visa              = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['visa_tab'])
                                                ->sum('total_sale_price_all_Services');
        
        $separate_Revenue_transportation    = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['transportation_tab'])
                                                ->sum('total_sale_price_all_Services');
                                                
        $separate_package_booking           = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                                ->where('tours_bookings.customer_id',$request->customer_id)
                                                ->where('cart_details.agent_name',$request->agent_id)
                                                ->where('cart_details.pakage_type','tour')
                                                ->get();
        if(count($separate_package_booking) > 0){
            foreach($separate_package_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $separate_package_grand_profit = 0;
                $separate_package_cost_price   = 0;
                $separate_package_Revenue      = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $separate_package_Revenue = 0;
        }
        
        $separate_activity_booking          = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                                ->where('tours_bookings.customer_id',$request->customer_id)
                                                ->where('cart_details.agent_name',$request->agent_id)
                                                ->where('cart_details.pakage_type','activity')
                                                ->get();
        if(count($separate_activity_booking) > 0){
            foreach($separate_activity_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $separate_activity_grand_profit = 0;
                $separate_activity_cost_price   = 0;
                $separate_activity_Revenue      = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $separate_activity_Revenue = 0;
        }
        
        return response()->json(['message'=>'success',
            'agent_data'                        => $all_agents_data,
            'separate_Revenue_accomodation'     => $separate_Revenue_accomodation,
            'separate_Revenue_flight'           => $separate_Revenue_flight,
            'separate_Revenue_visa'             => $separate_Revenue_visa,
            'separate_Revenue_transportation'   => $separate_Revenue_transportation,
            'separate_package_Revenue'          => $separate_package_Revenue,
            'separate_activity_Revenue'         => $separate_activity_Revenue,
        ]);
    }
    
    function supplier_stats_details(Request $request){
        // print_r($request->all());
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $supplier_data = DB::table('supplier')->where('id',$request->supplier_id)->first();
            
            
                $supplier_flights = DB::table('Flight_sup_seats')->where('supplier',$supplier_data->id)->
                    select('id','flight_type','selected_flight_airline','flights_per_person_price','flights_number_of_seat','occupied_seat','flights_per_child_price','flights_per_infant_price','flight_total_price','flight_paid_amount','over_paid_amount')->get();
                // $seats_booking = [];
                // foreach($supplier_flights as $flight_res){
                    
                //     $seats_data = [
                //         'seat_data' =>$flight_res
                //         ];
                    
                //     array_push($seats_booking,$seats_data);
                // //       $flight_packages = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.flight_id',$flight_res->id)
                // //       ->select('tours.id','tours.title','tours.currency_symbol','tours_2.flights_details','tours_2.flights_details_more','tours_2.markup_details','tours_2.more_markup_details','tours_2.child_flight_cost_price','tours_2.child_flight_sale_price','tours_2.infant_flight_cost','tours_2.in_markup_other_c')->get();
                
                // //     print_r($flight_packages);
                    
                // //         foreach($flight_packages as $flight_res){
                            
                // //             $all_markups = json_decode($flight_res->markup_details);
                // //             $adult_flight_sale_price = 0;
                // //             $child_flight_sale_price = $flight_res->child_flight_sale_price;
                // //             $infant_flight_sale_price = 0;
                            
                // //             foreach($all_markups as $mark_res){
                // //                 if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                // //                     $adult_flight_sale_price = $mark_res->markup_price;
                // //                 }
                // //             }
                            
                          
                // //             $infants_markups = json_decode($flight_res->in_markup_other_c);
                // //             // print_r($infants_markups);
                // //               $infant_flight_sale_price = $infants_markups[0]->infant_flight_sale;
                              
                // //             echo "Flight Adult sale Price $adult_flight_sale_price Child $child_flight_sale_price and Infant $infant_flight_sale_price ";
                // //         }
                
                // }
                
                
                // print_r($supplier_flights);
                // die;
                
                // $agents_invoice_booking = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->id)->select('total_sale_price_all_Services','total_paid_amount','over_paid_amount','accomodation_details','accomodation_details_more','markup_details','more_markup_details','no_of_pax_days','id','agent_Id','currency_symbol')->get();
                 
                // //  print_r($agents_invoice_booking);
                 
                // //  die;
                //  $booking_all_data = [];
               
                 
            
                 
                     $supplier_all_data = ['supplier_id'=>$supplier_data->id,
                                 'supplier_name'=>$supplier_data->companyname,
                                 'supplier_wallet'=>$supplier_data->wallet_amount,
                                 'supplier_seats_booking'=>$supplier_flights,
                                 ];

                                 
            
           
        }
        
                     
        
        // die;
        
        return response()->json(['message'=>'success','supplier_data'=>$supplier_all_data]);
        // print_r($request->all());
    }
    
    function update_booking(Request $request){
        $cart_data = json_decode($request->cart_data);
        
        function dateDiffInDays($date1, $date2){
                $diff = strtotime($date2) - strtotime($date1);
                return abs(round($diff / 86400));
            }
        
        function getBetweenDates($startDate, $endDate){
                $rangArray = [];
                $startDate = strtotime($startDate);
                $endDate = strtotime($endDate);
                     
                     $startDate += (86400); 
                for ($currentDate = $startDate; $currentDate <= $endDate; 
                                                $currentDate += (86400)) {
                                                        
                    $date = date('Y-m-d', $currentDate);
                    $rangArray[] = $date;
                }
          
                return $rangArray;
            }
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        
        if($userData){    
            foreach($cart_data[0] as $cart_res){
                if($request->cart_visa != "null"){
                    $cart_visa_data = json_decode($request->cart_visa);
                    $cart_visa_data_save = '';
                    foreach($cart_visa_data as $visa_res){
                        $cart_visa_data_save = $visa_res;
                    }
                }else{
                    $cart_visa_data_save = "";
                }
                
                $Cart_details = Cart_details::where('invoice_no',$request->invoice_no)->first();
                $cart_prev_data = json_decode($Cart_details->cart_total_data);
                $cart_new_data = $cart_res;
                
                $Cart_details->tour_id = $cart_res->tourId;
                $Cart_details->cart_total_data = json_encode($cart_res);
                $Cart_details->visa_change_data = json_encode($cart_visa_data_save);
                
                $Cart_details->adults = $cart_res->adults;
                $Cart_details->childs = $cart_res->children;
                $Cart_details->sigle_price = $cart_res->sigle_price;
                $Cart_details->tour_total_price = $cart_res->tour_total_price;
                $Cart_details->total_service_price = $cart_res->total_service_price;
                $Cart_details->price = $cart_res->price;
                
                $Cart_details->sharing2 = $cart_res->sharing2;
                $Cart_details->sharing3 = $cart_res->sharing3;
                $Cart_details->sharing4 = $cart_res->sharing4;
                $Cart_details->sharingSelect = $cart_res->sharingSelect;
                $Cart_details->currency = $cart_res->currency;
                $Cart_details->pakage_type = $cart_res->pakage_type;
                
                if(isset($cart_res->child_price)){
                    $child_price = $cart_res->child_price;
                    $cost_price_child = $cart_res->cost_price_child;
                    $adult_total_price = $cart_res->adult_total_price;
                    $child_total_price = $cart_res->child_total_price;
                    $Cart_details->childs = 0;
                }else{
                    $child_price = 0;
                    $cost_price_child = 0;
                    $adult_total_price = 0;
                    $child_total_price = 0;
                }
                
                $Cart_details->child_price_tour = $child_price;
                $Cart_details->child_cost_price = $cost_price_child;
                $Cart_details->adult_total_price = $adult_total_price;
                $Cart_details->child_total_price = $child_total_price;
                 
                if(isset($cart_res->double_adults)){
                    $Cart_details->double_adults = $cart_res->double_adults;
                }
                 
                if(isset($cart_res->triple_adults)){
                    $Cart_details->triple_adults = $cart_res->triple_adults;
                }
                 
                if(isset($cart_res->quad_adults)){
                    $Cart_details->quad_adults = $cart_res->quad_adults;
                }
                 
                if(isset($cart_res->without_acc_adults)){
                    $Cart_details->without_acc_adults = $cart_res->without_acc_adults;
                }
                 
                if(isset($cart_res->double_rooms)){
                    $Cart_details->double_room = $cart_res->double_rooms;
                }
                 
                if(isset($cart_res->triple_rooms)){
                    $Cart_details->triple_room = $cart_res->triple_rooms;
                }
                 
                if(isset($cart_res->quad_rooms)){
                    $Cart_details->quad_room = $cart_res->quad_rooms;
                }
                 
                if(isset($cart_res->without_acc_sale_price)){
                    $Cart_details->without_acc_sale_price = $cart_res->without_acc_sale_price;
                }
                 
                if(isset($cart_res->agent_name)){
                    $Cart_details->agent_name = $cart_res->agent_name;
                }
                
                DB::beginTransaction();
                try {
                    $Cart_data = $Cart_details->update();
                    
                    // Passenger Name
                    if(isset($request->passenger_name) && !empty($request->passenger_name)){
                        DB::table('tours_bookings')->where('invoice_no',$request->invoice_no)->update(['passenger_name'=>$request->passenger_name]);
                    }
                    
                    // Adult and Childs
                    $tours_bookings                             = DB::table('tours_bookings')->where('invoice_no',$request->invoice_no)->first();
                    if(isset($request->lead_Details)){
                        $lead_Details                           = json_decode($request->lead_Details);
                        if(!empty($lead_Details)){
                            $passenger_detail                   = json_decode($tours_bookings->passenger_detail);
                            // dd($passenger_detail,$lead_Details);
                            $passenger_detail[0]->lead_title    = $lead_Details->lead_title;
                            $passenger_detail[0]->name          = $lead_Details->name;
                            $passenger_detail[0]->lname         = $lead_Details->lname;
                            $passenger_detail[0]->email         = $lead_Details->email;
                            $passenger_detail[0]->phone         = $lead_Details->phone;
                            $passenger_detail[0]->country       = $lead_Details->country;
                            $passenger_detail[0]->gender        = $lead_Details->gender;
                            $passenger_detail                   = json_encode($passenger_detail);
                            DB::table('tours_bookings')->where('invoice_no',$request->invoice_no)->update(['passenger_detail'=>$passenger_detail]);
                        }
                    }
                    
                    if(isset($request->lead_Details)){
                        $adult_Details                          = json_decode($request->adult_Details);
                        if(!empty($adult_Details)){
                            $tours_bookings->adults_detail      = json_encode($adult_Details);
                            DB::table('tours_bookings')->where('invoice_no',$request->invoice_no)->update(['adults_detail'=>$adult_Details]);
                        }
                    }
                    
                    if(isset($request->child_Details)){
                        $child_Details                          = json_decode($request->child_Details);
                        if(!empty($child_Details)){
                            $tours_bookings->child_detail       = json_encode($child_Details);
                            DB::table('tours_bookings')->where('invoice_no',$request->invoice_no)->update(['child_detail'=>$child_Details]);
                        }
                    }
                    // Adult and Childs
                    
                    DB::table('view_booking_payment_recieve')->where('package_id',$Cart_details->booking_id)->update(['total_amount'=>$cart_res->price]);
                    
                    if($cart_prev_data->agent_name != $cart_new_data->agent_name){
                         
                         // Agent is Changed
                         // previous Agent Working
                         if($cart_prev_data->agent_name != '-1' && $cart_prev_data->agent_name != ''){
                              
                              
                                $agent_data = DB::table('Agents_detail')->where('id',$cart_prev_data->agent_name)->select('id','balance')->first();
       
                                if(isset($agent_data)){
                                    // echo "Enter hre ";
                                    $received_amount = -1 * abs($cart_prev_data->tour_total_price);
                                    $agent_balance = $agent_data->balance - $cart_prev_data->tour_total_price;
                                    
                                    // update Agent Balance
                                   
                                    DB::table('agents_ledgers_new')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$agent_balance,
                                        'package_invoice_no'=>$Cart_details->invoice_no,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                  DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                }
                        
                         }
                         
                         // New Agent Working
                         if($cart_new_data->agent_name != '-1' && $cart_new_data->agent_name != ''){
                             
                              
                                $agent_data = DB::table('Agents_detail')->where('id',$cart_new_data->agent_name)->select('id','balance')->first();
       
                                if(isset($agent_data)){
                                    // echo "Enter hre ";
                                    $received_amount = $cart_new_data->tour_total_price;
                                    $agent_balance = $agent_data->balance + $cart_new_data->tour_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('agents_ledgers_new')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$agent_balance,
                                        'package_invoice_no'=>$Cart_details->invoice_no,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                  DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                }
                        
                         }
                         
                    }else{
                         // Agent is Not Changed
                         
                         if($cart_prev_data->agent_name != '-1' && $cart_prev_data->agent_name != ''){
                              $difference  = $cart_new_data->tour_total_price - $cart_prev_data->tour_total_price;
                              

                                $agent_data = DB::table('Agents_detail')->where('id',$cart_new_data->agent_name)->select('id','balance')->first();
       
                                if(isset($agent_data)){
                                    // echo "Enter hre ";
                                    $agent_balance = $agent_data->balance + $difference;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('agents_ledgers_new')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'received'=>$difference,
                                        'balance'=>$agent_balance,
                                        'package_invoice_no'=>$Cart_details->invoice_no,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                }
                        
                         }
                     }
                    
                    if(isset($cart_prev_data->customer_id) && $cart_prev_data->customer_id != $cart_new_data->customer_id){
                         
                         // Agent is Changed
                         // previous Agent Working
                         if($cart_prev_data->customer_id != '-1' && $cart_prev_data->customer_id != ''){
                              
                              
                                $customer_data = DB::table('booking_customers')->where('id',$cart_prev_data->customer_id)->select('id','balance')->first();
       
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $received_amount = -1 * abs($cart_prev_data->tour_total_price);
                                    $customer_balance = $customer_data->balance - $cart_prev_data->tour_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$customer_balance,
                                        'package_invoice_no'=>$Cart_details->invoice_no,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                }
                        
                         }
                         
                         // New Agent Working
                         if(isset($cart_prev_data->customer_id) && $cart_new_data->customer_id != '-1' && $cart_new_data->customer_id != ''){
                             
                              
                                $customer_data = DB::table('booking_customers')->where('id',$cart_new_data->customer_id)->select('id','balance')->first();
       
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $received_amount = $cart_new_data->tour_total_price;
                                    $customer_balance = $customer_data->balance + $cart_new_data->tour_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$customer_balance,
                                        'package_invoice_no'=>$Cart_details->invoice_no,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                        DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                }
                        
                         }
                         
                    }else{
                         // Agent is Not Changed
                         
                         if(isset($cart_prev_data->customer_id) && $cart_prev_data->customer_id != '-1' && $cart_prev_data->customer_id != ''){
                              $difference  = $cart_new_data->tour_total_price - $cart_prev_data->tour_total_price;
                              

                                $customer_data = DB::table('booking_customers')->where('id',$cart_new_data->customer_id)->select('id','balance')->first();
       
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $customer_balance = $customer_data->balance + $difference;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$difference,
                                        'balance'=>$customer_balance,
                                        'package_invoice_no'=>$Cart_details->invoice_no,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                        DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                }
                        
                         }
                     }
                    
                    if(!isset($cart_prev_data->customer_id)){
                          if($cart_new_data->customer_id != '-1' && $cart_new_data->customer_id != ''){
                              $difference  = $cart_new_data->tour_total_price;
                              

                                $customer_data = DB::table('booking_customers')->where('id',$cart_new_data->customer_id)->select('id','balance')->first();
       
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $customer_balance = $customer_data->balance + $difference;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$difference,
                                        'balance'=>$customer_balance,
                                        'package_invoice_no'=>$Cart_details->invoice_no,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                        DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                }
                        
                         }
                     }
                    
                    $tours_data = DB::table('tours')->where('id',$cart_res->tourId)->select('accomodation_details','accomodation_details_more')->first();
                    $accomodation = json_decode($tours_data->accomodation_details);
                    $more_accomodation_details = json_decode($tours_data->accomodation_details_more);
                    
                    DB::table('rooms_bookings_details')->where("booking_id","$Cart_details->invoice_no")->delete();
                    
                    if(isset($accomodation)){
                        foreach($accomodation as $accomodation_res){
                            if(isset($accomodation_res->hotelRoom_type_idM)){
                            $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                        
                           if($room_data){
                            
                                    $rooms_qty_prev = 0;
                                      if(isset($cart_prev_data->double_rooms)){
                                            if($accomodation_res->acc_type == 'Double'){
                                                 $rooms_qty_prev = $cart_prev_data->double_rooms;
                                            }
                                             
                                     }
                                     
                                      if(isset($cart_prev_data->triple_rooms)){
                                            if($accomodation_res->acc_type == 'Triple'){
                                                 $rooms_qty_prev = $cart_prev_data->triple_rooms;
                                            }
                                     }
                                     
                                      if(isset($cart_prev_data->quad_rooms)){
                                          if($accomodation_res->acc_type == 'Quad'){
                                                 $rooms_qty_prev = $cart_prev_data->quad_rooms;
                                            }
                                     }
                                     
                                     $rooms_qty_new = 0;
                                      if(isset($cart_new_data->double_rooms)){
                                            if($accomodation_res->acc_type == 'Double'){
                                                 $rooms_qty_new = $cart_new_data->double_rooms;
                                            }
                                             
                                     }
                                     
                                      if(isset($cart_new_data->triple_rooms)){
                                            if($accomodation_res->acc_type == 'Triple'){
                                                 $rooms_qty_new = $cart_new_data->triple_rooms;
                                            }
                                     }
                                     
                                     if(isset($cart_new_data->quad_rooms)){
                                          if($accomodation_res->acc_type == 'Quad'){
                                                 $rooms_qty_new = $cart_new_data->quad_rooms;
                                            }
                                     }
                                     
                                     $room_diff = $rooms_qty_new - $rooms_qty_prev;
                                     $update_booked_qty = $room_data->booked + $room_diff;
                                     
                                     $result = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->update(['booked'=>$update_booked_qty]);
    
                                    // echo "update booking qty is ".$update_booked_qty;
                                  
                                     DB::table('rooms_bookings_details')->insert([
                                         'room_id'=> $accomodation_res->hotelRoom_type_id,
                                         'booking_from'=>'package',
                                         'quantity'=>$rooms_qty_new,
                                         'booking_id'=>$Cart_details->invoice_no,
                                         'package_id'=>$cart_prev_data->tourId,
                                         'date'=>date('Y-m-d'),
                                         'check_in'=>$accomodation_res->acc_check_in,
                                         'check_out'=>$accomodation_res->acc_check_out,
                                     ]);
                                     
                           
                                         
                            // Update Hotel Supplier Balance
                    
                             $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
    
                             if(isset($supplier_data)){
                                    // echo "Enter hre ";
                                    
                                         $week_days_total = 0;
                                         $week_end_days_totals = 0;
                                         $total_price = 0;
                                         $accomodation_res->acc_check_in = date('Y-m-d',strtotime($accomodation_res->acc_check_in));
                                         $accomodation_res->acc_check_out = date('Y-m-d',strtotime($accomodation_res->acc_check_out));
                                        if($room_data->price_week_type == 'for_all_days'){
                                            $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                            $total_price = $room_data->price_all_days * $avaiable_days;
                                        }else{
                                             $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                             
                                             $all_days = getBetweenDates($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                             
                                            //  print_r($all_days);
                                             $week_days = json_decode($room_data->weekdays);
                                             $week_end_days = json_decode($room_data->weekends);
                                             
                                             foreach($all_days as $day_res){
                                                 $day = date('l', strtotime($day_res));
                                                 $day = trim($day);
                                                 $week_day_found = false;
                                                 $week_end_day_found = false;
                                                
                                                 foreach($week_days as $week_day_res){
                                                     if($week_day_res == $day){
                                                         $week_day_found = true;
                                                     }
                                                 }
                                          
                                                // echo "  ".$room_data->weekdays_price;
                                                 if($week_day_found){
                                                     $week_days_total += $room_data->weekdays_price;
                                                 }else{
                                                     $week_end_days_totals += $room_data->weekends_price;
                                                 }
                                                 
                                                 
                                                //  foreach($week_end_days as $week_day_res){
                                                //      if($week_day_res == $day){
                                                //          $week_end_day_found = true;
                                                //      }
                                                //  }
                                                //   if($week_end_day_found){
                                                      
                                                //  }
                                             }
                                             
                                             
                                            //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                             
                                            //  print_r($all_days);
                                             $total_price = $week_days_total + $week_end_days_totals;
                                        }
                                        
                                        
                                    $all_days_price = $total_price * $room_diff;
                                    
                                    // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                    // die;
                                    
                                    // echo "The supplier Balance is ".$supplier_data->balance;
                                    $supplier_balance = $supplier_data->balance;
                                    $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                    
                                    // update Agent Balance
                                    
                                    if($all_days_price != 0){
                                         DB::table('hotel_supplier_ledger')->insert([
                                        'supplier_id'=>$supplier_data->id,
                                        'payment'=>$all_days_price,
                                        'balance'=>$supplier_balance,
                                        'payable_balance'=>$supplier_payable_balance,
                                        'room_id'=>$room_data->id,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        'package_invoice_no'=>$Cart_details->invoice_no,
                                        'available_from'=>$accomodation_res->acc_check_in,
                                        'available_to'=>$accomodation_res->acc_check_out,
                                        'room_quantity'=>$room_diff,
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                         DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                            'balance'=>$supplier_balance,
                                            'payable'=>$supplier_payable_balance
                                        ]);
                                    }
                                    
                                   
                                    
                                    
                                      
                                                                
                                }
                        
                           }
                         }
                        }
                    }
                    
                    if(isset($more_accomodation_details)){
                        foreach($more_accomodation_details as $accomodation_res){
                            if(isset($accomodation_res->more_hotelRoom_type_idM)){
                            $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                            
                            // print_r($room_data);
                            if($room_data){
                            
                                    // $total_booked = $room_data->booked + $accomodation_res->more_acc_qty;
                                    
                                    
                                    
                                    
                                        $rooms_qty_prev = 0;
                                          if(isset($cart_prev_data->double_rooms)){
                                                    if($accomodation_res->more_acc_type == 'Double'){
                                                         $rooms_qty_prev = $cart_prev_data->double_rooms;
                                                    }
                                                     
                                             }
                                             
                                              if(isset($cart_prev_data->triple_rooms)){
                                                    if($accomodation_res->more_acc_type == 'Triple'){
                                                         $rooms_qty_prev = $cart_prev_data->triple_rooms;
                                                    }
                                             }
                                             
                                              if(isset($cart_prev_data->quad_rooms)){
                                                  if($accomodation_res->more_acc_type == 'Quad'){
                                                         $rooms_qty_prev = $cart_prev_data->quad_rooms;
                                                    }
                                             }
                                             
                                             
                                        $rooms_qty_new = 0;
                                          if(isset($cart_new_data->double_rooms)){
                                                    if($accomodation_res->more_acc_type == 'Double'){
                                                         $rooms_qty_new = $cart_new_data->double_rooms;
                                                    }
                                                     
                                             }
                                             
                                              if(isset($cart_new_data->triple_rooms)){
                                                    if($accomodation_res->more_acc_type == 'Triple'){
                                                         $rooms_qty_new = $cart_new_data->triple_rooms;
                                                    }
                                             }
                                             
                                              if(isset($cart_new_data->quad_rooms)){
                                                  if($accomodation_res->more_acc_type == 'Quad'){
                                                         $rooms_qty_new = $cart_new_data->quad_rooms;
                                                    }
                                             }
                                             
                                            //  $total_booked = $room_data->booked + $rooms_qty;
                                   
                                    
                                             $room_diff = $rooms_qty_new - $rooms_qty_prev;
                                             $update_booked_qty = $room_data->booked + $room_diff;
                                             
                                             $result = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->update(['booked'=>$update_booked_qty]);
            
                                            // echo "update booking qty is ".$update_booked_qty;
                                        
                                             DB::table('rooms_bookings_details')->insert([
                                                 'room_id'=> $accomodation_res->more_hotelRoom_type_id,
                                                 'booking_from'=>'package',
                                                 'quantity'=>$rooms_qty_new,
                                                 'booking_id'=>$Cart_details->invoice_no,
                                                 'package_id'=>$cart_prev_data->tourId,
                                                 'date'=>date('Y-m-d'),
                                                 'check_in'=>$accomodation_res->more_acc_check_in,
                                                 'check_out'=>$accomodation_res->more_acc_check_out,
                                             ]);
                                             
                                             
                                              // Update Hotel Supplier Balance
                            
                                     $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
            
                                     if(isset($supplier_data)){
                                            // echo "Enter hre ";
                                            
                                                 $week_days_total = 0;
                                                 $week_end_days_totals = 0;
                                                 $total_price = 0;
                                                 $accomodation_res->more_acc_check_in = date('Y-m-d',strtotime($accomodation_res->more_acc_check_in));
                                                 $accomodation_res->more_acc_check_out = date('Y-m-d',strtotime($accomodation_res->more_acc_check_out));
                                                if($room_data->price_week_type == 'for_all_days'){
                                                    $avaiable_days = dateDiffInDays($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                    $total_price = $room_data->price_all_days * $avaiable_days;
                                                }else{
                                                     $avaiable_days = dateDiffInDays($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                     
                                                     $all_days = getBetweenDates($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                     
                                                    //  print_r($all_days);
                                                     $week_days = json_decode($room_data->weekdays);
                                                     $week_end_days = json_decode($room_data->weekends);
                                                     
                                                     foreach($all_days as $day_res){
                                                         $day = date('l', strtotime($day_res));
                                                         $day = trim($day);
                                                         $week_day_found = false;
                                                         $week_end_day_found = false;
                                                        
                                                         foreach($week_days as $week_day_res){
                                                             if($week_day_res == $day){
                                                                 $week_day_found = true;
                                                             }
                                                         }
                                                  
                                                        // echo "  ".$room_data->weekdays_price;
                                                         if($week_day_found){
                                                             $week_days_total += $room_data->weekdays_price;
                                                         }else{
                                                             $week_end_days_totals += $room_data->weekends_price;
                                                         }
                                                         
                                                         
                                                        //  foreach($week_end_days as $week_day_res){
                                                        //      if($week_day_res == $day){
                                                        //          $week_end_day_found = true;
                                                        //      }
                                                        //  }
                                                        //   if($week_end_day_found){
                                                              
                                                        //  }
                                                     }
                                                     
                                                     
                                                    //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                     
                                                    //  print_r($all_days);
                                                     $total_price = $week_days_total + $week_end_days_totals;
                                                }
                                                
                                                
                                            $all_days_price = $total_price * $room_diff;
                                            
                                            // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                            // die;
                                            
                                            // echo "The supplier Balance is ".$supplier_data->balance;
                                            $supplier_balance = $supplier_data->balance;
                                            $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                            
                                            // update Agent Balance
                                            
                                            if($all_days_price != 0){
                                                 DB::table('hotel_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'payment'=>$all_days_price,
                                                'balance'=>$supplier_balance,
                                                'payable_balance'=>$supplier_payable_balance,
                                                'room_id'=>$room_data->id,
                                                'customer_id'=>$userData->id,
                                                'date'=>date('Y-m-d'),
                                                'package_invoice_no'=>$Cart_details->invoice_no,
                                                'available_from'=>$accomodation_res->more_acc_check_in,
                                                'available_to'=>$accomodation_res->more_acc_check_out,
                                                'room_quantity'=>$room_diff,
                                                'remarks'=>'Invoice Updated',
                                                ]);
                                                
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                        'balance'=>$supplier_balance,
                                                        'payable'=>$supplier_payable_balance
                                                    ]);
                                            }
                                            
                                           
                                            
                                            
                                              
                                                                        
                                        }
                                             
                        
                        
                            }
                         }  
                        }
                    }
                    
                    $tours_data = DB::table('tours_2')->where('tour_id',$cart_prev_data->tourId)->select('flight_supplier','flight_route_id_occupied','flights_per_person_price')->first();
                    
                    $flight_data = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                    $tours_data_new = DB::table('tours_2')->where('tour_id',$cart_new_data->tourId)->select('flight_supplier','flight_route_id_occupied','flights_per_person_price')->first();
                    
                    $flight_data_new = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                    $cart_new_data = $cart_res;
                    if(isset($flight_data) && isset($flight_data_new)){
                        
                            $ele_found = false;
                     
                                if($flight_data->id == $flight_data_new->id){
                                    $ele_found = true;
                                    $route_obj = $flight_data;
                                    // print_r($route_obj);
                                    // die;
                                    
                                    // Calaculate Child Prev Price Differ
                                    $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $cart_prev_data->total_childs;
                                    $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $cart_prev_data->total_childs;
                                    
                                    $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                    
                                    // Calaculate Child New Price Differ
                                    $child_price_wi_adult_price_new = $route_obj->flights_per_person_price * $cart_new_data->total_childs;
                                    $child_price_wi_child_price_new = $route_obj->flights_per_child_price * $cart_new_data->total_childs;
                                    
                                    $price_diff_new = $child_price_wi_adult_price_new - $child_price_wi_child_price_new;
                                    
                                    // Calculate Final Differ
                                    $child_price_diff = $price_diff_new - $price_diff_prev;
                                    
                                    
                                    // Calaculate Infant Prev Price
                                    $infant_price_prev = $route_obj->flights_per_infant_price * $cart_prev_data->total_Infants;
                                    
                                     // Calaculate Infant New Price
                                    $infant_price_new = $route_obj->flights_per_infant_price * $cart_new_data->total_Infants;
                                    
                                    // Calculate Final Differ
                                    $infant_price_diff = $infant_price_new - $infant_price_prev;
                                    
                                    
                                    
                                    $supplier_data = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                    
                                    if($child_price_diff != 0 || $infant_price_diff != 0){
                                        $supplier_balance = $supplier_data->balance - $child_price_diff;
                                        
                                        $supplier_balance = $supplier_balance + $infant_price_diff;
                                        $total_differ = $infant_price_diff - $child_price_diff;
                                        
                                        DB::table('flight_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$total_differ,
                                                    'balance'=>$supplier_balance,
                                                    'route_id'=>$flight_data->id,
                                                    'date'=>date('Y-m-d'),
                                                    'customer_id'=>$userData->id,
                                                    'adult_price'=>$flight_data->flights_per_person_price,
                                                    'child_price'=>$flight_data->flights_per_child_price,
                                                    'infant_price'=>$flight_data->flights_per_infant_price,
                                                    'adult_seats_booked'=>$cart_res->total_adults,
                                                    'child_seats_booked'=>$cart_res->total_childs,
                                                    'infant_seats_booked'=>$cart_res->total_Infants,
                                                    'package_invoice_no'=>$Cart_details->invoice_no,
                                                    'remarks'=>'Package Invoice Updated',
                                                  ]);
                                                  
                                        DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                    }
                                    
                                }else{
                                    
                                        // Remove From Previous
                                        $route_obj = $flight_data;
                                        // print_r($route_obj);
                                        // die;
                                        
                                        // Calaculate Child Prev Price Differ
                                        $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $cart_prev_data->total_childs;
                                        $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $cart_prev_data->total_childs;
                                        
                                        $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                        
                                        
                                        // Calaculate Infant Prev Price
                                        $infant_price_prev = $route_obj->flights_per_infant_price * $cart_prev_data->total_Infants;
                                        
                                        
                                        
                                        
                                        
                                        $supplier_data = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                        
                                        if($price_diff_prev != 0 || $infant_price_prev != 0){
                                            $supplier_balance = $supplier_data->balance + $price_diff_prev;
                                            
                                            $supplier_balance = $supplier_balance - $infant_price_prev;
                                            $total_differ = $price_diff_prev - $infant_price_prev;
                                            
                                            DB::table('flight_supplier_ledger')->insert([
                                                         'supplier_id'=>$supplier_data->id,
                                                        'payment'=>$total_differ,
                                                        'balance'=>$supplier_balance,
                                                        'route_id'=>$flight_data->id,
                                                        'date'=>date('Y-m-d'),
                                                        'customer_id'=>$userData->id,
                                                        'adult_price'=>$flight_data->flights_per_person_price,
                                                        'child_price'=>$flight_data->flights_per_child_price,
                                                        'infant_price'=>$flight_data->flights_per_infant_price,
                                                        'adult_seats_booked'=>$cart_prev_data->total_adults,
                                                        'child_seats_booked'=>$cart_prev_data->total_childs,
                                                        'infant_seats_booked'=>$cart_prev_data->total_Infants,
                                                        'package_invoice_no'=>$Cart_details->invoice_no,
                                                        'remarks'=>'Package Invoice Updated',
                                                      ]);
                                                      
                                            DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                        }
                                        
                                        
                                        // Add to New One
                                        
                                                $route_obj = $flight_data_new;
                                                
                                                $flight_data = $flight_data_new;
                                        
                                              $supplier_data = DB::table('supplier')->where('id',$flight_data->dep_supplier)->first();
                                               
                                                //  Calculate Child Price
                                                
                                                $price_diff = 0;
                                                if($cart_res->total_childs > 0){
                                                    $child_price_wi_adult_price = $flight_data->flights_per_person_price * $cart_res->total_childs;
                                                    $child_price_wi_child_price = $flight_data->flights_per_child_price * $cart_res->total_childs;
                                                    $price_diff = $child_price_wi_adult_price - $child_price_wi_child_price;
                                                }
                                                
                                                $infant_price = 0;
                                                if($cart_res->total_Infants > 0){
                                                    $infant_price = $flight_data->flights_per_infant_price * $cart_res->total_Infants;
                                                }
                                                
                                                
                                                
                                                if($price_diff != 0 || $infant_price != 0){
                                                    $supplier_balance = $supplier_data->balance - $price_diff;
                                                    
                                                    $supplier_balance = $supplier_balance + $infant_price;
                                                    $total_differ = $infant_price - $price_diff;
                                                    
                                                    DB::table('flight_supplier_ledger')->insert([
                                                                'supplier_id'=>$supplier_data->id,
                                                                'payment'=>$total_differ,
                                                                'balance'=>$supplier_balance,
                                                                'route_id'=>$flight_data->id,
                                                                'date'=>date('Y-m-d'),
                                                                'customer_id'=>$userData->id,
                                                                'adult_price'=>$flight_data->flights_per_person_price,
                                                                'child_price'=>$flight_data->flights_per_child_price,
                                                                'infant_price'=>$flight_data->flights_per_infant_price,
                                                                'adult_seats_booked'=>$cart_res->total_adults,
                                                                'child_seats_booked'=>$cart_res->total_childs,
                                                                'infant_seats_booked'=>$cart_res->total_Infants,
                                                                'package_invoice_no'=>$Cart_details->invoice_no,
                                                                'remarks'=>'Package Booked',
                                                              ]);
                                                              
                                                    DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                                }
                                                                
                                        
                                }
                            
                    }
                    
                    DB::commit();
                    return response()->json(['message'=>'success','invoice_id'=>$request->invoice_no]);
                } catch (\Exception $e) {
                    DB::rollback();
                    echo $e;die;
                    return response()->json(['message'=>'error','booking_id'=> '']);
                    // something went wrong
                }
            }
        }
    }
    
    function delete_booking_Activity(Request $request){
        function dateDiffInDays($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
        }
        
        function getBetweenDates($startDate, $endDate){
            $rangArray  = [];
            $startDate  = strtotime($startDate);
            $endDate    = strtotime($endDate);
            $startDate  += (86400); 
            for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                $date           = date('Y-m-d', $currentDate);
                $rangArray[]    = $date;
            }
            return $rangArray;
        }
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            DB::beginTransaction();
            try {
                $booking_data   = DB::table('tours_bookings')->where('id',$request->booking_id)->get();
                $invoice_no     = $booking_data[0]->invoice_no;
                $invoiceId      = $invoice_no;
                $invoice_data   = DB::table('cart_details')->where('booking_id',$request->booking_id)->first();
                $cart_res       = json_decode($invoice_data->cart_total_data);
                
                // dd($cart_res);
                
                if($cart_res->agent_name != '-1'){
                    $agent_data = DB::table('Agents_detail')->where('id',$cart_res->agent_name)->select('id','balance')->first();
                    if(isset($agent_data)){
                        $agent_balance = $agent_data->balance - $cart_res->activity_total_price;
                        
                        // update Agent Balance
                        DB::table('agents_ledgers_new')->insert([
                            'agent_id'              => $agent_data->id,
                            'payment'               => $cart_res->activity_total_price,
                            'balance'               => $agent_balance,
                            'activity_invoice_no'   => $invoice_no,
                            'customer_id'           => $userData->id,
                            'date'                  => date('Y-m-d'),
                            'remarks'               => 'Activity Invoice Deleted'
                        ]);
                        DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                    }
                }
                
                if(isset($cart_res->customer_id)){
                    if($cart_res->customer_id != '-1'){
                        $customer_data = DB::table('booking_customers')->where('id',$cart_res->customer_id)->select('id','balance')->first();
                        if(isset($customer_data)){
                            
                            $customer_balance = $customer_data->balance - $cart_res->activity_total_price;
                            // update Agent Balance
                            DB::table('customer_ledger')->insert([
                                'booking_customer'      => $customer_data->id,
                                'payment'               => $cart_res->activity_total_price,
                                'balance'               => $customer_balance,
                                'activity_invoice_no'   => $invoiceId,
                                'customer_id'           => $userData->id,
                                'date'                  => date('Y-m-d'),
                                'remarks'               => 'Activity Invoice Deleted'
                            ]);
                            DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                        }
                    }
                }
                
                $result                 = DB::table('tours_bookings')->where('id',$request->booking_id)->delete();
                $result                 = DB::table('cart_details')->where('booking_id',$request->booking_id)->delete();
                // $result                 = DB::table('view_booking_payment_recieve_Activity')->where('package_id',$request->booking_id)->delete();
                // $result                 = DB::table('customer_tour_pay_dt')->where('invoice_id',$invoice_no)->delete();
                
                DB::commit();
                return response()->json(['message'=>'success']);
            } catch (\Exception $e) {
                echo $e;
                DB::rollback();
                $result = false;
                return response()->json(['message'=>'error']);
            }
        }
    }
    
    function delete_booking(Request $request){
        function dateDiffInDays($date1, $date2){
                $diff = strtotime($date2) - strtotime($date1);
                return abs(round($diff / 86400));
            }
            
            function getBetweenDates($startDate, $endDate){
                $rangArray = [];
                $startDate = strtotime($startDate);
                $endDate = strtotime($endDate);
                     
                     $startDate += (86400); 
                for ($currentDate = $startDate; $currentDate <= $endDate; 
                                                $currentDate += (86400)) {
                                                        
                    $date = date('Y-m-d', $currentDate);
                    $rangArray[] = $date;
                }
          
                return $rangArray;
            }
            
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            DB::beginTransaction();
            try {
                $booking_data = DB::table('tours_bookings')->where('id',$request->booking_id)->get();
                $invoice_no = $booking_data[0]->invoice_no;
                $invoiceId = $invoice_no;
                
                $invoice_data = DB::table('cart_details')->where('booking_id',$request->booking_id)->first();
                
                $cart_res = json_decode($invoice_data->cart_total_data);
               
                
                if($cart_res->agent_name != '-1'){
                    $agent_data = DB::table('Agents_detail')->where('id',$cart_res->agent_name)->select('id','balance')->first();
       
                        if(isset($agent_data)){
                            // echo "Enter hre ";
                            $agent_balance = $agent_data->balance - $cart_res->tour_total_price;
                            
                            // update Agent Balance
                            
                            DB::table('agents_ledgers_new')->insert([
                                'agent_id'=>$agent_data->id,
                                'payment'=>$cart_res->tour_total_price,
                                'balance'=>$agent_balance,
                                'package_invoice_no'=>$invoice_no,
                                'customer_id'=>$userData->id,
                                'date'=>date('Y-m-d'),
                                'remarks' => 'Package Invoice Deleted'
                                ]);
                                
                            DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                        }
                }
                
                if(isset($cart_res->customer_id)){
                        if($cart_res->customer_id != '-1'){
                            $customer_data = DB::table('booking_customers')->where('id',$cart_res->customer_id)->select('id','balance')->first();
               
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $customer_balance = $customer_data->balance - $cart_res->tour_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'payment'=>$cart_res->tour_total_price,
                                        'balance'=>$customer_balance,
                                        'package_invoice_no'=>$invoiceId,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        'remarks' => 'Package Invoice Deleted'
                                        ]);
                                        
                                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                }
                        }
                }
                
                // Accomodation Working
                 $tours_data = DB::table('tours')->where('id',$cart_res->tourId)->select('accomodation_details','accomodation_details_more')->first();
                    $accomodation = json_decode($tours_data->accomodation_details);
                    $more_accomodation_details = json_decode($tours_data->accomodation_details_more);
                    
                    // print_r($accomodation);
                    // print_r($more_accomodation_details);
                    // die;
                    
                     if(isset($accomodation)){
                         foreach($accomodation as $accomodation_res){
                             if(isset($accomodation_res->hotelRoom_type_idM)){
                                $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                            
                               if($room_data){
                                
                                        $rooms_qty = 0;
                                          if(isset($cart_res->double_rooms)){
                                                if($accomodation_res->acc_type == 'Double'){
                                                     $rooms_qty = $cart_res->double_rooms;
                                                }
                                                 
                                         }
                                         
                                          if(isset($cart_res->triple_rooms)){
                                                if($accomodation_res->acc_type == 'Triple'){
                                                     $rooms_qty = $cart_res->triple_rooms;
                                                }
                                         }
                                         
                                          if(isset($cart_res->quad_rooms)){
                                              if($accomodation_res->acc_type == 'Quad'){
                                                     $rooms_qty = $cart_res->quad_rooms;
                                                }
                                         }
                                         
                                         $total_booked = $room_data->booked - (int)$rooms_qty;
                               
                                        $room_negative = -1 * abs($total_booked);
                                
                                         
                                         DB::table('rooms_bookings_details')->insert([
                                             'room_id'=> $accomodation_res->hotelRoom_type_id,
                                             'booking_from'=>'package',
                                             'quantity'=>$room_negative,
                                             'booking_id'=>$invoiceId,
                                             'package_id'=>$cart_res->tourId,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$accomodation_res->acc_check_in,
                                             'check_out'=>$accomodation_res->acc_check_out,
                                         ]);
                                         
                               
                                        DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->update(['booked'=>$total_booked]);
                                        
                                        // Update Hotel Supplier Balance
                                
                                         $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
               
                                         if(isset($supplier_data)){
                                                // echo "Enter hre ";
                                                
                                                     $week_days_total = 0;
                                                     $week_end_days_totals = 0;
                                                     $total_price = 0;
                                                     $accomodation_res->acc_check_in = date('Y-m-d',strtotime($accomodation_res->acc_check_in));
                                                     $accomodation_res->acc_check_out = date('Y-m-d',strtotime($accomodation_res->acc_check_out));
                                                    if($room_data->price_week_type == 'for_all_days'){
                                                        $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                        $total_price = $room_data->price_all_days * $avaiable_days;
                                                    }else{
                                                         $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                         
                                                         $all_days = getBetweenDates($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                         
                                                        //  print_r($all_days);
                                                         $week_days = json_decode($room_data->weekdays);
                                                         $week_end_days = json_decode($room_data->weekends);
                                                         
                                                         foreach($all_days as $day_res){
                                                             $day = date('l', strtotime($day_res));
                                                             $day = trim($day);
                                                             $week_day_found = false;
                                                             $week_end_day_found = false;
                                                            
                                                             foreach($week_days as $week_day_res){
                                                                 if($week_day_res == $day){
                                                                     $week_day_found = true;
                                                                 }
                                                             }
                                                      
                                                            // echo "  ".$room_data->weekdays_price;
                                                             if($week_day_found){
                                                                 $week_days_total += $room_data->weekdays_price;
                                                             }else{
                                                                 $week_end_days_totals += $room_data->weekends_price;
                                                             }
                                                             
                                                             
                                                            //  foreach($week_end_days as $week_day_res){
                                                            //      if($week_day_res == $day){
                                                            //          $week_end_day_found = true;
                                                            //      }
                                                            //  }
                                                            //   if($week_end_day_found){
                                                                  
                                                            //  }
                                                         }
                                                         
                                                         
                                                        //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                         
                                                        //  print_r($all_days);
                                                         $total_price = $week_days_total + $week_end_days_totals;
                                                    }
                                                    
                                                    
                                                $all_days_price = $total_price * (int)$rooms_qty;
                                                
                                                // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                                // die;
                                                
                                                // echo "The supplier Balance is ".$supplier_data->balance;
                                                $supplier_balance = $supplier_data->balance;
                                                $supplier_payable_balance = $supplier_data->payable - $all_days_price;
                                                
                                                // update Agent Balance
                                                
                                                DB::table('hotel_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'received'=>$all_days_price,
                                                    'balance'=>$supplier_balance,
                                                    'payable_balance'=>$supplier_payable_balance,
                                                    'room_id'=>$room_data->id,
                                                    'customer_id'=>$userData->id,
                                                    'date'=>date('Y-m-d'),
                                                    'package_invoice_no'=>$invoiceId,
                                                    'available_from'=>$accomodation_res->acc_check_in,
                                                    'available_to'=>$accomodation_res->acc_check_out,
                                                    'room_quantity'=>$rooms_qty,
                                                    'remarks' => 'Package Invoice Deleted'
                                                    ]);
                                                    
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                    'balance'=>$supplier_balance,
                                                    'payable'=>$supplier_payable_balance
                                                    ]);
                                                
                                                
                                                  
                                                                            
                                            }
                            
                               }
                             }
                          
                         }
                        
                    }
                    
                    if(isset($more_accomodation_details)){
                        // print_r($more_accomodation_details);
                        
                         foreach($more_accomodation_details as $accomodation_res){
                             if(isset($accomodation_res->more_hotelRoom_type_idM)){
                                $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                                
                                // print_r($room_data);
                                if($room_data){
                                
                                        // $total_booked = $room_data->booked + $accomodation_res->more_acc_qty;
                                        
                                        
                                        
                                        
                                          $rooms_qty = 0;
                                              if(isset($cart_res->double_rooms)){
                                                        if($accomodation_res->more_acc_type == 'Double'){
                                                             $rooms_qty = $cart_res->double_rooms;
                                                        }
                                                         
                                                 }
                                                 
                                                  if(isset($cart_res->triple_rooms)){
                                                        if($accomodation_res->more_acc_type == 'Triple'){
                                                             $rooms_qty = $cart_res->triple_rooms;
                                                        }
                                                 }
                                                 
                                                  if(isset($cart_res->quad_rooms)){
                                                      if($accomodation_res->more_acc_type == 'Quad'){
                                                             $rooms_qty = $cart_res->quad_rooms;
                                                        }
                                                 }
                                                 
                                                 $total_booked = $room_data->booked - (int)$rooms_qty;
                                       
                                        
                                                $room_negative = -1 * abs($total_booked);
                                                 
                                                 DB::table('rooms_bookings_details')->insert([
                                                     'room_id'=> $accomodation_res->more_hotelRoom_type_id,
                                                     'booking_from'=>'package',
                                                     'quantity'=>$room_negative,
                                                     'booking_id'=>$invoiceId,
                                                     'package_id'=>$cart_res->tourId,
                                                     'date'=>date('Y-m-d'),
                                                     'check_in'=>$accomodation_res->more_acc_check_in,
                                                     'check_out'=>$accomodation_res->more_acc_check_out,
                                                 ]);
                                                 
                                      
                                        DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->update(['booked'=>$total_booked]);
                                        
                                        // Update Hotel Supplier Balance
                                
                                         $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
               
                                         if(isset($supplier_data)){
                                                // echo "Enter hre ";
                                                
                                                     $week_days_total = 0;
                                                     $week_end_days_totals = 0;
                                                     $total_price = 0;
                                                     $accomodation_res->more_acc_check_in = date('Y-m-d',strtotime($accomodation_res->more_acc_check_in));
                                                     $accomodation_res->more_acc_check_out = date('Y-m-d',strtotime($accomodation_res->more_acc_check_out));
                                                    if($room_data->price_week_type == 'for_all_days'){
                                                        $avaiable_days = dateDiffInDays($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                        $total_price = $room_data->price_all_days * $avaiable_days;
                                                    }else{
                                                         $avaiable_days = dateDiffInDays($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                         
                                                         $all_days = getBetweenDates($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                         
                                                        //  print_r($all_days);
                                                         $week_days = json_decode($room_data->weekdays);
                                                         $week_end_days = json_decode($room_data->weekends);
                                                         
                                                         foreach($all_days as $day_res){
                                                             $day = date('l', strtotime($day_res));
                                                             $day = trim($day);
                                                             $week_day_found = false;
                                                             $week_end_day_found = false;
                                                            
                                                             foreach($week_days as $week_day_res){
                                                                 if($week_day_res == $day){
                                                                     $week_day_found = true;
                                                                 }
                                                             }
                                                      
                                                            // echo "  ".$room_data->weekdays_price;
                                                             if($week_day_found){
                                                                 $week_days_total += $room_data->weekdays_price;
                                                             }else{
                                                                 $week_end_days_totals += $room_data->weekends_price;
                                                             }
                                                             
                                                             
                                                            //  foreach($week_end_days as $week_day_res){
                                                            //      if($week_day_res == $day){
                                                            //          $week_end_day_found = true;
                                                            //      }
                                                            //  }
                                                            //   if($week_end_day_found){
                                                                  
                                                            //  }
                                                         }
                                                         
                                                         
                                                        //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                         
                                                        //  print_r($all_days);
                                                         $total_price = $week_days_total + $week_end_days_totals;
                                                    }
                                                    
                                                    
                                                $all_days_price = $total_price * (int)$rooms_qty;
                                                
                                                // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                                // die;
                                                
                                                // echo "The supplier Balance is ".$supplier_data->balance;
                                                $supplier_balance = $supplier_data->balance;
                                                $supplier_payable_balance = $supplier_data->payable - $all_days_price;
                                                
                                                // update Agent Balance
                                                
                                                DB::table('hotel_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'received'=>$all_days_price,
                                                    'balance'=>$supplier_balance,
                                                    'payable_balance'=>$supplier_payable_balance,
                                                    'room_id'=>$room_data->id,
                                                    'customer_id'=>$userData->id,
                                                    'date'=>date('Y-m-d'),
                                                    'package_invoice_no'=>$invoiceId,
                                                    'available_from'=>$accomodation_res->more_acc_check_in,
                                                    'available_to'=>$accomodation_res->more_acc_check_out,
                                                    'room_quantity'=>$rooms_qty,
                                                    'remarks' => 'Package Invoice Deleted'
                                                    ]);
                                                    
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                    'balance'=>$supplier_balance,
                                                    'payable'=>$supplier_payable_balance
                                                    ]);
                                                
                                                
                                                  
                                                                            
                                            }
                            
                                }
                             }  
                         }
                    
                    }
                    
                    $tours_data = DB::table('tours_2')->where('tour_id',$cart_res->tourId)->select('flight_supplier','flight_route_id_occupied','flights_per_person_price')->first();
                    
                    $flight_data = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                 
                    if(isset($flight_data)){
                         
                                // Update Flight Supplier Balance
                                
                                $supplier_data = DB::table('supplier')->where('id',$flight_data->dep_supplier)->first();
                                                  
                                //  Calculate Child Price
                                
                                $price_diff = 0;
                                if($cart_res->total_childs > 0){
                                    $child_price_wi_adult_price = $flight_data->flights_per_person_price * $cart_res->total_childs;
                                    $child_price_wi_child_price = $flight_data->flights_per_child_price * $cart_res->total_childs;
                                    $price_diff = $child_price_wi_adult_price - $child_price_wi_child_price;
                                }
                                
                                $infant_price = 0;
                                if($cart_res->total_Infants > 0){
                                    $infant_price = $flight_data->flights_per_infant_price * $cart_res->total_Infants;
                                }
                                
                                
                                
                                if($price_diff != 0 || $infant_price != 0){
                            
                                    $supplier_balance = $supplier_data->balance + $price_diff;
                                    
                                    $supplier_balance = $supplier_balance - $infant_price;
                                    $total_differ = $infant_price - $price_diff;
                                    
                                    DB::table('flight_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'received'=>$total_differ,
                                                'balance'=>$supplier_balance,
                                                'route_id'=>$flight_data->id,
                                                'date'=>date('Y-m-d'),
                                                'customer_id'=>$userData->id,
                                                'adult_price'=>$flight_data->flights_per_person_price,
                                                'child_price'=>$flight_data->flights_per_child_price,
                                                'infant_price'=>$flight_data->flights_per_infant_price,
                                                'adult_seats_booked'=>$cart_res->total_adults,
                                                'child_seats_booked'=>$cart_res->total_childs,
                                                'infant_seats_booked'=>$cart_res->total_Infants,
                                                'package_invoice_no'=>$invoiceId,
                                                'remarks'=>'Package Invoice Deleted',
                                              ]);
                                              
                                    DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                }
                                
                                
                            
                        
                        
                    }
                    
                      //Update Packge Pax
                    $package_data = DB::table('tours')->where('id',$cart_res->tourId)->select('id','available_seats','available_single_seats','available_double_seats','available_triple_seats','available_quad_seats')->first();
               
                     // Double Total Pax
                        $total_doubal_pax = 0;
                        if(isset($cart_res->double_adults)){
                            $total_doubal_pax += (float)$cart_res->double_adults;
                        }
                        
                        if(isset($cart_res->double_childs)){
                            $total_doubal_pax += (float)$cart_res->double_childs;
                        }
                        
                        if(isset($cart_res->double_infant)){
                            $total_doubal_pax += (float)$cart_res->double_infant;
                        }
                        

                    // Triple Total Pax
                        $total_triple_pax = 0;
                        if(isset($cart_res->triple_adults)){
                            $total_triple_pax += (int)$cart_res->triple_adults;
                        }
                        
                        if(isset($cart_res->triple_childs)){
                            $total_triple_pax += (float)$cart_res->triple_childs;
                        }
                        
                        if(isset($cart_res->triple_infant)){
                            $total_triple_pax += (float)$cart_res->triple_infant;
                        }
                        
                    // Quad Total Pax
                        $total_Quad_pax = 0;
                        if(isset($cart_res->quad_adults)){
                            $total_Quad_pax += (int)$cart_res->quad_adults;
                        }
                        
                        if(isset($cart_res->quad_childs)){
                            $total_Quad_pax += (float)$cart_res->quad_childs;
                        }
                        
                        if(isset($cart_res->quad_infant)){
                            $total_Quad_pax += (float)$cart_res->quad_infant;
                        }
                        
                    // Without Pax Total
                        $total_without_pax = 0;
                        if(isset($cart_res->without_acc_adults)){
                            $total_without_pax += (int)$cart_res->without_acc_adults;
                        }
                        
                        if(isset($cart_res->children)){
                            $total_without_pax += (float)$cart_res->children;
                        }
                        
                        if(isset($cart_res->infants)){
                            $total_without_pax += (float)$cart_res->infants;
                        }
                        
                    $total_pax = (int)$total_without_pax + (int)$total_doubal_pax + (int)$total_triple_pax + (int)$total_Quad_pax;
                    
                    
                    // Without Accomodation Pax
                    
                    // echo "package pax ".$package_data->available_double_seats." double pax $total_pax";
               
                    $available_seats = (float)$package_data->available_seats + (float)$total_pax;
                    $available_double_seats = (float)$package_data->available_double_seats + (float)$total_doubal_pax;
                    $available_triple_seats = (float)$package_data->available_triple_seats + (float)$total_triple_pax;
                    $available_quad_seats = (float)$package_data->available_quad_seats + (float)$total_Quad_pax;
                    
                    // print_r($package_data);
                    $package_data = DB::table('tours')->where('id',$package_data->id)->update([
                        'available_seats'=>$available_seats,
                        'available_double_seats'=>$available_double_seats,
                        'available_triple_seats'=>$available_triple_seats,
                        'available_quad_seats'=>$available_quad_seats,
                    ]);
                    
                $result = DB::table('tours_bookings')->where('id',$request->booking_id)->delete();
                $result = DB::table('cart_details')->where('booking_id',$request->booking_id)->delete();
                $result = DB::table('view_booking_payment_recieve')->where('package_id',$request->booking_id)->delete();
                $result = DB::table('customer_tour_pay_dt')->where('invoice_id',$invoice_no)->delete();
                DB::commit();
                return response()->json(['message'=>'success']);
                // all good
            } catch (\Exception $e) {
                echo $e;
                DB::rollback();
                $result = false;
                return response()->json(['message'=>'error']);
            }
        }
    }
    
    function save_booking_combine(Request $request){
        // print_r($request->all());
        
             // $data = $request->request_data;
        $request_data = json_decode($request->req_data);
        $lead_passenger_data = json_decode($request_data->lead_passenger);
        
        
        // print_r($lead_passenger_data);

        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
 
         $randomNumber = random_int(1000000, 9999999);
         $invoiceId =  "ZAM".$randomNumber;
         
          $result = DB::table('combine_Booking')->insert([
            'invoice_no' => $invoiceId,
            'tour_Data' => $request_data->tours_data,
            'passenger_name' => $lead_passenger_data[0]->name." ".$lead_passenger_data[0]->lname,
            'email' => $lead_passenger_data['0']->email,
            'tour_Data' => $request_data->tours_data,
            'activity_Data' => $request_data->activity_Data,
            'hotel_Data' => $request_data->hotel_Data,
            'transfer_Data' => $request_data->transfer_Data,
            'tour_id' => $request_data->tour_id,
            'tour_generate_id' => $request_data->tour_generate_id,
            'activity_id' => $request_data->activity_id,
            'activity_generate_id' => $request_data->activity_generate_id,
            'tour_hotels_data' => $request_data->hotel_response,
            'lead_passenger' => $request_data->lead_passenger,
            'adults_data' => $request_data->adults_Data,
            'child_data' => $request_data->childs_Data,
            'user_id' => $userData->id,
        ]);
        
        if($result){
            return response()->json(['message'=>'success','invoice_id'=>$invoiceId]);
        }else{
            return response()->json(['message'=>'error']);
        }
         
         
    }
    
    function save_booking1(Request $request){
        $request_data = json_decode($request->request_data);
        $cart_data = json_decode($request->cart_data);
        $request_data = json_decode($request->request_data);
        $name = $request_data[0]->name." ".$request_data[0]->lname;
        $email = $request_data[0]->email;
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
       
        
        $randomNumber = random_int(1000000, 9999999);
        $invoiceId =  "AHT".$randomNumber;
       
        $tourObj = new ToursBooking;

        $tourObj->passenger_name = $name;
        $tourObj->email = $email;
        $tourObj->cart_details = '';
        $tourObj->passenger_detail = $request->request_data;
        $tourObj->adults_detail = $request->adults;
        $tourObj->child_detail = $request->childs;
        $tourObj->customer_id = $userData->id;
        $tourObj->parent_token = $request->token;
        $tourObj->invoice_no = $invoiceId;
        $tourObj->booking_person = $request->booking_person;

        DB::beginTransaction();
        try {
            $tourData = $tourObj->save();
            $booking_id = $tourObj->id;
            $cart_data_main = json_decode($request->cart_data);
            $cart_data = $cart_data_main[0];
            if($cart_data_main[1] == 'tour'){
                foreach($cart_data as $cart_res){
                
                    $addtional_services = [];
                    if($cart_res->Additional_services_names != ''){
                        $service = [
                            'service'=>$cart_res->Additional_services_names,
                            'service_price'=>$cart_res->services_price,
                            'service_type'=>'',
                            'person'=>$cart_res->services_persons,
                            'dates'=>$cart_res->service_dates,
                            'Service_Days'=>$cart_res->service_day,
                            'total_price'=>$cart_res->service_day * $cart_res->services_price * $cart_res->services_persons,
                        ];
                            
                        array_push($addtional_services,$service);
                    }
                    
                    if($cart_res->Additional_services_names_more !== 'null' AND $cart_res->Additional_services_names_more !== ''){
                        $services_names = json_decode($cart_res->Additional_services_names_more);
                        $services_persons_more = json_decode($cart_res->services_persons_more);
                        $services_price_more = json_decode($cart_res->services_price_more);
                        $services_days_more = json_decode($cart_res->services_days_more);
                        $services_dates_more = json_decode($cart_res->services_dates_more);
                        $z = 0;
                        foreach($services_names as $service_res){
                            $service = [
                                'service'=>$service_res,
                                'service_price'=> $services_price_more[$z],
                                'service_type'=>'',
                                'person'=>$services_persons_more[$z],
                                'dates'=>$services_dates_more[$z],
                                'Service_Days'=>$services_days_more[$z],
                                'total_price'=>$services_days_more[$z] * $services_price_more[$z] * $services_persons_more[$z],
                            ];
                            
                            array_push($addtional_services,$service);
                        }
                    }
                    
                    if(isset($cart_res->Additional_services_Awaab)){
                        $addtional_services = $cart_res->Additional_services_Awaab;
                    }else{
                        $addtional_services = json_encode($addtional_services);
                    }
                    
                    $Cart_details = new Cart_details;
                    
                    $Cart_details->tour_id = $cart_res->tourId;
                    $Cart_details->generate_id = $cart_res->generate_id;
                    $Cart_details->tour_name = $cart_res->name;
                    $Cart_details->adults = $cart_res->adults;
                    $Cart_details->childs = $cart_res->children;
                    $Cart_details->sigle_price = $cart_res->sigle_price;
                    $Cart_details->tour_total_price = $cart_res->tour_total_price;
                    $Cart_details->markup_Price = $cart_res->markup_Price;
                    $Cart_details->discount_Price = $cart_res->discount_Price;
                    $Cart_details->total_service_price = $cart_res->total_service_price;
                    $Cart_details->price = $cart_res->price;
                    $Cart_details->sharing2 = $cart_res->sharing2;
                    $Cart_details->sharing3 = $cart_res->sharing3;
                    $Cart_details->sharing4 = $cart_res->sharing4;
                    $Cart_details->sharingSelect = $cart_res->sharingSelect;
                    $Cart_details->image = $cart_res->image;
                    $Cart_details->booking_id = $booking_id;
                    $Cart_details->invoice_no = $invoiceId;
                    $Cart_details->currency = $cart_res->currency;
                    $Cart_details->pakage_type = $cart_res->pakage_type;
                    $Cart_details->Additional_services_names = $addtional_services;
                    $Cart_details->client_id = $userData->id;
                    $Cart_data = $Cart_details->save();
                }
            }else{
                foreach($cart_data as $cart_res){
                    $addtional_services = [];
                    if($cart_res->Additional_services_names_more !== 'null' AND $cart_res->Additional_services_names_more !== ''){
                        $services_names = json_decode($cart_res->Additional_services_names_more);
                        $services_persons_more = json_decode($cart_res->services_persons_more);
                        $services_price_more = json_decode($cart_res->services_price_more);
                        $services_days_more = json_decode($cart_res->services_days_more);
                        $services_dates_more = json_decode($cart_res->services_dates_more);
                        $z = 0;
                        foreach($services_names as $service_res){
                            $service = [
                                'service'=>$service_res,
                                'service_price'=> $services_price_more[$z],
                                'service_type'=>'',
                                'person'=>$services_persons_more[$z],
                                'dates'=>$services_dates_more[$z],
                                'Service_Days'=>$services_days_more[$z],
                                'total_price'=>$services_days_more[$z] * $services_price_more[$z] * $services_persons_more[$z],
                            ];
                            
                            array_push($addtional_services,$service);
                        }
                    }
                    
                    if(isset($cart_res->Additional_services_Awaab)){
                        $addtional_services = $cart_res->Additional_services_Awaab;
                    }else{
                        $addtional_services = json_encode($addtional_services);
                    }
                    
                    $start_date = date("Y-m-d", strtotime($cart_res->activity_select_date));
                    $Cart_details = new Cart_details;
                    $Cart_details->tour_id = $cart_res->activtyId;
                    $Cart_details->generate_id = $cart_res->generate_id;
                    $Cart_details->tour_name = $cart_res->name;
                    $Cart_details->adults = $cart_res->adults;
                    $Cart_details->childs = $cart_res->children;
                    $Cart_details->adult_price = $cart_res->adult_price;
                    $Cart_details->child_price = $cart_res->child_price;
                    $Cart_details->markup_Price = $cart_res->markup_Price;
                    $Cart_details->discount_Price = $cart_res->discount_Price;
                    $Cart_details->activity_select_date = $start_date;
                    $Cart_details->tour_total_price = $cart_res->activity_total_price;
                    $Cart_details->total_service_price = $cart_res->total_service_price;
                    $Cart_details->price = $cart_res->price;
                    $Cart_details->image = $cart_res->image;
                    $Cart_details->booking_id = $booking_id;
                    $Cart_details->invoice_no = $invoiceId;
                    $Cart_details->currency = $cart_res->currency;
                    $Cart_details->pakage_type = $cart_res->pakage_type;
                    $Cart_details->Additional_services_names = $addtional_services;
                    $Cart_details->client_id = $userData->id;
                    $Cart_data = $Cart_details->save();
                }
            }
            DB::commit();
            return response()->json(['message'=>'success','booking_id'=>$tourObj->id,'invoice_id'=>$invoiceId]);
        }
        catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
    }
function save_booking_payment(Request $request){
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
         $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
         
       
        $result = DB::table('customer_tour_pay_dt')->insert([
            'invoice_id' => $request->invoice_id,
            'payment_am' => $request->payment_am,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'transcation_id' => $request->transcation_id,
            'account_no' => $request->account_no,
            'email' => $request->email,
            'voucher_img' => $request->voucher,
            'domain' => $request->domain,
            'customer_id' => $userData->id
        ]);
        
        if($result){
            return response()->json(['message'=>'success']);
        }else{
            return response()->json(['message'=>'error']);
        }
               
   
     
    }
    
    function invoice_data(Request $request){
        DB::beginTransaction();
        try {
            $countries  = DB::table('countries')->get();
            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            if($userData){
              if($userData->status == 1){
                    $inv_details = ToursBooking::where('invoice_no',$request->booking_id)->first();
                    if($inv_details){
                        $cart_data          = cart_details::where('invoice_no',$request->booking_id)->get();
                        $cart_data_count    = count($cart_data);
                        if($cart_data_count > 0){
                            return response()->json(['message'=>'success','inv_details'=>$inv_details,'cart_data'=> $cart_data,'countries'=>$countries]);
                        }else{
                            return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'','countries'=>$countries]);
                        }
                    }else{
                        return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'','countries'=>$countries]);
                    }
                }
            }
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
       
    }
    
function invoice_dataI(Request $request){
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $countries  = DB::table('countries')->get();
        if($userData){
            if($userData->status == 1){
                // $inv_details = ToursBooking::join('cart_details', 'cart_details.booking_id', '=', 'tours_bookings.id')
                //                 ->where('tours_bookings.id',$request->booking_id)
                //                 ->get(['tours_bookings.*','cart_details.*']);
                $inv_details        = DB::table('add_manage_invoices')->where('generate_id',$request->booking_id)->first();
                // $inv_details = ToursBooking::where('invoice_no',$request->booking_id)->first();
                if($inv_details){
                    $otherPaxDetails    = DB::table('otherPaxDetails')->where('invoice_id',$inv_details->id)->get();
                    
                    // $cart_data = cart_details::where('invoice_no',$request->booking_id)->get();
                    $cart_data =  DB::table('add_manage_invoices')->where('generate_id',$request->booking_id)->get();
                    return response()->json(['message'=>'success','inv_details'=>$inv_details,'cart_data'=> $cart_data,'countries'=>$countries,'otherPaxDetails'=>$otherPaxDetails]);
                }else{
                    return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'','countries'=>$countries,'otherPaxDetails'=>'']);
                }
            }
        }
       
    }
function invoice_dataQ(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            if($userData->status == 1){
                // $inv_details = ToursBooking::join('cart_details', 'cart_details.booking_id', '=', 'tours_bookings.id')
                //                 ->where('tours_bookings.id',$request->booking_id)
                //                 ->get(['tours_bookings.*','cart_details.*']);
                $inv_details = DB::table('add_manage_quotations')->where('generate_id',$request->booking_id)->first();
                // $inv_details = ToursBooking::where('invoice_no',$request->booking_id)->first();
                if($inv_details){
                    // $cart_data = cart_details::where('invoice_no',$request->booking_id)->get();
                    $cart_data =  DB::table('add_manage_quotations')->where('generate_id',$request->booking_id)->get();
                    return response()->json(['message'=>'success','inv_details'=>$inv_details,'cart_data'=> $cart_data]);
                }else{
                    return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'']);
                }
            }
        }
       
    }
function invoice_combine(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
          if($userData->status == 1){
                // $inv_details = ToursBooking::join('cart_details', 'cart_details.booking_id', '=', 'tours_bookings.id')
                //                 ->where('tours_bookings.id',$request->booking_id)
                //                 ->get(['tours_bookings.*','cart_details.*']);
                $inv_details = DB::table('combine_Booking')->where('invoice_no',$request->booking_id)->first();
                if($inv_details){
                    
                    return response()->json(['message'=>'success','inv_details'=>$inv_details]);
                }else{
                    return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'']);
                }
                

                // print_r($inv_details);
          }
        }
       
    }
function tour_revenue(Request $request){
        DB::beginTransaction();
        try {
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            if($userData){
                if($userData->status == 1){
                    $tour_invs      = [];
                    $tours_bookings = DB::table('cart_details')->where('tour_id',$request->tour_id)->get();
                    foreach($tours_bookings as $book_res){
                        $payments       = DB::table('view_booking_payment_recieve')->where('package_id',$book_res->booking_id)->get();
                        $total_paid_am  = 0;
                        foreach($payments as $pay_res){
                            $total_paid_am += $pay_res->amount_paid;
                        }
                        $booking_inv = [
                            'booking_id'        => $book_res->booking_id,
                            'invoice_no'        => $book_res->invoice_no,
                            'tour_id'           => $book_res->tour_id,
                            'tour_name'         => $book_res->tour_name,
                            'total_amount'      => $book_res->tour_total_price,
                            'paid_amount'       => $total_paid_am,
                            'remaing_amount'    => $book_res->tour_total_price - $total_paid_am,
                            'currency_symbol'   => $book_res->currency,
                        ];  
                        array_push($tour_invs,$booking_inv);
                    }
                    
                    if($tour_invs){   
                        return response()->json(['message'=>'success','payments'=>$tour_invs]);
                    }else{
                        return response()->json(['message'=>'Tour Not Booked Yet']);
                    }
                }
            }
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
    }
    
    public function invoice_package_data(Request $request){
         $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            if($userData){
                if($userData->status == 1){
                    try {
                        $agentDetails = DB::table('Agents_detail')->where('customer_id',$userData->id)->get();
                        // print_r($request);die();
                        $T_ID = $request->T_ID;
                        $booking_id1 = $request->booking_id1;
                        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                        $detail = CustomerSubcription::where('Auth_key',$request->token)->first();
                        $client_id=$detail->id;
                        $contact_details=DB::table('contact_details')->where('customer_id',$client_id)->first();
                        // print_r($contact_details);die();
                        if($userData){
                            // print_r('if');die();
                            if($userData->status == 1){
                                
                                $package_Type               = DB::table('cart_details')->where('invoice_no',$request->booking_id)->where('client_id',$userData->id)->select('pakage_type')->first();
                                if($package_Type){
                                    // Package Details
                                    // $package_Payments           = DB::table('cart_details')->where('invoice_no',$request->booking_id)
                                    //                                 ->Join('view_booking_payment_recieve','cart_details.id','view_booking_payment_recieve.package_id')->get();
                                    $package_Payments           = DB::table('view_booking_payment_recieve')->where('package_id',$request->booking_id1)->get();
                                    
                                    // $package_Payments_count     = DB::table('cart_details')->where('invoice_no',$request->booking_id)->Join('view_booking_payment_recieve','cart_details.id','view_booking_payment_recieve.package_id')->count();
                                    $package_Payments_count     = DB::table('view_booking_payment_recieve')->where('package_id',$request->booking_id1)->count();
                                                                    
                                    // $total_package_Payments     = DB::table('cart_details')->where('invoice_no',$request->booking_id)->first();
                                    $total_package_Payments     = DB::table('view_booking_payment_recieve')->where('package_id',$request->booking_id1)->first();
                                    
                                    // $recieved_package_Payments  = DB::table('cart_details')->where('invoice_no',$request->booking_id)->Join('view_booking_payment_recieve','cart_details.id','view_booking_payment_recieve.package_id')->sum('amount_paid');
                                    $recieved_package_Payments  = DB::table('view_booking_payment_recieve')->where('package_id',$request->booking_id1)->sum('amount_paid');
                                                                    
                                    // $remainig_package_Payments  = DB::table('cart_details')->where('invoice_no',$request->booking_id)->Join('view_booking_payment_recieve','cart_details.id','view_booking_payment_recieve.package_id')->sum('remaining_amount');
                                    $remainig_package_Payments  = DB::table('view_booking_payment_recieve')->where('package_id',$request->booking_id1)->sum('remaining_amount');
                                                                    
                                    $currency_Symbol            = CustomerSubcription::where('Auth_key',$request->token)->first();
                                    
                                    // Activity Details
                                    $activity_Payments           = DB::table('cart_details')->where('invoice_no',$request->booking_id)
                                                                    ->Join('view_booking_payment_recieve_Activity','cart_details.id','view_booking_payment_recieve_Activity.package_id')->get();
                                    $activity_Payments_count     = DB::table('cart_details')->where('invoice_no',$request->booking_id)
                                                                    ->Join('view_booking_payment_recieve_Activity','cart_details.id','view_booking_payment_recieve_Activity.package_id')->count();
                                    $total_activity_Payments     = DB::table('cart_details')->where('invoice_no',$request->booking_id)->first();
                                    $recieved_activity_Payments  = DB::table('cart_details')->where('invoice_no',$request->booking_id)
                                                                    ->Join('view_booking_payment_recieve_Activity','cart_details.id','view_booking_payment_recieve_Activity.package_id')->sum('amount_paid');
                                    $remainig_activity_Payments  = DB::table('cart_details')->where('invoice_no',$request->booking_id)
                                                                    ->Join('view_booking_payment_recieve_Activity','cart_details.id','view_booking_payment_recieve_Activity.package_id')->sum('remaining_amount');
                                    
                                    // status
                                    
                                    $booking_ID =  DB::table('tours_bookings')->where('invoice_no',$request->booking_id)->first();
                                    
                                    $payment_Status = DB::table('cart_details')->where('booking_id',$booking_id1)->first();
                                    
                                    $tour_data_id = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$T_ID)->first();
                                    
                                    if($package_Type == 'tour'){
                                        $tour_ID = DB::table('tours')->where('id',$T_ID)->first();
                                    }else{
                                        $tour_ID = DB::table('new_activites')->where('id',$T_ID)->first();
                                    }
                                    
                                    $customer_Data      = DB::table('customer_subcriptions')->where('id',$booking_ID->customer_id)->first();
                                    $created_By         = $customer_Data->name.' '.$customer_Data->lname;
                                    
                                    if(isset($booking_ID->SU_id) && $booking_ID->SU_id > 0){
                                        $sub_User       = DB::table('role_managers')->where('id',$booking_ID->SU_id)->first();
                                        $created_By     = $sub_User->first_name.' '.$sub_User->last_name;
                                    }
                                    
                                    $inv_details = ToursBooking::where('invoice_no',$request->booking_id)->first();
                                    // print_r($inv_details);die();
                                    
                                    $total_paid_amount_list = DB::table('invoices_payment_recv')->where('invoice_type','Package invoice')->where('invoice_no',$request->booking_id)->get();
                                    
                                    if($inv_details){
                                        // print_r('if');die();
                                        $cart_data = cart_details::where('invoice_no',$request->booking_id)->get();
                                        $cart_data1 = cart_details::where('invoice_no',$request->booking_id)->first();
                                        $tour_batchs=DB::table('tour_batchs')->where('generate_id',$cart_data1->generate_id)->first();
                                        return response()->json([
                                            'message'                   => 'success',
                                            
                                            'booking_ID'                => $booking_ID,
                                            'tour_ID'                   => $tour_ID,
                                            'package_Type'              => $package_Type,
                                            'tour_data_id'              => $tour_data_id,
                                            'package_Payments_count'    => $package_Payments_count,
                                            'package_Payments'          => $package_Payments,
                                            'currency_Symbol'           => $currency_Symbol,
                                            'total_package_Payments'    => $total_package_Payments,
                                            'recieved_package_Payments' => $recieved_package_Payments,
                                            'remainig_package_Payments' => $remainig_package_Payments,
                                            
                                            'activity_Payments'          => $activity_Payments,
                                            'activity_Payments_count'    => $activity_Payments_count,
                                            'total_activity_Payments'    => $total_activity_Payments,
                                            'recieved_activity_Payments' => $recieved_activity_Payments,
                                            'remainig_activity_Payments' => $remainig_activity_Payments,
                                            
                                            'payment_Status'             => $payment_Status,
                                            
                                            'inv_details'               => $inv_details,
                                            'cart_data'                 => $cart_data,
                                            'contact_details'           => $contact_details,
                                            'tour_batchs'               => $tour_batchs,
                                            'total_paid_amount_list'    => $total_paid_amount_list,
                                            
                                            'created_By'                => $created_By,
                                            'agentDetails'              => $agentDetails,
                                        ]);
                                    }else{
                                        return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'']);
                                    }
                                }else{
                                    return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'']);
                                }
                                
                            }
                        }
                    } catch (Throwable $e) {
                        return response()->json(['message'=>'error','booking_id'=> '']);
                    }
                }
                
            }else{
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
    }
    
    public function invoice_package_data_atol(Request $request){
        DB::beginTransaction();
        try {
            $customer_id    = $request->customer_id;
            $id             = $request->booking_id;
            $T_ID           = $request->T_ID;
            // dd($T_ID);
            $booking_id1    = $request->booking_id1;
            $package_Type   = DB::table('cart_details')->where('invoice_no',$id)->where('client_id',$customer_id)->select('pakage_type')->first();
            if($package_Type->pakage_type == 'tour'){
                $tour_ID = DB::table('tours')
                            ->join('tours_2','tours.id','tours_2.tour_id')
                            ->where('tours.id',$T_ID)
                            ->where('tours.customer_id',$customer_id)->first();
            }else{
                $tour_ID = DB::table('new_activites')->where('id',$T_ID)->where('customer_id',$customer_id)->first();
            }
            $inv_details = ToursBooking::where('invoice_no',$id)->where('customer_id',$customer_id)->first();
            
            if($inv_details){
                return response()->json([
                    'message'       => 'success',
                    'package_Type'  => $package_Type,
                    'tour_ID'       => $tour_ID,
                    'tour_ID'       => $tour_ID,
                    'inv_details'   => $inv_details,
                ]);
            }else{
                return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'']);
            }
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
    }
    
    public function get_tour_iternery_invoice_package_data(Request $request){
        DB::beginTransaction();
        try {
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            $customer_id = $userData->id;
            if($request->type == 'tour'){
                $tours  = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
                            ->where('tours.generate_id',$request->generate_id)->select('tours.*','tours_2.*')->get();
            }
            if($request->type == 'activity'){
                $tours  = DB::table('active_batchs')->where('generate_id',$request->generate_id)->select('id','tour_id','generate_id','title','Itinerary_details','tour_itinerary_details_1','currency_symbol','start_date','end_date','time_duration','tour_location','cancellation_policy','checkout_message','starts_rating')->get();
            }
            return response()->json(['message'=>'success','tours'=>$tours]);
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
    }
    
    public function get_tour_iternery(Request $request){
        //   print_r($request->all());die;
      $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
  
      $customer_id = $userData->id;
      
      if($request->type == 'tour'){
        //   echo "enter in if";
         $tours = DB::table('tours')
                        ->join('tours_2','tours.id','tours_2.tour_id')
                        ->where('tours.generate_id',$request->generate_id)
                        ->select('tours.*','tours_2.*')
                        ->get();
                        // dd($tours);
                // $tours=DB::table('tour_batchs')->where('generate_id',$request->generate_id)->select('id','tour_id','generate_id','title','Itinerary_details','tour_itinerary_details_1','currency_symbol','start_date','end_date','time_duration','tour_location','whats_included','whats_excluded','destination_details','destination_details_more','flights_details','flights_details_more','accomodation_details','transportation_details','transportation_details_more','cancellation_policy','checkout_message','visa_fee','visa_type','visa_rules_regulations','visa_image','starts_rating')->get();
      }
      
       if($request->type == 'activity'){
        //   echo "enter in activity "; 
                $tours=DB::table('new_activites_batches')->where('generate_id',$request->generate_id)->select('id','activity_id','generate_id','title','what_expect','currency_symbol','activity_date','duration','location','whats_included','whats_excluded','cancellation_policy','checkout_message','meeting_and_pickups','starts_rating')->get();
      }
      // print_r($customer_id);die();
      return response()->json(['message'=>'success','tours'=>$tours]);
    }
    
    public function get_tour_iteneryI(Request $request){
        $userData       = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $customer_id    = $userData->id;
        if($request->type == 'Invoice'){
            $tours      = DB::table('add_manage_invoices')->where('add_manage_invoices.generate_id',$request->generate_id)->get();
            // dd($tours->agent_Id);
            if(isset($tours[0]->agent_Id) && $tours[0]->agent_Id != null && $tours[0]->agent_Id != ''){
                $agent_data = DB::table('Agents_detail')->where('Agents_detail.id',$tours[0]->agent_Id)->first();
            }else{
                $agent_data = '';
            }
        }
        return response()->json(['message'=>'success','tours'=>$tours,'agent_data'=>$agent_data]);
    }
    
    public function get_tour_iteneryQ(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $customer_id = $userData->id;
        if($request->type == 'Invoice'){
            $tours = DB::table('add_manage_quotations')->where('add_manage_quotations.generate_id',$request->generate_id)->get();
        }
        return response()->json(['message'=>'success','tours'=>$tours]);
    }
    
    public function financial_statement(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            
            // Select Agent Invoices 
            $agent_Data = DB::table('Agents_detail')->where('id',$request->agent_id)->first();
             $agent_ledger = DB::table('agents_ledgers')->where('agent_id',$request->agent_id)->orderBy('id','asc')->get();
       
             
             
             return response()->json([
                 'status'=>'success',
                 'data'=>$agent_ledger,
                 'agent_data'=>$agent_Data
                 ]);
            //  print_r($payment_trans_package_inv);
            
        }
    }
 
 /*
|--------------------------------------------------------------------------
| bookingController Function Ended
|--------------------------------------------------------------------------
*/   
}


