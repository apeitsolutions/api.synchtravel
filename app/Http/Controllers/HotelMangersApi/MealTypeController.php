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

class MealTypeController extends Controller
{
    public function custom_Meal_Type_Create(Request $request){
        DB::beginTransaction();          
        try {
            $custom_Meal_Types          = DB::table('custom_Meal_Types')->where('customer_id',$request->customer_id)->get();
            if($custom_Meal_Types){
                return response()->json([
                    'status'            => 'success',
                    'custom_Meal_Types' => $custom_Meal_Types,
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function custom_Meal_Type_Add(Request $request){
        DB::beginTransaction();          
        try {
            $custom_Meal_Types          = DB::table('custom_Meal_Types')->where('customer_id',$request->customer_id)
                                            ->whereRaw('LOWER(meal_Type) = ?', [strtolower($request->meal_Type)])->first();
            if($custom_Meal_Types != null){
                DB::commit();
                return response()->json(['status'=>'Exist']);
            }
            
            $result                     = DB::table('custom_Meal_Types')->insert([
                'token'                 => $request->token,
                'SU_id'                 => $request->SU_id ?? NULL,
                'customer_id'           => $request->customer_id,
                'meal_Type'             => $request->meal_Type,
            ]);
            if($result){
                
                DB::commit();
                return response()->json(['status'=>'success']);
            }else{
                DB::commit();
                return response()->json(['status'=>'error']);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function custom_Meal_Type_Update(Request $request){
        DB::beginTransaction();          
        try {
            $custom_Meal_Types      = DB::table('custom_Meal_Types')->where('customer_id',$request->customer_id)->where('id','!=',$request->id)
                                        ->whereRaw('LOWER(meal_Type) = ?', [strtolower($request->meal_Type)])->first();
            if($custom_Meal_Types != null){
                DB::commit();
                return response()->json(['status'=>'Exist']);
            }
            
            $result                 = DB::table('custom_Meal_Types')->where('customer_id',$request->customer_id)->where('id',$request->id)->update(['meal_Type' => $request->meal_Type]);
            if($result){
                DB::commit();
                return response()->json(['status'=>'success']);
            }else{
                DB::commit();
                return response()->json(['status'=>'error']);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function custom_Meal_Type_Delete(Request $request){
        DB::beginTransaction();          
        try {
            $custom_Meal_Types = DB::table('custom_Meal_Types')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            if($custom_Meal_Types != null){
                DB::table('custom_Meal_Types')->where('customer_id',$request->customer_id)->where('id',$request->id)->delete();
                DB::commit();
                return response()->json(['status'=>'success']);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
}