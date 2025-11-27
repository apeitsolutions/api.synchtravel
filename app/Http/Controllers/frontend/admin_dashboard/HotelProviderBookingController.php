<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\Tour;
use App\Models\country;
use App\Models\view_booking_payment_recieve;
use App\Models\view_booking_payment_recieve_Activity;
use Auth;
use DB;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\booking_customers;
use Illuminate\Support\Facades\Hash;

class HotelProviderBookingController extends Controller
{
    
      public function hotel_provider_bookings(Request $request)
 {
     
      DB::beginTransaction();
        
            try {
        $lead_passenger = json_decode($request->lead_passenger_details);
        // print_r($lead_passenger);
        // die;
        $userData = CustomerSubcription::where('Auth_key',$request->auth_token)->select('id','status')->first();

                $customer_exist = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$lead_passenger->lead_email)->first();
                if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                        $customer_id = $customer_exist->id;
                }else{
                   
                    
                    $password = Hash::make('admin123');
                    
                    $customer_detail                    = new booking_customers();
                    $customer_detail->name              = $lead_passenger->lead_first_name." ".$lead_passenger->lead_last_name;
                    $customer_detail->opening_balance   = 0;
                    $customer_detail->balance           = 0;
                    $customer_detail->email             = $lead_passenger->lead_email;
                    $customer_detail->password             = $password;
                    $customer_detail->phone             = $lead_passenger->lead_phone;

                    $customer_detail->customer_id       = $userData->id;
                    $result = $customer_detail->save();
                    
                    $customer_id = $customer_detail->id;
    
                    
                }
                
                $credit_cart_data = '';
                if(isset($request->token)){
                     $credit_cart_data = $request->token;
                }
               
     $record_id = DB::table('hotel_provider_bookings')->insertGetId([
                    'booking_customer_id'=>$customer_id,
                    'booked'=>'customer',
                    'credit_card_data'=> $credit_cart_data,
                    'auth_token'=>$request->auth_token,
                    'invoice_no'=>$request->invoice_no,
                    'check_in'=>$request->check_in,
                    'check_out'=>$request->check_out,
                    'rooms'=>$request->rooms,
                    'adults'=>$request->adults,
                    'childs'=>$request->childs,
                    'lead_passenger_details'=>$request->lead_passenger_details,
                    'other_passenger_details'=>$request,
                    'child_details'=>$request->other_passenger_details,
                    'provider'=>$request->provider,
                    'search_rq'=>$request->search_rq,
                    'search_rs'=>$request->search_rs,
                    'checkavailability_rq'=>$request->checkavailability_rq,
                    'checkavailability_rs'=>$request->checkavailability_rs,
                    'checkavailability_again_rq'=>$request->checkavailability_again_rq,
                    'checkavailability_again_rs'=>$request->checkavailability_again_rs,
                    'ratehawk_checkavailability_again_rq_arr'=>$request->ratehawk_checkavailability_again_rq_arr,
                     'tbo_BookingDetail_rs'=>$request->tbo_BookingDetail_rs,
                     'booking_rq'=>$request->booking_rq,
                     'booking_rs'=>$request->booking_rs,
                     'booking_detail_rq'=>$request->booking_detail_rq,
                     'booking_detail_rs'=>$request->booking_detail_rs,
                     'travellanda_policy'=>$request->travellanda_policy,
                     'booking_status'=>$request->booking_status,
                     'exchange_price'=>$request->exchange_price,
                     'exchange_currency'=>$request->exchange_currency,
                     
            
        ]);
        
        
        
       //added code by jamshaid cheena 08-06-2023
       $client_markup=$request->client_markup;
                    $admin_markup=$request->admin_markup;
                    $client_markup_type=$request->client_markup_type;
                    $admin_markup_type=$request->admin_markup_type;
                    $payable_price=$request->payable_price;
                    $client_commission_amount=$request->client_commission_amount;
                    
                    $total_markup_price=$request->total_markup_price;
                    $currency=$request->currency;
                    $exchange_client_commission_amount=$request->exchange_client_commission_amount;                                         
                    $exchange_payable_price=$request->exchange_payable_price;                                          
                    $exchange_admin_commission_amount=$request->exchange_admin_commission_amount; 
                    $exchange_total_markup_price=$request->exchange_total_markup_price; 
                    $exchange_currency=$request->exchange_currency; 
                    $exchange_rate=$request->exchange_rate; 
        
                    $admin_exchange_amount=$request->admin_exchange_amount;
                    $admin_commission_amount=$request->admin_commission_amount;
                    $admin_exchange_currency=$request->admin_exchange_currency;
                    $admin_exchange_rate=$request->admin_exchange_rate;
                    $admin_exchange_total_markup_price=$request->admin_exchange_total_markup_price;
        
        
         $price=$request->exchange_price;
        $p_price=json_decode($price);
        
        
        
        
        
                      
                                                  
                    

 $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->auth_token)->limit(1)->first();
 $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
