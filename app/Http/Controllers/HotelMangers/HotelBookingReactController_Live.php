<?php

namespace App\Http\Controllers\HotelMangers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\booking_customers;
use App\Models\alhijaz_Notofication;
use Carbon\Carbon;
use Hash;
use Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Stuba\Stuba_Controller;
use App\Http\Controllers\SunHotel\SunHotel_Controller;
use App\Http\Controllers\Mail3rdPartyController;
use App\Models\stuba_hotel_details;
use App\Http\Controllers\CustomSearch\CustomHotelBooking;
use App\Jobs\UpdateHotelsBookingStatus;
use DateTime;

class HotelBookingReactController_Live extends Controller
{
    public static function pr($x){
	    $apiKey = '833583586f0fd4fccd3757cd8a57c0a8';
        $secret = 'ae2dc887d0';
        $signature = hash("sha256", $apiKey.$secret.time());
        return $signature;
        // print_r($signature);
	}
	
    public static function dateDiffInDays($date1, $date2){
        $diff = strtotime($date2) - strtotime($date1);
        return abs(round($diff / 86400));
    }
    
    public static function getBetweenDates($startDate, $endDate){
        $rangArray  = [];
        $startDate  = strtotime($startDate);
        $endDate    = strtotime($endDate);
        // $startDate  += (86400);
        $endDate    -= (86400);
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
            $date           = date('Y-m-d', $currentDate);
            $rangArray[]    = $date;
        }
        return $rangArray;
    }
    
    public static function calculateRoomsAllDaysPrice($room_data,$check_in,$checkout,$customer_Id){
        // return $room_data;
        
        $week_days_total            = 0;
        $week_end_days_totals       = 0;
        $total_price                = 0;
        $check_in                   = date('Y-m-d',strtotime($check_in));
        $checkout                   = date('Y-m-d',strtotime($checkout));
        if($room_data->price_week_type == 'for_all_days'){
            $avaiable_days          = self::dateDiffInDays($check_in, $checkout);
            $allowd_Rooms           = DB::table('allowed_Hotels_Rooms')->where('client_Id',$customer_Id)->where('room_Id',$room_data->id)->get();
            if($allowd_Rooms->isEmpty()){
                $total_price            = ($room_data->price_all_days_wi_markup ?? $room_data->price_all_days) * $avaiable_days;
            }else{
                // return $allowd_Rooms;
                $total_price            = ($allowd_Rooms[0]->room_Sale_Price_AD) * $avaiable_days;
            }
        }else{
            $avaiable_days          = self::dateDiffInDays($check_in, $checkout);
            $all_days               = self::getBetweenDates($check_in, $checkout);
            $week_days              = json_decode($room_data->weekdays);
            $week_end_days          = json_decode($room_data->weekends);
            foreach($all_days as $day_res){
                $day                = date('l', strtotime($day_res));
                $day                = trim($day);
                $week_day_found     = false;
                $week_end_day_found = false;
                
                foreach($week_days as $week_day_res){
                    if($week_day_res == $day){
                        $week_day_found = true;
                    }
                }
                
                if($week_day_found){
                    $allowd_Rooms           = DB::table('allowed_Hotels_Rooms')->where('client_Id',$customer_Id)->where('room_Id',$room_data->id)->get();
                    if($allowd_Rooms->isEmpty()){
                        $week_days_total        += $room_data->weekdays_price_wi_markup ?? $room_data->weekdays_price;
                    }else{
                        $week_days_total        += $allowd_Rooms[0]->room_Sale_Price_WD ?? 0;
                    }
                }else{
                    $allowd_Rooms           = DB::table('allowed_Hotels_Rooms')->where('client_Id',$customer_Id)->where('room_Id',$room_data->id)->get();
                    if($allowd_Rooms->isEmpty()){
                        $week_end_days_totals   += $room_data->weekends_price_wi_markup ?? $room_data->weekends_price;
                    }else{
                        // return $allowd_Rooms[0];
                        $week_end_days_totals   += $allowd_Rooms[0]->room_Sale_Price_WE ?? 0;
                    }
                }
            }
            // return $week_end_days_totals;
            $total_price = $week_days_total + $week_end_days_totals;
        }
        
        $all_days_price = $total_price * 1;
        return $all_days_price;
        
    }
    
    public static function dateDiffInDays_Promotion($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
    }
    
    public static function getAllDates_Promotion($startDate, $endDate){
        $rangArray  = [];
        $startDate  = strtotime($startDate);
        $endDate    = strtotime($endDate);
        // $startDate  += (86400);
        $endDate    -= (86400);
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
            $date           = date('Y-m-d', $currentDate);
            $rangArray[]    = $date;
        }
        return $rangArray;
    }
    
    public static function getBetweenDates_Promotion($startDate, $endDate){
        $rangArray  = [];
        $startDate  = strtotime($startDate);
        $endDate    = strtotime($endDate);
        // $startDate  += (86400);
        $endDate    -= (86400);
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
            $date           = date('Y-m-d', $currentDate);
            $rangArray[]    = $date;
        }
        return $rangArray;
    }
    
    public static function calculateRoomsAllDaysPrice_Promotions($room_Promotions,$room_data,$check_in,$checkout){
        // return $room_data;
        
        $at_Least_OP                = false;
        
        $week_days_total            = 0;
        $week_end_days_totals       = 0;
        $total_price                = 0;
        $check_in                   = date('Y-m-d',strtotime($check_in));
        $checkout                   = date('Y-m-d',strtotime($checkout));
        $all_days                   = self::getAllDates_Promotion($check_in, $checkout);
        // return $all_days;
        if($room_data->price_week_type == 'for_all_days'){
            $all_days_Promotions    = self::getBetweenDates_Promotion($room_Promotions->promotion_Date_From, $room_Promotions->promotion_Date_To);
            foreach($all_days as $day_res){
                $date_Found         = false;
                $day                = date('l', strtotime($day_res));
                $day                = trim($day);
                
                foreach($all_days_Promotions as $val_PD){
                    if($day_res == $val_PD){
                        $date_Found  = true;
                        $at_Least_OP = true;
                        $total_price += $room_Promotions->promotion_Rate;
                    }
                }
                
                if($date_Found == false){
                    $total_price += $room_Promotions->total_Rate;
                }
            }
        }else{
            $i=1;
            $week_days              = json_decode($room_data->weekdays);
            $week_end_days          = json_decode($room_data->weekends);
            $all_days_Promotions_WD = self::getBetweenDates_Promotion($room_Promotions->promotion_Date_From_WD, $room_Promotions->promotion_Date_To_WD);
            $all_days_Promotions_WE = self::getBetweenDates_Promotion($room_Promotions->promotion_Date_From_WE, $room_Promotions->promotion_Date_To_WE);
            
            $filtered_Dates_WD      = array_filter($all_days, function($date) use ($all_days_Promotions_WD, $week_days) {
                if (in_array($date, $all_days_Promotions_WD)) {
                    $dayOfWeek      = date('l', strtotime($date));
                    return in_array($dayOfWeek, $week_days);
                }
                return false;
            });
            $new_filtered_Dates_WD  = array_values($filtered_Dates_WD);
            
            // return $filtered_Dates_WD;
            
            $filtered_Dates_WE      = array_filter($all_days, function($date) use ($all_days_Promotions_WE, $week_end_days) {
                if (in_array($date, $all_days_Promotions_WE)) {
                    $dayOfWeek      = date('l', strtotime($date));
                    return in_array($dayOfWeek, $week_end_days);
                }
                return false;
            });
            $new_filtered_Dates_WE  = array_values($filtered_Dates_WE);
            
            // return $new_filtered_Dates_WE;
            
            foreach($all_days as $day_res){
                $day                            = date('l', strtotime($day_res));
                $day                            = trim($day);
                $promotion_date_Found_WeekDays  = false;
                $promotion_date_Found_WeekEnds  = false;
                $week_day_found                 = false;
                $week_end_day_found             = false;
                
                foreach($new_filtered_Dates_WD as $key_PD_WD => $val_PD_WD){
                    $promotion_day  = date('l', strtotime($val_PD_WD));
                    $promotion_day  = trim($promotion_day);
                    if($day == $promotion_day){
                        foreach($week_days as $week_day_res){
                            if($week_day_res == $day){
                                $week_day_found                 = true;
                                $promotion_date_Found_WeekDays  = true;
                                $at_Least_OP                    = true;
                                $week_days_total                += $room_Promotions->promotion_Rate_WD;
                                unset($new_filtered_Dates_WD[$key_PD_WD]);
                                break;
                            }
                        }
                    }
                }
                
                foreach($new_filtered_Dates_WE as $key_PD_WE => $val_PD_WE){
                    $promotion_day  = date('l', strtotime($val_PD_WE));
                    $promotion_day  = trim($promotion_day);
                    if($day == $promotion_day){
                        foreach($week_end_days as $week_end_days_res){
                            if($week_end_days_res == $day){
                                // return $day.' '.$week_end_days_res;
                                $week_end_day_found             = true;
                                $promotion_date_Found_WeekEnds  = true;
                                $at_Least_OP                    = true;
                                $week_end_days_totals           += $room_Promotions->promotion_Rate_WE;
                                unset($new_filtered_Dates_WE[$key_PD_WE]);
                                break;
                            }
                        }
                    }
                }
                
                if($promotion_date_Found_WeekDays == false){
                    foreach($week_days as $week_day_res){
                        if($week_day_res == $day){
                            $week_day_found = true;
                        }
                    }
                }
                
                if($promotion_date_Found_WeekEnds == false){
                    foreach($week_end_days as $week_end_days_res){
                        if($week_end_days_res == $day){
                            $week_end_day_found = true;
                        }
                    }
                }
                
                if($week_day_found == true &&  $promotion_date_Found_WeekDays == false){
                    $week_days_total        += $room_Promotions->total_Rate_WD;
                }
                
                if($week_end_day_found == true && $promotion_date_Found_WeekEnds == false){
                    $week_end_days_totals   += $room_Promotions->total_Rate_WE;
                }
                
                $i++;
            }
            
            $total_price += $week_days_total + $week_end_days_totals;
        }
        
        $all_days_price = $total_price * 1;
        
        if($at_Least_OP != false){
            return $all_days_price;
        }else{
            return 0;
        }
        
    }
    
    public function search_hotels_CUSTOM_HOTELS(Request $request){
        $token                  = $request->token;
        $check_in               = $request->check_in;
        $check_out              = $request->check_out;
        $destination            = $request->destination ?? $request->destination_name;
        $destination_first_char = substr($request->destination_name, 0, 10);
        $adult_per_room         = $request->adult_per_room ?? $request->Adults;
        $child_per_room         = $request->child_per_room ?? $request->children;
        
        $adult_searching        = $request->adult_searching;
        $child_searching        = $request->child_searching;
        $hotel_search_request   = $request->hotel_search_request;
        $country_nationality    = $request->country_nationality;
        
        $CheckInDate            = $check_in;
        $CheckOutDate           = $check_out;
        
        $sub_customer_id        = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        $markup                 = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)->orwhere('added_markup','synchtravel')->orderBy('id','DESC')->get();
        
        $admin_travelenda_markup    = 0;
        $admin_hotel_bed_markup     = 0;
        $admin_custom_markup        = 0;
        
        $customer_markup                    = 0;
        $customer_custom_hotel_markup       = 0;
        $customer_custom_hotel_markup_type  = '';
        
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
      
        $get_res    = DB::table('travellanda_get_cities')->where('CityName',$destination)->first();
        $CityId     = '';
        if($get_res){
            $CityId = $get_res->CityId;
        }
        
        $hotels_list_arr        = [];
        $hotels_list_arr_match  = [];
        
        // ************************************************************ //
        // Custom Hotels
        // ************************************************************ //
        
        $hotel_city         = $destination;
        $hotel_city_changed = $destination;
        
        if($hotel_city == 'Al Madinah Al Munawwarah' || $hotel_city == 'Al-Madinah al-Munawwarah'){
            $hotel_city_changed = 'Medina';
        }
        
        if($hotel_city == 'Mecca'){
            $hotel_city_changed = 'Makkah';
        }
        
        if($hotel_city == 'Madinah'){
            $hotel_city_changed = 'Medina';
        }
        
        if($hotel_city == 'مكة'){
            $hotel_city_changed = 'Makkah';
        }
        
        if($hotel_city == 'Taif'){
            $hotel_city_changed = "Ta'if";
        }
        
        $rooms_adults       = $adult_per_room;
        $rooms_childs       = $child_per_room;
        
        $all_hotels         = DB::table('hotels')->where('property_city',$hotel_city_changed)->orWhere('property_city',$destination)->where('owner_id',$sub_customer_id->id)->get();
        
        // return $sub_customer_id;
        // $all_hotels;
        
        $custom_hotels      = [];
        $hotel_list_item    = [];
        
        // return $request;
        
        foreach($all_hotels as $hotel_res){
            $rooms_found    = [];
            $rooms_ids      = [];
            $rooms_qty      = [];
            $counter        = 0;
            
            $total_adults_in_rooms = 0;
            $total_childs_in_rooms = 0;
            if(isset($rooms_adults) && !empty($rooms_adults)){
                foreach($rooms_adults as $index => $adult_res){
                    
                    $rooms  = DB::table('rooms')
                                ->where('availible_from','<=',$check_in)
                                ->where('availible_to','>=',$check_out)
                                ->whereRaw('booked < quantity')
                                ->where('max_adults',$adult_res)
                                ->where('display_on_web','true')
                                ->where('hotel_id',$hotel_res->id)
                                ->where('owner_id',$sub_customer_id->id)
                                ->get();
                    
                    if(count($rooms) > 0){
                        foreach($rooms as $room_res){
                            if (!in_array($room_res->id, $rooms_ids)) {
                               $rooms_ids[]             = $room_res->id;
                               $aval_qty                = $room_res->quantity - $room_res->booked;
                               $rooms_qty[]             = $aval_qty;
                               $total_adults_in_rooms   += ($room_res->max_adults * $aval_qty);
                               $total_childs_in_rooms   += ($room_res->max_child * $aval_qty);
                               $rooms_found[]           = $room_res;
                            }
                        }
                    }
                }
            }
            
            // $hotel_currency = '';
            $hotel_currency = $hotel_res->currency_symbol ?? '';
            if($hotel_res->currency_symbol == '£' || $hotel_res->currency_symbol == '﷼')  {
                $hotel_currency = 'SAR';
            }
            
            $options_room       = [];
            $room_prices_arr    = [];
            
            if($total_adults_in_rooms >= $adult_searching && $total_childs_in_rooms >= $child_searching){
                foreach($rooms_found as $room_res){
                    $room_price         = self::calculateRoomsAllDaysPrice($room_res,$check_in,$check_out,$sub_customer_id->id);
                    $room_prices_arr[]  = $room_price;
                    $options_room[]     = (Object)[
                        'booking_req_id'            => $room_res->id,
                        'allotment'                 => $room_res->quantity - $room_res->booked,
                        'room_name'                 => $room_res->room_type_name,
                        'room_code'                 => $room_res->room_type_cat,
                        'request_type'              => $room_res->rooms_on_rq,
                        'board_id'                  => $room_res->room_meal_type,
                        'board_code'                => $room_res->room_meal_type,
                        'rooms_total_price'         => $room_price * $request->room,
                        'rooms_selling_price'       => $room_price * $request->room,
                        'rooms_qty'                 => $request->room ?? 1,
                        'adults'                    => $request->adult ?? $rooms_adults[0] ?? $room_res->max_adults,
                        'childs'                    => $request->child ?? $rooms_childs[0] ?? $room_res->max_child,
                        'cancliation_policy_arr'    => [],
                        'rooms_list'                => []
                    ];
                }
                
                $min_price = 0;
                if(!empty($room_prices_arr)){
                    $min_price = min($room_prices_arr);
                }
                
                $max_price = 0;
                if(!empty($room_prices_arr)){
                    $max_price = max($room_prices_arr);
                }
                
                $hotel_list_item            = (Object)[
                    'hotel_provider'        => 'Custome_hotel',
                    'admin_markup'          => $admin_custom_markup,
                    'customer_markup'       => $customer_custom_hotel_markup,
                    'admin_markup_type'     => 'Percentage',
                    'customer_markup_type'  => $customer_custom_hotel_markup_type,
                    'hotel_id'              => $hotel_res->id,
                    'hotel_name'            => $hotel_res->property_name,
                    'stars_rating'          => $hotel_res->star_type,
                    'hotel_curreny'         => $hotel_currency,
                    'min_price'             => $min_price * $request->room,
                    'max_price'             => $max_price * $request->room,
                    'rooms_options'         => $options_room,
                    'latitude_Hotel'        => $hotel_res->latitude,
                    'longitude_Hotel'       => $hotel_res->longitude,
                ];
                
                $hotel_first_char = substr($hotel_res->property_name, 0, 10);
                if($hotel_first_char == $destination_first_char){
                    if(isset($hotel_list_item->hotel_provider) && $max_price > 0){
                        array_push($hotels_list_arr_match,$hotel_list_item);
                    }
                }else{
                    if(isset($hotel_list_item->hotel_provider) && $max_price > 0){
                        array_push($hotels_list_arr,$hotel_list_item);
                    }
                }
            }
        }
        
        // ************************************************************ //
        // Custom Hotels Providers
        // ************************************************************ //
        
        $final_hotel_Array = array_merge($hotels_list_arr_match, $hotels_list_arr);
        
        return response()->json([
            'status'            => 'success',
            'hotels_list'       => $final_hotel_Array
        ]);
    }
    
    public function search_hotels_Travelenda(Request $request){
        // return $request;
        
        $token                  = $request->token;
        $check_in               = $request->check_in;
        $check_out              = $request->check_out;
        $destination            = $request->destination ?? $request->destination_name;
        $destination_first_char = substr($request->destination_name, 0, 10);
        $adult_per_room         = $request->adult_per_room ?? $request->Adults;
        $child_per_room         = $request->child_per_room ?? $request->children;
        
        $adult_searching        = $request->adult_searching;
        $child_searching        = $request->child_searching;
        $hotel_search_request   = $request->hotel_search_request;
        $country_nationality    = $request->slc_nationality ?? $request->country_nationality;
        
        $CheckInDate            = $check_in;
        $CheckOutDate           = $check_out;
        
        $sub_customer_id        = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        $markup                 = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)
                                    // ->orwhere('added_markup','synchtravel')
                                    ->orderBy('id','DESC')->get();
                                    
        $admin_travelenda_markup            = 0;
        $admin_hotel_bed_markup             = 0;
        $admin_custom_markup                = 0;
        
        $customer_markup                    = 0;
        $customer_custom_hotel_markup       = 0;
        $customer_custom_hotel_markup_type  = '';
        
        foreach($markup as $data){
            if($data->added_markup == 'synchtravel'){
                
                if($data->provider == 'CustomHotel'){
                    $admin_custom_markup        = $data->markup_value;
                }
                
                if($data->provider == 'Ratehawk'){
                    // config(['Ratehawk' => $data->markup_value]);  
                }
                
                if($data->provider == 'TBO'){
                    // config(['TBO' => $data->markup_value]);   
                }
                
                if($data->provider == 'Travellanda'){
                    $admin_travelenda_markup    = $data->markup_value;
                }
                
                if($data->provider == 'Hotelbeds'){
                    $admin_hotel_bed_markup     = $data->markup_value;
                }
                
                if($data->provider == 'All'){
                    $admin_travelenda_markup    = $data->markup_value;
                    $admin_hotel_bed_markup     = $data->markup_value;
                    $admin_custom_markup        = $data->markup_value;
                }
            } 
            
            if($data->added_markup == 'Haramayn_hotels'){
                $customer_markup = $data->markup_value;
            }
        }
      
        $get_res    = DB::table('travellanda_get_cities')->where('CityName',$destination)->first();
        $CityId     = '';
        if($get_res){
            $CityId = $get_res->CityId;
        }
        
        $hotels_list_arr        = [];
        $hotels_list_arr_match  = [];
        
        $rooms_adults           = $adult_per_room;
        $rooms_childs           = $child_per_room;
        
        // ************************************************************ //
        // Travelenda Provider
        // ************************************************************ //
        
        $country                = $request->slc_nationality;
        $countryList            = DB::table('tboHoliday_Country_List')->whereRaw('LOWER(Name) = ?', [strtolower($country)])->first();
        $country_nationality    = $countryList->Code;
        
        $rooms_no = 1;
        $room_request_create = [];
        foreach($request->rooms_counter as $index => $room_counter){
            
            $child_age  = [];
            $childern   = $request->children[$index];
            
            // echo "child is $childern ";
            $child_age_index = 'child_ages'.$room_counter;
            
            $child_ages = $request->$child_age_index;
            // echo $child_age_index;
            for($i = 0; $i<$childern; $i++){
                array_push($child_age,$child_ages[$i]);
            }
            
            $single_room = (object)[
                'Room'=>$rooms_no++,
                'Adults'=>$request->Adults[$index],
                'Children'=>$request->children[$index],
                'ChildrenAge'=>$child_age
            ];
            
            array_push($room_request_create,$single_room);
        }
        
        function travellandasearch($CityId, $CheckInDate, $CheckOutDate, $res_data, $country_nationality){
            $url    = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
            $data   = array('case' => 'travelandaSearch', 'CityId' => $CityId, 'CheckInDate' => $CheckInDate, 'CheckOutDate' => $CheckOutDate, 'res_data' => json_encode($res_data), 'country_nationality' =>$country_nationality);
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
        
        $responseData3              = travellandasearch($CityId, $check_in, $check_out, $room_request_create, $country_nationality);
        // return $responseData3;
        $result_travellanda         = json_decode($responseData3);
        // return $result_travellanda;
        
        $travellanda_obj            = [];
        $travelenda_hotels_count    = 0;
        $travelenda_curreny         = '';
        if(isset($result_travellanda->Body->HotelsReturned)){
            $travelenda_curreny                 = $result_travellanda->Body->Currency;
            if($result_travellanda->Body->HotelsReturned > 1){
                if(isset($result_travellanda->Body->Hotels->Hotel)){
                    $travellanda_obj            = $result_travellanda->Body->Hotels->Hotel;
                    $travelenda_hotels_count    = $result_travellanda->Body->HotelsReturned ?? '0';
                }
            }
        }
        
        foreach($travellanda_obj as $travel_res){
            $rooms_list             = [];
            $prices_arr             = [];
            $option_list            = [];
            
            if(isset($travel_res->Options)){
                $Options            = $travel_res->Options;
                if(isset($Options->Option)){
                    foreach($Options->Option as $room_res){
                        
                        $options_room   = [];
                        $rooms_qty      = 0;
                        $total_adults   = 0;
                        $total_childs   = 0;
                        $room_name      = '';
                        
                        if(isset($room_res->Rooms->Room)){
                            if (is_array($room_res->Rooms->Room)) {
                                // return 'IF';
                                
                                $rooms_qty              = count($room_res->Rooms->Room);
                                $room_name              = $room_res->Rooms->Room[0]->RoomName;
                                foreach($room_res->Rooms->Room as $room_list_res){
                                    $daily_prices       = [];
                                    if(isset($room_list_res->DailyPrices)){
                                      $daily_prices     = $room_list_res->DailyPrices->DailyPrice;
                                    }
                                   
                                    $total_adults       += $room_list_res->NumAdults;
                                    $total_childs       += $room_list_res->NumChildren;
                                    
                                    $options_room[]     = (Object)[
                                        'room_id'       => $room_list_res->RoomId,
                                        'room_name'     => $room_list_res->RoomName,
                                        'adults'        => $room_list_res->NumAdults,
                                        'childs'        => $room_list_res->NumChildren,
                                        'room_price'    => $room_list_res->RoomPrice,
                                        'daily_prices'  => $daily_prices
                                    ];
                                }
                            } else {
                                // return 'ELSE';
                                
                                $rooms_qty = 1;
                                $room_name = $room_res->Rooms->Room->RoomName;
                                
                                $daily_prices = [];
                                if(isset($room_list_res->DailyPrices)){
                                  $daily_prices = $room_res->Rooms->Room->DailyPrices->DailyPrice;
                                }
                               
                                $total_adults += $room_res->Rooms->Room->NumAdults;
                                $total_childs += $room_res->Rooms->Room->NumChildren;
                               
                                $options_room[] = (Object)[
                                    'room_id'       => $room_res->Rooms->Room->RoomId,
                                    'room_name'     => $room_res->Rooms->Room->RoomName,
                                    'adults'        => $room_res->Rooms->Room->NumAdults,
                                    'childs'        => $room_res->Rooms->Room->NumChildren,
                                    'room_price'    => $room_res->Rooms->Room->RoomPrice,
                                    'daily_prices'  => $daily_prices
                                ];
                            }
                            
                        }
                        
                        if(isset($room_res->OptionId)){
                            $option_list[]                  = (Object)[
                                'booking_req_id'            => $room_res->OptionId,
                                'allotment'                 => 1,
                                'room_name'                 => $room_name,
                                'room_code'                 => '',
                                'request_type'              => $room_res->OnRequest,
                                'board_id'                  => $room_res->BoardType,
                                'board_code'                => '',
                                'rooms_total_price'         => $room_res->TotalPrice,
                                'rooms_selling_price'       => '',
                                'rooms_qty'                 => $rooms_qty,
                                'adults'                    => $total_adults,
                                'childs'                    => $total_childs,
                                'cancliation_policy_arr'    =>[],
                                'rooms_list'                => $options_room
                            ];
                            $prices_arr[]                   = $room_res->TotalPrice;
                        }
                    }
                }
            }
           
            if(!empty($prices_arr)){
                $max_hotel_price = max($prices_arr);
                $min_hotel_price = min($prices_arr);
            }
            else{
                $min_hotel_price =  0;
                $max_hotel_price = 0;  
            }
            
            if($travel_res->StarRating == '5'){
                $stars_rating   = 5;
            }
            
            if($travel_res->StarRating == '4'){
                $stars_rating   = 4;
            }
            
            if($travel_res->StarRating == '3'){
                $stars_rating   = 3;
            }
            
            if($travel_res->StarRating == '2'){
                $stars_rating   = 2;
            }
            
            if($travel_res->StarRating == '1'){
                $stars_rating   = 1;
            }
            
            $hotel_list_item = (Object)[
                'hotel_provider'        => 'travelenda',
                'admin_markup'          => $admin_travelenda_markup,
                'customer_markup'       => $customer_markup,
                'admin_markup_type'     => 'Percentage',
                'customer_markup_type'  => 'Percentage',
                'hotel_id'              => $travel_res->HotelId,
                'hotel_name'            => $travel_res->HotelName,
                'stars_rating'          => $stars_rating,
                'hotel_curreny'         => $travelenda_curreny,
                'min_price'             => $min_hotel_price,
                'max_price'             => $max_hotel_price,
                'rooms_options'         => $option_list,
            ];
            
            $hotel_first_char = substr($travel_res->HotelName, 0, 10);
            if($hotel_first_char == $destination_first_char){
                array_push($hotels_list_arr_match,$hotel_list_item);
            }else{
                array_push($hotels_list_arr,$hotel_list_item);
            } 
        }
        
        $final_hotel_Array      = array_merge($hotels_list_arr_match,$hotels_list_arr);
        
        return response()->json([
            'status'            => 'success',
            'travelenda_count'  => $travelenda_hotels_count,
            'hotels_list'       => $final_hotel_Array,
        ]);
    }
    
    public function search_hotels_SunHotel(Request $request){
        // return 'ok';
        
        $token                              = $request->token;
        $check_in                           = $request->check_in;
        $check_out                          = $request->check_out;
        $destination                        = $request->destination ?? $request->destination_name;
        $adult_per_room                     = $request->adult_per_room ?? $request->Adults;
        $child_per_room                     = $request->child_per_room ?? $request->children;
        $adult_searching                    = $request->adult_searching;
        $child_searching                    = $request->child_searching;
        $hotel_search_request               = $request->hotel_search_request;
        $country_nationality                = $request->country_nationality;
        $CheckInDate                        = $check_in;
        $CheckOutDate                       = $check_out;
        $destination_first_char             = substr($request->destination_name, 0, 10);
        
        $numberOfRooms                      = $request->room ?? $adult_per_room;
        $numberOfAdults                     = $request->adult ?? $adult_per_room;
        $numberOfChildrenWithInfants        = $request->child ?? $child_per_room;
        
        $sub_customer_id                    = DB::table('customer_subcriptions')->where('Auth_key', $request->token)->select('id')->first();
        // return $sub_customer_id;
        $markup                             = DB::table('admin_markups')->where('customer_id', $sub_customer_id->id)->where('status', 1)->orwhere('added_markup', 'synchtravel')->orderBy('id', 'DESC')->get();
        
        $admin_travelenda_markup            = 0;
        $admin_hotel_bed_markup             = 0;
        $admin_custom_markup                = 0;
        $customer_markup                    = 0;
        $customer_custom_hotel_markup       = 0;
        $customer_custom_hotel_markup_type  = '';
        
        foreach ($markup as $data) {
            if ($data->added_markup == 'synchtravel') {
                if ($data->provider == 'CustomHotel') {
                    $admin_custom_markup = $data->markup_value;
                }
                if ($data->provider == 'Ratehawk') {
                    // config(['Ratehawk' => $data->markup_value]);
                }
                if ($data->provider == 'TBO') {
                    // config(['TBO' => $data->markup_value]);
                }
                if ($data->provider == 'Travellanda') {
                    $admin_travelenda_markup = $data->markup_value;
                }
                if ($data->provider == 'Hotelbeds') {
                    $admin_hotel_bed_markup = $data->markup_value;
                }
                if ($data->provider == 'All') {
                    $admin_travelenda_markup = $data->markup_value;
                    $admin_hotel_bed_markup = $data->markup_value;
                    $admin_custom_markup = $data->markup_value;
                }
            }

            ///ended

            if ($data->added_markup == 'Haramayn_hotels') {
                // if($data->provider == 'All'){
                $customer_markup = $data->markup_value;
                // }
            }

        }
        
        $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id', $sub_customer_id->id)->where('status', 1)->first();
        if ($custom_hotel_markup) {
            $customer_custom_hotel_markup = $custom_hotel_markup->markup_value;
            $customer_custom_hotel_markup_type = $custom_hotel_markup->markup;
        }
        
        $get_res    = DB::table('travellanda_get_cities')->where('CityName', $destination)->first();
        $CityId     = '';
        if ($get_res) {
            $CityId = $get_res->CityId;
        }
        
        $hotels_list_arr        = [];
        $hotels_list_arr_match  = [];
        
        // ************************************************************ //
        //  SunHotels Start
        // ************************************************************ //
        
        $totalNumberSunhotel        = 0;
        $hotelsSunhotel             = [];
        $sunhotelAdminMarkup        = 0;
        $sunhotelCustomerMarkup     = 0;
        $sunhotelAdminMarkupType    = 'Percentage';
        $sunhotelCustomerMarkupType = 'Percentage';
        $destinationCode            = '';
        $sunhotel_destination       = DB::table('webbed_destination_codes')->where('DestinationName', $request->destination_name)->first();
        
        if ($sunhotel_destination) {
            $destinationCode = $sunhotel_destination->DestinationCode
                ?? $sunhotel_destination->DestinationCode_2
                ?? $sunhotel_destination->DestinationCode_3
                ?? $sunhotel_destination->DestinationCode_4;
        }
        
        $child_Age_Array = [];
        if(count($request->children) > 0){
            for($ca=0; $ca<count($request->children); $ca++){
                $new_ca     = $ca + 1;
                $make_Name = 'child_ages'.$new_ca;
                $child_ages = $request->$make_Name;
                // return $child_ages;
                array_push($child_Age_Array,$child_ages);
            }
        }
        if($destinationCode != ''){
            $responseData3          = SunHotel_Controller::sunHotelSearch($child_per_room,$CheckInDate,$CheckOutDate,$numberOfRooms,$destinationCode,$numberOfAdults,$request->room,$numberOfChildrenWithInfants,$request->Adults,$request->rooms_counter,$child_Age_Array,$request->children,$sunhotelAdminMarkup,$sunhotelCustomerMarkup,$customer_Hotel_markup_SunHotel);
            // return $responseData3;
            
            if(isset($responseData3['hotelsSunhotel'])){
                $hotelsSunhotel     = $responseData3['hotelsSunhotel'];
            }
            
            if(isset($responseData3['totalNumberSunhotel'])){
                $totalNumberSunhotel = $responseData3['totalNumberSunhotel'];
            }
        }
        
        // ************************************************************ //
        //  End SunHotels
        // ************************************************************ //
        
        $final_hotel_Array          = array_merge($hotelsSunhotel);
        
        return response()->json([
            'status'            => 'success',
            'sun_hotel_counts'  => $totalNumberSunhotel,
            'hotels_list'       => $final_hotel_Array
        ]);
    }
    
    public function search_hotels_Stuba(Request $request){
        // return 'ok';
        
        $token                              = $request->token;
        $check_in                           = $request->check_in;
        $check_out                          = $request->check_out;
        $destination                        = $request->destination ?? $request->destination_name;
        $adult_per_room                     = $request->adult_per_room ?? $request->Adults;
        $child_per_room                     = $request->child_per_room ?? $request->children;
        $adult_searching                    = $request->adult_searching;
        $child_searching                    = $request->child_searching;
        $hotel_search_request               = $request->hotel_search_request;
        $country_nationality                = $request->country_nationality;
        $CheckInDate                        = $check_in;
        $CheckOutDate                       = $check_out;
        $destination_first_char             = substr($request->destination_name, 0, 10);
        
        $numberOfRooms                      = $request->room ?? $adult_per_room;
        $numberOfAdults                     = $request->adult ?? $adult_per_room;
        $numberOfChildrenWithInfants        = $request->child ?? $child_per_room;
        
        $sub_customer_id                    = DB::table('customer_subcriptions')->where('Auth_key', $request->token)->select('id')->first();
        // return $sub_customer_id;
        $markup                             = DB::table('admin_markups')->where('customer_id', $sub_customer_id->id)->where('status', 1)->orwhere('added_markup', 'synchtravel')->orderBy('id', 'DESC')->get();
        
        $admin_travelenda_markup            = 0;
        $admin_hotel_bed_markup             = 0;
        $admin_custom_markup                = 0;
        $customer_markup                    = 0;
        $customer_custom_hotel_markup       = 0;
        $customer_custom_hotel_markup_type  = '';
        
        foreach ($markup as $data) {
            if ($data->added_markup == 'synchtravel') {
                if ($data->provider == 'CustomHotel') {
                    $admin_custom_markup = $data->markup_value;
                }
                if ($data->provider == 'Ratehawk') {
                    // config(['Ratehawk' => $data->markup_value]);
                }
                if ($data->provider == 'TBO') {
                    // config(['TBO' => $data->markup_value]);
                }
                if ($data->provider == 'Travellanda') {
                    $admin_travelenda_markup = $data->markup_value;
                }
                if ($data->provider == 'Hotelbeds') {
                    $admin_hotel_bed_markup = $data->markup_value;
                }
                if ($data->provider == 'All') {
                    $admin_travelenda_markup = $data->markup_value;
                    $admin_hotel_bed_markup = $data->markup_value;
                    $admin_custom_markup = $data->markup_value;
                }
            }

            ///ended

            if ($data->added_markup == 'Haramayn_hotels') {
                // if($data->provider == 'All'){
                $customer_markup = $data->markup_value;
                // }
            }

        }
        
        $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id', $sub_customer_id->id)->where('status', 1)->first();
        if ($custom_hotel_markup) {
            $customer_custom_hotel_markup = $custom_hotel_markup->markup_value;
            $customer_custom_hotel_markup_type = $custom_hotel_markup->markup;
        }
        
        $get_res    = DB::table('travellanda_get_cities')->where('CityName', $destination)->first();
        $CityId     = '';
        if ($get_res) {
            $CityId = $get_res->CityId;
        }
        
        $hotels_list_arr        = [];
        $hotels_list_arr_match  = [];
        
        // ************************************************************ //
        // Stuba
        // ************************************************************ //
        
        $rooms_no               = 1;
        $room_request_create    = [];
        $stuba_hotels_items     = '';
        $stuba_hotel_count      = 0;
        
        if (!is_array($request->rooms_counter)) {
            $request->rooms_counter = json_decode($request->rooms_counter);
        }
        
        foreach ($request->rooms_counter as $index => $room_counter) {
            // return $index;
            
            $child_age = [];
            
            if (!is_array($request->children)) {
                $request->children = json_decode($request->children);
            }
            
            $childern           = $request->children[$index];
            // return $childern;
            $child_age_index    = 'child_ages' . $room_counter;
            $child_ages         = $request->$child_age_index;
            for ($i = 0; $i < $childern; $i++) {
                array_push($child_age, $child_ages[$i]);
            }
            
            $single_room        = (object)[
                'Room'          => $rooms_no++,
                'Adults'        => $request->Adults[$index],
                'Children'      => $request->children[$index],
                'ChildrenAge'   => $child_age,
            ];
            array_push($room_request_create, $single_room);
        }
        
        $roomsData      = $room_request_create;
        // return $roomsData;
        $city           = $destination_first_char;
        $arivaldate     = $check_in;
        $nights         = self::dateDiffInDays($check_in, $check_out);
        $country        = $request->slc_nationality;
        $countryList    = DB::table('tboHoliday_Country_List')->whereRaw('LOWER(Name) = ?', [strtolower($country)])->first();
        $country_code   = $countryList->Code;
        $hotels         = DB::table('stuba_cityids')->whereRaw('LOWER(Name) = ?', [strtolower($city)])->first();
        // return $hotels;
        if (isset($hotels)) {
            $id                         = $hotels->cityid;
            $hotelsresults              = [];
            $hoteldetails               = DB::table('stuba_hotel_details')->WhereJsonContains('hotelRegion', [['CityID' => (string)$id]])->get();
            // return $hoteldetails;
            
            foreach ($hoteldetails as $details) {
                $region                 = json_decode($details->hotelRegion);
                $cityid                 = $region[0]->CityID ?? '';
                if ($cityid == (string)$id) {
                    $hotelsresults[]    = $details;
                }
            }
            
            foreach ($hotelsresults as $hotel) {
                $hotelscodes[]          = $hotel->hotelid;
            }
        }
        
        if(isset($hotelscodes)){
            $stuba_response = Stuba_Controller::stubaSearch($request,$hotelscodes,$arivaldate,$nights,$country_code,$roomsData);
            // return $stuba_response;
        }
        
        if(isset($stuba_response['stuba_hotels_items'])){
            $stuba_hotels_items = $stuba_response['stuba_hotels_items'];
        }else{
            $stuba_hotels_items = [];
        }
        
        if(isset($stuba_response['stuba_hotel_count'])){
            $stuba_hotel_count  = $stuba_response['stuba_hotel_count'];
        }else{
            $stuba_hotel_count  = 0;
        }
        
        // ************************************************************ //
        // Stuba Search End
        // ************************************************************ //
        
        $final_hotel_Array          = array_merge($stuba_hotels_items);
        
        return response()->json([
            'status'                => 'success',
            'stuba_hotel_count'     => $stuba_hotel_count,
            'hotels_list'           => $final_hotel_Array
        ]);
    }
    
    // Merge Rooms
    public static function dateDiffInDays_MergeRooms($date1, $date2){
        $diff = strtotime($date2) - strtotime($date1);
        return abs(round($diff / 86400));
    }
    
    public static function getBetweenDates_MergeRooms($startDate, $endDate){
        $rangArray  = [];
        $startDate  = strtotime($startDate);
        $endDate    = strtotime($endDate);
        // $startDate  += (86400);
        $endDate    -= (86400);
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
            $date           = date('Y-m-d', $currentDate);
            $rangArray[]    = $date;
        }
        return $rangArray;
    }
    
    public static function calculateRoomsAllDaysPrice_MergeRooms($room_data,$check_in,$checkout,$customer_Id){
        // return $room_data;
        
        $week_days_total            = 0;
        $week_end_days_totals       = 0;
        $total_price                = 0;
        
        if(isset($room_data->room_From_CheckIn) && $room_data->room_From_CheckIn == '1'){
            $check_in                   = date('Y-m-d',strtotime($check_in));
            $checkout                   = date('Y-m-d',strtotime($room_data->availible_to));
        }
        
        if(isset($room_data->room_From_CheckOut) && $room_data->room_From_CheckOut == '1'){
            $check_in                   = date('Y-m-d',strtotime($room_data->availible_from));
            $checkout                   = date('Y-m-d',strtotime($checkout));
        }
        
        if($room_data->price_week_type == 'for_all_days'){
            $avaiable_days          = self::dateDiffInDays_MergeRooms($check_in, $checkout);
            $allowd_Rooms           = DB::table('allowed_Hotels_Rooms')->where('client_Id',$customer_Id)->where('room_Id',$room_data->id)->get();
            if($allowd_Rooms->isEmpty()){
                $total_price            = ($room_data->price_all_days_wi_markup ?? $room_data->price_all_days) * $avaiable_days;
            }else{
                // return $allowd_Rooms;
                $total_price            = ($allowd_Rooms[0]->room_Sale_Price_AD) * $avaiable_days;
            }
        }else{
            $avaiable_days          = self::dateDiffInDays_MergeRooms($check_in, $checkout);
            $all_days               = self::getBetweenDates_MergeRooms($check_in, $checkout);
            $week_days              = json_decode($room_data->weekdays);
            $week_end_days          = json_decode($room_data->weekends);
            foreach($all_days as $day_res){
                $day                = date('l', strtotime($day_res));
                $day                = trim($day);
                $week_day_found     = false;
                $week_end_day_found = false;
                
                foreach($week_days as $week_day_res){
                    if($week_day_res == $day){
                        $week_day_found = true;
                    }
                }
                
                if($week_day_found){
                    $allowd_Rooms           = DB::table('allowed_Hotels_Rooms')->where('client_Id',$customer_Id)->where('room_Id',$room_data->id)->get();
                    if($allowd_Rooms->isEmpty()){
                        $week_days_total        += $room_data->weekdays_price_wi_markup ?? $room_data->weekdays_price;
                    }else{
                        $week_days_total        += $allowd_Rooms[0]->room_Sale_Price_WD ?? 0;
                    }
                }else{
                    $allowd_Rooms           = DB::table('allowed_Hotels_Rooms')->where('client_Id',$customer_Id)->where('room_Id',$room_data->id)->get();
                    if($allowd_Rooms->isEmpty()){
                        $week_end_days_totals   += $room_data->weekends_price_wi_markup ?? $room_data->weekends_price;
                    }else{
                        // return $allowd_Rooms[0];
                        $week_end_days_totals   += $allowd_Rooms[0]->room_Sale_Price_WE ?? 0;
                    }
                }
            }
            // return $week_end_days_totals;
            $total_price = $week_days_total + $week_end_days_totals;
        }
        
        $all_days_price = $total_price * 1;
        return $all_days_price;
        
    }
    // Merge Rooms
    
    public function search_hotels(Request $request){
        $token                  = $request->token;
        $check_in               = $request->check_in;
        $check_out              = $request->check_out;
        $destination            = $request->destination ?? $request->destination_name;
        $destination_first_char = substr($request->destination_name, 0, 10);
        $adult_per_room         = $request->adult_per_room ?? $request->Adults;
        $child_per_room         = $request->child_per_room ?? $request->children;
        
        $adult_searching        = $request->adult_searching;
        $child_searching        = $request->child_searching;
        $hotel_search_request   = $request->hotel_search_request;
        $country_nationality    = $request->slc_nationality ?? $request->country_nationality;
        
        $CheckInDate            = $check_in;
        $CheckOutDate           = $check_out;
        
        $sub_customer_id        = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        $markup                 = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)
                                    // ->orwhere('added_markup','synchtravel')
                                    ->orderBy('id','DESC')->get();
                                    
        $admin_travelenda_markup            = 0;
        $admin_hotel_bed_markup             = 0;
        $admin_custom_markup                = 0;
        
        $customer_markup                    = 0;
        $customer_custom_hotel_markup       = 0;
        $customer_custom_hotel_markup_type  = '';
        
        $sunhotelAdminMarkup                = 0;
        $sunhotelCustomerMarkup             = 0;
        
        $numberOfRooms                      = $request->room ?? $adult_per_room;
        $numberOfAdults                     = $request->adult ?? $adult_per_room;
        $numberOfChildrenWithInfants        = $request->child ?? $child_per_room;
        
        foreach($markup as $data){
            if($data->added_markup == 'synchtravel'){
                
                if($data->provider == 'CustomHotel'){
                    $admin_custom_markup        = $data->markup_value;
                }
                
                if($data->provider == 'Ratehawk'){
                    // config(['Ratehawk' => $data->markup_value]);  
                }
                
                if($data->provider == 'TBO'){
                    // config(['TBO' => $data->markup_value]);   
                }
                
                if($data->provider == 'Travellanda'){
                    $admin_travelenda_markup    = $data->markup_value;
                }
                
                if($data->provider == 'Hotelbeds'){
                    $admin_hotel_bed_markup     = $data->markup_value;
                }
                
                if($data->provider == 'All'){
                    $admin_custom_markup        = $data->markup_value;
                    $admin_travelenda_markup    = $data->markup_value;
                    $admin_hotel_bed_markup     = $data->markup_value;
                    $sunhotelAdminMarkup        = $data->markup_value;
                }
            } 
            
            if($data->added_markup == 'Haramayn_hotels'){
                $customer_markup = $data->markup_value;
            }
        }
        
        $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id',$sub_customer_id->id)->where('status',1)->orderBy('id','DESC')->get();
        if(count($custom_hotel_markup) > 0){
            $customer_custom_hotel_markup       = $custom_hotel_markup[0]->markup_value ?? '';
            $customer_custom_hotel_markup_type  = $custom_hotel_markup[0]->markup ?? '';
        }
        
        // B2B Markup
        $markup_Find    = false;
        $user_Token     = $request->token;
        $b2b_Agent_Id   = $request->b2b_agent_id ?? '';
        $b2b_Markup     = DB::table('b2b_Agent_Markups')
                            ->where(function ($query) use ($user_Token, $b2b_Agent_Id) {
                                $query->where('token', $user_Token)
                                ->where('agent_Id', $b2b_Agent_Id)
                                ->where('markup_status',1);
                            })
                            ->orWhere(function ($query) use ($user_Token, $b2b_Agent_Id) {
                                $query->where('token', $user_Token)
                                ->where('agent_Id', 0)
                                ->where('markup_status',1);
                            })
                            ->get();
        if(!empty($b2b_Markup) && $b2b_Markup != null){
            foreach($b2b_Markup as $val_BAM){
                if($val_BAM->agent_Id == $b2b_Agent_Id){
                    $customer_custom_hotel_markup_type  = $val_BAM->markup_Type; 
                    $customer_markup                    = $val_BAM->markup_Value;
                    $customer_custom_hotel_markup       = $val_BAM->markup_Value;
                    $markup_Find                        = true;
                }
            }
            
            if($markup_Find != true){
                foreach($b2b_Markup as $val_BAM){
                    if($val_BAM->agent_Id == 0){
                        $customer_custom_hotel_markup_type  = $val_BAM->markup_Type; 
                        $customer_markup                    = $val_BAM->markup_Value;
                        $customer_custom_hotel_markup       = $val_BAM->markup_Value;
                    }
                }
            }
        }
        // B2B Markup
      
        $get_res    = DB::table('travellanda_get_cities')->where('CityName',$destination)->first();
        $CityId     = '';
        if($get_res){
            $CityId = $get_res->CityId;
        }
        
        $hotels_list_arr        = [];
        $hotels_list_arr_match  = [];
        
        // ************************************************************ //
        // Custom Hotels
        // ************************************************************ //
        
        $hotel_city         = $destination;
        $hotel_city_changed = $destination;
        
        // if($hotel_city == 'Al Madinah Al Munawwarah' || $hotel_city == 'Al-Madinah al-Munawwarah'){
        //     $hotel_city_changed = 'Medina';
        // }
        
        // if($hotel_city == 'Mecca'){
        //     $hotel_city_changed = 'Makkah';
        // }
        
        // if($hotel_city == 'Madinah'){
        //     $hotel_city_changed = 'Medina';
        // }
        
        // if($hotel_city == 'مكة'){
        //     $hotel_city_changed = 'Makkah';
        // }
        
        // if($hotel_city == 'Taif'){
        //     $hotel_city_changed = "Ta’if";
        // }
        
        $cityMap = [
            'Al Madinah Al Munawwarah' => 'Medina',
            'Al-Madinah al-Munawwarah' => 'Medina',
            'Madinah'                  => 'Medina',
            'Mecca'                    => 'Makkah',
            'مكة'                       => 'Makkah',
            'Taif'                     => "Ta’if",
        ];
        $hotel_city_changed = $cityMap[$hotel_city] ?? $hotel_city;
        
        $rooms_adults   = $adult_per_room;
        $rooms_childs   = $child_per_room;
        $customer_Id    = $sub_customer_id->id;
        $all_Hotels     = [];
        $user_hotels    = DB::table('hotels')->where('owner_id',$customer_Id)->where('property_city', $hotel_city_changed)->orderBy('hotels.created_at', 'desc')->get();
        // return $user_hotels;
        $allowd_Hotels  = DB::table('allowed_Hotels_Rooms')->where('client_Id',$customer_Id)->where('status',NULL)->orderBy('created_at', 'desc')->get();
        if($allowd_Hotels->isEmpty()){
            $all_Hotels = $user_hotels;
        }else{
            foreach($allowd_Hotels as $val_AH){
                $allowd_hotel    = DB::table('hotels')->where('id',$val_AH->hotel_Id)->where('property_city', $hotel_city_changed)->orderBy('created_at', 'desc')->first();
                if($allowd_hotel != null){
                    array_push($all_Hotels,$allowd_hotel);
                }
            }
            foreach($user_hotels as $val_HD){
                array_push($all_Hotels,$val_HD);
            }
        }
        
        $collect_Hotels = collect($all_Hotels);
        $all_hotels     = $collect_Hotels->unique('id')->values()->all();
        
        // return $all_hotels;
        
        $custom_hotels      = [];
        $hotel_list_item    = [];
        
        foreach($all_hotels as $hotel_res){
            $rooms_found            = [];
            $rooms_ids              = [];
            $rooms_qty              = [];
            $counter                = 0;
            $room_Quantity          = 0;
            $total_adults_in_rooms  = 0;
            $total_childs_in_rooms  = 0;
            
            $merger_Rooms           = [];
            
            if(isset($rooms_adults) && !empty($rooms_adults)){
                foreach($rooms_adults as $index => $adult_res){
                    // return $adult_res;
                    
                    $hotel_ID   = $hotel_res->id;
                    
                    if($request->token == config('token_Alsubaee')){
                        if(isset($request->room_View) && $request->room_View != null && $request->room_View != ''){
                            $rooms  = DB::table('rooms')
                                        ->where('availible_from','<=',$check_in)->where('availible_to','>=',$check_out)->where('room_view',$request->room_View)->whereRaw('booked < quantity')
                                        ->where('max_adults','>=',$adult_res)->where('display_on_web','true')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->get();
                        }else{
                            $rooms  = DB::table('rooms')
                                        ->where('availible_from','<=',$check_in)->where('availible_to','>=',$check_out)->whereRaw('booked < quantity')
                                        ->where('max_adults','>=',$adult_res)->where('display_on_web','true')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->get();
                        }
                    }else{
                        if(isset($request->room_View) && $request->room_View != null && $request->room_View != ''){
                            $rooms  = DB::table('rooms')
                                        ->where('availible_from','<=',$check_in)->where('availible_to','>=',$check_out)->where('room_view',$request->room_View)->whereRaw('booked < quantity')
                                        ->where('max_adults',$adult_res)->where('display_on_web','true')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->get();
                        }else{
                            $rooms  = DB::table('rooms')
                                        ->where('availible_from','<=',$check_in)->where('availible_to','>=',$check_out)->where('display_on_web','true')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)
                                        // ->whereRaw('booked < quantity')
                                        // ->where('max_adults',$adult_res)
                                        ->where(function ($q) use ($adult_res) {
                                            $q->where('max_adults', '>=', $adult_res)
                                                ->orWhereRaw('(max_adults + extra_beds) >= ?', [$adult_res]);
                                        })
                                        ->get();
                        }
                    }
                    
                    if(count($rooms) > 0){
                        foreach($rooms as $room_res){
                            if (!in_array($room_res->id, $rooms_ids)) {
                                
                                // Check Allowed
                                $check_Allowed = DB::table('allowed_Hotels_Rooms')->where('client_Id',$customer_Id)->where('hotel_Id',$hotel_res->id)->where('room_Id',$room_res->id)->first();
                                // return $check_Allowed->status;
                                // Check Allowed
                                if(isset($check_Allowed->status)){
                                    if($check_Allowed->status != 'Stop'){
                                        $rooms_ids[]             = $room_res->id;
                                        $aval_qty                = $room_res->quantity - $room_res->booked;
                                        $rooms_qty[]             = $aval_qty;
                                        $total_adults_in_rooms   += ($room_res->max_adults * $aval_qty);
                                        $total_childs_in_rooms   += ($room_res->max_child * $aval_qty);
                                        $rooms_found[]           = $room_res;
                                    }
                                }else{
                                    $rooms_ids[]             = $room_res->id;
                                    $aval_qty                = $room_res->quantity - $room_res->booked;
                                    $rooms_qty[]             = $aval_qty;
                                    $total_adults_in_rooms   += ($room_res->max_adults * $aval_qty);
                                    $total_childs_in_rooms   += ($room_res->max_child * $aval_qty);
                                    $rooms_found[]           = $room_res;
                                }
                            }
                        }
                    }else{
                        if($hotel_res->owner_id == 48 || $hotel_res->owner_id == 28){
                            $room_CheckIn   = DB::table('rooms')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->where('display_on_web','true')
                                                ->where('availible_from', '<=', $check_in)->where('availible_to', '>=', $check_in)
                                                ->whereRaw('booked < quantity')->where('max_adults', $adult_res)->get();
                            if(count($room_CheckIn) > 0){
                                foreach($room_CheckIn as $val_RCI){
                                    $val_RCI->room_From_CheckIn = '1';
                                    array_push($merger_Rooms,$val_RCI);
                                }
                            }
                            
                            $room_CheckOut  = DB::table('rooms')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->where('display_on_web','true')
                                                ->where('availible_from', '<=', $check_out)->where('availible_to', '>=', $check_out)
                                                ->whereRaw('booked < quantity')->where('max_adults', $adult_res)->get();
                            if(count($room_CheckOut) > 0 && count($room_CheckIn)){
                                foreach($room_CheckOut as $val_RCO){
                                    $val_RCO->room_From_CheckOut = '1';
                                    array_push($merger_Rooms,$val_RCO);
                                }
                            }
                            
                            if(count($room_CheckOut) > 0 && count($room_CheckIn)){}
                            else{
                                $merger_Rooms = [];
                            }
                            
                            if(count($merger_Rooms) > 0){}
                            else{
                                $allRoomsOfHotel = DB::table('rooms')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->get();
                                info($rooms_found);
                                foreach($allRoomsOfHotel as $index => $room){
                                    $allRoomsOfHotel[$index]->rooms_on_rq           = "1";
                                    $allRoomsOfHotel[$index]->make_on_reqest_able   = true;
                                    $rooms_found[]                                  = $room;
                                }
                                info('after');
                            }
                        }
                    }
                }
            }
            
            $hotel_currency = $hotel_res->currency_symbol ?? '';
            if($hotel_res->currency_symbol == '£' || $hotel_res->currency_symbol == '﷼') {
                $hotel_currency = 'SAR';
            }
            
            $client_Id                  = (int)$hotel_res->owner_id;
            $options_room               = [];
            $room_prices_arr            = [];
            $room_prices_arr_Promotion  = [];
            $room_price_Promotion       = 0;
            $room_Promotions_Exist      = '0';
            if($total_adults_in_rooms >= $adult_searching && $total_childs_in_rooms >= $child_searching){
                
                // return $merger_Rooms;
                
                if($hotel_res->owner_id == 48 || $hotel_res->owner_id == 28){
                    if(count($rooms_found) > 0){
                    }else{
                        if(count($merger_Rooms) > 0){
                            // return $merger_Rooms;
                            
                            $options_room_Merge     = [];
                            foreach($merger_Rooms as $room_res){
                                $room_Promotions    = DB::table('room_promotions')
                                                        ->where(function ($query) use ($check_in, $check_out, $room_res) {
                                                            $query->where('customer_id', $room_res->owner_id)
                                                            ->where('room_Id', $room_res->id)
                                                            ->where('hotel_Id', $room_res->hotel_id)
                                                            ->where(function ($q) use ($check_in, $check_out) {
                                                                $q->whereBetween('promotion_Date_From', [$check_in, $check_out])
                                                                ->orWhereBetween('promotion_Date_To', [$check_in, $check_out])
                                                                ->orWhere(function ($q) use ($check_in, $check_out) { 
                                                                    $q->where('promotion_Date_From', '<=', $check_in)
                                                                    ->where('promotion_Date_To', '>=', $check_out);
                                                                });
                                                            });
                                                        })
                                                        ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                            $query->where('customer_id', $room_res->owner_id)
                                                            ->where('room_Id', $room_res->id)
                                                            ->where('hotel_Id', $room_res->hotel_id)
                                                            ->where(function ($q) use ($check_in, $check_out) {
                                                                $q->whereBetween('promotion_Date_From_WD', [$check_in, $check_out])
                                                                ->orWhereBetween('promotion_Date_To_WD', [$check_in, $check_out])
                                                                ->orWhere(function ($q) use ($check_in, $check_out) {
                                                                    $q->where('promotion_Date_From_WD', '<=', $check_in)
                                                                    ->where('promotion_Date_To_WD', '>=', $check_out);
                                                                });
                                                            });
                                                        })
                                                        ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                            $query->where('customer_id', $room_res->owner_id)
                                                            ->where('room_Id', $room_res->id)
                                                            ->where('hotel_Id', $room_res->hotel_id)
                                                            ->where(function ($q) use ($check_in, $check_out) {
                                                                $q->whereBetween('promotion_Date_From_WE', [$check_in, $check_out])
                                                                ->orWhereBetween('promotion_Date_To_WE', [$check_in, $check_out])
                                                                ->orWhere(function ($q) use ($check_in, $check_out) {
                                                                    $q->where('promotion_Date_From_WE', '<=', $check_in)
                                                                    ->where('promotion_Date_To_WE', '>=', $check_out);
                                                                });
                                                            });
                                                        })
                                                        ->first();
                                $supplier_Details   = DB::table('rooms_Invoice_Supplier')->where('id',$room_res->room_supplier_name)->where('customer_id',$sub_customer_id->id)->first();
                                
                                if(!empty($room_Promotions) > 0){
                                    $room_price_Promotion               = self::calculateRoomsAllDaysPrice_Promotions($room_Promotions,$room_res,$check_in,$check_out);
                                    if($room_price_Promotion > 0){
                                        $room_Promotions_Exist          = '1';
                                        // $room_prices_arr_Promotion[]    = $room_price_Promotion;
                                    }
                                }else{
                                    $room_prices_arr_Promotion[]        = 0;
                                }
                                
                                $room_price                     = self::calculateRoomsAllDaysPrice_MergeRooms($room_res,$check_in,$check_out,$customer_Id);
                                // $room_prices_arr[]              = $room_price;
                                $room_Quantity_Details          = DB::table('rooms_types')->where('id',$room_res->room_type_cat)->first();
                                $rooms_total_price              = $room_price;
                                $rooms_selling_price            = $room_price;
                                $room_Quantity                  += $room_res->quantity;
                                $make_on_reqest_able            = false;
                                if(isset($room_res->make_on_reqest_able)){
                                    $make_on_reqest_able        = true;
                                }
                                
                                $cancellation_policy_arr = [];
                                if (!empty($room_res->cancellation_details)) {
                                    $decoded                        = json_decode($room_res->cancellation_details, true);
                                    if ($decoded) {
                                        $cancellation_policy_arr[]  = $decoded;
                                    }
                                }
                                
                                $options_room_Merge[]           = (Object)[
                                    'make_on_reqest_able'       => $make_on_reqest_able,
                                    'booking_req_id'            => $room_res->id,
                                    'allotment'                 => $room_res->quantity - $room_res->booked,
                                    'room_name'                 => $room_res->room_type_name,
                                    'room_code'                 => $room_res->room_type_cat,
                                    'request_type'              => $room_res->rooms_on_rq,
                                    'board_id'                  => $room_res->room_meal_type,
                                    'board_code'                => $room_res->room_meal_type,
                                    //'rooms_total_price'             => $room_price * $request->room,
                                    //'rooms_selling_price'           => $room_price * $request->room,
                                    'rooms_total_price'             => $rooms_total_price,
                                    'rooms_selling_price'           => $rooms_selling_price,
                                    'rooms_total_price_Promotion'   => $room_price_Promotion * $request->room,
                                    'rooms_selling_price_Promotion' => $room_price_Promotion * $request->room,
                                    'rooms_qty'                 => 1,
                                    'room_Quantity'             => $room_res->quantity ?? 0,
                                    'adults'                    => $room_res->max_adults ?? $rooms_adults[0] ?? '',
                                    'childs'                    => $room_res->max_child ?? $rooms_childs[0] ?? '',
                                    'cancliation_policy_arr'    => $cancellation_policy_arr ?? [],
                                    'rooms_list'                => $room_res,
                                    'merge_Rooms'               => $room_res,
                                    'room_supplier_code'        => $supplier_Details->room_supplier_code ?? '',
                                    'room_Promotions_Exist'     => $room_Promotions_Exist,
                                    'room_Promotions'           => $room_Promotions,
                                    'room_view'                 => $room_res->room_view ?? '',
                                ];
                            }
                            
                            if(count($options_room_Merge) > 0){
                                // return $options_room_Merge;
                                
                                $room_List_All                  = [];
                                $previousRoom                   = null;
                                $rooms_total_price_All          = 0;
                                $rooms_selling_price            = 0;
                                $rooms_total_price_Promotion    = 0;
                                $rooms_selling_price_Promotion  = 0;
                                
                                $mergedRooms                    = [];
                                $usedKeys                       = [];
                                
                                // return $options_room_Merge;
                                
                                foreach ($options_room_Merge as $key => $val_RO) {
                                    if (in_array($key, $usedKeys)) {
                                        continue;
                                    }
                                    $currentRoom            = $val_RO;
                                    $currentTotalPrice      = $val_RO->rooms_total_price;
                                    $currentSellingPrice    = $val_RO->rooms_selling_price;
                                    $currentRoomsList       = [];
                                    array_push($currentRoomsList,$val_RO->merge_Rooms);
                                    $isMerged               = false;
                                
                                    foreach ($options_room_Merge as $innerKey => $innerVal_RO) {
                                        if ($key !== $innerKey && !in_array($innerKey, $usedKeys)) {
                                            if ($currentRoom->rooms_list->availible_to == $innerVal_RO->rooms_list->availible_from) {
                                                $currentTotalPrice                      += $innerVal_RO->rooms_total_price;
                                                $currentSellingPrice                    += $innerVal_RO->rooms_selling_price;
                                                array_push($currentRoomsList, $innerVal_RO->merge_Rooms);
                                                $currentRoom->rooms_list->availible_to  = $innerVal_RO->rooms_list->availible_to;
                                                $usedKeys[]                             = $innerKey;
                                                $isMerged                               = true;
                                            }
                                        }
                                    }
                                
                                    if ($isMerged) {
                                        $currentRoom->rooms_total_price     = $currentTotalPrice;
                                        $currentRoom->rooms_selling_price   = $currentSellingPrice;
                                        $currentRoom->merge_Rooms           = $currentRoomsList;
                                        $mergedRooms[]                      = $currentRoom;
                                    }
                                    
                                    $usedKeys[]                             = $key;
                                }

                                
                                // return $mergedRooms;
                                
                                foreach($mergedRooms as $val_MR){
                                    $room_prices_arr[]                  = $val_MR->rooms_total_price;
                                    $room_prices_arr_Promotion[]        = $val_MR->rooms_total_price_Promotion;
                                    $options_room[]                     = (Object)[
                                        'make_on_reqest_able'           => $val_MR->make_on_reqest_able,
                                        'booking_req_id'                => $val_MR->booking_req_id,
                                        'allotment'                     => $val_MR->allotment,
                                        'room_name'                     => $val_MR->room_name,
                                        'room_code'                     => $val_MR->room_code,
                                        'request_type'                  => $val_MR->request_type,
                                        'board_id'                      => $val_MR->board_id,
                                        'board_code'                    => $val_MR->board_code,
                                        'rooms_total_price'             => $val_MR->rooms_total_price,
                                        'rooms_selling_price'           => $val_MR->rooms_selling_price,
                                        'rooms_total_price_Promotion'   => $val_MR->rooms_total_price_Promotion,
                                        'rooms_selling_price_Promotion' => $val_MR->rooms_selling_price_Promotion,
                                        'rooms_qty'                     => $val_MR->rooms_qty,
                                        'room_Quantity'                 => $val_MR->room_Quantity,
                                        'adults'                        => $val_MR->adults,
                                        'childs'                        => $val_MR->childs,
                                        'cancliation_policy_arr'        => $val_MR->cancliation_policy_arr,
                                        'rooms_list'                    => $val_MR->rooms_list,
                                        'merge_Rooms'                   => $val_MR->merge_Rooms,
                                        'room_supplier_code'            => $val_MR->room_supplier_code,
                                        'room_Promotions_Exist'         => $val_MR->room_Promotions_Exist,
                                        'room_Promotions'               => $val_MR->room_Promotions,
                                        'room_view'                     => $val_MR->room_view,
                                    ];
                                }
                            }
                        }
                    }
                }
                
                foreach($rooms_found as $room_res){
                    // return $rooms_found;
                    
                    $room_Promotions    = DB::table('room_promotions')
                                            ->where(function ($query) use ($check_in, $check_out, $room_res) {
                                                $query->where('customer_id', $room_res->owner_id)
                                                ->where('room_Id', $room_res->id)
                                                ->where('hotel_Id', $room_res->hotel_id)
                                                ->where(function ($q) use ($check_in, $check_out) {
                                                    $q->whereBetween('promotion_Date_From', [$check_in, $check_out])
                                                    ->orWhereBetween('promotion_Date_To', [$check_in, $check_out])
                                                    ->orWhere(function ($q) use ($check_in, $check_out) { 
                                                        $q->where('promotion_Date_From', '<=', $check_in)
                                                        ->where('promotion_Date_To', '>=', $check_out);
                                                    });
                                                });
                                            })
                                            ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                $query->where('customer_id', $room_res->owner_id)
                                                ->where('room_Id', $room_res->id)
                                                ->where('hotel_Id', $room_res->hotel_id)
                                                ->where(function ($q) use ($check_in, $check_out) {
                                                    $q->whereBetween('promotion_Date_From_WD', [$check_in, $check_out])
                                                    ->orWhereBetween('promotion_Date_To_WD', [$check_in, $check_out])
                                                    ->orWhere(function ($q) use ($check_in, $check_out) {
                                                        $q->where('promotion_Date_From_WD', '<=', $check_in)
                                                        ->where('promotion_Date_To_WD', '>=', $check_out);
                                                    });
                                                });
                                            })
                                            ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                $query->where('customer_id', $room_res->owner_id)
                                                ->where('room_Id', $room_res->id)
                                                ->where('hotel_Id', $room_res->hotel_id)
                                                ->where(function ($q) use ($check_in, $check_out) {
                                                    $q->whereBetween('promotion_Date_From_WE', [$check_in, $check_out])
                                                    ->orWhereBetween('promotion_Date_To_WE', [$check_in, $check_out])
                                                    ->orWhere(function ($q) use ($check_in, $check_out) {
                                                        $q->where('promotion_Date_From_WE', '<=', $check_in)
                                                        ->where('promotion_Date_To_WE', '>=', $check_out);
                                                    });
                                                });
                                            })
                                            ->first();
                    $supplier_Details   = DB::table('rooms_Invoice_Supplier')->where('id',$room_res->room_supplier_name)->where('customer_id',$sub_customer_id->id)->first();
                    
                    if(!empty($room_Promotions) > 0){
                        $room_price_Promotion               = self::calculateRoomsAllDaysPrice_Promotions($room_Promotions,$room_res,$check_in,$check_out);
                        if($room_price_Promotion > 0){
                            $room_Promotions_Exist          = '1';
                            $room_prices_arr_Promotion[]    = $room_price_Promotion;
                        }
                    }else{
                        $room_prices_arr_Promotion[]        = 0;
                    }
                    
                    $room_price                     = self::calculateRoomsAllDaysPrice($room_res,$check_in,$check_out,$customer_Id);
                    $room_prices_arr[]              = $room_price;
                    if($request->token == config('token_Alsubaee')){
                        $room_Quantity_Details      = DB::table('rooms_types')->where('id',$room_res->room_type_cat)->first();
                        $rooms_total_price          = $room_price;
                        $rooms_selling_price        = $room_price;
                    }else{
                        $rooms_total_price          = $room_price * $request->room;
                        $rooms_selling_price        = $room_price * $request->room;
                    }
                    
                    $room_Quantity                  += $room_res->quantity;
                    
                    $make_on_reqest_able = false;
                    if(isset($room_res->make_on_reqest_able)){
                        $make_on_reqest_able = true;
                    }
                    
                    // Quantity Working
                    $quantityDateRange = [];
                    if($request->token == config('token_HaramaynRooms')){
                        $roomId     = (int) $room_res->id; 
                        $checkIn    = $request->check_in;
                        $checkOut   = $request->check_out;
                        
                        // make date range
                        $RequestDateRange   = collect();
                        $start              = \Carbon\Carbon::parse($checkIn);
                        $end                = \Carbon\Carbon::parse($checkOut);
                        
                        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                            $RequestDateRange->push($date->format('Y-m-d'));
                        }
                        
                        $clientID       = DB::table('customer_subcriptions')->where('Auth_key', $request->token)->first();
                        $checkBookings  = DB::table('hotels_bookings')->where('customer_id', $clientID->id)->where('provider', 'Custome_hotel')
                                            // check room_code in JSON array
                                            ->whereRaw(
                                                "JSON_CONTAINS(reservation_response, ?, '$.hotel_details.rooms')",
                                                [json_encode(['room_code' => $roomId])]
                                            )
                                            // ->whereIn(
                                            //     DB::raw("JSON_UNQUOTE(JSON_EXTRACT(reservation_response, '$.hotel_details.checkIn'))"),
                                            //     $RequestDateRange
                                            // )
                                            // ->whereIn(
                                            //     DB::raw("JSON_UNQUOTE(JSON_EXTRACT(reservation_response, '$.hotel_details.checkOut'))"),
                                            //     $RequestDateRange
                                            // )
                                            ->get();
                        if($checkBookings->isNotEmpty()){
                            // return $checkBookings;
                            foreach($RequestDateRange as $dateRange){
                                $bookedRoomsQuantity        = 0;
                                $remainingRoomsQuantity     = $room_res->quantity;
                                foreach($checkBookings as $CB){
                                    if(isset($CB->reservation_response) && $CB->reservation_response != null && $CB->reservation_response != '' && !empty($CB->reservation_response)){
                                        $reservation_response = json_decode($CB->reservation_response, true);
                                        if(isset($reservation_response['hotel_details']) && $reservation_response['hotel_details'] != null && $reservation_response['hotel_details'] != '' && !empty($reservation_response['hotel_details'])){
                                            if(!is_string($reservation_response['hotel_details'])){
                                                $hotel_details  = $reservation_response['hotel_details'];
                                                $checkIn        = $hotel_details['checkIn'] ?? '';
                                                $checkOut       = $hotel_details['checkOut'] ?? '';
                                                // if($dateRange == $checkIn || $dateRange == $checkOut || ($dateRange >= $checkIn && $dateRange <= $checkOut)){
                                                if(Carbon::parse($dateRange)->between($checkIn, $checkOut)){
                                                    if(isset($hotel_details['rooms']) && is_array($hotel_details['rooms'])){
                                                        foreach($hotel_details['rooms'] as $val_Rooms){
                                                            if($val_Rooms['room_rates']){
                                                                $bookedRoomsQuantity += (int) $val_Rooms['room_rates'][0]['room_qty'];
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $dataRangeObject    = [
                                    'date'          => $dateRange,
                                    'booked'        => $bookedRoomsQuantity,
                                    'remaining'     => max(0, $remainingRoomsQuantity - $bookedRoomsQuantity),
                                ];
                                array_push($quantityDateRange,$dataRangeObject);
                            }
                        }else{
                            foreach ($RequestDateRange as $dateRange) {
                                $quantityDateRange[] = [
                                    'date'      => $dateRange,
                                    'booked'    => 0,
                                    'remaining' => $room_res->quantity,
                                ];
                            }
                        }
                        
                    }
                    // Quantity Working
                    
                    $hasZeroRemaining = collect($quantityDateRange)->contains(function ($day) {
                        return $day['remaining'] <= 0;
                    });
                    
                    $cancellation_policy_arr = [];
                    if (!empty($room_res->cancellation_details)) {
                        $decoded                        = json_decode($room_res->cancellation_details, true);
                        if ($decoded) {
                            $cancellation_policy_arr[]  = $decoded;
                        }
                    }
                    
                    if (!$hasZeroRemaining) {
                        $options_room[]                 = (Object)[
                            'make_on_reqest_able'       => $make_on_reqest_able,
                            'booking_req_id'            => $room_res->id,
                            'allotment'                 => $room_res->quantity - $room_res->booked,
                            'room_name'                 => $room_res->room_type_name,
                            'room_code'                 => $room_res->room_type_cat,
                            'request_type'              => $room_res->rooms_on_rq,
                            'board_id'                  => $room_res->room_meal_type,
                            'board_code'                => $room_res->room_meal_type,
                            //'rooms_total_price'             => $room_price * $request->room,
                            //'rooms_selling_price'           => $room_price * $request->room,
                            'rooms_total_price'             => $rooms_total_price,
                            'rooms_selling_price'           => $rooms_selling_price,
                            'rooms_total_price_Promotion'   => $room_price_Promotion * $request->room,
                            'rooms_selling_price_Promotion' => $room_price_Promotion * $request->room,
                            'rooms_qty'                 => 1,
                            'room_Quantity'             => $room_res->quantity ?? 0,
                            'adults'                    => $room_res->max_adults ?? $rooms_adults[0] ?? '',
                            'childs'                    => $room_res->max_child ?? $rooms_childs[0] ?? '',
                            'cancliation_policy_arr'    => $cancellation_policy_arr ?? [],
                            'rooms_list'                => $room_res,
                            'room_supplier_code'        => $supplier_Details->room_supplier_code ?? '',
                            'room_Promotions_Exist'     => $room_Promotions_Exist,
                            'room_Promotions'           => $room_Promotions,
                            'room_view'                 => $room_res->room_view ?? '',
                            'quantityDateRange'         => $quantityDateRange,
                        ];
                    }
                    
                    // return $options_room;
                }
                
                if(isset($room_prices_arr_Promotion[0]) && $room_prices_arr_Promotion[0] > 0){
                    $min_price = 0;
                    if(!empty($room_prices_arr_Promotion)){
                        $min_price = min($room_prices_arr_Promotion);
                    }
                    
                    $max_price = 0;
                    if(!empty($room_prices_arr_Promotion)){
                        $max_price = max($room_prices_arr_Promotion);
                    }
                    
                    $min_price_Actual = 0;
                    if(!empty($room_prices_arr)){
                        $min_price_Actual = min($room_prices_arr);
                    }
                    
                    $max_price_Actual = 0;
                    if(!empty($room_prices_arr)){
                        $max_price_Actual = max($room_prices_arr);
                    }
                }else{
                    $min_price_Actual = 0;
                    $max_price_Actual = 0;
                    
                    $min_price = 0;
                    if(!empty($room_prices_arr)){
                        $min_price = min($room_prices_arr);
                    }
                    
                    $max_price = 0;
                    if(!empty($room_prices_arr)){
                        $max_price = max($room_prices_arr);
                    }
                }
                
                $hotel_list_item            = (Object)[
                    'hotel_provider'        => 'Custome_hotel',
                    'admin_markup'          => $admin_custom_markup,
                    'customer_markup'       => $customer_custom_hotel_markup,
                    'admin_markup_type'     => 'Percentage',
                    'customer_markup_type'  => $customer_custom_hotel_markup_type,
                    'hotel_id'              => $hotel_res->id,
                    'hotel_name'            => $hotel_res->property_name,
                    'room_Quantity'         => $room_Quantity ?? 0,
                    'stars_rating'          => (float)$hotel_res->star_type ?? $stars_rating ?? 1,
                    'hotel_curreny'         => $hotel_currency,
                    'min_price_Actual'      => $min_price_Actual * $request->room,
                    'max_price_Actual'      => $max_price_Actual * $request->room,
                    'min_price'             => $min_price * $request->room,
                    'max_price'             => $max_price * $request->room,
                    'rooms_options'         => $options_room,
                    'latitude_Hotel'        => $hotel_res->latitude,
                    'longitude_Hotel'       => $hotel_res->longitude,
                    'client_Id'             => $client_Id,
                    'check_in'              => $check_in,
                    'check_out'             => $check_out,
                ];
                
                // Sale Stop
                $sale_Stop                          = 'NO';
                if($customer_Id == '28'){
                    if($max_price > 0){
                        // Check Hotel
                        $check_HSL                  = DB::table('hotels')->where('id',$hotel_res->id)->where('stop_Sale_Status','Stop')->first();
                        if(!empty($check_HSL)){
                            $sale_Stop              = 'YES';
                        }
                        
                        // Check Rooms
                        $rooms_options              = $hotel_list_item->rooms_options;
                        foreach($hotel_list_item->rooms_options as $key => $val_RD){
                            $check_RSL              = DB::table('rooms')->where('id',$val_RD->booking_req_id)->where('stop_Sale_Status','Stop')->first();
                            if(!empty($check_RSL)){
                                unset($hotel_list_item->rooms_options[$key]);
                                $sale_Stop          = 'YES';
                            }
                            
                            // Check Date Wise Rooms
                            $check_DW_SL            = DB::table('stop_Sale_Date_Wise')->where('room_id',$val_RD->booking_req_id)->where('status','Stop')->first();
                            if(!empty($check_DW_SL)){
                                if($check_in >= $check_DW_SL->available_from && $check_in <= $check_DW_SL->available_to){
                                    $sale_Stop      = 'YES';
                                }else{
                                    if($check_out >= $check_DW_SL->available_from && $check_out <= $check_DW_SL->available_to){
                                        $sale_Stop  = 'YES';
                                    }
                                }
                            }
                        }
                    }
                }
                // Sale Stop
                
                if($sale_Stop == 'NO'){
                    if(count($options_room) > 0){
                        $hotel_first_char = substr($hotel_res->property_name, 0, 10);
                        if($hotel_first_char == $destination_first_char){
                            if(isset($hotel_list_item->hotel_provider) && $max_price > 0){
                                array_push($hotels_list_arr_match,$hotel_list_item);
                            }
                        }else{
                            if(isset($hotel_list_item->hotel_provider) && $max_price > 0){
                                array_push($hotels_list_arr,$hotel_list_item);
                            }
                        }
                    }
                }
            }
        }
        
        info('all hotels list');
        info($hotels_list_arr);
        
        // ************************************************************ //
        // Custom Hotels Providers
        // ************************************************************ //
        
        $hotel_city = $destination;
        $hotel_city_changed = $destination;
        
        if($hotel_city == 'Al Madinah Al Munawwarah'){
            $hotel_city_changed = 'Medina';
        }
        
        if($hotel_city == 'Mecca'){
            $hotel_city_changed = 'Makkah';
        }
        
        if($hotel_city == 'Madinah'){
            $hotel_city_changed = 'Medina';
        }
        
        if($hotel_city == 'مكة'){
            $hotel_city_changed = 'Makkah';
        }
        
        if($hotel_city == 'Taif'){
            $hotel_city_changed = "Ta'if";
        }
        
        $rooms_adults = $adult_per_room;
        $rooms_childs = $child_per_room;
        
        $all_hotel_providers = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('provider_id','!=','NULL')->where('provider_id','!=','')->get();
        // return $all_hotel_providers;
        if(isset($all_hotel_providers)){
            foreach($all_hotel_providers as $hotel_provider_res){
                $provider_markup_data   = DB::table('become_provider_markup')->where('customer_id',$hotel_provider_res->provider_id)->where('status','1')->first();
                $all_hotels             = DB::table('hotels')->where('property_city',$hotel_city_changed)->where('owner_id',$hotel_provider_res->provider_id)->get();
                
                // Manage Markups 
                $admin_custom_hotel_pro_markup  = 0;
                $customer_markup                = 0;
                foreach($markup as $data){
                    if($data->added_markup == 'synchtravel'){
                        if($data->provider == $hotel_provider_res->provider){
                            $admin_custom_hotel_pro_markup = $data->markup_value;
                        }
                        
                        if($data->provider == 'All'){
                            $admin_custom_hotel_pro_markup =$data->markup_value;
                        }   
                    }
                    if($data->added_markup == 'Haramayn_hotels'){
                        $customer_markup =  $data->markup_value;
                    }
                }
                
                $custom_hotels      = [];
                $hotel_list_item    = [];
                
                foreach($all_hotels as $hotel_res){
                    $rooms_found            = [];
                    $rooms_ids              = [];
                    $rooms_qty              = [];
                    $counter                = 0;
                    $total_adults_in_rooms  = 0;
                    $total_childs_in_rooms  = 0;
                    // return $rooms_adults;
                    
                    foreach($rooms_adults as $index => $adult_res){
                        $rooms = DB::table('rooms')
                                    ->where('availible_from','<=',$check_in)
                                    ->where('availible_to','>=',$check_out)
                                    ->whereRaw('booked < quantity')
                                    ->where('max_adults',$adult_res)
                                    ->where('display_on_web','true')
                                    ->where('hotel_id',$hotel_res->id)
                                    ->where('owner_id',$hotel_provider_res->provider_id)
                                    // ->where('rooms_on_rq','0')
                                    ->get();
                                    
                        if(count($rooms) > 0){
                            // $any_room_found = false;
                            foreach($rooms as $room_res){
                                if (!in_array($room_res->id, $rooms_ids)) {
                                    $rooms_ids[]             = $room_res->id;
                                    $aval_qty                = $room_res->quantity - $room_res->booked;
                                    $rooms_qty[]             = $aval_qty;
                                    $total_adults_in_rooms   += ($room_res->max_adults * $aval_qty);
                                    $total_childs_in_rooms   += ($room_res->max_child * $aval_qty);
                                    $rooms_found[]           = $room_res;
                                }
                            }
                        }
                    }
                    
                    // $hotel_currency     = '';
                    $hotel_currency = $hotel_res->currency_symbol ?? '';
                    if($hotel_res->currency_symbol == '£' || $hotel_res->currency_symbol == '﷼')  {
                        $hotel_currency = 'SAR';
                    }
                    
                    $options_room       = [];
                    $room_prices_arr    = [];
                    
                    if($total_adults_in_rooms >= $adult_searching && $total_childs_in_rooms >= $child_searching){
                        foreach($rooms_found as $room_res){
                            
                            $room_price         = self::calculateRoomsAllDaysPrice($room_res,$check_in,$check_out,$sub_customer_id->id);
                            $provider_markup    = 0;
                            if(isset($provider_markup_data)){
                                if($provider_markup_data->markup == 'Percentage'){
                                    $provider_markup = ($room_price ?? '0' * $provider_markup_data->markup_value) / 100;
                                }else{
                                    $provider_markup = $provider_markup_data->markup_value;
                                }
                            }
                            
                            $room_price         = $room_price + $provider_markup;
                            $room_prices_arr[]  = $room_price;
                            $options_room[]     = (Object)[
                                'booking_req_id'            => $room_res->id,
                                'allotment'                 => $room_res->quantity - $room_res->booked,
                                'room_name'                 => $room_res->room_type_name,
                                'room_code'                 => $room_res->room_type_cat,
                                'request_type'              => $room_res->rooms_on_rq,
                                'board_id'                  => $room_res->room_meal_type,
                                'board_code'                => $room_res->room_meal_type,
                                'rooms_total_price'         => $room_price,
                                'rooms_selling_price'       => $room_price,
                                'rooms_qty'                 => 1,
                                'adults'                    => $rooms_adults[0] ?? $room_res->max_adults,
                                'childs'                    => $rooms_childs[0] ?? $room_res->max_child,
                                'cancliation_policy_arr'    => [],
                                'rooms_list'                => []
                            ];
                        }
                        
                        $min_price = 0;
                        if(!empty($room_prices_arr)){
                            $min_price = min($room_prices_arr);
                        }
                        
                        $max_price = 0;
                        if(!empty($room_prices_arr)){
                            $max_price = max($room_prices_arr);
                        }
                        
                        if($hotel_res->star_type == '5'){
                            $stars_rating = 5;
                        }
                        
                        if($hotel_res->star_type == '4'){
                            $stars_rating = 4;
                        }
                        
                        if($hotel_res->star_type == '3'){
                            $stars_rating = 3;
                        }
                        
                        if($hotel_res->star_type == '2'){
                            $stars_rating = 2;
                        }
                        
                        if($hotel_res->star_type == '1'){
                            $stars_rating = 1;
                        }
                        
                        $hotel_list_item = (Object)[
                            'hotel_provider'        => 'custom_hotel_provider',
                            'custom_hotel_provider' => $hotel_provider_res->provider,
                            'admin_markup'          => $admin_hotel_bed_markup ?? $admin_custom_hotel_pro_markup,
                            'customer_markup'       => $customer_custom_hotel_markup ?? $customer_markup,
                            'admin_markup_type'     => 'Percentage',
                            'customer_markup_type'  => $customer_custom_hotel_markup_type ?? 'Percentage',
                            'hotel_id'              => $hotel_res->id,
                            'hotel_name'            => $hotel_res->property_name,
                            'stars_rating'          => $stars_rating ?? $hotel_res->star_type,
                            'hotel_curreny'         => $hotel_currency,
                            'min_price'             => $min_price * $request->room,
                            'max_price'             => $max_price * $request->room,
                            'rooms_options'         => $options_room,
                            'latitude_Hotel'        => $hotel_res->latitude,
                            'longitude_Hotel'       => $hotel_res->longitude,
                        ];
                        
                        $hotel_first_char = substr($hotel_res->property_name, 0, 10);
                        if($hotel_first_char == $destination_first_char){
                            if(isset($hotel_list_item->hotel_provider) && $max_price > 0){
                                array_push($hotels_list_arr_match,$hotel_list_item);
                            }
                        }else{
                            if(isset($hotel_list_item->hotel_provider) && $max_price > 0){
                                array_push($hotels_list_arr,$hotel_list_item);
                            }
                        }
                    }
                }
            }
        }
        
        // ************************************************************ //
        // Travelenda Provider
        // ************************************************************ //
        
        $country                    = $request->slc_nationality;
        $countryList                = DB::table('tboHoliday_Country_List')->whereRaw('LOWER(Name) = ?', [strtolower($country)])->first();
        $country_nationality        = $countryList->Code;
        
        $rooms_no                   = 1;
        $room_request_create        = [];
        foreach($request->rooms_counter as $index => $room_counter){
            
            $child_age          = [];
            $childern           = $request->children[$index];
            $child_age_index    = 'child_ages'.$room_counter;
            $child_ages         = $request->$child_age_index;
            for($i = 0; $i<$childern; $i++){
                array_push($child_age,(string)$child_ages[$i]);
            }
            
            $single_room = (object)[
                'Room'          => $rooms_no++,
                'Adults'        => $request->Adults[$index],
                'Children'      => $request->children[$index],
                'ChildrenAge'   => $child_age
            ];
            
            array_push($room_request_create,$single_room);
        }
        
        // return $room_request_create;
        
        function travellandasearch($CityId, $CheckInDate, $CheckOutDate, $res_data, $country_nationality){
            $url    = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
            $data   = array('case' => 'travelandaSearch', 'CityId' => $CityId, 'CheckInDate' => $CheckInDate, 'CheckOutDate' => $CheckOutDate, 'res_data' => json_encode($res_data), 'country_nationality' =>$country_nationality);
            
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
        
        $responseData3              = travellandasearch($CityId, $check_in, $check_out, $room_request_create, $country_nationality);
        // return $responseData3;
        $result_travellanda         = json_decode($responseData3);
        
        $travellanda_obj            = [];
        $travelenda_hotels_count    = 0;
        $travelenda_curreny         = '';
        if(isset($result_travellanda->Body->HotelsReturned)){
            $travelenda_curreny                 = $result_travellanda->Body->Currency;
            if($result_travellanda->Body->HotelsReturned > 1){
                if(isset($result_travellanda->Body->Hotels->Hotel)){
                    $travellanda_obj            = $result_travellanda->Body->Hotels->Hotel;
                    $travelenda_hotels_count    = $result_travellanda->Body->HotelsReturned ?? '0';
                }
            }
        }
        // return $travellanda_obj;
        foreach($travellanda_obj as $travel_res){
            $rooms_list             = [];
            $prices_arr             = [];
            $option_list            = [];
            
            // return $travellanda_obj;
            
            if(isset($travel_res->Options)){
                $Options            = $travel_res->Options;
                if(isset($Options->Option) && is_array($Options->Option)){
                    foreach($Options->Option as $room_res){
                        // return $room_res;
                        
                        $options_room   = [];
                        $rooms_qty      = 0;
                        $total_adults   = 0;
                        $total_childs   = 0;
                        $room_name      = '';
                        
                        if(isset($room_res->Rooms->Room)){
                            if (is_array($room_res->Rooms->Room)) {
                                // return 'IF';
                                
                                $rooms_qty              = count($room_res->Rooms->Room);
                                $room_name              = $room_res->Rooms->Room[0]->RoomName;
                                foreach($room_res->Rooms->Room as $room_list_res){
                                    $daily_prices       = [];
                                    if(isset($room_list_res->DailyPrices)){
                                      $daily_prices     = $room_list_res->DailyPrices->DailyPrice;
                                    }
                                   
                                    $total_adults       += $room_list_res->NumAdults;
                                    $total_childs       += $room_list_res->NumChildren;
                                    
                                    $options_room[]     = (Object)[
                                        'room_id'       => $room_list_res->RoomId,
                                        'room_name'     => $room_list_res->RoomName,
                                        'adults'        => $room_list_res->NumAdults,
                                        'childs'        => $room_list_res->NumChildren,
                                        'room_price'    => $room_list_res->RoomPrice,
                                        'daily_prices'  => $daily_prices,
                                    ];
                                }
                            } else {
                                // return 'ELSE';
                                
                                $rooms_qty = 1;
                                $room_name = $room_res->Rooms->Room->RoomName;
                                
                                $daily_prices = [];
                                if(isset($room_list_res->DailyPrices)){
                                    $daily_prices = $room_res->Rooms->Room->DailyPrices->DailyPrice;
                                }
                               
                                $total_adults += $room_res->Rooms->Room->NumAdults;
                                $total_childs += $room_res->Rooms->Room->NumChildren;
                               
                                $options_room[] = (Object)[
                                    'room_id'       => $room_res->Rooms->Room->RoomId,
                                    'room_name'     => $room_res->Rooms->Room->RoomName,
                                    'adults'        => $room_res->Rooms->Room->NumAdults,
                                    'childs'        => $room_res->Rooms->Room->NumChildren,
                                    'room_price'    => $room_res->Rooms->Room->RoomPrice,
                                    'daily_prices'  => $daily_prices,
                                ];
                            }
                        }
                        
                        if(isset($room_res->OptionId)){
                            if(count($options_room) > 1){
                                $seenPrices         = [];
                                $options_room_NV    = array_filter($options_room, function($room) use (&$seenPrices) {
                                    if (in_array($room->room_price, $seenPrices)) {
                                        return false;
                                    }
                                    $seenPrices[]   = $room->room_price;
                                    return true;
                                });
                                $options_room_FV    = array_values($options_room_NV);
                                
                                if(count($options_room_FV) == count($options_room)){
                                    $rooms_qty = 1;
                                }
                                
                                foreach($options_room_FV as $va_RO){
                                    $option_list[]                  = (Object)[
                                        'booking_req_id'            => $room_res->OptionId,
                                        'allotment'                 => $rooms_qty,
                                        'room_name'                 => $va_RO->room_name,
                                        'room_code'                 => '',
                                        'request_type'              => $room_res->OnRequest,
                                        'board_id'                  => $room_res->BoardType,
                                        'board_code'                => '',
                                        'rooms_total_price'         => $va_RO->room_price * $rooms_qty,
                                        'rooms_selling_price'       => $va_RO->room_price * $rooms_qty,
                                        'rooms_qty'                 => $rooms_qty,
                                        'adults'                    => (float)$va_RO->adults,
                                        'childs'                    => (float)$va_RO->childs,
                                        'cancliation_policy_arr'    => [],
                                        'rooms_list'                => $va_RO
                                    ];
                                    $prices_arr[]                   = $va_RO->room_price;
                                }
                            }else{
                                $option_list[]                  = (Object)[
                                    'booking_req_id'            => $room_res->OptionId,
                                    'allotment'                 => $rooms_qty,
                                    'room_name'                 => $room_name,
                                    'room_code'                 => '',
                                    'request_type'              => $room_res->OnRequest,
                                    'board_id'                  => $room_res->BoardType,
                                    'board_code'                => '',
                                    'rooms_total_price'         => $room_res->TotalPrice * $rooms_qty,
                                    'rooms_selling_price'       => $room_res->TotalPrice * $rooms_qty,
                                    'rooms_qty'                 => $rooms_qty,
                                    'adults'                    => $total_adults,
                                    'childs'                    => $total_childs,
                                    'cancliation_policy_arr'    =>[],
                                    'rooms_list'                => $options_room
                                ];
                                $prices_arr[]                   = $room_res->TotalPrice;
                            }
                        }
                    }
                }else{
                    $options_room   = [];
                    $rooms_qty      = 0;
                    $total_adults   = 0;
                    $total_childs   = 0;
                    $room_name      = '';
                    
                    $room_res = $Options->Option;
                    
                    if(isset($room_res->Rooms->Room)){
                        if (is_array($room_res->Rooms->Room)) {
                            // return 'IF';
                            
                            $rooms_qty              = count($room_res->Rooms->Room);
                            $room_name              = $room_res->Rooms->Room[0]->RoomName;
                            foreach($room_res->Rooms->Room as $room_list_res){
                                $daily_prices       = [];
                                if(isset($room_list_res->DailyPrices)){
                                  $daily_prices     = $room_list_res->DailyPrices->DailyPrice;
                                }
                               
                                $total_adults       += $room_list_res->NumAdults;
                                $total_childs       += $room_list_res->NumChildren;
                                
                                $options_room[]     = (Object)[
                                    'room_id'       => $room_list_res->RoomId,
                                    'room_name'     => $room_list_res->RoomName,
                                    'adults'        => $room_list_res->NumAdults,
                                    'childs'        => $room_list_res->NumChildren,
                                    'room_price'    => $room_list_res->RoomPrice,
                                    'daily_prices'  => $daily_prices,
                                ];
                            }
                            
                        } else {
                            // return 'ELSE';
                            
                            $rooms_qty = 1;
                            $room_name = $room_res->Rooms->Room->RoomName;
                            
                            $daily_prices = [];
                            if(isset($room_list_res->DailyPrices)){
                              $daily_prices = $room_res->Rooms->Room->DailyPrices->DailyPrice;
                            }
                           
                            $total_adults += $room_res->Rooms->Room->NumAdults;
                            $total_childs += $room_res->Rooms->Room->NumChildren;
                           
                            $options_room[] = (Object)[
                                'room_id'       => $room_res->Rooms->Room->RoomId,
                                'room_name'     => $room_res->Rooms->Room->RoomName,
                                'adults'        => $room_res->Rooms->Room->NumAdults,
                                'childs'        => $room_res->Rooms->Room->NumChildren,
                                'room_price'    => $room_res->Rooms->Room->RoomPrice,
                                'daily_prices'  => $daily_prices,
                            ];
                        }
                    }
                    
                    if(isset($room_res->OptionId)){
                        if(count($options_room) > 1){
                            $seenPrices         = [];
                            $options_room_NV    = array_filter($options_room, function($room) use (&$seenPrices) {
                                if (in_array($room->room_price, $seenPrices)) {
                                    return false;
                                }
                                $seenPrices[]   = $room->room_price;
                                return true;
                            });
                            $options_room_FV    = array_values($options_room_NV);
                            
                            if(count($options_room_FV) == count($options_room)){
                                $rooms_qty = 1;
                            }
                            
                            foreach($options_room_FV as $va_RO){
                                $option_list[]                  = (Object)[
                                    'booking_req_id'            => $room_res->OptionId,
                                    'allotment'                 => $rooms_qty,
                                    'room_name'                 => $va_RO->room_name,
                                    'room_code'                 => '',
                                    'request_type'              => $room_res->OnRequest,
                                    'board_id'                  => $room_res->BoardType,
                                    'board_code'                => '',
                                    'rooms_total_price'         => $va_RO->room_price * $rooms_qty,
                                    'rooms_selling_price'       => $va_RO->room_price * $rooms_qty,
                                    'rooms_qty'                 => $rooms_qty,
                                    'adults'                    => (float)$va_RO->adults,
                                    'childs'                    => (float)$va_RO->childs,
                                    'cancliation_policy_arr'    => [],
                                    'rooms_list'                => $va_RO
                                ];
                                $prices_arr[]                   = $va_RO->room_price;
                            }
                        }else{
                            $option_list[]                  = (Object)[
                                'booking_req_id'            => $room_res->OptionId,
                                'allotment'                 => $rooms_qty,
                                'room_name'                 => $room_name,
                                'room_code'                 => '',
                                'request_type'              => $room_res->OnRequest,
                                'board_id'                  => $room_res->BoardType,
                                'board_code'                => '',
                                'rooms_total_price'         => $room_res->TotalPrice * $rooms_qty,
                                'rooms_selling_price'       => $room_res->TotalPrice * $rooms_qty,
                                'rooms_qty'                 => $rooms_qty,
                                'adults'                    => $total_adults,
                                'childs'                    => $total_childs,
                                'cancliation_policy_arr'    =>[],
                                'rooms_list'                => $options_room
                            ];
                            $prices_arr[]                   = $room_res->TotalPrice;
                        }
                    }
                }
            }
           
            if(!empty($prices_arr)){
                $max_hotel_price = max($prices_arr);
                $min_hotel_price = min($prices_arr);
            }
            else{
                $min_hotel_price = 0;
                $max_hotel_price = 0;  
            }
            
            $StarRating = 1;
            if (isset($travel_res->StarRating) && is_numeric($travel_res->StarRating)) {
                $StarRating = round((float)$travel_res->StarRating);
            }
            
            $hotel_list_item = (Object)[
                'hotel_provider'        => 'travelenda',
                'admin_markup'          => $admin_travelenda_markup,
                'customer_markup'       => $customer_custom_hotel_markup ?? $customer_markup,
                'admin_markup_type'     => 'Percentage',
                'customer_markup_type'  => 'Percentage',
                'hotel_id'              => $travel_res->HotelId,
                'hotel_name'            => $travel_res->HotelName,
                'stars_rating'          => $StarRating,
                'hotel_curreny'         => $travelenda_curreny,
                'min_price'             => $min_hotel_price * $request->room,
                'max_price'             => $max_hotel_price * $request->room,
                'rooms_options'         => $option_list,
            ];
            
            $hotel_first_char = substr($travel_res->HotelName, 0, 10);
            if($hotel_first_char == $destination_first_char){
                array_push($hotels_list_arr_match,$hotel_list_item);
            }else{
                array_push($hotels_list_arr,$hotel_list_item);
            } 
        }
        
        // ************************************************************ //
        // Hotel Bed Provider
        // ************************************************************ //
        
        $counts                 = [];
        $rooms_no               = 1;
        $room_request_create    = [];
        $adults_counts_arr      = array_count_values($request->Adults);
        
        foreach($request->rooms_counter as $index => $room_counter){
            
            $others_pax = [];
            $childern = $request->children[$index];
            
            // echo "child is $childern ";
            $child_age_index = 'child_ages'.$room_counter;
            
            $child_ages = $request->$child_age_index;
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
                    'adults'=>$request->Adults[$index],
                    'children'=>$request->children[$index],
                    'paxes'=>$others_pax
                ];
                
                array_push($room_request_create,$single_room);
        }
        
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
        
        $final_convert_rooms = [];
        foreach ($counts as $identifier => $count) {
            $rooms_data = json_decode($identifier, true);
            $rooms_data['rooms'] = $count;
            $rooms_data['paxes'] = json_decode($rooms_data['paxes']);
            $final_convert_rooms[] = (object)$rooms_data;
            // echo "Object with values: " . print_r($rooms_data, true) . " appears {$count} times." . PHP_EOL;
        }
        
        if($request->lat == NULL && $request->long == NULL && $request->cityd == 'Jerusalem'){
            $lat    = '31.7683';
            $long   = '35.2137';
        }
        else{
            $lat    = $request->lat;
            $long   = $request->long;  
        }
        
        $room_request_create    = $final_convert_rooms;
        $newstart               = $check_in;
        $newend                 = $check_out;
        
        function hotelbedssearch($newstart, $newend, $res_hotel_beds, $lat, $long){
            $url     = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi_live.php";
            $data   = array('case' => 'serach_hotelbeds', 'CheckIn' => $newstart, 'CheckOut' => $newend, 'res_hotel_beds' => json_encode($res_hotel_beds), 'lat' =>$lat,'long' =>$long);
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
        
        $responseData3      = hotelbedssearch($check_in, $check_out, $room_request_create, $lat, $long);
        // return $responseData3;
        $result_hotelbeds   = json_decode($responseData3);
        // return $result_hotelbeds;
        $hotel_bed_counts   = 0;
        
        if(isset($result_hotelbeds->hotels->total)){
            $hotel_bed_counts = $result_hotelbeds->hotels->total;
        }
        
        if(isset($result_hotelbeds->hotels->hotels)){
            foreach($result_hotelbeds->hotels->hotels as $hotel_res){
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
                                                'type'          => $cancel_res->type ?? 'Fix Amount',
                                                'amount'        => $cancel_res->amount,
                                                'from_date'     => $cancel_res->from,
                                              ];
                                          $cancliation_policy_arr[] = $cancel_tiem;
                                      }
                                  }
                                    
                                    $options_room[] = (Object)[
                                        'booking_req_id'            => $room_list_res->rateKey,
                                        'allotment'                 => $room_list_res->allotment,
                                        'room_name'                 => $room_res->name,
                                        'room_code'                 => $room_res->code,
                                        'request_type'              => '',
                                        'board_id'                  => $room_list_res->boardName,
                                        'board_code'                => $room_list_res->boardCode,
                                        'rooms_total_price'         => $room_list_res->net,
                                        'rooms_selling_price'       => $selling_rate,
                                        'rooms_qty'                 => $room_list_res->rooms,
                                        'adults'                    => $room_list_res->adults,
                                        'childs'                    => $room_list_res->children,
                                        'cancliation_policy_arr'    => $cancliation_policy_arr,
                                        'rooms_list'                => []
                                    ];
                                }
                            }
                        }
                    }
                }
                
                $hotel_list_item = (Object)[
                    'hotel_provider'        => 'hotel_beds',
                    'admin_markup_type'     => 'Percentage',
                    'admin_markup'          => $admin_hotel_bed_markup,
                    'customer_markup_type'  => 'Percentage',
                    'customer_markup'       => $customer_custom_hotel_markup ?? $customer_markup,
                    'hotel_id'              => $hotel_res->code,
                    'hotel_name'            => $hotel_res->name,
                    'stars_rating'          => (float)$hotel_res->categoryCode ?? 1,
                    'hotel_curreny'         => $hotel_res->currency,
                    'min_price'             => $hotel_res->minRate,
                    'max_price'             => $hotel_res->maxRate,
                    'rooms_options'         => $options_room,
                    'latitude_Hotel'        => $hotel_res->latitude,
                    'longitude_Hotel'       => $hotel_res->longitude,
                ];
                
                $hotel_first_char = substr($hotel_res->name, 0, 10);
                if($hotel_first_char == $destination_first_char){
                    array_push($hotels_list_arr_match,$hotel_list_item);
                }else{
                    array_push($hotels_list_arr,$hotel_list_item);
                }
            }
        }
        
        // ************************************************************ //
        // Hotel Bed End
        // ************************************************************ //
        
        // ************************************************************ //
        // Stuba
        // ************************************************************ //
        
        $rooms_no               = 1;
        $room_request_create    = [];
        $stuba_hotels_items     = [];
        $stuba_hotel_count      = 0;
        
        if (!is_array($request->rooms_counter)) {
            $request->rooms_counter = json_decode($request->rooms_counter);
        }
        
        foreach ($request->rooms_counter as $index => $room_counter) {
            $child_age = [];
            
            if (!is_array($request->children)) {
                $request->children = json_decode($request->children);
            }
            
            $childern           = $request->children[$index];
            $child_age_index    = 'child_ages' . $room_counter;
            $child_ages         = $request->$child_age_index;
            for ($i = 0; $i < $childern; $i++) {
                array_push($child_age, $child_ages[$i]);
            }
            
            $single_room = (object)[
                'Room' => $rooms_no++,
                'Adults' => $request->Adults[$index],
                'Children' => $request->children[$index],
                'ChildrenAge' => $child_age,
            ];
            array_push($room_request_create, $single_room);
        }
        
        $roomsData      = $room_request_create;
        $city           = $destination_first_char;
        $arivaldate     = $check_in;
        $nights         = self::dateDiffInDays($check_in, $check_out);
        $country        = $request->slc_nationality;
        $countryList    = DB::table('tboHoliday_Country_List')->whereRaw('LOWER(Name) = ?', [strtolower($country)])->first();
        $country_code   = $countryList->Code;
        $hotels         = DB::table('stuba_cityids')->whereRaw('LOWER(Name) = ?', [strtolower($city)])->first();
        if (isset($hotels)) {
            $id                         = $hotels->cityid;
            $hotelsresults              = [];
            $hoteldetails               = DB::table('stuba_hotel_details')->WhereJsonContains('hotelRegion', [['CityID' => (string)$id]])->get();
            foreach ($hoteldetails as $details) {
                $region                 = json_decode($details->hotelRegion);
                $cityid                 = $region[0]->CityID ?? '';
                if ($cityid == (string)$id) {
                    $hotelsresults[]    = $details;
                }
            }
            
            foreach ($hotelsresults as $hotel) {
                $hotelscodes[]          = $hotel->hotelid;
            }
        }
        // return $hotelscodes;
        
        $customer_Hotel_markup_Stuba = $customer_custom_hotel_markup ?? $customer_markup ?? 0;
        if(isset($hotelscodes)){
            // $stuba_response = Stuba_Controller::stubaSearch($request,$hotelscodes,$arivaldate,$nights,$country_code,$roomsData,$customer_Hotel_markup_Stuba);
            // return $stuba_response;
        }
        
        if(isset($stuba_response['stuba_hotels_items'])){
            $stuba_hotels_items = $stuba_response['stuba_hotels_items'];
        }else{
            $stuba_hotels_items = [];
        }
        
        if(isset($stuba_response['stuba_hotel_count'])){
            $stuba_hotel_count  = $stuba_response['stuba_hotel_count'];
        }else{
            $stuba_hotel_count  = 0;
        }
        
        // ************************************************************ //
        // Stuba Search End
        // ************************************************************ //
        
        // ************************************************************ //
        //  SunHotels Start
        // ************************************************************ //
        
        $totalNumberSunhotel        = 0;
        $hotelsSunhotel             = [];
        // $sunhotelAdminMarkup        = 0;
        // $sunhotelCustomerMarkup     = 0;
        $sunhotelAdminMarkupType    = 'Percentage';
        $sunhotelCustomerMarkupType = 'Percentage';
        $destinationCode            = '';
        $sunhotel_destination       = DB::table('webbed_destination_codes')->where('DestinationName', $request->destination_name)->first();
        
        if ($sunhotel_destination) {
            $destinationCode = $sunhotel_destination->DestinationCode
                ?? $sunhotel_destination->DestinationCode_2
                ?? $sunhotel_destination->DestinationCode_3
                ?? $sunhotel_destination->DestinationCode_4;
        }
        
        $child_Age_Array = [];
        if(count($request->children) > 0){
            for($ca=0; $ca<count($request->children); $ca++){
                $new_ca     = $ca + 1;
                $make_Name = 'child_ages'.$new_ca;
                $child_ages = $request->$make_Name;
                // return $child_ages;
                array_push($child_Age_Array,$child_ages);
            }
        }
        $customer_Hotel_markup_SunHotel = $customer_custom_hotel_markup ?? $customer_markup ?? 0;
        if($destinationCode != ''){
            $responseData3          = SunHotel_Controller::sunHotelSearch($child_per_room,$CheckInDate,$CheckOutDate,$numberOfRooms,$destinationCode,$numberOfAdults,$request->room,$numberOfChildrenWithInfants,$request->Adults,$request->rooms_counter,$child_Age_Array,$request->children,$sunhotelAdminMarkup,$sunhotelCustomerMarkup,$customer_Hotel_markup_SunHotel);
            // return $responseData3;
            
            if(isset($responseData3['hotelsSunhotel'])){
                $hotelsSunhotel     = $responseData3['hotelsSunhotel'];
            }
            
            if(isset($responseData3['totalNumberSunhotel'])){
                $totalNumberSunhotel = $responseData3['totalNumberSunhotel'];
            }
        }
        
        // ************************************************************ //
        //  End SunHotels
        // ************************************************************ //
        
        // $final_hotel_Array = array_merge($hotels_list_arr_match,$hotels_list_arr);
        $final_hotel_Array = array_merge($stuba_hotels_items,$hotelsSunhotel,$hotels_list_arr_match,$hotels_list_arr);
        // $final_hotel_Array = array_merge($stuba_hotels_items);
        
        return response()->json([
            'status'                => 'success',
            'travelenda_count'      => $travelenda_hotels_count,
            'hotel_beds_counts'     => $hotel_bed_counts,
            'stuba_hotel_count'     => $stuba_hotel_count,
            'totalNumberSunhotel'   => $totalNumberSunhotel,
            'hotels_list'           => $final_hotel_Array,
        ]);
    }
    
    public function hotels_mata_apis(Request $request){
        // return 'ok';
        
        $token                  = $request->token;
        $hotel_code             = $request->hotel_code;
        $provider               = $request->provider;
        $hotels_detials_data    = [];
        
        // Get Details For Custom Hotel 
        if($provider == 'custom_hotel_provider'){
            $provider_data  = DB::table('custom_hotel_provider')->where('provider_name',$provider)->first();
            $hotel_data     = DB::table('hotels')->where('id',$hotel_code)->first();
            $customer_data  = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
            $faclility_arr  = [];
            
            if(isset($hotel_data->facilities)){
                $count      = 1;
                $facilities = unserialize($hotel_data->facilities);
                if(is_array($facilities)){
                    foreach($facilities as $facility){
                        if($count < 7){
                            $count++;
                            $faclility_arr[] = $facility;
                        }else{
                            break;
                        }
                    }
                }
            }
            
            // $webiste_Address        = $customer_data->webiste_Address;
            
            $hotel_address              = $hotel_data->property_address ?? '';
            if(isset($hotel_data->image_Url_Other_Dashboard) && $hotel_data->image_Url_Other_Dashboard != null && $hotel_data->image_Url_Other_Dashboard != ''){
                $image_address          = $hotel_data->image_Url_Other_Dashboard;
            }else{
                $image_address          = $customer_data->dashboard_Address ?? $customer_data->webiste_Address;
            }
            
            $hotels_detials_data    = [
                'image'             => $image_address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'',
                // 'image'             => $webiste_Address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'',
                'address'           => $hotel_address,
                'facilities'        => $faclility_arr
            ]; 
            return response()->json([
                'status'            => 'success',
                'details_data'      => $hotels_detials_data
            ]);
        }
        
        if($provider == 'Custome_hotel'){
            $hotel_data     = DB::table('hotels')->where('id',$hotel_code)->first();
            $customer_data  = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
            
            $faclility_arr = [];
            if(isset($hotel_data->facilities)){
                $count      = 1;
                $facilities = unserialize($hotel_data->facilities);
                if(is_array($facilities)){
                    foreach($facilities as $facility){
                        if($count < 7){
                            $count++;
                            $faclility_arr[] = $facility;
                        }else{
                            break;
                        }
                    }
                }
            }
          
            $hotel_address              = $hotel_data->property_address ?? '';
            if(isset($hotel_data->image_Url_Other_Dashboard) && $hotel_data->image_Url_Other_Dashboard != null && $hotel_data->image_Url_Other_Dashboard != ''){
                $image_address          = $hotel_data->image_Url_Other_Dashboard;
            }else{
                $image_address          = $customer_data->dashboard_Address ?? $customer_data->webiste_Address;
            }
            
            $hotels_detials_data    = [
                'image'             => $image_address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'',
                // 'image'         => 'https://system.alhijaztours.net/public/uploads/package_imgs/'.$hotel_data->property_img.'',
                'address'           => $hotel_address,
                'facilities'        => $faclility_arr
              ];
              
            return response()->json([
                'status' => 'success',
                'details_data' => $hotels_detials_data
            ]);
        }
        
        if($provider == 'hotel_beds'){
            $hotel_beds_code    = $hotel_code;
            $curl               = curl_init();
            $signature          = self::pr($hotel_beds_code);
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
            curl_close($curl);
            
            $hotels_details = json_decode($response);
            $faclility_arr  = [];
            if(isset($hotels_details->hotel->facilities)){
                $count = 1;
                foreach($hotels_details->hotel->facilities as $facility){
                    if($facility->description->content == 'Wi-fi' ||
                        $facility->description->content == 'Internet access' || $facility->description->content == 'TV' || $facility->description->content == 'Wake-up service' || $facility->description->content == 'Smoking rooms'
                        || $facility->description->content == 'Wheelchair-accessible' || $facility->description->content == 'Laundry service' || $facility->description->content == 'Banquet hall' 
                        || $facility->description->content == 'Non-smoking establishment' || $facility->description->content == 'Safe'){
                        
                        if($count < 7){
                            $count++;
                            $faclility_arr[] = $facility->description->content;
                        }else{
                            break;
                        }
                    }
                }
            }
            
            $hotel_address          = $hotels_details->hotel->address->content ?? '';
            $hotel_Image_Path       = $hotels_details->hotel->images[0]->path ?? '';
            $hotels_detials_data    = [
                'image'             => 'https://photos.hotelbeds.com/giata/bigger/'.$hotel_Image_Path.'',
                'address'           => $hotel_address,
                'facilities'        => $faclility_arr
            ];
             
            return response()->json([
                'status'            => 'success',
                'details_data'      => $hotels_detials_data
            ]);
        }
        
        if($provider == 'travelenda'){
            $data           = array(
                'case'      => 'GetHotelDetails',
                'HotelId'   => $hotel_code,
            );
            // return $data;
            $curl           = curl_init();
            curl_setopt_array($curl, array(
                //CURLOPT_URL => 'https://admin.synchronousdigital.com/synchtravelhotelapi/travellandaapi.php',
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
            // echo $response;die();
            curl_close($curl);
            
            $hotel_detail   = json_decode($response);
            $hotel_image    = 'https://localhost/haramaynhotels/public/admin_package/frontend/images/detail_img/no-photo-available-icon-4.jpg';
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
            
            $hotel_address          =  $hotel_detail->Body->Hotels->Hotel->Address ?? '';
            $hotels_detials_data    = [
                'image'             => $hotel_image,
                'address'           => $hotel_address,
                'facilities'        => $faclility_arr
            ];
            
            return response()->json([
                'status'        => 'success',
                'details_data'  => $hotels_detials_data
            ]);
        }
        
        if($provider == 'Stuba') {
            $data = DB::table('stuba_hotel_details')->where('hotelid',$hotel_code)->get();
            // return $data;
            foreach ($data as $hotel){
                // $hotelimages    = json_decode($hotel->hotelimages);
                // $url            = $hotelimages[0]->Url ?? '';
                
                $hotelObject    = [
                                    "hotel_images" => array_map(function ($url) {
                                        return "https://api.stuba.com" . $url;
                                    }, array_column(json_decode($hotel->hotelimages, true), 'Url')),
                                    "hotel_facilities" => array_map(function ($amenity) {
                                        return $amenity['Text'] ?? '';
                                    }, json_decode($hotel->hotelamenity, true))
                                ];
                function isImageUrl($url){
                    $headers = get_headers($url, 1);
                    if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'image') !== false) {
                        return true;
                    } else {
                        return false;
                    }
                }
                
                $images = [];
                foreach ($hotelObject['hotel_images'] as $image) {
                    if (isImageUrl($image)) { ;
                        $images[]   = $image;
                    } else {
                        $images[]   = 'https://as2.ftcdn.net/v2/jpg/04/70/29/97/1000_F_470299797_UD0eoVMMSUbHCcNJCdv2t8B2g1GVqYgs.jpg';
                    }
                }
            }
            
            $faclility_arr          =  $hotelObject['hotel_facilities'];
            $faclility_arr          = array_slice($faclility_arr, 0, 5);
            $address                = json_decode($hotel->hoteladdress, true);
            $address                =  $address['Address1'];
            $hotels_detials_data    = [
                'image'             => $images[0] ?? '',
                'address'           => $address ?? '',
                'facilities'        => $faclility_arr['Text'] ?? $faclility_arr  ?? '',
            ];
            
            return response()->json([
                'status'        => 'success',
                'details_data'  => $hotels_detials_data
            ]);
        }
        
        if($provider == 'Sunhotel') {
            $sunHotel_hotel_details = SunHotel_Controller::sunHotelDetails($hotel_code);
            // return $sunHotel_hotel_details;
            
            $hotels_detials_data    = [];
            if (!empty ($sunHotel_hotel_details) && isset ($sunHotel_hotel_details['hotels']['hotel'])) {
                $sunHotel_faclility_arr = [];
                $image_URL              = '';
                
                // return $data['hotels']['hotel']['features']['feature'][0]['@attributes']['name'];
                
                if(isset($sunHotel_hotel_details['hotels']['hotel']['images']['image'][0]['smallImage']['@attributes']['url'])){
                    $image_URL    = env('SUN_HOTEL_IMAGE_URL').$sunHotel_hotel_details['hotels']['hotel']['images']['image'][0]['smallImage']['@attributes']['url'];
                }
                
                if (isset($sunHotel_hotel_details['hotels']['hotel']['features']['feature'])) {
                    $sunHotel_facility = $sunHotel_hotel_details['hotels']['hotel']['features']['feature'];
                    for ($count = 0; $count < count($sunHotel_facility); $count++) {
                        if ($count < 7) {
                            $sunHotel_faclility_arr[] = $sunHotel_facility[$count]['@attributes']['name'];
                        } else {
                            break;
                        }
                    }
                }
                
                $hotels_detials_data = [
                    'message'       => 'success',
                    'image'         => $image_URL,
                    'address'       => $sunHotel_hotel_details['hotels']['hotel']['hotel.address'],
                    'facilities'    => $sunHotel_faclility_arr
                ];
            }
            
            return response()->json([
                'status'        => 'success',
                'details_data'  => $hotels_detials_data
            ]);
        }
    }
    
    // Custom Search
    public function custom_Search_Hotels(Request $request){
        // return $request;
        $search_Data        = self::customer_Hotel_Search($request);
        // return $search_Data;
        $decode_SD          = json_decode($search_Data->getContent());
        if(isset($decode_SD->hotels_list) && count($decode_SD->hotels_list) > 0){
            // return 'OK';
            $details_Data   = self::customer_Hotel_Detail($request,$decode_SD->hotels_list);
            return $details_Data;
        }else{
            return response()->json([
                'status'    => 'error',
                'message'   => 'Rooms Not Available',
            ]);
        }
    }
    
    public static function customer_Hotel_Search($request){
        try {
            $token                  = $request->token;
            $check_in               = $request->check_in;
            $check_out              = $request->check_out;
            $destination            = $request->destination ?? $request->destination_name;
            $destination_first_char = substr($request->destination_name, 0, 10);
            $adult_per_room         = $request->adult_per_room ?? $request->Adults;
            $child_per_room         = $request->child_per_room ?? $request->children;
            
            $adult_searching        = $request->adult_searching;
            $child_searching        = $request->child_searching;
            $hotel_search_request   = $request->hotel_search_request;
            $country_nationality    = $request->slc_nationality ?? $request->country_nationality;
            
            $CheckInDate            = $check_in;
            $CheckOutDate           = $check_out;
            
            $sub_customer_id        = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
            $markup                 = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)->orderBy('id','DESC')->get();
            
            $admin_travelenda_markup            = 0;
            $admin_hotel_bed_markup             = 0;
            $admin_custom_markup                = 0;
            
            $customer_markup                    = 0;
            $customer_custom_hotel_markup       = 0;
            $customer_custom_hotel_markup_type  = '';
            
            $sunhotelAdminMarkup                = 0;
            $sunhotelCustomerMarkup             = 0;
            
            $numberOfRooms                      = $request->room ?? $adult_per_room;
            $numberOfAdults                     = $request->adult ?? $adult_per_room;
            $numberOfChildrenWithInfants        = $request->child ?? $child_per_room;
            
            foreach($markup as $data){
                if($data->added_markup == 'synchtravel'){
                    
                    if($data->provider == 'CustomHotel'){
                        $admin_custom_markup        = $data->markup_value;
                    }
                    
                    if($data->provider == 'Ratehawk'){
                        // config(['Ratehawk' => $data->markup_value]);  
                    }
                    
                    if($data->provider == 'TBO'){
                        // config(['TBO' => $data->markup_value]);   
                    }
                    
                    if($data->provider == 'Travellanda'){
                        $admin_travelenda_markup    = $data->markup_value;
                    }
                    
                    if($data->provider == 'Hotelbeds'){
                        $admin_hotel_bed_markup     = $data->markup_value;
                    }
                    
                    if($data->provider == 'All'){
                        $admin_custom_markup        = $data->markup_value;
                        $admin_travelenda_markup    = $data->markup_value;
                        $admin_hotel_bed_markup     = $data->markup_value;
                        $sunhotelAdminMarkup        = $data->markup_value;
                    }
                } 
                
                if($data->added_markup == 'Haramayn_hotels'){
                    $customer_markup = $data->markup_value;
                }
            }
            
            $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id',$sub_customer_id->id)->where('status',1)->orderBy('id','DESC')->get();
            if(count($custom_hotel_markup) > 0){
                $customer_custom_hotel_markup       = $custom_hotel_markup[0]->markup_value ?? '';
                $customer_custom_hotel_markup_type  = $custom_hotel_markup[0]->markup ?? '';
            }
            
            // B2B Markup
            $markup_Find    = false;
            $user_Token     = $request->token;
            $b2b_Agent_Id   = $request->b2b_agent_id ?? '';
            $b2b_Markup     = DB::table('b2b_Agent_Markups')
                                ->where(function ($query) use ($user_Token, $b2b_Agent_Id) {
                                    $query->where('token', $user_Token)
                                    ->where('agent_Id', $b2b_Agent_Id)
                                    ->where('markup_status',1);
                                })
                                ->orWhere(function ($query) use ($user_Token, $b2b_Agent_Id) {
                                    $query->where('token', $user_Token)
                                    ->where('agent_Id', 0)
                                    ->where('markup_status',1);
                                })
                                ->get();
            if(!empty($b2b_Markup) && $b2b_Markup != null){
                foreach($b2b_Markup as $val_BAM){
                    if($val_BAM->agent_Id == $b2b_Agent_Id){
                        $customer_custom_hotel_markup_type  = $val_BAM->markup_Type; 
                        $customer_markup                    = $val_BAM->markup_Value;
                        $customer_custom_hotel_markup       = $val_BAM->markup_Value;
                        $markup_Find                        = true;
                    }
                }
                
                if($markup_Find != true){
                    foreach($b2b_Markup as $val_BAM){
                        if($val_BAM->agent_Id == 0){
                            $customer_custom_hotel_markup_type  = $val_BAM->markup_Type; 
                            $customer_markup                    = $val_BAM->markup_Value;
                            $customer_custom_hotel_markup       = $val_BAM->markup_Value;
                        }
                    }
                }
            }
            // B2B Markup
          
            $get_res    = DB::table('travellanda_get_cities')->where('CityName',$destination)->first();
            $CityId     = '';
            if($get_res){
                $CityId = $get_res->CityId;
            }
            
            $hotels_list_arr        = [];
            $hotels_list_arr_match  = [];
            $rooms_no               = 1;
            $room_request_create    = [];
            $stuba_hotels_items     = [];
            $stuba_hotel_count      = 0;
            $hotelsSunhotel         = [];
            $totalNumberSunhotel    = 0;
            
            $hotel_city             = $destination;
            $hotel_city_changed     = $destination;
            
            // if($hotel_city == 'Al Madinah Al Munawwarah' || $hotel_city == 'Al-Madinah al-Munawwarah'){
            //     $hotel_city_changed = 'Medina';
            // }
            
            // if($hotel_city == 'Mecca'){
            //     $hotel_city_changed = 'Makkah';
            // }
            
            // if($hotel_city == 'Madinah'){
            //     $hotel_city_changed = 'Medina';
            // }
            
            // if($hotel_city == 'مكة'){
            //     $hotel_city_changed = 'Makkah';
            // }
            
            // if($hotel_city == 'Taif'){
            //     $hotel_city_changed = "Ta'if";
            // }
            
            $cityMap = [
                'Al Madinah Al Munawwarah' => 'Medina',
                'Al-Madinah al-Munawwarah' => 'Medina',
                'Madinah'                  => 'Medina',
                'Mecca'                    => 'Makkah',
                'مكة'                       => 'Makkah',
                'Taif'                     => "Ta’if",
            ];
            $hotel_city_changed = $cityMap[$hotel_city] ?? $hotel_city;
            
            $rooms_adults   = $adult_per_room;
            $rooms_childs   = $child_per_room;
            $customer_Id    = $sub_customer_id->id;
            $all_Hotels     = [];
            $user_hotels    = DB::table('hotels')->where('id',$request->hotel_Id)->where('owner_id',$customer_Id)->where('property_city', $hotel_city_changed)->orderBy('hotels.created_at', 'desc')->get();
            // return $user_hotels;
            $allowd_Hotels  = DB::table('allowed_Hotels_Rooms')->where('hotel_Id',$request->hotel_Id)->where('client_Id',$customer_Id)->where('status',NULL)->orderBy('created_at', 'desc')->get();
            if($allowd_Hotels->isEmpty()){
                $all_Hotels = $user_hotels;
            }else{
                foreach($allowd_Hotels as $val_AH){
                    $allowd_hotel    = DB::table('hotels')->where('id',$val_AH->hotel_Id)->where('property_city', $hotel_city_changed)->orderBy('created_at', 'desc')->first();
                    if($allowd_hotel != null){
                        array_push($all_Hotels,$allowd_hotel);
                    }
                }
                foreach($user_hotels as $val_HD){
                    array_push($all_Hotels,$val_HD);
                }
            }
            
            $collect_Hotels = collect($all_Hotels);
            $all_hotels     = $collect_Hotels->unique('id')->values()->all();
            
            $custom_hotels      = [];
            $hotel_list_item    = [];
            
            if($request->hotel_provider == 'Custome_hotel'){
                foreach($all_hotels as $hotel_res){
                    $rooms_found            = [];
                    $rooms_ids              = [];
                    $rooms_qty              = [];
                    $counter                = 0;
                    $room_Quantity          = 0;
                    $total_adults_in_rooms  = 0;
                    $total_childs_in_rooms  = 0;
                    
                    $merger_Rooms           = [];
                    
                    if(isset($rooms_adults) && !empty($rooms_adults)){
                        foreach($rooms_adults as $index => $adult_res){
                            // return $adult_res;
                            
                            $hotel_ID   = $hotel_res->id;
                            
                            if($request->token == config('token_Alsubaee')){
                                if(isset($request->room_View) && $request->room_View != null && $request->room_View != ''){
                                    $rooms  = DB::table('rooms')
                                                ->where('availible_from','<=',$check_in)->where('availible_to','>=',$check_out)->where('room_view',$request->room_View)->whereRaw('booked < quantity')
                                                ->where('max_adults','>=',$adult_res)->where('display_on_web','true')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->get();
                                }else{
                                    $rooms  = DB::table('rooms')
                                                ->where('availible_from','<=',$check_in)->where('availible_to','>=',$check_out)->whereRaw('booked < quantity')
                                                ->where('max_adults','>=',$adult_res)->where('display_on_web','true')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->get();
                                }
                            }else{
                                if(isset($request->room_View) && $request->room_View != null && $request->room_View != ''){
                                    $rooms  = DB::table('rooms')
                                                ->where('availible_from','<=',$check_in)->where('availible_to','>=',$check_out)->where('room_view',$request->room_View)->whereRaw('booked < quantity')
                                                ->where('max_adults',$adult_res)->where('display_on_web','true')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->get();
                                }else{
                                    $rooms  = DB::table('rooms')->where('display_on_web','true')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)
                                                ->where('availible_from','<=',$check_in)->where('availible_to','>=',$check_out)
                                                // ->whereRaw('booked < quantity')
                                                // ->where('max_adults',$adult_res)
                                                ->where(function ($q) use ($adult_res) {
                                                    $q->where('max_adults', '>=', $adult_res)
                                                        ->orWhereRaw('(max_adults + extra_beds) >= ?', [$adult_res]);
                                                })
                                                ->get();
                                    // return $rooms;
                                }
                            }
                            
                            // return $rooms;
                            
                            if(count($rooms) > 0){
                                foreach($rooms as $room_res){
                                    if (!in_array($room_res->id, $rooms_ids)) {
                                        
                                        // Check Allowed
                                        $check_Allowed = DB::table('allowed_Hotels_Rooms')->where('client_Id',$customer_Id)->where('hotel_Id',$hotel_res->id)->where('room_Id',$room_res->id)->first();
                                        // return $check_Allowed->status;
                                        // Check Allowed
                                        if(isset($check_Allowed->status)){
                                            if($check_Allowed->status != 'Stop'){
                                                $rooms_ids[]             = $room_res->id;
                                                $aval_qty                = $room_res->quantity - $room_res->booked;
                                                $rooms_qty[]             = $aval_qty;
                                                $total_adults_in_rooms   += ($room_res->max_adults * $aval_qty);
                                                $total_childs_in_rooms   += ($room_res->max_child * $aval_qty);
                                                $rooms_found[]           = $room_res;
                                            }
                                        }else{
                                            $rooms_ids[]             = $room_res->id;
                                            $aval_qty                = $room_res->quantity - $room_res->booked;
                                            $rooms_qty[]             = $aval_qty;
                                            $total_adults_in_rooms   += ($room_res->max_adults * $aval_qty);
                                            $total_childs_in_rooms   += ($room_res->max_child * $aval_qty);
                                            $rooms_found[]           = $room_res;
                                        }
                                    }
                                }
                            }else{
                                if($hotel_res->owner_id == 48 || $hotel_res->owner_id == 28){
                                    $room_CheckIn   = DB::table('rooms')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->where('display_on_web','true')
                                                        ->where('availible_from', '<=', $check_in)->where('availible_to', '>=', $check_in)
                                                        ->whereRaw('booked < quantity')->where('max_adults', $adult_res)->get();
                                    if(count($room_CheckIn) > 0){
                                        foreach($room_CheckIn as $val_RCI){
                                            $val_RCI->room_From_CheckIn = '1';
                                            array_push($merger_Rooms,$val_RCI);
                                        }
                                    }
                                    
                                    $room_CheckOut  = DB::table('rooms')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->where('display_on_web','true')
                                                        ->where('availible_from', '<=', $check_out)->where('availible_to', '>=', $check_out)
                                                        ->whereRaw('booked < quantity')->where('max_adults', $adult_res)->get();
                                    if(count($room_CheckOut) > 0 && count($room_CheckIn)){
                                        foreach($room_CheckOut as $val_RCO){
                                            $val_RCO->room_From_CheckOut = '1';
                                            array_push($merger_Rooms,$val_RCO);
                                        }
                                    }
                                    
                                    if(count($room_CheckOut) > 0 && count($room_CheckIn)){}
                                    else{
                                        $merger_Rooms = [];
                                    }
                                    
                                    if(count($merger_Rooms) > 0){}
                                    else{
                                        $allRoomsOfHotel = DB::table('rooms')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->get();
                                        info($rooms_found);
                                        foreach($allRoomsOfHotel as $index => $room){
                                            $allRoomsOfHotel[$index]->rooms_on_rq           = "1";
                                            $allRoomsOfHotel[$index]->make_on_reqest_able   = true;
                                            $rooms_found[]                                  = $room;
                                        }
                                        info('after');
                                    }
                                }
                            }
                        }
                    }
                    
                    $hotel_currency = $hotel_res->currency_symbol ?? '';
                    if($hotel_res->currency_symbol == '£' || $hotel_res->currency_symbol == '﷼') {
                        $hotel_currency = 'SAR';
                    }
                    
                    $client_Id                  = (int)$hotel_res->owner_id;
                    $options_room               = [];
                    $room_prices_arr            = [];
                    $room_prices_arr_Promotion  = [];
                    $room_price_Promotion       = 0;
                    $room_Promotions_Exist      = '0';
                    if($total_adults_in_rooms >= $adult_searching && $total_childs_in_rooms >= $child_searching){
                        if($hotel_res->owner_id == 48 || $hotel_res->owner_id == 28){
                            if(count($rooms_found) > 0){
                            }else{
                                if(count($merger_Rooms) > 0){
                                    // return $merger_Rooms;
                                    
                                    $options_room_Merge     = [];
                                    foreach($merger_Rooms as $room_res){
                                        $room_Promotions    = DB::table('room_promotions')
                                                                ->where(function ($query) use ($check_in, $check_out, $room_res) {
                                                                    $query->where('customer_id', $room_res->owner_id)
                                                                    ->where('room_Id', $room_res->id)
                                                                    ->where('hotel_Id', $room_res->hotel_id)
                                                                    ->where(function ($q) use ($check_in, $check_out) {
                                                                        $q->whereBetween('promotion_Date_From', [$check_in, $check_out])
                                                                        ->orWhereBetween('promotion_Date_To', [$check_in, $check_out])
                                                                        ->orWhere(function ($q) use ($check_in, $check_out) { 
                                                                            $q->where('promotion_Date_From', '<=', $check_in)
                                                                            ->where('promotion_Date_To', '>=', $check_out);
                                                                        });
                                                                    });
                                                                })
                                                                ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                                    $query->where('customer_id', $room_res->owner_id)
                                                                    ->where('room_Id', $room_res->id)
                                                                    ->where('hotel_Id', $room_res->hotel_id)
                                                                    ->where(function ($q) use ($check_in, $check_out) {
                                                                        $q->whereBetween('promotion_Date_From_WD', [$check_in, $check_out])
                                                                        ->orWhereBetween('promotion_Date_To_WD', [$check_in, $check_out])
                                                                        ->orWhere(function ($q) use ($check_in, $check_out) {
                                                                            $q->where('promotion_Date_From_WD', '<=', $check_in)
                                                                            ->where('promotion_Date_To_WD', '>=', $check_out);
                                                                        });
                                                                    });
                                                                })
                                                                ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                                    $query->where('customer_id', $room_res->owner_id)
                                                                    ->where('room_Id', $room_res->id)
                                                                    ->where('hotel_Id', $room_res->hotel_id)
                                                                    ->where(function ($q) use ($check_in, $check_out) {
                                                                        $q->whereBetween('promotion_Date_From_WE', [$check_in, $check_out])
                                                                        ->orWhereBetween('promotion_Date_To_WE', [$check_in, $check_out])
                                                                        ->orWhere(function ($q) use ($check_in, $check_out) {
                                                                            $q->where('promotion_Date_From_WE', '<=', $check_in)
                                                                            ->where('promotion_Date_To_WE', '>=', $check_out);
                                                                        });
                                                                    });
                                                                })
                                                                ->first();
                                        $supplier_Details   = DB::table('rooms_Invoice_Supplier')->where('id',$room_res->room_supplier_name)->where('customer_id',$sub_customer_id->id)->first();
                                        
                                        if(!empty($room_Promotions) > 0){
                                            $room_price_Promotion               = self::calculateRoomsAllDaysPrice_Promotions($room_Promotions,$room_res,$check_in,$check_out);
                                            if($room_price_Promotion > 0){
                                                $room_Promotions_Exist          = '1';
                                                // $room_prices_arr_Promotion[]    = $room_price_Promotion;
                                            }
                                        }else{
                                            $room_prices_arr_Promotion[]        = 0;
                                        }
                                        
                                        $room_price                     = self::calculateRoomsAllDaysPrice_MergeRooms($room_res,$check_in,$check_out,$customer_Id);
                                        // $room_prices_arr[]              = $room_price;
                                        $room_Quantity_Details          = DB::table('rooms_types')->where('id',$room_res->room_type_cat)->first();
                                        $rooms_total_price              = $room_price;
                                        $rooms_selling_price            = $room_price;
                                        $room_Quantity                  += $room_res->quantity;
                                        $make_on_reqest_able            = false;
                                        if(isset($room_res->make_on_reqest_able)){
                                            $make_on_reqest_able        = true;
                                        }
                                        
                                        $cancellation_policy_arr = [];
                                        if (!empty($room_res->cancellation_details)) {
                                            $decoded                        = json_decode($room_res->cancellation_details, true);
                                            if ($decoded) {
                                                $cancellation_policy_arr[]  = $decoded;
                                            }
                                        }
                                        
                                        $options_room_Merge[]           = (Object)[
                                            'make_on_reqest_able'       => $make_on_reqest_able,
                                            'booking_req_id'            => $room_res->id,
                                            'allotment'                 => $room_res->quantity - $room_res->booked,
                                            'room_name'                 => $room_res->room_type_name,
                                            'room_code'                 => $room_res->room_type_cat,
                                            'request_type'              => $room_res->rooms_on_rq,
                                            'board_id'                  => $room_res->room_meal_type,
                                            'board_code'                => $room_res->room_meal_type,
                                            //'rooms_total_price'             => $room_price * $request->room,
                                            //'rooms_selling_price'           => $room_price * $request->room,
                                            'rooms_total_price'             => $rooms_total_price,
                                            'rooms_selling_price'           => $rooms_selling_price,
                                            'rooms_total_price_Promotion'   => $room_price_Promotion * $request->room,
                                            'rooms_selling_price_Promotion' => $room_price_Promotion * $request->room,
                                            'rooms_qty'                 => 1,
                                            'room_Quantity'             => $room_res->quantity ?? 0,
                                            'adults'                    => $room_res->max_adults ?? $rooms_adults[0] ?? '',
                                            'childs'                    => $room_res->max_child ?? $rooms_childs[0] ?? '',
                                            'cancliation_policy_arr'    => $cancellation_policy_arr ?? [],
                                            'rooms_list'                => $room_res,
                                            'merge_Rooms'               => $room_res,
                                            'room_supplier_code'        => $supplier_Details->room_supplier_code ?? '',
                                            'room_Promotions_Exist'     => $room_Promotions_Exist,
                                            'room_Promotions'           => $room_Promotions,
                                            'room_view'                 => $room_res->room_view ?? '',
                                        ];
                                    }
                                    
                                    if(count($options_room_Merge) > 0){
                                        // return $options_room_Merge;
                                        
                                        $room_List_All                  = [];
                                        $previousRoom                   = null;
                                        $rooms_total_price_All          = 0;
                                        $rooms_selling_price            = 0;
                                        $rooms_total_price_Promotion    = 0;
                                        $rooms_selling_price_Promotion  = 0;
                                        
                                        $mergedRooms                    = [];
                                        $usedKeys                       = [];
                                        
                                        // return $options_room_Merge;
                                        
                                        foreach ($options_room_Merge as $key => $val_RO) {
                                            if (in_array($key, $usedKeys)) {
                                                continue;
                                            }
                                            $currentRoom            = $val_RO;
                                            $currentTotalPrice      = $val_RO->rooms_total_price;
                                            $currentSellingPrice    = $val_RO->rooms_selling_price;
                                            $currentRoomsList       = [];
                                            array_push($currentRoomsList,$val_RO->merge_Rooms);
                                            $isMerged               = false;
                                        
                                            foreach ($options_room_Merge as $innerKey => $innerVal_RO) {
                                                if ($key !== $innerKey && !in_array($innerKey, $usedKeys)) {
                                                    if ($currentRoom->rooms_list->availible_to == $innerVal_RO->rooms_list->availible_from) {
                                                        $currentTotalPrice                      += $innerVal_RO->rooms_total_price;
                                                        $currentSellingPrice                    += $innerVal_RO->rooms_selling_price;
                                                        array_push($currentRoomsList, $innerVal_RO->merge_Rooms);
                                                        $currentRoom->rooms_list->availible_to  = $innerVal_RO->rooms_list->availible_to;
                                                        $usedKeys[]                             = $innerKey;
                                                        $isMerged                               = true;
                                                    }
                                                }
                                            }
                                        
                                            if ($isMerged) {
                                                $currentRoom->rooms_total_price     = $currentTotalPrice;
                                                $currentRoom->rooms_selling_price   = $currentSellingPrice;
                                                $currentRoom->merge_Rooms           = $currentRoomsList;
                                                $mergedRooms[]                      = $currentRoom;
                                            }
                                            
                                            $usedKeys[]                             = $key;
                                        }
        
                                        
                                        // return $mergedRooms;
                                        
                                        foreach($mergedRooms as $val_MR){
                                            $room_prices_arr[]                  = $val_MR->rooms_total_price;
                                            $room_prices_arr_Promotion[]        = $val_MR->rooms_total_price_Promotion;
                                            $options_room[]                     = (Object)[
                                                'make_on_reqest_able'           => $val_MR->make_on_reqest_able,
                                                'booking_req_id'                => $val_MR->booking_req_id,
                                                'allotment'                     => $val_MR->allotment,
                                                'room_name'                     => $val_MR->room_name,
                                                'room_code'                     => $val_MR->room_code,
                                                'request_type'                  => $val_MR->request_type,
                                                'board_id'                      => $val_MR->board_id,
                                                'board_code'                    => $val_MR->board_code,
                                                'rooms_total_price'             => $val_MR->rooms_total_price,
                                                'rooms_selling_price'           => $val_MR->rooms_selling_price,
                                                'rooms_total_price_Promotion'   => $val_MR->rooms_total_price_Promotion,
                                                'rooms_selling_price_Promotion' => $val_MR->rooms_selling_price_Promotion,
                                                'rooms_qty'                     => $val_MR->rooms_qty,
                                                'room_Quantity'                 => $val_MR->room_Quantity,
                                                'adults'                        => $val_MR->adults,
                                                'childs'                        => $val_MR->childs,
                                                'cancliation_policy_arr'        => $val_MR->cancliation_policy_arr,
                                                'rooms_list'                    => $val_MR->rooms_list,
                                                'merge_Rooms'                   => $val_MR->merge_Rooms,
                                                'room_supplier_code'            => $val_MR->room_supplier_code,
                                                'room_Promotions_Exist'         => $val_MR->room_Promotions_Exist,
                                                'room_Promotions'               => $val_MR->room_Promotions,
                                                'room_view'                     => $val_MR->room_view,
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                        
                        foreach($rooms_found as $room_res){
                            $room_Promotions    = DB::table('room_promotions')
                                                    ->where(function ($query) use ($check_in, $check_out, $room_res) {
                                                        $query->where('customer_id', $room_res->owner_id)
                                                        ->where('room_Id', $room_res->id)
                                                        ->where('hotel_Id', $room_res->hotel_id)
                                                        ->where(function ($q) use ($check_in, $check_out) {
                                                            $q->whereBetween('promotion_Date_From', [$check_in, $check_out])
                                                            ->orWhereBetween('promotion_Date_To', [$check_in, $check_out])
                                                            ->orWhere(function ($q) use ($check_in, $check_out) { 
                                                                $q->where('promotion_Date_From', '<=', $check_in)
                                                                ->where('promotion_Date_To', '>=', $check_out);
                                                            });
                                                        });
                                                    })
                                                    ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                        $query->where('customer_id', $room_res->owner_id)
                                                        ->where('room_Id', $room_res->id)
                                                        ->where('hotel_Id', $room_res->hotel_id)
                                                        ->where(function ($q) use ($check_in, $check_out) {
                                                            $q->whereBetween('promotion_Date_From_WD', [$check_in, $check_out])
                                                            ->orWhereBetween('promotion_Date_To_WD', [$check_in, $check_out])
                                                            ->orWhere(function ($q) use ($check_in, $check_out) {
                                                                $q->where('promotion_Date_From_WD', '<=', $check_in)
                                                                ->where('promotion_Date_To_WD', '>=', $check_out);
                                                            });
                                                        });
                                                    })
                                                    ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                        $query->where('customer_id', $room_res->owner_id)
                                                        ->where('room_Id', $room_res->id)
                                                        ->where('hotel_Id', $room_res->hotel_id)
                                                        ->where(function ($q) use ($check_in, $check_out) {
                                                            $q->whereBetween('promotion_Date_From_WE', [$check_in, $check_out])
                                                            ->orWhereBetween('promotion_Date_To_WE', [$check_in, $check_out])
                                                            ->orWhere(function ($q) use ($check_in, $check_out) {
                                                                $q->where('promotion_Date_From_WE', '<=', $check_in)
                                                                ->where('promotion_Date_To_WE', '>=', $check_out);
                                                            });
                                                        });
                                                    })
                                                    ->first();
                            $supplier_Details   = DB::table('rooms_Invoice_Supplier')->where('id',$room_res->room_supplier_name)->where('customer_id',$sub_customer_id->id)->first();
                            
                            if(!empty($room_Promotions) > 0){
                                $room_price_Promotion               = self::calculateRoomsAllDaysPrice_Promotions($room_Promotions,$room_res,$check_in,$check_out);
                                if($room_price_Promotion > 0){
                                    $room_Promotions_Exist          = '1';
                                    $room_prices_arr_Promotion[]    = $room_price_Promotion;
                                }
                            }else{
                                $room_prices_arr_Promotion[]        = 0;
                            }
                            
                            $room_price                     = self::calculateRoomsAllDaysPrice($room_res,$check_in,$check_out,$customer_Id);
                            $room_prices_arr[]              = $room_price;
                            if($request->token == config('token_Alsubaee')){
                                $room_Quantity_Details      = DB::table('rooms_types')->where('id',$room_res->room_type_cat)->first();
                                $rooms_total_price          = $room_price;
                                $rooms_selling_price        = $room_price;
                            }else{
                                $rooms_total_price          = $room_price * $request->room;
                                $rooms_selling_price        = $room_price * $request->room;
                            }
                            
                            $room_Quantity                  += $room_res->quantity;
                            
                            $make_on_reqest_able = false;
                            if(isset($room_res->make_on_reqest_able)){
                                $make_on_reqest_able = true;
                            }
                            
                            // Quantity Working
                            $quantityDateRange = [];
                            if($request->token == config('token_HaramaynRooms')){
                                $roomId     = (int) $room_res->id; 
                                $checkIn    = $request->check_in;
                                $checkOut   = $request->check_out;
                                
                                // make date range
                                $RequestDateRange   = collect();
                                $start              = \Carbon\Carbon::parse($checkIn);
                                $end                = \Carbon\Carbon::parse($checkOut);
                                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                                    $RequestDateRange->push($date->format('Y-m-d'));
                                }
                                
                                $clientID       = DB::table('customer_subcriptions')->where('Auth_key', $request->token)->first();
                                $checkBookings  = DB::table('hotels_bookings')->where('customer_id', $clientID->id)->where('provider', 'Custome_hotel')
                                                    ->whereRaw(
                                                        "JSON_CONTAINS(reservation_response, ?, '$.hotel_details.rooms')",
                                                        [json_encode(['room_code' => $roomId])]
                                                    )->get();
                                if($checkBookings->isNotEmpty()){
                                    foreach($RequestDateRange as $dateRange){
                                        $bookedRoomsQuantity        = 0;
                                        $remainingRoomsQuantity     = $room_res->quantity;
                                        foreach($checkBookings as $CB){
                                            if(isset($CB->reservation_response) && $CB->reservation_response != null && $CB->reservation_response != '' && !empty($CB->reservation_response)){
                                                $reservation_response = json_decode($CB->reservation_response, true);
                                                if(isset($reservation_response['hotel_details']) && $reservation_response['hotel_details'] != null && $reservation_response['hotel_details'] != '' && !empty($reservation_response['hotel_details'])){
                                                    if(!is_string($reservation_response['hotel_details'])){
                                                        $hotel_details  = $reservation_response['hotel_details'];
                                                        $checkIn        = $hotel_details['checkIn'] ?? '';
                                                        $checkOut       = $hotel_details['checkOut'] ?? '';
                                                        if(Carbon::parse($dateRange)->between($checkIn, $checkOut)){
                                                            if(isset($hotel_details['rooms']) && is_array($hotel_details['rooms'])){
                                                                foreach($hotel_details['rooms'] as $val_Rooms){
                                                                    if($val_Rooms['room_rates']){
                                                                        $bookedRoomsQuantity += (int) $val_Rooms['room_rates'][0]['room_qty'];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        $dataRangeObject    = [
                                            'date'          => $dateRange,
                                            'booked'        => $bookedRoomsQuantity,
                                            'remaining'     => max(0, $remainingRoomsQuantity - $bookedRoomsQuantity),
                                        ];
                                        array_push($quantityDateRange,$dataRangeObject);
                                    }
                                }else{
                                    foreach ($RequestDateRange as $dateRange) {
                                        $quantityDateRange[] = [
                                            'date'      => $dateRange,
                                            'booked'    => 0,
                                            'remaining' => $room_res->quantity,
                                        ];
                                    }
                                }
                                
                            }
                            // Quantity Working
                            
                            $hasZeroRemaining = collect($quantityDateRange)->contains(function ($day) {
                                return $day['remaining'] <= 0;
                            });
                            
                            $cancellation_policy_arr = [];
                            if (!empty($room_res->cancellation_details)) {
                                $decoded                        = json_decode($room_res->cancellation_details, true);
                                if ($decoded) {
                                    $cancellation_policy_arr[]  = $decoded;
                                }
                            }
                            
                            if (!$hasZeroRemaining) {
                                $options_room[]                 = (Object)[
                                    'make_on_reqest_able'       => $make_on_reqest_able,
                                    'booking_req_id'            => $room_res->id,
                                    'allotment'                 => $room_res->quantity - $room_res->booked,
                                    'room_name'                 => $room_res->room_type_name,
                                    'room_code'                 => $room_res->room_type_cat,
                                    'request_type'              => $room_res->rooms_on_rq,
                                    'board_id'                  => $room_res->room_meal_type,
                                    'board_code'                => $room_res->room_meal_type,
                                    //'rooms_total_price'             => $room_price * $request->room,
                                    //'rooms_selling_price'           => $room_price * $request->room,
                                    'rooms_total_price'             => $rooms_total_price,
                                    'rooms_selling_price'           => $rooms_selling_price,
                                    'rooms_total_price_Promotion'   => $room_price_Promotion * $request->room,
                                    'rooms_selling_price_Promotion' => $room_price_Promotion * $request->room,
                                    'rooms_qty'                 => 1,
                                    'room_Quantity'             => $room_res->quantity ?? 0,
                                    'adults'                    => $room_res->max_adults ?? $rooms_adults[0] ?? '',
                                    'childs'                    => $room_res->max_child ?? $rooms_childs[0] ?? '',
                                    'cancliation_policy_arr'    => $cancellation_policy_arr ?? [],
                                    'rooms_list'                => $room_res,
                                    'room_supplier_code'        => $supplier_Details->room_supplier_code ?? '',
                                    'room_Promotions_Exist'     => $room_Promotions_Exist,
                                    'room_Promotions'           => $room_Promotions,
                                    'room_view'                 => $room_res->room_view ?? '',
                                    'quantityDateRange'         => $quantityDateRange,
                                ];
                            }
                        }
                        
                        if(isset($room_prices_arr_Promotion[0]) && $room_prices_arr_Promotion[0] > 0){
                            $min_price = 0;
                            if(!empty($room_prices_arr_Promotion)){
                                $min_price = min($room_prices_arr_Promotion);
                            }
                            
                            $max_price = 0;
                            if(!empty($room_prices_arr_Promotion)){
                                $max_price = max($room_prices_arr_Promotion);
                            }
                            
                            $min_price_Actual = 0;
                            if(!empty($room_prices_arr)){
                                $min_price_Actual = min($room_prices_arr);
                            }
                            
                            $max_price_Actual = 0;
                            if(!empty($room_prices_arr)){
                                $max_price_Actual = max($room_prices_arr);
                            }
                        }else{
                            $min_price_Actual = 0;
                            $max_price_Actual = 0;
                            
                            $min_price = 0;
                            if(!empty($room_prices_arr)){
                                $min_price = min($room_prices_arr);
                            }
                            
                            $max_price = 0;
                            if(!empty($room_prices_arr)){
                                $max_price = max($room_prices_arr);
                            }
                        }
                        
                        $hotel_list_item            = (Object)[
                            'hotel_provider'        => 'Custome_hotel',
                            'admin_markup'          => $admin_custom_markup,
                            'customer_markup'       => $customer_custom_hotel_markup,
                            'admin_markup_type'     => 'Percentage',
                            'customer_markup_type'  => $customer_custom_hotel_markup_type,
                            'hotel_id'              => $hotel_res->id,
                            'hotel_name'            => $hotel_res->property_name,
                            'room_Quantity'         => $room_Quantity ?? 0,
                            'stars_rating'          => (float)$hotel_res->star_type ?? $stars_rating ?? 1,
                            'hotel_curreny'         => $hotel_currency,
                            'min_price_Actual'      => $min_price_Actual * $request->room,
                            'max_price_Actual'      => $max_price_Actual * $request->room,
                            'min_price'             => $min_price * $request->room,
                            'max_price'             => $max_price * $request->room,
                            'rooms_options'         => $options_room,
                            'latitude_Hotel'        => $hotel_res->latitude,
                            'longitude_Hotel'       => $hotel_res->longitude,
                            'client_Id'             => $client_Id,
                            'check_in'              => $check_in,
                            'check_out'             => $check_out,
                        ];
                        
                        // Sale Stop
                        $sale_Stop                          = 'NO';
                        if($customer_Id == '28'){
                            if($max_price > 0){
                                // Check Hotel
                                $check_HSL                  = DB::table('hotels')->where('id',$hotel_res->id)->where('stop_Sale_Status','Stop')->first();
                                if(!empty($check_HSL)){
                                    $sale_Stop              = 'YES';
                                }
                                
                                // Check Rooms
                                $rooms_options              = $hotel_list_item->rooms_options;
                                foreach($hotel_list_item->rooms_options as $key => $val_RD){
                                    $check_RSL              = DB::table('rooms')->where('id',$val_RD->booking_req_id)->where('stop_Sale_Status','Stop')->first();
                                    if(!empty($check_RSL)){
                                        unset($hotel_list_item->rooms_options[$key]);
                                        $sale_Stop          = 'YES';
                                    }
                                    
                                    // Check Date Wise Rooms
                                    $check_DW_SL            = DB::table('stop_Sale_Date_Wise')->where('room_id',$val_RD->booking_req_id)->where('status','Stop')->first();
                                    if(!empty($check_DW_SL)){
                                        if($check_in >= $check_DW_SL->available_from && $check_in <= $check_DW_SL->available_to){
                                            $sale_Stop      = 'YES';
                                        }else{
                                            if($check_out >= $check_DW_SL->available_from && $check_out <= $check_DW_SL->available_to){
                                                $sale_Stop  = 'YES';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        // Sale Stop
                        
                        if($sale_Stop == 'NO'){
                            if(count($options_room) > 0){
                                $hotel_first_char = substr($hotel_res->property_name, 0, 10);
                                if($hotel_first_char == $destination_first_char){
                                    if(isset($hotel_list_item->hotel_provider) && $max_price > 0){
                                        array_push($hotels_list_arr_match,$hotel_list_item);
                                    }
                                }else{
                                    if(isset($hotel_list_item->hotel_provider) && $max_price > 0){
                                        array_push($hotels_list_arr,$hotel_list_item);
                                    }
                                }
                            }
                        }
                    }
                }
                
                info('all hotels list');
                info($hotels_list_arr);
            }
            
            if($request->hotel_provider == 'custom_hotel_provider'){
                $all_hotel_providers = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('provider_id','!=','NULL')->where('provider_id','!=','')->get();
                if(isset($all_hotel_providers)){
                    foreach($all_hotel_providers as $hotel_provider_res){
                        $provider_markup_data           = DB::table('become_provider_markup')->where('customer_id',$hotel_provider_res->provider_id)->where('status','1')->first();
                        $all_hotels                     = DB::table('hotels')->where('id',$request->hotel_Id)->where('owner_id',$hotel_provider_res->provider_id)->get();
                        
                        $admin_custom_hotel_pro_markup  = 0;
                        $customer_markup                = 0;
                        foreach($markup as $data){
                            if($data->added_markup == 'synchtravel'){
                                if($data->provider == $hotel_provider_res->provider){
                                    $admin_custom_hotel_pro_markup = $data->markup_value;
                                }
                                
                                if($data->provider == 'All'){
                                    $admin_custom_hotel_pro_markup =$data->markup_value;
                                }   
                            }
                            if($data->added_markup == 'Haramayn_hotels'){
                                $customer_markup =  $data->markup_value;
                            }
                        }
                        
                        $custom_hotels      = [];
                        $hotel_list_item    = [];
                        
                        foreach($all_hotels as $hotel_res){
                            $rooms_found            = [];
                            $rooms_ids              = [];
                            $rooms_qty              = [];
                            $counter                = 0;
                            $total_adults_in_rooms  = 0;
                            $total_childs_in_rooms  = 0;
                            
                            foreach($rooms_adults as $index => $adult_res){
                                $rooms = DB::table('rooms')
                                            ->where('availible_from','<=',$check_in)
                                            ->where('availible_to','>=',$check_out)
                                            ->whereRaw('booked < quantity')
                                            ->where('max_adults',$adult_res)
                                            ->where('display_on_web','true')
                                            ->where('hotel_id',$hotel_res->id)
                                            ->where('owner_id',$hotel_provider_res->provider_id)
                                            // ->where('rooms_on_rq','0')
                                            ->get();
                                            
                                if(count($rooms) > 0){
                                    // $any_room_found = false;
                                    foreach($rooms as $room_res){
                                        if (!in_array($room_res->id, $rooms_ids)) {
                                            $rooms_ids[]             = $room_res->id;
                                            $aval_qty                = $room_res->quantity - $room_res->booked;
                                            $rooms_qty[]             = $aval_qty;
                                            $total_adults_in_rooms   += ($room_res->max_adults * $aval_qty);
                                            $total_childs_in_rooms   += ($room_res->max_child * $aval_qty);
                                            $rooms_found[]           = $room_res;
                                        }
                                    }
                                }
                            }
                            
                            // $hotel_currency     = '';
                            $hotel_currency = $hotel_res->currency_symbol ?? '';
                            if($hotel_res->currency_symbol == '£' || $hotel_res->currency_symbol == '﷼')  {
                                $hotel_currency = 'SAR';
                            }
                            
                            $options_room       = [];
                            $room_prices_arr    = [];
                            
                            if($total_adults_in_rooms >= $adult_searching && $total_childs_in_rooms >= $child_searching){
                                foreach($rooms_found as $room_res){
                                    
                                    $room_price                 = self::calculateRoomsAllDaysPrice($room_res,$check_in,$check_out,$sub_customer_id->id);
                                    $provider_markup            = 0;
                                    if(isset($provider_markup_data)){
                                        if($provider_markup_data->markup == 'Percentage'){
                                            $provider_markup    = ($room_price ?? '0' * $provider_markup_data->markup_value) / 100;
                                        }else{
                                            $provider_markup    = $provider_markup_data->markup_value;
                                        }
                                    }
                                    
                                    $room_price                     = $room_price + $provider_markup;
                                    $room_prices_arr[]              = $room_price;
                                    $options_room[]                 = (Object)[
                                        'booking_req_id'            => $room_res->id,
                                        'allotment'                 => $room_res->quantity - $room_res->booked,
                                        'room_name'                 => $room_res->room_type_name,
                                        'room_code'                 => $room_res->room_type_cat,
                                        'request_type'              => $room_res->rooms_on_rq,
                                        'board_id'                  => $room_res->room_meal_type,
                                        'board_code'                => $room_res->room_meal_type,
                                        'rooms_total_price'         => round($room_price,2),
                                        'rooms_selling_price'       => round($room_price,2),
                                        'rooms_qty'                 => 1,
                                        'adults'                    => $room_res->max_adults,
                                        'childs'                    => $room_res->max_child,
                                        'cancliation_policy_arr'    => [],
                                        'rooms_list'                => []
                                    ];
                                }
                                
                                $min_price = 0;
                                if(!empty($room_prices_arr)){
                                    $min_price = min($room_prices_arr);
                                    $min_price = round($min_price,2);
                                }
                                
                                $max_price = 0;
                                if(!empty($room_prices_arr)){
                                    $max_price = max($room_prices_arr);
                                    $max_price = round($max_price,2);
                                }
                                
                                $hotel_list_item = (Object)[
                                    'hotel_provider'        => 'custom_hotel_provider',
                                    'custom_hotel_provider' => $hotel_provider_res->provider,
                                    'admin_markup_type'     => 'Percentage',
                                    'admin_markup'          => $admin_hotel_bed_markup ?? $admin_custom_hotel_pro_markup ?? '',
                                    'customer_markup_type'  => 'Percentage',
                                    'customer_markup'       => $customer_markup ?? '',
                                    'hotel_id'              => $hotel_res->id,
                                    'hotel_name'            => $hotel_res->property_name,
                                    'stars_rating'          => (float)$hotel_res->star_type,
                                    'hotel_curreny'         => $hotel_currency,
                                    'min_price'             => $min_price * $request->room,
                                    'max_price'             => $max_price * $request->room,
                                    'rooms_options'         => $options_room,
                                    'latitude_Hotel'        => $hotel_res->latitude,
                                    'longitude_Hotel'       => $hotel_res->longitude,
                                ];
                                
                                $hotel_first_char = substr($hotel_res->property_name, 0, 10);
                                if($hotel_first_char == $destination_first_char){
                                    if(isset($hotel_list_item->hotel_provider) && $max_price > 0){
                                        array_push($hotels_list_arr_match,$hotel_list_item);
                                    }
                                }else{
                                    if(isset($hotel_list_item->hotel_provider) && $max_price > 0){
                                        array_push($hotels_list_arr,$hotel_list_item);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            if($request->hotel_provider == 'travelenda'){
                $country                    = $request->slc_nationality;
                $countryList                = DB::table('tboHoliday_Country_List')->whereRaw('LOWER(Name) = ?', [strtolower($country)])->first();
                $country_nationality        = $countryList->Code;
                
                $rooms_no                   = 1;
                $room_request_create        = [];
                foreach($request->rooms_counter as $index => $room_counter){
                    
                    $child_age          = [];
                    $childern           = $request->children[$index];
                    $child_age_index    = 'child_ages'.$room_counter;
                    $child_ages         = $request->$child_age_index;
                    for($i = 0; $i<$childern; $i++){
                        array_push($child_age,(string)$child_ages[$i]);
                    }
                    
                    $single_room = (object)[
                        'Room'          => $rooms_no++,
                        'Adults'        => $request->Adults[$index],
                        'Children'      => $request->children[$index],
                        'ChildrenAge'   => $child_age
                    ];
                    
                    array_push($room_request_create,$single_room);
                }
                
                // return $room_request_create;
                
                function travellandasearch($CityId, $CheckInDate, $CheckOutDate, $res_data, $country_nationality){
                    $url    = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                    $data   = array('case' => 'travelandaSearch', 'CityId' => $CityId, 'CheckInDate' => $CheckInDate, 'CheckOutDate' => $CheckOutDate, 'res_data' => json_encode($res_data), 'country_nationality' =>$country_nationality);
                    
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
                
                $responseData3              = travellandasearch($CityId, $check_in, $check_out, $room_request_create, $country_nationality);
                // return $responseData3;
                $result_travellanda         = json_decode($responseData3);
                
                $travellanda_obj            = [];
                $travelenda_hotels_count    = 0;
                $travelenda_curreny         = '';
                if(isset($result_travellanda->Body->HotelsReturned)){
                    $travelenda_curreny                 = $result_travellanda->Body->Currency;
                    if($result_travellanda->Body->HotelsReturned > 1){
                        if(isset($result_travellanda->Body->Hotels->Hotel)){
                            $travellanda_obj            = $result_travellanda->Body->Hotels->Hotel;
                            $travelenda_hotels_count    = $result_travellanda->Body->HotelsReturned ?? '0';
                        }
                    }
                }
                // return $travellanda_obj;
                foreach($travellanda_obj as $travel_res){
                    $rooms_list             = [];
                    $prices_arr             = [];
                    $option_list            = [];
                    
                    // return $travellanda_obj;
                    
                    if(isset($travel_res->Options)){
                        $Options            = $travel_res->Options;
                        if(isset($Options->Option) && is_array($Options->Option)){
                            foreach($Options->Option as $room_res){
                                // return $room_res;
                                
                                $options_room   = [];
                                $rooms_qty      = 0;
                                $total_adults   = 0;
                                $total_childs   = 0;
                                $room_name      = '';
                                
                                if(isset($room_res->Rooms->Room)){
                                    if (is_array($room_res->Rooms->Room)) {
                                        // return 'IF';
                                        
                                        $rooms_qty              = count($room_res->Rooms->Room);
                                        $room_name              = $room_res->Rooms->Room[0]->RoomName;
                                        foreach($room_res->Rooms->Room as $room_list_res){
                                            $daily_prices       = [];
                                            if(isset($room_list_res->DailyPrices)){
                                              $daily_prices     = $room_list_res->DailyPrices->DailyPrice;
                                            }
                                           
                                            $total_adults       += $room_list_res->NumAdults;
                                            $total_childs       += $room_list_res->NumChildren;
                                            
                                            $options_room[]     = (Object)[
                                                'room_id'       => $room_list_res->RoomId,
                                                'room_name'     => $room_list_res->RoomName,
                                                'adults'        => $room_list_res->NumAdults,
                                                'childs'        => $room_list_res->NumChildren,
                                                'room_price'    => $room_list_res->RoomPrice,
                                                'daily_prices'  => $daily_prices,
                                            ];
                                        }
                                    } else {
                                        // return 'ELSE';
                                        
                                        $rooms_qty = 1;
                                        $room_name = $room_res->Rooms->Room->RoomName;
                                        
                                        $daily_prices = [];
                                        if(isset($room_list_res->DailyPrices)){
                                            $daily_prices = $room_res->Rooms->Room->DailyPrices->DailyPrice;
                                        }
                                       
                                        $total_adults += $room_res->Rooms->Room->NumAdults;
                                        $total_childs += $room_res->Rooms->Room->NumChildren;
                                       
                                        $options_room[] = (Object)[
                                            'room_id'       => $room_res->Rooms->Room->RoomId,
                                            'room_name'     => $room_res->Rooms->Room->RoomName,
                                            'adults'        => $room_res->Rooms->Room->NumAdults,
                                            'childs'        => $room_res->Rooms->Room->NumChildren,
                                            'room_price'    => $room_res->Rooms->Room->RoomPrice,
                                            'daily_prices'  => $daily_prices,
                                        ];
                                    }
                                }
                                
                                if(isset($room_res->OptionId)){
                                    if(count($options_room) > 1){
                                        $seenPrices         = [];
                                        $options_room_NV    = array_filter($options_room, function($room) use (&$seenPrices) {
                                            if (in_array($room->room_price, $seenPrices)) {
                                                return false;
                                            }
                                            $seenPrices[]   = $room->room_price;
                                            return true;
                                        });
                                        $options_room_FV    = array_values($options_room_NV);
                                        
                                        if(count($options_room_FV) == count($options_room)){
                                            $rooms_qty = 1;
                                        }
                                        
                                        foreach($options_room_FV as $va_RO){
                                            $option_list[]                  = (Object)[
                                                'booking_req_id'            => $room_res->OptionId,
                                                'allotment'                 => $rooms_qty,
                                                'room_name'                 => $va_RO->room_name,
                                                'room_code'                 => '',
                                                'request_type'              => $room_res->OnRequest,
                                                'board_id'                  => $room_res->BoardType,
                                                'board_code'                => '',
                                                'rooms_total_price'         => $va_RO->room_price * $rooms_qty,
                                                'rooms_selling_price'       => $va_RO->room_price * $rooms_qty,
                                                'rooms_qty'                 => $rooms_qty,
                                                'adults'                    => (float)$va_RO->adults,
                                                'childs'                    => (float)$va_RO->childs,
                                                'cancliation_policy_arr'    => [],
                                                'rooms_list'                => $va_RO
                                            ];
                                            $prices_arr[]                   = $va_RO->room_price;
                                        }
                                    }else{
                                        $option_list[]                  = (Object)[
                                            'booking_req_id'            => $room_res->OptionId,
                                            'allotment'                 => $rooms_qty,
                                            'room_name'                 => $room_name,
                                            'room_code'                 => '',
                                            'request_type'              => $room_res->OnRequest,
                                            'board_id'                  => $room_res->BoardType,
                                            'board_code'                => '',
                                            'rooms_total_price'         => $room_res->TotalPrice * $rooms_qty,
                                            'rooms_selling_price'       => $room_res->TotalPrice * $rooms_qty,
                                            'rooms_qty'                 => $rooms_qty,
                                            'adults'                    => $total_adults,
                                            'childs'                    => $total_childs,
                                            'cancliation_policy_arr'    =>[],
                                            'rooms_list'                => $options_room
                                        ];
                                        $prices_arr[]                   = $room_res->TotalPrice;
                                    }
                                }
                            }
                        }else{
                            $options_room   = [];
                            $rooms_qty      = 0;
                            $total_adults   = 0;
                            $total_childs   = 0;
                            $room_name      = '';
                            
                            $room_res = $Options->Option;
                            
                            if(isset($room_res->Rooms->Room)){
                                if (is_array($room_res->Rooms->Room)) {
                                    // return 'IF';
                                    
                                    $rooms_qty              = count($room_res->Rooms->Room);
                                    $room_name              = $room_res->Rooms->Room[0]->RoomName;
                                    foreach($room_res->Rooms->Room as $room_list_res){
                                        $daily_prices       = [];
                                        if(isset($room_list_res->DailyPrices)){
                                          $daily_prices     = $room_list_res->DailyPrices->DailyPrice;
                                        }
                                       
                                        $total_adults       += $room_list_res->NumAdults;
                                        $total_childs       += $room_list_res->NumChildren;
                                        
                                        $options_room[]     = (Object)[
                                            'room_id'       => $room_list_res->RoomId,
                                            'room_name'     => $room_list_res->RoomName,
                                            'adults'        => $room_list_res->NumAdults,
                                            'childs'        => $room_list_res->NumChildren,
                                            'room_price'    => $room_list_res->RoomPrice,
                                            'daily_prices'  => $daily_prices,
                                        ];
                                    }
                                    
                                } else {
                                    // return 'ELSE';
                                    
                                    $rooms_qty = 1;
                                    $room_name = $room_res->Rooms->Room->RoomName;
                                    
                                    $daily_prices = [];
                                    if(isset($room_list_res->DailyPrices)){
                                      $daily_prices = $room_res->Rooms->Room->DailyPrices->DailyPrice;
                                    }
                                   
                                    $total_adults += $room_res->Rooms->Room->NumAdults;
                                    $total_childs += $room_res->Rooms->Room->NumChildren;
                                   
                                    $options_room[] = (Object)[
                                        'room_id'       => $room_res->Rooms->Room->RoomId,
                                        'room_name'     => $room_res->Rooms->Room->RoomName,
                                        'adults'        => $room_res->Rooms->Room->NumAdults,
                                        'childs'        => $room_res->Rooms->Room->NumChildren,
                                        'room_price'    => $room_res->Rooms->Room->RoomPrice,
                                        'daily_prices'  => $daily_prices,
                                    ];
                                }
                            }
                            
                            if(isset($room_res->OptionId)){
                                if(count($options_room) > 1){
                                    $seenPrices         = [];
                                    $options_room_NV    = array_filter($options_room, function($room) use (&$seenPrices) {
                                        if (in_array($room->room_price, $seenPrices)) {
                                            return false;
                                        }
                                        $seenPrices[]   = $room->room_price;
                                        return true;
                                    });
                                    $options_room_FV    = array_values($options_room_NV);
                                    
                                    if(count($options_room_FV) == count($options_room)){
                                        $rooms_qty = 1;
                                    }
                                    
                                    foreach($options_room_FV as $va_RO){
                                        $option_list[]                  = (Object)[
                                            'booking_req_id'            => $room_res->OptionId,
                                            'allotment'                 => $rooms_qty,
                                            'room_name'                 => $va_RO->room_name,
                                            'room_code'                 => '',
                                            'request_type'              => $room_res->OnRequest,
                                            'board_id'                  => $room_res->BoardType,
                                            'board_code'                => '',
                                            'rooms_total_price'         => $va_RO->room_price * $rooms_qty,
                                            'rooms_selling_price'       => $va_RO->room_price * $rooms_qty,
                                            'rooms_qty'                 => $rooms_qty,
                                            'adults'                    => (float)$va_RO->adults,
                                            'childs'                    => (float)$va_RO->childs,
                                            'cancliation_policy_arr'    => [],
                                            'rooms_list'                => $va_RO
                                        ];
                                        $prices_arr[]                   = $va_RO->room_price;
                                    }
                                }else{
                                    $option_list[]                  = (Object)[
                                        'booking_req_id'            => $room_res->OptionId,
                                        'allotment'                 => $rooms_qty,
                                        'room_name'                 => $room_name,
                                        'room_code'                 => '',
                                        'request_type'              => $room_res->OnRequest,
                                        'board_id'                  => $room_res->BoardType,
                                        'board_code'                => '',
                                        'rooms_total_price'         => $room_res->TotalPrice * $rooms_qty,
                                        'rooms_selling_price'       => $room_res->TotalPrice * $rooms_qty,
                                        'rooms_qty'                 => $rooms_qty,
                                        'adults'                    => $total_adults,
                                        'childs'                    => $total_childs,
                                        'cancliation_policy_arr'    =>[],
                                        'rooms_list'                => $options_room
                                    ];
                                    $prices_arr[]                   = $room_res->TotalPrice;
                                }
                            }
                        }
                    }
                   
                    if(!empty($prices_arr)){
                        $max_hotel_price = max($prices_arr);
                        $min_hotel_price = min($prices_arr);
                    }
                    else{
                        $min_hotel_price = 0;
                        $max_hotel_price = 0;  
                    }
                    
                    $StarRating = 1;
                    if (isset($travel_res->StarRating) && is_numeric($travel_res->StarRating)) {
                        $StarRating = round((float)$travel_res->StarRating);
                    }
                    
                    $hotel_list_item = (Object)[
                        'hotel_provider'        => 'travelenda',
                        'admin_markup'          => $admin_travelenda_markup,
                        'customer_markup'       => $customer_custom_hotel_markup ?? $customer_markup,
                        'admin_markup_type'     => 'Percentage',
                        'customer_markup_type'  => 'Percentage',
                        'hotel_id'              => $travel_res->HotelId,
                        'hotel_name'            => $travel_res->HotelName,
                        'stars_rating'          => $StarRating,
                        'hotel_curreny'         => $travelenda_curreny,
                        'min_price'             => $min_hotel_price * $request->room,
                        'max_price'             => $max_hotel_price * $request->room,
                        'rooms_options'         => $option_list,
                    ];
                    
                    $hotel_first_char = substr($travel_res->HotelName, 0, 10);
                    if($hotel_first_char == $destination_first_char){
                        array_push($hotels_list_arr_match,$hotel_list_item);
                    }else{
                        array_push($hotels_list_arr,$hotel_list_item);
                    } 
                }
            }
            
            if($request->hotel_provider == 'hotel_beds'){
                $counts                 = [];
                $rooms_no               = 1;
                $room_request_create    = [];
                $adults_counts_arr      = array_count_values($request->Adults);
                
                foreach($request->rooms_counter as $index => $room_counter){
                    $others_pax         = [];
                    $childern           = $request->children[$index];
                    $child_age_index    = 'child_ages'.$room_counter;
                    $child_ages         = $request->$child_age_index;
                    for($i = 0; $i<$childern; $i++){
                        $others_pax[]   = (Object)[
                            'type'      => 'CH',
                            'age'       => $child_ages[$i]
                        ];
                    }
                    $single_room        = (object)[
                        'rooms'         => 2,
                        'adults'        => $request->Adults[$index],
                        'children'      => $request->children[$index],
                        'paxes'         => $others_pax
                    ];
                    // return $single_room;
                    array_push($room_request_create,$single_room);
                }
                
                foreach ($room_request_create as $object) {
                    $data           = (array) $object;
                    $data['paxes']  = json_encode($data['paxes']);
                    $identifier     = json_encode($data);
                    if (!isset($counts[$identifier])) {
                        $counts[$identifier] = 1;
                    } else {
                        $counts[$identifier]++;
                    }
                }
                
                $final_convert_rooms = [];
                foreach ($counts as $identifier => $count) {
                    $rooms_data             = json_decode($identifier, true);
                    $rooms_data['rooms']    = $count;
                    $rooms_data['paxes']    = json_decode($rooms_data['paxes']);
                    $final_convert_rooms[]  = (object)$rooms_data;
                }
                
                if($request->lat == NULL && $request->long == NULL && $request->cityd == 'Jerusalem'){
                    $lat    = '31.7683';
                    $long   = '35.2137';
                }
                else{
                    $lat    = $request->lat;
                    $long   = $request->long;  
                }
                
                $room_request_create    = $final_convert_rooms;
                $newstart               = $check_in;
                $newend                 = $check_out;
                
                function hotelbedssearch($newstart, $newend, $res_hotel_beds, $lat, $long){
                    $url        = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi_live.php";
                    $data       = array('case' => 'serach_hotelbeds', 'CheckIn' => $newstart, 'CheckOut' => $newend, 'res_hotel_beds' => json_encode($res_hotel_beds), 'lat' =>$lat,'long' =>$long);
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
                
                $responseData3      = hotelbedssearch($check_in, $check_out, $room_request_create, $lat, $long);
                // return $responseData3;
                $result_hotelbeds   = json_decode($responseData3);
                // return $result_hotelbeds;
                $hotel_bed_counts   = 0;
                
                if(isset($result_hotelbeds->hotels->total)){
                    $hotel_bed_counts = $result_hotelbeds->hotels->total;
                }
                
                if(isset($result_hotelbeds->hotels->hotels)){
                    foreach($result_hotelbeds->hotels->hotels as $hotel_res){
                        if(isset($hotel_res->code) && $hotel_res->code == $request->hotel_Id){
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
                                                        $cancel_tiem    = (Object)[
                                                            'type'      => $cancel_res->type ?? 'Fix Amount',
                                                            'amount'    => $cancel_res->amount,
                                                            'from_date' => $cancel_res->from,
                                                        ];
                                                        $cancliation_policy_arr[] = $cancel_tiem;
                                                    }
                                                }
                                                
                                                $options_room[] = (Object)[
                                                    'booking_req_id'            => $room_list_res->rateKey,
                                                    'allotment'                 => $room_list_res->allotment,
                                                    'room_name'                 => $room_res->name,
                                                    'room_code'                 => $room_res->code,
                                                    'request_type'              => '',
                                                    'board_id'                  => $room_list_res->boardName,
                                                    'board_code'                => $room_list_res->boardCode,
                                                    'rooms_total_price'         => $room_list_res->net,
                                                    'rooms_selling_price'       => $selling_rate,
                                                    'rooms_qty'                 => $room_list_res->rooms,
                                                    'adults'                    => $room_list_res->adults,
                                                    'childs'                    => $room_list_res->children,
                                                    'cancliation_policy_arr'    => $cancliation_policy_arr,
                                                    'rooms_list'                => []
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                            
                            $hotel_list_item = (Object)[
                                'hotel_provider'        => 'hotel_beds',
                                'admin_markup_type'     => 'Percentage',
                                'admin_markup'          => $admin_hotel_bed_markup,
                                'customer_markup_type'  => 'Percentage',
                                'customer_markup'       => $customer_custom_hotel_markup ?? $customer_markup,
                                'hotel_id'              => $hotel_res->code,
                                'hotel_name'            => $hotel_res->name,
                                'stars_rating'          => (float)$hotel_res->categoryCode ?? 1,
                                'hotel_curreny'         => $hotel_res->currency,
                                'min_price'             => $hotel_res->minRate,
                                'max_price'             => $hotel_res->maxRate,
                                'rooms_options'         => $options_room,
                                'latitude_Hotel'        => $hotel_res->latitude,
                                'longitude_Hotel'       => $hotel_res->longitude,
                            ];
                            
                            $hotel_first_char = substr($hotel_res->name, 0, 10);
                            if($hotel_first_char == $destination_first_char){
                                array_push($hotels_list_arr_match,$hotel_list_item);
                            }else{
                                array_push($hotels_list_arr,$hotel_list_item);
                            }
                        }
                    }
                }
            }
            
            if($request->hotel_provider == 'Stuba'){
                if (!is_array($request->rooms_counter)) {
                    $request->rooms_counter = json_decode($request->rooms_counter);
                }
                
                foreach ($request->rooms_counter as $index => $room_counter) {
                    $child_age = [];
                    
                    if (!is_array($request->children)) {
                        $request->children = json_decode($request->children);
                    }
                    
                    $childern = $request->children[$index];
                    $child_age_index = 'child_ages' . $room_counter;
                    
                    for ($i = 0; $i < $childern; $i++) {
                        array_push($child_age, $child_age_index);
                    }
                    
                    $single_room = (object)[
                        'Room' => $rooms_no++,
                        'Adults' => $request->Adults[$index],
                        'Children' => $request->children[$index],
                        'ChildrenAge' => $child_age,
                    ];
                    array_push($room_request_create, $single_room);
                }
                
                $roomsData                      = $room_request_create;
                $city                           = $destination_first_char;
                $arivaldate                     = $check_in;
                $nights                         = self::dateDiffInDays($check_in, $check_out);
                $country                        = $request->slc_nationality;
                $countryList                    = DB::table('tboHoliday_Country_List')->whereRaw('LOWER(Name) = ?', [strtolower($country)])->first();
                $country_code                   = $countryList->Code;
                $hotels                         = DB::table('stuba_cityids')->whereRaw('LOWER(Name) = ?', [strtolower($city)])->first();
                if(isset($hotels)) {
                    $id                         = $hotels->cityid;
                    $hotelsresults              = [];
                    $hoteldetails               = DB::table('stuba_hotel_details')->where('hotelid',$request->hotel_Id)->get();
                    // $hoteldetails               = DB::table('stuba_hotel_details')->WhereJsonContains('hotelRegion', [['CityID' => (string)$id]])->get();
                    foreach ($hoteldetails as $details) {
                        $region                 = json_decode($details->hotelRegion);
                        $cityid                 = $region[0]->CityID ?? '';
                        if ($cityid == (string)$id) {
                            $hotelsresults[]    = $details;
                        }
                    }
                    
                    foreach ($hotelsresults as $hotel) {
                        $hotelscodes[]          = $hotel->hotelid;
                    }
                }
                $customer_Hotel_markup_Stuba = $customer_custom_hotel_markup ?? $customer_markup ?? 0;
                if(isset($hotelscodes)){
                    // $stuba_response = Stuba_Controller::stubaSearch($request,$hotelscodes,$arivaldate,$nights,$country_code,$roomsData,$customer_Hotel_markup_Stuba);
                    // return $stuba_response;
                }
                
                if(isset($stuba_response['stuba_hotels_items'])){
                    $stuba_hotels_items = $stuba_response['stuba_hotels_items'];
                }else{
                    $stuba_hotels_items = [];
                }
                
                if(isset($stuba_response['stuba_hotel_count'])){
                    $stuba_hotel_count  = $stuba_response['stuba_hotel_count'];
                }else{
                    $stuba_hotel_count  = 0;
                }
            }
            
            if($request->hotel_provider == 'Sunhotel'){
                $sunhotelAdminMarkupType    = 'Percentage';
                $sunhotelCustomerMarkupType = 'Percentage';
                $destinationCode            = '';
                $sunhotel_destination       = DB::table('webbed_destination_codes')->where('DestinationName', $request->destination_name)->first();
                
                if ($sunhotel_destination) {
                    $destinationCode = $sunhotel_destination->DestinationCode
                        ?? $sunhotel_destination->DestinationCode_2
                        ?? $sunhotel_destination->DestinationCode_3
                        ?? $sunhotel_destination->DestinationCode_4;
                }
                
                $child_Age_Array = [];
                if(count($request->children) > 0){
                    for($ca=0; $ca<count($request->children); $ca++){
                        $new_ca     = $ca + 1;
                        $make_Name  = 'child_ages'.$new_ca;
                        $child_ages = $request->$make_Name;
                        array_push($child_Age_Array,$child_ages);
                    }
                }
                $customer_Hotel_markup_SunHotel = $customer_custom_hotel_markup ?? $customer_markup ?? 0;
                if($destinationCode != ''){
                    $responseData3          = SunHotel_Controller::sunHotelCustomSearch($request->hotel_Id,$child_per_room,$CheckInDate,$CheckOutDate,$numberOfRooms,$destinationCode,$numberOfAdults,$request->room,$numberOfChildrenWithInfants,$request->Adults,$request->rooms_counter,$child_Age_Array,$request->children,$sunhotelAdminMarkup,$sunhotelCustomerMarkup,$customer_Hotel_markup_SunHotel);
                    // return $responseData3;
                    
                    if(isset($responseData3['hotelsSunhotel'])){
                        $hotelsSunhotel     = $responseData3['hotelsSunhotel'];
                    }
                    
                    if(isset($responseData3['totalNumberSunhotel'])){
                        $totalNumberSunhotel = $responseData3['totalNumberSunhotel'];
                    }
                }
            }
            
            $final_hotel_Array  = array_merge($hotels_list_arr_match,$hotels_list_arr,$stuba_hotels_items,$hotelsSunhotel);
            
            return response()->json([
                'status'        => 'success',
                'hotels_list'   => $final_hotel_Array,
            ]);
            
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
    
    public static function customer_Hotel_Detail($request,$decode_SD){
        try {
            $hotel_rooms_data                   = $decode_SD[0] ?? '';
            $sub_customer_id                    = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
            $markup                             = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)->orderBy('id','DESC')->get();
            $admin_travelenda_markup            = 0;
            $admin_hotel_bed_markup             = 0;
            $admin_custom_markup                = 0;
            $customer_markup                    = 0;
            $customer_custom_hotel_markup       = 0;
            $customer_custom_hotel_markup_type  = '';
            
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
                       
                        $admin_travelenda_markup = $data->markup_value;  
                    }
                    
                    if($data->provider == 'Hotelbeds'){
                        $admin_hotel_bed_markup = $data->markup_value;
                    }
                    
                    if($data->provider == 'All'){
                        $admin_travelenda_markup    = $data->markup_value;
                        $admin_hotel_bed_markup     = $data->markup_value;
                        $admin_custom_markup        = $data->markup_value;
                    }   
                }
                
                if($data->added_markup == 'Haramayn_hotels'){
                    $customer_markup =  $data->markup_value;
                }
            
            }
            
            $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id',$sub_customer_id->id)->where('status',1)->first();
            if($custom_hotel_markup){
                $customer_custom_hotel_markup       = $custom_hotel_markup->markup_value;
                $customer_custom_hotel_markup_type  = $custom_hotel_markup->markup;
            }
            
            // B2B Markup
            $markup_Find    = false;
            $user_Token     = $request->token;
            $b2b_Agent_Id   = $request->b2b_agent_id ?? '';
            $b2b_Markup     = DB::table('b2b_Agent_Markups')
                                ->where(function ($query) use ($user_Token, $b2b_Agent_Id) {
                                    $query->where('token', $user_Token)
                                    ->where('agent_Id', $b2b_Agent_Id)
                                    ->where('markup_status',1);
                                })
                                ->orWhere(function ($query) use ($user_Token, $b2b_Agent_Id) {
                                    $query->where('token', $user_Token)
                                    ->where('agent_Id', 0)
                                    ->where('markup_status',1);
                                })
                                ->get();
            if(!empty($b2b_Markup) && $b2b_Markup != null){
                foreach($b2b_Markup as $val_BAM){
                    if($val_BAM->agent_Id == $b2b_Agent_Id){
                        $customer_custom_hotel_markup_type  = $val_BAM->markup_Type; 
                        $customer_markup                    = $val_BAM->markup_Value;
                        $customer_custom_hotel_markup       = $val_BAM->markup_Value;
                        $markup_Find                        = true;
                    }
                }
                
                if($markup_Find != true){
                    foreach($b2b_Markup as $val_BAM){
                        if($val_BAM->agent_Id == 0){
                            $customer_custom_hotel_markup_type  = $val_BAM->markup_Type; 
                            $customer_markup                    = $val_BAM->markup_Value;
                            $customer_custom_hotel_markup       = $val_BAM->markup_Value;
                        }
                    }
                }
            }
            // B2B Markup
            
            if($hotel_rooms_data->hotel_provider == 'Custome_hotel'){
                $hotel_data     = DB::table('hotels')->where('id',$hotel_rooms_data->hotel_id)->first();
                $customer_data  = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
                
                // Images
                $hotel_images_gallery = [];
                if(isset($hotel_data->room_gallery)){
                    $gallery_images = json_decode($hotel_data->room_gallery);
                    foreach($gallery_images as $hotel_img){
                        if(isset($hotel_data->image_Url_Other_Dashboard) && $hotel_data->image_Url_Other_Dashboard != null && $hotel_data->image_Url_Other_Dashboard != ''){
                            $image_address          = $hotel_data->image_Url_Other_Dashboard;
                        }else{
                            $image_address          = $customer_data->dashboard_Address ?? $customer_data->webiste_Address;
                        }
                        $hotel_images_gallery[]     = $image_address.'/public/uploads/more_room_images/'.$hotel_img.'';
                        // $hotel_images_gallery[] = 'https://system.alhijaztours.net/public/uploads/more_room_images/'.$hotel_img.'';
                    }
                }
              
                // Hotel Rooms
                if(isset($hotel_rooms_data->rooms_options)){
                    $gallery_images = [];
                    if(isset($hotel_data->room_gallery)){
                        $gallery_images = json_decode($hotel_data->room_gallery);
                    }
                    foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                        $image_arr      = [];
                        if(isset($hotel_data->image_Url_Other_Dashboard) && $hotel_data->image_Url_Other_Dashboard != null && $hotel_data->image_Url_Other_Dashboard != ''){
                            $image_address          = $hotel_data->image_Url_Other_Dashboard;
                        }else{
                            $image_address          = $customer_data->dashboard_Address ?? $customer_data->webiste_Address;
                        }
                        if(isset($gallery_images[$index])){
                            $room_img = $image_address.'/public/uploads/more_room_images/'.$gallery_images[$index].'';
                            // $room_img = 'https://system.alhijaztours.net/public/uploads/more_room_images/'.$gallery_images[$index].'';
                        }else{
                            $room_img = $image_address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'';
                            // $room_img = 'https://system.alhijaztours.net/public/uploads/package_imgs/'.$hotel_data->property_img.'';
                        }
                        
                        $image_arr[]                                                = $room_img;
                        $hotel_rooms_data->rooms_options[$index]->rooms_images      = $image_arr; 
                        $hotel_rooms_data->rooms_options[$index]->rooms_facilities  = unserialize($hotel_data->facilities);
                    }
                }
                
                $newRoomOptions             = $hotel_rooms_data->rooms_options;
                if($sub_customer_id->id == '59' || $sub_customer_id->id == '60'){
                    $room_options           = [];
                    if($hotel_rooms_data->rooms_options){
                        foreach($hotel_rooms_data->rooms_options as $valRO){
                            $valRL                                      = $valRO->rooms_list;
                            if($valRL->extra_beds > 0){
                                for($RL=0; $RL<=$valRL->extra_beds; $RL++){
                                    $room_name                          = $valRO->room_name;
                                    $adults                             = $valRO->adults;
                                    $childs                             = $valRO->childs;
                                    $rooms_total_price                  = $valRO->rooms_total_price;
                                    $rooms_selling_price                = $valRO->rooms_selling_price;
                                    
                                    if($RL > 0){
                                        $adults                         = $valRO->adults + $RL;
                                        $rooms_total_price              = $valRO->rooms_total_price + ($RL * $valRL->extra_beds_charges);
                                        $rooms_selling_price            = $valRO->rooms_selling_price + ($RL * $valRL->extra_beds_charges);
                                        
                                        if($RL == 1){
                                            $room_name                  = 'Triple';
                                        }
                                        
                                        if($RL == 2){
                                            $room_name                  = 'Quad';
                                        }
                                        
                                        if($RL == 3){
                                            $room_name                  = 'Quint';
                                        }
                                        
                                        if($RL == 4){
                                            $room_name                  = '6 Pax Room';
                                        }
                                    }
                                    
                                    $newRoomOptionObject                = [
                                        'make_on_reqest_able'           => $valRO->make_on_reqest_able,
                                        'booking_req_id'                => $valRO->booking_req_id,
                                        'allotment'                     => $valRO->allotment,
                                        'room_name'                     => $room_name,
                                        'room_code'                     => $valRO->room_code,
                                        'request_type'                  => $valRO->request_type,
                                        'board_id'                      => $valRO->board_id,
                                        'board_code'                    => $valRO->board_code,
                                        'rooms_total_price'             => $rooms_total_price,
                                        'rooms_selling_price'           => $rooms_selling_price,
                                        'rooms_total_price_Promotion'   => $valRO->rooms_total_price_Promotion,
                                        'rooms_selling_price_Promotion' => $valRO->rooms_selling_price_Promotion,
                                        'rooms_qty'                     => $valRO->rooms_qty,
                                        'room_Quantity'                 => $valRO->room_Quantity,
                                        'adults'                        => $adults,
                                        'childs'                        => $childs,
                                        'cancliation_policy_arr'        => $valRO->cancliation_policy_arr,
                                        'rooms_list'                    => $valRO->rooms_list,
                                        'room_supplier_code'            => $valRO->room_supplier_code,
                                        'room_Promotions_Exist'         => $valRO->room_Promotions_Exist,
                                        'room_Promotions'               => $valRO->room_Promotions,
                                        'room_view'                     => $valRO->room_view,
                                        'rooms_images'                  => $valRO->rooms_images,
                                        'rooms_facilities'              => $valRO->rooms_facilities,
                                        'quantityDateRange'             => $valRO->quantityDateRange ?? '',
                                    ];
                                    array_push($room_options,$newRoomOptionObject);
                                }
                            }
                        }
                    }
                    if(count($room_options) > 0){
                        $newRoomOptions = $room_options;
                    }
                    // return $newRoomOptions;
                }
                
                $hotel_detials_generted_Obj = (Object)[
                    'hotel_provider'            => 'Custome_hotel',
                    'admin_markup'              => $hotel_rooms_data->admin_markup ?? $admin_custom_markup,
                    'customer_markup'           => $hotel_rooms_data->customer_markup ?? $customer_custom_hotel_markup,
                    'admin_markup_type'         => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                    'customer_markup_type'      => $hotel_rooms_data->customer_markup_type ?? $customer_custom_hotel_markup_type,
                    'hotel_code'                => $hotel_data->id,
                    'hotel_name'                => $hotel_data->property_name,
                    'hotel_address'             => $hotel_data->property_address,
                    'room_Quantity'             => $hotel_rooms_data->room_Quantity ?? 0,
                    'longitude'                 => $request->long ?? $hotel_data->longitude,
                    'latitude'                  => $request->lat ?? $hotel_data->latitude,
                    'hotel_country'             => $hotel_data->property_country ?? '',
                    'hotel_city'                => $hotel_data->property_city ?? '',
                    'stars_rating'              => $hotel_rooms_data->stars_rating,
                    'hotel_curreny'             => $hotel_rooms_data->hotel_curreny,
                    'min_price_Actual'          => $hotel_rooms_data->min_price_Actual ?? 0,
                    'max_price_Actual'          => $hotel_rooms_data->max_price_Actual ?? 0,
                    'min_price'                 => $hotel_rooms_data->min_price,
                    'max_price'                 => $hotel_rooms_data->max_price,
                    'description'               => $hotel_data->property_desc ?? '',
                    'hotel_gallery'             => $hotel_images_gallery,
                    'hotel_boards'              => [],
                    'hotel_segments'            => [],
                    'hotel_facilities'          => unserialize($hotel_data->facilities),
                    'rooms_options'             => $newRoomOptions ?? $hotel_rooms_data->rooms_options,
                    'client_Id'                 => $hotel_rooms_data->client_Id ?? '',
                ];
                
                return response()->json([
                    'status'        => 'success',
                    'hotel_details' => $hotel_detials_generted_Obj
                ]);
            }
            
            if($hotel_rooms_data->hotel_provider == 'custom_hotel_provider'){
                
                $hotel_data     = DB::table('hotels')->where('id',$hotel_rooms_data->hotel_id)->first();
                $customer_data  = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
                
                // Images
                $hotel_images_gallery = [];
                if(isset($hotel_data->room_gallery)){
                    $gallery_images = json_decode($hotel_data->room_gallery);
                    foreach($gallery_images as $hotel_img){
                        $hotel_images_gallery[] = $customer_data->webiste_Address.'/public/uploads/more_room_images/'.$hotel_img.'';
                    }
                }
                
                if(isset($hotel_rooms_data->rooms_options)){
                    $gallery_images = [];
                    if(isset($hotel_data->room_gallery)){
                        $gallery_images = json_decode($hotel_data->room_gallery);
                    }
                    foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                        $image_arr = [];
                        if(isset($gallery_images[$index])){
                            $room_img = $customer_data->webiste_Address.'/public/uploads/more_room_images/'.$gallery_images[$index].'';
                        }else{
                            $room_img = $customer_data->webiste_Address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'';
                        }
                        $image_arr[]                                                = $room_img;
                        $hotel_rooms_data->rooms_options[$index]->rooms_images      = $image_arr; 
                        $hotel_rooms_data->rooms_options[$index]->rooms_facilities  = unserialize($hotel_data->facilities);
                    }
                }
                
                $admin_custom_hotel_pro_markup  = 0;
                $customer_markup                = 0;
                
                foreach($markup as $data){
                    if($data->added_markup == 'synchtravel'){
                        if($data->provider == $hotel_rooms_data->custom_hotel_provider){
                            $admin_custom_hotel_pro_markup = $data->markup_value;
                        }
                        
                        if($data->provider == 'All'){
                            $admin_custom_hotel_pro_markup =$data->markup_value;
                        }
                    }
                    
                    if($data->added_markup == 'Haramayn_hotels'){
                        $customer_markup =  $data->markup_value;
                    }
                }
                
                $hotel_detials_generted_Obj = (Object)[
                    'hotel_provider'        => 'custom_hotel_provider',
                    'custom_hotel_provider' => $hotel_rooms_data->custom_hotel_provider,
                    'admin_markup'          => $hotel_rooms_data->admin_markup ?? $admin_custom_hotel_pro_markup,
                    'customer_markup'       => $hotel_rooms_data->customer_markup ?? $customer_markup,
                    'admin_markup_type'     => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                    'customer_markup_type'  => $hotel_rooms_data->customer_markup_type ?? 'Percentage',
                    'hotel_code'            => $hotel_data->id,
                    'hotel_name'            => $hotel_data->property_name,
                    'hotel_address'         => $hotel_data->property_address,
                    'longitude'             => $hotel_data->longitude,
                    'latitude'              => $hotel_data->latitude,
                    'hotel_country'         => $hotel_data->property_country ?? '',
                    'hotel_city'            => $hotel_data->property_city ?? '',
                    'stars_rating'          => $hotel_rooms_data->stars_rating,
                    'hotel_curreny'         => $hotel_rooms_data->hotel_curreny,
                    'min_price'             => $hotel_rooms_data->min_price,
                    'max_price'             => $hotel_rooms_data->max_price,
                    'description'           => $hotel_data->property_desc ?? '',
                    'hotel_gallery'         => $hotel_images_gallery,
                    'hotel_boards'          => [],
                    'hotel_segments'        => [],
                    'hotel_facilities'      => unserialize($hotel_data->facilities),
                    'rooms_options'         => $hotel_rooms_data->rooms_options
                ];
                
                return response()->json([
                    'status'            => 'success',
                    'hotel_details'     => $hotel_detials_generted_Obj
                ]);
            }
            
            if($hotel_rooms_data->hotel_provider == 'travelenda'){
                $data           = array(     
                    'case'      => 'GetHotelDetails',
                    'HotelId'   => $hotel_rooms_data->hotel_id,
                );
                $curl           = curl_init();
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
                $response           = curl_exec($curl);
                $hotel_meta_data    = json_decode($response);
                // return $hotel_meta_data;
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
                            
                            // Cancellation Policy
                            $reqdata    =   "<Request>
                                                <Head>
                                                    <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
                                                    <Password>XjzqSyyOL0EV</Password>
                                                    <RequestType>HotelPolicies</RequestType>
                                                </Head>
                                                <Body>
                                                    <OptionId>".$room_availibilty_res->booking_req_id."</OptionId>
                                                </Body>
                                            </Request>";
                            $curl = curl_init();
                            curl_setopt_array(
                                $curl,
                                array(
                                    CURLOPT_URL => 'https://xml.travellanda.com/xmlv1/HotelPoliciesRequest.xsd',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => http_build_query(array('xml' => $reqdata)),
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/x-www-form-urlencoded'
                                    ),
                                )
                            );
                            $response               = curl_exec($curl);
                            $xml                    = simplexml_load_string($response);
                            $json                   = json_encode($xml);
                            $result_HotelPolicies   = json_decode($json);
                            
                            if(isset($result_HotelPolicies->Body->OptionId)){
                                if($room_availibilty_res->booking_req_id == $result_HotelPolicies->Body->OptionId){
                                    $cancliation_policy_arr             = [];
                                    if(isset($result_HotelPolicies->Body->Policies->Policy)){
                                        foreach($result_HotelPolicies->Body->Policies->Policy as $cancel_res){
                                            $cancel_tiem                 = (Object)[
                                                'amount'                => $cancel_res->Value ?? '',
                                                'type'                  => $cancel_res->Type ?? '',
                                                'from_date'             => $cancel_res->From ?? '',
                                            ];
                                            $cancliation_policy_arr[]    = $cancel_tiem;
                                        }
                                    }
                                    $hotel_rooms_data->rooms_options[$index]->cancliation_policy_arr = $cancliation_policy_arr;
                                }
                            }
                            // Cancellation Policy
                            
                            $hotel_rooms_data->rooms_options[$index]->rooms_images      = $image_arr;
                            $hotel_rooms_data->rooms_options[$index]->rooms_facilities  = $room_facilities_arr;
                        }
                    }
                    
                    $address                    = $hotel_meta_data->Hotel->Address;
                    
                    $Location                   = $hotel_meta_data->Hotel->Location ?? '';
                    if (is_array($Location)) {
                        if (empty($Location)) {
                            $Location           = '';
                        }
                    } elseif (is_object($Location)) {
                        if (json_encode($Location) === '{}') {
                            $Location           = '';
                        }
                    } else {
                        $Location               = $Location;
                    }
                    
                    $hotel_detials_generted_Obj = (Object)[
                        'hotel_provider'        => 'travelenda',
                        'admin_markup'          => $hotel_rooms_data->admin_markup ?? $admin_travelenda_markup,
                        'customer_markup'       => $hotel_rooms_data->customer_markup ?? $customer_markup,
                        'admin_markup_type'     => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                        'customer_markup_type'  => $hotel_rooms_data->customer_markup_type ?? 'Percentage',
                        'hotel_code'            => $hotel_rooms_data->hotel_id,
                        'hotel_name'            => $hotel_rooms_data->hotel_name,
                        'hotel_address'         => $address,
                        'longitude'             => $hotel_meta_data->Hotel->Longitude,
                        'latitude'              => $hotel_meta_data->Hotel->Latitude,
                        'hotel_country'         => $Location ?? '',
                        'hotel_city'            => $Location ?? '',
                        'stars_rating'          => $hotel_rooms_data->stars_rating,
                        'hotel_curreny'         => $hotel_rooms_data->hotel_curreny,
                        'min_price'             => $hotel_rooms_data->min_price,
                        'max_price'             => $hotel_rooms_data->max_price,
                        'description'           => $hotel_meta_data->Hotel->Description ?? '',
                        'hotel_gallery'         => $hotel_images_gallery,
                        'hotel_boards'          => $hotel_barod_arr,
                        'hotel_segments'        => $hotel_segments_arr,
                        'hotel_facilities'      => $hotel_facilities_arr,
                        'rooms_options'         => $hotel_rooms_data->rooms_options
                    ];
                    
                    return response()->json([
                        'status'        => 'success',
                        'hotel_details' => $hotel_detials_generted_Obj
                    ]);
                }
            }
            
            if($hotel_rooms_data->hotel_provider == 'hotel_beds'){
                $data                   = array(
                    'case'              => 'hotel_details',
                    'hotel_beds_code'   => $hotel_rooms_data->hotel_id,
                );
                $curl                   = curl_init();
                curl_setopt_array($curl, array(
                    //CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_hotel_details_by_hotelbeds',
                    CURLOPT_URL => 'https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi_live.php',
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
                
                $response                   = curl_exec($curl);
                $hotel_meta_data            = json_decode($response);
                // return $hotel_meta_data;
              
                $hotel_meta_data            = $hotel_meta_data->hotel;
              
                $hotel_name                 = '';
                if(isset($hotel_meta_data->name->content)){
                    $hotel_name             = $hotel_meta_data->name->content;
                }
                
                $hotel_images_gallery       = [];
                if(isset($hotel_meta_data->images)){
                    foreach($hotel_meta_data->images as $hotel_img){
                        $hotel_images_gallery[] = 'https://photos.hotelbeds.com/giata/bigger/'.$hotel_img->path.'';
                    }
                }
                
                $hotel_barod_arr            = [];
                if(isset($hotel_meta_data->boards)){
                    foreach($hotel_meta_data->boards as $board_res){
                        $board_item         = (Object)[
                            'code'          => $board_res->code,
                            'board_name'    => $board_res->description->content
                        ];
                        $hotel_barod_arr[] = $board_item;
                    }
                }
                
                $hotel_segments_arr         = [];
                if(isset($hotel_meta_data->segments)){
                    foreach($hotel_meta_data->segments as $segment_res){
                        $hotel_segments_arr[] = $segment_res->description->content;
                    }
                }
                
                $hotel_facilities_arr       = [];
                if(isset($hotel_meta_data->facilities)){
                    foreach($hotel_meta_data->facilities as $facility_res){
                        $hotel_facilities_arr[] = $facility_res->description->content;
                    }
                }
                
                if(isset($hotel_rooms_data->rooms_options)){
                    foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                        foreach($hotel_meta_data->rooms as $hotel_rooms_data_res){
                            if($room_availibilty_res->room_code == $hotel_rooms_data_res->roomCode){
                              // Save Rooms Images
                              
                                $image_arr = [];
                                if(isset($hotel_meta_data->images) && $hotel_meta_data->images != NULL){
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
                               
                                $hotel_rooms_data->rooms_options[$index]->rooms_images       = $image_arr; 
                                $hotel_rooms_data->rooms_options[$index]->rooms_facilities   = $room_facilities_arr; 
                            }
                        }
                    }
                }
                
                $address                    = $hotel_meta_data->destination->name->content ?? '';
                $address                    .= " ".$hotel_meta_data->country->description->content ?? "";
                
                $hotel_detials_generted_Obj = (Object)[
                    'hotel_provider'        => 'hotel_beds',
                    'admin_markup'          => $hotel_rooms_data->admin_markup ?? $admin_hotel_bed_markup,
                    'customer_markup'       => $hotel_rooms_data->customer_markup ?? $customer_markup,
                    'admin_markup_type'     => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                    'customer_markup_type'  => $hotel_rooms_data->customer_markup_type ?? 'Percentage',
                    'hotel_code'            => $hotel_meta_data->code,
                    'hotel_name'            => $hotel_name,
                    'hotel_address'         => $address,
                    'longitude'             => $hotel_meta_data->coordinates->longitude,
                    'latitude'              => $hotel_meta_data->coordinates->latitude,
                    'hotel_country'         => $hotel_meta_data->country->description->content ?? '',
                    'hotel_city'            => $hotel_meta_data->destination->name->content ?? '',
                    'stars_rating'          => $hotel_rooms_data->stars_rating,
                    'hotel_curreny'         => $hotel_rooms_data->hotel_curreny,
                    'min_price'             => $hotel_rooms_data->min_price,
                    'max_price'             => $hotel_rooms_data->max_price,
                    'description'           => $hotel_meta_data->description->content ?? '',
                    'hotel_gallery'         => $hotel_images_gallery,
                    'hotel_boards'          => $hotel_barod_arr,
                    'hotel_segments'        => $hotel_segments_arr,
                    'hotel_facilities'      => $hotel_facilities_arr,
                    'rooms_options'         => $hotel_rooms_data->rooms_options
                ];
                
                return response()->json([
                    'status'                => 'success',
                    'hotel_details'         => $hotel_detials_generted_Obj
                ]);
            }
            
            if($hotel_rooms_data->hotel_provider == 'Stuba') {
                $data                       = DB::table('stuba_hotel_details')->where('hotelid', $hotel_rooms_data->hotel_id)->get();
                foreach ($data as $hotel){
                    $hotelRegion            = json_decode($hotel->hotelRegion);
                    $hotelrating            = json_decode($hotel->hotelrating);
                    
                    $hotelObject            = [
                        "hotel_id"          => $hotel->hotelid,
                        "hotel_name"        => $hotel->hotelname,
                        "hotel_stars"       => (float)$hotel->hotelstars,
                        "hotel_region_id"   => $hotelRegion[0]->ID,
                        "hotel_address"     => json_decode($hotel->hoteladdress, true),
                        "hotel_latitude"    => json_decode($hotel->hotellocation)->Latitude ?? '',
                        "hotel_longitude"   => json_decode($hotel->hotellocation)->Longitude ?? '',
                        "hotel_description" => array_column(json_decode($hotel->hoteldescription, true), 'Text'),
                        "hotel_images"      => array_map(function ($url) {
                            return "https://api.stuba.com" . $url;
                        }, array_column(json_decode($hotel->hotelimages, true), 'Url')),
                        "hotel_rating"      => $hotelrating[0]->Description ?? '',
                        "hotel_facilities"  => array_map(function ($amenity) {
                            return $amenity['Text'] ?? '';
                        }, json_decode($hotel->hotelamenity, true))
                    ];
                    
                    function isImageUrl($url){
                        $headers = get_headers($url, 1);
                        if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'image') !== false) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                    
                    $images         = [];
                    foreach ($hotelObject['hotel_images'] as $image) {
                        if (isImageUrl($image)) {
                            $images[]           = $image;
                        } else {
                            $images[]           = 'https://as2.ftcdn.net/v2/jpg/04/70/29/97/1000_F_470299797_UD0eoVMMSUbHCcNJCdv2t8B2g1GVqYgs.jpg';
                        }
                    }
                    
                    if(isset($hotel_rooms_data->rooms_options)){
                        foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                            $hotel_rooms_data->rooms_options[$index]->rooms_images  = $images; 
                        }
                    }
                    
                    $hotel_detials_generted_Obj = (object)[
                        'hotel_provider'        => 'Stuba',
                        'admin_markup_type'     => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                        'admin_markup'          => $hotel_rooms_data->admin_markup ?? $admin_custom_markup,
                        'customer_markup_type'  => $hotel_rooms_data->customer_markup_type ?? 'Percentage',
                        'customer_markup'       => $hotel_rooms_data->customer_markup ?? $customer_markup,
                        'hotel_code'            => $hotelObject['hotel_id'],
                        'hotel_name'            => $hotelObject['hotel_name'],
                        'hotel_address'         => $hotelObject['hotel_address']['Address1'],
                        'longitude'             => $hotelObject['hotel_longitude'],
                        'latitude'              => $hotelObject['hotel_latitude'],
                        'hotel_country'         => $hotelObject['hotel_address']['Country'] ?? '',
                        'hotel_city'            => $hotelObject['hotel_city'] ?? '',
                        'stars_rating'          => $hotelObject['hotel_stars'],
                        'hotel_curreny'         => $hotel_rooms_data->hotel_curreny,
                        'min_price'             => $hotel_rooms_data->min_price,
                        'max_price'             => $hotel_rooms_data->max_price,
                        'description'           => isset($hotelObject['hotel_description']) ? $hotelObject['hotel_description'] : '',
                        'hotel_gallery'         => $images,
                        'hotel_boards'          => [],
                        'hotel_segments'        => [],
                        'hotel_facilities'      => $hotelObject['hotel_facilities']['Text'] ?? $hotelObject['hotel_facilities'],
                        'rooms_options'         => $hotel_rooms_data->rooms_options
                    ];
                    return response()->json([
                        'status'                => 'success',
                        'hotel_details'         => $hotel_detials_generted_Obj
                    ]);
                }
            }
            
            if($hotel_rooms_data->hotel_provider == 'Sunhotel') {
                $sunHotel_hotel_details = SunHotel_Controller::sunHotelDetails($request->hotel_Id);
                // return $sunHotel_hotel_details;
                
                $hotel_name = '';
                if (isset($hotel_rooms_data->hotel_name)) {
                    $hotel_name = $hotel_rooms_data->hotel_name;
                }
                
                // Images
                $hotel_images_gallery = [];
                if (isset($sunHotel_hotel_details['hotels']['hotel']['images'])) {
                    foreach ($sunHotel_hotel_details['hotels']['hotel']['images']['image'] as $hotel_img) {
                        if(isset($hotel_img['smallImage']['@attributes']['url'])){
                            $image_URL    = env('SUN_HOTEL_IMAGE_URL').$hotel_img['smallImage']['@attributes']['url'];
                        }
                        $hotel_images_gallery[] = $image_URL;
                    }
                }
                
                // Board
                $hotel_barod_arr    = [];
                
                // Segments
                $hotel_segments_arr = [];
                
                // Hotel Facilities
                $hotel_facilities_arr = [];
                if (isset($sunHotel_hotel_details['hotels']['hotel']['features']['feature'])) {
                    $sunHotel_facility = $sunHotel_hotel_details['hotels']['hotel']['features']['feature'];
                    for ($count = 0; $count < count($sunHotel_facility); $count++) {
                        if ($count < 7) {
                            $hotel_facilities_arr[] = $sunHotel_facility[$count]['@attributes']['name'];
                        } else {
                            break;
                        }
                    }
                }
                
                // Hotel Rooms
                if (isset($hotel_rooms_data->rooms_options)) {
                    foreach ($hotel_rooms_data->rooms_options as $room_availibilty_res) {
                        // return $room_availibilty_res;
                        $room_availibilty_res->rooms_images      = $hotel_images_gallery;
                        $room_availibilty_res->rooms_facilities  = $hotel_facilities_arr;
                    }
                }
                
                $address = $sunHotel_hotel_details['hotels']['hotel']['hotel.address'];
                
                $hotel_detials_generted_Obj = (object)[
                    'hotel_provider'        => 'Sunhotel',
                    'admin_markup'          => $hotel_rooms_data->admin_markup ?? $admin_hotel_bed_markup,
                    'customer_markup'       => $hotel_rooms_data->customer_markup ?? $customer_markup,
                    'admin_markup_type'     => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                    'customer_markup_type'  => $hotel_rooms_data->customer_markup_type ?? 'Percentage',
                    'hotel_code'            => $sunHotel_hotel_details['hotels']['hotel']['hotel.id'],
                    'hotel_name'            => $hotel_name,
                    'hotel_address'         => $address,
                    'longitude'             => $request->lat,
                    'latitude'              => $request->long,
                    'hotel_country'         => $sunHotel_hotel_details['hotels']['hotel']['hotel.addr.country'],
                    'hotel_city'            => $sunHotel_hotel_details['hotels']['hotel']['hotel.addr.city'],
                    'stars_rating'          => (float)$sunHotel_hotel_details['hotels']['hotel']['classification'],
                    'hotel_curreny'         => $hotel_rooms_data->hotel_curreny,
                    'min_price'             => $hotel_rooms_data->min_price,
                    'max_price'             => $hotel_rooms_data->max_price,
                    // 'description' => strip_tags($sunHotel_hotel_details['hotels']['hotel']['Description']),
                    'description'           => $sunHotel_hotel_details['hotels']['hotel']['description'],
                    'hotel_gallery'         => $hotel_images_gallery,
                    'hotel_boards'          => $hotel_barod_arr,
                    'hotel_segments'        => $hotel_segments_arr,
                    'hotel_facilities'      => $hotel_facilities_arr,
                    'rooms_options'         => $hotel_rooms_data->rooms_options
                ];
                
                return response()->json([
                    'status'        => 'success',
                    'hotel_details' => $hotel_detials_generted_Obj
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
    // Custom Search
    
    public function all_Hotel_Cancellation_Policy(Request $request){
        // return $request;
        $cancliation_policy_arr = [];
        
        if($request->hotel_provider == 'travelenda'){
            // Cancellation Policy
            $reqdata    =   "<Request>
                                <Head>
                                    <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
                                    <Password>XjzqSyyOL0EV</Password>
                                    <RequestType>HotelPolicies</RequestType>
                                </Head>
                                <Body>
                                    <OptionId>".$request->booking_req_id."</OptionId>
                                </Body>
                            </Request>";
            $curl = curl_init();
            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => 'https://xml.travellanda.com/xmlv1/HotelPoliciesRequest.xsd',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => http_build_query(array('xml' => $reqdata)),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/x-www-form-urlencoded'
                    ),
                )
            );
            $response               = curl_exec($curl);
            $xml                    = simplexml_load_string($response);
            $json                   = json_encode($xml);
            $result_HotelPolicies   = json_decode($json);
            
            // return $result_HotelPolicies;
            
            if(isset($result_HotelPolicies->Body->OptionId)){
                if(isset($result_HotelPolicies->Body->Policies->Policy)){
                    foreach($result_HotelPolicies->Body->Policies->Policy as $cancel_res){
                        $cancel_tiem                = (Object)[
                            'CancellationDeadline'  => $result_HotelPolicies->Body->CancellationDeadline ?? '',
                            'amount'                => $cancel_res->Value ?? '',
                            'type'                  => $cancel_res->Type ?? '',
                            'from_date'             => $cancel_res->From ?? '',
                        ];
                        $cancliation_policy_arr[]   = $cancel_tiem;
                    }
                }
            }
        }
        
        if($request->hotel_provider == 'Stuba'){
            $cancliation_policy_arr     = [];
            $response                   = Stuba_Controller::stuba_Booking_Prepare($request->booking_req_id);
            // return $response['Booking'];
            if(isset($response['Booking'])){
                if(isset($response['Booking']['HotelBooking']['Room'])){
                    $Hotel_Rooms                    = $response['Booking']['HotelBooking']['Room'];
                    if(isset($Hotel_Rooms['CanxFees'])){
                        $CanxFees                   = $Hotel_Rooms['CanxFees'];
                        if(isset($CanxFees['Fee'])){
                            $Fee                    = $CanxFees['Fee'];
                            // return $Fee['@attributes']['from'];
                            if(isset($Fee['@attributes'])){
                                $cancel_tiem        = (Object)[
                                    'amount'        => $Fee['Amount']['@attributes']['amt'] ?? '',
                                    'type'          => $Fee['Amount']['@attributes']['type'] ?? 'Fix Amount',
                                    'from_date'     => $Fee['@attributes']['from'] ?? '',
                                ];
                                $cancliation_policy_arr[]    = $cancel_tiem;
                            }else{
                                if(is_array($Fee) && count($Fee) > 0){
                                    foreach($Fee as $val_F){
                                        $cancel_tiem        = (Object)[
                                            'amount'        => $val_F['Amount']['@attributes']['amt'] ?? '',
                                            'type'          => $val_F['Amount']['@attributes']['type'] ?? 'Fix Amount',
                                            'from_date'     => $val_F['@attributes']['from'] ?? '',
                                        ];
                                        $cancliation_policy_arr[]    = $cancel_tiem;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // Cancellation Policy
        }
        
        return $cancliation_policy_arr;
        // Cancellation Policy
    }
    
    public function view_hotel_details(Request $request){
        $hotel_rooms_data                   = json_decode($request->hotel_search_data);
        // return $hotel_rooms_data;
        $sub_customer_id                    = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        $markup                             = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)
                                                // ->orwhere('added_markup','synchtravel')
                                                ->orderBy('id','DESC')->get();
        $admin_travelenda_markup            = 0;
        $admin_hotel_bed_markup             = 0;
        $admin_custom_markup                = 0;
        $customer_markup                    = 0;
        $customer_custom_hotel_markup       = 0;
        $customer_custom_hotel_markup_type  = '';
        
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
                   
                    $admin_travelenda_markup = $data->markup_value;  
                }
                
                if($data->provider == 'Hotelbeds'){
                    $admin_hotel_bed_markup = $data->markup_value;
                }
                
                if($data->provider == 'All'){
                    $admin_travelenda_markup    = $data->markup_value;
                    $admin_hotel_bed_markup     = $data->markup_value;
                    $admin_custom_markup        = $data->markup_value;
                }   
            }
            
            if($data->added_markup == 'Haramayn_hotels'){
                $customer_markup =  $data->markup_value;
            }
        
        }
        
        $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id',$sub_customer_id->id)->where('status',1)->first();
        if($custom_hotel_markup){
            $customer_custom_hotel_markup       = $custom_hotel_markup->markup_value;
            $customer_custom_hotel_markup_type  = $custom_hotel_markup->markup;
        }
        
        // B2B Markup
        $markup_Find    = false;
        $user_Token     = $request->token;
        $b2b_Agent_Id   = $request->b2b_agent_id ?? '';
        $b2b_Markup     = DB::table('b2b_Agent_Markups')
                            ->where(function ($query) use ($user_Token, $b2b_Agent_Id) {
                                $query->where('token', $user_Token)
                                ->where('agent_Id', $b2b_Agent_Id)
                                ->where('markup_status',1);
                            })
                            ->orWhere(function ($query) use ($user_Token, $b2b_Agent_Id) {
                                $query->where('token', $user_Token)
                                ->where('agent_Id', 0)
                                ->where('markup_status',1);
                            })
                            ->get();
        // return $b2b_Markup;
        if(!empty($b2b_Markup) && $b2b_Markup != null){
            foreach($b2b_Markup as $val_BAM){
                if($val_BAM->agent_Id == $b2b_Agent_Id){
                    $customer_custom_hotel_markup_type  = $val_BAM->markup_Type; 
                    $customer_markup                    = $val_BAM->markup_Value;
                    $customer_custom_hotel_markup       = $val_BAM->markup_Value;
                    $markup_Find                        = true;
                }
            }
            
            if($markup_Find != true){
                foreach($b2b_Markup as $val_BAM){
                    if($val_BAM->agent_Id == 0){
                        $customer_custom_hotel_markup_type  = $val_BAM->markup_Type; 
                        $customer_markup                    = $val_BAM->markup_Value;
                        $customer_custom_hotel_markup       = $val_BAM->markup_Value;
                    }
                }
            }
        }
        // B2B Markup
        
        // Detials For Custom Hotel Provider
        if($hotel_rooms_data->hotel_provider == 'custom_hotel_provider'){
            
            $hotel_data     = DB::table('hotels')->where('id',$hotel_rooms_data->hotel_id)->first();
            $customer_data  = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
            
            // Images
            $hotel_images_gallery = [];
            if(isset($hotel_data->room_gallery)){
                $gallery_images = json_decode($hotel_data->room_gallery);
                foreach($gallery_images as $hotel_img){
                    $hotel_images_gallery[] = $customer_data->webiste_Address.'/public/uploads/more_room_images/'.$hotel_img.'';
                }
            }
            
            if(isset($hotel_rooms_data->rooms_options)){
                $gallery_images = [];
                if(isset($hotel_data->room_gallery)){
                    $gallery_images = json_decode($hotel_data->room_gallery);
                }
                foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                    $image_arr = [];
                    if(isset($gallery_images[$index])){
                        $room_img = $customer_data->webiste_Address.'/public/uploads/more_room_images/'.$gallery_images[$index].'';
                    }else{
                        $room_img = $customer_data->webiste_Address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'';
                    }
                    $image_arr[]                                                = $room_img;
                    $hotel_rooms_data->rooms_options[$index]->rooms_images      = $image_arr; 
                    $hotel_rooms_data->rooms_options[$index]->rooms_facilities  = unserialize($hotel_data->facilities);
                }
            }
            
            $admin_custom_hotel_pro_markup  = 0;
            $customer_markup                = 0;
            
            foreach($markup as $data){
                if($data->added_markup == 'synchtravel'){
                    if($data->provider == $hotel_rooms_data->custom_hotel_provider){
                        $admin_custom_hotel_pro_markup = $data->markup_value;
                    }
                    
                    if($data->provider == 'All'){
                        $admin_custom_hotel_pro_markup =$data->markup_value;
                    }
                }
                
                if($data->added_markup == 'Haramayn_hotels'){
                    $customer_markup =  $data->markup_value;
                }
            }
            
            $hotel_detials_generted_Obj = (Object)[
                'hotel_provider'        => 'custom_hotel_provider',
                'custom_hotel_provider' => $hotel_rooms_data->custom_hotel_provider,
                'admin_markup'          => $hotel_rooms_data->admin_markup ?? $admin_custom_hotel_pro_markup,
                'customer_markup'       => $hotel_rooms_data->customer_markup ?? $customer_markup,
                'admin_markup_type'     => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                'customer_markup_type'  => $hotel_rooms_data->customer_markup_type ?? 'Percentage',
                'hotel_code'            => $hotel_data->id,
                'hotel_name'            => $hotel_data->property_name,
                'hotel_address'         => $hotel_data->property_address,
                'longitude'             => $hotel_data->longitude,
                'latitude'              => $hotel_data->latitude,
                'hotel_country'         => $hotel_data->property_country ?? '',
                'hotel_city'            => $hotel_data->property_city ?? '',
                'stars_rating'          => $hotel_rooms_data->stars_rating,
                'hotel_curreny'         => $hotel_rooms_data->hotel_curreny,
                'min_price'             => $hotel_rooms_data->min_price,
                'max_price'             => $hotel_rooms_data->max_price,
                'description'           => $hotel_data->property_desc ?? '',
                'hotel_gallery'         => $hotel_images_gallery,
                'hotel_boards'          => [],
                'hotel_segments'        => [],
                'hotel_facilities'      => unserialize($hotel_data->facilities),
                'rooms_options'         => $hotel_rooms_data->rooms_options
            ];
            
            return response()->json([
                'status'            => 'success',
                'hotel_details'     => $hotel_detials_generted_Obj
            ]);
        }
        
        // Detials For Custom Hotel
        if($hotel_rooms_data->hotel_provider == 'Custome_hotel'){
            $hotel_data     = DB::table('hotels')->where('id',$hotel_rooms_data->hotel_id)->first();
            $customer_data  = DB::table('customer_subcriptions')->where('id',$hotel_data->owner_id)->first();
            
            // Images
            $hotel_images_gallery = [];
            if(isset($hotel_data->room_gallery)){
                $gallery_images = json_decode($hotel_data->room_gallery);
                foreach($gallery_images as $hotel_img){
                    if(isset($hotel_data->image_Url_Other_Dashboard) && $hotel_data->image_Url_Other_Dashboard != null && $hotel_data->image_Url_Other_Dashboard != ''){
                        $image_address          = $hotel_data->image_Url_Other_Dashboard;
                    }else{
                        $image_address          = $customer_data->dashboard_Address ?? $customer_data->webiste_Address;
                    }
                    $hotel_images_gallery[] = $image_address.'/public/uploads/more_room_images/'.$hotel_img.'';
                    // $hotel_images_gallery[] = 'https://system.alhijaztours.net/public/uploads/more_room_images/'.$hotel_img.'';
                }
            }
          
            // Hotel Rooms
            if(isset($hotel_rooms_data->rooms_options)){
                $gallery_images                 = [];
                if(isset($hotel_data->room_gallery)){
                    $gallery_images             = json_decode($hotel_data->room_gallery);
                }
                
                foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                    $image_arr                  = [];
                    if(isset($hotel_data->image_Url_Other_Dashboard) && $hotel_data->image_Url_Other_Dashboard != null && $hotel_data->image_Url_Other_Dashboard != ''){
                        $image_address          = $hotel_data->image_Url_Other_Dashboard;
                    }else{
                        $image_address          = $customer_data->dashboard_Address ?? $customer_data->webiste_Address;
                    }
                    
                    if(isset($gallery_images[$index])){
                        $room_img = $image_address.'/public/uploads/more_room_images/'.$gallery_images[$index].'';
                        // $room_img = 'https://system.alhijaztours.net/public/uploads/more_room_images/'.$gallery_images[$index].'';
                    }else{
                        $room_img = $image_address.'/public/uploads/package_imgs/'.$hotel_data->property_img.'';
                        // $room_img = 'https://system.alhijaztours.net/public/uploads/package_imgs/'.$hotel_data->property_img.'';
                    }
                    
                    $image_arr[]                                                = $room_img;
                    $hotel_rooms_data->rooms_options[$index]->rooms_images      = $image_arr; 
                    $hotel_rooms_data->rooms_options[$index]->rooms_facilities  = unserialize($hotel_data->facilities);
                }
            }
            
            // Fetch room rates per date
            $final_rates_datewise = [];
            if($sub_customer_id->id == '28'){
                $room_rates                             = [];
                $all_Room_Details                       = DB::table('rooms')->where('hotel_id', $hotel_rooms_data->hotel_id)->get();
                
                if(isset($all_Room_Details)){
                    foreach($all_Room_Details as $index => $room_res){
                        
                        if ($room_res) {
                            // $room_rates                 = [];
                            $availible_from             = $room_res->availible_from;
                            $availible_to               = $room_res->availible_to;
                            $check_in                   = $availible_from;
                            $check_out                  = $availible_to;
                            
                            // Promotions
                            $room_Promotions            = DB::table('room_promotions')
                                                            ->where(function ($query) use ($check_in, $check_out, $room_res) {
                                                                $query->where('customer_id', $room_res->owner_id)
                                                                ->where('room_Id', $room_res->id)
                                                                ->where('hotel_Id', $room_res->hotel_id)
                                                                ->where(function ($q) use ($check_in, $check_out) {
                                                                    $q->whereBetween('promotion_Date_From', [$check_in, $check_out])
                                                                    ->orWhereBetween('promotion_Date_To', [$check_in, $check_out])
                                                                    ->orWhere(function ($q) use ($check_in, $check_out) { 
                                                                        $q->where('promotion_Date_From', '<=', $check_in)
                                                                        ->where('promotion_Date_To', '>=', $check_out);
                                                                    });
                                                                });
                                                            })
                                                            ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                                $query->where('customer_id', $room_res->owner_id)
                                                                ->where('room_Id', $room_res->id)
                                                                ->where('hotel_Id', $room_res->hotel_id)
                                                                ->where(function ($q) use ($check_in, $check_out) {
                                                                    $q->whereBetween('promotion_Date_From_WD', [$check_in, $check_out])
                                                                    ->orWhereBetween('promotion_Date_To_WD', [$check_in, $check_out])
                                                                    ->orWhere(function ($q) use ($check_in, $check_out) {
                                                                        $q->where('promotion_Date_From_WD', '<=', $check_in)
                                                                        ->where('promotion_Date_To_WD', '>=', $check_out);
                                                                    });
                                                                });
                                                            })
                                                            ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                                $query->where('customer_id', $room_res->owner_id)
                                                                ->where('room_Id', $room_res->id)
                                                                ->where('hotel_Id', $room_res->hotel_id)
                                                                ->where(function ($q) use ($check_in, $check_out) {
                                                                    $q->whereBetween('promotion_Date_From_WE', [$check_in, $check_out])
                                                                    ->orWhereBetween('promotion_Date_To_WE', [$check_in, $check_out])
                                                                    ->orWhere(function ($q) use ($check_in, $check_out) {
                                                                        $q->where('promotion_Date_From_WE', '<=', $check_in)
                                                                        ->where('promotion_Date_To_WE', '>=', $check_out);
                                                                    });
                                                                });
                                                            })
                                                            ->first();
                            $promotion_Exist            = 'NO';
                            if(!empty($room_Promotions)){
                                $promotion_Exist                                            = 'YES';
                                $all_days                                                   = self::getAllDates_Promotion($hotel_rooms_data->check_in, $hotel_rooms_data->check_out);
                            }
                            // Promotions
                            
                            if (!empty($availible_from) && !empty($availible_to)) {
                                $allowd_Rooms                                               = DB::table('allowed_Hotels_Rooms')->where('client_Id',$sub_customer_id->id)->where('room_Id',$room_res->id)->get();
                                $startDate                                                  = new \DateTime($availible_from);
                                $endDate                                                    = new \DateTime($availible_to);
                                
                                while ($startDate->format('Y-m-d') <= $endDate->format('Y-m-d')) {
                                    if ($room_res->price_week_type == 'for_all_days') {
                                        $daily_price                                        = $room_res->price_all_days ?? 0;
                                        if($promotion_Exist == 'YES'){
                                            $all_days_Promotions                            = self::getBetweenDates_Promotion($room_Promotions->promotion_Date_From, $room_Promotions->promotion_Date_To);
                                            $day_res                                        = $startDate->format('Y-m-d');
                                            foreach($all_days_Promotions as $val_PD){
                                                if($day_res == $val_PD){
                                                    $daily_price                            = $room_Promotions->promotion_Rate ?? $room_Promotions->total_Rate ?? 0;
                                                }
                                            }
                                        }else{
                                            if($allowd_Rooms->isEmpty()){
                                                $daily_price                                = $room_res->price_all_days_wi_markup ?? $room_res->price_all_days ?? 0;
                                            }else{
                                                $daily_price                                = $allowd_Rooms[0]->room_Sale_Price_AD ?? 0;
                                            }
                                        }
                                    } else if ($room_res->price_week_type == 'for_week_end') {
                                        $week_days                                          = !empty($room_res->weekdays) ? json_decode($room_res->weekdays, true) : [];
                                        $week_end_days                                      = !empty($room_res->weekends) ? json_decode($room_res->weekends, true) : [];
                                        $weekdays_price                                     = $room_res->weekdays_price ?? 0;
                                        $weekends_price                                     = $room_res->weekends_price ?? 0;
                                        if($promotion_Exist == 'YES'){
                                            $day                                            = $startDate->format('l');
                                            
                                            $all_days_Promotions_WD                         = self::getBetweenDates_Promotion($room_Promotions->promotion_Date_From_WD, $room_Promotions->promotion_Date_To_WD);
                                            $promotion_date_Found_WeekDays                  = false;
                                            $week_day_found                                 = false;
                                            foreach($all_days_Promotions_WD as $key_PD_WD => $val_PD_WD){
                                                if($startDate->format('Y-m-d') == $val_PD_WD){
                                                    foreach($week_days as $week_day_res){
                                                        if($week_day_res == $day){
                                                            $week_day_found                 = true;
                                                            $promotion_date_Found_WeekDays  = true;
                                                            $at_Least_OP                    = true;
                                                            $daily_price                    = $room_Promotions->promotion_Rate_WD;
                                                            unset($all_days_Promotions_WD[$key_PD_WD]);
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            $all_days_Promotions_WE                         = self::getBetweenDates_Promotion($room_Promotions->promotion_Date_From_WE, $room_Promotions->promotion_Date_To_WE);
                                            $promotion_date_Found_WeekEnds                  = false;
                                            $week_end_day_found                             = false;
                                            foreach($all_days_Promotions_WE as $key_PD_WE => $val_PD_WE){
                                                if($startDate->format('Y-m-d') == $val_PD_WE){
                                                    foreach($week_end_days as $week_end_days_res){
                                                        if($week_end_days_res == $day){
                                                            $week_end_day_found             = true;
                                                            $promotion_date_Found_WeekEnds  = true;
                                                            $at_Least_OP                    = true;
                                                            $daily_price                    = $room_Promotions->promotion_Rate_WE;
                                                            unset($all_days_Promotions_WE[$key_PD_WE]);
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            if($promotion_date_Found_WeekDays == false){
                                                foreach($week_days as $week_day_res){
                                                    if($week_day_res == $day){
                                                        $week_day_found = true;
                                                    }
                                                }
                                            }
                                            
                                            if($promotion_date_Found_WeekEnds == false){
                                                foreach($week_end_days as $week_end_days_res){
                                                    if($week_end_days_res == $day){
                                                        $week_end_day_found = true;
                                                    }
                                                }
                                            }
                                            
                                            if($week_day_found == true &&  $promotion_date_Found_WeekDays == false){
                                                $daily_price = $room_Promotions->total_Rate_WD;
                                            }
                                            
                                            if($week_end_day_found == true && $promotion_date_Found_WeekEnds == false){
                                                $daily_price = $room_Promotions->total_Rate_WE;
                                            }
                                        }else{
                                            $dayOfWeek                                      = $startDate->format('l');
                                            if (in_array($dayOfWeek, $week_days)) {
                                                if($allowd_Rooms->isEmpty()){
                                                    $daily_price                            = $room_res->weekdays_price_wi_markup ?? $weekdays_price ?? 0;
                                                }else{
                                                    $daily_price                            = $allowd_Rooms[0]->room_Sale_Price_WD ?? 0;
                                                }
                                            } else {
                                                if($allowd_Rooms->isEmpty()){
                                                    $daily_price                            = $room_res->weekends_price_wi_markup ?? $weekends_price ?? 0;
                                                }else{
                                                    $daily_price                            = $allowd_Rooms[0]->room_Sale_Price_WE ?? 0;
                                                }
                                            }
                                        }
                                    }
                                    
                                    $room_rates[]   = [
                                        'date'      => $startDate->format('Y-m-d'),
                                        'price'     => $daily_price
                                    ];
                                    $startDate->modify('+1 day');
                                }
                            }
                            
                            if (!empty($room_rates)) {
                                $all_Room_Details[$index]->rates_datewise = $room_rates;
                            }
                        }
                    }
                }
                
                foreach ($all_Room_Details as $room) {
                    if (!empty($room->rates_datewise)) {
                        foreach ($room->rates_datewise as $rate) {
                            $date   = $rate['date'];
                            $price  = $rate['price'];
                            if (!isset($final_rates_datewise[$date]) || $price < $final_rates_datewise[$date]) {
                                $final_rates_datewise[$date] = $price;
                            }
                        }
                    }
                }
                
                // Convert associative array to indexed array format
                $final_rates_datewise = array_map(function ($date, $price) {
                    return ['date' => $date, 'price' => $price];
                }, array_keys($final_rates_datewise), $final_rates_datewise);
                
                // Sort by date (optional)
                usort($final_rates_datewise, function ($a, $b) {
                    return strtotime($a['date']) - strtotime($b['date']);
                });
            }
            // Fetch room rates per date
            
            // return $hotel_rooms_data->rooms_options;
            
            $newRoomOptions             = $hotel_rooms_data->rooms_options;
            if($sub_customer_id->id == '59' || $sub_customer_id->id == '60'){
                $room_options           = [];
                if($hotel_rooms_data->rooms_options){
                    foreach($hotel_rooms_data->rooms_options as $valRO){
                        $valRL                                      = $valRO->rooms_list;
                        if($valRL->extra_beds > 0){
                            for($RL=0; $RL<=$valRL->extra_beds; $RL++){
                                $room_name                          = $valRO->room_name;
                                $adults                             = $valRO->adults;
                                $childs                             = $valRO->childs;
                                $rooms_total_price                  = $valRO->rooms_total_price;
                                $rooms_selling_price                = $valRO->rooms_selling_price;
                                
                                if($RL > 0){
                                    $adults                         = $valRO->adults + $RL;
                                    $rooms_total_price              = $valRO->rooms_total_price + ($RL * $valRL->extra_beds_charges);
                                    $rooms_selling_price            = $valRO->rooms_selling_price + ($RL * $valRL->extra_beds_charges);
                                    
                                    if($RL == 1){
                                        $room_name                  = 'Triple';
                                    }
                                    
                                    if($RL == 2){
                                        $room_name                  = 'Quad';
                                    }
                                    
                                    if($RL == 3){
                                        $room_name                  = 'Quint';
                                    }
                                    
                                    if($RL == 4){
                                        $room_name                  = '6 Pax Room';
                                    }
                                }
                                
                                $newRoomOptionObject                = [
                                    'make_on_reqest_able'           => $valRO->make_on_reqest_able,
                                    'booking_req_id'                => $valRO->booking_req_id,
                                    'allotment'                     => $valRO->allotment,
                                    'room_name'                     => $room_name,
                                    'room_code'                     => $valRO->room_code,
                                    'request_type'                  => $valRO->request_type,
                                    'board_id'                      => $valRO->board_id,
                                    'board_code'                    => $valRO->board_code,
                                    'rooms_total_price'             => $rooms_total_price,
                                    'rooms_selling_price'           => $rooms_selling_price,
                                    'rooms_total_price_Promotion'   => $valRO->rooms_total_price_Promotion,
                                    'rooms_selling_price_Promotion' => $valRO->rooms_selling_price_Promotion,
                                    'rooms_qty'                     => $valRO->rooms_qty,
                                    'room_Quantity'                 => $valRO->room_Quantity,
                                    'adults'                        => $adults,
                                    'childs'                        => $childs,
                                    'cancliation_policy_arr'        => $valRO->cancliation_policy_arr,
                                    'rooms_list'                    => $valRO->rooms_list,
                                    'room_supplier_code'            => $valRO->room_supplier_code,
                                    'room_Promotions_Exist'         => $valRO->room_Promotions_Exist,
                                    'room_Promotions'               => $valRO->room_Promotions,
                                    'room_view'                     => $valRO->room_view,
                                    'rooms_images'                  => $valRO->rooms_images,
                                    'rooms_facilities'              => $valRO->rooms_facilities,
                                    'quantityDateRange'             => $valRO->quantityDateRange ?? '',
                                ];
                                array_push($room_options,$newRoomOptionObject);
                            }
                        }else{
                            array_push($room_options,$valRO);
                        }
                    }
                }
                if(count($room_options) > 0){
                    $newRoomOptions = $room_options;
                }
                // return $newRoomOptions;
            }
            
            $hotel_detials_generted_Obj     = (Object)[
                'hotel_provider'            => 'Custome_hotel',
                // 'check_in'                  => $hotel_rooms_data->check_in ?? '',
                // 'check_out'                 => $hotel_rooms_data->check_out ?? '',
                'admin_markup'              => $hotel_rooms_data->admin_markup ?? $admin_custom_markup,
                'customer_markup'           => $hotel_rooms_data->customer_markup ?? $customer_custom_hotel_markup,
                'admin_markup_type'         => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                'customer_markup_type'      => $hotel_rooms_data->customer_markup_type ?? $customer_custom_hotel_markup_type,
                'hotel_code'                => $hotel_data->id,
                'hotel_name'                => $hotel_data->property_name,
                'hotel_address'             => $hotel_data->property_address,
                'room_Quantity'             => $hotel_rooms_data->room_Quantity ?? 0,
                'longitude'                 => $request->long ?? $hotel_data->longitude,
                'latitude'                  => $request->lat ?? $hotel_data->latitude,
                'hotel_country'             => $hotel_data->property_country ?? '',
                'hotel_city'                => $hotel_data->property_city ?? '',
                'stars_rating'              => $hotel_rooms_data->stars_rating,
                'hotel_curreny'             => $hotel_rooms_data->hotel_curreny,
                'min_price_Actual'          => $hotel_rooms_data->min_price_Actual ?? 0,
                'max_price_Actual'          => $hotel_rooms_data->max_price_Actual ?? 0,
                'min_price'                 => $hotel_rooms_data->min_price,
                'max_price'                 => $hotel_rooms_data->max_price,
                'description'               => $hotel_data->property_desc ?? '',
                'hotel_gallery'             => $hotel_images_gallery,
                'hotel_boards'              => [],
                'hotel_segments'            => [],
                'hotel_facilities'          => unserialize($hotel_data->facilities),
                'rooms_options'             => $newRoomOptions,
                'rates_datewise'            => $final_rates_datewise ?? [],
                'client_Id'                 => $hotel_rooms_data->client_Id ?? '',
            ];
            
            return response()->json([
                'status'        => 'success',
                'hotel_details' => $hotel_detials_generted_Obj
            ]);
        }
        
        // Detials For Hotel Beds
        if($hotel_rooms_data->hotel_provider == 'hotel_beds'){
            
            $data                   = array(
                'case'              => 'hotel_details',
                'hotel_beds_code'   => $hotel_rooms_data->hotel_id,
            );
            $curl                   = curl_init();
            curl_setopt_array($curl, array(
                //CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_hotel_details_by_hotelbeds',
                CURLOPT_URL => 'https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi_live.php',
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
            
            $response                   = curl_exec($curl);
            $hotel_meta_data            = json_decode($response);
          
            $hotel_meta_data            = $hotel_meta_data->hotel;
          
            $hotel_name                 = '';
            if(isset($hotel_meta_data->name->content)){
                $hotel_name             = $hotel_meta_data->name->content;
            }
            
            $hotel_images_gallery       = [];
            if(isset($hotel_meta_data->images)){
                foreach($hotel_meta_data->images as $hotel_img){
                    $hotel_images_gallery[] = 'https://photos.hotelbeds.com/giata/bigger/'.$hotel_img->path.'';
                }
            }
            
            $hotel_barod_arr            = [];
            if(isset($hotel_meta_data->boards)){
                foreach($hotel_meta_data->boards as $board_res){
                    $board_item         = (Object)[
                        'code'          => $board_res->code,
                        'board_name'    => $board_res->description->content
                    ];
                    $hotel_barod_arr[] = $board_item;
                }
            }
            
            $hotel_segments_arr         = [];
            if(isset($hotel_meta_data->segments)){
                foreach($hotel_meta_data->segments as $segment_res){
                    $hotel_segments_arr[] = $segment_res->description->content;
                }
            }
            
            $hotel_facilities_arr       = [];
            if(isset($hotel_meta_data->facilities)){
                foreach($hotel_meta_data->facilities as $facility_res){
                    $hotel_facilities_arr[] = $facility_res->description->content;
                }
            }
            
            if(isset($hotel_rooms_data->rooms_options)){
                foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                    foreach($hotel_meta_data->rooms as $hotel_rooms_data_res){
                        if($room_availibilty_res->room_code == $hotel_rooms_data_res->roomCode){
                          // Save Rooms Images
                          
                            $image_arr = [];
                            if(isset($hotel_meta_data->images) && $hotel_meta_data->images != NULL){
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
                           
                            $hotel_rooms_data->rooms_options[$index]->rooms_images       = $image_arr; 
                            $hotel_rooms_data->rooms_options[$index]->rooms_facilities   = $room_facilities_arr; 
                        }
                    }
                }
            }
            
            $address                    = $hotel_meta_data->destination->name->content ?? '';
            $address                    .= " ".$hotel_meta_data->country->description->content ?? "";
            
            $hotel_detials_generted_Obj = (Object)[
                'hotel_provider'        => 'hotel_beds',
                'admin_markup'          => $hotel_rooms_data->admin_markup ?? $admin_hotel_bed_markup,
                'customer_markup'       => $hotel_rooms_data->customer_markup ?? $customer_markup,
                'admin_markup_type'     => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                'customer_markup_type'  => $hotel_rooms_data->customer_markup_type ?? 'Percentage',
                'hotel_code'            => $hotel_meta_data->code,
                'hotel_name'            => $hotel_name,
                'hotel_address'         => $address,
                'longitude'             => $hotel_meta_data->coordinates->longitude,
                'latitude'              => $hotel_meta_data->coordinates->latitude,
                'hotel_country'         => $hotel_meta_data->country->description->content ?? '',
                'hotel_city'            => $hotel_meta_data->destination->name->content ?? '',
                'stars_rating'          => $hotel_rooms_data->stars_rating,
                'hotel_curreny'         => $hotel_rooms_data->hotel_curreny,
                'min_price'             => $hotel_rooms_data->min_price,
                'max_price'             => $hotel_rooms_data->max_price,
                'description'           => $hotel_meta_data->description->content ?? '',
                'hotel_gallery'         => $hotel_images_gallery,
                'hotel_boards'          => $hotel_barod_arr,
                'hotel_segments'        => $hotel_segments_arr,
                'hotel_facilities'      => $hotel_facilities_arr,
                'rooms_options'         => $hotel_rooms_data->rooms_options
            ];
            
            return response()->json([
                'status'                => 'success',
                'hotel_details'         => $hotel_detials_generted_Obj
            ]);
            
        }
      
        // Details For Travelenda
        if($hotel_rooms_data->hotel_provider == 'travelenda'){
            $data           = array(     
                'case'      => 'GetHotelDetails',
                'HotelId'   => $hotel_rooms_data->hotel_id,
            );
            $curl           = curl_init();
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
            $response           = curl_exec($curl);
            $hotel_meta_data    = json_decode($response);
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
                // return $hotel_rooms_data;
                
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
                        
                        // Cancellation Policy
                        // $reqdata    =   "<Request>
                        //                     <Head>
                        //                         <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
                        //                         <Password>XjzqSyyOL0EV</Password>
                        //                         <RequestType>HotelPolicies</RequestType>
                        //                     </Head>
                        //                     <Body>
                        //                         <OptionId>".$room_availibilty_res->booking_req_id."</OptionId>
                        //                     </Body>
                        //                 </Request>";
                        // $curl = curl_init();
                        // curl_setopt_array(
                        //     $curl,
                        //     array(
                        //         CURLOPT_URL => 'https://xml.travellanda.com/xmlv1/HotelPoliciesRequest.xsd',
                        //         CURLOPT_RETURNTRANSFER => true,
                        //         CURLOPT_ENCODING => '',
                        //         CURLOPT_MAXREDIRS => 10,
                        //         CURLOPT_TIMEOUT => 0,
                        //         CURLOPT_FOLLOWLOCATION => true,
                        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        //         CURLOPT_CUSTOMREQUEST => 'POST',
                        //         CURLOPT_POSTFIELDS => http_build_query(array('xml' => $reqdata)),
                        //         CURLOPT_HTTPHEADER => array(
                        //             'Content-Type: application/x-www-form-urlencoded'
                        //         ),
                        //     )
                        // );
                        // $response               = curl_exec($curl);
                        // $xml                    = simplexml_load_string($response);
                        // $json                   = json_encode($xml);
                        // $result_HotelPolicies   = json_decode($json);
                        
                        // if(isset($result_HotelPolicies->Body->OptionId)){
                        //     if($room_availibilty_res->booking_req_id == $result_HotelPolicies->Body->OptionId){
                        //         $cancliation_policy_arr             = [];
                        //         if(isset($result_HotelPolicies->Body->Policies->Policy)){
                        //             foreach($result_HotelPolicies->Body->Policies->Policy as $cancel_res){
                        //                 $cancel_tiem                 = (Object)[
                        //                     'amount'                => $cancel_res->Value ?? '',
                        //                     'type'                  => $cancel_res->Type ?? '',
                        //                     'from_date'             => $cancel_res->From ?? '',
                        //                 ];
                        //                 $cancliation_policy_arr[]    = $cancel_tiem;
                        //             }
                        //         }
                        //         $hotel_rooms_data->rooms_options[$index]->cancliation_policy_arr = $cancliation_policy_arr;
                        //     }
                        // }
                        // Cancellation Policy
                        
                        $hotel_rooms_data->rooms_options[$index]->rooms_images      = $image_arr;
                        $hotel_rooms_data->rooms_options[$index]->rooms_facilities  = $room_facilities_arr;
                    }
                }
                
                $address    = $hotel_meta_data->Hotel->Address;
                $Location   = $hotel_meta_data->Hotel->Location ?? '';
                
                if (is_array($Location)) {
                    if (empty($Location)) {
                        $Location  = '';
                    }
                } elseif (is_object($Location)) {
                    if (json_encode($Location) === '{}') {
                        $Location   = '';
                    }
                } else {
                    $Location   = $Location;
                }

                // return $hotel_meta_data;
                
                $hotel_detials_generted_Obj = (Object)[
                    'hotel_provider'        => 'travelenda',
                    'admin_markup'          => $hotel_rooms_data->admin_markup ?? $admin_travelenda_markup,
                    'customer_markup'       => $hotel_rooms_data->customer_markup ?? $customer_markup,
                    'admin_markup_type'     => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                    'customer_markup_type'  => $hotel_rooms_data->customer_markup_type ?? 'Percentage',
                    'hotel_code'            => $hotel_rooms_data->hotel_id,
                    'hotel_name'            => $hotel_rooms_data->hotel_name,
                    'hotel_address'         => $address,
                    'longitude'             => $hotel_meta_data->Hotel->Longitude,
                    'latitude'              => $hotel_meta_data->Hotel->Latitude,
                    'hotel_country'         => $Location ?? '',
                    'hotel_city'            => $Location ?? '',
                    'stars_rating'          => $hotel_rooms_data->stars_rating,
                    'hotel_curreny'         => $hotel_rooms_data->hotel_curreny,
                    'min_price'             => $hotel_rooms_data->min_price,
                    'max_price'             => $hotel_rooms_data->max_price,
                    'description'           => $hotel_meta_data->Hotel->Description ?? '',
                    'hotel_gallery'         => $hotel_images_gallery,
                    'hotel_boards'          => $hotel_barod_arr,
                    'hotel_segments'        => $hotel_segments_arr,
                    'hotel_facilities'      => $hotel_facilities_arr,
                    'rooms_options'         => $hotel_rooms_data->rooms_options
                ];
                
                return response()->json([
                    'status'        => 'success',
                    'hotel_details' => $hotel_detials_generted_Obj
                ]);
            }
        }
        
        // Details for Stuba
        if ($hotel_rooms_data->hotel_provider == 'Stuba') {
            $data                       = DB::table('stuba_hotel_details')->where('hotelid', $hotel_rooms_data->hotel_id)->get();
            foreach ($data as $hotel){
                $hotelRegion            = json_decode($hotel->hotelRegion);
                $hotelrating            = json_decode($hotel->hotelrating);
                
                $hotelObject            = [
                    "hotel_id"          => $hotel->hotelid,
                    "hotel_name"        => $hotel->hotelname,
                    "hotel_stars"       => (float)$hotel->hotelstars,
                    "hotel_region_id"   => $hotelRegion[0]->ID,
                    "hotel_address"     => json_decode($hotel->hoteladdress, true),
                    "hotel_latitude"    => json_decode($hotel->hotellocation)->Latitude ?? '',
                    "hotel_longitude"   => json_decode($hotel->hotellocation)->Longitude ?? '',
                    "hotel_description" => array_column(json_decode($hotel->hoteldescription, true), 'Text'),
                    "hotel_images"      => array_map(function ($url) {
                        return "https://api.stuba.com" . $url;
                    }, array_column(json_decode($hotel->hotelimages, true), 'Url')),
                    "hotel_rating"      => $hotelrating[0]->Description ?? '',
                    "hotel_facilities"  => array_map(function ($amenity) {
                        return $amenity['Text'] ?? '';
                    }, json_decode($hotel->hotelamenity, true))
                ];
                
                function isImageUrl($url){
                    $headers = get_headers($url, 1);
                    if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'image') !== false) {
                        return true;
                    } else {
                        return false;
                    }
                }
                
                $images         = [];
                foreach ($hotelObject['hotel_images'] as $image) {
                    if (isImageUrl($image)) {
                        $images[]           = $image;
                    } else {
                        $images[]           = 'https://as2.ftcdn.net/v2/jpg/04/70/29/97/1000_F_470299797_UD0eoVMMSUbHCcNJCdv2t8B2g1GVqYgs.jpg';
                    }
                }
                
                if(isset($hotel_rooms_data->rooms_options)){
                    foreach($hotel_rooms_data->rooms_options as $index => $room_availibilty_res){
                        $hotel_rooms_data->rooms_options[$index]->rooms_images  = $images; 
                    }
                }
                
                if(isset($result_HotelPolicies->Body->OptionId)){
                    if($room_availibilty_res->booking_req_id == $result_HotelPolicies->Body->OptionId){
                        $cancliation_policy_arr             = [];
                        if(isset($result_HotelPolicies->Body->Policies->Policy)){
                            foreach($result_HotelPolicies->Body->Policies->Policy as $cancel_res){
                                $cancel_tiem                 = (Object)[
                                    'amount'                => $cancel_res->Value ?? '',
                                    'type'                  => $cancel_res->Type ?? '',
                                    'from_date'             => $cancel_res->From ?? '',
                                ];
                                $cancliation_policy_arr[]    = $cancel_tiem;
                            }
                        }
                        $hotel_rooms_data->rooms_options[$index]->cancliation_policy_arr = $cancliation_policy_arr;
                    }
                }
                // Cancellation Policy
                
                $hotel_detials_generted_Obj = (object)[
                    'hotel_provider'        => 'Stuba',
                    'admin_markup'          => $hotel_rooms_data->admin_markup ?? $admin_custom_markup,
                    'customer_markup'       => $hotel_rooms_data->customer_markup ?? $customer_custom_hotel_markup,
                    'admin_markup_type'     => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                    'customer_markup_type'  => $hotel_rooms_data->customer_markup_type ?? $customer_custom_hotel_markup_type,
                    'hotel_code'            => $hotelObject['hotel_id'],
                    'hotel_name'            => $hotelObject['hotel_name'],
                    'hotel_address'         => $hotelObject['hotel_address']['Address1'],
                    'longitude'             => $hotelObject['hotel_longitude'],
                    'latitude'              => $hotelObject['hotel_latitude'],
                    'hotel_country'         => $hotelObject['hotel_address']['Country'] ?? '',
                    'hotel_city'            => $hotelObject['hotel_city'] ?? '',
                    'stars_rating'          => $hotelObject['hotel_stars'],
                    'hotel_curreny'         => $hotel_rooms_data->hotel_curreny,
                    'min_price'             => $hotel_rooms_data->min_price,
                    'max_price'             => $hotel_rooms_data->max_price,
                    'description'           => isset($hotelObject['hotel_description']) ? $hotelObject['hotel_description'] : '',
                    'hotel_gallery'         => $images,
                    'hotel_boards'          => [],
                    'hotel_segments'        => [],
                    'hotel_facilities'      => $hotelObject['hotel_facilities']['Text'] ?? $hotelObject['hotel_facilities'],
                    'rooms_options'         => $hotel_rooms_data->rooms_options
                ];
                return response()->json([
                    'status'                => 'success',
                    'hotel_details'         => $hotel_detials_generted_Obj
                ]);
            }
        }
        
        // Detials For Sun Hotel
        if ($hotel_rooms_data->hotel_provider == 'Sunhotel') {
            // return $hotel_rooms_data;
            
            $sunHotel_hotel_details = SunHotel_Controller::sunHotelDetails($request->hotel_code);
            // return $sunHotel_hotel_details;
            
            $hotel_name = '';
            if (isset($hotel_rooms_data->hotel_name)) {
                $hotel_name = $hotel_rooms_data->hotel_name;
            }
            
            // Images
            $hotel_images_gallery = [];
            if (isset($sunHotel_hotel_details['hotels']['hotel']['images'])) {
                foreach ($sunHotel_hotel_details['hotels']['hotel']['images']['image'] as $hotel_img) {
                    if(isset($hotel_img['smallImage']['@attributes']['url'])){
                        $image_URL    = env('SUN_HOTEL_IMAGE_URL').$hotel_img['smallImage']['@attributes']['url'];
                    }
                    $hotel_images_gallery[] = $image_URL;
                }
            }
            
            // Board
            $hotel_barod_arr    = [];
            
            // Segments
            $hotel_segments_arr = [];
            
            // Hotel Facilities
            $hotel_facilities_arr = [];
            if (isset($sunHotel_hotel_details['hotels']['hotel']['features']['feature'])) {
                $sunHotel_facility = $sunHotel_hotel_details['hotels']['hotel']['features']['feature'];
                for ($count = 0; $count < count($sunHotel_facility); $count++) {
                    if ($count < 7) {
                        $hotel_facilities_arr[] = $sunHotel_facility[$count]['@attributes']['name'];
                    } else {
                        break;
                    }
                }
            }
            
            // Hotel Rooms
            if (isset($hotel_rooms_data->rooms_options)) {
                foreach ($hotel_rooms_data->rooms_options as $room_availibilty_res) {
                    // return $room_availibilty_res;
                    $room_availibilty_res->rooms_images      = $hotel_images_gallery;
                    $room_availibilty_res->rooms_facilities  = $hotel_facilities_arr;
                }
            }
            
            $address = $sunHotel_hotel_details['hotels']['hotel']['hotel.address'];
            
            // return $hotel_rooms_data;
            
            $hotel_detials_generted_Obj = (object)[
                'hotel_provider'        => 'Sunhotel',
                'admin_markup'          => $hotel_rooms_data->admin_markup ?? $admin_hotel_bed_markup,
                'customer_markup'       => $hotel_rooms_data->customer_markup ?? $customer_markup,
                'admin_markup_type'     => $hotel_rooms_data->admin_markup_type ?? 'Percentage',
                'customer_markup_type'  => $hotel_rooms_data->customer_markup_type ?? 'Percentage',
                'hotel_code'            => $sunHotel_hotel_details['hotels']['hotel']['hotel.id'],
                'hotel_name'            => $hotel_name,
                'hotel_address'         => $address,
                'longitude'             => $request->lat,
                'latitude'              => $request->long,
                'hotel_country'         => $sunHotel_hotel_details['hotels']['hotel']['hotel.addr.country'],
                'hotel_city'            => $sunHotel_hotel_details['hotels']['hotel']['hotel.addr.city'],
                'stars_rating'          => $sunHotel_hotel_details['hotels']['hotel']['classification'],
                'hotel_curreny'         => $hotel_rooms_data->hotel_curreny,
                'min_price'             => $hotel_rooms_data->min_price,
                'max_price'             => $hotel_rooms_data->max_price,
                // 'description' => strip_tags($sunHotel_hotel_details['hotels']['hotel']['Description']),
                'description'           => $sunHotel_hotel_details['hotels']['hotel']['description'],
                'hotel_gallery'         => $hotel_images_gallery,
                'hotel_boards'          => $hotel_barod_arr,
                'hotel_segments'        => $hotel_segments_arr,
                'hotel_facilities'      => $hotel_facilities_arr,
                'rooms_options'         => $hotel_rooms_data->rooms_options
            ];
            
            return response()->json([
                'status'        => 'success',
                'hotel_details' => $hotel_detials_generted_Obj
            ]);
        }
    }
    
    public function room_Rates_Calender(Request $request){
        // Fetch room rates per date
        $sub_customer_id        = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        $final_rates_datewise   = [];
        if(!empty($sub_customer_id)){
            if($sub_customer_id->id == '28' || $sub_customer_id->id == '4'){
                if($request->hotel_provider == 'Custome_hotel'){
                    $room_rates                             = [];
                    $all_Room_Details                       = DB::table('rooms')->where('hotel_id', $request->hotel_id)->get();
                    
                    if(isset($all_Room_Details)){
                        foreach($all_Room_Details as $index => $room_res){
                            
                            if ($room_res) {
                                // $room_rates                 = [];
                                $availible_from             = $room_res->availible_from;
                                $availible_to               = $room_res->availible_to;
                                $check_in                   = $availible_from;
                                $check_out                  = $availible_to;
                                
                                // Promotions
                                $room_Promotions            = DB::table('room_promotions')
                                                                ->where(function ($query) use ($check_in, $check_out, $room_res) {
                                                                    $query->where('customer_id', $room_res->owner_id)
                                                                    ->where('room_Id', $room_res->id)
                                                                    ->where('hotel_Id', $room_res->hotel_id)
                                                                    ->where(function ($q) use ($check_in, $check_out) {
                                                                        $q->whereBetween('promotion_Date_From', [$check_in, $check_out])
                                                                        ->orWhereBetween('promotion_Date_To', [$check_in, $check_out])
                                                                        ->orWhere(function ($q) use ($check_in, $check_out) { 
                                                                            $q->where('promotion_Date_From', '<=', $check_in)
                                                                            ->where('promotion_Date_To', '>=', $check_out);
                                                                        });
                                                                    });
                                                                })
                                                                ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                                    $query->where('customer_id', $room_res->owner_id)
                                                                    ->where('room_Id', $room_res->id)
                                                                    ->where('hotel_Id', $room_res->hotel_id)
                                                                    ->where(function ($q) use ($check_in, $check_out) {
                                                                        $q->whereBetween('promotion_Date_From_WD', [$check_in, $check_out])
                                                                        ->orWhereBetween('promotion_Date_To_WD', [$check_in, $check_out])
                                                                        ->orWhere(function ($q) use ($check_in, $check_out) {
                                                                            $q->where('promotion_Date_From_WD', '<=', $check_in)
                                                                            ->where('promotion_Date_To_WD', '>=', $check_out);
                                                                        });
                                                                    });
                                                                })
                                                                ->orWhere(function ($query) use ($check_in, $check_out, $room_res) {
                                                                    $query->where('customer_id', $room_res->owner_id)
                                                                    ->where('room_Id', $room_res->id)
                                                                    ->where('hotel_Id', $room_res->hotel_id)
                                                                    ->where(function ($q) use ($check_in, $check_out) {
                                                                        $q->whereBetween('promotion_Date_From_WE', [$check_in, $check_out])
                                                                        ->orWhereBetween('promotion_Date_To_WE', [$check_in, $check_out])
                                                                        ->orWhere(function ($q) use ($check_in, $check_out) {
                                                                            $q->where('promotion_Date_From_WE', '<=', $check_in)
                                                                            ->where('promotion_Date_To_WE', '>=', $check_out);
                                                                        });
                                                                    });
                                                                })
                                                                ->first();
                                $promotion_Exist            = 'NO';
                                if(!empty($room_Promotions)){
                                    $promotion_Exist                                            = 'YES';
                                    $all_days                                                   = self::getAllDates_Promotion($request->check_in, $request->check_out);
                                }
                                // Promotions
                                
                                if (!empty($availible_from) && !empty($availible_to)) {
                                    $allowd_Rooms                                               = DB::table('allowed_Hotels_Rooms')->where('client_Id',$sub_customer_id->id)->where('room_Id',$room_res->id)->get();
                                    $startDate                                                  = new \DateTime($availible_from);
                                    $endDate                                                    = new \DateTime($availible_to);
                                    
                                    while ($startDate->format('Y-m-d') <= $endDate->format('Y-m-d')) {
                                        if ($room_res->price_week_type == 'for_all_days') {
                                            $daily_price                                        = $room_res->price_all_days ?? 0;
                                            if($promotion_Exist == 'YES'){
                                                $all_days_Promotions                            = self::getBetweenDates_Promotion($room_Promotions->promotion_Date_From, $room_Promotions->promotion_Date_To);
                                                $day_res                                        = $startDate->format('Y-m-d');
                                                foreach($all_days_Promotions as $val_PD){
                                                    if($day_res == $val_PD){
                                                        $daily_price                            = $room_Promotions->promotion_Rate ?? $room_Promotions->total_Rate ?? 0;
                                                    }
                                                }
                                            }else{
                                                if($allowd_Rooms->isEmpty()){
                                                    $daily_price                                = $room_res->price_all_days_wi_markup ?? $room_res->price_all_days ?? 0;
                                                }else{
                                                    $daily_price                                = $allowd_Rooms[0]->room_Sale_Price_AD ?? 0;
                                                }
                                            }
                                        } else if ($room_res->price_week_type == 'for_week_end') {
                                            $week_days                                          = !empty($room_res->weekdays) ? json_decode($room_res->weekdays, true) : [];
                                            $week_end_days                                      = !empty($room_res->weekends) ? json_decode($room_res->weekends, true) : [];
                                            $weekdays_price                                     = $room_res->weekdays_price ?? 0;
                                            $weekends_price                                     = $room_res->weekends_price ?? 0;
                                            if($promotion_Exist == 'YES'){
                                                $day                                            = $startDate->format('l');
                                                
                                                $all_days_Promotions_WD                         = self::getBetweenDates_Promotion($room_Promotions->promotion_Date_From_WD, $room_Promotions->promotion_Date_To_WD);
                                                $promotion_date_Found_WeekDays                  = false;
                                                $week_day_found                                 = false;
                                                foreach($all_days_Promotions_WD as $key_PD_WD => $val_PD_WD){
                                                    if($startDate->format('Y-m-d') == $val_PD_WD){
                                                        foreach($week_days as $week_day_res){
                                                            if($week_day_res == $day){
                                                                $week_day_found                 = true;
                                                                $promotion_date_Found_WeekDays  = true;
                                                                $at_Least_OP                    = true;
                                                                $daily_price                    = $room_Promotions->promotion_Rate_WD;
                                                                unset($all_days_Promotions_WD[$key_PD_WD]);
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                $all_days_Promotions_WE                         = self::getBetweenDates_Promotion($room_Promotions->promotion_Date_From_WE, $room_Promotions->promotion_Date_To_WE);
                                                $promotion_date_Found_WeekEnds                  = false;
                                                $week_end_day_found                             = false;
                                                foreach($all_days_Promotions_WE as $key_PD_WE => $val_PD_WE){
                                                    if($startDate->format('Y-m-d') == $val_PD_WE){
                                                        foreach($week_end_days as $week_end_days_res){
                                                            if($week_end_days_res == $day){
                                                                $week_end_day_found             = true;
                                                                $promotion_date_Found_WeekEnds  = true;
                                                                $at_Least_OP                    = true;
                                                                $daily_price                    = $room_Promotions->promotion_Rate_WE;
                                                                unset($all_days_Promotions_WE[$key_PD_WE]);
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                if($promotion_date_Found_WeekDays == false){
                                                    foreach($week_days as $week_day_res){
                                                        if($week_day_res == $day){
                                                            $week_day_found = true;
                                                        }
                                                    }
                                                }
                                                
                                                if($promotion_date_Found_WeekEnds == false){
                                                    foreach($week_end_days as $week_end_days_res){
                                                        if($week_end_days_res == $day){
                                                            $week_end_day_found = true;
                                                        }
                                                    }
                                                }
                                                
                                                if($week_day_found == true &&  $promotion_date_Found_WeekDays == false){
                                                    $daily_price = $room_Promotions->total_Rate_WD;
                                                }
                                                
                                                if($week_end_day_found == true && $promotion_date_Found_WeekEnds == false){
                                                    $daily_price = $room_Promotions->total_Rate_WE;
                                                }
                                            }else{
                                                $dayOfWeek                                      = $startDate->format('l');
                                                if (in_array($dayOfWeek, $week_days)) {
                                                    if($allowd_Rooms->isEmpty()){
                                                        $daily_price                            = $room_res->weekdays_price_wi_markup ?? $weekdays_price ?? 0;
                                                    }else{
                                                        $daily_price                            = $allowd_Rooms[0]->room_Sale_Price_WD ?? 0;
                                                    }
                                                } else {
                                                    if($allowd_Rooms->isEmpty()){
                                                        $daily_price                            = $room_res->weekends_price_wi_markup ?? $weekends_price ?? 0;
                                                    }else{
                                                        $daily_price                            = $allowd_Rooms[0]->room_Sale_Price_WE ?? 0;
                                                    }
                                                }
                                            }
                                        }
                                        
                                        $room_rates[]   = [
                                            'date'      => $startDate->format('Y-m-d'),
                                            'price'     => $daily_price
                                        ];
                                        $startDate->modify('+1 day');
                                    }
                                }
                                
                                if (!empty($room_rates)) {
                                    $all_Room_Details[$index]->rates_datewise = $room_rates;
                                }
                            }
                        }
                    }
                    
                    foreach ($all_Room_Details as $room) {
                        if (!empty($room->rates_datewise)) {
                            foreach ($room->rates_datewise as $rate) {
                                $date   = $rate['date'];
                                $price  = $rate['price'];
                                if (!isset($final_rates_datewise[$date]) || $price < $final_rates_datewise[$date]) {
                                    $final_rates_datewise[$date] = $price;
                                }
                            }
                        }
                    }
                    
                    // Convert associative array to indexed array format
                    $final_rates_datewise = array_map(function ($date, $price) {
                        return ['date' => $date, 'price' => $price];
                    }, array_keys($final_rates_datewise), $final_rates_datewise);
                    
                    // Sort by date (optional)
                    usort($final_rates_datewise, function ($a, $b) {
                        return strtotime($a['date']) - strtotime($b['date']);
                    });
                }
                
                // if($request->hotel_provider == 'hotel_beds'){
                    
                //     $data                   = array(
                //         'case'              => 'hotel_details',
                //         'hotel_beds_code'   => $request->hotel_id,
                //     );
                //     $curl                   = curl_init();
                //     curl_setopt_array($curl, array(
                //         //CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_hotel_details_by_hotelbeds',
                //         CURLOPT_URL => 'https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi_live.php',
                //         CURLOPT_RETURNTRANSFER => true,
                //         CURLOPT_ENCODING => '',
                //         CURLOPT_MAXREDIRS => 1,
                //         CURLOPT_TIMEOUT => 0,
                //         CURLOPT_FOLLOWLOCATION => true,
                //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //         CURLOPT_CUSTOMREQUEST => 'POST',
                //         CURLOPT_POSTFIELDS =>  $data,
                //         CURLOPT_HTTPHEADER => array(
                //             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                //         ),
                //     ));
                    
                //     $response                   = curl_exec($curl);
                //     $decode_Response            = json_decode($response);
                //     // return $decode_Response;
                //     $hotel_meta_data            = $decode_Response->hotel;
                //     // return $hotel_meta_data->rooms;
                    
                //     if(!empty($decode_Response->hotel)){
                //         foreach($decode_Response->hotel->rooms as $index => $room_availibilty_res){
                //             return $room_availibilty_res;
                //         }
                //     }
                // }
            }
            // Fetch room rates per date
        }
        
        $hotel_detials_generted_Obj     = (Object)[
            'rates_datewise'            => $final_rates_datewise ?? [],
        ];
        
        return response()->json([
            'status'                    => 'success',
            'hotel_details'             => $hotel_detials_generted_Obj
        ]);
    }
    
    public function hotels_checkout(Request $request){
        $hotel_request_data                 = json_decode($request->request_data);
        $selected_hotel                     = json_decode($request->selected_hotel);
        $selected_hotel_details             = json_decode($request->selected_hotel_details);
        $sub_customer_id                    = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        $markup                             = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)
                                                // ->orwhere('added_markup','synchtravel')
                                                ->orderBy('id','DESC')->get();
        $admin_travelenda_markup            = 0;
        $admin_hotel_bed_markup             = 0;
        $admin_custom_markup                = 0;
        $customer_markup                    = 0;
        $customer_custom_hotel_markup       = 0;
        $customer_custom_hotel_markup_type  = '';
        
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
            
            if($data->added_markup == 'Haramayn_hotels'){
                $customer_markup =  $data->markup_value;  
            }
        }
        
        $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id',$sub_customer_id->id)->where('status',1)->first();
        if($custom_hotel_markup){
            $customer_custom_hotel_markup = $custom_hotel_markup->markup_value;
            $customer_custom_hotel_markup_type = $custom_hotel_markup->markup;
        }
        
        // B2B Markup
        $markup_Find    = false;
        $user_Token     = $request->token;
        $b2b_Agent_Id   = $request->b2b_agent_id ?? '';
        $b2b_Markup     = DB::table('b2b_Agent_Markups')
                            ->where(function ($query) use ($user_Token, $b2b_Agent_Id) {
                                $query->where('token', $user_Token)
                                ->where('agent_Id', $b2b_Agent_Id)
                                ->where('markup_status',1);
                            })
                            ->orWhere(function ($query) use ($user_Token, $b2b_Agent_Id) {
                                $query->where('token', $user_Token)
                                ->where('agent_Id', 0)
                                ->where('markup_status',1);
                            })
                            ->get();
        // return $b2b_Markup;
        if(!empty($b2b_Markup) && $b2b_Markup != null){
            foreach($b2b_Markup as $val_BAM){
                if($val_BAM->agent_Id == $b2b_Agent_Id){
                    $customer_custom_hotel_markup_type  = $val_BAM->markup_Type; 
                    $customer_markup                    = $val_BAM->markup_Value;
                    $customer_custom_hotel_markup       = $val_BAM->markup_Value;
                    $markup_Find                        = true;
                }
            }
            
            if($markup_Find != true){
                foreach($b2b_Markup as $val_BAM){
                    if($val_BAM->agent_Id == 0){
                        $customer_custom_hotel_markup_type  = $val_BAM->markup_Type; 
                        $customer_markup                    = $val_BAM->markup_Value;
                        $customer_custom_hotel_markup       = $val_BAM->markup_Value;
                    }
                }
            }
        }
        // B2B Markup
        
        // return $selected_hotel;
        
        $checkout_Object = [];
        if(isset($selected_hotel->hotel_provider)){
            
            if($selected_hotel->hotel_provider == 'custom_hotel_provider'){
                $hotelbeds_select_room = $hotel_request_data->rooms_select_data;
                $hotelbeds_select_room = json_decode($hotelbeds_select_room);
                
                $roomRate                   = [];
                if(isset($hotelbeds_select_room)){
                    foreach ($hotelbeds_select_room as $room_res){
                        $room_Obj           = json_decode($room_res->room_rate_key);
                        $roomRate[]         = (Object)[
                            'rate_rateKey'  => $room_Obj->rateKey
                        ];       
                    }
                }
                
                $on_request                 = false;
                if(isset($selected_hotel)){
                    $hotel_data             = $selected_hotel;
                    $options_room           = [];
                    $total_price            = 0;
                    foreach($roomRate as $select_room_res){
                        if(isset($selected_hotel->rooms_options)){
                            $room_found = false;
                            foreach($selected_hotel->rooms_options as $room_res){
                                if(!$room_found){
                                    if($select_room_res->rate_rateKey == $room_res->booking_req_id){
                                        $total_price += $room_res->rooms_total_price;
                                        if($room_res->request_type == '1'){
                                            $on_request                 = true;
                                        }
                                        $options_room[]                 = (Object)[
                                            'booking_req_id'            => $room_res->booking_req_id,
                                            'rate_class'                => '',
                                            'room_name'                 => $room_res->room_name,
                                            'room_code'                 => $room_res->room_code,
                                            'request_type'              => $room_res->request_type,
                                            'board_id'                  => $room_res->board_id,
                                            'board_code'                => $room_res->board_code,
                                            'rooms_total_price'         => $room_res->rooms_total_price,
                                            'rooms_selling_price'       => $room_res->rooms_total_price,
                                            'rooms_qty'                 => $room_res->rooms_qty,
                                            'adults'                    => $room_res->adults,
                                            'childs'                    => $room_res->childs,
                                            'cancliation_policy_arr'    => [],
                                            'rooms_list'                => $room_res
                                        ];
                                        $room_found                     = true;
                                    }
                                }
                            }
                        }
                    }
                    
                    // Manage Markups 
                    $admin_custom_hotel_pro_markup              = 0;
                    $customer_markup                            = 0;
                    foreach($markup as $data){
                        if($data->added_markup == 'synchtravel'){
                            if($data->provider == $selected_hotel->custom_hotel_provider){
                                $admin_custom_hotel_pro_markup  = $data->markup_value;
                            }
                            
                            if($data->provider == 'All'){
                                $admin_custom_hotel_pro_markup  = $data->markup_value;
                            }
                        }
                        
                        if($data->added_markup == 'Haramayn_hotels'){
                            $customer_markup =  $data->markup_value;
                        }
                    }
                    
                    $checkout_Object            = (Object)[
                        'hotel_provider'        => 'custom_hotel_provider',
                        'custom_hotel_provider' => $selected_hotel->custom_hotel_provider,
                        'on_request'            => $on_request,
                        'admin_markup_type'     => $selected_hotel->admin_markup_type ?? 'Percentage',
                        'admin_markup'          => $selected_hotel->admin_markup ?? $admin_custom_hotel_pro_markup,
                        'customer_markup_type'  => $selected_hotel->customer_markup_type ?? 'Percentage',
                        'customer_markup'       => $selected_hotel->customer_markup ?? $customer_markup,
                        'hotel_id'              => $selected_hotel->hotel_id ?? $selected_hotel->hotel_code,
                        'hotel_name'            => $selected_hotel->hotel_name,
                        'checkIn'               => $selected_hotel_details->check_in,
                        'checkOut'              => $selected_hotel_details->check_out,
                        'stars_rating'          => $selected_hotel->stars_rating,
                        'destinationCode'       => $selected_hotel_details->hotel_address,
                        'destinationName'       => $selected_hotel_details->hotel_address,
                        'zoneCode'              => $selected_hotel_details->hotel_country,
                        'zoneName'              => $selected_hotel_details->hotel_city,
                        'latitude'              => $selected_hotel_details->lat ?? $selected_hotel_details->latitude ?? '',
                        'longitude'             => $selected_hotel_details->long ?? $selected_hotel_details->longitude ?? '',
                        'total_price'           => $total_price,
                        'currency'              => $selected_hotel->hotel_curreny,
                        'rooms_list'            => $options_room
                    ];
                    
                    return response()->json([
                        'status'        => 'success',
                        'hotels_data'   => $checkout_Object,
                    ]);
                }
                
                if($result_checkrates->error){
                    return response()->json([
                        'status'    => 'error',
                        'message'   => $result_checkrates->error->message,
                    ]);
                }
            }
            
            if($selected_hotel->hotel_provider == 'Custome_hotel'){
                $hotelbeds_select_room = $hotel_request_data->rooms_select_data;
                $hotelbeds_select_room = json_decode($hotelbeds_select_room);
                
                $roomRate = [];
                if(isset($hotelbeds_select_room)){
                    foreach ($hotelbeds_select_room as $room_res){
                        $room_Obj           = json_decode($room_res->room_rate_key);
                        // return $room_Obj;
                        $roomRate[]         = (Object)[
                            'index'         => $room_Obj->index,
                            'rate_rateKey'  => $room_Obj->rateKey,
                            'rooms_qty'     => $room_res->rooms_qty,
                        ];       
                    }
                }
                
                $on_request = false;
                if(isset($selected_hotel)){
                    $hotel_data     = $selected_hotel;
                    $options_room   = [];
                    $total_price    = 0;
                    foreach($roomRate as $select_room_res){
                        if(isset($selected_hotel->rooms_options)){
                            $room_found = false;
                            foreach($selected_hotel->rooms_options as $index => $room_res){
                                if(!$room_found){
                                    if($select_room_res->rate_rateKey == $room_res->booking_req_id && $select_room_res->index == $index){
                                        $total_price += $room_res->rooms_total_price;
                                        if($room_res->request_type == '1'){
                                            $on_request = true;
                                        }
                                        
                                        $room_qty_selected = $select_room_res->rooms_qty ?? 0;
                                        
                                        if($room_qty_selected == 0){
                                            if(isset($hotelbeds_select_room)){
                                                foreach ($hotelbeds_select_room as $keyIndex => $room_select_res){
                                                    $room_Obj = json_decode($room_select_res->room_rate_key);
                                                    if($room_Obj->rateKey == $room_res->booking_req_id){
                                                        $room_qty_selected = $room_select_res->rooms_qty;
                                                    }     
                                                }
                                            }
                                        }
                                        
                                        $options_room[] = (Object)[
                                            'booking_req_id'            => $room_res->booking_req_id,
                                            'allotment'                 => $room_res->allotment,
                                            'selected_qty'              => $room_qty_selected,
                                            'rate_class'                => '',
                                            'room_name'                 => $room_res->room_name,
                                            'room_code'                 => $room_res->room_code,
                                            'request_type'              => $room_res->request_type,
                                            'board_id'                  => $room_res->board_id,
                                            'board_code'                => $room_res->board_code,
                                            'room_Promotions'           => $room_res->room_Promotions,
                                            'room_Views'                => $room_res->room_view ?? '',
                                            'room_Promotions_Exist'     => $room_res->room_Promotions_Exist,
                                            'rooms_total_price'             => $room_res->rooms_total_price * $room_qty_selected,
                                            'rooms_selling_price'           => $room_res->rooms_total_price * $room_qty_selected,
                                            'rooms_selling_price_Promotion' => $room_res->rooms_selling_price_Promotion * $room_qty_selected,
                                            'rooms_total_price_Promotion'   => $room_res->rooms_total_price_Promotion * $room_qty_selected,
                                            'rooms_qty'                 => $room_res->rooms_qty,
                                            'adults'                    => $room_res->adults,
                                            'childs'                    => $room_res->childs,
                                            'cancliation_policy_arr'    => $room_res->cancliation_policy_arr ?? [],
                                            'rooms_list'                => $room_res->rooms_list,
                                            'merge_Rooms'               => $room_res->merge_Rooms ?? '',
                                        ];
                                        $room_found = true;
                                    }
                                }
                            }
                        }
                    }
                    
                    $checkout_Object = (Object)[
                        'hotel_provider'        => 'Custome_hotel',
                        'on_request'            => $on_request,
                        'admin_markup'          => $selected_hotel->admin_markup ?? $admin_custom_markup,
                        'customer_markup'       => $selected_hotel->customer_markup ?? $customer_custom_hotel_markup,
                        'admin_markup_type'     => $selected_hotel->admin_markup_type ?? 'Percentage',
                        'customer_markup_type'  => $selected_hotel->customer_markup_type ?? $customer_custom_hotel_markup_type,
                        'hotel_id'              => $selected_hotel->hotel_id ?? $selected_hotel->hotel_code,
                        'hotel_name'            => $selected_hotel->hotel_name,
                        'checkIn'               => $selected_hotel_details->check_in,
                        'checkOut'              => $selected_hotel_details->check_out,
                        'stars_rating'          => $selected_hotel->stars_rating,
                        'destinationCode'       => $selected_hotel_details->hotel_address,
                        'destinationName'       => $selected_hotel_details->hotel_address,
                        'zoneCode'              => $selected_hotel_details->hotel_country,
                        'zoneName'              => $selected_hotel_details->hotel_city,
                        // 'latitude'              => $selected_hotel_details->latitude ?? '',
                        // 'longitude'             => $selected_hotel_details->longitude ?? '',
                        'latitude'              => $selected_hotel_details->lat ?? $selected_hotel_details->latitude ?? '',
                        'longitude'             => $selected_hotel_details->long ?? $selected_hotel_details->longitude ?? '',
                        'total_price'           => $total_price,
                        'currency'              => $selected_hotel->hotel_curreny,
                        'rooms_list'            => $options_room,
                        'client_Id'             => $selected_hotel->client_Id ?? '',
                    ];
                    
                    return response()->json([
                        'status'        => 'success',
                        'hotels_data'   => $checkout_Object,
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
                    
                    // return($hotelbeds_select_room);
                    
                    $roomRate = [];
                    if(isset($hotelbeds_select_room)){
                        foreach ($hotelbeds_select_room as $room_res){
                                // print_r($room_res);
                                // die;
                                $room_Obj = json_decode($room_res->room_rate_key);
                                 
                                $roomRate[] = (Object)[
                                        'rate_rateKey' => $room_Obj->rateKey
                                    ];       
                        }
                    }
                   
                    $roomRate = json_encode($roomRate);
                    
                    // return $roomRate;
                  
                    function hotelbedsgetBooking($roomRate){
                        $url = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi_live.php";
                        $data = array('case' => 'getBookingMultipleRooms', 'roomRate' => $roomRate);
                        // return($data);
                        Session::put('hotelbeds_search_request',json_encode($data));
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $responseData = curl_exec($ch);
                        // return $responseData;
                        if (curl_errno($ch)) {
                            return curl_error($ch);
                        }
                        curl_close($ch);
                        return $responseData;
                    }
                  
                    $hotel_response = hotelbedsgetBooking($roomRate);
                    
                    // return $hotel_response;
                    
                    $result_checkrates = json_decode($hotel_response);
                    
                    if(isset($result_checkrates->hotel)){
                        $hotel_data = $result_checkrates->hotel;
                        
                        // echo $result_checkrates;die;
                      
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
                                                        'type'      => $cancel_res->type ?? 'Fix Amount',
                                                        'amount'    => $cancel_res->amount,
                                                        'from_date' => $cancel_res->from,
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
                            'hotel_provider'        => 'hotel_beds',
                            'on_request'            => false,
                            'admin_markup'          => $selected_hotel->admin_markup ?? $admin_hotel_bed_markup,
                            'customer_markup'       => $selected_hotel->customer_markup ?? $customer_markup,
                            'admin_markup_type'     => $selected_hotel->admin_markup_type ?? 'Percentage',
                            'customer_markup_type'  => $selected_hotel->customer_markup_type ?? 'Percentage',
                            'hotel_id' => $hotel_data->hotel_id ?? $hotel_data->code,
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
                            'status'        => 'success',
                            'hotels_data'   => $checkout_Object,
                        ]);
                    }
                    
                    // return $result_checkrates->error;
                    
                    if(isset($result_checkrates->error)){
                        return response()->json([
                            'status'    => 'error',
                            'message'   => $result_checkrates->error->message,
                        ]);
                    }
                  
                //   print_r($checkout_Object);
            }
            
            if($selected_hotel->hotel_provider == 'travelenda'){
                // return $hotel_request_data;
                
                $travelenda_select_rooms    = json_decode($hotel_request_data->rooms_select_data);
                $roomRate                   = [];
                if(isset($travelenda_select_rooms)){
                    $room_Obj               = json_decode($travelenda_select_rooms[0]->room_rate_key);
                    $roomRate[]             = (Object)[
                        'OptionId'          => $room_Obj->rateKey
                    ];
                    
                    // foreach ($travelenda_select_rooms as $room_res){
                    //     $room_Obj           = json_decode($room_res->room_rate_key);
                    //     $roomRate[]         = (Object)[
                    //         'OptionId'      => $room_Obj->rateKey
                    //     ];       
                    // }
                }
                
                // return $roomRate;
                
                function travelandapreBooking($roomRate){
                    $url    = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                    $data   = array('case' => 'travelandapreBooking', 'roomRate' => json_encode($roomRate)); 
                    $ch     = curl_init();
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
                
                $responseData3          = travelandapreBooking($roomRate);
                // return $responseData3;
                $result_HotelPolicies   = json_decode($responseData3);
                
                // return $result_HotelPolicies->Body->Policies->Policy;
                
            if(isset($result_HotelPolicies->Body->OptionId)){
                $options_room = [];
                if(isset($selected_hotel->rooms_options)){
                        foreach($selected_hotel->rooms_options as $room_res){
                            if($room_res->booking_req_id == $result_HotelPolicies->Body->OptionId){
                                // Rooms Cancilation Policy
                                $cancliation_policy_arr             = [];
                                if(isset($result_HotelPolicies->Body->Policies->Policy)){
                                    foreach($result_HotelPolicies->Body->Policies->Policy as $cancel_res){
                                        $cancel_tiem                = (Object)[
                                            'CancellationDeadline'  => $result_HotelPolicies->Body->CancellationDeadline ?? '',
                                            'amount'                => $cancel_res->Value ?? '',
                                            'type'                  => $cancel_res->Type ?? '',
                                            'from_date'             => $cancel_res->From ?? '',
                                        ];
                                        $cancliation_policy_arr[]    = $cancel_tiem;
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
                                    'booking_req_id'            => $result_HotelPolicies->Body->OptionId,
                                    'allotment'                 => $room_res->allotment,
                                    'selected_qty'              => $room_qty_selected,
                                    'rate_class'                => '',
                                    'room_name'                 => $room_res->room_name,
                                    'room_code'                 => $room_res->room_code,
                                    'request_type'              => $room_res->request_type,
                                    'board_id'                  => $room_res->board_id,
                                    'board_code'                => $room_res->board_code,
                                    'rooms_total_price'         => $room_res->rooms_total_price,
                                    'rooms_selling_price'       => $room_res->rooms_total_price,
                                    'rooms_qty'                 => $room_res->rooms_qty,
                                    'adults'                    => $room_res->adults,
                                    'childs'                    => $room_res->childs,
                                    'cancliation_policy_arr'    => $cancliation_policy_arr,
                                    'rooms_list'                => $room_res->rooms_list
                                ];
                            }
                        }
                    }
                  
                    $checkout_Object = (Object)[
                        'hotel_provider'        => 'travelenda',
                        'on_request'            => false,
                        'admin_markup'          => $selected_hotel->admin_markup ?? $admin_travelenda_markup,
                        'customer_markup'       => $selected_hotel->customer_markup ?? $customer_markup,
                        'admin_markup_type'     => $selected_hotel->admin_markup_type ?? 'Percentage',
                        'customer_markup_type'  => $selected_hotel->customer_markup_type ?? 'Percentage',
                        'hotel_id'              => $selected_hotel->hotel_id ?? $selected_hotel->hotel_code,
                        'hotel_name'            => $selected_hotel->hotel_name,
                        'checkIn'               => $selected_hotel_details->check_in,
                        'checkOut'              => $selected_hotel_details->check_out,
                        'stars_rating'          => $selected_hotel->stars_rating,
                        'destinationCode'       => $selected_hotel_details->hotel_address,
                        'destinationName'       => $selected_hotel_details->hotel_address,
                        'zoneCode'              => $selected_hotel_details->hotel_country,
                        'zoneName'              => $selected_hotel_details->hotel_city,
                        'latitude'              => $selected_hotel_details->latitude,
                        'longitude'             => $selected_hotel_details->longitude,
                        'total_price'           => $result_HotelPolicies->Body->TotalPrice,
                        'currency'              => $result_HotelPolicies->Body->Currency,
                        'rooms_list'            => $options_room
                    ];
                    
                    return response()->json([
                        'status'        => 'success',
                        'hotels_data'   => $checkout_Object,
                    ]);
                }
                
                if(isset($result_HotelPolicies->Body->Error)){
                    if($result_HotelPolicies->Body->Error->ErrorId == '117'){
                        return response()->json([
                            'status'    => 'error',
                            'type'      => 'redirect',
                            'message'   => 'Your Request Time out',
                        ]);
                    } 
                }
            }
            
            if($selected_hotel->hotel_provider == 'Stuba'){
                $stuba_select_rooms = json_decode($hotel_request_data->rooms_select_data);
                $roomRate = [];
                
                if (isset($stuba_select_rooms)) {
                    foreach ($stuba_select_rooms as $room_res) {
                        $room_Obj = json_decode($room_res->room_rate_key);
                        $roomRate[] = (object)[
                            'OptionId' => $room_Obj->rateKey
                        ];
                    }
                }
                
                if (is_array($roomRate)) {
                    $id = $roomRate[0]->OptionId;
                    $filtered_rooms_options = array_filter($selected_hotel->rooms_options, function ($room) use ($id) {
                        return $room->booking_req_id === $id;
                    });
                    
                    $total_price = 0;
                    foreach ($filtered_rooms_options as $room) {
                        $total_price            += floatval($room->rooms_total_price);
                        $room->selected_qty     = $room->rooms_qty;
                        $room->rate_class       = "NOR";
                        
                        $cancliation_policy_arr = [];
                        $response               = Stuba_Controller::stuba_Booking_Prepare($room->booking_req_id);
                        // return $response;
                        if(isset($response['Booking'])){
                            if(isset($response['Booking']['HotelBooking']['Room'])){
                                $Hotel_Rooms                    = $response['Booking']['HotelBooking']['Room'];
                                if(isset($Hotel_Rooms['CanxFees'])){
                                    $CanxFees                   = $Hotel_Rooms['CanxFees'];
                                    if(isset($CanxFees['Fee'])){
                                        $Fee                    = $CanxFees['Fee'];
                                        if(isset($Fee['@attributes'])){
                                            $cancel_tiem        = (Object)[
                                                'amount'        => $Fee['Amount']['@attributes']['amt'] ?? '',
                                                'type'          => $Fee['Amount']['@attributes']['type'] ?? 'Fix Amount',
                                                'from_date'     => $Fee['@attributes']['from'] ?? '',
                                            ];
                                            $cancliation_policy_arr[]    = $cancel_tiem;
                                        }else{
                                            if(is_array($Fee) && count($Fee) > 0){
                                                foreach($Fee as $val_F){
                                                    $cancel_tiem        = (Object)[
                                                        'amount'        => $val_F['Amount']['@attributes']['amt'] ?? '',
                                                        'type'          => $val_F['Amount']['@attributes']['type'] ?? 'Fix Amount',
                                                        'from_date'     => $val_F['@attributes']['from'] ?? '',
                                                    ];
                                                    $cancliation_policy_arr[]    = $cancel_tiem;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $room->cancliation_policy_arr = $cancliation_policy_arr;
                        // Cancellation Policy
                    }
                    
                    $result = [
                        'hotel_provider'        => $selected_hotel->hotel_provider,
                        'on_request'            => false,
                        'admin_markup'          => $selected_hotel->admin_markup,
                        'customer_markup'       => $selected_hotel->customer_markup,
                        'admin_markup_type'     => $selected_hotel->admin_markup_type,
                        'customer_markup_type'  => $selected_hotel->customer_markup_type,
                        'hotel_id'              => $selected_hotel->hotel_id ?? $selected_hotel->hotel_code,
                        'hotel_name'            => $selected_hotel->hotel_name,
                        'checkIn'               => $selected_hotel_details->check_in,
                        'checkOut'              => $selected_hotel_details->check_out,
                        'stars_rating'          => $selected_hotel->stars_rating,
                        'destinationCode'       => '',
                        'destinationName'       => $selected_hotel_details->hotel_country,
                        'zoneCode'              => '',
                        'zoneName'              => '',
                        'latitude'              => $selected_hotel_details->latitude,
                        'longitude'             => $selected_hotel_details->longitude,
                        'total_price'           => $total_price,
                        'currency'              => $selected_hotel->hotel_curreny,
                        'rooms_list'            => array_values($filtered_rooms_options)
                    ];
                    
                    $result_json            = json_encode($result, JSON_PRETTY_PRINT);
                    $output                 = [];
                    $output['status']       = "Success";
                    $output['hotels_data']  = $result;
                    // return  $output;
                    return response()->json([
                        'status'            => 'success',
                        'hotels_data'       => $result,
                    ]);
                } else {
                    $output = [];
                    $output['status']       = "error";
                    $output['message']      = "Error";
                    // return  $output;
                    return response()->json([
                        'status'            => 'error',
                        'hotels_data'       => [],
                    ]);
                }
            }
            
            if($selected_hotel->hotel_provider == 'Sunhotel'){
                $sunHotel_select_room   = json_decode($hotel_request_data->rooms_select_data);
                // $sunHotel_select_room   = $hotel_request_data->rooms_select_data;
                $roomRate               = [];
                if (isset($sunHotel_select_room)) {
                    foreach ($sunHotel_select_room as $room_res) {
                        $room_Obj           = json_decode($room_res->room_rate_key);
                        // $room_Obj           = $room_res->room_rate_key;
                        $roomRate[]         = (object)[
                            'rate_rateKey'  => $room_Obj->rateKey
                        ];
                    }
                }
                
                foreach ($selected_hotel->rooms_options as $room) {
                    if($roomRate[0]->rate_rateKey == $room->booking_req_id){
                        $rooms_options[] = $room;
                    }
                }
                
                if(!isset($rooms_options)){
                    $rooms_options      = $selected_hotel->rooms_options;
                }
                
                // return $rooms_options;
                
                if (count($roomRate) > 0) {
                    for ($r_c = 0; $r_c < count($roomRate); $r_c++) {
                        // return $rooms_options;
                        $pre_Book_Arr           = [
                            'language'          => 'en',
                            'currency'          => $selected_hotel->hotel_curreny ?? '',
                            'checkInDate'       => $selected_hotel_details->check_in,
                            'checkOutDate'      => $selected_hotel_details->check_out,
                            'roomId'            => $rooms_options[0]->booking_req_id,
                            'infant'            => $rooms_options[0]->infant ?? '0',
                            'mealId'            => $rooms_options[0]->meal_ID,
                            'CustomerCountry'   => 'gb',
                            'B2C'               => 0,
                            'searchPrice'       => $rooms_options[0]->rooms_total_price,
                        ];
                        
                        $rooms_qty          = 0;
                        $adults             = 0;
                        $childs             = 0;
                        $child_Ages         = [];
                        $room_Details_Arr   = [];
                        if(count($rooms_options) > 1){
                            for($rd=0; $rd<count($rooms_options); $rd++){
                                $rooms_qty  += $rooms_options[$rd]->rooms_qty;
                                $adults     += $rooms_options[$rd]->adults;
                                $childs     += $rooms_options[$rd]->childs;
                                array_push($child_Ages,$rooms_options[$rd]->child_Ages);
                            }
                            
                            $room_Details_Arr   = [
                                'rooms'         => $rooms_qty,
                                'adults'        => $adults,
                                'children'      => $childs,
                                'childrenAges'  => $child_Ages,
                            ];
                        }else{
                            $room_Details_Arr   = [
                                'rooms'         => $rooms_options[0]->rooms_qty,
                                'adults'        => $rooms_options[0]->adults,
                                'children'      => $rooms_options[0]->childs,
                                'childrenAges'  => $rooms_options[0]->child_Ages ?? '',
                            ];
                        }
                        
                        $pre_Book_Arr = array_merge($pre_Book_Arr,$room_Details_Arr);
                        
                        // return $pre_Book_Arr;
                        
                        $tboholidays_PreBook    = SunHotel_Controller::sunHotelPreBook($pre_Book_Arr);
                        // return $tboholidays_PreBook;
                        if(isset($tboholidays_PreBook['PreBookCode'])){
                            $sunHotel_Hotel_Details     = SunHotel_Controller::sunHotelDetails($selected_hotel->hotel_id);
                            $checkout_Object            = (object)[
                                'hotel_provider'        => 'Sunhotel',
                                'on_request'            => false,
                                'admin_markup'          => $selected_hotel->admin_markup ?? $admin_hotel_bed_markup,
                                'customer_markup'       => $selected_hotel->customer_markup ?? $customer_markup,
                                'admin_markup_type'     => $selected_hotel->admin_markup_type ?? 'Percentage',
                                'customer_markup_type'  => $selected_hotel->customer_markup_type ?? 'Percentage',
                                'hotel_id'              => $selected_hotel->hotel_id ?? $selected_hotel->hotel_code,
                                'hotel_name'            => $selected_hotel->hotel_name,
                                'checkIn'               => $selected_hotel_details->check_in,
                                'checkOut'              => $selected_hotel_details->check_out,
                                'stars_rating'          => $selected_hotel->stars_rating,
                                'destinationCode'       => $sunHotel_Hotel_Details['hotels']['hotel']['hotel.addr.countrycode'],
                                'destinationName'       => $sunHotel_Hotel_Details['hotels']['hotel']['hotel.addr.city'],
                                'zoneCode'              => $hotel_data->zoneCode ?? '',
                                'zoneName'              => $hotel_data->zoneName ?? '',
                                'latitude'              => $selected_hotel_details->latitude,
                                'longitude'             => $selected_hotel_details->longitude,
                                'total_price'           => $rooms_options[0]->rooms_total_price,
                                'currency'              => $selected_hotel->hotel_curreny ?? '',
                                'rooms_list'            => $rooms_options
                            ];
                            // return $checkout_Object;
                            
                            return response()->json([
                                'status'        => 'success',
                                'hotels_data'   => $checkout_Object,
                            ]);
                        }else{
                            return response()->json([
                                'status'        => 'error',
                                'hotels_data'   => [],
                            ]);
                        }
                    }
                }
            }
        }
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
        // if($request->token == config('token_UmrahShop')){
        //     // return $request;
        //     $invoiceId          = 'AL111111';
        //     $status_RB          = 'Confirmed';
        //     $check_Mail         = self::MailSend($request,$invoiceId,$status_RB);
        //     return $check_Mail;
        // }
        
        // return 'stop';
        
        $hotel_request_data         = json_decode($request->request_data);
        // return $hotel_request_data;
        $hotel_checkout_select      = json_decode($request->hotel_checkout_select);
        // return $hotel_checkout_select;
        $customer_search_data       = json_decode($request->customer_search_data);
        // return $customer_search_data;
        $userData                   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status','hotel_Booking_Tag')->first();
        $booking_customer_id        = '';
        
        $randomNumber               = random_int(1000000, 9999999);
        if(isset($userData->hotel_Booking_Tag) && $userData->hotel_Booking_Tag != null && $userData->hotel_Booking_Tag != ''){
            $invoiceId              = $userData->hotel_Booking_Tag.$randomNumber;
        }else{
            $invoiceId              = $randomNumber;
        }
        
        // return $invoiceId;
        
        $customer_exist = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$hotel_request_data->lead_email)->first();
        if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
            $booking_customer_id = $customer_exist->id;
        }else{
           
            if($hotel_request_data->lead_title == "Mr"){
                $gender  = 'male';
            }else{
                $gender = 'female';
            }
            
            $password = Hash::make('admin123');
            if(!isset($request->booking_type) || $request->booking_type == 'b2c'){
                $customer_detail                    = new booking_customers();
                $customer_detail->name              = $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name;
                $customer_detail->opening_balance   = 0;
                $customer_detail->balance           = 0;
                $customer_detail->email             = $hotel_request_data->lead_email;
                $customer_detail->password          = $password;
                $customer_detail->phone             = $hotel_request_data->lead_phone;
                $customer_detail->gender            = $gender;
                $customer_detail->country           = $hotel_request_data->lead_country;
                $customer_detail->customer_id       = $userData->id;
                $result                             = $customer_detail->save();
                
                $booking_customer_id                = $customer_detail->id;
            }
        }
        
        // return $hotel_checkout_select->rooms_list;;
        
        // return 'STOP';
        
        // ************************************************************
        // Custom Hotel Reservation
        // ************************************************************
        if($hotel_checkout_select->hotel_provider  == 'Custome_hotel'){
            DB::beginTransaction();
            try {
                if(isset($hotel_checkout_select->rooms_list)){
                    // return $hotel_checkout_select->rooms_list;
                    
                    foreach($hotel_checkout_select->rooms_list as $room_res){
                        if($room_res->request_type == '0' || $room_res->request_type == ''){
                            $room_data = DB::table('rooms')->where('id',$room_res->booking_req_id)->first();
                            if($room_data){
                                // Update Room Data
                                $rooms_qty      = $room_res->selected_qty;
                                $total_booked   = $room_data->booked + $rooms_qty;
                                DB::table('rooms_bookings_details')->insert([
                                    'room_Promotions'           => json_encode($room_res->room_Promotions),
                                    'room_Promotions_Exist'     => $room_res->room_Promotions_Exist,
                                    'room_id'                   => $room_res->booking_req_id,
                                    'booking_from'              => 'website',
                                    'quantity'                  => $rooms_qty,
                                    'booking_id'                => $invoiceId,
                                    'date'                      => date('Y-m-d'),
                                    'check_in'                  => $hotel_checkout_select->checkIn,
                                    'check_out'                 => $hotel_checkout_select->checkOut,
                                ]);
                                
                                DB::table('rooms')->where('id',$room_res->booking_req_id)->update(['booked'=>$total_booked]);
                                
                                // Update Hotel Supplier Balance
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
                                
                                if(isset($supplier_data)){
                                    // echo "Enter hre ";
                                    $total_price = $room_res->rooms_total_price;
                                   
                                    // echo "The supplier Balance is ".$supplier_data->balance;
                                    $supplier_balance           = $supplier_data->balance;
                                    $supplier_payable_balance   = $supplier_data->payable + $total_price;
                                    
                                    // update Agent Balance
                                    DB::table('hotel_supplier_ledger')->insert([
                                        'supplier_id'           => $supplier_data->id,
                                        'payment'               => $total_price,
                                        'balance'               => $supplier_balance,
                                        'payable_balance'       => $supplier_payable_balance,
                                        'room_id'               => $room_data->id,
                                        'customer_id'           => $userData->id,
                                        'date'                  => date('Y-m-d'),
                                        'website_booking_id'    => $invoiceId,
                                        'available_from'        => $hotel_checkout_select->checkIn,
                                        'available_to'          => $hotel_checkout_select->checkOut,
                                        'room_quantity'         => $room_res->selected_qty,
                                    ]);
                                    
                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                        'balance'               => $supplier_balance,
                                        'payable'               => $supplier_payable_balance
                                    ]);               
                                }
                            }
                        }
                    }
                    
                    $room_book_status           = 'Tentative';
                    
                    if($request->slc_pyment_method == 'Bank_Payment' || $request->slc_pyment_method == 'membership'){
                        $room_book_status   = 'Tentative';
                    }else{
                        if(isset($hotel_checkout_select->rooms_list[0])){
                            if($hotel_checkout_select->rooms_list[0]->request_type == '0' || empty($hotel_checkout_select->rooms_list[0]->request_type)){
                                $room_book_status   = 'Confirmed';
                            }else{
                                $room_book_status   = 'Room On Request';
                            }
                        }
                    }
                    
                    $rooms_details_arr          = [];
                    $merge_Rooms                = [];
                    if(isset($hotel_checkout_select->rooms_list)){
                        foreach($hotel_checkout_select->rooms_list as $room_res){
                            $room_rate_arr              = [];
                            $cancliation_policy_arr     = $room_res->cancliation_policy_arr;
                            $room_rate_arr[]            = (Object)[
                                'rateClass'             => '',
                                'net'                   => $room_res->rooms_total_price,
                                'rateComments'          => '',
                                'room_board'            => $room_res->board_id,
                                'room_qty'              => $room_res->selected_qty,
                                'adults'                => $room_res->adults,
                                'children'              => $room_res->childs,
                                'cancellation_policy'   => $cancliation_policy_arr,
                            ];
                            
                            $rooms_details_arr[]        = (Object)[
                                'room_stutus'           => $room_book_status,
                                'room_code'             => $room_res->booking_req_id,
                                'room_name'             => $room_res->room_name ?? $room_res->board_id,
                                'room_paxes'            => [],
                                'adults'                => $room_res->adults,
                                'childs'                => $room_res->childs,
                                'room_rates'            => $room_rate_arr,
                            ];
                            
                            if(isset($room_res->merge_Rooms) && $room_res->merge_Rooms != null && $room_res->merge_Rooms != ''){
                                $final_Merge_Rooms      = $room_res->merge_Rooms;
                                foreach($final_Merge_Rooms as $val_FRL){
                                    $merge_Rooms[]      = (Object)[
                                        'room_stutus'   => $room_book_status,
                                        'room_code'     => $val_FRL->id,
                                        'room_name'     => $val_FRL->room_type_name,
                                        'room_paxes'    => [],
                                        'adults'        => $room_res->adults,
                                        'childs'        => $room_res->childs,
                                        'room_rates'    => $room_rate_arr,
                                    ];
                                }
                            }
                        }  
                    }
                  
                    $hotel_checkout_request_d   = json_decode($request->hotel_checkout_select);
                    $hotel_booking_conf_res     = (Object)[
                        'provider'              => 'Custome_hotel',
                        'reference_no'          => $invoiceId,
                        'admin_markup'          => $hotel_checkout_request_d->admin_markup,
                        'customer_markup'       => $hotel_checkout_request_d->customer_markup,
                        'admin_markup_type'     => $hotel_checkout_request_d->admin_markup_type,
                        'customer_markup_type'  => $hotel_checkout_request_d->customer_markup_type,
                        'total_price'           => $hotel_checkout_select->total_price,
                        'hotel_currency'        => $hotel_checkout_select->currency,
                        'clientReference'       => '',
                        'creationDate'          => date('Y-m-d'),
                        'status'                => $room_book_status,
                        'lead_passenger'        => $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name,
                        'hotel_details'         => (Object)[
                            'checkIn'           => $hotel_checkout_select->checkIn,
                            'checkOut'          => $hotel_checkout_select->checkOut,
                            'hotel_code'        => $hotel_checkout_select->hotel_id,
                            'hotel_name'        => $hotel_checkout_select->hotel_name,
                            'stars_rating'      => $hotel_checkout_select->stars_rating,
                            'destinationCode'   => $hotel_checkout_select->destinationCode,
                            'zoneName'          => $hotel_checkout_select->zoneName ?? '',
                            'latitude'          => $hotel_checkout_select->latitude,
                            'longitude'         => $hotel_checkout_select->longitude,
                            'rooms'             => $rooms_details_arr,
                            'merge_Rooms'       => $merge_Rooms,
                        ]
                    ];
                    // return $hotel_booking_conf_res;
                    $invoiceId                  = $invoiceId;
                    $customer_id                = '';
                    $userData                   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                    if($userData){
                        $customer_id            = $userData->id;
                    }
                    
                    $lead_passenger_object = (Object)[
                        'lead_title'            => $hotel_request_data->lead_title,
                        'lead_first_name'       => $hotel_request_data->lead_first_name,
                        'lead_last_name'        => $hotel_request_data->lead_last_name,
                        'lead_date_of_birth'    => date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                        'lead_phone'            => $hotel_request_data->lead_phone,
                        'lead_email'            => $hotel_request_data->lead_email,
                        'lead_country'          => $hotel_request_data->lead_country, 
                    ];
                    
                    $others_adults = [];
                    
                    if(isset($hotel_request_data->other_title)){
                        foreach($hotel_request_data->other_title as $index => $other_res){
                            $others_adults[] = (Object)[
                                'title'         => $other_res,
                                'name'          => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                'nationality'   => $hotel_request_data->other_nationality[$index],
                            ];
                        }
                    }
                    
                    $childs = [];
                    if(isset($hotel_request_data->child_title)){
                        foreach($hotel_request_data->child_title as $index => $other_res){
                            $childs[] = (Object)[
                                'title'         => $other_res,
                                'name'          => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                'nationality'   => $hotel_request_data->child_nationality[$index],
                            ];
                        }
                    }
                    
                    // Hasanat Credits Working
                    // $b2b_Agent_Id = $request->b2b_agent_id ?? '254';
                    if(config('token_Alif') == $request->token){
                        if($request->slc_pyment_method == 'membership'){
                            $b2b_agents             = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->b2b_agent_id)->first();
                            // return $b2b_agents;
                            DB::table('custom_Booking_Hasanat_Credits')->insert([
                                'token'             => $request->token,
                                'customer_id'       => $userData->id,
                                'booking_Id'        => $invoiceId,
                                'b2b_Agent_Id'      => $request->b2b_agent_id,
                                'package_Id'        => $b2b_agents->select_Package,
                                'booked_Credits'    => $request->booked_Credits,
                            ]);
                            
                            // $booked_Credits     = $request->booked_Credits ?? '1';
                            $remaining_Credits  = $b2b_agents->total_Hasanat_Credits - $request->booked_Credits;
                            DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->b2b_agent_id)->update(['total_Hasanat_Credits'=>$remaining_Credits]);
                        }
                    }
                    // Hasanat Credits Working
                    
                    $result                             = DB::table('hotels_bookings')->insert([
                        'invoice_no'                    => $invoiceId,
                        'booking_customer_id'           => $booking_customer_id,
                        'provider'                      => $hotel_booking_conf_res->provider,
                        'client_Id'                     => $hotel_checkout_select->client_Id ?? '',
                        'exchange_currency'             => $request->exchange_currency,
                        'exchange_price'                => $request->exchange_price,
                        'base_exchange_rate'            => $request->base_exchange_rate,
                        'base_currency'                 => $request->base_currency,
                        'selected_exchange_rate'        => $request->selected_exchange_rate,
                        'selected_currency'             => $request->selected_currency,
                        'GBP_currency'                  => $request->admin_exchange_currency,
                        'GBP_exchange_rate'             => $request->admin_exchange_rate,
                        'GBP_invoice_price'             => $request->admin_exchange_total_markup_price,
                        'creationDate'                  => date('Y-m-d'),
                        'status'                        => $room_book_status,
                        'lead_passenger'                => $hotel_booking_conf_res->lead_passenger,
                        'lead_passenger_data'           => json_encode($lead_passenger_object),
                        'other_adults_data'             => json_encode($others_adults),
                        'childs_data'                   => json_encode($childs),
                        'total_adults'                  => $customer_search_data->adult_searching,
                        'total_childs'                  => $customer_search_data->child_searching,
                        'total_rooms'                   => $customer_search_data->room_searching,
                        'reservation_request'           => json_encode($request->all()),
                        'reservation_response'          => json_encode($hotel_booking_conf_res),
                        'actual_reservation_response'   => '',
                        'customer_id'                   => $customer_id,
                        'booking_type'                  => $request->booking_type,
                        'payment_details'               => $request->payment_details,
                        'payment_Type'                  => $request->slc_pyment_method ?? NULL,
                        'meal_Details'                  => $request->meal_Details ?? '',
                        'b2b_agent_id'                  => $request->b2b_agent_id ?? $customer_search_data->b2b_agent_id ?? null,
                    ]);
                    
                    // B2B Credit Lmit
                    $b2b_agent_id                       = $request->b2b_agent_id ?? $customer_search_data->b2b_agent_id ?? null;
                    if(isset($request->slc_pyment_method) && $request->slc_pyment_method == 'Credit_Payment' && $b2b_agent_id != null){
                        // $total_Amount                   = DB::table('b2b_Agent_Credit_Limits')->where('token',$request->token)->where('b2b_Agent_Id',$b2b_agent_id)->max('total_Amount');
                        $total_Amount                   = DB::table('b2b_Agent_Credit_Limits')->where('token',$request->token)->where('b2b_Agent_Id',$b2b_agent_id)->where('status','Credit_Payment')->orderBy('id', 'DESC')->get();
                        $booking_Amount                 = DB::table('b2b_Agent_Credit_Limits')->where('token',$request->token)->where('b2b_Agent_Id',$b2b_agent_id)->where('status','Booking')->sum('booking_Amount');
                        if($booking_Amount > 0){
                            $total_Booking_Amount       = $booking_Amount + $request->exchange_price;
                            $remaining_Amount           = $total_Amount[0]->total_Amount - $total_Booking_Amount;
                        }else{
                            $remaining_Amount           = $total_Amount[0]->total_Amount - $request->exchange_price;
                        }
                        
                        DB::table('b2b_Agent_Credit_Limits')->insert([
                            'token'                     => $request->token,
                            'b2b_Agent_Id'              => $b2b_agent_id,
                            'customer_Id'               => $userData->id,
                            'transaction_Id'            => $invoiceId,
                            'booking_Amount'            => $request->exchange_price,
                            'total_Amount'              => $total_Amount[0]->total_Amount,
                            'remaining_Amount'          => $remaining_Amount,
                            'currency'                  => $userData->currency_symbol ?? $userData->currency_value ?? 'SAR',
                            'status'                    => 'Booking',
                            'status_Type'               => 'Approved',
                            'payment_Method'            => 'Bank Transfer',
                            'payment_Remarks'           => NULL,
                            'services_Type'             => 'Hotel',
                        ]);
                    }
                    // B2B Credit Lmit
                    
                    if($room_book_status == 'Confirmed' || $room_book_status == 'Tentative'){
                        $client_markup                          = $request->client_markup;
                        $admin_markup                           = $request->admin_markup;
                        $client_markup_type                     = $request->client_markup_type;
                        $admin_markup_type                      = $request->admin_markup_type;
                        $payable_price                          = $request->admin_commission_amount_orignial;
                        $client_commission_amount               = $request->client_commission_amount;
                        $total_markup_price                     = $request->total_markup_price;
                        $currency                               = $request->currency;
                        $exchange_client_commission_amount      = $request->exchange_client_commission_amount;                                         
                        $exchange_payable_price                 = $request->exchange_admin_commission_amount;                                          
                        $exchange_admin_commission_amount       = $request->exchange_admin_commission_amount; 
                        $exchange_total_markup_price            = $request->exchange_total_markup_price; 
                        $exchange_currency                      = $request->exchange_currency; 
                        $exchange_rate                          = $request->exchange_rate; 
                        $admin_exchange_amount                  = $request->admin_commission_amount;
                        $admin_commission_amount                = $request->admin_commission_amount;
                        $admin_exchange_currency                = $request->admin_exchange_currency;
                        $admin_exchange_rate                    = $request->admin_exchange_rate;
                        $admin_exchange_total_markup_price      = $request->admin_exchange_total_markup_price;
                        $price                                  = $request->exchange_price;
                        $p_price                                = json_decode($price);
                        $get_hotel_customer_ledgers             = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                        $sum_hotel_customer_ledgers             = $get_hotel_customer_ledgers->balance_amount ?? '0';
                        $big_exchange_payable_price             = (float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                        
                        $hotel_customer_ledgers                 = DB::table('hotel_customer_ledgers')->insert([
                            'token'                             => $request->token,
                            'invoice_no'                        => $invoiceId,
                            'received_amount'                   => $exchange_payable_price,
                            'balance_amount'                    => $big_exchange_payable_price,
                            'type'                              => 'hotel_booking'
                        ]);
                        
                        $manage_customer_markups                = DB::table('manage_customer_markups')->insert([
                            'token'                             => $request->token,
                            'invoice_no'                        => $invoiceId,
                            'client_markup'                     => $client_markup,
                            'admin_markup'                      => $admin_markup,
                            'client_markup_type'                => $client_markup_type,
                            'admin_markup_type'                 => $admin_markup_type,
                            'payable_price'                     => $payable_price,
                            'client_commission_amount'          => $client_commission_amount,
                            'admin_commission_amount'           => $admin_commission_amount,
                            'total_markup_price'                => $total_markup_price,
                            'currency'                          => $currency,
                            'exchange_payable_price'            => $exchange_payable_price,
                            'exchange_client_commission_amount' => $exchange_client_commission_amount,
                            'exchange_total_markup_price'       => $exchange_total_markup_price,
                            'exchange_currency'                 => $exchange_currency,
                            'exchange_rate'                     => $exchange_rate,
                            'admin_exchange_amount'             => $admin_exchange_amount,
                            'exchange_admin_commission_amount'  => $admin_commission_amount,
                            'admin_exchange_currency'           => $admin_exchange_currency,
                            'admin_exchange_rate'               => $admin_exchange_rate,
                            'admin_exchange_total_markup_price' => $admin_exchange_total_markup_price,
                        ]);
                        
                        $admin_provider_payments_hotelbeds      = DB::table('admin_provider_payments')->latest()->first();
                        $payment_remaining_amount               = $admin_provider_payments_hotelbeds->payment_remaining_amount ?? '0';
                        $add_price                              = (float)$payment_remaining_amount + (float)$admin_exchange_total_markup_price;
                        
                        $admin_payments                         = DB::table('admin_provider_payments')->insert([
                            'payment_transction_id'             => $invoiceId,
                            'provider'                          => 'Custome_hotel',
                            'payment_remaining_amount'          => $add_price,
                        ]);
                        
                        $booking_customer_data  = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                        if($booking_customer_data){
                            $customer_balance   = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                            
                            DB::table('booking_customers')->where('id',$booking_customer_id)->update(['balance' => $customer_balance]);
                            
                            DB::table('customer_ledger')->insert([
                                'received'          => $request->admin_exchange_total_markup_price,
                                'balance'           => $customer_balance,
                                'booking_customer'  => $booking_customer_id,
                                'hotel_invoice_no'  => $invoiceId,
                                'date'              => date('Y-m-d'),
                                'customer_id'       => $userData->id
                            ]);
                            
                            if($request->slc_pyment_method == 'slc_stripe'){
                                $payment_Method = 'Stripe';
                            }elseif($request->slc_pyment_method == 'Bank_Payment'){
                                $payment_Method = 'Bank Transfer';
                            }else{
                                $payment_Method = '';
                            }
                            
                            // if($request->slc_pyment_method == 'slc_stripe'){
                                
                                $customer_balance = $customer_balance - $request->admin_exchange_total_markup_price;
                                DB::table('booking_customers')->where('id',$booking_customer_id)->update(['balance' => $customer_balance]);
                                
                                DB::table('customer_ledger')->insert([
                                    'payment'           => $request->admin_exchange_total_markup_price,
                                    'balance'           => $customer_balance,
                                    'booking_customer'  => $booking_customer_id,
                                    'hotel_invoice_no'  => $invoiceId,
                                    'payment_method'    => $payment_Method,
                                    'date'              => date('Y-m-d'),
                                    'customer_id'       => $userData->id
                                ]);
                            // }
                        }
                    }
                }
                DB::commit();
                
                $response_Status = 'success';
                
                // $booking_data       = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                // return response()->json([
                //     'status'        => 'success',
                //     'Invoice_id'    => $invoiceId,
                //     'Invoice_data'  => $booking_data
                // ]);
            } catch (Throwable $e) {
                DB::rollback();
                echo $e;
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
        }
        
        // ************************************************************
        // Custom Hotel Provider Reservation
        // ************************************************************
        if($hotel_checkout_select->hotel_provider  == 'custom_hotel_provider'){
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
                            'rooms' => $rooms_details_arr,
                        ]
                    ];
                    
                    $invoiceId      =  $invoiceId;
                    
                    $customer_id    = '';
                    $userData       = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                    
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
                        'booking_type'=>$request->booking_type,
                        'b2b_agent_id'=>$request->b2b_agent_id ?? $customer_search_data->b2b_agent_id ?? null,
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
                    
                    $price                      = $request->exchange_price;
                    $p_price                    = json_decode($price);
                    
                    $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                    $sum_hotel_customer_ledgers = $get_hotel_customer_ledgers->balance_amount ?? '0';
                    $big_exchange_payable_price = (float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                    
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
                
                $response_Status = 'success';
                
                // $booking_data       = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                // return response()->json([
                //     'status'        => 'success',
                //     'Invoice_id'    => $invoiceId,
                //     'Invoice_data'  => $booking_data
                // ]);
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
                  $url = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi_live.php";
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
                //   return $responseData;
                  if (curl_errno($ch)) {
                      return curl_error($ch);
                  }
                  curl_close($ch);
                  return $responseData;
              }
              
                $responseData3      = confirmbooking($hotel_request_data,$hotel_checkout_select);
                $responseData3      = json_decode($responseData3);
                $hotel_request_send = json_decode($responseData3->request);
                $result_booking_rs  = json_decode($responseData3->response);
                // return $result_booking_rs;
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
                    
                    $customer_id    = '';
                    $userData       = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
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
                            'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id ?? $customer_search_data->b2b_agent_id ?? null,
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
                        
                        if(isset($credit_data)){
                            $ramainAmount=$credit_data->remaining_amount - $request->creditAmount;
                            $currency=$credit_data->currency;
                        }
                        else{
                            $ramainAmount=$request->creditAmount;
                            $currency='';
                        }
                        
                        $credit_limits = DB::table('credit_limits')->insert([
                            'transection_id'=>$invoiceId,
                            'customer_id'=>$customer_get_data->id,
                            'amount'=>$request->creditAmount,
                            'total_amount'=>$credit_data->total_amount ?? '',
                            'remaining_amount'=>$ramainAmount,
                            'currency'=>$currency,
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
                        
                        $response_Status = 'success';
                        
                        // $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                        // return response()->json([
                        //     'status' => 'success',
                        //     'Invoice_id' => $invoiceId,
                        //     'Invoice_data' => $booking_data
                        // ]);
                    } catch (Throwable $e) {
                        DB::rollback();
                        echo $e;
                        return response()->json(['message'=>'error','booking_id'=> '']);
                    }
                }
                else{   
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
                        'reservation_response' => $result_booking_rs->error->message ?? 'ERROR',
                        'actual_reservation_response' => json_encode($result_booking_rs),
                        'customer_id' => $customer_id,
                        'payment_details'=>$request->payment_details,
                        'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id ?? $customer_search_data->b2b_agent_id ?? null,
                    ]);
                    
                    $response_Status = 'error';
                    
                    // return response()->json([
                    //     'status' => 'error',
                    //     'message' => $result_booking_rs->error->message ?? 'ERROR',
                    // ]);   
                }
                
                if(isset($result_booking_rs->error)){
                    $response_Status = 'error';
                    // return response()->json([
                    //     'status' => 'error',
                    //     'message' => $result_booking_rs->error->message
                    // ]);
                }
            }else{
               // Non Refundable Booking
               
                $slc_pyment_method=$request->slc_pyment_method;
                if($slc_pyment_method == 'slc_stripe')
                {
                    function confirmbooking($hotel_request_data,$hotel_checkout_select){
                  $url = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi_live.php";
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
                            else
                            {
                                $randomNumber = random_int(1000000, 9999999);
                               $invoiceId =  "HH".$randomNumber;
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
                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id ?? $customer_search_data->b2b_agent_id ?? null,
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
                                    
                                    $response_Status = 'success';
                                    
                                    // $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                                    // if($result){
                                    //     return response()->json([
                                    //         'status' => 'success',
                                    //         'Invoice_id' => $invoiceId,
                                    //         'Invoice_data' => $booking_data
                                    //     ]);
                                    // }
                            
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
                            else
                            {
                                $randomNumber = random_int(1000000, 9999999);
                               $invoiceId =  "HH".$randomNumber;
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
                                    'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id ?? $customer_search_data->b2b_agent_id ?? null,
                                ]);
                                
                                $response_Status = 'error';
                                
                        // return response()->json([
                        //     'status' => 'error',
                        //     'message' => $result_booking_rs->error->message
                        // ]);   
                  }   
                   
                }
                else
                {
                    
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
                            'booking_type'=>$request->booking_type,
                            'b2b_agent_id'=>$request->b2b_agent_id ?? $customer_search_data->b2b_agent_id ?? null,
                        ]);
                        
                        $response_Status    = 'success_non_refundable';
                        
                        // $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                        // if($result){
                        //     return response()->json([
                        //         'status' => 'success_non_refundable',
                        //         'Invoice_id' => $invoiceId,
                        //         'Invoice_data' => $booking_data
                        //     ]);
                        // }
                    } catch (Throwable $e) {
                        echo $e;
                        return response()->json(['message'=>'error','booking_id'=> '']);
                    }
               }
            }
        }
        
        // ************************************************************
        // Travelanda Reservation
        // ************************************************************
        if($hotel_checkout_select->hotel_provider  == 'travelenda'){
            
            $current_date       = date('Y-m-d');
            $cancilation_date   = date('Y-m-d',strtotime($hotel_checkout_select->rooms_list[0]->cancliation_policy_arr[0]->from_date ?? ''));
            $cancilation_date   = Carbon::parse($cancilation_date);
            $current1           = Carbon::parse($current_date);
            
            // return 'cancilation_date : '.$cancilation_date.' current1 : '.$current1;
            
            if($cancilation_date > $current1){
                // Refundable
                // return 'Refundable';
                
                function travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select){
                    $url    = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                    $data   = array('case' => 'travelandaCnfrmBookingnew','hotel_request_data' => json_encode($hotel_request_data),'hotel_checkout_select'=>json_encode($hotel_checkout_select));
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
                
                $responseData3          = travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select);
                // return $responseData3;
                $responseData3          = json_decode($responseData3);
                $request_xml            = simplexml_load_string($responseData3->request);
                $hotel_request_send     = json_encode($request_xml);
                $result_booking_rs      = json_decode($responseData3->response);
                
                if(isset($result_booking_rs->Body->HotelBooking)){
                    
                    $rooms_details_arr = [];
                    if(isset($hotel_checkout_select->rooms_list)){
                        foreach($hotel_checkout_select->rooms_list as $room_res){
                            if(isset($room_res->rooms_list)){
                                foreach($room_res->rooms_list as $room_option_res){
                                
                                    $room_rate_arr = [];
                                    $cancliation_policy_arr = $room_res->cancliation_policy_arr;
                                    
                                    $room_rate_arr[] = (Object)[
                                        'rateClass'             => '',
                                        'net'                   => $room_option_res->room_price,
                                        'rateComments'          => '',
                                        'room_board'            => $room_res->board_id,
                                        'room_qty'              => 1,
                                        'adults'                => $room_option_res->adults,
                                        'children'              => $room_option_res->childs,
                                        'cancellation_policy'   => $cancliation_policy_arr,
                                    ];
                                    
                                    $rooms_details_arr[] = (Object)[
                                        'room_stutus'   => "Confirmed",
                                        'room_code'     => $room_option_res->room_id,
                                        'room_name'     => $room_option_res->room_name,
                                        'room_paxes'    => [],
                                        'adults'        => $room_option_res->adults,
                                        'childs'        => $room_option_res->childs,
                                        'room_rates'    => $room_rate_arr,
                                    ];
                                }
                            }
                        }
                    }
                  
                    $hotel_checkout_request_d   = json_decode($request->hotel_checkout_select);
                    $hotel_booking_conf_res     = (Object)[
                        'provider'              => 'travelenda',
                        'admin_markup'          => $hotel_checkout_request_d->admin_markup,
                        'customer_markup'       => $hotel_checkout_request_d->customer_markup,
                        'admin_markup_type'     => $hotel_checkout_request_d->admin_markup_type,
                        'customer_markup_type'  => $hotel_checkout_request_d->customer_markup_type,
                        'reference_no'          => $result_booking_rs->Body->HotelBooking->BookingReference,
                        'total_price'           => $result_booking_rs->Body->HotelBooking->TotalPrice,
                        'hotel_currency'        => $result_booking_rs->Body->HotelBooking->Currency,
                        'clientReference'       => $result_booking_rs->Body->HotelBooking->YourReference,
                        'creationDate'          => $result_booking_rs->Head->ServerTime,
                        'status'                => $result_booking_rs->Body->HotelBooking->BookingStatus,
                        'lead_passenger'        => $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name,
                        'hotel_details'         => (Object)[
                            'checkIn'           => $hotel_checkout_select->checkIn,
                            'checkOut'          => $hotel_checkout_select->checkOut,
                            'hotel_code'        => $hotel_checkout_select->hotel_id,
                            'hotel_name'        => $hotel_checkout_select->hotel_name,
                            'stars_rating'      => $hotel_checkout_select->stars_rating,
                            'destinationCode'   => $hotel_checkout_select->destinationCode,
                            'destinationName'   => $hotel_checkout_select->destinationName,
                            'latitude'          => $hotel_checkout_select->latitude,
                            'longitude'         => $hotel_checkout_select->longitude,
                            'rooms'             => $rooms_details_arr
                        ]
                    ];
                    
                    $customer_id                = '';
                    $userData                   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                    if($userData){
                        $customer_id            = $userData->id;
                    }
                    
                    $lead_passenger_object      = (Object)[
                        'lead_title'            => $hotel_request_data->lead_title,
                        'lead_first_name'       => $hotel_request_data->lead_first_name,
                        'lead_last_name'        => $hotel_request_data->lead_last_name,
                        'lead_date_of_birth'    => date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                        'lead_phone'            => $hotel_request_data->lead_phone,
                        'lead_email'            => $hotel_request_data->lead_email,
                        'lead_country'          => $hotel_request_data->lead_country, 
                    ];
                    
                    $others_adults              = [];
                    if(isset($hotel_request_data->other_title)){
                        foreach($hotel_request_data->other_title as $index => $other_res){
                            $others_adults[]    = (Object)[
                                'title'         => $other_res,
                                'name'          => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                'nationality'   => $hotel_request_data->other_nationality[$index],
                            ];
                        }
                    }
                    
                    $childs                     = [];
                    if(isset($hotel_request_data->child_title)){
                        foreach($hotel_request_data->child_title as $index => $other_res){
                            $childs[]           = (Object)[
                                'title'         => $other_res,
                                'name'          => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                'nationality'   => $hotel_request_data->child_nationality[$index],
                            ];
                        }
                    }
                    
                    try {
                        $result                                 = DB::table('hotels_bookings')->insert([
                            'invoice_no'                        => $invoiceId,
                            'booking_customer_id'               => $booking_customer_id,
                            'provider'                          => $hotel_booking_conf_res->provider,
                            'exchange_currency'                 => $request->exchange_currency,
                            'exchange_price'                    => $request->exchange_price,
                            'base_exchange_rate'                => $request->base_exchange_rate,
                            'base_currency'                     => $request->base_currency,
                            'selected_exchange_rate'            => $request->selected_exchange_rate,
                            'selected_currency'                 => $request->selected_currency,
                            'GBP_currency'                      => $request->admin_exchange_currency,
                            'GBP_exchange_rate'                 => $request->admin_exchange_rate,
                            'GBP_invoice_price'                 => $request->admin_exchange_total_markup_price,
                            'creationDate'                      => $result_booking_rs->Head->ServerTime,
                            'status'                            => $result_booking_rs->Body->HotelBooking->BookingStatus,
                            'lead_passenger'                    => $hotel_booking_conf_res->lead_passenger,
                            'lead_passenger_data'               => json_encode($lead_passenger_object),
                            'other_adults_data'                 => json_encode($others_adults),
                            'childs_data'                       => json_encode($childs),
                            'total_adults'                      => $customer_search_data->adult_searching,
                            'total_childs'                      => $customer_search_data->child_searching,
                            'total_rooms'                       => $customer_search_data->room_searching,
                            'reservation_request'               => $hotel_request_send,
                            'reservation_response'              => json_encode($hotel_booking_conf_res),
                            'actual_reservation_response'       => json_encode($result_booking_rs),
                            'customer_id'                       => $customer_id,
                            'booking_type'                      => $request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id ?? $customer_search_data->b2b_agent_id ?? null,
                        ]);
                        
                        $client_markup                          = $request->client_markup;
                        $admin_markup                           = $request->admin_markup;
                        $client_markup_type                     = $request->client_markup_type;
                        $admin_markup_type                      = $request->admin_markup_type;
                        $payable_price                          = $request->payable_price;
                        $client_commission_amount               = $request->client_commission_amount;
                        $total_markup_price                     = $request->total_markup_price;
                        $currency                               = $request->currency;
                        $exchange_client_commission_amount      = $request->exchange_client_commission_amount;                                         
                        $exchange_payable_price                 = $request->exchange_payable_price;                                          
                        $exchange_admin_commission_amount       = $request->admin_commission_amount; 
                        $exchange_total_markup_price            = $request->exchange_total_markup_price; 
                        $exchange_currency                      = $request->exchange_currency; 
                        $exchange_rate                          = $request->exchange_rate; 
                        $admin_exchange_amount                  = $request->admin_exchange_amount;
                        $admin_commission_amount                = $request->admin_commission_amount;
                        $admin_exchange_currency                = $request->admin_exchange_currency;
                        $admin_exchange_rate                    = $request->admin_exchange_rate;
                        $admin_exchange_total_markup_price      = $request->admin_exchange_total_markup_price;
                        $price                                  = $request->exchange_price;
                        $p_price                                = json_decode($price);
                        
                        $get_hotel_customer_ledgers             = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$request->token)->limit(1)->first();
                        $sum_hotel_customer_ledgers             = $get_hotel_customer_ledgers->balance_amount ?? '0';
                        $big_exchange_payable_price             = (float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                        $hotel_customer_ledgers                 = DB::table('hotel_customer_ledgers')->insert([
                            'token'                     => $request->token,
                            'invoice_no'                => $invoiceId,
                            'received_amount'           => $exchange_payable_price,
                            'balance_amount'            => $big_exchange_payable_price,
                            'type'                      => 'hotel_booking'
                        ]);
                        
                        $manage_customer_markups                = DB::table('manage_customer_markups')->insert([
                            'token'                             => $request->token,
                            'invoice_no'                        => $invoiceId,
                            'client_markup'                     => $client_markup,
                            'admin_markup'                      => $admin_markup,
                            'client_markup_type'                => $client_markup_type,
                            'admin_markup_type'                 => $admin_markup_type,
                            'payable_price'                     => $payable_price,
                            'client_commission_amount'          => $client_commission_amount,
                            'admin_commission_amount'           => $admin_commission_amount,
                            'total_markup_price'                => $total_markup_price,
                            'currency'                          => $currency,
                            'exchange_payable_price'            => $exchange_payable_price,
                            'exchange_client_commission_amount' => $exchange_client_commission_amount,
                            'exchange_total_markup_price'       => $exchange_total_markup_price,
                            'exchange_currency'                 => $exchange_currency,
                            'exchange_rate'                     => $exchange_rate,
                            'admin_exchange_amount'             => $admin_exchange_amount,
                            'exchange_admin_commission_amount'  => $admin_commission_amount,
                            'admin_exchange_currency'           => $admin_exchange_currency,
                            'admin_exchange_rate'               => $admin_exchange_rate,
                            'admin_exchange_total_markup_price' => $admin_exchange_total_markup_price,
                        ]);
                        
                        $admin_provider_payments_hotelbeds      = DB::table('admin_provider_payments')->latest()->first();
                        $payment_remaining_amount               = $admin_provider_payments_hotelbeds->payment_remaining_amount ?? '0';
                        $add_price                              = (float)$payment_remaining_amount + (float)$admin_exchange_total_markup_price;
                        
                        $customer_get_data                      = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                        $credit_data                            = DB::table('credit_limits')->where('customer_id',$customer_get_data->id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
                        $ramainAmount                           = $credit_data->remaining_amount ?? 0 - $request->creditAmount ?? 0;
                        
                        $credit_limits                          = DB::table('credit_limits')->insert([
                            'transection_id'                    => $invoiceId,
                            'customer_id'                       => $customer_get_data->id,
                            'amount'                            => $request->creditAmount,
                            'total_amount'                      => $credit_data->total_amount ?? 0,
                            'remaining_amount'                  => $ramainAmount,
                            'currency'                          => $credit_data->currency ?? '',
                            'status'                            => '1',
                            'status_type'                       => 'approved',
                        ]);
                        
                        $credit_limits                          = DB::table('credit_limit_transections')->insert([
                            'invoice_no'                        => $invoiceId,
                            'customer_id'                       => $customer_get_data->id,
                            'transection_amount'                => $request->creditAmount,
                            'remaining_amount'                  => $ramainAmount,
                            'type'                              => 'booked',
                        ]);
                        
                        $admin_payments                         = DB::table('admin_provider_payments')->insert([
                            'payment_transction_id'             => $invoiceId,
                            'provider'                          => 'hotelbeds',
                            'payment_remaining_amount'          => $add_price,
                        ]);
                        
                        $booking_customer_data                  = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                        if($booking_customer_data){
                            
                            $customer_balance                   = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                            DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                'balance'                       => $customer_balance
                            ]);
                                
                            DB::table('customer_ledger')->insert([
                                'received'                      => $request->admin_exchange_total_markup_price,
                                'balance'                       => $customer_balance,
                                'booking_customer'              => $booking_customer_id,
                                'hotel_invoice_no'              => $invoiceId,
                                'date'                          => date('Y-m-d'),
                                'customer_id'                   => $userData->id
                            ]);
                            
                            if($request->slc_pyment_method == 'slc_stripe'){
                                
                                $customer_balance               = $customer_balance - $request->admin_exchange_total_markup_price;
                                DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                    'balance'                   => $customer_balance
                                ]);
                                
                                DB::table('customer_ledger')->insert([
                                    'payment'                   => $request->admin_exchange_total_markup_price,
                                    'balance'                   => $customer_balance,
                                    'booking_customer'          => $booking_customer_id,
                                    'hotel_invoice_no'          => $invoiceId,
                                    'payment_method'            => 'Stripe',
                                    'date'                      => date('Y-m-d'),
                                    'customer_id'               => $userData->id
                                ]);
                            }
                        }
                        
                        $response_Status = 'success';
                        // return 'Start : '.$response_Status;
                        
                        //   $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                        
                        // if($result){
                        //     return response()->json([
                        //         'status' => 'success',
                        //         'Invoice_id' => $invoiceId,
                        //         'Invoice_data' => $booking_data
                        //     ]);
                        // }
                    
                    } catch (Throwable $e) {
                        DB::rollback();
                        echo $e;
                        return response()->json(['message'=>'error','booking_id'=> '']);
                    }
                }else{
                    if($result_booking_rs->Body->Error){
                        $response_Status = 'error';
                        //       return response()->json([
                        //             'status' => 'error',
                        //             'message' => $result_booking_rs->Body->Error->ErrorText
                        //         ]);
                    }
                }
              
            }else{
                // Non Refundable
                // return 'Non Refundable';
                
                $slc_pyment_method = $request->slc_pyment_method;
                if($slc_pyment_method == 'slc_stripe'){
                    
                    // return $hotel_checkout_select;
                    
                    function travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select){
                        $url = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                        $data = array('case' => 'travelandaCnfrmBookingnew','hotel_request_data' => json_encode($hotel_request_data),
                                          'hotel_checkout_select'=>json_encode($hotel_checkout_select));
                                        //   return $data;
                        // Session::put('travelenda_booking_request',$data);
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
                    
                    $responseData3      = travelandaCnfrmBooking($hotel_request_data,$hotel_checkout_select);
                    // return $responseData3;
                    $responseData3      = json_decode($responseData3);
                    $request_xml        = simplexml_load_string($responseData3->request);
                    $hotel_request_send = json_encode($request_xml);
                    $result_booking_rs  = json_decode($responseData3->response);
                    
                    if(isset($result_booking_rs->Body->HotelBooking)){
                      
                        $rooms_details_arr = [];
                        if(isset($hotel_checkout_select->rooms_list)){
                            foreach($hotel_checkout_select->rooms_list as $room_res){
                                if(isset($room_res->rooms_list)){
                                    foreach($room_res->rooms_list as $room_option_res){
                                        $room_rate_arr              = [];
                                        $cancliation_policy_arr     = $room_res->cancliation_policy_arr;
                                        
                                        $room_rate_arr[]            = (Object)[
                                            'rateClass'             => '',
                                            'net'                   => $room_option_res->room_price,
                                            'rateComments'          => '',
                                            'room_board'            => $room_res->board_id,
                                            'room_qty'              => 1,
                                            'adults'                => $room_option_res->adults,
                                            'children'              => $room_option_res->childs,
                                            'cancellation_policy'   => $cancliation_policy_arr,
                                        ];
                                        
                                        $rooms_details_arr[] = (Object)[
                                            'room_stutus'           => "Confirmed",
                                            'room_code'             => $room_option_res->room_id,
                                            'room_name'             => $room_option_res->room_name,
                                            'room_paxes'            => [],
                                            'adults'                => $room_option_res->adults,
                                            'childs'                => $room_option_res->childs,
                                            'room_rates'            => $room_rate_arr,
                                        ];
                                    }
                                }
                            }
                        }
                      
                        $hotel_checkout_request_d           = json_decode($request->hotel_checkout_select);
                        $hotel_booking_conf_res             = (Object)[
                            'provider'                  => 'travelenda',
                            'admin_markup'             => $hotel_checkout_request_d->admin_markup,
                            'customer_markup'           => $hotel_checkout_request_d->customer_markup,
                            'admin_markup_type'         => $hotel_checkout_request_d->admin_markup_type,
                            'customer_markup_type'      => $hotel_checkout_request_d->customer_markup_type,
                            'reference_no'              => $result_booking_rs->Body->HotelBooking->BookingReference,
                            'total_price'               => $result_booking_rs->Body->HotelBooking->TotalPrice,
                            'hotel_currency'            => $result_booking_rs->Body->HotelBooking->Currency,
                            'clientReference'           => $result_booking_rs->Body->HotelBooking->YourReference,
                            'creationDate'              => $result_booking_rs->Head->ServerTime,
                            'status'                    => $result_booking_rs->Body->HotelBooking->BookingStatus,
                            'lead_passenger'            => $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name,
                            'hotel_details'             =>( Object)[
                                'checkIn'           => $hotel_checkout_select->checkIn,
                                'checkOut'          => $hotel_checkout_select->checkOut,
                                'hotel_code'        => $hotel_checkout_select->hotel_id,
                                'hotel_name'        => $hotel_checkout_select->hotel_name,
                                'stars_rating'      => $hotel_checkout_select->stars_rating,
                                'destinationCode'   => $hotel_checkout_select->destinationCode,
                                'destinationName'   => $hotel_checkout_select->destinationName,
                                'latitude'          => $hotel_checkout_select->latitude,
                                'longitude'         => $hotel_checkout_select->longitude,
                                'rooms'             => $rooms_details_arr
                            ]
                        ];
                        
                        $customer_id                = '';
                        $userData                   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                        if($userData){
                            $customer_id            = $userData->id;
                        }
                        
                        $lead_passenger_object      = (Object)[
                            'lead_title'            => $hotel_request_data->lead_title,
                            'lead_first_name'       => $hotel_request_data->lead_first_name,
                            'lead_last_name'        => $hotel_request_data->lead_last_name,
                            'lead_date_of_birth'    => date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                            'lead_phone'            => $hotel_request_data->lead_phone,
                            'lead_email'            => $hotel_request_data->lead_email,
                            'lead_country'          => $hotel_request_data->lead_country, 
                        ];
                        
                        $others_adults              = [];
                        if(isset($hotel_request_data->other_title)){
                            foreach($hotel_request_data->other_title as $index => $other_res){
                                $others_adults[]    = (Object)[
                                    'title'         => $other_res,
                                    'name'          => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                    'nationality'   => $hotel_request_data->other_nationality[$index],
                                ];
                            }
                        }
                        
                        $childs = [];
                        if(isset($hotel_request_data->child_title)){
                            foreach($hotel_request_data->child_title as $index => $other_res){
                                $childs[] = (Object)[
                                    'title'         => $other_res,
                                    'name'          => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                    'nationality'   => $hotel_request_data->child_nationality[$index],
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
                                'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id ?? $customer_search_data->b2b_agent_id ?? null,
                            ]);
                            
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
                            $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                            
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
                            
                            $response_Status = 'success';
                            
                            // $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                            // if($result){
                            //     return response()->json([
                            //         'status' => 'success',
                            //         'Invoice_id' => $invoiceId,
                            //         'Invoice_data' => $booking_data
                            //     ]);
                            // }
                        } catch (Throwable $e) {
                            DB::rollback();
                            echo $e;
                            return response()->json(['message'=>'error','booking_id'=> '']);
                        }
                    }else{
                        if($result_booking_rs->Body->Error){
                            $response_Status = 'error';
                            // return response()->json([
                            //     'status' => 'error',
                            //     'message' => $result_booking_rs->Body->Error->ErrorText
                            // ]);
                        }
                    }
                }
                else{
                    // return 'ELSE';
                    
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
                            'booking_type'=>$request->booking_type,'b2b_agent_id'=>$request->b2b_agent_id ?? $customer_search_data->b2b_agent_id ?? null,
                        ]);
                        
                        $response_Status = 'success_non_refundable';
                        
                        //   $booking_data = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first(); 
                        // if($result){
                        //     return response()->json([
                        //         'status' => 'success_non_refundable',
                        //         'Invoice_id' => $invoiceId,
                        //         'Invoice_data' => $booking_data
                        //     ]);
                        // }
                    } catch (Throwable $e) {
                        echo $e;
                        return response()->json(['message'=>'error','booking_id'=> '']);
                    }
                }
            }
        }
        
        // ************************************************************
        // Stuba Reservation
        // ************************************************************
        if ($hotel_checkout_select->hotel_provider  == 'Stuba') {
            // return 's';
            $current_date           = date('Y-m-d');
            $cancilation_date       = date('Y-m-d', strtotime($hotel_checkout_select->rooms_list[0]->cancliation_policy_arr[0]->from_date ?? ''));
            $cancilation_date       = Carbon::parse($cancilation_date);
            $current1               = Carbon::parse($current_date);
            $hotel_request_data     = json_decode($request->request_data);
            
            $title                  = $hotel_request_data->lead_title;
            $firstname              =  $hotel_request_data->lead_first_name;
            $lastname               = $hotel_request_data->lead_last_name;
            $hotel_checkout_select  = json_decode($request->hotel_checkout_select);
            $rooms                  =  $hotel_checkout_select->rooms_list;
            
            $booking_id             = $rooms[0]->booking_req_id;
            $rooms_info             = [];
            $i                      = 1;
            
            foreach ($rooms as $room) {
                $room_data = [];
                $room_info = [];
                $room_info['adults'] = $room->adults;
                $room_info['childs'] = $room->childs;
                $room_data["room" . $i] = $room_info;
                array_push($rooms_info, $room_data);
                if ($room->rooms_qty) {
                    for ($x = 1; $x < $room->rooms_qty; $x++) {
                        $room_data = [];
                        $room_info['adults'] = $room->adults;
                        $room_info['childs'] = $room->childs;
                        $room_data["room" . ($i + $x)] = $room_info;
                        array_push($rooms_info, $room_data);
                    }
                }
                $i++;
            }
            
            $uniqueRoomsInfo = [];
            
            foreach ($rooms_info as $room_data) {
                // Extract the room index (e.g., "room1", "room2", etc.)
                $room_index = key($room_data);
                // If the room index is not already present, add it to the $uniqueRoomsInfo array
                if (!isset($uniqueRoomsInfo[$room_index])) {
                    $uniqueRoomsInfo[] = $room_data;
                } else {
                    // If the room index is already present, overwrite the data with the new one
                    $uniqueRoomsInfo[$room_index] = $room_data;
                }
            }
            
            $rooms_info = $uniqueRoomsInfo;
            
            $xmlPayload =   '<BookingCreate>
                                <Authority xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                                    <Org>'.env('STUBA_HOTEL_ORG').'</Org>
                                    <User>'.env('STUBA_HOTEL_USERNAME').'</User>
                                    <Password>'.env('STUBA_HOTEL_PASSWORD').'</Password>
                                    <Currency>GBP</Currency>
                                    <Language>en</Language>
                                    <TestDebug>false</TestDebug>
                                    <Version>1.28</Version>
                                </Authority>
                                <QuoteId>' . $booking_id . '</QuoteId>
                                <HotelStayDetails>
                            <Nationality>GB</Nationality>';
            foreach ($rooms_info as $room_data) {
                // Extract the room index and room info
                $room_index = key($room_data);
                $room_info = reset($room_data);
                
                $xmlPayload .= "<Room>
                    <Guests>";
                
                $adults = $room_info['adults'];
                $childs = $room_info['childs'];
                
                for ($z = 1; $z <= $adults; $z++) {
                    $xmlPayload .= '<Adult title="' . $title . '" first="' . $firstname . '" last="' . $lastname . '" />';
                }
                
                for ($z = 1; $z <= $childs; $z++) {
                    $xmlPayload .= '<Child  title="' . $title . '" first="' . "Child" . '" last="' . "child" . '"  age="5"/>';
                }
                
                $xmlPayload .= "</Guests>
                </Room>";
            }
            
            $xmlPayload .= '</HotelStayDetails>
                                <CommitLevel>confirm</CommitLevel>
                            </BookingCreate>';
                            // return $xmlPayload;
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.stuba.com/RXLServices/ASMX/XmlService.asmx',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $xmlPayload,
                CURLOPT_HTTPHEADER => array(
                    'op: AvailabilitySearch',
                    'Content-Type: text/xml; charset=utf-8'
                ),
            ));
            
            // Execute the cURL request
            $response = curl_exec($curl);
            
            // Close cURL
            curl_close($curl);
            
            // Output the response
            $xml = simplexml_load_string($response);
            $json = json_encode($xml);
            
            $data           = json_encode(json_decode($json), JSON_PRETTY_PRINT);
            $bookingData    = json_decode($data, true);
            $booking_id     = $bookingData['Booking']['Id'] ?? 100000000;
            // if ($cancilation_date > $current1) {
            //     return "if";
            // } else {
                $slc_pyment_method = $request->slc_pyment_method;
                if ($slc_pyment_method == 'slc_stripe') {
                    if (isset($bookingData['Booking']['HotelBooking'])) {
                        $booking_details =  $bookingData['Booking']['HotelBooking'];
                        $total_price = 0;
                        if (isset($booking_details[0])) {
                            $i = 1;
                            foreach ($booking_details as $booking) {
                                if (isset($booking['Room'])) {
                                    $paxes_arr          = [];
                                    $adults             = $booking['Room']['Guests']['Adult'];
                                    foreach ($adults as $adult) {
                                        $title          =  $adult['@attributes']['title'] ?? '';
                                        $first_name     = $adult['@attributes']['first'] ?? '';
                                        $last_name      =  $adult['@attributes']['last'] ?? '';
                                        $paxes_arr[]    = [
                                            'type'      => 'Adult',
                                            'name'      =>  $first_name . " " . $last_name,
                                        ];
                                    }
                                }
                                if (isset($booking['Room']['CanxFees']['Fee'])) {
                                    if (isset(['Booking']['HotelBooking']['Room']['CanxFees']['Fee'][0]['Amount']['@attributes']['amt'])) {
                                        $amount         = $booking['Room']['CanxFees']['Fee'][0]['Amount']['@attributes']['amt'];
                                        $from           = $booking['Room']['CanxFees']['Fee'][1]['@attributes']['from'];
                                        $cancel_tiem    = (object)[
                                            'amount'    => $amount,
                                            'from_date' => $from,
                                        ];
                                    } elseif (isset($booking['Room']['CanxFees']['Fee']['Amount']['@attributes']['amt'])) {
                                        $cancel         = $booking['Room']['CanxFees']['Fee'];
                                        $cancel_tiem    = (object)[
                                            'amount'    => $cancel['Amount']['@attributes']['amt'],
                                            'from_date' => '',
                                        ];
                                    } else {
                                        $cancelinfos =  $booking['Room']['CanxFees']['Fee'];
                                        foreach ($cancelinfos as $cancelinfo) {
                                            $cancel_tiem    = (object)[
                                                'amount'    => $cancelinfo['Amount']['@attributes']['amt'],
                                                'from_date' => '',
                                            ];
                                        }
                                    }
                                }
                                $rate           = $booking['Room']['TotalSellingPrice']['@attributes']['amt'];
                                $price          = $booking['Room']['TotalSellingPrice']['@attributes'];
                                $total_price    += floatval($price['amt']);
                                $room_board     = $booking['Room']['MealType']['@attributes']['text'];
                                if (isset($booking['Room']['Guests']['Adult'])) {
                                    $adults = count($booking['Room']['Guests']['Adult']);
                                }
                                if (isset($booking['Room']['Guests']['Child'])) {
                                    $childs = count($booking['Room']['Guests']['Child']);
                                }
                                $room_rate_arr[] = (object)[
                                    'rateClass'             => 'NOR',
                                    'net'                   =>   $rate,
                                    'rateComments'          => '' ?? '',
                                    'room_board'            =>    $room_board,
                                    'room_qty'              => '1',
                                    'adults'                => count($booking['Room']['Guests']['Adult']),
                                    'children'              =>  $childs ?? '',
                                    'cancellation_policy'   => $cancel_tiem,
                                ];
                                $status                 = $booking['Room']['Status'];
                                $rooms_details_arr[]    = (object)[
                                    'room_stutus'       => $bookingData['CommitLevel'],
                                    'room_code'         => $booking['Room']['RoomType']['@attributes']['code'],
                                    'room_name'         => $booking['Room']['RoomType']['@attributes']['text'],
                                    'room_paxes'        => $paxes_arr,
                                    'room_rates'        => array($room_rate_arr[$i] ?? $room_rate_arr[$i - 1] ?? $room_rate_arr),
                                ];
                                $refrence[]             = (object)[
                                    'reference' => $booking['Id'] ??   $bookingData['Booking']['HotelBooking']['Id']
                                ];
                                $i++;
                            }
                        } else {
                            $adults     = $booking_details['Room']['Guests']['Adult'];
                            $adults_no  = count($adults);
                            if(isset($adults[0])){
                                foreach ($adults as $adult) {
                                    $paxes_arr[] = [
                                        'type' => 'Adult',
                                        'name' => $adult['@attributes']['first'] . " " . $adult['@attributes']['last'],
                                    ];
                                }
                            }
                            else{
                                $paxes_arr[] = [
                                    'type' => 'Adult',
                                    'name' => $adults['@attributes']['first'] . " " . $adults['@attributes']['last'],
                                ];
                            }
                            
                            if (isset($booking_details['Room']['CanxFees']['Fee'])) {
                                if (isset(['Booking']['HotelBooking']['Room']['CanxFees']['Fee'][0]['Amount']['@attributes']['amt'])) {
                                    $amount         = $booking_details['Room']['CanxFees']['Fee'][0]['Amount']['@attributes']['amt'];
                                    $from           = $booking_details['Room']['CanxFees']['Fee'][1]['@attributes']['from'];
                                    $cancel_tiem    = (object)[
                                        'amount'    => $amount,
                                        'from_date' => $from,
                                    ];
                                } elseif (isset($booking['Room']['CanxFees']['Fee']['Amount']['@attributes']['amt'])) {
                                    $cancel         = $booking['Room']['CanxFees']['Fee'];
                                    $cancel_tiem    = (object)[
                                        'amount'    => $cancel['Amount']['@attributes']['amt'],
                                        'from_date' => '',
                                    ];
                                } else {
                                    $cancelinfos    = $booking_details['Room']['CanxFees']['Fee'];
                                    if (isset($cancelinfos[0])) {
                                        foreach ($cancelinfos as $cancelinfo) {
                                            $cancel_tiem = (object)[
                                                'amount'    => $cancelinfo['Amount']['@attributes']['amt'] ?? $cancelinfo['@attributes']['amt'],
                                                'from_date' => $cancelinfo['@attributes']['from'] ?? '',
                                            ];
                                        }
                                    } else {
                                        // return $cancelinfos;
                                        $cancel_tiem = (object)[
                                            'amount'    => $cancelinfos['Amount']['@attributes']['amt'] ?? $cancelinfos['@attributes']['amt'],
                                            'from_date' => $cancelinfos['@attributes']['from'] ?? '',
                                        ];
                                    }
                                }
                            }
                            $rate           = $booking_details['Room']['TotalSellingPrice']['@attributes']['amt'];
                            $price          = $booking_details['Room']['TotalSellingPrice']['@attributes'];
                            $total_price    += floatval($price['amt']);
                            $room_board     = $booking_details['Room']['MealType']['@attributes']['text'];
                            if (isset($booking['Room']['Guests']['Adult'])) {
                                $adults     = count($booking_details['Room']['Guests']['Adult']);
                            }
                            if (isset($booking_details['Room']['Guests']['Child'])) {
                                $childs     = count($booking_details['Room']['Guests']['Child']);
                            }
                            
                            $room_rate_arr[] = (object)[
                                'rateClass'             => 'NOR',
                                'net'                   =>   $rate,
                                'rateComments'          => '' ?? '',
                                'room_board'            =>    $room_board,
                                'room_qty'              => '1',
                                'adults'                => isset($booking_details['Room']['Guests']['Adult']) ? count($booking_details['Room']['Guests']['Adult']) : $adults_no,
                                'children'              => isset($booking_details['Room']['Guests']['Child']) ? count($booking_details['Room']['Guests']['Child']) : 0,
                                'cancellation_policy'   => $cancel_tiem,
                            ];
                            //$status =  $booking_details['Room']['Status'];
                            $rooms_details_arr[] = (object)[
                                'room_stutus'   => $bookingData['CommitLevel'],
                                'room_code'     => $booking_details['Room']['RoomType']['@attributes']['code'],
                                'room_name'     => $booking_details['Room']['RoomType']['@attributes']['text'],
                                'room_paxes'    => $paxes_arr,
                                'room_rates'    => $room_rate_arr,
                            ];
                            
                            $refrence[] = (object)[
                                'reference' => $booking['Id'] ??   $bookingData['Booking']['HotelBooking']['Id']
                            ];
                        }
                        $hotel_checkout_request_d   = json_decode($request->hotel_checkout_select);
                        
                        $hotel_booking_conf_res     = (object)[
                            'provider'              => 'Stuba',
                            'admin_markup'          => $hotel_checkout_request_d->admin_markup,
                            'customer_markup'       => $hotel_checkout_request_d->customer_markup,
                            'admin_markup_type'     => $hotel_checkout_request_d->admin_markup_type,
                            'customer_markup_type'  => $hotel_checkout_request_d->customer_markup_type,
                            'reference_no'          => $booking_id,
                            'room_reference_no'     => $refrence,
                            'total_price'           => $total_price,
                            'hotel_currency'        => $bookingData['Currency'],
                            'clientReference'       => '',
                            'creationDate'          => $bookingData['Booking']['CreationDate'],
                            'status'                => $bookingData['CommitLevel'],
                            'lead_passenger'        => $paxes_arr[0]['name'],
                            'status'                => $bookingData['CommitLevel'],
                            'hotel_details'         => (object)[
                                'checkIn'           => $hotel_checkout_request_d->checkIn,
                                'checkOut'          => $hotel_checkout_request_d->checkOut,
                                'hotel_code'        => $hotel_checkout_request_d->hotel_id,
                                'hotel_name'        => $hotel_checkout_request_d->hotel_name,
                                'stars_rating'      => $hotel_checkout_select->stars_rating,
                                'destinationCode'   => $hotel_checkout_select->destinationCode,
                                'destinationName'   => $hotel_checkout_select->destinationName,
                                'latitude'          => $hotel_checkout_select->latitude,
                                'longitude'         => $hotel_checkout_select->longitude,
                                'rooms'             => $rooms_details_arr
                            ]
                        ];
                        
                        
                        $customer_id        = '';
                        $userData           = CustomerSubcription::where('Auth_key', $request->token)->select('id', 'status')->first();
                        if ($userData) {
                            $customer_id    = $userData->id;
                        }
                        
                        $lead_passenger_object  = (object)[
                            'lead_title'            => $hotel_request_data->lead_title,
                            'lead_first_name'       => $hotel_request_data->lead_first_name,
                            'lead_last_name'        => $hotel_request_data->lead_last_name,
                            'lead_date_of_birth'    => date('d-m-Y', strtotime($hotel_request_data->lead_date_of_birth)),
                            'lead_phone'            => $hotel_request_data->lead_phone,
                            'lead_email'            => $hotel_request_data->lead_email,
                            'lead_country'          => $hotel_request_data->lead_country,
                        ];
                        
                        $others_adults          = [];
                        
                        if (isset($hotel_request_data->other_title)) {
                            foreach ($hotel_request_data->other_title as $index => $other_res) {
                                $others_adults[] = (object)[
                                    'title'         => $other_res,
                                    'name'          => $hotel_request_data->other_first_name[$index] . " " . $hotel_request_data->other_last_name[$index],
                                    'nationality'   => $hotel_request_data->other_nationality[$index],
                                ];
                            }
                        }
                        
                        $childs = [];
                        if (isset($hotel_request_data->child_title)) {
                            foreach ($hotel_request_data->child_title as $index => $other_res) {
                                $childs[] = (object)[
                                    'title'         => $other_res,
                                    'name'          => $hotel_request_data->child_first_name[$index] . " " . $hotel_request_data->child_last_name[$index],
                                    'nationality'   => $hotel_request_data->child_nationality[$index],
                                ];
                            }
                        }
                        
                        try {
                            $result = DB::table('hotels_bookings')->insert([
                                'invoice_no'                    => $invoiceId,
                                'provider'                      => $hotel_booking_conf_res->provider,
                                'booking_customer_id'           => $booking_customer_id,
                                'exchange_currency'             => $request->exchange_currency_customer ?? $request->exchange_currency ?? '',
                                'exchange_price'                => $request->exchange_price,
                                'base_exchange_rate'            => $request->base_exchange_rate,
                                'base_currency'                 => $request->base_currency,
                                'selected_exchange_rate'        => $request->selected_exchange_rate,
                                'selected_currency'             => $request->selected_currency,
                                'GBP_currency'                  => $request->admin_exchange_currency,
                                'GBP_exchange_rate'             => $request->admin_exchange_rate,
                                'GBP_invoice_price'             => $request->admin_exchange_total_markup_price,
                                'creationDate'                  => $hotel_booking_conf_res->creationDate,
                                'status'                        => $hotel_booking_conf_res->status,
                                'lead_passenger'                => $hotel_booking_conf_res->lead_passenger,
                                'lead_passenger_data'           => json_encode($lead_passenger_object),
                                'other_adults_data'             => json_encode($others_adults),
                                'childs_data'                   => json_encode($childs),
                                'status'                        => $hotel_booking_conf_res->status,
                                'total_adults'                  => $customer_search_data->adult_searching,
                                'total_childs'                  => $customer_search_data->child_searching,
                                'total_rooms'                   => $customer_search_data->room_searching,
                                'reservation_request'           => json_encode($xmlPayload),
                                'reservation_response'          => json_encode($hotel_booking_conf_res),
                                'actual_reservation_response'   => json_encode($bookingData),
                                'customer_id'                   => $customer_id,
                                'payment_details'               => $request->payment_details,
                                'booking_type'                  => $request->booking_type,
                                'b2b_agent_id'                  => $request->b2b_agent_id
                            ]);
                            
                            $client_markup = $request->client_markup;
                            $admin_markup = $request->admin_markup;
                            $client_markup_type = $request->client_markup_type;
                            $admin_markup_type = $request->admin_markup_type;
                            $payable_price = $request->payable_price;
                            $client_commission_amount = $request->client_commission_amount;
                            
                            $total_markup_price = $request->total_markup_price;
                            $currency = $request->currency;
                            $exchange_client_commission_amount = $request->exchange_client_commission_amount;
                            $exchange_payable_price = $request->exchange_payable_price;
                            $exchange_admin_commission_amount = $request->admin_commission_amount;
                            $exchange_total_markup_price = $request->exchange_total_markup_price;
                            $exchange_currency = $request->exchange_currency;
                            $exchange_rate = $request->exchange_rate;
                            
                            $admin_exchange_amount = $request->admin_exchange_amount;
                            $admin_commission_amount = $request->admin_commission_amount;
                            $admin_exchange_currency = $request->admin_exchange_currency;
                            $admin_exchange_rate = $request->admin_exchange_rate;
                            $admin_exchange_total_markup_price = $request->admin_exchange_total_markup_price;
                            
                            $price = $request->exchange_price;
                            $p_price = json_decode($price);
                            
                            $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id', 'DESC')->where('token', $request->token)->limit(1)->first();
                            $sum_hotel_customer_ledgers = $get_hotel_customer_ledgers->balance_amount ?? '0';
                            //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                            $big_exchange_payable_price = (float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                            //print_r($price_with_out_commission);die();
                            $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                
                                'token' => $request->token,
                                'invoice_no' => $invoiceId,
                                'received_amount' => $exchange_payable_price,
                                'balance_amount' => $big_exchange_payable_price,
                                'type' => 'hotel_booking'
                            ]);
                            
                            $manage_customer_markups = DB::table('manage_customer_markups')->insert([

                                'token' => $request->token,
                                'invoice_no' => $invoiceId,
                                'client_markup' => $client_markup,
                                'admin_markup' => $admin_markup,
                                'client_markup_type' => $client_markup_type,
                                'admin_markup_type' => $admin_markup_type,
                                'payable_price' => $payable_price,
                                'client_commission_amount' => $client_commission_amount,
                                'admin_commission_amount' => $admin_commission_amount,
                                'total_markup_price' => $total_markup_price,
                                'currency' => $currency,

                                'exchange_payable_price' => $exchange_payable_price,
                                'exchange_client_commission_amount' => $exchange_client_commission_amount,
                                'exchange_total_markup_price' => $exchange_total_markup_price,
                                'exchange_currency' => $exchange_currency,
                                'exchange_rate' => $exchange_rate,

                                'admin_exchange_amount' => $admin_exchange_amount,
                                'exchange_admin_commission_amount' => $admin_commission_amount,
                                'admin_exchange_currency' => $admin_exchange_currency,
                                'admin_exchange_rate' => $admin_exchange_rate,
                                'admin_exchange_total_markup_price' => $admin_exchange_total_markup_price,

                            ]);
                            
                            $admin_provider_payments_hotelbeds = DB::table('admin_provider_payments')->latest()->first();
                            $payment_remaining_amount = $admin_provider_payments_hotelbeds->payment_remaining_amount ?? '0';
                            $add_price = (float)$payment_remaining_amount + (float)$admin_exchange_total_markup_price;
                            
                            $admin_payments = DB::table('admin_provider_payments')->insert([
                                'payment_transction_id' => $invoiceId,
                                'provider' => 'hotelbeds',
                                'payment_remaining_amount' => $add_price,
                            ]);
                            
                            $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key', $request->token)->first();
                            $credit_data = DB::table('credit_limits')->where('customer_id', $customer_get_data->id)->where('status_type', 'approved')->orderBy('id', 'DESC')->limit(1)->first();
                            //print_r($credit_data);die();
                            if (isset($credit_data)) {
                                $ramainAmount = $credit_data->remaining_amount ?? 0 - $request->creditAmount ?? 0;
                                $currency = $credit_data->currency;
                            } else {
                                $ramainAmount = $request->creditAmount;
                                $currency = '';
                            }
                            
                            $credit_limits = DB::table('credit_limits')->insert([
                                'transection_id' => $invoiceId,
                                'customer_id' => $customer_get_data->id,
                                'amount' => $request->creditAmount,
                                'total_amount' => $credit_data->total_amount ?? '',
                                'remaining_amount' => $ramainAmount,
                                'currency' => $currency,
                                'status' => '1',
                                'status_type' => 'approved',
                            ]);
                            
                            $credit_limits = DB::table('credit_limit_transections')->insert([
                                'invoice_no' => $invoiceId,
                                'customer_id' => $customer_get_data->id,
                                'transection_amount' => $request->creditAmount,
                                'remaining_amount' => $ramainAmount,
                                'type' => 'booked',
                            ]);
                            
                            // Added Balance To Customer Ledger
                            $booking_customer_data = DB::table('booking_customers')->where('id', $booking_customer_id)->first();
                            if ($booking_customer_data) {
                                $customer_balance = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                                DB::table('booking_customers')->where('id', $booking_customer_id)->update([
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
                                if ($request->slc_pyment_method == 'slc_stripe') {
                                    $customer_balance = $customer_balance - $request->admin_exchange_total_markup_price;
                                    DB::table('booking_customers')->where('id', $booking_customer_id)->update([
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
                            $response_Status = 'success';
                            
                            // $booking_data = DB::table('hotels_bookings')->where('invoice_no', $invoiceId)->first();
                            // return response()->json([
                            //     'status' => 'success',
                            //     'Invoice_id' => $invoiceId,
                            //     'Invoice_data' => $booking_data
                            // ]);
                        } catch (Throwable $e) {
                            DB::rollback();
                            echo $e;
                            return response()->json(['message' => 'error', 'booking_id' => '']);
                        }
                    }
                }
            // }
        }
        
        // ************************************************************
        // SunHotel Reservation
        // ************************************************************
        if ($hotel_checkout_select->hotel_provider == 'Sunhotel') {
            
            // Customer Id
            $customer_id    = '';
            $userData       = CustomerSubcription::where('Auth_key', $request->token)->select('id', 'status')->first();
            if ($userData) {
                $customer_id = $userData->id;
            }
            // Customer Id
            
            // Pax Details
            $lead_passenger_object = (object)[
                'lead_title'            => $hotel_request_data->lead_title,
                'lead_first_name'       => $hotel_request_data->lead_first_name,
                'lead_last_name'        => $hotel_request_data->lead_last_name,
                'lead_date_of_birth'    => date('d-m-Y', strtotime($hotel_request_data->lead_date_of_birth)),
                'lead_phone'            => $hotel_request_data->lead_phone,
                'lead_email'            => $hotel_request_data->lead_email,
                'lead_country'          => $hotel_request_data->lead_country,
            ];
            
            $others_adults = [];
            if (isset($hotel_request_data->other_title)) {
                foreach ($hotel_request_data->other_title as $index => $other_res) {
                    $others_adults[] = (object)[
                        'title'         => $other_res,
                        'name'          => $hotel_request_data->other_first_name[$index] . " " . $hotel_request_data->other_last_name[$index],
                        'nationality'   => $hotel_request_data->other_nationality[$index],
                        'first_Name'    => $hotel_request_data->other_first_name[$index],
                        'last_Name'     => $hotel_request_data->other_last_name[$index],
                    ];
                }
            }
            
            // return $hotel_request_data;
            $childs = [];
            if (isset($hotel_request_data->child_title)) {
                foreach ($hotel_request_data->child_title as $index => $other_res) {
                    $childs[] = (object)[
                        'title'         => $other_res,
                        'name'          => $hotel_request_data->child_first_name[$index] . " " . $hotel_request_data->child_last_name[$index],
                        'nationality'   => $hotel_request_data->child_nationality[$index],
                        'first_Name'    => $hotel_request_data->child_first_name[$index],
                        'last_Name'     => $hotel_request_data->child_last_name[$index],
                    ];
                }
            }
            // Pax Details
            
            $current_Date       = date('d-m-Y');
            $current_date       = date('Y-m-d');
            $cancellation_date  = date('Y-m-d', strtotime($hotel_checkout_select->rooms_list[0]->cancliation_policy_arr[0]->from_date ?? ''));
            $cancellation_date  = Carbon::parse($cancellation_date);
            $current1           = Carbon::parse($current_date);
            
            if ($cancellation_date > $current1) {
                // return 'Refundable';
                
                $rooms_list     = $hotel_checkout_select->rooms_list;
                
                $total_Adults   = 0;
                $total_Rooms    = 0;
                $total_Childs   = 0;
                if(isset($rooms_list[0]) || count($rooms_list) > 1){
                    foreach($rooms_list as $val_RL){
                        $total_Adults   += $val_RL->adults;
                        $total_Rooms    += $val_RL->rooms_qty;
                        $total_Childs   += $val_RL->childs;
                    }
                }
                
                $child_Ages_Arr = [];
                if(isset($rooms_list[0]) && count($rooms_list) > 1){
                    foreach($rooms_list as $val_RL){
                        $child_Ages = $val_RL->child_Ages;
                        if(count($child_Ages) > 0){
                            for($cA=0; $cA<count($child_Ages); $cA++){
                                array_push($child_Ages_Arr,$child_Ages[$cA]);
                            }
                        }
                    }
                }else{
                    if(isset($rooms_list[0])){
                        foreach($rooms_list as $val_RL){
                            $child_Ages = $val_RL->child_Ages;
                            if (!empty($child_Ages[0])) {
                                if(count($child_Ages) > 0){
                                    for($cA=0; $cA<count($child_Ages); $cA++){
                                        array_push($child_Ages_Arr,$child_Ages[$cA][0]);
                                    }
                                }
                            }
                        }
                    }
                }
                
                // return $child_Ages_Arr;
                
                $responseData3 = SunHotel_Controller::sunHotelBook($hotel_request_data,$rooms_list[0],$others_adults,$childs,$child_Ages_Arr,$total_Rooms,$total_Adults,$total_Childs,$hotel_checkout_select);
                // return $responseData3;
                
                // $responseData3  = SunHotel_Controller::sunHotelBook($hotel_request_data,$rooms_list[0],$others_adults,$childs);
                
                if($responseData3 != 'Error'){
                    $hotel_request_send         = $responseData3['request'];
                    $result_booking_rs          = $responseData3['response'];
                    $booking_details_sunHotel   = SunHotel_Controller::sunHotelDetails($result_booking_rs['hotel.id']);
                    // return $booking_details_tbo;
                    
                    if (isset($booking_details_sunHotel['hotels']['hotel'])) {
                        $rooms_details_arr = [];
                        if (isset($rooms_list)) {
                            foreach ($rooms_list as $room_res) {
                                $paxes_arr = [];
                                
                                if (count($others_adults) > 0) {
                                    foreach ($others_adults as $adult_Pax) {
                                        $paxes_arr[]    = [
                                            'type'      => 'Adult',
                                            'name'      => $adult_Pax->first_Name . " " . $adult_Pax->last_Name,
                                        ];
                                    }
                                }
                                
                                if (count($childs) > 0) {
                                    foreach ($childs as $child_Pax) {
                                        $paxes_arr[] = [
                                            'type' => 'Child',
                                            'name' => $child_Pax->first_Name . " " . $child_Pax->last_Name,
                                        ];
                                    }
                                }
                                
                                $cancliation_policy_arr = [];
                                if ($room_res->cancliation_policy_arr) {
                                    $CancelPolicies = $room_res->cancliation_policy_arr;
                                    if (count($CancelPolicies) > 1) {
                                        for ($cc = 0; $cc < count($CancelPolicies); $cc++) {
                                            if($CancelPolicies[$cc]->amount > 0){
                                                $cancel_tiem = (object)[
                                                    'amount'        => $CancelPolicies[$cc]->amount,
                                                    'from_date'     => $CancelPolicies[$cc]->from_date,
                                                ];
                                                $cancliation_policy_arr[] = $cancel_tiem;
                                            }
                                        }
                                    }else{
                                        if (count($CancelPolicies) > 0) {
                                            for ($cc = 0; $cc < count($CancelPolicies); $cc++) {
                                                $cancellation_Date = date('d-m-Y', strtotime($CancelPolicies[$cc]->from_date));
                                                if($cancellation_Date > $current_Date){
                                                    $cancel_tiem = (object)[
                                                        'amount'        => $CancelPolicies[$cc]->amount,
                                                        'from_date'     => $CancelPolicies[$cc]->from_date,
                                                    ];
                                                    $cancliation_policy_arr[] = $cancel_tiem;
                                                }else{
                                                    if($CancelPolicies[$cc]->from_date > 0){
                                                        $cancel_tiem = (object)[
                                                            'amount'        => $CancelPolicies[$cc]->amount,
                                                            'from_date'     => $CancelPolicies[$cc]->from_date,
                                                        ];
                                                        $cancliation_policy_arr[] = $cancel_tiem;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                
                                $room_rate_arr[]            = (object)[
                                    'rateClass'             => $room_res->rateClass ?? '',
                                    'net'                   => $room_res->rooms_selling_price ?? '',
                                    'rateComments'          => $room_res->rateComments ?? '',
                                    'room_board'            => $room_res->board_id ?? '',
                                    'room_qty'              => $room_res->rooms_qty,
                                    'adults'                => $room_res->adults,
                                    'children'              => $room_res->childs,
                                    'cancellation_policy'   => $cancliation_policy_arr,
                                ];
                                
                                $rooms_details_arr[]        = (object)[
                                    'room_stutus'           => 'Confirm',
                                    'room_code'             => $room_res->room_code,
                                    'room_name'             => $room_res->room_name,
                                    'room_paxes'            => $paxes_arr,
                                    'room_rates'            => $room_rate_arr,
                                ];
                            }
                        }
                        
                        $hotel_checkout_request_d   = json_decode($request->hotel_checkout_select);
                        $hotel_booking_conf_res     = (object)[
                            'provider'              => 'Sunhotel',
                            'admin_markup'          => $hotel_checkout_request_d->admin_markup,
                            'customer_markup'       => $hotel_checkout_request_d->customer_markup,
                            'admin_markup_type'     => $hotel_checkout_request_d->admin_markup_type,
                            'customer_markup_type'  => $hotel_checkout_request_d->customer_markup_type,
                            'reference_no'          => $result_booking_rs['bookingnumber'],
                            'total_price'           => $request->creditAmount,
                            'hotel_currency'        => $request->base_currency,
                            'clientReference'       => $result_booking_rs['bookingnumber'],
                            'creationDate'          => date('Y-m-d'),
                            'lead_passenger'        => $request->lead_first_name . '' . $request->lead_last_name,
                            'status'                => 'Confirm',
                            'hotel_details'         => (object)[
                                'checkIn'           => date('Y-m-d',strtotime($result_booking_rs['checkindate'])),
                                'checkOut'          => date('Y-m-d',strtotime($result_booking_rs['checkoutdate'])),
                                'hotel_code'        => $hotel_checkout_request_d->hotel_id,
                                'hotel_name'        => $result_booking_rs['hotel.name'],
                                'stars_rating'      => $result_booking_rs['Rating'] ?? '',
                                'destinationCode'   => $hotel_checkout_request_d->destinationCode,
                                'destinationName'   => $hotel_checkout_request_d->destinationName,
                                'latitude'          => $hotel_checkout_request_d->latitude,
                                'longitude'         => $hotel_checkout_request_d->longitude,
                                'rooms'             => $rooms_details_arr
                            ]
                        ];
                        
                        try {
                            $result = DB::table('hotels_bookings')->insert([
                                'invoice_no'                    => $invoiceId,
                                'provider'                      => $hotel_booking_conf_res->provider,
                                'booking_customer_id'           => $booking_customer_id,
                                'exchange_currency'             => $request->exchange_currency_customer ?? $request->exchange_currency ?? $request->base_currency,
                                'exchange_price'                => $request->exchange_price,
                                'base_exchange_rate'            => $request->base_exchange_rate,
                                'base_currency'                 => $request->base_currency,
                                'selected_exchange_rate'        => $request->selected_exchange_rate,
                                'selected_currency'             => $request->selected_currency,
                                'GBP_currency'                  => $request->admin_exchange_currency,
                                'GBP_exchange_rate'             => $request->admin_exchange_rate,
                                'GBP_invoice_price'             => $request->admin_exchange_total_markup_price,
                                'creationDate'                  => date('Y-m-d'),
                                'status'                        => $hotel_booking_conf_res->status,
                                'lead_passenger'                => $hotel_booking_conf_res->lead_passenger,
                                'lead_passenger_data'           => json_encode($lead_passenger_object),
                                'other_adults_data'             => json_encode($others_adults),
                                'childs_data'                   => json_encode($childs),
                                'status'                        => $hotel_booking_conf_res->status,
                                'total_adults'                  => $customer_search_data->adult_searching,
                                'total_childs'                  => $customer_search_data->child_searching,
                                'total_rooms'                   => $customer_search_data->room_searching,
                                'reservation_request'           => json_encode($hotel_request_send),
                                'reservation_response'          => json_encode($hotel_booking_conf_res),
                                'actual_reservation_response'   => json_encode($result_booking_rs),
                                'customer_id'                   => $customer_id,
                                'payment_details'               => $request->payment_details,
                                'booking_type'                  => $request->booking_type, 'b2b_agent_id' => $request->b2b_agent_id
                            ]);
                            
                            $client_markup                      = $request->client_markup;
                            $admin_markup                       = $request->admin_markup;
                            $client_markup_type                 = $request->client_markup_type;
                            $admin_markup_type                  = $request->admin_markup_type;
                            $payable_price                      = $request->payable_price;
                            $client_commission_amount           = $request->client_commission_amount;
                            
                            $total_markup_price                 = $request->total_markup_price;
                            $currency                           = $request->currency;
                            $exchange_client_commission_amount  = $request->exchange_client_commission_amount;
                            $exchange_payable_price             = $request->exchange_payable_price;
                            $exchange_admin_commission_amount   = $request->admin_commission_amount;
                            $exchange_total_markup_price        = $request->exchange_total_markup_price;
                            $exchange_currency                  = $request->exchange_currency;
                            $exchange_rate                      = $request->exchange_rate;
                            
                            $admin_exchange_amount              = $request->admin_exchange_amount;
                            $admin_commission_amount            = $request->admin_commission_amount;
                            $admin_exchange_currency            = $request->admin_exchange_currency;
                            $admin_exchange_rate                = $request->admin_exchange_rate;
                            $admin_exchange_total_markup_price  = $request->admin_exchange_total_markup_price;
                            
                            $price                              = $request->exchange_price;
                            $p_price                            = json_decode($price);
                            
                            $get_hotel_customer_ledgers         = DB::table('hotel_customer_ledgers')->orderBy('id', 'DESC')->where('token', $request->token)->limit(1)->first();
                            $sum_hotel_customer_ledgers         = $get_hotel_customer_ledgers->balance_amount ?? '0';
                            $big_exchange_payable_price         = (float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                            
                            $hotel_customer_ledgers             = DB::table('hotel_customer_ledgers')->insert([
                                'token'                         => $request->token,
                                'invoice_no'                    => $invoiceId,
                                'received_amount'               => $exchange_payable_price,
                                'balance_amount'                => $big_exchange_payable_price,
                                'type'                          => 'hotel_booking'
                            ]);
                            
                            $manage_customer_markups            = DB::table('manage_customer_markups')->insert([
                                'token'                             => $request->token,
                                'invoice_no'                        => $invoiceId,
                                'client_markup'                     => $client_markup,
                                'admin_markup'                      => $admin_markup,
                                'client_markup_type'                => $client_markup_type,
                                'admin_markup_type'                 => $admin_markup_type,
                                'payable_price'                     => $payable_price,
                                'client_commission_amount'          => $client_commission_amount,
                                'admin_commission_amount'           => $admin_commission_amount,
                                'total_markup_price'                => $total_markup_price,
                                'currency'                          => $currency,
                                
                                'exchange_payable_price'            => $exchange_payable_price,
                                'exchange_client_commission_amount' => $exchange_client_commission_amount,
                                'exchange_total_markup_price'       => $exchange_total_markup_price,
                                'exchange_currency'                 => $exchange_currency,
                                'exchange_rate'                     => $exchange_rate,
                                
                                'admin_exchange_amount'             => $admin_exchange_amount,
                                'exchange_admin_commission_amount'  => $admin_commission_amount,
                                'admin_exchange_currency'           => $admin_exchange_currency,
                                'admin_exchange_rate'               => $admin_exchange_rate,
                                'admin_exchange_total_markup_price' => $admin_exchange_total_markup_price,
                                
                            ]);
                            
                            $admin_provider_payments_hotelbeds  = DB::table('admin_provider_payments')->latest()->first();
                            $payment_remaining_amount           = $admin_provider_payments_hotelbeds->payment_remaining_amount ?? '0';
                            $add_price                          = (float)$payment_remaining_amount + (float)$admin_exchange_total_markup_price;
                            
                            $admin_payments                     = DB::table('admin_provider_payments')->insert([
                                'payment_transction_id'         => $invoiceId,
                                'provider'                      => 'Sunhotel',
                                'payment_remaining_amount'      => $add_price,
                            ]);
                            
                            $customer_get_data                  = DB::table('customer_subcriptions')->where('Auth_key', $request->token)->first();
                            $credit_data                        = DB::table('credit_limits')->where('customer_id', $customer_get_data->id)->where('status_type', 'approved')->orderBy('id', 'DESC')->limit(1)->first();
                            
                            if (isset($credit_data)) {
                                $ramainAmount                   = $credit_data->remaining_amount - $request->creditAmount;
                                $currency                       = $credit_data->currency;
                            } else {
                                $ramainAmount                   = $request->creditAmount;
                                $currency                       = '';
                            }
                            
                            $credit_limits                      = DB::table('credit_limits')->insert([
                                'transection_id'                => $invoiceId,
                                'customer_id'                   => $customer_get_data->id,
                                'amount'                        => $request->creditAmount,
                                'total_amount'                  => $credit_data->total_amount ?? '',
                                'remaining_amount'              => $ramainAmount,
                                'currency'                      => $currency,
                                'status'                        => '1',
                                'status_type'                   => 'approved',
                            ]);
                            
                            $credit_limits                      = DB::table('credit_limit_transections')->insert([
                                'invoice_no'                    => $invoiceId,
                                'customer_id'                   => $customer_get_data->id,
                                'transection_amount'            => $request->creditAmount,
                                'remaining_amount'              => $ramainAmount,
                                'type'                          => 'booked',
                            ]);
                            
                            $booking_customer_data              = DB::table('booking_customers')->where('id', $booking_customer_id)->first();
                            
                            if ($booking_customer_data) {
                                $customer_balance = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                                
                                DB::table('booking_customers')->where('id', $booking_customer_id)->update(['balance' => $customer_balance]);
                                
                                DB::table('customer_ledger')->insert([
                                    'received'          => $request->admin_exchange_total_markup_price,
                                    'balance'           => $customer_balance,
                                    'booking_customer'  => $booking_customer_id,
                                    'hotel_invoice_no'  => $invoiceId,
                                    'date'              => date('Y-m-d'),
                                    'customer_id'       => $userData->id,
                                ]);
                                
                                if ($request->slc_pyment_method == 'slc_stripe') {
                                    
                                    $customer_balance = $customer_balance - $request->admin_exchange_total_markup_price;
                                    DB::table('booking_customers')->where('id', $booking_customer_id)->update([
                                        'balance' => $customer_balance
                                    ]);
                                    
                                    DB::table('customer_ledger')->insert([
                                        'payment'           => $request->admin_exchange_total_markup_price,
                                        'balance'           => $customer_balance,
                                        'booking_customer'  => $booking_customer_id,
                                        'hotel_invoice_no'  => $invoiceId,
                                        'payment_method'    => 'Stripe',
                                        'date'              => date('Y-m-d'),
                                        'customer_id'       => $userData->id
                                    ]);
                                }
                            }
                            $booking_data = DB::table('hotels_bookings')->where('invoice_no', $invoiceId)->first();
                            
                            return response()->json([
                                'status'        => 'success',
                                'Invoice_id'    => $invoiceId,
                                'Invoice_data'  => $booking_data
                            ]);
                        } catch (Throwable $e) {
                            DB::rollback();
                            echo $e;
                            return response()->json(['message' => 'error', 'booking_id' => '']);
                        }
                    }
                    else{
                        $result = DB::table('hotels_bookings')->insert([
                            'invoice_no'                    => $invoiceId,
                            'provider'                      => '',
                            'booking_customer_id'           => $booking_customer_id,
                            'exchange_currency'             => $request->exchange_currency_customer ?? $request->exchange_currency ?? '',
                            'exchange_price'                => $request->exchange_price,
                            'base_exchange_rate'            => $request->base_exchange_rate,
                            'base_currency'                 => $request->base_currency,
                            'selected_exchange_rate'        => $request->selected_exchange_rate,
                            'selected_currency'             => $request->selected_currency,
                            'GBP_currency'                  => $request->admin_exchange_currency,
                            'GBP_exchange_rate'             => $request->admin_exchange_rate,
                            'GBP_invoice_price'             => $request->admin_exchange_total_markup_price,
                            'creationDate'                  => '',
                            'status'                        => 'Failed',
                            'lead_passenger'                => '',
                            'lead_passenger_data'           => json_encode($lead_passenger_object),
                            'other_adults_data'             => json_encode($others_adults),
                            'childs_data'                   => json_encode($childs),
                            'status'                        => 'Failed',
                            'total_adults'                  => $customer_search_data->adult_searching,
                            'total_childs'                  => $customer_search_data->child_searching,
                            'total_rooms'                   => $customer_search_data->room_searching,
                            'reservation_request'           => json_encode($hotel_request_send),
                            'reservation_response'          => $result_booking_rs->error->message,
                            'actual_reservation_response'   => json_encode($result_booking_rs),
                            'customer_id'                   => $customer_id,
                            'payment_details'               => $request->payment_details,
                            'booking_type'                  => $request->booking_type, 'b2b_agent_id' => $request->b2b_agent_id
                        ]);
                        
                        return response()->json([
                            'status'    => 'error',
                            'message'   => $result_booking_rs->error->message ?? ''
                        ]);
                    }
                }else{
                    return response()->json(['message'=>'error','booking_id'=>'']);
                }
            }
            else {
                return 'Non-Refundable';
                
                $rooms_list         = $hotel_checkout_select->rooms_list;
                $slc_pyment_method  = $request->slc_pyment_method;
                if ($slc_pyment_method == 'slc_stripe') {
                    // return $rooms_list;
                    // return $hotel_request_data;
                    // return $hotel_checkout_select;
                    
                    $total_Adults   = 0;
                    $total_Rooms    = 0;
                    $total_Childs   = 0;
                    if(isset($rooms_list[0]) || count($rooms_list) > 1){
                        foreach($rooms_list as $val_RL){
                            $total_Adults   += $val_RL->adults;
                            $total_Rooms    += $val_RL->rooms_qty;
                            $total_Childs   += $val_RL->childs;
                        }
                    }
                    
                    $child_Ages_Arr = [];
                    if(isset($rooms_list[0]) && count($rooms_list) > 1){
                        foreach($rooms_list as $val_RL){
                            $child_Ages = $val_RL->child_Ages;
                            if(count($child_Ages) > 0){
                                for($cA=0; $cA<count($child_Ages); $cA++){
                                    array_push($child_Ages_Arr,$child_Ages[$cA]);
                                }
                            }
                        }
                    }else{
                        if(isset($rooms_list[0])){
                            foreach($rooms_list as $val_RL){
                                $child_Ages = $val_RL->child_Ages;
                                if (!empty($child_Ages[0])) {
                                    if(count($child_Ages) > 0){
                                        for($cA=0; $cA<count($child_Ages); $cA++){
                                            array_push($child_Ages_Arr,$child_Ages[$cA][0]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    // return $child_Ages_Arr;
                    
                    $responseData3 = SunHotel_Controller::sunHotelBook($hotel_request_data,$rooms_list[0],$others_adults,$childs,$child_Ages_Arr,$total_Rooms,$total_Adults,$total_Childs,$hotel_checkout_select);
                    // return $responseData3;
                    
                    if($responseData3 != 'Error'){
                        $hotel_request_send         = $responseData3['request'];
                        $result_booking_rs          = $responseData3['response'];
                        $booking_details_sunHotel   = SunHotel_Controller::sunHotelDetails($result_booking_rs['hotel.id']);
                        // return $booking_details_sunHotel;
                        
                        if (isset($booking_details_sunHotel['hotels']['hotel'])) {
                            $rooms_details_arr = [];
                            if (isset($rooms_list)) {
                                foreach ($rooms_list as $room_res) {
                                    
                                    // return $room_res->meals;
                                    
                                    $paxes_arr = [];
                                    
                                    if (count($others_adults) > 0) {
                                        foreach ($others_adults as $adult_Pax) {
                                            $paxes_arr[]    = [
                                                'type'      => 'Adult',
                                                'name'      => $adult_Pax->first_Name . " " . $adult_Pax->last_Name,
                                            ];
                                        }
                                    }
                                    
                                    if (count($childs) > 0) {
                                        foreach ($childs as $child_Pax) {
                                            $paxes_arr[] = [
                                                'type' => 'Child',
                                                'name' => $child_Pax->first_Name . " " . $child_Pax->last_Name,
                                            ];
                                        }
                                    }
                                    
                                    $cancliation_policy_arr = [];
                                    if ($room_res->cancliation_policy_arr) {
                                        $CancelPolicies = $room_res->cancliation_policy_arr;
                                        if (count($CancelPolicies) > 1) {
                                            for ($cc = 0; $cc < count($CancelPolicies); $cc++) {
                                                if($CancelPolicies[$cc]->amount > 0){
                                                    $cancel_tiem = (object)[
                                                        'amount'        => $CancelPolicies[$cc]->amount,
                                                        'from_date'     => $CancelPolicies[$cc]->from_date,
                                                    ];
                                                    $cancliation_policy_arr[] = $cancel_tiem;
                                                }
                                            }
                                        }else{
                                            if (count($CancelPolicies) > 0) {
                                                for ($cc = 0; $cc < count($CancelPolicies); $cc++) {
                                                    $cancellation_Date = date('d-m-Y', strtotime($CancelPolicies[$cc]->from_date));
                                                    if($cancellation_Date > $current_Date){
                                                        $cancel_tiem = (object)[
                                                            'amount'        => $CancelPolicies[$cc]->amount,
                                                            'from_date'     => $CancelPolicies[$cc]->from_date,
                                                        ];
                                                        $cancliation_policy_arr[] = $cancel_tiem;
                                                    }else{
                                                        if($CancelPolicies[$cc]->from_date > 0){
                                                            $cancel_tiem = (object)[
                                                                'amount'        => $CancelPolicies[$cc]->amount,
                                                                'from_date'     => $CancelPolicies[$cc]->from_date,
                                                            ];
                                                            $cancliation_policy_arr[] = $cancel_tiem;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    
                                    $room_rate_arr[] = (object)[
                                        'rateClass'             => $room_res->rateClass ?? '',
                                        'net'                   => $room_res->rooms_selling_price ?? '',
                                        'rateComments'          => $room_res->rateComments ?? '',
                                        'room_board'            => $room_res->board_id ?? '',
                                        'room_qty'              => $room_res->rooms_qty,
                                        'adults'                => $room_res->adults,
                                        'children'              => $room_res->childs,
                                        'cancellation_policy'   => $cancliation_policy_arr,
                                    ];
                                    
                                    $rooms_details_arr[] = (object)[
                                        'room_stutus'   => 'Confirm',
                                        'room_code'     => $room_res->room_code,
                                        'room_name'     => $room_res->room_name,
                                        'room_paxes'    => $paxes_arr,
                                        'room_rates'    => $room_rate_arr,
                                    ];
                                }
                            }
                            
                            $hotel_checkout_request_d   = json_decode($request->hotel_checkout_select);
                            $hotel_booking_conf_res     = (object)[
                                'provider'              => 'Sunhotel',
                                'admin_markup'          => $hotel_checkout_request_d->admin_markup,
                                'customer_markup'       => $hotel_checkout_request_d->customer_markup,
                                'admin_markup_type'     => $hotel_checkout_request_d->admin_markup_type,
                                'customer_markup_type'  => $hotel_checkout_request_d->customer_markup_type,
                                'reference_no'          => $result_booking_rs['bookingnumber'],
                                'total_price'           => $request->creditAmount,
                                'hotel_currency'        => $request->base_currency,
                                'clientReference'       => $result_booking_rs['bookingnumber'],
                                'creationDate'          => date('Y-m-d'),
                                'lead_passenger'        => $request->lead_first_name . '' . $request->lead_last_name,
                                'status'                => 'Confirm',
                                'hotel_details'         => (object)[
                                    'checkIn'           => date('Y-m-d',strtotime($result_booking_rs['checkindate'])),
                                    'checkOut'          => date('Y-m-d',strtotime($result_booking_rs['checkoutdate'])),
                                    'hotel_code'        => $hotel_checkout_request_d->hotel_id,
                                    'hotel_name'        => $result_booking_rs['hotel.name'],
                                    'stars_rating'      => $result_booking_rs['Rating'] ?? '',
                                    'destinationCode'   => $hotel_checkout_request_d->destinationCode,
                                    'destinationName'   => $hotel_checkout_request_d->destinationName,
                                    'latitude'          => $hotel_checkout_request_d->latitude,
                                    'longitude'         => $hotel_checkout_request_d->longitude,
                                    'rooms'             => $rooms_details_arr
                                ]
                            ];
                            
                            // return $hotel_booking_conf_res;
                            
                            try {
                                $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no'                    => $invoiceId,
                                    'provider'                      => $hotel_booking_conf_res->provider,
                                    'booking_customer_id'           => $booking_customer_id,
                                    'exchange_currency'             => $request->exchange_currency_customer ?? $request->exchange_currency ?? '',
                                    'exchange_price'                => $request->exchange_price,
                                    'base_exchange_rate'            => $request->base_exchange_rate,
                                    'base_currency'                 => $request->base_currency,
                                    'selected_exchange_rate'        => $request->selected_exchange_rate,
                                    'selected_currency'             => $request->selected_currency,
                                    'GBP_currency'                  => $request->admin_exchange_currency,
                                    'GBP_exchange_rate'             => $request->admin_exchange_rate,
                                    'GBP_invoice_price'             => $request->admin_exchange_total_markup_price,
                                    // 'creationDate'                  => $hotel_booking_conf_res->creationDate,
                                    'creationDate'                  => date('Y-m-d'),
                                    'status'                        => $hotel_booking_conf_res->status,
                                    'lead_passenger'                => $hotel_booking_conf_res->lead_passenger,
                                    'lead_passenger_data'           => json_encode($lead_passenger_object),
                                    'other_adults_data'             => json_encode($others_adults),
                                    'childs_data'                   => json_encode($childs),
                                    'status'                        => $hotel_booking_conf_res->status,
                                    'total_adults'                  => $customer_search_data->adult_searching,
                                    'total_childs'                  => $customer_search_data->child_searching,
                                    'total_rooms'                   => $customer_search_data->room_searching,
                                    'reservation_request'           => json_encode($hotel_request_send),
                                    'reservation_response'          => json_encode($hotel_booking_conf_res),
                                    'actual_reservation_response'   => json_encode($result_booking_rs),
                                    'customer_id'                   => $customer_id,
                                    'payment_details'               => $request->payment_details,
                                    'booking_type'                  => $request->booking_type,
                                    'b2b_agent_id'                  => $request->b2b_agent_id
                                ]);
                                
                                $client_markup                      = $request->client_markup;
                                $admin_markup                       = $request->admin_markup;
                                $client_markup_type                 = $request->client_markup_type;
                                $admin_markup_type                  = $request->admin_markup_type;
                                $payable_price                      = $request->payable_price;
                                $client_commission_amount           = $request->client_commission_amount;
                                $total_markup_price                 = $request->total_markup_price;
                                $currency                           = $request->currency;
                                $exchange_client_commission_amount  = $request->exchange_client_commission_amount;
                                $exchange_payable_price             = $request->exchange_payable_price;
                                $exchange_admin_commission_amount   = $request->admin_commission_amount;
                                $exchange_total_markup_price        = $request->exchange_total_markup_price;
                                $exchange_currency                  = $request->exchange_currency;
                                $exchange_rate                      = $request->exchange_rate;
                                $admin_exchange_amount              = $request->admin_exchange_amount;
                                $admin_commission_amount            = $request->admin_commission_amount;
                                $admin_exchange_currency            = $request->admin_exchange_currency;
                                $admin_exchange_rate                = $request->admin_exchange_rate;
                                $admin_exchange_total_markup_price  = $request->admin_exchange_total_markup_price;
                                $price                              = $request->exchange_price;
                                $p_price                            = json_decode($price);
                                
                                $get_hotel_customer_ledgers         = DB::table('hotel_customer_ledgers')->orderBy('id', 'DESC')->where('token', $request->token)->limit(1)->first();
                                $sum_hotel_customer_ledgers         = $get_hotel_customer_ledgers->balance_amount ?? '0';
                                $big_exchange_payable_price         = (float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                
                                $hotel_customer_ledgers             = DB::table('hotel_customer_ledgers')->insert([
                                    'token'                         => $request->token,
                                    'invoice_no'                    => $invoiceId,
                                    'received_amount'               => $exchange_payable_price,
                                    'balance_amount'                => $big_exchange_payable_price,
                                    'type'                          => 'hotel_booking'
                                ]);
                                
                                $manage_customer_markups                = DB::table('manage_customer_markups')->insert([
                                    'token'                             => $request->token,
                                    'invoice_no'                        => $invoiceId,
                                    'client_markup'                     => $client_markup,
                                    'admin_markup'                      => $admin_markup,
                                    'client_markup_type'                => $client_markup_type,
                                    'admin_markup_type'                 => $admin_markup_type,
                                    'payable_price'                     => $payable_price,
                                    'client_commission_amount'          => $client_commission_amount,
                                    'admin_commission_amount'           => $admin_commission_amount,
                                    'total_markup_price'                => $total_markup_price,
                                    'currency'                          => $currency,
                                    'exchange_payable_price'            => $exchange_payable_price,
                                    'exchange_client_commission_amount' => $exchange_client_commission_amount,
                                    'exchange_total_markup_price'       => $exchange_total_markup_price,
                                    'exchange_currency'                 => $exchange_currency,
                                    'exchange_rate'                     => $exchange_rate,
                                    'admin_exchange_amount'             => $admin_exchange_amount,
                                    'exchange_admin_commission_amount'  => $admin_commission_amount,
                                    'admin_exchange_currency'           => $admin_exchange_currency,
                                    'admin_exchange_rate'               => $admin_exchange_rate,
                                    'admin_exchange_total_markup_price' => $admin_exchange_total_markup_price,
                                ]);
                                
                                $admin_provider_payments_hotelbeds  = DB::table('admin_provider_payments')->latest()->first();
                                $payment_remaining_amount           = $admin_provider_payments_hotelbeds->payment_remaining_amount ?? '0';
                                $add_price                          = (float)$payment_remaining_amount + (float)$admin_exchange_total_markup_price;
                                
                                $admin_payments                     = DB::table('admin_provider_payments')->insert([
                                    'payment_transction_id'         => $invoiceId,
                                    'provider'                      => 'Sunhotel',
                                    'payment_remaining_amount'      => $add_price,
                                ]);
                                
                                // Added Balance To Customer Ledger
                                $booking_customer_data = DB::table('booking_customers')->where('id', $booking_customer_id)->first();
                                if ($booking_customer_data) {
                                    $customer_balance = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                                    
                                    DB::table('booking_customers')->where('id', $booking_customer_id)->update([
                                        'balance' => $customer_balance
                                    ]);
                                    
                                    DB::table('customer_ledger')->insert([
                                        'received'          => $request->admin_exchange_total_markup_price,
                                        'balance'           => $customer_balance,
                                        'booking_customer'  => $booking_customer_id,
                                        'hotel_invoice_no'  => $invoiceId,
                                        'date'              => date('Y-m-d'),
                                        'customer_id'       => $userData->id
                                    ]);
                                    
                                    if ($request->slc_pyment_method == 'slc_stripe') {
                                        
                                        $customer_balance = $customer_balance - $request->admin_exchange_total_markup_price;
                                        DB::table('booking_customers')->where('id', $booking_customer_id)->update([
                                            'balance' => $customer_balance
                                        ]);
                                        
                                        DB::table('customer_ledger')->insert([
                                            'payment'           => $request->admin_exchange_total_markup_price,
                                            'balance'           => $customer_balance,
                                            'booking_customer'  => $booking_customer_id,
                                            'hotel_invoice_no'  => $invoiceId,
                                            'payment_method'    => 'Stripe',
                                            'date'              => date('Y-m-d'),
                                            'customer_id'       => $userData->id
                                        ]);
                                    }
                                }
                                
                                // return 'response';
                                
                                $booking_data = DB::table('hotels_bookings')->where('invoice_no', $invoiceId)->first();
                                if ($result) {
                                    return response()->json([
                                        'status'        => 'success',
                                        'Invoice_id'    => $invoiceId,
                                        'Invoice_data'  => $booking_data
                                    ]);
                                }
                                
                            }
                            catch (Throwable $e) {
                                DB::rollback();
                                echo $e;
                                return response()->json(['message' => 'error', 'booking_id' => '']);
                            }
                        }
                        else {
                            // return 'ELSE BOOKING FAILED';
                            $result                             = DB::table('hotels_bookings')->insert([
                                'invoice_no'                    => $invoiceId,
                                'provider'                      => '',
                                'booking_customer_id'           => $booking_customer_id,
                                'exchange_currency'             => $request->exchange_currency_customer ?? $request->exchange_currency ?? '',
                                'exchange_price'                => $request->exchange_price,
                                'base_exchange_rate'            => $request->base_exchange_rate,
                                'base_currency'                 => $request->base_currency,
                                'selected_exchange_rate'        => $request->selected_exchange_rate,
                                'selected_currency'             => $request->selected_currency,
                                'GBP_currency'                  => $request->admin_exchange_currency,
                                'GBP_exchange_rate'             => $request->admin_exchange_rate,
                                'GBP_invoice_price'             => $request->admin_exchange_total_markup_price,
                                'creationDate'                  => date('Y-m-d'),
                                'status'                        => 'Failed',
                                'lead_passenger'                => '',
                                'lead_passenger_data'           => json_encode($lead_passenger_object),
                                'other_adults_data'             => json_encode($others_adults),
                                'childs_data'                   => json_encode($childs),
                                'total_adults'                  => $customer_search_data->adult_searching,
                                'total_childs'                  => $customer_search_data->child_searching,
                                'total_rooms'                   => $customer_search_data->room_searching,
                                'reservation_request'           => json_encode($hotel_request_send),
                                'reservation_response'          => $result_booking_rs->error->message ?? 'Error',
                                'actual_reservation_response'   => json_encode($result_booking_rs),
                                'customer_id'                   => $customer_id,
                                'payment_details'               => $request->payment_details,
                                'booking_type'                  => $request->booking_type,
                                'b2b_agent_id'                  => $request->b2b_agent_id
                            ]);
                            
                            return response()->json([
                                'status'    => 'error',
                                'message'   => $result_booking_rs->error->message ?? 'Error'
                            ]);
                        }
                    }else{
                        return response()->json(['message'=>'error','booking_id'=>'']);
                    }
                }
                else {
                    // return 'ELSE ELSE';
                    try {
                        $result = DB::table('hotels_bookings')->insert([
                            'invoice_no'                => $invoiceId,
                            'booking_customer_id'       => $booking_customer_id,
                            'provider'                  => $hotel_checkout_select->hotel_provider,
                            'exchange_currency'         => $request->exchange_currency,
                            'exchange_price'            => $request->exchange_price,
                            'base_exchange_rate'        => $request->base_exchange_rate,
                            'base_currency'             => $request->base_currency,
                            'selected_exchange_rate'    => $request->selected_exchange_rate,
                            'selected_currency'         => $request->selected_currency,
                            'GBP_currency'              => $request->admin_exchange_currency,
                            'GBP_exchange_rate'         => $request->admin_exchange_rate,
                            'GBP_invoice_price'         => $request->admin_exchange_total_markup_price,
                            'creationDate'              => date('Y-m-d'),
                            'status'                    => "non_refundable",
                            'lead_passenger'            => $lead_passenger_object->lead_first_name . " " . $lead_passenger_object->lead_last_name,
                            'lead_passenger_data'       => json_encode($lead_passenger_object),
                            'other_adults_data'         => json_encode($others_adults),
                            'childs_data'               => json_encode($childs),
                            'total_adults'              => $customer_search_data->adult_searching,
                            'total_childs'              => $customer_search_data->child_searching,
                            'total_rooms'               => $customer_search_data->room_searching,
                            'reservation_request'       => '',
                            'reservation_response'      => '',
                            'all_checkout_request_data' => json_encode($request->all()),
                            'hotel_request_data'        => json_encode($hotel_request_data),
                            'hotel_data'                => json_encode($hotel_checkout_select),
                            'customer_id'               => $customer_id,
                            'booking_type'              => $request->booking_type, 'b2b_agent_id' => $request->b2b_agent_id
                        ]);
                        
                        $booking_data = DB::table('hotels_bookings')->where('invoice_no', $invoiceId)->first();
                        
                        if ($result) {
                            return response()->json([
                                'status'        => 'success_non_refundable',
                                'Invoice_id'    => $invoiceId,
                                'Invoice_data'  => $booking_data
                            ]);
                        }
                        
                    } catch (Throwable $e) {
                        echo $e;
                        return response()->json(['message'=>'error','booking_id'=>'']);
                    }
                }
            }
        }
        
        // Hasanat Points Working
        // $b2b_Agent_Id = $request->b2b_agent_id ?? '260';
        if(config('token_Alif') == $request->token){
            if($hotel_checkout_select->hotel_provider != 'Custome_hotel' && $hotel_checkout_select->hotel_provider != 'custom_hotel_provider'){
                if(isset($request->hasanat_Point_Exist) && $request->hasanat_Point_Exist  == 'membership'){
                    $b2b_agents                 = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->b2b_agent_id)->first();
                    // return $b2b_agents;
                    
                    DB::table('3rd_Party_Booking_Hasanat_Coins')->insert([
                        'token'                 => $request->token,
                        'customer_id'           => $userData->id,
                        'booking_Id'            => $invoiceId,
                        'b2b_Agent_Id'          => $request->b2b_agent_id,
                        'package_Id'            => $b2b_agents->select_Package,
                        'discount_Id'           => $request->discount_Id ?? NULL,
                        'booked_Hasanat_Coins'  => $request->booked_Hasanat_Coins,
                    ]);
                    
                    // $request->discount_Id = NULL;
                    // $booked_Hasanat_Coins = $request->booked_Hasanat_Coins ?? '200';
                    if(isset($request->discount_Id) && $request->discount_Id != null && $request->discount_Id != '' && $request->discount_Id > 0){
                        $remaining_Hasanat_Points = $b2b_agents->total_Hasanat_Points - $request->booked_Hasanat_Coins;
                        DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->b2b_agent_id)->update(['total_Hasanat_Points'=>$remaining_Hasanat_Points]);
                    }else{
                        $remaining_Hasanat_Points = $b2b_agents->total_Hasanat_Points + $request->booked_Hasanat_Coins;
                        DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->b2b_agent_id)->update(['total_Hasanat_Points'=>$remaining_Hasanat_Points]);
                    }
                }
            }
        }
        // Hasanat Points Working
        
        $status_RB = $room_book_status ?? 'Confirmed';
        if(isset($response_Status) && $response_Status != 'error'){
            $check_Mail         = self::MailSend($request,$invoiceId,$status_RB);
            // if($check_Mail == 'Success'){
                $booking_data                                   = DB::table('hotels_bookings')->where('invoice_no',$invoiceId)->first();
                $customer_subcriptions                          = DB::table('customer_subcriptions')->where('id',$booking_data->customer_id)->first();
                
                $notification_insert                            = new alhijaz_Notofication();
                $notification_insert->type_of_notification_id   = $booking_data->id ?? '';
                $notification_insert->customer_id               = $booking_data->customer_id ?? '';
                $notification_insert->type_of_notification      = 'Hotel_Booking';
                $notification_insert->generate_id               = $booking_data->invoice_no ?? '';
                $notification_insert->notification_creator_name = $booking_data->lead_passenger ?? $customer_subcriptions->company_name ?? '';
                $notification_insert->total_price               = $booking_data->exchange_price ?? '';
                $notification_insert->amount_paid               = 0;
                $notification_insert->remaining_price           = $booking_data->exchange_price ?? '';
                $notification_insert->notification_status       = '1';
                $notification_insert->save();
                
                return response()->json([
                    'status'        => $response_Status ?? 'error',
                    'Invoice_id'    => $invoiceId ?? '',
                    'Invoice_data'  => $booking_data ?? '',
                ]);
            // }else{
            //     return response()->json([
            //         'status'        => 'Mail Sending Failed',
            //         'Invoice_id'    => $invoiceId ?? '',
            //         'Invoice_data'  => $booking_data ??'',
            //     ]);
            // }
        }else{
            return response()->json([
                'status'        => 'error',
                'Invoice_id'    => '',
                'Invoice_data'  => '',
            ]);
        }
        
        // return $check_Mail;
    }
    
    public function get_Meal_Types_Custom_Hotel(Request $request){
        try {
            $customer_Data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data != null){
                
                // return $request;
                
                $hotel_Details  = DB::table('hotels')->where('owner_id',$customer_Data->id)->where('id',$request->hotel_Id)->first();
                $meal_Types     = DB::table('meal_Types')->where('token',$request->token)->where('selected_Hotel',$request->hotel_Id)
                                    ->where('start_Date','<=',$request->start_Date)->where('end_Date','>=',$request->end_Date)->get();
                
                return response()->json([
                    'status'        => 'success',
                    'hotel_Details' => $hotel_Details,
                    'meal_Types'    => $meal_Types,
                ]);
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => "Token Not Matched",
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong',
            ]);
        }
    }
    
    public static function MailSend($request,$invoiceId,$room_book_status){
        $mail_Send_Status   = false;
        
        // Haramayn Hotels Hotel Mail
        if($request->token == config('token_HaramynHotel')){
            $from_Address       = config('mail_From_Address_HaramynHotel');
            $website_Title      = config('mail_Title_HaramynHotel');
            $mail_Template_Key  = config('mail_Template_Key_HaramynHotel');
            $website_Url        = config('website_Url_HaramynHotel').'hotel-booking-invoice';
            $mail_Send_Status   = true;
        }
        
        // Alhijaz Rooms Mail
        if($request->token == config('token_AlhijazRooms')){
            $from_Address       = config('mail_From_Address_AlhijazRooms');
            $website_Title      = config('mail_Title_AlhijazRooms');
            $mail_Template_Key  = config('mail_Template_Key_AlhijazRooms');
            $website_Url        = config('website_Url_AlhijazRooms').'hotel_voucher';
            $mail_Send_Status   = true;
        }
        
        // Sidra Tours Mail
        if($request->token == config('token_SidraTours')){
            $from_Address       = config('mail_From_Address_SidraTours');
            $website_Title      = config('mail_Title_SidraTours');
            $mail_Template_Key  = config('mail_Template_Key_SidraTours');
            $website_Url        = config('website_Url_SidraTours').'hotel_voucher';
            $mail_Send_Status   = true;
        }
        
        // Alsubaee Mail
        if($request->token == config('token_Alsubaee')){
            $from_Address               = config('mail_From_Address_Alsubaee');
            $website_Title              = config('mail_Title_Alsubaee');
            $mail_Template_Key          = config('mail_Template_Key_Alsubaee');
            $website_Url                = config('website_Url_Alsubaee').'hotel_voucher';
            $mail_Address_Register_BPC  = config('mail_Address_Register_BPC');
            // $mail_Address_Register_BPC  = 'ua758323@gmail.com';
            $mail_Send_Status           = true;
        }
        
        // Alif Mail
        if($request->token == config('token_Alif')){
            $from_Address               = config('mail_From_Address_Alif');
            $website_Title              = config('mail_Title_Alif');
            $mail_Template_Key          = config('mail_Template_Key_Alif');
            $website_Url                = config('website_Url_Alif').'hotel_voucher';
            $mail_Address_Admin         = config('mail_Address_Admin');
            $mail_Send_Status           = true;
        }
        
        // Umrah Shop
        if($request->token == config('token_UmrahShop')){
            $from_Address       = config('mail_From_Address_UmrahShop');
            $website_Title      = config('mail_Title_UmrahShop');
            $mail_Template_Key  = config('mail_Template_Key_UmrahShop');
            $website_Url        = config('website_Url_UmrahShop').'hotel_voucher';
            $mail_Send_Status   = true;
        }
        
        // Hashim Travel
        if($request->token == config('token_HashimTravel')){
            $from_Address       = config('mail_From_Address_HashimTravel');
            $website_Title      = config('mail_Title_HashimTravel');
            $mail_Template_Key  = config('mail_Template_Key_HashimTravel');
            $website_Url        = config('website_Url_HashimTravel').'hotel_voucher';
            $mail_Send_Status   = true;
        }
        
        // Haramayn Rooms
        if($request->token == config('token_HaramaynRooms')){
            $from_Address       = config('mail_From_Address_HaramaynRooms');
            $website_Title      = config('mail_Title_HaramaynRooms');
            $mail_Template_Key  = config('mail_Template_Key_HaramaynRooms');
            $website_Url        = config('website_Url_HaramaynRooms').'hotel_voucher';
            $mail_Send_Status   = true;
            $room_book_status   = 'Tentative';
        }
        
        // Almnhaj Hotels
        if($request->token == config('token_AlmnhajHotel')){
            $from_Address       = config('mail_From_Address_AlmnhajHotel');
            $website_Title      = config('mail_Title_AlmnhajHotel');
            $mail_Template_Key  = config('mail_Template_Key_AlmnhajHotel');
            $website_Url        = config('website_Url_AlmnhajHotel').'hotel_voucher';
            $mail_Send_Status   = true;
            $room_book_status   = 'Tentative';
        }
        
        if($mail_Send_Status != false){
            $currency                   = $request->exchange_currency ?? 'SAR';
            $total_Price                = $request->exchange_price ?? '0';
            $booking_Date               = Carbon::now();
            $formatted_Booking_Date     = $booking_Date->format('d-m-Y H:i:s');
            
            $hotel_checkout_select      = json_decode($request->hotel_checkout_select);
            // return $hotel_checkout_select;
            $room_Details               = $hotel_checkout_select->rooms_list;
            $room_Message_Mail          = '';
            foreach($room_Details as $val_RD){
                $room_name              = $val_RD->room_name;
                $meal_Type              = $val_RD->board_id;
                $room_Message_Mail      .= '<li>Room Type: '.$room_name.'</li><li>Meal Type: '.$meal_Type.'</li>';
            }
            
            $lead_title                 = 'MR';
            $lead_passenger_data        = json_decode($request->request_data);
            if(isset($lead_passenger_data->lead_title) && $lead_passenger_data->lead_title != null && $lead_passenger_data->lead_title != ''){
                if($lead_passenger_data->lead_title == 'Mr' || $lead_passenger_data->lead_title == 'MR'){
                    $lead_title         = 'MR';
                }else{
                    $lead_title         = 'MRS';
                }
            }
            
            $lead_email                 = $lead_passenger_data->lead_email;
            $lead_first_name            = $lead_passenger_data->lead_first_name;
            $lead_last_name             = $lead_passenger_data->lead_last_name;
            $lead_phone                 = $lead_passenger_data->lead_phone;
            
            $customer_search_data       = json_decode($request->customer_search_data);
            $check_in                   = Carbon::createFromFormat('Y-m-d', $customer_search_data->check_in);
            $formatted_Check_In         = $check_in->format('d-m-Y');
            $check_out                  = Carbon::createFromFormat('Y-m-d', $customer_search_data->check_out);
            $formatted_Check_Out        = $check_out->format('d-m-Y');
            
            $website_Invoice            = $website_Url.'/'.$invoiceId;
            
            $details                    = [
                'status'                => $room_book_status,
                'invoice_no'            => $invoiceId,
                'lead_title'            => $lead_title,
                'lead_Name'             => $lead_first_name,
                'email'                 => $lead_email,
                'contact'               => $lead_phone,
                'booking_Date'          => $formatted_Booking_Date,
                'Check_In'              => $formatted_Check_In,
                'Check_Out'             => $formatted_Check_Out,
                'no_Of_Rooms'           => $customer_search_data->room_searching ?? '0',
                'no_Of_Adults'          => $customer_search_data->adult_searching ?? '0',
                'price'                 => $currency.' '.$total_Price,
                'hotel_Name'            => $hotel_checkout_select->hotel_name ?? '',
                'room_Message_Mail'     => $room_Message_Mail,
            ];
            // return $details;
            
            $email_Message      = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> <h4> Your booking has been '.$room_book_status.'! Thank you for choosing our service.Below are the details of your booking:</h4> <ul><li>Invoice No: '.$details['invoice_no'].' </li> <li>Booking Date: '.$details['booking_Date'].'</li> <li>Check-in Date: '.$details['Check_In'].' </li><li>Check-out Date: '.$details['Check_Out'].' </li><li>Number of Rooms: '.$details['no_Of_Rooms'].' </li><li>Number of Adults: '.$details['no_Of_Adults'].' </li></ul> <h3>Room Details:</h3><ul><li>Hotel Name: '.$details['hotel_Name'].' </li>'.$details['room_Message_Mail'].'<li>Price: '.$details['price'].' </li><li> Room Status: '.$details['status'].' </li></ul> <h3>Customer Details:</h3><ul><li>Name: '.$details['lead_title'].' '.$details['lead_Name'].' </li><li>Email: '.$details['email'].' </li><li>Phone Number: '.$details['contact'].' </li></ul> <h4>We look forward to providing you with a comfortable and enjoyable stay. Should you have any questions or require further assistance, please do not hesitate to contact us. <br>Thank you for choosing '.$website_Title.' </h4> <br> Invoice Link : '.$website_Invoice.' </div>';
            // $from_Address       = 'noreply@alhijazrooms.com';
            // $to_Address         = 'ua758323@gmail.com';
            $to_Address         = $lead_email;
            $reciever_Name      = $lead_first_name;
            if($request->token == config('token_Alsubaee')){
                $mail_Check             = Mail3rdPartyController::mail_Check_Alsubaee($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                
                $email_Message_Register  = '<div> <h3> Dear '.$website_Title.', </h3> A Booking has been Generated at '.$website_Title.' B2B portal for '.$details['hotel_Name'].' ('.$details['invoice_no'].'). <br> Below are the details of Booking: <br> <ul><li>Invoice No: '.$details['invoice_no'].' </li> <li>Booking Date: '.$details['booking_Date'].'</li> <li>Check-in Date: '.$details['Check_In'].' </li><li>Check-out Date: '.$details['Check_Out'].' </li><li>Number of Rooms: '.$details['no_Of_Rooms'].' </li><li>Number of Adults: '.$details['no_Of_Adults'].' </li></ul> <h3>Room Details:</h3><ul><li>Hotel Name: '.$details['hotel_Name'].' </li>'.$details['room_Message_Mail'].'<li>Price: '.$details['price'].' </li><li> Room Status: '.$details['status'].' </li></ul> <h3>Customer Details:</h3><ul><li>Name: '.$details['lead_title'].' '.$details['lead_Name'].' </li><li>Email: '.$details['email'].' </li><li>Phone Number: '.$details['contact'].' </li></ul> <h4> Thank you </h4> </div>';
                $mail_Check_Alsubaee    = Mail3rdPartyController::mail_Check_Alsubaee($from_Address,$mail_Address_Register_BPC,$reciever_Name,$email_Message_Register,$mail_Template_Key);
                
                if($room_book_status == 'Tentative'){
                    $email_Message      = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> <h4> Option date for a tentative booking '.$details['invoice_no'].' is due in few hours. Please make payment as soon as possible to avoid cancellation.</h4> <h4>We look forward to providing you with a comfortable and enjoyable stay. Should you have any questions or require further assistance, please do not hesitate to contact us. <br>Thank you for choosing '.$website_Title.' </h4> <br> Invoice Link : '.$website_Invoice.' </div>';
                     $mail_Check             = Mail3rdPartyController::mail_Check_Alsubaee($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                }
            }
            else{
                // Leas Passenger
                $mail_Check                     = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                // return $mail_Check;
                
                if($request->token == config('token_Alif')){
                    // Client Points
                    if($hotel_checkout_select->hotel_provider != 'Custome_hotel' && $hotel_checkout_select->hotel_provider != 'custom_hotel_provider'){
                        if(isset($request->hasanat_Point_Exist) && $request->hasanat_Point_Exist  == 'membership'){
                            $b2b_agents             = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->b2b_agent_id)->first();
                            $mail_Client            = $b2b_agents->email;
                            $subscriptions_Packages = DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$b2b_agents->select_Package)->first();
                            $booked_Hasanat_Coins   = $request->booked_Hasanat_Coins ?? '0';
                            $email_Message_Client   = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> <h4> Your booking has been '.$room_book_status.'! Thank you for choosing our service.Below are the details of your booking:</h4> <ul><li>Invoice No: '.$details['invoice_no'].' </li> <li>Booking Date: '.$details['booking_Date'].'</li> <li>Check-in Date: '.$details['Check_In'].' </li><li>Check-out Date: '.$details['Check_Out'].' </li><li>Number of Rooms: '.$details['no_Of_Rooms'].' </li><li>Number of Adults: '.$details['no_Of_Adults'].' </li></ul> <h3>Room Details:</h3><ul><li>Hotel Name: '.$details['hotel_Name'].' </li>'.$details['room_Message_Mail'].' <li> Room Status: '.$details['status'].' </li></ul> <h3>Points Details:</h3> <ul><li>Total Poins: '.$subscriptions_Packages->number_Of_Hasanat_Coins.' </li> <li>Booking Reference: '.$details['invoice_no'].' </li> <li>Points Used: '.$booked_Hasanat_Coins.' </li> <li>Remaining Points: '.$b2b_agents->total_Hasanat_Points.' </li> </ul> <h3>Customer Details:</h3><ul><li>Name: '.$details['lead_title'].' '.$details['lead_Name'].' </li><li>Email: '.$details['email'].' </li><li>Phone Number: '.$details['contact'].' </li></ul> <h4>We look forward to providing you with a comfortable and enjoyable stay. Should you have any questions or require further assistance, please do not hesitate to contact us. <br>Thank you for choosing '.$website_Title.' </h4> <br> Invoice Link : '.$website_Invoice.' </div>';
                            $mail_Check_Alif_Client = Mail3rdPartyController::mail_Check_All($from_Address,$mail_Client,$reciever_Name,$email_Message_Client,$mail_Template_Key);
                            return $mail_Check_Alif_Client;
                        }
                    }
                    
                    // Client Credits
                    if($request->slc_pyment_method == 'membership'){
                        $b2b_agents             = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->b2b_agent_id)->first();
                        $mail_Client            = $b2b_agents->email;
                        $subscriptions_Packages = DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$b2b_agents->select_Package)->first();
                        
                        $total_Credits          = $subscriptions_Packages->number_Of_Credits ?? '0';
                        $hasanat_TC             = DB::table('hasanat_Credits')->where('token',$request->token)->where('selected_Client',$request->b2b_agent_id)->where('selected_Package',$subscriptions_Packages->id)->get();
                        if($hasanat_TC->isEmpty()){
                        }else{
                            foreach($hasanat_TC as $val_TC){
                                $total_Credits  += $val_TC->add_Credits ?? '0';
                            }
                        }
                        
                        $booked_Credits         = 0;
                        $hasanat_BC             = DB::table('custom_Booking_Hasanat_Credits')->where('token',$request->token)->where('b2b_Agent_Id',$request->b2b_agent_id)->get();
                        if($hasanat_BC->isEmpty()){
                        }else{
                            foreach($hasanat_BC as $val_BC){
                                $booked_Credits += $val_BC->booked_Credits ?? '0';
                            }
                        }
                        
                        $email_Message_Client   = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> <h4> Your booking has been '.$room_book_status.'! Thank you for choosing our service.Below are the details of your booking:</h4> <ul><li>Invoice No: '.$details['invoice_no'].' </li> <li>Booking Date: '.$details['booking_Date'].'</li> <li>Check-in Date: '.$details['Check_In'].' </li><li>Check-out Date: '.$details['Check_Out'].' </li><li>Number of Rooms: '.$details['no_Of_Rooms'].' </li><li>Number of Adults: '.$details['no_Of_Adults'].' </li></ul> <h3>Room Details:</h3><ul><li>Hotel Name: '.$details['hotel_Name'].' </li>'.$details['room_Message_Mail'].' <li> Room Status: '.$details['status'].' </li></ul> <h3>Credits Details:</h3> <ul><li>Total Credits: '.$total_Credits.' </li> <li>Booking Reference: '.$details['invoice_no'].' </li> <li>Credit Used: '.$booked_Credits.' </li> <li>Remaining Credits: '.$b2b_agents->total_Hasanat_Credits.' </li> </ul> <h3>Customer Details:</h3><ul><li>Name: '.$details['lead_title'].' '.$details['lead_Name'].' </li><li>Email: '.$details['email'].' </li><li>Phone Number: '.$details['contact'].' </li></ul> <h4>We look forward to providing you with a comfortable and enjoyable stay. Should you have any questions or require further assistance, please do not hesitate to contact us. <br>Thank you for choosing '.$website_Title.' </h4> <br> Invoice Link : '.$website_Invoice.' </div>';
                        $mail_Check_Alif_Client = Mail3rdPartyController::mail_Check_All($from_Address,$mail_Client,$reciever_Name,$email_Message_Client,$mail_Template_Key);
                    }
                    
                    // Admin
                    $email_Message_Register     = '<div> <h3> Dear '.$website_Title.', </h3> A Booking has been Generated at '.$website_Title.' B2B portal for '.$details['hotel_Name'].' ('.$details['invoice_no'].'). <br> Below are the details of Booking: <br> <ul><li>Invoice No: '.$details['invoice_no'].' </li> <li>Booking Date: '.$details['booking_Date'].'</li> <li>Check-in Date: '.$details['Check_In'].' </li><li>Check-out Date: '.$details['Check_Out'].' </li><li>Number of Rooms: '.$details['no_Of_Rooms'].' </li><li>Number of Adults: '.$details['no_Of_Adults'].' </li></ul> <h3>Room Details:</h3><ul><li>Hotel Name: '.$details['hotel_Name'].' </li>'.$details['room_Message_Mail'].'<li>Price: '.$details['price'].' </li><li> Room Status: '.$details['status'].' </li></ul> <h3>Customer Details:</h3><ul><li>Name: '.$details['lead_title'].' '.$details['lead_Name'].' </li><li>Email: '.$details['email'].' </li><li>Phone Number: '.$details['contact'].' </li></ul> <h4> Thank you </h4> </div>';
                    $mail_Check_Alif            = Mail3rdPartyController::mail_Check_All($from_Address,$mail_Address_Admin,$reciever_Name,$email_Message_Register,$mail_Template_Key);
                }
                
                if($request->token == config('token_UmrahShop')){
                    $to_Address                 = 'admin@umrahshop.com';
                    $mail_Check                 = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                }
                
                
                if($request->token == config('token_HaramaynRooms') || $request->token == config('token_AlmnhajHotel')){
                    $mail_Check                 = Mail3rdPartyController::mail_Check_HR($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                }
            }
            // return $mail_Check;
            
            if($mail_Check == 'Success'){
                return 'Success';
            }else{
                return 'Error';
            }
        }else{
            return 'Error';
        }
    }
    
    public static function MailSend_Static(){
        // return 'OK';
        
        $from_Address               = 'noreply@sidratours.com';
        $website_Title              = 'Sidra Tours.';
        $mail_Template_Key          = '2d6f.41936cba9708a01.k1.b6a19770-6c33-11ef-99aa-52540064429e.191c6b2aa67';
        $website_Invoice            = config('website_Url_SidraTours').'hotel_voucher/ST8820238';
        
        $details                    = [
            'status'                => 'CONFIRMED',
            'invoice_no'            => 'ST8820238',
            'lead_Name'             => 'MRS Georgina Darvill',
            'email'                 => 'simon.darvill@sky.com',
            'contact'               => '+447940733454',
            'booking_Date'          => '05-09-2024',
            'Check_In'              => '20-11-2024',
            'Check_Out'             => '23-11-2024',
            'no_Of_Rooms'           => '1',
            'no_Of_Adults'          => '2',
            'price'                 => 'GBP 423.40',
            'hotel_Name'            => 'Hotel Mayfair',
            'room_Message_Mail'     => '<li>Room Type: Standard - Double</li><li>Meal Type: BED AND BREAKFAST</li>',
            'Booking_CN'            => '195-2745236',
        ];
        // return $details;
        
        $email_Message      = '<div> <h3> Dear '.$details['lead_Name'].' ,</h3> <h4> Your booking has been confirmed! Thank you for choosing our service.Below are the details of your booking:</h4> <ul><li>Invoice No: '.$details['invoice_no'].' </li> <li>Booking Confirmation No: '.$details['Booking_CN'].' </li> <li>Booking Date: '.$details['booking_Date'].'</li> <li>Check-in Date: '.$details['Check_In'].' </li><li>Check-out Date: '.$details['Check_Out'].' </li><li>Number of Rooms: '.$details['no_Of_Rooms'].' </li><li>Number of Adults: '.$details['no_Of_Adults'].' </li></ul> <h3>Room Details:</h3><ul><li>Hotel Name: '.$details['hotel_Name'].' </li>'.$details['room_Message_Mail'].'<li>Price: '.$details['price'].' </li><li> Room Status: '.$details['status'].' </li></ul> <h3>Customer Details:</h3><ul><li>Name: '.$details['lead_Name'].' </li><li>Email: '.$details['email'].' </li><li>Phone Number: '.$details['contact'].' </li></ul> <h4>We look forward to providing you with a comfortable and enjoyable stay. Should you have any questions or require further assistance, please do not hesitate to contact us. <br>Thank you for choosing '.$website_Title.' </h4> <br> Invoice Link : '.$website_Invoice.' </div>';
        $from_Address       = 'noreply@sidratours.com';
        // $to_Address         = 'ua758323@gmail.com';
        $to_Address         = 'simon.darvill@sky.com';
        $reciever_Name      = $details['lead_Name'];
        $mail_Check         = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
        return $mail_Check;
        
        if($mail_Check != 'Success'){
            return response()->json(['message'=>'Email Sending Failed','booking_id'=> '']);
        }
    }
    
    public static function get_Otp_For_Bookings(Request $request){
        $validated  = $request->validate([
            'token' => 'required|string|exists:customer_subcriptions,Auth_key',
        ]);
        
        DB::beginTransaction();
        try {
            // if(isset($request->token) && $request->token != null && $request->token != ''){
                if(isset($request->email) && $request->email != null && $request->email != ''){
                    $customer_Data              = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                    $booking_data               = DB::table('hotels_bookings')->where('customer_id',$customer_Data->id)->whereJsonContains('lead_passenger_data->lead_email', $request->email)->get();
                    // return $booking_data;
                    if($booking_data->isEmpty()){
                        return response()->json([
                            'status'            => 'error',
                            'message'           => 'No Bookings Found!',
                        ]);
                    }else{
                        // Alhijaz Tours Mail
                        if($request->token == config('token_AlhijazTours')){
                            $from_Address       = config('mail_From_Address_AlhijazTours');
                            $website_Title      = config('mail_Title_AlhijazTours');
                            $mail_Template_Key  = config('mail_Template_Key_AlhijazTours');
                            $website_Url        = config('website_Url_AlhijazTours').'hotel-booking-invoice';
                            $mail_Send_Status   = true;
                            $user_Name          = 'User';
                        }
                        
                        // Haramayn Hotels Mail
                        if($request->token == config('token_HaramynHotel')){
                            $from_Address       = config('mail_From_Address_HaramynHotel');
                            $website_Title      = config('mail_Title_HaramynHotel');
                            $mail_Template_Key  = config('mail_Template_Key_HaramynHotel');
                            $website_Url        = config('website_Url_HaramynHotel').'hotel-booking-invoice';
                            $mail_Send_Status   = true;
                        }
                        
                        // Alhijaz Rooms Mail
                        if($request->token == config('token_AlhijazRooms')){
                            $from_Address       = config('mail_From_Address_AlhijazRooms');
                            $website_Title      = config('mail_Title_AlhijazRooms');
                            $mail_Template_Key  = config('mail_Template_Key_AlhijazRooms');
                            $website_Url        = config('website_Url_AlhijazRooms').'hotel_voucher';
                            $mail_Send_Status   = true;
                        }
                        
                        // Sidra Tours Mail
                        if($request->token == config('token_SidraTours')){
                            $from_Address       = config('mail_From_Address_SidraTours');
                            $website_Title      = config('mail_Title_SidraTours');
                            $mail_Template_Key  = config('mail_Template_Key_SidraTours');
                            $website_Url        = config('website_Url_SidraTours').'hotel_voucher';
                            $mail_Send_Status   = true;
                        }
                        
                        // Alsubaee Mail
                        if($request->token == config('token_Alsubaee')){
                            $from_Address       = config('mail_From_Address_Alsubaee');
                            $website_Title      = config('mail_Title_Alsubaee');
                            $mail_Template_Key  = config('mail_Template_Key_Alsubaee');
                            $website_Url        = config('website_Url_Alsubaee').'hotel_voucher';
                            $mail_Send_Status   = true;
                        }
                        
                        // UmrahShop Mail
                        if($request->token == config('token_UmrahShop')){
                            $from_Address       = config('mail_From_Address_UmrahShop');
                            $website_Title      = config('mail_Title_UmrahShop');
                            $mail_Template_Key  = config('mail_Template_Key_UmrahShop');
                            $website_Url        = config('website_Url_UmrahShop').'hotel_voucher';
                            $mail_Send_Status   = true;
                            $user_Name          = 'User';
                        }
                        
                        $otp_For_Bookings       = random_int(10000, 99999);
                        $reciever_Name          = $user_Name ?? $customer_Data->name ?? 'User';
                        // $to_Address             = 'ua758323@gmail.com';
                        $to_Address             = $request->email;
                        $email_Message          = '<div> <h3> Dear '.$reciever_Name.',</h3> Enter Your OTP ('.$otp_For_Bookings.') to get Bookings! <br><br> Regards <br> '.$website_Title.' </div>';
                        $mail_Check             = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                        // return $mail_Check;
                        
                        if($mail_Check != 'Success'){
                            return response()->json(['message'=>'Email Sending Failed!']);
                        }else{
                            DB::table('hotels_bookings_Otp')->insert([
                                'token'         => $request->token,
                                'customer_id'   => $customer_Data->id,
                                'email'         => $request->email,
                                'otp'           => $otp_For_Bookings,
                                'status'        => 'Waiting For Response',
                            ]);
                            
                            DB::commit();
                            
                            return response()->json([
                                'status'        => 'success',
                                'message'       => 'Check Your Email!',
                            ]);
                        }
                    }
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'message'   => 'Email is Required!',
                    ]);
                }
            // }else{
            //     return response()->json([
            //         'status'    => 'error',
            //         'message'   => 'Token is Required!',
            //     ]);
            // }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
    
    public static function check_Otp_For_Bookings(Request $request){
        $validated  = $request->validate([
            'token' => 'required|string|exists:customer_subcriptions,Auth_key',
        ]);
        
        DB::beginTransaction();
        try {
            // if(isset($request->token) && $request->token != null && $request->token != ''){
                if(isset($request->email) && $request->email != null && $request->email != ''){
                    $customer_Data              = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                    $booking_data               = DB::table('hotels_bookings')->where('customer_id',$customer_Data->id)->whereJsonContains('lead_passenger_data->lead_email', $request->email)->get();
                    if($booking_data->isEmpty()){
                        return response()->json([
                            'status'            => 'error',
                            'message'           => 'No Bookings Found!',
                        ]);
                    }else{
                        $booking_data_otp       = DB::table('hotels_bookings_Otp')->where('customer_id',$customer_Data->id)->where('email',$request->email)->where('otp',$request->otp)->get();
                        
                        if($booking_data_otp->isEmpty()){
                            return response()->json([
                                'status'        => 'error',
                                'message'       => 'Invalid Otp!',
                            ]);
                        }else{
                            if($booking_data_otp[0]->status != 'Expired'){
                                DB::table('hotels_bookings_Otp')->where('customer_id',$customer_Data->id)->where('email',$request->email)->where('otp',$request->otp)->update(['status'=>'Expired']);
                                
                                DB::commit();
                                
                                return response()->json([
                                    'status'    => 'success',
                                    'message'   => 'Bookings Get Successfully!',
                                    'bookings'  => $booking_data,
                                ]);
                            }else{
                                return response()->json([
                                    'status'    => 'error',
                                    'message'   => 'Otp Expired!',
                                ]);
                            }
                        }
                    }
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'message'   => 'Email is Required!'
                    ]);
                }
            // }else{
            //     return response()->json([
            //         'status'    => 'error',
            //         'message'   => 'Token is Required!'
            //     ]);
            // }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
    
    public function view_reservation(Request $request){
        // $sunHotelBookingDetails = SunHotel_Controller::sunHotelBookingDetails($request->bookingID);
        // return $sunHotelBookingDetails;
        
        $booking_data   = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
        $markups        = DB::table('manage_customer_markups')->where('invoice_no',$request->invoice_no)->first();
        if(isset($booking_data->b2b_agent_id) && $booking_data->b2b_agent_id != null && $booking_data->b2b_agent_id != '' && $booking_data->b2b_agent_id > 0){
            $b2b_Agent  = DB::table('b2b_agents')->where('id',$booking_data->b2b_agent_id)->first();
        }else{
            $b2b_Agent  = NULL;
        }
        
        // Hotel & Rooms Details
        $hotelDetails   = [];
        $roomDetails    = [];
        if(!empty($booking_data->provider) && $booking_data->provider == 'Custome_hotel'){
            if(!empty($booking_data->reservation_response)){
                $reservation_response = json_decode($booking_data->reservation_response);
                if(!empty($reservation_response->hotel_details)){
                    $hotel_details = $reservation_response->hotel_details;
                    $hotelDetails  = DB::table('hotels')->where('id',$hotel_details->hotel_code)->first();
                    if(!empty($hotel_details->rooms)){
                        foreach($hotel_details->rooms as $valRD){
                            $singleRoom = DB::table('rooms')->where('id',$valRD->room_code)->first();
                            array_push($roomDetails,$singleRoom);
                        }
                    }
                }
            }
        }
        // Hotel & Rooms Details
        
        if($booking_data){
            return response()->json([
                'status'            => 'success',
                'booking_data'      => $booking_data,
                'markups_details'   => $markups,
                'b2b_Agent'         => $b2b_Agent,
                'hotelDetails'      => $hotelDetails,
                'roomDetails'       => $roomDetails,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => "Wrong Invoice Number",
            ]);
        }
    }
    
    public function payment_Update(Request $request){
        try {
            $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
            if($booking_data){
                $customer_Data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                // return $customer_Data;
                if($customer_Data->id == $booking_data->customer_id && $booking_data->b2b_agent_id == $request->b2b_agent_id){
                    // Alsubaee Hotels
                    if($request->token == config('token_Alsubaee')){
                        $from_Address               = config('mail_From_Address_Alsubaee');
                        $website_Title              = config('mail_Title_Alsubaee');
                        $mail_Template_Key          = config('mail_Template_Key_Alsubaee');
                        $website_Url                = config('website_Url_Alsubaee');
                        $mail_Address_Register_Payment  = config('mail_Address_Register_Payment');
                        // $mail_Address_Register_Payment  = 'ua758323@gmail.com';
                        $mail_Send_Status           = true;
                        $b2b_agents                 = DB::table('b2b_agents')->where('id',$booking_data->b2b_agent_id)->first();
                        $b2b_Agent_First_Name       = $b2b_agents->first_name ?? '';
                        $b2b_Agent_Last_Name        = $b2b_agents->last_name ?? '';
                        $b2b_Agent_Name             = $b2b_Agent_First_Name.' '.$b2b_Agent_Last_Name;
                        
                        // return $booking_data;
                        
                        $currency                   = $booking_data->exchange_currency ?? 'SAR';
                        $total_Price                = $booking_data->exchange_price ?? '0';
                        $booking_Date               = Carbon::now();
                        $formatted_Booking_Date     = $booking_Date->format('d-m-Y H:i:s');
                        
                        $reservation_response       = json_decode($booking_data->reservation_response);
                        // return $reservation_response;
                        $hotel_details              = $reservation_response->hotel_details;
                        $room_Details               = $hotel_details->rooms;
                        $room_Message_Mail          = '';
                        foreach($room_Details as $val_RD){
                            $room_Data              = DB::table('rooms')->where('id',$val_RD->room_code)->first();
                            
                            $room_name              = $room_Data->room_type_name;
                            $meal_Type              = $room_Data->room_meal_type;
                            $room_Message_Mail      .= '<li>Room Type: '.$room_name.'</li><li>Meal Type: '.$meal_Type.'</li>';
                        }
                        
                        $check_in                   = Carbon::createFromFormat('Y-m-d', $hotel_details->checkIn);
                        $formatted_Check_In         = $check_in->format('d-m-Y');
                        $check_out                  = Carbon::createFromFormat('Y-m-d', $hotel_details->checkOut);
                        $formatted_Check_Out        = $check_out->format('d-m-Y');
                        
                        $details                    = [
                            'status'                => $booking_data->status,
                            'invoice_no'            => $booking_data->invoice_no,
                            'Check_In'              => $formatted_Check_In,
                            'Check_Out'             => $formatted_Check_Out,
                            'price'                 => $currency.' '.$total_Price,
                            'hotel_Name'            => $hotel_details->hotel_name ?? '',
                            'room_Message_Mail'     => $room_Message_Mail,
                        ];
                        
                        $email_Message_Register     = '<div> <h3> Dear '.$website_Title.',</h3> <br> An amount of '.$details['price'].' has been deposited into '.$website_Title.' Account by '.$b2b_Agent_Name.'<br> Following are the booking details. <br><br> <ul><li>Invoice number: '.$details['invoice_no'].' </li><li>Checkin: '.$details['Check_In'].' </li><li>Checkout: '.$details['Check_Out'].' </li><li>Hotel Name: '.$details['hotel_Name'].' </li><li>Room Type: '.$details['room_Message_Mail'].' </li><li>Total Amount: '.$details['price'].' </li></ul> <br> Thank you. </div>';
                        // return $email_Message_Register;
                        $reciever_Name              = $website_Title;
                        $mail_Check_Alsubaee        = Mail3rdPartyController::mail_Check_Alsubaee($from_Address,$mail_Address_Register_Payment,$reciever_Name,$email_Message_Register,$mail_Template_Key);
                        // return $mail_Check_Alsubaee;
                    }
                    
                    DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
                        'payment_details' => $request->payment_details,
                    ]);
                    
                    return response()->json([
                        'status'    => 'success',
                        'message'   => 'Payment Updated Successfully',
                    ]);
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'message'   => "Token Not Matched",
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => "Invoice Number Not Matched",
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong',
            ]);
        }
    }
    
    public function hotel_reservation_cancell_new(Request $request){
        // return $request;
        
        // $BookingReference = $request->BookingReference;
        // function cancellation_booking($BookingReference){
        //     $url    = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi_live.php";
        //     $data   = array('case' => 'cancellation_booking','refrence_id' => $BookingReference);
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_POST, true);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     $responseData = curl_exec($ch);
        //     //echo $responseData;die();
        //     if (curl_errno($ch)) {
        //         return curl_error($ch);
        //     }
        //     curl_close($ch);
        //     return $responseData;
        // }
        
        // $responseData = cancellation_booking($BookingReference);
        // return $responseData;
        
        if(isset($request->token)){
            $invoice_no     = $request->invoice_no;
            $token          = $request->token;
            $booking_data   = DB::table('hotels_bookings')->where('invoice_no',$invoice_no)->first();
            
            if(isset($booking_data->provider)){
                if($booking_data->provider == 'hotel_beds'){
                    $actual_reservation_response    = json_decode($booking_data->actual_reservation_response);
                    $BookingReference               = $actual_reservation_response->booking->reference;
                    function cancellation_booking($BookingReference){
                        $url    = "https://api.synchtravel.com/synchtravelhotelapi/hotelbedsapi_live.php";
                        $data   = array('case' => 'cancellation_booking','refrence_id' => $BookingReference);
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
                    
                    $responseData = cancellation_booking($BookingReference); 
                   
                }
                
                if($booking_data->provider == 'travelenda'){
                    $actual_reservation_response = json_decode($booking_data->actual_reservation_response);
                    $BookingReference            = $actual_reservation_response->Body->HotelBooking->BookingReference;
                    //print_r($BookingReference);die();
                    function HotelBookingCancel($BookingReference){
                        $url    = "https://api.synchtravel.com/synchtravelhotelapi/travellandaapi.php";
                        $data   = array('case' => 'HotelBookingCancel','id' => $BookingReference);
                        $ch     = curl_init();
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
                    $responseData = HotelBookingCancel($BookingReference);
                    //print_r($responseData);die();
                }
               
                if ($booking_data->provider == 'Stuba') {
                    $booking_Details = DB::table('hotels_bookings')->where('invoice_no', $request->invoice_no)->first();
                    if($booking_Details->status == 'Cancelled'){
                        return response()->json([
                            'status'    => 'error',
                            'message'   => "Already Cancel",
                        ]);
                    }
                    $actual_reservation_response    = json_decode($booking_Details->actual_reservation_response);
                    $booking_Id                     = $actual_reservation_response->Booking->Id;
                    $responseData                   = Stuba_Controller::stuba_Cancel($booking_Id);
                    // return $responseData;
                }
                
                if ($booking_data->provider == 'Sunhotel') {
                    // return 'Sunhotel';
                    $booking_Details = DB::table('hotels_bookings')->where('invoice_no', $request->invoice_no)->first();
                    if($booking_Details->status == 'Cancelled'){
                        return response()->json([
                            'status'    => 'error',
                            'message'   => "Already Cancel",
                        ]);
                    }
                    $actual_reservation_response    = json_decode($booking_Details->actual_reservation_response);
                    $bookingnumber                  = $actual_reservation_response->bookingnumber;
                    $responseData                   = SunHotel_Controller::sunHotelCancel($bookingnumber);
                    // return $responseData;
                }
            }
            
            if($booking_data){
                $booking_data   = DB::table('hotels_bookings')->where('invoice_no',$invoice_no)->update([
                                    'cancelination_response'    => $responseData,
                                    'status'                    => 'Cancelled'
                                ]);
                return response()->json([
                    'status'                    => 'success',
                    'view_reservation_details'  => $responseData,
                ]);
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => "Invalid Invoice Number",
                ]);
            }
        }
        else{
            return response()->json(['message','Invalid Token']);  
        }  
    }
    
    public function hotel_invoice(Request $request){
        $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
        $visa_booking_data = DB::table('visa_bookings')->where('invoice_no',$request->invoice_no)->first();
        $markups = DB::table('manage_customer_markups')->where('invoice_no',$request->invoice_no)->first();
       
       $visa_type = '';
       $visa_country = '';
       if($visa_booking_data){
            $visa_avail_data = json_decode($visa_booking_data->visa_avail_data);
            $visa_type = DB::table('visa_types')->where('id',$visa_avail_data->visa_type)->select('other_visa_type')->first();
            $visa_country = DB::table('countries')->where('id',$visa_avail_data->country)->select('name')->first();
       } 
      
        
        if($booking_data){
            return response()->json([
                'status' => 'success',
                'booking_data' => $booking_data,
                'markups_details' => $markups,
                'visa_booking_data' => $visa_booking_data,
                'visa_type' => $visa_type,
                'visa_country' => $visa_country,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => "Wrong Invoice Number",
            ]);
        }
    }
    
    // Departure Country Working
    public function getCountryPckages($request,$userData,$today_date,$cat_res){
        $customer_id                = $userData->id;
        $departure_Country          = NULL;
        if(isset($request->departure_Country)){
            $countries              = DB::table('countries')->where('name', $request->departure_Country)->first();
            if($countries != null){
                $departure_Country  = $countries->id;
            }
        }
        if($cat_res != 'NO'){
            $tours  = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->join('countries', 'tours.departure_Country', 'countries.id')
                        ->where('tours.start_date','>=',$today_date)->where('tours.customer_id',$customer_id)->where('tours.categories',$cat_res->id)->where('tours.tour_feature',0)
                        ->when($departure_Country, function ($query, $departure_Country) {
                            return $query->where('tours.departure_Country', $departure_Country);
                        })
                        ->select('tours.id','countries.name as departure_Country','tours.departureAirportCode','tours.title','tours_2.quad_grand_total_amount','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image',
                            'tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.categories','tours.created_at','tours.start_date','tours.end_date')
                        ->orderBy('tours.created_at', 'desc')->limit($request->limit)->get();
        }else{
            $tours  = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->join('countries', 'tours.departure_Country', 'countries.id')
                        ->select('tours.id','countries.name as departure_Country','tours.departureAirportCode','tours.title','tours_2.quad_grand_total_amount','tours_2.flights_details','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.tour_location','tours.starts_rating','tours.whats_included','tours.start_date','tours.end_date','tours.gallery_images')
                        ->where('tour_feature',0)->where('tours.customer_id',$customer_id)->where('tours.start_date','>=',$today_date)
                        ->when($departure_Country, function ($query, $departure_Country) {
                            return $query->where('tours.departure_Country', $departure_Country);
                        })
                        ->orwhereJsonContains('tours_2.flights_details', $request->departure_from)->orderBy('tours.created_at', 'desc')->get();
        }
        return $tours;
    }
    // Departure Country Working
    
    public function latest_packages(Request $request){
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status','webiste_Address','phone','email')->first();
        if($userData){
            $visa_types = DB::table('visa_types')->where('customer_id',$userData->id)->get();
            if(isset($request->type) && $request->type == 'activity'){ 
                $customer_id        = $userData->id;
                $transfer_detail    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
                $activities         = DB::table('new_activites')->where('customer_id',$customer_id)->select('id','title','currency_symbol','duration','location','activity_content','tour_attributes','min_people','max_people','Availibilty','sale_price','child_sale_price','featured_image','banner_image','activity_date','starts_rating','payment_getway')->orderBy('created_at', 'desc')->limit(6)->get();
                return response()->json(['message'=>'success','activity'=>$activities,'transfer_detail'=>$transfer_detail]);
            }
            
            $userData           = CustomerSubcription::where('Auth_key',$request->token)->first();
            $today_date         = date('Y-m-d');
            $customer_id        = $userData->id;
            $transfer_detail    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->select('ziyarat_City_details')->get();
            
            // Departure Country Working
            $cat_res            = 'NO';
            $getCountryPckages  = $this->getCountryPckages($request,$userData,$today_date,$cat_res);
            if(!$getCountryPckages->isEmpty()){
                $tours          = $getCountryPckages;
            }else{
                $tours          = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->join('countries', 'tours.departure_Country', 'countries.id')
                                    ->select('tours.id','countries.name as departure_Country','tours.departureAirportCode','tours.title','tours_2.quad_grand_total_amount','tours_2.flights_details','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.tour_location','tours.starts_rating','tours.whats_included','tours.start_date','tours.end_date','tours.gallery_images')
                                    ->where('tour_feature',0)->where('tours.customer_id',$customer_id)->where('tours.start_date','>=',$today_date)
                                    ->orwhereJsonContains('tours_2.flights_details', $request->departure_from)->orderBy('tours.created_at', 'desc')->get();
            }
            // Departure Country Working
            
            $hotels             = DB::table('hotels')
                                    ->select('hotels.id', 'hotels.property_name','hotels.property_img','hotels.currency_symbol','hotels.star_type','hotels.property_city', DB::raw('MIN(rooms.price_all_days) AS min_price'))
                                    ->join('rooms', 'hotels.id', '=', 'rooms.hotel_id')->groupBy('hotels.id', 'hotels.property_name')
                                    ->where('hotels.owner_id',$userData->id)->get();
            // Get All Top Categories
            $final_data                 = [];
            $categories                 = DB::table('categories')->where('customer_id',$userData->id)->select('id','image','title','description')->orderBy('placement', 'asc')->limit(5)->get();
            
            
            $category_count_arr         = [];
            foreach($categories as $cat_res){
                $result                 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$userData->id)->where('tours.categories',$cat_res->id)
                                            // ->where('tours.departure_Country', $departure_Country)
                                            ->count();
                $category_count_arr[]   = $result;
               
            }
            $final_data                 = [$categories,$category_count_arr];
            
            // Get All Categories
            $categories                 = DB::table('categories')->where('customer_id',$userData->id)->select('id','image','title','description')->orderBy('placement', 'asc')->get();
            // Get Meta Data Page
            $meta_data                  = DB::table('pages_meta_info')->where('page_url',$request->currentURL)->first();
            // Category Tours
            $category_tours             = [];
            // return $final_data;
            if(isset($final_data[0])){
                foreach($final_data[0] as $cat_res){
                    $today_date         = date('Y-m-d');
                    $customer_id        = $userData->id;
                    
                    // Departure Country Working
                    $getCountryPckages  = $this->getCountryPckages($request,$userData,$today_date,$cat_res);
                    if(!$getCountryPckages->isEmpty()){
                        $tours          = $getCountryPckages;
                    }else{
                        $tours          = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->join('countries', 'tours.departure_Country', 'countries.id')
                                            ->where('tours.start_date','>=',$today_date)->where('tours.customer_id',$customer_id)
                                            ->where('tours.categories',$cat_res->id)->where('tours.tour_feature',0)
                                            ->select('tours.id','countries.name as departure_Country','tours.departureAirportCode','tours.title','tours_2.quad_grand_total_amount','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image',
                                                'tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.categories','tours.created_at','tours.start_date','tours.end_date')
                                            ->orderBy('tours.created_at', 'desc')->limit($request->limit)->get();
                    }
                    // Departure Country Working
                    
                    $tours_enquire      = DB::table('tours_enquire')->join('tours_2_enquire','tours_enquire.id','tours_2_enquire.tour_id')
                                            ->where('tours_enquire.start_date','>=',$today_date)->where('tours_enquire.customer_id',$customer_id)
                                            ->where('tours_enquire.categories',$cat_res->id)
                                            ->where('tours_enquire.tour_feature',0)
                                            ->select('tours_enquire.id','tours_enquire.title','tours_2_enquire.quad_grand_total_amount','tours_2_enquire.triple_grand_total_amount','tours_2_enquire.double_grand_total_amount','tours_enquire.tour_featured_image','tours_enquire.tour_banner_image',
                                                'tours_enquire.time_duration','tours_enquire.content','tours_enquire.no_of_pax_days','tours_enquire.currency_symbol','tours_enquire.categories','tours_enquire.created_at')
                                            ->orderBy('tours_enquire.created_at', 'desc')->limit($request->limit)->get();
                            
                    if(isset($tours_enquire)){
                        foreach($tours_enquire as $index => $toure_res){
                            $tours_enquire[$index]->package_type = 'enquire';
                        }
                    }
                    
                    $collection1            = collect($tours);
                    $collection2            = collect($tours_enquire);
                    
                    $mergedCollection       = $collection1->merge($collection2);
                    $sortedCollection       = $mergedCollection->sortByDesc('created_at');
                    $tours                  = $sortedCollection->values()->all();
                    
                    array_push($category_tours,$tours);
                }
            }
            
            return response()->json([
                'message'=>'success',
                // 'tours'=>$tours,
                // 'transfer_detail'=>$transfer_detail,
                // 'hotels'=>$hotels,
                // 'visa_types'=>$visa_types,
                // 'userData' => $userData,
                // 'top_cateogries' => $final_data,
                'all_cateogries' => $categories,
                // 'meta_data' => $meta_data,
                'latest_packages' => $category_tours
            ]);
        }else{
            return response()->json([
                'message'   => 'error',
                'userData'  => $userData,
            ]);
        }
    }
    
    public function hotels_Make_Payemnt(Request $request){
        // return 'WORKING IN PROGRESS';
        // return $request;
        
        DB::beginTransaction();
        try {
            $request->validate([
                'payment_Image' => 'required|string',
            ]);
            
            $base64String = trim(stripslashes($request->input('payment_Image')), '"');
            if (preg_match('/^data:image\/(jpeg|png|gif);base64,/', $base64String, $type)) {
                $current_time           = date('d-m-Y H:i:s');
                $extension              = $type[1];
                $base64                 = substr($base64String, strpos($base64String, ',') + 1);
                $imageData              = base64_decode($base64);
                $fileName               = $current_time.'-'.$extension;
                $b2b_agent_id           = $request->b2b_agent_id;
                $invoice_No             = $request->invoice_No;
                $payment_Date           = $request->payment_Date;
                $transaction_No         = $request->transaction_No;
                $booking_data           = DB::table('hotels_bookings')->where('invoice_no',$invoice_No)->first();
                $reservation_request    = json_decode($booking_data->reservation_request);
                
                if($booking_data->b2b_agent_id == $b2b_agent_id){
                    // $payment_Type = '';
                    // if($reservation_request->slc_pyment_method == 'slc_stripe'){
                    //     $payment_Type = 'Strpe';
                    // }elseif($reservation_request->slc_pyment_method == 'Bank_Payment'){
                        $payment_Type = 'Bank Transfer';
                    // }else{
                    //     $payment_Type = 'Room on Request';
                    // }
                    
                    // return $request;
                    
                    if($booking_data->status != 'Confirmed'){
                        
                        // Alsubaee Hotels
                        $customer_Data = DB::table('customer_subcriptions')->where('id',$booking_data->customer_id)->first();
                        if($customer_Data->Auth_key == config('token_Alsubaee')){
                            $from_Address               = config('mail_From_Address_Alsubaee');
                            $website_Title              = config('mail_Title_Alsubaee');
                            $mail_Template_Key          = config('mail_Template_Key_Alsubaee');
                            $website_Url                = config('website_Url_Alsubaee');
                            $mail_Address_Register_Payment  = config('mail_Address_Register_Payment');
                            // $mail_Address_Register_Payment  = 'ua758323@gmail.com';
                            $mail_Send_Status           = true;
                            $b2b_agents                 = DB::table('b2b_agents')->where('id',$request->b2b_agent_id)->first();
                            $b2b_Agent_First_Name       = $b2b_agents->first_name ?? '';
                            $b2b_Agent_Last_Name        = $b2b_agents->last_name ?? '';
                            $b2b_Agent_Name             = $b2b_Agent_First_Name.' '.$b2b_Agent_Last_Name;
                            
                            // return $booking_data;
                            
                            $currency                   = $booking_data->exchange_currency ?? 'SAR';
                            $total_Price                = $booking_data->exchange_price ?? '0';
                            $booking_Date               = Carbon::now();
                            $formatted_Booking_Date     = $booking_Date->format('d-m-Y H:i:s');
                            
                            $reservation_response       = json_decode($booking_data->reservation_response);
                            // return $reservation_response;
                            $hotel_details              = $reservation_response->hotel_details;
                            $room_Details               = $hotel_details->rooms;
                            $room_Message_Mail          = '';
                            foreach($room_Details as $val_RD){
                                $room_Data              = DB::table('rooms')->where('id',$val_RD->room_code)->first();
                                if($room_Data == null){
                                    return response()->json([
                                        'status'            => 'error',
                                        'message'           => 'Room not Exist!',
                                        'payment_Details'   => '',
                                        'booking_Data'      => '',
                                    ]);
                                }
                                $room_name              = $room_Data->room_type_name;
                                $meal_Type              = $room_Data->room_meal_type;
                                $room_Message_Mail      .= '<li>Room Type: '.$room_name.'</li><li>Meal Type: '.$meal_Type.'</li>';
                            }
                            
                            $check_in                   = Carbon::createFromFormat('Y-m-d', $hotel_details->checkIn);
                            $formatted_Check_In         = $check_in->format('d-m-Y');
                            $check_out                  = Carbon::createFromFormat('Y-m-d', $hotel_details->checkOut);
                            $formatted_Check_Out        = $check_out->format('d-m-Y');
                            
                            $details                    = [
                                'status'                => $booking_data->status,
                                'invoice_no'            => $booking_data->invoice_no,
                                'Check_In'              => $formatted_Check_In,
                                'Check_Out'             => $formatted_Check_Out,
                                'price'                 => $currency.' '.$total_Price,
                                'hotel_Name'            => $hotel_details->hotel_name ?? '',
                                'room_Message_Mail'     => $room_Message_Mail,
                            ];
                            
                            $email_Message_Register     = '<div> <h3> Dear '.$website_Title.',</h3> <br> An amount of '.$details['price'].' has been deposited into '.$website_Title.' Account by '.$b2b_Agent_Name.'<br> Following are the booking details. <br><br> <ul><li>Invoice number: '.$details['invoice_no'].' </li><li>Checkin: '.$details['Check_In'].' </li><li>Checkout: '.$details['Check_Out'].' </li><li>Hotel Name: '.$details['hotel_Name'].' </li><li>Room Type: '.$details['room_Message_Mail'].' </li><li>Total Amount: '.$details['price'].' </li></ul> <br> Thank you. </div>';
                            // return $email_Message_Register;
                            $reciever_Name              = $website_Title;
                            $mail_Check_Alsubaee        = Mail3rdPartyController::mail_Check_Alsubaee($from_Address,$mail_Address_Register_Payment,$reciever_Name,$email_Message_Register,$mail_Template_Key);
                            // return $mail_Check_Alsubaee;
                        }
                        
                        $payment_Details = [
                            'status'            => 'Tentative',
                            'b2b_agent_id'      => $b2b_agent_id,
                            'payment_Type'      => $payment_Type,
                            'total_Price'       => $reservation_request->creditAmount ?? $reservation_request->exchange_price,
                            'invoice_No'        => $invoice_No,
                            'payment_Date'      => $payment_Date,
                            'transaction_No'    => $transaction_No,
                            'payment_Image'     => $fileName,
                        ];
                        
                        DB::table('hotels_bookings')->where('invoice_no',$invoice_No)->update(['status' => 'Tentative','payment_details' => $payment_Details,'payment_Reject_Status' => NULL]);
                        
                        // $payment_Image->move(public_path('uploads/make_Payement'), $current_time.'_'.$fileName);
                        $path       = public_path('images' . $fileName);
                        Storage::disk('public')->put($fileName, $imageData);
                        file_put_contents($path, $imageData);
                        
                        DB::commit();
                        
                        $booking_data       = DB::table('hotels_bookings')->where('invoice_no',$invoice_No)->first();
                        return response()->json([
                            'status'            => 'success',
                            // 'message'           => 'Payment has done Successfully!',
                            'message'           => 'Your payment details have been submitted for review. Once the admin approves your payment, your booking will be confirmed.',
                            'payment_Details'   => $payment_Details,
                            'booking_Data'      => $booking_data,
                        ]);
                    }else{
                        return response()->json([
                            'status'            => 'error',
                            'message'           => 'Status Already Confirmed!',
                            'payment_Details'   => '',
                            'booking_Data'      => '',
                        ]);
                    }
                    
                }else{
                    return response()->json([
                        'status'        => 'error',
                        'message'       => 'Token not Matched!',
                        'booking_Data'  => ''
                    ]);
                }
                
                // return 'STOP';
            }else{
                return response()->json([
                    'status'        => 'error',
                    'message'       => 'Upload Payment Voucher!',
                    'booking_Data'  => ''
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['message'=>'error','message'=>'Something Went Wrong','Invoice_data'=> '']);
        }
        
        return $request;
    }
    
    // public function make_Cancel_Request(Request $request){
    //     try {
    //         $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
    //         if($booking_data){
    //             $customer_Data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
    //             // return $customer_Data;
    //             if($customer_Data->id == $booking_data->customer_id && $booking_data->b2b_agent_id == $request->b2b_agent_id){
                    
    //                 if($booking_data->status == 'Confirmed' && $booking_data->after_Confirm_Status != 'Apply For Rejection'){
    //                     // return $request;
                        
    //                     // after_Confirm_Status
    //                     // cancellation_Request_Message
                        
    //                     DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
    //                         'after_Confirm_Status'           => 'Apply For Rejection',
    //                         'cancellation_Request_Message'   => $request->cancellation_Request_Message,
    //                     ]);
                        
    //                     return response()->json([
    //                         'status'    => 'success',
    //                         'message'   => 'Cancellation Requested Successfully',
    //                     ]);
    //                 }else{
    //                     return response()->json([
    //                         'status'    => 'error',
    //                         'message'   => 'Confirm Booking First!',
    //                     ]);
    //                 }
    //             }else{
    //                 return response()->json([
    //                     'status'    => 'error',
    //                     'message'   => "Token Not Matched",
    //                 ]);
    //             }
    //         }else{
    //             return response()->json([
    //                 'status'    => 'error',
    //                 'message'   => "Invoice Number Not Matched",
    //             ]);
    //         }
    //     } catch (Throwable $e) {
    //         return response()->json([
    //             'status'    => 'error',
    //             'message'   => 'Something Went Wrong',
    //         ]);
    //     }
    // }
    
    public function B2B_Payment_List(Request $request){
        try {
            $customer_Data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data){
                $booking_data = DB::table('hotels_bookings')->where('customer_id',$customer_Data->id)->where('b2b_agent_id',$request->b2b_agent_id)->get();
                
                // Almnhaj
                if($customer_Data->id == 60){
                    $transferBookings = DB::table('transfers_new_booking')->where('customer_id',$customer_Data->id)->where('b2b_agent_id',$request->b2b_agent_id)->get();
                }
                // Almnhaj
                
                return response()->json([
                    'status'            => 'success',
                    'booking_data'      => $booking_data,
                    'transferBookings'  => $transferBookings ?? [],
                ]);
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => "Token Not Matched",
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong',
            ]);
        }
    }
    
    public function bank_Transfer_Cancel(Request $request){
        try {
            $customer_Data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data){
                $booking_data = DB::table('hotels_bookings')->where('customer_id',$customer_Data->id)->where('invoice_no',$request->invoice_no)->first();
                // return $booking_data;
                if($booking_data){
                    DB::table('hotels_bookings')->where('customer_id',$customer_Data->id)->where('invoice_no',$request->invoice_no)->update(['status' => 'Cancelled']);
                    
                    $booking_data = DB::table('hotels_bookings')->where('customer_id',$customer_Data->id)->where('invoice_no',$request->invoice_no)->first();
                    
                    return response()->json([
                        'status'        => 'success',
                        'booking_data'  => $booking_data,
                    ]);
                }else{
                    return response()->json([
                        'status'        => 'error',
                        'booking_data'  => 'Invoice Not Exist',
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => "Token Not Matched",
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong',
            ]);
        }
    }
}
