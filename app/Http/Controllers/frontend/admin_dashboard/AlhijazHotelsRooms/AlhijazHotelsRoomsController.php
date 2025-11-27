<?php

namespace App\Http\Controllers\frontend\admin_dashboard\AlhijazHotelsRooms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\hotel_manager\Rooms;
use App\Models\admin\RoomsType;
use App\Models\hotel_manager\RoomGallery;
use App\Models\hotel_manager\Hotels;
use App\Models\MetaInfo;
use App\Models\Policies;
use App\Models\country;
use App\Models\city;
use App\Models\rooms_Invoice_Supplier;
use App\Models\allowed_Hotels_Rooms;
use Session;
use concat;
use Auth;
use DB;

class AlhijazHotelsRoomsController extends Controller
{
    public function view_Alhijaz_Hotels_Rooms(Request $request){
        $today_Date     = date('Y-m-d');
        // dd($today_Date);
        
        $id             = $request->customer_id;
        $client_Details = DB::table('customer_subcriptions')->where('id','!=',$id)->get();
        $user_hotel_arr = [];
        $user_hotels    = DB::table('hotels')->where('owner_id',$id)
                            ->join('meta_infos','hotels.id','meta_infos.hotel_id')->join('policies','hotels.id','policies.hotel_id')
                            ->orderBy('hotels.created_at', 'desc')->get();
                            // dd($user_hotels);
        foreach($user_hotels as $val_UH){
            $rooms          = DB::table('rooms')->where('hotel_id',$val_UH->hotel_id)->where('availible_to', '>=', $today_Date)->orderBy('id', 'asc')->get();
            if(empty($rooms) || count($rooms) === 0){
                $rooms = null;
            }
            $hotels_Rooms   = [
                                'hotel' => $val_UH,
                                'rooms' => $rooms,
                            ];
            array_push($user_hotel_arr,$hotels_Rooms);
        }
        $unique_User_Hotels = $user_hotel_arr;
        
        return response()->json([
            'user_hotels'       => $unique_User_Hotels,
            'client_Details'    => $client_Details,
        ]);
    }
    
    public function view_Alhijaz_Hotels_Rooms_All(Request $request){
        $id             = $request->customer_id;
        $client_Details = DB::table('customer_subcriptions')->where('id','!=',$id)->get();
        return response()->json([
            'client_Details'    => $client_Details,
        ]);
    }
    
    public function get_Hotel_Client_Wise(Request $request){
        $client_Details = DB::table('customer_subcriptions')->where('id',$request->client_Id)->get();
        $user_hotel_arr = [];
        $user_hotels    = DB::table('hotels')->where('owner_id',$request->client_Id)
                            ->join('meta_infos','hotels.id','meta_infos.hotel_id')->join('policies','hotels.id','policies.hotel_id')
                            ->orderBy('hotels.created_at', 'desc')->get();
                            // dd($user_hotels);
        foreach($user_hotels as $val_UH){
            $rooms          = DB::table('rooms')->where('hotel_id',$val_UH->hotel_id)->orderBy('id', 'asc')->get();
            if(empty($rooms) || count($rooms) === 0){
                $rooms = null;
            }
            $hotels_Rooms   = [
                                'hotel' => $val_UH,
                                'rooms' => $rooms,
                            ];
            array_push($user_hotel_arr,$hotels_Rooms);
        }
        $unique_User_Hotels = $user_hotel_arr;
        
        return response()->json([
            'user_hotels'       => $unique_User_Hotels,
            'client_Details'    => $client_Details,
        ]);
    }
    
