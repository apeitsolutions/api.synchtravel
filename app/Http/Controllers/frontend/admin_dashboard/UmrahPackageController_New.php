<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\Tour;
use App\Models\Tour_enquire;
use App\Models\Active;
use App\Models\alhijaz_Notofication;
use App\Models\country;
use App\Models\Activities;
use DB;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\Cart_details;
use Carbon\Carbon;
use App\Models\MinaPackage;
use App\Models\ArfatPackage;
use App\Models\MuzdilifaPackage;
use App\Models\TourHajPackage;
use App\Models\expense\expense;
use App\Models\expense\expenseCategory;
use App\Models\expense\expenseSubCategory;
use App\Models\Accounts\CashAccounts;
use App\Models\Accounts\CashAccountsBal;
use App\Models\Accounts\CashAccountledger;

use Illuminate\Support\Facades\Storage;

class UmrahPackageController_New extends Controller
{
    // Tour Packages
    public function create_excursion2(Request $request){
        $customer_id            = $request->customer_id;
        $categories             = DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes             = DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer               = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $all_countries          = country::all();
        $all_countries_currency = country::all();
        $payment_gateways       = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes          = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol        = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $transfer_Vehicle       = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
        $others_providers_list  = DB::table('3rd_party_commissions')->where('customer_id',$customer_id)->get();
        $supplier_detail        = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $destination_details    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $all_flight_routes      = DB::table('flight_rute')->where('customer_id',$customer_id)->get();
        $flight_suppliers       = DB::table('supplier')->where('customer_id',$customer_id)->get();
        $bir_airports           = DB::table('bir_airports')->get();
        $Agents_detail          = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
        $user_hotels            = Hotels::where('owner_id',$customer_id)->get();
        $customers_data         = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
        $all_curr_symboles      = country::all();
        $tranfer_vehicle        = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
        $tranfer_destination    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $tranfer_supplier       = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $tranfer_company        = DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();
        $mange_currencies       = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
        $visa_supplier          = DB::table('visa_Sup')->where('customer_id',$customer_id)->get();
        $visa_types             = DB::table('visa_types')->where('customer_id',$customer_id)->get();
        $minaPackage            = MinaPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $minaPackage1           = MinaPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $ArfatPackage           = ArfatPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $ArfatPackage1          = ArfatPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $muzdalfaPackage        = MuzdilifaPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $muzdalfaPackage1       = MuzdilifaPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $vehicle_category       = DB::table('vehicle_category')->where('customer_id',$customer_id)->get();
        $custom_Meal_Types      = DB::table('custom_Meal_Types')->where('customer_id',$request->customer_id)->get();
        
        $airportsJson           = Storage::get('airports.json');
        $airports               = json_decode($airportsJson, true);
     
        return response()->json(['message'=>'success',
            'vehicle_category'          => $vehicle_category,
            'flight_suppliers'          => $flight_suppliers,
            'all_flight_routes'         => $all_flight_routes,
            'destination_details'       => $destination_details,
            'visa_supplier'             => $visa_supplier,
            'visa_types'                => $visa_types,
            'supplier_detail'           => $supplier_detail,
            'transfer_Vehicle'          => $transfer_Vehicle,
            'currency_Symbol'           => $currency_Symbol,
            'customer'                  => $customer,
            'attributes'                => $attributes,
            'others_providers_list'     => $others_providers_list,
            'categories'                => $categories,
            'all_countries'             => $all_countries,
            'all_countries_currency'    => $all_countries_currency,
            'payment_gateways'          => $payment_gateways,
            'payment_modes'             => $payment_modes,
            'bir_airports'              => $bir_airports,
            'Agents_detail'             => $Agents_detail,
            'user_hotels'               => $user_hotels,
            'customers_data'            => $customers_data,
            'all_curr_symboles'         => $all_curr_symboles,
            'tranfer_vehicle'           => $tranfer_vehicle,
            'tranfer_destination'       => $tranfer_destination,
            'tranfer_supplier'          => $tranfer_supplier,
            'tranfer_company'           => $tranfer_company,
            'mange_currencies'          => $mange_currencies,
            'minaPackage'               => $minaPackage,
            'minaPackage1'              => $minaPackage1,
            'ArfatPackage'              => $ArfatPackage,
            'ArfatPackage1'             => $ArfatPackage1,
            'muzdalfaPackage'           => $muzdalfaPackage,
            'muzdalfaPackage1'          => $muzdalfaPackage1,
            'custom_Meal_Types'         => $custom_Meal_Types,
            'airports'                  => $airports,
        ]);
    }
    
