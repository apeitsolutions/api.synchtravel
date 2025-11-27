<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Session;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
Use Illuminate\Support\Facades\Input as input;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\country;
use App\Models\b2b_Agents_detail;
use App\Models\roomPromotion;
use App\Models\room_Promotions_details;

class PromotionController extends Controller
{
    public function create_Promotions(Request $request){
        DB::beginTransaction(); 
        try {
            $all_countries  = country::all();
            $all_Hotels     = DB::table('hotels')->where('owner_id',$request->customer_id)->orderBy('id', 'DESC')->get();
            $all_Rooms      = DB::table('rooms')->where('owner_id',$request->customer_id)->orderBy('id', 'DESC')->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','all_Hotels'=>$all_Hotels,'all_Rooms'=>$all_Rooms,'all_countries'=>$all_countries]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function view_Promotions(Request $request){
        DB::beginTransaction(); 
        try {
            $all_countries          = country::all();
            $all_Room_Promotions    = DB::table('room_promotions')->where('customer_id',$request->customer_id)->orderBy('id', 'DESC')->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','all_Room_Promotions'=>$all_Room_Promotions,'all_countries'=>$all_countries]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function add_Promotions(Request $request){
        DB::beginTransaction();
        try {
            
            if($request->promotion_Date_From != NULL){
                $promotion_Date_From    = $request->promotion_Date_From;
                $promotion_Date_To      = $request->promotion_Date_To;
                
                $check_Room_promotions = DB::table('room_promotions')->where('token',$request->token)
                                            ->where('room_Id',$request->room_Id)->where('hotel_Id',$request->hotel_Id)
                                            ->where('availible_from',$request->availible_from)->where('availible_to',$request->availible_to)
                                            ->where(function($query) use ($promotion_Date_From, $promotion_Date_To) {
                                            $query->whereBetween('promotion_Date_From', [$promotion_Date_From, $promotion_Date_To])
                                                ->orWhereBetween('promotion_Date_To', [$promotion_Date_From, $promotion_Date_To])
                                                ->orWhere(function($query) use ($promotion_Date_From, $promotion_Date_To) {
                                                $query->where('promotion_Date_From', '<=', $promotion_Date_From)
                                                    ->where('promotion_Date_To', '>=', $promotion_Date_To);
                                                });
                                            })->exists();
                // dd($check_Room_promotions);
            }else{
                $promotion_Date_From_WD    = $request->promotion_Date_From_WD;
                $promotion_Date_To_WD      = $request->promotion_Date_To_WD;
                $promotion_Date_From_WE    = $request->promotion_Date_From_WE;
                $promotion_Date_To_WE      = $request->promotion_Date_To_WE;
                
                $check_Room_promotions = DB::table('room_promotions')->where('token',$request->token)
                                            ->where('room_Id',$request->room_Id)->where('hotel_Id',$request->hotel_Id)
                                            ->where('availible_from',$request->availible_from)->where('availible_to',$request->availible_to)
                                            ->where(function($query) use ($promotion_Date_From_WD, $promotion_Date_To_WD, $promotion_Date_From_WE, $promotion_Date_To_WE) {
                                            $query->whereBetween('promotion_Date_From_WD', [$promotion_Date_From_WD, $promotion_Date_To_WD])
                                                ->whereBetween('promotion_Date_From_WE', [$promotion_Date_From_WE, $promotion_Date_To_WE])
                                                ->orWhereBetween('promotion_Date_To_WD', [$promotion_Date_From_WD, $promotion_Date_To_WD])
                                                ->orWhereBetween('promotion_Date_To_WE', [$promotion_Date_From_WE, $promotion_Date_To_WE])
                                                ->orWhere(function($query) use ($promotion_Date_From_WD, $promotion_Date_To_WD, $promotion_Date_From_WE, $promotion_Date_To_WE) {
                                                $query->where('promotion_Date_From_WD', '<=', $promotion_Date_From_WD)
                                                    ->where('promotion_Date_From_WE', '<=', $promotion_Date_From_WE)
                                                    ->where('promotion_Date_To_WD', '>=', $promotion_Date_To_WD)
                                                    ->where('promotion_Date_To_WE', '>=', $promotion_Date_To_WE);
                                                });
                                            })->exists();
            }
            
            if($check_Room_promotions == true){
                return response()->json(['status'=>'exist','message'=>'Promotions Already Exist']); 
            }else{
                $room_promotions                = DB::table('room_promotions')->insert([
                    'token'                     => $request->token,
                    'customer_id'               => $request->customer_id,
                    'hotel_Id'                  => $request->hotel_Id,
                    'hotel_Name'                => $request->hotel_Name,
                    'room_Id'                   => $request->room_Id,
                    'room_Type'                 => $request->room_Type,
                    'availible_from'            => $request->availible_from,
                    'availible_to'              => $request->availible_to,
                    'total_Rate'                => $request->total_Rate,
                    'promotion_Date_From'       => $request->promotion_Date_From,
                    'promotion_Date_To'         => $request->promotion_Date_To,
                    'promotion_Rate'            => $request->promotion_Rate,
                    'total_Rate_WD'             => $request->total_Rate_WD,
                    'promotion_Date_From_WD'    => $request->promotion_Date_From_WD,
                    'promotion_Date_To_WD'      => $request->promotion_Date_To_WD,
                    'promotion_Rate_WD'         => $request->promotion_Rate_WD,
                    'total_Rate_WE'             => $request->total_Rate_WE,
                    'promotion_Date_From_WE'    => $request->promotion_Date_From_WE,
                    'promotion_Date_To_WE'      => $request->promotion_Date_To_WE,
                    'promotion_Rate_WE'         => $request->promotion_Rate_WE,
                    'promotion_Image'           => $request->promotion_Image,
                ]);
                DB::commit();
                return response()->json(['status'=>'success','message'=>'Promotions Added Succesfully']); 
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function edit_Promotion(Request $request){
        DB::beginTransaction(); 
        try {
            $all_Hotels     = DB::table('hotels')->where('owner_id',$request->customer_id)->orderBy('id', 'DESC')->get();
            $all_Rooms      = DB::table('rooms')->where('owner_id',$request->customer_id)->orderBy('id', 'DESC')->get();
            $single_Promotion = DB::table('room_promotions')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','single_Promotion'=>$single_Promotion,'all_Hotels'=>$all_Hotels,'all_Rooms'=>$all_Rooms]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function update_Promotions(Request $request){
        DB::beginTransaction();
        try {
            // if($request->promotion_Date_From != NULL){
            //     $promotion_Date_From    = $request->promotion_Date_From;
            //     $promotion_Date_To      = $request->promotion_Date_To;
                
            //     $check_Room_promotions = DB::table('room_promotions')->where('token',$request->token)
            //                                 ->where('room_Id',$request->room_Id)->where('hotel_Id',$request->hotel_Id)
            //                                 ->where('availible_from',$request->availible_from)->where('availible_to',$request->availible_to)
            //                                 ->where(function($query) use ($promotion_Date_From, $promotion_Date_To) {
            //                                 $query->whereBetween('promotion_Date_From', [$promotion_Date_From, $promotion_Date_To])
            //                                     ->orWhereBetween('promotion_Date_To', [$promotion_Date_From, $promotion_Date_To])
            //                                     ->orWhere(function($query) use ($promotion_Date_From, $promotion_Date_To) {
            //                                     $query->where('promotion_Date_From', '<=', $promotion_Date_From)
            //                                         ->where('promotion_Date_To', '>=', $promotion_Date_To);
            //                                     });
            //                                 })->exists();
            //     // dd($check_Room_promotions);
            // }else{
            //     $promotion_Date_From_WD    = $request->promotion_Date_From_WD;
            //     $promotion_Date_To_WD      = $request->promotion_Date_To_WD;
            //     $promotion_Date_From_WE    = $request->promotion_Date_From_WE;
            //     $promotion_Date_To_WE      = $request->promotion_Date_To_WE;
                
            //     $check_Room_promotions = DB::table('room_promotions')->where('token',$request->token)
            //                                 ->where('room_Id',$request->room_Id)->where('hotel_Id',$request->hotel_Id)
            //                                 ->where('availible_from',$request->availible_from)->where('availible_to',$request->availible_to)
            //                                 ->where(function($query) use ($promotion_Date_From_WD, $promotion_Date_To_WD, $promotion_Date_From_WE, $promotion_Date_To_WE) {
            //                                 $query->whereBetween('promotion_Date_From_WD', [$promotion_Date_From_WD, $promotion_Date_To_WD])
            //                                     ->whereBetween('promotion_Date_From_WE', [$promotion_Date_From_WE, $promotion_Date_To_WE])
            //                                     ->orWhereBetween('promotion_Date_To_WD', [$promotion_Date_From_WD, $promotion_Date_To_WD])
            //                                     ->orWhereBetween('promotion_Date_To_WE', [$promotion_Date_From_WE, $promotion_Date_To_WE])
            //                                     ->orWhere(function($query) use ($promotion_Date_From_WD, $promotion_Date_To_WD, $promotion_Date_From_WE, $promotion_Date_To_WE) {
            //                                     $query->where('promotion_Date_From_WD', '<=', $promotion_Date_From_WD)
            //                                         ->where('promotion_Date_From_WE', '<=', $promotion_Date_From_WE)
            //                                         ->where('promotion_Date_To_WD', '>=', $promotion_Date_To_WD)
            //                                         ->where('promotion_Date_To_WE', '>=', $promotion_Date_To_WE);
            //                                     });
            //                                 })->exists();
            // }
            
            
            if(isset($request->promotion_Id) && $request->promotion_Id != null && $request->promotion_Id != ''){
                // dd($request->promotion_Id);
                
                DB::table('room_promotions')->where('id',$request->promotion_Id)->update([
                    'hotel_Id'                  => $request->hotel_Id,
                    'hotel_Name'                => $request->hotel_Name,
                    'room_Id'                   => $request->room_Id,
                    'room_Type'                 => $request->room_Type,
                    'availible_from'            => $request->availible_from,
                    'availible_to'              => $request->availible_to,
                    'total_Rate'                => $request->total_Rate,
                    'promotion_Date_From'       => $request->promotion_Date_From,
                    'promotion_Date_To'         => $request->promotion_Date_To,
                    'promotion_Rate'            => $request->promotion_Rate,
                    'total_Rate_WD'             => $request->total_Rate_WD,
                    'promotion_Date_From_WD'    => $request->promotion_Date_From_WD,
                    'promotion_Date_To_WD'      => $request->promotion_Date_To_WD,
                    'promotion_Rate_WD'         => $request->promotion_Rate_WD,
                    'total_Rate_WE'             => $request->total_Rate_WE,
                    'promotion_Date_From_WE'    => $request->promotion_Date_From_WE,
                    'promotion_Date_To_WE'      => $request->promotion_Date_To_WE,
                    'promotion_Rate_WE'         => $request->promotion_Rate_WE,
                    'promotion_Image'           => $request->promotion_Image,
                ]);
                DB::commit();
                return response()->json(['status'=>'success','message'=>'Promotions Updated Succesfully']); 
            }else{
                return response()->json(['status'=>'error','message'=>'Hotel and Room Cannnot be Changed']); 
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function delete_Promotion(Request $request){
        DB::beginTransaction(); 
        try {
            DB::table('room_promotions')->where('customer_id',$request->customer_id)->where('id',$request->id)->delete();
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Data Deleted Successfully!',]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
}