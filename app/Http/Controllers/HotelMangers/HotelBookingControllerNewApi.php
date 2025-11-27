<?php

namespace App\Http\Controllers\HotelMangers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\CustomerSubcription\HotelsProvider;

use App\Models\booking_customers;
use Carbon\Carbon;
use Hash;
use Facades\App\Http\Controllers\TransfersController;

class HotelBookingControllerNewApi extends Controller
{
    //
    function dateDiffInDays($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
    }
    
    function pr($x){
	    $apiKey = '833583586f0fd4fccd3757cd8a57c0a8';
        $secret = 'ae2dc887d0';
        $signature = hash("sha256", $apiKey.$secret.time());
        return $signature;
        // print_r($signature);
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
    
    function third_party_hotels_providers_list(){
        $all_providers = DB::table('3rd_party_hotel_providers')->get();
        $all_customer = CustomerSubcription::with('hotelsProviders')->select('customer_subcriptions.id','customer_subcriptions.company_name')->get();
        
      /*  foreach($customerSubscriptions as $cust_res){
            echo "customer id ".$cust_res->id;
            print_r($cust_res->hotelsProviders);  
        }
        
        die;*/
        return view('template/frontend/userdashboard/pages/hotels_providers/hotels_providers_list',compact('all_providers','all_customer'));
    }
    
    function update_customer_hotel_providers(Request $request){
        // print_r($request->all());
        
        if(isset($request->customer_id)){
              DB::beginTransaction();
        
            try {   
             DB::table('customer_assign_hotel_providers')->where('customer_id',$request->customer_id)->delete();
             
             if(isset($request->customerProviders) && !empty($request->customerProviders)){
                 foreach($request->customerProviders as $provider_res){
                     DB::table('customer_assign_hotel_providers')->insert([
                            'customer_id' => $request->customer_id,
                            'provider_id' => $provider_res
                         ]);
                 }
             }
             
             DB::commit();
             
             return redirect()->back()->with(['success'=>'Providers Update Successfully']);
             
            } catch (Throwable $e) {
                 DB::rollback();
                return redirect()->back()->with(['error'=>'Something Went Wrong Try Again']);
            }
            
        }
    }
    
    function calculateRoomsAllDaysPrice($room_data,$check_in,$checkout){
         $week_days_total = 0;
         $week_end_days_totals = 0;
         $total_price = 0;
         $check_in = date('Y-m-d',strtotime($check_in));
         $checkout = date('Y-m-d',strtotime($checkout));
        if($room_data->price_week_type == 'for_all_days'){
            $avaiable_days = $this->dateDiffInDays($check_in, $checkout);
            $total_price = $room_data->price_all_days * $avaiable_days;
        }else{
             $avaiable_days = $this->dateDiffInDays($check_in, $checkout);
             
             $all_days = $this->getBetweenDates($check_in, $checkout);
             
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
        
        
        $all_days_price = $total_price * 1;
        return $all_days_price;
        
    }
    
    function checkPrvoiderAllowOrNot($customer_id,$provider_name){
        $customer_providers = DB::table('customer_assign_hotel_providers')->where('customer_id',$customer_id)->get();
        
        if(isset($customer_providers)){
            foreach($customer_providers as $cust_pro_res){
                if($cust_pro_res->provider_id == $provider_name){
                    return true;
                }
            }
        }
        
        return false;
    }
    
    function search_hotels(Request $request){
        // print_r($request->all());
        
        $request_data = json_decode($request->hotel_search_request);
        
        // print_r($request_data);
        
        $request_all_data = json_decode($request_data->request_all_data);
        $destination_first_char = substr($request_all_data->destination_name, 0, 10);
        
        $sub_customer_id=DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        $markup=DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)->orwhere('added_markup','synchtravel')->orderBy('id','DESC')->get();
        
        // dd($markup);
        
        $admin_travelenda_markup = 0;
        $admin_hotel_bed_markup = 0;
        $admin_custom_markup = 0;
        
        $customer_markup = 0;
        $customer_custom_hotel_markup = 0;
        $customer_custom_hotel_markup_type = '';
        foreach($markup as $data){
            if($data->added_markup == 'synchtravel'){
                    if($data->provider == 'CustomHotel'){
                        $admin_custom_markup = $data->markup_value;
                    }
                    if($data->provider == 'Ratehawk'){
                        // config(['Ratehawk' => $data->markup_value]);  
                    }
                    if($data->provider == 'TBO'){
                        // config(['TBO' => $data->markup_value]);   
                    }
                    if($data->provider == 'Travellanda'){
                        $admin_travelenda_markup =$data->markup_value;  
                    }
                    if($data->provider == 'Hotelbeds'){
                        $admin_hotel_bed_markup = $data->markup_value;
                
                    }
                    if($data->provider == 'All'){
                        $admin_travelenda_markup =$data->markup_value;
                        $admin_hotel_bed_markup = $data->markup_value;
                        $admin_custom_markup = $data->markup_value;
                    }   
            }
            
            ///ended  
            
            if($data->added_markup == 'Haramayn_hotels'){
                    // if($data->provider == 'All'){
                        $customer_markup =  $data->markup_value;   
                    // } 
            }
        }
        
        // dd($customer_markup,$admin_custom_markup);
        
        $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id',$sub_customer_id->id)->where('status',1)->first();
        if($custom_hotel_markup){
            $customer_custom_hotel_markup = $custom_hotel_markup->markup_value;
            $customer_custom_hotel_markup_type = $custom_hotel_markup->markup;
        }
        
        $get_res=DB::table('travellanda_get_cities')->where('CityName',$request_data->destination)->first();
        $CityId = '';
        if($get_res){
            $CityId = $get_res->CityId;
        }
        
        $hotels_list_arr        = [];
        $hotels_list_arr_match  = [];
        
        // ************************************************************ //
        // Custom Hotels
        // ************************************************************ //
        
        $hotel_city         = $request_data->destination;
        $hotel_city_changed = $request_data->destination;
        
        if($hotel_city == 'Al Madinah Al Munawwarah' || $hotel_city == 'Al-Madinah al-Munawwarah'){
            $hotel_city_changed = 'Medina';
        }
        
        if($hotel_city == 'Mecca'){
            $hotel_city_changed = 'Makkah';
        }
        
        $rooms_adults       = json_decode($request_data->adult_per_room);
        $rooms_childs       = json_decode($request_data->child_per_room);
        
        $all_hotels         = DB::table('hotels')->where('property_city',$hotel_city_changed)->orWhere('property_city',$request_data->destination)->where('owner_id',$sub_customer_id->id)->get();
        
        $custom_hotels      = [];
        $hotel_list_item    = [];
        
        foreach($all_hotels as $hotel_res){
            $rooms_found = [];
            $rooms_ids = [];
            $rooms_qty = [];
            $counter = 0;
            
            $total_adults_in_rooms = 0;
            $total_childs_in_rooms = 0;
            foreach($rooms_adults as $index => $adult_res){
                $rooms = DB::table('rooms')
                            ->where('availible_from','<=',$request_data->check_in)
                            ->where('availible_to','>=',$request_data->check_out)
                            ->whereRaw('booked < quantity')
                            ->where('max_adults',$adult_res)
                            ->where('display_on_web','true')
                            ->where('hotel_id',$hotel_res->id)
                            ->where('owner_id',$sub_customer_id->id)
                            ->get();
                            
                // print_r($rooms);
                if(count($rooms)){
                    foreach($rooms as $room_res){
                        if (!in_array($room_res->id, $rooms_ids)) {
                           $rooms_ids[] = $room_res->id;
                           $aval_qty = $room_res->quantity - $room_res->booked;
                           $rooms_qty[] = $aval_qty;
                           $total_adults_in_rooms += ($room_res->max_adults * $aval_qty);
                           $total_childs_in_rooms += ($room_res->max_child * $aval_qty);
                           $rooms_found[] = $room_res;
                        }
                    }
                    
                    
                }
                
            }
            
           
            
            $hotel_currency = '';
            if($hotel_res->currency_symbol == '﷼')  {
                $hotel_currency = 'SAR';
            }
                                // print_r($rooms_found);

            $options_room = [];
            $room_prices_arr = [];
           
            if($total_adults_in_rooms >= $request_data->adult_searching && $total_childs_in_rooms >= $request_data->child_searching){
                foreach($rooms_found as $room_res){
                    $room_price = $this->calculateRoomsAllDaysPrice($room_res,$request_data->check_in,$request_data->check_out);
                    $room_prices_arr[] = $room_price;
                     $options_room[] = (Object)[
                                    'booking_req_id' => $room_res->id,
                                    'allotment' => $room_res->quantity - $room_res->booked,
                                    'room_name' => $room_res->room_type_name,
                                    'room_code' => $room_res->room_type_cat,
                                    'request_type' => $room_res->rooms_on_rq,
                                    'board_id' => $room_res->room_meal_type,
                                    'board_code' => $room_res->room_meal_type,
                                    'rooms_total_price' => $room_price,
                                    'rooms_selling_price' => $room_price,
                                    'rooms_qty' => 1,
                                    'adults' => $room_res->max_adults,
                                    'childs' => $room_res->max_child,
                                    'cancliation_policy_arr' => $room_res->cancellation_details,
                                    'rooms_list' => []
                                ];
                }
                
                $hotel_list_item = (Object)[
                    'hotel_provider' => 'Custome_hotel',
                    'admin_markup' => $admin_custom_markup,
                    'customer_markup' => $customer_custom_hotel_markup,
                    'admin_markup_type' => 'Percentage',
                    'customer_markup_type' => $customer_custom_hotel_markup_type,
                    'hotel_id' => $hotel_res->id,
                    'hotel_name' => $hotel_res->property_name,
                    'stars_rating' => $hotel_res->star_type,
                    'hotel_curreny' => $hotel_currency,
                    'min_price' => min($room_prices_arr),
                    'max_price' => max($room_prices_arr),
                    'rooms_options' => $options_room,
                ];
             
                
                $hotel_first_char = substr($hotel_res->property_name, 0, 10);
                if($hotel_first_char == $destination_first_char){
                    array_push($hotels_list_arr_match,$hotel_list_item);
                }else{
                    array_push($hotels_list_arr,$hotel_list_item);
                }
               
            }
        }
        
        // ************************************************************ //
        // Custom Hotels Providers
        // ************************************************************ //
        
        $hotel_city             = $request_data->destination;
        $hotel_city_changed     = $request_data->destination;
        
        if($hotel_city == 'Al Madinah Al Munawwarah'){
            $hotel_city_changed = 'Medina';
        }
        
        if($hotel_city == 'Mecca'){
            $hotel_city_changed = 'Makkah';
        }
        
        $rooms_adults = json_decode($request_data->adult_per_room);
        $rooms_childs = json_decode($request_data->child_per_room);
        
        $all_hotel_providers = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('provider_id','!=','NULL')->where('provider_id','!=','')->get();
        
        if(isset($all_hotel_providers)){
            foreach($all_hotel_providers as $hotel_provider_res){
                $provider_markup_data = DB::table('become_provider_markup')->where('customer_id',$hotel_provider_res->provider_id)->where('status','1')->first();
                
                $all_hotels = DB::table('hotels')->where('property_city',$hotel_city_changed)->where('owner_id',$hotel_provider_res->provider_id)->get();
                
                // Manage Markups 
                $admin_custom_hotel_pro_markup = 0;
                $customer_markup = 0;
              
                foreach($markup as $data){
                    if($data->added_markup == 'synchtravel'){
                            if($data->provider == $hotel_provider_res->provider){
                                $admin_custom_hotel_pro_markup = $data->markup_value;
                            }
                            
                            if($data->provider == 'All'){
                                $admin_custom_hotel_pro_markup =$data->markup_value;
                            }   
                    }
                    
                    ///ended  
                    
                    if($data->added_markup == 'Haramayn_hotels'){
                            // if($data->provider == 'All'){
                                $customer_markup =  $data->markup_value;   
                            // } 
                    }
                
                }
                
                $custom_hotels      = [];
                $hotel_list_item    = [];
                
                foreach($all_hotels as $hotel_res){
                    $rooms_found = [];
                    $rooms_ids = [];
                    $rooms_qty = [];
                    $counter = 0;
                    
                    $total_adults_in_rooms = 0;
                    $total_childs_in_rooms = 0;
                    // print_r($rooms_adults);
                    foreach($rooms_adults as $index => $adult_res){
                        // echo "Iteration $index ";
                        $rooms = DB::table('rooms')
                                    ->where('availible_from','<=',$request_data->check_in)
                                    ->where('availible_to','>=',$request_data->check_out)
                                    ->whereRaw('booked < quantity')
                                    ->where('max_adults',$adult_res)
                                    ->where('display_on_web','true')
                                    ->where('hotel_id',$hotel_res->id)
                                    ->where('owner_id',$hotel_provider_res->provider_id)
                                    ->where('rooms_on_rq','0')
                                    ->get();
                                    
                        // print_r($rooms);
                        if(count($rooms)){
                            $any_room_found = false;
                           foreach($rooms as $room_res){
                                if (!in_array($room_res->id, $rooms_ids)) {
                                   $rooms_ids[] = $room_res->id;
                                   $aval_qty = $room_res->quantity - $room_res->booked;
                                   $rooms_qty[] = $aval_qty;
                                   $total_adults_in_rooms += ($room_res->max_adults * $aval_qty);
                                   $total_childs_in_rooms += ($room_res->max_child * $aval_qty);
                                   $rooms_found[] = $room_res;
                                }
                            }
                            
                            if($any_room_found){
                                $counter++;
                            }
                            
                            
                        }
                        
                    }
                    
                   
                    // die;
                    $hotel_currency = '';
                    if($hotel_res->currency_symbol == '﷼')  {
                        $hotel_currency = 'SAR';
                    }
                                        // print_r($rooms_found);
    
                    $options_room = [];
                    $room_prices_arr = [];
                    // echo "Count $counter room ".count($rooms_adults);
                    if($total_adults_in_rooms >= $request_data->adult_searching && $total_childs_in_rooms >= $request_data->child_searching){
                        foreach($rooms_found as $room_res){
                            $room_price = $this->calculateRoomsAllDaysPrice($room_res,$request_data->check_in,$request_data->check_out);
                            $provider_markup = 0;
                            if(isset($provider_markup_data)){
                                
                                if($provider_markup_data->markup =='Percentage'){
                                    $provider_markup = ($room_price * $provider_markup_data->markup_value) / 100;
                                }else{
                                    $provider_markup = $provider_markup_data->markup_value;
                                }
                            }
                            // echo "Price $room_price and Markup $provider_markup "."<br>";
                            $room_price = $room_price + $provider_markup;
                            $room_prices_arr[] = $room_price;
                             $options_room[] = (Object)[
                                            'booking_req_id' => $room_res->id,
                                            'allotment' => $room_res->quantity - $room_res->booked,
                                            'room_name' => $room_res->room_type_name,
                                            'room_code' => $room_res->room_type_cat,
                                            'request_type' => $room_res->rooms_on_rq,
                                            'board_id' => $room_res->room_meal_type,
                                            'board_code' => $room_res->room_meal_type,
                                            'rooms_total_price' => $room_price,
                                            'rooms_selling_price' => $room_price,
                                            'rooms_qty' => 1,
                                            'adults' => $room_res->max_adults,
                                            'childs' => $room_res->max_child,
                                            'cancliation_policy_arr' => [],
                                            'rooms_list' => []
                                        ];
                        }
                        
                        $hotel_list_item = (Object)[
                            'hotel_provider' => 'custom_hotel_provider',
                            'custom_hotel_provider' => $hotel_provider_res->provider,
                            'admin_markup' => $admin_custom_hotel_pro_markup,
                            'customer_markup' => $customer_markup,
                            'admin_markup_type' => 'Percentage',
                            'customer_markup_type' => 'Percentage',
                            'hotel_id' => $hotel_res->id,
                            'hotel_name' => $hotel_res->property_name,
                            'stars_rating' => $hotel_res->star_type,
                            'hotel_curreny' => $hotel_currency,
                            'min_price' => min($room_prices_arr),
                            'max_price' => max($room_prices_arr),
                            'rooms_options' => $options_room,
                        ];
                     
                        
                        $hotel_first_char = substr($hotel_res->property_name, 0, 10);
                        if($hotel_first_char == $destination_first_char){
                            array_push($hotels_list_arr_match,$hotel_list_item);
                        }else{
                            array_push($hotels_list_arr,$hotel_list_item);
                        }
                       
                    }
                }
            }
        }
        
        // ************************************************************ //
        // Travelenda Provider
        // ************************************************************ //
        
        if($this->checkPrvoiderAllowOrNot($sub_customer_id->id,'travelenda')){
            $rooms_no               = 1;
            $room_request_create    = [];
            
            foreach($request_all_data->rooms_counter as $index => $room_counter){
                
                $child_age = [];
                $childern = $request_all_data->children[$index];
                
                // echo "child is $childern ";
                $child_age_index = 'child_ages'.$room_counter;
                
                $child_ages = $request_all_data->$child_age_index;
                // echo $child_age_index;
                for($i = 0; $i<$childern; $i++){
                  
                    array_push($child_age,$child_ages[$i]);
                }
                $single_room = (object)[
                        'Room'=>$rooms_no++,
                        'Adults'=>$request_all_data->Adults[$index],
                        'Children'=>$request_all_data->children[$index],
                        'ChildrenAge'=>$child_age
                    ];
                    
                    array_push($room_request_create,$single_room);
            }
            
            function travellandasearch($CityId, $CheckInDate, $CheckOutDate, $res_data, $country_nationality){
                $url = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                $data = array('case' => 'travelandaSearch', 'CityId' => $CityId, 'CheckInDate' => $CheckInDate, 'CheckOutDate' => $CheckOutDate, 'res_data' => json_encode($res_data), 'country_nationality' =>$country_nationality);
                // print_r($data);die();
                Session::put('travellanda_search_request',json_encode($data));
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $responseData = curl_exec($ch);
                // echo $responseData;die();
                if (curl_errno($ch)) {
                    return curl_error($ch);
                }
                curl_close($ch);
                return $responseData;
            }
            
            $responseData3 = travellandasearch($CityId, $request_data->check_in, $request_data->check_out, $room_request_create, $request_data->country_nationality);
            
            $result_travellanda      = json_decode($responseData3);
            $travellanda_obj         = [];
            $travelenda_hotels_count = 0;
            $travelenda_curreny      = '';
            
            if(isset($result_travellanda->Body->HotelsReturned)){
                  $travelenda_curreny = $result_travellanda->Body->Currency;
                  if($result_travellanda->Body->HotelsReturned > 1){
                    // $travellanda_obj=array();
                        if(isset($result_travellanda->Body->Hotels->Hotel)){
                            $travellanda_obj=$result_travellanda->Body->Hotels->Hotel;
                            $travelenda_hotels_count=$result_travellanda->Body->HotelsReturned ?? '0';
                        
                        }
                        
                }
            }
            
            foreach($travellanda_obj as $travel_res){
                $rooms_list     = [];
                $prices_arr     = [];
                $option_list    = [];
                if(isset($travel_res->Options->Option)){
                    foreach($travel_res->Options->Option as $room_res){
                        $options_room = [];
                        $rooms_qty = 0;
                        $total_adults = 0;
                        $total_childs = 0;
                        $room_name = '';
                        if(isset($room_res->Rooms->Room)){
                            
                            if (is_array($room_res->Rooms->Room)) {
                                $rooms_qty = count($room_res->Rooms->Room);
                                $room_name = $room_res->Rooms->Room[0]->RoomName;
                               foreach($room_res->Rooms->Room as $room_list_res){
                                   
                                   $daily_prices = [];
                                   if(isset($room_list_res->DailyPrices)){
                                       $daily_prices = $room_list_res->DailyPrices->DailyPrice;
                                   }
                                   
                                   $total_adults += $room_list_res->NumAdults;
                                   $total_childs += $room_list_res->NumChildren;
                                   
                                    $options_room[] = (Object)[
                                            'room_id' => $room_list_res->RoomId,
                                            'room_name' => $room_list_res->RoomName,
                                            'adults' => $room_list_res->NumAdults,
                                            'childs' => $room_list_res->NumChildren,
                                            'room_price' => $room_list_res->RoomPrice,
                                            'daily_prices' => $daily_prices
                                        ];
                                }
                            } else {
                                $rooms_qty = 1;
                                $room_name = $room_res->Rooms->Room->RoomName;
                                
                                $daily_prices = [];
                                   if(isset($room_list_res->DailyPrices)){
                                       $daily_prices = $room_res->Rooms->Room->DailyPrices->DailyPrice;
                                   }
                                   
                                   $total_adults += $room_res->Rooms->Room->NumAdults;
                                   $total_childs += $room_res->Rooms->Room->NumChildren;
                                   
                               $options_room[] = (Object)[
                                            'room_id' => $room_res->Rooms->Room->RoomId,
                                            'room_name' => $room_res->Rooms->Room->RoomName,
                                            'adults' => $room_res->Rooms->Room->NumAdults,
                                            'childs' => $room_res->Rooms->Room->NumChildren,
                                            'room_price' => $room_res->Rooms->Room->RoomPrice,
                                            'daily_prices' => $daily_prices
                                        ];
                            }
                            
                        }
                        
                        if(isset($room_res->OptionId)){
                            $option_list[] = (Object)[
                                'booking_req_id' => $room_res->OptionId,
                                'allotment' => 1,
                                'room_name' => $room_name,
                                'room_code' => '',
                                'request_type' => $room_res->OnRequest,
                                'board_id' => $room_res->BoardType,
                                'board_code' => '',
                                'rooms_total_price' => $room_res->TotalPrice,
                                'rooms_selling_price' => '',
                                'rooms_qty' => $rooms_qty,
                                'adults' => $total_adults,
                                'childs' => $total_childs,
                                'cancliation_policy_arr'=>[],
                                'rooms_list' => $options_room
                            ];
                            
                            $prices_arr[] = $room_res->TotalPrice;
                        }
                        
                    }
                    
                    // print_r($option_list);
                }
                
                if(!empty($prices_arr)){
                    $max_hotel_price = max($prices_arr);
                    $min_hotel_price = min($prices_arr);
                }
                else{
                    $min_hotel_price =  0;
                    $max_hotel_price = 0;  
                }
                
                $hotel_list_item = (Object)[
                    'hotel_provider' => 'travelenda',
                    'admin_markup' => $admin_travelenda_markup,
                    'customer_markup' => $customer_markup,
                    'admin_markup_type' => 'Percentage',
                    'customer_markup_type' => 'Percentage',
                    'hotel_id' => $travel_res->HotelId,
                    'hotel_name' => $travel_res->HotelName,
                    'stars_rating' => $travel_res->StarRating,
                    'hotel_curreny' => $travelenda_curreny,
                    'min_price' => $min_hotel_price,
                    'max_price' => $max_hotel_price,
                    'rooms_options' => $option_list,
                ];
                
                $hotel_first_char = substr($travel_res->HotelName, 0, 10);
                if($hotel_first_char == $destination_first_char){
                    array_push($hotels_list_arr_match,$hotel_list_item);
                }else{
                    array_push($hotels_list_arr,$hotel_list_item);
                }
            }
        }
        
        // ************************************************************ //
        // Hotel Bed Provider
        // ************************************************************ //
        
        if($this->checkPrvoiderAllowOrNot($sub_customer_id->id,'hotel_beds')){
            $rooms_no               = 1;
            $room_request_create    = [];
            $adults_counts_arr      = array_count_values($request_all_data->Adults);
            
            foreach($request_all_data->rooms_counter as $index => $room_counter){
                
                $others_pax = [];
                $childern = $request_all_data->children[$index];
                
                // echo "child is $childern ";
                $child_age_index = 'child_ages'.$room_counter;
                
                $child_ages = $request_all_data->$child_age_index;
                // echo $child_age_index;
                for($i = 0; $i<$childern; $i++){
                  
                    $others_pax[] = (Object)[
                            'type' => 'CH',
                            'age'=> $child_ages[$i]
                        ];
                    // array_push($child_age,$child_ages[$i]);
                }
                $single_room = (object)[
                        'rooms'=>2,
                        'adults'=>$request_all_data->Adults[$index],
                        'children'=>$request_all_data->children[$index],
                        'paxes'=>$others_pax
                    ];
                    
                    array_push($room_request_create,$single_room);
            }
            
            // Create an array to store the counts
            $counts = [];
            
            // Iterate through the array
            foreach ($room_request_create as $object) {
                // Convert the object to an associative array for comparison
                $data = (array) $object;
            
                // Convert the paxes array to a string for comparison
                $data['paxes'] = json_encode($data['paxes']);
            
                // Create a unique identifier for the object's values
                $identifier = json_encode($data);
            
                // Count occurrences of each identifier
                if (!isset($counts[$identifier])) {
                    $counts[$identifier] = 1;
                } else {
                    $counts[$identifier]++;
                }
            }
            
            // Display the counts
            $final_convert_rooms = [];
            foreach ($counts as $identifier => $count) {
                $rooms_data = json_decode($identifier, true);
                $rooms_data['rooms'] = $count;
                $rooms_data['paxes'] = json_decode($rooms_data['paxes']);
                $final_convert_rooms[] = (object)$rooms_data;
                // echo "Object with values: " . print_r($rooms_data, true) . " appears {$count} times." . PHP_EOL;
            }
            
            if($request_all_data->lat == NULL && $request_all_data->long == NULL && $request_all_data->cityd == 'Jerusalem'){
                $lat='31.7683';
                $long='35.2137';
            }
            else{
                $lat=$request_all_data->lat;
                $long=$request_all_data->long;  
             }
            
            $room_request_create = $final_convert_rooms;
            
            function hotelbedssearch($newstart, $newend, $res_hotel_beds, $lat, $long){
                $url = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi.php";
                $data = array('case' => 'serach_hotelbeds', 'CheckIn' => $newstart, 'CheckOut' => $newend, 'res_hotel_beds' => json_encode($res_hotel_beds), 'lat' =>$lat,'long' =>$long);
                // print_r($data);die();
                Session::put('hotelbeds_search_request',json_encode($data));
                 
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
                // curl_setopt($ch, CURLOPT_TCP_FASTOPEN, true);
                $responseData = curl_exec($ch);
                // echo $responseData;die();
                if (curl_errno($ch)) {
                    return curl_error($ch);
                }
                curl_close($ch);
                return $responseData;
            }
            
            $responseData3 = hotelbedssearch($request_data->check_in, $request_data->check_out, $room_request_create, $lat, $long);
            
            $result_hotelbeds = json_decode($responseData3);
          
            $hotel_bed_counts = 0;
            if(isset($result_hotelbeds->hotels->total)){
                $hotel_bed_counts = $result_hotelbeds->hotels->total;
            }
            
            if(isset($result_hotelbeds->hotels->hotels)){
                foreach($result_hotelbeds->hotels->hotels as $hotel_res){
                    $stars_rating = '';
                    if($hotel_res->categoryCode == '5EST'){
                        $stars_rating = 5;
                    }
                    
                    if($hotel_res->categoryCode == '4EST'){
                        $stars_rating = 4;
                    }
                    
                    if($hotel_res->categoryCode == '3EST'){
                        $stars_rating = 3;
                    }
                    
                    if($hotel_res->categoryCode == '2EST'){
                        $stars_rating = 2;
                    }
                    
                    if($hotel_res->categoryCode == '1EST'){
                        $stars_rating = 1;
                    }
                    
                    // Create Rooms List
                    
                    $options_room = [];
                if(isset($hotel_res->rooms)){
                    
                    foreach($hotel_res->rooms as $room_res){
    
                        
                        
                        if(isset($room_res->rates)){
                            
                            if (is_array($room_res->rates)) {
                               foreach($room_res->rates as $room_list_res){
                                   $selling_rate = 0;
                                   if(isset($room_list_res->sellingRate)){
                                       $selling_rate = $room_list_res->sellingRate;
                                   }
                                   
                                   // Rooms Cancilation Policy
                                   $cancliation_policy_arr = [];
                                   if(isset($room_list_res->cancellationPolicies)){
                                       foreach($room_list_res->cancellationPolicies as $cancel_res){
                                           $cancel_tiem = (Object)[
                                                'amount'=> $cancel_res->amount,
                                                'from_date'=> $cancel_res->from,
                                               ];
                                           $cancliation_policy_arr[] = $cancel_tiem;
                                       }
                                   }
                                   
                                   
                                    $options_room[] = (Object)[
                                            'booking_req_id' => $room_list_res->rateKey,
                                            'allotment' => $room_list_res->allotment,
                                            'room_name' => $room_res->name,
                                            'room_code' => $room_res->code,
                                            'request_type' => '',
                                            'board_id' => $room_list_res->boardName,
                                            'board_code' => $room_list_res->boardCode,
                                            'rooms_total_price' => $room_list_res->net,
                                            'rooms_selling_price' => $selling_rate,
                                            'rooms_qty' => $room_list_res->rooms,
                                            'adults' => $room_list_res->adults,
                                            'childs' => $room_list_res->children,
                                            'cancliation_policy_arr' => $cancliation_policy_arr,
                                            'rooms_list' => []
                                        ];
                                }
                            } 
                            
                        }
                        
                       
                        
                    }
                    
                    // print_r($option_list);
                }
                    
                    
                    
                    $hotel_list_item = (Object)[
                        'hotel_provider' => 'hotel_beds',
                        'admin_markup' => $admin_hotel_bed_markup,
                        'customer_markup' => $customer_markup,
                        'admin_markup_type' => 'Percentage',
                        'customer_markup_type' => 'Percentage',
                        'hotel_id' => $hotel_res->code,
                        'hotel_name' => $hotel_res->name,
                        'stars_rating' => $stars_rating,
                        'hotel_curreny' => $hotel_res->currency,
                        'min_price' => $hotel_res->minRate,
                        'max_price' => $hotel_res->maxRate,
                        'rooms_options' => $options_room
                        
                    ];
                    
                    $hotel_first_char = substr($hotel_res->name, 0, 10);
                    if($hotel_first_char == $destination_first_char){
                        array_push($hotels_list_arr_match,$hotel_list_item);
                    }else{
                        array_push($hotels_list_arr,$hotel_list_item);
                    }
                
                }
                // print_r($hotels_list_arr);
                // die;
            }
        }
        
        $final_hotel_Array = array_merge($hotels_list_arr_match, $hotels_list_arr);
        
        return response()->json([
            'status'            => 'success',
            'travelenda_count'  => $travelenda_hotels_count ?? 0,
            'hotel_beds_counts' => $hotel_bed_counts ?? 0,
            'hotels_list'       => $final_hotel_Array
        ]);
    }
    
    // function hotels_details_ajax(Request $request){
        
    //     $request_data = json_decode($request->request_data);
        
        
    //     $hotels_detials_data = [];
        
        
    //     if($request_data->provider == 'custom_hotel_provider'){
    //         $hotel_data = DB::table('hotels')->where('id',$request_data->hotel_code)->first();
    //         $customer_data = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
            
            
    //         $faclility_arr = [];
    //           if(isset($hotel_data->facilities)){
    //                 $count=1;
    //                 $facilities = unserialize($hotel_data->facilities);
    //                 foreach($facilities as $facility){
    //                      if($count < 7){
    //                          $count++;
    //                          $faclility_arr[] = $facility;
    //                      }else{
    //                          break;
    //                      }
                        
    //                 }
    //             }
              
    //           $hotel_address = $hotel_data->property_address ?? '';
    //           $hotels_detials_data = [
    //                 'image'=> $customer_data->webiste_Address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'',
    //                 'address' =>$hotel_address,
    //                 'facilities' =>$faclility_arr,
    //                 'description'=> $hotel_data->property_desc ?? '',
    //               ];

    //           return response()->json([
    //                 'status' => 'success',
    //                 'details_data' => $hotels_detials_data
    //               ]);
    //     }
        
    //     // Get Details For Custom Hotel 
    //     if($request_data->provider == 'Custome_hotel'){
    //         $hotel_data = DB::table('hotels')->where('id',$request_data->hotel_code)->first();
    //         $customer_data = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
            
            
    //         $faclility_arr = [];
    //           if(isset($hotel_data->facilities)){
    //                 $count=1;
    //                 $facilities = unserialize($hotel_data->facilities);
    //                 foreach($facilities as $facility){
    //                      if($count < 7){
    //                          $count++;
    //                          $faclility_arr[] = $facility;
    //                      }else{
    //                          break;
    //                      }
                        
    //                 }
    //             }
              
    //           $hotel_address = $hotel_data->property_address ?? '';
    //           $hotels_detials_data = [
    //                 'image'=> $customer_data->webiste_Address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'',
    //                 'address' =>$hotel_address,
    //                 'facilities' =>$faclility_arr,
    //                 'description'=> $hotel_data->property_desc ?? '',
    //               ];

    //           return response()->json([
    //                 'status' => 'success',
    //                 'details_data' => $hotels_detials_data
    //               ]);
    //     }
        
    //     if($request_data->provider == 'hotel_beds'){
    //         $data = array(    
    //               'case' => 'hotel_details',
    //               'hotel_beds_code' => $request_data->hotel_code,
    //               );
              
    //           $curl = curl_init();
    //           curl_setopt_array($curl, array(
              
    //           // CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_hotel_details_by_hotelbeds',
    //           CURLOPT_URL => 'https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi.php',
    //           CURLOPT_RETURNTRANSFER => true,
    //           CURLOPT_ENCODING => '',
    //           CURLOPT_MAXREDIRS => 1,
    //           CURLOPT_TIMEOUT => 0,
    //           CURLOPT_FOLLOWLOCATION => true,
    //           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //           CURLOPT_CUSTOMREQUEST => 'POST',
    //           CURLOPT_POSTFIELDS =>  $data,
    //           CURLOPT_HTTPHEADER => array(
    //           'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
    //           ),
    //           ));
              
    //           $response = curl_exec($curl);
    //         //   echo $response;die();
    //           $hotels_details = json_decode($response);
    //           $faclility_arr = [];
    //           if(isset($hotels_details->hotel->facilities)){
    //                 $count=1;
    //                 foreach($hotels_details->hotel->facilities as $facility){
    //                     if($facility->description->content == 'Wi-fi' ||
    //                     $facility->description->content == 'Internet access' || $facility->description->content == 'TV' || $facility->description->content == 'Wake-up service' 
    //                     || $facility->description->content == 'Smoking rooms'
    //                     || $facility->description->content == 'Wheelchair-accessible'
    //                     || $facility->description->content == 'Laundry service'
    //                     || $facility->description->content == 'Banquet hall' 
    //                     || $facility->description->content == 'Non-smoking establishment' 
    //                     || $facility->description->content == 'Safe'){
    //                      if($count < 7){
    //                          $count++;
    //                          $faclility_arr[] = $facility->description->content;
    //                      }else{
    //                          break;
    //                      }
    //                     }
    //                 }
    //             }
              
    //           $hotel_address = $hotels_details->hotel->address->content ?? '';
    //           $hotels_detials_data = [
    //                 'image'=> 'https://photos.hotelbeds.com/giata/bigger/'.$hotels_details->hotel->images[0]->path.'',
    //                 'address' =>$hotel_address,
    //                 'facilities' =>$faclility_arr,
    //                 'description'=> $hotels_details->hotel->description->content ?? '',
    //               ];
    //           curl_close($curl);
    //           return response()->json([
    //                 'status' => 'success',
    //                 'details_data' => $hotels_detials_data
    //               ]);
    //     }
        
    //     if($request_data->provider == 'travelenda'){
    //         $data = array(
                            
    //             'case' => 'GetHotelDetails',
    //             'HotelId' => $request_data->hotel_code,
    //             );
            
    //         $curl = curl_init();
    //         curl_setopt_array($curl, array(
             
    //         CURLOPT_URL => 'https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php',
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 1,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS =>  $data,
    //         CURLOPT_HTTPHEADER => array(
    //         'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
    //         ),
    //         ));
            
    //         $response = curl_exec($curl);
    //         //echo $response;die();
    //         curl_close($curl);
            
    //         $hotel_detail=json_decode($response);
            
    //          $hotel_image = 'https://localhost/haramaynhotels/public/admin_package/frontend/images/detail_img/no-photo-available-icon-4.jpg';
    //          if(isset($hotel_detail->Body->Hotels->Hotel->Images)){
    //              if(is_array($hotel_detail->Body->Hotels->Hotel->Images)){
    //                  $hotel_image = $hotel_detail->Body->Hotels->Hotel->Images->Image[0];
    //              }
                 
    //              if(is_object($hotel_detail->Body->Hotels->Hotel->Images)){
    //                  $hotel_image = $hotel_detail->Body->Hotels->Hotel->Images->Image[0] ?? '';
       
    //               }
    //          }
             
    //          $faclility_arr = [];
    //              if(isset($hotel_detail->Body->Hotels->Hotel->Facilities)){
    //                 $count=1;
    //                 foreach($hotel_detail->Body->Hotels->Hotel->Facilities->Facility as $facility){
    //                     if($facility->FacilityType == 'Hotel Facilities'){
    //                         if($facility->FacilityName == 'Free WiFi' 
    //                     || $facility->FacilityName == 'Internet access' 
    //                     || $facility->FacilityName == 'TV' 
    //                     || $facility->FacilityName == 'Wake-up service' 
    //                     || $facility->FacilityName == 'Smoking rooms'
    //                     || $facility->FacilityName == 'Wheelchair accessible (may have limitations)'
    //                     || $facility->FacilityName == 'Laundry facilities'
    //                     || $facility->FacilityName == 'Banquet hall' 
    //                     || $facility->FacilityName == 'Non-smoking establishment' 
    //                     || $facility->FacilityName == 'Safe'){
    //                         if($count < 7){
                             
    //                             $faclility_arr[] = $facility->FacilityName;
    //                             $count=$count+1;   
    //                          }else{
    //                              break;
    //                          }
    //                         }
    //                     }
    //                 }
    //             }
                
                
                
    //             $hotel_address =  $hotel_detail->Body->Hotels->Hotel->Address ?? '';
    //             $hotels_detials_data = [
    //                 'image'=> $hotel_image,
    //                 'address' =>$hotel_address,
    //                 'facilities' =>$faclility_arr,
    //                 'description'=> $hotel_detail->Hotel->Description ?? '',
    //               ];
                  
    //           return response()->json([
    //             'status' => 'success',
    //             'details_data' => $hotels_detials_data
    //           ]);
             
    //     }
    // }
    
    function hotels_details_ajax(Request $request){
        
        $request_data = json_decode($request->request_data);
        
        
        $hotels_detials_data = [];
        
        
        
        // Get Details For Custom Hotel 
        if($request_data->provider == 'custom_hotel_provider'){
            $provider_data = DB::table('custom_hotel_provider')->where('provider_name',$request_data->provider)->first();
            
            $hotel_data = DB::table('hotels')->where('id',$request_data->hotel_code)->first();
            $customer_data = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
            
            
            $faclility_arr = [];
               if(isset($hotel_data->facilities)){
                    $count=1;
                    $facilities = unserialize($hotel_data->facilities);
                    foreach($facilities as $facility){
                         if($count < 7){
                             $count++;
                             $faclility_arr[] = $facility;
                         }else{
                             break;
                         }
                        
                    }
                }
              
              $hotel_address = $hotel_data->property_address ?? '';
              $hotels_detials_data = [
                    'image'=> $customer_data->webiste_Address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'',
                    'address' =>$hotel_address,
                    'facilities' =>$faclility_arr
                  ];

              return response()->json([
                    'status' => 'success',
                    'details_data' => $hotels_detials_data
                  ]);
        }
        
        if($request_data->provider == 'Custome_hotel'){
            $hotel_data = DB::table('hotels')->where('id',$request_data->hotel_code)->first();
            $customer_data = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
            
            
            $faclility_arr = [];
               if(isset($hotel_data->facilities)){
                    $count=1;
                    $facilities = unserialize($hotel_data->facilities);
                    foreach($facilities as $facility){
                         if($count < 7){
                             $count++;
                             $faclility_arr[] = $facility;
                         }else{
                             break;
                         }
                        
                    }
                }
              
              $hotel_address = $hotel_data->property_address ?? '';
              $hotels_detials_data = [
                    'image'=> $customer_data->webiste_Address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'',
                    'address' =>$hotel_address,
                    'facilities' =>$faclility_arr
                  ];

              return response()->json([
                    'status' => 'success',
                    'details_data' => $hotels_detials_data
                  ]);
        }
        
        if($request_data->provider == 'hotel_beds'){
                
                $hotel_beds_code= $request_data->hotel_code;
                $curl = curl_init();
                $signature = $this->pr($hotel_beds_code);
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels/'.$hotel_beds_code.'/details?language=ENG&useSecondaryLanguage=False',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 1,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'GET',
                  CURLOPT_HTTPHEADER => array(
                    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
                    "X-Signature: $signature",
                    'Accept: application/json',
                    'Accept-Encoding: gzip'
                  ),
                ));
                
                $response = curl_exec($curl);
                //   echo $response;
                curl_close($curl);
                //echo $response;
                          $hotels_details = json_decode($response);
              
              $faclility_arr = [];
               if(isset($hotels_details->hotel->facilities)){
                    $count=1;
                    foreach($hotels_details->hotel->facilities as $facility){
                        if($facility->description->content == 'Wi-fi' ||
                        $facility->description->content == 'Internet access' || $facility->description->content == 'TV' || $facility->description->content == 'Wake-up service' 
                        || $facility->description->content == 'Smoking rooms'
                        || $facility->description->content == 'Wheelchair-accessible'
                        || $facility->description->content == 'Laundry service'
                        || $facility->description->content == 'Banquet hall' 
                        || $facility->description->content == 'Non-smoking establishment' 
                        || $facility->description->content == 'Safe'){
                         if($count < 7){
                             $count++;
                             $faclility_arr[] = $facility->description->content;
                         }else{
                             break;
                         }
                        }
                    }
                }
              
              $hotel_address = $hotels_details->hotel->address->content ?? '';
              $hotels_detials_data = [
                    'image'=> 'https://photos.hotelbeds.com/giata/bigger/'.$hotels_details->hotel->images[0]->path.'',
                    'address' =>$hotel_address,
                    'facilities' =>$faclility_arr
                  ];
              curl_close($curl);
              return response()->json([
                    'status' => 'success',
                    'details_data' => $hotels_detials_data
                  ]);
        }
        
