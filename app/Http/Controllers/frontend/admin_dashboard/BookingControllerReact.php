<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\ToursBooking;
use App\Models\booking_users;
use App\Models\Cart_details;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\view_booking_payment_recieve;
use App\Models\pay_Invoice_Agent;
use App\Models\alhijaz_Notofication;
use App\Models\booking_customers;

use DB;



class BookingControllerReact extends Controller
{
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
            //     die;
            //   $json_data = json_encode($cart_data);
            //     $array_data = json_decode($json_data, true);
                
            //         print_r($array_data);
            //     // die;
                foreach($cart_data as $index => $cart_res){
                     $cart_data[$index]->customer_id = $customer_id;
                 }
                 
            
                 
                //         print_r($cart_data);
                // die;
                 
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
                    
                    if($cart_res->Additional_services_names_more !== 'null' AND !empty($cart_res->Additional_services_names_more)){
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
                    
                    $Cart_details->stripe_payment_response = "";
                            
                    
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
                                            //   dd('enter ');
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
                                  
                                         $total_booked = $room_data->booked + (int)$rooms_qty;
                               
                                        // dd($total_booked);
                                         
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
                                                 
                                                //  dd($rooms_qty);
                                                 
                                                 $total_booked = (int)$room_data->booked + (int)$rooms_qty;
                                       
                                        
                                        
                                                 
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
                                                    
                                                    // dd($rooms_qty,$total_price);
                                                $all_days_price = (int)$total_price * (int)$rooms_qty;
                                                
                                                // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                                // die;
                                                
                                                // echo "The supplier Balance is ".$supplier_data->balance;
                                                $supplier_balance = $supplier_data->balance;
                                                $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                                
                                                // update Agent Balance
                                                if(!empty($rooms_qty)){
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
                        if(isset($cart_res->triple_adults) && !empty($cart_res->triple_adults)){
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
                        if(isset($cart_res->quad_adults)  && !empty($cart_res->quad_adults)){
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
                        if(isset($cart_res->without_acc_adults) && !empty($cart_res->without_acc_adults)){
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
    
     function invoice_data(Request $request){
        DB::beginTransaction();
        try {
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            if($userData){
              if($userData->status == 1){
                    $inv_details = ToursBooking::where('invoice_no',$request->booking_id)->first();
                    if($inv_details){
                        $cart_data = cart_details::where('invoice_no',$request->booking_id)->get();
                        $cart_data_count = count($cart_data);
                        
                        if($cart_data[0]->pakage_type == 'tour'){
                            $tours  = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
                                        ->where('tours.generate_id',$cart_data[0]->generate_id)->select('tours.*','tours_2.*')->get();
                        }
                        if($cart_data[0]->pakage_type == 'activity'){
                            $tours  = DB::table('new_activites')->where('generate_id',$cart_data[0]->generate_id)->get();
                        }
                        if($cart_data_count > 0){
                            return response()->json(['message'=>'success','inv_details'=>$inv_details,'cart_data'=> $cart_data,'tour_data' => $tours]);
                        }else{
                            return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'']);
                        }
                    }else{
                        return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'']);
                    }
                }
            }
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
       
    }
    
    function invoice_package_data(Request $request){
         $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            if($userData){
                if($userData->status == 1){
                    try {
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
                                    
                                    $tour_data_id = DB::table('tours')->where('id',$T_ID)->first();
                                    
                                    if($package_Type == 'tour'){
                                        $tour_ID = DB::table('tours')->where('id',$T_ID)->first();
                                    }else{
                                        $tour_ID = DB::table('new_activites')->where('id',$T_ID)->first();
                                    }
                                    
                                    $inv_details = ToursBooking::where('invoice_no',$request->booking_id)->first();
                                    // print_r($inv_details);die();
                                    
                                    $total_paid_amount_list = DB::table('invoices_payment_recv')->where('invoice_type','Package invoice')->where('invoice_no',$request->booking_id)->get();
                                    
                                    if($inv_details){
                                        // print_r('if');die();
                                        $cart_data = cart_details::where('invoice_no',$request->booking_id)->get();
                                        $cart_data1 = cart_details::where('invoice_no',$request->booking_id)->first();
                                        $tour_batchs=DB::table('tour_batchs')->where('generate_id',$cart_data1->generate_id)->first();
                                        
                                        if($payment_Status->pakage_type == 'tour'){
                                            $tours  = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
                                                            ->where('tours.generate_id',$payment_Status->generate_id)->select('tours.*','tours_2.*')->get();
                                            }
                                            if($payment_Status->pakage_type == 'activity'){
                                                $tours  = DB::table('active_batchs')->where('generate_id',$payment_Status->generate_id)->select('id','tour_id','generate_id','title','Itinerary_details','tour_itinerary_details_1','currency_symbol','start_date','end_date','time_duration','tour_location','cancellation_policy','checkout_message','starts_rating')->get();
                                            }
            
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
                                            'total_paid_amount_list'   => $total_paid_amount_list,
                                            'tours_data' => $tours
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
    
}