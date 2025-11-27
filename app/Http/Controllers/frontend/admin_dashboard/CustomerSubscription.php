<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\country;
use Illuminate\Support\Facades\Hash;
use App\Models\CustomerSubcription\CustomerSubcription;
use Auth;
use Illuminate\Support\Str;
use App\Models\ToursBooking;
use App\Models\Cart_details;
use App\Models\CustomerSubcription\RoleManager;
use App\Models\client_users;
use App\Models\companies;
use App\Models\staffDetail;
use App\Http\Controllers\B2BAgentController;
use DB;
use Carbon\Carbon;

class CustomerSubscription extends Controller
{
    public function view_customer_subcription(Request $request){
        $all_Users      = DB::table('customer_subcriptions')->get();
        $all_countries  = DB::table('countries')->get();
        return view('template/frontend/userdashboard/pages/customer_subcription/view_customer_subcription',compact(['all_Users','all_countries']));
    }
    
    public function subcirbed_customer_ledger($id){
        $user_data          = DB::table('customer_subcriptions')->where('id',$id)->select('id','company_name')->first();
        $customer_ledger    = DB::table('customer_subcription_ledger')->where('customer_id',$id)->get();
        return view('template/frontend/userdashboard/pages/customer_subcription/subcirbed_customer_ledger',compact(['user_data','customer_ledger']));
    }
    
      public function fetch_all_countries(Request $request)
     {
            $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            if($userData->status == 1){
                $countries = DB::table('countries')->get();
                return response()->json(['message'=>'success','data'=>$countries]);
                

            }
        }
        
     }
     
     // Old Login Function
//   function customer_login_sub(Request $request){
//   //print_r($request->all());
   
//         $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
         
//         if($userData){
//           if($userData->status == 1){
//              // print_r($userData->id);
//               //print_r($request->email);
//               //die();
//         //   echo "Customer id is $request->email ";
//                 $customer_data = client_users::where('email',$request->email)->where('customer_id',$userData->id)->first();
               
//                  if($customer_data){
//                     if(Hash::check($request->password, $customer_data->password)){
//                         return response()->json(['message'=>'success','data'=>$customer_data]);
//                     }else{
//                     return response()->json(['message'=>'failed','data'=>'']);
                        
//                     }
                    
//                 }else{
//                     return response()->json(['message'=>'failed','data'=>'']);
//                 }
//                 // print_r($inv_details);
//           }else{
//                     return response()->json(['message'=>'failed','data'=>'']);
//                 }
//         }else{
//                     return response()->json(['message'=>'failed','data'=>'']);
//                 }
//   }
  
//   function customer_login_sub(Request $request){
//   //print_r($request->all());
   
//         $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
         
//         if($userData){
//           if($userData->status == 1){
//              // print_r($userData->id);
//               //print_r($request->email);
//               //die();
//         //   echo "Customer id is $request->email ";
//                 $customer_data = DB::table('booking_customers')->where('email',$request->email)->where('customer_id',$userData->id)->first();
               
//                  if($customer_data){
//                     if(Hash::check($request->password, $customer_data->password)){
//                         return response()->json(['message'=>'success','data'=>$customer_data]);
//                     }else{
//                     return response()->json(['message'=>'failed','data'=>'']);
                        
//                     }
                    