//  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
 $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
 //print_r($price_with_out_commission);die();
        $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
            
            'token'=>$request->auth_token,
            'invoice_no'=>$request->invoice_no,
            'received_amount'=>$exchange_payable_price,
            'balance_amount'=>$big_exchange_payable_price,
            'type'=>'hotel_booking'
            ]);
        
        
        
        $manage_customer_markups = DB::table('manage_customer_markups')->insert([
            
            'token'=>$request->auth_token,
            'invoice_no'=>$request->invoice_no,
            'client_markup'=>$client_markup,
            'admin_markup'=>$admin_markup,
            'client_markup_type'=>$client_markup_type,
            'admin_markup_type'=>$admin_markup_type,
            'payable_price'=>$payable_price,
            'client_commission_amount'=>$client_commission_amount,
            'admin_commission_amount'=>$admin_commission_amount,
            'total_markup_price'=>$total_markup_price,
            'currency'=>$currency,
            
            'exchange_payable_price'=>$exchange_payable_price,
            'exchange_client_commission_amount'=>$exchange_client_commission_amount,
            'exchange_total_markup_price'=>$exchange_total_markup_price,
            'exchange_currency'=>$exchange_currency,
            'exchange_rate'=>$exchange_rate,
            
            'admin_exchange_amount'=>$admin_exchange_amount,
            'exchange_admin_commission_amount'=>$admin_commission_amount,
            'admin_exchange_currency'=>$admin_exchange_currency,
            'admin_exchange_rate'=>$admin_exchange_rate,
            'admin_exchange_total_markup_price'=>$admin_exchange_total_markup_price,
            
            ]);
            
            
            
            $admin_provider_payments_hotelbeds= DB::table('admin_provider_payments')->latest()->first();
            $payment_remaining_amount=$admin_provider_payments_hotelbeds->payment_remaining_amount ?? '0';
            $add_price=(float)$payment_remaining_amount + (float)$admin_exchange_total_markup_price;
            
            
            
            $admin_payments = DB::table('admin_provider_payments')->insert([
            
            'payment_transction_id'=>$request->invoice_no,
            'provider'=>$request->provider,
            'payment_remaining_amount'=>$add_price,
            
            
            ]);
       //ended code by jamshaid cheena 08-06-2023
       
       
        
        
        
       
        
        
        if(isset($request->rooms_data)){
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
             
                
                
                    $rooms_data = json_decode($request->rooms_data);
                    foreach($rooms_data as $index => $room_res_data){
                        
                        
                        if($room_res_data->rooms_on_rq == 1){
                            
                        }else{
                          
                                  $booking_status='Non Refundable';
                                // $booking_status='Confirmed'; 
                       
                                
                                $hotel_data = DB::table('hotel_provider_bookings')->where('id',$record_id)->select('check_in','check_out')->first();
                                // print_r($hotel_data);
                                // die;
                               
                                    // die;
                    
                                    $room_res = $room_res_data->rooms_id;
                                                        
                                                            $room_data = DB::table('rooms')->where('id',$room_res)->first();
                                                            if($room_data){

                                                                $rooms_qty = 1;
                                                                
                                                                $total_booked = $room_data->booked + $rooms_qty;
                                        
                                                                 DB::table('rooms_bookings_details')->insert([
                                                                     'room_id'=> $room_res,
                                                                     'booking_from'=>'website',
                                                                     'quantity'=>$rooms_qty,
                                                                     'booking_id'=>$record_id,
                                                                     'date'=>date('Y-m-d'),
                                                                     'check_in'=>$hotel_data->check_in,
                                                                     'check_out'=>$hotel_data->check_out,
                                                                 ]);
                                                                 
                                                       
                                                                DB::table('rooms')->where('id',$room_res)->update(['booked'=>$total_booked]);
                                                                
                                                                
                                                                
                                                                // Update Hotel Supplier Balance
                                                        
                                                                 $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
                                       
                                                                 if(isset($supplier_data)){
                                                                        // echo "Enter hre ";
                                                                        
                                                                             $week_days_total = 0;
                                                                             $week_end_days_totals = 0;
                                                                             $total_price = 0;
                                                                             $hotel_data->check_in = date('Y-m-d',strtotime($hotel_data->check_in));
                                                                             $hotel_data->check_out = date('Y-m-d',strtotime($hotel_data->check_out));
                                                                            if($room_data->price_week_type == 'for_all_days'){
                                                                                $avaiable_days = dateDiffInDays($hotel_data->check_in, $hotel_data->check_out);
                                                                                $total_price = $room_data->price_all_days * $avaiable_days;
                                                                            }else{
                                                                                 $avaiable_days = dateDiffInDays($hotel_data->check_in, $hotel_data->check_out);
                                                                                 
                                                                                 $all_days = getBetweenDates($hotel_data->check_in, $hotel_data->check_out);
                                                                                 
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
                                                                            'website_booking_id'=>$record_id,
                                                                            'available_from'=>$hotel_data->check_in,
                                                                            'available_to'=>$hotel_data->check_out,
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
        
        
        
        
        
         DB::commit();
                
                return response()->json(['message'=>'success']);
                
                   
                    // dd($invoiceId);
                    // return response()->json(['message'=>'success','booking_id'=>$tourObj->id,'invoice_id'=>$invoiceId]);
                } catch (\Exception $e) {
                    DB::rollback();
                    echo $e;die;
                    return response()->json(['status'=>'error']);
                    // something went wrong
                }
       
       
        
 }
    
    
 public function bookings(Request $request)
 {
     //print_r($request->invoice_no);die();
if($request->invoice_no)
{
 
 $result = DB::table('hotel_provider_bookings')->insert([
     
            'invoice_no' => $request->invoice_no,
            'search_rq' => $request->search_rq,
            'search_rs' => $request->search_rs,
            
        ]);
 }
 
 if($request->checkavailability_rq)
{
 
 $result = DB::table('hotel_provider_bookings')->where('invoice_no',$request->invoice_id)->update([
     
            'checkavailability_rq' => $request->checkavailability_rq,
            'checkavailability_rs' => $request->checkavailability_rs,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'provider' => $request->provider,
            'rooms' => $request->rooms,
            'adults' => $request->adults,
            'childs' => $request->childs,
            
        ]);
}

if($request->checkavailability_again_rq)
{
 
 
  if($request->travellanda_policy)
 {
     $policy=$request->travellanda_policy;
 }
 else
 {
  $policy='';  
 }
 
 $result = DB::table('hotel_provider_bookings')->where('invoice_no',$request->invoice_id)->update([
     
            'checkavailability_again_rq' => $request->checkavailability_again_rq,
            'checkavailability_again_rs' => $request->checkavailability_again_rs,
            'travellanda_policy' => $policy,
            'ratehawk_checkavailability_again_rq_arr' => $request->ratehawk_checkavailability_again_rq_arr,
            
        ]);
}

if($request->booking_rq)
{
 
 if($request->refundable == 'refundable')
 {
     $booking_status='Confirmed';
 }
 else
 {
   $booking_status='Non-Refundable';  
 }
 if($request->booked == 'Agent')
 {
     $Agent='Agent';
 }
 else
 {
   $Agent='Customer';  
 }
 
 $result = DB::table('hotel_provider_bookings')->where('invoice_no',$request->invoice_id)->update([
     
            'booking_rq' => $request->booking_rq,
            'booking_rs' => $request->booking_rs,
            'booking_detail_rq' => $request->booking_detail_rq,
            'booking_detail_rs' => $request->booking_detail_rs,
            'exchange_price' => $request->exchange_price,
            'exchange_currency' => $request->exchange_currency,
            'auth_token' => $request->auth_token,
            'booking_status' => $booking_status,
            'tbo_BookingDetail_rs' => $request->tbo_BookingDetail_rs ?? '',
            'booked' => $Agent,
            
            'lead_passenger_details' => $request->lead_passenger_details,
            'other_passenger_details' => $request->other_passenger_details,
            'child_details' => $request->other_child,
            
        ]);
}
if($request->booking_rs)
{
 
 if($request->refundable == 'refundable')
 {
     $booking_status='Confirmed';
 }
 else
 {
   $booking_status='Non-Refundable';  
 }
 if($request->booked == 'Agent')
 {
     $Agent='Agent';
 }
 else
 {
   $Agent='Customer';  
 }
 
 $result = DB::table('hotel_provider_bookings')->where('invoice_no',$request->invoice_id)->update([
     
            'booking_rq' => $request->booking_rq,
            'booking_rs' => $request->booking_rs,
            'booking_detail_rq' => $request->booking_detail_rq,
            'booking_detail_rs' => $request->booking_detail_rs,
            'exchange_price' => $request->exchange_price,
            'exchange_currency' => $request->exchange_currency,
            'auth_token' => $request->auth_token,
            'booking_status' => $booking_status,
            'tbo_BookingDetail_rs' => $request->tbo_BookingDetail_rs ?? '',
            'booked' => $Agent,
            
           
            
        ]);
}


if($request->cancell_booking_rq)
{
 

 
 $result = DB::table('hotel_provider_bookings')->where('invoice_no',$request->invoice_id)->update([
     
            'cancell_booking_rq' => $request->cancell_booking_rq,
            'cancell_booking_rs' => $request->cancell_booking_rs,
            'booking_status' => $request->booking_status,
           
        ]);
}

if($request->tbo_BookingDetail_rs)
{
 

 
 $result = DB::table('hotel_provider_bookings')->where('invoice_no',$request->invoice_id)->update([
     
            'tbo_BookingDetail_rs' => $request->tbo_BookingDetail_rs,
           
           
        ]);
}

if($request->lead_passenger_details)
{
 

 
 $result = DB::table('hotel_provider_bookings')->where('invoice_no',$request->invoice_id)->update([
     
            'lead_passenger_details' => $request->lead_passenger_details,
            'other_passenger_details' => $request->other_passenger_details,
            'child_details' => $request->child_details,
           
           
        ]);
        return response()->json(['message'=>'success','result'=>$result]);
}







 
 
 
 }

    
    
}
