<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\Tour;
use App\Models\country;
use App\Models\view_booking_payment_recieve;
use App\Models\view_booking_payment_recieve_Activity;
use App\Models\CustomerSubcription\CustomerSubcription;
use Auth;
use DB;
use App\Jobs\ApiJob;

class AdminHotelPaymentController extends Controller
{
    
/*
|--------------------------------------------------------------------------
| AdminHotelPaymentController Function Started
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Payment View Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Payment List.
*/
function payment_view(Request $request){
    
        $auth_token=$request->token;
        $customer = DB::table('customer_subcriptions')->where('Auth_key',$auth_token)->first();
        $data = DB::table('hotel_payment_details')->orderBy('id','DESC')->where('token',$auth_token)->limit(1)->first();
        $total_booking = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->count();
        $bookings = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->select('exchange_price')->get();
        
        $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$auth_token)->limit(1)->first();
        $paid_amount = DB::table('hotel_customer_ledgers')->sum('payment_amount');
        
        $manage_customer_markups = DB::table('manage_customer_markups')->where('token',$auth_token)->get();
        
        return response()->json(['manage_customer_markups'=>$manage_customer_markups,'data'=>$data,'customer'=>$customer,'total_booking'=>$total_booking,'bookings'=>$bookings,'hotel_customer_ledgers'=>$hotel_customer_ledgers,'paid_amount'=>$paid_amount]);
        
        
    }
/*
|--------------------------------------------------------------------------
| Payment View Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Submit Payment Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Insert Payment Details.
*/
function submit_payment(Request $request){
       
       
        $token=$request->token;
        $payment_date=$request->payment_date;
        $payment_method=$request->payment_method;
        $payment_transction_id=$request->payment_transction_id;
        $payment_total_amount=$request->payment_total_amount;
        $payment_received_amount=$request->payment_received_amount;
        $payment_remaining_amount=$request->payment_remaining_amount;
        $payment_paid_amount=$request->payment_paid_amount;
        $payment_remarks=$request->payment_remarks;
        
        
        
        
        
        
        $payment_id = DB::table('hotel_payment_details')->insertGetId([
            'token'=>$token,
            'payment_date'=>$payment_date,
            'payment_method'=>$payment_method,
            'payment_transction_id'=>$payment_transction_id,
            'payment_total_amount'=>$payment_total_amount,
            'payment_received_amount'=>$payment_received_amount,
            'payment_remaining_amount'=>$payment_remaining_amount,
            'payment_paid_amount'=>$payment_paid_amount,
            'payment_remarks'=>$payment_remarks,
            ]);
            
            
            //print_r($payment_id);die();
            
            $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$token)->limit(1)->first();
        $balance_amount=str_replace(',', '', $get_hotel_customer_ledgers->balance_amount) - $payment_received_amount;
                //print_r($remain_price);die();
        
        
         $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
            'token'=>$token,
            'invoice_no'=>$payment_id,
            'payment_amount'=>$payment_received_amount,
            'balance_amount'=>$balance_amount,
            'type'=>'payment'
            ]);
            
            
            
            
        return response()->json(['payment_id'=>$payment_id,'hotel_customer_ledgers'=>$hotel_customer_ledgers]);
        
    }
