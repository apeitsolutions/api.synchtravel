<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\Tour;
use App\Models\country;
use App\Models\transfer_booking;
use App\Models\view_booking_payment_recieve;
use App\Models\view_booking_payment_recieve_Activity;
use Auth;
use DB;

class TransferzController extends Controller
{
    public function transfer_serach(Request $request){
        $transfer_Details = DB::table('tranfer_destination')
                            ->where('pickup_City','LIKE','%'.''.$request->pickup_City.'%')
                            ->where('dropof_City','LIKE','%'.''.$request->dropof_City.'%')
                            ->get();
                            
        if($transfer_Details){
            return response()->json(['message'=>'Success','transfer_Details'=>$transfer_Details]);    
        }else{
            return response()->json(['message'=>'Failed']);  
        }
        
    }
    
    public function SaveTransferBooking(Request $request){
        
        if($request->vehicle_ID){
            $vehicle_details = DB::table('tranfer_vehicle')->where('id',$request->vehicle_ID)->first();
            $result = new transfer_booking();
            $result->vehicle_ID     = $request->vehicle_ID;
            $result->pickup_City    = $request->pickup_City;
            $result->dropof_City    = $request->dropof_City;
            $result->pickup_Date    = $request->pickup_Date;
            $result->dropof_Date    = $request->dropof_Date;
            $result->vehicle_Price  = $request->vehicle_Price;
            $result->save();
            $booking_ID = $result->id;
            return response()->json(['message'=>'Success','vehicle_details'=>$vehicle_details,'booking_ID'=>$booking_ID]);
        }
        
        if($request->booking_ID){
            $result     = DB::table('transfer_booking')->where('id',$request->booking_ID)->update(['total_Passengers' => $request->total_Passengers]);
            $data       = DB::table('transfer_booking')->where('id',$request->booking_ID)->first();
            $countries  = DB::table('countries')->get();
            return response()->json(['message'=>'Success','data'=>$data,'countries'=>$countries]);
        }
    }
    
    public function add_lead_passengar_transfer(Request $request){
        $booking_ID             = $request->booking_ID;
        $lead_passenger_details = $request->lead_passenger_details;
        $add_lead_passengar = DB::table('transfer_booking')->where('id',$booking_ID)->update(
        [
            'lead_passenger_details'=>$lead_passenger_details,
        ]);
        
        return response()->json(['lead_passenger_details'=>$lead_passenger_details]);
        
    }
    
    public function add_other_passengar_transfer(Request $request){
        $booking_ID                 = $request->booking_ID;
        $other_passenger_details    = $request->other_passenger_details;
        $other_data = DB::table('transfer_booking')->where('id',$booking_ID)->update(
        [
            'other_passenger_details'=>$other_passenger_details,
        ]);
        return response()->json(['other_passenger_details'=>$other_passenger_details]);
    }
    
    public function confrimTransferbooking(Request $request){
        $generate_id    = rand(0,9999999);
        $result         = DB::table('transfer_booking')->where('id',$request->booking_ID)->update(['booking_Status'=>1,'voucher_No'=>$generate_id]);
        $data           = DB::table('transfer_booking')->where('id',$request->booking_ID)->first();
        return response()->json(['message'=>'Success','data'=>$data]);
    }
    
    public function transfer_voucher(Request $request){
        $data = DB::table('transfer_booking')->where('voucher_No',$request->voucher_No)->first();
        return response()->json(['message'=>'Success','data'=>$data]);
    }
    
    public function transfer_cancellation(Request $request){
        $result     = DB::table('transfer_booking')->where('id',$request->booking_ID)->update(['booking_Status' => 0]);
        $data       = DB::table('transfer_booking')->where('id',$request->booking_ID)->first();
        return response()->json(['message'=>'Success','data'=>$data,'result'=>$result]);
    }
}