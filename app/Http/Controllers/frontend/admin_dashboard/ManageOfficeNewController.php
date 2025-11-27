<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\Agents_detail;
use App\Models\booking_customers;
use App\Models\addManageInvoice;
use App\Models\pay_Invoice_Agent;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\Tour;
use App\Models\Active;
use App\Models\country;
use App\Models\Activities;
use App\Models\payInvoiceAgent;
use App\Models\rooms_Invoice_Supplier;
use App\Models\alhijaz_Notofication;
use App\Models\flight_seats_occupied;
use App\Models\expense\expense;
use App\Models\expense\expenseCategory;
use App\Models\expense\expenseSubCategory;
use App\Models\Accounts\CashAccounts;
use App\Models\Accounts\CashAccountsBal;
use App\Models\Accounts\CashAccountledger;
use App\Models\uploadDocumentInvoice;
use App\Models\agent_Slots;
use DB;
use Carbon\Carbon;

class ManageOfficeNewController extends Controller
{
    public function update_Invoices_test(Request $req){
        $id     = $req->id;
        $insert = addManageInvoice::find($id);
        
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
        
        if($insert){
            
            $prev_acc = $insert->accomodation_details;
            $prev_acc_more = $insert->accomodation_details_more;
        
            $previous_agent = $insert->agent_Id;
            $new_agent = $req->agent_Id;
            
            $previous_transfer_sup = $insert->transfer_supplier_id;
            $new_transfer_sup = $req->transfer_supplier_id;
            
            $previous_customer = $insert->booking_customer_id;
            $new_customer = $req->booking_customer_id;
            
            $previous_total_price = $insert->total_sale_price_all_Services;
            $new_total_price = $req->total_sale_price_all_Services;
            
            $prev_flight_pax = json_decode($insert->flights_Pax_details);
            $new_flight_pax = json_decode($req->flights_Pax_details);
            
            $prev_transfer_det = json_decode($insert->transportation_details);
            $new_transfer_det = json_decode($req->transportation_details);
            
            
            $accomodation_data = json_decode($req->accomodation_details);
            $accomodation_more_data = json_decode($req->more_accomodation_details);
            
            $prev_visa_all_details = json_decode($insert->all_visa_price_details);
            $new_visa_all_details = json_decode($req->all_visa_price_details);
              
            if(isset($accomodation_data)){
                foreach($accomodation_data as $index => $acc_res){
                if($acc_res->room_select_type == 'true'){
                    
                    $room_type_data = json_decode($acc_res->new_rooms_type);
                     $Rooms = new Rooms;
                                $Rooms->hotel_id =  $acc_res->hotel_id;
                                 $Rooms->rooms_on_rq= '';
                                $Rooms->room_type_id =  $room_type_data->parent_cat; 
                                $Rooms->room_type_name =  $room_type_data->room_type; 
                                $Rooms->room_type_cat =  $room_type_data->id; 
                              
                               
                                $Rooms->quantity =  $acc_res->acc_qty;  
                                $Rooms->min_stay =  0; 
                                $Rooms->max_child =  1; 
                                $Rooms->max_adults =  $room_type_data->no_of_persons; 
                                $Rooms->extra_beds =  0; 
                                $Rooms->extra_beds_charges =  0; 
                                $Rooms->availible_from =  $acc_res->acc_check_in; 
                                $Rooms->availible_to =  $acc_res->acc_check_out; 
                                $Rooms->room_option_date =  $acc_res->acc_check_in; 
                                $Rooms->price_week_type =  'for_all_days'; 
                                $Rooms->price_all_days =  $acc_res->price_per_room_purchase;
                                $Rooms->room_supplier_name =  $acc_res->new_supplier_id;
                                $Rooms->room_meal_type  = $acc_res->hotel_meal_type;
                                // $Rooms->weekdays = serialize($request->weekdays);
                                $Rooms->weekdays = null;
                                $Rooms->weekdays_price =  NULL; 
                                // $Rooms->weekends =  serialize($request->weekend); 
                                $Rooms->weekends =  null;
                                $Rooms->weekends_price =  NULL; 
                                
                              
                                
                                $user_id = $req->customer_id;
                                $Rooms->owner_id = $user_id;
                                
                                
                                $result = $Rooms->save();
                                $Roomsid = $Rooms->id;
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                   
                                    if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                        
                                             $week_days_total = 0;
                                             $week_end_days_totals = 0;
                                             $total_price = 0;
                                            if($Rooms->price_week_type == 'for_all_days'){
                                                $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                $total_price = $Rooms->price_all_days * $avaiable_days;
                                            }else{
                                                 $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                 
                                                 
                                                 $all_days = getBetweenDates($Rooms->availible_from, $Rooms->availible_to);
                                                 $week_days = json_decode($Rooms->weekdays);
                                                 $week_end_days = json_decode($Rooms->weekends);
                                                 
                                                
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
                                              
                                                     if($week_day_found){
                                                         $week_days_total += $Rooms->weekdays_price;
                                                     }else{
                                                         $week_end_days_totals += $Rooms->weekends_price;
                                                     }
                                                     
                                                     
                                                    //  foreach($week_end_days as $week_day_res){
                                                    //      if($week_day_res == $day){
                                                    //          $week_end_day_found = true;
                                                    //      }
                                                    //  }
                                                    //   if($week_end_day_found){
                                                          
                                                    //  }
                                                 }
                                                 
                                                 
                                                  
                                                 
                                                //  print_r($all_days);
                                                 $total_price = $week_days_total + $week_end_days_totals;
                                            }
                                            
                                            
                                        $all_days_price = $total_price * $Rooms->quantity;
                                        $supplier_balance = $supplier_data->balance + $all_days_price;
                                        
                                        // update Agent Balance
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'=>$supplier_data->id,
                                            'payment'=>$all_days_price,
                                            'balance'=>$supplier_balance,
                                            'payable_balance'=>$supplier_data->payable,
                                            'room_id'=>$Roomsid,
                                            'customer_id'=>$user_id,
                                            'date'=>date('Y-m-d'),
                                            'available_from'=>$Rooms->availible_from,
                                            'available_to'=>$Rooms->availible_to,
                                            'room_quantity'=>$Rooms->quantity,
                                            ]);
                                            
                                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                        
                                        
                                          
                                                                    
                                    }
                                    
                                $accomodation_data[$index]->acc_type = $room_type_data->parent_cat;
                                $accomodation_data[$index]->hotel_supplier_id = $acc_res->new_supplier_id;
                                $accomodation_data[$index]->hotel_type_id = $room_type_data->id;
                                $accomodation_data[$index]->hotel_type_cat = $room_type_data->room_type;
                                $accomodation_data[$index]->hotelRoom_type_id = $Roomsid;
                }
                
            }
            }
   
            if(isset($accomodation_more_data)){
                foreach($accomodation_more_data as $index => $acc_res){
                if($acc_res->more_room_select_type == 'true'){
                    
                    $room_type_data = json_decode($acc_res->more_new_rooms_type);
                     $Rooms = new Rooms;
                                $Rooms->hotel_id =  $acc_res->more_hotel_id;
                                 $Rooms->rooms_on_rq= '';
                                $Rooms->room_type_id =  $room_type_data->parent_cat; 
                                $Rooms->room_type_name =  $room_type_data->room_type; 
                                $Rooms->room_type_cat =  $room_type_data->id; 
                              
                               
                                $Rooms->quantity =  $acc_res->more_acc_qty;  
                                $Rooms->min_stay =  0; 
                                $Rooms->max_child =  1; 
                                $Rooms->max_adults =  $room_type_data->no_of_persons; 
                                $Rooms->extra_beds =  0; 
                                $Rooms->extra_beds_charges =  0; 
                                $Rooms->availible_from =  $acc_res->more_acc_check_in; 
                                $Rooms->availible_to =  $acc_res->more_acc_check_out; 
                                $Rooms->room_option_date =  $acc_res->more_acc_check_in; 
                                $Rooms->price_week_type =  'for_all_days'; 
                                $Rooms->price_all_days =  $acc_res->more_price_per_room_purchase;
                                $Rooms->room_supplier_name =  $acc_res->more_new_supplier_id;
                                $Rooms->room_meal_type  = $acc_res->more_hotel_meal_type;
                                // $Rooms->weekdays = serialize($request->weekdays);
                                $Rooms->weekdays = null;
                                $Rooms->weekdays_price =  NULL; 
                                // $Rooms->weekends =  serialize($request->weekend); 
                                $Rooms->weekends =  null;
                                $Rooms->weekends_price =  NULL; 
                                
                              
                                
                                $user_id = $req->customer_id;
                                $Rooms->owner_id = $user_id;
                                
                                
                                $result = $Rooms->save();
                                $Roomsid = $Rooms->id;
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                   
                                    if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                        
                                             $week_days_total = 0;
                                             $week_end_days_totals = 0;
                                             $total_price = 0;
                                            if($Rooms->price_week_type == 'for_all_days'){
                                                $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                $total_price = $Rooms->price_all_days * $avaiable_days;
                                            }
                                            
                                            
                                        $all_days_price = $total_price * $Rooms->quantity;
                                        $supplier_balance = $supplier_data->balance + $all_days_price;
                                        
                                        // update Agent Balance
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'=>$supplier_data->id,
                                            'payment'=>$all_days_price,
                                            'balance'=>$supplier_balance,
                                            'payable_balance'=>$supplier_data->payable,
                                            'room_id'=>$Roomsid,
                                            'customer_id'=>$user_id,
                                            'date'=>date('Y-m-d'),
                                            'available_from'=>$Rooms->availible_from,
                                            'available_to'=>$Rooms->availible_to,
                                            'room_quantity'=>$Rooms->quantity,
                                            ]);
                                            
                                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                        
                                        
                                          
                                                                    
                                    }
                                    
                                $accomodation_more_data[$index]->more_acc_type = $room_type_data->parent_cat;
                                $accomodation_more_data[$index]->more_hotel_supplier_id = $acc_res->more_new_supplier_id;
                                $accomodation_more_data[$index]->more_hotel_type_id = $room_type_data->id;
                                $accomodation_more_data[$index]->more_hotel_type_cat = $room_type_data->room_type;
                                $accomodation_more_data[$index]->more_hotelRoom_type_id = $Roomsid;
                }
                
            }
            }
            
            $req->accomodation_details = json_encode($accomodation_data);
            $req->more_accomodation_details = json_encode($accomodation_more_data);
            
            $visa_details = json_decode($req->all_visa_price_details);
            // print_r($visa_details);
            // die;
            
            if(isset($visa_details)){
                foreach($visa_details as $index => $visa_res){
                    
                    // 1 Check Add New Visa or Exists Use
                    if($visa_res->visa_add_type_new !== 'false'){
                        // Add As New
                        
                        $visa_avail_id = DB::table('visa_Availability')->insertGetId([
                                'visa_supplier' => $visa_res->visa_supplier_id,
                                'visa_type' => $visa_res->visa_type_id,
                                'visa_qty' => $visa_res->visa_occupied,
                                'visa_available' => $visa_res->visa_occupied,
                                'visa_price' => $visa_res->visa_fee_purchase,
                                'availability_from' => $visa_res->visa_av_from,
                                'availability_to' => $visa_res->visa_av_to,
                                'country' => $visa_res->visa_country_id,
                                'currency_conversion' => $req->conversion_type_Id,
                                'visa_price_conversion_rate' => $visa_res->exchange_rate_visa,
                                'visa_converted_price'=> $visa_res->visa_fee,
                                'customer_id' => $req->customer_id,
                        ]);
                        
                           $visa_details[$index]->visa_avail_id = $visa_avail_id;
                           $supplier_data = DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->first();
        
                            if(isset($supplier_data)){
                                $supplier_balance = $supplier_data->balance + $visa_res->visa_purchase_total;
                                
                                DB::table('visa_supplier_ledger')->insert([
                                        'supplier_id' => $visa_res->visa_supplier_id,
                                        'payment' => $visa_res->visa_purchase_total,
                                        'balance' => $supplier_balance,
                                        'payable' => $supplier_data->payable,
                                        'visa_qty' => $visa_res->visa_occupied,
                                        'visa_type' => $visa_res->visa_type_id,
                                        'visa_price' => $visa_res->visa_fee_purchase,
                                        'visa_avl_id'=> $visa_avail_id,
                                        'date'=> date('Y-m-d'),
                                        'remarks'=> 'New Visa Purchased',
                                        'customer_id'=> $req->customer_id,
                                    ]);
                                
                                $data=DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([  
                                   'balance'               => $supplier_balance,
                                ]);
                            }
                    }
                    
                   
                    
                 
                }
            }
            $new_visa_all_details = $visa_details;
            $visa_details = json_encode($visa_details);
            
            // print_r($insert);
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->services                = $req->services;
            $insert->booking_customer_id = $req->booking_customer_id;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name     = $req->agent_Company_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
             
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            // $insert->quotation_prepared      = $req->quotation_prepared;
            // $insert->quotation_valid_date    = $req->quotation_valid_date;
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $insert->generate_id                    = $generate_id;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            
            $insert->flight_route_type              = $req->flight_route_type;
            $insert->flight_supplier                = $req->flight_supplier;
            $insert->flights_details                = $req->flights_details;
            $insert->return_flights_details         = $req->return_flights_details;
            $insert->flights_Pax_details            = $req->flights_Pax_details;
            
            $insert->accomodation_details           = $req->accomodation_details;
            $insert->accomodation_details_more      = $req->more_accomodation_details;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            $insert->more_visa_details              = $req->more_visa_details;
            $insert->all_visa_price_details         = $visa_details;
            
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_image                     = $req->visa_image;
            
            $insert->without_markup_total_price_visa    = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa            = $req->markup_total_price_visa;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            $insert->gallery_images                 = $req->gallery_images;
            $insert->start_date                     = $req->start_date;
            $insert->end_date=$req->end_date;
            $insert->time_duration=$req->time_duration;
            $insert->tour_location=$req->tour_location;
            $insert->whats_included=$req->whats_included;
            $insert->whats_excluded=$req->whats_excluded;
            $insert->currency_symbol=$req->currency_symbol;
            $insert->tour_publish=$req->defalut_state;
            $insert->tour_author=$req->tour_author;
            $insert->tour_feature=$req->tour_feature;
            $insert->defalut_state=$req->defalut_state;
            $insert->payment_gateways=$req->payment_gateways;
            $insert->payment_modes=$req->payment_modes;
            $insert->markup_details=$req->markup_details;
            $insert->more_markup_details=$req->more_markup_details;
            $insert->tour_featured_image=$req->tour_featured_image;
            $insert->tour_banner_image=$req->tour_banner_image;
            $insert->Itinerary_details=$req->Itinerary_details;
            $insert->tour_itinerary_details_1=$req->tour_itinerary_details_1;
            $insert->tour_extra_price=$req->tour_extra_price;
            $insert->tour_extra_price_1=$req->tour_extra_price_1;
            $insert->tour_faq=$req->tour_faq;
            $insert->tour_faq_1=$req->tour_faq_1;
            
            $insert->payment_messag=$req->checkout_message;
            
            $insert->total_Cost_Price_All=$req->total_Cost_Price_All;
            $insert->total_Sale_Price_All=$req->total_Sale_Price_All;
            
            $insert->option_date=$req->option_date;
            $insert->reservation_number=$req->reservation_number;
            $insert->hotel_reservation_number=$req->hotel_reservation_number;
            
            $insert->total_sale_price_all_Services=$req->total_sale_price_all_Services;
            $insert->total_cost_price_all_Services=$req->total_cost_price_all_Services;
            
            $insert->transfer_supplier      = $req->transfer_supplier;
            $insert->transfer_supplier_id   = $req->transfer_supplier_id;
            
            DB::beginTransaction();
              
            try {
                    $insert->update();
                    
                    $invoice_id     = $insert->id;
                    $prev_acc       = json_decode($prev_acc);
                    $prev_acc_more  = json_decode($prev_acc_more);
                    $new_acc        = json_decode($req->accomodation_details);
                    $new_acc_more   = json_decode($req->more_accomodation_details);
                    
                    $flights_Pax_details = json_decode($req->flights_Pax_details);
                    if(isset($flights_Pax_details) && $flights_Pax_details != null && $flights_Pax_details != ''){
                        
                        $occupied_against_id = DB::table('flight_seats_occupied')->where('booking_id',$id)->get();
                        if(isset($occupied_against_id) && $occupied_against_id != null && $occupied_against_id != ''){
                            foreach($occupied_against_id as $occupied_against_idS){
                                DB::table('flight_seats_occupied')->where('id',$occupied_against_idS->id)->delete();
                            }
                        }
                        
                        foreach($flights_Pax_details as $value){
                            $check_id = DB::table('flight_seats_occupied')->where('booking_id',$id)->where('flight_route_id',$value->flight_route_id_occupied)->first();
                            if(isset($check_id) && $check_id != null && $check_id != ''){
                                DB::table('flight_seats_occupied')->insert([
                                    'token'                         => $req->token,
                                    'type'                          => 'Invoice',
                                    'booking_id'                    => $invoice_id,
                                    'flight_supplier'               => $req->flight_supplier,
                                    'flight_route_id'               => $value->flight_route_id_occupied,
                                    'flights_adult_seats'           => $value->flights_adult_seats,
                                    'flights_child_seats'           => $value->flights_child_seats,
                                    'flight_route_seats_occupied'   => $value->flights_adult_seats + $value->flights_child_seats,
                                ]);
                            }else{
                                DB::table('flight_seats_occupied')->insert([
                                    'token'                         => $req->token,
                                    'type'                          => 'Invoice',
                                    'booking_id'                    => $invoice_id,
                                    'flight_supplier'               => $req->flight_supplier,
                                    'flight_route_id'               => $value->flight_route_id_occupied,
                                    'flights_adult_seats'           => $value->flights_adult_seats,
                                    'flights_child_seats'           => $value->flights_child_seats,
                                    'flight_route_seats_occupied'   => $value->flights_adult_seats + $value->flights_child_seats,
                                ]);
                            }
                        }
                    }
                    
                    // Agent Data Updated
                    if($previous_agent != $new_agent){
                         
                         // Agent is Changed
                         // previous Agent Working
                   
                              
                              
                                $agent_data = DB::table('Agents_detail')->where('id',$previous_agent)->select('id','balance')->first();
       
                                if(isset($agent_data)){
                                    // echo "Enter hre ";
                                    $received_amount = -1 * abs($previous_total_price);
                                    $agent_balance = $agent_data->balance - $previous_total_price;
                                    
                                    // update Agent Balance
                                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                    DB::table('agents_ledgers_new')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$agent_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=>$insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                }
                        
                         
                         
                         // New Agent Working
                    
                             
                              
                                $agent_data = DB::table('Agents_detail')->where('id',$new_agent)->select('id','balance')->first();
       
                                if(isset($agent_data)){
                                    // echo "Enter hre ";
                                    $received_amount = $new_total_price;
                                    $agent_balance = $agent_data->balance + $new_total_price;
                                    
                                    // update Agent Balance
                                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                    DB::table('agents_ledgers_new')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$agent_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=>$insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                }
                        
                         
                         
                    }else{
                         // Agent is Not Changed
                         
                              $difference  = $new_total_price - $previous_total_price;
                              
                            //   echo "Differ is $difference ";
                              
                                $agent_data = DB::table('Agents_detail')->where('id',$new_agent)->select('id','balance')->first();
       
                                if(isset($agent_data)){
                                    // echo "Enter hre ";
                                    $agent_balance = $agent_data->balance + $difference;
                                    
                                    // update Agent Balance
                                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                    DB::table('agents_ledgers_new')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'received'=>$difference,
                                        'balance'=>$agent_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=> $insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                }
                        
                         
                     }
                    
                    // Customer Data Updated
                    if($previous_customer != $new_customer){
                         
                         // Agent is Changed
                         // previous Agent Working
                   
                              
                              
                                $customer_data = DB::table('booking_customers')->where('id',$previous_customer)->select('id','balance')->first();
       
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $received_amount = -1 * abs($previous_total_price);
                                    $customer_balance = $customer_data->balance - $previous_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$customer_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=>$insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                }
                        
                         
                         
                         // New Agent Working
                    
                             
                              
                                $customer_data = DB::table('booking_customers')->where('id',$new_customer)->select('id','balance')->first();
       
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $received_amount = $new_total_price;
                                    $customer_balance = $customer_data->balance + $new_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$customer_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=>$insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                }
                        
                         
                         
                    }else{
                         // Agent is Not Changed
                         
                              $difference  = $new_total_price - $previous_total_price;
                              
                            //   echo "Differ is $difference ";
                              
                                $customer_data = DB::table('booking_customers')->where('id',$new_customer)->select('id','balance')->first();
       
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $customer_balance = $customer_data->balance + $difference;
                                    
                                    // update Agent Balance
                                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$difference,
                                        'balance'=>$customer_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=> $insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                }
                        
                         
                    }
                
                    // Loop on Previous Accomodations 
                    DB::table('rooms_bookings_details')->where('booking_id', "$insert->id")->where('booking_from','Invoices')->delete();
                    
                    // Previous Element Found Working
                    $arr_ele_found = [];
                    if(isset($prev_acc) && !empty($prev_acc)){
                        foreach($prev_acc as $prev_acc_res){
                            
                            if(isset($prev_acc_res->hotelRoom_type_id) AND !empty($prev_acc_res->hotelRoom_type_id)){
                                // echo $prev_acc_res->hotelRoom_type_id;
                                
                                $found = false;
                                foreach($arr_ele_found as $arr_id_res){
                                    if($arr_id_res == $prev_acc_res->hotelRoom_type_id){
                                        $found = true;
                                    }
                                }
                                
                                if(!$found){
                                    $perv_total = 0;
                                    $rooms_total_pr_prev = 0;
                                    foreach($prev_acc as $cal_total_prev){
                                        if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                                            $perv_total += $cal_total_prev->acc_qty;
                                            
                                            // Calaculate Room Prices
                                             $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                            
                                                if($room_data){
                                                         $week_days_total = 0;
                                                         $week_end_days_totals = 0;
                                                         $total_price = 0;
                                                         $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                                                         $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                                                        if($room_data->price_week_type == 'for_all_days'){
                                                            $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                            $total_price = $room_data->price_all_days * $avaiable_days;
                                                        }else{
                                                             $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                             
                                                             $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                             
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
                                                        
                                                        
                                                        $all_days_price = $total_price * $cal_total_prev->acc_qty;
                                                        $rooms_total_pr_prev += $all_days_price;
                                                }
                                                    
                                                    
                                                      
                                             
                                            
                                            
                                            
                                            
                                        }
                                    }
                                    
                                    
                                    if(isset($prev_acc_more) && !empty($prev_acc_more)){
                                        foreach($prev_acc_more as $cal_total_prev){
                                            if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                                                $perv_total += $cal_total_prev->more_acc_qty;
                                                
                                                // Calaculate Room Prices
                                           $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                
                                                    if($room_data){
                                                             $week_days_total = 0;
                                                             $week_end_days_totals = 0;
                                                             $total_price = 0;
                                                             $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                                                             $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                                                            if($room_data->price_week_type == 'for_all_days'){
                                                                $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                $total_price = $room_data->price_all_days * $avaiable_days;
                                                            }else{
                                                                 $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                                                                 $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
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
                                                            
                                                            
                                                            $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                                                            $rooms_total_pr_prev += $all_days_price;
                                                    }
                                            }
                                        }
                                    }
                                    
                                    
                                    
                                   
                                    $new_total = 0;
                                    $rooms_total_pr_new = 0;
                                    foreach($new_acc as $cal_total_prev){
                                        if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                                            $new_total += $cal_total_prev->acc_qty;
                                            
                                            // Calaculate Room Prices
                                             $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                            
                                                if($room_data){
                                                         $week_days_total = 0;
                                                         $week_end_days_totals = 0;
                                                         $total_price = 0;
                                                         $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                                                         $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                                                        if($room_data->price_week_type == 'for_all_days'){
                                                            $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                            $total_price = $room_data->price_all_days * $avaiable_days;
                                                        }else{
                                                             $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                             
                                                             $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                             
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
                                                        
                                                        
                                                        $all_days_price = $total_price * $cal_total_prev->acc_qty;
                                                        $rooms_total_pr_new += $all_days_price;
                                                }
                                            
                                            DB::table('rooms_bookings_details')->insert([
                                                    'room_id'=> $cal_total_prev->hotelRoom_type_id,
                                                    'booking_from'=> 'Invoices',
                                                    'quantity'=> $cal_total_prev->acc_qty,
                                                    'booking_id'=> $insert->id,
                                                    'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                                    'check_in'=> $cal_total_prev->acc_check_in,
                                                    'check_out'=> $cal_total_prev->acc_check_out,
                                                ]);
                                            
                                        }
                                    }
                                    
                                    
                                    if(isset($new_acc_more) && !empty($new_acc_more)){
                                        foreach($new_acc_more as $cal_total_prev){
                                            if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                                                $new_total += $cal_total_prev->more_acc_qty;
                                                
                                                  // Calaculate Room Prices
                                                 $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                
                                                    if($room_data){
                                                             $week_days_total = 0;
                                                             $week_end_days_totals = 0;
                                                             $total_price = 0;
                                                             $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                                                             $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                                                            if($room_data->price_week_type == 'for_all_days'){
                                                                $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                $total_price = $room_data->price_all_days * $avaiable_days;
                                                            }else{
                                                                 $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                                                                 $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
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
                                                            
                                                            
                                                            $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                                                            $rooms_total_pr_new += $all_days_price;
                                                    }
                                                
                                                DB::table('rooms_bookings_details')->insert([
                                                        'room_id'=> $cal_total_prev->more_hotelRoom_type_id,
                                                        'booking_from'=> 'Invoices',
                                                        'quantity'=> $cal_total_prev->more_acc_qty,
                                                        'booking_id'=> $insert->id,
                                                        'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                                        'check_in'=> $cal_total_prev->more_acc_check_in,
                                                        'check_out'=> $cal_total_prev->more_acc_check_out,
                                                    ]);
                                            }
                                        }
                                    }
                                    
                                    array_push($arr_ele_found,$prev_acc_res->hotelRoom_type_id);
                                }
                                
                                $room_data = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->first();
                                 
                                $difference = $new_total - $perv_total;
                                $Price_difference = $rooms_total_pr_new - $rooms_total_pr_prev;
                                
                                $update_booked = $room_data->booked + $difference;
                                
                                $room_data = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->update(['booked'=>$update_booked]);
                                    
                                $room_data = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->first();
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
        
                                if(isset($supplier_data)){

                                    $supplier_balance = $supplier_data->balance;
                                        $supplier_payable_balance = $supplier_data->payable + $Price_difference;
                                        
                                        // update Agent Balance
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'=>$supplier_data->id,
                                            'payment'=>$Price_difference,
                                            'balance'=>$supplier_balance,
                                            'payable_balance'=>$supplier_payable_balance,
                                            'room_id'=>$room_data->id,
                                            'customer_id'=>$insert->customer_id,
                                            'date'=>date('Y-m-d'),
                                            'invoice_no'=>$insert->id,
                                            'available_from'=>'',
                                            'available_to'=>'',
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
                    
                    if(isset($prev_acc_more) && !empty($prev_acc_more)){
                        foreach($prev_acc_more as $prev_acc_res){
                            if(isset($prev_acc_res->more_hotelRoom_type_id) AND !empty($prev_acc_res->more_hotelRoom_type_id)){
                                $found = false;
                                foreach($arr_ele_found as $arr_id_res){
                                    if($arr_id_res == $prev_acc_res->more_hotelRoom_type_id){
                                        $found = true;
                                    }
                                }
                                if(!$found){
                                    
                                    $perv_total = 0;
                                    $rooms_total_pr_prev = 0;
                                    foreach($prev_acc as $cal_total_prev){
                                        if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                                            $perv_total += $cal_total_prev->acc_qty;
                                            
                                             $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                                
                                                    if($room_data){
                                                             $week_days_total = 0;
                                                             $week_end_days_totals = 0;
                                                             $total_price = 0;
                                                             $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                                                             $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                                                            if($room_data->price_week_type == 'for_all_days'){
                                                                $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                $total_price = $room_data->price_all_days * $avaiable_days;
                                                            }else{
                                                                 $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                                                                 $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
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
                                                            
                                                            
                                                            $all_days_price = $total_price * $cal_total_prev->acc_qty;
                                                            $rooms_total_pr_prev += $all_days_price;
                                                    }
                                        }
                                    }
                                    
                                    
                                    if(isset($prev_acc_more) && !empty($prev_acc_more)){
                                        foreach($prev_acc_more as $cal_total_prev){
                                            if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                                                $perv_total += $cal_total_prev->more_acc_qty;
                                                
                                                 // Calaculate Room Prices
                                               $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                    
                                                        if($room_data){
                                                                 $week_days_total = 0;
                                                                 $week_end_days_totals = 0;
                                                                 $total_price = 0;
                                                                 $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                                                                 $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                                                                if($room_data->price_week_type == 'for_all_days'){
                                                                    $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                    $total_price = $room_data->price_all_days * $avaiable_days;
                                                                }else{
                                                                     $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                     
                                                                     $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                     
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
                                                                
                                                                
                                                                $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                                                                $rooms_total_pr_prev += $all_days_price;
                                                        }
                                            }
                                        }
                                    }
                                    
                                    
                                    $new_total = 0;
                                    $rooms_total_pr_new = 0;
                                    foreach($new_acc as $cal_total_prev){
                                        if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                                            $new_total += $cal_total_prev->acc_qty;
                                            
                                            // Calaculate Room Prices
                                                 $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                                
                                                    if($room_data){
                                                             $week_days_total = 0;
                                                             $week_end_days_totals = 0;
                                                             $total_price = 0;
                                                             $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                                                             $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                                                            if($room_data->price_week_type == 'for_all_days'){
                                                                $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                $total_price = $room_data->price_all_days * $avaiable_days;
                                                            }else{
                                                                 $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                                                                 $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
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
                                                            
                                                            
                                                            $all_days_price = $total_price * $cal_total_prev->acc_qty;
                                                            $rooms_total_pr_new += $all_days_price;
                                                    }
                                            
                                             DB::table('rooms_bookings_details')->insert([
                                                    'room_id'=> $cal_total_prev->hotelRoom_type_id,
                                                    'booking_from'=> 'Invoices',
                                                    'quantity'=> $cal_total_prev->acc_qty,
                                                    'booking_id'=> $insert->id,
                                                    'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                                    'check_in'=> $cal_total_prev->acc_check_in,
                                                    'check_out'=> $cal_total_prev->acc_check_out,
                                                ]);
                                        }
                                    }
                                    
                                    
                                     if(isset($new_acc_more) && !empty($new_acc_more)){
                                        foreach($new_acc_more as $cal_total_prev){
                                            if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                                                $new_total += $cal_total_prev->more_acc_qty;
                                                
                                                   // Calaculate Room Prices
                                                     $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                    
                                                        if($room_data){
                                                                 $week_days_total = 0;
                                                                 $week_end_days_totals = 0;
                                                                 $total_price = 0;
                                                                 $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                                                                 $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                                                                if($room_data->price_week_type == 'for_all_days'){
                                                                    $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                    $total_price = $room_data->price_all_days * $avaiable_days;
                                                                }else{
                                                                     $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                     
                                                                     $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                     
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
                                                                
                                                                
                                                                $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                                                                $rooms_total_pr_new += $all_days_price;
                                                        }
                                                
                                                DB::table('rooms_bookings_details')->insert([
                                                        'room_id'=> $cal_total_prev->more_hotelRoom_type_id,
                                                        'booking_from'=> 'Invoices',
                                                        'quantity'=> $cal_total_prev->more_acc_qty,
                                                        'booking_id'=> $insert->id,
                                                        'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                                        'check_in'=> $cal_total_prev->more_acc_check_in,
                                                        'check_out'=> $cal_total_prev->more_acc_check_out,
                                                    ]);
                                            }
                                        }
                                     }
                                    
                                    array_push($arr_ele_found,$prev_acc_res->more_hotelRoom_type_id);
                                    
                                    $room_data = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->first();
                                    $difference = $new_total - $perv_total;
                                    
                                    $Price_difference = $rooms_total_pr_new - $rooms_total_pr_prev;
                                    
                                    $update_booked = $room_data->booked + $difference;
                                    
                                    $room_data = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->update([
                                    'booked'=>$update_booked
                                    ]);
                                    
                                    $room_data = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->first();
                                     $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
           
                                     if(isset($supplier_data)){
    
                                        $supplier_balance = $supplier_data->balance;
                                            $supplier_payable_balance = $supplier_data->payable + $Price_difference;
                                            
                                            // update Agent Balance
                                            
                                            DB::table('hotel_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'payment'=>$Price_difference,
                                                'balance'=>$supplier_balance,
                                                'payable_balance'=>$supplier_payable_balance,
                                                'room_id'=>$room_data->id,
                                                'customer_id'=>$insert->customer_id,
                                                'date'=>date('Y-m-d'),
                                                'invoice_no'=>$insert->id,
                                                'available_from'=>'',
                                                'available_to'=>'',
                                                'remarks'=>'Invoice Updated',
                                                ]);
                                                
                                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                'balance'=>$supplier_balance,
                                                'payable'=>$supplier_payable_balance
                                                ]);
    
                                    }
                                    // echo "The Room id is ".$prev_acc_res->more_hotelRoom_type_id." and total qty is $perv_total and new $new_total "."<br>";
                                }
                            }
                        }
                    }
                    
                    // New Element Found Working
                    if(isset($new_acc) && !empty($new_acc)){
                        foreach($new_acc as $new_acc_res){
                            $ele_found = false;
                         
                            foreach($arr_ele_found as $arr_res){
                                if($new_acc_res->hotelRoom_type_id == $arr_res){
                                    $ele_found = true;
                                }
                            }
                         
                            if(isset($new_acc_res->hotelRoom_type_id) AND !empty($new_acc_res->hotelRoom_type_id)){
                                if(!$ele_found){
                                    $room_data = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->first();
                                
                                
                                    $update_booked = (int)$room_data->booked + (int)$new_acc_res->acc_qty;
                                
                                    $room_update = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->update(['booked'=>$update_booked
                                    ]);
                            
                                    DB::table('rooms_bookings_details')->insert([
                                            'room_id'=> $new_acc_res->hotelRoom_type_id,
                                            'booking_from'=> 'Invoices',
                                            'quantity'=> $new_acc_res->acc_qty,
                                            'booking_id'=> $insert->id,
                                            'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                            'check_in'=> $new_acc_res->acc_check_in,
                                            'check_out'=> $new_acc_res->acc_check_out,
                                        ]);
                                        
                                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
   
                                    if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                    
                                         $week_days_total = 0;
                                         $week_end_days_totals = 0;
                                         $total_price = 0;
                                         $new_acc_res->acc_check_in = date('Y-m-d',strtotime($new_acc_res->acc_check_in));
                                         $new_acc_res->acc_check_out = date('Y-m-d',strtotime($new_acc_res->acc_check_out));
                                        if($room_data->price_week_type == 'for_all_days'){
                                            $avaiable_days = dateDiffInDays($new_acc_res->acc_check_in, $new_acc_res->acc_check_out);
                                            $total_price = $room_data->price_all_days * $avaiable_days;
                                        }else{
                                             $avaiable_days = dateDiffInDays($new_acc_res->acc_check_in, $new_acc_res->acc_check_out);
                                             
                                             $all_days = getBetweenDates($new_acc_res->acc_check_in, $new_acc_res->acc_check_out);
                                             
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
                                        
                                        
                                    $all_days_price = $total_price * $new_acc_res->acc_qty;
                                    
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
                                        'customer_id'=>$insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'invoice_no'=>$insert->id,
                                        'available_from'=>$new_acc_res->acc_check_in,
                                        'available_to'=>$new_acc_res->acc_check_out,
                                        'room_quantity'=>$new_acc_res->acc_qty,
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
                  
                    if(isset($new_acc_more) && !empty($new_acc_more)){
                         foreach($new_acc_more as $new_acc_res){
                             $ele_found = false;
                             
                             foreach($arr_ele_found as $arr_res){
                                 if($new_acc_res->more_hotelRoom_type_id == $arr_res){
                                     $ele_found = true;
                                 }
                             }
                             
                             if(isset($new_acc_res->more_hotelRoom_type_id) AND !empty($new_acc_res->more_hotelRoom_type_id)){
                                 if(!$ele_found){
                                 $room_data = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->first();
                                    
                                    $update_booked = (int)$room_data->booked + (int)$new_acc_res->more_acc_qty;
                                    
                                    $room_update = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->update([
                                    'booked'=>$update_booked
                                    ]);
                                    
                                    // echo "room id is from new".$new_acc_res->more_hotelRoom_type_id;
                                    // print_r($room_data);
                                
                                  DB::table('rooms_bookings_details')->insert([
                                                'room_id'=> $new_acc_res->more_hotelRoom_type_id,
                                                'booking_from'=> 'Invoices',
                                                'quantity'=> $new_acc_res->more_acc_qty,
                                                'booking_id'=> $insert->id,
                                                'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                                'check_in'=> $new_acc_res->more_acc_check_in,
                                                'check_out'=> $new_acc_res->more_acc_check_out,
                                            ]);
                                            
                                    
                                            
                                            
                                            
                                 $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
       
                                 if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                        
                                             $week_days_total = 0;
                                             $week_end_days_totals = 0;
                                             $total_price = 0;
                                             $new_acc_res->more_acc_check_in = date('Y-m-d',strtotime($new_acc_res->more_acc_check_in));
                                             $new_acc_res->more_acc_check_out = date('Y-m-d',strtotime($new_acc_res->more_acc_check_out));
                                            if($room_data->price_week_type == 'for_all_days'){
                                                $avaiable_days = dateDiffInDays($new_acc_res->more_acc_check_in, $new_acc_res->more_acc_check_out);
                                                $total_price = $room_data->price_all_days * $avaiable_days;
                                            }else{
                                                 $avaiable_days = dateDiffInDays($new_acc_res->more_acc_check_in, $new_acc_res->more_acc_check_out);
                                                 
                                                 $all_days = getBetweenDates($new_acc_res->more_acc_check_in, $new_acc_res->more_acc_check_out);
                                                 
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
                                            
                                            
                                        $all_days_price = $total_price * $new_acc_res->more_acc_qty;
                                        
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
                                            'customer_id'=>$insert->customer_id,
                                            'date'=>date('Y-m-d'),
                                            'invoice_no'=>$insert->id,
                                            'available_from'=>$new_acc_res->more_acc_check_in,
                                            'available_to'=>$new_acc_res->more_acc_check_out,
                                            'room_quantity'=>$new_acc_res->more_acc_qty,
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
                    
                    // 1 Loop on Previous
                    if(isset($prev_flight_pax)){
                        foreach($prev_flight_pax as $flight_prev_res){
                            $ele_found = false;
                            foreach($new_flight_pax as $flight_new_res){
                                if($flight_prev_res->flight_route_id_occupied == $flight_new_res->flight_route_id_occupied){
                                    $ele_found = true;
                                    $route_obj = DB::table('flight_rute')->where('id',$flight_prev_res->flight_route_id_occupied)->first();
                                    // print_r($route_obj);
                                    // die;
                                    
                                    // Calaculate Child Prev Price Differ
                                    $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $flight_prev_res->flights_child_seats;
                                    $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $flight_prev_res->flights_child_seats;
                                    
                                    $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                    
                                    // Calaculate Child New Price Differ
                                    $child_price_wi_adult_price_new = $route_obj->flights_per_person_price * $flight_new_res->flights_child_seats;
                                    $child_price_wi_child_price_new = $route_obj->flights_per_child_price * $flight_new_res->flights_child_seats;
                                    
                                    $price_diff_new = $child_price_wi_adult_price_new - $child_price_wi_child_price_new;
                                    
                                    // Calculate Final Differ
                                    $child_price_diff = $price_diff_new - $price_diff_prev;
                                    
                                    
                                    // Calaculate Infant Prev Price
                                    $infant_price_prev = $route_obj->flights_per_infant_price * $flight_prev_res->flights_infant_seats;
                                    
                                     // Calaculate Infant New Price
                                    $infant_price_new = $route_obj->flights_per_infant_price * $flight_new_res->flights_infant_seats;
                                    
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
                                                    'route_id'=>$flight_prev_res->flight_route_id_occupied,
                                                    'date'=>date('Y-m-d'),
                                                    'customer_id'=>$insert->customer_id,
                                                    'adult_price'=>$route_obj->flights_per_person_price,
                                                    'child_price'=>$route_obj->flights_per_child_price,
                                                    'infant_price'=>$route_obj->flights_per_infant_price,
                                                    'adult_seats_booked'=>$flight_new_res->flights_adult_seats,
                                                    'child_seats_booked'=>$flight_new_res->flights_child_seats,
                                                    'infant_seats_booked'=>$flight_new_res->flights_infant_seats,
                                                    'invoice_no'=>$insert->id,
                                                    'remarks'=>'Invoice Update',
                                                  ]);
                                                  
                                        DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                    }
                                    
                                }
                            }
                            
                            // If element Not Found in New
                            if(!$ele_found){
                                $route_obj = DB::table('flight_rute')->where('id',$flight_prev_res->flight_route_id_occupied)->first();
                                    // print_r($route_obj);
                                    // die;
                                    
                                    // Calaculate Child Prev Price Differ
                                    $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $flight_prev_res->flights_child_seats;
                                    $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $flight_prev_res->flights_child_seats;
                                    
                                    $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                    
                                    
                                    // Calaculate Infant Prev Price
                                    $infant_price_prev = $route_obj->flights_per_infant_price * $flight_prev_res->flights_infant_seats;
                                    
                                    
                                    
                                    
                                    
                                    $supplier_data = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                    
                                    if($price_diff_prev != 0 || $infant_price_prev != 0){
                                        $supplier_balance = $supplier_data->balance + $price_diff_prev;
                                        
                                        $supplier_balance = $supplier_balance - $infant_price_prev;
                                        $total_differ = $price_diff_prev - $infant_price_prev;
                                        
                                        DB::table('flight_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$total_differ,
                                                    'balance'=>$supplier_balance,
                                                    'route_id'=>$flight_prev_res->flight_route_id_occupied,
                                                    'date'=>date('Y-m-d'),
                                                    'customer_id'=>$insert->customer_id,
                                                    'adult_price'=>$route_obj->flights_per_person_price,
                                                    'child_price'=>$route_obj->flights_per_child_price,
                                                    'infant_price'=>$route_obj->flights_per_infant_price,
                                                    'adult_seats_booked'=>$flight_prev_res->flights_adult_seats,
                                                    'child_seats_booked'=>$flight_prev_res->flights_child_seats,
                                                    'infant_seats_booked'=>$flight_prev_res->flights_infant_seats,
                                                    'invoice_no'=>$insert->id,
                                                    'remarks'=>'Invoice Update',
                                                  ]);
                                                  
                                        DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                    }
                            }
                        }
                    }
                
                    // 2 Loop on New 
                    if(isset($new_flight_pax)){
                        foreach($new_flight_pax as $flight_new_res){
                            $pre_el_found = false;
                            foreach($prev_flight_pax as $flight_prev_res){
                                if($flight_prev_res->flight_route_id_occupied == $flight_new_res->flight_route_id_occupied){
                                    $pre_el_found = true;
                                }
                            }
                            
                            // If element Not Found in Prev
                            if(!$pre_el_found){
                                $route_obj = DB::table('flight_rute')->where('id',$flight_new_res->flight_route_id_occupied)->first();
                                    // print_r($route_obj);
                                    // die;
                                    
                                    // Calaculate Child Prev Price Differ
                                    $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $flight_new_res->flights_child_seats;
                                    $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $flight_new_res->flights_child_seats;
                                    
                                    $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                    
                                    
                                    // Calaculate Infant Prev Price
                                    $infant_price_prev = $route_obj->flights_per_infant_price * $flight_new_res->flights_infant_seats;
                                    
                                    
                                    
                                    
                                    
                                    $supplier_data = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                    
                                    if($price_diff_prev != 0 || $infant_price_prev != 0){
                                        $supplier_balance = $supplier_data->balance - $price_diff_prev;
                                        
                                        $supplier_balance = $supplier_balance + $infant_price_prev;
                                        $total_differ = $infant_price_prev - $price_diff_prev;
                                        
                                        DB::table('flight_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$total_differ,
                                                    'balance'=>$supplier_balance,
                                                    'route_id'=>$flight_new_res->flight_route_id_occupied,
                                                    'date'=>date('Y-m-d'),
                                                    'customer_id'=>$insert->customer_id,
                                                    'adult_price'=>$route_obj->flights_per_person_price,
                                                    'child_price'=>$route_obj->flights_per_child_price,
                                                    'infant_price'=>$route_obj->flights_per_infant_price,
                                                    'adult_seats_booked'=>$flight_new_res->flights_adult_seats,
                                                    'child_seats_booked'=>$flight_new_res->flights_child_seats,
                                                    'infant_seats_booked'=>$flight_new_res->flights_infant_seats,
                                                    'invoice_no'=>$insert->id,
                                                    'remarks'=>'Invoice Update',
                                                  ]);
                                                  
                                        DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                    }
                            }
                        }
                    }
                    
                    // echo "$previous_transfer_sup id is $new_transfer_sup";
                    
                    if($previous_transfer_sup == $new_transfer_sup){
                        
                        // Calculate Previous Total
                         $transfer_prev_total = 0;
                        if(isset($prev_transfer_det) && !empty($prev_transfer_det) && $prev_transfer_det != null){
                                $transfer_data = $prev_transfer_det;
                
                                if(isset($transfer_data)){
                                        if(1 == count($transfer_data)){
                                              foreach($transfer_data  as $index => $trans_res_data){
                                     
                                                     $transfer_prev_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                            
                                             }
                                            
                                        }else{
                                          
                                          foreach($transfer_data  as $index => $trans_res_data){
                                            if($trans_res_data->transportation_pick_up_location != ''){
                                               $transfer_prev_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                              
                                            }
                                          }
                                        
                                    }
                                }
                            }
                            
                        // Calculate New Total
                        $transfer_new_total = 0;
                        if(isset($new_transfer_det) && !empty($new_transfer_det) && $new_transfer_det != null){
                                $transfer_data = $new_transfer_det;
                                // print_r($transfer_data);
                                // die;
                                if(isset($transfer_data)){
                                        if(1 == count($transfer_data)){
                                              foreach($transfer_data  as $index => $trans_res_data){
                                     
                                                     $transfer_new_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                            
                                             }
                                            
                                        }else{
                                          
                                          foreach($transfer_data  as $index => $trans_res_data){
                                            if($trans_res_data->transportation_pick_up_location != ''){
                                               $transfer_new_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                              
                                            }
                                          }
                                        
                                    }
                                }
                            }
                            
                        $price_diff = $transfer_new_total - $transfer_prev_total;
                        
                        $req->transfer_supplier_id = (int)$req->transfer_supplier_id;
                        // Update Supplier Balance
                        $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->select('id','balance')->first();
                        
                        if(isset($transfer_sup_data)){
                            $trans_sup_balance = $transfer_sup_data->balance + $price_diff;
                            if($price_diff != 0){
                                DB::table('transfer_supplier_ledger')->insert([
                                        'supplier_id'=>$req->transfer_supplier_id,
                                        'payment'=> $price_diff,
                                        'balance'=> $trans_sup_balance,
                                        'invoice_no'=>$insert->id,
                                        'remarks'=>'Invoice Updated',
                                        'date'=>date('Y-m-d'),
                                        'customer_id'=>$req->customer_id,
                                    ]);
                                    
                                    $trans_sup_balance = (float)$trans_sup_balance;
                                    
                                $update_result = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                              
                            }
                        }
                    }else{
                         // Update Previous Supplier Balance
                         $transfer_prev_total = 0;
                        if(isset($prev_transfer_det) && !empty($prev_transfer_det) && $prev_transfer_det != null){
                                $transfer_data = $prev_transfer_det;
                
                                if(isset($transfer_data)){
                                        if(1 == count($transfer_data)){
                                              foreach($transfer_data  as $index => $trans_res_data){
                                     
                                                     $transfer_prev_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                            
                                             }
                                            
                                        }else{
                                          
                                          foreach($transfer_data  as $index => $trans_res_data){
                                            if($trans_res_data->transportation_pick_up_location != ''){
                                               $transfer_prev_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                              
                                            }
                                          }
                                        
                                    }
                                    
                                    $price_diff = 0 - $transfer_prev_total;
                                    
                                    // Update Supplier Balance
                                    $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$previous_transfer_sup)->select('id','balance')->first();
                                             
                                    if(isset($transfer_sup_data)){               
                                        $trans_sup_balance = (float)$transfer_sup_data->balance + (float)$price_diff;
                                        
                                         if($price_diff != 0){
                                            DB::table('transfer_supplier_ledger')->insert([
                                                    'supplier_id'=>$previous_transfer_sup,
                                                    'payment'=> $price_diff,
                                                    'balance'=> $trans_sup_balance,
                                                    'invoice_no'=>$insert->id,
                                                    'remarks'=>'Invoice Updated',
                                                    'date'=>date('Y-m-d'),
                                                    'customer_id'=>$req->customer_id,
                                                ]);
                                            DB::table('transfer_Invoice_Supplier')->where('id',$transfer_sup_data->id)->update(['balance'=>$trans_sup_balance]);
                                         }
                                    }
                                }
                            }
                            
                        // Calculate New Total
                        $transfer_new_total = 0;
                        if(isset($new_transfer_det) && !empty($new_transfer_det) && $new_transfer_det != null){
                                $transfer_data = $new_transfer_det;
                                // print_r($transfer_data);
                                // die;
                                if(isset($transfer_data)){
                                        if(1 == count($transfer_data)){
                                              foreach($transfer_data  as $index => $trans_res_data){
                                     
                                                     $transfer_new_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                            
                                             }
                                            
                                        }else{
                                          
                                          foreach($transfer_data  as $index => $trans_res_data){
                                            if($trans_res_data->transportation_pick_up_location != ''){
                                               $transfer_new_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                              
                                            }
                                          }
                                        
                                    }
                                    
                                    // echo $transfer_new_total;
                                    // die;
                                      // Update Supplier Balance
                                     $transfer_new_total = (float)$transfer_new_total;
                                    $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$new_transfer_sup)->select('id','balance')->first();
                                    
                                    if(isset($transfer_sup_data)){                        
                                        $trans_sup_balance = (float)$transfer_sup_data->balance + $transfer_new_total;
                                        
                                        
                                        if($transfer_new_total != 0){
                                            DB::table('transfer_supplier_ledger')->insert([
                                                    'supplier_id'=>$req->transfer_supplier_id,
                                                    'payment'=> $transfer_new_total,
                                                    'balance'=> $trans_sup_balance,
                                                    'invoice_no'=>$insert->id,
                                                    'remarks'=>'Invoice Updated',
                                                    'date'=>date('Y-m-d'),
                                                    'customer_id'=>$req->customer_id,
                                                ]);
                                            DB::table('transfer_Invoice_Supplier')->where('id',$new_transfer_sup)->update(['balance'=>$trans_sup_balance]);
                                        }
                                    }
                                }
                            }
                            
                        
                    }
                    
                    // Visa Seats And Supplier Ledgers Update
                    
                    // print_r($new_visa_all_details);
                    // print_r($prev_visa_all_details);
                    // 1 Loop on New 
                    foreach($new_visa_all_details as $new_visa_res){
                        $el_found = false;
                        foreach($prev_visa_all_details as $prev_visa_res){
                            if($new_visa_res->visa_avail_id == $prev_visa_res->visa_avail_id){
                                $el_found = true;
                                
                               // 2 Update No of Seats Occupied in Visa
                                $visa_avail_data = DB::table('visa_Availability')->where('id',$new_visa_res->visa_avail_id)->first();
                                
                                    // Calculate Visa Difference
                                    $visa_diff = $new_visa_res->visa_occupied - $prev_visa_res->visa_occupied;
                                
                                $updated_seats = $visa_avail_data->visa_available - $visa_diff;
                                
                                DB::table('visa_Availability')->where('id',$new_visa_res->visa_avail_id)->update([
                                        'visa_available' => $updated_seats
                                ]);
                                
                                // 3 Update Visa Supplier Balance
                                $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->first();
                                
                                    // Calculate Visa Price Difference
                                    $prev_visa_price_total = $prev_visa_res->visa_fee_purchase * $prev_visa_res->visa_occupied;
                                    $new_visa_price_total = $new_visa_res->visa_fee_purchase * $new_visa_res->visa_occupied;
                                    
                                    $price_difference = $new_visa_price_total - $prev_visa_price_total;
                                    if($price_difference != 0){
                                
                                        $visa_supplier_payable_balance = $visa_supplier_data->payable + $price_difference;
                                        DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                                'payable' => $visa_supplier_payable_balance
                                        ]);
                                        
                                        DB::table('visa_supplier_ledger')->insert([
                                                'supplier_id' => $visa_avail_data->visa_supplier,
                                                'payment' => $price_difference,
                                                'balance' => $visa_supplier_data->balance,
                                                'payable' => $visa_supplier_payable_balance,
                                                'visa_qty' => $visa_diff,
                                                'visa_type' => $visa_avail_data->visa_type,
                                                'invoice_no' => $insert->id,
                                                'visa_avl_id' => $visa_avail_data->id,
                                                'remarks' => 'Invoice Updated',
                                                'date' => date('Y-m-d'),
                                                'customer_id' => $req->customer_id,
                                        ]);
                                        
                                        DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                                'payable' => $visa_supplier_payable_balance
                                        ]);
                                    }
                            }
                        }
                        
                        if(!$el_found){
                            // echo $new_visa_res->visa_avail_id;
                            // die;
                            // 2 Update No of Seats Occupied in Visa
                                $visa_avail_data = DB::table('visa_Availability')->where('id',$new_visa_res->visa_avail_id)->first();
                                
                                    // Calculate Visa Difference
                                    $visa_diff = $new_visa_res->visa_occupied;
                                
                                $updated_seats = $visa_avail_data->visa_available - $visa_diff;
                                
                                DB::table('visa_Availability')->where('id',$new_visa_res->visa_avail_id)->update([
                                        'visa_available' => $updated_seats
                                ]);
                                
                                // 3 Update Visa Supplier Balance
                                $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->first();
                                
                                    // Calculate Visa Price Difference
                                    
                                    $new_visa_price_total = $new_visa_res->visa_fee_purchase * $new_visa_res->visa_occupied;
                                    
                                    $price_difference = $new_visa_price_total;
                                
                                $visa_supplier_payable_balance = $visa_supplier_data->payable + $price_difference;
                                DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                        'payable' => $visa_supplier_payable_balance
                                ]);
                                
                                DB::table('visa_supplier_ledger')->insert([
                                        'supplier_id' => $visa_avail_data->visa_supplier,
                                        'payment' => $price_difference,
                                        'balance' => $visa_supplier_data->balance,
                                        'payable' => $visa_supplier_payable_balance,
                                        'visa_qty' => $visa_diff,
                                        'visa_type' => $visa_avail_data->visa_type,
                                        'invoice_no' => $insert->id,
                                        'visa_avl_id' => $visa_avail_data->id,
                                        'remarks' => 'Invoice Updated',
                                        'date' => date('Y-m-d'),
                                        'customer_id' => $req->customer_id,
                                ]);
                                
                                DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                        'payable' => $visa_supplier_payable_balance
                                ]);
                        }
                    }
                    
                    // 2 Loop on Previous 
                    foreach($prev_visa_all_details as $prev_visa_res){
                        $el_found = false;
                        foreach($new_visa_all_details as $new_visa_res){
                            if($new_visa_res->visa_avail_id == $prev_visa_res->visa_avail_id){
                                $el_found = true;
                            }
                        }
                        
                        if(!$el_found){
                            // 2 Update No of Seats Occupied in Visa
                                $visa_avail_data = DB::table('visa_Availability')->where('id',$prev_visa_res->visa_avail_id)->first();
                                
                                    // Calculate Visa Difference
                                    $visa_diff = $prev_visa_res->visa_occupied;
                                
                                $updated_seats = $visa_avail_data->visa_available + $visa_diff;
                                
                                DB::table('visa_Availability')->where('id',$prev_visa_res->visa_avail_id)->update([
                                        'visa_available' => $updated_seats
                                ]);
                                
                                // 3 Update Visa Supplier Balance
                                $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->first();
                                
                                    // Calculate Visa Price Difference
                                    
                                    $new_visa_price_total = $prev_visa_res->visa_fee_purchase * $prev_visa_res->visa_occupied;
                                    
                                    $price_difference = $new_visa_price_total;
                                
                                $visa_supplier_payable_balance = $visa_supplier_data->payable - $price_difference;
                                DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                        'payable' => $visa_supplier_payable_balance
                                ]);
                                
                                DB::table('visa_supplier_ledger')->insert([
                                        'supplier_id' => $visa_avail_data->visa_supplier,
                                        'payment' => $price_difference,
                                        'balance' => $visa_supplier_data->balance,
                                        'payable' => $visa_supplier_payable_balance,
                                        'visa_qty' => $visa_diff,
                                        'visa_type' => $visa_avail_data->visa_type,
                                        'invoice_no' => $insert->id,
                                        'visa_avl_id' => $visa_avail_data->id,
                                        'remarks' => 'Invoice Updated',
                                        'date' => date('Y-m-d'),
                                        'customer_id' => $req->customer_id,
                                ]);
                                
                                DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                        'payable' => $visa_supplier_payable_balance
                                ]); 
                        }
                    }
                 
                    DB::commit();
                    
                 
            
                 
            } catch (Throwable $e) {
                echo $e;
                DB::rollback();
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
            return response()->json(['status'=>'success','message'=>'Agent Invoice Updated Succesfully']); 
        }else{
            return response()->json(['status'=>'error','message'=>$id]); 
        }
    }
    
    public function get_flights_selected_supplier(Request $req){
        $data     = DB::table('flight_rute')->where('customer_id',$req->customer_id)->where('dep_supplier',$req->supp_Id)->get();
        return response()->json([
            'data'      => $data,
        ]); 
    }
    
    public function get_flights_all_routes(Request $req){
        $data     = DB::table('flight_rute')->where('customer_id',$req->customer_id)->get();
        return response()->json([
            'data'      => $data,
        ]); 
    }
    
    public function get_flights_selected_route(Request $req){
        $data     = DB::table('flight_rute')->where('customer_id',$req->customer_id)->where('id',$req->SR_Id)->get();
        return response()->json([
            'data'      => $data,
        ]); 
    }

    public function get_all_transfer_destinations(Request $req){
        //print_r($req->all());die();
        if(isset($req->start_date) && $req->start_date != null && $req->start_date != ''){
            $data     = DB::table('tranfer_destination')
                        ->where('customer_id',$req->customer_id)
                        ->where('available_from','<=',$req->start_date)
                        ->where('available_to','>=',$req->end_date)
                        // ->whereBetween('available_from',[$req->start_date,$req->end_date])
                        // ->whereBetween('available_to',[$req->start_date,$req->end_date])
                        ->get();
                        //print_r($data);die();
        }else{
            $data     = DB::table('tranfer_destination')->where('customer_id',$req->customer_id)->get();
        }
        return response()->json([
            'data'      => $data,
        ]); 
    }
    
    public function get_flights_occupied_seats_Old(Request $req){
        $data       = DB::table('flight_seats_occupied')->where('flight_supplier',$req->supp_Id)->where('flight_route_id',$req->route_Id)->sum('flight_route_seats_occupied');
        $dataId     = DB::table('flight_seats_occupied')
                        ->where('flight_supplier',$req->supp_Id)
                        ->where('flight_route_id',$req->route_Id)
                        ->where('booking_id',$req->booking_id)
                        ->where('type','Invoice')->select('booking_id','flight_route_id')->first();
        return response()->json([
            'data'      => $data,
            'dataId'    => $dataId,
        ]); 
    }
    
    public function get_flights_occupied_seats(Request $req){
        // dd($req->all());
        $data       = DB::table('flight_seats_occupied')
                        ->where('flight_supplier',$req->supp_Id)
                        ->where('flight_route_id',$req->route_Id)
                        ->sum('flight_route_seats_occupied');
        $dataId     = DB::table('flight_seats_occupied')
                        ->where('flight_supplier',$req->supp_Id)
                        ->where('flight_route_id',$req->route_Id)
                        ->where('booking_id',$req->booking_id)
                        ->where('type',$req->I_type)->select('booking_id','flight_route_id','flight_route_seats_occupied')->first();
        return response()->json([
            'data'      => $data,
            'dataId'    => $dataId,
        ]); 
    }

    // End Invoice
    
    public function get_TranferSuppliers(Request $req){
        $tranfer_supplier   = DB::table('transfer_Invoice_Supplier')
                                ->where('transfer_Invoice_Supplier.customer_id',$req->customer_id)
                                ->where('transfer_Invoice_Supplier.id',$req->id)
                                ->first();
                                
        $vehicle_details    = DB::table('tranfer_destination')
                                ->where('tranfer_destination.customer_id',$req->customer_id)
                                ->select('vehicle_details')
                                // ->whereJsonContains('vehicle_details',['transfer_supplier_Id'])
                                ->get();
                                
                            // ->whereJsonContains('services',['visa_tab'])    
                            
        return response()->json([
            'tranfer_supplier'  => $tranfer_supplier,
            'vehicle_details'   => $vehicle_details,
        ]);
    }
    
    public function agent_Statement_Season_Working($all_data,$request){
        // dd($request->start_date);
        $today_Date     = date('Y-m-d');
        
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
            $start_Date = Carbon::parse($season_Details->start_Date);
            $end_Date   = Carbon::parse($season_Details->end_Date);
            
            $filtered_data = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record->check_in)) {
                    return false;
                }
                
                if($record->check_in != null && $record->check_in != ''){
                    $checkIn    = Carbon::parse($record->check_in);
                    
                    // dd($checkIn,$start_Date,$end_Date);
                    return $checkIn->between($start_Date, $end_Date) || ($checkIn->lte($start_Date) && $checkIn->gte($end_Date));
                    
                    // $checkOut   = isset($record->check_out) ? Carbon::parse($record->check_out) : $checkIn;
                    // return $checkIn->between($start_Date, $end_Date) || $checkOut->between($start_Date, $end_Date) || ($checkIn->lte($start_Date) && $checkOut->gte($end_Date));
                }else{
                    return false;
                }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public function agent_booking_statement(Request $request){
        
        if(isset($request->agent_id)){
            
            if(isset($request->start_date)){
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                
                $agent_invoices_all = DB::table('add_manage_invoices')
                                        ->where('agent_Id',$request->agent_id)
                                        ->select('id','services','generate_id','confirm','all_services_quotation','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_AC')
                                        ->orderBy('created_at')
                                        ->get();
                $agent_invoices = [];
                foreach($agent_invoices_all as $inv_res){
                    $accomodation_details   = json_decode($inv_res->accomodation_details);
                    $services               = json_decode($inv_res->services);
                    $check_in               = '';
                    if(isset($accomodation_details[0]->acc_check_in) && $accomodation_details[0]->acc_check_in >= $startDate && $accomodation_details[0]->acc_check_in <= $endDate){
                        $check_in = $accomodation_details[0]->acc_check_in;
                        $inv_res->check_in = $check_in;
                        $agent_invoices[] = $inv_res;
                    }
                    
                    if(isset($services[0])){
                        if($services[0] == 'visa_tab' && $inv_res->created_at >= $startDate && $inv_res->created_at <= $endDate){
                            $inv_res->check_in = $inv_res->created_at;
                            $agent_invoices[] = $inv_res;
                        }
                    }
                    
                    if(isset($services[0])){
                        if($services[0] == 'transportation_tab'  && $inv_res->created_at >= $startDate && $inv_res->created_at <= $endDate){
                            $inv_res->check_in = $inv_res->created_at;
                            $agent_invoices[] = $inv_res;
                        }
                    }
                }
                
                $packages_booking_all = DB::table('cart_details')
                                        ->join('tours','tours.id','=','cart_details.tour_id')
                                        ->where('agent_name',$request->agent_id)
                                        ->select('cart_details.confirm','cart_details.id','cart_details.booking_id','cart_details.currency','cart_details.double_room','cart_details.triple_room','cart_details.quad_room','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.cart_total_data','cart_details.visa_change_data','cart_details.created_at'
                                        ,'tours.accomodation_details','tours.accomodation_details_more','tours.visa_type','tours.visa_fee','tours.id as package_id','tours.start_date','tours.end_date')
                                        ->orderBy('created_at')
                                        ->get();
                                        
                $packages_booking   = [];
                foreach($packages_booking_all as $inv_res){
                    $accomodation_details = json_decode($inv_res->accomodation_details);
                    
                    $check_in = '';
                    if(isset($accomodation_details[0]->acc_check_in) && $accomodation_details[0]->acc_check_in >= $startDate && $accomodation_details[0]->acc_check_in <= $endDate){
                        $check_in = $accomodation_details[0]->acc_check_in;
                        $inv_res->check_in  = $check_in;
                        $packages_booking[] = $inv_res;
                    }
                    
                }
                
                $payments_data      = DB::table('recevied_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$request->agent_id)
                                        ->whereDate('payment_date','>=', $startDate)
                                        ->whereDate('payment_date','<=', $endDate)
                                        ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
                    
                $make_payments_data = DB::table('make_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$request->agent_id)
                                        ->whereDate('payment_date','>=', $startDate)
                                        ->whereDate('payment_date','<=', $endDate)
                                        ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
            }else{
                $agent_invoices_all = DB::table('add_manage_invoices')
                                        ->where('agent_Id',$request->agent_id)
                                        ->select('id','services','generate_id','confirm','all_services_quotation','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_AC')
                                        ->orderBy('created_at')
                                        ->get();
                $agent_invoices = [];
                foreach($agent_invoices_all as $inv_res){
                    $accomodation_details = json_decode($inv_res->accomodation_details);
                    $services = json_decode($inv_res->services);
                    
                    $check_in = '';
                    if(isset($accomodation_details[0]->acc_check_in)){
                        $check_in = $accomodation_details[0]->acc_check_in;
                    }
                    
                    if(isset($services[0])){
                        if($services[0] == 'visa_tab'){
                             $check_in = $inv_res->created_at;
                        }
                    }
                    
                    
                    if(isset($services[0])){
                        if($services[0] == 'transportation_tab'){
                             $check_in = $inv_res->created_at;
                        }
                    }
                    
                    $inv_res->check_in = $check_in;
                    $agent_invoices[] = $inv_res;
                }
                
                // Fetch data from table2
                $packages_booking_all   = DB::table('cart_details')
                                            ->join('tours','tours.id','=','cart_details.tour_id')
                                            ->where('agent_name',$request->agent_id)
                                            ->select('cart_details.confirm','cart_details.id','cart_details.booking_id','cart_details.currency','cart_details.double_room','cart_details.triple_room','cart_details.quad_room','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.cart_total_data','cart_details.visa_change_data','cart_details.created_at'
                                            ,'tours.accomodation_details','tours.accomodation_details_more','tours.visa_type','tours.visa_fee','tours.id as package_id','tours.start_date','tours.end_date')
                                            ->orderBy('created_at')
                                            ->get();
                $packages_booking = [];
                foreach($packages_booking_all as $inv_res){
                    $accomodation_details = json_decode($inv_res->accomodation_details);
                    
                    $check_in = '';
                    if(isset($accomodation_details[0]->acc_check_in)){
                        $check_in = $accomodation_details[0]->acc_check_in;
                    }
                    
                    $inv_res->check_in = $check_in;
                    $packages_booking[] = $inv_res;
                }
                
                $payments_data      = DB::table('recevied_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$request->agent_id)
                                        ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
                    
                $make_payments_data = DB::table('make_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$request->agent_id)
                                        ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
            }
            
            $agent_invoices     = collect($agent_invoices);
            $packages_booking   = collect($packages_booking);
            $all_data           = $agent_invoices->concat($packages_booking)->concat($payments_data)->concat($make_payments_data)->sortBy('check_in');
            
            $Agents_detail      = DB::table('Agents_detail')->where('id',$request->agent_id)->first();
            
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
            if($request->customer_id == 4 || $request->customer_id == 54){
                // dd($all_data);
                $all_data       = $this->agent_Statement_Season_Working($all_data,$request);
                // dd($all_data);
            }
            
            return response()->json(['status'=>'success','Agents_detail'=>$all_data,'Agents_Pers_details'=>$Agents_detail,'season_Details'=>$season_Details]);
        }
        
        if(isset($request->customer_id)){
             $customer_invoices = DB::table('add_manage_invoices')
                ->where('booking_customer_id',$request->customer_id)
                ->select('id','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at')
                ->orderBy('created_at')
                ->get();
            
            // Fetch data from table2
            $packages_booking = DB::table('cart_details')
                ->where('cart_total_data->customer_id',$request->customer_id)
                ->select('confirm','id','pakage_type','tour_name','price','invoice_no','created_at')
                ->orderBy('created_at')
                ->get();
                
            $payments_data = DB::table('recevied_payments')
                // ->where('customer_id',$request->customer_id)
                ->whereJsonContains('Criteria','Customer')
                ->whereJsonContains('Content_Ids',$request->customer_id)
                ->select('id','Content_Ids','Criteria','Amount','remarks','created_at','total_received')
                ->orderBy('created_at')
                ->get();
            
            $customer_data =     DB::table('booking_customers')->where('id',$request->customer_id)
                                                                            ->first();
            $customer_payments_req = DB::table('customer_tour_pay_dt')->where('email',$customer_data->email)->where('status','approve')
                                        ->orderBy('created_at')
                                        ->get();
      
                
            $make_payments_data = DB::table('payments')
                // ->where('customer_id',$request->customer_id)
                ->whereJsonContains('Criteria','Customer')
                ->whereJsonContains('Content_Ids',$request->customer_id)
                ->select('id','Content_Ids','Criteria','Amount','remarks','created_at','total_payments')
                ->orderBy('created_at')
                ->get();
                
    
            // dd($make_payments_data);
            $all_data = $customer_invoices->concat($packages_booking)->concat($payments_data)->concat($make_payments_data)->concat($customer_payments_req)->sortBy('created_at');
            
            $customer_detail          = DB::table('booking_customers')->where('id',$request->customer_id)->first();
            // print_r($all_data);
            return response()->json(['status'=>'success','customer_detail'=>$all_data,'Customer_Pers_details'=>$customer_detail]);
         }
    }
    
    public function customer_Statement_Season_Working($all_data,$request){
        // dd($request->start_date);
        $today_Date     = date('Y-m-d');
        
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
            $start_Date = $season_Details->start_Date;
            $end_Date   = $season_Details->end_Date;
            
            $filtered_data = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                // dd($record);
                
                if (!isset($record->check_in)) {
                    return false;
                }
                
                if($record->check_in != null && $record->check_in != ''){
                    $checkIn    = Carbon::parse($record->check_in);
                    $checkOut   = isset($record->check_out) ? Carbon::parse($record->check_out) : $checkIn;
                    return $checkIn->between($start_Date, $end_Date) || $checkOut->between($start_Date, $end_Date) || ($checkIn->lte($start_Date) && $checkOut->gte($end_Date));
                }else{
                    return false;
                }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public function customer_booking_statement(Request $request){
        
        if(isset($request->booking_customer_id)){
            
            if(isset($request->start_date)){
                $startDate  = $request->start_date;
                $endDate    = $request->end_date;
                
                // dd($startDate,$endDate);
                
                $customer_invoices_all = DB::table('add_manage_invoices')
                                            ->where('booking_customer_id',$request->booking_customer_id)
                                            ->select('id','services','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_AC')
                                            ->orderBy('created_at')
                                            ->get();
            
            // dd($customer_invoices_all);
            
            $customer_invoices = [];
            foreach($customer_invoices_all as $inv_res){
                $accomodation_details   = json_decode($inv_res->accomodation_details);
                $services               = json_decode($inv_res->services);
                $check_in               = '';
                
                if(isset($accomodation_details[0]->acc_check_in) && $accomodation_details[0]->acc_check_in >= $startDate && $accomodation_details[0]->acc_check_in <= $endDate){
                    $check_in               = $accomodation_details[0]->acc_check_in;
                    $inv_res->check_in      = $check_in;
                    $customer_invoices[]    = $inv_res;
                }
                
                if(isset($services[0])){
                    if($services[0] == 'visa_tab' && $inv_res->created_at >= $startDate && $inv_res->created_at <= $endDate){
                        $inv_res->check_in = $inv_res->created_at;
                        $customer_invoices[] = $inv_res;
                    }
                }
                
                if(isset($services[0])){
                    if($services[0] == 'transportation_tab'  && $inv_res->created_at >= $startDate && $inv_res->created_at <= $endDate){
                        $inv_res->check_in = $inv_res->created_at;
                        $customer_invoices[] = $inv_res;
                    }
                }
            }
            
            // dd($customer_invoices);
            
            // Fetch data from table2
            $packages_booking_all   = DB::table('cart_details')
                                        ->join('tours','tours.id','=','cart_details.tour_id')
                                        ->whereJsonContains('cart_total_data->customer_id',"$request->booking_customer_id")
                                        ->select('cart_details.id','cart_details.currency','cart_details.double_room','cart_details.triple_room','cart_details.quad_room','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.cart_total_data','cart_details.visa_change_data','cart_details.created_at'
                                             ,'tours.accomodation_details','tours.accomodation_details_more','tours.visa_type','tours.visa_fee','tours.id as package_id','tours.start_date','tours.end_date')
                                        ->orderBy('created_at')
                                        ->get();
            
            
             $packages_booking = [];
            foreach($packages_booking_all as $inv_res){
                $accomodation_details = json_decode($inv_res->accomodation_details);
                
                $check_in = '';
                if(isset($accomodation_details[0]->acc_check_in) && $accomodation_details[0]->acc_check_in >= $startDate && $accomodation_details[0]->acc_check_in <= $endDate){
                    $check_in = $accomodation_details[0]->acc_check_in;
                    $inv_res->check_in = $check_in;
                    $packages_booking[] = $inv_res;
                }
                
            }
                
            $payments_data = DB::table('recevied_payments_details')
                ->where('Criteria','Customer')
                ->where('Content_Ids',$request->booking_customer_id)
                ->whereDate('payment_date','>=', $startDate)
                ->whereDate('payment_date','<=', $endDate)
                ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                ->orderBy('check_in')
                ->get();
                
            $make_payments_data = DB::table('make_payments_details')
                ->where('Criteria','Customer')
                ->where('Content_Ids',$request->booking_customer_id)
                ->whereDate('payment_date','>=', $startDate)
                ->whereDate('payment_date','<=', $endDate)
                ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                ->orderBy('check_in')
                ->get();
             
            }else{
                $customer_invoices_all = DB::table('add_manage_invoices')
                ->where('booking_customer_id',$request->booking_customer_id)
                ->select('id','services','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_AC')
                ->orderBy('created_at')
                ->get();
            
                
                $customer_invoices = [];
                foreach($customer_invoices_all as $inv_res){
                    $accomodation_details = json_decode($inv_res->accomodation_details);
                    $services = json_decode($inv_res->services);
                    
                    $check_in = '';
                    if(isset($accomodation_details[0]->acc_check_in)){
                        $check_in = $accomodation_details[0]->acc_check_in;
                    }
                    
                    if(isset($services[0])){
                        if($services[0] == 'visa_tab'){
                             $check_in = $inv_res->created_at;
                        }
                    }
                    
                    
                    if(isset($services[0])){
                        if($services[0] == 'transportation_tab'){
                             $check_in = $inv_res->created_at;
                        }
                    }
                    
                    $inv_res->check_in = $check_in;
                    $customer_invoices[] = $inv_res;
                }
                
                
                // Fetch data from table2
                $packages_booking_all = DB::table('cart_details')
                    ->join('tours','tours.id','=','cart_details.tour_id')
                    ->whereJsonContains('cart_total_data->customer_id',"$request->booking_customer_id")
                    ->select('cart_details.id','cart_details.currency','cart_details.double_room','cart_details.triple_room','cart_details.quad_room','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.cart_total_data','cart_details.visa_change_data','cart_details.created_at'
                     ,'tours.accomodation_details','tours.accomodation_details_more','tours.visa_type','tours.visa_fee','tours.id as package_id','tours.start_date','tours.end_date')
                    ->orderBy('created_at')
                    ->get();
                
                
                 $packages_booking = [];
                foreach($packages_booking_all as $inv_res){
                    $accomodation_details = json_decode($inv_res->accomodation_details);
                    
                    $check_in = '';
                    if(isset($accomodation_details[0]->acc_check_in)){
                        $check_in = $accomodation_details[0]->acc_check_in;
                    }
                    
                    $inv_res->check_in = $check_in;
                    $packages_booking[] = $inv_res;
                }
                    
                $payments_data = DB::table('recevied_payments_details')
                    ->where('Criteria','Customer')
                    ->where('Content_Ids',$request->booking_customer_id)
                    ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                    ->orderBy('check_in')
                    ->get();
                    
                $make_payments_data = DB::table('make_payments_details')
                    ->where('Criteria','Customer')
                    ->where('Content_Ids',$request->booking_customer_id)
                    ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                    ->orderBy('check_in')
                    ->get();
            }
            
            $customer_invoices  = collect($customer_invoices);
            $packages_booking   = collect($packages_booking);
            $all_data           = $customer_invoices->concat($packages_booking)->concat($payments_data)->concat($make_payments_data)->sortBy('check_in');
            $customer_details   = DB::table('booking_customers')->where('id',$request->booking_customer_id)->first();
            
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
            if($request->customer_id == 4 || $request->customer_id == 54){
                // dd($all_data);
                $all_data       = $this->customer_Statement_Season_Working($all_data,$request);
                // dd($all_data);
            }
            
            return response()->json(['status'=>'succedddss','booking_statement_data'=>$all_data,'customer_details'=>$customer_details,'season_Details'=>$season_Details]);
        }
    }
    
    public function agent_account_statement_datewise_Ajax(Request $request){
        if(isset($request->booking_customer_id)){
            
            if(isset($request->start_date)){
                $startDate  = $request->start_date;
                $endDate    = $request->end_date;
                
                $customer_invoices_all = DB::table('add_manage_invoices')
                                            ->where('agent_Id',$request->booking_customer_id)
                                            ->select('id','services','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_AC')
                                            ->orderBy('created_at')
                                            ->get();
                $customer_invoices = [];
                foreach($customer_invoices_all as $inv_res){
                    $accomodation_details   = json_decode($inv_res->accomodation_details);
                    $services               = json_decode($inv_res->services);
                    $check_in               = '';
                    
                    if(isset($accomodation_details[0]->acc_check_in) && $accomodation_details[0]->acc_check_in >= $startDate && $accomodation_details[0]->acc_check_in <= $endDate){
                        $check_in               = $accomodation_details[0]->acc_check_in;
                        $inv_res->check_in      = $check_in;
                        $customer_invoices[]    = $inv_res;
                    }
                    
                    if(isset($services[0])){
                        if($services[0] == 'visa_tab' && $inv_res->created_at >= $startDate && $inv_res->created_at <= $endDate){
                            $inv_res->check_in = $inv_res->created_at;
                            $customer_invoices[] = $inv_res;
                        }
                    }
                    
                    if(isset($services[0])){
                        if($services[0] == 'transportation_tab'  && $inv_res->created_at >= $startDate && $inv_res->created_at <= $endDate){
                            $inv_res->check_in = $inv_res->created_at;
                            $customer_invoices[] = $inv_res;
                        }
                    }
                }
                
                // dd($customer_invoices);
                
                // Fetch data from table2
                $packages_booking_all   = DB::table('cart_details')
                                            ->join('tours','tours.id','=','cart_details.tour_id')
                                            ->whereJsonContains('cart_total_data->agent_name',"$request->booking_customer_id")
                                            ->select('cart_details.id','cart_details.currency','cart_details.double_room','cart_details.triple_room','cart_details.quad_room','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.cart_total_data','cart_details.visa_change_data','cart_details.created_at'
                                                 ,'tours.accomodation_details','tours.accomodation_details_more','tours.visa_type','tours.visa_fee','tours.id as package_id','tours.start_date','tours.end_date')
                                            ->orderBy('created_at')
                                            ->get();
                                            
                $packages_booking = [];
                foreach($packages_booking_all as $inv_res){
                    $accomodation_details = json_decode($inv_res->accomodation_details);
                    
                    $check_in = '';
                    if(isset($accomodation_details[0]->acc_check_in) && $accomodation_details[0]->acc_check_in >= $startDate && $accomodation_details[0]->acc_check_in <= $endDate){
                        $check_in = $accomodation_details[0]->acc_check_in;
                        $inv_res->check_in = $check_in;
                        $packages_booking[] = $inv_res;
                    }
                    
                }
                    
                $payments_data      = DB::table('recevied_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$request->booking_customer_id)
                                        ->whereDate('payment_date','>=', $startDate)
                                        ->whereDate('payment_date','<=', $endDate)
                                        ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
                    
                $make_payments_data = DB::table('make_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$request->booking_customer_id)
                                        ->whereDate('payment_date','>=', $startDate)
                                        ->whereDate('payment_date','<=', $endDate)
                                        ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
            }else{
                $customer_invoices_all  = DB::table('add_manage_invoices')
                                            ->where('agent_Id',$request->booking_customer_id)
                                            ->select('id','services','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_AC')
                                            ->orderBy('created_at')
                                            ->get();
                $customer_invoices = [];
                foreach($customer_invoices_all as $inv_res){
                    $accomodation_details   = json_decode($inv_res->accomodation_details);
                    $services               = json_decode($inv_res->services);
                    
                    $check_in = '';
                    if(isset($accomodation_details[0]->acc_check_in)){
                        $check_in           = $accomodation_details[0]->acc_check_in;
                    }
                    
                    if(isset($services[0])){
                        if($services[0] == 'visa_tab'){
                            $check_in       = $inv_res->created_at;
                        }
                    }
                    
                    if(isset($services[0])){
                        if($services[0] == 'transportation_tab'){
                            $check_in       = $inv_res->created_at;
                        }
                    }
                    
                    $inv_res->check_in      = $check_in;
                    $customer_invoices[]    = $inv_res;
                }
                
                // Fetch data from table2
                $packages_booking_all   = DB::table('cart_details')
                                            ->join('tours','tours.id','=','cart_details.tour_id')
                                            ->whereJsonContains('cart_total_data->agent_name',"$request->booking_customer_id")
                                            ->select('cart_details.id','cart_details.currency','cart_details.double_room','cart_details.triple_room','cart_details.quad_room','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.cart_total_data','cart_details.visa_change_data','cart_details.created_at'
                                            ,'tours.accomodation_details','tours.accomodation_details_more','tours.visa_type','tours.visa_fee','tours.id as package_id','tours.start_date','tours.end_date')
                                            ->orderBy('created_at')
                                            ->get();
                $packages_booking       = [];
                foreach($packages_booking_all as $inv_res){
                    $accomodation_details = json_decode($inv_res->accomodation_details);
                    
                    $check_in = '';
                    if(isset($accomodation_details[0]->acc_check_in)){
                        $check_in = $accomodation_details[0]->acc_check_in;
                    }
                    
                    $inv_res->check_in = $check_in;
                    $packages_booking[] = $inv_res;
                }
                    
                $payments_data      = DB::table('recevied_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$request->booking_customer_id)
                                        ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
                                    
                $make_payments_data = DB::table('make_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$request->booking_customer_id)
                                        ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
            }
            
            $customer_invoices  = collect($customer_invoices);
            $packages_booking   = collect($packages_booking);
            $all_data           = $customer_invoices->concat($packages_booking)->concat($payments_data)->concat($make_payments_data)->sortBy('check_in');
            $Agents_detail      = DB::table('Agents_detail')->where('id',$request->booking_customer_id)->first();
            
            return response()->json(['status'=>'succedddss','booking_statement_data'=>$all_data,'customer_details'=>$Agents_detail]);
        }
    }
    
    public function agent_ledeger(Request $req){
        $Agents_detail          = DB::table('Agents_detail')->where('id',$req->agent_id)->first();
        $Agents_ledger_data     = DB::table('agents_ledgers_new')
                                    // ->leftJoin('add_manage_invoices','agents_ledgers_new.invoice_no','add_manage_invoices.id')
                                    ->leftJoin('add_manage_invoices as add_manage_invoices', function ($join) {
                                        $join->on('agents_ledgers_new.invoice_no', '=', 'add_manage_invoices.id');
                                    })
                                    // ->leftJoin('tours_bookings','agents_ledgers_new.package_invoice_no','tours_bookings.invoice_no')
                                    ->leftJoin('tours_bookings as tours_bookings', function ($join) {
                                        $join->on('agents_ledgers_new.package_invoice_no', '=', 'tours_bookings.invoice_no');
                                    })
                                    ->leftJoin('tours_bookings as tours_bookings_1', function ($join) {
                                        $join->on('agents_ledgers_new.activity_invoice_no', '=', 'tours_bookings_1.invoice_no');
                                    })
                                    // ->leftJoin('cart_details','agents_ledgers_new.package_invoice_no','cart_details.invoice_no')
                                    ->leftJoin('cart_details as cart_details', function ($join) {
                                        $join->on('agents_ledgers_new.package_invoice_no', '=', 'cart_details.invoice_no');
                                    })
                                    ->leftJoin('cart_details as cart_details_1', function ($join) {
                                        $join->on('agents_ledgers_new.activity_invoice_no', '=', 'cart_details_1.invoice_no');
                                    })
                                    ->where('agents_ledgers_new.agent_id',$req->agent_id)
                                    ->select('agents_ledgers_new.*','agents_ledgers_new.id as ledger_id','add_manage_invoices.f_name','add_manage_invoices.middle_name','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.transportation_details','add_manage_invoices.transportation_details_more','add_manage_invoices.more_visa_details','add_manage_invoices.flights_details','add_manage_invoices.return_flights_details','add_manage_invoices.visa_type','add_manage_invoices.visa_Pax','add_manage_invoices.more_visa_details','add_manage_invoices.services','tours_bookings.passenger_name','cart_details.tour_name'
                                            ,'cart_details_1.tour_name as tour_name1','tours_bookings_1.passenger_name as passenger_name1')
                                    ->orderBy('agents_ledgers_new.id','asc')
                                    ->get();
        return response()->json(['status'=>'success','Agents_detail'=>$Agents_ledger_data,'Agents_Pers_details'=>$Agents_detail]);
    }
    
    public function agent_ledeger1(Request $req){
        $Agents_detail  = DB::table('Agents_detail')->where('id',$req->agent_id)->first();
        $Agents_ledger_data  = DB::table('agents_ledgers_new')
                               
                                ->where('agent_id',$req->agent_id)
                                
                                ->get();
        
        // print_r($Agents_ledger_data);
        // die;
        return response()->json(['status'=>'success','Agents_detail'=>$Agents_ledger_data,'Agents_Pers_details'=>$Agents_detail]);
        // return view('template/frontend/userdashboard/pages/manage_Office/Agents/create_Agents',compact('Agents_detail'));  
    }
    
    public function customer_ledger(Request $req){
        // dd($req->customer_id);
        
        $customer_detail        = DB::table('booking_customers')->where('id',$req->customer_id)->first();
        // $customer_ledger_data   = DB::table('customer_ledger')
        //                             ->leftJoin('add_manage_invoices','customer_ledger.invoice_no','add_manage_invoices.id')
        //                             ->leftJoin('tours_bookings','customer_ledger.package_invoice_no','tours_bookings.invoice_no')
        //                             ->leftJoin('cart_details','customer_ledger.package_invoice_no','cart_details.invoice_no')
        //                             ->where('booking_customer',$req->customer_id)
        //                             ->select('customer_ledger.*','add_manage_invoices.f_name','add_manage_invoices.middle_name','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.transportation_details','add_manage_invoices.transportation_details_more','add_manage_invoices.more_visa_details','add_manage_invoices.flights_details','add_manage_invoices.return_flights_details','add_manage_invoices.visa_type','add_manage_invoices.visa_Pax','add_manage_invoices.more_visa_details','add_manage_invoices.services','tours_bookings.passenger_name','cart_details.tour_name')
        //                             ->get();
                                    
        $customer_ledger_data   = DB::table('customer_ledger')
                                    ->leftJoin('add_manage_invoices as add_manage_invoices', function ($join) {
                                        $join->on('customer_ledger.invoice_no', '=', 'add_manage_invoices.id');
                                    })
                                    ->leftJoin('tours_bookings as tours_bookings', function ($join) {
                                        $join->on('customer_ledger.package_invoice_no', '=', 'tours_bookings.invoice_no');
                                    })
                                    ->leftJoin('tours_bookings as tours_bookings_1', function ($join) {
                                        $join->on('customer_ledger.activity_invoice_no', '=', 'tours_bookings_1.invoice_no');
                                    })
                                    ->leftJoin('cart_details as cart_details', function ($join) {
                                        $join->on('customer_ledger.package_invoice_no', '=', 'cart_details.invoice_no');
                                    })
                                    ->leftJoin('cart_details as cart_details_1', function ($join) {
                                        $join->on('customer_ledger.activity_invoice_no', '=', 'cart_details_1.invoice_no');
                                    })
                                    ->where('booking_customer',$req->customer_id)
                                    ->select('customer_ledger.*','add_manage_invoices.f_name','add_manage_invoices.middle_name','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.transportation_details','add_manage_invoices.transportation_details_more','add_manage_invoices.more_visa_details','add_manage_invoices.flights_details','add_manage_invoices.return_flights_details','add_manage_invoices.visa_type','add_manage_invoices.visa_Pax','add_manage_invoices.more_visa_details','add_manage_invoices.services','tours_bookings.passenger_name','cart_details.tour_name'
                                            ,'cart_details_1.tour_name as tour_name1','tours_bookings_1.passenger_name as passenger_name1')
                                    ->orderBy('customer_ledger.id','asc')
                                    ->get();
                                    
        if($req->customer_id == '4'){
            // dd('if');
            $customer_detail        = DB::table('booking_customers')->where('id',$req->id)->first();
            $customer_ledger_data   = DB::table('customer_ledger')
                                        ->leftJoin('add_manage_invoices as add_manage_invoices', function ($join) {
                                            $join->on('customer_ledger.invoice_no', '=', 'add_manage_invoices.id');
                                        })
                                        ->leftJoin('tours_bookings as tours_bookings', function ($join) {
                                            $join->on('customer_ledger.package_invoice_no', '=', 'tours_bookings.invoice_no');
                                        })
                                        ->leftJoin('tours_bookings as tours_bookings_1', function ($join) {
                                            $join->on('customer_ledger.activity_invoice_no', '=', 'tours_bookings_1.invoice_no');
                                        })
                                        ->leftJoin('cart_details as cart_details', function ($join) {
                                            $join->on('customer_ledger.package_invoice_no', '=', 'cart_details.invoice_no');
                                        })
                                        ->leftJoin('cart_details as cart_details_1', function ($join) {
                                            $join->on('customer_ledger.activity_invoice_no', '=', 'cart_details_1.invoice_no');
                                        })
                                        ->where('booking_customer',$req->id)
                                        ->select('customer_ledger.*','add_manage_invoices.f_name','add_manage_invoices.middle_name','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.transportation_details','add_manage_invoices.transportation_details_more','add_manage_invoices.more_visa_details','add_manage_invoices.flights_details','add_manage_invoices.return_flights_details','add_manage_invoices.visa_type','add_manage_invoices.visa_Pax','add_manage_invoices.more_visa_details','add_manage_invoices.services','tours_bookings.passenger_name','cart_details.tour_name'
                                                ,'cart_details_1.tour_name as tour_name1','tours_bookings_1.passenger_name as passenger_name1')
                                        ->orderBy('customer_ledger.id','asc')
                                        ->get();
                                        // dd($customer_ledger_data);
        }
        
        return response()->json(['status'=>'success','customer_detail'=>$customer_ledger_data,'customer_Pers_details'=>$customer_detail]);
    }
    
    public function add_Invoices_TranSupp(Request $req){
        // Data
            $insert = new addManageInvoice();
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->services                = $req->services;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
            $insert->city_Count              = $req->city_Count;
            
            // $insert->data_of_birth                  = $req->data_of_birth;
            // $insert->passport_no                   = $req->passport_no;
            // $insert->passport_expiry_date        = $req->passport_expiry_date;
            
            
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            // $insert->quotation_prepared      = $req->quotation_prepared;
            // $insert->quotation_valid_date    = $req->quotation_valid_date;
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            $insert->passengerDetailAdults   = $req->passengerDetailAdults;
            $insert->passengerDetailChilds   = $req->passengerDetailChilds;
            $insert->passengerDetailInfant   = $req->passengerDetailInfant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $insert->generate_id                    = $generate_id;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            $insert->flights_details                = $req->flights_details;
            $insert->flights_details_more           = $req->more_flights_details;
            $insert->accomodation_details           = $req->accomodation_details;
            $insert->accomodation_details_more      = $req->more_accomodation_details;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            $insert->more_visa_details              = $req->more_visa_details;
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_image                     = $req->visa_image;
            
            $insert->without_markup_total_price_visa    = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa            = $req->markup_total_price_visa;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            $insert->gallery_images                 = $req->gallery_images;
            $insert->start_date                     = $req->start_date;
            $insert->end_date=$req->end_date;
            $insert->time_duration=$req->time_duration;
            $insert->tour_location=$req->tour_location;
            $insert->whats_included=$req->whats_included;
            $insert->whats_excluded=$req->whats_excluded;
            $insert->currency_symbol=$req->currency_symbol;
            $insert->tour_publish=$req->defalut_state;
            $insert->tour_author=$req->tour_author;
            $insert->tour_feature=$req->tour_feature;
            $insert->defalut_state=$req->defalut_state;
            $insert->payment_gateways=$req->payment_gateways;
            $insert->payment_modes=$req->payment_modes;
            $insert->markup_details=$req->markup_details;
            $insert->more_markup_details=$req->more_markup_details;
            $insert->tour_featured_image=$req->tour_featured_image;
            $insert->tour_banner_image=$req->tour_banner_image;
            $insert->Itinerary_details=$req->Itinerary_details;
            $insert->tour_itinerary_details_1=$req->tour_itinerary_details_1;
            $insert->tour_extra_price=$req->tour_extra_price;
            $insert->tour_extra_price_1=$req->tour_extra_price_1;
            $insert->tour_faq=$req->tour_faq;
            $insert->tour_faq_1=$req->tour_faq_1;
            
            $insert->payment_messag=$req->checkout_message;
            
            $insert->total_Cost_Price_All=$req->total_Cost_Price_All;
            $insert->total_Sale_Price_All=$req->total_Sale_Price_All;
            
            $insert->option_date=$req->option_date;
            $insert->reservation_number=$req->reservation_number;
            $insert->hotel_reservation_number=$req->hotel_reservation_number;
            
            $insert->total_sale_price_all_Services=$req->total_sale_price_all_Services;
            $insert->total_cost_price_all_Services=$req->total_cost_price_all_Services;
            
            $insert->transfer_supplier      = $req->transfer_supplier;
            $insert->transfer_supplier_id   = $req->transfer_supplier_id;
            
            
            DB::beginTransaction();
            try {
                $insert->save();
                
                $invoice_id = $insert->id;
                $accomodation = json_decode($req->accomodation_details);
                $more_accomodation_details = json_decode($req->more_accomodation_details);
                
                if(isset($accomodation)){
                     foreach($accomodation as $accomodation_res){
                         
                         if(isset($accomodation_res->hotelRoom_type_idM)){
                             if($accomodation_res->hotelRoom_type_idM != null && $accomodation_res->hotelRoom_type_idM != ''){
                        //   echo "Enter here ";
                                $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                                
                                if($room_data){
                                    $rooms_more_data = json_decode($room_data->more_room_type_details);
                                    // print_r($rooms_more_data);
                                    
                                    $total_booked = 0;
                                    $booking_main_data = [];
                                    foreach($rooms_more_data as $key => $room_more_res){
                                        // echo "The room gen ".$room_more_res->room_gen_id." and request is ".$request->generate_id;
                                        if($room_more_res->room_gen_id == $accomodation_res->hotelRoom_type_idM){
                                            // print_r($room_more_res);
                                            $total_booked = $room_more_res->more_quantity_booked + $accomodation_res->acc_qty;
                                            
                                            
                                            if(!empty($room_more_res->more_booking_details) && $room_more_res->more_booking_details !== null){
                                                $booking_main_data = json_decode($room_more_res->more_booking_details);
                                            }
                                            
                                             $booking_details = ['booking_from'=>'Invoices',
                                             'quantity'=>$accomodation_res->acc_qty,
                                             'booking_id'=>$invoice_id,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$accomodation_res->acc_check_in,
                                             'check_out'=>$accomodation_res->acc_check_out,
                                             ];
                                            array_push($booking_main_data,$booking_details);
                                            $booking_main_data = json_encode($booking_main_data);
                                            
                                            $rooms_more_data[$key]->more_quantity_booked = $total_booked;
                                            $rooms_more_data[$key]->more_booking_details = $booking_main_data;
                                            // echo "total booked rooms is $total_booked";
                                        }
                                    }
                                    
                                    // print_r($rooms_more_data);
                                    
                                    
                                    // die;
                             
                                    DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->update(['more_room_type_details'=>$rooms_more_data]);
                                }
                        
                        
                        
                        
                            }else{
                                    // die;
                                    $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                                    
                                    if($room_data){
                                        
                                        
                                        $total_booked = $room_data->booked + $accomodation_res->acc_qty;
                                        
                                        $booking_main_data = [];
                                        if(!empty($room_data->booking_details) && $room_data->booking_details !== null){
                                            $booking_main_data = json_decode($room_data->booking_details);
                                        }
                                        
                                       
                                        
                                          $booking_details = ['booking_from'=>'Invoices',
                                                 'quantity'=>$accomodation_res->acc_qty,
                                                 'booking_id'=>$invoice_id,
                                                 'date'=>date('Y-m-d'),
                                                 'check_in'=>$accomodation_res->acc_check_in,
                                                 'check_out'=>$accomodation_res->acc_check_out,
                                                 ];
                                                 
                                        array_push($booking_main_data,$booking_details);
                                        // print_r($booking_main_data);
                                        $booking_main_data = json_encode($booking_main_data);
                                        DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->update(['booked'=>$total_booked,'booking_details'=>$booking_main_data]);
                                
                                    }
                                }
                         }
                      
                     }
                    
                }
                
                if(isset($more_accomodation_details)){
                    // print_r($more_accomodation_details);
                    
                     foreach($more_accomodation_details as $accomodation_res){
                         if(isset($accomodation_res->more_hotelRoom_type_idM)){
                             if($accomodation_res->more_hotelRoom_type_idM != null && $accomodation_res->more_hotelRoom_type_idM != ''){
                            //   echo "Enter here ";
                            $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                            
                                if($room_data){
                                    $rooms_more_data = json_decode($room_data->more_room_type_details);
                                    // print_r($rooms_more_data);
                                    
                                    $total_booked = 0;
                                    $booking_main_data = [];
                                    foreach($rooms_more_data as $key => $room_more_res){
                                        // echo "The room gen ".$room_more_res->room_gen_id." and request is ".$request->generate_id;
                                        if($room_more_res->room_gen_id == $accomodation_res->more_hotelRoom_type_idM){
                                            // print_r($room_more_res);
                                            $total_booked = $room_more_res->more_quantity_booked + $accomodation_res->more_acc_qty;
                                            
                                            
                                            if(!empty($room_more_res->more_booking_details) && $room_more_res->more_booking_details !== null){
                                                $booking_main_data = json_decode($room_more_res->more_booking_details);
                                            }
                                            
                                             $booking_details = ['booking_from'=>'Invoices',
                                             'quantity'=>$accomodation_res->more_acc_qty,
                                             'booking_id'=>$invoice_id,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$accomodation_res->more_acc_check_in,
                                             'check_out'=>$accomodation_res->more_acc_check_out,
                                             ];
                                            array_push($booking_main_data,$booking_details);
                                            $booking_main_data = json_encode($booking_main_data);
                                            
                                            $rooms_more_data[$key]->more_quantity_booked = $total_booked;
                                            $rooms_more_data[$key]->more_booking_details = $booking_main_data;
                                            // echo "total booked rooms is $total_booked";
                                        }
                                    }
                                    
                                    // echo "More";
                                    // print_r($rooms_more_data);
                                    
                                    
                                    // die;
                             
                                    DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->update(['more_room_type_details'=>$rooms_more_data]);
                                
                                }
                            
                             }else{
                                    // die;
                                    $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                                    
                                    if($room_data){
                                        
                                   
                                        $total_booked = $room_data->booked + $accomodation_res->more_acc_qty;
                                        
                                        $booking_main_data = [];
                                        if(!empty($room_data->booking_details) && $room_data->booking_details !== null){
                                            $booking_main_data = json_decode($room_data->booking_details);
                                        }
                                        
                                       
                                        
                                          $booking_details = ['booking_from'=>'Invoices',
                                                 'quantity'=>$accomodation_res->more_acc_qty,
                                                 'booking_id'=>$invoice_id,
                                                 'date'=>date('Y-m-d'),
                                                 'check_in'=>$accomodation_res->more_acc_check_in,
                                                 'check_out'=>$accomodation_res->more_acc_check_out,
                                                 ];
                                                 
                                        array_push($booking_main_data,$booking_details);
                                        
                                        // print_r($booking_main_data);
                                        $booking_main_data = json_encode($booking_main_data);
                                        DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->update(['booked'=>$total_booked,'booking_details'=>$booking_main_data]);
                                    }
                                }
                         }
                          
                     }
                
                }
                
                DB::commit();
                return response()->json(['status'=>'success','message'=>'Agent Invoice added Succesfully']); 
            
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
            }
    }
    
    public function add_more_passenger_Invoice(Request $req){
        if($req->request_form == 'leadPassenger'){
            try {
                $insert = DB::table('add_manage_invoices')->where('id',$req->invoice_Id_Input)->update([
                    'lead_title'            => $req->lead_title,
                    'lead_fname'            => $req->lead_fname,
                    'f_name'                => $req->lead_fname,
                    'lead_lname'            => $req->lead_lname,
                    'middle_name'           => $req->lead_lname,
                    'lead_gender'           => $req->lead_gender,
                    'gender'                => $req->lead_gender,
                    'lead_dob'              => $req->lead_dob,
                    'email'                 => $req->lead_email,
                    'lead_nationality'      => $req->lead_nationality,
                    'contact_landline'      => $req->contact_landline,
                    'lead_passport_number'  => $req->lead_passport_number,
                    'lead_passport_expiry'  => $req->lead_passport_expiry,
                    'lead_visa_Type'        => $req->lead_visa_Type,
                    'passport_Image'        => $req->passport_Image,
                ]);
                
                if($req->quotation_id != null && $req->quotation_id != '' && $req->quotation_id != 'null'){
                    $insert = DB::table('addManageQuotationPackage')->where('id',$req->quotation_id)->update([
                        'lead_title'            => $req->lead_title,
                        'lead_fname'            => $req->lead_fname,
                        'f_name'                => $req->lead_fname,
                        'lead_lname'            => $req->lead_lname,
                        'middle_name'           => $req->lead_lname,
                        'lead_gender'           => $req->lead_gender,
                        'gender'                => $req->lead_gender,
                        'lead_dob'              => $req->lead_dob,
                        'email'                 => $req->lead_email,
                        'lead_nationality'      => $req->lead_nationality,
                        'contact_landline'      => $req->contact_landline,
                        'lead_passport_number'  => $req->lead_passport_number,
                        'lead_passport_expiry'  => $req->lead_passport_expiry,
                        'lead_visa_Type'        => $req->lead_visa_Type,
                        'passport_Image'        => $req->passport_Image,
                    ]);
                }
                
                return response()->json(['status'=>'success','message'=>'Lead Passenger detail Updated Successfully']);
            } catch (Throwable $e) {
                echo $e;
            }
        }else if($req->request_form == 'otherPassenger'){
            $request_data       = json_decode($req->request_data);
            $others_passengers  = DB::table('add_manage_invoices')->where('id',$request_data->invoice_id)->select('more_Passenger_Data')->first();
            $others_passengers  = $others_passengers->more_Passenger_Data;
            $passenger_data =   [
                "more_p_fname"              => $request_data->passengerName,
                "more_p_lname"              => $request_data->lname,
                "more_p_gender"             => $request_data->gender,
                "more_p_dob"                => $request_data->date_of_birth,
                "more_p_nationality"        => $request_data->country,
                "more_p_passport_number"    => $request_data->passport_lead,
                "more_p_passport_expiry"    => $request_data->passport_exp_lead,
                "more_p_passport"           => '',
                "more_p_image"              => '',
                "more_p_visa_Type"          => '',
            ];
            
            if(!is_null($others_passengers) && $others_passengers != ''){
                $others_passengers = json_decode($others_passengers);
            }else{
                $others_passengers = [];
            }
            
            array_push($others_passengers,$passenger_data);
            $OP_count = count($others_passengers);
            $others_passengers = json_encode($others_passengers);
            
            try {
                $update = DB::table('add_manage_invoices')->where('id',$request_data->invoice_id)->update([
                    'more_Passenger_Data'   => $others_passengers,
                    'count_P_Input'         => $OP_count,
                ]);
                
                if($req->quotation_id != null && $req->quotation_id != '' && $req->quotation_id != 'null'){
                    $update = DB::table('addManageQuotationPackage')->where('id',$request_data->quotation_id)->update([
                        'more_Passenger_Data'   => $others_passengers,
                        'count_P_Input'         => $OP_count,
                    ]); 
                }
                
                return response()->json(['status'=>'success','message'=>'More Passengers Invoice added Successfully']);
            } catch (Throwable $e) {
                echo $e;
            }
        }else if($req->request_form == 'updatePassenger'){
            $request_data = json_decode($req->request_data);
            $others_passengers = DB::table('add_manage_invoices')->where('id',$request_data->invoice_id)->select('more_Passenger_Data')->first();
            $others_passengers = $others_passengers->more_Passenger_Data;
            $passenger_data = [
                "more_p_fname"              => $request_data->passengerName,
                "more_p_lname"              => $request_data->lname,
                "more_p_gender"             => $request_data->gender,
                "more_p_dob"                => $request_data->date_of_birth,
                "more_p_nationality"        => $request_data->country,
                "more_p_passport_number"    => $request_data->passport_lead,
                "more_p_passport_expiry"    => $request_data->passport_exp_lead,
                "more_p_passport"           => '',
                "more_p_image"              => '',
                "more_p_visa_Type"          => '',
            ];
            
            if(!is_null($others_passengers) && $others_passengers != ''){
                $others_passengers = json_decode($others_passengers);
            }else{
                $others_passengers = [];
            }
           
            $others_passengers[$request_data->index - 2] = $passenger_data;
            $others_passengers = json_encode($others_passengers);
            
            try {
                $update = DB::table('add_manage_invoices')->where('id',$request_data->invoice_id)->update([
                    'more_Passenger_Data' => $others_passengers,
                ]);
                
                if($req->quotation_id != null && $req->quotation_id != '' && $req->quotation_id != 'null'){
                    $update = DB::table('addManageQuotationPackage')->where('id',$request_data->quotation_id)->update([
                        'more_Passenger_Data' => $others_passengers,
                    ]); 
                }
                
                return response()->json(['status'=>'success','message'=>'More Passengers of Invoice Updated Successfully']);
            } catch (Throwable $e) {
                echo $e;
            }
        }
    }
    
    public function get_single_Invoice(Request $req){
        $data1                          = addManageInvoice::where('customer_id',$req->customer_id)->where('id',$req->id)->first();
        $invoice_Agent_Payment_data     = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->get();
        $outStanding_Invoice_Agent      = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->sum('amount_Paid');
        $total_Invoice_Agent            = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->first();
        return response()->json([
            'data1'                         => $data1,
            'invoice_Agent_Payment_data'    => $invoice_Agent_Payment_data,
            'outStanding_Invoice_Agent'     => $outStanding_Invoice_Agent,
            'total_Invoice_Agent'           => $total_Invoice_Agent,
        ]);
    }
    
    public function get_single_Quotation(Request $req){
        $customer_id    = DB::table('customer_subcriptions')->where('Auth_key',$req->token)->select('id')->first();
        $data1          = DB::table('addManageQuotationPackage')->where('customer_id',$customer_id->id)->where('id',$req->id)->first();
        return response()->json([
            'data1' => $data1,
        ]);
    }
    
    public function add_more_passenger_Quotation(Request $req){
        if($req->request_form == 'leadPassenger'){
            try {
                // dd($req->quotation_status);
                
                $insert = DB::table('addManageQuotationPackage')->where('id',$req->invoice_Id_Input)->update([
                    'lead_title'            => $req->lead_title,
                    'lead_fname'            => $req->lead_fname,
                    'f_name'                => $req->lead_fname,
                    'lead_lname'            => $req->lead_lname,
                    'middle_name'           => $req->lead_lname,
                    'lead_gender'           => $req->lead_gender,
                    'gender'                => $req->lead_gender,
                    'lead_dob'              => $req->lead_dob,
                    'email'                 => $req->lead_email,
                    'lead_nationality'      => $req->lead_nationality,
                    'contact_landline'      => $req->contact_landline,
                    'lead_passport_number'  => $req->lead_passport_number,
                    'lead_passport_expiry'  => $req->lead_passport_expiry,
                    'lead_visa_Type'        => $req->lead_visa_Type,
                    'passport_Image'        => $req->passport_Image,
                ]);
                
                if($req->quotation_status == 1){
                    $insert = DB::table('add_manage_invoices')->where('quotation_id',$req->invoice_Id_Input)->update([
                        'lead_title'            => $req->lead_title,
                        'lead_fname'            => $req->lead_fname,
                        'f_name'                => $req->lead_fname,
                        'lead_lname'            => $req->lead_lname,
                        'middle_name'           => $req->lead_lname,
                        'lead_gender'           => $req->lead_gender,
                        'gender'                => $req->lead_gender,
                        'lead_dob'              => $req->lead_dob,
                        'email'                 => $req->lead_email,
                        'lead_nationality'      => $req->lead_nationality,
                        'contact_landline'      => $req->contact_landline,
                        'lead_passport_number'  => $req->lead_passport_number,
                        'lead_passport_expiry'  => $req->lead_passport_expiry,
                        'lead_visa_Type'        => $req->lead_visa_Type,
                        'passport_Image'        => $req->passport_Image,
                    ]);
                }
                
                return response()->json(['status'=>'success','message'=>'Lead Passenger detail Updated Successfully']);
            } catch (Throwable $e) {
                echo $e;
            }
        }else if($req->request_form == 'otherPassenger'){
            $request_data       = json_decode($req->request_data);
            $others_passengers  = DB::table('addManageQuotationPackage')->where('id',$request_data->invoice_id)->select('more_Passenger_Data')->first();
            $others_passengers  = $others_passengers->more_Passenger_Data;
            $passenger_data =   [
                "more_p_fname"              => $request_data->passengerName,
                "more_p_lname"              => $request_data->lname,
                "more_p_gender"             => $request_data->gender,
                "more_p_dob"                => $request_data->date_of_birth,
                "more_p_nationality"        => $request_data->country,
                "more_p_passport_number"    => $request_data->passport_lead,
                "more_p_passport_expiry"    => $request_data->passport_exp_lead,
                "more_p_passport"           => '',
                "more_p_image"              => '',
                "more_p_visa_Type"          => '',
            ];
            
            if(!is_null($others_passengers) && $others_passengers != ''){
                $others_passengers = json_decode($others_passengers);
            }else{
                $others_passengers = [];
            }
            
            array_push($others_passengers,$passenger_data);
            $OP_count = count($others_passengers);
            $others_passengers = json_encode($others_passengers);
            
            try {
                $update = DB::table('addManageQuotationPackage')->where('id',$request_data->invoice_id)->update([
                    'more_Passenger_Data'   => $others_passengers,
                    'count_P_Input'         => $OP_count,
                ]);
                
                if($req->quotation_status != null){
                    $update = DB::table('add_manage_invoices')->where('quotation_id',$request_data->invoice_id)->update([
                        'more_Passenger_Data'   => $others_passengers,
                        'count_P_Input'         => $OP_count,
                    ]);
                }
                
                return response()->json(['status'=>'success','message'=>'More Passengers Quotation added Successfully']);
            } catch (Throwable $e) {
                echo $e;
            }
        }else if($req->request_form == 'updatePassenger'){
            $request_data = json_decode($req->request_data);
            $others_passengers = DB::table('addManageQuotationPackage')->where('id',$request_data->invoice_id)->select('more_Passenger_Data')->first();
            $others_passengers = $others_passengers->more_Passenger_Data;
            $passenger_data = [
                "more_p_fname"              => $request_data->passengerName,
                "more_p_lname"              => $request_data->lname,
                "more_p_gender"             => $request_data->gender,
                "more_p_dob"                => $request_data->date_of_birth,
                "more_p_nationality"        => $request_data->country,
                "more_p_passport_number"    => $request_data->passport_lead,
                "more_p_passport_expiry"    => $request_data->passport_exp_lead,
                "more_p_passport"           => '',
                "more_p_image"              => '',
                "more_p_visa_Type"          => '',
            ];
            
            if(!is_null($others_passengers) && $others_passengers != ''){
                $others_passengers = json_decode($others_passengers);
            }else{
                $others_passengers = [];
            }
           
            $others_passengers[$request_data->index - 2] = $passenger_data;
            $others_passengers = json_encode($others_passengers);
            
            try {
                $update = DB::table('addManageQuotationPackage')->where('id',$request_data->invoice_id)->update([
                    'more_Passenger_Data' => $others_passengers,
                ]);
                
                if($req->quotation_status != null){
                    $update = DB::table('add_manage_invoices')->where('quotation_id',$request_data->invoice_id)->update([
                        'more_Passenger_Data' => $others_passengers,
                    ]);
                }
                
                return response()->json(['status'=>'success','message'=>'More Passengers of Quotation Updated Successfully']);
            } catch (Throwable $e) {
                echo $e;
            }
        }
    }
    
    public function create_customer(Request $req){
        $userData = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
            $customer_detail    = DB::table('booking_customers')->where('customer_id',$req->customer_id)->where('status', '!=', 0)->get();
            $countires          = DB::table('countries')->select('id','name','phonecode')->get();
            return response()->json(['status'=>'success','customer_detail'=>$customer_detail,'countires'=>$countires]);
        }else{
            return response()->json(['status'=>'error','customer_detail'=>'']);
        }
      
        // return view('template/frontend/userdashboard/pages/manage_Office/Agents/create_Agents',compact('Agents_detail'));  
    }
    
    public function edit_customer(Request $req){
        $userData = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
            $customer_detail    = DB::table('booking_customers')->where('customer_id',$req->customer_id)->where('id',$req->id)->first();
            $countires          = DB::table('countries')->select('id','name','phonecode')->get();
            return response()->json(['status'=>'success','customer_detail'=>$customer_detail,'countires'=>$countires]);
        }else{
            return response()->json(['status'=>'error','customer_detail'=>'']);
        }
    }
    
    public function edit_customer_submit(Request $request){
        $request_data       = json_decode($request->request_data);
        $country            = json_decode($request_data->country);
        // $customer_detail    = DB::table('booking_customers')->where('customer_id',$request->customer_id)->where('id',$request_data->cust_name_id)->first();
        $customer_detail    = booking_customers::find($request_data->cust_name_id);
        if($customer_detail){
            $customer_detail->name              = $request_data->cust_name;
            $customer_detail->opening_balance   = $request_data->opening_balance;
            $customer_detail->balance           = $request_data->opening_balance;
            $customer_detail->email             = $request_data->email;
            $customer_detail->phone             = $request_data->phone;
            $customer_detail->whatsapp          = $request_data->whatsapp;
            $customer_detail->gender            = $request_data->gender;
            $customer_detail->country           = $country->name;
            $customer_detail->city              = $request_data->city ?? '';
            $customer_detail->post_code         = $request_data->post_code;
            $customer_detail->update();
            return response()->json(['status'=>'success','Success'=>'Customer Updated Successful!']);
        }
        else{
            return response()->json(['status'=>'error','customer_detail'=>$customer_detail,'Error'=>'Customer Not Updated!']);
        }
    }
    
    public function submit_customer(Request $req){
        $userData           = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
            $request_data   = json_decode($req->request_data);
            $country        = json_decode($request_data->country);
            $customer_exist = DB::table('booking_customers')->where('customer_id',$req->customer_id)->where('email',$request_data->email)->first();
            // dd($customer_exist);
            if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                $customer_detail    = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
                $countires          = DB::table('countries')->select('id','name')->get();
                return response()->json(['status'=>'error','message'=>'customer already exist','customer_detail'=>$customer_detail,'countires'=>$countires]);
            }else{
                // $customer_detail  = DB::table('booking_customers')->insert([
                //     'name'              => $request_data->cust_name,
                //     'opening_balance'   => $request_data->opening_balance,
                //     'balance'           => $request_data->opening_balance,
                //     'email'             => $request_data->email,
                //     'phone'             => $request_data->phone,
                //     'whatsapp'          => $request_data->whatsapp,
                //     'gender'            => $request_data->gender,
                //     'country'           => $country->name,
                //     'city'              => $request_data->city,
                //     'post_code'         => $request_data->post_code,
                //     'customer_id'       => $userData->id,
                // ]);
                
                // dd($request_data);
                $customer_detail                    = new booking_customers();
                
                if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                    $customer_detail->SU_id               = $req->SU_id;
                }
                
                $customer_detail->name              = $request_data->cust_name;
                $customer_detail->opening_balance   = $request_data->opening_balance;
                $customer_detail->balance           = $request_data->opening_balance;
                $customer_detail->email             = $request_data->email;
                $customer_detail->phone             = $request_data->phone;
                $customer_detail->whatsapp          = $request_data->whatsapp;
                $customer_detail->gender            = $request_data->gender;
                $customer_detail->country           = $country->name;
                $customer_detail->city              = $request_data->city ?? '';
                $customer_detail->post_code         = $request_data->post_code;
                $customer_detail->customer_id       = $userData->id;
                $customer_detail->save();
                $customer_name = $customer_detail->id;
                
                $customer_detail    = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
                $countires          = DB::table('countries')->select('id','name')->get();
                return response()->json(['status'=>'success','message1'=>'success','customer_name'=>$customer_name,'customer_detail'=>$customer_detail,'countires'=>$countires,'message'=>'customer added Successfully']);
            }
        }else{
            return response()->json(['status'=>'error','customer_detail'=>'','message'=>'validation Error']);
        }
    }
 









 public function update_Invoices_TranSupp(Request $req){
        $id     = $req->id;
        $insert = addManageInvoice::find($id);
        if($insert)
        {
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->services                = $req->services;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
             
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            // $insert->quotation_prepared      = $req->quotation_prepared;
            // $insert->quotation_valid_date    = $req->quotation_valid_date;
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $insert->generate_id                    = $generate_id;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            $insert->flights_details                = $req->flights_details;
            $insert->flights_details_more           = $req->more_flights_details;
            $insert->accomodation_details           = $req->accomodation_details;
            $insert->accomodation_details_more      = $req->more_accomodation_details;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            $insert->more_visa_details              = $req->more_visa_details;
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_image                     = $req->visa_image;
            
            $insert->without_markup_total_price_visa    = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa            = $req->markup_total_price_visa;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            $insert->gallery_images                 = $req->gallery_images;
            $insert->start_date                     = $req->start_date;
            $insert->end_date=$req->end_date;
            $insert->time_duration=$req->time_duration;
            $insert->tour_location=$req->tour_location;
            $insert->whats_included=$req->whats_included;
            $insert->whats_excluded=$req->whats_excluded;
            $insert->currency_symbol=$req->currency_symbol;
            $insert->tour_publish=$req->defalut_state;
            $insert->tour_author=$req->tour_author;
            $insert->tour_feature=$req->tour_feature;
            $insert->defalut_state=$req->defalut_state;
            $insert->payment_gateways=$req->payment_gateways;
            $insert->payment_modes=$req->payment_modes;
            $insert->markup_details=$req->markup_details;
            $insert->more_markup_details=$req->more_markup_details;
            $insert->tour_featured_image=$req->tour_featured_image;
            $insert->tour_banner_image=$req->tour_banner_image;
            $insert->Itinerary_details=$req->Itinerary_details;
            $insert->tour_itinerary_details_1=$req->tour_itinerary_details_1;
            $insert->tour_extra_price=$req->tour_extra_price;
            $insert->tour_extra_price_1=$req->tour_extra_price_1;
            $insert->tour_faq=$req->tour_faq;
            $insert->tour_faq_1=$req->tour_faq_1;
            
            $insert->payment_messag=$req->checkout_message;
            
            $insert->total_Cost_Price_All=$req->total_Cost_Price_All;
            $insert->total_Sale_Price_All=$req->total_Sale_Price_All;
            
            $insert->option_date=$req->option_date;
            $insert->reservation_number=$req->reservation_number;
            $insert->hotel_reservation_number=$req->hotel_reservation_number;
            
            $insert->total_sale_price_all_Services=$req->total_sale_price_all_Services;
            
            $insert->transfer_supplier      = $req->transfer_supplier;
            $insert->transfer_supplier_id   = $req->transfer_supplier_id;
            
            $insert->update();
            return response()->json(['status'=>'success','message'=>'Agent Invoice Updated Succesfully']); 
        }else{
            return response()->json(['status'=>'error','message'=>$id]); 
        }
    }
    
    public function confirm_invoice_Agent(Request $request){
        $id             = $request->id;
        $Invoice_detail = addManageInvoice::find($id);
        if($Invoice_detail)
        {
            $Invoice_detail->confirm            = 1;
            $Invoice_detail->mail_request_pax   = 1;
            $Invoice_detail->update();
            return response()->json(['Success'=>'Invoice Confirmed Successful!']);
        }
        else{
            return response()->json(['Invoice_detail'=>$Invoice_detail,'Error'=>'Agents Not Updated!']);
        }
    }
    
    public function invoice_Agent(Request $req){
        try {
            $data1                          = addManageInvoice::find($req->id);
            // dd($data1);
            if($data1){
                if(isset($data1->agent_Id) && $data1->agent_Id != null && $data1->agent_Id != ''){
                    $agent_data             = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->where('id',$data1->agent_Id)->first();
                    $total_paid_amount      = DB::table('invoices_payment_recv')->where('invoice_no',$data1->generate_id)->get();
                }else{
                    $agent_data             = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
                    $total_paid_amount      = DB::table('invoices_payment_recv')->where('invoice_no',$data1->generate_id)->get();
                    // $total_paid_amount      = 0;
                }
                
                $b2b_Agent_Data             = DB::table('b2b_agents')->where('id',$data1->b2b_Agent_Id)->first();
                
                $booking_customer_data      = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
                $customer_data              = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
                $groups_Data                = DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->where('group_Invoice_No',$req->id)->get();
                $contact_details            = DB::table('contact_details')->where('customer_id',$req->customer_id)->first();
                $invoice_P_details          = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->get();
                $total_invoice_Payments     = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->first();
                $recieved_invoice_Payments  = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->sum('amount_paid');
                $remainig_invoice_Payments  = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->sum('remaining_amount');
                $all_countries              = country::all();
                
                $invoice_Advance_Option     = DB::table('add_manage_invoice_advanceoptions')->where('customer_id',$req->customer_id)->where('invoice_Id',$data1->id)->first();
                if($invoice_Advance_Option != null){
                    $data1->all_costing_details_AO      = $invoice_Advance_Option->all_costing_details_AO ?? '';
                    $data1->WOFVT_details               = $invoice_Advance_Option->WOFVT_details ?? '';
                }
                
                $otherPaxDetails                = DB::table('otherPaxDetails')->where('customer_id',$req->customer_id)->where('invoice_id',$req->id)->get();
                $userData                       = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
                $staffDetails                   = DB::table('staffDetails')->where('customer_id',$req->customer_id)->get();
                
                return response()->json([
                    'message'                   => 'success',
                    'userData'                  => $userData,
                    'b2b_Agent_Data'            => $b2b_Agent_Data,
                    'data1'                     => $data1,
                    'customer_data'             => $customer_data,
                    'contact_details'           => $contact_details,
                    'invoice_P_details'         => $invoice_P_details,
                    'total_invoice_Payments'    => $total_invoice_Payments,
                    'recieved_invoice_Payments' => $recieved_invoice_Payments,
                    'remainig_invoice_Payments' => $remainig_invoice_Payments,
                    'all_countries'             => $all_countries,
                    'agent_data'                => $agent_data,
                    'total_paid_amount'         => $total_paid_amount,
                    'groups_Data'               => $groups_Data,
                    'booking_customer_data'     => $booking_customer_data,
                    'otherPaxDetails'           => $otherPaxDetails,
                    'staffDetails'              => $staffDetails,
                ]);
            }else{
                return response()->json([
                    'message'                   => 'error',
                ]);
            }
        } catch (\Exception $e) {
            echo $e;
        }
   }
    
    public function invoice_Agent_B2B(Request $req){
        try {
            // 2102
            // 276
            // 54
            $customer_Data                  = DB::table('customer_subcriptions')->where('Auth_key',$req->token)->first();
            if($customer_Data){
                $invoice_Data               = DB::table('add_manage_invoices')->where('customer_id',$customer_Data->id)->where('b2b_Agent_Id',$req->b2b_Agent_Id)->where('id',$req->invoice_Id)->first();
                if($invoice_Data != null){
                    $total_paid_amount      = DB::table('invoices_payment_recv')->where('invoice_no',$invoice_Data->generate_id)->get();
                    $groups_Data            = DB::table('addGroupsdetails')->where('customer_id',$customer_Data->id)->where('group_Invoice_No',$req->invoice_Id)->get();
                    $invoice_P_details      = DB::table('pay_Invoice_Agent')->where('customer_id',$customer_Data->id)->where('invoice_Id',$req->invoice_Id)->get();
                    $all_countries          = country::all();
                    
                    return response()->json([
                        'message'           => 'success',
                        'invoice_Data'      => $invoice_Data,
                        'customer_Data'     => $customer_Data,
                        'total_paid_amount' => $total_paid_amount,
                        'groups_Data'       => $groups_Data,
                        'invoice_P_details' => $invoice_P_details,
                        'all_countries'     => $all_countries,
                    ]);
                }else{
                    return response()->json([
                        'message'           => 'error',
                        'invoice_Data'      => [],
                        'customer_Data'     => [],
                        'total_paid_amount' => [],
                        'groups_Data'       => [],
                        'invoice_P_details' => [],
                        'all_countries'     => [],
                    ]);
                }
            }else{
                return response()->json([
                    'message'               => 'error',
                    'invoice_Data'          => [],
                    'customer_Data'         => [],
                    'total_paid_amount'     => [],
                    'groups_Data'           => [],
                    'invoice_P_details'     => [],
                    'all_countries'         => [],
                ]);
            }
        } catch (\Exception $e) {
            echo $e;
        }
   }
    
    public function get_rooms_list(Request $request){
        $customer_id        = $request->customer_id;
        $id                 = $request->id;
        $rooms_types        = DB::table('rooms_types')->where('customer_id',$customer_id)->get();
        $rooms_suppliers    = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        
        $user_rooms         = DB::table('rooms')->where('hotel_id',$id)
                                ->where('owner_id',$customer_id)
                                ->where('availible_from','<=', $request->check_in)
                                ->where('availible_to','>=', $request->check_out)
                                ->get();
        return response()->json(['status'=>'success','user_rooms'=>$user_rooms,'rooms_types'=>$rooms_types,'rooms_suppliers'=>$rooms_suppliers]); 
    }
    
    public function get_hotel_Suppliers(Request $request){
        // $hotel_Supplier_city        = [];
        // $hotel_Supplier_city_Single = DB::table('rooms_Invoice_Supplier')->where('customer_id', $request->customer_id)->where('city', $request->city_name)->get();
        // foreach($hotel_Supplier_city_Single as $val_S){
        //     array_push($hotel_Supplier_city,$val_S);
        // }
        
        // $hotel_Supplier_city_All    = DB::table('rooms_Invoice_Supplier')->where('customer_id', $request->customer_id)->get();
        // foreach($hotel_Supplier_city_All as $val_A){
        //     $city = json_decode($val_A->city);
        //     if(is_array($city)){
        //         foreach($city as $val_city){
        //             if($val_city == $request->city_name){
        //                 array_push($hotel_Supplier_city,$val_A);
        //             }
        //         }
        //     }
        // }
        
        $desiredCity            =   $request->city_name;
        $hotel_Supplier_city    =   rooms_Invoice_Supplier::where('customer_id',$request->customer_id)->where(function ($query) use ($desiredCity) {
                                        $query->where(function ($query) use ($desiredCity) {
                                            $query->whereRaw('JSON_VALID(city) = 1')->whereRaw('JSON_CONTAINS(city, ?)', ['"'.$desiredCity.'"']);
                                        })->orWhere('city', $desiredCity);
                                    })->get();
        // dd($hotel_Supplier_city);
        
        return response()->json(['status'=>'success','hotel_Supplier_city'=>$hotel_Supplier_city]); 
    }
    
    public function get_rooms_view(Request $request){
        $customer_id        = $request->customer_id;
        $id                 = $request->id;
        $rooms_view         = DB::table('rooms')
                                ->where('id',$id)
                                ->where('owner_id',$customer_id)
                                ->select('room_view')
                                ->get();
        return response()->json(['status'=>'success','rooms_view'=>$rooms_view]); 
    }
    
    public function get_invoiceRoomSupplier_detail(Request $request){
        $customer_id        = $request->customer_id;
        $RI_suppliers       = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        return response()->json(['status'=>'success','RI_suppliers'=>$RI_suppliers]); 
    }
    
    public function submit_invoiceRoomSupplier(Request $request){
        $customer_id        = $request->customer_id;
        $room_supplier_name = $request->room_supplier_name;
        $RI_suppliers_add   = DB::table('rooms_Invoice_Supplier')->insert(['room_supplier_name'=>$room_supplier_name,'customer_id'=>$request->customer_id]);
        $RI_suppliers       = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
        return response()->json(['success'=>'Room Invoice Suppliers Added SuccessFUl!','RI_suppliers'=>$RI_suppliers]);
    } 
    
    public function get_hotels_list(Request $request){
        $customer_id        = $request->customer_id;
        $property_city_new  = $request->property_city_new;
        $user_hotels        = DB::table('hotels')->where('owner_id',$customer_id)->where('property_city',$property_city_new)->get();
        // $user_hotels        = DB::table('hotels')
        //                         ->where(function ($query) use ($customer_id, $property_city_new) {
        //                             $query->where('hotels.owner_id', $customer_id)
        //                                   ->where('hotels.property_city', $property_city_new);
        //                         })
        //                         ->orWhere(function ($query) use ($customer_id, $property_city_new) {
        //                             $query->WhereJsonContains('hotels.allowed_Clients', [['client_Id' => (string)$customer_id]])
        //                                   ->where('hotels.property_city', $property_city_new);
        //                         })
        //                         ->get();
        // return $user_hotels;
        return response()->json(['status'=>'success','user_hotels'=>$user_hotels]); 
    }
    
    public function get_hotels_list_Single(Request $request){
        $customer_id        = $request->customer_id;
        $user_hotels        = DB::table('hotels')->where('owner_id',$customer_id)->where('id',$request->hotel_Id)->get();
        return response()->json(['status'=>'success','user_hotels'=>$user_hotels]); 
    }
    
    public function pay_invoice_Agent(Request $request){
        // dd($request);
        $data1                      = DB::table('add_manage_invoices')->where('id',$request->id)->get();
        $amount_Paid                = DB::table('pay_Invoice_Agent')->where('invoice_Id',$request->id)->sum('amount_Paid');
        return response()->json([
            'data1'         => $data1,
            'amount_Paid'   => $amount_Paid,
        ]);
    }
    
    public function recieve_invoice_Agent(Request $req){
        DB::beginTransaction();
        try {
                $invoice_data       = DB::table('add_manage_invoices')->where('id',$req->invoice_Id)->first();
                
                $total_amount       = $invoice_data->total_sale_price_all_Services;
                $paid_amount        = $invoice_data->total_paid_amount;
                $over_paid_amount   = $invoice_data->over_paid_amount;
                
                $total_paid_amount  = $paid_amount + $req->recieved_Amount;
                $total_over_paid    = 0;
                $over_paid_am       = 0;
                
                if($total_paid_amount > $total_amount){
                    $over_paid_am       = $total_paid_amount - $total_amount;
                    $total_over_paid    = $over_paid_amount + $over_paid_am;
                    $total_paid_amount  = $total_paid_amount - $over_paid_am;
                }
                
                DB::table('add_manage_invoices')->where('id',$req->invoice_Id)->update([
                    'total_paid_amount' => $total_paid_amount,
                    'over_paid_amount'  => $total_over_paid,
                ]);
                
                $agent_data         = DB::table('Agents_detail')->where('id',$invoice_data->agent_Id)->select('id','over_paid_am')->first();
                $agent_over_paid    = $agent_data->over_paid_am + $over_paid_am;
                
                DB::table('Agents_detail')->where('id',$agent_data->id)->update(['over_paid_am'=>$agent_over_paid]);
    
                $insert                     = new pay_Invoice_Agent();
                $insert->invoice_Id         = $req->invoice_Id;
                $insert->generate_id        = $req->generate_id;
                $insert->customer_id        = $req->customer_id;
                $insert->agent_Name         = $req->agent_Name;
                $insert->date               = $req->date;
                $insert->passenger_Name     = $req->passenger_Name;
                $insert->total_Amount       = $req->total_Amount;
                $insert->recieved_Amount    = $req->recieved_Amount;
                $insert->remaining_Amount   = $req->remaining_Amount;
                $insert->amount_Paid        = $req->amount_Paid;
                $insert->save();
                
                $notification_insert                            = new alhijaz_Notofication();
                $notification_insert->type_of_notification_id   = $insert->id ?? ''; 
                $notification_insert->customer_id               = $insert->customer_id ?? ''; 
                $notification_insert->type_of_notification      = 'pay_Invoice' ?? ''; 
                $notification_insert->generate_id               = $insert->generate_id ?? '';
                $notification_insert->notification_creator_name = $req->agent_Name ?? '';
                $notification_insert->total_price               = $insert->total_Amount ?? ''; 
                $notification_insert->amount_paid               = $insert->amount_Paid ?? ''; 
                $notification_insert->remaining_price           = $insert->remaining_Amount ?? ''; 
                $notification_insert->notification_status       = '1' ?? ''; 
                $notification_insert->save();
                
                DB::table('agents_ledgers')->insert([
                    'agent_id'=>$agent_data->id,
                    'payment_id'=>$insert->id,
                    "payment"=>$req->recieved_Amount,
                    "invoice_no"=>$invoice_data->id,
                    "total_amount"=>$total_amount,
                    "paid_amount"=>$total_paid_amount,
                    "remaining_amount"=>$total_amount - $total_paid_amount,
                    'over_paid'=>$agent_over_paid,
                    'date'=>$req->date,
                  
                 ]);
                
                DB::commit();
            
                return response()->json([
                    'message' => 'Success',
                ]);
                
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
        }
    }
    
    // public function payable_ledger(Request $req){
    //     $customer_id        = $req->customer_id;
    //     $join_data          = DB::table('add_manage_invoices')
    //                             ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
    //                             ->where('add_manage_invoices.customer_id',$customer_id)
    //                             ->get();
                        
    //     $invoice_data       = DB::table('add_manage_invoices')->where('add_manage_invoices.customer_id',$customer_id)->get();
    //     $payment_data       = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)->orderBy('invoice_Id', 'ASC')->orderBy('pay_Invoice_Agent.created_at', 'ASC')->get();
    //     $total_Dues         = DB::table('add_manage_invoices')->where('add_manage_invoices.customer_id',$customer_id)->get();
    //     $total_Sdue         = DB::table('add_manage_invoices')
    //                             ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
    //                             ->where('add_manage_invoices.customer_id',$customer_id)->select('invoice_Id','amount_Paid')->get();
                                
    //     $invoice_payment_count = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)
    //                                 ->select('invoice_Id')->groupBy('invoice_Id')->count();
                            
    //     return response()->json([
    //         'status'        => 'success',
    //         'join_data'     => $join_data,
    //         'invoice_data'  => $invoice_data,
    //         'payment_data'  => $payment_data,
    //         'total_Dues'    => $total_Dues,
    //         'total_Sdue'    => $total_Sdue,
    //         'invoice_payment_count' => $invoice_payment_count,
    //     ]);
    // }
    
    // public function receivAble_ledger(Request $req){
    //     $customer_id            = $req->customer_id;
    //     $join_data              = DB::table('add_manage_invoices')
    //                                 ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
    //                                 ->where('add_manage_invoices.customer_id',$customer_id)
    //                                 ->get();
                        
    //     $invoice_data           = DB::table('add_manage_invoices')->where('add_manage_invoices.customer_id',$customer_id)->get();
    //     $payment_data           = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)->orderBy('invoice_Id', 'ASC')->orderBy('pay_Invoice_Agent.created_at', 'ASC')->get();
    //     $total_recieveAble      = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)->sum('recieved_Amount');
    //     $total_Sdue             = DB::table('add_manage_invoices')
    //                                 ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
    //                                 ->where('add_manage_invoices.customer_id',$customer_id)->select('invoice_Id','amount_Paid')->get();
                                
    //     $invoice_payment_count  = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)
    //                                 ->select('invoice_Id')->groupBy('invoice_Id')->count();
                                    
    //     // $jugar_count            = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)->count();
        
    //     return response()->json([
    //         'status'                => 'success',
    //         'join_data'             => $join_data,
    //         'invoice_data'          => $invoice_data,
    //         'payment_data'          => $payment_data,
    //         'total_recieveAble'     => $total_recieveAble,
    //         'total_Sdue'            => $total_Sdue,
    //         'invoice_payment_count' => $invoice_payment_count,
    //         // 'jugar_count'           => $jugar_count,
    //     ]);
    // }
    
    // public function cash_ledger(Request $req){
    //     $customer_id        = $req->customer_id;
    //     $join_data          = DB::table('add_manage_invoices')
    //                             ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
    //                             ->where('add_manage_invoices.customer_id',$customer_id)
    //                             ->get();
                        
    //     $invoice_data       = DB::table('add_manage_invoices')->where('add_manage_invoices.customer_id',$customer_id)->get();
    //     $payment_data       = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)->orderBy('invoice_Id', 'ASC')->orderBy('pay_Invoice_Agent.created_at', 'ASC')->get();
    //     $total_Dues         = DB::table('add_manage_invoices')->where('add_manage_invoices.customer_id',$customer_id)->get();
    //     $total_Sdue         = DB::table('add_manage_invoices')
    //                             ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
    //                             ->where('add_manage_invoices.customer_id',$customer_id)->select('invoice_Id','amount_Paid')->get();
                            
    //     return response()->json([
    //         'status'        => 'success',
    //         'join_data'     => $join_data,
    //         'invoice_data'  => $invoice_data,
    //         'payment_data'  => $payment_data,
    //         'total_Dues'    => $total_Dues,
    //         'total_Sdue'    => $total_Sdue,
    //     ]);
    // }
    
    public function request_Invoices(Request $req){
        $customer_id=$req->customer_id;
        $categories=DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes=DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer=DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries = country::all();
        $all_countries_currency = country::all();
        $bir_airports=DB::table('bir_airports')->get();
        $payment_gateways=DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes=DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $Agents_detail  = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        $user_hotels    = Hotels::where('owner_id',$customer_id)->get();
        $mange_currencies   = DB::table('mange_currencies')->where('customer_id',$req->customer_id)->get();
        return response()->json(['message'=>'success','mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes]);
    }
    
    public function single_notification_detail(Request $request){
        $single_notification_detail = DB::table('alhijaz_Notofication')->where('id',$request->id)->first();
        return response()->json([
            'single_notification_detail' => $single_notification_detail,
        ]);
    }
    
    public function all_notification_detail(Request $request){
        if(isset($request->customer_id)){
            $all_notification_detail = DB::table('alhijaz_Notofication')->where('customer_id',$request->customer_id)->where('alhijaz_Notofication.notification_status','1')->orderBy('id', 'desc')->get();
        }else{
            $all_notification_detail = DB::table('alhijaz_Notofication')->where('alhijaz_Notofication.notification_status','1')->orderBy('id', 'desc')->get();
        }
        return response()->json([
            'all_notification_detail' => $all_notification_detail,
        ]);
    }
    
    public function agents_financial_stats(Request $request){
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $agent_lists = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
            
            $all_agents_data = [];
            foreach($agent_lists as $agent_res){
                $agents_tour_booking    = DB::table('cart_details')->where('agent_name',$agent_res->id)->get();
                $agents_invoice_booking = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->id)->select('total_sale_price_all_Services','total_paid_amount','over_paid_amount','accomodation_details','accomodation_details_more','markup_details','more_markup_details','no_of_pax_days','id','agent_Id','currency_symbol')->get();
                
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
                    
                    if($cart_all_data->discount_type == 'amount'){
                        $final_profit = $grand_profit - $cart_all_data->discount_enter_am;
                    }else{
                       $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                       $final_profit = $grand_profit - $discunt_am_over_all;
                    }
                    
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
                                (float)$double_cost     = $accmod_res->acc_total_amount; 
                                (float)$double_sale     = $accmod_res->hotel_invoice_markup ?? 0; 
                                $double_total_cost      = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                                $double_total_sale      = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                                $double_profit          = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                                $double_total_profit    = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Double'){
                                $double_cost = $accmod_res->more_acc_total_amount; 
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                                }else{
                                    $double_sale = 0; 
                                }
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
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? '0'; 
                                }else{
                                    $triple_sale = 0; 
                                }
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
                    $quad_total_cost    = 0;
                    $quad_total_sale    = 0;
                    $quad_total_profit  = 0;
                    if(isset($accomodation)){
                                foreach($accomodation as $accmod_res){
                                    if($accmod_res->acc_type == 'Quad'){
                                        $quad_cost = $accmod_res->acc_total_amount; 
                                        if(isset($accmod_res->hotel_invoice_markup)){
                                            $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                        }else{
                                            $quad_sale = 0; 
                                        }
                                        $quad_total_cost    = $quad_total_cost ?? 0 + ($quad_cost * (float)$accmod_res->acc_qty);
                                        $quad_total_sale    = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                        $quad_profit        = ($quad_sale ?? 0 - $quad_cost) * (float)$accmod_res->acc_qty;
                                        $quad_total_profit  = $quad_total_profit + $quad_profit;
                                    }
                                }
                             }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Quad'){
                                $quad_cost = (float)$accmod_res->more_acc_total_amount;
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                }else{
                                    $quad_sale = 0; 
                                }
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
                                if(isset($accmod_res->hotel_invoice_markup)){
                                    (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                                }else{
                                    $double_sale = 0; 
                                }
                                
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
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                                }else{
                                    $double_sale = 0; 
                                }
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
                                if(isset($accmod_res->hotel_invoice_markup)){
                                    $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                }else{
                                    $triple_sale = 0; 
                                }
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
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                }else{
                                    $triple_sale = 0; 
                                }
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
                                        if(isset($accmod_res->hotel_invoice_markup)){
                                            $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                        }else{
                                            $quad_sale = 0; 
                                        }
                                        
                                         $quad_total_cost = $quad_total_cost ?? 0 + ($quad_cost * (float)$accmod_res->acc_qty ?? 1);
                                         $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty ?? 1);
                                         $quad_profit = ($quad_sale ?? 0 - $quad_cost) * (float)$accmod_res->acc_qty ?? 1;
                                         $quad_total_profit = $quad_total_profit + $quad_profit;
                                    }
                                }
                             }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Quad'){
                                $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                }else{
                                    $quad_sale = 0; 
                                }
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
            $separate_package_grand_profit = 0;
            $separate_package_cost_price   = 0;
            $separate_package_Revenue      = 0;
            foreach($separate_package_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data);
                     
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
            $separate_package_grand_profit = 0;
            $separate_package_cost_price   = 0;
            $separate_package_Revenue      = 0;
        }
        
        $separate_activity_booking          = DB::table('cart_details')->where('client_id',$request->customer_id)->where('pakage_type','activity')->get();
        
        if(count($separate_activity_booking) > 0){
            $separate_activity_grand_profit = 0;
            $separate_activity_cost_price   = 0;
            $separate_activity_Revenue      = 0;
            
            foreach($separate_activity_booking as $activity_res){
                $tours_costing  = DB::table('new_activites')->where('new_activites.id',$activity_res->tour_id)->first();                    
                $cart_all_data  = json_decode($activity_res->cart_total_data); 
                
                // dd($tours_costing);
                
                if(isset($cart_all_data) && $cart_all_data->adults > 0 && isset($tours_costing->cost_price)){
                    $double_adult_total_cost            = $tours_costing->cost_price * $cart_all_data->adults;
                    $double_profit                      = $cart_all_data->adult_price - $double_adult_total_cost;
                    $separate_activity_grand_profit     += $double_profit;
                    $separate_activity_cost_price       += $double_adult_total_cost;
                    $separate_activity_Revenue          += $cart_all_data->adult_price;
                }
                
                if(isset($cart_all_data) && $cart_all_data->children > 0 && isset($tours_costing->cost_price)){
                    $double_child_total_cost            = $tours_costing->cost_price * $cart_all_data->children;
                    $double_profit                      = $cart_all_data->child_price - $double_child_total_cost;
                    $separate_activity_grand_profit     += $double_profit;
                    $separate_activity_cost_price       += $double_child_total_cost;
                    $separate_activity_Revenue          += $cart_all_data->child_price;
                }
                
            }
        }else{
            $separate_activity_grand_profit = 0;
            $separate_activity_cost_price   = 0;
            $separate_activity_Revenue      = 0;
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
    
    public static function revenue_Stream_Season_Working($all_data,$request){
        $today_Date             = date('Y-m-d');
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
        
        // dd($season_Details,$all_data);
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            
            $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                // dd($record['created_at']);
                
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
    
    public function booking_financial_stats(Request $request){
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $agent_lists        = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
            $customer_lists     = DB::table('booking_customers')->where('customer_id',$request->customer_id)->get();
            $all_agents_data    = [];
            
            $agents_tour_booking        = DB::table('cart_details')->where('client_id',$request->customer_id)->where('pakage_type','tour')->get();
            $agents_activity_booking    = DB::table('cart_details')->where('client_id',$request->customer_id)->where('pakage_type','activity')->get();
            $agents_invoice_booking     = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id)->select('total_cost_price_Company','total_sale_price_Company','f_name','generate_id','created_at','services','agent_Id','agent_name','more_visa_details','total_sale_price_all_Services','total_cost_price_all_Services','total_paid_amount','over_paid_amount','accomodation_details','accomodation_details_more','markup_details','more_markup_details','no_of_pax_days','id','agent_Id','currency_symbol','visa_Pax')->get();
            
            $booking_all_data = [];
            foreach($agents_tour_booking as $tour_res){
             
                $tours_costing      = DB::table('tours_2')
                                        ->join('tours','tours_2.tour_id','tours.id')
                                        ->where('tours_2.tour_id',$tour_res->tour_id)
                                        ->select('tours.created_at','tours.title','tours_2.quad_cost_price','tours_2.triple_cost_price','tours_2.double_cost_price','tours_2.without_acc_cost_price','tours_2.child_grand_total_cost_price','tours_2.infant_total_cost_price')->first();
                
                $passenger_nameQ    = DB::table('tours_bookings')
                                        ->where('tours_bookings.id',$tour_res->booking_id)
                                        ->select('passenger_name')
                                        ->get();
                
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
                    $double_infant_total_cost   = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                    $double_profit              = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    $grand_profit               += $double_profit;
                    $grand_cost                 += $double_infant_total_cost;
                    $grand_sale                 += $cart_all_data->double_infant_total;
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
                    $quad_profit            = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    $grand_profit           += $quad_profit;
                    $grand_cost            += $quad_infant_total_cost;
                    $grand_sale             += $cart_all_data->quad_infant_total;
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
                
                if($cart_all_data->discount_type == 'amount'){
                    $final_profit   = $grand_profit - $cart_all_data->discount_enter_am;
                    $grand_sale     = $grand_sale - $cart_all_data->discount_enter_am;
                }else{
                   $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                   $final_profit = $grand_profit - $discunt_am_over_all;
                   $grand_sale   = $grand_sale - $discunt_am_over_all;
                }
                
                if(isset($cart_all_data->special_discount)){
                    $final_profit   = $grand_profit - $cart_all_data->special_discount;
                    $grand_sale     = $grand_sale - $cart_all_data->special_discount;
                }
                // else{
                //     $final_profit   = $grand_profit;
                // }
                
                $commission_add = '';
                if(isset($cart_all_data->agent_commsion_add_total)){
                    $commission_add = $cart_all_data->agent_commsion_add_total;
                }
                
                $agent_name = $tour_res->agent_name;
                foreach($agent_lists as $agent_res){
                    if($agent_res->id == $tour_res->agent_name){
                        $agent_name = $agent_res->agent_Name;
                    }
                }
                
                $booking_data = [
                        'title'                     => $tour_res->tour_name,
                        'agent_Id'                  => $tour_res->agent_name,
                        'agent_name'                => $agent_name,
                        'invoice_id'                => $tour_res->invoice_no,
                        'booking_id'                => $tour_res->booking_id,
                        'passenger_name'            => $passenger_nameQ[0]->passenger_name,
                        'tour_id'                   => $tour_res->tour_id,
                        'price'                     => $tour_res->tour_total_price,
                        'paid_amount'               => $tour_res->total_paid_amount,
                        'remaing_amount'            => $tour_res->tour_total_price - $tour_res->total_paid_amount,
                        'over_paid_amount'          => $tour_res->over_paid_amount,
                        'tour_name'                 => $cart_all_data->name,
                        'profit'                    => $final_profit,
                        'discount_am'               => $cart_all_data->discount_Price,
                        'special_discount'          => $cart_all_data->special_discount ?? '0',
                        'total_cost'                => $grand_cost,
                        'total_sale'                => $grand_sale,
                        'commission_am'             => $cart_all_data->agent_commsion_am,
                        'agent_commsion_add_total'  => $commission_add,
                        'currency'                  => $tour_res->currency,
                        'created_at'                => $tour_res->created_at
                    ];
                
                array_push($booking_all_data,$booking_data);
            }
            
            foreach($agents_activity_booking as $activity_res){
             
                $tours_costing      = DB::table('new_activites')->where('new_activites.id',$activity_res->tour_id)->first();
                
                $passenger_nameQ    = DB::table('tours_bookings')->where('tours_bookings.id',$activity_res->booking_id)->select('passenger_name')->get();
                
                $cart_all_data      = json_decode($activity_res->cart_total_data);
                $grand_profit       = 0;
                $grand_cost         = 0;
                $grand_sale         = 0;
                
                // dd($tours_costing);
                
                // Profit From Double Adults
                if(isset($cart_all_data) && $cart_all_data->adults > 0 && isset($tours_costing->cost_price)){
                    $double_adult_total_cost    = $tours_costing->cost_price * $cart_all_data->adults;
                    $double_profit              = $cart_all_data->adult_price - $double_adult_total_cost;
                    $grand_profit               += $double_profit;
                    $grand_cost                 += $double_adult_total_cost;
                    $grand_sale                 += $cart_all_data->adult_price;
                }
                
                if(isset($cart_all_data) && $cart_all_data->children > 0 && isset($tours_costing->cost_price)){
                    $double_child_total_cost    = $tours_costing->cost_price * $cart_all_data->children;
                    $double_profit              = $cart_all_data->child_price - $double_child_total_cost;
                    $grand_profit               += $double_profit;
                    $grand_cost                 += $double_child_total_cost;
                    $grand_sale                 += $cart_all_data->child_price;
                }
                
                $agent_id   = '';
                $agent_name = $activity_res->agent_name;
                if($agent_name != null && $agent_name != '' && $agent_name != '-1'){
                    foreach($agent_lists as $agent_res){
                        if($agent_res->id == $activity_res->agent_name){
                            $agent_name = $agent_res->agent_Name;
                            $agent_id   = $activity_res->agent_Name;
                        }
                    }
                }else{
                    foreach($customer_lists as $customer_res){
                        if(isset($tours_costing->customer_id) && $customer_res->id == $tours_costing->customer_id ?? ''){
                            $agent_name = $customer_res->name;
                            if(isset($cart_all_data) && $cart_all_data != null){
                                $agent_id   = $cart_all_data->customer_id ?? '';
                            }else{
                                $agent_id   = $tours_costing->customer_id ?? '';
                            }
                        }
                    }
                }
                
                $booking_data = [
                        'title'                     => $activity_res->tour_name,
                        'agent_Id'                  => $agent_id,
                        'agent_name'                => $agent_name,
                        'invoice_id'                => $activity_res->invoice_no,
                        'booking_id'                => $activity_res->booking_id,
                        'passenger_name'            => $passenger_nameQ[0]->passenger_name,
                        'tour_id'                   => $activity_res->tour_id,
                        'price'                     => $activity_res->tour_total_price,
                        'paid_amount'               => $activity_res->total_paid_amount,
                        'remaing_amount'            => $activity_res->tour_total_price - $activity_res->total_paid_amount,
                        'over_paid_amount'          => $activity_res->over_paid_amount,
                        'tour_name'                 => $tours_costing->title ?? '',
                        'profit'                    => $final_profit ?? '0',
                        'discount_am'               => 0,
                        'special_discount'          => 0,
                        'total_cost'                => $grand_cost,
                        'total_sale'                => $grand_sale,
                        'commission_am'             => 0,
                        'agent_commsion_add_total'  => 0,
                        'currency'                  => $activity_res->currency,
                        'created_at'                => $activity_res->created_at
                    ];
                
                // array_push($booking_all_data,$booking_data);
                
                // dd($booking_data);
            }
            
            $invoices_all_data = [];
            foreach($agents_invoice_booking as $agent_inv_res){
                
                $accomodation           = json_decode($agent_inv_res->accomodation_details);
                $accomodation_more      = json_decode($agent_inv_res->accomodation_details_more);
                $markup_details         = json_decode($agent_inv_res->markup_details);
                $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
                
                // Caluclate Flight Price 
                $grand_cost     = 0;
                $grand_sale     = 0;
                $flight_cost    = 0;
                $flight_sale    = 0;
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
                $more_visa_cost = 0;
                $more_visa_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                        $visa_cost = $mark_res->without_markup_price; 
                        $visa_sale = $mark_res->markup_price; 
                    }
                }
                
                if(isset($agent_inv_res->visa_Pax) && $agent_inv_res->visa_Pax != null && $agent_inv_res->visa_Pax != ''){
                    $visa_total_cost = (float)$visa_cost * (float)$agent_inv_res->visa_Pax;
                    $visa_total_sale = (float)$visa_sale * (float)$agent_inv_res->visa_Pax;
                }else{
                    $visa_total_cost = (float)$visa_cost * (float)$agent_inv_res->no_of_pax_days;
                    $visa_total_sale = (float)$visa_sale * (float)$agent_inv_res->no_of_pax_days;
                }
                
                $more_visa_details_Pricing = json_decode($agent_inv_res->more_visa_details);
                if(isset($more_visa_details_Pricing) && $more_visa_details_Pricing != null && $more_visa_details_Pricing != ''){
                    foreach($more_visa_details_Pricing as $value){
                        if(isset($value->more_visa_Pax) && $value->more_visa_Pax != null && $value->more_visa_Pax != ''){
                            $visa_total_cost = $visa_total_cost + (float)$value->more_exchange_rate_visa_fee * (float)$value->more_visa_Pax;
                            $visa_total_sale = $visa_total_sale + (float)$value->more_total_visa_markup_value * (float)$value->more_visa_Pax;
                        }
                    }
                }
                
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
                            (float)$double_cost     = $accmod_res->acc_total_amount; 
                            (float)$double_sale     = $accmod_res->hotel_invoice_markup ?? 0; 
                            $double_total_cost      = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                            $double_total_sale      = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                            $double_profit          = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                            $double_total_profit    = $double_total_profit + $double_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Double'){
                            $double_cost = $accmod_res->more_acc_total_amount; 
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                            }else{
                                $double_sale = 0; 
                            }
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
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? '0'; 
                            }else{
                                $triple_sale = 0; 
                            }
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
                            if(isset($accmod_res->hotel_invoice_markup)){
                                $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                            }else{
                                $quad_sale = 0; 
                            }
                            $quad_total_cost    = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                            $quad_total_sale    = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                            $quad_profit        = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                            $quad_total_profit  = $quad_total_profit + $quad_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Quad'){
                            $quad_cost = (float)$accmod_res->more_acc_total_amount;
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            }else{
                                $quad_sale = 0; 
                            }
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
                $Final_inv_price    = $flight_total_sale + $visa_total_sale + $trans_total_sale + $double_total_sale + $triple_total_sale + $quad_total_sale;
                $Final_inv_profit   = $flight_profit + $visa_profit + $trans_profit + $double_total_profit + $triple_total_profit + $quad_total_profit;
                
                $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                $total_paid_amount = 0;
                foreach($invoice_payments as $inv_pay_res){
                    $total_paid_amount += $inv_pay_res->amount_Paid;
                }
                
                $service_type = '';
                $services = json_decode($agent_inv_res->services);
                // dd($services[0]);
                if($services[0] == '1'){
                    $service_type = 'All Services';
                }else if($services[0] == 'accomodation_tab'){
                    $service_type = 'Hotel';
                }else if($services[0] == 'flight_tab'){
                    $service_type = 'Flight';
                }else if($services[0] == 'visa_tab'){
                    $service_type = 'Visa';
                }else if($services[0] == 'transportation_tab'){
                    $service_type = 'Transfer';
                }else{
                    $service_type = 'Not Selected';
                }
                
                if($agent_inv_res->total_sale_price_Company != null && $agent_inv_res->total_sale_price_Company != '' && $agent_inv_res->total_sale_price_Company != 'null'){
                    $total_sale_price_Company = $agent_inv_res->total_sale_price_Company;
                }else{
                    $total_sale_price_Company = $agent_inv_res->total_sale_price_all_Services ?? 0;
                }
                
                if($agent_inv_res->total_cost_price_Company != null && $agent_inv_res->total_cost_price_Company != '' && $agent_inv_res->total_cost_price_Company != 'null'){
                    $total_cost_price_Company = $agent_inv_res->total_cost_price_Company;
                }else{
                    $total_cost_price_Company = $agent_inv_res->total_cost_price_all_Services ?? 0;
                }
                
                // if($agent_inv_res->generate_id == '3339688'){
                //     dd($agent_inv_res,$total_cost_price_Company);
                // }
                
                $inv_single_data = [
                    'agent_Id'          => $agent_inv_res->agent_Id,
                    'agent_name'        => $agent_inv_res->agent_name,
                    'passenger_name'    => $agent_inv_res->f_name,
                    'service_type'      => $service_type,
                    'invoice_id'        => $agent_inv_res->id,
                    'generate_id'       => $agent_inv_res->generate_id,
                    'price'             => $total_sale_price_Company,
                    'paid_amount'       => $agent_inv_res->total_paid_amount,
                    'remaing_amount'    => $total_sale_price_Company - $agent_inv_res->total_paid_amount,
                    'over_paid_amount'  => $agent_inv_res->over_paid_amount,
                    'profit'            => $total_sale_price_Company - $total_cost_price_Company,
                    'total_cost'        => $total_cost_price_Company,
                    'total_sale'        => $total_sale_price_Company,
                    'currency'          => $agent_inv_res->currency_symbol,
                    'created_at'        => $agent_inv_res->created_at
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
                            if(isset($accmod_res->hotel_invoice_markup)){
                                (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                            }else{
                                $double_sale = 0; 
                            }
                            
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
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                            }else{
                                $double_sale = 0; 
                            }
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
                            if(isset($accmod_res->hotel_invoice_markup)){
                                $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                            }else{
                                $triple_sale = 0; 
                            }
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
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            }else{
                                $triple_sale = 0; 
                            }
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
                                    if(isset($accmod_res->hotel_invoice_markup)){
                                        $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                    }else{
                                        $quad_sale = 0; 
                                    }
                                    
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
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            }else{
                                $quad_sale = 0; 
                            }
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
            
            $other_expense  = 0;
            $expense_data   = expense::join('expense_categories','expenses.category_id','expense_categories.id')
                                    ->join('expense_sub_categories','expenses.sub_category_id','expense_sub_categories.id')
                                    ->join('cash_accounts', 'cash_accounts.id', '=', 'expenses.account_id')
                                    ->where('expenses.customer_id',$request->customer_id)
                                    // ->select('expenses.*','expense_sub_categories.exp_sub_category','expense_categories.exp_category_name','cash_accounts.name','cash_accounts.account_no')
                                    ->orderBy('expenses.id','desc')
                                    ->get();
            if($expense_data != null && $expense_data != ''){
                foreach($expense_data as $expense_data_value){
                    if(isset($expense_data_value->total_amount) && $expense_data_value->total_amount != null && $expense_data_value->total_amount != '' && $expense_data_value->total_amount > 0){
                        $other_expense = $other_expense + $expense_data_value->total_amount;
                    }
                }
            }
            
            $agent_data = [
                    'agents_tour_booking'               => $booking_all_data,
                    'agents_invoices_booking'           => $invoices_all_data,
                    'invoice_Acc_details'               => $invoice_Acc_details,
                    'invoice_Flight_details'            => $invoice_Flight_details,
                    'invoice_Visa_details'              => $invoice_Visa_details,
                    'invoice_Transportation_details'    => $invoice_Transportation_details,
            ];
            array_push($all_agents_data,$agent_data);
            
            // dd($all_agents_data[0]['agents_tour_booking']);
            
            $all_agents_list    = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
            $all_customer_list  = DB::table('booking_customers')->where('customer_id',$request->customer_id)->get();
            $all_supplier_list  = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
            
            // Season
            $season_Id              = '';
            $today_Date             = date('Y-m-d');
            if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
                $season_Id          = 'all_Seasons';
            }elseif(isset($request->season_Id) && $request->season_Id > 0){
                $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
                $season_Id          = $season_SD->id ?? '';
            }else{
                $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
                $season_Id          = $season_SD->id ?? '';
            }
            $season_Details         = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
            if($request->customer_id == 4 || $request->customer_id == 54){
                if(!isset($all_agents_data[0]['agents_tour_booking']) && $all_agents_data[0]['agents_tour_booking']->isEmpty()){
                }else{
                    // dd($all_agents_data[0]['agents_tour_booking']);
                    $all_agents_data[0]['agents_tour_booking'] = $this->revenue_Stream_Season_Working($all_agents_data[0]['agents_tour_booking'],$request);
                    // dd($all_agents_data[0]['agents_tour_booking']);
                }
                
                if(!isset($all_agents_data[0]['agents_invoices_booking']) && $all_agents_data[0]['agents_invoices_booking']->isEmpty()){
                }else{
                    // dd($all_agents_data[0]['agents_invoices_booking']);
                    $all_agents_data[0]['agents_invoices_booking'] = $this->revenue_Stream_Season_Working($all_agents_data[0]['agents_invoices_booking'],$request);
                    // dd($all_agents_data[0]['agents_invoices_booking']);
                }
            }
            // Season
        }
        
        // dd($all_agents_data);
        return response()->json([
            'message'           => 'success',
            'agents_data'       => $all_agents_data,
            'expense_data'      => $expense_data,
            'all_agents_list'   => $all_agents_list,
            'all_customer_list' => $all_customer_list,
            'all_supplier_list' => $all_supplier_list,
            'season_Details'    => $season_Details,
            'season_Id'         => $season_Id,
        ]);
    }
    
    public function booking_financial_stats_Ajax(Request $request){
        // dd($request);
        
        $request_Data = json_decode($request->request_Data);
        // dd($request,$request_Data);
        
        $userData                       = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            
            // if($request_Data->supplier == 'all'){
            //     if($request_Data->report_Type_RS == 'data_wise'){
                    $agents_tour_booking        = DB::table('cart_details')->where('client_id',$request->customer_id)
                                                    ->whereDate('created_at','>=', $request_Data->start_date_RS)->whereDate('created_at','<=', $request_Data->end_date_RS)
                                                    ->where('pakage_type','tour')->get();
                    $agents_activity_booking    = DB::table('cart_details')->where('client_id',$request->customer_id)
                                                    ->whereDate('created_at','>=', $request_Data->start_date_RS)->whereDate('created_at','<=', $request_Data->end_date_RS)
                                                    ->where('pakage_type','activity')->get();
                    // $agents_invoice_booking     = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id)
                    //                                 ->whereDate('created_at','>=', $request_Data->start_date_RS)->whereDate('created_at','<=', $request_Data->end_date_RS)
                    //                                 ->select('total_cost_price_Company','total_sale_price_Company','f_name','generate_id','created_at','services','agent_Id','agent_name','more_visa_details','total_sale_price_all_Services','total_cost_price_all_Services','total_paid_amount','over_paid_amount','accomodation_details','accomodation_details_more','markup_details','more_markup_details','no_of_pax_days','id','agent_Id','currency_symbol','visa_Pax')->get();
                    
                    $invoices                   = DB::table('add_manage_invoices')
                                                    ->where('customer_id', $request->customer_id)
                                                    ->whereRaw("JSON_VALID(accomodation_details)")
                                                    ->select('total_cost_price_Company','total_sale_price_Company','f_name','generate_id','created_at','services','agent_Id','agent_name','more_visa_details','total_sale_price_all_Services','total_cost_price_all_Services','total_paid_amount','over_paid_amount','accomodation_details','accomodation_details_more','markup_details','more_markup_details','no_of_pax_days','id','agent_Id','currency_symbol','visa_Pax')
                                                    ->get();
                    $agents_invoice_booking     = $invoices->filter(function ($invoice) use ($request_Data) {
                                                    $accomodations = json_decode($invoice->accomodation_details, true);
                                                    if (is_array($accomodations)) {
                                                        foreach ($accomodations as $acc) {
                                                            if (
                                                                $acc['acc_check_in'] >= $request_Data->start_date_RS &&
                                                                $acc['acc_check_in'] <= $request_Data->end_date_RS
                                                            ) {
                                                                return true;
                                                            }
                                                        }
                                                    }
                                                    return false;
                                                });
                                            // dd($agents_invoice_booking);
            //     }
            // }
            
            $agent_lists                = DB::table('Agents_detail')->where('customer_id',$request->customer_id)
                                            // ->whereDate('created_at','>=', $request_Data->start_date_RS)->whereDate('created_at','<=', $request_Data->end_date_RS)
                                            ->get();
            $customer_lists             = DB::table('booking_customers')->where('customer_id',$request->customer_id)
                                            // ->whereDate('created_at','>=', $request_Data->start_date_RS)->whereDate('created_at','<=', $request_Data->end_date_RS)
                                            ->get();
            $all_agents_data            = [];
            
            // dd($agents_invoice_booking);
            $booking_all_data           = [];
            foreach($agents_tour_booking as $tour_res){
             
                $tours_costing      = DB::table('tours_2')
                                        ->join('tours','tours_2.tour_id','tours.id')
                                        ->where('tours_2.tour_id',$tour_res->tour_id)
                                        ->select('tours.created_at','tours.title','tours_2.quad_cost_price','tours_2.triple_cost_price','tours_2.double_cost_price','tours_2.without_acc_cost_price','tours_2.child_grand_total_cost_price','tours_2.infant_total_cost_price')->first();
                
                $passenger_nameQ    = DB::table('tours_bookings')
                                        ->where('tours_bookings.id',$tour_res->booking_id)
                                        ->select('passenger_name')
                                        ->get();
                
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
                    $double_infant_total_cost   = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                    $double_profit              = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    $grand_profit               += $double_profit;
                    $grand_cost                 += $double_infant_total_cost;
                    $grand_sale                 += $cart_all_data->double_infant_total;
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
                    $quad_profit            = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    $grand_profit           += $quad_profit;
                    $grand_cost            += $quad_infant_total_cost;
                    $grand_sale             += $cart_all_data->quad_infant_total;
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
                
                if($cart_all_data->discount_type == 'amount'){
                    $final_profit   = $grand_profit - $cart_all_data->discount_enter_am;
                    $grand_sale     = $grand_sale - $cart_all_data->discount_enter_am;
                }else{
                   $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                   $final_profit = $grand_profit - $discunt_am_over_all;
                   $grand_sale   = $grand_sale - $discunt_am_over_all;
                }
                
                if(isset($cart_all_data->special_discount)){
                    $final_profit   = $grand_profit - $cart_all_data->special_discount;
                    $grand_sale     = $grand_sale - $cart_all_data->special_discount;
                }
                // else{
                //     $final_profit   = $grand_profit;
                // }
                
                $commission_add = '';
                if(isset($cart_all_data->agent_commsion_add_total)){
                    $commission_add = $cart_all_data->agent_commsion_add_total;
                }
                
                $agent_name = $tour_res->agent_name;
                foreach($agent_lists as $agent_res){
                    if($agent_res->id == $tour_res->agent_name){
                        $agent_name = $agent_res->agent_Name;
                    }
                }
                
                $booking_data = [
                        'title'                     => $tour_res->tour_name,
                        'agent_Id'                  => $tour_res->agent_name,
                        'agent_name'                => $agent_name,
                        'invoice_id'                => $tour_res->invoice_no,
                        'booking_id'                => $tour_res->booking_id,
                        'passenger_name'            => $passenger_nameQ[0]->passenger_name,
                        'tour_id'                   => $tour_res->tour_id,
                        'price'                     => $tour_res->tour_total_price,
                        'paid_amount'               => $tour_res->total_paid_amount,
                        'remaing_amount'            => $tour_res->tour_total_price - $tour_res->total_paid_amount,
                        'over_paid_amount'          => $tour_res->over_paid_amount,
                        'tour_name'                 => $cart_all_data->name,
                        'profit'                    => $final_profit,
                        'discount_am'               => $cart_all_data->discount_Price,
                        'special_discount'          => $cart_all_data->special_discount ?? '0',
                        'total_cost'                => $grand_cost,
                        'total_sale'                => $grand_sale,
                        'commission_am'             => $cart_all_data->agent_commsion_am,
                        'agent_commsion_add_total'  => $commission_add,
                        'currency'                  => $tour_res->currency,
                        'created_at'                => $tour_res->created_at
                    ];
                
                array_push($booking_all_data,$booking_data);
            }
            
            foreach($agents_activity_booking as $activity_res){
             
                $tours_costing      = DB::table('new_activites')->where('new_activites.id',$activity_res->tour_id)->first();
                
                $passenger_nameQ    = DB::table('tours_bookings')->where('tours_bookings.id',$activity_res->booking_id)->select('passenger_name')->get();
                
                $cart_all_data      = json_decode($activity_res->cart_total_data);
                $grand_profit       = 0;
                $grand_cost         = 0;
                $grand_sale         = 0;
                
                // dd($tours_costing);
                
                // Profit From Double Adults
                if(isset($cart_all_data) && $cart_all_data->adults > 0 && isset($tours_costing->cost_price)){
                    $double_adult_total_cost    = $tours_costing->cost_price * $cart_all_data->adults;
                    $double_profit              = $cart_all_data->adult_price - $double_adult_total_cost;
                    $grand_profit               += $double_profit;
                    $grand_cost                 += $double_adult_total_cost;
                    $grand_sale                 += $cart_all_data->adult_price;
                }
                
                if(isset($cart_all_data) && $cart_all_data->children > 0 && isset($tours_costing->cost_price)){
                    $double_child_total_cost    = $tours_costing->cost_price * $cart_all_data->children;
                    $double_profit              = $cart_all_data->child_price - $double_child_total_cost;
                    $grand_profit               += $double_profit;
                    $grand_cost                 += $double_child_total_cost;
                    $grand_sale                 += $cart_all_data->child_price;
                }
                
                $agent_id   = '';
                $agent_name = $activity_res->agent_name;
                if($agent_name != null && $agent_name != '' && $agent_name != '-1'){
                    foreach($agent_lists as $agent_res){
                        if($agent_res->id == $activity_res->agent_name){
                            $agent_name = $agent_res->agent_Name;
                            $agent_id   = $activity_res->agent_Name;
                        }
                    }
                }else{
                    foreach($customer_lists as $customer_res){
                        if(isset($tours_costing->customer_id) && $customer_res->id == $tours_costing->customer_id ?? ''){
                            $agent_name = $customer_res->name;
                            if(isset($cart_all_data) && $cart_all_data != null){
                                $agent_id   = $cart_all_data->customer_id ?? '';
                            }else{
                                $agent_id   = $tours_costing->customer_id ?? '';
                            }
                        }
                    }
                }
                
                $booking_data = [
                        'title'                     => $activity_res->tour_name,
                        'agent_Id'                  => $agent_id,
                        'agent_name'                => $agent_name,
                        'invoice_id'                => $activity_res->invoice_no,
                        'booking_id'                => $activity_res->booking_id,
                        'passenger_name'            => $passenger_nameQ[0]->passenger_name,
                        'tour_id'                   => $activity_res->tour_id,
                        'price'                     => $activity_res->tour_total_price,
                        'paid_amount'               => $activity_res->total_paid_amount,
                        'remaing_amount'            => $activity_res->tour_total_price - $activity_res->total_paid_amount,
                        'over_paid_amount'          => $activity_res->over_paid_amount,
                        'tour_name'                 => $tours_costing->title ?? '',
                        'profit'                    => $final_profit ?? '0',
                        'discount_am'               => 0,
                        'special_discount'          => 0,
                        'total_cost'                => $grand_cost,
                        'total_sale'                => $grand_sale,
                        'commission_am'             => 0,
                        'agent_commsion_add_total'  => 0,
                        'currency'                  => $activity_res->currency,
                        'created_at'                => $activity_res->created_at
                    ];
                
                // array_push($booking_all_data,$booking_data);
                
                // dd($booking_data);
            }
            
            $invoices_all_data          = [];
            foreach($agents_invoice_booking as $agent_inv_res){
                $service_type       = '';
                $services = json_decode($agent_inv_res->services);
                if($services[0] == '1'){
                    $service_type   = 'All Services';
                }else if($services[0] == 'accomodation_tab'){
                    $service_type   = 'Hotel';
                }else if($services[0] == 'flight_tab'){
                    $service_type   = 'Flight';
                }else if($services[0] == 'visa_tab'){
                    $service_type   = 'Visa';
                }else if($services[0] == 'transportation_tab'){
                    $service_type   = 'Transfer';
                }else{
                    $service_type   = 'Not Selected';
                }
                
                $invoice_payments   = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                $total_paid_amount  = 0;
                foreach($invoice_payments as $inv_pay_res){
                    $total_paid_amount += $inv_pay_res->amount_Paid;
                }
                
                if($agent_inv_res->total_sale_price_Company != null && $agent_inv_res->total_sale_price_Company != '' && $agent_inv_res->total_sale_price_Company != 'null'){
                    $total_sale_price_Company = $agent_inv_res->total_sale_price_Company;
                }else{
                    $total_sale_price_Company = $agent_inv_res->total_sale_price_all_Services ?? 0;
                }
                
                if($agent_inv_res->total_cost_price_Company != null && $agent_inv_res->total_cost_price_Company != '' && $agent_inv_res->total_cost_price_Company != 'null'){
                    $total_cost_price_Company = $agent_inv_res->total_cost_price_Company;
                }else{
                    $total_cost_price_Company = $agent_inv_res->total_cost_price_all_Services ?? 0;
                }
                
                $inv_single_data = [
                    'agent_Id'          => $agent_inv_res->agent_Id,
                    'agent_name'        => $agent_inv_res->agent_name,
                    'passenger_name'    => $agent_inv_res->f_name,
                    'service_type'      => $service_type,
                    'invoice_id'        => $agent_inv_res->id,
                    'generate_id'       => $agent_inv_res->generate_id,
                    'price'             => $total_sale_price_Company,
                    'paid_amount'       => $agent_inv_res->total_paid_amount,
                    'remaing_amount'    => $total_sale_price_Company - $agent_inv_res->total_paid_amount,
                    'over_paid_amount'  => $agent_inv_res->over_paid_amount,
                    'profit'            => $total_sale_price_Company - $total_cost_price_Company,
                    'total_cost'        => $total_cost_price_Company,
                    'total_sale'        => $total_sale_price_Company,
                    'currency'          => $agent_inv_res->currency_symbol,
                    'created_at'        => $agent_inv_res->created_at
                ];
                array_push($invoices_all_data,$inv_single_data);
                
            }
            
            $other_expense              = 0;
            $expense_data               = expense::join('expense_categories','expenses.category_id','expense_categories.id')
                                            ->join('expense_sub_categories','expenses.sub_category_id','expense_sub_categories.id')->join('cash_accounts', 'cash_accounts.id', '=', 'expenses.account_id')
                                            ->where('expenses.customer_id',$request->customer_id)
                                            ->whereDate('expenses.created_at','>=', $request_Data->start_date_RS)->whereDate('expenses.created_at','<=', $request_Data->end_date_RS)
                                            // ->select('expenses.*','expense_sub_categories.exp_sub_category','expense_categories.exp_category_name','cash_accounts.name','cash_accounts.account_no')
                                            ->orderBy('expenses.id','desc')->get();
            if($expense_data != null && $expense_data != ''){
                foreach($expense_data as $expense_data_value){
                    if(isset($expense_data_value->total_amount) && $expense_data_value->total_amount != null && $expense_data_value->total_amount != '' && $expense_data_value->total_amount > 0){
                        $other_expense = $other_expense + $expense_data_value->total_amount;
                    }
                }
            }
            
            $agent_data = [
                'agents_tour_booking'               => $booking_all_data,
                'agents_invoices_booking'           => $invoices_all_data,
            ];
            array_push($all_agents_data,$agent_data);
            
            $all_agents_list = DB::table('Agents_detail')->where('customer_id',$request->customer_id)
                                // ->whereDate('created_at','>=', $request_Data->start_date_RS)->whereDate('created_at','<=', $request_Data->end_date_RS)
                                ->get();
            // Season
            $season_Id              = '';
            $today_Date             = date('Y-m-d');
            if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
                $season_Id          = 'all_Seasons';
            }elseif(isset($request->season_Id) && $request->season_Id > 0){
                $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
                $season_Id          = $season_SD->id ?? '';
            }else{
                $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
                $season_Id          = $season_SD->id ?? '';
            }
            $season_Details         = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
            if($request->customer_id == 4 || $request->customer_id == 54){
                if(!isset($all_agents_data[0]['agents_tour_booking']) && $all_agents_data[0]['agents_tour_booking']->isEmpty()){
                }else{
                    // dd($all_agents_data[0]['agents_tour_booking']);
                    $all_agents_data[0]['agents_tour_booking'] = $this->revenue_Stream_Season_Working($all_agents_data[0]['agents_tour_booking'],$request);
                    // dd($all_agents_data[0]['agents_tour_booking']);
                }
                
                if(!isset($all_agents_data[0]['agents_invoices_booking']) && $all_agents_data[0]['agents_invoices_booking']->isEmpty()){
                }else{
                    // dd($all_agents_data[0]['agents_invoices_booking']);
                    $all_agents_data[0]['agents_invoices_booking'] = $this->revenue_Stream_Season_Working($all_agents_data[0]['agents_invoices_booking'],$request);
                    // dd($all_agents_data[0]['agents_invoices_booking']);
                }
            }
            // Season
        }
        
        // dd($all_agents_data);
        return response()->json([
            'message'           => 'success',
            'agents_data'       => $all_agents_data,
            'expense_data'      => $expense_data,
            'all_agents_list'   => $all_agents_list,
        ]);
    }
    
    public function booking_financial_stats_month_wise(Request $request){
    // dd($request);
    
    
    
    
    $userData                       = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
    if($userData){
       $year = $request->year;
        
        // Initialize an array to hold the dates
        $monthsWiseRevenue = [];
        
        // Loop through all months
        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
            $monthName = Carbon::create($year, $month, 1)->format('M');
            
            $monthRevenue = $this->get_revenue_with_date_wise($startOfMonth,$endOfMonth,$request->customer_id);
            
            $monthsWiseRevenue[] = [
                'year' => $year,
                'month' => $month,
                'monthName' => $monthName,
                'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
                'revenue' => $monthRevenue
            ];
        }
        
        
        $currentMonth = Carbon::now()->month;

        // Slice the array to get the last 6 months, including the current month
        $startIndex = max(0, $currentMonth - 6); // Prevent index from being negative
        $lastSixMonthsRevenue = array_slice($monthsWiseRevenue, $startIndex, 6);
    }
    
    // dd($all_agents_data);
    return response()->json([
        'message'           => 'success',
        'monthsWiseRevenue'       => $lastSixMonthsRevenue,
    ]);
}

