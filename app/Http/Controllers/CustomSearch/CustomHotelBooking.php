<?php

namespace App\Http\Controllers\CustomSearch;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TBOHotel_3rdPartyBooking_Controller;
use App\Models\booking_customers;
use App\Models\CustomerSubcription\CustomerSubcription;
use Carbon\Carbon;
use DB;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Session;
use App\Http\Controllers\HotelMangers\HotelBookingReactController_Live;

class CustomHotelBooking extends Controller
{
    // Custom Hotel
    public static function customer_Hotel_Search($request){
        $token                              = $request->token;
        $check_in                           = $request->check_in;
        $check_out                          = $request->check_out;
        $destination                        = $request->destination ?? $request->destination_name;
        $destination_first_char             = substr($request->destination_name, 0, 10);
        $adult_per_room                     = $request->adult_per_room ?? $request->Adults;
        $child_per_room                     = $request->child_per_room ?? $request->children;
        
        $adult_searching                    = $request->adult_searching;
        $child_searching                    = $request->child_searching;
        $hotel_search_request               = $request->hotel_search_request;
        $country_nationality                = $request->slc_nationality ?? $request->country_nationality;
        
        $CheckInDate                        = $check_in;
        $CheckOutDate                       = $check_out;
        
        $sub_customer_id                    = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        $markup                             = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)->orderBy('id','DESC')->get();
                                    
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
        
        // return $sub_customer_id;
        
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
        
        // return $customer_markup;
      
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
        
        $rooms_adults       = $adult_per_room;
        $rooms_childs       = $child_per_room;
        $customer_Id        = $sub_customer_id->id;
        
        $all_Hotels     = [];
        // return 'customer_Id :'.$customer_Id.' property_city : '.$hotel_city_changed;
        $user_hotels    = DB::table('hotels')->where('id',$request->hotel_Id)->where('owner_id',$customer_Id)->where('property_city', $hotel_city_changed)->orderBy('hotels.created_at', 'desc')->get();
        // return $user_hotels;
        $allowd_Hotels  = DB::table('allowed_Hotels_Rooms')->where('hotel_Id',$request->hotel_Id)->where('client_Id',$customer_Id)->orderBy('created_at', 'desc')->get();
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
        
        // return $all_hotels;
        
