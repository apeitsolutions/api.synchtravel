<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\country;
use Illuminate\Support\Facades\Hash;
use App\Models\CustomerSubcription\CustomerSubcription;
use Auth;
use Illuminate\Support\Str;
use DB;
use App\Models\ToursBooking;
use App\Models\Cart_details;
use App\Models\CustomerSubcription\RoleManager;
use App\Models\client_users;

class CreditLimitApiController extends Controller
{
    
    
    public function credit_limit_list(Request $request){
        $customer_id=$request->customer_id;
        $customer      = DB::table('customer_subcriptions')->where('id',$customer_id)->first();
        $customer_credit= DB::table('credit_limits')->where('customer_id',$customer_id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
        return response()->json(['customer'=>$customer,'customer_credit'=>$customer_credit]);
    }
    
     public function credit_limit_submit(Request $request){
         
         $customer_id=$request->customer_id;
         $transection_id=$request->transection_id;
         $payment_method=$request->payment_method;
         $services_type=$request->services_type;
         
         
         
        $getData= DB::table('credit_limits')->where('customer_id',$customer_id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
        if(isset($getData))
        {
        //   $updated= DB::table('credit_limits')->where('id',$getData->id)->update([
        //     'status'=>'0',
        //     ]);
            
            $total_amount=$request->amount + $getData->total_amount;
            $remaining_amount=$request->amount + $getData->remaining_amount;
            
            $credit_limits= DB::table('credit_limits')->insert([
            'transection_id'=>$transection_id,
            'payment_method'=>$request->payment_method,
             'pament_remarks'=>$request->transection_remarks,
            'customer_id'=>$request->customer_id,
            'amount'=>$request->amount,
            'total_amount'=>$total_amount,
            'remaining_amount'=>$remaining_amount,
            'currency'=>$request->currency,
            'status'=>'1',
            'status_type'=>'unapproved',
            'services_type'=>$services_type,
            ]);
            
            $credit_limit_transections= DB::table('credit_limit_transections')->insert([
            'invoice_no'=>$transection_id,
            'customer_id'=>$request->customer_id,
            'transection_amount'=>$request->amount,
            'remaining_amount'=>$remaining_amount,
            'type'=>'unapproved',
            'services_type'=>$services_type,
          
            ]);
            
            
        }
        else
        {
             $amount=$request->amount;
            
            
          $credit_limits= DB::table('credit_limits')->insert([
              'transection_id'=>$transection_id,
            'payment_method'=>$payment_method,
            'pament_remarks'=>$request->transection_remarks,
            'customer_id'=>$request->customer_id,
            'amount'=>$amount,
            'total_amount'=>$amount,
            'remaining_amount'=>$amount,
            'currency'=>$request->currency,
            'status'=>'1',
            'status_type'=>'unapproved',
            'services_type'=>$services_type,
            ]);
            
            $credit_limit_transections= DB::table('credit_limit_transections')->insert([
            'invoice_no'=>$transection_id,
            'customer_id'=>$request->customer_id,
            'transection_amount'=>$request->amount,
            'remaining_amount'=>$request->amount,
            'type'=>'unapproved',
            'services_type'=>$services_type,
          
            ]);  
        }
       
       return response()->json(['credit_limits'=>$credit_limits,'credit_limit_transections'=>$credit_limit_transections]); 
        
        
    }
    
    
  
    
    public function customer_get_credit_data(Request $request)
    {
        $token=$request->token;
        $customer= DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
        $credit_data= DB::table('credit_limits')->orderBy('id','DESC')->where('customer_id',$customer->id)->where('status_type','approved')->limit(1)->first();
       return response()->json(['credit_data'=>$credit_data]);
        
    }
    
   
   
    
    
    
     public function costomer_credit_history(Request $request)
    {
        
        $customer_id=$request->customer_id;
      $customer_credit= DB::table('credit_limits')->where('customer_id',$customer_id)->orderBy('id','DESC')->get(); 
      return response()->json(['customer_credit'=>$customer_credit]);
    }
    
    
     public function costomer_credit_ledger(Request $request)
    {
        $customer_id=$request->customer_id;
        $customer      = DB::table('customer_subcriptions')->where('id',$customer_id)->first();
        $credit_limits      = DB::table('credit_limits')->where('customer_id',$customer_id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
        $all_booking = DB::table('credit_limit_transections')->where('customer_id',$customer_id)->where('type','!=','unapproved')->get();
        
        $getArraybooking=array();
        if(isset($all_booking))
        {
        foreach($all_booking as $booking)
        {
            if($booking->type == 'booked')
            {
                $hotels_booking= DB::table('hotels_bookings')->where('invoice_no',$booking->invoice_no)->first();
                $getArraybooking[]=(object)[
               
               'id'=>$booking->id,
               'invoice_no'=>$booking->invoice_no,
               'customer_id'=>$booking->customer_id,
               'transection_amount'=>$booking->transection_amount,
               'remaining_amount'=>$booking->remaining_amount,
               'type'=>$booking->type,
               'created_at'=>$booking->created_at,
               'creationDate'=>$hotels_booking->creationDate,
               'status'=>$hotels_booking->status,
               'total_adults'=>$hotels_booking->total_adults,
               'total_childs'=>$hotels_booking->total_childs,
               'total_rooms'=>$hotels_booking->total_rooms,
               'reservation_response'=>$hotels_booking->reservation_response,
               ];
            }
            else
            {
                $getArraybooking[]=(object)[
               
               'id'=>$booking->id,
               'invoice_no'=>$booking->invoice_no,
               'customer_id'=>$booking->customer_id,
               'transection_amount'=>$booking->transection_amount,
               'remaining_amount'=>$booking->remaining_amount,
               'type'=>$booking->type,
               'created_at'=>$booking->created_at,
               
               ];
            }
            
        }
        }
        return response()->json(['all_booking'=>$getArraybooking,'customer'=>$customer,'credit_limits'=>$credit_limits]);
        
       
    }
     
    

 public function costomer_credit_ledger_flight(Request $request)
    {
        $customer_id=$request->customer_id;
        $customer      = DB::table('customer_subcriptions')->where('id',$customer_id)->first();
        $credit_limits      = DB::table('credit_limits')->where('customer_id',$customer_id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
        $all_booking = DB::table('credit_limit_transections')->where('customer_id',$customer_id)->where('type','!=','unapproved')->get();
        
        $getArraybooking=array();
        if(isset($all_booking))
        {
        foreach($all_booking as $booking)
        {
            if($booking->type == 'booked')
            {
                $flight_booking= DB::table('flight_bookings')->where('invoice_no',$booking->invoice_no)->first();
                $getArraybooking[]=(object)[
               
               'id'=>$booking->id,
               'invoice_no'=>$booking->invoice_no,
               'customer_id'=>$booking->customer_id,
               'transection_amount'=>$booking->transection_amount,
               'remaining_amount'=>$booking->remaining_amount,
               'type'=>$booking->type,
               'created_at'=>$booking->created_at,
               'creationDate'=>$flight_booking->created_at ?? '',
               'status'=>$flight_booking->booking_status ?? '',
               'total_adults'=>$flight_booking->adults ?? '',
               'total_childs'=>$flight_booking->childs ?? '',
               'infant'=>$flight_booking->infant ?? '',
               'reservation_response'=>$flight_booking->booking_detail_rs ?? '',
               ];
            }
            else
            {
                $getArraybooking[]=(object)[
               
               'id'=>$booking->id,
               'invoice_no'=>$booking->invoice_no,
               'customer_id'=>$booking->customer_id,
               'transection_amount'=>$booking->transection_amount,
               'remaining_amount'=>$booking->remaining_amount,
               'type'=>$booking->type,
               'created_at'=>$booking->created_at,
               
               ];
            }
            
        }
        }
        return response()->json(['all_booking'=>$getArraybooking,'customer'=>$customer,'credit_limits'=>$credit_limits]);
        
       
    }
     




    
}