/*
|--------------------------------------------------------------------------
| Submit Payment Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Customer Hotel Ledger Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Customer Ledger List.
*/
function customer_hotel_ledger(Request $request){
    
        $auth_token=$request->token;
        $customer = DB::table('customer_subcriptions')->where('Auth_key',$auth_token)->first();
        $hotel_payment = DB::table('hotel_payment_details')->where('token',$auth_token)->get();
        $hotels_booking = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->get();
        
        $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->where('token',$auth_token)->get();
        
        
   
        
        
        
        $all_data = DB::table('hotel_customer_ledgers')
        ->leftjoin('hotel_provider_bookings', 'hotel_customer_ledgers.invoice_no', '=', 'hotel_provider_bookings.invoice_no')
        ->leftjoin('hotel_payment_details', 'hotel_customer_ledgers.invoice_no', '=', 'hotel_payment_details.id')
        ->where('hotel_customer_ledgers.type','=', 'payment')->orwhere('hotel_customer_ledgers.type','=', 'hotel_booking')
        ->select('hotel_customer_ledgers.*','hotel_payment_details.*', 'hotel_provider_bookings.check_in', 'hotel_provider_bookings.check_out', 'hotel_provider_bookings.rooms'
        , 'hotel_provider_bookings.adults', 'hotel_provider_bookings.childs', 'hotel_provider_bookings.checkavailability_rs', 'hotel_provider_bookings.checkavailability_again_rs','hotel_provider_bookings.booking_rs','hotel_provider_bookings.lead_passenger_details','hotel_provider_bookings.created_at')
        
        
        ->orderBy('hotel_customer_ledgers.id','ASC')->get();
        
        
        
       return response()->json(['all_data'=>$all_data,'hotels_booking'=>$hotels_booking,'customer'=>$customer,'hotel_payment'=>$hotel_payment,'hotel_customer_ledgers'=>$hotel_customer_ledgers]);
        
    }
/*
|--------------------------------------------------------------------------
| Customer Hotel Ledger Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Hotel Payment History Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Hotel Payment History List.
*/
function hotel_payment_history(Request $request){
    
        $auth_token=$request->token;
        $customer = DB::table('customer_subcriptions')->where('Auth_key',$auth_token)->first();
        $hotel_payment_details = DB::table('hotel_payment_details')->orderBy('id','DESC')->get();
        $total_booking = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->count();
        $bookings = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->select('exchange_price')->get();
        
        $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$auth_token)->limit(1)->first();
        $paid_amount = DB::table('hotel_customer_ledgers')->sum('payment_amount');
        
        $manage_customer_markups = DB::table('manage_customer_markups')->where('token',$auth_token)->get();
        
        return view('template/frontend/userdashboard/pages/admin_hotel_payment/payment_history',compact('hotel_payment_details'));
        
        
        
    }
/*
|--------------------------------------------------------------------------
| Hotel Payment History Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Hotel Payment Approved Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Hotel Payment Approved List.
*/
function hotel_payment_approved(Request $request){
    
    
    
    DB::beginTransaction();
                  
                     try {
                         $payment_id=$request->payment_id;
       $get_payment_data = DB::table('hotel_payment_details')->where('id',$payment_id)->first();
       
       $get_data = DB::table('hotel_customer_ledgers')->where('token',$get_payment_data->token)->latest()->first();
       
       $get_balance=str_replace(',', '', $get_data->balance_amount) - $get_payment_data->payment_received_amount;
       //print_r($get_balance);die();
       
       
       $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
            'token'=>$get_payment_data->token,
            'invoice_no'=>$payment_id,
            'payment_amount'=>$get_payment_data->payment_received_amount,
            'balance_amount'=>$get_balance,
            'type'=>'payment',
            'status'=>1
            ]);
       
    //   print_r($get_payment_data);die();
        $hotel_payment_details = DB::table('hotel_payment_details')->where('id',$payment_id)->update([
            'payment_status'=>1,
            ]);
                          
                           DB::commit();
                                return redirect()->back()->with('message','Payment Approved Successfully');
                        } catch (Throwable $e) {

                            DB::rollback();
                            return redirect()->back()->with('message','Payment Approved Successfully');
                        }
    
       
            
            
            

       
        
        
        
    }
/*
|--------------------------------------------------------------------------
| Hotel Payment Approved Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Hotel Payment Declined Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Hotel Payment Declined List.
*/
function hotel_payment_declined(Request $request){
    
        $payment_id=$request->payment_id;
       $hotel_payment_details = DB::table('hotel_payment_details')->where('id',$payment_id)->update([
            'payment_status'=>2,
            ]);

        return redirect()->back()->with('message','Payment Declined');
        
        
        
    }