        foreach($all_hotels as $hotel_res){
            $rooms_found    = [];
            $rooms_ids      = [];
            $rooms_qty      = [];
            $counter        = 0;
            
            $total_adults_in_rooms = 0;
            $total_childs_in_rooms = 0;
            if(isset($rooms_adults) && !empty($rooms_adults)){
                foreach($rooms_adults as $index => $adult_res){
                    $hotel_ID   = $hotel_res->id;
                    
                    if(isset($request->room_View) && $request->room_View != null && $request->room_View != ''){
                        $rooms  = DB::table('rooms')
                                    ->where('availible_from','<=',$check_in)->where('availible_to','>=',$check_out)->where('room_view',$request->room_View)->whereRaw('booked < quantity')
                                    ->where('max_adults',$adult_res)->where('display_on_web','true')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->get();
                    }else{
                        $rooms  = DB::table('rooms')
                                    ->where('availible_from','<=',$check_in)->where('availible_to','>=',$check_out)->whereRaw('booked < quantity')
                                    ->where('max_adults',$adult_res)->where('display_on_web','true')->where('hotel_id',$hotel_res->id)->where('owner_id',$hotel_res->owner_id)->get();
                    }
                    
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
            
            $hotel_currency = '';
            if($hotel_res->currency_symbol == '﷼')  {
                $hotel_currency = 'SAR';
            }
            
            $client_Id                  = (int)$hotel_res->owner_id;
            $options_room               = [];
            $room_prices_arr            = [];
            $room_prices_arr_Promotion  = [];
            $room_price_Promotion       = 0;
            $room_Promotions_Exist      = '0';
            if($total_adults_in_rooms >= $adult_searching && $total_childs_in_rooms >= $child_searching){
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
                    
                    $supplier_Details                       = DB::table('rooms_Invoice_Supplier')->where('id',$room_res->room_supplier_name)->where('customer_id',$sub_customer_id->id)->first();
                    
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
                    // return $room_price;
                    $room_prices_arr[]              = $room_price;
                    $options_room[]                 = (Object)[
                        'booking_req_id'            => $room_res->id,
                        'allotment'                 => $room_res->quantity - $room_res->booked,
                        'room_name'                 => $room_res->room_type_name,
                        'room_code'                 => $room_res->room_type_cat,
                        'request_type'              => $room_res->rooms_on_rq,
                        'board_id'                  => $room_res->room_meal_type,
                        'board_code'                => $room_res->room_meal_type,
                        'rooms_total_price'             => $room_price * $request->room,
                        'rooms_selling_price'           => $room_price * $request->room,
                        'rooms_total_price_Promotion'   => $room_price_Promotion * $request->room,
                        'rooms_selling_price_Promotion' => $room_price_Promotion * $request->room,
                        'rooms_qty'                 => $request->room ?? 1,
                        'adults'                    => $rooms_adults[0] ?? $room_res->max_adults,
                        'childs'                    => $rooms_childs[0] ?? $room_res->max_child,
                        'cancliation_policy_arr'    => [],
                        'rooms_list'                => $room_res,
                        'room_supplier_code'        => $supplier_Details->room_supplier_code ?? '',
                        'room_Promotions_Exist'     => $room_Promotions_Exist,
                        'room_Promotions'           => $room_Promotions,
                        'room_view'                 => $room_res->room_view ?? '',
                    ];
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
        
        $final_hotel_Array = array_merge($hotels_list_arr_match,$hotels_list_arr);
        
        return response()->json([
            'status'        => 'success',
            'hotels_list'   => $final_hotel_Array,
        ]);
    }
    
    public static function customer_Hotel_Detail($request,$decode_SD){
        $hotel_rooms_data                   = $decode_SD[0] ?? '';
        $sub_customer_id                    = DB::table('customer_subcriptions')->where('Auth_key',$token)->select('id')->first();
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
        $user_Token     = $token;
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
        
        if(isset($hotel_rooms_data->hotel_provider) && $hotel_rooms_data->hotel_provider == 'Custome_hotel'){
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
            
            $hotel_detials_generted_Obj = (Object)[
                'hotel_provider'            => 'Custome_hotel',
                'admin_markup'              => $admin_custom_markup,
                'customer_markup'           => $customer_custom_hotel_markup,
                'admin_markup_type'         => 'Percentage',
                'customer_markup_type'      => $customer_custom_hotel_markup_type,
                'hotel_code'                => $hotel_data->id,
                'hotel_name'                => $hotel_data->property_name,
                'hotel_address'             => $hotel_data->property_address,
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
                'rooms_options'             => $hotel_rooms_data->rooms_options,
                'client_Id'                 => $hotel_rooms_data->client_Id ?? '',
            ];
            
            return response()->json([
                'status'        => 'success',
                'hotel_details' => $hotel_detials_generted_Obj
            ]);
        }
    }
    // Custom Hotel
    
    // Custom Hotel Provider
    public static function customer_Hotel_Provider_Search($hotel_Id,$token){
        $token                              = $token;
        $check_in                           = '2024-11-20';
        $check_out                          = '2024-11-21';
        
        $adult_per_room                     = $request->adult_per_room ?? $request->Adults ?? ['2'];
        $child_per_room                     = $request->child_per_room ?? $request->children ?? ['0'];
        
        $adult_searching                    = $request->adult_searching ?? '';
        $child_searching                    = $request->child_searching ?? '';
        $hotel_search_request               = $request->hotel_search_request ?? '';
        $country_nationality                = $request->slc_nationality ?? $request->country_nationality ?? 'SA';
        
        $CheckInDate                        = $check_in;
        $CheckOutDate                       = $check_out;
        
        $sub_customer_id                    = DB::table('customer_subcriptions')->where('Auth_key',$token)->select('id')->first();
        $markup                             = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)->orderBy('id','DESC')->get();
        
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
        
        $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id',$sub_customer_id->id)->where('status',1)->first();
        if($custom_hotel_markup){
            $customer_custom_hotel_markup       = $custom_hotel_markup->markup_value;
            $customer_custom_hotel_markup_type  = $custom_hotel_markup->markup;
        }
        
        // B2B Markup
        $markup_Find    = false;
        $user_Token     = $token;
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
        
        // return $customer_markup;
        
        $hotels_list_arr        = [];
        $hotels_list_arr_match  = [];
        $destination_first_char = 'Dorrar Al Eiman Royal Hotel';
        
        // ************************************************************ //
        // Custom Hotels Providers
        // ************************************************************ //
        
        $rooms_adults = $adult_per_room;
        $rooms_childs = $child_per_room;
        
        $all_hotel_providers = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('provider_id','!=','NULL')->where('provider_id','!=','')->get();
        // return $all_hotel_providers;
        if(isset($all_hotel_providers)){
            foreach($all_hotel_providers as $hotel_provider_res){
                $all_hotels             = DB::table('hotels')->where('id',$hotel_Id)->get();
                
                $provider_markup_data   = DB::table('become_provider_markup')->where('customer_id',$hotel_provider_res->provider_id)->where('status','1')->first();
                // $all_hotels             = DB::table('hotels')->where('owner_id',$hotel_provider_res->provider_id)->get();
                
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
                
                // return $all_hotels;
                
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
                                //   return $rooms;  
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
                    
                    // return $rooms_found;
                    
                    $hotel_currency     = '';
                    if($hotel_res->currency_symbol == '﷼')  {
                        $hotel_currency = 'SAR';
                    }
                    
                    $options_room       = [];
                    $room_prices_arr    = [];
                    
                    if($total_adults_in_rooms >= $adult_searching && $total_childs_in_rooms >= $child_searching){
                        foreach($rooms_found as $room_res){
                            
                            $room_price         = HotelBookingReactController_Live::calculateRoomsAllDaysPrice($room_res,$check_in,$check_out,$sub_customer_id->id);
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
                                'adults'                    => $room_res->max_adults,
                                'childs'                    => $room_res->max_child,
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
                        
                        $hotel_list_item = (Object)[
                            'hotel_provider'        => 'custom_hotel_provider',
                            'custom_hotel_provider' => $hotel_provider_res->provider,
                            'admin_markup'          => $admin_hotel_bed_markup ?? $admin_custom_hotel_pro_markup,
                            'customer_markup'       => $customer_custom_hotel_markup ?? $customer_markup,
                            'admin_markup_type'     => 'Percentage',
                            'customer_markup_type'  => $customer_custom_hotel_markup_type ?? 'Percentage',
                            'hotel_id'              => $hotel_res->id,
                            'hotel_name'            => $hotel_res->property_name,
                            'stars_rating'          => (float)$hotel_res->star_type,
                            'hotel_curreny'         => $hotel_currency,
                            'min_price'             => $min_price * 1,
                            'max_price'             => $max_price * 1,
                            'rooms_options'         => $options_room,
                            'latitude_Hotel'        => $hotel_res->latitude,
                            'longitude_Hotel'       => $hotel_res->longitude,
                        ];
                        
                        // return $hotel_list_item;
                        
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
        
        $final_hotel_Array  = array_merge($hotels_list_arr_match,$hotels_list_arr);
        
        return response()->json([
            'status'        => 'success',
            'hotels_list'   => $final_hotel_Array,
        ]);
    }
    
    public static function customer_Hotel_Provider_Detail($hotel_Id,$token,$decode_SD){
        return $decode_SD;
        
        $hotel_rooms_data                   = $decode_SD[0] ?? '';
        $sub_customer_id                    = DB::table('customer_subcriptions')->where('Auth_key',$token)->select('id')->first();
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
        $user_Token     = $token;
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
        
        if(isset($hotel_rooms_data->hotel_provider) && $hotel_rooms_data->hotel_provider == 'Custome_hotel'){
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
            
            $hotel_detials_generted_Obj = (Object)[
                'hotel_provider'            => 'Custome_hotel',
                'admin_markup'              => $admin_custom_markup,
                'customer_markup'           => $customer_custom_hotel_markup,
                'admin_markup_type'         => 'Percentage',
                'customer_markup_type'      => $customer_custom_hotel_markup_type,
                'hotel_code'                => $hotel_data->id,
                'hotel_name'                => $hotel_data->property_name,
                'hotel_address'             => $hotel_data->property_address,
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
                'rooms_options'             => $hotel_rooms_data->rooms_options,
                'client_Id'                 => $hotel_rooms_data->client_Id ?? '',
            ];
            
            return response()->json([
                'status'        => 'success',
                'hotel_details' => $hotel_detials_generted_Obj
            ]);
        }
    }
    // Custom Hotel Provider
    
    // Custom Hotel
    public static function travelenda_Hotel_Search($request){
        $token                              = $request->token;
        $check_in                           = $request->check_in;
        $check_out                          = $request->check_out;
        $destination                        = $request->destination ?? $request->destination_name;
        $destination_first_char             = substr($request->destination_name, 0, 10);
        $adult_per_room                     = $request->adult_per_room ?? $request->Adults;
        $child_per_room                     = $request->child_per_room ?? $request->children;
        
        $adult_searching                    = $request->adult_searching;
        $child_searching                    = $request->child_searching;
        $hotel_search_request               = $request->hotel_search_request;
        $country_nationality                = $request->slc_nationality ?? $request->country_nationality;
        
        $CheckInDate                        = $check_in;
        $CheckOutDate                       = $check_out;
        
        $sub_customer_id                    = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        $markup                             = DB::table('admin_markups')->where('customer_id',$sub_customer_id->id)->where('status',1)->orderBy('id','DESC')->get();
        
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
        
        // return $sub_customer_id;
        
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
        
        // return $customer_markup;
      
        $get_res    = DB::table('travellanda_get_cities')->where('CityName',$destination)->first();
        $CityId     = '';
        if($get_res){
            $CityId = $get_res->CityId;
        }
        
        $hotels_list_arr        = [];
        $hotels_list_arr_match  = [];
        
        // ************************************************************ //
        // Travelenda Provider
        // ************************************************************ //
        
        $country                    = $request->slc_nationality ?? '';
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
                array_push($child_age,$child_ages[$i]);
            }
            
            $single_room = (object)[
                'Room'          => $rooms_no++,
                'Adults'        => $request->Adults[$index],
                'Children'      => $request->children[$index],
                'ChildrenAge'   => $child_age
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
            if(isset($travel_res->HotelId) && $travel_res->HotelId == $request->hotel_Id){
                
                $rooms_list             = [];
                $prices_arr             = [];
                $option_list            = [];
                
                if(isset($travel_res->Options)){
                    $Options            = $travel_res->Options;
                    if(isset($Options->Option) && is_array($Options->Option)){
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
               
                if(!empty($prices_arr)){
                    $max_hotel_price = max($prices_arr);
                    $min_hotel_price = min($prices_arr);
                }
                else{
                    $min_hotel_price = 0;
                    $max_hotel_price = 0;  
                }
                
                $hotel_list_item = (Object)[
                    'hotel_provider'        => 'travelenda',
                    'admin_markup'          => $admin_travelenda_markup,
                    'customer_markup'       => $customer_markup,
                    'admin_markup_type'     => 'Percentage',
                    'customer_markup_type'  => 'Percentage',
                    'hotel_id'              => $travel_res->HotelId,
                    'hotel_name'            => $travel_res->HotelName,
                    'stars_rating'          => (float)round($travel_res->StarRating ?? 1),
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
        }
        
        $final_hotel_Array          = array_merge($hotels_list_arr_match,$hotels_list_arr);
        
        return response()->json([
            'status'                => 'success',
            'hotels_list'           => $final_hotel_Array,
        ]);
    }
    
    public static function travelenda_Hotel_Detail($request,$decode_SD){
        // return $decode_SD;
        
        $hotel_rooms_data                   = $decode_SD[0] ?? '';
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
                                            'amount'                => $cancel_res->Value,
                                            'type'                  => $cancel_res->Type,
                                            'from_date'             => $cancel_res->From,
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
                
                $address = $hotel_meta_data->Hotel->Address;
                
                $hotel_detials_generted_Obj = (Object)[
                    'hotel_provider'        => 'travelenda',
                    'admin_markup'          => $admin_travelenda_markup,
                    'customer_markup'       => $customer_markup,
                    'admin_markup_type'     => 'Percentage',
                    'customer_markup_type'  => 'Percentage',
                    'hotel_code'            => $hotel_rooms_data->hotel_id,
                    'hotel_name'            => $hotel_rooms_data->hotel_name,
                    'hotel_address'         => $address,
                    'longitude'             => $hotel_meta_data->Hotel->Longitude,
                    'latitude'              => $hotel_meta_data->Hotel->Latitude,
                    'hotel_country'         => $hotel_meta_data->Hotel->Location ?? '',
                    'hotel_city'            => $hotel_meta_data->Hotel->Location ?? '',
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
    }
    // Custom Hotel
}