<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\CustomerSubcription\CustomerSubcription;
use Illuminate\Support\Facades\Validator;
use App\Models\booking_customers;
use Carbon\Carbon;
use Hash;
use App\Models\booking_customer;

class LoginController extends Controller
{

/*
|--------------------------------------------------------------------------
| LoginController Function Started
|--------------------------------------------------------------------------
*/    
/*
|--------------------------------------------------------------------------
| Token Validated Initialize Started
|--------------------------------------------------------------------------
| In this variable, we coded the logic to get the response of Authenticated Token of Yours Apis in which token is required.
| if you are not given the token parameters body then Did Not get the api Response.
*/    
protected $valid_token='IoOCQ3ObCcqj3BmtYVUBKTaCIWWoFnQcCorlRUsL-peMCb6m7dlEhXnSJhXXEo7Dh8bG7WQq18wbzMIvAKNk2RtIVKSBc3uUgZASa-0DZ0L5oiwJ9rSktbNb1dM3efA-b7BLH97ryRSj8vglisLUecscxtA1OFPF7kYWWaqDSKxovS9yKw4jBhUWwMrYT306oG2UZgmDpxP-zx6hENsrnFrHXtOqO6e5SA6ZdJsbJmOXZxDq5ZOcLdZ6PgzeQVdnivhXQHA8g3gzQoNuhYo4E1UYNOdTYGS16EvMpOUTxfmhmLz1-hw9SPnIiIzOX9K83qEOptngC4ftezuMmw2cFusTrxrKMvbH8SUqKAiywnTuiyV4yunaolsqVwbR-4PyM6FO8usVBMFf49vNBSO0nh-cdb8imZPtqb4xGeGHHIu5mG7uMAKZaJVbXGpC2eZfjab3NGV9Z-fmSmrDdAmO44ew0Xf0ZIXu4UoJx8a7EfGQRwWl51g5ZF93J0HH';
/*
|--------------------------------------------------------------------------
| Token Validated Initialize Ended
|--------------------------------------------------------------------------
*/  
/*
|--------------------------------------------------------------------------
| Customer Login Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Customer Login.If Customer Login Details Are Correct Then Access the Customer Dashboard
| other wise does not access the Dashboard and return the login page.because i have the implemented Authenticated Middleware Used.
*/ 
function customer_login(Request $request){
    
       if(isset($_POST['token']))
        {
        
        $validated=array(
            'token' => 'required',
             'email' => 'required',
          
        );
        
        	$rules=Validator::make($request->all(),$validated);
    	  
    	if ($rules->fails())
    	 {
    	return response(['errors'=>$rules->errors()->all()], 422);
    	}
    	else
    	{
    	    $token= $_POST['token'];
    	    $email= $_POST['email'];
       
            $userData = CustomerSubcription::where('Auth_key',$token)->select('id','status')->first();
             
            if($userData){
              if($userData->status == 1){
                 // print_r($userData->id);
                  //print_r($request->email);
                  //die();
            //   echo "Customer id is $request->email ";
                    $customer_data = DB::table('booking_customers')->where('email',$email)->where('customer_id',$userData->id)->first();
                   
                     if($customer_data){
                         
                        $OTP = rand(100000, 999999);
                        $OTP_Hased = Hash::make($OTP);
                        
                        $result = DB::table('booking_customers')->where('id',$customer_data->id)->update([
                                'password'=>$OTP_Hased
                            ]);
                        
                        if($result){
                            return response()->json(['message'=>'success','otp'=>$OTP,'customer_data'=>$customer_data]);
                        }else{
                        return response()->json(['message'=>'failed','otp'=>'','customer_data'=>'']);
                            
                        }
                        
                    }else{
                        return response()->json(['message'=>'Email Not Found','otp'=>'','customer_data'=>'']);
                    }
                    // print_r($inv_details);
              }else{
                        return response()->json(['message'=>'failed','otp'=>'','customer_data'=>'']);
                    }
            }else{
                        return response()->json(['message'=>'failed','otp'=>'','customer_data'=>'']);
                    }
    	}
        }
        else
        {
        return response()->json(['message','Invalid Token']);  
        }
            
        
       //print_r($request->all());
       
    }
/*
|--------------------------------------------------------------------------
| Customer Login Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Customer OTP Verify Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Customer Send the OTP From Given Email and Varify The OTP.
*/ 
function customer_otp_verify(Request $request){
       
       if(isset($_POST['token']))
        {
        
        $validated=array(
            'token' => 'required',
             'email' => 'required',
             'booking_customer_id' => 'required',
             'otp' => 'required',
        );
        
        	$rules=Validator::make($request->all(),$validated);
    	  
    	if ($rules->fails())
    	 {
    	return response(['errors'=>$rules->errors()->all()], 422);
    	}
    	else
    	{
    	     $token= $_POST['token'];
    	    $booking_customer_id= $_POST['booking_customer_id'];
    	    $password= $_POST['otp'];
    	     $userData = CustomerSubcription::where('Auth_key',$token)->select('id','status')->first();
             
            if($userData){
              if($userData->status == 1){
                 // print_r($userData->id);
                  //print_r($request->email);
                  //die();
            //   echo "Customer id is $request->email ";
                    $customer_data = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                   
                     if($customer_data){
                        if(Hash::check($password, $customer_data->password)){
                            $customer_data = booking_customer::find($booking_customer_id);
                            $token = $customer_data->createToken('MyApp')->plainTextToken;
                             
                            return response()->json(['message'=>'success','data'=>$customer_data,'token'=>$token]);
                        }else{
                        return response()->json(['message'=>'failed3','data'=>'']);
                            
                        }
                        
                    }else{
                        return response()->json(['message'=>'failed2','data'=>'']);
                    }
                    // print_r($inv_details);
              }else{
                        return response()->json(['message'=>'failed1','data'=>'']);
                    }
            }else{
                        return response()->json(['message'=>'token not matched','data'=>'']);
                    }
    	}
        }
        else
        {
          return response()->json(['message','Invalid Token']);    
        }
    	
      
       
           
      }
/*
|--------------------------------------------------------------------------
| Customer OTP Verify Function Ended
|--------------------------------------------------------------------------
*/       
 /*
|--------------------------------------------------------------------------
| Customer OTP Verify Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Get The Customer Hotel Booking.
*/     
function customer_hotel_booking(Request $request){
          
          
          if(isset($_POST['token']))
        {
        
        $validated=array(
            'token' => 'required',
             'booking_customer_id' => 'required',
          
        );
        
        
        
        	$rules=Validator::make($request->all(),$validated);
    	  
    	if ($rules->fails())
    	 {
    	return response(['errors'=>$rules->errors()->all()], 422);
    	}
    	else
    	{
    	    $token= $_POST['token'];
    	    $booking_customer_id= $_POST['booking_customer_id'];
    	     $userData = CustomerSubcription::where('Auth_key',$token)->select('id','status')->first();
        if($userData){
          if($userData->status == 1){
      
            $booking_customer_id = (int)$booking_customer_id;
            $inv_hotels = DB::table('hotels_bookings')
                                                ->where('booking_customer_id',$booking_customer_id)
                                                ->where('customer_id',$userData->id)->get();
                if($inv_hotels){
                    
                    return response()->json(['message'=>'success','hotels_inv'=>$inv_hotels]);
                }else{
                    return response()->json(['message'=>'failed','hotels_inv'=>'']);
                }
                // print_r($inv_details);
          }
        }
    	}
        }
        else
        {
         return response()->json(['message','Invalid Token']);       
        }
    	
        //   $login_agent=auth()->guard('booking_customers')->user()->email;
          
          
       
       
    } 
  /*
|--------------------------------------------------------------------------
| Customer Hotel BOOKING Function Ended
|--------------------------------------------------------------------------

*/  

}