        if($request_data->provider == 'travelenda'){
            $data = array(
                            
                'case' => 'GetHotelDetails',
                'HotelId' => $request_data->hotel_code,
                );
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
             
            CURLOPT_URL => 'https://admin.synchronousdigital.com/synchtravelhotelapi/travellandaapi.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
            'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
            ));
            
            $response = curl_exec($curl);
            //echo $response;die();
            curl_close($curl);
            
            $hotel_detail=json_decode($response);
            
             $hotel_image = 'https://localhost/haramaynhotels/public/admin_package/frontend/images/detail_img/no-photo-available-icon-4.jpg';
             if(isset($hotel_detail->Body->Hotels->Hotel->Images)){
                 if(is_array($hotel_detail->Body->Hotels->Hotel->Images)){
                     $hotel_image = $hotel_detail->Body->Hotels->Hotel->Images->Image[0];
                 }
                 
                 if(is_object($hotel_detail->Body->Hotels->Hotel->Images)){
                     $hotel_image = $hotel_detail->Body->Hotels->Hotel->Images->Image[0] ?? '';
       
                  }
             }
             
             $faclility_arr = [];
                 if(isset($hotel_detail->Body->Hotels->Hotel->Facilities)){
                    $count=1;
                    foreach($hotel_detail->Body->Hotels->Hotel->Facilities->Facility as $facility){
                        if($facility->FacilityType == 'Hotel Facilities'){
                            if($facility->FacilityName == 'Free WiFi' 
                        || $facility->FacilityName == 'Internet access' 
                        || $facility->FacilityName == 'TV' 
                        || $facility->FacilityName == 'Wake-up service' 
                        || $facility->FacilityName == 'Smoking rooms'
                        || $facility->FacilityName == 'Wheelchair accessible (may have limitations)'
                        || $facility->FacilityName == 'Laundry facilities'
                        || $facility->FacilityName == 'Banquet hall' 
                        || $facility->FacilityName == 'Non-smoking establishment' 
                        || $facility->FacilityName == 'Safe'){
                            if($count < 7){
                             
                                $faclility_arr[] = $facility->FacilityName;
                                $count=$count+1;   
                             }else{
                                 break;
                             }
                            }
                        }
                    }
                }
                
                
                
                $hotel_address =  $hotel_detail->Body->Hotels->Hotel->Address ?? '';
                $hotels_detials_data = [
                    'image'=> $hotel_image,
                    'address' =>$hotel_address,
                    'facilities' =>$faclility_arr
                  ];
                  
              return response()->json([
                'status' => 'success',
                'details_data' => $hotels_detials_data
              ]);
             
        }
    }
    
    function view_hotel_details(Request $request){
        $hotel_rooms_data = json_decode($request->hotel_search_data);
        
        $sub_customer_id=DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        $markup=DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)->orwhere('added_markup','synchtravel')->orderBy('id','DESC')->get();
        
        $admin_travelenda_markup = 0;
        $admin_hotel_bed_markup = 0;
        $admin_custom_markup = 0;
        
        $customer_markup = 0;
        $customer_custom_hotel_markup = 0;
        $customer_custom_hotel_markup_type = '';
        foreach($markup as $data){
            if($data->added_markup == 'synchtravel'){
                    if($data->provider == 'CustomHotel'){
                        $admin_custom_markup = $data->markup_value;
                    }
                    if($data->provider == 'Ratehawk'){
                        // config(['Ratehawk' => $data->markup_value]);  
                    }
                    if($data->provider == 'TBO'){
                        // config(['TBO' => $data->markup_value]);   
                    }
                    if($data->provider == 'Travellanda'){
                        $admin_travelenda_markup =$data->markup_value;  
                    }
                    if($data->provider == 'Hotelbeds'){
                        $admin_hotel_bed_markup = $data->markup_value;
                
                    }
                    if($data->provider == 'All'){
                        $admin_travelenda_markup =$data->markup_value;
                        $admin_hotel_bed_markup = $data->markup_value;
                        $admin_custom_markup = $data->markup_value;
                    }   
            }
            
            ///ended  
            
            if($data->added_markup == 'Haramayn_hotels'){
                    // if($data->provider == 'All'){
                        $customer_markup =  $data->markup_value;   
                    // } 
            }
        
        }
        
        $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id',$sub_customer_id->id)->where('status',1)->first();
        if($custom_hotel_markup){
            $customer_custom_hotel_markup = $custom_hotel_markup->markup_value;
            $customer_custom_hotel_markup_type = $custom_hotel_markup->markup;
        }
        
        // Detials For Custom Hotel Provider
        if($hotel_rooms_data->hotel_provider == 'custom_hotel_provider'){
            
            $hotel_data = DB::table('hotels')->where('id',$hotel_rooms_data->hotel_id)->first();
            $customer_data = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
            
              // Images
              $hotel_images_gallery = [];
              if(isset($hotel_data->room_gallery)){
                  $gallery_images = json_decode($hotel_data->room_gallery);
                  foreach($gallery_images as $hotel_img){
                      $hotel_images_gallery[] = $customer_data->webiste_Address.'/public/uploads/more_room_images/'.$hotel_img.'';
                  }
              }
              
               // Hotel Rooms
                  
                 if(isset($hotel_rooms_data->rooms_options)){
                        $gallery_images = [];
                        if(isset($hotel_data->room_gallery)){
                            $gallery_images = json_decode($hotel_data->room_gallery);
                        }
                      foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                                  // Save Rooms Images
                                  
                                  $image_arr = [];
                                    if(isset($gallery_images[$index])){
                                        $room_img = $customer_data->webiste_Address.'/public/uploads/more_room_images/'.$gallery_images[$index].'';
                                    }else{
                                        $room_img = $customer_data->webiste_Address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'';
                                    }
                                
                                                  $image_arr[] = $room_img;

                                   
                                  $hotel_rooms_data->rooms_options[$index]->rooms_images = $image_arr; 
                                  $hotel_rooms_data->rooms_options[$index]->rooms_facilities = unserialize($hotel_data->facilities); 
                              
                          
                      }
                  }
                  
                      // Manage Markups 
                    $admin_custom_hotel_pro_markup = 0;
                    $customer_markup = 0;
                  
                    foreach($markup as $data){
                        if($data->added_markup == 'synchtravel'){
                                if($data->provider == $hotel_rooms_data->custom_hotel_provider){
                                    $admin_custom_hotel_pro_markup = $data->markup_value;
                                }
                                
                                if($data->provider == 'All'){
                                    $admin_custom_hotel_pro_markup =$data->markup_value;
                                }   
                        }
                        
                        ///ended  
                        
                        if($data->added_markup == 'Haramayn_hotels'){
                                // if($data->provider == 'All'){
                                    $customer_markup =  $data->markup_value;   
                                // } 
                        }
                    
                    }
            
            $hotel_detials_generted_Obj = (Object)[
                    'hotel_provider' => 'custom_hotel_provider',
                    'custom_hotel_provider' => $hotel_rooms_data->custom_hotel_provider,
                    'admin_markup' => $admin_custom_hotel_pro_markup,
                    'customer_markup' => $customer_markup,
                    'admin_markup_type' => 'Percentage',
                    'customer_markup_type' => 'Percentage',
                    'hotel_code' => $hotel_data->id,
                    'hotel_name' => $hotel_data->property_name,
                    'hotel_address' => $hotel_data->property_address,
                    'longitude' => $hotel_data->longitude,
                    'latitude' => $hotel_data->latitude,
                    'hotel_country' => $hotel_data->property_country ?? '',
                    'hotel_city' => $hotel_data->property_city ?? '',
                    'stars_rating'=> $hotel_rooms_data->stars_rating,
                    'hotel_curreny'=> $hotel_rooms_data->hotel_curreny,
                    'min_price'=> $hotel_rooms_data->min_price,
                    'max_price'=> $hotel_rooms_data->max_price,
                    'description'=> $hotel_data->property_desc ?? '',
                    'hotel_gallery'=> $hotel_images_gallery,
                    'hotel_boards' =>[],
                    'hotel_segments' =>[],
                    'hotel_facilities' =>unserialize($hotel_data->facilities),
                    'rooms_options' =>$hotel_rooms_data->rooms_options
                ];
                
            return response()->json([
                'status' => 'success',
                'hotel_details' => $hotel_detials_generted_Obj
              ]);
            
        }
        
        // Detials For Custom Hotel
        if($hotel_rooms_data->hotel_provider == 'Custome_hotel'){
            
            $hotel_data = DB::table('hotels')->where('id',$hotel_rooms_data->hotel_id)->first();
            $customer_data = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
            
              // Images
              $hotel_images_gallery = [];
              if(isset($hotel_data->room_gallery)){
                  $gallery_images = json_decode($hotel_data->room_gallery);
                  foreach($gallery_images as $hotel_img){
                      $hotel_images_gallery[] = $customer_data->webiste_Address.'/public/uploads/more_room_images/'.$hotel_img.'';
                  }
              }
              
               // Hotel Rooms
                  
                 if(isset($hotel_rooms_data->rooms_options)){
                        $gallery_images = [];
                        if(isset($hotel_data->room_gallery)){
                            $gallery_images = json_decode($hotel_data->room_gallery);
                        }
                      foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                                  // Save Rooms Images
                                  
                                  $image_arr = [];
                                    if(isset($gallery_images[$index])){
                                        $room_img = $customer_data->webiste_Address.'/public/uploads/more_room_images/'.$gallery_images[$index].'';
                                    }else{
                                        $room_img = $customer_data->webiste_Address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'';
                                    }
                                
                                                  $image_arr[] = $room_img;

                                   
                                  $hotel_rooms_data->rooms_options[$index]->rooms_images = $image_arr; 
                                  $hotel_rooms_data->rooms_options[$index]->rooms_facilities = unserialize($hotel_data->facilities); 
                              
                          
                      }
                  }
            
            $hotel_detials_generted_Obj = (Object)[
                    'hotel_provider' => 'Custome_hotel',
                    'admin_markup' => $admin_custom_markup,
                    'customer_markup' => $customer_custom_hotel_markup,
                    'admin_markup_type' => 'Percentage',
                    'customer_markup_type' => $customer_custom_hotel_markup_type,
                    'hotel_code' => $hotel_data->id,
                    'hotel_name' => $hotel_data->property_name,
                    'hotel_address' => $hotel_data->property_address,
                    'longitude' => $hotel_data->longitude,
                    'latitude' => $hotel_data->latitude,
                    'hotel_country' => $hotel_data->property_country ?? '',
                    'hotel_city' => $hotel_data->property_city ?? '',
                    'stars_rating'=> $hotel_rooms_data->stars_rating,
                    'hotel_curreny'=> $hotel_rooms_data->hotel_curreny,
                    'min_price'=> $hotel_rooms_data->min_price,
                    'max_price'=> $hotel_rooms_data->max_price,
                    'description'=> $hotel_data->property_desc ?? '',
                    'hotel_gallery'=> $hotel_images_gallery,
                    'hotel_boards' =>[],
                    'hotel_segments' =>[],
                    'hotel_facilities' =>unserialize($hotel_data->facilities),
                    'rooms_options' =>$hotel_rooms_data->rooms_options
                ];
                
            return response()->json([
                'status' => 'success',
                'hotel_details' => $hotel_detials_generted_Obj
              ]);
            
        }
        
        // Detials For Hotel Beds
        if($hotel_rooms_data->hotel_provider == 'hotel_beds'){
            
            // Get Hotel Details 
                $data = array(
                
                      'case' => 'hotel_details',
                      'hotel_beds_code' => $hotel_rooms_data->hotel_id,
                      );
                  
                  $curl = curl_init();
                  curl_setopt_array($curl, array(
                  
                  // CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_hotel_details_by_hotelbeds',
                  CURLOPT_URL => 'https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi.php',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 1,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  $data,
                  CURLOPT_HTTPHEADER => array(
                  'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                  ),
                  ));
                  
                  $response = curl_exec($curl);
                  $hotel_meta_data = json_decode($response);
                //   print_r($hotel_meta_data);
                //   die;
                //   echo $response;die();
                
                  $hotel_meta_data = $hotel_meta_data->hotel;
                  
                  $hotel_name = '';
                  if(isset($hotel_meta_data->name->content)){
                      $hotel_name = $hotel_meta_data->name->content;
                  }
                  
                  // Images
                  $hotel_images_gallery = [];
                  if(isset($hotel_meta_data->images)){
                      foreach($hotel_meta_data->images as $hotel_img){
                          $hotel_images_gallery[] = 'https://photos.hotelbeds.com/giata/bigger/'.$hotel_img->path.'';
                      }
                  }
                  
                  // Board
                  $hotel_barod_arr = [];
                  if(isset($hotel_meta_data->boards)){
                      foreach($hotel_meta_data->boards as $board_res){
                          $board_item = (Object)[
                                'code'=>$board_res->code,
                                'board_name'=>$board_res->description->content
                              ];
                          $hotel_barod_arr[] = $board_item;
                      }
                  }
                  
                   // Segments
                  $hotel_segments_arr = [];
                  if(isset($hotel_meta_data->segments)){
                      foreach($hotel_meta_data->segments as $segment_res){
                          $hotel_segments_arr[] = $segment_res->description->content;
                      }
                  }
                  
                  // Hotel Facilities
                  
                  
                  $hotel_facilities_arr = [];
                  if(isset($hotel_meta_data->facilities)){
                      foreach($hotel_meta_data->facilities as $facility_res){
                          $hotel_facilities_arr[] = $facility_res->description->content;
                      }
                  }
                  
                  
                  // Hotel Rooms
                  
                  if(isset($hotel_rooms_data->rooms_options)){
                      foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                          foreach($hotel_meta_data->rooms as $hotel_rooms_data_res){
                              if($room_availibilty_res->room_code == $hotel_rooms_data_res->roomCode){
                                  // Save Rooms Images
                                  
                                  $image_arr = [];
                                   if($hotel_meta_data->images != NULL){
                                       foreach($hotel_meta_data->images as $img_res){
                                           if(isset($img_res->roomCode)){
                                               if($room_availibilty_res->room_code == $img_res->roomCode){
                                                   $image_arr[] = 'https://photos.hotelbeds.com/giata/bigger/'.$img_res->path.'';
                                               }
                                           }
                                       }
                                   }
                                   
                                   // Save Rooms Facilities
                                   $room_facilities_arr = [];
                                   if(isset($hotel_rooms_data_res->roomFacilities)){
                                       foreach($hotel_rooms_data_res->roomFacilities as $roomFacilities){
                                           $room_facilities_arr[] = $roomFacilities->description->content;
                                       }
                                   }
                                   
                                   $hotel_rooms_data->rooms_options[$index]->rooms_images = $image_arr; 
                                   $hotel_rooms_data->rooms_options[$index]->rooms_facilities = $room_facilities_arr; 
                              }
                          }
                      }
                  }
                  
                  
                  
                  $address = $hotel_meta_data->destination->name->content ?? '';
                  $address .= " ".$hotel_meta_data->country->description->content ?? "";
                  
                  
                
                $hotel_detials_generted_Obj = (Object)[
                        'hotel_provider' => 'hotel_beds',
                        'admin_markup' => $admin_hotel_bed_markup,
                        'customer_markup' => $customer_markup,
                        'admin_markup_type' => 'Percentage',
                        'customer_markup_type' => 'Percentage',
                        'hotel_code' => $hotel_meta_data->code,
                        'hotel_name' => $hotel_name,
                        'hotel_address' => $address,
                        'longitude' => $hotel_meta_data->coordinates->longitude,
                        'latitude' => $hotel_meta_data->coordinates->latitude,
                        'hotel_country' => $hotel_meta_data->country->description->content ?? '',
                        'hotel_city' => $hotel_meta_data->destination->name->content ?? '',
                        'stars_rating'=> $hotel_rooms_data->stars_rating,
                        'hotel_curreny'=> $hotel_rooms_data->hotel_curreny,
                        'min_price'=> $hotel_rooms_data->min_price,
                        'max_price'=> $hotel_rooms_data->max_price,
                        'description'=> $hotel_meta_data->description->content ?? '',
                        'hotel_gallery'=> $hotel_images_gallery,
                        'hotel_boards' =>$hotel_barod_arr,
                        'hotel_segments' =>$hotel_segments_arr,
                        'hotel_facilities' =>$hotel_facilities_arr,
                        'rooms_options' =>$hotel_rooms_data->rooms_options
                    ];
                    
                return response()->json([
                    'status' => 'success',
                    'hotel_details' => $hotel_detials_generted_Obj
                  ]);
                    
                    // dd($hotel_detials_generted_Obj);
        }
      
        // Details For Travelenda
        if($hotel_rooms_data->hotel_provider == 'travelenda'){
               $data = array(     
                    'case' => 'GetHotelDetails',
                    'HotelId' => $hotel_rooms_data->hotel_id,
                    );
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                 
                CURLOPT_URL => 'https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 1,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $data,
                CURLOPT_HTTPHEADER => array(
                'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                ),
                ));
                
                $response = curl_exec($curl);
                $hotel_meta_data = json_decode($response);
                // print_r($hotel_meta_data);
                //echo $response;die();
                curl_close($curl);
                
                
                
                if(isset($hotel_meta_data->Body->Hotels)){
                    $hotel_meta_data = $hotel_meta_data->Body->Hotels;
                    
                    // Images
                     $hotel_images_gallery = [];
                     if(isset($hotel_meta_data->Hotel->Images->Image)){
                         foreach($hotel_meta_data->Hotel->Images->Image as $hotel_img){
                              $hotel_images_gallery[] = $hotel_img;
                         }
                     }
                     
                     // Boards
                     $hotel_barod_arr = [];
                     
                     // Boards
                     $hotel_segments_arr = [];
                     
                     // Hotel Facilities
                      $hotel_facilities_arr = [];
                      if(isset($hotel_meta_data->Hotel->Facilities->Facility)){
                          foreach($hotel_meta_data->Hotel->Facilities->Facility as $facility_res){
                              $hotel_facilities_arr[] = $facility_res->FacilityName ?? '';
                          }
                      }
                      
                      // Hotel Rooms
                  
                      if(isset($hotel_rooms_data->rooms_options)){
                          foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                                      // Save Rooms Images
                                      if(isset($hotel_images_gallery[$index])){
                                          $room_img = $hotel_images_gallery[$index];
                                      }else{
                                          if(isset($hotel_images_gallery[0])){
                                              $room_img = $hotel_images_gallery[0];
                                          }else{
                                              $room_img = '';
                                          }
                                      }
                                       
                                      
                                      $image_arr = [$room_img];

                                       // Save Rooms Facilities
                                       $room_facilities_arr = [];
                                       if(isset($hotel_meta_data->Hotel->Facilities->Facility)){
                                            foreach($hotel_meta_data->Hotel->Facilities->Facility as $facility_res){
                                                if($facility_res->FacilityType == 'Hotel Facilities'){
                                                    $room_facilities_arr[] = $facility_res->FacilityName;
                                                }
                                               
                                           }
                                       }
                                       
                                    //   $data = array(
                
                                    //         'case' => 'travelandaPolicy',
                                    //         'OptionId' => $room_availibilty_res->booking_req_id,
                                    //         );
                                        
                                    //     $curl = curl_init();
                                    //     curl_setopt_array($curl, array(
                                         
                                    //     CURLOPT_URL => 'https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php',
                                    //     CURLOPT_RETURNTRANSFER => true,
                                    //     CURLOPT_ENCODING => '',
                                    //     CURLOPT_MAXREDIRS => 1,
                                    //     CURLOPT_TIMEOUT => 0,
                                    //     CURLOPT_FOLLOWLOCATION => true,
                                    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    //     CURLOPT_CUSTOMREQUEST => 'POST',
                                    //     CURLOPT_POSTFIELDS =>  $data,
                                    //     CURLOPT_HTTPHEADER => array(
                                    //     'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                                    //     ),
                                    //     ));
                                        
                                    //     $response = curl_exec($curl);
                                    //     json_decode($response)
                                    //     echo $response;die();
                                       
                                       $hotel_rooms_data->rooms_options[$index]->rooms_images = $image_arr; 
                                       $hotel_rooms_data->rooms_options[$index]->rooms_facilities = $room_facilities_arr; 
                                  
                              
                          }
                      }
                    
                    
                    $address = $hotel_meta_data->Hotel->Address;
                    
                    $hotel_detials_generted_Obj = (Object)[
                        'hotel_provider' => 'travelenda',
                        'admin_markup' => $admin_travelenda_markup,
                        'customer_markup' => $customer_markup,
                        'admin_markup_type' => 'Percentage',
                        'customer_markup_type' => 'Percentage',
                        'hotel_code' => $hotel_rooms_data->hotel_id,
                        'hotel_name' => $hotel_rooms_data->hotel_name,
                        'hotel_address' => $address,
                        'longitude' => $hotel_meta_data->Hotel->Longitude,
                        'latitude' => $hotel_meta_data->Hotel->Latitude,
                        'hotel_country' => $hotel_meta_data->Hotel->Location ?? '',
                        'hotel_city' => $hotel_meta_data->Hotel->Location ?? '',
                        'stars_rating'=> $hotel_rooms_data->stars_rating,
                        'hotel_curreny'=> $hotel_rooms_data->hotel_curreny,
                        'min_price'=> $hotel_rooms_data->min_price,
                        'max_price'=> $hotel_rooms_data->max_price,
                        'description'=> $hotel_meta_data->Hotel->Description ?? '',
                        'hotel_gallery'=> $hotel_images_gallery,
                        'hotel_boards' =>$hotel_barod_arr,
                        'hotel_segments' =>$hotel_segments_arr,
                        'hotel_facilities' =>$hotel_facilities_arr,
                        'rooms_options' =>$hotel_rooms_data->rooms_options
                    ];
                    
                return response()->json([
                    'status' => 'success',
                    'hotel_details' => $hotel_detials_generted_Obj
                  ]);
                }
        }
    }
    
    function hotels_checkout(Request $request){
        $hotel_request_data = json_decode($request->request_data);
        $selected_hotel = json_decode($request->selected_hotel);
        // dd($selected_hotel);
        $selected_hotel_details = json_decode($request->selected_hotel_details);
        
        $sub_customer_id=DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        $markup=DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)->orwhere('added_markup','synchtravel')->orderBy('id','DESC')->get();
        // print_r($markup);
        // die;
        $admin_travelenda_markup = 0;
        $admin_hotel_bed_markup = 0;
        $admin_custom_markup = 0;
        
        $customer_markup = 0;
        $customer_custom_hotel_markup = 0;
        $customer_custom_hotel_markup_type = '';
        foreach($markup as $data){
            if($data->added_markup == 'synchtravel'){
                    if($data->provider == 'CustomHotel'){
                        $admin_custom_markup = $data->markup_value;
                    }
                    if($data->provider == 'Ratehawk'){
                        // config(['Ratehawk' => $data->markup_value]);  
                    }
                    if($data->provider == 'TBO'){
                        // config(['TBO' => $data->markup_value]);   
                    }
                    if($data->provider == 'Travellanda'){
                        $admin_travelenda_markup =$data->markup_value;  
                    }
                    if($data->provider == 'Hotelbeds'){
                        $admin_hotel_bed_markup = $data->markup_value;
                
                    }
                    if($data->provider == 'All'){
                        $admin_travelenda_markup =$data->markup_value;
                        $admin_hotel_bed_markup = $data->markup_value;
                        $admin_custom_markup = $data->markup_value;
                    }   
            }
            
            ///ended  
            
            if($data->added_markup == 'Haramayn_hotels'){
                    // if($data->provider == 'All'){
                        $customer_markup =  $data->markup_value;   
                    // } 
            }
        
        }
        
        $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id',$sub_customer_id->id)->where('status',1)->first();
        if($custom_hotel_markup){
            $customer_custom_hotel_markup = $custom_hotel_markup->markup_value;
            $customer_custom_hotel_markup_type = $custom_hotel_markup->markup;
        }
        
        // dd($hotel_rooms_data);
        // Detials For Custom Hotel
        
        $checkout_Object = [];
        if(isset($selected_hotel->hotel_provider)){
            if($selected_hotel->hotel_provider == 'custom_hotel_provider'){
                $hotelbeds_select_room = $hotel_request_data->hotelbeds_select_room;
                
                $roomRate = [];
                if(isset($hotelbeds_select_room)){
                    foreach ($hotelbeds_select_room as $room_res){
                            $room_Obj = json_decode($room_res);
                            $roomRate[] = (Object)[
                                    'rate_rateKey' => $room_Obj->rateKey
                                ];       
                    }
                }
                
                $on_request = false;
                if(isset($selected_hotel)){
                  $hotel_data = $selected_hotel;
                  
                    $options_room = [];
                    $total_price = 0;
                    foreach($roomRate as $select_room_res){
                        if(isset($selected_hotel->rooms_options)){
                            $room_found = false;
                            foreach($selected_hotel->rooms_options as $room_res){
                                if(!$room_found){
                                    if($select_room_res->rate_rateKey == $room_res->booking_req_id){
                                        $total_price += $room_res->rooms_total_price;
                                        if($room_res->request_type == '1'){
                                            $on_request = true;
                                        }
                                        $options_room[] = (Object)[
                                                    'booking_req_id' => $room_res->booking_req_id,
                                                    'rate_class' => '',
                                                    'room_name' => $room_res->room_name,
                                                    'room_code' => $room_res->room_code,
                                                    'request_type' => $room_res->request_type,
                                                    'board_id' => $room_res->board_id,
                                                    'board_code' => $room_res->board_code,
                                                    'rooms_total_price' => $room_res->rooms_total_price,
                                                    'rooms_selling_price' =>  $room_res->rooms_total_price,
                                                    'rooms_qty' => $room_res->rooms_qty,
                                                    'adults' => $room_res->adults,
                                                    'childs' => $room_res->childs,
                                                    'cancliation_policy_arr' => [],
                                                    'rooms_list' => []
                                                ];
                                        $room_found = true;
                                    }
                                }
                               
                            }
                        }
                    }
                    
                      // Manage Markups 
                    $admin_custom_hotel_pro_markup = 0;
                    $customer_markup = 0;
                  
                    foreach($markup as $data){
                        if($data->added_markup == 'synchtravel'){
                                if($data->provider == $selected_hotel->custom_hotel_provider){
                                    $admin_custom_hotel_pro_markup = $data->markup_value;
                                }
                                
                                if($data->provider == 'All'){
                                    $admin_custom_hotel_pro_markup =$data->markup_value;
                                }   
                        }
                        
                        ///ended  
                        
                        if($data->added_markup == 'Haramayn_hotels'){
                                // if($data->provider == 'All'){
                                    $customer_markup =  $data->markup_value;   
                                // } 
                        }
                    
                    }
                    
                    $checkout_Object = (Object)[
                        'hotel_provider' => 'custom_hotel_provider',
                        'custom_hotel_provider' => $selected_hotel->custom_hotel_provider,
                        'on_request' => $on_request,
                        'admin_markup' => $admin_custom_hotel_pro_markup,
                        'customer_markup' => $customer_markup,
                        'admin_markup_type' => 'Percentage',
                        'customer_markup_type' => 'Percentage',
                        'hotel_id' => $selected_hotel->hotel_id,
                        'hotel_name' => $selected_hotel->hotel_name,
                        'checkIn' => $selected_hotel_details->check_in,
                        'checkOut' => $selected_hotel_details->check_out,
                        'stars_rating' => $selected_hotel->stars_rating,
                        'destinationCode' => $selected_hotel_details->hotel_address,
                        'destinationName' => $selected_hotel_details->hotel_address,
                        'zoneCode' => $selected_hotel_details->hotel_country,
                        'zoneName' => $selected_hotel_details->hotel_city,
                        'latitude' => $selected_hotel_details->latitude,
                        'longitude' => $selected_hotel_details->longitude,
                        'total_price' => $total_price,
                        'currency' => $selected_hotel->hotel_curreny,
                        'rooms_list' => $options_room
                      ];
                      
                      return response()->json([
                        'status' => 'success',
                        'hotels_data' => $checkout_Object,
                    ]);
              }
              
                if($result_checkrates->error){
                    return response()->json([
                        'status' => 'error',
                        'message' => $result_checkrates->error->message,
                    ]);
                }
            }
            
            if($selected_hotel->hotel_provider == 'Custome_hotel'){
                $hotelbeds_select_room= $hotel_request_data->rooms_select_data;
                $hotelbeds_select_room = json_decode($hotelbeds_select_room);
               

                    $roomRate = [];
                    // if(isset($hotelbeds_select_room)){
                    //     foreach ($hotelbeds_select_room as $room_res){
                    //             $room_Obj = json_decode($room_res);
                    //             $roomRate[] = (Object)[
                    //                     'rate_rateKey' => $room_Obj->rateKey
                    //                 ];       
                    //     }
                    // }
                    
                    if(isset($hotelbeds_select_room)){
                        foreach ($hotelbeds_select_room as $room_res){
                                $room_Obj = json_decode($room_res->room_rate_key);
                                 
                                $roomRate[] = (Object)[
                                        'rate_rateKey' => $room_Obj->rateKey
                                    ];       
                        }
                    }
                     
 //print_r($selected_hotel);die();
                    $on_request = false;
                  if(isset($selected_hotel)){
                      $hotel_data = $selected_hotel;
                      
                        $options_room = [];
                        $total_price = 0;
                        foreach($roomRate as $select_room_res){
                            if(isset($selected_hotel->rooms_options)){
                                $room_found = false;
                                foreach($selected_hotel->rooms_options as $room_res){
                                    if(!$room_found){
                                        if($select_room_res->rate_rateKey == $room_res->booking_req_id){
                                            $total_price += $room_res->rooms_total_price;
                                            if($room_res->request_type == '1'){
                                                $on_request = true;
                                            }
                                            
                                            $room_qty_selected = 0;
                                           if(isset($hotelbeds_select_room)){
                                                foreach ($hotelbeds_select_room as $room_select_res){
                                                        $room_Obj = json_decode($room_select_res->room_rate_key);
                                                        if($room_Obj->rateKey == $room_res->booking_req_id){
                                                            $room_qty_selected = $room_select_res->rooms_qty;
                                                        }     
                                                }
                                            }
                                            
                                            $options_room[] = (Object)[
                                                        'booking_req_id' => $room_res->booking_req_id,
                                                        'allotment' => $room_res->allotment,
                                                        'selected_qty' => $room_qty_selected,
                                                        'rate_class' => '',
                                                        'room_name' => $room_res->room_name,
                                                        'room_code' => $room_res->room_code,
                                                        'request_type' => $room_res->request_type,
                                                        'board_id' => $room_res->board_id,
                                                        'board_code' => $room_res->board_code,
                                                        'rooms_total_price' => $room_res->rooms_total_price * $room_qty_selected,
                                                        'rooms_selling_price' =>  $room_res->rooms_total_price * $room_qty_selected,
                                                        'rooms_qty' => $room_res->rooms_qty,
                                                        'adults' => $room_res->adults,
                                                        'childs' => $room_res->childs,
                                                        'cancliation_policy_arr' => $room_res->cancliation_policy_arr,
                                                        'rooms_list' => []
                                                    ];
                                            $room_found = true;
                                        }
                                    }
                                   
                                }
                            }
                        }
                        
                        
                        $checkout_Object = (Object)[
                            'hotel_provider' => 'Custome_hotel',
                            'on_request' => $on_request,
                            'admin_markup' => $admin_custom_markup,
                            'customer_markup' => $customer_custom_hotel_markup,
                            'admin_markup_type' => 'Percentage',
                            'customer_markup_type' => $customer_custom_hotel_markup_type,
                            'hotel_id' => $selected_hotel->hotel_id,
                            'hotel_name' => $selected_hotel->hotel_name,
                            'checkIn' => $selected_hotel_details->check_in,
                            'checkOut' => $selected_hotel_details->check_out,
                            'stars_rating' => $selected_hotel->stars_rating,
                            'destinationCode' => $selected_hotel_details->hotel_address,
                            'destinationName' => $selected_hotel_details->hotel_address,
                            'zoneCode' => $selected_hotel_details->hotel_country,
                            'zoneName' => $selected_hotel_details->hotel_city,
                            'latitude' => $selected_hotel_details->latitude,
                            'longitude' => $selected_hotel_details->longitude,
                            'total_price' => $total_price,
                            'currency' => $selected_hotel->hotel_curreny,
                            'rooms_list' => $options_room
                          ];
                          
                          return response()->json([
                            'status' => 'success',
                            'hotels_data' => $checkout_Object,
                        ]);
                  }
                  
                  
                  if($result_checkrates->error){
                      return response()->json([
                            'status' => 'error',
                            'message' => $result_checkrates->error->message,
                        ]);
                  }
            }
            
            if($selected_hotel->hotel_provider == 'hotel_beds'){
                    // $hotelbeds_select_room = $hotel_request_data->hotelbeds_select_room;
                    
                    $hotelbeds_select_room = json_decode($hotel_request_data->rooms_select_data);
                    // dd($hotelbeds_select_room);
                    $roomRate = [];
                    if(isset($hotelbeds_select_room)){
                        foreach ($hotelbeds_select_room as $room_res){
                                $room_Obj = json_decode($room_res->room_rate_key);
                                 
                                $roomRate[] = (Object)[
                                        'rate_rateKey' => $room_Obj->rateKey
                                    ];       
                        }
                    }
                    
                   
                    $roomRate = json_encode($roomRate);
                  
                  function hotelbedsgetBooking($roomRate){
                      $url = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi.php";
                      $data = array('case' => 'getBookingMultipleRooms', 'roomRate' => $roomRate);
                      //print_r($data);die();
                      Session::put('hotelbeds_search_request',json_encode($data));
                      $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_POST, true);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                      
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      $responseData = curl_exec($ch);
                      //echo $responseData;die();
                      if (curl_errno($ch)) {
                          return curl_error($ch);
                      }
                      curl_close($ch);
                      return $responseData;
                  }
          
                  $hotel_response = hotelbedsgetBooking($roomRate);
                //   echo $hotel_response;
                //   die;
                  $result_checkrates = json_decode($hotel_response);
                //   print_r($result_checkrates);
                //   die;
                  if(isset($result_checkrates->hotel)){
                      $hotel_data = $result_checkrates->hotel;
                      
                        $options_room = [];
                        if(isset($hotel_data->rooms)){
                            foreach($hotel_data->rooms as $room_res){
                                if(isset($room_res->rates)){
                                    
                                    if (is_array($room_res->rates)) {
                                       foreach($room_res->rates as $room_list_res){
                                           $selling_rate = 0;
                                           if(isset($room_list_res->sellingRate)){
                                               $selling_rate = $room_list_res->sellingRate;
                                           }
                                           
                                           // Rooms Cancilation Policy
                                           $cancliation_policy_arr = [];
                                           if(isset($room_list_res->cancellationPolicies)){
                                               foreach($room_list_res->cancellationPolicies as $cancel_res){
                                                   $cancel_tiem = (Object)[
                                                        'amount'=> $cancel_res->amount,
                                                        'type'=> '',
                                                        'from_date'=> $cancel_res->from,
                                                       ];
                                                   $cancliation_policy_arr[] = $cancel_tiem;
                                               }
                                           }
                                           
                                           $room_qty_selected = 0;
                                           if(isset($hotelbeds_select_room)){
                                                foreach ($hotelbeds_select_room as $room_select_res){
                                                        $room_Obj = json_decode($room_select_res->room_rate_key);
                                                        if($room_Obj->rateKey == $room_list_res->rateKey){
                                                            $room_qty_selected = $room_select_res->rooms_qty;
                                                        }     
                                                }
                                            }
                                           
                                           
                                            $options_room[] = (Object)[
                                                    'booking_req_id' => $room_list_res->rateKey,
                                                    'allotment' => $room_list_res->allotment,
                                                    'selected_qty' => $room_qty_selected,
                                                    'rate_class' => $room_list_res->rateClass,
                                                    'room_name' => $room_res->name,
                                                    'room_code' => $room_res->code,
                                                    'request_type' => '',
                                                    'board_id' => $room_list_res->boardName,
                                                    'board_code' => $room_list_res->boardCode,
                                                    'rooms_total_price' => $room_list_res->net,
                                                    'rooms_selling_price' => $selling_rate,
                                                    'rooms_qty' => $room_list_res->rooms,
                                                    'adults' => $room_list_res->adults,
                                                    'childs' => $room_list_res->children,
                                                    'cancliation_policy_arr' => $cancliation_policy_arr,
                                                    'rooms_list' => []
                                                ];
                                        }
                                    } 
                                    
                                }
                                
                               
                                
                            }
                            
                            // print_r($option_list);
                        }
                      
                        $checkout_Object = (Object)[
                            'hotel_provider' => 'hotel_beds',
                            'on_request' => false,
                            'admin_markup' => $admin_hotel_bed_markup,
                            'customer_markup' => $customer_markup,
                            'admin_markup_type' => 'Percentage',
                            'customer_markup_type' => 'Percentage',
                            'hotel_id' => $hotel_data->code,
                            'hotel_name' => $hotel_data->name,
                            'checkIn' => $hotel_data->checkIn,
                            'checkOut' => $hotel_data->checkOut,
                            'stars_rating' => $selected_hotel->stars_rating,
                            'destinationCode' => $hotel_data->destinationCode,
                            'destinationName' => $hotel_data->destinationName,
                            'zoneCode' => $hotel_data->zoneCode,
                            'zoneName' => $hotel_data->zoneName,
                            'latitude' => $hotel_data->latitude,
                            'longitude' => $hotel_data->longitude,
                            'total_price' => $hotel_data->totalNet,
                            'currency' => $hotel_data->currency,
                            'rooms_list' => $options_room
                          ];
                          
                          return response()->json([
                            'status' => 'success',
                            'hotels_data' => $checkout_Object,
                        ]);
                  }
                  
                  
                  if($result_checkrates->error){
                      return response()->json([
                            'status' => 'error',
                            'message' => $result_checkrates->error->message,
                        ]);
                  }
                  
                //   print_r($checkout_Object);
            }
            
            if($selected_hotel->hotel_provider == 'travelenda'){
            
                // $travelenda_select_rooms= $hotel_request_data->hotelbeds_select_room;
                $travelenda_select_rooms = json_decode($hotel_request_data->rooms_select_data);
                $roomRate = [];
                
                   if(isset($travelenda_select_rooms)){
                        foreach ($travelenda_select_rooms as $room_res){
                                $room_Obj = json_decode($room_res->room_rate_key);
                                $roomRate[] = (Object)[
                                        'OptionId' => $room_Obj->rateKey
                                    ];       
                        }
                    }
                 
                function travelandapreBooking($roomRate){
                      $url = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                      $data = array('case' => 'travelandapreBooking', 'roomRate' => json_encode($roomRate));
                      //print_r($data);die();
                      
                       $ch = curl_init();
                 
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_POST, true);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                      
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      $responseData = curl_exec($ch);
                      //echo $responseData;die();
                      if (curl_errno($ch)) {
                          return curl_error($ch);
                      }
                      curl_close($ch);
                      return $responseData;
                 }
            
                  $responseData3 = travelandapreBooking($roomRate);
                  $result_HotelPolicies= json_decode($responseData3);
                
                    // print_r($result_HotelPolicies);
                
                  if(isset($result_HotelPolicies->Body->OptionId)){

                        $options_room = [];
                        if(isset($selected_hotel->rooms_options)){
                            foreach($selected_hotel->rooms_options as $room_res){
                                
                                    if($room_res->booking_req_id == $result_HotelPolicies->Body->OptionId){
                                         
                                           
                                           // Rooms Cancilation Policy
                                           $cancliation_policy_arr = [];
                                           if(isset($result_HotelPolicies->Body->Policies->Policy)){
                                               foreach($result_HotelPolicies->Body->Policies->Policy as $cancel_res){
                                                   $cancel_tiem = (Object)[
                                                        'amount'=> $cancel_res->Value,
                                                        'type'=> $cancel_res->Type,
                                                        'from_date'=> $cancel_res->From,
                                                       ];
                                                   $cancliation_policy_arr[] = $cancel_tiem;
                                               }
                                           }
                                           
                                           $room_qty_selected = 0;
                                           if(isset($travelenda_select_rooms)){
                                                foreach ($travelenda_select_rooms as $room_select_res){
                                                        $room_Obj = json_decode($room_select_res->room_rate_key);
                                                        if($room_Obj->rateKey == $room_res->booking_req_id){
                                                            $room_qty_selected = $room_select_res->rooms_qty;
                                                        }     
                                                }
                                            }
                                            
                                            $options_room[] = (Object)[
                                                    'booking_req_id' => $result_HotelPolicies->Body->OptionId,
                                                    'allotment' => $room_res->allotment,
                                                    'selected_qty' => $room_qty_selected,
                                                    'rate_class' => '',
                                                    'room_name' => $room_res->room_name,
                                                    'room_code' => $room_res->room_code,
                                                    'request_type' => $room_res->request_type,
                                                    'board_id' => $room_res->board_id,
                                                    'board_code' => $room_res->board_code,
                                                    'rooms_total_price' => $room_res->rooms_total_price,
                                                    'rooms_selling_price' =>  $room_res->rooms_total_price,
                                                    'rooms_qty' => $room_res->rooms_qty,
                                                    'adults' => $room_res->adults,
                                                    'childs' => $room_res->childs,
                                                    'cancliation_policy_arr' => $cancliation_policy_arr,
                                                    'rooms_list' => $room_res->rooms_list
                                                ];
                                        
                                     
                                    
                                    }
                                
                               
                                
                            }
                            
                            // print_r($option_list);
                        }
                      
                        $checkout_Object = (Object)[
                            'hotel_provider' => 'travelenda',
                            'on_request' => false,
                            'admin_markup' => $admin_travelenda_markup,
                            'customer_markup' => $customer_markup,
                            'admin_markup_type' => 'Percentage',
                            'customer_markup_type' => 'Percentage',
                            'hotel_id' => $selected_hotel->hotel_id,
                            'hotel_name' => $selected_hotel->hotel_name,
                            'checkIn' => $selected_hotel_details->check_in,
                            'checkOut' => $selected_hotel_details->check_out,
                            'stars_rating' => $selected_hotel->stars_rating,
                            'destinationCode' => $selected_hotel_details->hotel_address,
                            'destinationName' => $selected_hotel_details->hotel_address,
                            'zoneCode' => $selected_hotel_details->hotel_country,
                            'zoneName' => $selected_hotel_details->hotel_city,
                            'latitude' => $selected_hotel_details->latitude,
                            'longitude' => $selected_hotel_details->longitude,
                            'total_price' => $result_HotelPolicies->Body->TotalPrice,
                            'currency' => $result_HotelPolicies->Body->Currency,
                            'rooms_list' => $options_room
                          ];
                          
                          return response()->json([
                            'status' => 'success',
                            'hotels_data' => $checkout_Object,
                        ]);
                  }
                  
                  if(isset($result_HotelPolicies->Body->Error)){
                      if($result_HotelPolicies->Body->Error->ErrorId == '117'){
                          return response()->json([
                                'status' => 'error',
                                'type' => 'redirect',
                                'message' => 'Your Request Time out',
                            ]);
                      }
                      
                      
                  }
      
            }
        }
        // print_r($selected_hotel);
        // print_r($selected_hotel);
    }
    
    public function visa_checkout_save(Request $request){
        
                    $visa_all_data = json_decode($request->visa_data);
                    $price_data = json_decode($visa_all_data->visa_price_details);
                    
                    $hotel_invoice_data = DB::table('hotels_bookings')->where('invoice_no',$visa_all_data->invoice_id)->first();
                    
                    $visa_avail_data = DB::table('visa_Availability')->where('id',$price_data->visa_avail_id)->first();
                    
                    $lead_passenger = json_decode($visa_all_data->lead_passenger_details);
                    
                    $visa_booking_id = DB::table('visa_bookings')->insertGetId([
                            'invoice_no' => $visa_all_data->invoice_id,
                            'no_of_paxs' => $price_data->no_of_paxs_visa,
                            'hotel_booked' => true,
                            'departure_date' => $request->departure_date,
                            'lead_passenger_data' => $visa_all_data->lead_passenger_details,
                            'other_passenger_data' => $visa_all_data->other_passenger_details,
                            'visa_avail_id' => $price_data->visa_avail_id,
                            'visa_avail_data' => json_encode($visa_avail_data),
                            'visa_price_exchange' => $price_data->exchange_price_visa,
                            'visa_total_price_exchange' => $price_data->exchange_price_total_visa,
                            'exchange_currency' => $price_data->exchange_curreny_visa,
                            'visa_price' => $price_data->original_price_visa,
                            'visa_total_price' => $price_data->original_price_total_visa,
                            'currency' => $price_data->original_curreny_visa,
                            'booking_customer_id' => $hotel_invoice_data->booking_customer_id,
                            'lead_passenger' => $hotel_invoice_data->lead_passenger,
                            'customer_id' => $hotel_invoice_data->customer_id,
                            
                        ]);

                if($visa_booking_id){
                    
                    DB::table('hotels_bookings')->where('invoice_no',$visa_all_data->invoice_id)->update([
                            'visa_book_status' => 'on_request',
                            'visa_booking_id' => $visa_booking_id
                        ]);
                    
                    return response()->json(['status'=>'success',
                                         'Invoice_no'=>$visa_booking_id
                                            ]);
                }else{
                    DB::table('hotels_bookings')->where('invoice_no',$visa_all_data->invoice_id)->update([
                            'visa_book_status' => 'failed',
                        ]);
                    return response()->json(['status'=>'error',
                                         'Invoice_no'=>''
                                            ]);
                }
                
            
    }
    
    public function hotels_checkout_submit(Request $request){
        $hotel_request_data = json_decode($request->request_data);
        $hotel_checkout_select = json_decode($request->hotel_checkout_select);
        $customer_search_data = json_decode($request->customer_search_data);
        
        
        //print_r($hotel_request_data);
        // print_r($hotel_checkout_select);
        // print_r($customer_search_data);
        //die;
                $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                $booking_customer_id = "";
                $customer_exist = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$hotel_request_data->lead_email)->first();
                if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                        $booking_customer_id = $customer_exist->id;
                }else{
                   
                   if($hotel_request_data->lead_title == "Mr"){
                       $gender = 'male';
                   }else{
                        $gender = 'female';
                   }
                    
                    $password = Hash::make('admin123');
                    if(!isset($request->booking_type) || $request->booking_type == 'b2c')
                    {
                    $customer_detail                    = new booking_customers();
                    $customer_detail->name              = $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name;
                    $customer_detail->opening_balance   = 0;
                    $customer_detail->balance           = 0;
                    $customer_detail->email             = $hotel_request_data->lead_email;
                    $customer_detail->password             = $password;
                    $customer_detail->phone             = $hotel_request_data->lead_phone;
                    $customer_detail->gender            = $gender;
                    $customer_detail->country           = $hotel_request_data->lead_country;
    
                    $customer_detail->customer_id       = $userData->id;
                    
                    $result = $customer_detail->save();
                    
                    $booking_customer_id = $customer_detail->id;
                    }
                    
                }
                
         // ************************************************************
        // Custom Hotel Reservation
        // ************************************************************
        if($hotel_checkout_select->hotel_provider  == 'Custome_hotel'){
            if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
            //   dd($request->all());
            DB::beginTransaction();
        
            try {   
                    if(isset($hotel_checkout_select->rooms_list)){
                        
                        foreach($hotel_checkout_select->rooms_list as $room_res){
                            if($room_res->request_type == '0' || $room_res->request_type == ''){
                                $room_data = DB::table('rooms')->where('id',$room_res->booking_req_id)->first();
                                    if($room_data){
        
                                        // Update Room Data
                                        $rooms_qty = $room_res->selected_qty;
                                        $total_booked = $room_data->booked + $rooms_qty;
                
                                         DB::table('rooms_bookings_details')->insert([
                                             'room_id'=> $room_res->booking_req_id,
                                             'booking_from'=>'website',
                                             'quantity'=>$rooms_qty,
                                             'booking_id'=>$invoiceId,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$hotel_checkout_select->checkIn,
                                             'check_out'=>$hotel_checkout_select->checkOut,
                                         ]);
                                         
                               
                                        DB::table('rooms')->where('id',$room_res->booking_req_id)->update(['booked'=>$total_booked]);
                                        
                                        
                                        // Update Hotel Supplier Balance
                                
                                         $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
               
                                         if(isset($supplier_data)){
                                                // echo "Enter hre ";
                                                $total_price = $room_res->rooms_total_price;
                                               
                                                // echo "The supplier Balance is ".$supplier_data->balance;
                                                $supplier_balance = $supplier_data->balance;
                                                $supplier_payable_balance = $supplier_data->payable + $total_price;
                                                
                                                // update Agent Balance
                                                
                                                DB::table('hotel_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$total_price,
                                                    'balance'=>$supplier_balance,
                                                    'payable_balance'=>$supplier_payable_balance,
                                                    'room_id'=>$room_data->id,
                                                    'customer_id'=>$userData->id,
                                                    'date'=>date('Y-m-d'),
                                                    'website_booking_id'=>$invoiceId,
                                                    'available_from'=>$hotel_checkout_select->checkIn,
                                                    'available_to'=>$hotel_checkout_select->checkOut,
                                                    'room_quantity'=>$room_res->selected_qty,
                                                    ]);
                                                    
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                    'balance'=>$supplier_balance,
                                                    'payable'=>$supplier_payable_balance
                                                    ]);                      
                                            }
                                            
                                       
        
                                    }
                            }
                        }
                
                $room_book_status = '';
                if(isset($hotel_checkout_select->rooms_list[0])){
                    if($hotel_checkout_select->rooms_list[0]->request_type == '0' || empty($hotel_checkout_select->rooms_list[0]->request_type)){
                        $room_book_status = 'Confirmed';
                    }else{
                        $room_book_status = 'Room On Request';
                    }
                }
                //  dd($room_book_status);
                 // Booking Save Working
                                

                                      $rooms_details_arr = [];
                                      if(isset($hotel_checkout_select->rooms_list)){
                                          foreach($hotel_checkout_select->rooms_list as $room_res){
                                              
                                                     // Room Rates Arr
                                                      $room_rate_arr = [];
                    
                                                                // Rooms Cancilation Policy
                                                               $cancliation_policy_arr = $room_res->cancliation_policy_arr;
                                                          
                                                              
                                                              $room_rate_arr[] = (Object)[
                                                                    'rateClass' => '',
                                                                    'net' => $room_res->rooms_total_price,
                                                                    'rateComments' => '',
                                                                    'room_board' => $room_res->board_id,
                                                                    'room_qty' => $room_res->selected_qty,
                                                                    'adults' => $room_res->adults,
                                                                    'children' => $room_res->childs,
                                                                    'cancellation_policy' => $cancliation_policy_arr,
                                                                    'exchange_cancellation_policy' => $hotel_request_data->cancellation_details ?? '',
                                                                  ];
                                                          
                                                      
                                              
                                                      $rooms_details_arr[] = (Object)[
                                                                    'room_stutus' => $room_book_status,
                                                                    'room_code' => $room_res->booking_req_id,
                                                                    'room_name' => $room_res->room_name,
                                                                    'room_paxes' => [],
                                                                    'adults' => $room_res->adults,
                                                                    'childs' => $room_res->childs,
                                                                    'room_rates' => $room_rate_arr,
                                                                  ];
                                                
                                             
                                          }
                                          
                                      }
                                      
                                      $hotel_checkout_request_d = json_decode($request->hotel_checkout_select);
                                      
                                        $hotel_booking_conf_res = (Object)[
                                                'provider' => 'Custome_hotel',
                                                'reference_no' => $invoiceId,
                                                'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                                'total_price' => $hotel_checkout_select->total_price,
                                                'hotel_currency' => $hotel_checkout_select->currency,
                                                'clientReference' => '',
                                                'creationDate' => date('Y-m-d'),
                                                'status' => $room_book_status,
                                                'lead_passenger' => $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name,
                                                'hotel_details' =>(Object)[
                                                        'checkIn' => $hotel_checkout_select->checkIn,
                                                        'checkOut' => $hotel_checkout_select->checkOut,
                                                        'hotel_code' => $hotel_checkout_select->hotel_id,
                                                        'hotel_name' => $hotel_checkout_select->hotel_name,
                                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                                        'destinationCode' => $hotel_checkout_select->destinationCode,
                                                        'destinationName' => $hotel_checkout_select->destinationName,
                                                        'latitude' => $hotel_checkout_select->latitude,
                                                        'longitude' => $hotel_checkout_select->longitude,
                                                        'rooms' => $rooms_details_arr
                                                    ]
                                            ];
                                            
                                            $invoiceId =  $invoiceId;
                                            
                                            $customer_id = '';
                                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                                            if($userData){
                                                $customer_id = $userData->id;
                                            }
                                            
                                               $lead_passenger_object = (Object)[
                                                    'lead_title' =>$hotel_request_data->lead_title,
                                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                                    'lead_email' =>$hotel_request_data->lead_email,
                                                    'lead_country' => $hotel_request_data->lead_country, 
                                                ];
                                                
                                                $others_adults = [];
                                                
                                                if(isset($hotel_request_data->other_title)){
                                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                                        $others_adults[] = (Object)[
                                                                'title' => $other_res,
                                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                                            ];
                                                    }
                                                }
                                                
                                                $childs = [];
                                                if(isset($hotel_request_data->child_title)){
                                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                                        $childs[] = (Object)[
                                                                'title' => $other_res,
                                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                                            ];
                                                    }
                                                }
                                            
                                            if(isset($request->transfer_all_data) && $request->transfer_all_data !== NULL){
                                                $result = TransfersController::transfer_checkout_submit_with_service($request->transfer_all_data,$invoiceId,$booking_customer_id);
                                            }
                                            
                                            $result = DB::table('hotels_bookings')->insert([
                                                    'invoice_no' => $invoiceId,
                                                    'booking_customer_id' => $booking_customer_id,
                                                    'provider' => $hotel_booking_conf_res->provider,
                                                    'exchange_currency' => $request->exchange_currency,
                                                    'exchange_price' => $request->exchange_price,
                                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                                    'base_currency'=>$request->base_currency,
                                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                                    'selected_currency'=>$request->selected_currency,
                                                    'GBP_currency'=>$request->admin_exchange_currency,
                                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                                    'creationDate' =>  date('Y-m-d'),
                                                    'status' => $room_book_status,
                                                    'lead_passenger' => $hotel_booking_conf_res->lead_passenger,
                                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                                    'other_adults_data' => json_encode($others_adults),
                                                    'childs_data' => json_encode($childs),
                                                    'total_adults' => $customer_search_data->adult_searching,
                                                    'total_childs' => $customer_search_data->child_searching,
                                                    'total_rooms' => $customer_search_data->room_searching,
                                                    'reservation_request' => json_encode($request->all()),
                                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                                    'actual_reservation_response' => '',
                                                    'commision_charges' => $request->commision_charges ?? '',
                                                    
                                                    'customer_id' => $customer_id,
                                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id,'b2b_agent_id'=>$request->b2b_agent_id
                                                    
                                                ]);
                                                    
                                                    
                                                if($room_book_status == 'Confirmed'){
                                                    
                                                    //added code by jamshaid cheena 08-06-2023
                                                    $client_markup=$request->client_markup;
                                                    $admin_markup=$request->admin_markup;
                                                    $client_markup_type=$request->client_markup_type;
                                                    $admin_markup_type=$request->admin_markup_type;
                                                    $payable_price=$request->admin_commission_amount_orignial;
                                                    $client_commission_amount=$request->client_commission_amount;
                                                    
                                                    $total_markup_price=$request->total_markup_price;
                                                    $currency=$request->currency;
                                                    $exchange_client_commission_amount=$request->exchange_client_commission_amount;                                         
                                                    $exchange_payable_price=$request->exchange_admin_commission_amount;                                          
                                                    $exchange_admin_commission_amount=$request->exchange_admin_commission_amount; 
                                                    $exchange_total_markup_price=$request->exchange_total_markup_price; 
                                                    $exchange_currency=$request->exchange_currency; 
                                                    $exchange_rate=$request->exchange_rate; 
                                        
                                                    $admin_exchange_amount=$request->admin_commission_amount;
                                                    $admin_commission_amount=$request->admin_commission_amount;
                                                    $admin_exchange_currency=$request->admin_exchange_currency;
                                                    $admin_exchange_rate=$request->admin_exchange_rate;
                                                    $admin_exchange_total_markup_price=$request->admin_exchange_total_markup_price;
                                        
                                        
                                                    $price=$request->exchange_price;
                                                    $p_price=json_decode($price);
                        
                
                                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                                     //print_r($price_with_out_commission);die();
                                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                                        
                                                        'token'=>$request->token,
                                                        'invoice_no'=>$invoiceId,
                                                        'received_amount'=>$exchange_payable_price,
                                                        'balance_amount'=>$big_exchange_payable_price,
                                                        'type'=>'hotel_booking'
                                                        ]);
                                                    
                                                    
                                                    
                                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                                        
                                                        'token'=>$request->token,
                                                        'invoice_no'=>$invoiceId,
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
                                                        
                                                        'payment_transction_id'=> $invoiceId,
                                                        'provider'=> 'Custome_hotel',
                                                        'payment_remaining_amount'=>$add_price,
                                                        
                                                        
                                                        ]);
                                                        
                                                        
                                                        
                                                   //ended code by jamshaid cheena 08-06-2023
                                                   
                                                   // Added Balance To Customer Ledger 
                                                        $booking_customer_data = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                                                        if($booking_customer_data){
                                                            $customer_balance = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                                                        
                                                            DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                                    'balance' => $customer_balance
                                                                ]);
                                                                
                                                            DB::table('customer_ledger')->insert([
                                                                    'received' => $request->admin_exchange_total_markup_price,
                                                                    'balance' => $customer_balance,
                                                                    'booking_customer' => $booking_customer_id,
                                                                    'hotel_invoice_no' => $invoiceId,
                                                                    'date' => date('Y-m-d'),
                                                                    'customer_id' => $userData->id
                                                                ]);
                                                                
                                                            if($request->slc_pyment_method == 'slc_stripe'){
                                                                
                                                                $customer_balance = $customer_balance - $request->admin_exchange_total_markup_price;
                                                                DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                                    'balance' => $customer_balance
                                                                ]);
                                                                
                                                                DB::table('customer_ledger')->insert([
                                                                        'payment' => $request->admin_exchange_total_markup_price,
                                                                        'balance' => $customer_balance,
                                                                        'booking_customer' => $booking_customer_id,
                                                                        'hotel_invoice_no' => $invoiceId,
                                                                        'payment_method' => 'Stripe',
                                                                        'date' => date('Y-m-d'),
                                                                        'customer_id' => $userData->id
                                                                    ]);
                                                            }
                                                        }
                                                        
                                                }
            }
            
                     DB::commit();
                     
                    $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                     
                    return response()->json([
                        'status' => 'success',
                        'Invoice_id' => $invoiceId,
                        'Invoice_data' => $booking_data
                    ]);
                
            
             } catch (Throwable $e) {
                 DB::rollback();
                echo $e;
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
            
        }
        
        
        // ************************************************************
        // Custom Hotel Provider Reservation
        // ************************************************************
        
        // dd($hotel_checkout_select);
        if($hotel_checkout_select->hotel_provider  == 'custom_hotel_provider'){
            //   dd($request->all());
                if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
            //   dd($request->all());
            DB::beginTransaction();
        
            try {   
                    if(isset($hotel_checkout_select->rooms_list)){
                        
                        foreach($hotel_checkout_select->rooms_list as $room_res){
                                $room_data = DB::table('rooms')->where('id',$room_res->booking_req_id)->first();
                                    if($room_data){
        
                                        // Update Room Data
                                        $rooms_qty = $room_res->rooms_qty;
                                        $total_booked = $room_data->booked + $rooms_qty;
                
                                         DB::table('rooms_bookings_details')->insert([
                                             'room_id'=> $room_res->booking_req_id,
                                             'booking_from'=>'website',
                                             'quantity'=>$rooms_qty,
                                             'booking_id'=>$invoiceId,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$hotel_checkout_select->checkIn,
                                             'check_out'=>$hotel_checkout_select->checkOut,
                                         ]);
                                         
                               
                                        DB::table('rooms')->where('id',$room_res->booking_req_id)->update(['booked'=>$total_booked]);
                                        
                                        
                                        // Update Hotel Supplier Balance
                                
                                         $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
               
                                         if(isset($supplier_data)){
                                                // echo "Enter hre ";
                                                $total_price = $room_res->rooms_total_price;
                                                $provider_markup_data    = DB::table('become_provider_markup')->where('customer_id',$room_data->owner_id)->where('status','1')->first();
                                                $provider_markup = 0;
                                                if(isset($provider_markup_data)){
                                    
                                                    if($provider_markup_data->markup =='Percentage'){
                                                        $provider_markup = ($total_price * $provider_markup_data->markup_value) / 100;
                                                    }else{
                                                        $provider_markup = $provider_markup_data->markup_value;
                                                    }
                                                }
                                
                                                $total_price = $total_price - $provider_markup;
                                                // echo "The supplier Balance is ".$supplier_data->balance;
                                                $supplier_balance = $supplier_data->balance;
                                                $supplier_payable_balance = $supplier_data->payable + $total_price;
                                                
                                                // update Agent Balance
                                                
                                                DB::table('hotel_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$total_price,
                                                    'balance'=>$supplier_balance,
                                                    'payable_balance'=>$supplier_payable_balance,
                                                    'room_id'=>$room_data->id,
                                                    'customer_id'=>$userData->id,
                                                    'date'=>date('Y-m-d'),
                                                    'website_booking_id'=>$invoiceId,
                                                    'available_from'=>$hotel_checkout_select->checkIn,
                                                    'available_to'=>$hotel_checkout_select->checkOut,
                                                    'room_quantity'=>$room_res->rooms_qty,
                                                    ]);
                                                    
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                    'balance'=>$supplier_balance,
                                                    'payable'=>$supplier_payable_balance
                                                    ]);                      
                                            }
                                            
                                       
        
                                    }
                            
                        }
                
                $room_book_status = 'Confirmed';
                 
                 // Booking Save Working
                                

                                      $rooms_details_arr = [];
                                      if(isset($hotel_checkout_select->rooms_list)){
                                          foreach($hotel_checkout_select->rooms_list as $room_res){
                                              
                                                     // Room Rates Arr
                                                      $room_rate_arr = [];
                    
                                                                // Rooms Cancilation Policy
                                                               $cancliation_policy_arr = $room_res->cancliation_policy_arr;
                                                          
                                                              
                                                              $room_rate_arr[] = (Object)[
                                                                    'rateClass' => '',
                                                                    'net' => $room_res->rooms_total_price,
                                                                    'rateComments' => '',
                                                                    'room_board' => $room_res->board_id,
                                                                    'room_qty' => 1,
                                                                    'adults' => $room_res->adults,
                                                                    'children' => $room_res->childs,
                                                                    'cancellation_policy' => $cancliation_policy_arr,
                                                                  ];
                                                          
                                                      
                                              
                                                      $rooms_details_arr[] = (Object)[
                                                                    'room_stutus' => $room_book_status,
                                                                    'room_code' => $room_res->booking_req_id,
                                                                    'room_name' => $room_res->room_name,
                                                                    'room_paxes' => [],
                                                                    'adults' => $room_res->adults,
                                                                    'childs' => $room_res->childs,
                                                                    'room_rates' => $room_rate_arr,
                                                                  ];
                                                
                                             
                                          }
                                          
                                      }
                                      
                                      $hotel_checkout_request_d = json_decode($request->hotel_checkout_select);
                                      
                                        $hotel_booking_conf_res = (Object)[
                                                'provider' => 'custom_hotel_provider',
                                                'custom_hotel_provider' => $hotel_checkout_request_d->custom_hotel_provider,
                                                'reference_no' => $invoiceId,
                                                'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                                'total_price' => $hotel_checkout_select->total_price,
                                                'hotel_currency' => $hotel_checkout_select->currency,
                                                'clientReference' => '',
                                                'creationDate' => date('Y-m-d'),
                                                'status' => $room_book_status,
                                                'lead_passenger' => $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name,
                                                'hotel_details' =>(Object)[
                                                        'checkIn' => $hotel_checkout_select->checkIn,
                                                        'checkOut' => $hotel_checkout_select->checkOut,
                                                        'hotel_code' => $hotel_checkout_select->hotel_id,
                                                        'hotel_name' => $hotel_checkout_select->hotel_name,
                                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                                        'destinationCode' => $hotel_checkout_select->destinationCode,
                                                        'destinationName' => $hotel_checkout_select->destinationName,
                                                        'latitude' => $hotel_checkout_select->latitude,
                                                        'longitude' => $hotel_checkout_select->longitude,
                                                        'rooms' => $rooms_details_arr
                                                    ]
                                            ];
                                            
                                            $invoiceId =  $invoiceId;
                                            
                                            $customer_id = '';
                                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                                            if($userData){
                                                $customer_id = $userData->id;
                                            }
                                            
                                               $lead_passenger_object = (Object)[
                                                    'lead_title' =>$hotel_request_data->lead_title,
                                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                                    'lead_email' =>$hotel_request_data->lead_email,
                                                    'lead_country' => $hotel_request_data->lead_country, 
                                                ];
                                                
                                                $others_adults = [];
                                                
                                                if(isset($hotel_request_data->other_title)){
                                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                                        $others_adults[] = (Object)[
                                                                'title' => $other_res,
                                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                                            ];
                                                    }
                                                }
                                                
                                                $childs = [];
                                                if(isset($hotel_request_data->child_title)){
                                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                                        $childs[] = (Object)[
                                                                'title' => $other_res,
                                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                                            ];
                                                    }
                                                }
                                            
                                            if(isset($request->transfer_all_data) && $request->transfer_all_data !== NULL){
                                                $result = TransfersController::transfer_checkout_submit_with_service($request->transfer_all_data,$invoiceId,$booking_customer_id);
                                            }
                                            
                                            $result = DB::table('hotels_bookings')->insert([
                                                    'invoice_no' => $invoiceId,
                                                    'booking_customer_id' => $booking_customer_id,
                                                    'provider' => $hotel_booking_conf_res->provider,
                                                    'exchange_currency' => $request->exchange_currency,
                                                    'exchange_price' => $request->exchange_price,
                                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                                    'base_currency'=>$request->base_currency,
                                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                                    'selected_currency'=>$request->selected_currency,
                                                    'GBP_currency'=>$request->admin_exchange_currency,
                                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                                    'creationDate' =>  date('Y-m-d'),
                                                    'status' => $room_book_status,
                                                    'lead_passenger' => $hotel_booking_conf_res->lead_passenger,
                                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                                    'other_adults_data' => json_encode($others_adults),
                                                    'childs_data' => json_encode($childs),
                                                    'total_adults' => $customer_search_data->adult_searching,
                                                    'total_childs' => $customer_search_data->child_searching,
                                                    'total_rooms' => $customer_search_data->room_searching,
                                                    'reservation_request' => json_encode($request->all()),
                                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                                    'actual_reservation_response' => '',
                                                    'commision_charges' => $request->commision_charges ?? '',
                                                    'customer_id' => $customer_id,
                                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
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
                                                    $exchange_admin_commission_amount=$request->admin_commission_amount; 
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
                        
                
                                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                                     //print_r($price_with_out_commission);die();
                                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                                        
                                                        'token'=>$request->token,
                                                        'invoice_no'=>$invoiceId,
                                                        'received_amount'=>$exchange_payable_price,
                                                        'balance_amount'=>$big_exchange_payable_price,
                                                        'type'=>'hotel_booking'
                                                        ]);
                                                    
                                                    
                                                    
                                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                                        
                                                        'token'=>$request->token,
                                                        'invoice_no'=>$invoiceId,
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
                                                        
                                                        'payment_transction_id'=> $invoiceId,
                                                        'provider'=> $hotel_checkout_request_d->custom_hotel_provider,
                                                        'payment_remaining_amount'=>$add_price,
                                                        
                                                        
                                                        ]);
                                                   //ended code by jamshaid cheena 08-06-2023
                                                   
                                                //   dd($request->all());
                                                   $provider_data = DB::table('custom_hotel_provider')->where('provider_name',$hotel_checkout_request_d->custom_hotel_provider)->first();
                                                   if($provider_data){
                                                       $total_booking_price = $request->admin_exchange_total_markup_price;
                                                       $hotel_provider_receviable = (float)$request->admin_exchange_total_markup_price - ((float)$request->admin_commission_amount + (float)$request->exchange_client_commission_amount);
                                                       
                                                       $provider_receviable_balance = $provider_data->balance + $hotel_provider_receviable;
                                                       DB::table('custom_hotel_provider')->where('provider_name',$hotel_checkout_request_d->custom_hotel_provider)->update([
                                                           'balance' => $provider_receviable_balance
                                                           ]);
                                                           
                                                        $provider_markup_data    = DB::table('become_provider_markup')->where('customer_id',$provider_data->customer_id)->where('status','1')->first();
                                                           
                                                           
                                                        DB::table('cust_hotel_provider_ledger')->insert([
                                                                'payment' => $hotel_provider_receviable,
                                                                'balance' => $provider_receviable_balance,
                                                                'invoice_no' => $invoiceId,
                                                                'total_price' => $total_booking_price,
                                                                'provider_id' => $provider_data->customer_id,
                                                                'provider_name' => $provider_data->provider_name,
                                                                'provider_id' => $provider_data->customer_id,
                                                                'commission' => $provider_markup_data->markup,
                                                                'commission_amount' => $provider_markup_data->markup_value,
                                                            ]);
                                                   }
                                                   
                                                   // Added Balance To Customer Ledger 
                                                        $booking_customer_data = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                                                        if($booking_customer_data){
                                                            $customer_balance = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                                                        
                                                            DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                                    'balance' => $customer_balance
                                                                ]);
                                                                
                                                            DB::table('customer_ledger')->insert([
                                                                    'received' => $request->admin_exchange_total_markup_price,
                                                                    'balance' => $customer_balance,
                                                                    'booking_customer' => $booking_customer_id,
                                                                    'hotel_invoice_no' => $invoiceId,
                                                                    'date' => date('Y-m-d'),
                                                                    'customer_id' => $userData->id
                                                                ]);
                                                                
                                                            if($request->slc_pyment_method == 'slc_stripe'){
                                                                
                                                                $customer_balance = $customer_balance - $request->admin_exchange_total_markup_price;
                                                                DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                                    'balance' => $customer_balance
                                                                ]);
                                                                
                                                                DB::table('customer_ledger')->insert([
                                                                        'payment' => $request->admin_exchange_total_markup_price,
                                                                        'balance' => $customer_balance,
                                                                        'booking_customer' => $booking_customer_id,
                                                                        'hotel_invoice_no' => $invoiceId,
                                                                        'payment_method' => 'Stripe',
                                                                        'date' => date('Y-m-d'),
                                                                        'customer_id' => $userData->id
                                                                    ]);
                                                            }
                                                        }
                                                
            }
            
                     DB::commit();
                     
                    $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                     
                    return response()->json([
                        'status' => 'success',
                        'Invoice_id' => $invoiceId,
                        'Invoice_data' => $booking_data
                    ]);
                
            
             } catch (Throwable $e) {
                 DB::rollback();
                echo $e;
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
            
        }
                
        // ************************************************************
        // Hotels Bed Reservation
        // ************************************************************
        if($hotel_checkout_select->hotel_provider  == 'hotel_beds'){
            
            $current_date = date('Y-m-d');
            $cancilation_date = date('Y-m-d',strtotime($hotel_checkout_select->rooms_list[0]->cancliation_policy_arr[0]->from_date ?? ''));
           
              $cancilation_date = Carbon::parse($cancilation_date);
             $current1 = Carbon::parse($current_date);
            if($cancilation_date > $current1){
               // Refundable Booking
               
                  function confirmbooking($hotel_request_data,$hotel_checkout_select){
                  $url = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi.php";
                  $data = array('case' => 'multi_rooms_confirmbooking', 
                                  'hotel_request_data' => json_encode($hotel_request_data),
                                  'hotel_checkout_select'=>json_encode($hotel_checkout_select));
                  Session::put('hotelbeds_booking_rq',$data);
                   $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $url);
                  curl_setopt($ch, CURLOPT_POST, true);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                  
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $responseData = curl_exec($ch);
                //   echo $responseData;die();
                  if (curl_errno($ch)) {
                      return curl_error($ch);
                  }
                  curl_close($ch);
                  return $responseData;
              }
          
                  $responseData3 = confirmbooking($hotel_request_data,$hotel_checkout_select);
                //   echo $responseData3;
                //   die;
                  $responseData3 = json_decode($responseData3);
                  $hotel_request_send = json_decode($responseData3->request);
                  $result_booking_rs = json_decode($responseData3->response);
                  
                //   $result_booking_rs = json_decode($hotel_response);
                //   dd($result_booking_rs);
                  //print_r($result_booking_rs);die();
               
                  if(isset($result_booking_rs->booking)){
                      
                      $rooms_details_arr = [];
                      if(isset($result_booking_rs->booking->hotel->rooms)){
                          foreach($result_booking_rs->booking->hotel->rooms as $room_res){
                              
                              // Paxes Arr
                              $paxes_arr = [];
                              if(isset($room_res->paxes)){
                                  foreach($room_res->paxes as $pax_res){
                                      $type = '';
                                      if($pax_res->type == 'AD'){
                                          $type = 'Adult';
                                      }
                                      
                                      if($pax_res->type == 'CH'){
                                          $type = 'Child';
                                      }
                                      
                                      $paxes_arr[] = [
                                            'type' => $type,
                                            'name' => $pax_res->name." ".$pax_res->surname,
                                          ];
                                  }
                              }
                              
                              // Room Rates Arr
                              $room_rate_arr = [];
                              if(isset($room_res->rates)){
                                  foreach($room_res->rates as $rate_res){
                                      
                                        // Rooms Cancilation Policy
                                       $cancliation_policy_arr = [];
                                       if(isset($rate_res->cancellationPolicies)){
                                           foreach($rate_res->cancellationPolicies as $cancel_res){
                                               $cancel_tiem = (Object)[
                                                    'amount'=> $cancel_res->amount,
                                                    'from_date'=> $cancel_res->from,
                                                   ];
                                               $cancliation_policy_arr[] = $cancel_tiem;
                                           }
                                       }
                                      
                                      $room_rate_arr[] = (Object)[
                                            'rateClass' => $rate_res->rateClass,
                                            'net' => $rate_res->net,
                                            'rateComments' => $rate_res->rateComments ?? '',
                                            'room_board' => $rate_res->boardName,
                                            'room_qty' => $rate_res->rooms,
                                            'adults' => $rate_res->adults,
                                            'children' => $rate_res->children,
                                            'cancellation_policy' => $cancliation_policy_arr,
                                          ];
                                  }
                              }
                              
                              $rooms_details_arr[] = (Object)[
                                            'room_stutus' => $room_res->status,
                                            'room_code' => $room_res->code,
                                            'room_name' => $room_res->name,
                                            'room_paxes' => $paxes_arr,
                                            'room_rates' => $room_rate_arr,
                                          ];
                          }
                          
                      }
                      
                      $hotel_checkout_request_d = json_decode($request->hotel_checkout_select);
                        $hotel_booking_conf_res = (Object)[
                                'provider' => 'hotel_beds',
                                'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                'reference_no' => $result_booking_rs->booking->reference,
                                'total_price' => $result_booking_rs->booking->totalNet,
                                'hotel_currency' => $result_booking_rs->booking->currency,
                                'clientReference' => $result_booking_rs->booking->clientReference,
                                'creationDate' => $result_booking_rs->booking->creationDate,
                                'status' => $result_booking_rs->booking->status,
                                'lead_passenger' => $result_booking_rs->booking->holder->name." ".$result_booking_rs->booking->holder->surname,
                                'status' => $result_booking_rs->booking->status,
                                'hotel_details' =>(Object)[
                                        'checkIn' => $result_booking_rs->booking->hotel->checkIn,
                                        'checkOut' => $result_booking_rs->booking->hotel->checkOut,
                                        'hotel_code' => $result_booking_rs->booking->hotel->code,
                                        'hotel_name' => $result_booking_rs->booking->hotel->name,
                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                        'destinationCode' => $result_booking_rs->booking->hotel->destinationCode,
                                        'destinationName' => $result_booking_rs->booking->hotel->destinationName,
                                        'latitude' => $result_booking_rs->booking->hotel->latitude,
                                        'longitude' => $result_booking_rs->booking->hotel->longitude,
                                        'rooms' => $rooms_details_arr
                                    ]
                            ];
                            
                             if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                            
                            $customer_id = '';
                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                            if($userData){
                                $customer_id = $userData->id;
                            }
                            
                               $lead_passenger_object = (Object)[
                                    'lead_title' =>$hotel_request_data->lead_title,
                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                    'lead_email' =>$hotel_request_data->lead_email,
                                    'lead_country' => $hotel_request_data->lead_country, 
                                ];
                                
                                $others_adults = [];
                                
                                if(isset($hotel_request_data->other_title)){
                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                        $others_adults[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                            ];
                                    }
                                }
                                
                                $childs = [];
                                if(isset($hotel_request_data->child_title)){
                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                        $childs[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                            ];
                                    }
                                }
                            
                            // dd($hotel_booking_conf_res);
                             try {
                                //  dd($request->transfer_all_data);
                                 if(isset($request->transfer_all_data) && $request->transfer_all_data !== NULL){
                                    $result = TransfersController::transfer_checkout_submit_with_service($request->transfer_all_data,$invoiceId,$booking_customer_id);
                                 }
                            
                            $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no' => $invoiceId,
                                    'provider' => $hotel_booking_conf_res->provider,
                                    'booking_customer_id' => $booking_customer_id,
                                    'exchange_currency' => $request->exchange_currency_customer,
                                    'exchange_price' => $request->exchange_price,
                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                    'base_currency'=>$request->base_currency,
                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                    'selected_currency'=>$request->selected_currency,
                                    'GBP_currency'=>$request->admin_exchange_currency,
                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                    'creationDate' => $hotel_booking_conf_res->creationDate,
                                    'status' => $hotel_booking_conf_res->status,
                                    'lead_passenger' => $hotel_booking_conf_res->lead_passenger,
                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                    'other_adults_data' => json_encode($others_adults),
                                    'childs_data' => json_encode($childs),
                                    'status' => $hotel_booking_conf_res->status,
                                    'total_adults' => $customer_search_data->adult_searching,
                                    'total_childs' => $customer_search_data->child_searching,
                                    'total_rooms' => $customer_search_data->room_searching,
                                    'reservation_request' => json_encode($hotel_request_send),
                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                     'commision_charges'=>$request->commision_charges ?? '',
                                    'customer_id' => $customer_id,
                                    'payment_details'=>$request->payment_details,
                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
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
                                    $exchange_admin_commission_amount=$request->admin_commission_amount; 
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
        

                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                     //print_r($price_with_out_commission);die();
                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
                                        'received_amount'=>$exchange_payable_price,
                                        'balance_amount'=>$big_exchange_payable_price,
                                        'type'=>'hotel_booking'
                                        ]);
                                    
                                    
                                    
                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
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
                                        
                                        'payment_transction_id'=> $invoiceId,
                                        'provider'=> 'hotelbeds',
                                        'payment_remaining_amount'=>$add_price,
                                        
                                        
                                        ]);
                                        
                                        
                                        
                                         $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                                        $credit_data = DB::table('credit_limits')->where('customer_id',$customer_get_data->id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
                                         $ramainAmount=$credit_data->remaining_amount - $request->creditAmount;
                                         
                                        $credit_limits = DB::table('credit_limits')->insert([
                                            'transection_id'=>$invoiceId,
                                            'customer_id'=>$customer_get_data->id,
                                                'amount'=>$request->creditAmount,
                                                'total_amount'=>$credit_data->total_amount,
                                                'remaining_amount'=>$ramainAmount,
                                                'currency'=>$credit_data->currency,
                                                'status'=>'1',
                                                'status_type'=>'approved',
                                            
                                            ]);
                                        
                                       $credit_limits = DB::table('credit_limit_transections')->insert([
                                        'invoice_no'=> $invoiceId,
                                        'customer_id'=>$customer_get_data->id,
                                        'transection_amount'=>$request->creditAmount,
                                        'remaining_amount'=>$ramainAmount,
                                        'type'=>'booked',
                                        ]);
                                        
                                        
                                        
                                   //ended code by jamshaid cheena 08-06-2023
                                   
                                   // Added Balance To Customer Ledger 
                                    $booking_customer_data = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                                    if($booking_customer_data){
                                        $customer_balance = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                                    
                                        DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                        DB::table('customer_ledger')->insert([
                                                'received' => $request->admin_exchange_total_markup_price,
                                                'balance' => $customer_balance,
                                                'booking_customer' => $booking_customer_id,
                                                'hotel_invoice_no' => $invoiceId,
                                                'date' => date('Y-m-d'),
                                                'customer_id' => $userData->id,
                                            ]);
                                            
                                        if($request->slc_pyment_method == 'slc_stripe'){
                                            
                                            $customer_balance = $customer_balance - $request->admin_exchange_total_markup_price;
                                            DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                            DB::table('customer_ledger')->insert([
                                                    'payment' => $request->admin_exchange_total_markup_price,
                                                    'balance' => $customer_balance,
                                                    'booking_customer' => $booking_customer_id,
                                                    'hotel_invoice_no' => $invoiceId,
                                                    'payment_method' => 'Stripe',
                                                    'date' => date('Y-m-d'),
                                                    'customer_id' => $userData->id
                                                ]);
                                        }
                                    }
                                                        
                                    $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                                    return response()->json([
                                        'status' => 'success',
                                        'Invoice_id' => $invoiceId,
                                        'Invoice_data' => $booking_data
                                    ]);
                                
                            
                             } catch (Throwable $e) {
                                 DB::rollback();
                                echo $e;
                                return response()->json(['message'=>'error','booking_id'=> '']);
                            }
                            
    
                  }
                  
                  else
                  {
                   
                   
                             if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                            
                            $customer_id = '';
                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                            if($userData){
                                $customer_id = $userData->id;
                            }
                            
                               $lead_passenger_object = (Object)[
                                    'lead_title' =>$hotel_request_data->lead_title,
                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                    'lead_email' =>$hotel_request_data->lead_email,
                                    'lead_country' => $hotel_request_data->lead_country, 
                                ];
                                
                                $others_adults = [];
                                
                                if(isset($hotel_request_data->other_title)){
                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                        $others_adults[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                            ];
                                    }
                                }
                                
                                $childs = [];
                                if(isset($hotel_request_data->child_title)){
                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                        $childs[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                            ];
                                    }
                                }
                                
                                if(isset($request->transfer_all_data) && $request->transfer_all_data !== NULL){
                                    $result = TransfersController::transfer_checkout_submit_with_service($request->transfer_all_data,$invoiceId,$booking_customer_id);
                                 }
                                
                                $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no' => $invoiceId,
                                    'provider' => '',
                                    'booking_customer_id' => $booking_customer_id,
                                    'exchange_currency' => $request->exchange_currency_customer,
                                    'exchange_price' => $request->exchange_price,
                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                    'base_currency'=>$request->base_currency,
                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                    'selected_currency'=>$request->selected_currency,
                                    'GBP_currency'=>$request->admin_exchange_currency,
                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                    'creationDate' => '',
                                    'status' => 'Failed',
                                    'lead_passenger' => '',
                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                    'other_adults_data' => json_encode($others_adults),
                                    'childs_data' => json_encode($childs),
                                    'status' => 'Failed',
                                    'total_adults' => $customer_search_data->adult_searching,
                                    'total_childs' => $customer_search_data->child_searching,
                                    'total_rooms' => $customer_search_data->room_searching,
                                    'reservation_request' => json_encode($hotel_request_send),
                                    'reservation_response' => $result_booking_rs->error->message,
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                     'commision_charges'=>$request->commision_charges ?? '',
                                    'customer_id' => $customer_id,
                                    'payment_details'=>$request->payment_details,
                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
                                ]);
                                
                   return response()->json([
                            'status' => 'error',
                            'message' => $result_booking_rs->error->message
                        ]);   
                  }
                //   if($result_booking_rs->error){
                //       return response()->json([
                //             'status' => 'error',
                //             'message' => $result_booking_rs->error->message
                //         ]);
                //   }
              
            }else{
               // Non Refundable Booking
               
               $slc_pyment_method=$request->slc_pyment_method;
               if($slc_pyment_method == 'slc_stripe')
               {
                   
                               
                  function confirmbooking($hotel_request_data,$hotel_checkout_select){
                  $url = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi.php";
                  $data = array('case' => 'multi_rooms_confirmbooking', 
                                  'hotel_request_data' => json_encode($hotel_request_data),
                                  'hotel_checkout_select'=>json_encode($hotel_checkout_select));
                  Session::put('hotelbeds_booking_rq',$data);
                   $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $url);
                  curl_setopt($ch, CURLOPT_POST, true);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                  
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $responseData = curl_exec($ch);
                //   echo $responseData;die();
                  if (curl_errno($ch)) {
                      return curl_error($ch);
                  }
                  curl_close($ch);
                  return $responseData;
              }
                //   dd('test');
                  $responseData3 = confirmbooking($hotel_request_data,$hotel_checkout_select);
                //   echo $responseData3;die;
                  $responseData3 = json_decode($responseData3);
                  $hotel_request_send = json_decode($responseData3->request);
                  $result_booking_rs = json_decode($responseData3->response);
                  
                //   $result_booking_rs = json_decode($hotel_response);
                //   dd($result_booking_rs);
                  //print_r($result_booking_rs);die();
               
                  if(isset($result_booking_rs->booking)){
                      
                      $rooms_details_arr = [];
                      if(isset($result_booking_rs->booking->hotel->rooms)){
                          foreach($result_booking_rs->booking->hotel->rooms as $room_res){
                              
                              // Paxes Arr
                              $paxes_arr = [];
                              if(isset($room_res->paxes)){
                                  foreach($room_res->paxes as $pax_res){
                                      $type = '';
                                      if($pax_res->type == 'AD'){
                                          $type = 'Adult';
                                      }
                                      
                                      if($pax_res->type == 'CH'){
                                          $type = 'Child';
                                      }
                                      
                                      $paxes_arr[] = [
                                            'type' => $type,
                                            'name' => $pax_res->name." ".$pax_res->surname,
                                          ];
                                  }
                              }
                              
                              // Room Rates Arr
                              $room_rate_arr = [];
                              if(isset($room_res->rates)){
                                  foreach($room_res->rates as $rate_res){
                                      
                                        // Rooms Cancilation Policy
                                       $cancliation_policy_arr = [];
                                       if(isset($rate_res->cancellationPolicies)){
                                           foreach($rate_res->cancellationPolicies as $cancel_res){
                                               $cancel_tiem = (Object)[
                                                    'amount'=> $cancel_res->amount,
                                                    'from_date'=> $cancel_res->from,
                                                   ];
                                               $cancliation_policy_arr[] = $cancel_tiem;
                                           }
                                       }
                                      
                                      $room_rate_arr[] = (Object)[
                                            'rateClass' => $rate_res->rateClass,
                                            'net' => $rate_res->net,
                                            'rateComments' => $rate_res->rateComments ?? '',
                                            'room_board' => $rate_res->boardName,
                                            'room_qty' => $rate_res->rooms,
                                            'adults' => $rate_res->adults,
                                            'children' => $rate_res->children,
                                            'cancellation_policy' => $cancliation_policy_arr,
                                          ];
                                  }
                              }
                              
                              $rooms_details_arr[] = (Object)[
                                            'room_stutus' => $room_res->status,
                                            'room_code' => $room_res->code,
                                            'room_name' => $room_res->name,
                                            'room_paxes' => $paxes_arr,
                                            'room_rates' => $room_rate_arr,
                                          ];
                          }
                          
                      }
                      
                      $hotel_checkout_request_d = json_decode($request->hotel_checkout_select);
                        $hotel_booking_conf_res = (Object)[
                                'provider' => 'hotel_beds',
                                'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                'reference_no' => $result_booking_rs->booking->reference,
                                'total_price' => $result_booking_rs->booking->totalNet,
                                'hotel_currency' => $result_booking_rs->booking->currency,
                                'clientReference' => $result_booking_rs->booking->clientReference,
                                'creationDate' => $result_booking_rs->booking->creationDate,
                                'status' => $result_booking_rs->booking->status,
                                'lead_passenger' => $result_booking_rs->booking->holder->name." ".$result_booking_rs->booking->holder->surname,
                                'status' => $result_booking_rs->booking->status,
                                'hotel_details' =>(Object)[
                                        'checkIn' => $result_booking_rs->booking->hotel->checkIn,
                                        'checkOut' => $result_booking_rs->booking->hotel->checkOut,
                                        'hotel_code' => $result_booking_rs->booking->hotel->code,
                                        'hotel_name' => $result_booking_rs->booking->hotel->name,
                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                        'destinationCode' => $result_booking_rs->booking->hotel->destinationCode,
                                        'destinationName' => $result_booking_rs->booking->hotel->destinationName,
                                        'latitude' => $result_booking_rs->booking->hotel->latitude,
                                        'longitude' => $result_booking_rs->booking->hotel->longitude,
                                        'rooms' => $rooms_details_arr
                                    ]
                            ];
                            
                             if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                            
                            $customer_id = '';
                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                            if($userData){
                                $customer_id = $userData->id;
                            }
                            
                               $lead_passenger_object = (Object)[
                                    'lead_title' =>$hotel_request_data->lead_title,
                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                    'lead_email' =>$hotel_request_data->lead_email,
                                    'lead_country' => $hotel_request_data->lead_country, 
                                ];
                                
                                $others_adults = [];
                                
                                if(isset($hotel_request_data->other_title)){
                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                        $others_adults[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                            ];
                                    }
                                }
                                
                                $childs = [];
                                if(isset($hotel_request_data->child_title)){
                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                        $childs[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                            ];
                                    }
                                }
                            
                            // dd($hotel_booking_conf_res);
                             try {
                                 if(isset($request->transfer_all_data) && $request->transfer_all_data !== NULL){
                                    $result = TransfersController::transfer_checkout_submit_with_service($request->transfer_all_data,$invoiceId,$booking_customer_id);
                                 }
                                 
                            $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no' => $invoiceId,
                                    'provider' => $hotel_booking_conf_res->provider,
                                    'booking_customer_id' => $booking_customer_id,
                                    'exchange_currency' => $request->exchange_currency_customer,
                                    'exchange_price' => $request->exchange_price,
                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                    'base_currency'=>$request->base_currency,
                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                    'selected_currency'=>$request->selected_currency,
                                    'GBP_currency'=>$request->admin_exchange_currency,
                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                    'creationDate' => $hotel_booking_conf_res->creationDate,
                                    'status' => $hotel_booking_conf_res->status,
                                    'lead_passenger' => $hotel_booking_conf_res->lead_passenger,
                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                    'other_adults_data' => json_encode($others_adults),
                                    'childs_data' => json_encode($childs),
                                    'status' => $hotel_booking_conf_res->status,
                                    'total_adults' => $customer_search_data->adult_searching,
                                    'total_childs' => $customer_search_data->child_searching,
                                    'total_rooms' => $customer_search_data->room_searching,
                                    'reservation_request' => json_encode($hotel_request_send),
                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                     'commision_charges'=>$request->commision_charges ?? '',
                                    'customer_id' => $customer_id,
                                    'payment_details'=>$request->payment_details,
                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
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
                                    $exchange_admin_commission_amount=$request->admin_commission_amount; 
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
        

                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                     //print_r($price_with_out_commission);die();
                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
                                        'received_amount'=>$exchange_payable_price,
                                        'balance_amount'=>$big_exchange_payable_price,
                                        'type'=>'hotel_booking'
                                        ]);
                                    
                                    
                                    
                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
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
                                        
                                        'payment_transction_id'=> $invoiceId,
                                        'provider'=> 'hotelbeds',
                                        'payment_remaining_amount'=>$add_price,
                                        
                                        
                                        ]);
                                   //ended code by jamshaid cheena 08-06-2023
                                   
                                   // Added Balance To Customer Ledger 
                                    $booking_customer_data = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                                    if($booking_customer_data){
                                        $customer_balance = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                                    
                                        DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                        DB::table('customer_ledger')->insert([
                                                'received' => $request->admin_exchange_total_markup_price,
                                                'balance' => $customer_balance,
                                                'booking_customer' => $booking_customer_id,
                                                'hotel_invoice_no' => $invoiceId,
                                                'date' => date('Y-m-d'),
                                                'customer_id' => $userData->id
                                            ]);
                                            
                                        if($request->slc_pyment_method == 'slc_stripe'){
                                            
                                            $customer_balance = $customer_balance - $request->admin_exchange_total_markup_price;
                                            DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                            DB::table('customer_ledger')->insert([
                                                    'payment' => $request->admin_exchange_total_markup_price,
                                                    'balance' => $customer_balance,
                                                    'booking_customer' => $booking_customer_id,
                                                    'hotel_invoice_no' => $invoiceId,
                                                    'payment_method' => 'Stripe',
                                                    'date' => date('Y-m-d'),
                                                    'customer_id' => $userData->id
                                                ]);
                                        }
                                    }
                                    
                                    $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                                if($result){
                                    return response()->json([
                                        'status' => 'success',
                                        'Invoice_id' => $invoiceId,
                                        'Invoice_data' => $booking_data
                                    ]);
                                }
                            
                             } catch (Throwable $e) {
                                 DB::rollback();
                                echo $e;
                                return response()->json(['message'=>'error','booking_id'=> '']);
                            }
                            
    
                  }
                  
                  else
                  {
                   
                   
                            if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                            
                            $customer_id = '';
                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                            if($userData){
                                $customer_id = $userData->id;
                            }
                            
                               $lead_passenger_object = (Object)[
                                    'lead_title' =>$hotel_request_data->lead_title,
                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                    'lead_email' =>$hotel_request_data->lead_email,
                                    'lead_country' => $hotel_request_data->lead_country, 
                                ];
                                
                                $others_adults = [];
                                
                                if(isset($hotel_request_data->other_title)){
                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                        $others_adults[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                            ];
                                    }
                                }
                                
                                $childs = [];
                                if(isset($hotel_request_data->child_title)){
                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                        $childs[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                            ];
                                    }
                                }
                                
                                if(isset($request->transfer_all_data) && $request->transfer_all_data !== NULL){
                                    $result = TransfersController::transfer_checkout_submit_with_service($request->transfer_all_data,$invoiceId,$booking_customer_id);
                                }
                                
                                $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no' => $invoiceId,
                                    'provider' => '',
                                    'booking_customer_id' => $booking_customer_id,
                                    'exchange_currency' => $request->exchange_currency_customer,
                                    'exchange_price' => $request->exchange_price,
                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                    'base_currency'=>$request->base_currency,
                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                    'selected_currency'=>$request->selected_currency,
                                    'GBP_currency'=>$request->admin_exchange_currency,
                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                    'creationDate' => '',
                                    'status' => 'Failed',
                                    'lead_passenger' => '',
                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                    'other_adults_data' => json_encode($others_adults),
                                    'childs_data' => json_encode($childs),
                                    'status' => 'Failed',
                                    'total_adults' => $customer_search_data->adult_searching,
                                    'total_childs' => $customer_search_data->child_searching,
                                    'total_rooms' => $customer_search_data->room_searching,
                                    'reservation_request' => json_encode($hotel_request_send),
                                    'reservation_response' => $result_booking_rs->error->message,
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                     'commision_charges'=>$request->commision_charges ?? '',
                                    'customer_id' => $customer_id,
                                    'payment_details'=>$request->payment_details,
                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
                                ]);
                                
                   return response()->json([
                            'status' => 'error',
                            'message' => $result_booking_rs->error->message
                        ]);   
                  }   
                   
               }
               else
               {
                         if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                        
                        $customer_id = '';
                        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                        if($userData){
                            $customer_id = $userData->id;
                        }
                        
                           $lead_passenger_object = (Object)[
                                'lead_title' =>$hotel_request_data->lead_title,
                                'lead_first_name' =>$hotel_request_data->lead_first_name,
                                'lead_last_name' =>$hotel_request_data->lead_last_name,
                                'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                'lead_phone' =>$hotel_request_data->lead_phone,
                                'lead_email' =>$hotel_request_data->lead_email,
                                'lead_country' => $hotel_request_data->lead_country, 
                            ];
                            
                            $others_adults = [];
                            
                            if(isset($hotel_request_data->other_title)){
                                foreach($hotel_request_data->other_title as $index => $other_res){
                                    $others_adults[] = (Object)[
                                            'title' => $other_res,
                                            'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                            'nationality' => $hotel_request_data->other_nationality[$index],
                                        ];
                                }
                            }
                            
                            $childs = [];
                            if(isset($hotel_request_data->child_title)){
                                foreach($hotel_request_data->child_title as $index => $other_res){
                                    $childs[] = (Object)[
                                            'title' => $other_res,
                                            'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                            'nationality' => $hotel_request_data->child_nationality[$index],
                                        ];
                                }
                            }
                        
                        // dd($hotel_booking_conf_res);
                         try {
                             
                             if(isset($request->transfer_all_data) && $request->transfer_all_data !== NULL){
                                    $result = TransfersController::transfer_checkout_submit_with_service($request->transfer_all_data,$invoiceId,$booking_customer_id);
                                 }
                                 
                        $result = DB::table('hotels_bookings')->insert([
                                'invoice_no' => $invoiceId,
                                'booking_customer_id' => $booking_customer_id,
                                'provider' => $hotel_checkout_select->hotel_provider,
                                'exchange_currency' => $request->exchange_currency,
                                'exchange_price' => $request->exchange_price,
                                'base_exchange_rate'=>$request->base_exchange_rate,
                                'base_currency'=>$request->base_currency,
                                'selected_exchange_rate'=>$request->selected_exchange_rate,
                                'selected_currency'=>$request->selected_currency,
                                'GBP_currency'=>$request->admin_exchange_currency,
                                'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                'creationDate' => date('Y-m-d'),
                                'status' => "non_refundable",
                                'lead_passenger' => $lead_passenger_object->lead_first_name." ".$lead_passenger_object->lead_last_name,
                                'lead_passenger_data' => json_encode($lead_passenger_object),
                                'other_adults_data' => json_encode($others_adults),
                                'childs_data' => json_encode($childs),
                                'total_adults' => $customer_search_data->adult_searching,
                                'total_childs' => $customer_search_data->child_searching,
                                'total_rooms' => $customer_search_data->room_searching,
                                'reservation_request' => '',
                                'reservation_response' => '',
                                'all_checkout_request_data'=> json_encode($request->all()),
                                'hotel_request_data' => json_encode($hotel_request_data),
                                 'commision_charges'=>$request->commision_charges ?? '',
                                'hotel_data' => json_encode($hotel_checkout_select),
                                'customer_id' => $customer_id,
                                'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
                            ]);
                            
                            $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                                
                            if($result){
                                return response()->json([
                                    'status' => 'success_non_refundable',
                                    'Invoice_id' => $invoiceId,
                                    'Invoice_data' => $booking_data
                                ]);
                            }
                        
                         } catch (Throwable $e) {
                            echo $e;
                            return response()->json(['message'=>'error','booking_id'=> '']);
                        }   
               }

            }
            
            // die;
            
            

        }
        
        
        
        if($hotel_checkout_select->hotel_provider  == 'travelenda'){
            
            $current_date = date('Y-m-d');
            $cancilation_date = date('Y-m-d',strtotime($hotel_checkout_select->rooms_list[0]->cancliation_policy_arr[0]->from_date ?? ''));
           
              $cancilation_date = Carbon::parse($cancilation_date);
             $current1 = Carbon::parse($current_date);
            if($cancilation_date > $current1){
                
                function travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select){
                      $url = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                      $data = array('case' => 'travelandaCnfrmBookingnew','hotel_request_data' => json_encode($hotel_request_data),
                                          'hotel_checkout_select'=>json_encode($hotel_checkout_select));
                     
                     Session::put('travelenda_booking_request',$data);
                       $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_POST, true);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                      
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      $responseData = curl_exec($ch);
                      //echo $responseData;die();
                      if (curl_errno($ch)) {
                          return curl_error($ch);
                      }
                      curl_close($ch);
                      return $responseData;
                 }
      
                  $responseData3 = travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select);
                
                  $responseData3 = json_decode($responseData3);
              
                  $request_xml = simplexml_load_string($responseData3->request);
                  $hotel_request_send = json_encode($request_xml);
                    // print_r($hotel_request_send);
                    // die;
                  $result_booking_rs = json_decode($responseData3->response);
                  
                //   print_r($result_booking_rs);
                //   die;
                //   $result_booking_rs = json_decode($hotel_response);
                //   dd($result_booking_rs);
                  
                  if(isset($result_booking_rs->Body->HotelBooking)){
                      
                      $rooms_details_arr = [];
                      if(isset($hotel_checkout_select->rooms_list)){
                          foreach($hotel_checkout_select->rooms_list as $room_res){
                         
                              
                             if(isset($room_res->rooms_list)){
                                foreach($room_res->rooms_list as $room_option_res){
                                    
                                     // Room Rates Arr
                                      $room_rate_arr = [];
    
                                                // Rooms Cancilation Policy
                                               $cancliation_policy_arr = $room_res->cancliation_policy_arr;
                                          
                                              
                                              $room_rate_arr[] = (Object)[
                                                    'rateClass' => '',
                                                    'net' => $room_option_res->room_price,
                                                    'rateComments' => '',
                                                    'room_board' => $room_res->board_id,
                                                    'room_qty' => 1,
                                                    'adults' => $room_option_res->adults,
                                                    'children' => $room_option_res->childs,
                                                    'cancellation_policy' => $cancliation_policy_arr,
                                                  ];
                                          
                                      
                              
                                      $rooms_details_arr[] = (Object)[
                                                    'room_stutus' => "Confirmed",
                                                    'room_code' => $room_option_res->room_id,
                                                    'room_name' => $room_option_res->room_name,
                                                    'room_paxes' => [],
                                                    'adults' => $room_option_res->adults,
                                                    'childs' => $room_option_res->childs,
                                                    'room_rates' => $room_rate_arr,
                                                  ];
                                }
                             }
                          }
                          
                      }
                      
                      $hotel_checkout_request_d = json_decode($request->hotel_checkout_select);
                        $hotel_booking_conf_res = (Object)[
                                'provider' => 'travelenda',
                                 'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                'reference_no' => $result_booking_rs->Body->HotelBooking->BookingReference,
                                'total_price' => $result_booking_rs->Body->HotelBooking->TotalPrice,
                                'hotel_currency' => $result_booking_rs->Body->HotelBooking->Currency,
                                'clientReference' => $result_booking_rs->Body->HotelBooking->YourReference,
                                'creationDate' => $result_booking_rs->Head->ServerTime,
                                'status' => $result_booking_rs->Body->HotelBooking->BookingStatus,
                                'lead_passenger' => $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name,
                                'hotel_details' =>(Object)[
                                        'checkIn' => $hotel_checkout_select->checkIn,
                                        'checkOut' => $hotel_checkout_select->checkOut,
                                        'hotel_code' => $hotel_checkout_select->hotel_id,
                                        'hotel_name' => $hotel_checkout_select->hotel_name,
                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                        'destinationCode' => $hotel_checkout_select->destinationCode,
                                        'destinationName' => $hotel_checkout_select->destinationName,
                                        'latitude' => $hotel_checkout_select->latitude,
                                        'longitude' => $hotel_checkout_select->longitude,
                                        'rooms' => $rooms_details_arr
                                    ]
                            ];
                            
                             if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                            
                            $customer_id = '';
                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                            if($userData){
                                $customer_id = $userData->id;
                            }
                            
                               $lead_passenger_object = (Object)[
                                    'lead_title' =>$hotel_request_data->lead_title,
                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                    'lead_email' =>$hotel_request_data->lead_email,
                                    'lead_country' => $hotel_request_data->lead_country, 
                                ];
                                
                                $others_adults = [];
                                
                                if(isset($hotel_request_data->other_title)){
                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                        $others_adults[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                            ];
                                    }
                                }
                                
                                $childs = [];
                                if(isset($hotel_request_data->child_title)){
                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                        $childs[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                            ];
                                    }
                                }
                            
                            // dd($hotel_booking_conf_res);
                             try {
                                 
                                 if(isset($request->transfer_all_data) && $request->transfer_all_data !== NULL){
                                    $result = TransfersController::transfer_checkout_submit_with_service($request->transfer_all_data,$invoiceId,$booking_customer_id);
                                 }
                                 
                            $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no' => $invoiceId,
                                    'booking_customer_id' => $booking_customer_id,
                                    'provider' => $hotel_booking_conf_res->provider,
                                    'exchange_currency' => $request->exchange_currency,
                                    'exchange_price' => $request->exchange_price,
                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                    'base_currency'=>$request->base_currency,
                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                    'selected_currency'=>$request->selected_currency,
                                    'GBP_currency'=>$request->admin_exchange_currency,
                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                    'creationDate' =>  $result_booking_rs->Head->ServerTime,
                                    'status' => $result_booking_rs->Body->HotelBooking->BookingStatus,
                                    'lead_passenger' => $hotel_booking_conf_res->lead_passenger,
                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                    'other_adults_data' => json_encode($others_adults),
                                    'childs_data' => json_encode($childs),
                                    'total_adults' => $customer_search_data->adult_searching,
                                    'total_childs' => $customer_search_data->child_searching,
                                    'total_rooms' => $customer_search_data->room_searching,
                                    'reservation_request' => $hotel_request_send,
                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                     'commision_charges'=>$request->commision_charges ?? '',
                                    'customer_id' => $customer_id,
                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
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
                                    $exchange_admin_commission_amount=$request->admin_commission_amount; 
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
        

                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                     //print_r($price_with_out_commission);die();
                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
                                        'received_amount'=>$exchange_payable_price,
                                        'balance_amount'=>$big_exchange_payable_price,
                                        'type'=>'hotel_booking'
                                        ]);
                                    
                                    
                                    
                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
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
                                        
                                        $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                                      $credit_data = DB::table('credit_limits')->where('customer_id',$customer_get_data->id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
                                         $ramainAmount=$credit_data->remaining_amount - $request->creditAmount;
                                         
                                        $credit_limits = DB::table('credit_limits')->insert([
                                            'transection_id'=>$invoiceId,
                                            'customer_id'=>$customer_get_data->id,
                                                'amount'=>$request->creditAmount,
                                                'total_amount'=>$credit_data->total_amount,
                                                'remaining_amount'=>$ramainAmount,
                                                'currency'=>$credit_data->currency,
                                                'status'=>'1',
                                                'status_type'=>'approved',
                                            
                                            ]);
                                        
                                       $credit_limits = DB::table('credit_limit_transections')->insert([
                                        'invoice_no'=> $invoiceId,
                                        'customer_id'=>$customer_get_data->id,
                                        'transection_amount'=>$request->creditAmount,
                                        'remaining_amount'=>$ramainAmount,
                                        'type'=>'booked',
                                        ]);
                                        
                                        $admin_payments = DB::table('admin_provider_payments')->insert([
                                        
                                        'payment_transction_id'=> $invoiceId,
                                        'provider'=> 'hotelbeds',
                                        'payment_remaining_amount'=>$add_price,
                                        
                                        
                                        ]);
                                   //ended code by jamshaid cheena 08-06-2023
                                   
                                   // Added Balance To Customer Ledger 
                                    $booking_customer_data = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                                    if($booking_customer_data){
                                        $customer_balance = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                                    
                                        DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                        DB::table('customer_ledger')->insert([
                                                'received' => $request->admin_exchange_total_markup_price,
                                                'balance' => $customer_balance,
                                                'booking_customer' => $booking_customer_id,
                                                'hotel_invoice_no' => $invoiceId,
                                                'date' => date('Y-m-d'),
                                                'customer_id' => $userData->id
                                            ]);
                                            
                                        if($request->slc_pyment_method == 'slc_stripe'){
                                            
                                            $customer_balance = $customer_balance - $request->admin_exchange_total_markup_price;
                                            DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                            DB::table('customer_ledger')->insert([
                                                    'payment' => $request->admin_exchange_total_markup_price,
                                                    'balance' => $customer_balance,
                                                    'booking_customer' => $booking_customer_id,
                                                    'hotel_invoice_no' => $invoiceId,
                                                    'payment_method' => 'Stripe',
                                                    'date' => date('Y-m-d'),
                                                    'customer_id' => $userData->id
                                                ]);
                                        }
                                    }
                                   
                                   $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                                   
                                if($result){
                                    return response()->json([
                                        'status' => 'success',
                                        'Invoice_id' => $invoiceId,
                                        'Invoice_data' => $booking_data
                                    ]);
                                }
                            
                             } catch (Throwable $e) {
                                 DB::rollback();
                                echo $e;
                                return response()->json(['message'=>'error','booking_id'=> '']);
                            }
                            
    
                  }
                  
                  if($result_booking_rs->Body->Error){
                       return response()->json([
                            'status' => 'error',
                            'message' => $result_booking_rs->Body->Error->ErrorText
                        ]);
                  }
              
            }else{
                // Non Refundable Booking
               
               
               $slc_pyment_method=$request->slc_pyment_method;
               if($slc_pyment_method == 'slc_stripe')
               {
                                 function travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select){
                      $url = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                      $data = array('case' => 'travelandaCnfrmBookingnew','hotel_request_data' => json_encode($hotel_request_data),
                                          'hotel_checkout_select'=>json_encode($hotel_checkout_select));
                     
                     Session::put('travelenda_booking_request',$data);
                       $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_POST, true);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                      
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      $responseData = curl_exec($ch);
                      //echo $responseData;die();
                      if (curl_errno($ch)) {
                          return curl_error($ch);
                      }
                      curl_close($ch);
                      return $responseData;
                 }
      
                  $responseData3 = travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select);
                
                  $responseData3 = json_decode($responseData3);
              
                  $request_xml = simplexml_load_string($responseData3->request);
                  $hotel_request_send = json_encode($request_xml);
                    // print_r($hotel_request_send);
                    // die;
                  $result_booking_rs = json_decode($responseData3->response);
                  
                //   print_r($result_booking_rs);
                //   die;
                //   $result_booking_rs = json_decode($hotel_response);
                //   dd($result_booking_rs);
                  
                  if(isset($result_booking_rs->Body->HotelBooking)){
                      
                      $rooms_details_arr = [];
                      if(isset($hotel_checkout_select->rooms_list)){
                          foreach($hotel_checkout_select->rooms_list as $room_res){
                         
                              
                             if(isset($room_res->rooms_list)){
                                foreach($room_res->rooms_list as $room_option_res){
                                    
                                     // Room Rates Arr
                                      $room_rate_arr = [];
    
                                                // Rooms Cancilation Policy
                                               $cancliation_policy_arr = $room_res->cancliation_policy_arr;
                                          
                                              
                                              $room_rate_arr[] = (Object)[
                                                    'rateClass' => '',
                                                    'net' => $room_option_res->room_price,
                                                    'rateComments' => '',
                                                    'room_board' => $room_res->board_id,
                                                    'room_qty' => 1,
                                                    'adults' => $room_option_res->adults,
                                                    'children' => $room_option_res->childs,
                                                    'cancellation_policy' => $cancliation_policy_arr,
                                                  ];
                                          
                                      
                              
                                      $rooms_details_arr[] = (Object)[
                                                    'room_stutus' => "Confirmed",
                                                    'room_code' => $room_option_res->room_id,
                                                    'room_name' => $room_option_res->room_name,
                                                    'room_paxes' => [],
                                                    'adults' => $room_option_res->adults,
                                                    'childs' => $room_option_res->childs,
                                                    'room_rates' => $room_rate_arr,
                                                  ];
                                }
                             }
                          }
                          
                      }
                      
                      $hotel_checkout_request_d = json_decode($request->hotel_checkout_select);
                        $hotel_booking_conf_res = (Object)[
                                'provider' => 'travelenda',
                                 'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                'reference_no' => $result_booking_rs->Body->HotelBooking->BookingReference,
                                'total_price' => $result_booking_rs->Body->HotelBooking->TotalPrice,
                                'hotel_currency' => $result_booking_rs->Body->HotelBooking->Currency,
                                'clientReference' => $result_booking_rs->Body->HotelBooking->YourReference,
                                'creationDate' => $result_booking_rs->Head->ServerTime,
                                'status' => $result_booking_rs->Body->HotelBooking->BookingStatus,
                                'lead_passenger' => $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name,
                                'hotel_details' =>(Object)[
                                        'checkIn' => $hotel_checkout_select->checkIn,
                                        'checkOut' => $hotel_checkout_select->checkOut,
                                        'hotel_code' => $hotel_checkout_select->hotel_id,
                                        'hotel_name' => $hotel_checkout_select->hotel_name,
                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                        'destinationCode' => $hotel_checkout_select->destinationCode,
                                        'destinationName' => $hotel_checkout_select->destinationName,
                                        'latitude' => $hotel_checkout_select->latitude,
                                        'longitude' => $hotel_checkout_select->longitude,
                                        'rooms' => $rooms_details_arr
                                    ]
                            ];
                            
                             if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                            
                            $customer_id = '';
                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                            if($userData){
                                $customer_id = $userData->id;
                            }
                            
                               $lead_passenger_object = (Object)[
                                    'lead_title' =>$hotel_request_data->lead_title,
                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                    'lead_email' =>$hotel_request_data->lead_email,
                                    'lead_country' => $hotel_request_data->lead_country, 
                                ];
                                
                                $others_adults = [];
                                
                                if(isset($hotel_request_data->other_title)){
                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                        $others_adults[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                            ];
                                    }
                                }
                                
                                $childs = [];
                                if(isset($hotel_request_data->child_title)){
                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                        $childs[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                            ];
                                    }
                                }
                            
                            // dd($hotel_booking_conf_res);
                             try {
                                 
                                 if(isset($request->transfer_all_data) && $request->transfer_all_data !== NULL){
                                    $result = TransfersController::transfer_checkout_submit_with_service($request->transfer_all_data,$invoiceId,$booking_customer_id);
                                 }
                                 
                            $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no' => $invoiceId,
                                    'booking_customer_id' => $booking_customer_id,
                                    'provider' => $hotel_booking_conf_res->provider,
                                    'exchange_currency' => $request->exchange_currency,
                                    'exchange_price' => $request->exchange_price,
                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                    'base_currency'=>$request->base_currency,
                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                    'selected_currency'=>$request->selected_currency,
                                    'GBP_currency'=>$request->admin_exchange_currency,
                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                    'creationDate' =>  $result_booking_rs->Head->ServerTime,
                                    'status' => $result_booking_rs->Body->HotelBooking->BookingStatus,
                                    'lead_passenger' => $hotel_booking_conf_res->lead_passenger,
                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                    'other_adults_data' => json_encode($others_adults),
                                    'childs_data' => json_encode($childs),
                                    'total_adults' => $customer_search_data->adult_searching,
                                    'total_childs' => $customer_search_data->child_searching,
                                    'total_rooms' => $customer_search_data->room_searching,
                                    'reservation_request' => $hotel_request_send,
                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                     'commision_charges'=>$request->commision_charges ?? '',
                                    'customer_id' => $customer_id,
                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
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
                                    $exchange_admin_commission_amount=$request->admin_commission_amount; 
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
        

                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                     //print_r($price_with_out_commission);die();
                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
                                        'received_amount'=>$exchange_payable_price,
                                        'balance_amount'=>$big_exchange_payable_price,
                                        'type'=>'hotel_booking'
                                        ]);
                                    
                                    
                                    
                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
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
                                        
                                        'payment_transction_id'=> $invoiceId,
                                        'provider'=> 'hotelbeds',
                                        'payment_remaining_amount'=>$add_price,
                                        
                                        
                                        ]);
                                   //ended code by jamshaid cheena 08-06-2023
                                   
                                   // Added Balance To Customer Ledger 
                                    $booking_customer_data = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                                    if($booking_customer_data){
                                        $customer_balance = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                                    
                                        DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                        DB::table('customer_ledger')->insert([
                                                'received' => $request->admin_exchange_total_markup_price,
                                                'balance' => $customer_balance,
                                                'booking_customer' => $booking_customer_id,
                                                'hotel_invoice_no' => $invoiceId,
                                                'date' => date('Y-m-d'),
                                                'customer_id' => $userData->id
                                            ]);
                                            
                                        if($request->slc_pyment_method == 'slc_stripe'){
                                            
                                            $customer_balance = $customer_balance - $request->admin_exchange_total_markup_price;
                                            DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                            DB::table('customer_ledger')->insert([
                                                    'payment' => $request->admin_exchange_total_markup_price,
                                                    'balance' => $customer_balance,
                                                    'booking_customer' => $booking_customer_id,
                                                    'hotel_invoice_no' => $invoiceId,
                                                    'payment_method' => 'Stripe',
                                                    'date' => date('Y-m-d'),
                                                    'customer_id' => $userData->id
                                                ]);
                                        }
                                    }
                                   
                                   $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                                if($result){
                                    return response()->json([
                                        'status' => 'success',
                                        'Invoice_id' => $invoiceId,
                                        'Invoice_data' => $booking_data
                                    ]);
                                }
                            
                             } catch (Throwable $e) {
                                 DB::rollback();
                                echo $e;
                                return response()->json(['message'=>'error','booking_id'=> '']);
                            }
                            
    
                  }
                  
                  if($result_booking_rs->Body->Error){
                       return response()->json([
                            'status' => 'error',
                            'message' => $result_booking_rs->Body->Error->ErrorText
                        ]);
                  }  
               }
               else
               {
                        if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                        
                        $customer_id = '';
                        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                        if($userData){
                            $customer_id = $userData->id;
                        }
                        
                           $lead_passenger_object = (Object)[
                                'lead_title' =>$hotel_request_data->lead_title,
                                'lead_first_name' =>$hotel_request_data->lead_first_name,
                                'lead_last_name' =>$hotel_request_data->lead_last_name,
                                'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                'lead_phone' =>$hotel_request_data->lead_phone,
                                'lead_email' =>$hotel_request_data->lead_email,
                                'lead_country' => $hotel_request_data->lead_country, 
                            ];
                            
                            $others_adults = [];
                            
                            if(isset($hotel_request_data->other_title)){
                                foreach($hotel_request_data->other_title as $index => $other_res){
                                    $others_adults[] = (Object)[
                                            'title' => $other_res,
                                            'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                            'nationality' => $hotel_request_data->other_nationality[$index],
                                        ];
                                }
                            }
                            
                            $childs = [];
                            if(isset($hotel_request_data->child_title)){
                                foreach($hotel_request_data->child_title as $index => $other_res){
                                    $childs[] = (Object)[
                                            'title' => $other_res,
                                            'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                            'nationality' => $hotel_request_data->child_nationality[$index],
                                        ];
                                }
                            }
                        // dd($hotel_booking_conf_res);
                         try {
                             
                             if(isset($request->transfer_all_data) && $request->transfer_all_data !== NULL){
                                    $result = TransfersController::transfer_checkout_submit_with_service($request->transfer_all_data,$invoiceId,$booking_customer_id);
                                 }
                        $result = DB::table('hotels_bookings')->insert([
                                'invoice_no' => $invoiceId,
                                'booking_customer_id' => $booking_customer_id,
                                'provider' => $hotel_checkout_select->hotel_provider,
                                'exchange_currency' => $request->exchange_currency,
                                'exchange_price' => $request->exchange_price,
                                'base_exchange_rate'=>$request->base_exchange_rate,
                                'base_currency'=>$request->base_currency,
                                'selected_exchange_rate'=>$request->selected_exchange_rate,
                                'selected_currency'=>$request->selected_currency,
                                'GBP_currency'=>$request->admin_exchange_currency,
                                'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                'creationDate' => date('Y-m-d'),
                                'status' => "non_refundable",
                                'lead_passenger' => $lead_passenger_object->lead_first_name." ".$lead_passenger_object->lead_last_name,
                                'lead_passenger_data' => json_encode($lead_passenger_object),
                                'other_adults_data' => json_encode($others_adults),
                                'childs_data' => json_encode($childs),
                                'total_adults' => $customer_search_data->adult_searching,
                                'total_childs' => $customer_search_data->child_searching,
                                'total_rooms' => $customer_search_data->room_searching,
                                'reservation_request' => '',
                                'reservation_response' => '',
                                'all_checkout_request_data'=> json_encode($request->all()),
                                'hotel_request_data' => json_encode($hotel_request_data),
                                'hotel_data' => json_encode($hotel_checkout_select),
                                 'commision_charges'=>$request->commision_charges ?? '',
                                'customer_id' => $customer_id,
                                'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
                            ]);
                               
                               $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first(); 
                            if($result){
                                return response()->json([
                                    'status' => 'success_non_refundable',
                                    'Invoice_id' => $invoiceId,
                                    'Invoice_data' => $booking_data
                                ]);
                            }
                        
                         } catch (Throwable $e) {
                            echo $e;
                            return response()->json(['message'=>'error','booking_id'=> '']);
                        }   
               }
               
               
               

            }
        }
        
        // print_r($hotel_request_data);
        // print_r($hotel_checkout_select);
    }
    
    public function proccess_non_refundable_booking(Request $request){
        $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
        $booking_customer_id = $booking_data->booking_customer_id;
        
        $hotel_request_data = json_decode($booking_data->hotel_request_data);
        $hotel_checkout_select = json_decode($booking_data->hotel_data);
        $hotel_all_request_data = json_decode($booking_data->all_checkout_request_data);
        
           // ************************************************************
        // Hotels Bed Reservation
        // ************************************************************
        if($hotel_checkout_select->hotel_provider  == 'hotel_beds'){
            
            $current_date = date('Y-m-d');
           
            
               // Refundable Booking
               
                  function confirmbooking($hotel_request_data,$hotel_checkout_select){
                  $url = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi.php";
                  $data = array('case' => 'new_confirmbooking', 
                                  'hotel_request_data' => json_encode($hotel_request_data),
                                  'hotel_checkout_select'=>json_encode($hotel_checkout_select));
                  Session::put('hotelbeds_booking_rq',$data);
                   $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $url);
                  curl_setopt($ch, CURLOPT_POST, true);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                  
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $responseData = curl_exec($ch);
                //   echo $responseData;die();
                  if (curl_errno($ch)) {
                      return curl_error($ch);
                  }
                  curl_close($ch);
                  return $responseData;
              }
          
                  $responseData3 = confirmbooking($hotel_request_data,$hotel_checkout_select);
                  $responseData3 = json_decode($responseData3);
                  $hotel_request_send = json_decode($responseData3->request);
                  $result_booking_rs = json_decode($responseData3->response);
                  
                //   $result_booking_rs = json_decode($hotel_response);
                //   dd($result_booking_rs);
                  
                  if(isset($result_booking_rs->booking)){
                      
                      $rooms_details_arr = [];
                      if(isset($result_booking_rs->booking->hotel->rooms)){
                          foreach($result_booking_rs->booking->hotel->rooms as $room_res){
                              
                              // Paxes Arr
                              $paxes_arr = [];
                              if(isset($room_res->paxes)){
                                  foreach($room_res->paxes as $pax_res){
                                      $type = '';
                                      if($pax_res->type == 'AD'){
                                          $type = 'Adult';
                                      }
                                      
                                      if($pax_res->type == 'CH'){
                                          $type = 'Child';
                                      }
                                      
                                      $paxes_arr[] = [
                                            'type' => $type,
                                            'name' => $pax_res->name." ".$pax_res->surname,
                                          ];
                                  }
                              }
                              
                              // Room Rates Arr
                              $room_rate_arr = [];
                              if(isset($room_res->rates)){
                                  foreach($room_res->rates as $rate_res){
                                      
                                        // Rooms Cancilation Policy
                                       $cancliation_policy_arr = [];
                                       if(isset($rate_res->cancellationPolicies)){
                                           foreach($rate_res->cancellationPolicies as $cancel_res){
                                               $cancel_tiem = (Object)[
                                                    'amount'=> $cancel_res->amount,
                                                    'from_date'=> $cancel_res->from,
                                                   ];
                                               $cancliation_policy_arr[] = $cancel_tiem;
                                           }
                                       }
                                      
                                      $room_rate_arr[] = (Object)[
                                            'rateClass' => $rate_res->rateClass,
                                            'net' => $rate_res->net,
                                            'rateComments' => $rate_res->rateComments ?? '',
                                            'room_board' => $rate_res->boardName,
                                            'room_qty' => $rate_res->rooms,
                                            'adults' => $rate_res->adults,
                                            'children' => $rate_res->children,
                                            'cancellation_policy' => $cancliation_policy_arr,
                                          ];
                                  }
                              }
                              
                              $rooms_details_arr[] = (Object)[
                                            'room_stutus' => $room_res->status,
                                            'room_code' => $room_res->code,
                                            'room_name' => $room_res->name,
                                            'room_paxes' => $paxes_arr,
                                            'room_rates' => $room_rate_arr,
                                          ];
                          }
                          
                      }
                      
                      $hotel_checkout_request_d = $hotel_checkout_select;
                        $hotel_booking_conf_res = (Object)[
                                'provider' => 'hotel_beds',
                                'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                'reference_no' => $result_booking_rs->booking->reference,
                                'total_price' => $result_booking_rs->booking->totalNet,
                                'hotel_currency' => $result_booking_rs->booking->currency,
                                'clientReference' => $result_booking_rs->booking->clientReference,
                                'creationDate' => $result_booking_rs->booking->creationDate,
                                'status' => $result_booking_rs->booking->status,
                                'lead_passenger' => $result_booking_rs->booking->holder->name." ".$result_booking_rs->booking->holder->surname,
                                'status' => $result_booking_rs->booking->status,
                                'hotel_details' =>(Object)[
                                        'checkIn' => $result_booking_rs->booking->hotel->checkIn,
                                        'checkOut' => $result_booking_rs->booking->hotel->checkOut,
                                        'hotel_code' => $result_booking_rs->booking->hotel->code,
                                        'hotel_name' => $result_booking_rs->booking->hotel->name,
                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                        'destinationCode' => $result_booking_rs->booking->hotel->destinationCode,
                                        'destinationName' => $result_booking_rs->booking->hotel->destinationName,
                                        'latitude' => $result_booking_rs->booking->hotel->latitude,
                                        'longitude' => $result_booking_rs->booking->hotel->longitude,
                                        'rooms' => $rooms_details_arr
                                    ]
                            ];
                            
                            
                            
                            // dd($hotel_booking_conf_res);
                             try {
                                 
                                 $invoiceId = $request->invoice_no;
                            $result = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
                                   
                                    'status' => $hotel_booking_conf_res->status,
                                    'reservation_request' => json_encode($hotel_request_send),
                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                ]);
                                
                                 //added code by jamshaid cheena 08-06-2023
                                    $client_markup=$hotel_all_request_data->client_markup;
                                    $admin_markup=$hotel_all_request_data->admin_markup;
                                    $client_markup_type=$hotel_all_request_data->client_markup_type;
                                    $admin_markup_type=$hotel_all_request_data->admin_markup_type;
                                    $payable_price=$hotel_all_request_data->payable_price;
                                    $client_commission_amount=$hotel_all_request_data->client_commission_amount;
                                    
                                    $total_markup_price=$hotel_all_request_data->total_markup_price;
                                    $currency=$hotel_all_request_data->currency;
                                    $exchange_client_commission_amount=$hotel_all_request_data->exchange_client_commission_amount;                                         
                                    $exchange_payable_price=$hotel_all_request_data->exchange_payable_price;                                          
                                    $exchange_admin_commission_amount=$hotel_all_request_data->admin_commission_amount; 
                                    $exchange_total_markup_price=$hotel_all_request_data->exchange_total_markup_price; 
                                    $exchange_currency=$hotel_all_request_data->exchange_currency; 
                                    $exchange_rate=$hotel_all_request_data->exchange_rate; 
                        
                                    $admin_exchange_amount=$hotel_all_request_data->admin_exchange_amount;
                                    $admin_commission_amount=$hotel_all_request_data->admin_commission_amount;
                                    $admin_exchange_currency=$hotel_all_request_data->admin_exchange_currency;
                                    $admin_exchange_rate=$hotel_all_request_data->admin_exchange_rate;
                                    $admin_exchange_total_markup_price=$hotel_all_request_data->admin_exchange_total_markup_price;
                        
                        
                                    $price=$hotel_all_request_data->exchange_price;
                                    $p_price=json_decode($price);
        

                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$hotel_all_request_data->token)->limit(1)->first();
                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                     //print_r($price_with_out_commission);die();
                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                        
                                        'token'=>$hotel_all_request_data->token,
                                        'invoice_no'=>$invoiceId,
                                        'received_amount'=>$exchange_payable_price,
                                        'balance_amount'=>$big_exchange_payable_price,
                                        'type'=>'hotel_booking'
                                        ]);
                                    
                                    
                                    
                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                        
                                        'token'=>$hotel_all_request_data->token,
                                        'invoice_no'=>$invoiceId,
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
                                        
                                        'payment_transction_id'=> $invoiceId,
                                        'provider'=> 'hotelbeds',
                                        'payment_remaining_amount'=>$add_price,
                                        
                                        
                                        ]);
                                   //ended code by jamshaid cheena 08-06-2023
                                   
                                   // Added Balance To Customer Ledger 
                                   
                                    $booking_customer_data = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                                    if($booking_customer_data){
                                        $customer_balance = $booking_customer_data->balance + $hotel_all_request_data->admin_exchange_total_markup_price;
                                    
                                        DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                        DB::table('customer_ledger')->insert([
                                                'received' => $hotel_all_request_data->admin_exchange_total_markup_price,
                                                'balance' => $customer_balance,
                                                'booking_customer' => $booking_customer_id,
                                                'hotel_invoice_no' => $invoiceId,
                                                'date' => date('Y-m-d'),
                                                'customer_id' => $userData->id
                                            ]);
                                            
                                        if($hotel_all_request_data->slc_pyment_method == 'slc_stripe'){
                                            
                                            $customer_balance = $customer_balance - $hotel_all_request_data->admin_exchange_total_markup_price;
                                            DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                            DB::table('customer_ledger')->insert([
                                                    'payment' => $hotel_all_request_data->admin_exchange_total_markup_price,
                                                    'balance' => $customer_balance,
                                                    'booking_customer' => $booking_customer_id,
                                                    'hotel_invoice_no' => $invoiceId,
                                                    'payment_method' => 'Stripe',
                                                    'date' => date('Y-m-d'),
                                                    'customer_id' => $userData->id
                                                ]);
                                        }
                                    }
                                                        
                                    $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                                if($result){
                                    return response()->json([
                                        'status' => 'success',
                                        'Invoice_id' => $invoiceId,
                                        'Invoice_data' => $booking_data
                                    ]);
                                }
                            
                             } catch (Throwable $e) {
                                 DB::rollback();
                                echo $e;
                                return response()->json(['message'=>'error','booking_id'=> '']);
                            }
                            
    
                  }
                  
                  if($result_booking_rs->error){
                        $result = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
                                   
                                    'status' => 'Failed',
                                    'failed_message' => $result_booking_rs->error->message
                                    
                                ]);
                                
                       return response()->json([
                            'status' => 'error',
                            'message' => $result_booking_rs->error->message
                        ]);
                  }
              
            
            
            // die;
            
            

        }
        
        
        if($hotel_checkout_select->hotel_provider  == 'travelenda'){
            
                $current_date = date('Y-m-d');
          
                
                function travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select){
                      $url = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                      $data = array('case' => 'travelandaCnfrmBookingnew','hotel_request_data' => json_encode($hotel_request_data),
                                          'hotel_checkout_select'=>json_encode($hotel_checkout_select));
                     
                     Session::put('travelenda_booking_request',$data);
                       $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_POST, true);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                      
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      $responseData = curl_exec($ch);
                      //echo $responseData;die();
                      if (curl_errno($ch)) {
                          return curl_error($ch);
                      }
                      curl_close($ch);
                      return $responseData;
                 }
      
                  $responseData3 = travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select);
                
                  $responseData3 = json_decode($responseData3);
              
                  $request_xml = simplexml_load_string($responseData3->request);
                  $hotel_request_send = json_encode($request_xml);
                    // print_r($hotel_request_send);
                    // die;
                  $result_booking_rs = json_decode($responseData3->response);
                  
                //   print_r($result_booking_rs);
                //   die;
                //   $result_booking_rs = json_decode($hotel_response);
                //   dd($result_booking_rs);
                  
                  if(isset($result_booking_rs->Body->HotelBooking)){
                      
                      $rooms_details_arr = [];
                      if(isset($hotel_checkout_select->rooms_list)){
                          foreach($hotel_checkout_select->rooms_list as $room_res){
                         
                              
                             if(isset($room_res->rooms_list)){
                                foreach($room_res->rooms_list as $room_option_res){
                                    
                                     // Room Rates Arr
                                      $room_rate_arr = [];
    
                                                // Rooms Cancilation Policy
                                               $cancliation_policy_arr = $room_res->cancliation_policy_arr;
                                          
                                              
                                              $room_rate_arr[] = (Object)[
                                                    'rateClass' => '',
                                                    'net' => $room_option_res->room_price,
                                                    'rateComments' => '',
                                                    'room_board' => $room_res->board_id,
                                                    'room_qty' => 1,
                                                    'adults' => $room_option_res->adults,
                                                    'children' => $room_option_res->childs,
                                                    'cancellation_policy' => $cancliation_policy_arr,
                                                  ];
                                          
                                      
                              
                                      $rooms_details_arr[] = (Object)[
                                                    'room_stutus' => "Confirmed",
                                                    'room_code' => $room_option_res->room_id,
                                                    'room_name' => $room_option_res->room_name,
                                                    'room_paxes' => [],
                                                    'adults' => $room_option_res->adults,
                                                    'childs' => $room_option_res->childs,
                                                    'room_rates' => $room_rate_arr,
                                                  ];
                                }
                             }
                          }
                          
                      }
                      
                      
                      $hotel_checkout_request_d = $hotel_checkout_select;
                        $hotel_booking_conf_res = (Object)[
                                'provider' => 'travelenda',
                                'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                'reference_no' => $result_booking_rs->Body->HotelBooking->BookingReference,
                                'total_price' => $result_booking_rs->Body->HotelBooking->TotalPrice,
                                'hotel_currency' => $result_booking_rs->Body->HotelBooking->Currency,
                                'clientReference' => $result_booking_rs->Body->HotelBooking->YourReference,
                                'creationDate' => $result_booking_rs->Head->ServerTime,
                                'status' => $result_booking_rs->Body->HotelBooking->BookingStatus,
                                'lead_passenger' => $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name,
                                'hotel_details' =>(Object)[
                                        'checkIn' => $hotel_checkout_select->checkIn,
                                        'checkOut' => $hotel_checkout_select->checkOut,
                                        'hotel_code' => $hotel_checkout_select->hotel_id,
                                        'hotel_name' => $hotel_checkout_select->hotel_name,
                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                        'destinationCode' => $hotel_checkout_select->destinationCode,
                                        'destinationName' => $hotel_checkout_select->destinationName,
                                        'latitude' => $hotel_checkout_select->latitude,
                                        'longitude' => $hotel_checkout_select->longitude,
                                        'rooms' => $rooms_details_arr
                                    ]
                            ];
                            
                        
                            // dd($hotel_booking_conf_res);
                             try {
                                 
                                 $invoiceId = $request->invoice_no;
                                $result = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
                                    'status' => $result_booking_rs->Body->HotelBooking->BookingStatus,
                                    'reservation_request' => $hotel_request_send,
                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                ]);
                                    
                                    
                                 //added code by jamshaid cheena 08-06-2023
                                    $client_markup=$hotel_all_request_data->client_markup;
                                    $admin_markup=$hotel_all_request_data->admin_markup;
                                    $client_markup_type=$hotel_all_request_data->client_markup_type;
                                    $admin_markup_type=$hotel_all_request_data->admin_markup_type;
                                    $payable_price=$hotel_all_request_data->payable_price;
                                    $client_commission_amount=$hotel_all_request_data->client_commission_amount;
                                    
                                    $total_markup_price=$hotel_all_request_data->total_markup_price;
                                    $currency=$hotel_all_request_data->currency;
                                    $exchange_client_commission_amount=$hotel_all_request_data->exchange_client_commission_amount;                                         
                                    $exchange_payable_price=$hotel_all_request_data->exchange_payable_price;                                          
                                    $exchange_admin_commission_amount=$hotel_all_request_data->admin_commission_amount; 
                                    $exchange_total_markup_price=$hotel_all_request_data->exchange_total_markup_price; 
                                    $exchange_currency=$hotel_all_request_data->exchange_currency; 
                                    $exchange_rate=$hotel_all_request_data->exchange_rate; 
                        
                                    $admin_exchange_amount=$hotel_all_request_data->admin_exchange_amount;
                                    $admin_commission_amount=$hotel_all_request_data->admin_commission_amount;
                                    $admin_exchange_currency=$hotel_all_request_data->admin_exchange_currency;
                                    $admin_exchange_rate=$hotel_all_request_data->admin_exchange_rate;
                                    $admin_exchange_total_markup_price=$hotel_all_request_data->admin_exchange_total_markup_price;
                        
                        
                                    $price=$hotel_all_request_data->exchange_price;
                                    $p_price=json_decode($price);
        

                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$hotel_all_request_data->token)->limit(1)->first();
                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                     //print_r($price_with_out_commission);die();
                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                        
                                        'token'=>$hotel_all_request_data->token,
                                        'invoice_no'=>$invoiceId,
                                        'received_amount'=>$exchange_payable_price,
                                        'balance_amount'=>$big_exchange_payable_price,
                                        'type'=>'hotel_booking'
                                        ]);
                                    
                                    
                                    
                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                        
                                        'token'=>$hotel_all_request_data->token,
                                        'invoice_no'=>$invoiceId,
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
                                        
                                        'payment_transction_id'=> $invoiceId,
                                        'provider'=> 'hotelbeds',
                                        'payment_remaining_amount'=>$add_price,
                                        
                                        
                                        ]);
                                   //ended code by jamshaid cheena 08-06-2023
                                   //ended code by jamshaid cheena 08-06-2023
                                   
                                   // Added Balance To Customer Ledger 
                                   
                                    $booking_customer_data = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                                    if($booking_customer_data){
                                        $customer_balance = $booking_customer_data->balance + $hotel_all_request_data->admin_exchange_total_markup_price;
                                    
                                        DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                        DB::table('customer_ledger')->insert([
                                                'received' => $hotel_all_request_data->admin_exchange_total_markup_price,
                                                'balance' => $customer_balance,
                                                'booking_customer' => $booking_customer_id,
                                                'hotel_invoice_no' => $invoiceId,
                                                'date' => date('Y-m-d'),
                                                'customer_id' => $userData->id
                                            ]);
                                            
                                        if($hotel_all_request_data->slc_pyment_method == 'slc_stripe'){
                                            
                                            $customer_balance = $customer_balance - $hotel_all_request_data->admin_exchange_total_markup_price;
                                            DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                            DB::table('customer_ledger')->insert([
                                                    'payment' => $hotel_all_request_data->admin_exchange_total_markup_price,
                                                    'balance' => $customer_balance,
                                                    'booking_customer' => $booking_customer_id,
                                                    'hotel_invoice_no' => $invoiceId,
                                                    'payment_method' => 'Stripe',
                                                    'date' => date('Y-m-d'),
                                                    'customer_id' => $userData->id
                                                ]);
                                        }
                                    }
                                    
                                   $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                                   
                                if($result){
                                    return response()->json([
                                        'status' => 'success',
                                        'Invoice_id' => $invoiceId,
                                        'Invoice_data' => $booking_data
                                    ]);
                                }
                            
                             } catch (Throwable $e) {
                                 DB::rollback();
                                echo $e;
                                return response()->json(['message'=>'error','booking_id'=> '']);
                            }
                            
    
                  }
                  
                  if($result_booking_rs->Body->Error){
                      $result = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
                                    'status' => 'Failed',
                                    'failed_message' => $result_booking_rs->Body->Error->ErrorText
                                ]);
                                
                       return response()->json([
                            'status' => 'error',
                            'message' => $result_booking_rs->Body->Error->ErrorText
                        ]);
                  }
              
            
        }
        // $hotel_checkout_select->hotel_provider
        // dd($hotel_checkout_select);
    }
    
    public function on_request_checkout_submit(Request $request){
        $all_request_data = json_decode($request->request_data);
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        // dd($all_request_data);
         $booking_data = DB::table('hotels_bookings')->where('invoice_no',$all_request_data->invoice_no)->first();
         
        //  dd($booking_data);
         
          DB::beginTransaction();
        
            try { 
                $invoiceId = $all_request_data->invoice_no;
                $reservation_request_data = json_decode($booking_data->reservation_request);
                $hotel_checkout_select = json_decode($reservation_request_data->hotel_checkout_select);
                
                // dd($reservation_request_data);
                
                if(isset($hotel_checkout_select->rooms_list)){
                                
                                foreach($hotel_checkout_select->rooms_list as $room_res){
                                    if($room_res->request_type == '1'){
                                        $room_data = DB::table('rooms')->where('id',$room_res->booking_req_id)->first();
                                            if($room_data){
                
                                                // Update Room Data
                                                $rooms_qty = $room_res->selected_qty;
                                                $total_booked = $room_data->booked + $rooms_qty;
                        
                                                 DB::table('rooms_bookings_details')->insert([
                                                     'room_id'=> $room_res->booking_req_id,
                                                     'booking_from'=>'website',
                                                     'quantity'=>$rooms_qty,
                                                     'booking_id'=>$invoiceId,
                                                     'date'=>date('Y-m-d'),
                                                     'check_in'=>$hotel_checkout_select->checkIn,
                                                     'check_out'=>$hotel_checkout_select->checkOut,
                                                 ]);
                                                 
                                       
                                                DB::table('rooms')->where('id',$room_res->booking_req_id)->update(['booked'=>$total_booked]);
                                                
                                                
                                                // Update Hotel Supplier Balance
                                        
                                                 $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
                       
                                                 if(isset($supplier_data)){
                                                        // echo "Enter hre ";
                                                        $total_price = $room_res->rooms_total_price;
                                                       
                                                        // echo "The supplier Balance is ".$supplier_data->balance;
                                                        $supplier_balance = $supplier_data->balance;
                                                        $supplier_payable_balance = $supplier_data->payable + $total_price;
                                                        
                                                        // update Agent Balance
                                                        
                                                        DB::table('hotel_supplier_ledger')->insert([
                                                            'supplier_id'=>$supplier_data->id,
                                                            'payment'=>$total_price,
                                                            'balance'=>$supplier_balance,
                                                            'payable_balance'=>$supplier_payable_balance,
                                                            'room_id'=>$room_data->id,
                                                            'customer_id'=>$userData->id,
                                                            'date'=>date('Y-m-d'),
                                                            'website_booking_id'=>$invoiceId,
                                                            'available_from'=>$hotel_checkout_select->checkIn,
                                                            'available_to'=>$hotel_checkout_select->checkOut,
                                                            'room_quantity'=>$room_res->selected_qty,
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
                
                
                
                $reservation_data = json_decode($booking_data->reservation_response);
                
                $reservation_data->status = 'Confrimed';
                
                if(isset($reservation_data->hotel_details->rooms)){
                    foreach($reservation_data->hotel_details->rooms as $index => $room_res){
                        $reservation_data->hotel_details->rooms[$index]->room_stutus = 'Confrimed';
                    }
                }
                // dd($reservation_data);
                $result = DB::table('hotels_bookings')->where('invoice_no',$all_request_data->invoice_no)->update([
                   'payment_details'=>$request->payment_details,       
                    'status' => 'Confrimed',
                    'reservation_response' => json_encode($reservation_data),
                ]);
                
                //added code by jamshaid cheena 08-06-2023
                    $client_markup=$reservation_request_data->client_markup;
                    $admin_markup=$reservation_request_data->admin_markup;
                    $client_markup_type=$reservation_request_data->client_markup_type;
                    $admin_markup_type=$reservation_request_data->admin_markup_type;
                    $payable_price=$reservation_request_data->admin_commission_amount_orignial;
                    $client_commission_amount=$reservation_request_data->client_commission_amount;
                    
                    $total_markup_price=$reservation_request_data->total_markup_price;
                    $currency=$reservation_request_data->currency;
                    $exchange_client_commission_amount=$reservation_request_data->exchange_client_commission_amount;                                         
                    $exchange_payable_price=$reservation_request_data->exchange_admin_commission_amount;                                          
                    $exchange_admin_commission_amount=$reservation_request_data->exchange_admin_commission_amount; 
                    $exchange_total_markup_price=$reservation_request_data->exchange_total_markup_price; 
                    $exchange_currency=$reservation_request_data->exchange_currency; 
                    $exchange_rate=$reservation_request_data->exchange_rate; 
        
                    $admin_exchange_amount=$reservation_request_data->admin_commission_amount;
                    $admin_commission_amount=$reservation_request_data->admin_commission_amount;
                    $admin_exchange_currency=$reservation_request_data->admin_exchange_currency;
                    $admin_exchange_rate=$reservation_request_data->admin_exchange_rate;
                    $admin_exchange_total_markup_price=$reservation_request_data->admin_exchange_total_markup_price;
        
        
                    $price=$reservation_request_data->exchange_price;
                    $p_price=json_decode($price);


                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$reservation_request_data->token)->limit(1)->first();
                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                     //print_r($price_with_out_commission);die();
                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                        
                        'token'=>$reservation_request_data->token,
                        'invoice_no'=>$invoiceId,
                        'received_amount'=>$exchange_payable_price,
                        'balance_amount'=>$big_exchange_payable_price,
                        'type'=>'hotel_booking'
                        ]);
                    
                    
                    
                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                        
                        'token'=>$reservation_request_data->token,
                        'invoice_no'=>$invoiceId,
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
                            'payment_transction_id'=> $invoiceId,
                            'provider'=> 'Custome_hotel',
                            'payment_remaining_amount'=>$add_price,
                        
                        ]);
                        
                        
                
                $booking_data = DB::table('hotels_bookings')->where('invoice_no',$all_request_data->invoice_no)->first();
                
                DB::commit();
                return response()->json([
                        'status' => 'success',
                        'Invoice_id' => $all_request_data->invoice_no,
                        'Invoice_data' => $booking_data
                ]);
            }catch (Throwable $e) {
                 DB::rollback();
                echo $e;
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
    }
    
    public function confrim_on_request(Request $request){
        $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
        
        $reservation_data = json_decode($booking_data->reservation_response);
        
        $reservation_data->status = 'In_Progess';
        
        if(isset($reservation_data->hotel_details->rooms)){
            foreach($reservation_data->hotel_details->rooms as $index => $room_res){
                $reservation_data->hotel_details->rooms[$index]->room_stutus = 'In_Progess';
            }
        }
        // dd($reservation_data);
        $currentDateTime = Carbon::now();

        // Add 2 hours to the current time
        $updatedDateTime = $currentDateTime->addHours($request->hours);
        
        // Optionally, you can format the updated time as per your requirement
        $formattedDateTime = $updatedDateTime->format('Y-m-d H:i:s');

        $result = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
            'expire_at' => $formattedDateTime,              
            'status' => 'In_Progess',
            'reservation_response' => json_encode($reservation_data),
        ]);
        
         $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
        
        //  if($result){
            return response()->json([
                'status' => 'success',
                'Invoice_id' => $request->invoice_no,
                'Invoice_data' => $booking_data
            ]);
        // }
        // dd($booking_data);
    }
    
    public function reject_on_request(Request $request){
        $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
        
        $reservation_data = json_decode($booking_data->reservation_response);
        
        $reservation_data->status = 'Reject';
        
        if(isset($reservation_data->hotel_details->rooms)){
            foreach($reservation_data->hotel_details->rooms as $index => $room_res){
                $reservation_data->hotel_details->rooms[$index]->room_stutus = 'Reject';
            }
        }
        // dd($reservation_data);
        $result = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
                                   
            'status' => 'Reject',
            'reservation_response' => json_encode($reservation_data),
        ]);
        
         $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
        //  if($result){
            return response()->json([
                'status' => 'success',
                'Invoice_id' => $request->invoice_no,
                'Invoice_data' => $booking_data
            ]);
        // }
        // dd($booking_data);
    }
    
    public function hotel_invoice(Request $request){
        $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
        $visa_booking_data = DB::table('visa_bookings')->where('invoice_no',$request->invoice_no)->first();
        $transfer_booking_data = DB::table('transfers_new_booking')->where('invoice_no',$request->invoice_no)->first();
        $markups = DB::table('manage_customer_markups')->where('invoice_no',$request->invoice_no)->first();
       
       dd($transfer_booking_data);
       
       $visa_type = '';
       $visa_country = '';
       if($visa_booking_data){
            $visa_avail_data = json_decode($visa_booking_data->visa_avail_data);
            $visa_type = DB::table('visa_types')->where('id',$visa_avail_data->visa_type)->select('other_visa_type')->first();
            $visa_country = DB::table('countries')->where('id',$visa_avail_data->country)->select('name')->first();
       } 
      
        
        if($booking_data || $transfer_booking_data){
            return response()->json([
                'status' => 'success',
                'booking_data' => $booking_data,
                'markups_details' => $markups,
                'visa_booking_data' => $visa_booking_data,
                'visa_type' => $visa_type,
                'visa_country' => $visa_country,
                'transfer_booking_data' => $transfer_booking_data
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => "Wrong Invoice Number",
            ]);
        }
    }
    
    function get_hotel_facilities(Request $request){
        $request_data = json_decode($request->request_data);
        
        
        $hotels_detials_data = [];
        // Get Details For Hotel Bed
        
        if($request->provider == 'hotel_beds'){
            $data = array(    
                  'case' => 'hotel_details',
                  'hotel_beds_code' => $request->hotel_code,
                  );
              
              $curl = curl_init();
              curl_setopt_array($curl, array(
              
              // CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_hotel_details_by_hotelbeds',
              CURLOPT_URL => 'https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi.php',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 1,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  $data,
              CURLOPT_HTTPHEADER => array(
              'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
              ),
              ));
              
              $response = curl_exec($curl);
            //   echo $response;die();
              $hotels_details = json_decode($response);
              
              $faclility_arr = [];
               if(isset($hotels_details->hotel->facilities)){
                    $count=1;
                    foreach($hotels_details->hotel->facilities as $facility){
                             $faclility_arr[] = $facility->description->content;
                    }
                }
              
              $hotel_address = $hotels_details->hotel->address->content ?? '';
              $hotels_detials_data = [
                    'facilities' =>$faclility_arr
                  ];
              curl_close($curl);
              return response()->json([
                    'status' => 'success',
                    'details_data' => $hotels_detials_data
                  ]);
        }
        
        if($request->provider == 'travelenda'){
            $data = array(
                            
                'case' => 'GetHotelDetails',
                'HotelId' => $request->hotel_code,
                );
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
             
            CURLOPT_URL => 'https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
            'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
            ));
            
            $response = curl_exec($curl);
            //echo $response;die();
            curl_close($curl);
            
            $hotel_detail=json_decode($response);
            
             $hotel_image = 'https://localhost/haramaynhotels/public/admin_package/frontend/images/detail_img/no-photo-available-icon-4.jpg';
             if(isset($hotel_detail->Body->Hotels->Hotel->Images)){
                 if(is_array($hotel_detail->Body->Hotels->Hotel->Images)){
                     $hotel_image = $hotel_detail->Body->Hotels->Hotel->Images->Image[0];
                 }
                 
                 if(is_object($hotel_detail->Body->Hotels->Hotel->Images)){
                     $hotel_image = $hotel_detail->Body->Hotels->Hotel->Images->Image[0] ?? '';
       
                  }
             }
             
             $faclility_arr = [];
                 if(isset($hotel_detail->Body->Hotels->Hotel->Facilities)){
                    foreach($hotel_detail->Body->Hotels->Hotel->Facilities->Facility as $facility){
                                $faclility_arr[] = $facility->FacilityName;

                    }
                }
                
                
                
                $hotel_address =  $hotel_detail->Body->Hotels->Hotel->Address ?? '';
                $hotels_detials_data = [
                    'facilities' =>$faclility_arr
                  ];
                  
              return response()->json([
                'status' => 'success',
                'details_data' => $hotels_detials_data
              ]);
             
        }
    }
    
    function hotel_booking_cancel(Request $request){
        $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
        $reservation_response = json_decode($booking_data->reservation_response);
        
        if($booking_data){
            
            // Cancilation For Travelenda 
            if($booking_data->provider == 'travelenda'){
                function travelandaCancelBooking($ref_no){
                      $url = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                      $data = array('case' => 'HotelBookingCancel','id' => $ref_no);
                     
                     Session::put('travelenda_booking_request',$data);
                       $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_POST, true);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                      
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      $responseData = curl_exec($ch);
                      //echo $responseData;die();
                      if (curl_errno($ch)) {
                          return curl_error($ch);
                      }
                      curl_close($ch);
                      return $responseData;
                 }
                 
                 
                 
                 
      
                  $responseData3 = travelandaCancelBooking($reservation_response->reference_no ?? '');
                  $responseData3 = json_decode($responseData3);
                  
                  $cancilation_status = '';
                  if(isset($responseData3->Body->HotelBooking)){
                      if(isset($responseData3->Body->HotelBooking->RequestStatus)){
                          $cancilation_status = "Cancellation Pending";
                      }else if($responseData3->Body->HotelBooking->BookingStatus == 'Cancelled'){
                          $cancilation_status = "Cancelled";
                      }
                  }else{
                      $cancilation_status = $booking_data->status;
                  }
                  
                  
                    $reservation_response->status = $cancilation_status;
                    
                    foreach($reservation_response->hotel_details->rooms as $index => $reserv_room_res){
                        $reservation_response->hotel_details->rooms[$index]->room_stutus = $cancilation_status;
                    }
                    
                    
                    $result  = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
                                    'status' => $cancilation_status,
                                    'reservation_response' => json_encode($reservation_response)
                                ]);
                    
                                
                    return response()->json([
                        'status' => 'success',
                        'cancilation_status' =>$cancilation_status,
                    ]);
                    
            }
            
            if($booking_data->provider == 'hotel_beds'){
                 function cancellation_booking($id){
                     
                      $url = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi.php";
                      $data = array('case' => 'cancellation_booking', 'refrence_id' => $id);
                       $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_POST, true);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                      
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      $responseData = curl_exec($ch);
                      //echo $responseData;die();
                      if (curl_errno($ch)) {
                          return curl_error($ch);
                      }
                      curl_close($ch);
                      return $responseData;
                  }
          
                    // dd($reservation_response->reference_no);
                  $responseData3 = cancellation_booking($reservation_response->reference_no ?? '');
                  $result_booking= json_decode($responseData3);  
                  
                  $cancilation_status = $booking_data->status;
                  if(isset($result_booking->booking->status)){
                      if($result_booking->booking->status == 'CANCELLED'){
                          $cancilation_status = 'Cancelled';
                          
                          $reservation_response->status = $cancilation_status;
                    
                            foreach($reservation_response->hotel_details->rooms as $index => $reserv_room_res){
                                $reservation_response->hotel_details->rooms[$index]->room_stutus = $cancilation_status;
                            }
                            
                            
                            $result  = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
                                            'status' => $cancilation_status,
                                            'reservation_response' => json_encode($reservation_response),
                                            'cancelination_response' => json_encode($result_booking)
                                        ]);
                            
                                        
                            return response()->json([
                                'status' => 'success',
                                'cancilation_status' =>$cancilation_status,
                            ]);
                      }
                  }
                  
                  if(isset($result_booking->error)){
                      return response()->json([
                            'status' => 'error',
                            'cancilation_status' => $result_booking->error->message,
                        ]);
                  }
                //   dd($result_booking);
                //   die;
            }
            if($booking_data->provider == 'Custome_hotel'){
             $result  = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
                                            'status' => 'Cancelled',
                                            'cancelled_date'=>date('Y-m-d'),
                                            'cancelination_response' => ''
                                        ]);
            return response()->json([
                            'status' => 'success'
                        ]);                            
            }
            
            
        }else{
            return response()->json([
                'status' => 'error',
                'message' => "Wrong Invoice Number",
            ]);
        }
    }
    
    // Admin Panel Functions
    
    function custom_hotel_markup(Request $request){
        $custom_hotel_markup_type = DB::table('custom_hotel_markup_type')->where('customer_id',$request->customer_id)->first();
        $custom_hotel_markup = DB::table('custom_hotel_markups')->where('customer_id',$request->customer_id)->get();
        $custom_hotel_vendor_markup = DB::table('custom_hotels_vendors_markup')->where('hotel_owner',$request->customer_id)->get();
        $customer_hotels = DB::table('hotels')->where('owner_id',$request->customer_id)->get();
        return response()->json([
                        'status' => 'success',
                        'custom_hotel_markup_type' => $custom_hotel_markup_type,
                        'custom_hotel_markup' => $custom_hotel_markup,
                        'custom_hotel_vendor_markup' => $custom_hotel_vendor_markup,
                        'customer_hotels' => $customer_hotels
                    ]);
        
    }
    function get_hotels_booking(Request $request)
    {
     $booking=DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
     $markup = DB::table('manage_customer_markups')->where('invoice_no',$request->invoice_no)->first();
     return response()->json(['booking' => $booking,'markup' => $markup]);
    }
     function booking_refundable_update(Request $request)
    {
     $booking=DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
         'payment_refunds'=>$request->payment_refunds,
         ]); 
        return response()->json(['booking' => $booking,]);
    }
    