//                 }else{
//                     return response()->json(['message'=>'failed','data'=>'']);
//                 }
//                 // print_r($inv_details);
//           }else{
//                     return response()->json(['message'=>'failed','data'=>'']);
//                 }
//         }else{
//                     return response()->json(['message'=>'failed','data'=>'']);
//                 }
//   }

  function customer_login_sub_otp(Request $request){
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
                        return response()->json(['message'=>'success','data'=>$customer_data]);
                    }else{
                    return response()->json(['message'=>'failed','data'=>'']);
                        
                    }
                    
                }else{
                    return response()->json(['message'=>'failed','data'=>'']);
                }
                // print_r($inv_details);
          }else{
                    return response()->json(['message'=>'failed','data'=>'']);
                }
        }else{
                    return response()->json(['message'=>'failed','data'=>'']);
                }
  }

    function customer_login_sub(Request $request){
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
                        return response()->json(['message'=>'failed','otp'=>'','customer_data'=>'']);
                    }
                    // print_r($inv_details);
              }else{
                        return response()->json(['message'=>'failed','otp'=>'','customer_data'=>'']);
                    }
            }else{
                        return response()->json(['message'=>'failed','otp'=>'','customer_data'=>'']);
                    }
    }
  
  
    function cleint_users_save(Request $request){
    //   print_r($request->all());
      $request_data = json_decode($request->request_data);
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
          if($userData->status == 1){
                
                $result = DB::table('client_users')
                            ->where('email',$request_data->email)
                            ->where('customer_id',$userData->id)
                            ->first();
                if(!$result){
                    $client_users = new client_users;
                    $client_users->name = $request_data->name;
                    $client_users->lname = $request_data->lname;
                    $client_users->email = $request_data->email;
                    $client_users->password = bcrypt($request_data->password);
                    $client_users->gender = $request_data->gender;
                    $client_users->country = $request_data->country;
                    $client_users->phone = $request_data->phone;
                    $client_users->passport = $request_data->passport_no;
                    $client_users->nationlaity = $request_data->Nationality;
                    $client_users->address = $request_data->address;
                    $client_users->customer_id = $userData->id;
                    
                    $result = $client_users->save();
                    
                    if($result){
                        return response()->json(['status'=>'success']);
                    }else{
                        return response()->json(['status'=>'failed']);
                    }
                }else{
                    return response()->json(['status'=>'','message'=>'This email is already in use Try another']);
                }
                
                // print_r($inv_details);
          }
        }
  }

  function view_customer_booking(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
          if($userData->status == 1){
                // $inv_details = ToursBooking::join('cart_details', 'cart_details.booking_id', '=', 'tours_bookings.id')
                //                 ->where('tours_bookings.email',$request->email)
                //                 ->where('tours_bookings.parent_token',$request->token)
                //                 ->get(['tours_bookings.*','cart_details.*']);
                                
                            //   $inv_details = ToursBooking->cartDetails();
                                $booking_customer_id = (int)$request->booking_customer_id;
                                $inv_details = DB::table('cart_details')
                                ->join('tours_bookings', 'cart_details.booking_id', '=', 'tours_bookings.id')
                                 ->whereJsonContains('cart_details.cart_total_data->customer_id', $booking_customer_id)
                                ->where('tours_bookings.parent_token',$request->token)
                              ->get(['cart_details.*','cart_details.id as cart_id','tours_bookings.*']);
                              
                              
                if($inv_details){
                    
                    return response()->json(['message'=>'success','tour_inv'=>$inv_details]);
                }else{
                    return response()->json(['message'=>'failed','inv_details'=>'']);
                }
                // print_r($inv_details);
          }
        }
       
    }
    
    function customer_hotel_bookings(Request $request){
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
    
    function customer_transfers_bookings(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
          if($userData->status == 1){
                // $inv_details = ToursBooking::join('cart_details', 'cart_details.booking_id', '=', 'tours_bookings.id')
                //                 ->where('tours_bookings.email',$request->email)
                //                 ->where('tours_bookings.parent_token',$request->token)
                //                 ->get(['tours_bookings.*','cart_details.*']);
                                
                            //   $inv_details = ToursBooking->cartDetails();
                                $booking_customer_id = (int)$request->booking_customer_id;

                              
                              $inv_transfers = DB::table('transfer_bookings')
                                                ->where('booking_customer_id',$booking_customer_id)
                                                ->where('auth_key',$request->token)->get();
                if($inv_transfers){
                    
                    return response()->json(['message'=>'success','transfer_inv'=>$inv_transfers]);
                }else{
                    return response()->json(['message'=>'failed','inv_details'=>'']);
                }
                // print_r($inv_details);
            }
        }
    }
    
    public static function get_Invoice_Season_Working($all_data,$request,$userData){
        $today_Date                 = date('Y-m-d');
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details     = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details         = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            
            
            $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                $start_Date     = Carbon::parse($start_Date);
                $end_Date       = Carbon::parse($end_Date);
                
                // If services exists and is valid JSON
                $services = [];
            
                if (isset($record->services)) {
                    $decoded    = json_decode($record->services, true);
                    $services   = is_array($decoded) ? $decoded : [];
                }
                
                // If 'accomodation_tab' is present in services
                if (is_array($services) && in_array('accomodation_tab', $services)) {
                    if (!isset($record->start_date) || empty($record->start_date)) {
                        return false;
                    }
                    
                    $created_at = Carbon::parse($record->start_date);
                } else {
                    // Fallback to created_at
                    if (!isset($record->created_at) || empty($record->created_at)) {
                        return false;
                    }
                    
                    $created_at = Carbon::parse($record->created_at);
                }
                
                // Now check if the date falls within range
                return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                // return $created_at->between($start_Date, $end_Date);
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public static function get_Hotel_Booking_Season_Working($all_data,$request,$userData){
        $today_Date                 = date('Y-m-d');
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details     = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details         = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        // print_r($season_Details);die;
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            
            $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if(!empty($record->provider) && $record->provider == 'Custome_hotel'){
                    $reservation_request = json_decode($record->reservation_request);
                    if(!empty($reservation_request)){
                        $hotel_checkout_select =  json_decode($reservation_request->hotel_checkout_select);
                        if(!empty($hotel_checkout_select)){
                            $start_Date     = Carbon::parse($start_Date);
                            $end_Date       = Carbon::parse($end_Date);
                        
                            if (isset($hotel_checkout_select->checkIn)) {
                                if (!isset($hotel_checkout_select->checkIn) || empty($hotel_checkout_select->checkIn)) {
                                    return false;
                                }
                                
                                $created_at = Carbon::parse($hotel_checkout_select->checkIn);
                            } else {
                                // Fallback to created_at
                                if (!isset($record->created_at) || empty($record->created_at)) {
                                    return false;
                                }
                                
                                $created_at = Carbon::parse($record->created_at);
                            }
                            return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                        }
                    }
                }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public static function get_Transfer_Season_Working($all_data,$request,$userData){
        $today_Date                 = date('Y-m-d');
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details     = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details         = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            
            $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if(!empty($record)){
                    $transfer_data =  json_decode($record->transfer_data);
                    if(!empty($transfer_data)){
                        $start_Date     = Carbon::parse($start_Date);
                        $end_Date       = Carbon::parse($end_Date);
                    
                        if (isset($transfer_data->pickup_date)) {
                            if (!isset($transfer_data->pickup_date) || empty($transfer_data->pickup_date)) {
                                return false;
                            }
                            
                            $created_at = Carbon::parse($transfer_data->pickup_date);
                        } else {
                            // Fallback to created_at
                            if (!isset($record->created_at) || empty($record->created_at)) {
                                return false;
                            }
                            
                            $created_at = Carbon::parse($record->created_at);
                        }
                        return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                    }
                }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    function view_customer_hotel_booking(Request $request){
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            if($userData->status == 1){
                $inv_details                = DB::table('hotels_bookings')->where('b2b_agent_id', $request->b2b_agent_id)->orderBy('id', 'desc')->get();
                $b2b_agent_id               = $request->b2b_agent_id;
                $tentative_Hotel_Bookings   = DB::table('hotels_bookings')
                                                ->where(function ($query) use ($b2b_agent_id) {
                                                    $query->where('b2b_agent_id', $b2b_agent_id)
                                                    ->where('status', 'Tentative');
                                                })
                                                ->orderBy('id', 'desc')
                                                ->get();
                $hotels_bookings            = DB::table('hotels_bookings')->where('b2b_agent_id', $request->b2b_agent_id)->orderBy('id', 'desc')->get();
                $transfer_Bookings          = DB::table('transfers_new_booking')->where('b2b_agent_id',$request->b2b_agent_id)->orderBy('id', 'desc')->get();
                $invoice_Bookings           = DB::table('add_manage_invoices')->where('b2b_agent_id',$request->b2b_agent_id)->orderBy('id', 'desc')->get();
                
                if($userData->id == 54){
                    if(empty($inv_details)){
                    }else{
                        $inv_details                = $this->get_Hotel_Booking_Season_Working($inv_details,$request,$userData);
                    }
                    
                    if(empty($tentative_Hotel_Bookings)){
                    }else{
                        $tentative_Hotel_Bookings   = $this->get_Hotel_Booking_Season_Working($tentative_Hotel_Bookings,$request,$userData);
                    }
                    
                    if(empty($hotels_bookings)){
                    }else{
                        $hotels_bookings            = $this->get_Hotel_Booking_Season_Working($hotels_bookings,$request,$userData);
                    }
                    
                    if(empty($transfer_Bookings)){
                    }else{
                        $transfer_Bookings          = $this->get_Transfer_Season_Working($transfer_Bookings,$request,$userData);
                    }
                    
                    if(empty($invoice_Bookings)){
                    }else{
                        $invoice_Bookings           = $this->get_Invoice_Season_Working($invoice_Bookings,$request,$userData);
                    }
                }
                
                if($inv_details){
                    return response()->json([
                        'message'                   => 'success',
                        'inv_details'               => $inv_details,
                        'tentative_Hotel_Bookings'  => $tentative_Hotel_Bookings,
                        'hotels_bookings'           => $hotels_bookings,
                        'transfer_Bookings'         => $transfer_Bookings,
                        'invoice_Bookings'          => $invoice_Bookings,
                    ]);
                }else{
                    return response()->json(['message'=>'failed','inv_details'=>'']);
                }
            }
        }
    }
    
    function view_customer_booking_combine(Request $request){
        // print_r($request->all());
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
          if($userData->status == 1){
                // $inv_details = ToursBooking::join('cart_details', 'cart_details.booking_id', '=', 'tours_bookings.id')
                //                 ->where('tours_bookings.email',$request->email)
                //                 ->where('tours_bookings.parent_token',$request->token)
                //                 ->get(['tours_bookings.*','cart_details.*']);
                                
                            //   $inv_details = ToursBooking->cartDetails();
                                
                                $inv_details = DB::table('combine_Booking')
                                ->where('email',$request->email)
                                ->where('user_id',$userData->id)
                                ->get();
                if($inv_details){
                    
                    return response()->json(['message'=>'success','inv_details'=>$inv_details]);
                }else{
                    return response()->json(['message'=>'failed','inv_details'=>'']);
                }
                // print_r($inv_details);
          }
        }
       
    }
    
    // Staff Details
    public function staffDetails(Request $request){
        DB::beginTransaction();
        try {
            $allCountries   = country::all();
            $userData       = CustomerSubcription::where('id',$request->customer_id)->first();
            if($userData){
                $staffData  = staffDetail::where('customer_id',$request->customer_id)->get();
            }
            return response()->json(['allCountries'=>$allCountries,'userData'=>$userData,'staffData'=>$staffData]);
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
    
    public function addStaffDetails(Request $request){
        DB::beginTransaction();
        try {
            $userData                   = CustomerSubcription::find($request->customer_id);
            if($userData){
                $data                   = new staffDetail();
                $data->token            = $request->token ?? NULL;
                $data->customer_id      = $request->customer_id ?? NULL;
                $data->staffName        = $request->staffName ?? NULL;
                $data->staffContact     = $request->staffContact ?? NULL;
                $data->staffCountry     = $request->staffCountry ?? NULL;
                $data->staffCity        = $request->staffCity ?? NULL;
                $data->save();
                
                DB::commit();
                return response()->json(['message'=>'success']);
            }
            else{
                return response()->json(['message'=>'error']); 
            }
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
    
    public function updateStaffDetails(Request $request){
        DB::beginTransaction();
        try {
            $userData                   = CustomerSubcription::find($request->customer_id);
            if($userData){
                $data                   = staffDetail::findorFail($request->id);
                $data->staffName        = $request->staffName ?? NULL;
                $data->staffContact     = $request->staffContact ?? NULL;
                $data->staffCountry     = $request->staffCountry ?? NULL;
                $data->staffCity        = $request->staffCity ?? NULL;
                $data->update();
                
                DB::commit();
                return response()->json(['message'=>'success']);
            }
            else{
                return response()->json(['message'=>'error']); 
            }
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
    
    public function deleteStaffDetails(Request $request){
        DB::beginTransaction();
        try {
            $userData                   = CustomerSubcription::find($request->customer_id);
            if($userData){
                $data                   = staffDetail::findorFail($request->id);
                $data->delete();
                
                DB::commit();
                return response()->json(['message'=>'success']);
            }
            else{
                return response()->json(['message'=>'error']); 
            }
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
    // Staff Details
    
    function account_details(Request $request){
         $customer_id=$request->customer_id;
         $data = CustomerSubcription::where('id',$customer_id)->first();
         $all_countries_currency = country::all();
         return response()->json(['data'=>$data,'all_countries_currency'=>$all_countries_currency]);
     }

    function edit_account_details(Request $request){
        $id     = $request->id;
        $data   = CustomerSubcription::find($id);
        if($data){
            $data->phone            = $request->phone;
            $data->currency_symbol  = $request->currency_symbol;
            $data->currency_value   = $request->currency_value;
            // Account Information
            $data->accountTitle     = $request->accountTitle ?? NULL;
            $data->branchName       = $request->branchName ?? NULL;
            $data->account_No       = $request->account_No;
            $data->accountIBAN      = $request->accountIBAN ?? NULL;
            
            $data->update();
            return response()->json(['message'=>'Success']);
        }
        else{
            return response()->json(['message'=>'Error']); 
        }  
    }
    
    function contact_details(Request $request){
         $customer_id=$request->customer_id;
         $data = DB::table('contact_details')->where('customer_id',$customer_id)->first();
         return response()->json(['data'=>$data]);
    }
    
     public function get_meta_tags(Request $request)
     {
         $customer_id=$request->customer_id;
         $data = DB::table('meta_tags')->where('customer_id',$customer_id)->get();
         return response()->json(['data'=>$data]);
     }
     
    public function get_url_meta_tag_info(Request $request){
        $customer_id    = $request->customer_id;
        $data           = DB::table('pages_meta_info')->where('page_url',$request->pageUrl)->first();
        return response()->json(['message'=>'success','data'=>$data]);
    }
     
     
     
      public function save_meta_tags(Request $request)
     {
        //  print_r($request->all());
        //  $customer_id=$request->customer_id;
         $data = DB::table('meta_tags')->insert([
             'title'=>$request->meta_tag,
             'customer_id'=>$request->customer_id,
             ]);
             if($data){
                  return response()->json(['status'=>'success']);
             }else{
                  return response()->json(['status'=>'faild']);
             }
        //  return response()->json(['data'=>$data]);
     }
     
       public function save_pages_meta_info(Request $request)
     {
        //  print_r($request->all());
        //  $customer_id=$request->customer_id;
        $request_data = json_decode($request->request_data);
         $data = DB::table('pages_meta_info')->insert([
             'page_url'=>$request_data->page_url,
             'focus_keyword'=>$request_data->focus_keyword,
             'pagename'=>$request_data->pagename,
             'pageTitle'=>$request_data->pageTitle,
             'metaTitle'=>$request_data->metaTitle,
             'meta_description'=>$request_data->meta_description,
             'meta_tags'=>json_encode($request_data->meta_tags),
             'customer_id'=>$request->customer_id,
             ]);
             if($data){
                  return response()->json(['status'=>'success']);
             }else{
                  return response()->json(['status'=>'faild']);
             }
        //  return response()->json(['data'=>$data]);
     }
     
            public function update_pages_meta_info(Request $request)
     {
        //  print_r($request->all());
        //  $customer_id=$request->customer_id;
        $request_data = json_decode($request->request_data);
         $data = DB::table('pages_meta_info')->where('id',$request_data->page_id)->update([
             'page_url'=>$request_data->page_url,
             'focus_keyword'=>$request_data->focus_keyword,
             'pagename'=>$request_data->pagename,
             'pageTitle'=>$request_data->pageTitle,
             'metaTitle'=>$request_data->metaTitle,
             'meta_description'=>$request_data->meta_description,
             'meta_tags'=>json_encode($request_data->meta_tags),
             'customer_id'=>$request->customer_id,
             ]);
             if($data){
                  return response()->json(['status'=>'success']);
             }else{
                  return response()->json(['status'=>'faild']);
             }
        //  return response()->json(['data'=>$data]);
     }
     
     
     
         public function get_all_pages_meta_info(Request $request)
     {
        //  print_r($request->all());
         $customer_id=$request->customer_id;
         $data = DB::table('pages_meta_info')->where('customer_id',$customer_id)->get();
         return response()->json(['data'=>$data]);
     }
     
      public function get_single_page_data(Request $request)
     {
        //  print_r($request->all());
        //  $customer_id=$request->customer_id;
         $data = DB::table('pages_meta_info')->where('id',$request->page_id)->first();
         return response()->json(['data'=>$data]);
     }
     
     
     
     
     
     
      public function submit_contact_details(Request $request)
     {
         $customer_id=$request->customer_id;
         $company_name=$request->company_name;
         $email=$request->email;
         $contact_number=$request->contact_number;
         $address=$request->address;
         $iata_number=$request->iata_number;
         $upload_iata_certificate=$request->upload_iata_certificate;
         $company_registration_number=$request->company_registration_number;
         $atol_number=$request->atol_number;
         $upload_atol_certificate=$request->upload_atol_certificate;
         
         
         
         
         $data = DB::table('contact_details')->where('customer_id',$customer_id)->first();
         if($data == '')
         {
            $data = DB::table('contact_details')->where('customer_id',$customer_id)->insert([
                'customer_id'=>$customer_id,
                'company_name'=>$company_name,
                'email'=>$email,
                'contact_number'=>$contact_number,
                'address'=>$address,
                'iata_number'=>$iata_number,
                'upload_iata_certificate'=>$upload_iata_certificate,
                'company_registration_number'=>$company_registration_number,
                'atol_number'=>$atol_number,
                'upload_atol_certificate'=>$upload_atol_certificate,
                ]);
                
                return response()->json(['contact_details'=>'Submit Data Successful','data'=>$data]);
         }
         else
         {
            $data = DB::table('contact_details')->where('customer_id',$customer_id)->update([
                'customer_id'=>$customer_id,
                'company_name'=>$company_name,
                'email'=>$email,
                'contact_number'=>$contact_number,
                'address'=>$address,
                'iata_number'=>$iata_number,
                'upload_iata_certificate'=>$upload_iata_certificate,
                'company_registration_number'=>$company_registration_number,
                'atol_number'=>$atol_number,
                'upload_atol_certificate'=>$upload_atol_certificate,
                ]);
                
                return response()->json(['contact_details'=>'Updated Successful','data'=>$data]);
         }
         
         
     }
     public function payment_gateways_list(Request $request)
     {
         $customer_id=$request->customer_id;
         $data = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
         return response()->json(['data'=>$data]);
     }
     public function submit_payment_gateways(Request $request)
     {
         $customer_id=$request->customer_id;
         $payment_gateway_title=$request->payment_gateway_title;
         $email=$request->email;
         $primary_key=$request->primary_key;
         $auth_key=$request->auth_key;
         $data = DB::table('payment_gateways')->insert([
             'customer_id'=>$customer_id,
             'payment_gateway_title'=>$payment_gateway_title,
             'email'=>$email,
             'primary_key'=>$primary_key,
             'auth_key'=>$auth_key,
             ]);
         return response()->json(['message'=>'success','data'=>$data]);
     }
     
     public function edit_payment_gateways(Request $request)
     {
         $id=$request->id;
         $customer_id=$request->customer_id;
         $payment_gateway_title=$request->payment_gateway_title;
         $email=$request->email;
         $primary_key=$request->primary_key;
         $auth_key=$request->auth_key;
         $data = DB::table('payment_gateways')->where('id',$id)->update([
             'customer_id'=>$customer_id,
             'payment_gateway_title'=>$payment_gateway_title,
             'email'=>$email,
             'primary_key'=>$primary_key,
             'auth_key'=>$auth_key,
             ]);
         return response()->json(['message'=>'success','data'=>$data]);
     }
     
     public function payment_mode_list(Request $request)
     {
         $customer_id=$request->customer_id;
         $data = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
         return response()->json(['data'=>$data]);
     }
     public function submit_payment_mode(Request $request)
     {
         $customer_id=$request->customer_id;
         $payment_mode=$request->payment_mode;
        
         $data = DB::table('payment_modes')->insert([
             'customer_id'=>$customer_id,
             'payment_mode'=>$payment_mode,
             
             ]);
         return response()->json(['message'=>'success','data'=>$data]);
     }
     
     public function edit_payment_mode(Request $request)
     {
         $id=$request->id;
         $customer_id=$request->customer_id;
         $payment_mode=$request->payment_mode;
         
         $data = DB::table('payment_modes')->where('id',$id)->update([
             'customer_id'=>$customer_id,
             'payment_mode'=>$payment_mode,
             
             ]);
         return response()->json(['message'=>'success','data'=>$data]);
     }
     
     
          public function third_party_commession(Request $request)
     {
         $customer_id=$request->customer_id;
    
         $customer = CustomerSubcription::where('id','!=',$customer_id)->get();
         $data = DB::table('3rd_party_commissions')->where('customer_id',$customer_id)->get();
         return response()->json(['message'=>'success','data'=>$data,'customer'=>$customer]);
     }
     
         public function third_party_commission_payable(Request $request)
     {
         $customer_id=$request->customer_id;
    
         $customer = CustomerSubcription::all();
        //  dd($customer);
         $data = DB::table('3rd_party_commissions')
                    ->join('customer_subcriptions','customer_subcriptions.id','3rd_party_commissions.third_party_id')
                    ->where('3rd_party_commissions.customer_id',$customer_id)
                    ->select('3rd_party_commissions.*','customer_subcriptions.company_name')
                    ->get();
         return response()->json(['message'=>'success','data'=>$data,'customer'=>$customer]);
     }
     
         public function third_party_commission_receivable(Request $request)
     {
         $customer_id=$request->customer_id;
    
         $customer = '';
         $data = DB::table('3rd_party_commissions')
                    ->join('customer_subcriptions','customer_subcriptions.id','3rd_party_commissions.customer_id')
                    ->where('3rd_party_commissions.third_party_id',$customer_id)
                    ->select('3rd_party_commissions.*','customer_subcriptions.company_name')
                    ->get();
         return response()->json(['message'=>'success','data'=>$data,'customer'=>$customer]);
     }
     
           public function third_party_payable_ledger(Request $request)
     {
         $customer_id=$request->customer_id;
    
        $provider_name = CustomerSubcription::where('id',$request->provider_id)->select('company_name')->first();
         $data = DB::table('3rd_party_package_book_ledger')
                    ->leftJoin('tours','tours.id','3rd_party_package_book_ledger.package_id')
                    ->join('customer_subcriptions','customer_subcriptions.id','3rd_party_package_book_ledger.package_owner')
                    ->where('3rd_party_package_book_ledger.package_owner',$customer_id)
                    ->where('3rd_party_package_book_ledger.package_request',$request->provider_id)
                    ->select('3rd_party_package_book_ledger.*','tours.title','tours.currency_symbol','customer_subcriptions.webiste_Address')
                    ->orderBy('3rd_party_package_book_ledger.id')
                    ->get();
         return response()->json(['message'=>'success','data'=>$data,'company_name'=>$provider_name]);
     }
     
         public function third_party_receivable_ledger(Request $request)
     {
         $customer_id=$request->customer_id;
    
         $provider_name = CustomerSubcription::where('id',$request->provider_id)->select('company_name')->first();
        
                    
         $data = DB::table('3rd_party_package_book_ledger')
                    ->leftJoin('tours','tours.id','3rd_party_package_book_ledger.package_id')
                    ->join('customer_subcriptions','customer_subcriptions.id','3rd_party_package_book_ledger.package_owner')
                    ->where('3rd_party_package_book_ledger.package_owner',$request->provider_id)
                    ->where('3rd_party_package_book_ledger.package_request',$customer_id)
                    ->select('3rd_party_package_book_ledger.*','tours.title','tours.currency_symbol','customer_subcriptions.webiste_Address')
                    ->orderBy('3rd_party_package_book_ledger.id')
                    ->get();
         return response()->json(['message'=>'success','data'=>$data,'company_name'=>$provider_name]);
     }
     
     
     
           public function third_party_packages_selection(Request $request)
     {
          $customer_id=$request->customer_id;
            // print_r($request->all());
          $customer = CustomerSubcription::where('id',$customer_id)->get();
          $data = DB::table('3rd_party_commissions')->where('third_party_id',$customer_id)->get();
          
        //   print_r($data);
        //   die;
          $customers_data = [];
          foreach($data as $data_res){
                $single_customer = DB::table('customer_subcriptions')->where('id',$data_res->customer_id)->select('id','name','lname')->get();
                array_push($customers_data,$single_customer);
          }
          return response()->json(['message'=>'success','data'=>$data,'customer'=>$customers_data]);
     }
     
      public function third_party_packages_approve(Request $request)
     {
          $customer_id=$request->customer_id;
            // print_r($request->all());
          $customer = CustomerSubcription::where('id',$customer_id)->get();
          $data = DB::table('3rd_party_commissions')
                    ->where('id', $request->id)
                    ->update(['status' => $request->status]);
          if($data){
              return response()->json(['message'=>'success','data'=>$data]);
          }else{
              return response()->json(['message'=>'error','data'=>$data]);
          }
        //   $customers_data = [];
        //   foreach($data as $data_res){
        //         $single_customer = DB::table('customer_subcriptions')->where('id',$data_res->customer_id)->select('id','name','lname')->get();
        //         array_push($customers_data,$single_customer);
        //   }
        //   return response()->json(['message'=>'success','data'=>$data,'customer'=>$customers_data]);
     }
        public function submit_other_provider(Request $request)
     {
         $customer_id=$request->customer_id;
     $third_party_name=$request->third_party_name;
      $third_party_id=$request->third_party_id;
       $commission=$request->commission;
       $data = DB::table('3rd_party_commissions')->insert(['customer_id'=>$customer_id,'third_party_id'=>$third_party_id,'third_party_name'=>$third_party_name,'commission'=>$commission,]);
         return response()->json(['message'=>'success','data'=>$data]);
     }
     
      public function submit_all_provider(Request $request)
     {
         $customer_id=$request->customer_id;
     $third_party_name=$request->third_party_name;
     
       $commission=$request->commission;
       $data = DB::table('3rd_party_commissions')->insert(['customer_id'=>$customer_id,'third_party_name'=>$third_party_name,'commission'=>$commission]);
         return response()->json(['message'=>'success','data'=>$data]);
     }
      public function delete_provider(Request $request)
     {
         $id=$request->id;
       $data = DB::table('3rd_party_commissions')->where('id',$id)->delete();
         return response()->json(['message'=>'success','data'=>$data]);
     }
      public function edit_provider(Request $request)
     {
         $id=$request->id;
         $customer_id=$request->customer_id;
         $customer = CustomerSubcription::where('id','!=',$customer_id)->get();
       $data = DB::table('3rd_party_commissions')->where('id',$id)->first();
         return response()->json(['message'=>'success','data'=>$data,'customer'=>$customer]);
     }
     
      public function submit_edit_provider(Request $request)
     {
         $id=$request->id;
         $customer_id=$request->customer_id;
     $third_party_name=$request->third_party_name;
      $third_party_id=$request->third_party_id;
       $commission=$request->commission;
       $data = DB::table('3rd_party_commissions')->where('id',$id)->update(['customer_id'=>$customer_id,'third_party_id'=>$third_party_id,'third_party_name'=>$third_party_name,'commission'=>$commission,]);
         return response()->json(['message'=>'success','data'=>$data]);
     }
     
     
    //
    public function showSubscriptionForm(){
        $all_countries = country::all();
        return view('template/frontend/userdashboard/pages/customer_subcription/customer_subcription',compact('all_countries'));
    }

    public function index(){
        $customers = CustomerSubcription::all();
        return response()->json([
            'status'=>'success',
            'data'=> $customers
        ]);
    }

    public function create(Request $request){
        // dd($request->hotel_Booking_Tag);
        
        if($request->file('img')){
            // echo "Enter here ";
            $picture_name               = $request->file('img');
            $namegen                    = hexdec(uniqid());
            $img_ext                    = strtolower($picture_name->getClientOriginalExtension());
            $img_name                   = $namegen.".".$img_ext;
            $up_location                = 'public/images/customerSubcription/';
            $last_img                   = $up_location.$img_name;
            $file_upload                = $picture_name->move($up_location,$img_name);
            
            if($file_upload){
                $token                  = $random = Str::random(40)."-".Str::random(60)."-".Str::random(25)."-".Str::random(75)."-".Str::random(100)."-".Str::random(75)."-".Str::random(25)."-".Str::random(50)."-".Str::random(50);
                $result                 = CustomerSubcription::create([
                    'name'              => $request->name,
                    'email'             => $request->email,
                    'password'          => Hash::make($request->password),
                    'lname'             => $request->lname,
                    'phone'             => $request->phone,
                    'company_logo'      => $img_name,
                    'company_name'      => $request->company_name,
                    'webiste_Address'   => $request->webiste_Address,
                    'Auth_key'          => $token,
                    'country'           => $request->country,
                    'city'              => $request->city,
                    'hotel_Booking_Tag' => $request->hotel_Booking_Tag ?? 'HH',
                    'status'            => 1
                ]);
                
                if($result){
                    return redirect()->intended('super_admin/customer_subcription')->with('success','Registered Successfully');
                }
                
            }
        }
    }

    public function access_url(Request $request){
        $token=$request->token;

     

        $credentials = $request->validate([
            'token' => ['required'],
        ]);
 
    
            $userData = CustomerSubcription::where('Auth_key',$token)->first();
            if($userData){
                if($userData->status == 1){
                    return response()->json([
                        'status' =>'Success',
                        'data'=> $userData
                    ]);
                }

            }
          
          
            
                
         

        return response()->json([
            'status' =>'Failed',
            'message'=> 'Your Key is not match or Your are Blocked'
        ]);
    }




    public function login_old(Request $request){
        $data       = CustomerSubcription::where('Auth_key',$request->token)->first();
        $token_get  = $data->Auth_key;
        $this->validate($request,[
            'email'     => 'required',
            'password'  => 'required',
            'token'     => 'required',
        ]);
        $token_rq=$request->token;
        if($token_get == $token_rq){
            $data = CustomerSubcription::where('Auth_key',$request->token)->first();
            $credentials = $request->only('email', 'password');
            if(Auth::guard('customer')->attempt($credentials)){
                return response()->json([
                    'status'    => 'success',
                    'data'      => $data,
                    'message'   => 'SuccessFUl Login Your Account!'
                ]);
            }
            else{
                return response()->json([
                    'status'    => 'Failed',
                    'message'   => 'Invalid Token'
                ]);
            }
        }
    }
    
    public function login(Request $request){
        $select_users = $request->select_users;
        
        if($select_users == 'MainUser'){
            $data           = CustomerSubcription::where('Auth_key',$request->token)->first();
            // dd($request->token);
            $token_get      = $data->Auth_key;
            $email_get      = $data->email;
            $password_get   = $data->password;
            
            $this->validate($request,[
                'email'     => 'required',
                'password'  => 'required',
                'token'     => 'required',
            ]);
            $token_rq       = $request->token;
            $email_rq       = $request->email;
            $password_rq    = $request->password;
            
            if($token_get == $token_rq){
                if($email_rq == $email_get){
                    if(Hash::check($password_rq, $password_get)){
                        $data = CustomerSubcription::where('Auth_key',$request->token)->first();
                        $credentials        = $request->only('email', 'password');
                        if(Auth::guard('customer')->attempt($credentials)){
                            
                            $atol_Data      = DB::table('addAtolFlightPackage')->join('addAtol','addAtolFlightPackage.token','addAtol.token')->where('addAtolFlightPackage.token',$request->token)->where('addAtol.token',$request->token)->first();
                            
                            return response()->json([
                                'status'    => 'success',
                                'data'      => $data,
                                'atol_Data' => $atol_Data,
                                'message'   => 'Successfully Login Your Account!'
                            ]);
                        }
                        else{
                            return response()->json([
                                'status'    => 'Failed',
                                'message'   => 'Invalid Credentials!',
                            ]);
                        }
                    }else{
                        return response()->json([
                            'status'        => 'Failed',
                            'message'       => 'Please Enter Correct Password!',
                        ]);
                    }
                }else{
                    return response()->json([
                        'status'            => 'Failed',
                        'message'           => 'Please Enter Correct Email!',
                    ]);
                }
            }else{
                return response()->json([
                    'status'                => 'Failed',
                    'message'               => 'Invalid Token!',
                ]);
            }
        }
        
        if($select_users == 'SubUser'){
            $data           = CustomerSubcription::where('Auth_key',$request->token)->first(); 
            $token_get      = $data->Auth_key;
            $customer_id    = $data->id;
            
            $this->validate($request,[
                'email'     => 'required',
                'password'  => 'required',
                'token'     => 'required',
            ]);
            $token_rq       = $request->token;
            $email_rq       = $request->email;
            $password_rq    = $request->password;
            
            $data           = RoleManager::where('customer_id',$customer_id)->where('email',$request->email)->first();
            // dd($data);
            if(isset($data->email)){
                $email_get                      = $data->email;
                $password_get                   = $data->password;
                if($token_get == $token_rq  && $email_rq == $email_get){
                    if(Hash::check($password_rq, $password_get)){
                        $parent_data            = CustomerSubcription::where('Auth_key',$request->token)->first();
                        $credentials            = $request->only('email','password');
                        if(Auth::guard('Role_manager')->attempt($credentials)){
                            $atol_Data          = DB::table('addAtolFlightPackage')->join('addAtol','addAtolFlightPackage.token','addAtol.token')->where('addAtolFlightPackage.token',$request->token)->where('addAtol.token',$request->token)->first();
                            
                            return response()->json([
                                'status'        =>'success',
                                'data'          => $data,
                                'parent_data'   => $parent_data,
                                'atol_Data'     => $atol_Data,
                                'message'       => 'Successfully Login Your Account!'
                            ]);
                        }
                        else{
                            return response()->json([
                                'status'        => 'Failed',
                                'message'       => 'Invalid Credentials'
                            ]);
                        }
                    }else{
                        return response()->json([
                            'status'            => 'Failed',
                            'message'           => 'Invalid Password'
                        ]);                    
                    }
                }else{
                    return response()->json([
                        'status'                => 'Failed',
                        'message'               => 'Invalid Token'
                    ]);
                }
            }else{
                return response()->json([
                    'status'                    => 'Failed',
                    'message'                   => 'Invalid Email'
                ]);
            }
        }
        
        // if($select_users == 'Company'){
        //     $data           = CustomerSubcription::where('Auth_key',$request->token)->first(); 
        //     $token_get      = $data->Auth_key;
        //     $customer_id    = $data->id;
            
        //     $this->validate($request,[
        //         'email'     => 'required',
        //         'password'  => 'required',
        //         'token'     => 'required',
        //     ]);
        //     $token_rq       = $request->token;
        //     $email_rq       = $request->email;
        //     $password_rq    = $request->password;
            
        //     $data           = companies::where('customer_id',$customer_id)->where('email',$request->email)->first();
        //     // dd($data);
        //     if(isset($data->email)){
        //         $email_get                      = $data->email;
        //         $password_get                   = $data->password;
        //         if($token_get == $token_rq  && $email_rq == $email_get){
        //             if(Hash::check($password_rq, $password_get)){
        //                 $parent_data            = CustomerSubcription::where('Auth_key',$request->token)->first();
        //                 $credentials            = $request->only('email','password');
        //                 if(Auth::guard('companies')->attempt($credentials)){
                            
        //                     $atol_Data      = DB::table('addAtolFlightPackage')->join('addAtol','addAtolFlightPackage.token','addAtol.token')->where('addAtolFlightPackage.token',$request->token)->where('addAtol.token',$request->token)->first();
                            
        //                     return response()->json([
        //                         'status'        =>'success',
        //                         'data'          => $data,
        //                         'parent_data'   => $parent_data,
        //                         'atol_Data'     => $atol_Data,
        //                         'message'       => 'Successfully Login Your Account!'
        //                     ]);
        //                 }
        //                 else{
        //                     return response()->json([
        //                         'status'        => 'Failed',
        //                         'message'       => 'Invalid Credentials'
        //                     ]);
        //                 }
        //             }else{
        //                 return response()->json([
        //                     'status'            => 'Failed',
        //                     'message'           => 'Invalid Password'
        //                 ]);                    
        //             }
        //         }else{
        //             return response()->json([
        //                 'status'                => 'Failed',
        //                 'message'               => 'Invalid Token'
        //             ]);
        //         }
        //     }else{
        //         return response()->json([
        //             'status'                    => 'Failed',
        //             'message'                   => 'Invalid Email'
        //         ]);
        //     }
        // }
    }
    
    public function login_SS(Request $request){
        // dd('OK');
        
        $select_users   = $request->select_users;
        $country_Name   = '';
        $city_Name      = '';
        
        if($select_users == 'MainUser'){
            // $data           = CustomerSubcription::where('Auth_key',$request->token)->first();
            $data           = CustomerSubcription::where('email',$request->email)->first();
            // $token_get      = $data->Auth_key;
            $email_get      = $data->email;
            $password_get   = $data->password;
            
            $this->validate($request,[
                'email'     => 'required',
                'password'  => 'required',
                'token'     => 'required',
            ]);
            $token_rq       = $request->token;
            $email_rq       = $request->email;
            $password_rq    = $request->password;
            
            if($email_rq == $email_get){
                if(Hash::check($password_rq, $password_get)){
                    // dd('ok');
                    $data           = CustomerSubcription::where('email',$request->email)->first();
                    $credentials    = $request->only('email', 'password');
                    if(Auth::guard('customer')->attempt($credentials)){
                        
                        if(isset($data->country) && $data->country != null && $data->country != ''){
                            $country_Details    = DB::table('countries')->where('id',$data->country)->first();
                            $country_Name       = $country_Details->name;
                        }
                        
                        if(isset($data->city) && $data->city != null && $data->city != ''){
                            $city_Details       = DB::table('cities')->where('id',$data->city)->first();
                            $city_Name          = $city_Details->name;
                        }
                        
                        $atol_Data = DB::table('addAtolFlightPackage')->join('addAtol','addAtolFlightPackage.token','addAtol.token')->where('addAtolFlightPackage.token',$request->token)->where('addAtol.token',$request->token)->first();
                        
                        return response()->json([
                            'status'        => 'success',
                            'data'          => $data,
                            'atol_Data'     => $atol_Data,
                            'country_Name'  => $country_Name,
                            'city_Name'     => $city_Name,
                            'message'       => 'Successfully Login Your Account!'
                        ]);
                    }
                    else{
                        return response()->json([
                            'status' => 'Failed',
                            'message'=> 'Invalid Credentials!'
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' => 'Failed',
                        'message'=> 'Invalid Password!'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'Failed',
                    'message'=> 'Invalid Email!'
                ]);
            }
        }
        
        if($select_users == 'SubUser'){
            // $data           = CustomerSubcription::where('Auth_key',$request->token)->first(); 
            $data           = CustomerSubcription::where('email',$request->email)->first();
            // $token_get      = $data->Auth_key;
            $customer_id    = $data->id;
            
            $this->validate($request,[
                'email'     => 'required',
                'password'  => 'required',
                'token'     => 'required',
            ]);
            $token_rq       = $request->token;
            $email_rq       = $request->email;
            $password_rq    = $request->password;
            
            $data           = RoleManager::where('customer_id',$customer_id)->where('email',$request->email)->first();
            $email_get      = $data->email;
            $password_get   = $data->password;
            
            if($email_rq == $email_get){
                if(Hash::check($password_rq, $password_get)){
                    $parent_data    = CustomerSubcription::where('email',$request->email)->first();
                    $credentials    = $request->only('email','password');
                    if(Auth::guard('Role_manager')->attempt($credentials)){
                        
                        if(isset($data->country) && $data->country != null && $data->country != ''){
                            $country_Details    = DB::table('countries')->where('id',$data->country)->first();
                            $country_Name       = $country_Details->name;
                        }
                        
                        if(isset($data->city) && $data->city != null && $data->city != ''){
                            $city_Details       = DB::table('cities')->where('id',$data->city)->first();
                            $city_Name          = $city_Details->name;
                        }
                        
                        $atol_Data = DB::table('addAtolFlightPackage')->join('addAtol','addAtolFlightPackage.token','addAtol.token')->where('addAtolFlightPackage.token',$request->token)->where('addAtol.token',$request->token)->first();
                        
                        return response()->json([
                            'status'        =>'success',
                            'data'          => $data,
                            'parent_data'   => $parent_data,
                            'atol_Data'     => $atol_Data,
                            'country_Name'  => $country_Name,
                            'city_Name'     => $city_Name,
                            'message'       => 'SuccessFUl Login Your Account!'
                        ]);
                    }
                    else{
                        return response()->json([
                            'status' =>'Failed',
                            'message'=> 'Invalid Credentials!'
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' =>'Failed',
                        'message'=> 'Invalid Password!'
                    ]);                    
                }
            }else{
                return response()->json([
                    'status' =>'Failed',
                    'message'=> 'Invalid Email!'
                ]);
            }   
        }
    }

    public function update_web_content(Request $request){
        $credentials = $request->validate([
            'token' => ['required'],
        ]);

        
        $captionImages = json_decode($request->imagesCaption);
        $SliderImages = json_decode($request->images);
        $sliderData = [$captionImages,$SliderImages];


        $caption_array = 

        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            if($userData->status == 1){
                $id = $userData->id;
                $result = CustomerSubcription::find($id)->update([
                    'slider_images' => json_encode($sliderData),
                    'page_titile' => $request->pageTitle,
                    'page_content1' => $request->content1,
                    'page_content2' => $request->content2,
                    'page_content3' => $request->content3,
                    'page_content4' => $request->content4
                ]);
                
                if($result){
                    return response()->json([
                        'status' =>'Success',
                        'data'=> $result
                    ]);
                }else{
                    return response()->json([
                        'status' =>'error',
                        'data'=> 'Something went worng try again'
                    ]);
                }
               
            }

        }

    return response()->json([
        'status' =>'Failed',
        'message'=> 'Your Key is not match or Your are Blocked'
    ]);
}

    public function view_web_content(Request $request){
        $credentials = $request->validate([
            'token' => ['required'],
        ]);


        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('page_titile','slider_images',
        'page_content1','page_content2','page_content3','page_content4','status')->first();
        
        if($userData){
            if($userData->status == 1){
                return response()->json([
                    'status' =>'Success',
                    'data'=> $userData
                ]);
            }

        }

    return response()->json([
        'status' =>'Failed',
        'message'=> 'Your Key is not match or Your are Blocked'
    ]);
}

    public function update_web_cont(Request $request){
        $credentials = $request->validate([
            'token' => ['required'],
        ]);

        



        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            if($userData->status == 1){
                $id = $userData->id;
                $result = CustomerSubcription::find($id)->update([
                    'page_titile' => $request->pageTitle,
                    'page_content1' => $request->content1,
                    'page_content2' => $request->content2,
                    'page_content3' => $request->content3,
                    'page_content4' => $request->content4
                ]);
                
                if($result){
                    return response()->json([
                        'status' =>'Success',
                        'data'=> $result
                    ]);
                }else{
                    return response()->json([
                        'status' =>'error',
                        'data'=> 'Something went worng try again'
                    ]);
                }
               
            }

        }

    return response()->json([
        'status' =>'Failed',
        'message'=> 'Your Key is not match or Your are Blocked'
    ]);
}



    public function slider_Images(Request $request){
        $credentials = $request->validate([
            'token' => ['required'],
        ]);


        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('slider_images','status')->first();
        if($userData){
            if($userData->status == 1){
                return response()->json([
                    'status' =>'Success',
                    'data'=> $userData
                ]);
            }

        }

    return response()->json([
        'status' =>'Failed',
        'message'=> 'Your Key is not match or Your are Blocked'
    ]);
}



    public function slider_Images_update(Request $request){
        $credentials = $request->validate([
            'token' => ['required'],
        ]);

        
        $captionImages = json_decode($request->imagesCaption);
        $SliderImages = json_decode($request->images);
        $sliderData = [$captionImages,$SliderImages];



        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            if($userData->status == 1){
                $id = $userData->id;
                $result = CustomerSubcription::find($id)->update([
                    'slider_images' => json_encode($sliderData),
                ]);
                
                if($result){
                    return response()->json([
                        'status' =>'Success',
                        'data'=> $result
                    ]);
                }else{
                    return response()->json([
                        'status' =>'error',
                        'data'=> 'Something went worng try again'
                    ]);
                }
               
            }

        }

    return response()->json([
        'status' =>'Failed',
        'message'=> 'Your Key is not match or Your are Blocked'
    ]);
}


    public function fetch_top_categories(Request $request)
     {
        //  print_r($request->all());
          $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            if($userData->status == 1){
                // echo "customer id is ".$userData->id;
                
                $fetch_top_activities_country = '';
                $final_data = [];
                if($request->request_page == 'top_categories'){
                       $category_count_arr = [];
                        $categories = DB::table('categories')->where('customer_id',$userData->id)->select('id','image','title','description')->orderBy('placement', 'asc')->limit(5)->get();
                        foreach($categories as $cat_res){
                            $result = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$userData->id)->where('tours.categories',$cat_res->id)->count();
                            array_push($category_count_arr,$result);
                        }
                        
                        $final_data = [$categories,$category_count_arr];
                }else{
                      $fetch_top_activities_country = DB::table('country_activities_count')
                        ->join('countries', 'countries.id', '=', 'country_activities_count.country_id')
                        ->where('customer_id',$userData->id)
                        ->select('country_activities_count.*', 'countries.name')
                        ->orderBy('activity_count', 'desc')->limit(10)
                        ->get();
                }
             
                
                
               
                
                
                
         
                    	
                // //     	$query = DB::table('category_issue')
                // //         ->select(array('issues.*', DB::raw('COUNT(issue_subscriptions.issue_id) as followers')))
                // //         ->where('category_id', '=', 1)
                // //         ->join('issues', 'category_issue.issue_id', '=', 'issues.id')
                // //         ->left_join('issue_subscriptions', 'issues.id', '=', 'issue_subscriptions.issue_id')
                // //         ->group_by('issues.id')
                // //         ->order_by('followers', 'desc')
                // //         ->get();
                        
                //         // $query = DB::table('tours')
                //         // ->select(array('tours.id', DB::raw('COUNT(tours.categories) as total_tours')))
                //         // ->join('categories', 'categories.id', '=', 'tours.id')
                //         // ->groupBy('categories.id')
                //         // ->orderBy('total_tours', 'desc')
                //         // ->get();
                        
                //           $query = DB::table('categories')
                //         ->join('tours', 'tours.categories', '=', 'categories.id')
                //         ->orderBy('categories.id', 'desc')
                //         ->select('categories.id as cat_id','tours.id','tours.categories',DB::raw('COUNT(tours.categories) as total_cat'))
                //         ->groupBy('tours.id')
                //         ->get();
                        
                        // print_r($query);
              
                return response()->json(['message'=>'success','data'=>$final_data,'top_country_activites'=>$fetch_top_activities_country]);
            }
        }
        //  $id=$request->id;
    //      $customer_id=$request->customer_id;
    //      $customer = CustomerSubcription::where('id','!=',$customer_id)->get();
    //   $data = DB::table('3rd_party_commissions')->where('id',$id)->first();
    //      
     }
     
    public function fetch_all_categories(Request $request){
        DB::beginTransaction();
        try {
            $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
            if($userData){
                if($userData->status == 1){
                    $categories = DB::table('categories')->where('customer_id',$userData->id)->select('id','image','title','description')->orderBy('placement', 'asc')->get();
                    return response()->json(['message'=>'success','data'=>$categories]);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            $result = false;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function fetch_all_categories_wi_packages(Request $request){
        DB::beginTransaction();
        try {
            $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
            if($userData){
                if($userData->status == 1){
                    // $categories = DB::table('categories')->where('customer_id',$userData->id)->select('id','image','title','description')->orderBy('placement', 'asc')->get();
                    $categories = DB::table('categories')
                        ->join('tours', 'categories.id', '=', 'tours.categories')
                        ->where('tours.tour_feature',0)
                        ->where('categories.customer_id',$userData->id)
                        ->select('categories.*')
                        ->groupBy('categories.id')
                        ->get();
                    return response()->json(['message'=>'success','data'=>$categories]);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            $result = false;
            return response()->json(['message'=>'error']);
        }
    }
    
    
     
       public function fetch_all_attributes(Request $request)
     {
        //  print_r($request->all());
      
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            if($userData->status == 1){
                $attributes = DB::table($request->table)->where('customer_id',$userData->id)->select('id','title')->get();
                return response()->json(['message'=>'success','data'=>$attributes]);
                

            }
        }
        
     }
     
      public function filter_tour(Request $request)
     {
        
          $request_data = json_decode($request->request_data);
          //print_r($request_data);die();
       
         
        
         
          $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            if($userData->status == 1){
                
                if(isset($request_data->min))
                {
                 $tours = DB::table('tours')
                        ->join('tours_2','tours.id','tours_2.tour_id')
                        ->where('tours.customer_id',$userData->id)
                        // ->where('tours.categories',$request_data->category)
                        // ->where('tours.start_date','>=',$start_date)
                        
                        ->Where(function($query) use($request_data) {
                            
                            $query->whereBetween('tours_2.quad_grand_total_amount',[$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.triple_grand_total_amount', [$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.double_grand_total_amount', [$request_data->min,$request_data->max]);
                          
                         })
                        ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours.tour_attributes','tours_2.flights_details','tours_2.flights_details_more')->orderBy('tours.created_at', 'desc')->get();
                        return response()->json(['message'=>'success','data'=>$tours]);   
                }
                if(isset($request_data->startype))
                {
                    //dd($request_data->startype);
                 $tours = DB::table('tours')
                        ->join('tours_2','tours.id','tours_2.tour_id')
                        ->where('tours.customer_id',$userData->id)
                        // ->where('tours.categories',$request_data->category)
                        // ->where('tours.start_date','>=',$start_date)
                        
                        ->Where(function($query) use($request_data) {
                           
                                $query->whereIn('tours.starts_rating',$request_data->startype);    
                           
                            
                          
                         })
                        ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours.tour_attributes','tours_2.flights_details','tours_2.flights_details_more')->orderBy('tours.created_at', 'desc')->get();
                        return response()->json(['message'=>'success','data'=>$tours]);   
                }
                
             
                   
                   
                
                
                

            }
        }
        
     }
         public function filter_tour_before(Request $request)
     {
        //  echo "this is call now ";
        //  dd($request->all());
          $request_data = json_decode($request->request_data);
          print_r($request_data);die();
        //  echo "The min price is ".$request_data->min." max price is ".$request_data->max;
         
        
          $start_date = date("Y-m-d", strtotime($request_data->start));
          $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            if($userData->status == 1){
                
                if(isset($request_data->request_page)){
                    
                    if($request_data->category){
                       
                        $tours = DB::table('tours')
                        ->join('tours_2','tours.id','tours_2.tour_id')
                        ->where('tours.customer_id',$userData->id)
                        ->where('tours.categories',$request_data->category)
                        ->where('tours.start_date','>=',$start_date)
                        
                        ->Where(function($query) use($request_data) {
                            
                            $query->whereBetween('tours_2.quad_grand_total_amount',[$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.triple_grand_total_amount', [$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.double_grand_total_amount', [$request_data->min,$request_data->max]);
                          
                         })
                        ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours.tour_attributes','tours_2.flights_details','tours_2.flights_details_more')->orderBy('tours.created_at', 'desc')->get();
                        return response()->json(['message'=>'success','data'=>$tours]);
                    }else{
                        $tours = DB::table('tours')
                        ->join('tours_2','tours.id','tours_2.tour_id')
                        ->where('tours.customer_id',$userData->id)
                        ->where('tours.start_date','>=',$start_date)
                        
                        ->Where(function($query) use($request_data) {
                            
                             $query->whereBetween('tours_2.quad_grand_total_amount',[$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.triple_grand_total_amount', [$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.double_grand_total_amount', [$request_data->min,$request_data->max]);
                          
                         })
                        ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours.tour_attributes','tours_2.flights_details','tours_2.flights_details_more')->orderBy('tours.created_at', 'desc')->get();
                        return response()->json(['message'=>'success','data'=>$tours]);
                    }
                   
                }else{
                    
                    $tours = DB::table('tours')
                    ->join('tours_2','tours.id','tours_2.tour_id')
                    ->where('tours.customer_id',$userData->id)
                    ->where('tours.categories',$request_data->category)
                    ->where('tours.start_date','>=',$start_date)
                    
                    ->Where(function($query) use($request_data) {
                        
                        $query->whereBetween('tours_2.quad_grand_total_amount',[$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.triple_grand_total_amount', [$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.double_grand_total_amount', [$request_data->min,$request_data->max]);
                      
                     })
                   ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours.tour_attributes','tours_2.flights_details','tours_2.flights_details_more')->orderBy('tours.created_at', 'desc')->get();
                    
                    // print_r($tours);
                    // die;
                    return response()->json(['message'=>'success','data'=>$tours]);
                }
                
                

            }
        }
        
     }
     
     public function filter_activities(Request $request)
     {
        //  echo "this is call now ";
        //  print_r($request->all());
          $request_data = json_decode($request->request_data);
        // //  echo "The min price is ".$request_data->min." max price is ".$request_data->max;
        // print_r($request_data);

          $start_date = date("Y-m-d", strtotime($request_data->start));
        //   $today_date = date("Y-m-d");
          $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            if($userData->status == 1){
                
                if(isset($request_data->request_page) && $request_data->request_page == 'country_activity'){
                    // echo "Enter here in if ";
                     $tours = DB::table('new_activites')
                        ->where('customer_id',$userData->id)
                         ->where('country',$request_data->city)
                         ->where('end_date','>=',$start_date)
                        ->whereBetween('sale_price', [$request_data->min,$request_data->max])
                        ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','tour_attributes','start_date')->orderBy('created_at', 'desc')->get();
                        
                
                    $relatedtours = [];
                    return response()->json(['message'=>'success','data'=>$tours,'relatedTours'=>$relatedtours]);
                }if(isset($request_data->request_page) && $request_data->request_page == 'all_activities'){
                    // echo "Enter here in  inif ";
                     $tours = DB::table('new_activites')
                        ->where('customer_id',$userData->id)
                         ->where('end_date','>=',$start_date)
                        ->whereBetween('sale_price', [$request_data->min,$request_data->max])
                        ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','tour_attributes','start_date')->orderBy('created_at', 'desc')->get();
                        
                
                    $relatedtours = [];
                    return response()->json(['message'=>'success','data'=>$tours,'relatedTours'=>$relatedtours]);
                }else{
                    // echo "Enter here in else ";
                      $tours = DB::table('new_activites')
                        ->where('customer_id',$userData->id)
                         ->where('location','LIKE','%'.''.$request_data->city.'%')
                        //  ->where('start_date','<=',$start_date)
                         ->where('end_date','>=',$start_date)
                        ->whereBetween('sale_price', [$request_data->min,$request_data->max])
                        ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','tour_attributes','start_date')->orderBy('created_at', 'desc')->get();
                        
                       $tomorrowDate = date('Y-m-d', strtotime('-1 days'));
                        $relatedtours =DB::table('new_activites')
                      ->where('location','LIKE','%'.''.$request_data->city.'%')
                      ->where('start_date','>=',$tomorrowDate)
                      ->where('customer_id','=',$userData->id)
                       ->whereBetween('sale_price', [$request_data->min,$request_data->max])
                        ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','tour_attributes','start_date')->orderBy('created_at', 'desc')->get();
                        
                        return response()->json(['message'=>'success','data'=>$tours,'relatedTours'=>$relatedtours]);
                }
              

            }
        }
        
     }
     
    
     
    public function  fetch_category_tour_wi_limit(Request $request){
        // print_r($request->all());
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $today_date = date('Y-m-d');
        $customer_id = $userData->id;
        $tours=     DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
                ->where('tours.start_date','>=',$today_date)->where('tours.customer_id',$customer_id)
                ->where('tours.categories',$request->cat_id)
                ->where('tours.tour_feature',0)
                ->select('tours.id','tours.title','tours_2.quad_grand_total_amount','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image',
                'tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.categories','tours.created_at','tours.start_date','tours.end_date')
                
                ->orderBy('tours.created_at', 'desc')->limit($request->limit)->get();
        
        $tours_enquire=     DB::table('tours_enquire')->join('tours_2_enquire','tours_enquire.id','tours_2_enquire.tour_id')
                ->where('tours_enquire.start_date','>=',$today_date)->where('tours_enquire.customer_id',$customer_id)
                ->where('tours_enquire.categories',$request->cat_id)
                ->where('tours_enquire.tour_feature',0)
                ->select('tours_enquire.id','tours_enquire.title','tours_2_enquire.quad_grand_total_amount','tours_2_enquire.triple_grand_total_amount','tours_2_enquire.double_grand_total_amount','tours_enquire.tour_featured_image','tours_enquire.tour_banner_image',
                'tours_enquire.time_duration','tours_enquire.content','tours_enquire.no_of_pax_days','tours_enquire.currency_symbol','tours_enquire.categories','tours_enquire.created_at')
                
                ->orderBy('tours_enquire.created_at', 'desc')->limit($request->limit)->get();
                
            if(isset($tours_enquire)){
                foreach($tours_enquire as $index => $toure_res){
                    $tours_enquire[$index]->package_type = 'enquire';
                }
            }
            
            $collection1 = collect($tours);
            $collection2 = collect($tours_enquire);

            $mergedCollection = $collection1->merge($collection2);
            $sortedCollection = $mergedCollection->sortByDesc('created_at');
            $tours = $sortedCollection->values()->all();
        // print_r($customer_id);die();
        // dd($tours);
        return response()->json(['message'=>'success','tours'=>$tours]);
    }
    
     public function insert_contact_us(Request $request)
     {
        //  echo "this is call now ";
        //  print_r($request->all());
          $request_data = json_decode($request->request_data);
        // //  echo "The min price is ".$request_data->min." max price is ".$request_data->max;

         
          $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            if($userData->status == 1){
                $result = DB::table('contact_us')->insert(['name'=>$request_data->name,
                                                'email'=>$request_data->email,
                                                'phone'=>$request_data->phone,
                                                'subject'=>$request_data->subject,
                                                'message'=>$request_data->message,
                                                ]);
                if($result){
                   return response()->json(['message'=>'success']);

                }else{
                   return response()->json(['message'=>'failed']);
                }

            }
        }
        
     }
    
    






    
}
