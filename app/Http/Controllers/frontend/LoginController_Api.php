<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\User;
use DB;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\booking_customer;
use App\Http\Controllers\Mail3rdPartyController;

class LoginController_Api extends Controller
{


	public function login(){



		return view('template/frontend/userdashboard/pages/login/login');

	}
	
	function customer_login(Request $request){
       //print_r($request->all());
       
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
             
            if($userData){
              if($userData->status == 1){
                 // print_r($userData->id);
                  //print_r($request->email);
                  //die();
            //   echo "Customer id is $request->email ";
                    $customer_data = DB::table('booking_customers')->where('email',$request->email)->where('customer_id',$userData->id)->first();
                   
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
    
    function customer_otp_verify(Request $request){
      //print_r($request->all());
       
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
             
            if($userData){
              if($userData->status == 1){
                 // print_r($userData->id);
                  //print_r($request->email);
                  //die();
            //   echo "Customer id is $request->email ";
                    $customer_data = DB::table('booking_customers')->where('id',$request->booking_customer_id)->first();
                   
                     if($customer_data){
                        if(Hash::check($request->password, $customer_data->password)){
                            $customer_data = booking_customer::find($request->booking_customer_id);
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
      
    //   function customer_otp_verify(Request $request){
    //   //print_r($request->all());
       
    //         $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
             
    //         if($userData){
    //           if($userData->status == 1){
    //              // print_r($userData->id);
    //               //print_r($request->email);
    //               //die();
    //         //   echo "Customer id is $request->email ";
    //                 // $customer_data = DB::table('booking_customers')->where('id',$request->booking_customer_id)->first();
                   
    //                 //  if($customer_data){
    //                     if(Hash::check($request->password, $customer_data->password)){
    //                         $customer_data = booking_customer::find($request->booking_customer_id);
    //                         $token = $customer_data->createToken('MyApp')->accessToken;
                             
    //                         return response()->json(['message'=>'success','data'=>$customer_data,'token'=>$token]);
    //                     }else{
    //                     return response()->json(['message'=>'failed3','data'=>'']);
                            
    //                     }
                        
    //                 // }else{
    //                 //     return response()->json(['message'=>'failed2','data'=>'']);
    //                 // }
    //                 // print_r($inv_details);
    //           }else{
    //                     return response()->json(['message'=>'failed1','data'=>'']);
    //                 }
    //         }else{
    //                     return response()->json(['message'=>'token not matched','data'=>'']);
    //                 }
    //   }
      
    function customer_hotel_booking(Request $request){
          
        //   $login_agent=auth()->guard('booking_customers')->user()->email;
          
          print_r($login_agent);
          die;
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
          if($userData->status == 1){
      
                                $booking_customer_id = (int)$request->booking_customer_id;
                              
                              
                              $inv_hotels = DB::table('hotel_provider_bookings')
                                                ->where('booking_customer_id',$booking_customer_id)
                                                ->where('auth_token',$request->token)->get();
                if($inv_hotels){
                    
                    return response()->json(['message'=>'success','hotels_inv'=>$inv_hotels]);
                }else{
                    return response()->json(['message'=>'failed','hotels_inv'=>'']);
                }
                // print_r($inv_details);
          }
        }
       
    }
    
	public function signin(Request $request)
	{
    	//print_r($request->email);
		$this->validate($request,
			[
				'email'=>'required',
				'password'=>'required',

			]);

        $credentials = $request->only('email', 'password');


        $login=Auth::guard('web')->attempt($credentials);

		if($login)
		{

            return redirect()->intended('super_admin');

		}

		else
		{
			return redirect()->back()->with('message','Email or password is not correct!!');
		}

	}

	public function change_password(Request $request)
	{
	    
	     $customer_login=$request->id;
	    $customer_login1=DB::table('customer_subcriptions')->where('id',$customer_login)->first();
	    if (Hash::check($request->input('current_password'), $customer_login1->password) == false)
	   
			{
			
				return response()->json(['message'=>'invalid current password']);
			}
	    
	    if($request->input('new_password') != $request->input('cnew_password'))
		{
			return response()->json(['message'=>'confirm password password does not match!!']);
		}
		else
		{
		    $customer = CustomerSubcription::find($customer_login);
			$customer->password = Hash::make($request->input('new_password'));
 			$customer->update();
	            return response()->json(['message'=>'password updated successfully']);
		}
		
	}
	
	public static function change_Password_mail($request,$b2b_Agent_Details){
        $mail_Send_Status       = false;
        
        // Alsubaee Hotels
        if($request->token == config('token_Alif')){
            $from_Address           = config('mail_From_Address_Alif');
            $website_Title          = config('mail_Title_Alif');
            $mail_Template_Key      = config('mail_Template_Key_Alif');
            $website_Url            = config('website_Url_Alif');
            // $mail_Address_Register  = 'ua7583232@gmail.com';
            $mail_Send_Status       = true;
        }
        
        // Umrah Shop
        if($request->token == config('token_UmrahShop')){
            $from_Address           = config('mail_From_Address_UmrahShop');
            $website_Title          = config('mail_Title_UmrahShop');
            $mail_Template_Key      = config('mail_Template_Key_UmrahShop');
            $website_Url            = config('website_Url_UmrahShop');
            // $mail_Address_Register  = 'ua7583232@gmail.com';
            $mail_Send_Status       = true;
        }
        
        // dd($request->token);
        
        if($mail_Send_Status != false){
            $b2b_Agents                 = DB::table('b2b_agents')->where('token',$request->token)->where('id',$b2b_Agent_Details->id)->first();
            $lead_title                 = $b2b_Agents->title ?? '';
            // $lead_email                 = $b2b_Agents->email ?? '';
            $lead_email                 = 'ua758323@gmail.com';
            $lead_first_name            = $b2b_Agents->first_name ?? '';
            $lead_last_name             = $b2b_Agents->last_name ?? '';
            $lead_phone                 = $b2b_Agents->phone_no ?? '';
            $details                    = [
                'lead_title'            => $lead_title,
                'lead_Name'             => $lead_first_name,
                'email'                 => $lead_email,
                'contact'               => $lead_phone,
            ];
            // dd($details);
            $to_Address                 = $lead_email;
            $reciever_Name              = $lead_first_name;
            
            if($request->token == config('token_Alif') || $request->token == config('token_UmrahShop')){
                $email_Message          = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> Your Password has been Changed! Thank you for using our service. Please login with below details. <br><br> <ul> <li>Username: '.$details['email'].' </li> <li>Password: '.$request->new_password.' </li> </ul> <br><br> Do you have any questions or require further assistance, please do not hesitate to contact us. <br> <br> Regards, <br> '.$website_Title.' </div>';
                // return $email_Message;
                $mail_Check             = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                // return $mail_Check;
            }
            
            if($mail_Check == 'Success'){
                return 'Success';
            }else{
                return 'Error';
            }
        }else{
            return 'Error';
        }
    }
	
	public function agent_change_password(Request $request){
        // return $request;
        // $b2b_Agent_Details  = DB::table('b2b_agents')->where('id',$request->id)->first();
        // $mail_Send          = LoginController_Api::change_Password_mail($request,$b2b_Agent_Details);
        // return $mail_Send;
        
        $agent_login    = $request->id;
        $agent_login1   = DB::table('b2b_agents')->where('id',$agent_login)->first();
        
        if (Hash::check($request->input('current_password'), $agent_login1->password) == false){
            return response()->json(['status'=>'error','message'=>'invalid current password']);
        }
        else if($request->input('new_password') != $request->input('cnew_password')){
            return response()->json(['status'=>'error','message'=>'confirm password password does not match!!']);
        }
        else{
            DB::table('b2b_agents')->where('id',$agent_login)->update(['password'=>Hash::make($request->input('new_password'))]);
            
            $b2b_Agent_Details  = DB::table('b2b_agents')->where('id',$agent_login)->first();
            $mail_Send          = LoginController_Api::change_Password_mail($request,$b2b_Agent_Details);
            // return $mail_Send;
            
            return response()->json(['status'=>'success','message'=>'password updated successfully']);
            // return $mail_Send;
        }
	}


	public function logout()
	{
		Auth::guard('web')->logout();
		return redirect('login1');


	}
	public function signup(){
		return view('template/frontend/pages/signup');

	}


}