function hotels_checkout_submit_admin(Request $request){
        $hotel_request_data = json_decode($request->request_data);
        $hotel_checkout_select = json_decode($request->hotel_checkout_select);
        $customer_search_data = json_decode($request->customer_search_data);
        
        //print_r($hotel_request_data);die();

                $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                //print_r($userData->id);die();
                $booking_customer_id = "";
                $customer_exist = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$hotel_request_data->lead_email)->first();
                if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                        $booking_customer_id = $customer_exist->id;
                }else{
                   
                   if($hotel_request_data->lead_title == "Mr"){
                       $gender = 'male';
                   }else{
                        $gender = 'female';
                   }
                    
                    $password = Hash::make('admin123');
                    
                    $customer_detail                    = new booking_customers();
                    $customer_detail->name              = $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name;
                    $customer_detail->opening_balance   = 0;
                    $customer_detail->balance           = 0;
                    $customer_detail->email             = $hotel_request_data->lead_email;
                    $customer_detail->password             = $password;
                    $customer_detail->phone             = $hotel_request_data->lead_phone;
                    $customer_detail->gender            = $gender;
                    $customer_detail->country           = $hotel_request_data->lead_country;
    
                    $customer_detail->customer_id       = $userData->id;
                    $result = $customer_detail->save();
                    
                    $booking_customer_id = $customer_detail->id;
    
                    
                }
                
         // ************************************************************
        // Custom Hotel Reservation
        // ************************************************************
        if($hotel_checkout_select->hotel_provider  == 'Custome_hotel'){
            if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
           if($userData->id == 28)
            {
             $randomNumber = random_int(1000000, 9999999);
             $invoiceId =  "HH".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
            //   dd($request->all());
            DB::beginTransaction();
        
            try {   
                    if(isset($hotel_checkout_select->rooms_list)){
                        
                        foreach($hotel_checkout_select->rooms_list as $room_res){
                            if($room_res->request_type == '0'){
                                $room_data = DB::table('rooms')->where('id',$room_res->booking_req_id)->first();
                                    if($room_data){
        
                                        // Update Room Data
                                        $rooms_qty = $room_res->rooms_qty;
                                        $total_booked = $room_data->booked + $rooms_qty;
                
                                         DB::table('rooms_bookings_details')->insert([
                                             'room_id'=> $room_res->booking_req_id,
                                             'booking_from'=>'website',
                                             'quantity'=>$rooms_qty,
                                             'booking_id'=>$invoiceId,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$hotel_checkout_select->checkIn,
                                             'check_out'=>$hotel_checkout_select->checkOut,
                                         ]);
                                         
                               
                                        DB::table('rooms')->where('id',$room_res->booking_req_id)->update(['booked'=>$total_booked]);
                                        
                                        
                                        // Update Hotel Supplier Balance
                                
                                         $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
               
                                         if(isset($supplier_data)){
                                                // echo "Enter hre ";
                                                $total_price = $room_res->rooms_total_price;
                                               
                                                // echo "The supplier Balance is ".$supplier_data->balance;
                                                $supplier_balance = $supplier_data->balance;
                                                $supplier_payable_balance = $supplier_data->payable + $total_price;
                                                
                                                // update Agent Balance
                                                
                                                DB::table('hotel_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$total_price,
                                                    'balance'=>$supplier_balance,
                                                    'payable_balance'=>$supplier_payable_balance,
                                                    'room_id'=>$room_data->id,
                                                    'customer_id'=>$userData->id,
                                                    'date'=>date('Y-m-d'),
                                                    'website_booking_id'=>$invoiceId,
                                                    'available_from'=>$hotel_checkout_select->checkIn,
                                                    'available_to'=>$hotel_checkout_select->checkOut,
                                                    'room_quantity'=>$room_res->rooms_qty,
                                                    ]);
                                                    
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                    'balance'=>$supplier_balance,
                                                    'payable'=>$supplier_payable_balance
                                                    ]);                      
                                            }
                                            
                                       
        
                                    }
                            }
                        }
                
                $room_book_status = '';
                if(isset($hotel_checkout_select->rooms_list[0])){
                    if($hotel_checkout_select->rooms_list[0]->request_type == '0' || empty($hotel_checkout_select->rooms_list[0]->request_type)){
                        $room_book_status = 'Confirmed';
                    }else{
                        $room_book_status = 'Room On Request';
                    }
                }
                 
                 // Booking Save Working
                                

                                      $rooms_details_arr = [];
                                      if(isset($hotel_checkout_select->rooms_list)){
                                          foreach($hotel_checkout_select->rooms_list as $room_res){
                                              
                                                     // Room Rates Arr
                                                      $room_rate_arr = [];
                    
                                                                // Rooms Cancilation Policy
                                                               $cancliation_policy_arr = $room_res->cancliation_policy_arr;
                                                          
                                                              
                                                              $room_rate_arr[] = (Object)[
                                                                    'rateClass' => '',
                                                                    'net' => $room_res->rooms_total_price,
                                                                    'rateComments' => '',
                                                                    'room_board' => $room_res->board_id,
                                                                    'room_qty' => 1,
                                                                    'adults' => $room_res->adults,
                                                                    'children' => $room_res->childs,
                                                                    'cancellation_policy' => $cancliation_policy_arr,
                                                                  ];
                                                          
                                                      
                                              
                                                      $rooms_details_arr[] = (Object)[
                                                                    'room_stutus' => $room_book_status,
                                                                    'room_code' => $room_res->booking_req_id,
                                                                    'room_name' => $room_res->room_name,
                                                                    'room_paxes' => [],
                                                                    'adults' => $room_res->adults,
                                                                    'childs' => $room_res->childs,
                                                                    'room_rates' => $room_rate_arr,
                                                                  ];
                                                
                                             
                                          }
                                          
                                      }
                                      
                                      $hotel_checkout_request_d = json_decode($request->hotel_checkout_select);
                                      
                                        $hotel_booking_conf_res = (Object)[
                                                'provider' => 'Custome_hotel',
                                                'reference_no' => $invoiceId,
                                                'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                                'total_price' => $hotel_checkout_select->total_price,
                                                'hotel_currency' => $hotel_checkout_select->currency,
                                                'clientReference' => '',
                                                'creationDate' => date('Y-m-d'),
                                                'status' => $room_book_status,
                                                'lead_passenger' => $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name,
                                                'hotel_details' =>(Object)[
                                                        'checkIn' => $hotel_checkout_select->checkIn,
                                                        'checkOut' => $hotel_checkout_select->checkOut,
                                                        'hotel_code' => $hotel_checkout_select->hotel_id,
                                                        'hotel_name' => $hotel_checkout_select->hotel_name,
                                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                                        'destinationCode' => $hotel_checkout_select->destinationCode,
                                                        'destinationName' => $hotel_checkout_select->destinationName,
                                                        'latitude' => $hotel_checkout_select->latitude,
                                                        'longitude' => $hotel_checkout_select->longitude,
                                                        'rooms' => $rooms_details_arr
                                                    ]
                                            ];
                                            
                                            $invoiceId =  $invoiceId;
                                            
                                            $customer_id = '';
                                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                                            if($userData){
                                                $customer_id = $userData->id;
                                            }
                                            
                                               $lead_passenger_object = (Object)[
                                                    'lead_title' =>$hotel_request_data->lead_title,
                                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                                    'lead_email' =>$hotel_request_data->lead_email,
                                                    'lead_country' => $hotel_request_data->lead_country, 
                                                ];
                                                
                                                $others_adults = [];
                                                
                                                if(isset($hotel_request_data->other_title)){
                                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                                        $others_adults[] = (Object)[
                                                                'title' => $other_res,
                                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                                            ];
                                                    }
                                                }
                                                
                                                $childs = [];
                                                if(isset($hotel_request_data->child_title)){
                                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                                        $childs[] = (Object)[
                                                                'title' => $other_res,
                                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                                            ];
                                                    }
                                                }
                                            
                                            
                                            
                                            $result = DB::table('hotels_bookings')->insert([
                                                    'invoice_no' => $invoiceId,
                                                    'booking_customer_id' => $booking_customer_id,
                                                    'provider' => $hotel_booking_conf_res->provider,
                                                    'exchange_currency' => $request->exchange_currency,
                                                    'exchange_price' => $request->exchange_price,
                                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                                    'base_currency'=>$request->base_currency,
                                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                                    'selected_currency'=>$request->selected_currency,
                                                    'GBP_currency'=>$request->admin_exchange_currency,
                                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                                    'creationDate' =>  date('Y-m-d'),
                                                    'status' => $room_book_status,
                                                    'lead_passenger' => $hotel_booking_conf_res->lead_passenger,
                                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                                    'other_adults_data' => json_encode($others_adults),
                                                    'childs_data' => json_encode($childs),
                                                    'total_adults' => $customer_search_data->adult_searching,
                                                    'total_childs' => $customer_search_data->child_searching,
                                                    'total_rooms' => $customer_search_data->room_searching,
                                                    'reservation_request' => json_encode($request->all()),
                                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                                    'actual_reservation_response' => '',
                                                    'customer_id' => $customer_id,
                                                    'booked'=>'admin',
                                                    'commision_charges'=>$request->commision_charges ?? '',
                                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
                                                ]);
                                                    
                                                    
                                                if($room_book_status == 'Confirmed'){
                                                    
                                                    //added code by jamshaid cheena 08-06-2023
                                                    $client_markup=$request->client_markup;
                                                    $admin_markup=$request->admin_markup;
                                                    $client_markup_type=$request->client_markup_type;
                                                    $admin_markup_type=$request->admin_markup_type;
                                                    $payable_price=$request->admin_commission_amount_orignial;
                                                    $client_commission_amount=$request->client_commission_amount;
                                                    
                                                    $total_markup_price=$request->total_markup_price;
                                                    $currency=$request->currency;
                                                    $exchange_client_commission_amount=$request->exchange_client_commission_amount;                                         
                                                    $exchange_payable_price=$request->exchange_admin_commission_amount;                                          
                                                    $exchange_admin_commission_amount=$request->exchange_admin_commission_amount; 
                                                    $exchange_total_markup_price=$request->exchange_total_markup_price; 
                                                    $exchange_currency=$request->exchange_currency; 
                                                    $exchange_rate=$request->exchange_rate; 
                                        
                                                    $admin_exchange_amount=$request->admin_commission_amount;
                                                    $admin_commission_amount=$request->admin_commission_amount;
                                                    $admin_exchange_currency=$request->admin_exchange_currency;
                                                    $admin_exchange_rate=$request->admin_exchange_rate;
                                                    $admin_exchange_total_markup_price=$request->admin_exchange_total_markup_price;
                                        
                                        
                                                    $price=$request->exchange_price;
                                                    $p_price=json_decode($price);
                        
                
                                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                                     //print_r($price_with_out_commission);die();
                                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                                        
                                                        'token'=>$request->token,
                                                        'invoice_no'=>$invoiceId,
                                                        'received_amount'=>$exchange_payable_price,
                                                        'balance_amount'=>$big_exchange_payable_price,
                                                        'type'=>'hotel_booking'
                                                        ]);
                                                    
                                                    
                                                    
                                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                                        
                                                        'token'=>$request->token,
                                                        'invoice_no'=>$invoiceId,
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
                                                        
                                                        'payment_transction_id'=> $invoiceId,
                                                        'provider'=> 'Custome_hotel',
                                                        'payment_remaining_amount'=>$add_price,
                                                        
                                                        
                                                        ]);
                                                   //ended code by jamshaid cheena 08-06-2023
                                                }
            }
            
                     DB::commit();
                     
                    $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                     
                    return response()->json([
                        'status' => 'success',
                        'Invoice_id' => $invoiceId,
                        'Invoice_data' => $booking_data
                    ]);
                
            
             } catch (Throwable $e) {
                 DB::rollback();
                echo $e;
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
            
        }
                
        // ************************************************************
        // Hotels Bed Reservation
        // ************************************************************
        if($hotel_checkout_select->hotel_provider  == 'hotel_beds'){
            
            $current_date = date('Y-m-d');
            $cancilation_date = date('Y-m-d',strtotime($hotel_checkout_select->rooms_list[0]->cancliation_policy_arr[0]->from_date ?? ''));
           
              $cancilation_date = Carbon::parse($cancilation_date);
             $current1 = Carbon::parse($current_date);
            if($cancilation_date > $current1){
               // Refundable Booking
               
                  function confirmbooking($hotel_request_data,$hotel_checkout_select){
                  $url = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi.php";
                  $data = array('case' => 'new_confirmbooking', 
                                  'hotel_request_data' => json_encode($hotel_request_data),
                                  'hotel_checkout_select'=>json_encode($hotel_checkout_select));
                  Session::put('hotelbeds_booking_rq',$data);
                   $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $url);
                  curl_setopt($ch, CURLOPT_POST, true);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                  
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $responseData = curl_exec($ch);
                //   echo $responseData;die();
                  if (curl_errno($ch)) {
                      return curl_error($ch);
                  }
                  curl_close($ch);
                  return $responseData;
              }
          
                  $responseData3 = confirmbooking($hotel_request_data,$hotel_checkout_select);
                  $responseData3 = json_decode($responseData3);
                  $hotel_request_send = json_decode($responseData3->request);
                  $result_booking_rs = json_decode($responseData3->response);
                  
                //   $result_booking_rs = json_decode($hotel_response);
                //   dd($result_booking_rs);
                  //print_r($result_booking_rs);die();
               
                  if(isset($result_booking_rs->booking)){
                      
                      $rooms_details_arr = [];
                      if(isset($result_booking_rs->booking->hotel->rooms)){
                          foreach($result_booking_rs->booking->hotel->rooms as $room_res){
                              
                              // Paxes Arr
                              $paxes_arr = [];
                              if(isset($room_res->paxes)){
                                  foreach($room_res->paxes as $pax_res){
                                      $type = '';
                                      if($pax_res->type == 'AD'){
                                          $type = 'Adult';
                                      }
                                      
                                      if($pax_res->type == 'CH'){
                                          $type = 'Child';
                                      }
                                      
                                      $paxes_arr[] = [
                                            'type' => $type,
                                            'name' => $pax_res->name." ".$pax_res->surname,
                                          ];
                                  }
                              }
                              
                              // Room Rates Arr
                              $room_rate_arr = [];
                              if(isset($room_res->rates)){
                                  foreach($room_res->rates as $rate_res){
                                      
                                        // Rooms Cancilation Policy
                                       $cancliation_policy_arr = [];
                                       if(isset($rate_res->cancellationPolicies)){
                                           foreach($rate_res->cancellationPolicies as $cancel_res){
                                               $cancel_tiem = (Object)[
                                                    'amount'=> $cancel_res->amount,
                                                    'from_date'=> $cancel_res->from,
                                                   ];
                                               $cancliation_policy_arr[] = $cancel_tiem;
                                           }
                                       }
                                      
                                      $room_rate_arr[] = (Object)[
                                            'rateClass' => $rate_res->rateClass,
                                            'net' => $rate_res->net,
                                            'rateComments' => $rate_res->rateComments ?? '',
                                            'room_board' => $rate_res->boardName,
                                            'room_qty' => $rate_res->rooms,
                                            'adults' => $rate_res->adults,
                                            'children' => $rate_res->children,
                                            'cancellation_policy' => $cancliation_policy_arr,
                                          ];
                                  }
                              }
                              
                              $rooms_details_arr[] = (Object)[
                                            'room_stutus' => $room_res->status,
                                            'room_code' => $room_res->code,
                                            'room_name' => $room_res->name,
                                            'room_paxes' => $paxes_arr,
                                            'room_rates' => $room_rate_arr,
                                          ];
                          }
                          
                      }
                      
                      $hotel_checkout_request_d = json_decode($request->hotel_checkout_select);
                        $hotel_booking_conf_res = (Object)[
                                'provider' => 'hotel_beds',
                                'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                'reference_no' => $result_booking_rs->booking->reference,
                                'total_price' => $result_booking_rs->booking->totalNet,
                                'hotel_currency' => $result_booking_rs->booking->currency,
                                'clientReference' => $result_booking_rs->booking->clientReference,
                                'creationDate' => $result_booking_rs->booking->creationDate,
                                'status' => $result_booking_rs->booking->status,
                                'lead_passenger' => $result_booking_rs->booking->holder->name." ".$result_booking_rs->booking->holder->surname,
                                'status' => $result_booking_rs->booking->status,
                                'hotel_details' =>(Object)[
                                        'checkIn' => $result_booking_rs->booking->hotel->checkIn,
                                        'checkOut' => $result_booking_rs->booking->hotel->checkOut,
                                        'hotel_code' => $result_booking_rs->booking->hotel->code,
                                        'hotel_name' => $result_booking_rs->booking->hotel->name,
                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                        'destinationCode' => $result_booking_rs->booking->hotel->destinationCode,
                                        'destinationName' => $result_booking_rs->booking->hotel->destinationName,
                                        'latitude' => $result_booking_rs->booking->hotel->latitude,
                                        'longitude' => $result_booking_rs->booking->hotel->longitude,
                                        'rooms' => $rooms_details_arr
                                    ]
                            ];
                            
            if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            if($userData->id == 28)
            {
             $randomNumber = random_int(1000000, 9999999);
             $invoiceId =  "HH".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                            
                            $customer_id = '';
                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                            if($userData){
                                $customer_id = $userData->id;
                            }
                            
                               $lead_passenger_object = (Object)[
                                    'lead_title' =>$hotel_request_data->lead_title,
                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                    'lead_email' =>$hotel_request_data->lead_email,
                                    'lead_country' => $hotel_request_data->lead_country, 
                                ];
                                
                                $others_adults = [];
                                
                                if(isset($hotel_request_data->other_title)){
                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                        $others_adults[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                            ];
                                    }
                                }
                                
                                $childs = [];
                                if(isset($hotel_request_data->child_title)){
                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                        $childs[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                            ];
                                    }
                                }
                            
                            // dd($hotel_booking_conf_res);
                             try {
                            $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no' => $invoiceId,
                                    'provider' => $hotel_booking_conf_res->provider,
                                    'booking_customer_id' => $booking_customer_id,
                                    'exchange_currency' => $request->exchange_currency_customer,
                                    'exchange_price' => $request->exchange_price,
                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                    'base_currency'=>$request->base_currency,
                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                    'selected_currency'=>$request->selected_currency,
                                    'GBP_currency'=>$request->admin_exchange_currency,
                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                    'creationDate' => $hotel_booking_conf_res->creationDate,
                                    'status' => $hotel_booking_conf_res->status,
                                    'lead_passenger' => $hotel_booking_conf_res->lead_passenger,
                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                    'other_adults_data' => json_encode($others_adults),
                                    'childs_data' => json_encode($childs),
                                    'status' => $hotel_booking_conf_res->status,
                                    'total_adults' => $customer_search_data->adult_searching,
                                    'total_childs' => $customer_search_data->child_searching,
                                    'total_rooms' => $customer_search_data->room_searching,
                                    'reservation_request' => json_encode($hotel_request_send),
                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                    'customer_id' => $customer_id,
                                    'payment_details'=>$request->payment_details,
                                    'booked'=>'admin',
                                    'commision_charges'=>$request->commision_charges,
                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
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
                                    $exchange_admin_commission_amount=$request->admin_commission_amount; 
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
        

                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                     //print_r($price_with_out_commission);die();
                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
                                        'received_amount'=>$exchange_payable_price,
                                        'balance_amount'=>$big_exchange_payable_price,
                                        'type'=>'hotel_booking'
                                        ]);
                                    
                                    
                                    
                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
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
                                        
                                        'payment_transction_id'=> $invoiceId,
                                        'provider'=> 'hotelbeds',
                                        'payment_remaining_amount'=>$add_price,
                                        
                                        
                                        ]);
                                        
                                        
                                        
                                        
                                         $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                                        $credit_data = DB::table('credit_limits')->where('customer_id',$customer_get_data->id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
                                         $ramainAmount=$credit_data->remaining_amount - $request->creditAmount;
                                         
                                        $credit_limits = DB::table('credit_limits')->insert([
                                            'transection_id'=>$invoiceId,
                                            'customer_id'=>$customer_get_data->id,
                                                'amount'=>$request->creditAmount,
                                                'total_amount'=>$credit_data->total_amount,
                                                'remaining_amount'=>$ramainAmount,
                                                'currency'=>$credit_data->currency,
                                                'status'=>'1',
                                                'status_type'=>'approved',
                                            
                                            ]);
                                        
                                       $credit_limits = DB::table('credit_limit_transections')->insert([
                                        'invoice_no'=> $invoiceId,
                                        'customer_id'=>$customer_get_data->id,
                                        'transection_amount'=>$request->creditAmount,
                                        'remaining_amount'=>$ramainAmount,
                                        'type'=>'booked',
                                        ]);
                                        
                                        
                                   //ended code by jamshaid cheena 08-06-2023
                                    $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                                if($result){
                                    return response()->json([
                                        'status' => 'success',
                                        'Invoice_id' => $invoiceId,
                                        'Invoice_data' => $booking_data
                                    ]);
                                }
                            
                             } catch (Throwable $e) {
                                 DB::rollback();
                                echo $e;
                                return response()->json(['message'=>'error','booking_id'=> '']);
                            }
                            
    
                  }
                  
                  else
                  {
                   
                   
                            if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            if($userData->id == 28)
            {
             $randomNumber = random_int(1000000, 9999999);
             $invoiceId =  "HH".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                            
                            $customer_id = '';
                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                            if($userData){
                                $customer_id = $userData->id;
                            }
                            
                               $lead_passenger_object = (Object)[
                                    'lead_title' =>$hotel_request_data->lead_title,
                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                    'lead_email' =>$hotel_request_data->lead_email,
                                    'lead_country' => $hotel_request_data->lead_country, 
                                ];
                                
                                $others_adults = [];
                                
                                if(isset($hotel_request_data->other_title)){
                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                        $others_adults[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                            ];
                                    }
                                }
                                
                                $childs = [];
                                if(isset($hotel_request_data->child_title)){
                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                        $childs[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                            ];
                                    }
                                }
                                
                                $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no' => $invoiceId,
                                    'provider' => '',
                                    'booking_customer_id' => $booking_customer_id,
                                    'exchange_currency' => $request->exchange_currency_customer,
                                    'exchange_price' => $request->exchange_price,
                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                    'base_currency'=>$request->base_currency,
                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                    'selected_currency'=>$request->selected_currency,
                                    'GBP_currency'=>$request->admin_exchange_currency,
                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                    'creationDate' => '',
                                    'status' => 'Failed',
                                    'lead_passenger' => '',
                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                    'other_adults_data' => json_encode($others_adults),
                                    'childs_data' => json_encode($childs),
                                    'status' => 'Failed',
                                    'total_adults' => $customer_search_data->adult_searching,
                                    'total_childs' => $customer_search_data->child_searching,
                                    'total_rooms' => $customer_search_data->room_searching,
                                    'reservation_request' => json_encode($hotel_request_send),
                                    'reservation_response' => $result_booking_rs->error->message,
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                    'customer_id' => $customer_id,
                                    'payment_details'=>$request->payment_details,
                                    'booked'=>'admin',
                                    'commision_charges'=>$request->commision_charges,
                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
                                ]);
                                
                   return response()->json([
                            'status' => 'error',
                            'message' => $result_booking_rs->error->message
                        ]);   
                  }
                //   if($result_booking_rs->error){
                //       return response()->json([
                //             'status' => 'error',
                //             'message' => $result_booking_rs->error->message
                //         ]);
                //   }
              
            }else{
               // Non Refundable Booking
               
              
                         if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            if($userData->id == 28)
            {
             $randomNumber = random_int(1000000, 9999999);
             $invoiceId =  "HH".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                        
                        $customer_id = '';
                        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                        if($userData){
                            $customer_id = $userData->id;
                        }
                        
                           $lead_passenger_object = (Object)[
                                'lead_title' =>$hotel_request_data->lead_title,
                                'lead_first_name' =>$hotel_request_data->lead_first_name,
                                'lead_last_name' =>$hotel_request_data->lead_last_name,
                                'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                'lead_phone' =>$hotel_request_data->lead_phone,
                                'lead_email' =>$hotel_request_data->lead_email,
                                'lead_country' => $hotel_request_data->lead_country, 
                            ];
                            
                            $others_adults = [];
                            
                            if(isset($hotel_request_data->other_title)){
                                foreach($hotel_request_data->other_title as $index => $other_res){
                                    $others_adults[] = (Object)[
                                            'title' => $other_res,
                                            'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                            'nationality' => $hotel_request_data->other_nationality[$index],
                                        ];
                                }
                            }
                            
                            $childs = [];
                            if(isset($hotel_request_data->child_title)){
                                foreach($hotel_request_data->child_title as $index => $other_res){
                                    $childs[] = (Object)[
                                            'title' => $other_res,
                                            'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                            'nationality' => $hotel_request_data->child_nationality[$index],
                                        ];
                                }
                            }
                        
                        // dd($hotel_booking_conf_res);
                         try {
                        $result = DB::table('hotels_bookings')->insert([
                                'invoice_no' => $invoiceId,
                                'booking_customer_id' => $booking_customer_id,
                                'provider' => $hotel_checkout_select->hotel_provider,
                                'exchange_currency' => $request->exchange_currency,
                                'exchange_price' => $request->exchange_price,
                                'base_exchange_rate'=>$request->base_exchange_rate,
                                'base_currency'=>$request->base_currency,
                                'selected_exchange_rate'=>$request->selected_exchange_rate,
                                'selected_currency'=>$request->selected_currency,
                                'GBP_currency'=>$request->admin_exchange_currency,
                                'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                'creationDate' => date('Y-m-d'),
                                'status' => "non_refundable",
                                'lead_passenger' => $lead_passenger_object->lead_first_name." ".$lead_passenger_object->lead_last_name,
                                'lead_passenger_data' => json_encode($lead_passenger_object),
                                'other_adults_data' => json_encode($others_adults),
                                'childs_data' => json_encode($childs),
                                'total_adults' => $customer_search_data->adult_searching,
                                'total_childs' => $customer_search_data->child_searching,
                                'total_rooms' => $customer_search_data->room_searching,
                                'reservation_request' => '',
                                'reservation_response' => '',
                                'all_checkout_request_data'=> json_encode($request->all()),
                                'hotel_request_data' => json_encode($hotel_request_data),
                                'hotel_data' => json_encode($hotel_checkout_select),
                                'customer_id' => $customer_id,
                                'booked'=>'admin',
                               'commision_charges'=>$request->commision_charges,
                               'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
                            ]);
                            
                            $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                                
                            if($result){
                                return response()->json([
                                    'status' => 'success_non_refundable',
                                    'Invoice_id' => $invoiceId,
                                    'Invoice_data' => $booking_data
                                ]);
                            }
                        
                         } catch (Throwable $e) {
                            echo $e;
                            return response()->json(['message'=>'error','booking_id'=> '']);
                        }   
               

            }
            
            // die;
            
            

        }
        
        
        
        if($hotel_checkout_select->hotel_provider  == 'travelenda'){
            
            $current_date = date('Y-m-d');
            $cancilation_date = date('Y-m-d',strtotime($hotel_checkout_select->rooms_list[0]->cancliation_policy_arr[0]->from_date ?? ''));
           
              $cancilation_date = Carbon::parse($cancilation_date);
             $current1 = Carbon::parse($current_date);
            if($cancilation_date > $current1){
                
                function travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select){
                      $url = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                      $data = array('case' => 'travelandaCnfrmBookingnew','hotel_request_data' => json_encode($hotel_request_data),
                                          'hotel_checkout_select'=>json_encode($hotel_checkout_select));
                     
                     Session::put('travelenda_booking_request',$data);
                       $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_POST, true);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                      
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      $responseData = curl_exec($ch);
                      //echo $responseData;die();
                      if (curl_errno($ch)) {
                          return curl_error($ch);
                      }
                      curl_close($ch);
                      return $responseData;
                 }
      
                  $responseData3 = travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select);
                
                  $responseData3 = json_decode($responseData3);
              
                  $request_xml = simplexml_load_string($responseData3->request);
                  $hotel_request_send = json_encode($request_xml);
                    // print_r($hotel_request_send);
                    // die;
                  $result_booking_rs = json_decode($responseData3->response);
                  
                //   print_r($result_booking_rs);
                //   die;
                //   $result_booking_rs = json_decode($hotel_response);
                //   dd($result_booking_rs);
                  
                  if(isset($result_booking_rs->Body->HotelBooking)){
                      
                      $rooms_details_arr = [];
                      if(isset($hotel_checkout_select->rooms_list)){
                          foreach($hotel_checkout_select->rooms_list as $room_res){
                         
                              
                             if(isset($room_res->rooms_list)){
                                foreach($room_res->rooms_list as $room_option_res){
                                    
                                     // Room Rates Arr
                                      $room_rate_arr = [];
    
                                                // Rooms Cancilation Policy
                                               $cancliation_policy_arr = $room_res->cancliation_policy_arr;
                                          
                                              
                                              $room_rate_arr[] = (Object)[
                                                    'rateClass' => '',
                                                    'net' => $room_option_res->room_price,
                                                    'rateComments' => '',
                                                    'room_board' => $room_res->board_id,
                                                    'room_qty' => 1,
                                                    'adults' => $room_option_res->adults,
                                                    'children' => $room_option_res->childs,
                                                    'cancellation_policy' => $cancliation_policy_arr,
                                                  ];
                                          
                                      
                              
                                      $rooms_details_arr[] = (Object)[
                                                    'room_stutus' => "Confirmed",
                                                    'room_code' => $room_option_res->room_id,
                                                    'room_name' => $room_option_res->room_name,
                                                    'room_paxes' => [],
                                                    'adults' => $room_option_res->adults,
                                                    'childs' => $room_option_res->childs,
                                                    'room_rates' => $room_rate_arr,
                                                  ];
                                }
                             }
                          }
                          
                      }
                      
                      $hotel_checkout_request_d = json_decode($request->hotel_checkout_select);
                        $hotel_booking_conf_res = (Object)[
                                'provider' => 'travelenda',
                                 'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                'reference_no' => $result_booking_rs->Body->HotelBooking->BookingReference,
                                'total_price' => $result_booking_rs->Body->HotelBooking->TotalPrice,
                                'hotel_currency' => $result_booking_rs->Body->HotelBooking->Currency,
                                'clientReference' => $result_booking_rs->Body->HotelBooking->YourReference,
                                'creationDate' => $result_booking_rs->Head->ServerTime,
                                'status' => $result_booking_rs->Body->HotelBooking->BookingStatus,
                                'lead_passenger' => $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name,
                                'hotel_details' =>(Object)[
                                        'checkIn' => $hotel_checkout_select->checkIn,
                                        'checkOut' => $hotel_checkout_select->checkOut,
                                        'hotel_code' => $hotel_checkout_select->hotel_id,
                                        'hotel_name' => $hotel_checkout_select->hotel_name,
                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                        'destinationCode' => $hotel_checkout_select->destinationCode,
                                        'destinationName' => $hotel_checkout_select->destinationName,
                                        'latitude' => $hotel_checkout_select->latitude,
                                        'longitude' => $hotel_checkout_select->longitude,
                                        'rooms' => $rooms_details_arr
                                    ]
                            ];
                            
                             if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
           if($userData->id == 28)
            {
             $randomNumber = random_int(1000000, 9999999);
             $invoiceId =  "HH".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                            
                            $customer_id = '';
                            $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                            if($userData){
                                $customer_id = $userData->id;
                            }
                            
                               $lead_passenger_object = (Object)[
                                    'lead_title' =>$hotel_request_data->lead_title,
                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                    'lead_email' =>$hotel_request_data->lead_email,
                                    'lead_country' => $hotel_request_data->lead_country, 
                                ];
                                
                                $others_adults = [];
                                
                                if(isset($hotel_request_data->other_title)){
                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                        $others_adults[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                            ];
                                    }
                                }
                                
                                $childs = [];
                                if(isset($hotel_request_data->child_title)){
                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                        $childs[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                            ];
                                    }
                                }
                            
                            // dd($hotel_booking_conf_res);
                             try {
                            $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no' => $invoiceId,
                                    'booking_customer_id' => $booking_customer_id,
                                    'provider' => $hotel_booking_conf_res->provider,
                                    'exchange_currency' => $request->exchange_currency,
                                    'exchange_price' => $request->exchange_price,
                                    'base_exchange_rate'=>$request->base_exchange_rate,
                                    'base_currency'=>$request->base_currency,
                                    'selected_exchange_rate'=>$request->selected_exchange_rate,
                                    'selected_currency'=>$request->selected_currency,
                                    'GBP_currency'=>$request->admin_exchange_currency,
                                    'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                    'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                    'creationDate' =>  $result_booking_rs->Head->ServerTime,
                                    'status' => $result_booking_rs->Body->HotelBooking->BookingStatus,
                                    'lead_passenger' => $hotel_booking_conf_res->lead_passenger,
                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                    'other_adults_data' => json_encode($others_adults),
                                    'childs_data' => json_encode($childs),
                                    'total_adults' => $customer_search_data->adult_searching,
                                    'total_childs' => $customer_search_data->child_searching,
                                    'total_rooms' => $customer_search_data->room_searching,
                                    'reservation_request' => $hotel_request_send,
                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                    'customer_id' => $customer_id,
                                    'booked'=>'admin',
                                    'commision_charges'=>$request->commision_charges,
                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
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
                                    $exchange_admin_commission_amount=$request->admin_commission_amount; 
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
        

                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                     //print_r($price_with_out_commission);die();
                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
                                        'received_amount'=>$exchange_payable_price,
                                        'balance_amount'=>$big_exchange_payable_price,
                                        'type'=>'hotel_booking'
                                        ]);
                                    
                                    
                                    
                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                        
                                        'token'=>$request->token,
                                        'invoice_no'=>$invoiceId,
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
                                        
                                        'payment_transction_id'=> $invoiceId,
                                        'provider'=> 'hotelbeds',
                                        'payment_remaining_amount'=>$add_price,
                                        
                                        
                                        ]);
                                        
                                        
                                        
                                        
                                        
                                         $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                                        $credit_data = DB::table('credit_limits')->where('customer_id',$customer_get_data->id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
                                         $ramainAmount=$credit_data->remaining_amount - $request->creditAmount;
                                         
                                        $credit_limits = DB::table('credit_limits')->insert([
                                            'transection_id'=>$invoiceId,
                                            'customer_id'=>$customer_get_data->id,
                                                'amount'=>$request->creditAmount,
                                                'total_amount'=>$credit_data->total_amount,
                                                'remaining_amount'=>$ramainAmount,
                                                'currency'=>$credit_data->currency,
                                                'status'=>'1',
                                                'status_type'=>'approved',
                                            
                                            ]);
                                        
                                       $credit_limits = DB::table('credit_limit_transections')->insert([
                                        'invoice_no'=> $invoiceId,
                                        'customer_id'=>$customer_get_data->id,
                                        'transection_amount'=>$request->creditAmount,
                                        'remaining_amount'=>$ramainAmount,
                                        'type'=>'booked',
                                        ]);
                                   //ended code by jamshaid cheena 08-06-2023
                                   
                                   $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                                   
                                if($result){
                                    return response()->json([
                                        'status' => 'success',
                                        'Invoice_id' => $invoiceId,
                                        'Invoice_data' => $booking_data
                                    ]);
                                }
                            
                             } catch (Throwable $e) {
                                 DB::rollback();
                                echo $e;
                                return response()->json(['message'=>'error','booking_id'=> '']);
                            }
                            
    
                  }
                  
                  if($result_booking_rs->Body->Error){
                       return response()->json([
                            'status' => 'error',
                            'message' => $result_booking_rs->Body->Error->ErrorText
                        ]);
                  }
              
            }else{
                // Non Refundable Booking
               
              
            
                         if($userData->id == 29)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "UT".$randomNumber;   
            }
            if($userData->id == 21)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 24)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "365T".$randomNumber;   
            }
            if($userData->id == 20)
            {
             $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  "AHT".$randomNumber;   
            }
            if($userData->id == 28)
            {
             $randomNumber = random_int(1000000, 9999999);
             $invoiceId =  "HH".$randomNumber;   
            }
            else
            {
                $randomNumber = random_int(1000000, 9999999);
               $invoiceId =  $randomNumber;
            }
                        
                        $customer_id = '';
                        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                        if($userData){
                            $customer_id = $userData->id;
                        }
                        
                           $lead_passenger_object = (Object)[
                                'lead_title' =>$hotel_request_data->lead_title,
                                'lead_first_name' =>$hotel_request_data->lead_first_name,
                                'lead_last_name' =>$hotel_request_data->lead_last_name,
                                'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                'lead_phone' =>$hotel_request_data->lead_phone,
                                'lead_email' =>$hotel_request_data->lead_email,
                                'lead_country' => $hotel_request_data->lead_country, 
                            ];
                            
                            $others_adults = [];
                            
                            if(isset($hotel_request_data->other_title)){
                                foreach($hotel_request_data->other_title as $index => $other_res){
                                    $others_adults[] = (Object)[
                                            'title' => $other_res,
                                            'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                            'nationality' => $hotel_request_data->other_nationality[$index],
                                        ];
                                }
                            }
                            
                            $childs = [];
                            if(isset($hotel_request_data->child_title)){
                                foreach($hotel_request_data->child_title as $index => $other_res){
                                    $childs[] = (Object)[
                                            'title' => $other_res,
                                            'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                            'nationality' => $hotel_request_data->child_nationality[$index],
                                        ];
                                }
                            }
                        // dd($hotel_booking_conf_res);
                         try {
                        $result = DB::table('hotels_bookings')->insert([
                                'invoice_no' => $invoiceId,
                                'booking_customer_id' => $booking_customer_id,
                                'provider' => $hotel_checkout_select->hotel_provider,
                                'exchange_currency' => $request->exchange_currency,
                                'exchange_price' => $request->exchange_price,
                                'base_exchange_rate'=>$request->base_exchange_rate,
                                'base_currency'=>$request->base_currency,
                                'selected_exchange_rate'=>$request->selected_exchange_rate,
                                'selected_currency'=>$request->selected_currency,
                                'GBP_currency'=>$request->admin_exchange_currency,
                                'GBP_exchange_rate'=>$request->admin_exchange_rate,
                                'GBP_invoice_price'=>$request->admin_exchange_total_markup_price,
                                'creationDate' => date('Y-m-d'),
                                'status' => "non_refundable",
                                'lead_passenger' => $lead_passenger_object->lead_first_name." ".$lead_passenger_object->lead_last_name,
                                'lead_passenger_data' => json_encode($lead_passenger_object),
                                'other_adults_data' => json_encode($others_adults),
                                'childs_data' => json_encode($childs),
                                'total_adults' => $customer_search_data->adult_searching,
                                'total_childs' => $customer_search_data->child_searching,
                                'total_rooms' => $customer_search_data->room_searching,
                                'reservation_request' => '',
                                'reservation_response' => '',
                                'all_checkout_request_data'=> json_encode($request->all()),
                                'hotel_request_data' => json_encode($hotel_request_data),
                                'hotel_data' => json_encode($hotel_checkout_select),
                                'customer_id' => $customer_id,
                                'booked'=>'admin',
                                'commision_charges'=>$request->commision_charges,
                                'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id
                            ]);
                               
                               $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first(); 
                            if($result){
                                return response()->json([
                                    'status' => 'success_non_refundable',
                                    'Invoice_id' => $invoiceId,
                                    'Invoice_data' => $booking_data
                                ]);
                            }
                        
                         } catch (Throwable $e) {
                            echo $e;
                            return response()->json(['message'=>'error','booking_id'=> '']);
                        }   
               
               
               
               

            }
        }
        
        // print_r($hotel_request_data);
        // print_r($hotel_checkout_select);
    }
    
    public function provider_payments(Request $request){
        $custom_hotel_provider = DB::table('custom_hotel_provider')
                                        ->join('customer_subcriptions','customer_subcriptions.id','=','custom_hotel_provider.customer_id')
                                        ->join('become_provider_markup','become_provider_markup.customer_id','=','custom_hotel_provider.customer_id')
                                        ->where('become_provider_markup.status','1')
                                        ->where('custom_hotel_provider.customer_id',$request->customer_id)
                                        ->select('custom_hotel_provider.*','customer_subcriptions.id as cust_provider_id','customer_subcriptions.company_name','become_provider_markup.markup','become_provider_markup.markup_value')
                                        ->first();
                                        
        $customer_ledger = DB::table('cust_hotel_provider_ledger')->where('provider_id',$request->customer_id)->orderBy('id','asc')->get();
        return response()->json([
                            'status' => 'success',
                            'customer_ledger' => $customer_ledger,
                            'custom_hotel_provider' => $custom_hotel_provider
                        ]);
        // dd($custom_hotel_provider);
    }
    
    public function provider_payments_requests(Request $request){
         $payment_request = DB::table('provider_payments_requests')
                                        ->join('custom_hotel_provider','custom_hotel_provider.customer_id','=','provider_payments_requests.customer_id')
                                        ->where('provider_payments_requests.customer_id',$request->customer_id)
                                        ->select('provider_payments_requests.*','custom_hotel_provider.*')
                                        ->get();
        return response()->json([
                            'status' => 'success',
                            'payment_request' => $payment_request
                        ]);
    }
    
    
}