    public function add_Alhijaz_Hotels_Rooms(Request $req){
        $today_Date     = date('Y-m-d');
        $id             = $req->customer_id;
        $token          = $req->token;
        $img_url        = $req->img_url;
        $hotel_Details  = json_decode($req->all_Hotel_Details);
        $client_Id      = $req->client_Id;  
        // dd($hotel_Details,$client_Id);
        
        DB::beginTransaction();
        try {
            // New
            $room_Count                                         = 0;
            $room_Details                                       = json_decode($req->room_Details);
            $all_rooms                                          = DB::table('rooms')->where('owner_id',$req->customer_id)->where('availible_to', '>=', $today_Date)->where('hotel_id',$hotel_Details->hotel_id)->get();
            
            foreach($all_rooms as $val_AR){
                $already_Allowed                                = DB::table('allowed_Hotels_Rooms')->where('customer_id',$req->customer_id)->where('client_Id',$req->client_Id)->where('hotel_Id',$val_AR->hotel_id)->where('room_Id',$val_AR->id)->first();
                if($already_Allowed == null){
                    
                    // dd($room_Details[$room_Count]);
                    
                    $allowed_Hotels_Rooms                       = new allowed_Hotels_Rooms();
                    // $allowed_Hotels_Rooms->SU_id                = $req->SU_id;
                    $allowed_Hotels_Rooms->customer_id          = $req->customer_id;
                    $allowed_Hotels_Rooms->client_Id            = $req->client_Id;
                    $allowed_Hotels_Rooms->hotel_Id             = $val_AR->hotel_id;
                    $allowed_Hotels_Rooms->room_Id              = $val_AR->id;
                    $allowed_Hotels_Rooms->hotel_Markup_Type    = $req->hotel_Markup_Type;
                    $allowed_Hotels_Rooms->hotel_Markup_Price   = $req->hotel_Markup_Price;
                    $allowed_Hotels_Rooms->room_Detail          = json_encode($val_AR);
                    
                    $allowed_Hotels_Rooms->room_Price_AD        = $room_Details[$room_Count]->room_Price_AD ?? NULL;
                    $allowed_Hotels_Rooms->room_Markup_Type_AD  = $room_Details[$room_Count]->room_Markup_Type_AD ?? NULL;
                    $allowed_Hotels_Rooms->room_Markup_Price_AD = $room_Details[$room_Count]->room_Markup_Price_AD ?? NULL;
                    $allowed_Hotels_Rooms->room_Sale_Price_AD   = $room_Details[$room_Count]->room_Sale_Price_AD ?? NULL;
                    
                    $allowed_Hotels_Rooms->room_Price_WD        = $room_Details[$room_Count]->room_Price_WD ?? NULL;
                    $allowed_Hotels_Rooms->room_Markup_Type_WD  = $room_Details[$room_Count]->room_Markup_Type_WD ?? NULL;
                    $allowed_Hotels_Rooms->room_Markup_Price_WD = $room_Details[$room_Count]->room_Markup_Price_WD ?? NULL;
                    $allowed_Hotels_Rooms->room_Sale_Price_WD   = $room_Details[$room_Count]->room_Sale_Price_WD ?? NULL;
                    
                    $allowed_Hotels_Rooms->room_Price_WE        = $room_Details[$room_Count]->room_Price_WE ?? NULL;
                    $allowed_Hotels_Rooms->room_Markup_Type_WE  = $room_Details[$room_Count]->room_Markup_Type_WE ?? NULL;
                    $allowed_Hotels_Rooms->room_Markup_Price_WE = $room_Details[$room_Count]->room_Markup_Price_WE ?? NULL;
                    $allowed_Hotels_Rooms->room_Sale_Price_WE   = $room_Details[$room_Count]->room_Sale_Price_WE ?? NULL;
                    
                    $allowed_Hotels_Rooms->save();
                }
                
                if($val_AR->room_supplier_name != 135){
                    DB::table('rooms')->where('owner_id',$req->customer_id)->where('hotel_id',$hotel_Details->hotel_id)->update([
                        'room_supplier_name_AR' => 135,
                    ]);
                }
                
                $room_Count++;
            }
            // New
            
            DB::commit();
            
            return response()->json([
                'message' => 'Success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
        
        return response()->json([
            'user_hotels' => $user_hotel_arr,
        ]);
    }
    
    public function add_Alhijaz_Hotels_Rooms_All(Request $req){
        $id             = $req->customer_id;
        $token          = $req->token;
        $img_url        = $req->img_url;
        $hotel_Details  = json_decode($req->hotel_Details);
        $client_Details = json_decode($req->client_Details);
        // dd($hotel_Details,$client_Details);
        
        DB::beginTransaction();
        try {
            $client_Id                          = $client_Details->id;
            $all_rooms                          = json_decode($req->hotel_Details);
            foreach($all_rooms as $val_AR_All){
                $val_AR_Decoded                 = json_decode($val_AR_All);
                foreach($val_AR_Decoded as $val_AR){
                    $already_Allowed                                = DB::table('allowed_Hotels_Rooms')->where('customer_id',$req->customer_id)->where('client_Id',$client_Id)->where('hotel_Id',$val_AR->hotel_id)->where('room_Id',$val_AR->id)->first();
                    // return $already_Allowed;
                    if($already_Allowed == null){
                        // dd($val_AR);
                        
                        if($val_AR->price_week_type == 'for_all_days'){
                            if($val_AR->price_all_days > 0){
                                $price_all_days             = $val_AR->price_all_days;
                            }else{
                                $price_all_days             = $val_AR->price_all_days_wi_markup;
                            }
                            
                            if($price_all_days > 0){
                                $markup_Type_AD     = $req->markup_Type;
                                $markup_Value_AD    = $req->markup_Value;
                                
                                if($markup_Type_AD == '%'){
                                    $total_PAD                  = ($markup_Value_AD * $price_all_days/100) + (float)$price_all_days;
                                    $total_PAD_Fixed            = round($total_PAD);
                                }else{
                                    $total_PAD                  = (float)$markup_Value_AD + (float)$price_all_days;
                                    $total_PAD_Fixed            = round($total_PAD);
                                }
                            }
                        }else{
                            if($val_AR->price_week_type == 'for_week_end'){
                                // WeekDays
                                if($val_AR->weekdays_price > 0){
                                    $price_week_days        = $val_AR->weekdays_price;
                                }else{
                                    $price_week_days        = $val_AR->weekdays_price_wi_markup;
                                }
                                
                                if($price_week_days > 0){
                                    $markup_Type_WD     = $req->markup_Type;
                                    $markup_Value_WD    = $req->markup_Value;
                                    
                                    if($markup_Type_WD == '%'){
                                        $total_PWD              = ($markup_Value_WD * $price_week_days/100) + (float)$price_week_days;
                                        $total_PWD_Fixed        = round($total_PWD);
                                    }else{
                                        $total_PWD              = (float)$markup_Value_WD + (float)$price_week_days;
                                        $total_PWD_Fixed        = round($total_PWD);
                                    }
                                }
                                
                                // WeekEnds
                                if($val_AR->weekends_price > 0){
                                    $price_weekends_days    = $val_AR->weekends_price;
                                }else{
                                    $price_weekends_days    = $val_AR->weekends_price_wi_markup;
                                }
                                
                                if($price_weekends_days > 0){
                                    $markup_Type_WE     = $req->markup_Type;
                                    $markup_Value_WE    = $req->markup_Value;
                                    
                                    if($markup_Type_WE == '%'){
                                        $total_PWE              = ($markup_Value_WE * $price_weekends_days/100) + (float)$price_weekends_days;
                                        $total_PWE_Fixed        = round($total_PWE);
                                    }else{
                                        $total_PWE              = (float)$markup_Value_WE + (float)$price_weekends_days;
                                        $total_PWE_Fixed        = round($total_PWE);
                                    }
                                }
                            }
                        }
                        
                        $allowed_Hotels_Rooms                       = new allowed_Hotels_Rooms();
                        $allowed_Hotels_Rooms->customer_id          = $req->customer_id;
                        $allowed_Hotels_Rooms->client_Id            = $client_Id;
                        $allowed_Hotels_Rooms->hotel_Id             = $val_AR->hotel_id;
                        $allowed_Hotels_Rooms->room_Id              = $val_AR->id;
                        $allowed_Hotels_Rooms->hotel_Markup_Type    = $req->markup_Type ?? NULL;
                        $allowed_Hotels_Rooms->hotel_Markup_Price   = $req->markup_Value ?? NULL;
                        $allowed_Hotels_Rooms->room_Detail          = json_encode($val_AR);
                        
                        $allowed_Hotels_Rooms->room_Price_AD        = $price_all_days ?? NULL;
                        $allowed_Hotels_Rooms->room_Markup_Type_AD  = $markup_Type_AD ?? NULL;
                        $allowed_Hotels_Rooms->room_Markup_Price_AD = $markup_Value_AD ?? NULL;
                        $allowed_Hotels_Rooms->room_Sale_Price_AD   = $total_PAD_Fixed ?? NULL;
                        
                        $allowed_Hotels_Rooms->room_Price_WD        = $price_week_days ?? NULL;
                        $allowed_Hotels_Rooms->room_Markup_Type_WD  = $markup_Type_WD ?? NULL;
                        $allowed_Hotels_Rooms->room_Markup_Price_WD = $markup_Value_WD ?? NULL;
                        $allowed_Hotels_Rooms->room_Sale_Price_WD   = $total_PWD_Fixed ?? NULL;
                        
                        $allowed_Hotels_Rooms->room_Price_WE        = $price_weekends_days ?? NULL;
                        $allowed_Hotels_Rooms->room_Markup_Type_WE  = $markup_Type_WE ?? NULL;
                        $allowed_Hotels_Rooms->room_Markup_Price_WE = $markup_Value_WE ?? NULL;
                        $allowed_Hotels_Rooms->room_Sale_Price_WE   = $total_PWE_Fixed ?? NULL;
                        
                        $allowed_Hotels_Rooms->save();
                    }
                    
                    if($val_AR->room_supplier_name != 135){
                        DB::table('rooms')->where('owner_id',$req->customer_id)->where('hotel_id',$val_AR->hotel_id)->update([
                            'room_supplier_name_AR' => 135,
                        ]);
                    }
                }
            }
            // New
            
            DB::commit();
            
            return response()->json([
                'message' => 'Success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function show_Allowed_Alhijaz_Hotels_Rooms(Request $request){
        DB::beginTransaction();
        try {
            $id             = $request->customer_id;
            $all_Details    = [];
            $client_Details = DB::table('customer_subcriptions')->where('id','!=',$id)->get();
            foreach($client_Details as $val_CD){
                // $allowedHotels  = DB::table('hotels')->where('owner_id',$request->customer_id)
                //                     ->whereJsonContains('allowed_Clients', [['client_Id' => (string) $val_CD->id]])
                //                     ->get();
                
                $all_Hotels     = [];
                $allowd_Hotels  = DB::table('allowed_Hotels_Rooms')->where('customer_id',$id)->where('client_Id',$val_CD->id)->orderBy('created_at', 'desc')->get();
                $collect_Hotels = collect($allowd_Hotels);
                $unique_AH      = $collect_Hotels->unique('hotel_Id')->values()->all();
                
                foreach($unique_AH as $val_AH){
                    $user_hotels    = DB::table('hotels')->where('id',$val_AH->hotel_Id)->orderBy('created_at', 'desc')->first();
                    array_push($all_Hotels,$user_hotels);
                }
                
                $collect_Hotels = collect($all_Hotels);
                $allowedHotels  = $collect_Hotels->unique('id')->values()->all();
                
                if(!empty($allowedHotels) && count($allowedHotels) > 0){
                    foreach($allowedHotels as $val_HD){
                        $total_Booking_Quantity = 0;
                        $total_Revenue          = 0;
                        $hotels_Bookings        = DB::table('hotels_bookings')->where('customer_id',$val_CD->id)->where('provider','Custome_hotel')->get();
                        if(!empty($hotels_Bookings) && count($hotels_Bookings) > 0){
                            foreach($hotels_Bookings as $val_HB) {
                                // dd($val_HB);
                                if($val_HB->reservation_request != null && $val_HB->reservation_request != ''){
                                    $reservation_request    = json_decode($val_HB->reservation_request);
                                    $hotel_checkout_select  = json_decode($reservation_request->hotel_checkout_select);
                                    if($hotel_checkout_select->hotel_id == $val_HD->id){
                                        
                                        if (isset($val_HB->GBP_invoice_price) && is_numeric($val_HB->GBP_invoice_price) && $val_HB->GBP_invoice_price > 0) {
                                            $total_Revenue += (float)$val_HB->GBP_invoice_price;
                                        } else if (isset($val_HB->exchange_price) && is_numeric($val_HB->exchange_price) && $val_HB->exchange_price > 0) {
                                            $total_Revenue += (float)$val_HB->exchange_price;
                                        } else {
                                            $total_Revenue += 0;
                                        }
                                        
                                        $rooms_list             = $hotel_checkout_select->rooms_list;
                                        foreach($rooms_list as $val_RL){
                                            $total_Booking_Quantity += $val_RL->adults ?? '0' + $val_RL->childs ?? '0';
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    if($total_Revenue > 0){
                        $final_Total_Revenue = number_format($total_Revenue,2);
                    }else{
                        $final_Total_Revenue = $total_Revenue;
                    }
                    
                    $all_Details_Object = [
                        'client_Id'                 => $val_CD->id,
                        'company_name'              => $val_CD->company_name,
                        'total_Booking_Quantity'    => $total_Booking_Quantity,
                        'client_Currency'           => $val_CD->currency_value ?? '',
                        'total_Revenue'             => $final_Total_Revenue,
                    ];
                    array_push($all_Details,$all_Details_Object);
                }
            }
            
            // dd($all_Details);
            
            DB::commit();
            
            return response()->json([
                'all_Details' => $all_Details,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong try Again']);
            // something went wrong
        }
    }
    
    public function show_Allowed_Client_Hotels_Rooms(Request $request){
        DB::beginTransaction();
        try {
            $id             = $request->customer_id;
            $all_Details    = [];
            $client_Details = DB::table('customer_subcriptions')->where('id','!=',$id)->get();
            foreach($client_Details as $val_CD){
                // $allowedHotels  = DB::table('hotels')->where('owner_id',$request->customer_id)
                //                     ->whereJsonContains('allowed_Clients', [['client_Id' => (string) $val_CD->id]])
                //                     ->get();
                
                $total_Hotels   = 0;
                $hotel_Id       = [];
                $all_Hotels     = [];
                $allowd_Hotels  = DB::table('allowed_Hotels_Rooms')->where('customer_id',$id)->where('client_Id',$val_CD->id)->orderBy('created_at', 'desc')->get();
                $collect_Hotels = collect($allowd_Hotels);
                $unique_AH      = $collect_Hotels->unique('hotel_Id')->values()->all();
                
                foreach($unique_AH as $val_AH){
                    $user_hotels    = DB::table('hotels')->where('id',$val_AH->hotel_Id)->orderBy('created_at', 'desc')->first();
                    array_push($all_Hotels,$user_hotels);
                }
                
                $collect_Hotels = collect($all_Hotels);
                $allowedHotels  = $collect_Hotels->unique('id')->values()->all();
                
                if(!empty($allowedHotels) && count($allowedHotels) > 0){
                    foreach($allowedHotels as $val_HD){
                        
                        $total_Booking_Quantity = 0;
                        $total_Revenue          = 0;
                        $hotels_Bookings        = DB::table('hotels_bookings')->where('customer_id',$val_CD->id)->where('provider','Custome_hotel')->get();
                        if(!empty($hotels_Bookings) && count($hotels_Bookings) > 0){
                            foreach($hotels_Bookings as $val_HB) {
                                // dd($val_HB);
                                if($val_HB->reservation_request != null && $val_HB->reservation_request != ''){
                                    $reservation_request    = json_decode($val_HB->reservation_request);
                                    $hotel_checkout_select  = json_decode($reservation_request->hotel_checkout_select);
                                    if($hotel_checkout_select->hotel_id == $val_HD->id){
                                        
                                        if (isset($val_HB->GBP_invoice_price) && is_numeric($val_HB->GBP_invoice_price) && $val_HB->GBP_invoice_price > 0) {
                                            $total_Revenue += (float)$val_HB->GBP_invoice_price;
                                        } else if (isset($val_HB->exchange_price) && is_numeric($val_HB->exchange_price) && $val_HB->exchange_price > 0) {
                                            $total_Revenue += (float)$val_HB->exchange_price;
                                        } else {
                                            $total_Revenue += 0;
                                        }
                                        
                                        $rooms_list             = $hotel_checkout_select->rooms_list;
                                        foreach($rooms_list as $val_RL){
                                            $total_Booking_Quantity += $val_RL->adults ?? 0 + $val_RL->childs ?? 0;
                                        }
                                    }
                                }
                            }
                        }
                        
                        array_push($hotel_Id,$val_HD->id);
                        
                        $total_Hotels           += 1;
                    }
                    
                    if($total_Revenue > 0){
                        $final_Total_Revenue = number_format($total_Revenue,2);
                    }else{
                        $final_Total_Revenue = $total_Revenue;
                    }
                    
                    $all_Details_Object             = [
                        'client_Id'                 => $val_CD->id,
                        'hotel_Id'                  => $hotel_Id,
                        'company_name'              => $val_CD->company_name,
                        'total_Hotels'              => $total_Hotels,
                        'total_Booking_Quantity'    => $total_Booking_Quantity,
                        'client_Currency'           => $val_CD->currency_value ?? '',
                        'total_Revenue'             => $final_Total_Revenue,
                    ];
                    array_push($all_Details,$all_Details_Object);
                }
            }
            
            // dd($all_Details);
            
            DB::commit();
            
            return response()->json([
                'all_Details' => $all_Details,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong try Again']);
            // something went wrong
        }
    }
    
    public function edit_Alhijaz_Hotels_Rooms_Old(Request $request){
        
        // dd($request->hotel_Id);
        
        $hotel_Id = json_decode($request->hotel_Id);
        dd($hotel_Id);
        
        $allowd_Hotels  = DB::table('allowed_Hotels_Rooms')->where('client_Id',$request->client_Id)->where('hotel_Id',$request->hotel_Id)->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'allowd_Hotels' => $allowd_Hotels,
        ]);
    }
    
    public function edit_Alhijaz_Hotels_Rooms(Request $request){
        $today_Date     = date('Y-m-d');
        $hotel_Id       = json_decode($request->hotel_Id);
        $user_hotel_arr = [];
        $client_Details = DB::table('customer_subcriptions')->where('id',$request->client_Id)->first();
        foreach($hotel_Id as $val_HID){
            $user_hotels    = DB::table('hotels')->where('id',$val_HID)
                                ->orderBy('created_at', 'desc')->get();
                                // dd($user_hotels);
            foreach($user_hotels as $val_UH){
                $rooms          = DB::table('rooms')->where('hotel_id',$val_HID)->where('availible_to', '>=', $today_Date)->orderBy('id', 'asc')->get();
                $allowed_Rooms  = DB::table('allowed_Hotels_Rooms')->where('client_Id',$request->client_Id)->where('hotel_Id',$val_HID)->orderBy('room_Id', 'asc')->get();
                $new_AR         = [];
                
                if(!empty($allowed_Rooms)){
                    foreach($allowed_Rooms as $val_AR){
                        $Check_Room = DB::table('rooms')->where('id',$val_AR->room_Id)->where('availible_to', '>=', $today_Date)->first();
                        if($Check_Room != null){
                            array_push($new_AR,$val_AR);
                        }
                    }
                }
                
                if(empty($new_AR)){
                    $new_AR = $allowed_Rooms;
                }
                
                // if($val_HID == '512'){
                //     dd($new_AR,$rooms);
                // }
                
                if(empty($rooms) || count($rooms) === 0){
                    $rooms = null;
                }
                $hotels_Rooms   = [
                                    'hotel'             => $val_UH,
                                    'rooms'             => $rooms,
                                    'allowed_Rooms'     => $new_AR,
                                ];
                array_push($user_hotel_arr,$hotels_Rooms);
                
                // if($val_HID == '429'){
                //     dd($hotels_Rooms);
                // }
            }
            $unique_User_Hotels = $user_hotel_arr;
        }
        
        // dd($unique_User_Hotels);
        
        return response()->json([
            'user_hotels'       => $unique_User_Hotels,
            'client_Details'    => $client_Details,
        ]);
    }
    
    public function update_Alhijaz_Hotels_Rooms(Request $req){
        $today_Date     = date('Y-m-d');
        $id             = $req->customer_id;
        $token          = $req->token;
        $img_url        = $req->img_url;
        $hotel_Details  = json_decode($req->all_Hotel_Details);
        $client_Id      = $req->client_Id;  
        // dd($hotel_Details,$client_Id);
        
        DB::beginTransaction();
        try {
            $room_Count                                     = 0;
            $room_Details                                   = json_decode($req->room_Details);
            $all_rooms                                      = DB::table('rooms')->where('owner_id',$req->customer_id)->where('hotel_id',$hotel_Details->id)->where('availible_to', '>=', $today_Date)->orderBy('id', 'desc')->get();
            
            // dd($all_rooms,$room_Details);
            
            foreach($all_rooms as $val_AR){
                $already_Allowed                            = DB::table('allowed_Hotels_Rooms')->where('customer_id',$req->customer_id)->where('client_Id',$req->client_Id)->where('hotel_Id',$val_AR->hotel_id)->where('room_Id',$val_AR->id)->first();
                
                // dd($already_Allowed);
                
                $allowed_Hotels_Rooms                       = allowed_Hotels_Rooms::find($already_Allowed->id);
                
                $allowed_Hotels_Rooms->hotel_Markup_Type    = $req->hotel_Markup_Type;
                $allowed_Hotels_Rooms->hotel_Markup_Price   = $req->hotel_Markup_Price;
                
                $allowed_Hotels_Rooms->room_Price_AD        = $room_Details[$room_Count]->room_Price_AD ?? NULL;
                $allowed_Hotels_Rooms->room_Markup_Type_AD  = $room_Details[$room_Count]->room_Markup_Type_AD ?? NULL;
                $allowed_Hotels_Rooms->room_Markup_Price_AD = $room_Details[$room_Count]->room_Markup_Price_AD ?? NULL;
                $allowed_Hotels_Rooms->room_Sale_Price_AD   = $room_Details[$room_Count]->room_Sale_Price_AD ?? NULL;
                
                $allowed_Hotels_Rooms->room_Price_WD        = $room_Details[$room_Count]->room_Price_WD ?? NULL;
                $allowed_Hotels_Rooms->room_Markup_Type_WD  = $room_Details[$room_Count]->room_Markup_Type_WD ?? NULL;
                $allowed_Hotels_Rooms->room_Markup_Price_WD = $room_Details[$room_Count]->room_Markup_Price_WD ?? NULL;
                $allowed_Hotels_Rooms->room_Sale_Price_WD   = $room_Details[$room_Count]->room_Sale_Price_WD ?? NULL;
                
                $allowed_Hotels_Rooms->room_Price_WE        = $room_Details[$room_Count]->room_Price_WE ?? NULL;
                $allowed_Hotels_Rooms->room_Markup_Type_WE  = $room_Details[$room_Count]->room_Markup_Type_WE ?? NULL;
                $allowed_Hotels_Rooms->room_Markup_Price_WE = $room_Details[$room_Count]->room_Markup_Price_WE ?? NULL;
                $allowed_Hotels_Rooms->room_Sale_Price_WE   = $room_Details[$room_Count]->room_Sale_Price_WE ?? NULL;
                
                $allowed_Hotels_Rooms->update();
                
                if($val_AR->room_supplier_name != 135){
                    DB::table('rooms')->where('owner_id',$req->customer_id)->where('hotel_id',$hotel_Details->id)->update([
                        'room_supplier_name_AR' => 135,
                    ]);
                }
                
                $room_Count++;
            }
            
            DB::commit();
            
            return response()->json([
                'message' => 'Success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
        
        return response()->json([
            'user_hotels' => $user_hotel_arr,
        ]);
    }
    
    public function hotel_Suppliers_Client_Details(Request $request){
        $id             = $request->customer_id;
        $all_Details    = [];
        $client_Details = DB::table('customer_subcriptions')->where('id',$request->client_Id)->first();
        // $allowedHotels  = DB::table('hotels')->where('owner_id',$request->customer_id)
        //                     ->whereJsonContains('allowed_Clients', [['client_Id' => (string) $request->client_Id]])
        //                     ->get();
        
        $all_Hotels     = [];
        $allowd_Hotels  = DB::table('allowed_Hotels_Rooms')->where('client_Id',$request->client_Id)->orderBy('created_at', 'desc')->get();
        $collect_Hotels = collect($allowd_Hotels);
        $unique_AH      = $collect_Hotels->unique('hotel_Id')->values()->all();
        
        foreach($unique_AH as $val_AH){
            // dd($unique_AH);
            $user_hotels    = DB::table('hotels')->where('id',$val_AH->hotel_Id)->orderBy('created_at', 'desc')->first();
            array_push($all_Hotels,$user_hotels);
        }
        
        $collect_Hotels = collect($all_Hotels);
        $allowedHotels  = $collect_Hotels->unique('id')->values()->all();
        
        // dd($allowedHotels);
        
        if(!empty($allowedHotels) && count($allowedHotels) > 0){
            
            foreach($allowedHotels as $val_HD){
                $total_Booking_Quantity = 0;
                $total_Revenue          = 0;
                $hotels_Bookings        = DB::table('hotels_bookings')->where('customer_id',$request->client_Id)->where('provider','Custome_hotel')->get();
                if(!empty($hotels_Bookings) && count($hotels_Bookings) > 0){
                    foreach($hotels_Bookings as $val_HB) {
                        // dd($hotels_Bookings);
                        if($val_HB->reservation_request != null && $val_HB->reservation_request != ''){
                            $reservation_request    = json_decode($val_HB->reservation_request);
                            $hotel_checkout_select  = json_decode($reservation_request->hotel_checkout_select);
                            if($hotel_checkout_select->hotel_id == $val_HD->id){
                                
                                if($val_HB->GBP_invoice_price > 0){
                                    $total_Revenue += $val_HB->GBP_invoice_price ?? 0;
                                }else if($val_HB->exchange_price > 0){
                                    $total_Revenue += $val_HB->exchange_price ?? 0;
                                }else{
                                    $total_Revenue += 0;
                                }
                                
                                // $total_Revenue += number_format($val_HB->GBP_invoice_price,2);
                                // $total_Revenue += number_format($val_HB->exchange_price,2);
                                
                                $rooms_list             = $hotel_checkout_select->rooms_list;
                                foreach($rooms_list as $val_RL){
                                    $total_Booking_Quantity += $val_RL->adults ?? '0' + $val_RL->childs ?? '0';
                                }
                            }
                        }
                    }
                }
                
                // Room Allowed Status
                $status = 'Stop';
                $allowed_Hotels_Rooms = DB::table('allowed_Hotels_Rooms')->where('client_Id',$request->client_Id)->where('hotel_Id',$val_HD->id)->orderBy('created_at', 'desc')->get();
                foreach($allowed_Hotels_Rooms as $val_AH){
                    if($val_AH->hotel_Id == $val_HD->id){
                        if($val_AH->status != 'Stop'){
                            $status = NULL;
                            break;
                        }
                    }
                }
                // Room Allowed Status
                
                // Hotel Markup
                $markup_AR = DB::table('allowed_Hotels_Rooms')->where('client_Id',$request->client_Id)->where('hotel_Id',$val_HD->id)->first();
                // dd($markup_AR);
                // if($val_HD->id == '419'){
                //     dd($request->client_Id,$val_HD->id,$markup_AR,$val_HD);
                // }
                
                if($markup_AR != null){
                    if($markup_AR->room_Markup_Price_AD != null){
                        // All Days
                        $hotel_Markup_Type          = $markup_AR->room_Markup_Type_AD;
                        $hotel_Markup_Price         = $markup_AR->room_Markup_Price_AD;
                    }else{
                        // WeekDays
                        if($markup_AR->room_Markup_Price_WD != null){
                            $hotel_Markup_Type      = $markup_AR->room_Markup_Type_WD;
                            $hotel_Markup_Price     = $markup_AR->room_Markup_Price_WD;
                        }
                        
                        // WeekEnds
                        if($markup_AR->room_Markup_Price_WE != null){
                            $hotel_Markup_Type      = $markup_AR->room_Markup_Type_WE;
                            $hotel_Markup_Price     = $markup_AR->room_Markup_Price_WE;
                        }
                    }
                }
                // Hotel Markup
                
                $all_Details_Object = [
                    'hotel_Id'                  => $val_HD->id,
                    'hotel_Name'                => $val_HD->property_name,
                    'hotel_Markup_Type'         => $hotel_Markup_Type ?? 0,
                    'hotel_Markup_Price'        => $hotel_Markup_Price ?? 0,
                    'status'                    => $status ?? NULL,
                    'total_Booking_Quantity'    => $total_Booking_Quantity,
                    'total_Revenue'             => $total_Revenue,
                    'client_Currency'           => $client_Details->currency_value ?? $client_Details->currency_symbol ?? '',
                ];
                array_push($all_Details,$all_Details_Object);
            }
        }
        
        // dd($all_Details);
        
        return response()->json([
            'all_Details' => $all_Details,
        ]);
    }
    
    public function Client_Allowed_Hotel_Rooms(Request $request){
        $today_Date     = date('Y-m-d');
        $id             = $request->customer_id;
        $all_Details    = [];
        $client_Details = DB::table('customer_subcriptions')->where('id',$request->client_Id)->first();
        $all_Hotels     = [];
        $allowd_Hotels  = DB::table('allowed_Hotels_Rooms')->where('client_Id',$request->client_Id)->where('hotel_Id',$request->hotel_Id)->orderBy('created_at', 'desc')->get();
        
        // dd($allowd_Hotels);
        
        if(!empty($allowd_Hotels) && count($allowd_Hotels) > 0){
            foreach($allowd_Hotels as $val_HD){
                $hotel_Details          = DB::table('hotels')->where('id',$val_HD->hotel_Id)->first();
                $room_Details           = DB::table('rooms')->where('id',$val_HD->room_Id)->first();
                $all_Details_Object     = [
                    'allowed_Details'   => $val_HD,
                    'hotel_Details'     => $hotel_Details,
                    'room_Details'      => $room_Details,
                ];
                array_push($all_Details,$all_Details_Object);
            }
        }
        
        // dd($all_Details);
        
        return response()->json([
            'all_Details' => $all_Details,
        ]);
    }
    
    public function allowed_Hotel_Stop(Request $request){
        DB::beginTransaction();
        try {
            $allowed_Rooms  = DB::table('allowed_Hotels_Rooms')->where('customer_id',$request->customer_id)->where('client_Id',$request->client_Id)->where('hotel_Id',$request->hotel_Id)->get();
            // dd($allowed_Rooms);
            if($allowed_Rooms->isEmpty()) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Rooms Not Exist',
                ]);
            }else{
                foreach($allowed_Rooms as $val_AR){
                    // dd($val_AR);
                    DB::table('allowed_Hotels_Rooms')->where('id',$val_AR->id)->update(['status' => 'Stop']);
                }
                DB::commit();
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Hotel Stop Successfully',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function allowed_Hotel_Open(Request $request){
        DB::beginTransaction();
        try {
            $allowed_Rooms  = DB::table('allowed_Hotels_Rooms')->where('customer_id',$request->customer_id)->where('client_Id',$request->client_Id)->where('hotel_Id',$request->hotel_Id)->get();
            // dd($allowed_Rooms);
            if($allowed_Rooms->isEmpty()) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Rooms Not Exist',
                ]);
            }else{
                foreach($allowed_Rooms as $val_AR){
                    // dd($val_AR);
                    DB::table('allowed_Hotels_Rooms')->where('id',$val_AR->id)->update(['status' => NULL]);
                }
                DB::commit();
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Hotel Allowed Successfully',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function update_Allowed_Hotel_Markup(Request $request){
        DB::beginTransaction();
        try {
            $allowed_Rooms  = DB::table('allowed_Hotels_Rooms')->where('customer_id',$request->customer_id)->where('client_Id',$request->client_Id)->where('hotel_Id',$request->hotel_Id)->get();
            // dd($allowed_Rooms);
            if($allowed_Rooms->isEmpty()) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Rooms Not Exist',
                ]);
            }else{
                foreach($allowed_Rooms as $val_AR){
                    
                    // dd($val_AR->id);
                    
                    if($val_AR->room_Price_AD > 0){
                        $room_Price_AD                  = $val_AR->room_Price_AD;
                        $markup_Type_AD                 = $val_AR->room_Markup_Type_AD;
                        $markup_Value_AD                = $request->hotel_Markup_Price;
                        
                        if($markup_Type_AD == '%'){
                            $total_PAD                  = ($markup_Value_AD * $room_Price_AD/100) + (float)$room_Price_AD;
                            $total_PAD_Fixed            = round($total_PAD);
                        }else{
                            $total_PAD                  = (float)$markup_Value_AD + (float)$room_Price_AD;
                            $total_PAD_Fixed            = round($total_PAD);
                        }
                    }else{
                        // WeekDays
                        if($val_AR->room_Price_WD > 0){
                            $room_Price_WD              = $val_AR->room_Price_WD;
                            $markup_Type_WD             = $val_AR->room_Markup_Type_WD;
                            $markup_Value_WD            = $request->hotel_Markup_Price;
                            
                            if($markup_Type_WD == '%'){
                                $total_PWD              = ($markup_Value_WD * $room_Price_WD/100) + (float)$room_Price_WD;
                                $total_PWD_Fixed        = round($total_PWD);
                            }else{
                                $total_PWD              = (float)$markup_Value_WD + (float)$room_Price_WD;
                                $total_PWD_Fixed        = round($total_PWD);
                            }
                        }
                        
                        // WeekEnds
                        if($val_AR->room_Price_WE > 0){
                            $room_Price_WE              = $val_AR->room_Price_WE;
                            $markup_Type_WE             = $val_AR->room_Markup_Type_WE;
                            $markup_Value_WE            = $request->hotel_Markup_Price;
                            
                            if($markup_Type_WE == '%'){
                                $total_PWE              = ($markup_Value_WE * $room_Price_WE/100) + (float)$room_Price_WE;
                                $total_PWE_Fixed        = round($total_PWE);
                            }else{
                                $total_PWE              = (float)$markup_Value_WE + (float)$room_Price_WE;
                                $total_PWE_Fixed        = round($total_PWE);
                            }
                        }
                    }
                    
                    // dd($total_PAD_Fixed);
                    
                    $allowed_Hotels_Rooms                       = allowed_Hotels_Rooms::find($val_AR->id);
                    
                    $allowed_Hotels_Rooms->room_Markup_Type_AD  = $markup_Type_AD ?? NULL;
                    $allowed_Hotels_Rooms->room_Markup_Price_AD = $markup_Value_AD ?? NULL;
                    $allowed_Hotels_Rooms->room_Sale_Price_AD   = $total_PAD_Fixed ?? NULL;
                    
                    $allowed_Hotels_Rooms->room_Markup_Type_WD  = $markup_Type_WD ?? NULL;
                    $allowed_Hotels_Rooms->room_Markup_Price_WD = $markup_Value_WD ?? NULL;
                    $allowed_Hotels_Rooms->room_Sale_Price_WD   = $total_PWD_Fixed ?? NULL;
                    
                    $allowed_Hotels_Rooms->room_Markup_Type_WE  = $markup_Type_WE ?? NULL;
                    $allowed_Hotels_Rooms->room_Markup_Price_WE = $markup_Value_WE ?? NULL;
                    $allowed_Hotels_Rooms->room_Sale_Price_WE   = $total_PWE_Fixed ?? NULL;
                    
                    // dd($allowed_Hotels_Rooms);
                    
                    $allowed_Hotels_Rooms->update();
                }
                DB::commit();
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Hotel Markup Updated Successfully',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function allowed_Room_Stop(Request $request){
        DB::beginTransaction();
        try {
            $allowed_Rooms  = DB::table('allowed_Hotels_Rooms')->where('id',$request->room_Id)->get();
            // dd($allowed_Rooms);
            if($allowed_Rooms->isEmpty()) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Rooms Not Exist',
                ]);
            }else{
                DB::table('allowed_Hotels_Rooms')->where('id',$request->room_Id)->update(['status' => 'Stop']);
                
                DB::commit();
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Rooms Stop Successfully',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function allowed_Room_Open(Request $request){
        DB::beginTransaction();
        try {
            $allowed_Rooms  = DB::table('allowed_Hotels_Rooms')->where('id',$request->room_Id)->get();
            // dd($allowed_Rooms);
            if($allowed_Rooms->isEmpty()) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Rooms Not Exist',
                ]);
            }else{
                DB::table('allowed_Hotels_Rooms')->where('id',$request->room_Id)->update(['status' => NULL]);
                
                DB::commit();
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Rooms Allowed Successfully',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function edit_Allowed_Room_Stop(Request $request){
        DB::beginTransaction();
        try {
            $allowed_Rooms  = DB::table('allowed_Hotels_Rooms')->where('id',$request->room_Id)->first();
            // dd($allowed_Rooms);
            if($allowed_Rooms != null) {
                DB::commit();
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Rooms Fetch Successfully',
                    'room_Data' => $allowed_Rooms
                ]);
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Rooms Not Exist',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function update_Allowed_Room_Stop(Request $req){
        $token          = $req->token;
        DB::beginTransaction();
        try {
            
            $room_Details                               = json_decode($req->room_Details);
            // dd($room_Details);
            $allowed_Hotels_Rooms                       = allowed_Hotels_Rooms::find($req->allowed_Room_Id);
            
            $allowed_Hotels_Rooms->hotel_Markup_Type    = $req->hotel_Markup_Type;
            $allowed_Hotels_Rooms->hotel_Markup_Price   = $req->hotel_Markup_Price;
            
            $allowed_Hotels_Rooms->room_Price_AD        = $room_Details[0]->room_Price_AD ?? NULL;
            $allowed_Hotels_Rooms->room_Markup_Type_AD  = $room_Details[0]->room_Markup_Type_AD ?? NULL;
            $allowed_Hotels_Rooms->room_Markup_Price_AD = $room_Details[0]->room_Markup_Price_AD ?? NULL;
            $allowed_Hotels_Rooms->room_Sale_Price_AD   = $room_Details[0]->room_Sale_Price_AD ?? NULL;
            
            $allowed_Hotels_Rooms->room_Price_WD        = $room_Details[0]->room_Price_WD ?? NULL;
            $allowed_Hotels_Rooms->room_Markup_Type_WD  = $room_Details[0]->room_Markup_Type_WD ?? NULL;
            $allowed_Hotels_Rooms->room_Markup_Price_WD = $room_Details[0]->room_Markup_Price_WD ?? NULL;
            $allowed_Hotels_Rooms->room_Sale_Price_WD   = $room_Details[0]->room_Sale_Price_WD ?? NULL;
            
            $allowed_Hotels_Rooms->room_Price_WE        = $room_Details[0]->room_Price_WE ?? NULL;
            $allowed_Hotels_Rooms->room_Markup_Type_WE  = $room_Details[0]->room_Markup_Type_WE ?? NULL;
            $allowed_Hotels_Rooms->room_Markup_Price_WE = $room_Details[0]->room_Markup_Price_WE ?? NULL;
            $allowed_Hotels_Rooms->room_Sale_Price_WE   = $room_Details[0]->room_Sale_Price_WE ?? NULL;
            
            // dd($allowed_Hotels_Rooms);
            
            $allowed_Hotels_Rooms->update();
            
            DB::commit();
            return response()->json([
                'message' => 'Success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
        
        return response()->json([
            'user_hotels' => $user_hotel_arr,
        ]);
    }
    
    public function add_Alhijaz_Hotels_Rooms_OLD(Request $req){
        $id             = $req->customer_id;
        $token          = $req->token;
        $img_url        = $req->img_url;
        $hotel_Details  = json_decode($req->all_Hotel_Details);
        $client_Id      = $req->client_Id;
        // dd($hotel_Details,$client_Id);
        
        DB::beginTransaction();
        try {
            $request                    = $hotel_Details;
            $hotel                      = new Hotels;
            $hotel->property_name       = $request->property_name; 
            $hotel->currency_symbol     = $request->currency_symbol; 
            
            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                $hotel->SU_id =  $request->SU_id;
            }
            
            $hotel->currency_value      = $request->currency_value; 
            $hotel->property_desc       = $request->property_desc; 
            $hotel->property_google_map = $request->property_google_map; 
            $hotel->latitude            = $request->latitude; 
            $hotel->longitude           = $request->longitude;
            $hotel->property_country    = $request->property_country; 
            $hotel->property_city       = $request->property_city;  
            $hotel->price_type          = $request->price_type; 
            $hotel->star_type           = $request->star_type; 
            $hotel->status              = $request->status; 
            $hotel->property_type       = $request->property_type; 
            $hotel->b2c_markup          = $request->b2c_markup; 
            $hotel->b2b_markup          = $request->b2b_markup; 
            $hotel->b2e_markup          = $request->b2e_markup; 
            $hotel->service_fee         = $request->service_fee; 
            $hotel->tax_type            = $request->tax_type; 
            $hotel->tax_value           = $request->tax_value; 
            $hotel->facilities          = $request->facilities; 
            $hotel->hotel_email         = $request->hotel_email; 
            $hotel->hotel_website       = $request->hotel_website; 
            $hotel->property_phone      = $request->property_phone; 
            $hotel->property_address    = $request->property_address;
            $hotel->room_gallery        = $request->room_gallery;
            $hotel->property_img        = $request->property_img; 
            $user_id                    = $req->client_Id;
            $hotel->owner_id            = $user_id;
            
            $hotel->image_Url_Other_Dashboard   = $img_url;
            $hotel->from_owner_id       = $request->owner_id;
            $hotel->from_hotel_id       = $request->hotel_id;
            
            // dd($hotel);
            
            $result                     = $hotel->save();
            $hotelId                    = $hotel->id;
            
            // Save Meta Info
            $metaInfo                   = new MetaInfo;
            $metaInfo->meta_title       = $request->meta_title; 
            $metaInfo->keywords         = $request->keywords; 
            $metaInfo->meta_desc        = $request->meta_desc; 
            $metaInfo->hotel_id         = $hotelId;
            $meta_info_Result           = $metaInfo->save();
            
            // Save Poilices
            $policies                   = new Policies;
            $policies->check_in_form    = $request->check_in_form;
            $policies->check_out_to     = $request->check_out_to;
            $policies->payment_option   = $request->payment_option;
            $policies->policy_and_terms = $request->policy_and_terms;
            $policies->hotel_id         = $hotelId;
            $policies_Result            = $policies->save();
            
            // Rooms
            $check_Rooms = DB::table('rooms')->where('rooms.owner_id',$hotel_Details->owner_id)->where('rooms.hotel_id',$hotel_Details->hotel_id)
                            ->join('room_galleries','rooms.id','room_galleries.room_id')->get();
            
            // dd($check_Rooms);
            
            if(count($check_Rooms) > 0){
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
                
                foreach($check_Rooms as $val_CR){
                    
                    $supplier_Exist = DB::table('rooms_Invoice_Supplier')->where('customer_id',$client_Id)->where('AR_Customer_Id',$id)->where('AR_Supplier_Id',$val_CR->room_supplier_name)->first();
                    if($supplier_Exist == null || $supplier_Exist == ''){
                        $supplier_Details   = DB::table('rooms_Invoice_Supplier')->where('customer_id',$id)->where('id',$val_CR->room_supplier_name)->first();
                        $words_RSN          = preg_split('/\s+/', $supplier_Details->room_supplier_name);
                        $room_supplier_name = '';
                        foreach ($words_RSN as $word) {
                            if (ctype_alpha($word[0])) {
                                $room_supplier_name .= strtoupper($word[0]);
                            }
                        }
                        
                        DB::table('rooms_Invoice_Supplier')->insert([  
                            'SU_id'                 => $supplier_Details->SU_id ?? NULL,
                            'customer_id'           => $client_Id,
                            'AR_Customer_Id'        => $supplier_Details->customer_id,
                            'AR_Supplier_Id'        => $val_CR->room_supplier_name,
                            'opening_balance'       => $supplier_Details->opening_balance,
                            'balance'               => $supplier_Details->opening_balance,
                            'opening_payable'       => $supplier_Details->payable,
                            'payable'               => $supplier_Details->payable,
                            'room_supplier_name'    => $supplier_Details->room_supplier_name,
                            'room_supplier_code'    => $room_supplier_name.'-'.rand(0,4444),
                            'email'                 => $supplier_Details->email,
                            'phone_no'              => $supplier_Details->phone_no,
                            'address'               => $supplier_Details->address,
                            'contact_person_name'   => $supplier_Details->contact_person_name,
                            'country'               => $supplier_Details->country,
                            'city'                  => $supplier_Details->city,
                            'more_phone_no'         => $supplier_Details->more_phone_no,
                            'contact_person_name'   => $supplier_Details->contact_person_name,
                        ]);
                    }
                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('customer_id',$client_Id)->where('AR_Customer_Id',$id)->where('AR_Supplier_Id',$val_CR->room_supplier_name)->first();
                    
                    // dd($val_CR);
                    
                    $Rooms                                  = new Rooms;
                    
                    if(isset($val_CR->SU_id) && $val_CR->SU_id != null && $val_CR->SU_id != ''){
                        $Rooms->SU_id                       =  $val_CR->SU_id;
                    }
                    
                    $Rooms->from_owner_id                   = $request->owner_id;
                    $Rooms->from_room_id                    = $val_CR->room_id;
                    
                    $Rooms->hotel_id                        = $hotelId;
                    $Rooms->rooms_on_rq                     = $val_CR->rooms_on_rq;
                    $Rooms->room_type_id                    = $val_CR->room_type_id; 
                    $Rooms->room_type_name                  = $val_CR->room_type_name; 
                    $Rooms->room_type_cat                   = $val_CR->room_type_cat; 
                    $Rooms->room_view                       = $val_CR->room_view; 
                    $Rooms->price_type                      = $val_CR->price_type; 
                    $Rooms->adult_price                     = $val_CR->adult_price;
                    $Rooms->child_price                     = $val_CR->child_price; 
                    $Rooms->quantity                        = $val_CR->quantity;  
                    $Rooms->min_stay                        = $val_CR->min_stay; 
                    $Rooms->max_child                       = $val_CR->max_child; 
                    $Rooms->max_adults                      = $val_CR->max_adults; 
                    $Rooms->extra_beds                      = $val_CR->extra_beds; 
                    $Rooms->extra_beds_charges              = $val_CR->extra_beds_charges; 
                    $Rooms->availible_from                  = $val_CR->availible_from; 
                    $Rooms->availible_to                    = $val_CR->availible_to; 
                    $Rooms->room_option_date                = $val_CR->room_option_date; 
                    $Rooms->price_week_type                 = $val_CR->price_week_type; 
                    $Rooms->price_all_days                  = $val_CR->price_all_days;
                    $Rooms->room_supplier_name              = $supplier_data->id;
                    $Rooms->room_meal_type                  = $val_CR->room_meal_type;
                    $Rooms->weekdays                        = $val_CR->weekdays;
                    $Rooms->weekdays_price                  = $val_CR->weekdays_price;
                    $Rooms->weekends                        = $val_CR->weekends;
                    $Rooms->weekends_price                  = $val_CR->weekends_price; 
                    $Rooms->room_description                = $val_CR->room_description;
                    $Rooms->amenitites                      = $val_CR->amenitites;
                    $Rooms->status                          = $val_CR->status;
                    $Rooms->room_img                        = $val_CR->room_img; 
                    $Rooms->more_room_type_details          = ''; 
                    $Rooms->owner_id                        = $user_id;
                    
                    $Rooms->cancellation_details            = $val_CR->cancellation_details;
                    $Rooms->additional_meal_type            = $val_CR->additional_meal_type;
                    $Rooms->additional_meal_type_charges    = $val_CR->additional_meal_type_charges;
                    $Rooms->display_on_web                  = $val_CR->display_on_web;
                    $Rooms->markup_type                     = $val_CR->markup_type;
                    $Rooms->markup_value                    = $val_CR->markup_value;
                    $Rooms->price_all_days_wi_markup        = $val_CR->price_all_days_wi_markup;
                    $Rooms->weekdays_price_wi_markup        = $val_CR->weekdays_price_wi_markup;
                    $Rooms->weekends_price_wi_markup        = $val_CR->weekends_price_wi_markup;
                    $result                                 = $Rooms->save();
                    $Roomsid                                = $Rooms->id;
                    
                    if(isset($supplier_data)){
                        $week_days_total        = 0;
                        $week_end_days_totals   = 0;
                        $total_price            = 0;
                        if($Rooms->price_week_type == 'for_all_days'){
                            $avaiable_days      = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                            $total_price        = $Rooms->price_all_days * $avaiable_days;
                        }else{
                            $avaiable_days      = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                            $all_days           = getBetweenDates($Rooms->availible_from, $Rooms->availible_to);
                            $week_days          = json_decode($Rooms->weekdays);
                            $week_end_days      = json_decode($Rooms->weekends);
                            
                            foreach($all_days as $day_res){
                                $day                    = date('l', strtotime($day_res));
                                $day                    = trim($day);
                                $week_day_found         = false;
                                $week_end_day_found     = false;
                                
                                foreach($week_days as $week_day_res){
                                    if($week_day_res == $day){
                                        $week_day_found = true;
                                    }
                                }
                                
                                if($week_day_found){
                                    $week_days_total        += $Rooms->weekdays_price;
                                }else{
                                    $week_end_days_totals   += $Rooms->weekends_price;
                                }
                            }
                            
                            $total_price        = $week_days_total + $week_end_days_totals;
                        }
                        
                        $all_days_price         = $total_price * $Rooms->quantity;
                        $supplier_balance       = $supplier_data->balance + $all_days_price;
                        
                        DB::table('hotel_supplier_ledger')->insert([
                            'supplier_id'       => $supplier_data->id,
                            'payment'           => $all_days_price,
                            'balance'           => $supplier_balance,
                            'payable_balance'   => $supplier_data->payable,
                            'room_id'           => $Roomsid,
                            'customer_id'       => $user_id,
                            'date'              => date('Y-m-d'),
                            'available_from'    => $Rooms->availible_from,
                            'available_to'      => $Rooms->availible_to,
                            'room_quantity'     => $Rooms->quantity,
                        ]);
                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                    }
                    
                    if(isset($val_CR->more_room_type_details) && !empty($val_CR->more_room_type_details)){
                        $more_rooms = json_decode($val_CR->more_room_type_details);
                        
                        foreach($more_rooms as $room_more_res){
                            $meal_policy            = $room_more_res->more_meal_policy;
                            $meal_policy            = json_encode($meal_policy);
                            $concellation_policy    = $room_more_res->more_concellation_policy;
                            $guests_pay_days        = $room_more_res->more_guests_pay_days;
                            $guests_pay             = $room_more_res->more_guests_pay;
                            $prepaymentpolicy       = $room_more_res->more_prepaymentpolicy;
                            
                            $cancellation_details       = (object)[
                                'meal_policy'           => $meal_policy,
                                'concellation_policy'   => $concellation_policy,
                                'guests_pay_days'       => $guests_pay_days,
                                'guests_pay'            => $guests_pay,
                                'prepaymentpolicy'      => $prepaymentpolicy,
                            ];
                            $cancellation_details               = json_encode($cancellation_details);
                            $room_insert_id                     = DB::table('rooms')->insertGetId([
                                'room_gen_id'                   => $room_more_res->room_gen_id,
                                'hotel_id'                      => $hotelId,
                                'rooms_on_rq'                   => $room_more_res->more_rooms_on_rq,
                                'room_type_id'                  => $room_more_res->more_room_type,
                                'room_type_name'                => $room_more_res->more_room_type_name,
                                'room_type_cat'                 => $room_more_res->more_room_type_id,
                                'room_view'                     => $room_more_res->more_room_view,
                                'price_type'                    => NULL,
                                'adult_price'                   => NULL,
                                'child_price'                   => NULL, 
                                'quantity'                      => $room_more_res->more_quantity,
                                'booked'                        => $room_more_res->more_quantity_booked,
                                'min_stay'                      => $room_more_res->more_min_stay, 
                                'max_child'                     => $room_more_res->more_max_childrens, 
                                'max_adults'                    => $room_more_res->more_max_adults,
                                'extra_beds'                    => $room_more_res->more_extra_beds, 
                                'extra_beds_charges'            => $room_more_res->more_extra_beds_charges, 
                                'availible_from'                => $room_more_res->more_room_av_from,
                                'availible_to'                  => $room_more_res->more_room_av_to,
                                'room_option_date'              => $room_more_res->more_room_option_date, 
                                'price_week_type'               => $room_more_res->more_week_price_type,
                                'price_all_days'                => $room_more_res->more_price_all_days,
                                'room_supplier_name'            => $supplier_data->id,
                                'room_meal_type'                => $room_more_res->more_room_meal_type,
                                'weekdays'                      => $room_more_res->more_weekdays,
                                'weekdays_price'                => $room_more_res->more_week_days_price,
                                'weekends'                      => $room_more_res->more_weekend,
                                'weekends_price'                => $room_more_res->more_week_end_price,
                                'room_description'              => $val_CR->room_description,
                                'amenitites'                    => $val_CR->amenitites,
                                'status'                        => $val_CR->status,
                                'more_room_type_details'        => NULL,
                                'owner_id'                      => $user_id,
                                'cancellation_details'          => $cancellation_details,
                                'additional_meal_type'          => $room_more_res->more_additional_meal_type,
                                'additional_meal_type_charges'  => $room_more_res->more_additional_meal_type_charges,
                                'display_on_web'                => $room_more_res->display_on_web,
                                'markup_type'                   => $room_more_res->markup_type,
                                'markup_value'                  => $room_more_res->markup_value,
                                'price_all_days_wi_markup'      => $room_more_res->price_all_days_wi_markup,
                                'weekdays_price_wi_markup'      => $room_more_res->weekdays_price_wi_markup,
                                'weekends_price_wi_markup'      => $room_more_res->weekends_price_wi_markup,
                            ]);
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_more_res->more_room_supplier_name)->select('id','balance','payable')->first();
                            
                            if(isset($supplier_data)){
                                // echo "Enter hre ";
                                
                                $week_days_total        = 0;
                                $week_end_days_totals   = 0;
                                $total_price        = 0;
                                if($room_more_res->more_week_price_type == 'for_all_days'){
                                    $avaiable_days  = dateDiffInDays($room_more_res->more_room_av_from, $room_more_res->more_room_av_to);
                                    $total_price    = $room_more_res->more_price_all_days * $avaiable_days;
                                }else{
                                    $avaiable_days  = dateDiffInDays($room_more_res->more_room_av_from, $room_more_res->more_room_av_to);
                                    $all_days       = getBetweenDates($room_more_res->more_room_av_from, $room_more_res->more_room_av_to);
                                    $week_days      = json_decode($room_more_res->more_weekdays);
                                    $week_end_days  = json_decode($room_more_res->more_weekend);
                                    
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
                                            $week_days_total        += $room_more_res->more_week_days_price;
                                        }else{
                                            $week_end_days_totals   += $room_more_res->more_week_end_price;
                                        }
                                    }
                                    $total_price    = $week_days_total + $week_end_days_totals;
                                }
                                
                                $all_days_price     = $total_price * $room_more_res->more_quantity;
                                $supplier_balance   = $supplier_data->balance + $all_days_price;
                                
                                
                                DB::table('hotel_supplier_ledger')->insert([
                                    'supplier_id'       => $supplier_data->id,
                                    'payment'           => $all_days_price,
                                    'balance'           => $supplier_balance,
                                    'payable_balance'   => $supplier_data->payable,
                                    'room_id'           => $room_insert_id,
                                    'customer_id'       => $user_id,
                                    'date'              => date('Y-m-d'),
                                    'available_from'    => $room_more_res->more_room_av_from,
                                    'available_to'      => $room_more_res->more_room_av_to,
                                    'room_quantity'     => $room_more_res->more_quantity,
                                ]);
                                
                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                            } 
                        }
                    }
                    
                    $roomGallery                = new RoomGallery();
                    $roomGallery->img_name      = $val_CR->img_name;
                    $roomGallery->room_id       = $Roomsid;
                    $roomGallery                = $roomGallery->save();
                }
            }
            // Rooms
            
            DB::commit();
            
            return response()->json([
                'message' => 'Success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
        
        return response()->json([
            'user_hotels' => $user_hotel_arr,
        ]);
    }
    
    public function get_Client_Rooms(Request $request){
        $allowed_Rooms  = DB::table('allowed_Hotels_Rooms')->where('customer_id',$request->customer_id)->where('client_Id',$request->client_Id)->where('hotel_Id',$request->hotel_Id)->orderBy('room_Id', 'asc')->get();
        // dd($allowed_Rooms);
        if($allowed_Rooms->isEmpty()) {
            return response()->json([
                'status'            => 'error',
            ]);
        }else{
            return response()->json([
                'status'            => 'success',
                'allowed_Rooms'     => $allowed_Rooms,
            ]);
        }
    }
}