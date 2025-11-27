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

class CreditLimitController extends Controller
{
    
    
    public function credit_limit(Request $request){
        $all_customer      = DB::table('customer_subcriptions')->orderBy('id','DESC')->get();
        $all_customer_credit= DB::table('credit_limits')->where('status_type','approved')->orderBy('id','DESC')->get();
        
   
        return view('template/frontend/userdashboard/pages/credit_limit/view_credit_limit',compact('all_customer','all_customer_credit'));
    }
    
     public function credit_limit_submit(Request $request){
         
         $customer_id=$request->customer_id;
         
         if(isset($request->transection_id))
         {
            $transection_id=$request->transection_id;  
         }
         else
         {
             $random=rand(10,100000);
          $transection_id=$random;     
         }
         
       
         $payment_method=$request->payment_method;
         $transection_remarks=$request->transection_remarks;
         $services_type=$request->services_type;
         
        $getData= DB::table('credit_limits')->where('customer_id',$customer_id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
        if(isset($getData))
        {
          return redirect()->back()->with('message','Please Edit This Customer');  
        }
        else
        {
          $data= DB::table('credit_limits')->insert([
               'transection_id'=>$transection_id,
            'payment_method'=>$payment_method,
             'pament_remarks'=>$transection_remarks,
            'customer_id'=>$request->customer_id,
            'amount'=>$request->amount,
            'total_amount'=>$request->total_amount,
            'remaining_amount'=>$request->amount,
            'currency'=>$request->currency,
            'status'=>'1',
             'status_type'=>'approved',
             'services_type'=>$services_type
            ]);
            
            $insert= DB::table('credit_limit_transections')->insert([
            'invoice_no'=>$request->customer_id,
            'customer_id'=>$request->customer_id,
            'transection_amount'=>$request->amount,
            'remaining_amount'=>$request->amount,
            'type'=>'payment_insert',
            'services_type'=>$services_type
          
            ]);  
        }
        //print_r($getData);die();
        
            
            return redirect()->back()->with('message','successful');
        
    }
    
    
    public function update(Request $request){
        
        $credit_id=$request->credit_id;
        $services_type=$request->services_type;
        if(isset($request->transection_id))
         {
            $transection_id=$request->transection_id;  
         }
         else
         {
             $random=rand(10,100000);
          $transection_id=$random;     
         }
         
       
         $payment_method=$request->payment_method;
         $transection_remarks=$request->transection_remarks;
         
        $getData= DB::table('credit_limits')->where('id',$credit_id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
       
       //print_r($request->customer_id);die();
            
            $total_amount=$request->amount + $getData->total_amount;
            $remaining_amount=$request->amount + $getData->remaining_amount;
            
            $credit_limits= DB::table('credit_limits')->insert([
            'transection_id'=>$transection_id,
            'payment_method'=>$payment_method,
             'pament_remarks'=>$transection_remarks,
            'customer_id'=>$request->customer_id,
            'amount'=>$request->amount,
            'total_amount'=>$total_amount,
            'remaining_amount'=>$remaining_amount,
            'currency'=>$request->currency,
            'status'=>'1',
            'status_type'=>'approved',
            'services_type'=>$services_type
            ]);
            
            $credit_limit_transections= DB::table('credit_limit_transections')->insert([
            'invoice_no'=>$transection_id,
            'customer_id'=>$request->customer_id,
            'transection_amount'=>$request->amount,
            'remaining_amount'=>$remaining_amount,
            'type'=>'payment_insert',
            'services_type'=>$services_type
          
            ]);
            
            return redirect()->back()->with('message','Updated successful');
        
    }
    
    public function customer_get_credit_data(Request $request)
    {
        $customer_id=$request->customer_id;
       
        $credit_data= DB::table('credit_limits')->where('customer_id',$customer_id)->where('status','1')->first();
       return response()->json(['credit_data'=>$credit_data]);
        
    }
    
    public function costomer_credit_ledger(Request $request,$id)
    {
        
        $customer      = DB::table('customer_subcriptions')->where('id',$id)->first();
        $credit_limits      = DB::table('credit_limits')->where('customer_id',$id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
        $all_booking = DB::table('credit_limit_transections')->where('customer_id',$id)->where('type','!=','unapproved')->get();
          // print_r($all_booking);die();
  
        return view('template/frontend/userdashboard/pages/credit_limit/view_ledger',compact('all_booking','customer','credit_limits')); 
    }
    
    
    
    public function costomer_credit_history(Request $request)
    {
        
      $all_customer_credit= DB::table('credit_limits')->orderBy('id','DESC')->get(); 
      return view('template/frontend/userdashboard/pages/credit_limit/credit_limit_history',compact('all_customer_credit'));
    }
    
    public function credit_limit_approved(Request $request)
    {
       $credit_id=$request->credit_id;
        $data_get= DB::table('credit_limits')->where('id',$credit_id)->first();
        $updated= DB::table('credit_limits')->where('id',$credit_id)->update([
                'status_type'=>'approved',
            ]);
            
            $insert= DB::table('credit_limit_transections')->where('invoice_no',$data_get->transection_id)->update([
            'type'=>'payment_insert',
          
            ]);
            return redirect()->back()->with('message','Updated successful');
    }
    
     public function credit_limit_declined(Request $request)
    {
       $credit_id=$request->credit_id;
        $data_get= DB::table('credit_limits')->where('id',$credit_id)->first();
        $updated= DB::table('credit_limits')->where('id',$credit_id)->update([
                'status_type'=>'declined',
            ]);
            
            $insert= DB::table('credit_limit_transections')->where('invoice_no',$data_get->transection_id)->update([
            'type'=>'declined',
          
            ]);
            return redirect()->back()->with('message','Updated successful');
    }
    
    
    
     
    






    
}
