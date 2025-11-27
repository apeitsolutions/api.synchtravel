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

class FlightController extends Controller
{
    
function flight_payment_history(Request $request){
    
        
        $flight_payment_details = DB::table('flight_payment_details')->orderBy('id','DESC')->get();
        
        
        return view('template/frontend/userdashboard/pages/flight/flight_payment_history',compact('flight_payment_details'));
        
        
        
    }
    
  function flight_payment_approved(Request $request){
    
    
    
    DB::beginTransaction();
                  
                     try {
                         $payment_id=$request->payment_id;
       $get_payment_data = DB::table('flight_payment_details')->where('id',$payment_id)->first();
        //print_r($get_payment_data);die();
       $get_data = DB::table('flight_customer_ledgers')->where('token',$get_payment_data->auth_token)->latest()->first();
      
       $get_balance=str_replace(',', '', $get_data->balance_amount) - $get_payment_data->payment_received_amount;
       //print_r($get_balance);die();
       
       
       $hotel_customer_ledgers = DB::table('flight_customer_ledgers')->insert([
            'token'=>$get_payment_data->auth_token,
            'invoice_no'=>$get_payment_data->payment_transction_id,
            'payment_amount'=>$get_payment_data->payment_received_amount,
            'balance_amount'=>$get_balance,
            'type'=>'payment',
            'status'=>1
            ]);
       
    //   print_r($get_payment_data);die();
        $hotel_payment_details = DB::table('flight_payment_details')->where('id',$payment_id)->update([
            'payment_status'=>1,
            ]);
                          
                           DB::commit();
                                return redirect()->back()->with('message','Payment Approved Successfully');
                        } catch (Throwable $e) {

                            DB::rollback();
                            return redirect()->back()->with('message','Payment Approved Successfully');
                        }
    
       
            
            
            

       
        
        
        
    }
    
  function flight_payment_declined(Request $request){
    
        $payment_id=$request->payment_id;
       $hotel_payment_details = DB::table('flight_payment_details')->where('id',$payment_id)->update([
            'payment_status'=>2,
            ]);

        return redirect()->back()->with('message','Payment Declined');
        
        
        
    }
    
    
    public function flight_booking(Request $request)
    {
         
    // $result = DB::table('flight_bookings')->select('id','invoice_no','adults','childs','infant','departure_date','booking_detail_rs')->orderBy('id','DESC')->get();
     $result= DB::table('flight_bookings')
     ->Join('airline_markups', 'flight_bookings.invoice_no', '=', 'airline_markups.invoice_no') ->select('airline_markups.*','flight_bookings.adults','flight_bookings.childs','flight_bookings.infant','flight_bookings.departure_date','flight_bookings.adult_price','flight_bookings.child_price'
     ,'flight_bookings.infant_price','flight_bookings.total_price','flight_bookings.adult_price_markup','flight_bookings.child_price_markup','flight_bookings.infant_price_markup'
     ,'flight_bookings.total_price_markup','flight_bookings.client_commission_amount','flight_bookings.admin_commission_amount','flight_bookings.client_payable_price','flight_bookings.currency','flight_bookings.booking_detail_rs')->where('flight_bookings.booking_status','Confirmed')->orderBy('id','DESC')->get();
 
  // print_r($result);die();
        return view('template/frontend/userdashboard/pages/flight/booking_list',compact('result'));
        
        
   }
   
   
    public function client_list(Request $request)
    {
 
       $client=DB::table('customer_subcriptions')->orderBy('id','DESC')->get();
       //print_r($client);die();
        return view('template/frontend/userdashboard/pages/hotel_booking/client_list',compact('client'));
        
        
   }
    
    
    
     
    






    
}
