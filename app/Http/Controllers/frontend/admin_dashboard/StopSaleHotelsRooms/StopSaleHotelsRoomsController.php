<?php

namespace App\Http\Controllers\frontend\admin_dashboard\StopSaleHotelsRooms;

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

class StopSaleHotelsRoomsController extends Controller
{
    public function all_Hotels_For_Stop_Sale(Request $request){
        $id             = $request->customer_id;
        $all_Details    = [];
        $allowedHotels  = DB::table('hotels')->where('owner_id',$request->customer_id)->get();
        
        if(!empty($allowedHotels) && count($allowedHotels) > 0){
            foreach($allowedHotels as $val_HD){
                $all_Details_Object = [
                    'hotel_Id'                  => $val_HD->id,
                    'hotel_Name'                => $val_HD->property_name,
                    'status'                    => $val_HD->stop_Sale_Status ?? 'Stop',
                    'hotel_Details'             => $val_HD,
                ];
                array_push($all_Details,$all_Details_Object);
            }
        }
        
        return response()->json([
            'all_Details' => $all_Details,
        ]);
    }
    
    public function hotel_Stop_All(Request $request){
        DB::beginTransaction();
        try {
            $all_Rooms  = DB::table('rooms')->where('owner_id',$request->customer_id)->where('hotel_Id',$request->hotel_Id)->get();
            if($all_Rooms->isEmpty()) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Rooms Not Exist',
                ]);
            }else{
                DB::table('hotels')->where('id',$request->hotel_Id)->update(['stop_Sale_Status' => 'Stop']);
                foreach($all_Rooms as $val_AR){
                    DB::table('rooms')->where('id',$val_AR->id)->update(['stop_Sale_Status' => 'Stop']);
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
    
    public function hotel_Open_All(Request $request){
        DB::beginTransaction();
        try {
            $all_Rooms  = DB::table('rooms')->where('owner_id',$request->customer_id)->where('hotel_Id',$request->hotel_Id)->get();
            if($all_Rooms->isEmpty()) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Rooms Not Exist',
                ]);
            }else{
                DB::table('hotels')->where('id',$request->hotel_Id)->update(['stop_Sale_Status' => NULL]);
                foreach($all_Rooms as $val_AR){
                    DB::table('rooms')->where('id',$val_AR->id)->update(['stop_Sale_Status' => NULL]);
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
    
    public function hotel_Rooms(Request $request){
        $today_Date     = date('Y-m-d');
        $id             = $request->customer_id;
        $all_Details    = [];
        $client_Details = DB::table('customer_subcriptions')->where('id',$request->customer_id)->first();
        $all_Rooms      = DB::table('rooms')->where('owner_id',$request->customer_id)->where('hotel_Id',$request->hotel_Id)->orderBy('created_at', 'desc')->get();
        if(!empty($all_Rooms) && count($all_Rooms) > 0){
            foreach($all_Rooms as $val_HD){
                $hotel_Details              = DB::table('hotels')->where('id',$val_HD->hotel_id)->first();
                $check_Stop_Sale_Exist      = DB::table('stop_Sale_Date_Wise')->where('customer_id',$request->customer_id)->where('hotel_id',$val_HD->hotel_id)
                                                ->where('room_id',$val_HD->id)->where('status','Stop')->first();
                if(!empty($check_Stop_Sale_Exist)){
                    $date_Wise_SL_Exist     = 'YES';
                }else{
                    $date_Wise_SL_Exist     = 'NO';
                }
                $all_Details_Object         = [
                    'room_Details'          => $val_HD,
                    'hotel_Details'         => $hotel_Details,
                    'date_Wise_SL_Exist'    => $date_Wise_SL_Exist,
                    'check_Stop_Sale_Exist' => $check_Stop_Sale_Exist,
                ];
                array_push($all_Details,$all_Details_Object);
            }
        }
        
        return response()->json([
            'all_Details' => $all_Details,
        ]);
    }
    
    public function room_Stop_Single(Request $request){
        DB::beginTransaction();
        try {
            $room_Exist  = DB::table('rooms')->where('id',$request->room_Id)->get();
            if($room_Exist->isEmpty()) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Rooms Not Exist',
                ]);
            }else{
                DB::table('rooms')->where('id',$request->room_Id)->update(['stop_Sale_Status' => 'Stop']);
                
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
    
    public function room_Open_Single(Request $request){
        DB::beginTransaction();
        try {
            $room_Exist  = DB::table('rooms')->where('id',$request->room_Id)->get();
            if($room_Exist->isEmpty()) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Rooms Not Exist',
                ]);
            }else{
                DB::table('rooms')->where('id',$request->room_Id)->update(['stop_Sale_Status' => NULL]);
                
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
    
    public function room_Stop_Date_Wise(Request $request){
        DB::beginTransaction();
        try {
            $check_Stop_Sale_Exist  = DB::table('stop_Sale_Date_Wise')->where('customer_id',$request->customer_id)->where('hotel_id',$request->hotel_id)
                                        ->where('room_id',$request->room_id)->where('status','Stop')->first();
            if(!empty($check_Stop_Sale_Exist)){
                return response()->json([
                    'status'            => 'error',
                    'message'           => 'Stop sale already exist for this room',
                ]);
            }else{
                DB::table('stop_Sale_Date_Wise')->insert([
                    'token'             => $request->token,
                    'customer_id'       => $request->customer_id,
                    'hotel_id'          => $request->hotel_id,
                    'room_id'           => $request->room_id,
                    'available_from'    => $request->available_from,
                    'available_to'      => $request->available_to,
                    'status'            => 'Stop',
                ]);
                
                DB::commit();
                return response()->json([
                    'status'            => 'success',
                    'message'           => 'Stop sale added successfully',
                ]);
            }
        } catch (Throwable $e) {
            // dd($e);
            DB::rollback();
            return response()->json(['message'=>'error','message' => 'Something Went Wrong']);
        }
    }
    
    public function room_Stop_Date_Wise_Edit(Request $request){
        DB::beginTransaction();
        try { 
            $check_Stop_Sale_Exist  = DB::table('stop_Sale_Date_Wise')->where('customer_id',$request->customer_id)->where('hotel_id',$request->hotel_id)->where('room_id',$request->room_id)->first();
            if(!empty($check_Stop_Sale_Exist)){
                DB::table('stop_Sale_Date_Wise')->where('customer_id',$request->customer_id)->where('hotel_id',$request->hotel_id)->where('room_id',$request->room_id)->update([
                    'available_from'    => $request->available_from,
                    'available_to'      => $request->available_to,
                ]);
                
                DB::commit();
                return response()->json([
                    'status'            => 'success',
                    'message'           => 'Stop sale updated successfully',
                ]);
            }else{
                return response()->json([
                    'status'            => 'error',
                    'message'           => 'Stop sale already exist for this room',
                ]);
            }
        } catch (Throwable $e) {
            // dd($e);
            DB::rollback();
            return response()->json(['message'=>'error','message' => 'Something Went Wrong']);
        }
    }
    
    public function room_Open_Date_Wise(Request $request){
        DB::beginTransaction();
        try {
            $check_Stop_Sale_Exist  = DB::table('stop_Sale_Date_Wise')->where('customer_id',$request->customer_id)->where('room_id',$request->room_Id)->first();
            if(!empty($check_Stop_Sale_Exist)) {
                DB::table('stop_Sale_Date_Wise')->where('customer_id',$request->customer_id)->update(['status' => NULL]);
                
                DB::commit();
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Room stop successfully',
                ]);
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Room Not Exist',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
}