    public function view_tour(Request $request){
        $pakage_type        = 'tour';
        $customer_id        = $request->customer_id;
        $tours              = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$customer_id)->get();
        $data1              = DB::table('cart_details')->where('pakage_type',$pakage_type)->get();
        $booking_Id         = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->join('cart_details','tours.id','cart_details.tour_id')->where('tours.customer_id',$customer_id)->select('cart_details.tour_id','cart_details.booking_id')->get();
        return response()->json([
            'message'           => 'success',
            'tours'             => $tours,
            'data1'             => $data1,
            'booking_Id'        => $booking_Id,
        ]);
    }

    public function submit_tour(Request $request){
        
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
        
        $fg                 = json_decode($request->flight_id_array);
        $fgg                = json_decode($request->flight_reserve_array);
        
        $accomodation       = json_decode($request->accomodation_details);
        $accomodation_more  = json_decode($request->more_accomodation_details);
        
        if($fg !='' && $fg != null){
            foreach($fg as $key=>$fgz){
                $update_fig = (int)$fgg[$key];
                
                $seat = DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('id',$fgz)->select('occupied_seat','flights_number_of_seat')->first();
                
                $total_occupied = $seat->occupied_seat + $update_fig;
                
                DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('id',$fgz)->update(['occupied_seat'=>$total_occupied,]);
            } 
        }
        
        $total_single_seats = 0;
        $total_double_seats = 0;
        $total_triple_seats = 0;
        $total_quad_seats   = 0;
        
        $accomodation_data      = json_decode($request->accomodation_details);
        $accomodation_more_data = json_decode($request->more_accomodation_details);
        
        if(isset($accomodation_data)){
            foreach($accomodation_data as $index => $acc_res){
                if($acc_res->room_select_type == 'true'){
                    
                    $room_type_data = json_decode($acc_res->new_rooms_type);
                    $Rooms = new Rooms;
                                $Rooms->hotel_id        =  $acc_res->hotel_id;
                                $Rooms->rooms_on_rq     = '';
                                $Rooms->room_type_id    =  $room_type_data->parent_cat; 
                                $Rooms->room_type_name  =  $room_type_data->room_type; 
                                $Rooms->room_type_cat   =  $room_type_data->id; 
                                
                                $Rooms->SU_id           = $request->SU_id ?? NULL;
                               
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
                                
                              
                                
                                $user_id = $request->customer_id;
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
                                            'SU_id' => $request->SU_id ?? NULL,
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
        // print_r($accomodation_more_data);
        
        if(isset($accomodation_more_data)){
            foreach($accomodation_more_data as $index => $acc_res){
                if($acc_res->more_room_select_type == 'true'){
                    
                    $room_type_data = json_decode($acc_res->more_new_rooms_type);
                     $Rooms = new Rooms;
                                $Rooms->hotel_id        =  $acc_res->more_hotel_id;
                                $Rooms->rooms_on_rq     = '';
                                $Rooms->room_type_id    =  $room_type_data->parent_cat; 
                                $Rooms->room_type_name  =  $room_type_data->room_type; 
                                $Rooms->room_type_cat   =  $room_type_data->id; 
                                
                                $Rooms->SU_id           =  $request->SU_id ?? NULL;
                                
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
                                
                              
                                
                                $user_id = $request->customer_id;
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
                                            'SU_id' => $request->SU_id ?? NULL,
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
        
        if(isset($accomodation_data) && $accomodation_data != null && $accomodation_data != ''){
            foreach($accomodation_data as $acc_res){
                if($acc_res->acc_type == 'Single'){
                    $total_single_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Double'){
                    $total_double_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Triple'){
                    $total_triple_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Quad'){
                    $total_quad_seats += $acc_res->acc_pax;
                }
            }
        }
        
        if(isset($accomodation_more_data) && $accomodation_more_data != null && $accomodation_more_data != ''){
            foreach($accomodation_more_data as $acc_res){
                if($acc_res->more_acc_type == 'Single'){
                    $total_single_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Double'){
                    $total_double_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Triple'){
                    $total_triple_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Quad'){
                    $total_quad_seats += $acc_res->more_acc_pax;
                }
            }
        }
        
        $request->accomodation_details = json_encode($accomodation_data);
        $request->more_accomodation_details = json_encode($accomodation_more_data);
        
        $visa_details = json_decode($request->visa_details);
        
        if(isset($visa_details)){
            foreach($visa_details as $index => $visa_res){
                
                // 1 Check Add New Visa or Exists Use
                if($visa_res->visa_add_type_new !== 'false'){
                    // Add As New
                    
                    $visa_avail_id = DB::table('visa_Availability')->insertGetId([
                            'SU_id' => $request->SU_id ?? NULL,
                            'visa_supplier' => $visa_res->visa_supplier_id,
                            'visa_type' => $visa_res->visa_type_id,
                            'visa_qty' => $visa_res->visa_occupied,
                            'visa_available' => $visa_res->visa_occupied,
                            'visa_price' => $visa_res->visa_fee_purchase,
                            'availability_from' => $visa_res->visa_av_from,
                            'availability_to' => $visa_res->visa_av_to,
                            'country' => $visa_res->visa_country_id,
                            'currency_conversion' => $request->conversion_type_Id,
                            'visa_price_conversion_rate' => $visa_res->exchange_rate_visa,
                            'visa_converted_price'=> $visa_res->visa_fee,
                            'customer_id' => $request->customer_id,
                    ]);
                        
                    $visa_details[$index]->visa_avail_id    = $visa_avail_id;
                    $supplier_data                          = DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->first();
                    
                    if(isset($supplier_data)){
                        $supplier_balance = $supplier_data->balance + $visa_res->visa_purchase_total;
                        
                        DB::table('visa_supplier_ledger')->insert([
                                'SU_id' => $request->SU_id ?? NULL,
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
                                'customer_id'=> $request->customer_id,
                            ]);
                        
                        $data=DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([  
                           'balance'               => $supplier_balance,
                        ]);
                    }
                }
                
                // // 2 Update No of Seats Occupied in Visa
                //     $visa_avail_data = DB::table('visa_Availability')->where('id',$visa_res->visa_avail_id)->first();
                    
                //     $updated_seats = $visa_avail_data->visa_available - $visa_res->visa_occupied;
                    
                //     DB::table('visa_Availability')->where('id',$visa_res->visa_avail_id)->update([
                //             'visa_available' => $updated_seats
                //         ]);
                  
                // // 3 Update Visa Supplier Balance
                //     $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->first();
                    
                    
                //     $visa_supplier_payable_balance = $visa_supplier_data->payable + $visa_res->visa_purchase_total;
                //     DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([
                //             'payable' => $visa_supplier_payable_balance
                //     ]);
                    
                //     DB::table('visa_supplier_ledger')->insert([
                //             'supplier_id' => $visa_res->visa_supplier_id,
                //             'payment' => $visa_res->visa_purchase_total,
                //             'balance' => $visa_supplier_data->balance,
                //             'payable' => $visa_supplier_payable_balance,
                //             'visa_qty' => $visa_res->visa_occupied,
                //             'visa_type' => $visa_res->visa_type_id,
                //             'package_id' => $visa_res->visa_supplier_id,
                //             'visa_avl_id' => $visa_res->visa_avail_id,
                //             'remarks' => 'New Package Created',
                //             'date' => date('Y-m-d'),
                //             'customer_id' => $request->customer_id,
                //     ]);
                    
                //     DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([
                //             'payable' => $visa_supplier_payable_balance
                //     ]);
            }
        }
        
        $visa_details = json_encode($visa_details);
        
        $generate_id=rand(0,9999999);
        $tour = new Tour();
        
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $tour->SU_id               = $request->SU_id;
        }
        
        $tour->flight_id=$request->flight_id;
        $tour->generate_id=$generate_id;
        $tour->customer_id=$request->customer_id;
        $tour->title=$request->title;
        $tour->content=$request->content;
        $tour->categories=$request->tour_categories;
        $tour->tour_attributes=$request->tour_attributes;
        $tour->no_of_pax_days=$request->no_of_pax_days;
        $tour->city_Count=$request->city_Count;
        $tour->destination_details=$request->destination_details;
        $tour->destination_details_more=$request->destination_details_more;
        $tour->accomodation_details=$request->accomodation_details;
        $tour->accomodation_details_more=$request->more_accomodation_details;
        $tour->visa_type=$request->visa_type;
        $tour->visa_rules_regulations=$request->visa_rules_regulations;
        $tour->visa_detials = $visa_details;
        
        $tour->currency_conversion=$request->currency_conversion;
        $tour->conversion_type_Id=$request->conversion_type_Id;
        
        $tour->visa_fee_purchase=$request->visa_fee_purchase;
        $tour->exchange_rate_visa=$request->exchange_rate_visa;
        $tour->conversion_type=$request->conversion_type;
        $tour->visa_fee=$request->visa_fee;
        $tour->visa_image=$request->visa_image;
        $tour->gallery_images=$request->gallery_images;
        $tour->start_date=$request->start_date;
        $tour->end_date=$request->end_date;
        $tour->time_duration=$request->time_duration;
        $tour->tour_location=$request->tour_location;
        $tour->whats_included=$request->whats_included;
        $tour->whats_excluded=$request->whats_excluded;
        $tour->currency_symbol=$request->currency_symbol;
        $tour->tour_publish=$request->tour_publish;
        $tour->tour_author=$request->tour_author;
        $tour->others_providers_show=$request->others_providers_show;
        
        // dd($request->tour_feature);
        
        $tour->tour_feature=$request->tour_feature;
        $tour->defalut_state=$request->defalut_state;
        $tour->payment_gateways=$request->payment_gateways;
        $tour->payment_modes=$request->payment_modes;
        $tour->tour_featured_image=$request->tour_featured_image;
        $tour->tour_banner_image=$request->tour_banner_image;
        $tour->starts_rating=$request->starts_rating;
        $tour->checkout_message     =   $request->checkout_message;
        $tour->cancellation_policy  =   $request->cancellation_policy;
        
        // $tour->arfatData  =   $request->arfatData;
        $tour->arfat_selected  =   $request->arfat_selected;
        // $tour->muzdalfaData  =   $request->muzdalfaData;
        $tour->muzdalfa_selected  =   $request->muzdalfa_selected;
        // $tour->minaData  =   $request->minaData;
        $tour->mina_selected  =   $request->mina_selected;
        
        $tour->mina_pkg_details     =   $request->mina_pkg_details;
        $tour->arfat_pkg_details    =   $request->arfat_pkg_details;
        $tour->muzdalfa_details     =   $request->muzdalfa_details;
        
        $tour->available_seats          =   $request->no_of_pax_days;
        $tour->available_single_seats   =   $total_single_seats;
        $tour->available_double_seats   =   $total_double_seats;
        $tour->available_triple_seats   =   $total_triple_seats;
        $tour->available_quad_seats     =   $total_quad_seats;
        
        // Adult
        $tour->WQFVT_details                = $request->WQFVT_details ?? '';
        $tour->WDFVT_details                = $request->WDFVT_details ?? '';
        $tour->WTFVT_details                = $request->WTFVT_details ?? '';
        $tour->WAFVT_details                = $request->WAFVT_details ?? '';
        
        // Child
        $tour->WQFVT_details_child          = $request->WQFVT_details_child ?? '';
        $tour->WTFVT_details_child          = $request->WTFVT_details_child ?? '';
        $tour->WDFVT_details_child          = $request->WDFVT_details_child ?? '';
        $tour->WAFVT_details_child          = $request->WAFVT_details_child ?? '';
        
        // Infant
        $tour->WQFVT_details_infant         = $request->WQFVT_details_infant ?? '';
        $tour->WTFVT_details_infant         = $request->WTFVT_details_infant ?? '';
        $tour->WDFVT_details_infant         = $request->WDFVT_details_infant ?? '';
        $tour->WAFVT_details_infant         = $request->WAFVT_details_infant ?? '';
        
        $tour->departure_Country            = $request->departure_Country ?? NULL;
        $tour->departureAirportCode         = $request->departureAirportCode ?? NULL;
        
        // $tour->hotel_Reservation_details    = $request->hotel_Reservation_details ?? '';
        // $tour->transfer_Reservation_No      = $request->transfer_Reservation_No ?? '';
        // $tour->flight_Reservation_No        = $request->flight_Reservation_No ?? '';
        // $tour->visa_Reservation_No          = $request->visa_Reservation_No ?? '';
        
        DB::beginTransaction();
        try {
            
            $tour->save();
            $lastTourId = $tour->id;
            
            // Cost
            $quad_cost_priceN               = $request->quad_cost_price ?? '0';
            $triple_cost_priceN             = $request->triple_cost_price ?? '0';
            $double_cost_priceN             = $request->double_cost_price ?? '0';
            $without_acc_cost_priceN        = $request->without_acc_cost_price ?? '0';
            $total_cost_price_all_Services  = $double_cost_priceN + $triple_cost_priceN + $quad_cost_priceN + $without_acc_cost_priceN;
            
            // Sale
            $quad_grand_total_amountN       = $request->quad_grand_total_amount ?? '0';
            $triple_grand_total_amountN     = $request->triple_grand_total_amount ?? '0';
            $double_grand_total_amountN     = $request->double_grand_total_amount ?? '0';
            $without_acc_sale_price_singleN = $request->without_acc_sale_price ?? '0';
            $total_sale_price_all_Services  = $double_grand_total_amountN + $triple_grand_total_amountN + $quad_grand_total_amountN + $without_acc_sale_price_singleN;
            
            $notification_insert = new alhijaz_Notofication();
            $notification_insert->type_of_notification_id   = $tour->id ?? '';
            $notification_insert->customer_id               = $tour->customer_id ?? '';
            $notification_insert->type_of_notification      = 'create_Package' ?? '';
            $notification_insert->generate_id               = $tour->generate_id ?? '';
            $notification_insert->notification_creator_name = 'AlhijazTours' ?? '';
            $notification_insert->total_price               = $total_sale_price_all_Services ?? '';
            $notification_insert->amount_paid               = $tour->amount_Paid ?? '';
            $notification_insert->remaining_price           = $total_sale_price_all_Services ?? '';
            $notification_insert->notification_status       = '1' ?? '';
            $notification_insert->save();
            
            $flights_details            = json_decode($request->flights_details);
            if(isset($flights_details) && $flights_details != null && $flights_details != ''){
                DB::table('flight_seats_occupied')->insert([
                    'SU_id'                         => $request->SU_id ?? NULL,
                    'token'                         => $request->token,
                    'type'                          => 'Package',
                    'booking_id'                    => $lastTourId,
                    'flight_supplier'               => $request->flight_supplier,
                    'flight_route_id'               => $request->flight_route_id_occupied,
                    'flights_adult_seats'           => $request->flight_total_seats,
                    'flights_child_seats'           => 0,
                    'flight_route_seats_occupied'   => $request->flight_total_seats,
                ]);
            }
            
            $tours_2 = DB::table('tours_2')->insert([
                'tour_id'=>$lastTourId,
                'Itinerary_details'=>$request->Itinerary_details,
                'tour_itinerary_details_1'=>$request->tour_itinerary_details_1,
                'tour_extra_price'=>$request->tour_extra_price,
                'tour_extra_price_1'=>$request->tour_extra_price_1,
                'tour_faq'=>$request->tour_faq,
                'tour_faq_1'=>$request->tour_faq_1,
                'markup_details'=>$request->markup_details,
                'more_markup_details'=>$request->more_markup_details,
                
                'flight_route_type'         => $request->flight_route_type,
                'flight_supplier'           => $request->flight_supplier,
                'flight_route_id_occupied'  => $request->flight_route_id_occupied,
                'flight_total_seats'        => $request->flight_total_seats,
                'flights_per_person_price'  => $request->flights_per_person_price,
                
                'flights_details'           => $request->flights_details,
                'return_flights_details'    => $request->return_flights_details,
                
                'transportation_details'=>$request->transportation_details,
                'transportation_details_more'=>$request->transportation_details_more,
                'quad_grand_total_amount'=>$request->quad_grand_total_amount,
                'triple_grand_total_amount'=>$request->triple_grand_total_amount,
                'double_grand_total_amount'=>$request->double_grand_total_amount,
                'quad_cost_price'=>$request->quad_cost_price,
                'triple_cost_price'=>$request->triple_cost_price,
                'double_cost_price'=>$request->double_cost_price,
                'all_markup_type'=>$request->all_markup_type,
                'all_markup_add'=>$request->all_markup_add,
                
                'child_flight_cost_price'       => $request->child_flight_cost_price,
                'child_flight_mark_type'        => $request->child_flight_markup_type,
                'child_flight_mark_value'       => $request->child_flight_markup_value,
                'child_flight_sale_price'       => $request->child_flight_sale,
                'child_visa_cost_price'         => $request->child_visa_cost,
                'child_visa_mark_type'          => $request->child_visa_markup_type,
                'child_visa_mark_value'         => $request->child_visa_markup_value,
                'child_visa_sale_price'         => $request->child_visa_sale,
                'child_transp_cost_price'       => $request->child_Transporationcost,
                'child_transp_mark_type'        => $request->child_Transporation_markup_type,
                'child_transp_mark_value'       => $request->child_Transporation_markup_value,
                'child_transp_sale_price'       => $request->child_Transporation_sale,
                
                'without_acc_cost_price'        => $request->without_acc_cost_price,
                'without_acc_sale_price'        => $request->without_acc_sale_price,
                
                // Child Prices
                'quad_cost_price_child'             => $request->quad_cost_price_child,
                'triple_cost_price_child'           => $request->triple_cost_price_child,
                'double_cost_price_child'           => $request->double_cost_price_child,
                'child_grand_total_cost_price'      => $request->child_total_cost_price,
                
                'quad_grand_total_amount_child'     => $request->quad_grand_total_amount_child,
                'triple_grand_total_amount_child'   => $request->triple_grand_total_amount_child,
                'double_grand_total_amount_child'   => $request->double_grand_total_amount_child,
                'child_grand_total_sale_price'      => $request->child_total_sale_price,
                
                // Infant Prices
                'quad_cost_price_infant'            => $request->quad_cost_price_infant,
                'triple_cost_price_infant'          => $request->triple_cost_price_infant,
                'double_cost_price_infant'          => $request->double_cost_price_infant,
                'infant_total_cost_price'           => $request->infant_total_cost_price,
                
                'quad_grand_total_amount_infant'    => $request->quad_grand_total_amount_infant,
                'triple_grand_total_amount_infant'  => $request->triple_grand_total_amount_infant,
                'double_grand_total_amount_infant'  => $request->double_grand_total_amount_infant,
                'infant_total_sale_price'           => $request->infant_total_sale_price,
                
                'infant_flight_cost'            => $request->infant_flight_cost,
                'infant_transp_cost'            => $request->infant_transp_cost,
                'infant_visa_cost'              => $request->infant_visa_cost,
                
                'ch_price_other_c'              => $request->child_other_prices,
                'in_price_other_c'              => $request->infant_other_prices,
                'in_markup_other_c'             => $request->infant_markup_prices,
                
                'transfer_supplier'         => $request->transfer_supplier,
                'transfer_supplier_id'      => $request->transfer_supplier_id,
                'ziyarat_details'            => $request->ziyarat_details,
            ]);
            
            $tour_batchs = DB::table('tour_batchs')->insertGetId([
                'SU_id' => $request->SU_id ?? NULL,
                'tour_id'=>$lastTourId,
                'generate_id'=>$generate_id,
                'customer_id'=>$request->customer_id,
                'city_Count'=>$request->city_Count,
                'title'=>$request->title,
                'categories'=>$request->tour_categories,
                'starts_rating'=>$request->starts_rating,
                'content'=>$request->content,
                'tour_attributes'=>$request->tour_attributes,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
                'time_duration'=>$request->time_duration,
                'tour_location'=>$request->tour_location,
                'whats_included'=>$request->whats_included,
                'whats_excluded'=>$request->whats_excluded,
                'currency_symbol'=>$request->currency_symbol,
                'tour_publish'=>$request->tour_publish,
                'tour_author'=>$request->tour_author,
                'tour_feature'=>$request->tour_feature,
                'defalut_state'=>$request->defalut_state,
                'tour_featured_image'=>$request->tour_featured_image,
                'tour_banner_image'=>$request->tour_banner_image,
                'external_packages'=>$request->external_packages,
                'no_of_pax_days'=>$request->no_of_pax_days,
                'destination_details'=>$request->destination_details,
                'destination_details_more'=>$request->destination_details_more,
                'accomodation_details'=>$request->accomodation_details,
                'accomodation_details_more'=>$request->more_accomodation_details,
                'visa_fee'=>$request->visa_fee,
                'visa_type'=>$request->visa_type,
                
                'visa_fee_purchase'=>$request->visa_fee_purchase,
                'exchange_rate_visa'=>$request->exchange_rate_visa,
                
               
                'visa_image'=>$request->visa_image,
                'visa_rules_regulations'=>$request->visa_rules_regulations,
                'gallery_images'=>$request->gallery_images,
                'payment_gateways'=>$request->payment_gateways,
                'payment_modes'=>$request->payment_modes,
                'cancellation_policy'=>$request->cancellation_policy,
                'checkout_message'=>$request->checkout_message,
                
                // Adult
                'WQFVT_details'                => $request->WQFVT_details ?? '',
                'WDFVT_details'                => $request->WDFVT_details ?? '',
                'WTFVT_details'                => $request->WTFVT_details ?? '',
                'WAFVT_details'                => $request->WAFVT_details ?? '',
                
                // Child
                'WQFVT_details_child'          => $request->WQFVT_details_child ?? '',
                'WTFVT_details_child'          => $request->WTFVT_details_child ?? '',
                'WDFVT_details_child'          => $request->WDFVT_details_child ?? '',
                'WAFVT_details_child'          => $request->WAFVT_details_child ?? '',
                
                // Infant
                'WQFVT_details_infant'         => $request->WQFVT_details_infant ?? '',
                'WTFVT_details_infant'         => $request->WTFVT_details_infant ?? '',
                'WDFVT_details_infant'         => $request->WDFVT_details_infant ?? '',
                'WAFVT_details_infant'         => $request->WAFVT_details_infant ?? '',
            ]);
            
            $lastbatchsId = $tour_batchs;
            
            $tour_batchs2 = DB::table('tour_batchs_2')->insert([
                'batchs_id'=>$lastbatchsId,
                'generate_id'=>$generate_id,
                'Itinerary_details'=>$request->Itinerary_details,
                'tour_itinerary_details_1'=>$request->tour_itinerary_details_1,
                'tour_extra_price'=>$request->tour_extra_price,
                'tour_extra_price_1'=>$request->tour_extra_price_1,
                'tour_faq'=>$request->tour_faq,
                'tour_faq_1'=>$request->tour_faq_1,
                'markup_details'=>$request->markup_details,
                'more_markup_details'=>$request->more_markup_details,
                
                'flights_details'=>$request->flights_details,
                
                'transportation_details'=>$request->transportation_details,
                'transportation_details_more'=>$request->transportation_details_more,
                'quad_grand_total_amount'=>$request->quad_grand_total_amount,
                'triple_grand_total_amount'=>$request->triple_grand_total_amount,
                'double_grand_total_amount'=>$request->double_grand_total_amount,
                'quad_cost_price'=>$request->quad_cost_price,
                'triple_cost_price'=>$request->triple_cost_price,
                'double_cost_price'=>$request->double_cost_price,
                'all_markup_type'=>$request->all_markup_type,
                'all_markup_add'=>$request->all_markup_add,
                'child_flight_cost_price'=>$request->child_flight_cost,
                'child_flight_mark_type'=>$request->child_flight_markup_type,
                'child_flight_mark_value'=>$request->child_flight_markup_value,
                'child_flight_sale_price'=>$request->child_flight_sale,
                'child_visa_cost_price'=>$request->child_visa_cost,
                'child_visa_mark_type'=>$request->child_visa_markup_type,
                'child_visa_mark_value'=>$request->child_visa_markup_value,
                'child_visa_sale_price'=>$request->child_visa_sale,
                'child_transp_cost_price'=>$request->child_Transporationcost,
                'child_transp_mark_type'=>$request->child_Transporation_markup_type,
                'child_transp_mark_value'=>$request->child_Transporation_markup_value,
                'child_transp_sale_price'=>$request->child_Transporation_sale,
                'without_acc_cost_price'=>$request->without_acc_cost_price,
                'without_acc_sale_price'=>$request->without_acc_sale_price,
                
                // Child Prices
                'quad_cost_price_child'             => $request->quad_cost_price_child,
                'triple_cost_price_child'           => $request->triple_cost_price_child,
                'double_cost_price_child'           => $request->double_cost_price_child,
                'child_grand_total_cost_price'      => $request->child_total_cost_price,
                
                'quad_grand_total_amount_child'     => $request->quad_grand_total_amount_child,
                'triple_grand_total_amount_child'   => $request->triple_grand_total_amount_child,
                'double_grand_total_amount_child'   => $request->double_grand_total_amount_child,
                'child_grand_total_sale_price'      => $request->child_total_sale_price,
                
                // Infant Prices
                'quad_cost_price_infant'            => $request->quad_cost_price_infant,
                'triple_cost_price_infant'          => $request->triple_cost_price_infant,
                'double_cost_price_infant'          => $request->double_cost_price_infant,
                'infant_total_cost_price'           => $request->infant_total_cost_price,
                
                'quad_grand_total_amount_infant'    => $request->quad_grand_total_amount_infant,
                'triple_grand_total_amount_infant'  => $request->triple_grand_total_amount_infant,
                'double_grand_total_amount_infant'  => $request->double_grand_total_amount_infant,
                'infant_total_sale_price'           => $request->infant_total_sale_price,
                
                'infant_flight_cost'=>$request->infant_flight_cost,
                'infant_transp_cost'=>$request->infant_transp_cost,
                'infant_visa_cost'=>$request->infant_visa_cost,
                
                'ch_price_other_c'=>$request->child_other_prices,
                'in_price_other_c'=>$request->infant_other_prices,
                'in_markup_other_c'=>$request->infant_markup_prices,
                
                'transfer_supplier'         => $request->transfer_supplier,
                'transfer_supplier_id'      => $request->transfer_supplier_id,
                'ziyarat_details'           => $request->ziyarat_details,
            ]);
           
            if(isset($request->transportation_details) && !empty($request->transportation_details) && $request->transportation_details != null){
                $transfer_data = json_decode($request->transportation_details);
                if(isset($transfer_data)){
                    if(1 == count($transfer_data)){
                        foreach($transfer_data as $index => $trans_res_data){
                            if(isset($trans_res_data->transportation_pick_up_location) && $trans_res_data->transportation_pick_up_location != null && $trans_res_data->transportation_pick_up_location != ''){
                                $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->select('id','balance')->first();
                                if($transfer_sup_data){
                                    $trans_sup_balance = (float)$transfer_sup_data->balance + (float)$trans_res_data->transportation_vehicle_total_price_purchase;
                                    DB::table('transfer_supplier_ledger')->insert([
                                        'SU_id' => $request->SU_id ?? NULL,
                                        'supplier_id'=>$request->transfer_supplier_id,
                                        'payment'=> $trans_res_data->transportation_vehicle_total_price_purchase,
                                        'balance'=> $trans_sup_balance,
                                        'vehicle_price'=> $trans_res_data->transportation_price_per_vehicle,
                                        'vehicle_type'=>$trans_res_data->transportation_vehicle_type,
                                        'no_of_vehicles'=> $trans_res_data->transportation_no_of_vehicle,
                                        'destination_id'=> $trans_res_data->destination_id,
                                        'package_id'=>$lastTourId,
                                        'remarks'=>'New Package Created',
                                        'date'=>date('Y-m-d'),
                                        'customer_id'=>$request->customer_id,
                                    ]);
                                    DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                                }
                            }
                        }
                    }else{
                      
                        $total_price = 0;
                        foreach($transfer_data  as $index => $trans_res_data){
                            if($trans_res_data->transportation_pick_up_location != ''){
                               $total_price += $trans_res_data->transportation_vehicle_total_price_purchase;
                              
                            }
                        }
                      
                        $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->select('id','balance')->first();
                        if(isset($transfer_sup_data)){
                            $trans_sup_balance = (float)$transfer_sup_data->balance + (float)$total_price;
                                
                            DB::table('transfer_supplier_ledger')->insert([
                                'SU_id' => $request->SU_id ?? NULL,
                                'supplier_id'=>$request->transfer_supplier_id,
                                'payment'=> $total_price,
                                'balance'=> $trans_sup_balance,
                                'package_id'=>$lastTourId,
                                'remarks'=>'New Invoice Created',
                                'date'=>date('Y-m-d'),
                                'customer_id'=>$request->customer_id,
                            ]);
                            DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                        }
                    }
                }
            }
            
            DB::commit();  
            return response()->json(['status'=>'success','message'=>'Tour2 is add','tour_batchs'=>$tour_batchs,'lastbatchsId'=>$lastbatchsId,'tour_batchs2'=>$tour_batchs2]); 
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error']);
        }
       
    }
    
    public function submit_tour_test(Request $request){
        // dd($request->all());
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
        
        $fg = json_decode($request->flight_id_array);
        $fgg = json_decode($request->flight_reserve_array);
    
        $accomodation       = json_decode($request->accomodation_details);
        $accomodation_more  = json_decode($request->more_accomodation_details);

        if($fg !='' && $fg != null){
           
           foreach($fg as $key=>$fgz){
                $update_fig = (int)$fgg[$key];
                
                $seat = DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('id',$fgz)->select('occupied_seat','flights_number_of_seat')->first();
                
                $total_occupied = $seat->occupied_seat + $update_fig;
                
                DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('id',$fgz)->update(['occupied_seat'=>$total_occupied,]);
           } 
        }

        $total_single_seats = 0;
        $total_double_seats = 0;
        $total_triple_seats = 0;
        $total_quad_seats   = 0;
        
        if(isset($accomodation) && $accomodation != null && $accomodation != ''){
            foreach($accomodation as $acc_res){
                if($acc_res->acc_type == 'Single'){
                    $total_single_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Double'){
                    $total_double_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Triple'){
                    $total_triple_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Quad'){
                    $total_quad_seats += $acc_res->acc_pax;
                }
            }
        }
        
        if(isset($accomodation_more) && $accomodation_more != null && $accomodation_more != ''){
            foreach($accomodation_more as $acc_res){
                if($acc_res->more_acc_type == 'Single'){
                    $total_single_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Double'){
                    $total_double_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Triple'){
                    $total_triple_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Quad'){
                    $total_quad_seats += $acc_res->more_acc_pax;
                }
            }
        }
        
         $accomodation_data = json_decode($request->accomodation_details);
        $accomodation_more_data = json_decode($request->more_accomodation_details);
  
        
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
                                
                              
                                
                                $user_id = $request->customer_id;
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
        // print_r($accomodation_more_data);
        
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
                                
                              
                                
                                $user_id = $request->customer_id;
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
        
        if(isset($accomodation_data) && $accomodation_data != null && $accomodation_data != ''){
            foreach($accomodation_data as $acc_res){
                if($acc_res->acc_type == 'Single'){
                    $total_single_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Double'){
                    $total_double_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Triple'){
                    $total_triple_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Quad'){
                    $total_quad_seats += $acc_res->acc_pax;
                }
            }
        }
        
        if(isset($accomodation_more_data) && $accomodation_more_data != null && $accomodation_more_data != ''){
            foreach($accomodation_more_data as $acc_res){
                if($acc_res->more_acc_type == 'Single'){
                    $total_single_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Double'){
                    $total_double_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Triple'){
                    $total_triple_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Quad'){
                    $total_quad_seats += $acc_res->more_acc_pax;
                }
            }
        }
        
        
        $request->accomodation_details = json_encode($accomodation_data);
        $request->more_accomodation_details = json_encode($accomodation_more_data);
        
        $visa_details = json_decode($request->visa_details);
            
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
                            'currency_conversion' => $request->conversion_type_Id,
                            'visa_price_conversion_rate' => $visa_res->exchange_rate_visa,
                            'visa_converted_price'=> $visa_res->visa_fee,
                            'customer_id' => $request->customer_id,
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
                                    'customer_id'=> $request->customer_id,
                                ]);
                            
                            $data=DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([  
                               'balance'               => $supplier_balance,
                            ]);
                        }
                }
                
                // // 2 Update No of Seats Occupied in Visa
                //     $visa_avail_data = DB::table('visa_Availability')->where('id',$visa_res->visa_avail_id)->first();
                    
                //     $updated_seats = $visa_avail_data->visa_available - $visa_res->visa_occupied;
                    
                //     DB::table('visa_Availability')->where('id',$visa_res->visa_avail_id)->update([
                //             'visa_available' => $updated_seats
                //         ]);
                  
                // // 3 Update Visa Supplier Balance
                //     $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->first();
                    
                    
                //     $visa_supplier_payable_balance = $visa_supplier_data->payable + $visa_res->visa_purchase_total;
                //     DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([
                //             'payable' => $visa_supplier_payable_balance
                //     ]);
                    
                //     DB::table('visa_supplier_ledger')->insert([
                //             'supplier_id' => $visa_res->visa_supplier_id,
                //             'payment' => $visa_res->visa_purchase_total,
                //             'balance' => $visa_supplier_data->balance,
                //             'payable' => $visa_supplier_payable_balance,
                //             'visa_qty' => $visa_res->visa_occupied,
                //             'visa_type' => $visa_res->visa_type_id,
                //             'package_id' => $visa_res->visa_supplier_id,
                //             'visa_avl_id' => $visa_res->visa_avail_id,
                //             'remarks' => 'New Package Created',
                //             'date' => date('Y-m-d'),
                //             'customer_id' => $request->customer_id,
                //     ]);
                    
                //     DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([
                //             'payable' => $visa_supplier_payable_balance
                //     ]);
            }
        }
        
        $visa_details = json_encode($visa_details);
        
        $generate_id=rand(0,9999999);
        $tour = new Tour();
        $tour->flight_id=$request->flight_id;
        $tour->generate_id=$generate_id;
        $tour->customer_id=$request->customer_id;
        $tour->title=$request->title;
        $tour->content=$request->content;
        $tour->categories=$request->tour_categories;
        $tour->tour_attributes=$request->tour_attributes;
        $tour->no_of_pax_days=$request->no_of_pax_days;
        $tour->city_Count=$request->city_Count;
        $tour->destination_details=$request->destination_details;
        $tour->destination_details_more=$request->destination_details_more;
        $tour->accomodation_details=$request->accomodation_details;
        $tour->accomodation_details_more=$request->more_accomodation_details;
        $tour->visa_type=$request->visa_type;
        $tour->visa_rules_regulations=$request->visa_rules_regulations;
        $tour->visa_detials = $visa_details;
        
        $tour->currency_conversion=$request->currency_conversion;
        $tour->conversion_type_Id=$request->conversion_type_Id;
        
        $tour->visa_fee_purchase=$request->visa_fee_purchase;
        $tour->exchange_rate_visa=$request->exchange_rate_visa;
        $tour->conversion_type=$request->conversion_type;
        $tour->visa_fee=$request->visa_fee;
        $tour->visa_image=$request->visa_image;
        $tour->gallery_images=$request->gallery_images;
        $tour->start_date=$request->start_date;
        $tour->end_date=$request->end_date;
        $tour->time_duration=$request->time_duration;
        $tour->tour_location=$request->tour_location;
        $tour->whats_included=$request->whats_included;
        $tour->whats_excluded=$request->whats_excluded;
        $tour->currency_symbol=$request->currency_symbol;
        $tour->tour_publish=$request->tour_publish;
        $tour->tour_author=$request->tour_author;
        $tour->others_providers_show=$request->others_providers_show;
        $tour->tour_feature=$request->tour_feature;
        $tour->defalut_state=$request->defalut_state;
        $tour->payment_gateways=$request->payment_gateways;
        $tour->payment_modes=$request->payment_modes;
        $tour->tour_featured_image=$request->tour_featured_image;
        $tour->tour_banner_image=$request->tour_banner_image;
        $tour->starts_rating=$request->starts_rating;
        $tour->checkout_message     =   $request->checkout_message;
        $tour->cancellation_policy  =   $request->cancellation_policy;
        
        // $tour->arfatData  =   $request->arfatData;
        $tour->arfat_selected  =   $request->arfat_selected;
        // $tour->muzdalfaData  =   $request->muzdalfaData;
        $tour->muzdalfa_selected  =   $request->muzdalfa_selected;
        // $tour->minaData  =   $request->minaData;
        $tour->mina_selected  =   $request->mina_selected;
        
        $tour->mina_pkg_details  =   $request->mina_pkg_details;
        $tour->arfat_pkg_details  =   $request->arfat_pkg_details;
        $tour->muzdalfa_details  =   $request->muzdalfa_details;
           
        $tour->available_seats          =   $request->no_of_pax_days;
        $tour->available_single_seats   =   $total_single_seats;
        $tour->available_double_seats   =   $total_double_seats;
        $tour->available_triple_seats   =   $total_triple_seats;
        $tour->available_quad_seats     =   $total_quad_seats;
        
        
        
        
        
        
        
        DB::beginTransaction();
        try {
            
            $tour->save();
            $lastTourId = $tour->id;
        
            // Cost
            $quad_cost_priceN               = $request->quad_cost_price ?? '0';
            $triple_cost_priceN             = $request->triple_cost_price ?? '0';
            $double_cost_priceN             = $request->double_cost_price ?? '0';
            $without_acc_cost_priceN        = $request->without_acc_cost_price ?? '0';
            $total_cost_price_all_Services  = $double_cost_priceN + $triple_cost_priceN + $quad_cost_priceN + $without_acc_cost_priceN;
            
            // Sale
            $quad_grand_total_amountN       = $request->quad_grand_total_amount ?? '0';
            $triple_grand_total_amountN     = $request->triple_grand_total_amount ?? '0';
            $double_grand_total_amountN     = $request->double_grand_total_amount ?? '0';
            $without_acc_sale_price_singleN = $request->without_acc_sale_price ?? '0';
            $total_sale_price_all_Services  = $double_grand_total_amountN + $triple_grand_total_amountN + $quad_grand_total_amountN + $without_acc_sale_price_singleN;
            
            $notification_insert = new alhijaz_Notofication();
            $notification_insert->type_of_notification_id   = $tour->id ?? '';
            $notification_insert->customer_id               = $tour->customer_id ?? '';
            $notification_insert->type_of_notification      = 'create_Package' ?? '';
            $notification_insert->generate_id               = $tour->generate_id ?? '';
            $notification_insert->notification_creator_name = 'AlhijazTours' ?? '';
            $notification_insert->total_price               = $total_sale_price_all_Services ?? '';
            $notification_insert->amount_paid               = $tour->amount_Paid ?? '';
            $notification_insert->remaining_price           = $total_sale_price_all_Services ?? '';
            $notification_insert->notification_status       = '1' ?? '';
            $notification_insert->save();
            
            $flights_details            = json_decode($request->flights_details);
            if(isset($flights_details) && $flights_details != null && $flights_details != ''){
                DB::table('flight_seats_occupied')->insert([
                    'token'                         => $request->token,
                    'type'                          => 'Package',
                    'booking_id'                    => $lastTourId,
                    'flight_supplier'               => $request->flight_supplier,
                    'flight_route_id'               => $request->flight_route_id_occupied,
                    'flights_adult_seats'           => $request->flight_total_seats,
                    'flights_child_seats'           => 0,
                    'flight_route_seats_occupied'   => $request->flight_total_seats,
                ]);
            }
            
            $tours_2 = DB::table('tours_2')->insert([
                'tour_id'=>$lastTourId,
                'Itinerary_details'=>$request->Itinerary_details,
                'tour_itinerary_details_1'=>$request->tour_itinerary_details_1,
                'tour_extra_price'=>$request->tour_extra_price,
                'tour_extra_price_1'=>$request->tour_extra_price_1,
                'tour_faq'=>$request->tour_faq,
                'tour_faq_1'=>$request->tour_faq_1,
                'markup_details'=>$request->markup_details,
                'more_markup_details'=>$request->more_markup_details,
                
                'flight_route_type'         => $request->flight_route_type,
                'flight_supplier'           => $request->flight_supplier,
                'flight_route_id_occupied'  => $request->flight_route_id_occupied,
                'flight_total_seats'        => $request->flight_total_seats,
                'flights_per_person_price'  => $request->flights_per_person_price,
                
                'flights_details'           => $request->flights_details,
                'return_flights_details'    => $request->return_flights_details,
                
                'transportation_details'=>$request->transportation_details,
                'transportation_details_more'=>$request->transportation_details_more,
                'transfer_supplier_id' =>$request->transfer_supplier_id,
                'quad_grand_total_amount'=>$request->quad_grand_total_amount,
                'triple_grand_total_amount'=>$request->triple_grand_total_amount,
                'double_grand_total_amount'=>$request->double_grand_total_amount,
                'quad_cost_price'=>$request->quad_cost_price,
                'triple_cost_price'=>$request->triple_cost_price,
                'double_cost_price'=>$request->double_cost_price,
                'all_markup_type'=>$request->all_markup_type,
                'all_markup_add'=>$request->all_markup_add,
                
                'child_flight_cost_price'       => $request->child_flight_cost_price,
                'child_flight_mark_type'        => $request->child_flight_markup_type,
                'child_flight_mark_value'       => $request->child_flight_markup_value,
                'child_flight_sale_price'       => $request->child_flight_sale,
                'child_visa_cost_price'         => $request->child_visa_cost,
                'child_visa_mark_type'          => $request->child_visa_markup_type,
                'child_visa_mark_value'         => $request->child_visa_markup_value,
                'child_visa_sale_price'         => $request->child_visa_sale,
                'child_transp_cost_price'       => $request->child_Transporationcost,
                'child_transp_mark_type'        => $request->child_Transporation_markup_type,
                'child_transp_mark_value'       => $request->child_Transporation_markup_value,
                'child_transp_sale_price'       => $request->child_Transporation_sale,
                'child_grand_total_cost_price'  => $request->child_total_cost_price,
                'child_grand_total_sale_price'  => $request->child_total_sale_price,
                'without_acc_cost_price'        => $request->without_acc_cost_price,
                'without_acc_sale_price'        => $request->without_acc_sale_price,
                
                'infant_flight_cost'            => $request->infant_flight_cost,
                'infant_transp_cost'            => $request->infant_transp_cost,
                'infant_visa_cost'              => $request->infant_visa_cost,
                'infant_total_cost_price'       => $request->infant_total_cost_price,
                'infant_total_sale_price'       => $request->infant_total_sale_price,
                
                'ch_price_other_c'              => $request->child_other_prices,
                'in_price_other_c'              => $request->infant_other_prices,
                'in_markup_other_c'             => $request->infant_markup_prices,
            ]);
            
            $tour_batchs = DB::table('tour_batchs')->insertGetId([
                'tour_id'=>$lastTourId,
                'generate_id'=>$generate_id,
                'customer_id'=>$request->customer_id,
                'city_Count'=>$request->city_Count,
                'title'=>$request->title,
                'categories'=>$request->tour_categories,
                'starts_rating'=>$request->starts_rating,
                'content'=>$request->content,
                'tour_attributes'=>$request->tour_attributes,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
                'time_duration'=>$request->time_duration,
                'tour_location'=>$request->tour_location,
                'whats_included'=>$request->whats_included,
                'whats_excluded'=>$request->whats_excluded,
                'currency_symbol'=>$request->currency_symbol,
                'tour_publish'=>$request->tour_publish,
                'tour_author'=>$request->tour_author,
                'tour_feature'=>$request->tour_feature,
                'defalut_state'=>$request->defalut_state,
                'tour_featured_image'=>$request->tour_featured_image,
                'tour_banner_image'=>$request->tour_banner_image,
                'external_packages'=>$request->external_packages,
                'no_of_pax_days'=>$request->no_of_pax_days,
                'destination_details'=>$request->destination_details,
                'destination_details_more'=>$request->destination_details_more,
                'accomodation_details'=>$request->accomodation_details,
                'accomodation_details_more'=>$request->more_accomodation_details,
                'visa_fee'=>$request->visa_fee,
                'visa_type'=>$request->visa_type,
                
                'visa_fee_purchase'=>$request->visa_fee_purchase,
                'exchange_rate_visa'=>$request->exchange_rate_visa,
                
               
                'visa_image'=>$request->visa_image,
                'visa_rules_regulations'=>$request->visa_rules_regulations,
                'gallery_images'=>$request->gallery_images,
                'payment_gateways'=>$request->payment_gateways,
                'payment_modes'=>$request->payment_modes,
                'cancellation_policy'=>$request->cancellation_policy,
                'checkout_message'=>$request->checkout_message,
            ]);
            
            $lastbatchsId = $tour_batchs;
            
            $tour_batchs2=DB::table('tour_batchs_2')->insert([
                'batchs_id'=>$lastbatchsId,
                'generate_id'=>$generate_id,
                'Itinerary_details'=>$request->Itinerary_details,
                'tour_itinerary_details_1'=>$request->tour_itinerary_details_1,
                'tour_extra_price'=>$request->tour_extra_price,
                'tour_extra_price_1'=>$request->tour_extra_price_1,
                'tour_faq'=>$request->tour_faq,
                'tour_faq_1'=>$request->tour_faq_1,
                'markup_details'=>$request->markup_details,
                'more_markup_details'=>$request->more_markup_details,
                
                'flights_details'=>$request->flights_details,
                
                'transportation_details'=>$request->transportation_details,
                'transportation_details_more'=>$request->transportation_details_more,
                'quad_grand_total_amount'=>$request->quad_grand_total_amount,
                'triple_grand_total_amount'=>$request->triple_grand_total_amount,
                'double_grand_total_amount'=>$request->double_grand_total_amount,
                'quad_cost_price'=>$request->quad_cost_price,
                'triple_cost_price'=>$request->triple_cost_price,
                'double_cost_price'=>$request->double_cost_price,
                'all_markup_type'=>$request->all_markup_type,
                'all_markup_add'=>$request->all_markup_add,
                'child_flight_cost_price'=>$request->child_flight_cost,
                'child_flight_mark_type'=>$request->child_flight_markup_type,
                'child_flight_mark_value'=>$request->child_flight_markup_value,
                'child_flight_sale_price'=>$request->child_flight_sale,
                'child_visa_cost_price'=>$request->child_visa_cost,
                'child_visa_mark_type'=>$request->child_visa_markup_type,
                'child_visa_mark_value'=>$request->child_visa_markup_value,
                'child_visa_sale_price'=>$request->child_visa_sale,
                'child_transp_cost_price'=>$request->child_Transporationcost,
                'child_transp_mark_type'=>$request->child_Transporation_markup_type,
                'child_transp_mark_value'=>$request->child_Transporation_markup_value,
                'child_transp_sale_price'=>$request->child_Transporation_sale,
                'child_grand_total_cost_price'=>$request->child_total_cost_price,
                'child_grand_total_sale_price'=>$request->child_total_sale_price,
                'without_acc_cost_price'=>$request->without_acc_cost_price,
                'without_acc_sale_price'=>$request->without_acc_sale_price,
                
                'infant_flight_cost'=>$request->infant_flight_cost,
                'infant_transp_cost'=>$request->infant_transp_cost,
                'infant_visa_cost'=>$request->infant_visa_cost,
                'infant_total_cost_price'=>$request->infant_total_cost_price,
                'infant_total_sale_price'=>$request->infant_total_sale_price,
                
                'ch_price_other_c'=>$request->child_other_prices,
                'in_price_other_c'=>$request->infant_other_prices,
                'in_markup_other_c'=>$request->infant_markup_prices,
                
            ]);
            
            if(isset($request->transportation_details) && !empty($request->transportation_details) && $request->transportation_details != null){
                $transfer_data = json_decode($request->transportation_details);
                if(isset($transfer_data)){
                    
           
                    if(1 == count($transfer_data)){
                        foreach($transfer_data as $index => $trans_res_data){
                            if(isset($trans_res_data->transportation_pick_up_location) && $trans_res_data->transportation_pick_up_location != null && $trans_res_data->transportation_pick_up_location != ''){
                                $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->select('id','balance')->first();
                                
                                if($transfer_sup_data){
                                    $trans_sup_balance = (float)$transfer_sup_data->balance + (float)$trans_res_data->transportation_vehicle_total_price_purchase;
                                    
                                    DB::table('transfer_supplier_ledger')->insert([
                                        'supplier_id'=>$request->transfer_supplier_id,
                                        'payment'=> $trans_res_data->transportation_vehicle_total_price_purchase,
                                        'balance'=> $trans_sup_balance,
                                        'vehicle_price'=> $trans_res_data->transportation_price_per_vehicle,
                                        'vehicle_type'=>$trans_res_data->transportation_vehicle_type,
                                        'no_of_vehicles'=> $trans_res_data->transportation_no_of_vehicle,
                                        'destination_id'=> $trans_res_data->destination_id,
                                        'package_id'=>$lastTourId,
                                        'remarks'=>'New Package Created',
                                        'date'=>date('Y-m-d'),
                                        'customer_id'=>$request->customer_id,
                                    ]);
                                    DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                                }
                            }
                        }
                    }else{
                      
                        $total_price = 0;
                        foreach($transfer_data  as $index => $trans_res_data){
                            if($trans_res_data->transportation_pick_up_location != ''){
                               $total_price += $trans_res_data->transportation_vehicle_total_price_purchase;
                              
                            }
                        }
                      
                        $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->select('id','balance')->first();
                        if(isset($transfer_sup_data)){
                            $trans_sup_balance = (float)$transfer_sup_data->balance + (float)$total_price;
                                
                            DB::table('transfer_supplier_ledger')->insert([
                                'supplier_id'=>$request->transfer_supplier_id,
                                'payment'=> $total_price,
                                'balance'=> $trans_sup_balance,
                                'package_id'=>$lastTourId,
                                'remarks'=>'New Invoice Created',
                                'date'=>date('Y-m-d'),
                                'customer_id'=>$request->customer_id,
                            ]);
                            DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                        }
                    }
                }
            }
                
            DB::commit();  
            return response()->json(['status'=>'success','message'=>'Tour2 is add','tour_batchs'=>$tour_batchs,'lastbatchsId'=>$lastbatchsId,'tour_batchs2'=>$tour_batchs2]); 
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error']);
        }
       
    }
    
    public function edit_tour(Request $request){
        $id                     = $request->id;
        $customer_id            = $request->customer_id;
        $categories             = DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes             = DB::table('activities_attributes')->where('customer_id',$customer_id)->get();
        $customer               = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries          = country::all();
        $all_countries_currency = country::all();
        $bir_airports           = DB::table('bir_airports')->get();
        $payment_gateways       = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes          = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol        = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        
        if($request->type == 'tour'){
            // $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$request->id)->first();
            $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$request->id)->select('tours_2.*','tours.*','tours_2.id as tour2id')->first();
        }
        
        if($request->type == 'activity'){
            $tours=DB::table('actives')->where('id',$request->id)->first();
        }
        
        $other_Hotel_Name       = DB::table('hotel_Name')->where('customer_id',$customer_id)->get();
        $airline_Name           = DB::table('airline_name_tb')->where('customer_id',$customer_id)->get();
        $visa_type_get          = DB::table('visa_types')->where('customer_id',$request->customer_id)->get();
        $mange_currencies       = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
        $supplier_detail        = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $tranfer_supplier       = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $destination_details    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $all_flight_routes      = DB::table('flight_rute')->where('customer_id',$customer_id)->get();
        $flight_suppliers       = DB::table('supplier')->where('customer_id',$customer_id)->get();
        $transfer_Vehicle       = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
        $others_providers_list  = DB::table('3rd_party_commissions')->where('customer_id',$customer_id)->get();
        $Agents_detail          = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
        $user_hotels            = Hotels::where('owner_id',$customer_id)->get();
        $customers_data         = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
        $all_curr_symboles      = country::all();
        $tranfer_vehicle        = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
        $tranfer_destination    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $tranfer_company        = DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();
        $minaPackage            = MinaPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $minaPackage1           = MinaPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $ArfatPackage           = ArfatPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $ArfatPackage1          = ArfatPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $muzdalfaPackage        = MuzdilifaPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $muzdalfaPackage1       = MuzdilifaPackage::orderBy('id','DESC')->where('customer_id',$customer_id)->get();
        $visa_supplier          = DB::table('visa_Sup')->where('customer_id',$customer_id)->get();
        $visa_types             = DB::table('visa_types')->where('customer_id',$customer_id)->get();
        $custom_Meal_Types      = DB::table('custom_Meal_Types')->where('customer_id',$request->customer_id)->get();
        
        $airportsJson           = Storage::get('airports.json');
        $airports               = json_decode($airportsJson, true);
        
        return response()->json(['message'=>'success','flight_suppliers'=>$flight_suppliers,'all_flight_routes'=>$all_flight_routes,'tranfer_supplier'=>$tranfer_supplier,'destination_details'=>$destination_details,'supplier_detail'=>$supplier_detail,'visa_type_get'=>$visa_type_get,'mange_currencies'=>$mange_currencies,'airline_Name'=>$airline_Name,'other_Hotel_Name' => $other_Hotel_Name,'tours'=>$tours,'currency_Symbol'=>$currency_Symbol,'payment_modes'=>$payment_modes,'payment_gateways'=>$payment_gateways,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,
            'bir_airports'          => $bir_airports,
            'minaPackage'           => $minaPackage,
            'minaPackage1'          => $minaPackage1,
            'ArfatPackage'          => $ArfatPackage,
            'ArfatPackage1'         => $ArfatPackage1,
            'muzdalfaPackage'       => $muzdalfaPackage,
            'muzdalfaPackage1'      => $muzdalfaPackage1,
            'visa_supplier'         => $visa_supplier,
            'visa_types'            => $visa_types,
            'transfer_Vehicle'      => $transfer_Vehicle,
            'others_providers_list' => $others_providers_list,
            'Agents_detail'         => $Agents_detail,
            'user_hotels'           => $user_hotels,
            'customers_data'        => $customers_data,
            'all_curr_symboles'     => $all_curr_symboles,
            'tranfer_vehicle'       => $tranfer_vehicle,
            'tranfer_destination'   => $tranfer_destination,
            'tranfer_company'       => $tranfer_company,
            'custom_Meal_Types'     => $custom_Meal_Types,
            'airports'              => $airports,
        ]);
    }

    public function submit_edit_tour(Request $request){
        
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
        
        $id             = $request->id;
        $generate_id    = rand(0,9999999);
        $activities     = Tour::find($id);
        
        // dd($request);
        
        DB::beginTransaction();
        try {
            $accomodation_data = json_decode($request->accomodation_details);
            $accomodation_more_data = json_decode($request->more_accomodation_details);
            
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
                                    
                                  
                                    
                                    $user_id = $request->customer_id;
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
            
            // print_r($accomodation_more_data);
            
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
                                    
                                  
                                    
                                    $user_id = $request->customer_id;
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
            
            $request->accomodation_details = json_encode($accomodation_data);
            $request->more_accomodation_details = json_encode($accomodation_more_data);
            
            $visa_details = json_decode($request->visa_details);
            
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
                                'currency_conversion' => $request->conversion_type_Id,
                                'visa_price_conversion_rate' => $visa_res->exchange_rate_visa,
                                'visa_converted_price'=> $visa_res->visa_fee,
                                'customer_id' => $request->customer_id,
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
                                        'customer_id'=> $request->customer_id,
                                    ]);
                                
                                $data=DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([  
                                   'balance'               => $supplier_balance,
                                ]);
                            }
                    }
                    
                 
                }
            }
            
            $visa_details = json_encode($visa_details);
         
            if($activities)
            { 
                if($request->package_update_type == 'new'){
                    $activities->generate_id=$generate_id;
                }
                
                $activities->customer_id            = $request->customer_id;
                // Package_Details
                $activities->title                  = $request->title;
                $activities->no_of_pax_days         = $request->no_of_pax_days;
                $activities->city_Count             = $request->city_Count;
                $activities->starts_rating          = $request->starts_rating;
                $activities->currency_symbol        = $request->currency_symbol;
                $activities->content                = $request->content;
                $activities->start_date             = $request->start_date;
                $activities->end_date               = $request->end_date;
                $activities->time_duration          = $request->time_duration;
                $activities->categories             = $request->tour_categories;
                $activities->tour_feature           = $request->tour_feature;
                $activities->defalut_state          = $request->defalut_state;
                $activities->tour_featured_image    = $request->tour_featured_image;
                $activities->tour_banner_image      = $request->tour_banner_image;
                $activities->tour_author            = $request->tour_author;
                $activities->gallery_images         = $request->gallery_images;
                $activities->payment_gateways       = $request->payment_gateways;
                $activities->payment_modes          = $request->payment_modes;
                $activities->tour_location          = $request->tour_location;
                $activities->conversion_type        = $request->conversion_type;
                
                $activities->currency_conversion    = $request->currency_conversion;
                $activities->conversion_type_Id     = $request->conversion_type_Id;
                
                // Destination_details
                $activities->destination_details=$request->destination_details;
                $activities->destination_details_more=$request->destination_details_more;
                // Accomodation_Details
                $activities->accomodation_details=$request->accomodation_details;
                $activities->accomodation_details_more=$request->more_accomodation_details;
                // Visa_Details
                
                // dd($request->visa_fee);
                
                $activities->visa_type=$request->visa_type;
                $activities->visa_rules_regulations=$request->visa_rules_regulations;
                $activities->visa_fee=$request->visa_fee;
                
                $activities->visa_fee_purchase=$request->visa_fee_purchase;
                $activities->exchange_rate_visa=$request->exchange_rate_visa;
                $activities->visa_image=$request->visa_image;
                
                $activities->visa_detials = $visa_details;
                
                $activities->arfat_selected     = $request->arfat_selected;
                $activities->muzdalfa_selected  = $request->muzdalfa_selected;
                $activities->mina_selected      = $request->mina_selected;
                
                $activities->mina_pkg_details   = $request->mina_pkg_details;
                $activities->arfat_pkg_details  = $request->arfat_pkg_details;
                $activities->muzdalfa_details   = $request->muzdalfa_details;
                
                $activities->whats_included         = $request->whats_included;
                $activities->whats_excluded         = $request->whats_excluded;
                $activities->cancellation_policy    = $request->cancellation_policy;
                $activities->checkout_message       = $request->checkout_message;
                
                $activities->departure_Country      = $request->departure_Country ?? NULL;
                $activities->departureAirportCode   = $request->departureAirportCode ?? NULL;
                
                $total_single_seats = 0;
                $total_double_seats = 0;
                $total_triple_seats = 0;
                $total_quad_seats   = 0;
                
                if(isset($accomodation_data) && $accomodation_data != null && $accomodation_data != ''){
                    foreach($accomodation_data as $acc_res){
                        if($acc_res->acc_type == 'Single'){
                            $total_single_seats += $acc_res->acc_pax;
                        }
                        
                        if($acc_res->acc_type == 'Double'){
                            $total_double_seats += $acc_res->acc_pax;
                        }
                        
                        if($acc_res->acc_type == 'Triple'){
                            $total_triple_seats += $acc_res->acc_pax;
                        }
                        
                        if($acc_res->acc_type == 'Quad'){
                            $total_quad_seats += $acc_res->acc_pax;
                        }
                    }
                }
                
                if(isset($accomodation_more_data) && $accomodation_more_data != null && $accomodation_more_data != ''){
                    foreach($accomodation_more_data as $acc_res){
                        if($acc_res->more_acc_type == 'Single'){
                            $total_single_seats += $acc_res->more_acc_pax;
                        }
                        
                        if($acc_res->more_acc_type == 'Double'){
                            $total_double_seats += $acc_res->more_acc_pax;
                        }
                        
                        if($acc_res->more_acc_type == 'Triple'){
                            $total_triple_seats += $acc_res->more_acc_pax;
                        }
                        
                        if($acc_res->more_acc_type == 'Quad'){
                            $total_quad_seats += $acc_res->more_acc_pax;
                        }
                    }
                }
                
                $activities->available_seats        = $request->no_of_pax_days;
                $activities->available_single_seats = $total_single_seats;
                $activities->available_double_seats = $total_double_seats;
                $activities->available_triple_seats = $total_triple_seats;
                $activities->available_quad_seats   = $total_quad_seats;
                
                // Adult
                $activities->WQFVT_details          = $request->WQFVT_details ?? '';
                $activities->WDFVT_details          = $request->WDFVT_details ?? '';
                $activities->WTFVT_details          = $request->WTFVT_details ?? '';
                $activities->WAFVT_details          = $request->WAFVT_details ?? '';
                
                // Child
                $activities->WQFVT_details_child    = $request->WQFVT_details_child ?? '';
                $activities->WTFVT_details_child    = $request->WTFVT_details_child ?? '';
                $activities->WDFVT_details_child    = $request->WDFVT_details_child ?? '';
                $activities->WAFVT_details_child    = $request->WAFVT_details_child ?? '';
                
                // Infant
                $activities->WQFVT_details_infant   = $request->WQFVT_details_infant ?? '';
                $activities->WTFVT_details_infant   = $request->WTFVT_details_infant ?? '';
                $activities->WDFVT_details_infant   = $request->WDFVT_details_infant ?? '';
                $activities->WAFVT_details_infant   = $request->WAFVT_details_infant ?? '';
                
                $activities->update();
                
                $flight_route_id_occupied = $request->flight_route_id_occupied;
                if(isset($flight_route_id_occupied) && $flight_route_id_occupied != null && $flight_route_id_occupied != ''){
                    DB::table('flight_seats_occupied')->where('booking_id',$id)->update([
                        'token'                         => $request->token,
                        'type'                          => 'Package',
                        'booking_id'                    => $id,
                        'flight_supplier'               => $request->flight_supplier,
                        'flight_route_id'               => $request->flight_route_id_occupied,
                        'flights_adult_seats'           => $request->flight_total_seats,
                        'flights_child_seats'           => 0,
                        'flight_route_seats_occupied'   => $request->flight_total_seats,
                    ]);
                }
                
                $tours_prev_data= DB::table('tours_2')->where('tour_id',$id)->select('flight_supplier','flight_route_id_occupied','tour_id','transfer_supplier_id','transportation_details')->first();
                $tour_id = $id;
                $previous_flight_sup = $tours_prev_data->flight_supplier;
                $previous_flight_route = $tours_prev_data->flight_route_id_occupied;
                
                $activities2 = DB::table('tours_2')->where('tour_id',$id)->update([
                    
                    'flight_route_type'         => $request->flight_route_type,
                    'flight_supplier'           => $request->flight_supplier,
                    'flight_route_id_occupied'  => $request->flight_route_id_occupied,
                    'flight_total_seats'        => $request->flight_total_seats,
                    'flights_per_person_price'  => $request->flights_per_person_price,
                    
                    'flights_details'           => $request->flights_details,
                    'return_flights_details'    => $request->return_flights_details,
                    
                    'transportation_details'=> $request->transportation_details,
                    'transportation_details_more'=> $request->transportation_details_more,
                    'Itinerary_details'=> $request->Itinerary_details,
                    'tour_itinerary_details_1'=> $request->tour_itinerary_details_1,
                    'tour_extra_price'=> $request->tour_extra_price,
                    'tour_extra_price_1'=> $request->tour_extra_price_1,
                    'tour_faq'=> $request->tour_faq,
                    'tour_faq_1'=> $request->tour_faq_1,
                    'quad_cost_price'=> $request->quad_cost_price,
                    'triple_cost_price'=> $request->triple_cost_price,
                    'double_cost_price'=> $request->double_cost_price,
                    'quad_grand_total_amount'=> $request->quad_grand_total_amount,
                    'triple_grand_total_amount'=> $request->triple_grand_total_amount,
                    'double_grand_total_amount'=> $request->double_grand_total_amount,
                    'all_markup_type'=> $request->all_markup_type,
                    'all_markup_add'=> $request->all_markup_add,
                    'markup_details'=> $request->markup_details,
                    'more_markup_details'=> $request->more_markup_details,
                    
                    'child_flight_cost_price'       => $request->child_flight_cost_price,
                    'child_flight_mark_type'        => $request->child_flight_markup_type,
                    'child_flight_mark_value'       => $request->child_flight_markup_value,
                    'child_flight_sale_price'       => $request->child_flight_sale,
                    'child_visa_cost_price'         => $request->child_visa_cost,
                    'child_visa_mark_type'          => $request->child_visa_markup_type,
                    'child_visa_mark_value'         => $request->child_visa_markup_value,
                    'child_visa_sale_price'         => $request->child_visa_sale,
                    'child_transp_cost_price'       => $request->child_Transporationcost,
                    'child_transp_mark_type'        => $request->child_Transporation_markup_type,
                    'child_transp_mark_value'       => $request->child_Transporation_markup_value,
                    'child_transp_sale_price'       => $request->child_Transporation_sale,
                    'without_acc_cost_price'        => $request->without_acc_cost_price,
                    'without_acc_sale_price'        => $request->without_acc_sale_price,
                    
                    'infant_flight_cost'            => $request->infant_flight_cost,
                    'infant_transp_cost'            => $request->infant_transp_cost,
                    'infant_visa_cost'              => $request->infant_visa_cost,
                    
                    'ch_price_other_c'              => $request->child_other_prices,
                    'in_price_other_c'              => $request->infant_other_prices,
                    'in_markup_other_c'             => $request->infant_markup_prices,
                    
                    'without_acc_sale_price'        => $request->without_acc_sale_price,
                    
                    // Child Prices
                    'quad_cost_price_child'             => $request->quad_cost_price_child,
                    'triple_cost_price_child'           => $request->triple_cost_price_child,
                    'double_cost_price_child'           => $request->double_cost_price_child,
                    'child_grand_total_cost_price'      => $request->child_total_cost_price,
                    
                    'quad_grand_total_amount_child'     => $request->quad_grand_total_amount_child,
                    'triple_grand_total_amount_child'   => $request->triple_grand_total_amount_child,
                    'double_grand_total_amount_child'   => $request->double_grand_total_amount_child,
                    'child_grand_total_sale_price'      => $request->child_total_sale_price,
                    
                    // Infant Prices
                    'quad_cost_price_infant'            => $request->quad_cost_price_infant,
                    'triple_cost_price_infant'          => $request->triple_cost_price_infant,
                    'double_cost_price_infant'          => $request->double_cost_price_infant,
                    'infant_total_cost_price'           => $request->infant_total_cost_price,
                    
                    'quad_grand_total_amount_infant'    => $request->quad_grand_total_amount_infant,
                    'triple_grand_total_amount_infant'  => $request->triple_grand_total_amount_infant,
                    'double_grand_total_amount_infant'  => $request->double_grand_total_amount_infant,
                    'infant_total_sale_price'           => $request->infant_total_sale_price,
                    
                    'transfer_supplier'         => $request->transfer_supplier,
                    'transfer_supplier_id'      => $request->transfer_supplier_id,
                    'ziyarat_details'           => $request->ziyarat_details,
                ]);
                
                if($request->package_update_type == 'old'){
                    
                    $tour_bactches  = DB::table('tour_batchs')->where('generate_id',$request->prev_generate_code)->update([
                        'tour_id'=>$id,
                        'customer_id'=>$request->customer_id,
                        'city_Count'=>$request->city_Count,
                        'title'=> $request->title,
                        'no_of_pax_days'=> $request->no_of_pax_days,
                        'starts_rating'=> $request->starts_rating,
                        'currency_symbol'=> $request->currency_symbol,
                        'content'=> $request->content,
                        'start_date'=> $request->start_date,
                        'end_date'=> $request->end_date,
                        'time_duration'=> $request->time_duration,
                        'categories'=> $request->tour_categories,
                        'tour_feature'=> $request->tour_feature,
                        'defalut_state'=> $request->defalut_state,
                        'tour_featured_image'=> $request->tour_featured_image,
                        'tour_banner_image'=> $request->tour_banner_image,
                        'tour_author'=> $request->tour_author,
                        'gallery_images'=> $request->gallery_images,
                        'destination_details'=> $request->destination_details,
                        'destination_details_more'=> $request->destination_details_more,
                        'accomodation_details'=> $request->accomodation_details,
                        'accomodation_details_more'=> $request->more_accomodation_details,
                        'visa_type'=> $request->visa_type,
                        'visa_rules_regulations'=> $request->visa_rules_regulations,
                        'visa_fee'=> $request->visa_fee,
                        'visa_fee_purchase'=> $request->visa_fee_purchase,
                        'exchange_rate_visa'=> $request->exchange_rate_visa,
                        'visa_image'=> $request->visa_image,
                        'whats_included'=> $request->whats_included,
                        'whats_excluded'=> $request->whats_excluded,
                        'payment_gateways'=> $request->payment_gateways,
                        'payment_modes'=> $request->payment_modes,
                        'tour_location'=> $request->tour_location,
                        
                        'cancellation_policy'=> $request->cancellation_policy,
                        
                        // Adult
                        'WQFVT_details'                => $request->WQFVT_details ?? '',
                        'WDFVT_details'                => $request->WDFVT_details ?? '',
                        'WTFVT_details'                => $request->WTFVT_details ?? '',
                        'WAFVT_details'                => $request->WAFVT_details ?? '',
                        
                        // Child
                        'WQFVT_details_child'          => $request->WQFVT_details_child ?? '',
                        'WTFVT_details_child'          => $request->WTFVT_details_child ?? '',
                        'WDFVT_details_child'          => $request->WDFVT_details_child ?? '',
                        'WAFVT_details_child'          => $request->WAFVT_details_child ?? '',
                        
                        // Infant
                        'WQFVT_details_infant'         => $request->WQFVT_details_infant ?? '',
                        'WTFVT_details_infant'         => $request->WTFVT_details_infant ?? '',
                        'WDFVT_details_infant'         => $request->WDFVT_details_infant ?? '',
                        'WAFVT_details_infant'         => $request->WAFVT_details_infant ?? '',
                    ]);
                    
                    $tour_bactches2 = DB::table('tour_batchs_2')->where('generate_id',$request->prev_generate_code)->update([
                        'flights_details'=> $request->flights_details,
                        
                        'transportation_details'=> $request->transportation_details,
                        'transportation_details_more'=> $request->transportation_details_more,
                        'Itinerary_details'=> $request->Itinerary_details,
                        'tour_itinerary_details_1'=> $request->tour_itinerary_details_1,
                        'tour_extra_price'=> $request->tour_extra_price,
                        'tour_extra_price_1'=> $request->tour_extra_price_1,
                        'tour_faq'=> $request->tour_faq,
                        'tour_faq_1'=> $request->tour_faq_1,
                        'quad_cost_price'=> $request->quad_cost_price,
                        'triple_cost_price'=> $request->triple_cost_price,
                        'double_cost_price'=> $request->double_cost_price,
                        'quad_grand_total_amount'=> $request->quad_grand_total_amount,
                        'triple_grand_total_amount'=> $request->triple_grand_total_amount,
                        'double_grand_total_amount'=> $request->double_grand_total_amount,
                        'all_markup_type'=> $request->all_markup_type,
                        'all_markup_add'=> $request->all_markup_add,
                        'markup_details'=> $request->markup_details,
                        'more_markup_details'=> $request->more_markup_details,
                        
                        'infant_flight_cost'=>$request->infant_flight_cost,
                        'infant_transp_cost'=>$request->infant_transp_cost,
                        'infant_visa_cost'=>$request->infant_visa_cost,
                        
                        'ch_price_other_c'=>$request->child_other_prices,
                        'in_price_other_c'=>$request->infant_other_prices,
                        'in_markup_other_c'=>$request->infant_markup_prices,
                        
                        // Child Prices
                        'quad_cost_price_child'             => $request->quad_cost_price_child,
                        'triple_cost_price_child'           => $request->triple_cost_price_child,
                        'double_cost_price_child'           => $request->double_cost_price_child,
                        'child_grand_total_cost_price'      => $request->child_total_cost_price,
                        
                        'quad_grand_total_amount_child'     => $request->quad_grand_total_amount_child,
                        'triple_grand_total_amount_child'   => $request->triple_grand_total_amount_child,
                        'double_grand_total_amount_child'   => $request->double_grand_total_amount_child,
                        'child_grand_total_sale_price'      => $request->child_total_sale_price,
                        
                        // Infant Prices
                        'quad_cost_price_infant'            => $request->quad_cost_price_infant,
                        'triple_cost_price_infant'          => $request->triple_cost_price_infant,
                        'double_cost_price_infant'          => $request->double_cost_price_infant,
                        'infant_total_cost_price'           => $request->infant_total_cost_price,
                        
                        'quad_grand_total_amount_infant'    => $request->quad_grand_total_amount_infant,
                        'triple_grand_total_amount_infant'  => $request->triple_grand_total_amount_infant,
                        'double_grand_total_amount_infant'  => $request->double_grand_total_amount_infant,
                        'infant_total_sale_price'           => $request->infant_total_sale_price,
                        
                        'transfer_supplier'         => $request->transfer_supplier,
                        'transfer_supplier_id'      => $request->transfer_supplier_id,
                        'ziyarat_details'           => $request->ziyarat_details,
                    ]);
                   
                }else{
                    
                    $tour_bactches=DB::table('tour_batchs')->insertGetId([
                        'tour_id'=>$id,
                        'generate_id'=>$generate_id,
                        'customer_id'=>$request->customer_id,
                        'title'=> $request->title,
                        'city_Count'=>$request->city_Count,
                        'no_of_pax_days'=> $request->no_of_pax_days,
                        'starts_rating'=> $request->starts_rating,
                        'currency_symbol'=> $request->currency_symbol,
                        'content'=> $request->content,
                        'start_date'=> $request->start_date,
                        'end_date'=> $request->end_date,
                        'time_duration'=> $request->time_duration,
                        'categories'=> $request->tour_categories,
                        'tour_feature'=> $request->tour_feature,
                        'defalut_state'=> $request->defalut_state,
                        'tour_featured_image'=> $request->tour_featured_image,
                        'tour_banner_image'=> $request->tour_banner_image,
                        'tour_author'=> $request->tour_author,
                        'gallery_images'=> $request->gallery_images,
                        'destination_details'=> $request->destination_details,
                        'destination_details_more'=> $request->destination_details_more,
                        'accomodation_details'=> $request->accomodation_details,
                        'accomodation_details_more'=> $request->more_accomodation_details,
                        'visa_type'=> $request->visa_type,
                        
                        'visa_fee_purchase'=> $request->visa_fee_purchase,
                        'exchange_rate_visa'=> $request->exchange_rate_visa,
                        
                        'visa_rules_regulations'=> $request->visa_rules_regulations,
                        'visa_fee'=> $request->visa_fee,
                        'visa_image'=> $request->visa_image,
                        'whats_included'=> $request->whats_included,
                        'whats_excluded'=> $request->whats_excluded,
                        'payment_gateways'=> $request->payment_gateways,
                        'payment_modes'=> $request->payment_modes,
                        'tour_location'=> $request->tour_location,
                        
                        'cancellation_policy'=> $request->cancellation_policy,
                        'checkout_message'=> $request->checkout_message,
                        
                        
                        // Adult
                        'WQFVT_details'                => $request->WQFVT_details ?? '',
                        'WDFVT_details'                => $request->WDFVT_details ?? '',
                        'WTFVT_details'                => $request->WTFVT_details ?? '',
                        'WAFVT_details'                => $request->WAFVT_details ?? '',
                        
                        // Child
                        'WQFVT_details_child'          => $request->WQFVT_details_child ?? '',
                        'WTFVT_details_child'          => $request->WTFVT_details_child ?? '',
                        'WDFVT_details_child'          => $request->WDFVT_details_child ?? '',
                        'WAFVT_details_child'          => $request->WAFVT_details_child ?? '',
                        
                        // Infant
                        'WQFVT_details_infant'         => $request->WQFVT_details_infant ?? '',
                        'WTFVT_details_infant'         => $request->WTFVT_details_infant ?? '',
                        'WDFVT_details_infant'         => $request->WDFVT_details_infant ?? '',
                        'WAFVT_details_infant'         => $request->WAFVT_details_infant ?? '',
                    ]);
                    
                    $lastbatchsId = $tour_bactches;
                    
                    $tour_bactches2=DB::table('tour_batchs_2')->insert([
                        'batchs_id'=>$lastbatchsId,
                        'generate_id'=>$generate_id,
                        'flights_details'=> $request->flights_details,
                        
                        'transportation_details'=> $request->transportation_details,
                        'transportation_details_more'=> $request->transportation_details_more,
                        'Itinerary_details'=> $request->Itinerary_details,
                        'tour_itinerary_details_1'=> $request->tour_itinerary_details_1,
                        'tour_extra_price'=> $request->tour_extra_price,
                        'tour_extra_price_1'=> $request->tour_extra_price_1,
                        'tour_faq'=> $request->tour_faq,
                        'tour_faq_1'=> $request->tour_faq_1,
                        'quad_cost_price'=> $request->quad_cost_price,
                        'triple_cost_price'=> $request->triple_cost_price,
                        'double_cost_price'=> $request->double_cost_price,
                        'quad_grand_total_amount'=> $request->quad_grand_total_amount,
                        'triple_grand_total_amount'=> $request->triple_grand_total_amount,
                        'double_grand_total_amount'=> $request->double_grand_total_amount,
                        'all_markup_type'=> $request->all_markup_type,
                        'all_markup_add'=> $request->all_markup_add,
                        'markup_details'=> $request->markup_details,
                        'more_markup_details'=> $request->more_markup_details,
                        
                        // Child Prices
                        'quad_cost_price_child'             => $request->quad_cost_price_child,
                        'triple_cost_price_child'           => $request->triple_cost_price_child,
                        'double_cost_price_child'           => $request->double_cost_price_child,
                        'child_grand_total_cost_price'      => $request->child_total_cost_price,
                        
                        'quad_grand_total_amount_child'     => $request->quad_grand_total_amount_child,
                        'triple_grand_total_amount_child'   => $request->triple_grand_total_amount_child,
                        'double_grand_total_amount_child'   => $request->double_grand_total_amount_child,
                        'child_grand_total_sale_price'      => $request->child_total_sale_price,
                        
                        // Infant Prices
                        'quad_cost_price_infant'            => $request->quad_cost_price_infant,
                        'triple_cost_price_infant'          => $request->triple_cost_price_infant,
                        'double_cost_price_infant'          => $request->double_cost_price_infant,
                        'infant_total_cost_price'           => $request->infant_total_cost_price,
                        
                        'quad_grand_total_amount_infant'    => $request->quad_grand_total_amount_infant,
                        'triple_grand_total_amount_infant'  => $request->triple_grand_total_amount_infant,
                        'double_grand_total_amount_infant'  => $request->double_grand_total_amount_infant,
                        'infant_total_sale_price'           => $request->infant_total_sale_price,
                        
                        'transfer_supplier'         => $request->transfer_supplier,
                        'transfer_supplier_id'      => $request->transfer_supplier_id,
                        'ziyarat_details'           => $request->ziyarat_details,
                    ]);
                   
                }
                
                // Update Flight Supplier
                if($previous_flight_route != $request->flight_route_id_occupied){
                    
                    
                    // Remove From Previous One
                    
                    $route_obj      = DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('id',$previous_flight_route)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    $route_obj_new  = DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('id',$request->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                    
                    $Cart_details = Cart_details::where('tour_id',$tour_id)->select('tour_id','cart_total_data','invoice_no')->get();
                    //  print_r($cart_res);die;
                    
                    foreach($Cart_details as $cart_res){
                        $cart_prev_data = json_decode($cart_res->cart_total_data);
                        
                        // Update For Previous
                        
                        // dd($previous_flight_route);
                        
                        // Calaculate Child Prev Price Differ
                        $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price ?? '0' * $cart_prev_data->total_childs;
                        $child_price_wi_child_price_prev = $route_obj->flights_per_child_price ?? '0' * $cart_prev_data->total_childs;
                        
                        $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                        
                        
                        // Calaculate Infant Prev Price
                        $infant_price_prev = $route_obj->flights_per_infant_price ?? '0' * $cart_prev_data->total_Infants;
                        
                        
                        $supplier_data = DB::table('supplier')->where('customer_id',$request->customer_id)->where('id',$previous_flight_sup)->first();
                        
                        if($price_diff_prev != 0 || $infant_price_prev != 0){
                            $supplier_balance = $supplier_data->balance + $price_diff_prev;
                            
                            $supplier_balance = $supplier_balance - $infant_price_prev;
                            $total_differ = $price_diff_prev - $infant_price_prev;
                            
                            DB::table('flight_supplier_ledger')->insert([
                                        'supplier_id'=>$supplier_data->id,
                                        'payment'=>$total_differ,
                                        'balance'=>$supplier_balance,
                                        'route_id'=>$route_obj->id ?? $route_obj_new->id,
                                        'date'=>date('Y-m-d'),
                                        'customer_id'=>$request->customer_id,
                                        'adult_price'=>$route_obj->flights_per_person_price ?? '0',
                                        'child_price'=>$route_obj->flights_per_child_price ?? '0',
                                        'infant_price'=>$route_obj->flights_per_infant_price ?? '0',
                                        'adult_seats_booked'=>$cart_prev_data->total_adults,
                                        'child_seats_booked'=>$cart_prev_data->total_childs,
                                        'infant_seats_booked'=>$cart_prev_data->total_Infants,
                                        'package_invoice_no'=>$cart_res->invoice_no,
                                        'remarks'=>'Package Route Updated',
                                      ]);
                                      
                            DB::table('supplier')->where('customer_id',$request->customer_id)->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                        }
                        
                        
                        // Update For New 
                        
                        // dd($route_obj_new);
                        if(isset($route_obj_new->flights_per_person_price) && $route_obj_new->flights_per_person_price > 0){
                            $flights_per_person_price = $route_obj_new->flights_per_person_price;
                        }else{
                            $flights_per_person_price = 1;
                        }
                        
                        if(isset($route_obj_new->flights_per_child_price) && $route_obj_new->flights_per_child_price > 0){
                            $flights_per_child_price = $route_obj_new->flights_per_child_price;
                        }else{
                            $flights_per_child_price = 1;
                        }
                        
                        // Calaculate Child Prev Price Differ
                        $child_price_wi_adult_price_prev = $flights_per_person_price * $cart_prev_data->total_childs;
                        $child_price_wi_child_price_prev = $flights_per_child_price * $cart_prev_data->total_childs;
                        
                        $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                        
                        if(isset($route_obj_new->flights_per_infant_price) && $route_obj_new->flights_per_infant_price > 0){
                            $flights_per_infant_price = $route_obj_new->flights_per_infant_price;
                        }else{
                            $flights_per_infant_price = 1;
                        }
                        
                        // Calaculate Infant Prev Price
                        $infant_price_prev = $flights_per_infant_price * $cart_prev_data->total_Infants;
                        
                        
                        $supplier_data = DB::table('supplier')->where('customer_id',$request->customer_id)->where('id',$request->flight_supplier)->first();
                        
                        if($price_diff_prev != 0 || $infant_price_prev != 0){
                            $supplier_balance = $supplier_data->balance - $price_diff_prev;
                            
                            $supplier_balance = $supplier_balance + $infant_price_prev;
                            $total_differ = $infant_price_prev - $price_diff_prev;
                           
                            
                            DB::table('flight_supplier_ledger')->insert([
                                        'supplier_id'=>$supplier_data->id,
                                        'payment'=>$total_differ,
                                        'balance'=>$supplier_balance,
                                        'route_id'=>$route_obj_new->id,
                                        'date'=>date('Y-m-d'),
                                        'customer_id'=>$request->customer_id,
                                        'adult_price'=>$route_obj_new->flights_per_person_price ?? 0,
                                        'child_price'=>$route_obj_new->flights_per_child_price ?? 0,
                                        'infant_price'=>$route_obj_new->flights_per_infant_price ?? 0,
                                        'adult_seats_booked'=>$cart_prev_data->total_adults,
                                        'child_seats_booked'=>$cart_prev_data->total_childs,
                                        'infant_seats_booked'=>$cart_prev_data->total_Infants,
                                        'package_invoice_no'=>$cart_res->invoice_no,
                                        'remarks'=>'Package Route Updated',
                                      ]);
                                      
                            DB::table('supplier')->where('customer_id',$request->customer_id)->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                        }
                    }
                    
                    
                }
                
                // dd($tours_prev_data);
                $previous_transfer_sup = $tours_prev_data->transfer_supplier_id;
                $new_transfer_sup = $request->transfer_supplier_id;
                
                $prev_transfer_det = json_decode($tours_prev_data->transportation_details);
                $new_transfer_det = json_decode($request->transportation_details);
                
                // Transfer Working 
                if($previous_transfer_sup == $new_transfer_sup){
                    // Calculate Previous Total
                    $transfer_prev_total = 0;
                    if(isset($prev_transfer_det) && !empty($prev_transfer_det) && $prev_transfer_det != null){
                            $transfer_data = $prev_transfer_det;
            
                            if(isset($transfer_data)){
                                    if(1 == count($transfer_data)){
                                          foreach($transfer_data  as $index => $trans_res_data){
                                 
                                                 $transfer_prev_total += (int) $trans_res_data->transportation_vehicle_total_price_purchase;
                                        
                                         }
                                        
                                    }else{
                                      
                                      foreach($transfer_data  as $index => $trans_res_data){
                                        if($trans_res_data->transportation_pick_up_location != ''){
                                           $transfer_prev_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                          
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
                                 
                                                 $transfer_new_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                        
                                         }
                                        
                                    }else{
                                      
                                      foreach($transfer_data  as $index => $trans_res_data){
                                        if($trans_res_data->transportation_pick_up_location != ''){
                                           $transfer_new_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                          
                                        }
                                      }
                                    
                                }
                            }
                        }
                        
                    $price_diff = $transfer_new_total - $transfer_prev_total;
                    
                    // Update Supplier Balance
                    $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->select('id','balance')->first();
                    
                    if(isset($transfer_sup_data)){       
                        $trans_sup_balance = $transfer_sup_data->balance + $price_diff;
                        
                        if($price_diff != 0){
                            DB::table('transfer_supplier_ledger')->insert([
                                    'supplier_id'=>$request->transfer_supplier_id,
                                    'payment'=> $price_diff,
                                    'balance'=> $trans_sup_balance,
                                    'package_id'=>$id,
                                    'remarks'=>'Package Updated',
                                    'date'=>date('Y-m-d'),
                                    'customer_id'=>$request->customer_id,
                                ]);
                            DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
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
                                 
                                                 $transfer_prev_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                        
                                         }
                                        
                                    }else{
                                      
                                      foreach($transfer_data  as $index => $trans_res_data){
                                        if($trans_res_data->transportation_pick_up_location != ''){
                                           $transfer_prev_total += (float)$trans_res_data->transportation_vehicle_total_price_purchase;
                                          
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
                                                'package_id'=>$id,
                                                'remarks'=>'Package Updated',
                                                'date'=>date('Y-m-d'),
                                                'customer_id'=>$request->customer_id,
                                            ]);
                                        DB::table('transfer_Invoice_Supplier')->where('id',$previous_transfer_sup)->update(['balance'=>$trans_sup_balance]);
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
                                 
                                                 $transfer_new_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                        
                                         }
                                        
                                    }else{
                                      
                                      foreach($transfer_data  as $index => $trans_res_data){
                                        if($trans_res_data->transportation_pick_up_location != ''){
                                           $transfer_new_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                          
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
                                                'supplier_id'=>$request->transfer_supplier_id,
                                                'payment'=> $transfer_new_total,
                                                'balance'=> $trans_sup_balance,
                                                'package_id'=>$id,
                                                'remarks'=>'Package Updated',
                                                'date'=>date('Y-m-d'),
                                                'customer_id'=>$request->customer_id,
                                            ]);
                                        DB::table('transfer_Invoice_Supplier')->where('id',$new_transfer_sup)->update(['balance'=>$trans_sup_balance]);
                                    }
                                }
                            }
                        }
                        
                    
                }
                
                DB::commit();  
                return response()->json(['status'=>'success','tour_bactches2'=>$tour_bactches2,'lastbatchsId'=>$lastbatchsId,'Success'=>'Tour Updated Successful!']);
            }
            else{
                return response()->json(['status'=>'error','Tour'=>$activities,'Error'=>'Tour Not Updated!']);
            }
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['status'=>'error']);
        }
    }
    
    public function submit_edit_tour_test(Request $request){
        //   dd($request->all());
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
        
        $id             = $request->id;
        $generate_id    = rand(0,9999999);
        $activities     = Tour::find($id);
        
        
        DB::beginTransaction();
         try {
           $accomodation_data = json_decode($request->accomodation_details);
        $accomodation_more_data = json_decode($request->more_accomodation_details);
        
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
                                
                              
                                
                                $user_id = $request->customer_id;
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
        // print_r($accomodation_more_data);
        
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
                                
                              
                                
                                $user_id = $request->customer_id;
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
        
        
        $request->accomodation_details = json_encode($accomodation_data);
        $request->more_accomodation_details = json_encode($accomodation_more_data);
        
        $visa_details = json_decode($request->visa_details);
            
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
                                'currency_conversion' => $request->conversion_type_Id,
                                'visa_price_conversion_rate' => $visa_res->exchange_rate_visa,
                                'visa_converted_price'=> $visa_res->visa_fee,
                                'customer_id' => $request->customer_id,
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
                                        'customer_id'=> $request->customer_id,
                                    ]);
                                
                                $data=DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([  
                                   'balance'               => $supplier_balance,
                                ]);
                            }
                    }
                    
                 
                }
            }
            
            $visa_details = json_encode($visa_details);
         
                if($activities)
                { 
                    if($request->package_update_type == 'new'){
                        $activities->generate_id=$generate_id;
                    }
                    
                   
                   
                        $activities->customer_id            = $request->customer_id;
                        // Package_Details
                        $activities->title                  = $request->title;
                        $activities->no_of_pax_days         = $request->no_of_pax_days;
                        $activities->city_Count             = $request->city_Count;
                        $activities->starts_rating          = $request->starts_rating;
                        $activities->currency_symbol        = $request->currency_symbol;
                        $activities->content                = $request->content;
                        $activities->start_date             = $request->start_date;
                        $activities->end_date               = $request->end_date;
                        $activities->time_duration          = $request->time_duration;
                        $activities->categories             = $request->tour_categories;
                        $activities->tour_feature           = $request->tour_feature;
                        $activities->defalut_state          = $request->defalut_state;
                        $activities->tour_featured_image    = $request->tour_featured_image;
                        $activities->tour_banner_image      = $request->tour_banner_image;
                        $activities->tour_author            = $request->tour_author;
                        $activities->gallery_images         = $request->gallery_images;
                        $activities->payment_gateways       = $request->payment_gateways;
                        $activities->payment_modes          = $request->payment_modes;
                        $activities->tour_location          = $request->tour_location;
                        $activities->conversion_type        = $request->conversion_type;
                        $activities->visa_detials = $visa_details;
                        
                        $activities->currency_conversion    = $request->currency_conversion;
                        $activities->conversion_type_Id     = $request->conversion_type_Id;
                    
                        // Destination_details
                        $activities->destination_details=$request->destination_details;
                        $activities->destination_details_more=$request->destination_details_more;
                        // Accomodation_Details
                        $activities->accomodation_details=$request->accomodation_details;
                        $activities->accomodation_details_more=$request->more_accomodation_details;
                        // Visa_Details
                        $activities->visa_type=$request->visa_type;
                        $activities->visa_rules_regulations=$request->visa_rules_regulations;
                        $activities->visa_fee=$request->visa_fee;
                        
                        $activities->visa_fee_purchase=$request->visa_fee_purchase;
                        $activities->exchange_rate_visa=$request->exchange_rate_visa;
                        $activities->visa_image=$request->visa_image;
                        
                         // $tour->arfatData  =   $request->arfatData;
        $activities->arfat_selected  =   $request->arfat_selected;
        // $tour->muzdalfaData  =   $request->muzdalfaData;
        $activities->muzdalfa_selected  =   $request->muzdalfa_selected;
        // $tour->minaData  =   $request->minaData;
        $activities->mina_selected  =   $request->mina_selected;
        
        $activities->mina_pkg_details  =   $request->mina_pkg_details;
        $activities->arfat_pkg_details  =   $request->arfat_pkg_details;
        $activities->muzdalfa_details  =   $request->muzdalfa_details;
                        
                        $activities->update();
                        
                        $flight_route_id_occupied = $request->flight_route_id_occupied;
                        if(isset($flight_route_id_occupied) && $flight_route_id_occupied != null && $flight_route_id_occupied != ''){
                            DB::table('flight_seats_occupied')->where('booking_id',$id)->update([
                                'token'                         => $request->token,
                                'type'                          => 'Package',
                                'booking_id'                    => $id,
                                'flight_supplier'               => $request->flight_supplier,
                                'flight_route_id'               => $request->flight_route_id_occupied,
                                'flights_adult_seats'           => $request->flight_total_seats,
                                'flights_child_seats'           => 0,
                                'flight_route_seats_occupied'   => $request->flight_total_seats,
                            ]);
                        }
                        
                        // dd('stop');
                        
                        $tours_prev_data= DB::table('tours_2')->where('tour_id',$id)->select('flight_supplier','flight_route_id_occupied','tour_id','transfer_supplier_id','transportation_details','transportation_details_more')->first();
                        $tour_id = $id;
                        $previous_flight_sup = $tours_prev_data->flight_supplier;
                        $previous_flight_route = $tours_prev_data->flight_route_id_occupied;
                        
                        $previous_transfer_sup = $tours_prev_data->transfer_supplier_id;
                        $new_transfer_sup = $request->transfer_supplier_id;
                        
                        $prev_transfer_det = json_decode($tours_prev_data->transportation_details);
                        $new_transfer_det = json_decode($request->transportation_details);
                                    
                    
                        $activities2 = DB::table('tours_2')->where('tour_id',$id)->update([
                            
                            'flight_route_type'         => $request->flight_route_type,
                            'flight_supplier'           => $request->flight_supplier,
                            'flight_route_id_occupied'  => $request->flight_route_id_occupied,
                            'flight_total_seats'        => $request->flight_total_seats,
                            'flights_per_person_price'  => $request->flights_per_person_price,
                            
                            'flights_details'           => $request->flights_details,
                            'return_flights_details'    => $request->return_flights_details,
                            
                            'transportation_details'=> $request->transportation_details,
                            'transportation_details_more'=> $request->transportation_details_more,
                            'Itinerary_details'=> $request->Itinerary_details,
                            'tour_itinerary_details_1'=> $request->tour_itinerary_details_1,
                            'tour_extra_price'=> $request->tour_extra_price,
                            'tour_extra_price_1'=> $request->tour_extra_price_1,
                            'tour_faq'=> $request->tour_faq,
                            'tour_faq_1'=> $request->tour_faq_1,
                            'quad_cost_price'=> $request->quad_cost_price,
                            'triple_cost_price'=> $request->triple_cost_price,
                            'double_cost_price'=> $request->double_cost_price,
                            'quad_grand_total_amount'=> $request->quad_grand_total_amount,
                            'triple_grand_total_amount'=> $request->triple_grand_total_amount,
                            'double_grand_total_amount'=> $request->double_grand_total_amount,
                            'all_markup_type'=> $request->all_markup_type,
                            'all_markup_add'=> $request->all_markup_add,
                            'markup_details'=> $request->markup_details,
                            'more_markup_details'=> $request->more_markup_details,
                            
                            'child_flight_cost_price'       => $request->child_flight_cost_price,
                            'child_flight_mark_type'        => $request->child_flight_markup_type,
                            'child_flight_mark_value'       => $request->child_flight_markup_value,
                            'child_flight_sale_price'       => $request->child_flight_sale,
                            'child_visa_cost_price'         => $request->child_visa_cost,
                            'child_visa_mark_type'          => $request->child_visa_markup_type,
                            'child_visa_mark_value'         => $request->child_visa_markup_value,
                            'child_visa_sale_price'         => $request->child_visa_sale,
                            'child_transp_cost_price'       => $request->child_Transporationcost,
                            'child_transp_mark_type'        => $request->child_Transporation_markup_type,
                            'child_transp_mark_value'       => $request->child_Transporation_markup_value,
                            'child_transp_sale_price'       => $request->child_Transporation_sale,
                            'child_grand_total_cost_price'  => $request->child_total_cost_price,
                            'child_grand_total_sale_price'  => $request->child_total_sale_price,
                            'without_acc_cost_price'        => $request->without_acc_cost_price,
                            'without_acc_sale_price'        => $request->without_acc_sale_price,
                            
                            'infant_flight_cost'            => $request->infant_flight_cost,
                            'infant_transp_cost'            => $request->infant_transp_cost,
                            'infant_visa_cost'              => $request->infant_visa_cost,
                            'infant_total_cost_price'       => $request->infant_total_cost_price,
                            'infant_total_sale_price'       => $request->infant_total_sale_price,
                            
                            'ch_price_other_c'              => $request->child_other_prices,
                            'in_price_other_c'              => $request->infant_other_prices,
                            'in_markup_other_c'             => $request->infant_markup_prices,
                            
                            'without_acc_sale_price'        => $request->without_acc_sale_price,
                        ]);
                    
                        if($request->package_update_type == 'old'){
                            echo "Update";
                            $tour_bactches=DB::table('tour_batchs')->where('generate_id',$request->prev_generate_code)->update([
                                'tour_id'=>$id,
                                'customer_id'=>$request->customer_id,
                                'city_Count'=>$request->city_Count,
                                'title'=> $request->title,
                                'no_of_pax_days'=> $request->no_of_pax_days,
                                'starts_rating'=> $request->starts_rating,
                                'currency_symbol'=> $request->currency_symbol,
                                'content'=> $request->content,
                                'start_date'=> $request->start_date,
                                'end_date'=> $request->end_date,
                                'time_duration'=> $request->time_duration,
                                'categories'=> $request->tour_categories,
                                'tour_feature'=> $request->tour_feature,
                                'defalut_state'=> $request->defalut_state,
                                'tour_featured_image'=> $request->tour_featured_image,
                                'tour_banner_image'=> $request->tour_banner_image,
                                'tour_author'=> $request->tour_author,
                                'gallery_images'=> $request->gallery_images,
                                'destination_details'=> $request->destination_details,
                                'destination_details_more'=> $request->destination_details_more,
                                'accomodation_details'=> $request->accomodation_details,
                                'accomodation_details_more'=> $request->more_accomodation_details,
                                'visa_type'=> $request->visa_type,
                                'visa_rules_regulations'=> $request->visa_rules_regulations,
                                'visa_fee'=> $request->visa_fee,
                                'visa_fee_purchase'=> $request->visa_fee_purchase,
                                'exchange_rate_visa'=> $request->exchange_rate_visa,
                                'visa_image'=> $request->visa_image,
                                'whats_included'=> $request->whats_included,
                                'whats_excluded'=> $request->whats_excluded,
                                'payment_gateways'=> $request->payment_gateways,
                                'payment_modes'=> $request->payment_modes,
                                'tour_location'=> $request->tour_location,
                            ]);
                            
                            $tour_bactches2=DB::table('tour_batchs_2')->where('generate_id',$request->prev_generate_code)->update([
                                'flights_details'=> $request->flights_details,
                                
                                'transportation_details'=> $request->transportation_details,
                                'transportation_details_more'=> $request->transportation_details_more,
                                'Itinerary_details'=> $request->Itinerary_details,
                                'tour_itinerary_details_1'=> $request->tour_itinerary_details_1,
                                'tour_extra_price'=> $request->tour_extra_price,
                                'tour_extra_price_1'=> $request->tour_extra_price_1,
                                'tour_faq'=> $request->tour_faq,
                                'tour_faq_1'=> $request->tour_faq_1,
                                'quad_cost_price'=> $request->quad_cost_price,
                                'triple_cost_price'=> $request->triple_cost_price,
                                'double_cost_price'=> $request->double_cost_price,
                                'quad_grand_total_amount'=> $request->quad_grand_total_amount,
                                'triple_grand_total_amount'=> $request->triple_grand_total_amount,
                                'double_grand_total_amount'=> $request->double_grand_total_amount,
                                'all_markup_type'=> $request->all_markup_type,
                                'all_markup_add'=> $request->all_markup_add,
                                'markup_details'=> $request->markup_details,
                                'more_markup_details'=> $request->more_markup_details,
                                
                                'infant_flight_cost'=>$request->infant_flight_cost,
                                'infant_transp_cost'=>$request->infant_transp_cost,
                                'infant_visa_cost'=>$request->infant_visa_cost,
                                'infant_total_cost_price'=>$request->infant_total_cost_price,
                                'infant_total_sale_price'=>$request->infant_total_sale_price,
                                
                                'ch_price_other_c'=>$request->child_other_prices,
                                'in_price_other_c'=>$request->infant_other_prices,
                                'in_markup_other_c'=>$request->infant_markup_prices,
                            ]);
                           
                        }else{
                            
                            $tour_bactches=DB::table('tour_batchs')->insertGetId([
                                'tour_id'=>$id,
                                'generate_id'=>$generate_id,
                                'customer_id'=>$request->customer_id,
                                'title'=> $request->title,
                                'city_Count'=>$request->city_Count,
                                'no_of_pax_days'=> $request->no_of_pax_days,
                                'starts_rating'=> $request->starts_rating,
                                'currency_symbol'=> $request->currency_symbol,
                                'content'=> $request->content,
                                'start_date'=> $request->start_date,
                                'end_date'=> $request->end_date,
                                'time_duration'=> $request->time_duration,
                                'categories'=> $request->tour_categories,
                                'tour_feature'=> $request->tour_feature,
                                'defalut_state'=> $request->defalut_state,
                                'tour_featured_image'=> $request->tour_featured_image,
                                'tour_banner_image'=> $request->tour_banner_image,
                                'tour_author'=> $request->tour_author,
                                'gallery_images'=> $request->gallery_images,
                                'destination_details'=> $request->destination_details,
                                'destination_details_more'=> $request->destination_details_more,
                                'accomodation_details'=> $request->accomodation_details,
                                'accomodation_details_more'=> $request->more_accomodation_details,
                                'visa_type'=> $request->visa_type,
                                
                                'visa_fee_purchase'=> $request->visa_fee_purchase,
                                'exchange_rate_visa'=> $request->exchange_rate_visa,
                                
                                'visa_rules_regulations'=> $request->visa_rules_regulations,
                                'visa_fee'=> $request->visa_fee,
                                'visa_image'=> $request->visa_image,
                                'whats_included'=> $request->whats_included,
                                'whats_excluded'=> $request->whats_excluded,
                                'payment_gateways'=> $request->payment_gateways,
                                'payment_modes'=> $request->payment_modes,
                                'tour_location'=> $request->tour_location,
                            ]);
                            
                            $lastbatchsId = $tour_bactches;
                            
                            $tour_bactches2=DB::table('tour_batchs_2')->insert([
                                'batchs_id'=>$lastbatchsId,
                                'generate_id'=>$generate_id,
                                'flights_details'=> $request->flights_details,
                                
                                'transportation_details'=> $request->transportation_details,
                                'transportation_details_more'=> $request->transportation_details_more,
                                'Itinerary_details'=> $request->Itinerary_details,
                                'tour_itinerary_details_1'=> $request->tour_itinerary_details_1,
                                'tour_extra_price'=> $request->tour_extra_price,
                                'tour_extra_price_1'=> $request->tour_extra_price_1,
                                'tour_faq'=> $request->tour_faq,
                                'tour_faq_1'=> $request->tour_faq_1,
                                'quad_cost_price'=> $request->quad_cost_price,
                                'triple_cost_price'=> $request->triple_cost_price,
                                'double_cost_price'=> $request->double_cost_price,
                                'quad_grand_total_amount'=> $request->quad_grand_total_amount,
                                'triple_grand_total_amount'=> $request->triple_grand_total_amount,
                                'double_grand_total_amount'=> $request->double_grand_total_amount,
                                'all_markup_type'=> $request->all_markup_type,
                                'all_markup_add'=> $request->all_markup_add,
                                'markup_details'=> $request->markup_details,
                                'more_markup_details'=> $request->more_markup_details,
                            ]);
                           
                        }
                        
                        // Update Flight Supplier
                        if($previous_flight_route != $request->flight_route_id_occupied){
                            
                            
                            // Remove From Previous One
                            
                            $route_obj = DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('id',$previous_flight_route)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                            $route_obj_new = DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('id',$request->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                            
                            
                            
                            
                            $Cart_details = Cart_details::where('tour_id',$tour_id)->select('tour_id','cart_total_data','invoice_no')->get();
                            //  print_r($cart_res);die;
                            
                            foreach($Cart_details as $cart_res){
                                $cart_prev_data = json_decode($cart_res->cart_total_data);
                           
                                // Update For Previous 
                                
                                // Calaculate Child Prev Price Differ
                                $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $cart_prev_data->total_childs;
                                $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $cart_prev_data->total_childs;
                                
                                $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                
                                
                                // Calaculate Infant Prev Price
                                $infant_price_prev = $route_obj->flights_per_infant_price * $cart_prev_data->total_Infants;
                                
                                
                                
                                
                                
                                $supplier_data = DB::table('supplier')->where('customer_id',$request->customer_id)->where('id',$previous_flight_sup)->first();
                                
                                if($price_diff_prev != 0 || $infant_price_prev != 0){
                                    $supplier_balance = $supplier_data->balance + $price_diff_prev;
                                    
                                    $supplier_balance = $supplier_balance - $infant_price_prev;
                                    $total_differ = $price_diff_prev - $infant_price_prev;
                                    
                                    DB::table('flight_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'payment'=>$total_differ,
                                                'balance'=>$supplier_balance,
                                                'route_id'=>$route_obj->id,
                                                'date'=>date('Y-m-d'),
                                                'customer_id'=>$request->customer_id,
                                                'adult_price'=>$route_obj->flights_per_person_price,
                                                'child_price'=>$route_obj->flights_per_child_price,
                                                'infant_price'=>$route_obj->flights_per_infant_price,
                                                'adult_seats_booked'=>$cart_prev_data->total_adults,
                                                'child_seats_booked'=>$cart_prev_data->total_childs,
                                                'infant_seats_booked'=>$cart_prev_data->total_Infants,
                                                'package_invoice_no'=>$cart_res->invoice_no,
                                                'remarks'=>'Package Route Updated',
                                              ]);
                                              
                                    DB::table('supplier')->where('customer_id',$request->customer_id)->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                }
                                
                                
                                // Update For New 
                                
                                
                                
                                // Calaculate Child Prev Price Differ
                                $child_price_wi_adult_price_prev = $route_obj_new->flights_per_person_price * $cart_prev_data->total_childs;
                                $child_price_wi_child_price_prev = $route_obj_new->flights_per_child_price * $cart_prev_data->total_childs;
                                
                                $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                
                                
                                // Calaculate Infant Prev Price
                                $infant_price_prev = $route_obj_new->flights_per_infant_price * $cart_prev_data->total_Infants;
                                
                                
                                
                                
                                
                                $supplier_data = DB::table('supplier')->where('customer_id',$request->customer_id)->where('id',$request->flight_supplier)->first();
                                
                                if($price_diff_prev != 0 || $infant_price_prev != 0){
                                    $supplier_balance = $supplier_data->balance - $price_diff_prev;
                                    
                                    $supplier_balance = $supplier_balance + $infant_price_prev;
                                    $total_differ = $infant_price_prev - $price_diff_prev;
                                   
                                    
                                    DB::table('flight_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'payment'=>$total_differ,
                                                'balance'=>$supplier_balance,
                                                'route_id'=>$route_obj_new->id,
                                                'date'=>date('Y-m-d'),
                                                'customer_id'=>$request->customer_id,
                                                'adult_price'=>$route_obj_new->flights_per_person_price,
                                                'child_price'=>$route_obj_new->flights_per_child_price,
                                                'infant_price'=>$route_obj_new->flights_per_infant_price,
                                                'adult_seats_booked'=>$cart_prev_data->total_adults,
                                                'child_seats_booked'=>$cart_prev_data->total_childs,
                                                'infant_seats_booked'=>$cart_prev_data->total_Infants,
                                                'package_invoice_no'=>$cart_res->invoice_no,
                                                'remarks'=>'Package Route Updated',
                                              ]);
                                              
                                    DB::table('supplier')->where('customer_id',$request->customer_id)->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                }
                            }
                            
                            
                        }
                        
                        //--------------------------------------
                        // Transfer Working 
                        //--------------------------------------
                        if($previous_transfer_sup == $new_transfer_sup){
                            
                            // Calculate Previous Total
                             $transfer_prev_total = 0;
                            if(isset($prev_transfer_det) && !empty($prev_transfer_det) && $prev_transfer_det != null){
                                    $transfer_data = $prev_transfer_det;
                    
                                    if(isset($transfer_data)){
                                            if(1 == count($transfer_data)){
                                                  foreach($transfer_data  as $index => $trans_res_data){
                                         
                                                         $transfer_prev_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                                
                                                 }
                                                
                                            }else{
                                              
                                              foreach($transfer_data  as $index => $trans_res_data){
                                                if($trans_res_data->transportation_pick_up_location != ''){
                                                   $transfer_prev_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                                  
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
                                         
                                                         $transfer_new_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                                
                                                 }
                                                
                                            }else{
                                              
                                              foreach($transfer_data  as $index => $trans_res_data){
                                                if($trans_res_data->transportation_pick_up_location != ''){
                                                   $transfer_new_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                                  
                                                }
                                              }
                                            
                                        }
                                    }
                                }
                                
                            $price_diff = $transfer_new_total - $transfer_prev_total;
                            
                            // Update Supplier Balance
                            $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->select('id','balance')->first();
                            
                            if(isset($transfer_sup_data)){       
                                $trans_sup_balance = $transfer_sup_data->balance + $price_diff;
                                
                                if($price_diff != 0){
                                    DB::table('transfer_supplier_ledger')->insert([
                                            'supplier_id'=>$request->transfer_supplier_id,
                                            'payment'=> $price_diff,
                                            'balance'=> $trans_sup_balance,
                                            'package_id'=>$id,
                                            'remarks'=>'Package Updated',
                                            'date'=>date('Y-m-d'),
                                            'customer_id'=>$request->customer_id,
                                        ]);
                                    DB::table('transfer_Invoice_Supplier')->where('id',$request->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
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
                                         
                                                         $transfer_prev_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                                
                                                 }
                                                
                                            }else{
                                              
                                              foreach($transfer_data  as $index => $trans_res_data){
                                                if($trans_res_data->transportation_pick_up_location != ''){
                                                   $transfer_prev_total += (float)$trans_res_data->transportation_vehicle_total_price_purchase;
                                                  
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
                                                        'package_id'=>$id,
                                                        'remarks'=>'Package Updated',
                                                        'date'=>date('Y-m-d'),
                                                        'customer_id'=>$request->customer_id,
                                                    ]);
                                                DB::table('transfer_Invoice_Supplier')->where('id',$previous_transfer_sup)->update(['balance'=>$trans_sup_balance]);
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
                                         
                                                         $transfer_new_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                                
                                                 }
                                                
                                            }else{
                                              
                                              foreach($transfer_data  as $index => $trans_res_data){
                                                if($trans_res_data->transportation_pick_up_location != ''){
                                                   $transfer_new_total += $trans_res_data->transportation_vehicle_total_price_purchase;
                                                  
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
                                                        'supplier_id'=>$request->transfer_supplier_id,
                                                        'payment'=> $transfer_new_total,
                                                        'balance'=> $trans_sup_balance,
                                                        'package_id'=>$id,
                                                        'remarks'=>'Package Updated',
                                                        'date'=>date('Y-m-d'),
                                                        'customer_id'=>$request->customer_id,
                                                    ]);
                                                DB::table('transfer_Invoice_Supplier')->where('id',$new_transfer_sup)->update(['balance'=>$trans_sup_balance]);
                                            }
                                        }
                                    }
                                }
                                
                            
                        }
                        
                        DB::commit();  
                        return response()->json(['status'=>'success','tour_bactches2'=>$tour_bactches2,'lastbatchsId'=>$lastbatchsId,'Success'=>'Tour Updated Successful!']);
                   
                    
                    
                }
                else{
                    return response()->json(['status'=>'error','Tour'=>$activities,'Error'=>'Tour Not Updated!']);
                }
        
            } catch (Throwable $e) {
                    DB::rollback();
                    return response()->json(['status'=>'error']);
            }
    }
    // End Tour Packages
    
    // Enquire Package
    public function view_tour_enquire(Request $request){
        $pakage_type    = 'tour';
        $customer_id    = $request->customer_id;
        $tours          = DB::table('tours_enquire')
                            ->join('tours_2_enquire','tours_enquire.id','tours_2_enquire.tour_id')
                            ->where('tours_enquire.customer_id',$customer_id)
                            ->orderBy('tours_enquire.id', 'DESC')
                            ->get();
        $equire_Lead    = DB::table('addLead')->whereNotNull('package_id')->where('package_id', '!=', '')->orderBy('id', 'DESC')->get();
        $all_countries  = country::all();
        return response()->json([
            'message'       => 'success',
            'tours'         => $tours,
            'equire_Lead'   => $equire_Lead,
            'all_countries' => $all_countries,
        ]);
    }
    
    public function submit_tour_enquire(Request $request){
        
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
        
        $generate_id=rand(0,9999999);
        $tour = new Tour_enquire();
        
        $tour->generate_id                  = $generate_id;
        $tour->customer_id                  = $request->customer_id;
        $tour->title                        = $request->title;
        $tour->content                      = $request->content;
        $tour->categories                   = $request->tour_categories;
        $tour->tour_attributes              = $request->tour_attributes;
        $tour->no_of_pax_days               = $request->no_of_pax_days;
        $tour->city_Count                   = $request->city_Count;
        $tour->destination_details          = $request->destination_details;
        $tour->destination_details_more     = $request->destination_details_more;
        
        // Accomodation
        $tour->accomodation_details         = $request->accomodation_details;
        $tour->accomodation_details_more    = $request->more_accomodation_details;
        // Accomodation
        
        // Visa
        $tour->visa_type                    = $request->visa_type;
        $tour->visa_fee_purchase            = $request->visa_fee_purchase;
        $tour->exchange_rate_visa           = $request->exchange_rate_visa;
        $tour->visa_fee                     = $request->visa_fee;
        $tour->visa_rules_regulations       = $request->visa_rules_regulations;
        $tour->visa_image                   = $request->visa_image;
        // Visa
        
        $tour->currency_conversion          = $request->currency_conversion;
        $tour->conversion_type_Id           = $request->conversion_type_Id;
        $tour->conversion_type              = $request->conversion_type;
        
        $tour->start_date               = $request->start_date;
        $tour->end_date                 = $request->end_date;
        $tour->time_duration            = $request->time_duration;
        $tour->tour_location            = $request->tour_location;
        $tour->whats_included           = $request->whats_included;
        $tour->whats_excluded           = $request->whats_excluded;
        $tour->currency_symbol          = $request->currency_symbol;
        $tour->tour_publish             = $request->tour_publish;
        $tour->tour_author              = $request->tour_author;
        $tour->others_providers_show    = $request->others_providers_show;
        $tour->tour_feature             = $request->tour_feature;
        $tour->defalut_state            = $request->defalut_state;
        $tour->payment_gateways         = $request->payment_gateways;
        $tour->payment_modes            = $request->payment_modes;
        $tour->tour_featured_image      = $request->tour_featured_image;
        $tour->tour_banner_image        = $request->tour_banner_image;
        $tour->starts_rating            = $request->starts_rating;
        $tour->checkout_message         = $request->checkout_message;
        $tour->cancellation_policy      = $request->cancellation_policy;
        $tour->gallery_images           = $request->gallery_images;
        
        $tour->arfat_selected           = $request->arfat_selected;
        $tour->muzdalfa_selected        = $request->muzdalfa_selected;
        $tour->mina_selected            = $request->mina_selected;
        
        $tour->mina_pkg_details         = $request->mina_pkg_details;
        $tour->arfat_pkg_details        = $request->arfat_pkg_details;
        $tour->muzdalfa_details         = $request->muzdalfa_details;
        
        DB::beginTransaction();
        try {
            
            $tour->save();
            $lastTourId = $tour->id;
            
            // dd($lastTourId); 
            
            // Cost
            $quad_cost_priceN               = $request->quad_cost_price ?? '0';
            $triple_cost_priceN             = $request->triple_cost_price ?? '0';
            $double_cost_priceN             = $request->double_cost_price ?? '0';
            $without_acc_cost_priceN        = $request->without_acc_cost_price ?? '0';
            $total_cost_price_all_Services  = $double_cost_priceN + $triple_cost_priceN + $quad_cost_priceN + $without_acc_cost_priceN;
            
            // Sale
            $quad_grand_total_amountN       = $request->quad_grand_total_amount ?? '0';
            $triple_grand_total_amountN     = $request->triple_grand_total_amount ?? '0';
            $double_grand_total_amountN     = $request->double_grand_total_amount ?? '0';
            $without_acc_sale_price_singleN = $request->without_acc_sale_price ?? '0';
            $total_sale_price_all_Services  = $double_grand_total_amountN + $triple_grand_total_amountN + $quad_grand_total_amountN + $without_acc_sale_price_singleN;
            
            $notification_insert = new alhijaz_Notofication();
            $notification_insert->type_of_notification_id   = $tour->id ?? '';
            $notification_insert->customer_id               = $tour->customer_id ?? '';
            $notification_insert->type_of_notification      = 'create_Package' ?? '';
            $notification_insert->generate_id               = $tour->generate_id ?? '';
            $notification_insert->notification_creator_name = 'AlhijazTours' ?? '';
            $notification_insert->total_price               = $total_sale_price_all_Services ?? '';
            $notification_insert->amount_paid               = $tour->amount_Paid ?? '';
            $notification_insert->remaining_price           = $total_sale_price_all_Services ?? '';
            $notification_insert->notification_status       = '1' ?? '';
            $notification_insert->save();
            
            $tours_2 = DB::table('tours_2_enquire')->insert([
                'tour_id'                       => $lastTourId,
                'Itinerary_details'             => $request->Itinerary_details,
                'tour_itinerary_details_1'      => $request->tour_itinerary_details_1,
                'tour_extra_price'              => $request->tour_extra_price,
                'tour_extra_price_1'            => $request->tour_extra_price_1,
                'tour_faq'                      => $request->tour_faq,
                'tour_faq_1'                    => $request->tour_faq_1,
                'markup_details'                => $request->markup_details,
                'more_markup_details'           => $request->more_markup_details,
                
                'flight_route_type'             => $request->flight_route_type,
                'flight_supplier'               => $request->flight_supplier,
                'flight_route_id_occupied'      => $request->flight_route_id_occupied,
                'flight_total_seats'            => $request->flight_total_seats,
                'flights_per_person_price'      => $request->flights_per_person_price,
                
                'flights_details'               => $request->flights_details,
                'return_flights_details'        => $request->return_flights_details,
                
                'transportation_details'        => $request->transportation_details,
                'transportation_details_more'   => $request->transportation_details_more,
                'quad_grand_total_amount'       => $request->quad_grand_total_amount,
                'triple_grand_total_amount'     => $request->triple_grand_total_amount,
                'double_grand_total_amount'     => $request->double_grand_total_amount,
                'quad_cost_price'               => $request->quad_cost_price,
                'triple_cost_price'             => $request->triple_cost_price,
                'double_cost_price'             => $request->double_cost_price,
                'all_markup_type'               => $request->all_markup_type,
                'all_markup_add'                => $request->all_markup_add,
                
                'child_flight_cost_price'       => $request->child_flight_cost_price,
                'child_flight_mark_type'        => $request->child_flight_markup_type,
                'child_flight_mark_value'       => $request->child_flight_markup_value,
                'child_flight_sale_price'       => $request->child_flight_sale,
                'child_visa_cost_price'         => $request->child_visa_cost,
                'child_visa_mark_type'          => $request->child_visa_markup_type,
                'child_visa_mark_value'         => $request->child_visa_markup_value,
                'child_visa_sale_price'         => $request->child_visa_sale,
                'child_transp_cost_price'       => $request->child_Transporationcost,
                'child_transp_mark_type'        => $request->child_Transporation_markup_type,
                'child_transp_mark_value'       => $request->child_Transporation_markup_value,
                'child_transp_sale_price'       => $request->child_Transporation_sale,
                'child_grand_total_cost_price'  => $request->child_total_cost_price,
                'child_grand_total_sale_price'  => $request->child_total_sale_price,
                'without_acc_cost_price'        => $request->without_acc_cost_price,
                'without_acc_sale_price'        => $request->without_acc_sale_price,
                
                'infant_flight_cost'            => $request->infant_flight_cost,
                'infant_transp_cost'            => $request->infant_transp_cost,
                'infant_visa_cost'              => $request->infant_visa_cost,
                'infant_total_cost_price'       => $request->infant_total_cost_price,
                'infant_total_sale_price'       => $request->infant_total_sale_price,
                
                'ch_price_other_c'              => $request->child_other_prices,
                'in_price_other_c'              => $request->infant_other_prices,
                'in_markup_other_c'             => $request->infant_markup_prices,
            ]);
            
            $tour_batchs = DB::table('tour_batchs_enquire')->insertGetId([
                'tour_id'=>$lastTourId,
                'generate_id'=>$generate_id,
                'customer_id'=>$request->customer_id,
                'city_Count'=>$request->city_Count,
                'title'=>$request->title,
                'categories'=>$request->tour_categories,
                'starts_rating'=>$request->starts_rating,
                'content'=>$request->content,
                'tour_attributes'=>$request->tour_attributes,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
                'time_duration'=>$request->time_duration,
                'tour_location'=>$request->tour_location,
                'whats_included'=>$request->whats_included,
                'whats_excluded'=>$request->whats_excluded,
                'currency_symbol'=>$request->currency_symbol,
                'tour_publish'=>$request->tour_publish,
                'tour_author'=>$request->tour_author,
                'tour_feature'=>$request->tour_feature,
                'defalut_state'=>$request->defalut_state,
                'tour_featured_image'=>$request->tour_featured_image,
                'tour_banner_image'=>$request->tour_banner_image,
                'external_packages'=>$request->external_packages,
                'no_of_pax_days'=>$request->no_of_pax_days,
                'destination_details'=>$request->destination_details,
                'destination_details_more'=>$request->destination_details_more,
                'accomodation_details'=>$request->accomodation_details,
                'accomodation_details_more'=>$request->more_accomodation_details,
                'visa_fee'=>$request->visa_fee,
                'visa_type'=>$request->visa_type,
                
                'visa_fee_purchase'=>$request->visa_fee_purchase,
                'exchange_rate_visa'=>$request->exchange_rate_visa,
                
                'visa_image'=>$request->visa_image,
                'visa_rules_regulations'=>$request->visa_rules_regulations,
                'gallery_images'=>$request->gallery_images,
                'payment_gateways'=>$request->payment_gateways,
                'payment_modes'=>$request->payment_modes,
                'cancellation_policy'=>$request->cancellation_policy,
                'checkout_message'=>$request->checkout_message,
            ]);
            
            $lastbatchsId = $tour_batchs;
            
            $tour_batchs2=DB::table('tour_batchs_2_enquire')->insert([
                'batchs_id'=>$lastbatchsId,
                'generate_id'=>$generate_id,
                'Itinerary_details'=>$request->Itinerary_details,
                'tour_itinerary_details_1'=>$request->tour_itinerary_details_1,
                'tour_extra_price'=>$request->tour_extra_price,
                'tour_extra_price_1'=>$request->tour_extra_price_1,
                'tour_faq'=>$request->tour_faq,
                'tour_faq_1'=>$request->tour_faq_1,
                'markup_details'=>$request->markup_details,
                'more_markup_details'=>$request->more_markup_details,
                
                'flights_details'=>$request->flights_details,
                
                'transportation_details'=>$request->transportation_details,
                'transportation_details_more'=>$request->transportation_details_more,
                'quad_grand_total_amount'=>$request->quad_grand_total_amount,
                'triple_grand_total_amount'=>$request->triple_grand_total_amount,
                'double_grand_total_amount'=>$request->double_grand_total_amount,
                'quad_cost_price'=>$request->quad_cost_price,
                'triple_cost_price'=>$request->triple_cost_price,
                'double_cost_price'=>$request->double_cost_price,
                'all_markup_type'=>$request->all_markup_type,
                'all_markup_add'=>$request->all_markup_add,
                'child_flight_cost_price'=>$request->child_flight_cost,
                'child_flight_mark_type'=>$request->child_flight_markup_type,
                'child_flight_mark_value'=>$request->child_flight_markup_value,
                'child_flight_sale_price'=>$request->child_flight_sale,
                'child_visa_cost_price'=>$request->child_visa_cost,
                'child_visa_mark_type'=>$request->child_visa_markup_type,
                'child_visa_mark_value'=>$request->child_visa_markup_value,
                'child_visa_sale_price'=>$request->child_visa_sale,
                'child_transp_cost_price'=>$request->child_Transporationcost,
                'child_transp_mark_type'=>$request->child_Transporation_markup_type,
                'child_transp_mark_value'=>$request->child_Transporation_markup_value,
                'child_transp_sale_price'=>$request->child_Transporation_sale,
                'child_grand_total_cost_price'=>$request->child_total_cost_price,
                'child_grand_total_sale_price'=>$request->child_total_sale_price,
                'without_acc_cost_price'=>$request->without_acc_cost_price,
                'without_acc_sale_price'=>$request->without_acc_sale_price,
                
                'infant_flight_cost'=>$request->infant_flight_cost,
                'infant_transp_cost'=>$request->infant_transp_cost,
                'infant_visa_cost'=>$request->infant_visa_cost,
                'infant_total_cost_price'=>$request->infant_total_cost_price,
                'infant_total_sale_price'=>$request->infant_total_sale_price,
                
                'ch_price_other_c'=>$request->child_other_prices,
                'in_price_other_c'=>$request->infant_other_prices,
                'in_markup_other_c'=>$request->infant_markup_prices,
            ]);
            
            DB::commit();  
            return response()->json(['status'=>'success','message'=>'Tour2 is add','tour_batchs'=>$tour_batchs,'lastbatchsId'=>$lastbatchsId,'tour_batchs2'=>$tour_batchs2]); 
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error']);
        }
       
    }
    // End Enquire Package
}