public function get_revenue_with_date_wise($startDate,$endDate,$customer_id){
     
     $tour_booking        = DB::table('cart_details')->where('client_id',$customer_id)
                                     ->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=', $endDate)
                                     ->where('pakage_type','tour')->get();
     $agents_activity_booking    = DB::table('cart_details')->where('client_id',$customer_id)
                                     ->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=', $endDate)
                                     ->where('pakage_type','activity')->get();
     $agents_invoice_booking     = DB::table('add_manage_invoices')->where('customer_id',$customer_id)
                                     ->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=', $endDate)
                                     ->select('total_cost_price_Company','total_sale_price_Company','f_name','generate_id','created_at','services','agent_Id','agent_name','more_visa_details','total_sale_price_all_Services','total_cost_price_all_Services','total_paid_amount','over_paid_amount','accomodation_details','accomodation_details_more','markup_details','more_markup_details','no_of_pax_days','id','agent_Id','currency_symbol','visa_Pax')->get();
    

     $all_agents_data            = [];
     
     // dd($agents_invoice_booking);
     $booking_all_data           = [];

     $totalProfit = 0;
     $profitBreakDown = [];
     $packagesProfit = 0;
     foreach($tour_booking as $tour_res){
      
         $tours_costing      = DB::table('tours_2')
                                 ->join('tours','tours_2.tour_id','tours.id')
                                 ->where('tours_2.tour_id',$tour_res->tour_id)
                                 ->select('tours.created_at','tours.title','tours_2.quad_cost_price','tours_2.triple_cost_price','tours_2.double_cost_price','tours_2.without_acc_cost_price','tours_2.child_grand_total_cost_price','tours_2.infant_total_cost_price')->first();
         
         $passenger_nameQ    = DB::table('tours_bookings')
                                 ->where('tours_bookings.id',$tour_res->booking_id)
                                 ->select('passenger_name')
                                 ->get();
         
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
             $double_infant_total_cost   = $tours_costing->double_cost_price * $cart_all_data->double_infant;
             $double_profit              = $cart_all_data->double_infant_total - $double_infant_total_cost;
             $grand_profit               += $double_profit;
             $grand_cost                 += $double_infant_total_cost;
             $grand_sale                 += $cart_all_data->double_infant_total;
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
             $quad_profit            = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
             $grand_profit           += $quad_profit;
             $grand_cost            += $quad_infant_total_cost;
             $grand_sale             += $cart_all_data->quad_infant_total;
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
         
         if($cart_all_data->discount_type == 'amount'){
             $final_profit   = $grand_profit - $cart_all_data->discount_enter_am;
             $grand_sale     = $grand_sale - $cart_all_data->discount_enter_am;
         }else{
            $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
            $final_profit = $grand_profit - $discunt_am_over_all;
            $grand_sale   = $grand_sale - $discunt_am_over_all;
         }
         
         if(isset($cart_all_data->special_discount)){
             $final_profit   = $grand_profit - $cart_all_data->special_discount;
             $grand_sale     = $grand_sale - $cart_all_data->special_discount;
         }
         // else{
         //     $final_profit   = $grand_profit;
         // }
         
         $commission_add = '';
         if(isset($cart_all_data->agent_commsion_add_total)){
             $commission_add = $cart_all_data->agent_commsion_add_total;
         }

         $packagesProfit += $final_profit;
         
     }

     $profitBreakDown['packagesProfit'] = $packagesProfit;
     $totalProfit += $packagesProfit;
     
     $activityProfit = 0;
     foreach($agents_activity_booking as $activity_res){
      
         $tours_costing      = DB::table('new_activites')->where('new_activites.id',$activity_res->tour_id)->first();
         
         $passenger_nameQ    = DB::table('tours_bookings')->where('tours_bookings.id',$activity_res->booking_id)->select('passenger_name')->get();
         
         $cart_all_data      = json_decode($activity_res->cart_total_data);
         $grand_profit       = 0;
         $grand_cost         = 0;
         $grand_sale         = 0;
         
         // dd($tours_costing);
         
         // Profit From Double Adults
         if(isset($cart_all_data) && $cart_all_data->adults > 0 && isset($tours_costing->cost_price)){
             $double_adult_total_cost    = $tours_costing->cost_price * $cart_all_data->adults;
             $double_profit              = $cart_all_data->adult_price - $double_adult_total_cost;
             $grand_profit               += $double_profit;
             $grand_cost                 += $double_adult_total_cost;
             $grand_sale                 += $cart_all_data->adult_price;
         }
         
         if(isset($cart_all_data) && $cart_all_data->children > 0 && isset($tours_costing->cost_price)){
             $double_child_total_cost    = $tours_costing->cost_price * $cart_all_data->children;
             $double_profit              = $cart_all_data->child_price - $double_child_total_cost;
             $grand_profit               += $double_profit;
             $grand_cost                 += $double_child_total_cost;
             $grand_sale                 += $cart_all_data->child_price;
         }
         
         $activityProfit += $grand_profit;

     }

     $profitBreakDown['activityProfit'] = $activityProfit;
     $totalProfit += $activityProfit;
     
     $invoicesProfilt = 0;
     foreach($agents_invoice_booking as $agent_inv_res){
         $service_type       = '';
         $services = json_decode($agent_inv_res->services);
         if($services[0] == '1'){
             $service_type   = 'All Services';
         }else if($services[0] == 'accomodation_tab'){
             $service_type   = 'Hotel';
         }else if($services[0] == 'flight_tab'){
             $service_type   = 'Flight';
         }else if($services[0] == 'visa_tab'){
             $service_type   = 'Visa';
         }else if($services[0] == 'transportation_tab'){
             $service_type   = 'Transfer';
         }else{
             $service_type   = 'Not Selected';
         }
         
         $invoice_payments   = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
         $total_paid_amount  = 0;
         foreach($invoice_payments as $inv_pay_res){
             $total_paid_amount += $inv_pay_res->amount_Paid;
         }
         
         if($agent_inv_res->total_sale_price_Company != null && $agent_inv_res->total_sale_price_Company != '' && $agent_inv_res->total_sale_price_Company != 'null'){
             $total_sale_price_Company = $agent_inv_res->total_sale_price_Company;
         }else{
             $total_sale_price_Company = $agent_inv_res->total_sale_price_all_Services ?? 0;
         }
         
         if($agent_inv_res->total_cost_price_Company != null && $agent_inv_res->total_cost_price_Company != '' && $agent_inv_res->total_cost_price_Company != 'null'){
             $total_cost_price_Company = $agent_inv_res->total_cost_price_Company;
         }else{
             $total_cost_price_Company = $agent_inv_res->total_cost_price_all_Services ?? 0;
         }

         $invoicesProfilt += ($total_sale_price_Company - $total_cost_price_Company);                
     }

     $profitBreakDown['invoicesProfilt'] = $invoicesProfilt;    
     $totalProfit += $invoicesProfilt;

     $profitBreakDown['totalProfit'] = $totalProfit;
     return $profitBreakDown;
}
    
    
    public function all_cost_stats(Request $request){
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $tour_booking       = DB::table('cart_details')->where('client_id',$request->customer_id)->where('pakage_type','tour')->get();
            $invoice_booking    = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id)->select('generate_id','created_at','services','agent_Id','agent_name','more_visa_details','total_sale_price_all_Services','total_cost_price_all_Services','total_paid_amount','over_paid_amount','accomodation_details','accomodation_details_more','markup_details','more_markup_details','no_of_pax_days','id','agent_Id','currency_symbol','visa_Pax')->get();
            $all_visa_types     = DB::table('visa_types')->where('customer_id',$request->customer_id)->get();
            
            $booking_all_data = [];
            $pack_inv_acc_cost_arr = [];
            $pack_inv_flight_cost_arr = [];
            foreach($tour_booking as $book_res){
                
                $cart_total_data = json_decode($book_res->cart_total_data);
                $cart_visa_data = json_decode($book_res->visa_change_data);
             
                $tours_details = DB::table('tours_2')->join('tours','tours_2.tour_id','tours.id')->where('tours_2.tour_id',$book_res->tour_id)->select('tours.*','tours.id as tour_id','tours_2.*')->first();
                
                $accomodation_details       = json_decode($tours_details->accomodation_details);
                $accomodation_details_more  = json_decode($tours_details->accomodation_details_more);
                
                if(isset($accomodation_details)){
                    foreach($accomodation_details as $acc_res){
                    
                    $booked_rooms = 0;
                    if($acc_res->acc_type == 'Double'){
                        $booked_rooms = $cart_total_data->double_rooms;
                    }
                    
                    if($acc_res->acc_type == 'Triple'){
                        $booked_rooms = $cart_total_data->triple_rooms;
                    }
                    
                    if($acc_res->acc_type == 'Quad'){
                        $booked_rooms = $cart_total_data->quad_rooms;
                    }
                    
                    $supplier_name = 'Manual Entry';
                    if(isset($acc_res->hotel_supplier_id)){
                        $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$acc_res->hotel_supplier_id)->select('room_supplier_name')->first();
                        $supplier_name = $supplier_data->room_supplier_name ?? '';
                    }
                    
                    $single_inv = [
                            'invoice_no' => $book_res->invoice_no,
                            'invoice_total' => $book_res->tour_total_price,
                            'acc_purchase_price' => $acc_res->price_per_room_purchase,
                            'acc_sale_price' => $acc_res->price_per_room_sale,
                            'exchange_rate_price' => $acc_res->exchange_rate_price,
                            'no_of_nights' => $acc_res->acc_no_of_nightst,
                            'acc_check_in' => $acc_res->acc_check_in,
                            'acc_check_out' => $acc_res->acc_check_out,
                            'hotel_supplier_id'=>$acc_res->hotel_supplier_id,
                            'supplier_name'=>$supplier_name,
                            'hotel_name'=>$acc_res->acc_hotel_name,
                            'room_id' => $acc_res->hotelRoom_type_id,
                            'room_type' => $acc_res->acc_type,
                            'booked_rooms' => $booked_rooms,
                            'rooms_total_price_purchase' => ($acc_res->price_per_room_purchase * $acc_res->acc_no_of_nightst) * $booked_rooms,
                            'rooms_total_price_sale' => ($acc_res->price_per_room_sale * $acc_res->acc_no_of_nightst) * $booked_rooms,
                        ];
                        
                    array_push($pack_inv_acc_cost_arr,$single_inv);
                }
                }
                
                if(isset($accomodation_details_more)){
                    foreach($accomodation_details_more as $acc_res){
                    
                        $booked_rooms = 0;
                        if($acc_res->more_acc_type == 'Double'){
                            $booked_rooms = $cart_total_data->double_rooms;
                        }
                        
                        if($acc_res->more_acc_type == 'Triple'){
                            $booked_rooms = $cart_total_data->triple_rooms;
                        }
                        
                        if($acc_res->more_acc_type == 'Quad'){
                            $booked_rooms = $cart_total_data->quad_rooms;
                        }
                        
                        $supplier_name = 'Manual Entry';
                        if(isset($acc_res->more_hotel_supplier_id)){
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$acc_res->more_hotel_supplier_id)->select('room_supplier_name')->first();
                            $supplier_name = $supplier_data->room_supplier_name ?? '';
                        }
                        
                        $single_inv = [
                                'invoice_no' => $book_res->invoice_no,
                                'invoice_total' => $book_res->tour_total_price,
                                'acc_purchase_price' => $acc_res->more_price_per_room_purchase,
                                'acc_sale_price' => $acc_res->more_price_per_room_sale,
                                'exchange_rate_price' => $acc_res->more_exchange_rate_price,
                                'no_of_nights' => $acc_res->more_acc_no_of_nightst,
                                'acc_check_in' => $acc_res->more_acc_check_in,
                                'acc_check_out' => $acc_res->more_acc_check_out,
                                'hotel_supplier_id'=>$acc_res->more_hotel_supplier_id,
                                'supplier_name'=>$supplier_name,
                                'hotel_name'=>$acc_res->more_acc_hotel_name,
                                'room_id' => $acc_res->more_hotelRoom_type_id,
                                'room_type' => $acc_res->more_acc_type,
                                'booked_rooms' => $booked_rooms,
                                'rooms_total_price_purchase' => ($acc_res->more_price_per_room_purchase * $acc_res->more_acc_no_of_nightst) * $booked_rooms,
                                'rooms_total_price_sale' => ($acc_res->more_price_per_room_sale * $acc_res->more_acc_no_of_nightst) * $booked_rooms,
                            ];
                            
                        array_push($pack_inv_acc_cost_arr,$single_inv);
                    }
                }
                
                // Calculate Flight Cost
                
                $markup_details = json_decode($tours_details->markup_details);
                
                $adult_flight_price     = 0;
                $child_flight_price     = $tours_details->child_flight_cost_price;
                $infant_flight_price    = $tours_details->infant_flight_cost;
                
                if(isset($markup_details)){
                    foreach($markup_details as $markup_res){
                        if($markup_res->markup_Type_Costing == 'flight_Type_Costing'){
                            $adult_flight_price = $markup_res->without_markup_price;
                        }
                    }
                }
                
                if($adult_flight_price > 0){
                    // $pack_inv_flight_cost_arr
                    
                        $supplier_name = 'Manual Entry';
                        if(isset($tours_details->flight_supplier)){
                            $supplier_data = DB::table('supplier')->where('id',$tours_details->flight_supplier)->select('companyname')->first();
                            $supplier_name = $supplier_data->companyname;
                        }
                        
                        $total_adutls = $cart_total_data->double_adults + $cart_total_data->triple_adults + $cart_total_data->quad_adults + $cart_total_data->without_acc_adults;
                        $total_childs = $cart_total_data->double_childs + $cart_total_data->triple_childs + $cart_total_data->quad_childs + $cart_total_data->children;
                        $total_infants = $cart_total_data->double_infant + $cart_total_data->triple_infant + $cart_total_data->quad_infant + $cart_total_data->infants;
                        

                     $single_inv = [
                                'invoice_no' => $book_res->invoice_no,
                                'invoice_total' => $book_res->tour_total_price,
                                'adult_flight_cost' => $adult_flight_price,
                                'child_flight_cost' => $child_flight_price,
                                'infant_flight_cost' => $infant_flight_price,
                                'flight_supplier_id'=>$tours_details->flight_supplier,
                                'supplier_name'=>$supplier_name,
                                'route_id' => $tours_details->flight_route_id_occupied,
                                'total_adutls' => $total_adutls,
                                'total_childs' => $total_childs,
                                'total_infants' => $total_infants,
                                'adults_total_price' => $total_adutls * $adult_flight_price,
                                'child_total_price' => $total_childs * $child_flight_price,
                                'infant_total_price' => $total_infants * $infant_flight_price,
                            ];
                            
                    array_push($pack_inv_flight_cost_arr,$single_inv);
                }
                
                // Calculate Visa Cost
                
                $package_actual_visa_type = '';
                $package_actual_visa_price = 0;
                if(isset($markup_details)){
                    foreach($markup_details as $markup_res){
                        if($markup_res->markup_Type_Costing == 'visa_Type_Costing'){
                            $package_actual_visa_type = $markup_res->hotel_name_markup;
                            $package_actual_visa_price = $markup_res->without_markup_price;
                        }
                    }
                }
                
                // Step 1 : Loop On all Visa Types
                
                $visa_type_cost_arr = [];
                
                foreach($all_visa_types as $visa_type){
                    $type_total_cost = 0;    
                    $change_double = 0;
                    if($cart_visa_data->double_adult_visa_persons > 0){
                        $change_double = $cart_visa_data->double_adult_visa_persons;
                                if($visa_type->other_visa_type == $cart_visa_data->double_adult_visa_type){
                                    
                                    $type_total_cost += $cart_visa_data->double_adult_visa_persons * $cart_visa_data->visa_actual_price_change_triple;
                                }
                    }
                            
                    if($visa_type->other_visa_type == $package_actual_visa_type){
                         // Calaculate Actual Visa Person
                         
                           $without_change_visa_double_adult = $cart_total_data->double_adults - $change_double;
                           $type_total_cost += $without_change_visa_double_adult * $package_actual_visa_price;
                    }
                }
                
                // Calcualte one Type Cost
            }
            
            dd($pack_inv_flight_cost_arr);
        }
    }
    public function arrival_listing(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $all_listing                = [];
            $arrival_listing_data       = [];
            $agent_lists                = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
            $customer_lists             = DB::table('booking_customers')->where('customer_id',$request->customer_id)->get();
            $agents_tour_booking        = DB::table('cart_details')->where('client_id',$request->customer_id)->where('pakage_type','tour')->select('tour_id','booking_id','invoice_no','cart_total_data','currency','created_at','confirm')->get();
            $agents_invoice_booking     = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id)->select('id','generate_id','start_date','end_date','booking_customer_id','created_at','services','confirm','accomodation_details','accomodation_details_more','agent_Id','currency_symbol')->get();
            $hotel_booking_detail       = DB::table('hotels_bookings')->where('provider','!=',NULL)->select('id','invoice_no','reservation_response','lead_passenger','created_at','customer_id','provider')->get();
            // ->where('customer_id',$request->customer_id)
            
            foreach($agents_tour_booking as $tour_res){
                $agent_customer_id      = '';
                $agent_customer_name    = '';
                $client_type            = '';
                $service_type           = 'Package';
                $cart_all_data          = json_decode($tour_res->cart_total_data);
                
                $tours_costing          = DB::table('tours_2')->join('tours','tours_2.tour_id','tours.id')->where('tours.customer_id',$request->customer_id)->where('tours_2.tour_id',$tour_res->tour_id)->select('tours.start_date','tours.end_date','tours.created_at','accomodation_details','accomodation_details_more')->first();
                $passenger_nameQ        = DB::table('tours_bookings')->where('customer_id',$request->customer_id)->where('id',$tour_res->booking_id)->select('passenger_name')->get();
                
                if(isset($cart_all_data) && $cart_all_data != null && $cart_all_data != ''){
                    if(isset($cart_all_data->customer_id) && $cart_all_data->customer_id != null && $cart_all_data->customer_id != '' && $cart_all_data->customer_id != '-1'){
                        foreach($customer_lists as $customer_lists_val){
                            if($customer_lists_val->id == $cart_all_data->customer_id){
                                $agent_customer_id      = $customer_lists_val->id;
                                $client_type            = 'Customer';
                            }
                        }
                    }
                    else{
                        if(isset($cart_all_data->agent_name) && $cart_all_data->agent_name != null && $cart_all_data->agent_name != '' && $cart_all_data->agent_name != '-1'){
                            foreach($agent_lists as $agent_lists_val){
                                if($agent_lists_val->id == $cart_all_data->agent_name){
                                    $agent_customer_id  = $agent_lists_val->id;
                                    $client_type        = 'Agent';
                                }
                            }
                        }
                    }
                }
                
                if($tour_res->confirm == 1){
                    $confirm = 'CONFIRMED';
                }else{
                    $confirm = 'TENTATIVE';
                }
                
                $accomodation_details   = json_decode($tours_costing->accomodation_details);
                if($accomodation_details != null && $accomodation_details != ''){
                    foreach($accomodation_details as $accomodation_details_val){
                        $listing_data = [
                            'invoice_id'                => $tour_res->booking_id,
                            'ref_number'                => $tour_res->invoice_no,
                            'agent_customer_id'         => $agent_customer_id,
                            'agent_customer_name'       => $passenger_nameQ[0]->passenger_name,
                            'service_type'              => $service_type,
                            'client_type'               => $client_type,
                            'check_in'                  => $accomodation_details_val->acc_check_in,
                            'check_out'                 => $accomodation_details_val->acc_check_out,
                            'city_name'                 => $accomodation_details_val->hotel_city_name,
                            'hotel_name'                => $accomodation_details_val->acc_hotel_name,
                            'room_type'                 => $accomodation_details_val->acc_type,
                            'total_pax'                 => $accomodation_details_val->acc_pax,
                            'currency'                  => $tour_res->currency,
                            'booking_date'              => $tour_res->created_at,
                            'status'                    => $confirm,
                        ];
                        array_push($all_listing,$listing_data);
                        
                        $accomodation_details_more  = json_decode($tours_costing->accomodation_details_more);
                        if($accomodation_details_more != null && $accomodation_details_more != ''){
                            foreach($accomodation_details_more as $accomodation_details_more_val){
                                if($accomodation_details_val->hotel_city_name == $accomodation_details_more_val->more_hotel_city){
                                    $listing_data = [
                                        'invoice_id'                => $tour_res->booking_id,
                                        'ref_number'                => $tour_res->invoice_no,
                                        'agent_customer_id'         => $agent_customer_id,
                                        'agent_customer_name'       => $passenger_nameQ[0]->passenger_name,
                                        'service_type'              => $service_type,
                                        'client_type'               => $client_type,
                                        'check_in'                  => $accomodation_details_more_val->more_acc_check_in,
                                        'check_out'                 => $accomodation_details_more_val->more_acc_check_out,
                                        'city_name'                 => $accomodation_details_more_val->more_hotel_city,
                                        'hotel_name'                => $accomodation_details_more_val->more_acc_hotel_name,
                                        'room_type'                 => $accomodation_details_more_val->more_acc_type,
                                        'total_pax'                 => $accomodation_details_more_val->more_acc_pax,
                                        'currency'                  => $tour_res->currency,
                                        'booking_date'              => $tour_res->created_at,
                                        'status'                    => $confirm,
                                    ];
                                    array_push($all_listing,$listing_data);
                                }
                            }
                        }
                    }
                }
            }
            
            foreach($agents_invoice_booking as $agent_inv_res){
                $service_type   = '';
                $services       = json_decode($agent_inv_res->services);
                if($services[0] == '1'){
                    $service_type = 'All Services';
                }else if($services[0] == 'accomodation_tab'){
                    $service_type = 'Hotel';
                }else{
                    $service_type = 'Not Selected';
                }
                
                if($service_type == 'All Services' || $service_type == 'Hotel'){
                    $agent_customer_id      = '';
                    $agent_customer_name    = '';
                    $client_type            = '';
                    $service_type           = 'Invoice';
                    
                    if($agent_inv_res->booking_customer_id != null && $agent_inv_res->booking_customer_id != '' && $agent_inv_res->booking_customer_id != '-1'){
                        foreach($customer_lists as $customer_lists_val){
                            if($customer_lists_val->id == $agent_inv_res->booking_customer_id){
                                $agent_customer_id      = $customer_lists_val->id;
                                $agent_customer_name    = $customer_lists_val->name;
                                $client_type            = 'Customer';
                            }
                        }
                    }else{
                        if($agent_inv_res->agent_Id != null && $agent_inv_res->agent_Id != '' && $agent_inv_res->agent_Id != '-1'){
                            foreach($agent_lists as $agent_lists_val){
                                if($agent_lists_val->id == $agent_inv_res->agent_Id){
                                    $agent_customer_id      = $agent_lists_val->id;
                                    $agent_customer_name    = $agent_lists_val->agent_Name;
                                    $client_type            = 'Agent';
                                }
                            }
                        }
                    }
                    
                    if($agent_inv_res->confirm == 1){
                        $confirm = 'CONFIRMED';
                    }else{
                        $confirm = 'TENTATIVE';
                    }
                    
                    $accomodation_details   = json_decode($agent_inv_res->accomodation_details);
                    if($accomodation_details != null && $accomodation_details != ''){
                        foreach($accomodation_details as $accomodation_details_val){
                            $listing_data = [
                                'invoice_id'                => $agent_inv_res->id,
                                'ref_number'                => $agent_inv_res->generate_id,
                                'agent_customer_id'         => $agent_customer_id,
                                'agent_customer_name'       => $agent_customer_name,
                                'service_type'              => $service_type,
                                'client_type'               => $client_type,
                                'check_in'                  => $accomodation_details_val->acc_check_in,
                                'check_out'                 => $accomodation_details_val->acc_check_out,
                                'city_name'                 => $accomodation_details_val->hotel_city_name,
                                'hotel_name'                => $accomodation_details_val->acc_hotel_name,
                                'room_type'                 => $accomodation_details_val->acc_type,
                                'total_pax'                 => $accomodation_details_val->acc_pax,
                                'currency'                  => $agent_inv_res->currency_symbol,
                                'booking_date'              => $agent_inv_res->created_at,
                                'status'                    => $confirm,
                            ];
                            array_push($all_listing,$listing_data);
                            
                            $accomodation_details_more  = json_decode($agent_inv_res->accomodation_details_more);
                            if($accomodation_details_more != null && $accomodation_details_more != ''){
                                foreach($accomodation_details_more as $accomodation_details_more_val){
                                    if($accomodation_details_val->hotel_city_name == $accomodation_details_more_val->more_hotel_city){
                                        $listing_data = [
                                            'invoice_id'                => $agent_inv_res->id,
                                            'ref_number'                => $agent_inv_res->generate_id,
                                            'agent_customer_id'         => $agent_customer_id,
                                            'agent_customer_name'       => $agent_customer_name,
                                            'service_type'              => $service_type,
                                            'client_type'               => $client_type,
                                            'check_in'                  => $accomodation_details_more_val->more_acc_check_in,
                                            'check_out'                 => $accomodation_details_more_val->more_acc_check_out,
                                            'city_name'                 => $accomodation_details_more_val->more_hotel_city,
                                            'hotel_name'                => $accomodation_details_more_val->more_acc_hotel_name,
                                            'room_type'                 => $accomodation_details_more_val->more_acc_type,
                                            'total_pax'                 => $accomodation_details_more_val->more_acc_pax,
                                            'currency'                  => $agent_inv_res->currency_symbol,
                                            'booking_date'              => $agent_inv_res->created_at,
                                            'status'                    => $confirm,
                                        ];
                                        array_push($all_listing,$listing_data);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            foreach($hotel_booking_detail as $hotel_booking_detail_res){
                $reservation_response = json_decode($hotel_booking_detail_res->reservation_response);
                if(isset($reservation_response->hotel_details)){
                    if($hotel_booking_detail_res->provider == 'Custome_hotel'){
                        $client_type    = 'Custom Hotel';
                        $provider_name  = 'Custom Hotel';
                    }else{
                        $client_type    = '3rd Party Hotels';
                        $provider_name  = $hotel_booking_detail_res->provider;
                    }
                    
                    $checkIn            = $reservation_response->hotel_details->checkIn;
                    $checkOut           = $reservation_response->hotel_details->checkOut;
                    $hotel_name         = $reservation_response->hotel_details->hotel_name;
                    $destinationName    = $reservation_response->hotel_details->destinationName;
                    $status             = $reservation_response->status;
                    
                    if($status == 'Cancelled'){
                        $status = 'CANCELLED';
                    }else if($status == 'Confirmed' || $status == 'CONFIRMED'){
                        $status = 'CONFIRMED';
                    }else{
                        $status = 'TENTATIVE';
                    }
                    
                    if(isset($reservation_response->hotel_details->rooms)){
                        $rooms = $reservation_response->hotel_details->rooms;
                        foreach($rooms as $rooms_val){
                            $room_rates = $rooms_val->room_rates[0];
                            $adults     = $room_rates->adults;
                            $children   = $room_rates->children;
                            $total_pax  = $adults + $children;
                            $listing_data = [
                                'invoice_id'                => $hotel_booking_detail_res->id,
                                'ref_number'                => $hotel_booking_detail_res->invoice_no,
                                'agent_customer_id'         => $hotel_booking_detail_res->customer_id,
                                'agent_customer_name'       => $hotel_booking_detail_res->lead_passenger,
                                'service_type'              => 'Website',
                                'client_type'               => $client_type,
                                'provider_name'             => $provider_name,
                                'check_in'                  => $checkIn,
                                'check_out'                 => $checkOut,
                                'city_name'                 => $destinationName,
                                'hotel_name'                => $hotel_name,
                                'room_type'                 => $rooms_val->room_name,
                                'total_pax'                 => $total_pax,
                                'booking_date'              => $hotel_booking_detail_res->created_at,
                                'status'                    => $status,
                            ];
                            array_push($all_listing,$listing_data);
                        }
                    }
                }
            }
            
            $arrival_listing = [
                'all_listing' => $all_listing,
            ];
            array_push($arrival_listing_data,$arrival_listing);
        }
        return response()->json([
            'message'               => 'success',
            'arrival_listing_data'  => $arrival_listing_data,
        ]);
    }
    public function transfer_arrival_list(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status','Auth_key')->first();
        if($userData){
            $countries                  = DB::table('countries')->get();
            $transfer_supplier_lists    = DB::table('transfer_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
            $agent_lists                = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
            $customer_lists             = DB::table('booking_customers')->where('customer_id',$request->customer_id)->get();
            $b2b_agent_lists            = DB::table('b2b_agents')->where('token',$userData->Auth_key)->get();
        }
        return response()->json([
            'message'                   => 'success',
            'all_countries'             => $countries,
            'suppliers_list'            => $transfer_supplier_lists,
            'all_agents'                => $agent_lists,
            'customer_lists'            => $customer_lists,
            'b2b_agent_lists'           => $b2b_agent_lists
        ]);
    }
    public function transfer_arrival_filter(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status','Auth_key')->first();
        if($userData){
            
            $request_data               = json_decode($request->request_data);
            $all_listing                = [];
            $arrival_listing_data       = [];
            $agent_customer_id          = '';
            $transfer_supplier_lists    = DB::table('transfer_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
            $agent_lists                = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
            $customer_lists             = DB::table('booking_customers')->where('customer_id',$request->customer_id)->get();
            $b2b_agent_lists            = DB::table('b2b_agents')->where('token',$userData->Auth_key)->get();
            
            if($request_data->supplier == 'all'){
                if(isset($request_data->searchBy)){
                    if($request_data->searchBy == 'B2BAgent'){
                        if($request_data->b2b_Agent_Id == 'all_B2B_Agent'){
                            $agents_tour_booking        = DB::table('cart_details')->where('client_id',$request->customer_id)->where('pakage_type','tour')->where('b2b_agent_name', '!=', NULL)->select('tour_id','b2b_agent_name','booking_id','invoice_no','cart_total_data','currency','created_at','confirm')->get();
                            $agents_invoice_booking     = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id)->where('b2b_Agent_Id', '!=', NULL)->select('id','b2b_Agent_Id','generate_id','start_date','end_date','created_at','services','confirm','transportation_details','transportation_details_more','currency_symbol')->get();
                        }elseif($request_data->b2b_Agent_Id != 'all_B2B_Agent'){
                            $agents_tour_booking        =   DB::table('cart_details')->where('client_id',$request->customer_id)->where('pakage_type','tour')->where('b2b_agent_name',$request_data->b2b_Agent_Id)->select('tour_id','booking_id','invoice_no','cart_total_data','currency','created_at','confirm')->get();
                            $agents_invoice_booking     =   DB::table('add_manage_invoices')->where('b2b_Agent_Id',$request_data->b2b_Agent_Id)->where('customer_id',$request->customer_id)->select('id','b2b_Agent_Id','generate_id','start_date','end_date','created_at','services','confirm','transportation_details','transportation_details_more','currency_symbol')->get();
                        }
                    }else{
                        if($request_data->agent_Id == 'all_agent'){
                            $agents_tour_booking        = DB::table('cart_details')->where('client_id',$request->customer_id)->where('pakage_type','tour')->select('tour_id','booking_id','invoice_no','cart_total_data','currency','created_at','confirm')->get();
                            $agents_invoice_booking     = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id)->select('id','generate_id','start_date','end_date','booking_customer_id','created_at','services','confirm','transportation_details','transportation_details_more','agent_Id','currency_symbol')->get();
                        }elseif($request_data->agent_Id != 'all_agent'){
                            $agents_tour_booking        =   DB::table('cart_details')->where('client_id',$request->customer_id)->where('pakage_type','tour')->where('agent_name',$request_data->agent_Id)->select('tour_id','booking_id','invoice_no','cart_total_data','currency','created_at','confirm')->get();
                            $agents_invoice_booking     =   DB::table('add_manage_invoices')->where('agent_Id',$request_data->agent_Id)->where('customer_id',$request->customer_id)->select('id','generate_id','start_date','end_date','booking_customer_id','created_at','services','confirm','transportation_details','transportation_details_more','agent_Id','currency_symbol')->get();
                        }
                    }
                }else{
                    if($request_data->agent_Id == 'all_agent'){
                        $agents_tour_booking        = DB::table('cart_details')->where('client_id',$request->customer_id)->where('pakage_type','tour')->select('tour_id','booking_id','invoice_no','cart_total_data','currency','created_at','confirm')->get();
                        $agents_invoice_booking     = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id)->select('id','generate_id','start_date','end_date','booking_customer_id','created_at','services','confirm','transportation_details','transportation_details_more','agent_Id','currency_symbol')->get();
                    }elseif($request_data->agent_Id != 'all_agent'){
                        $agents_tour_booking        =   DB::table('cart_details')->where('client_id',$request->customer_id)->where('pakage_type','tour')->where('agent_name',$request_data->agent_Id)->select('tour_id','booking_id','invoice_no','cart_total_data','currency','created_at','confirm')->get();
                        $agents_invoice_booking     =   DB::table('add_manage_invoices')->where('agent_Id',$request_data->agent_Id)->where('customer_id',$request->customer_id)->select('id','generate_id','start_date','end_date','booking_customer_id','created_at','services','confirm','transportation_details','transportation_details_more','agent_Id','currency_symbol')->get();
                    }
                }
            }
            
            if($request_data->supplier != 'all'){
                if(isset($request_data->searchBy)){
                    if($request_data->searchBy == 'B2BAgent'){
                        if($request_data->b2b_Agent_Id == 'all_B2B_Agent'){
                            $agents_tour_booking    =   DB::table('cart_details')->where('cart_details.b2b_agent_name', '!=', NULL)->where('cart_details.client_id',$request->customer_id)->where('cart_details.pakage_type','tour')
                                                                ->join('tours_2', function ($join) use ($request_data) {
                                                                    $join->on('tours_2.tour_id', '=', 'cart_details.tour_id')->where('transfer_supplier_id', $request_data->supplier);
                                                                })->select('cart_details.tour_id','cart_details.b2b_agent_name','cart_details.booking_id','cart_details.invoice_no','cart_details.cart_total_data','cart_details.currency','cart_details.created_at','cart_details.confirm')->get();
                            $agents_invoice_booking =   DB::table('add_manage_invoices')->where('b2b_Agent_Id', '!=', NULL)->where('transfer_supplier_id',$request_data->supplier)->where('customer_id',$request->customer_id)->select('id','b2b_Agent_Id','generate_id','start_date','end_date','created_at','services','confirm','transportation_details','transportation_details_more','currency_symbol')->get();
                        }elseif($request_data->b2b_Agent_Id != 'all_B2B_Agent'){
                            $agents_tour_booking    =   DB::table('cart_details')->where('cart_details.b2b_agent_name',$request_data->b2b_Agent_Id)->where('cart_details.pakage_type','tour')
                                                                ->join('tours_2', function ($join) use ($request_data) {
                                                                    $join->on('tours_2.tour_id', '=', 'cart_details.tour_id')
                                                                        ->where('transfer_supplier_id', $request_data->supplier);
                                                                })->select('cart_details.tour_id','cart_details.b2b_agent_name','cart_details.booking_id','cart_details.invoice_no','cart_details.cart_total_data','cart_details.currency','cart_details.created_at','cart_details.confirm')->get();
                            $agents_invoice_booking =   DB::table('add_manage_invoices')->where('b2b_Agent_Id',$request_data->b2b_Agent_Id)->where('transfer_supplier_id',$request_data->supplier)->where('customer_id',$request->customer_id)->select('id','b2b_Agent_Id','generate_id','start_date','end_date','created_at','services','confirm','transportation_details','transportation_details_more','currency_symbol')->get();
                            
                        }
                    }else{
                        if($request_data->agent_Id == 'all_agent'){
                            $agents_tour_booking    =   DB::table('cart_details')->where('cart_details.client_id',$request->customer_id)->where('cart_details.pakage_type','tour')
                                                                ->join('tours_2', function ($join) use ($request_data) {
                                                                    $join->on('tours_2.tour_id', '=', 'cart_details.tour_id')->where('transfer_supplier_id', $request_data->supplier);
                                                                })->select('cart_details.tour_id','cart_details.booking_id','cart_details.invoice_no','cart_details.cart_total_data','cart_details.currency','cart_details.created_at','cart_details.confirm')->get();
                            $agents_invoice_booking =   DB::table('add_manage_invoices')->where('transfer_supplier_id',$request_data->supplier)->where('customer_id',$request->customer_id)->select('id','generate_id','start_date','end_date','booking_customer_id','created_at','services','confirm','transportation_details','transportation_details_more','agent_Id','currency_symbol')->get();
                        }elseif($request_data->agent_Id != 'all_agent'){
                            $agents_tour_booking    =   DB::table('cart_details')->where('agent_name',$request_data->agent_Id)->where('cart_details.pakage_type','tour')
                                                                ->join('tours_2', function ($join) use ($request_data) {
                                                                    $join->on('tours_2.tour_id', '=', 'cart_details.tour_id')->where('transfer_supplier_id', $request_data->supplier);
                                                                })->select('cart_details.tour_id','cart_details.booking_id','cart_details.invoice_no','cart_details.cart_total_data','cart_details.currency','cart_details.created_at','cart_details.confirm')->get();
                            $agents_invoice_booking =   DB::table('add_manage_invoices')->where('agent_Id',$request_data->agent_Id)->where('transfer_supplier_id',$request_data->supplier)->where('customer_id',$request->customer_id)->select('id','b2b_Agent_Id','generate_id','start_date','end_date','booking_customer_id','created_at','services','confirm','transportation_details','transportation_details_more','agent_Id','currency_symbol')->get();
                            
                        }
                    }
                }else{
                    if($request_data->agent_Id == 'all_agent'){
                        $agents_tour_booking    =   DB::table('cart_details')->where('cart_details.client_id',$request->customer_id)->where('cart_details.pakage_type','tour')
                                                            ->join('tours_2', function ($join) use ($request_data) {
                                                                $join->on('tours_2.tour_id', '=', 'cart_details.tour_id')->where('transfer_supplier_id', $request_data->supplier);
                                                            })->select('cart_details.tour_id','cart_details.booking_id','cart_details.invoice_no','cart_details.cart_total_data','cart_details.currency','cart_details.created_at','cart_details.confirm')->get();
                        $agents_invoice_booking =   DB::table('add_manage_invoices')->where('transfer_supplier_id',$request_data->supplier)->where('customer_id',$request->customer_id)->select('id','generate_id','start_date','end_date','booking_customer_id','created_at','services','confirm','transportation_details','transportation_details_more','agent_Id','currency_symbol')->get();
                    }elseif($request_data->agent_Id != 'all_agent'){
                        $agents_tour_booking    =   DB::table('cart_details')->where('agent_name',$request_data->agent_Id)->where('cart_details.pakage_type','tour')
                                                            ->join('tours_2', function ($join) use ($request_data) {
                                                                $join->on('tours_2.tour_id', '=', 'cart_details.tour_id')->where('transfer_supplier_id', $request_data->supplier);
                                                            })->select('cart_details.tour_id','cart_details.booking_id','cart_details.invoice_no','cart_details.cart_total_data','cart_details.currency','cart_details.created_at','cart_details.confirm')->get();
                        $agents_invoice_booking =   DB::table('add_manage_invoices')->where('agent_Id',$request_data->agent_Id)->where('transfer_supplier_id',$request_data->supplier)->where('customer_id',$request->customer_id)->select('id','b2b_Agent_Id','generate_id','start_date','end_date','booking_customer_id','created_at','services','confirm','transportation_details','transportation_details_more','agent_Id','currency_symbol')->get();
                        
                    }
                }
                
            }
            
            if($request_data->report_type == 'all_data'){
                $transfer_website_booking   = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->select('id','invoice_no','b2b_agent_id','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                if(isset($request_data->searchBy)){
                    if($request_data->searchBy == 'B2BAgent'){
                        if($request_data->b2b_Agent_Id == 'all_B2B_Agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->select('id','invoice_no','b2b_agent_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }elseif($request_data->b2b_Agent_Id != 'all_B2B_Agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->where('b2b_agent_id',$request_data->b2b_Agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','b2b_agent_id','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }
                    }elseif($request_data->searchBy == 'Customer'){
                        if($request_data->booking_customer_id == 'all_customer'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }elseif($request_data->booking_customer_id != 'all_customer'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->where('booking_customer_id',$request_data->booking_customer_id)->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }
                    }else{
                        if($request_data->agent_Id == 'all_agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->select('id','invoice_no','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }elseif($request_data->agent_Id != 'all_agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->where('booking_customer_id',$request_data->agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }
                    }
                }else{
                    if($request_data->agent_Id == 'all_agent'){
                        $transfer_website_booking   = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->select('id','invoice_no','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                    }elseif($request_data->agent_Id != 'all_agent'){
                        $transfer_website_booking   = DB::table('transfers_new_booking')->where('booking_customer_id',$request_data->agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                    }
                }
            }
            
            if($request_data->report_type == 'data_today_wise'){
                $today_date = date('Y-m-d');
            }
            
            if($request_data->report_type == 'data_tomorrow_wise'){
                $today_date = date('Y-m-d',strtotime("+1 days"));
            }
            
            if($request_data->report_type == 'data_week_wise'){
                $startDate  = now()->startOfWeek()->toDateString();
                $endDate    = now()->endOfWeek()->toDateString();
                
                if(isset($request_data->searchBy)){
                    if($request_data->searchBy == 'B2BAgent'){
                        if($request_data->b2b_Agent_Id == 'all_B2B_Agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('customer_id',$request->customer_id)->select('id','invoice_no','b2b_agent_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }elseif($request_data->b2b_Agent_Id != 'all_B2B_Agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('b2b_agent_id',$request_data->b2b_Agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','b2b_agent_id','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }
                    }else{
                        if($request_data->agent_Id == 'all_agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }elseif($request_data->agent_Id != 'all_agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('booking_customer_id',$request_data->agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }
                    }
                }else{
                    if($request_data->agent_Id == 'all_agent'){
                        $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                    }elseif($request_data->agent_Id != 'all_agent'){
                        $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('booking_customer_id',$request_data->agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                    }
                }
                
            }
            
            if($request_data->report_type == 'data_month_wise'){
                $startOfMonth   = Carbon::now()->startOfMonth();
                $startDate      = $startOfMonth->format('Y-m-d');
                
                $endOfMonth     = $startOfMonth->copy()->endOfMonth();
                $endDate        = $endOfMonth->format('Y-m-d');
                
                // $transfer_website_booking   =   DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->whereBetween('departure_date',[$startDate,$endDate])->select('id','invoice_no','b2b_agent_id','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                if(isset($request_data->searchBy)){
                    if($request_data->searchBy == 'B2BAgent'){
                        if($request_data->b2b_Agent_Id == 'all_B2B_Agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('customer_id',$request->customer_id)->select('id','invoice_no','b2b_agent_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }elseif($request_data->b2b_Agent_Id != 'all_B2B_Agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('b2b_agent_id',$request_data->b2b_Agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','b2b_agent_id','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }
                    }else{
                        if($request_data->agent_Id == 'all_agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }elseif($request_data->agent_Id != 'all_agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('booking_customer_id',$request_data->agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }
                    }
                }else{
                    if($request_data->agent_Id == 'all_agent'){
                        $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                    }elseif($request_data->agent_Id != 'all_agent'){
                        $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('booking_customer_id',$request_data->agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                    }
                }
            }
            
            if($request_data->report_type == 'data_wise'){
                $startDate                  = $request_data->check_in;
                $endDate                    = $request_data->check_out;
                // $transfer_website_booking   =   DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->whereBetween('departure_date',[$startDate,$endDate])->select('id','invoice_no','b2b_agent_id','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                if(isset($request_data->searchBy)){
                    if($request_data->searchBy == 'B2BAgent'){
                        if($request_data->b2b_Agent_Id == 'all_B2B_Agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('customer_id',$request->customer_id)->select('id','invoice_no','b2b_agent_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }elseif($request_data->b2b_Agent_Id != 'all_B2B_Agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('b2b_agent_id',$request_data->b2b_Agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','b2b_agent_id','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }
                    }else{
                        if($request_data->agent_Id == 'all_agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }elseif($request_data->agent_Id != 'all_agent'){
                            $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('booking_customer_id',$request_data->agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                        }
                    }
                }else{
                    if($request_data->agent_Id == 'all_agent'){
                        $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                    }elseif($request_data->agent_Id != 'all_agent'){
                        $transfer_website_booking   = DB::table('transfers_new_booking')->whereBetween('departure_date',[$startDate,$endDate])->where('booking_customer_id',$request_data->agent_Id)->where('customer_id',$request->customer_id)->select('id','invoice_no','booking_customer_id','lead_passenger_data','transfer_data','departure_date','created_at','booking_status')->get();
                    }
                }
            }
            
            foreach($agents_tour_booking as $tour_res){
                
                $agent_customer_name    = '';
                $client_type            = '';
                $service_type           = 'Package';
                $cart_all_data          = json_decode($tour_res->cart_total_data);
                
                $tours_costing          = DB::table('tours_2')->join('tours','tours_2.tour_id','tours.id')->where('tours.customer_id',$request->customer_id)->where('tours_2.tour_id',$tour_res->tour_id)->select('tours.start_date','tours.end_date','tours.created_at','transportation_details','transportation_details_more')->first();
                $passenger_nameQ        = DB::table('tours_bookings')->where('customer_id',$request->customer_id)->where('id',$tour_res->booking_id)->select('passenger_name')->get();
                
                if(isset($cart_all_data) && $cart_all_data != null && $cart_all_data != ''){
                    if(isset($cart_all_data->customer_id) && $cart_all_data->customer_id != null && $cart_all_data->customer_id != '' && $cart_all_data->customer_id != '-1'){
                        foreach($customer_lists as $customer_lists_val){
                            if($customer_lists_val->id == $cart_all_data->customer_id){
                                $agent_customer_id      = $customer_lists_val->id;
                                $client_type            = 'Customer';
                            }
                        }
                    }
                    else{
                        if(isset($cart_all_data->agent_name) && $cart_all_data->agent_name != null && $cart_all_data->agent_name != '' && $cart_all_data->agent_name != '-1'){
                            foreach($agent_lists as $agent_lists_val){
                                if($agent_lists_val->id == $cart_all_data->agent_name){
                                    $agent_customer_id  = $agent_lists_val->id;
                                    $client_type        = 'Agent';
                                }
                            }
                        }
                    }
                }
                
                if($tour_res->confirm == 1){
                    $confirm = 'CONFIRMED';
                }else{
                    $confirm = 'TENTATIVE';
                }
                
                $transportaion_details          = json_decode($tours_costing->transportation_details);
                $transportation_details_more    = json_decode($tours_costing->transportation_details_more);
                
                $vehicle = '';
                if(isset($transportaion_details)  && $transportaion_details != null && $transportaion_details != '' && is_array($transportaion_details)){
                    foreach($transportaion_details as $transportation_res){
                        $vehicle            = $transportation_res->transportation_vehicle_type;
                        $pickup_date        = '';
                        if(!empty($transportation_res->transportation_pick_up_date)){
                            $pickup_date    = date('d-m-Y',strtotime($transportation_res->transportation_pick_up_date ?? ''));
                        }
                        
                        $drop_off_date      = '';
                        if(!empty($transportation_res->transportation_drop_of_date)){
                            $drop_off_date = date('d-m-Y',strtotime($transportation_res->transportation_drop_of_date ?? ''));
                        }
                        
                        $pickup_date_match  = date('Y-m-d',strtotime($pickup_date));
                        
                        if($request_data->report_type == 'data_today_wise' || $request_data->report_type == 'data_tomorrow_wise'){
                             if($pickup_date_match == $today_date){
                                $listing_data = [
                                    'invoice_id'                => $tour_res->booking_id,
                                    'ref_number'                => $tour_res->invoice_no,
                                    'agent_customer_id'         => $agent_customer_id,
                                    'agent_customer_name'       => $passenger_nameQ[0]->passenger_name,
                                    'service_type'              => $service_type,
                                    'client_type'               => $client_type,
                                    'pick_up_date'              => $pickup_date,
                                    'vehicle_type'                => $transportation_res->transportation_vehicle_type ?? '',
                                    'pick_up_location'          => $transportation_res->transportation_pick_up_location ?? '',
                                    'drop_off_location'          => $transportation_res->transportation_drop_off_location ?? '',
                                    'route_type'                 => 'Pickup',
                                    'booking_date'              => $tour_res->created_at,
                                    'status'                    => $confirm,
                                ];
                                array_push($all_listing,$listing_data);
                            }
                        }
                        
                        if($request_data->report_type == 'data_week_wise' || $request_data->report_type == 'data_month_wise' || $request_data->report_type == 'data_wise'){
                            if($pickup_date_match >=$startDate && $pickup_date_match <= $endDate){
                                $listing_data = [
                                    'invoice_id'                => $tour_res->booking_id,
                                    'ref_number'                => $tour_res->invoice_no,
                                    'agent_customer_id'         => $agent_customer_id,
                                    'agent_customer_name'       => $passenger_nameQ[0]->passenger_name,
                                    'service_type'              => $service_type,
                                    'client_type'               => $client_type,
                                    'pick_up_date'              => $pickup_date,
                                    'vehicle_type'                => $transportation_res->transportation_vehicle_type ?? '',
                                    'pick_up_location'          => $transportation_res->transportation_pick_up_location ?? '',
                                    'drop_off_location'          => $transportation_res->transportation_drop_off_location ?? '',
                                    'route_type'                 => 'Pickup',
                                    'booking_date'              => $tour_res->created_at,
                                    'status'                    => $confirm,
                                ];
                                array_push($all_listing,$listing_data);
                            }
                        }
                        
                        if($request_data->report_type == 'all_data'){
                            $listing_data = [
                                'invoice_id'                => $tour_res->booking_id,
                                'ref_number'                => $tour_res->invoice_no,
                                'agent_customer_id'         => $agent_customer_id,
                                'agent_customer_name'       => $passenger_nameQ[0]->passenger_name,
                                'service_type'              => $service_type,
                                'client_type'               => $client_type,
                                'pick_up_date'              => $pickup_date,
                                'vehicle_type'              => $transportation_res->transportation_vehicle_type ?? '',
                                'pick_up_location'          => $transportation_res->transportation_pick_up_location ?? '',
                                'drop_off_location'         => $transportation_res->transportation_drop_off_location ?? '',
                                'route_type'                => 'Pickup',
                                'booking_date'              => $tour_res->created_at,
                                'status'                    => $confirm,
                            ];
                            array_push($all_listing,$listing_data);
                            
                            // dd($listing_data);
                        }
                        
                        $pickup_date        = '';
                        if(!empty($transportation_res->return_transportation_pick_up_date)){
                            $pickup_date = date('d-m-Y',strtotime($transportation_res->return_transportation_pick_up_date ?? ''));
                        }
                        
                        $drop_off_date      = '';
                        if(!empty($transportation_res->return_transportation_drop_of_date)){
                            $drop_off_date = date('d-m-Y',strtotime($transportation_res->return_transportation_drop_of_date ?? ''));
                        }
                        
                        if(!empty($transportation_res->return_transportation_pick_up_location)){
                            
                            $pickup_date_match = date('Y-m-d',strtotime($pickup_date));
                            if($request_data->report_type == 'data_today_wise' || $request_data->report_type == 'data_tomorrow_wise'){
                                if($pickup_date_match == $today_date){
                                    // echo "Pickup date $pickup_date_match start $startDate and enddate $endDate"."<br>";
                                        $listing_data = [
                                        'invoice_id'                => $tour_res->booking_id,
                                        'ref_number'                => $tour_res->invoice_no,
                                        'agent_customer_id'         => $agent_customer_id,
                                        'agent_customer_name'       => $passenger_nameQ[0]->passenger_name,
                                        'service_type'              => $service_type,
                                        'client_type'               => $client_type,
                                        'pick_up_date'              => $pickup_date,
                                        'vehicle_type'                => $transportation_res->transportation_vehicle_type ?? '',
                                        'pick_up_location'          => $transportation_res->return_transportation_pick_up_location ?? '',
                                        'drop_off_location'          => $transportation_res->return_transportation_drop_off_location ?? '',
                                        'route_type'                 => 'Return',
                                        'booking_date'              => $tour_res->created_at,
                                        'status'                    => $confirm,
                                    ];
                                    array_push($all_listing,$listing_data);
                                }
                            }
                            
                            if($request_data->report_type == 'data_week_wise' || $request_data->report_type == 'data_month_wise' || $request_data->report_type == 'data_wise'){
                                if($pickup_date_match >=$startDate && $pickup_date_match <= $endDate){
                                    // echo "Pickup date $pickup_date_match start $startDate and enddate $endDate"."<br>";
                                        $listing_data = [
                                        'invoice_id'                => $tour_res->booking_id,
                                        'ref_number'                => $tour_res->invoice_no,
                                        'agent_customer_id'         => $agent_customer_id,
                                        'agent_customer_name'       => $passenger_nameQ[0]->passenger_name,
                                        'service_type'              => $service_type,
                                        'client_type'               => $client_type,
                                        'pick_up_date'              => $pickup_date,
                                        'vehicle_type'                => $transportation_res->transportation_vehicle_type ?? '',
                                        'pick_up_location'          => $transportation_res->return_transportation_pick_up_location ?? '',
                                        'drop_off_location'          => $transportation_res->return_transportation_drop_off_location ?? '',
                                        'route_type'                 => 'Return',
                                        'booking_date'              => $tour_res->created_at,
                                        'status'                    => $confirm,
                                    ];
                                    array_push($all_listing,$listing_data);
                                }
                            }
                            
                            if($request_data->report_type == 'all_data'){
                                 $listing_data = [
                                        'invoice_id'                => $tour_res->booking_id,
                                        'ref_number'                => $tour_res->invoice_no,
                                        'agent_customer_id'         => $agent_customer_id,
                                        'agent_customer_name'       => $passenger_nameQ[0]->passenger_name,
                                        'service_type'              => $service_type,
                                        'client_type'               => $client_type,
                                        'pick_up_date'              => $pickup_date,
                                        'vehicle_type'                => $transportation_res->transportation_vehicle_type ?? '',
                                        'pick_up_location'          => $transportation_res->return_transportation_pick_up_location ?? '',
                                        'drop_off_location'          => $transportation_res->return_transportation_drop_off_location ?? '',
                                        'route_type'                 => 'Return',
                                        'booking_date'              => $tour_res->created_at,
                                        'status'                    => $confirm,
                                    ];
                                    array_push($all_listing,$listing_data);
                            }
                            
                        }
                    }
                }
                
                if(isset($transportation_details_more) && $transportation_details_more != null && $transportation_details_more != ''){
                    foreach($transportation_details_more as $tran_more){
                        if($tran_more->more_transportation_pick_up_location != ''){
                            
                            $pickup_date = '';
                            if(!empty($tran_more->more_transportation_pick_up_date)){
                                $pickup_date = date('d-m-Y',strtotime($tran_more->more_transportation_pick_up_date ?? ''));
                            }
                            
                            $drop_off_date = '';
                            if(!empty($tran_more->more_transportation_drop_of_date)){
                                $drop_off_date = date('d-m-Y',strtotime($tran_more->more_transportation_drop_of_date ?? ''));
                            }
                            
                            $pickup_date_match = date('Y-m-d',strtotime($pickup_date));
                            
                            if($request_data->report_type == 'data_today_wise' || $request_data->report_type == 'data_tomorrow_wise'){
                                if($pickup_date_match == $today_date){
                                    $listing_data = [
                                        'invoice_id'                => $tour_res->booking_id,
                                        'ref_number'                => $tour_res->invoice_no,
                                        'agent_customer_id'         => $agent_customer_id,
                                        'agent_customer_name'       => $passenger_nameQ[0]->passenger_name,
                                        'service_type'              => $service_type,
                                        'client_type'               => $client_type,
                                        'pick_up_date'              => $pickup_date,
                                        'vehicle_type'              => $vehicle,
                                        'pick_up_location'          => $tran_more->more_transportation_pick_up_location ?? '',
                                        'drop_off_location'         => $tran_more->more_transportation_drop_off_location ?? '',
                                        'route_type'                => 'More Destination',
                                        'booking_date'              => $tour_res->created_at,
                                        'status'                    => $confirm,
                                    ];
                                    array_push($all_listing,$listing_data);
                                }
                            }
                            
                            if($request_data->report_type == 'data_week_wise' || $request_data->report_type == 'data_month_wise' || $request_data->report_type == 'data_wise'){
                                if($pickup_date_match >=$startDate && $pickup_date_match <= $endDate){
                                    $listing_data = [
                                        'invoice_id'                => $tour_res->booking_id,
                                        'ref_number'                => $tour_res->invoice_no,
                                        'agent_customer_id'         => $agent_customer_id,
                                        'agent_customer_name'       => $passenger_nameQ[0]->passenger_name,
                                        'service_type'              => $service_type,
                                        'client_type'               => $client_type,
                                        'pick_up_date'              => $pickup_date,
                                        'vehicle_type'                => $vehicle,
                                        'pick_up_location'          => $tran_more->more_transportation_pick_up_location ?? '',
                                        'drop_off_location'          => $tran_more->more_transportation_drop_off_location ?? '',
                                        'route_type'                 => 'More Destination',
                                        'booking_date'              => $tour_res->created_at,
                                        'status'                    => $confirm,
                                    ];
                                    array_push($all_listing,$listing_data);
                                }
                            }
                            
                            if($request_data->report_type == 'all_data'){
                                $listing_data = [
                                    'invoice_id'                => $tour_res->booking_id,
                                    'ref_number'                => $tour_res->invoice_no,
                                    'agent_customer_id'         => $agent_customer_id,
                                    'agent_customer_name'       => $passenger_nameQ[0]->passenger_name,
                                    'service_type'              => $service_type,
                                    'client_type'               => $client_type,
                                    'pick_up_date'              => $pickup_date,
                                    'vehicle_type'              => $vehicle,
                                    'pick_up_location'          => $tran_more->more_transportation_pick_up_location ?? '',
                                    'drop_off_location'         => $tran_more->more_transportation_drop_off_location ?? '',
                                    'route_type'                => 'More Destination',
                                    'booking_date'              => $tour_res->created_at,
                                    'status'                    => $confirm,
                                ];
                                array_push($all_listing,$listing_data);
                                // 955804
                                // dd($listing_data);
                            }
                        }
                    }
                }
            }
            
            foreach($agents_invoice_booking as $agent_inv_res){
                $service_type   = '';
                $services       = json_decode($agent_inv_res->services);
                if($services[0] == '1'){
                    $service_type = 'All Services';
                }else if($services[0] == 'transportation_tab'){
                    $service_type = 'Transfer';
                }else{
                    $service_type = 'Not Selected';
                }
                
                if($service_type == 'All Services' || $service_type == 'Transfer'){
                    $agent_customer_id      = '';
                    $agent_customer_name    = '';
                    $client_type            = '';
                    $service_type           = 'Invoice'; 
                    
                    if(isset($agent_inv_res->b2b_Agent_Id) && $agent_inv_res->b2b_Agent_Id != null && $agent_inv_res->b2b_Agent_Id != '' && $agent_inv_res->b2b_Agent_Id != '-1'){
                        foreach($b2b_agent_lists as $val_b2bAD){
                            if($val_b2bAD->id == $agent_inv_res->b2b_Agent_Id){
                                $agent_customer_id      = $val_b2bAD->id;
                                $agent_customer_name    = $val_b2bAD->first_name ?? '' .' '. $val_b2bAD->last_name ?? '' .'('. $val_b2bAD->company_name ?? '' .')';
                                $client_type            = 'B2B Agent';
                            }
                        }
                    }elseif(isset($agent_inv_res->booking_customer_id) && $agent_inv_res->booking_customer_id != null && $agent_inv_res->booking_customer_id != '' && $agent_inv_res->booking_customer_id != '-1'){
                        foreach($customer_lists as $customer_lists_val){
                            if($customer_lists_val->id == $agent_inv_res->booking_customer_id){
                                $agent_customer_id      = $customer_lists_val->id;
                                $agent_customer_name    = $customer_lists_val->name;
                                $client_type            = 'Customer';
                            }
                        }
                    }else{
                        if(isset($agent_inv_res->agent_Id) && $agent_inv_res->agent_Id != null && $agent_inv_res->agent_Id != '' && $agent_inv_res->agent_Id != '-1'){
                            foreach($agent_lists as $agent_lists_val){
                                if($agent_lists_val->id == $agent_inv_res->agent_Id){
                                    $agent_customer_id      = $agent_lists_val->id;
                                    $agent_customer_name    = $agent_lists_val->agent_Name;
                                    $client_type            = 'Agent';
                                }
                            }
                        }
                    }
                    
                    if($client_type != ''){
                    
                        if($agent_inv_res->confirm == 1){
                            $confirm = 'CONFIRMED';
                        }else{
                            $confirm = 'TENTATIVE';
                        }
                        
                        $transportaion_details          = json_decode($agent_inv_res->transportation_details);
                        $transportation_details_more    = json_decode($agent_inv_res->transportation_details_more);
                        $vehicle                        = '';
                        
                        if(isset($transportaion_details)  && $transportaion_details != null && $transportaion_details != '' && is_array($transportaion_details)){
                            foreach($transportaion_details as $transportation_res){
                                $vehicle                = $transportation_res->transportation_vehicle_type;
                                $pickup_date            = '';
                                if(!empty($transportation_res->transportation_pick_up_date)){
                                    $pickup_date        = date('d-m-Y',strtotime($transportation_res->transportation_pick_up_date ?? ''));
                                }
                                
                                $drop_off_date          = '';
                                if(!empty($transportation_res->transportation_drop_of_date)){
                                    $drop_off_date      = date('d-m-Y',strtotime($transportation_res->transportation_drop_of_date ?? ''));
                                }
                                
                                $pickup_date_match      = date('Y-m-d',strtotime($pickup_date));
                                
                                $transfer_type = $transportation_res->transportation_trip_type ?? '';
                                if($transfer_type == 'More Destination'){
                                    $transfer_type = 'All Round';
                                }
                                
                                if($request_data->report_type == 'data_today_wise' || $request_data->report_type == 'data_tomorrow_wise'){
                                    if($pickup_date_match == $today_date){
                                        $listing_data = [
                                            'invoice_id'                => $agent_inv_res->id,
                                            'ref_number'                => $agent_inv_res->generate_id,
                                            'agent_customer_id'         => $agent_customer_id,
                                            'agent_customer_name'       => $agent_customer_name,
                                            'service_type'              => $service_type,
                                            'client_type'               => $client_type,
                                            'pick_up_date'              => $pickup_date,
                                            'vehicle_type'              => $vehicle,
                                            'pick_up_location'          => $transportation_res->transportation_pick_up_location ?? '',
                                            'drop_off_location'         => $transportation_res->transportation_drop_off_location ?? '',
                                            'route_type'                => $transfer_type ?? 'More Destination',
                                            'booking_date'              => $agent_inv_res->created_at,
                                            'status'                    => $confirm,
                                        ];
                                        array_push($all_listing,$listing_data);
                                    }
                                }
                                
                                if($request_data->report_type == 'data_week_wise' || $request_data->report_type == 'data_month_wise' || $request_data->report_type == 'data_wise'){
                                    if($pickup_date_match >=$startDate && $pickup_date_match <= $endDate){
                                        $listing_data = [
                                            'invoice_id'                => $agent_inv_res->id,
                                            'ref_number'                => $agent_inv_res->generate_id,
                                            'agent_customer_id'         => $agent_customer_id,
                                            'agent_customer_name'       => $agent_customer_name,
                                            'service_type'              => $service_type,
                                            'client_type'               => $client_type,
                                            'pick_up_date'              => $pickup_date,
                                            'vehicle_type'              => $vehicle,
                                            'pick_up_location'          => $transportation_res->transportation_pick_up_location ?? '',
                                            'drop_off_location'         => $transportation_res->transportation_drop_off_location ?? '',
                                            'route_type'                => $transfer_type ?? 'More Destination',
                                            'booking_date'              => $agent_inv_res->created_at,
                                            'status'                    => $confirm,
                                        ];
                                        array_push($all_listing,$listing_data);
                                    }
                                }
                                
                                if($request_data->report_type == 'all_data'){
                                    if($pickup_date != ''){
                                        $listing_data = [
                                            'invoice_id'                => $agent_inv_res->id,
                                            'ref_number'                => $agent_inv_res->generate_id,
                                            'agent_customer_id'         => $agent_customer_id,
                                            'agent_customer_name'       => $agent_customer_name,
                                            'service_type'              => $service_type,
                                            'client_type'               => $client_type,
                                            'pick_up_date'              => $pickup_date,
                                            'vehicle_type'              => $vehicle,
                                            'pick_up_location'          => $transportation_res->transportation_pick_up_location ?? '',
                                            'drop_off_location'         => $transportation_res->transportation_drop_off_location ?? '',
                                            'route_type'                => $transfer_type ?? 'More Destination',
                                            'booking_date'              => $agent_inv_res->created_at,
                                            'status'                    => $confirm,
                                        ];
                                        array_push($all_listing,$listing_data);
                                    }
                                }
                                
                                $pickup_date            = '';
                                if(!empty($transportation_res->return_transportation_pick_up_date)){
                                    $pickup_date = date('d-m-Y',strtotime($transportation_res->return_transportation_pick_up_date ?? ''));
                                }
                                
                                $drop_off_date          = '';
                                if(!empty($transportation_res->return_transportation_drop_of_date)){
                                    $drop_off_date = date('d-m-Y',strtotime($transportation_res->return_transportation_drop_of_date ?? ''));
                                }
                                
                                if(!empty($transportation_res->return_transportation_pick_up_location)){
                                    $pickup_date_match = date('Y-m-d',strtotime($pickup_date));
                                    
                                    if($request_data->report_type == 'data_today_wise' || $request_data->report_type == 'data_tomorrow_wise'){
                                        if($pickup_date_match == $today_date){
                                            $listing_data = [
                                                'invoice_id'                => $agent_inv_res->id,
                                                'ref_number'                => $agent_inv_res->generate_id,
                                                'agent_customer_id'         => $agent_customer_id,
                                                'agent_customer_name'       => $agent_customer_name,
                                                'service_type'              => $service_type,
                                                'client_type'               => $client_type,
                                                'pick_up_date'              => $pickup_date,
                                                'vehicle_type'              => $vehicle,
                                                'pick_up_location'          => $transportation_res->return_transportation_pick_up_location ?? '',
                                                'drop_off_location'         => $transportation_res->return_transportation_drop_off_location ?? '',
                                                'route_type'                => $transfer_type ?? 'More Destination',
                                                'booking_date'              => $agent_inv_res->created_at,
                                                'status'                    => $confirm,
                                            ];
                                            array_push($all_listing,$listing_data);
                                        }
                                    }
                                    
                                    if($request_data->report_type == 'data_week_wise' || $request_data->report_type == 'data_month_wise' || $request_data->report_type == 'data_wise'){
                                        if($pickup_date_match >=$startDate && $pickup_date_match <= $endDate){
                                            $listing_data = [
                                                'invoice_id'                => $agent_inv_res->id,
                                                'ref_number'                => $agent_inv_res->generate_id,
                                                'agent_customer_id'         => $agent_customer_id,
                                                'agent_customer_name'       => $agent_customer_name,
                                                'service_type'              => $service_type,
                                                'client_type'               => $client_type,
                                                'pick_up_date'              => $pickup_date,
                                                'vehicle_type'              => $vehicle,
                                                'pick_up_location'          => $transportation_res->return_transportation_pick_up_location ?? '',
                                                'drop_off_location'         => $transportation_res->return_transportation_drop_off_location ?? '',
                                                'route_type'                => $transfer_type ?? 'More Destination',
                                                'booking_date'              => $agent_inv_res->created_at,
                                                'status'                    => $confirm,
                                            ];
                                            array_push($all_listing,$listing_data);
                                        }
                                    }
                                    
                                    if($request_data->report_type == 'all_data'){
                                        if($pickup_date != ''){
                                            $listing_data = [
                                                'invoice_id'                => $agent_inv_res->id,
                                                'ref_number'                => $agent_inv_res->generate_id,
                                                'agent_customer_id'         => $agent_customer_id,
                                                'agent_customer_name'       => $agent_customer_name,
                                                'service_type'              => $service_type,
                                                'client_type'               => $client_type,
                                                'pick_up_date'              => $pickup_date,
                                                'vehicle_type'              => $vehicle,
                                                'pick_up_location'          => $transportation_res->return_transportation_pick_up_location ?? '',
                                                'drop_off_location'         => $transportation_res->return_transportation_drop_off_location ?? '',
                                                'route_type'                => $transfer_type ?? 'More Destination',
                                                'booking_date'              => $agent_inv_res->created_at,
                                                'status'                    => $confirm,
                                            ];
                                            array_push($all_listing,$listing_data);
                                        }
                                    }
                                }
                            }
                        }
                        
                        if(isset($transportation_details_more) && $transportation_details_more != null && $transportation_details_more != ''){
                            foreach($transportation_details_more as $tran_more){
                                if($tran_more->more_transportation_pick_up_location != ''){
                                    
                                    $pickup_date        = '';
                                    if(!empty($tran_more->more_transportation_pick_up_date)){
                                        $pickup_date    = date('d-m-Y',strtotime($tran_more->more_transportation_pick_up_date ?? ''));
                                    }
                                    
                                    $drop_off_date      = '';
                                    if(!empty($tran_more->more_transportation_drop_of_date)){
                                        $drop_off_date  = date('d-m-Y',strtotime($tran_more->more_transportation_drop_of_date ?? ''));
                                    }
                                    
                                    $pickup_date_match  = date('Y-m-d',strtotime($pickup_date));
                                    
                                    $transfer_type = 'All Round';
                                    
                                    if($request_data->report_type == 'data_today_wise' || $request_data->report_type == 'data_tomorrow_wise'){
                                        if($pickup_date_match == $today_date){
                                            $listing_data                   = [
                                                'invoice_id'                => $agent_inv_res->id,
                                                'ref_number'                => $agent_inv_res->generate_id,
                                                'agent_customer_id'         => $agent_customer_id,
                                                'agent_customer_name'       => $agent_customer_name,
                                                'service_type'              => $service_type,
                                                'client_type'               => $client_type,
                                                'pick_up_date'              => $pickup_date,
                                                'vehicle_type'              => $vehicle,
                                                'pick_up_location'          => $tran_more->more_transportation_pick_up_location ?? '',
                                                'drop_off_location'         => $tran_more->more_transportation_drop_off_location ?? '',
                                                'route_type'                => $transfer_type ?? 'More Destination',
                                                'booking_date'              => $agent_inv_res->created_at,
                                                'status'                    => $confirm,
                                            ];
                                            array_push($all_listing,$listing_data);
                                        }
                                    }
                                    
                                    if($request_data->report_type == 'data_week_wise' || $request_data->report_type == 'data_month_wise' || $request_data->report_type == 'data_wise'){
                                        if($pickup_date_match >=$startDate && $pickup_date_match <= $endDate){
                                            $listing_data = [
                                                'invoice_id'                => $agent_inv_res->id,
                                                'ref_number'                => $agent_inv_res->generate_id,
                                                'agent_customer_id'         => $agent_customer_id,
                                                'agent_customer_name'       => $agent_customer_name,
                                                'service_type'              => $service_type,
                                                'client_type'               => $client_type,
                                                'pick_up_date'              => $pickup_date,
                                                'vehicle_type'              => $vehicle,
                                                'pick_up_location'          => $tran_more->more_transportation_pick_up_location ?? '',
                                                'drop_off_location'         => $tran_more->more_transportation_drop_off_location ?? '',
                                                'route_type'                => $transfer_type ?? 'More Destination',
                                                'booking_date'              => $agent_inv_res->created_at,
                                                'status'                    => $confirm,
                                            ];
                                            array_push($all_listing,$listing_data);
                                        }
                                    }
                                    
                                    if($request_data->report_type == 'all_data'){
                                        if($pickup_date != ''){
                                            $listing_data = [
                                                'invoice_id'                => $agent_inv_res->id,
                                                'ref_number'                => $agent_inv_res->generate_id,
                                                'agent_customer_id'         => $agent_customer_id,
                                                'agent_customer_name'       => $agent_customer_name,
                                                'service_type'              => $service_type,
                                                'client_type'               => $client_type,
                                                'pick_up_date'              => $pickup_date,
                                                'vehicle_type'              => $vehicle,
                                                'pick_up_location'          => $tran_more->more_transportation_pick_up_location ?? '',
                                                'drop_off_location'         => $tran_more->more_transportation_drop_off_location ?? '',
                                                'route_type'                => $transfer_type ?? 'More Destination',
                                                'booking_date'              => $agent_inv_res->created_at,
                                                'status'                    => $confirm,
                                            ];
                                            array_push($all_listing,$listing_data);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } 
            
            if(!empty($transfer_website_booking)){
                foreach($transfer_website_booking as $trans_res){
                    
                    $agent_customer_id      = '';
                    $agent_customer_name    = '';
                    $client_type            = '';
                    $service_type           = 'Invoice'; 
                    
                    if(isset($trans_res->b2b_agent_id) && $trans_res->b2b_agent_id != null && $trans_res->b2b_agent_id != '' && $trans_res->b2b_agent_id != '-1'){
                        foreach($b2b_agent_lists as $val_b2bAD){
                            if($val_b2bAD->id == $trans_res->b2b_agent_id){
                                $agent_customer_id      = $val_b2bAD->id;
                                $agent_customer_name    = $val_b2bAD->first_name ?? '' .' '. $val_b2bAD->last_name ?? '' .'('. $val_b2bAD->company_name ?? '' .')';
                                $client_type            = 'B2B Agent';
                            }
                        }
                    }elseif(isset($trans_res->booking_customer_id) && $trans_res->booking_customer_id != null && $trans_res->booking_customer_id != '' && $trans_res->booking_customer_id != '-1'){
                        foreach($customer_lists as $customer_lists_val){
                            if($customer_lists_val->id == $trans_res->booking_customer_id){
                                $agent_customer_id      = $customer_lists_val->id;
                                $agent_customer_name    = $customer_lists_val->name;
                                $client_type            = 'Customer';
                            }
                        }
                    }else{
                        if(isset($trans_res->agent_Id) && $trans_res->agent_Id != null && $trans_res->agent_Id != '' && $trans_res->agent_Id != '-1'){
                            foreach($agent_lists as $agent_lists_val){
                                if($agent_lists_val->id == $trans_res->agent_Id){
                                    $agent_customer_id      = $agent_lists_val->id;
                                    $agent_customer_name    = $agent_lists_val->agent_Name;
                                    $client_type            = 'Agent';
                                }
                            }
                        }
                    }
                    
                    if($client_type != ''){
                        
                        $lead_passenger_data            = json_decode($trans_res->lead_passenger_data);
                        $transfer_data                  = json_decode($trans_res->transfer_data);
                        
                        $transfer_type = $transfer_data->transfer_type ?? '';
                        if($transfer_type == 'More Destination'){
                            $transfer_type = 'All Round';
                        }
                        
                        $listing_data                   = [
                            'invoice_id'                => $trans_res->invoice_no,
                            'agent_customer_id'         => $agent_customer_id,
                            'agent_customer_name'       => $lead_passenger_data->lead_first_name ?? ''." ".$lead_passenger_data->lead_last_name ?? '',
                            'service_type'              => "Website Booking",
                            'client_type'               => $client_type,
                            'pick_up_date'              => date('d-m-Y',strtotime($trans_res->departure_date)),
                            'route_type'                => $transfer_type,
                            'pick_up_location'          => $transfer_data->pickup_City ?? '',
                            'drop_off_location'         => $transfer_data->return_dropof_City ?? '',
                            'vehicle_type'              => $transfer_data->vehicle_Name,
                            'booking_date'              => $trans_res->created_at,
                            'status'                    => $trans_res->booking_status,
                        ];      
                        if($request_data->supplier != 'all' && $request_data->agent_Id == 'all_agent'){
                            if($transfer_data->transfer_supplier_Id == $request_data->supplier){
                                array_push($all_listing,$listing_data);
                            }
                        }
                        
                        if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
                            array_push($all_listing,$listing_data);
                        }
                    }
                }
            }
            
            $all_listing = new Collection($all_listing);
            
            $all_listing = $all_listing->sortBy('pick_up_date');
            
            $all_listing = $all_listing->values();
            
            $all_listing = $all_listing->toArray(); 
            
            return response()->json([
                'message'               => 'success',
                'arrival_listing_data'  => $all_listing,
            ]);
        }
    }
}