/*
|--------------------------------------------------------------------------
| Hotel Payment Declined Function Ended
|--------------------------------------------------------------------------
*/ 
/*
|--------------------------------------------------------------------------
| Hotel List Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Hotel List for Our Client.
*/
public function hotel_list_Old(Request $request){
 
        $all_booking_data = DB::table('hotel_provider_bookings')
        ->leftJoin('manage_customer_markups', 'hotel_provider_bookings.invoice_no', '=', 'manage_customer_markups.invoice_no')
         ->where('hotel_provider_bookings.provider','!=', 'NULL')
        ->select('hotel_provider_bookings.booked','hotel_provider_bookings.invoice_no','hotel_provider_bookings.check_in'
        ,'hotel_provider_bookings.check_out'
        ,'hotel_provider_bookings.rooms'
        ,'hotel_provider_bookings.adults'
         ,'hotel_provider_bookings.childs'
         ,'hotel_provider_bookings.lead_passenger_details'
         ,'hotel_provider_bookings.other_passenger_details'
         ,'hotel_provider_bookings.child_details'
         ,'hotel_provider_bookings.provider'
         ,'hotel_provider_bookings.checkavailability_rs'
         ,'hotel_provider_bookings.checkavailability_again_rs'
           ,'hotel_provider_bookings.booking_rs'
            ,'hotel_provider_bookings.booking_detail_rs'
            ,'hotel_provider_bookings.booking_status'
            ,'hotel_provider_bookings.exchange_price'
            ,'hotel_provider_bookings.auth_token'
            ,'hotel_provider_bookings.checkavailability_rq'
            
        ,'manage_customer_markups.*')->orderBy('hotel_provider_bookings.id','ASC')->get();
        
    //print_r($all_booking_data);die();
       
        return view('template/frontend/userdashboard/pages/hotel_booking/all_provider_booking',compact('all_booking_data'));
        
        
   }


public function hotel_list(Request $request){
    $userData                       = CustomerSubcription::select('id','status')->get();
    $all_booking_data               = DB::table('hotels_bookings')
                                        ->leftJoin('manage_customer_markups', 'hotels_bookings.invoice_no', '=', 'manage_customer_markups.invoice_no')
                                        ->select('hotels_bookings.*','hotels_bookings.status as hotel_booking_status','manage_customer_markups.payable_price','manage_customer_markups.client_commission_amount','manage_customer_markups.total_markup_price','manage_customer_markups.currency'
                                            ,'manage_customer_markups.exchange_payable_price','manage_customer_markups.exchange_client_commission_amount','manage_customer_markups.exchange_total_markup_price','manage_customer_markups.exchange_currency'
                                            ,'manage_customer_markups.exchange_rate','hotels_bookings.customer_id'
                                        )->orderBy('hotels_bookings.id','ASC')->get();
    $get_data_customer_subcriptions = DB::table('customer_subcriptions')->get();
    $hotel_payment_details          = DB::table('hotel_payment_details')->get();
    return view('template/frontend/userdashboard/pages/hotel_booking/all_provider_booking',compact('all_booking_data','get_data_customer_subcriptions','hotel_payment_details'));
}

/*
|--------------------------------------------------------------------------
| Hotel List Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Client List Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Client List for Our Client.
*/
public function client_list(Request $request){
 
       $client=DB::table('customer_subcriptions')->orderBy('id','DESC')->get();
       //print_r($client);die();
        return view('template/frontend/userdashboard/pages/hotel_booking/client_list',compact('client'));
        
        
   }
