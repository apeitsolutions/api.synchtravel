<?php

namespace App\Http\Controllers\HotelMangersApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\RoomsType;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\hotel_manager\RoomGallery;
use App\Models\allowed_Hotels_Rooms;
use Session;
use Auth;
use DB;
class RoomControllerApi extends Controller
{
    // Meal Type
    public function create_Meal_Type(Request $request){
        $user_hotels        = Hotels::select(['id','property_name'])->where('hotels.owner_id',$request->customer_id)->where('hotels.id',$request->id)->first();
        $meal_Types         = DB::table('meal_Types')->where('token',$request->token)->where('customer_id',$request->customer_id)->where('selected_Hotel',$request->id)->get();
        return response()->json([
            'user_hotels'   => $user_hotels,
            'meal_Types'    => $meal_Types,
        ]);
    }
    
    public function add_Meal_Type(Request $request){
        DB::beginTransaction();
        try {
            $check_Meal_Type = DB::table('meal_Types')->where('token',$request->token)->where('customer_id',$request->customer_id)
                                ->where('start_Date',$request->start_Date)->where('end_Date',$request->end_Date)
                                ->where('selected_Hotel',$request->selected_Hotel)->where('meal_Name',$request->meal_Name)
                                ->where('meal_Price',$request->meal_Price)
                                ->first();
            if($check_Meal_Type != null){
                return response()->json([
                    'message'           => 'error',
                ]);
            }else{
                DB::table('meal_Types')->insert([
                    'token'             => $request->token,
                    'SU_id'             => $request->SU_id ?? NULL,
                    'customer_id'       => $request->customer_id,
                    'selected_Hotel'    => $request->selected_Hotel,
                    'meal_Name'         => $request->meal_Name,
                    'start_Date'        => $request->start_Date,
                    'end_Date'          => $request->end_Date,
                    'meal_Price'        => $request->meal_Price,
                ]);
            }
            
            DB::commit();
            return response()->json([
                'message'           => 'success',
            ]);
        } catch (Throwable $e) {
            DB::rollback();
            
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function edit_Meal_Type(Request $request){
        DB::beginTransaction();
        try {
            $meal_Types         = DB::table('meal_Types')->where('token',$request->token)->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            if($meal_Types != null){
                $user_hotels        = Hotels::select(['id','property_name'])->where('owner_id',$request->customer_id)->where('id',$meal_Types->selected_Hotel)->first();
                if($user_hotels != null){
                    DB::commit();
                    return response()->json([
                        'message'       => 'success',
                        'meal_Types'    => $meal_Types,
                        'user_hotels'   => $user_hotels,
                    ]);
                }else{
                    DB::commit();
                    return response()->json([
                        'message'       => 'error',
                    ]);
                }
            }else{
                DB::commit();
                return response()->json([
                    'message'       => 'error',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();
            
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function update_Meal_Type(Request $request){
        DB::beginTransaction();
        try {
            $check_Meal_Type = DB::table('meal_Types')->where('token',$request->token)->where('id',$request->id)->first();
            if($check_Meal_Type != null){
                DB::table('meal_Types')->where('token',$request->token)->where('id',$request->id)->update([
                    'meal_Name'         => $request->meal_Name,
                    'start_Date'        => $request->start_Date,
                    'end_Date'          => $request->end_Date,
                    'meal_Price'        => $request->meal_Price,
                ]);
            }else{
                return response()->json([
                    'message'           => 'error',
                ]);
            }
            
            DB::commit();
            return response()->json([
                'message'           => 'success',
            ]);
        } catch (Throwable $e) {
            DB::rollback();
            
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function delete_Meal_Type(Request $request){
        DB::beginTransaction();
        try {
            $meal_Types         = DB::table('meal_Types')->where('token',$request->token)->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            if($meal_Types != null){
                DB::table('meal_Types')->where('token',$request->token)->where('customer_id',$request->customer_id)->where('id',$request->id)->delete();
                DB::commit();
                return response()->json([
                    'message'   => 'success',
                ]);
            }else{
                DB::commit();
                return response()->json([
                    'message'   => 'error',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();
            
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    // Meal Type
    
    public function index(Request $request){
      $id = $request->customer_id;
      $user_rooms = Rooms::join('hotels','rooms.hotel_id','=','hotels.id')
                     ->join('rooms_types','rooms.room_type_id','=','rooms_types.room_type')
                     
                     ->where('rooms.owner_id',$id)
                     ->orderBy('rooms.created_at', 'desc')
                     ->get(['rooms.more_room_type_details','rooms.id','rooms.room_img','rooms.availible_from','rooms.availible_to','rooms.quantity','rooms.status','rooms_types.room_type','hotels.property_name']);
      return response()->json([
         'user_rooms' => $user_rooms,
      ]);
        // return view('template/frontend/userdashboard/pages/hotel_manager.rooms_list',compact('user_rooms'));
    }

    public function showAddRoomFrom(Request $request){
        $id                 = $request->customer_id;
        $user_hotels        = Hotels::select(['id','property_name'])
                                ->where('hotels.owner_id',$id)
                                ->get();
        $hotel_suppliers    = DB::table('rooms_Invoice_Supplier')
                                ->where('customer_id',$id)
                                ->get();
        $roomTypes          = RoomsType::where('customer_id',$id)->get();
        return response()->json([
           'user_hotels'        => $user_hotels,
           'roomTypes'          => $roomTypes,
           'hotel_suppliers'    => $hotel_suppliers
        ]);
    }
   
    public function add_room_type_sub(Request $request){
        $result = DB::table('rooms_types')->insert([
                    'SU_id'         => $request->SU_id ?? NULL,
                    'room_type'     => $request->type_name,
                    'no_of_persons' => $request->person,
                    'parent_cat'    => $request->parent_cat,
                    'customer_id'   => $request->customer_id,
                ]);
        if($result){
            $room_types = DB::table('rooms_types')->where('customer_id',$request->customer_id)->get();            
            return response()->json([
                'status'        => 'success',
                'room_types'    => $room_types,
            ]);
        }else{
            return response()->json([
                'status'        => 'error',
                'room_types'    => $room_types,
            ]);
        }   
    }
    
    public function fetch_room_types(Request $request){
        DB::beginTransaction();          
        try {
            $room_types = DB::table('rooms_types')->where('customer_id',$request->customer_id)->get();
            if($room_types){
                $parent_Category_Room_Types     = DB::table('parent_Category_Room_Types')->where('customer_id',$request->customer_id)->get();
                return response()->json([
                    'status'                        => 'success',
                    'room_types'                    => $room_types,
                    'parent_Category_Room_Types'    => $parent_Category_Room_Types,
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function edit_Room_Types(Request $request){
        DB::beginTransaction();          
        try {
            $room_types                             = DB::table('rooms_types')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            $parent_Category_Room_Types             = DB::table('parent_Category_Room_Types')->where('customer_id',$request->customer_id)->get();
            if($room_types != null){
                return response()->json([
                    'status'                        => 'success',
                    'room_types'                    => $room_types,
                    'parent_Category_Room_Types'    => $parent_Category_Room_Types,
                ]);
            }else{
                return response()->json([
                    'status'        => 'error',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function update_Room_Types(Request $request){
        DB::beginTransaction();          
        try {
            $room_types             = DB::table('rooms_types')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            if($room_types != null){
                // dd($room_types);
                DB::table('rooms_types')->where('customer_id',$request->customer_id)->where('id',$request->id)->update([
                    'parent_cat'    => $request->parent_cat,
                    'room_type'     => $request->type_name,
                    'no_of_persons' => $request->person,
                ]);
                DB::commit();
                return response()->json([
                    'status'        => 'success',
                ]);
            }else{
                return response()->json([
                    'status'        => 'error',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function delete_Room_Types(Request $request){
        DB::beginTransaction();          
        try {
            $room_types             = DB::table('rooms_types')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            if($room_types != null){
                // dd($room_types);
                DB::table('rooms_types')->where('customer_id',$request->customer_id)->where('id',$request->id)->delete();
                DB::commit();
                return response()->json([
                    'status'        => 'success',
                ]);
            }else{
                return response()->json([
                    'status'        => 'error',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    // Parent Category Room Type
    public function add_Parent_Category_Room_Type(Request $request){
        DB::beginTransaction();          
        try {
            $parent_Category_Room_Types     = DB::table('parent_Category_Room_Types')->where('customer_id',$request->customer_id)
                                                ->whereRaw('LOWER(parent_Category_Name) = ?', [strtolower($request->parent_Category_Name)])->first();
            if($parent_Category_Room_Types != null){
                return response()->json([
                    'status'                => 'error',
                ]);
            }else{
                DB::table('parent_Category_Room_Types')->insert([
                    'SU_id'                 => $request->SU_id ?? NULL,
                    'customer_id'           => $request->customer_id,
                    'parent_Category_Name'  => $request->parent_Category_Name,
                ]);
                DB::commit();
                return response()->json([
                    'status'        => 'success',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function edit_Parent_Category_Room_Type(Request $request){
        DB::beginTransaction();          
        try {
            $parent_Category_Room_Types             = DB::table('parent_Category_Room_Types')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            if($parent_Category_Room_Types != null){
                return response()->json([
                    'status'                        => 'success',
                    'parent_Category_Room_Types'    => $parent_Category_Room_Types,
                ]);
                
            }else{
                return response()->json([
                    'status'                        => 'error',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function update_Parent_Category_Room_Type(Request $request){
        DB::beginTransaction();          
        try {
            $parent_Category_Room_Types     = DB::table('parent_Category_Room_Types')->where('customer_id',$request->customer_id)->where('id','!=',$request->id)
                                                ->whereRaw('LOWER(parent_Category_Name) = ?', [strtolower($request->parent_Category_Name)])->first();
            if($parent_Category_Room_Types != null){
                return response()->json([
                    'status'        => 'error',
                ]);
            }else{
                DB::table('parent_Category_Room_Types')->where('customer_id',$request->customer_id)->where('id',$request->id)->update([
                    'parent_Category_Name'  => $request->parent_Category_Name,
                ]);
                DB::commit();
                return response()->json([
                    'status'        => 'success',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function delete_Parent_Category_Room_Type(Request $request){
        DB::beginTransaction();          
        try {
            $parent_Category_Room_Types     = DB::table('parent_Category_Room_Types')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            if($parent_Category_Room_Types != null){
                $rooms_types                = DB::table('rooms_types')->where('customer_id',$request->customer_id)
                                                ->whereRaw('LOWER(parent_cat) = ?', [strtolower($parent_Category_Room_Types->parent_Category_Name)])->first();
                if($rooms_types != null){
                    return response()->json([
                        'status'        => 'error',
                    ]);
                }else{
                    DB::table('parent_Category_Room_Types')->where('customer_id',$request->customer_id)->where('id',$request->id)->delete();
                    DB::commit();
                    return response()->json([
                        'status'        => 'success',
                    ]);
                }
            }else{
                return response()->json([
                    'status'        => 'error',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    // Parent Category Room Type

    public function addRoom(Request $request){
        // print_r($request->all());
        
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
            
        // die;
         DB::beginTransaction();
                  
                     try {
                         
                            $room_type_obj = json_decode($request->room_type);
                            $Rooms = new Rooms;
                            
                            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                                $Rooms->SU_id =  $request->SU_id;
                            }
                            
                            $Rooms->hotel_id =  $request->hotel;
                            $Rooms->rooms_on_rq=$request->rooms_on_rq;
                            $Rooms->room_type_id =  $room_type_obj->parent_cat; 
                            $Rooms->room_type_name =  $room_type_obj->room_type; 
                            $Rooms->room_type_cat =  $room_type_obj->id; 
                            $Rooms->room_view =  $request->room_view; 
                            $Rooms->price_type =  $request->price_type; 
                            $Rooms->adult_price =  $request->adult_price;
                            $Rooms->child_price =  $request->child_price; 
                            $Rooms->quantity =  $request->quantity;  
                            $Rooms->min_stay =  $request->min_stay; 
                            $Rooms->max_child =  $request->max_childrens; 
                            $Rooms->max_adults =  $request->max_adults; 
                            $Rooms->extra_beds =  $request->extra_beds; 
                            $Rooms->extra_beds_charges =  $request->extra_beds_charges; 
                            $Rooms->availible_from =  $request->room_av_from; 
                            $Rooms->availible_to =  $request->room_av_to; 
                            $Rooms->room_option_date =  $request->room_option_date; 
                            $Rooms->price_week_type =  $request->week_price_type; 
                            $Rooms->price_all_days =  $request->price_all_days;
                            $Rooms->room_supplier_name =  $request->room_supplier_name;
                            $Rooms->room_meal_type  = $request->room_meal_type;
                            // $Rooms->weekdays = serialize($request->weekdays);
                            $Rooms->weekdays = $request->weekdays;
                            $Rooms->weekdays_price =  $request->week_days_price; 
                            // $Rooms->weekends =  serialize($request->weekend); 
                            $Rooms->weekends =  $request->weekend;
                            $Rooms->weekends_price =  $request->week_end_price; 
                            $Rooms->room_description =  $request->room_desc; 
                            // $Rooms->amenitites =  serialize($request->amenities);
                            $Rooms->amenitites =  $request->amenities;
                            $Rooms->status =  $request->status;
                            $Rooms->room_img =  $request->room_img; 
                            $Rooms->more_room_type_details =  ''; 
                            $user_id = $request->customer_id;
                            $Rooms->owner_id = $user_id;
                            
                            $Rooms->cancellation_details =  $request->cancellation_details;
                            $Rooms->additional_meal_type =  $request->additional_meal_type;
                            $Rooms->additional_meal_type_charges =  $request->additional_meal_type_charges;
                            $Rooms->display_on_web     = $request->display_on_web;
                            $Rooms->markup_type     = $request->markup_type;
                            $Rooms->markup_value     = $request->markup_value;
                            $Rooms->price_all_days_wi_markup     = $request->price_all_days_wi_markup;
                            $Rooms->weekdays_price_wi_markup     = $request->weekdays_price_wi_markup;
                            $Rooms->weekends_price_wi_markup     = $request->weekends_price_wi_markup;
                    
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
                            
                            if(isset($request->more_room_type_details) && !empty($request->more_room_type_details)){
                                      $more_rooms = json_decode($request->more_room_type_details);
                                      
                                      foreach($more_rooms as $room_more_res){
                                             $meal_policy=$room_more_res->more_meal_policy;
                                            $meal_policy=json_encode($meal_policy);
                                            $concellation_policy=$room_more_res->more_concellation_policy;
                                             $guests_pay_days=$room_more_res->more_guests_pay_days;
                                             $guests_pay=$room_more_res->more_guests_pay;
                                             $prepaymentpolicy=$room_more_res->more_prepaymentpolicy;
                                             
                                             
                                             $cancellation_details=(object)[
                                                        'meal_policy'            => $meal_policy,
                                                        'concellation_policy'            =>$concellation_policy,
                                                        'guests_pay_days'            => $guests_pay_days,
                                                        'guests_pay'         => $guests_pay,
                                                        'prepaymentpolicy'           => $prepaymentpolicy,
                                                        
                                                        ];
                                                        $cancellation_details=json_encode($cancellation_details);
                                          
                                          
                                         $room_insert_id =  DB::table('rooms')->insertGetId([
                                                        'room_gen_id' =>  $room_more_res->room_gen_id,
                                                        'hotel_id' =>  $request->hotel,
                                                        'rooms_on_rq'=>$room_more_res->more_rooms_on_rq,
                                                        'room_type_id' =>  $room_more_res->more_room_type,
                                                        'room_type_name' =>  $room_more_res->more_room_type_name,
                                                        'room_type_cat' =>  $room_more_res->more_room_type_id,
                                                        'room_view' =>  $room_more_res->more_room_view,
                                                        'price_type' =>  NULL,
                                                        'adult_price' =>  NULL,
                                                        'child_price' =>  NULL, 
                                                        'quantity' =>  $room_more_res->more_quantity,
                                                        'booked' =>  $room_more_res->more_quantity_booked,
                                                        'min_stay' =>  $room_more_res->more_min_stay, 
                                                        'max_child' =>  $room_more_res->more_max_childrens, 
                                                        'max_adults' =>  $room_more_res->more_max_adults,
                                                        'extra_beds' =>  $room_more_res->more_extra_beds, 
                                                        'extra_beds_charges' =>  $room_more_res->more_extra_beds_charges, 
                                                        'availible_from' =>  $room_more_res->more_room_av_from,
                                                        'availible_to' =>  $room_more_res->more_room_av_to,
                                                        'room_option_date' =>  $room_more_res->more_room_option_date, 
                                                        'price_week_type' =>  $room_more_res->more_week_price_type,
                                                        'price_all_days' =>  $room_more_res->more_price_all_days,
                                                        'room_supplier_name' =>  $room_more_res->more_room_supplier_name,
                                                        'room_meal_type' => $room_more_res->more_room_meal_type,
                                                        // 'weekdays' => serialize($room_more_res->weekdays);
                                                        'weekdays' => $room_more_res->more_weekdays,
                                                        'weekdays_price' =>  $room_more_res->more_week_days_price,
                                                        // 'weekends' =>  serialize($room_more_res->weekend); 
                                                        'weekends' =>  $room_more_res->more_weekend,
                                                        'weekends_price' =>  $room_more_res->more_week_end_price,
                                                        'room_description' =>  $request->room_desc,
                                                        // 'amenitites' =>  serialize($room_more_res->amenities);
                                                        'amenitites' =>  $request->amenities,
                                                        'status' =>  $request->status,
                                                        'more_room_type_details' => NULL,
                                                        'owner_id' => $user_id,
                                                        'cancellation_details' =>  $cancellation_details,
                                                        'additional_meal_type' =>  $room_more_res->more_additional_meal_type,
                                                        'additional_meal_type_charges' =>  $room_more_res->more_additional_meal_type_charges,
                                                        'display_on_web' => $room_more_res->display_on_web,
                                                        'markup_type' => $room_more_res->markup_type,
                                                        'markup_value' => $room_more_res->markup_value,
                                                        'price_all_days_wi_markup' => $room_more_res->price_all_days_wi_markup,
                                                        'weekdays_price_wi_markup' => $room_more_res->weekdays_price_wi_markup,
                                                        'weekends_price_wi_markup' => $room_more_res->weekends_price_wi_markup,
                                              ]);
                                              
                                                        $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_more_res->more_room_supplier_name)->select('id','balance','payable')->first();
                                                        // print_r($supplier_data);
                                                        if(isset($supplier_data)){
                                                            // echo "Enter hre ";
                                                            
                                                                 $week_days_total = 0;
                                                                 $week_end_days_totals = 0;
                                                                 $total_price = 0;
                                                                if($room_more_res->more_week_price_type == 'for_all_days'){
                                                                    $avaiable_days = dateDiffInDays($room_more_res->more_room_av_from, $room_more_res->more_room_av_to);
                                                                    $total_price = $room_more_res->more_price_all_days * $avaiable_days;
                                                                }else{
                                                                     $avaiable_days = dateDiffInDays($room_more_res->more_room_av_from, $room_more_res->more_room_av_to);
                                                                     
                                                                     
                                                                     $all_days = getBetweenDates($room_more_res->more_room_av_from, $room_more_res->more_room_av_to);
                                                                     $week_days = json_decode($room_more_res->more_weekdays);
                                                                     $week_end_days = json_decode($room_more_res->more_weekend);
                                                                     
                                                                    
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
                                                                             $week_days_total += $room_more_res->more_week_days_price;
                                                                         }else{
                                                                             $week_end_days_totals += $room_more_res->more_week_end_price;
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
                                                                
                                                                
                                                                // echo "The Supplier balance is ".$supplier_data->balance."<br>";
                                                                $all_days_price = $total_price * $room_more_res->more_quantity;
                                                            $supplier_balance = $supplier_data->balance + $all_days_price;
                                                            
                                                            // update Agent Balance
                                                            
                                                            DB::table('hotel_supplier_ledger')->insert([
                                                                'supplier_id'=>$supplier_data->id,
                                                                'payment'=>$all_days_price,
                                                                'balance'=>$supplier_balance,
                                                                'payable_balance'=>$supplier_data->payable,
                                                                'room_id'=>$room_insert_id,
                                                                'customer_id'=>$user_id,
                                                                'date'=>date('Y-m-d'),
                                                                'available_from'=>$room_more_res->more_room_av_from,
                                                                'available_to'=>$room_more_res->more_room_av_to,
                                                                'room_quantity'=>$room_more_res->more_quantity,
                                                                ]);
                                                                
                                                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                                        }
                                              
                                              
                                      }
                                    //   print_r($more_rooms);
                                }
                            
                            
                            $roomGallery =new RoomGallery();
                              $roomGallery->img_name = $request->room_gallery;
                              $roomGallery->room_id = $Roomsid;
                              $roomGallery = $roomGallery->save();
                              
                              DB::commit();
                              
                            return response()->json([
                                'message'=>'success',
                                'Rooms'=>$Rooms,
                                'roomGallery'=>$roomGallery,
                                ]);
                                
                     } catch (Throwable $e) {

                            DB::rollback();
                            
                            echo $e;
                            return response()->json(['message'=>'error','booking_id'=> '']);
                        }
   }

    public function updateShowForm(Request $request,$id_room){
      $id = $request->customer_id;
      $id_room = $request->id;
      $user_hotels = Hotels::select(['id','property_name'])
                 ->where('hotels.owner_id',$id)
                 ->get();
                

      $roomTypes = RoomsType::where('customer_id',$id)->get();
      $roomData    = Rooms::find($id_room);
      
       $selected_hotel = Hotels::select(['id'])
                 ->where('id',$roomData->hotel_id)
                 ->first();
      
      $roomGallery = RoomGallery::where('room_id',$id_room)->get();
      $rooms_Invoice_Supplier= DB::table('rooms_Invoice_Supplier')->where('customer_id',$id)->get();
       $room_galleries= DB::table('room_galleries')->where('room_id',$id_room)->get();
      
      return response()->json([
         'user_hotels' => $user_hotels,
         'selected_hotel'=>$selected_hotel,
         'roomTypes'   => $roomTypes,
         'roomData'    => $roomData,
         'roomGallery' => $roomGallery,
         'rooms_Invoice_Supplier' => $rooms_Invoice_Supplier,
         'room_galleries' => $room_galleries,
      ]);

      // return view('template/frontend/userdashboard/pages/hotel_manager.view_room',compact('user_hotels','roomTypes','roomData','roomGallery'));
   }
    
    function dateDiffInDays1($date1, $date2){
        $diff = strtotime($date2) - strtotime($date1);
        return abs(round($diff / 86400));
    }
    
    function getBetweenDates1($startDate, $endDate){
        $rangArray  = [];
        $startDate  = strtotime($startDate);
        $endDate    = strtotime($endDate);
             
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
            $date           = date('Y-m-d', $currentDate);
            $rangArray[]    = $date;
        }
        return $rangArray;
    }
    
    public function update_room(Request $request,$id_room){
        function dateDiffInDays($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
        }
        
        function getBetweenDates($startDate, $endDate){
            $rangArray          = [];
            $startDate          = strtotime($startDate);
            $endDate            = strtotime($endDate);
            for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                $date           = date('Y-m-d', $currentDate);
                $rangArray[]    = $date;
            }
            return $rangArray;
        }
        
        $room_type_obj                      = json_decode($request->room_type);
        $id_room                            = $request->id;
        $Rooms                              = Rooms::find($id_room);
        
        $previous_room                      = (Object)[
            'availible_from'                => $Rooms->availible_from,
            'availible_to'                  => $Rooms->availible_to,
            'price_all_days'                => $Rooms->price_all_days,
            'price_week_type'               => $Rooms->price_week_type,
            'weekends'                      => $Rooms->weekends,
            'weekdays'                      => $Rooms->weekdays,
            'weekdays_price'                => $Rooms->weekdays_price,
            'weekends_price'                => $Rooms->weekends_price,
            'room_supplier_name'            => $Rooms->room_supplier_name,
            'quantity'                      => $Rooms->quantity
        ];
        
        $Rooms->hotel_id                    = $request->hotel;
        $Rooms->rooms_on_rq                 = $request->rooms_on_rq;
        $Rooms->room_type_id                = $room_type_obj->parent_cat; 
        $Rooms->room_type_name              = $room_type_obj->room_type; 
        $Rooms->room_type_cat               = $room_type_obj->id; 
        $Rooms->room_view                   = $request->room_view; 
        $Rooms->price_type                  = $request->price_type; 
        $Rooms->adult_price                 = $request->adult_price;
        $Rooms->child_price                 = $request->child_price; 
        $Rooms->quantity                    = $request->quantity;  
        $Rooms->min_stay                    = $request->min_stay; 
        $Rooms->max_child                   = $request->max_childrens; 
        $Rooms->max_adults                  = $request->max_adults; 
        $Rooms->extra_beds                  = $request->extra_beds; 
        $Rooms->extra_beds_charges          = $request->extra_beds_charges; 
        $Rooms->availible_from              = $request->room_av_from; 
        $Rooms->availible_to                = $request->room_av_to; 
        $Rooms->room_option_date            = $request->room_option_date; 
        $Rooms->price_week_type             = $request->week_price_type; 
        $Rooms->price_all_days              = $request->price_all_days;
        $Rooms->room_supplier_name          = $request->room_supplier_name;
        $Rooms->room_meal_type              = $request->room_meal_type;
        $Rooms->weekdays                    = $request->weekdays;
        $Rooms->weekdays_price              = $request->week_days_price;
        $Rooms->weekends                    = $request->weekend;
        $Rooms->weekends_price              = $request->week_end_price; 
        $Rooms->display_on_web              = $request->display_on_web;
        $Rooms->markup_type                 = $request->markup_type;
        $Rooms->markup_value                = $request->markup_value;
        $Rooms->price_all_days_wi_markup    = $request->price_all_days_wi_markup;
        $Rooms->weekdays_price_wi_markup    = $request->weekdays_price_wi_markup;
        $Rooms->weekends_price_wi_markup    = $request->weekends_price_wi_markup;
        $Rooms->room_description            = $request->room_desc; 
        $Rooms->amenitites                  = $request->amenities;
        $Rooms->status                      = $request->status;
        
        if(!empty($request->room_img)){
            $Rooms->room_img                = $request->room_img; 
        }
        
        $Rooms->more_room_type_details      = $request->more_room_type_details; 
        $Rooms->cancellation_details        = $request->cancellation_details;
        $Rooms->additional_meal_type        = $request->additional_meal_type;
        $Rooms->additional_meal_type_charges= $request->additional_meal_type_charges;
        $user_id                            = $request->customer_id;
        $Rooms->owner_id                    = $user_id;
        
        DB::beginTransaction();          
        try {
            $Rooms->update();
            
            $allowed_Rooms                      = DB::table('allowed_Hotels_Rooms')->where('customer_id',$request->customer_id)->where('hotel_Id',$request->hotel)->where('room_Id',$id_room)->get();
            // dd($allowed_Rooms);
            if(!empty($allowed_Rooms)){
                foreach($allowed_Rooms as $val_AR){
                    if($Rooms->price_week_type == 'for_all_days'){
                        if($Rooms->price_all_days > 0){
                            $price_all_days             = $Rooms->price_all_days;
                        }else{
                            $price_all_days             = $Rooms->price_all_days_wi_markup;
                        }
                        
                        if($price_all_days > 0){
                            $markup_Type_AD             = $val_AR->room_Markup_Type_AD;
                            $markup_Value_AD            = $val_AR->room_Markup_Price_AD;
                            
                            if($markup_Type_AD == '%'){
                                $total_PAD              = ($markup_Value_AD * $price_all_days/100) + (float)$price_all_days;
                                $total_PAD_Fixed        = round($total_PAD);
                            }else{
                                $total_PAD              = (float)$markup_Value_AD + (float)$price_all_days;
                                $total_PAD_Fixed        = round($total_PAD);
                            }
                        }
                    }else{
                        if($Rooms->price_week_type == 'for_week_end'){
                            // WeekDays
                            if($Rooms->weekdays_price > 0){
                                $price_week_days        = $Rooms->weekdays_price;
                            }else{
                                $price_week_days        = $Rooms->weekdays_price_wi_markup;
                            }
                            
                            if($price_week_days > 0){
                                $markup_Type_WD         = $val_AR->room_Markup_Type_WD;
                                $markup_Value_WD        = $val_AR->room_Markup_Price_WD;
                                
                                if($markup_Type_WD == '%'){
                                    $total_PWD          = ($markup_Value_WD * $price_week_days/100) + (float)$price_week_days;
                                    $total_PWD_Fixed    = round($total_PWD);
                                }else{
                                    $total_PWD          = (float)$markup_Value_WD + (float)$price_week_days;
                                    $total_PWD_Fixed    = round($total_PWD);
                                }
                            }
                            
                            // WeekEnds
                            if($Rooms->weekends_price > 0){
                                $price_weekends_days    = $Rooms->weekends_price;
                            }else{
                                $price_weekends_days    = $Rooms->weekends_price_wi_markup;
                            }
                            
                            if($price_weekends_days > 0){
                                $markup_Type_WE         = $val_AR->room_Markup_Type_WE;
                                $markup_Value_WE        = $val_AR->room_Markup_Price_WE;
                                
                                if($markup_Type_WE == '%'){
                                    $total_PWE          = ($markup_Value_WE * $price_weekends_days/100) + (float)$price_weekends_days;
                                    $total_PWE_Fixed    = round($total_PWE);
                                }else{
                                    $total_PWE          = (float)$markup_Value_WE + (float)$price_weekends_days;
                                    $total_PWE_Fixed    = round($total_PWE);
                                }
                            }
                        }
                    }
                    
                    $allowed_Hotels_Rooms                       = allowed_Hotels_Rooms::find($val_AR->id);
                    
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
                    
                    // dd($allowed_Hotels_Rooms);
                    
                    $allowed_Hotels_Rooms->update();
                }
            }
            // dd('STOP');
            
            $roomGallery = RoomGallery::where('room_id',$id_room)->first();
            
            if($roomGallery){
                $roomGallery->img_name  = $request->room_gallery;
                $roomGallery->room_id   = $id_room;
                $result                 = $roomGallery->update();
            }
            $week_days_total        = 0;
            $week_end_days_totals   = 0;
            $total_price            = 0;
            
            if($previous_room->price_week_type == 'for_all_days'){
                $avaiable_days  = dateDiffInDays($previous_room->availible_from, $previous_room->availible_to);
                $total_price    = $previous_room->price_all_days * $avaiable_days;
            }else{
                $avaiable_days  = dateDiffInDays($previous_room->availible_from, $previous_room->availible_to);
                $all_days       = getBetweenDates($previous_room->availible_from, $previous_room->availible_to);
                $week_days      = json_decode($previous_room->weekdays);
                $week_end_days  = json_decode($previous_room->weekends);
                
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
                        $week_days_total += $previous_room->weekdays_price;
                    }else{
                        $week_end_days_totals += $previous_room->weekends_price;
                    }
                }
                
                $total_price    = $week_days_total + $week_end_days_totals;
            }
            
            $prev_days_price        = $total_price * $previous_room->quantity;
            $week_days_total        = 0;
            $week_end_days_totals   = 0;
            $total_price            = 0;
            
            if($Rooms->price_week_type == 'for_all_days'){
                $avaiable_days  = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                $total_price    = $Rooms->price_all_days * $avaiable_days;
            }else{
                $avaiable_days   = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                $all_days       = getBetweenDates($Rooms->availible_from, $Rooms->availible_to);
                $week_days      = json_decode($Rooms->weekdays);
                $week_end_days  = json_decode($Rooms->weekends);
                
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
                        $week_days_total        += $Rooms->weekdays_price;
                    }else{
                        $week_end_days_totals   += $Rooms->weekends_price;
                    }
                }
                
                $total_price = $week_days_total + $week_end_days_totals;
            }
            
            $new_days_price = $total_price * $Rooms->quantity;
            $price_differ   = $new_days_price - $prev_days_price;
            
            if($previous_room->room_supplier_name == $request->room_supplier_name){
                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                if(isset($supplier_data)){
                    $supplier_balance = $supplier_data->balance + $price_differ;
                    DB::table('hotel_supplier_ledger')->insert([
                        'supplier_id'=>$supplier_data->id,
                        'payment'=>$price_differ,
                        'balance'=>$supplier_balance,
                        'payable_balance'=>$supplier_data->payable,
                        'room_id'=>$Rooms->id,
                        'customer_id'=>$user_id,
                        'date'=>date('Y-m-d'),
                        'available_from'=>$Rooms->availible_from,
                        'available_to'=>$Rooms->availible_to,
                        'room_quantity'=>$Rooms->quantity,
                        'remarks'=>'Room Updated'
                    ]);
                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                }
                    
            }else{
                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$previous_room->room_supplier_name)->select('id','balance','payable')->first();
                if(isset($supplier_data)){
                    $supplier_balance = $supplier_data->balance - $price_differ;
                    DB::table('hotel_supplier_ledger')->insert([
                        'supplier_id'=>$supplier_data->id,
                        'received'=>$price_differ,
                        'balance'=>$supplier_balance,
                        'payable_balance'=>$supplier_data->payable,
                        'room_id'=>$Rooms->id,
                        'customer_id'=>$user_id,
                        'date'=>date('Y-m-d'),
                        'available_from'=>$Rooms->availible_from,
                        'available_to'=>$Rooms->availible_to,
                        'room_quantity'=>$Rooms->quantity,
                        'remarks'=>'Room Updated'
                    ]);
                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                }
                
                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request->room_supplier_name)->select('id','balance','payable')->first();
                if(isset($supplier_data)){
                    $supplier_balance = $supplier_data->balance + $price_differ;
                    DB::table('hotel_supplier_ledger')->insert([
                        'supplier_id'=>$supplier_data->id,
                        'payment'=>$price_differ,
                        'balance'=>$supplier_balance,
                        'payable_balance'=>$supplier_data->payable,
                        'room_id'=>$Rooms->id,
                        'customer_id'=>$user_id,
                        'date'=>date('Y-m-d'),
                        'available_from'=>$Rooms->availible_from,
                        'available_to'=>$Rooms->availible_to,
                        'room_quantity'=>$Rooms->quantity,
                        'remarks'=>'Room Updated'
                    ]);
                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                }
            }
            
            $other_Rooms      = Rooms::where('from_room_id',$id_room)->where('from_owner_id',$request->customer_id)->get();
            // dd($other_Rooms);
            if(count($other_Rooms) > 0){
                foreach($other_Rooms as $val_OR){
                    $update_OR = $this->update_other_room($request,$val_OR->id,$val_OR->hotel_id,$val_OR->owner_id);
                    // dd($update_OR);
                }
            }
            
            // dd('STOP');
            
            DB::commit();
            
            return response()->json([
                'message'       => 'success',
                'Rooms'         => $Rooms,
                'RoomGallery'   => $result,
            ]);
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
   }
   
    public function updateRoomPriceAjax(Request $request){
        DB::beginTransaction();          
        try {
            $Rooms                      = Rooms::find($request->id);
            if($request->allDaysPriceEdit > 0){
                $Rooms->price_all_days  = $request->allDaysPriceEdit;
            }
            if($request->weekdays_price > 0){
                $Rooms->weekdays_price  = $request->weekDaysPriceEdit;
            }
            if($request->weekends_price > 0){
                $Rooms->weekends_price  = $request->weekEndsPriceEdit;
            }
            $Rooms->update();
            
            DB::commit();
            return response()->json(['message'=>'success']);
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function update_other_room($request,$room_Id,$hotel_Id,$owner_Id){
        $room_type_obj                      = json_decode($request->room_type);
        $Rooms                              = Rooms::find($room_Id);
        
        // dd($Rooms->hotel_id);
        
        $previous_room                      = (Object)[
            'availible_from'                => $Rooms->availible_from,
            'availible_to'                  => $Rooms->availible_to,
            'price_all_days'                => $Rooms->price_all_days,
            'price_week_type'               => $Rooms->price_week_type,
            'weekends'                      => $Rooms->weekends,
            'weekdays'                      => $Rooms->weekdays,
            'weekdays_price'                => $Rooms->weekdays_price,
            'weekends_price'                => $Rooms->weekends_price,
            'room_supplier_name'            => $Rooms->room_supplier_name,
            'quantity'                      => $Rooms->quantity
        ];
        
        $Rooms->hotel_id                    = $hotel_Id;
        $Rooms->rooms_on_rq                 = $request->rooms_on_rq;
        $Rooms->room_type_id                = $room_type_obj->parent_cat; 
        $Rooms->room_type_name              = $room_type_obj->room_type; 
        $Rooms->room_type_cat               = $room_type_obj->id; 
        $Rooms->room_view                   = $request->room_view; 
        $Rooms->price_type                  = $request->price_type; 
        $Rooms->adult_price                 = $request->adult_price;
        $Rooms->child_price                 = $request->child_price; 
        $Rooms->quantity                    = $request->quantity;  
        $Rooms->min_stay                    = $request->min_stay; 
        $Rooms->max_child                   = $request->max_childrens; 
        $Rooms->max_adults                  = $request->max_adults; 
        $Rooms->extra_beds                  = $request->extra_beds; 
        $Rooms->extra_beds_charges          = $request->extra_beds_charges; 
        $Rooms->availible_from              = $request->room_av_from; 
        $Rooms->availible_to                = $request->room_av_to; 
        $Rooms->room_option_date            = $request->room_option_date; 
        $Rooms->price_week_type             = $request->week_price_type; 
        $Rooms->price_all_days              = $request->price_all_days;
        $Rooms->room_supplier_name          = $request->room_supplier_name;
        $Rooms->room_meal_type              = $request->room_meal_type;
        $Rooms->weekdays                    = $request->weekdays;
        $Rooms->weekdays_price              = $request->week_days_price;
        $Rooms->weekends                    = $request->weekend;
        $Rooms->weekends_price              = $request->week_end_price; 
        $Rooms->display_on_web              = $request->display_on_web;
        $Rooms->markup_type                 = $request->markup_type;
        $Rooms->markup_value                = $request->markup_value;
        $Rooms->price_all_days_wi_markup    = $request->price_all_days_wi_markup;
        $Rooms->weekdays_price_wi_markup    = $request->weekdays_price_wi_markup;
        $Rooms->weekends_price_wi_markup    = $request->weekends_price_wi_markup;
        $Rooms->room_description            = $request->room_desc; 
        $Rooms->amenitites                  = $request->amenities;
        $Rooms->status                      = $request->status;
        
        if(!empty($request->room_img)){
            $Rooms->room_img                = $request->room_img; 
        }
        
        $Rooms->more_room_type_details      = $request->more_room_type_details; 
        $Rooms->cancellation_details        = $request->cancellation_details;
        $Rooms->additional_meal_type        = $request->additional_meal_type;
        $Rooms->additional_meal_type_charges= $request->additional_meal_type_charges;
        $user_id                            = $request->customer_id;
        $Rooms->owner_id                    = $owner_Id;
        
        DB::beginTransaction();          
        try {
            $Rooms->update();
            
            $roomGallery = RoomGallery::where('room_id',$room_Id)->first();
            
            if($roomGallery){
                $roomGallery->img_name  = $request->room_gallery;
                $roomGallery->room_id   = $room_Id;
                $result                 = $roomGallery->update();
            }
            $week_days_total        = 0;
            $week_end_days_totals   = 0;
            $total_price            = 0;
            
            if($previous_room->price_week_type == 'for_all_days'){
                $avaiable_days  = $this->dateDiffInDays1($previous_room->availible_from, $previous_room->availible_to);
                $total_price = $previous_room->price_all_days * $avaiable_days;
            }else{
                $avaiable_days  = $this->dateDiffInDays1($previous_room->availible_from, $previous_room->availible_to);
                $all_days       = $this->getBetweenDates1($previous_room->availible_from, $previous_room->availible_to);
                $week_days      = json_decode($previous_room->weekdays);
                $week_end_days  = json_decode($previous_room->weekends);
                
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
                        $week_days_total += $previous_room->weekdays_price;
                    }else{
                        $week_end_days_totals += $previous_room->weekends_price;
                    }
                }
                
                $total_price    = $week_days_total + $week_end_days_totals;
            }
            
            $prev_days_price        = $total_price * $previous_room->quantity;
            $week_days_total        = 0;
            $week_end_days_totals   = 0;
            $total_price            = 0;
            
            if($Rooms->price_week_type == 'for_all_days'){
                $avaiable_days  = $this->dateDiffInDays1($Rooms->availible_from, $Rooms->availible_to);
                $total_price    = $Rooms->price_all_days * $avaiable_days;
            }else{
                $avaiable_days  = $this->dateDiffInDays1($Rooms->availible_from, $Rooms->availible_to);
                $all_days       = $this->getBetweenDates1($Rooms->availible_from, $Rooms->availible_to);
                $week_days      = json_decode($Rooms->weekdays);
                $week_end_days  = json_decode($Rooms->weekends);
                
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
                        $week_days_total        += $Rooms->weekdays_price;
                    }else{
                        $week_end_days_totals   += $Rooms->weekends_price;
                    }
                }
                
                $total_price = $week_days_total + $week_end_days_totals;
            }
            
            $new_days_price = $total_price * $Rooms->quantity;
            $price_differ   = $new_days_price - $prev_days_price;
            
            if($previous_room->room_supplier_name == $request->room_supplier_name){
                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                if(isset($supplier_data)){
                    $supplier_balance = $supplier_data->balance + $price_differ;
                    DB::table('hotel_supplier_ledger')->insert([
                        'supplier_id'=>$supplier_data->id,
                        'payment'=>$price_differ,
                        'balance'=>$supplier_balance,
                        'payable_balance'=>$supplier_data->payable,
                        'room_id'=>$Rooms->id,
                        'customer_id'=>$user_id,
                        'date'=>date('Y-m-d'),
                        'available_from'=>$Rooms->availible_from,
                        'available_to'=>$Rooms->availible_to,
                        'room_quantity'=>$Rooms->quantity,
                        'remarks'=>'Room Updated'
                    ]);
                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                }
                    
            }else{
                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$previous_room->room_supplier_name)->select('id','balance','payable')->first();
                if(isset($supplier_data)){
                    $supplier_balance = $supplier_data->balance - $price_differ;
                    DB::table('hotel_supplier_ledger')->insert([
                        'supplier_id'=>$supplier_data->id,
                        'received'=>$price_differ,
                        'balance'=>$supplier_balance,
                        'payable_balance'=>$supplier_data->payable,
                        'room_id'=>$Rooms->id,
                        'customer_id'=>$user_id,
                        'date'=>date('Y-m-d'),
                        'available_from'=>$Rooms->availible_from,
                        'available_to'=>$Rooms->availible_to,
                        'room_quantity'=>$Rooms->quantity,
                        'remarks'=>'Room Updated'
                    ]);
                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                }
                
                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request->room_supplier_name)->select('id','balance','payable')->first();
                if(isset($supplier_data)){
                    $supplier_balance = $supplier_data->balance + $price_differ;
                    DB::table('hotel_supplier_ledger')->insert([
                        'supplier_id'=>$supplier_data->id,
                        'payment'=>$price_differ,
                        'balance'=>$supplier_balance,
                        'payable_balance'=>$supplier_data->payable,
                        'room_id'=>$Rooms->id,
                        'customer_id'=>$user_id,
                        'date'=>date('Y-m-d'),
                        'available_from'=>$Rooms->availible_from,
                        'available_to'=>$Rooms->availible_to,
                        'room_quantity'=>$Rooms->quantity,
                        'remarks'=>'Room Updated'
                    ]);
                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'message'       =>'success',
                'Rooms'         => $Rooms,
                'RoomGallery'   => $result,
            ]);
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
   }
   
    //04-02-2023
    // public function addRoomsForms(Request $request){
//       $id = $request->customer_id;
//       $user_hotels = Hotels::select(['id','property_name'])
//                   ->where('hotels.owner_id',$id)
//                   ->get();
//     $hotel_suppliers = DB::table('rooms_Invoice_Supplier')
//                   ->where('customer_id',$id)
//                   ->get();

//       $roomTypes = RoomsType::all();
//       return response()->json([
//          'user_hotels' => $user_hotels,
//          'roomTypes'   => $roomTypes,
//          'hotel_suppliers'=>$hotel_suppliers
//       ]);      
//     //   return view('template/frontend/userdashboard/pages/hotel_manager.add_rooms',compact('user_hotels','roomTypes'));
//   }
    //04-02-2023
    
    public function addRoomsForms(Request $request){
      $id = $request->customer_id;
      $user_hotels = Hotels::select(['id','property_name'])
                  ->where('hotels.owner_id',$id)
                  ->get();
    $hotel_suppliers = DB::table('rooms_Invoice_Supplier')
                  ->where('customer_id',$id)
                  ->get();

      $roomTypes = RoomsType::where('customer_id',$id)->get();
      return response()->json([
         'user_hotels' => $user_hotels,
         'roomTypes'   => $roomTypes,
         'hotel_suppliers'=>$hotel_suppliers
      ]);      
    //   return view('template/frontend/userdashboard/pages/hotel_manager.add_rooms',compact('user_hotels','roomTypes'));
   }
    
    public function viewRooms(Request $request){
        $id         = $request->customer_id;
        $hotel_id   = $request->hotel_id;
        // $Rooms      = Rooms::where('hotel_id',$hotel_id)
        //                 ->orWhereJsonContains('allowed_Clients', [['client_Id' => $id]])
        //                 ->Where('owner_id',$request->customer_id)->get();
        $Rooms      = Rooms::where('hotel_id',$hotel_id)->get();
        $hotels     = Hotels::where('id',$hotel_id)->select('property_name','currency_symbol','allowed_Clients','owner_id')->get();
        return response()->json([
            'rooms'     => $Rooms,
            'hotels'    => $hotels,
        
        ]);
   }
    
    public function deleteRooms(Request $request){
        DB::beginTransaction();
        try {
            $roomsExist                 = DB::table('rooms')->where('id',$request->id)->get();
            if(!empty($roomsExist) && count($roomsExist) > 0){
                // $allowed_Hotels_Rooms   = DB::table('allowed_Hotels_Rooms')->where('customer_id',$request->customer_id)->where('hotel_Id',$roomsExist[0]->hotel_id)->get();
                $allowed_Hotels_Rooms   = DB::table('allowed_Hotels_Rooms')->where('customer_id',$request->customer_id)->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(room_Detail, '$.id')) = ?", [$request->id])->get();
                if(count($allowed_Hotels_Rooms) > 0){
                    return response()->json(['message'=>'Room Allowed to some clients','rooms' => $roomsExist]);
                }
                
                $Rooms                  = Rooms::find($request->id);
                $Rooms->delete();
                DB::table('room_promotions')->where('room_Id',$request->id)->delete();
                
                DB::commit();
                return response()->json(['message' => 'Successful','rooms' => $Rooms]);
            }else{
                DB::commit();
                return response()->json(['message' => 'Rooms Already Deleted','rooms' => $Rooms]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error']);
        }
   }
    
    public function hotels_amenities(Request $request){
      $id = $request->customer_id;
      $id = $request->id;
      
       $Rooms = Hotels::where('id',$id)->first();
      
     return response()->json([
         'hotels' => $Rooms,
        
        
      ]); 
      
      
    //   return view('template/frontend/userdashboard/pages/hotel_manager.add_rooms',compact('user_hotels','roomTypes'));
   }
    
    public function hotels_galleries(Request $request){
      $id = $request->customer_id;
      $id = $request->id;
      
       $Rooms = Hotels::where('id',$id)->first();
      
     return response()->json([
         'hotels' => $Rooms,
        
        
      ]); 
      
      
    //   return view('template/frontend/userdashboard/pages/hotel_manager.add_rooms',compact('user_hotels','roomTypes'));
   }
   
    //04-02-2023
    //04-02-2023
   
    public function view_room_facilities(Request $request){
      
      
      $rooms_facilities=DB::table('rooms_facilities')->where('customer_id',$request->customer_id)->get();
      
     return response()->json([
         'rooms_facilities' => $rooms_facilities,
        
        
      ]); 
      

   }
    
    public function submit_rooms_facilities(Request $request){
      
      
      $rooms_facilities=DB::table('rooms_facilities')->insert([
          
          'room_type'=>$request->room_type,
          'customer_id'=>$request->customer_id,
          'room_facilities'=>$request->facilities_details,
          
          ]);
      
     return response()->json([
         'rooms_facilities' => $rooms_facilities,
        
        
      ]); 
      

   }
    
    public function custom_hotel_facilities(Request $request){
      
      
      $facilities=DB::table('rooms_facilities')->where('room_type',$request->room_type)->where('customer_id',$request->customer_id)->get();
      
     return response()->json([
         'facilities' => $facilities,

      ]); 
      

   }
}