/*
|--------------------------------------------------------------------------
| Client List Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Customer Hotel Ledger Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Customer Hotel Ledger for Our Client.
*/
function customerHotelLedger(Request $request,$id){
    
        
        $customer = DB::table('customer_subcriptions')->where('id',$id)->first();
        $token=$customer->Auth_key;
        //print_r($token);die();
        // $hotel_payment = DB::table('hotel_payment_details')->where('token',$auth_token)->get();
        // $hotels_booking = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->get();
        
        // $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->where('token',$auth_token)->get();
        
        
   
        
        
        
        $all_data = DB::table('hotel_customer_ledgers')
        ->leftjoin('hotel_provider_bookings', 'hotel_customer_ledgers.invoice_no', '=', 'hotel_provider_bookings.invoice_no')
        ->leftjoin('hotel_payment_details', 'hotel_customer_ledgers.invoice_no', '=', 'hotel_payment_details.id')
        ->where('hotel_customer_ledgers.token',$token)
        ->select('hotel_customer_ledgers.*','hotel_payment_details.*', 'hotel_provider_bookings.check_in', 'hotel_provider_bookings.check_out', 'hotel_provider_bookings.rooms'
        , 'hotel_provider_bookings.adults', 'hotel_provider_bookings.childs', 'hotel_provider_bookings.checkavailability_rs', 'hotel_provider_bookings.checkavailability_again_rs','hotel_provider_bookings.booking_rs','hotel_provider_bookings.lead_passenger_details','hotel_provider_bookings.created_at','hotel_provider_bookings.provider')
        
        
        ->orderBy('hotel_customer_ledgers.id','ASC')->get();
        
        
         return view('template/frontend/userdashboard/pages/hotel_booking/customer_ledger',compact('all_data','customer'));
       
        
    }
/*
|--------------------------------------------------------------------------
| Customer Hotel Ledger Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Customer Hotel Payment History Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Customer payment history for Our Client.
*/
function customerHotelPaymentHistory(Request $request,$id){
        $customer = DB::table('customer_subcriptions')->where('id',$id)->first();
        $hotel_payment_details = DB::table('hotel_payment_details')->where('token',$customer->Auth_key)->orderBy('id','DESC')->get();
        
        return view('template/frontend/userdashboard/pages/hotel_booking/payment_history',compact('hotel_payment_details','customer'));   
    }
/*
|--------------------------------------------------------------------------
| Customer Hotel Payment History Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Provider Supplier Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Provider Supplier list for Our Client.
*/
function providers_suppliers(Request $request){
        
      
        $hotelbeds= DB::table('manage_customer_markups')
        ->leftjoin('hotel_provider_bookings', 'manage_customer_markups.invoice_no', '=', 'hotel_provider_bookings.invoice_no')->where('hotel_provider_bookings.provider','hotelbeds')->get();
        //print_r(count($hotelbeds));die();

         return view('template/frontend/userdashboard/pages/hotel_booking/view_provider_supplier',compact('hotelbeds')); 
    }
/*
|--------------------------------------------------------------------------
| Provider Supplier Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Provider Pyments Submit Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Insert The Provider details from database for Our Client.
*/
function providers_payments_submit(Request $request){
        
      
        $hotelbeds= DB::table('admin_provider_payments')->insert([
            'provider'=>$request->provider,
            'payment_date'=>$request->payment_date,
            'payment_method'=>$request->payment_method,
            'payment_received_amount'=>$request->payment_received_amount,
            'payment_transction_id'=>$request->payment_transction_id,
            'payment_total_amount'=>$request->payment_total_amount,
            'payment_remaining_amount'=>$request->payment_remaining_amount,
            'payment_paid_amount'=>$request->payment_paid_amount,
            'payment_remarks'=>$request->payment_remarks,
            'payment_status'=>1,
            ]);
        
        
        return redirect()->back()->with('message','Payment Submit Successful');
    }
/*
|--------------------------------------------------------------------------
| Provider Pyments Submit Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| AdminHotelPaymentController Function Ended
|--------------------------------------------------------------------------
*/    
   
}
